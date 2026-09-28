<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 9/26/2018
 * Time: 5:01 PM
 */
require_once "Modul_Controller.php";

class _processProject extends Modul_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function select()
    {

        $cCode = "_TR_" . $this->jenisTr;
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $nomer = isset($_GET['nomer']) ? $_GET['nomer'] : 0;
        $lab_enc = isset($_GET['lab_enc']) ? base64_decode($_GET['lab_enc']) : "";

        $_SESSION[$cCode]['main']['projectID'] = $id;
        $_SESSION[$cCode]['main']['masterIDPrev'] = $id;
        $_SESSION[$cCode]['main']['nomerPrev'] = $nomer;
        $_SESSION[$cCode]['main']['projectName'] = isset($lab_enc) ? $lab_enc : "";

        echo "<script>";
        echo "top.document.getElementById('projectName').value='" . strtoupper($nomer) . "';";
        echo "top.document.getElementById('pilihan_project').innerHTML='';";
        echo "</script>";

        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());

        $initMasterValues = array(
            "olehID"        => my_id(),
            "olehName"      => my_name(),
            "placeID"       => my_cabang_id(),
            "placeName"     => my_cabang_nama(),
            "divID"         => my_div_id(),
            "divName"       => my_div_nama(),
            "cabangID"      => my_cabang_id(),
            "cabangName"    => my_cabang_nama(),
            "gudangID"      => my_gudang_id(),
            "gudangName"    => my_gudang_nama(),
            "jenis_usaha"   => my_jenis_usaha(),
            "tokoID"        => my_toko_id(),
            "tokoNama"      => my_toko_nama(),
            "ppnFactor"     => my_ppn_factor(),
            "jenisTr"       => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop"    => $this->configUiJenis['steps'][1]['target'],
            "jenisTrName"   => $this->configUiJenis['steps'][1]['label'],
            "dtime"         => dtimeNow(),
            "fulldate"      => dtimeNow("Y-m-d"),
        );

        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);
        fillValues_he_value_builder($this->jenisTr, 1, 1, $this->configCoreJenis, $this->configUiJenis, $this->configValuesJenis, my_ppn_factor());

        /* --------------------------------------------------
             * ngereload shoping cart dlm modul
             * --------------------------------------------------*/
        echo "<script>";
        echo "  top.$('#shopping_cart').load('" . MODUL_PATH . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "</script>";
    }

    public function remove()
    {
        $cCode = $this->cCode;

        $_SESSION[$cCode]['main']['projectID'] = null;
        $_SESSION[$cCode]['main']['projectName'] = null;
        $_SESSION[$cCode]['main']['projectName2'] = null;
        unset($_SESSION[$cCode]['main']['projectID']);
        unset($_SESSION[$cCode]['main']['projectName']);
        unset($_SESSION[$cCode]['main']['projectName2']);

        $_SESSION[$cCode]['main']['project2ID'] = null;
        $_SESSION[$cCode]['main']['project2Name'] = null;
        $_SESSION[$cCode]['main']['project2Name2'] = null;
        unset($_SESSION[$cCode]['main']['project2ID']);
        unset($_SESSION[$cCode]['main']['project2Name']);
        unset($_SESSION[$cCode]['main']['project2Name2']);

    }

}