<?php

require_once "Modul_Controller.php";

class _processSelectNota extends Modul_Controller
{


    public function __construct()
    {
        parent::__construct();
        // $this->jenisTr = $this->uri->segment(4);
        $cCode = $this->cCode;
        if (!isset($_SESSION[$cCode]['main']['refIDs'])) {
            $_SESSION[$cCode]['main']['refIDs'] = array();
        }
        if (!isset($_SESSION[$cCode]['main']['refs'])) {
            $_SESSION[$cCode]['main']['refs'] = "";
        }
        if (!isset($_SESSION[$cCode]['main']['refs_intext'])) {
            $_SESSION[$cCode]['main']['refs_intext'] = "";
        }
        $this->validateJenisTr = array(
            "771", "1771", //"475", "476", "477",
        );
    }

    public function select()
    {

        foreach ($_GET as $kg => $item) {
            // get dari checklist, checkbox...
            if (isset($_GET["state"])) {
                if (blobDecode($item) == NULL) {
                    $valss = NULL;//$item;
                }
                else {
                    $valss = blobDecode($item);
                }
            }
            else {
                // BUKAN get dari checklist, checkbox...
                $valss = $item;
            }
//            $_GET[$kg] = ($kg == "state") ? $item : blobDecode($item);
            $_GET[$kg] = ($kg == "state") ? $item : $valss;
        }
//arrPrint($_GET);
//        matiHEre(__LINE__ . "::: under maintenance::: " . $_GET["dateFaktur"] . " ==== " . $_GET["eFaktur"]);

        $reload_shoppingcart = 1;
        $jenisTr = $this->jenisTr;
        $cCode = $this->cCode;
        $trID = $transaksi_id_selected = $id = $_GET['transaksi_id'];
        $transaksi_jenis_selected = $_GET['jenis'];

        $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $componentAssConfig = isset($this->configUi[$this->jenisTr]['componentsAss']) ? $this->configUi[$this->jenisTr]['componentsAss'] : array();
        $relativeComNameDetails = isset($this->configUi[$this->jenisTr]['relativeComNameDetails']) ? $this->configUi[$this->jenisTr]['relativeComNameDetails'] : array();
        $isRadioSelect = isset($this->configUi[$this->jenisTr]['isPaymentRadioSelect']) ? $this->configUi[$this->jenisTr]['isPaymentRadioSelect'] : false;
        $trLabel = isset($this->configUi[$this->jenisTr]['label']) ? $this->configUi[$this->jenisTr]['label'] : "";
        $exchangeValidate = isset($this->configUi[$this->jenisTr]['exchangeValidate']) ? $this->configUi[$this->jenisTr]['exchangeValidate'] : array();
        $referenceGateFields = isset($this->configUi[$this->jenisTr]['referenceGateFields']) ? $this->configUi[$this->jenisTr]['referenceGateFields'] : array();
        $shopingCartPairProdukSrc = isset($this->configUi[$this->jenisTr]['shopingCartPairProdukSrc']) ? $this->configUi[$this->jenisTr]['shopingCartPairProdukSrc'] : array();
        $shopingCartPairProdukSrcException = isset($this->configUi[$this->jenisTr]['shopingCartPairProdukSrcException']) ? $this->configUi[$this->jenisTr]['shopingCartPairProdukSrcException'] : array();
        $shopingCartPairProdukSrc_gate = isset($this->configUi[$this->jenisTr]['shopingCartPairProdukGate']) ? $this->configUi[$this->jenisTr]['shopingCartPairProdukGate'] : array();
        $shopingCartPairProdukGateDpp = isset($this->configUi[$this->jenisTr]['shopingCartPairProdukGateDpp']) ? $this->configUi[$this->jenisTr]['shopingCartPairProdukGateDpp'] : false;
        $selectorNotaLocker = isset($this->configUi[$this->jenisTr]['selectorNotaLocker']) ? $this->configUi[$this->jenisTr]['selectorNotaLocker'] : array();
        $shoppingCartReferenceToItem = isset($this->configUi[$this->jenisTr]['shoppingCartReferenceToItem']['enabled']) ? $this->configUi[$this->jenisTr]['shoppingCartReferenceToItem']['enabled'] : false;
        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $ppnCekDefaultValue = isset($this->configUi[$this->jenisTr]['ppnCekDefaultValue']) ? $this->configUi[$this->jenisTr]['ppnCekDefaultValue'] : array();
        $shopingCartPairProdukGateCopy = isset($this->configUi[$this->jenisTr]['shopingCartPairProdukGateCopy']) ? $this->configUi[$this->jenisTr]['shopingCartPairProdukGateCopy'] : false;
        $shopingCartForceItemSelected = isset($this->configUi[$this->jenisTr]['shopingCartForceItemSelected']) ? $this->configUi[$this->jenisTr]['shopingCartForceItemSelected'] : array();
        $shopingCartEmbededTaxItem = isset($this->configUi[$this->jenisTr]['shopingCartEmbededTaxItem']) ? $this->configUi[$this->jenisTr]['shopingCartEmbededTaxItem'] : array();
        $shopingCartEmbededGate = isset($shopingCartEmbededTaxItem['gateEmbeded']) ? $shopingCartEmbededTaxItem['gateEmbeded'] : array();
        if (in_array($this->jenisTr, $this->validateJenisTr)) {
            if (isset($_GET['extern_label2']) && ($_GET['extern_label2'] == null)) {
                die(lgShowAlert("$trLabel gagal dilanjutkan, karena jenis biaya tidak ditentukan saat request $trLabel."));
            }
        }


        //---------------------------------------
        if ((sizeof($selectorNotaLocker) > 0)) {
            if (isset($selectorNotaLocker["enabled"]) && ($selectorNotaLocker["enabled"] == true)) {
                $this->load->model("Mdls/MdlLockerTransaksi");
                $lt = New MdlLockerTransaksi();
                $lt->addFilter("transaksi_id='$trID'");
                $lt->addFilter("state='hold'");
                $lt->addFilter("jumlah='1'");
                $ltTmp = $lt->lookupAll()->result();
                // showLast_query("pink");
                $allowedActionByHold = NULL;
                if (sizeof($ltTmp) > 0) {
                    if ($ltTmp[0]->oleh_id == my_id()) {
                        $allowedAction = true;
                        // cekUngu("transaksi HOLD by yang login, bisa dilanjutkan");
                    }
                    else {
                        $allowedAction = false;
                        $allowedActionByHold = $ltTmp[0]->oleh_nama;
                        // cekUngu("transaksi HOLD by orang lain, TIDAK BISA dilanjutkan");
                    }
                }
                if ($allowedActionByHold != NULL) {
                    $msg = "Nota yang anda pilih sedang ditindaklanjuti oleh $allowedActionByHold. Silahkan pilih nota lainnya. code: " . __LINE__;
                    mati_disini($msg);
                }
            }
        }
        //---------------------------------------


        if ($isRadioSelect == true) {
            $detailResetList = array(
                "items",
                "items2",
                "tableIn_detail",
                "tableIn_detail2",
                "tableIn_detail_values",
                "tableIn_detail2_sum",
                "tableIn_detail_values2_sum",
                "items2_sum",
                "main",
                "main_elements",

            );
            foreach ($detailResetList as $sSName) {
                $_SESSION[$cCode][$sSName] = null;
                unset($_SESSION[$cCode][$sSName]);
            }

            $_SESSION[$cCode]['main']['refIDs'] = null;
            unset($_SESSION[$cCode]['main']['refIDs']);

            if (sizeof($_SESSION[$cCode]['items']) > 0) {

            }
            else {
                $mainValueInjector = isset($this->configUi[$this->jenisTr]['mainValueInjectors']) ? $this->configUi[$this->jenisTr]['mainValueInjectors'] : array();
                if (sizeof($mainValueInjector) > 0) {
                    foreach ($mainValueInjector as $key => $val) {
                        $_SESSION[$cCode]['main'][$val] = null;
                        unset($_SESSION[$cCode]['main'][$val]);
                    }
                }
            }

            if (isset($_SESSION[$cCode]['items2'])) {
                $_SESSION[$cCode]['items2'] = null;
                unset($_SESSION[$cCode]['items2']);
            }

        }

        $initMain = array(
            "pihakID" => $_GET['extern_id'],
            "pihakName" => $_GET['extern_nama'],
            "valasDetails" => $_GET['valas_id'],
            "pihakMainName" => isset($_GET['extern_label2']) && sizeof($_GET['extern_label2']) > 0 ? $_GET['extern_label2'] : "",
            "other" => isset($_GET['extern_nilai4']) && sizeof($_GET['extern_nilai4']) > 0 ? $_GET['extern_nilai4'] : "",
            "relativeComName" => sizeof($relativeComNameDetails) > 0 ? $relativeComNameDetails[$_GET['extern_label2']] : "",
            "pph_23" => isset($_GET['pph_23']) ? $_GET['pph_23'] : 0,
            "terbayar_pph23" => isset($_GET['terbayar_pph23']) ? $_GET['terbayar_pph23'] : 0,
            "pphGate" => isset($_GET['extern_jenis']) && sizeof($_GET['extern_jenis']) > 0 ? $_GET['extern_jenis'] : "",
            "pphGateLabel" => isset($_GET['extern2_nama']) && sizeof($_GET['extern2_nama']) > 0 ? $_GET['extern2_nama'] : "",
            "pairPihakName" => isset($_GET['extern2_nama']) && sizeof($_GET['extern2_nama']) > 0 ? $_GET['extern2_nama'] : "",
            "pairPihakID" => isset($_GET['extern2_id']) && sizeof($_GET['extern2_id']) > 0 ? $_GET['extern2_id'] : "",
            "pphGateId" => isset($_GET['extern2_id']) && sizeof($_GET['extern2_id']) > 0 ? $_GET['extern2_id'] : "",
//            "ppnTransaksi" => "",
            "pphGateLabel_3" => isset($_GET['extern3_nama']) && sizeof($_GET['extern3_nama']) > 0 ? $_GET['extern3_nama'] : "",
            "pairPihakName_3" => isset($_GET['extern3_nama']) && sizeof($_GET['extern3_nama']) > 0 ? $_GET['extern3_nama'] : "",
            "pairPihakID_3" => isset($_GET['extern3_id']) && sizeof($_GET['extern3_id']) > 0 ? $_GET['extern3_id'] : "",
            "pphGateId_3" => isset($_GET['extern3_id']) && sizeof($_GET['extern3_id']) > 0 ? $_GET['extern3_id'] : "",
            "pphGateLabel_4" => isset($_GET['extern4_nama']) && sizeof($_GET['extern4_nama']) > 0 ? $_GET['extern4_nama'] : "",
            "pairPihakName_4" => isset($_GET['extern4_nama']) && sizeof($_GET['extern4_nama']) > 0 ? $_GET['extern4_nama'] : "",
            "pairPihakID_4" => isset($_GET['extern4_id']) && sizeof($_GET['extern4_id']) > 0 ? $_GET['extern4_id'] : "",
            "pphGateId_4" => isset($_GET['extern4_id']) && sizeof($_GET['extern4_id']) > 0 ? $_GET['extern4_id'] : "",
        );
//arrPrint($_GET);
//        matiHere();
        foreach ($initMain as $key => $src) {
            $_SESSION[$cCode]['main'][$key] = $src;
        }
        foreach ($_GET as $get_key => $get_val) {
            $new_get_key = str_replace("amp;", "", $get_key);
            $row[$new_get_key] = $get_val;
        }

        if (sizeof($exchangeValidate) > 0) {
            if (isset($exchangeValidate['enabled']) && ($exchangeValidate['enabled'] == true)) {
                $valasID = $row['valas_id'];
                if (isset($_SESSION[$cCode]['items'])) {
                    foreach ($_SESSION[$cCode]['items'] as $itemSpec) {
                        if ($valasID != $itemSpec['valas_id']) {
                            $label_err = $exchangeValidate['label'];
                            mati_disini($label_err . " || LINE: " . __LINE__);
                        }
                    }
                }
            }
        }

        //region detector beda
        if ($this->jenisTr == "462") {
            //cekrelasi um
            if (count($_SESSION[$cCode]['items']) == 0) {
                $tempUm = $this->validateUangMuka($_GET["extern_id"], $_GET["transaksi_ref_id_po"]);
                if (count($tempUm) > 0) {
                    if (isset($tempUm["um"])) {
                        $_SESSION[$cCode]["main"]["validate_multi"] = "notallowed";
                    }
                    if (isset($tempUm["um_titipan"])) {
                        $_SESSION[$cCode]["main"]["validate_multi"] = "notallowed";
                    }
                }
                else {
                    $_SESSION[$cCode]["main"]["validate_multi"] = "allowed";
                }

            }

        }
        if (count($_SESSION[$cCode]['items']) > 0) {

            if ($this->jenisTr == "462") {
                $curentRelasiPo = $_SESSION[$cCode]['main']["transaksi_ref_id_po"];


                $state = $_GET["state"];
                $opt_id = "opt" . $_GET["transaksi_id"];
                $link_allow_multi = MODUL_PATH . "" . get_class($this) . "/allowMultiNota/$jenisTr";

                if ($_GET["transaksi_ref_id_po"] != $curentRelasiPo && $_SESSION[$cCode]["main"]["allow_multi"] == 0) {

                    $alert_label = isset($this->configUi[$this->jenisTr]['shopingCartAlert']["referensi_po"]) ? $this->configUi[$this->jenisTr]['shopingCartAlert']["referensi_po"] : "follow up 2/ lebih transaksi. Lanjutkan?";
                    $alert_label2 = isset($this->configUi[$this->jenisTr]['shopingCartAlert']["referensi_um"]) ? $this->configUi[$this->jenisTr]['shopingCartAlert']["referensi_um"] : "Tidak diijinkan";


                    //cek punya uang muka/relasi titipan po
                    $allowmulti_nota = false;
                    if (isset($_SESSION[$cCode]["main"]["validate_multi"]) && $_SESSION[$cCode]["main"]["validate_multi"] == "allowed") {
                        $tempValidateUm = $this->validateuangMuka($_GET["extern_id"], $_GET["transaksi_ref_id_po"]);
                        if (count($tempValidateUm) == 0) {
                            $allowmulti_nota = true;
                        }

                    }
                    else {
                        $linkOpenRealsi = base_url() . "" . "kas/Create/index/4644";
                        $linkOpenReset = MODUL_PATH . "" . "_shoppingCart/reset/462?noreload=1";
                    }

                    if ($allowmulti_nota) {
                        $alert = "<script>
        top.swal({
            title: '!! PERHATIAN !!',
            html: '$alert_label',
            type: 'warning',
            showConfirmButton: false, // Menonaktifkan tombol confirm
            showCancelButton: false, // Menonaktifkan tombol cancel
            allowOutsideClick: false,
            onOpen: function() {
                // Membuat tombol manual setelah swal terbuka
                const modalContent = top.swal.getContent();
top.close_holdon();
                // Membuat div untuk tombol manual
                const customButtons = document.createElement('div');
                customButtons.classList.add('custom-buttons');
                customButtons.innerHTML = `
                    <div style='margin-top: 55px;'>
                        <button id='btn-yes' class='btn btn-lg btn-primary btn-yes'>Ya, Lanjutkan</button>
                        <button id='btn-no' class='btn btn-lg btn-danger btn-no'>Cancel</button>
                    </div>
                `;

                // Menambahkan tombol ke dalam modal
                modalContent.appendChild(customButtons);

                // Menambahkan event listener untuk tombol
                top.document.getElementById('btn-yes').addEventListener('click', function() {
$jenisTr
                    top.swal('menerapkan aturan');
                    top.swal.enableLoading();
                    
                    top.$.ajax({
                        url: '$link_allow_multi?state=1',
                        success: function(aaa){
                            console.log(aaa);
                            if(aaa){
                                top.$('#$opt_id')
                                .prop('checked', false)
                                .trigger('click');
                                top.swal.close();
                            }
                            else{
                                top.swal('terjadi galat');
                            }
                        }
                    });
                    

                });

                top.document.getElementById('btn-no').addEventListener('click', function() {
                    top.$('#$opt_id').prop('checked', false);
                    top.swal.close()
                });
            }
        });
    </script>";
                        echo $alert;
                        die();
                    }
                    else {
                        $alert = "<script>
        top.swal({
            title: '!! PERHATIAN !!',
            html: '$alert_label2',
            type: 'error',
            showConfirmButton: false, // Menonaktifkan tombol confirm
            showCancelButton: false, // Menonaktifkan tombol cancel
            
            allowOutsideClick: false,
            onOpen: function() {
                // Membuat tombol manual setelah swal terbuka
                const modalContent = top.swal.getContent();
                top.close_holdon();
                // Membuat div untuk tombol manual
                const customButtons = document.createElement('div');
                customButtons.classList.add('custom-buttons');
                customButtons.innerHTML = `
                <a id='' href='javascript:void(0)' onclick=\"top.$('#result').load('$linkOpenReset');top.window.location.href='$linkOpenRealsi'\"><h3 ><b>klik disini untuk lepas relasi titipan</b></h3></a>
                    <div style='margin-top: 55px;'>
                        <button id='btn-yes' class='btn btn-lg btn-primary btn-yes'>close</button>
                        
                    </div>
                `;

                // Menambahkan tombol ke dalam modal
                modalContent.appendChild(customButtons);

                // Menambahkan event listener untuk tombol
                top.document.getElementById('btn-yes').addEventListener('click', function() {
                    top.$('#$opt_id').prop('checked', false);
                    top.swal.close()
                });

                top.document.getElementById('btn-no').addEventListener('click', function() {
                    top.$('#$opt_id').prop('checked', false);
                    top.swal.close()
                });
            }
        });
    </script>";
                        echo $alert;
                        die();
                        matiHere();
                    }


                }
            }

        }

        //endregion

        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $componentAssConfig = isset($this->configUi[$this->jenisTr]['componentsAss']) ? $this->configUi[$this->jenisTr]['componentsAss'] : array();

        if ($_GET["nomer"] == "pemindahbukuan") {
            $_SESSION[$cCode]["main"]["transaksi_injected_entry"] = "1";
            $_SESSION[$cCode]["main"]["tipe_transaksi_sumber"] = "1";//untuk pembeda pindah buku dan reguler
            $_SESSION[$cCode]["main"]["ppnTransaksi"] = $_GET['ppnTransaksi'];//untuk pembeda pindah buku dan reguler
            unset($_SESSION[$cCode]['items']);
            unset($_SESSION[$cCode]['items2']);
            unset($_SESSION[$cCode]['items4_sum']);
            $_SESSION[$cCode]["main"]["harga_x"] = 0;
            $_SESSION[$cCode]["main"]["koreksi"] = 0;
        }
        else {
            $_SESSION[$cCode]["main"]["transaksi_injected_entry"] = "0";
            $_SESSION[$cCode]["main"]["ppnTransaksi"] = $_GET['ppnTransaksi'];//untuk pembeda pindah buku dan reguler
            $_SESSION[$cCode]["main"]["tipe_transaksi_sumber"] = "0";//untuk pembeda pindah buku dan reguler

            $_SESSION[$cCode]["main"]["harga_x"] = 0;
            $_SESSION[$cCode]["main"]["koreksi"] = 0;
        }
//        matiHere();
//        unset($_SESSION[$cCode]['items'][$id]);
        if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
//            cekMerah("TIDAK ADA DI SESI ITEMS");
            $tmp = array(
                "handler" => $this->uri->segment(1) . "/" . get_class($this),
                "id" => $id,
                "refID" => $id,
                "jml" => 1,
                "valid_qty" => 1,
                "qty_saldo" => 1,
                "harga" => 0,
                "satuan" => "-",
                "subtotal" => 0,
                "jenis_source" => isset($_GET['target_jenis']) ? $_GET['target_jenis'] : 0,
                "valas_id" => isset($_GET['valas_id']) ? $_GET['valas_id'] : 0,
                "valas_nama" => isset($_GET['valas_nama']) ? $_GET['valas_nama'] : 0,
                "valas_nilai" => isset($_GET['valas_nilai']) ? $_GET['valas_nilai'] : 0,
                "tabel_id" => isset($_GET['tabel_id']) ? $_GET['tabel_id'] : 0,
                "ppnTransaksi" => isset($_GET['ppnTransaksi']) ? $_GET['ppnTransaksi'] : 0,
                "referensi_po_id" => isset($_GET['referensi_po_id']) ? $_GET['referensi_po_id'] : 0,
                "referensi_po_nomer" => isset($_GET['referensi_po_nomer']) ? $_GET['referensi_po_nomer'] : 0,
                "transaksi_ref_id_po" => isset($_GET['transaksi_ref_id_po']) ? $_GET['transaksi_ref_id_po'] : 0,
                "transaksi_ref_po_nomer" => isset($_GET['transaksi_ref_po_nomer']) ? $_GET['transaksi_ref_po_nomer'] : 0,
                "jenis_ref_po" => isset($_GET['jenis']) ? $_GET['jenis'] : 0,
            );
            foreach ($fieldSrcs as $key => $src) {
                $tmp[$key] = makeValue($src, $row, $row, 0);
            }
            foreach ($_GET as $aa => $bb) {
                $tmp[$aa] = $bb;
//                if(!array_key_exists($aa, $tmp)){
//                }
            }
            if ($subAmountConfig != null) {
                $subtotal = makeValue($subAmountConfig, $row, $row, 0);
                $tmp["subtotal"] = $subtotal;
            }
            else {
                $tmp["subtotal"] = 0;
            }

            if (sizeof($componentAssConfig) > 0) {
                $this->load->model($componentAssConfig["model"]);
                $tr = new $componentAssConfig["model"]();
                $tmpReg = $tr->lookupDataRegistriesByMasterID($id)->result();
                // cekHitam($this->db->last_query());
                $mainRegistries = array();
                $itemsRegistries = array();
                $items2Registries = array();
                if (sizeof($tmpReg) > 0) {
                    foreach ($tmpReg as $row) {
                        foreach ($row as $param => $values) {
                            switch ($param) {
                                case "main"://
                                    $mainRegistries = unserialize(base64_decode($values));
                                    if (sizeof($referenceGateFields) > 0) {
                                        $target = $referenceGateFields['target'];
                                        $fields = $referenceGateFields['fields'];
                                        foreach ($fields as $skey => $tkey) {
                                            $_SESSION[$cCode][$target][$tkey] = isset($mainRegistries[$skey]) ? $mainRegistries[$skey] : "";
                                        }
                                    }
                                    break;
                                case "items"://
                                    $itemsRegistries = unserialize(base64_decode($values));
                                    // arrPrint($itemsRegistries);
                                    // matiHEre();
                                    break;
                                case "items2"://
                                    $items2Registries = unserialize(base64_decode($values));
                                    break;
                            }
                        }

                    }

                }
                if (sizeof($relativeComNameDetails) > 0) {
                    $key = isset($_SESSION[$cCode]['main']['pihakMainName']) ? $_SESSION[$cCode]['main']['pihakMainName'] : "";
//                    cekHere($key);
//                    cekHere("$relativeComNameDetails[$key] " . $relativeComNameDetails[$key]);
                    $tmp['relativeCom'] = isset($relativeComNameDetails[$key]) ? $relativeComNameDetails[$key] : "";
                    $tmp['rekName'] = isset($_SESSION[$cCode]['main']['pihakMainName']) ? $_SESSION[$cCode]['main']['pihakMainName'] : "";
                }


            }
            //-------
            if ($shoppingCartReferenceToItem == true) {
                foreach ($row as $get_key => $get_val) {
                    $tmp[$get_key] = $get_val;
                }
            }
            //-------

        }
        else {
            if (sizeof($itemNumLabels) > 0) {
                foreach ($itemNumLabels as $key => $label) {
                    if (isset($_GET[$key]) && strlen($_GET[$key]) > 0) {
                        $newValue = $_GET[$key];
                        $tmp[$key] = $newValue;
                        $_SESSION[$cCode]['items'][$id][$key] = $newValue;

                        $reload_shoppingcart = 0;
                    }
                }
            }
        }

//        arrPrintWEbs($tmp);
//        matiHere();
        $_SESSION[$cCode]['main']['refIDs'][$id] = $id;

        switch ($_GET['state']) {
            case "true":
                if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                    $_SESSION[$cCode]['items'][$id] = $tmp;
                    if ($this->jenisTr == "483") {
                        $_SESSION[$cCode]['items'][$id]["extern_nilai2"] = $tmp["tagihan"];
                    }
                }
                if ($this->jenisTr == "489") {
                    $eFakturKode = isset($_GET['extern_kode2']) ? trim($_GET['extern_kode2']) : (isset($row['extern_kode2']) ? trim($row['extern_kode2']) : "");
                    if (strlen($eFakturKode) > 0) {
                        $dppVal = isset($_GET['dpp_ppn']) ? floatval($_GET['dpp_ppn']) : (isset($_GET['dpp_pengganti']) ? floatval($_GET['dpp_pengganti']) : 0);
                        $ppnVal = isset($_GET['ppn_final']) ? floatval($_GET['ppn_final']) : 0;
                        $tagihanBayar = $dppVal + $ppnVal;

                        $item6Spec = array(
                            "transaksi_id"     => $id,
                            "dpp_pengganti"    => $dppVal,
                            "ppn_final"        => $ppnVal,
                            "tagihan_bayar"    => $tagihanBayar,
                            "dateFaktur"       => isset($_GET['extern_date2']) ? $_GET['extern_date2'] : "",
                            "eFaktur"          => $eFakturKode,
                            "skip_faktur"      => isset($_GET['skip_faktur']) ? $_GET['skip_faktur'] : "",
                            "ppn_sudah_faktur" => isset($_GET['ppn_approved']) ? $_GET['ppn_approved'] : 0,
                        );

                        if (!isset($_SESSION[$cCode]['items6_sum'])) {
                            $_SESSION[$cCode]['items6_sum'] = array();
                        }
                        $_SESSION[$cCode]['items6_sum'][] = $item6Spec;
                    }
                }
                if (sizeof($componentAssConfig) > 0) {
                    if (!array_key_exists($id, $_SESSION[$cCode]['items2'])) {
                        $_SESSION[$cCode]['items2'][$id] = $itemsRegistries;
                    }
                    if (!array_key_exists($id, $_SESSION[$cCode]['items4'])) {
                        $_SESSION[$cCode]['items4'][$id] = $items2Registries;
                    }
                }


                if ((sizeof($selectorNotaLocker) > 0)) {
                    if (isset($selectorNotaLocker["enabled"]) && ($selectorNotaLocker["enabled"] == true)) {

                        $this->load->model("Mdls/MdlLockerTransaksi");
                        if ($this->session->login['ghost'] == 0) {
                            $lt = New MdlLockerTransaksi();
                            $lt->addFilter("transaksi_id='$transaksi_id_selected'");
                            $ltTmp = $lt->lookupAll()->result();
                            if (sizeof($ltTmp) == 0) {
                                $ltHold = array(
                                    "state" => "hold",
                                    "produk_id" => $transaksi_id_selected,
                                    "transaksi_id" => $transaksi_id_selected,
                                    "cabang_id" => my_cabang_id(),
                                    "oleh_id" => my_id(),
                                    "oleh_nama" => my_name(),
                                    "jenis" => "transaksi",
                                    "jenis_locker" => "transaksi",
                                    "jumlah" => 1,
                                    "gudang_id" => "0",
                                );
                                // insert ke tabel locker transaksi
                                $lt->addData($ltHold);
                            }
                            else {
                                $byUpdateHold = array();
                                $totalUpdateHold = 0;
                                $insertHold = true;
                                foreach ($ltTmp as $ltSpec) {
                                    if (($ltSpec->state == "hold") && ($ltSpec->jumlah == "1")) {
                                        $insertHold = false;
                                        break;
                                    }
                                    elseif (($ltSpec->state == "hold")) {
                                        $totalUpdateHold += isset($ltSpec->jumlah) ? $ltSpec->jumlah : 0;
                                        $byUpdateHold[] = $ltSpec->oleh_id;
                                    }
                                }
                                if ($insertHold == true) {
                                    if (($totalUpdateHold == 0) && (in_array($this->session->login['id'], $byUpdateHold))) {
                                        $ltHold = array(
                                            "jumlah" => 1,
                                        );
                                        $ltWhere = array(
                                            "state" => "hold",
                                            "produk_id" => $transaksi_id_selected,
                                            "transaksi_id" => $transaksi_id_selected,
                                            "jenis" => "transaksi",
                                            "jenis_locker" => "transaksi",
                                            "oleh_id" => my_id(),
                                            "oleh_nama" => my_name(),
                                        );
                                        $lt->updateData($ltWhere, $ltHold);
                                    }
                                    else {
                                        $ltHold = array(
                                            "state" => "hold",
                                            "produk_id" => $transaksi_id_selected,
                                            "transaksi_id" => $transaksi_id_selected,
                                            "cabang_id" => my_cabang_id(),
                                            "oleh_id" => my_id(),
                                            "oleh_nama" => my_name(),
                                            "jenis" => "transaksi",
                                            "jenis_locker" => "transaksi",
                                            "jumlah" => 1,
                                            "gudang_id" => "0",
                                        );
                                        $lt->addData($ltHold);
                                    }
                                }
                                else {
                                    //                cekPink("sudah ada yang HOLD");
                                }
                            }
                        }

                    }
                }

                break;
            case "false":
                $reload_shoppingcart = 1;
                if (array_key_exists($id, $_SESSION[$cCode]['items'])) {
                    $detailResetList = array(
                        "items",
                        "tableIn_detail",
                        "tableIn_detail2",
                        "tableIn_detail_values",
                        "tableIn_detail2_sum",
                        "tableIn_detail_values2_sum",
                    );
                    foreach ($detailResetList as $sSName) {
                        $_SESSION[$cCode][$sSName][$id] = null;
                        unset($_SESSION[$cCode][$sSName][$id]);
                    }
                }
                if (isset($_SESSION[$cCode]['main']['refIDs'][$id])) {
                    $_SESSION[$cCode]['main']['refIDs'][$id] = null;
                    unset($_SESSION[$cCode]['main']['refIDs'][$id]);
                }

                if (sizeof($_SESSION[$cCode]['items']) > 0) {

                }
                else {

                    $mainValueInjector = isset($this->configUi[$this->jenisTr]['mainValueInjectors']) ? $this->configUi[$this->jenisTr]['mainValueInjectors'] : array();
                    if (sizeof($mainValueInjector) > 0) {
                        foreach ($mainValueInjector as $key => $val) {
                            $_SESSION[$cCode]['main'][$val] = null;

                            unset($_SESSION[$cCode]['main'][$val]);

                        }
                    }
                }
                if (isset($_SESSION[$cCode]['items2'][$id])) {
                    $_SESSION[$cCode]['items2'][$id] = null;
                    unset($_SESSION[$cCode]['items2'][$id]);
                }

                if (isset($_SESSION[$cCode]['items4'][$id])) {
                    $_SESSION[$cCode]['items4'][$id] = null;
                    unset($_SESSION[$cCode]['items4'][$id]);
                }
                if (isset($_SESSION[$cCode]['items3_sum'])) {
                    $_SESSION[$cCode]['items3_sum'] = null;
                    unset($_SESSION[$cCode]['items3_sum']);
                }
                if (isset($_SESSION[$cCode]['items4_sum'])) {
                    $_SESSION[$cCode]['items4_sum'] = null;
                    unset($_SESSION[$cCode]['items4_sum']);
                }

                if ((sizeof($selectorNotaLocker) > 0)) {
                    if (isset($selectorNotaLocker["enabled"]) && ($selectorNotaLocker["enabled"] == true)) {
                        $this->load->model("Mdls/MdlLockerTransaksi");
                        if ($transaksi_id_selected > 0) {
                            $lt = New MdlLockerTransaksi();
                            $lt->execLocker($_SESSION[$cCode]['main'], 0, $transaksi_id_selected, NULL);
                        }
                    }
                }

                break;
        }

        if (sizeof($_SESSION[$cCode]['items2']) > 0) {
//            cekBiru("bulding summary item_result...");
            $_SESSION[$cCode]['items2_sum'] = array();
            foreach ($_SESSION[$cCode]['items2'] as $pID => $pSpec) {
                foreach ($pSpec as $eSpec) {
                    if (!isset($_SESSION[$cCode]['items2_sum'][$eSpec['id']])) {
                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']] = $eSpec;
                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['jml'] = 0;
//                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['harga'] = 0;
//
                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['subtotal'] = 0;
                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['produk_ids'] = array();


                    }

                    $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['jml'] += $eSpec['jml'];

                    if ($this->jenisTr == "462") {
                        if (!isset($_SESSION[$cCode]['items2_sum'][$eSpec['id']]['ppn_i'])) {
                            $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['ppn_i'] = 0;
                        }
                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['ppn_i'] += $eSpec['ppn'];
                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['ppn'] = $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['ppn_i'] / $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['jml'];
                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['subtotal'] += $eSpec['subtotal'];
                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['harga'] = $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['subtotal'] / $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['jml'];
//                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['subtotal'] += $eSpec['harga'];
                        //                        if(!isset($_SESSION[$cCode]['items2_sum'][$eSpec['id']]['ppn'])){
//                            $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['ppn'] = 0;
//                        }
                    }
                    else {
                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['harga'] += $eSpec['harga'];
                    }

                    $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['produk_ids'][$pID] = $pID;
                    if (sizeof($relativeComNameDetails) > 0) {
                        $key = isset($_SESSION[$cCode]['main']['pihakMainName']) ? $_SESSION[$cCode]['main']['pihakMainName'] : "";
                        cekHere($key);
                        cekHere("$relativeComNameDetails[$key] " . $relativeComNameDetails[$key]);
                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['relativeCom'] = isset($relativeComNameDetails[$key]) ? $relativeComNameDetails[$key] : "";
                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['rekName'] = isset($_SESSION[$cCode]['main']['pihakMainName']) ? $_SESSION[$cCode]['main']['pihakMainName'] : "";
                    }


                }
            }
        }

        if (sizeof($_SESSION[$cCode]['items2_sum']) > 0) {
            foreach ($_SESSION[$cCode]['items2_sum'] as $bID => $pSpec) {
                $_SESSION[$cCode]['items2_sum'][$bID]['produk_ids'] = serialize(base64_encode($pSpec['produk_ids']));
            }
        }
        $_SESSION[$cCode]['main']['refs'] = base64_encode(serialize($_SESSION[$cCode]['main']['refIDs']));
        $_SESSION[$cCode]['main']['refs_intext'] = print_r($_SESSION[$cCode]['main']['refIDs'], true);
        $_SESSION[$cCode]['main']['cashMethode'] = "none";
        $_SESSION[$cCode]['main']['ppnFactor'] = my_ppn_factor();
        if (!isset($_SESSION[$cCode]["main"]["allow_multi"])) {
            $_SESSION[$cCode]["main"]["allow_multi"] = 0;
        }

        if ($_GET["nomer"] == "pemindahbukuan") {

        }
        else {
            if (count($shopingCartPairProdukSrc) > 0) {

                if (isset($this->configUiJenis["referenceModul"]["reference_jenis"][$transaksi_jenis_selected])) {
                    cekHere("MASUK DISINI");
                    $pathModel = $this->configUiJenis["referenceModul"]["reference_jenis"][$transaksi_jenis_selected]["path"];
                    $srcModel = $this->configUiJenis["referenceModul"]["reference_jenis"][$transaksi_jenis_selected]["model"];
                    $pathSrcModel = $pathModel . "/" . $srcModel;
                    cekHere("[$pathModel]");
                    cekHere("[$srcModel]");
                    cekHere("[$pathSrcModel]");
                    $this->load->model($pathSrcModel);
                    $tr = new $srcModel();
                    $tr->setFilters(array());
                    $tr->addFilter("transaksi_id in ('" . implode("','", $_SESSION[$cCode]['main']['refIDs']) . "')");
                    $tmpReg = $tr->lookupDataRegistries();


                }

                $itemRegProduk = array();
                if (count($tmpReg) > 0) {
                    foreach ($tmpReg as $ii => $eeReg) {
                        if (isset($eeReg[$shopingCartPairProdukSrc_gate])) {
                            $itemRegRel = ($eeReg[$shopingCartPairProdukSrc_gate]);

                            foreach ($itemRegRel as $pid => $pidData) {
//                                arrPrintWebs($pidData);
//                                mati_disini(__LINE__);
                                $itemRegProduk[$pid]["id"] = $pid;
                                foreach ($shopingCartPairProdukSrc as $src => $srcLabel) {

                                    if (!array_key_exists($src, $shopingCartPairProdukSrcException)) {
                                        $itemRegProduk[$pid][$src] = isset($pidData[$src]) ? $pidData[$src] : "";
                                    }
                                }

                                if (!isset($itemRegProduk[$pid]["jml"])) {
                                    $itemRegProduk[$pid]["jml"] = 0;
                                }
                                if (!isset($itemRegProduk[$pid]["ppn"])) {
                                    $itemRegProduk[$pid]["ppn"] = 0;
                                }
                                if (!isset($itemRegProduk[$pid]["subtotal"])) {
                                    $itemRegProduk[$pid]["subtotal"] = 0;
                                }

//                                $itemRegProduk[$pid]["refID"] = $eeReg->transaksi_id;
                                $itemRegProduk[$pid]["refID"] = $transaksi_id_selected;
                                $itemRegProduk[$pid]["jml"] += $pidData["jml"];
                                $itemRegProduk[$pid]["ppn"] += ($pidData["jml"] * $pidData["ppn"]);
                                $itemRegProduk[$pid]["ppnValue_item"] += ($pidData["jml"] * $pidData["ppn"]);
                                $itemRegProduk[$pid]["subtotal"] += ($pidData["jml"] * $pidData["harga"]);
                                if ($this->jenisTr == "462") {
                                    //untuk rata-rata harga dari multi PO jasa dibayar bebarengan
                                    $itemRegProduk[$pid]["harga"] = $itemRegProduk[$pid]["subtotal"] / $itemRegProduk[$pid]["jml"];
                                }


                                if ($shopingCartPairProdukGateCopy == true) {
                                    foreach ($pidData as $key => $val) {
                                        if (!array_key_exists($key, $itemRegProduk[$pid])) {
                                            $itemRegProduk[$pid][$key] = $val;
                                        }
                                    }
                                }

                                if ($shopingCartPairProdukGateDpp == true) {
//                                    $nilai_cek = $pidData["nett"] - $pidData["ppn"];
                                    $dpp_persen = ($pidData["dppPPn"] / $pidData["harga"]) * 100;
                                    $itemRegProduk[$pid]["dpp_persen"] = $dpp_persen;
//                                    mati_disini("[$dpp_persen] [$nilai_cek]");

                                    $dpp_pph_persen = ($pidData["dppPPh"] / $pidData["harga"]) * 100;
                                    $itemRegProduk[$pid]["dpp_pph_persen"] = $dpp_pph_persen;
                                }

                            }
                        }
                    }
                }
                /*
                     * ini untuk menampilkan produk di A/P untuk keperluan realisasi ppn
                     */
                if (count($itemRegProduk) > 0) {
                    //region seelct jika sudah difaktur
//                    arrPrint($_GET);
                    $cekFaktur_received=array();
                    if (isset($this->configUi[$this->jenisTr]['referenceFakturReceive'])) {
                        $apiModul = $this->configUiJenis[$jenisTr]['paymentFakturPajak']["taxes"];//modul tax
                        $cekFaktur_received=$this->_fetchReferenceFakturReceive($cCode, $this->configUi[$this->jenisTr]['referenceFakturReceive'],$_GET["extern_kode2"],$api_path);
                    }
//                    matiHere(__LINE__);
                    //endregion
                    unset($_SESSION[$cCode]["items4_sum"]);
                    /**
                     * untuk mereset jika ada keperluan hanya menampilkan produk tertentu
                     * contoh pembayaran hutang bpsj(jenis tr 1487), hanya menmpilkan data bpjs saja, pphs1 dan gaji tidak perlu tampil
                     */
                    if (count($shopingCartForceItemSelected) > 0) {
                        $itemRegProduk__ = array();
                        if (isset($shopingCartForceItemSelected["id"])) {
                            foreach ($shopingCartForceItemSelected["id"] as $_id) {
                                if (isset($itemRegProduk[$_id])) {
                                    $itemRegProduk__[$_id] = $itemRegProduk[$_id];
                                }
                                else {
                                }
                            }

                        }
                        else {
                            $itemRegProduk__ = $itemRegProduk;
                        }
                    }
                    else {
                        $itemRegProduk__ = $itemRegProduk;
                    }
                    if(count($cekFaktur_received["items4_sum"])>0){
                        $itemRegProduk__=$cekFaktur_received["items4_sum"];
                    }
                    $_SESSION[$cCode]["items4_sum"] = $itemRegProduk;
                }
            }

            /**
             * untuk handling pajak
             */
            if (count($shopingCartEmbededTaxItem) > 0) {
                // ui shoppingcart tidak reload
                $this->load->model("MdlTransaksi");
                $tr = new MdlTransaksi();
                $tr->setFilters(array());
                $tr->addFilter("transaksi_id in ('" . implode("','", $_SESSION[$cCode]['main']['refIDs']) . "')");
                $tmpReg = $tr->lookupDataRegistries()->result();
//                $itemRegProdukTax = array();
                if (count($tmpReg) > 0) {
                    foreach ($tmpReg as $ii => $eeReg) {
                        $trid = $eeReg->transaksi_id;
                        foreach ($shopingCartEmbededGate as $ereg_key => $ereg_gateTarget) {
                            if (isset($eeReg->$ereg_key)) {
                                $itemRegRel = blobDecode($eeReg->$ereg_key);
                                $_SESSION[$cCode][$ereg_gateTarget][$trid] = $itemRegRel;
                            }
                        }
                    }
                }
            }
        }

        //----------------------
        if (sizeof($_SESSION[$cCode]['items']) == 0) {
//            mati_disini("KOSONG...");
            // maka reset sesi saat ini...
            echo "<script>";
            echo "top.$('#result').load('" . MODUL_PATH . "/_shoppingCart/reset/" . $this->jenisTr . "');";
            echo "</script>";
        }
        if (count($_SESSION[$cCode]['items']) > 0) {
            switch ($this->jenisTr) {
                case "462":
                case "1424":
                case "483":
                case "487":
                case "489":
                    $_SESSION[$cCode]['items6_sum'] = array();
                    $totalPpnFakturDiterima = 0;
                    $mainIsPredefined = 0;
                    foreach ($_SESSION[$cCode]['items'] as $itemKey => $iItems) {
                        $eFakturKode = "";
                        if (isset($_GET['extern_kode2']) && (strlen(trim($_GET['extern_kode2'])) > 0)) {
                            $eFakturKode = trim($_GET['extern_kode2']);
                        } elseif (isset($_GET['eFaktur']) && (strlen(trim($_GET['eFaktur'])) > 0)) {
                            $eFakturKode = trim($_GET['eFaktur']);
                        } elseif (isset($iItems['extern_kode2']) && (strlen(trim($iItems['extern_kode2'])) > 0)) {
                            $eFakturKode = trim($iItems['extern_kode2']);
                        } elseif (isset($iItems['eFaktur']) && (strlen(trim($iItems['eFaktur'])) > 0)) {
                            $eFakturKode = trim($iItems['eFaktur']);
                        }

                        $dateFakturVal = "";
                        if (isset($_GET['extern_date2']) && (strlen(trim($_GET['extern_date2'])) > 0)) {
                            $dateFakturVal = trim($_GET['extern_date2']);
                        } elseif (isset($_GET['dateFaktur']) && (strlen(trim($_GET['dateFaktur'])) > 0)) {
                            $dateFakturVal = trim($_GET['dateFaktur']);
                        } elseif (isset($iItems['extern_date2']) && (strlen(trim($iItems['extern_date2'])) > 0)) {
                            $dateFakturVal = trim($iItems['extern_date2']);
                        } elseif (isset($iItems['dateFaktur']) && (strlen(trim($iItems['dateFaktur'])) > 0)) {
                            $dateFakturVal = trim($iItems['dateFaktur']);
                        }

                        $grnNomerVal = isset($iItems['nomer']) ? trim($iItems['nomer']) : (isset($_GET['nomer']) ? trim($_GET['nomer']) : "");

                        // Filter out dummy values ("0", "0000-00-00", or GRN document number)
                        if (strlen($eFakturKode) <= 6 || $eFakturKode == "0" || $eFakturKode == "0000-00-00" || ($grnNomerVal != "" && $eFakturKode == $grnNomerVal)) {
                            $eFakturKode = "";
                        }
                        if ($dateFakturVal == "0000-00-00") {
                            $dateFakturVal = "";
                        }

                        if (strlen($eFakturKode) > 0) {
                            $_SESSION[$cCode]['items'][$itemKey]['extern_kode2'] = $eFakturKode;
                        } else {
                            $_SESSION[$cCode]['items'][$itemKey]['extern_kode2'] = "";
                        }
                        if (strlen($dateFakturVal) > 0) {
                            $_SESSION[$cCode]['items'][$itemKey]['extern_date2'] = $dateFakturVal;
                        } else {
                            $_SESSION[$cCode]['items'][$itemKey]['extern_date2'] = "";
                        }

                        $dppVal = isset($_GET['dpp_pengganti']) && (floatval($_GET['dpp_pengganti']) > 0)
                            ? floatval($_GET['dpp_pengganti'])
                            : (isset($_GET['dpp_ppn']) ? floatval($_GET['dpp_ppn']) : (isset($iItems['dpp_pengganti']) ? floatval($iItems['dpp_pengganti']) : (isset($iItems['dpp_ppn']) ? floatval($iItems['dpp_ppn']) : 0)));

                        $ppnVal = isset($_GET['ppn_final']) && (floatval($_GET['ppn_final']) > 0)
                            ? floatval($_GET['ppn_final'])
                            : (isset($_GET['ppn']) ? floatval($_GET['ppn']) : (isset($iItems['ppn_final']) ? floatval($iItems['ppn_final']) : (isset($iItems['ppn']) ? floatval($iItems['ppn']) : 0)));

                        $tagihanBayar = $dppVal + $ppnVal;
                        $skipFakturVal = isset($_GET['skip_faktur']) ? $_GET['skip_faktur'] : (isset($iItems['skip_faktur']) ? $iItems['skip_faktur'] : "");
                        $ppnApprovedVal = isset($_GET['ppn_approved']) ? $_GET['ppn_approved'] : (isset($iItems['ppn_approved']) ? $iItems['ppn_approved'] : $ppnVal);

                        $hasEf = (strlen($eFakturKode) >= 10 && substr($eFakturKode, 0, 1) === "0");
                        $hasTgl = (strlen($dateFakturVal) > 6 && $dateFakturVal != "0000-00-00");
                        if ($hasEf || $hasTgl) {
                            $totalPpnFakturDiterima += $ppnVal;
                        }

                        $rawDbEf = isset($iItems['extern_kode2']) ? trim($iItems['extern_kode2']) : (isset($iItems['eFaktur']) ? trim($iItems['eFaktur']) : "");
                        $isDbPredefined = (strlen($rawDbEf) >= 10 && substr($rawDbEf, 0, 1) === "0" && $rawDbEf != "0000-00-00");
                        if ($isDbPredefined) {
                            $mainIsPredefined = 1;
                        }

                        $_SESSION[$cCode]['items6_sum'][] = array(
                            "transaksi_id"         => $itemKey,
                            "dpp_final"            => isset($iItems['dpp_final']) ? floatval($iItems['dpp_final']) : $dppVal,
                            "dpp_pengganti"        => $dppVal,
                            "ppn_final"            => $ppnVal,
                            "tagihan_bayar"        => $tagihanBayar,
                            "dateFaktur"           => $dateFakturVal,
                            "eFaktur"              => $eFakturKode,
                            "skip_faktur"          => $skipFakturVal,
                            "ppn_sudah_faktur"     => $ppnApprovedVal,
                            "is_predefined_faktur" => $isDbPredefined ? 1 : 0,
                        );
                    }

                    if(isset($cekFaktur_received["items6_sum"]) && count($cekFaktur_received["items6_sum"])>0 ){
                        unset($_SESSION[$cCode]['items6_sum']);
                        $totalPpnFakturDiterima=0;
                        $tmp_faktur = array();
                        foreach ($cekFaktur_received["items6_sum"] as $xx =>$preDataFaktur){
                            $tmp_faktur[]=array_merge($preDataFaktur, array("is_predefined_faktur"=>"1","ppn_final_asli"=>$preDataFaktur["ppn_sudah_faktur"]));
                            $totalPpnFakturDiterima +=$preDataFaktur["ppn_sudah_faktur"];
                        }
                        $_SESSION[$cCode]['items6_sum']=$tmp_faktur;
                    }

                    if (isset($cekFaktur_received["items2_sum"]) && count($cekFaktur_received["items2_sum"]) > 0) {
                        unset($_SESSION[$cCode]['items2_sum']);

                        $_SESSION[$cCode]['items2_sum'] = $cekFaktur_received["items2_sum"];
                    }
                    if (isset($cekFaktur_received["items4"]) && count($cekFaktur_received["items4"]) > 0) {
                        unset($_SESSION[$cCode]['items4']);
                        $_SESSION[$cCode]['items4'] = $cekFaktur_received["items4"];
                    }
                    $_SESSION[$cCode]['main']['ppn_faktur_diterima'] = $totalPpnFakturDiterima;
                    $_SESSION[$cCode]['main']['is_predefined_faktur'] = $mainIsPredefined;
                    break;
                default:
                    break;
            }
        }
        $dtime_now = dtimeNow();
        $dtime_now_ex = explode(" ", $dtime_now);
        $date_now = str_replace("-", "", $dtime_now_ex[0]);
        $time_now = str_replace(":", "", $dtime_now_ex[1]);
        $bookingNumber = "$date_now" . "$time_now";
        $_SESSION[$cCode]["main"]["bookingNumber"] = $bookingNumber;
        //-----------------------------------------------------


        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, 1, $this->configUiJenis);
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        if (count($ppnCekDefaultValue) > 0) {
            if ($ppnCekDefaultValue["enabled"] == true) {
                if (isset($ppnCekDefaultValue["required_value"])) {
                    $src_val_def = $ppnCekDefaultValue["required_value"];
                    if (isset($_GET[$src_val_def]) && $_GET[$src_val_def] > 0) {
                        $selectedDef = 1;
                    }
                    else {
                        $selectedDef = $ppnCekDefaultValue["ppnPersenCheck"];
                    }
                }
                else {
                    //defaultaja
                    $selectedDef = $ppnCekDefaultValue["ppnPersenCheck"];
                }
                $ppnPersenCheck = isset($_SESSION[$cCode]["main"]["ppnPersenCheck"]) ? $_SESSION[$cCode]["main"]["ppnPersenCheck"] : $selectedDef;
                $keyDetail = $ppnCekDefaultValue["keyDetail"];
                $selsMainTarget = $keyDetail['srcMain'];
                $selsItemsTarget = $keyDetail['srcItem'];
                $mainOverwrite = $keyDetail['overWriteMain'];

                echo "<script>";
                echo "  top.$('#result').load('" . MODUL_PATH . "_processSelectProductPpn/select/$jenisTr?ppn=$ppnPersenCheck&ppnTargetItems=$selsItemsTarget&ppnTargetMain=$selsMainTarget&overWriteMain=$mainOverwrite" . "');";
                echo "</script>";
            }
        }

        /* --------------------------------------------------
         * ngereload shoping cart dlm modul
         * --------------------------------------------------*/
        if ($reload_shoppingcart == 1) {
            echo "<script>";
            echo "  if(top.document.getElementById('shopping_cart')){";
            echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/buildValues/" . $this->jenisTr . "?selID=$id');";
            echo "  }";
            echo "</script>";
        }
        else {
            // ui shoppingcart tidak reload
        }

    }

    public function remove()
    {
        $id = $_GET['id'];
        $cCode = $this->cCode;
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();

        if (isset($lockerConfig['enabled']) && $lockerConfig['enabled'] == true) {
            cekBiru("melibatkan session");
            if (isset($_SESSION[$cCode]['items'][$id])) {
                cekBiru("ada barang, cek lokernya");
                $this->db->trans_start();

                $mdlName = $lockerConfig['mdlName'];
                $this->load->model("Mdls/" . $mdlName);

                $c = new $mdlName();
                $array_hold_sebelumnya = $c->cekLoker($this->session->login['cabang_id'], $id, "hold", $this->session->login['id']);
                $where = array(
                    "id" => $array_hold_sebelumnya['id'],
                );
                $data_hold = array(
                    "jumlah" => 0,
                );
                $c->updateData($where, $data_hold);


                $c = new $mdlName();
                $array_active_sebelumnya = $c->cekLoker($this->session->login['cabang_id'], $id, "active");
                $where = array(
                    "id" => $array_active_sebelumnya['id'],
                );
                $data_active = array(
                    "jumlah" => $array_active_sebelumnya['jumlah'] + $array_hold_sebelumnya['jumlah'],
                );
                $c->updateData($where, $data_active);


                $this->db->trans_complete() or die("Gagal bro");
            }
            else {
                cekBiru("TIDAK ada barang, ga jadi cek loker");
            }
        }
        else {
            cekBiru("TIDAK melibatkan session");
        }


        if (isset($_SESSION[$cCode]['items'][$id])) {
            $_SESSION[$cCode]['items'][$id] = null;
            unset($_SESSION[$cCode]['items'][$id]);
        }

        if (isset($_SESSION[$cCode]['main']['refIDs'][$id])) {
            $_SESSION[$cCode]['main']['refIDs'][$id] = null;
            unset($_SESSION[$cCode]['main']['refIDs'][$id]);
        }


        $_SESSION[$cCode]['main']['refs'] = base64_encode(serialize($_SESSION[$cCode]['main']['refIDs']));
        $_SESSION[$cCode]['main']['refs_intext'] = print_r($_SESSION[$cCode]['main']['refIDs'], true);
        $_SESSION[$cCode]['main']['ppnFactor'] = my_ppn_factor();
        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, 1, $this->configUiJenis);
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);
        /* --------------------------------------------------
         * ngereload shoping cart dlm modul
         * --------------------------------------------------*/
        echo "<script>";
        echo "  if(top.document.getElementById('shopping_cart')){";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "  }";
        echo "</script>";
    }

    public function updateValues()
    {
        $cCode = "_TR_" . $this->jenisTr;
        die("updating.............................. (will be available sooner or later)");
        $rawParam = $_GET['param'];
        $param = unserialize(base64_decode($rawParam));
        if (is_array($param) && sizeof($param) > 0) {

        }
    }

    //------------------------------------------------------
    public function selectId()
    {

        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $id = $_GET['id'];
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;

        $cCode = $this->cCode;

        $selectorModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = isset($_SESSION[$cCode]['main']['pihakMdlNameSrc']) ? $_SESSION[$cCode]['main']['pihakMdlNameSrc'] : $this->configUi[$this->jenisTr]['selectorSrcModel'];

        // detektor tanda kurawal {}
        if (substr($selectorModel, 0, 1) == "{") {
            $selectorModel = trim($selectorModel, "{");
            $selectorModel = trim($selectorModel, "}");
            $selectorModel = str_replace($selectorModel, $_SESSION[$cCode]['main'][$selectorModel], $selectorModel);
        }
        else {
            cekkuning("TIDAK mengandung kurawal");
        }
        if (substr($selectorSrcModel, 0, 1) == "{") {
            $selectorSrcModel = trim($selectorSrcModel, "{");
            $selectorSrcModel = trim($selectorSrcModel, "}");
            $selectorSrcModel = str_replace($selectorSrcModel, $_SESSION[$cCode]['main'][$selectorSrcModel], $selectorSrcModel);
        }
        else {
            cekkuning("TIDAK mengandung kurawal");
        }


        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        $priceSrcConfig = $this->config->item('hePrices') != null ? $this->config->item('hePrices') : array();
        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();

        $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $priceMainConfig = isset($this->configUi[$this->jenisTr]['selectedMainPrice']) ? $this->configUi[$this->jenisTr]['selectedMainPrice'] : array();

        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $connectedDiscountConfig = isset($this->configUi[$this->jenisTr]['connectedDiscount']) ? $this->configUi[$this->jenisTr]['connectedDiscount'] : array();
        $priceFilter = isset($this->configUi[$this->jenisTr]['selectedPrice']['mdlFilter']) ? $this->configUi[$this->jenisTr]['selectedPrice']['mdlFilter'] : array();
        $resetFilter = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $validateMeasurement = isset($this->configUi[$this->jenisTr]['validateMeasurement'][1]) ? $this->configUi[$this->jenisTr]['validateMeasurement'][1] : array();


        $tmpB = $b->lookupByID($id)->result();

        if (sizeof($tmpB) > 0) {
            foreach ($tmpB as $row) {
                $rows = $row;
                $valValidate_items = array();
                if (sizeof($validateMeasurement) > 0) {
                    $iValidate = 0;
                    foreach ($validateMeasurement as $keyVal => $validateKol) {
                        $valValidate = $row->$keyVal;
                        if ($valValidate == 0) {
                            $msg = "<br><red class='text-red'>" . htmlspecialchars($row->kode) . " " . htmlspecialchars($row->nama) . "</red><hr><br><red class='text-red'>$validateKol = $valValidate </red><br>silahkan hubungi bagian entry data untuk melengkapi data produk";
                            $alerts = array(
                                "type" => "warning",
                                "title" => strtoupper("Data ukuran produk belum lengkap "),
                                "html" => $msg,
                            );
                            echo swalAlert($alerts);
                            die($msg);
                        }
                    }

                }


                if (sizeof($valValidate_items) > 0) {
//                    arrPrint($valValidate_items);
                    $msg = "Data pendukung produk belum lengkap<br><red class='text-red'>" . htmlspecialchars($row->kode) . " " . htmlspecialchars($row->nama) . "</red><hr>$jml_now $satuan stock available";
                    $alerts = array(
                        "type" => "warning",
                        "title" => strtoupper($kode),
                        "html" => $msg,
                    );
                    echo swalAlert($alerts);
                    die($msg);
                }
                $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                $tmpJml = 1;


                $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");

                if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                    $tmp = array(
                        "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
                        "id" => $id,
                        "jml" => $tmpJml,
                        "harga" => 0,
                        "subtotal" => 0,
                        "satuan" => strlen($rows->satuan) > 0 ? $rows->satuan : "n/a",

                    );


                    foreach ($fieldSrcs as $key => $src) {
                        if (is_array($src) && sizeof($src) > 0) {
                            foreach ($src as $srcSpec) {
                                if (isset($tmp[$srcSpec]) || isset($rows->$srcSpec)) {
                                    cekBiru("ambil gerbang key -> $srcSpec");
                                    $tmp[$key] = makeValue($srcSpec, $tmp, $tmp, isset($rows->$srcSpec) ? $rows->$srcSpec : 0);
                                }
                            }
                        }
                        else {
                            $tmp[$key] = makeValue($src, $tmp, $tmp, isset($rows->$src) ? $rows->$src : 0);
//                            cekHere("hasilnya $key -> " . $tmp[$key]);
                        }
                    }

                    if (sizeof($itemNumLabels) > 0) {
                        echo("iterating subNums..");
                        foreach ($itemNumLabels as $key => $label) {
                            if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                $newValue = $_GET[$key];
                                $tmp[$key] = $newValue;
                                echo "replacing value for $key with " . $newValue . "<br>";
                            }
                        }
                    }


                    if ($subAmountConfig != null) {
                        $tmp['subtotal'] = makeValue($subAmountConfig, $tmp, $tmp, 0);
                    }
                    else {
                        $tmp['subtotal'] = 0;
                    }

                    $_SESSION[$cCode]['items'][$id] = $tmp;

                }
                else {
                    if (sizeof($itemNumLabels) > 0) {
                        echo("iterating subNums..");
                        foreach ($itemNumLabels as $key => $label) {
                            if (isset($_GET[$key]) && strlen($_GET[$key]) > 0) {
                                $newValue = $_GET[$key];
                                $tmp[$key] = $newValue;
                                $_SESSION[$cCode]['items'][$id][$key] = $newValue;
                                echo "replacing value for $key with " . $newValue . "<br>";
                            }

                        }


                        if ($subAmountConfig != null) {
                            $tmp['subtotal'] = makeValue($subAmountConfig, $_SESSION[$cCode]['items'][$id], $_SESSION[$cCode]['items'][$id], 0);
                        }
                        else {
                            $tmp['subtotal'] = 0;
                        }
                        $_SESSION[$cCode]['items'][$id]['subtotal'] = $tmp['subtotal'];
                    }
                }
            }
        }
        else {
            cekMerah("tidak ada itemnya!");
            die();
        }


        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, 1, $this->configUiJenis);
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);
        /* --------------------------------------------------
         * ngereload shoping cart dlm modul
         * --------------------------------------------------*/
        echo "<script>";
        echo "  if(top.document.getElementById('shopping_cart')){";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "  }";
        echo "</script>";
    }

    public function allowMultiNota()
    {

        $jenisTr = $this->jenisTr;
        $cCode = $this->cCode;
//        matiHEre($cCode);
        if (!isset($_SESSION[$cCode]["main"]["allow_multi"])) {
            $_SESSION[$cCode]["main"]["allow_multi"] = 0;
        }
        $_SESSION[$cCode]["main"]["allow_multi"] = isset($_GET["state"]) ? $_GET["state"] : 0;
        echo 1;

    }

    public function validateUangMuka($externid, $id_po)
    {
        $this->load->model("Mdls/MdlSupplierUangMukaPpnRek");
        $this->load->model("Mdls/MdlSupplierCreditUangMuka");
        $um = new MdlSupplierUangMukaPpnRek();
        $ti = new MdlSupplierCreditUangMuka();

        $um->addFilter("extern_id='$externid'");
        $um->addFilter("extern2_id='$id_po'");
        $um->addFilter("cabang_id='" . $this->session->login["cabang_id"] . "'");
        $um->addFilter("debet>'0'");
        $tempUm = $um->lookUpAll()->result();
        $lock_multi = array();
        if (count($tempUm) > 0) {
            $lock_multi["um"] = $tempUm[0]->debet;
        }
        $ti->addFilter("extern_id='$externid'");
        $ti->addFilter("extern2_id='$id_po'");
        $ti->addFilter("extern_label2='vendor'");
        $ti->addFilter("sisa>'0'");
        $ti->addFilter("cabang_id='" . $this->session->login["cabang_id"] . "'");

        $temprelasiPO = $ti->lookUpAll()->result();
//        arrPrint($temprelasiPO);
        if (count($temprelasiPO) > 0) {
            $lock_multi["um_titipan"] = $temprelasiPO[0]->sisa;
        }
        return $lock_multi;
//        cekLime($this->db->last_query());
//
//        matiHere("line".__LINE__);
    }

    private function _fetchReferenceFakturReceive_old($cCode, $refFakturConf,$faktur)
    {
//        matiHere($faktur);
        if(strlen($faktur) > 5){

        }
        else{
            $faktur="";
        }
        $datas = array();
        if(count($refFakturConf)>0){
            if ($faktur != "") {

                $this->load->model("Mdls/".$refFakturConf['mdlName']);
                $mPay = new $refFakturConf['mdlName']();
                $mPay->setFilters(array());

                $mPay->addFilter("target_jenis='0000'");
                $mPay->addFilter("label='ppn realisasi'");
                $mPay->addFilter("extern_label2='$faktur'");
                $payResult = $mPay->lookUpAll()->result();
                if (sizeof($payResult) > 0) {
                    $this->load->model("MdlTransaksi");
                    $tr = new MdlTransaksi();
                    $row = $payResult[0];
                    $trid = $row->transaksi_id;
                    //region ambil data koreksi atau data faktur
                    $source_key = $refFakturConf["sourceRegistry"];
                    $tmp = $tr->lookupDataRegistriesByMasterID($trid)->result();
//                    arrPrint($tmp);
                    $items2 = blobDecode($tmp[0]->items2);
                    $items2_old = blobDecode($tmp[0]->items2_sum);
                    $items4 = blobDecode($tmp[0]->items4);
                    $items4_old = blobDecode($tmp[0]->$source_key);
                    $items6_old = blobDecode($tmp[0]->items6_sum);
                    $datas=array(
                        "items2" => $items2,
                        "items2_sum" => $items2_old,
                        "items4" => $items4,
                        "items4_sum"=>$items4_old,
                        "items6_sum"=>$items6_old,
                    );

                }
            }

        }
        return $datas;
    }
    private function _fetchReferenceFakturReceive($cCode, $refFakturConf,$faktur,$api_path)
    {
//        matiHere($faktur);
        if(strlen($faktur) > 5){

        }
        else{
            $faktur="";
        }
        $datas = array();
        if(count($refFakturConf)>0){
            if ($faktur != "") {
                $link = base_url().$api_path;
                $this->load->model("Mdls/".$refFakturConf['mdlName']);
                $mPay = new $refFakturConf['mdlName']();
                $mPay->setFilters(array());

                $mPay->addFilter("target_jenis='0000'");
                $mPay->addFilter("label='ppn realisasi'");
                $mPay->addFilter("extern_label2='$faktur'");
                $payResult = $mPay->lookUpAll()->result();
                if (sizeof($payResult) > 0) {
                    $this->load->model("MdlTransaksi");
                    $tr = new MdlTransaksi();
                    $row = $payResult[0];
                    $trid = $row->transaksi_id;
                    //region ambil data koreksi atau data faktur
                    $source_key = $refFakturConf["sourceRegistry"];
                    $tmp = $tr->lookupDataRegistriesByMasterID($trid)->result();
//                    arrPrint($tmp);
                    $items2 = blobDecode($tmp[0]->items2);
                    $items2_old = blobDecode($tmp[0]->items2_sum);
                    $items4 = blobDecode($tmp[0]->items4);
                    $items4_old = blobDecode($tmp[0]->$source_key);
                    $items6_old = blobDecode($tmp[0]->items6_sum);
                    $datas=array(
                        "items2" => $items2,
                        "items2_sum" => $items2_old,
                        "items4" => $items4,
                        "items4_sum"=>$items4_old,
                        "items6_sum"=>$items6_old,
                    );

                }
            }

        }
        return $datas;
    }

}