<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/22/2018
 * Time: 8:38 PM
 */
$config["coTransaksiCore"] = array(
    "460" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "supplierID" => "pihakID",
                "supplierName" => "pihakName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "produk_id" => "id",
                "produk_nama" => "nama",

//                "ppn" => "(ppnFactor*harga)/100",
                "ppn" => "(0*harga)/100",
                "hpp_nppn" => "harga+ppn",
                "hpp_nppv" => "harga*ppv_index__nilai",
                "ppv" => "hpp_nppv-harga",
                "hpp_nppn_nppv" => "hpp_nppn+ppv",
                "nett" => "harga+ppn", // yg dipakai di grand total
            ),
            "master_dependent" => array(
                "paymentMethod" => array(
//                    "cash" => array(
//                        "nilai_cash" => "tagihan",
//                        "nilai_credit" => "0",
//                    ),
                    "credit" => array(
                        "nilai_credit" => "tagihan",
                        "nilai_cash" => "0",
                    ),
                    "cbd" => array(
                        "nilai_credit" => "tagihan",
                        "nilai_cash" => "0",
                    ),
                    "cia" => array(
                        "nilai_credit" => "tagihan",
                        "nilai_cash" => "0",
                    ),
                    "tt_adv" => array(
                        "nilai_credit" => "tagihan",
                        "nilai_cash" => "0",
                    ),
                ),
            ),
        ),
        "valueBuilders" => array(
            "grand_total" => "nett",
            "tagihan" => "grand_total-discount-dp",
        ),
        "preProcessor" => array(
            "460" => array(
                "master" => array(
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".active",
                            "jenis" => ".ppn in",
                            "produk_id" => "pihakID",
                            "nama" => "pihakName",
                            "nilai" => "ppn",
//                            "transaksi_id" => "masterID",
                            "transaksi_id" => "currentID",
                            "oleh_id" => ".0",
                            "paymentMethod" => "paymentMethod",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "nilai_dipakai" => "nilai_dipakai",
                                "nilai_tambah" => "nilai_tambah",
                            ),
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".active",
                            "jenis" => ".piutang pembelian",
                            "produk_id" => "pihakID",
                            "nama" => "pihakName",
                            "nilai" => "tagihan-nilai_dipakai_ppn_in",
//                            "transaksi_id" => "masterID",
                            "transaksi_id" => "currentID",
                            "oleh_id" => ".0",
                            "paymentMethod" => "paymentMethod",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "nilai_dipakai" => "nilai_dipakai",
                                "nilai_tambah" => "nilai_tambah",
                            ),
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
        ),
        "tableIn" => array(
            "master" => array(
                "jenis_master" => "jenisTrMaster",
                "jenis_top" => "jenisTrTop",
                "jenis" => "jenisTr",
                "jenis_label" => "jenisTrName",
                "div_id" => "divID",
                "div_nama" => "divName",
                "dtime" => "dtime",
                "fulldate" => "fulldate",
                "oleh_id" => "olehID",
                "oleh_nama" => "olehName",

                "suppliers_id" => "supplierID",
                "suppliers_nama" => "supplierName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "nett",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",

            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "produk",
            ),
        ),

        "components" => array(
            "460" => array(
                "master" => array(


                    //region jurnal pertama
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030040" => "exchange__harga",//persediaan produk riil
                            "1010040050" => "exchange__nilai_tambah_ppn_in",//ppn in

                            "2010010" => "exchange__nilai_tambah_piutang_pembelian",//hutang dagang
                            "1010020030" => "-exchange__nilai_dipakai_piutang_pembelian",//piutang pembelian
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010030040" => "exchange__harga",//persediaan produk riil
                            "1010040050" => "exchange__nilai_tambah_ppn_in",//ppn in

                            "2010010" => "exchange__nilai_tambah_piutang_pembelian",//hutang dagang
                            "1010020030" => "-exchange__nilai_dipakai_piutang_pembelian",//piutang pembelian
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "2010010" => "exchange__nilai_tambah_piutang_pembelian",//hutang dagang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "1010020030" => "-exchange__nilai_dipakai_piutang_pembelian",//piutang pembelian
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "1010040050" => "exchange__nilai_tambah_ppn_in",//ppn in
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // pembantu hutang dagang (lokal / import)
                    array(
                        "comName" => "RekeningPembantuSupplierJenis",
                        "loop" => array(
                            "2010010" => "exchange__nilai_tambah_piutang_pembelian",//hutang dagang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2010010020",
                            "extern_nama" => ".import",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // pembantu hutang dagang (lokal/import dengan supplier)
                    array(
                        "comName" => "RekeningPembantuSupplierSubJenis",
                        "loop" => array(
                            "2010010" => "exchange__nilai_tambah_piutang_pembelian",//hutang dagang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2010010020",
                            "extern_nama" => ".import",
                            "extern2_id" => "pihakID",
                            "extern2_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //endregion

                    //region jurnal kedua pindah persediaan riil ke persediaan(std)
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
//
//                            "1010030030" => "exchange__hpp_nppv",//persediaan produk
//                            "1010030040" => "-exchange__harga",//persediaan produk riil
//                            "2010090010" => "exchange__ppv",//hutang lain ppv
                            "1010030030" => "exchange__harga",//persediaan produk
                            "1010030040" => "-exchange__harga",//persediaan produk riil
                            "2010090010" => ".0",//hutang lain ppv

                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
//
//                            "1010030030" => "exchange__hpp_nppv",//persediaan produk
//                            "1010030040" => "-exchange__harga",//persediaan produk riil
//                            "2010090010" => "exchange__ppv",//hutang lain ppv
                            "1010030030" => "exchange__harga",//persediaan produk
                            "1010030040" => "-exchange__harga",//persediaan produk riil
                            "2010090010" => ".0",//hutang lain ppv
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion

                    //ingat atambalihn pembantu pembelian lokal atau import


                ),
                "detail" => array(

                    array(
                        "comName" => "RekeningPembantuProdukRiil",
                        "loop" => array(
                            "1010030040" => "exchange__sub_harga",//persediaan produk riil
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "produk_nilai" => "exchange__harga",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuProdukRiil",
                        "loop" => array(
                            "1010030040" => "-exchange__sub_harga",//persediaan produk riil
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "exchange__harga",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
//                            "1010030030" => "exchange__sub_hpp_nppv",//persediaan produk
                            "1010030030" => "exchange__sub_harga",//persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
//                            "produk_nilai" => "exchange__hpp_nppv",
                            "produk_nilai" => "exchange__harga",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            //---------------------------------
//                            "produk_nilai_riil" => "exchange__hpp",
                            "produk_nilai_riil" => "exchange__harga",
                            "produk_nilai_ppv" => "exchange__ppv",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),

        ),
        "postProcessor" => array(
            "460r" => array(
                "master" => array(
                    array(
                        "comName" => "RekeningPembantuTransaksiPembelian",// oke
                        "loop" => array(
                            "460r" => "exchange__harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "transaksi_id",
                            "produk_nama" => "transaksi_no",
                            "produk_nilai" => "harga_disc",
                            "extern_id" => "pihakID",
                        ),
                        "reversable" => false,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),//nulis ke table pembelian_pembantu_transaksi_cache dan mutasi
                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningTransaksiDataPembelian",
                        "loop" => array(
                            "460r" => "sub_exchange__harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "exchange__harga",
                            "produk_qty" => "qty",
                            "extern_id" => "transaksi_id",
                        ),
                        "reversable" => false,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//nulis ke table _rek_penjualan_transkasi_data dan mutasi
                    array(
                        "comName" => "RekeningTransaksiDataPembelianCache",
                        "loop" => array(
                            "460r" => "sub_exchange__harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "exchange__harga",
                            "produk_qty" => "qty",
                            "extern_id" => "transaksi_id",
                        ),
                        "reversable" => false,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//nulis ke table casche produk sebagai index,rekening dan mutasi
                    array(
                        "comName" => "TransaksiDataPembelian",
                        "loop" => array(
                            "460r" => "sub_exchange__harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "exchange__harga",
                            "produk_qty" => "qty",
                            "extern_id" => "transaksi_id",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//untuk update pembelian_transaki_data
                ),
            ),
            "460a" => array(
                "master" => array(
                    array(
                        "comName" => "RekeningPembantuTransaksiPembelian",
                        "loop" => array(
                            "460r" => "-exchange__harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "currentID",//klau posisi mengurangi wajib bawa ini
//                            "produk_nama" => "transaksi_no",
                            "produk_nilai" => "exchange__harga",
                            "extern_id" => "pihakID",
                        ),
                        "reversable" => false,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),//nulis ke table pembelian_pembantu_transaksi_cache dan mutasi
                    array(
                        "comName" => "RekeningPembantuTransaksiPembelian",
                        "loop" => array(
                            "460a" => "exchange__harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
//                            "produk_id" => "trasnaksi_id",//masuk tidak perlu karena akan diinject pakai transkai+id hasul followup
//                            "produk_nama" => "transaksi_no",
                            "produk_nilai" => "exchange__harga",
                            "extern_id" => "pihakID",
                        ),
                        "reversable" => false,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),//nulis ke table pembelian_pembantu_transaksi_cache dan mutasi

                ),
                "detail" => array(

                    //region ERP
                    array(
                        "comName" => "RekeningTransaksiDataPembelian",
                        "loop" => array(
                            "460r" => "-sub_exchange__harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "exchange__harga",
                            "produk_qty" => "-qty",
                            "extern_id" => "currentID",//didefine kena akan dikurangi jika masuk abaikan
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//nulis ke table _rek_penjualan_transkasi_data dan mutasi
                    array(
                        "comName" => "RekeningTransaksiDataPembelian",
                        "loop" => array(
                            "460a" => "sub_exchange__harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "exchange__harga",
                            "produk_qty" => "qty",
//                            "extern_id" => "transaksi_id",//masuk tidak perlu extern_id karena baca transaksi_id hasil followup
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//nulis ke table _rek_penjualan_transkasi_data dan mutasi

                    array(
                        "comName" => "RekeningTransaksiDataPembelianCache",
                        "loop" => array(
                            "460r" => "-sub_exchange__harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "exchange__harga",
                            "produk_qty" => "-qty",
                            "extern_id" => "currentID",//tranksi 466r
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//nulis ke table casche produk sebagai index,rekening dan mutasi
                    array(
                        "comName" => "RekeningTransaksiDataPembelianCache",
                        "loop" => array(
                            "460a" => "sub_exchange__harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "exchange__harga",
                            "produk_qty" => "qty",
//                            "extern_id" => "transaksi_id",//tranksi 466r
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//nulis ke table casche produk sebagai index,rekening dan mutasi
//
                    array(
                        "comName" => "TransaksiDataPembelian",
                        "loop" => array(
                            "460r" => "-sub_exchange__harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "exchange__harga",
                            "produk_qty" => "-qty",
                            "extern_id" => "currentID",
                            "method" => ".approve",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//untuk update pembelian_transaki_data
                    array(
                        "comName" => "TransaksiDataPembelian",
                        "loop" => array(
                            "460a" => "sub_exchange__harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "exchange__harga",
                            "produk_qty" => "qty",
//                            "extern_id" => "transaksi_id",
                            "method" => ".create",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//untuk update pembelian_transaki_data
                    //endregion

                ),
            ),
            "460" => array(
                "master" => array(
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".hold",
                            "jenis" => ".ppn in",
                            "produk_id" => "pihakID",
                            "nama" => "pihakName",
                            "nilai" => "-exchange__nilai_dipakai_ppn_in",
                            "transaksi_id" => "currentID",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".hold",
                            "jenis" => ".piutang pembelian",
                            "produk_id" => "pihakID",
                            "nama" => "pihakName",
                            "nilai" => "-exchange__nilai_dipakai_piutang_pembelian",
                            "transaksi_id" => "currentID",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuTransaksiPembelian",
                        "loop" => array(
                            "460a" => "-exchange__harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "currentID",//klau posisi mengurangi wajib bawa ini
//                            "produk_nama" => "transaksi_no",
                            "produk_nilai" => "exchange__harga",
                            "extern_id" => "pihakID",
                        ),
                        "reversable" => false,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),//nulis ke table pembelian_pembantu_transaksi_cache dan mutasi
                    array(
                        "comName" => "RekeningPembantuTransaksiPembelian",
                        "loop" => array(
                            "460" => "exchange__harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
//                            "produk_id" => "trasnaksi_id",//masuk tidak perlu karena akan diinject pakai transkai+id hasul followup
//                            "produk_nama" => "transaksi_no",
                            "produk_nilai" => "exchange__harga",
                            "extern_id" => "pihakID",
                        ),
                        "reversable" => false,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),//nulis ke table pembelian_pembantu_transaksi_cache dan mutasi

                ),
                "detail" => array(
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".produk",
                            "jml" => "qty",
                            "produk_id" => "id",
//                            "hpp" => "exchange__hpp_nppv",
//                            "jml_nilai" => "exchange__sub_hpp_nppv",
                            "hpp" => "exchange__harga",
                            "jml_nilai" => "exchange__sub_harga",
                            "hpp_riil" => "exchange__harga",
                            "jml_nilai_riil" => "exchange__sub_harga",
                            "ppv_riil" => "exchange__ppv",
                            "ppv_nilai_riil" => "exchange__sub_ppv",
                            "nama" => "name",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",

                            "ppn_in" => "exchange__ppn",
                            "ppn_in_nilai" => "exchange__sub_ppn",
                            "suppliers_id" => "pihakID",
                            "suppliers_nama" => "pihakName",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "FifoProdukJadi",
                        "loop" => array(),
                        "static" => array(
                            "unit" => "qty",
                            "produk_id" => "id",
                            "produk_nama" => "name",
//                            "hpp" => "exchange__hpp_nppv",
//                            "jml_nilai" => "exchange__sub_hpp_nppv",
                            "hpp" => "exchange__harga",
                            "jml_nilai" => "exchange__sub_harga",
                            "hpp_riil" => "exchange__harga",
                            "jml_nilai_riil" => "exchange__sub_harga",
                            "ppv_riil" => "exchange__ppv",
                            "ppv_nilai_riil" => "exchange__sub_ppv",
                            "hpp_nppv" => "exchange__hpp_nppv",
                            "jml_nilai_nppv" => "exchange__sub_hpp_nppv",

                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",

                            "ppn_in" => "exchange__ppn",
                            "ppn_in_nilai" => "exchange__sub_ppn",
                            "suppliers_id" => "pihakID",
                            "suppliers_nama" => "pihakName",
                            "produk_jenis" => ".import",
                            "produk_jenis_id" => ".2",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // locker stok reguler
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".active",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "qty_debet" => "qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),


                    //region ERP
                    array(
                        "comName" => "RekeningTransaksiDataPembelian",
                        "loop" => array(
                            "460a" => "-sub_exchange__harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "exchange__harga",
                            "produk_qty" => "-qty",
                            "extern_id" => "currentID",//didefine kena akan dikurangi jika masuk abaikan
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//nulis ke table _rek_penjualan_transkasi_data dan mutasi
                    array(
                        "comName" => "RekeningTransaksiDataPembelian",
                        "loop" => array(
                            "460" => "sub_exchange__harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "exchange__harga",
                            "produk_qty" => "qty",
//                            "extern_id" => "transaksi_id",//masuk tidak perlu extern_id karena baca transaksi_id hasil followup
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//nulis ke table _rek_penjualan_transkasi_data dan mutasi

                    array(
                        "comName" => "RekeningTransaksiDataPembelianCache",
                        "loop" => array(
                            "460a" => "-sub_exchange__harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "exchange__harga",
                            "produk_qty" => "-qty",
                            "extern_id" => "currentID",//tranksi 466r
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//nulis ke table casche produk sebagai index,rekening dan mutasi
                    array(
                        "comName" => "RekeningTransaksiDataPembelianCache",
                        "loop" => array(
                            "460" => "sub_exchange__harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "exchange__harga",
                            "produk_qty" => "qty",
//                            "extern_id" => "transaksi_id",//tranksi 466r
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//nulis ke table casche produk sebagai index,rekening dan mutasi
//
                    array(
                        "comName" => "TransaksiDataPembelian",
                        "loop" => array(
                            "460a" => "-sub_exchange__harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "exchange__harga",
                            "produk_qty" => "-qty",
                            "extern_id" => "currentID",
                            "method" => ".approve",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//untuk update pembelian_transaki_data
                    array(
                        "comName" => "TransaksiDataPembelian",
                        "loop" => array(
                            "460" => "sub_exchange__harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "exchange__harga",
                            "produk_qty" => "qty",
//                            "extern_id" => "transaksi_id",
                            "method" => ".create",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//untuk update pembelian_transaki_data
                    //endregion

                ),
            ),

        ),

        "closedRequest" => array(
            2 => array(
                "enabled" => true,
            ),
        ),
        //-----
        "countersEdit" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|supplierID",
        ),
        "formatNotaEdit" => "stepCode|placeID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|supplierID",
        ),
        "formatNotaReject" => "stepCode|placeID",
        //-----
        "replacerReject" => array(
            "460r" => array(// ini step 1
                "master" => array(
                    "460r" => array(
                        "produk_id" => "referenceID",
                        "produk_nama" => "referenceNomer",
                    ),
                ),
                "detail" => array(
                    "460r" => array(
                        "extern_id" => "referenceID",
                        "extern_nama" => "referenceNomer",
                    ),
                ),
            ),
            "460a" => array(// ini step 2
                "master" => array(
                    "460r" => array(
                        "produk_id" => "referenceID__1",
                        "produk_nama" => "referenceNomer__1",
                    ),
                    "460a" => array(
                        "produk_id" => "currentID",
                        "produk_nama" => "currentNomer",
                    ),
                ),
                "detail" => array(
                    "460r" => array(
                        "produk_id" => "referenceID__1",
                        "produk_nama" => "referenceNomer__1",
                    ),
                    "460a" => array(
                        "produk_id" => "currentID",
                        "produk_nama" => "currentNomer",
                    ),
                ),
            ),

        ),
        //-----
        "postProcessorEdit" => array(
            "460r" => array(
                "master" => array(
                    // mengembalikan originalnya
                    array(
                        "comName" => "RekeningPembantuTransaksiPembelian",// oke
                        "loop" => array(
                            "460r" => "-exchange__harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "transaksi_id",
                            "produk_nama" => "transaksi_no",
                            "produk_nilai" => "exchange__harga",
                            "extern_id" => "pihakID",
                            "method" => ".reject",
                        ),
                        "reversable" => false,
                        "srcGateName" => "mainOriginal",
                        "srcRawGateName" => "mainOriginal",
                    ),//nulis ke table pembelian_pembantu_transaksi_cache dan mutasi


                ),
                "detail" => array(
                    // mengembalikan originalnya
                    array(
                        "comName" => "RekeningTransaksiDataPembelian",
                        "loop" => array(
                            "460r" => "-sub_exchange__harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "exchange__harga",
                            "produk_qty" => "-qty",
                            "extern_id" => "transaksi_id",
                            "method" => ".reject",
                        ),
                        "reversable" => false,
                        "srcGateName" => "itemsOriginal",
                        "srcRawGateName" => "itemsOriginal",
                    ),//nulis ke table _rek_penjualan_transkasi_data dan mutasi
                    array(
                        "comName" => "RekeningTransaksiDataPembelianCache",
                        "loop" => array(
                            "460r" => "-sub_exchange__harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "exchange__harga",
                            "produk_qty" => "-qty",
                            "extern_id" => "transaksi_id",
                            "method" => ".reject",
                        ),
                        "reversable" => false,
                        "srcGateName" => "itemsOriginal",
                        "srcRawGateName" => "itemsOriginal",
                    ),//nulis ke table casche produk sebagai index,rekening dan mutasi
                    array(
                        "comName" => "TransaksiDataPembelian",
                        "loop" => array(
                            "460r" => "-sub_exchange__harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "exchange__harga",
                            "produk_qty" => "-qty",
                            "extern_id" => "transaksi_id",
                            "method" => ".reject",
                        ),
                        "reversable" => true,
                        "srcGateName" => "itemsOriginal",
                        "srcRawGateName" => "itemsOriginal",
                    ),//untuk update pembelian_transaki_data


                ),
            ),
        ),

    ),
    //  config return pembelian finish goods import
    "960" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "supplierID" => "pihakID",
                "supplierName" => "pihakName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "produk_id" => "id",
                "produk_nama" => "nama",

//                "ppn" => "(ppnFactor*harga)/100",
                "ppn" => "(0*harga)/100",
                "hpp_nppn" => "harga+ppn",
                "hpp_nppv" => "harga*ppv_index__nilai",
                "ppv" => "hpp_nppv-harga",
                "hpp_nppn_nppv" => "hpp_nppn+ppv",
                "nett" => "harga+ppn",
            ),
            "rsltItems" => array(//===sumber nilai berupa rincian
                "fifo_riil" => "hpp/1.25",
                "ppv" => "hpp-fifo_riil",
            ),
        ),
        "valueBuilders" => array(
//            "ppv" => "hpp-hpp_riil",
            "selisih_fifo" => "(hpp+ppn)-(nett+ppv)",
        ),
        "valueBuilders_rsltItems" => array(

            "hpp" => "sub_hpp",

        ),
        "preProcessor" => array(
            "960" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => "gudangID",
                        ),
                        "resultParams" => array(
                            "items" => array(
                                "hpp" => "hpp",
                                "hpp_riil" => "hpp_riil",
                                "ppv_riil" => "ppv_riil",
                                "ppn_in" => "ppn_in",
                                "ppn_in_nilai" => "ppn_in_nilai",
                                "suppliers_id" => "suppliers_id",
                                "suppliers_nama" => "suppliers_nama",
                                "hpp_nppv" => "hpp_nppv",
                                "produk_jenis" => "produk_jenis",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "FifoProdukJadi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => "gudangID",
                        ),
                        "resultParams" => array(
                            "rsltItems" => array(
                                "id" => "produk_id",
                                "nama" => "nama",
                                "name" => "nama",
//                                "harga" => "hpp",
                                "hpp" => "hpp",
                                "jml" => "qty",
                                "qty" => "qty",
                                "hpp_riil" => "hpp_riil",
                                "ppv_riil" => "ppv_riil",
                                "subtotal" => "subtotal",
                                "ppn_in" => "ppn_in",
                                "ppn_in_nilai" => "ppn_in_nilai",
                                "suppliers_id" => "suppliers_id",
                                "suppliers_nama" => "suppliers_nama",
                                "hpp_nppv" => "hpp_nppv",
                                "produk_jenis" => "produk_jenis",
                                "produk_jenis_id" => "produk_jenis_id",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
        "tableIn" => array(
            "master" => array(
                "jenis_master" => "jenisTrMaster",
                "jenis_top" => "jenisTrTop",
                "jenis" => "jenisTr",
                "jenis_label" => "jenisTrName",
                "div_id" => "divID",
                "div_nama" => "divName",
                "dtime" => "dtime",
                "fulldate" => "fulldate",
                "oleh_id" => "olehID",
                "oleh_nama" => "olehName",

                "suppliers_id" => "supplierID",
                "suppliers_nama" => "supplierName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "bruto",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",
                "referensi_id" => "referenceID",

                "pembayaran" => "paymentMethod",
                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
            ),

            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
            ),

            "rsltItems" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
            ),
            "rsltItemsValues" => array(
                "harga" => "harga",
                "hpp" => "hpp",
                "ppn" => "ppn",
                "nett" => "nett",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "produk",
            ),
            "detail_rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "produk",
            ),
        ),

        "components" => array(
            "960" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
//                            "1010030030" => "-hpp",//persediaan produk
//                            "1010030040" => "hpp_riil",//persediaan produk riil
//                            "2010090010" => "-ppv_riil",//hutang lain ppv
                            "1010030030" => "-hpp",//persediaan produk
                            "1010030040" => "hpp_riil",//persediaan produk riil
                            "2010090010" => ".0",//hutang lain ppv
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            //                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
//                            "1010030030" => "-hpp",//persediaan produk
//                            "1010030040" => "hpp_riil",//persediaan produk riil
//                            "2010090010" => "-ppv_riil",//hutang lain ppv
                            "1010030030" => "-hpp",//persediaan produk
                            "1010030040" => "hpp_riil",//persediaan produk riil
                            "2010090010" => ".0",//hutang lain ppv
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            //                            "transaksi_no" => "nomer",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030040" => "-hpp_riil",//persediaan produk riil
                            "1010020030" => "exchange__nett",//piutang pembelian
                            "1010040050" => "-ppn",//ppn in
                            "7010050" => "(hpp_riil+ppn)-exchange__nett",//laba(rugi) selisih fifo return pembelian
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            //                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010030040" => "-hpp_riil",//persediaan produk riil
                            "1010020030" => "exchange__nett",//piutang pembelian
                            "1010040050" => "-ppn",//ppn in
                            "7010050" => "(hpp_riil+ppn)-exchange__nett",//laba(rugi) selisih fifo return pembelian
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            //                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    array(
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "1010020030" => "exchange__nett",//piutang pembelian
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            //                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "1010040050" => "-ppn",//ppn in
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            //                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "-sub_hpp",//persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030040" => "sub_hpp_riil",//persediaan produk riil
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030040" => "-sub_hpp_riil",//persediaan produk riil
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "960r" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "TransaksiItemReturnUpdate",
                        "loop" => array(),
                        "static" => array(
                            "produk_jenis" => ".produk",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "transaksi_id" => "referenceID",
                            "seluruhnya" => "seluruhnya",
                            "returnMethod" => "pihakMainName", // by pass diisi metode per-barang atau per-nota
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".active",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => ".0",
                            "nomer" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "transaksi_id",
                            "nomer" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
            "960" => array(
                "master" => array(
                    // post procc payment anti source
                    array(
                        "comName" => "PaymentAntiSourceValas",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "transaksi_id" => ".0",
                            "jenis" => ".0",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".piutang pembelian",
                            "sisa" => "exchange__nett",
                            "sisa_valas" => "nett",
                            "valas_id" => "currencyDetails",
                            "valas_nama" => "currencyDetails__label",
                            "target_jenis" => ".4891",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // fifo valas dari return pembelian avg dan riil
                    array(
                        "comName" => "FifoValasExternReturnAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".valas",
                            "produk_id" => "pihak2ID",
                            "nama" => "currencyDetails__label",
                            "jml" => "nett", // jumlah valas
                            "hpp" => "pihak2Exchange", // kurs valas dari nota grn
                            "jml_nilai" => "exchange__nett",
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "FifoValasExternReturn",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".valas",
                            "produk_id" => "pihak2ID",
                            "produk_nama" => "currencyDetails__label",
                            "unit" => "nett",
                            "hpp" => "pihak2Exchange",
                            "jml_nilai" => "exchange__nett",
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                ),
                "detail" => array(
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => "masterID",
                            "oleh_id" => ".0",
                            "oleh_nama" => "",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".deactivated",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "oleh_nama" => "",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "qty_debet" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
        //-----
        "countersEdit" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|supplierID",
        ),
        "formatNotaEdit" => "stepCode|placeID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|supplierID",
        ),
        "formatNotaReject" => "stepCode|placeID",
    ),
    "1960" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "supplierID" => "pihakID",
                "supplierName" => "pihakName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "produk_id" => "id",
                "produk_nama" => "nama",

                "ppn" => "(ppnFactor*harga)/100",
                "hpp_nppn" => "harga+ppn",
                "hpp_nppv" => "harga*ppv_index__nilai",
                "ppv" => "hpp_nppv-harga",
                "hpp_nppn_nppv" => "hpp_nppn+ppv",
                "nett" => "harga+ppn", // yg dipakai di grand total
            ),
            "master_dependent" => array(
                "paymentMethod" => array(
//                    "cash" => array(
//                        "nilai_cash" => "tagihan",
//                        "nilai_credit" => "0",
//                    ),
                    "credit" => array(
                        "nilai_credit" => "tagihan",
                        "nilai_cash" => "0",
                    ),
                    "cbd" => array(
                        "nilai_credit" => "tagihan",
                        "nilai_cash" => "0",
                    ),
                    "cia" => array(
                        "nilai_credit" => "tagihan",
                        "nilai_cash" => "0",
                    ),
                    "tt_adv" => array(
                        "nilai_credit" => "tagihan",
                        "nilai_cash" => "0",
                    ),
                ),
            ),
        ),
        "valueBuilders" => array(
            "grand_total" => "nett",
            "tagihan" => "grand_total-discount-dp",
        ),
        "preProcessor" => array(),
        "tableIn" => array(
            "master" => array(
                "jenis_master" => "jenisTrMaster",
                "jenis_top" => "jenisTrTop",
                "jenis" => "jenisTr",
                "jenis_label" => "jenisTrName",
                "div_id" => "divID",
                "div_nama" => "divName",
                "dtime" => "dtime",
                "fulldate" => "fulldate",
                "oleh_id" => "olehID",
                "oleh_nama" => "olehName",

                "suppliers_id" => "supplierID",
                "suppliers_nama" => "supplierName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "nett",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",

            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "produk",
            ),
        ),

        "components" => array(),
        "postProcessor" => array(),
        //-----
        "countersEdit" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|supplierID",
        ),
        "formatNotaEdit" => "stepCode|placeID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|supplierID",
        ),
        "formatNotaReject" => "stepCode|placeID",
    ),

);