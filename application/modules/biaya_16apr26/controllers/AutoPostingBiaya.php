<?php

//error_reporting(E_ALL);
//ini_set('display_errors', 1);
require_once "Modul_Controller.php";

//class AutoDepresiasi_coa extends CI_Controller
class AutoPostingBiaya extends Modul_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper("he_stepping");
        $this->load->helper("he_access_right");
        $this->load->library("MobileDetect");
        $this->load->helper('he_angka');
        $this->load->config("heAccounting");
        $this->load->model("CustomCounter");
        $this->load->model("MdlTransaksi");
    }

    public function index()
    {

        $jenisTr = isset($_REQUEST["jenistr"]) ? $_REQUEST["jenistr"] : matiHere("jenisTr belum di set");
        $no_spk  = isset($_REQUEST["no_spk"]) ? $_REQUEST["no_spk"] : matiHere("no_spk belum di set");

//        echo "<title>AUTO-TRANSAKSI</title>";

        $this->jenisTr = $jenisTr;
        $this->tableInConfig = isset($this->configUi[$this->jenisTr]['tableIn']) ? $this->configUi[$this->jenisTr]['tableIn'] : array();
        $this->tableInConfig_static = isset($this->configUi[$this->jenisTr]['tableIn_static']) ? $this->configUi[$this->jenisTr]['tableIn_static'] : array();

        $cCode = "_TR_" . $this->jenisTr;
        $relOptionConfigs   = isset($this->configUi[$this->jenisTr]['relativeOptions']) ?            $this->configUi[$this->jenisTr]['relativeOptions'] : array();
        $itemNumLabels      = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ?   $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig        = isset($this->configUi[$this->jenisTr]['selectedPrice']) ?              $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $lockerConfig       = isset($this->configUi[$this->jenisTr]['lockerCheck']) ?                $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig    = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $mainClonerConfig   = isset($this->configUi[$this->jenisTr]['mainCloner']['items']) ?        $this->configUi[$this->jenisTr]['mainCloner']['items'] : array();

        $title              = $this->configUi[$this->jenisTr]["label"];
        $subTitle           = $this->configUi[$this->jenisTr]["steps"][1]['label'];
        $handler            = $this->configUi[$this->jenisTr]['selectorProcessor'];
        $handler2           = $this->configUi[$this->jenisTr]['selectorProcessor2'];

        $this->db->trans_start();

        //manual define
        $gudID = "-10";
        $gud2ID = "9";
        $cabID = "1";
        $cab2ID = "-1";

        //definisi gudang
        $this->load->model("Mdls/MdlGudang");
        $gud = new MdlGudang();
        $gud->setFilters(array());
        $tmpGud = $gud->lookUpAll()->result();

        $branchData=array();
        foreach($tmpGud as $rcab){
            $branchData[$rcab->cabang_id][$rcab->id] = (array)$rcab;
            $branchData[$rcab->cabang_id][$rcab->id]["gudang_nama"] = $rcab->nama;
        }

        //definisi cabang
        $this->load->model("Mdls/MdlCabang");
        $cab = new MdlCabang();
        $cab->setFilters(array());
        $tmpCab = $cab->lookUpAll()->result();

        $cabangData=array();
        foreach($tmpCab as $rcab){
            $cabangData[$rcab->id] = $rcab->nama;
        }

        $this->load->model("Mdls/MdlTasklistProject");
        $tp = new MdlTasklistProject();
        $tp->setFilters(array());
        $tp->addFilter("no_spk='$no_spk'");
        $tp->addFilter("status=1");
        $tp->addFilter("trash=0");
        $tp->addFilter("post_biaya_id=0");
        $tmpTp = $tp->lookUpAll()->result();

        $arrTaskList=array();
        if(!empty($tmpTp)){
            foreach($tmpTp as $rowTp){
                $arrTaskList[$rowTp->no_spk] = (array)$rowTp;
            }
        }
        else{
            $result = array(
                "status" => 0,
                "reason" => "biaya ini sudah di posting ($no_spk)",
                "line" => __LINE__,
            );
            echo json_encode($result);
            die();
        }

        $this->load->model("Mdls/MdlProdukProject");
        $tpp = new MdlProdukProject();
        $tpp->setFilters(array());
        $tpp->addFilter("id='".$tmpTp[0]->produk_id."'");
        $tmpTpp = $tpp->lookUpAll()->result();


        $tmpB=array();//MdlSubProgresTasklistKomposisi
        $this->load->model("Mdls/MdlSubProgresTasklistKomposisi");
        $stlk = new MdlSubProgresTasklistKomposisi();
        $stlk->setFilters(array());
        $stlk->addFilter("no_spk='$no_spk'");
        $stlk->addFilter("status=1");
        $stlk->addFilter("trash=0");
        $stlk->addFilter("jenis='biaya'");
        $tmpB = $stlk->lookUpAll()->result();


        //region array builder transaction
        $mainTmp = array(
            "olehID" => "olehID",
            "olehName" => "olehName",
            "placeID" => "placeID",
            "placeName" => "placeName",
            "cabangID" => "cabangID",
            "cabangName" => "cabangName",
            "gudangID" => "gudangID",
            "gudangName" => "gudangName",
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $this->jenisTr . "r",
            "jenisTrName" => $title,
            "stepNumber" => "1",
            "stepCode" => $this->jenisTr . "r",
            "dtime" => "dtime",
            "fulldate" => "date",
            "gudang2" => "-1",
            "gudang2__label" => "default center warehouse",
            "gudang2__nama" => "",
            "harga" => "harga",
            "divID" => "18",
            "divName" => "default",
            "subtotal" => "subtotal",
            "reference" => "0",
            "gudang2ID" => "-1",
            "gudang2Name" => "default center warehouse",
            "jenis" => $this->jenisTr . "r",
            "transaksi_jenis" => $this->jenisTr . "r",
            "next_step_code" => $this->jenisTr,
            "next_group_code" => "o_finance",
            "step_number" => "1",
            "step_current" => "1",
            "longitude" => "",
            "lattitude" => "",
            "accuracy" => "",
            "nilai_bayar" => "0",
            "new_sisa" => "0",
            "note" => "0",
            "description" => "",
            "pihakID" => "-1",
            "pihakName" => "PUSAT",
            "pihakName2" => "PUSAT",
            "pihakDisc" => "",
            "cabang2ID" => "-1",
            "cabang2Name" => "PUSAT",
            "place2ID" => "-1",
            "place2Name" => "PUSAT",

//            "pihakWoProjek" => "pihakWoProjek",
//            "pihakWoProjekName" => "pihakWoProjekName",
//            "pihakWoProjekSpk" => "pihakWoProjekSpk",
//            "pihakWoProjekEmployee" => "pihakWoProjekEmployee",
//            "pihakWoProjekEmployeeName" => "pihakWoProjekEmployeeName",

        );
        $itemsTmp = array(
            "handler" => "Selectors/_processSelectBiaya",
            "id" => "id",
            "jml" => "1",
            "harga" => "harga",
            "subtotal" => "subtotal",
            "nama" => "nama",
            "label" => "",
            "reference" => "",
            "qty" => "1",
            "name" => "extern_nama",
            "extern_id" => "extern_id",
            "extern_nama" => "extern_nama",
            "sub_harga" => "sub_harga",
            "sub_subtotal" => "sub_total",
            "olehID" => "olehID",
            "olehName" => "olehName",
            "placeID" => "placeID",
            "placeName" => "cabang_nama",
            "cabangID" => "cabangID",
            "cabangName" => "cabangName",
            "gudangID" => "gudangID",
            "gudangName" => "gudangName",
            "gudang2ID" => "-1",
            "gudang2Name" => "default center warehouse",
            "jenisTr" => $this->jenisTr,
            "next_substep_code" => $this->jenisTr,
            "next_subgroup_code" => "o_finance",
            "sub_step_number" => "1",
            "sub_step_current" => "1",
            "nilai_bayar" => "",
            "new_sisa" => "0",
            "sub_new_sisa" => "0",
            "note" => "",
            "pihakID" => "-1",
            "pihakName" => "PUSAT",
            "place2ID" => "-1",
            "place2Name" => "PUSAT",
            "cabang2ID" => "-1",
            "cabang2Name" => "PUSAT",
            "cat_id" => "cat_id",
            "cat_nama" => "cat_nama",

//            "pihakWoProjek" => $arrTaskList[$no_spk]["id"],
//            "pihakWoProjekName" => $arrTaskList[$no_spk]["produk_nama"],
//            "pihakWoProjekSpk" => $no_spk,
//            "pihakWoProjekEmployee" => $arrTaskList[$no_spk]["employee_id"],
//            "pihakWoProjekEmployeeName" => $arrTaskList[$no_spk]["employee_nama"],

        );
        $items2 = array();
        $items2_sum = array();
        $rsltItems = array();
        $rsltItems2 = array();
        $tableIn_masterTmp = array(
            "trash" => "0",
            "jenis_master" => $this->jenisTr,
            "jenis_top" => $this->jenisTr . "r",
            "jenis" => $this->jenisTr . "r",
            "jenis_label" => $title,
            "div_id" => "18",
            "div_nama" => "default",
            "oleh_id" => "olehID",
            "oleh_nama" => "olehName",
            "cabang_id" => "cabangID",
            "cabang_nama" => "cabangName",
            "transaksi_nilai" => "sub_total",
            "transaksi_jenis" => $this->jenisTr . "r",
            "gudang_id" => "gudangID",
            "gudang_nama" => "gudangName",
            "gudang2_id" => "-1",
            "gudang2_nama" => "default center warehouse",
            "keterangan" => "",
            "cabang2_id" => "-1",
            "cabang2_nama" => "PUSAT",
        );
        $tableIn_detailTmp = array(
            "dtime" => date("Y-m-d H:i:s"),
            "produk_id" => "id",
            "produk_kode" => "",
            "produk_label" => "",
            "produk_nama" => "nama",
            "produk_ord_jml" => "jml",
            "produk_ord_hrg" => "harga",
            "hpp" => "harga",
            "satuan" => "",
            "note" => "",
            "reference" => "",
            "trash" => 0,
            "produk_jenis" => "expense",
            "next_substep_code" => "3674",
            "next_subgroup_code" => "o_finance",
            "sub_step_number" => 1,
            "sub_step_current" => 1,
            "valid_qty" => "jml",
        );
        $tableIn_detail2_sum = array();
        $tableIn_detail_rsltItems = array();
        $tableIn_detail_rsltItems2 = array();
        $tableIn_master_valuesTmp = array(
            "gudang2" => "-1",
            "harga" => "harga",
            "divID" => "18",
            "subtotal" => "subtotal",
            "reference" => "0",
            "nilai_bayar" => "0",
            "note" => "0",
        );
        $tableIn_detail_valuesTmp = array(
            "jml" => "1",
            "harga" => "harga",
            "subtotal" => "subtotal",
            "qty" => "1",
            "sub_harga" => "sub_harga",
            "sub_subtotal" => "subtotal",
            "sub_new_sisa" => "0",
        );
        $tableIn_detail_values_rsltItemsTmp = array();
        $tableIn_detail_values_rsltItems2Tmp = array();
        $tableIn_detail_values2_sumTmp = array();
        $tableIn_detail2 = array();
        $main_add_values = array();
        $main_add_fields = array();
        $main_elements = array();
        $main_inputs = array();
        $main_inputs_orig = array();
        $receiptDetailFieldsTmp = array(
            "produk_nama" => "name",
        );
        $receiptSumFieldsTmp = array(
            "harga" => "total amount",
        );
        $receiptDetailFields2 = array();
        $receiptSumFields2 = array();
        $tableIn_detail_values2_sum = array();
        $items3 = array();
        $items3_sum = array();
        //endregion


        //transaksi main buildder taro sini
        $main = array();
        $arrItems=array();
        $arrItems2=array();

        if (count($tmpB) > 0) {
            //items
            foreach($tmpB as $row){
                $id = $row->produk_dasar_id;
                $satuan = (isset($row->satuan) && strlen($row->satuan) > 0) ? $row->satuan : "n/a";
                $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
                if ((!array_key_exists($id, $arrItems))) {
//                    cekHitam(":: MASUK ATAS ::");
                    //baca dari config untuk yang wajib diisi/ mandatory

                    $tmp = array(
                        "handler" => $this->uri->segment(1) . "/" . $handler,
                        "id" => $id,
                        "nama" => $row->produk_dasar_nama,
                        "jml" => $row->jml,
                        "harga" => $row->harga,
                        "harga_anggaran" => $row->harga,
                        "cat_id" => $row->cat_id,
                        "cat_nama" => $row->cat_nama,
                        "subtotal" => $row->harga*$row->jml,
                        "rekening" => isset($row->rekening) ? $row->rekening : "",

//                        "pihakWoProjek" => $arrTaskList[$no_spk]["id"],
//                        "pihakWoProjekName" => $arrTaskList[$no_spk]["produk_nama"],
//                        "pihakWoProjekSpk" => $no_spk,
//                        "pihakWoProjekEmployee" => $arrTaskList[$no_spk]["employee_id"],
//                        "pihakWoProjekEmployeeName" => $arrTaskList[$no_spk]["employee_nama"],
                    );

                    arrPrintWebs($fieldSrcs);

                    foreach ($fieldSrcs as $key => $src) {
                        $tmp[$key] = makeValue($src, $tmp, $tmp, isset($row->$key) ? $row->$key : 0);
                    }

                    //region perhitungan subtotal items
                    if (isset($subAmountConfig) && $subAmountConfig != null) {
                        $subtotal = makeValue($subAmountConfig, $tmp, $tmp, 0);
                    }
                    else {
                        $subtotal = 0;
                    }
                    $tmp["subtotal"] = $subtotal;
                    $arrItems[$id] = $tmp;
                    //endregion

                    if (sizeof($itemNumLabels) > 0) {
                        foreach ($itemNumLabels as $key => $label) {
                            if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                $newValue = $_GET[$key];
                                $tmp[$key] = $newValue;
                                $arrItems[$id][$key] = $newValue;
                            }
                        }
                        $arrItems[$id]['subtotal'] = ($arrItems[$id]['jml'] * $arrItems[$id]['harga']);
                    }
                }
            }
        }

        $this->load->model("Mdls/MdlProjectKomponenBiayaDetailsRabSub");
        $stlk2 = new MdlProjectKomponenBiayaDetailsRabSub();
        $stlk2->setFilters(array());
        $stlk2->addFilter("no_spk='$no_spk'");
        $stlk2->addFilter("status=1");
        $stlk2->addFilter("trash=0");
        $stlk2->addFilter("jenis='biaya'");
        $tmpB2 = $stlk2->lookUpAll()->result();

        if (count($tmpB2) > 0) {
            $arrItemsTmp2=array();
            foreach ($tmpB2 as $row) {
                $arrItemsTmp2[] = $row;
            }

            //items2
            foreach($arrItemsTmp2 as $row){
                $id = $row->biaya_id;
                $id_dasar = $row->biaya_dasar_id;
                $satuan = (isset($row->satuan) && strlen($row->satuan) > 0) ? $row->satuan : "n/a";
                $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");

                if ((!array_key_exists($id_dasar, $arrItems2[$id]))) {
//                    cekHitam(":: MASUK ATAS ::");
                    //baca dari config untuk yang wajib diisi/ mandatory

                    $tmp = array(
                        "handler" => $this->uri->segment(1) . "/" . $handler2,
                        "editTarget" => MODUL_PATH . $handler2 ."/".$this->uri->segment(4),
                        "id" => $id,
                        "biaya_dasar_id" => $id_dasar,
                        "biaya_dasar_nama" => $row->biaya_dasar_nama,
                        "biaya_id" => $row->biaya_id,
                        "biaya_nama" => $row->biaya_nama,
                        "nama" => $row->biaya_dasar_nama,
                        "project_id" => $row->project_id,
                        "project_nama" => $row->project_nama,
                        "place2ID" => $cab2ID,
                        "gudangID" => $gud2ID,
                        "no_spk" => $row->no_spk,
                        "wo_id" => $row->sub_fase_id,
                        "wo_nama" => $row->sub_fase_nama,
                        "project_employee" => $arrTaskList[$row->no_spk]['employee_id'],
                        "project_employee_nama" => $arrTaskList[$row->no_spk]['employee_nama'],
                        "jml" => $row->jml,
                        "harga" => $row->harga,
                        "cat_id" => $row->cat_id,
                        "cat_nama" => $row->cat_nama,
                        "subtotal" => $row->jml*$row->harga,
                        "rekening" => isset($row->rekening) ? $row->rekening : "",
                    );

                    foreach ($fieldSrcs as $key => $src) {
                        $tmp[$key] = makeValue($src, $tmp, $tmp, isset($row->$key) ? $row->$key : 0);
                    }

                    //region perhitungan subtotal items
                    if (isset($subAmountConfig) && $subAmountConfig != null) {
                        $subtotal = makeValue($subAmountConfig, $tmp, $tmp, 0);
                    }
                    else {
                        $subtotal = 0;
                    }
                    $tmp["subtotal"] = $subtotal;
                    $arrItems2[$id][$id_dasar] = $tmp;
                    //endregion

                    if (sizeof($itemNumLabels) > 0) {
                        foreach ($itemNumLabels as $key => $label) {
                            if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                $newValue = $_GET[$key];
                                $tmp[$key] = $newValue;
                                $arrItems2[$id][$id_dasar][$key] = $newValue;
                            }
                        }
                        $arrItems2[$id][$id_dasar]['subtotal'] = ($arrItems2[$id][$id_dasar]['jml'] * $arrItems2[$id][$id_dasar]['harga']);
                    }
                }
            }
        }

        if(isset($arrItems2)){
            foreach($arrItems2 as $by_id => $data_0){
                $totalDetails=0;
                foreach($data_0 as $by_drs_id => $data_1){
                    $totalDetails += $data_1["subtotal"]*1;
                }
                $arrItems[$by_id]["subtotal"] = $totalDetails;
            }
        }

        $subtotal=0;
        if (sizeof($arrItems) > 0) {
            foreach ($arrItems as $xid => $iSpec) {
                $subtotal += $iSpec["subtotal"]*1;
            }
        }

        //region builder main
        $main = array(
            "olehID" => "-100",
            "olehName" => "sys",
            "placeID" => $cabID,
            "placeName" => $cabangData[$cabID],
            "cabangID" => $cabID,
            "cabangName" => $cabangData[$cabID],
            "gudangID" => $gudID,
            "gudangName" => (isset($branchData[$cabID][$gudID]['gudang_nama']) ? $branchData[$cabID][$gudID]['gudang_nama'] : ""),
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $this->jenisTr . "r",
            "jenisTrName" => $title,
            "stepNumber" => "1",
            "stepCode" => $this->jenisTr . "r",
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow(),
            "harga" => $subtotal,
            "divID" => "18",
            "divName" => "default",
            "subtotal" => $subtotal,
            "reference" => "0",
            "jenis" => $this->jenisTr . "r",
            "transaksi_jenis" => $this->jenisTr . "r",
            "next_step_code" => $this->jenisTr,
            "next_group_code" => "o_finance",
            "step_number" => "1",
            "step_current" => "1",
            "longitude" => "",
            "lattitude" => "",
            "accuracy" => "",
            "nilai_bayar" => "0",
            "new_sisa" => "0",
            "note" => "0",
            "description" => "",
            "pihakDisc" => "",

            "pihakWoProjek" => $arrTaskList[$no_spk]["id"],
            "pihakWoProjekName" => $arrTaskList[$no_spk]["produk_nama"],
            "pihakWoProjekSpk" => $no_spk,
            "pihakWoProjekEmployee" => $arrTaskList[$no_spk]["employee_id"],
            "pihakWoProjekEmployeeName" => $arrTaskList[$no_spk]["employee_nama"],
        );
        //endregion builder main

        $subcat_ = array();
        if (sizeof($arrItems) > 0) {
            foreach ($arrItems as $xid => $iSpec) {
                $id = $iSpec['id'];
                $main['harga'] += ($iSpec['jml'] * $iSpec['harga']);
                $catnama_ = str_replace(" ","_", $iSpec["cat_nama"]);
                if(!isset($subcat_[$catnama_])){
                    $subcat_[$catnama_] = 0;
                }
                $subcat_[$catnama_] += $iSpec["subtotal"]*1;
                if(!isset($subcat_["piutang_cabang"])){
                    $subcat_["piutang_cabang"] = 0;
                }
                $subcat_["piutang_cabang"] += $iSpec["subtotal"]*1;
            }
            foreach($subcat_ as $key => $val){
                $main[$key] = $val;
            }
        }

        //region builder items
        $items = array();
        foreach ($arrItems as $itsID => $itsData) {
            foreach ($itemsTmp as $col => $selectedRow) {
                $items[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
            }
        }


//region builder items
        $items2 = $arrItems2;

        //region builder tabel in master
        $tableIn_master = array(
            "trash" => "0",
            "jenis_master" => $this->jenisTr,
            "jenis_top" => $this->jenisTr . "r",
            "jenis" => $this->jenisTr . "r",
            "jenis_label" => $title,
            "div_id" => "18",
            "div_nama" => "default",
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow(),
            "oleh_id" => "-100",
            "oleh_nama" => "sys",
            "cabang_id" => $cabID,
            "cabang_nama" => $cabangData[$cabID],
            "transaksi_nilai" => $subtotal,
            "transaksi_jenis" => $this->jenisTr . "r",
            "gudang_id" => $gudID,
            "gudang_nama" => isset($branchData[$cabID][$gudID]['gudang_nama']) ? $branchData[$cabID][$gudID]['gudang_nama'] : "",
            "gudang2_id" => "-1",
            "gudang2_nama" => "default center warehouse",
            "keterangan" => "",
            "cabang2_id" => "-1",
            "cabang2_nama" => "PUSAT",
        );
        //endregion builder tabel in master

        //region builder table in detil
        $tableIn_detail = array();
        foreach ($arrItems as $itsID => $itsData) {
            foreach ($tableIn_detailTmp as $col => $selectedRow) {
                $tableIn_detail[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
            }
        }
        //endregion builder table in detil

        //region table in master values
        $tableIn_master_values = array(
            "gudang" => $gudID,
            "harga" => $subtotal,
            "divID" => "18",
            "subtotal" => $subtotal,
            "reference" => "0",
            "nilai_bayar" => "0",
            "note" => "0",
        );
        //endregion table in master values

        //region build table in detil values
        $tableIn_detail_values = array();
        foreach ($arrItems as $itsID => $itsData) {
            foreach ($tableIn_detail_valuesTmp as $col => $selectedRow) {
                $tableIn_detail_values[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
            }
        }
        //endregion build table in detil values

        //region build table receipDetailFields
        $receiptDetailFields = array();
        foreach ($arrItems as $itsID => $itsData) {
            foreach ($receiptDetailFieldsTmp as $col => $selectedRow) {
                $receiptDetailFields[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
            }
        }
        //endregion

        //region receiptSumFields
        $receiptSumFields = array();
        foreach ($arrItems as $itsID => $itsData) {
            foreach ($receiptSumFieldsTmp as $col => $selectedRow) {
                $receiptSumFields[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
            }
        }
        //endregion

        if(!empty($tmpTpp)){
            $main['pihakProjekID'] = $tmpTp[0]->produk_id;
            $main['pihakProjekName'] = $tmpTp[0]->produk_nama;
            //-GUDANG PER PROJECT------
            $main['pihakProjekCustomerID'] = isset($tmpTpp[0]->customer_id) ? $tmpTpp[0]->customer_id : 0;
            $main['pihakProjekCustomerName'] = isset($tmpTpp[0]->customer_nama) ? $tmpTpp[0]->customer_nama : 0;
            $main['pihakProjekCustomerNama'] = isset($tmpTpp[0]->customer_nama) ? $tmpTpp[0]->customer_nama : 0;

            $main['pihakProjekGudangID']   = getDefaultWarehouseProject($tmpTp[0]->produk_id, $main['pihakProjekName'])["gudang_id"];
            $main['pihakProjekGudangName'] = getDefaultWarehouseProject($tmpTp[0]->produk_id, $main['pihakProjekName'])["gudang_nama"];
            $main['pihakProjekGudangNama'] = getDefaultWarehouseProject($tmpTp[0]->produk_id, $main['pihakProjekName'])["gudang_nama"];
            //-------
        }

//        arrPrintWebs($items2);
//        cekMerah("items2");
//        arrPrintWebs($arrItems2);
//        cekMerah("arrItems2");
//        arrPrint($arrItems);
//        arrPrintWebs($main);
//        arrPrint($arrItems2);

        if (sizeof($arrItems) > 0) {

            $gate['items'] = $arrItems;
            $gate['items2'] = $arrItems2;
            $gate['main'] = $main;
            $jenisTrTarget = isset($this->configUi[$this->jenisTr]["steps"][1]["target"]) ? $this->configUi[$this->jenisTr]["steps"][1]["target"] : NULL;

            //region transaksional
            $buildTablesMaster = isset($this->configCore[$this->jenisTr]['components'][1]['master']) ? $this->configCore[$this->jenisTr]['components'][1]['master'] : array();
            $buildTablesDetail = isset($this->configCore[$this->jenisTr]['components'][1]['detail']) ? $this->configCore[$this->jenisTr]['components'][1]['detail'] : array();
            $addMasterTables = array(
                "rugilaba",
                "laba ditahan",
                "rugilaba lain lain",
            );
            foreach ($addMasterTables as $trek) {
                $buildTablesMaster[] = array(
                    "comName" => "RugiLaba",
                    "loop" => array(
                        "$trek" => .0,
                    ),
                );
            }
            if (sizeof($buildTablesMaster) > 0) {
                $bCtr = 0;
                foreach ($buildTablesMaster as $buildTablesMaster_specs) {
                    $bCtr++;
                    $mdlName = $buildTablesMaster_specs['comName'];
                    if (substr($mdlName, 0, 1) == "{") {
                        $mdlName = trim($mdlName, "{");
                        $mdlName = trim($mdlName, "}");
                        $mdlName = str_replace($mdlName, $main[$mdlName], $mdlName);
                    }
                    else {
                        //                        cekkuning("TIDAK mengandung kurawal");
                    }

                    $mdlName = "Com" . $mdlName;
                    $this->load->model("Coms/" . $mdlName);
                    $m = new $mdlName();
                    if (isset($buildTablesMaster_specs['loop']) && sizeof($buildTablesMaster_specs['loop']) > 0) {
                        foreach ($buildTablesMaster_specs['loop'] as $key => $val) {
                            if (substr($key, 0, 1) == "{") {
                                $oldParam = $buildTablesMaster_specs['loop'][$key];
                                unset($buildTablesMaster_specs['loop'][$key]);
                                $key = trim($key, "{");
                                $key = trim($key, "}");
                                $key = str_replace($key, $main[$key], $key);
                                $buildTablesMaster_specs['loop'][$key] = $oldParam;
                            }
                        }
                    }
                    if (method_exists($m, "getTableNameMaster")) {
                        if (sizeof($m->getTableNameMaster())) {
                            $m->buildTables($buildTablesMaster_specs);
                        }
                    }
                }
            }
            if (sizeof($buildTablesDetail) > 0) {
                foreach ($buildTablesDetail as $buildTablesDetail_specs) {
                    foreach ($items as $itemSpec) {
                        $mdlName = $buildTablesDetail_specs['comName'];
                        if (substr($mdlName, 0, 1) == "{") {
                            $mdlName = trim($mdlName, "{");
                            $mdlName = trim($mdlName, "}");
                            $mdlName = str_replace($mdlName, $itemSpec[$mdlName], $mdlName);
                        }
                        $mdlName = "Com" . $mdlName;
                        cekbiru("model: $mdlName");
                        $this->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();
                        if (isset($buildTablesDetail_specs['loop']) && sizeof($buildTablesDetail_specs['loop']) > 0) {
                            foreach ($buildTablesDetail_specs['loop'] as $key => $val) {
                                if (substr($key, 0, 1) == "{") {
                                    $oldParam = $buildTablesDetail_specs['loop'][$key];
                                    unset($buildTablesDetail_specs['loop'][$key]);
                                    $key = trim($key, "{");
                                    $key = trim($key, "}");
                                    $key = str_replace($key, $itemSpec[$key], $key);
                                    $buildTablesDetail_specs['loop'][$key] = $oldParam;
                                }
                            }
                        }
                        if (method_exists($m, "getTableNameMaster")) {
                            if (sizeof($m->getTableNameMaster())) {
                                $m->buildTables($buildTablesDetail_specs);
                            }
                        }
                    }
                }
            }

            //region pre-processors (master)
            if (isset($this->configCore[$this->jenisTr]['preProcessor'][1]['master'])) {
                $iterator = isset($this->configCore[$this->jenisTr]['preProcessor'][1]['detail']) ? $this->configCore[$this->jenisTr]['preProcessor'][1]['master'] : array();
                $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields']) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'] : array();
                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
                        $subParams = array();

                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {
                                $realValue = makeValue($value, $main, $main, 0);
                                $subParams['static'][$key] = $realValue;
                            }
                            $subParams['static']["fulldate"] = date("Y-m-d");
                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                            $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " oleh " . $this->session->login['nama'];
                        }
                        $tmpOutParams[$cCtr] = $subParams;

                        $mdlName = "Pre" . ucfirst($comName);
                        $this->load->model("Preprocs/" . $mdlName);
                        $m = new $mdlName($resultParams);

                        if (sizeof($tmpOutParams[$cCtr]) > 0) {
                            $tobeExecuted = true;
                        }
                        else {
                            $tobeExecuted = false;
                        }

                        if ($tobeExecuted) {
                            $m->pair(0, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $gotParams = $m->exec();
                            if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                foreach ($gotParams as $gateName => $gSpec) {
                                    if (isset($main)) {
                                        if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                            foreach ($gSpec as $key => $val) {
                                                $main[$key] = $val;
                                            }
                                        }
                                    }
                                    //==inject gotParams to child gate
                                    if (isset($main)) {
                                        if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                            foreach ($gSpec as $key => $val) {
                                                $main[$key] = $val;
                                            }
                                        }
                                    }
                                    //cekMerah("REBUILDING VALUES..");
                                    if (sizeof($itemNumLabels) > 0) {
                                        //cekHijau("REBUILDING SUBS FOR ITEMS");
                                        foreach ($itemNumLabels as $key => $label) {
                                            //cekHere("$id === $key => $label");
                                            if (isset($main[$key])) {
                                                $main['sub_' . $key] = ($main['jml'] * $main[$key]);
                                            }
                                        }
                                    }
                                }
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
                $this->load->helper("he_value_builder");
                fillValues($this->jenisTr, 1, 1);
            }
            else {
                //echo("no processor defined. skipping preprocessor..<br>");
            }
            //endregion

            //region pre-processors (item)
            if (isset($this->configCore[$this->jenisTr]['preProcessor'][1]['detail'])) {
                $iterator = isset($this->configCore[$this->jenisTr]['preProcessor'][1]['detail']) ? $this->configCore[$this->jenisTr]['preProcessor'][1]['detail'] : array();
                $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields']) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'] : array();
                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        foreach ($gate[$srcGateName] as $xid => $dSpec) {
                            $tmpOutParams[$cCtr] = array();
                            $id = $xid;
                            $subParams = array();
                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {
                                    $realValue = makeValue($value, $gate[$srcGateName][$id], $gate[$srcGateName][$id], 0);
                                    $subParams['static'][$key] = $realValue;
                                }
                                $subParams['static']["fulldate"] = date("Y-m-d");
                                $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " oleh " . $this->session->login['nama'];
                            }
                            if (sizeof($subParams) > 0) {
                                $tmpOutParams[$cCtr][] = $subParams;
                                $comName = $tComSpec['comName'];
                                $srcGateName = $tComSpec['srcGateName'];
                                $srcRawGateName = $tComSpec['srcRawGateName'];
                                $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
                                $mdlName = "Pre" . ucfirst($comName);
                                $this->load->model("Preprocs/" . $mdlName);
                                $m = new $mdlName($resultParams);
                                if (sizeof($tmpOutParams[$cCtr]) > 0) {
                                    $tobeExecuted = true;
                                }
                                else {
                                    $tobeExecuted = false;
                                }

                                if ($tobeExecuted) {
                                    $m->pair(0, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                    $gotParams = $m->exec();
                                    if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                        foreach ($gotParams as $gateName => $paramSpec) {
                                            if (!isset($gate[$gateName])) {
                                                $gate[$gateName] = array();
                                            }
                                            else {

                                            }
                                            foreach ($paramSpec as $id => $gSpec) {
                                                if (!isset($gate[$gateName][$id])) {
                                                    $gate[$gateName][$id] = array();
                                                }
                                                if (isset($gate[$gateName][$id])) {
                                                    if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                        foreach ($gSpec as $key => $val) {
                                                            $gate[$gateName][$id][$key] = $val;
                                                        }
                                                    }
                                                }
                                                //==inject gotParams to child gate
                                                if (isset($gate[$srcGateName][$id])) {
                                                    if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                        foreach ($gSpec as $key => $val) {
                                                            $gate[$srcGateName][$id][$key] = $val;
                                                        }
                                                    }
                                                }
                                                //cekMerah("REBUILDING VALUES..");
                                                if (sizeof($itemNumLabels) > 0) {
                                                    //cekHijau("REBUILDING SUBS FOR ITEMS");
                                                    foreach ($itemNumLabels as $key => $label) {
                                                        if (isset($gate[$gateName][$id][$key])) {
                                                            $gate[$gateName][$id]['sub_' . $key] = ($gate[$gateName][$id]['jml'] * $gate[$gateName][$id][$key]);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                else {
                                    cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                                }
                            }
                        }
                    }
                }
                else {
                    //cekKuning("sub-preproc is not set");
                }
                $this->load->helper("he_value_builder");
                fillValues($this->jenisTr, 1, 1);
            }
            else {
                //echo("no processor defined. skipping preprocessor..<br>");
            }
            //endregion

//            $this->midValidate();
//            $this->unionValidate();
            //===finalisasi sebelum masuk tabel beneran

            //===isinya ada pembentukan nomor nota dll
            //region penomoran receipt
            $this->load->model("CustomCounter");
            $cn = new CustomCounter("transaksi");
            $cn->setType("transaksi");

            $counterForNumber = array($this->configCore[$this->jenisTr]['formatNota']);

            if (!in_array($counterForNumber[0], $this->configCore[$this->jenisTr]['counters'])) {
                die("LINE: ".__LINE__." || Used number should be registered in 'counters' config as well");
            }

            foreach ($counterForNumber as $i => $cRawParams) {
                $cParams = explode("|", $cRawParams);
                $cValues = array();
                foreach ($cParams as $param) {
                    $cValues[$i][$param] = $main[$param];
                }
                $cRawValues = implode("|", $cValues[$i]);
                $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);
            }

            $stepNumber = 1;
            $tmpNomorNota = $paramSpec['paramString'];

            if (isset($this->configUi[$this->jenisTr]['steps'][2])) {
                $nextProp = array(
                    "num" => 2,
                    "code" => $this->configUi[$this->jenisTr]['steps'][2]['target'],
                    "label" => $this->configUi[$this->jenisTr]['steps'][2]['label'],
                    "groupID" => $this->configUi[$this->jenisTr]['steps'][2]['userGroup'],
                );
            }
            else {
                $nextProp = array(
                    "num" => 0,
                    "code" => "",
                    "label" => "",
                    "groupID" => "",
                );
            }
            //endregion

            //region dynamic counters
            $cn = new CustomCounter("transaksi");
            $cn->setType("transaksi");
            $configCustomParams = $this->configCore[$this->jenisTr]['counters'];
            $configCustomParams[] = "stepCode";

            if (sizeof($configCustomParams) > 0) {
                $cContent = array();
                foreach ($configCustomParams as $i => $cRawParams) {
                    $cParams = explode("|", $cRawParams);
                    $cValues = array();
                    foreach ($cParams as $param) {
                        $cValues[$i][$param] = $main[$param];
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
                }
            }
            $appliedCounters = base64_encode(serialize($cContent));
            $appliedCounters_inText = print_r($cContent, true);

            //region addition on master
            $addValues = array(
                'counters' => $appliedCounters,
                'counters_intext' => $appliedCounters_inText,
                'nomer' => $tmpNomorNota,
                'dtime' => date("Y-m-d H:i:s"),
                'fulldate' => date("Y-m-d"),
                "step_avail" => sizeof($this->configUi[$this->jenisTr]['steps']),
                "step_number" => 1,
                "step_current" => 1,
                "next_step_num" => $nextProp['num'],
                "next_step_code" => $nextProp['code'],
                "next_step_label" => $nextProp['label'],
                "next_group_code" => $nextProp['groupID'],
                "tail_number" => 1,
                "tail_code" => $this->configUi[$this->jenisTr]['steps'][1]['target'],
            );
            foreach ($addValues as $key => $val) {
                $tableIn_master[$key] = $val;
            }
            //endregion

            //region addition on detail
            $addSubValues = array(
                "sub_step_number" => 1,
                "sub_step_current" => 1,
                "sub_step_avail" => sizeof($this->configUi[$this->jenisTr]['steps']),
                "next_substep_num" => $nextProp['num'],
                "next_substep_code" => $nextProp['code'],
                "next_substep_label" => $nextProp['label'],
                "next_subgroup_code" => $nextProp['groupID'],
                "sub_tail_number" => 1,
                "sub_tail_code" => $this->configUi[$this->jenisTr]['steps'][1]['target'],
            );
            foreach ($tableIn_detail as $id => $dSpec) {
                foreach ($addSubValues as $key => $val) {
                    $tableIn_detail[$id][$key] = $val;
                }
            }
            //endregion

//            arrPrintWebs($main);
//            arrPrintWebs($arrItems);
//            arrPrint($tableIn_detail);
//            matiHere(__LINE__);
            //region ----------write transaksi, transaksi_data, main_fields, main_values, main_applets, etc
            if (sizeof($tableIn_master) > 0) {
                $tableIn_master['status_4'] = 11;
                $tableIn_master['trash_4'] = 0;

                $tr = new MdlTransaksi();
                $tr->addFilter("transaksi.cabang_id='" . $tableIn_master['cabang_id'] . "'");
                $insertID = $tr->writeMainEntries($tableIn_master);
                $tableIn_master_query = $this->db->last_query();
                showLast_query("hijau");
                $mongoList['main'][] = $insertID;
                $epID = $tr->writeMainEntries_entryPoint($insertID, $insertID, $tableIn_master);
                $mongoList['main'][] = $epID;

                $insertNum = $tableIn_master['nomer'];
                $main['nomer'] = $insertNum;
                if ($insertID < 1) {
                    die("Gagal saat berusaha  write transaction entry pada " . __FILE__ . " baris " . __LINE__);
                }

                //==transaksi_id dan nomor nota diinject kan ke gate utama
                $injectors = array(
                    "transaksi_id" => $insertID,
                    "nomer" => $tmpNomorNota,
                );
                $arrInjectorsTarget = array(
                    "items",
                );
                foreach ($injectors as $key => $val) {
                    $main[$key] = $val;
                    foreach ($arrInjectorsTarget as $target) {
                        foreach ($items as $xis => $iSpec) {
                            $id = isset($iSpec['id']) && $iSpec['id'] > 0 ? $iSpec['id'] : $xis;
                            if (isset($items[$id])) {
                                $items[$id][$key] = $val;
                            }
                        }
                        foreach ($gate[$target] as $xis => $iSpec) {
                            $id = isset($iSpec['id']) && $iSpec['id'] > 0 ? $iSpec['id'] : $xis;
                            $gate[$target][$id][$key] = $val;
                        }
                    }
                }

                //===signature
                $dwsign = $tr->writeSignature($insertID, array(
                    "nomer" => $main['nomer'],
                    "step_number" => 1,
                    "step_code" => $this->jenisTr,
                    "step_name" => $this->configUi[$this->jenisTr]['steps'][1]['label'],
                    "group_code" => $this->configUi[$this->jenisTr]['steps'][1]['userGroup'],
                    "oleh_id" => "-100",
                    "oleh_nama" => "sys",
                    "keterangan" => $this->configUi[$this->jenisTr]['steps'][1]['label'] . " oleh sys",
                    "transaksi_id" => $insertID,
                )) or die("Failed to write signature");

                $mongoList['sign'][] = $dwsign;
                $idHis = array(
                    $stepNumber => array(
                        "step" => $stepNumber,
                        "trID" => $insertID,
                        "nomer" => $tmpNomorNota,
                        "counters" => $appliedCounters,
                        "counters_intext" => $appliedCounters_inText,
                    ),
                );
                $idHis_blob = blobEncode($idHis);
                $idHis_intext = print_r($idHis, true);
                $tr = new MdlTransaksi();
                $dupState = $tr->updateData(array("id" => $insertID), array(
                    "next_step_num" => $nextProp['num'],
                    "next_step_code" => $nextProp['code'],
                    "next_step_label" => $nextProp['label'],
                    "next_group_code" => $nextProp['groupID'],

                    //===references
                    "id_master" => $insertID,
                    "id_top" => $insertID,
                    "ids_prev" => "",
                    "ids_prev_intext" => "",
                    "nomer_top" => $main['nomer'],
                    "nomers_prev" => "",
                    "nomers_prev_intext" => "",
                    "jenises_prev" => "",
                    "jenises_prev_intext" => "",
                    "ids_his" => $idHis_blob,
                    "ids_his_intext" => $idHis_intext,
                )) or die("Failed to update tr next-state!");

                $addValues = array(
                    //===references
                    "id_master" => $insertID,
                    "id_top" => $insertID,
                    "ids_prev" => "",
                    "ids_prev_intext" => "",
                    "nomer_top" => $main['nomer'],
                    "nomers_prev" => "",
                    "nomers_prev_intext" => "",
                    "jenises_prev" => "",
                    "jenises_prev_intext" => "",
                    "ids_his" => $idHis_blob,
                    "ids_his_intext" => $idHis_intext,
                );
                foreach ($addValues as $key => $val) {
                    $tableIn_master[$key] = $val;
                }

                $injectors_items = array(
                    "transaksi_id" => $insertID,
                    "transaksi_no" => $tmpNomorNota,
                    "nomer" => $tmpNomorNota,
                );
            }
            if (sizeof($tableIn_master_values) > 0) {
                if (isset($this->configCore[$this->jenisTr]['tableIn']['mainValues'])) {
                    $inserMainValues = array();
                    foreach ($this->configCore[$this->jenisTr]['tableIn']['mainValues'] as $key => $src) {
                        if (isset($tableIn_master_values[$key])) {
                            $dd = $tr->writeMainValues($insertID, array(
                                "key" => $key,
                                "value" => $tableIn_master_values[$key],
                            ));
                            $inserMainValues[] = $dd;
                            $mongoList['mainValues'][] = $dd;
                        }
                    }
                    if (sizeof($inserMainValues) > 0) {
                        $arrBlob = blobEncode($inserMainValues);
                        $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                    }
                }
            }
            if (sizeof($main_add_values) > 0) {
                $inserMainValues = array();
                foreach ($main_add_values as $key => $val) {
                    $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                    $inserMainValues[] = $dd;
                    $mongoList['mainValues'][] = $dd;
                }
                if (sizeof($inserMainValues) > 0) {
                    $arrBlob = blobEncode($inserMainValues);
                    $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                }

                            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
            }
            if (sizeof($main_inputs) > 0) {
                foreach ($main_inputs as $key => $val) {
                    $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                    $inserMainValues[] = $dd;
                    $mongoList['mainValues'][] = $dd;
                }
                if (sizeof($inserMainValues) > 0) {
                    $arrBlob = blobEncode($inserMainValues);
                    $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                }
                            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
            }
            if (sizeof($main_add_fields) > 0) {
                foreach ($main_add_fields as $key => $val) {
                    $tr->writeMainFields($insertID, array("key" => $key, "value" => $val));
                }
                            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
            }
            if (sizeof($main_elements) > 0) {
                foreach ($main_elements as $elName => $aSpec) {
                    $tr->writeMainElements($insertID, array(
                        "mdl_name" => isset($aSpec['mdl_name']) ? $aSpec['mdl_name'] : "",
                        "key" => isset($aSpec['key']) ? $aSpec['key'] : 0,
                        "value" => isset($aSpec['value']) ? $aSpec['value'] : "",
                        "name" => $aSpec['name'],
                        "label" => isset($aSpec['label']) ? $aSpec['label'] : "",
                        "contents" => isset($aSpec['contents']) ? $aSpec['contents'] : "",
                        "contents_intext" => isset($aSpec['contents_intext']) ? $aSpec['contents_intext'] : "",
                    ));

                    //==nebeng bikin inputLabels
                    $currentValue = "";
                    switch ($aSpec['elementType']) {
                        case "dataModel":
                            $currentValue = $aSpec['key'];
                            break;
                        case "dataField":
                            $currentValue = $aSpec['value'];
                            break;
                    }
                    if (array_key_exists($elName, $relOptionConfigs)) {
                        if (isset($relOptionConfigs[$elName][$currentValue])) {
                            if (sizeof($relOptionConfigs[$elName][$currentValue]) > 0) {
                                foreach ($relOptionConfigs[$elName][$currentValue] as $oValueName => $oValSpec) {
                                    $inputLabels[$oValueName] = $oValSpec['label'];
                                    if (isset($oValSpec['auth'])) {
                                        if (isset($oValSpec['auth']['groupID'])) {
                                            $inputAuthConfigs[$oValueName] = $oValSpec['auth']['groupID'];
                                        }
                                    }
                                }
                            }
                        }
                        else {
                            //						cekKuning("option $currentValue pada $eName TIDAK ada pilihannya");
                        }
                    }
                }
            }
            if (sizeof($tableIn_detail) > 0) {
                $insertIDs = array();
                $insertDeIDs = array();
                $arrLastQuery_tableIn_detail = array();
                foreach ($tableIn_detail as $dSpec) {
                    $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                    $arrLastQuery_tableIn_detail[$insertDetailID] = $this->db->last_query();
                    $insertIDs[] = $insertDetailID;
                    $insertDeIDs[$insertID][] = $insertDetailID;
                    $mongoList['detail'][] = $insertDetailID;
                    if ($epID != 999) {
                        $insertEpID = $tr->writeDetailEntries($epID, $dSpec);
                        $insertIDs[] = $insertEpID;
                        $insertDeIDs[$epID][] = $insertEpID;
                        $mongoList['detail'][] = $insertEpID;
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
                        $this->db->query("UPDATE transaksi SET indexing_details = '$arrBlob' WHERE id=$k");
                        cekOrange($this->db->last_query());
                    }
                }
            }
            if (sizeof($tableIn_detail2) > 0) {
                $insertIDs = array();
                foreach ($tableIn_detail2 as $dSpec) {
                    $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                    $mongoList['detail'] = $insertIDs;
                    if ($epID != 999) {
                        $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);
                        $mongoList['detail'] = $insertIDs;
                    }
                }
            }
            if (sizeof($tableIn_detail2_sum) > 0) {
                $insertIDs = array();
                foreach ($tableIn_detail2_sum as $dSpec) {
                    $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                    $insertIDs[] = $insertDetailID;
                    $mongoList['detail'][] = $insertDetailID;

                    if ($epID != 999) {
                        $insertDetailID = $tr->writeDetailEntries($epID, $dSpec);
                        $insertIDs[] = $insertDetailID;
                        $mongoList['detail'][] = $insertDetailID;
                    }
                }
                cekOrange($this->db->last_query());
            }
            if (sizeof($tableIn_detail_rsltItems) > 0) {
                $insertIDs = array();
                foreach ($tableIn_detail_rsltItems as $dSpec) {
                    $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                    $insertIDs[] = $insertDetailID;
                    $mongoList['detail'][] = $insertDetailID;
                    if ($epID != 999) {
                        $insertDetailID = $tr->writeDetailEntries($epID, $dSpec);
                        $insertIDs[] = $insertDetailID;
                        $mongoList['detail'][] = $insertDetailID;
                    }
                    cekUngu($this->db->last_query());
                }
            }
            if (sizeof($tableIn_detail_values) > 0) {
                foreach ($tableIn_detail_values as $pID => $dSpec) {
                    if (isset($this->configCore[$this->jenisTr]['tableIn']['detailValues'])) {
                        $insertIDs = array();
                        foreach ($this->configCore[$this->jenisTr]['tableIn']['detailValues'] as $key => $src) {
                            if (isset($tableIn_detail[$pID])) {
                                $dd = $tr->writeDetailValues($insertID, array(
                                    "produk_jenis" => $tableIn_detail[$pID]['produk_jenis'],
                                    "produk_id" => $pID,
                                    "key" => $key,
                                    "value" => $dSpec[$src],
                                ));
                                $insertIDs[$pID][] = $dd;
                                $mongoList['detailValues'][] = $dd;
                            }
                            cekLime($this->db->last_query());
                        }
                        if (sizeof($insertIDs) > 0) {
                            $arrBlob = blobEncode($insertIDs);
                            $this->db->query("UPDATE transaksi SET indexing_detail_values = '$arrBlob' WHERE id=$insertID");
                        }
                    }
                }
            }
            if (sizeof($tableIn_detail_values2_sum) > 0) {
                foreach ($tableIn_detail_values2_sum as $pID => $dSpec) {
                    if (isset($this->configCore[$this->jenisTr]['tableIn']['detailValues2_sum'])) {
                        foreach ($this->configCore[$this->jenisTr]['tableIn']['detailValues2_sum'] as $key => $src) {
                            $dd = $tr->writeDetailValues($insertID, array(
                                "produk_jenis" => $tableIn_detail2_sum[$pID]['produk_jenis'],
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
            //endregion

            //===components akan langsung dieksekusi jika steps-nya tidak pakai approval
            $steps = $this->configUi[$this->jenisTr]['steps'];

            $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
            $filterNeeded = false;

            //====registri value-gate
            $baseRegistries = array(
                'main' => sizeof($main) > 0 ? $main : array(),
                'items' => sizeof($items) > 0 ? $items : array(),
                'items2' => sizeof($items2) > 0 ? $items2 : array(),
                'items2_sum' => sizeof($items2_sum) > 0 ? $items2_sum : array(),
                'items3' => sizeof($items3) > 0 ? $items3 : array(),
                'items3_sum' => sizeof($items3_sum) > 0 ? $items3_sum : array(),
                'items4_sum' => isset($items4_sum) && count($items4_sum) > 0 ? $items4_sum : array(),
                'rsltItems' => sizeof($rsltItems) > 0 ? $rsltItems : array(),
                'rsltItems2' => sizeof($rsltItems2) > 0 ? $rsltItems2 : array(),
                'tableIn_master' => sizeof($tableIn_master) > 0 ? $tableIn_master : array(),
                'tableIn_detail' => sizeof($tableIn_detail) > 0 ? $tableIn_detail : array(),
                'tableIn_detail2_sum' => sizeof($tableIn_detail2_sum) > 0 ? $tableIn_detail2_sum : array(),
                'tableIn_detail_rsltItems' => sizeof($tableIn_detail_rsltItems) > 0 ? $tableIn_detail_rsltItems : array(),
                'tableIn_detail_rsltItems2' => sizeof($tableIn_detail_rsltItems2) > 0 ? $tableIn_detail_rsltItems2 : array(),
                'tableIn_master_values' => sizeof($tableIn_master_values) > 0 ? $tableIn_master_values : array(),
                'tableIn_detail_values' => sizeof($tableIn_detail_values) > 0 ? $tableIn_detail_values : array(),
                'tableIn_detail_values_rsltItems' => isset($tableIn_detail_values_rsltItems) && count($tableIn_detail_values_rsltItems) > 0 ? $tableIn_detail_values_rsltItems : array(),
                'tableIn_detail_values_rsltItems2' => isset($tableIn_detail_values_rsltItems2) && count($tableIn_detail_values_rsltItems2) > 0 ? $tableIn_detail_values_rsltItems2 : array(),
                'tableIn_detail_values2_sum' => sizeof($tableIn_detail_values2_sum) > 0 ? $tableIn_detail_values2_sum : array(),
                'main_add_values' => sizeof($main_add_values) > 0 ? $main_add_values : array(),
                'main_add_fields' => sizeof($main_add_fields) > 0 ? $main_add_fields : array(),
                'main_elements' => sizeof($main_elements) > 0 ? $main_elements : array(),
                'main_inputs' => sizeof($main_inputs) > 0 ? $main_inputs : array(),
                'main_inputs_orig' => sizeof($main_inputs) > 0 ? $main_inputs : array(),
                "receiptDetailFields" => isset($this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptDetailFields'][1]) ? $this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptDetailFields'][1] : array(),
                "receiptSumFields" => isset($this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptSumFields'][1]) ? $this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptSumFields'][1] : array(),
                "receiptDetailFields2" => isset($this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptDetailFields2'][1]) ? $this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptDetailFields2'][1] : array(),
                "receiptSumFields2" => isset($this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptSumFields2'][1]) ? $this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptSumFields2'][1] : array(),
            );

//            arrPrint($baseRegistries);
//            matiHere(__LINE__);
            $doWriteReg = $tr->writeDataRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));
            $baseRegistriesQuery = $this->db->last_query();
            showLast_query("biru");
            $mongRegID[] = $doWriteReg;
            //endregion

            //region processing sub-post-processors, always items
            $iterator = isset($this->configCore[$this->jenisTr]['postProcessor'][$jenisTrTarget]['detail']) ? $this->configCore[$this->jenisTr]['postProcessor'][$jenisTrTarget]['detail'] : array();
            if (sizeof($iterator) > 0) {
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    $tmpOutParams[$cCtr] = array();
                    foreach ($gate[$srcGateName] as $cnt => $dSpec) {
                        foreach ($injectors_items as $ikey => $ival) {
                            $gate[$srcGateName][$cnt][$ikey] = $ival;
                        }

                        $subParams = array();
                        if (isset($tComSpec['loop'])) {
                            foreach ($tComSpec['loop'] as $key => $value) {
                                $realValue = makeValue($value, $gate[$srcGateName][$cnt], $gate[$srcGateName][$cnt], 0);
                                $subParams['loop'][$key] = $realValue;
                            }
                        }
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {
                                $realValue = makeValue($value, $gate[$srcGateName][$cnt], $gate[$srcGateName][$cnt], 0);
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
                                $jenis = $gate['main']['jenis'];
                                foreach ($paramForceFillers[$comName] as $k => $v) {
                                    $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                }
                            }
                            $subParams['static']["fulldate"] = date("Y-m-d");
                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                            $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . (isset($this->session->login['nama']) ? $this->session->login['nama'] : "sys");
                        }

                        if (sizeof($subParams) > 0) {
                            $tmpOutParams[$cCtr][] = $subParams;
                        }
//                                        echo "<script>top.writeProgress('" . $subParams['static']['name'] . " " . $subParams['static']['extern_nama'] . " " . $subParams['static']['nama'] . "');</script>";
                    }
                }

                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    $mdlName = "Com" . ucfirst($comName);
                    $this->load->model("Coms/" . $mdlName);
                    $m = new $mdlName();
                    $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    $m->exec() or die("Gagal saat berusaha exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
                }
            }
            //endregion

            // endregion
            //region writelog
            $this->load->model("Mdls/" . "MdlActivityLog");
            $hTmp = new MdlActivityLog();
            $tmpHData = array(
                "title" => $main['jenisTrName'],
                "sub_title" => "SPK ($no_spk)",
                "uid" => "-100",
                "uname" => "sys",
                "dtime" => date("Y-m-d H:i:s"),
                "transaksi_id" => $insertID,
                "deskripsi_old" => "",
                "deskripsi_new" => "",
                "jenis" => $this->jenisTr,
                "ipadd" => $_SERVER['REMOTE_ADDR'],
                "devices" => $_SERVER['HTTP_USER_AGENT'],
                "category" => "transaksi",
                "controller" => "AutoPostingBiaya",
                "method" => "index",
                "url" => "",
            );
            $logID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
        }
        else {
            cekOrange('gak bikin transaksi mungkin nilai udh abiss wkwkwk');
            $result = array(
                "status" => 0,
                "reason" => "tidak ada arrItems nya",
                "arrItems" => $arrItems,
                "line" => __LINE__,
            );
            echo json_encode($result);
            die();
        }

        //endregion

//        cekOrange("======================================= batas bawah ASSET =======================================");
        //endregion batas aset

        //==TANDAI TASKLIST SUDAH DI POSTING BIAYA
        $tpPost = new MdlTasklistProject();
        $update = array(
            "post_biaya_id" => $insertID,
            "post_biaya_no" => $tmpNomorNota,
            "post_biaya_dtime" => date("Y-m-d H:i:s"),
        );
        $where = array(
            "no_spk" => $no_spk,
            "post_biaya_id" => 0,
        );
        $tpPost->setFilters(array());
        $update = $tpPost->updateData($where, $update) or matiHere("gagal memperbaharui data LINE: " . __LINE__);
        //==TANDAI TASKLIST SUDAH DI POSTING BIAYA

//        mati_disini("============= BELUM COMMIT AUTO TRANSAKSI ===============");
//        if (isset($_GET['debug'])) {
//            mati_disini("============= BELUM COMMIT SEWA COA DEBUG ===============");
//        }

//        $commit = 1;
        $commit = $this->db->trans_complete() or die("Gagal saat berusaha commit transaction!");

        if($commit){
            $result = array(
                "status" => $commit,
                "reason" => "biaya berhasil diposting",
                "no_spk" => $no_spk,
//                "baseRegistriesQuery" => $baseRegistriesQuery,
                "tableIn_master_query" => $tableIn_master_query,
                "arrLastQuery_tableIn_detail" => $arrLastQuery_tableIn_detail,
                "line" => __LINE__,
            );
            echo json_encode($result);
        }
        else{
            $result = array(
                "status" => 0,
                "reason" => "gagal posting biaya ($no_spk)",
                "line" => __LINE__,
            );
            echo json_encode($result);
        }

//        cekHijau("======================================= BERHASIL COMMIT =======================================");

    }
}