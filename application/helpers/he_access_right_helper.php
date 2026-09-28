<?php
/**
 * Created by PhpStorm.
 * User: widi
 * Date: 12/09/2019
 * Time: 14:00
 */

function callAvailTransaction()
{
    $ci = &get_instance();
    $availTrans = $ci->config->item("heTransaksi_ui");
    $availStepTemp = array();
    foreach ($availTrans as $jenis => $details) {
        $steps = $details["steps"];
        $tempAvail = array();
        foreach ($steps as $steps => $stepDetails) {
            $steps_label = $stepDetails["label"];
            $tempAvail[$steps] = array(
                "source" => $stepDetails["source"],
                "target" => $stepDetails["target"],
            );
        }
        $availStepTemp[$jenis] = $tempAvail;
    }
    return $availStepTemp;
}

function availGroupAccess()
{
    $ci = &get_instance();

    // $membership = $ci->session->login['membership'];
    $availTrans = $ci->config->item("heTransaksi_ui");
    $availPlace = $ci->config->item("userPlace_allowed");
    $availStepTemp = array();
    foreach ($availTrans as $jenis => $details) {
        $stepes = $details["steps"];
        $parentLabels = $details["label"];
        $place = $details["place"];
        if (in_array($place, $availPlace)) {

            $tempAvail = array();
            foreach ($stepes as $steps => $stepDetails) {
                $steps_label = $stepDetails["label"];
                $steps_userLevel = isset($stepDetails["userLevel"]) ? $stepDetails["userLevel"] : "no lepel";
                // cekHere($steps_label);
                // $access_group = $stepDetails["userGroup"];
                $access_group = isset($stepDetails["userFungsi"]) ? $stepDetails["userFungsi"] : "no";
                // if (in_array($access_group, $availGroup_allowed)) {
                if ($access_group != 0) {
                    $tempAvail[$access_group][$steps][] = $access_group;

                    $availStepTemp[$place][$access_group][$jenis] = $tempAvail[$access_group];
                }
            }
        }
    }
    // arrPrintKuning($availStepTemp);

    $temData = array();
    foreach ($availStepTemp as $place => $tempG) {
        $temp = array();
        foreach ($tempG as $gJenisTr => $gAvail) {
            foreach ($gAvail as $gId => $step) {
                $temp[$gId][$gJenisTr] = $step;
            }
        }
        $temData[$place] = $temp;
    }


    return $availStepTemp;
}

function groupAlias()
{
    $ci = &get_instance();
    $gDev = $ci->config->item("userGroup_root");
    $gCenter = $ci->config->item("userGroup");
    $gBrach = $ci->config->item("userGroup_cabang");
    $gWarehouse = $ci->config->item("userGroup_gudang");

    $groupAlias = array_merge($gDev, $gCenter, $gBrach, $gWarehouse);
    return array_flip($groupAlias);

}

function transactionStepAlias()
{
    $ci = &get_instance();
    $availTrans = $ci->config->item("heTransaksi_ui");
    $availStepTemp = array();
    foreach ($availTrans as $jenis => $details) {
        $steps = $details["steps"];
        $tempAvail = array();
        foreach ($steps as $steps => $stepDetails) {
            $steps_label = $stepDetails["label"];
            $tempAvail[$steps] = $steps_label;
        }
        $availStepTemp[$jenis] = $tempAvail;
    }
    return $availStepTemp;
}

function transactionJenisAlias()
{
    $ci = &get_instance();
    $availTrans = $ci->config->item("heTransaksi_ui");
    $availStepTemp = array();
    foreach ($availTrans as $jenis => $details) {
        $steps = $details["steps"];
        $tempAvail = array();

        $availStepTemp[$jenis] = $details["label"];
    }
    return $availStepTemp;
}

function availCreatorCenter($place)
{
    $ci = &get_instance();
    $availTrans = $ci->config->item("heTransaksi_ui");
    $availStepTemp = array();
    foreach ($availTrans as $jenis => $details) {
        $steps = $details["steps"][1];
        $place2 = $details['place'];

        $availStepTemp[$place2][$jenis] = 1;
    }
    $temp = array();
    foreach ($availStepTemp as $placeID => $placeData) {
        cekHere("$placeID**");
        if ($placeID == $place) {

        }
        else {
            foreach ($placeData as $jn => $value) {
                $temp[$jn] = array($value => "0");
            }
        }

    }
    return $temp;
}

function allowedAccessPlace($place = "")
{
    $ci = &get_instance();

    $availDatas = $ci->config->item("heDataBehaviour");
    $availTrans = $ci->config->item("heTransaksi_ui");
    $dtMdl = array_keys($availDatas);
    $jenise = [];
    foreach ($availTrans as $trJenis => $availTran) {
        $place_ui = $availTran['place'];

        if (is_array($place_ui)) {
            foreach ($place_ui as $itemPlace) {
                $jenise[$itemPlace][] = $trJenis;
            }
        }
        else {
            $jenise[$place_ui][] = $trJenis;
        }
    }


    $trJenis = $jenise;
    if ($place != "") {
        $trJenis = $jenise[$place];
    }
    else {
        $trJenis = $jenise;
    }

    /* -------------------------------------------------------------------------------
     * menggabungkan jenistr dan mdl
     * -------------------------------------------------------------------------------*/

    return array_merge($trJenis, $dtMdl);
}

function alowedAccess($employee_id, $tr_jenis = "")
{
    $ci = &get_instance();

//    $tr_jenis="466";
    $ci->load->model("Mdls/MdlMenu");
    $ma = new MdlMenu();
    if ($tr_jenis != "") {
        $condites = [
            "menu_category" => $tr_jenis,
//            "steps" => 2
        ];
        $ma->setConditional($condites);
    }

    $tmpDatas = $ma->lookupMyMenu($employee_id)->result();
//    showLast_query("merah");
    $kode = "";
    $eAvailAkses = "";
    foreach ($tmpDatas as $eData) {
        $eAvail[$eData->menu_category][$eData->steps][$eData->crud_id] = $eData->crud_id;
        $kode = $eData->steps . $eData->crud_id;
        if (CB_ID_PUSAT) {

        }
        $eAvailAkses[$eData->menu_category][$kode] = $eData->steps;
    }

    $vars = array();
    $vars["datas"] = $eAvail;
    $vars["akses"] = $eAvailAkses;

    return $vars;
}

function subPlaceStep()
{
    $ci = &get_instance();
    $availTrans = $ci->config->item("heTransaksi_ui");
    $availStepTemp = array();
    foreach ($availTrans as $jenis => $details) {
        $steps = $details["steps"];
        foreach ($steps as $step => $stepDetails) {
            if (isset($stepDetails["subplace"])) {

                $subplace = $stepDetails["subplace"];
                $availStepTemp[$jenis][$step] = $subplace;
            }

        }
    }
    return $availStepTemp;
}

//---------------------------
function factoryAccess()
{
    $ci = &get_instance();

    $availTrans = $ci->config->item("heTransaksi_ui");
    $availPlace = $ci->config->item("userPlace_allowed");
    $availGroup = $ci->config->item("userGroup") != null ? array_keys($ci->config->item("userGroup")) : array();
    $availGroup_cabang = $ci->config->item("userGroup_cabang") != null ? array_keys($ci->config->item("userGroup_cabang")) : array();
    $availGroup_gudang = $ci->config->item("userGroup_gudang") != null ? array_keys($ci->config->item("userGroup_gudang")) : array();
    $availGroup_allowed = array_merge($availGroup, $availGroup_cabang, $availGroup_gudang);
    $placeExtend = "factory";

    $availStepTemp = array();
    foreach ($availTrans as $jenis => $details) {
        if (isset($details['placeExtended']) && ($details['placeExtended'] == $placeExtend)) {
            $steps = $details["steps"];
            $parentLabels = $details["label"];
            $place = $details["place"];
            $tempAvail = array();
            if (in_array($place, $availPlace)) {
                foreach ($steps as $steps => $stepDetails) {
                    $steps_label = $stepDetails["label"];
                    $access_group = $stepDetails["userGroup"];

                    if (in_array($access_group, $availGroup_allowed)) {

                        $tempAvail[$steps][] = $access_group;
                    }
                }
                $availStepTemp[$place][$jenis] = $tempAvail;
            }
        }

    }

    return $availStepTemp;
}

//-VERSI MODUL---------------
function callAvailTransaction_he_access_right($configUi)
{
    $ci = &get_instance();
    $availTrans = $configUi;
    $availStepTemp = array();
    foreach ($availTrans as $jenis => $details) {
        $steps = $details["steps"];
        $tempAvail = array();
        foreach ($steps as $steps => $stepDetails) {
            $steps_label = $stepDetails["label"];
            $tempAvail[$steps] = array(
                "source" => $stepDetails["source"],
                "target" => $stepDetails["target"],
            );
        }
        $availStepTemp[$jenis] = $tempAvail;
    }
    return $availStepTemp;
}

function availGroupAccess_he_access_right($configUi)
{
    $ci = &get_instance();

    $membership = $ci->session->login['membership'];
    $availTrans = $configUi;
    $availPlace = $ci->config->item("userPlace_allowed");
    $availGroup = $ci->config->item("userGroup") != null ? array_keys($ci->config->item("userGroup")) : array();
    $availGroup_cabang = $ci->config->item("userGroup_cabang") != null ? array_keys($ci->config->item("userGroup_cabang")) : array();
    $availGroup_gudang = $ci->config->item("userGroup_gudang") != null ? array_keys($ci->config->item("userGroup_gudang")) : array();
    $availGroup_allowed = array_merge($availGroup, $availGroup_cabang, $availGroup_gudang);

    $availStepTemp = array();
    foreach ($availTrans as $jenis => $details) {
        $steps = $details["steps"];
        $parentLabels = $details["label"];
        $place = $details["place"];
        $tempAvail = array();
        if (in_array($place, $availPlace)) {

            foreach ($steps as $steps => $stepDetails) {
                $steps_label = $stepDetails["label"];
                $access_group = $stepDetails["userGroup"];
                if (in_array($access_group, $availGroup_allowed)) {
                    $tempAvail[$steps][] = $access_group;
                }
            }
            $availStepTemp[$place][$jenis] = $tempAvail;
        }

    }

    $temData = array();
    foreach ($availStepTemp as $place => $tempG) {
        $temp = array();
        foreach ($tempG as $gJenisTr => $gAvail) {
            foreach ($gAvail as $gId => $step) {
                $temp[$gId][$gJenisTr] = $step;
            }
        }
        $temData[$place] = $temp;
    }


    return $availStepTemp;
}

function transactionStepAlias_he_access_right($configUi)
{
    $ci = &get_instance();
    $availTrans = $configUi;
    $availStepTemp = array();
    foreach ($availTrans as $jenis => $details) {
        $steps = $details["steps"];
        $tempAvail = array();
        foreach ($steps as $steps => $stepDetails) {
            $steps_label = $stepDetails["label"];
            $tempAvail[$steps] = $steps_label;
        }
        $availStepTemp[$jenis] = $tempAvail;
    }
    return $availStepTemp;
}

function transactionJenisAlias_he_access_right($configUi)
{
    $ci = &get_instance();
    $availTrans = $configUi;
    $availStepTemp = array();
    foreach ($availTrans as $jenis => $details) {
        $steps = $details["steps"];
        $tempAvail = array();

        $availStepTemp[$jenis] = $details["label"];
    }
    return $availStepTemp;
}

function availCreatorCenter_he_access_right($place, $configUi)
{

    $ci = &get_instance();
    $availTrans = $configUi;
    $availStepTemp = array();
    foreach ($availTrans as $jenis => $details) {
        $steps = $details["steps"][1];
        $place2 = $details['place'];

        $availStepTemp[$place2][$jenis] = 1;
    }
    $temp = array();
    foreach ($availStepTemp as $placeID => $placeData) {
        //        cekHere("$placeID**");
        if ($placeID == $place) {

        }
        else {
            foreach ($placeData as $jn => $value) {
                $temp[$jn] = array($value => "0");
            }
        }

    }

    return $temp;
}

function alowedAccess_he_access_right($employee_id, $configUi)
{
    // return $eAvail;
    return alowedAccess($employee_id, $configUi);


}

//----------------------
function groupAccessLabel_he_access_right()
{
    $ci = &get_instance();
    $availTrans = $ci->config->item("heTransaksi_ui");
    $arrGroupLabel = array();
    foreach ($availTrans as $jenis => $spec) {
        foreach ($spec['steps'] as $step => $subSpec) {
            if (!isset($subSpec['userGroup'])) {
                $subSpec['userGroup'] = "o_none";
            }
            if (!isset($arrGroupLabel[$subSpec['userGroup']][$jenis]['sublabel'])) {
                $arrGroupLabel[$subSpec['userGroup']][$jenis]['sublabel'] = array();
            }
            $arrGroupLabel[$subSpec['userGroup']][$jenis]['label'] = $spec['label'];
            $arrGroupLabel[$subSpec['userGroup']][$jenis]['sublabel'][] = $subSpec['label'];
        }
    }
    return $arrGroupLabel;
}

function customAccessJenis_he_access_right($employee_id, $jenisTr, $configUiJenis)
{

    $ci = &get_instance();
    $ci->load->model("Mdls/MdlAccessRight");
    $a = new MdlAccessRight();
    $a->addFilter("trash='0'");
    $a->addFilter("employee_id='$employee_id'");
    $a->addFilter("menu_category='$jenisTr'");
    $customAcces = $a->lookupAll()->result();
    //    $customAcces = array();
    if (sizeof($customAcces) > 0) {
        if (sizeof($customAcces) == 1) {
            // hanya 1, dan create maka masuk ke create (step 1)
            if ($customAcces[0]->steps == 1) {
                $link_redirect = MODUL_PATH . "Create/index/$jenisTr?gr=$getGr";
            } // hanya 1, dan bukan create maka masuk ke index (step > 1)
            else {
                //                    $link_redirect = MODUL_PATH . "Transaksi/index/$jenisTr?gr=$getGr";
                $link_redirect = NULL;
            }
        }
        else {
            // lebih dari 1, maka masuk ke index
            //                $link_redirect = MODUL_PATH . "Transaksi/index/$jenisTr?gr=$getGr";
            $link_redirect = NULL;
        }
    }
    else {
        // tidak punya hak akses custom $this->>jenisTr .....
        $link_redirect = NULL;
        $mems = $ci->session->login['membership'];
        $groups = array();
        foreach ($configUiJenis['steps'] as $step => $data) {
            $groups[$data['userGroup']] = $step;
        }

        $groupsResult = array();
        foreach ($mems as $membership) {
            if (array_key_exists($membership, $groups)) {
                $groupsResult[] = $groups[$membership];
            }
        }

        if (sizeof($groupsResult) > 0) {
            if (sizeof($groupsResult) == 1) {
                // hanya 1, dan create maka masuk ke create (step 1)
                if ($groupsResult[0] == 1) {
                    $link_redirect = MODUL_PATH . "Create/index/$jenisTr?gr=$getGr";
                } // hanya 1, dan bukan create maka masuk ke index (step > 1)
                else {
                    //                    $link_redirect = MODUL_PATH . "Transaksi/index/$jenisTr?gr=$getGr";
                    $link_redirect = NULL;
                }
            }
            else {
                // lebih dari 1, maka masuk ke index
                //                $link_redirect = MODUL_PATH . "Transaksi/index/$jenisTr?gr=$getGr";
                $link_redirect = NULL;
            }
        }
    }


    return $link_redirect;
}

//----------------------
function alowedAccessManufactur_he_access_right($employee_id)
{
    //    $xT = callAvailTransaction();
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlAccessRightManufactur");
    $a = new MdlAccessRightManufactur();
    $a->addFilter("trash='0'");
    $a->addFilter("employee_id='$employee_id'");
    $customAcces = $a->lookupAll()->result();
    //    cekHere($ci->db->last_query());
    if (sizeof($customAcces) > 0) {
        $eAvail = array();
        foreach ($customAcces as $eData) {
            //            if (isset($xT[$eData->menu_category][$eData->steps])) {
            //                $allowCreate = isset($xT[$eData->menu_category][$eData->steps]) && strlen($xT[$eData->menu_category][$eData->steps]['source']) > 0 ? false : true;
            //
            //                $allowReject = $eData->steps > 1 ? true : false;
            //                $allowDelete = $eData->steps == 1 ? true : false;
            //                $allowEdit = $eData->steps == 1 ? true : false;
            //                $allowUndo = isset($xT[$eData->menu_category][$eData->steps]) && strlen($xT[$eData->menu_category][$eData->steps]['target']) > 0 ? true : false;
            $allowCreate = true;
            $eAvail[$eData->menu_category][$eData->steps][$eData->steps_code] = array(
                "allowCreate" => $allowCreate,
                //                    "allowFollowUp" => $allowFollowUp,
                //                    "allowReject" => $allowReject,
                //                    "allowDelete" => $allowDelete,
                //                    "allowEdit" => $allowEdit,
                //                    "allowUndo" => $allowUndo,
            );
            //            }
        }
    }
    else {
        $eAvail = array();
    }

    return $eAvail;


}

function customProjectAccess($id, $membersip, $status_produk = "0")
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlProjectInternAccessList");
    $ci->load->model("Mdls/MdlProjectInternAccessMember");
    $m = new MdlProjectInternAccessList();
    $ml = new MdlProjectInternAccessMember();
    $tmp = $m->lookUpAll()->result();
//    showlast_query("hitam");
//    arrPrint($tmp);

    $ml->addFilter("per_employee_id='$id'");
    $tmpMember = $ml->lookUpAll()->result();
//    showlast_query("hitam");
//    arrPrint($tmpMember);

    $dataGroup = array();
    if (count($tmp) > 0) {
        foreach ($tmp as $tmp_0) {
            $dataGroup[$tmp_0->access_id][$tmp_0->access_metode][] = $tmp_0->mdl_name;
        }
    }

    $dataMemAllowed = array();
    if (count($tmpMember) > 0) {
        if ($membersip == "o_project_spv") {
            $dataMemAllowed[$id] = 1;
        }
        else {
            foreach ($tmpMember as $tmpMember_0) {
                $dataMemAllowed[$id] = $tmpMember_0->acc_level;
            }
        }
    }
    else {
        if ($membersip == "o_project_spv") {
            $dataMemAllowed[$id] = 1;
        }
    }

    $data = array();
    if (count($dataMemAllowed) > 0) {
        foreach ($dataMemAllowed as $mid => $master_mid) {
            $data = isset($dataGroup[$master_mid]) ? $dataGroup[$master_mid] : array();
        }
    }
    return $data;

}

function groupMaster()
{
    $ci = &get_instance();
    $availTrans = $ci->config->item("heTransaksi_ui");
    $dtaGrup = array();
    foreach ($availTrans as $jenis => $tmp) {
        $grup = isset($tmp["grupMenu"]) ? $tmp["grupMenu"] : 6;
        $dtaGrup[$grup][] = $jenis;

    }
    return $dtaGrup;
}

function groupPlaceMaster()
{
    $ci = &get_instance();
    $availTrans = $ci->config->item("heTransaksi_ui");
    $dtaGrup = array();
    foreach ($availTrans as $jenis => $tmp) {
        $grup = isset($tmp["grupMenu"]) ? $tmp["grupMenu"] : 6;
        $dtaGrup[$tmp['place']][$grup][] = $jenis;

    }
    return $dtaGrup;
}

function availGroupPlace()
{
    $ci = &get_instance();

    $membership = $ci->session->login['membership'];
    $availTrans = $ci->config->item("heTransaksi_ui");
    $availPlace = $ci->config->item("userPlace_allowed");
    $availGroup = $ci->config->item("userGroup") != null ? array_keys($ci->config->item("userGroup")) : array();
    $availGroup_cabang = $ci->config->item("userGroup_cabang") != null ? array_keys($ci->config->item("userGroup_cabang")) : array();
    $availGroup_gudang = $ci->config->item("userGroup_gudang") != null ? array_keys($ci->config->item("userGroup_gudang")) : array();
    $availGroup_allowed = array_merge($availGroup, $availGroup_cabang, $availGroup_gudang);
    //matiHEre("uu");
    $availStepTemp = array();
    foreach ($availTrans as $jenis => $details) {
        $steps = $details["steps"];
        $parentLabels = $details["label"];
        $place = $details["place"];
        if (in_array($place, $availPlace)) {

            foreach ($steps as $steps => $stepDetails) {
                $steps_label = $stepDetails["label"];
                if ($steps > 1) {
                    $availStepTemp[$place][] = $jenis;
                }
                //                $access_group = $stepDetails["userGroup"];
                //                if (in_array($access_group, $availGroup_allowed)) {
                ////                    cekHere("jenis: $jenis, $access_group");
                //                    $tempAvail[$steps][] = $access_group;
                //                }
            }
            //            $availStepTemp[$place][$jenis] = $tempAvail;
        }
    }

    return $availStepTemp;
}

function dataLabel()
{
    $data = array(
        "viewers" => "lihat",
        "creators" => "tambah ",
        "updaters" => "ubah/perbaharui ",
        "deleters" => "hapus",
        "historyViewers" => "histori",
    );
    return $data;
}

function dataAdditionalLabel()
{
    $data = array(
        "allow" => "lihat",
        // "creators"       => "tambah ",
        // "updaters"       => "ubah/perbaharui ",
        // "deleters"       => "hapus",
        // "historyViewers" => "histori",
    );
    return $data;
}

/*
 * baca dari data behavior semua model yang avaible
 */
function availMenuConfigData()
{
    $ci = &get_instance();
    $dataModel = $ci->config->item("heDataBehaviour");
    $dataTmp = array();
    foreach ($dataModel as $MdlName => $data_0) {
        $dataTmp[$MdlName] = array(
            "restriction" => isset($data_0["restriction"]) ? $data_0["restriction"] : false,
            "allowedGroup" => isset($data_0["allowedRestriction"]) ? $data_0["allowedRestriction"] : array(),
            "model" => $MdlName,
            "nama" => $data_0["label"],
            "label" => $data_0["label"],
            "default" => isset($data_0["default"]) ? $data_0["default"] : "",
        );
    }
    return $dataTmp;
}

/*
 * baca dari data set menu data sesuai employee
 */
function availMenuData($mployeeid)
{
    $ci = &get_instance();
    $availConfig = availMenuConfigData();
    $membership = isset($ci->session->login["membership"]) ? $ci->session->login["membership"] : array();
    $ci->load->model("Mdls/MdlDataAccessRight");
    $m = new MdlDataAccessRight();
    $m->addFilter("employee_id='$mployeeid'");
    $tmp = $m->lookUpAll()->result();
    // showLast_query("merah");
    if (sizeof($tmp) > 0) {
        foreach ($tmp as $temp_0) {
            $mdlName = $temp_0->mdl_name;
            $steps = $temp_0->steps;
            $label = isset($availConfig[$mdlName]["label"]) ? $availConfig[$mdlName]["label"] : "";
            $tmpLabel = str_replace("Mdl", "", $mdlName);
            switch ($steps) {
                case "viewers":
                    $datas["dataMenus"][$tmpLabel] = array(
                        "label" => $label . createObjectSuffix($label),
                        "badge" => "<sup><span id='crdta$tmpLabel'></span><span id='crdtb$tmpLabel'></span></sup>",
                    );
                    $datas["allowedDataMenus"][$mdlName] = $mdlName;


                    break;
                case "creators":
                    $datas["dataMenus"][$tmpLabel] = array(
                        "label" => $label . createObjectSuffix($label),
                        "badge" => "<sup><span id='crdta$tmpLabel'></span><span id='crdtb$tmpLabel'></span></sup>",
                    );
                    $datas["allowedDataMenus"][$mdlName] = $mdlName;
                    break;
            }
            $membership = isset($steps) ? 1 : 0;
            $datas["fungsi"][$mdlName][$steps] = $membership;
        }
    }
    else {
        $datas = array();
    }

    return $datas;
}

/* -------------------------------------------------------------------------------------------------------------
 * custom hak akses additional dijakian satu untuk menu yang menggunakan controller
 * cintoh reporting
 * leger
 * mutasi
 */
function availMenuconfigAdditional($jenisAkun)
{
    /*
     * jenis akun merupaka super user yang bisa memberikan hak aksesnya ke akun lain misalkan o_holding
     */
    $ci = &get_instance();
    $dataModelSerc = $ci->config->item("groupMenu");
    $dataModel = $ci->config->item("menu")[$jenisAkun];
    $avaibleGrMenu = array();
    foreach ($dataModelSerc as $grMenu => $grData) {
        foreach ($dataModel as $i => $iKey) {
            if (isset($grData["availMenu"][$iKey])) {
                // cekMErah($iKey);
                $avaibleGrMenu[$grMenu]["label"] = $grData["label"];
                $avaibleGrMenu[$grMenu]["icon"] = $grData["icon"];
                $avaibleGrMenu[$grMenu]["tanpaCbNama"] = isset($grData["tanpaCbNama"]) ? $grData["tanpaCbNama"] : "";
                $avaibleGrMenu[$grMenu]["availMenu"][$iKey] = $grData["availMenu"][$iKey];

            }
        }
    }
    return $avaibleGrMenu;
}

function availMenuAdditional($jenisAKun)
{
    /*
     * jenis akun merupaka super user yang bisa memberikan hak aksesnya ke akun lain misalkan o_holding
     */
    $ci = &get_instance();
    $dataModelSerc = availMenuconfigAdditional($jenisAKun);
    $avaibleGrMenu = array();
    if (count($dataModelSerc) > 0) {
        foreach ($dataModelSerc as $grMenu => $grData) {

        }
    }
    matiHere(__LINE__);
}

function menuTitleAliasing()
{
    $ci = &get_instance();
    $availTrans = $ci->config->item("heTransaksi_ui");
    $availStepTemp = array();
    foreach ($availTrans as $jenis => $details) {
        $steps = $details["steps"];
        $availStepTemp[$jenis] = isset($details["deskripsi"]) ? $details["deskripsi"] : $details["label"];;
    }
    return $availStepTemp;
}

function availMembershipAndCabang($employee_id)
{
    /*
     * load modeluntuk load data membeship dari tabele  per_employee_membership sebagai acuan group utama
     * load modeluntuk load data membeship cabang dari tabele  per_employee_membership_cabang untuk multi cabang akses
     */

    $ci = &get_instance();
    $ci->load->model("Mdls/MdlCabang");//realsi dengan membersip
    $ci->load->model("Mdls/MdlEmployeeMembershipCabang");//relasi dengan cabang
    $c = new MdlCabang();
    $mc = new MdlEmployeeMembershipCabang();

    $allCabangTmp = $c->lookUpAll()->result();
//    showLast_query("biru");
    $allCabang = array();
    foreach ($allCabangTmp as $allCabang_0) {
        $allCabang[$allCabang_0->id] = array(
                "status_id" => $allCabang_0->status_id,
                "status_nama" => $allCabang_0->status_nama,
                "place" => $allCabang_0->status_nama,
            ) + (array)$allCabang_0;
    }
    $tempMember = $mc->lookUpAll()->result();

    $mc->addFilter("employee_id='$employee_id'");
    $ci->db->order_by("cabang_id", "asc");
    $tempcabang = $mc->lookUpall()->result();
    // showLast_query("biru");
    $result = array();


//    if (count($tempcabang) > 0) {
//        foreach ($tempcabang as $tempcabang_0) {
//            $cb_id = $tempcabang_0->cabang_id;
//            $cb_nama = $allCabang[$cb_id]["nama"];
//            $result["cabang"][$cb_id] = array(
//                    "id"          => $cb_id,
//                    "nama"        => $cb_nama,
//                    "cabang_id"   => $cb_id,
//                    "cabang_nama" => $cb_nama,
//                ) + $allCabang[$cb_id];
//
//        }
//    }

    if (count($tempcabang) > 0) {
        foreach ($tempcabang as $tempcabang_0) {
            $cb_id = $tempcabang_0->cabang_id;
            $cb_nama = $allCabang[$cb_id]["nama"];

            if (isset($allCabang[$cb_id])) {
                $result["cabang"][$cb_id] = array(
                    "id" => $cb_id,
                    "nama" => $cb_nama,
                    "cabang_id" => $cb_id,
                    "cabang_nama" => $cb_nama,
                );
                foreach ($allCabang[$cb_id] as $key => $val) {
                    $result["cabang"][$cb_id][$key] = $val;
                }
            }
        }
    }
//    arrPrint($result);

    return $result;
}

function validatePlaceActive($placeID, $placeName)
{
    $ci = &get_instance();
    $current_active_place = my_cabang_id();
    if ($placeID == $current_active_place) {
//        matiHEre("lanjut sesi valid");
    }
    else {
        $active_cabang_nama = my_cabang_nama();
//        $label = "anda membuka lebih dari 1 tab browser dan berbeda lokasi login<br>";
        $label = "Anda ganti cabang ke <b>$active_cabang_nama </b><br>";
        $label .= "transaksi yang anda followup milik  <b>$placeName</b><br>";
        $label .= "silahkan login sesuai lokasi untuk followup transaksi ini<br>";
        matiHEre($label);
    }
    return true;

}

function validateallowAccess($id, $jenisTr, $url, $curent_place)
{
    $availTrans = callAvailTransPlace();
}

function callAvailTransPlace()
{
    $ci = &get_instance();
    $allConfigUi = $ci->load->config->item("heTransaksi_ui");
    $data = array();
    foreach ($allConfigUi as $m_jenis => $mData) {
        $data[$m_jenis] = $mData["place"];
    }
    return $data;

}

//untuk validasi place diijinkan untuk transaksi
function validateAllowPlace($jenisMaster, $curentBranch, $modal = false)
{
    $ci = &get_instance();
    $cabangAliasing = cabangStatus_he_misc();
    $ci->load->model("Mdls/MdlCabang");
    $c = new MdlCabang();
    $temp = $c->callSpecs_($curentBranch);
    $dataCabang = $c->lookUpTypeCabang($curentBranch);
    $new_login_cabang_nama = my_cabang_nama();
    $trans_login_cabang_nama = $temp[$curentBranch]->nama;
    $dataMsg = array();
    foreach ($dataCabang as $status_id => $label) {
        //        cekBiru($cabangAliasing[$status_id]["alias"] . "$status_id" . $label);
        if (isset($cabangAliasing[$status_id]) && $cabangAliasing[$status_id]["nama"] == $label) {
            //            cekHitam($cabangAliasing[$status_id]["alias"]);
            $place = $cabangAliasing[$status_id]["alias"];
            //cek apakah boleh traksi di cabang terpilih
            $allowPlaceTrans = allowedAccessPlace($place);
            if (in_array($jenisMaster, $allowPlaceTrans)) {
                /**
                 * pass boleh transaksi sesuai lokasi untuk transaksi
                 */
                //                matiHere(__LINE__);
            }
            else {
                $msg = "Transaksi gagal diselesaikan karena ";
                $msg .= "anda login di $new_login_cabang_nama($label), sedangkan transaksi hanya diijinkan di (" . callAvailTransPlace()["$jenisMaster"] . ") ";
                $msg .= "Silahkan login ulang .";
                if ($modal) {
                    $dataMsg["error"] = $msg;
                    //                    echo "<script>swal({type:'warning'})</script>";
                    //                    matiHEre(__LINE__."::".$modal);

                }
                else {
                    mati_disini($msg);
                }

            }
        }
        else {
            //show error
            $msg = "Transaski tidak dapat dilanjutkan karena cabang tidak terbaca. silahkan relogin ";
            if ($modal) {
                $dataMsg["error"] = $msg;
            }
            else {
                matiHEre($msg);
            }


        }
    }
    return $dataMsg;
}

//validasi antara session login dengan session transaksi sama
function validatePlaceTrans($curentBranch, $sessionBranch, $modal = false)
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlCabang");
    $c = new MdlCabang();
    $temp = $c->callSpecs_($sessionBranch);
    //    arrPrint($temp);
    //    cekHitam($ci->db->last_query());
    $new_login_cabang_nama = my_cabang_nama();
    $trans_login_cabang_nama = $temp[$sessionBranch]->nama;
    //    matiHere($curentBranch."::".$sessionBranch);
    $err = array();
    //    cekHitam("$curentBranch!=$sessionBranch");
    $msg = "";
    if ($curentBranch != $sessionBranch) {
        $msg = "Transaksi gagal diselesaikan karena ";
        $msg .= "anda login di $new_login_cabang_nama, sedangkan transaksi berada di $trans_login_cabang_nama. ";
        $msg .= "<br>Silahkan login ulang di cabang $trans_login_cabang_nama.";
        if ($modal) {
            $err["error"] = $msg;
        }
        else {
            mati_disini($msg);
        }

    }
    else {
        if (empty($curentBranch)) {
            $msg = "Transaksi gagal diselesaikan karena ";
            $msg .= "sesi anda telah habis. ";
            $msg .= "Silahkan login ulang";

            if ($modal) {
                $err["error"] = $msg;
            }
            else {
                mati_disini($msg);
            }

        }
        if (empty($sessionBranch)) {
            $msg = "Transaksi gagal diselesaikan karena ";
            $msg .= "sesi anda telah habis. ";
            $msg .= "Silahkan login ulang";

            if ($modal) {
                $err["error"] = $msg;
            }
            else {
                mati_disini($msg);
            }
        }
    }
    return $msg;


}

//tambahan detektor stepcode hak akses, 21 maret 2023-------------------------
 function stepCodeByEmployeeID($jenisMaster, $step, $employeeID, $curentBranch, $sessionBranch, $modal = false)
 {
     cekKuning($sessionBranch);
     cekHitam($jenisMaster . "::" . $step . "::*" . $employeeID . "::" . $curentBranch . "" . $sessionBranch);

     $ci =& get_instance();

     $transaksiALias = transactionJenisAlias();
     $ci->load->model("Mdls/MdlAccessRight");
     $a = new MdlAccessRight();
     $hakCreate = [1, 2];
     $konstantaAkses = $ci->config->item("crud");
     //    arrPrint($konstantaAkses);
     //    matiHere();
     $tmp = $a->getDetailAccess($jenisMaster, $step, $employeeID);
     $adaCocok = count(array_intersect($tmp[$step], $hakCreate)) > 0 ? 1 : 0;
     $errorMassage = array();
     if ($adaCocok) {
         //cek apakah jenis trasaksi sesui untuk cabang yang sedang login
         validatePlaceTrans($curentBranch, $sessionBranch, $modal);
         validateAllowPlace($jenisMaster, $curentBranch, $modal);
     }
     else {
         //blokir akses karen tidak memiliki akses
         $msg = "Transaksi tidak dapat dilanjutkan karena anda tidak memiliki akses, silahkan minta super admin untuk dilakukan seting.<br> Selanjutnya silahkan ulangi relogin";
         matiHEre($msg);
     }

     if (sizeof($tmp) > 0) {
         $result = true;
     }
     else {
         $result = false;
     }
     //matiHere(__LINE__);
     return $result;
 }

function validateAllowCreate($jenisMaster, $step, $employeeID, $curentBranch, $sessionBranch, $modal = false)
{
    //cekKuning($sessionBranch);
    //    cekHitam($jenisMaster . "::" . $step . "::*" . $employeeID."::".$curentBranch."".$sessionBranch);

    $ci =& get_instance();

    $transaksiALias = transactionJenisAlias();
    $ci->load->model("Mdls/MdlAccessRight");
    $a = new MdlAccessRight();
    $hakCreate = [1, 2];//create,update
    $konstantaAkses = $ci->config->item("crud");
    //    arrPrint($konstantaAkses);
    //    matiHere();
    $tmp = $a->getDetailAccess($jenisMaster, $step, $employeeID);
    cekBiru($ci->db->last_query());
    $adaCocok = count(array_intersect($tmp[$step], $hakCreate)) > 0 ? 1 : 0;
    //arrPrint($tmp);
    $errorMassage = array();
    if ($adaCocok) {
        //cek apakah jenis trasaksi sesui untuk cabang yang sedang login
        validatePlaceTrans($curentBranch, $sessionBranch, $modal);
        validateAllowPlace($jenisMaster, $curentBranch, $modal);
    }
    else {
        //blokir akses karen tidak memiliki akses
        $msg = "*Transaksi tidak dapat dilanjutkan karena anda tidak memiliki akses, silahkan minta super admin untuk dilakukan seting.<br> Selanjutnya silahkan ulangi relogin";
        matiHEre($msg);
    }

    if (sizeof($tmp) > 0) {
        $result = true;
    }
    else {
        $result = false;
    }
    //matiHere(__LINE__);
    return $result;
}

//ini untuk index selama miliki hak akses akan diijinkan untuk membuka transaksi (crud) sesuai kewenangan
function validateAllowAccessEmployee($employee_id, $jenisTr, $modal = false)
{
    $tmp = alowedAccess($employee_id, $jenisTr);
    $msg = array();
    if (count($tmp["akses"])) {
    }
    else {
        if ($modal) {
            $msg["error"] = "Anda tidak memiliki kewenangan di transaksi ini.<br> Silahkna menghubungi Super admin untuk diberikan akses yang sesuai";
            die();
        }
        else {
            $msg = "Anda tidak memiliki kewenangan di transaksi ini.<br> Silahkna menghubungi Super admin untuk diberikan akses yang sesuai";
            $alerts = array(
                "type" => "warning",
                "html" => "$msg",
            );
            echo swalAlert($alerts);
            redirecResult(base_url());
        }
    }
    return $msg;
}


