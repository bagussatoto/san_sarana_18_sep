<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Webhook extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function receive_leads()
    {
        // Mapping field dari JSON ke database
        // Hanya field yang pasti ada di tabel per_customers_register
        $koloms = [
            "nama"          => "company_name",
            "email"         => "email",
            "tlp_1"         => "phone",
            "alamat_1"      => "address",
            "kelurahan"     => "subdivision",
            "kecamatan"     => "subdistrict",
            "kabupaten"     => "city",
            "propinsi"      => "province",
            "kode_pos"      => "zip",
            "no_ktp"        => "nik",
            "npwp"          => "npwp",
            "referensi_id"  => "id",
            "dc_id"         => "dc_id",
        ];

        $this->load->model("Mdls/MdlCustomer");
        $c = new MdlCustomer();

        // ------------------------------------------------------------------
        // 1. Validasi SECRET KEY
        // ------------------------------------------------------------------
        $secret_key = $this->input->get_request_header('X-Webhook-Secret');
        if (!$this->_validate_secret($secret_key)) {
            $this->_send_response(401, 'Unauthorized - Invalid secret key');
            return;
        }

        // ------------------------------------------------------------------
        // 2. Ambil JSON POST
        // ------------------------------------------------------------------
        $json = json_decode($this->input->raw_input_stream, true);

        if (!is_array($json)) {
            $this->_send_response(400, "Invalid JSON payload");
            return;
        }

        // ------------------------------------------------------------------
        // 3. Validasi field wajib
        // ------------------------------------------------------------------
        if (empty($json['id'])) {
            $this->_send_response(400, 'Missing required field: id');
            return;
        }

        $ref_id = $json['id'];
        $dc_id  = isset($json['dc_id']) ? $json['dc_id'] : 0;

        // ------------------------------------------------------------------
        // 4. Cek apakah sudah ada di database
        // ------------------------------------------------------------------
        $preData = $c->getRequestCustomerAll($ref_id)->result();

        // ------------------------------------------------------------------
        // 5. Proses insert sesuai dc_id
        // ------------------------------------------------------------------
        if ($dc_id == 0) {
            if (count($preData) == 0) {
                // ---------------------------- Bentuk data insert ----------------------------
                $toInsert = [];
                foreach ($koloms as $k => $src) {
                    if ($k === 'nama') {
                        $value = !empty($json['company_name']) ? $json['company_name'] :
                                (!empty($json['name']) ? $json['name'] :
                                (!empty($json['customer_name']) ? $json['customer_name'] :
                                (!empty($json['lead_name']) ? $json['lead_name'] :
                                (!empty($json['client_name']) ? $json['client_name'] : 'Tanpa Nama'))));
                    } else {
                    $value = isset($json[$src]) ? $json[$src] : null;
                    }
                    // Skip field yang kosong/null, kecuali field required
                    if ($value === null || $value === '') {
                        // Field required yang wajib ada meskipun kosong
                        if ($k === 'npwp') {
                            $toInsert[$k] = '0'; // Default value untuk npwp
                        }
                        // Skip field lain yang kosong
                        continue;
                    }
                    $toInsert[$k] = $value;
                }
                // ---------------------------- Overwrite PROVINSI / KABUPATEN ----------------------------
                $propinsi  = isset($json['province']) ? $json['province'] : null;
                $kabupaten = isset($json['city']) ? $json['city'] : null;
                if (!empty($propinsi)) {
                    $this->db->select('kabupaten_id, propinsi_id, postal_code, kabupaten, propinsi');
                    $this->db->from('postal_codes');
                    $this->db->where('propinsi_id', $propinsi);
                    if (!empty($kabupaten)) {
                        $this->db->where('kabupaten_id', $kabupaten);
                    }
                    $this->db->group_by('kabupaten_id, propinsi_id');
                    $this->db->limit(1);
                    $postal = $this->db->get();
                    if ($postal->num_rows() == 1) {
                        $row = $postal->row();
                        // overwrite nama propinsi
                        $toInsert["propinsi"] = $row->propinsi;
                        // overwrite nama kabupaten bila tersedia
                        if (!empty($kabupaten)) {
                            $toInsert["kabupaten"] = $row->kabupaten;
                        }
                    }
                }
                // ---------------------------- Insert ke DB ----------------------------
                $c->addRequest($toInsert);
            } else {
                // data sudah ada → skip
            }
        } else {
            // dc_id != 0 → insert ke per_customers dengan field wajib
            $preData = $c->getCustomerDC($dc_id)->result();
            if(count($preData)>0){
                //pass sudah terdaftar

            }
            else{
                $toInsert = [];
                foreach ($koloms as $k => $src) {
                    if ($k === 'nama') {
                        $value = !empty($json['company_name']) ? $json['company_name'] :
                                (!empty($json['name']) ? $json['name'] :
                                (!empty($json['customer_name']) ? $json['customer_name'] :
                                (!empty($json['lead_name']) ? $json['lead_name'] :
                                (!empty($json['client_name']) ? $json['client_name'] : 'Tanpa Nama'))));
                    } else {
                    $value = isset($json[$src]) ? $json[$src] : null;
                    }
                    if ($value === null || $value === '') {
                        if ($k === 'npwp') {
                            $toInsert[$k] = '0';
                        }
                        continue;
                    }
                    $toInsert[$k] = $value;
                }
                // Overwrite propinsi/kabupaten jika ada
                $propinsi  = isset($json['province']) ? $json['province'] : null;
                $kabupaten = isset($json['city']) ? $json['city'] : null;
                if (!empty($propinsi)) {
                    $this->db->select('kabupaten_id, propinsi_id, postal_code, kabupaten, propinsi');
                    $this->db->from('postal_codes');
                    $this->db->where('propinsi_id', $propinsi);
                    if (!empty($kabupaten)) {
                        $this->db->where('kabupaten_id', $kabupaten);
                    }
                    $this->db->group_by('kabupaten_id, propinsi_id');
                    $this->db->limit(1);
                    $postal = $this->db->get();
                    if ($postal->num_rows() == 1) {
                        $row = $postal->row();
                        $toInsert["propinsi"] = $row->propinsi;
                        if (!empty($kabupaten)) {
                            $toInsert["kabupaten"] = $row->kabupaten;
                        }
                    }
                }
                // Isi field wajib dengan helper model
                $toInsert["dc_id"]=$dc_id;
                $toInsert["member_id"]=$ref_id;
                unset($toInsert["referensi_id"]);
                $toInsert = $c->fillRequiredFields($toInsert);
                $result = $c->addCustomer($toInsert);
                if (!is_numeric($result)) {
                    $this->_send_response(500, $result);
                    return;
                }
            }

        }

        // ------------------------------------------------------------------
        // 6. Response sukses
        // ------------------------------------------------------------------
        $this->_send_response(200, 'Webhook received successfully');
    }
    public function receive_leads_debug() {
        // Mapping field dari JSON ke database
        // Hanya field yang pasti ada di tabel per_customers_register
        $koloms = [
            "nama"          => "company_name",
            "email"         => "email",
            "tlp_1"         => "phone",
            "alamat_1"      => "address",
            "kelurahan"     => "subdivision",
            "kecamatan"     => "subdistrict",
            "kabupaten"     => "city",
            "propinsi"      => "province",
            "kode_pos"      => "zip",
            "no_ktp"        => "nik",
            "npwp"          => "npwp",
            "referensi_id"  => "id",
        ];

        $this->load->model("Mdls/MdlCustomer");
        $c = new MdlCustomer();

        // Log request info
//        log_message('info', 'REQUEST METHOD: ' . $_SERVER['REQUEST_METHOD']);
//        log_message('info', 'QUERY STRING: ' . $_SERVER['QUERY_STRING']);

        // Validasi secret key dari header
        $secret_key = $this->input->get_request_header('X-Webhook-Secret');
//        log_message('info', 'SECRET KEY RECEIVED: ' . $secret_key);
        $pakai_scret = 0;
        if($pakai_scret){
            if (!$this->_validate_secret($secret_key)) {
                log_message('error', 'SECRET KEY VALIDATION FAILED');
                $this->_send_response(401, 'Unauthorized - Invalid secret key');
                return;
            }
        }
        // Log data yang diterima
//        log_message('info', 'GET DATA RECEIVED: ' . print_r($get_data, true));
//arrPrint($this->input->get());
        // Validasi data required
        if (empty($this->input->get('id'))) {
            $this->_send_response(400, 'Missing required field: id');
            return;
        }

        // Process data

        $dc_id = $this->input->get('dc_id');
        $ref_id = $this->input->get('id');


        if($dc_id==0){
            //per_customer_register
            $preData = $c->getRequestCustomerAll($ref_id)->result();
            $last_query = $this->db->last_query();
            if(count($preData)>0){
            //sudah ada update skip
            }
            else{
                //insert baru
                $propinsi = $this->input->get('province');
                $kabupaten = $this->input->get('city');
                /**
                 * ambil data dari postal_codes berdasarkan propinsi dan kota nya
                 *
                 */
                $this->db->select('kabupaten_id, propinsi_id, postal_code, kabupaten, propinsi');
                $this->db->from('postal_codes');

                if(!empty($propinsi) && $propinsi != '0'){
                    $this->db->where('propinsi_id', $propinsi);
                }

                if(!empty($kabupaten) && $kabupaten != '0'){
                    $this->db->where('kabupaten_id', $kabupaten);
                }

                //pre data
                $toInsert = array();
                foreach ($koloms as $k => $src_key){
                    $toInsert[$k]=$this->input->get($src_key);
                }
                if(!empty($propinsi)){
                    $this->db->group_by('kabupaten_id, propinsi_id'); // Group untuk menghindari duplikat
                    $this->db->limit(1);
                    $query = $this->db->get();
                    if($query->num_rows() == 1) {
                        $result = $query->row();
                        $toInsert["propinsi"]=$result->propinsi;
                        if(!empty($kabupaten)){
                            $toInsert["kabupaten"]=$result->kabupaten;
                        }
                    }
                }

                if(count($toInsert)>0){
                     $c->addRequest($toInsert);
                }

            }
        }
        else{
            //cek apakah sudah ada di daftar konsumen equivalent per_customer
            $preData = $c->getCustomerDC($dc_id)->result();
            if(count($preData)>0){
                //pass sduah terdaftar

            }
            else{
                //insert data customer
                //insert baru
                $propinsi = $this->input->get('province');
                $kabupaten = $this->input->get('city');
                /**
                 * ambil data dari postal_codes berdasarkan propinsi dan kota nya
                 *
                 */
                $this->db->select('kabupaten_id, propinsi_id, postal_code, kabupaten, propinsi');
                $this->db->from('postal_codes');

                if(!empty($propinsi) && $propinsi != '0'){
                    $this->db->where('propinsi_id', $propinsi);
                }

                if(!empty($kabupaten) && $kabupaten != '0'){
                    $this->db->where('kabupaten_id', $kabupaten);
                }

                //pre data
                $toInsert = array();
                foreach ($koloms as $k => $src_key){
                    $toInsert[$k]=$this->input->get($src_key);
                }
                if(!empty($propinsi)){
                    $this->db->group_by('kabupaten_id, propinsi_id'); // Group untuk menghindari duplikat
                    $this->db->limit(1);
                    $query = $this->db->get();
                    if($query->num_rows() == 1) {
                        $result = $query->row();
                        $toInsert["propinsi"]=$result->propinsi;
                        if(!empty($kabupaten)){
                            $toInsert["kabupaten"]=$result->kabupaten;
                        }
                    }
                }
                $toInsert["dc_id"]=$dc_id;
                $toInsert["member_id"]=$ref_id;
                unset($toInsert["referensi_id"]);
                if(count($toInsert)>0){
                    $c->addData($toInsert);
//                    cekHitam($this->db->last_query());
                }

//matiHere(__LINE__.":insert data konsumen:");
            }
//            arrPrint($preData);
//            matiHEre($this->db->last_query());
        }

        $this->_send_response(200, 'GET Webhook received successfully', [
            'service' => 'SALES Webhook - GET Method',
            'method' => $_SERVER['REQUEST_METHOD'],
            'data_received' => $this->input->get(),
            'database_query' => $last_query,
            'query_result_count' => count($preData),
            'query_results' => $preData,
            'secret_key_valid' => 'YES',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Validasi secret key
     */
    private function _validate_secret($secret) {
        $valid_secret = "mgk2025webhooks";
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
        $this->_send_response(200, 'Webhook endpoint is working', [
            'service' => 'SALES Webhook',
            'version' => '1.0',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * handling order menggantikan rabbit
     */
    public function receive_order__(){
//     arrPrint($_GET);

        $this->db->trans_start();
        $this->load->model("Mdls/MdlCrmDataBridge");
        $this->load->model("Mdls/MdlCustomer");
        $this->load->model("Mdls/MdlCustomerAddress");
        $this->load->model("Mdls/MdlCustomerBillAddress");
        $a = new MdlCustomerAddress();
        $b = new MdlCustomerBillAddress();
        $c = new MdlCustomer();
        $m = new MdlCrmDataBridge();
        $transformKey = $m->getConvertFieldCrm();
        $data_master = blobDecode($_GET["data"]);
//        $data_master = $data_insert["data"];
        $data_items = $data_master["items"];
//arrPrint($data_master);
//matiHere();
        /**
         * lanjuklan denga cek data konsumen di aplikasi lokal
         * jika sudah ada skip
         * jika belum terdaftar ambil data dari Data center lalu import ke local
         */
        $dc_id = $data_items[0]["dc_id"];//id customer data center
        $client_id = $ref_id=$data_items[0]["client_id"];//client_id local dari CRM

        if($dc_id>0){

            $c->addFilter("dc_id='$dc_id'");//id dari konsumen bersangkutan di data center
            $cekCustomer = $c->lookUpAll()->result();
            if (count($cekCustomer) > 0) {
                $insertID = $cekCustomer[0]->id;
//            cekHere(__LINE__);

            } else {
                //import dari data center
                $customerDataCenter = call_curl(ADM_DOMAIN . "/eusvc/Customers/seeItemAll/id/$dc_id");
                if (count($customerDataCenter) > 0) {
                    $dataCustomer = $customerDataCenter["data"][0];
                    $dataCustomerAddress = $customerDataCenter["data"][0]["address"][0];
                    $dataCustomerBilling = $customerDataCenter["data"][0]["billing"][0];
                    $phone = $dataCustomer["phone"];
                    $alamat = $dataCustomer["alamat"];
                    $nik = $dataCustomer["nik"];
                    $credit_limit = $dataCustomer["credit_limit"];
                    //cleansing data sebelum diinsertkan
                    unset($dataCustomer["address"]);
                    unset($dataCustomer["billing"]);
                    unset($dataCustomer["id"]);
                    unset($dataCustomer["first_name"]);
                    unset($dataCustomer["last_name"]);
                    unset($dataCustomer["login_name"]);
                    unset($dataCustomer["phone"]);
                    unset($dataCustomer["alamat"]);
                    unset($dataCustomer["credit_limit"]);
                    unset($dataCustomer["nik"]);
                    unset($dataCustomer["attn"]);


//                unset($dataCustomer["nik"]);
                    $dataCustomer["tlp_1"] = $phone;
                    $dataCustomer["alamat_1"] = $alamat;
                    $dataCustomer["no_ktp"] = $nik;
//                $dataCustomer["due_days"]=credit_limit;
                    $dataCustomer["dc_id"] = $dc_id;
                    $dataCustomer["is_customer"] = "1";


//                $fields = $c->getFields();

                    $insertID = $c->addData($dataCustomer) or die("error on insert");
                    cekKuning($insertID);
                    cekHitam($this->db->last_query());
                    $dataCustomerAddress["tlp"] = $dataCustomerAddress["telp"];
                    $dataCustomerAddress["tlp_2"] = $dataCustomerAddress["telp2"];
                    $dataCustomerAddress["tlp_3"] = $dataCustomerAddress["telp3"];
                    $dataCustomerAddress["extern_id"] = $insertID;
//                $dataCustomerAddress["extern_nama"]=$insertID;
                    //address
                    unset($dataCustomerAddress["id"]);
                    unset($dataCustomerAddress["customer"]);
                    unset($dataCustomerAddress["telp"]);
                    unset($dataCustomerAddress["telp2"]);
                    unset($dataCustomerAddress["telp3"]);
                    $a->addData($dataCustomerAddress) or die("error on insert");
                    cekHitam($this->db->last_query());

                    //billing
                    arrprint($dataCustomerBilling);
                    $dataCustomerBilling["tlp"] = $dataCustomerBilling["telp"];
                    $dataCustomerBilling["tlp_2"] = $dataCustomerBilling["telp2"];
                    $dataCustomerBilling["tlp_3"] = $dataCustomerBilling["telp3"];
                    $dataCustomerBilling["extern_id"] = $insertID;
                    unset($dataCustomerBilling["id"]);
                    unset($dataCustomerBilling["customer"]);
                    unset($dataCustomerBilling["telp"]);
                    unset($dataCustomerBilling["telp2"]);
                    unset($dataCustomerBilling["telp3"]);

                    $b->addData($dataCustomerBilling) or die("error on insert");
                    cekHitam($this->db->last_query());
                    cekMerah($insertID);

                } else {
                    //tetap bisa insert data tetapi relasi degnan konsumen belum ada
                    $insertID=0; //nom sebagai penamda belum meiliki relasi data
                }
            }
        }
        else{
            //belum ada data dcid
            //cek ke data register
            //per_customer_register
            $insertID=0;//karena baru registere maka belum punya customerID, akana dilogic saat akan otorisasi
            $preData = $c->getRequestCustomerAll($ref_id)->result();
            if(count($preData)>0){
                //sudah ada update skip
            }
            else{
                //insert baru
                $propinsi = $this->input->get('province');
                $kabupaten = $this->input->get('city');
                /**
                 * ambil data dari postal_codes berdasarkan propinsi dan kota nya
                 *
                 */
                $this->db->select('kabupaten_id, propinsi_id, postal_code, kabupaten, propinsi');
                $this->db->from('postal_codes');

                if(!empty($propinsi) && $propinsi != '0'){
                    $this->db->where('propinsi_id', $propinsi);
                }

                if(!empty($kabupaten) && $kabupaten != '0'){
                    $this->db->where('kabupaten_id', $kabupaten);
                }

                //pre data
                $koloms = [
                    "nama"          => "company_name",
                    "email"         => "email",
                    "tlp_1"         => "phone",
                    "alamat_1"      => "address",
                    "kelurahan"     => "subdivision",
                    "kecamatan"     => "subdistrict",
                    "kabupaten"     => "city",
                    "propinsi"      => "province",
                    "kode_pos"      => "zip",
                    "no_ktp"        => "nik",
                    "npwp"          => "npwp",
                    "referensi_id"  => "id",
                    "dc_id"         => "dc_id",
                ];
                $toInsert = array();
                foreach ($koloms as $k => $src_key){
                    $toInsert[$k]=$this->input->get($src_key);
                }
                if(!empty($propinsi)){
                    $this->db->group_by('kabupaten_id, propinsi_id'); // Group untuk menghindari duplikat
                    $this->db->limit(1);
                    $query = $this->db->get();
                    if($query->num_rows() == 1) {
                        $result = $query->row();
                        $toInsert["propinsi"]=$result->propinsi;
                        if(!empty($kabupaten)){
                            $toInsert["kabupaten"]=$result->kabupaten;
                        }
                    }
                }

                if(count($toInsert)>0){
                    $c->addRequest($toInsert);
                }

            }
        }

        //region cek data ada belum
        $m->addFilter("referensi_id='" . $data_master["referensi_id"] . "'");
        $tmp = $m->lookUpAll()->result();
        if (count($tmp) > 0) {
            //skip sudah ada
            $insert = 1;
            cekMerah(__LINE__."".$data_master["referensi_id"]);
        } else {
            $m->setFilters(array());
            if (count($data_items) > 0) {
                foreach ($data_items as $data_items_0) {
                    $data_items_0["crm_domain"] = $data_master["domain"];
                    foreach ($transformKey as $k => $v) {
                        if (isset($data_items_0[$v])) {
                            $data_items_0[$k] = $data_items_0[$v];
                        }
                    }
//                    arrprint($data_items_0);
                    $insert = $m->addDataBridge($data_items_0+array("customer_id"=>$insertID));
                    cekOrange($this->db->last_query());
                }
            }
        }
        //endregion
//        matiHere(__LINE__);
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        if ($insert) {
            $vars = array(
                "sinkron" => 1,
                "status" => 200,//sukses
                "dtime" => date("Y-m-d H:i"),
            );
            $this->response($vars, 200);
        } else {
            $vars = array(
                "sinkron" => 0,
                "status" => 500,
                "data" => $data_master,
            );
            $this->response($vars, 500);
        }
//matiHEre(__LINE__);
    }

    public function receive_order()
    {
        $debugMode = ((string) $this->input->get('debuger') === "1");
        $raw = file_get_contents('php://input');
        $data_master = json_decode($raw, true);


//arrPrint($data_master);
        // exit sudah ditangani di dalam _send_response()
        $this->db->trans_begin();

        $this->load->model("Mdls/MdlCrmDataBridge");
        $this->load->model("Mdls/MdlCustomer");
        $this->load->model("Mdls/MdlCustomerAddress");
        $this->load->model("Mdls/MdlCustomerBillAddress");

        $a = new MdlCustomerAddress();
        $b = new MdlCustomerBillAddress();
        $c = new MdlCustomer();
        $m = new MdlCrmDataBridge();

        $transformKey = $m->getConvertFieldCrm();
        $insert = 0;
        $insertID = 0;

        try {
            if (!is_array($data_master) || empty($data_master)) {
                $this->db->trans_rollback();
                $payload = array(
                    "sinkron" => 0,
                    "status"  => 400,
                    "message" => "Payload JSON tidak valid"
                );
                if ($debugMode) {
                    $payload["json_last_error"] = json_last_error_msg();
                    $payload["raw_payload_size"] = strlen((string) $raw);
                }
                $this->_send_response(400, $payload["message"], $payload);
            }

            $data_items = isset($data_master['items']) && is_array($data_master['items'])
                ? $data_master['items']
                : array();

            // Gunakan _send_response bawaan dari file ini, bukan method bawaan REST_Controller

            if (count($data_items) == 0) {
                $this->db->trans_rollback();
                $payload = array(
                    "sinkron" => 0,
                    "status"  => 400,
                    "message" => "Items kosong atau format tidak valid"
                );
                if ($debugMode) {
                    $payload["items_type"] = gettype(isset($data_master['items']) ? $data_master['items'] : null);
                }
                $this->_send_response(400, $payload["message"], $payload);
            }


            $firstItem = $data_items[0];
            $dc_id  = isset($firstItem['dc_id']) ? $firstItem['dc_id'] : 0;
            $ref_id = isset($firstItem['client_id']) ? $firstItem['client_id'] : 0;

            /**
             * cek data customer lokal
             */
            if ((int)$dc_id > 0) {

                $c->addFilter("dc_id='" . $dc_id . "'");
                $cekCustomer = $c->lookUpAll()->result();

                if (count($cekCustomer) > 0) {
                    $insertID = $cekCustomer[0]->id;
                } else {
                    // import dari data center
                    $customerDataCenter = call_curl(ADM_DOMAIN . "/eusvc/Customers/seeItemAll/id/" . $dc_id);

                    if (isset($customerDataCenter["data"][0]) && is_array($customerDataCenter["data"][0])) {
                        $dataCustomer = $customerDataCenter["data"][0];

                        $dataCustomerAddress = array();
                        if (isset($customerDataCenter["data"][0]["address"][0])) {
                            $dataCustomerAddress = $customerDataCenter["data"][0]["address"][0];
                        }

                        $dataCustomerBilling = array();
                        if (isset($customerDataCenter["data"][0]["billing"][0])) {
                            $dataCustomerBilling = $customerDataCenter["data"][0]["billing"][0];
                        }

                        $phone = isset($dataCustomer["phone"]) ? $dataCustomer["phone"] : null;
                        $alamat = isset($dataCustomer["alamat"]) ? $dataCustomer["alamat"] : null;
                        $nik = isset($dataCustomer["nik"]) ? $dataCustomer["nik"] : null;

                        unset($dataCustomer["address"]);
                        unset($dataCustomer["billing"]);
                        unset($dataCustomer["id"]);
                        unset($dataCustomer["first_name"]);
                        unset($dataCustomer["last_name"]);
                        unset($dataCustomer["login_name"]);
                        unset($dataCustomer["phone"]);
                        unset($dataCustomer["alamat"]);
                        unset($dataCustomer["credit_limit"]);
                        unset($dataCustomer["nik"]);
                        unset($dataCustomer["attn"]);

                        $dataCustomer["tlp_1"] = $phone;
                        $dataCustomer["alamat_1"] = $alamat;
                        $dataCustomer["no_ktp"] = $nik;
                        $dataCustomer["dc_id"] = $dc_id;
                        $dataCustomer["is_customer"] = "1";

                        $insertID = $c->addData($dataCustomer);
                        if (!$insertID) {
                            throw new Exception("Gagal insert customer");
                        }

                        // address
                        if (!empty($dataCustomerAddress) && is_array($dataCustomerAddress)) {
                            $dataCustomerAddress["tlp"] = isset($dataCustomerAddress["telp"]) ? $dataCustomerAddress["telp"] : null;
                            $dataCustomerAddress["tlp_2"] = isset($dataCustomerAddress["telp2"]) ? $dataCustomerAddress["telp2"] : null;
                            $dataCustomerAddress["tlp_3"] = isset($dataCustomerAddress["telp3"]) ? $dataCustomerAddress["telp3"] : null;
                            $dataCustomerAddress["extern_id"] = $insertID;

                            unset($dataCustomerAddress["id"]);
                            unset($dataCustomerAddress["customer"]);
                            unset($dataCustomerAddress["telp"]);
                            unset($dataCustomerAddress["telp2"]);
                            unset($dataCustomerAddress["telp3"]);

                            $addrInsert = $a->addData($dataCustomerAddress);
                            if (!$addrInsert) {
                                throw new Exception("Gagal insert customer address");
                            }
                        }

                        // billing
                        if (!empty($dataCustomerBilling) && is_array($dataCustomerBilling)) {
                            $dataCustomerBilling["tlp"] = isset($dataCustomerBilling["telp"]) ? $dataCustomerBilling["telp"] : null;
                            $dataCustomerBilling["tlp_2"] = isset($dataCustomerBilling["telp2"]) ? $dataCustomerBilling["telp2"] : null;
                            $dataCustomerBilling["tlp_3"] = isset($dataCustomerBilling["telp3"]) ? $dataCustomerBilling["telp3"] : null;
                            $dataCustomerBilling["extern_id"] = $insertID;

                            unset($dataCustomerBilling["id"]);
                            unset($dataCustomerBilling["customer"]);
                            unset($dataCustomerBilling["telp"]);
                            unset($dataCustomerBilling["telp2"]);
                            unset($dataCustomerBilling["telp3"]);

                            $billInsert = $b->addData($dataCustomerBilling);
                            if (!$billInsert) {
                                throw new Exception("Gagal insert customer billing");
                            }
                        }
                    } else {
                        // tetap bisa insert data bridge walau customer belum ketemu
                        $insertID = 0;
                    }
                }

            } else {
                // belum punya dc_id
                $insertID = 0;

                $preData = $c->getRequestCustomerAll($ref_id)->result();

                if (count($preData) == 0) {
                    $toInsert = array(
                        "nama"         => isset($firstItem["company_name"]) ? $firstItem["company_name"] : null,
                        "email"        => isset($firstItem["email"]) ? $firstItem["email"] : null,
                        "tlp_1"        => isset($firstItem["phone"]) ? $firstItem["phone"] : null,
                        "alamat_1"     => isset($firstItem["address"]) ? $firstItem["address"] : null,
                        "kelurahan"    => isset($firstItem["subdivision"]) ? $firstItem["subdivision"] : null,
                        "kecamatan"    => isset($firstItem["subdistrict"]) ? $firstItem["subdistrict"] : null,
                        "kabupaten"    => isset($firstItem["city"]) ? $firstItem["city"] : null,
                        "propinsi"     => isset($firstItem["province"]) ? $firstItem["province"] : null,
                        "kode_pos"     => isset($firstItem["zip"]) ? $firstItem["zip"] : null,
                        "no_ktp"       => isset($firstItem["nik"]) ? $firstItem["nik"] : null,
                        "npwp"         => isset($firstItem["npwp"]) ? $firstItem["npwp"] : null,
                        "referensi_id" => $ref_id,
                        "dc_id"        => $dc_id
                    );

                    // Coalesce / Multi-field fallback for name if still empty
                    if (empty($toInsert["nama"]) || $toInsert["nama"] === '') {
                        $toInsert["nama"] = !empty($profile["name"]) ? $profile["name"] :
                                            (!empty($profile["customer_name"]) ? $profile["customer_name"] :
                                            (!empty($profile["lead_name"]) ? $profile["lead_name"] :
                                            (!empty($profile["client_name"]) ? $profile["client_name"] : null)));
                    }

                    // Overwrite PROVINSI / KABUPATEN
                    $propinsi  = !empty($profile['province']) ? $profile['province'] : (isset($firstItem['province']) ? $firstItem['province'] : null);
                    $kabupaten = !empty($profile['city']) ? $profile['city'] : (isset($firstItem['city']) ? $firstItem['city'] : null);
                    if (!empty($propinsi)) {
                        $this->db->select('kabupaten_id, propinsi_id, postal_code, kabupaten, propinsi');
                        $this->db->from('postal_codes');
                        $this->db->where('propinsi_id', $propinsi);
                        if (!empty($kabupaten)) {
                            $this->db->where('kabupaten_id', $kabupaten);
                        }
                        $this->db->group_by('kabupaten_id, propinsi_id');
                        $this->db->limit(1);
                        $postal = $this->db->get();
                        if ($postal->num_rows() == 1) {
                            $row = $postal->row();
                            $toInsert["propinsi"] = $row->propinsi;
                            if (!empty($kabupaten)) {
                                $toInsert["kabupaten"] = $row->kabupaten;
                            }
                        }
                    }

                    $reqInsert = $c->addRequest($toInsert);
                    if (!$reqInsert) {
                        throw new Exception("Gagal insert request customer");
                    }
                }
            }


            // cek bridge sudah ada belum
            $referensi_id = isset($data_master["referensi_id"]) ? $data_master["referensi_id"] : 0;

            $m->addFilter("referensi_id='" . $referensi_id . "'");
            $tmp = $m->lookUpAll()->result();
            $query_cek = array();
            if (count($tmp) > 0) {
                $insert = 1;
            } else {
                $m->setFilters(array());

                foreach ($data_items as $data_items_0) {
                    $data_items_0["crm_domain"] = isset($data_master["domain"]) ? $data_master["domain"] : null;

                    foreach ($transformKey as $k => $v) {
                        if (isset($data_items_0[$v])) {
                            $data_items_0[$k] = $data_items_0[$v];
                        }
                    }

                    $insert = $m->addDataBridge($data_items_0 + array("customer_id" => $insertID));
                    $query_cek[]=$this->db->last_query();
                    if (!$insert) {
                        throw new Exception("Gagal insert data bridge");
                    }
                }
            }


            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $payload = array(
                    "sinkron" => 0,
                    "status"  => 500,
                    "message" => "Transaction gagal",
                );
                if ($debugMode) {
                    $payload["db_error"] = $this->db->error();
                    $payload["last_query"] = $this->db->last_query();
                    $payload["json_last_error"] = json_last_error_msg();
                }
                $this->_send_response(500, $payload["message"], $payload);
            }


            $this->db->trans_commit();

            $payload = array(
                "sinkron"      => 1,
                "status"       => 200,
                "dtime"        => date("Y-m-d H:i"),
                "referensi_id" => $referensi_id,
                "total_items"  => count($data_items),
                "query"=>$query_cek,
            );
            $this->_send_response(200, "Webhook order processed", $payload);

        } catch (Exception $e) {
            $this->db->trans_rollback();

            $db_error = array();
            if (isset($this->db) && method_exists($this->db, "error")) {
                $db_error = $this->db->error();
            }
            $last_query = (isset($this->db) && method_exists($this->db, "last_query")) ? $this->db->last_query() : "";
            $referensi_id = (is_array($data_master) && isset($data_master["referensi_id"])) ? $data_master["referensi_id"] : 0;

            log_message(
                "error",
                "[Webhook::receive_order] " . $e->getMessage()
                . " @ " . $e->getFile() . ":" . $e->getLine()
                . " | referensi_id=" . $referensi_id
                . " | db_error=" . json_encode($db_error)
                . " | last_query=" . $last_query
            );

            $payload = array(
                "sinkron" => 0,
                "status"  => 500,
                "message" => $debugMode ? $e->getMessage() : "Internal server error",
                "referensi_id" => $referensi_id,
                "note"=>"masuk error",
            );
            if ($debugMode) {
                $payload["exception_type"] = get_class($e);
                $payload["exception_file"] = $e->getFile();
                $payload["exception_line"] = $e->getLine();
                $payload["db_error"] = $db_error;
                $payload["last_query"] = $last_query;
            }

            $this->_send_response(500, $payload["message"], $payload);
        }
    }

    /**
     * Endpoint untuk menerima update harga dari holding
     * POST /eusvc/webhook/price_update
     * edited by glg (18:00 WIB, 2025-12-19)
     * change: menggunakan model MdlHargaProduk untuk operasi database
     * technical rationale: menggunakan model existing yang sudah handle tabel price
     */
    public function price_update()
    {
        // Load model
        $this->load->model('Mdls/MdlHargaProduk');

        // ------------------------------------------------------------------
        // 1. Validasi Signature (HMAC SHA256)
        // ------------------------------------------------------------------
        $signature = $this->input->get_request_header('X-Signature');
        $raw_payload = $this->input->raw_input_stream;

        if (!$this->_validate_signature($signature, $raw_payload)) {
            $this->_send_json_response(401, 'error', 'invalid signature');
            return;
        }

        // ------------------------------------------------------------------
        // 2. Parse & Validasi Payload
        // ------------------------------------------------------------------
        $payload = json_decode($raw_payload, true);

        if (!is_array($payload)) {
            $this->_send_json_response(422, 'error', 'invalid payload');
            return;
        }

        // Validasi field wajib
        // edited by glg (18:35 WIB, 2025-12-19)
        // change: kontrak baru - hapus cabang_id & toko_id, tambah scope_code
        // technical rationale: holding hanya mengirim scope_code, bukan ID internal subsidiary
        $required_fields = [
            'holding_price_id', 'hash', 'produk_id', 'jenis',
            'jenis_value', 'nilai', 'scope_code'
        ];

        foreach ($required_fields as $field) {
            if (!isset($payload[$field]) || $payload[$field] === '' || $payload[$field] === null) {
                $this->_send_json_response(422, 'error', "invalid payload - missing field: {$field}");
                return;
            }
        }

        // ------------------------------------------------------------------
        // 3. Resolve scope_code → cabang_id[]
        // edited by glg (18:42 WIB, 2025-12-19)
        // change: tambahkan resolver layer untuk mapping business code ke operasional ID
        // technical rationale: holding berbicara dalam scope_code, subsidiary terjemahkan ke cabang_id
        // ------------------------------------------------------------------
        $scope_code = $payload['scope_code'];
        $cabang_list = $this->_resolve_scope_code($scope_code);

        if ($cabang_list === false) {
            $this->_send_json_response(422, 'error', "invalid scope_code: {$scope_code}");
            return;
        }

        // ------------------------------------------------------------------
        // 4. Loop per cabang - Idempotency Check & Insert
        // edited by glg (18:45 WIB, 2025-12-19)
        // change: wrap logic existing dalam loop per cabang
        // technical rationale: satu event holding bisa menghasilkan banyak insert (1 per cabang)
        // ------------------------------------------------------------------
        $holding_price_id = (int) $payload['holding_price_id'];
        $hash = $payload['hash'];

        $success_count = 0;
        $skip_count = 0;

        foreach ($cabang_list as $cabang_id) {
            // Idempotency check per (holding_price_id + hash + cabang_id)
            if ($this->MdlHargaProduk->isDuplicateWebhook($holding_price_id, $hash, $cabang_id)) {
                $skip_count++;
                continue;  // Skip cabang yang sudah diproses
            }

            // Bentuk data untuk insert
            $data = [
                'holding_price_id' => $holding_price_id,
                'hash'             => $hash,
                'produk_id'        => (int) $payload['produk_id'],
                'jenis'            => $payload['jenis'],
                'jenis_value'      => $payload['jenis_value'],
                'nilai'            => $payload['nilai'],
                'cabang_id'        => $cabang_id,
                'toko_id'          => 0  // Set toko_id = 0 sesuai requirement
            ];

            // Jalankan logic trash + insert via model
            $result = $this->MdlHargaProduk->updatePriceVersionedWebhook($data);

            if ($result) {
                $success_count++;
            }
        }

        // ------------------------------------------------------------------
        // 5. Response
        // ------------------------------------------------------------------
        if ($success_count == 0 && $skip_count > 0) {
            // Semua cabang sudah diproses sebelumnya
            $this->_send_json_response(200, 'ignored', 'all branches already processed');
            return;
        }

        if ($success_count == 0) {
            // Tidak ada yang berhasil diproses
            $this->_send_json_response(500, 'error', 'transaction failed for all branches');
            return;
        }

        // Ada yang berhasil diproses
        $this->_send_json_response(200, 'ok', "price updated for {$success_count} branch(es)");
    }

    /**
     * Resolver untuk menerjemahkan scope_code menjadi daftar cabang_id lokal
     * edited by glg (16:30 WIB, 2025-12-19)
     * change: GLOBAL ambil semua cabang aktif dari database, bukan hardcode
     * technical rationale: holding berbicara kebijakan (GLOBAL = semua cabang), subsidiary terjemahkan ke cabang operasional
     */
    private function _resolve_scope_code($scope_code) {
        if ($scope_code === 'GLOBAL') {
            // GLOBAL = semua cabang operasional aktif milik subsidiary
            $this->load->model('Mdls/MdlCabang');
            $cabangModel = new MdlCabang();

            // Ambil semua cabang aktif (status=1, trash=0, id>0)
            $cabangModel->setFilters([]);
            $cabangModel->addFilter("status='1'");
            $cabangModel->addFilter("trash='0'");
            $cabangModel->addFilter("id > 0");  // Exclude special IDs seperti -1

            $cabangs = $cabangModel->lookUpAll()->result();

            if (empty($cabangs)) {
                // Fallback: jika tidak ada cabang aktif, gunakan cabang_id = 0 (default)
                return [0];
            }

            // Return array of cabang_id
            $cabang_ids = [];
            foreach ($cabangs as $cabang) {
                $cabang_ids[] = $cabang->id;
            }

            return $cabang_ids;
        }

        // Future: support scope_code lain seperti REGION_JAWA, CUSTOM, dll
        // Bisa dipindahkan ke tabel konfigurasi

        return false;  // scope_code tidak dikenali
    }

    /**
     * Validasi signature webhook dengan HMAC SHA256
     * edited by glg (17:30 WIB, 2025-12-18)
     * change: menambahkan validasi signature untuk keamanan webhook
     */
    private function _validate_signature($signature, $payload) {
        if (empty($signature) || empty($payload)) {
            return false;
        }

        $secret = "mgk2025webhooks";
        $expected_signature = hash_hmac('sha256', $payload, $secret);

        return hash_equals($expected_signature, $signature);
    }

    /**
     * Helper untuk mengirim response JSON (format webhook price)
     * edited by glg (17:30 WIB, 2025-12-18)
     * change: menambahkan helper response khusus untuk webhook price
     */
    private function _send_json_response($http_code, $status, $message) {
        http_response_code($http_code);
        header('Content-Type: application/json');

        echo json_encode([
            'status' => $status,
            'message' => $message
        ]);

        exit;
    }

}