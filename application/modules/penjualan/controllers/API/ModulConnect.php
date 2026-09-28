<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header("Access-Control-Allow-Origin: *");

$forceDebug = 1;

if ($forceDebug) {
    error_reporting(-1);
    ini_set('display_errors', 1);
} else {
    ini_set('display_errors', 0);
    if (version_compare(PHP_VERSION, '5.3', '>=')) {
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
    } else {
        error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
    }
}


require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class ModulConnect extends REST_Controller
{
    function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->database();
        session_write_close();
        require_once APPPATH . "modules/penjualan/models/MdlPenjualanTransaksi.php";
    }

    public function index_get()
    {
        echo(index);
    }


    public function api_extern_penjualan_get()
    {
        $targetJenis = $this->uri->segment(5);
        $tr = new MdlPenjualanTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("sisa>0");
        $tmpSrc = $tr->lookupPaymentSrcByJenis($targetJenis)->result();
//        $jsonData = json_encode($tmpSrc);
//        $compressedData = gzencode($jsonData);
//        $this->output->set_header('Content-Encoding: gzip');
        $this->response($tmpSrc, 200);
    }

    public function api_select_penjualan_get()
    {
        $master_target = $this->uri->segment(5);
        $externID = $this->uri->segment(6);
        $selectedTrID = $this->uri->segment(7);
        $tr = new MdlPenjualanTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("extern_id='$externID'");
        $tr->addFilter("sisa>0");
        if ($selectedTrID > 0) {
            $tr->addFilter("transaksi_id='$selectedTrID'");
        }
        $tmpSrc = $tr->lookupPaymentSrcByJenis_joined($master_target)->result();

//        $jsonData = json_encode($tmpSrc);
//        $compressedData = gzencode($jsonData);
//        $this->output->set_header('Content-Encoding: gzip');
        $this->response($tmpSrc, 200);
    }

    public function api_return_penjualan_get()
    {

        $this->response($tmpSrc, 200);
    }

    /**
     * api dieksekusi oleh API principal san
     */
    public function api_exec_principal_get()
    {
        $arrDatas = blobDecode($_GET["enc"]);//data dari principal
        $this->db->where($arrDatas);
        $vars = $this->db->get("pembelian_transaksi_bridge")->result();
        if (count($vars) > 0) {
            $respon = array(
                "data" => $vars,
                "status" => 200,
            );
        } else {
            $respon = array(
                "data" => "empty",
                "status" => 404,
            );
        }
        $this->response((object)$respon, 200);
    }

    /**
     * update bridge pembelian detail setelah dieksekusi pre so
     * yang melkukan update API principal/aplikasi utama(san)
     */
    public function api_update_bridge_get()
    {
        $this->db->trans_start();
        $arrDatas = blobDecode($_GET["enc"]);//data dari principal
        if (count($arrDatas) > 0) {
            if (isset($arrDatas["id"])) {
                $where = "id in ('" . implode("','", $arrDatas["id"]) . "')";
            }
            if (isset($arrDatas["update"])) {
                $update = $arrDatas["update"];
            }
            if (count($arrDatas["id"]) > 0 && count($arrDatas["update"]) > 0) {
                $this->db->where($where);
                $insert = $this->db->update("pembelian_transaksi_bridge", $update);
//                cekMerah($this->db->last_query());
                if ($insert) {
                    $status = 200;

                } else {
                    $status = 500;
                }
            } else {
                $status = 404;
            }
        } else {
            $status = 404;
        }
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        $respon = array(
            "status" => $status,
        );

        $this->response((object)$respon, 200);
    }

    /**
     * update bridge pembelian master setelah dieksekusi pre so
     * yang melakukan update API principal/aplikasi utama(san)
     */
    public function api_update_masterBridge_get()
    {
        $this->db->trans_start();
        $arrDatas = blobDecode($_GET["enc"]);//data dari principal
        if (count($arrDatas) > 0) {
            if (isset($arrDatas["id"])) {
                $where = "referensi_id in ('" . implode("','", $arrDatas["id"]) . "')";
            }
            if (isset($arrDatas["update"])) {
                $update = $arrDatas["update"];
            }
            if (count($arrDatas["id"]) > 0 && count($arrDatas["update"]) > 0) {
                $this->db->where($where);
                $insert = $this->db->update("pembelian_transaksi_bridge_master", $update);
//                cekMerah($this->db->last_query());
                if ($insert) {
                    $status = 200;

                } else {
                    $status = 500;
                }
            } else {
                $status = 404;
            }
        } else {
            $status = 404;
        }
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        $respon = array(
            "status" => $status,
        );

        $this->response((object)$respon, 200);
    }

    /**
     * handling multi packinglist
     * jika referensi dan packinglist belum ada ditambahkan
     */
    public function api_masterBridgePL_get()
    {
        /**
         * 1. cari referensi_id dan id packinglist 0
         *    jika ada lakukan update id packinglist
         * 2. jika tidak ada ceklagi apakah ada referensi_id denagan id packinglist yang dikirim
         *    jika ada skip karena sudah terdaftar
         *    jika tidak ada insert baru, asumsi multi packinglist
         *
         */
        $this->db->trans_start();
        $arrDatas = blobDecode($_GET["enc"]);//data dari principal
        if (count($arrDatas) > 0) {
            if (isset($arrDatas["id"])) {
                $where = "referensi_id in ('" . implode("','", $arrDatas["id"]) . "')";
            }
            if (isset($arrDatas["update"])) {
                $update = $arrDatas["update"];
            }
            if (count($arrDatas["id"]) > 0 && count($arrDatas["update"]) > 0) {
                $this->db->where($where);
                $insert = $this->db->update("pembelian_transaksi_bridge_master", $update);
//                cekMerah($this->db->last_query());
                if ($insert) {
                    $status = 200;

                } else {
                    $status = 500;
                }
            } else {
                $status = 404;
            }
        } else {
            $status = 404;
        }
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        $respon = array(
            "status" => $status,
        );

        $this->response((object)$respon, 200);
    }

    public function api_writeBridge_get()
    {

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
        $data_insert = blobDecode($_GET["enc"]);
        $data_master = $data_insert["data"];
        $data_items = $data_master["items"];

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
//        matiHere();
        //region cek data ada belum
        $m->addFilter("referensi_id='" . $data_master["referensi_id"] . "'");
        $tmp = $m->lookUpAll()->result();
        cekMerah($this->db->last_query());
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
                "data" => $data_insert,
            );
            $this->response($vars, 500);
        }

    }

    /**
     * untuk melihat alamat konsumen
     */
    public function api_penjualan_alamat_konsumen_get(){
        $var = isset($_GET["id"]) ? blobDecode($_GET["id"]):array();
        $tr = new MdlPenjualanTransaksi();

        if(count($var)>0){
            $temp = $tr->lookUpSalesAddress($var);
            $vars = array(
                "status_label" => "success",
                "status" => 200,
                "data" => $temp,
            );
            $this->response($vars, 200);
        }
        else{
            $vars = array(
                "status_label" => "empty data",
                "status" => 404,
                "data" => array(),
            );
            $this->response($vars, 200);
//            matiHere("empty params");
        }
//        arrPrint($_GET);
//        matiHere(__LINE__);
    }


}