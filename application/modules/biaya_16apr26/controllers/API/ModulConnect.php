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
        require_once APPPATH . "modules/biaya/models/MdlBiayaTransaksi.php";
    }

    public function index_get()
    {
        echo(index);
    }

    public function api_extern_biaya_get()
    {
        $targetJenis = $this->uri->segment(5);
        $tr = new MdlBiayaTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("sisa>100");
        $tmpSrc = $tr->lookupPaymentSrcByJenis($targetJenis)->result();
//        $jsonData = json_encode($tmpSrc);
//        $compressedData = gzencode($jsonData);
//        $this->output->set_header('Content-Encoding: gzip');
        $this->response($tmpSrc, 200);
    }

    public function api_select_biaya_get()
    {
        $master_target = $this->uri->segment(5);
        $externID = $this->uri->segment(6);
        $selectedTrID = $this->uri->segment(7);
        $tr = new MdlBiayaTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("extern_id='$externID'");
        $tr->addFilter("sisa>100");
        if ($selectedTrID > 0) {
            $tr->addFilter("transaksi_id='$selectedTrID'");
        }
        $tmpSrc = $tr->lookupPaymentSrcByJenis_joined($master_target)->result();
//        $jsonData = json_encode($tmpSrc);
//        $compressedData = gzencode($jsonData);
//        $this->output->set_header('Content-Encoding: gzip');
        $this->response($tmpSrc, 200);
    }

    public function api_return_biaya_get()
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
        $vars = $this->db->get("biaya_transaksi_bridge")->result();
//        cekHitam($this->db->last_query());
//        arrPrint($vars);
//        cekBiru($this->db->last_query());
//        matiHEre(__LINE__);
//        matiHere();
        if (count($vars) > 0) {
            $respon = array(
                "data" => $vars,
                "status" => 200,
            );
        }
        else {
            $respon = array(
                "data" => "empty",
                "status" => 404,
            );
        }
//        matiHEre(__LINE__);
        $this->response((object)$respon, 200);
    }

    /**
     * update bridge biaya detail setelah dieksekusi pre so
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
                $insert = $this->db->update("biaya_transaksi_bridge", $update);
//                cekMerah($this->db->last_query());
                if ($insert) {
                    $status = 200;

                }
                else {
                    $status = 500;
                }
            }
            else {
                $status = 404;
            }
        }
        else {
            $status = 404;
        }
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        $respon = array(
            "status" => $status,
        );

        $this->response((object)$respon, 200);
    }

    /**
     * update bridge biaya master setelah dieksekusi pre so
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
                $insert = $this->db->update("biaya_transaksi_bridge_master", $update);
//                cekMerah($this->db->last_query());
                if ($insert) {
                    $status = 200;

                }
                else {
                    $status = 500;
                }
            }
            else {
                $status = 404;
            }
        }
        else {
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
//arrPrint($arrDatas);
        $where = array(
            "cli_id" => $arrDatas["cli_id"],
            "principal_spd_id" => $arrDatas["principal_spd_id"],
        );
        $this->db->where($where);
        $vars = $this->db->get("biaya_transaksi_bridge_terima_master")->result();
        if (count($vars) > 0) {
            $status = 200;
//            matiHere();
        }
        else {
            //insert baru
            $adData = array();
            $merger = array_merge($arrDatas, array("dtime" => date("Y-m-d H:i:s"), "fulldate" => date("y-m-d)")));
//            arrprint($merger);
            $this->db->insert("biaya_transaksi_bridge_terima_master", $merger) or matiHere("fail to insert data");
            $status = 200;

        }


//        matiHere(__LINE__);
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        $respon = array(
            "status" => $status,
        );

        $this->response((object)$respon, 200);
    }

}