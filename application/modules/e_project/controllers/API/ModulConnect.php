<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header("Access-Control-Allow-Origin: *");

$forceDebug = 0;

if($forceDebug){
    error_reporting(-1);
    ini_set('display_errors', 1);
}
else{
    ini_set('display_errors', 0);
    if (version_compare(PHP_VERSION, '5.3', '>=')) {
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
    }
    else {
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
        require_once APPPATH . "modules/pembelian/models/MdlProjectTransaksi.php";
    }

    public function index_get()
    {

        echo(index);
    }

    public function api_extern_pembelian_get()
    {
        $targetJenis = $this->uri->segment(5);
        $tr = new MdlProjectTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("sisa>0");
        $tmpSrc = $tr->lookupPaymentSrcByJenis($targetJenis)->result();
//        $jsonData = json_encode($tmpSrc);
//        $compressedData = gzencode($jsonData);
//        $this->output->set_header('Content-Encoding: gzip');
        $this->response($tmpSrc, 200);
    }

    public function api_select_pembelian_get()
    {
        $master_target = $this->uri->segment(5);
        $externID = $this->uri->segment(6);
        $selectedTrID = $this->uri->segment(7);
        $tr = new MdlProjectTransaksi();
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

    public function api_return_pembelian_get()
    {

        $this->response($tmpSrc, 200);
    }
}