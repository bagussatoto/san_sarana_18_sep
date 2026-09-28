<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Webhook extends CI_Controller
{

    public function __construct() {
        parent::__construct();
        $this->load->library("Transaksional");
    }
    /**
     * Validasi secret key
     */
    private function _validate_secret($secret) {
        $valid_secret = WEBHOOK_SECRET;//lihat di he_url
        return $secret === $valid_secret;
    }

    private function _is_ip_allowed() {
        $allowed_ips = $this->config->item('modulconnect_auth_ip_whitelist');
        if (!is_array($allowed_ips) || count($allowed_ips) < 1) {
            return true;
        }
        return in_array($this->input->ip_address(), $allowed_ips);
    }

    private function _constant_time_equals($known, $given) {
        if (function_exists('hash_equals')) {
            return hash_equals($known, $given);
        }

        if (strlen($known) !== strlen($given)) {
            return false;
        }

        $diff = 0;
        $len = strlen($known);
        for ($i = 0; $i < $len; $i++) {
            $diff |= ord($known[$i]) ^ ord($given[$i]);
        }

        return $diff === 0;
    }

    private function _get_request_id() {
        $requestId = trim((string)$this->input->get_request_header('X-Request-Id'));
        if ($requestId === '') {
            $requestId = strtoupper(substr(sha1(uniqid(mt_rand(), true)), 0, 20));
        }
        return $requestId;
    }

    private function _get_signature_secret() {
        $secret = $this->config->item('webhook_signature_secret');
        if (!is_string($secret)) {
            $secret = '';
        }
        $secret = trim($secret);
        if ($secret === '' && defined('WEBHOOK_SECRET')) {
            $secret = trim((string)WEBHOOK_SECRET);
        }

        return $secret;
    }

    private function _is_signature_required() {
        return $this->config->item('webhook_require_signature') === true;
    }

    private function _validate_signature($rawPayload, &$errorMessage) {
        $errorMessage = '';
        $secret = $this->_get_signature_secret();
        if ($secret === '') {
            if ($this->_is_signature_required()) {
                $errorMessage = 'Webhook signature secret belum dikonfigurasi.';
                return false;
            }
            return true;
        }

        $timestampHeader = trim((string)$this->input->get_request_header('X-Webhook-Timestamp'));
        $signatureHeader = trim((string)$this->input->get_request_header('X-Webhook-Signature'));
        if ($timestampHeader === '' || $signatureHeader === '') {
            if ($this->_is_signature_required()) {
                $errorMessage = 'Missing webhook signature header.';
                return false;
            }
            return true;
        }

        if (!ctype_digit($timestampHeader)) {
            $errorMessage = 'Invalid timestamp format.';
            return false;
        }

        $allowedDrift = (int)$this->config->item('webhook_signature_allowed_drift');
        if ($allowedDrift < 30) {
            $allowedDrift = 300;
        }
        $timestamp = (int)$timestampHeader;
        if (abs(time() - $timestamp) > $allowedDrift) {
            $errorMessage = 'Webhook timestamp is expired.';
            return false;
        }

        $expected = hash_hmac('sha256', $timestampHeader . '.' . (string)$rawPayload, $secret);
        if (!$this->_constant_time_equals($expected, $signatureHeader)) {
            $errorMessage = 'Invalid webhook signature.';
            return false;
        }

        return true;
    }

    private function _check_rate_limit($requestId) {
        $window = (int)$this->config->item('webhook_rate_limit_window');
        $maxRequest = (int)$this->config->item('webhook_rate_limit_max');
        if ($window < 10) {
            $window = 60;
        }
        if ($maxRequest < 1) {
            $maxRequest = 60;
        }

        $bucket = (int)floor(time() / $window);
        $ip = (string)$this->input->ip_address();
        $uri = (string)$this->uri->uri_string();
        $key = sha1($ip . "|" . $uri . "|" . $bucket);
        $path = APPPATH . 'cache/webhook_rate_limit';
        if (!is_dir($path)) {
            @mkdir($path, 0755, true);
        }
        $file = $path . DIRECTORY_SEPARATOR . $key . '.rl';
        $count = 0;

        $fp = @fopen($file, 'c+');
        if ($fp !== false) {
            if (@flock($fp, LOCK_EX)) {
                $raw = stream_get_contents($fp);
                $count = (int)$raw;
                if ($count >= $maxRequest) {
                    @flock($fp, LOCK_UN);
                    fclose($fp);
                    log_message('error', 'Webhook rate limit exceeded. request_id=' . $requestId . ' ip=' . $ip . ' uri=' . $uri);
                    return false;
                }

                $count++;
                ftruncate($fp, 0);
                rewind($fp);
                fwrite($fp, (string)$count);
                fflush($fp);
                @flock($fp, LOCK_UN);
            }
            fclose($fp);
        }

        $expiredBefore = time() - ($window * 3);
        $oldFiles = @glob($path . DIRECTORY_SEPARATOR . '*.rl');
        if (is_array($oldFiles)) {
            foreach ($oldFiles as $oldFile) {
                if (@filemtime($oldFile) < $expiredBefore) {
                    @unlink($oldFile);
                }
            }
        }

        return true;
    }

    /**
     * Helper untuk mengirim response JSON
     */
    private function _send_response($status_code, $message, $data = null, $requestId = '') {
        http_response_code($status_code);

        $response = [
            'status' => $status_code,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        if ($requestId !== '') {
            $response['request_id'] = $requestId;
            header('X-Request-Id: ' . $requestId);
        }

        if ($data !== null) {
            $response['data'] = $data;
        }

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    /**
     * Endpoint untuk test koneksi
     */
    public function test() {
        $requestId = $this->_get_request_id();
        if (!$this->_check_rate_limit($requestId)) {
            $this->_send_response(429, 'Too many requests', null, $requestId);
            return;
        }

        $secret_key = $this->input->get_request_header('X-Webhook-Secret');
        if (!$this->_validate_secret($secret_key)) {
            $this->_send_response(401, 'Unauthorized - Invalid secret key', null, $requestId);
            return;
        }
        if (!$this->_is_ip_allowed()) {
            $this->_send_response(403, 'Forbidden - IP not allowed', null, $requestId);
            return;
        }

        $signatureError = '';
        if (!$this->_validate_signature('', $signatureError)) {
            $this->_send_response(401, $signatureError, null, $requestId);
            return;
        }

        $this->_send_response(200, 'Webhook endpoint is working', [
            'service' => 'SALES Webhook',
            'version' => '1.0',
            'timestamp' => date('Y-m-d H:i:s')
        ], $requestId);
    }

    public function autoRejectPurchase_get(){
        $this->_send_response(405, 'Method Not Allowed - Use POST only', null, $this->_get_request_id());
    }

    public function autoRejectPurchase() {
        $requestId = $this->_get_request_id();
        if (!$this->_check_rate_limit($requestId)) {
            $this->_send_response(429, 'Too many requests', null, $requestId);
            return;
        }

        // Validate secret key from header
        $secret_key = $this->input->get_request_header('X-Webhook-Secret');
        if (!$this->_validate_secret($secret_key)) {
            $this->_send_response(401, 'Unauthorized - Invalid secret key', null, $requestId);
            return;
        }
        if (!$this->_is_ip_allowed()) {
            $this->_send_response(403, 'Forbidden - IP not allowed', null, $requestId);
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->_send_response(405, 'Method Not Allowed - Use POST only', null, $requestId);
            return;
        }

        $rawInput = file_get_contents('php://input');
        $signatureError = '';
        if (!$this->_validate_signature($rawInput, $signatureError)) {
            $this->_send_response(401, $signatureError, null, $requestId);
            return;
        }

        // Get data from POST request
        $inputData = $this->input->post();

        // If no POST data found, check for JSON input
        if (empty($inputData)) {
            if (!empty($rawInput)) {
                $inputData = json_decode($rawInput, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $this->_send_response(400, 'Invalid JSON format', null, $requestId);
                    return;
                }
            }
        }

        // Debug log received data
        log_message('info', 'Webhook request_id=' . $requestId . ' data=' . json_encode($inputData));

        // Validate required parameters
        $required_params = array('so_id', 'po_id', 'referensi_cabang_id');
        foreach ($required_params as $param) {
            if (!isset($inputData[$param]) || empty($inputData[$param])) {
                $this->_send_response(400, "Missing required parameter: $param", null, $requestId);
                return;
            }
        }

        // Extract parameters
        $so_id = (int)$inputData["so_id"];
        $po_id = (int)$inputData["po_id"];
        $cab_id = (int)$inputData["referensi_cabang_id"];

        $p = new Transaksional();
        $tempDataBridge = $p->cekExecSalesOrder($so_id, $po_id); // cek apakah so valid

        if (count($tempDataBridge) > 0) {
            $status = 200;
            $message = "Success";

            // cek apakah sudah pernah direject
            $this->load->model("MdlSalesRejectHolding");
            $t = new MdlSalesRejectHolding();
            $tmp = $p->cekRejectSalesOrder($so_id, $po_id);

            if ($tmp["reject"] == 0) {
                // belum pernah reject, insert data
                $insertData = array(
                    "so_id" => $so_id,
                    "po_id" => $po_id,
                    "keterangan" => isset($inputData["keterangan"]) ? $inputData["keterangan"] : "",
                    "dtime" => date("Y-m-d H:i:s"),
                    "reject_ref_id" => isset($inputData["reject_by_transaksi_id"]) ? $inputData["reject_by_transaksi_id"] : null,
                    "reject_ref_nomer" => isset($inputData["reject_by_nomer"]) ? $inputData["reject_by_nomer"] : "",
                    "reject_ref_oleh_id" => isset($inputData["reject_by_id"]) ? $inputData["reject_by_id"] : null,
                    "reject_ref_oleh_nama" => isset($inputData["reject_by_nama"]) ? $inputData["reject_by_nama"] : "",
                    "reject_ref_dtime" => isset($inputData["reject_by_time"]) ? $inputData["reject_by_time"] : date("Y-m-d H:i:s"),
                );

                $insert = $t->addData($insertData);

                if ($insert) {
                    // Log successful insertion
                    log_message('info', "Webhook request_id=$requestId reject data inserted - SO: $so_id, PO: $po_id");
                } else {
                    $status = 500;
                    $message = "Failed to insert reject data";
                    log_message('error', "Webhook request_id=$requestId failed insert reject data - SO: $so_id, PO: $po_id");
                }
            } else {
                $message = "Purchase already rejected previously";
                log_message('info', "Webhook request_id=$requestId duplicate reject - SO: $so_id, PO: $po_id");
            }
        } else {
            $status = 404;
            $message = "Sales Order or Purchase Order not found";
            log_message('error', "Webhook request_id=$requestId SO/PO not found - SO: $so_id, PO: $po_id");
        }

        $this->_send_response($status, $message, [
            'service' => 'SALES Webhook',
            'version' => '1.0',
            'timestamp' => date('Y-m-d H:i:s'),
            'data_received' => [
                'so_id' => $so_id,
                'po_id' => $po_id,
                'referensi_cabang_id' => $cab_id
            ]
        ], $requestId);
    }
}
