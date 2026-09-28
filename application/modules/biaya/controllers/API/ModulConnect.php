<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class ModulConnect extends REST_Controller
{
    protected $authEnabled = false;
    protected $authKeys = array();
    protected $authAllowedDrift = 300;
    protected $authIpWhitelist = array();
    protected $nonceStorePath = "";

    function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->database();
        session_write_close();
        require_once APPPATH . "modules/biaya/models/MdlBiayaTransaksi.php";

        $this->bootAuthConfig();
    }

    protected function bootAuthConfig()
    {
        $this->authEnabled = $this->config->item('modulconnect_auth_enabled') === true;
        $this->authKeys = $this->config->item('modulconnect_auth_keys');
        $this->authAllowedDrift = (int)$this->config->item('modulconnect_auth_allowed_drift');
        $this->authIpWhitelist = $this->config->item('modulconnect_auth_ip_whitelist');
        if (!is_array($this->authKeys)) {
            $this->authKeys = array();
        }
        if (!is_array($this->authIpWhitelist)) {
            $this->authIpWhitelist = array();
        }
        if ($this->authAllowedDrift < 30) {
            $this->authAllowedDrift = 300;
        }

        $noncePath = APPPATH . "cache/modulconnect_nonce";
        if (!is_dir($noncePath)) {
            @mkdir($noncePath, 0755, true);
        }
        $this->nonceStorePath = $noncePath;
    }

    protected function getHeaderValue($name)
    {
        $value = $this->input->get_request_header($name, true);
        if ($value === null) {
            return "";
        }

        return trim((string)$value);
    }

    protected function constantTimeEquals($known, $given)
    {
        if (function_exists('hash_equals')) {
            return hash_equals($known, $given);
        }

        if (strlen($known) !== strlen($given)) {
            return false;
        }

        $result = 0;
        for ($i = 0; $i < strlen($known); $i++) {
            $result |= ord($known[$i]) ^ ord($given[$i]);
        }

        return $result === 0;
    }

    protected function isIpAllowed()
    {
        if (sizeof($this->authIpWhitelist) < 1) {
            return true;
        }

        $ipAddress = $this->input->ip_address();
        return in_array($ipAddress, $this->authIpWhitelist);
    }

    protected function checkAndStoreNonce($keyId, $nonce, $timestamp)
    {
        if ($nonce === "") {
            return false;
        }
        if (!is_dir($this->nonceStorePath)) {
            return false;
        }

        $timeBucket = (int)floor($timestamp / $this->authAllowedDrift);
        $nonceHash = sha1($keyId . "|" . $nonce . "|" . $timeBucket);
        $nonceFile = $this->nonceStorePath . DIRECTORY_SEPARATOR . $nonceHash . ".nonce";

        if (file_exists($nonceFile)) {
            return false;
        }

        @file_put_contents($nonceFile, (string)$timestamp, LOCK_EX);
        if (!file_exists($nonceFile)) {
            return false;
        }

        // Best-effort cleanup file nonce lama.
        $expiry = time() - ($this->authAllowedDrift * 2);
        $files = @glob($this->nonceStorePath . DIRECTORY_SEPARATOR . "*.nonce");
        if (is_array($files) && sizeof($files) > 0) {
            foreach ($files as $filePath) {
                if (@filemtime($filePath) < $expiry) {
                    @unlink($filePath);
                }
            }
        }

        return true;
    }

    protected function verifySignedRequest($rawBody)
    {
        if ($this->authEnabled !== true) {
            return true;
        }

        if (!$this->isIpAllowed()) {
            $this->response(array("status" => 403, "message" => "IP not allowed"), 403);
            return false;
        }

        $keyId = $this->getHeaderValue("X-ModulConnect-Key");
        $signature = strtolower($this->getHeaderValue("X-ModulConnect-Signature"));
        $timestampRaw = $this->getHeaderValue("X-ModulConnect-Timestamp");
        $nonce = $this->getHeaderValue("X-ModulConnect-Nonce");

        if ($keyId === "" || $signature === "" || $timestampRaw === "" || $nonce === "") {
            $this->response(array("status" => 401, "message" => "Missing auth headers"), 401);
            return false;
        }

        if (!ctype_digit($timestampRaw)) {
            $this->response(array("status" => 401, "message" => "Invalid timestamp"), 401);
            return false;
        }

        if (!isset($this->authKeys[$keyId])) {
            $this->response(array("status" => 401, "message" => "Unknown key"), 401);
            return false;
        }

        $secret = (string)$this->authKeys[$keyId];
        if ($secret === "") {
            $this->response(array("status" => 500, "message" => "Server auth secret not configured"), 500);
            return false;
        }

        $timestamp = (int)$timestampRaw;
        if (abs(time() - $timestamp) > $this->authAllowedDrift) {
            $this->response(array("status" => 401, "message" => "Expired request"), 401);
            return false;
        }

        if (!$this->checkAndStoreNonce($keyId, $nonce, $timestamp)) {
            $this->response(array("status" => 401, "message" => "Replay request detected"), 401);
            return false;
        }

        $bodyHash = hash('sha256', (string)$rawBody);
        $stringToSign = implode("\n", array(
            strtoupper($this->input->method(true)),
            $this->uri->uri_string(),
            $timestampRaw,
            $nonce,
            $bodyHash,
        ));
        $expectedSignature = hash_hmac('sha256', $stringToSign, $secret);

        if (!$this->constantTimeEquals($expectedSignature, $signature)) {
            $this->response(array("status" => 401, "message" => "Invalid signature"), 401);
            return false;
        }

        return true;
    }

    protected function parseSignedPayload($payloadKey = "enc")
    {
        $rawBody = trim((string)$this->input->raw_input_stream);
        $enc = $this->input->post($payloadKey, false);
        if ($rawBody === "" && is_string($enc) && $enc !== "") {
            // Fallback untuk form-urlencoded: signature tetap mengikat isi payload.
            $rawBody = $enc;
        }

        if (!$this->verifySignedRequest($rawBody)) {
            return null;
        }

        $payload = array();
        if ($rawBody !== "") {
            $jsonPayload = json_decode($rawBody, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($jsonPayload)) {
                $payload = $jsonPayload;
            }
        }

        if (sizeof($payload) < 1) {
            if ($enc === null && isset($_GET[$payloadKey])) {
                // fallback transisi endpoint lama.
                $enc = $_GET[$payloadKey];
            }
            if (is_string($enc) && $enc !== "") {
                $payload = blobDecodeRequest($enc, array());
            }
        }

        if (!is_array($payload) || sizeof($payload) < 1) {
            $this->response(array("status" => 400, "message" => "Invalid payload"), 400);
            return null;
        }

        return $payload;
    }

    protected function sanitizeWhereMap($filters)
    {
        $safe = array();
        if (!is_array($filters) || sizeof($filters) < 1) {
            return $safe;
        }

        foreach ($filters as $key => $value) {
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $key)) {
                continue;
            }
            if (is_array($value) || is_object($value)) {
                continue;
            }
            $safe[$key] = $value;
        }

        return $safe;
    }

    protected function sanitizeUpdateMap($update)
    {
        $safe = array();
        if (!is_array($update) || sizeof($update) < 1) {
            return $safe;
        }

        foreach ($update as $key => $value) {
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $key)) {
                continue;
            }
            if (is_array($value) || is_object($value)) {
                continue;
            }
            $safe[$key] = $value;
        }

        return $safe;
    }

    protected function sanitizeIntList($input)
    {
        $ids = array();
        if (!is_array($input) || sizeof($input) < 1) {
            return $ids;
        }

        foreach ($input as $value) {
            $id = (int)$value;
            if ($id > 0) {
                $ids[$id] = $id;
            }
        }

        return array_values($ids);
    }

    protected function methodNotAllowed($allowedMethod)
    {
        $this->response(array(
            "status" => 405,
            "message" => "Method not allowed. Use " . strtoupper($allowedMethod),
        ), 405);
    }

    public function index_get()
    {
        $this->response(array(
            "status" => 200,
            "message" => "ModulConnect API online",
        ), 200);
    }

    public function api_extern_biaya_get()
    {
        $targetJenis = preg_replace('/[^a-zA-Z0-9_\\-]/', '', (string)$this->uri->segment(5));
        $tr = new MdlBiayaTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("sisa>100");
        $tmpSrc = $tr->lookupPaymentSrcByJenis($targetJenis)->result();
        $this->response($tmpSrc, 200);
    }

    public function api_select_biaya_get()
    {
        $masterTarget = preg_replace('/[^a-zA-Z0-9_\\-]/', '', (string)$this->uri->segment(5));
        $externID = preg_replace('/[^a-zA-Z0-9_\\-]/', '', (string)$this->uri->segment(6));
        $selectedTrID = (int)$this->uri->segment(7);

        $tr = new MdlBiayaTransaksi();
        $tr->setFilters(array());
        if ($externID !== "") {
            $tr->addFilter("extern_id='" . $externID . "'");
        }
        $tr->addFilter("sisa>100");
        if ($selectedTrID > 0) {
            $tr->addFilter("transaksi_id=" . $selectedTrID);
        }
        $tmpSrc = $tr->lookupPaymentSrcByJenis_joined($masterTarget)->result();
        $this->response($tmpSrc, 200);
    }

    public function api_return_biaya_get()
    {
        $this->response(array(), 200);
    }

    public function api_exec_principal_get()
    {
        $this->methodNotAllowed("POST");
    }

    /**
     * query bridge biaya oleh service principal
     */
    public function api_exec_principal_post()
    {
        $arrDatas = $this->parseSignedPayload("enc");
        if ($arrDatas === null) {
            return;
        }

        $where = $this->sanitizeWhereMap($arrDatas);
        if (sizeof($where) < 1) {
            $this->response(array("status" => 400, "message" => "Invalid filter payload"), 400);
            return;
        }

        $this->db->where($where);
        $vars = $this->db->get("biaya_transaksi_bridge")->result();
        if (count($vars) > 0) {
            $response = array(
                "data" => $vars,
                "status" => 200,
            );
        } else {
            $response = array(
                "data" => "empty",
                "status" => 404,
            );
        }

        $this->response((object)$response, 200);
    }

    public function api_update_bridge_get()
    {
        $this->methodNotAllowed("POST");
    }

    /**
     * update bridge biaya detail setelah dieksekusi pre so
     */
    public function api_update_bridge_post()
    {
        $arrDatas = $this->parseSignedPayload("enc");
        if ($arrDatas === null) {
            return;
        }

        $ids = isset($arrDatas["id"]) ? $this->sanitizeIntList($arrDatas["id"]) : array();
        $update = isset($arrDatas["update"]) ? $this->sanitizeUpdateMap($arrDatas["update"]) : array();
        if (sizeof($ids) < 1 || sizeof($update) < 1) {
            $this->response(array("status" => 400, "message" => "Invalid update payload"), 400);
            return;
        }

        $this->db->trans_start();
        $this->db->where_in("id", $ids);
        $updated = $this->db->update("biaya_transaksi_bridge", $update);
        $this->db->trans_complete();

        if (!$updated || $this->db->trans_status() === false) {
            $this->response(array("status" => 500, "message" => "Update failed"), 500);
            return;
        }

        $this->response(array(
            "status" => 200,
            "affected_rows" => $this->db->affected_rows(),
        ), 200);
    }

    public function api_update_masterBridge_get()
    {
        $this->methodNotAllowed("POST");
    }

    /**
     * update bridge biaya master setelah dieksekusi pre so
     */
    public function api_update_masterBridge_post()
    {
        $arrDatas = $this->parseSignedPayload("enc");
        if ($arrDatas === null) {
            return;
        }

        $ids = isset($arrDatas["id"]) ? $this->sanitizeIntList($arrDatas["id"]) : array();
        $update = isset($arrDatas["update"]) ? $this->sanitizeUpdateMap($arrDatas["update"]) : array();
        if (sizeof($ids) < 1 || sizeof($update) < 1) {
            $this->response(array("status" => 400, "message" => "Invalid update payload"), 400);
            return;
        }

        $this->db->trans_start();
        $this->db->where_in("referensi_id", $ids);
        $updated = $this->db->update("biaya_transaksi_bridge_master", $update);
        $this->db->trans_complete();

        if (!$updated || $this->db->trans_status() === false) {
            $this->response(array("status" => 500, "message" => "Update failed"), 500);
            return;
        }

        $this->response(array(
            "status" => 200,
            "affected_rows" => $this->db->affected_rows(),
        ), 200);
    }

    public function api_masterBridgePL_get()
    {
        $this->methodNotAllowed("POST");
    }

    /**
     * handling multi packinglist:
     * bila referensi dan packinglist belum ada, tambahkan data baru.
     */
    public function api_masterBridgePL_post()
    {
        $arrDatas = $this->parseSignedPayload("enc");
        if ($arrDatas === null) {
            return;
        }

        $required = array("cli_id", "principal_spd_id");
        foreach ($required as $key) {
            if (!isset($arrDatas[$key]) || $arrDatas[$key] === "") {
                $this->response(array("status" => 400, "message" => "Missing required field: " . $key), 400);
                return;
            }
        }

        $where = array(
            "cli_id" => $arrDatas["cli_id"],
            "principal_spd_id" => $arrDatas["principal_spd_id"],
        );

        $insertData = $this->sanitizeWhereMap($arrDatas);
        $insertData["dtime"] = date("Y-m-d H:i:s");
        $insertData["fulldate"] = date("Y-m-d");

        $this->db->trans_start();
        $this->db->where($where);
        $vars = $this->db->get("biaya_transaksi_bridge_terima_master")->result();
        if (count($vars) < 1) {
            $this->db->insert("biaya_transaksi_bridge_terima_master", $insertData);
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $this->response(array("status" => 500, "message" => "Save failed"), 500);
            return;
        }

        $this->response(array("status" => 200), 200);
    }
}
