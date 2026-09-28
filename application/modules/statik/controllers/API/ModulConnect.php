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

    public function api_writeEmployee_get()
    {
        $this->db->trans_start();
        $this->load->model("Mdls/MdlEmployee");
        $m = new MdlEmployee();
        $data_insert = blobDecode($_GET["enc"]);
        $data_master = $data_insert["data"];
        $data_items = $data_master["items"];

//        arrprint($data_insert);

        //region cek data ada belum
        $m->addFilter("referensi_crm_account='" . $data_master["referensi_crm_account"] . "'");
        $tmp = $m->lookUpAll()->result();
        if (count($tmp) > 0) {
            //skip sudah ada
            $insert = 1;
//            cekMErah("sekippp");
        } else {
            $m->setFilters(array());
            if (count($data_items) > 0) {
                $insert_data = array(
                    "nama"=>$data_items["first_name"],
                    "nama_depan"=>$data_items["first_name"],
                    "nama_belakang"=>$data_items["last_name"],
                    "email"=>$data_items["email"],
                    "referensi_crm_account"=>$data_master["referensi_crm_account"],
                );
                    $insert = $m->addData($insert_data);
            }
        }
        //endregion
//matiHEre(__LINE__);
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


}