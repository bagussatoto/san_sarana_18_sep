<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once "Modul_Controller.php";

class History extends Modul_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Mdls/MdlCurrency");
        /* ----------------------------------------------------------------------------------
          * loader cunstruk yg wajib ada
          * variabel-variabel bisa langsung dipangil, apa saja yang ada bisa dilihat didalamnya
          * ----------------------------------------------------------------------------------*/
        // require_once "_construct_file.php";
        $this->configUiModul = loadConfigUiModul_he_misc();
        $this->masterConfigUi = $this->config->item("heTransaksi_ui");
        $this->prefixName = "manufactur";
    }

    public function index()
    {
    }

    //--------------------------------------
    public function viewHistory()
    {

        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
        $starttime = microtime(true);
        $limit = 100;
        $maxPageNum = 20;
        $jenisTr = $this->uri->segment(4);
        $jenisTrsub = $this->uri->segment(5);
        $cCode = $this->cCode;
        $allStep = $this->configUi[$jenisTr]['steps'];
        $availSteps = array();
        foreach ($allStep as $step => $tempStep) {
            $availSteps[$tempStep['target']] = $step;
        }
        $selectedSTep = isset($availSteps[$jenisTrsub]) ? $availSteps[$jenisTrsub] : 1;
        $historyFields = isset($this->configUi[$jenisTr]['historyFields'][$selectedSTep]) ? $this->configUi[$jenisTr]['historyFields'][$selectedSTep] : $this->configUi[$jenisTr]['shortHistoryFields'];
        $pairRegistries = isset($this->configUi[$jenisTr]['pairRegistries']) ? $this->configUi[$jenisTr]['pairRegistries'] : array();
        $historyFieldsExt = isset($this->configUi[$jenisTr]["extHistoryFields"][$selectedSTep]) ? $this->configUi[$jenisTr]["extHistoryFields"][$selectedSTep] : array();
        $extHistoryFields2 = isset($this->configUi[$jenisTr]["extHistoryFields2"][$selectedSTep]) ? $this->configUi[$jenisTr]["extHistoryFields2"][$selectedSTep] : array();
        $customButton = isset($this->configLayout[$jenisTr]["customButton"][$selectedSTep]) ? $this->configLayout[$jenisTr]["customButton"][$selectedSTep] : array();
        $printValas = isset($this->configLayout[$jenisTr]["print_nvalas"]) ? $this->configLayout[$jenisTr]["print_nvalas"] : array();
        $pairTransaksi = isset($this->configUi[$jenisTr]['pairTransaksi']) ? $this->configUi[$jenisTr]['pairTransaksi'] : array();
        $extHistoryKeterangan = isset($this->configUi[$jenisTr]['extHistoryKeterangan'][$selectedSTep]) ? $this->configUi[$jenisTr]['extHistoryKeterangan'][$selectedSTep] : array();
        //arrPrint($extHistoryKeterangan);
        $modul = isset($this->masterConfigUi[$jenisTr]["modul"]) ? $this->masterConfigUi[$jenisTr]["modul"] : false;
        $modul_path = base_url() . $modul . "/";
        cekHItam(__LINE__ . " || " . $selectedSTep);
        $mb = New MobileDetect();
        $isMob = $mb->isMobile();
        if ($isMob) {
            $historyFields = isset($this->configUi[$jenisTr]['compactHistoryFields']) ? $this->configUi[$jenisTr]['compactHistoryFields'] : array();
        }

        $backdate_f = formatTanggal(backDate(30), 'Y-m-d');

        $date1 = isset($_GET['date1']) ? $_GET['date1'] : $backdate_f;
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");


        // cekHere($date1." ******* ".$date2);

        //region preparing ERP step labels for top link
        $steps = $this->configUi[$jenisTr]['steps'];
        $stepLabels = array();
        $stepLinks = array();
        if (sizeof($steps) > 1) {
            $subCodes = array();
            $stepCodes = array();
            $jmlStep = count($steps);

            foreach ($steps as $stepNumber => $stepSpec) {
                if ($stepNumber <= $jmlStep) {
                    $subCodes[$stepSpec['target']] = $stepSpec['label'];
                    $stepCodes[] = $stepSpec['target'];
                    $stepLabels[$stepNumber] = $stepSpec['label'];
                    $stepLinks[$stepNumber] = MODUL_PATH . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->jenisTr . "/" . $stepSpec['target'] . "?date1=$date1&date2=$date2";
                }
            }
            if (isset($_GET['stID'])) {
                $currentState = $_GET['stID'];
            }
            else {
                $currentState = strlen($this->uri->segment(5)) > 0 ? $this->uri->segment(5) : $this->configUi[$jenisTr]['steps'][1]['target'];
            }
        }
        //endregion

// arrPrint($historyFieldsExt);
        //region lookup histories

        $this->load->model("MdlTransaksiModul");

        $tr = new MdlTransaksiModul();
        $tr->setPrefix($this->prefixName);
        $searchStr = isset($_GET['search']) ? $_GET['search'] : "";
        if (my_cabang_id() == "-1") {
            $filters = array(
                // "cabang_id" => $this->placeId,
                "jenis_master" => $this->jenisTr,
                "link_id" => "0",
                "div_id" => $this->session->login['div_id'],
            );
        }
        else {
            if ($this->session->login['employee_type'] == "employee_freelance") {
                // $tr->addFilter("seller_id='" . $this->session->login['id'] . "'");
                $filters = array(
                    "cabang_id" => $this->placeId,
                    "jenis_master" => $this->jenisTr,
                    "link_id" => "0",
                    "div_id" => $this->session->login['div_id'],
                    "seller_id" => $this->session->login['id']

                );
            }
            else {
// arrPrint();
                $filters = array(
                    "cabang_id" => $this->placeId,
                    "gudang_id" => $this->session->login["gudang_id"],
                    "jenis_master" => $this->jenisTr,
                    "link_id" => "0",
                    "div_id" => $this->session->login['div_id'],
                );
            }

        }

        //region date filter
        // $this->db->where("fulldate>='" . $date1 . "'");
        // $this->db->where("fulldate<='" . $date2 . "'");
        //endregion
        if (sizeof($filters) > 0) {
            foreach ($filters as $key_f => $val_f) {
                $tr->addFilter("$key_f='" . $val_f . "'");
            }
        }
        if (isset($currentState)) {
            $tr->addFilter("jenis='" . $currentState . "'");
        }

        $addParams = array();
        if (isset($_GET['addParams'])) {
            $addParams = unserialize(base64_decode($_GET['addParams']));
        }
        if ($addParams != null && sizeof($addParams) > 0) {
            foreach ($addParams as $f) {
                $tr->addFilter($f);
            }
        }


        if (isset($_GET['search'])) {
            $tr->setKeyWord($searchStr);
        }
        else {
            $this->db->where("fulldate>='" . $date1 . "'");
            $this->db->where("fulldate<='" . $date2 . "'");
        }

        $jmlData = $tr->lookupDataCount();
        $page = (isset($_GET['page']) && $_GET['page'] > 0) ? ($_GET['page']) : 1;
        $offset = ($limit * ($page - 1));

        $addParams = array();
        if (isset($_GET['addParams'])) {
            $addParams = unserialize(base64_decode($_GET['addParams']));
        }
        if ($addParams != null && sizeof($addParams) > 0) {
            foreach ($addParams as $f) {
                $tr->addFilter($f);
            }
        }

        $action = array(
            "viewDetails" => base_url() . get_class($this) . "/viewDetails",
        );

        if (isset($_GET['search'])) {
            $tr->setKeyWord($searchStr);
        }
        else {
            $this->db->where("fulldate>='" . $date1 . "'");
            $this->db->where("fulldate<='" . $date2 . "'");
        }
        if (isset($_GET['date1'])) {
            $limit = "";
        }
        $tmpHist = $tr->lookupHistories($jmlData, $limit, $page)->result();
//        cekLime($this->db->last_query());
        // arrPrintWebs($tmpHist);
        //matiHEre();
        $arrCurrency = array();
        if (sizeof($printValas) > 0) {
            $trv = new MdlCurrency();
            $tmpCurrency = $trv->lookupAll()->result();
            if (sizeof($tmpCurrency) > 0) {
                foreach ($tmpCurrency as $key => $value) {
                    $arrCurrency[$key] = $value;
                }
            }
        }

        $cabang_id = $this->placeId;

        $arrayHistory = array();
        $arrayHistory_ids = array();
        $arrayHistory_canceled = array();
        $sumValue = array();
        if (sizeof($tmpHist) > 0) {
            if (sizeof($pairRegistries) > 0) {
                $arrSalesName = array();
                $arrTransID = array();
                $arrTransTopID = array();
                $arrIndexID = array();
                $arrIdsHist = array();
                $arrTransHist = array();
                $arrTransMainHist = array();
                foreach ($tmpHist as $row) {
                    $arrTransID[] = $row->id;
                    $arrTransTopID[] = $row->id_top;

                    if ($row->ids_his != "") {
                        $hist = blobDecode($row->ids_his);
                        foreach ($hist as $hisSpec) {
                            $arrIdsHist[$row->id][$hisSpec['step']] = array(
                                "step" => $hisSpec['step'],
                                "trID" => $hisSpec['trID'],
                                "nomer" => $hisSpec['nomer'],
                            );
                            $arrTransHist[] = $hisSpec['trID'];
                        }
                    }
                }

                $tmpReg_result = array();
                $trReg = new MdlTransaksiModul();
                $trReg->setPrefix($this->prefixName);
                $trReg->setFilters(array());

                // $trReg->setFields($pairRegistries);
                $selectKolom = implode(",", $pairRegistries) . ",transaksi_id";
                $trReg->setJointSelectFields($selectKolom);
                $trReg->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
                $tmpReg = $trReg->lookupDataRegistries()->result();
                if (sizeof($tmpReg) > 0) {
                    foreach ($tmpReg as $regRow) {
                        foreach ($pairRegistries as $param) {
                            $tmpReg_result[$regRow->transaksi_id][$param] = blobDecode($regRow->$param);
                        }

                    }
                }


                $tr = new MdlTransaksiModul();
                $tr->setPrefix($this->prefixName);
                $tr->setFilters(array());
                $tr->addFilter("id in ('" . implode("','", $arrTransTopID) . "')");
                $tmpTrTop = $tr->lookUpMainTransaksi()->result();
                if (sizeof($tmpTrTop) > 0) {
                    foreach ($tmpTrTop as $topSpec) {
                        $arrSalesName[$topSpec->id_top] = $topSpec->oleh_nama;
                    }
                }

                if (sizeof($arrIdsHist) > 0) {
                    $tr = new MdlTransaksiModul();
                    $tr->setPrefix($this->prefixName);
                    // $tr->setParam("id");
                    // $tr->setInParam($arrTransHist);
                    $tr->setFilters(array());
                    $tr->addFilter("id in ('" . implode("','", $arrTransHist) . "')");
                    $tmpTransHist = $tr->lookUpMainTransaksi()->result();
                    $tmpTransHist_result = array();
                    if (sizeof($tmpTransHist) > 0) {
                        foreach ($tmpTransHist as $histSpec) {
                            $tmpTransHist_result[$histSpec->id] = array(
                                "oleh_id" => $histSpec->oleh_id,
                                "oleh_nama" => $histSpec->oleh_nama,
                            );
                        }
                    }
                    //                    arrPrint($tmpTransHist_result);
                    //                    matiHEre();
                    if (sizeof($arrIdsHist) > 0) {
                        foreach ($arrIdsHist as $trID => $histSpec) {
                            foreach ($histSpec as $step => $detailSpec) {
                                if (array_key_exists($detailSpec['trID'], $tmpTransHist_result)) {
                                    $detailSpec['main'] = $tmpTransHist_result[$detailSpec['trID']];
                                }
                                $arrTransMainHist[$trID][$step] = $detailSpec;
                            }
                        }
                    }

                }

            }

            //arrPrint($tmpReg_result);
            //            matiHere();
            $numb = 0;
            foreach ($tmpHist as $ii => $row) {

                $this->placeId = $cabang_id = $row->cabang_id;

                // region ids_his
                $id_hist = blobDecode($row->ids_his);
                // endregion ids_his

                //region memangil global counter
                $tNomer_top = $row->nomer_top;
                //                $tr = new MdlTransaksiModul();
                //                $tr->addFilter("param='main'");
                //                $tmpReg = $tr->lookupRegistriesByNumber($tNomer_top)->result();
                //
                //                $arrSalesName = "";
                //                foreach ($tmpReg as $tmpRowReg) {
                //                    $arrSalesName = $tmpRowReg->oleh_nama;
                //                }
                $salesName = isset($arrSalesName[$row->id_top]) ? $arrSalesName[$row->id_top] : "-";


                $tNomer = $row->nomer;
                $jenisTrtop = explode(".", $tNomer_top)[0];
                $jenisTrsub = explode(".", $tNomer)[0];
                $counterjenis = "$jenisTrsub|" . $this->placeId;
                // $counterjenis = my_cabang_id()=="-1"? "$jenisTrsub":"$jenisTrsub|" . $this->placeId;

                // matiHEre($jenisTrsub);
                $counterIds_his = blobDecode(blobDecode($row->ids_his)[1]['counters']);
                $counters = blobDecode($row->counters);
                // arrPrint($counters);
                // matiHere();

                $counterGlobal = $counters['stepCode|placeID'][$counterjenis];


                $counterIds_his_global = isset($counterIds_his['stepCode|placeID']["$jenisTrtop|$cabang_id"]) ? $counterIds_his['stepCode|placeID']["$jenisTrtop|$cabang_id"] : "";
                $cGlobals = digit_5($counterGlobal);
                $cGlobal_spo = digit_5($counterIds_his_global);

                //endregion

                if (sizeof($pairRegistries) > 0) {
                    if ((sizeof($tmpReg_result) > 0) && (isset($tmpReg_result[$row->id]))) {
                        foreach ($tmpReg_result[$row->id] as $param => $eReg) {
                            //                                                        cekLime($param);
                            if ($param == "main") {
                                foreach ($eReg as $k => $v) {
                                    if (!isset($row->$k)) {
                                        $row->$k = $v;
                                    }
                                }
                            }
                            else {
                                if (sizeof($extHistoryFields2) > 0) {
                                    foreach ($extHistoryFields2 as $k1 => $v1) {
                                        if (is_array($v1)) {
                                            $kolom = $v1['kolom'];
                                            $format = $v1['format'];
                                            //                                                cekHitam(":: $kolom :: $format ::");
                                            if (!isset($row->$k1)) {
                                                $tmpDetail = "";
                                                foreach ($eReg as $eeReg) {
                                                    $valDetail = formatField_he_format($format, $eeReg[$kolom]);
                                                    $tmpDetail .= "<span>$valDetail</span><br>";
                                                }
                                                $row->$k1 = $tmpDetail;
                                            }
                                        }
                                        else {

                                            if (!isset($row->$k1)) {
                                                $tmpDetail = "";
                                                foreach ($eReg as $eeReg) {
                                                    $valDetail = formatField_he_format("nomer", $eeReg[$v1]);
                                                    $tmpDetail .= "<span>$valDetail</span><br>";
                                                    //                                                arrPrint($eeReg);
                                                }
                                                $row->$k1 = $tmpDetail;
                                            }
                                        }
                                        //                                        else{
                                        //                                            mati_disini("sudah ada $k1");
                                        //                                        }
                                        //
                                    }
                                }
                            }
                        }
                    }
                    if (sizeof($pairTransaksi) > 0) {
                        if ($row->referenceID > 0) {
                            $trPair = new MdlTransaksiModul();
                            $trPair->setPrefix($this->prefixName);
                            $trPair->addFilter("id='" . $row->referenceID . "'");
                            $trPairTmp = $trPair->lookupMainTransaksi()->result();
                            if (sizeof($trPairTmp) > 0) {
                                $hisTr = isset($trPairTmp[0]->ids_his) ? blobDecode($trPairTmp[0]->ids_his) : array();
                                foreach ($hisTr as $step => $hisTrSpec) {
                                    foreach ($pairTransaksi['kolom'] as $keyPair => $labelPair) {
                                        $keyPairs = $keyPair . "_" . $step;
                                        $row->$keyPairs = isset($hisTrSpec[$labelPair]) ? $hisTrSpec[$labelPair] : "--";
                                    }
                                }
                            }
                        }
                    }
                }

                //arrPrintWebs($row);
                if (sizeof($historyFieldsExt) > 0) {
                    foreach ($historyFieldsExt as $alias => $colom) {
                        $row->$alias = $row->$colom;
                    }
                }
                $tmp = array();
                $tmp1 = array();
                // arrPrint($row);
                //                 break;
                $numb++;
                foreach ($historyFields as $fName => $fLabel) {
                    //                    cekHitam($fName);
                    if (strpos($fName, '+') !== false) {//==mengandung penggabungan (+)
                        $chars = explode("+", $fName);
                        $colValue = "";
                        foreach ($chars as $key) {
                            if (is_numeric($row->$key)) {
                                if (!isset($sumValue[$key])) {
                                    $sumValue[$key] = 0;
                                }
                                $sumValue[$key] += $row->$key;
                            }
                            $colValue .= isset($row->$key) ? formatField_he_format($key, $row->$key) . "<br>" : "";
                        }
                        $colValue = rtrim($colValue, "<br>");
                    }
                    else {

                        if (is_numeric(isset($row->$fName) ? $row->$fName : "")) {
                            if (!isset($sumValue[$fName])) {
                                $sumValue[$fName] = 0;
                            }
                            $sumValue[$fName] += $row->$fName;
                        }

                        //region nomer dengan global counter
                        if ($fName == "nomer") {
                            // switch ($jenisTr) {
                            switch ($jenisTrsub) {
                                case "582s":
                                    $kolomValues = $row->$fName . "&#x2011;$cGlobals";
                                    break;
                                // case "582spd":
                                default:
                                    // $kolomValue_0s = formatField($fName, $row->$fName);
                                    $kolomValue_0s = formatField_he_format($fName, $row->$fName);
                                    $kolomValues = str_replace("</span>", "&#x2011;" . $cGlobals, $kolomValue_0s);
                                    break;
                            }
                        }
                        elseif ($fName == "nomer_top") {
                            // $kolomValue_0s = formatField($fName, $row->$fName);
                            $kolomValue_0s = formatField_he_format($fName, $row->$fName);
                            $kolomValues = str_replace("</span>", "&#x2011;" . $cGlobal_spo, $kolomValue_0s);
                        }
                        else {
                            //                            cekHitam(";; $fName");
                            // $kolomValues = isset($row->$fName) ? formatField($fName, $row->$fName) : "-";
                            $kolomValues = isset($row->$fName) ? formatField_he_format($fName, $row->$fName) : "-";
                        }
                        //endregion

                        // $colValue = isset($row->$fName) ? formatField($fName, $row->$fName) : "";

                        $colValue = isset($row->$fName) ? $kolomValues : "";
                        //                        cekLime("$colValue"." ".$fName);

                    }

                    //                    if ($fName == "ids_his") {
                    if (is_array($fLabel)) {

                        $hisStep = $fLabel['step'];
                        $hisKey = $fLabel['key'];
                        //                        $tNomer = $id_hist[$hisStep][$hisKey];


                        if ($hisKey == "nomer") {
                            $colValue = isset($row->ids_his) ? showHistoriGlobalNumbers($row->ids_his, $hisStep, true, $this->jenisTr) : "";
                        }
                        else {
                            if (isset($fLabel['transaksi_jenis2'][$row->transaksi_jenis2])) {
                                $getKey = $fLabel['transaksi_jenis2'][$row->transaksi_jenis2];
                                //                                cekHere(":: $getKey ::");
                                // $colValue = isset($row->$getKey) ? formatField($getKey, $row->$getKey) : "";
                                // $colValue = isset($row->$getKey) ? formatField_he_format($getKey, $row->$getKey,$this->jenisTr,$modul_path) : "";
                                $colValue = isset($row->$getKey) ? formatField_he_format($getKey, $row->$getKey) : "";
                            }
                            else {

                                $colValue = "-";
                            }
                        }


                        //                            $tr = new MdlTransaksiModul();
                        //                            $tr->setFilters(array());
                        //                            $tr->addFilter("param='main'");
                        //                            $tmpReg = $tr->lookupRegistriesByNumber($tNomer)->result();
                        ////                            cekHere($this->db->last_query());
                        //
                        //                            $logistic = $tmpReg[0]->oleh_nama;
                        $logistic = "";
                        if (isset($arrTransMainHist[$row->id][$hisStep]['main'])) {
                            $main = $arrTransMainHist[$row->id][$hisStep]['main'];
                            $logistic = $main['oleh_nama'];
                        }
                    }
                    //                    }

                    if ($fName == "no") {
                        // $colValue = formatField($fName, $numb);
                        $colValue = formatField_he_format($fName, $numb);
                    }

                    //                    cekHere($logistic);
                    $tmp['logistic'] = isset($logistic) && $logistic != null ? $logistic : 'undefined';
                    $tmp['sales_name'] = $salesName;
                    $tmp[$fName] = $colValue;
                    $tmp1["id"] = $row->id;
                }


                if (sizeof($arrCurrency) > 0) {
                    $valas = "";
                    $valas .= "<div class='btn-group'>";
                    $valas .= "<button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>";
                    $valas .= "<i class='fa fa-print'></i>";
                    $valas .= "<span class='caret'></span>";
                    $valas .= "</button>";

                    $valas .= "<ul style='background:#cde8ff;' class='dropdown-menu dropdown-menu-right'>";
                    foreach ($arrCurrency as $arrV) {
                        $nama = $arrV->nama;
                        $nomer = $row->nomer;
                        $nilai = number_format($arrV->exchange, 0);
                        // $valas .= " <li class='text-bold'><a class='dropdown-item' href='javascript:void(0);' onclick=\"top.popBig('" . base_url() . "Transaksi/viewReceipt/$nomer?type=" . blobEncode($nama) . "&f=" . blobEncode($arrV->exchange) . "')\"> <i class='fa fa-print'></i> in $nama - ($nilai) </a></li>";
                        $valas .= " <li class='text-bold'><a class='dropdown-item' href='javascript:void(0);' onclick=\"top.popBig('" . MODUL_PATH . "Printing/viewReceipt/" . $this->jenisTr . "/$nomer?type=" . blobEncode($nama) . "&f=" . blobEncode($arrV->exchange) . "')\"> <i class='fa fa-print'></i> in $nama - ($nilai) </a></li>";
                    }

                    // $valas .= " <li><a class='btn btn-xs btn-warning' href='javascript:void(0);' onclick=\"top.location.href='" . base_url() . "data/view/Currency'\"> <i class='fa fa-plus'></i> tambah currency </a></li>";
                    $valas .= " <li><a class='btn btn-xs btn-warning' href='javascript:void(0);' onclick=\"top.location.href='" . MODUL_PATH . "data/view/Currency'\"> <i class='fa fa-plus'></i> tambah currency </a></li>";
                    $valas .= "</ul>";
                    $valas .= "</div>";
                    $tmp["print_nvalas"] = $valas;
                }

                $tmp['next_pic'] = "-";

                // menambah background-color karena dicancel/reject/undo, atau yang dibatalkan
                //                $tmp['keterangan'] = "-";
                $addKeterangan = "";
                if (isset($row->trash_4) && ($row->trash_4 == 1)) {
                    //                    $tmp['keterangan'] = "CANCELED";
                    $addKeterangan = "CANCELED";
                    //                    $addKeterangan .= $row->cancel_dtime != NULL ? "&nl2br; date: " . $row->cancel_dtime : "";
                    //                    $addKeterangan .= $row->cancel_name != NULL ? "&nl2br; by: " . $row->cancel_name : "";
                    $addKeterangan .= $row->cancel_dtime != NULL ? nl2br(" date: " . $row->cancel_dtime) : "";
                    $addKeterangan .= $row->cancel_name != NULL ? nl2br(" by: " . $row->cancel_name) : "";
                    $arrayHistory_canceled[$ii] = array(
                        "bgcolor" => "background-color:red;color:#cccccc;",
                    );
                }
                // menambah background-color karena diedit...
                if (sizeof($extHistoryKeterangan) > 0) {
                    foreach ($extHistoryKeterangan as $mode => $modeSpec) {
                        $mode_result = "";
                        if (sizeof($modeSpec) > 0) {
                            if (isset($row->$modeSpec['kolom']) && ($row->$modeSpec['kolom'] == $modeSpec['value'])) {
                                $addBr = $addKeterangan != NULL ? "<hr>" : "";
                                $l_result = "";
                                if (is_array($modeSpec['labels'])) {
                                    foreach ($modeSpec['labels'] as $l) {
                                        if ($l_result == "") {
                                            $l_result = "$addBr $mode by: " . formatField($l, $row->$l);
                                        }
                                        else {
                                            $l_result .= ", " . formatField($l, $row->$l);
                                        }
                                    }
                                }
                                else {
                                    $l_result = $modeSpec['labels'];
                                }
                                $mode_result .= $l_result;

                                //----------------------------------------
                                if (isset($modeSpec['style'])) {
                                    $color = $modeSpec['style']['color'];
                                    $bgcolor = $modeSpec['style']['bgcolor'];
                                    $arrayHistory_keterangan[$ii] = array(
                                        "bgcolor" => "background-color:$bgcolor;color:$color;",
                                    );
                                }
                            }

                        }
                        $addKeterangan .= $mode_result;


                    }
                }


                $tmp['keterangan'] = $addKeterangan;
                $arrayHistory[$ii] = $tmp;
                $arrayHistory_ids[$ii] = $tmp1;


            }
        }
        //endregion


        //region link to add new transaction
        if (placeCanMakeTrans($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr)) {
            //        if (in_array($this->configUi[$jenisTr]["steps"][1]['userGroup'], $this->session->login['membership'])) {
            $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
            if (array_key_exists($this->jenisTr, $createIndexes)) {
                $targetUrl = base_url() . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
            }
            else {
                $targetUrl = MODUL_PATH . "Create/index/" . $this->jenisTr;
            }

            $addLink = array(
                "link" => $targetUrl,
                "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->configUi[$jenisTr]["steps"][1]['label'],
            );
        }
        else {
            $addLink = null;
        }
        //endregion

        //region prepare params for viewer
        $data = array(
            "mode" => $this->uri->segment(3),
            //            "mode" => "viewHistory",
            "isMobile" => $isMob,
            "jenisTr" => $jenisTr,
            "trName" => $this->configUi[$jenisTr]["label"],
            "errMsg" => $this->session->errMsg,
            "title" => isset($subCodes) && isset($currentState) ? $subCodes[$currentState] : $this->jenisTrName,
            "subTitle" => "histories",
            "customButton" => $customButton,
            "customButtonTarget" => isset($currentState) ? "$currentState?date1=$date1&date2=$date2" : "",
            "arrayHistoryLabels" => $historyFields,
            "arrayHistory" => $arrayHistory,
            "arrayHistorySumField" => $sumValue,
            "arrayHistoryId" => $arrayHistory_ids,
            "action" => $action,
            "steps" => $steps,
            "stepLabels" => $stepLabels,
            "stepLinks" => $stepLinks,
            "addParams" => isset($_GET['addParams']) ? $_GET['addParams'] : null,
            "currentState" => isset($currentState) ? $currentState : "all states",
            "alternateLink" => MODUL_PATH . "/Transaksi/viewIncomplete/" . $this->uri->segment(3),
            "alternateLinkCaption" => "incomplete " . $this->configUi[$jenisTr]["label"] . " <span class='glyphicon glyphicon-arrow-right'></span>",
            "addLink" => $addLink,
            "filters" => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "thisPage" => MODUL_PATH . get_class($this) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5),
            // "thisPage"             => MODUL_PATH . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) ,

            "history_canceled" => isset($arrayHistory_canceled) ? $arrayHistory_canceled : array(),
            "history_keterangan" => isset($arrayHistory_keterangan) ? $arrayHistory_keterangan : array(),
        );
        //endregion
        $endtime = microtime(true); // Bottom of page
        $valTimeEnd = $endtime - $starttime;
        //        cekBiru("load time start $starttime ||  end $endtime =>" . "$valTimeEnd");

        $this->load->view("history", $data);
    }

    public function showData()
    {

//        arrPrintHijau($this->session->login);
        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
        $starttime = microtime(true);
        $limit = 20;
        $maxPageNum = 20;
        $jenisTr = $this->jenisTr;
        $jenisTrsub = $this->uri->segment(6);
        $pid = $bomID = $this->uri->segment(5);
        $cCode = $this->cCode;
        $allStep = $this->configUi[$jenisTr]['steps'];
        $origWip = isset($_GET["srcWip"]) ? $_GET["srcWip"] : "";
        $wip = isset($_GET["srcWip"]) ? blobDecode($_GET["srcWip"]) : array();
        $jointtab = sizeof($wip) > 0 ? true : false;

        $availSteps = array();
        foreach ($allStep as $step => $tempStep) {
            $availSteps[$tempStep['target']] = $step;
        }
        $selectedSTep = isset($availSteps[$jenisTrsub]) ? $availSteps[$jenisTrsub] : 1;
        $historyFields = isset($this->configUi[$jenisTr]['historyFields'][$selectedSTep]) ? $this->configUi[$jenisTr]['historyFields'][$selectedSTep] : $this->configUi[$jenisTr]['shortHistoryFields'];
        $pairRegistries = isset($this->configUi[$jenisTr]['pairRegistries']) ? $this->configUi[$jenisTr]['pairRegistries'] : array();
        $historyFieldsExt = isset($this->configUi[$jenisTr]["extHistoryFields"][$selectedSTep]) ? $this->configUi[$jenisTr]["extHistoryFields"][$selectedSTep] : array();
        $extHistoryFields2 = isset($this->configUi[$jenisTr]["extHistoryFields2"][$selectedSTep]) ? $this->configUi[$jenisTr]["extHistoryFields2"][$selectedSTep] : array();
        $customButton = isset($this->configLayout[$jenisTr]["customButton"][$selectedSTep]) ? $this->configLayout[$jenisTr]["customButton"][$selectedSTep] : array();
        $printValas = isset($this->configLayout[$jenisTr]["print_nvalas"]) ? $this->configLayout[$jenisTr]["print_nvalas"] : array();
        $pairTransaksi = isset($this->configUi[$jenisTr]['pairTransaksi']) ? $this->configUi[$jenisTr]['pairTransaksi'] : array();
        $extHistoryKeterangan = isset($this->configUi[$jenisTr]['extHistoryKeterangan'][$selectedSTep]) ? $this->configUi[$jenisTr]['extHistoryKeterangan'][$selectedSTep] : array();
        $modul = isset($this->masterConfigUi[$jenisTr]["modul"]) ? $this->masterConfigUi[$jenisTr]["modul"] : false;
        $modul_path = base_url() . $modul . "/";
        //arrPrint($extHistoryKeterangan);

        $mb = New MobileDetect();
        $isMob = $mb->isMobile();
        if ($isMob) {
            $historyFields = isset($this->configUi[$jenisTr]['compactHistoryFields']) ? $this->configUi[$jenisTr]['compactHistoryFields'] : array();
        }

        $backdate_f = formatTanggal(backDate(30), 'Y-m-d');

        $date1 = isset($_GET['date1']) ? $_GET['date1'] : $backdate_f;
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");


        //region preparing ERP step labels for top link
        $steps = $this->configUi[$jenisTr]['steps'];
//        unset($steps[sizeof($steps)]);
        $stepLabels = array();
        $stepLinks = array();
        if (sizeof($steps) > 1) {
            $subCodes = array();
            $stepCodes = array();
            $jmlStep = count($steps);

            foreach ($steps as $stepNumber => $stepSpec) {
                if ($stepNumber <= $jmlStep) {
                    $subCodes[$stepSpec['target']] = $stepSpec['label'];
                    $stepCodes[] = $stepSpec['target'];
                    $stepLabels[$stepNumber] = $stepSpec['label'];
//                    $stepLinks[$stepNumber] = MODUL_PATH . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4). "/" . $this->uri->segment(5). "/" . $this->uri->segment(6) . "/" . $stepSpec['target'] . "?date1=$date1&date2=$date2";
                    $stepLinks[$stepNumber] = MODUL_PATH . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/" . $stepSpec['target'] . "?date1=$date1&date2=$date2&srcWip=" . $_GET["srcWip"];
                }
            }
            if (isset($_GET['stID'])) {
                $currentState = $_GET['stID'];
            }
            else {
                $currentState = strlen($this->uri->segment(6)) > 0 ? $this->uri->segment(6) : $this->configUi[$jenisTr]['steps'][1]['target'];
            }
        }
        unset($steps[sizeof($steps)]);
        unset($stepLabels[sizeof($stepLabels)]);
        unset($stepLinks[sizeof($stepLinks)]);
        //endregion


        //region lookup histories
        $this->load->model("MdlTransaksiModul");
        $tr = new MdlTransaksiModul();
        $tr->setPrefix($this->prefixName);
        $searchStr = isset($_GET['search']) ? $_GET['search'] : "";
        if (my_cabang_id() == "-1") {
            $filters = array(
                // "cabang_id" => $this->placeId,
                "jenis_master" => $this->jenisTr,
                "link_id" => "0",
                "div_id" => $this->session->login['div_id'],
            );
        }
        else {
            if ($this->session->login['employee_type'] == "employee_freelance") {
                $filters = array(
                    "cabang_id" => $this->placeId,
                    "jenis_master" => $this->jenisTr,
                    "link_id" => "0",
                    "div_id" => $this->session->login['div_id'],
                    "seller_id" => $this->session->login['id']
                );
            }
            else {
                $filters = array(
                    "cabang_id" => $this->placeId,
                    "jenis_master" => $this->jenisTr,
                    "link_id" => "0",
                    "div_id" => $this->session->login['div_id'],
                );
            }
        }
        if (sizeof($filters) > 0) {
            foreach ($filters as $key_f => $val_f) {
                $tr->addFilter("$key_f='" . $val_f . "'");
            }
        }
        if (isset($currentState)) {
            $tr->addFilter("jenis='" . $currentState . "'");
        }
        $tr->addFilter("bom_id='$bomID'");
        $tr->addFilter("gudang_id=" . my_gudang_id());
        $addParams = array();
        if (isset($_GET['addParams'])) {
            $addParams = unserialize(base64_decode($_GET['addParams']));
        }
        if ($addParams != null && sizeof($addParams) > 0) {
            foreach ($addParams as $f) {
                $tr->addFilter($f);
            }
        }
        if (isset($_GET['search'])) {
            $tr->setKeyWord($searchStr);
        }
        else {
//            if(isset($_GET['date1']) && isset($_GET['date2'])){
//                $this->db->where("fulldate>='" . $date1 . "'");
//                $this->db->where("fulldate<='" . $date2 . "'");
//            }
        }
        $jmlData = $tr->lookupDataCount();
        $page = (isset($_GET['page']) && $_GET['page'] > 0) ? ($_GET['page']) : 1;
        $offset = ($limit * ($page - 1));
        $addParams = array();
        if (isset($_GET['addParams'])) {
            $addParams = unserialize(base64_decode($_GET['addParams']));
        }
        if ($addParams != null && sizeof($addParams) > 0) {
            foreach ($addParams as $f) {
                $tr->addFilter($f);
            }
        }
        $action = array(
            "viewDetails" => base_url() . get_class($this) . "/viewDetails",
        );
        if (isset($_GET['search'])) {
            $tr->setKeyWord($searchStr);
        }
        else {
//            if(isset($_GET['date1']) && isset($_GET['date2'])){
//                $this->db->where("fulldate>='" . $date1 . "'");
//                $this->db->where("fulldate<='" . $date2 . "'");
//            }
        }
        $tr->addFilter("bom_id='$bomID'");
        $tr->addFilter("gudang_id=" . my_gudang_id());
        $tmpHist = $tr->lookupHistories($jmlData, $limit, $page)->result();
        ceklIme($this->db->last_query());
//        showLast_query("biru");
//        cekKuning(sizeof($tmpHist));
        // arrPrintWebs($tmpHist);
        //matiHEre();
        $arrCurrency = array();
        if (sizeof($printValas) > 0) {
            $this->load->model("Mdls/MdlCurrency");
            $trv = new MdlCurrency();
            $tmpCurrency = $trv->lookupAll()->result();
            if (sizeof($tmpCurrency) > 0) {
                foreach ($tmpCurrency as $key => $value) {
                    $arrCurrency[$key] = $value;
                }
            }
        }

        $cabang_id = $this->placeId;

        $arrayHistory = array();
        $arrayHistory_ids = array();
        $arrayHistory_canceled = array();
        $sumValue = array();
        if (sizeof($tmpHist) > 0) {
            if (sizeof($pairRegistries) > 0) {
                $arrSalesName = array();
                $arrTransID = array();
                $arrTransTopID = array();
                $arrIndexID = array();
                $arrIdsHist = array();
                $arrTransHist = array();
                $arrTransMainHist = array();
                foreach ($tmpHist as $row) {
                    $arrTransID[] = $row->id;
                    $arrTransTopID[] = $row->id_top;

                    if ($row->ids_his != "") {
                        $hist = blobDecode($row->ids_his);
                        foreach ($hist as $hisSpec) {
                            $arrIdsHist[$row->id][$hisSpec['step']] = array(
                                "step" => $hisSpec['step'],
                                "trID" => $hisSpec['trID'],
                                "nomer" => $hisSpec['nomer'],
                            );
                            $arrTransHist[] = $hisSpec['trID'];
                        }
                    }
                }

                $tmpReg_result = array();
                $trReg = new MdlTransaksiModul();
                $trReg->setPrefix($this->prefixName);
                $trReg->setFilters(array());

                // foreach ($pairRegistries as $param) {
                // $trReg->setParam("transaksi_id");
                // $trReg->setInParam($arrTransID);
                // $trReg->setFilters(array("param" => $param));
                // arrPrint($arrTransID);
                // matiHere();
                if (sizeof($pairRegistries) > 0) {
                    $selectKolom = implode(",", $pairRegistries) . ",transaksi_id";
                    // matiHEre(__LINE__);
                    $trReg->setJointSelectFields($selectKolom);
                    $trReg->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
                    $tmpReg = $trReg->lookupDataRegistries()->result();
                    // ceklIme($this->db->last_query());
                    // matiHEre();

                    //                                        arrPrint($tmpReg);
                    if (sizeof($tmpReg) > 0) {
                        foreach ($tmpReg as $regRow) {
                            foreach ($pairRegistries as $param) {
                                $tmpReg_result[$regRow->transaksi_id][$param] = blobDecode($regRow->$param);
                            }
                            // $tmpReg_result[$regRow->transaksi_id][$regRow->param] = blobDecode($regRow->values);
                        }
                    }
                }

                // }

                $tr->setFilters(array());
                $tr = new MdlTransaksiModul();
                $tr->setPrefix($this->prefixName);
                // $tr->setParam("id");
                // $tr->setInParam($arrTransTopID);
                $tr->addFilter("id in ('" . implode(",", $arrTransTopID) . "')");
                $tmpTrTop = $tr->lookUpMainTransaksi()->result();
                if (sizeof($tmpTrTop) > 0) {
                    foreach ($tmpTrTop as $topSpec) {
                        $arrSalesName[$topSpec->id_top] = $topSpec->oleh_nama;
                    }
                }

                if (sizeof($arrIdsHist) > 0) {
                    $tr->setFilters(array());
                    $tr = new MdlTransaksiModul();
                    $tr->setPrefix($this->prefixName);
                    // $tr->setParam("id");
                    // $tr->setInParam($arrTransHist);
                    $tr->addFilter("id in ('" . implode(",", $arrTransHist) . "')");
                    $tmpTransHist = $tr->lookUpMainTransaksi()->result();

                    //                    $tr = new MdlTransaksiModul();
                    //                    $tr->setFilters(array());
                    //                    $tr->addFilter("id in ('" . implode("','", $arrTransHist) . "')");
                    //                    $tmpTransHist = $tr->lookupAll()->result();

                    $tmpTransHist_result = array();
                    if (sizeof($tmpTransHist) > 0) {
                        foreach ($tmpTransHist as $histSpec) {
                            $tmpTransHist_result[$histSpec->id] = array(
                                "oleh_id" => $histSpec->oleh_id,
                                "oleh_nama" => $histSpec->oleh_nama,
                            );
                        }
                    }
                    //                    arrPrint($tmpTransHist_result);
                    //                    matiHEre();
                    if (sizeof($arrIdsHist) > 0) {
                        foreach ($arrIdsHist as $trID => $histSpec) {
                            foreach ($histSpec as $step => $detailSpec) {
                                if (array_key_exists($detailSpec['trID'], $tmpTransHist_result)) {
                                    $detailSpec['main'] = $tmpTransHist_result[$detailSpec['trID']];
                                }
                                $arrTransMainHist[$trID][$step] = $detailSpec;
                            }
                        }
                    }

                }

            }

            //arrPrint($tmpReg_result);
            //            matiHere();
            $numb = 0;
            foreach ($tmpHist as $ii => $row) {

                $this->placeId = $cabang_id = $row->cabang_id;
                // region ids_his
                $id_hist = blobDecode($row->ids_his);
                // endregion ids_his

                //region memangil global counter
                $tNomer_top = $row->nomer_top;
                //                $tr = new MdlTransaksiModul();
                //                $tr->addFilter("param='main'");
                //                $tmpReg = $tr->lookupRegistriesByNumber($tNomer_top)->result();
                //
                //                $arrSalesName = "";
                //                foreach ($tmpReg as $tmpRowReg) {
                //                    $arrSalesName = $tmpRowReg->oleh_nama;
                //                }
                $salesName = isset($arrSalesName[$row->id_top]) ? $arrSalesName[$row->id_top] : "-";


                $tNomer = $row->nomer;
                $jenisTrtop = explode(".", $tNomer_top)[0];
                $jenisTrsub = explode(".", $tNomer)[0];
                $counterjenis = "$jenisTrsub|" . $this->placeId;
                // $counterjenis = my_cabang_id()=="-1"? "$jenisTrsub":"$jenisTrsub|" . $this->placeId;

                // matiHEre($jenisTrsub);
                $counterIds_his = blobDecode(blobDecode($row->ids_his)[1]['counters']);
                $counters = blobDecode($row->counters);
                // arrPrint($counters);
                // matiHere();

                $counterGlobal = $counters['stepCode|placeID'][$counterjenis];


                $counterIds_his_global = isset($counterIds_his['stepCode|placeID']["$jenisTrtop|$cabang_id"]) ? $counterIds_his['stepCode|placeID']["$jenisTrtop|$cabang_id"] : "";
                $cGlobals = digit_5($counterGlobal);
                $cGlobal_spo = digit_5($counterIds_his_global);

                //endregion

                if (sizeof($pairRegistries) > 0) {
                    if ((sizeof($tmpReg_result) > 0) && (isset($tmpReg_result[$row->id]))) {
                        foreach ($tmpReg_result[$row->id] as $param => $eReg) {
                            //                                                        cekLime($param);
                            if ($param == "main") {
                                foreach ($eReg as $k => $v) {
                                    if (!isset($row->$k)) {
                                        $row->$k = $v;
                                    }
                                }
                            }
                            else {
                                if (sizeof($extHistoryFields2) > 0) {
                                    foreach ($extHistoryFields2 as $k1 => $v1) {
                                        if (is_array($v1)) {
                                            $kolom = $v1['kolom'];
                                            $format = $v1['format'];
                                            //                                                cekHitam(":: $kolom :: $format ::");
                                            if (!isset($row->$k1)) {
                                                $tmpDetail = "";
                                                foreach ($eReg as $eeReg) {
                                                    $valDetail = formatField_he_format($format, $eeReg[$kolom]);
                                                    $tmpDetail .= "<span>$valDetail</span><br>";
                                                }
                                                $row->$k1 = $tmpDetail;
                                            }
                                        }
                                        else {

                                            if (!isset($row->$k1)) {
                                                $tmpDetail = "";
                                                foreach ($eReg as $eeReg) {
                                                    $valDetail = formatField_he_format("nomer", $eeReg[$v1]);
                                                    $tmpDetail .= "<span>$valDetail</span><br>";
                                                    //                                                arrPrint($eeReg);
                                                }
                                                $row->$k1 = $tmpDetail;
                                            }
                                        }
                                        //                                        else{
                                        //                                            mati_disini("sudah ada $k1");
                                        //                                        }
                                        //
                                    }
                                }
                            }
                        }
                    }
                    if (sizeof($pairTransaksi) > 0) {
                        if ($row->referenceID > 0) {
                            $trPair = new MdlTransaksiModul();
                            $trPair->setPrefix($this->prefixName);
                            $trPair->addFilter("id='" . $row->referenceID . "'");
                            $trPairTmp = $trPair->lookupMainTransaksi()->result();
                            if (sizeof($trPairTmp) > 0) {
                                $hisTr = isset($trPairTmp[0]->ids_his) ? blobDecode($trPairTmp[0]->ids_his) : array();
                                foreach ($hisTr as $step => $hisTrSpec) {
                                    foreach ($pairTransaksi['kolom'] as $keyPair => $labelPair) {
                                        $keyPairs = $keyPair . "_" . $step;
                                        $row->$keyPairs = isset($hisTrSpec[$labelPair]) ? $hisTrSpec[$labelPair] : "--";
                                    }
                                }
                            }
                        }
                    }
                }

                //arrPrintWebs($row);
                if (sizeof($historyFieldsExt) > 0) {
                    foreach ($historyFieldsExt as $alias => $colom) {
                        $row->$alias = $row->$colom;
                    }
                }
                $tmp = array();
                $tmp1 = array();
                //arrPrint($row);
                //                 break;
                $numb++;
                foreach ($historyFields as $fName => $fLabel) {
                    //                    cekHitam($fName);
                    if (strpos($fName, '+') !== false) {//==mengandung penggabungan (+)
                        $chars = explode("+", $fName);
                        $colValue = "";
                        foreach ($chars as $key) {
                            if (is_numeric($row->$key)) {
                                if (!isset($sumValue[$key])) {
                                    $sumValue[$key] = 0;
                                }
                                $sumValue[$key] += $row->$key;
                            }
                            $colValue .= isset($row->$key) ? formatField_he_format($key, $row->$key) . "<br>" : "";
                        }
                        $colValue = rtrim($colValue, "<br>");
                    }
                    else {

                        if (is_numeric(isset($row->$fName) ? $row->$fName : "")) {
                            if (!isset($sumValue[$fName])) {
                                $sumValue[$fName] = 0;
                            }
                            $sumValue[$fName] += $row->$fName;
                        }

                        //region nomer dengan global counter
                        if ($fName == "nomer") {
                            // switch ($jenisTr) {
                            switch ($jenisTrsub) {
                                case "582s":
                                    $kolomValues = $row->$fName . "&#x2011;$cGlobals";
                                    break;
                                // case "582spd":
                                default:
                                    $kolomValue_0s = formatField_he_format($fName, $row->$fName);
                                    $kolomValues = str_replace("</span>", "&#x2011;" . $cGlobals, $kolomValue_0s);
                                    break;
                            }
                        }
                        elseif ($fName == "nomer_top") {
                            $kolomValue_0s = formatField_he_format($fName, $row->$fName);
                            $kolomValues = str_replace("</span>", "&#x2011;" . $cGlobal_spo, $kolomValue_0s);
                        }
                        else {
                            //                            cekHitam(";; $fName");
                            $kolomValues = isset($row->$fName) ? formatField_he_format($fName, $row->$fName) : "-";
                        }
                        //endregion

                        // $colValue = isset($row->$fName) ? formatField($fName, $row->$fName) : "";

                        $colValue = isset($row->$fName) ? $kolomValues : "";
                        //                        cekLime("$colValue"." ".$fName);

                    }

                    //                    if ($fName == "ids_his") {
                    if (is_array($fLabel)) {

                        $hisStep = $fLabel['step'];
                        $hisKey = $fLabel['key'];
                        //                        $tNomer = $id_hist[$hisStep][$hisKey];


                        if ($hisKey == "nomer") {
                            $colValue = isset($row->ids_his) ? showHistoriGlobalNumbers($row->ids_his, $hisStep, true, $this->jenisTr) : "";
                        }
                        else {
                            if (isset($fLabel['transaksi_jenis2'][$row->transaksi_jenis2])) {
                                $getKey = $fLabel['transaksi_jenis2'][$row->transaksi_jenis2];
                                //                                cekHere(":: $getKey ::");
                                $colValue = isset($row->$getKey) ? formatField_he_format($getKey, $row->$getKey) : "";
                            }
                            else {

                                $colValue = "-";
                            }
                        }


                        //                            $tr = new MdlTransaksiModul();
                        //                            $tr->setFilters(array());
                        //                            $tr->addFilter("param='main'");
                        //                            $tmpReg = $tr->lookupRegistriesByNumber($tNomer)->result();
                        ////                            cekHere($this->db->last_query());
                        //
                        //                            $logistic = $tmpReg[0]->oleh_nama;
                        $logistic = "";
                        if (isset($arrTransMainHist[$row->id][$hisStep]['main'])) {
                            $main = $arrTransMainHist[$row->id][$hisStep]['main'];
                            $logistic = $main['oleh_nama'];
                        }
                    }
                    //                    }

                    if ($fName == "no") {
                        $colValue = formatField_he_format($fName, $numb);
                    }

                    //                    cekHere($logistic);
                    $tmp['logistic'] = isset($logistic) && $logistic != null ? $logistic : 'undefined';
                    $tmp['sales_name'] = $salesName;
                    $tmp[$fName] = $colValue;
                    $tmp1["id"] = $row->id;
                }


                if (sizeof($arrCurrency) > 0) {
                    $valas = "";
                    $valas .= "<div class='btn-group'>";
                    $valas .= "<button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>";
                    $valas .= "<i class='fa fa-print'></i>";
                    $valas .= "<span class='caret'></span>";
                    $valas .= "</button>";

                    $valas .= "<ul style='background:#cde8ff;' class='dropdown-menu dropdown-menu-right'>";
                    foreach ($arrCurrency as $arrV) {
                        $nama = $arrV->nama;
                        $nomer = $row->nomer;
                        $nilai = number_format($arrV->exchange, 0);
                        // $valas .= " <li class='text-bold'><a class='dropdown-item' href='javascript:void(0);' onclick=\"top.popBig('" . MODUL_PATH . "Printing/viewReceipt/$nomer?type=" . blobEncode($nama) . "&f=" . blobEncode($arrV->exchange) . "')\"> <i class='fa fa-print'></i> in $nama - ($nilai) </a></li>";
                        $valas .= " <li class='text-bold'><a class='dropdown-item' href='javascript:void(0);' onclick=\"top.popBig('" . MODUL_PATH . "Printing/viewReceipt/" . $this->jenisTr . "/$nomer?type=" . blobEncode($nama) . "&f=" . blobEncode($arrV->exchange) . "')\"> <i class='fa fa-print'></i> in $nama - ($nilai) </a></li>";
                    }

                    // $valas .= " <li><a class='btn btn-xs btn-warning' href='javascript:void(0);' onclick=\"top.location.href='" . base_url() . "data/view/Currency'\"> <i class='fa fa-plus'></i> tambah currency </a></li>";
                    $valas .= "</ul>";
                    $valas .= "</div>";
                    $tmp["print_nvalas"] = $valas;
                }

                $tmp['next_pic'] = "-";

                // menambah background-color karena dicancel/reject/undo, atau yang dibatalkan
                //                $tmp['keterangan'] = "-";
                $addKeterangan = "";
                if (isset($row->trash_4) && ($row->trash_4 == 1)) {
                    //                    $tmp['keterangan'] = "CANCELED";
                    $addKeterangan = "CANCELED";
                    //                    $addKeterangan .= $row->cancel_dtime != NULL ? "&nl2br; date: " . $row->cancel_dtime : "";
                    //                    $addKeterangan .= $row->cancel_name != NULL ? "&nl2br; by: " . $row->cancel_name : "";
                    $addKeterangan .= $row->cancel_dtime != NULL ? nl2br(" date: " . $row->cancel_dtime) : "";
                    $addKeterangan .= $row->cancel_name != NULL ? nl2br(" by: " . $row->cancel_name) : "";
                    $arrayHistory_canceled[$ii] = array(
                        "bgcolor" => "background-color:red;color:#cccccc;",
                    );
                }
                // menambah background-color karena diedit...
                if (sizeof($extHistoryKeterangan) > 0) {
                    foreach ($extHistoryKeterangan as $mode => $modeSpec) {
                        $mode_result = "";
                        if (sizeof($modeSpec) > 0) {
                            if (isset($row->$modeSpec['kolom']) && ($row->$modeSpec['kolom'] == $modeSpec['value'])) {
                                $addBr = $addKeterangan != NULL ? "<hr>" : "";
                                $l_result = "";
                                if (is_array($modeSpec['labels'])) {
                                    foreach ($modeSpec['labels'] as $l) {
                                        if ($l_result == "") {
                                            $l_result = "$addBr $mode by: " . formatField_he_format($l, $row->$l);
                                        }
                                        else {
                                            $l_result .= ", " . formatField_he_format($l, $row->$l);
                                        }
                                    }
                                }
                                else {
                                    $l_result = $modeSpec['labels'];
                                }
                                $mode_result .= $l_result;

                                //----------------------------------------
                                if (isset($modeSpec['style'])) {
                                    $color = $modeSpec['style']['color'];
                                    $bgcolor = $modeSpec['style']['bgcolor'];
                                    $arrayHistory_keterangan[$ii] = array(
                                        "bgcolor" => "background-color:$bgcolor;color:$color;",
                                    );
                                }
                            }

                        }
                        $addKeterangan .= $mode_result;


                    }
                }


                $tmp['keterangan'] = $addKeterangan;
                $arrayHistory[$ii] = $tmp;
                $arrayHistory_ids[$ii] = $tmp1;


            }
        }
        //endregion


        //region link to add new transaction
        if (placeCanMakeTrans_he_menu($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr, $this->configUiJenis)) {
            //        if (in_array($this->configUi[$jenisTr]["steps"][1]['userGroup'], $this->session->login['membership'])) {
            $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
            if (array_key_exists($this->jenisTr, $createIndexes)) {
                $targetUrl = MODUL_PATH . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
            }
            else {
                $targetUrl = MODUL_PATH . "Create/index/" . $this->jenisTr;
            }
            $addLink = array(
                "link" => $targetUrl,
                "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->configUi[$jenisTr]["steps"][1]['label'],
            );
        }
        else {
            $addLink = null;
        }
        //endregion

        $pakai_ini = 1;
        if ($pakai_ini == 1) {

            //region tambahan joint fase
            if ($jointtab) {
//            arrPrint($this->uri->segment_array());
                $masterjointJenis = "7778";
                $lastKey = sizeof($steps);
                $nextStepCodeFase = array();
                $this->load->model("Mdls/MdlProdukFase");
                $tr = new MdlTransaksiModul();
                $pf = new MdlProdukFase();
                $tempFase = $pf->conectedProduct();
//                showLast_query("biru");
                if (isset($wip) && count($wip) > 0) {
                    cekHitam($wip);
                    foreach ($wip as $pid => $dStep) {
                        if (isset($tempFase[$pid])) {
                            foreach ($tempFase[$pid] as $oldstep => $faseData) {
                                $lastKey++;
                                $nextStepCodeFase[$lastKey] = $faseData["next_kode_transaksi"];
                                $steps[$lastKey] = array(
                                    "origStep" => $oldstep,
                                    "label" => $faseData["nama"],
                                    "actionLabel" => $faseData["nama"],
                                    "source" => $faseData["kode_transaksi"],
                                    "target" => $faseData["next_kode_transaksi"],
                                    "userGroup" => "p_produksi",
                                    "stateLabel" => $faseData["nama"],
                                    "stateColor" => "#dd3300",
                                    "stateCaption" => $faseData["nama"] . " by ",
                                    "allowEdit" => 1,
                                    "allowIncrement" => "",
                                );
//                            $fase_id_key = $faseData["urut"] +1;
                                $fase_id_key = $faseData["urut"];
                                $stepLabels[$fase_id_key] = $faseData["nama"];
                                $stepLinks[$fase_id_key] = MODUL_PATH . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $masterjointJenis . "/" . $this->uri->segment(5) . "/" . $faseData["next_kode_transaksi"] . "?date1=$date1&date2=$date2&srcWip=" . $_GET["srcWip"];
//                        $stepLinks[$fase_id_key] = MODUL_PATH . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $masterjointJenis . "/" . $this->uri->segment(5) . "/" . "?step=$fase_id_key&date1=$date1&date2=$date2";
                            }
                        }
                    }
                }
                else {
                    cekBiru(__LINE__);
                    $nextStepCodeFase = array($jenisTr);
                }

// arrPrint($steps);
// arrPrint($_GET);
// arrPrint($this->uri->segment_array());
//             matiHEre();
                $this->load->model("MdlTransaksiModul");
                $tr = new MdlTransaksiModul();
                $tr->setPrefix($this->prefixName);
//            $stepCodes = array();
//            $jmlStep = count($steps);
//            $tr->addFilter("bom_id='$pid'");
//            $tr->addFilter("div_id='" . $this->session->login['div_id'] . "'");
//            $tr->addFilter("jenis_top in ('" . implode("','", $nextStepCodeFase) . "')");
//            $tr->addFilter("next_substep_code<>''");
//            $tr->addFilter("sub_step_number>0");
//            $tr->addFilter("valid_qty>0");
//            $sesionReplacer = array();
//            $tmpHist = $tr->lookupRecentUndoneEntries_joined($sesionReplacer)->result();
//cekHere(":::: $currentState");
                if (isset($_GET['stID'])) {
                    $currentState = $_GET['stID'];
                }
                else {
                    $currentState = strlen($this->uri->segment(6)) > 0 ? $this->uri->segment(6) : $this->configUi[$jenisTr]['steps'][1]['target'];
                }
                $searchStr = isset($_GET['search']) ? $_GET['search'] : "";
                if (my_cabang_id() == "-1") {
                    $filters = array(
                        // "cabang_id" => $this->placeId,
                        "jenis_master" => $masterjointJenis,
                        "link_id" => "0",
                        "div_id" => $this->session->login['div_id'],
                    );
                }
                else {
                    if ($this->session->login['employee_type'] == "employee_freelance") {
                        $filters = array(
                            "cabang_id" => $this->placeId,
                            "jenis_master" => $masterjointJenis,
                            "link_id" => "0",
                            "div_id" => $this->session->login['div_id'],
                            "seller_id" => $this->session->login['id']
                        );
                    }
                    else {
                        $filters = array(
                            "cabang_id" => $this->placeId,
                            "jenis_master" => $masterjointJenis,
                            "link_id" => "0",
                            "div_id" => $this->session->login['div_id'],
                        );
                    }
                }
                if (sizeof($filters) > 0) {
                    foreach ($filters as $key_f => $val_f) {
                        $tr->addFilter("$key_f='" . $val_f . "'");
                    }
                }
                if (isset($currentState)) {
//                $tr->addFilter("jenis='" . $currentState . "'");
                    $tr->addFilter("jenis in ('" . implode("','", $nextStepCodeFase) . "')");
                }
                $tr->addFilter("bom_id='$bomID'");
                $addParams = array();
                if (isset($_GET['addParams'])) {
                    $addParams = unserialize(base64_decode($_GET['addParams']));
                }
                if ($addParams != null && sizeof($addParams) > 0) {
                    foreach ($addParams as $f) {
                        $tr->addFilter($f);
                    }
                }
                if (isset($_GET['search'])) {
                    $tr->setKeyWord($searchStr);
                }
                else {
//            if(isset($_GET['date1']) && isset($_GET['date2'])){
//                $this->db->where("fulldate>='" . $date1 . "'");
//                $this->db->where("fulldate<='" . $date2 . "'");
//            }
                }
                $jmlData = $tr->lookupDataCount();
                $page = (isset($_GET['page']) && $_GET['page'] > 0) ? ($_GET['page']) : 1;
                $offset = ($limit * ($page - 1));
                $addParams = array();
                if (isset($_GET['addParams'])) {
                    $addParams = unserialize(base64_decode($_GET['addParams']));
                }
                if ($addParams != null && sizeof($addParams) > 0) {
                    foreach ($addParams as $f) {
                        $tr->addFilter($f);
                    }
                }
                $action = array(
                    "viewDetails" => base_url() . get_class($this) . "/viewDetails",
                );
                if (isset($_GET['search'])) {
                    $tr->setKeyWord($searchStr);
                }
                else {
//            if(isset($_GET['date1']) && isset($_GET['date2'])){
//                $this->db->where("fulldate>='" . $date1 . "'");
//                $this->db->where("fulldate<='" . $date2 . "'");
//            }
                }
                $tr->addFilter("bom_id='$bomID'");
                $tmpHist = $tr->lookupHistories($jmlData, $limit, $page)->result();
//                showLast_query("biru");
//                cekKuning(sizeof($tmpHist));
                if (sizeof($tmpHist) > 0) {
                    $arrTransID = array();
                    $arrTransTopID = array();
                    $arrIdsHist = array();
                    $arrTransHist = array();
                    $arrNextAction = array();
                    foreach ($tmpHist as $row) {
                        $arrTransID[] = $row->transaksi_id;
                        $arrTransTopID[] = $row->id_top;
                        $arrNextAction[$row->transaksi_id] = array(
                            "next_step_num" => $row->next_substep_num,
                            "next_step_code" => $row->next_substep_code,
                        );
                        if ($row->ids_his != "") {
                            $hist = blobDecode($row->ids_his);
                            foreach ($hist as $hisSpec) {
                                $arrIdsHist[$row->id][$hisSpec['step']] = array(
                                    "step" => $hisSpec['step'],
                                    "trID" => $hisSpec['trID'],
                                    "nomer" => $hisSpec['nomer'],
                                );
                                $arrTransHist[] = $hisSpec['trID'];
                            }
                        }
                        //                    arrPrint($row);
                    }

                    $tmpReg_result = array();
                    if (sizeof($pairRegistries) > 0) {
                        $selectKolom = implode(",", $pairRegistries) . ", transaksi_id";
                        $trReg = new MdlTransaksiModul();
                        $trReg->setPrefix($this->prefixName);
                        $trReg->setFilters(array());
                        //            $trReg->addFilter("param='main'");
                        $trReg->setJointSelectFields($selectKolom);
                        $trReg->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
                        $tmpReg = $trReg->lookupDataRegistries()->result();
                        if (sizeof($tmpReg) > 0) {
                            foreach ($tmpReg as $regRow) {
                                //                    arrPrintWebs($regRow);
                                foreach ($regRow as $key_reg => $val_reg) {
                                    if ($key_reg != "transaksi_id") {
                                        $tmpReg_result[$regRow->transaksi_id][$key_reg] = blobDecode($val_reg);
                                    }
                                }

                            }
                        }
                    }


                    $arrNextPIC = callNextPIC($arrNextAction);

                    if (sizeof($arrIdsHist) > 0) {
                        $tr = new MdlTransaksiModul();
                        $tr->setPrefix($this->prefixName);
                        $tr->setFilters(array());
                        $tr->addFilter("id in ('" . implode("','", $arrTransHist) . "')");
                        $tmpTransHist = $tr->lookupAll()->result();


                        if (sizeof($tmpTransHist) > 0) {
                            foreach ($tmpTransHist as $histSpec) {
                                $tmpTransHist_result[$histSpec->id] = array(
                                    "oleh_id" => $histSpec->oleh_id,
                                    "oleh_nama" => $histSpec->oleh_nama,
                                );
                            }
                        }

                        foreach ($arrIdsHist as $trID => $histSpec) {
                            foreach ($histSpec as $step => $detailSpec) {
                                if (array_key_exists($detailSpec['trID'], $tmpTransHist_result)) {
                                    $detailSpec['main'] = $tmpTransHist_result[$detailSpec['trID']];
                                }
                                $arrTransMainHist[$trID][$step] = $detailSpec;
                            }
                        }
                    }
//cekUngu("jumlah data: " . sizeof($tmpHist));
                    $numb = 0;
                    foreach ($tmpHist as $row) {
//                    arrPrint($row);
                        $extHistoryFields2 = isset($arrExtHistoryFields2[$row->step_number]) ? $arrExtHistoryFields2[$row->step_number] : array();

                        if (sizeof($pairRegistries) > 0) {
                            if ((sizeof($tmpReg_result) > 0) && (isset($tmpReg_result[$row->transaksi_id]))) {
                                foreach ($tmpReg_result[$row->transaksi_id] as $param => $eReg) {
                                    switch ($param) {
                                        case "main":
                                            foreach ($eReg as $k => $v) {
                                                if (!isset($row->$k)) {
                                                    $row->$k = $v;
                                                }
                                            }
                                            break;
                                        case "items":
                                            if (sizeof($extHistoryFields2) > 0) {
                                                foreach ($extHistoryFields2 as $k1 => $v1) {
                                                    if (is_array($v1)) {
                                                        $kolom = $v1['kolom'];
                                                        $format = $v1['format'];
                                                        //                                                cekHitam(":: $kolom :: $format ::");
                                                        if (!isset($row->$k1)) {
                                                            $tmpDetail = "";
                                                            foreach ($eReg as $eeReg) {
                                                                $valDetail = formatField_he_format($format, $eeReg[$kolom]);
                                                                $tmpDetail .= "<span>$valDetail</span><br>";
                                                            }
                                                            $row->$k1 = $tmpDetail;
                                                        }
                                                    }
                                                    else {
                                                        if (!isset($row->$k1)) {
                                                            $tmpDetail = "";
                                                            foreach ($eReg as $eeReg) {
                                                                $valDetail = formatField_he_format("nomer", $eeReg[$v1]);
                                                                $tmpDetail .= "<span>$valDetail</span><br>";
                                                            }
                                                            $row->$k1 = $tmpDetail;
                                                        }
                                                    }
                                                }
                                            }
                                            break;
                                    }
                                }
                            }
                        }
                        //arrPrint($row);
                        $numb++;

                        $tmp = array();
                        foreach ($historyFields as $fName => $fLabel) {
                            if (isset($row->$fName)) {
                                if (is_numeric($row->$fName)) {
                                    if (!isset($sumFooter[$fName])) {
                                        $sumFooter[$fName] = 0;
                                    }
                                    $sumFooter[$fName] += $row->$fName;
                                }
                            }

                            if (is_array($fLabel)) {
                                $hisStep = $fLabel['step'];
                                $hisKey = $fLabel['key'];

                                if (isset($row->ids_his)) {
                                    if ($hisKey == "nomer") {
                                        $returnVal = showHistoriGlobalNumbers($row->ids_his, $hisStep, true, $this->jenisTr);
                                        if ($returnVal == "") {
                                            $tmp[$fName] = "-";
                                        }
                                        else {
                                            $tmp[$fName] = $returnVal;
                                        }
                                    }
                                    else {
                                        $ids_his_decode = blobDecode($row->ids_his);
                                        if (isset($ids_his_decode[$hisStep][$hisKey])) {
                                            $tmp[$fName] = $ids_his_decode[$hisStep][$hisKey];
                                        }
                                        else {
                                            $tmp[$fName] = "-";
                                        }
                                    }
                                }
                                else {
                                    $tmp[$fName] = "-";
                                }
                            }
                            else {
                                $tmp[$fName] = isset($row->$fName) ? formatField_he_format($fName, $row->$fName) : formatField_he_format($fName, 0);
                            }

                            if ($fName == "no") {
                                $tmp[$fName] = formatField_he_format($fName, $numb);
                            }

                        }

                        if (sizeof($row->cancel_packing_source_id) > 0) {
//                            arrPrint($row->cancel_packing_source_id);
                            $trx = new MdlTransaksiModul();
                            $trx->setPrefix($this->prefixName);
                            $trx->addFilter("id='" . $row->cancel_packing_source_id . "'");
                            $tmpTrx = $trx->lookupAll()->result();

                            //                        $tmp['nomer_top'] = $tmpTrx[0]->nomer;
                            $tmp['nomer_top'] = formatField_he_format("nomer_top", $tmpTrx[0]->nomer);
                            //                        arrPrint($tmpTrx[0]->nomer);
                        }


                        if ($row->sub_step_number > 0) {
                            $tmp['state'] = "<span style='color:" . $this->configUi[$jenisTr]['steps'][$row->sub_step_number]['stateColor'] . "'>" . $this->configUi[$jenisTr]['steps'][$row->sub_step_number]['stateLabel'] . "</span>";
                            $tmp['state'] .= "<br>" . createStateSign($row->sub_step_number, $row->step_avail, $jenisTr);
                        }
                        else {
                            $tmp['state'] = "<span style='color:#777777'>canceled</span>";
                        }

                        $nextStepNum = ($row->next_substep_num);
                        $currentStepNum = ($row->sub_step_number);
                        $currentStepCode = ($row->jenis);
                        $nextStepCode = ($row->next_step_code);
                        $currentFaseNum = ($row->fase_id + 1);

                        $tmp['next_pic'] = "-";
                        // cekMErah(in_array($steps[$row->step_current]));

//                    foreach ($steps as $prevStep => $prevDataStep) {
////                         arrPrint($prevDataStep);
//                        if (isset($prevDataStep["origStep"]) && $prevDataStep["origStep"] == $row->step_current) {
////                            $tmp['curentStep'] = $prevStep;
//                            $tmp['curentStep'] = $prevStep;
//                            $currentFaseNum = $prevStep;
////                            cekMerah("$prevStep :: " . $row->step_current);
//                        }
//                    }

                        // matiHEre();
//                    cekMerah(":: $currentFaseNum :: " . $row->step_current);
                        // $tmp['curentStep'] = $row->step_current;
                        if (sizeof($arrNextPIC) > 0) {
                            if (isset($arrNextPIC[$row->next_substep_code][$row->next_substep_num])) {
                                $next_pic = "";
                                $nob = 1;
                                foreach ($arrNextPIC[$row->next_substep_code][$row->next_substep_num] as $spec) {
                                    if ($row->cabang_id == $spec['cabang_id']) {

                                        if ($next_pic == "") {
                                            $next_pic = "$nob. " . $spec['nama'];
                                        }
                                        else {
                                            $nob++;
                                            $next_pic = $next_pic . "<br>" . "$nob. " . $spec['nama'];
                                        }

                                    }

                                }
                                $tmp['next_pic'] = $next_pic;
                                //                            cekHitam($next_pic);
                            }
                        }


                        $tmp['action'] = "";

//                    $nextStepNum = ($row->next_substep_num);
//                    $currentStepNum = ($row->sub_step_number);
//                    $currentStepCode = ($row->jenis);
//                    $nextStepCode = ($row->next_step_code);
//                    // $currentFaseNum = ($row->fase_id);

                        if (isset($row->sub_step_number) && ($row->sub_step_number > 0)) {
                            //                        cekHere("koq");
                            $allowFollowup = true;
                            $actionLabel = "review " . $this->configUi[$jenisTr]['steps'][$currentStepNum]['label'];
                        }

                        if (isset($this->configUi[$jenisTr]['steps'][$nextStepNum])) {
                            if (isset($this->accessList[$jenisTr])) {
                                if (isset($this->accessList[$jenisTr][$nextStepNum][$nextStepCode]["allowFollowUp"])) {
                                    $allowFollowup = $this->accessList[$jenisTr][$nextStepNum][$nextStepCode]["allowFollowUp"];
                                    $actionLabel = $this->configUi[$jenisTr]['steps'][$nextStepNum]['actionLabel'];
                                }


                            }
                            else {
                                if (in_array($this->configUi[$jenisTr]['steps'][$nextStepNum]['userGroup'], $this->session->login['membership'])) {
                                    $allowFollowup = true;
                                    $actionLabel = $this->configUi[$jenisTr]['steps'][$nextStepNum]['actionLabel'];
                                }
                                else {
                                    $allowFollowup = true;
                                    $actionLabel = "review " . $this->configUi[$jenisTr]['steps'][$currentStepNum]['label'];
                                }
                            }
                        }
                        $req_cancel_qty = (isset($row->req_cancel_qty) && ($row->req_cancel_qty != '')) ? $row->req_cancel_qty : 0;
                        $valid_qty = (isset($row->valid_qty) && ($row->valid_qty != '')) ? $row->valid_qty : 0;


                        if (isset($allowFollowup) && ($allowFollowup)) {
                            $allowJoin = isset($this->configUi[$jenisTr]["steps"][$nextStepNum]['allowJoin']) && $this->configUi[$jenisTr]["steps"][$nextStepNum]['allowJoin'] == true ? $this->configUi[$jenisTr]["steps"][$nextStepNum]['allowJoin'] : false;
                            $stepLabel = isset($this->configUi[$jenisTr]['steps'][$nextStepNum]['label']) ? $this->configUi[$jenisTr]['steps'][$nextStepNum]['label'] : "";
                            $isCancelPacking = isset($this->configUi[$jenisTr]['steps'][$nextStepNum]['isCancelPacking']) ? $this->configUi[$jenisTr]['steps'][$nextStepNum]['isCancelPacking'] : false;
                            $allowCancel = isset($this->configUi[$jenisTr]['steps'][$nextStepNum]['allowCancel']) ? $this->configUi[$jenisTr]['steps'][$nextStepNum]['allowCancel'] : false;

                            $targetFollowupLink = $isCancelPacking == true ? "followupCancelPackingPrePreview" : "followupPrePreview";

                            $followupLink = "top.$('#result').load('" . MODUL_PATH . "FollowUp/$targetFollowupLink/$jenisTr/" . $row->transaksi_id . "/$nextStepNum/" . $row->sub_step_number . "');";

                            $tmp['action'] = "<div class='input-group'>";
                            $tmp['action'] .= "<a class='btn btn-primary btn-block' title='turn this entry into $stepLabel' href='JavaScript:void(0)' 
                                onClick =\"top.open_holdon();$followupLink\">" . $actionLabel . "</a>";
                            //                        if ($allowJoin) {
                            //                            $tmp['action'] .= "<span class='input-group-addon'>";
                            //                            $tmp['action'] .= "<a title='process many items at once' href='" . base_url() . "Transaksi/viewIncomplete/" . $this->jenisTr . "/$currentStepNum'><span class='fa fa-dedent'></span></a>";
                            //                            $tmp['action'] .= "</span class='input-group-addon'>";
                            //                        }
                            $tmp['action'] .= "</div class='input-group'>";

                            if ($req_cancel_qty > 0 && $valid_qty == 0) {
                                $tmp['action'] = "<div class='btn-group' role='group' aria-label='cancel packing on progress'>";
                                $tmp['action'] .= "<button type='button' disabled class='btn btn-warning' title='sedang dalam process cancel packing' href='JavaScript:void(0)'>menuggu approve cancel</button>";
                                //                            $tmp['action'] .= "<button type='button' class='btn btn-warning' title='sedang dalam process cancel packing' href='JavaScript:void(0)'>x</button>";
                                $tmp['action'] .= "</div>";
                            }

                        }
                        else {

                        }

                        $tmpMark = array();
                        $tmp['keterangan'] = "-";
                        if ($row->partial == 1) {
                            $tmpMark['style'] = "background-color:yellow;";
                            $tmp['keterangan'] = "<span style='color:red;'>transaksi diproses sebagian</span>";
                        }


                        $arrayOnprogress[] = $tmp;
                        //                $arrayOnprogressGroup[$currentStepNum][] = $tmp;
                        // $arrayOnprogressGroup[$currentFaseNum][] = $tmp;
                        $arrayOnprogressGroup[$currentFaseNum][] = $tmp;

                        $arrayOnprogressPartialMark[] = $tmpMark;
                        //                $arrayOnprogressGroupPartialMark[$currentStepNum][] = $tmpMark;
                        $arrayOnprogressGroupPartialMark[$currentFaseNum][] = $tmpMark;

                        //                    break;
                    }
                }

            }

            //endregion
        }


        //region prepare params for viewer
        $data = array(
            "mode" => "showData",
            //            "mode" => "viewHistory",
            "isMobile" => $isMob,
            "jenisTr" => $jenisTr,
            "trName" => $this->configUi[$jenisTr]["label"],
            "errMsg" => $this->session->errMsg,
            "title" => isset($subCodes) && isset($currentState) ? $subCodes[$currentState] : $this->jenisTrName,
            "subTitle" => "histories",
            "customButton" => $customButton,
            "customButtonTarget" => isset($currentState) ? "$currentState?date1=$date1&date2=$date2" : "",
            "arrayHistoryLabels" => $historyFields,
            "arrayHistory" => $arrayHistory,
            "arrayHistorySumField" => $sumValue,
            "arrayHistoryId" => $arrayHistory_ids,
            "action" => $action,
            "steps" => $steps,
            "stepLabels" => $stepLabels,
            "stepLinks" => $stepLinks,
            "addParams" => isset($_GET['addParams']) ? $_GET['addParams'] : null,
            "currentState" => isset($currentState) ? $currentState : "all states",
            //            "alternateLink" => base_url() . $this->uri->segment(1) . "/viewIncomplete/" . $this->uri->segment(3),
            //            "alternateLinkCaption" => "incomplete " . $this->configUi[$jenisTr]["label"] . " <span class='glyphicon glyphicon-arrow-right'></span>",
            "alternateLink" => "",
            "alternateLinkCaption" => "",
            "addLink" => $addLink,
            "filters" => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "thisPage" => MODUL_PATH . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/" . $this->uri->segment(6),

            "history_canceled" => isset($arrayHistory_canceled) ? $arrayHistory_canceled : array(),
            "history_keterangan" => isset($arrayHistory_keterangan) ? $arrayHistory_keterangan : array(),
            "srcWip" => $origWip,
        );
        //endregion

        $endtime = microtime(true); // Bottom of page
        $valTimeEnd = $endtime - $starttime;


        $this->load->view("history", $data);
    }

    //--------------------------------------
    public function showDataWip()
    {
        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
        $starttime = microtime(true);
        $limit = 20;
        $maxPageNum = 20;
        $jenisTr = $this->jenisTr;
        $jenisTrsub = $this->uri->segment(6);
        $bomID = $this->uri->segment(5);
        $cCode = $this->cCode;
        $allStep = $this->configUi[$jenisTr]['steps'];
        $availSteps = array();
        foreach ($allStep as $step => $tempStep) {
            $availSteps[$tempStep['target']] = $step;
        }


        // region bom
        $this->load->model("Mdls/MdlProdukRakitan");
        $this->load->model("Mdls/MdlProdukFase");
        $pr = New MdlProdukRakitan();
        $pf = New MdlProdukFase();

        $pr->addFilter("id=$bomID");
        $prTmp = $pr->lookupAll()->result();
        $prResult = array();
        if (sizeof($prTmp) > 0) {
            foreach ($prTmp as $prSpec) {
                $prResult[$prSpec->id] = $prSpec->nama;
            }
        }

        $pf->addFilter("produk_id=$bomID");
        $pfTmp = $pf->lookupAll()->result();
        $pfResult = array();
        $pfResultBom = array();
        $pfResultFase = array();
        if (sizeof($pfTmp) > 0) {
            foreach ($pfTmp as $pfSpec) {
                $pfResult[] = $pfSpec->kode_transaksi;
                $pfResultBom[$pfSpec->produk_id][$pfSpec->urut] = $pfSpec->kode_transaksi;
                $pfResultFase[$pfSpec->urut] = array(
                    "label" => $pfSpec->nama,
                    "source" => $pfSpec->kode_transaksi,
                    "target" => $pfSpec->next_kode_transaksi,
                    "actionLabel" => "simpan " . $pfSpec->nama,
                );
            }
        }
        // endregion bom


        $selectedSTep = isset($availSteps[$jenisTrsub]) ? $availSteps[$jenisTrsub] : 1;
        $historyFields = isset($this->configUi[$jenisTr]['historyFields'][$selectedSTep]) ? $this->configUi[$jenisTr]['historyFields'][$selectedSTep] : $this->configUi[$jenisTr]['shortHistoryFields'];
        $pairRegistries = isset($this->configUi[$jenisTr]['pairRegistries']) ? $this->configUi[$jenisTr]['pairRegistries'] : array();
        $historyFieldsExt = isset($this->configUi[$jenisTr]["extHistoryFields"][$selectedSTep]) ? $this->configUi[$jenisTr]["extHistoryFields"][$selectedSTep] : array();
        $extHistoryFields2 = isset($this->configUi[$jenisTr]["extHistoryFields2"][$selectedSTep]) ? $this->configUi[$jenisTr]["extHistoryFields2"][$selectedSTep] : array();
        $customButton = isset($this->configLayout[$jenisTr]["customButton"][$selectedSTep]) ? $this->configLayout[$jenisTr]["customButton"][$selectedSTep] : array();
        $printValas = isset($this->configLayout[$jenisTr]["print_nvalas"]) ? $this->configLayout[$jenisTr]["print_nvalas"] : array();
        $pairTransaksi = isset($this->configUi[$jenisTr]['pairTransaksi']) ? $this->configUi[$jenisTr]['pairTransaksi'] : array();
        $extHistoryKeterangan = isset($this->configUi[$jenisTr]['extHistoryKeterangan'][$selectedSTep]) ? $this->configUi[$jenisTr]['extHistoryKeterangan'][$selectedSTep] : array();
        $modul = isset($this->masterConfigUi[$jenisTr]["modul"]) ? $this->masterConfigUi[$jenisTr]["modul"] : false;
        $modul_path = base_url() . $modul . "/";
        //arrPrint($extHistoryKeterangan);

        $mb = New MobileDetect();
        $isMob = $mb->isMobile();
        if ($isMob) {
            $historyFields = isset($this->configUi[$jenisTr]['compactHistoryFields']) ? $this->configUi[$jenisTr]['compactHistoryFields'] : array();
        }

        $backdate_f = formatTanggal(backDate(30), 'Y-m-d');

        $date1 = isset($_GET['date1']) ? $_GET['date1'] : $backdate_f;
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");


        //region preparing ERP step labels for top link
        $steps = $this->configUi[$jenisTr]['steps'];
        $stepLabels = array();
        $stepLinks = array();
        if (sizeof($steps) > 1) {
            $subCodes = array();
            $stepCodes = array();
//            $jmlStep = count($steps);
            $jmlStep = count($pfResultFase);
//            arrprintKuning($steps);
//            foreach ($steps as $stepNumber => $stepSpec) {
            foreach ($pfResultFase as $stepNumber => $stepSpec) {
                if ($stepNumber <= $jmlStep) {
                    $subCodes[$stepSpec['target']] = $stepSpec['label'];
                    $stepCodes[] = $stepSpec['target'];
                    $stepLabels[$stepNumber] = $stepSpec['label'];
                    $stepLinks[$stepNumber] = MODUL_PATH . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/" . $stepSpec['target'] . "?date1=$date1&date2=$date2";
                }
            }
            if (isset($_GET['stID'])) {
                $currentState = $_GET['stID'];
            }
            else {
//                $currentState = strlen($this->uri->segment(6)) > 0 ? $this->uri->segment(6) : $this->configUi[$jenisTr]['steps'][1]['target'];
                $currentState = strlen($this->uri->segment(6)) > 0 ? $this->uri->segment(6) : $pfResultFase[1]['target'];
            }
        }
        //endregion


        //region lookup histories

        $this->load->model("MdlTransaksiModul");
        $tr = new MdlTransaksiModul();
        $tr->setPrefix($this->prefixName);

        $searchStr = isset($_GET['search']) ? $_GET['search'] : "";
        if (my_cabang_id() == "-1") {
            $filters = array(
                // "cabang_id" => $this->placeId,
                "jenis_master" => $this->jenisTr,
                "link_id" => "0",
                "div_id" => $this->session->login['div_id'],
            );
        }
        else {
            if ($this->session->login['employee_type'] == "employee_freelance") {
                $filters = array(
                    "cabang_id" => $this->placeId,
                    "jenis_master" => $this->jenisTr,
                    "link_id" => "0",
                    "div_id" => $this->session->login['div_id'],
                    "seller_id" => $this->session->login['id']
                );
            }
            else {
                $filters = array(
                    "cabang_id" => $this->placeId,
                    "jenis_master" => $this->jenisTr,
                    "link_id" => "0",
                    "div_id" => $this->session->login['div_id'],
                );
            }
        }
        if (sizeof($filters) > 0) {
            foreach ($filters as $key_f => $val_f) {
                $tr->addFilter("$key_f='" . $val_f . "'");
            }
        }
        if (isset($currentState)) {
            $tr->addFilter("jenis='" . $currentState . "'");
        }
        $tr->addFilter("bom_id='$bomID'");

        $addParams = array();
        if (isset($_GET['addParams'])) {
            $addParams = unserialize(base64_decode($_GET['addParams']));
        }
        if ($addParams != null && sizeof($addParams) > 0) {
            foreach ($addParams as $f) {
                $tr->addFilter($f);
            }
        }

        if (isset($_GET['search'])) {
            $tr->setKeyWord($searchStr);
        }
        else {
//            if(isset($_GET['date1']) && isset($_GET['date2'])){
//                $this->db->where("fulldate>='" . $date1 . "'");
//                $this->db->where("fulldate<='" . $date2 . "'");
//            }
        }

        $jmlData = $tr->lookupDataCount();
        $page = (isset($_GET['page']) && $_GET['page'] > 0) ? ($_GET['page']) : 1;
        $offset = ($limit * ($page - 1));

        $addParams = array();
        if (isset($_GET['addParams'])) {
            $addParams = unserialize(base64_decode($_GET['addParams']));
        }
        if ($addParams != null && sizeof($addParams) > 0) {
            foreach ($addParams as $f) {
                $tr->addFilter($f);
            }
        }

        $action = array(
            "viewDetails" => base_url() . get_class($this) . "/viewDetails",
        );

        if (isset($_GET['search'])) {
            $tr->setKeyWord($searchStr);
        }
        else {
//            if(isset($_GET['date1']) && isset($_GET['date2'])){
//                $this->db->where("fulldate>='" . $date1 . "'");
//                $this->db->where("fulldate<='" . $date2 . "'");
//            }
        }
        $tmpHist = $tr->lookupHistories($jmlData, $limit, $page)->result();
        showLast_query("biru");
        // arrPrintWebs($tmpHist);
        //matiHEre();
        $arrCurrency = array();
        if (sizeof($printValas) > 0) {
            $this->load->model("Mdls/MdlCurrency");
            $trv = new MdlCurrency();
            $tmpCurrency = $trv->lookupAll()->result();
            if (sizeof($tmpCurrency) > 0) {
                foreach ($tmpCurrency as $key => $value) {
                    $arrCurrency[$key] = $value;
                }
            }
        }

        $cabang_id = $this->placeId;

        $arrayHistory = array();
        $arrayHistory_ids = array();
        $arrayHistory_canceled = array();
        $sumValue = array();
        if (sizeof($tmpHist) > 0) {
            if (sizeof($pairRegistries) > 0) {
                $arrSalesName = array();
                $arrTransID = array();
                $arrTransTopID = array();
                $arrIndexID = array();
                $arrIdsHist = array();
                $arrTransHist = array();
                $arrTransMainHist = array();
                foreach ($tmpHist as $row) {
                    $arrTransID[] = $row->id;
                    $arrTransTopID[] = $row->id_top;

                    if ($row->ids_his != "") {
                        $hist = blobDecode($row->ids_his);
                        foreach ($hist as $hisSpec) {
                            $arrIdsHist[$row->id][$hisSpec['step']] = array(
                                "step" => $hisSpec['step'],
                                "trID" => $hisSpec['trID'],
                                "nomer" => $hisSpec['nomer'],
                            );
                            $arrTransHist[] = $hisSpec['trID'];
                        }
                    }
                }

                $tmpReg_result = array();
                $trReg = new MdlTransaksiModul();
                $trReg->setPrefix($this->prefixName);
                $trReg->setFilters(array());

                // foreach ($pairRegistries as $param) {
                // $trReg->setParam("transaksi_id");
                // $trReg->setInParam($arrTransID);
                // $trReg->setFilters(array("param" => $param));
                // arrPrint($arrTransID);
                // matiHere();
                if (sizeof($pairRegistries) > 0) {
                    $selectKolom = implode(",", $pairRegistries) . ",transaksi_id";
                    // matiHEre(__LINE__);
                    $trReg->setJointSelectFields($selectKolom);
                    $trReg->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
                    $tmpReg = $trReg->lookupDataRegistries()->result();
                    // ceklIme($this->db->last_query());
                    // matiHEre();

                    //                                        arrPrint($tmpReg);
                    if (sizeof($tmpReg) > 0) {
                        foreach ($tmpReg as $regRow) {
                            foreach ($pairRegistries as $param) {
                                $tmpReg_result[$regRow->transaksi_id][$param] = blobDecode($regRow->$param);
                            }
                            // $tmpReg_result[$regRow->transaksi_id][$regRow->param] = blobDecode($regRow->values);
                        }
                    }
                }

                // }

                $tr->setFilters(array());
                $tr = new MdlTransaksiModul();
                $tr->setPrefix($this->prefixName);
                // $tr->setParam("id");
                // $tr->setInParam($arrTransTopID);
                $tr->addFilter("id in ('" . implode(",", $arrTransTopID) . "')");
                $tmpTrTop = $tr->lookUpMainTransaksi()->result();
                if (sizeof($tmpTrTop) > 0) {
                    foreach ($tmpTrTop as $topSpec) {
                        $arrSalesName[$topSpec->id_top] = $topSpec->oleh_nama;
                    }
                }

                if (sizeof($arrIdsHist) > 0) {
                    $tr->setFilters(array());
                    $tr = new MdlTransaksiModul();
                    $tr->setPrefix($this->prefixName);
                    // $tr->setParam("id");
                    // $tr->setInParam($arrTransHist);
                    $tr->addFilter("id in ('" . implode(",", $arrTransHist) . "')");
                    $tmpTransHist = $tr->lookUpMainTransaksi()->result();

                    //                    $tr = new MdlTransaksiModul();
                    //                    $tr->setFilters(array());
                    //                    $tr->addFilter("id in ('" . implode("','", $arrTransHist) . "')");
                    //                    $tmpTransHist = $tr->lookupAll()->result();

                    $tmpTransHist_result = array();
                    if (sizeof($tmpTransHist) > 0) {
                        foreach ($tmpTransHist as $histSpec) {
                            $tmpTransHist_result[$histSpec->id] = array(
                                "oleh_id" => $histSpec->oleh_id,
                                "oleh_nama" => $histSpec->oleh_nama,
                            );
                        }
                    }
                    //                    arrPrint($tmpTransHist_result);
                    //                    matiHEre();
                    if (sizeof($arrIdsHist) > 0) {
                        foreach ($arrIdsHist as $trID => $histSpec) {
                            foreach ($histSpec as $step => $detailSpec) {
                                if (array_key_exists($detailSpec['trID'], $tmpTransHist_result)) {
                                    $detailSpec['main'] = $tmpTransHist_result[$detailSpec['trID']];
                                }
                                $arrTransMainHist[$trID][$step] = $detailSpec;
                            }
                        }
                    }

                }

            }

            //arrPrint($tmpReg_result);
            //            matiHere();
            $numb = 0;
            foreach ($tmpHist as $ii => $row) {

                $this->placeId = $cabang_id = $row->cabang_id;
                // region ids_his
                $id_hist = blobDecode($row->ids_his);
                // endregion ids_his

                //region memangil global counter
                $tNomer_top = $row->nomer_top;
                //                $tr = new MdlTransaksiModul();
                //                $tr->addFilter("param='main'");
                //                $tmpReg = $tr->lookupRegistriesByNumber($tNomer_top)->result();
                //
                //                $arrSalesName = "";
                //                foreach ($tmpReg as $tmpRowReg) {
                //                    $arrSalesName = $tmpRowReg->oleh_nama;
                //                }
                $salesName = isset($arrSalesName[$row->id_top]) ? $arrSalesName[$row->id_top] : "-";


                $tNomer = $row->nomer;
                $jenisTrtop = explode(".", $tNomer_top)[0];
                $jenisTrsub = explode(".", $tNomer)[0];
                $counterjenis = "$jenisTrsub|" . $this->placeId;
                // $counterjenis = my_cabang_id()=="-1"? "$jenisTrsub":"$jenisTrsub|" . $this->placeId;

                // matiHEre($jenisTrsub);
                $counterIds_his = blobDecode(blobDecode($row->ids_his)[1]['counters']);
                $counters = blobDecode($row->counters);
                // arrPrint($counters);
                // matiHere();

                $counterGlobal = $counters['stepCode|placeID'][$counterjenis];


                $counterIds_his_global = isset($counterIds_his['stepCode|placeID']["$jenisTrtop|$cabang_id"]) ? $counterIds_his['stepCode|placeID']["$jenisTrtop|$cabang_id"] : "";
                $cGlobals = digit_5($counterGlobal);
                $cGlobal_spo = digit_5($counterIds_his_global);

                //endregion

                if (sizeof($pairRegistries) > 0) {
                    if ((sizeof($tmpReg_result) > 0) && (isset($tmpReg_result[$row->id]))) {
                        foreach ($tmpReg_result[$row->id] as $param => $eReg) {
                            //                                                        cekLime($param);
                            if ($param == "main") {
                                foreach ($eReg as $k => $v) {
                                    if (!isset($row->$k)) {
                                        $row->$k = $v;
                                    }
                                }
                            }
                            else {
                                if (sizeof($extHistoryFields2) > 0) {
                                    foreach ($extHistoryFields2 as $k1 => $v1) {
                                        if (is_array($v1)) {
                                            $kolom = $v1['kolom'];
                                            $format = $v1['format'];
                                            //                                                cekHitam(":: $kolom :: $format ::");
                                            if (!isset($row->$k1)) {
                                                $tmpDetail = "";
                                                foreach ($eReg as $eeReg) {
                                                    $valDetail = formatField_he_format($format, $eeReg[$kolom]);
                                                    $tmpDetail .= "<span>$valDetail</span><br>";
                                                }
                                                $row->$k1 = $tmpDetail;
                                            }
                                        }
                                        else {

                                            if (!isset($row->$k1)) {
                                                $tmpDetail = "";
                                                foreach ($eReg as $eeReg) {
                                                    $valDetail = formatField_he_format("nomer", $eeReg[$v1]);
                                                    $tmpDetail .= "<span>$valDetail</span><br>";
                                                    //                                                arrPrint($eeReg);
                                                }
                                                $row->$k1 = $tmpDetail;
                                            }
                                        }
                                        //                                        else{
                                        //                                            mati_disini("sudah ada $k1");
                                        //                                        }
                                        //
                                    }
                                }
                            }
                        }
                    }
                    if (sizeof($pairTransaksi) > 0) {
                        if ($row->referenceID > 0) {
                            $trPair = new MdlTransaksiModul();
                            $trPair->setPrefix($this->prefixName);
                            $trPair->addFilter("id='" . $row->referenceID . "'");
                            $trPairTmp = $trPair->lookupMainTransaksi()->result();
                            if (sizeof($trPairTmp) > 0) {
                                $hisTr = isset($trPairTmp[0]->ids_his) ? blobDecode($trPairTmp[0]->ids_his) : array();
                                foreach ($hisTr as $step => $hisTrSpec) {
                                    foreach ($pairTransaksi['kolom'] as $keyPair => $labelPair) {
                                        $keyPairs = $keyPair . "_" . $step;
                                        $row->$keyPairs = isset($hisTrSpec[$labelPair]) ? $hisTrSpec[$labelPair] : "--";
                                    }
                                }
                            }
                        }
                    }
                }

                //arrPrintWebs($row);
                if (sizeof($historyFieldsExt) > 0) {
                    foreach ($historyFieldsExt as $alias => $colom) {
                        $row->$alias = $row->$colom;
                    }
                }
                $tmp = array();
                $tmp1 = array();
                //arrPrint($row);
                //                 break;
                $numb++;
                foreach ($historyFields as $fName => $fLabel) {
                    //                    cekHitam($fName);
                    if (strpos($fName, '+') !== false) {//==mengandung penggabungan (+)
                        $chars = explode("+", $fName);
                        $colValue = "";
                        foreach ($chars as $key) {
                            if (is_numeric($row->$key)) {
                                if (!isset($sumValue[$key])) {
                                    $sumValue[$key] = 0;
                                }
                                $sumValue[$key] += $row->$key;
                            }
                            $colValue .= isset($row->$key) ? formatField_he_format($key, $row->$key) . "<br>" : "";
                        }
                        $colValue = rtrim($colValue, "<br>");
                    }
                    else {

                        if (is_numeric(isset($row->$fName) ? $row->$fName : "")) {
                            if (!isset($sumValue[$fName])) {
                                $sumValue[$fName] = 0;
                            }
                            $sumValue[$fName] += $row->$fName;
                        }

                        //region nomer dengan global counter
                        if ($fName == "nomer") {
                            // switch ($jenisTr) {
                            switch ($jenisTrsub) {
                                case "582s":
                                    $kolomValues = $row->$fName . "&#x2011;$cGlobals";
                                    break;
                                // case "582spd":
                                default:
                                    $kolomValue_0s = formatField_he_format($fName, $row->$fName);
                                    $kolomValues = str_replace("</span>", "&#x2011;" . $cGlobals, $kolomValue_0s);
                                    break;
                            }
                        }
                        elseif ($fName == "nomer_top") {
                            $kolomValue_0s = formatField_he_format($fName, $row->$fName);
                            $kolomValues = str_replace("</span>", "&#x2011;" . $cGlobal_spo, $kolomValue_0s);
                        }
                        else {
                            //                            cekHitam(";; $fName");
                            $kolomValues = isset($row->$fName) ? formatField_he_format($fName, $row->$fName) : "-";
                        }
                        //endregion

                        // $colValue = isset($row->$fName) ? formatField($fName, $row->$fName) : "";

                        $colValue = isset($row->$fName) ? $kolomValues : "";
                        //                        cekLime("$colValue"." ".$fName);

                    }

                    //                    if ($fName == "ids_his") {
                    if (is_array($fLabel)) {

                        $hisStep = $fLabel['step'];
                        $hisKey = $fLabel['key'];
                        //                        $tNomer = $id_hist[$hisStep][$hisKey];


                        if ($hisKey == "nomer") {
                            $colValue = isset($row->ids_his) ? showHistoriGlobalNumbers($row->ids_his, $hisStep, true, $this->jenisTr) : "";
                        }
                        else {
                            if (isset($fLabel['transaksi_jenis2'][$row->transaksi_jenis2])) {
                                $getKey = $fLabel['transaksi_jenis2'][$row->transaksi_jenis2];
                                //                                cekHere(":: $getKey ::");
                                $colValue = isset($row->$getKey) ? formatField_he_format($getKey, $row->$getKey) : "";
                            }
                            else {

                                $colValue = "-";
                            }
                        }


                        //                            $tr = new MdlTransaksiModul();
                        //                            $tr->setFilters(array());
                        //                            $tr->addFilter("param='main'");
                        //                            $tmpReg = $tr->lookupRegistriesByNumber($tNomer)->result();
                        ////                            cekHere($this->db->last_query());
                        //
                        //                            $logistic = $tmpReg[0]->oleh_nama;
                        $logistic = "";
                        if (isset($arrTransMainHist[$row->id][$hisStep]['main'])) {
                            $main = $arrTransMainHist[$row->id][$hisStep]['main'];
                            $logistic = $main['oleh_nama'];
                        }
                    }
                    //                    }

                    if ($fName == "no") {
                        $colValue = formatField_he_format($fName, $numb);
                    }

                    //                    cekHere($logistic);
                    $tmp['logistic'] = isset($logistic) && $logistic != null ? $logistic : 'undefined';
                    $tmp['sales_name'] = $salesName;
                    $tmp[$fName] = $colValue;
                    $tmp1["id"] = $row->id;
                }


                if (sizeof($arrCurrency) > 0) {
                    $valas = "";
                    $valas .= "<div class='btn-group'>";
                    $valas .= "<button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>";
                    $valas .= "<i class='fa fa-print'></i>";
                    $valas .= "<span class='caret'></span>";
                    $valas .= "</button>";

                    $valas .= "<ul style='background:#cde8ff;' class='dropdown-menu dropdown-menu-right'>";
                    foreach ($arrCurrency as $arrV) {
                        $nama = $arrV->nama;
                        $nomer = $row->nomer;
                        $nilai = number_format($arrV->exchange, 0);
                        // $valas .= " <li class='text-bold'><a class='dropdown-item' href='javascript:void(0);' onclick=\"top.popBig('" . MODUL_PATH . "Printing/viewReceipt/$nomer?type=" . blobEncode($nama) . "&f=" . blobEncode($arrV->exchange) . "')\"> <i class='fa fa-print'></i> in $nama - ($nilai) </a></li>";
                        $valas .= " <li class='text-bold'><a class='dropdown-item' href='javascript:void(0);' onclick=\"top.popBig('" . MODUL_PATH . "Printing/viewReceipt/" . $this->jenisTr . "/$nomer?type=" . blobEncode($nama) . "&f=" . blobEncode($arrV->exchange) . "')\"> <i class='fa fa-print'></i> in $nama - ($nilai) </a></li>";
                    }

                    // $valas .= " <li><a class='btn btn-xs btn-warning' href='javascript:void(0);' onclick=\"top.location.href='" . base_url() . "data/view/Currency'\"> <i class='fa fa-plus'></i> tambah currency </a></li>";
                    $valas .= "</ul>";
                    $valas .= "</div>";
                    $tmp["print_nvalas"] = $valas;
                }

                $tmp['next_pic'] = "-";

                // menambah background-color karena dicancel/reject/undo, atau yang dibatalkan
                //                $tmp['keterangan'] = "-";
                $addKeterangan = "";
                if (isset($row->trash_4) && ($row->trash_4 == 1)) {
                    //                    $tmp['keterangan'] = "CANCELED";
                    $addKeterangan = "CANCELED";
                    //                    $addKeterangan .= $row->cancel_dtime != NULL ? "&nl2br; date: " . $row->cancel_dtime : "";
                    //                    $addKeterangan .= $row->cancel_name != NULL ? "&nl2br; by: " . $row->cancel_name : "";
                    $addKeterangan .= $row->cancel_dtime != NULL ? nl2br(" date: " . $row->cancel_dtime) : "";
                    $addKeterangan .= $row->cancel_name != NULL ? nl2br(" by: " . $row->cancel_name) : "";
                    $arrayHistory_canceled[$ii] = array(
                        "bgcolor" => "background-color:red;color:#cccccc;",
                    );
                }
                // menambah background-color karena diedit...
                if (sizeof($extHistoryKeterangan) > 0) {
                    foreach ($extHistoryKeterangan as $mode => $modeSpec) {
                        $mode_result = "";
                        if (sizeof($modeSpec) > 0) {
                            if (isset($row->$modeSpec['kolom']) && ($row->$modeSpec['kolom'] == $modeSpec['value'])) {
                                $addBr = $addKeterangan != NULL ? "<hr>" : "";
                                $l_result = "";
                                if (is_array($modeSpec['labels'])) {
                                    foreach ($modeSpec['labels'] as $l) {
                                        if ($l_result == "") {
                                            $l_result = "$addBr $mode by: " . formatField_he_format($l, $row->$l);
                                        }
                                        else {
                                            $l_result .= ", " . formatField_he_format($l, $row->$l);
                                        }
                                    }
                                }
                                else {
                                    $l_result = $modeSpec['labels'];
                                }
                                $mode_result .= $l_result;

                                //----------------------------------------
                                if (isset($modeSpec['style'])) {
                                    $color = $modeSpec['style']['color'];
                                    $bgcolor = $modeSpec['style']['bgcolor'];
                                    $arrayHistory_keterangan[$ii] = array(
                                        "bgcolor" => "background-color:$bgcolor;color:$color;",
                                    );
                                }
                            }

                        }
                        $addKeterangan .= $mode_result;


                    }
                }


                $tmp['keterangan'] = $addKeterangan;
                $arrayHistory[$ii] = $tmp;
                $arrayHistory_ids[$ii] = $tmp1;


            }
        }
        //endregion


        //region link to add new transaction
        if (placeCanMakeTrans_he_menu($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr, $this->configUiJenis)) {
            //        if (in_array($this->configUi[$jenisTr]["steps"][1]['userGroup'], $this->session->login['membership'])) {
            $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
            if (array_key_exists($this->jenisTr, $createIndexes)) {
                $targetUrl = MODUL_PATH . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
            }
            else {
                $targetUrl = MODUL_PATH . "Create/index/" . $this->jenisTr;
            }
            $addLink = array(
                "link" => $targetUrl,
                "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->configUi[$jenisTr]["steps"][1]['label'],
            );
        }
        else {
            $addLink = null;
        }
        //endregion

        //region prepare params for viewer
        $data = array(
            "mode" => "showData",
            //            "mode" => "viewHistory",
            "isMobile" => $isMob,
            "jenisTr" => $jenisTr,
            "trName" => $this->configUi[$jenisTr]["label"],
            "errMsg" => $this->session->errMsg,
            "title" => isset($subCodes) && isset($currentState) ? $subCodes[$currentState] : $this->jenisTrName,
            "subTitle" => "histories",
            "customButton" => $customButton,
            "customButtonTarget" => isset($currentState) ? "$currentState?date1=$date1&date2=$date2" : "",
            "arrayHistoryLabels" => $historyFields,
            "arrayHistory" => $arrayHistory,
            "arrayHistorySumField" => $sumValue,
            "arrayHistoryId" => $arrayHistory_ids,
            "action" => $action,
//            "steps"                => $steps,
            "steps" => $pfResultFase,
            "stepLabels" => $stepLabels,
            "stepLinks" => $stepLinks,
            "addParams" => isset($_GET['addParams']) ? $_GET['addParams'] : null,
            "currentState" => isset($currentState) ? $currentState : "all states",
            //            "alternateLink" => base_url() . $this->uri->segment(1) . "/viewIncomplete/" . $this->uri->segment(3),
            //            "alternateLinkCaption" => "incomplete " . $this->configUi[$jenisTr]["label"] . " <span class='glyphicon glyphicon-arrow-right'></span>",
            "alternateLink" => "",
            "alternateLinkCaption" => "",
            "addLink" => $addLink,
            "filters" => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "thisPage" => MODUL_PATH . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/" . $this->uri->segment(6),

            "history_canceled" => isset($arrayHistory_canceled) ? $arrayHistory_canceled : array(),
            "history_keterangan" => isset($arrayHistory_keterangan) ? $arrayHistory_keterangan : array(),
        );
        //endregion

        $endtime = microtime(true); // Bottom of page
        $valTimeEnd = $endtime - $starttime;
        //        cekBiru("load time start $starttime ||  end $endtime =>" . "$valTimeEnd");

        $this->load->view("history", $data);
    }

    public function viewHistoryWip()
    {
        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
        $starttime = microtime(true);
        $limit = 100;
        $maxPageNum = 20;
        $jenisTr = $this->uri->segment(4);
        $jenisTrsub = $this->uri->segment(6);
        $bomID = $this->uri->segment(5);
        $cCode = $this->cCode;
        $allStep = $this->configUi[$jenisTr]['steps'];
        $availSteps = array();
        foreach ($allStep as $step => $tempStep) {
            $availSteps[$tempStep['target']] = $step;
        }


        // region bom
        $this->load->model("Mdls/MdlProdukRakitan");
        $this->load->model("Mdls/MdlProdukFase");
        $pr = New MdlProdukRakitan();
        $pf = New MdlProdukFase();

        $pr->addFilter("id=$bomID");
        $prTmp = $pr->lookupAll()->result();
        $prResult = array();
        if (sizeof($prTmp) > 0) {
            foreach ($prTmp as $prSpec) {
                $prResult[$prSpec->id] = $prSpec->nama;
            }
        }

        $pf->addFilter("produk_id=$bomID");
        $pfTmp = $pf->lookupAll()->result();
        $pfResult = array();
        $pfResultBom = array();
        $pfResultFase = array();
        if (sizeof($pfTmp) > 0) {
            foreach ($pfTmp as $pfSpec) {
                $pfResult[] = $pfSpec->kode_transaksi;
                $pfResultBom[$pfSpec->produk_id][$pfSpec->urut] = $pfSpec->kode_transaksi;
                $pfResultFase[$pfSpec->urut] = array(
                    "label" => $pfSpec->nama,
                    "source" => $pfSpec->kode_transaksi,
                    "target" => $pfSpec->next_kode_transaksi,
                    "actionLabel" => "simpan " . $pfSpec->nama,
                );
            }
        }
        // endregion bom


        $selectedSTep = isset($availSteps[$jenisTrsub]) ? $availSteps[$jenisTrsub] : 1;
        $historyFields = isset($this->configUi[$jenisTr]['historyFields'][$selectedSTep]) ? $this->configUi[$jenisTr]['historyFields'][$selectedSTep] : $this->configUi[$jenisTr]['shortHistoryFields'];
        $pairRegistries = isset($this->configUi[$jenisTr]['pairRegistries']) ? $this->configUi[$jenisTr]['pairRegistries'] : array();
        $historyFieldsExt = isset($this->configUi[$jenisTr]["extHistoryFields"][$selectedSTep]) ? $this->configUi[$jenisTr]["extHistoryFields"][$selectedSTep] : array();
        $extHistoryFields2 = isset($this->configUi[$jenisTr]["extHistoryFields2"][$selectedSTep]) ? $this->configUi[$jenisTr]["extHistoryFields2"][$selectedSTep] : array();
        $customButton = isset($this->configLayout[$jenisTr]["customButton"][$selectedSTep]) ? $this->configLayout[$jenisTr]["customButton"][$selectedSTep] : array();
        $printValas = isset($this->configLayout[$jenisTr]["print_nvalas"]) ? $this->configLayout[$jenisTr]["print_nvalas"] : array();
        $pairTransaksi = isset($this->configUi[$jenisTr]['pairTransaksi']) ? $this->configUi[$jenisTr]['pairTransaksi'] : array();
        $extHistoryKeterangan = isset($this->configUi[$jenisTr]['extHistoryKeterangan'][$selectedSTep]) ? $this->configUi[$jenisTr]['extHistoryKeterangan'][$selectedSTep] : array();
        $modul = isset($this->masterConfigUi[$jenisTr]["modul"]) ? $this->masterConfigUi[$jenisTr]["modul"] : false;
        $modul_path = base_url() . $modul . "/";
        //arrPrint($extHistoryKeterangan);

        $mb = New MobileDetect();
        $isMob = $mb->isMobile();
        if ($isMob) {
            $historyFields = isset($this->configUi[$jenisTr]['compactHistoryFields']) ? $this->configUi[$jenisTr]['compactHistoryFields'] : array();
        }

        $backdate_f = formatTanggal(backDate(30), 'Y-m-d');

        $date1 = isset($_GET['date1']) ? $_GET['date1'] : $backdate_f;
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");


        //region preparing ERP step labels for top link
        $steps = $this->configUi[$jenisTr]['steps'];
        $stepLabels = array();
        $stepLinks = array();
        if (sizeof($steps) > 1) {
            $subCodes = array();
            $stepCodes = array();
//            $jmlStep = count($steps);
            $jmlStep = count($pfResultFase);
//            arrprintKuning($steps);
//            foreach ($steps as $stepNumber => $stepSpec) {
            foreach ($pfResultFase as $stepNumber => $stepSpec) {
                if ($stepNumber <= $jmlStep) {
                    $subCodes[$stepSpec['target']] = $stepSpec['label'];
                    $stepCodes[] = $stepSpec['target'];
                    $stepLabels[$stepNumber] = $stepSpec['label'];
                    $stepLinks[$stepNumber] = MODUL_PATH . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/" . $stepSpec['target'] . "?date1=$date1&date2=$date2";
                }
            }
            if (isset($_GET['stID'])) {
                $currentState = $_GET['stID'];
            }
            else {
//                $currentState = strlen($this->uri->segment(6)) > 0 ? $this->uri->segment(6) : $this->configUi[$jenisTr]['steps'][1]['target'];
                $currentState = strlen($this->uri->segment(6)) > 0 ? $this->uri->segment(6) : $pfResultFase[1]['target'];
            }
        }
        //endregion


        //region lookup histories

        $this->load->model("MdlTransaksiModul");
        $tr = new MdlTransaksiModul();
        $tr->setPrefix($this->prefixName);

        $searchStr = isset($_GET['search']) ? $_GET['search'] : "";
        if (my_cabang_id() == "-1") {
            $filters = array(
                // "cabang_id" => $this->placeId,
                "jenis_master" => $this->jenisTr,
                "link_id" => "0",
                "div_id" => $this->session->login['div_id'],
            );
        }
        else {
            if ($this->session->login['employee_type'] == "employee_freelance") {
                $filters = array(
                    "cabang_id" => $this->placeId,
                    "jenis_master" => $this->jenisTr,
                    "link_id" => "0",
                    "div_id" => $this->session->login['div_id'],
                    "seller_id" => $this->session->login['id']
                );
            }
            else {
                $filters = array(
                    "cabang_id" => $this->placeId,
                    "jenis_master" => $this->jenisTr,
                    "link_id" => "0",
                    "div_id" => $this->session->login['div_id'],
                );
            }
        }
        if (sizeof($filters) > 0) {
            foreach ($filters as $key_f => $val_f) {
                $tr->addFilter("$key_f='" . $val_f . "'");
            }
        }
        if (isset($currentState)) {
            $tr->addFilter("jenis='" . $currentState . "'");
        }
        $tr->addFilter("bom_id='$bomID'");

        $addParams = array();
        if (isset($_GET['addParams'])) {
            $addParams = unserialize(base64_decode($_GET['addParams']));
        }
        if ($addParams != null && sizeof($addParams) > 0) {
            foreach ($addParams as $f) {
                $tr->addFilter($f);
            }
        }

        if (isset($_GET['search'])) {
            $tr->setKeyWord($searchStr);
        }
        else {
            $this->db->where("fulldate>='" . $date1 . "'");
            $this->db->where("fulldate<='" . $date2 . "'");
        }

        $jmlData = $tr->lookupDataCount();
        $page = (isset($_GET['page']) && $_GET['page'] > 0) ? ($_GET['page']) : 1;
        $offset = ($limit * ($page - 1));

        $addParams = array();
        if (isset($_GET['addParams'])) {
            $addParams = unserialize(base64_decode($_GET['addParams']));
        }
        if ($addParams != null && sizeof($addParams) > 0) {
            foreach ($addParams as $f) {
                $tr->addFilter($f);
            }
        }

        $action = array(
            "viewDetails" => base_url() . get_class($this) . "/viewDetails",
        );

        if (isset($_GET['search'])) {
            $tr->setKeyWord($searchStr);
        }
        else {
            $this->db->where("fulldate>='" . $date1 . "'");
            $this->db->where("fulldate<='" . $date2 . "'");
        }
        if (isset($_GET['date1'])) {
            $limit = "";
        }
        $tmpHist = $tr->lookupHistories($jmlData, $limit, $page)->result();
        showLast_query("biru");
        // arrPrintWebs($tmpHist);
        //matiHEre();
        $arrCurrency = array();
        if (sizeof($printValas) > 0) {
            $this->load->model("Mdls/MdlCurrency");
            $trv = new MdlCurrency();
            $tmpCurrency = $trv->lookupAll()->result();
            if (sizeof($tmpCurrency) > 0) {
                foreach ($tmpCurrency as $key => $value) {
                    $arrCurrency[$key] = $value;
                }
            }
        }

        $cabang_id = $this->placeId;

        $arrayHistory = array();
        $arrayHistory_ids = array();
        $arrayHistory_canceled = array();
        $sumValue = array();
        if (sizeof($tmpHist) > 0) {
            if (sizeof($pairRegistries) > 0) {
                $arrSalesName = array();
                $arrTransID = array();
                $arrTransTopID = array();
                $arrIndexID = array();
                $arrIdsHist = array();
                $arrTransHist = array();
                $arrTransMainHist = array();
                foreach ($tmpHist as $row) {
                    $arrTransID[] = $row->id;
                    $arrTransTopID[] = $row->id_top;

                    if ($row->ids_his != "") {
                        $hist = blobDecode($row->ids_his);
                        foreach ($hist as $hisSpec) {
                            $arrIdsHist[$row->id][$hisSpec['step']] = array(
                                "step" => $hisSpec['step'],
                                "trID" => $hisSpec['trID'],
                                "nomer" => $hisSpec['nomer'],
                            );
                            $arrTransHist[] = $hisSpec['trID'];
                        }
                    }
                }

                $tmpReg_result = array();
                $trReg = new MdlTransaksiModul();
                $trReg->setPrefix($this->prefixName);
                $trReg->setFilters(array());

                // $trReg->setFields($pairRegistries);
                $selectKolom = implode(",", $pairRegistries) . ",transaksi_id";
                $trReg->setJointSelectFields($selectKolom);
                $trReg->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
                $tmpReg = $trReg->lookupDataRegistries()->result();
                if (sizeof($tmpReg) > 0) {
                    foreach ($tmpReg as $regRow) {
                        foreach ($pairRegistries as $param) {
                            $tmpReg_result[$regRow->transaksi_id][$param] = blobDecode($regRow->$param);
                        }

                    }
                }


                $tr = new MdlTransaksiModul();
                $tr->setPrefix($this->prefixName);
                $tr->setFilters(array());
                $tr->addFilter("id in ('" . implode("','", $arrTransTopID) . "')");
                $tmpTrTop = $tr->lookUpMainTransaksi()->result();
                if (sizeof($tmpTrTop) > 0) {
                    foreach ($tmpTrTop as $topSpec) {
                        $arrSalesName[$topSpec->id_top] = $topSpec->oleh_nama;
                    }
                }

                if (sizeof($arrIdsHist) > 0) {
                    $tr = new MdlTransaksiModul();
                    $tr->setPrefix($this->prefixName);
                    // $tr->setParam("id");
                    // $tr->setInParam($arrTransHist);
                    $tr->setFilters(array());
                    $tr->addFilter("id in ('" . implode("','", $arrTransHist) . "')");
                    $tmpTransHist = $tr->lookUpMainTransaksi()->result();
                    $tmpTransHist_result = array();
                    if (sizeof($tmpTransHist) > 0) {
                        foreach ($tmpTransHist as $histSpec) {
                            $tmpTransHist_result[$histSpec->id] = array(
                                "oleh_id" => $histSpec->oleh_id,
                                "oleh_nama" => $histSpec->oleh_nama,
                            );
                        }
                    }
                    //                    arrPrint($tmpTransHist_result);
                    //                    matiHEre();
                    if (sizeof($arrIdsHist) > 0) {
                        foreach ($arrIdsHist as $trID => $histSpec) {
                            foreach ($histSpec as $step => $detailSpec) {
                                if (array_key_exists($detailSpec['trID'], $tmpTransHist_result)) {
                                    $detailSpec['main'] = $tmpTransHist_result[$detailSpec['trID']];
                                }
                                $arrTransMainHist[$trID][$step] = $detailSpec;
                            }
                        }
                    }

                }

            }

            //arrPrint($tmpReg_result);
            //            matiHere();
            $numb = 0;
            foreach ($tmpHist as $ii => $row) {

                $this->placeId = $cabang_id = $row->cabang_id;
                // region ids_his
                $id_hist = blobDecode($row->ids_his);
                // endregion ids_his

                //region memangil global counter
                $tNomer_top = $row->nomer_top;
                //                $tr = new MdlTransaksiModul();
                //                $tr->addFilter("param='main'");
                //                $tmpReg = $tr->lookupRegistriesByNumber($tNomer_top)->result();
                //
                //                $arrSalesName = "";
                //                foreach ($tmpReg as $tmpRowReg) {
                //                    $arrSalesName = $tmpRowReg->oleh_nama;
                //                }
                $salesName = isset($arrSalesName[$row->id_top]) ? $arrSalesName[$row->id_top] : "-";


                $tNomer = $row->nomer;
                $jenisTrtop = explode(".", $tNomer_top)[0];
                $jenisTrsub = explode(".", $tNomer)[0];
                $counterjenis = "$jenisTrsub|" . $this->placeId;
                // $counterjenis = my_cabang_id()=="-1"? "$jenisTrsub":"$jenisTrsub|" . $this->placeId;

                // matiHEre($jenisTrsub);
                $counterIds_his = blobDecode(blobDecode($row->ids_his)[1]['counters']);
                $counters = blobDecode($row->counters);
                // arrPrint($counters);
                // matiHere();

                $counterGlobal = $counters['stepCode|placeID'][$counterjenis];


                $counterIds_his_global = isset($counterIds_his['stepCode|placeID']["$jenisTrtop|$cabang_id"]) ? $counterIds_his['stepCode|placeID']["$jenisTrtop|$cabang_id"] : "";
                $cGlobals = digit_5($counterGlobal);
                $cGlobal_spo = digit_5($counterIds_his_global);

                //endregion

                if (sizeof($pairRegistries) > 0) {
                    if ((sizeof($tmpReg_result) > 0) && (isset($tmpReg_result[$row->id]))) {
                        foreach ($tmpReg_result[$row->id] as $param => $eReg) {
                            //                                                        cekLime($param);
                            if ($param == "main") {
                                foreach ($eReg as $k => $v) {
                                    if (!isset($row->$k)) {
                                        $row->$k = $v;
                                    }
                                }
                            }
                            else {
                                if (sizeof($extHistoryFields2) > 0) {
                                    foreach ($extHistoryFields2 as $k1 => $v1) {
                                        if (is_array($v1)) {
                                            $kolom = $v1['kolom'];
                                            $format = $v1['format'];
                                            //                                                cekHitam(":: $kolom :: $format ::");
                                            if (!isset($row->$k1)) {
                                                $tmpDetail = "";
                                                foreach ($eReg as $eeReg) {
                                                    $valDetail = formatField_he_format($format, $eeReg[$kolom]);
                                                    $tmpDetail .= "<span>$valDetail</span><br>";
                                                }
                                                $row->$k1 = $tmpDetail;
                                            }
                                        }
                                        else {

                                            if (!isset($row->$k1)) {
                                                $tmpDetail = "";
                                                foreach ($eReg as $eeReg) {
                                                    $valDetail = formatField_he_format("nomer", $eeReg[$v1]);
                                                    $tmpDetail .= "<span>$valDetail</span><br>";
                                                    //                                                arrPrint($eeReg);
                                                }
                                                $row->$k1 = $tmpDetail;
                                            }
                                        }
                                        //                                        else{
                                        //                                            mati_disini("sudah ada $k1");
                                        //                                        }
                                        //
                                    }
                                }
                            }
                        }
                    }
                    if (sizeof($pairTransaksi) > 0) {
                        if ($row->referenceID > 0) {
                            $trPair = new MdlTransaksiModul();
                            $trPair->setPrefix($this->prefixName);
                            $trPair->addFilter("id='" . $row->referenceID . "'");
                            $trPairTmp = $trPair->lookupMainTransaksi()->result();
                            if (sizeof($trPairTmp) > 0) {
                                $hisTr = isset($trPairTmp[0]->ids_his) ? blobDecode($trPairTmp[0]->ids_his) : array();
                                foreach ($hisTr as $step => $hisTrSpec) {
                                    foreach ($pairTransaksi['kolom'] as $keyPair => $labelPair) {
                                        $keyPairs = $keyPair . "_" . $step;
                                        $row->$keyPairs = isset($hisTrSpec[$labelPair]) ? $hisTrSpec[$labelPair] : "--";
                                    }
                                }
                            }
                        }
                    }
                }

                //arrPrintWebs($row);
                if (sizeof($historyFieldsExt) > 0) {
                    foreach ($historyFieldsExt as $alias => $colom) {
                        $row->$alias = $row->$colom;
                    }
                }
                $tmp = array();
                $tmp1 = array();
                //arrPrint($row);
                //                 break;
                $numb++;
                foreach ($historyFields as $fName => $fLabel) {
                    //                    cekHitam($fName);
                    if (strpos($fName, '+') !== false) {//==mengandung penggabungan (+)
                        $chars = explode("+", $fName);
                        $colValue = "";
                        foreach ($chars as $key) {
                            if (is_numeric($row->$key)) {
                                if (!isset($sumValue[$key])) {
                                    $sumValue[$key] = 0;
                                }
                                $sumValue[$key] += $row->$key;
                            }
                            $colValue .= isset($row->$key) ? formatField_he_format($key, $row->$key) . "<br>" : "";
                        }
                        $colValue = rtrim($colValue, "<br>");
                    }
                    else {

                        if (is_numeric(isset($row->$fName) ? $row->$fName : "")) {
                            if (!isset($sumValue[$fName])) {
                                $sumValue[$fName] = 0;
                            }
                            $sumValue[$fName] += $row->$fName;
                        }

                        //region nomer dengan global counter
                        if ($fName == "nomer") {
                            // switch ($jenisTr) {
                            switch ($jenisTrsub) {
                                case "582s":
                                    $kolomValues = $row->$fName . "&#x2011;$cGlobals";
                                    break;
                                // case "582spd":
                                default:
                                    $kolomValue_0s = formatField_he_format($fName, $row->$fName);
                                    $kolomValues = str_replace("</span>", "&#x2011;" . $cGlobals, $kolomValue_0s);
                                    break;
                            }
                        }
                        elseif ($fName == "nomer_top") {
                            $kolomValue_0s = formatField_he_format($fName, $row->$fName);
                            $kolomValues = str_replace("</span>", "&#x2011;" . $cGlobal_spo, $kolomValue_0s);
                        }
                        else {
                            //                            cekHitam(";; $fName");
                            $kolomValues = isset($row->$fName) ? formatField_he_format($fName, $row->$fName) : "-";
                        }
                        //endregion

                        // $colValue = isset($row->$fName) ? formatField($fName, $row->$fName) : "";

                        $colValue = isset($row->$fName) ? $kolomValues : "";
                        //                        cekLime("$colValue"." ".$fName);

                    }

                    //                    if ($fName == "ids_his") {
                    if (is_array($fLabel)) {

                        $hisStep = $fLabel['step'];
                        $hisKey = $fLabel['key'];
                        //                        $tNomer = $id_hist[$hisStep][$hisKey];


                        if ($hisKey == "nomer") {
                            $colValue = isset($row->ids_his) ? showHistoriGlobalNumbers($row->ids_his, $hisStep, true, $this->jenisTr) : "";
                        }
                        else {
                            if (isset($fLabel['transaksi_jenis2'][$row->transaksi_jenis2])) {
                                $getKey = $fLabel['transaksi_jenis2'][$row->transaksi_jenis2];
                                //                                cekHere(":: $getKey ::");
                                $colValue = isset($row->$getKey) ? formatField_he_format($getKey, $row->$getKey) : "";
                            }
                            else {

                                $colValue = "-";
                            }
                        }


                        //                            $tr = new MdlTransaksiModul();
                        //                            $tr->setFilters(array());
                        //                            $tr->addFilter("param='main'");
                        //                            $tmpReg = $tr->lookupRegistriesByNumber($tNomer)->result();
                        ////                            cekHere($this->db->last_query());
                        //
                        //                            $logistic = $tmpReg[0]->oleh_nama;
                        $logistic = "";
                        if (isset($arrTransMainHist[$row->id][$hisStep]['main'])) {
                            $main = $arrTransMainHist[$row->id][$hisStep]['main'];
                            $logistic = $main['oleh_nama'];
                        }
                    }
                    //                    }

                    if ($fName == "no") {
                        $colValue = formatField_he_format($fName, $numb);
                    }

                    //                    cekHere($logistic);
                    $tmp['logistic'] = isset($logistic) && $logistic != null ? $logistic : 'undefined';
                    $tmp['sales_name'] = $salesName;
                    $tmp[$fName] = $colValue;
                    $tmp1["id"] = $row->id;
                }


                if (sizeof($arrCurrency) > 0) {
                    $valas = "";
                    $valas .= "<div class='btn-group'>";
                    $valas .= "<button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>";
                    $valas .= "<i class='fa fa-print'></i>";
                    $valas .= "<span class='caret'></span>";
                    $valas .= "</button>";

                    $valas .= "<ul style='background:#cde8ff;' class='dropdown-menu dropdown-menu-right'>";
                    foreach ($arrCurrency as $arrV) {
                        $nama = $arrV->nama;
                        $nomer = $row->nomer;
                        $nilai = number_format($arrV->exchange, 0);
                        // $valas .= " <li class='text-bold'><a class='dropdown-item' href='javascript:void(0);' onclick=\"top.popBig('" . MODUL_PATH . "Printing/viewReceipt/$nomer?type=" . blobEncode($nama) . "&f=" . blobEncode($arrV->exchange) . "')\"> <i class='fa fa-print'></i> in $nama - ($nilai) </a></li>";
                        $valas .= " <li class='text-bold'><a class='dropdown-item' href='javascript:void(0);' onclick=\"top.popBig('" . MODUL_PATH . "Printing/viewReceipt/" . $this->jenisTr . "/$nomer?type=" . blobEncode($nama) . "&f=" . blobEncode($arrV->exchange) . "')\"> <i class='fa fa-print'></i> in $nama - ($nilai) </a></li>";
                    }

                    // $valas .= " <li><a class='btn btn-xs btn-warning' href='javascript:void(0);' onclick=\"top.location.href='" . base_url() . "data/view/Currency'\"> <i class='fa fa-plus'></i> tambah currency </a></li>";
                    $valas .= " <li><a class='btn btn-xs btn-warning' href='javascript:void(0);' onclick=\"top.location.href='" . MODUL_PATH . "data/view/Currency'\"> <i class='fa fa-plus'></i> tambah currency </a></li>";
                    $valas .= "</ul>";
                    $valas .= "</div>";
                    $tmp["print_nvalas"] = $valas;
                }

                $tmp['next_pic'] = "-";

                // menambah background-color karena dicancel/reject/undo, atau yang dibatalkan
                //                $tmp['keterangan'] = "-";
                $addKeterangan = "";
                if (isset($row->trash_4) && ($row->trash_4 == 1)) {
                    //                    $tmp['keterangan'] = "CANCELED";
                    $addKeterangan = "CANCELED";
                    //                    $addKeterangan .= $row->cancel_dtime != NULL ? "&nl2br; date: " . $row->cancel_dtime : "";
                    //                    $addKeterangan .= $row->cancel_name != NULL ? "&nl2br; by: " . $row->cancel_name : "";
                    $addKeterangan .= $row->cancel_dtime != NULL ? nl2br(" date: " . $row->cancel_dtime) : "";
                    $addKeterangan .= $row->cancel_name != NULL ? nl2br(" by: " . $row->cancel_name) : "";
                    $arrayHistory_canceled[$ii] = array(
                        "bgcolor" => "background-color:red;color:#cccccc;",
                    );
                }
                // menambah background-color karena diedit...
                if (sizeof($extHistoryKeterangan) > 0) {
                    foreach ($extHistoryKeterangan as $mode => $modeSpec) {
                        $mode_result = "";
                        if (sizeof($modeSpec) > 0) {
                            if (isset($row->$modeSpec['kolom']) && ($row->$modeSpec['kolom'] == $modeSpec['value'])) {
                                $addBr = $addKeterangan != NULL ? "<hr>" : "";
                                $l_result = "";
                                if (is_array($modeSpec['labels'])) {
                                    foreach ($modeSpec['labels'] as $l) {
                                        if ($l_result == "") {
                                            $l_result = "$addBr $mode by: " . formatField($l, $row->$l);
                                        }
                                        else {
                                            $l_result .= ", " . formatField($l, $row->$l);
                                        }
                                    }
                                }
                                else {
                                    $l_result = $modeSpec['labels'];
                                }
                                $mode_result .= $l_result;

                                //----------------------------------------
                                if (isset($modeSpec['style'])) {
                                    $color = $modeSpec['style']['color'];
                                    $bgcolor = $modeSpec['style']['bgcolor'];
                                    $arrayHistory_keterangan[$ii] = array(
                                        "bgcolor" => "background-color:$bgcolor;color:$color;",
                                    );
                                }
                            }

                        }
                        $addKeterangan .= $mode_result;


                    }
                }


                $tmp['keterangan'] = $addKeterangan;
                $arrayHistory[$ii] = $tmp;
                $arrayHistory_ids[$ii] = $tmp1;


            }
        }
        //endregion


        //region link to add new transaction
        if (placeCanMakeTrans($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr)) {
            //        if (in_array($this->configUi[$jenisTr]["steps"][1]['userGroup'], $this->session->login['membership'])) {
            $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
            if (array_key_exists($this->jenisTr, $createIndexes)) {
                $targetUrl = base_url() . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
            }
            else {
                $targetUrl = MODUL_PATH . "Create/index/" . $this->jenisTr;
            }
            $addLink = array(
                "link" => $targetUrl,
                "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->configUi[$jenisTr]["steps"][1]['label'],
            );
        }
        else {
            $addLink = null;
        }
        //endregion

        //region prepare params for viewer
        $data = array(
//            "mode"                 => $this->uri->segment(3),
            "mode" => "viewHistory",
            "isMobile" => $isMob,
            "jenisTr" => $jenisTr,
            "trName" => $this->configUi[$jenisTr]["label"],
            "errMsg" => $this->session->errMsg,
            "title" => isset($subCodes) && isset($currentState) ? $subCodes[$currentState] : $this->jenisTrName,
            "subTitle" => "histories",
            "customButton" => $customButton,
            "customButtonTarget" => isset($currentState) ? "$currentState?date1=$date1&date2=$date2" : "",
            "arrayHistoryLabels" => $historyFields,
            "arrayHistory" => $arrayHistory,
            "arrayHistorySumField" => $sumValue,
            "arrayHistoryId" => $arrayHistory_ids,
            "action" => $action,
//            "steps"                => $steps,
            "steps" => $pfResultFase,
            "stepLabels" => $stepLabels,
            "stepLinks" => $stepLinks,
            "addParams" => isset($_GET['addParams']) ? $_GET['addParams'] : null,
            "currentState" => isset($currentState) ? $currentState : "all states",
            "alternateLink" => MODUL_PATH . "/Transaksi/viewIncomplete/" . $this->uri->segment(3),
            "alternateLinkCaption" => "incomplete " . $this->configUi[$jenisTr]["label"] . " <span class='glyphicon glyphicon-arrow-right'></span>",
            "addLink" => $addLink,
            "filters" => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "thisPage" => MODUL_PATH . get_class($this) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5),
            // "thisPage"             => MODUL_PATH . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) ,

            "history_canceled" => isset($arrayHistory_canceled) ? $arrayHistory_canceled : array(),
            "history_keterangan" => isset($arrayHistory_keterangan) ? $arrayHistory_keterangan : array(),
        );
        //endregion

        $endtime = microtime(true); // Bottom of page
        $valTimeEnd = $endtime - $starttime;
        //        cekBiru("load time start $starttime ||  end $endtime =>" . "$valTimeEnd");

        $this->load->view("history", $data);
    }
}
