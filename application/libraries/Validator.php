<?php

/**
 * Created by JetBrains PhpStorm.
 * User: azes
 * Date: 5/9/12
 * Time: 11:56 AM
 * To change this template use File | Settings | File Templates.
 */


class Validator
{
    protected $configUiJenis;
    // protected $configLayout;
    protected $configCoreJenis;
    protected $cCode;
    protected $sessionData;
    protected $jenisTr;
    protected $stepNum;


    public function getStepNum()
    {
        return $this->stepNum;
    }

    public function setStepNum($stepNum)
    {
        $this->stepNum = $stepNum;
    }

    public function getJenisTr()
    {
        return $this->jenisTr;
    }

    public function setJenisTr($jenisTr)
    {
        $this->jenisTr = $jenisTr;
    }

    public function getSessionData()
    {
        return $this->sessionData;
    }

    public function setSessionData($sessionData)
    {
        $this->sessionData = $sessionData;
    }

    public function getConfigUiJenis()
    {
        return $this->configUiJenis;
    }

    public function setConfigUiJenis($configUiJenis)
    {
        $this->configUiJenis = $configUiJenis;
    }

    public function getConfigCoreJenis()
    {
        return $this->configCoreJenis;
    }

    public function setConfigCoreJenis($configCoreJenis)
    {
        $this->configCoreJenis = $configCoreJenis;
    }

    public function getCCode()
    {
        return $this->cCode;
    }

    public function setCCode($cCode)
    {
        $this->cCode = $cCode;
    }

    public function __construct()
    {
        // parent::__construct();
        $this->CI =& get_instance();

    }

    //---------------------
    public function midValidate($step)
    {
        $cCode = isset($this->cCode) ? $this->cCode : matiHere("cCode " . __METHOD__ . " silahkan diset");
        // $step = $_SESSION[$cCode]['main']['step_number'];

        $fieldMidValidatorRules = isset($this->configUiJenis["shoppingCartFieldMidValidators"]) ? $this->configUiJenis["shoppingCartFieldMidValidators"] : array();
        $fieldMidPairedItemValidatorRules = isset($this->configUiJenis["shoppingCartFieldMidValidatorsPairedItem"]) ? $this->configUiJenis["shoppingCartFieldMidValidatorsPairedItem"] : array();
        $rowMidValidatorRules = isset($this->configUiJenis["shoppingCartRowMidValidators"]) ? $this->configUiJenis["shoppingCartRowMidValidators"] : array();
        $rowMidValidatorItemRules = isset($this->configUiJenis["shoppingCartRowMidItemValidators"]) ? $this->configUiJenis["shoppingCartRowMidItemValidators"] : array();
        $fieldMidComparisonValidatorRules = isset($this->configUiJenis["shoppingCartFieldMidValidatorsComparison"]) ? $this->configUiJenis["shoppingCartFieldMidValidatorsComparison"] : array();
        $elementConfigs = isset($this->configUiJenis['receiptElements']) ? $this->configUiJenis['receiptElements'] : array();
        $relElementConfigs = isset($this->configUiJenis['relativeElements']) ? $this->configUiJenis['relativeElements'] : array();
        $efakturValidatorConfig = isset($this->configUiJenis['efakturValidator'][$step]) ? $this->configUiJenis['efakturValidator'][$step] : array();
        $followupMainNoteValidatorConfig = isset($this->configUiJenis['followupMainNoteValidator'][$step]) ? $this->configUiJenis['followupMainNoteValidator'][$step] : array();
        $paidValidatorConfig = isset($this->configUiJenis['shoppingCartPaidValidators']) ? $this->configUiJenis['shoppingCartPaidValidators'] : array();
        $kekuranganValidate = isset($this->configUiJenis['shoppingCartKekuranganValidate']) ? $this->configUiJenis['shoppingCartKekuranganValidate'] : array();
        $fieldMidComparisonValidatorByStepRules = isset($this->configUiJenis["shoppingCartRowValidatorsByStepComparison"][$step]) ? $this->configUiJenis["shoppingCartRowValidatorsByStepComparison"][$step] : array();
        $validateClosing = isset($this->configUiJenis['validateClosing'][$step]) ? $this->configUiJenis['validateClosing'][$step] : array();
        $validateClosingKey = isset($this->configUiJenis['validateClosingKey'][$step]) ? $this->configUiJenis['validateClosingKey'][$step] : array();
        $validateClosingExtractedSubItems = isset($this->configUiJenis['validateClosingExtractedSubItems'][$step]) ? $this->configUiJenis['validateClosingExtractedSubItems'][$step] : array();
        //------------------
        $itemPriceValidator = isset($this->configUiJenis["itemPriceValidator"][$step]) ? $this->configUiJenis["itemPriceValidator"][$step] : array();
        //------------------
        $pairedItemBreakDownValidator = isset($this->configUiJenis["shoppingCartPairedItemBreakDownValidator"][$step]) ? $this->configUiJenis["shoppingCartPairedItemBreakDownValidator"][$step] : array();
        //-------------------------
        $serialNumberValidator = isset($this->configUiJenis["serialNumberValidator"][$step]) ? $this->configUiJenis["serialNumberValidator"][$step] : array();
        //-------------------------
        $selectorValidator = isset($this->configUiJenis["selectorValidator"][$step]) ? $this->configUiJenis["selectorValidator"][$step] : array();
        $ppnPersenCheckValidate = isset($this->configUiJenis["ppnPersenCheckValidate"][$step]) ? $this->configUiJenis["ppnPersenCheckValidate"][$step] : array();

//        mati_disini(sizeof($ppnPersenCheckValidate) . " --- " . $this->jenisTr);

        $rawPrevURL = isset($_GET['rawPrev']) ? $_GET['rawPrev'] : "";

        $errMsgs = array();
        $errLines = array();
        $errFields = array();
        $errRows = array();
        if (sizeof($rowMidValidatorRules) > 0) {

            foreach ($rowMidValidatorRules as $field => $label) {
                if (!isset($_SESSION[$cCode]['main'][$field])) {
                    $errMsgs[] = "$label is required";

                    $errRows[] = $field;
                }
            }
        }

        //----------------------------
        if (sizeof($rowMidValidatorItemRules) > 0) {

            foreach ($rowMidValidatorItemRules as $field => $label) {

                if (isset($_SESSION[$cCode]['items'])) {
                    foreach ($_SESSION[$cCode]['items'] as $iSpec) {

                        if (!isset($iSpec[$field]) || ($iSpec[$field] == 0)) {
                            $nama = $iSpec['nama'];
                            $errMsgs[] = "$label $nama is required";
                            $errRows[] = $field;
                        }
                    }
                }
            }
        }


        if (sizeof($fieldMidPairedItemValidatorRules) > 0) {
            $result = array();
            foreach ($fieldMidPairedItemValidatorRules as $field => $label) {
                if (!isset($_SESSION[$cCode]['main'][$field])) {
                    $errMsgs[] = "$field value is required";
                    $errRows[] = $field;
                }
                $result[$field] = isset($_SESSION[$cCode]['main'][$field]) ? $_SESSION[$cCode]['main'][$field] : 0;
            }

            if (($result['hpp_sumber'] - $result['hpp_target']) > 0.0000000100) {
                $selisih = $result['hpp_sumber'] - $result['hpp_target'];

                $errMsgs[] = "Nilai konversi tidak sama, silahkan cek harga per-unitnya.<br>$selisih";
                $errRows[] = "test";
            }
            elseif (($result['hpp_sumber'] - $result['hpp_target']) < -0.0000000100) {
                $selisih = $result['hpp_sumber'] - $result['hpp_target'];

                $errMsgs[] = "Nilai konversi tidak sama, silahkan cek harga per-unitnya.<br>$selisih";
                $errRows[] = "test";
            }
        }

        if (sizeof($fieldMidValidatorRules) > 0) {

            if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
                foreach ($_SESSION[$cCode]['items'] as $xid => $iSpec) {
                    $id = $iSpec['id'];
                    if ((isset($iSpec['disabled']) && $iSpec['disabled'] == "0") || !isset($iSpec['disabled'])) {
                        if (!isset($errFields[$id])) {
                            $errFields[$id] = array();
                        }
                        foreach ($fieldMidValidatorRules as $field => $label) {
                            if (!isset($iSpec[$field])) {
                                $errMsgs[] = "item $label is required";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                            if (!is_numeric($iSpec[$field])) {
                                $errMsgs[] = "item $label must be a valid number";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                            if ($iSpec[$field] < 0.5) {
                                $errMsgs[] = "item $label must be > 0";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                        }
                    }

                }
            }
            if (isset($_SESSION[$cCode]['rsltItems']) && sizeof($_SESSION[$cCode]['rsltItems']) > 0) {
                foreach ($_SESSION[$cCode]['rsltItems'] as $xid => $iSpec) {
                    $id = $iSpec['id'];
                    $name = $iSpec['nama'];
                    if ((isset($iSpec['disabled']) && $iSpec['disabled'] == "0") || !isset($iSpec['disabled'])) {
                        if (!isset($errFields[$id])) {
                            $errFields[$id] = array();
                        }
                        foreach ($fieldMidValidatorRules as $field => $label) {
                            if (!isset($iSpec[$field])) {
                                $errMsgs[] = "item $label $name is required";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                            if (!is_numeric($iSpec[$field])) {
                                $errMsgs[] = "item $label $name must be a valid number";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                            if ($iSpec[$field] < 0.5) {
                                $errMsgs[] = "item $label $name must be > 0";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                        }
                    }

                }
            }

        }

        if (sizeof($fieldMidComparisonValidatorRules) > 0) {
            $result = array();
            $labels = array();
            foreach ($fieldMidComparisonValidatorRules as $field => $label) {
                if (!isset($_SESSION[$cCode]['main'][$field])) {
                    $errMsgs[] = "$field value is required";
                    $errRows[] = $field;
                }
                if ($_SESSION[$cCode]['main'][$field] < 0) {
                    $errMsgs[] = "$field must be >= 0";
                    $errRows[] = $field;
                }
                $labels[$label] = $field;
                $result[$label] = isset($_SESSION[$cCode]['main'][$field]) ? $_SESSION[$cCode]['main'][$field] : 0;
            }

            if ($result["sumber"] > $result["target"]) {
                $labelSrc = isset($labels["sumber"]) ? $labels["sumber"] : "";
                $labelTarget = isset($labels["target"]) ? $labels["target"] : "";
                $errMsgs[] = "Nilai $labelSrc lebih besar dari nilai $labelTarget.";
                $errRows[] = "test";
            }

        }
        //------------------------
//        cekKuning($fieldMidComparisonValidatorByStepRules);
        cekHitam(__LINE__);
        if (sizeof($fieldMidComparisonValidatorByStepRules) > 0) {
            $result = array();
            $labels = array();
            foreach ($fieldMidComparisonValidatorByStepRules as $field => $fSpec) {
                if (!isset($_SESSION[$cCode]['main'][$fSpec['key']])) {
                    $errMsgs[] = $fSpec['label'] . " value is required";
//                    $errRows[] = $field;
                }
                if ($_SESSION[$cCode]['main'][$fSpec['key']] < 0) {
                    $errMsgs[] = $fSpec['label'] . " must be >= 0";
//                    $errRows[] = $field;
                }
                $labels[$field] = $fSpec['label'];
                $result[$field] = isset($_SESSION[$cCode]['main'][$fSpec['key']]) ? $_SESSION[$cCode]['main'][$fSpec['key']] : 0;
            }

            if ($result["source"] > $result["target"]) {
                $labelSrc = isset($labels["source"]) ? $labels["source"] : "";
                $labelTarget = isset($labels["target"]) ? $labels["target"] : "";
                $errMsgs[] = "Nilai $labelSrc lebih besar dari nilai $labelTarget. Silahkan diperiksa kembali.";

            }

        }
        //------------------------
//arrPrint($errMsgs);
//mati_disini();

        if (sizeof($elementConfigs) > 0) {
            $hiddenMsg = "<br><span style='color:white;font-size:15px;'>" . get_class($this) . "/" . __FUNCTION__ . "</span>";
            foreach ($elementConfigs as $eName => $aSpec) {
                if (!isset($_SESSION[$cCode]['main_elements'][$eName])) {
                    if ((isset($aSpec['noValidate'])) && ($aSpec['noValidate'] == true)) {

                    }
                    else {
                        if (isset($aSpec['labelValidate'])) {
                            $elementMsg = $aSpec['labelValidate'];
                        }
                        else {
                            $elementMsg = ($aSpec['label'] . " harus ditentukan. Silahkan hubungi Admin.");
                        }
                        $errMsgs[] = $elementMsg;
                        echo "<script>";
                        echo "top.document.getElementById('elTitle_$eName').className='box-headers text-red text-left';";
                        echo "</script>";
                    }
                }
                else {
                    switch ($aSpec['elementType']) {
                        case "dataModel":
                            if (isset($validateReceiveElement[$eName]) && sizeof($validateReceiveElement[$eName])) {
                                $valid = 0;
                                $check = array();
                                foreach ($validateReceiveElement[$eName] as $tLabel => $textWarning) {
                                    if (strlen($_SESSION[$cCode]['main'][$eName . '__' . $tLabel]) > 10) {
                                    }
                                    else {
                                        $check[] = $tLabel;
                                    }
                                }
                                if (sizeof($check) > 1) {
                                    $errMsgs[] = ("Harap lengkapi salah satu NPWP/KTP diBillingDetails customer");
                                    foreach ($check as $nLabel) {
                                        $errMsgs[] = ("lengkapi $nLabel!");
                                    }
                                }
                            }


                            if (strlen($_SESSION[$cCode]['main_elements'][$eName]['key']) < 0.5) {
                                $errMsgs[] = ("element " . $aSpec['label'] . " must be filled with one entry!");
                                echo "<script>";
                                //                                echo "top.document.getElementById('divel_$eName').style.backgroundColor='#ffff00';";
                                echo "if(top.document.getElementById('elTitle_$eName')){top.document.getElementById('elTitle_$eName').className='box-headers text-red text-left';}";
                                echo "</script>";
                            }
                            else {

                                if ((isset($aSpec['noValidate'])) && ($aSpec['noValidate'] == true)) {

                                }
                                else {
                                echo "<script>";
                                    echo "if(top.document.getElementById('elTitle_$eName')){top.document.getElementById('elTitle_$eName').className='box-headers bg-grey text-left';}";
                                echo "</script>";
                            }

                                // matiHEre(__FUNCTION__." $eName LINE ".__LINE__);
                            }
                            break;
                        case "dataField":
                            if (strlen($_SESSION[$cCode]['main_elements'][$eName]['value']) < 0.5) {
                                if ((isset($aSpec['noValidate'])) && ($aSpec['noValidate'] == true)) {
                                }
                                else {
                                    $errMsgs[] = ($aSpec['label'] . " must be filled with one entry!");
                                    echo "<script>";
                                    //                                echo "top.document.getElementById('divel_$eName').style.backgroundColor='#ffff00';";
                                    echo "if(top.document.getElementById('elTitle_$eName')){top.document.getElementById('elTitle_$eName').className='box-headers text-red text-left';}";
                                    echo "</script>";
                                }

                            }
                            else {
                                echo "<script>";
                                echo "if(top.document.getElementById('elTitle_$eName')){top.document.getElementById('elTitle_$eName').className='box-headers bg-grey text-left';}";
                                echo "</script>";
                            }
                            break;
                    }

                }
            }
            if (isset($_SESSION[$cCode]['main_elements'])) {

                foreach ($_SESSION[$cCode]['main_elements'] as $elName => $elSpec) {
                    $elementType = isset($elSpec['elementType']) ? $elSpec['elementType'] : NULL;
                    switch ($elementType) {
                        case "dataField":

                            if (isset($elSpec['value'])) {
                                // arrPrint($elSpec['value']);

//                                if (sizeof(blobDecode($elSpec['value'])) == 0) {
                                if (($elSpec['value'] == 0) || ($elSpec['value'] == "")){
                                    $errMsgs[] = ("Baris " . $elSpec['label'] . " belum dipilih. Silahkan dipilih dahulu. (code " . __LINE__ . ") $hiddenMsg");
                                }
                            }
                            else {
                                $errMsgs[] = ("Baris " . $elSpec['label'] . " belum dipilih. Silahkan dipilih dahulu. (code " . __LINE__ . ") $hiddenMsg");
                            }
                            break;

                        case "dataModel":

                            if (isset($elSpec['contents'])) {
                                if (sizeof(blobDecode($elSpec['contents'])) == 0) {
                                    $errMsgs[] = ("Baris " . $elSpec['label'] . " belum dipilih. Silahkan dipilih dahulu. (code " . __LINE__ . ") $hiddenMsg");
                                }
                            }
                            else {
                                $errMsgs[] = ("Baris " . $elSpec['label'] . " belum dipilih. Silahkan dipilih dahulu. (code " . __LINE__ . ") $hiddenMsg");
                            }
                            break;
                    }


                }
            }
            else {
                cekBiru("main_element tidak ada di session @" . __LINE__ . " " . __FILE__);
            }
        }

        if (sizeof($efakturValidatorConfig) > 0) {
            $enabled = isset($efakturValidatorConfig['enabled']) ? $efakturValidatorConfig['enabled'] : false;
            if ($enabled == true) {
                $kolom = isset($efakturValidatorConfig['kolom']) ? $efakturValidatorConfig['kolom'] : array();
                $source = isset($efakturValidatorConfig['source']) ? $efakturValidatorConfig['source'] : array();
                if (sizeof($kolom) > 0) {
                    foreach ($source as $vSrc) {
                        if ($_SESSION[$cCode]['main'][$vSrc] > 0) {

                            foreach ($kolom as $key => $label) {
                                if (!isset($_SESSION[$cCode]['main'][$key])) {
                                    $errMsgs[] = $label;
                                }
                            }
                        }
                    }
                }
            }
        }

        if (sizeof($followupMainNoteValidatorConfig) > 0) {
            $enabled = isset($followupMainNoteValidatorConfig['enabled']) ? $followupMainNoteValidatorConfig['enabled'] : false;
            if ($enabled == true) {
                $kolom = isset($followupMainNoteValidatorConfig['kolom']) ? $followupMainNoteValidatorConfig['kolom'] : array();
                $source = isset($followupMainNoteValidatorConfig['source']) ? $followupMainNoteValidatorConfig['source'] : array();
                if (sizeof($kolom) > 0) {
                    foreach ($source as $vSrc) {
                        foreach ($kolom as $key => $label) {
                            if (!isset($_SESSION[$cCode]['main'][$key])) {
                                $errMsgs[] = $label;
                            }
                            else {
                                if (strlen($_SESSION[$cCode]['main'][$key]) < 3) {
                                    $errMsgs[] = $label;
                                }
                            }
                        }
                    }
                }
            }
        }

        if (sizeof($paidValidatorConfig) > 0) {
            foreach ($paidValidatorConfig as $pKey => $pVal) {
                if ($_SESSION[$cCode]['main'][$pKey] <= 0) {
                    $errMsgs[] = $pVal;
                }
            }
        }
        //--------------
        if (sizeof($kekuranganValidate) > 0) {
            foreach ($kekuranganValidate as $key => $val) {
                switch ($key) {
                    case "source":
                        if (isset($_SESSION[$cCode]['items9_sum'])) {
                            foreach ($_SESSION[$cCode]['items9_sum'] as $bSpec) {
                                if ($bSpec['qty_kekurangan'] > 0) {
                                    $bName = htmlspecialchars($bSpec['nama_bahan']);
                                    $qty = $bSpec['qty_kekurangan'];
                                    $satuan = $bSpec['satuan'];
                                    $msg = "stok $bName kekurangan $qty $satuan.";
                                    $errMsgs[] = $msg;
                                }

                            }
                        }
                        break;
                    default:
                        break;
                }
            }
        }
        //--------------
        if (sizeof($validateClosing) > 0) {
            if ($_SESSION[$cCode]['main']['garansi_nilai'] > 0) {
                foreach ($validateClosing as $key => $value) {
                    if (!array_key_exists($key, $_SESSION[$cCode]['main'])) {
                        $msg = $value;
                        $errMsgs[] = $msg;
                    }
                }
            }
        }
        // $validateClosingExtractedSubItems
        if (sizeof($validateClosingExtractedSubItems) > 0) {
            foreach ($validateClosingExtractedSubItems as $key => $value) {
                if (!array_key_exists($key, $_SESSION[$cCode]['main'])) {
                    $msg = $value;
                    $errMsgs[] = $msg;
                }
                elseif (isset($_SESSION[$cCode]['main'][$key]) && ($_SESSION[$cCode]['main'][$key] == 0)) {
                    $msg = $value;
                    $errMsgs[] = $msg;
                }
            }
        }
        //--------------
        if (isset($itemPriceValidator["enabled"]) && ($itemPriceValidator["enabled"] == true)) {

            // hpp, gerbang items
            // harga jual lama, gerbang items
            // harga jual baru, gerbang items4_sum
            if (isset($_SESSION[$cCode]['items'])) {
                $updateHarga = array();
                if (isset($_SESSION[$cCode]['items4_sum']) && (sizeof($_SESSION[$cCode]['items4_sum']) > 0)) {
                    foreach ($_SESSION[$cCode]['items4_sum'] as $pID => $iSpec) {
                        $updateHarga[$pID] = array(
                            "jual_baru" => isset($iSpec["jual_baru"]) ? $iSpec["jual_baru"] : 0,
                            "hpp_nppv" => isset($iSpec["hpp_nppv"]) ? $iSpec["hpp_nppv"] : 0,
                        );
                    }
                }
//                arrPrintWebs($updateHarga);
//                cekMerah(__LINE__);
                foreach ($_SESSION[$cCode]['items'] as $pID => $iSpec) {
                    $nama = isset($iSpec['nama']) ? $iSpec['nama'] : 0;
                    $hpp = isset($iSpec['hpp']) ? $iSpec['hpp'] : 0;
                    $harga_list = isset($iSpec['jual']) ? $iSpec['jual'] : 0;
                    $harga_jual_baru = isset($updateHarga[$pID]['jual_baru']) ? $updateHarga[$pID]['jual_baru'] : 0;
                    $harga_tandas = isset($updateHarga[$pID]['hpp_nppv']) ? $updateHarga[$pID]['hpp_nppv'] : 0;

                    // ADA SETTING HARGA JUAL BARU
                    if (isset($updateHarga[$pID])) {
                        //harga beli 10.000 > harga jual baru 1.000, tolak
                        if (($hpp > $harga_jual_baru)) {
                            $msg = "Harga jual baru $nama lebih kecil dari harga beli. Silahkan dikoreksi di halaman ini. Code: " . __LINE__;
                            mati_disini($msg);
                        }

                        if ($harga_tandas < 1) {
                            $msg = "Harga tandas $nama belum diisikan. Silahkan dikoreksi di halaman ini. Code: " . __LINE__;
                            mati_disini($msg);
                        }
                    }
                    // TIDAK ADA SETTING HARGA JUAL BARU
                    else {
                        //harga beli 10.000 > harga jual 1.000, tolak
                        if (($hpp > $harga_list)) {
                            $msg = "Harga jual lama $nama lebih kecil dari harga beli. Silahkan dikoreksi dengan mengisi harga jual baru di halaman ini. Code: " . __LINE__;
                            mati_disini($msg);
                        }
                        //harga jual lama (0), harga jual baru (0), tolak
                        elseif (($harga_jual_baru == 0) && ($harga_list == 0)) {
                            $msg = "Harga jual $nama belum diisikan. Silahkan diisikan dengan mengisi harga jual baru di halaman ini. Code: " . __LINE__;
                            mati_disini($msg);
                        }

                        if ($harga_tandas < 1) {
                            $msg = "Harga tandas $nama belum diisikan. Silahkan dikoreksi di halaman ini. Code: " . __LINE__;
                            mati_disini($msg);
                        }
                    }


                }
            }
        }
        // konversi breakdown
        if (sizeof($pairedItemBreakDownValidator) > 0) {
            if (isset($pairedItemBreakDownValidator["enabled"]) && ($pairedItemBreakDownValidator["enabled"] == true)) {
                if (isset($_SESSION[$cCode]["items"]) && sizeof($_SESSION[$cCode]['items']) > 0) {
                    foreach ($_SESSION[$cCode]["items"] as $id => $spec) {
                        $produk_nama_items = $spec["nama"];
                        $sub_hpp_avg_items = $spec["sub_hpp_avg"];
                        $sub_hpp_avg_items4 = 0;
                        if (isset($_SESSION[$cCode]["items4"][$id])) {
                            foreach ($_SESSION[$cCode]["items4"][$id] as $sid => $iiSpec) {
                                $sub_hpp_avg_items4 += isset($iiSpec["sub_hpp_avg"]) ? $iiSpec["sub_hpp_avg"] : 0;
                            }
                        }
                        $selisih = $sub_hpp_avg_items4 - $sub_hpp_avg_items;
                        //            $selisih = ($selisih < 0) ? ($selisih *-1) : $selisih;
                        if ($selisih > 1) {
                            $msg = "akumulasi hpp hasil konversi produk $produk_nama_items melebihi hpp rata-rata. silahkan diperiksa lagi. code: " . __LINE__;
//                            $msg .= "[$sub_hpp_avg_items4] [$sub_hpp_avg_items]";
                            mati_disini($msg);
                        }
                    }
                }
            }
        }
        //--------------
        if (sizeof($serialNumberValidator) > 0) {
            if (isset($serialNumberValidator["enabled"]) && ($serialNumberValidator["enabled"] == true)) {
                cekHitam("MASUK VALIDASI PRODUK YANG DIDEFINE SERIAL");
                if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
                    $gateSource = $serialNumberValidator["source"];
                    $labelSource = $serialNumberValidator["label"];
                    foreach ($_SESSION[$cCode]['items'] as $xid => $iSpec) {
                        $id = $iSpec['id'];
                        $p_nama = $iSpec['nama'];
                        $jml_serial = $iSpec['jml_serial'] * $iSpec["jml"];
                        if ($jml_serial > 0) {
                            $jml_serial_items2 = count($_SESSION[$cCode][$gateSource][$id]);
                            $jml_serial_items2_qty = $jml_serial_items2 * $iSpec["jml"];
                            $serial_produk = 0;
                            if (isset($_SESSION[$cCode][$gateSource][$id]) && (sizeof($_SESSION[$cCode][$gateSource][$id]) > 0)) {
                                foreach ($_SESSION[$cCode][$gateSource][$id] as $sku => $spec) {
                                    $jml_sku_serial = count($spec);
                                    $serial_produk += $jml_sku_serial;
//                                    cekHere(":: $jml_sku_serial :: $serial_produk ::");
                                }
                            }
                            if ($serial_produk != $jml_serial_items2_qty) {
                                $labelSource = str_replace("{produk_nama}", "$p_nama", $labelSource);
//                                cekHere($labelSource);
                                $errMsgs[] = $labelSource;
//                                $errMsgs[] = $labelSource . " :: $serial_produk != $jml_serial_items2_qty";
                            }

                        }
                    }
                }
            }
        }
        //----------------------
        $pakai_ini = 0;
        if ($pakai_ini == 1) {
            if (sizeof($selectorValidator) > 0) {
                $selKosong = array();
                foreach ($selectorValidator as $ii => $spec) {
                    if (isset($spec["reference"])) {
                        $reference = isset($_SESSION[$cCode]["main"][$spec["reference"]]) ? $_SESSION[$cCode]["main"][$spec["reference"]] : 0;
                        if ($reference > 0) {
                            foreach ($spec["keys"] as $key) {
                                if (!isset($_SESSION[$cCode]['main'][$key])) {
                                    $selKosong[$ii] = $key;
                                }
                                elseif (isset($_SESSION[$cCode]['main'][$key]) && ($_SESSION[$cCode]['main'][$key] == null)) {
                                    $selKosong[$ii] = $key;
                                }
                            }
                        }
                    }
                    else {
                        foreach ($spec["keys"] as $key) {
                            if (!isset($_SESSION[$cCode]['main'][$key])) {
                                $selKosong[$ii] = $key;
                            }
                            elseif (isset($_SESSION[$cCode]['main'][$key]) && ($_SESSION[$cCode]['main'][$key] == null)) {
                                $selKosong[$ii] = $key;
                            }
                        }
                    }
                }
                if (sizeof($selKosong) > 0) {
                    foreach ($selKosong as $iii => $x) {
                        $errMsgs[] = $selectorValidator[$iii]["label"];
                    }
                }
            }
        }
        //----------------------
        if (sizeof($ppnPersenCheckValidate) > 0) {
            if (isset($ppnPersenCheckValidate["enabled"]) && ($ppnPersenCheckValidate["enabled"] == true)) {
                $key_cek = $ppnPersenCheckValidate["key"];
                $sessionCek = $ppnPersenCheckValidate["sessionCek"];
                $result_key_cek = (isset($_SESSION[$cCode]["main"][$key_cek]) && ($_SESSION[$cCode]["main"][$key_cek] > 0)) ? $_SESSION[$cCode]["main"][$key_cek] : 0;
                $result_sessionCek = (isset($_SESSION[$cCode]["main"][$sessionCek]) && ($_SESSION[$cCode]["main"][$sessionCek] > 0)) ? $_SESSION[$cCode]["main"][$sessionCek] : 0;
                if ($result_key_cek == 0) {
                    // tanpa ppn
                    if ($result_sessionCek > 0) {
                        // warning
                        $label_warning = $ppnPersenCheckValidate["labelWarning"][0];
                        $errMsgs[] = $label_warning;
                    }
                }
                else {
                    // dengan ppn
                    if ($result_sessionCek <= 0) {
                        // warning
                        $label_warning = $ppnPersenCheckValidate["labelWarning"][1];
                        $errMsgs[] = $label_warning;
                    }
                }
            }
        }
        //----------------------
        if (isset($this->configUi[$this->jenisTr]["cabangValidator"]["enabled"]) && ($this->configUi[$this->jenisTr]["cabangValidator"]["enabled"] == true)) {
//            if ($_SESSION[$cCode]["tableIn_master"]["cabang_id"] == CB_ID_PUSAT) {
//                $label_warning = $this->configUi[$this->jenisTr]["cabangValidator"]["label"];
//                mati_disini($label_warning);
//            }
//            elseif ($_SESSION[$cCode]["main"]["placeID"] == CB_ID_PUSAT) {
//                $label_warning = $this->configUi[$this->jenisTr]["cabangValidator"]["label"];
//                mati_disini($label_warning);
//            }
            $label_warning = "Transaksi tidak terdaftar di DC/Pusat atau di Cabang. Silahkan periksa transaksi anda.";
            if ($_SESSION[$cCode]["tableIn_master"]["cabang_id"] == NULL) {
                mati_disini($label_warning);
            }
            elseif ($_SESSION[$cCode]["main"]["placeID"] == NULL) {
                mati_disini($label_warning);
            }
            elseif ($_SESSION[$cCode]["tableIn_master"]["cabang_id"] == 0) {
                mati_disini($label_warning);
            }
            elseif ($_SESSION[$cCode]["main"]["placeID"] == 0) {
                mati_disini($label_warning);
            }
        }
        //----------------------


        if (sizeof($errMsgs) > 0) {
            $_SESSION['errMsg'] = implode("<br>", $errMsgs);

            if (sizeof($errLines) > 0) {
                $_SESSION['errLines'] = $errLines;
            }
            if (sizeof($errFields) > 0) {
                $_SESSION['errFields'] = $errFields;
            }
            arrPrintKuning($_SESSION['errMsg']);
            cekMerah(__LINE__);
            echo lgShowAlert($_SESSION['errMsg']);
            die();
        }

    }

    public function unionValidate()
    {
        $cCode = isset($this->cCode) ? $this->cCode : matiHere("cCode " . __METHOD__ . " silahkan diset");
        $unionValidatorRules = isset($this->configUiJenis["shoppingCartUnionValidators"]) ? $this->configUiJenis["shoppingCartUnionValidators"] : array();


        $rawPrevURL = isset($_GET['rawPrev']) ? $_GET['rawPrev'] : "";
        //matiHEre();

        $errMsgs = array();
        $errLines = array();
        $errFields = array();
        $errRows = array();


        if (sizeof($unionValidatorRules) > 0) {
            $result = array();
            $validateFields = 0;
            foreach ($unionValidatorRules as $uSpec) {

                foreach ($uSpec as $field => $label) {
                    $validateFields++;
                    if (!isset($_SESSION[$cCode]['main'][$field])) {
                        $result[$field] = "$label value is required";
                    }
                }
                //                $result[$field] = isset($_SESSION[$cCode]['main'][$field]) ? $_SESSION[$cCode]['main'][$field] : 0;
            }
            //            cekHitam(sizeof($result));
            //            matiHEre($validateFields);
            if (sizeof($result) == $validateFields) {
                foreach ($result as $field => $label) {
                    $errMsgs[] = $label;
                    $errRows[] = $field;
                }
            }

        }

        //        matiHere(sizeof($result) ." ==". $validateFields);

        if (sizeof($errMsgs) > 0) {
            $_SESSION['errMsg'] = implode("<br>", $errMsgs);
            //            print_r($_SESSION['errMsg']);
            //            echo "<script>";
            //            echo "top.getData('" . base_url() . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id','shopping_cart');";
            //            echo "</script>";
            if (sizeof($errLines) > 0) {
                $_SESSION['errLines'] = $errLines;
            }
            if (sizeof($errFields) > 0) {
                $_SESSION['errFields'] = $errFields;
            }

            echo lgShowAlert($_SESSION['errMsg']);
            die();
        }
        //        else {
        //
        //            $actionTarget = "top.BootstrapDialog.show(                                   {
        //                                       title:'preview',
        //                                        message: " . '$' . "('<div></div>').load('" . base_url() . "Transaksi/preview/" . $this->jenisTr . "?rawPrev=$rawPrevURL'),
        //                                        draggable:false,
        //                                        size:top.BootstrapDialog.SIZE_WIDE,
        //                                        type:top.BootstrapDialog.TYPE_SUCCESS,
        //                                        closable:true,
        //                                        }
        //                                        );";
        //
        //            echo "<html>";
        //            echo "<head>";
        //            echo "<script src=\"" . cdn_suport()."AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
        //            echo "</head>";
        //            echo "<body onload=\"$actionTarget\">";
        //            echo "</body>";
        //
        //        }
    }

    public function validate2()
    {
        $cCode = isset($this->cCode) ? $this->cCode : matiHere("cCode " . __METHOD__ . " silahkan diset");
        $validationRules = isset($this->configUiJenis['validationRules']) ? $this->configUiJenis['validationRules'] : array();
        $pairedItemBreakDownValidator = isset($this->configUiJenis["shoppingCartPairedItemBreakDownValidator"]) ? $this->configUiJenis["shoppingCartPairedItemBreakDownValidator"] : array();
        //----------------------
        $msgWarning = array();
        if (sizeof($validationRules) > 0) {
            foreach ($validationRules as $gateName => $gSpec) {
                if (isset($_SESSION[$cCode][$gateName])) {
                    foreach ($_SESSION[$cCode][$gateName] as $eKey => $eSpec) {
                        if (isset($gSpec['target']) && isset($eSpec[$gSpec['target']]) && $eSpec[$gSpec['target']] > 0) {
                            if ($eSpec[$gSpec['source']] > $eSpec[$gSpec['target']]) {
                                $msg = $gSpec['target'] . " " . $eSpec['nama'] . " tidak cukup.";
                                $msgWarning[$eSpec['id']] = array(
                                    "id" => "trItems_" . $eSpec['id'],
                                    "label" => $msg,
                                );
                            }
                        }
                        else {
                            $msg = $gSpec['target'] . " " . $eSpec['nama'] . " tidak cukup.";
                            $msgWarning[$eSpec['id']] = array(
                                "id" => "trItems_" . $eSpec['id'],
                                "label" => $msg,
                            );
                        }
                    }
                }
            }
        }
        //----------------------
        if (sizeof($pairedItemBreakDownValidator) > 0) {
            if (isset($pairedItemBreakDownValidator[$this->stepNum]["enabled"]) && ($pairedItemBreakDownValidator[$this->stepNum]["enabled"] == true)) {
                if (isset($_SESSION[$cCode]["items"]) && sizeof($_SESSION[$cCode]['items']) > 0) {
                    foreach ($_SESSION[$cCode]["items"] as $id => $spec) {
                        $produk_nama_items = $spec["nama"];
                        $sub_hpp_avg_items = $spec["sub_hpp_avg"];
                        $sub_hpp_avg_items4 = 0;
                        if (isset($_SESSION[$cCode]["items4"][$id])) {
                            foreach ($_SESSION[$cCode]["items4"][$id] as $sid => $iiSpec) {
                                $sub_hpp_avg_items4 += isset($iiSpec["sub_hpp_avg"]) ? $iiSpec["sub_hpp_avg"] : 0;
                            }
                        }
                        $selisih = $sub_hpp_avg_items4 - $sub_hpp_avg_items;
                        $selisih = ($selisih < 0) ? ($selisih * -1) : $selisih;
                        if ($selisih > 1) {
                            $msg = "akumulasi hpp hasil konversi produk $produk_nama_items tidak sama dengan hpp sumber konversi. silahkan diperiksa lagi. code: " . __LINE__;
                            $msgWarning[$id] = array(
                                "id" => "trItems_" . $id,
                                "label" => $msg,
                            );
                        }
                    }
                }
            }
        }

        //----------------------


        return $msgWarning;
    }

    public function lastValidate($trID = "")
    {
        $cCode = isset($this->cCode) ? $this->cCode : matiHere("cCode " . __METHOD__ . " silahkan diset");
        $step = $_SESSION[$cCode]['main']['step_number'];
        $errMsgs = array();


        //region cek request vs ambil fifo
        cekhijau(":: VALIDATE AKHIR request vs postprocc fifo...");
        $postprocc = isset($this->configCore[$this->jenisTr]['postProcessor'][$step]) ? $this->configCore[$this->jenisTr]['postProcessor'][$step] : array();
        $postproccValidate = ($this->CI->config->item('heTransaksi_validatePostProcc') != NULL) ? $this->CI->config->item('heTransaksi_validatePostProcc') : array();
        if (sizeof($postprocc) > 0) {
            if (isset($postprocc['detail'])) {
                if ($postproccValidate['enabled'] == true) {
                    $jenisTrException = $postproccValidate['jenisTrException'];
                    if (!in_array($this->jenisTr, $jenisTrException)) {

                        $arrPostProcc = array();
                        foreach ($postprocc['detail'] as $postSpec) {
                            $arrPostProcc[$postSpec['comName']] = array(
                                "comName" => $postSpec['comName'],
                                "srcGateName" => $postSpec['srcGateName'],
                                //                            "resultParams" => $postSpec['resultParams'],
                            );
                        }
                        $postproccDetail = isset($postproccValidate['postProcc']['detail']) ? $postproccValidate['postProcc']['detail'] : array();
                        if (sizeof($postproccDetail) > 0) {
                            foreach ($postproccDetail as $postName => $postSpec) {
                                if (array_key_exists($postName, $arrPostProcc)) {
                                    $srcGateName = $arrPostProcc[$postName]['srcGateName'];
                                    $model = $postSpec['model'];
                                    $models = "Mdl" . $model;
                                    $arrGateItems = array();
                                    $arrFifoItems = array();
                                    foreach ($_SESSION[$cCode][$srcGateName] as $xid => $xSpec) {
                                        $arrGateItems[$xSpec['id']]['name'] = $xSpec['name'];
                                        if (!isset($arrGateItems[$xSpec['id']]['jml'])) {
                                            $arrGateItems[$xSpec['id']]['jml'] = 0;
                                        }
                                        $arrGateItems[$xSpec['id']]['jml'] += $xSpec['jml'];
                                    }

                                    //region FIFO, detail
                                    $this->load->model("Mdls/" . $models);
                                    $m = New $models();
                                    $m->addFilter("transaksi_id='$trID'");
                                    $mTmp = $m->lookupAll()->result();
                                    //endregion

                                    if (sizeof($mTmp) > 0) {
                                        foreach ($mTmp as $fSpec) {
                                            $arrFifoItems[$fSpec->produk_id]['nama'] = $fSpec->produk_nama;
                                            if (!isset($arrFifoItems[$fSpec->produk_id]['jml'])) {
                                                $arrFifoItems[$fSpec->produk_id]['jml'] = 0;
                                            }
                                            $arrFifoItems[$fSpec->produk_id]['jml'] += $fSpec->unit;
                                        }
                                    }
                                    else {
                                        $msg = "Transaksi gagal, karena gagal eksekusi fifo persediaan. Segera hubungi admin.";
                                        $errMsgs[] = $msg;
                                    }

                                    //--KALKULASI
                                    //                                cekHijau($arrFifoItems);
                                    //                                cekhijau($arrGateItems);
                                    if (count($arrFifoItems) != count($arrGateItems)) {
                                        $msg = "Transaksi gagal, karena total item yang direquest tidak sama dengan total items yang masuk persediaan. Segera hubungi admin.";
                                        $errMsgs[] = $msg;
                                    }
                                    else {
                                        cekHijau(":: fifo: " . count($arrFifoItems) . ", request: " . count($arrGateItems) . " ::");
                                        foreach ($arrGateItems as $pID => $spec) {
                                            $gate_nama = $spec['name'];
                                            $gate_jml = $spec['jml'];
                                            $fifo_jml = isset($arrFifoItems[$pID]['jml']) ? $arrFifoItems[$pID]['jml'] : 0;
                                            if ($gate_jml != $fifo_jml) {
                                                $msg = "$gate_nama, jumlah request $gate_jml, tidak sama dengan jumlah masuk persediaan, jumlah masuk $fifo_jml";
                                                $errMsgs[] = $msg;
                                            }
                                            else {
                                                cekHijau("$gate_nama, cocok [req: $gate_jml] [fifo: $fifo_jml]");
                                            }
                                        }

                                    }
                                }
                            }
                        }
                    }
                }

            }
        }
        //endregion

        cekBiru($errMsgs);
        if (sizeof($errMsgs) > 0) {
            $_SESSION['errMsg'] = implode("<br>", $errMsgs);

            echo lgShowAlert($_SESSION['errMsg']);
            die();
        }


    }

    public function pairSesionValidate($step)
    {
        $fieldItemValueRules = isset($this->configUiJenis["shopingCartValidatePairSession"][$step]) ? $this->configUiJenis["shopingCartValidatePairSession"][$step] : array();
        $fieldValidate = isset($this->configUiJenis["shopingCartValidatePairSession"][$step]["validate"]) ? $this->configUiJenis["shopingCartValidatePairSession"][$step]["validate"] : array();
//        $cCode = "_TR_" . $this->jenisTr;
        $cCode = $this->cCode;

        $this->CI->load->model("Mdls/MdlCabang");
        $c = new MdlCabang();
        $tnpC = $c->lookUpAll()->result();
        $cab = array();
        foreach ($tnpC as $tmp) {
            $cab[$tmp->id] = $tmp->nama;
        }
        $cab[CB_ID_PUSAT] = "PUSAT";

//        arrPrint($fieldItemValueRules);
        if (sizeof($fieldItemValueRules) > 0) {
            $source = $fieldItemValueRules['source'];
            $target = $fieldItemValueRules['target'];
            $sourceData = $_SESSION[$cCode][$source];
            $targetData = $_SESSION[$cCode][$target];
            $fieldsSource = $fieldValidate["source"];
            $fieldstarget = $fieldValidate["target"];
            //            arrPrint($targetData);
            $errMsgs = array();
            foreach ($targetData as $Cid => $tmpData) {
                $tmpError = array();
                foreach ($tmpData as $PID => $tmpVal) {
                    $sourceVal = $sourceData[$PID][$fieldsSource];
                    $targetVal = $tmpVal[$fieldstarget];
                    $minVal = $sourceVal + $sourceVal * 0.025;
                    if ($sourceVal > $targetVal) {
                        //                        $errMsgs[] = "Harga jual ".$sourceData[$PID]['nama']." di ".$cab[$Cid]." lebih rendah dari HPP, silahkan diperbaiki<br>";
                        //                        $tmpError[] = "harga jual ".$sourceData[$PID]['nama']." di ".$cab[$cid]." lebih rendah dari HPP! harga jual harus lebih besar  2,5% dari ".formatfield("nilai",$sourceVal)." ( sebesar ".formatField("nilai",$minVal)." ) harga yang dimasukan saat ini sebesar (".formatField("nilai",$targetVal).") silahkan diperbaiki<br>";
                        $errMsgs[] = "harga jual " . $sourceData[$PID]['nama'] . " di " . $cab[$Cid] . " lebih rendah dari HPP! harga jual harus lebih besar  2,5% dari " . formatfield("nilai", $sourceVal) . " ( sebesar " . formatField("nilai", $minVal) . " ) harga yang dimasukan saat ini sebesar (" . formatField("nilai", $targetVal) . ") silahkan diperbaiki<br>";
                        cekHitam($cab[$Cid] . " " . $sourceData[$PID]['nama']);
                    }

                }
                //                $errMsgs[]=$tmpError;
            }
            //arrPrint($errMsgs);
            //            matiHEre();
            if (sizeof($errMsgs) > 0) {
                $_SESSION['errMsg'] = implode("<br>", $errMsgs);
                //                foreach($errMsgs as $errMsg){
                //                    $_SESSION['errMsg'] = implode("<br>", $errMsg);
                //                    echo lgShowAlert($_SESSION['errMsg']);
                //                }

                //                foreach()
                echo lgShowAlert($_SESSION['errMsg']);
                die();
            }

        }

    }
    //---------------------


    //---------------------
    public function midValidateSessionData($step)
    {

        $sessionData = isset($this->sessionData) ? $this->sessionData : mati_disini("Gagal melakukan validasi data. Data yang akan divalidasi tidak tersedia. Silahkan hubungi admin.");
        $cCode = isset($this->cCode) ? $this->cCode : matiHere("cCode " . __METHOD__ . " silahkan diset");

        $fieldMidValidatorRules = isset($this->configUiJenis["shoppingCartFieldMidValidators"]) ? $this->configUiJenis["shoppingCartFieldMidValidators"] : array();
        $fieldMidPairedItemValidatorRules = isset($this->configUiJenis["shoppingCartFieldMidValidatorsPairedItem"]) ? $this->configUiJenis["shoppingCartFieldMidValidatorsPairedItem"] : array();
        $rowMidValidatorRules = isset($this->configUiJenis["shoppingCartRowMidValidators"]) ? $this->configUiJenis["shoppingCartRowMidValidators"] : array();
        $rowMidValidatorItemRules = isset($this->configUiJenis["shoppingCartRowMidItemValidators"]) ? $this->configUiJenis["shoppingCartRowMidItemValidators"] : array();
        $fieldMidComparisonValidatorRules = isset($this->configUiJenis["shoppingCartFieldMidValidatorsComparison"]) ? $this->configUiJenis["shoppingCartFieldMidValidatorsComparison"] : array();
        $elementConfigs = isset($this->configUiJenis['receiptElements']) ? $this->configUiJenis['receiptElements'] : array();
        $relElementConfigs = isset($this->configUiJenis['relativeElements']) ? $this->configUiJenis['relativeElements'] : array();
        $efakturValidatorConfig = isset($this->configUiJenis['efakturValidator'][$step]) ? $this->configUiJenis['efakturValidator'][$step] : array();
        $followupMainNoteValidatorConfig = isset($this->configUiJenis['followupMainNoteValidator'][$step]) ? $this->configUiJenis['followupMainNoteValidator'][$step] : array();
        $paidValidatorConfig = isset($this->configUiJenis['shoppingCartPaidValidators']) ? $this->configUiJenis['shoppingCartPaidValidators'] : array();
        $kekuranganValidate = isset($this->configUiJenis['shoppingCartKekuranganValidate']) ? $this->configUiJenis['shoppingCartKekuranganValidate'] : array();
        $fieldMidComparisonValidatorByStepRules = isset($this->configUiJenis["shoppingCartRowValidatorsByStepComparison"][$step]) ? $this->configUiJenis["shoppingCartRowValidatorsByStepComparison"][$step] : array();
        //------------------
        $itemPriceValidator = isset($this->configUiJenis["itemPriceValidator"][$step]) ? $this->configUiJenis["itemPriceValidator"][$step] : array();
        //------------------

        $rawPrevURL = isset($_GET['rawPrev']) ? $_GET['rawPrev'] : "";

        $errMsgs = array();
        $errLines = array();
        $errFields = array();
        $errRows = array();
        if (sizeof($rowMidValidatorRules) > 0) {

            foreach ($rowMidValidatorRules as $field => $label) {

                if (!isset($sessionData['main'][$field])) {
                    $errMsgs[] = "$label is required";

                    $errRows[] = $field;
                }
            }
        }

        //----------------------------
        if (sizeof($rowMidValidatorItemRules) > 0) {

            foreach ($rowMidValidatorItemRules as $field => $label) {

                if (isset($sessionData['items'])) {
                    foreach ($sessionData['items'] as $iSpec) {

                        if (!isset($iSpec[$field]) || ($iSpec[$field] == 0)) {
                            $nama = $iSpec['nama'];
                            $errMsgs[] = "$label $nama is required";
                            $errRows[] = $field;
                        }
                    }
                }
            }
        }


        if (sizeof($fieldMidPairedItemValidatorRules) > 0) {
            $result = array();
            foreach ($fieldMidPairedItemValidatorRules as $field => $label) {
                if (!isset($sessionData['main'][$field])) {
                    $errMsgs[] = "$field value is required";
                    $errRows[] = $field;
                }
                $result[$field] = isset($sessionData['main'][$field]) ? $sessionData['main'][$field] : 0;
            }

            if (($result['hpp_sumber'] - $result['hpp_target']) > 0.0000000100) {
                $selisih = $result['hpp_sumber'] - $result['hpp_target'];

                $errMsgs[] = "Nilai konversi tidak sama, silahkan cek harga per-unitnya.<br>$selisih";
                $errRows[] = "test";
            }
            elseif (($result['hpp_sumber'] - $result['hpp_target']) < -0.0000000100) {
                $selisih = $result['hpp_sumber'] - $result['hpp_target'];

                $errMsgs[] = "Nilai konversi tidak sama, silahkan cek harga per-unitnya.<br>$selisih";
                $errRows[] = "test";
            }
        }


        if (sizeof($fieldMidValidatorRules) > 0) {

            if (isset($sessionData['items']) && sizeof($sessionData['items']) > 0) {
                foreach ($sessionData['items'] as $xid => $iSpec) {
                    $id = $iSpec['id'];
                    if ((isset($iSpec['disabled']) && $iSpec['disabled'] == "0") || !isset($iSpec['disabled'])) {
                        if (!isset($errFields[$id])) {
                            $errFields[$id] = array();
                        }
                        foreach ($fieldMidValidatorRules as $field => $label) {
                            if (!isset($iSpec[$field])) {
                                $errMsgs[] = "item $label is required";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                            if (!is_numeric($iSpec[$field])) {
                                $errMsgs[] = "item $label must be a valid number";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                            if ($iSpec[$field] < 0.5) {
                                $errMsgs[] = "item $label must be > 0";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                        }
                    }

                }
            }
            if (isset($sessionData['rsltItems']) && sizeof($sessionData['rsltItems']) > 0) {
                foreach ($sessionData['rsltItems'] as $xid => $iSpec) {
                    $id = $iSpec['id'];
                    $name = $iSpec['nama'];
                    if ((isset($iSpec['disabled']) && $iSpec['disabled'] == "0") || !isset($iSpec['disabled'])) {
                        if (!isset($errFields[$id])) {
                            $errFields[$id] = array();
                        }
                        foreach ($fieldMidValidatorRules as $field => $label) {
                            if (!isset($iSpec[$field])) {
                                $errMsgs[] = "item $label $name is required";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                            if (!is_numeric($iSpec[$field])) {
                                $errMsgs[] = "item $label $name must be a valid number";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                            if ($iSpec[$field] < 0.5) {
                                $errMsgs[] = "item $label $name must be > 0";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                        }
                    }

                }
            }

        }

        //------------------------
        if (sizeof($fieldMidComparisonValidatorRules) > 0) {
            $result = array();
            $labels = array();
            foreach ($fieldMidComparisonValidatorRules as $field => $label) {
                if (!isset($sessionData['main'][$field])) {
                    $errMsgs[] = "$field value is required";
                    $errRows[] = $field;
                }
                if ($sessionData['main'][$field] < 0) {
                    $errMsgs[] = "$field must be >= 0";
                    $errRows[] = $field;
                }
                $labels[$label] = $field;
                $result[$label] = isset($sessionData['main'][$field]) ? $sessionData['main'][$field] : 0;
            }

            if ($result["sumber"] > $result["target"]) {
                $labelSrc = isset($labels["sumber"]) ? $labels["sumber"] : "";
                $labelTarget = isset($labels["target"]) ? $labels["target"] : "";
                $errMsgs[] = "Nilai $labelSrc lebih besar dari nilai $labelTarget.";
                $errRows[] = "test";
            }

        }
        //------------------------

        if (sizeof($fieldMidComparisonValidatorByStepRules) > 0) {
            $result = array();
            $labels = array();
            foreach ($fieldMidComparisonValidatorByStepRules as $field => $fSpec) {
                if (!isset($sessionData['main'][$fSpec['key']])) {
                    $errMsgs[] = $fSpec['label'] . " value is required";
//                    $errRows[] = $field;
                }
                if ($sessionData['main'][$fSpec['key']] < 0) {
                    $errMsgs[] = $fSpec['label'] . " must be >= 0";
//                    $errRows[] = $field;
                }
                $labels[$field] = $fSpec['label'];
                $result[$field] = isset($sessionData['main'][$fSpec['key']]) ? $sessionData['main'][$fSpec['key']] : 0;
            }

            if ($result["source"] > $result["target"]) {
                $labelSrc = isset($labels["source"]) ? $labels["source"] : "";
                $labelTarget = isset($labels["target"]) ? $labels["target"] : "";
                $errMsgs[] = "Nilai $labelSrc lebih besar dari nilai $labelTarget. Silahkan diperiksa kembali.";

            }

        }
        //------------------------
//arrPrint($this->CI->session->login);
        if (sizeof($elementConfigs) > 0) {
            $hiddenMsg = "<br><span style='color:white;font-size:15px;'>" . get_class($this) . "/" . __FUNCTION__ . "</span>";
            foreach ($elementConfigs as $eName => $aSpec) {
                if (!isset($sessionData['main_elements'][$eName])) {
                    if ((isset($aSpec['noValidate'])) && ($aSpec['noValidate'] == true)) {

                    }
                    else {
                        if ($this->CI->session->login) {
                            $errMsgs[] = ($aSpec['label'] . " must be filled first!");
                            echo "<script id='888'>";
                            echo "if(top.document.getElementById('elTitle_$eName')){top.document.getElementById('elTitle_$eName').className='box-headers text-red text-left';}";
                            echo "</script>";
                        }
                    }
                }
                else {
                    switch ($aSpec['elementType']) {
                        case "dataModel":
                            if (isset($validateReceiveElement[$eName]) && sizeof($validateReceiveElement[$eName])) {
                                $valid = 0;
                                $check = array();
                                foreach ($validateReceiveElement[$eName] as $tLabel => $textWarning) {
                                    if (strlen($sessionData['main'][$eName . '__' . $tLabel]) > 10) {
                                    }
                                    else {
                                        $check[] = $tLabel;
                                    }
                                }
                                if (sizeof($check) > 1) {
                                    $errMsgs[] = ("Harap lengkapi salah satu NPWP/KTP diBillingDetails customer");
                                    foreach ($check as $nLabel) {
                                        $errMsgs[] = ("lengkapi $nLabel!");
                                    }
                                }
                            }

                            if (strlen($sessionData['main_elements'][$eName]['key']) < 0.5) {
                                if ($this->CI->session->login) {
                                    $errMsgs[] = ("element " . $aSpec['label'] . " must be filled with one entry!");
                                    echo "<script id='sini919'>";
                                    echo "if(top.document.getElementById('elTitle_$eName')){top.document.getElementById('elTitle_$eName').className='box-headers text-red text-left';}";
                                    echo "</script>";
                                }
                            }
                            else {
                                if ((isset($aSpec['noValidate'])) && ($aSpec['noValidate'] == true)) {

                                }
                                else {
                                    if ($this->CI->session->login) {
                                        echo "<script id='sini929'>";
                                        echo "if(top.document.getElementById('elTitle_$eName')){top.document.getElementById('elTitle_$eName').className='box-headers bg-grey text-left';}";
                                        echo "</script>";
                                    }
                                }
                            }
                            break;
                        case "dataField":
                            if (strlen($sessionData['main_elements'][$eName]['value']) < 0.5) {
                                if ((isset($aSpec['noValidate'])) && ($aSpec['noValidate'] == true)) {
                                }
                                else {
                                    if ($this->CI->session->login) {
                                        $errMsgs[] = ($aSpec['label'] . " must be filled with one entry!***".__LINE__);
                                        echo "<script>";
                                        echo "top.document.getElementById('elTitle_$eName').className='box-headers text-red text-left';";
                                        echo "</script>";
                                    }
                                }
                            }
                            else {
                                if ($this->CI->session->login) {
                                    echo "<script>";
                                    echo "if(top.document.getElementById('elTitle_$eName')){top.document.getElementById('elTitle_$eName').className='box-headers bg-grey text-left';}";
                                    echo "</script>";
                                }
                            }
                            break;
                    }
                }
            }
            if (isset($sessionData['main_elements'])) {
                foreach ($sessionData['main_elements'] as $elName => $elSpec) {
                    switch ($elSpec['elementType']) {
                        case "dataField":
                            if (isset($elSpec['value'])) {
                                if (sizeof(blobDecode($elSpec['value'])) == 0) {
                                    $errMsgs[] = ("Baris " . $elSpec['label'] . " belum dipilih. Silahkan dipilih dahulu. (code " . __LINE__ . ") $hiddenMsg");
                                }
                            }
                            else {
                                $errMsgs[] = ("Baris " . $elSpec['label'] . " belum dipilih. Silahkan dipilih dahulu. (code " . __LINE__ . ") $hiddenMsg");
                            }
                            break;
                        case "dataModel":
                            if (isset($elSpec['contents'])) {
                                if (sizeof(blobDecode($elSpec['contents'])) == 0) {
                                    $errMsgs[] = ("Baris " . $elSpec['label'] . " belum dipilih. Silahkan dipilih dahulu. (code " . __LINE__ . ") $hiddenMsg");
                                }
                            }
                            else {
                                $errMsgs[] = ("Baris " . $elSpec['label'] . " belum dipilih. Silahkan dipilih dahulu. (code " . __LINE__ . ") $hiddenMsg");
                            }
                            break;
                    }
                }
            }
            else {
                cekBlue("main_element tidak ada di session @" . __LINE__ . " " . __FILE__);
            }
        }

        if (sizeof($efakturValidatorConfig) > 0) {
            $enabled = isset($efakturValidatorConfig['enabled']) ? $efakturValidatorConfig['enabled'] : false;
            if ($enabled == true) {
                $kolom = isset($efakturValidatorConfig['kolom']) ? $efakturValidatorConfig['kolom'] : array();
                $source = isset($efakturValidatorConfig['source']) ? $efakturValidatorConfig['source'] : array();
                if (sizeof($kolom) > 0) {
                    foreach ($source as $vSrc) {
                        if ($sessionData['main'][$vSrc] > 0) {

                            foreach ($kolom as $key => $label) {
                                if (!isset($sessionData['main'][$key])) {
                                    $errMsgs[] = $label;
                                }
                            }
                        }
                    }
                }
            }
        }

        if (sizeof($followupMainNoteValidatorConfig) > 0) {
            $enabled = isset($followupMainNoteValidatorConfig['enabled']) ? $followupMainNoteValidatorConfig['enabled'] : false;
            if ($enabled == true) {
                $kolom = isset($followupMainNoteValidatorConfig['kolom']) ? $followupMainNoteValidatorConfig['kolom'] : array();
                $source = isset($followupMainNoteValidatorConfig['source']) ? $followupMainNoteValidatorConfig['source'] : array();
                if (sizeof($kolom) > 0) {
                    foreach ($source as $vSrc) {
                        //                        if ($sessionData['main'][$vSrc] > 0) {

                        foreach ($kolom as $key => $label) {
                            if (!isset($sessionData['main'][$key]) || (strlen($sessionData['main'][$key]) < 1)) {
                                $errMsgs[] = $label;
                            }

                        }
                        //                        }
                    }
                }
            }
        }


        if (sizeof($paidValidatorConfig) > 0) {
            foreach ($paidValidatorConfig as $pKey => $pVal) {
                if ($sessionData['main'][$pKey] <= 0) {
                    $errMsgs[] = $pVal;
                }
            }
        }

        //--------------
//        if (sizeof($kekuranganValidate) > 0) {
//            foreach ($kekuranganValidate as $key => $val) {
//                switch ($key) {
//                    case "source":
//                        if (isset($sessionData['items9_sum'])) {
//                            foreach ($sessionData['items9_sum'] as $bSpec) {
//                                if ($bSpec['qty_kekurangan'] > 0) {
//                                    $bName = htmlspecialchars($bSpec['produk_nama']);
//                                    $qty = $bSpec['qty_kekurangan'];
//                                    $satuan = $bSpec['satuan'];
//                                    $msg = "stok $bName kekurangan $qty $satuan.";
//                                    $errMsgs[] = $msg;
//                                }
//
//                            }
//                        }
//                        break;
//                    default:
//                        break;
//                }
//            }
//        }

        //--------------
        if (isset($itemPriceValidator["enabled"]) && ($itemPriceValidator["enabled"] == true)) {
            // hpp, gerbang items
            // harga jual lama, gerbang items
            // harga jual baru, gerbang items3_sum
            if (isset($sessionData['items'])) {
                $updateHarga = array();
                if (isset($sessionData['items3_sum']) && (sizeof($sessionData['items3_sum']) > 0)) {
                    foreach ($sessionData['items3_sum'] as $pID => $iSpec) {
                        $updateHarga[$pID] = array(
                            "jual_nppn" => isset($iSpec["jual_nppn"]) ? $iSpec["jual_nppn"] : 0,
                        );
                    }
                }
                foreach ($sessionData['items'] as $pID => $iSpec) {
                    $nama = isset($iSpec['nama']) ? $iSpec['nama'] : 0;
                    $hpp = isset($iSpec['hpp']) ? $iSpec['hpp'] : 0;
                    $harga_list = isset($iSpec['harga_list']) ? $iSpec['harga_list'] : 0;
                    $harga_jual_baru = isset($updateHarga[$pID]['jual_nppn']) ? $updateHarga[$pID]['jual_nppn'] : 0;

                    // ADA SETTING HARGA JUAL BARU
                    if (isset($updateHarga[$pID])) {
                        //harga beli 10.000 > harga jual baru 1.000, tolak
                        if (($hpp > $harga_jual_baru)) {
                            $msg = "Harga jual baru $nama lebih kecil dari harga beli. Silahkan dikoreksi di halaman ini.";
                            mati_disini($msg);
                        }
                    }
                    // TIDAK ADA SETTING HARGA JUAL BARU
                    else {
                        //harga beli 10.000 > harga jual 1.000, tolak
                        if (($hpp > $harga_list)) {
                            $msg = "Harga jual lama $nama lebih kecil dari harga beli. Silahkan dikoreksi dengan mengisi harga jual baru di halaman ini.";
                            mati_disini($msg);
                        }
                        //harga jual lama (0), harga jual baru (0), tolak
                        elseif (($harga_jual_baru == 0) && ($harga_list == 0)) {
                            $msg = "Harga jual $nama belum diisikan. Silahkan diisikan dengan mengisi harga jual baru di halaman ini.";
                            mati_disini($msg);
                        }
                    }


                }
            }
        }

        if (sizeof($errMsgs) > 0) {
            $_SESSION['errMsg'] = implode("<br>", $errMsgs);

            if (sizeof($errLines) > 0) {
                $_SESSION['errLines'] = $errLines;
            }
            if (sizeof($errFields) > 0) {
                $_SESSION['errFields'] = $errFields;
            }

            echo lgShowAlert($_SESSION['errMsg']);
            die();
        }

    }

    public function unionValidateSessionData()
    {
        $sessionData = isset($this->sessionData) ? $this->sessionData : mati_disini("Gagal melakukan validasi data. Data yang akan divalidasi tidak tersedia. Silahkan hubungi admin.");
        $cCode = isset($this->cCode) ? $this->cCode : matiHere("cCode " . __METHOD__ . " silahkan diset");
        $unionValidatorRules = isset($this->configUiJenis["shoppingCartUnionValidators"]) ? $this->configUiJenis["shoppingCartUnionValidators"] : array();


        $rawPrevURL = isset($_GET['rawPrev']) ? $_GET['rawPrev'] : "";
        //matiHEre();

        $errMsgs = array();
        $errLines = array();
        $errFields = array();
        $errRows = array();


        if (sizeof($unionValidatorRules) > 0) {
            $result = array();
            $validateFields = 0;
            foreach ($unionValidatorRules as $uSpec) {

                foreach ($uSpec as $field => $label) {
                    $validateFields++;
                    if (!isset($sessionData['main'][$field])) {
                        $result[$field] = "$label value is required";
                    }
                }
                //                $result[$field] = isset($sessionData['main'][$field]) ? $sessionData['main'][$field] : 0;
            }
            //            cekHitam(sizeof($result));
            //            matiHEre($validateFields);
            if (sizeof($result) == $validateFields) {
                foreach ($result as $field => $label) {
                    $errMsgs[] = $label;
                    $errRows[] = $field;
                }
            }

        }


        if (sizeof($errMsgs) > 0) {
            $_SESSION['errMsg'] = implode("<br>", $errMsgs);
            //            print_r($_SESSION['errMsg']);
            //            echo "<script>";
            //            echo "top.getData('" . base_url() . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id','shopping_cart');";
            //            echo "</script>";
            if (sizeof($errLines) > 0) {
                $_SESSION['errLines'] = $errLines;
            }
            if (sizeof($errFields) > 0) {
                $_SESSION['errFields'] = $errFields;
            }

            echo lgShowAlert($_SESSION['errMsg']);
            die();
        }
        //        else {
        //
        //            $actionTarget = "top.BootstrapDialog.show(                                   {
        //                                       title:'preview',
        //                                        message: " . '$' . "('<div></div>').load('" . base_url() . "Transaksi/preview/" . $this->jenisTr . "?rawPrev=$rawPrevURL'),
        //                                        draggable:false,
        //                                        size:top.BootstrapDialog.SIZE_WIDE,
        //                                        type:top.BootstrapDialog.TYPE_SUCCESS,
        //                                        closable:true,
        //                                        }
        //                                        );";
        //
        //            echo "<html>";
        //            echo "<head>";
        //            echo "<script src=\"" . cdn_suport()."AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
        //            echo "</head>";
        //            echo "<body onload=\"$actionTarget\">";
        //            echo "</body>";
        //
        //        }
    }

    public function validate2SessionData()
    {
        $sessionData = isset($this->sessionData) ? $this->sessionData : mati_disini("Gagal melakukan validasi data. Data yang akan divalidasi tidak tersedia. Silahkan hubungi admin.");
        $cCode = isset($this->cCode) ? $this->cCode : matiHere("cCode " . __METHOD__ . " silahkan diset");
        // $validationRules = isset($this->configUiJenis['validationRules']) ? $this->configUiJenis['validationRules'] : matiHere("configUiJenis::validationRules " . __METHOD__ . " silahkan diset");
        $validationRules = isset($this->configUiJenis['validationRules']) ? $this->configUiJenis['validationRules'] : array();


        $msgWarning = array();
        if (isset($validationRules)) {
            foreach ($validationRules as $gateName => $gSpec) {
                if (isset($sessionData[$gateName])) {
                    foreach ($sessionData[$gateName] as $eKey => $eSpec) {
                        if (isset($gSpec['target']) && isset($eSpec[$gSpec['target']]) && $eSpec[$gSpec['target']] > 0) {
                            if ($eSpec[$gSpec['source']] > $eSpec[$gSpec['target']]) {

                                $msg = $gSpec['target'] . " " . $eSpec['nama'] . " tidak cukup.";
                                $msgWarning[$eSpec['id']] = array(
                                    "id" => "trItems_" . $eSpec['id'],
                                    "label" => $msg,
                                );
                            }
                        }
                        else {
                            $msg = $gSpec['target'] . " " . $eSpec['nama'] . " tidak cukup.";
                            $msgWarning[$eSpec['id']] = array(
                                "id" => "trItems_" . $eSpec['id'],
                                "label" => $msg,
                            );
                        }
                    }
                }
            }
        }

        return $msgWarning;
    }

    public function pairSesionValidateSessionData($step)
    {
        $sessionData = isset($this->sessionData) ? $this->sessionData : mati_disini("Gagal melakukan validasi data. Data yang akan divalidasi tidak tersedia. Silahkan hubungi admin.");
        $fieldItemValueRules = isset($this->configUiJenis["shopingCartValidatePairSession"][$step]) ? $this->configUiJenis["shopingCartValidatePairSession"][$step] : array();
        $fieldValidate = isset($this->configUiJenis["shopingCartValidatePairSession"][$step]["validate"]) ? $this->configUiJenis["shopingCartValidatePairSession"][$step]["validate"] : array();
//        $cCode = "_TR_" . $this->jenisTr;
        $cCode = $this->cCode;

        $this->CI->load->model("Mdls/MdlCabang");
        $c = new MdlCabang();
        $tnpC = $c->lookUpAll()->result();
        $cab = array();
        foreach ($tnpC as $tmp) {
            $cab[$tmp->id] = $tmp->nama;
        }
        $cab[CB_ID_PUSAT] = "PUSAT";

//        arrPrint($fieldItemValueRules);
        if (sizeof($fieldItemValueRules) > 0) {
            $source = $fieldItemValueRules['source'];
            $target = $fieldItemValueRules['target'];
            $sourceData = $sessionData[$source];
            $targetData = $sessionData[$target];
            $fieldsSource = $fieldValidate["source"];
            $fieldstarget = $fieldValidate["target"];
            //            arrPrint($targetData);
            $errMsgs = array();
            foreach ($targetData as $Cid => $tmpData) {
                $tmpError = array();
                foreach ($tmpData as $PID => $tmpVal) {
                    $sourceVal = $sourceData[$PID][$fieldsSource];
                    $targetVal = $tmpVal[$fieldstarget];
                    $minVal = $sourceVal + $sourceVal * 0.025;
                    if ($sourceVal > $targetVal) {
                        //                        $errMsgs[] = "Harga jual ".$sourceData[$PID]['nama']." di ".$cab[$Cid]." lebih rendah dari HPP, silahkan diperbaiki<br>";
                        //                        $tmpError[] = "harga jual ".$sourceData[$PID]['nama']." di ".$cab[$cid]." lebih rendah dari HPP! harga jual harus lebih besar  2,5% dari ".formatfield("nilai",$sourceVal)." ( sebesar ".formatField("nilai",$minVal)." ) harga yang dimasukan saat ini sebesar (".formatField("nilai",$targetVal).") silahkan diperbaiki<br>";
                        $errMsgs[] = "harga jual " . $sourceData[$PID]['nama'] . " di " . $cab[$Cid] . " lebih rendah dari HPP! harga jual harus lebih besar  2,5% dari " . formatfield("nilai", $sourceVal) . " ( sebesar " . formatField("nilai", $minVal) . " ) harga yang dimasukan saat ini sebesar (" . formatField("nilai", $targetVal) . ") silahkan diperbaiki<br>";
                        cekHitam($cab[$Cid] . " " . $sourceData[$PID]['nama']);
                    }

                }
                //                $errMsgs[]=$tmpError;
            }
            //arrPrint($errMsgs);
            //            matiHEre();
            if (sizeof($errMsgs) > 0) {
                $_SESSION['errMsg'] = implode("<br>", $errMsgs);
                //                foreach($errMsgs as $errMsg){
                //                    $_SESSION['errMsg'] = implode("<br>", $errMsg);
                //                    echo lgShowAlert($_SESSION['errMsg']);
                //                }

                //                foreach()
                echo lgShowAlert($_SESSION['errMsg']);
                die();
            }

        }

    }

    public function validateSessionData()
    {
        $fieldOpnameEntryValidatorRules = isset($this->configUiJenis["shoppingCartFieldOpnameEntryValidators"]) ? $this->configUiJenis["shoppingCartFieldOpnameEntryValidators"] : array();
        $fieldOpnameValidatorRules = isset($this->configUiJenis["shoppingCartFieldOpnameValidators"]) ? $this->configUiJenis["shoppingCartFieldOpnameValidators"] : array();

        $fieldValidatorRules = isset($this->configUiJenis["shoppingCartFieldValidators"]) ? $this->configUiJenis["shoppingCartFieldValidators"] : array();
        $rowValidatorRules = isset($this->configUiJenis["shoppingCartRowValidators"]) ? $this->configUiJenis["shoppingCartRowValidators"] : array();
        $rowNumValidatorRules = isset($this->configUiJenis["shoppingCartRowNumValidators"]) ? $this->configUiJenis["shoppingCartRowNumValidators"] : array();
        $rowOptValidatorRules = isset($this->configUiJenis["shoppingCartRowOptionalValidators"]) ? $this->configUiJenis["shoppingCartRowOptionalValidators"] : array();
        $validateReceiveElement = isset($this->configUiJenis["validateReceiveElement"][1]) ? $this->configUiJenis["validateReceiveElement"][1] : array();
        $appletConfigs = isset($this->configUiJenis['applets']) ? $this->configUiJenis['applets'] : array();
        $elementConfigs = isset($this->configUiJenis['receiptElements']) ? $this->configUiJenis['receiptElements'] : array();
        $relElementConfigs = isset($this->configUiJenis['relativeElements']) ? $this->configUiJenis['relativeElements'] : array();
        $availPayments = isset($this->configUiJenis['availPayments']) ? $this->configUiJenis['availPayments'] : array();
        $validateAdditional = isset($this->configUiJenis['shopingCartAddValidator']['additional']) ? $this->configUiJenis['shopingCartAddValidator']['additional'] : array();
        $fieldValidatorsComparison = isset($this->configUiJenis['shoppingCartFieldValidatorsComparison']) ? $this->configUiJenis['shoppingCartFieldValidatorsComparison'] : array();

        $fieldValidatorPairedItem = isset($this->configUiJenis["shoppingCartValidatorsPairedItem"]) ? $this->configUiJenis["shoppingCartValidatorsPairedItem"] : array();
        $unionValidateComparison = isset($this->configUiJenis["shopingCartUnionComparison"]) ? $this->configUiJenis["shopingCartUnionComparison"] : array();
        $unionValidate = isset($this->configUiJenis["shoppingCartUnionValidators"]) ? $this->configUiJenis["shoppingCartUnionValidators"] : array();
        $balanceValidate = isset($this->configUiJenis["shoppingCartBalanceValidate"]) ? $this->configUiJenis["shoppingCartBalanceValidate"] : array();
        $purchaseValidator = isset($this->configUiJenis["purchaseValidator"][1]) ? $this->configUiJenis["purchaseValidator"][1] : array();


        $previewCtr = isset($this->configUiJenis["previewCtr"]) ? $this->configUiJenis["previewCtr"] : matiHere('controller preview belum diset');

        $sessionData = isset($this->sessionData) ? $this->sessionData : mati_disini("Gagal melakukan validasi data. Data yang akan divalidasi tidak tersedia. Silahkan hubungi admin.");
        $cCode = isset($this->cCode) ? $this->cCode : matiHere("cCode " . __METHOD__ . " silahkan diset");
//        $rawPrevURL = isset($_GET['rawPrev']) ? $_GET['rawPrev'] : "";

        //==iterasi untuk memasukkan element relatif
        if (isset($sessionData['main_elements']) && sizeof($sessionData['main_elements']) > 0) {
            //            //cekBiru("hendak memeriksa relative impacts");
            foreach ($sessionData['main_elements'] as $eName => $eSpec) {
                //                //cekBiru("memeriksa $eName:");
                if (array_key_exists($eName, $relElementConfigs)) {
                    //                    //cekHijau("$eName memiliki relative impacts");
                    $currentValue = "";
                    switch ($eSpec['elementType']) {
                        case "dataModel":
                            $currentValue = $eSpec['key'];
                            break;
                        case "dataField":
                            $currentValue = $eSpec['value'];
                            break;
                    }
                    if (array_key_exists($currentValue, $relElementConfigs[$eName])) {
                        //                        //cekHijau("memenuhi syarat");
                        //===daftarkan ke elementConfig
                        if (sizeof($relElementConfigs[$eName][$currentValue]) > 0) {
                            //                            //cekMerah("memeriksa $eName, $currentValue");
                            //                            $rcCtr = 0;
                            foreach ($relElementConfigs[$eName][$currentValue] as $rcID => $rcSpec) {
                                //                                $elKey = $eName . "_" . $currentValue . "_" . $rcID;
                                $elKey = $rcID;
                                $elementConfigs[$elKey] = $relElementConfigs[$eName][$currentValue][$rcID];
                                //                                $rcCtr++;
                            }
                        }

                    }
                    else {
                        //                        //cekMerah("TIDAK memenuhi syarat");
                    }
                }
            }
        }
//        $arrySegment = $this->uri->segment_array();


        if (isset($sessionData['mode']['edit']) && sizeof($sessionData['mode']['edit']) > 0) {
            $prevAction = $previewCtr . "/editPreview";
            $main_id = "/" . $sessionData['mode']['edit'];
        }
        else {
            if (isset($sessionData['mode']['cancel']) && sizeof($sessionData['mode']['cancel']) > 0) {
                $prevAction = "Transaksi/preCancelPackingPreview";
                $main_id = "/" . $sessionData['mode']['cancel'];
            }
            else {
                $prevAction = $previewCtr . "/preview";
                $main_id = "";
            }
        }

        $errMsgs = array();
        $errLines = array();
        $errFields = array();
        $errRows = array();
        $errErrorException = array();
        if (sizeof($rowValidatorRules) > 0) {

            foreach ($rowValidatorRules as $field => $label) {
                if (!isset($sessionData['main'][$field])) {
                    $errMsgs[] = "$label wajib diisi";

                    $errRows[] = $field;
                }

            }

        }
        if (sizeof($rowNumValidatorRules) > 0) {

            foreach ($rowNumValidatorRules as $field => $label) {
                if (!isset($sessionData['main'][$field])) {
                    $errMsgs[] = "$label wajib dilengkapi";

                    $errRows[] = $field;
                }
                else {
                    if (0 + ($sessionData['main'][$field]) < 0.1) {
                        $errMsgs[] = "$label wajib berupa angka. Silahkan dikoreksi kembali.";

                        $errRows[] = $field;
                    }
                }

            }

        }
        if (sizeof($rowOptValidatorRules) > 0) {
            foreach ($rowOptValidatorRules as $srcName => $srcSpec) {
                foreach ($srcSpec as $value => $pair) {
                    if (isset($sessionData['main'][$srcName]) && $sessionData['main'][$srcName] == $value) {
                        foreach ($pair as $k => $v) {
                            if (!isset($sessionData['main'][$k])) {
                                $errMsgs[] = $v != '' ? $v : "$k wajib dipilih. Silahkan dikoreksi kambali.*";
                                $errRows[] = $k;
                            }
                        }
                    }
                }
            }
        }
        if (sizeof($fieldValidatorRules) > 0) {

            if (isset($sessionData['items']) && sizeof($sessionData['items']) > 0) {
                $no_baris = 0;
                foreach ($sessionData['items'] as $xid => $iSpec) {
                    $no_baris++;
                    $id = $iSpec['id'];
                    $nama = htmlspecialchars($iSpec['name']);
                    if ((isset($iSpec['disabled']) && $iSpec['disabled'] == "0") || !isset($iSpec['disabled'])) {
                        if (!isset($errFields[$id])) {
                            $errFields[$id] = array();
                        }
                        foreach ($fieldValidatorRules as $field => $label) {
                            if (!isset($iSpec[$field])) {
                                $errMsgs[] = "$label $nama wajib diisi pada baris ke-$no_baris. Silahkan dikoreksi kembali.";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                            if (!is_numeric($iSpec[$field])) {
                                $errMsgs[] = "$label $nama wajib berupa angka pada baris ke-$no_baris. Silahkan dikoreksi kembali.";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                            if ((int)$iSpec[$field] < 0.5) {
//                                $errMsgs[] = "$label must be > 0";
                                $errMsgs[] = "$label $nama belum diisi pada baris ke-$no_baris. Silahkan diisi dahulu.";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;

                            }
                        }
                    }

                }
            }

        }
        if (sizeof($elementConfigs) > 0) {
            foreach ($elementConfigs as $eName => $aSpec) {
                if (!isset($sessionData['main_elements'][$eName])) {
                    if ((isset($aSpec['noValidate'])) && ($aSpec['noValidate'] == true)) {
                    }
                    else {
                        $errMsgs[] = ($aSpec['label'] . " wajib dipilih!");
                        echo "<script>";
                        echo "if(top.document.getElementById('elTitle_$eName')){top.document.getElementById('elTitle_$eName').className='box-headers text-red text-left'};";
                        echo "</script>\n";
                    }
                }
                else {
                    switch ($aSpec['elementType']) {
                        case "dataModel":
                            if (isset($validateReceiveElement[$eName]) && sizeof($validateReceiveElement[$eName])) {
                                $valid = 0;
                                $check = array();
                                foreach ($validateReceiveElement[$eName] as $tLabel => $textWarning) {
                                    if (strlen($sessionData['main'][$eName . '__' . $tLabel]) > 10) {
                                    }
                                    else {
                                        $check[] = $tLabel;
                                    }
                                }
                                if (sizeof($check) > 1) {
                                    $errMsgs[] = ("Harap lengkapi salah satu NPWP/KTP diBillingDetails customer");
                                    foreach ($check as $nLabel) {
                                        $errMsgs[] = ("lengkapi $nLabel!");
                                    }
                                }
                            }

                            if (strlen($sessionData['main_elements'][$eName]['key']) < 0.5) {
                                $errMsgs[] = ("element " . $aSpec['label'] . " must be filled with one entry!");
                                echo "<script>";
                                echo "if(top.document.getElementById('elTitle_$eName')){top.document.getElementById('elTitle_$eName').className='box-headers text-red text-left'};";
                                echo "</script>\n";
                            }
                            else {
                                echo "<script>";
                                echo "if(top.document.getElementById('elTitle_$eName')){top.document.getElementById('elTitle_$eName').className='box-headers bg-grey text-left'};";
                                echo "</script>\n";
                            }
                            break;
                        case "dataField":
                            if (strlen($sessionData['main_elements'][$eName]['value']) < 0.5) {
                                if ((isset($aSpec['noValidate'])) && ($aSpec['noValidate'] == true)) {
                                }
                                else {
                                    $errMsgs[] = ($aSpec['label'] . " must be filled with one entry!***");
                                    echo "<script>";
                                    echo "if(top.document.getElementById('elTitle_$eName')){top.document.getElementById('elTitle_$eName').className='box-headers text-red text-left'};";
                                    echo "</script>\n";
                                }
                            }
                            else {
                                echo "<script>";
                                echo "if(top.document.getElementById('elTitle_$eName')){top.document.getElementById('elTitle_$eName').className='box-headers bg-grey text-left'};";
                                echo "</script>\n";
                            }
                            break;
                    }

                }
            }
        }
        if (sizeof($fieldValidatorsComparison) > 0) {
            $result = array();
            $labels = array();
            foreach ($fieldValidatorsComparison as $field => $label) {
                $labels[$label] = $field;
                $result[$label] = isset($sessionData['main'][$field]) ? $sessionData['main'][$field] : 0;
            }

            if (round($result["sumber"], 2) != round($result["target"], 2)) {
                $labelSrc = isset($labels["sumber"]) ? $labels["sumber"] : "";
                $labelTarget = isset($labels["target"]) ? $labels["target"] : "";
                $errMsgs[] = "Nilai total tidak sama dari nilai akumulasi nota yang dipilih.";
                $errRows[] = "test";
            }
        }
        if (sizeof($unionValidateComparison) > 0) {
            $result = array();
            $labels = array();
            $validate = 0;
            $val = array();
            $valsValidate = array();

            foreach ($unionValidateComparison as $fieldData) {

                if (isset($sessionData['main']["nilai_entry"])) {
                    if ($sessionData['main']["nilai_entry"] > 0) {

                    }
                    else {
                        if ($sessionData['main']["nilai_entry"] == 0) {
                            unset($sessionData['main']["nilai_entry"]);
                        }
                        else {

                            if (sizeof($unionValidate) > 0) {

                                foreach ($unionValidate as $fieldUnion) {
                                    $validateUnion = 0;
                                    foreach ($fieldUnion as $field => $fieldLabel) {
                                        if (isset($sessionData['main'][$field]) && $sessionData['main'][$field] > 0) {

                                        }
                                        else {
                                            $validateUnion++;

                                        }
                                    }
                                    if (sizeof($fieldUnion) == $validateUnion) {
                                        $errMsgs[] = $fieldData["nilai_entry"];
                                    }
                                }
                            }
                            else {
                                $errMsgs[] = $fieldData["nilai_entry"];
                            }
                        }

                    }
                }
                else {
                    if (sizeof($unionValidate) > 0) {

                        foreach ($unionValidate as $fieldUnion) {
                            $validateUnion = 0;
                            $err = array();
                            foreach ($fieldUnion as $field => $fieldLabel) {
                                if (isset($sessionData['main'][$field]) && $sessionData['main'][$field] > 0) {

                                }
                                else {
                                    $validateUnion++;
                                    $err[] = $fieldLabel;

                                }
                            }
                            if (sizeof($err) == sizeof($fieldUnion)) {
                                $errMsgs[] = "metode pembayaran tidak ada yang dipilih atau jumlah pembayaran belum diisi, silahkan periksa kembali.";
                            }
                        }
                    }
                    else {
                        $errMsgs[] = $fieldData["nilai_entry"];
                    }
                }

                foreach ($fieldData as $field => $label) {

                    //                    if (isset($sessionData['main'][$field]) && ($sessionData['main'][$field] > 0)) {
                    if (isset($sessionData['main'][$field])) {
                        //cekHere("ada $field dan nilai $field tidak sama dengan nol ");
                        $validate++;
                        $val["source"] = $field;
                        $valsValidate["source"][] = $field;
                    }
                    else {
                        //cekHere("TIDAK ada $field  ");
                        $val["target"] = $field;
                        $valsValidate["target"][] = $field;
                    }
                }

            }
            //arrPrint($unionValidateComparison);
            //arrPrint($valsValidate);


            if (array_key_exists("source", $val)) {
                $cekval = $val["target"];
                if (!isset($sessionData['main'][$cekval])) {
                    foreach ($unionValidateComparison as $data) {
                        //                        //cekHere(":: sampai sini $cekval :: " . $data[$cekval]);
                        if (isset($data[$cekval])) {
                            $errMsgs[] = $data[$cekval];
                            $errErrorException[] = $data[$cekval];
                        }
                    }
                }
                //arrPrint($errMsgs);
            }
            // arrPrint($valsValidate);
            if (!isset($valsValidate["source"]) || (sizeof($valsValidate["source"]) == 0)) {
                // mati_disini("tidak memilih cash account, credit note, uang muka");
                //  $errMsgs[] = "cash account, credit note, uang muka tidak ada yang dipilih.";
            }

        }
        if (sizeof($fieldValidatorPairedItem) > 0) {
            $label = $this->configUiJenis["label"];
            $source = isset($sessionData[$fieldValidatorPairedItem['sumber']]) ? $sessionData[$fieldValidatorPairedItem['sumber']] : "";
            $target = isset($sessionData[$fieldValidatorPairedItem['target']]) ? $sessionData[$fieldValidatorPairedItem['target']] : "";
            if (sizeof($source) > 0) {
                foreach ($source as $pID => $pSpec) {
                    $pTarget = isset($target[$pID]) ? $target[$pID] : array();

                    if (!array_key_exists($pID, $target)) {
                        $errMsgs[] = "$label " . $pSpec['name'] . " wajib dipilih/ditentukan. Silahkan dikoreksi kembali.";
                    }
                    if (isset($pTarget['jml']) && isset($pSpec['jml'])) {
                        if ($pTarget['jml'] < $pSpec['jml']) {
                            $errMsgs[] = "konversi " . $pSpec['name'] . " salah. silahkan cek ulang jumlah konversinya.";
                        }
                        $jml_satuan = $pTarget['jml'] / $pSpec['jml'];
                        $jml_satuan_ex = explode(".", $jml_satuan);
                        if (sizeof($jml_satuan_ex) > 1) {
                            $errMsgs[] = "konversi " . $pSpec['name'] . " salah. silahkan cek ulang jumlah konversinya.";
                        }
                    }
                }
            }
        }
        if (sizeof($fieldOpnameValidatorRules) > 0) {
            if (isset($sessionData['items']) && sizeof($sessionData['items']) > 0) {
                foreach ($sessionData['items'] as $xid => $iSpec) {
                    $id = $iSpec['id'];
                    $name = htmlspecialchars($iSpec['nama']);
                    if ((isset($iSpec['disabled']) && $iSpec['disabled'] == "0") || !isset($iSpec['disabled'])) {
                        foreach ($fieldOpnameValidatorRules as $field => $label) {
                            if (isset($iSpec['qty_selisih']) && ($iSpec['qty_selisih'] > 0)) {
                                if ((int)$iSpec[$field] <= 0) {
                                    $errMsgs[] = "$label $name harus lebih dari 0. Silahkan dikoreksi kembali.";
                                    $errLines[] = $id;
                                    $errFields[$id][] = $field;
                                }
                            }
                        }
                    }
                }
            }
        }
        if (sizeof($fieldOpnameEntryValidatorRules) > 0) {
            if (isset($sessionData['items']) && sizeof($sessionData['items']) > 0) {
                $no = 0;
                foreach ($sessionData['items'] as $xid => $iSpec) {
                    $no++;
                    $id = $iSpec['id'];
                    $kode = $iSpec['produk_kode'];
                    $label = $iSpec['label'];

                    $name = htmlspecialchars($iSpec['nama']);
                    if ((isset($iSpec['disabled']) && $iSpec['disabled'] == "0") || !isset($iSpec['disabled'])) {
                        foreach ($fieldOpnameEntryValidatorRules as $field => $label) {
                            if (!isset($iSpec[$field]) && ($iSpec['stok'] > 0)) {
                                $errMsgs[] = "$label $name harus diketik ulang, baris nomer $no.";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;

                            }
                        }
                    }

                    $stokBuku = isset($iSpec->stok) ? $iSpec->stok : 0;
                    $stokOpname = $iSpec->qty_opname;
                    $selisih = $stokOpname - $stokBuku;
                    $stokBukuHitung = $iSpec->qty_opname - $iSpec->qty_debet + $iSpec->qty_kredit;
                    if ($selisih < 0) {
                        if ($selisih != $iSpec->qty_kredit) {
                            $msg = "Produk $label $kode SALAH. Silahkan diperiksa baris nomer $no.";
                            $errMsgs[] = $msg;
                        }
                    }
                    elseif ($selisih > 0) {
                        if ($selisih != $iSpec->qty_debet) {
                            $msg = "Produk $label $kode SALAH. Silahkan diperiksa baris nomer $no.";
                            $errMsgs[] = $msg;
                        }
                    }
                    elseif ($stokBukuHitung != $stokBuku) {
                        $msg = "Produk $label $kode SALAH. Silahkan diperiksa baris nomer $no.";
                        $errMsgs[] = $msg;
                    }
                    //                    else{
                    //                        mati_disini("Produk $label $kode SALAH. Silahkan diperiksa baris nomer $no.");
                    //                    }
                }
            }
        }

        if(sizeof($purchaseValidator)>0){
            if(isset($purchaseValidator["enabled"]) && ($purchaseValidator["enabled"] == true)){
                $grandTotal = $_SESSION[$cCode]["main"]["grandTotal"];
                $nilai_entry = $_SESSION[$cCode]["main"]["nilai_entry"];
                $selisih = $grandTotal - $nilai_entry;
                $selisih = ($selisih < 0) ? $selisih * -1 : $selisih;
                $grandTotal_f = "<strong>" . number_format($grandTotal, "0", ".", ",") . "</strong>";
                $nilai_entry_f = "<strong>" . number_format($nilai_entry, "0", ".", ",") . "</strong>";
                if ($selisih > 100) {
                    $msg = "Nilai pembelian dari system $grandTotal_f. Nilai pembelian yang anda masukkan $nilai_entry_f.";
                    $msg .= "<br>Pastikan nilai pembelian dari system sama dengan nilai invoice dari supplier/vendor. ";
                    $msg .= "Silahkan dikoreksi kembali.";
                    mati_disini($msg);
                }
            }
        }

        //region validate additional
        if (sizeof($validateAdditional) > 0) {
            $selectedAdd = $sessionData["main"]["additional"];
            if (isset($validateAdditional[$selectedAdd])) {
                $valKey = $validateAdditional[$selectedAdd];
                if ($sessionData["main"][$valKey] == 0) {
                    $errMsgs[] = "additional kurs must be filled or select none on additional for additional kurs 0 ";
                }
            }

        }
        //        matiHEre();

        //endregion

        if (sizeof($balanceValidate) > 0) {
            foreach ($balanceValidate as $kk => $kSpec) {
                if ($kk == "key") {
                    $debet = $kSpec[0];
                    $kredit = $kSpec[1];
                    $debetNilai = isset($sessionData['main'][$debet]) ? $sessionData['main'][$debet] : 0;
                    $kreditNilai = isset($sessionData['main'][$kredit]) ? $sessionData['main'][$kredit] : 0;
                    if ($debetNilai != $kreditNilai) {
                        $errMsgs[] = $balanceValidate['label'];
                    }
                }
            }
        }


        $gateNameDetail = "tableIn_detail";
        //arrPrint($sessionData[$gateNameDetail]);
        if (!isset($sessionData[$gateNameDetail]) || sizeof($sessionData[$gateNameDetail]) == 0) {
            $msg = "#1 Transaksi gagal dilanjutkan karena rincian transaksi kosong.<br>Silahkan merubah qty/unit price dahulu dan masukkan data yang benar supaya transaksi bisa dilanjutkan. @" . __LINE__ . __METHOD__;

            mati_disini($msg);
        }
        else {
            foreach ($sessionData[$gateNameDetail] as $spec) {
                if (!isset($spec['valid_qty'])) {
                    $hidden_msg = "<br><span style='color:#ffffff;'>gerbang valid_qty tidak ada</span>";
                    $msg = "#2 Transaksi gagal dilanjutkan karena rincian transaksi kosong.<br>Silahkan merubah qty/unit price dahulu dan masukkan data yang benar supaya transaksi bisa dilanjutkan. $hidden_msg";

                    mati_disini($msg);
                }
                else {
                    if ($spec['valid_qty'] == 0) {
                        $hidden_msg = "<br><span style='color:#ffffff;'>gerbang valid_qty bernilai 0</span>";
                        $msg = "#3 Transaksi gagal dilanjutkan karena rincian transaksi kosong.<br>Silahkan merubah qty/unit price dahulu dan masukkan data yang benar supaya transaksi bisa dilanjutkan. $hidden_msg";

                        mati_disini($msg);
                    }
                }
            }
        }


        /* ------------------------------
         * validasi stok saat mau produksi
         * ------------------------------*/
        $mains = $sessionData;
        $items2_sum = isset($mains['items2_sum']) ? $mains['items2_sum'] : "";
        switch ($this->jenisTr) {
            case "776":

                $kurangStoks = array();
                $hasil = "";
                $no = 0;
                foreach ($items2_sum as $pId => $item) {

                    $nama = $item['nama'];
                    $sisa = $item['stok'] - $item['jml'];

                    if ($sisa < 0) {
                        $no++;
                        $kurangStoks[$pId] = $sisa;
                        //
                        $sisa_f = $sisa * -1;
                        $nama_f = htmlspecialchars($nama);
                        $var = "<span class='font-size-0-7'>$no. $nama_f: <span class='text-bold'>$sisa_f</span></span>";
                        if ($hasil == "") {
                            $hasil = "$var";
                        }
                        else {
                            $hasil = "$hasil<br>$var";
                        }
                    }
                }


                if (sizeof($kurangStoks) > 0) {
                    // //cekhitam(sizeof($kurangStoks));
                    $alerts = array("type" => "warning",
                        "title" => "Persediaan tidak cukup",
                        "html" => $hasil,
                    );
                    echo swalAlert($alerts);
                    die();
                }

                break;
            case "1982":
                break;
            case "961":
            case "967":
                $items = $mains['items'];
                $kurangStoks = array();
                $hasil = "";
                $no = 0;
                foreach ($items as $pId => $item) {
                    if (isset($item['stok'])) {
                        $nama = $item['nama'];
                        $sisa = $item['stok'] - $item['jml'];

                        if ($sisa < 0) {
                            $no++;
                            $kurangStoks[$pId] = $sisa;

                            $sisa_f = $sisa * -1;
                            $stok_f = $item['stok'];
                            $jml_f = $item['jml'];
                            $nama_f = htmlspecialchars($nama);
                            $var = "<span class='font-size-0-7'>$no. <span style='font-weight:bold;'>$nama_f</span>, stok tersedia $stok_f, jumlah input <span style='font-weight:bold;'>$jml_f</span> melebihi stok yang tersedia. Silahkan dikoreksi lagi.</span>";
                            if ($hasil == "") {
                                $hasil = "$var";
                            }
                            else {
                                $hasil = "$hasil<br>$var";
                            }
                        }
                    }
                }
                if (sizeof($kurangStoks) > 0) {

                    $alerts = array("type" => "warning",
                        "title" => "Insufficient stock",
                        "html" => $hasil,
                    );
                    echo swalAlert($alerts);
                    die();
                }

                break;
            case "749":
                if ($sessionData['main']['lebih_bayar'] <= 0) {
                    //                    matiHEre("iki opoo ".$sessionData['main']['lebih_bayar']);
                    $sessionData['main']['nilai_cash'] = $sessionData['main']['nilai_entry'];
                    //arrPrint($errErrorException);
                    if (sizeof($errErrorException) > 0) {
                        foreach ($errErrorException as $errKey => $errVal) {
                            if (array_key_exists($errKey, $errMsgs)) {
                                unset($errMsgs[$errKey]);
                            }
                        }
                    }

                }

                break;
        }


        //---------
        if (sizeof($errMsgs) > 0) {
            $_SESSION['errMsg'] = implode("<br>", $errMsgs);

            if (sizeof($errLines) > 0) {
                $_SESSION['errLines'] = $errLines;
            }

            if (sizeof($errFields) > 0) {
                $_SESSION['errFields'] = $errFields;
            }


            echo lgShowAlert($_SESSION['errMsg']);
            die();
        }
//        else {
//
//            // mati_disini("LOLOS, tidak kena VALIDATE :: $prevAction");
//            // $shoppingCart =  base_url() . $this->modul . "/" . $prevAction . "/" . $this->jenisTr . "?rawPrev=$rawPrevURL";
//
//            $actionTarget = "top.BootstrapDialog.show({
//                                title:'preview',
//                                message: " . 'top.$' . "('<div></div>').load('" . base_url() . $this->modul . "/" . $prevAction . "/" . $this->jenisTr . "?rawPrev=$rawPrevURL'),
//                                draggable:false,
//                                animate: false,
//                                size:top.BootstrapDialog.SIZE_WIDE,
//                                type:top.BootstrapDialog.TYPE_DEFAULT,
//                                closable:true,
//                                onshow: function(dialogRef){
//
//                                },
//                                onshown: function(dialogRef){
//
//                                },
//                                onhide: function(dialogRef){
//
//                                },
//                                onhidden: function(dialogRef){
//
//                                }
//
//                                });";
//
//            echo "<script>top.close_holdon();$actionTarget</script>";
//
//        }

        //---------

    }
    //---------------------


    //validate master dengan detail, dipakai produk, supplies, rakitan----------------------------
    public function validateMasterDetail($trID, $configMaster, $configDetail)
    {

        $this->CI->load->model("Coms/ComRekening");


        $cr = New ComRekening();

//        arrPrintKuning($configMaster);
//        arrPrintPink($configDetail);
        $arrRekeningAlias = fetchAccountStructureAlias();
        $arrRekeningReadyValidate = array();
        $arrRekeningValidate = array(
            "1010030010",//persediaan supplies
            "1010030030",//persediaan produk
            "1010030070",//persediaan rakitan
        );
        $arrComRekeningBlacklist = array(
            "RekeningPembantuProdukPerSerial",//serial number
        );
        $arrTrIDs = array(
            $trID
        );

        $arrPembantu = array();
        if (sizeof($configMaster) > 0) {
            foreach ($configMaster as $ii => $mSpec) {
                if (isset($mSpec['loop']) && sizeof($mSpec['loop']) == 1) {
                    foreach ($mSpec['loop'] as $rek_loop => $xxxx) {
//                        cekHere("$rek_loop ---> " . $mSpec['comName']);
                        $arrPembantu[$rek_loop] = $mSpec['comName'];
                    }
                }
            }
        }

        if (sizeof($configDetail) > 0) {
            foreach ($configDetail as $ii => $dSpec) {
                if (isset($dSpec['loop']) && sizeof($dSpec['loop']) == 1) {
                    foreach ($dSpec['loop'] as $rek_loop => $xxxx) {
//                        cekHere("$rek_loop ---> " . $dSpec['comName']);
                        $arrPembantu[$rek_loop] = $dSpec['comName'];
                    }
                }
            }
        }

//        arrprintHijau($arrPembantu);
        $temppembantu = array();
        if (sizeof($arrPembantu) > 0) {
            foreach ($arrPembantu as $rek_coa => $comName) {
                if (in_array($rek_coa, $arrRekeningValidate)) {
                    if (!in_array($comName, $arrComRekeningBlacklist)) {
                        $arrRekeningReadyValidate[$rek_coa] = $comName;

                        //region rekening detail
                        $comNameNew = "Com" . $comName;
                        $this->CI->load->model("Coms/$comNameNew");
                        $cc = New $comNameNew();
                        $ccResult = $cc->fetchMovesByTransIDs($rek_coa, $arrTrIDs);
                        showlast_query("biru");
//                    arrPrintKuning($ccResult);
                        foreach ($ccResult as $ddSpec) {
//                        arrPrint($ddSpec);
                            $transaksi_jenis_validate = $ddSpec->jenis;
                            $dDebet = isset($ddSpec->debet) ? $ddSpec->debet : 0;
                            $dKredit = isset($ddSpec->kredit) ? $ddSpec->kredit : 0;
                            if (!isset($cekRekening["detail"][$rek_coa]["debet"])) {
                                $cekRekening["detail"][$rek_coa]["debet"] = 0;
                            }
                            if (!isset($cekRekening["detail"][$rek_coa]["kredit"])) {
                                $cekRekening["detail"][$rek_coa]["kredit"] = 0;
                            }
                            $cekRekening["detail"][$rek_coa]["debet"] += $dDebet;
                            $cekRekening["detail"][$rek_coa]["kredit"] += $dKredit;
                        $temppembantu[$rek_coa]["debet"][$ddSpec->produk_id]=$dDebet;
                        $temppembantu[$rek_coa]["kredit"][$ddSpec->produk_id]=$dKredit;
                        }
                        //endregion

                        //region rekening master
                        $crResult = $cr->fetchMovesByTransIDs($rek_coa, $arrTrIDs);
                        showLast_query("biru");
                        foreach ($crResult as $mmSpec) {
                            $mDebet = isset($mmSpec->debet) ? $mmSpec->debet : 0;
                            $mKredit = isset($mmSpec->kredit) ? $mmSpec->kredit : 0;
                            if (!isset($cekRekening["master"][$rek_coa]["debet"])) {
                                $cekRekening["master"][$rek_coa]["debet"] = 0;
                            }
                            if (!isset($cekRekening["master"][$rek_coa]["kredit"])) {
                                $cekRekening["master"][$rek_coa]["kredit"] = 0;
                            }
                            $cekRekening["master"][$rek_coa]["debet"] += $mDebet;
                            $cekRekening["master"][$rek_coa]["kredit"] += $mKredit;
                        }
                        //endregion rekening master
                    }
                }
            }

//            arrPrintKuning($arrRekeningReadyValidate);
//            arrPrintHijau($cekRekening);
            cekHitam(":: $transaksi_jenis_validate");
            foreach ($arrRekeningReadyValidate as $rek_coa => $com) {
                $rek_coa_alias = isset($arrRekeningAlias[$rek_coa]) ? $arrRekeningAlias[$rek_coa] : $rek_coa;
                $detail_debet = isset($cekRekening["detail"][$rek_coa]["debet"]) ? $cekRekening["detail"][$rek_coa]["debet"] : 0;
                $detail_kredit = isset($cekRekening["detail"][$rek_coa]["kredit"]) ? $cekRekening["detail"][$rek_coa]["kredit"] : 0;
                $master_debet = isset($cekRekening["master"][$rek_coa]["debet"]) ? $cekRekening["master"][$rek_coa]["debet"] : 0;
                $master_kredit = isset($cekRekening["master"][$rek_coa]["kredit"]) ? $cekRekening["master"][$rek_coa]["kredit"] : 0;
                cekungu("dd: $detail_debet, dk: $detail_kredit");
                cekungu("md: $master_debet, mk: $master_kredit");

                $selisih_debet = $detail_debet - $master_debet;
                $selisih_kredit = $detail_kredit - $master_kredit;
                $selisih_master = $master_debet - $master_kredit;
                $selisih_detail = $detail_debet - $detail_kredit;

//                $master_debet = 1;
                cekHitam("::: $transaksi_jenis_validate :::");
                switch ($transaksi_jenis_validate) {
                    case "758":
                    case "759":
                    case "5587":
                    case "6687":
                    case "2587":
                    case "2687":
                        break;
                    //transaksi id masuk di rekening master saja atau rekening detail saja
                    case "9999999999":
                        //---------------
                        if ($detail_debet != $detail_kredit) {
                            // sementara disetop, perlu dikirim notif ke telegram
                            cekHitam(":: $detail_debet != $detail_kredit ::");
                            $selisih_detail = $selisih_detail < 0 ? $selisih_detail * -1 : $selisih_detail;
                            if ($selisih_detail > 1) {

                                mati_disini(__LINE__ . " STOP!!! rekening $rek_coa $rek_coa_alias, detail debet tidak sama dengan master debet");
                            }
                        }
                        else {
                            cekHijau("detail debet vs master debet -> sama");
                        }
                        //---------------
                        if ($master_debet != $master_kredit) {
                            // sementara disetop, perlu dikirim notif ke telegram
                            $selisih_master = $selisih_master < 0 ? $selisih_master * -1 : $selisih_master;
                            if ($selisih_master > 1) {

                                mati_disini(__LINE__ . "STOP!!! rekening $rek_coa $rek_coa_alias, detail kredit tidak sama dengan master kredit");
                            }
                        }
                        else {
                            cekHijau("detail kredit vs master kredit -> sama");
                        }
                        //---------------
                        break;

                    //transaksi id masuk di rekening master dan rekening detail
                    default:
                        //---------------
                        if ($detail_debet != $master_debet) {
                            // sementara disetop, perlu dikirim notif ke telegram
                            cekHitam(":: $detail_debet != $master_debet :: $selisih_debet");
                            $selisih_debet = $selisih_debet < 0 ? $selisih_debet * -1 : $selisih_debet;
                            if ($selisih_debet > 1) {

                                mati_disini(__LINE__ . " STOP!!! rekening $rek_coa $rek_coa_alias, detail debet tidak sama dengan master debet");
                            }
                        }
                        else {
                            cekHijau("detail debet vs master debet -> sama");
                        }
                        //---------------
                        if ($detail_kredit != $master_kredit) {
                            // sementara disetop, perlu dikirim notif ke telegram
                            cekHitam(":: $detail_debet != $master_debet :: $selisih_kredit");
                            $selisih_kredit = $selisih_kredit < 0 ? $selisih_kredit * -1 : $selisih_kredit;
                            if ($selisih_kredit > 1) {

                                mati_disini(__LINE__ . " STOP!!! rekening $rek_coa $rek_coa_alias, detail kredit tidak sama dengan master kredit");
                            }
                        }
                        else {
                            cekHijau("detail kredit vs master kredit -> sama");
                        }
                        //---------------
                        break;
                }


            }
        }
    }
    //----------------------------



    public function midValidate_ns($sessionData, $step)
    {
        $cCode = isset($this->cCode) ? $this->cCode : matiHere("cCode " . __METHOD__ . " silahkan diset");

        $fieldMidValidatorRules = isset($this->configUiJenis["shoppingCartFieldMidValidators"]) ? $this->configUiJenis["shoppingCartFieldMidValidators"] : array();
        $fieldMidPairedItemValidatorRules = isset($this->configUiJenis["shoppingCartFieldMidValidatorsPairedItem"]) ? $this->configUiJenis["shoppingCartFieldMidValidatorsPairedItem"] : array();
        $rowMidValidatorRules = isset($this->configUiJenis["shoppingCartRowMidValidators"]) ? $this->configUiJenis["shoppingCartRowMidValidators"] : array();
        $fieldMidComparisonValidatorRules = isset($this->configUiJenis["shoppingCartFieldMidValidatorsComparison"]) ? $this->configUiJenis["shoppingCartFieldMidValidatorsComparison"] : array();
        $elementConfigs = isset($this->configUiJenis['receiptElements']) ? $this->configUiJenis['receiptElements'] : array();
        $relElementConfigs = isset($this->configUiJenis['relativeElements']) ? $this->configUiJenis['relativeElements'] : array();
        $efakturValidatorConfig = isset($this->configUiJenis['efakturValidator'][$step]) ? $this->configUiJenis['efakturValidator'][$step] : array();
        $followupMainNoteValidatorConfig = isset($this->configUiJenis['followupMainNoteValidator'][$step]) ? $this->configUiJenis['followupMainNoteValidator'][$step] : array();
        $paidValidatorConfig = isset($this->configUiJenis['shoppingCartPaidValidators']) ? $this->configUiJenis['shoppingCartPaidValidators'] : array();
        $kekuranganValidate = isset($this->configUiJenis['shoppingCartKekuranganValidate']) ? $this->configUiJenis['shoppingCartKekuranganValidate'] : array();
        $validateClosing = isset($this->configUiJenis['validateClosing'][$step]) ? $this->configUiJenis['validateClosing'][$step] : array();
        $validateClosingKey = isset($this->configUiJenis['validateClosingKey'][$step]) ? $this->configUiJenis['validateClosingKey'][$step] : array();
        $validateClosingExtractedSubItems = isset($this->configUiJenis['validateClosingExtractedSubItems'][$step]) ? $this->configUiJenis['validateClosingExtractedSubItems'][$step] : array();
        //------------------
        $itemPriceValidator = isset($this->configUiJenis["itemPriceValidator"][$step]) ? $this->configUiJenis["itemPriceValidator"][$step] : array();
        //------------------
        $pairedItemBreakDownValidator = isset($this->configUiJenis["shoppingCartPairedItemBreakDownValidator"][$step]) ? $this->configUiJenis["shoppingCartPairedItemBreakDownValidator"][$step] : array();
        //-------------------------
        $serialNumberValidator = isset($this->configUiJenis["serialNumberValidator"][$step]) ? $this->configUiJenis["serialNumberValidator"][$step] : array();
        //-------------------------
        $selectorValidator = isset($this->configUiJenis["selectorValidator"][$step]) ? $this->configUiJenis["selectorValidator"][$step] : array();
        $ppnPersenCheckValidate = isset($this->configUiJenis["ppnPersenCheckValidate"][$step]) ? $this->configUiJenis["ppnPersenCheckValidate"][$step] : array();

//        mati_disini(sizeof($ppnPersenCheckValidate) . " --- " . $this->jenisTr);

        $rawPrevURL = isset($_GET['rawPrev']) ? $_GET['rawPrev'] : "";

        $errMsgs = array();
        $errLines = array();
        $errFields = array();
        $errRows = array();
        if (sizeof($rowMidValidatorRules) > 0) {

            foreach ($rowMidValidatorRules as $field => $label) {
                if (!isset($sessionData[$cCode]['main'][$field])) {
                    $errMsgs[] = "$label is required";

                    $errRows[] = $field;
                }
            }
        }

        if (sizeof($fieldMidPairedItemValidatorRules) > 0) {
            $result = array();
            foreach ($fieldMidPairedItemValidatorRules as $field => $label) {
                if (!isset($sessionData[$cCode]['main'][$field])) {
                    $errMsgs[] = "$field value is required";
                    $errRows[] = $field;
                }
                $result[$field] = isset($sessionData[$cCode]['main'][$field]) ? $sessionData[$cCode]['main'][$field] : 0;
            }

            if (($result['hpp_sumber'] - $result['hpp_target']) > 0.0000000100) {
                $selisih = $result['hpp_sumber'] - $result['hpp_target'];

                $errMsgs[] = "Nilai konversi tidak sama, silahkan cek harga per-unitnya.<br>$selisih";
                $errRows[] = "test";
            }
            elseif (($result['hpp_sumber'] - $result['hpp_target']) < -0.0000000100) {
                $selisih = $result['hpp_sumber'] - $result['hpp_target'];

                $errMsgs[] = "Nilai konversi tidak sama, silahkan cek harga per-unitnya.<br>$selisih";
                $errRows[] = "test";
            }
        }

        if (sizeof($fieldMidValidatorRules) > 0) {

            if (isset($sessionData[$cCode]['items']) && sizeof($sessionData[$cCode]['items']) > 0) {
                foreach ($sessionData[$cCode]['items'] as $xid => $iSpec) {
                    $id = $iSpec['id'];
                    if ((isset($iSpec['disabled']) && $iSpec['disabled'] == "0") || !isset($iSpec['disabled'])) {
                        if (!isset($errFields[$id])) {
                            $errFields[$id] = array();
                        }
                        foreach ($fieldMidValidatorRules as $field => $label) {
                            if (!isset($iSpec[$field])) {
                                $errMsgs[] = "item $label is required";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                            if (!is_numeric($iSpec[$field])) {
                                $errMsgs[] = "item $label must be a valid number";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                            if ($iSpec[$field] < 0.5) {
                                $errMsgs[] = "item $label must be > 0";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                        }
                    }

                }
            }
            if (isset($sessionData[$cCode]['rsltItems']) && sizeof($sessionData[$cCode]['rsltItems']) > 0) {
                foreach ($sessionData[$cCode]['rsltItems'] as $xid => $iSpec) {
                    $id = $iSpec['id'];
                    $name = $iSpec['nama'];
                    if ((isset($iSpec['disabled']) && $iSpec['disabled'] == "0") || !isset($iSpec['disabled'])) {
                        if (!isset($errFields[$id])) {
                            $errFields[$id] = array();
                        }
                        foreach ($fieldMidValidatorRules as $field => $label) {
                            if (!isset($iSpec[$field])) {
                                $errMsgs[] = "item $label $name is required";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                            if (!is_numeric($iSpec[$field])) {
                                $errMsgs[] = "item $label $name must be a valid number";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                            if ($iSpec[$field] < 0.5) {
                                $errMsgs[] = "item $label $name must be > 0";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                        }
                    }

                }
            }

        }

        if (sizeof($fieldMidComparisonValidatorRules) > 0) {
            $result = array();
            $labels = array();
            foreach ($fieldMidComparisonValidatorRules as $field => $label) {
                if (!isset($sessionData[$cCode]['main'][$field])) {
                    $errMsgs[] = "$field value is required";
                    $errRows[] = $field;
                }
                if ($sessionData[$cCode]['main'][$field] < 0) {
                    $errMsgs[] = "$field must be >= 0";
                    $errRows[] = $field;
                }
                $labels[$label] = $field;
                $result[$label] = isset($sessionData[$cCode]['main'][$field]) ? $sessionData[$cCode]['main'][$field] : 0;
            }

            if ($result["sumber"] > $result["target"]) {
                $labelSrc = isset($labels["sumber"]) ? $labels["sumber"] : "";
                $labelTarget = isset($labels["target"]) ? $labels["target"] : "";
                $errMsgs[] = "Nilai $labelSrc lebih besar dari nilai $labelTarget.";
                $errRows[] = "test";
            }

        }

        if (sizeof($elementConfigs) > 0) {
            $hiddenMsg = "<br><span style='color:white;font-size:15px;'>" . get_class($this) . "/" . __FUNCTION__ . "</span>";
            foreach ($elementConfigs as $eName => $aSpec) {
                cekHere("[$eName]");
                if (!isset($sessionData[$cCode]['main_elements'][$eName])) {
                    switch ($eName) {
                        case "elementReference":
                            $fulldate = $sessionData[$cCode]['main']['fulldate'];
                            if ($fulldate <= "2024-08-06") {
                                $validate = false;
                            }
                            else {
                                $validate = true;
                            }
                            break;
                        default:
                            $validate = true;
                            break;
                    }
                    if ((isset($aSpec['noValidate'])) && ($aSpec['noValidate'] == true)) {

                    }
                    else {
                        if ($validate == true) {

                            if (isset($aSpec['labelValidate'])) {
                                $elementMsg = $aSpec['labelValidate'] . " ##";
                            }
                            else {
                                $elementMsg = ($aSpec['label']  . "::ELEMENT ". $eName."harus ditentukan. Silahkan hubungi Admin.");
                            }
                            $errMsgs[] = $elementMsg;
                            echo "<script>";
                            echo "top.document.getElementById('elTitle_$eName').className='box-headers text-red text-left';";
                            echo "</script>";

                        }
                    }
                }
                else {
                    switch ($aSpec['elementType']) {
                        case "dataModel":
                            if (isset($validateReceiveElement[$eName]) && sizeof($validateReceiveElement[$eName])) {
                                $valid = 0;
                                $check = array();
                                foreach ($validateReceiveElement[$eName] as $tLabel => $textWarning) {
                                    if (strlen($sessionData[$cCode]['main'][$eName . '__' . $tLabel]) > 10) {
                                    }
                                    else {
                                        $check[] = $tLabel;
                                    }
                                }
                                if (sizeof($check) > 1) {
                                    $errMsgs[] = ("Harap lengkapi salah satu NPWP/KTP diBillingDetails customer");
                                    foreach ($check as $nLabel) {
                                        $errMsgs[] = ("lengkapi $nLabel!");
                                    }
                                }
                            }


                            if (strlen($sessionData[$cCode]['main_elements'][$eName]['key']) < 0.5) {
                                $errMsgs[] = ("element " . $aSpec['label'] . " must be filled with one entry!");
                                echo "<script>";
                                //                                echo "top.document.getElementById('divel_$eName').style.backgroundColor='#ffff00';";
                                echo "top.document.getElementById('elTitle_$eName').className='box-headers text-red text-left';";
                                echo "</script>";
                            }
                            else {
                                echo "<script>";
                                echo "top.document.getElementById('elTitle_$eName').className='box-headers bg-grey text-left';";
                                echo "</script>";
                            }
                            break;
                        case "dataField":
                            if (strlen($sessionData[$cCode]['main_elements'][$eName]['value']) < 0.5) {
                                if ((isset($aSpec['noValidate'])) && ($aSpec['noValidate'] == true)) {
                                }
                                else {
                                    $errMsgs[] = ($aSpec['label'] . " must be filled with one entry!***");
                                    echo "<script>";
                                    //                                echo "top.document.getElementById('divel_$eName').style.backgroundColor='#ffff00';";
                                    echo "top.document.getElementById('elTitle_$eName').className='box-headers text-red text-left';";
                                    echo "</script>";
                                }

                            }
                            else {
                                echo "<script>";
                                echo "top.document.getElementById('elTitle_$eName').className='box-headers bg-grey text-left';";
                                echo "</script>";
                            }
                            break;
                    }

                }
            }
            if (isset($sessionData[$cCode]['main_elements'])) {

                foreach ($sessionData[$cCode]['main_elements'] as $elName => $elSpec) {
                    //                if(isset($elSpec['noValidate']) && ($elSpec['noValidate']==true)){
                    switch ($elSpec['elementType']) {
                        case "dataField":

                            if (isset($elSpec['value'])) {
                                // arrPrint($elSpec['value']);

                                if (sizeof(blobDecode($elSpec['value'])) == 0) {
                                    $errMsgs[] = ("Baris " . $elSpec['label'] . " belum dipilih. Silahkan dipilih dahulu. (code " . __LINE__ . ") $hiddenMsg");
                                }
                            }
                            else {
                                $errMsgs[] = ("Baris " . $elSpec['label'] . " belum dipilih. Silahkan dipilih dahulu. (code " . __LINE__ . ") $hiddenMsg");
                            }
                            break;

                        case "dataModel":

                            if (isset($elSpec['contents'])) {
                                if (sizeof(blobDecode($elSpec['contents'])) == 0) {
                                    $errMsgs[] = ("Baris " . $elSpec['label'] . " belum dipilih. Silahkan dipilih dahulu. (code " . __LINE__ . ") $hiddenMsg");
                                }
                            }
                            else {
                                $errMsgs[] = ("Baris " . $elSpec['label'] . " belum dipilih. Silahkan dipilih dahulu. (code " . __LINE__ . ") $hiddenMsg");
                            }
                            break;
                    }
                    //                }

                }
            }
            else {
                cekBiru("main_element tidak ada di session @" . __LINE__ . " " . __FILE__);
            }
        }

        if (sizeof($efakturValidatorConfig) > 0) {
            cekUngu("skip faktur " . $sessionData[$cCode]["main"]["skip_faktur"]);
            if ($sessionData[$cCode]["main"]["skip_faktur"] == true) {

            }
            else {
                $enabled = isset($efakturValidatorConfig['enabled']) ? $efakturValidatorConfig['enabled'] : false;
                if ($enabled == true) {
                    $kolom = isset($efakturValidatorConfig['kolom']) ? $efakturValidatorConfig['kolom'] : array();
                    $source = isset($efakturValidatorConfig['source']) ? $efakturValidatorConfig['source'] : array();
                    $gateSource = $efakturValidatorConfig["gateSource"];
                    $pakai_ini = 0;
                    if ($pakai_ini == 1) {
                        if (sizeof($kolom) > 0) {
                            foreach ($source as $vSrc) {
                                if ($sessionData[$cCode]['main'][$vSrc] > 0) {
                                    foreach ($kolom as $key => $label) {
                                        if (!isset($sessionData[$cCode]['main'][$key])) {
                                            $errMsgs[] = $label;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if (isset($sessionData[$cCode][$gateSource]) && sizeof($sessionData[$cCode][$gateSource]) > 0) {
                        $ppn_final_total = 0;
                        $nom = 0;
                        foreach ($sessionData[$cCode][$gateSource] as $ii => $iiSpec) {
                            $nom++;
                            $noii = $ii + 1;
                            $ppn_final = isset($iiSpec["ppn_final"]) ? $iiSpec["ppn_final"] : 0;
                            if ($ppn_final == 0) {
                                $msg = "Nilai PPN pada formulir $nom sebesar 0. Silahkan diisi dengan benar atau didelete bila tidak diperlukan. code: " . __LINE__;
                                mati_disini($msg);
                            }
                            $ppn_final_total += $ppn_final;
                            //----
                            if (sizeof($kolom) > 0) {
                                foreach ($kolom as $key => $label) {
                                    if (!isset($iiSpec[$key])) {
                                        $errMsgs[] = $label . "($nom)";
                                    }
                                    elseif (isset($iiSpec[$key]) && ($iiSpec[$key] == NULL)) {
                                        $errMsgs[] = $label . "($nom)";
                                    }
                                }
                            }
                        }
                        $selisih = $ppn_final_total - $sessionData[$cCode]["main"]["ppn_netto"];
                        $selisih = ($selisih < 0) ? ($selisih * -1) : $selisih;
                        if ($selisih > 100) {
//                            $msg = "Selisih nilai PPN melebihi batasan. code: " . __LINE__;
                            $msg = "Terdapat selisih pada nilai PPN dari tagihan dengan input faktur. Silahkan dikoreksi lagi. code: " . __LINE__;
                            mati_disini($msg);
                        }
                    }
                    else {
                        cekHitam("tidak ada gerbang $gateSource, berarti masih mode lama");
                        if (sizeof($kolom) > 0) {
                            foreach ($source as $vSrc) {
                                if ($sessionData[$cCode]['main'][$vSrc] > 0) {
                                    foreach ($kolom as $key => $label) {
                                        if (!isset($sessionData[$cCode]['main'][$key])) {
                                            $errMsgs[] = $label;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

        }

        if (sizeof($followupMainNoteValidatorConfig) > 0) {
            $enabled = isset($followupMainNoteValidatorConfig['enabled']) ? $followupMainNoteValidatorConfig['enabled'] : false;
            if ($enabled == true) {
                $kolom = isset($followupMainNoteValidatorConfig['kolom']) ? $followupMainNoteValidatorConfig['kolom'] : array();
                $source = isset($followupMainNoteValidatorConfig['source']) ? $followupMainNoteValidatorConfig['source'] : array();
                $maximal_karakter = isset($followupMainNoteValidatorConfig['maximal_karakter']) ? $followupMainNoteValidatorConfig['maximal_karakter'] : NULL;
                $maximal_karakter_label = str_replace("maximal_karakter", $maximal_karakter, $followupMainNoteValidatorConfig['maximal_karakter_label']);
                if (sizeof($kolom) > 0) {
                    foreach ($source as $vSrc) {
                        foreach ($kolom as $key => $label) {
                            if (!isset($sessionData[$cCode]['main'][$key])) {
                                $errMsgs[] = $label;
                            }
                            else {
                                if (strlen($sessionData[$cCode]['main'][$key]) < 3) {
                                    $errMsgs[] = $label;
                                }
                                if ($maximal_karakter != NULL) {
                                    if (strlen($sessionData[$cCode]['main'][$key]) > $maximal_karakter) {
                                        $errMsgs[] = $maximal_karakter_label;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        if (sizeof($paidValidatorConfig) > 0) {
            foreach ($paidValidatorConfig as $pKey => $pVal) {
                if ($sessionData[$cCode]['main'][$pKey] <= 0) {
                    $errMsgs[] = $pVal;
                }
            }
        }
        //--------------
        if (sizeof($kekuranganValidate) > 0) {
            foreach ($kekuranganValidate as $key => $val) {
                switch ($key) {
                    case "source":
                        if (isset($sessionData[$cCode]['items9_sum'])) {
                            foreach ($sessionData[$cCode]['items9_sum'] as $bSpec) {
                                if ($bSpec['qty_kekurangan'] > 0) {
                                    $bName = htmlspecialchars($bSpec['nama_bahan']);
                                    $qty = $bSpec['qty_kekurangan'];
                                    $satuan = $bSpec['satuan'];
                                    $msg = "stok $bName kekurangan $qty $satuan.";
                                    $errMsgs[] = $msg;
                                }

                            }
                        }
                        break;
                    default:
                        break;
                }
            }
        }
        //--------------
        if (sizeof($validateClosing) > 0) {
            if ($sessionData[$cCode]['main']['garansi_nilai'] > 0) {
                foreach ($validateClosing as $key => $value) {
                    if (!array_key_exists($key, $sessionData[$cCode]['main'])) {
                        $msg = $value;
                        $errMsgs[] = $msg;
                    }
                }
            }
        }
        // $validateClosingExtractedSubItems
        if (sizeof($validateClosingExtractedSubItems) > 0) {
            foreach ($validateClosingExtractedSubItems as $key => $value) {
                if (!array_key_exists($key, $sessionData[$cCode]['main'])) {
                    $msg = $value;
                    $errMsgs[] = $msg;
                }
                elseif (isset($sessionData[$cCode]['main'][$key]) && ($sessionData[$cCode]['main'][$key] == 0)) {
                    $msg = $value;
                    $errMsgs[] = $msg;
                }
            }
        }
        //--------------
        if (isset($itemPriceValidator["enabled"]) && ($itemPriceValidator["enabled"] == true)) {

            // hpp, gerbang items
            // harga jual lama, gerbang items
            // harga jual baru, gerbang items4_sum
            if (isset($sessionData[$cCode]['items'])) {
                $updateHarga = array();
                if (isset($sessionData[$cCode]['items4_sum']) && (sizeof($sessionData[$cCode]['items4_sum']) > 0)) {
                    foreach ($sessionData[$cCode]['items4_sum'] as $pID => $iSpec) {
                        $updateHarga[$pID] = array(
                            "jual_baru" => isset($iSpec["jual_baru"]) ? $iSpec["jual_baru"] : 0,
                            "hpp_nppv" => isset($iSpec["hpp_nppv"]) ? $iSpec["hpp_nppv"] : 0,
                        );
                    }
                }
//                arrPrintWebs($updateHarga);
//                cekMerah(__LINE__);
                foreach ($sessionData[$cCode]['items'] as $pID => $iSpec) {
                    $nama = isset($iSpec['nama']) ? $iSpec['nama'] : 0;
                    $hpp = isset($iSpec['hpp']) ? $iSpec['hpp'] : 0;
                    $harga_list = isset($iSpec['jual']) ? $iSpec['jual'] : 0;
                    $harga_jual_baru = isset($updateHarga[$pID]['jual_baru']) ? $updateHarga[$pID]['jual_baru'] : 0;
                    $harga_tandas = isset($updateHarga[$pID]['hpp_nppv']) ? $updateHarga[$pID]['hpp_nppv'] : 0;

                    // ADA SETTING HARGA JUAL BARU
                    if (isset($updateHarga[$pID])) {
                        //harga beli 10.000 > harga jual baru 1.000, tolak
                        if (($hpp > $harga_jual_baru)) {
                            $msg = "Harga jual baru $nama lebih kecil dari harga beli. Silahkan dikoreksi di halaman ini. Code: " . __LINE__;
                            mati_disini($msg);
                        }

                        if ($harga_tandas < 1) {
                            $msg = "Harga tandas $nama belum diisikan. Silahkan dikoreksi di halaman ini. Code: " . __LINE__;
                            mati_disini($msg);
                        }
                    }
                    // TIDAK ADA SETTING HARGA JUAL BARU
                    else {
                        //harga beli 10.000 > harga jual 1.000, tolak
                        if (($hpp > $harga_list)) {
                            $msg = "Harga jual lama $nama lebih kecil dari harga beli. Silahkan dikoreksi dengan mengisi harga jual baru di halaman ini. Code: " . __LINE__;
                            mati_disini($msg);
                        }
                        //harga jual lama (0), harga jual baru (0), tolak
                        elseif (($harga_jual_baru == 0) && ($harga_list == 0)) {
                            $msg = "Harga jual $nama belum diisikan. Silahkan diisikan dengan mengisi harga jual baru di halaman ini. Code: " . __LINE__;
                            mati_disini($msg);
                        }

                        if ($harga_tandas < 1) {
                            $msg = "Harga tandas $nama belum diisikan. Silahkan dikoreksi di halaman ini. Code: " . __LINE__;
                            mati_disini($msg);
                        }
                    }


                }
            }
        }
        // konversi breakdown
        if (sizeof($pairedItemBreakDownValidator) > 0) {
            if (isset($pairedItemBreakDownValidator["enabled"]) && ($pairedItemBreakDownValidator["enabled"] == true)) {
                if (isset($sessionData[$cCode]["items"]) && sizeof($sessionData[$cCode]['items']) > 0) {
                    foreach ($sessionData[$cCode]["items"] as $id => $spec) {
                        $produk_nama_items = $spec["nama"];
                        $sub_hpp_avg_items = $spec["sub_hpp_avg"];
                        $sub_hpp_avg_items4 = 0;
                        if (isset($sessionData[$cCode]["items4"][$id])) {
                            foreach ($sessionData[$cCode]["items4"][$id] as $sid => $iiSpec) {
                                $sub_hpp_avg_items4 += isset($iiSpec["sub_hpp_avg"]) ? $iiSpec["sub_hpp_avg"] : 0;
                            }
                        }
                        $selisih = $sub_hpp_avg_items4 - $sub_hpp_avg_items;
                        //            $selisih = ($selisih < 0) ? ($selisih *-1) : $selisih;
                        if ($selisih > 1) {
                            $msg = "akumulasi hpp hasil konversi produk $produk_nama_items melebihi hpp rata-rata. silahkan diperiksa lagi. code: " . __LINE__;
//                            $msg .= "[$sub_hpp_avg_items4] [$sub_hpp_avg_items]";
                            mati_disini($msg);
                        }
                    }
                }
            }
        }
        //--------------
        if (sizeof($serialNumberValidator) > 0) {
            if (isset($serialNumberValidator["enabled"]) && ($serialNumberValidator["enabled"] == true)) {
                cekHitam("MASUK VALIDASI PRODUK YANG DIDEFINE SERIAL *(REGULER)");
                if (isset($sessionData[$cCode]['items']) && sizeof($sessionData[$cCode]['items']) > 0) {
                    $gateSource = $serialNumberValidator["source"];
                    $labelSource = $serialNumberValidator["label"];
                    foreach ($sessionData[$cCode]['items'] as $xid => $iSpec) {
                        $id = $iSpec['id'];
                        $p_nama = $iSpec['nama'];
                        $jml_serial = $iSpec['jml_serial'] * $iSpec["jml"];
                        if ($jml_serial > 0) {
                            $jml_serial_items2 = count($sessionData[$cCode][$gateSource][$id]);
                            $jml_serial_items2_qty = $jml_serial_items2 * $iSpec["jml"];
                            $serial_produk = 0;
                            if (isset($sessionData[$cCode][$gateSource][$id]) && (sizeof($sessionData[$cCode][$gateSource][$id]) > 0)) {
                                foreach ($sessionData[$cCode][$gateSource][$id] as $sku => $spec) {
                                    $jml_sku_serial = count($spec);
                                    $serial_produk += $jml_sku_serial;
//                                    cekHere(":: $jml_sku_serial :: $serial_produk ::");
                                }
                            }
                            if ($serial_produk != $jml_serial_items2_qty) {
                                $labelSource_new = str_replace("{produk_nama}", "$p_nama", $labelSource);
                                $errMsgs[] = $labelSource_new;
                            }
                        }
                    }
                }
                cekHitam("MASUK VALIDASI PRODUK YANG DIDEFINE SERIAL *(PAKET)");
                if (isset($sessionData[$cCode]['items6']) && sizeof($sessionData[$cCode]['items6']) > 0) {
                    $labelSource = $serialNumberValidator["label"];
                    foreach ($sessionData[$cCode]['items6'] as $xid => $iSpec) {
                        foreach ($iSpec as $xiid => $iiSpec) {
                            $p_nama = $iiSpec['nama'];
                            $jml_serial = $iiSpec['jml_serial'] * $iiSpec["jml"];
                            $gateSource = "items7";
                            if ($iiSpec["jml_serial"] > 0) {
                                $jml_serial_items2 = count($sessionData[$cCode][$gateSource][$xid][$xiid]);
                                $jml_serial_items2_qty = $jml_serial_items2 * $iiSpec["jml"];
                                $serial_produk = 0;
                                if (isset($sessionData[$cCode][$gateSource][$xid][$xiid]) && (sizeof($sessionData[$cCode][$gateSource][$xid][$xiid]) > 0)) {
                                    foreach ($sessionData[$cCode][$gateSource][$xid][$xiid] as $sku => $spec) {
                                        $jml_sku_serial = count($spec);
                                        $serial_produk += $jml_sku_serial;
                                    }
                                }
                                cekUngu("## $serial_produk == $jml_serial_items2_qty ##");
                                if ($serial_produk != $jml_serial_items2_qty) {
                                    $labelSource_new = str_replace("{produk_nama}", "$p_nama", $labelSource);
//                                    $errMsgs[] = $labelSource_new;
                                }
                            }
                        }
                    }
                }

            }
            if (isset($serialNumberValidator["scanCheckerEnabled"]) && ($serialNumberValidator["scanCheckerEnabled"] == true)) {
                cekHitam("VALIDASI jml_scan masing-masing barang");
                if (isset($sessionData[$cCode]['items']) && sizeof($sessionData[$cCode]['items']) > 0) {
                    $labelSource = $serialNumberValidator["scanCheckerLabel"];
                    foreach ($sessionData[$cCode]['items'] as $xid => $iSpec) {
                        if ($iSpec["jml_serial"] == 0) {
                            if ($iSpec["kategori_id"] != 4) {
                                $p_nama = $iSpec['nama'];
                                $jml_scan_cek = isset($iSpec["jml_scan"]) ? $iSpec["jml_scan"] : 0;
                                $jml = isset($iSpec["jml"]) ? $iSpec["jml"] : 0;
                                if ($jml != $jml_scan_cek) {
                                    $labelSource_new = str_replace("{produk_nama}", "$p_nama", $labelSource);
                                    $errMsgs[] = $labelSource_new;
                                }
                            }
                        }
                    }
                }
                if (isset($sessionData[$cCode]['items6']) && sizeof($sessionData[$cCode]['items6']) > 0) {
                    foreach ($sessionData[$cCode]['items6'] as $xid => $iSpec) {
                        foreach ($iSpec as $xiid => $iiSpec) {
                            if ($iiSpec["jml_serial"] == 0) {
                                $p_nama = $iiSpec['nama'];
                                $jml_scan_cek = isset($iiSpec["jml_scan"]) ? $iiSpec["jml_scan"] : 0;
                                $jml = isset($iiSpec["jml"]) ? $iiSpec["jml"] : 0;
                                if ($jml != $jml_scan_cek) {
                                    $labelSource_new = str_replace("{produk_nama}", "$p_nama", $labelSource);
                                    $errMsgs[] = $labelSource_new;
                                }
                            }
                        }
                    }
                }
            }
        }
        //----------------------
        $pakai_ini = 0;
        if ($pakai_ini == 1) {
            if (sizeof($selectorValidator) > 0) {
                $selKosong = array();
                foreach ($selectorValidator as $ii => $spec) {
                    if (isset($spec["reference"])) {
                        $reference = isset($sessionData[$cCode]["main"][$spec["reference"]]) ? $sessionData[$cCode]["main"][$spec["reference"]] : 0;
                        if ($reference > 0) {
                            foreach ($spec["keys"] as $key) {
                                if (!isset($sessionData[$cCode]['main'][$key])) {
                                    $selKosong[$ii] = $key;
                                }
                                elseif (isset($sessionData[$cCode]['main'][$key]) && ($sessionData[$cCode]['main'][$key] == null)) {
                                    $selKosong[$ii] = $key;
                                }
                            }
                        }
                    }
                    else {
                        foreach ($spec["keys"] as $key) {
                            if (!isset($sessionData[$cCode]['main'][$key])) {
                                $selKosong[$ii] = $key;
                            }
                            elseif (isset($sessionData[$cCode]['main'][$key]) && ($sessionData[$cCode]['main'][$key] == null)) {
                                $selKosong[$ii] = $key;
                            }
                        }
                    }
                }
                if (sizeof($selKosong) > 0) {
                    foreach ($selKosong as $iii => $x) {
                        $errMsgs[] = $selectorValidator[$iii]["label"];
                    }
                }
            }
        }
        //----------------------
        if (sizeof($ppnPersenCheckValidate) > 0) {
            if (isset($ppnPersenCheckValidate["enabled"]) && ($ppnPersenCheckValidate["enabled"] == true)) {
                $key_cek = $ppnPersenCheckValidate["key"];
                $sessionCek = $ppnPersenCheckValidate["sessionCek"];
                $result_key_cek = (isset($sessionData[$cCode]["main"][$key_cek]) && ($sessionData[$cCode]["main"][$key_cek] > 0)) ? $sessionData[$cCode]["main"][$key_cek] : 0;
                $result_sessionCek = (isset($sessionData[$cCode]["main"][$sessionCek]) && ($sessionData[$cCode]["main"][$sessionCek] > 0)) ? $sessionData[$cCode]["main"][$sessionCek] : 0;
                if ($result_key_cek == 0) {
                    // tanpa ppn
                    if ($result_sessionCek > 0) {
                        // warning
                        $label_warning = $ppnPersenCheckValidate["labelWarning"][0];
                        $errMsgs[] = $label_warning;
                    }
                }
                else {
                    // dengan ppn
                    if ($result_sessionCek <= 0) {
                        // warning
                        $label_warning = $ppnPersenCheckValidate["labelWarning"][1];
                        $errMsgs[] = $label_warning;
                    }
                }
            }
        }
        //----------------------

        if (sizeof($errMsgs) > 0) {
            $sessionData['errMsg'] = implode("<br>", $errMsgs);

            if (sizeof($errLines) > 0) {
                $sessionData['errLines'] = $errLines;
            }
            if (sizeof($errFields) > 0) {
                $sessionData['errFields'] = $errFields;
            }
            arrPrintKuning($sessionData['errMsg']);
            cekMerah(__LINE__);
            echo lgShowAlert($sessionData['errMsg']);
            die();
        }

    }

    public function unionValidate_ns($sessionData)
    {
        $cCode = isset($this->cCode) ? $this->cCode : matiHere("cCode " . __METHOD__ . " silahkan diset");
        $unionValidatorRules = isset($this->configUiJenis["shoppingCartUnionValidators"]) ? $this->configUiJenis["shoppingCartUnionValidators"] : array();


        $rawPrevURL = isset($_GET['rawPrev']) ? $_GET['rawPrev'] : "";
        //matiHEre();

        $errMsgs = array();
        $errLines = array();
        $errFields = array();
        $errRows = array();


        if (sizeof($unionValidatorRules) > 0) {
            $result = array();
            $validateFields = 0;
            foreach ($unionValidatorRules as $uSpec) {

                foreach ($uSpec as $field => $label) {
                    $validateFields++;
                    if (!isset($sessionData[$cCode]['main'][$field])) {
                        $result[$field] = "$label value is required";
                    }
                }
                //                $result[$field] = isset($sessionData[$cCode]['main'][$field]) ? $sessionData[$cCode]['main'][$field] : 0;
            }
            //            cekHitam(sizeof($result));
            //            matiHEre($validateFields);
            if (sizeof($result) == $validateFields) {
                foreach ($result as $field => $label) {
                    $errMsgs[] = $label;
                    $errRows[] = $field;
                }
            }

        }

        //        matiHere(sizeof($result) ." ==". $validateFields);

        if (sizeof($errMsgs) > 0) {
            $sessionData['errMsg'] = implode("<br>", $errMsgs);
            //            print_r($sessionData['errMsg']);
            //            echo "<script>";
            //            echo "top.getData('" . base_url() . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id','shopping_cart');";
            //            echo "</script>";
            if (sizeof($errLines) > 0) {
                $sessionData['errLines'] = $errLines;
            }
            if (sizeof($errFields) > 0) {
                $sessionData['errFields'] = $errFields;
            }

            echo lgShowAlert($sessionData['errMsg']);
            die();
        }
        //        else {
        //
        //            $actionTarget = "top.BootstrapDialog.show(                                   {
        //                                       title:'preview',
        //                                        message: " . '$' . "('<div></div>').load('" . base_url() . "Transaksi/preview/" . $this->jenisTr . "?rawPrev=$rawPrevURL'),
        //                                        draggable:false,
        //                                        size:top.BootstrapDialog.SIZE_WIDE,
        //                                        type:top.BootstrapDialog.TYPE_SUCCESS,
        //                                        closable:true,
        //                                        }
        //                                        );";
        //
        //            echo "<html>";
        //            echo "<head>";
        //            echo "<script src=\"" . cdn_suport()."AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
        //            echo "</head>";
        //            echo "<body onload=\"$actionTarget\">";
        //            echo "</body>";
        //
        //        }
    }


}
