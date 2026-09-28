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

    /**
     * Helper untuk mengirim response JSON
     */
    private function _send_response($status_code, $message, $data = null) {
        http_response_code($status_code);

        $response = [
            'status' => $status_code,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s')
        ];

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
        $secret_key = $this->input->get_request_header('X-Webhook-Secret');
        if (!$this->_validate_secret($secret_key)) {
            $this->_send_response(401, 'Unauthorized - Invalid secret key');
            return;
        }
        $this->_send_response(200, 'Webhook endpoint is working', [
            'service' => 'SALES Webhook',
            'version' => '1.0',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }

    public function autoRejectPurchase_get(){

        $secret_key = $this->input->get_request_header('X-Webhook-Secret');
        if (!$this->_validate_secret($secret_key)) {
            $this->_send_response(401, 'Unauthorized - Invalid secret key');
            return;
        }

        //region update table reject untuk
        arrPrint($_GET);
        $so_id = $_GET["so_id"];
        $po_id = $_GET["po_id"];
        $cab_id = $_GET["referensi_cabang_id"];
        $p = new Transaksional();
        $tempDataBridge = $temp = $p->cekExecSalesOrder($so_id,$po_id);//cek apakah so valid
        if(count($tempDataBridge)>0){
            $status ="200";
            //cek apakah sudah pernah direject
            $this->load->model("MdlSalesRejectHolding");
            $t = new MdlSalesRejectHolding();
            $tmp = $p->cekRejectSalesOrder($so_id,$po_id);

            if($tmp["reject"] == 0){
                //belum pernah reject insert saja
                $insertData = array(
                    "so_id"=>$so_id,
                    "po_id"=>$po_id,
                    "keterangan"=>isset($_GET["keterangan"]) ? $_GET["keterangan"]:"",
                    "dtime"=>date("Y-m-d H:i:s"),
                    "reject_ref_id"=>$_GET["reject_by_transaksi_id"],
                    "reject_ref_nomer"=>$_GET["reject_by_nomer"],
                    "reject_ref_oleh_id"=>$_GET["reject_by_id"],
                    "reject_ref_oleh_nama"=>$_GET["reject_by_nama"],
                    "reject_ref_dtime"=>$_GET["reject_by_time"],
                );
                $insert = $t->addData($insertData);
                if($insert){

                }
                else{
                    $status=500;
                }

            }
        }
        else{
            $status =404;
            $message="not found";
        }
        //endregion
        $this->_send_response($status, 'Webhook endpoint is working', [
            'service' => 'SALES Webhook',
            'version' => '1.0',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }

    public function autoRejectPurchase() {
        // Validate secret key from header
        $secret_key = $this->input->get_request_header('X-Webhook-Secret');
        if (!$this->_validate_secret($secret_key)) {
            $this->_send_response(401, 'Unauthorized - Invalid secret key');
            return;
        }
        // Determine request method and get data accordingly
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get data from POST request
            $inputData = $this->input->post();

            // If no POST data found, check for JSON input
            if (empty($inputData)) {
                $rawInput = file_get_contents('php://input');
                if (!empty($rawInput)) {
                    $inputData = json_decode($rawInput, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $this->_send_response(400, 'Invalid JSON format');
                        return;
                    }
                }
            }
        } else {
            // For backward compatibility - still accept GET
            $inputData = $this->input->get();

            // Log warning for GET usage
            log_message('warning', 'GET method used for autoRejectPurchase - Consider migrating to POST');
        }

        // Debug log received data
        log_message('info', 'Webhook data received: ' . json_encode($inputData));

        // Validate required parameters
        $required_params = array('so_id', 'po_id', 'referensi_cabang_id');
        foreach ($required_params as $param) {
            if (!isset($inputData[$param]) || empty($inputData[$param])) {
                $this->_send_response(400, "Missing required parameter: $param");
                return;
            }
        }

        // Extract parameters
        $so_id = $inputData["so_id"];
        $po_id = $inputData["po_id"];
        $cab_id = $inputData["referensi_cabang_id"];

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
                    log_message('info', "Reject data inserted successfully - SO: $so_id, PO: $po_id");
                } else {
                    $status = 500;
                    $message = "Failed to insert reject data";
                    log_message('error', "Failed to insert reject data - SO: $so_id, PO: $po_id");
                }
            } else {
                $message = "Purchase already rejected previously";
                log_message('info', "Duplicate reject attempt - SO: $so_id, PO: $po_id already rejected");
            }
        } else {
            $status = 404;
            $message = "Sales Order or Purchase Order not found";
            log_message('error', "SO/PO not found - SO: $so_id, PO: $po_id");
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
        ]);
    }
}