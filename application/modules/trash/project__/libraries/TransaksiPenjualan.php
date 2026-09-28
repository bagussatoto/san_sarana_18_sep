<?php

/**
 * Created by JetBrains PhpStorm.
 * User: azes
 * Date: 5/9/12
 * Time: 11:56 AM
 * To change this template use File | Settings | File Templates.
 */

class TransaksiPenjualan
{
    protected $toko_id;
    protected $configUi;
    protected $configLayout;
    protected $configCore;
    protected $configValues;
    protected $configUiJenis;
    protected $configLayoutJenis;
    protected $configCoreJenis;
    protected $configValuesJenis;
    protected $jenisTr;


    public function getJenisTr()
    {
        return $this->jenisTr;
    }

    public function setJenisTr($jenisTr)
    {
        $this->jenisTr = $jenisTr;
    }

    public function getConfigUi()
    {
        return $this->configUi;
    }

    public function setConfigUi($configUi)
    {
        $this->configUi = $configUi;
    }

    public function getConfigLayout()
    {
        return $this->configLayout;
    }

    public function setConfigLayout($configLayout)
    {
        $this->configLayout = $configLayout;
    }

    public function getConfigCore()
    {
        return $this->configCore;
    }

    public function setConfigCore($configCore)
    {
        $this->configCore = $configCore;
    }

    public function getConfigValues()
    {
        return $this->configValues;
    }

    public function setConfigValues($configValues)
    {
        $this->configValues = $configValues;
    }

    public function getConfigUiJenis()
    {
        return $this->configUiJenis;
    }

    public function setConfigUiJenis($configUiJenis)
    {
        $this->configUiJenis = $configUiJenis;
    }

    public function getConfigLayoutJenis()
    {
        return $this->configLayoutJenis;
    }

    public function setConfigLayoutJenis($configLayoutJenis)
    {
        $this->configLayoutJenis = $configLayoutJenis;
    }

    public function getConfigCoreJenis()
    {
        return $this->configCoreJenis;
    }

    public function setConfigCoreJenis($configCoreJenis)
    {
        $this->configCoreJenis = $configCoreJenis;
    }

    public function getConfigValuesJenis()
    {
        return $this->configValuesJenis;
    }

    public function setConfigValuesJenis($configValuesJenis)
    {
        $this->configValuesJenis = $configValuesJenis;
    }

    public function getTokoId()
    {
        return $this->toko_id;
    }

    public function setTokoId($toko_id)
    {
        $this->toko_id = $toko_id;
    }

    public function __construct()
    {
        // parent::__construct();
        $this->CI =& get_instance();

    }


    public function autoOtorisasi($jenisTr, $no, $stepNum, $stepNumCurrent, $itemsReplacer = array(), $extractedItems_last = array())
    {
        $transaksiID_reference = $masterID = $no;
        $nextStepNum = $stepNum + 1;
        $itemsReplacerQty = array();
        if (sizeof($itemsReplacer) > 0) {
            foreach ($itemsReplacer as $pid => $spec) {
                $itemsReplacerQty[$pid] = $spec["jml"];
            }
        }
        $paramPatchers = $this->CI->config->item('heTransaksi_paramPatchers') != null ? $this->CI->config->item('heTransaksi_paramPatchers') : array();
        $paramForceFillers = $this->CI->config->item('heTransaksi_paramForceFillers') != null ? $this->CI->config->item('heTransaksi_paramForceFillers') : array();
        $this->CI->load->library("FieldCalculator");
        $cal = new FieldCalculator();
        $stepNowParameter = array();
        $this->CI->load->model("MdlPenjualanTransaksi");
        $tr = new MdlPenjualanTransaksi();
        $tr->addFilter($tr->getTableNames()["main"] . ".id in (" . implode(",", explode("-", $no)) . ")");
        $tr->addFilterJoin($tr->getTableNames()["detail"] . ".trash='0'");
        $tmpTr = $tr->lookupJoined();
        showLast_query("biru");
        arrPrintKuning($tmpTr);
        if (sizeof($tmpTr) > 0) {
            $extractedItems = array();//==untuk urusan update transaksi referer
            $validItems = array();
            $validItemSends = array();
            $validItemReqCancels = array();
            $validItemCancels = array();
            $validItemPreCancels = array();
            $validItemSents = array();
            foreach ($tmpTr as $row) {
//                arrPrintPink($row);
                if ($row->qty_kredit > 0) {
                    if (!isset($validItems[$row->produk_id])) {
                        $validItems[$row->produk_id] = 0;
                    }
                    if (!isset($validItemSends[$row->produk_id])) {
                        $validItemSends[$row->produk_id] = 0;
                    }
                    if (!isset($validItemCancels[$row->produk_id])) {
                        $validItemCancels[$row->produk_id] = 0;
                    }
                    if (!isset($validItemReqCancels[$row->produk_id])) {
                        $validItemReqCancels[$row->produk_id] = 0;
                    }
                    if (!isset($validItemPackeds[$row->produk_id])) {
                        $validItemPackeds[$row->produk_id] = 0;
                    }
                    if (!isset($validItemPreCancels[$row->produk_id])) {
                        $validItemPreCancels[$row->produk_id] = 0;
                    }

                    $validItems[$row->produk_id] += isset($row->qty_kredit) ? $row->qty_kredit : 0;
                    $validItemSends[$row->produk_id] += isset($arrTmp__['582spd'][$row->produk_id]) ? $arrTmp__['582spd'][$row->produk_id] : 0;
                    $validItemCancels[$row->produk_id] += isset($row->cancel_qty) ? $row->cancel_qty : 0;
                    $validItemReqCancels[$row->produk_id] += isset($row->req_cancel_qty) ? $row->req_cancel_qty : 0;
                    $validItemPreCancels[$row->produk_id] += isset($arrPreTmp__['1982'][$row->produk_id]) ? $arrPreTmp__['1982'][$row->produk_id] : 0;
                    $validItemPackeds[$row->produk_id] += isset($arrTmp__['582pkd'][$row->produk_id]) ? $arrTmp__['582pkd'][$row->produk_id] : 0;

                    if (!isset($extractedItems[$row->produk_id])) {
                        $extractedItems[$row->produk_id] = array();
                    }
                    $extractedItems[$row->produk_id][$row->id_detail] = array(
//                        "id" => $row->id_detail,
                        "id" => $row->produk_id,
                        "produk_id" => $row->produk_id,
                        "qty" => $row->produk_ord_jml,
                        "valid_qty" => $row->qty_kredit,
                        "transaksi_id" => $row->transaksi_id,
                        "packed_qty" => isset($arrTmp__['582pkd'][$row->produk_id]) ? $arrTmp__['582pkd'][$row->produk_id] : 0,
                        "sent_qty" => isset($arrTmp__['582spd'][$row->produk_id]) ? $arrTmp__['582spd'][$row->produk_id] : 0,
                        "req_cancel_qty" => isset($arrPreTmp__['1982'][$row->produk_id]) ? $arrPreTmp__['1982'][$row->produk_id] : 0,
                        "cancel_qty" => $row->cancel_qty,
                        "outstanding" => $row->produk_ord_jml - ($row->produk_ord_jml - $row->qty_kredit),
                    );
                }
            }
            $this->jenisTr = $tmpTr[0]->jenis_master;
            $masterID = $tmpTr[0]->id_master;
            $topID = $tmpTr[0]->id_top;
            $tmpNomorNota = $tmpTr[0]->nomer;
            $origJenis = $tmpTr[0]->jenis_master;
            $pengirimID = $tmpTr[0]->pengirim_id;
            $pengirimName = $tmpTr[0]->pengirim_nama;
            //--------------------------------
            $gudangStatusJenis = $tmpTr[0]->gudang_status_jenis;
            $cabangTujuanID = $tmpTr[0]->cabang_id;

            $trID = $tmpTr[0]->transaksi_id;
            $cCode = "_TR_" . $this->jenisTr;
            if (isset($sessionData[$cCode])) {
                $sessionData[$cCode] = null;
                unset($sessionData[$cCode]);
            }
            //region session init
            if (!isset($sessionData[$cCode])) {
                $sessionData[$cCode] = array(
                    "items" => array(),
                    "main" => array(),
                );
            }
            if (!isset($sessionData[$cCode]['main'])) {
                $sessionData[$cCode]['main'] = array();
            }
            if (!isset($sessionData[$cCode]['items'])) {
                $sessionData[$cCode]['items'] = array();
            }
            //endregion

            $sessionData[$cCode]['extractedItems'] = $extractedItems;


            $configUiMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiUi");
            $configCoreMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiCore");
            $configLayoutMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiLayout");
            $configValuesMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiValues");
            $modul_transaksi = $this->CI->config->item("heTransaksi_ui")[$this->jenisTr]["modul"];

            $configUiMasterModulOrigJenis = loadConfigModulJenis_he_misc($origJenis, "coTransaksiUi");
            $configCoreMasterModulOrigJenis = loadConfigModulJenis_he_misc($origJenis, "coTransaksiCore");
            $configLayoutMasterModulOrigJenis = loadConfigModulJenis_he_misc($origJenis, "coTransaksiLayout");


            $jenisTrTarget = isset($configUiMasterModulJenis["steps"][$stepNum]["target"]) ? $configUiMasterModulJenis["steps"][$stepNum]["target"] : NULL;
            $detailValuesConfig = isset($configCoreMasterModulJenis['tableIn']['detailValues']) ? $configCoreMasterModulJenis['tableIn']['detailValues'] : array();
            $additionalData = isset($configUiMasterModulJenis["addDetailData"][$stepNum]) ? $configUiMasterModulJenis["addDetailData"][$stepNum] : array();
            //--------------------------------
            $tableInMaster = isset($configCoreMasterModulJenis['tableIn']['master']) ? $configCoreMasterModulJenis['tableIn']['master'] : array();
            $tableInDetail = isset($configCoreMasterModulJenis['tableIn']['detail']) ? $configCoreMasterModulJenis['tableIn']['detail'] : array();
            //--------------------------------

            $totalSteps = sizeof($configUiMasterModulJenis['steps']);
            //==references, previous entry
            $prevProp = array(
                "id" => $tmpTr[0]->transaksi_id,
                "jenis" => $tmpTr[0]->jenis,
                "nomer" => $tmpTr[0]->nomer,
            );
            //------
            $stepNowParameter = array(
                "next_step_code" => $tmpTr[0]->next_step_code,
                "next_step_label" => $tmpTr[0]->next_step_label,
                "next_group_code" => $tmpTr[0]->next_group_code,
                "next_step_num" => $tmpTr[0]->next_step_num,
                "step_current" => $tmpTr[0]->step_current,
            );
//            $tmpVal_main = $tr->lookupMainValuesByTransID($trID)->result();
            $tmpVal_main = array();
            $tmpVal_detail = $tr->lookupDetailValuesByTransID($trID)->result();
            $mainValues = array();
            if (sizeof($tmpVal_main) > 0) {
                foreach ($tmpVal_main as $row) {
                    $mainValues[$row->key] = $row->value;
                }
            }
            $detailValues = array();
            if (sizeof($tmpVal_detail) > 0) {
                foreach ($tmpVal_detail as $row) {
                    $detailValues[$row->produk_id][$row->key] = $row->value;
                }
            }

            $main = array();
            $items = array();
            $prevIDs = array();
            $prevNos = array();
            foreach ($tmpTr as $row) {
                //----
                $main = (array)$row;
//                    $items[$row->produk_id] = (array)$row;
                //----
                $items[$row->produk_id] = array(
                    "id" => $row->produk_id,
                    "nama" => $row->produk_nama,
                    "jml" => $row->produk_ord_jml,
                    "harga" => $row->produk_ord_hrg,
                    "valid_qty" => $row->qty_kredit,
                    "transaksi_id" => $row->transaksi_id,
                    "nomer" => $row->nomer,
                );
                if ($row->qty_kredit > 0) {
                    cekHitam("ok lanjut");
                }
                else {
                    if (isset($sessionData[$cCode]['items'][$row->produk_id])) {
                        matiHere("Followed up already. Please close and refresh your browser " . $row->produk_nama . " " . $row->produk_id);//kalo session active ya harus dimatiin biar gak dobel
                    }
                }
                if (!in_array($row->transaksi_id, $prevIDs)) {
                    $prevIDs[] = $row->transaksi_id;
                }
                if (!in_array($row->nomer, $prevNos)) {
                    $prevNos[] = $row->nomer;
                }
                if (sizeof($detailValuesConfig) > 0) {
                    foreach ($detailValuesConfig as $key => $src) {
                        echo "<script>top.writeProgress('$key akan ambil nilai dari $src');</script>";
                        if (isset($detailValues[$row->produk_id][$key])) {
                            $items[$row->produk_id][$key] = $detailValues[$row->produk_id][$key];
                        }
                        else {
                            if (isset($row->$key)) {
                                $items[$row->produk_id][$key] = $row->$key;
                            }
                        }
                        echo "dan sekarang nilainya: " . $items[$row->produk_id][$key] . "<br>";
                        echo "<script>top.writeProgress('dan sekarang nilainya: " . $items[$row->produk_id][$key] . "');</script>";
                    }
                }

                //-------
                if (sizeof($tableInMaster) > 0) {
                    foreach ($tableInMaster as $mKey => $mVal) {
                        $main[$mVal] = isset($row->$mKey) ? $row->$mKey : "";
                    }
                }
                //-------
                if (sizeof($tableInDetail) > 0) {
                    foreach ($tableInDetail as $mKey => $mVal) {
                        if ($mVal != NULL) {
//                                $items[$row->produk_id][$mVal] = isset($row->$mKey) ? $row->$mKey : "";
                        }
                    }
                }
                //-------
            }

            //region take from registries
            $trr = new MdlPenjualanTransaksi();

            $trr->setFilters(array());
            $trrTmp = $trr->lookupMainElementsByTransID($no)->result();
            if (sizeof($trrTmp) > 0) {
                foreach ($trrTmp as $trrSpec) {
                    $mainElements[$trrSpec->name] = (array)$trrSpec;
                }
            }

            $trr->setFilters(array());
//            $trr->addFilter("transaksi_id in (" . implode(",", explode("-", $no)) . ")");
//            $tmpReg = $trr->lookupDataRegistries()->result();
            $tempReg = $trr->lookUpAllChild($no);
            cekKuning($this->CI->db->last_query());
//            $main = array();
            $items = array();
            $items2 = array();
            $items2_sum = array();
            $items3 = array();
            $items3_sum = array();
            $items4 = array();
            $items4_sum = array();
            $items4 = array();
            $items6_sum = array();
            $items6 = array();
            $items7 = array();
            $items7_sum = array();
            $items9_sum = array();
            $items10_sum = array();
            $rsltItems = array();
            $rsltItems2 = array();
            $masterGates = array();
            $childGates = array();
            $childGates2 = array();
            $childGates2_sum = array();
            $childGatesRsltItems = array();
            $childGatesRsltItems2 = array();
            $masterTableInParams = array();
            $childTableInParams = array();
            $childTableInParamsRsltItems = array();
            $childTableInParamsRsltItems2 = array();
            $masterTableInValueParams = array();
            $childTableInValueParams = array();
            $childTableInValueParamsRsltItems = array();
            $childTableInValueParamsRsltItems2 = array();
            $masterAddValues = array();
            $masterAddFields = array();
//            $mainElements = array();
            $mainInputs = array();
            $itemsKomposisi = array();
            if (sizeof($tempReg) > 0) {
                foreach ($tempReg as $reg => $valuePair) {
//                    cekHere("[$reg]");
//                    arrPrintPink($valuePair);
                    switch ($reg) {
//                        case "main"://
//                            $main = $main + $valuePair;
//                            break;
                        case "items"://
                            $items = $items + $valuePair;
                            break;
                        case "items2"://
                            $items2 = $items2 + $valuePair;
                            break;
                        case "rsltItems"://
                            $rsltItems = $rsltItems + $valuePair;
                            break;
                        case "rsltItems2"://
                            $rsltItems2 = $rsltItems2 + $valuePair;
                            break;
                        case "items2_sum"://
                            $items2_sum = $items2_sum + $valuePair;
                            break;
                        case "items3"://
                            $items3 = $items3 + $valuePair;
                            break;
                        case "items3_sum"://
                            $items3_sum = $items3_sum + $valuePair;
                            break;
                        case "items4_sum"://
                            $items4_sum = $items4_sum + $valuePair;
                            break;
                        case "items5_sum"://
                            $items5_sum = $items5_sum + $valuePair;
                            break;
                        case "items6_sum"://
                            $items6_sum = $items6_sum + $valuePair;
                            break;
                        case "items7_sum"://
                            $items7_sum = $items7_sum + $valuePair;
                            break;
                        case "items8_sum"://
                            $items8_sum = $items8_sum + $valuePair;
                            break;
                        case "items9_sum"://
                            $items9_sum = $items9_sum + $valuePair;
                            break;
                        case "items10_sum"://
                            $items10_sum = $items10_sum + $valuePair;
                            break;
                        case "items_komposisi"://
                            $itemsKomposisi = $valuePair;
                            break;
                    }
                }

            }
            else {
                die("Cannot read the registry entries from $masterID!");
            }
            //endregion
//arrPrintWebs($main);
//mati_disini(__LINE__);

//            $main['ppnFactor'] = 11;


            $masterReplacers = array(
                "jenisTrMaster" => $this->jenisTr,
                "jenisTrTop" => $main['jenis_top'],
                "harga" => 0,
                "masterID" => $masterID,
            );
            foreach ($masterReplacers as $key => $src) {
                $main[$key] = $src;
                $mainValues[$key] = $src;
                $masterGates[$key] = $src;
            }
            if (sizeof($itemsReplacerQty) > 0) {
                foreach ($itemsReplacerQty as $pid => $qty) {
                    if (array_key_exists($pid, $items)) {
                        $items[$pid]["qty"] = $qty;
                        $items[$pid]["jml"] = $qty;
                    }
                }
            }
            if (sizeof($items) > 0) {
                foreach ($items as $xid => $iSpec) {
                    if (isset($items6[$xid]) && sizeof($items6[$xid]) > 0) {
                        foreach ($items6[$xid] as $xid6 => $iSpec) {
                            $id = $iSpec['id'];
                            if (sizeof($itemsKomposisi[$xid]) > 0) {
                                if (array_key_exists($id, $itemsKomposisi[$xid])) {
                                    $items6[$xid][$xid6]['jml'] = $itemsKomposisi[$xid][$id]["jml"] * $validItems[$xid];
                                    $items6[$xid][$xid6]['qty'] = $itemsKomposisi[$xid][$id]["jml"] * $validItems[$xid];
                                    $items6[$xid][$xid6]['max_jml'] = $items6[$xid][$xid6]['qty'];
                                }
                            }
                        }
                    }
                }
            }

            //region session-swapper
            $main["pengirimID"] = $pengirimID;
            $main["pengirimName"] = $pengirimName;
            $swappers = array(
                "main" => $main,
                "items" => $items,
                "items2" => $items2,
                "items2_sum" => $items2_sum,
                "items3" => $items3,
                "items3_sum" => $items3_sum,
                "items4" => $items4,
                "items4_sum" => $items4_sum,
                "items6" => $items6,
                "items6_sum" => $items6_sum,
                "items7" => $items7,
                "items7_sum" => $items7_sum,
                "items9_sum" => $items9_sum,
                "items10_sum" => $items10_sum,
                "items_child" => isset($itemChildData) ? $itemChildData : array(),
                "rsltItems" => isset($rsltItems) ? $rsltItems : array(),
                "rsltItems2" => isset($rsltItems2) ? $rsltItems2 : array(),
                "extractedItems" => isset($extractedItems) ? $extractedItems : array(),
                "extractedItems_last" => isset($extractedItems_last) ? $extractedItems_last : array(),
                "tableIn_master" => isset($masterTableInParams) ? $masterTableInParams : array(),
                "tableIn_detail" => isset($childTableInParams) ? $childTableInParams : array(),
                "tableIn_detail_rsltItems" => isset($childTableInParamsRsltItems) ? $childTableInParamsRsltItems : array(),
                "tableIn_detail_rsltItems2" => isset($childTableInParamsRsltItems2) ? $childTableInParamsRsltItems2 : array(),
                "tableIn_master_values" => isset($masterTableInValueParams) ? $masterTableInValueParams : array(),
                "tableIn_detail_values" => isset($childTableInValueParams) ? $childTableInValueParams : array(),
                "tableIn_detail_values_rsltItems" => isset($childTableInValueParamsRsltItems) ? $childTableInValueParamsRsltItems : array(),
                "tableIn_detail_values_rsltItems2" => isset($childTableInValueParamsRsltItems2) ? $childTableInValueParamsRsltItems2 : array(),
                "main_add_values" => isset($masterAddValues) ? $masterAddValues : array(),
                "main_add_fields" => isset($masterAddFields) ? $masterAddFields : array(),
                "main_elements" => isset($mainElements) ? $mainElements : array(),
                "main_inputs" => isset($mainInputs) ? $mainInputs : array(),
                "extSteps" => isset($extSteps) ? $extSteps : array(),
                "paySrcs" => isset($paySrcs) ? $paySrcs : array(),
                "lockerPayment" => isset($tempBtnUndo) ? $tempBtnUndo : array(),
                "items_komposisi" => isset($itemsKomposisi) ? $itemsKomposisi : array(),
            );
            foreach ($swappers as $targetVar => $src) {
                $sessionData[$cCode][$targetVar] = $src;

            }
            //endregion


            // region copy gerbang serial dari distribusi
            $shoppingCartCopySerialNumber = isset($configUiMasterModulJenis["shoppingCartCopySerialNumber"][$stepNum]) ? $configUiMasterModulJenis["shoppingCartCopySerialNumber"][$stepNum] : array();
            if (sizeof($shoppingCartCopySerialNumber) > 0) {
                $statusGudangConfig = $shoppingCartCopySerialNumber["statusGudang"];
                $copyGateConfig = $shoppingCartCopySerialNumber["copyGate"];
                $copyJenisConfig = $shoppingCartCopySerialNumber["copyJenis"];
                if ($gudangStatusJenis == $statusGudangConfig) {
                    $trs = new MdlPenjualanTransaksi();
                    $trs->addFilter("jenis='$copyJenisConfig'");
                    $trs->addFilter("reference_id_top='$topID'");
                    $trsTmp = $trs->lookupAll()->result();
                    showLast_query("biru");
                    $trsID = $trsTmp[0]->id;

                    $trs = new MdlPenjualanTransaksi();
                    $trs->setFilters(array());
                    $trs->setJointSelectFields($copyGateConfig);
                    $trs->addFilter("transaksi_id='$trsID'");
                    $tmpReg = $trs->lookupDataRegistries()->result();
                    showLast_query("biru");
                    if (sizeof($tmpReg) > 0) {
                        foreach ($tmpReg as $row) {
                            foreach ($row as $key_reg => $val_reg) {
                                if ($val_reg == null) {
                                    $val_reg = blobEncode(array());
                                }
                                $sessionData[$cCode][$key_reg] = blobDecode($val_reg);
                            }
                        }
                    }
                }

            }
            // endregion copy gerbang serial dari distribusi


            $ppnFactor = isset($sessionData[$cCode]["main"]["ppnFactor"]) ? $sessionData[$cCode]["main"]["ppnFactor"] : matiHere("gagal menghitung ppn silahkan refresh atau relogin");
//arrPrint($sessionData[$cCode]["items"]);
            $this->CI->load->helper("he_value_builder");
//            resetValues($this->jenisTr);
            $sessionData[$cCode] = fillValues_he_value_builder_ns($this->jenisTr, $stepNumCurrent, $stepNum, $configCoreMasterModulJenis, $configUiMasterModulJenis, $configValuesMasterModulJenis, $ppnFactor, $sessionData[$cCode]);

            $sessionData[$cCode]['extractedItems_last'] = $extractedItems_last;

            //region pembulatan replacer disini
            $injectBulat = isset($configCoreMasterModulJenis['valuePembulatan'][$stepNum]) ? $configCoreMasterModulJenis['valuePembulatan'][$stepNum] : array();
            if (sizeof($injectBulat) > 0) {
                echo "<script>top.writeProgress('PEMBULATAN', 'HEAD');</script>";
                //            arrPrint($injectBulat);
                $selectedSource = $injectBulat['source'];
                $injectSource = makeDppBulat($sessionData[$cCode]['main'][$selectedSource]);
                foreach ($injectBulat['replacer'] as $k => $fields) {
                    $sessionData[$cCode]['main'][$fields] = $injectSource[$k];
                    echo "<script>top.writeProgress('PEMBULATAN ($fields)');</script>";
                }

            }
            //endregion

            cekMerah(":: MEMULAI PRE-PROCC ITEMS...");
            $ppnFactor = isset($sessionData[$cCode]["main"]["ppnFactor"]) ? $sessionData[$cCode]["main"]["ppnFactor"] : matiHere("gagal menghitung ppn silahkan refresh atau relogin");

            //region pre-processors (item)
            if (isset($configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['detail'])) {
                $iterator = isset($configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['detail']) ? $configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['detail'] : array();
                $itemNumLabels = isset($configUiMasterModulJenis['shoppingCartNumFields'][$stepNum]) ? $configUiMasterModulJenis['shoppingCartNumFields'][$stepNum] : array();
                echo "ITEM NUM LABELS";

                if (sizeof($iterator) > 0) {
                    echo "<script>top.writeProgress('PERSIAPAN PRE-PROCESSOR...', 'HEAD');</script>";
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        echo __LINE__ . " :: sub-preproc: $comName, initializing values <br>";
                        if (isset($sessionData[$cCode][$srcGateName]) && count($sessionData[$cCode][$srcGateName]) > 0) {
                            foreach ($sessionData[$cCode][$srcGateName] as $xid => $dSpec) {
                                $tmpOutParams[$cCtr] = array();
                                $id = $xid;
                                $subParams = array();
                                if (isset($tComSpec['static'])) {
                                    foreach ($tComSpec['static'] as $key => $value) {

                                        $realValue = makeValue($value, $sessionData[$cCode][$srcGateName][$id], $sessionData[$cCode][$srcGateName][$id], 0);
                                        $subParams['static'][$key] = $realValue;

                                    }

                                    if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                        foreach ($paramPatchers[$comName] as $k => $v) {
                                            if (!isset($subParams['static'][$k])) {
                                                $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                            }
                                        }
                                    }
                                    if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                                        $jenis = $sessionData[$cCode]['main']['jenis'];
                                        foreach ($paramForceFillers[$comName] as $k => $v) {
                                            $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                        }
                                    }

                                    $subParams['static']["fulldate"] = date("Y-m-d");
                                    $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                    $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . "";
                                }
                                if (sizeof($subParams) > 0) {

                                    $tmpOutParams[$cCtr][] = $subParams;
                                }

                                $comName = $tComSpec['comName'];
                                $srcGateName = $tComSpec['srcGateName'];
                                $srcRawGateName = $tComSpec['srcRawGateName'];
                                $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();

                                echo "sub preproc #: $comName, sending values <br>";

                                $mdlName = "Pre" . ucfirst($comName);
                                $this->CI->load->model("Preprocs/" . $mdlName);
                                $m = new $mdlName($resultParams);
                                if (sizeof($tmpOutParams[$cCtr]) > 0) {
                                    $tobeExecuted = true;
                                }
                                else {
                                    $tobeExecuted = false;
                                }

                                //                                arrprint($tmpOutParams);

                                //                                matiHEre(__LINE__ . " :: preproc itemsss");

                                if ($tobeExecuted) {
                                    $m->pair($masterID, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                    $gotParams = $m->exec();
                                    cekHitam(":: PRE-PROCC -> GOTNAME, ITERATING...");
                                    arrprint($gotParams);
                                    if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                        foreach ($gotParams as $gateName => $paramSpec) {

                                            if (!isset($sessionData[$cCode][$gateName])) {
                                                $sessionData[$cCode][$gateName] = array();
                                                //                                    cekhijau("building the session: $gateName");
                                            }
                                            else {
                                                //                                    cekhijau("NOT building the session: $gateName");
                                            }

                                            foreach ($paramSpec as $id => $gSpec) {
                                                //                                        $id = $gSpec['id'];
                                                if (!isset($sessionData[$cCode][$gateName][$id])) {
                                                    $sessionData[$cCode][$gateName][$id] = array();
                                                }

                                                if (isset($sessionData[$cCode][$gateName][$id])) {
                                                    if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                        foreach ($gSpec as $key => $val) {
                                                            $sessionData[$cCode][$gateName][$id][$key] = $val;
                                                        }
                                                    }
                                                }
                                                //==inject gotParams to child gate
                                                if ($gateName == $srcGateName) {
                                                    if (isset($sessionData[$cCode][$srcGateName][$id])) {
                                                        if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                            foreach ($gSpec as $key => $val) {
                                                                $sessionData[$cCode][$srcGateName][$id][$key] = $val;
                                                            }
                                                        }
                                                    }
                                                }

                                                //cekMerah("REBUILDING VALUES..");
                                                if (sizeof($itemNumLabels) > 0) {
                                                    //cekHijau("REBUILDING SUBS FOR ITEMS");
                                                    foreach ($itemNumLabels as $key => $label) {
                                                        //cekHere("$id === $key => $label");
                                                        $sessionData[$cCode][$gateName][$id]['sub_' . $key] = ($sessionData[$cCode][$gateName][$id]['jml'] * $sessionData[$cCode][$gateName][$id][$key]);
                                                        //                                        die();
                                                    }
                                                }
                                            }
                                            //                                    arrPrint($sessionData[$cCode][$gateName]);die();
                                        }
                                    }

                                }
                                else {
                                    cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                                }
                            }
                            $this->CI->load->helper("he_value_builder");
                            $sessionData[$cCode] = fillValues_he_value_builder_ns($this->jenisTr, $stepNumCurrent, $stepNum, $configCoreMasterModulJenis, $configUiMasterModulJenis, $configValuesMasterModulJenis, $ppnFactor, $sessionData[$cCode]);
                        }

                    }
                }
                else {
                    //cekKuning("sub-preproc is not set");
                }


                $this->CI->load->helper("he_value_builder");
                $sessionData[$cCode] = fillValues_he_value_builder_ns($this->jenisTr, $stepNumCurrent, $stepNum, $configCoreMasterModulJenis, $configUiMasterModulJenis, $configValuesMasterModulJenis, $ppnFactor, $sessionData[$cCode]);
            }
            else {
                echo("no processor defined. skipping preprocessor..<br>");
            }


            //ini untuk preproc dari multidimensional array contoh items2,items6
            if (isset($configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['sub_detail'])) {
                $iterator = isset($configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['sub_detail']) ? $configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['sub_detail'] : array();
                $itemNumLabels = isset($configUiMasterModulJenis['shoppingCartNumFields'][$stepNum]) ? $configUiMasterModulJenis['shoppingCartNumFields'][$stepNum] : array();
                echo "ITEM NUM LABELS";

                if (sizeof($iterator) > 0) {
                    echo "<script>top.writeProgress('PERSIAPAN PRE-PROCESSOR...', 'HEAD');</script>";
                    foreach ($iterator as $cCtrX => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        echo __LINE__ . " :: sub-preproc: $comName, initializing values <br>";
                        if (isset($sessionData[$cCode][$srcGateName]) && count($sessionData[$cCode][$srcGateName]) > 0) {
                            foreach ($sessionData[$cCode][$srcGateName] as $xiID => $aDSpec) {
                                $cCtr = 0;
                                foreach ($aDSpec as $xid => $dSpec) {
                                    $cCtr++;
                                    $tmpOutParams[$cCtr] = array();
                                    $id = $xid;
                                    $subParams = array();
                                    if (isset($tComSpec['static'])) {
                                        foreach ($tComSpec['static'] as $key => $value) {

                                            $realValue = makeValue($value, $dSpec, $dSpec, 0);
                                            $subParams['static'][$key] = $realValue;

                                        }
                                        if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                            foreach ($paramPatchers[$comName] as $k => $v) {
                                                if (!isset($subParams['static'][$k])) {
                                                    $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                                }
                                            }
                                        }
                                        if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                                            $jenis = $sessionData[$cCode]['main']['jenis'];
                                            foreach ($paramForceFillers[$comName] as $k => $v) {
                                                $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                            }
                                        }
                                        $subParams['static']["fulldate"] = date("Y-m-d");
                                        $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                        $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . "";
                                    }
                                    if (sizeof($subParams) > 0) {

                                        $tmpOutParams[$cCtr][] = $subParams;
                                    }
                                    $comName = $tComSpec['comName'];
                                    $srcGateName = $tComSpec['srcGateName'];
                                    $srcRawGateName = $tComSpec['srcRawGateName'];
                                    $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
                                    echo "sub preproc #: $comName, sending values <br>";
                                    $mdlName = "Pre" . ucfirst($comName);
                                    $this->CI->load->model("Preprocs/" . $mdlName);
                                    $m = new $mdlName($resultParams);
                                    if (sizeof($tmpOutParams[$cCtr]) > 0) {
                                        $tobeExecuted = true;
                                    }
                                    else {
                                        $tobeExecuted = false;
                                    }
                                    if ($tobeExecuted) {
                                        $m->pair($masterID, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                        $gotParams = $m->exec();
                                        cekHitam(":: PRE-PROCC -> GOTNAME, ITERATING...");
                                        if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                            foreach ($gotParams as $gateName => $paramSpec) {

                                                if (!isset($sessionData[$cCode][$gateName])) {
                                                    $sessionData[$cCode][$gateName] = array();
                                                    //                                    cekhijau("building the session: $gateName");
                                                }
                                                else {
                                                    //                                    cekhijau("NOT building the session: $gateName");
                                                }

                                                foreach ($paramSpec as $idx => $gSpec) {
                                                    //                                        $id = $gSpec['id'];
                                                    if (!isset($sessionData[$cCode][$gateName][$xiID][$idx])) {
                                                        $sessionData[$cCode][$gateName][$xiID][$idx] = array();
                                                    }

                                                    if (isset($sessionData[$cCode][$gateName][$xiID][$idx])) {
                                                        if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                            foreach ($gSpec as $key => $val) {
                                                                $sessionData[$cCode][$gateName][$xiID][$idx][$key] = $val;
                                                            }
                                                        }
                                                    }
                                                    //==inject gotParams to child gate
                                                    if ($gateName == $srcGateName) {
                                                        if (isset($sessionData[$cCode][$srcGateName][$xiID][$idx])) {
                                                            if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                                foreach ($gSpec as $key => $val) {
                                                                    $sessionData[$cCode][$srcGateName][$xiID][$idx][$key] = $val;
                                                                }
                                                            }
                                                        }
                                                    }

                                                    //cekMerah("REBUILDING VALUES..");
                                                    if (sizeof($itemNumLabels) > 0) {
                                                        matiHere(__LINE__);
                                                        //cekHijau("REBUILDING SUBS FOR ITEMS");
                                                        foreach ($itemNumLabels as $key => $label) {
                                                            //cekHere("$id === $key => $label");
                                                            $sessionData[$cCode][$gateName][$xiID][$idx]['sub_' . $key] = ($sessionData[$cCode][$gateName][$xiID][$idx]['jml'] * $sessionData[$cCode][$gateName][$xiID][$idx][$key]);
                                                            //                                        die();
                                                            arrprint($sessionData[$cCode][$gateName][$xiID][$idx]);
                                                        }
                                                    }
                                                    else {
                                                        //                                                    matiHere(__LINE__.":: ".$this->jenisTr." step::".$stepNum);
                                                    }
                                                }
                                                //                                    arrPrint($sessionData[$cCode][$gateName]);die();
                                            }
                                        }
                                    }
                                    else {
                                        cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                                    }
                                }
                            }
                            $this->CI->load->helper("he_value_builder");
                            $sessionData[$cCode] = fillValues_he_value_builder_ns($this->jenisTr, $stepNumCurrent, $stepNum, $configCoreMasterModulJenis, $configUiMasterModulJenis, $configValuesMasterModulJenis, $ppnFactor, $sessionData[$cCode]);

                        }

                    }
                }
                else {
                    //cekKuning("sub-preproc is not set");
                }
            }
            //endregion

            //region prepoc subdetail multi dimensional array /array 2 tinggkat
            $iterator = isset($configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['sub_detail']) ? $configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['sub_detail'] : array();
            if (sizeof($iterator) > 0) {
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    echo "sub-postProcessor: $comName, initializing values <br>";
                    echo "<script>top.writeProgress('MENYIAPKAN DATA SUB-PROCESSORS UNTUK DIKIRIM...', 'head');</script>";
                    $tmpOutParams[$cCtr] = array();
                    foreach ($sessionData[$cCode][$srcGateName] as $cnt => $dDSpec) {
                        foreach ($dDSpec as $idOP_spec => $dSpec) {
                            $subParams = array();
                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {

                                    $realValue = makeValue($value, $sessionData[$cCode][$srcGateName][$cnt][$idOP_spec], $sessionData[$cCode][$srcGateName][$cnt][$idOP_spec], 0);
                                    $subParams['loop'][$key] = $realValue;

                                }
                            }
                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {

                                    $realValue = makeValue($value, $sessionData[$cCode][$srcGateName][$cnt][$idOP_spec], $sessionData[$cCode][$srcGateName][$cnt][$idOP_spec], 0);
                                    $subParams['static'][$key] = $realValue;
                                    cekBiru("$key diisi dengan $realValue");

                                }
                                if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                    foreach ($paramPatchers[$comName] as $k => $v) {
                                        if (!isset($subParams['static'][$k])) {
                                            $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                        }
                                    }
                                }
                                if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                                    $jenis = $sessionData[$cCode]['main']['jenis'];
                                    foreach ($paramForceFillers[$comName] as $k => $v) {
                                        $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                        cekorange(":: $k diisikan dengan " . $subParams['static'][$k]);
                                    }
                                }
                                $subParams['static']["fulldate"] = date("Y-m-d");
                                $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . "";
                            }
                            if (sizeof($subParams) > 0) {
                                $tmpOutParams[$cCtr][] = $subParams;
                            }
                        }
                        echo "<script>top.writeProgress('" . isset($subParams['static']['name']) ? $subParams['static']['name'] : "" . " " . isset($subParams['static']['extern_nama']) ? $subParams['static']['extern_nama'] : "" . " " . isset($subParams['static']['nama']) ? $subParams['static']['nama'] : "" . "');</script>";
                    }
                }
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    echo "sub-postProcessor: $comName, sending values <br>";
                    echo "<script>top.writeProgress('SENDING SUB-PROCESSORS ($comName)...', 'head');</script>";
                    $mdlName = "Com" . ucfirst($comName);
                    $this->CI->load->model("Coms/" . $mdlName);
                    $m = new $mdlName();
                    if (count($tmpOutParams[$cCtr]) > 0) {
                        $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        cekBiru($this->CI->db->last_query());
                    }


                }
            }

            //endregion

            //region pre-processors (master)
            if (isset($configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['master'])) {
                $iterator = isset($configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['master']) ? $configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['master'] : array();
                $itemNumLabels = isset($configUiMasterModulJenis['shoppingCartNumFields']) ? $configUiMasterModulJenis['shoppingCartNumFields'] : array();

                echo "ITEM NUM LABELS";

                if (sizeof($iterator) > 0) {
                    echo "<script>top.writeProgress('PERSIAPAN PRE-PROCESSOR...', 'HEAD');</script>";
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
                        $switchResultParams = isset($tComSpec['switchResultParams']) ? $tComSpec['switchResultParams'] : false;

                        echo "master-preproc: $comName, initializing values <br>";
                        $tmpOutParams[$cCtr] = array();
                        $subParams = array();
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {

                                $realValue = makeValue($value, $sessionData[$cCode][$srcGateName], $sessionData[$cCode][$srcGateName], 0);
                                $subParams['static'][$key] = $realValue;

                            }
                            if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                foreach ($paramPatchers[$comName] as $k => $v) {
                                    if (!isset($subParams['static'][$k])) {
                                        $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                    }
                                }
                            }
                            if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                                $jenis = $sessionData[$cCode]['main']['jenis'];
                                foreach ($paramForceFillers[$comName] as $k => $v) {
                                    $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                }
                            }
                            $subParams['static']["fulldate"] = date("Y-m-d");
                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                            $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . "";
                        }
                        if (sizeof($subParams) > 0) {
                            $tmpOutParams[$cCtr] = $subParams;
                        }
                        $mdlName = "Pre" . ucfirst($comName);
                        $this->CI->load->model("Preprocs/" . $mdlName);
                        $m = new $mdlName($resultParams);
                        if (sizeof($tmpOutParams[$cCtr]) > 0) {
                            $tobeExecuted = true;
                        }
                        else {
                            $tobeExecuted = false;
                        }
                        if ($tobeExecuted) {
                            $m->pair($masterID, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $gotParams = $m->exec();
                            cekbiru("gotparams dari $comName");
                            arrprint($gotParams);
                            if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                cekhijau("ada gotparam, sekarang mau replace");
                                foreach ($gotParams as $gateName => $gSpec) {

                                    if ($switchResultParams == true) {
                                        foreach ($gSpec as $id => $ggSpec) {
                                            if (!isset($sessionData[$cCode][$gateName][$id])) {
                                                $sessionData[$cCode][$gateName][$id] = array();
                                            }
                                            if (isset($sessionData[$cCode][$gateName][$id])) {
                                                if (is_array($ggSpec) && sizeof($ggSpec) > 0) {
                                                    foreach ($ggSpec as $key => $val) {
                                                        $sessionData[$cCode][$gateName][$id][$key] = $val;
                                                    }
                                                }
                                            }
                                            //cekMerah("REBUILDING VALUES..");
                                            if (sizeof($itemNumLabels) > 0) {
                                                //cekHijau("REBUILDING SUBS FOR ITEMS");
                                                foreach ($itemNumLabels as $key => $label) {
                                                    //cekHere("$id === $key => $label");
                                                    if (isset($sessionData[$cCode][$gateName][$id][$key])) {
                                                        $sessionData[$cCode][$gateName][$id]['sub_' . $key] = ($sessionData[$cCode][$gateName][$id]['jml'] * $sessionData[$cCode][$gateName][$id][$key]);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    else {

                                        if (isset($sessionData[$cCode]['main'])) {
                                            if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                foreach ($gSpec as $key => $val) {
                                                    cekbiru("injecting param $key with $val");
                                                    $sessionData[$cCode]['main'][$key] = $val;
                                                }
                                            }
                                        }
                                        //==inject gotParams to child gate
                                        if (isset($sessionData[$cCode]['main'])) {
                                            if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                foreach ($gSpec as $key => $val) {
                                                    $sessionData[$cCode]['main'][$key] = $val;
                                                }
                                            }
                                        }
                                    }

                                }
                            }
                            else {
                                cekmerah("TIDAK ada gotparam, tidak perlu replace");
                            }
                        }
                        else {
                            cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                        }
                    }
                }
                else {
                    //cekKuning("sub-preproc is not set");
                }


                $this->CI->load->helper("he_value_builder");
                $sessionData[$cCode] = fillValues_he_value_builder_ns($this->jenisTr, $stepNumCurrent, $stepNum, $configCoreMasterModulJenis, $configUiMasterModulJenis, $configValuesMasterModulJenis, $ppnFactor, $sessionData[$cCode]);


            }
            else {
                echo("no processor defined. skipping preprocessor..<br>");
            }

            //endregion

            //region pre-proc value injector items2 items2_sum dari gerbang main
            $injectValues = isset($configCoreMasterModulJenis['preInjectValue'][$stepNum]) ? $configCoreMasterModulJenis['preInjectValue'][$stepNum] : array();
            if (sizeof($injectValues) > 0) {
                $iterator = isset($configCoreMasterModulJenis['preInjectValue'][$stepNum]['master']) ? $configCoreMasterModulJenis['preInjectValue'][$stepNum]['master'] : array();
                $itemNumLabels = isset($configUiMasterModulJenis['shoppingCartNumFields']) ? $configUiMasterModulJenis['shoppingCartNumFields'] : array();
                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
                        //                    echo "master-preproc: $comName, initializing values <br>";
                        $tmpOutParams[$cCtr] = array();


                        $subParams = array();
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {

                                $realValue = makeValue($value, $sessionData[$cCode][$srcGateName], $sessionData[$cCode][$srcGateName], 0);
                                $subParams['static'][$key] = $realValue;

                            }

                            if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                foreach ($paramPatchers[$comName] as $k => $v) {
                                    if (!isset($subParams['static'][$k])) {
                                        $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                    }
                                }
                            }
                            if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                                $jenis = $sessionData[$cCode]['main']['jenis'];
                                foreach ($paramForceFillers[$comName] as $k => $v) {
                                    $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                }
                            }

                            $subParams['static']["fulldate"] = date("Y-m-d");
                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                            $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . "";
                        }
                        if (sizeof($subParams) > 0) {
                            $tmpOutParams[$cCtr] = $subParams;
                        }


                        $mdlName = "Pre" . ucfirst($comName);
                        $this->CI->load->model("Preprocs/" . $mdlName);
                        $m = new $mdlName($resultParams);


                        if (sizeof($tmpOutParams[$cCtr]) > 0) {
                            $tobeExecuted = true;
                        }
                        else {
                            $tobeExecuted = false;
                        }

                        if ($tobeExecuted) {
                            $m->pair($masterID, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $gotParams = $m->exec();
                            if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                //                            cekhijau("ada gotparam, sekarang mau replace");
                                foreach ($gotParams as $gateName => $gSpec) {
                                    if ($gateName == "main") {
                                        foreach ($gSpec as $key => $val) {
                                            $sessionData[$cCode]['main'][$key] = $val;
                                        }
                                    }
                                    if ($gateName == "items2") {
                                        foreach ($sessionData[$cCode]['items2'] as $k => $tmpSes) {
                                            foreach ($gSpec as $key => $val) {
                                                foreach ($tmpSes as $y => $sesData) {
                                                    if (array_key_exists($key, $sesData)) {
                                                        $sessionData[$cCode]['items2'][$k][$y][$key] = $val;
                                                    }
                                                }
                                            }
                                        }

                                    }
                                    if ($gateName == "items2_sum") {
                                        foreach ($sessionData[$cCode]['items2_sum'] as $k => $tmpSes) {
                                            foreach ($gSpec as $key => $val) {
                                                $sessionData[$cCode]['items2_sum'][$k][$key] = $val;
                                            }
                                        }

                                    }

                                }
                            }
                            else {
                                cekmerah("TIDAK ada gotparam, tidak perlu replace");
                            }

                        }
                        else {
                            cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                        }

                    }
                }
                else {
                    //cekKuning("sub-preproc is not set");
                }

                $this->CI->load->helper("he_value_builder");
                $sessionData[$cCode] = fillValues_he_value_builder_ns($this->jenisTr, $stepNumCurrent, $stepNum, $configCoreMasterModulJenis, $configUiMasterModulJenis, $configValuesMasterModulJenis, $ppnFactor, $sessionData[$cCode]);

            }
            //endregion

            $this->CI->load->library("Validator");
            $va = new Validator();
            $va->setConfigUiJenis($configUiMasterModulJenis);
            $va->setCCode($cCode);
            $va->midValidate_ns($sessionData, $stepNum);
            $va->unionValidate_ns($sessionData);

            //region update step2an
            if (isset($configUiMasterModulJenis['steps'][$nextStepNum])) {//===masih ada langkah selanjutnya
                echo "authorizing to next step..<br>";
                $nextProp = array(
                    "num" => $nextStepNum,
                    "code" => $configUiMasterModulJenis['steps'][$nextStepNum]['target'],
                    "label" => $configUiMasterModulJenis['steps'][$nextStepNum]['label'],
                    "groupID" => $configUiMasterModulJenis['steps'][$nextStepNum]['userGroup'],
                );
            }
            else {//==ini step terakhir, tulis komponen jika ada
                $nextProp = array(
                    "num" => 0,
                    "code" => "",
                    "label" => "",
                    "groupID" => "",
                );
            }
            //endregion
            arrPrintPink($nextProp);
            //==tulis signature
            $dwsign = $tr->writeSignature($masterID, array(
                "nomer" => $tmpNomorNota,
                "step_number" => $stepNum,
                "step_code" => $configUiMasterModulOrigJenis['steps'][$stepNum]['target'],
                "step_name" => $configUiMasterModulOrigJenis['steps'][$stepNum]['label'],
                "group_code" => $configUiMasterModulOrigJenis['steps'][$stepNum]['userGroup'],
                "oleh_id" => $sessionData[$cCode]["main"]["oleh_id"],
                "oleh_nama" => $sessionData[$cCode]["main"]["oleh_nama"],
                "keterangan" => $configUiMasterModulOrigJenis['steps'][$stepNum]['label'] . "",
                "transaksi_id" => $masterID,
            )) or mati_disini("Failed to write signature");

            //region update step terdahulu
            $tr = new MdlPenjualanTransaksi();
            $dupState = $tr->updateData(array("id" => $topID), array(
                "next_step_code" => $nextProp['code'],
                "next_step_label" => $nextProp['label'],
                "next_group_code" => $nextProp['groupID'],
                "next_step_num" => $nextProp['num'],
                "step_current" => $stepNum,
                "partial" => isset($sessionData[$cCode]['main']['partial']) ? $sessionData[$cCode]['main']['partial'] : 0,

            )) or die("Failed to update tr next-state!");
            cekHijau(__LINE__ . " ::: " . $this->CI->db->last_query());

            //-------------------------------------------------
            $tr = new MdlPenjualanTransaksi();
            $dupState = $tr->updateData(array("id" => $trID), array(
                "partial" => isset($sessionData[$cCode]['main']['partial']) ? $sessionData[$cCode]['main']['partial'] : 0,
            )) or die("Failed to update tr next-state!");
            //endregion

            arrPrintPink($sessionData[$cCode]['main']);


            $tCode = $configUiMasterModulOrigJenis['steps'][$stepNum]['target'];
            $tCodeName = $configUiMasterModulOrigJenis['steps'][$stepNum]['label'];
            $masterReplacers = array(
                "inv" => $tmpNomorNota,
                "jenis" => $tCode,
                "jenis_label" => $tCodeName,
                "transaksi_jenis" => $tCode,
                "cabang_id" => $sessionData[$cCode]['main']['cabang_id'],
                "cabang_nama" => $sessionData[$cCode]['main']['cabang_nama'],
                "gudang_id" => $sessionData[$cCode]['main']['gudang_id'],
                "gudang_nama" => $sessionData[$cCode]['main']['gudang_nama'],
                "oleh_id" => $sessionData[$cCode]["main"]["oleh_id"],
                "oleh_nama" => $sessionData[$cCode]["main"]["oleh_nama"],
                "step_avail" => sizeof($configUiMasterModulJenis["steps"]),
                "step_current" => $stepNum,
                "step_number" => $stepNum,
                "next_step_num" => $nextProp['num'],
                "next_step_code" => $nextProp['code'],
                "next_step_label" => $nextProp['label'],
                "next_group_code" => $nextProp['groupID'],
                //===references
                "id_master" => $masterID,
                "id_top" => $topID,
                "ids_prev" => base64_encode(serialize($prevIDs)),
                "ids_prev_intext" => print_r($prevIDs, true),
                "nomer_top2" => isset($sessionData[$cCode]['main']['nomer_top2']) ? $sessionData[$cCode]['main']['nomer_top2'] : "",
                "nomer_top" => $sessionData[$cCode]['main']['nomer_top'],
                "nomers_prev" => base64_encode(serialize($prevNos)),
                "nomers_prev_intext" => print_r($prevNos, true),
                "jenises_prev" => base64_encode(serialize(array($prevProp['jenis']))),
                "jenises_prev_intext" => print_r(array($prevProp['jenis']), true),
                "tail_number" => $stepNum,
                "tail_code" => $configUiMasterModulJenis['steps'][$stepNum]['target'],
                "ids_his" => $sessionData[$cCode]["main"]["ids_his"],

            );
            foreach ($masterReplacers as $key => $val) {
                $sessionData[$cCode]['tableIn_master'][$key] = $val;
            }

            $childTableRepaclers = array(
                "sub_step_number" => $stepNum,
                "sub_step_current" => $stepNum,
                "sub_step_avail" => sizeof($configUiMasterModulJenis['steps']),
                "next_substep_num" => $nextProp['num'],
                "next_substep_code" => $nextProp['code'],
                "next_substep_label" => $nextProp['label'],
                "next_subgroup_code" => $nextProp['groupID'],
            );
            foreach ($sessionData[$cCode]['tableIn_detail'] as $id => $dSpec) {
                //			$id = $dSpec['id'];
                foreach ($childTableRepaclers as $key => $val) {
                    $sessionData[$cCode]['tableIn_detail'][$id][$key] = $val;
                }
            }

            $masterReplacersO = array(
                "jenisTr" => $tCode,
                "jenisTrName" => $tCodeName,
                "oleh_id" => $sessionData[$cCode]["main"]["oleh_id"],
                "oleh_nama" => $sessionData[$cCode]["main"]["oleh_nama"],
                "stepNumber" => $stepNum,
                "stepCode" => $tCode,
            );
            foreach ($masterReplacersO as $key => $val) {
                $sessionData[$cCode]['main'][$key] = $val;
            }
            cekHitam("[$modul_transaksi]");

            //region menimbulkan nilai tagihan
            $unpaidList = null != $this->CI->config->item('tr_unpaidList') ? $this->CI->config->item('tr_unpaidList') : array();
            //        arrprint($sessionData[$cCode]['tableIn_master']);
            if (in_array($tCode, $unpaidList)) {
                $sessionData[$cCode]['tableIn_master']["transaksi_nilai_tagihan"] = $sessionData[$cCode]['tableIn_master']['transaksi_nilai'];
                $sessionData[$cCode]['tableIn_master']["transaksi_nilai_terbayar"] = 0;
                $sessionData[$cCode]['tableIn_master']["transaksi_nilai_sisa"] = ($sessionData[$cCode]['tableIn_master']['transaksi_nilai_tagihan'] - $sessionData[$cCode]['tableIn_master']['transaksi_nilai_terbayar']);
                //cekMerah("NULIS TAGIHANN");
            }
            else {
                //cekMerah("TIDAK NULIS TAGIHANN");
            }
            //endregion

            //region penomoran receipt #1

            $this->CI->load->model("CustomCounter");
            $cn = new CustomCounter("transaksi");
            $cn->setType("transaksi");
            $cn->setModul($modul_transaksi);
            $cn->setStepCode($jenisTrTarget);
            $counterForNumber = array($configCoreMasterModulOrigJenis['formatNota']);
            if (!in_array($counterForNumber[0], $configCoreMasterModulOrigJenis['counters'])) {
                die(__LINE__ . " Used number should be registered in 'counters' config as well");
            }
            foreach ($counterForNumber as $i => $cRawParams) {
                $cParams = explode("|", $cRawParams);
                $cValues = array();
                foreach ($cParams as $param) {
                    $cValues[$i][$param] = $sessionData[$cCode]['main'][$param];
                }
                $cRawValues = implode("|", $cValues[$i]);
                $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);
            }
            $tmpNomorNota2_current = $tmpNomorNota2 = $paramSpec['paramString'];
            $tmpNomorNota2Alias_current = $tmpNomorNota2Alias = formatNota("nomer_nolink", $tmpNomorNota2);
            //endregion

            //region dynamic counters #1
            echo "<script>top.writeProgress('sedang membuat penomoran');</script>";
            // <editor-fold defaultstate="collapsed" desc="==========__init+update dynamic-counters ">
            $cn = new CustomCounter("transaksi");
            $cn->setType("transaksi");
            $cn->setModul($modul_transaksi);
            $cn->setStepCode($jenisTrTarget);
            $configCustomParams = $configCoreMasterModulOrigJenis['counters'];
            $configCustomParams[] = "stepCode";
            if (sizeof($configCustomParams) > 0) {
                $cContent = array();
                foreach ($configCustomParams as $i => $cRawParams) {
                    $cParams = explode("|", $cRawParams);
                    $cValues = array();
                    foreach ($cParams as $param) {
                        $cValues[$i][$param] = $sessionData[$cCode]['main'][$param];
                    }
                    $cRawValues = implode("|", $cValues[$i]);
                    $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);

                    $cContent[$cRawParams][$cRawValues] = $paramSpec['value'];
                    switch ($paramSpec['id']) {
                        case 0: //===counter type is new
                            $paramKeyRaw = print_r($cParams, true);
                            $paramValuesRaw = print_r($cValues[$i], true);
                            $cn->writeNewCount($cParams, $cValues[$i], $paramKeyRaw, $paramValuesRaw);
                            break;
                        default: //===counter to be updated
                            $cn->updateCount($paramSpec['id'], $paramSpec['value']);
                            break;
                    }
                    //echo "<hr>";
                }
            }
            $appliedCounters2 = base64_encode(serialize($cContent));
            $appliedCounters_inText2 = print_r($cContent, true);


            $masterReplacers = array(
                "nomer" => $tmpNomorNota2,
                "nomer2" => $tmpNomorNota2Alias,
                "counters" => $appliedCounters2,
                "counters_intext" => $appliedCounters_inText2,
            );
            foreach ($masterReplacers as $key => $val) {
                $sessionData[$cCode]['tableIn_master'][$key] = $val;
            }

            $addValues = array(
                'counters' => $appliedCounters2,
                'counters_intext' => $appliedCounters_inText2,
                'nomer' => $tmpNomorNota2,
                'nomer2' => $tmpNomorNota2Alias,
                'dtime' => date("Y-m-d H:i:s"),
                'fulldate' => date("Y-m-d"),
            );
            foreach ($addValues as $key => $val) {
                $sessionData[$cCode]['tableIn_master'][$key] = $val;
            }
            //endregion

            //region numbering tambahan
            $this->CI->load->library("CounterNumber");
            $ccn = new CounterNumber();
            $ccn->setModul($modul_transaksi);
            $ccn->setCCode($cCode);
            $ccn->setStepCode($jenisTrTarget);
            $ccn->setJenisTr($this->jenisTr);
            $ccn->setTransaksiGate($sessionData[$cCode]['tableIn_master']);
            $ccn->setMainGate($sessionData[$cCode]['main']);
            $ccn->setItemsGate($sessionData[$cCode]['items']);
            $ccn->setItems2SumGate($sessionData[$cCode]['items2_sum']);
            $new_counter = $ccn->getCounterNumber();
            cekHitam("jenistr yang disett dari create " . $this->jenisTr);

            if (isset($new_counter['main']) && sizeof($new_counter['main']) > 0) {
                foreach ($new_counter['main'] as $ckey => $cval) {
                    $sessionData[$cCode]['tableIn_master'][$ckey] = $cval;
                    $sessionData[$cCode]['main'][$ckey] = $cval;
                }
            }
            if (isset($new_counter['items']) && sizeof($new_counter['items']) > 0) {
                foreach ($new_counter['items'] as $ikey => $iSpec) {
                    foreach ($iSpec as $iikey => $iival) {
                        $sessionData[$cCode]['items'][$ikey][$iikey] = $iival;
                    }
                }
            }
            if (isset($new_counter['items2_sum']) && sizeof($new_counter['items2_sum']) > 0) {
                foreach ($new_counter['items2_sum'] as $ikey => $iSpec) {
                    foreach ($iSpec as $iikey => $iival) {
                        $sessionData[$cCode]['items2_sum'][$ikey][$iikey] = $iival;
                    }
                }
            }
            //endregion
            //==tulis kloningan transaksi
            arrPrintWebs($sessionData[$cCode]['tableIn_master']);

            //region write entries
            $pakai_cli = 1;
            if (sizeof($sessionData[$cCode]['tableIn_master']) > 0) {

                // region locker transaksi---------------------------------
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    if ($this->session->login['ghost'] == 0) {
                        //                $followUpValidator = isset($configUiMasterModulOrigJenis['followUpValidator'][$stepNum]) ? $configUiMasterModulOrigJenis['followUpValidator'][$stepNum] : false;
                        //                if ($followUpValidator == true) {

                        $this->CI->load->model("Mdls/MdlLockerTransaksi");
                        $lt = New MdlLockerTransaksi();
                        $lt->addFilter("transaksi_id='$no'");
                        $lt->addFilter("state='hold'");
                        $lt->addFilter("jumlah='1'");
                        $lt->addFilter("oleh_id=" . my_id());
                        $ltTmp = $lt->lookupAll()->result();
                        showLast_query("biru");
                        if (sizeof($ltTmp) == 1) {
                            cekHijau(":: lanjuut eksekusi transaksi ini....");
                        }
                        else {
                            $msg = "Transaksi sudah dieksekusi atau ada indikasi transaksi ganda. Silahkan tutup halaman ini dan refresh ulang.";
                            cekMerah($msg);
                            die(lgShowAlertBiru($msg));
                        }

                        //                }
                    }
                }
                // endregion locker transaksi---------------------------------

                $sessionData[$cCode]['tableIn_master']['status_4'] = 11;
                $sessionData[$cCode]['tableIn_master']['trash_4'] = 0;
                $sessionData[$cCode]['main']['status_4'] = 11;
                $sessionData[$cCode]['main']['trash_4'] = 0;
                if ($pakai_cli == 1) {
                    $sessionData[$cCode]['main']['cli'] = 0;
                }
                else {
                    $sessionData[$cCode]['main']['cli'] = 1;
                }

                $insertTransaksiID = $insertID = $tr->writeMainEntries($sessionData[$cCode]['tableIn_master']);
                $midmaster = $insertID;
                cekBiru("master invoice " . $insertID);
//                $epID = $tr->writeMainEntries_entryPoint($insertID, $masterID, $sessionData[$cCode]['tableIn_master']);
//                $mongoList['main'] = array($insertID, $epID);
                $insertNum = $sessionData[$cCode]['tableIn_master']['nomer'];
                $mNumMaster = $insertNum;
                $mJenisMaster = $sessionData[$cCode]['tableIn_master']['jenis'];
                $sessionData[$cCode]['main']['nomer'] = $insertNum;
                if ($insertID < 1) {
                    die("Gagal saat berusaha  write transaction entry pada " . __FILE__ . " baris " . __LINE__);
                }


                if (isset($sessionData[$cCode]['tableIn_master']['ids_his'])) {
                    $idHis_decode = blobDecode($sessionData[$cCode]['tableIn_master']['ids_his']);
                    $idHis_decode[$stepNum] = array(
                        "dtime" => date("Y-m-d H:i:s"),
                        "fulldate" => date("Y-m-d"),
                        "olehID" => $sessionData[$cCode]['main']['olehID'],
                        "olehName" => $sessionData[$cCode]['main']['olehName'],
                        "step" => $stepNum,
                        "trID" => $insertID,
                        "nomer" => $tmpNomorNota2,
                        "nomer2" => $tmpNomorNota2Alias,
                        "counters" => $appliedCounters2,
                        "counters_intext" => $appliedCounters_inText2,
                    );
                    $idHis_blob = blobEncode($idHis_decode);
                    $idHis_intext = print_r($idHis_decode, true);

                    $sessionData[$cCode]['tableIn_master']['ids_his'] = $idHis_blob;
                    $sessionData[$cCode]['tableIn_master']['ids_his_intext'] = $idHis_intext;


                    $tr = new MdlPenjualanTransaksi();
                    $dup = $tr->updateData(array("id" => $insertID), array(
                        "ids_his" => $idHis_blob,
                        "ids_his_intext" => $idHis_intext,

                    )) or mati_disini("Failed to update tr next-state!");
                    cekUngu($this->CI->db->last_query());
                }
                else{
                    cekHitam("tidak ada tableIn_master ids_his");
                }


                cekUngu(":: insertID => $insertID ::");
                if (isset($sessionData[$cCode]['tableIn_master_values']) && sizeof($sessionData[$cCode]['tableIn_master_values']) > 0) {
                    $inserMainValues = array();
                    $mongoList['mainValues'] = array();
                    foreach ($sessionData[$cCode]['tableIn_master_values'] as $key => $val) {
                        $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                        $inserMainValues[] = $dd;
                        $mongoList['mainValues'][] = $dd;
                    }
                    if (sizeof($inserMainValues) > 0) {
                        $arrBlob = blobEncode($inserMainValues);
                        $this->CI->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                    }
                }
                if (isset($sessionData[$cCode]['main_add_values']) && sizeof($sessionData[$cCode]['main_add_values']) > 0) {
                    foreach ($sessionData[$cCode]['main_add_values'] as $key => $val) {
                        $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                        $mongoList['mainValues'][] = $dd;
                    }
                }
                if (isset($sessionData[$cCode]['main_inputs']) && sizeof($sessionData[$cCode]['main_inputs']) > 0) {
                    foreach ($sessionData[$cCode]['main_inputs'] as $key => $val) {
                        $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                        $mongoList['mainValues'][] = $dd;
                    }
                }
                if (isset($sessionData[$cCode]['main_add_fields']) && sizeof($sessionData[$cCode]['main_add_fields']) > 0) {
                    foreach ($sessionData[$cCode]['main_add_fields'] as $key => $val) {
                        $tr->writeMainFields($insertID, array("key" => $key, "value" => $val));
                    }
                }


                if (isset($sessionData[$cCode]['main_elements']) && sizeof($sessionData[$cCode]['main_elements']) > 0) {
                    //                cekMerah("ada mainElements $cCode");
                    //                arrprint($sessionData[$cCode]['main_elements']);die();
                    foreach ($sessionData[$cCode]['main_elements'] as $elName => $aSpec) {
                        $tr->writeMainElements($insertID, array(
                            "mdl_name" => isset($aSpec['mdl_name']) ? $aSpec['mdl_name'] : "",
                            "key" => isset($aSpec['key']) ? $aSpec['key'] : 0,
                            "value" => isset($aSpec['value']) ? $aSpec['value'] : "",
                            "name" => $aSpec['name'],
                            "label" => $aSpec['label'],
                            "contents" => isset($aSpec['contents']) ? $aSpec['contents'] : "",
                            "contents_intext" => isset($aSpec['contents_intext']) ? print_r($aSpec['contents_intext'], true) : "",

                        ));
                    }
                }
                else {
                    //                cekMerah("TAK ada mainElements");
                }

                if (isset($sessionData[$cCode]['tableIn_detail_values']) && sizeof($sessionData[$cCode]['tableIn_detail_values']) > 0) {
                    $insertIDs = array();
                    foreach ($sessionData[$cCode]['tableIn_detail_values'] as $pID => $dSpec) {
                        if (isset($configCoreMasterModulJenis['tableIn']['detailValues'])) {
                            foreach ($configCoreMasterModulJenis['tableIn']['detailValues'] as $key => $src) {
                                $dd = $tr->writeDetailValues($insertID, array(
                                    "produk_jenis" => $sessionData[$cCode]['tableIn_detail'][$pID]['produk_jenis'],
                                    "produk_id" => $pID,
                                    "key" => $key,
                                    "value" => isset($dSpec[$src]) ? $dSpec[$src] : 0,
                                ));
                                $insertIDs[$pID][] = $dd;
                                $mongoList['detailValues'][] = $dd;
                            }

                        }
                    }
                    if (sizeof($insertIDs) > 0) {
                        $arrBlob = blobEncode($insertIDs);
                        $this->CI->db->query("UPDATE transaksi SET indexing_detail_values = '$arrBlob' WHERE id=$insertID");
                    }
                }
                if (isset($sessionData[$cCode]['tableIn_detail_values2_sum']) && sizeof($sessionData[$cCode]['tableIn_detail_values2_sum']) > 0) {
                    foreach ($sessionData[$cCode]['tableIn_detail_values2_sum'] as $pID => $dSpec) {
                        if (isset($configCoreMasterModulJenis['tableIn']['detailValues2_sum'])) {
                            foreach ($configCoreMasterModulJenis['tableIn']['detailValues2_sum'] as $key => $src) {
                                $dd = $tr->writeDetailValues($insertID, array(
                                    "produk_jenis" => $sessionData[$cCode]['tableIn_detail2_sum'][$pID]['produk_jenis'],
                                    "produk_id" => $pID,
                                    "key" => $key,
                                    "value" => $dSpec[$src],
                                ));
                                $insertIDs[] = $dd;
                                $mongoList['detailValues'][] = $dd;
                            }
                        }


                    }
                }
                if (isset($sessionData[$cCode]['tableIn_detail_rsltItems']) && sizeof($sessionData[$cCode]['tableIn_detail_rsltItems']) > 0) {
                    foreach ($sessionData[$cCode]['tableIn_detail_rsltItems'] as $pID => $dSpec) {
                        if (isset($configCoreMasterModulJenis['tableIn']['detail_rsltItems'])) {
                            foreach ($configCoreMasterModulJenis['tableIn']['detail_rsltItems'] as $key => $src) {
                                $dd = $tr->writeDetailValues($insertID, array(
                                    "produk_jenis" => $sessionData[$cCode]['tableIn_detail_rsltItems'][$pID]['produk_jenis'],
                                    "produk_id" => $pID,
                                    "key" => $key,
                                    "value" => $dSpec[$src],
                                ));
                                $insertIDs[$pID][] = $dd;
                                $mongoList['detailValues'][] = $dd;
                            }
                        }


                    }
                }

                //region update validQty pada step sebelumnya yang di-refer
                echo "<script>top.writeProgress('EXTRACT ITEMS...','head');</script>";
                $seluruhnya = true;
                $prevTrID = 0;
                $arrvalidQtySisa = array();
                if (isset($sessionData[$cCode]['tableIn_detail']) && sizeof($sessionData[$cCode]['tableIn_detail']) > 0) {
                    $closedRequest = isset($configCoreMasterModulOrigJenis['closedRequest'][$stepNum]['enabled']) ? $configCoreMasterModulOrigJenis['closedRequest'][$stepNum]['enabled'] : false;
                    $insertIDs = array();
                    $insertDeIDs = array();
                    foreach ($sessionData[$cCode]['tableIn_detail'] as $iID => $dSpec) {
                        $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                        cekHitam($this->CI->db->last_query());
                        if ($insertDetailID < 1) {
                            die("Gagal saat berusaha write transaction detail entry pada " . __FILE__ . " baris " . __LINE__);
                        }
                        else {
                            $insertIDs[] = $insertDetailID;
                            $insertDeIDs[$insertID][] = $insertDetailID;
                            $mongoList['detail'][] = $insertDetailID;

                        }

//                        if ($epID != 999) {
//                            $insertEpID = $tr->writeDetailEntries($epID, $dSpec);
//                            if ($insertEpID < 1) {
//                                die("Gagal saat berusaha write transaction detail entry point pada " . __FILE__ . " baris " . __LINE__);
//                            }
//                            else {
//                                $insertIDs[] = $insertEpID;
//                                $insertDeIDs[$epID][] = $insertEpID;
//                                $mongoList['detail'][] = $insertDetailID;
//                            }
//                        }

                        cekHitam("EXTRACTED ITEMS... [$iID]");
                        echo "<script>top.writeProgress('" . strtoupper($dSpec['produk_nama']) . "');</script>";


                        if (isset($sessionData[$cCode]['extractedItems'])) {
                            if (array_key_exists($iID, $sessionData[$cCode]['extractedItems'])) {
                                $itemFulfilledJml = 0;
                                foreach ($sessionData[$cCode]['extractedItems'][$iID] as $triID => $triSpec) {
                                    $prevTrID = $triSpec['transaksi_id'];
                                    $tru = new MdlPenjualanTransaksi();
                                    $tru->setFilters(array());
                                    $tru->setTableName($tru->getTableNames()['detail']);
                                    //----------------------------------------------------------
                                    if ($triSpec['valid_qty'] >= $dSpec['produk_ord_jml']) {
                                        $newValidQty = ($triSpec['valid_qty'] - $dSpec['produk_ord_jml']);
                                        //                                    cekmerah("validQty dikurangi oleh produk_ord_jml, yaitu " . $dSpec['produk_ord_jml']);
                                    }
                                    else {
                                        $newValidQty = ($triSpec['valid_qty'] - $triSpec['valid_qty']);
                                        //                                    cekmerah("validQty dikurangi oleh triSpec,  myaitu " . $triSpec['valid_qty']);
                                    }
                                    //----------------------------------------------------------
                                    $newValidQtyNotApprove = 0;
                                    if ($closedRequest == true) {
                                        cekPink2("closed Request enabled, request: " . $triSpec['valid_qty'] . ", approve: " . $dSpec['produk_ord_jml'] . ", newValidQty: " . $newValidQty);
                                        if ($triSpec['valid_qty'] >= $dSpec['produk_ord_jml']) {
                                            $newValidQty = 0;
                                            $newValidQtyNotApprove = ($triSpec['valid_qty'] - $dSpec['produk_ord_jml']);

                                        }
                                        //                                    else{
                                        //                                        $newValidQty = 0;
                                        //                                        $newValidQtyNotApprove = ($triSpec['valid_qty'] - $dSpec['produk_ord_jml']);
                                        //                                    }
                                        cekPink2("new valid qty: $newValidQty, valid qty not approve: $newValidQtyNotApprove");
                                    }
                                    //----------------------------------------------------------


                                    $itemFulfilledJml += $newValidQty;
                                    $updateContents = array(
//                                        "valid_qty" => $newValidQty,
//                                        "valid_qty_no_approve" => $newValidQtyNotApprove,
                                    );
                                    if ($newValidQty < 1) {
                                        $childPrevRepaclers = array(
                                            "next_substep_code" => "",
                                            "next_substep_label" => "",
                                            "next_subgroup_code" => "",
                                            "sub_tail_number" => $stepNum,
                                            "sub_tail_code" => $configUiMasterModulJenis['steps'][$stepNum]['target'],
                                        );
                                        foreach ($childPrevRepaclers as $key => $val) {
                                            $updateContents[$key] = $val;
                                        }
                                    }
                                    else {//==kalau ada yang tidak habis, berarti TIDAK seluruhnya yang dilanjutkan pada step berikutnya
                                        $seluruhnya = false;
                                        $arrvalidQtySisa[$iID] = $newValidQty;
                                    }
                                    $dupState = $tru->updateData(array(
                                        "produk_id" => $iID,
                                        "id" => $triID,
                                        "transaksi_id" => $triSpec['transaksi_id'],
                                    ), $updateContents) or die("Failed to update previous detail entries!");
                                    cekHijau(__LINE__ . " :: UPDATE TRANSAKSI_DATA :: " . $this->CI->db->last_query());

                                    unset($tru);
                                }
                            }
                            //                        else{
                            //                            if($closedRequest == true){
                            //
                            //                            }
                            //                        }
                        }
                    }

                    if ($closedRequest == true) {
                        if (isset($sessionData[$cCode]['extractedItems'])) {
                            foreach ($sessionData[$cCode]['extractedItems'] as $iIDex => $exSpec) {
                                if (!array_key_exists($iIDex, $sessionData[$cCode]['tableIn_detail'])) {
                                    foreach ($exSpec as $trDataID => $trdSpec) {
                                        $tru = new MdlPenjualanTransaksi();
                                        $tru->setFilters(array());
                                        $tru->setTableName($tru->getTableNames()['detail']);
                                        $updateContents = array(
//                                            "valid_qty" => 0,
//                                            "valid_qty_no_approve" => $trdSpec['qty'],
                                        );
                                        $childPrevRepaclers = array(
                                            "next_substep_code" => "",
                                            "next_substep_label" => "",
                                            "next_subgroup_code" => "",
                                            "sub_tail_number" => $stepNum,
                                            "sub_tail_code" => $configUiMasterModulJenis['steps'][$stepNum]['target'],
                                        );
                                        foreach ($childPrevRepaclers as $key => $val) {
                                            $updateContents[$key] = $val;
                                        }
                                        $dupState = $tru->updateData(array(
                                            "produk_id" => $iIDex,
                                            "id" => $trDataID,
                                            "transaksi_id" => $trdSpec['transaksi_id'],
                                        ), $updateContents) or die("Failed to update previous detail entries!");
                                        unset($tru);
                                    }
                                }
                            }
                        }
                    }

                    if (sizeof($insertIDs) == 0) {
                        die(lgShowAlert("Transaksi gagal disimpan karena rincian transaksi kosong."));
                    }
                    else {
                        $indexing_details = array();
                        foreach ($insertDeIDs as $key => $numb) {
                            $indexing_details[$key] = $numb;
                        }
                        foreach ($indexing_details as $k => $arrID) {
                            arrPrint($arrID);
                            $arrBlob = blobEncode($arrID);
                            $this->CI->db->query("UPDATE transaksi SET indexing_details = '$arrBlob' WHERE id=$k");
                            cekOrange($this->CI->db->last_query());
                        }
                    }

                    //-------------
                    $lastStepPartialApprove = isset($configUiMasterModulJenis['lastStepPartialApprove']) ? $configUiMasterModulJenis['lastStepPartialApprove'] : false;
                    if ($lastStepPartialApprove == true) {
                        cekKuning(__LINE__ . " $lastStepPartialApprove :: $totalSteps");
                        if ($totalSteps == 2) {
                            if (sizeof($arrvalidQtySisa) > 0) {
                                cekPink("ada valid qty yang tersisa");
                                $tr = new MdlPenjualanTransaksi();
                                $dupState = $tr->updateData(array("id" => $topID), $stepNowParameter) or die("Failed to update tr next-state!");
                                cekHitam(__LINE__ . " ## 2 step, dan step akhir partial, YESS...");
                                showLast_query("orange");
                            }
                        }
                    }
                }
                else {
                    die(lgShowAlert("Transaksi gagal disimpan karena rincian transaksi kosong."));
                }

                if ($seluruhnya) {
                    $tr = new MdlPenjualanTransaksi();
                    $dupState = $tr->updateData(array("id" => $prevTrID), array(
                        "tail_number" => $stepNum,
                        "tail_code" => $configUiMasterModulJenis['steps'][$stepNum]['target'],
                        "status_4" => $sessionData[$cCode]['main']['status_4'],
                        "trash_4" => $sessionData[$cCode]['main']['trash_4'],
                    )) or mati_disini("Failed to update tr next-state!");
                    cekHijau(":: UOPDATE transaksi dengan trID -> $prevTrID");
                    $mongUpdateList['update']['main'][] = array(
                        "where" => array(
                            "id" => "$prevTrID",
                        ),
                        "value" => array(
                            "tail_number" => $stepNum,
                            "tail_code" => $configUiMasterModulJenis['steps'][$stepNum]['target'],
                            "status_4" => $sessionData[$cCode]['main']['status_4'],
                            "trash_4" => $sessionData[$cCode]['main']['trash_4'],
                        ),
                    );
                    cekHijau($this->CI->db->last_query());
                }
                //endregion

                //region cloner items to item_child
                if (sizeof($additionalData) > 0) {
                    echo "<script>top.writeProgress('CLONING ITEMS TO ITEM CHILD...','head');</script>";
                    cekHitam("ini data");
                    $dataMdl = $additionalData["mdlName"];
                    $this->CI->load->model("Mdls/" . $dataMdl);
                    $da = new $dataMdl();
                    $arrColl = $da->getFields();
                    $selectedCol = array();
                    foreach ($arrColl as $colSpec) {
                        $selectedCol[] = $colSpec['kolom'];
                    }

                    if (isset($sessionData[$cCode]['items_child']) && sizeof($sessionData[$cCode]['items_child'])) {
                        $gateData = isset($configUiMasterModulJenis['shopingCartDetailFields'][$stepNum]['gate']) ? $configUiMasterModulJenis['shopingCartDetailFields'][$stepNum]['gate'] : "detail";

                        $arrBlacklist = array(
                            "jml", "max_jml", "qty",
                        );
                        if (isset($sessionData[$cCode]["items2_sum"])) {
                            unset($sessionData[$cCode]["items2_sum"]);
                            unset($sessionData[$cCode]["items2"]);
                            unset($sessionData[$cCode]["tableIn_detail_values2_sum"]);
                        }
                        foreach ($sessionData[$cCode]['items_child'] as $mainProdsID => $defData) {
                            if ($gateData == "detail") {
                                $itemsMain = isset($sessionData[$cCode]['items'][$mainProdsID]) ? $sessionData[$cCode]['items'][$mainProdsID] : array();
                            }
                            else {
                                $forceMainToItems = isset($configUiMasterModulJenis['shopingCartDetailFields'][$stepNum]['changeToItems'][$gateData]) ? $configUiMasterModulJenis['shopingCartDetailFields'][$stepNum]['changeToItems'][$gateData] : array();
                                if (sizeof($forceMainToItems) > 0) {
                                    foreach ($forceMainToItems as $key1 => $key2) {
                                        $keyForce = strlen($key2) > 2 ? $key2 : $key1;
                                        $itemsMain[$key1] = isset($sessionData[$cCode]['main'][$keyForce]) ? $sessionData[$cCode]['main'][$keyForce] : "";
                                    }
                                    $itemsMain["jml"] = "1";
                                    $itemsMain["qty"] = "1";
                                    $itemsMain["max_jml"] = "1";

                                }
                                else {
                                    matiHEre("detil aset gagal di tulis!");
                                }
                                //                            arrPrint($forceMainToItems);
                            }

                            $arrChilds = array_diff_key($itemsMain, array_flip($arrBlacklist));
                            //                        arrPrint($itemsMain);
                            //                        matiHEre();
                            //
                            //arrPrint($arrChilds);
                            cekLime("ini brooo " . $gateData);

                            $arrNew = array();
                            if (sizeof($itemsMain) > 0) {
                                foreach ($defData as $inID => $detil_child) {
                                    //                        $arrNewChild = array_diff($itemsMain,$detil_child);

                                    $paramDetil = array_replace($arrChilds, $detil_child);
                                    if (array_key_exists("id", $paramDetil)) {

                                        $paramDetil["parent_id"] = $paramDetil["id"];
                                        if (!isset($paramDetil["folders"]) || $paramDetil["folders"] == 0) {
                                            $paramDetil["folders"] = $paramDetil["pihakMainId"];
                                            $paramDetil["keterangan"] = $paramDetil["pihakMainName"];
                                        }
                                        unset($paramDetil["id"]);
                                    }
                                    $tmpData = array();
                                    foreach ($selectedCol as $i => $coloum) {
                                        if (isset($paramDetil[$coloum])) {
                                            $tmpData[$coloum] = $paramDetil[$coloum];
                                        }
                                    }
                                    //                                arrPrint($paramDetil);
                                    if (isset($paramDetil["subtotal"])) {
                                        $paramDetil["subtotal"] = $paramDetil["jml"] * $paramDetil["harga"];
                                    }

                                    $insertDataID = $da->addData($tmpData, $da->getTableName()) or die(lgShowError("Gagal menulis pengajuan data", __FILE__));
                                    cekHere($this->CI->db->last_query());
                                    $paramDetil["id"] = $insertDataID;
                                    echo "<script>top.writeProgress('PENGAJUAN DATA (TRID:$insertDataID)');</script>";
                                    $sessionData[$cCode]["items2_sum"][$insertDataID] = $paramDetil;
                                    $sessionData[$cCode]["items2"][$mainProdsID][$insertDataID] = $paramDetil;
                                    //                            $arrNew

                                }
                            }


                            //                        arrPrint($arrNew);
                            //


                            //                  arrPrint($itemsMain);
                        }

                    }
                }

                //endregion

                if (isset($sessionData[$cCode]['tableIn_detail2_sum']) && sizeof($sessionData[$cCode]['tableIn_detail2_sum']) > 0) {
                    $insertIDs = array();
                    foreach ($sessionData[$cCode]['tableIn_detail2_sum'] as $iID => $dSpec) {
                        $dd = $tr->writeDetailEntries($insertID, $dSpec);
                        $insertIDs[] = $dd;
                        $mongoList['detail'][] = $dd;
//                        if ($epID != 999) {
//                            $dd = $tr->writeDetailEntries($epID, $dSpec);
//                            $insertIDs[] = $dd;
//                            $mongoList['detail'][] = $dd;
//                        }
                    }
                }
                if (isset($sessionData[$cCode]['tableIn_detail2']) && sizeof($sessionData[$cCode]['tableIn_detail2']) > 0) {
                    $insertIDs = array();
                    foreach ($sessionData[$cCode]['tableIn_detail2'] as $iID => $dSpec) {
                        $dd = $tr->writeDetailEntries($insertID, $dSpec);
                        $insertIDs[] = $dd;
                        $mongoList['detail'][] = $dd;
//                        if ($epID != 999) {
//                            $dd = $tr->writeDetailEntries($epID, $dSpec);
//                            $insertIDs[] = $dd;
//                            $mongoList['detail'][] = $dd;
//                        }
                        cekUngu($this->CI->db->last_query());
                    }
                }


                if (isset($configUiMasterModulJenis['updateDueDate'][$stepNum])) {
                    $dueDateConf = $configUiMasterModulJenis['updateDueDate'][$stepNum];
                    $sourceDue = $dueDateConf['source'];
                    $targetDue = $dueDateConf['target'];
                    $datenow = date("Y-m-d");
                    foreach ($sourceDue as $key => $val) {
                        $indexVal = isset($sessionData[$cCode]['main_elements'][$key][$val]) ? $sessionData[$cCode]['main_elements'][$key][$val] : 14;
                        $dueDate = dueDate($datenow, $indexVal);
                    }
                    $fieldDue = $tr->getFields()["dueDate"];
                    $dataDue = array();
                    foreach ($fieldDue as $kol) {
                        if (isset($sessionData[$cCode]['tableIn_master'][$kol])) {
                            $dataDue[$kol] = $sessionData[$cCode]['tableIn_master'][$kol];
                        }
                    }
                    $dataDue['due_date'] = $dueDate;
                    $validateDue = validateDueDate($sessionData[$cCode]['main']['customerID'], $sessionData[$cCode]['main']['dtime']);
//                    cekMErah("masuk config duedate");
//                    arrPrint($validateDue);
//                    arrPrintWebs($dataDue);
//                    cekBiru($sessionData[$cCode]['main']['nilai_tambah_2010050_2010050010']);
//matiHere(__LINE__);
//                    arrPrint($validateDue);
//                    if ($validateDue['allow_create'] == "true") {
                    if (isset($sessionData[$cCode]['main']['nilai_tambah_2010050_2010050010']) && $sessionData[$cCode]['main']['nilai_tambah_2010050_2010050010'] > 0) {
                        cekBiru($sessionData[$cCode]['main']['nilai_tambah_2010050_2010050010']);
                        switch ($sessionData[$cCode]['main']['paymentMethod']) {
                            case "cash":
                                break;
                            default:
                                $tr->writeDueDate($insertID, $dataDue);
                                break;
                        }

                        cekHitam($this->CI->db->last_query());
                    }
//                    }
//                    else {
//                        $allowedOver = validateOverDue($sessionData[$cCode]['main']['customerID']);
//                        if ($allowedOver['status'] == "allowed") {
//
//                        }
//                        else {
//                            //                        matiHere($validateDue['error']);//matiin transaksi sudah over due
//                        }
//                        //                    arrPrint()
//                        //                    matiHere($validateDue['error']);//matiin transaksi sudah over due
//                    }
//                                    matiHere();
                    //update main elementnya
                    foreach ($targetDue as $keyTarget => $valTarget) {
                        $sessionData[$cCode]['main_elements'][$keyTarget][$valTarget] = $dueDate;
                        $sessionData[$cCode]['main']['dueDate'] = $dueDate;
                    }
                }
                else {
//                    matiHere("GAgal menulis duedate");
                }

                //pengganti registry ditulis ke tabel fisik
                if (isset($sessionData[$cCode]['tableIn_items']) && sizeof($sessionData[$cCode]['tableIn_items']) > 0) {
                    $insertIDs = array();
                    foreach ($sessionData[$cCode]['tableIn_items'] as $dSpec) {
                        arrPrint($dSpec);
                        $insertIDs[] = $tr->writeDetailItemsEntries($insertID, $dSpec);
                        cekBiru($this->CI->db->last_query());
                    }
//                matiHere();
                }
                if (isset($sessionData[$cCode]['tableIn_items2']) && sizeof($sessionData[$cCode]['tableIn_items2']) > 0) {
                    $insertIDs = array();
                    foreach ($sessionData[$cCode]['tableIn_items2'] as $dSpec) {
                        $insertIDs[] = $tr->writeEntriesDetailItems2($insertID, $dSpec);
                        $mongoList['detail'] = $insertIDs;
//                    if ($epID != 999) {
//                        $insertIDs[] = $tr->writeEntriesDetailItems2($epID, $dSpec);
//                        $mongoList['detail'] = $insertIDs;
//                    }
                        cekUngu($this->CI->db->last_query());
                    }
                }
                if (isset($sessionData[$cCode]['tableIn_items2_sum']) && sizeof($sessionData[$cCode]['tableIn_items2_sum']) > 0) {
                    $insertIDs = array();
                    foreach ($sessionData[$cCode]['tableIn_items2_sum'] as $dSpec) {
                        $insertDetailID = $tr->writeEntriesDetailItems2_sum($insertID, $dSpec);
                        $insertIDs[] = $insertDetailID;
                        $mongoList['detail'][] = $insertDetailID;
//                    if ($epID != 999) {
//                        $dd = $tr->writeEntriesDetailItems2_sum($epID, $dSpec);
//                        $insertIDs[] = $dd;
//                        $mongoList['detail'][] = $dd;
//                    }
                    }
                }
                if (isset($sessionData[$cCode]['tableIn_items3']) && sizeof($sessionData[$cCode]['tableIn_items3']) > 0) {
                    $insertIDs = array();
                    foreach ($sessionData[$cCode]['tableIn_items3'] as $dSpec) {
                        $insertIDs[] = $tr->writeEntriesDetailItems3($insertID, $dSpec);
                    }
                }
                if (isset($sessionData[$cCode]['tableIn_items3_sum']) && sizeof($sessionData[$cCode]['tableIn_items3_sum']) > 0) {
                    $insertIDs = array();
                    foreach ($sessionData[$cCode]['tableIn_items3_sum'] as $dSpec) {
                        $insertDetailID = $tr->writeEntriesDetailItems3_sum($insertID, $dSpec);
                        cekMErah($this->CI->db->last_query());
                    }
                }
                if (isset($sessionData[$cCode]['tableIn_items4']) && sizeof($sessionData[$cCode]['tableIn_items4']) > 0) {
                    $insertIDs = array();
                    foreach ($sessionData[$cCode]['tableIn_items4'] as $dSpec) {
                        $insertIDs[] = $tr->writeEntriesDetailItems4($insertID, $dSpec);
                    }
                }
                if (isset($sessionData[$cCode]['tableIn_items4_sum']) && sizeof($sessionData[$cCode]['tableIn_items4_sum']) > 0) {
                    $insertIDs = array();
                    foreach ($sessionData[$cCode]['tableIn_items4_sum'] as $dSpec) {
                        $insertDetailID = $tr->writeEntriesDetailItems4_sum($insertID, $dSpec);
                    }
                }
                if (isset($sessionData[$cCode]['tableIn_items5']) && sizeof($sessionData[$cCode]['tableIn_items5']) > 0) {
                    $insertIDs = array();
                    foreach ($sessionData[$cCode]['tableIn_items5'] as $dSpec) {
                        $insertIDs[] = $tr->writeEntriesDetailItems5($insertID, $dSpec);
                    }
                }
                if (isset($sessionData[$cCode]['tableIn_items5_sum']) && sizeof($sessionData[$cCode]['tableIn_items5_sum']) > 0) {
                    $insertIDs = array();
                    foreach ($sessionData[$cCode]['tableIn_items5_sum'] as $dSpec) {
                        $insertDetailID = $tr->writeEntriesDetailItems5_sum($insertID, $dSpec);
                    }
                }
                if (isset($sessionData[$cCode]['tableIn_items6']) && sizeof($sessionData[$cCode]['tableIn_items6']) > 0) {
                    $insertIDs = array();
                    foreach ($sessionData[$cCode]['tableIn_items6'] as $dSpec) {
                        $insertIDs[] = $tr->writeEntriesDetailItems6($insertID, $dSpec);
                    }
                }
                if (isset($sessionData[$cCode]['tableIn_items6_sum']) && sizeof($sessionData[$cCode]['tableIn_items6_sum']) > 0) {
                    $insertIDs = array();
                    foreach ($sessionData[$cCode]['tableIn_items6_sum'] as $dSpec) {
                        $insertDetailID = $tr->writeEntriesDetailItems6_sum($insertID, $dSpec);
                    }
                }
                if (isset($sessionData[$cCode]['tableIn_items7']) && sizeof($sessionData[$cCode]['tableIn_items7']) > 0) {
                    $insertIDs = array();
                    foreach ($sessionData[$cCode]['tableIn_items7'] as $dSpec) {
                        $insertIDs[] = $tr->writeEntriesDetailItems7($insertID, $dSpec);
                    }
                }
                if (isset($sessionData[$cCode]['tableIn_items7_sum']) && sizeof($sessionData[$cCode]['tableIn_items7_sum']) > 0) {
                    $insertIDs = array();
                    foreach ($sessionData[$cCode]['tableIn_items7_sum'] as $dSpec) {
                        $insertDetailID = $tr->writeEntriesDetailItems7_sum($insertID, $dSpec);
                    }
                }
                if (isset($sessionData[$cCode]['tableIn_items8']) && sizeof($sessionData[$cCode]['tableIn_items8']) > 0) {
                    $insertIDs = array();
                    foreach ($sessionData[$cCode]['tableIn_items8'] as $dSpec) {
                        $insertIDs[] = $tr->writeEntriesDetailItems8($insertID, $dSpec);
                    }
                }
                if (isset($sessionData[$cCode]['tableIn_items8_sum']) && sizeof($sessionData[$cCode]['tableIn_items8_sum']) > 0) {
                    $insertIDs = array();
                    foreach ($sessionData[$cCode]['tableIn_items8_sum'] as $dSpec) {
                        $insertDetailID = $tr->writeEntriesDetailItems8_sum($insertID, $dSpec);
                    }
                }
                if (isset($sessionData[$cCode]['tableIn_items9_sum']) && sizeof($sessionData[$cCode]['tableIn_items9_sum']) > 0) {
                    $insertIDs = array();
                    foreach ($sessionData[$cCode]['tableIn_items9_sum'] as $dSpec) {
                        $insertDetailID = $tr->writeEntriesDetailItems9_sum($insertID, $dSpec);
                        cekMErah($this->CI->db->last_query());
                    }
                }
                if (isset($sessionData[$cCode]['tableIn_items10_sum']) && sizeof($sessionData[$cCode]['tableIn_items10_sum']) > 0) {
                    $insertIDs = array();
                    foreach ($sessionData[$cCode]['tableIn_items10_sum'] as $dSpec) {
                        $insertDetailID = $tr->writeEntriesDetailItems10_sum($insertID, $dSpec);
                    }
                }


                $baseRegistries = array(
                    "jurnal_index" => isset($configCoreMasterModulJenis['components'][$jenisTrTarget]) ? $configCoreMasterModulJenis['components'][$jenisTrTarget] : array(),
                    "preProcessor" => isset($configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]) ? $configCoreMasterModulJenis['preProcessor'][$jenisTrTarget] : array(),
                    "postProcessor" => isset($configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]) ? $configCoreMasterModulJenis['postProcessor'][$jenisTrTarget] : array(),
                    "revert" => isset($sessionData[$cCode]['revert']) ? $sessionData[$cCode]['revert'] : array(),
                    "items_komposisi" => isset($sessionData[$cCode]['items_komposisi']) ? $sessionData[$cCode]['items_komposisi'] : array(),
                    "componentsBuilder" => isset($sessionData[$cCode]['componentsBuilder']) ? $sessionData[$cCode]['componentsBuilder'] : array(),
                    "jurnalItems" => isset($sessionData[$cCode]['jurnalItems']) ? $sessionData[$cCode]['jurnalItems'] : array(),
                );
                $doWriteReg = $tr->writeDataRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));
                $mongRegID = $doWriteReg;
                echo "<script>top.writeProgress('MENULIS KE-REGISTRY....');</script>";
            }
            else {
                die(lgShowAlert("Transaksi gagal disimpan, silahkan cek kembali transaksi ini."));
            }
            //endregion


            $tr = new MdlPenjualanTransaksi();
            $tr->addFilter($tr->getTableNames()["main"] . ".id='$insertID'");
            $tmpTr = $tr->lookupAll()->result();
            arrPrintHijau($tmpTr);

            mati_disini(__LINE__ . ";;function " . __FUNCTION__);

            //region processing sub-post-processors, always
            $iterator = isset($configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]['detail']) ? $configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]['detail'] : array();
            if (sizeof($iterator) > 0) {
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    echo "sub-postProcessor: $comName, initializing values <br>";
                    echo "<script>top.writeProgress('MENYIAPKAN DATA SUB-PROCESSORS UNTUK DIKIRIM...', 'head');</script>";

                    $tmpOutParams[$cCtr] = array();
                    foreach ($sessionData[$cCode][$srcGateName] as $cnt => $dSpec) {
                        $subParams = array();
                        if (isset($tComSpec['loop'])) {
                            foreach ($tComSpec['loop'] as $key => $value) {

                                $realValue = makeValue($value, $sessionData[$cCode][$srcGateName][$cnt], $sessionData[$cCode][$srcGateName][$cnt], 0);
                                $subParams['loop'][$key] = $realValue;

                            }
                        }
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {

                                $realValue = makeValue($value, $sessionData[$cCode][$srcGateName][$cnt], $sessionData[$cCode][$srcGateName][$cnt], 0);
                                $subParams['static'][$key] = $realValue;
                                cekBiru("$key diisi dengan $realValue");

                            }

                            if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                foreach ($paramPatchers[$comName] as $k => $v) {
                                    if (!isset($subParams['static'][$k])) {
                                        $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                    }
                                }
                            }
                            if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                                $jenis = $sessionData[$cCode]['main']['jenis'];
                                foreach ($paramForceFillers[$comName] as $k => $v) {
                                    $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                    cekorange(":: $k diisikan dengan " . $subParams['static'][$k]);
                                }
                            }

                            $subParams['static']["fulldate"] = date("Y-m-d");
                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                            $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " ";
                        }

                        if (sizeof($subParams) > 0) {
                            $tmpOutParams[$cCtr][] = $subParams;
                        }
//                        echo "<script>top.writeProgress('" . isset($subParams['static']['name']) ? $subParams['static']['name'] : "" . " " . isset($subParams['static']['extern_nama']) ? $subParams['static']['extern_nama'] : "" . " " . isset($subParams['static']['nama']) ? $subParams['static']['nama'] : "" . "');</script>";
                    }
                }

                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    if (sizeof($tmpOutParams[$cCtr]) > 0) {

                        echo "sub-postProcessor: $comName, sending values <br>";
                        echo "<script>top.writeProgress('SENDING SUB-PROCESSORS ($comName)...', 'head');</script>";
                        $mdlName = "Com" . ucfirst($comName);
                        $this->CI->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();

                        $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        cekBiru($this->CI->db->last_query());
                    }
                }
            }

            //endregion

            //region postproc sub detail , multidimensional array
            $iterator = isset($configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]['sub_detail']) ? $configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]['sub_detail'] : array();
            if (sizeof($iterator) > 0) {
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    echo "sub-postProcessor: $comName, initializing values <br>";
                    echo "<script>top.writeProgress('MENYIAPKAN DATA SUB-PROCESSORS UNTUK DIKIRIM...', 'head');</script>";

                    $tmpOutParams[$cCtr] = array();

                    if (isset($sessionData[$cCode][$srcGateName]) && count($sessionData[$cCode][$srcGateName]) > 0) {

                        foreach ($sessionData[$cCode][$srcGateName] as $cnt => $dDSpec) {
                            foreach ($dDSpec as $cnt2 => $dSpec) {
                                $subParams = array();
                                if (isset($tComSpec['loop'])) {
                                    foreach ($tComSpec['loop'] as $key => $value) {

                                        $realValue = makeValue($value, $sessionData[$cCode][$srcGateName][$cnt][$cnt2], $sessionData[$cCode][$srcGateName][$cnt][$cnt2], 0);
                                        $subParams['loop'][$key] = $realValue;

                                    }
                                }
                                if (isset($tComSpec['static'])) {
                                    foreach ($tComSpec['static'] as $key => $value) {

                                        $realValue = makeValue($value, $sessionData[$cCode][$srcGateName][$cnt][$cnt2], $sessionData[$cCode][$srcGateName][$cnt][$cnt2], 0);
                                        $subParams['static'][$key] = $realValue;
                                        cekBiru("$key diisi dengan $realValue");

                                    }

                                    if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                        foreach ($paramPatchers[$comName] as $k => $v) {
                                            if (!isset($subParams['static'][$k])) {
                                                $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                            }
                                        }
                                    }
                                    if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                                        $jenis = $sessionData[$cCode]['main']['jenis'];
                                        foreach ($paramForceFillers[$comName] as $k => $v) {
                                            $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                            cekorange(":: $k diisikan dengan " . $subParams['static'][$k]);
                                        }
                                    }

                                    $subParams['static']["fulldate"] = date("Y-m-d");
                                    $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                    $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . "";
                                }

                                if (sizeof($subParams) > 0) {
                                    $tmpOutParams[$cCtr][] = $subParams;
                                }
                                echo "<script>top.writeProgress('" . isset($subParams['static']['name']) ? $subParams['static']['name'] : "" . " " . isset($subParams['static']['extern_nama']) ? $subParams['static']['extern_nama'] : "" . " " . isset($subParams['static']['nama']) ? $subParams['static']['nama'] : "" . "');</script>";

                            }

                        }
                    }
                }

                foreach ($iterator as $cCtr => $tComSpec) {
                    if (count($tmpOutParams[$cCtr]) > 0) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        echo "sub-postProcessor: $comName, sending values <br>";
                        echo "<script>top.writeProgress('SENDING SUB-PROCESSORS ($comName)...', 'head');</script>";
                        $mdlName = "Com" . ucfirst($comName);
                        $this->CI->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();

                        $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        cekBiru($this->CI->db->last_query());
                    }

                }
            }
            //endregion


            //region processing main-post-processors, always
            //<editor-fold desc="----------postProc">

            $iterator = isset($configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]['master']) ? $configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]['master'] : array();
            if (sizeof($iterator) > 0) {
                echo "<script>top.writeProgress('MEMPROSES MAIN-PROCESSORS...', 'head');</script>";
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    echo "post-processor: $comName<br>";

                    $dSpec = $sessionData[$cCode][$srcGateName];
                    $tmpOutParams = array();
                    if (isset($tComSpec['loop'])) {
                        foreach ($tComSpec['loop'] as $key => $value) {

                            $realValue = makeValue($value, $sessionData[$cCode][$srcGateName], $sessionData[$cCode][$srcGateName], 0);
                            $tmpOutParams['loop'][$key] = $realValue;

                        }
                    }
                    if (isset($tComSpec['static'])) {
                        //cekHere("DISINI OIII");
                        foreach ($tComSpec['static'] as $key => $value) {

                            $realValue = makeValue($value, $sessionData[$cCode][$srcGateName], $sessionData[$cCode][$srcGateName], 0);
                            $tmpOutParams['static'][$key] = $realValue;

                        }
                        if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                            foreach ($paramPatchers[$comName] as $k => $v) {
                                if (!isset($tmpOutParams['static'][$k])) {
                                    $tmpOutParams['static'][$k] = isset($$v) ? $$v : "_v";
                                    echo "<script>top.writeProgress(':: $key diisikan dengan " . $tmpOutParams['static'][$k] . ");</script>";
                                }
                            }
                        }
                        if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                            $jenis = $sessionData[$cCode]['main']['jenis'];
                            foreach ($paramForceFillers[$comName] as $k => $v) {
                                $tmpOutParams['static'][$k] = isset($$v) ? $$v : "_v";
                                echo "<script>top.writeProgress(':: $key diisikan dengan " . $tmpOutParams['static'][$k] . ");</script>";
                            }
                        }
                        $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . "";


                    }
                    if (isset($tComSpec['static2'])) {
                        //cekHere("DISINI OIII");
                        foreach ($tComSpec['static2'] as $key => $value) {

                            $realValue = makeValue($value, $sessionData[$cCode][$srcGateName][$cCtr], $sessionData[$cCode][$srcGateName][$cCtr], 0);
                            $tmpOutParams['static2'][$key] = $realValue;

                        }
                        if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                            foreach ($paramPatchers[$comName] as $k => $v) {
                                if (!isset($subParams['static'][$k])) {
                                    $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                }
                            }
                        }
                        if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                            $jenis = $sessionData[$cCode]['main']['jenis'];
                            foreach ($paramForceFillers[$comName] as $k => $v) {
                                $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                            }
                        }
                        $tmpOutParams['static2']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static2']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static2']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . "";


                    }

                    //lgShowError("Ada kesalahan",);
                    $mdlName = "Com" . ucfirst($comName);
                    $this->CI->load->model("Coms/" . $mdlName);
                    $m = new $mdlName();

                    //                cekBiru("kiriman komponem $comName");
                    //                                    arrPrint($tmpOutParams);
                    $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);


                }
            }


            //</editor-fold>
            //endregion

            //region ----------subcomponents GESER KE CLI
            $iterator = isset($configCoreMasterModulJenis['components'][$jenisTrTarget]['detail']) ? $configCoreMasterModulJenis['components'][$jenisTrTarget]['detail'] : array();
            $componentConfig['detail'] = $iterator;
            if ($pakai_cli == 1) {

            }
            else {
                if (sizeof($iterator) > 0) {
                    $compValidators = ($this->CI->config->item('transaksi_value_required_components') != null) ? $this->CI->config->item('transaksi_value_required_components') : array();
                    $filterNeeded = false;
                    if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                        $filterNeeded = true;
                    }
                    foreach ($iterator as $cCtr => $tComSpec) {
                        //                $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];

                        echo "sub-component: $comName, $srcGateName, initializing values <br>";
                        $tmpOutParams[$cCtr] = array();
                        foreach ($sessionData[$cCode][$srcGateName] as $id => $dSpec) {
                            cekmerah("mengevaluasi $srcGateName..");
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $sessionData[$cCode][$srcGateName][$id][$comName], $comName);
                                $tComSpec['comName'] = $comName;
                                $iterator[$cCtr]['comName'] = $comName;
                            }

                            $filterNeeded = false;
                            $mdlName = "Com" . ucfirst($comName);
                            if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                $filterNeeded = true;
                            }


                            $subParams = array();
                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {
                                    if (substr($key, 0, 1) == "{") {
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        $key = str_replace($key, $sessionData[$cCode][$srcGateName][$id][$key], $key);
                                    }
                                    $realValue = makeValue($value, $sessionData[$cCode][$srcGateName][$id], $sessionData[$cCode][$srcGateName][$id], 0);
                                    $subParams['loop'][$key] = $realValue;
                                    cekKuning("LOOP: $key diisi dengan $realValue");

                                    if ($filterNeeded) {
                                        if ($subParams['loop'][$key] == 0) {
                                            unset($subParams['loop'][$key]);
                                        }
                                    }
                                }
                            }
                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {

                                    $realValue = makeValue($value, $sessionData[$cCode][$srcGateName][$id], $sessionData[$cCode][$srcGateName][$id], 0);
                                    $subParams['static'][$key] = $realValue;
                                    cekKuning("STATIC: $key diisi dengan $realValue");

                                }
                                if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                    foreach ($paramPatchers[$comName] as $k => $v) {
                                        if (!isset($subParams['static'][$k])) {
                                            $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                            cekOrange("fill :: $comName :: $k => " . $subParams['static'][$k]);
                                        }
                                    }
                                }
                                if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                                    //                            cekOrange("comName:: $comName");
                                    $jenis = $sessionData[$cCode]['main']['jenis'];
                                    foreach ($paramForceFillers[$comName] as $k => $v) {
                                        $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                        cekOrange("fillforce :: $comName :: $k => " . $subParams['static'][$k]);
                                    }
                                }
                                $subParams['static']["fulldate"] = date("Y-m-d");
                                $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . "";
                            }
                            cekHitam("cetak subParams");
                            arrPrint($subParams);
                            if (sizeof($subParams) > 0) {
                                if ($filterNeeded) {
                                    if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                    }
                                }
                                else {

                                    $tmpOutParams[$cCtr][] = $subParams;
                                }
                            }
                        }

                        $componentGate['detail'][$cCtr] = $subParams;
                    }


                    $it = 0;
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $it++;


                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];

                        echo "sub component #$it: $comName, sending values <br>";

                        $mdlName = "Com" . ucfirst($comName);
                        $this->CI->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();


                        if (sizeof($tmpOutParams[$cCtr]) > 0) {
                            $tobeExecuted = true;
                        }
                        else {
                            $tobeExecuted = false;
                        }


                        if ($tobeExecuted) {
                            $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        }
                        else {
                            cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                        }
                    }
                }
                else {
                    //cekKuning("subcomponents is not set");
                }
            }

            //endregion

            //region ----------components
            //<editor-fold desc="----------components">
            $componentJurnal = array();
            $componentGate['master'] = array();
            $componentConfig['master'] = array();
            if (isset($configCoreMasterModulJenis['relativeComponets']) && $configCoreMasterModulJenis['relativeComponets'] == true) {
                $iterator = isset($sessionData[$cCode]['revert']['jurnal'][$stepNum]['master']) ? $sessionData[$cCode]['revert']['jurnal'][$stepNum]['master'] : array();
            }
            else {
                if (isset($sessionData[$cCode]['componentsBuilder'][$stepNum]['master'])) {
                    $iterator = $sessionData[$cCode]['componentsBuilder'][$stepNum]['master'];
                }
                elseif (isset($configCoreMasterModulJenis['components'][$jenisTrTarget]['master'])) {
                    $iterator = $configCoreMasterModulJenis['components'][$jenisTrTarget]['master'];
                }
                else {
                    $iterator = array();
                }
            }


            if (sizeof($iterator) > 0) {
                echo "<script>top.writeProgress('KOMPONEN...', 'head');</script>";
                $componentConfig['master'] = $iterator;

                $it = 0;
                //==filter nilai, jika NOL tidak dikirim, sesuai config==
                $compValidators = ($this->CI->config->item('transaksi_value_required_components') != null) ? $this->CI->config->item('transaksi_value_required_components') : array();
                foreach ($iterator as $cCtr => $tComSpec) {
                    //                cekPink($tComSpec);
                    //                mati_disini();
                    $it++;
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    echo "component #$it: $comName :: $srcGateName <br>";

                    $dSpec = $sessionData[$cCode][$srcGateName];
                    $tmpOutParams = array();
                    if (isset($tComSpec['loop'])) {
                        foreach ($tComSpec['loop'] as $key => $value) {
                            if (substr($key, 0, 1) == "{") {
                                $key = trim($key, "{");
                                $key = trim($key, "}");
                                //                            $key = str_replace($key, $sessionData[$cCode]['main'][$key], $key);
                                $key = str_replace($key, $sessionData[$cCode][$srcGateName][$key], $key);
                            }
                            $realValue = makeValue($value, $sessionData[$cCode][$srcGateName], $sessionData[$cCode][$srcGateName], 0);
                            if ($key != null) {
                                $tmpOutParams['loop'][$key] = $realValue;
                            }

                        }
                    }
                    //                cekBiru($tmpOutParams);
                    //                mati_disini(__LINE__);
                    if (isset($tComSpec['static'])) {
                        foreach ($tComSpec['static'] as $key => $value) {

                            $realValue = makeValue($value, $sessionData[$cCode][$srcGateName], $sessionData[$cCode][$srcGateName], 0);
                            $tmpOutParams['static'][$key] = $realValue;
                            cekHijau(":: NORMAL :: $key => $realValue ::");
                        }
                        if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                            cekHijau(":: masuk ke PATCHER ::");
                            foreach ($paramPatchers[$comName] as $k => $v) {
                                cekHijau(":: ada yang mau di-PATCHER ::");
                                arrPrint($tmpOutParams['static']);
                                if (!isset($tmpOutParams['static'][$k])) {
                                    $tmpOutParams['static'][$k] = isset($$v) ? $$v : "_v";
                                    cekHijau(":: PATCHER :: $key => $realValue ::");
                                }

                            }
                        }
                        else {
                            cekMerah(":: TIDAK TERMASUK PATCHER ::");
                        }
                        if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                            $jenis = $sessionData[$cCode]['main']['jenis'];
                            foreach ($paramForceFillers[$comName] as $k => $v) {
                                $tmpOutParams['static'][$k] = isset($$v) ? $$v : "_v";
                                cekHijau(":: FORCEFILL :: $key => $realValue ::");
                            }
                        }
                        $tmpOutParams['static']["urut"] = $cCtr;
                        $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . "";


                    }
                    if (isset($tComSpec['static2'])) {
                        foreach ($tComSpec['static2'] as $key => $value) {

                            $realValue = makeValue($value, $sessionData[$cCode][$srcGateName], $sessionData[$cCode][$srcGateName], 0);
                            $tmpOutParams['static2'][$key] = $realValue;

                        }
                        if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                            foreach ($paramPatchers[$comName] as $k => $v) {
                                if (!isset($subParams['static'][$k])) {
                                    $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                }
                            }
                        }
                        if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                            $jenis = $sessionData[$cCode]['main']['jenis'];
                            foreach ($paramForceFillers[$comName] as $k => $v) {
                                $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                            }
                        }
                        $tmpOutParams['static2']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static2']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static2']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh ";


                    }

                    //lgShowError("Ada kesalahan",);
                    $mdlName = "Com" . ucfirst($comName);
                    $this->CI->load->model("Coms/" . $mdlName);
                    $m = new $mdlName();

                    //===filter value nol, jika harus difilter
                    $tobeExecuted = true;

                    if (in_array($mdlName, $compValidators)) {

                        $loopParams = isset($tmpOutParams['loop']) ? $tmpOutParams['loop'] : array();
                        if (sizeof($loopParams) > 0) {
                            foreach ($loopParams as $key => $val) {
                                cekmerah("$comName : $key = $val ");
                                if ($val == 0) {
                                    unset($tmpOutParams['loop'][$key]);
                                }
                            }
                        }
                        if (sizeof($tmpOutParams['loop']) < 1) {
                            $tobeExecuted = false;
                        }

                    }


                    if ($tobeExecuted) {
                        cekBiru("kiriman komponen $comName");
                        arrPrint($tmpOutParams);
                        $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    }
                    else {
                        cekBiru("komponem $comName tidak memenuhi syarat untuk ditulis");
                    }

                    $componentGate['master'][$cCtr] = $tmpOutParams;
                    if ($comName == "Jurnal") {
                        $componentJurnal[] = $tmpOutParams;
                    }
                }
            }
            else {
                //cekKuning("components is not set");
            }


            //endregion

            //region nulis paymentSource
            $stepCode = $configUiMasterModulJenis['steps'][$stepNum]['target'];
            $paymentSources = $this->CI->config->item("payment_source");
            if (array_key_exists($stepCode, $paymentSources)) {
                $payConfigs = isset($paymentSources[$stepCode][$stepNum]) ? $paymentSources[$stepCode][$stepNum] : array();
                if (sizeof($payConfigs) > 0) {
                    foreach ($payConfigs as $paymentSrcConfig) {
                        $valueLabel = isset($paymentSrcConfig['label_key']) ? $paymentSrcConfig['label_key'] : $paymentSrcConfig['label'];
                        $valueSrc = $paymentSrcConfig['valueSrc'];
                        $externSrc = $paymentSrcConfig['externSrc'];
                        $valueAdd = isset($sessionData[$cCode]['main'][$paymentSrcConfig['addValueValidator']]) ? $sessionData[$cCode]['main'][$paymentSrcConfig['addValueValidator']] : 0;
                        if (isset($paymentSrcConfig['model'])) {
                            $mdlName = $paymentSrcConfig['model'];
                            $this->CI->load->model("Mdls/$mdlName");
                            $pMdl = New $mdlName();
                            $pTmpMdl = $pMdl->lookupAll()->result();
                            $pTmpMdlResult = array();
                            if (sizeof($pTmpMdl) > 0) {
                                foreach ($pTmpMdl as $pTmpMdlSpec) {
                                    $pTmpMdlResult[$pTmpMdlSpec->id] = $pTmpMdlSpec;
                                }
                            }
                        }
                        else {
                            $pTmpMdlResult = array();
                        }

                        if (isset($sessionData[$cCode]['main'][$valueSrc]) && $sessionData[$cCode]['main'][$valueSrc] > 0) {
                            if (isset($externSrc['extern_label2'])) {
                                //cek ada isinya atau kosong
                                $cek = strlen($sessionData[$cCode]['main'][$externSrc['extern_label2']]) > 4 ? "" : matiHere("jenis biaya tidak dikenali " . __LINE__);//
                            }
                            //region cek duplikasi paymentsource
                            $tr->setFilters(array());
                            $tr->addFilter("transaksi_id='$insertID'");
                            $tr->addFilter("target_jenis='" . $paymentSrcConfig['jenisTarget'] . "'");
                            // $tr->addFilter("target_jenis='759'");
                            $validateIsInserted = $tr->lookUpAllPaymentSrc()->result();
                            if (sizeof($validateIsInserted) > 0) {
                                matiHEre("Gagal menulis transaksi. Silahkan relogin untuk membersihkan sesi demi menghindari duplikasi data, dan coba kembali transaksi yang gagal");
                            }
                            //endregion

                            //-----------------------
                            cekHitam("valuelabel: $valueLabel, valueSrc: $valueSrc");
                            $this->CI->load->helper("he_payment_source");
                            //                        paymentSource($this->jenisTr, $componentJurnal, $sessionData[$cCode]['main'], $valueLabel, $valueSrc, $valueAdd);
                            //-----------------------

                            $arrPymSrc = array(
                                "jenis" => $stepCode,
                                "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                                "extern_id" => isset($sessionData[$cCode]['main'][$externSrc['id']]) ? $sessionData[$cCode]['main'][$externSrc['id']] : "",
                                "extern_nama" => isset($sessionData[$cCode]['main'][$externSrc['nama']]) ? $sessionData[$cCode]['main'][$externSrc['nama']] : "",
                                "nomer" => $tmpNomorNota2,
                                "label" => $paymentSrcConfig['label'],

                                "tagihan" => $sessionData[$cCode]['main'][$valueSrc],
                                "terbayar" => 0,
                                "sisa" => $sessionData[$cCode]['main'][$valueSrc],

                                "cabang_id" => $sessionData[$cCode]['main']['placeID'],
                                "cabang_nama" => $sessionData[$cCode]['main']['placeName'],
                                "oleh_id" => $sessionData[$cCode]["main"]["oleh_id"],
                                "oleh_nama" => $sessionData[$cCode]["main"]["oleh_nama"],
                                "dtime" => date("Y-m-d H:i:s"),
                                "fulldate" => date("Y-m-d"),
                                "valas_id" => isset($externSrc['valasId']) && isset($sessionData[$cCode]['main'][$externSrc['valasId']]) ? $sessionData[$cCode]['main'][$externSrc['valasId']] : '',
                                "valas_nama" => isset($externSrc['valasLabel']) && isset($sessionData[$cCode]['main'][$externSrc['valasLabel']]) ? $sessionData[$cCode]['main'][$externSrc['valasLabel']] : '',
                                "valas_nilai" => isset($externSrc['valasValue']) && isset($sessionData[$cCode]['main'][$externSrc['valasValue']]) ? $sessionData[$cCode]['main'][$externSrc['valasValue']] : '',

                                "tagihan_valas" => isset($externSrc['valasTagihan']) && isset($sessionData[$cCode]['main'][$externSrc['valasTagihan']]) ? $sessionData[$cCode]['main'][$externSrc['valasTagihan']] : '',
                                "terbayar_valas" => 0,
                                "sisa_valas" => isset($externSrc['valasSisa']) && isset($sessionData[$cCode]['main'][$externSrc['valasSisa']]) ? $sessionData[$cCode]['main'][$externSrc['valasSisa']] : '',

                                //                            "extern_label2" => isset($sessionData[$cCode]['main']['pihakMainName']) ? $sessionData[$cCode]['main']['pihakMainName'] : "",
                                "extern_label2" => (isset($externSrc['extern_label2']) && ($sessionData[$cCode]['main'][$externSrc['extern_label2']])) ? $sessionData[$cCode]['main'][$externSrc['extern_label2']] : "",

                                "dpp_ppn" => (isset($externSrc['dpp_ppn']) && ($sessionData[$cCode]['main'][$externSrc['dpp_ppn']])) ? $sessionData[$cCode]['main'][$externSrc['dpp_ppn']] : 0,
                                "ppn" => (isset($externSrc['ppn']) && ($sessionData[$cCode]['main'][$externSrc['ppn']])) ? $sessionData[$cCode]['main'][$externSrc['ppn']] : 0,
                                "ppn_approved" => (isset($externSrc['ppn_approved']) && ($sessionData[$cCode]['main'][$externSrc['ppn_approved']])) ? $sessionData[$cCode]['main'][$externSrc['ppn_approved']] : 0,
                                "ppn_sisa" => (isset($externSrc['ppn']) && ($sessionData[$cCode]['main'][$externSrc['ppn']])) ? $sessionData[$cCode]['main'][$externSrc['ppn']] : "",
                                "ppn_status" => (isset($externSrc['ppn_status'])) ? $externSrc['ppn_status'] : 0,
                                "extern_nilai2" => (isset($externSrc['extern_nilai2']) && ($sessionData[$cCode]['main'][$externSrc['extern_nilai2']])) ? $sessionData[$cCode]['main'][$externSrc['extern_nilai2']] : 0,
                                "extern_date2" => (isset($externSrc['extern_date2']) && ($sessionData[$cCode]['main'][$externSrc['extern_date2']])) ? $sessionData[$cCode]['main'][$externSrc['extern_date2']] : "",
                                "pph_23" => (isset($externSrc['pph_23']) && ($sessionData[$cCode]['main'][$externSrc['pph_23']])) ? $sessionData[$cCode]['main'][$externSrc['pph_23']] : "",

                                "npwp" => (isset($externSrc['npwp']) && ($sessionData[$cCode]['main'][$externSrc['npwp']])) ? $sessionData[$cCode]['main'][$externSrc['npwp']] : "",
                                "extern2_id" => (isset($externSrc['extern2_id']) && ($sessionData[$cCode]['main'][$externSrc['extern2_id']])) ? $sessionData[$cCode]['main'][$externSrc['extern2_id']] : "",
                                "extern2_nama" => (isset($externSrc['extern2_nama']) && ($sessionData[$cCode]['main'][$externSrc['extern2_nama']])) ? $sessionData[$cCode]['main'][$externSrc['extern2_nama']] : "",
                                "ppn_pph_faktor" => (isset($externSrc['ppn_pph_faktor']) && ($sessionData[$cCode]['main'][$externSrc['ppn_pph_faktor']])) ? $sessionData[$cCode]['main'][$externSrc['ppn_pph_faktor']] : "",
                                "extern_jenis" => (isset($externSrc['extern_jenis']) && ($sessionData[$cCode]['main'][$externSrc['extern_jenis']])) ? $sessionData[$cCode]['main'][$externSrc['extern_jenis']] : "",
                                "extern_nilai3" => (isset($externSrc['extern_nilai3']) && ($sessionData[$cCode]['main'][$externSrc['extern_nilai3']])) ? $sessionData[$cCode]['main'][$externSrc['extern_nilai3']] : "",
                                "extern_nilai4" => (isset($externSrc['extern_nilai4']) && ($sessionData[$cCode]['main'][$externSrc['extern_nilai4']])) ? $sessionData[$cCode]['main'][$externSrc['extern_nilai4']] : "",
                                "npwp" => (isset($externSrc['npwp']) && ($sessionData[$cCode]['main'][$externSrc['npwp']])) ? $sessionData[$cCode]['main'][$externSrc['npwp']] : "",
                                //                            "extern_nilai2" => (isset($externSrc['extern_nilai2']) && ($sessionData[$cCode]['main'][$externSrc['extern_nilai2']])) ? $sessionData[$cCode]['main'][$externSrc['extern_nilai2']] : "",
                                "payment_locked" => (isset($externSrc['payment_locked']) && ($sessionData[$cCode]['main'][$externSrc['payment_locked']])) ? $sessionData[$cCode]['main'][$externSrc['payment_locked']] : 0,
                                "cash_account" => (isset($externSrc['cash_account']) && ($sessionData[$cCode]['main'][$externSrc['cash_account']])) ? $sessionData[$cCode]['main'][$externSrc['cash_account']] : 0,
                                "cash_account_nama" => (isset($externSrc['cash_account_nama']) && ($sessionData[$cCode]['main'][$externSrc['cash_account_nama']])) ? $sessionData[$cCode]['main'][$externSrc['cash_account_nama']] : 0,
                            );
                            $tr->writePaymentSrc($insertID, $arrPymSrc);

                        }


                        cekMerah($this->CI->db->last_query());
                    }
                }

            }
            else {
                cekMerah("TIDAK nulis paymentSrc");
            }

            $addPaymentSource = isset($configUiMasterModulJenis['steps'][$stepNum]['additionalStep']['shippingService']) ? $configUiMasterModulJenis['steps'][$stepNum]['additionalStep']['shippingService'] : array();

            //endregion

            //region nulis paymentAntiSource
            $stepCode = $configUiMasterModulJenis['steps'][$stepNum]['target'];
            $paymentSources = $this->CI->config->item("payment_antiSource");
            if (array_key_exists($stepCode, $paymentSources)) {
                cekMerah(":: starting PAYMENT ANTI SOURCE");
                $payConfigs = $paymentSources[$stepCode];
                if (sizeof($payConfigs) > 0) {
                    foreach ($payConfigs as $paymentSrcConfig) {
                        //					$paymentSrcConfig = $paymentSources[$stepCode];
                        $valueSrc = $paymentSrcConfig['valueSrc'];
                        $externSrc = $paymentSrcConfig['externSrc'];
                        $tr->writePaymentAntiSrc($insertID, array(
                            "jenis" => $stepCode,
                            "target_jenis" => $paymentSrcConfig['jenisTarget'],
                            "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                            "extern_id" => $sessionData[$cCode]['main'][$externSrc['id']],
                            "extern_nama" => $sessionData[$cCode]['main'][$externSrc['nama']],
                            "nomer" => $tmpNomorNota2,
                            "label" => $paymentSrcConfig['label'],
                            "tagihan" => $sessionData[$cCode]['main'][$valueSrc],
                            "terbayar" => 0,
                            "sisa" => $sessionData[$cCode]['main'][$valueSrc],
                            "cabang_id" => $sessionData[$cCode]['main']['placeID'],
                            "cabang_nama" => $sessionData[$cCode]['main']['placeName'],
                            "oleh_id" => $sessionData[$cCode]["main"]["oleh_id"],
                            "oleh_nama" => $sessionData[$cCode]["main"]["oleh_nama"],
                            "dtime" => date("Y-m-d H:i:s"),
                            "fulldate" => date("Y-m-d"),
                        ));
                        //cekMerah($this->CI->db->last_query());
                    }
                }

            }
            else {
                //cekMerah("TIDAK nulis paymentSrc");
            }
            //endregion

            //region nulis uangMukaSource
            /*dimatiin geser ke ComUangmukaSourceDetail karena ada di items.
            /*revisi tanggal 27 mei 2020 subject digeser ke vendor dari jenis transaksi misal uangmuka asuransi,uang muka pembelian ->uang muka.
             *
             */
            $stepCode = $configUiMasterModulJenis['steps'][$stepNum]['target'];
            $uangMukaSources = $this->CI->config->item("uang_muka");

            if (array_key_exists($stepCode, $uangMukaSources)) {
                cekMerah(":: starting UANG MUKA  SOURCE");
                //            matiHere();
                $uangMukaConfigs = isset($uangMukaSources[$stepCode][$stepNum]) ? $uangMukaSources[$stepCode][$stepNum] : array();
                if (sizeof($uangMukaConfigs) > 0) {
                    $cekPreValue = "";
                    $this->CI->load->model("Mdls/MdlPaymentUangMuka");
                    $l = new MdlPaymentUangMuka();
                    foreach ($uangMukaConfigs as $uangMukaSrcConfig) {
                        //					$paymentSrcConfig = $paymentSources[$stepCode];
                        //                    arrPrint($uangMukaSrcConfig);
                        $valueSrc = $uangMukaSrcConfig['valueSrc'];
                        $externSrc = $uangMukaSrcConfig['externSrc'];
                        $l->addFilter("extern_id='" . $sessionData[$cCode]['main'][$externSrc['id']] . "'");
                        $l->addFilter("extern_label2='" . $externSrc['extLabel'] . "'");
                        $tmpUm = $l->lookupAll()->result();
                        //                    arrPrint($tmpUm);
                        if (sizeof($tmpUm) > 0) {
                            //update here broo
                            $preTagihan = $tmpUm[0]->tagihan;
                            $preSisa = $tmpUm[0]->sisa;

                            $newTahigan = $preTagihan + $sessionData[$cCode]['main'][$valueSrc];
                            $newsisa = $preSisa + $sessionData[$cCode]['main'][$valueSrc];
                            $update = array(
                                "tagihan" => $newTahigan,
                                "sisa" => $newsisa,
                            );
                            $where = array(
                                "extern_id" => $sessionData[$cCode]['main'][$externSrc['id']],
                            );
                            $tr->updateUangMukaSrc($where, $update);
                            cekHitam($this->CI->db->last_query());
                        }
                        else {
                            //insertbaru brooo
                            $tr->writeUangMukaSrc($insertID, array(
                                "jenis" => $stepCode,
                                "target_jenis" => $uangMukaSrcConfig['jenisTarget'],
                                "reference_jenis" => $uangMukaSrcConfig['jenisSrc'],
                                "extern_id" => $sessionData[$cCode]['main'][$externSrc['id']],
                                "extern_nama" => $sessionData[$cCode]['main'][$externSrc['nama']],
                                "nomer" => "",
                                "note" => "",
                                "label" => $uangMukaSrcConfig['label'],
                                "tagihan" => $sessionData[$cCode]['main'][$valueSrc],
                                "terbayar" => 0,
                                "sisa" => $sessionData[$cCode]['main'][$valueSrc],
                                "cabang_id" => $sessionData[$cCode]['main']['placeID'],
                                "cabang_nama" => $sessionData[$cCode]['main']['placeName'],
                                "oleh_id" => $sessionData[$cCode]["main"]["oleh_id"],
                                "oleh_nama" => $sessionData[$cCode]["main"]["oleh_nama"],
                                "dtime" => date("Y-m-d H:i:s"),
                                "fulldate" => date("Y-m-d"),
                                "extern_label2" => $externSrc['extLabel'],
                            ));
                        }
                        cekMerah($this->CI->db->last_query());
                    }
                }
                else {
                    cekLime("not write uang muka");
                }

            }
            else {
                cekMerah("not write uang muka");
            }
            //endregion

            validateAllBalances($cabangTujuanID);


            //region connecting antar cabang
            $configUiMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiUi");
            $configCoreMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiCore");
            $configLayoutMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiLayout");
            $configValuesMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiValues");
            $modul_transaksi = $this->CI->config->item("heTransaksi_ui")[$this->jenisTr]["modul"];
            $configUiMasterModulOrigJenis = loadConfigModulJenis_he_misc($origJenis, "coTransaksiUi");
            $configCoreMasterModulOrigJenis = loadConfigModulJenis_he_misc($origJenis, "coTransaksiCore");
            $configLayoutMasterModulOrigJenis = loadConfigModulJenis_he_misc($origJenis, "coTransaksiLayout");

            $steps = isset($configUiMasterModulOrigJenis['steps']) ? $configUiMasterModulOrigJenis['steps'] : array();
            $connector = isset($configUiMasterModulOrigJenis['connectTo']) ? $configUiMasterModulOrigJenis['connectTo'] : "";
            $preReplacer = isset($configUiMasterModulJenis['replacerConnectTo']) ? $configUiMasterModulJenis['replacerConnectTo'] : array();
            $validateValueConnector = isset($configUiMasterModulJenis['connectoValidate'][$stepNum]) ? $configUiMasterModulJenis['connectoValidate'][$stepNum] : array();
            $mongoListConnect = array();
            $mongRegIDConnect = array();
            $insertConnectingID = 0;
            if (strlen($connector) > 0) {
                cekMerah("TO BE CONNECT TO $connector |$stepNum|" . sizeof($steps));
                if (isset($configUiMasterModulJenis['connectoValidate'][$stepNum])) {
                    $validateValueConnector = $configUiMasterModulJenis['connectoValidate'][$stepNum];
                    $preVal = $sessionData[$cCode]['main'][$validateValueConnector];
                    $stepNum = $preVal > 0 ? $stepNum : "1000";//1000 untuk nglewatin step biar gak jalan connectingnya karena nilai yang dicari 0 kasusnya cash in advance ppn sudah masuk pusat tidak perlu diterbitkan auto dorong ppn ke pusat
                }
                if ($stepNum == sizeof($steps)) {
                    cekMerah("NOW CONNECTING to $connector");

                    $configUiMasterModulJenis = loadConfigModulJenis_he_misc($connector, "coTransaksiUi");
                    $configCoreMasterModulJenis = loadConfigModulJenis_he_misc($connector, "coTransaksiCore");
                    $configLayoutMasterModulJenis = loadConfigModulJenis_he_misc($connector, "coTransaksiLayout");
                    $configValuesMasterModulJenis = loadConfigModulJenis_he_misc($connector, "coTransaksiValues");
                    $modul_transaksi = $this->CI->config->item("heTransaksi_ui")[$connector]["modul"];

                    if (sizeof($configUiMasterModulJenis) == 0) {
                        die("kode connector tidak dikenali!");
                    }
                    if (sizeof($configUiMasterModulJenis['steps']) < 2) {
                        die("konfigurasi connector harus memiliki step lebih dari satu!");
                    }


                    $oldCode = $cCode;
                    $cCode = "_TR_" . $connector;

                    $sessionData[$cCode] = array();
                    $sessionData[$cCode] = array(
                        "main" => $sessionData[$oldCode]['main'],
                        "items" => $sessionData[$oldCode]['items'],
                        "items2" => $sessionData[$oldCode]['items2'],
                        "items2_sum" => $sessionData[$oldCode]['items2_sum'],
                        "items3" => $sessionData[$oldCode]['items3'],
                        "items3_sum" => $sessionData[$oldCode]['items3_sum'],
                        "items4" => $sessionData[$oldCode]['items4'],
                        "items4_sum" => $sessionData[$oldCode]['items4_sum'],
                        "items5" => $sessionData[$oldCode]['items5'],
                        "items5_sum" => $sessionData[$oldCode]['items5_sum'],
                        "items6" => $sessionData[$oldCode]['items6'],
                        "items6_sum" => $sessionData[$oldCode]['items6_sum'],
                        "items7" => $sessionData[$oldCode]['items7'],
                        "items7_sum" => $sessionData[$oldCode]['items7_sum'],
                        "items8" => $sessionData[$oldCode]['items8'],
                        "items8_sum" => $sessionData[$oldCode]['items8_sum'],
                        "items9_sum" => $sessionData[$oldCode]['items9_sum'],
                        "items10_sum" => $sessionData[$oldCode]['items10_sum'],
                        "items_noapprove" => $sessionData[$oldCode]['items_noapprove'],
                        "tableIn_master" => $sessionData[$oldCode]['tableIn_master'],
                        "tableIn_detail" => $sessionData[$oldCode]['tableIn_detail'],
                        "rsltItems" => $sessionData[$oldCode]['rsltItems'],
                        "tableIn_detail_rsltItems" => $sessionData[$oldCode]['tableIn_detail_rsltItems'],
                        "tableIn_master_values" => $sessionData[$oldCode]['tableIn_master_values'],
                        "tableIn_detail_values" => $sessionData[$oldCode]['tableIn_detail_values'],
                        "tableIn_detail_values_rsltItems" => $sessionData[$oldCode]['tableIn_detail_values_rsltItems'],
                    );

                    //==replace pertama
                    $masterReplacersO = array(
                        "jenisTr" => $connector,
                        "jenisTrMaster" => $connector,
                        "jenisTrTop" => $configUiMasterModulJenis['steps'][1]['target'],
                        "jenis" => $configUiMasterModulJenis['steps'][1]['target'],
                        "jenis_label" => $configUiMasterModulJenis['steps'][1]['label'],
                        "transaksi_jenis" => $configUiMasterModulJenis['steps'][1]['target'],
                        "stepCode" => $configUiMasterModulJenis['steps'][1]['target'],
                        "placeID" => isset($preReplacer['place2ID']) ? $preReplacer['place2ID'] : $sessionData[$cCode]['main']['place2ID'],
                        "placeName" => isset($preReplacer['place2Name']) ? $preReplacer['place2Name'] : $sessionData[$cCode]['main']['place2Name'],
                        "place2ID" => $sessionData[$cCode]['main']['placeID'],
                        "place2Name" => $sessionData[$cCode]['main']['placeName'],
                        "cabangID" => isset($preReplacer['cabang2ID']) ? $preReplacer['cabang2ID'] : $sessionData[$cCode]['main']['place2ID'],
                        "cabangName" => isset($preReplacer['place2Name']) ? $preReplacer['place2Name'] : $sessionData[$cCode]['main']['place2Name'],
                        "cabang2ID" => $sessionData[$cCode]['main']['placeID'],
                        "cabang2Name" => $sessionData[$cCode]['main']['placeName'],
                        //
                        "gudang2ID" => $sessionData[$cCode]['main']['gudangID'],
                        "gudang2Name" => $sessionData[$cCode]['main']['gudangName'],
                        "gudangID" => isset($preReplacer['gudang2ID']) ? $preReplacer['gudang2ID'] : $sessionData[$cCode]['main']['gudang2ID'],
                        "gudangName" => isset($preReplacer['gudang2Name']) ? $preReplacer['gudang2Name'] : $sessionData[$cCode]['main']['gudang2Name'],
                        "pihakID" => isset($sessionData[$cCode]['main']['placeID']) ? $sessionData[$cCode]['main']['placeID'] : "",
                        "pihakName" => isset($sessionData[$cCode]['main']['placeName']) ? $sessionData[$cCode]['main']['placeName'] : "",
                        "pihakName2" => $sessionData[$cCode]['main']['placeName'],
                        "gudang" => $sessionData[$cCode]['main']['gudangID'],
                        "gudang__name" => $sessionData[$cCode]['main']['gudangName'],
                        "gudang__label" => $sessionData[$cCode]['main']['gudangName'],
                        "efaktur_source" => isset($preReplacer['efaktur_source']) ? $sessionData[$cCode]['main']['nomer'] : "",

                    );
                    foreach ($masterReplacersO as $key => $val) {
                        $sessionData[$cCode]['main'][$key] = $val;
                        //                    $sessionData[$cCode]['main'][$key] = $val;
                    }
                    $masterReplacers = array(
                        "inv" => $tmpNomorNota,
                        "jenis_master" => $connector,
                        "jenis_top" => $configUiMasterModulJenis['steps'][1]['target'],
                        "jenis" => $configUiMasterModulJenis['steps'][1]['target'],
                        "jenis_label" => $configUiMasterModulJenis['steps'][1]['label'],
                        "transaksi_jenis" => $configUiMasterModulJenis['steps'][1]['target'],
                        "cabang_id" => isset($preReplacer['cabang2ID']) ? $preReplacer['cabang2ID'] : $sessionData[$cCode]['tableIn_master']['cabang2_id'],
                        "cabang_nama" => isset($preReplacer['cabang2Name']) ? $preReplacer['cabang2Name'] : $sessionData[$cCode]['tableIn_master']['cabang2_nama'],
                        "cabang2_id" => $sessionData[$cCode]['tableIn_master']['cabang_id'],
                        "cabang2_nama" => $sessionData[$cCode]['tableIn_master']['cabang_nama'],
                        "gudang_id" => isset($preReplacer['gudang2ID']) ? $preReplacer['gudang2ID'] : $sessionData[$cCode]['tableIn_master']['gudang2_id'],
                        "gudang_nama" => isset($preReplacer['gudang2Name']) ? $preReplacer['gudang2Name'] : $sessionData[$cCode]['tableIn_master']['gudang2_nama'],
                        "gudang2_id" => $sessionData[$cCode]['tableIn_master']['gudang_id'],
                        "gudang2_nama" => $sessionData[$cCode]['tableIn_master']['gudang_nama'],
                        "gudang" => $sessionData[$cCode]['tableIn_master']['gudang_id'],
                        "gudang__name" => $sessionData[$cCode]['tableIn_master']['gudang_nama'],
                        "gudang__label" => $sessionData[$cCode]['tableIn_master']['gudang_nama'],

                        "step_avail" => sizeof($configUiMasterModulJenis['steps']),
                        "step_current" => 1,
                        "step_number" => 1,
                        "next_step_code" => isset($configUiMasterModulJenis['steps'][2]) ? $configUiMasterModulJenis['steps'][2]['target'] : "",
                        "next_step_label" => isset($configUiMasterModulJenis['steps'][2]) ? $configUiMasterModulJenis['steps'][2]['label'] : "",
                        "next_group_code" => isset($configUiMasterModulJenis['steps'][2]) ? $configUiMasterModulJenis['steps'][2]['userGroup'] : "",
                        "next_step_num" => isset($configUiMasterModulJenis['steps'][2]) ? 2 : "0",
                        "efaktur_source" => isset($preReplacer['efaktur_source']) ? $sessionData[$cCode]['main']['nomer'] : "",
                        //===references
                        //                    "id_master"            => $masterID,
                        //                    "id_top"               => $topID,
                        //                    "ids_prev"             => base64_encode(serialize(array($prevProp['id']))),
                        //                    "ids_prev_intext"      => print_r(array($prevProp['id'], true)),
                        //                    "nomer_top"            => $sessionData[$cCode]['main']['nomer'],
                        //                    "nomers_prev"          => base64_encode(serialize(array($prevProp['nomer']))),
                        //                    "nomers_prev_intext"   => print_r(array($prevProp['nomer'], true)),
                        //                    "jenis_top"            => $this->jenisTr,
                        //                    "jenises_prev"        => base64_encode(serialize(array($prevProp['jenis']))),
                        //                    "jenises_prev_intext" => print_r(array($prevProp['jenis'], true)),
                    );

                    foreach ($masterReplacers as $key => $val) {
                        $sessionData[$cCode]['tableIn_master'][$key] = $val;
                    }

                    //region penomoran receipt #2
                    //<editor-fold desc="==========penomoran">
                    $this->CI->load->model("CustomCounter");
                    $cn = new CustomCounter("transaksi");
                    $cn->setType("transaksi");
                    $cn->setModul($modul_transaksi);
                    $cn->setStepCode($configUiMasterModulJenis['steps'][1]['target']);
                    $counterForNumber = array($configCoreMasterModulJenis['formatNota']);
                    if (!in_array($counterForNumber[0], $configCoreMasterModulJenis['counters'])) {
                        die(__LINE__ . " Used number should be registered in 'counters' config as well");
                    }

                    foreach ($counterForNumber as $i => $cRawParams) {
                        $cParams = explode("|", $cRawParams);
                        $cValues = array();
                        foreach ($cParams as $param) {
                            //                    $cValues[$i][$param] = $sessionData[$cCode]['main'][$param];
                            //                    echo "filling $param with " . $sessionData[$cCode]['main'][$param] . "<br>";
                            $cValues[$i][$param] = $sessionData[$cCode]['main'][$param];
                            //                    echo "filling $param with " . $sessionData[$cCode]['main'][$param] . "<br>";
                        }
                        $cRawValues = implode("|", $cValues[$i]);
                        $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);

                    }

                    $tmpNomorNotaConnecting = $tmpNomorNota2 = $paramSpec['paramString'];
                    $tmpNomorNota2Alias = formatNota("nomer_nolink", $tmpNomorNota2);

//arrprint($tmpNomorNota2);
//matiHere();
                    //</editor-fold>
                    //endregion

                    //region dynamic counters #2
                    // <editor-fold defaultstate="collapsed" desc="==========__init+update dynamic-counters ">
                    $cn = new CustomCounter("transaksi");
                    $cn->setType("transaksi");
                    $cn->setModul($modul_transaksi);
                    $cn->setStepCode($configUiMasterModulJenis['steps'][1]['target']);
                    $configCustomParams = $configCoreMasterModulJenis['counters'];
                    $configCustomParams[] = "stepCode";
                    if (sizeof($configCustomParams) > 0) {
                        $cContent = array();
                        foreach ($configCustomParams as $i => $cRawParams) {
                            $cParams = explode("|", $cRawParams);
                            $cValues = array();
                            foreach ($cParams as $param) {
                                $cValues[$i][$param] = $sessionData[$cCode]['main'][$param];
                            }
                            $cRawValues = implode("|", $cValues[$i]);
                            $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);

                            $cContent[$cRawParams][$cRawValues] = $paramSpec['value'];
                            switch ($paramSpec['id']) {
                                case 0: //===counter type is new
                                    $paramKeyRaw = print_r($cParams, true);
                                    $paramValuesRaw = print_r($cValues[$i], true);
                                    $cn->writeNewCount($cParams, $cValues[$i], $paramKeyRaw, $paramValuesRaw);
                                    break;
                                default: //===counter to be updated
                                    $cn->updateCount($paramSpec['id'], $paramSpec['value']);
                                    break;
                            }
                            //echo "<hr>";
                        }
                    }
                    $appliedCounters2 = base64_encode(serialize($cContent));
                    $appliedCounters_inText2 = print_r($cContent, true);
                    // </editor-fold>
                    //endregion

                    //region tambahan counter
                    $this->CI->load->library("CounterNumber");
                    $ccn = new CounterNumber();
                    $ccn->setCCode($cCode);
                    $ccn->setJenisTr($connector);
                    $ccn->setTransaksiGate($sessionData[$cCode]['tableIn_master']);
                    $ccn->setMainGate($sessionData[$cCode]['main']);
                    $ccn->setItemsGate($sessionData[$cCode]['items']);
                    $ccn->setItems2SumGate($sessionData[$cCode]['items2_sum']);
                    $new_counter = $ccn->getCounterNumber();
                    cekHitam("jenistr yang disett dari create " . $this->jenisTr);


                    if (isset($new_counter['main']) && sizeof($new_counter['main']) > 0) {
                        foreach ($new_counter['main'] as $ckey => $cval) {
                            $sessionData[$cCode]['tableIn_master'][$ckey] = $cval;
                            $sessionData[$cCode]['main'][$ckey] = $cval;
                        }
                    }
                    if (isset($new_counter['items']) && sizeof($new_counter['items']) > 0) {
                        foreach ($new_counter['items'] as $ikey => $iSpec) {
                            foreach ($iSpec as $iikey => $iival) {
                                $sessionData[$cCode]['items'][$ikey][$iikey] = $iival;
                            }
                        }
                    }
                    if (isset($new_counter['items2_sum']) && sizeof($new_counter['items2_sum']) > 0) {
                        foreach ($new_counter['items2_sum'] as $ikey => $iSpec) {
                            foreach ($iSpec as $iikey => $iival) {
                                $sessionData[$cCode]['items2_sum'][$ikey][$iikey] = $iival;
                            }
                        }
                    }
                    //endregion
                    $addValues = array(
                        'counters' => $appliedCounters2,
                        'counters_intext' => $appliedCounters_inText2,
                        'nomer' => $tmpNomorNota2,
                        'nomer2' => $tmpNomorNota2Alias,
                        'dtime' => date("Y-m-d H:i:s"),
                        'fulldate' => date("Y-m-d"),
                    );
                    foreach ($addValues as $key => $val) {
                        $sessionData[$cCode]['tableIn_master'][$key] = $val;
                    }

                    //===cloning nota cab1 ke cab2
                    //===daftar perbedaan
                    //== referensi_id, inv, jenis, nomer, counters, counters_inText, cabang_id, cabang_nama, cabang2_id, cabang2_nama,
                    //==replace kedua
                    $masterReplacers = array(
                        "nomer" => $tmpNomorNota2,
                        "nomer2" => $tmpNomorNota2Alias,
                        "counters" => $appliedCounters2,
                        "counters_intext" => $appliedCounters_inText2,
                    );
                    foreach ($masterReplacers as $key => $val) {
                        $sessionData[$cCode]['tableIn_master'][$key] = $val;
                    }

                    //===cloning detail/items cabang1 ke cabang2
                    //===yang direplace: sub_step_number, sub_step_current, sub_step_avail, next_substep_num, next_substep_code, next_substep_label, next_subgroup_code
                    $detailReplacers = array(
                        "sub_step_avail" => sizeof($configUiMasterModulJenis['steps']),
                        "sub_step_current" => 1,
                        "sub_step_number" => 1,
                        "next_substep_num" => $sessionData[$cCode]['tableIn_master']['next_step_num'],
                        "next_substep_code" => $sessionData[$cCode]['tableIn_master']['next_step_code'],
                        "next_substep_label" => $sessionData[$cCode]['tableIn_master']['next_step_label'],
                        "next_subgroup_code" => $sessionData[$cCode]['tableIn_master']['next_group_code'],
                    );
                    if (isset($sessionData[$cCode]['tableIn_detail']) && sizeof($sessionData[$cCode]['tableIn_detail']) > 0) {
                        foreach ($sessionData[$cCode]['tableIn_detail'] as $k => $dSpec) {
                            foreach ($dSpec as $key => $val) {
                                $sessionData[$cCode]['tableIn_detail'][$k][$key] = isset($detailReplacers[$key]) ? $detailReplacers[$key] : $val;
                            }
                        }
                    }
                    else {
                        //                    cekmerah("GAGAL tulis rincian transaksi kedua");
                    }


                    //region ----------write transaksi & transaksi_data #2
                    if (isset($sessionData[$cCode]['tableIn_master']) && sizeof($sessionData[$cCode]['tableIn_master']) > 0) {
                        $tr = new MdlPenjualanTransaksi();
                        $insertConnectingID = $insertID = $tr->writeMainEntries($sessionData[$cCode]['tableIn_master']);//                        cekUngu($this->CI->db->last_query());
                        $epID = $tr->writeMainEntries_entryPoint($insertID, $masterID, $sessionData[$cCode]['tableIn_master']);
                        $insertNum = $sessionData[$cCode]['tableIn_master']['nomer'];
                        $sessionData[$cCode]['main']['nomer'] = $insertNum;
                        if ($insertID < 1) {
                            die("Gagal saat berusaha  write transaction entry pada " . __FILE__ . " baris " . __LINE__);
                        }
                    }
                    else {
                        cekmerah("GAGAL tulis transaksi kedua");
                    }
                    if (isset($sessionData[$cCode]['tableIn_master_values']) && sizeof($sessionData[$cCode]['tableIn_master_values']) > 0) {
                        $inserMainValues = array();
                        foreach ($sessionData[$cCode]['tableIn_master_values'] as $key => $val) {
                            $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                            $inserMainValues[] = $dd;
                            $mongoListConnect['mainValues'][] = $dd;
                        }
                        if (sizeof($inserMainValues) > 0) {
                            $arrBlob = blobEncode($inserMainValues);
                            $this->CI->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                        }
                    }
                    if (isset($sessionData[$cCode]['main_add_values']) && sizeof($sessionData[$cCode]['main_add_values']) > 0) {
                        foreach ($sessionData[$cCode]['main_add_values'] as $key => $val) {
                            $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                            $mongoListConnect['mainValues'][] = $dd;
                        }
                    }
                    if (isset($sessionData[$cCode]['main_inputs']) && sizeof($sessionData[$cCode]['main_inputs']) > 0) {
                        foreach ($sessionData[$cCode]['main_inputs'] as $key => $val) {
                            $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                            $inserMainValues[] = $dd;
                            $mongoListConnect['mainValues'][] = $dd;
                        }
                    }
                    if (isset($sessionData[$cCode]['main_elements']) && sizeof($sessionData[$cCode]['main_elements']) > 0) {
                        //                    cekMerah("ada mainElements");
                        foreach ($sessionData[$cCode]['main_elements'] as $elName => $aSpec) {
                            $tr->writeMainElements($insertID, array(
                                "mdl_name" => isset($aSpec['mdl_name']) ? $aSpec['mdl_name'] : "",
                                "key" => isset($aSpec['key']) ? $aSpec['key'] : 0,
                                "value" => isset($aSpec['value']) ? $aSpec['value'] : "",
                                "name" => $aSpec['name'],
                                "label" => $aSpec['label'],
                                "contents" => isset($aSpec['contents']) ? $aSpec['contents'] : "",
                                "contents_intext" => isset($aSpec['contents_intext']) ? $aSpec['contents_intext'] : "",

                            ));
                        }
                    }
                    if (isset($sessionData[$cCode]['tableIn_detail']) && sizeof($sessionData[$cCode]['tableIn_detail']) > 0) {
                        $insertIDs = array();
                        $insertDeIDs = array();
                        foreach ($sessionData[$cCode]['tableIn_detail'] as $dSpec) {
                            $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                            if ($insertDetailID < 1) {
                                die("Gagal saat berusaha write transaction detail entry pada " . __FILE__ . " baris " . __LINE__);
                            }
                            else {
                                $insertIDs[] = $insertDetailID;
                                $insertDeIDs[$insertID][] = $insertDetailID;
                                $mongoListConnect['detail'][] = $insertDetailID;
                            }
                            if ($epID != 999) {
                                $insertEpID = $tr->writeDetailEntries($epID, $dSpec);
                                if ($insertEpID < 1) {
                                    die("Gagal saat berusaha write transaction detail entry point pada " . __FILE__ . " baris " . __LINE__);
                                }
                                else {
                                    $insertIDs[] = $insertEpID;
                                    $insertDeIDs[$epID][] = $insertEpID;
                                    $mongoListConnect['detail'][] = $insertEpID;
                                }
                            }
                        }
                        if (sizeof($insertIDs) == 0) {
                            die(lgShowAlert("Transaksi gagal disimpan karena rincian transaksi kosong."));
                        }
                        else {
                            $indexing_details = array();
                            foreach ($insertDeIDs as $key => $numb) {
                                $indexing_details[$key] = $numb;
                            }

                            foreach ($indexing_details as $k => $arrID) {
                                $arrBlob = blobEncode($arrID);
                                $this->CI->db->query("UPDATE transaksi SET indexing_details = '$arrBlob' WHERE id=$k");
                                cekOrange($this->CI->db->last_query());
                            }
                        }
                    }
                    if (isset($sessionData[$cCode]['tableIn_detail2_sum']) && sizeof($sessionData[$cCode]['tableIn_detail2_sum']) > 0) {
                        $insertIDs = array();
                        foreach ($sessionData[$cCode]['tableIn_detail2_sum'] as $dSpec) {
                            $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                            $mongoListConnect['detail'] = $insertIDs;
                            if ($epID != 999) {
                                $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);
                                $mongoListConnect['detail'] = $mongoListConnect['detail'] = $insertIDs;;
                            }
                        }
                    }
                    if (isset($sessionData[$cCode]['tableIn_detail_values']) && sizeof($sessionData[$cCode]['tableIn_detail_values']) > 0) {
                        $insertIDs = array();
                        foreach ($sessionData[$cCode]['tableIn_detail_values'] as $pID => $dSpec) {
                            if (isset($this->configCore[$this->jenisTr]['tableIn']['detailValues'])) {
                                foreach ($this->configCore[$this->jenisTr]['tableIn']['detailValues'] as $key => $src) {
                                    //                                $insertIDs[$pID][] = $tr->writeDetailValues($insertID, array(
                                    //                                    "produk_jenis" => $sessionData[$cCode]['tableIn_detail'][$pID]['produk_jenis'],
                                    //                                    "produk_id" => $pID,
                                    //                                    "key" => $key,
                                    //                                    "value" => isset($dSpec[$src]) ? $dSpec[$src] : 0,
                                    //                                ));
                                    $dd = $tr->writeDetailValues($insertID, array(
                                        "produk_jenis" => $sessionData[$cCode]['tableIn_detail'][$pID]['produk_jenis'],
                                        "produk_id" => $pID,
                                        "key" => $key,
                                        "value" => isset($dSpec[$src]) ? $dSpec[$src] : 0,
                                    ));
                                    $insertIDs[] = $dd;
                                    $mongoListConnect['detailValues'][] = $dd;

                                }
                            }
                        }
                        if (sizeof($insertIDs) > 0) {
                            $arrBlob = blobEncode($insertIDs);
                            $this->CI->db->query("UPDATE transaksi SET indexing_detail_values = '$arrBlob' WHERE id=$insertID");
                        }
                    }
                    if (isset($sessionData[$cCode]['tableIn_detail_values2_sum']) && sizeof($sessionData[$cCode]['tableIn_detail_values2_sum']) > 0) {
                        foreach ($sessionData[$cCode]['tableIn_detail_values2_sum'] as $pID => $dSpec) {
                            if (isset($this->configCore[$this->jenisTr]['tableIn']['detailValues2_sum'])) {
                                foreach ($this->configCore[$this->jenisTr]['tableIn']['detailValues2_sum'] as $key => $src) {
                                    $insertIDs[] = $tr->writeDetailValues($insertID, array(
                                        "produk_jenis" => $sessionData[$cCode]['tableIn_detail2_sum'][$pID]['produk_jenis'],
                                        "produk_id" => $pID,
                                        "key" => $key,
                                        "value" => $dSpec[$src],
                                    ));

                                }
                            }
                        }
                    }

                    //
                    //region nulis paymentSource
                    //                $stepCode = $configUiMasterModulJenis['steps'][1]['target'];
                    $stepCode = $configUiMasterModulJenis['steps'][1]['target'];
                    $paymentSources = $this->CI->config->item("payment_source");
                    if (array_key_exists($stepCode, $paymentSources)) {

                        $payConfigs = $paymentSources[$stepCode];
                        if (sizeof($payConfigs) > 0) {
                            foreach ($payConfigs as $paymentSrcConfig) {
                                //					$paymentSrcConfig = $paymentSources[$stepCode];
                                $valueSrc = $paymentSrcConfig['valueSrc'];
                                $externSrc = $paymentSrcConfig['externSrc'];
                                $tr->writePaymentSrc($insertID, array(
                                    "jenis" => $stepCode,
                                    "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                    "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                                    "extern_id" => $sessionData[$cCode]['main'][$externSrc['id']],
                                    "extern_nama" => $sessionData[$cCode]['main'][$externSrc['nama']],
                                    "nomer" => $tmpNomorNota2,
                                    "label" => $paymentSrcConfig['label'],
                                    "tagihan" => $sessionData[$cCode]['main'][$valueSrc],
                                    "terbayar" => 0,
                                    "sisa" => $sessionData[$cCode]['main'][$valueSrc],
                                    "cabang_id" => $sessionData[$cCode]['main']['placeID'],
                                    "cabang_nama" => $sessionData[$cCode]['main']['placeName'],
                                    "oleh_id" => $sessionData[$cCode]["main"]["oleh_id"],
                                    "oleh_nama" => $sessionData[$cCode]["main"]["oleh_nama"],
                                    "dtime" => date("Y-m-d H:i:s"),
                                    "fulldate" => date("Y-m-d"),
                                    "valas_id" => isset($sessionData[$cCode]['main'][$externSrc['valasId']]) ? $sessionData[$cCode]['main'][$externSrc['valasId']] : '',
                                    "valas_nama" => isset($sessionData[$cCode]['main'][$externSrc['valasLabel']]) ? $sessionData[$cCode]['main'][$externSrc['valasLabel']] : '',
                                    "valas_nilai" => isset($sessionData[$cCode]['main'][$externSrc['valasValue']]) ? $sessionData[$cCode]['main'][$externSrc['valasValue']] : '',
                                    "tagihan_valas" => isset($sessionData[$cCode]['main'][$externSrc['valasTagihan']]) ? $sessionData[$cCode]['main'][$externSrc['valasTagihan']] : '',
                                    "terbayar_valas" => 0,
                                    "sisa_valas" => isset($sessionData[$cCode]['main'][$externSrc['valasSisa']]) ? $sessionData[$cCode]['main'][$externSrc['valasSisa']] : '',
                                ));
                            }
                        }


                        //cekMerah($this->CI->db->last_query());

                    }
                    else {
                        //cekMerah("TIDAK nulis paymentSrc");
                    }
                    //endregion


                    //region nulis paymentAntiSource
                    //                $stepCode = $configUiMasterModulJenis['steps'][1]['target'];
                    $stepCode = $configUiMasterModulJenis['steps'][1]['target'];
                    $paymentSources = $this->CI->config->item("payment_antiSource");
                    if (array_key_exists($stepCode, $paymentSources)) {
                        $payConfigs = $paymentSources[$stepCode];
                        if (sizeof($payConfigs) > 0) {
                            foreach ($payConfigs as $paymentSrcConfig) {
                                //					$paymentSrcConfig = $paymentSources[$stepCode];
                                $valueSrc = $paymentSrcConfig['valueSrc'];
                                $externSrc = $paymentSrcConfig['externSrc'];
                                $tr->writePaymentAntiSrc($insertID, array(
                                    "jenis" => $stepCode,
                                    "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                    "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                                    "extern_id" => $sessionData[$cCode]['main'][$externSrc['id']],
                                    "extern_nama" => $sessionData[$cCode]['main'][$externSrc['nama']],
                                    "nomer" => $tmpNomorNota2,
                                    "label" => $paymentSrcConfig['label'],
                                    "tagihan" => $sessionData[$cCode]['main'][$valueSrc],
                                    "terbayar" => 0,
                                    "sisa" => $sessionData[$cCode]['main'][$valueSrc],
                                    "cabang_id" => $sessionData[$cCode]['main']['placeID'],
                                    "cabang_nama" => $sessionData[$cCode]['main']['placeName'],
                                    "oleh_id" => $sessionData[$cCode]["main"]["oleh_id"],
                                    "oleh_nama" => $sessionData[$cCode]["main"]["oleh_nama"],
                                    "dtime" => date("Y-m-d H:i:s"),
                                    "fulldate" => date("Y-m-d"),
                                ));
                            }
                        }


                        //cekMerah($this->CI->db->last_query());

                    }
                    else {
                        //cekMerah("TIDAK nulis paymentSrc");
                    }
                    //endregion


                    $idHis_decode[$stepNum] = array(
                        "olehID" => $sessionData[$cCode]['main']['olehID'],
                        "olehName" => $sessionData[$cCode]['main']['olehName'],
                        "step" => $stepNum,
                        "trID" => $insertID,
                        "nomer" => $tmpNomorNota2,
                        "nomer2" => $tmpNomorNota2Alias,
                        "counters" => $appliedCounters2,
                        "counters_intext" => $appliedCounters_inText2,
                        "dtime" => date("Y-m-d H:i:s"),
                        "fulldate" => date("Y-m-d"),
                    );
                    $idHis_blob = blobEncode($idHis_decode);
                    $idHis_intext = print_r($idHis_decode, true);

                    $sessionData[$cCode]['tableIn_master']['ids_his'] = $idHis_blob;
                    $sessionData[$cCode]['tableIn_master']['ids_his_intext'] = $idHis_intext;

                    $tr = new MdlPenjualanTransaksi();
                    $dupState = $tr->updateData(array("id" => $insertID), array(
                        "id_master" => $masterID,
                        "id_top" => $insertID,

                        "ids_his" => $idHis_blob,
                        "ids_his_intext" => $idHis_intext,

                    )) or die("Failed to update tr next-state!");

                    //pengganti registry ditulis ke tabel fisik
                    if (isset($sessionData[$cCode]['tableIn_items']) && sizeof($sessionData[$cCode]['tableIn_items']) > 0) {
                        $insertIDs = array();
                        foreach ($sessionData[$cCode]['tableIn_items'] as $dSpec) {
                            arrPrint($dSpec);
                            $insertIDs[] = $tr->writeDetailItemsEntries($insertID, $dSpec);
                            cekBiru($this->CI->db->last_query());
                        }
//                matiHere();
                    }
                    if (isset($sessionData[$cCode]['tableIn_items2']) && sizeof($sessionData[$cCode]['tableIn_items2']) > 0) {
                        $insertIDs = array();
                        foreach ($sessionData[$cCode]['tableIn_items2'] as $dSpec) {
                            $insertIDs[] = $tr->writeEntriesDetailItems2($insertID, $dSpec);
                            $mongoList['detail'] = $insertIDs;
//                    if ($epID != 999) {
//                        $insertIDs[] = $tr->writeEntriesDetailItems2($epID, $dSpec);
//                        $mongoList['detail'] = $insertIDs;
//                    }
                            cekUngu($this->CI->db->last_query());
                        }
                    }
                    if (isset($sessionData[$cCode]['tableIn_items2_sum']) && sizeof($sessionData[$cCode]['tableIn_items2_sum']) > 0) {
                        $insertIDs = array();
                        foreach ($sessionData[$cCode]['tableIn_items2_sum'] as $dSpec) {
                            $insertDetailID = $tr->writeEntriesDetailItems2_sum($insertID, $dSpec);
                            $insertIDs[] = $insertDetailID;
                            $mongoList['detail'][] = $insertDetailID;
//                    if ($epID != 999) {
//                        $dd = $tr->writeEntriesDetailItems2_sum($epID, $dSpec);
//                        $insertIDs[] = $dd;
//                        $mongoList['detail'][] = $dd;
//                    }
                        }
                    }

                    if (isset($sessionData[$cCode]['tableIn_items3']) && sizeof($sessionData[$cCode]['tableIn_items3']) > 0) {
                        $insertIDs = array();
                        foreach ($sessionData[$cCode]['tableIn_items3'] as $dSpec) {
                            $insertIDs[] = $tr->writeEntriesDetailItems3($insertID, $dSpec);
                        }
                    }
                    if (isset($sessionData[$cCode]['tableIn_items3_sum']) && sizeof($sessionData[$cCode]['tableIn_items3_sum']) > 0) {
                        $insertIDs = array();
                        foreach ($sessionData[$cCode]['tableIn_items3_sum'] as $dSpec) {
                            $insertDetailID = $tr->writeEntriesDetailItems3_sum($insertID, $dSpec);
                            cekMErah($this->CI->db->last_query());
                        }
                    }
                    if (isset($sessionData[$cCode]['tableIn_items4']) && sizeof($sessionData[$cCode]['tableIn_items4']) > 0) {
                        $insertIDs = array();
                        foreach ($sessionData[$cCode]['tableIn_items4'] as $dSpec) {
                            $insertIDs[] = $tr->writeEntriesDetailItems4($insertID, $dSpec);
                        }
                    }
                    if (isset($sessionData[$cCode]['tableIn_items4_sum']) && sizeof($sessionData[$cCode]['tableIn_items4_sum']) > 0) {
                        $insertIDs = array();
                        foreach ($sessionData[$cCode]['tableIn_items4_sum'] as $dSpec) {
                            $insertDetailID = $tr->writeEntriesDetailItems4_sum($insertID, $dSpec);
                        }
                    }
                    if (isset($sessionData[$cCode]['tableIn_items5']) && sizeof($sessionData[$cCode]['tableIn_items5']) > 0) {
                        $insertIDs = array();
                        foreach ($sessionData[$cCode]['tableIn_items5'] as $dSpec) {
                            $insertIDs[] = $tr->writeEntriesDetailItems5($insertID, $dSpec);
                        }
                    }
                    if (isset($sessionData[$cCode]['tableIn_items5_sum']) && sizeof($sessionData[$cCode]['tableIn_items5_sum']) > 0) {
                        $insertIDs = array();
                        foreach ($sessionData[$cCode]['tableIn_items5_sum'] as $dSpec) {
                            $insertDetailID = $tr->writeEntriesDetailItems5_sum($insertID, $dSpec);
                        }
                    }
                    if (isset($sessionData[$cCode]['tableIn_items6']) && sizeof($sessionData[$cCode]['tableIn_items6']) > 0) {
                        $insertIDs = array();
                        foreach ($sessionData[$cCode]['tableIn_items6'] as $dSpec) {
                            $insertIDs[] = $tr->writeEntriesDetailItems6($insertID, $dSpec);
                        }
                    }
                    if (isset($sessionData[$cCode]['tableIn_items6_sum']) && sizeof($sessionData[$cCode]['tableIn_items6_sum']) > 0) {
                        $insertIDs = array();
                        foreach ($sessionData[$cCode]['tableIn_items6_sum'] as $dSpec) {
                            $insertDetailID = $tr->writeEntriesDetailItems6_sum($insertID, $dSpec);
                        }
                    }
                    if (isset($sessionData[$cCode]['tableIn_items7']) && sizeof($sessionData[$cCode]['tableIn_items7']) > 0) {
                        $insertIDs = array();
                        foreach ($sessionData[$cCode]['tableIn_items7'] as $dSpec) {
                            $insertIDs[] = $tr->writeEntriesDetailItems7($insertID, $dSpec);
                        }
                    }
                    if (isset($sessionData[$cCode]['tableIn_items7_sum']) && sizeof($sessionData[$cCode]['tableIn_items7_sum']) > 0) {
                        $insertIDs = array();
                        foreach ($sessionData[$cCode]['tableIn_items7_sum'] as $dSpec) {
                            $insertDetailID = $tr->writeEntriesDetailItems7_sum($insertID, $dSpec);
                        }
                    }
                    if (isset($sessionData[$cCode]['tableIn_items8']) && sizeof($sessionData[$cCode]['tableIn_items8']) > 0) {
                        $insertIDs = array();
                        foreach ($sessionData[$cCode]['tableIn_items8'] as $dSpec) {
                            $insertIDs[] = $tr->writeEntriesDetailItems8($insertID, $dSpec);
                        }
                    }
                    if (isset($sessionData[$cCode]['tableIn_items8_sum']) && sizeof($sessionData[$cCode]['tableIn_items8_sum']) > 0) {
                        $insertIDs = array();
                        foreach ($sessionData[$cCode]['tableIn_items8_sum'] as $dSpec) {
                            $insertDetailID = $tr->writeEntriesDetailItems8_sum($insertID, $dSpec);
                        }
                    }
                    if (isset($sessionData[$cCode]['tableIn_items9_sum']) && sizeof($sessionData[$cCode]['tableIn_items9_sum']) > 0) {
                        $insertIDs = array();
                        foreach ($sessionData[$cCode]['tableIn_items9_sum'] as $dSpec) {
                            $insertDetailID = $tr->writeEntriesDetailItems9_sum($insertID, $dSpec);
                            cekMErah($this->CI->db->last_query());
                        }
                    }
                    if (isset($sessionData[$cCode]['tableIn_items10_sum']) && sizeof($sessionData[$cCode]['tableIn_items10_sum']) > 0) {
                        $insertIDs = array();
                        foreach ($sessionData[$cCode]['tableIn_items10_sum'] as $dSpec) {
                            $insertDetailID = $tr->writeEntriesDetailItems10_sum($insertID, $dSpec);
                        }
                    }


                    $baseRegistries = array(
                        "items_komposisi" => isset($sessionData[$cCode]['items_komposisi']) ? $sessionData[$cCode]['items_komposisi'] : array(),
                        "componentsBuilder" => isset($sessionData[$cCode]['componentsBuilder']) ? $sessionData[$cCode]['componentsBuilder'] : array(),
                        "jurnalItems" => isset($sessionData[$cCode]['jurnalItems']) ? $sessionData[$cCode]['jurnalItems'] : array(),
                        "jurnal_index" => isset($sessionData[$cCode]['jurnal_index']) ? $sessionData[$cCode]['jurnal_index'] : array(),
                        "postProcessor" => isset($sessionData[$cCode]['postProcessor']) ? $sessionData[$cCode]['postProcessor'] : array(),
                        "preProcessor" => isset($sessionData[$cCode]['preProcessor']) ? $sessionData[$cCode]['preProcessor'] : array(),
                        "revert" => isset($sessionData[$cCode]['revert']) ? $sessionData[$cCode]['revert'] : array(),
                    );
                    $doWriteReg = $tr->writeDataRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));
                    $mongRegIDConnect = $doWriteReg;
                    //endregion


                    //==================================================================================================
                    //==MENULIS LOCKER TRANSAKSI ACTIVE=================================================================
                    $this->CI->load->model("Mdls/MdlLockerTransaksi");
                    $lt = New MdlLockerTransaksi();
                    $lt->execLocker($sessionData[$cCode]['main'], $nextProp['num'], NULL, $insertID);
                    //==================================================================================================
                }
                else {
                    cekMerah("to be delayed to connect to $connector");
                }
            }
            else {
                //cekKuning("not connecting to any tCode");
            }
            //endregion


            $returnTransaksi = array(
                "transaksi_id" => $insertTransaksiID,
                "transaksi_nomer" => $tmpNomorNota2_current,
                "transaksi_id_connecting" => $insertConnectingID,
                "transaksi_nomer_connecting" => $tmpNomorNotaConnecting,
            );
            return $returnTransaksi;
        }
        else {
            $masterID = 0;
            $tmpNomorNota = "XXXX";
            $origJenis = 0;
            $topID = 0;
            mati_disini(("No such receipt ID: $no, pada step: $stepNumCurrent, // code: " . __LINE__));
        }


    }

    public function followupPrePreviewAuto($jenisTr, $no, $stepNum, $stepNumCurrent)
    {

        $no = rtrim($no, "-");
        $stepNumber = $stepNum;
        $currentStepNum = $stepNumCurrent;
        $url = str_replace("index.php/", "", current_url());
        $rawBuilderURL = blobEncode($url);
        $modePengirim = isset($_GET["pengirim"]) ? $_GET["pengirim"] : "";


        //region read items from existing model
        $this->CI->load->model("MdlPenjualanTransaksi");
        $tr = new MdlPenjualanTransaksi();
        $tr->setFilters(array());
        $tr->addFilter($tr->getTableName() . ".id in (" . implode(",", explode("-", $no)) . ")");
        $tmpTr = $tr->lookupJoined();
        cekBiru($this->CI->db->last_query());
        //endregion


        $cancelPackingId = isset($tmpTr[0]->cancel_packing_source_id) ? $tmpTr[0]->cancel_packing_source_id : 0;
        $tmpTrCancelPacking = array();
        $id_top_source_cancel_packing = array();
        if ($cancelPackingId > 0) {
            $tr->setFilters(array());
            $tr->addFilter("id in (" . implode(",", explode("-", $cancelPackingId)) . ")");
            $tmpTrCancelPacking = $tr->lookupJoined();
            $id_top_source_cancel_packing = $tmpTrCancelPacking[0]->id_top;
        }


        $signNumbers = array();
        $trs = new MdlPenjualanTransaksi();
        $trs->setFilters(array());
        $tmpSign = $trs->lookupSignaturesByMasterID($no)->result();
        if (sizeof($tmpSign) > 0) {
            $sCtr = 0;
            foreach ($tmpSign as $row) {
                $signNumbers[$sCtr] = "" . $row->step_number;
                $sCtr++;
            }
        }


        $rawItems = array();
        if (sizeof($tmpTr) > 0) {
            $this->jenisTr = $tmpTr[0]->jenis_master;
            $cCode = "_TR_" . $this->jenisTr;
            if (isset($sessionData[$cCode])) {
                $sessionData[$cCode] = null;
                unset($sessionData[$cCode]);
            }

            //region session init
            if (!isset($sessionData[$cCode])) {
                $sessionData[$cCode] = array(
                    "items" => array(),
                    "main" => array(),
                );
            }
            if (!isset($sessionData[$cCode]['main'])) {
                $sessionData[$cCode]['main'] = array();
            }
            if (!isset($sessionData[$cCode]['items'])) {
                $sessionData[$cCode]['items'] = array();
            }
            //endregion

            $trID = $tmpTr[0]->transaksi_id;
            $itemLabels = isset($this->configLayout[$this->jenisTr]['receiptDetailFields'][$stepNumber]) ? $this->configLayout[$this->jenisTr]['receiptDetailFields'][$stepNumber] : array();
            $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][$stepNumber] : array();
            $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][$stepNumber] : null;
            //            $masterID = isset($tmpTr[0]->referensi_id) && $tmpTr[0]->referensi_id > 0 ? $tmpTr[0]->referensi_id : $tmpTr[0]->transaksi_id;
            $measurementDetails = isset($this->configUi[$this->jenisTr]["receiptMesurementRows"]) ? $this->configUi[$this->jenisTr]["receiptMesurementRows"] : array();
            $validatePaymentLocker = isset($this->configUi[$this->jenisTr]["validatePaymentSource"][$stepNumber]) ? $this->configUi[$this->jenisTr]["validatePaymentSource"][$stepNumber] : array();
            $itemsChild = isset($this->configUi[$this->jenisTr]["shopingCartDetailFields"][$stepNumber]['fields']) ? $this->configUi[$this->jenisTr]["shopingCartDetailFields"][$stepNumber]['fields'] : array();//dipake detil pembelian aset
            $itemsChildGate = isset($this->configUi[$this->jenisTr]["shopingCartDetailFields"][$stepNumber]['gate']) ? $this->configUi[$this->jenisTr]["shopingCartDetailFields"][$stepNumber]['gate'] : array();//dipake detil pembelian aset/penambahan aset dari supplies sebagai switcer baca item atau main


            $masterID = $tmpTr[0]->id_master;
            $topID = $tmpTr[0]->id_top;
            $tmpNomorNota = $tmpTr[0]->nomer;
            $origJenis = $tmpTr[0]->jenis_master;
            $currentStepNum = $tmpTr[0]->step_number;
            $afterTargetStepNum = ($currentStepNum + 1);
            $pengirimID = $tmpTr[0]->pengirim_id;
            $pengirimName = $tmpTr[0]->pengirim_nama;
            $jenisCurrentTransaksi = $tmpTr[0]->jenis;
            //--------------------------------
            $id_top = isset($tmpTr[0]->id_top) ? $tmpTr[0]->id_top : "";
            $gudangStatusJenis = $tmpTr[0]->gudang_status_jenis;
            $idsHis = ($tmpTr[0]->ids_his != null) ? blobDecode($tmpTr[0]->ids_his) : array();

            //region periksa locker value;
            $tempLocker = array();
            $tempBtnUndo = array();
            if (sizeof($validatePaymentLocker) > 0) {
                $mdlName = "Mdls/" . $validatePaymentLocker;
                $this->CI->load->model($mdlName);
                $l = new $validatePaymentLocker();
                $l->addFilter("transaksi_id='$no'");
                $l->addFilter("state='active'");
                $l->addFilter("nilai > 0");
                $tempLocker = $l->lookupAll()->result();
                //cekUngu($this->CI->db->last_query() . " >> $validatePaymentLocker");
                //arrPrint($tempLocker);
                if (sizeof($tempLocker) > 0) {
                    $tempBtnUndo = array(
                        "allowedUndone" => false,//tidak boleh di undo/reject
                        "allowedFollow" => false,//boleh di followup
                    );
                }
                else {
                    $jnTarget = isset($this->CI->config->item('payment_source')[$this->jenisTr][$currentStepNum][0]['jenisTarget']) ? $this->CI->config->item('payment_source')[$this->jenisTr][$currentStepNum][0]['jenisTarget'] : "";
                    $tempBtnUndo = array(
                        "allowedUndone" => true,// boleh di undo/reject
                        "allowedFollow" => true,//tidak boleh di followup
                        "label" => isset($this->configUi[$jnTarget]['label']) ? $this->configUi[$jnTarget]['label'] : "",
                    );
                }
            }

            //endregion

            $allowEdit = isset($this->configUi[$this->jenisTr]['steps'][$stepNumber]['allowEdit']) ? $this->configUi[$this->jenisTr]['steps'][$stepNumber]['allowEdit'] : false;
            $allowCancel = isset($this->configUi[$this->jenisTr]['steps'][$stepNumber]['allowCancel']) ? $this->configUi[$this->jenisTr]['steps'][$stepNumber]['allowCancel'] : false;
            $editableFields = isset($this->configUi[$this->jenisTr]['shoppingCartEditableFields'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartEditableFields'][$stepNumber] : array();


            //region valid items
            $extractedItems = array();//==untuk urusan update transaksi referer
            $validItems = array();
            $validItemSends = array();
            $validItemReqCancels = array();
            $validItemCancels = array();
            $validItemPreCancels = array();
            $validItemSents = array();
            $main = array();
//            $items = array();
            if (sizeof($tmpTr) > 0) {
                cekmerah("ada yang mau diekstrak");
                foreach ($tmpTr as $row) {
                    //----
                    $main = (array)$row;
//                    $items[$row->produk_id] = (array)$row;
                    //----
                    if (!isset($validItems[$row->produk_id])) {
                        $validItems[$row->produk_id] = 0;
                    }
                    if (!isset($validItemSends[$row->produk_id])) {
                        $validItemSends[$row->produk_id] = 0;
                    }
                    if (!isset($validItemCancels[$row->produk_id])) {
                        $validItemCancels[$row->produk_id] = 0;
                    }
                    if (!isset($validItemReqCancels[$row->produk_id])) {
                        $validItemReqCancels[$row->produk_id] = 0;
                    }
                    if (!isset($validItemPackeds[$row->produk_id])) {
                        $validItemPackeds[$row->produk_id] = 0;
                    }
                    if (!isset($validItemPreCancels[$row->produk_id])) {
                        $validItemPreCancels[$row->produk_id] = 0;
                    }
                    $validItems[$row->produk_id] += isset($row->valid_qty) ? $row->valid_qty : 0;
                    $validItemSends[$row->produk_id] += isset($arrTmp__['582spd'][$row->produk_id]) ? $arrTmp__['582spd'][$row->produk_id] : 0;
                    $validItemCancels[$row->produk_id] += isset($row->cancel_qty) ? $row->cancel_qty : 0;
                    $validItemReqCancels[$row->produk_id] += isset($row->req_cancel_qty) ? $row->req_cancel_qty : 0;
                    $validItemPreCancels[$row->produk_id] += isset($arrPreTmp__['1982'][$row->produk_id]) ? $arrPreTmp__['1982'][$row->produk_id] : 0;
                    $validItemPackeds[$row->produk_id] += isset($arrTmp__['582pkd'][$row->produk_id]) ? $arrTmp__['582pkd'][$row->produk_id] : 0;
                    if (!isset($extractedItems[$row->produk_id])) {
                        $extractedItems[$row->produk_id] = array();
                    }
                    $extractedItems[$row->produk_id][$row->id_detail] = array(
//                        "id" => $row->id,
                        "id" => $row->produk_id,
                        "produk_id" => $row->produk_id,
                        "qty" => $row->produk_ord_jml,
//                        "valid_qty" => $row->valid_qty,
                        "valid_qty" => $row->qty_kredit,
                        "transaksi_id" => $row->transaksi_id,
                        "packed_qty" => isset($arrTmp__['582pkd'][$row->produk_id]) ? $arrTmp__['582pkd'][$row->produk_id] : 0,
                        "sent_qty" => isset($arrTmp__['582spd'][$row->produk_id]) ? $arrTmp__['582spd'][$row->produk_id] : 0,
                        "req_cancel_qty" => isset($arrPreTmp__['1982'][$row->produk_id]) ? $arrPreTmp__['1982'][$row->produk_id] : 0,
                        "cancel_qty" => isset($row->cancel_qty) ? $row->cancel_qty : 0,
//                        "outstanding" => $row->produk_ord_jml - ($row->produk_ord_jml - $row->valid_qty),
                        "outstanding" => $row->produk_ord_jml - ($row->produk_ord_jml - $row->qty_kredit),
                    );
                    //-------
                    if (sizeof($tableInMaster) > 0) {
                        foreach ($tableInMaster as $mKey => $mVal) {
//                            if ($mVal != NULL) {
                            $main[$mVal] = isset($row->$mKey) ? $row->$mKey : "";
//                            }
                        }
                    }
                    //-------
                    if (sizeof($tableInDetail) > 0) {
                        foreach ($tableInDetail as $mKey => $mVal) {
                            if ($mVal != NULL) {
//                                $items[$row->produk_id][$mVal] = isset($row->$mKey) ? $row->$mKey : "";
                            }
                        }
                    }
                    //-------
                }
            }
            else {
                cekmerah("TIDAK ada yang mau diekstrak");
            }
            cekBiru($validItems);
            //endregion

            //region take from registries
            $trr = new MdlPenjualanTransaksi();
            $trr->setFilters(array());
            $trrTmp = $trr->lookupMainElementsByTransID($no)->result();
            if (sizeof($trrTmp) > 0) {
                foreach ($trrTmp as $trrSpec) {
                    $mainElements[$trrSpec->name] = (array)$trrSpec;
                }
            }
            //lookup pengganti registry ke tabel penjuelan_transkai_data_xxx
            $trr->setFilters(array());
            $tempReg = $trr->lookUpAllChild($no);
            //            matiHere();
            $main = array();
            $items = array();
            $items2 = array();
            $items2_sum = array();
            $items3 = array();
            $items3_sum = array();
            $items4 = array();
            $items4_sum = array();
            $items6 = array();
            $items6_sum = array();
            $items7 = array();
            $items7_sum = array();
            $items8_sum = array();
            $items9_sum = array();
            $items10_sum = array();
            $rsltItems = array();
            $rsltItems2 = array();

            $masterGates = array();
            $childGates = array();
            $childGates2 = array();
            $childGates2_sum = array();
            $childGatesRsltItems = array();
            $childGatesRsltItems2 = array();
            $masterTableInParams = array();
            $childTableInParams = array();
            $childTableInParamsRsltItems = array();
            $childTableInParamsRsltItems2 = array();
            $masterTableInValueParams = array();
            $childTableInValueParams = array();
            $childTableInValueParamsRsltItems = array();
            $childTableInValueParamsRsltItems2 = array();
            $masterAddValues = array();
            $masterAddFields = array();
            $mainElements = array();
            $mainInputs = array();
            $itemsKomposisi = array();
            if (sizeof($tmpReg) > 0) {
                foreach ($tempReg as $reg => $valuePair) {
                    switch ($reg) {
                        case "main"://
                            $main = $main + $valuePair;
                            break;
                        case "items"://
                            $items = $items + $valuePair;
                            break;
                        case "items2"://
                            $items2 = $items2 + $valuePair;
                            break;
                        case "rsltItems"://
                            $rsltItems = $rsltItems + $valuePair;
                            break;
                        case "rsltItems2"://
                            $rsltItems2 = $rsltItems2 + $valuePair;
                            break;
                        case "items2_sum"://
                            $items2_sum = $items2_sum + $valuePair;
                            break;
                        case "items3"://
                            $items3 = $items3 + $valuePair;
                            break;
                        case "items3_sum"://
                            $items3_sum = $items3_sum + $valuePair;
                            break;
                        case "items4_sum"://
                            $items4_sum = $items4_sum + $valuePair;
                            break;
                        case "items5_sum"://
                            $items5_sum = $items5_sum + $valuePair;
                            break;
                        case "items6_sum"://
                            $items6_sum = $items6_sum + $valuePair;
                            break;
                        case "items7_sum"://
                            $items7_sum = $items7_sum + $valuePair;
                            break;
                        case "items8_sum"://
                            $items8_sum = $items8_sum + $valuePair;
                            break;
                        case "items9_sum"://
                            $items9_sum = $items9_sum + $valuePair;
                            break;
                        case "items10_sum"://
                            $items10_sum = $items10_sum + $valuePair;
                            break;
                        case "items_komposisi"://
                            $itemsKomposisi = $valuePair;
                            break;
                    }
                }

            }
            else {
                die("Cannot read the registry entries from $masterID!");
            }
            //endregion

            $masterReplacers = array(
                "jenisTrMaster" => $this->jenisTr,
                "jenisTrTop" => $masterTableInParams['jenis_top'],
                "harga" => 0,
                "masterID" => $masterID,
            );
            foreach ($masterReplacers as $key => $src) {
                $main[$key] = $src;
                $mainValues[$key] = $src;
                $masterGates[$key] = $src;
            }


            //==revalidate items
            $this->CI->load->library("FieldCalculator");
            $this->CI->load->helper("he_angka");
            $cal = new FieldCalculator();

            $itemChildData = array();
            if (sizeof($items) > 0) {

                foreach ($items as $xid => $iSpec) {
                    $id = $iSpec['id'];
                    $tipeSize = isset($iSpec['detilSize']) && sizeof($iSpec['detilSize']) > 0 ? $iSpec['detilSize'] : "";

                    if (array_key_exists($id, $validItems)) {
                        $items[$id]['jml'] = $validItems[$id];
                        //                        $items[$id]['jml'] = $validItems[$id]-(int)$validItemPreCancels[$id];
                        $items[$id]['max_jml'] = $validItems[$id];
                        //                        $items[$id]['max_jml'] = $validItems[$id]-(int)$validItemPreCancels[$id];
                        $items[$id]['packed_jml'] = $validItemPackeds[$id];
                        $items[$id]['sent_jml'] = $validItemSends[$id];
                        $items[$id]['cancel_jml'] = $validItemCancels[$id];
                        $items[$id]['req_cancel_jml'] = $validItemPreCancels[$id];
                        if (sizeof($editableFields) > 0) {
                            foreach ($editableFields as $fName) {
                                $items[$id]["max_$fName"] = isset($iSpec[$fName]) ? $iSpec[$fName] : 0;
                            }
                        }

                        if (sizeof($measurementDetails)) {
                            if (in_array($stepNumber, $measurementDetails["allowView"]) && isset($measurementDetails[$tipeSize])) {
                                $selectedColl = $measurementDetails[$tipeSize];
                                foreach ($selectedColl as $colSelected => $tempHelper) {
                                    foreach ($tempHelper as $newKey => $heAngka) {
                                        $items[$id][$newKey] = $heAngka($iSpec[$colSelected]);
                                    }
                                }
                            }
                        }


                        if ($subAmountConfig != null) {
                            $tmpEx = $cal->multiExplode($subAmountConfig);
                            if (sizeof($tmpEx) > 1) {
                                //                            echo lgShowAlert("menghitung subtotal pakai rumus $subAmountConfig di step ke # $stepNumber");
                                $newSrc = $subAmountConfig;
                                foreach ($tmpEx as $key2 => $val2) {
                                    if (isset($items[$id][$val2])) {
                                        $newSrc = str_replace($val2, $items[$id][$val2], $newSrc);

                                    }
                                    else {
                                        if (isset($tmp[$val2])) {
                                            $newSrc = str_replace($val2, $items[$val2], $newSrc);

                                        }
                                        else {
                                            $newSrc = str_replace($val2, "0", $newSrc);

                                        }
                                    }


                                }
                                $subtotal = $cal->calculate($newSrc);


                            }
                            else {
                                //                            echo lgShowAlert("memasang subtotal dari $subAmountConfig");
                                $subtotal = $items[$id][$subAmountConfig];

                            }
                        }
                        else {
                            //                        echo lgShowAlert("tidak mengapa-apakan subtotal");
                            $subtotal = 0;

                        }

                        $items[$id]['subtotal'] = $subtotal;
                        //region item child

                        if (sizeof($itemsChild) > 0 && ($itemsChildGate == 'detail')) {
                            //                        if (sizeof($itemsChild)  > 0 ) {
                            for ($x = 1; $x <= $validItems[$id]; $x++) {
                                foreach ($itemsChild as $col => $col_label) {
                                    $itemChildData[$id][$x][$col] = isset($items[$id][$col]) ? $items[$id][$col] : "";
                                    $itemChildData[$id][$x]["jml"] = 1;
                                    $itemChildData[$id][$x]["qty"] = 1;
                                    $itemChildData[$id][$x]["folders"] = $main['pihakMainID'];
                                }

                            }
                            //                            arrPrint($itemsChild);
                            //                        foreach ($itemsChild as )
                        }

                        //endregion
                        cekBiru($itemsKomposisi);
                        if (sizeof($itemsKomposisi) > 0) {
                            if (array_key_exists($id, $itemsKomposisi)) {
                                //                                cekBiru(":: ADA komposisinya yaitu $id ::");
                                foreach ($items2[$id] as $jenis_komposisi => $iiSpec) {
                                    foreach ($iiSpec as $ee => $eeSpec) {
                                        $komposisi = $itemsKomposisi[$id][$jenis_komposisi][$ee];
                                        // re-kalkulasi gerbang items2
                                        $items2[$id][$jenis_komposisi][$ee]['jml'] = $komposisi->jml * $validItems[$id];
                                        $items2[$id][$jenis_komposisi][$ee]['sub_nilai'] = $komposisi->nilai * $validItems[$id];
                                        cekhijau("pID: $id [], jml: " . $komposisi->jml . " validItems: " . $validItems[$id]);
                                    }
                                }
                            }
                        }


                    }
                    else {
                        unset($items[$id]);
                        unset($items2[$id]);
                        unset($childGates[$id]);
                        unset($childTableInParams[$id]);
                        unset($childTableInValueParams[$id]);

                    }
                }


                if (sizeof($itemsKomposisi) > 0) {
                    $items2_sum = array();// supplies-nya...
                    $items3_sum = array();// biaya-nya...
                    foreach ($items2 as $pID => $pSpec) {
                        foreach ($pSpec as $jenis => $jSpec) {
                            foreach ($jSpec as $eSpec) {
                                if ($jenis == "produk") {
                                    if (!isset($items2_sum[$eSpec['id']])) {
                                        $items2_sum[$eSpec['id']] = $eSpec;
                                        $items2_sum[$eSpec['id']]['jml'] = 0;
                                        $items2_sum[$eSpec['id']]['produk_ids'] = array();
                                    }
                                    $items2_sum[$eSpec['id']]['jml'] += $eSpec['jml'];
                                    $items2_sum[$eSpec['id']]['produk_ids'][$pID] = $pID;

                                    cekBiru("pID: " . $eSpec['id'] . " jml: " . $eSpec['jml']);
                                }
                                if ($jenis == "biaya") {
                                    if (!isset($items3_sum[$eSpec['id']])) {
                                        $items3_sum[$eSpec['id']] = $eSpec;
                                        $items3_sum[$eSpec['id']]['jml'] = 0;
                                        $items3_sum[$eSpec['id']]['sub_nilai'] = 0;
                                        $items3_sum[$eSpec['id']]['produk_ids'] = array();
                                    }
                                    $items3_sum[$eSpec['id']]['jml'] += $eSpec['jml'];
                                    $items3_sum[$eSpec['id']]['sub_nilai'] += $eSpec['sub_nilai'];
                                    $items3_sum[$eSpec['id']]['produk_ids'][$pID] = $pID;
                                }
                            }
                        }
                    }
                }

            }

            if (sizeof($itemsChild) > 0 && ($itemsChildGate == 'main')) {

                $fieldAlias = isset($this->configUi[$this->jenisTr]["shopingCartDetailFields"][$stepNumber]['fieldAlias']) ? $this->configUi[$this->jenisTr]["shopingCartDetailFields"][$stepNumber]['fieldAlias'] : $itemsChild;//dipake detil pembelian aset
                foreach ($fieldAlias as $col => $col_label) {
                    $itemChildData[$main['pihakMainRulesID']][1][$col] = isset($main[$col_label]) ? $main[$col_label] : "";
                }
                $itemChildData[$main['pihakMainRulesID']][1]["jml"] = 1;
                $itemChildData[$main['pihakMainRulesID']][1]["qty"] = 1;
                $itemChildData[$main['pihakMainRulesID']][1]["folders"] = $main['pihakID'];
                //                cekBiru("main");
            }

            //region session-swapper
            unset($main["nilai_pembulatan"]);
            $main["pengirimID"] = $pengirimID;
            $main["pengirimName"] = $pengirimName;
            $swappers = array(
                "main" => $main,
                "items" => $items,
                "items2" => $items2,
                "items2_sum" => $items2_sum,
                "items3" => $items3,
                "items3_sum" => $items3_sum,
                "items4" => $items4,
                "items4_sum" => $items4_sum,
                "items6" => $items6,
                "items6_sum" => $items6_sum,
                "items7" => $items7,
                "items7_sum" => $items7_sum,
                "items8_sum" => $items8_sum,
                "items9_sum" => $items9_sum,
                "items10_sum" => $items10_sum,
                "items_child" => $itemChildData,
                "rsltItems" => $rsltItems,
                "rsltItems2" => $rsltItems2,
                "extractedItems" => $extractedItems,


                "tableIn_master" => $masterTableInParams,
                "tableIn_detail" => $childTableInParams,
                "tableIn_detail_rsltItems" => $childTableInParamsRsltItems,
                "tableIn_detail_rsltItems2" => $childTableInParamsRsltItems2,
                "tableIn_master_values" => $masterTableInValueParams,
                "tableIn_detail_values" => $childTableInValueParams,
                "tableIn_detail_values_rsltItems" => $childTableInValueParamsRsltItems,
                "tableIn_detail_values_rsltItems2" => $childTableInValueParamsRsltItems2,
                "main_add_values" => $masterAddValues,
                //        ""=>$childAddValues ,
                "main_add_fields" => $masterAddFields,
                //                "main_applets"          => $mainApplets,
                "main_elements" => $mainElements,
                "main_inputs" => $mainInputs,
                //
                "extSteps" => $extSteps,
                "paySrcs" => $paySrcs,
                "lockerPayment" => $tempBtnUndo,
                "items_komposisi" => $itemsKomposisi,
            );
            foreach ($swappers as $targetVar => $src) {
                $sessionData[$cCode][$targetVar] = $src;

            }
            //endregion

            if (sizeof($idsHis) > 0) {
                foreach ($idsHis as $step_his => $data_his) {
                    if ($step_his == 2) {
                        $_SESSION[$cCode]['main']['referenceIDSO'] = $data_his["trID"];
                        $_SESSION[$cCode]['main']['referenceNumberSO'] = $data_his["nomer"];
                        $_SESSION[$cCode]['main']['referenceNumberSOCounters'] = blobDecode($data_his["counters"]);
                    }
                    $_SESSION[$cCode]['main']['referenceID__' . $step_his] = $data_his["trID"];
                    $_SESSION[$cCode]['main']['referenceNumber__' . $step_his] = $data_his["nomer"];
                    $_SESSION[$cCode]['main']['referenceNomer__' . $step_his] = $data_his["nomer"];
                    $_SESSION[$cCode]['main']['referenceDtime__' . $step_his] = $data_his["dtime"];
                    $_SESSION[$cCode]['main']['referenceFulldate__' . $step_his] = $data_his["fulldate"];
                }

            }

            $this->CI->load->helper("he_value_builder");

            //-------------------------------------------
            $receiptElementsInjector = isset($this->configUi[$this->jenisTr]["receiptElementsInjector"]) ? $this->configUi[$this->jenisTr]["receiptElementsInjector"] : array();
            if (sizeof($receiptElementsInjector) > 0) {
                foreach ($receiptElementsInjector as $eName => $eSpec) {

                    if ((!isset($main[$eName])) || (!isset($mainElements[$eName]))) {
                        //                        cekhitam("tidak kenal ppv, maka diinjeckkan...");
                        if (isset($eSpec['defaultValue'])) {//==cek apakah ada seting defaultValue
                            //                        cekmerah("default value for $eName is: " . $eSpec['defaultValue']);
                            $defValueSrc = $eSpec['defaultValue'];
                            switch ($eSpec['elementType']) {
                                case "dataModel":
                                    heFetchElement_modul($this->jenisTr, $eName, $eSpec['mdlName'], $defValueSrc, $this->configUiJenis);
                                    break;
                                case "dataField":
                                    heRecordElement_modul($this->jenisTr, $eName, $defValueSrc, $this->configUiJenis);
                                    break;
                            }
                            $sessionData[$cCode]['main_elements'][$eName]['autoSelect'] = true;
                        }
                        else {//==cek apakah pilihannya cuma satu
                            if (isset($eSpec['noPrefetch']) && $eSpec['noPrefetch'] == true) {

                            }
                            else {
                                //                            cekHere(__LINE__);
                                switch ($eSpec['elementType']) {
                                    case "dataModel":
                                        $amdlName = $eSpec['mdlName'];
                                        $this->CI->load->model("Mdls/" . $amdlName);
                                        $labelSrc = $eSpec['labelSrc'];
                                        $keySrc = $eSpec['key'];
                                        $oo = new $amdlName();
                                        $aFilter = isset($eSpec['mdlFilter']) ? $eSpec['mdlFilter'] : array();
                                        //                                    cekHitam($amdlName);
                                        //                                    arrPrint($aFilter);
                                        if (sizeof($aFilter) > 0) {
                                            $oo = makeFilter($aFilter, $sessionData[$cCode]['main'], $oo);
                                        }
                                        $tmpo = $oo->lookupAll()->result();
                                        if (sizeof($tmpo) == 1) {
                                            $usedKey = $eSpec['key'];
                                            $defValueSrc = $tmpo[0]->$usedKey;
                                            heFetchElement_modul($this->jenisTr, $eName, $eSpec['mdlName'], $defValueSrc, $this->configUiJenis);
                                        }
                                        break;
                                    case "dataField":
                                        break;
                                }
                            }
                        }

                        resetValues($this->jenisTr);
                        $sessionData[$cCode] = fillValues_he_value_builder_ns($this->jenisTr, $this->uri->segment(7), $this->uri->segment(6), $this->configCoreJenis, $this->configUiJenis, $this->configValuesJenis, $ppnFactor, $sessionData[$cCode]);

                    }
                }
            }

            //==init replacer
            //==recover nilai HARGA master
            $sessionData[$cCode]['main']['harga'] = 0;
            $sessionData[$cCode]['main']['currentID'] = $no;

            //==default load dari nota, maka dianggap langsung done
            $sessionData[$cCode]['main']['status_4'] = 1;
            $sessionData[$cCode]['main']['trash_4'] = 0;
            if (sizeof($sessionData[$cCode]['items']) > 0) {
                foreach ($sessionData[$cCode]['items'] as $xid => $iSpec) {
                    $id = $iSpec['id'];
                    $sessionData[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
                    /*---untuk keperluan mobile view---*/
                    $sessionData[$cCode]['items'][$xid]['jml_target_scan'] = $iSpec['jml'];
                }
            }

            //overwriter ppn facrot
//            if (isset($sessionData[$cCode]["main"]["ppnFactor"]) && $sessionData[$cCode]["main"]["ppnFactor"] == $this->session->login["ppnFactor"]) {
//
//            }
//            else {
//                $sessionData[$cCode]["main"]["ppnFactor"] = $this->session->login["ppnFactor"];//baca dari session login
//            }
            $ppnFactor = isset($sessionData[$cCode]["main"]["ppnFactor"]) && $sessionData[$cCode]["main"]["ppnFactor"] == 11 ? $sessionData[$cCode]["main"]["ppnFactor"] : matiHere("error on build values on PrePrev " . __LINE__ . " silahkan relogin");

            $transaksiID_exception = array(
                "125339",
                "125341",
                //-----------
                "113564",
                "113544",
                "113542",
                "113534",
                "113518",
                "112436",
                "112422",
                //-----------
                "127435",
                "127439",
                "127443",
                "127453",
                "127461",
                "127467",
                "127471",
                //-----------
            );
            $dtime_ex = explode(" ", $tmpTr[0]->dtime);
            $transaksi_date = $dtime_ex[0];

            /* ----------------------------------------------------------------------
             * deteksi mobile auto atau hanya orang tertentu,
             * diatur di heWeb mobile
             * ----------------------------------------------------------------------*/
            $isMob0 = isMobile_he_misc();
            $isMob = isset($_GET['ismob']) ? $_GET['ismob'] : $isMob0;
            cekHere("mob: $isMob");


            // region reload data produk sesuai config dari shoppingcart-----------------------------------
            $pakai_ini = 0;
            if ($pakai_ini == 1) {
                $arrItemsKey = array_keys($sessionData[$cCode]["items"]);
                $arrDataTambahan = array(
                    "outdoor" => array(
                        "outdoor_id" => "outdoor_nama",
                    ),
                    "indoor" => array(
                        "indoor_id_1" => "indoor_nama_1",
                        "indoor_id_2" => "indoor_nama_2",
                        "indoor_id_3" => "indoor_nama_3",
                        "indoor_id_4" => "indoor_nama_4",
                    ),
                    "heater" => array(
                        "heater_id" => "heater_nama",
                    ),
                );
                $selectorSrcModel = isset($sessionData[$cCode]['main']['pihakMdlNameSrc']) ? $sessionData[$cCode]['main']['pihakMdlNameSrc'] : $this->configUi[$this->jenisTr]['selectorSrcModel'];
                $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
                //arrPrintWebs($fieldSrcs);
                $this->CI->load->model("Mdls/" . $selectorSrcModel);
                $b = new $selectorSrcModel();
                $b->addFilter("id in ('" . implode("','", $arrItemsKey) . "')");
                $tmpB = $b->lookupAll()->result();
                //            showLast_query("ungu");
                //            cekHitam(sizeof($tmpB));
                if (sizeof($tmpB) > 0) {
                    foreach ($tmpB as $row) {
                        $rows = $row;
                        $tmp = (array)$row;
                        $produk_id = $idp = $row->id;
                        if (!isset($sessionData[$cCode]['items2'][$idp])) {
                            $sessionData[$cCode]['items2'][$idp] = array();
                        }
                        foreach ($fieldSrcs as $key => $src) {
                            //                        cekHitam("$key => $src");
                            if (is_array($src) && sizeof($src) > 0) {
                                //                            cekHitam("masuk disini " . __LINE__);
                                foreach ($src as $srcSpec) {
                                    if (isset($tmp[$srcSpec]) || isset($rows->$srcSpec)) {
                                        $sessionData[$cCode]['items'][$idp][$key] = makeValue($srcSpec, $tmp, $tmp, isset($rows->$srcSpec) ? $rows->$srcSpec : "-");
                                    }
                                }
                            }
                            else {
                                //                            cekUngu("masuk disini [$key => $src] " . __LINE__);
                                $sessionData[$cCode]['items'][$idp][$key] = makeValue($src, $tmp, $tmp, isset($rows->$src) ? $rows->$src : 0);
                            }
                        }
                        // memasukkan kolom sku ke items2
                        //                    $tmp = $sessionData[$cCode]['items'][$id];
                        //handle serial 1
                        $jml_serial = $rows->jml_serial;
                        $sessionData[$cCode]['items'][$produk_id]['jml_serial'] = $jml_serial;
                        if (($jml_serial * 1) == 1) {
                            $d_kode = $rows->kode;
                            $sessionData[$cCode]['items2'][$produk_id][$d_kode] = array();
                        }
                        $arrCat = array();
                        $arrCode = array();
                        foreach ($arrDataTambahan as $cat => $catSpec) {
                            foreach ($catSpec as $dkey => $dval) {
                                if (isset($rows->$dval) && ($rows->$dval != NULL)) {
                                    $sessionData[$cCode]['items2'][$produk_id][$rows->$dval] = array();
                                    //--------------
                                    if (!isset($arrCat[$cat])) {
                                        $arrCat[$cat] = 0;
                                    }
                                    $arrCat[$cat] += 1;
                                    //--------------
                                    if (!isset($arrCode[$rows->$dval])) {
                                        $arrCode[$rows->$dval] = 0;
                                    }
                                    $arrCode[$rows->$dval] += 1;
                                    //--------------
                                }
                            }
                        }
                        $keterangan = "";
                        $static_keterangan = "";
                        if (!empty($arrCat)) {
                            foreach ($arrCat as $kcat => $vcat) {
                                $new_vcat = $vcat * $sessionData[$cCode]['items'][$idp]["jml"];
                                if ($keterangan == "") {
                                    $keterangan = " $new_vcat $kcat";
                                }
                                else {
                                    $keterangan .= "<br> $new_vcat $kcat";
                                }
                                if ($static_keterangan == "") {
                                    $static_keterangan = " $vcat $kcat";
                                }
                                else {
                                    $static_keterangan .= "<br> $vcat $kcat";
                                }
                                $new_keyy = "qty_" . $kcat;
                                $sessionData[$cCode]['items'][$idp][$new_keyy] = $vcat;
                            }
                        }
                        if (!empty($arrCode)) {
                            foreach ($arrCode as $kcat => $vcat) {
                                $new_vcat = $vcat * $sessionData[$cCode]['items'][$idp]["jml"];
                                $sessionData[$cCode]['items'][$idp][$kcat] = $new_vcat;
                            }
                        }
                        $sessionData[$cCode]['items'][$idp]['keterangan'] = $keterangan;
                        $sessionData[$cCode]['items'][$idp]['static_keterangan'] = $static_keterangan;
                    }
                }
            }
            // endregion reload data produk sesuai config dari shoppingcart-----------------------------------


            // region copy gerbang serial dari distribusi
            $shoppingCartCopySerialNumber = isset($this->configUi[$this->jenisTr]["shoppingCartCopySerialNumber"][$stepNumber]) ? $this->configUi[$this->jenisTr]["shoppingCartCopySerialNumber"][$stepNumber] : array();
            if (sizeof($shoppingCartCopySerialNumber) > 0) {
                $statusGudangConfig = $shoppingCartCopySerialNumber["statusGudang"];
                $copyGateConfig = $shoppingCartCopySerialNumber["copyGate"];
                $copyJenisConfig = $shoppingCartCopySerialNumber["copyJenis"];
                if ($gudangStatusJenis == $statusGudangConfig) {
                    $trs = new MdlPenjualanTransaksi();
                    $trs->addFilter("jenis='$copyJenisConfig'");
                    $trs->addFilter("reference_id_top='$topID'");
                    $trsTmp = $trs->lookupAll()->result();
                    $trsID = $trsTmp[0]->id;

                    $trs = new MdlPenjualanTransaksi();
                    $trs->setFilters(array());
                    $trs->setJointSelectFields($copyGateConfig);
                    $trs->addFilter("transaksi_id='$trsID'");
                    $tmpReg = $trs->lookupDataRegistries()->result();
                    if (sizeof($tmpReg) > 0) {
                        foreach ($tmpReg as $row) {
                            foreach ($row as $key_reg => $val_reg) {
                                if ($val_reg == null) {
                                    $val_reg = blobEncode(array());
                                }
                                $sessionData[$cCode][$key_reg] = blobDecode($val_reg);
                            }
                        }
                    }
                }

            }
            // endregion copy gerbang serial dari distribusi


            resetValues($this->jenisTr);
            $sessionData[$cCode] = fillValues_he_value_builder_ns($this->jenisTr, $currentStepNum, $stepNum, $this->configCoreJenis, $this->configUiJenis, $this->configValuesJenis, $ppnFactor, $sessionData[$cCode]);

            $pakai_ini = 0;
            if ($pakai_ini == 1) {
                if ($isMob == true) {
                    /**/

                    $link_mobile = MODUL_PATH . "FollowUp/followupPreviewMobile/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/" . $this->uri->segment(6) . "/" . $this->uri->segment(7) . "?pengirim=$modePengirim";
                    // $actionTarget = "top.window.open('$link_mobile');";
                    $actionTarget = "top.location.href='$link_mobile';";
                    // $actionTarget = "top.window.location.href='$link_mobile';";
                    // mati_disini(__LINEE__);
                    // echo "<script>top.close_holdon();$actionTarget</script>";
                    if (isset($_GET['ismob'])) {
                        echo "<script>$actionTarget</script>";
                    }
                    else {
                        echo "<script>top.close_holdon();$actionTarget</script>";
                    }
                }
                else {
                    $actionTarget = "top.BootstrapDialog.closeAll();top.BootstrapDialog.show(
                                   {
                                       title:'Followup preview',
                                       message: " . 'top.$' . "('<div></div>').load('" . MODUL_PATH . "FollowUp/followupPreview/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/" . $this->uri->segment(6) . "/" . $this->uri->segment(7) . "?rawBuilderURL=$rawBuilderURL'),
                                        size:top.BootstrapDialog.SIZE_WIDE,
                                        type:top.BootstrapDialog.TYPE_DEFAULT,
                                        draggable:true,
                                        closable:false,
                                        
                                        
                                        }
                                        );";

                    echo "<script>top.close_holdon();$actionTarget</script>";
                }
            }

            return $sessionData;
        }
        else {
            mati_disini(("No such transaction. You may want to refresh the browser to re-fetch actual content."));
        }


    }


}


?>