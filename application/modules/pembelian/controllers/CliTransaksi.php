<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//require_once "Modul_Controller.php";

//class CliTransaksi extends Modul_Controller
class CliTransaksi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper("he_stepping");
        $this->load->helper("he_access_right");
        $this->load->library("MobileDetect");
        $this->load->helper("he_session_replacer");
        $this->load->model("Mdls/MdlCurrency");
        $this->load->model("Mdls/MdlSupplier");
        $this->load->model("MdlPembelianTransaksi");
        $this->load->helper('he_angka');
//        $tmpJenis = $this->jenisTr;
//        $this->allSteps = isset($this->configUi[$tmpJenis]['steps']) ? $this->configUi[$tmpJenis]['steps'] : array();
        /* ----------------------------------------------------------------------------------
          * loader cunstruk yg wajib ada
          * variabel-variabel bisa langsung dipangil, apa saja yang ada bisa dilihat didalamnya
          * ----------------------------------------------------------------------------------*/

        $this->load->library("TransaksionalPembelian");
        $this->load->library("Transaksional");
        $this->load->helper("he_value_builder");

        $this->companyProfile = array();
        $this->load->model("Mdls/MdlCompany");
        $cp = New MdlCompany();
        $cpTmp = $cp->lookupAll()->result();
        if (sizeof($cpTmp) == 1) {
            $this->companyProfile = (array)$cpTmp[0];
        }
        else {
            $msg = "Data Profil Perusahaan salah. code: " . __LINE__;
            mati_disini($msg);
        }

//        $this->load->library("Rabbitmq_lib");

    }

    // 1. sinkron auto po dari so di ADI
    public function autoPurchaseOrder()
    {

        // region membaca tabel bridge
        $p = new Transaksional();
        $tempDataBridge = $temp = $p->callSalesAutoPo();
        // endregion membaca tabel bridge
//arrPrint($tempDataBridge);
//matiHere();
        if (sizeof($temp) > 0) {

            //panggil nilai yagn diseting holding, jika beluma ada matikan dan kirim info
            //region cekdiskon margin dari holding
//

            $insert = call_curl(ADM_DOMAIN . "eusvc/SubsidiaryDiskon/lookUpDiskon?src=" . blobEncode(ADM_LOCAL_DOMAIN));
            //perubahan dari UI saat otorisasi
            $pakai_margin = 1;
            if($pakai_margin){
                cekHitam("%%%");
                $active_diskon = $temp[0]->margin_subsidiary > 0 ? $temp[0]->margin_subsidiary:$insert["nilai"];
            }
            else{
                cekBiru("^^^^");
            $active_diskon = 0;
            if ($insert["status"] == 200) {
                $active_diskon = $insert["nilai"];
            }
            else {
                matiHEre("belum diseting diskon di holding company untuk " . ADM_LOCAL_DOMAIN);
            }
            }



            //endregion

//            matiHere($active_diskon);

            $supplier_id_holding = $supplier_id = $temp[0]->supplier_id;// milik ADI adalah member_id, jadi kalau butuh SUPPLIER_ID maka cek ulang dengan filter member_id ini.
            $supplier_nama_holding = $supplier_nama = $temp[0]->supplier_nama;
            $cabang_id = $temp[0]->cabang_id;
            $cabang_nama = $temp[0]->cabang_nama;
            $gudang_id = $temp[0]->gudang_id;
            $gudang_nama = $temp[0]->gudang_nama;
            $reference_order_id = $temp[0]->referensi_id;
            $sales_order_id = $temp[0]->transaksi_id;
            $sales_order_nama = $temp[0]->transaksi_no;
            $seller_id = $temp[0]->seller_id;
            $seller_nama = $temp[0]->seller_nama;
            $seller_dc_id = $temp[0]->seller_dc_id;
            $seller_dc_nama = $temp[0]->seller_dc_nama;

            $companyProfileID = $this->companyProfile["id"];
            $companyProfileNama = $this->companyProfile["nama"];

            $div_id = 18;
            $div_nama = "";
            $connectToStep = $jenisTr_master = "466";

            // region config pembelian
            $modul = getFolderModul($connectToStep);
            $pathModul = "../../$modul/models";
            $modelModul = "MdlPembelianTransaksi";
            $configUiMasterModulJenis = loadConfigModulJenis_he_misc($connectToStep, "coTransaksiUi");
            $configCoreMasterModulJenis = loadConfigModulJenis_he_misc($connectToStep, "coTransaksiCore");
            $configLayoutMasterModulJenis = loadConfigModulJenis_he_misc($connectToStep, "coTransaksiLayout");
            $configValuesMasterModulJenis = loadConfigModulJenis_he_misc($connectToStep, "coTransaksiValues");
            $configUi = loadConfigUiByModul_he_misc()[$modul];
            $configLayout = loadConfigLayoutByModul_he_misc()[$modul];
            $configCore = loadConfigCoreByModul_he_misc()[$modul];
            $configValues = loadConfigValuesByModul_he_misc()[$modul];

            $cCode = "_TR_" . $connectToStep;
            $step_number = 1;
            $next_step_number = $step_number + 1;
            $selectorSrcModel = $configUiMasterModulJenis['selectorSrcModel'];
            $fieldSrcs = isset($configUiMasterModulJenis['shoppingCartFieldSrc']) ? $configUiMasterModulJenis['shoppingCartFieldSrc'] : array();
            $subAmountConfig = isset($configUiMasterModulJenis['shoppingCartAmountValue'][1]) ? $configUiMasterModulJenis['shoppingCartAmountValue'][1] : null;
            // endregion config pembelian

            // region initial awal
            $sessionData[$cCode]['main'] = array(
                "olehID" => $companyProfileID,
                "olehName" => $companyProfileNama,
                "sellerID" => $companyProfileID,
                "sellerName" => $companyProfileNama,
                "placeID" => CB_ID_PUSAT,
                "placeName" => CB_NAME_PUSAT,
                "divID" => $div_id,
                "divName" => $div_nama,
                "cabangID" => CB_ID_PUSAT,
                "cabangName" => CB_NAME_PUSAT,
                "gudangID" => getDefaultWarehouseID(CB_ID_PUSAT)["gudang_id"],
                "gudangName" => getDefaultWarehouseID(CB_ID_PUSAT)["gudang_nama"],
                "jenis_usaha" => my_jenis_usaha(),
                "tokoID" => 0,
                "tokoNama" => "",
                "jenisTr" => $connectToStep,
                "jenisTrMaster" => $connectToStep,
                "jenisTrTop" => $configUiMasterModulJenis['steps'][$step_number]['target'],
                "jenisTrName" => $configUiMasterModulJenis['steps'][$step_number]['label'],
                "stepNumber" => $step_number,
                "stepCode" => isset($configUiMasterModulJenis['steps'][$step_number]['target']) ? $configUiMasterModulJenis['steps'][$step_number]['target'] : 0,
                "dtime" => dtimeNow(),
                "fulldate" => dtimeNow("Y-m-d"),
                "ppnFactor" => 12,
                "ppnConstanta" => "0.9166666667",
                "ppnConstantaStr" => "11/12",
                "ppnPersenCheck" => 1,
                "ppnTransaksi" => 1,
            );
            // endregion initial awal

            // region supplier_id
            if ($supplier_id > 0) {
                //ini dikirim oleh holding company
                $sp = New MdlSupplier();
                $sp->addFilter("member_id='$supplier_id'");
                $spTmp = $sp->lookupAll()->result();
                if (sizeof($spTmp) > 0) {
                    $supplier_id = $spTmp[0]->id;// milik ADI adalah member_id, jadi kalau butuh SUPPLIER_ID maka cek ulang dengan filter member_id ini.
                    $supplier_nama = $spTmp[0]->nama;
                }
                else {
                    $msg = "Auto PO gagal karena SUPPLIER_ID tidak dikenal. Silahkan cek data supplier. code: " . __LINE__;
                    mati_disini($msg);
                }
                // endregion supplier_id

//            matiHere(__LINE__ . " [$supplier_id] [$supplier_nama]");

                // region select supplier
                $sessionData[$cCode]['main']['pihakID'] = $supplier_id;
                $sessionData[$cCode]['main']['pihakName'] = $supplier_nama;
                $sessionData[$cCode]['main']['pihakName2'] = $supplier_nama;
                // endregion select supplier

                $sessionData[$cCode]['main']['referenceID_so'] = $sales_order_id;
                $sessionData[$cCode]['main']['referenceNomer_so'] = $sales_order_nama;

                // region select produk
                $this->load->model("Mdls/" . $selectorSrcModel);
                foreach ($temp as $tempSpec) {
                    $produk_id = $tempSpec->produk_id;
                    $produk_nama = $tempSpec->produk_nama;
                    $produk_jml = $tempSpec->produk_ord_jml;
                    $sales_order_harga = $tempSpec->produk_ord_hrg;
                    $harga_list = $tempSpec->harga_list;


                    $b = new $selectorSrcModel();
                    $tmpB = $b->lookupByID($produk_id)->result();
//                showLast_query("biru");
                    if (sizeof($tmpB) > 0) {
                        foreach ($tmpB as $rows) {
                            $harga_final = $tempSpec->diskon_persen == 100 ? 1 : $sales_order_harga * (100 - $active_diskon) / 100;//harga dari so
                            $tmp = array(
                                "handler" => "",
                                "id" => $produk_id,
                                "nama" => $produk_nama,
                                "jml" => $produk_jml,
                                "qty" => $produk_jml,
                                "harga" => $harga_final,
                                "harga_order" => $tempSpec->diskon_persen == 100 ? 1 : $sales_order_harga,
                                "harga_list" => $tempSpec->harga_list,
                                "diskon_persen_jual" => $tempSpec->diskon_persen,
                                "diskon_nilai_jual" => $tempSpec->diskon_nilai,
                                "subtotal" => 0,
                                "satuan" => strlen($rows->satuan) > 0 ? $rows->satuan : "n/a",
                                "discPersen" => $tempSpec->diskon_persen == 100 ? (($harga_list - 1) / $harga_list) * 100 : $active_diskon,//margin dari holding company
                                "disc" => $tempSpec->diskon_persen == 100 ? $harga_list - 1 : $sales_order_harga * ($active_diskon / 100),//margin dari holding company
                                "discount_qty" => 0,
                            );
                            foreach ($fieldSrcs as $key => $src) {
                                if (is_array($src) && sizeof($src) > 0) {
                                    foreach ($src as $srcSpec) {
                                        if (isset($tmp[$srcSpec]) || isset($rows->$srcSpec)) {
                                            $tmp[$key] = makeValue($srcSpec, $tmp, $tmp, isset($rows->$srcSpec) ? $rows->$srcSpec : "-");
                                        }
                                    }
                                }
                                else {
                                    $tmp[$key] = makeValue($src, $tmp, $tmp, isset($rows->$src) ? $rows->$src : 0);
                                }
                            }
                            if ($subAmountConfig != null) {
                                $tmp['subtotal'] = makeValue($subAmountConfig, $tmp, $tmp, 0);
                            }
                            else {
                                $tmp['subtotal'] = 0;
                            }
                            $sessionData[$cCode]['items'][$produk_id] = $tmp;
                        }
                    }

                }
                // endregion select produk
//                arrPrintWebs($sessionData[$cCode]['items']);
//            matiHere();
                // region element

                $elementConfigs = isset($configUiMasterModulJenis['receiptElements']) ? $configUiMasterModulJenis['receiptElements'] : array();
                if (sizeof($elementConfigs) > 0) {
                    foreach ($elementConfigs as $eName => $eSpec) {
                        switch ($eSpec['elementType']) {
                            case "dataModel":
                                $amdlName = $eSpec['mdlName'];
                                $this->load->model("Mdls/" . $amdlName);
                                $labelSrc = $eSpec['labelSrc'];
                                $keySrc = $eSpec['key'];
                                $oo = new $amdlName();
                                $aFilter = isset($eSpec['mdlFilter']) ? $eSpec['mdlFilter'] : array();
                                if (sizeof($aFilter) > 0) {
                                    $oo = makeFilter($aFilter, $sessionData[$cCode]['main'], $oo);
                                }
                                $tmpo = $oo->lookupAll()->result();
                                showLast_query("hitam");
                                if (sizeof($tmpo) == 1) {
                                    cekHere("[$eName] hanya 1");
                                    $usedKey = $eSpec['key'];
                                    $defValueSrc = $tmpo[0]->$usedKey;
                                    $sessionData[$cCode] = heFetchElement_modul_ns($connectToStep, $eName, $eSpec['mdlName'], $defValueSrc, $configUiMasterModulJenis, $sessionData[$cCode]);
                                }
                                else {
                                    cekHere("[$eName] lebih dari 1");
                                    if (isset($eSpec['defaultValue'])) {
                                        $defValueSrc = $eSpec['defaultValue'];
                                        switch ($eSpec['elementType']) {
                                            case "dataModel":
                                                $sessionData[$cCode] = heFetchElement_modul_ns($connectToStep, $eName, $eSpec['mdlName'], $defValueSrc, $configUiMasterModulJenis, $sessionData[$cCode]);
                                                break;
                                            case "dataField":
                                                $sessionData[$cCode] = heRecordElement_modul_ns($connectToStep, $eName, $defValueSrc, $configUiMasterModulJenis, $sessionData[$cCode]);
                                                break;
                                        }
                                    }
                                }
                                break;
                            case "dataField":
                                cekHitam("BUKAN data MODEL");
                                $defValueSrc = $eSpec['defaultValue'];
                                $sessionData[$cCode] = heRecordElement_modul_ns($connectToStep, $eName, $defValueSrc, $configUiMasterModulJenis, $sessionData[$cCode]);

                                break;
                        }
                    }
                }

                // endregion element
                $sessionData[$cCode] = fillValues_he_value_builder_ns($jenisTr_master, 1, 1, $configCoreMasterModulJenis, $configUiMasterModulJenis, $configValuesMasterModulJenis, $sessionData[$cCode]["main"]["ppnFactor"], NULL, $sessionData[$cCode]);
//                arrPrint($sessionData[$cCode]);
//                matiHere(__LINE__);

                $this->db->trans_start();

                cekMerah("START MENJALANKAN AUTO CREATE TRANSAKSI");

                // region create po
                $lt = New TransaksionalPembelian();
                $lt->setCCode($cCode);
                $lt->setModul($modul);
                $lt->setJenisTr($connectToStep);
                $lt->setCCodeData($sessionData);
                $lt->setStepNum($step_number);
                $lt->setStepNumCurrent($step_number);
                $lt->setTransaksiNumber(0);
                $lt->setConfigUiModul($configUi);
                $lt->setConfigLayoutModul($configLayout);
                $lt->setConfigCoreModul($configCore);
                $lt->setConfigValuesModul($configValues);
                $lt->setConfigCoreMaster($this->config->item('heTransaksi_core'));
                $lt->setModelModules($modelModul);
                $lt->setPathModules($pathModul);
                // save karena step 1
                $libResult = $lt->autoCreate();
                $transaksi_id_request = $libResult["transaksi_id"];
                $transaksi_nomer_request = $libResult["transaksi_nomer"];
                // endregion create po

//            cekMerah("SELESAI MENJALANKAN AUTO CREATE TRANSAKSI");
//            arrPrint($libResult);
//            mati_disini(__LINE__);


                cekMerah("START MENJALANKAN AUTO OTORISASI TRANSAKSI");

                // region auto otorisasi po
                $lt = New TransaksionalPembelian();
                $lt->setModelModules($modelModul);
                $lt->setPathModules($pathModul);
                $nextStepNumTarget = $step_number + 1;
                cekMerah("[$nextStepNumTarget = $step_number + 1]");

                $returnTransaksi = $lt->autoOtorisasi($connectToStep, $transaksi_id_request, $nextStepNumTarget, $step_number, $itemsReplacer, $extractedItems_last);
                // endregion auto otorisasi po

//            cekMerah("SELESAI MENJALANKAN AUTO OTORISASI TRANSAKSI");
//            mati_disini(__LINE__);


                // region mengupdate tabel bridge
                $this->load->model("../../penjualan/models/Coms/ComTransaksiDataPenjualanBridging");
                $cpb = New ComTransaksiDataPenjualanBridging();
                $tbl_master_bridge = $cpb->getTableNameMaster()["master_bridge"];
                $tbl_data_bridge = $cpb->getTableName();
                foreach ($tempDataBridge as $dSpec) {
                    $data_update = array(
                        "auto_po_id" => $returnTransaksi["transaksi_id"],
                        "auto_po_num" => $returnTransaksi["transaksi_nomer"],
                        "auto_po_dtime" => date("Y-m-d H:i:s"),
                        "cli" => 1,
                    );
                    $where_data_update = array(
                        "id" => $dSpec->id,
                    );
                    $cpb->setTableName($tbl_data_bridge);
                    $cpb->updateData($where_data_update, $data_update);
                    showLast_query("orange");
                }
                $master_update = array(
                    "po_id" => $returnTransaksi["transaksi_id"],
                    "po_nomer" => $returnTransaksi["transaksi_nomer"],
                    "cli" => 1,
                    "cli_id" => $transaksi_id_request,
                    "cli_nomer" => $transaksi_nomer_request,
                    "cli_dtime" => date("Y-m-d H:i:s"),
                );
                $where_master_update = array(
                    "so_id" => $sales_order_id,
                    "po_id" => 0,
                );
                $cpb->setTableName($tbl_master_bridge);
                $cpb->updateData($where_master_update, $master_update);
                showLast_query("orange");

                // endregion mengupdate tabel bridge


                // region mengisi tabel bridge pembelian
//                arrPrintWebs($returnTransaksi["sessionData"]["items"]);
//                matiHere();
                $this->load->model("../../pembelian/models/Coms/ComTransaksiDataPembelianBridging");
                $no = 0;
                foreach ($returnTransaksi["sessionData"]["items"] as $pid => $pidSpec) {
//                    arrPrint($pidSpec);
                    // cek harga jual reseller
                    if ($pidSpec["harga"] == 0) {
                        $msg = $pid ."Sinkronisasi Auto PO gagal. Harga Jual Reseller 0. Line: " . __LINE__;
                        mati_disini($msg);
                    }
                    if ($pidSpec["harga_order"] == 0) {
                        $msg = "Sinkronisasi Auto PO gagal. Harga Jual Reseller 0. Line: " . __LINE__;
                        mati_disini($msg);
                    }


                    $no++;
                    $dataPembelianBridging[$no] = array(
                        "static" => array(
                            "cabang_id" => $cabang_id,// cabang yang melakukan so
                            "referensi_id" => $reference_order_id,// pre so
                            "produk_id" => $pidSpec["id"],// produk id
                            "produk_nama" => $pidSpec["nama"],// produk nama
                            "produk_ord_hrg" => $pidSpec["harga"],// harga jual reseller
                            "produk_ord_jml" => $pidSpec["jml"],// jumlah po
                            "qty_saldo" => $pidSpec["jml"],// jumlah po
                            "transaksi_id" => $sales_order_id,
                            "transaksi_no" => $sales_order_nama,
                            "auto_po_id" => $returnTransaksi["transaksi_id"],
                            "auto_po_nomer" => $returnTransaksi["transaksi_nomer"],
                            "auto_po_dtime" => date("Y-m-d H:i:s"),
                            "oleh_id" => $companyProfileID,
                            "oleh_nama" => $companyProfileNama,
                            "domain" => ADM_LOCAL_DOMAIN,
                            "supplier_id" => $supplier_id_holding,
                            "supplier_nama" => $supplier_nama_holding,
                            "member_id" => $supplier_id,
                            "member_nama" => $supplier_nama,
                            "harga_order" => $pidSpec["harga_order"],
                            "harga_list" => $pidSpec["harga_list"],
                            "diskon_persen_order" => $pidSpec["discPersen"],
                            "diskon_nilai_order" => $pidSpec["disc"],
                            "diskon_persen_jual" => $pidSpec["diskon_persen_jual"],
                            "diskon_nilai_jual" => $pidSpec["diskon_nilai_jual"],
                            "seller_id" => $seller_id,
                            "seller_nama" => $seller_nama,
                            "seller_dc_id" => $seller_dc_id,
                            "seller_dc_nama" => $seller_dc_nama,
                            "description" => $tempDataBridge[0]->description,
                        ),
                    );
                }
//                arrPrintWebs($dataPembelianBridging);
//            matiHere(__LINE__);
                $ctdb = New ComTransaksiDataPembelianBridging();
                $ctdb->pair($dataPembelianBridging);
                $ctdb->exec();
                // endregion mengisi tabel bridge pembelian

//            mati_disini("BELUM COMMIT MODE DEBUG, AUTO PO BERHASIL...");


                $arrData = array(
                    "data" => array(
                        "dtime" => date("Y-m-d H:i:s"),
                        "fulldate" => date("Y-m-d"),
                        "referensi_id" => $reference_order_id,
                        "po_id" => $returnTransaksi["transaksi_id"],
                        "po_nomer" => $returnTransaksi["transaksi_nomer"],
                        "so_id" => $sales_order_id,
                        "so_nomer" => $sales_order_nama,
                        "domain" => ADM_LOCAL_DOMAIN,
                        "referensi_cabang_id" => $cabang_id,
                    ),
                    "api_domain" => ADM_DOMAIN . "penjualan/API/ModulConnect/api_writeBridge",
                );
                $encodeData = blobEncode($arrData);
                /**
                 * write backup order yang dikirim ke holding company
                 * sehingga tinggal copas ke holding company tabel reseller_master_bridge
                 * jika terjadi kegagalan endpoint holding 18 juni 2026  widi
                 */
                $lt = new TransaksionalPembelian();
                $lt->writeReselerMasterBridge($arrData);
//            mati_disini("BELUM COMMIT MODE DEBUG, AUTO PO BERHASIL...");

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    mati_disini("Gagal saat berusaha commit transaction!");
                }

                try {
                    /**
                     * kirim ke rabbitMQ
                     */
                $this->load->library("Rabbitmq_lib");
                    $rabbit = new Rabbitmq_lib();
                $rabbit_return = $rabbit->publish("transaksi", json_encode($arrData));
                    if (!isset($rabbit_return["status"]) || $rabbit_return["status"] != 200) {
                        log_message('error', "Sinkronisasi PO gagal di RabbitMQ. Status return tidak valid.");
                        cekMerah("RabbitMQ publish status bukan 200");
                    }
                    $rabbit->close();
                } catch (\Exception $e) {
                    log_message('error', "RabbitMQ Exception: " . $e->getMessage());
                    cekMerah("RabbitMQ Error: " . $e->getMessage());
                }



                cekHijau("AUTO PO BERHASIL...");
            }


        }
        else {
            cekMerah("DATA BRIDGE KOSONG...");
        }

    }

    // 2. sinkron po ADI yang sudah di so kan oleh SAN
    public function callPoAutoTerimaAPI()
    {

        $this->load->model("../../pembelian/models/Coms/ComTransaksiDataPembelianTerimaBridging");

        $cpb = New ComTransaksiDataPembelianTerimaBridging();
        $tbl_master_bridge = $cpb->getTableNameMaster()["master_bridge"];
        $tbl_data_bridge = $cpb->getTableName();
        $cpb->addFilter("cli_grn_id=0");
        $cpb->addFilter("so_id>0");
        $cpb->addFilter("po_id>0");
        $cpb->addFilter("cli_id>0");
        $cpb->addFilter("status=1");
        $cpb->addFilter("trash=0");
        $cpb->addFilter("cli=0");
        $this->db->limit(1);
        $this->db->order_by("id", "ASC");
        $cpb->setTableName($tbl_master_bridge);
        $cpbTmp = $cpb->lookupAll()->result();
        showLast_query("kuning");
        if (sizeof($cpbTmp) == 1) {
            $params = array(
                "referensi_id" => $cpbTmp[0]->referensi_id,
                "so_id" => $cpbTmp[0]->so_id,
                "po_id" => $cpbTmp[0]->po_id,
                "principal_spd_id" => $cpbTmp[0]->principal_spd_id,
                "domain" => ADM_LOCAL_DOMAIN,
                "cli_id" => $cpbTmp[0]->cli_id,
                "cabang_id" => $cpbTmp[0]->cabang_id,
            );
            $paramsBlob = blobEncode($params);
        }
        else {
            $msg = "Data kosong atau sudah sinkron semua. code: " . __LINE__;
            mati_disini($msg);
        }

//        cekHere($paramsBlob);
//        arrPrint($params);
//        mati_disini(__LINE__);

//        echo(ADM_DOMAIN . "penjualan/API/ModulConnect/api_getPackingList?enc=$paramsBlob");
        $insert = call_curl(ADM_DOMAIN . "penjualan/API/ModulConnect/api_getPackingList?enc=$paramsBlob");
//        print_r($insert);
//        mati_disini(__LINE__);

        $this->db->trans_start();


        // region update ke tabel pembelian_transaksi_bridge_master
        $status_api = isset($insert["status"]) ? $insert["status"] : 404;
        $lanjut_detail = false;
        switch ($status_api) {
            case "200":
                $cli = 1;
                $lanjut_detail = true;
                break;
            case "500":
                $cli = 11;
                break;
            case "404":
            default:
                $cli = 12;
                mati_disini("GAGAL, tidak mendapat respon dari server utama. " . __LINE__);
                break;
        }
//        mati_disini("GAGAL, tidak mendapat respon dari server utama. " . __LINE__);
        $where_master_update = array(
            "id" => $cpbTmp[0]->id,
        );
        $master_update = array(
            "cli" => $cli,
        );
        $cpb = New ComTransaksiDataPembelianTerimaBridging();
        $cpb->setTableName($tbl_master_bridge);
        $cpb->setFilters(array());
        $last_update_id = $cpb->updateData($where_master_update, $master_update);
        showLast_query("orange");
        if ($last_update_id == 0) {
            $msg = "CLI Auto sinkron persiapan GRN gagal karena update tabel pembelian bridge master salah. code: " . __LINE__;
            mati_disini($msg);
        }
        // endregion update ke tabel pembelian_transaksi_bridge_master


        if ($lanjut_detail == true) {

            // region menulis ke tabel pembelian_transaksi_bridge_terima_master
            if (isset($insert["data"]) && (sizeof($insert["data"]) > 0)) {
                $no = 0;
                foreach ($insert["data"] as $ii => $iiSpec) {

                    // region supplier_id
                    $sp = New MdlSupplier();
                    $sp->addFilter("member_id='" . $iiSpec["principal_cabang_id"] . "'");
                    $spTmp = $sp->lookupAll()->result();
                    if (sizeof($spTmp) > 0) {
                        $supplier_id_reseller = $spTmp[0]->id;// milik ADI adalah member_id, jadi kalau butuh SUPPLIER_ID maka cek ulang dengan filter member_id ini.
                        $supplier_nama_reseller = $spTmp[0]->nama;
                    }
                    else {
                        $msg = "Auto PO gagal karena SUPPLIER_ID tidak dikenal. Silahkan cek data supplier. code: " . __LINE__;
                        mati_disini($msg);
                    }
                    // endregion supplier_id

                    $no++;
                    $dataPembelianBridging[$no] = array(
                        "static" => array(
                            "cabang_id" => $iiSpec["cabang_id"],// cabang yang melakukan so
                            "referensi_id" => $iiSpec["referensi_id"],// pre so
                            "produk_id" => $iiSpec["produk_id"],// produk id
                            "produk_nama" => $iiSpec["produk_nama"],// produk nama
                            "produk_ord_hrg" => $iiSpec["principal_produk_ord_harga"],// harga jual reseller
                            "produk_ord_jml" => $iiSpec["principal_produk_ord_jml"],// jumlah po
//                        "qty_saldo" => $iiSpec["jml"],// jumlah po
                            "supplier_id" => $iiSpec["principal_cabang_id"],// cabang san sebagai supplier
                            "supplier_nama" => "",
                            "po_id" => $iiSpec["po_id"],
                            "so_id" => $iiSpec["so_id"],
                            "auto_po_id" => $iiSpec["po_id"],
                            "transaksi_id" => $iiSpec["so_id"],
//                        "oleh_id" => $companyProfileID,
//                        "oleh_nama" => $companyProfileNama,
                            "domain" => ADM_LOCAL_DOMAIN,
                            "principal_spd_id" => $iiSpec["principal_spd_id"],
                            "principal_spd_nomer" => $iiSpec["principal_spd_nomer"],
                            "principal_spd_dtime" => $iiSpec["principal_spd_dtime"],
                            "principal_cabang_id" => $iiSpec["principal_cabang_id"],
                            "principal_produk_ord_hrg" => $iiSpec["principal_produk_ord_harga"],// harga jual reseller
                            "principal_produk_ord_jml" => $iiSpec["principal_produk_ord_jml"],// jumlah po
                            "dtime" => date("Y-m-d H:i:s"),
                            "fulldate" => date("Y-m-d"),
                            "member_id" => $supplier_id_reseller,
                            "member_nama" => $supplier_nama_reseller,
                        ),
                    );

                }
                arrPrintWebs($dataPembelianBridging);
                $ctdb = New ComTransaksiDataPembelianTerimaBridging();
                $ctdb->pair($dataPembelianBridging);
                $ctdb->exec();
            }
            else {
                $msg = "Data sinkronisasi kosong... code: " . __LINE__;
                mati_disini($msg);
            }
            // endregion menulis ke tabel pembelian_transaksi_bridge_terima_master

        }


//        mati_disini("BELUM COMMIT MODE DEBUG, AUTO PO BERHASIL...");
        $this->db->trans_complete() or mati_disini(("Gagal saat berusaha  commit transaction!"));

        cekHijau("AUTO PO BERHASIL...");


    }

    // 3. sinkron auto pre-grn, grn, req distribusi, auto otorisasi distribusi, auto terima di cabang, auto pre-packinglist.
    public function autoTerimaPurchaseOrder()
    {

        // region membaca tabel bridge
        $p = new Transaksional();
        $tempDataBridge = $temp = $p->callPoAutoTerima();
        // endregion membaca tabel bridge

        if (sizeof($temp) > 0) {
            $supplier_id = $temp[0]->supplier_id;
            $supplier_nama = $temp[0]->supplier_nama;
            $cabang_tujuan_id = $cabang_id = $temp[0]->cabang_id;
            $cabang_tujuan_nama = $cabang_nama = $temp[0]->cabang_nama;
            $gudang_id = $temp[0]->gudang_id;
            $gudang_nama = $temp[0]->gudang_nama;
            $reference_order_id = $temp[0]->referensi_id;
            $sales_order_id = $temp[0]->transaksi_id;
            $sales_order_nama = $temp[0]->transaksi_no;
            $po_id = $temp[0]->auto_po_id;
            $po_nomer = $temp[0]->auto_po_nomer;
            $principal_spd_id = $temp[0]->principal_spd_id;
            $principal_spd_nomer = $temp[0]->principal_spd_nomer;

            $div_id = 18;
            $div_nama = "";
            $connectToStep = $jenisTr_master = "466";
            $cCode = "_TR_" . $connectToStep;
            $modul = getFolderModul($connectToStep);
            $pathModul = "../../$modul/models";
            $modelModul = "MdlPembelianTransaksi";

            $itemsReplacer = array();
            $itemsReplacerPackinglist = array();
            $itemsReplacerUpdater = array();
            foreach ($temp as $tempSpec) {
                $produk_id = $tempSpec->produk_id;
                $produk_nama = $tempSpec->produk_nama;
                $produk_jml = $tempSpec->principal_produk_ord_jml;
                $sales_order_harga = $tempSpec->principal_produk_ord_hrg;
                $itemsReplacer[$produk_id] = array(
                    "id" => $produk_id,
                    "nama" => $produk_nama,
                    "jml" => $produk_jml,
                    "harga" => $sales_order_harga,
                    "hpp" => $sales_order_harga,
                );
                $itemsReplacerPackinglist[$produk_id] = array(
                    "id" => $produk_id,
                    "nama" => $produk_nama,
                    "jml" => $produk_jml,
                );
                $itemsReplacerUpdater[$produk_id] = array(
                    "id_tbl" => $tempSpec->id,
                    "id" => $produk_id,
                    "nama" => $produk_nama,
                    "jml" => $produk_jml,
                );
            }


            $this->db->trans_start();


            // region otorisasi pre-grn
            cekMerah("START MENJALANKAN AUTO OTORISASI TRANSAKSI PRE-GRN");
            $lt = New TransaksionalPembelian();
            $lt->setModelModules($modelModul);
            $lt->setPathModules($pathModul);
            $step_number = 2;
            $nextStepNumTarget = $step_number + 1;
            cekMerah("[$nextStepNumTarget = $step_number + 1]");
            $addMainData = array(
                "description_main_followup" => "$principal_spd_nomer",
            );
            $returnTransaksi = $lt->autoOtorisasi($connectToStep, $po_id, $nextStepNumTarget, $step_number, $itemsReplacer, $extractedItems_last, $addMainData);
            cekMerah("SELESAI MENJALANKAN AUTO OTORISASI TRANSAKSI PRE-GRN");
            // endregion otorisasi pre-grn

//            arrPrint($returnTransaksi);
//            mati_disini(__LINE__);

            // region otorisasi grn
            cekMerah("START MENJALANKAN AUTO OTORISASI TRANSAKSI GRN");
            $lt = New TransaksionalPembelian();
            $lt->setModelModules($modelModul);
            $lt->setPathModules($pathModul);
            $step_number = 3;
            $nextStepNumTarget = $step_number + 1;
            cekMerah("[$nextStepNumTarget = $step_number + 1]");
            $addMainData = array(
                "description_main_followup" => "$po_nomer",
            );
            $pre_grn_id = $returnTransaksi["transaksi_id"];
            $pre_grn_nomer = $returnTransaksi["transaksi_nomer"];
            $returnTransaksi = $lt->autoOtorisasi($connectToStep, $pre_grn_id, $nextStepNumTarget, $step_number, $itemsReplacer, $extractedItems_last, $addMainData);
            $transaksi_grn_id = $returnTransaksi["transaksi_id"];
            $transaksi_grn_nomer = $returnTransaksi["transaksi_nomer"];
            $sessionData[$cCode] = $returnTransaksi["sessionData"];
            cekMerah("SELESAI MENJALANKAN AUTO OTORISASI TRANSAKSI GRN");
            // endregion otorisasi grn


            // region update tabel pembelian bridge terima
            $this->load->model("../../pembelian/models/Coms/ComTransaksiDataPembelianTerimaBridging");
            foreach ($itemsReplacerUpdater as $pid => $pidSpec) {
                $id_tbl = $pidSpec["id_tbl"];
                $qty_update = $pidSpec["jml"];
                $data_update = array(
                    "principal_produk_ord_jml" => 0,
                    "principal_produk_ord_jml_cli" => $qty_update,
                    "cli_grn" => 1,
                    "cli_grn_dtime" => date("Y-m-d H:i:s"),

                );
                $where_update = array(
                    "id" => $id_tbl,
                );
                $cpb = New ComTransaksiDataPembelianTerimaBridging();
                $cpb->setFilters(array());
                $cpb->updateData($where_update, $data_update);
                showLast_query("orange");
            }
            $cpb = New ComTransaksiDataPembelianTerimaBridging();
            $cpbTbl = $cpb->getTableNameMaster()["master_bridge"];
            $cpb->setTableName($cpbTbl);
            $master_update = array(
                "cli_grn_id" => $transaksi_grn_id,
                "cli_grn_nomer" => $transaksi_grn_nomer,
                "cli_grn_dtime" => date("Y-m-d H:i:s"),
            );
            $where_master_update = array(
                "principal_spd_id" => $principal_spd_id,
            );
            $cpb->setFilters(array());
            $last_update_id = $cpb->updateData($where_master_update, $master_update);
            showLast_query("orange");
            if ($last_update_id == 0) {
                $msg = "CLI Auto GRN gagal disimpan karena update tabel pembelian bridge master salah. code: " . __LINE__;
                mati_disini($msg);
            }
            // endregion update tabel pembelian bridge terima

//            mati_disini(__LINE__);


            // region auto create distribusi
            cekMerah("START AUTO CREATE DISTRIBUSI KE CABANG");
            $connectToStep = "583";
            $modul = getFolderModul($connectToStep);
            $pathModul = "../../$modul/models";
            $modelModul = "MdlDistribusiTransaksi";
            $configUiMasterModulJenis = loadConfigModulJenis_he_misc($connectToStep, "coTransaksiUi");
            $configCoreMasterModulJenis = loadConfigModulJenis_he_misc($connectToStep, "coTransaksiCore");
            $configLayoutMasterModulJenis = loadConfigModulJenis_he_misc($connectToStep, "coTransaksiLayout");
            $configValuesMasterModulJenis = loadConfigModulJenis_he_misc($connectToStep, "coTransaksiValues");
            $preReplacer = isset($this->configUi[$this->jenisTr]['replacerConnectToStep']) ? $this->configUi[$this->jenisTr]['replacerConnectToStep'] : array();
            $connectToStepMainBuilder = isset($this->configUi[$this->jenisTr]['connectToStepMainBuilder'][$stepNum]) ? $this->configUi[$this->jenisTr]['connectToStepMainBuilder'][$stepNum] : array();

//            $clonerTransaction = isset($this->configUi[$this->jenisTr]['clonerTransaction'][$jenisTrTarget]) ? $this->configUi[$this->jenisTr]['clonerTransaction'][$jenisTrTarget] : array();
//            if (sizeof($clonerTransaction) && isset($clonerTransaction['main']['cloner'])) {
////                        cekKuning("ATAS");
//                $sessionData[$cCode] = $sessionData[$oldCode];
//                $masterReplacers = array(
//                    "inv" => $tmpNomorNota,
//                    "jenis_master" => $connectToStep,
//                    "jenis_top" => $configUiMasterModulJenis['steps'][$step_number]['target'],
//                    "jenis" => $configUiMasterModulJenis['steps'][$step_number]['target'],
//                    "jenis_label" => $configUiMasterModulJenis['steps'][$step_number]['label'],
//                    "transaksi_jenis" => $configUiMasterModulJenis['steps'][$step_number]['target'],
//                    "div_id" => my_div_id(),
//                    "div_nama" => my_div_nama(),
//                    //                            "cabang_id"       => $_SESSION[$oldCode]['tableIn_master']['cabang2_id'],
//                    //                            "cabang_nama"     => $_SESSION[$oldCode]['tableIn_master']['cabang2_nama'],
//                    //                            "gudang_id"       => $_SESSION[$oldCode]['tableIn_master']['gudang2_id'],
//                    //                            "gudang_nama"     => $_SESSION[$oldCode]['tableIn_master']['gudang2_nama'],
//                    "cabang2_id" => $arrCabangTujuan['cabang_id'],
//                    "cabang2_nama" => $arrCabangTujuan['cabang_nama'],
//                    "gudang2_id" => $arrCabangTujuan['gudang_id'],
//                    "gudang2_nama" => $arrCabangTujuan['gudang_nama'],
//
//                    "step_avail" => sizeof($configUiMasterModulJenis['steps']),
//                    "step_current" => $step_number,
//                    "step_number" => $step_number,
//                    "next_step_code" => isset($configUiMasterModulJenis['steps'][$next_step_number]) ? $configUiMasterModulJenis['steps'][$next_step_number]['target'] : "",
//                    "next_step_label" => isset($configUiMasterModulJenis['steps'][$next_step_number]) ? $configUiMasterModulJenis['steps'][$next_step_number]['label'] : "",
//                    "next_group_code" => isset($configUiMasterModulJenis['steps'][$next_step_number]) ? $configUiMasterModulJenis['steps'][$next_step_number]['userGroup'] : "",
//                    "next_step_num" => isset($configUiMasterModulJenis['steps'][$next_step_number]) ? $next_step_number : "0",
//
//                );
//                $masterReplacersO = array(
//                    "jenisTr" => $connectToStep,
//                    "div_id" => my_div_id(),
//                    "jenisTrMaster" => $connectToStep,
//                    "jenisTrTop" => $configUiMasterModulJenis['steps'][$step_number]['target'],
//                    "jenis" => $configUiMasterModulJenis['steps'][$step_number]['target'],
//                    "jenis_label" => $configUiMasterModulJenis['steps'][$step_number]['label'],
//                    "transaksi_jenis" => $configUiMasterModulJenis['steps'][$step_number]['target'],
//                    "stepCode" => $configUiMasterModulJenis['steps'][$step_number]['target'],
//                    "jenisTrName" => $configUiMasterModulJenis['steps'][$step_number]['label'],
//                    //                            "placeID"         => $_SESSION[$oldCode]['main']['place2ID'],
//                    //                            "placeName"       => $_SESSION[$oldCode]['main']['place2Name'],
//                    //                            "place2ID"        => $_SESSION[$oldCode]['main']['placeID'],
//                    //                            "place2Name"      => $_SESSION[$oldCode]['main']['placeName'],
//                    //                            "cabangID"        => $_SESSION[$oldCode]['main']['place2ID'],
//                    //                            "cabangName"      => $_SESSION[$oldCode]['main']['place2Name'],
//                    //                            "cabang2ID"       => $_SESSION[$oldCode]['main']['placeID'],
//                    //                            "cabang2Name"     => $_SESSION[$oldCode]['main']['placeName'],
//                    //                            //
//                    //                            "gudang2ID"       => $_SESSION[$cCode]['main']['gudangID'],
//                    //                            "gudang2Name"     => $_SESSION[$cCode]['main']['gudangName'],
//                    //                            "gudangID"        => $_SESSION[$cCode]['main']['gudang2ID'],
//                    //                            "gudangName"      => $_SESSION[$cCode]['main']['gudang2Name'],
//                    "pihakID" => $arrCabangTujuan['cabang_id'],
//                    "pihakName" => $arrCabangTujuan['cabang_nama'],
//                    "place2ID" => $arrCabangTujuan['cabang_id'],
//                    "place2Name" => $arrCabangTujuan['cabang_nama'],
//                    "cabang2ID" => $arrCabangTujuan['cabang_id'],
//                    "cabang2Name" => $arrCabangTujuan['cabang_nama'],
//                    "cabang2_id" => $arrCabangTujuan['cabang_id'],
//                    "cabang2_nama" => $arrCabangTujuan['cabang_nama'],
//                    "gudang2_id" => $arrCabangTujuan['gudang_id'],
//                    "gudang2_nama" => $arrCabangTujuan['gudang_nama'],
//                    "branchDetails" => $arrCabangTujuan['cabang_id'],
//                    "branchDetails__name" => $arrCabangTujuan['cabang_nama'],
//                    "branchDetails__label" => $arrCabangTujuan['cabang_nama'],
//                    "gudang2ID" => $arrCabangTujuan['gudang_id'],
//                    "gudang2Name" => $arrCabangTujuan['gudang_nama'],
//                    "gudang2ID__name" => $arrCabangTujuan['gudang_nama'],
//                    "gudang2ID__label" => $arrCabangTujuan['gudang_nama'],
//                );
//            }
//            else {
//                //cekKuning("BAWAH");
//                $sessionData[$cCode] = $sessionData[$oldCode];
//                $masterReplacersO = array(
//                    "jenisTr" => $connectToStep,
//                    "jenisTrMaster" => $connectToStep,
//                    "jenisTrTop" => $configUiMasterModulJenis['steps'][$step_number]['target'],
//                    "jenis" => $configUiMasterModulJenis['steps'][$step_number]['target'],
//                    "jenis_label" => $configUiMasterModulJenis['steps'][$step_number]['label'],
//                    "transaksi_jenis" => $configUiMasterModulJenis['steps'][$step_number]['target'],
//                    "stepCode" => $configUiMasterModulJenis['steps'][$step_number]['target'],
//                    "placeID" => isset($preReplacer['place2ID']) ? $preReplacer['place2ID'] : $sessionData[$cCode]['main']['place2ID'],
//                    "placeName" => isset($preReplacer['place2Name']) ? $preReplacer['place2Name'] : $sessionData[$cCode]['main']['place2Name'],
//                    "place2ID" => $sessionData[$cCode]['main']['placeID'],
//                    "place2Name" => $sessionData[$cCode]['main']['placeName'],
//                    "cabangID" => isset($preReplacer['cabang2ID']) ? $preReplacer['cabang2ID'] : $sessionData[$cCode]['main']['place2ID'],
//                    "cabangName" => isset($preReplacer['place2Name']) ? $preReplacer['place2Name'] : $sessionData[$cCode]['main']['place2Name'],
//                    "cabang2ID" => $sessionData[$cCode]['main']['placeID'],
//                    "cabang2Name" => $sessionData[$cCode]['main']['placeName'],
//                    //
//                    "gudang2ID" => $sessionData[$cCode]['main']['gudangID'],
//                    "gudang2Name" => $sessionData[$cCode]['main']['gudangName'],
//                    "gudangID" => isset($preReplacer['gudang2ID']) ? $preReplacer['gudang2ID'] : $sessionData[$cCode]['main']['gudang2ID'],
//                    "gudangName" => isset($preReplacer['gudang2Name']) ? $preReplacer['gudang2Name'] : $sessionData[$cCode]['main']['gudang2Name'],
//                    "pihakID" => isset($sessionData[$cCode]['main']['placeID']) ? $sessionData[$cCode]['main']['placeID'] : "",
//                    "pihakName" => isset($sessionData[$cCode]['main']['placeName']) ? $sessionData[$cCode]['main']['placeName'] : "",
//                    "pihakName2" => $sessionData[$cCode]['main']['placeName'],
//                    "gudang" => $sessionData[$cCode]['main']['gudangID'],
//                    "gudang__name" => $sessionData[$cCode]['main']['gudangName'],
//                    "gudang__label" => $sessionData[$cCode]['main']['gudangName'],
//                    "efaktur_source" => isset($preReplacer['efaktur_source']) ? $sessionData[$cCode]['main']['nomer'] : "",
//
//                );
//                $masterReplacers = array(
//                    //                    "referensi_id" => $masterID, (dimatikan)
//                    "inv" => $tmpNomorNota,
//                    "jenis_master" => $connectToStep,
//                    "jenis_top" => $configUiMasterModulJenis['steps'][$step_number]['target'],
//                    "jenis" => $configUiMasterModulJenis['steps'][$step_number]['target'],
//                    "jenis_label" => $configUiMasterModulJenis['steps'][$step_number]['label'],
//                    "transaksi_jenis" => $configUiMasterModulJenis['steps'][$step_number]['target'],
//                    "cabang_id" => isset($preReplacer['cabang2ID']) ? $preReplacer['cabang2ID'] : $sessionData[$cCode]['tableIn_master']['cabang2_id'],
//                    "cabang_nama" => isset($preReplacer['cabang2Name']) ? $preReplacer['cabang2Name'] : $sessionData[$cCode]['tableIn_master']['cabang2_nama'],
//                    "cabang2_id" => $sessionData[$cCode]['tableIn_master']['cabang_id'],
//                    "cabang2_nama" => $sessionData[$cCode]['tableIn_master']['cabang_nama'],
//                    "gudang_id" => isset($preReplacer['gudang2ID']) ? $preReplacer['gudang2ID'] : $sessionData[$cCode]['tableIn_master']['gudang2_id'],
//                    "gudang_nama" => isset($preReplacer['gudang2Name']) ? $preReplacer['gudang2Name'] : $sessionData[$cCode]['tableIn_master']['gudang2_nama'],
//                    "gudang2_id" => $sessionData[$cCode]['tableIn_master']['gudang_id'],
//                    "gudang2_nama" => $sessionData[$cCode]['tableIn_master']['gudang_nama'],
//                    "gudang" => $sessionData[$cCode]['tableIn_master']['gudang_id'],
//                    "gudang__name" => $sessionData[$cCode]['tableIn_master']['gudang_nama'],
//                    "gudang__label" => $sessionData[$cCode]['tableIn_master']['gudang_nama'],
//
//                    "step_avail" => sizeof($configUiMasterModulJenis['steps']),
//                    "step_current" => $step_number,
//                    "step_number" => $step_number,
//                    "next_step_code" => isset($configUiMasterModulJenis['steps'][$next_step_number]) ? $configUiMasterModulJenis['steps'][$next_step_number]['target'] : "",
//                    "next_step_label" => isset($configUiMasterModulJenis['steps'][$next_step_number]) ? $configUiMasterModulJenis['steps'][$next_step_number]['label'] : "",
//                    "next_group_code" => isset($configUiMasterModulJenis['steps'][$next_step_number]) ? $configUiMasterModulJenis['steps'][$next_step_number]['userGroup'] : "",
//                    "next_step_num" => isset($configUiMasterModulJenis['steps'][$next_step_number]) ? $next_step_number : "0",
//                    "efaktur_source" => isset($preReplacer['efaktur_source']) ? $sessionData[$cCode]['main']['nomer'] : "",
//
//                );
//            }

//            $place_id = CB_ID_PUSAT;
//            $place_nama = CB_NAME_PUSAT;
            $place_id = $sessionData[$cCode]['main']['placeID'];
            $place_nama = $sessionData[$cCode]['main']['placeName'];
            $place2_id = $cabang_tujuan_id;
            $place2_nama = $cabang_tujuan_nama;
            $gudang_id = getDefaultWarehouseID($place_id)["gudang_id"];
            $gudang_nama = getDefaultWarehouseID($place_id)["gudang_nama"];
            $gudang2_id = getDefaultWarehouseID($place2_id)["gudang_id"];
            $gudang2_nama = getDefaultWarehouseID($place2_id)["gudang_nama"];

            $oldCode = $cCode;
            $cCode = "_TR_" . $connectToStep;
            $step_number = 1;
            $next_step_number = $step_number + 1;
            cekMerah("[oldCode: $oldCode] [cCode: $cCode]");

            $sessionData[$cCode] = array();
            $sessionData[$cCode] = $sessionData[$oldCode];
            $masterReplacersO = array(
                "jenisTr" => $connectToStep,
                "jenisTrMaster" => $connectToStep,
                "jenisTrTop" => $configUiMasterModulJenis['steps'][$step_number]['target'],
                "jenis" => $configUiMasterModulJenis['steps'][$step_number]['target'],
                "jenis_label" => $configUiMasterModulJenis['steps'][$step_number]['label'],
                "transaksi_jenis" => $configUiMasterModulJenis['steps'][$step_number]['target'],
                "stepCode" => $configUiMasterModulJenis['steps'][$step_number]['target'],
                "placeID" => $place_id,
                "placeName" => $place_nama,
                "place2ID" => $place2_id,
                "place2Name" => $place2_nama,
                "cabangID" => $place_id,
                "cabangName" => $place_nama,
                "cabang2ID" => $place2_id,
                "cabang2Name" => $place2_nama,

                "gudangID" => $gudang_id,
                "gudangName" => $gudang_nama,
                "gudang2ID" => $gudang2_id,
                "gudang2Name" => $gudang2_nama,

                "pihakID" => $place2_id,
                "pihakName" => $place2_nama,
                "pihakName2" => $place2_nama,

                "gudang" => $gudang2_id,
                "gudang__name" => $gudang2_nama,
                "gudang__label" => $gudang2_nama,
//                "efaktur_source" => isset($preReplacer['efaktur_source']) ? $sessionData[$cCode]['main']['nomer'] : "",
            );
            $masterReplacers = array(
                "inv" => $tmpNomorNota,
                "jenis_master" => $connectToStep,
                "jenis_top" => $configUiMasterModulJenis['steps'][$step_number]['target'],
                "jenis" => $configUiMasterModulJenis['steps'][$step_number]['target'],
                "jenis_label" => $configUiMasterModulJenis['steps'][$step_number]['label'],
                "transaksi_jenis" => $configUiMasterModulJenis['steps'][$step_number]['target'],

                "cabang_id" => $cabang_id,
                "cabang_nama" => $cabang_nama,
                "gudang_id" => $gudang_id,
                "gudang_nama" => $gudang_nama,

                "cabang2_id" => $cabang_tujuan_id,
                "cabang2_nama" => $cabang_tujuan_nama,
                "gudang2_id" => $gudang2_id,
                "gudang2_nama" => $gudang2_nama,
                "gudang" => $gudang2_id,
                "gudang__name" => $gudang2_nama,
                "gudang__label" => $gudang2_nama,

                "step_avail" => sizeof($configUiMasterModulJenis['steps']),
                "step_current" => $step_number,
                "step_number" => $step_number,
                "next_step_code" => isset($configUiMasterModulJenis['steps'][$next_step_number]) ? $configUiMasterModulJenis['steps'][$next_step_number]['target'] : "",
                "next_step_label" => isset($configUiMasterModulJenis['steps'][$next_step_number]) ? $configUiMasterModulJenis['steps'][$next_step_number]['label'] : "",
                "next_group_code" => isset($configUiMasterModulJenis['steps'][$next_step_number]) ? $configUiMasterModulJenis['steps'][$next_step_number]['userGroup'] : "",
                "next_step_num" => isset($configUiMasterModulJenis['steps'][$next_step_number]) ? $next_step_number : "0",
                "efaktur_source" => isset($preReplacer['efaktur_source']) ? $sessionData[$cCode]['main']['nomer'] : "",

            );

            foreach ($masterReplacersO as $key => $val) {
                $sessionData[$cCode]['main'][$key] = $val;
            }
            foreach ($masterReplacers as $key => $val) {
                $sessionData[$cCode]['tableIn_master'][$key] = $val;
            }
            if (sizeof($connectToStepMainBuilder) > 0) {
                foreach ($connectToStepMainBuilder as $r_key => $r_val) {
                    $sessionData[$cCode]['main'][$r_key] = isset($sessionData[$cCode]['main'][$r_val]) ? $sessionData[$cCode]['main'][$r_val] : "";
                }
            }
            //-------
            $elementConfigs = isset($configUiMasterModulJenis['receiptElements']) ? $configUiMasterModulJenis['receiptElements'] : array();
            if (sizeof($elementConfigs) > 0) {
                foreach ($elementConfigs as $eName => $eSpec) {
                    switch ($eSpec['elementType']) {
                        case "dataModel":
                            $amdlName = $eSpec['mdlName'];
                            $this->load->model("Mdls/" . $amdlName);
                            $labelSrc = $eSpec['labelSrc'];
                            $keySrc = $eSpec['key'];
                            $oo = new $amdlName();
                            $aFilter = isset($eSpec['mdlFilter']) ? $eSpec['mdlFilter'] : array();
                            if (sizeof($aFilter) > 0) {
                                $oo = makeFilter($aFilter, $sessionData[$cCode]['main'], $oo);
                            }
                            $tmpo = $oo->lookupAll()->result();
                            if (sizeof($tmpo) == 1) {
                                $usedKey = $eSpec['key'];
                                $defValueSrc = $tmpo[0]->$usedKey;
                                $sessionData[$cCode] = heFetchElement_modul_ns($connectToStep, $eName, $eSpec['mdlName'], $defValueSrc, $configUiMasterModulJenis, $sessionData[$cCode]);
                            }


                            break;
                        case "dataField":
                            break;
                    }
                }
            }
            //-------
            $configUi = loadConfigUiByModul_he_misc()[$modul];
            $configLayout = loadConfigLayoutByModul_he_misc()[$modul];
            $configCore = loadConfigCoreByModul_he_misc()[$modul];
            $configValues = loadConfigValuesByModul_he_misc()[$modul];
            //-------dimasukkan ke value builder non session
            $this->load->helper("he_value_builder");
            $sessionData[$cCode] = fillValues_he_value_builder_ns($connectToStep, 1, 1, $configCore[$connectToStep], $configUi[$connectToStep], $configValues[$connectToStep], $ppnFactor, NULL, $sessionData[$cCode]);
            //-------
            $this->load->library("TransaksionalPembelian");
            $lt = New TransaksionalPembelian();
            $lt->setCCode($cCode);
            $lt->setModul($modul);
            $lt->setJenisTr($connectToStep);
            $lt->setCCodeData($sessionData);
            $lt->setStepNum($step_number);
            $lt->setStepNumCurrent($step_number);
            $lt->setTransaksiNumber(0);
            $lt->setConfigUiModul($configUi);
            $lt->setConfigLayoutModul($configLayout);
            $lt->setConfigCoreModul($configCore);
            $lt->setConfigValuesModul($configValues);
            $lt->setConfigCoreMaster($this->config->item('heTransaksi_core'));
            $lt->setModelModules($modelModul);
            $lt->setPathModules($pathModul);
            $libResult = $lt->autoCreate();
//            arrPrint($libResult);
            cekMerah("SELESAI AUTO CREATE DISTRIBUSI KE CABANG");
            // endregion auto create distribusi

//            mati_disini(__LINE__);


            // region auto otorisasi distribusi 1 dan connecting ke cabang
            $transaksi_req_distribusi_id = $libResult["transaksi_id"];
            $transaksi_req_distribusi_nomer = $libResult["transaksi_nomer"];
            cekMerah("START MENJALANKAN AUTO OTORISASI TRANSAKSI DISTRIBUSI");
            $lt = New TransaksionalPembelian();
            $lt->setModelModules($modelModul);
            $lt->setPathModules($pathModul);
            $step_number = 1;
            $nextStepNumTarget = $step_number + 1;
            cekMerah("[$nextStepNumTarget = $step_number + 1]");
            $addMainData = array(
                "description_main_followup" => "$po_nomer",
                "ppnFactor" => 12,
                "ppnConstanta" => "0.9166666667",
                "ppnConstantaStr" => "11/12",
                "ppnPersenCheck" => "1",
            );
            $returnTransaksi = $lt->autoOtorisasi($connectToStep, $transaksi_req_distribusi_id, $nextStepNumTarget, $step_number, $itemsReplacer, $extractedItems_last, $addMainData);
            cekMerah("SELESAI MENJALANKAN AUTO OTORISASI TRANSAKSI DISTRIBUSI");
            // endregion auto otorisasi distribusi 1 dan connecting ke cabang

            cekHitam($returnTransaksi["transaksi_id"]);
            cekHitam($returnTransaksi["transaksi_nomer"]);
            cekHitam($returnTransaksi["transaksi_id_connecting"]);
            cekHitam($returnTransaksi["transaksi_nomer_connecting"]);
//            mati_disini(__LINE__);
            $pakai_ini = 1;
            if ($pakai_ini == 1) {

                // region auto otorisasi distribusi 2 di cabang
                $connectToStep = "585";
                $transaksi_connect_distribusi_id = $returnTransaksi["transaksi_id_connecting"];
                $transaksi_connect_distribusi_nomer = $returnTransaksi["transaksi_nomer_connecting"];
                cekMerah("START MENJALANKAN AUTO TERIMA TRANSAKSI DISTRIBUSI [$transaksi_connect_distribusi_id] [$transaksi_connect_distribusi_nomer]");
                $lt = New TransaksionalPembelian();
                $lt->setModelModules($modelModul);
                $lt->setPathModules($pathModul);
                $step_number = 1;
                $nextStepNumTarget = $step_number + 1;
                cekMerah("[$nextStepNumTarget = $step_number + 1]");
                $addMainData = array(
                    "description_main_followup" => "$po_nomer",
                    "ppnFactor" => 12,
                    "ppnConstanta" => "0.9166666667",
                    "ppnConstantaStr" => "11/12",
                    "ppnPersenCheck" => "1",
                );
                $returnTransaksi = $lt->autoOtorisasi($connectToStep, $transaksi_connect_distribusi_id, $nextStepNumTarget, $step_number, $itemsReplacer, $extractedItems_last, $addMainData);
                cekMerah("SELESAI MENJALANKAN AUTO TERIMA TRANSAKSI DISTRIBUSI");
                // endregion auto otorisasi distribusi 2 di cabang


                // region auto pre-packinglist
                $connectToStep = "5822";
                $modul = getFolderModul($connectToStep);
                $pathModul = "../../$modul/models";
                $modelModul = "MdlPenjualanTransaksi";
                cekMerah("START MENJALANKAN AUTO PRE-PACKINGLIST [$sales_order_id] [$sales_order_nama]");
                $lt = New TransaksionalPembelian();
                $lt->setModelModules($modelModul);
                $lt->setPathModules($pathModul);
                $step_number = 2;
                $nextStepNumTarget = $step_number + 1;
                cekMerah("[$nextStepNumTarget = $step_number + 1]");
                $addMainData = array(
                    "description_main_followup" => "$po_nomer",
                    "ppnFactor" => 12,
                    "ppnConstanta" => "0.9166666667",
                    "ppnConstantaStr" => "11/12",
                    "ppnPersenCheck" => "1",
                );
                arrPrint($itemsReplacerPackinglist);
                $returnTransaksi = $lt->autoOtorisasi($connectToStep, $sales_order_id, $nextStepNumTarget, $step_number, $itemsReplacerPackinglist, $extractedItems_last, $addMainData);
                cekMerah("SELESAI MENJALANKAN AUTO PRE-PACKINGLIST");
                // endregion auto pre-packinglist


            }

//            mati_disini("BELUM COMMIT MODE DEBUG, AUTO OTORISASI BERHASIL...");

            $this->db->trans_complete() or mati_disini(("Gagal saat berusaha  commit transaction!"));

            cekHijau("SELESAI.... AUTO OTORISASI BERHASIL...");


        }
        else {
            cekMerah("DATA BRIDGE KOSONG...");
        }


    }


    public function autoRejectPurchaseOrder()
    {

        //ambil transaksi dari daftar reject
        $this->db->trans_start();
        $lt = new TransaksionalPembelian();
        $this->load->model("MdlSalesRejectHolding");

        $t = new MdlSalesRejectHolding();
        $prevData = $t->lookUpRejectedPoTrans();//rejectall
        if (count($prevData)) {
            // region config pembelian
            $connectToStep = $jenisTr_master = "466";
            $modul = getFolderModul($connectToStep);
            $pathModul = "../../$modul/models";
            $modelModul = "MdlPembelianTransaksi";
            $configUiMasterModulJenis = loadConfigModulJenis_he_misc($connectToStep, "coTransaksiUi");
            $configCoreMasterModulJenis = loadConfigModulJenis_he_misc($connectToStep, "coTransaksiCore");
            $configLayoutMasterModulJenis = loadConfigModulJenis_he_misc($connectToStep, "coTransaksiLayout");
            $configValuesMasterModulJenis = loadConfigModulJenis_he_misc($connectToStep, "coTransaksiValues");
            $configUi = loadConfigUiByModul_he_misc()[$modul];
            $configLayout = loadConfigLayoutByModul_he_misc()[$modul];
            $configCore = loadConfigCoreByModul_he_misc()[$modul];
            $configValues = loadConfigValuesByModul_he_misc()[$modul];

            $cCode = "_TR_" . $connectToStep;
            $step_number = 1;
            $next_step_number = $step_number + 1;
            $selectorSrcModel = $configUiMasterModulJenis['selectorSrcModel'];
            $fieldSrcs = isset($configUiMasterModulJenis['shoppingCartFieldSrc']) ? $configUiMasterModulJenis['shoppingCartFieldSrc'] : array();
            $subAmountConfig = isset($configUiMasterModulJenis['shoppingCartAmountValue'][1]) ? $configUiMasterModulJenis['shoppingCartAmountValue'][1] : null;
            // endregion config pembelian
            $sessionData = array();

            $currentID = $prevData[0]["po_id"];
            $lt->setCCode($cCode);
            $lt->setModul($modul);
            $lt->setJenisTr($connectToStep);
            $lt->setCCodeData($sessionData);
            $lt->setStepNum($step_number);
            $lt->setStepNumCurrent($step_number);
            $lt->setTransaksiNumber(0);
            $lt->setConfigUiModul($configUi);
            $lt->setConfigLayoutModul($configLayout);
            $lt->setConfigCoreModul($configCore);
            $lt->setConfigValuesModul($configValues);
            $lt->setConfigCoreMaster($this->config->item('heTransaksi_core'));
            $lt->setModelModules($modelModul);
            $lt->setPathModules($pathModul);
            $execReject = $lt->autoReject($currentID);

            if (count($execReject["transaksi_id"]) > 0) {

            }
            else {
                matiHEre("gagal reject");
            }
            //update reejct yang sudah di exec
//            $currentID = $prevData[0]["po_id"];
            $update = array(
                "exec_po" => 1,
                "exec_po_id" => $execReject["transaksi_id"][0],
            );
//            arrPrint($execReject);
            $t->updateData(array("po_id" => $currentID), $update);
//            cekLime($this->db->last_query());
//arrPrint($execReject);

        }
        else {
            //nojob to exec skip
            cekHitam("habis");
        }


//        matiHere(__LINE__);
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        $po_id = 1;


    }

}
