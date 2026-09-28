<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once "Modul_Controller.php";

class Test extends Modul_Controller
{
    public function __construct()
    {
        parent::__construct();

        /* ----------------------------------------------------------------------------------
         * loader dari main CI
         * ----------------------------------------------------------------------------------*/
        //arrPrintWebs($this->session->login);
//        $this->load->helper("he_stepping");
//        $this->load->helper("he_access_right");
//        $this->load->library("MobileDetect");
//        $this->load->helper("he_session_replacer");
//        $this->load->model("Mdls/MdlCurrency");
//        $this->load->helper('he_angka');
//
//        $this->load->config("heWebs");
//        $maintenanceTransaksi = $this->config->item("maintenanceTransaksi");
//        $this->transaksiMaintenance = $maintenanceTransaksi != null && $maintenanceTransaksi == true ? true : false;
//        $maintenanceOption = $this->config->item("maintenanceOptions");
//        $this->transaksiMaintenanceMsg = isset($maintenanceOption[1]) ? $maintenanceOption[1] : array();
//
//        $this->load->model("Mdls/MdlMongoMother");
//        $this->mongoTableList = array(
//            "main" => "transaksi",
//            "mainValues" => "transaksi_values",
//            "detail" => "transaksi_data",
//            "detailValues" => "transaksi_data_values",
//            "sign" => "transaksi_sign",
//            "extras" => "transaksi_extstep",
//            "registry" => "transaksi_data_registry",
//        );

//        $this->load->model("../../pembelian/models/Coms/ComRekeningPenjualan");
        $this->load->model("../../pembelian/models/Coms/ComRekeningPembelian");
    }

    /* -------------------------------------------------------------------------------------
     * create_form ada di index
     * lanjut_ke -> preview -> save
     * -------------------------------------------------------------------------------------*/
    public function index()
    {
cekHere(__LINE__);
    }




}
