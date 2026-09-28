<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 9/18/2018
 * Time: 8:45 PM
 */
require_once "Modul_Controller.php";
class _processSelectProductPpn extends Modul_Controller
{
    public function __construct()
    {
        parent::__construct();
//        $this->jenisTr = $this->uri->segment(4);
//        $cCode = "_TR_" . $this->jenisTr;
//        if (!isset($_SESSION[$cCode]['items'])) {
//            $_SESSION[$cCode]['items'] = array();
//        }

    }

    public function select()
    {
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();
        $ppn = $_GET['ppn'];
        $ppnTargetItems = $_GET['ppnTargetItems'];
        $ppnTargetMain = $_GET['ppnTargetMain'];

        $overWriteVendor = isset($_GET['overWriteMain']) ? $_GET['overWriteMain'] : "ppnVendor";

        $cCode = $this->cCode;

        if (isset($_SESSION[$cCode]['items'])) {
            $newPpn = 10 * $ppn;
            if (isset($_SESSION[$cCode]['main'])) {
                if (!isset($_SESSION[$cCode]['main'][$ppnTargetMain])) {
                    $_SESSION[$cCode]['main'][$ppnTargetMain] = array();
                }
                $_SESSION[$cCode]['main'][$ppnTargetMain] = $ppn;
                $_SESSION[$cCode]['main'][$overWriteVendor] = $newPpn;
            }
            foreach ($_SESSION[$cCode]['items'] as $id => $aaaaaaaaaaaaaaa) {
                $_SESSION[$cCode]['items'][$id][$ppnTargetItems] = $newPpn;
                $_SESSION[$cCode]['items'][$id][$overWriteVendor] = $newPpn;
                // arrPrint( $_SESSION[$cCode]['items'][$id][$ppnTargetItems]);
            }
        }

//arrPrintPink($this->configCoreJenis);
        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, 1, $this->configUiJenis);
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);
        echo "<script>";
        echo "  if(top.document.getElementById('shopping_cart')){";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "  }";
        echo "</script>";
//        if(isset($_GET['spc'])){
//            echo "<script>";
//            echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=0&spc=1')";
//            echo "</script>";
//        }
//        else{
//            echo "<script>";
//            echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=0')";
//            echo "</script>";
//        }
    }
}