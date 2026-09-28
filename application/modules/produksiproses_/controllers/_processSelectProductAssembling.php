<?php

require_once "Modul_Controller.php";

class _processSelectProductAssembling extends Modul_Controller
{
    public function __construct()
    {
        parent::__construct();
//        $this->jenisTr = $this->uri->segment(4);
//        $cCode = "_TR_" . $this->jenisTr;
//arrPrint($this->configValuesJenis);
//        die();
    }

    public function select()
    {
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();
        // arrPrint($this->uri->segment_array());
//        arrPrintWebs($_GET);
        $pid = isset($_GET['pid']) ? $_GET['pid'] : $this->uri->segment(5);// produkID BOM
        $faseID = $this->uri->segment(6);
        $id = $_GET['id'];// produkID Fase
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;
        $addJml = isset($_GET['addJml']) ? $_GET['addJml'] : 0;
        $stepNum = $this->uri->segment(5) > 0 ? $this->uri->segment(5) : 1;

        $cCode = "_TR_" . $this->jenisTr;
        $arrComponents = $_SESSION[$cCode]["items_komposisi"];
        $selectorModel = $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = $this->configUi[$this->jenisTr]['selectorSrcModel'];

        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();

        // region resetor session komposisi
        if ((isset($_SESSION[$cCode]['items'])) && (!array_key_exists($id, $_SESSION[$cCode]['items']))) {

            $session_komposisi = array(
                "items",
                "items2",
                "items2_sum",
                "items3_sum",
                "items4_sum",
                "items6_sum",
                "items7_sum",
                "items_komposisi",
                "items9_sum",
                "items10_sum",
            );
            foreach ($session_komposisi as $ses_komp) {
                if (isset($_SESSION[$cCode][$ses_komp])) {
                    $_SESSION[$cCode][$ses_komp] = NULL;
                    unset($_SESSION[$cCode][$ses_komp]);
                }
            }

        }
        // endregion resetor session komposisi


        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $componentAssConfig = isset($this->configUi[$this->jenisTr]['componentsAss']) ? $this->configUi[$this->jenisTr]['componentsAss'] : array();
        $componentFaseConfig = isset($this->configUi[$this->jenisTr]['componentsFase']) ? $this->configUi[$this->jenisTr]['componentsFase'] : array();
        $updateHit = isset($this->configUi[$this->jenisTr]['itemHits']) ? $this->configUi[$this->jenisTr]['itemHits'] : false;

        $tmpB = $b->lookupByID($pid)->result();
// matiHere($this->db->last_query()."||".$selectorSrcModel);

        //region metode baru
        $komposisiAll = $_SESSION[$cCode]["items2"];
        if (!isset($komposisiAll[$id]["produk"])) {
            matiHEre("komposisi fase produksi belum diseting, silahkan hubungi admin data untuk melakukan seting komposisi fase produksi $faseID, code: " . __LINE__);
        }
        else {
            $kompisisiProdukFase = $komposisiAll[$id]["produk"];
        }
        if (!isset($_SESSION[$cCode]['items'][$id])) {
            /*
             * session dibuild di selectorFase saat buka halaman, tidak dibuilkan ulang disini
             */
            matiHere("gagal memuat data komposisi, silahkan refersh halaman");
        }
        else {
            cekUngu("SUDAH ADA ITEMS, GANTI QTyy");
            if (isset($_GET['newQty'])) {
                cekUngu("HAHAHA :: " . $_GET['newQty']);
                $_SESSION[$cCode]['items'][$id]['jml'] = $_GET['newQty'];
                $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
            }
            else {
                if ($addJml == 0) {
                    $_SESSION[$cCode]['items'][$id]['jml'] = 1;
                    $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
                }
                else {
                    $_SESSION[$cCode]['items'][$id]['jml'] += $jml;
                    $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
                }
            }
            if (sizeof($itemNumLabels) > 0) {
                echo("iterating subNums..");
                foreach ($itemNumLabels as $key => $label) {
                    if (isset($_GET[$key]) && $_GET[$key] > 0) {
                        $newValue = $_GET[$key];
                        $tmp[$key] = $newValue;
                        $_SESSION[$cCode]['items'][$id][$key] = $newValue;
                        echo "replacing value for $key with " . $newValue . "<br>";
                    }
                }

                foreach ($itemNumLabels as $key => $label) {
                    $_SESSION[$cCode]['items'][$id]["sub_" . $key] = ($_SESSION[$cCode]['items'][$id][$key] * $_SESSION[$cCode]['items'][$id]["jml"]);
                }

                $_SESSION[$cCode]['items'][$id]['sub_nett'] = ($_SESSION[$cCode]['items'][$id]['nett'] * $_SESSION[$cCode]['items'][$id]['jml']);
                $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
            }

            if (isset($_SESSION[$cCode]['items2'][$id]) && sizeof($_SESSION[$cCode]['items2'][$id]) > 0) {
                foreach ($_SESSION[$cCode]['items2'][$id]['produk'] as $e => $eSpec) {
                    $_SESSION[$cCode]['items2'][$id]['produk'][$e]['jml'] = isset($arrComponents[$id]['produk'][$e]['jml']) ? ($arrComponents[$id]['produk'][$e]['jml'] * $_SESSION[$cCode]['items'][$id]['jml']) : 0;
                }
                foreach ($_SESSION[$cCode]['items2'][$id]['biaya'] as $e => $eSpec) {
                    $_SESSION[$cCode]['items2'][$id]['biaya'][$e]['jml'] = isset($arrComponents[$id]['biaya'][$e]['jml']) ? ($arrComponents[$id]['biaya'][$e]['jml'] * $_SESSION[$cCode]['items'][$id]['jml']) : 0;
                    $_SESSION[$cCode]['items2'][$id]['biaya'][$e]['sub_nilai'] = isset($arrComponents[$id]['biaya'][$e]['jml']) ? ($arrComponents[$id]['biaya'][$e]['nilai'] * $_SESSION[$cCode]['items'][$id]['jml']) : 0;
                }
            }
        }
        if (sizeof($kompisisiProdukFase) > 0) {
            arrPrint($kompisisiProdukFase);
        }


        // arrPrint($kompisisiProdukFase);
// matiHEre();


        //endregion
        // $arrComponents = array();
        // $arrComponentsProduk = array();
        // $arrComponentsByFase = array();
        // $arrComponentsByFaseProduk = array();
        // $arrCostComponentsValidate = array();

        // cekMErah($componentFaseConfig['model']);
//         if (sizeof($componentFaseConfig) > 0) {
//             $this->load->model("Mdls/" . $componentFaseConfig['model']);
//             $pk = New $componentFaseConfig['model']();
//             $pk->setSortBy(array(
//                 "kolom" => "produk_dasar_id",
//                 "mode" => "ASC",
//             ));
//             $pk->setFilters(array());
//             $pk->addFilter("status=1");
//             $pk->addFilter("trash=0");
//             $pk->addFilter("fase_id=$faseID");
//
//             $tmpPK = $pk->lookupByPID($pid)->result();
//             showLast_query("biru");
//             $c1 = 0;
//             $c2 = 0;
//             $c3 = 0;
//             $arrProdukTarget = array();
//             if (sizeof($tmpPK) > 0) {
//                 foreach ($tmpPK as $e => $eSpec) {
// //                    arrPrintWebs($eSpec);
//                     if ($eSpec->jenis == "produk") {
//                         $c1++;
//                         $arrComponentsByFase[$id][$eSpec->fase_id][$eSpec->jenis][$eSpec->produk_dasar_id] = array(
//                             "handler" => "",
//                             "id" => $eSpec->produk_dasar_id,
//                             "nama" => $eSpec->produk_dasar_nama,
//                             "jml" => $eSpec->jml,
//                             "satuan_id" => $eSpec->satuan_id,
//                             "satuan" => $eSpec->satuan,
//                             "nilai" => $eSpec->nilai,
//                             "sub_nilai" => $eSpec->jml * $eSpec->nilai,
//                             "harga" => $eSpec->harga,
//                             "sub_harga" => $eSpec->jml * $eSpec->harga,
//                             "gudang_id" => $eSpec->gudang_id,
//                             "gudang_nama" => $eSpec->gudang_nama,
//                             "gudang2_id" => $eSpec->gudang2_id,
//                             "gudang2_nama" => $eSpec->gudang2_nama,
//                             "gudang_source_id" => $eSpec->gudang_id,
//                             "gudang_source_nama" => $eSpec->gudang_nama,
//                             "gudang_target_id" => $eSpec->gudang2_id,
//                             "gudang_target_nama" => $eSpec->gudang2_nama,
//                         );
//                         $arrComponentsByFaseProduk[$id][$eSpec->fase_id][$eSpec->jenis][$eSpec->produk_dasar_id] = (array)$eSpec;
//                     }
//                     elseif ($eSpec->jenis == "biaya") {
//                         $c2++;
//                         $arrComponentsByFase[$id][$eSpec->fase_id][$eSpec->jenis][$c2] = array(
//                             "handler" => "",
//                             "id" => $eSpec->produk_dasar_id,
//                             "nama" => $eSpec->produk_dasar_nama,
//                             "jml" => $eSpec->jml,
//                             "satuan_id" => $eSpec->satuan_id,
//                             "satuan" => $eSpec->satuan,
//                             "nilai" => $eSpec->nilai,
//                             "sub_nilai" => $eSpec->jml * $eSpec->nilai,
//                             "harga" => $eSpec->harga,
//                             "sub_harga" => $eSpec->jml * $eSpec->harga,
//                             "gudang_id" => $eSpec->gudang_id,
//                             "gudang_nama" => $eSpec->gudang_nama,
//                             "gudang2_id" => $eSpec->gudang2_id,
//                             "gudang2_nama" => $eSpec->gudang2_nama,
//                             "gudang_source_id" => $eSpec->gudang_id,
//                             "gudang_source_nama" => $eSpec->gudang_nama,
//                             "gudang_target_id" => $eSpec->gudang2_id,
//                             "gudang_target_nama" => $eSpec->gudang2_nama,
//                             //-----
// //                            "costID_coa_".$c2 => $ppbResult[$eSpec->produk_dasar_id]["coa_code"],
// //                            "cost2ID_coa_".$c2 => $ppbResult[$eSpec->produk_dasar_id]["coa_code_2"],
//                         );
//                         $arrComponentsByFaseProduk[$id][$eSpec->fase_id][$eSpec->jenis][$c2] = (array)$eSpec;
// //                        $arrComponentsByFaseProduk[$id][$eSpec->fase_id][$eSpec->jenis][$c2]["costID_coa_".$c2] = $ppbResult[$eSpec->produk_dasar_id]["coa_code"];
// //                        $arrComponentsByFaseProduk[$id][$eSpec->fase_id][$eSpec->jenis][$c2]["cost2ID_coa_".$c2] = $ppbResult[$eSpec->produk_dasar_id]["coa_code_2"];
// //                        $_SESSION[$cCode]['main']['costID_' . $c2] = $eSpec->produk_dasar_id;
// //                        $_SESSION[$cCode]['main']['costName_' . $c2] = $eSpec->produk_dasar_nama;
// //                        $arrCostComponentsValidate[][$eSpec->produk_dasar_id] = $eSpec->produk_dasar_nama;
//                     }
//                     elseif ($eSpec->jenis == "target") {
//                         $c3++;
// //                        $arrComponentsByFase[$id][$eSpec->fase_id][$eSpec->jenis][$c1] = array(
// //                            "handler" => "",
// //                            "id" => $eSpec->produk_dasar_id,
// //                            "nama" => $eSpec->produk_dasar_nama,
// //                            "jml" => $eSpec->jml,
// //                            "satuan" => $eSpec->satuan_nama,
// //                            "nilai" => $eSpec->nilai,
// //                            "sub_nilai" => $eSpec->jml * $eSpec->nilai,
// //                            "harga" => $eSpec->harga,
// //                            "sub_harga" => $eSpec->jml * $eSpec->harga,
// //                            "gudang_id" => $eSpec->gudang_id,
// //                            "gudang_nama" => $eSpec->gudang_nama,
// //                            "gudang2_id" => $eSpec->gudang2_id,
// //                            "gudang2_nama" => $eSpec->gudang2_nama,
// //                            "gudang_source_id" => $eSpec->gudang_id,
// //                            "gudang_source_nama" => $eSpec->gudang_nama,
// //                            "gudang_target_id" => $eSpec->gudang2_id,
// //                            "gudang_target_nama" => $eSpec->gudang2_nama,
// //                        );
// //                        $arrComponentsByFaseProduk[$id][$eSpec->fase_id][$eSpec->jenis][$c1] = (array)$eSpec;
//                         $arrComponentsByFase[$id][$eSpec->fase_id][$eSpec->jenis][$eSpec->produk_dasar_id] = array(
//                             "handler" => "",
//                             "id" => $eSpec->produk_dasar_id,
//                             "nama" => $eSpec->produk_dasar_nama,
//                             "jml" => $eSpec->jml,
//                             "satuan_id" => $eSpec->satuan_id,
//                             "satuan" => $eSpec->satuan,
//                             "nilai" => $eSpec->nilai,
//                             "sub_nilai" => $eSpec->jml * $eSpec->nilai,
//                             "harga" => $eSpec->harga,
//                             "sub_harga" => $eSpec->jml * $eSpec->harga,
//                             "gudang_id" => $eSpec->gudang_id,
//                             "gudang_nama" => $eSpec->gudang_nama,
//                             "gudang2_id" => $eSpec->gudang2_id,
//                             "gudang2_nama" => $eSpec->gudang2_nama,
//                             "gudang_source_id" => $eSpec->gudang_id,
//                             "gudang_source_nama" => $eSpec->gudang_nama,
//                             "gudang_target_id" => $eSpec->gudang2_id,
//                             "gudang_target_nama" => $eSpec->gudang2_nama,
//                         );
//                         $arrComponentsByFaseProduk[$id][$eSpec->fase_id][$eSpec->jenis][$eSpec->produk_dasar_id] = (array)$eSpec;
//                         $arrProdukTarget[$eSpec->produk_dasar_id] = $eSpec->produk_dasar_id;
//                     }
//                 }
//                 foreach ($arrComponentsByFaseProduk[$id] as $fase_id => $spec) {
//                     $ctr_ii = 0;
//                     foreach ($spec["biaya"] as $ii => $subspec) {
//                         $ctr_ii++;
//                         if (isset($spec["biaya"][$ii])) {
//                             unset($arrComponentsByFase[$id][$fase_id]["biaya"][$ii]);
//                             unset($arrComponentsByFaseProduk[$id][$fase_id]["biaya"][$ii]);
//                             $arrComponentsByFase[$id][$fase_id]["biaya"][$ctr_ii] = $subspec;
//                             $arrComponentsByFaseProduk[$id][$fase_id]["biaya"][$ctr_ii] = $subspec;
//                         }
//                         $arrComponentsByFase[$id][$fase_id]["biaya"][$ctr_ii]["costID_" . $ctr_ii . "_coa"] = $ppbResult[$subspec['produk_dasar_id']]["coa_code"];
//                         $arrComponentsByFase[$id][$fase_id]["biaya"][$ctr_ii]["cost2ID_" . $ctr_ii . "_coa"] = $ppbResult[$subspec['produk_dasar_id']]["coa_code_2"];
//                         $arrComponentsByFase[$id][$fase_id]["biaya"][$ctr_ii]["efisiensiID_" . $ctr_ii . "_coa"] = $ppbResult[$subspec['produk_dasar_id']]["coa_code_2"];
//                         $arrComponentsByFaseProduk[$id][$fase_id]["biaya"][$ctr_ii]["costID_" . $ctr_ii . "_coa"] = $ppbResult[$subspec['produk_dasar_id']]["coa_code"];
//                         $arrComponentsByFaseProduk[$id][$fase_id]["biaya"][$ctr_ii]["cost2ID_" . $ctr_ii . "_coa"] = $ppbResult[$subspec['produk_dasar_id']]["coa_code_2"];
//                         $arrComponentsByFaseProduk[$id][$fase_id]["biaya"][$ctr_ii]["efisiensiID_" . $ctr_ii . "_coa"] = $ppbResult[$subspec['produk_dasar_id']]["coa_code_2"];
//                     }
//                 }
//             }
//             else {
//                 $arrComponents[$id] = array();
//             }
//         }
        // if (sizeof($componentAssConfig) > 0) {
        // $this->load->model("Mdls/" . $componentAssConfig['model']);
        // $this->load->model("Mdls/MdlProdukRakitanPreBiaya");
        //
        // $pk = New $componentAssConfig['model']();
        // $pk->setSortBy(array(
        //     "kolom" => "produk_dasar_id",
        //     "mode" => "ASC",
        // ));
        // $tmpPK = $pk->lookupByPID($id)->result();
        // showLast_query("biru");
        //
        // $ppb = New MdlProdukRakitanPreBiaya();
        // $ppbTmp = $ppb->lookupAll()->result();
        // $ppbResult = array();
        // foreach ($ppbTmp as $ppbSpec) {
        //     $ppbResult[$ppbSpec->id] = array(
        //         "id" => $ppbSpec->id,
        //         "nama" => $ppbSpec->nama,
        //         "coa_code" => $ppbSpec->coa_code,
        //         "coa_code_2" => $ppbSpec->coa_code_2,
        //     );
        // }

//
//             $c1 = 0;
//             $c2 = 0;
//             if (sizeof($tmpPK) > 0) {
//                 foreach ($tmpPK as $e => $eSpec) {
// //                    arrPrint($eSpec);
//                     if (($eSpec->jenis == "produk") && (!in_array($eSpec->produk_dasar_id, $arrProdukTarget))) {
//                         $c1++;
//                         $arrComponents[$id][$eSpec->jenis][$c1] = array(
//                             "handler" => "",
//                             "id" => $eSpec->produk_dasar_id,
//                             "nama" => $eSpec->produk_dasar_nama,
//                             "jml" => $eSpec->jml,
//                             "satuan" => $eSpec->satuan_nama,
//                             "nilai" => $eSpec->nilai,
//                             "sub_nilai" => $eSpec->jml * $eSpec->nilai,
//                             "harga" => $eSpec->harga,
//                             "sub_harga" => $eSpec->jml * $eSpec->harga,
//                         );
//                         $arrComponentsProduk[$id][$eSpec->jenis][$c1] = (array)$eSpec;
//                     }
//                     else if ($eSpec->jenis == "biaya") {
//                         $c2++;
//                         $arrComponents[$id][$eSpec->jenis][$c2] = array(
//                             "handler" => "",
//                             "id" => $eSpec->produk_dasar_id,
//                             "nama" => $eSpec->produk_dasar_nama,
//                             "jml" => $eSpec->jml,
//                             "satuan" => $eSpec->satuan_nama,
//                             "nilai" => $eSpec->nilai,
//                             "sub_nilai" => $eSpec->jml * $eSpec->nilai,
//                             //-----
//                             // "costID_" . $c2 . "_coa" => $ppbResult[$eSpec->produk_dasar_id]["coa_code"],
//                             // "cost2ID_" . $c2 . "_coa" => $ppbResult[$eSpec->produk_dasar_id]["coa_code_2"],
//                         );
//                         cekHijau("$c2 ==== " . $eSpec->produk_dasar_id . " === " . $eSpec->jenis . " >>>> " . $ppbResult[$eSpec->produk_dasar_id]["coa_code"]);
// //                        arrPrint($arrComponents);
//                         $arrComponentsProduk[$id][$eSpec->jenis][$c2] = (array)$eSpec;
//                         $arrComponentsProduk[$id][$eSpec->jenis][$c2]["costID_" . $c2 . "_coa"] = $ppbResult[$eSpec->produk_dasar_id]["coa_code"];
//                         $arrComponentsProduk[$id][$eSpec->jenis][$c2]["cost2ID_" . $c2 . "_coa"] = $ppbResult[$eSpec->produk_dasar_id]["coa_code_2"];
//                         $_SESSION[$cCode]['main']['costID_' . $c2] = $eSpec->produk_dasar_id;
//                         $_SESSION[$cCode]['main']['costName_' . $c2] = $eSpec->produk_dasar_nama;
//                         $_SESSION[$cCode]['main']['costID_' . $c2 . "_coa"] = $ppbResult[$eSpec->produk_dasar_id]["coa_code"];
//                         $_SESSION[$cCode]['main']['costName_' . $c2 . "_coa"] = $eSpec->produk_dasar_nama;
//                         $_SESSION[$cCode]['main']['efisiensiID_' . $c2 . "_coa"] = $ppbResult[$eSpec->produk_dasar_id]["coa_code_2"];
//                         $_SESSION[$cCode]['main']['efisiensiName_' . $c2 . "_coa"] = $eSpec->produk_dasar_nama;
//                         $arrCostComponentsValidate[][$eSpec->produk_dasar_id] = $eSpec->produk_dasar_nama;
//                     }
//                 }
//             }
//             else {
//                 $arrComponents[$id] = array();
//             }


        // $this->load->model("Mdls/MdlProdukFase");
        // $pf = New MdlProdukFase();
        // $pf->addFilter("produk_id='$id'");
        // $pfTmp = $pf->lookupAll()->result();
        // $arrProdukFase = array();
        // foreach ($pfTmp as $pfSpec) {
        //     $arrProdukFase[$pfSpec->urut] = (array)$pfSpec;
        // }

        // }

//        arrPrintPink($arrComponentsByFase);
//        arrPrintKuning($arrComponentsByFaseProduk);
// matiHEre(__LINE__);

        if (sizeof($arrComponents[$id]) == 0) {
            $msg = "belum ada data komposisi produk, harap di setUp terlebih dahulu via login holding.";
            cekMerah($msg);
            die(lgShowAlert($msg));
        }
//         if (sizeof($arrCostComponentsValidate) == 0) {
//             $msg = "belum ada data Standart Cost By Product, harap di setUp terlebih dahulu via login holding.";
// //            cekMerah($msg);
// //            die(lgShowAlert($msg));
//         }

        $pakai_ini = 0;
        if ($pakai_ini == 1) {

            if (isset($_SESSION[$cCode]['pairs']['stokSupplies'])) {
//            arrPrint($_SESSION[$cCode]['pairs']['stokSupplies']);
                //============ AUTO QTY MENGIKUTI STOK YANG TERSEDIA =============
                $tmpEstStok = array();
                if (isset($arrComponents[$id]['produk'])) {
                    foreach ($arrComponents[$id]['produk'] as $com) {
                        $bahanID = $com['id'];
                        if ($_SESSION[$cCode]['pairs']['stokSupplies']) {
                            $tmpEstStok[$bahanID] = isset($_SESSION[$cCode]['pairs']['stokSupplies'][$bahanID]) && $_SESSION[$cCode]['pairs']['stokSupplies'][$bahanID] * 1 > 0 ? $_SESSION[$cCode]['pairs']['stokSupplies'][$bahanID] / $com['jml'] : 0;
                        }
                    }
                }
                $estimasi_stok = min($tmpEstStok);
                if (!isset($_GET['jml'])) {
                    $_GET['newQty'] = $estimasi_stok;
                    $jml = $estimasi_stok;
                    $tmpJml = $estimasi_stok;
                }
                //============ AUTO QTY MENGIKUTI STOK YANG TERSEDIA =============
            }

        }

        // menyimpan komposisi mentah ke session, akumulasi dalam 1 BOM
        // $_SESSION[$cCode]['items_komposisi'][$id] = $arrComponentsProduk[$id];
        // // menyimpan komposisi mentah ke session, 1 BOM dengan fasenya
        // $_SESSION[$cCode]['items9_sum'][$id] = $arrComponentsByFase[$id];
        // $_SESSION[$cCode]['items10_sum'][$id] = $arrComponentsByFaseProduk[$id];
        // $_SESSION[$cCode]['items6_sum'] = $arrProdukFase;


        if (sizeof($tmpB) > 0) {
//             foreach ($tmpB as $row) {
//                 $rows = $row;
//                 $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
//                 $nama = isset($row->nama) > 0 ? $row->nama : "n/a";
//                 $tmpJml = 1;
//
//                 $_SESSION[$cCode]['main']['bomProdukID'] = $id;
//                 $_SESSION[$cCode]['main']['bomProdukNama'] = $nama;
//                 $_SESSION[$cCode]['main']['bomProdukName'] = $nama;
//                 $_SESSION[$cCode]['main']['bom_produk_id'] = $id;
//                 $_SESSION[$cCode]['main']['bom_produk_nama'] = $nama;
//                 $_SESSION[$cCode]['main']['bom_produk_name'] = $nama;
//
//                 if (isset($lockerConfig['enabled']) && $lockerConfig['enabled'] == true) {
//                     cekMerah("masuk locker config");
//
//                     $mdlName = $lockerConfig['mdlName'];
//                     $this->load->model("Mdls/" . $mdlName);
//                     $c = new $mdlName();
//                     $c->addFilter("produk_id='$id'");
//                     $c->addFilter("state='active'");
//                     $c->addFilter("cabang_id=" . $this->session->login['cabang_id']);
//                     $c->addFilter("gudang_id=" . $this->session->login['gudang_id']);
//                     $tmpC = $c->lookupAll($id)->result();
//                     cekHere($this->db->last_query());
//
//                     if (sizeof($tmpC) > 0) {
//                         arrPrint($tmpC);
//                         foreach ($tmpC as $row) {
//                             $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
//                             $nama = $row->nama;
//
//                             $jml_now = $row->jumlah;
//                             if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
//                                 $jml_sudah_diambil = 0;
//                                 $jml_diperlukan = 1;
//                                 $jml_nambah = 1;
//                             }
//                             else {
//                                 if (isset($_GET['newQty'])) {
//                                     $jml_sudah_diambil = $_SESSION[$cCode]['items'][$id]['jml'];
//                                     $jml_diperlukan = $_GET['newQty'];
//                                     $jml_nambah = $jml_diperlukan - $jml_sudah_diambil;
//                                 }
//                                 else {
//                                     $jml_sudah_diambil = $_SESSION[$cCode]['items'][$id]['jml'];
//                                     $jml_diperlukan = $jml_sudah_diambil + $jml;
//                                     $jml_nambah = $jml;
//                                 }
//                             }
//                             //  region validasi stok
//                             if ($jml_nambah > $jml_now) {
//                                 echo "<script>top.alert('stok $nama tidak cukup. (perlu $jml_diperlukan, nambah $jml_nambah stok $jml_now)')";
//                                 echo "</script>";
//                                 die();
//                             }
//                             //  endregion validasi stok
//
//                             $this->db->trans_start();
//
//                             //  region update locker active
//                             $where = array(
//                                 "id" => $row->id,
//                             );
//                             $data_active = array(
//                                 "jumlah" => $jml_now - $jml_nambah,
//                                 "state" => "active",
//                             );
//                             $c->updateData($where, $data_active);
//                             cekHere($this->db->last_query());
//                             //  endregion update locker active
//
//                             //  region locker hold
//                             $array_hold_sebelumnya = $c->cekLoker($this->session->login['cabang_id'], $id, "hold", $this->session->login['id'], "0", $this->session->login['gudang_id']);
//                             if (sizeof($array_hold_sebelumnya) > 0) {
//                                 $where = array(
//                                     "id" => $array_hold_sebelumnya['id'],
//                                 );
//                                 $data_hold = array(
//                                     "jumlah" => $array_hold_sebelumnya['jumlah'] + $jml_nambah,
//                                 );
//                                 $c->updateData($where, $data_hold);
//                                 cekHere($this->db->last_query());
//                             }
//                             else {
//                                 $data_hold = array(
//                                     "jenis" => "produk",
//                                     "cabang_id" => $this->session->login['cabang_id'],
//                                     "produk_id" => $id,
//                                     "nama" => $nama,
//                                     "satuan" => $row->satuan,
//                                     "state" => "hold",
//                                     "jumlah" => $jml_nambah,
//                                     "oleh_id" => $this->session->login['id'],
//                                     "oleh_nama" => $this->session->login['nama'],
//                                     "gudang_id" => $this->session->login['gudang_id'],
//                                 );
//                                 $c->addData($data_hold);
//                                 cekHere($this->db->last_query());
//                             }
//                             //  endregion locker hold
//                             $this->db->trans_complete() or die("Gagal bro");
//                             $tmpJml = $jml_diperlukan;
//
//                         }
//                     }
//                     else {
//                         mati_disini("tidak ditemukan item " . $row->nama . " di locker stock.");
//                     }
//                 }
//
//                 $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
//
//                 if (!isset($_SESSION[$cCode]['items'][$id])) {
// //                    cekUngu("BELUM ADA ITEMS");
//                     $tmp = array(
//                         "handler" => $this->modul . "/" . $this->uri->segment(2),
//                         "id" => $id,
//                         "jml" => $tmpJml,
//                         "harga" => 0,
//                         "subtotal" => 0,
//                     );
//                     if (sizeof($priceConfig) > 0) {
//                         $mdlName = $priceConfig['model'];
//                         $this->load->model("Mdls/" . $mdlName);
//                         $h = new $mdlName();
//                         $h->addFilter("produk_id='$id'");
//                         $h->addFilter("status='1'");
//                         $h->addFilter("jenis_value in ('" . implode("','", $priceConfig['label']) . "')");
//                         $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
//                         $tmpH = $h->lookupAll($id)->result();
//                         cekMerah($this->db->last_query());
//                         if (sizeof($tmpH) > 0) {
//                             $rawPrices = array();
//                             foreach ($tmpH as $hSpec) {
//                                 foreach ($priceConfig['key_label'] as $key => $val) {
//                                     if ($key == $hSpec->jenis_value) {
//                                         $rawPrices[$key] = isset($hSpec->nilai) ? $hSpec->nilai : 0;
//                                     }
//                                 }
//                             }
//                             $prices = normalizePrices("produk", $rawPrices);
//                             if (sizeof($prices) > 0) {
//                                 foreach ($prices as $k => $v) {
//                                     $tmp[$k] = $v;
//                                 }
//                                 $tmp['harga'] = isset($tmp[$priceConfig['mainSrc']]) ? $tmp[$priceConfig['mainSrc']] : 0;
//                             }
//                         }
//                     }
//                     foreach ($fieldSrcs as $key => $src) {
//                         $tmp[$key] = makeValue($src, $tmp, $tmp, $rows->$src);
//                     }
//
//                     //region perhitungan subtotal
//                     if ($subAmountConfig != null) {
//                         $subtotal = makeValue($subAmountConfig, $tmp, $tmp, 0);
//                     }
//                     else {
//                         $subtotal = 0;
//                     }
//                     $tmp["subtotal"] = $subtotal;
//                     $_SESSION[$cCode]['items'][$id] = $tmp;
//                     //endregion
//
//                     $tmpRslt = $arrComponents[$id];
//                     $_SESSION[$cCode]['items2'][$id] = $tmpRslt;
//                 }
//                 else {
//                     cekUngu("SUDAH ADA ITEMS, GANTI QTyy");
//                     if (isset($_GET['newQty'])) {
//                         cekUngu("HAHAHA :: " . $_GET['newQty']);
//                         $_SESSION[$cCode]['items'][$id]['jml'] = $_GET['newQty'];
//                         $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
//                     }
//                     else {
//                         if ($addJml == 0) {
//                             $_SESSION[$cCode]['items'][$id]['jml'] = 1;
//                             $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
//                         }
//                         else {
//                             $_SESSION[$cCode]['items'][$id]['jml'] += $jml;
//                             $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
//                         }
//                     }
//                     if (sizeof($itemNumLabels) > 0) {
//                         echo("iterating subNums..");
//                         foreach ($itemNumLabels as $key => $label) {
//                             if (isset($_GET[$key]) && $_GET[$key] > 0) {
//                                 $newValue = $_GET[$key];
//                                 $tmp[$key] = $newValue;
//                                 $_SESSION[$cCode]['items'][$id][$key] = $newValue;
//                                 echo "replacing value for $key with " . $newValue . "<br>";
//                             }
//                         }
//
//                         foreach ($itemNumLabels as $key => $label) {
//                             $_SESSION[$cCode]['items'][$id]["sub_" . $key] = ($_SESSION[$cCode]['items'][$id][$key] * $_SESSION[$cCode]['items'][$id]["jml"]);
//                         }
//
//                         $_SESSION[$cCode]['items'][$id]['sub_nett'] = ($_SESSION[$cCode]['items'][$id]['nett'] * $_SESSION[$cCode]['items'][$id]['jml']);
//                         $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
//                     }
//
//                     if (isset($_SESSION[$cCode]['items2'][$id]) && sizeof($_SESSION[$cCode]['items2'][$id]) > 0) {
//                         foreach ($_SESSION[$cCode]['items2'][$id]['produk'] as $e => $eSpec) {
//                             $_SESSION[$cCode]['items2'][$id]['produk'][$e]['jml'] = isset($arrComponents[$id]['produk'][$e]['jml']) ? ($arrComponents[$id]['produk'][$e]['jml'] * $_SESSION[$cCode]['items'][$id]['jml']) : 0;
//                         }
//                         foreach ($_SESSION[$cCode]['items2'][$id]['biaya'] as $e => $eSpec) {
//                             $_SESSION[$cCode]['items2'][$id]['biaya'][$e]['jml'] = isset($arrComponents[$id]['biaya'][$e]['jml']) ? ($arrComponents[$id]['biaya'][$e]['jml'] * $_SESSION[$cCode]['items'][$id]['jml']) : 0;
//                             $_SESSION[$cCode]['items2'][$id]['biaya'][$e]['sub_nilai'] = isset($arrComponents[$id]['biaya'][$e]['jml']) ? ($arrComponents[$id]['biaya'][$e]['nilai'] * $_SESSION[$cCode]['items'][$id]['jml']) : 0;
//                         }
//                     }
//                 }
//             }

            if (sizeof($_SESSION[$cCode]['items']) > 0) {
                $_SESSION[$cCode]['main']['harga'] = 0;
                foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                    $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
                }
            }
            if (sizeof($_SESSION[$cCode]['items2']) > 0) {
//                cekBiru("bulding summary item_result...");
                $_SESSION[$cCode]['items2_sum'] = array();// supplies-nya...
                $_SESSION[$cCode]['items3_sum'] = array();// biaya-nya...
                foreach ($_SESSION[$cCode]['items2'] as $pID => $pSpec) {
                    foreach ($pSpec as $jenis => $jSpec) {
                        foreach ($jSpec as $eSpec) {
                            if ($jenis == "produk") {
                                if (!isset($_SESSION[$cCode]['items2_sum'][$eSpec['produk_dasar_id']])) {
                                    $_SESSION[$cCode]['items2_sum'][$eSpec['produk_dasar_id']] = $eSpec;
                                    $_SESSION[$cCode]['items2_sum'][$eSpec['produk_dasar_id']]['jml'] = 0;
                                    $_SESSION[$cCode]['items2_sum'][$eSpec['produk_dasar_id']]['produk_ids'] = array();
                                    $_SESSION[$cCode]['items2_sum'][$eSpec['produk_dasar_id']]["id"] = $eSpec['produk_dasar_id'];
                                    $_SESSION[$cCode]['items2_sum'][$eSpec['produk_dasar_id']]["nama"] = $eSpec['produk_dasar_nama'];
                                    $_SESSION[$cCode]['items2_sum'][$eSpec['produk_dasar_id']]["name"] = $eSpec['produk_dasar_nama'];
                                }
                                $_SESSION[$cCode]['items2_sum'][$eSpec['produk_dasar_id']]['jml'] += $eSpec['jml'];
                                $_SESSION[$cCode]['items2_sum'][$eSpec['produk_dasar_id']]['produk_ids'][$pID] = $pID;
                                $_SESSION[$cCode]['items2_sum'][$eSpec['produk_dasar_id']]['nilai_bom'] = $eSpec["nilai"];
                            }
                            if ($jenis == "biaya") {
                                if (!isset($_SESSION[$cCode]['items3_sum'][$eSpec['cat_id']])) {
                                    $_SESSION[$cCode]['items3_sum'][$eSpec['cat_id']] = $eSpec;
                                    $_SESSION[$cCode]['items3_sum'][$eSpec['cat_id']]['jml'] = 0;
                                    $_SESSION[$cCode]['items3_sum'][$eSpec['cat_id']]['sub_nilai'] = 0;
                                    $_SESSION[$cCode]['items3_sum'][$eSpec['cat_id']]['produk_ids'] = array();
                                    $_SESSION[$cCode]['items3_sum'][$eSpec['cat_id']]['nama'] = $eSpec['produk_dasar_nama'];
                                    $_SESSION[$cCode]['items3_sum'][$eSpec['cat_id']]['name'] = $eSpec['produk_dasar_nama'];
                                }
                                $_SESSION[$cCode]['items3_sum'][$eSpec['cat_id']]['jml'] += $eSpec['jml'];
                                $_SESSION[$cCode]['items3_sum'][$eSpec['cat_id']]['sub_nilai'] += $eSpec['sub_nilai'];
                                $_SESSION[$cCode]['items3_sum'][$eSpec['cat_id']]['produk_ids'][$pID] = $pID;
                            }
                        }
                    }
                }
            }
            if (sizeof($_SESSION[$cCode]['items2_sum']) > 0) {
                foreach ($_SESSION[$cCode]['items2_sum'] as $bID => $pSpec) {
                    $_SESSION[$cCode]['items2_sum'][$bID]['produk_ids'] = blobEncode($pSpec['produk_ids']);
                }
            }
            if (sizeof($_SESSION[$cCode]['items3_sum'])) {
                $oInject = array("cat_id", "cat_nama");
                foreach ($_SESSION[$cCode]['items3_sum'] as $catrData) {

                }
            }

            // kebutuhan per-fasenya
            if (sizeof($_SESSION[$cCode]['items10_sum']) > 0) {
//                cekBiru("bulding summary item_result...");
                $_SESSION[$cCode]['items7_sum'] = array();
                $_SESSION[$cCode]['items8_sum'] = array();
                foreach ($_SESSION[$cCode]['items10_sum'] as $pID => $fpSpec) {
//                    arrPrintPink($fpSpec);
                    foreach ($fpSpec as $fase => $pSpec) {
                        foreach ($pSpec as $jenis => $jSpec) {
                            foreach ($jSpec as $ii => $eSpec) {
                                arrPrintWebs($eSpec);
                                if ($jenis == "produk") {
                                    if (!isset($_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii])) {
                                        $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii] = $eSpec;

                                    }
                                    if (!isset($_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_jml"])) {
                                        $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_jml"] = 0;
                                    }
                                    if (!isset($_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_nilai"])) {
                                        $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_nilai"] = 0;
                                    }
                                    if (!isset($_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_harga"])) {
                                        $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_harga"] = 0;
                                    }
                                    $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_nilai"] += (($eSpec["nilai"] * $eSpec["jml"]) * $_SESSION[$cCode]['items'][$id]['jml']);
                                    $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_jml"] += ($eSpec["jml"] * $_SESSION[$cCode]['items'][$id]['jml']);
                                    $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_harga"] += ($eSpec["harga"] * $_SESSION[$cCode]['items'][$id]['jml']);
                                }
                                if ($jenis == "biaya") {
                                    if (!isset($_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii])) {
                                        $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii] = $eSpec;

                                    }
                                    if (!isset($_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_jml"])) {
                                        $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_jml"] = 0;
                                    }
                                    if (!isset($_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_nilai"])) {
                                        $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_nilai"] = 0;
                                    }
                                    if (!isset($_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_harga"])) {
                                        $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_harga"] = 0;
                                    }
                                    $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_nilai"] += ($eSpec["nilai"] * $_SESSION[$cCode]['items'][$id]['jml']);
//                                    $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_jml"] += ($eSpec["jml"] * $_SESSION[$cCode]['items'][$id]['jml']);
                                    $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_harga"] += ($eSpec["harga"] * $_SESSION[$cCode]['items'][$id]['jml']);
                                }
                                if ($jenis == "target") {
//                                    if (!isset($_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii])) {
//                                        $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii] = $eSpec;
//                                    }
//                                    if (!isset($_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_jml"])) {
//                                        $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_jml"] = 0;
//                                    }
//                                    if (!isset($_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_nilai"])) {
//                                        $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_nilai"] = 0;
//                                    }
//                                    if (!isset($_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_harga"])) {
//                                        $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_harga"] = 0;
//                                    }
//                                    $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_nilai"] += ($eSpec["nilai"] * $_GET["newQty"]);
//                                    $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_jml"] += ($eSpec["jml"] * $_GET["newQty"]);
//                                    $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_harga"] += ($eSpec["harga"] * $_GET["newQty"]);
                                    if (!isset($_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis])) {
                                        $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis] = $eSpec;
                                    }
                                    if (!isset($_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis]["sub_jml"])) {
                                        $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis]["sub_jml"] = 0;
                                    }
                                    if (!isset($_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis]["sub_nilai"])) {
                                        $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis]["sub_nilai"] = 0;
                                    }
                                    if (!isset($_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis]["sub_harga"])) {
                                        $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis]["sub_harga"] = 0;
                                    }
                                    $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis]["sub_nilai"] += (($eSpec["nilai"] * $eSpec["jml"]) * $_SESSION[$cCode]['items'][$id]['jml']);
                                    $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis]["sub_jml"] += ($eSpec["jml"] * $_SESSION[$cCode]['items'][$id]['jml']);
                                    $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis]["sub_harga"] += ($eSpec["harga"] * $_SESSION[$cCode]['items'][$id]['jml']);
                                }
                            }
                        }
                    }
                }
                foreach ($_SESSION[$cCode]['items8_sum'] as $pID => $iiSpec) {
                    $_SESSION[$cCode]['items7_sum'] = $iiSpec;
                }
            }


            //region update item hit
            if ($updateHit) {
                $this->load->model("Mdls/MdlProdukHit");
                $ph = new MdlProdukHit();
                $ph->updateHitProduk($this->modul, $_SESSION[$cCode]['items'][$id], my_cabang_id());
            }
            //endregion
        }
        else {
            cekMerah("tidak ada itemnya!");
            die();
        }

//        cekHitam("pemakaian per-fase");
//        arrPrint($_SESSION[$cCode]['items8_sum']);
//mati_disini(__LINE__);


        $_SESSION[$cCode]["main"]["gudangID_produk"] = getDefaultWarehouseID(my_cabang_id())["gudang_id"];
        $_SESSION[$cCode]["main"]["gudangName_produk"] = getDefaultWarehouseID(my_cabang_id())["gudang_nama"];
        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = array(
            "olehID" => my_id(),
            "olehName" => my_name(),
            "sellerID" => my_id(),
            "sellerName" => my_name(),
            "placeID" => my_cabang_id(),
            "placeName" => my_cabang_nama(),
            "divID" => my_div_id(),
            "divName" => my_div_nama(),
            "cabangID" => my_cabang_id(),
            "cabangName" => my_cabang_nama(),
            "gudangID" => my_gudang_id(),
            "gudangName" => my_gudang_nama(),
            "jenis_usaha" => my_jenis_usaha(),
            "tokoID" => my_toko_id(),
            "tokoNama" => my_toko_nama(),
            "ppnFactor" => my_ppn_factor(),
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
            "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
            "stepNumber" => 1,
            "stepCode" => $this->configUiJenis['steps'][1]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
        );
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);
        fillValues_he_value_builder($this->jenisTr, 1, 1, $this->configCoreJenis, $this->configUiJenis, $this->configValuesJenis, my_ppn_factor());

        /* --------------------------------------------------
         * ngereload shoping cart dlm modul
         * --------------------------------------------------*/
        echo "<script>";
        echo "  if(top.document.getElementById('shopping_cart')){";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "/" . $pid . "?selID=$id&fase_id=$faseID');";
        echo "  }";
        echo "</script>";

    }

    public function selectFase()
    {
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();


        $id = $_GET['id'];// produkID BOM

        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;
        $addJml = isset($_GET['addJml']) ? $_GET['addJml'] : 0;
        $faseID = isset($_GET['fase']) ? $_GET['fase'] : 0;
        $stepNum = $this->uri->segment(5) > 0 ? $this->uri->segment(5) : 1;

        $cCode = "_TR_" . $this->jenisTr;

        $selectorModel = $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = $this->configUi[$this->jenisTr]['selectorSrcModel'];

        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();

        // bila tidak ada session items, maka dimulai dari kosong, bila sudah ada maka biarkan saja...
        if (!isset($_SESSION[$cCode]['items'])) {
            $session_komposisi = array(
                "items",
                "items2",
                "items2_sum",
                "items3_sum",
                "items4_sum",
                "items6_sum",
                "items7_sum",
                "items_komposisi",
                "items8_sum",
                "items9_sum",
                "items10_sum",
                "tableIn_detail",
                "tableIn_detail_values",
                "tableIn_detail_values2",
                "tableIn_detail_values2_sum",
                "tableIn_master_values",
                "tableIn_detail2_sum",
                "rsltItems",
                "rsltItems2",
                "tableIn_detail_values_rsltItems",
                "tableIn_detail_values_rsltItems2",
                "main",
            );
            foreach ($session_komposisi as $ses_komp) {
                if (isset($_SESSION[$cCode][$ses_komp])) {
                    $_SESSION[$cCode][$ses_komp] = NULL;
                    unset($_SESSION[$cCode][$ses_komp]);
                }
            }


            $settingGudang = isset($this->configUi[$this->jenisTr]["settingGudang"]["setting"]) ? $this->configUi[$this->jenisTr]["settingGudang"]["setting"] : null;
            $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
            $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
            $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
            $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
            $componentAssConfig = isset($this->configUi[$this->jenisTr]['componentsAss']) ? $this->configUi[$this->jenisTr]['componentsAss'] : array();
            $componentFaseConfig = isset($this->configUi[$this->jenisTr]['componentsFase']) ? $this->configUi[$this->jenisTr]['componentsFase'] : array();
            $updateHit = isset($this->configUi[$this->jenisTr]['itemHits']) ? $this->configUi[$this->jenisTr]['itemHits'] : false;
            $tmpB = $b->lookupByID($id)->result();

            $this->load->model("Mdls/MdlProdukRakitanPreBiaya");
            $ppb = New MdlProdukRakitanPreBiaya();
            $ppbTmp = $ppb->lookupAll()->result();
            $ppbResult = array();
            foreach ($ppbTmp as $ppbSpec) {
                $ppbResult[$ppbSpec->id] = array(
                    "id" => $ppbSpec->id,
                    "nama" => $ppbSpec->nama,
                    "coa_code" => $ppbSpec->coa_code,
                    "coa_code_2" => $ppbSpec->coa_code_2,
                );
            }
//        arrPrintWebs($ppbResult);
            // matiHere();


            $arrComponents = array();
            $arrComponentsProduk = array();
            $arrComponentsByFase = array();
            $arrComponentsByFaseProduk = array();
            $arrCostComponentsValidate = array();

            if (sizeof($componentFaseConfig) > 0) {
                $this->load->model("Mdls/" . $componentFaseConfig['model']);
                $pk = New $componentFaseConfig['model']();
                $pk->setSortBy(array(
                    "kolom" => "produk_dasar_id",
                    "mode" => "ASC",
                ));
                $pk->setFilters(array());
                $pk->addFilter("status=1");
                $pk->addFilter("trash=0");
                $pk->addFilter("fase_id='$faseID'");
                $tmpPK = $pk->lookupByPID($id)->result();
                // showLast_query("biru");
                // matiHEre();
                $c1 = 0;
                $c2 = 0;
                $c3 = 0;
                $arrProdukTarget = array();
                $arrProdukTargetFase = array();
                // arrPrint($tmpPK);
                if (sizeof($tmpPK) > 0) {
                    foreach ($tmpPK as $e => $eSpec) {
//                    arrPrintWebs($eSpec);
                        if ($eSpec->jenis == "produk") {
                            $c1++;
                            $arrComponentsByFase[$id][$eSpec->fase_id][$eSpec->jenis][$eSpec->produk_dasar_id] = array(
                                "handler" => "",
                                "id" => $eSpec->produk_dasar_id,
                                "nama" => $eSpec->produk_dasar_nama,
                                "jml" => $eSpec->jml,
                                "satuan" => isset($eSpec->satuan_nama) ? $eSpec->satuan_nama : "",
                                "nilai" => $eSpec->nilai,
                                "sub_nilai" => $eSpec->jml * $eSpec->nilai,
                                "harga" => $eSpec->harga,
                                "sub_harga" => $eSpec->jml * $eSpec->harga,
                                "gudang_id" => $eSpec->gudang_id,
                                "gudang_nama" => $eSpec->gudang_nama,
                                "gudang2_id" => $eSpec->gudang2_id,
                                "gudang2_nama" => $eSpec->gudang2_nama,
                                "gudang_source_id" => $eSpec->gudang_id,
                                "gudang_source_nama" => $eSpec->gudang_nama,
                                "gudang_target_id" => $eSpec->gudang2_id,
                                "gudang_target_nama" => $eSpec->gudang2_nama,
                            );
                            $arrComponentsByFaseProduk[$id][$eSpec->fase_id][$eSpec->jenis][$eSpec->produk_dasar_id] = (array)$eSpec;
                        }
                        elseif ($eSpec->jenis == "biaya") {
                            $c2++;
                            $arrComponentsByFase[$id][$eSpec->fase_id][$eSpec->jenis][$c2] = array(
                                "handler" => "",
                                "id" => $eSpec->produk_dasar_id,
                                "nama" => $eSpec->produk_dasar_nama,
                                "jml" => $eSpec->jml,
                                "satuan" => isset($eSpec->satuan_nama) ? $eSpec->satuan_nama : "",
                                "nilai" => $eSpec->nilai,
                                "sub_nilai" => $eSpec->jml * $eSpec->nilai,
                                "harga" => $eSpec->harga,
                                "sub_harga" => $eSpec->jml * $eSpec->harga,
                                "gudang_id" => $eSpec->gudang_id,
                                "gudang_nama" => $eSpec->gudang_nama,
                                "gudang2_id" => $eSpec->gudang2_id,
                                "gudang2_nama" => $eSpec->gudang2_nama,
                                "gudang_source_id" => $eSpec->gudang_id,
                                "gudang_source_nama" => $eSpec->gudang_nama,
                                "gudang_target_id" => $eSpec->gudang2_id,
                                "gudang_target_nama" => $eSpec->gudang2_nama,
                                //-----
                                // "costID_coa_".$c2 => $ppbResult[$eSpec->cat_id]["coa_code"],
                                // "cost2ID_coa_".$c2 => $ppbResult[$eSpec->cat_id]["coa_code_2"],
                            );
                            $arrComponentsByFaseProduk[$id][$eSpec->fase_id][$eSpec->jenis][$c2] = (array)$eSpec;
//                        $arrComponentsByFaseProduk[$id][$eSpec->fase_id][$eSpec->jenis][$c2]["costID_coa_".$c2] = $ppbResult[$eSpec->produk_dasar_id]["coa_code"];
//                        $arrComponentsByFaseProduk[$id][$eSpec->fase_id][$eSpec->jenis][$c2]["cost2ID_coa_".$c2] = $ppbResult[$eSpec->produk_dasar_id]["coa_code_2"];
//                        $_SESSION[$cCode]['main']['costID_' . $c2] = $eSpec->produk_dasar_id;
//                        $_SESSION[$cCode]['main']['costName_' . $c2] = $eSpec->produk_dasar_nama;
//                        $arrCostComponentsValidate[][$eSpec->produk_dasar_id] = $eSpec->produk_dasar_nama;
                        }
                        elseif ($eSpec->jenis == "target") {
                            $c3++;
//                        $arrComponentsByFase[$id][$eSpec->fase_id][$eSpec->jenis][$c1] = array(
//                            "handler" => "",
//                            "id" => $eSpec->produk_dasar_id,
//                            "nama" => $eSpec->produk_dasar_nama,
//                            "jml" => $eSpec->jml,
//                            "satuan" => $eSpec->satuan_nama,
//                            "nilai" => $eSpec->nilai,
//                            "sub_nilai" => $eSpec->jml * $eSpec->nilai,
//                            "harga" => $eSpec->harga,
//                            "sub_harga" => $eSpec->jml * $eSpec->harga,
//                            "gudang_id" => $eSpec->gudang_id,
//                            "gudang_nama" => $eSpec->gudang_nama,
//                            "gudang2_id" => $eSpec->gudang2_id,
//                            "gudang2_nama" => $eSpec->gudang2_nama,
//                            "gudang_source_id" => $eSpec->gudang_id,
//                            "gudang_source_nama" => $eSpec->gudang_nama,
//                            "gudang_target_id" => $eSpec->gudang2_id,
//                            "gudang_target_nama" => $eSpec->gudang2_nama,
//                        );
//                        $arrComponentsByFaseProduk[$id][$eSpec->fase_id][$eSpec->jenis][$c1] = (array)$eSpec;
                            $arrComponentsByFase[$id][$eSpec->fase_id][$eSpec->jenis][$eSpec->produk_dasar_id] = array(
                                "handler" => "",
                                "id" => $eSpec->produk_dasar_id,
                                "nama" => $eSpec->produk_dasar_nama,
                                "jml" => $eSpec->jml,
                                "satuan" => isset($eSpec->satuan_nama) ? $eSpec->satuan_nama : "",
                                "nilai" => $eSpec->nilai,
                                "sub_nilai" => $eSpec->jml * $eSpec->nilai,
                                "harga" => $eSpec->harga,
                                "sub_harga" => $eSpec->jml * $eSpec->harga,
                                "gudang_id" => $eSpec->gudang_id,
                                "gudang_nama" => $eSpec->gudang_nama,
                                "gudang2_id" => $eSpec->gudang2_id,
                                "gudang2_nama" => $eSpec->gudang2_nama,
                                "gudang_source_id" => $eSpec->gudang_id,
                                "gudang_source_nama" => $eSpec->gudang_nama,
                                "gudang_target_id" => $eSpec->gudang2_id,
                                "gudang_target_nama" => $eSpec->gudang2_nama,
                            );
                            $arrComponentsByFaseProduk[$id][$eSpec->fase_id][$eSpec->jenis][$eSpec->produk_dasar_id] = (array)$eSpec;
                            $arrProdukTarget[$eSpec->produk_dasar_id] = $eSpec->produk_dasar_id;
                            $arrProdukTargetFase[$eSpec->fase_id] = $eSpec->produk_dasar_id;
                        }
                    }

                    // arrPrint($arrComponentsByFaseProduk);
                    // matiHere();
                    foreach ($arrComponentsByFaseProduk[$id] as $fase_id => $spec) {
                        $ctr_ii = 0;
                        foreach ($spec["biaya"] as $ii => $subspec) {
                            /*
                             * untuk biaya geser dari produk_dasaar_id ke cat_id karena produk_dasar_id berisi pembantu biaya contoh upah harian, cat_id=directlabor
                             */

                            $ctr_ii++;
                            if (isset($spec["biaya"][$ii])) {
                                unset($arrComponentsByFase[$id][$fase_id]["biaya"][$ii]);
                                unset($arrComponentsByFaseProduk[$id][$fase_id]["biaya"][$ii]);
                                $arrComponentsByFase[$id][$fase_id]["biaya"][$ctr_ii] = $subspec;
                                $arrComponentsByFaseProduk[$id][$fase_id]["biaya"][$ctr_ii] = $subspec;
                            }
                            $arrComponentsByFase[$id][$fase_id]["biaya"][$ctr_ii]["costID_" . $ctr_ii . "_coa"] = $ppbResult[$subspec['cat_id']]["coa_code"];
                            $arrComponentsByFase[$id][$fase_id]["biaya"][$ctr_ii]["cost2ID_" . $ctr_ii . "_coa"] = $ppbResult[$subspec['cat_id']]["coa_code_2"];
                            $arrComponentsByFase[$id][$fase_id]["biaya"][$ctr_ii]["efisiensiID_" . $ctr_ii . "_coa"] = $ppbResult[$subspec['cat_id']]["coa_code_2"];
                            $arrComponentsByFaseProduk[$id][$fase_id]["biaya"][$ctr_ii]["costID_" . $ctr_ii . "_coa"] = $ppbResult[$subspec['cat_id']]["coa_code"];
                            $arrComponentsByFaseProduk[$id][$fase_id]["biaya"][$ctr_ii]["cost2ID_" . $ctr_ii . "_coa"] = $ppbResult[$subspec['cat_id']]["coa_code_2"];
                            $arrComponentsByFaseProduk[$id][$fase_id]["biaya"][$ctr_ii]["efisiensiID_" . $ctr_ii . "_coa"] = $ppbResult[$subspec['cat_id']]["coa_code_2"];

                            $_SESSION[$cCode]["main"]["costID_" . $ctr_ii . ""] = $ppbResult[$subspec['cat_id']]["id"];
                            $_SESSION[$cCode]["main"]["costName_" . $ctr_ii . ""] = $ppbResult[$subspec['cat_id']]["nama"];
                            $_SESSION[$cCode]["main"]["costID_" . $ctr_ii . "_coa"] = $ppbResult[$subspec['cat_id']]["coa_code"];
                            $_SESSION[$cCode]["main"]["cost2ID_" . $ctr_ii . "_coa"] = $ppbResult[$subspec['cat_id']]["coa_code_2"];
                            $_SESSION[$cCode]["main"]["efisiensiID_" . $ctr_ii . "_coa"] = $ppbResult[$subspec['cat_id']]["coa_code_2"];


                        }
                    }
                }
                else {
                    $arrComponents[$id] = array();
                }
            }

            if (sizeof($componentAssConfig) > 0) {
                $this->load->model("Mdls/" . $componentAssConfig['model']);

                $pk = New $componentAssConfig['model']();
                $pk->setSortBy(array(
                    "kolom" => "produk_dasar_id",
                    "mode" => "ASC",
                ));
                $pk->addFilter("fase_id=$faseID");
                $tmpPK = $pk->lookupByPID($id)->result();
//             showLast_query("biru");
// //
// matiHEre(__LINE__."|$fase_id| ".$id);
                $c1 = 0;
                $c2 = 0;
                if (sizeof($tmpPK) > 0) {
                    foreach ($tmpPK as $e => $eSpec) {
//                    arrPrint($eSpec);
                        if (($eSpec->jenis == "produk") && (!in_array($eSpec->produk_dasar_id, $arrProdukTarget))) {
                            $c1++;
                            $arrComponents[$id][$eSpec->jenis][$c1] = array(
                                "handler" => "",
                                "id" => $eSpec->produk_dasar_id,
                                "nama" => $eSpec->produk_dasar_nama,
                                "jml" => $eSpec->jml,
                                "satuan_id" => $eSpec->satuan_id,
                                "satuan" => $eSpec->satuan,
                                "nilai" => $eSpec->nilai,
                                "sub_nilai" => $eSpec->jml * $eSpec->nilai,
                                "harga" => $eSpec->harga,
                                "sub_harga" => $eSpec->jml * $eSpec->harga,
                            );
                            $arrComponentsProduk[$id][$eSpec->jenis][$c1] = (array)$eSpec;
                        }
                        else if ($eSpec->jenis == "biaya") {
                            $c2++;
                            $arrComponents[$id][$eSpec->jenis][$c2] = array(
                                "handler" => "",
                                "id" => $eSpec->produk_dasar_id,
                                "nama" => $eSpec->produk_dasar_nama,
                                "jml" => $eSpec->jml,
                                "satuan_id" => $eSpec->satuan_id,
                                "satuan" => $eSpec->satuan,
                                "nilai" => $eSpec->nilai,
                                "sub_nilai" => $eSpec->jml * $eSpec->nilai,
                                //-----
                                // "costID_" . $c2 . "_coa" => $ppbResult[$eSpec->produk_dasar_id]["coa_code"],
                                // "cost2ID_" . $c2 . "_coa" => $ppbResult[$eSpec->produk_dasar_id]["coa_code_2"],
                                "costID_" . $c2 . "_coa" => $ppbResult[$eSpec->cat_id]["coa_code"],
                                "cost2ID_" . $c2 . "_coa" => $ppbResult[$eSpec->cat_id]["coa_code_2"],
                            );
//                        cekHijau("$c2 ==== " . $eSpec->produk_dasar_id . " === " . $eSpec->jenis . " >>>> " . $ppbResult[$eSpec->produk_dasar_id]["coa_code"]);
//                        arrPrint($arrComponents);
                            $arrComponentsProduk[$id][$eSpec->jenis][$c2] = (array)$eSpec;
                            $arrComponentsProduk[$id][$eSpec->jenis][$c2]["costID_" . $c2 . "_coa"] = $ppbResult[$eSpec->cat_id]["coa_code"];
                            $arrComponentsProduk[$id][$eSpec->jenis][$c2]["cost2ID_" . $c2 . "_coa"] = $ppbResult[$eSpec->cat_id]["coa_code_2"];
//                        $_SESSION[$cCode]['main']['costID_' . $c2] = $eSpec->produk_dasar_id;
//                        $_SESSION[$cCode]['main']['costName_' . $c2] = $eSpec->produk_dasar_nama;
//                        $_SESSION[$cCode]['main']['costID_' . $c2 . "_coa"] = $ppbResult[$eSpec->produk_dasar_id]["coa_code"];
//                        $_SESSION[$cCode]['main']['costName_' . $c2 . "_coa"] = $eSpec->produk_dasar_nama;
//                        $_SESSION[$cCode]['main']['efisiensiID_' . $c2 . "_coa"] = $ppbResult[$eSpec->produk_dasar_id]["coa_code_2"];
//                        $_SESSION[$cCode]['main']['efisiensiName_' . $c2 . "_coa"] = $eSpec->produk_dasar_nama;
                            $arrCostComponentsValidate[][$eSpec->produk_dasar_id] = $eSpec->produk_dasar_nama;
                        }
                    }
                }
                else {
                    $arrComponents[$id] = array();
                }


                // matiHere(__LINE__);
                $this->load->model("Mdls/MdlProdukFase");
                $pf = New MdlProdukFase();
                $pf->addFilter("produk_id='$id'");
                $pf->addFilter("urut='$faseID'");
                $pfTmp = $pf->lookupAll()->result();
                // cekLime($this->db->last_query());
                // matiHEre(__LINE__);
                $arrProdukFase = array();
                foreach ($pfTmp as $pfSpec) {
                    $arrProdukFase[$pfSpec->urut] = (array)$pfSpec;
                    $_SESSION[$cCode]["main"]["fase_id"] = $faseID;
                    $_SESSION[$cCode]["main"]["fase_nama"] = $pfSpec->nama;
                    $_SESSION[$cCode]["main"]["gudang_source_id"] = $pfSpec->gudang_id;
                    $_SESSION[$cCode]["main"]["gudang_source_nama"] = $pfSpec->gudang_nama;
                    $_SESSION[$cCode]["main"]["gudang_target_id"] = $pfSpec->gudang2_id;
                    $_SESSION[$cCode]["main"]["gudang_target_nama"] = $pfSpec->gudang2_nama;
                    $_SESSION[$cCode]["main"]["gudang2_id"] = $pfSpec->gudang2_id;
                    $_SESSION[$cCode]["main"]["gudang2_nama"] = $pfSpec->gudang2_nama;
                }

            }


            if (sizeof($arrComponents[$id]) == 0) {
                $msg = "**belum ada data komposisi produk, harap di setUp terlebih dahulu via login holding.";
                matiHEre($msg);
                die(lgShowAlert($msg));
            }
            if (sizeof($arrCostComponentsValidate) == 0) {
                $msg = "belum ada data Standart Cost By Product, harap di setUp terlebih dahulu via login holding.";
//            cekMerah($msg);
//            die(lgShowAlert($msg));
            }

            $pakai_ini = 0;
            if ($pakai_ini == 1) {

                if (isset($_SESSION[$cCode]['pairs']['stokSupplies'])) {
//            arrPrint($_SESSION[$cCode]['pairs']['stokSupplies']);
                    //============ AUTO QTY MENGIKUTI STOK YANG TERSEDIA =============
                    $tmpEstStok = array();
                    if (isset($arrComponents[$id]['produk'])) {
                        foreach ($arrComponents[$id]['produk'] as $com) {
                            $bahanID = $com['id'];
                            if ($_SESSION[$cCode]['pairs']['stokSupplies']) {
                                $tmpEstStok[$bahanID] = isset($_SESSION[$cCode]['pairs']['stokSupplies'][$bahanID]) && $_SESSION[$cCode]['pairs']['stokSupplies'][$bahanID] * 1 > 0 ? $_SESSION[$cCode]['pairs']['stokSupplies'][$bahanID] / $com['jml'] : 0;
                            }
                        }
                    }
                    $estimasi_stok = min($tmpEstStok);
                    if (!isset($_GET['jml'])) {
                        $_GET['newQty'] = $estimasi_stok;
                        $jml = $estimasi_stok;
                        $tmpJml = $estimasi_stok;
                    }
                    //============ AUTO QTY MENGIKUTI STOK YANG TERSEDIA =============
                }

            }


            // menyimpan komposisi mentah ke session, akumulasi dalam 1 BOM
            $_SESSION[$cCode]['items_komposisi'][$id] = $arrComponentsProduk[$id];
            // menyimpan komposisi mentah ke session, 1 BOM dengan fasenya
            $_SESSION[$cCode]['items9_sum'][$id] = $arrComponentsByFase[$id];
            $_SESSION[$cCode]['items10_sum'][$id] = $arrComponentsByFaseProduk[$id];
            $_SESSION[$cCode]['items6_sum'] = $arrProdukFase;


            if (sizeof($tmpB) > 0) {
                foreach ($tmpB as $row) {
                    $rows = $row;
                    $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                    $nama = isset($row->nama) > 0 ? $row->nama : "n/a";
                    $tmpJml = 1;

                    $_SESSION[$cCode]['main']['bomProdukID'] = $id;
                    $_SESSION[$cCode]['main']['bomProdukNama'] = $nama;
                    $_SESSION[$cCode]['main']['bomProdukName'] = $nama;
                    $_SESSION[$cCode]['main']['bom_produk_id'] = $id;
                    $_SESSION[$cCode]['main']['bom_produk_nama'] = $nama;
                    $_SESSION[$cCode]['main']['bom_produk_name'] = $nama;

                    if (isset($lockerConfig['enabled']) && $lockerConfig['enabled'] == true) {
                        cekMerah("masuk locker config");

                        $mdlName = $lockerConfig['mdlName'];
                        $this->load->model("Mdls/" . $mdlName);
                        $c = new $mdlName();
                        $c->addFilter("produk_id='$id'");
                        $c->addFilter("state='active'");
                        $c->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                        $c->addFilter("gudang_id=" . $this->session->login['gudang_id']);
                        $tmpC = $c->lookupAll($id)->result();
                        cekHere($this->db->last_query());

                        if (sizeof($tmpC) > 0) {
                            arrPrint($tmpC);
                            foreach ($tmpC as $row) {
                                $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                                $nama = $row->nama;

                                $jml_now = $row->jumlah;
                                if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                                    $jml_sudah_diambil = 0;
                                    $jml_diperlukan = 1;
                                    $jml_nambah = 1;
                                }
                                else {
                                    if (isset($_GET['newQty'])) {
                                        $jml_sudah_diambil = $_SESSION[$cCode]['items'][$id]['jml'];
                                        $jml_diperlukan = $_GET['newQty'];
                                        $jml_nambah = $jml_diperlukan - $jml_sudah_diambil;
                                    }
                                    else {
                                        $jml_sudah_diambil = $_SESSION[$cCode]['items'][$id]['jml'];
                                        $jml_diperlukan = $jml_sudah_diambil + $jml;
                                        $jml_nambah = $jml;
                                    }
                                }
                                //  region validasi stok
                                if ($jml_nambah > $jml_now) {
                                    echo "<script>top.alert('stok $nama tidak cukup. (perlu $jml_diperlukan, nambah $jml_nambah stok $jml_now)')";
                                    echo "</script>";
                                    die();
                                }
                                //  endregion validasi stok

                                $this->db->trans_start();

                                //  region update locker active
                                $where = array(
                                    "id" => $row->id,
                                );
                                $data_active = array(
                                    "jumlah" => $jml_now - $jml_nambah,
                                    "state" => "active",
                                );
                                $c->updateData($where, $data_active);
                                cekHere($this->db->last_query());
                                //  endregion update locker active

                                //  region locker hold
                                $array_hold_sebelumnya = $c->cekLoker($this->session->login['cabang_id'], $id, "hold", $this->session->login['id'], "0", $this->session->login['gudang_id']);
                                if (sizeof($array_hold_sebelumnya) > 0) {
                                    $where = array(
                                        "id" => $array_hold_sebelumnya['id'],
                                    );
                                    $data_hold = array(
                                        "jumlah" => $array_hold_sebelumnya['jumlah'] + $jml_nambah,
                                    );
                                    $c->updateData($where, $data_hold);
                                    cekHere($this->db->last_query());
                                }
                                else {
                                    $data_hold = array(
                                        "jenis" => "produk",
                                        "cabang_id" => $this->session->login['cabang_id'],
                                        "produk_id" => $id,
                                        "nama" => $nama,
                                        "satuan" => $row->satuan,
                                        "state" => "hold",
                                        "jumlah" => $jml_nambah,
                                        "oleh_id" => $this->session->login['id'],
                                        "oleh_nama" => $this->session->login['nama'],
                                        "gudang_id" => $this->session->login['gudang_id'],
                                    );
                                    $c->addData($data_hold);
                                    cekHere($this->db->last_query());
                                }
                                //  endregion locker hold
                                $this->db->trans_complete() or die("Gagal bro");
                                $tmpJml = $jml_diperlukan;

                            }
                        }
                        else {
                            mati_disini("tidak ditemukan item " . $row->nama . " di locker stock.");
                        }
                    }

                    $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");

                    if (!isset($_SESSION[$cCode]['items'][$id])) {
//                    cekUngu("BELUM ADA ITEMS");
                        $tmp = array(
                            "handler" => $this->modul . "/" . $this->uri->segment(2),
                            "id" => $id,
                            "jml" => $tmpJml,
                            "harga" => 0,
                            "subtotal" => 0,
                        );
                        if (sizeof($priceConfig) > 0) {
                            $mdlName = $priceConfig['model'];
                            $this->load->model("Mdls/" . $mdlName);
                            $h = new $mdlName();
                            $h->addFilter("produk_id='$id'");
                            $h->addFilter("status='1'");
                            $h->addFilter("jenis_value in ('" . implode("','", $priceConfig['label']) . "')");
                            $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                            $tmpH = $h->lookupAll($id)->result();
                            cekMerah($this->db->last_query());
                            if (sizeof($tmpH) > 0) {
                                $rawPrices = array();
                                foreach ($tmpH as $hSpec) {
                                    foreach ($priceConfig['key_label'] as $key => $val) {
                                        if ($key == $hSpec->jenis_value) {
                                            $rawPrices[$key] = isset($hSpec->nilai) ? $hSpec->nilai : 0;
                                        }
                                    }
                                }
                                $prices = normalizePrices("produk", $rawPrices);
                                if (sizeof($prices) > 0) {
                                    foreach ($prices as $k => $v) {
                                        $tmp[$k] = $v;
                                    }
                                    $tmp['harga'] = isset($tmp[$priceConfig['mainSrc']]) ? $tmp[$priceConfig['mainSrc']] : 0;
                                }
                            }
                        }
                        foreach ($fieldSrcs as $key => $src) {
                            $tmp[$key] = makeValue($src, $tmp, $tmp, $rows->$src);
                        }

                        //region perhitungan subtotal
                        if ($subAmountConfig != null) {
                            $subtotal = makeValue($subAmountConfig, $tmp, $tmp, 0);
                        }
                        else {
                            $subtotal = 0;
                        }
                        $tmp["subtotal"] = $subtotal;
                        $_SESSION[$cCode]['items'][$id] = $tmp;
                        //endregion

                        $tmpRslt = $arrComponents[$id];
                        $_SESSION[$cCode]['items2'][$id] = $tmpRslt;
                    }
                    else {
//                    cekUngu("SUDAH ADA ITEMS, GANTI QTyy");
                        if (isset($_GET['newQty'])) {
//                        cekUngu("HAHAHA :: " . $_GET['newQty']);
                            $_SESSION[$cCode]['items'][$id]['jml'] = $_GET['newQty'];
                            $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
                        }
                        else {
                            if ($addJml == 0) {
                                $_SESSION[$cCode]['items'][$id]['jml'] = 1;
                                $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
                            }
                            else {
//                            $_SESSION[$cCode]['items'][$id]['jml'] += $jml;
                                // jml produksi per-fase dipaksa 1 unit. jadi kalau refresh tidak menambah jumlah.
                                $_SESSION[$cCode]['items'][$id]['jml'] = 1;
                                $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
                            }
                        }
                        if (sizeof($itemNumLabels) > 0) {
                            echo("iterating subNums..");
                            foreach ($itemNumLabels as $key => $label) {
                                if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                    $newValue = $_GET[$key];
                                    $tmp[$key] = $newValue;
                                    $_SESSION[$cCode]['items'][$id][$key] = $newValue;
                                    echo "replacing value for $key with " . $newValue . "<br>";
                                }
                            }

                            foreach ($itemNumLabels as $key => $label) {
                                $_SESSION[$cCode]['items'][$id]["sub_" . $key] = ($_SESSION[$cCode]['items'][$id][$key] * $_SESSION[$cCode]['items'][$id]["jml"]);
                            }

                            $_SESSION[$cCode]['items'][$id]['sub_nett'] = ($_SESSION[$cCode]['items'][$id]['nett'] * $_SESSION[$cCode]['items'][$id]['jml']);
                            $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
                        }

                        if (isset($_SESSION[$cCode]['items2'][$id]) && sizeof($_SESSION[$cCode]['items2'][$id]) > 0) {
                            foreach ($_SESSION[$cCode]['items2'][$id]['produk'] as $e => $eSpec) {
                                $_SESSION[$cCode]['items2'][$id]['produk'][$e]['jml'] = isset($arrComponents[$id]['produk'][$e]['jml']) ? ($arrComponents[$id]['produk'][$e]['jml'] * $_SESSION[$cCode]['items'][$id]['jml']) : 0;
                            }
                            foreach ($_SESSION[$cCode]['items2'][$id]['biaya'] as $e => $eSpec) {
                                $_SESSION[$cCode]['items2'][$id]['biaya'][$e]['jml'] = isset($arrComponents[$id]['biaya'][$e]['jml']) ? ($arrComponents[$id]['biaya'][$e]['jml'] * $_SESSION[$cCode]['items'][$id]['jml']) : 0;
                                $_SESSION[$cCode]['items2'][$id]['biaya'][$e]['sub_nilai'] = isset($arrComponents[$id]['biaya'][$e]['jml']) ? ($arrComponents[$id]['biaya'][$e]['nilai'] * $_SESSION[$cCode]['items'][$id]['jml']) : 0;
                            }
                        }
                    }
                }

                if (sizeof($_SESSION[$cCode]['items']) > 0) {
                    $_SESSION[$cCode]['main']['harga'] = 0;
                    foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                        $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
                    }
                }
                if (sizeof($_SESSION[$cCode]['items2']) > 0) {
//                cekBiru("bulding summary item_result...");
                    $_SESSION[$cCode]['items2_sum'] = array();// supplies-nya...
                    $_SESSION[$cCode]['items3_sum'] = array();// biaya-nya...
                    foreach ($_SESSION[$cCode]['items2'] as $pID => $pSpec) {
                        foreach ($pSpec as $jenis => $jSpec) {
                            foreach ($jSpec as $eSpec) {
                                if ($jenis == "produk") {
                                    if (!isset($_SESSION[$cCode]['items2_sum'][$eSpec['produk_dasar_id']])) {
                                        $_SESSION[$cCode]['items2_sum'][$eSpec['produk_dasar_id']] = $eSpec;
                                        $_SESSION[$cCode]['items2_sum'][$eSpec['produk_dasar_id']]['jml'] = 0;
                                        $_SESSION[$cCode]['items2_sum'][$eSpec['produk_dasar_id']]['produk_ids'] = array();
                                    }
                                    $_SESSION[$cCode]['items2_sum'][$eSpec['produk_dasar_id']]['jml'] += $eSpec['jml'];
                                    $_SESSION[$cCode]['items2_sum'][$eSpec['produk_dasar_id']]['produk_ids'][$pID] = $pID;
                                    // $_SESSION[$cCode]['items2_sum'][$eSpec['produk_dasar_id']]['nilai_bom'] = $eSpec['harga'] *$jml;
                                }
                                if ($jenis == "biaya") {
                                    if (!isset($_SESSION[$cCode]['items3_sum'][$eSpec['produk_dasar_id']])) {
                                        $_SESSION[$cCode]['items3_sum'][$eSpec['produk_dasar_id']] = $eSpec;
                                        $_SESSION[$cCode]['items3_sum'][$eSpec['produk_dasar_id']]['jml'] = 0;
                                        $_SESSION[$cCode]['items3_sum'][$eSpec['produk_dasar_id']]['sub_nilai'] = 0;
                                        $_SESSION[$cCode]['items3_sum'][$eSpec['produk_dasar_id']]['produk_ids'] = array();
                                    }
                                    $_SESSION[$cCode]['items3_sum'][$eSpec['produk_dasar_id']]['jml'] += $eSpec['jml'];
                                    $_SESSION[$cCode]['items3_sum'][$eSpec['produk_dasar_id']]['sub_nilai'] += $eSpec['sub_nilai'];
                                    $_SESSION[$cCode]['items3_sum'][$eSpec['produk_dasar_id']]['produk_ids'][$pID] = $pID;
                                }
                            }
                        }
                    }
                }
                if (sizeof($_SESSION[$cCode]['items2_sum']) > 0) {
                    foreach ($_SESSION[$cCode]['items2_sum'] as $bID => $pSpec) {
                        $_SESSION[$cCode]['items2_sum'][$bID]['produk_ids'] = blobEncode($pSpec['produk_ids']);
                    }
                }

                // kebutuhan per-fasenya
                if (sizeof($_SESSION[$cCode]['items10_sum']) > 0) {
//                cekBiru("bulding summary item_result...");
                    $_SESSION[$cCode]['items7_sum'] = array();
                    $_SESSION[$cCode]['items8_sum'] = array();
                    foreach ($_SESSION[$cCode]['items10_sum'] as $pID => $fpSpec) {
//                    arrPrintPink($fpSpec);
                        foreach ($fpSpec as $fase => $pSpec) {
                            foreach ($pSpec as $jenis => $jSpec) {
                                foreach ($jSpec as $ii => $eSpec) {
//                                arrPrintWebs($eSpec);
                                    if ($jenis == "produk") {
                                        if (!isset($_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii])) {
                                            $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii] = $eSpec;

                                        }
                                        if (!isset($_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_jml"])) {
                                            $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_jml"] = 0;
                                        }
                                        if (!isset($_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_nilai"])) {
                                            $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_nilai"] = 0;
                                        }
                                        if (!isset($_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_harga"])) {
                                            $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_harga"] = 0;
                                        }
                                        $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_nilai"] += (($eSpec["nilai"] * $eSpec["jml"]) * $_SESSION[$cCode]['items'][$id]['jml']);
                                        $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_jml"] += ($eSpec["jml"] * $_SESSION[$cCode]['items'][$id]['jml']);
                                        $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_harga"] += ($eSpec["harga"] * $_SESSION[$cCode]['items'][$id]['jml']);
                                    }
                                    if ($jenis == "biaya") {
                                        if (!isset($_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii])) {
                                            $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii] = $eSpec;

                                        }
                                        if (!isset($_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_jml"])) {
                                            $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_jml"] = 0;
                                        }
                                        if (!isset($_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_nilai"])) {
                                            $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_nilai"] = 0;
                                        }
                                        if (!isset($_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_harga"])) {
                                            $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_harga"] = 0;
                                        }
                                        $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_nilai"] += ($eSpec["nilai"] * $_SESSION[$cCode]['items'][$id]['jml']);
//                                    $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_jml"] += ($eSpec["jml"] * $_SESSION[$cCode]['items'][$id]['jml']);
                                        $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_harga"] += ($eSpec["harga"] * $_SESSION[$cCode]['items'][$id]['jml']);
                                    }
                                    if ($jenis == "target") {
//                                    if (!isset($_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii])) {
//                                        $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii] = $eSpec;
//                                    }
//                                    if (!isset($_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_jml"])) {
//                                        $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_jml"] = 0;
//                                    }
//                                    if (!isset($_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_nilai"])) {
//                                        $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_nilai"] = 0;
//                                    }
//                                    if (!isset($_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_harga"])) {
//                                        $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_harga"] = 0;
//                                    }
//                                    $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_nilai"] += ($eSpec["nilai"] * $_GET["newQty"]);
//                                    $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_jml"] += ($eSpec["jml"] * $_GET["newQty"]);
//                                    $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis][$ii]["sub_harga"] += ($eSpec["harga"] * $_GET["newQty"]);
                                        if (!isset($_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis])) {
                                            $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis] = $eSpec;
                                        }
                                        if (!isset($_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis]["sub_jml"])) {
                                            $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis]["sub_jml"] = 0;
                                        }
                                        if (!isset($_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis]["sub_nilai"])) {
                                            $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis]["sub_nilai"] = 0;
                                        }
                                        if (!isset($_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis]["sub_harga"])) {
                                            $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis]["sub_harga"] = 0;
                                        }
                                        $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis]["sub_nilai"] += (($eSpec["nilai"] * $eSpec["jml"]) * $_SESSION[$cCode]['items'][$id]['jml']);
                                        $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis]["sub_jml"] += ($eSpec["jml"] * $_SESSION[$cCode]['items'][$id]['jml']);
                                        $_SESSION[$cCode]['items8_sum'][$pID][$fase][$jenis]["sub_harga"] += ($eSpec["harga"] * $_SESSION[$cCode]['items'][$id]['jml']);
                                    }
                                }
                            }
                        }
                    }
                    foreach ($_SESSION[$cCode]['items8_sum'] as $pID => $iiSpec) {
                        $_SESSION[$cCode]['items7_sum'] = $iiSpec;
                    }
                }


                // fase lebih dari 0
                if ($faseID > 0) {
//                arrPrintKuning($arrComponentsByFaseProduk);
//                arrPrintPink($_SESSION[$cCode]['items2']);
                    if (isset($_GET['newQty'])) {
                        $newQty = $_GET['newQty'];
                    }
                    else {
                        $newQty = 1;
                    }
                    $resetSession = array(
                        "items",
                        "items2",
                        "items2_sum",
                        "items3",
                        "items3_sum",
                        "items_komposisi",
                    );
                    foreach ($resetSession as $gate) {
                        $_SESSION[$cCode][$gate] = null;
                        unset($_SESSION[$cCode][$gate]);
                    }
                    $komposisiFase = array();
                    $itemsFase = array();
                    foreach ($arrComponentsByFaseProduk[$id][$faseID] as $jenis_bb => $faseSpec) {
//                    cekMerah(":: $jenis_bb ::");
//                    arrPrintHijau($faseSpec);
                        if ($jenis_bb == "produk") {
                            $komposisiFase[$jenis_bb] = $faseSpec;
                        }
                        if ($jenis_bb == "biaya") {
                            foreach ($faseSpec as $iii => $iiiSpec) {
                                $faseSpec[$iii]["subnilai"] = $iiiSpec["nilai"] * $iiiSpec["jml"];
                                $faseSpec[$iii]["subharga"] = $iiiSpec["harga"] * $iiiSpec["jml"];
                            }
                            $komposisiFase[$jenis_bb] = $faseSpec;
//                        arrPrintPink($faseSpec);
//                        mati_disini(__LINE__);
                        }
                        if ($jenis_bb == "target") {
                            foreach ($faseSpec as $ii => $iiSpec) {
                                $komposisiFase[$jenis_bb] = $iiSpec;
                                $itemsFase[$iiSpec['produk_dasar_id']] = $iiSpec;
                                $itemsFase[$iiSpec['produk_dasar_id']]['id'] = $iiSpec['produk_dasar_id'];
                                $itemsFase[$iiSpec['produk_dasar_id']]['jml'] = $iiSpec['jml'] * $newQty;
                                $itemsFase[$iiSpec['produk_dasar_id']]['qty'] = $iiSpec['qty'] * $newQty;
                                $itemsFase[$iiSpec['produk_dasar_id']]['sub_harga'] = ($iiSpec['harga'] * $iiSpec['jml']) * $newQty;
                                $itemsFase[$iiSpec['produk_dasar_id']]['sub_nilai'] = ($iiSpec['nilai'] * $iiSpec['qty']) * $newQty;
                                $itemsFase[$iiSpec['produk_dasar_id']]['nama'] = $iiSpec['produk_dasar_nama'];
                                $itemsFase[$iiSpec['produk_dasar_id']]['name'] = $iiSpec['produk_dasar_nama'];
                                $itemsFase[$iiSpec['produk_dasar_id']]['handler'] = $this->modul . "/" . $this->uri->segment(2);
                            }
                        }
                    }
                    $newKomposisiFase[$arrProdukTargetFase[$faseID]] = $komposisiFase;
                    $newItems2_sum = array();
                    $newItems3_sum = array();
                    if (sizeof($itemsFase) > 0) {
                        $_SESSION[$cCode]['main']['harga'] = 0;
                        foreach ($itemsFase as $id => $iSpec) {
                            $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
                            $_SESSION[$cCode]['main']['nilai'] += ($iSpec['jml'] * $iSpec['nilai']);
                        }
                    }
                    if (sizeof($newKomposisiFase) > 0) {

                        $newItems2_sum = array();// supplies-nya...
                        $newItems3_sum = array();// biaya-nya...
                        foreach ($newKomposisiFase as $pID => $pSpec) {
                            foreach ($pSpec as $jenis => $jSpec) {
                                foreach ($jSpec as $eSpec) {
                                    if ($jenis == "produk") {
                                        if (!isset($newItems2_sum[$eSpec['produk_dasar_id']])) {
                                            $newItems2_sum[$eSpec['produk_dasar_id']] = $eSpec;
                                            $newItems2_sum[$eSpec['produk_dasar_id']]['jml'] = 0;
                                            $newItems2_sum[$eSpec['produk_dasar_id']]['produk_ids'] = array();
                                            if (isset($newItems2_sum[$eSpec['produk_dasar_id']]["id"])) {
                                                $newItems2_sum[$eSpec['produk_dasar_id']]["id"] = $eSpec['produk_dasar_id'];
                                            }
                                        }
                                        $newItems2_sum[$eSpec['produk_dasar_id']]['jml'] += ($eSpec['jml'] * $newQty);
                                        $newItems2_sum[$eSpec['produk_dasar_id']]['sub_harga'] = (($eSpec['harga'] * $newItems2_sum[$eSpec['produk_dasar_id']]['jml']));
                                        $newItems2_sum[$eSpec['produk_dasar_id']]['sub_nilai'] = (($eSpec['nilai'] * $newItems2_sum[$eSpec['produk_dasar_id']]['jml']));
                                        $newItems2_sum[$eSpec['produk_dasar_id']]['nama'] = $eSpec['produk_dasar_nama'];
                                        $newItems2_sum[$eSpec['produk_dasar_id']]['name'] = $eSpec['produk_dasar_nama'];
                                        $newItems2_sum[$eSpec['produk_dasar_id']]['produk_ids'][$pID] = $pID;
                                        $newItems2_sum[$eSpec['produk_dasar_id']]["nilai_bom"] = $eSpec['nilai'];
                                    }
                                    if ($jenis == "biaya") {
                                        if (!isset($newItems3_sum[$eSpec['id']])) {
                                            $newItems3_sum[$eSpec['id']] = $eSpec;
                                            $newItems3_sum[$eSpec['id']]['jml'] = 0;
                                            $newItems3_sum[$eSpec['id']]['sub_nilai'] = 0;
                                            $newItems3_sum[$eSpec['id']]['produk_ids'] = array();
                                        }
                                        $newItems3_sum[$eSpec['id']]['jml'] += ($eSpec['jml'] * $newQty);
                                        $newItems3_sum[$eSpec['id']]['nama'] = $eSpec['produk_dasar_nama'];
                                        $newItems3_sum[$eSpec['id']]['name'] = $eSpec['produk_dasar_nama'];
//                                    $newItems3_sum[$eSpec['id']]['sub_nilai'] += $eSpec['sub_nilai'];
                                        $newItems3_sum[$eSpec['id']]['sub_harga'] = (($eSpec['harga'] * $newItems3_sum[$eSpec['id']]['jml']));
                                        $newItems3_sum[$eSpec['id']]['sub_nilai'] = (($eSpec['nilai'] * $newItems3_sum[$eSpec['id']]['jml']));
                                        $newItems3_sum[$eSpec['id']]['produk_ids'][$pID] = $pID;

                                    }
                                }
                            }
                        }
                    }


                    $_SESSION[$cCode]['items'] = $itemsFase;
                    $_SESSION[$cCode]['items2'] = $newKomposisiFase;
                    $_SESSION[$cCode]['items2_sum'] = $newItems2_sum;
                    $_SESSION[$cCode]['items3'] = array();
                    $_SESSION[$cCode]['items3_sum'] = $newItems3_sum;
                    $_SESSION[$cCode]['items_komposisi'] = $newKomposisiFase;
                }
//arrPrintPink($newKomposisiFase);
//arrPrintPink($itemsFase);
//arrPrintPink($arrProdukTargetFase);

                //region update item hit
                if ($updateHit) {
                    $this->load->model("Mdls/MdlProdukHit");
                    $ph = new MdlProdukHit();
                    $ph->updateHitProduk($this->modul, $_SESSION[$cCode]['items'][$id], my_cabang_id());
                }
                //endregion
            }
            else {
                cekMerah("tidak ada itemnya!");
                die();
            }


            // gudang utama/default dari cabangID
            $_SESSION[$cCode]["main"]["gudangID_produk"] = getDefaultWarehouseID(my_cabang_id())["gudang_id"];
            $_SESSION[$cCode]["main"]["gudangName_produk"] = getDefaultWarehouseID(my_cabang_id())["gudang_nama"];


            // region setting penggunaan gudang bahan baku, 1 gudang utama atau gudang per-fase

            $gudangBahanBakuMethode = $settingGudang;
//        $gudangBahanBakuMethode = "single";// gudang utama bahan baku
//        $gudangBahanBakuMethode = "multi";// gudang per-fase bahan baku
            $_SESSION[$cCode]["main"]["gudangBahanBakuMethode"] = $gudangBahanBakuMethode;

            // endregion setting penggunaan gudang bahan baku, 1 gudang utama atau gudang per-fase


        }

        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = array(
            "olehID" => my_id(),
            "olehName" => my_name(),
            "sellerID" => my_id(),
            "sellerName" => my_name(),
            "placeID" => my_cabang_id(),
            "placeName" => my_cabang_nama(),
            "divID" => my_div_id(),
            "divName" => my_div_nama(),
            "cabangID" => my_cabang_id(),
            "cabangName" => my_cabang_nama(),
            "gudangID" => my_gudang_id(),
            "gudangName" => my_gudang_nama(),
            "jenis_usaha" => my_jenis_usaha(),
            "tokoID" => my_toko_id(),
            "tokoNama" => my_toko_nama(),
            "ppnFactor" => my_ppn_factor(),
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
            "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
            "stepNumber" => 1,
            "stepCode" => $this->configUiJenis['steps'][1]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
        );
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        // fillValues_he_value_builder($this->jenisTr, 1, 1, $this->configCoreJenis, $this->configUiJenis, $this->configValuesJenis, my_ppn_factor());


        /* --------------------------------------------------
         * ngereload shoping cart dlm modul
         * --------------------------------------------------*/
        echo "<script>";
        echo "  if(top.document.getElementById('shopping_cart')){";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "/" . $_GET['id'] . "?selID=$id&fase_id=$faseID');";
        echo "  }";
        echo "</script>";

    }

    public function multiSelect()
    {
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $items = $_GET['items'];

        $arrItems = isset($_GET['items']) ? unserialize(base64_decode($items)) : array();
        $arrTrID = isset($_GET['trs']) ? unserialize(base64_decode($_GET['trs'])) : array();


        $cCode = "_TR_" . $this->jenisTr;

        $selectorModel = $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = $this->configUi[$this->jenisTr]['selectorSrcModel'];

        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;

        if (sizeof($arrItems) > 0) {
            foreach ($arrItems as $id => $jmlParam) {

                $tmpB = $b->lookupByID($id)->result();
                cekHere($this->db->last_query());
                arrPrint($tmpB);

                $jml = $jmlParam;
                if (sizeof($tmpB) > 0) {
                    foreach ($tmpB as $row) {
                        $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                        $tmpJml = $jmlParam;
                        if (isset($lockerConfig['enabled']) && $lockerConfig['enabled'] == true) {
                            cekMerah("masuk locker config");

                            $mdlName = $lockerConfig['mdlName'];
                            $this->load->model("Mdls/" . $mdlName);
                            $c = new $mdlName();
                            $c->addFilter("produk_id='$id'");
                            $c->addFilter("state='active'");
                            $c->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                            $c->addFilter("gudang_id=" . $this->session->login['gudang_id']);
                            $tmpC = $c->lookupAll($id)->result();
                            cekHere($this->db->last_query());


                            if (sizeof($tmpC) > 0) {
                                arrPrint($tmpC);
                                foreach ($tmpC as $row) {
                                    $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                                    $nama = $row->nama;

                                    $jml_now = $row->jumlah;
                                    if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                                        $jml_sudah_diambil = 0;
                                        $jml_diperlukan = 1;
                                        $jml_nambah = 1;
                                    }
                                    else {
                                        if (isset($_GET['newQty'])) {
                                            $jml_sudah_diambil = $_SESSION[$cCode]['items'][$id]['jml'];
                                            $jml_diperlukan = $_GET['newQty'];
                                            $jml_nambah = $jml_diperlukan - $jml_sudah_diambil;
                                        }
                                        else {
                                            $jml_sudah_diambil = $_SESSION[$cCode]['items'][$id]['jml'];
                                            $jml_diperlukan = $jml_sudah_diambil + $jml;
                                            $jml_nambah = $jml;
                                        }
                                    }
                                    //  region validasi stok
                                    if ($jml_nambah > $jml_now) {
                                        echo "<script>top.alert('stok $nama tidak cukup. (perlu $jml_diperlukan, nambah $jml_nambah stok $jml_now)')";
                                        echo "</script>";
                                        die();
                                    }
                                    //  endregion validasi stok


                                    $this->db->trans_start();

                                    //  region update locker active
                                    $where = array(
                                        "id" => $row->id,
                                    );
                                    $data_active = array(
                                        "jumlah" => $jml_now - $jml_nambah,
                                        "state" => "active",
                                    );
                                    $c->updateData($where, $data_active);
                                    cekHere($this->db->last_query());
                                    //  endregion update locker active


                                    //  region locker hold
                                    $array_hold_sebelumnya = $c->cekLoker($this->session->login['cabang_id'], $id, "hold", $this->session->login['id'], "0", $this->session->login['gudang_id']);
                                    if (sizeof($array_hold_sebelumnya) > 0) {
                                        $where = array(
                                            "id" => $array_hold_sebelumnya['id'],
                                        );
                                        $data_hold = array(
                                            "jumlah" => $array_hold_sebelumnya['jumlah'] + $jml_nambah,
                                        );
                                        $c->updateData($where, $data_hold);
                                        cekHere($this->db->last_query());
                                    }
                                    else {
                                        $data_hold = array(
                                            "jenis" => "produk",
                                            "cabang_id" => $this->session->login['cabang_id'],
                                            "produk_id" => $id,
                                            "nama" => $nama,
                                            "satuan" => $row->satuan,
                                            "state" => "hold",
                                            "jumlah" => $jml_nambah,
                                            "oleh_id" => $this->session->login['id'],
                                            "oleh_nama" => $this->session->login['nama'],
                                            "gudang_id" => $this->session->login['gudang_id'],
                                        );
                                        $c->addData($data_hold);
                                        cekHere($this->db->last_query());
                                    }
                                    //  endregion locker hold


                                    $this->db->trans_complete() or die("Gagal bro");

                                    $tmpJml = $jml_diperlukan;

                                }
                            }
                            else {
                                mati_disini("tidak ditemukan item " . $row->nama . " di locker stock.");
                            }

                        }

                        $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
                        if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                            $tmp = array(
                                "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
                                "id" => $id,
                                "jml" => $tmpJml,
                                "harga" => 0,
                                "subtotal" => 0,
                            );

                            if (sizeof($priceConfig) > 0) {
                                $mdlName = $priceConfig['model'];
                                $this->load->model("Mdls/" . $mdlName);
                                $h = new $mdlName();
                                $h->addFilter("produk_id='$id'");
                                $h->addFilter("status='1'");
//                                $h->addFilter("jenis_value='" . $priceConfig['label'] . "'");
                                $h->addFilter("jenis_value in ('" . implode("','", $priceConfig['label']) . "')");
                                $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                                $tmpH = $h->lookupAll($id)->result();
                                cekMerah($this->db->last_query());
                                if (sizeof($tmpH) > 0) {
                                    $rawPrices = array();
                                    foreach ($tmpH as $hSpec) {
                                        foreach ($priceConfig['key_label'] as $key => $val) {
                                            if ($key == $hSpec->jenis_value) {
                                                $rawPrices[$key] = isset($hSpec->nilai) ? $hSpec->nilai : 0;
                                            }
                                        }
                                    }
                                    $prices = normalizePrices("produk", $rawPrices);
                                    if (sizeof($prices) > 0) {
                                        foreach ($prices as $k => $v) {
                                            $tmp[$k] = $v;
                                        }
                                        $tmp['harga'] = isset($tmp[$priceConfig['mainSrc']]) ? $tmp[$priceConfig['mainSrc']] : 0;
                                    }
                                }
                            }

                            foreach ($fieldSrcs as $key => $src) {
                                $tmpEx = $cal->multiExplode($src);
                                arrPrint($tmpEx);
                                if (sizeof($tmpEx) > 1) {//===berarti mengandung karakter simbol perhitungan
                                    cekBiru("$key perhitungan");
                                    $newSrc = $src;
                                    foreach ($tmpEx as $key2 => $val2) {
                                        echo "$key2 - $val2 <br>";
                                        if (!is_numeric($val2)) {
                                            if (isset($tmp[$val2]) && $tmp[$val2] > 0) {
                                                $newSrc = str_replace($val2, $tmp[$val2], $newSrc);
                                            }
                                            else {
                                                $newSrc = str_replace($val2, 0, $newSrc);
                                            }
                                        }
//                                else {
//                                    if (isset($_SESSION[$cCode]['out_master'][$val2]) && $_SESSION[$cCode]['out_master'][$val2] > 0) {
//                                        $newSrc = str_replace($val2, $_SESSION[$cCode]['out_master'][$val2], $newSrc);
//                                    } else {
//                                        if (isset($_SESSION[$cCode]['main'][$val2]) && $_SESSION[$cCode]['main'][$val2] > 0) {
//                                            $newSrc = str_replace($val2, $_SESSION[$cCode]['main'][$val2], $newSrc);
//                                        } else {
//                                            $newSrc = str_replace($val2, 0, $newSrc);
//                                        }
//                                    }
//                                }
                                    }
                                    cekBiru("$$src -> $newSrc -> " . $cal->calculate($newSrc));
                                    $tmp[$key] = $cal->calculate($newSrc);
                                }
                                else {
                                    cekBiru("$key BUKAN perhitungan");
                                    $tmp[$key] = $row->$src;
                                }


                            }

                            //===perhitungan subtotal
                            $cal = new FieldCalculator();


                            if ($subAmountConfig != null) {
                                $tmpEx = $cal->multiExplode($subAmountConfig);
                                if (sizeof($tmpEx) > 1) {
                                    $newSrc = $subAmountConfig;
                                    foreach ($tmpEx as $key2 => $val2) {
                                        if (isset($tmp[$val2])) {
                                            $newSrc = str_replace($val2, $tmp[$val2], $newSrc);
                                            cekKuning("$val2 direplace dengan " . $tmp[$val2]);
                                        }
                                        else {
                                            $newSrc = str_replace($val2, "0", $newSrc);
                                            cekKuning("$val2 direplace dengan NOL");
                                        }

                                    }
                                    $subtotal = $cal->calculate($newSrc);
                                    cekHijau("subtotal dari perhitungan $subAmountConfig $newSrc");

                                }
                                else {
                                    $subtotal = 0;
                                    cekHijau("subtotal dari perhitungan yang gak ada");
                                }
                            }
                            else {
                                $subtotal = 0;
                                cekHijau("subtotal NOL");
                            }
                            $tmp["subtotal"] = $subtotal;
                            $_SESSION[$cCode]['items'][$id] = $tmp;
//                    die();
                        }
                        else {
                            if (isset($_GET['newQty'])) {
                                $_SESSION[$cCode]['items'][$id]['jml'] = $_GET['newQty'];
                                $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
                            }
                            else {
                                $_SESSION[$cCode]['items'][$id]['jml'] += $jml;
                                $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
                            }

                            if (sizeof($itemNumLabels) > 0) {
                                echo("iterating subNums..");
                                foreach ($itemNumLabels as $key => $label) {
                                    if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                        $newValue = $_GET[$key];
                                        $tmp[$key] = $newValue;
                                        $_SESSION[$cCode]['items'][$id][$key] = $newValue;
                                        echo "replacing value for $key with " . $newValue . "<br>";
                                    }

                                }

                                foreach ($itemNumLabels as $key => $label) {
                                    $_SESSION[$cCode]['items'][$id]["sub_" . $key] = ($_SESSION[$cCode]['items'][$id][$key] * $_SESSION[$cCode]['items'][$id]["jml"]);
                                }
                                $_SESSION[$cCode]['items'][$id]['sub_nett'] = ($_SESSION[$cCode]['items'][$id]['nett'] * $_SESSION[$cCode]['items'][$id]['jml']);

                                $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
                            }


                        }
                    }

                    if (sizeof($_SESSION[$cCode]['items']) > 0) {
                        $_SESSION[$cCode]['main']['harga'] = 0;
//                        $_SESSION[$cCode]['out_master']['harga'] = 0;
                        foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                            $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
//                            $_SESSION[$cCode]['out_master']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
                        }
                    }

                }
                else {
                    cekMerah("tidak ada itemnya!");
                    die();
                }

            }
        }

        if (sizeof($arrTrID) > 0) {
            $_SESSION[$cCode]['main']['references'] = $arrTrID;
//            $_SESSION[$cCode]['out_master']['references'] = $arrTrID;
        }


        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
//        $initMasterValues = array(
//            "olehID" => my_id(),
//            "olehName" => my_name(),
//            "sellerID" => my_id(),
//            "sellerName" => my_name(),
//            "placeID" => my_cabang_id(),
//            "placeName" => my_cabang_nama(),
//            "divID" => my_div_id(),
//            "divName" => my_div_nama(),
//            "cabangID" => my_cabang_id(),
//            "cabangName" => my_cabang_nama(),
//            "gudangID" => my_gudang_id(),
//            "gudangName" => my_gudang_nama(),
//            "jenis_usaha" => my_jenis_usaha(),
//            "tokoID" => my_toko_id(),
//            "tokoNama" => my_toko_nama(),
//            "jenisTr" => $this->jenisTr,
//            "jenisTrMaster" => $this->jenisTr,
//            "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
//            "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
//            "stepNumber" => $stepNum,
//            "stepCode" => $this->configUiJenis['steps'][$stepNum]['target'],
//            "dtime" => dtimeNow(),
//            "fulldate" => dtimeNow("Y-m-d"),
//            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
//        );
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

    public function remove()
    {
        $id = $_GET['id'];
        $cCode = "_TR_" . $this->jenisTr;
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();


        if (isset($lockerConfig['enabled']) && $lockerConfig['enabled'] == true) {

            if (isset($_SESSION[$cCode]['items'][$id])) {
                cekBiru("ada barang, cek lokernya");
                $this->db->trans_start();

                $mdlName = $lockerConfig['mdlName'];
                $this->load->model("Mdls/" . $mdlName);

                $c = new $mdlName();
                $array_hold_sebelumnya = $c->cekLoker($this->session->login['cabang_id'], $id, "hold", $this->session->login['id'], "0", $this->session->login['gudang_id']);
                $where = array(
                    "id" => $array_hold_sebelumnya['id'],
                );
                $data_hold = array(
                    "jumlah" => 0,
                );
                $c->updateData($where, $data_hold);


                $c = new $mdlName();
                $array_active_sebelumnya = $c->cekLoker($this->session->login['cabang_id'], $id, "active", "0", "0", $this->session->login['gudang_id']);
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

            $_SESSION[$cCode]['items6_sum'][$id] = null;
            unset($_SESSION[$cCode]['items6_sum'][$id]);

            $_SESSION[$cCode]['items7_sum'][$id] = null;
            unset($_SESSION[$cCode]['items7_sum'][$id]);

            $_SESSION[$cCode]['items8_sum'][$id] = null;
            unset($_SESSION[$cCode]['items8_sum'][$id]);

            $_SESSION[$cCode]['items9_sum'][$id] = null;
            unset($_SESSION[$cCode]['items9_sum'][$id]);

            $_SESSION[$cCode]['items10_sum'][$id] = null;
            unset($_SESSION[$cCode]['items10_sum'][$id]);
        }

        if (isset($_SESSION[$cCode]['tableIn_detail_values'][$id])) {
            $_SESSION[$cCode]['tableIn_detail_values'][$id] = null;
            unset($_SESSION[$cCode]['tableIn_detail_values'][$id]);
        }


        if (isset($_SESSION[$cCode]['items2'][$id])) {
            $_SESSION[$cCode]['items2'][$id] = null;
            unset($_SESSION[$cCode]['items2'][$id]);
        }

        $_SESSION[$cCode]['items2_sum'] = array();
        $_SESSION[$cCode]['items3_sum'] = array();
        $_SESSION[$cCode]['tableIn_detail2_sum'] = array();
        $_SESSION[$cCode]['tableIn_detail_values2_sum'] = array();
        if (sizeof($_SESSION[$cCode]['items2']) > 0) {
            foreach ($_SESSION[$cCode]['items2'] as $pID => $pSpec) {
                foreach ($pSpec as $jenis => $jSpec) {
                    foreach ($jSpec as $eSpec) {
                        if ($jenis == "produk") {
                            if (!isset($_SESSION[$cCode]['items2_sum'][$eSpec['id']])) {
                                $_SESSION[$cCode]['items2_sum'][$eSpec['id']] = $eSpec;
                                $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['jml'] = 0;
                            }
                            $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['jml'] += $eSpec['jml'];
                        }
                        if ($jenis == "biaya") {
                            if (!isset($_SESSION[$cCode]['items3_sum'][$eSpec['id']])) {
                                $_SESSION[$cCode]['items3_sum'][$eSpec['id']] = $eSpec;
                                $_SESSION[$cCode]['items3_sum'][$eSpec['id']]['jml'] = 0;
                                $_SESSION[$cCode]['items3_sum'][$eSpec['id']]['sub_nilai'] = 0;
                            }
                            $_SESSION[$cCode]['items3_sum'][$eSpec['id']]['jml'] += $eSpec['jml'];
                            $_SESSION[$cCode]['items3_sum'][$eSpec['id']]['sub_nilai'] += $eSpec['sub_nilai'];
                        }
                    }
                }
            }
        }
        else {
            $detailResetList = array(
                "items",
                "items2",
                "items2_sum",
                "tableIn_detail",
                "tableIn_detail2",
                "tableIn_detail2_sum",
                "tableIn_detail_values",
                "tableIn_detail_values2",
                "tableIn_detail_values2_sum",
            );
            foreach ($detailResetList as $sSName) {
                $_SESSION[$cCode][$sSName] = null;
                unset($_SESSION[$cCode][$sSName]);
            }
        }

        if (isset($_SESSION[$cCode]['items_komposisi'][$id])) {
            $_SESSION[$cCode]['items_komposisi'][$id] = NULL;
            unset($_SESSION[$cCode]['items_komposisi'][$id]);
        }


        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
//        $initMasterValues = array(
//            "olehID" => my_id(),
//            "olehName" => my_name(),
//            "sellerID" => my_id(),
//            "sellerName" => my_name(),
//            "placeID" => my_cabang_id(),
//            "placeName" => my_cabang_nama(),
//            "divID" => my_div_id(),
//            "divName" => my_div_nama(),
//            "cabangID" => my_cabang_id(),
//            "cabangName" => my_cabang_nama(),
//            "gudangID" => my_gudang_id(),
//            "gudangName" => my_gudang_nama(),
//            "jenis_usaha" => my_jenis_usaha(),
//            "tokoID" => my_toko_id(),
//            "tokoNama" => my_toko_nama(),
//            "jenisTr" => $this->jenisTr,
//            "jenisTrMaster" => $this->jenisTr,
//            "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
//            "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
//            "stepNumber" => $stepNum,
//            "stepCode" => $this->configUiJenis['steps'][$stepNum]['target'],
//            "dtime" => dtimeNow(),
//            "fulldate" => dtimeNow("Y-m-d"),
//            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
//        );
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
        echo "---------------------------your input params needed------------------------------";
        arrprint($_POST);
        $cCode = "_TR_" . $this->jenisTr;
        $rawParam = $_POST['param'];
        arrPrint($rawParam);
        die("updating.............................. (will be available sooner or later)");
        $rawParam = $_GET['param'];
        $param = unserialize(base64_decode($rawParam));
        if (is_array($param) && sizeof($param) > 0) {

        }
    }
}