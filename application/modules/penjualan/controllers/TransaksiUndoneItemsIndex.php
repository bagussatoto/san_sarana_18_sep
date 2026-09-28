<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once "Transaksi.php";

class TransaksiUndoneItemsIndex extends Transaksi
{
    public function __construct()
    {
        parent::__construct();

    }

    public function viewUndoneItemsIndex()
    {
        // matiHere(__LINE__);
        $sisa_pembayaran = 100;
        $jenisTrsub = isset($_GET['step']) ? $_GET['step'] : 1;
        $starttime = microtime(true);

        // Check session login
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
        }

        // Initialize variables
        $scriptBottom = "";
        $sesionReplacer = replaceSession();
        $jenisTr = $this->jenisTr;
        $cCode = "_TR_" . $this->jenisTr;

        // Load configuration from configUi
        $configUi = $this->configUi[$jenisTr];
        $paymentConfig = isset($configUi['paymentConfig']) ? $configUi['paymentConfig'] : false;
        $myPaymentConfig = isset($configUi['myPaymentConfig']) ? $configUi['myPaymentConfig'] : false;
        $historyFields = isset($configUi['shortHistoryFields']) ? $configUi['shortHistoryFields'] : array();
        $pairRegistries = isset($configUi['pairRegistries']) ? $configUi['pairRegistries'] : array();
        $connectTo = isset($configUi['connectTo']) ? $configUi['connectTo'] : "";
        $stepHistoryFields = isset($configUi['shortStepHistoryFields']) ? $configUi['shortStepHistoryFields'] : array();
        $arrExtHistoryFields2 = isset($configUi["extHistoryFields2"]) ? $configUi["extHistoryFields2"] : array();
        $kreditLimitValidate = isset($configUi["kreditLimitValidate"]) ? $configUi["kreditLimitValidate"] : array();
        $allowPrintQr = isset($configUi["steps"][$jenisTrsub]["allowPrintQr"]) ? $configUi["steps"][$jenisTrsub]["allowPrintQr"] : 0;
        $arrItemShow = isset($configUi["shortItemsFields"]) ? $configUi["shortItemsFields"] : array();
        $sumberPengiriman = isset($configUi["sumberPengiriman"]) ? $configUi["sumberPengiriman"] : array();

        /* ----------------------------------------------------------------------
         * Mobile detection
         * ----------------------------------------------------------------------*/
        $isMob = isMobile_he_misc();

        // Override history fields for specific IP
        if (ipadd() == "202.65.117.72") {
            $historyFields = isset($configUi['shortHistoryFieldsCek']) ? $configUi['shortHistoryFieldsCek'] : $historyFields;
        }

        // Prepare fields for display
        $prePreFields = $historyFields;
        $prePreFields['state'] = "status";
        $prePreFields['action'] = "action";

        // Get swap jenis transaksi
        $swapJenisTr = isset($configUi['requestCode']['masterCode']) ? $configUi['requestCode']['masterCode'] : array();
        $arrayOnprePre = array();
        $arrayOnprePreGroup = array();

        // Process swap transactions if available
        if (sizeof($swapJenisTr) > 0) {
            $this->_processSwapTransactions($swapJenisTr, $sesionReplacer, $pairRegistries,
                $historyFields, $arrayOnprePre, $arrayOnprePreGroup);
        }

        // Lookup ongoing transactions
        $progressFields = $historyFields;
        $progressFields['state'] = "status";
        $progressFields['action'] = "action";
        $steps = $configUi['steps'];

        $this->load->model("MdlPenjualanTransaksi");
        $tr = new MdlPenjualanTransaksi();
        $activeTabel = $tr->getTableNames();
        $stepCodes = array();
        $jmlStep = count($steps);

        // Apply access list filters
        $this->_applyAccessListFilters($tr, $jenisTr, $jmlStep, $steps);

        // Apply additional filters
        $tr->addFilter("qty_saldo>0");

        if ($this->session->login['employee_type'] == "employee_freelance") {
            $tr->addFilter("seller_id='" . $this->session->login['id'] . "'");
        }

        if ($this->session->login['employee_type'] == "employee_kirim") {
            $tr->addFilter("kirim_metode_id='1'");
        }

        // Execute query
        $tmpHist = $tr->lookupRecentUndoneEntries_joined($sesionReplacer)->result();
        cekBiru($this->db->last_query());

        // Process results
        $arrayOnprogress = array();
        $arrayOnprogressGroup = array();
        $arrayOnprogressPartialMark = array();
        $arrayOnprogressGroupPartialMark = array();

        if (sizeof($tmpHist) > 0) {
            $this->_processTransactionResults($tmpHist, $pairRegistries, $historyFields, $jenisTr,
                $steps, $arrItemShow, $sumberPengiriman, $kreditLimitValidate,
                $arrayOnprogress, $arrayOnprogressGroup,
                $arrayOnprogressPartialMark, $arrayOnprogressGroupPartialMark);
        }

        // Generate link to add new transaction
        $addLink = $this->_generateAddTransactionLink($jenisTr);

        // Prepare history fields for display
        $jenisTrsub = isset($_GET['step']) ? $_GET['step'] : 1;
        $historyFieldsDt = isset($configUi['historyFields'][$jenisTrsub]) ?
            $configUi['historyFields'][$jenisTrsub] : $configUi['shortHistoryFields'];
        $availDbTable = $tr->getAvailTable($historyFieldsDt);

        // Check transaction authorization
        $this->_checkTransactionAuthorization($jenisTr, $configUi, $addLink);

        // Calculate execution time
        $endtime = microtime(true);
        $val = $endtime - $starttime;

        $link_undoneList_kurir = MODUL_PATH . "Transaksi/viewUndoneItemsIndexKurir/$jenisTr/?gr=cGVtYmVsaWFu&ohyes=ohno&step=$getStep";

        // Prepare data for view
        $data = $this->_prepareViewData($jenisTr, $configUi, $isMob, $addLink, $historyFields,
            $progressFields, $historyFieldsDt, $availDbTable, $arrayOnprogress,
            $arrayOnprogressGroup, $arrayOnprePre, $arrayOnprePreGroup,
            $link_undoneList_kurir, $arrayOnprogressPartialMark,
            $arrayOnprogressGroupPartialMark);

        $this->load->view("transaksi", $data);
    }

    // ====================================================================================
    // PRIVATE HELPER METHODS
    // ====================================================================================

    /**
     * Process swap transactions
     */
    private function _processSwapTransactions($swapJenisTr, $sesionReplacer, $pairRegistries,
                                              $historyFields, &$arrayOnprePre, &$arrayOnprePreGroup)
    {
        $configUi = $this->configUi[$swapJenisTr];
        $steps = $configUi['steps'];

        if (sizeof($steps) > 1) {
            $this->load->model("MdlPenjualanTransaksi");
            $tr = new MdlPenjualanTransaksi();
            $arrFilters = array();

            // Build step codes based on access list
            $stepCodes = array();
            $jmlStep = count($steps);

            if (isset($this->accessList[$swapJenisTr]) && sizeof($this->accessList) > 0) {
                $indsteps = "(";
                foreach ($this->accessList[$swapJenisTr] as $stepNumber => $stepSpec) {
                    if ($stepNumber <= $jmlStep) {
                        foreach ($stepSpec as $targetCode => $filters) {
                            $indsteps .= "'$targetCode',";
                            $stepCodes[] = $targetCode;
                            if ($filters['allowFollowUp'] == "true") {
                                $arrFilters["allowFollowUp"][] = $targetCode;
                            }
                        }
                    }
                }
                $indsteps = rtrim($indsteps, ",");
                $indsteps .= ")";

                if (sizeof($arrFilters) > 0) {
                    $tr->addFilter("next_step_code in $indsteps");
                }
                else {
                    $tr->addFilter("transaksi.oleh_id='" . $this->session->login['id'] . "'");
                }
            }

            $tr->addFilter("div_id='" . $this->session->login['div_id'] . "'");
            $tr->addFilter("jenis_top='" . $steps[1]['target'] . "'");
            $tr->addFilter("next_substep_code<>''");
            $tr->addFilter("sub_step_number>0");
            $tr->addFilter("valid_qty>0");

            $tmpHist = $tr->lookupRecentUndoneEntries_joined($sesionReplacer)->result();

            if (sizeof($tmpHist) > 0) {
                $this->_processSwapTransactionResults($tmpHist, $pairRegistries, $historyFields,
                    $swapJenisTr, $arrayOnprePre, $arrayOnprePreGroup);
            }
        }
    }

    /**
     * Process swap transaction results
     */
    private function _processSwapTransactionResults($tmpHist, $pairRegistries, $historyFields,
                                                    $swapJenisTr, &$arrayOnprePre, &$arrayOnprePreGroup)
    {
        $arrTransID = array();
        $arrTransTopID = array();
        $arrIdsHist = array();
        $arrTransHist = array();

        // Collect transaction data
        foreach ($tmpHist as $row) {
            $arrTransID[] = $row->id;
            $arrTransTopID[] = $row->id_top;

            if ($row->ids_his != "") {
                $hist = blobDecode($row->ids_his);
                foreach ($hist as $hisSpec) {
                    $arrIdsHist[$row->id][$hisSpec['step']] = array(
                        "step"  => $hisSpec['step'],
                        "trID"  => $hisSpec['trID'],
                        "nomer" => $hisSpec['nomer'],
                    );
                    $arrTransHist[] = $hisSpec['trID'];
                }
            }
        }

        // Load registries
        $tmpReg_result = array();
        if (sizeof($pairRegistries) > 0) {
            $trReg = new MdlPenjualanTransaksi();
            $trReg->setFilters(array());
            $selectKolom = implode(",", $pairRegistries) . ",transaksi_id";
            $trReg->setJointSelectFields($selectKolom);
            $trReg->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
            $tmpReg_result = $trReg->lookupDataRegistries();
        }

        // Process each transaction
        foreach ($tmpHist as $row) {
            $this->_processSwapTransactionRow($row, $pairRegistries, $historyFields, $swapJenisTr,
                $tmpReg_result, $arrayOnprePre, $arrayOnprePreGroup);
        }
    }

    /**
     * Process individual swap transaction row
     */
    private function _processSwapTransactionRow($row, $pairRegistries, $historyFields, $swapJenisTr,
                                                $tmpReg_result, &$arrayOnprePre, &$arrayOnprePreGroup)
    {
        // Merge registry data
        if ((sizeof($tmpReg_result) > 0) && (isset($tmpReg_result[$row->id]))) {
            foreach ($tmpReg_result[$row->id] as $param => $eReg) {
                foreach ($eReg as $k => $v) {
                    if (!isset($row->$k)) {
                        $row->$k = $v;
                    }
                }
            }
        }

        // Create display array
        $tmp = array();
        foreach ($historyFields as $fName => $fLabel) {
            $tmp[$fName] = isset($row->$fName) ? formatField($fName, $row->$fName) : formatField($fName, 0);
        }

        // Add state information
        $this->_addSwapStateInfo($tmp, $row, $swapJenisTr);

        // Add action buttons
        $this->_addSwapActionButtons($tmp, $row, $swapJenisTr);

        $arrayOnprePre[] = $tmp;
        $arrayOnprePreGroup[$row->sub_step_number][] = $tmp;
    }

    /**
     * Add state information for swap transactions
     */
    private function _addSwapStateInfo(&$tmp, $row, $swapJenisTr)
    {
        if ($row->sub_step_number > 0) {
            $configUi = $this->configUi[$swapJenisTr];
            $stepNum = $row->sub_step_number;

            $tmp['state'] = "<div class='panel panel-warning' style='padding: 3px;margin-bottom: 5px;' ><span style='color:" .
                $configUi['steps'][$stepNum]['stateColor'] . "'>" .
                $configUi['steps'][$stepNum]['stateLabel'] . "</span>";
            $tmp['state'] .= "<br>" . createStateSign($row->sub_step_number, $row->step_avail, $swapJenisTr) . "</div>";

            // Check PO status
            $showPoStatus = isset($configUi['showPoStatus']) ? $configUi['showPoStatus'] : array();
            $cekStateLocation = ($this->session->login['cabang_id'] > 0) ? "cabang" : "pusat";

            $this->_checkPOStatus($tmp, $row, $swapJenisTr, $cekStateLocation);
        }
        else {
            $tmp['state'] = "<span style='color:#777777'>canceled</span>";
        }
    }

    /**
     * Check PO status for swap transactions
     */
    private function _checkPOStatus(&$tmp, $row, $swapJenisTr, $cekStateLocation)
    {
        $arrTransactionSource = array();
        $id_master = $row->id_master;
        $id_top1 = $row->id_top;

        $this->load->model("MdlPenjualanTransaksi");
        $l = new MdlPenjualanTransaksi();
        $l->setFilters(array());
        $l->addFilterJoin("transaksi_data.valid_qty>0");
        $l->addFilter("id_master='" . $id_master . "'");
        $l->addFilter("jenis_master='" . $swapJenisTr . "'");
        $l->addFilter("link_id=0");

        $tmpTS = $l->lookupJoined();

        $arrsub_step_number = array();
        $arrstep_avail = array();
        $arrext_blob = array();
        $arrjenis_master = array();
        $arrtransaksi_no = array();
        $arrketerangan = array();

        if (sizeof($tmpTS) > 0) {
            foreach ($tmpTS as $kk => $arVL) {
                $id = $arVL->id;
                $id_master = $arVL->id_master;
                $id_top2 = $arVL->id_top;
                $produk_id = $arVL->produk_id;
                $arrTransactionSource[$id_top1] = $produk_id;
                $arrsub_step_number[$id_top1] = $arVL->sub_step_number;
                $arrstep_avail[$id_top1] = $arVL->step_avail;
                $arrext_blob[$id_top1][$kk] = isset($arVL->ext_blob) ? ($arVL->ext_blob != "" ? blobDecode($arVL->ext_blob) : "") : "";
            }

            if (sizeof($arrext_blob[$id_top1]) > 0) {
                foreach ($arrext_blob[$id_top1] as $ky => $aVal) {
                    if ($aVal != "") {
                        foreach ($arrext_blob[$id_top1][$ky]['static'] as $numb => $numData) {
                            $arrtransaksi_no[$id_top1][$numb] = formatField_he_format("nomer", $numb);
                            $arrjenis_master[$id_top1][$numb] = $numData['jenis'];
                            $arrketerangan[$id_top1][$numb] = $numData['keterangan'];
                        }
                    }
                }
            }
        }

        if (isset($arrext_blob[$id_top1][$ky]) && $arrext_blob[$id_top1][$ky] != "") {
            $tmp['state'] .= "<div class='panel panel-danger bg-green' style='padding: 3px;margin-bottom: 5px;'>";

            if ($cekStateLocation == "cabang") {
                $tmp['state'] .= "<div><b>diproses oleh PUSAT</b></div>";
            }

            if (sizeof($arrtransaksi_no[$id_top1]) > 0) {
                foreach ($arrtransaksi_no[$id_top1] as $numb_) {
                    $tmp['state'] .= "<div><span class='fa fa-check-circle text-warning'></span> " . $numb_ . "</div>";
                }
            }

            $tmp['state'] .= "</div>";
        }
    }

    /**
     * Add action buttons for swap transactions
     */
    private function _addSwapActionButtons(&$tmp, $row, $swapJenisTr)
    {
        $configUi = $this->configUi[$swapJenisTr];
        $nextStepNum = $row->next_substep_num;
        $currentStepNum = $row->sub_step_number;
        $nextStepCode = $row->next_step_code;

        $allowFollowup = false;
        $actionLabel = "review " . $configUi['steps'][$currentStepNum]['label'];

        if (isset($configUi['steps'][$nextStepNum])) {
            if (isset($this->accessList[$swapJenisTr])) {
                if (isset($this->accessList[$swapJenisTr][$nextStepNum][$nextStepCode]["allowFollowUp"])) {
                    $allowFollowup = $this->accessList[$swapJenisTr][$nextStepNum][$nextStepCode]["allowFollowUp"];
                    $actionLabel = $configUi['steps'][$nextStepNum]['actionLabel'];
                }
            }
            else {
                if (in_array($configUi['steps'][$nextStepNum]['userGroup'], $this->session->login['membership'])) {
                    $allowFollowup = true;
                    $actionLabel = $configUi['steps'][$nextStepNum]['actionLabel'];
                }
                else {
                    $allowFollowup = true;
                    $actionLabel = "review " . $configUi['steps'][$currentStepNum]['label'];
                }
            }
        }

        if ($allowFollowup) {
            $allowJoin = isset($configUi["steps"][$nextStepNum]['allowJoin']) &&
            $configUi["steps"][$nextStepNum]['allowJoin'] == true ?
                $configUi["steps"][$nextStepNum]['allowJoin'] : false;
            $stepLabel = isset($configUi['steps'][$nextStepNum]['label']) ?
                $configUi['steps'][$nextStepNum]['label'] : "";
            $isCancelPacking = isset($configUi['steps'][$nextStepNum]['isCancelPacking']) ?
                $configUi['steps'][$nextStepNum]['isCancelPacking'] : false;
            $allowCancel = isset($configUi['steps'][$nextStepNum]['allowCancel']) ?
                $configUi['steps'][$nextStepNum]['allowCancel'] : false;

            $targetFollowupLink = $isCancelPacking == true ? "followupCancelPackingPrePreview" : "followupPrePreview";
            $followupLink = "top.$('#result').load('" . base_url() . "Transaksi/$targetFollowupLink/" .
                $row->transaksi_id . "/$nextStepNum/" . $row->sub_step_number . "');";

            $tmp['action'] = "<div class='input-group'>";
            $tmp['action'] .= "<a class='btn btn-primary btn-block' title='turn this entry into $stepLabel' " .
                "href='javascript:void(0)' onClick =\"top.open_holdon();$followupLink\">" .
                $actionLabel . "</a>";

            if ($allowJoin) {
                $tmp['action'] .= "<span class='input-group-addon'>";
                $tmp['action'] .= "<a title='process many items at once' href='" . base_url() .
                    "Transaksi/viewIncomplete/" . $this->jenisTr . "/$currentStepNum'>" .
                    "<span class='fa fa-dedent'></span></a>";
                $tmp['action'] .= "</span class='input-group-addon'>";
            }

            $tmp['action'] .= "</div class='input-group'>";

            if ($allowCancel) {
                // Additional cancel button if needed
            }
        }
    }

    /**
     * Apply access list filters
     */
    private function _applyAccessListFilters(&$tr, $jenisTr, $jmlStep, $steps)
    {
        if (isset($this->accessList[$jenisTr]) && sizeof($this->accessList) > 0) {
            $liststep = array();

            foreach ($this->accessList[$jenisTr] as $stepNumber => $stepSpec) {
                if ($stepSpec <= $jmlStep) {
                    $liststep[$steps[$stepSpec]["target"]] = $steps[$stepSpec]["target"];
                }
            }

            if (sizeof($liststep) > 0) {
                $tr->addFilter("next_step_code in ('" . implode("','", $liststep) . "')");
            }
            else {
                $activeTabel = $tr->getTableNames();
                $tr->addFilter($activeTabel["main"] . ".oleh_id='" . $this->session->login['id'] . "'");
                $tr->addFilter("next_step_code!=''");
            }
        }
    }

    /**
     * Process transaction results
     */
    private function _processTransactionResults($tmpHist, $pairRegistries, $historyFields, $jenisTr,
                                                $steps, $arrItemShow, $sumberPengiriman, $kreditLimitValidate,
                                                &$arrayOnprogress, &$arrayOnprogressGroup,
                                                &$arrayOnprogressPartialMark, &$arrayOnprogressGroupPartialMark)
    {
        $arrTransID = array();
        $arrTransTopID = array();
        $arrIdsHist = array();
        $arrTransHist = array();
        $arrNextAction = array();

        // Collect transaction data
        foreach ($tmpHist as $row) {
            $arrTransID[] = $row->transaksi_id;
            $arrTransTopID[] = $row->id_top;
            $arrNextAction[$row->transaksi_id] = array(
                "next_step_num"  => $row->next_step_num,
                "next_step_code" => $row->next_step_code,
            );

            if ($row->ids_his != "") {
                $hist = blobDecode($row->ids_his);
                foreach ($hist as $hisSpec) {
                    $arrIdsHist[$row->id][$hisSpec['step']] = array(
                        "step"  => $hisSpec['step'],
                        "trID"  => $hisSpec['trID'],
                        "nomer" => $hisSpec['nomer'],
                    );
                    $arrTransHist[] = $hisSpec['trID'];
                }
            }
        }

        // Check transaction locks
        $transaksiHold = $this->_checkTransactionLocks($arrTransID);

        // Check payment sources
        $psrcData = $this->_checkPaymentSources($arrTransID);

        // Load registries
        $tmpReg_result = $this->_loadRegistries($pairRegistries, $arrTransID);

        // Check bridge transactions
        $arrTransID_bridge = $this->_checkBridgeTransactions($arrTransID);

        // Get next PIC
        $arrNextPIC = callNextPIC($arrNextAction);

        // Load transaction history
        $arrTransMainHist = $this->_loadTransactionHistory($arrIdsHist, $arrTransHist);

        // Load transaction details
        $detailShow = $this->_loadTransactionDetails($arrTransID, $arrItemShow);

        // Process each transaction
        $numb = 0;
        foreach ($tmpHist as $row) {
            $numb++;
            $this->_processTransactionRow($row, $numb, $jenisTr, $steps, $historyFields, $pairRegistries,
                $tmpReg_result, $arrTransID_bridge, $transaksiHold, $psrcData,
                $arrNextPIC, $kreditLimitValidate, $sumberPengiriman,
                $arrayOnprogress, $arrayOnprogressGroup,
                $arrayOnprogressPartialMark, $arrayOnprogressGroupPartialMark);
        }
    }

    /**
     * Check transaction locks
     */
    private function _checkTransactionLocks($arrTransID)
    {
        $transaksiHold = array();

        if (sizeof($arrTransID) > 0) {
            $this->load->model("Mdls/MdlLockerTransaksi");
            $lt = new MdlLockerTransaksi();
            $lt->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
            $lt->addFilter("state='hold'");
            $lt->addFilter("jumlah='1'");

            $ltTmp = $lt->lookupAll()->result();

            if (sizeof($ltTmp) > 0) {
                foreach ($ltTmp as $ltSpec) {
                    $hold_oleh_id = $ltSpec->oleh_id;
                    if ($hold_oleh_id != my_id()) {
                        $transaksiHold[$ltSpec->transaksi_id] = $ltSpec->transaksi_id;
                    }
                }
            }
        }

        return $transaksiHold;
    }

    /**
     * Check payment sources
     */
    private function _checkPaymentSources($arrTransID)
    {
        $psrcData = array();
        $sisa_pembayaran = 100;

        if (sizeof($arrTransID) > 0) {
            $psrc = new MdlPenjualanTransaksi();
            $psrc->setFilters(array());
            $psrc->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
            $psrc->addFilter("sisa>$sisa_pembayaran");

            $psrcTmp = $psrc->lookUpAllPaymentSrc()->result();

            if (sizeof($psrcTmp) > 0) {
                foreach ($psrcTmp as $psrcSpec) {
                    $psrcData[$psrcSpec->transaksi_id] = array(
                        "extern_id"   => $psrcSpec->extern_id,
                        "extern_nama" => $psrcSpec->extern_nama,
                        "nomer"       => $psrcSpec->nomer,
                        "terbayar"    => $psrcSpec->terbayar,
                        "sisa"        => $psrcSpec->sisa,
                        "label"       => "* Transaksi belum dibayar/belum lunas.",
                    );
                }
            }
        }

        return $psrcData;
    }

    /**
     * Load registries
     */
    private function _loadRegistries($pairRegistries, $arrTransID)
    {
        $tmpReg_result = array();

        if (sizeof($pairRegistries) > 0 && sizeof($arrTransID) > 0) {
            $selectKolom = implode(",", $pairRegistries) . ", transaksi_id";
            $trReg = new MdlPenjualanTransaksi();
            $trReg->setFilters(array());
            $trReg->setJointSelectFields($selectKolom);
            $trReg->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
            $tmpReg_result = $trReg->lookupDataRegistries();
        }

        return $tmpReg_result;
    }

    /**
     * Check bridge transactions
     */
    private function _checkBridgeTransactions($arrTransID)
    {
        $arrTransID_bridge = array();

        if (sizeof($arrTransID) > 0) {
            $this->load->model("../../pembelian/models/Coms/ComTransaksiDataPembelianBridging");
            $ctdb = new ComTransaksiDataPembelianBridging();
            $ctdb->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
            $ctdbTmp = $ctdb->lookupAll()->result();

            if (sizeof($ctdbTmp) > 0) {
                foreach ($ctdbTmp as $ctdbSpec) {
                    $arrTransID_bridge[$ctdbSpec->transaksi_id] = $ctdbSpec->transaksi_id;
                }
            }
        }

        return $arrTransID_bridge;
    }

    /**
     * Load transaction history
     */
    private function _loadTransactionHistory($arrIdsHist, $arrTransHist)
    {
        $arrTransMainHist = array();

        if (sizeof($arrIdsHist) > 0 && sizeof($arrTransHist) > 0) {
            $tr = new MdlPenjualanTransaksi();
            $tr->setFilters(array());
            $tr->addFilter("id in ('" . implode("','", $arrTransHist) . "')");

            $tmpTransHist = $tr->lookupAll()->result();
            $tmpTransHist_result = array();

            if (sizeof($tmpTransHist) > 0) {
                foreach ($tmpTransHist as $histSpec) {
                    $tmpTransHist_result[$histSpec->id] = array(
                        "oleh_id"   => $histSpec->oleh_id,
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

        return $arrTransMainHist;
    }

    /**
     * Load transaction details
     */
    private function _loadTransactionDetails($arrTransID, $arrItemShow)
    {
        $detailShow = array();

        if (count($arrItemShow) > 0 && sizeof($arrTransID) > 0) {
            $tr = new MdlPenjualanTransaksi();
            $tr->setFilters(array());
            $detailShow = $tr->lookupDetailTransaksi($arrTransID);
        }

        return $detailShow;
    }

    /**
     * Process individual transaction row
     */
    private function _processTransactionRow($row, $numb, $jenisTr, $steps, $historyFields, $pairRegistries,
                                            $tmpReg_result, $arrTransID_bridge, $transaksiHold, $psrcData,
                                            $arrNextPIC, $kreditLimitValidate, $sumberPengiriman,
                                            &$arrayOnprogress, &$arrayOnprogressGroup,
                                            &$arrayOnprogressPartialMark, &$arrayOnprogressGroupPartialMark)
    {
        $configUi = $this->configUi[$jenisTr];
        $arrExtHistoryFields2 = isset($configUi["extHistoryFields2"]) ? $configUi["extHistoryFields2"] : array();
        $extHistoryFields2 = isset($arrExtHistoryFields2[$row->step_number]) ? $arrExtHistoryFields2[$row->step_number] : array();

        // Merge registry data
        if (sizeof($pairRegistries) > 0 && isset($tmpReg_result[$row->transaksi_id])) {
            $this->_mergeRegistryData($row, $tmpReg_result[$row->transaksi_id], $extHistoryFields2);
        }

        // Create display array
        $tmp = array();
        $sumFooter = array();

        foreach ($historyFields as $fName => $fLabel) {
            $tmp[$fName] = $this->_formatHistoryField($row, $fName, $fLabel, $sumFooter);

            if ($fName == "no") {
                $tmp[$fName] = formatField_he_format($fName, $numb);
            }
        }

        // Add cancel packing info
        if (sizeof($row->cancel_packing_source_id) > 0) {
            $this->_addCancelPackingInfo($tmp, $row);
        }

        // Add state information
        $this->_addStateInfo($tmp, $row, $jenisTr);

        // Add item details
        $this->_addItemDetails($tmp, $row, $tmpReg_result, $jenisTr);

        // Add next PIC
        $this->_addNextPIC($tmp, $row, $arrNextPIC);

        // Add action buttons
        $this->_addActionButtons($tmp, $row, $jenisTr, $configUi, $steps, $arrTransID_bridge,
            $transaksiHold, $psrcData, $kreditLimitValidate, $sumberPengiriman);

        // Add to arrays
        $arrayOnprogress[] = $tmp;
        $arrayOnprogressGroup[$row->step_number][] = $tmp;

        // Add partial marks
        $tmpMark = array();
        if ($row->partial == 1) {
            $tmpMark['style'] = "background-color:yellow;";
            $tmp['keterangan'] = "<span style='color:red;'>transaksi diproses sebagian</span>";
        }

        $arrayOnprogressPartialMark[] = $tmpMark;
        $arrayOnprogressGroupPartialMark[$row->step_number][] = $tmpMark;
    }

    /**
     * Merge registry data
     */
    private function _mergeRegistryData(&$row, $registryData, $extHistoryFields2)
    {
        foreach ($registryData as $param => $eReg) {
            switch ($param) {
                case "main":
                case "main_entries":
                    foreach ($eReg as $k => $v) {
                        if (($k != null) && !isset($row->$k)) {
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
                                if (($k1 != null) && !isset($row->$k1)) {
                                    $tmpDetail = "";
                                    foreach ($eReg as $eeReg) {
                                        $valDetail = formatField_he_format($format, $eeReg[$kolom]);
                                        $tmpDetail .= "<span>$valDetail</span><br>";
                                    }
                                    $row->$k1 = $tmpDetail;
                                }
                            }
                            else {
                                if (($k1 != null) && !isset($row->$k1)) {
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

    /**
     * Format history field
     */
    private function _formatHistoryField($row, $fName, $fLabel, &$sumFooter)
    {
        if (isset($row->$fName)) {
            if (is_numeric($row->$fName)) {
                if (!isset($sumFooter[$fName])) {
                    $sumFooter[$fName] = 0;
                }
                $sumFooter[$fName] += $row->$fName;
            }
        }

        if (is_array($fLabel)) {
            $hisStep = isset($fLabel['step']) ? $fLabel['step'] : 0;
            $hisKey = isset($fLabel['key']) ? $fLabel['key'] : 0;

            if (isset($row->ids_his)) {
                if ($hisKey == "nomer") {
                    $returnVal = showHistoriGlobalNumbers($row->ids_his, $hisStep, true, $this->jenisTr);
                    return $returnVal == "" ? "-" : $returnVal;
                }
                else {
                    $ids_his_decode = blobDecode($row->ids_his);
                    return isset($ids_his_decode[$hisStep][$hisKey]) ? $ids_his_decode[$hisStep][$hisKey] : "-";
                }
            }
            else {
                return "-";
            }
        }
        else {
            return isset($row->$fName) ? formatField_he_format($fName, $row->$fName) : formatField_he_format($fName, 0);
        }
    }

    /**
     * Add cancel packing info
     */
    private function _addCancelPackingInfo(&$tmp, $row)
    {
        if (sizeof($row->cancel_packing_source_id) > 0) {
            $trx = new MdlPenjualanTransaksi();
            $trx->addFilter("id='" . $row->cancel_packing_source_id . "'");
            $tmpTrx = $trx->lookupAll()->result();

            if (sizeof($tmpTrx) > 0) {
                $tmp['nomer_top'] = formatField_he_format("nomer_top", $tmpTrx[0]->nomer);
            }
        }
    }

    /**
     * Add state information
     */
    private function _addStateInfo(&$tmp, $row, $jenisTr)
    {
        if ($row->step_number > 0) {
            $configUi = $this->configUi[$jenisTr];
            $stepNum = $row->step_number;

            $tmp['state'] = "<span style='color:" . $configUi['steps'][$stepNum]['stateColor'] . "'>" .
                $configUi['steps'][$stepNum]['stateLabel'] . "</span>";
            $tmp['state'] .= "<br>" . createStateSign($row->step_number, $row->step_avail, $jenisTr);
        }
        else {
            $tmp['state'] = "<span style='color:#777777'>canceled</span>";
        }
    }

    /**
     * Add item details
     */
    private function _addItemDetails(&$tmp, $row, $tmpReg_result, $jenisTr)
    {
        $arrItemShow = isset($this->configUi[$jenisTr]["shortItemsFields"]) ?
            $this->configUi[$jenisTr]["shortItemsFields"] : array();

        if (isset($tmpReg_result[$row->transaksi_id])) {
            $detail = viewDetailTransaksi($tmpReg_result[$row->transaksi_id], $arrItemShow, $row->jenis_master);
            $tmp['item_fields'] = $detail;
        }
        else {
            $tmp['item_fields'] = "";
        }
    }

    /**
     * Add next PIC
     */
    private function _addNextPIC(&$tmp, $row, $arrNextPIC)
    {
        $tmp['next_pic'] = "-";

        if (sizeof($arrNextPIC) > 0 && isset($arrNextPIC[$row->next_substep_code][$row->next_substep_num])) {
            $next_pic = "";
            $nob = 1;

            foreach ($arrNextPIC[$row->next_substep_code][$row->next_substep_num] as $spec) {
                $cabangNamaPIC = "<span class='meta'>(" . $spec['cabang_nama'] . ")</span>";

                if ($row->cabang_id == $spec['cabang_id']) {
                    $next_pic = $this->_buildNextPICString($next_pic, $nob, $spec, $cabangNamaPIC);
                    $nob++;
                }

                if ($spec['cabang_id'] == CB_ID_PUSAT) {
                    $next_pic = $this->_buildNextPICString($next_pic, $nob, $spec, $cabangNamaPIC);
                    $nob++;
                }
            }

            $tmp['next_pic'] = $next_pic;
        }
    }

    /**
     * Build next PIC string
     */
    private function _buildNextPICString($current, $index, $spec, $cabangNamaPIC)
    {
        $newEntry = "$index. " . $spec['nama'] . " $cabangNamaPIC";

        if ($current == "") {
            return $newEntry;
        }
        else {
            return $current . "<br>" . $newEntry;
        }
    }

    /**
     * Add action buttons
     */
    private function _addActionButtons(&$tmp, $row, $jenisTr, $configUi, $steps, $arrTransID_bridge,
                                       $transaksiHold, $psrcData, $kreditLimitValidate, $sumberPengiriman)
    {
        $isMob = isMobile_he_misc();
        $nextStepNum = $row->next_step_num;
        $currentStepNum = $row->step_number;
        $nextStepCode = $row->next_step_code;

        $allowFollowup = true;
        $actionLabel = "review " . $configUi['steps'][$currentStepNum]['label'];

        if (isset($configUi['steps'][$nextStepNum])) {
            if (isset($this->accessList[$jenisTr])) {
                if (in_array($nextStepNum, $this->accessList[$jenisTr])) {
                    $actionLabel = $configUi['steps'][$nextStepNum]['actionLabel'];
                }
            }
            else {
                if (in_array($configUi['steps'][$nextStepNum]['userGroup'], $this->session->login['membership'])) {
                    $actionLabel = $configUi['steps'][$nextStepNum]['actionLabel'];
                }
            }
        }

        $req_cancel_qty = $row->req_cancel_qty != '' ? $row->req_cancel_qty : 0;
        $valid_qty = $row->qty_saldo != '' ? $row->qty_saldo : 0;

        // Check shipping source
        $disabled_sumber_pengiriman = "";
        $label_sumber_pengiriman = "";

        if (isset($sumberPengiriman[$currentStepNum]["enabled"]) &&
            ($sumberPengiriman[$currentStepNum]["enabled"] == true)) {
            $sumberPengirimanKey = $sumberPengiriman[$currentStepNum]["key"];
            $sumberPengirimanLabel = $sumberPengiriman[$currentStepNum]["label"];

            if (isset($sumberPengirimanKey[$row->pihakMainExec_id])) {
                $disabled_sumber_pengiriman = $sumberPengirimanKey[$row->pihakMainExec_id];
                $label_sumber_pengiriman = $sumberPengirimanLabel[$row->pihakMainExec_id];
            }
        }

        if ($allowFollowup) {
            $allowJoin = isset($configUi["steps"][$nextStepNum]['allowJoin']) &&
            $configUi["steps"][$nextStepNum]['allowJoin'] == true ?
                $configUi["steps"][$nextStepNum]['allowJoin'] : false;
            $stepLabel = isset($configUi['steps'][$nextStepNum]['label']) ?
                $configUi['steps'][$nextStepNum]['label'] : "";
            $isCancelPacking = isset($configUi['steps'][$nextStepNum]['isCancelPacking']) ?
                $configUi['steps'][$nextStepNum]['isCancelPacking'] : false;
            $allowCancel = isset($configUi['steps'][$nextStepNum]['allowCancel']) ?
                $configUi['steps'][$nextStepNum]['allowCancel'] : false;
            $allowScaner = isset($configUi['steps'][$nextStepNum]['allowScaner']) ?
                $configUi['steps'][$nextStepNum]['allowScaner'] : false;
            $allowNextStepOtorisasi = isset($configUi['allowNextStepOtorisasi'][$nextStepNum]) ?
                $configUi['allowNextStepOtorisasi'][$nextStepNum] : array();

            $this->_buildActionButtons($tmp, $row, $jenisTr, $nextStepNum, $currentStepNum,
                $isCancelPacking, $allowJoin, $stepLabel, $allowCancel,
                $allowScaner, $isMob, $arrTransID_bridge, $transaksiHold,
                $psrcData, $kreditLimitValidate, $sumberPengiriman,
                $disabled_sumber_pengiriman, $label_sumber_pengiriman,
                $actionLabel, $req_cancel_qty, $valid_qty);
        }

        // Add edit and reject buttons
        $this->_addEditRejectButtons($tmp, $row, $jenisTr, $nextStepNum, $currentStepNum, $transaksiHold);
    }

    /**
     * Build action buttons
     */
    private function _buildActionButtons(&$tmp, $row, $jenisTr, $nextStepNum, $currentStepNum,
                                         $isCancelPacking, $allowJoin, $stepLabel, $allowCancel,
                                         $allowScaner, $isMob, $arrTransID_bridge, $transaksiHold,
                                         $psrcData, $kreditLimitValidate, $sumberPengiriman,
                                         $disabled_sumber_pengiriman, $label_sumber_pengiriman,
                                         $actionLabel, $req_cancel_qty, $valid_qty)
    {
        $configUi = $this->configUi[$jenisTr];
        $sisa_pembayaran = 100;

        $tmpMark = array();
        $tmp['keterangan'] = "-";

        if ($row->partial == 1) {
            $tmpMark['style'] = "background-color:yellow;";
            $tmp['keterangan'] = "<span style='color:red;'>transaksi diproses sebagian</span>";
        }

        $targetFollowupLink = $isCancelPacking == true ? "followupCancelPackingPrePreview" : "followupPrePreview";
        $followupLink = "top.$('#result').load('" . MODUL_PATH . "FollowUp/$targetFollowupLink/$jenisTr/" .
            $row->transaksi_id . "/$nextStepNum/" . $row->step_number . "');";

        $btn_block = $isMob ? "" : "btn-block";
        $class_button = "btn-primary";

        $disabled_button = "";
        $disabled_button_kreditlimit = "";
        $disabled_button_reject = "";
        $disabled_kirim = "";
        $disabled_hold = "";
        $add_note = "";
        $strPengirim = "";

        // Check payment status
        if (isset($psrcData[$row->transaksi_id])) {
            $this->_handlePaymentStatus($psrcData[$row->transaksi_id], $sisa_pembayaran,
                $disabled_button, $disabled_button_reject,
                $class_button, $actionLabel, $add_note);
        }

        // Check credit limit
        $numberValidate = $row->step_number + 1;
        if (isset($kreditLimitValidate[$numberValidate])) {
            $this->_handleCreditLimit($row, $kreditLimitValidate[$numberValidate],
                $disabled_button_kreditlimit, $add_note, $class_button);
        }

        // Check shipping method
        if ($row->kirim_metode_id == 1) {
            $this->_handleShippingMethod($row, $jenisTr, $nextStepNum, $currentStepNum,
                $disabled_kirim, $add_note, $strPengirim);
        }

        // Check transaction holds
        if (isset($transaksiHold[$row->transaksi_id])) {
            $disabled_hold = "disabled";
            $add_note .= "<br><span class='meta'>* Barang siap dikirim.<br>Edit/Reject SO tidak bisa digunakan.</span>";
        }

        // Check bridge transactions
        $disabled_button_bridge = "";
        if (in_array($row->transaksi_id, $arrTransID_bridge)) {
            $disabled_button_bridge = "disabled";
            $tmpMark['style'] = "background-color:orange;";
            $tmp['keterangan'] = "<span style='color:black;'>Transaksi ini dibuat dan otorisasi otomatis by SYSTEM. Tidak perlu otorisasi manual.</span>";
        }

        $add_note .= "<br><span class='meta'>* $label_sumber_pengiriman</span>";

        // Build action HTML
        if ($this->session->login["employee_type"] != "employee_kirim") {
            $this->_buildActionHTML($tmp, $row, $jenisTr, $nextStepNum, $currentStepNum,
                $configUi, $allowScaner, $isMob, $followupLink, $btn_block,
                $class_button, $disabled_hold, $disabled_button_bridge,
                $disabled_sumber_pengiriman, $actionLabel, $strPengirim,
                $add_note, $req_cancel_qty, $valid_qty);
        }
    }

    /**
     * Handle payment status
     */
    private function _handlePaymentStatus($paymentData, $sisa_pembayaran, &$disabled_button,
                                          &$disabled_button_reject, &$class_button, &$actionLabel, &$add_note)
    {
        if ($paymentData["sisa"] > $sisa_pembayaran) {
            $disabled_button = "disabled";
            $add_note .= "<br><span class='meta'>" . $paymentData["label"] . "</span>";
            $class_button = "btn-primary";
            $actionLabel = "**menunggu pelunasan";
        }

        if ($paymentData["sisa"] <= $sisa_pembayaran) {
            $disabled_button = "";
            $disabled_button_reject = "disabled";
            $add_note .= "<br><button disabled class='btn btn-success btn-block btn-sm fa fa-money' style='margin-top:5px;'> LUNAS</button>";
            $class_button = "btn-primary";
            $actionLabel = "siap dikirim dari cabang";
        }
    }

    /**
     * Handle credit limit
     */
    private function _handleCreditLimit($row, $kreditLimitConfig, &$disabled_button_kreditlimit,
                                        &$add_note, &$class_button)
    {
        $arrKreditLimitDataKredit = array(); // This should be loaded from the model

        if (isset($arrKreditLimitDataKredit[$row->customers_id]["kredit"]) &&
            ($arrKreditLimitDataKredit[$row->customers_id]["kredit"] > 0)) {
            $disabled_button_kreditlimit = "disabled";
            $add_note .= "<br><span class='meta'>" . $kreditLimitConfig["label"] . "</span>";
            $class_button = "btn-primary";
        }
    }

    /**
     * Handle shipping method
     */
    private function _handleShippingMethod($row, $jenisTr, $nextStepNum, $currentStepNum,
                                           &$disabled_kirim, &$add_note, &$strPengirim)
    {
        if (($row->pengirim_id == 0) || ($row->pengirim_id == null)) {
            $followupPengirimLink = "top.$('#result').load('" . MODUL_PATH .
                "FollowUp/doFollowupPengirim/$jenisTr/" . $row->transaksi_id .
                "/$nextStepNum/" . $row->step_number . "?pengirim=pengirim');";
            $disabled_kirim = ($row->step_number >= 3) ? "disabled" : "";
            $add_note .= "<br><span class='meta'>Pengirim belum terdaftar pada transaksi ini.</span>";
        }
    }

    /**
     * Build action HTML
     */
    private function _buildActionHTML(&$tmp, $row, $jenisTr, $nextStepNum, $currentStepNum,
                                      $configUi, $allowScaner, $isMob, $followupLink, $btn_block,
                                      $class_button, $disabled_hold, $disabled_button_bridge,
                                      $disabled_sumber_pengiriman, $actionLabel, $strPengirim,
                                      $add_note, $req_cancel_qty, $valid_qty)
    {
        $allowNextStepOtorisasi = isset($configUi['allowNextStepOtorisasi'][$nextStepNum]) ?
            $configUi['allowNextStepOtorisasi'][$nextStepNum] : array();

        // Check warehouse status
        if ($row->cabang_id > 0) {
            if (($row->gudang_status_jenis != NULL) && ($row->gudang_status_jenis == "pusat")) {
                $allowScaner = false;
                if (isset($allowNextStepOtorisasi[$row->gudang_status_jenis])) {
                    $disabled_button = $allowNextStepOtorisasi[$row->gudang_status_jenis];
                    $add_note .= "<br><span class='meta'>* " . $allowNextStepOtorisasi["label"] . "</span>";
                    $actionLabel = "* siap dikirim dari dc/pusat";
                }
            }
        }

        $tmp['action'] = "<div class='input-group'>";

        if ($allowScaner == false || $isMob == 1) {
            $tmp['action'] .= "<button class='btn $btn_block $class_button' title='turn this entry into $stepLabel' 
            $disabled_hold $disabled_button_bridge $disabled_sumber_pengiriman
            href='javascript:void(0)' 
            onClick =\"top.open_holdon();$followupLink\">" . $actionLabel . "</button>";
        }
        else {
            $actionLabel = "QR untuk Handphone";
            $targetFollowupLink = "followupDariHp";
            $fpLink = MODUL_PATH . "FollowUp/$targetFollowupLink/$jenisTr/" .
                $row->transaksi_id . "/$nextStepNum/" . $row->step_number;
            $followupLink = "BootstrapDialog.show({
            title:'" . $actionLabel . "',
            message: $('<div></div>').load('" . $fpLink . "'),
            draggable:true,
            closable:true,
        });";
            $tmp['action'] .= "<button class='btn btn-primary btn-block' $disabled_button 
            title='turn this entry into $stepLabel' href='javascript:void(0)' 
            onClick =\"$followupLink\">" . $actionLabel . "</button>";
        }

        $tmp['action'] .= isset($strPengirim) ? $strPengirim : "";
        $tmp['action'] .= "</div class='input-group'>";
        $tmp['action'] .= $add_note;

        if ($req_cancel_qty > 0 && $valid_qty == 0) {
            $tmp['action'] = "<div class='btn-group' role='group' aria-label='cancel packing on progress'>";
            $tmp['action'] .= "<button type='button' disabled class='btn btn-warning' 
            title='sedang dalam process cancel packing' href='javascript:void(0)'>menuggu approve cancel</button>";
            $tmp['action'] .= "</div>";
        }
    }

    /**
     * Add edit and reject buttons
     */
    private function _addEditRejectButtons(&$tmp, $row, $jenisTr, $nextStepNum, $currentStepNum, $transaksiHold)
    {
        $arrStepAllow = array(1, 2);

        if (in_array($row->step_number, $arrStepAllow)) {
            $disabled_hold = isset($transaksiHold[$row->transaksi_id]) ? "disabled" : "";
            $disabled_button_reject = "";

            // Add edit button
            $this->_addEditButton($tmp, $row, $jenisTr, $nextStepNum, $currentStepNum, $disabled_hold, $disabled_button_reject);

            // Add reject buttons
            $this->_addRejectButtons($tmp, $row, $jenisTr, $nextStepNum, $currentStepNum, $disabled_hold, $disabled_button_reject);
        }
    }

    /**
     * Add edit button
     */
    private function _addEditButton(&$tmp, $row, $jenisTr, $nextStepNum, $currentStepNum, $disabled_hold, $disabled_button_reject)
    {
        $evPre = evaluatePreProcessors_he_menu($this->jenisTr, $currentStepNum, $this->configCoreJenis, $this->configUiJenis);
        $evPost = evaluatePostProcessors_he_menu($this->jenisTr, $currentStepNum, $this->configCoreJenis, $this->configUiJenis);
        $evCom = evaluateComponents_he_menu($this->jenisTr, $currentStepNum, $this->configCoreJenis, $this->configUiJenis);
        $evMaster = evaluateMain_he_menu($this->jenisTr, $currentStepNum, $this->configCoreJenis, $this->configUiJenis);

        if ($evPre == null && $evPost == null && $evCom == null && $evMaster == null) {
            $transaksiID_reject = $row->transaksi_id;
            $link_reject_all = MODUL_PATH . "FollowUp/followupPrePreview/" . $this->jenisTr .
                "/$transaksiID_reject/$nextStepNum/" . $row->step_number . "?getmode=edit";
            $actionLabel_edit = "Edit";

            $tmp['action'] .= "<button class='btn btn-danger btn-block btn-xs' title='turn this entry into $stepLabel' 
            href='javascript:void(0)' 
            style='background-color:#000000;color:#ffffff;'
            $disabled_hold $disabled_button_reject
            onclick =\"document.getElementById('result').src='$link_reject_all'\">" . $actionLabel_edit . " </button>";
        }
    }

    /**
     * Add reject buttons
     */
    private function _addRejectButtons(&$tmp, $row, $jenisTr, $nextStepNum, $currentStepNum, $disabled_hold, $disabled_button_reject)
    {
        $arrStepAllow = array(1, 2, 3);

        if (in_array($row->step_number, $arrStepAllow)) {
            $evPre = evaluatePreProcessors_he_menu($this->jenisTr, $currentStepNum, $this->configCoreJenis, $this->configUiJenis);
            $evPost = evaluatePostProcessors_he_menu($this->jenisTr, $currentStepNum, $this->configCoreJenis, $this->configUiJenis);
            $evCom = evaluateComponents_he_menu($this->jenisTr, $currentStepNum, $this->configCoreJenis, $this->configUiJenis);

            if ($evPre == null && $evPost == null && $evCom == null) {
                $transaksiID_reject = $row->transaksi_id;

                // Reject 1 step button
                if ($this->reject == true) {
                    $link_reject = MODUL_PATH . "FollowUp/followupPrePreview/" . $this->jenisTr .
                        "/$transaksiID_reject/$nextStepNum/" . $row->step_number . "?getmode=reject";
                    $actionLabel_edit = "Reject 1 step";

                    $tmp['action'] .= "<button class='btn btn-danger btn-block btn-xs' title='turn this entry into $stepLabel' 
                    href='javascript:void(0)' 
                    $disabled_hold $disabled_button_reject
                    onclick =\"document.getElementById('result').src='$link_reject'\">" . $actionLabel_edit . " </button>";
                }

                // Reject all steps button
                if ($this->reject_all == true) {
                    $link_reject_all = MODUL_PATH . "FollowUp/followupPrePreview/" . $this->jenisTr .
                        "/$transaksiID_reject/$nextStepNum/" . $row->step_number . "?getmode=rejectall";
                    $actionLabel_edit = "Reject all step";

                    $tmp['action'] .= "<button class='btn btn-danger btn-block btn-xs' title='turn this entry into $stepLabel' 
                    href='javascript:void(0)' 
                    style='background-color:#000000;color:#ffffff;'
                    $disabled_hold $disabled_button_reject
                    onclick =\"document.getElementById('result').src='$link_reject_all'\">" . $actionLabel_edit . " </button>";
                }
            }
        }
    }

    /**
     * Generate add transaction link
     */
    private function _generateAddTransactionLink($jenisTr)
    {
        if (placeCanMakeTrans_he_menu($this->session->login['membership'],
            $this->session->login['cabang_id'],
            $this->session->login['gudang_id'],
            $jenisTr, $this->configUiJenis)) {

            $createIndexes = (null != $this->config->item("transaksi_createIndex")) ?
                $this->config->item("transaksi_createIndex") : array();
            $isDisableMakeTrans = isset($this->configUi[$jenisTr]['isDisableMakeTrans']) ?
                $this->configUi[$jenisTr]['isDisableMakeTrans'] : false;

            if (array_key_exists($jenisTr, $createIndexes)) {
                $targetUrl = MODUL_PATH . $createIndexes[$jenisTr] . "/" . $jenisTr;
            }
            else {
                $targetUrl = MODUL_PATH . "Create/index/" . $jenisTr;
            }

            if ($isDisableMakeTrans) {
                return null;
            }
            else {
                return array(
                    "link"  => $targetUrl,
                    "label" => "<span class='glyphicon glyphicon-plus'></span> create new " .
                        $this->configUi[$jenisTr]["steps"][1]['label'],
                );
            }
        }
        else {
            return null;
        }
    }

    /**
     * Check transaction authorization
     */
    private function _checkTransactionAuthorization($jenisTr, $configUi, &$addLink)
    {
        $arrJenisTrCek = array("587", "687", "1587", "1687");

        if (in_array($jenisTr, $arrJenisTrCek)) {
            $transaksiName = isset($configUi['label']) ? $configUi['label'] : NULL;
            $subplace = isset($configUi['steps'][1]['subplace']) ? $configUi['steps'][1]['subplace'] : NULL;

            if ($subplace != NULL) {
                if (($subplace == "warehouse") && ($this->session->login['gudang_id'] < 0)) {
                    // User has permission
                }
                elseif (($subplace == "warehouse_ng") && ($this->session->login['gudang_id'] > 0)) {
                    // User has permission
                }
                else {
                    $msg = "Anda tidak memiliki kewenangan untuk membuat request $transaksiName";
                    $addLink = array();
                }
            }
        }
    }

    /**
     * Prepare view data
     */
    private function _prepareViewData($jenisTr, $configUi, $isMob, $addLink, $historyFields,
                                      $progressFields, $historyFieldsDt, $availDbTable, $arrayOnprogress,
                                      $arrayOnprogressGroup, $arrayOnprePre, $arrayOnprePreGroup,
                                      $link_undoneList_kurir, $arrayOnprogressPartialMark,
                                      $arrayOnprogressGroupPartialMark)
    {
        return array(
            "mode"                            => isset($configUi["mode"]) ? $configUi["mode"] : $this->uri->segment(3),
            "isMobile"                        => $isMob,
            "errMsg"                          => $this->session->errMsg,
            "template"                        => $configUi["template"],
            "title"                           => $configUi["label"],
            "subTitle"                        => $configUi["steps"][1]['label'],
            "jenisTr"                         => $jenisTr,
            "trName"                          => $configUi["label"],
            'addLink'                         => $addLink,
            "allSteps"                        => $this->allSteps,
            "historyTitle"                    => "<span class='glyphicon glyphicon-time'></span> recent " . $configUi["label"] . " histories",
            "arrayHistoryLabels"              => array("dtime" => "time") + $historyFields,
            "arrayHistoryLabelsDt"            => $historyFieldsDt,
            "availDbTable"                    => $availDbTable,
            "arrayHistory"                    => isset($arrayHistory) ? $arrayHistory : array(),
            "onprogressTitle"                 => "<span class='glyphicon glyphicon-alert'></span> TRANSAKSI YANG PERLU ACTION ",
            "arrayProgressLabels"             => $progressFields,
            "arrayOnProgressToPay"            => isset($configUi['paymentConfig']) ? $configUi['paymentConfig'] : false,
            "itemLabels"                      => isset($itemLabels) ? $itemLabels : "",
            "srcLabel"                        => isset($srcLabel) ? $srcLabel : "",
            "steps"                           => $isMob == true ? array(2 => $configUi['steps'][2]) : $configUi['steps'],
            "arrayOnProgress"                 => (isset($arrayOnprogress) && sizeof($arrayOnprogress) > 0) ? $arrayOnprogress : array(),
            "arrayOnprogressGroup"            => (isset($arrayOnprogressGroup) && (sizeof($arrayOnprogressGroup) > 0)) ? $arrayOnprogressGroup : array(),
            "arrayOnprePre"                   => $arrayOnprePre,
            "arrayOnprePreGroup"              => $arrayOnprePreGroup,
            "arrayOnpreDistribution"          => isset($arrayOnpreDistribution) ? $arrayOnpreDistribution : array(),
            "arrayOnpreDistributionGroup"     => isset($arrayOnpreDistributionGroup) ? $arrayOnpreDistributionGroup : array(),
            "entities"                        => isset($entities) ? $entities : array(),
            "recapTitle"                      => "<span class='fa fa-newspaper-o'></span> today " . $configUi["label"] . " reports",
            "arrayRecapLabels"                => isset($recapLabels) ? $recapLabels : array(),
            "arrayRecap"                      => isset($arrayRecap) ? $arrayRecap : array(),
            "onprogressViewTitle"             => "<span class='fa fa-eye'></span> show incomplete step " . $configUi["label"],
            "onprogressViewSubTitle"          => "<span class='text-black'>(daftar transaksi yang masih stanby di cabang tujuan)</span>",
            "arrayOnProgressView"             => isset($arrayOnProgressView) ? $arrayOnProgressView : array(),
            "stepHistoryFields"               => isset($configUi['shortStepHistoryFields']) ? $configUi['shortStepHistoryFields'] : array(),
            "selectProcessor"                 => isset($selectProcessor) ? $selectProcessor : "",
            "sumFooter"                       => isset($sumFooter) ? $sumFooter : array(),
            "scriptBottom"                    => "",
            "btnLabel"                        => isset($extData) ? $extData : "",
            "actionTarget"                    => isset($extact) ? $extact : "",
            "arrayOnprogressPartialMark"      => (isset($arrayOnprogressPartialMark) && sizeof($arrayOnprogressPartialMark) > 0) ? $arrayOnprogressPartialMark : array(),
            "arrayOnprogressGroupPartialMark" => (isset($arrayOnprogressGroupPartialMark) && sizeof($arrayOnprogressGroupPartialMark) > 0) ? $arrayOnprogressGroupPartialMark : array(),
            "defaultItemTrgEditable"          => isset($defaultItemTrgEditable) ? $defaultItemTrgEditable : array(),
            "editItemTrg"                     => MODUL_PATH . "_followupLiveEdit/editEfaktur/" . $jenisTr . "/",
            "link_scan_mobile"                => ($this->session->login["employee_type"] == "employee_kirim") ? MODUL_PATH . "FollowUp/followupScanMobile/$jenisTr" : NULL,
            "link_undoneList_kurir"           => $link_undoneList_kurir,
        );
    }
}