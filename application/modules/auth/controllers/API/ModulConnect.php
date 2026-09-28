<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header("Access-Control-Allow-Origin: *");

$forceDebug = 1;

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
        // require_once APPPATH . "modules/pembelian/models/MdlPembelianTransaksi.php";
    }

    public function index_get()
    {
        echo(index);
    }

    public function cp_data_get()
    {
        $this->load->model("Mdls/MdlCompany");
        $tr = new MdlCompany();

        $tmpSrc = $tr->lookupJoint();

        $this->response($tmpSrc, 200);
    }


}