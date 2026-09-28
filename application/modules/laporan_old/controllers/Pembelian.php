<?php

class Pembelian extends MX_Controller
{
    public function __construct()
    {
        $this->modul_path = base_url() . "pembelian/";
        $this->default_limit = 200;
        $this->jenisTr = "467";
        // $this->jenisTr_penjualan = "582spd";
        $this->jenisTrs = array("467", "460", "461", "967");
    }

    public function produk()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningPembantuProduk");
        $ps = new ComRekeningPembantuProduk();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $this->db->limit(100);
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "asc",
        );
        $ps->setSortBy($sortings);
        $ps->setJenisTr($this->jenisTrs);
        // $ps->setJenisTr("582");
        // $src = $ps->callMovementProduk("persediaan_produk");
        $src = $ps->callMovementProduk("1010030030");
        $masterData = $src['data'];
        // showLast_query("kuning");
        // cekBiru(sizeof($masterData));
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "dtime"              => array(
                "label"  => "tanggal",
                "format" => "formatField_he_format",
            ),
            "extern_id"          => array(
                "label" => "iD",
            ),
            "kode"               => array(
                "label" => "kode",
            ),
            "nama"               => array(
                "label" => "produk",
            ),
            "no_part"            => array(
                "label" => "no part",
            ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),
            "nomer"              => array(
                "label"  => "nomer",
                "format" => "formatField_he_format",
            ),
            "suppliers_nama"     => array(
                "label" => "vendor",
            ),
            "mata_uang"          => array(
                "label" => "mata uang",
                "attr"  => "class='bg-success'",
            ),
            "mata_uang_kurs"     => array(
                "label"  => "kurs",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right bg-danger'",
            ),
            "i_ppv_index__nilai" => array(
                "label" => "indeks ppv",
                "attr"  => "class='text-right'",
            ),
            // --
            // "i_harga"          => array(
            //     "label"  => "hpp IDR/USD",
            //     "format" => "formatField_he_format",
            //     "format_key" => "harga",
            // ),
            // "i_ppv"          => array(
            //     "label"  => "ppv",
            //     "format" => "formatField_he_format",
            //     "attr" => "class='text-right'",
            // ),
            // "i_hpp_nppv"          => array(
            //     "label"  => "i_hpp_nppv",
            //     "format" => "formatField_he_format",
            // ),
            // "i_exchange__hpp_nppv"          => array(
            //     "label"  => "hpp IDR",
            //     "format" => "formatField_he_format",
            // ),
            "qty_debet"          => array(
                "label"  => "qty",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right bg-warning'",
            ),
            "i_harga"            => array(
                "label"  => "cost",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right bg-info'",
            ),
            "i_sub_harga"        => array(
                "label"  => "cost value",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right bg-success'",
            ),
            "i_ppv"              => array(
                "label"  => "ppv",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right bg-info'",
            ),
            "i_sub_ppv"          => array(
                "label"  => "ppv value",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right bg-success'",
            ),
            "i_hpp_nppv"         => array(
                "label"  => "hpp",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right bg-info'",
            ),
            "harga"              => array(
                "label"  => "hpp (IDR)",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right bg-danger'",
            ),
            "i_sub_hpp_nppv"     => array(
                "label"  => "hpp value",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right bg-success'",
            ),

            // "mata_uang_kurs"          => array(
            //     "label"  => "kurs",
            //     "format" => "formatField_he_format",
            //     "attr" => "class='text-right bg-danger'",
            // ),
            // "harga"          => array(
            //     "label"  => "hpp (IDR)",
            //     "format" => "formatField_he_format",
            // ),
            "debet"              => array(
                "label"  => "hpp value (IDR)",
                "format" => "formatField_he_format",
                "attr"   => "class='text-right bg-danger'",
            ),

        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "default",
            "title"       => "Aktifitas pembelian $strDate",
            "subTitle"    => "Aktifitas pembelian $strDate",
            "modul_path"  => $this->modul_path,
            "jenisTr"     => "467",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow("Y-m-d"),
            "sum_satu"    => base_url() . "laporan/Pembelian/produkperproduk" . "$strGet",
            "sum_dua"     => base_url() . "laporan/Pembelian/produkvendor" . "$strGet",
            "sum_tiga"    => base_url() . "laporan/Pembelian/produkpertransaksi" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    public function produkraw()
    {
        // arrPrintHijau($_REQUEST);
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningPembantuProduk");
        $ps = new ComRekeningPembantuProduk();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $maxLimit = $this->default_limit;
            $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $ps->setSortBy($sortings);
        $ps->setJenisTr($this->jenisTr);
        // $ps->setJenisTr("582");
        // $src = $ps->callMovementProduk("persediaan_produk");
        $src = $ps->callMovementProduk("1010030030");
        $masterData_ori = $src['data'];
        // showLast_query("kuning");
        // cekBiru(sizeof($masterData));
        // cekBiru($masterData_ori);
        if (isset($_GET['suppliers_id'])) {
            $hasilOlahan_bysupplier = array();
            foreach ($masterData_ori as $item) {
                if (isset($_GET['suppliers_id']) && $item['suppliers_id'] == $_GET['suppliers_id']) {
                    $hasilOlahan_bysupplier[] = $item;
                }
            }
            $masterData = $hasilOlahan_bysupplier;
        }
        else {
            $masterData = $masterData_ori;
        }
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        // $arrHeaders = array(
        //     "kode"           => array(
        //         "label" => "kode",
        //     ),
        //     "nama"           => array(
        //         "label" => "produk",
        //     ),
        //     "no_part"        => array(
        //         "label" => "no part",
        //     ),
        //     // "kendaraan_nama" => array(
        //     //     "label" => "kendaraan",
        //     // ),
        //     "nomer"          => array(
        //         "label"  => "nomer",
        //         "format" => "formatField_he_format",
        //     ),
        //     "dtime"          => array(
        //         "label"  => "tanggal",
        //         "format" => "formatField_he_format",
        //     ),
        //     // "suppliers_nama" => array(
        //     //     "label" => "vendor",
        //     // ),
        //     // "keterangan"     => array(
        //     //     "label" => "note",
        //     // ),
        //     // --
        //     "harga"          => array(
        //         "label"  => "hpp",
        //         "format" => "formatField_he_format",
        //     ),
        //     "qty_debet"      => array(
        //         "label"  => "jumlah",
        //         "format" => "formatField_he_format",
        //     ),
        //     "debet"          => array(
        //         "label"  => "nilai",
        //         "format" => "formatField_he_format",
        //         "summary"    => true,
        //     ),
        //
        // );
        $arrHeaders = array(
            "dtime"          => array(
                "label"  => "tanggal",
                "format" => "formatField_he_format",
            ),
            "suppliers_nama" => array(
                "label" => "vendor",
            ),
            "nomer"          => array(
                "label"  => "nomer",
                "format" => "formatField_he_format",
            ),
            "keterangan"     => array(
                "label" => "note",
            ),
            "kode"           => array(
                "label" => "kode",
            ),
            "nama"           => array(
                "label" => "produk",
            ),
            "no_part"        => array(
                "label" => "no part",
            ),
            "kendaraan_nama" => array(
                "label" => "kendaraan",
            ),
            "satuan"         => array(
                "label" => "satuan",
            ),
            // --
            "qty_debet"      => array(
                "label"  => "qty",
                "format" => "formatField_he_format",
            ),
            "i_harga"        => array(
                "label"  => "hpp bruto",
                "format" => "formatField_he_format",
            ),
            "i_disc"         => array(
                "label"  => "disc",
                "format" => "formatField_he_format",
            ),
            "i_discPersen"   => array(
                "label"  => "disc %",
                "format" => "formatField_he_format",
            ),
            "harga"          => array(
                "label"  => "hpp netto",
                "format" => "formatField_he_format",
            ),


            "debet" => array(
                "label"   => "jumlah",
                "format"  => "formatField_he_format",
                "summary" => true,
            ),

        );
        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung",
            "title"       => "raw data $strDate",
            "subTitle"    => "Raw data pembelian",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "466",
            "data_id"     => "rawdata_" . randomNumber(1),
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "strGet"      => $strGet,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            "sum_satu"    => base_url() . "laporan/Pembelian/produkpertransaksi" . "$strGet",
            "sum_dua"     => base_url() . "laporan/Pembelian/produkvendor" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }

    public function produkvendor()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningPembantuProduk");
        $ps = new ComRekeningPembantuProduk();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $maxLimit = $this->default_limit;
            $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $ps->setSortBy($sortings);
        $ps->setJenisTr($this->jenisTrs);
        // $ps->setJenisTr("582");
        // $src = $ps->callMovementProduk("persediaan_produk");
        $src = $ps->callMovementProduk("1010030030");
        $srcMasterData = $src['data'];
        // showLast_query("kuning");
        // cekBiru(sizeof($srcMasterData));
        // cekBiru($srcMasterData);
        /* --------------------------------------------------------------------------------------------------
        *peparasi data harus 3 step
        * #1 pengumpulan data transaksi (main)
        * --------------------------------------------------------------------------------------------------*/
        $olahan = array();
        foreach ($srcMasterData as $masterDatum) {
            // $sellerID = $masterDatum['oleh_id'];
            // $cabangID = $masterDatum['cabang_id'];
            $transaksi_id = $masterDatum['transaksi_id'];

            $olahan[$transaksi_id] = $masterDatum;
        }
        /* --------------------------------------------------------------------------------------------------
         * #2 membuat tambahan kolom summary
         * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan = array();
        foreach ($olahan as $tr_id => $itemParam) {
            // arrPrintWebs($itemParam);
            $customer_id = $itemParam['m_pihakID'];

            //---------------------------------------------------------------------------------
            $sub_transaksi_nilai = $itemParam['debet'];
            if (!isset($hasilOlahan[$customer_id]['sumBruto'])) {
                $hasilOlahan[$customer_id]['sumBruto'] = 0;
            }
            $hasilOlahan[$customer_id]['sumBruto'] += $sub_transaksi_nilai;
            //---------------------------------------------------------------------------------
            $sub_transaksi_nilai_2 = $itemParam['m_harga'];
            if (!isset($hasilOlahan[$customer_id]['sumNetto'])) {
                $hasilOlahan[$customer_id]['sumNetto'] = 0;
            }
            $hasilOlahan[$customer_id]['sumNetto'] += $sub_transaksi_nilai_2;
            // //---------------------------------------------------------------------------------
            // $sub_total_disc = $itemParam['m_disc'];
            // if (!isset($hasilOlahan[$customer_id]['sumTotalDisc'])) {
            //     $hasilOlahan[$customer_id]['sumTotalDisc'] = 0;
            // }
            // $hasilOlahan[$customer_id]['sumTotalDisc'] += $sub_total_disc;
            //---------------------------------------------------------------------------------
            $sub_ppn = $itemParam['m_ppn'];
            if (!isset($hasilOlahan[$customer_id]['sumPpn'])) {
                $hasilOlahan[$customer_id]['sumPpn'] = 0;
            }
            $hasilOlahan[$customer_id]['sumPpn'] += $sub_ppn;
            //---------------------------------------------------------------------------------
            $sub_ppv = $itemParam['i_sub_ppv'];
            if (!isset($hasilOlahan[$customer_id]['sumPpv'])) {
                $hasilOlahan[$customer_id]['sumPpv'] = 0;
            }
            $hasilOlahan[$customer_id]['sumPpv'] += $sub_ppv;
            //-------------------------------------------------------------------------------
            $sub_harga = $itemParam['i_sub_harga'];
            if (!isset($hasilOlahan[$customer_id]['sumHarga'])) {
                $hasilOlahan[$customer_id]['sumHarga'] = 0;
            }
            $hasilOlahan[$customer_id]['sumHarga'] += $sub_harga;
            //---------------------------------------------------------------------------------
            $sub_hpp_ppv = $itemParam['i_sub_hpp_nppv'];
            if (!isset($hasilOlahan[$customer_id]['sumHppPpv'])) {
                $hasilOlahan[$customer_id]['sumHppPpv'] = 0;
            }
            $hasilOlahan[$customer_id]['sumHppPpv'] += $sub_hpp_ppv;
            //---------------------------------------------------------------------------------

        }
        // cekBiru($hasilOlahan);
        /* --------------------------------------------------------------------------------------------------
         * #3 pengumpulan data menjadi data siap tempur
         * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($olahan as $tr_id => $itemParam) {
            $customer_id = $itemParam['m_pihakID'];
            $hasilOlahan_1[$customer_id] = $itemParam + $hasilOlahan[$customer_id];
            // $hasilOlahan_1[$customer_id] = $itemParam;
            // $hasilOlahan[$customer_id] = $itemParam;
        }

        $masterData = $hasilOlahan_1;

        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            // "dtime"          => array(
            //     "label"  => "tanggal",
            //     "format" => "formatField_he_format",
            //     "summary" => false,
            // ),
            // "kode"           => array(
            //     "label" => "kode",
            // ),
            // "nama"           => array(
            //     "label" => "produk",
            // ),
            // "no_part"        => array(
            //     "label" => "no part",
            // ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),
            // "nomer"          => array(
            //     "label"  => "nomer",
            //     "format" => "formatField_he_format",
            // ),
            "suppliers_nama" => array(
                "label" => "vendor",
                "links" => array(
                    // "target" => "laporan/Pembelian/produkpertransaksi",
                    "target" => "laporan/Pembelian/produkraw",
                    "title"  => "Transaksi per vendor",
                    "key"    => "suppliers_id",
                ),
            ),
            "mata_uang"      => array(
                "label" => "mata uang",
            ),
            // --
            // "sumNetto"          => array(
            //     "label"  => "bruto",
            //     "format" => "formatField_he_format",
            //     "summary"    => true,
            // ),
            "sumHarga"       => array(
                "label"     => "cost",
                "format"    => "formatField_he_format",
                "attr"      => "class='text-right bg-success'",
                "attr_head" => "class='text-right'",
                // "summary"    => true,
            ),
            "sumPpv"         => array(
                "label"     => "ppv",
                "format"    => "formatField_he_format",
                // "summary" => true,
                "attr"      => "class='text-right bg-success'",
                "attr_head" => "class='text-right'",
            ),
            "sumHppPpv"      => array(
                "label"     => "hpp",
                "format"    => "formatField_he_format",
                // "summary"    => true,
                "attr"      => "class='text-right bg-success'",
                "attr_head" => "class='text-right'",
            ),
            // "sumPpn"      => array(
            //     "label"  => "PPN",
            //     "format" => "formatField_he_format",
            //     "summary"    => true,
            // ),
            "mata_uang_kurs" => array(
                "label"      => "kurs",
                "format"     => "formatField_he_format",
                "format_key" => "netto",
            ),
            "sumBruto"       => array(
                "label"      => "hpp (IDR)",
                "attr"       => "class='text-right'",
                "format"     => "formatField_he_format",
                "format_key" => "bruto",
                "summary"    => true,
            ),

        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung",
            "title"       => "summary pembelian per vendor $strDate",
            "subTitle"    => "Raw data pembelian",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "466",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "strGet"      => $strGet,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    public function produkperproduk()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningPembantuProduk");
        $ps = new ComRekeningPembantuProduk();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        else {
            $maxLimit = $this->default_limit;
            $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $ps->setSortBy($sortings);
        $ps->setJenisTr($this->jenisTrs);
        // $ps->setJenisTr("582");
        // $src = $ps->callMovementProduk("persediaan_produk");
        $src = $ps->callMovementProduk("1010030030");
        $srcMasterData = $src['data'];
        // showLast_query("kuning");
        // cekBiru(sizeof($srcMasterData));
        // cekBiru($srcMasterData);
        /* --------------------------------------------------------------------------------------------------
        *peparasi data harus 3 step
        * #1 pengumpulan data transaksi (main)
        * --------------------------------------------------------------------------------------------------*/
        $olahan = array();
        $hasilOlahan = array();
        foreach ($srcMasterData as $masterDatum) {
            // $sellerID = $masterDatum['oleh_id'];
            // $cabangID = $masterDatum['cabang_id'];
            $transaksi_id = $masterDatum['transaksi_id'];
            $extern_id = $masterDatum['extern_id'];
            $customer_id = $extern_id;
            $jenis = $masterDatum['jenis'];
            $harga = $masterDatum['harga'];
            $debet = $masterDatum['debet'];
            $i_harga = $masterDatum['i_harga'];
            $i_sub_harga = $masterDatum['i_sub_harga'];
            $i_hpp_nppv = $masterDatum['i_hpp_nppv'];
            $i_sub_hpp_nppv = $masterDatum['i_sub_hpp_nppv'];
            $i_ppv = $masterDatum['i_ppv'];
            $i_jml = $masterDatum['i_jml'];
            $i_disc = isset($masterDatum['i_sub_disc']) ? $masterDatum['i_sub_disc'] : 0;
            $i_sub_ppn = $masterDatum['i_sub_ppn'];
            $i_subHppNppn = $masterDatum['i_sub_hpp_nppn'];
            $sub_transaksi_nilai = $masterDatum['i_sub_harga'];

            $olahan[$extern_id] = $masterDatum;
            $jenis = $masterDatum['jenis'];
            $harga = $masterDatum['harga'];
            switch ($jenis) {
                case "967":
                    break;
                default:
                    //---------------------------------------------------------------------------------
                    if (!isset($hasilOlahan[$customer_id]['sumBruto'])) {
                        $hasilOlahan[$customer_id]['sumBruto'] = 0;
                    }
                    $hasilOlahan[$customer_id]['sumBruto'] += $sub_transaksi_nilai;
                    //---------------------------------------------------------------------------------
                    if (!isset($hasilOlahan[$customer_id]['sumJml'])) {
                        $hasilOlahan[$customer_id]['sumJml'] = 0;
                    }
                    $hasilOlahan[$customer_id]['sumJml'] += $i_jml;
                    // //---------------------------------------------------------------------------------
                    if (!isset($hasilOlahan[$customer_id]['sumDisc'])) {
                        $hasilOlahan[$customer_id]['sumDisc'] = 0;
                    }
                    $hasilOlahan[$customer_id]['sumDisc'] += $i_disc;
                    //---------------------------------------------------------------------------------
                    if (!isset($hasilOlahan[$customer_id]['sumPpn'])) {
                        $hasilOlahan[$customer_id]['sumPpn'] = 0;
                    }
                    $hasilOlahan[$customer_id]['sumPpn'] += $i_sub_ppn;
                    //---------------------------------------------------------------------------------
                    if (!isset($hasilOlahan[$customer_id]['sumNetto'])) {
                        $hasilOlahan[$customer_id]['sumNetto'] = 0;
                    }
                    $hasilOlahan[$customer_id]['sumNetto'] += $i_subHppNppn;
                    //---------------------------------------------------------------------------------
                    if (!isset($hasilOlahan[$customer_id]['sumDebet'])) {
                        $hasilOlahan[$customer_id]['sumDebet'] = 0;
                    }
                    $hasilOlahan[$customer_id]['sumDebet'] += $debet;
                    //---------------------------------------------------------------------------------
                    break;
            }
        }
        /* --------------------------------------------------------------------------------------------------
         * #2 membuat tambahan kolom summary
         * --------------------------------------------------------------------------------------------------*/
        // $hasilOlahan = array();
        // foreach ($olahan as $tr_id => $itemParam) {
        //     // arrPrintWebs($itemParam);
        //     $customer_id = $itemParam['m_pihakName'];
        //     $jenis = $itemParam['jenis'];
        //     $harga = $itemParam['harga'];
        //     $i_jml = $itemParam['i_jml'];
        //     $i_disc = $itemParam['i_disc'];
        //     $i_subHppNppn = $itemParam['i_subHppNppn'];
        //
        //     // if (!isset($hasilOlahan[$customer_id]['harga'])) {
        //     //     $hasilOlahan[$customer_id]['sumJml'] = 0;
        //     // }
        //     // $hasilOlahan[$customer_id]['sumJml'] += $i_jml;
        // }
        // cekBiru($hasilOlahan);
        /* --------------------------------------------------------------------------------------------------
         * #3 pengumpulan data menjadi data siap tempur
         * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($olahan as $tr_id => $itemParam) {
            $customer_id = $tr_id;
            $hasilOlahan_1[$customer_id] = $itemParam + $hasilOlahan[$customer_id];
            // $hasilOlahan_1[$customer_id] = $itemParam;
            // $hasilOlahan[$customer_id] = $itemParam;
        }

        $masterData = $hasilOlahan_1;

        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            // "dtime"          => array(
            //     "label"  => "tanggal",
            //     "format" => "formatField_he_format",
            //     "summary" => false,
            // ),
            "kode"        => array(
                "label" => "kode",
            ),
            "extern_nama" => array(
                "label" => "produk",
                // "links" => array(
                //     // "target" => "laporan/Pembelian/produkpertransaksi",
                //     "target" => "laporan/Pembelian/produkraw",
                //     "title"  => "Transaksi per vendor",
                //     "key"    => "suppliers_id",
                // ),
            ),
            "no_part"     => array(
                "label" => "no part",
            ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),
            // "nomer"          => array(
            //     "label"  => "nomer",
            //     "format" => "formatField_he_format",
            // ),

            // "kode" => array(
            //     "label" => "kode",
            // ),
            // --
            // "i_harga"          => array(
            //     "label"  => "hpp",
            //     "format" => "formatField_he_format",
            // ),
            // "i_ppv"      => array(
            //     "label"  => "ppv",
            //     "format" => "formatField_he_format",
            // ),
            // "i_hpp_nppv"      => array(
            //     "label"  => "hpp ppv",
            //     "format" => "formatField_he_format",
            // ),
            "sumJml"      => array(
                "label"  => "Qty",
                "format" => "formatField_he_format",
            ),
            // "sumBruto"       => array(
            //     "label"      => "nilai bruto",
            //     "attr"       => "class='text-right'",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "bruto",
            //     "summary"    => true,
            // ),
            // "sumDisc"       => array(
            //     "label"      => "diskon",
            //     "attr"       => "class='text-right'",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "bruto",
            //     "summary"    => true,
            // ),
            // "sumPpn"       => array(
            //     "label"      => "ppn",
            //     "attr"       => "class='text-right'",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "bruto",
            //     "summary"    => true,
            // ),
            "sumDebet"    => array(
                "label"      => "nilai",
                "attr"       => "class='text-right'",
                "format"     => "formatField_he_format",
                "format_key" => "bruto",
                "summary"    => true,
            ),

        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung",
            "title"       => "summary pembelian produk $strDate",
            "subTitle"    => "Raw data pembelian",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "466",
            "data_id"     => "perproduk",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "strGet"      => $strGet,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    public function produkpertransaksi()
    {
        // arrPrintHijau(url_segment());
        // arrPrintHijau($_REQUEST);
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningPembantuProduk");
        $ps = new ComRekeningPembantuProduk();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        // elseif (isset($_GET['suppliers_id'])){
        //     $condites = array(
        //         "suppliers_id" => $_GET['suppliers_id'],
        //     );
        //     $this->db->where($condites);
        // }
        else {
            $maxLimit = $this->default_limit;
            $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $ps->setSortBy($sortings);
        $ps->setJenisTr($this->jenisTr);
        // $ps->setJenisTr("582");
        // $src = $ps->callMovementProduk("persediaan_produk");
        $src = $ps->callMovementProduk("1010030030");
        $srcMasterData = $src['data'];
        // showLast_query("kuning");
        // cekBiru(sizeof($srcMasterData));
        // cekBiru($srcMasterData);
        /* --------------------------------------------------------------------------------------------------
        *peparasi data harus 3 step
        * #1 pengumpulan data transaksi (main)
        * --------------------------------------------------------------------------------------------------*/
        $olahan = array();
        foreach ($srcMasterData as $masterDatum) {
            // $sellerID = $masterDatum['oleh_id'];
            // $cabangID = $masterDatum['cabang_id'];
            $transaksi_id = $masterDatum['transaksi_id'];

            $olahan[$transaksi_id] = $masterDatum;
        }
        /* --------------------------------------------------------------------------------------------------
         * #2 membuat tambahan kolom summary
         * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan = array();
        foreach ($olahan as $tr_id => $itemParam) {
            // arrPrintWebs($itemParam);
            $customer_id = $itemParam['m_pihakID'];

            //---------------------------------------------------------------------------------
            $sub_transaksi_nilai = $itemParam['m_hpp_nppn'];
            if (!isset($hasilOlahan[$customer_id]['sumBruto'])) {
                $hasilOlahan[$customer_id]['sumBruto'] = 0;
            }
            $hasilOlahan[$customer_id]['sumBruto'] += $sub_transaksi_nilai;
            //---------------------------------------------------------------------------------
            // $sub_transaksi_nilai_2 = $itemParam['m_harga_nett3'];
            // if (!isset($hasilOlahan[$customer_id]['sumNetto'])) {
            //     $hasilOlahan[$customer_id]['sumNetto'] = 0;
            // }
            // $hasilOlahan[$customer_id]['sumNetto'] += $sub_transaksi_nilai_2;
            // //---------------------------------------------------------------------------------
            // $sub_total_disc = $itemParam['m_total_disc'];
            // if (!isset($hasilOlahan[$customer_id]['sumTotalDisc'])) {
            //     $hasilOlahan[$customer_id]['sumTotalDisc'] = 0;
            // }
            // $hasilOlahan[$customer_id]['sumTotalDisc'] += $sub_total_disc;
            //---------------------------------------------------------------------------------

        }
        // cekBiru($hasilOlahan);
        /* --------------------------------------------------------------------------------------------------
         * #3 pengumpulan data menjadi data siap tempur
         * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($olahan as $tr_id => $itemParam) {
            $customer_id = $itemParam['m_pihakID'];

            $hasilOlahan_1[$tr_id] = $itemParam + $hasilOlahan[$customer_id];

            if (isset($_GET['suppliers_id']) && $customer_id == $_GET['suppliers_id']) {
                // $hasilOlahan_1 = array();
                $hasilOlahan_bysupplier[$tr_id] = $itemParam + $hasilOlahan[$customer_id];
            }
        }

        if (isset($_GET['suppliers_id'])) {
            // cekHijau(__LINE__);
            $masterData = $hasilOlahan_bysupplier;
        }
        else {
            $masterData = $hasilOlahan_1;
        }

        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "dtime"          => array(
                "label"   => "tanggal",
                "format"  => "formatField_he_format",
                "summary" => false,
            ),
            // "kode"           => array(
            //     "label" => "kode",
            // ),
            "suppliers_nama" => array(
                "label" => "vendor",
            ),
            // "no_part"        => array(
            //     "label" => "no part",
            // ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),
            // "nomer"          => array(
            //     "label"  => "nomer",
            //     "format" => "formatField_he_format",
            // ),
            "nomer"          => array(
                "label"  => "nomer",
                "format" => "formatField_he_format",
                // "links" => array(
                //     "target" => "laporan/Pembelian/",
                //     "title" => "laporan Pembelian",
                // ),
            ),
            "keterangan"     => array(
                "label" => "note",
            ),
            // --
            "m_harga"        => array(
                "label"      => "bruto",
                "format"     => "formatField_he_format",
                "format_key" => "bruto",
                "summary"    => true,
            ),
            "m_disc"         => array(
                "label"      => "diskon",
                "format"     => "formatField_he_format",
                "format_key" => "bruto",
                "summary"    => true,
            ),
            "m_ppn"          => array(
                "label"      => "PPN",
                "format"     => "formatField_he_format",
                "format_key" => "bruto",
                "summary"    => true,
            ),
            "m_hpp_nppn"     => array(
                "label"      => "Netto",
                "attr"       => "class='text-right'",
                "format"     => "formatField_he_format",
                "format_key" => "bruto",
                "summary"    => true,
            ),

        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung",
            "title"       => "log per transaksi $strDate",
            "subTitle"    => "Raw data pembelian",
            "modul_path"  => $this->modul_path,
            "jenisTr"     => "466",
            "data_id"     => "pertransaksi_" . randomNumber(2),
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "strGet"      => $strGet,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    public function produkpermatauang()
    {
        // arrPrintHijau(url_segment());
        // arrPrintHijau($_REQUEST);
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningPembantuProduk");
        $ps = new ComRekeningPembantuProduk();

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : "";

        $strDate = "";
        if (isset($_GET['date1'])) {
            $condites = array(
                "date(dtime)>=" => $get_date1,
                "date(dtime)<=" => $get_date2,
            );
            $this->db->where($condites);

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        // elseif (isset($_GET['suppliers_id'])){
        //     $condites = array(
        //         "suppliers_id" => $_GET['suppliers_id'],
        //     );
        //     $this->db->where($condites);
        // }
        else {
            $maxLimit = $this->default_limit;
            $this->db->limit($maxLimit);

            $strDate = "$maxLimit data terakhir";
        }

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $ps->setSortBy($sortings);
        $ps->setJenisTr($this->jenisTr);
        // $ps->setJenisTr("582");
        // $src = $ps->callMovementProduk("persediaan_produk");
        $src = $ps->callMovementProduk("1010030030");
        $srcMasterData = $src['data'];
        // showLast_query("kuning");
        // cekBiru(sizeof($srcMasterData));
        // cekBiru($srcMasterData);
        /* --------------------------------------------------------------------------------------------------
        *peparasi data harus 3 step
        * #1 pengumpulan data transaksi (main)
        * --------------------------------------------------------------------------------------------------*/
        $olahan = array();
        foreach ($srcMasterData as $masterDatum) {
            // $sellerID = $masterDatum['oleh_id'];
            // $cabangID = $masterDatum['cabang_id'];
            $transaksi_id = $masterDatum['transaksi_id'];

            $olahan[$transaksi_id] = $masterDatum;
        }
        /* --------------------------------------------------------------------------------------------------
         * #2 membuat tambahan kolom summary
         * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan = array();
        foreach ($olahan as $tr_id => $itemParam) {
            // arrPrintWebs($itemParam);
            $customer_id = $itemParam['m_pihakID'];

            //---------------------------------------------------------------------------------
            $sub_transaksi_nilai = $itemParam['m_hpp_nppn'];
            if (!isset($hasilOlahan[$customer_id]['sumBruto'])) {
                $hasilOlahan[$customer_id]['sumBruto'] = 0;
            }
            $hasilOlahan[$customer_id]['sumBruto'] += $sub_transaksi_nilai;
            //---------------------------------------------------------------------------------
            // $sub_transaksi_nilai_2 = $itemParam['m_harga_nett3'];
            // if (!isset($hasilOlahan[$customer_id]['sumNetto'])) {
            //     $hasilOlahan[$customer_id]['sumNetto'] = 0;
            // }
            // $hasilOlahan[$customer_id]['sumNetto'] += $sub_transaksi_nilai_2;
            // //---------------------------------------------------------------------------------
            // $sub_total_disc = $itemParam['m_total_disc'];
            // if (!isset($hasilOlahan[$customer_id]['sumTotalDisc'])) {
            //     $hasilOlahan[$customer_id]['sumTotalDisc'] = 0;
            // }
            // $hasilOlahan[$customer_id]['sumTotalDisc'] += $sub_total_disc;
            //---------------------------------------------------------------------------------

        }
        // cekBiru($hasilOlahan);
        /* --------------------------------------------------------------------------------------------------
         * #3 pengumpulan data menjadi data siap tempur
         * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($olahan as $tr_id => $itemParam) {
            $customer_id = $itemParam['m_pihakID'];

            $hasilOlahan_1[$tr_id] = $itemParam + $hasilOlahan[$customer_id];

            if (isset($_GET['suppliers_id']) && $customer_id == $_GET['suppliers_id']) {
                // $hasilOlahan_1 = array();
                $hasilOlahan_bysupplier[$tr_id] = $itemParam + $hasilOlahan[$customer_id];
            }
        }

        if (isset($_GET['suppliers_id'])) {
            // cekHijau(__LINE__);
            $masterData = $hasilOlahan_bysupplier;
        }
        else {
            $masterData = $hasilOlahan_1;
        }

        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        $arrHeaders = array(
            "dtime"          => array(
                "label"   => "tanggal",
                "format"  => "formatField_he_format",
                "summary" => false,
            ),
            // "kode"           => array(
            //     "label" => "kode",
            // ),
            "suppliers_nama" => array(
                "label" => "vendor",
            ),
            // "no_part"        => array(
            //     "label" => "no part",
            // ),
            // "kendaraan_nama" => array(
            //     "label" => "kendaraan",
            // ),
            // "nomer"          => array(
            //     "label"  => "nomer",
            //     "format" => "formatField_he_format",
            // ),
            "nomer"          => array(
                "label"  => "nomer",
                "format" => "formatField_he_format",
                // "links" => array(
                //     "target" => "laporan/Pembelian/",
                //     "title" => "laporan Pembelian",
                // ),
            ),
            "keterangan"     => array(
                "label" => "note",
            ),
            // --
            "m_harga"        => array(
                "label"      => "bruto",
                "format"     => "formatField_he_format",
                "format_key" => "bruto",
                "summary"    => true,
            ),
            "m_disc"         => array(
                "label"      => "diskon",
                "format"     => "formatField_he_format",
                "format_key" => "bruto",
                "summary"    => true,
            ),
            "m_ppn"          => array(
                "label"      => "PPN",
                "format"     => "formatField_he_format",
                "format_key" => "bruto",
                "summary"    => true,
            ),
            "m_hpp_nppn"     => array(
                "label"      => "Netto",
                "attr"       => "class='text-right'",
                "format"     => "formatField_he_format",
                "format_key" => "bruto",
                "summary"    => true,
            ),

        );

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung",
            "title"       => "laporan per transaksi $strDate",
            "subTitle"    => "Raw data pembelian",
            "modul_path"  => $this->modul_path,
            "jenisTr"     => "466",
            "data_id"     => "pertransaksi_" . randomNumber(2),
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "strGet"      => $strGet,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
        );
        $this->load->view("laporan", $data);
    }

    public function outstanding()
    {
        $jenisTr = "466";
        $this->configPath = $configPath = "../../modules/pembelian/config/";
        cekHitam($this->configPath);
        $this->load->config($configPath . "coTransaksiUi");
        $this->configUi = $this->config->item("coTransaksiUi");
        $steps = $this->configUi[$jenisTr]['steps'];

        $this->load->helper("he_session_replacer");
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();

        $tr->addFilter("div_id='" . $this->session->login['div_id'] . "'");
        $tr->addFilter("jenis_top='466r'");
        $tr->addFilter("next_substep_code<>''");
        $tr->addFilter("sub_step_number>0");
        $tr->addFilter("transaksi_data.trash=0");
        $tr->addFilter("transaksi_data.valid_qty>0");

        $tmpHist = $tr->lookupUndoneEntries_joined(replaceSession())->result();
        showLast_query("hijau");
        cekHijau(sizeof($tmpHist));
        // arrPrintHijau($tmpHist);

        $tr->setFilters(array());
        $master = "transaksi";
        $slave = "transaksi_data";
        $jenies = array(
          "466r",
          // "466"
        );
        $condites = array(
            "link_id" => 0,
            "div_id" => 18,
            "$slave.valid_qty >" => 0,
        );
        $this->db->where($condites);
        // $this->db->where_in("jenis",  $jenies);
        $this->db->where_in("jenis_top",  $jenies);
        $this->db->group_by("$master.id");
        // $src_order = $tr->lookupAll()->result();
        $this->db->join("$slave", "$slave.transaksi_id = $master.id");
        $src_order = $this->db->get("transaksi")->result();
        showLast_query("kuning");
        cekKuning(sizeof($src_order));
    }
}