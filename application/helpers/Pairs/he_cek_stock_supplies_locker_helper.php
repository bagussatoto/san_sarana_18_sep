<?php

function cekStockSuppliesLocker($tr, $stepNumber, $paramsFilter = array(), $gate)
{

    $cCode = "_TR_" . $tr;
//    $paramsFilter = isset(config_item('heTransaksi_ui')[$tr]['pairMakers'][$stepNumber]['stokSupplies']['params']) ? config_item('heTransaksi_ui')[$tr]['pairMakers'][$stepNumber]['stokSupplies']['params'] : array();

    $pIDs = array();
    if(isset($_SESSION[$cCode][$gate]) && (sizeof($_SESSION[$cCode][$gate])>0)){
        foreach ($_SESSION[$cCode][$gate] as $iSpec){
            $pIDs[] = $iSpec['id'];
        }
    }

    $ci =& get_instance();
    $ci->load->model("Mdls/MdlLockerStockSupplies");
    $cs = New MdlLockerStockSupplies();
    if(sizeof($pIDs)>0){
        $cs->addFilter("produk_id in ('" . implode("','", $pIDs) . "')");
    }
    if (sizeof($paramsFilter) > 0) {
        foreach ($paramsFilter as $key => $val) {
            $realVal = makeValue($val, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
            $cs->addFilter("$key='$realVal'");
        }
        $tmpResult = $cs->lookupAll()->result();
//        cekhere($ci->db->last_query());
    }
    else {
        $tmpResult = array();
    }


    $result = array();
    if (sizeof($tmpResult) > 0) {
        foreach ($tmpResult as $eSpec) {

            $result[$eSpec->produk_id] = $eSpec->jumlah;
        }
    }
//arrPrint($result);
//mati_disini();
    return $result;
}