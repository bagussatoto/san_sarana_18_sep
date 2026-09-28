<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 4/19/2019
 * Time: 8:28 PM
 */


function heFetchElement($jenisTr, $elName, $mdlName, $key)
{
    $ci =& get_instance();
    $ci->load->helper("he_element");


    $cCode = "_TR_" . $jenisTr;

    $elementConfigs = isset($ci->config->item('heTransaksi_ui')[$jenisTr]['receiptElements']) ? $ci->config->item('heTransaksi_ui')[$jenisTr]['receiptElements'] : array();
    $relElementConfigs = isset($ci->config->item('heTransaksi_ui')[$jenisTr]['relativeElements']) ? $ci->config->item('heTransaksi_ui')[$jenisTr]['relativeElements'] : array();
    $relOptionConfigs = isset($ci->config->item('heTransaksi_ui')[$jenisTr]['relativeOptions']) ? $ci->config->item('heTransaksi_ui')[$jenisTr]['relativeOptions'] : array();
//    $metod2RelConfig = isset($ci->config->item('heTransaksi_ui')[$jenisTr]['relativeElements']['targetMethod2']) ? $ci->config->item('heTransaksi_ui')[$jenisTr]['relativeElements']['targetMethod2'] : array();
    $configRecomData = isset($ci->config->item("heTransaksi_ui")[$jenisTr]['pairRecomDataElement']) ? $ci->config->item("heTransaksi_ui")[$jenisTr]['pairRecomDataElement'] : array();


    $pairedRelative = array();
    if (sizeof($relElementConfigs) > 0) {
        foreach ($relElementConfigs as $eSrc => $esSpec) {
            foreach ($esSpec as $esName => $psubSpec) {
                if (sizeof($psubSpec) > 0) {
                    foreach ($psubSpec as $rcID => $subSpec) {
                        $elementConfigs[$rcID] = $subSpec;
                        if (isset($subSpec['pairedModel']) && sizeof($subSpec['pairedModel']) > 0) {
                            $ci->load->model("Coms/" . $subSpec['pairedModel']['mdlName']);
                            $pr = new $subSpec['pairedModel']['mdlName']();
                            if (sizeof($subSpec['pairedModel']['mdlFilter']) > 0) {

                                foreach ($subSpec['pairedModel']['mdlFilter'] as $prKey => $prVal) {
                                    $prVal = makeValue($prVal, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], $static = 0);
                                    $pr->addFilter("$prKey='$prVal'");
                                }
                            }

                            $pairedRek = $subSpec['pairedModel']['rekening'];
                            $pairedMethod = $subSpec['pairedModel']['mdlMethod'];
                            $prTemp = $pr->$pairedMethod($pairedRek);

                            if (sizeof($prTemp) > 0) {
                                $fieldID = $subSpec['pairedModel']['fieldID'];
                                $fieldLabel = $subSpec['pairedModel']['fieldLabel'];
                                foreach ($prTemp as $prSpec) {
                                    $colName = $subSpec['pairedModel']['key'];

                                    if ($prSpec->$colName == $key) {
                                        $pairedRelative[$fieldLabel] = $prSpec->$fieldID;
                                    }
                                }
                            }
                        }

                    }
                }
                if ($esName == (isset($_SESSION[$cCode]['main'][$eSrc]) ? $_SESSION[$cCode]['main'][$eSrc] : "")) {
                    if (isset($psubSpec[$elName]['pairMethod']) && sizeof($psubSpec[$elName]['pairMethod']) > 0) {
                        $model = $psubSpec[$elName]['pairMethod']["recom"];
                        $ci->load->model("ReComs/" . $model);
                        $gateVal = $psubSpec[$elName]['pairMethod']["calculate"];
                        $tc = new $model();
                        $tc->pair($gateVal, $key);
                        $tc->exec();

                    }
                }
            }
        }


        if (array_key_exists($elName, $relElementConfigs)) {
            //reset semua nilai anakan relatif yang mungkin saja terlanjur terbentuk
            if (isset($_SESSION[$cCode]['main_elements']) && sizeof($_SESSION[$cCode]['main_elements']) > 0) {
                foreach ($_SESSION[$cCode]['main_elements'] as $eeName => $jasghhagsghaj) {
                    if (strpos($eeName, $elName . "_") !== false) {
                        unset($_SESSION[$cCode]['main_elements'][$eeName]);
                    }
                    else {
                    }
                }
            }


        }
        if (array_key_exists($elName, $relOptionConfigs)) {
            //reset semua inputan relatif yang mungkin saja terlanjur terbentuk
            foreach ($relOptionConfigs[$elName] as $trigVal => $options) {
                foreach ($options as $iVarName => $jasghahgsghasha) {
                    if (isset($_SESSION[$cCode]['main_elements'][$elName]['key']) && $_SESSION[$cCode]['main_elements'][$elName]['key'] == $trigVal) {
                    }
                    else {
                        // hapus semua main_input dp/cia/diskon bila sudah diisi maka diisi ulang...
                        if (isset($_SESSION[$cCode]['main_inputs'])) {
                            foreach ($_SESSION[$cCode]['main_inputs'] as $k_input => $v_input) {
                                $_SESSION[$cCode]['main_inputs'][$k_input] = 0;
                                $_SESSION[$cCode]['main'][$k_input] = 0;
                            }
                        }
                    }

                }

            }


        }
        else {
        }

    }

    $keySrc = $elementConfigs[$elName]['key'];
    $aFilter = isset($elementConfigs[$elName]['mdlFilter']) ? $elementConfigs[$elName]['mdlFilter'] : array();

    $prTemp = array();
    $paired = array();
    if (sizeof($elementConfigs) > 0) {
        foreach ($elementConfigs as $subConfig) {
            if (isset($subConfig['pairedModel']) && sizeof($subConfig['pairedModel']) > 0) {
                $ci->load->model("Coms/" . $subConfig['pairedModel']['mdlName']);
                $pr = new $subConfig['pairedModel']['mdlName']();
                if (sizeof($subConfig['pairedModel']['mdlFilter']) > 0) {

                    foreach ($subConfig['pairedModel']['mdlFilter'] as $prKey => $prVal) {
                        $prVal = makeValue($prVal, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], $static = 0);
                        $pr->addFilter("$prKey='$prVal'");
                    }
                }

                $pairedRek = $subConfig['pairedModel']['rekening'];
                $pairedMethod = $subConfig['pairedModel']['mdlMethod'];
                $prTemp = $pr->$pairedMethod($pairedRek);
                if (sizeof($prTemp) > 0) {
                    $fieldID = $subConfig['pairedModel']['fieldID'];
                    $fieldLabel = $subConfig['pairedModel']['fieldLabel'];

                    if (isset($key) && ($key != NULL)) {
                        foreach ($prTemp as $prSpec) {
                            $colName = $subConfig['pairedModel']['key'];
                            if ($prSpec->$colName == $key) {
                                if ($rslt == "") {
                                    $rslt = $prSpec->$fieldID;
                                }
                                else {
                                    $rslt .= "+" . $prSpec->$fieldID;
                                }
                            }
                        }
                        $paired[$fieldLabel] = $rslt;
                    }
                    else {
                    }
                }
                else {

                }
            }
        }
    }
    else {
    }

//arrPrintWebs($paired);
//    mati_disini();
    $ci->load->model("Mdls/" . $mdlName);
    $oo = new $mdlName();


    if (sizeof($aFilter) > 0) {

        $oo = makeFilter($aFilter, $_SESSION[$cCode]['main'], $oo);
    }
    else {
    }

    $oo->init();
    $oo->setFilters(array());
    if ($oo->getTableName() == "static") {
        $oo->addFilter("$keySrc='$key'");
    }
    else {
        $oo->addFilter($oo->getTableName() . "." . "$keySrc='$key'");
    }


    $tmp = $oo->lookupAll()->result();


    $contents = array();
    $labelValue = "";
    if (sizeof($tmp) > 0) {
        foreach ($tmp as $row) {
            if (sizeof($paired) > 0) {
                foreach ($paired as $prKey => $prVal) {
                    $row->$prKey = $prVal;
                }
            }
            if (sizeof($pairedRelative) > 0) {
                foreach ($pairedRelative as $prKeyRel => $prValRel) {
                    $row->$prKeyRel = $prValRel;
                }
            }


            if (isset($elementConfigs[$elName]['usedFields']) && sizeof($elementConfigs[$elName]['usedFields']) > 0) {
                if ($row->$keySrc == $key) {
                    foreach ($elementConfigs[$elName]['usedFields'] as $src => $label) {
                        $contents[$src] = isset($row->$src) ? $row->$src : "";
                    }

                    if (isset($elementConfigs[$elName]['labelSrc'])) {

                        $ex = explode("/", $elementConfigs[$elName]['labelSrc']);
                        if (sizeof($ex) > 1) {
                            $labelValue = "";
                            foreach ($ex as $col) {

                                $labelValue .= $row->$col . " / ";
                            }
                            $labelValue = rtrim($labelValue, " / ");
                        }
                        else {
                            $kolomName = $elementConfigs[$elName]['labelSrc'];
                            $labelValue = $row->$kolomName;
                        }

                    }
                }
            }
        }
    }
    else {

    }


    //  method diskon....
    if (isset($elementConfigs[$elName]['targetMethod']) && sizeof($elementConfigs[$elName]['targetMethod']) > 0) {
        foreach ($elementConfigs[$elName]['targetMethod'] as $tKey => $tVal) {
//            matiHEre("$key ".$tKey." ".$elName);
            if ($key == $tKey) {
                $model = $tVal;
                $ci->load->model("ReComs/" . $model);
//                $ci->load->helper("he_value_builder");


                $tt = New $model();
                $tt->pair();
                $tt->exec();
//                $ci->fillValues($jenisTr);
            }
        }
    }

    //kalkulasi relemet ke main jika ada perhitungan logic
    if (isset($elementConfigs[$elName]['pairMethod']) && sizeof($elementConfigs[$elName]['pairMethod']) > 0) {
        $model = $elementConfigs[$elName]['pairMethod']["recom"];
        $ci->load->model("ReComs/" . $model);
        $gateVal = $elementConfigs[$elName]['pairMethod']["calculate"];
        $tc = new $model();
        $tc->pair($gateVal, $key);
        $tc->exec();
//        matiHEre("pairMethod");
    }

    if (isset($relElementConfigs[$elName]) && $elementConfigs[$elName] > 0) {
        if (isset($relElementConfigs[$elName][$key])) {
            foreach ($relElementConfigs[$elName][$key] as $tKey => $tVal) {
                if (isset($tVal['targetMethod2'])) {
                    foreach ($tVal['targetMethod2'] as $sKey => $sVal) {

                        if ($key == $sKey) {
                            $model = $sVal;
                            $ci->load->model("ReComs/" . $model);
                            $tt = New $model();
                            $tt->pair();
                            $tt->exec();
                        }


                    }
                }
            }
        }
    }


    if (!isset($_SESSION[$cCode]['main_elements'])) {
        $_SESSION[$cCode]['main_elements'] = array();
    }


    //==daftarkan ke gerbang yang sesuai
    if (sizeof($tmp) > 0) {
        $_SESSION[$cCode]['main_elements'][$elName] = array(
            "elementType" => $elementConfigs[$elName]['elementType'],
            "name" => $elName,
            "label" => $elementConfigs[$elName]['label'],
            "key" => $key,
            "labelSrc" => isset($elementConfigs[$elName]['labelSrc']) ? $elementConfigs[$elName]['labelSrc'] : "--",
            "labelValue" => $labelValue,
            "mdl_name" => $mdlName,
            "contents" => base64_encode(serialize($contents)),
            "contents_intext" => print_r($contents, true),
        );
        //==masukkan ke gerbang utama
        $_SESSION[$cCode]["main"][$elName] = $key;
        $_SESSION[$cCode]["main"][$elName . "__label"] = $labelValue;
        if (sizeof($contents)) {
            foreach ($contents as $key => $val) {
                $_SESSION[$cCode]["main"][$elName . "__" . $key] = $val;
            }
        }
        if (sizeof($configRecomData) > 0) {
            if (isset($configRecomData[$elName])) {
                $dataRe = $configRecomData[$elName];
                if (sizeof($configRecomData) > 0) {
                    $mdlName = $dataRe['mdlname'];
                    $filterKey = $dataRe['gateId'];
                    $targetGate = $dataRe['target'];
                    $keyID = $_SESSION[$cCode]['main'][$filterKey];
                    $ci->load->model("Mdls/" . $mdlName);
                    $md = new $mdlName();
                    $tmRe = $md->lookUpAll()->result();
                    $array = array();
                    foreach ($tmRe as $data) {
                        $array[$data->id] = $data->name;
                    }
                    if (isset($array[$keyID])) {
                        if (isset($array[$keyID]) && $array[$keyID] == "dipotong") {
//                        matiHere("m");
                            foreach ($targetGate as $gate => $key) {
                                $_SESSION[$cCode][$gate][$key] = 0;//false
                            }
                        }
                        else {
//                        matiHere("m");
                            foreach ($targetGate as $gate => $key) {

                                $_SESSION[$cCode][$gate][$key] = 1;//true
                            }
                        }
                    }

                }
            }
        }
    }


    else {
        unset($_SESSION[$cCode]['main_elements'][$elName]);
        //==masukkan ke gerbang utama
        unset($_SESSION[$cCode]["main"][$elName]);


//        unset($_SESSION[$cCode]["out_master"][$elName]);
    }

//    mati_disini("matii");
}

function heRecordElement($jenisTr, $elName, $val)
{

    $ci =& get_instance();
    $ci->load->helper("he_element");
//    $jenisTr = $ci->uri->segment(3);
    $cCode = "_TR_" . $jenisTr;
//    $elName = $ci->uri->segment(4);
    $elementConfigs = isset($ci->config->item('heTransaksi_ui')[$jenisTr]['receiptElements']) ? $ci->config->item('heTransaksi_ui')[$jenisTr]['receiptElements'] : array();
    $relElementConfigs = isset($ci->config->item('heTransaksi_ui')[$jenisTr]['relativeElements']) ? $ci->config->item('heTransaksi_ui')[$jenisTr]['relativeElements'] : array();
    $relOptionConfigs = isset($ci->config->item('heTransaksi_ui')[$jenisTr]['relativeOptions']) ? $ci->config->item('heTransaksi_ui')[$jenisTr]['relativeOptions'] : array();

    if (sizeof($relElementConfigs) > 0) {
        foreach ($relElementConfigs as $eSrc => $esSpec) {
            foreach ($esSpec as $esName => $psubSpec) {
                if (sizeof($psubSpec) > 0) {
//				        $ssCtr=0;
                    foreach ($psubSpec as $rcID => $subSpec) {
//                        $elementConfigs[$eSrc . "_" . $esName . "_" . $rcID] = $subSpec;
                        $elementConfigs[$rcID] = $subSpec;
//                            $ssCtr++;
                    }
                }

            }
        }
        if (array_key_exists($elName, $relElementConfigs)) {
            //reset semua nilai anakan relatif yang mungkin saja terlanjur terbentuk
            if (isset($_SESSION[$cCode]['main_elements']) && sizeof($_SESSION[$cCode]['main_elements']) > 0) {
                foreach ($_SESSION[$cCode]['main_elements'] as $eeName => $jasghhagsghaj) {
                    if (strpos($eeName, $elName . "_") !== false) {
                        unset($_SESSION[$cCode]['main_elements'][$eeName]);
                    }
                    else {
                    }
                }
            }


        }
        if (array_key_exists($elName, $relOptionConfigs)) {
            //reset semua inputan relatif yang mungkin saja terlanjur terbentuk
            foreach ($relOptionConfigs[$elName] as $trigVal => $options) {
                foreach ($options as $iVarName => $jasghahgsghasha) {

                    if (isset($_SESSION[$cCode]['main_elements'][$elName]['value']) && $_SESSION[$cCode]['main_elements'][$elName]['value'] == $trigVal) {
                    }
                    else {
                        if (isset($_SESSION[$cCode]['main_inputs'][$iVarName])) {
                            unset($_SESSION[$cCode]['main_inputs'][$iVarName]);
                        }
                    }


                }

            }


        }
        else {
        }

    }

//    $val = ($_GET['val']);
//matiHere("... ".__LINE__." ".__FUNCTION__);
    if (!isset($_SESSION[$cCode]['main_elements'])) {
        $_SESSION[$cCode]['main_elements'] = array();
    }
    $_SESSION[$cCode]['main_elements'][$elName] = array(
        "elementType" => $elementConfigs[$elName]['elementType'],
        "name" => $elName,
        "label" => $elementConfigs[$elName]['label'],
        "labelSrc" => isset($elementConfigs[$elName]['labelSrc']) ? $elementConfigs[$elName]['labelSrc'] : "--",
        "mdl_name" => "",
        "value" => $val,
    );

    //==masukkan ke gerbang utama
    $_SESSION[$cCode]["main"][$elName] = $val;
    if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
        foreach ($_SESSION[$cCode]['items'] as $iID => $iSpec) {
            if (isset($_SESSION[$cCode]['items'][$iID][$elName])) {
                $_SESSION[$cCode]['items'][$iID][$elName] = null;
                unset($_SESSION[$cCode]['items'][$iID][$elName]);
            }
        }
    }
//    $_SESSION[$cCode]["out_master"][$elName] = $val;
}

function hePairFromElement($jenisTr, $elName, $elSpec)
{
//    echo gettype($elSpec);
//    arrprint($elSpec);
    $result = array(
        $elName . "_id" => 0,
        $elName . "_nama" => "none",
    );

    $ci =& get_instance();
    $ci->load->database();

    if (isset($ci->config->item("heTransaksi_elementPairs")[$jenisTr])) {
        $pairConfig = $ci->config->item("heTransaksi_elementPairs")[$jenisTr];

    }

    if (isset($pairConfig[$elName]['id']) && isset($pairConfig[$elName]['label'])) {
        $labelSrc = $pairConfig[$elName]['label'];
        $result = array(
            $elName . "_id" => isset($elSpec[$pairConfig[$elName]['id']]) ? $elSpec[$pairConfig[$elName]['id']] : 0,
            $elName . "_nama" => isset($elSpec[$labelSrc]) ? $elSpec[$labelSrc] : "-",
        );
    }
    return array(
        "identifiers" => array(
            $elName . "_id" => $elName . "_nama",
        ),
        "content" => $result,
    );
}

function heFetchItemsElement($jenisTr, $elName, $mdlName, $key, $helpName = "")
{
    $ci =& get_instance();
    $ci->load->helper("he_element");


    $cCode = "_TR_" . $jenisTr;

    $elementConfigs = isset($ci->config->item('heTransaksi_ui')[$jenisTr]['receiptElementsItemsAuto']) ? $ci->config->item('heTransaksi_ui')[$jenisTr]['receiptElementsItemsAuto'] : array();


    if (isset($_SESSION[$cCode]['items'][$elName])) {
        $items = $_SESSION[$cCode]['items'][$elName];

        $keySrc = $elementConfigs[0]['key'];
        $aFilter = isset($elementConfigs[0]['mdlFilter']) ? $elementConfigs[0]['mdlFilter'] : array();


        $prTemp = array();
        $paired = array();
        if (sizeof($elementConfigs) > 0) {
            foreach ($elementConfigs as $subConfig) {

                if (isset($subConfig['pairedModel']) && sizeof($subConfig['pairedModel']) > 0) {
                    $ci->load->model("Coms/" . $subConfig['pairedModel']['mdlName']);
                    $pr = new $subConfig['pairedModel']['mdlName']();
                    if (sizeof($subConfig['pairedModel']['mdlFilter']) > 0) {

                        foreach ($subConfig['pairedModel']['mdlFilter'] as $prKey => $prVal) {
                            $prVal = makeValue($prVal, $items, $items, $static = 0);
                            $pr->addFilter("$prKey='$prVal'");
                        }
                    }

                    $pairedRek = $subConfig['pairedModel']['rekening'];
                    $pairedMethod = $subConfig['pairedModel']['mdlMethod'];
                    $prTemp = $pr->$pairedMethod($pairedRek);
                    if (sizeof($prTemp) > 0) {
                        $fieldID = $subConfig['pairedModel']['fieldID'];
                        $fieldLabel = $subConfig['pairedModel']['fieldLabel'];
                        foreach ($prTemp as $prSpec) {
                            $colName = $subConfig['pairedModel']['key'];

                            if ($prSpec->$colName == $key) {
                                $paired[$fieldLabel] = $prSpec->$fieldID;
                            }
                        }
                    }
                }
            }

        }
        else {
        }


        $ci->load->model("Mdls/" . $mdlName);
        $oo = new $mdlName();
        $oo->init();
        $oo->setFilters(array());
        if (sizeof($aFilter) > 0) {
            $oo = makeFilter($aFilter, $items, $oo);
        }
        else {
        }
        if ($oo->getTableName() == "static") {
            $oo->addFilter("$keySrc='$key'");
        }
        else {

            $oo->addFilter($oo->getTableName() . "." . "$keySrc='$key'");

        }

        $tmp = $oo->lookupAll()->result();
        $contents = array();
        $labelValue = "";
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                if (sizeof($paired) > 0) {
                    foreach ($paired as $prKey => $prVal) {
                        $row->$prKey = $prVal;
                    }
                }

                if (isset($elementConfigs[0]['usedFields']) && sizeof($elementConfigs[0]['usedFields']) > 0) {
                    if ($row->$keySrc == $key) {
                        foreach ($elementConfigs[0]['usedFields'] as $src => $label) {
                            $contents[$src] = isset($row->$src) ? $row->$src : "";
                        }

                        if (isset($elementConfigs[0]['labelSrc'])) {

                            $ex = explode("/", $elementConfigs[0]['labelSrc']);
                            if (sizeof($ex) > 1) {
                                $labelValue = "";
                                foreach ($ex as $col) {

                                    $labelValue .= $row->$col . " / ";
                                }
                                $labelValue = rtrim($labelValue, " / ");
                            }
                            else {
                                $kolomName = $elementConfigs[0]['labelSrc'];
                                $labelValue = $row->$kolomName;
                            }

                        }
                    }
                }
            }
        }
        else {

        }

        if (!isset($_SESSION[$cCode]['items_elements'])) {
            $_SESSION[$cCode]['items_elements'] = array();
        }

        //==daftarkan ke gerbang yang sesuai
        if (sizeof($tmp) > 0) {
            $_SESSION[$cCode]['items_elements'][$elName] = array(
                "elementType" => $elementConfigs[0]['elementType'],
                "name" => $elName,
                "label" => $elementConfigs[0]['label'],
                "key" => $key,
                "labelSrc" => isset($elementConfigs[0]['labelSrc']) ? $elementConfigs[0]['labelSrc'] : "--",
                "labelValue" => $labelValue,
                "mdl_name" => $mdlName,
                "contents" => base64_encode(serialize($contents)),
                "contents_intext" => print_r($contents, true),
            );
            //==masukkan ke gerbang items
            $_SESSION[$cCode]["items"][$elName][$helpName] = $key;
            $_SESSION[$cCode]["items"][$elName][$helpName . "__label"] = $labelValue;
            if (sizeof($contents)) {
                foreach ($contents as $key => $val) {
                    $_SESSION[$cCode]["items"][$elName][$helpName . "__" . $key] = $val;
                }
            }

        }
        else {
            unset($_SESSION[$cCode]['items_elements'][$elName]);
            unset($_SESSION[$cCode]["items"][$elName][$elName]);

        }
    }
}

function heFetchElement_modul($jenisTr, $elName, $mdlName, $key, $configUiJenis)
{

    $ci =& get_instance();
    $ci->load->helper("he_element");

    $cCode = cCodeBuilderMisc($jenisTr);

    $elementConfigs = isset($configUiJenis['receiptElements']) ? $configUiJenis['receiptElements'] : array();
    $relElementConfigs = isset($configUiJenis['relativeElements']) ? $configUiJenis['relativeElements'] : array();
    $relOptionConfigs = isset($configUiJenis['relativeOptions']) ? $configUiJenis['relativeOptions'] : array();
    $configRecomData = isset($configUiJenis['pairRecomDataElement']) ? $configUiJenis['pairRecomDataElement'] : array();

    $pairedRelative = array();
    if (sizeof($relElementConfigs) > 0) {
        foreach ($relElementConfigs as $eSrc => $esSpec) {
            foreach ($esSpec as $esName => $psubSpec) {

                if (sizeof($psubSpec) > 0) {
                    foreach ($psubSpec as $rcID => $subSpec) {
                        $elementConfigs[$rcID] = $subSpec;
                        if (isset($subSpec['pairedModel']) && sizeof($subSpec['pairedModel']) > 0) {
                            $ci->load->model("Coms/" . $subSpec['pairedModel']['mdlName']);
                            $pr = new $subSpec['pairedModel']['mdlName']();
                            if (sizeof($subSpec['pairedModel']['mdlFilter']) > 0) {

                                foreach ($subSpec['pairedModel']['mdlFilter'] as $prKey => $prVal) {
                                    $prVal = makeValue($prVal, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], $static = 0);
                                    $pr->addFilter("$prKey='$prVal'");
                                }
                            }

                            $pairedRek = $subSpec['pairedModel']['rekening'];
                            $pairedMethod = $subSpec['pairedModel']['mdlMethod'];
                            $prTemp = $pr->$pairedMethod($pairedRek);
                            if (sizeof($prTemp) > 0) {
                                $fieldID = $subSpec['pairedModel']['fieldID'];
                                $fieldLabel = $subSpec['pairedModel']['fieldLabel'];
                                if (isset($key) && ($key != NULL)) {
                                    $rslt = "";
                                    foreach ($prTemp as $prSpec) {
                                        $colName = $subSpec['pairedModel']['key'];
                                        if ($prSpec->$colName == $key) {
                                            if ($rslt == "") {
                                                $rslt = $prSpec->$fieldID;
                                            }
                                            else {
                                                $rslt .= "+" . $prSpec->$fieldID;
                                            }

                                            $pairedRelative[$fieldLabel] = $rslt;
                                        }
                                    }
                                }

                            }
                        }

                    }
                }
//                arrPrint($pairedRelative);
                if ($esName == (isset($_SESSION[$cCode]['main'][$eSrc]) ? $_SESSION[$cCode]['main'][$eSrc] : "")) {
                    if (isset($psubSpec[$elName]['pairMethod']) && sizeof($psubSpec[$elName]['pairMethod']) > 0) {
                        $model = $psubSpec[$elName]['pairMethod']["recom"];
                        $ci->load->model("ReComs/" . $model);
                        $gateVal = $psubSpec[$elName]['pairMethod']["calculate"];
                        $tc = new $model();
                        $tc->pair($gateVal, $key);
                        $tc->exec();

                    }
                }
            }
        }
//matiHEre(__LINE__);

        if (array_key_exists($elName, $relElementConfigs)) {
            //reset semua nilai anakan relatif yang mungkin saja terlanjur terbentuk
            if (isset($_SESSION[$cCode]['main_elements']) && sizeof($_SESSION[$cCode]['main_elements']) > 0) {
                foreach ($_SESSION[$cCode]['main_elements'] as $eeName => $jasghhagsghaj) {
                    if (strpos($eeName, $elName . "_") !== false) {
                        unset($_SESSION[$cCode]['main_elements'][$eeName]);
                    }
                    else {
                    }
                }
            }


        }
        if (array_key_exists($elName, $relOptionConfigs)) {
            //reset semua inputan relatif yang mungkin saja terlanjur terbentuk
            foreach ($relOptionConfigs[$elName] as $trigVal => $options) {
                foreach ($options as $iVarName => $jasghahgsghasha) {
                    if (isset($_SESSION[$cCode]['main_elements'][$elName]['key']) && $_SESSION[$cCode]['main_elements'][$elName]['key'] == $trigVal) {
                    }
                    else {
                        // hapus semua main_input dp/cia/diskon bila sudah diisi maka diisi ulang...
                        if (isset($_SESSION[$cCode]['main_inputs'])) {
                            foreach ($_SESSION[$cCode]['main_inputs'] as $k_input => $v_input) {
                                $_SESSION[$cCode]['main_inputs'][$k_input] = 0;
                                $_SESSION[$cCode]['main'][$k_input] = 0;
                            }
                        }
                    }

                }

            }


        }
        else {
        }

    }

    $keySrc = $elementConfigs[$elName]['key'];
    $aFilter = isset($elementConfigs[$elName]['mdlFilter']) ? $elementConfigs[$elName]['mdlFilter'] : array();

    $prTemp = array();
    $paired = array();
    if (sizeof($elementConfigs) > 0) {
        foreach ($elementConfigs as $subConfig) {
            if (isset($subConfig['pairedModel']) && sizeof($subConfig['pairedModel']) > 0) {
                $ci->load->model("Coms/" . $subConfig['pairedModel']['mdlName']);
                $pr = new $subConfig['pairedModel']['mdlName']();
                if (sizeof($subConfig['pairedModel']['mdlFilter']) > 0) {

                    foreach ($subConfig['pairedModel']['mdlFilter'] as $prKey => $prVal) {
                        $prVal = makeValue($prVal, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], $static = 0);
                        $pr->addFilter("$prKey='$prVal'");
                    }
                }

                $pairedRek = $subConfig['pairedModel']['rekening'];
                $pairedMethod = $subConfig['pairedModel']['mdlMethod'];
                $prTemp = $pr->$pairedMethod($pairedRek);

                if (sizeof($prTemp) > 0) {
                    $fieldID = $subConfig['pairedModel']['fieldID'];
                    $fieldLabel = $subConfig['pairedModel']['fieldLabel'];

                    if (isset($key) && ($key != NULL)) {
                        $rslt = "";
                        foreach ($prTemp as $prSpec) {
                            $colName = $subConfig['pairedModel']['key'];
                            if ($prSpec->$colName == $key) {
                                if ($rslt == "") {
                                    $rslt = $prSpec->$fieldID;
                                }
                                else {
                                    $rslt .= "+" . $prSpec->$fieldID;
                                }
                                $paired[$fieldLabel] = $rslt;
                            }
                        }
                    }
                    else {
                    }
                }
                else {

                }
            }
        }
    }
    else {
    }

    $ci->load->model("Mdls/" . $mdlName);
    $oo = new $mdlName();


    if (sizeof($aFilter) > 0) {

        $oo = makeFilter($aFilter, $_SESSION[$cCode]['main'], $oo);
    }
    else {
    }

    $oo->init();
    $oo->setFilters(array());
    if ($oo->getTableName() == "static") {
        $oo->addFilter("$keySrc='$key'");
    }
    else {
        $oo->addFilter($oo->getTableName() . "." . "$keySrc='$key'");
    }


    $tmp = $oo->lookupAll()->result();


    $contents = array();
    $labelValue = "";
    if (sizeof($tmp) > 0) {
        foreach ($tmp as $row) {
            if (sizeof($paired) > 0) {
                foreach ($paired as $prKey => $prVal) {
                    $row->$prKey = $prVal;
                }
            }
            if (sizeof($pairedRelative) > 0) {
                foreach ($pairedRelative as $prKeyRel => $prValRel) {
                    $row->$prKeyRel = $prValRel;
                }
            }


            if (isset($elementConfigs[$elName]['usedFields']) && sizeof($elementConfigs[$elName]['usedFields']) > 0) {
                if ($row->$keySrc == $key) {
                    foreach ($elementConfigs[$elName]['usedFields'] as $src => $label) {
                        $contents[$src] = isset($row->$src) ? $row->$src : "";
                    }

                    if (isset($elementConfigs[$elName]['labelSrc'])) {

                        $ex = explode("/", $elementConfigs[$elName]['labelSrc']);
                        if (sizeof($ex) > 1) {
                            $labelValue = "";
                            foreach ($ex as $col) {

                                $labelValue .= $row->$col . " / ";
                            }
                            $labelValue = rtrim($labelValue, " / ");
                        }
                        else {
                            $kolomName = $elementConfigs[$elName]['labelSrc'];
                            $labelValue = $row->$kolomName;
                        }

                    }
                }
            }
        }
    }
    else {

    }


    //  method diskon....
    if (isset($elementConfigs[$elName]['targetMethod']) && sizeof($elementConfigs[$elName]['targetMethod']) > 0) {
        foreach ($elementConfigs[$elName]['targetMethod'] as $tKey => $tVal) {
//            matiHEre("$key ".$tKey." ".$elName);
            if ($key == $tKey) {
                $model = $tVal;
                $ci->load->model("ReComs/" . $model);
//                $ci->load->helper("he_value_builder");
                $tt = New $model();
                $tt->pair();
                $tt->exec();
//                $ci->fillValues($jenisTr);
            }
        }
    }
    //kalkulasi relemet ke main jika ada perhitungan logic
    if (isset($elementConfigs[$elName]['pairMethod']) && sizeof($elementConfigs[$elName]['pairMethod']) > 0) {
        $model = $elementConfigs[$elName]['pairMethod']["recom"];
        $ci->load->model("ReComs/" . $model);
        $gateVal = $elementConfigs[$elName]['pairMethod']["calculate"];
        $tc = new $model();
        $tc->pair($gateVal, $key);
        $tc->exec();
        // matiHEre("pairMethod".$model);
    }

    if (isset($relElementConfigs[$elName]) && $elementConfigs[$elName] > 0) {
        if (isset($relElementConfigs[$elName][$key])) {
            foreach ($relElementConfigs[$elName][$key] as $tKey => $tVal) {
                if (isset($tVal['targetMethod2'])) {
                    foreach ($tVal['targetMethod2'] as $sKey => $sVal) {

                        if ($key == $sKey) {
                            $model = $sVal;
                            $ci->load->model("ReComs/" . $model);
                            $tt = New $model();
                            $tt->pair();
                            $tt->exec();
                        }


                    }
                }
            }
        }
    }


    if (!isset($_SESSION[$cCode]['main_elements'])) {
        $_SESSION[$cCode]['main_elements'] = array();
    }


    //==daftarkan ke gerbang yang sesuai
    if (sizeof($tmp) > 0) {
        $_SESSION[$cCode]['main_elements'][$elName] = array(
            "elementType" => $elementConfigs[$elName]['elementType'],
            "name" => $elName,
            "label" => $elementConfigs[$elName]['label'],
            "key" => $key,
            "labelSrc" => isset($elementConfigs[$elName]['labelSrc']) ? $elementConfigs[$elName]['labelSrc'] : "--",
            "labelValue" => $labelValue,
            "mdl_name" => $mdlName,
            "contents" => base64_encode(serialize($contents)),
            "contents_intext" => print_r($contents, true),
        );
        //==masukkan ke gerbang utama
        $_SESSION[$cCode]["main"][$elName] = $key;
        $_SESSION[$cCode]["main"][$elName . "__label"] = $labelValue;
        if (sizeof($contents)) {
            foreach ($contents as $key => $val) {
                $_SESSION[$cCode]["main"][$elName . "__" . $key] = $val;
            }
        }
        if (sizeof($configRecomData) > 0) {
            if (isset($configRecomData[$elName])) {
                $dataRe = $configRecomData[$elName];
                if (sizeof($configRecomData) > 0) {
                    $mdlName = $dataRe['mdlname'];
                    $filterKey = $dataRe['gateId'];
                    $targetGate = $dataRe['target'];
                    $keyID = $_SESSION[$cCode]['main'][$filterKey];
                    $ci->load->model("Mdls/" . $mdlName);
                    $md = new $mdlName();
                    $tmRe = $md->lookUpAll()->result();
                    $array = array();
                    foreach ($tmRe as $data) {
                        $array[$data->id] = $data->name;
                    }
                    if (isset($array[$keyID])) {
                        if (isset($array[$keyID]) && $array[$keyID] == "dipotong") {
//                        matiHere("m");
                            foreach ($targetGate as $gate => $key) {
                                $_SESSION[$cCode][$gate][$key] = 0;//false
                            }
                        }
                        else {
//                        matiHere("m");
                            foreach ($targetGate as $gate => $key) {
                                $_SESSION[$cCode][$gate][$key] = 1;//true
                            }
                        }
                    }

                }
            }
        }


//        mati_disini(__LINE__ . " [$key]");
    }
    else {
        unset($_SESSION[$cCode]['main_elements'][$elName]);
        //==masukkan ke gerbang utama
        unset($_SESSION[$cCode]["main"][$elName]);


//        unset($_SESSION[$cCode]["out_master"][$elName]);
    }


//    mati_disini("matii");
}

function heRecordElement_modul($jenisTr, $elName, $val, $configUiJenis)
{

    $ci =& get_instance();
    $ci->load->helper("he_element");
//    $jenisTr = $ci->uri->segment(3);
//    $cCode = "_TR_" . $jenisTr;
    $cCode = cCodeBuilderMisc($jenisTr);
//    $elName = $ci->uri->segment(4);
    $elementConfigs = isset($configUiJenis['receiptElements']) ? $configUiJenis['receiptElements'] : array();
    $relElementConfigs = isset($configUiJenis['relativeElements']) ? $configUiJenis['relativeElements'] : array();
    $relOptionConfigs = isset($configUiJenis['relativeOptions']) ? $configUiJenis['relativeOptions'] : array();
    if (sizeof($relElementConfigs) > 0) {
        foreach ($relElementConfigs as $eSrc => $esSpec) {
            foreach ($esSpec as $esName => $psubSpec) {
                if (sizeof($psubSpec) > 0) {
//				        $ssCtr=0;
                    foreach ($psubSpec as $rcID => $subSpec) {
//                        $elementConfigs[$eSrc . "_" . $esName . "_" . $rcID] = $subSpec;
                        $elementConfigs[$rcID] = $subSpec;
//                            $ssCtr++;
                    }
                }

            }
        }
        if (array_key_exists($elName, $relElementConfigs)) {
            //reset semua nilai anakan relatif yang mungkin saja terlanjur terbentuk
            if (isset($_SESSION[$cCode]['main_elements']) && sizeof($_SESSION[$cCode]['main_elements']) > 0) {
                foreach ($_SESSION[$cCode]['main_elements'] as $eeName => $jasghhagsghaj) {
                    if (strpos($eeName, $elName . "_") !== false) {
                        unset($_SESSION[$cCode]['main_elements'][$eeName]);
                    }
                    else {
                    }
                }
            }


        }
        if (array_key_exists($elName, $relOptionConfigs)) {
            //reset semua inputan relatif yang mungkin saja terlanjur terbentuk
            foreach ($relOptionConfigs[$elName] as $trigVal => $options) {
                foreach ($options as $iVarName => $jasghahgsghasha) {
                    if (isset($_SESSION[$cCode]['main_elements'][$elName]['value']) && $_SESSION[$cCode]['main_elements'][$elName]['value'] == $trigVal) {
                    }
                    else {
                        if (isset($_SESSION[$cCode]['main_inputs'][$iVarName])) {
                            unset($_SESSION[$cCode]['main_inputs'][$iVarName]);
                        }
                    }


                }

            }


        }
        else {
        }

    }
    if (!isset($_SESSION[$cCode]['main_elements'])) {
        $_SESSION[$cCode]['main_elements'] = array();
    }
    $_SESSION[$cCode]['main_elements'][$elName] = array(
        "elementType" => $elementConfigs[$elName]['elementType'],
        "name" => $elName,
        "label" => $elementConfigs[$elName]['label'],
        "labelSrc" => isset($elementConfigs[$elName]['labelSrc']) ? $elementConfigs[$elName]['labelSrc'] : "--",
        "mdl_name" => "",
        "value" => $val,
    );

    //==masukkan ke gerbang utama
    $_SESSION[$cCode]["main"][$elName] = $val;
    if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
        foreach ($_SESSION[$cCode]['items'] as $iID => $iSpec) {
            if (isset($_SESSION[$cCode]['items'][$iID][$elName])) {
                $_SESSION[$cCode]['items'][$iID][$elName] = null;
                unset($_SESSION[$cCode]['items'][$iID][$elName]);
            }
        }
    }
}

function heFetchItemsElement_modul($jenisTr, $elName, $mdlName, $key, $helpName = "", $configUiJenis)
{
    $ci =& get_instance();
    $ci->load->helper("he_element");
    $cCode = "_TR_" . $jenisTr;
    $elementConfigs = isset($configUiJenis['receiptElementsItemsAuto']) ? $configUiJenis['receiptElementsItemsAuto'] : array();
    if (isset($_SESSION[$cCode]['items'][$elName])) {
        $items = $_SESSION[$cCode]['items'][$elName];

        $keySrc = $elementConfigs[0]['key'];
        $aFilter = isset($elementConfigs[0]['mdlFilter']) ? $elementConfigs[0]['mdlFilter'] : array();


        $prTemp = array();
        $paired = array();
        if (sizeof($elementConfigs) > 0) {
            foreach ($elementConfigs as $subConfig) {
//arrPrintPink($subConfig);
                if (isset($subConfig['pairedModel']) && sizeof($subConfig['pairedModel']) > 0) {
                    $ci->load->model("Coms/" . $subConfig['pairedModel']['mdlName']);
                    $pr = new $subConfig['pairedModel']['mdlName']();
                    if (sizeof($subConfig['pairedModel']['mdlFilter']) > 0) {

                        foreach ($subConfig['pairedModel']['mdlFilter'] as $prKey => $prVal) {
                            $prVal = makeValue($prVal, $items, $items, $static = 0);
                            $pr->addFilter("$prKey='$prVal'");
                        }
                    }

                    $pairedRek = $subConfig['pairedModel']['rekening'];
                    $pairedMethod = $subConfig['pairedModel']['mdlMethod'];
                    $prTemp = $pr->$pairedMethod($pairedRek);
                    if (sizeof($prTemp) > 0) {
                        $fieldID = $subConfig['pairedModel']['fieldID'];
                        $fieldLabel = $subConfig['pairedModel']['fieldLabel'];
                        foreach ($prTemp as $prSpec) {
                            $colName = $subConfig['pairedModel']['key'];

                            if ($prSpec->$colName == $key) {
                                $paired[$fieldLabel] = $prSpec->$fieldID;
                            }
                        }
                    }
                }
            }

        }
        else {
        }


        $ci->load->model("Mdls/" . $mdlName);
        $oo = new $mdlName();

        $oo->init();
        $oo->setFilters(array());
        if (sizeof($aFilter) > 0) {
            $oo = makeFilter($aFilter, $items, $oo);
        }
        else {
        }
        if ($oo->getTableName() == "static") {
            $oo->addFilter("$keySrc='$key'");
        }
        else {

            $oo->addFilter($oo->getTableName() . "." . "$keySrc='$key'");

        }

        $tmp = $oo->lookupAll()->result();
        $contents = array();
        $labelValue = "";
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                if (sizeof($paired) > 0) {
                    foreach ($paired as $prKey => $prVal) {
                        $row->$prKey = $prVal;
                    }
                }

                if (isset($elementConfigs[0]['usedFields']) && sizeof($elementConfigs[0]['usedFields']) > 0) {
                    if ($row->$keySrc == $key) {
                        foreach ($elementConfigs[0]['usedFields'] as $src => $label) {
                            $contents[$src] = isset($row->$src) ? $row->$src : "";
                        }

                        if (isset($elementConfigs[0]['labelSrc'])) {

                            $ex = explode("/", $elementConfigs[0]['labelSrc']);
                            if (sizeof($ex) > 1) {
                                $labelValue = "";
                                foreach ($ex as $col) {

                                    $labelValue .= $row->$col . " / ";
                                }
                                $labelValue = rtrim($labelValue, " / ");
                            }
                            else {
                                $kolomName = $elementConfigs[0]['labelSrc'];
                                $labelValue = $row->$kolomName;
                            }

                        }
                    }
                }
            }
        }
        else {

        }


        if (!isset($_SESSION[$cCode]['items_elements'])) {
            $_SESSION[$cCode]['items_elements'] = array();
        }


        //==daftarkan ke gerbang yang sesuai
        if (sizeof($tmp) > 0) {
            $_SESSION[$cCode]['items_elements'][$elName] = array(
                "elementType" => $elementConfigs[0]['elementType'],
                "name" => $elName,
                "label" => $elementConfigs[0]['label'],
                "key" => $key,
                "labelSrc" => isset($elementConfigs[0]['labelSrc']) ? $elementConfigs[0]['labelSrc'] : "--",
                "labelValue" => $labelValue,
                "mdl_name" => $mdlName,
                "contents" => base64_encode(serialize($contents)),
                "contents_intext" => print_r($contents, true),
            );
            //==masukkan ke gerbang items
            $_SESSION[$cCode]["items"][$elName][$helpName] = $key;
            $_SESSION[$cCode]["items"][$elName][$helpName . "__label"] = $labelValue;
            if (sizeof($contents)) {
                foreach ($contents as $key => $val) {
                    $_SESSION[$cCode]["items"][$elName][$helpName . "__" . $key] = $val;
                }
            }

        }
        else {
            unset($_SESSION[$cCode]['items_elements'][$elName]);
            unset($_SESSION[$cCode]["items"][$elName][$elName]);

        }

    }


}


function heFetchElement_modul_ns($jenisTr, $elName, $mdlName, $key, $configUiJenis, $sessionData)
{

    $ci =& get_instance();
    $ci->load->helper("he_element");

    $cCode = cCodeBuilderMisc($jenisTr);

    $elementConfigs = isset($configUiJenis['receiptElements']) ? $configUiJenis['receiptElements'] : array();
    $relElementConfigs = isset($configUiJenis['relativeElements']) ? $configUiJenis['relativeElements'] : array();
    $relOptionConfigs = isset($configUiJenis['relativeOptions']) ? $configUiJenis['relativeOptions'] : array();
    $configRecomData = isset($configUiJenis['pairRecomDataElement']) ? $configUiJenis['pairRecomDataElement'] : array();

    $pairedRelative = array();
    if (sizeof($relElementConfigs) > 0) {
        foreach ($relElementConfigs as $eSrc => $esSpec) {
            foreach ($esSpec as $esName => $psubSpec) {

                if (sizeof($psubSpec) > 0) {
                    foreach ($psubSpec as $rcID => $subSpec) {
                        $elementConfigs[$rcID] = $subSpec;
                        if (isset($subSpec['pairedModel']) && sizeof($subSpec['pairedModel']) > 0) {
                            $ci->load->model("Coms/" . $subSpec['pairedModel']['mdlName']);
                            $pr = new $subSpec['pairedModel']['mdlName']();
                            if (sizeof($subSpec['pairedModel']['mdlFilter']) > 0) {

                                foreach ($subSpec['pairedModel']['mdlFilter'] as $prKey => $prVal) {
                                    $prVal = makeValue($prVal, $sessionData['main'], $sessionData['main'], $static = 0);
                                    $pr->addFilter("$prKey='$prVal'");
                                }
                            }

                            $pairedRek = $subSpec['pairedModel']['rekening'];
                            $pairedMethod = $subSpec['pairedModel']['mdlMethod'];
                            $prTemp = $pr->$pairedMethod($pairedRek);
                            if (sizeof($prTemp) > 0) {
                                $fieldID = $subSpec['pairedModel']['fieldID'];
                                $fieldLabel = $subSpec['pairedModel']['fieldLabel'];
                                foreach ($prTemp as $prSpec) {
                                    $colName = $subSpec['pairedModel']['key'];
                                    if ($prSpec->$colName == $key) {
                                        $pairedRelative[$fieldLabel] = $prSpec->$fieldID;
                                    }
                                }
                            }
                        }

                    }
                }
                if ($esName == (isset($sessionData['main'][$eSrc]) ? $sessionData['main'][$eSrc] : "")) {
                    if (isset($psubSpec[$elName]['pairMethod']) && sizeof($psubSpec[$elName]['pairMethod']) > 0) {
                        $model = $psubSpec[$elName]['pairMethod']["recom"];
                        $ci->load->model("ReComs/" . $model);
                        $gateVal = $psubSpec[$elName]['pairMethod']["calculate"];
                        $tc = new $model();
                        $tc->pair($gateVal, $key);
                        $tc->exec();

                    }
                }
            }
        }


        if (array_key_exists($elName, $relElementConfigs)) {
            //reset semua nilai anakan relatif yang mungkin saja terlanjur terbentuk
            if (isset($sessionData['main_elements']) && sizeof($sessionData['main_elements']) > 0) {
                foreach ($sessionData['main_elements'] as $eeName => $jasghhagsghaj) {
                    if (strpos($eeName, $elName . "_") !== false) {
                        unset($sessionData['main_elements'][$eeName]);
                    }
                    else {
                    }
                }
            }


        }
        if (array_key_exists($elName, $relOptionConfigs)) {
            //reset semua inputan relatif yang mungkin saja terlanjur terbentuk
            foreach ($relOptionConfigs[$elName] as $trigVal => $options) {
                foreach ($options as $iVarName => $jasghahgsghasha) {
                    if (isset($sessionData['main_elements'][$elName]['key']) && $sessionData['main_elements'][$elName]['key'] == $trigVal) {
                    }
                    else {
                        // hapus semua main_input dp/cia/diskon bila sudah diisi maka diisi ulang...
                        if (isset($sessionData['main_inputs'])) {
                            foreach ($sessionData['main_inputs'] as $k_input => $v_input) {
                                $sessionData['main_inputs'][$k_input] = 0;
                                $sessionData['main'][$k_input] = 0;
                            }
                        }
                    }

                }

            }


        }
        else {
        }

    }

    $keySrc = $elementConfigs[$elName]['key'];
    $aFilter = isset($elementConfigs[$elName]['mdlFilter']) ? $elementConfigs[$elName]['mdlFilter'] : array();

    $prTemp = array();
    $paired = array();
    if (sizeof($elementConfigs) > 0) {
        foreach ($elementConfigs as $subConfig) {
            if (isset($subConfig['pairedModel']) && sizeof($subConfig['pairedModel']) > 0) {
                $ci->load->model("Coms/" . $subConfig['pairedModel']['mdlName']);
                $pr = new $subConfig['pairedModel']['mdlName']();
                if (sizeof($subConfig['pairedModel']['mdlFilter']) > 0) {

                    foreach ($subConfig['pairedModel']['mdlFilter'] as $prKey => $prVal) {
                        $prVal = makeValue($prVal, $sessionData['main'], $sessionData['main'], $static = 0);
                        $pr->addFilter("$prKey='$prVal'");
                    }
                }

                $pairedRek = $subConfig['pairedModel']['rekening'];
                $pairedMethod = $subConfig['pairedModel']['mdlMethod'];
                $prTemp = $pr->$pairedMethod($pairedRek);

                if (sizeof($prTemp) > 0) {
                    $fieldID = $subConfig['pairedModel']['fieldID'];
                    $fieldLabel = $subConfig['pairedModel']['fieldLabel'];

                    if (isset($key) && ($key != NULL)) {
                        $rslt = "";
                        foreach ($prTemp as $prSpec) {
                            $colName = $subConfig['pairedModel']['key'];
                            if ($prSpec->$colName == $key) {
                                if ($rslt == "") {
                                    $rslt = $prSpec->$fieldID;
                                }
                                else {
                                    $rslt .= "+" . $prSpec->$fieldID;
                                }
                                $paired[$fieldLabel] = $rslt;
                            }
                        }
                    }
                    else {
                    }
                }
                else {

                }
            }
        }
    }
    else {
    }

    $ci->load->model("Mdls/" . $mdlName);
    $oo = new $mdlName();


    if (sizeof($aFilter) > 0) {

        $oo = makeFilter($aFilter, $sessionData['main'], $oo);
    }
    else {
    }

    $oo->init();
    $oo->setFilters(array());
    if ($oo->getTableName() == "static") {
        $oo->addFilter("$keySrc='$key'");
    }
    else {
        $oo->addFilter($oo->getTableName() . "." . "$keySrc='$key'");
    }


    $tmp = $oo->lookupAll()->result();


    $contents = array();
    $labelValue = "";
    if (sizeof($tmp) > 0) {
        foreach ($tmp as $row) {
            if (sizeof($paired) > 0) {
                foreach ($paired as $prKey => $prVal) {
                    $row->$prKey = $prVal;
                }
            }
            if (sizeof($pairedRelative) > 0) {
                foreach ($pairedRelative as $prKeyRel => $prValRel) {
                    $row->$prKeyRel = $prValRel;
                }
            }


            if (isset($elementConfigs[$elName]['usedFields']) && sizeof($elementConfigs[$elName]['usedFields']) > 0) {
                if ($row->$keySrc == $key) {
                    foreach ($elementConfigs[$elName]['usedFields'] as $src => $label) {
                        $contents[$src] = isset($row->$src) ? $row->$src : "";
                    }

                    if (isset($elementConfigs[$elName]['labelSrc'])) {

                        $ex = explode("/", $elementConfigs[$elName]['labelSrc']);
                        if (sizeof($ex) > 1) {
                            $labelValue = "";
                            foreach ($ex as $col) {

                                $labelValue .= $row->$col . " / ";
                            }
                            $labelValue = rtrim($labelValue, " / ");
                        }
                        else {
                            $kolomName = $elementConfigs[$elName]['labelSrc'];
                            $labelValue = $row->$kolomName;
                        }

                    }
                }
            }
        }
    }
    else {

    }


    //  method diskon....
    if (isset($elementConfigs[$elName]['targetMethod']) && sizeof($elementConfigs[$elName]['targetMethod']) > 0) {
        foreach ($elementConfigs[$elName]['targetMethod'] as $tKey => $tVal) {
//            matiHEre("$key ".$tKey." ".$elName);
            if ($key == $tKey) {
                $model = $tVal;
                $ci->load->model("ReComs/" . $model);
//                $ci->load->helper("he_value_builder");
                $tt = New $model();
                $tt->pair();
                $tt->exec();
//                $ci->fillValues($jenisTr);
            }
        }
    }
    //kalkulasi relemet ke main jika ada perhitungan logic
    if (isset($elementConfigs[$elName]['pairMethod']) && sizeof($elementConfigs[$elName]['pairMethod']) > 0) {
        $model = $elementConfigs[$elName]['pairMethod']["recom"];
        $ci->load->model("ReComs/" . $model);
        $gateVal = $elementConfigs[$elName]['pairMethod']["calculate"];
        $tc = new $model();
        $tc->pair($gateVal, $key);
        $tc->exec();
        // matiHEre("pairMethod".$model);
    }

    if (isset($relElementConfigs[$elName]) && $elementConfigs[$elName] > 0) {
        if (isset($relElementConfigs[$elName][$key])) {
            foreach ($relElementConfigs[$elName][$key] as $tKey => $tVal) {
                if (isset($tVal['targetMethod2'])) {
                    foreach ($tVal['targetMethod2'] as $sKey => $sVal) {

                        if ($key == $sKey) {
                            $model = $sVal;
                            $ci->load->model("ReComs/" . $model);
                            $tt = New $model();
                            $tt->pair();
                            $tt->exec();
                        }


                    }
                }
            }
        }
    }


    if (!isset($sessionData['main_elements'])) {
        $sessionData['main_elements'] = array();
    }


    //==daftarkan ke gerbang yang sesuai
    if (sizeof($tmp) > 0) {
        $sessionData['main_elements'][$elName] = array(
            "elementType" => $elementConfigs[$elName]['elementType'],
            "name" => $elName,
            "label" => $elementConfigs[$elName]['label'],
            "key" => $key,
            "labelSrc" => isset($elementConfigs[$elName]['labelSrc']) ? $elementConfigs[$elName]['labelSrc'] : "--",
            "labelValue" => $labelValue,
            "mdl_name" => $mdlName,
            "contents" => base64_encode(serialize($contents)),
            "contents_intext" => print_r($contents, true),
        );
        //==masukkan ke gerbang utama
        $sessionData["main"][$elName] = $key;
        $sessionData["main"][$elName . "__label"] = $labelValue;
        if (sizeof($contents)) {
            foreach ($contents as $key => $val) {
                $sessionData["main"][$elName . "__" . $key] = $val;
            }
        }
        if (sizeof($configRecomData) > 0) {
            if (isset($configRecomData[$elName])) {
                $dataRe = $configRecomData[$elName];
                if (sizeof($configRecomData) > 0) {
                    $mdlName = $dataRe['mdlname'];
                    $filterKey = $dataRe['gateId'];
                    $targetGate = $dataRe['target'];
                    $keyID = $sessionData['main'][$filterKey];
                    $ci->load->model("Mdls/" . $mdlName);
                    $md = new $mdlName();
                    $tmRe = $md->lookUpAll()->result();
                    $array = array();
                    foreach ($tmRe as $data) {
                        $array[$data->id] = $data->name;
                    }
                    if (isset($array[$keyID])) {
                        if (isset($array[$keyID]) && $array[$keyID] == "dipotong") {
//                        matiHere("m");
                            foreach ($targetGate as $gate => $key) {
                                $sessionData[$gate][$key] = 0;//false
                            }
                        }
                        else {
//                        matiHere("m");
                            foreach ($targetGate as $gate => $key) {
                                $sessionData[$gate][$key] = 1;//true
                            }
                        }
                    }

                }
            }
        }

        return $sessionData;


    }


}

function heRecordElement_modul_ns($jenisTr, $elName, $val, $configUiJenis, $sessionData)
{

    $ci =& get_instance();
    $ci->load->helper("he_element");
//    $cCode = cCodeBuilderMisc($jenisTr);
    $elementConfigs = isset($configUiJenis['receiptElements']) ? $configUiJenis['receiptElements'] : array();
    $relElementConfigs = isset($configUiJenis['relativeElements']) ? $configUiJenis['relativeElements'] : array();
    $relOptionConfigs = isset($configUiJenis['relativeOptions']) ? $configUiJenis['relativeOptions'] : array();

//    arrPrint($sessionData);
//    mati_disini(__LINE__);
//    if (sizeof($relElementConfigs) > 0) {
//        foreach ($relElementConfigs as $eSrc => $esSpec) {
//            foreach ($esSpec as $esName => $psubSpec) {
//                if (sizeof($psubSpec) > 0) {
//                    foreach ($psubSpec as $rcID => $subSpec) {
//                        $elementConfigs[$rcID] = $subSpec;
//                    }
//                }
//            }
//        }
//
//        if (array_key_exists($elName, $relElementConfigs)) {
//            //reset semua nilai anakan relatif yang mungkin saja terlanjur terbentuk
//            if (isset($sessionData['main_elements']) && sizeof($sessionData['main_elements']) > 0) {
//                foreach ($sessionData['main_elements'] as $eeName => $jasghhagsghaj) {
//                    if (strpos($eeName, $elName . "_") !== false) {
//                        unset($sessionData['main_elements'][$eeName]);
//                    }
//                    else {
//                    }
//                }
//            }
//        }
//        if (array_key_exists($elName, $relOptionConfigs)) {
//            //reset semua inputan relatif yang mungkin saja terlanjur terbentuk
//            foreach ($relOptionConfigs[$elName] as $trigVal => $options) {
//                foreach ($options as $iVarName => $jasghahgsghasha) {
//                    if (isset($sessionData['main_elements'][$elName]['value']) && $sessionData['main_elements'][$elName]['value'] == $trigVal) {
//                    }
//                    else {
//                        if (isset($sessionData['main_inputs'][$iVarName])) {
//                            unset($sessionData['main_inputs'][$iVarName]);
//                        }
//                    }
//                }
//            }
//        }
//        else {
//        }
//
//    }
//
//    if (!isset($sessionData['main_elements'])) {
//        $sessionData['main_elements'] = array();
//    }


    $sessionData['main_elements'][$elName] = array(
        "elementType" => $elementConfigs[$elName]['elementType'],
        "name" => $elName,
        "label" => $elementConfigs[$elName]['label'],
        "labelSrc" => isset($elementConfigs[$elName]['labelSrc']) ? $elementConfigs[$elName]['labelSrc'] : "--",
        "mdl_name" => "",
        "value" => $val,
    );

    //==masukkan ke gerbang utama
    $sessionData["main"][$elName] = $val;
    if (isset($sessionData['items']) && sizeof($sessionData['items']) > 0) {
        foreach ($sessionData['items'] as $iID => $iSpec) {
            if (isset($sessionData['items'][$iID][$elName])) {
                $sessionData['items'][$iID][$elName] = null;
                unset($sessionData['items'][$iID][$elName]);
            }
        }
    }

    return $sessionData;

}

?>