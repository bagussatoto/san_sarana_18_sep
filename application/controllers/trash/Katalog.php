<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 1/31/2019
 * Time: 10:00 PM
 */
class Katalog extends CI_Controller
{

    private $dates = array();

    public function __construct()
    {

        parent::__construct();
        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
        // $this->load->model("Mdls/MdlKatalog");
        // $trd = new MdlKatalog();
        //        $trd->addFilter("jenis_top='" . $this->jenisTr . "'");
        //         $this->dates = $trd->lookupDates();
        //         $this->dates['entries'][date("y-m-d")] = date("y-m-d");

        validateUserSession($this->session->login['id']);//
        $this->cabang_id = $this->session->login['cabang_id'];
        $this->gudang_id = $this->session->login['gudang_id'];


        //        arrPrint($this->session->login);
    }

    public function index()
    {

        die("_" . __FUNCTION__);
    }

    public function viewProduk()
    {
        /* ----------------------------------------------------------
         * header diatur langsung di view/katalog
         * ----------------------------------------------------------*/
        //        arrPrint($this->uri->segment_array());
        $relName = $this->uri->segment(3);
        $rekName = urldecode($this->uri->segment(4));
        $defPosition = detectRekDefaultPosition($rekName);

        $balConfig = isset($this->config->item('accountBalanceColumns')[$relName]) ? $this->config->item('accountBalanceColumns')[$relName] : array();
        $accountFilters = isset($this->config->item('accountBalanceColumns')[$relName]['viewFilters']) ? $this->config->item('accountBalanceColumns')[$relName]['viewFilters'] : array();


        $q = isset($_GET['q']) && strlen($_GET['q']) ? $_GET['q'] : "";
        $sortBy = isset($_GET['sortBy']) && strlen($_GET['sortBy']) ? $_GET['sortBy'] : "extern_nama";
        $sortMode = isset($_GET['sortMode']) && strlen($_GET['sortMode']) ? $_GET['sortMode'] : "ASC";


        $cabangNAMA = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_nama'];
        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];
        $gudangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['gudang_id'];


        $thisPage = base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "?o=$cabangID";
        $thisURL = base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "?q=$q&o=$cabangID";

        $this->load->model("Mdls/MdlLockerStockBooking");
        $lsb = new MdlLockerStockBooking();
        $lsb_datas = $lsb->getStokBooking();

        $this->load->helper("he_mass_table");
        $this->load->helper("he_angka");
        $this->load->model("Mdls/MdlProduk2");
        $this->load->model("Mdls/MdlHargaProduk");
        $this->load->model("Mdls/MdlHargaProdukRakitan");
        $this->load->model("Mdls/MdlHargaProdukKomposit");
        $this->load->model("Mdls/MdlLockerStock");
        $this->load->model("Mdls/MdlImages");
        $this->load->model("Mdls/MdlCabang");
        $this->load->model("Mdls/MdlFifoProdukJadi");
        $this->load->model("Mdls/MdlCabang");
        $this->load->model("Mdls/MdlGudang");
        $this->load->model("Mdls/MdlGudangDefault_center");
        $this->load->model("Mdls/MdlGudangDefault");
        $com = new MdlProduk2();
        $hg = new MdlHargaProduk();
        $hgr = new MdlHargaProdukRakitan();
        $hgk = new MdlHargaProdukKomposit();
        $st = new MdlLockerStock();
        $sthold = new MdlLockerStock();
        $im = new MdlImages();
        $cb = new MdlCabang();
        $ff = new MdlFifoProdukJadi();
        $gg = new MdlGudang();//gudang non default
        $ca = new MdlCabang();
        $gd_center = new MdlGudangDefault_center();
        $gd_cabang = new MdlGudangDefault();
        $selectedGudangFields = array("id", "name", "nama");
        // $com->addFilter("cabang_id='$cabangID'");
        $listedProds = "('254','57','823','824')";//untuk test aja

        $inCabId = array();
        // $inGid = array("-250", "-210", "-10", "-1","-260","-270","-280","-290","-300","-310");//ini ditembak dulu sambil nyari cara auto get gudang default
        $inGid_0 = array("-250", "-210", "-10", "-1", "-260", "-270", "-280", "-290", "-300", "-310");//ini ditembak dulu sambil nyari cara auto get gudang default

        $tempDefGudangCenter = $gd_center->lookUpAll()->result();
        $tempDefGudang = $gd_cabang->lookUpAll()->result();
        $tempCabang = $ca->lookUpAll()->result();
        // arrPrint($tempDefGudangCenter);

        //region call all cabang
        $cabangData = array();
        $inCabId = array();
        $allCabang = array();
        foreach ($tempCabang as $tempCabang_0) {
            $inCabId[] = $tempCabang_0->id;
            $allCabang[$tempCabang_0->id] = $tempCabang_0->nama;
            foreach ($selectedGudangFields as $iIndex_key => $iFields) {
                $cabangData[$tempCabang_0->id][$iFields] = isset($tempCabang_0->$iFields) ? $tempCabang_0->$iFields : "";
            }
            // arrPrint($tempCabang_0);
        }
        // arrPrint($cabangData);

        //endregion
        $gudangDefaultCenter = array();
        $allGudangDefault = array();
        $allGudangMember = array();
        foreach ($tempDefGudangCenter as $iiCenterData) {
            $allGudangDefault[$iiCenterData->id] = $iiCenterData->id;
            foreach ($selectedGudangFields as $i => $fieldsGud) {
                $gudangDefaultCabang[$iiCenterData->id][$iiCenterData->id][$fieldsGud] = isset($iiCenterData->$fieldsGud) ? $iiCenterData->$fieldsGud : "";
            }
            $allGudangMember[$iiCenterData->id][] = $iiCenterData->id;
        }
        foreach ($tempDefGudang as $iiCabangData) {
            $allGudangDefault[$iiCabangData->id] = $iiCabangData->id;
            foreach ($selectedGudangFields as $i => $fieldsGud) {
                $gudangDefaultCabang[$iiCabangData->cabang_id][$iiCabangData->id][$fieldsGud] = isset($iiCabangData->$fieldsGud) ? $iiCabangData->$fieldsGud : "";
            }
            $allGudangMember[$iiCabangData->cabang_id][] = $iiCabangData->id;
        }


        // $gg->addFilter("cabang_id='-1'");
        $tempGG = $gg->lookUpAll()->result();
        // arrPrint($tempGG);
        $gudangExtend = array();
        foreach ($tempGG as $tempGG_0) {
            if ($tempGG_0->id > 0) {
                $allGudangDefault[$tempGG_0->id] = $tempGG_0->id;
                foreach ($selectedGudangFields as $i => $fieldsGud) {

                    $gudangDefaultCabang[$tempGG_0->cabang_id][$tempGG_0->id][$fieldsGud] = isset($tempGG_0->$fieldsGud) ? $tempGG_0->$fieldsGud : "";
                }
                $allGudangMember[$tempGG_0->cabang_id][] = $tempGG_0->id;
            }

        }
        // arrPrintWebs($allGudangMember);
        // arrPrintWebs($gudangExtend);
        // matiHere();
        // $inGid = array_merge($inGid_0,$inGid2);
        if ($cabangID > 0) {
            // $hg->addFilter("cabang_id='$cabangID'");
            // $st->addFilter("cabang_id='$cabangID'");
            //            $st->addFilter("gudang_id='$gudangID'");
            //            if($cabangID == 25){
            //                $st->setFilters(array());
            //                $st->addFilter("jenis='produk rakitan'");
            //                $st->addFilter("jenis_locker='stock'");
            //                $st->addFilter("state='active'");
            //            }
            //            else{


            $st->setFilters(array());
            $st->addFilter("gudang_id in ('" . implode("','", $allGudangDefault) . "')");
            $st->addFilter("jenis='produk'");
            $st->addFilter("jenis_locker='stock'");
            $st->addFilter("state='active'");

            $sthold->setFilters(array());
            $sthold->addFilter("gudang_id in ('" . implode("','", $allGudangDefault) . "')");
            $sthold->addFilter("jenis='produk'");
            $sthold->addFilter("jenis_locker='stock'");
            $sthold->addFilter("state='hold'");
            $sthold->addFilter("jumlah>0");
            $sthold->addFilter("transaksi_id>0");
        }
        else {
            // $inCabId = array("25", "21", "1", "-1","26","27","28","29","30","31");
            // $inGid = array("-250", "-210", "-10", "-1","-260","-270","-280","-290","-300","-310");//ini ditembak dulu sambil nyari cara auto get gudang default
            $st->setFilters(array());
            $st->addFilter("gudang_id in ('" . implode("','", $allGudangDefault) . "')");
            //            $st->addFilter("jenis in ('produk', 'produk rakitan')");
            $st->addFilter("jenis='produk'");
            $st->addFilter("jenis_locker='stock'");
            $st->addFilter("state='active'");

            $sthold->setFilters(array());
            $sthold->addFilter("gudang_id in ('" . implode("','", $allGudangDefault) . "')");
            $sthold->addFilter("jenis='produk'");
            $sthold->addFilter("jenis_locker='stock'");
            $sthold->addFilter("state='hold'");
            $sthold->addFilter("jumlah>0");
            $sthold->addFilter("transaksi_id>0");
        }

        // arrPrint($allGudangDefault);
        // matiHere();
        // $tmp_0s = $com->lookupAll()->result();
        // $com->setFilters(array());
        // $com->addFilter("jenis<>folder");
        // $com->addFilter("id in $listedProds");//untuk test aja
        // $this->db->limit(7);
        $tmp_0s = $com->lookupByKeyword($q)->result();
        // cekHijau($this->db->last_query());
        // matiHEre();
        // cekHere(sizeof($tmp_0s));

        // $hg->addFilter("produk_id in $listedProds");
        $tmp_ss = $hg->lookupAll()->result();
        // cekHitam($this->db->last_query());
        // arrPrint($tmp_ss);
        $tmp_rr = $hgr->lookupAll()->result();
        $tmp_tt = $hgk->lookupAll()->result();
        //        $tmp_1s = $tmp_ss;
        $tmp_1s = array_merge($tmp_ss, $tmp_rr, $tmp_tt);
        //        arrPrint($tmp_1s);
        //        mati_disini();

        //        $st->addFilter("jenis='produk'");
        //        $st->addFilter("jenis_locker='stock'");
        //        $st->addFilter("state='active'");

        // $st->addFilter("produk_id in $listedProds");
        $tmp_2s = $st->lookupAll()->result();
        // showLast_query("biru");
        // arrPrintHijau($tmp_2s);
        // matiHere(__LINE__);
        $tmp_2shold = $sthold->lookupAll()->result();
        // cekHijau($this->db->last_query());
        // arrPrintKuning($tmp_2shold);
        // matiHere(__LINE__);
        //        arrPrint($tmp_2s);
        $im->addFilter("jenis='produk'");
        $tmp_3s = $im->lookupAll()->result();
        // cekHijau($this->db->last_query());
        $cb->addFilter("jenis<>''division");
        $tmp_4s = $cb->lookupAll()->result();


        // cekHijau("$cabangID");
        // matiHere();
        $kolom_0s = array(
            "id",
            "kode",
            "nama",
            "label",
            "jenis",
            "keterangan",
            "satuan",
            "folders_nama",
            "kategori_nama",
            "pic",
            "no_part",
            "berat",
            "lebar",
            "panjang",
            "tinggi",
            "volume",
            "berat_gross",
            "lebar_gross",
            "panjang_gross",
            "tinggi_gross",
            "volume_gross",
        );
        $kolom_1s = array(
            "cabang_id",
            "produk_id",
            "jenis_value",
            "nilai",
        );
        $kolom_2s = array(
            "cabang_id",
            "produk_id",
            "jumlah",
            "gudang_id",
        );
        $kolom_3s = array(
            "parent_id",
            "files",
        );
        $kolom_4s = array(
            "id",
            "nama",
        );

        //region specs produks
        $specs = array();
        foreach ($tmp_0s as $temps) {
            $tempDatas = array();
            foreach ($kolom_0s as $kolom) {
                $$kolom = isset($temps->$kolom) ? $temps->$kolom : "";
                $tempDatas[$kolom] = isset($temps->$kolom) ? $temps->$kolom : "";
            }
            $specs[$id] = $tempDatas;
        }
        //endregion
        // arrPrintWebs($tmp_0s);
        //region hargas produks
        $hargas = array();
        // $cabangIdsflip = array();
        foreach ($tmp_1s as $temps) {
            $tempDatas = array();
            foreach ($kolom_1s as $kolom) {
                $$kolom = $temps->$kolom;
                $tempDatas[$kolom] = (isset($temps->$kolom) || ($temps->$kolom == "")) ? $temps->$kolom : "-";
            }
            // $cabangIdsflip[$cabang_id] =1;
            $hargas[$cabang_id][$produk_id][$jenis_value] = $tempDatas;
        }
        // $cabangIds = array_keys($cabangIdsflip);
        //endregion

        //region stock produks
        $stocks = array();
        foreach ($tmp_2s as $temps) {
            //            arrprint($temps);
            $tempDatas = array();
            foreach ($kolom_2s as $kolom) {
                $$kolom = $temps->$kolom;
                //                if($temps->produk_id == 417){
                //                    cekHere("$produk_id: $jumlah");
                //                }
                $tempDatas[$kolom] = $temps->$kolom;
            }
            $stocks[$cabang_id][$gudang_id][$produk_id] = $tempDatas;
        }

        $stocksHold = array();
        $holdTrace = array();
        $totalHold = array();
        foreach ($tmp_2shold as $temps) {
            // arrPrint($temps);
            $tempDatas = array();
            foreach ($kolom_2s as $kolom) {
                $$kolom = $temps->$kolom;
                $tempDatas[$kolom] = $temps->$kolom;
            }
            // cekMErah($gudang_id);
            $stocksHold[$cabang_id][$gudang_id][$produk_id] = $tempDatas;
            if (!isset($totalHold[$cabang_id][$gudang_id][$produk_id])) {
                $totalHold[$cabang_id][$gudang_id][$produk_id] = 0;
            }
            $totalHold[$cabang_id][$gudang_id][$produk_id] += $temps->jumlah;

            $holdTrace[$cabang_id][$gudang_id][$produk_id] = array();
        }

        // arrPrintKuning($totalHold);
        // matiHEre(__LINE__);
        //endregion

        //region images produks
        $images = array();
        foreach ($tmp_3s as $temps) {
            $tempDatas = array();
            foreach ($kolom_3s as $kolom) {
                $$kolom = $temps->$kolom;
                $tempDatas[$kolom] = $temps->$kolom;
            }
            $images[$parent_id][] = $tempDatas;
        }
        //endregion

        //region cabang
        $cabangs = array();
        foreach ($tmp_4s as $temps) {
            $tempDatas = array();
            foreach ($kolom_4s as $kolom) {
                $$kolom = $temps->$kolom;
                $tempDatas[$kolom] = $temps->$kolom;
            }
            $cabangs[$id] = $nama;
        }
        // if ($cabangID > 0) {
        //     $cabangs = array();
        //     $cabangs[$cabangID] = $cabangNAMA;
        // }
        //endregion
        $pairFifoProduk = array();
        if ($cabangID == CB_ID_PUSAT) {
            $this->db->where_in("cabang_id", $inCabId);
            //            $ff->addFilter("cabang_id='$cabangID'");
            //            $ff->addFilter("unit>0");
            $ff->addFilter("gudang_id in ('" . implode("','", $allGudangDefault) . "')");
            $tmpProdukFifo = $ff->lookupAll()->result();
            //            cekHere($this->db->last_query());
            //            arrPrint($tmpProdukFifo);
            //             $pairFifoProduk = array();
            if (sizeof($tmpProdukFifo) > 0) {
                foreach ($tmpProdukFifo as $tmpProdukFifoSpec) {

                    //                    arrPrintWebs($tmpProdukFifoSpec);
                    //                    if (!isset($pairFifoProduk[$tmpProdukFifoSpec->produk_id][$tmpProdukFifoSpec->hpp])) {
                    //                        $pairFifoProduk[$tmpProdukFifoSpec->produk_id][$tmpProdukFifoSpec->hpp] = 0;
                    //                    }
                    //                    $pairFifoProduk[$tmpProdukFifoSpec->produk_id][$tmpProdukFifoSpec->hpp] += $tmpProdukFifoSpec->unit;

                    // hpp dari fifo sesuai dengan cabang masing-masing... nol ya nol, tidak ada ya tidak ada.
                    if (!isset($pairFifoProduk[$tmpProdukFifoSpec->produk_id][$tmpProdukFifoSpec->cabang_id][$tmpProdukFifoSpec->gudang_id][$tmpProdukFifoSpec->hpp])) {
                        $pairFifoProduk[$tmpProdukFifoSpec->produk_id][$tmpProdukFifoSpec->cabang_id][$tmpProdukFifoSpec->gudang_id][$tmpProdukFifoSpec->hpp] = 0;
                    }
                    $pairFifoProduk[$tmpProdukFifoSpec->produk_id][$tmpProdukFifoSpec->cabang_id][$tmpProdukFifoSpec->gudang_id][$tmpProdukFifoSpec->hpp] += $tmpProdukFifoSpec->unit;
                }
            }
        }

        $ppnFactor = my_ppn_factor();
        // $ppnFactor = 10;
        $pairedResult = array();
        // arrPrint($allGudangMember);
        // matiHere();
        foreach ($specs as $pid => $spec) {
            // arrPrintWebs($spec);

            $image = isset($images[$pid]) ? $images[$pid] : array();

            $dimensi = formatField("number", conv_mm_m($panjang_gross)) . "x" . formatField("number", conv_mm_m($lebar_gross)) . "x" . formatField("number", conv_mm_m($tinggi_gross));

            $spec['dimensi_m'] = $dimensi;
            $spec['images'] = $image;
            $pairedResult[$pid] = $spec;
            $pairedProdukFifoItem = (sizeof($pairFifoProduk) > 0 && isset($pairFifoProduk[$pid])) ? $pairFifoProduk[$pid] : array();

            // $pairedDatas = array();
            $specsData = array();
            // $sumSpecData = array();
            foreach ($cabangs as $cId => $cNama) {
                $gDataID = $allGudangMember[$cId];
                $sumtotalstok = 0;

                if (isset($allGudangMember[$cId])) {
                    foreach ($allGudangMember[$cId] as $gIndex => $gID) {
                        // cekMerah($gID);
                        $hpp = isset($hargas[$cId][$pid]['hpp']['nilai']) ? ($hargas[$cId][$pid]['hpp']['nilai'] * 1) : 0;
                        $hjual = isset($hargas[$cId][$pid]['jual']['nilai']) ? ($hargas[$cId][$pid]['jual']['nilai'] * 1) : 0;
                        $hjualnppn = isset($hargas[$cId][$pid]['jual_nppn']['nilai']) ? ($hargas[$cId][$pid]['jual_nppn']['nilai'] * 1) : 0;
                        $stok = isset($stocks[$cId][$gID][$pid]['jumlah']) ? ($stocks[$cId][$gID][$pid]['jumlah'] * 1) : 0;
                        $stokHold = isset($totalHold[$cId][$gID][$pid]) ? ($totalHold[$cId][$gID][$pid] * 1) : 0;
                        $harga_ppn_persen = (($hjualnppn - $hjual) / $hjual) * 100;

                        // mati_disini("$hjualnppn - $hjual " . $ppn_persen);

                        if ($cabangID == CB_ID_PUSAT) {
                            $hasil = "";
                            if (isset($pairedProdukFifoItem[$cId][$gID]) && sizeof($pairedProdukFifoItem[$cId][$gID]) > 0) {
                                foreach ($pairedProdukFifoItem[$cId][$gID] as $hpp => $qty) {
                                    if ($hasil == "") {
                                        $hasil = $qty . "x" . formatField("hpp", $hpp);
                                    }
                                    else {
                                        $hasil = $hasil . "<br>" . $qty . "x" . formatField("hpp", $hpp);
                                    }
                                }
                            }
                            $kode = $spec['kode'];
                            $nama = $spec['nama'];
                            if ($hasil != NULL) {
                                $hasil = "<a href='javascript:void(0)' onclick=\"showModal('" . base_url() . "Katalog/viewFifo/produk/$pid/$cId','DAFTAR FIFO PRODUK $kode $nama')\">" . $hasil . "</a>";
                                $specsData["hpp"] = $hasil;

                            }
                            else {
                                $specsData["hpp"] = formatField("hpp", $hpp);
                            }
                        }

                        /*
                         * ini dimatiin karena harga  gak muncul karena baca dari modul diskon
                         */
                        //                        $specsData["jual"] = (($cabangID == $cId) || ($cabangID == CB_ID_PUSAT)) ? formatField("jual", $hjual) : "-";
                        //                        $specsData["jualnppn"] = (($cabangID == $cId) || ($cabangID == CB_ID_PUSAT)) ? formatField("jualnppn", $hjualnppn) : "";
                        //                        if (($cabangID == $cId) || ($cabangID == CB_ID_PUSAT)) {
                        //                            if ($hjual == 0) {
                        //                                $specsData["jual"] = "<span class='meta'>harga jual belum diseting.<br>Silahkan hubungi admin.</span>";
                        //                            }
                        //                            else {
                        //                                $specsData["jual"] = formatField("jual", $hjual);
                        //                            }
                        //                        }
                        //                        else {
                        //                            $specsData["jual"] = "-";
                        //                        }
                        #-------------------#

                        //                $specsData["stok"] = formatField("stok", $stok);
                        //                $specsData["jual"] = (($cabangID == $cId) || ($cabangID == CB_ID_PUSAT)) ? $hjual : "-";
                        //                $specsData["jualnppn"] = (($cabangID == $cId) || ($cabangID == CB_ID_PUSAT)) ? $hjualnppn : "";

                        /* ------------------------------------------------------
                         * klik to show detile intransit
                         * ------------------------------------------------------*/
                        // $val_kirim_netto_l = "<a href='$link_detile'  onclick=\"return hs.htmlExpand(this, { outlineType: 'rounded-white',wrapperClassName: 'draggable-header', objectType: 'ajax',onReady: function(){console.log('ok')} } )\" class='text-link'>$val_kirim_netto_f</a>";
                        $stokHold_link = $stokHold;
                        $ling_instransit = base_url() . get_class($this) . "/viewProdukInstransit?id=$pid";
                        if ($stokHold > 0) {
                            // $stokHold_link = "<a href='javascript:void(0);' onclick=\"return hs.htmlExpand(this, { objectType: 'ajax'} );\">$stokHold</a>";
                            // $stokHold_link = "<a href='$ling_instransit' onclick=\"return hs.htmlExpand(this, { objectType: 'ajax', headingText: '$nama'});\">$stokHold</a>";
                            $stokHold_link = "<a href='$ling_instransit' onclick=\"return hs.htmlExpand(this, { outlineType: 'rounded-white',headingText: '$nama',wrapperClassName: 'draggable-header', objectType: 'ajax',onReady: function(){console.log('ok')} } )\">$stokHold</a>";
                        }


                        $specsData["in transit"] = $stokHold_link;
                        $specsData["stok avail"] = $stok;
                        $specsData["stok total"] = $stok + $stokHold;
                        $jmlStok = $stok + $stokHold;

                        if (!isset($sumSpecData[$cId][$pid])) {
                            $sumSpecData[$cId][$pid] = 0;
                        }
                        $sumSpecData[$cId][$pid] += $jmlStok;
                        // $sumtotalstok +=$jmlStok;

                        $pairedDatas[$cId][$gID][$pid] = $specsData;
                    }
                }
                // $sumSpecData[$cId][$pid]=$sumtotalstok;


            }
        }
        // arrPrint($sumSpecData);
        // arrPrint($gudangDefaultCabang);
        //         matiHEre();
        /* ---------------------------------------------------------------------------------------------------------
         * peringatan harga jualnpp tidak sesuai ppnFactor yg berlaku saat ini, perlu sync harga oleh yg berwenang
         * ---------------------------------------------------------------------------------------------------------*/
        $warning = "";
        if ($harga_ppn_persen != $ppnFactor) {
            $alerts = array(
                "html" => "test",
            );
            // $warning = swalAlert($alerts);
        }
        // ----------------------------------------------------------------------------------------------------------

        //ganti headerFields
        $headerFields = array(
            "rek_id" => "kode",
            //            "kode" => "product code",
            //            "extern_nama" => "item names",
            //
            //
        );
        if (isset($balConfig['pairedModel']['viewedColumns']) && sizeof($balConfig['pairedModel']['viewedColumns'])) {
            foreach ($balConfig['pairedModel']['viewedColumns'] as $k => $v) {
                $headerFields[$k] = $v;
            }
        }
        $headerFields["extern_nama"] = "item names";
        $headerQtyFields = array(
            "qty_" . $defPosition => "balance (QTY)",
        );
        $headerValueFields = array(
            $defPosition => "balance (IDR)",
        );
        if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
            $headerFields = $headerFields + $headerQtyFields;
        }
        if (isset($balConfig['showValue']) && $balConfig['showValue'] == true) {
            $headerFields = $headerFields + $headerValueFields;
        }

        $btn_excel = "";
        // if($_SERVER['REMOTE_ADDR'] == MGK_LIVE){
        $cb_nama = my_cabang_nama();
        $ssss = array(
            "mdl"       => "MdlProduk2",
            "fifo"      => "MdlFifoProdukJadi",
            "cabang_id" => my_cabang_id(),
        );
        $fff = blobEncode($ssss);
        $link_excel = base_url() . "ExcelWriter/katalogproduk/$fff";
        // $btn_excel = "<span class=\"input-group-addon bg-danger\"><a href=\"javascript:void(0)\" onclick=\"location.href='$link_excel'\"><i class='fa fa-download'></i> download file katalog</a></span>";
        $btn_excel = "<span class=\"input-group-addon text-uppercase\" style=\"background: bisque;\"><a href=\"javascript:void(0)\" onclick=\"btn_result('$link_excel')\"><i class='fa fa-download'></i> download file katalog $cb_nama</a></span>";
        // }
        // arrPrint($sumSpecData);
        $mb = New MobileDetect();
        $isMob = $mb->isMobile();
        $title = "Katalog ";
        $subTitle = "Produk termasuk produk rakitan";
        if ($q != "") {
            $subTitle .= " matched '$q'";
        }
        $data = array(
            "mode"          => "KatalogGudang",
            "title"         => "$title",
            "subTitle"      => $subTitle . $warning,
            // "cabang" => $cabangs,
            "cabang"        => $allCabang,
            "dataParents"   => $pairedResult,
            "dataChilds"    => $pairedDatas,
            "gudangData"    => $gudangDefaultCabang,
            // //            "headerFields" => $balConfig['viewedColumns'],
            // "headerFields" => $headerFields,
            // "thisPage"     => $thisPage,
            "isi_modal"     => "",
            "q"             => $q,
            "produkFields"  => $kolom_0s,
            "add_btn"       => $btn_excel,
            "sumStokCabang" => $sumSpecData,
            "isMob"         => $isMob,
            //            "inspectTarget_mutasi" => base_url() . "Ledger/viewMoves_l2/$relName/$rekName/",
        );

        $this->load->view("katalog", $data);
    }

    public function viewProdukInstransit()
    {
        // arrPrintHijau($_GET);
        $produk_id = $_GET['id'];

        $tbl_1 = "transaksi";
        $tbl_2 = "stock_locker";
        $condites = array(
            "$tbl_2.produk_id" => $produk_id,
            "$tbl_2.jumlah>"   => 0,
            "$tbl_2.state"     => "hold",
        );
        $this->db->from("$tbl_2");
        $this->db->join($tbl_1, "$tbl_2.transaksi_id = $tbl_1.id");
        $this->db->where($condites);
        $queries = $this->db->get()->result_array();
        // showLast_query("biru");

        // arrPrintHijau($queries);
        $headers = array(
            "fulldate"       => array(
                "label" => "tanggal",
                "format" => "formatField_he_format",
            ),
            "nomer"          => array(
                "label" => "nomer",
                "format" => "formatField_he_format",
                "format_jenis" => "5822",
                "format_modul" => "penjualan",
            ),
            "jumlah"         => array(
                "label" => "jumlah",
            ),
            "cabang2_nama" => array(
                "label" => "cab. tujuan",
            ),
            // "suppliers_nama" => array(
            //     "label" => "supplier",
            // ),
            "customers_nama" => array(
                "label" => "customer",
            ),
            "salesman_nama"  => array(
                "label" => "salesman",
            ),
            "oleh_nama"      => array(
                "label" => "PIC",
            ),
        );

        $hd = "";
        $hd .= "<tr class='bg-primary text-capitalize'>";
        $hd .= "<th>No</th>";
        foreach ($headers as $kolom => $params) {
            $label = $params["label"];
            $hd .= "<th>$label</th>";
        }
        $hd .= "</tr>";

        $bd = "";
        $no = 0;
        foreach ($queries as $query) {
            $no++;
            $bd .= "<tr>";
            $bd .= "<td>$no</td>";
            foreach ($headers as $kolom => $params) {
                $nilai = $query[$kolom];
                $format_jenis = isset($params['format_jenis']) ? $params['format_jenis'] : "";
                $format_modul = isset($params['format_modul']) ? base_url() . $params['format_modul']. "/" : "";
                $nilai_f = isset($params['format']) ? $params['format']($kolom,$nilai, $format_jenis, $format_modul) : $nilai;

                $bd .= "<td>$nilai_f</td>";
            }
            $bd .= "</tr>";
        }

        $var = "";
        $var .= "<style>
            .highslide-header {
                height: 32px !important;
            }
            .tbl-hslide td {
                font-size: 10px;
            }
            table.table-bordered{
                margin-top: unset;
            }
        </style>";
        $var .= "<div>Transaksi yang mengunci stok</div>";
        $var .= "<table class='table table-bordered tbl-hslide'>";
        $var .= $hd;
        $var .= $bd;
        $var .= "</table>";
        echo $var;
    }

    public function modal()
    {
        $ly = new Layout();
        $datas = blobDecode($this->uri->segment(3));
        // arrPrint($datas);
        $judul = $datas['title'];
        $action_form = isset($datas['action_form']) ? $datas['action_form'] : "";
        $att_form = isset($datas['attribute_form']) ? $datas['attribute_form'] : "";


        $bd = "";
        //region prepare image ke carousel
        $arrImages = array();
        if (sizeof($datas['body']) > 0) {
            foreach ($datas['body'] as $file) {
                $pic = $file;
                $arrImages[] = $pic;
            }
        }
        else {
            $arrImages[] = img_blank();
        }
        //endregion

        // arrPrint($arrImages);
        $bd .= $ly->carousel($arrImages);
        if (isset($datas['caption'])) {
            $bd .= "<div class='text-center panel panel-body margin-bottom-none margin-top-10' >";
            $bd .= $datas['caption'];
            $bd .= "</div>";
        }

        $footer = form_button("close", "Close", "class='btn pull-left' data-dismiss='modal'");
        if (isset($datas['action_form'])) {
            $footer .= form_submit("Submit", "Submit", "class='btn pull-right btn-primary'");
        }

        $data = array(
            "mode"    => "Modal",
            "heading" => $judul,
            "forms"   => $bd,
            "actios"  => $action_form,
            "att"     => $att_form,
            "footer"  => $footer,
        );

        $this->load->view("katalog", $data);
    }

    public function modalProduk()
    {
        // arrPrint($_REQUEST);
        $datas = blobDecode($this->uri->segment(3));
        $ly = new Layout();
        $this->load->helper('he_angka');
        $this->load->config('heWebs');
        $cKatalogs = $this->config->item('katalog');
        $koloms = $cKatalogs['modal']['fields'];
        foreach ($koloms as $kolom => $attrs) {
            $$kolom = $datas[$kolom];
        }

        $kodeNama = "$kode $nama";

        //region caption config
        $lg = formatField("number", conv_mm_m($lebar_gross));
        $pg = formatField("number", conv_mm_m($panjang_gross));
        $tg = formatField("number", conv_mm_m($tinggi_gross));
        $dimensi = "$pg x $lg x $tg";

        $captions = array(
            "dimension (p.l.t)(M)"           => $dimensi,
            // $koloms['volume_gross']['label'] => isset($koloms['volume_gross']['format']) ? formatField($koloms['volume_gross']['format'], $volume_gross) : "",
            $koloms['volume_gross']['label'] => isset($koloms['volume_gross']['format']) ? formatField("number", conv_mmc_mc($volume_gross)) : "",
            // $koloms['berat_gross']['label']  => isset($koloms['berat_gross']['format']) ? formatField($koloms['berat_gross']['format'], $berat_gross) : "",
            $koloms['berat_gross']['label']  => isset($koloms['berat_gross']['format']) ? formatField("number", conv_g_kg($berat_gross)) : "",
        );
        //endregion

        $bd = "";
        //region prepare image ke carousel
        $arrImages = array();
        if (sizeof($datas['images']) > 0) {
            foreach ($datas['images'] as $file) {
                $pic = $file['files'];
                $arrImages[] = $pic;
            }
        }
        else {
            $arrImages[] = img_blank();
        }
        //endregion

        // arrPrint($arrImages);
        $bd .= $ly->carousel($arrImages);

        //region penampil caption
        $bd .= "<div class='margin-top-10 alert alert-warning text-center'>";
        $hasil = "";
        foreach ($captions as $lable => $val) {
            $var = "<span class='text-uppercase text-grey'>$lable</span>: $val";
            if ($hasil == "") {
                $hasil = "$var";
            }
            else {
                $hasil = "$hasil, $var";
            }
        }
        $bd .= "$hasil";
        $bd .= "</div>";
        //endregion

        $footer = form_button("close", "Close", "class='btn pull-left' data-dismiss='modal'");
        // $footer .= form_submit("simpan", "RESET PASSWORD", "class='btn btn-danger'");
        $data = array(
            "mode"    => "Modal",
            "heading" => $kodeNama,
            "forms"   => $bd,
            "footer"  => $footer,
        );
        //endregion


        $this->load->view("katalog", $data);
    }

    public function viewSupplies()
    {
        //        arrPrint($this->uri->segment_array());
        $relName = $this->uri->segment(3);
        $rekName = urldecode($this->uri->segment(4));
        $defPosition = detectRekDefaultPosition($rekName);

        $balConfig = isset($this->config->item('accountBalanceColumns')[$relName]) ? $this->config->item('accountBalanceColumns')[$relName] : array();
        $accountFilters = isset($this->config->item('accountBalanceColumns')[$relName]['viewFilters']) ? $this->config->item('accountBalanceColumns')[$relName]['viewFilters'] : array();


        $q = isset($_GET['q']) && strlen($_GET['q']) ? $_GET['q'] : "";
        $sortBy = isset($_GET['sortBy']) && strlen($_GET['sortBy']) ? $_GET['sortBy'] : "extern_nama";
        $sortMode = isset($_GET['sortMode']) && strlen($_GET['sortMode']) ? $_GET['sortMode'] : "ASC";


        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];
        $gudangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['gudang_id'];


        $thisPage = base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "?o=$cabangID";
        $thisURL = base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "?q=$q&o=$cabangID";


        $this->load->helper("he_mass_table");
        $this->load->model("Mdls/MdlSupplies");
        $this->load->model("Mdls/MdlHargaProduk");
        $this->load->model("Mdls/MdlLockerStockSupplies");
        $this->load->model("Mdls/MdlImages");
        $this->load->model("Mdls/MdlCabang");
        $com = new MdlSupplies();
        $hg = new MdlHargaProduk();
        $st = new MdlLockerStockSupplies();
        $im = new MdlImages();
        $cb = new MdlCabang();
        // $com->addFilter("cabang_id='$cabangID'");


        $tmp_0s = $com->lookupByKeyword($q)->result();
        // cekHijau($this->db->last_query());

        // $hg->addFilter("cabang_id='$cabangID'");
        $hg->addFilter("jenis='supplies'");
        $tmp_1s = $hg->lookupAll()->result();
        // cekMerah($this->db->last_query());

        // $st->addFilter("cabang_id='$cabangID'");
        // $st->addFilter("gudang_id='$gudangID'");
        $st->addFilter("jenis='supplies'");
        $st->addFilter("jenis_locker='stock'");
        $st->addFilter("state='active'");
        $tmp_2s = $st->lookupAll()->result();
        // cekHitam($this->db->last_query());
        $im->addFilter("jenis='supplies'");
        $tmp_3s = $im->lookupAll()->result();
        $tmp_4s = $cb->lookupAll()->result();

        // cekHijau($this->db->last_query());
        // cekHijau("$cabangID");
        // $kolom_0s = array_keys($com->getListedFields());
        $kolom_0s = array(
            "id",
            "folders",
            "nama",
            "keterangan",
            "satuan",
            "jenis",
        );
        // mati_disini(arrPrint($kolom_0s));
        $kolom_1s = array(
            "cabang_id",
            "produk_id",
            "jenis_value",
            "nilai",
        );
        // $kolom_2s = array_keys($st->getListedFields());
        $kolom_2s = array(
            "cabang_id",
            "produk_id",
            "jumlah",
            "gudang_id",
        );
        // mati_disini(arrPrint($tmp_2s));
        $kolom_3s = array(
            "parent_id",
            "files",
        );
        $kolom_4s = array(
            "id",
            "nama",
        );
        //region specs produks
        $specs = array();
        foreach ($tmp_0s as $temps) {
            $tempDatas = array();
            foreach ($kolom_0s as $kolom) {
                $$kolom = $temps->$kolom;
                $tempDatas[$kolom] = $temps->$kolom;
            }
            $specs[$id] = $tempDatas;
        }
        //endregion

        //region hargas produks
        $hargas = array();
        foreach ($tmp_1s as $temps) {
            $tempDatas = array();
            foreach ($kolom_1s as $kolom) {
                $$kolom = $temps->$kolom;
                $tempDatas[$kolom] = $temps->$kolom;
            }
            // $hargas[$produk_id][$jenis_value] = $tempDatas;
            $hargas[$cabang_id][$produk_id][$jenis_value] = $tempDatas;
        }
        //endregion

        //region stock produks
        $stocks = array();
        foreach ($tmp_2s as $temps) {
            $tempDatas = array();
            foreach ($kolom_2s as $kolom) {
                $$kolom = $temps->$kolom;
                $tempDatas[$kolom] = $temps->$kolom;
            }
            $stocks[$cabang_id][$produk_id] = $tempDatas;
        }
        //endregion

        //region images produks
        $images = array();
        foreach ($tmp_3s as $temps) {
            $tempDatas = array();
            foreach ($kolom_3s as $kolom) {
                $$kolom = $temps->$kolom;
                $tempDatas[$kolom] = $temps->$kolom;
            }
            $images[$parent_id][] = $tempDatas;
        }
        //endregion

        //region cabang
        $cabangs = array();
        foreach ($tmp_4s as $temps) {
            $tempDatas = array();
            foreach ($kolom_4s as $kolom) {
                $$kolom = $temps->$kolom;
                $tempDatas[$kolom] = $temps->$kolom;
            }
            $cabangs[$id] = $nama;
        }
        //endregion


        $pairedResult = array();
        // if (isset($balConfig['pairedModel']) && sizeof($balConfig['pairedModel'])) {
        //
        // }
        foreach ($specs as $pid => $spec) {
            $hpp = isset($hargas[$pid]['hpp']['nilai']) ? ($hargas[$pid]['hpp']['nilai'] * 1) : 0;
            $hjual = isset($hargas[$pid]['jual']['nilai']) ? ($hargas[$pid]['jual']['nilai'] * 1) : 0;
            $hjualnppn = isset($hargas[$pid]['jual_nppn']['nilai']) ? ($hargas[$pid]['jual_nppn']['nilai'] * 1) : 0;
            $stok = isset($stocks[$pid]['jumlah']) ? ($stocks[$pid]['jumlah'] * 1) : 0;
            $image = isset($images[$pid]) ? $images[$pid] : array();

            $spec['hpp'] = $hpp;
            $spec['jual'] = $hjual;
            $spec['jualnppn'] = $hjualnppn;
            $spec['stok'] = $stok;
            $spec['images'] = $image;
            $pairedResult[$pid] = $spec;

            $specsData = array();
            foreach ($cabangs as $cId => $cNama) {
                $hpp = isset($hargas[$cId][$pid]['hpp']['nilai']) ? ($hargas[$cId][$pid]['hpp']['nilai'] * 1) : 0;
                $hjual = isset($hargas[$cId][$pid]['jual']['nilai']) ? ($hargas[$cId][$pid]['jual']['nilai'] * 1) : 0;
                $hjualnppn = isset($hargas[$cId][$pid]['jual_nppn']['nilai']) ? ($hargas[$cId][$pid]['jual_nppn']['nilai'] * 1) : 0;

                $stok = isset($stocks[$cId][$pid]['jumlah']) ? ($stocks[$cId][$pid]['jumlah'] * 1) : 0;

                if ($cabangID == CB_ID_PUSAT) {
                    $specsData["hpp"] = $hpp;
                }
                // $specsData["jual"] = (($cabangID == $cId) || ($cabangID == CB_ID_PUSAT)) ? $hjual : "-";
                // $specsData["jualnppn"] = (($cabangID == $cId) || ($cabangID == CB_ID_PUSAT)) ? $hjualnppn : "";
                // $specsData["stok"] = (($cabangID == $cId) || ($cabangID == CB_ID_PUSAT)) ? $stok : "";
                $specsData["stok"] = $stok;

                $pairedDatas[$cId][$pid] = $specsData;
            }
        }

        // arrPrint($pairedResult);
        $items = array();

        //ganti headerFields
        $headerFields = array(
            "rek_id" => "kode",
            //            "kode" => "product code",
            //            "extern_nama" => "item names",
            //
            //
        );
        if (isset($balConfig['pairedModel']['viewedColumns']) && sizeof($balConfig['pairedModel']['viewedColumns'])) {
            foreach ($balConfig['pairedModel']['viewedColumns'] as $k => $v) {
                $headerFields[$k] = $v;
            }
        }
        $headerFields["extern_nama"] = "item names";
        $headerQtyFields = array(
            "qty_" . $defPosition => "balance (QTY)",
        );
        $headerValueFields = array(
            $defPosition => "balance (IDR)",
        );
        if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
            $headerFields = $headerFields + $headerQtyFields;
        }
        if (isset($balConfig['showValue']) && $balConfig['showValue'] == true) {
            $headerFields = $headerFields + $headerValueFields;
        }
        // arrPrintWebs($pairedDatas);
        // mati_disini();
        $title = "Katalog ";
        $subTitle = "Supplies ";
        if ($q != "") {
            $subTitle .= " matched '$q'";
        }

        $data = array(
            "mode"         => "KatalogSupplies",
            "title"        => "$title",
            "subTitle"     => $subTitle,
            "cabang"       => $cabangs,
            "dataParents"  => $pairedResult,
            "dataChilds"   => $pairedDatas,
            // //            "headerFields" => $balConfig['viewedColumns'],
            // "headerFields" => $headerFields,
            // "thisPage"     => $thisPage,
            "isi_modal"    => "",
            "q"            => $q,
            "produkFields" => $kolom_0s,
            //            "inspectTarget_mutasi" => base_url() . "Ledger/viewMoves_l2/$relName/$rekName/",
        );
        //endregion


        $this->load->view("katalog", $data);


    }

    public function viewProdukAktif()
    {
        /* ------------------------------------------------------------------
         * menu di matikan dr config heMenu
         * ------------------------------------------------------------------*/
        //        arrPrint($this->uri->segment_array());
        $relName = $this->uri->segment(3);
        $rekName = urldecode($this->uri->segment(4));
        $defPosition = detectRekDefaultPosition($rekName);

        $balConfig = isset($this->config->item('accountBalanceColumns')[$relName]) ? $this->config->item('accountBalanceColumns')[$relName] : array();
        $accountFilters = isset($this->config->item('accountBalanceColumns')[$relName]['viewFilters']) ? $this->config->item('accountBalanceColumns')[$relName]['viewFilters'] : array();


        $q = isset($_GET['q']) && strlen($_GET['q']) ? $_GET['q'] : "";
        $sortBy = isset($_GET['sortBy']) && strlen($_GET['sortBy']) ? $_GET['sortBy'] : "extern_nama";
        $sortMode = isset($_GET['sortMode']) && strlen($_GET['sortMode']) ? $_GET['sortMode'] : "ASC";


        $cabangNAMA = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_nama'];
        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];
        $gudangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['gudang_id'];


        $thisPage = base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "?o=$cabangID";
        $thisURL = base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "?q=$q&o=$cabangID";


        $this->load->helper("he_mass_table");
        $this->load->helper("he_angka");
        $this->load->model("Mdls/MdlProduk2");
        $this->load->model("Mdls/MdlHargaProduk2");
        $this->load->model("Mdls/MdlLockerStock");
        $this->load->model("Mdls/MdlImages");
        $this->load->model("Mdls/MdlCabang");
        $this->load->model("MdlTransaksi");
        $com = new MdlProduk2();
        $hg = new MdlHargaProduk2();
        $st = new MdlLockerStock();
        $im = new MdlImages();
        $cb = new MdlCabang();
        $tr = new MdlTransaksi();
        // $com->addFilter("cabang_id='$cabangID'");
        // region outstanding
        $srcOuts = $tr->lookupOutstandingStocks();
        // cekLime($this->db->last_query());
        // showLast_query("lime", "okok");
        $outstandingCabang = $srcOuts["byCabang"];
        // arrPrint($outstandingCabang);
        // endregion outstanding
        // mati_disini(__LINE__ . __METHOD__);

        if ($cabangID > 0) {
            // $hg->addFilter("cabang_id='$cabangID'");
            // $st->addFilter("cabang_id='$cabangID'");
            // $st->addFilter("gudang_id='$gudangID'");
        }

        // $tmp_0s = $com->lookupAll()->result();
        // $com->setFilters(array());
        // $com->addFilter("jenis<>folder");
        $tmp_0s = $com->lookupByKeyword($q)->result();
        // cekHijau($this->db->last_query());

        $tmp_1s = $hg->lookupAll()->result();
        // cekHitam($this->db->last_query());

        $st->addFilter("jenis='produk'");
        $st->addFilter("jenis_locker='stock'");
        $st->addFilter("state='active'");
        $st->addFilter("gudang_id<'0'");
        $tmp_2s = $st->lookupAll()->result();
        // showLast_query("merah");
        $im->addFilter("jenis='produk'");
        $tmp_3s = $im->lookupAll()->result();
        $tmp_4s = $cb->lookupAll()->result();

        // cekHijau($this->db->last_query());
        // cekHijau("$cabangID");
        $kolom_0s = array(
            "id",
            "kode",
            "nama",
            "keterangan",
            "satuan",
            "folders_nama",
            "pic",
            "no_part",
            "berat",
            "lebar",
            "panjang",
            "tinggi",
            "volume",
            "berat_gross",
            "lebar_gross",
            "panjang_gross",
            "tinggi_gross",
            "volume_gross",
        );
        $kolom_1s = array(
            "cabang_id",
            "produk_id",
            "jenis_value",
            "nilai",
        );
        $kolom_2s = array(
            "cabang_id",
            "produk_id",
            "jumlah",
            "gudang_id",
        );
        $kolom_3s = array(
            "parent_id",
            "files",
        );
        $kolom_4s = array(
            "id",
            "nama",
        );
        //region specs produks
        $specs = array();
        foreach ($tmp_0s as $temps) {
            $tempDatas = array();
            foreach ($kolom_0s as $kolom) {
                $$kolom = $temps->$kolom;
                $tempDatas[$kolom] = $temps->$kolom;
            }
            $specs[$id] = $tempDatas;
        }
        //endregion
        //region hargas produks
        $hargas = array();
        // $cabangIdsflip = array();
        foreach ($tmp_1s as $temps) {
            $tempDatas = array();
            foreach ($kolom_1s as $kolom) {
                $$kolom = $temps->$kolom;
                $tempDatas[$kolom] = $temps->$kolom;
            }
            // $cabangIdsflip[$cabang_id] =1;
            $hargas[$cabang_id][$produk_id][$jenis_value] = $tempDatas;
        }
        // $cabangIds = array_keys($cabangIdsflip);
        //endregion
        //region stock produks
        $stocks = array();
        foreach ($tmp_2s as $temps) {
            $tempDatas = array();
            foreach ($kolom_2s as $kolom) {
                $$kolom = $temps->$kolom;
                $tempDatas[$kolom] = $temps->$kolom;
            }
            $stocks[$cabang_id][$produk_id] = $tempDatas;
        }
        // arrPrint($stocks);
        //endregion
        //region images produks
        $images = array();
        foreach ($tmp_3s as $temps) {
            $tempDatas = array();
            foreach ($kolom_3s as $kolom) {
                $$kolom = $temps->$kolom;
                $tempDatas[$kolom] = $temps->$kolom;
            }
            $images[$parent_id][] = $tempDatas;
        }
        //endregion
        //region cabang
        $cabangs = array();
        foreach ($tmp_4s as $temps) {
            $tempDatas = array();
            foreach ($kolom_4s as $kolom) {
                $$kolom = $temps->$kolom;
                $tempDatas[$kolom] = $temps->$kolom;
            }
            $cabangs[$id] = $nama;
        }
        // if ($cabangID > 0) {
        //     $cabangs = array();
        //     $cabangs[$cabangID] = $cabangNAMA;
        // }
        //endregion

        // arrPrint($tmp_0s);
        // arrPrint($tmp_1s);
        // arrPrint($tmp_2s);
        // arrPrint($stocks);
        // arrPrint($specs);
        // arrPrint($hargas);
        // arrPrint($tmp_4s);
        // arrPrint($cabangIds);
        // arrPrint($tmp_3s);
        // arrPrint($images);
        // mati_disini();

        $pairedResult = array();
        foreach ($specs as $pid => $spec) {

            // $hpp = isset($hargas[$pid]['hpp']['nilai']) ? ($hargas[$pid]['hpp']['nilai'] * 1) : 0;
            // $hjual = isset($hargas[$pid]['jual']['nilai']) ? ($hargas[$pid]['jual']['nilai'] * 1) : 0;
            // $cbId = isset($hargas[$pid]['cabang_id']) ? ($stocks[$pid]['cabang_id']) : 0;
            // $hjualnppn = isset($hargas[$pid]['jual_nppn']['nilai']) ? ($hargas[$pid]['jual_nppn']['nilai'] * 1) : 0;
            //
            // $stok = isset($stocks[$pid]['jumlah']) ? ($stocks[$pid]['jumlah'] * 1) : 0;
            $image = isset($images[$pid]) ? $images[$pid] : array();

            // $spec[$cbId]['hpp'] = $hpp;
            // $spec[$cbId]['jual'] = $hjual;
            // $spec[$cbId]['jualnppn'] = $hjualnppn;
            // $spec[$cbId]['stok'] = $stok;
            $dimensi = formatField("number", conv_mm_m($panjang_gross)) . "x" . formatField("number", conv_mm_m($lebar_gross)) . "x" . formatField("number", conv_mm_m($tinggi_gross));

            $spec['dimensi_m'] = $dimensi;
            $spec['images'] = $image;
            $pairedResult[$pid] = $spec;


            // $pairedDatas = array();
            $specsData = array();
            foreach ($cabangs as $cId => $cNama) {
                $hpp = isset($hargas[$cId][$pid]['hpp']['nilai']) ? ($hargas[$cId][$pid]['hpp']['nilai'] * 1) : 0;
                $hjual = isset($hargas[$cId][$pid]['jual']['nilai']) ? ($hargas[$cId][$pid]['jual']['nilai'] * 1) : 0;
                $hjualnppn = isset($hargas[$cId][$pid]['jual_nppn']['nilai']) ? ($hargas[$cId][$pid]['jual_nppn']['nilai'] * 1) : 0;

                $stok = isset($stocks[$cId][$pid]['jumlah']) ? ($stocks[$cId][$pid]['jumlah'] * 1) : 0;
                $stok_outstanding = isset($outstandingCabang[$cId][$pid]['valid_qty']) ? $outstandingCabang[$cId][$pid]['valid_qty'] : 0;
                $stok_aktif = ($stok > $stok_outstanding) ? ($stok - $stok_outstanding) : 0;

                if ($cabangID == CB_ID_PUSAT) {
                    $specsData["hpp"] = $hpp;
                }
                $specsData["jual"] = (($cabangID == $cId) || ($cabangID == CB_ID_PUSAT)) ? $hjual : "-";
                $specsData["jualnppn"] = (($cabangID == $cId) || ($cabangID == CB_ID_PUSAT)) ? $hjualnppn : "";
                // $specsData["stok"] = (($cabangID == $cId) || ($cabangID == CB_ID_PUSAT)) ? $stok : "";
                $specsData["stok"] = $stok;
                $specsData["outstanding"] = $stok_outstanding;
                $specsData["stok_aktif"] = $stok_aktif;

                $pairedDatas[$cId][$pid] = $specsData;
            }
        }

        // arrPrint($pairedDatas);
        // arrPrint($pairedResult);

        //ganti headerFields
        $headerFields = array(
            "rek_id" => "kode",
            //            "kode" => "product code",
            //            "extern_nama" => "item names",
            //
            //
        );
        if (isset($balConfig['pairedModel']['viewedColumns']) && sizeof($balConfig['pairedModel']['viewedColumns'])) {
            foreach ($balConfig['pairedModel']['viewedColumns'] as $k => $v) {
                $headerFields[$k] = $v;
            }
        }
        $headerFields["extern_nama"] = "item names";
        $headerQtyFields = array(
            "qty_" . $defPosition => "balance (QTY)",
        );
        $headerValueFields = array(
            $defPosition => "balance (IDR)",
        );
        if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
            $headerFields = $headerFields + $headerQtyFields;
        }
        if (isset($balConfig['showValue']) && $balConfig['showValue'] == true) {
            $headerFields = $headerFields + $headerValueFields;
        }


        $title = "Katalog ";
        $subTitle = "Produk ";
        if ($q != "") {
            $subTitle .= " matched '$q'";
        }

        $data = array(
            "mode"         => "KatalogAktif",
            "title"        => "$title",
            "subTitle"     => $subTitle,
            "cabang"       => $cabangs,
            "dataParents"  => $pairedResult,
            "dataChilds"   => $pairedDatas,
            // //            "headerFields" => $balConfig['viewedColumns'],
            // "headerFields" => $headerFields,
            // "thisPage"     => $thisPage,
            "isi_modal"    => "",
            "q"            => $q,
            "produkFields" => $kolom_0s,
            //            "inspectTarget_mutasi" => base_url() . "Ledger/viewMoves_l2/$relName/$rekName/",
        );
        //endregion


        $this->load->view("katalog", $data);


    }

    public function view()
    {
        // cekMerah();
        $membership = $this->session->login['membership'];
        $cabang_id = $this->cabang_id;
        $gudang_id = $this->gudang_id;
        $keyWord = isset($_GET['q']) ? $_GET['q'] : "";
        $this->load->model("Mdls/MdlProduk");
        $this->load->model("Mdls/MdlFolderProduk");
        $this->load->model("Mdls/MdlHargaProduk");
        $this->load->model("Mdls/MdlHargaProdukPerSupplier");
        $this->load->model("Mdls/MdlSupplier");
        $this->load->model("Mdls/MdlLockerStock");
        $this->load->model("Mdls/MdlProdukKategori");
        $pr = new MdlProduk();
        $fo = new MdlFolderProduk();
        $hr = new MdlHargaProduk();
        $ls = new MdlLockerStock();
        $ps = new MdlHargaProdukPerSupplier();
        $su = new MdlSupplier();
        $pcat = new MdlProdukKategori();
        // mati_disini();

        $srcProduks = $pr->lookupByKeyword($keyWord)->result();
        //         showLast_query("lime");

        $srcFolder = $fo->lookupAll()->result();
        $srcKategory = $pcat->lookupAll()->result();

        $hr->addFilter("jenis_value ='hpp'");
        $srcHarga = $hr->lookupAll()->result();
        // showLast_query("biru");
        $ls->addFilter("cabang_id ='$cabang_id'");
        $ls->addFilter("gudang_id ='$gudang_id'");

        $ls->addFilter("jenis_locker='stock'");
        $ls->addFilter("state='active'");
        $ls->addFilter("jenis='produk'");

        $srcLs = $ls->lookupAll()->result();
        // showLast_query("merah");

        $ps->addFilter("nilai >'0'");
        $srcPs = $ps->lookupAll()->result();
        // $srcPs = $ps->lookupProdukSupplier()->result();
        // showLast_query("Lime");

        $srcSu = $su->lookupAll()->result();

        $listedFields = $pr->getListedFields();
        $fields = $pr->getFields();

        $folderIds = array();
        foreach ($srcFolder as $foItems) {

            $folderIds[$foItems->id] = $foItems->nama;
        }
        foreach ($srcHarga as $item) {
            $hargaPps[$item->produk_id] = $item->nilai;
        }
        foreach ($srcLs as $item) {
            $lStocks[$item->produk_id] = $item->jumlah;
        }
        $produkSuppliers = array();
        foreach ($srcPs as $srcP) {
            $produkSuppliers[$srcP->produk_id][] = $srcP->suppliers_id;
        }
        foreach ($srcSu as $item) {
            $supplierIds[$item->id] = $item->nama;
        }
        $categoryIds = array();
        foreach ($srcKategory as $item) {
            $categoryIds[$item->id] = $item->nama;
        }

        // region attr
        foreach ($fields as $fieldItems) {
            // arrPrintWebs($fieldItems);
            isset($fieldItems['attr']) ? $fieldAttr[$fieldItems['kolom']] = $fieldItems['attr'] : "";
        }
        // endregion

        // arrPrint($srcPs);
        // arrPrint($produkSuppliers);
        // arrPrint($srcLs);
        // arrPrint($srcHarga);
        // arrPrint($folderIds);
        // arrPrint($listedFields);
        // arrPrint($fields);
        // arrPrint($srcProduks);
        // arrPrint($srcFolder);
        // arrPrint($fieldAttr);

        $title = "Product Datas ";
        $subTitle = "Produk ";
        // if ($q != "") {
        //     $subTitle .= " matched '$q'";
        // }
        if (in_array("o_seller", $membership)) {
            $addHeaders = array();
        }
        else {

            $addHeaders = array(
                // "no" => "class='text-center bg-success'",
                "original vendor" => "class='text-center bg-success valign-m'",
                "hpp rupiah/unit" => "class='text-center bg-success'",
                "qty stok"        => "class='text-center bg-success'",
            );
        }

        $bodiFields = "";
        $mainHeaders["no"] = "class='text-center bg-success valign-m'";
        $no = 0;
        foreach ($srcProduks as $srcProduk) {
            $no++;
            // arrPrint($srcProduk);
            $produk_specs["no"]["value"] = $no;
            $produk_specs["no"]["attr"] = "class='text-right'";
            foreach ($listedFields as $kolom => $alias) {
                $$kolom = $srcProduk->$kolom;
                $mainHeaders[$alias] = "class='text-center bg-success valign-m'";
                $kolomValue_0 = $srcProduk->$kolom;
                //                 cekLime("$kolom $kolomValue_0");
                $kolomValue = $kolomValue_0;

                if ($kolom == "folders") {
                    // arrPrintWebs($folderIds);
                    // $kolomValue = $kolomValue_1 = isset($folders[$kolomValue_0]) ? $folders[$kolomValue_0] : "none";
                    $kolomValue = $kolomValue_1 = array_key_exists($kolomValue_0, $folderIds) ? $folderIds[$kolomValue_0] : "none";
                    // cekBiru("$kolom *$kolomValue_0* ** $kolomValue_1");
                }
                //                else {
                //                    $kolomValue = $kolomValue_0;
                //                }

                //---------
                if ($kolom == "kategori_id") {
                    $kolomValue = array_key_exists($kolomValue_0, $categoryIds) ? $categoryIds[$kolomValue_0] : "none";
                }
                //                else {
                //                    $kolomValue = $kolomValue_0;
                //                }
                //---------

                $produk_specs[$kolom]["value"] = $kolomValue;
                $produk_specs[$kolom]["attr"] = isset($fieldAttr[$kolom]) ? $fieldAttr[$kolom] : "class='text-left'";
            }

            $vendorList = "";
            if (isset($produkSuppliers[$srcProduk->id])) {

                foreach ($produkSuppliers[$srcProduk->id] as $supplier_id) {
                    // $var = "$supplier_id";
                    $var = "- " . $supplierIds[$supplier_id];
                    if ($vendorList == "") {
                        $vendorList .= "$var";
                    }
                    else {
                        $vendorList = "$vendorList<br>$var";
                    }
                }
            }
            else {
                $vendorList = "-";
            }

            if (in_array("o_seller", $membership)) {

            }
            else {
                // $produk_specs['vendors']["value"] = isset($produkSuppliers[$srcProduk->id]) ? $produkSuppliers[$srcProduk->id] : "-";
                $produk_specs['vendors']["value"] = $vendorList;
                $produk_specs['vendors']["attr"] = "class='text-left'";

                $produk_specs['hpp']["value"] = isset($hargaPps[$srcProduk->id]) ? $hargaPps[$srcProduk->id] : 0;
                $produk_specs['hpp']["attr"] = "class='text-right'";
                $produk_specs['hpp']["format"] = "formatField";

                $produk_specs['qty']["value"] = isset($lStocks[$srcProduk->id]) ? $lStocks[$srcProduk->id] : 0;
                $produk_specs['qty']["attr"] = "class='text-right'";
            }


            $bodiFields[] = $produk_specs;
        }

        //         arrPrint($srcProduks);
        $headerFields = $mainHeaders + $addHeaders;

        //arrPrint($bodiFields);
        $data = array(
            "mode"      => "Data",
            "title"     => "$title",
            "subTitle"  => $subTitle,
            // "cabang" => $cabangs,
            // "dataParents" => $pairedResult,
            // "dataChilds" => $pairedDatas,
            // //            "headerFields" => $balConfig['viewedColumns'],
            "headers"   => $headerFields,
            "bodies"    => $bodiFields,
            // "thisPage"     => $thisPage,
            "isi_modal" => "",
            "q"         => $keyWord,
            // "produkFields" => $kolom_0s,
            //            "inspectTarget_mutasi" => base_url() . "Ledger/viewMoves_l2/$relName/$rekName/",
        );
        //endregion


        $this->load->view("katalog", $data);
    }

    public function viewProdukPabrik()
    {
        //        arrPrint($this->uri->segment_array());
        $relName = $this->uri->segment(3);
        $rekName = urldecode($this->uri->segment(4));
        $defPosition = detectRekDefaultPosition($rekName);

        $balConfig = isset($this->config->item('accountBalanceColumns')[$relName]) ? $this->config->item('accountBalanceColumns')[$relName] : array();
        $accountFilters = isset($this->config->item('accountBalanceColumns')[$relName]['viewFilters']) ? $this->config->item('accountBalanceColumns')[$relName]['viewFilters'] : array();


        $q = isset($_GET['q']) && strlen($_GET['q']) ? $_GET['q'] : "";
        $sortBy = isset($_GET['sortBy']) && strlen($_GET['sortBy']) ? $_GET['sortBy'] : "extern_nama";
        $sortMode = isset($_GET['sortMode']) && strlen($_GET['sortMode']) ? $_GET['sortMode'] : "ASC";


        $cabangNAMA = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_nama'];
        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];
        $gudangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['gudang_id'];


        $thisPage = base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "?o=$cabangID";
        $thisURL = base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "?q=$q&o=$cabangID";


        $this->load->helper("he_mass_table");
        $this->load->helper("he_angka");
        $this->load->model("Mdls/MdlProdukRakitan");
        $this->load->model("Mdls/MdlHargaProduk");
        $this->load->model("Mdls/MdlHargaProdukRakitan");
        $this->load->model("Mdls/MdlLockerStock");
        $this->load->model("Mdls/MdlImages");
        $this->load->model("Mdls/MdlCabang");
        $this->load->model("Mdls/MdlFifoProdukJadi");
        $com = new MdlProdukRakitan();
        $hg = new MdlHargaProduk();
        $hgr = new MdlHargaProdukRakitan();
        $st = new MdlLockerStock();
        $im = new MdlImages();
        $cb = new MdlCabang();
        $ff = new MdlFifoProdukJadi();
        // $com->addFilter("cabang_id='$cabangID'");

        // if (sizeof($accountFilters) > 0) {
        //     foreach ($accountFilters as $f) {
        //         $f_ex = explode("=", $f);
        //         if (!isset($f_ex[1])) {
        //             $f_ey = explode(">", $f_ex[0]);
        //             if (substr($f_ey[1], 0, 1) == ".") {
        //                 $com->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
        //             }
        //             else {
        //                 $com->addFilter($f_ey[0] . ">'" . $this->session->login[$f_ey[1]] . "'");
        //             }
        //         }
        //         else {
        //             if (substr($f_ex[1], 0, 1) == ".") {
        //                 $com->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
        //             }
        //             else {
        //                 $com->addFilter($f_ex[0] . "='" . $this->session->login[$f_ex[1]] . "'");
        //             }
        //         }
        //     }
        // }

        if ($cabangID > 0) {

            $st->setFilters(array());
            $st->addFilter("jenis='produk rakitan'");
            $st->addFilter("jenis_locker='stock'");
            $st->addFilter("state='active'");

            //---------------------------------------------
            $cb->addFilter("tipe='produksi'");
            $cb->addFilter("id='$cabangID'");
        }
        else {
            $inGid = array("-250", "-210", "-10", "-1");//ini ditembak dulu sambil nyari cara auto get gudang default
            $st->setFilters(array());
            $st->addFilter("gudang_id in('" . implode(",", $inGid) . "')");
            //            $st->addFilter("jenis in ('produk', 'produk rakitan')");
            $st->addFilter("jenis='produk rakitan'");
            $st->addFilter("jenis_locker='stock'");
            $st->addFilter("state='active'");

            //----------------------------------------------
            $cb->addFilter("tipe='produksi'");

        }

        // $tmp_0s = $com->lookupAll()->result();
        $tmp_0s = $com->lookupByKeyword($q)->result();
        //         cekHijau($this->db->last_query());

        $tmp_ss = $hg->lookupAll()->result();
        $tmp_rr = $hgr->lookupAll()->result();
        //        $tmp_1s = $tmp_ss;
        $tmp_1s = array_merge($tmp_ss, $tmp_rr);
        //        arrPrint($tmp_1s);
        //        mati_disini();
        // cekHitam($this->db->last_query());

        //        $st->addFilter("jenis='produk'");
        //        $st->addFilter("jenis_locker='stock'");
        //        $st->addFilter("state='active'");

        $tmp_2s = $st->lookupAll()->result();
        //        arrPrint($tmp_2s);
        cekHijau($this->db->last_query());

        $im->addFilter("jenis='produk'");
        $tmp_3s = $im->lookupAll()->result();
        // cekHitam($this->db->last_query());


        $tmp_4s = $cb->lookupAll()->result();

        //         cekHijau($this->db->last_query());
        // cekHijau("$cabangID");
        $kolom_0s = array(
            "id",
            "kode",
            "nama",
            "keterangan",
            "satuan",
            "folders_nama",
            "pic",
            "no_part",
            "berat",
            "lebar",
            "panjang",
            "tinggi",
            "volume",
            "berat_gross",
            "lebar_gross",
            "panjang_gross",
            "tinggi_gross",
            "volume_gross",
            "jenis",
        );
        $kolom_1s = array(
            "cabang_id",
            "produk_id",
            "jenis_value",
            "nilai",
        );
        $kolom_2s = array(
            "cabang_id",
            "produk_id",
            "jumlah",
            "gudang_id",
        );
        $kolom_3s = array(
            "parent_id",
            "files",
        );
        $kolom_4s = array(
            "id",
            "nama",
        );
        //region specs produks
        $specs = array();
        foreach ($tmp_0s as $temps) {
            $tempDatas = array();
            foreach ($kolom_0s as $kolom) {
                $$kolom = isset($temps->$kolom) ? $temps->$kolom : "";
                $tempDatas[$kolom] = isset($temps->$kolom) ? $temps->$kolom : "";
            }
            $specs[$id] = $tempDatas;
        }
        //endregion
        //region hargas produks
        $hargas = array();
        // $cabangIdsflip = array();
        foreach ($tmp_1s as $temps) {
            $tempDatas = array();
            foreach ($kolom_1s as $kolom) {
                $$kolom = $temps->$kolom;
                $tempDatas[$kolom] = (isset($temps->$kolom) || ($temps->$kolom == "")) ? $temps->$kolom : "-";
            }
            // $cabangIdsflip[$cabang_id] =1;
            $hargas[$cabang_id][$produk_id][$jenis_value] = $tempDatas;
        }
        // $cabangIds = array_keys($cabangIdsflip);
        //endregion
        //region stock produks
        $stocks = array();
        foreach ($tmp_2s as $temps) {
            //            arrprint($temps);
            $tempDatas = array();
            foreach ($kolom_2s as $kolom) {
                $$kolom = $temps->$kolom;
                //                if($temps->produk_id == 736){
                //                    cekHere("$jumlah");
                //                }
                $tempDatas[$kolom] = $temps->$kolom;
            }
            $stocks[$cabang_id][$produk_id] = $tempDatas;
        }
        //endregion
        //region images produks
        $images = array();
        foreach ($tmp_3s as $temps) {
            $tempDatas = array();
            foreach ($kolom_3s as $kolom) {
                $$kolom = $temps->$kolom;
                $tempDatas[$kolom] = $temps->$kolom;
            }
            $images[$parent_id][] = $tempDatas;
        }
        //endregion
        //region cabang
        $cabangs = array();
        foreach ($tmp_4s as $temps) {
            $tempDatas = array();
            foreach ($kolom_4s as $kolom) {
                $$kolom = $temps->$kolom;
                $tempDatas[$kolom] = $temps->$kolom;
            }
            $cabangs[$id] = $nama;
        }
        // if ($cabangID > 0) {
        //     $cabangs = array();
        //     $cabangs[$cabangID] = $cabangNAMA;
        // }
        //endregion

        if ($cabangID == CB_ID_PUSAT) {
            $ff->addFilter("cabang_id='$cabangID'");
            $ff->addFilter("unit>0");
            $tmpProdukFifo = $ff->lookupAll()->result();
            //            cekHere($this->db->last_query());
            //            arrPrint($tmpProdukFifo);
            $pairFifoProduk = array();
            if (sizeof($tmpProdukFifo) > 0) {
                foreach ($tmpProdukFifo as $tmpProdukFifoSpec) {
                    if (!isset($pairFifoProduk[$tmpProdukFifoSpec->produk_id][$tmpProdukFifoSpec->hpp])) {
                        $pairFifoProduk[$tmpProdukFifoSpec->produk_id][$tmpProdukFifoSpec->hpp] = 0;
                    }
                    $pairFifoProduk[$tmpProdukFifoSpec->produk_id][$tmpProdukFifoSpec->hpp] += $tmpProdukFifoSpec->unit;
                }
            }
        }


        $pairedResult = array();
        foreach ($specs as $pid => $spec) {

            // $hpp = isset($hargas[$pid]['hpp']['nilai']) ? ($hargas[$pid]['hpp']['nilai'] * 1) : 0;
            // $hjual = isset($hargas[$pid]['jual']['nilai']) ? ($hargas[$pid]['jual']['nilai'] * 1) : 0;
            // $cbId = isset($hargas[$pid]['cabang_id']) ? ($stocks[$pid]['cabang_id']) : 0;
            // $hjualnppn = isset($hargas[$pid]['jual_nppn']['nilai']) ? ($hargas[$pid]['jual_nppn']['nilai'] * 1) : 0;
            //
            // $stok = isset($stocks[$pid]['jumlah']) ? ($stocks[$pid]['jumlah'] * 1) : 0;
            $image = isset($images[$pid]) ? $images[$pid] : array();

            // $spec[$cbId]['hpp'] = $hpp;
            // $spec[$cbId]['jual'] = $hjual;
            // $spec[$cbId]['jualnppn'] = $hjualnppn;
            // $spec[$cbId]['stok'] = $stok;
            $dimensi = formatField("number", conv_mm_m($panjang_gross)) . "x" . formatField("number", conv_mm_m($lebar_gross)) . "x" . formatField("number", conv_mm_m($tinggi_gross));

            $spec['dimensi_m'] = $dimensi;
            $spec['images'] = $image;
            $pairedResult[$pid] = $spec;
            $pairedProdukFifoItem = (sizeof($pairFifoProduk) > 0 && isset($pairFifoProduk[$pid])) ? $pairFifoProduk[$pid] : array();
            //            arrPrint($pairedProdukFifoItem);

            // $pairedDatas = array();
            $specsData = array();
            foreach ($cabangs as $cId => $cNama) {
                $hpp = isset($hargas[$cId][$pid]['hpp']['nilai']) ? ($hargas[$cId][$pid]['hpp']['nilai'] * 1) : 0;
                $hjual = isset($hargas[$cId][$pid]['jual']['nilai']) ? ($hargas[$cId][$pid]['jual']['nilai'] * 1) : 0;
                $hjualnppn = isset($hargas[$cId][$pid]['jual_nppn']['nilai']) ? ($hargas[$cId][$pid]['jual_nppn']['nilai'] * 1) : 0;
                $stok = isset($stocks[$cId][$pid]['jumlah']) ? ($stocks[$cId][$pid]['jumlah'] * 1) : 0;

                if ($cabangID == CB_ID_PUSAT) {

                    $hasil = "";
                    if (sizeof($pairedProdukFifoItem) > 0) {
                        foreach ($pairedProdukFifoItem as $hpp => $qty) {
                            if ($hasil == "") {
                                $hasil = $qty . "x" . formatField("hpp", $hpp);
                            }
                            else {
                                $hasil = $hasil . "<br>" . $qty . "x" . formatField("hpp", $hpp);
                            }
                        }
                    }
                    //                    $specsData["hpp"] = $hpp;
                    $specsData["hpp"] = $hasil;
                    //                    cekKuning("$hasil");
                }
                $specsData["jual"] = (($cabangID == $cId) || ($cabangID == CB_ID_PUSAT)) ? formatField("jual", $hjual) : "-";
                $specsData["jualnppn"] = (($cabangID == $cId) || ($cabangID == CB_ID_PUSAT)) ? formatField("jualnppn", $hjualnppn) : "";
                //                $specsData["stok"] = formatField("stok", $stok);
                //                $specsData["jual"] = (($cabangID == $cId) || ($cabangID == CB_ID_PUSAT)) ? $hjual : "-";
                //                $specsData["jualnppn"] = (($cabangID == $cId) || ($cabangID == CB_ID_PUSAT)) ? $hjualnppn : "";
                $specsData["stok"] = $stok;
                if (!isset($sumStokCabang[$cabangID][$pid])) {
                    $sumStokCabang[$cabangID][$pid] = 0;
                }
                $sumStokCabang[$cabangID][$pid] += $stok;

                $pairedDatas[$cId][$pid] = $specsData;
            }
        }
        // arrPrintWebs($pairedDatas);

        //ganti headerFields
        $headerFields = array(
            "rek_id" => "kode",
            //            "kode" => "product code",
            //            "extern_nama" => "item names",
            //
            //
        );
        if (isset($balConfig['pairedModel']['viewedColumns']) && sizeof($balConfig['pairedModel']['viewedColumns'])) {
            foreach ($balConfig['pairedModel']['viewedColumns'] as $k => $v) {
                $headerFields[$k] = $v;
            }
        }
        $headerFields["extern_nama"] = "item names";
        $headerQtyFields = array(
            "qty_" . $defPosition => "balance (QTY)",
        );
        $headerValueFields = array(
            $defPosition => "balance (IDR)",
        );
        if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
            $headerFields = $headerFields + $headerQtyFields;
        }
        if (isset($balConfig['showValue']) && $balConfig['showValue'] == true) {
            $headerFields = $headerFields + $headerValueFields;
        }


        $title = "Produk Hasil Produksi";
        $subTitle = "";
        if ($q != "") {
            $subTitle .= " matched '$q'";
        }
        arrPrint($sumStokCabang);
        //        arrPrintWebs($headerFields);
        $data = array(
            "mode"          => "Katalog",
            "title"         => "$title",
            "subTitle"      => $subTitle,
            "cabang"        => $cabangs,
            "dataParents"   => $pairedResult,
            "dataChilds"    => $pairedDatas,
            // //            "headerFields" => $balConfig['viewedColumns'],
            // "headerFields" => $headerFields,
            // "thisPage"     => $thisPage,
            "isi_modal"     => "",
            "sumStokCabang" => $sumStokCabang,
            "q"             => $q,
            "produkFields"  => $kolom_0s,
            //            "inspectTarget_mutasi" => base_url() . "Ledger/viewMoves_l2/$relName/$rekName/",
        );
        //endregion


        $this->load->view("katalog", $data);


    }

    //-----------------------
    public function viewFifo()
    {

        $jenisItem = $this->uri->segment(3);
        $idItem = $this->uri->segment(4);
        $cabangID = $this->uri->segment(5);
        //        $masterConfig = loadConfigUiModul();
        $masterConfig = $this->config->item("heTransaksi_ui");

        switch ($jenisItem) {
            case "produk":
                $model = "MdlFifoProdukJadi";


                break;
            case "supplies":
                $model = "MdlFifoSupplies";

                break;
            default:
                $msg = "Jenis fifo barang tidak dikenali. Harap hubungi administrator.";
                die(lgShowAlertBiru($msg));
                break;
        }

        $this->load->model("Mdls/$model");
        $this->load->model("MdlTransaksi");

        $ff = New $model();
        $ff->addFilter("unit>0");
        $ff->addFilter("produk_id=$idItem");
        $ff->addFilter("cabang_id=$cabangID");
        $ffTmp = $ff->lookupAll()->result();
        //        showLast_query("biru");

        $ffTrID = array();
        $ffData = array();
        $trData = array();
        $ffKolom = array(
            "id",
            "produk_id",
            "produk_nama",
            "unit",
            "hpp",
            "jml_nilai",
            "hpp_riil",
            "jml_nilai_riil",
            "ppv_riil",
            "ppv_nilai_riil",
            "suppliers_id",
            "suppliers_nama",
        );
        $trKolom = array(
            "id",
            "dtime",
            "nomer",
            "counters",
            "jenis",
            "jenis_master",
            "cabang_id",
            "customers_id",
            "suppliers_id",
            "oleh_id",
            "jenis_label",
        );
        if (sizeof($ffTmp) > 0) {
            foreach ($ffTmp as $ffSpec) {

                $sub = array();
                foreach ($ffKolom as $kolom) {
                    $sub[$kolom] = isset($ffSpec->$kolom) ? $ffSpec->$kolom : "";
                }
                $ffData[$ffSpec->id] = $sub;
                $ffTrID[$ffSpec->id] = $ffSpec->transaksi_id;
            }

            //------baca transaksi
            $tr = New MdlTransaksi();
            $this->db->select($trKolom);
            $tr->addFilter("id in ('" . implode("','", $ffTrID) . "')");
            $trTmp = $tr->lookupAll()->result();
            foreach ($trTmp as $spec) {
                $jenis = $spec->jenis;
                $jenis_master = $spec->jenis_master;
                $counterDecode = blobDecode($spec->counters);
                //                $counterjenis = $jenis ."|" . $spec->cabang_id;
                $counterjenis = $jenis . "|" . $cabangID;
                $counterGlobal = $counterDecode['stepCode|placeID'][$counterjenis];
                $globalNomer = digit_5($counterGlobal);
                //cekHere(":: $jenis ::");
                $modul = isset($masterConfig[$jenis_master]["modul"]) ? $masterConfig[$jenis_master]["modul"] : "";


                $trData[$spec->id] = array(
                    "dtime"       => $spec->dtime,
                    "nomerTr"     => formatField("nomer_nolink", $spec->nomer) . "-" . $globalNomer,
                    "print_label" => $spec->nomer,
                    "description" => $spec->jenis_label,
                    "jenisTr"     => $jenis_master,
                    "modul_path"  => base_url() . "$modul/",
                );
            }
            //---------------
            foreach ($ffTrID as $ffID => $trID) {
                foreach ($trData[$trID] as $key => $val) {

                    $ffData[$ffID][$key] = $val;
                }
            }
        }


        $items = $ffData;
        //        arrPrint($items);
        $header = array(
            "dtime"       => "tanggal",
            "description" => "description",
            "nomerTr"     => "transaction number",
            "unit"        => "qty",
            "hpp"         => "hpp",
            "jml_nilai"   => "nilai",
            "print_label" => "print",
        );
        $footer = array(
            "unit"      => "qty",
            "jml_nilai" => "nilai",
        );
        $data = array(
            "mode"   => "ModalFifo",
            "title"  => isset($title) ? $title : "",
            "header" => isset($header) ? $header : array(),
            "items"  => isset($items) ? $items : array(),
            "footer" => isset($footer) ? $footer : array(),
        );
        $this->load->view("katalog", $data);
    }
}