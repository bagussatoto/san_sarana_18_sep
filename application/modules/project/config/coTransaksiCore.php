<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/22/2018
 * Time: 8:38 PM
 */

$config["coTransaksiCore"] = array(
    "410302" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",
            "stepCode|referenceID",
        ),
//        "formatNota" => "stepCode,fulldate,stepCode|fulldate,placeID,stepCode|placeID,olehID,stepCode|olehID,pihakID,stepCode|pihakID",
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "supplierID" => "pihakID",
                "supplierName" => "pihakName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "produk_id" => "id",
                "produk_nama" => "nama",

                "hpp" => "harga",
//                "ppn" => "(ppnFactor_item*harga)/100",
//                "hpp_nppn" => "harga+ppn",
                "hpp_nppv" => "harga*ppv_index__nilai",
                "ppv" => "hpp_nppv-harga",
//                "hpp_nppv" => ".0",
//                "ppv" => ".0",
                "hpp_nppn_nppv" => "hpp_nppn+ppv",
//                "nett" => "harga+ppn", // yg dipakai di grand total
                //----------------------------
                "diskon_npph_nilai_total" => "diskon_nilai_total-diskon_pph23",
                "laba_lain_lain" => "diskon_nilai_total",
                "ppnFactorDesimal_item"=>"ppnFactor_item/100",
                "ppnFactorInclude_item"=>"(100+ppnFactor_item)/100",
                //----------------------------
                "dpp_pengganti_item" => "harga*(ppnConstanta)",
                "ppn" => "(dpp_pengganti*(dpp_pengganti_item/100))*ppnPersenCheck",
            ),
            "master_dependent" => array(
                "paymentMethod" => array(
                    "credit" => array(
                        "nilai_credit" => "harga",
                        "nilai_cash" => "0",
                    ),
                    "cbd" => array(
                        "nilai_credit" => "harga",
                        "nilai_cash" => "0",
                    ),
                    "cia" => array(
                        "nilai_credit" => "harga",
                        "nilai_cash" => "0",
                    ),
                    "tt_adv" => array(
                        "nilai_credit" => "harga",
                        "nilai_cash" => "0",
                    ),
                ),
            ),
        ),
        /*
 * dpp ppn multi tarif
 * dppPpn_0
 * dppPpn_11
 * dppPpn_12
 */
//        "itemRecapBuildDpp" => array(
//            "items" => array(
//                "ppnFactor_item" => array(
//                    "dppPpn" => "sub_harga",
////                    "dppPpn"=>"sub_nett1",
//                ),
//            ),
//
//
//        ),

        "valueBuilders" => array(
//            "grand_total" => "nett",
//            "tagihan" => "grand_total-discount-dp",
//            "mergerDpp" => "dppPpn_11+dppPpn_12",
//            "ppn_0" => "dppPpn_0*0",
//            "ppn_11" => "dppPpn_11*(11/100)",
//            "ppn_12" => "dppPpn_12*(12/100)",
//            "ppn_out_bulat" => "ppn_11+ppn_12",
//            "ppn" => "ppn_out_bulat",
//            "mergerDpp_nppn" => "mergerDpp+ppn_out_bulat",
//            "selisih_ppn_realisasi" => "nilai_tambah_ppn_in-ppn_realisasi",
//            "new_sisa" =>"nilai_tambah_piutang_pembelian-selisih_ppn_realisasi",
            "dpp_pengganti" => "harga*(ppnConstanta)",
            "ppn" => "(dpp_pengganti*(ppnFactor/100))*ppnPersenCheck",
            "nett" => "harga+ppn",
            "hpp_nppn" => "harga+ppn",
            "grand_total" => "nett",
            "tagihan" => "grand_total",
        ),
        "preProcessor" => array(
            "410302spo" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "ProdukProject",
                        "loop" => array(
                            "project" => "harga",
                        ),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "cabang_nama" => "cabangName",
                            "nama" => "nama",
                            "kode" => "produk_kode",
                            "start_dtime" => "tanggalStart",
                            "end_dtime" => "tenggatWaktu",
                            "harga" => "harga",
                            "customer_id" => "pihakID",
                            "customer_nama" => "pihakName",
                            "spek" => "note",
                            "jenis" => "jenisTr",
                            "create_by_id" => "olehID",
                            "create_by_name" => "olehName",
                        ),
                        "reversable" => true,
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
                "jenis" => "jenis",
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
//                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "ppnPersenCheck" => "ppnPersenCheck",
                "transaksi_bruto" => "hpp_produk",
                "transaksi_net" => "hpp_nppn",
                "ppn_nilai" => "ppn",
                "ppv" => "ppv",
                "hpp" => "hpp_produk",
                "hpp_ppv" => "hpp_nppv_produk",
                "diskon" => "diskon",
                "tos" => "tos",
                "tos_nama" => "tos__nama",
                "top" => "top",
                "top_nama" => "top__nama",
                "pembayaran_sys" => "paymentMethod",
                "ppv_index" => "ppv_index__nilai",

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
                "no_part" => "satuan",
            ),
            "items" => array(
                "handler" => "handler",
                "name" => "name",
                "nama" => "nama",
                "produk_id" => "id",
                "produk_nama" => "nama",
                "produk_kode" => "produk_kode",
                "produk_label" => "produk_label",
                "jml" => "jml",
                "qty" => "qty",
                "discount_qty" => "discount_qty",
                "harga" => "harga",
                "subtotal" => "subtotal",
                "satuan" => "satuan",
                "produk_sku" => "produk_sku",
                "label" => "label",
                "ppn" => "ppn",
                "barcode" => "barcode",
                "jenis" => "jenis",
                "produk_jenis_id" => "produk_jenis_id",
                "produk_jenis_nama" => "produk_jenis_nama",
                "jml_serial" => "jml_serial",
                "kategori_id" => "kategori_id",
                "kategori_nama" => "kategori_nama",
                "part_id_1" => "part_id_1",
                "part_nama_1" => "part_nama_1",
                "part_barcode_1" => "part_barcode_1",
                "part_id_2" => "part_id_2",
                "part_nama_2" => "part_nama_2",
                "part_barcode_2" => "part_barcode_2",
                "heater_id" => "heater_id",
                "heater_nama" => "heater_nama",
                "heater_barcode" => "heater_barcode",
                "outdoor_id" => "outdoor_id",
                "outdoor_nama" => "outdoor_nama",
                "outdoor_barcode" => "outdoor_barcode",
                "outdoor_sku" => "outdoor_sku",
                "indoor_id_1" => "indoor_id_1",
                "indoor_nama_1" => "indoor_nama_1",
                "indoor_barcode_1" => "indoor_barcode_1",
                "indoor_sku_1" => "indoor_sku_1",
                "indoor_id_2" => "indoor_id_2",
                "indoor_nama_2" => "indoor_nama_2",
                "indoor_barcode_2" => "indoor_barcode_2",
                "indoor_sku_2" => "indoor_sku_2",
                "indoor_id_3" => "indoor_id_3",
                "indoor_nama_3" => "indoor_nama_3",
                "indoor_barcode_3" => "indoor_barcode_3",
                "indoor_sku_3" => "indoor_sku_3",
                "indoor_id_4" => "indoor_id_4",
                "indoor_nama_4" => "indoor_nama_4",
                "indoor_barcode_4" => "indoor_barcode_4",
                "indoor_sku_4" => "indoor_sku_4",
                "qty_outdoor" => "qty_outdoor",
                "qty_indoor" => "qty_indoor",
                "keterangan" => "keterangan",
                "static_keterangan" => "static_keterangan",
                "sub_qty_indoor" => "sub_qty_indoor",
                "sub_qty_outdoor" => "sub_qty_outdoor",
                "discPersen" => "discPersen",
                "lastNett" => "lastNett",
                "jual_dipakai" => "jual_dipakai",
                "harga_jual" => "harga_jual",
                "harga_disc" => "harga_disc",
                "discNilai" => "discNilai",
                "scan_mode" => "scan_mode",
                "qty_barcode" => "qty_barcode",
                "nett1" => "nett1",
                "harga_include_ppn" => "harga_include_ppn",
                "nett1_include_ppn" => "nett1_include_ppn",
                "_harga_non_ppn" => "_harga_non_ppn",
                "_diskon_non_ppn" => "_diskon_non_ppn",
                "_harga_ppn" => "_harga_ppn",
                "disc" => "disc",
                "_grand_total" => "_grand_total",
                "nett1_ppn" => "nett1_ppn",
                "sub_harga" => "sub_harga",
                "sub_subtotal" => "sub_subtotal",
                "sub_discount_persen" => "sub_discount_persen",
                "sub_discount_qty" => "sub_discount_qty",
                "sub_harga_jasa" => "sub_harga_jasa",
                "sub_jual_online" => "sub_jual_online",
                "sub_jual" => "sub_jual",
                "sub_jual_reseller" => "sub_jual_reseller",
                "sub_ppn" => "sub_ppn",
                "sub_discPersen" => "sub_discPersen",
                "sub_lastNett" => "sub_lastNett",
                "sub_jual_dipakai" => "sub_jual_dipakai",
                "sub_harga_jual" => "sub_harga_jual",
                "sub_harga_disc" => "sub_harga_disc",
                "sub_discNilai" => "sub_discNilai",
                "sub_qty_barcode" => "sub_qty_barcode",
                "sub_nett1" => "sub_nett1",
                "sub_harga_include_ppn" => "sub_harga_include_ppn",
                "sub__harga_non_ppn" => "sub__harga_non_ppn",
                "sub__diskon_non_ppn" => "sub__diskon_non_ppn",
                "sub__harga_ppn" => "sub__harga_ppn",
                "sub_disc" => "sub_disc",
                "sub__grand_total" => "sub__grand_total",
                "sub_nett1_ppn" => "sub_nett1_ppn",
                "sub_jml_serial" => "sub_jml_serial",
                "harga_jasa" => "harga_jasa",
                "jual_online" => "jual_online",
                "jual" => "jual",
                "jual_reseller" => "jual_reseller",
                "cabang_id" => "placeID",
                "gudang_id" => "gudangID",
                "transaksi_id" => "transaksi_id",
                "ppnFactorDesimal_item"=>"ppnFactorDesimal_item",
                "ppnFactorInclude_item"=>"ppnFactorInclude_item",
                "ppnFactor_item"=>"ppnFactor_item",
            ),
            "items3_sum" => array(
                "produk_id" => "produk_id",
                "produk_kode" => "produk_kode",
                "produk_label" => "produk_label",
                "produk_nama" => "nama",
                "produk_ord_jml" => "jml",
                "produk_ord_kurang" => "jml",
                "produk_satuan" => "satuan",
                "produk_sku" => "produk_kode",
                //----
                "produk_ord_hrg" => "nett1",
                "produk_ord_diskon" => "disc",
                "produk_ord_diskon_persen" => "disc_percent",
                "produk_ord_diskon_khusus" => "",
                "produk_ord_ppn" => "ppn",//ini belum masuk
                "produk_ord_ppn_persen" => "ppnFactor",
                "produk_ord_hpp" => "hpp",
                "produk_ord_hrg_net_ppn" => "nett1_ppn",//harga netto include ppn per unit
                "produk_ord_hrg_sub" => "sub_nett1",//harga netto include ppn(harga*qty)
                "produk_ord_hrg_sub_nppn" => "sub_nett1_include_ppn",//sub harga netto include ppn(harga+ppn*qty)
                "produk_ord_premi" => "",
                "produk_ord_hrg_ori" => "harga",//dari seting aka price
                "produk_ord_hrg_include_ppn" => "harga_include_ppn",//harga seting aka price
                "produk_ord_hrg_exclude_ppn" => "harga",//harga seting aka price
                "cabang_id" => "placeID",
                "gudang_id" => "gudangID",
//                "dtime" => "dtime",
            ),
            "items9_sum" => array(
                "handler" => "handler",
                "name" => "name",
                "nama" => "nama",
                "produk_id" => "produk_id",
                "produk_nama" => "nama",
                "produk_kode" => "produk_kode",
                "produk_label" => "produk_label",
                "jml" => "jml",
                "qty" => "qty",
                "discount_qty" => "discount_qty",
                "harga" => "harga",
                "subtotal" => "subtotal",
                "satuan" => "satuan",
                "produk_sku" => "produk_sku",
                "label" => "label",
                "ppn" => "ppn",
                "barcode" => "barcode",
                "jenis" => "jenis",
                "produk_jenis_id" => "produk_jenis_id",
                "produk_jenis_nama" => "produk_jenis_nama",
                "jml_serial" => "jml_serial",
                "kategori_id" => "kategori_id",
                "kategori_nama" => "kategori_nama",
                "part_id_1" => "part_id_1",
                "part_nama_1" => "part_nama_1",
                "part_barcode_1" => "part_barcode_1",
                "part_id_2" => "part_id_2",
                "part_nama_2" => "part_nama_2",
                "part_barcode_2" => "part_barcode_2",
                "heater_id" => "heater_id",
                "heater_nama" => "heater_nama",
                "heater_barcode" => "heater_barcode",
                "outdoor_id" => "outdoor_id",
                "outdoor_nama" => "outdoor_nama",
                "outdoor_barcode" => "outdoor_barcode",
                "outdoor_sku" => "outdoor_sku",
                "indoor_id_1" => "indoor_id_1",
                "indoor_nama_1" => "indoor_nama_1",
                "indoor_barcode_1" => "indoor_barcode_1",
                "indoor_sku_1" => "indoor_sku_1",
                "indoor_id_2" => "indoor_id_2",
                "indoor_nama_2" => "indoor_nama_2",
                "indoor_barcode_2" => "indoor_barcode_2",
                "indoor_sku_2" => "indoor_sku_2",
                "indoor_id_3" => "indoor_id_3",
                "indoor_nama_3" => "indoor_nama_3",
                "indoor_barcode_3" => "indoor_barcode_3",
                "indoor_sku_3" => "indoor_sku_3",
                "indoor_id_4" => "indoor_id_4",
                "indoor_nama_4" => "indoor_nama_4",
                "indoor_barcode_4" => "indoor_barcode_4",
                "indoor_sku_4" => "indoor_sku_4",
                "qty_outdoor" => "qty_outdoor",
                "qty_indoor" => "qty_indoor",
                "keterangan" => "keterangan",
                "static_keterangan" => "static_keterangan",
                "sub_qty_indoor" => "sub_qty_indoor",
                "sub_qty_outdoor" => "sub_qty_outdoor",
                "discPersen" => "discPersen",
                "lastNett" => "lastNett",
                "jual_dipakai" => "jual_dipakai",
                "harga_jual" => "harga_jual",
                "harga_disc" => "harga_disc",
                "discNilai" => "discNilai",
                "scan_mode" => "scan_mode",
                "qty_barcode" => "qty_barcode",
                "nett1" => "nett1",
                "harga_include_ppn" => "harga_include_ppn",
                "nett1_include_ppn" => "nett1_include_ppn",
                "_harga_non_ppn" => "_harga_non_ppn",
                "_diskon_non_ppn" => "_diskon_non_ppn",
                "_harga_ppn" => "_harga_ppn",
                "disc" => "disc",
                "_grand_total" => "_grand_total",
                "nett1_ppn" => "nett1_ppn",
                "sub_harga" => "sub_harga",
                "sub_subtotal" => "sub_subtotal",
                "sub_discount_persen" => "sub_discount_persen",
                "sub_discount_qty" => "sub_discount_qty",
                "sub_harga_jasa" => "sub_harga_jasa",
                "sub_jual_online" => "sub_jual_online",
                "sub_jual" => "sub_jual",
                "sub_jual_reseller" => "sub_jual_reseller",
                "sub_ppn" => "sub_ppn",
                "sub_discPersen" => "sub_discPersen",
                "sub_lastNett" => "sub_lastNett",
                "sub_jual_dipakai" => "sub_jual_dipakai",
                "sub_harga_jual" => "sub_harga_jual",
                "sub_harga_disc" => "sub_harga_disc",
                "sub_discNilai" => "sub_discNilai",
                "sub_qty_barcode" => "sub_qty_barcode",
                "sub_nett1" => "sub_nett1",
                "sub_harga_include_ppn" => "sub_harga_include_ppn",
                "sub__harga_non_ppn" => "sub__harga_non_ppn",
                "sub__diskon_non_ppn" => "sub__diskon_non_ppn",
                "sub__harga_ppn" => "sub__harga_ppn",
                "sub_disc" => "sub_disc",
                "sub__grand_total" => "sub__grand_total",
                "sub_nett1_ppn" => "sub_nett1_ppn",
                "sub_jml_serial" => "sub_jml_serial",
                "harga_jasa" => "harga_jasa",
                "jual_online" => "jual_online",
                "jual" => "jual",
                "jual_reseller" => "jual_reseller",
                "cabang_id" => "placeID",
                "gudang_id" => "gudangID",
                "transaksi_id" => "transaksi_id",
            ),
            "items10_sum" => array(
                "produk_id" => "produk_id",
                "produk_kode" => "produk_kode",
                "produk_label" => "produk_label",
                "produk_nama" => "nama",
                "produk_ord_jml" => "jml",
                "produk_ord_kurang" => "jml",
                "produk_satuan" => "satuan",
                "produk_sku" => "produk_kode",
                //----
                "produk_ord_hrg" => "nett1",
                "produk_ord_diskon" => "disc",
                "produk_ord_diskon_persen" => "disc_percent",
                "produk_ord_diskon_khusus" => "",
                "produk_ord_ppn" => "ppn",//ini belum masuk
                "produk_ord_ppn_persen" => "ppnFactor",
                "produk_ord_hpp" => "hpp",
                "produk_ord_hrg_net_ppn" => "nett1_ppn",//harga netto include ppn per unit
                "produk_ord_hrg_sub" => "sub_nett1",//harga netto include ppn(harga*qty)
                "produk_ord_hrg_sub_nppn" => "sub_nett1_include_ppn",//sub harga netto include ppn(harga+ppn*qty)
                "produk_ord_premi" => "",
                "produk_ord_hrg_ori" => "harga",//dari seting aka price
                "produk_ord_hrg_include_ppn" => "harga_include_ppn",//harga seting aka price
                "produk_ord_hrg_exclude_ppn" => "harga",//harga seting aka price
                "cabang_id" => "placeID",
                "gudang_id" => "gudangID",
//                "dtime" => "dtime",
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
            "467" => array(
                "master" => array(

                    //region jurnal pertama
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030040" => "harga_produk",//persediaan produk riil
                            "1010040050" => "nilai_tambah_ppn_in",//ppn in belum ada faktur
                            "2010010" => "nilai_tambah_piutang_pembelian",//hutang dagan
                            "1010020030" => "-nilai_dipakai_piutang_pembelian",//piutang pembelian
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
                            "1010030040" => "harga_produk",//persediaan produk riil
                            "1010040050" => "nilai_tambah_ppn_in",//ppn in belum ada faktur
                            "2010010" => "nilai_tambah_piutang_pembelian",//hutang dagang
                            "1010020030" => "-nilai_dipakai_piutang_pembelian",//piutang pembelian
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
                            "1010020030" => "-nilai_dipakai_piutang_pembelian",//piutang pembelian
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
                            "1010040050" => "nilai_tambah_ppn_in",//ppn in belum ada faktur
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
                    //endregion
                    // pembantu hutang dagang (supplier)
                    array(
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "2010010" => "nilai_tambah_piutang_pembelian",//hutang dagang
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

                    //region jurnal kedua pindah persediaan riil ke persediaan(std)
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
//                            "1010030030" => "hpp_nppv",//persediaan produk
//                            "1010030040" => "-harga",//persediaan produk riil
//                            "2010090010" => "ppv",//hutang lain ppv
                            "1010030030" => "harga_produk",//persediaan produk
                            "1010030040" => "-harga_produk",//persediaan produk riil
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
//                            "1010030030" => "hpp_nppv",//persediaan produk
//                            "1010030040" => "-harga",//persediaan produk riil
//                            "2010090010" => "ppv",//hutang lain ppv
                            "1010030030" => "harga_produk",//persediaan produk
                            "1010030040" => "-harga_produk",//persediaan produk riil
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

                    // region mencatat piutang, diskon dari supplier
                    99 => array(
                        "comName" => "Jurnal",
                        "loop" => array(
//                            "1010030030" => "-diskon_npph_nilai_total",// persediaan, diskon_nilai_total*
                            "1010020030" => "diskon_nilai_total",// piutang supplier
                            "7010150" => "laba_lain_lain",// laba lain-lain
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    98 => array(
                        "comName" => "Rekening",
                        "loop" => array(
//                            "1010030030" => "-diskon_npph_nilai_total",// persediaan, diskon_nilai_total*
                            "1010020030" => "diskon_nilai_total",// piutang supplier
                            "7010150" => "laba_lain_lain",// laba lain-lain
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // endregion mencatat piutang, diskon dari supplier

                    //region jurnal diskon bonus produk lain dari vendor
                    97 => array(
                        "comName" => "Jurnal",
                        "loop" => array(
//                            "1010030030" => "-diskon_npph_nilai_total",// persediaan, diskon_nilai_total*
                            "1010020030" => "produk_rel_harga",// piutang supplier
                            "7010150" => "produk_rel_harga",// laba lain-lain
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    96 => array(
                        "comName" => "Rekening",
                        "loop" => array(
//                            "1010030030" => "-diskon_npph_nilai_total",// persediaan, diskon_nilai_total*
                            "1010020030" => "produk_rel_harga",// piutang supplier
                            "7010150" => "produk_rel_harga",// laba lain-lain
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


                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuProdukRiil",
                        "loop" => array(
                            "1010030040" => "sub_harga_produk",//persediaan produk riil
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "produk_nilai" => "harga_produk",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items10_sum",
                        "srcRawGateName" => "items10_sum",
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuProdukRiil",
                        "loop" => array(
                            "1010030040" => "-sub_harga_produk",//persediaan produk riil
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "harga_produk",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items10_sum",
                        "srcRawGateName" => "items10_sum",
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "sub_harga_produk",//persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "produk_nilai" => "harga_produk",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "supplierID" => "pihakID",
                        ),
                        "srcGateName" => "items10_sum",
                        "srcRawGateName" => "items10_sum",
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
                    ),

                    // rekening pembantu piutang supplier, diskon supplier
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierItem",
                        "loop" => array(
                            "1010020030" => "sub_diskon_nilai",// piutang supplier
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
//                            "extern_id" => "diskon_id",
//                            "extern_nama" => "diskon_nama",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                        ),
                        "srcGateName" => "items4_sum",
                        "srcRawGateName" => "items4_sum",
                    ),

                    // rekening pembantu piutang supplier, diskon supplier, supplier
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierDetailItem",
                        "loop" => array(
                            "1010020030" => "sub_diskon_nilai",// piutang supplier
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "extern2_id" => "pihakID",
                            "extern2_nama" => "pihakName",
                            "extern_id" => "diskon_id",
                            "extern_nama" => "diskon_nama",
                        ),
                        "srcGateName" => "items4_sum",
                        "srcRawGateName" => "items4_sum",
                    ),

                    // rekening pembantu piutang supplier, diskon supplier, supplier, transaksi_id
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierDetailTransItem",
                        "loop" => array(
                            "1010020030" => "sub_diskon_nilai",// piutang supplier
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
//                            "extern2_id" => "pihakID",
//                            "extern2_nama" => "pihakName",
//                            "extern_id" => "diskon_id",
//                            "extern_nama" => "diskon_nama",
                            "extern3_id" => "pihakID",// supplier
                            "extern3_nama" => "pihakName",// supplier
                            "extern2_id" => "diskon_id",// jenis diskon
                            "extern2_nama" => "diskon_nama",// jenis diskon
                        ),
                        "srcGateName" => "items4_sum",
                        "srcRawGateName" => "items4_sum",
                    ),
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierDetailTransProdukItem",
                        "loop" => array(
                            "1010020030" => "sub_diskon_nilai",// piutang supplier
                        ),
                        "static" => array(
                            //extern_id diinject di model untuk ambil transaksi_id
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "extern_id" => "diskon_id",// jenis diskon
                            "extern_nama" => "diskon_nama",// jenis diskon
                            "extern2_id" => "pihakID",// supplier
                            "extern2_nama" => "pihakName",// supplier
                            "extern3_id" => "id",// produk yang dapet diskon (ac)
                            "extern3_nama" => "nama",
                            "extern4_id" => "diskon_id",// hadiahnya produknya(kabel,selang)
                            "extern4_nama" => "diskon_nama",// jenis diskon
                            "produk_qty" => ".1",// jenis diskon
                            "produk_nilai" => "diskon_nilai",// jenis diskon
                        ),
                        "srcGateName" => "items4_sum",
                        "srcRawGateName" => "items4_sum",
                    ),
                    // locker stok diskon mempertimbangkan nilai tidak hanya qty
                    array(
                        "comName" => "LockerDiskonValue",
                        "loop" => array(
                            "exec_locker" => "sub_diskon_nilai",//sengaja dipasang kalau kalau tidak punya biar tidak ditulis
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".diskon",
                            "jenis2" => ".diskon",
                            "jenis_locker" => ".stock",
                            "state" => ".active",
                            "jumlah" => ".1",
                            "nilai" => "sub_diskon_nilai",
                            "nilai2" => "sub_diskon_nilai",
                            "nilai_unit" => "sub_diskon_nilai",
                            "produk_id" => "diskon_id",//id diskon
                            "nama" => "diskon_nama",

                            "extern_id" => "diskon_id",//id produk hadiah/jika berupa diskon reguler diisi id diskon
                            "extern_nama" => "diskon_nama",
                            "extern2_id" => "id",//produk yang dibeli
                            "extern2_nama" => "nama",
                            "satuan" => "satuan",
//                            "transaksi_id" => "transaksi_id",
                            "transaksi_no" => "nomer",
                            "nomer" => "nomer",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                            "supplier_id" => "pihakID",
                            "supplier_nama" => "pihakName",
                        ),
                        "srcGateName" => "items4_sum",
                        "srcRawGateName" => "items4_sum",
                    ),

                    //region free produk

                    // rekening pembantu piutang supplier, diskon free produk
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierItem",
                        "loop" => array(
                            "1010020030" => "sub_produk_rel_harga",// piutang supplier
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                        ),
                        "srcGateName" => "items5_sum",
                        "srcRawGateName" => "items5_sum",
                    ),

                    // rekening pembantu piutang supplier, diskon supplier, supplier
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierDetailItem",
                        "loop" => array(
                            "1010020030" => "sub_produk_rel_harga",// piutang supplier
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "extern2_id" => "pihakID",
                            "extern2_nama" => "pihakName",
                            "extern_id" => "per_supplier_diskon_id",
                            "extern_nama" => "per_supplier_diskon_nama",
                        ),
                        "srcGateName" => "items5_sum",
                        "srcRawGateName" => "items5_sum",
                    ),

                    // rekening pembantu piutang supplier, diskon supplier, supplier, transaksi_id,produk,produk diskon
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierDetailTransItem",
                        "loop" => array(
                            "1010020030" => "sub_produk_rel_harga",// piutang supplier
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "extern3_id" => "pihakID",// supplier
                            "extern3_nama" => "pihakName",// supplier
                            "extern2_id" => "per_supplier_diskon_id",// jenis diskon
                            "extern2_nama" => "per_supplier_diskon_nama",// jenis diskon
                        ),
                        "srcGateName" => "items5_sum",
                        "srcRawGateName" => "items5_sum",
                    ),
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierDetailTransProdukItem",
                        "loop" => array(
                            "1010020030" => "sub_produk_rel_harga",// piutang supplier
                        ),
                        "static" => array(
                            //extern_id diinject di model untuk ambil transaksi_id
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "extern_id" => "per_supplier_diskon_id",// jenis diskon
                            "extern_nama" => "per_supplier_diskon_nama",// jenis diskon
                            "extern2_id" => "pihakID",// supplier
                            "extern2_nama" => "pihakName",// supplier
                            "extern3_id" => "produk_id",// produk yang dapet diskon (ac)
                            "extern3_nama" => "produk_nama",// jenis diskon
                            "extern4_id" => "produk_rel_id",// hadiahnya produknya(kabel,selang)
                            "extern4_nama" => "produk_rel_nama",// jenis diskon
                            "produk_qty" => "qty",// jenis diskon
                            "produk_nilai" => "produk_rel_harga",// jenis diskon
                        ),
                        "srcGateName" => "items5_sum",
                        "srcRawGateName" => "items5_sum",
                    ),

                    //endregion

                    //locker diskon free produk
                    array(
                        "comName" => "LockerDiskonValue",
                        "loop" => array(
                            "exec_locker" => "sub_produk_rel_harga",//sengaja dipasang kalau kalau tidak punya biar tidak ditulis
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".diskon",
                            "jenis2" => ".diskon",
                            "jenis_locker" => ".stock",
                            "state" => ".active",
                            "jumlah" => "qty",
                            "nilai" => "sub_produk_rel_harga",
                            "nilai2" => "produk_rel_harga",
                            "nilai_unit" => "produk_rel_harga",
                            "produk_id" => "per_supplier_diskon_id",//id diskon
                            "nama" => "per_supplier_diskon_nama",

                            "extern_id" => "produk_rel_id",//id produk hadiah/jika berupa diskon reguler diisi id diskon
                            "extern_nama" => "produk_rel_nama",
                            "extern2_id" => "produk_id",//produk yang dibeli
                            "extern2_nama" => "produk_nama",
                            "satuan" => "satuan",
//                            "transaksi_id" => "transaksi_id",
                            "transaksi_no" => "nomer",
                            "nomer" => "nomer",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                            "supplier_id" => "pihakID",
                            "supplier_nama" => "pihakName",

                        ),
                        "srcGateName" => "items5_sum",
                        "srcRawGateName" => "items5_sum",
                    ),

                ),
            ),
        ),
        "postProcessor" => array(
            "410302spo" => array(
                "master" => array(
                    array(
                        "comName" => "RekeningPembantuTransaksiProject",// oke
                        "loop" => array(
                            "410302spo" => "harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "transaksi_id",
                            "produk_nama" => "transaksi_no",
                            "produk_nilai" => "harga",
                            "extern_id" => "pihakID",
                        ),
                        "reversable" => false,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),//nulis ke table pembelian_pembantu_transaksi_cache dan mutasi
                    array(
                        "comName" => "ProdukProject",
                        "static" => array(
                            "transaksi_id" => "transaksi_id",
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "project_id" => "projectID",
                            "project_nama" => "projectNama",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningTransaksiDataProject",
                        "loop" => array(
                            "410302spo" => "harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => "qty",
                            "extern_id" => "transaksi_id",
                        ),
                        "reversable" => false,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//nulis ke table _rek_penjualan_transkasi_data dan mutasi
                    array(
                        "comName" => "RekeningTransaksiDataProjectCache",
                        "loop" => array(
                            "410302spo" => "harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => "qty",
                            "extern_id" => "transaksi_id",
                        ),
                        "reversable" => false,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//nulis ke table casche produk sebagai index,rekening dan mutasi
                    array(
                        "comName" => "TransaksiDataProject",
                        "loop" => array(
                            "410302spo" => "harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => "qty",
//                            "extern_id" => "transaksi_id",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//untuk update pembelian_transaki_data
                ),
            ),
            "410302po" => array(
                "master" => array(
                    array(
                        "comName" => "RekeningPembantuTransaksiProject",// oke
                        "loop" => array(
                            "410302spo" => "-harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "currentID",
//                            "produk_nama" => "transaksi_no",
                            "produk_nilai" => "harga",
                            "extern_id" => "pihakID",
                        ),
                        "reversable" => false,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),//nulis ke table pembelian_pembantu_transaksi_cache dan mutasi

                    array(
                        "comName" => "RekeningPembantuTransaksiProject",// oke
                        "loop" => array(
                            "410302po" => "harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
//                            "produk_id" => "transaksi_id",
//                            "produk_nama" => "transaksi_no",
                            "produk_nilai" => "harga",
                            "extern_id" => "pihakID",
                        ),
                        "reversable" => false,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),//nulis ke table pembelian_pembantu_transaksi_cache dan mutasi

                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningTransaksiDataProject",
                        "loop" => array(
                            "410302spo" => "-harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => "-qty",
                            "extern_id" => "currentID",
                        ),
                        "reversable" => false,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//nulis ke table _rek_penjualan_transkasi_data dan mutasi
                    array(
                        "comName" => "RekeningTransaksiDataProject",
                        "loop" => array(
                            "410302po" => "harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => "qty",
//                            "extern_id" => "transaksi_id",
                        ),
                        "reversable" => false,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//nulis ke table _rek_penjualan_transkasi_data dan mutasi

                    array(
                        "comName" => "RekeningTransaksiDataProjectCache",
                        "loop" => array(
                            "410302spo" => "-harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => "-qty",
                            "extern_id" => "currentID",
                        ),
                        "reversable" => false,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//nulis ke table casche produk sebagai index,rekening dan mutasi
                    array(
                        "comName" => "RekeningTransaksiDataProjectCache",
                        "loop" => array(
                            "410302po" => "harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => "qty",
//                            "extern_id" => "transaksi_id",
                        ),
                        "reversable" => false,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//nulis ke table casche produk sebagai index,rekening dan mutasi

                    array(
                        "comName" => "TransaksiDataProject",
                        "loop" => array(
                            "410302spo" => "-harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => "-qty",
                            "extern_id" => "currentID",
                            "method" => ".approve",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//untuk update pembelian_transaki_data

                    array(
                        "comName" => "TransaksiDataProject",
                        "loop" => array(
                            "410302po" => "harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => "qty",
//                            "extern_id" => "transaksi_id",
                            "method" => ".create",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//untuk update pembelian_transaki_data

                ),
            ),

            "466" => array(
                "master" => array(
                    array(
                        "comName" => "RekeningPembantuTransaksiPembelian",
                        "loop" => array(
                            "466r" => "-harga_produk",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "currentID",//klau posisi mengurangi wajib bawa ini
//                            "produk_nama" => "transaksi_no",
                            "produk_nilai" => "harga",
                            "extern_id" => "pihakID",
                        ),
                        "reversable" => false,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),//nulis ke table pembelian_pembantu_transaksi_cache dan mutasi
                    array(
                        "comName" => "RekeningPembantuTransaksiPembelian",
                        "loop" => array(
                            "466" => "harga_produk",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
//                            "produk_id" => "trasnaksi_id",//masuk tidak perlu karena akan diinject pakai transkai+id hasul followup
//                            "produk_nama" => "transaksi_no",
                            "produk_nilai" => "harga",
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
                            "466r" => "-sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
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
                            "466" => "sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
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
                            "466r" => "-sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
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
                            "466" => "sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
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
                            "466r" => "-sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
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
                            "466" => "sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => "qty",
//                            "extern_id" => "transaksi_id",
                            "method" => ".create",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//untuk update pembelian_transaki_data
                    //endregion

//
                    // locker stok free produk
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".freeproduk",
                            "jenis2" => ".freeproduk",
                            "state" => ".hold",
                            "jumlah" => "qty",
                            "produk_id" => "produk_rel_id",
                            "nama" => "produk_rel_nama",
                            "satuan" => "satuan",
                            "transaksi_id" => "masterID",
                            "transaksi_no" => "nomer",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items5_sum",
                        "srcRawGateName" => "items5_sum",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "produk_rel_id",
                            "extern_nama" => "produk_rel_nama",
                            "qty_debet" => "qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items5_sum",
                        "srcRawGateName" => "items5_sum",
                    ),


                ),
            ),
            "467r" => array(
                "master" => array(
                    array(
                        "comName" => "RekeningPembantuTransaksiPembelian",
                        "loop" => array(
                            "466" => "-harga_produk",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "currentID",//klau posisi mengurangi wajib bawa ini
//                            "produk_nama" => "transaksi_no",
                            "produk_nilai" => "harga",
                            "extern_id" => "pihakID",
                        ),
                        "reversable" => false,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),//nulis ke table pembelian_pembantu_transaksi_cache dan mutasi
                    array(
                        "comName" => "RekeningPembantuTransaksiPembelian",
                        "loop" => array(
                            "467r" => "harga_produk",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
//                            "produk_id" => "trasnaksi_id",//masuk tidak perlu karena akan diinject pakai transkai+id hasul followup
//                            "produk_nama" => "transaksi_no",
                            "produk_nilai" => "harga",
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
                            "466" => "-sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
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
                            "467r" => "sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
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
                            "466" => "-sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
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
                            "467r" => "sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => "qty",
//                            "extern_id" => "transaksi_id",//tranksi 466r
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//nulis ke table casche produk sebagai index,rekening dan mutasi

                    array(
                        "comName" => "TransaksiDataPembelian",
                        "loop" => array(
                            "466" => "-sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
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
                            "467r" => "sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => "qty",
//                            "extern_id" => "transaksi_id",
                            "method" => ".create",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//untuk update pembelian_transaki_data
                    // serial number produk
                    array(
                        "comName" => "ProdukSerialNumber",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "produk_serial_number" => "serial_number",
                            "produk_sku" => "produk_sku",
                            "produk_sku_serial" => "produk_sku_serial",
                            "produk_sku_part_id" => "produk_sku_part_id",
                            "produk_sku_part_nama" => "produk_sku_part_nama",
                            "produk_sku_part_serial" => "produk_sku_part_serial",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "supplier_id" => "supplierID",
                            "supplier_nama" => "supplierName",
                            "gudang_id" => "gudangID",
                            //---------------
                            "transaksi_reference_id" => "referenceID",
                            "transaksi_reference_no" => "referenceNomer",
                            "transaksi_reference_dtime" => "referenceDate",
                            "transaksi_reference_fulldate" => "referenceFulldate",
                            "transaksi_reference_count" => "referenceCount",
                            "transaksi_count" => "transaksi_count",
                            "transaksi_jenis_count" => "transaksi_jenis_count",
                            "part_keterangan" => "part_keterangan",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),
                ),
            ),
            "467" => array(
                "master" => array(
                    array(
                        "comName" => "RekeningPembantuTransaksiPembelian",
                        "loop" => array(
                            "467r" => "-harga_produk",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "currentID",//klau posisi mengurangi wajib bawa ini
//                            "produk_nama" => "transaksi_no",
                            "produk_nilai" => "harga",
                            "extern_id" => "pihakID",
                        ),
                        "reversable" => false,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),//nulis ke table pembelian_pembantu_transaksi_cache dan mutasi
                    array(
                        "comName" => "RekeningPembantuTransaksiPembelian",
                        "loop" => array(
                            "467" => "harga_produk",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
//                            "produk_id" => "trasnaksi_id",//masuk tidak perlu karena akan diinject pakai transkai+id hasul followup
//                            "produk_nama" => "transaksi_no",
                            "produk_nilai" => "harga",
                            "extern_id" => "pihakID",
                        ),
                        "reversable" => false,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),//nulis ke table pembelian_pembantu_transaksi_cache dan mutasi
                ),
                "detail" => array(
                    //regionERP
                    array(
                        "comName" => "RekeningTransaksiDataPembelian",
                        "loop" => array(
                            "467r" => "-sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
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
                            "467" => "sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
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
                            "467r" => "-sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
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
                            "467" => "sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => "qty",
//                            "extern_id" => "transaksi_id",//tranksi 466r
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//nulis ke table casche produk sebagai index,rekening dan mutasi

                    array(
                        "comName" => "TransaksiDataPembelian",
                        "loop" => array(
                            "467r" => "-sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
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
                            "467" => "sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => "qty",
//                            "extern_id" => "transaksi_id",
                            "method" => ".create",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//untuk update pembelian_transaki_data


                    //endregion

                    //region nati diobkan kalau sudah jalan full sistem
//                    // menambah persediaan full
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".produk",
                            "jml" => "qty",
                            "produk_id" => "id",
                            "hpp" => "harga_produk",
                            "jml_nilai" => "sub_harga_produk",
                            "hpp_riil" => "harga_produk",
                            "jml_nilai_riil" => "sub_harga_produk",
                            "ppv_riil" => "ppv",
                            "ppv_nilai_riil" => "sub_ppv",
                            "hpp_nppv" => "hpp_nppv",
                            "jml_nilai_nppv" => "sub_hpp_nppv",
                            "nama" => "name",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "ppn_in" => "ppn",
                            "ppn_in_nilai" => "sub_ppn",
                            "suppliers_id" => "pihakID",
                            "suppliers_nama" => "pihakName",
                            "produk_jenis" => ".lokal",
                        ),
                        "srcGateName" => "items10_sum",
                        "srcRawGateName" => "items10_sum",
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
                        "srcGateName" => "items10_sum",
                        "srcRawGateName" => "items10_sum",
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
                            "produk_nilai" => "hpp_produk",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items10_sum",
                        "srcRawGateName" => "items10_sum",
                    ),

                    // INI KHUSUS PRODUK...
                    array(
                        "comName" => "PriceProduk",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "nilai" => "harga_produk",
                            "cabang_id" => "placeID",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => ".produk",
                            "jenis_value" => ".hpp_grn",
                            "jenis_barang" => "jenis_barang",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // last purchase, tabel produk price last purchase
                    array(
                        "comName" => "PriceProdukLastPurchase",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "nilai" => "harga_produk",
                            "cabang_id" => "placeID",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => ".produk",
                            "jenis_value" => ".hpp",
                            "jenis_barang" => "jenis_barang",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // last purchase, tabel price
                    array(
                        "comName" => "PriceProduk",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "nilai" => "harga_produk",
                            "cabang_id" => "placeID",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => ".produk",
                            "jenis_value" => ".hpp",
                            "jenis_barang" => "jenis_barang",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // last purchase ppn, tabel price
                    array(
                        "comName" => "PriceProduk",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "nilai" => "hpp_nppn_produk",
                            "cabang_id" => "placeID",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => ".produk",
                            "jenis_value" => ".hpp_nppn",
                            "jenis_barang" => "jenis_barang",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // harga tandas
                    array(
                        "comName" => "PriceProduk",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "nilai" => "hrg_tandas",
                            "cabang_id" => "placeID",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => ".produk",
                            "jenis_value" => ".hpp_nppv",
                            "jenis_barang" => "jenis_barang",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // harga tandas
                    array(
                        "comName" => "PriceProduk",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "nilai" => "hrg_tandas_npph23",
                            "cabang_id" => "placeID",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => ".produk",
                            "jenis_value" => ".hpp_nppv_pph23",
                            "jenis_barang" => "jenis_barang",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //endregion

                ),
            ),
        ),
        /*
         * README CLI COMPONEN
         * true items dirunning oleh CLI
         * false item langsung diekskusi oleh controller
         */
        "runCliComponentDetail" => false,
        "closedRequest" => array(
            "466" => array(
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
            "466r" => array(// ini step 1
                "master" => array(
                    "466r" => array(
                        "produk_id" => "referenceID",
                        "produk_nama" => "referenceNomer",
                    ),
                ),
                "detail" => array(
                    "466r" => array(
                        "extern_id" => "referenceID",
                        "extern_nama" => "referenceNomer",
                    ),
                ),
            ),
            "466" => array(// ini step 2
                "master" => array(
                    "466r" => array(
                        "produk_id" => "referenceID__1",
                        "produk_nama" => "referenceNomer__1",
                    ),
                    "466" => array(
                        "produk_id" => "currentID",
                        "produk_nama" => "currentNomer",
                    ),
                ),
                "detail" => array(
                    "466r" => array(
                        "produk_id" => "referenceID__1",
                        "produk_nama" => "referenceNomer__1",
                    ),
                    "466" => array(
                        "produk_id" => "currentID",
                        "produk_nama" => "currentNomer",
                    ),
                ),
            ),
            "467r" => array(// ini step 2
                "master" => array(
                    "466r" => array(
                        "produk_id" => "referenceID__2",
                        "produk_nama" => "referenceNomer__2",
                    ),
                    "466" => array(
                        "produk_id" => "currentID",
                        "produk_nama" => "currentNomer",
                    ),
                ),
                "detail" => array(
                    "466r" => array(
                        "produk_id" => "referenceID__2",
                        "produk_nama" => "referenceNomer__2",
                    ),
                    "466" => array(
                        "produk_id" => "currentID",
                        "produk_nama" => "currentNomer",
                    ),
                ),
            ),
        ),
        //-----
        "postProcessorEdit" => array(
            "466r" => array(
                "master" => array(
                    // mengembalikan originalnya
                    array(
                        "comName" => "RekeningPembantuTransaksiPembelian",// oke
                        "loop" => array(
                            "466r" => "-harga_produk",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "transaksi_id",
                            "produk_nama" => "transaksi_no",
                            "produk_nilai" => "harga",
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
                            "466r" => "-sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
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
                            "466r" => "-sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
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
                            "466r" => "-sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
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

    "967" => array(
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
                "ppn" => "(ppnFactor*harga)/100",
                "hpp_nppn" => "harga+ppn",
                "hpp_nppv" => "harga*ppv_index__nilai",
                "ppv" => "hpp_nppv-harga",
                "hpp_nppn_nppv" => "hpp_nppn+ppv",
                "nett" => "harga+ppn",
            ),
            "rsltItems" => array(//===sumber nilai berupa rincian
//                "fifo_riil" => "hpp/1.25",
//                "ppv" => "hpp-fifo_riil",
            ),
        ),
        "valueBuilders" => array(
//            "ppv" => "hpp-hpp_riil",
//            "selisih_fifo" => "(hpp+ppn)-(nett+ppv)",
            "selisih_fifo" => "(hpp+ppn)-(nett)",
        ),
        "valueBuilders_rsltItems" => array(//            "hpp" => "sub_hpp",

        ),
        "preProcessor" => array(
            "967sc" => array(
                "master" => array(
                    //untuk reguler terbit items3_sum
                    array(
                        "comName" => "ProdukSerialNumberExtractor",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "jenisTr" => "jenisTrMaster",
                            "step_number" => "step_number",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
            "967" => array(
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
//                "jenis" => "jenisTr",
                "jenis" => "jenis",
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
            "967" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030030" => "-hpp",//persediaan produk
                            "1010030040" => "hpp",//persediaan produk riil
//                            "laba(rugi) selisih fifo return pembelian" => "selisih_fifo",
//                            "2010090010" => "-ppv_riil",//hutang lain ppv
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
                            "1010030030" => "-hpp",//persediaan produk
                            "1010030040" => "hpp",//persediaan produk riil
                            //                            "laba(rugi) selisih fifo return pembelian" => "selisih_fifo",
//                            "2010090010" => "-ppv_riil",//hutang lain ppv
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
//                            "persediaan produk"                        => "-hpp",
                            "1010030040" => "-hpp",//persediaan produk riil
                            "1010020030" => "nett",//piutang pembelian
                            "1010040050" => "-ppn",//ppn in belum ada faktur
//                            "7010050" => "(hpp_riil+ppn)-nett",//laba(rugi) selisih fifo return pembelian
                            "7010050" => "nett-(hpp+ppn)",//laba(rugi) selisih fifo return pembelian
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
                            //                            "persediaan produk"                        => "-hpp",
                            "1010030040" => "-hpp",//persediaan produk riil
                            "1010020030" => "nett",//piutang pembelian
                            "1010040050" => "-ppn",//ppn in belum ada faktur
//                            "7010050" => "(hpp_riil+ppn)-nett",//laba(rugi) selisih fifo return pembelian
                            "7010050" => "nett-(hpp+ppn)",//laba(rugi) selisih fifo return pembelian
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
                        "comName" => "RekeningPembantuPiutangSupplierMain",
                        "loop" => array(
                            "1010020030" => "nett",//piutang pembelian
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "extern_id" => ".1010020030010",
//                            "extern_nama" => ".Return Pembelian",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierDetailMain",
                        "loop" => array(
                            "1010020030" => "nett",//piutang pembelian
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".1010020030010",
                            "extern_nama" => ".Return Pembelian",
                            "extern2_id" => "pihakID",
                            "extern2_nama" => "pihakName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "1010040050" => "-ppn",//ppn in bekum ada faktur
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
                    //<editor-fold desc="Post-rekening pembantu, detail">
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
                            //							"produk_nilai" => "harga",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            //                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030040" => "sub_hpp",//persediaan produk riil
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            //							"produk_nilai" => "harga",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            //                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030040" => "-sub_hpp",//persediaan produk riil
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-qty",
                            //							"produk_nilai" => "harga",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            //                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //</editor-fold>

                    // rekening pembantu produk serial
                    array(
                        "comName" => "RekeningPembantuProdukPerSerial",
                        "loop" => array(
                            "1010030030" => ".-1",//persediaan produk, sub_diskon_nilai_total
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "extern_id" => ".0",
                            "extern_nama" => "produk_serial",
                            "extern2_id" => ".0",
                            "extern2_nama" => "produk_sku_part_nama",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "produk_qty" => "-jml",
                            "produk_nilai" => ".1",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "supplierID" => "pihakID",
                            "kategori_id" => "kategori_id",//ini untuk skip produk jasa
                        ),
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "967r" => array(
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
            "967" => array(
                "master" => array(
                    // post procc payment anti source
                    array(
                        "comName" => "PaymentAntiSource",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "transaksi_id" => ".0",
                            "jenis" => ".0",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
//                            "label" => ".hutang dagang",
                            "label" => ".piutang pembelian",
                            "sisa" => "nett",
                        ),
                        "reversable" => true,
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
                    array(
                        "comName" => "ProdukSerialNumberLocker",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "produk_serial_number" => "produk_serial",
                            "jumlah" => ".0",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "qty_debet" => "-qty",
//                            "produk_nilai" => "hpp",
//                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
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
    "1967" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|placeID|olehID|supplierID",
            "stepCode|supplierID",
            "stepCode|placeID|supplierID",
            "stepCode|olehID|supplierID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "supplierID" => "pihakID",
                "supplierName" => "pihakName",

            ),
            "detail" => array(//===sumber nilai berupa rincian
                "ppn" => "(ppnFactor*harga)/100",
                "hpp_nppn" => "harga+ppn",
                "hpp_nppv" => "harga*ppv_index__nilai",
                "ppv" => "hpp_nppv-harga",
                "hpp_nppn_nppv" => "hpp_nppn+ppv",
                "nett" => "harga+ppn",
            ),
            "rsltItems" => array(//===sumber nilai berupa rincian

            ),
        ),
        "valueBuilders" => array(
            "grand_total" => "harga+ppn",
            "tagihan" => "grand_total-discount",
        ),
        "valueBuilders_rsltItems" => array(),
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
        "postProcessor" => array(
            "master" => array(
//                array(
//                    "comName" => "Jurnal_activity",
//                    "loop" => array(//                            "activity" => ".1",
//                    ),
//                    "static" => array(
////                            "cabang_id" => "placeID",
////                            "cabang_nama" => "placeName",
////                            "cabang2_id" => "placeID",
////                            "cabang2_nama" => "placeName",
////                            "oleh_id" => "olehID",
////                            "oleh_nama" => "olehName",
////                            "jenis" => "jenisTr",
////                            "jenis_master" => "jenisTrMaster",
////                            "jenis_top" => "jenisTrTop",
////                            "master_id" => "transaksi_id",
////                            "step_number" => ".1",
//                    ),
//                    "srcGateName" => "main",
//                    "srcRawGateName" => "main",
//                ),
            ),
            "detail" => array(),
        ),


    ),

    //config pre request supplies from cabang to DC
    "1763" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabang2ID",
            "stepCode|placeID|cabang2ID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "pihakID" => "cabang2ID",
                "pihakName" => "cabang2Name",
                "gudang" => "gudang2ID",
                "gudang__label" => "gudang2Name",
                "gudang__name" => "gudang2Name",
                "gudang2Name" => "gudang2Name",

            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
            "rsltItems" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //"berat"         => "berat",
                //"lebar"         => "lebar",
                //"panjang"       => "panjang",
                //"tinggi"        => "tinggi",
                //"volume"        => "volume",
                //                "berat_gross" => "berat_gross",
                //                "lebar_gross" => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross" => "tinggi_gross",
                //                "volume_gross" => "volume_gross",
                //
                //                "hpp" => "hpp",
                //                "harga" => "harga",
                //                "sub_hpp" => "sub_hpp",
                //                "sub_harga" => "sub_harga",
                //
                //                "pihakID" => "pihakID",
                //                "pihakName" => "pihakName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
            ),
        ),
        "valueBuilders" => array(),
        "valueBuilders_rsltItems" => array(),
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

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "hpp",

                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "pihakID" => "place2ID",
                "pihakName" => "place2Name",
                "pihakName2" => "place2Name",
                "cabang2ID" => "place2ID",
                "cabang2Name" => "place2ID",
                "place2ID" => "place2ID",
                "place2Name" => "place2ID",

                "gudang" => "gudangID",
                "gudang__label" => "gudang2Name",
                "gudang__name" => "gudang2Name",
            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                //                "ppn" => "ppn",
                // "produk_ord_diskon",
                // "produk_hrg_ori",
                // "produk_hrg_gap",
                "satuan" => "satuan",
            ),
            "rsltItems" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                //                "ppn" => "ppn",
                // "produk_ord_diskon",
                // "produk_hrg_ori",
                // "produk_hrg_gap",
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
            "rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "produk",
            ),
        ),
        "components" => array(),
        "postProcessor" => array(),
    ),
    "11763" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabang2ID",
            "stepCode|placeID|cabang2ID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "pihakID" => "cabang2ID",
                "pihakName" => "cabang2Name",
                "gudang" => "gudang2ID",
                "gudang__label" => "gudang2Name",
                "gudang__name" => "gudang2Name",
                "gudang2Name" => "gudang2Name",

            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
            "rsltItems" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //"berat"         => "berat",
                //"lebar"         => "lebar",
                //"panjang"       => "panjang",
                //"tinggi"        => "tinggi",
                //"volume"        => "volume",
                //                "berat_gross" => "berat_gross",
                //                "lebar_gross" => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross" => "tinggi_gross",
                //                "volume_gross" => "volume_gross",
                //
                //                "hpp" => "hpp",
                //                "harga" => "harga",
                //                "sub_hpp" => "sub_hpp",
                //                "sub_harga" => "sub_harga",
                //
                //                "pihakID" => "pihakID",
                //                "pihakName" => "pihakName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
            ),
        ),
        "valueBuilders" => array(),
        "valueBuilders_rsltItems" => array(),
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

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "hpp",

                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "pihakID" => "place2ID",
                "pihakName" => "place2Name",
                "pihakName2" => "place2Name",
                "cabang2ID" => "place2ID",
                "cabang2Name" => "place2ID",
                "place2ID" => "place2ID",
                "place2Name" => "place2ID",

                "gudang" => "gudangID",
                "gudang__label" => "gudang2Name",
                "gudang__name" => "gudang2Name",
            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                //                "ppn" => "ppn",
                // "produk_ord_diskon",
                // "produk_hrg_ori",
                // "produk_hrg_gap",
                "satuan" => "satuan",
            ),
            "rsltItems" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                //                "ppn" => "ppn",
                // "produk_ord_diskon",
                // "produk_hrg_ori",
                // "produk_hrg_gap",
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
            "rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "produk",
            ),
        ),
        "components" => array(),
        "postProcessor" => array(),
    ),

    //supplies
    "461" => array(
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
                "gudang2ID" => "gudang2",
                "gudang2Name" => "gudang2__nama",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "disc" => "(discPersen*harga)/100",
                "harga_disc" => "harga-disc",
                //                "ppn" => "(ppnFactor*harga)/100",
                "ppnPersen" => "ppnFactor",
//                "ppn" => "(ppnPersen*harga_disc)/100",
//                "ppn" => "(ppnFactor*harga_disc)/100",

                //----------------------------
                "dpp_pengganti_item" => "harga_disc*(ppnConstanta)",
                "ppn" => "(dpp_pengganti*(dpp_pengganti_item/100))*ppnPersenCheck",

                "hpp_nppn" => "harga_disc+ppn",
                "hpp_nppv" => "harga_disc*ppv_index__nilai",
                "ppv" => "hpp_nppv-harga_disc",
                "hpp_nppn_nppv" => "hpp_nppn+ppv",
                "nett" => "hpp_nppn",
            ),
            "master_dependent" => array(
                "paymentMethod" => array(
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
            "dpp_pengganti" => "harga_disc*(ppnConstanta)",
            "ppn" => "(dpp_pengganti*(ppnFactor/100))*ppnPersenCheck",
            "nett" => "harga_disc+ppn",
            "hpp_nppn" => "harga_disc+ppn",

            "grand_total" => "nett",
            "tagihan" => "grand_total-discount-dp",
        ),
        "preProcessor" => array(
            "461" => array(
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
//                            "nilai" => "ppn",
                            "nilai" => ".0",
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
//                            "nilai" => "tagihan-nilai_dipakai_ppn_in",
                            "nilai" => "harga_disc-nilai_dipakai_ppn_in",
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
                "transaksi_nilai" => "harga",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "gudang2_id" => "gudang2ID",
                "gudang2_nama" => "gudang2Name",
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
                "keterangan" => "note",
            ),

        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "supplies",
            ),
        ),

        "components" => array(
            "461" => array(
                "master" => array(
                    //region jurnal pertama
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030020" => "harga_disc",//persediaan supplies riil
                            "1010040050" => "nilai_tambah_ppn_in",//ppn in
                            "2010010" => "nilai_tambah_piutang_pembelian",//hutang dagang
                            "1010020030" => "-nilai_dipakai_piutang_pembelian",//piutang pembelian
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
                            "1010030020" => "harga_disc",//persediaan supplies riil
                            "1010040050" => "nilai_tambah_ppn_in",//ppn in
                            "2010010" => "nilai_tambah_piutang_pembelian",//hutang dagang
                            "1010020030" => "-nilai_dipakai_piutang_pembelian",//piutang pembelian
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
                            //                            "hutang dagang" => "nilai_tambah_piutang_pembelian",
                            "1010020030" => "-nilai_dipakai_piutang_pembelian",//piutang pembelian
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
                            "2010010" => "nilai_tambah_piutang_pembelian",//hutang dagang
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
                            "1010040050" => "nilai_tambah_ppn_in",//ppn in
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

                    /*
 * dimatikan karena detail hutang dagang lokal/import belum digenerate
 * tujuan untuk memisah kategori hutang dagang
 * 22 desember 2022*/
                    // pembantu hutang dagang (lokal / import)
//                    array(
//                        "comName" => "RekeningPembantuSupplierJenis",
//                        "loop" => array(
//                            "2010010" => "nilai_tambah_piutang_pembelian",//hutang dagang
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => ".2010010010",
//                            "extern_nama" => ".lokal",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    // pembantu hutang dagang (lokal/import dengan supplier)

//                    array(
//                        "comName" => "RekeningPembantuSupplierSubJenis",
//                        "loop" => array(
//                            "2010010" => "nilai_tambah_piutang_pembelian",//hutang dagang
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => ".2010010010",
//                            "extern_nama" => ".lokal",
//                            "extern2_id" => "pihakID",
//                            "extern2_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    //endregion

                    //region jurnal kedua pindah persediaan riil ke persediaan(std)
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
//                            "1010030010" => "hpp_nppv",//persediaan supplies
//                            "1010030020" => "-harga_disc",//persediaan supplies riil
//                            "2010090010" => "ppv",//hutang lain ppv
                            "1010030010" => "harga_disc",//persediaan supplies
                            "1010030020" => "-harga_disc",//persediaan supplies riil
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
//                            "1010030010" => "hpp_nppv",//persediaan supplies
//                            "1010030020" => "-harga_disc",//persediaan supplies riil
//                            "2010090010" => "ppv",//hutang lain ppv
                            "1010030010" => "harga_disc",//persediaan supplies
                            "1010030020" => "-harga_disc",//persediaan supplies riil
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
                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuSuppliesRiil",
                        "loop" => array(
                            "1010030020" => "sub_harga_disc",//persediaan supplies riil
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "produk_nilai" => "harga_disc",
                            "gudang_id" => "gudangID",
                            //                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuSuppliesRiil",
                        "loop" => array(
                            "1010030020" => "-sub_harga_disc",//persediaan supplies riil
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "harga_disc",
                            "gudang_id" => "gudangID",
                            //                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
//                            "1010030010" => "sub_hpp_nppv",//persediaan supplies
                            "1010030010" => "sub_harga_disc",//persediaan supplies
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
//                            "produk_nilai" => "hpp_nppv",
                            "produk_nilai" => "harga_disc",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
//            "112" => array(
//                "master" => array(
//                    //region seleish ppn 10 vs 11 %
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
////                            "1010040050" => "-selisih_ppn_realisasi",//ppn in
////                            "2010010" => "-selisih_ppn_realisasi",//hutang dagang
//                            "1010040050" => "selisih_ppn_realisasi*-1",
//                            "2010010" => "selisih_ppn_realisasi*-1",
//
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "1010040050" => "selisih_ppn_realisasi*-1",
//                            "2010010" => "selisih_ppn_realisasi*-1",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuSupplier",
//                        "loop" => array(
//                            "2010010" => "selisih_ppn_realisasi*-1",//hutang dagang
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuSupplier",
//                        "loop" => array(
//                            "1010040050" => "selisih_ppn_realisasi*-1",//ppn in
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    /*
//                     * dimatikan karena detail hutang dagang lokal/import belum digenerate
//                     * tujuan untuk memisah kategori hutang dagang
//                     * 22 desember 2022*/
//                    // pembantu hutang dagang (lokal / import)
//
////                    array(
////                        "comName" => "RekeningPembantuSupplierJenis",
////                        "loop" => array(
////                            "2010010" => "selisih_ppn_realisasi*-1",//hutang dagang
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "extern_id" => ".2010010010",
////                            "extern_nama" => ".lokal",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
//
//                    // pembantu hutang dagang (lokal/import dengan supplier)
//
////                    array(
////                        "comName" => "RekeningPembantuSupplierSubJenis",
////                        "loop" => array(
////                            "2010010" => "selisih_ppn_realisasi*-1",//hutang dagang
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "extern_id" => ".2010010010",
////                            "extern_nama" => ".lokal",
////                            "extern2_id" => "pihakID",
////                            "extern2_nama" => "pihakName",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
//
//                    //endregion
//
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "1010040050" => "-ppn_realisasi",//ppn in
//                            "1010040060" => "ppn_realisasi",//ppn in realisasi
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "1010040050" => "-ppn_realisasi",//ppn in
//                            "1010040060" => "ppn_realisasi",//ppn in realisasi
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuSupplier",
//                        "loop" => array(
//                            "1010040050" => "-ppn_realisasi",//ppn in
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                ),
//                "detail" => array(),
//            ),
        ),
        "postProcessor" => array(
            "461ro" => array(
                "master" => array(
                    array(
                        "comName" => "RekeningPembantuTransaksiPembelian",// oke
                        "loop" => array(
                            "461ro" => "harga_disc",
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
                            "461ro" => "sub_harga_disc",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga_disc",
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
                            "461ro" => "sub_harga_disc",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga_disc",
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
                            "461ro" => "sub_harga_disc",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga_disc",
                            "produk_qty" => "qty",
                            "extern_id" => "transaksi_id",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//untuk update pembelian_transaki_data
                ),
            ),
            "461r" => array(
                "master" => array(
                    array(
                        "comName" => "RekeningPembantuTransaksiPembelian",
                        "loop" => array(
                            "461ro" => "-harga_disc",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "currentID",//klau posisi mengurangi wajib bawa ini
//                            "produk_nama" => "transaksi_no",
                            "produk_nilai" => "harga_disc",
                            "extern_id" => "pihakID",
                        ),
                        "reversable" => false,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),//nulis ke table pembelian_pembantu_transaksi_cache dan mutasi
                    array(
                        "comName" => "RekeningPembantuTransaksiPembelian",
                        "loop" => array(
                            "461r" => "harga_disc",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
//                            "produk_id" => "trasnaksi_id",//masuk tidak perlu karena akan diinject pakai transkai+id hasul followup
//                            "produk_nama" => "transaksi_no",
                            "produk_nilai" => "harga_disc",
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
                            "461ro" => "-sub_harga_disc",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga_disc",
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
                            "461r" => "sub_harga_disc",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga_disc",
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
                            "461ro" => "-sub_harga_disc",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga_disc",
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
                            "461r" => "sub_harga_disc",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga_disc",
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
                            "461ro" => "-sub_harga_disc",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga_disc",
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
                            "461r" => "sub_harga_disc",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga_disc",
                            "produk_qty" => "qty",
//                            "extern_id" => "transaksi_id",
                            "method" => ".create",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//untuk update pembelian_transaki_data
                    //endregion


                    array(
                        "comName" => "PriceSupplies",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "nilai" => "harga",
                            "cabang_id" => "placeID",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => ".supplies",
                            "jenis_value" => ".hpp",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
            "461" => array(
                "master" => array(
                    array(
                        "comName" => "RekeningPembantuTransaksiPembelian",
                        "loop" => array(
                            "461r" => "-harga_disc",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "currentID",//klau posisi mengurangi wajib bawa ini
//                            "produk_nama" => "transaksi_no",
                            "produk_nilai" => "harga_disc",
                            "extern_id" => "pihakID",
                        ),
                        "reversable" => false,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),//nulis ke table pembelian_pembantu_transaksi_cache dan mutasi
                    array(
                        "comName" => "RekeningPembantuTransaksiPembelian",
                        "loop" => array(
                            "461" => "harga_disc",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
//                            "produk_id" => "trasnaksi_id",//masuk tidak perlu karena akan diinject pakai transkai+id hasul followup
//                            "produk_nama" => "transaksi_no",
                            "produk_nilai" => "harga_disc",
                            "extern_id" => "pihakID",
                        ),
                        "reversable" => false,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),//nulis ke table pembelian_pembantu_transaksi_cache dan mutasi



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
                            "nilai" => "-nilai_dipakai_ppn_in",
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
                            "nilai" => "-nilai_dipakai_piutang_pembelian",
                            "transaksi_id" => "currentID",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(

                    //region ERP
                    array(
                        "comName" => "RekeningTransaksiDataPembelian",
                        "loop" => array(
                            "461r" => "-sub_harga_disc",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga_disc",
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
                            "461" => "sub_harga_disc",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga_disc",
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
                            "461r" => "-sub_harga_disc",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga_disc",
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
                            "461" => "sub_harga_disc",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga_disc",
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
                            "461r" => "-sub_harga_disc",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga_disc",
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
                            "461" => "sub_harga_disc",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga_disc",
                            "produk_qty" => "qty",
//                            "extern_id" => "transaksi_id",
                            "method" => ".create",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//untuk update pembelian_transaki_data
                    //endregion



                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".active",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                            //                            "gudang_id" => "gudang2ID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".supplies",
                            "jml" => "qty",
                            "produk_id" => "id",
                            "hpp" => "harga_disc",
                            "jml_nilai" => "sub_harga_disc",
                            "hpp_riil" => "harga_disc",
                            "jml_nilai_riil" => "sub_harga_disc",
                            "ppv_riil" => "ppv",
                            "ppv_nilai_riil" => "sub_ppv",
                            "hpp_nppv" => "hpp_nppv",
                            "jml_nilai_nppv" => "sub_hpp_nppv",
                            "nama" => "name",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "ppn_in" => "ppn",
                            "ppn_in_nilai" => "sub_ppn",
                            "suppliers_id" => "pihakID",
                            "suppliers_nama" => "pihakName",
                            "produk_jenis" => ".lokal",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasiSupplies",
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
                    array(
                        "comName" => "PriceSupplies",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "nilai" => "hpp_nppv",
                            "cabang_id" => "placeID",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => ".supplies",
                            "jenis_value" => ".hpp_nppv",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "PriceSupplies",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "nilai" => "harga",
                            "cabang_id" => "placeID",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => ".supplies",
                            "jenis_value" => ".hpp_grn",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),

        "closedRequest" => array(
            "461r" => array(
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
            "461ro" => array(// ini step 1
                "master" => array(
                    "461ro" => array(
                        "produk_id" => "referenceID",
                        "produk_nama" => "referenceNomer",
                    ),
                ),
                "detail" => array(
                    "461ro" => array(
                        "extern_id" => "referenceID",
                        "extern_nama" => "referenceNomer",
                    ),
                ),
            ),
            "461r" => array(// ini step 2
                "master" => array(
                    "461ro" => array(
                        "produk_id" => "referenceID__1",
                        "produk_nama" => "referenceNomer__1",
                    ),
                    "461r" => array(
                        "produk_id" => "currentID",
                        "produk_nama" => "currentNomer",
                    ),
                ),
                "detail" => array(
                    "461ro" => array(
                        "produk_id" => "referenceID__1",
                        "produk_nama" => "referenceNomer__1",
                    ),
                    "461r" => array(
                        "produk_id" => "currentID",
                        "produk_nama" => "currentNomer",
                    ),
                ),
            ),

        ),
        //-----
        "postProcessorEdit" => array(
            "461ro" => array(
                "master" => array(
                    // mengembalikan originalnya
                    array(
                        "comName" => "RekeningPembantuTransaksiPembelian",// oke
                        "loop" => array(
                            "461ro" => "-harga_disc",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "transaksi_id",
                            "produk_nama" => "transaksi_no",
                            "produk_nilai" => "harga",
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
                            "461ro" => "-sub_harga_disc",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
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
                            "461ro" => "-sub_harga_disc",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
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
                            "461ro" => "-sub_harga_disc",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
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
    //  config return pembelian supplies
    "961" => array(
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
                "ppn" => "(ppnFactor*harga)/100",
                "hpp_nppn" => "harga+ppn",
                "hpp_nppv" => "harga*ppv_index__nilai",
                "ppv" => "hpp_nppv-harga",
                "hpp_nppn_nppv" => "hpp_nppn+ppv",
                "nett" => "harga+ppn",
            ),
            "detail_rsltItems" => array(//===sumber nilai berupa rincian

            ),
        ),
        "valueBuilders" => array(
            //            "bruto" => "sub_harga",
            //            "ppn" => "sub_ppn",
            //            "nett" => "sub_nett",
        ),
        "valueBuilders_rsltItems" => array(
            //            "bruto" => "sub_harga",
            //            "ppn"   => "sub_ppn",
            "hpp" => "sub_hpp",
            //            "nett"  => "sub_nett",
        ),
        "preProcessor" => array(
            "961" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoAverageSupplies",
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
//                    array(
//                        "comName" => "FifoSupplies",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "produk_qty" => "qty",
//                            "gudang_id" => "gudangID",
//                        ),
//                        "resultParams" => array(
//                            "rsltItems" => array(
//                                "id" => "produk_id",
//                                "nama" => "nama",
//                                "name" => "nama",
////                                "harga" => "hpp",
//                                "hpp" => "hpp",
//                                "jml" => "qty",
//                                "qty" => "qty",
//                                "hpp_riil" => "hpp_riil",
//                                "ppv_riil" => "ppv_riil",
//                                "subtotal" => "subtotal",
//                                "ppn_in" => "ppn_in",
//                                "ppn_in_nilai" => "ppn_in_nilai",
//                                "suppliers_id" => "suppliers_id",
//                                "suppliers_nama" => "suppliers_nama",
//                                "hpp_nppv" => "hpp_nppv",
//                                "produk_jenis" => "produk_jenis",
//                                "produk_jenis_id" => "produk_jenis_id",
//                            ),
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
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
                "produk_jenis" => "supplies",
            ),
            "detail_rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "supplies",
            ),
        ),

        "components" => array(
            "961" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030010" => "-hpp",//persediaan supplies
                            "1010030020" => "hpp_riil",//persediaan supplies riil
                            "2010090010" => ".0",//hutang lain ppv
//                            "2010090010" => "-ppv_riil",//hutang lain ppv
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
                            "1010030010" => "-hpp",//persediaan supplies
                            "1010030020" => "hpp_riil",//persediaan supplies riil
                            "2010090010" => ".0",//hutang lain ppv
//                            "2010090010" => "-ppv_riil",//hutang lain ppv
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            //                            "transaksi_no" => "nomer",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //<editor-fold desc="Com-jurnal dan rekening">
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030020" => "-hpp_riil",//persediaan supplies riil
                            "1010020030" => "nett",//piutang pembelian
                            "1010040050" => "-ppn",//ppn in
                            "7010050" => "(hpp_riil+ppn)-nett",//laba(rugi) selisih fifo return pembelian
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
                            "1010030020" => "-hpp_riil",//persediaan supplies riil
                            "1010020030" => "nett",//piutang pembelian
                            "1010040050" => "-ppn",//ppn in
                            "7010050" => "(hpp_riil+ppn)-nett",//laba(rugi) selisih fifo return pembelian
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            //                            "transaksi_no" => "nomer",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //</editor-fold>

                    //<editor-fold desc="Com-rekening pembantu">
                    array(
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
//                            "hutang dagang" => "-nett",
                            "1010020030" => "nett",//piutang pembelian
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
                    //</editor-fold>
                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "1010030010" => "-sub_hpp",//persediaan supplies
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "961r" => array(
                "master" => array(),
                "detail" => array(
                    //<editor-fold desc="Post-Item return update">
                    array(
                        "comName" => "TransaksiItemReturnUpdate",
                        "loop" => array(),
                        "static" => array(
                            "produk_jenis" => ".supplies",
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
                    //</editor-fold>
                    //<editor-fold desc="Post-locker stock supplies">
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
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
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
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
                    //</editor-fold>
                ),
            ),
            "961" => array(
                "master" => array(
                    // post procc payment anti source
                    array(
                        "comName" => "PaymentAntiSource",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "transaksi_id" => ".0",
                            "jenis" => ".0",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
//                            "label" => ".hutang dagang",
                            "label" => ".piutang pembelian",
                            "sisa" => "nett",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    //<editor-fold desc="Post-locker stock">
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            //                            "transaksi_id" => "transaksi_id",
                            "transaksi_id" => "masterID",
                            "oleh_id" => ".0",
                            "oleh_nama" => "",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
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
                        "comName" => "LockerStockMutasiSupplies",
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
                    //</editor-fold>
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
    //  config cancel purchasing SP (make fullfill)
    "1961" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|placeID|olehID|supplierID",
            "stepCode|supplierID",
            "stepCode|placeID|supplierID",
            "stepCode|olehID|supplierID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "supplierID" => "pihakID",
                "supplierName" => "pihakName",

            ),
            "detail" => array(//===sumber nilai berupa rincian
                "ppn" => "(ppnFactor*harga)/100",
                "hpp_nppn" => "harga+ppn",
                "hpp_nppv" => "harga*ppv_index__nilai",
                "ppv" => "hpp_nppv-harga",
                "hpp_nppn_nppv" => "hpp_nppn+ppv",
                "nett" => "harga+ppn",
            ),
            "rsltItems" => array(//===sumber nilai berupa rincian

            ),
        ),
        "valueBuilders" => array(
            "grand_total" => "harga+ppn",
            "tagihan" => "grand_total-discount",
        ),
        "valueBuilders_rsltItems" => array(),
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
        "postProcessor" => array(
            "961r" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal_activity",
                        "loop" => array(//                            "activity" => ".1",
                        ),
                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang_nama" => "placeName",
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => "jenisTr",
//                            "jenis_master" => "jenisTrMaster",
//                            "jenis_top" => "jenisTrTop",
//                            "master_id" => "transaksi_id",
//                            "step_number" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
        ),
    ),
    "9763" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|placeID|olehID|supplierID",
            "stepCode|supplierID",
            "stepCode|placeID|supplierID",
            "stepCode|olehID|supplierID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "supplierID" => "pihakID",
                "supplierName" => "pihakName",

            ),
            "detail" => array(//===sumber nilai berupa rincian
                "ppn" => "(ppnFactor*harga)/100",
                "hpp_nppn" => "harga+ppn",
                "hpp_nppv" => "harga*ppv_index__nilai",
                "ppv" => "hpp_nppv-harga",
                "hpp_nppn_nppv" => "hpp_nppn+ppv",
                "nett" => "harga+ppn",
            ),
            "rsltItems" => array(//===sumber nilai berupa rincian

            ),
        ),
        "valueBuilders" => array(
            "grand_total" => "harga+ppn",
            "tagihan" => "grand_total-discount",
        ),
        "valueBuilders_rsltItems" => array(),
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
        "postProcessor" => array(
            "9763" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal_activity",
                        "loop" => array(//                            "activity" => ".1",
                        ),
                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang_nama" => "placeName",
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => "jenisTr",
//                            "jenis_master" => "jenisTrMaster",
//                            "jenis_top" => "jenisTrTop",
//                            "master_id" => "transaksi_id",
//                            "step_number" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
        ),
    ),

    //pembelian FG project
    "1466" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",
            "stepCode|referenceID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "supplierID" => "pihakID",
                "supplierName" => "pihakName",
                "gudangProjectID" => "gudangProject",
                "gudangProjectName" => "gudangProject__nama",
                "gudangProjectNama" => "gudangProject__nama",
            ),
            "detail" => array(//===sumber nilai berupa rincian
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
                        "nilai_credit" => "harga",
                        "nilai_cash" => "0",
                    ),
                    "cbd" => array(
                        "nilai_credit" => "harga",
                        "nilai_cash" => "0",
                    ),
                    "cia" => array(
                        "nilai_credit" => "harga",
                        "nilai_cash" => "0",
                    ),
                    "tt_adv" => array(
                        "nilai_credit" => "harga",
                        "nilai_cash" => "0",
                    ),
                ),
            ),
        ),
        "valueBuilders" => array(
            "grand_total" => "nett",
            "tagihan" => "grand_total-discount-dp",
//            "selisih_ppn_realisasi" => "nilai_tambah_ppn_in-ppn_realisasi",
//            "new_sisa" =>"nilai_tambah_piutang_pembelian-selisih_ppn_realisasi",
        ),
        "preProcessor" => array(
            "1467r" => array(
                "master" => array(
                    array(
                        "comName" => "ProdukSerialNumberExtractor",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "gudangProjectID",
                            "jenisTr" => "jenisTrMaster",
                            "step_number" => "step_number",
                            "gate_source" => ".items10_sum",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
            "1467" => array(
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
//                            "nilai" => "ppn",//geser ke bayar-bayar tidak ada ppn disni
                            "nilai" => ".0",
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
//                            "nilai" => "tagihan-nilai_dipakai_ppn_in",//geser ke harga perolehan
                            "nilai" => "harga-nilai_dipakai_ppn_in",
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
//
//                    array(
//                        "comName" => "ProdukSerialNumberExtractor",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "jenisTr" => "jenisTrMaster",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
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
                "transaksi_bruto" => "harga",
                "transaksi_nett" => "harga",
                "transaksi_netto" => "nett",
                "transaksi_nilai" => "harga",
                "ppn_nilai" => "ppn",
                "diskon_nilai" => "diskon_nilai",
                "hpp" => "harga_produk",
                "premi" => "premi",
                "biaya" => "biaya",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",
                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "ppnPersenCheck" => "ppnPersenCheck",

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
                "produk_ord_hrg_sub" => "sub_harga",//harga netto include ppn(harga*qty)

            ),
            "items" => array(
                "handler" => "handler",
                "name" => "name",
                "nama" => "nama",
                "produk_id" => "produk_id",
                "produk_nama" => "nama",
                "produk_kode" => "produk_kode",
                "produk_label" => "produk_label",
                "jml" => "jml",
                "qty" => "qty",
                "discount_qty" => "discount_qty",
                "harga" => "harga",
                "subtotal" => "subtotal",
                "satuan" => "satuan",
                "produk_sku" => "produk_sku",
                "label" => "label",
                "ppn" => "ppn",
                "barcode" => "barcode",
                "jenis" => "jenis",
                "produk_jenis_id" => "produk_jenis_id",
                "produk_jenis_nama" => "produk_jenis_nama",
                "jml_serial" => "jml_serial",
                "kategori_id" => "kategori_id",
                "kategori_nama" => "kategori_nama",
                "part_id_1" => "part_id_1",
                "part_nama_1" => "part_nama_1",
                "part_barcode_1" => "part_barcode_1",
                "part_id_2" => "part_id_2",
                "part_nama_2" => "part_nama_2",
                "part_barcode_2" => "part_barcode_2",
                "heater_id" => "heater_id",
                "heater_nama" => "heater_nama",
                "heater_barcode" => "heater_barcode",
                "outdoor_id" => "outdoor_id",
                "outdoor_nama" => "outdoor_nama",
                "outdoor_barcode" => "outdoor_barcode",
                "outdoor_sku" => "outdoor_sku",
                "indoor_id_1" => "indoor_id_1",
                "indoor_nama_1" => "indoor_nama_1",
                "indoor_barcode_1" => "indoor_barcode_1",
                "indoor_sku_1" => "indoor_sku_1",
                "indoor_id_2" => "indoor_id_2",
                "indoor_nama_2" => "indoor_nama_2",
                "indoor_barcode_2" => "indoor_barcode_2",
                "indoor_sku_2" => "indoor_sku_2",
                "indoor_id_3" => "indoor_id_3",
                "indoor_nama_3" => "indoor_nama_3",
                "indoor_barcode_3" => "indoor_barcode_3",
                "indoor_sku_3" => "indoor_sku_3",
                "indoor_id_4" => "indoor_id_4",
                "indoor_nama_4" => "indoor_nama_4",
                "indoor_barcode_4" => "indoor_barcode_4",
                "indoor_sku_4" => "indoor_sku_4",
                "qty_outdoor" => "qty_outdoor",
                "qty_indoor" => "qty_indoor",
                "keterangan" => "keterangan",
                "static_keterangan" => "static_keterangan",
                "sub_qty_indoor" => "sub_qty_indoor",
                "sub_qty_outdoor" => "sub_qty_outdoor",
                "discPersen" => "discPersen",
                "lastNett" => "lastNett",
                "jual_dipakai" => "jual_dipakai",
                "harga_jual" => "harga_jual",
                "harga_disc" => "harga_disc",
                "discNilai" => "discNilai",
                "scan_mode" => "scan_mode",
                "qty_barcode" => "qty_barcode",
                "nett1" => "nett1",
                "harga_include_ppn" => "harga_include_ppn",
                "nett1_include_ppn" => "nett1_include_ppn",
                "_harga_non_ppn" => "_harga_non_ppn",
                "_diskon_non_ppn" => "_diskon_non_ppn",
                "_harga_ppn" => "_harga_ppn",
                "disc" => "disc",
                "_grand_total" => "_grand_total",
                "nett1_ppn" => "nett1_ppn",
                "sub_harga" => "sub_harga",
                "sub_subtotal" => "sub_subtotal",
                "sub_discount_persen" => "sub_discount_persen",
                "sub_discount_qty" => "sub_discount_qty",
                "sub_harga_jasa" => "sub_harga_jasa",
                "sub_jual_online" => "sub_jual_online",
                "sub_jual" => "sub_jual",
                "sub_jual_reseller" => "sub_jual_reseller",
                "sub_ppn" => "sub_ppn",
                "sub_discPersen" => "sub_discPersen",
                "sub_lastNett" => "sub_lastNett",
                "sub_jual_dipakai" => "sub_jual_dipakai",
                "sub_harga_jual" => "sub_harga_jual",
                "sub_harga_disc" => "sub_harga_disc",
                "sub_discNilai" => "sub_discNilai",
                "sub_qty_barcode" => "sub_qty_barcode",
                "sub_nett1" => "sub_nett1",
                "sub_harga_include_ppn" => "sub_harga_include_ppn",
                "sub__harga_non_ppn" => "sub__harga_non_ppn",
                "sub__diskon_non_ppn" => "sub__diskon_non_ppn",
                "sub__harga_ppn" => "sub__harga_ppn",
                "sub_disc" => "sub_disc",
                "sub__grand_total" => "sub__grand_total",
                "sub_nett1_ppn" => "sub_nett1_ppn",
                "sub_jml_serial" => "sub_jml_serial",
                "harga_jasa" => "harga_jasa",
                "jual_online" => "jual_online",
                "jual" => "jual",
                "jual_reseller" => "jual_reseller",
                "cabang_id" => "placeID",
                "gudang_id" => "gudangID",
                "transaksi_id" => "transaksi_id",
            ),
            "items3_sum" => array(
                "handler" => "handler",
                "name" => "name",
                "nama" => "nama",
                "produk_id" => "produk_id",
                "produk_nama" => "nama",
                "produk_kode" => "produk_kode",
                "produk_label" => "produk_label",
                "jml" => "jml",
                "qty" => "qty",
                "discount_qty" => "discount_qty",
                "harga" => "harga",
                "subtotal" => "subtotal",
                "satuan" => "satuan",
                "produk_sku" => "produk_sku",
                "label" => "label",
                "ppn" => "ppn",
                "barcode" => "barcode",
                "jenis" => "jenis",
                "produk_jenis_id" => "produk_jenis_id",
                "produk_jenis_nama" => "produk_jenis_nama",
                "jml_serial" => "jml_serial",
                "kategori_id" => "kategori_id",
                "kategori_nama" => "kategori_nama",
                "part_id_1" => "part_id_1",
                "part_nama_1" => "part_nama_1",
                "part_barcode_1" => "part_barcode_1",
                "part_id_2" => "part_id_2",
                "part_nama_2" => "part_nama_2",
                "part_barcode_2" => "part_barcode_2",
                "heater_id" => "heater_id",
                "heater_nama" => "heater_nama",
                "heater_barcode" => "heater_barcode",
                "outdoor_id" => "outdoor_id",
                "outdoor_nama" => "outdoor_nama",
                "outdoor_barcode" => "outdoor_barcode",
                "outdoor_sku" => "outdoor_sku",
                "indoor_id_1" => "indoor_id_1",
                "indoor_nama_1" => "indoor_nama_1",
                "indoor_barcode_1" => "indoor_barcode_1",
                "indoor_sku_1" => "indoor_sku_1",
                "indoor_id_2" => "indoor_id_2",
                "indoor_nama_2" => "indoor_nama_2",
                "indoor_barcode_2" => "indoor_barcode_2",
                "indoor_sku_2" => "indoor_sku_2",
                "indoor_id_3" => "indoor_id_3",
                "indoor_nama_3" => "indoor_nama_3",
                "indoor_barcode_3" => "indoor_barcode_3",
                "indoor_sku_3" => "indoor_sku_3",
                "indoor_id_4" => "indoor_id_4",
                "indoor_nama_4" => "indoor_nama_4",
                "indoor_barcode_4" => "indoor_barcode_4",
                "indoor_sku_4" => "indoor_sku_4",
                "qty_outdoor" => "qty_outdoor",
                "qty_indoor" => "qty_indoor",
                "keterangan" => "keterangan",
                "static_keterangan" => "static_keterangan",
                "sub_qty_indoor" => "sub_qty_indoor",
                "sub_qty_outdoor" => "sub_qty_outdoor",
                "discPersen" => "discPersen",
                "lastNett" => "lastNett",
                "jual_dipakai" => "jual_dipakai",
                "harga_jual" => "harga_jual",
                "harga_disc" => "harga_disc",
                "discNilai" => "discNilai",
                "scan_mode" => "scan_mode",
                "qty_barcode" => "qty_barcode",
                "nett1" => "nett1",
                "harga_include_ppn" => "harga_include_ppn",
                "nett1_include_ppn" => "nett1_include_ppn",
                "_harga_non_ppn" => "_harga_non_ppn",
                "_diskon_non_ppn" => "_diskon_non_ppn",
                "_harga_ppn" => "_harga_ppn",
                "disc" => "disc",
                "_grand_total" => "_grand_total",
                "nett1_ppn" => "nett1_ppn",
                "sub_harga" => "sub_harga",
                "sub_subtotal" => "sub_subtotal",
                "sub_discount_persen" => "sub_discount_persen",
                "sub_discount_qty" => "sub_discount_qty",
                "sub_harga_jasa" => "sub_harga_jasa",
                "sub_jual_online" => "sub_jual_online",
                "sub_jual" => "sub_jual",
                "sub_jual_reseller" => "sub_jual_reseller",
                "sub_ppn" => "sub_ppn",
                "sub_discPersen" => "sub_discPersen",
                "sub_lastNett" => "sub_lastNett",
                "sub_jual_dipakai" => "sub_jual_dipakai",
                "sub_harga_jual" => "sub_harga_jual",
                "sub_harga_disc" => "sub_harga_disc",
                "sub_discNilai" => "sub_discNilai",
                "sub_qty_barcode" => "sub_qty_barcode",
                "sub_nett1" => "sub_nett1",
                "sub_harga_include_ppn" => "sub_harga_include_ppn",
                "sub__harga_non_ppn" => "sub__harga_non_ppn",
                "sub__diskon_non_ppn" => "sub__diskon_non_ppn",
                "sub__harga_ppn" => "sub__harga_ppn",
                "sub_disc" => "sub_disc",
                "sub__grand_total" => "sub__grand_total",
                "sub_nett1_ppn" => "sub_nett1_ppn",
                "sub_jml_serial" => "sub_jml_serial",
                "harga_jasa" => "harga_jasa",
                "jual_online" => "jual_online",
                "jual" => "jual",
                "jual_reseller" => "jual_reseller",
                "cabang_id" => "placeID",
                "gudang_id" => "gudangID",
                "transaksi_id" => "transaksi_id",
            ),
            "items9_sum" => array(
                "handler" => "handler",
                "name" => "name",
                "nama" => "nama",
                "produk_id" => "produk_id",
                "produk_nama" => "nama",
                "produk_kode" => "produk_kode",
                "produk_label" => "produk_label",
                "jml" => "jml",
                "qty" => "qty",
                "discount_qty" => "discount_qty",
                "harga" => "harga",
                "subtotal" => "subtotal",
                "satuan" => "satuan",
                "produk_sku" => "produk_sku",
                "label" => "label",
                "ppn" => "ppn",
                "barcode" => "barcode",
                "jenis" => "jenis",
                "produk_jenis_id" => "produk_jenis_id",
                "produk_jenis_nama" => "produk_jenis_nama",
                "jml_serial" => "jml_serial",
                "kategori_id" => "kategori_id",
                "kategori_nama" => "kategori_nama",
                "part_id_1" => "part_id_1",
                "part_nama_1" => "part_nama_1",
                "part_barcode_1" => "part_barcode_1",
                "part_id_2" => "part_id_2",
                "part_nama_2" => "part_nama_2",
                "part_barcode_2" => "part_barcode_2",
                "heater_id" => "heater_id",
                "heater_nama" => "heater_nama",
                "heater_barcode" => "heater_barcode",
                "outdoor_id" => "outdoor_id",
                "outdoor_nama" => "outdoor_nama",
                "outdoor_barcode" => "outdoor_barcode",
                "outdoor_sku" => "outdoor_sku",
                "indoor_id_1" => "indoor_id_1",
                "indoor_nama_1" => "indoor_nama_1",
                "indoor_barcode_1" => "indoor_barcode_1",
                "indoor_sku_1" => "indoor_sku_1",
                "indoor_id_2" => "indoor_id_2",
                "indoor_nama_2" => "indoor_nama_2",
                "indoor_barcode_2" => "indoor_barcode_2",
                "indoor_sku_2" => "indoor_sku_2",
                "indoor_id_3" => "indoor_id_3",
                "indoor_nama_3" => "indoor_nama_3",
                "indoor_barcode_3" => "indoor_barcode_3",
                "indoor_sku_3" => "indoor_sku_3",
                "indoor_id_4" => "indoor_id_4",
                "indoor_nama_4" => "indoor_nama_4",
                "indoor_barcode_4" => "indoor_barcode_4",
                "indoor_sku_4" => "indoor_sku_4",
                "qty_outdoor" => "qty_outdoor",
                "qty_indoor" => "qty_indoor",
                "keterangan" => "keterangan",
                "static_keterangan" => "static_keterangan",
                "sub_qty_indoor" => "sub_qty_indoor",
                "sub_qty_outdoor" => "sub_qty_outdoor",
                "discPersen" => "discPersen",
                "lastNett" => "lastNett",
                "jual_dipakai" => "jual_dipakai",
                "harga_jual" => "harga_jual",
                "harga_disc" => "harga_disc",
                "discNilai" => "discNilai",
                "scan_mode" => "scan_mode",
                "qty_barcode" => "qty_barcode",
                "nett1" => "nett1",
                "harga_include_ppn" => "harga_include_ppn",
                "nett1_include_ppn" => "nett1_include_ppn",
                "_harga_non_ppn" => "_harga_non_ppn",
                "_diskon_non_ppn" => "_diskon_non_ppn",
                "_harga_ppn" => "_harga_ppn",
                "disc" => "disc",
                "_grand_total" => "_grand_total",
                "nett1_ppn" => "nett1_ppn",
                "sub_harga" => "sub_harga",
                "sub_subtotal" => "sub_subtotal",
                "sub_discount_persen" => "sub_discount_persen",
                "sub_discount_qty" => "sub_discount_qty",
                "sub_harga_jasa" => "sub_harga_jasa",
                "sub_jual_online" => "sub_jual_online",
                "sub_jual" => "sub_jual",
                "sub_jual_reseller" => "sub_jual_reseller",
                "sub_ppn" => "sub_ppn",
                "sub_discPersen" => "sub_discPersen",
                "sub_lastNett" => "sub_lastNett",
                "sub_jual_dipakai" => "sub_jual_dipakai",
                "sub_harga_jual" => "sub_harga_jual",
                "sub_harga_disc" => "sub_harga_disc",
                "sub_discNilai" => "sub_discNilai",
                "sub_qty_barcode" => "sub_qty_barcode",
                "sub_nett1" => "sub_nett1",
                "sub_harga_include_ppn" => "sub_harga_include_ppn",
                "sub__harga_non_ppn" => "sub__harga_non_ppn",
                "sub__diskon_non_ppn" => "sub__diskon_non_ppn",
                "sub__harga_ppn" => "sub__harga_ppn",
                "sub_disc" => "sub_disc",
                "sub__grand_total" => "sub__grand_total",
                "sub_nett1_ppn" => "sub_nett1_ppn",
                "sub_jml_serial" => "sub_jml_serial",
                "harga_jasa" => "harga_jasa",
                "jual_online" => "jual_online",
                "jual" => "jual",
                "jual_reseller" => "jual_reseller",
                "cabang_id" => "placeID",
                "gudang_id" => "gudangID",
                "transaksi_id" => "transaksi_id",
            ),
            "items10_sum" => array(
                "handler" => "handler",
                "name" => "name",
                "nama" => "nama",
                "produk_id" => "produk_id",
                "produk_nama" => "nama",
                "produk_kode" => "produk_kode",
                "produk_label" => "produk_label",
                "jml" => "jml",
                "qty" => "qty",
                "discount_qty" => "discount_qty",
                "harga" => "harga",
                "subtotal" => "subtotal",
                "satuan" => "satuan",
                "produk_sku" => "produk_sku",
                "label" => "label",
                "ppn" => "ppn",
                "barcode" => "barcode",
                "jenis" => "jenis",
                "produk_jenis_id" => "produk_jenis_id",
                "produk_jenis_nama" => "produk_jenis_nama",
                "jml_serial" => "jml_serial",
                "kategori_id" => "kategori_id",
                "kategori_nama" => "kategori_nama",
                "part_id_1" => "part_id_1",
                "part_nama_1" => "part_nama_1",
                "part_barcode_1" => "part_barcode_1",
                "part_id_2" => "part_id_2",
                "part_nama_2" => "part_nama_2",
                "part_barcode_2" => "part_barcode_2",
                "heater_id" => "heater_id",
                "heater_nama" => "heater_nama",
                "heater_barcode" => "heater_barcode",
                "outdoor_id" => "outdoor_id",
                "outdoor_nama" => "outdoor_nama",
                "outdoor_barcode" => "outdoor_barcode",
                "outdoor_sku" => "outdoor_sku",
                "indoor_id_1" => "indoor_id_1",
                "indoor_nama_1" => "indoor_nama_1",
                "indoor_barcode_1" => "indoor_barcode_1",
                "indoor_sku_1" => "indoor_sku_1",
                "indoor_id_2" => "indoor_id_2",
                "indoor_nama_2" => "indoor_nama_2",
                "indoor_barcode_2" => "indoor_barcode_2",
                "indoor_sku_2" => "indoor_sku_2",
                "indoor_id_3" => "indoor_id_3",
                "indoor_nama_3" => "indoor_nama_3",
                "indoor_barcode_3" => "indoor_barcode_3",
                "indoor_sku_3" => "indoor_sku_3",
                "indoor_id_4" => "indoor_id_4",
                "indoor_nama_4" => "indoor_nama_4",
                "indoor_barcode_4" => "indoor_barcode_4",
                "indoor_sku_4" => "indoor_sku_4",
                "qty_outdoor" => "qty_outdoor",
                "qty_indoor" => "qty_indoor",
                "keterangan" => "keterangan",
                "static_keterangan" => "static_keterangan",
                "sub_qty_indoor" => "sub_qty_indoor",
                "sub_qty_outdoor" => "sub_qty_outdoor",
                "discPersen" => "discPersen",
                "lastNett" => "lastNett",
                "jual_dipakai" => "jual_dipakai",
                "harga_jual" => "harga_jual",
                "harga_disc" => "harga_disc",
                "discNilai" => "discNilai",
                "scan_mode" => "scan_mode",
                "qty_barcode" => "qty_barcode",
                "nett1" => "nett1",
                "harga_include_ppn" => "harga_include_ppn",
                "nett1_include_ppn" => "nett1_include_ppn",
                "_harga_non_ppn" => "_harga_non_ppn",
                "_diskon_non_ppn" => "_diskon_non_ppn",
                "_harga_ppn" => "_harga_ppn",
                "disc" => "disc",
                "_grand_total" => "_grand_total",
                "nett1_ppn" => "nett1_ppn",
                "sub_harga" => "sub_harga",
                "sub_subtotal" => "sub_subtotal",
                "sub_discount_persen" => "sub_discount_persen",
                "sub_discount_qty" => "sub_discount_qty",
                "sub_harga_jasa" => "sub_harga_jasa",
                "sub_jual_online" => "sub_jual_online",
                "sub_jual" => "sub_jual",
                "sub_jual_reseller" => "sub_jual_reseller",
                "sub_ppn" => "sub_ppn",
                "sub_discPersen" => "sub_discPersen",
                "sub_lastNett" => "sub_lastNett",
                "sub_jual_dipakai" => "sub_jual_dipakai",
                "sub_harga_jual" => "sub_harga_jual",
                "sub_harga_disc" => "sub_harga_disc",
                "sub_discNilai" => "sub_discNilai",
                "sub_qty_barcode" => "sub_qty_barcode",
                "sub_nett1" => "sub_nett1",
                "sub_harga_include_ppn" => "sub_harga_include_ppn",
                "sub__harga_non_ppn" => "sub__harga_non_ppn",
                "sub__diskon_non_ppn" => "sub__diskon_non_ppn",
                "sub__harga_ppn" => "sub__harga_ppn",
                "sub_disc" => "sub_disc",
                "sub__grand_total" => "sub__grand_total",
                "sub_nett1_ppn" => "sub_nett1_ppn",
                "sub_jml_serial" => "sub_jml_serial",
                "harga_jasa" => "harga_jasa",
                "jual_online" => "jual_online",
                "jual" => "jual",
                "jual_reseller" => "jual_reseller",
                "cabang_id" => "placeID",
                "gudang_id" => "gudangID",
                "transaksi_id" => "transaksi_id",
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

        //component hanya jalan untuk modul ketika jalan akunting wajib pakai bridging
        "components" => array(
            "1466r" => array(
                "master" => array(
                    array(
                        "comName" => "JurnalPembelian",
                        "loop" => array(
                            "1466r" => "harga",
//                        "1466r" => "sub_harga",
                            "1466" => "-harga",
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
                        "comName" => "RekeningPembelian",
                        "loop" => array(
                            "1466r" => "harga",
//                        "1466r" => "sub_harga",
                            "1466" => "-harga",
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
                        "comName" => "RekeningTransaksiPembelian",
                        "loop" => array(
                            "1466r" => "harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                ),
                "detail" => array(),
            ),
            "1467" => array(
                "master" => array(

                    //region jurnal pertama
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030020" => "harga_supplies",//persediaan supplies riil
                            "1010030040" => "harga_produk",//persediaan produk riil
                            "1010040050" => "nilai_tambah_ppn_in",//ppn in belum ada faktur
                            "2010010" => "nilai_tambah_piutang_pembelian",//hutang dagan
                            "1010020030" => "-nilai_dipakai_piutang_pembelian",//piutang pembelian
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
                            "1010030020" => "harga_supplies",//persediaan supplies riil
                            "1010030040" => "harga_produk",//persediaan produk riil
                            "1010040050" => "nilai_tambah_ppn_in",//ppn in belum ada faktur
                            "2010010" => "nilai_tambah_piutang_pembelian",//hutang dagang
                            "1010020030" => "-nilai_dipakai_piutang_pembelian",//piutang pembelian
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
                            "1010020030" => "-nilai_dipakai_piutang_pembelian",//piutang pembelian
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
                            "1010040050" => "nilai_tambah_ppn_in",//ppn in belum ada faktur
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

                    // pembantu hutang dagang (supplier)
                    array(
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "2010010" => "nilai_tambah_piutang_pembelian",//hutang dagang
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

                    /*
 * dimatikan karena detail hutang dagang lokal/import belum digenerate
 * tujuan untuk memisah kategori hutang dagang
 * 22 desember 2022*/
                    // pembantu hutang dagang (lokal / import)

//                    array(
//                        "comName" => "RekeningPembantuSupplierJenis",
//                        "loop" => array(
//                            "2010010" => "nilai_tambah_piutang_pembelian",//hutang dagang
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => ".2010010010",
//                            "extern_nama" => ".lokal",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    // pembantu hutang dagang (lokal/import dengan supplier)

//                    array(
//                        "comName" => "RekeningPembantuSupplierSubJenis",
//                        "loop" => array(
//                            "2010010" => "nilai_tambah_piutang_pembelian",//hutang dagang
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => ".2010010010",
//                            "extern_nama" => ".lokal",
//                            "extern2_id" => "pihakID",
//                            "extern2_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    //endregion

                    //region jurnal kedua pindah persediaan riil ke persediaan(std)
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
//                            "1010030030" => "hpp_nppv",//persediaan produk
//                            "1010030040" => "-harga",//persediaan produk riil
//                            "2010090010" => "ppv",//hutang lain ppv
                            "1010030030" => "harga_produk",//persediaan produk
                            "1010030040" => "-harga_produk",//persediaan produk riil

                            "1010030010" => "harga_supplies",//persediaan supplies
                            "1010030020" => "-harga_supplies",//persediaan supplies riil

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
//                            "1010030030" => "hpp_nppv",//persediaan produk
//                            "1010030040" => "-harga",//persediaan produk riil
//                            "2010090010" => "ppv",//hutang lain ppv
                            "1010030030" => "harga_produk",//persediaan produk
                            "1010030040" => "-harga_produk",//persediaan produk riil

                            "1010030010" => "harga_supplies",//persediaan supplies
                            "1010030020" => "-harga_supplies",//persediaan supplies riil

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

                    // region mencatat piutang, diskon dari supplier
                    99 => array(
                        "comName" => "Jurnal",
                        "loop" => array(
//                            "1010030030" => "-diskon_npph_nilai_total",// persediaan, diskon_nilai_total*
                            "1010020030" => "diskon_nilai_total",// piutang supplier
                            "7010150" => "laba_lain_lain",// laba lain-lain
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    98 => array(
                        "comName" => "Rekening",
                        "loop" => array(
//                            "1010030030" => "-diskon_npph_nilai_total",// persediaan, diskon_nilai_total*
                            "1010020030" => "diskon_nilai_total",// piutang supplier
                            "7010150" => "laba_lain_lain",// laba lain-lain
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // endregion mencatat piutang, diskon dari supplier

                    //region jurnal diskon bonus produk lain dari vendor
                    97 => array(
                        "comName" => "Jurnal",
                        "loop" => array(
//                            "1010030030" => "-diskon_npph_nilai_total",// persediaan, diskon_nilai_total*
                            "1010020030" => "produk_rel_harga",// piutang supplier
                            "7010150" => "produk_rel_harga",// laba lain-lain
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    96 => array(
                        "comName" => "Rekening",
                        "loop" => array(
//                            "1010030030" => "-diskon_npph_nilai_total",// persediaan, diskon_nilai_total*
                            "1010020030" => "produk_rel_harga",// piutang supplier
                            "7010150" => "produk_rel_harga",// laba lain-lain
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


                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuProdukRiil",
                        "loop" => array(
                            "1010030040" => "sub_harga_produk",//persediaan produk riil
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "produk_nilai" => "harga_produk",
                            "gudang_id" => "gudangProjectID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items10_sum",
                        "srcRawGateName" => "items10_sum",
                    ),
                    array(
                        "comName" => "RekeningPembantuProdukRiil",
                        "loop" => array(
                            "1010030040" => "-sub_harga_produk",//persediaan produk riil
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "harga_produk",
                            "gudang_id" => "gudangProjectID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items10_sum",
                        "srcRawGateName" => "items10_sum",
                    ),
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "sub_harga_produk",//persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "produk_nilai" => "harga_produk",
                            "gudang_id" => "gudangProjectID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "supplierID" => "pihakID",
                        ),
                        "srcGateName" => "items10_sum",
                        "srcRawGateName" => "items10_sum",
                    ),

                    array(
                        "comName" => "RekeningPembantuSuppliesRiil",
                        "loop" => array(
                            "1010030020" => "sub_harga_supplies",//persediaan produk riil
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "produk_nilai" => "harga_supplies",
                            "gudang_id" => "gudangProjectID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items9_sum",
                        "srcRawGateName" => "items9_sum",
                    ),
                    array(
                        "comName" => "RekeningPembantuSuppliesRiil",
                        "loop" => array(
                            "1010030020" => "-sub_harga_supplies",//persediaan produk riil
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "harga_supplies",
                            "gudang_id" => "gudangProjectID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items9_sum",
                        "srcRawGateName" => "items9_sum",
                    ),
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "1010030010" => "sub_harga_supplies",//persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "produk_nilai" => "harga_supplies",
                            "gudang_id" => "gudangProjectID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "supplierID" => "pihakID",
                        ),
                        "srcGateName" => "items9_sum",
                        "srcRawGateName" => "items9_sum",
                    ),


                    // rekening pembantu piutang supplier, diskon supplier
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierItem",
                        "loop" => array(
                            "1010020030" => "sub_diskon_nilai",// piutang supplier
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
//                            "extern_id" => "diskon_id",
//                            "extern_nama" => "diskon_nama",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                        ),
                        "srcGateName" => "items4_sum",
                        "srcRawGateName" => "items4_sum",
                    ),

                    // rekening pembantu piutang supplier, diskon supplier, supplier
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierDetailItem",
                        "loop" => array(
                            "1010020030" => "sub_diskon_nilai",// piutang supplier
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "extern2_id" => "pihakID",
                            "extern2_nama" => "pihakName",
                            "extern_id" => "diskon_id",
                            "extern_nama" => "diskon_nama",
                        ),
                        "srcGateName" => "items4_sum",
                        "srcRawGateName" => "items4_sum",
                    ),

                    // rekening pembantu piutang supplier, diskon supplier, supplier, transaksi_id
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierDetailTransItem",
                        "loop" => array(
                            "1010020030" => "sub_diskon_nilai",// piutang supplier
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
//                            "extern2_id" => "pihakID",
//                            "extern2_nama" => "pihakName",
//                            "extern_id" => "diskon_id",
//                            "extern_nama" => "diskon_nama",
                            "extern3_id" => "pihakID",// supplier
                            "extern3_nama" => "pihakName",// supplier
                            "extern2_id" => "diskon_id",// jenis diskon
                            "extern2_nama" => "diskon_nama",// jenis diskon
                        ),
                        "srcGateName" => "items4_sum",
                        "srcRawGateName" => "items4_sum",
                    ),
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierDetailTransProdukItem",
                        "loop" => array(
                            "1010020030" => "sub_diskon_nilai",// piutang supplier
                        ),
                        "static" => array(
                            //extern_id diinject di model untuk ambil transaksi_id
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "extern_id" => "diskon_id",// jenis diskon
                            "extern_nama" => "diskon_nama",// jenis diskon
                            "extern2_id" => "pihakID",// supplier
                            "extern2_nama" => "pihakName",// supplier
                            "extern3_id" => "id",// produk yang dapet diskon (ac)
                            "extern3_nama" => "nama",
                            "extern4_id" => "diskon_id",// hadiahnya produknya(kabel,selang)
                            "extern4_nama" => "diskon_nama",// jenis diskon
                            "produk_qty" => ".1",// jenis diskon
                            "produk_nilai" => "diskon_nilai",// jenis diskon
                        ),
                        "srcGateName" => "items4_sum",
                        "srcRawGateName" => "items4_sum",
                    ),
                    // locker stok diskon mempertimbangkan nilai tidak hanya qty
                    array(
                        "comName" => "LockerDiskonValue",
                        "loop" => array(
                            "exec_locker" => "sub_diskon_nilai",//sengaja dipasang kalau kalau tidak punya biar tidak ditulis
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".diskon",
                            "jenis2" => ".diskon",
                            "jenis_locker" => ".stock",
                            "state" => ".active",
                            "jumlah" => ".1",
                            "nilai" => "sub_diskon_nilai",
                            "nilai2" => "sub_diskon_nilai",
                            "nilai_unit" => "sub_diskon_nilai",
                            "produk_id" => "diskon_id",//id diskon
                            "nama" => "diskon_nama",

                            "extern_id" => "diskon_id",//id produk hadiah/jika berupa diskon reguler diisi id diskon
                            "extern_nama" => "diskon_nama",
                            "extern2_id" => "id",//produk yang dibeli
                            "extern2_nama" => "nama",
                            "satuan" => "satuan",
                            "transaksi_id" => "transaksi_id",
                            "transaksi_no" => "nomer",
                            "nomer" => "nomer",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                            "supplier_id" => "pihakID",
                            "supplier_nama" => "pihakName",
                        ),
                        "srcGateName" => "items4_sum",
                        "srcRawGateName" => "items4_sum",
                    ),

                    //region free produk

                    // rekening pembantu piutang supplier, diskon free produk
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierItem",
                        "loop" => array(
                            "1010020030" => "sub_produk_rel_harga",// piutang supplier
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                        ),
                        "srcGateName" => "items5_sum",
                        "srcRawGateName" => "items5_sum",
                    ),

                    // rekening pembantu piutang supplier, diskon supplier, supplier
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierDetailItem",
                        "loop" => array(
                            "1010020030" => "sub_produk_rel_harga",// piutang supplier
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "extern2_id" => "pihakID",
                            "extern2_nama" => "pihakName",
                            "extern_id" => "per_supplier_diskon_id",
                            "extern_nama" => "per_supplier_diskon_nama",
                        ),
                        "srcGateName" => "items5_sum",
                        "srcRawGateName" => "items5_sum",
                    ),

                    // rekening pembantu piutang supplier, diskon supplier, supplier, transaksi_id,produk,produk diskon
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierDetailTransItem",
                        "loop" => array(
                            "1010020030" => "sub_produk_rel_harga",// piutang supplier
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "extern3_id" => "pihakID",// supplier
                            "extern3_nama" => "pihakName",// supplier
                            "extern2_id" => "per_supplier_diskon_id",// jenis diskon
                            "extern2_nama" => "per_supplier_diskon_nama",// jenis diskon
                        ),
                        "srcGateName" => "items5_sum",
                        "srcRawGateName" => "items5_sum",
                    ),
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierDetailTransProdukItem",
                        "loop" => array(
                            "1010020030" => "sub_produk_rel_harga",// piutang supplier
                        ),
                        "static" => array(
                            //extern_id diinject di model untuk ambil transaksi_id
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "extern_id" => "per_supplier_diskon_id",// jenis diskon
                            "extern_nama" => "per_supplier_diskon_nama",// jenis diskon
                            "extern2_id" => "pihakID",// supplier
                            "extern2_nama" => "pihakName",// supplier
                            "extern3_id" => "produk_id",// produk yang dapet diskon (ac)
                            "extern3_nama" => "produk_nama",// jenis diskon
                            "extern4_id" => "produk_rel_id",// hadiahnya produknya(kabel,selang)
                            "extern4_nama" => "produk_rel_nama",// jenis diskon
                            "produk_qty" => "qty",// jenis diskon
                            "produk_nilai" => "produk_rel_harga",// jenis diskon
                        ),
                        "srcGateName" => "items5_sum",
                        "srcRawGateName" => "items5_sum",
                    ),

                    //endregion


                    //locker diskon free produk
                    array(
                        "comName" => "LockerDiskonValue",
                        "loop" => array(
                            "exec_locker" => "sub_produk_rel_harga",//sengaja dipasang kalau kalau tidak punya biar tidak ditulis
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".diskon",
                            "jenis2" => ".diskon",
                            "jenis_locker" => ".stock",
                            "state" => ".active",
                            "jumlah" => "qty",
                            "nilai" => "sub_produk_rel_harga",
                            "nilai2" => "produk_rel_harga",
                            "nilai_unit" => "produk_rel_harga",
                            "produk_id" => "per_supplier_diskon_id",//id diskon
                            "nama" => "per_supplier_diskon_nama",

                            "extern_id" => "produk_rel_id",//id produk hadiah/jika berupa diskon reguler diisi id diskon
                            "extern_nama" => "produk_rel_nama",
                            "extern2_id" => "produk_id",//produk yang dibeli
                            "extern2_nama" => "produk_nama",
                            "satuan" => "satuan",
//                            "transaksi_id" => "transaksi_id",
                            "transaksi_no" => "nomer",
                            "nomer" => "nomer",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                            "supplier_id" => "pihakID",
                            "supplier_nama" => "pihakName",

                        ),
                        "srcGateName" => "items5_sum",
                        "srcRawGateName" => "items5_sum",
                    ),

                ),
            ),
        ),
        "postProcessor" => array(
            "1466r" => array(
                "master" => array(
//                    array(
//                        "comName" => "Jurnal_activity",
//                        "loop" => array(
//                            "activity" => ".1",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang_nama" => "placeName",
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => "jenisTr",
//                            "jenis_master" => "jenisTrMaster",
//                            "jenis_top" => "jenisTrTop",
//                            "master_id" => "transaksi_id",
//                            "step_number" => ".1",
////                            "step_number" => "step_number",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Jurnal_activityMain",
//                        "loop" => array(
//                            "activity" => ".1",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang_nama" => "placeName",
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => "jenisTr",
//                            "jenis_master" => "jenisTrMaster",
//                            "jenis_top" => "jenisTrTop",
//                            "master_id" => "transaksi_id",
//                            "step_number" => ".1",
////                            "step_number" => "step_number",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    // locker transkasi
                    // array(
                    //     "comName" => "LockerTransaksi",
                    //     "loop" => array(),
                    //     "static" => array(
                    //         "cabang_id" => "placeID",
                    //         "jenis" => ".transaksi",
                    //         "state" => ".active",
                    //         "jumlah" => ".1",
                    //         "produk_id" => ".0",
                    //         "nama" => "",
                    //         "satuan" => "",
                    //         "oleh_id" => ".0",
                    //         "gudang_id" => ".0",
                    //     ),
                    //     "srcGateName" => "main",
                    //     "srcRawGateName" => "main",
                    // ),
                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningTransaksiDataPembelian",
                        "loop" => array(
                            "1466r" => "sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => "qty",
                            "extern_id" => "transaksi_id",
                        ),
                        "reversable" => false,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //nulis ke table _rek_penjualan_transkasi_data dan mutasi
                    array(
                        "comName" => "TransaksiDataPembelian",
                        "loop" => array(
                            "1466r" => "sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => "qty",
                            "extern_id" => "transaksi_id",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//untuk update penjualan_transaki_data
                ),
            ),
            "1466" => array(
                "master" => array(
//                    array(
//                        "comName" => "Jurnal_activity",
//                        "loop" => array(
//                            "activity" => ".1",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang_nama" => "placeName",
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => "jenisTr",
//                            "jenis_master" => "jenisTrMaster",
//                            "jenis_top" => "jenisTrTop",
//                            "master_id" => "transaksi_id",
//                            "step_number" => ".2",
////                            "step_number" => "step_number",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    array(
//                        "comName" => "Jurnal_activityMain",
//                        "loop" => array(
//                            "activity" => ".1",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang_nama" => "placeName",
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => "jenisTr",
//                            "jenis_master" => "jenisTrMaster",
//                            "jenis_top" => "jenisTrTop",
//                            "master_id" => "transaksi_id",
//                            "step_number" => ".2",
////                            "step_number" => "step_number",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    // locker transkasi
                    // array(
                    //     "comName" => "LockerTransaksi",
                    //     "loop" => array(),
                    //     "static" => array(
                    //         "cabang_id" => "placeID",
                    //         "jenis" => ".transaksi",
                    //         "state" => ".active",
                    //         "jumlah" => ".1",
                    //         "produk_id" => ".0",
                    //         "nama" => "",
                    //         "satuan" => "",
                    //         "oleh_id" => ".0",
                    //         "gudang_id" => ".0",
                    //     ),
                    //     "srcGateName" => "main",
                    //     "srcRawGateName" => "main",
                    // ),
                ),
                "detail" => array(
                    array(
                        "comName" => "PriceProdukPerSupplier",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "suppliers_id" => "pihakID",
                            "produk_nama" => "name",
                            "nilai" => "harga",
                            "cabang_id" => "placeID",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => ".produk",
                            "jenis_value" => ".hpp",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "PriceProdukLastPurchase",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "nilai" => "harga",
                            "cabang_id" => "placeID",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => ".produk",
                            "jenis_value" => ".hpp",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "PriceProduk",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "nilai" => "harga",
                            "cabang_id" => "placeID",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => ".produk",
                            "jenis_value" => ".hpp",

                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    //debet trasnaksi data pembelian order
                    array(
                        "comName" => "RekeningTransaksiDataPembelian",
                        "loop" => array(
                            "1466r" => "-sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => "-qty",
                            "extern_id" => "transaksi_id",
                        ),
                        "reversable" => false,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "TransaksiDataPembelian",
                        "loop" => array(
                            "1466r" => "-sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => "-qty",
                            "extern_id" => "transaksi_id",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    //masuk otorisasi
                    array(
                        "comName" => "RekeningTransaksiDataPembelian",
                        "loop" => array(
                            "1466" => "sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => "qty",
                            "extern_id" => "transaksi_id",
                        ),
                        "reversable" => false,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//nulis ke table _rek_penjualan_transkasi_data dan mutasi
                    array(
                        "comName" => "TransaksiDataPembelian",
                        "loop" => array(
                            "1466" => "sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => "qty",
                            "extern_id" => "transaksi_id",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//untuk update penjualan_transaki_data

                ),
            ),
            "1467r" => array(
                "master" => array(),
                "detail" => array(
                    // serial number produk
                    array(
                        "comName" => "ProdukSerialNumber",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "produk_serial_number" => "serial_number",
                            "produk_sku" => "produk_sku",
                            "produk_sku_serial" => "produk_sku_serial",
                            "produk_sku_part_id" => "produk_sku_part_id",
                            "produk_sku_part_nama" => "produk_sku_part_nama",
                            "produk_sku_part_serial" => "produk_sku_part_serial",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "supplier_id" => "supplierID",
                            "supplier_nama" => "supplierName",
                            "gudang_id" => "gudangProjectID",
                            //---------------
                            "transaksi_reference_id" => "referenceID",
                            "transaksi_reference_no" => "referenceNomer",
                            "transaksi_reference_dtime" => "referenceDate",
                            "transaksi_reference_fulldate" => "referenceFulldate",
                            "transaksi_reference_count" => "referenceCount",
                            "transaksi_count" => "transaksi_count",
                            "transaksi_jenis_count" => "transaksi_jenis_count",
                            "part_keterangan" => "part_keterangan",
                        ),
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),
                    //debet trasnaksi data pembelian order
                    array(
                        "comName" => "RekeningTransaksiDataPembelian",
                        "loop" => array(
                            "1466" => "-sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => "-qty",
                            "extern_id" => "transaksi_id",
                        ),
                        "reversable" => false,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "TransaksiDataPembelian",
                        "loop" => array(
                            "1466" => "-sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => "-qty",
                            "extern_id" => "transaksi_id",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    //masuk otorisasi
                    array(
                        "comName" => "RekeningTransaksiDataPembelian",
                        "loop" => array(
                            "1467r" => "sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => "qty",
                            "extern_id" => "transaksi_id",
                        ),
                        "reversable" => false,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//nulis ke table _rek_penjualan_transkasi_data dan mutasi
                    array(
                        "comName" => "TransaksiDataPembelian",
                        "loop" => array(
                            "1467r" => "sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => "qty",
                            "extern_id" => "transaksi_id",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),//untuk update penjualan_transaki_data
                ),
            ),
            "1467" => array(
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
                            "nilai" => "-nilai_dipakai_ppn_in",
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
                            "nilai" => "-nilai_dipakai_piutang_pembelian",
                            "transaksi_id" => "currentID",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    // locker transkasi
                    // array(
                    //     "comName" => "LockerTransaksi",
                    //     "loop" => array(),
                    //     "static" => array(
                    //         "cabang_id" => "placeID",
                    //         "jenis" => ".transaksi",
                    //         "state" => ".active",
                    //         "jumlah" => ".1",
                    //         "produk_id" => ".0",
                    //         "nama" => "",
                    //         "satuan" => "",
                    //         "oleh_id" => ".0",
                    //         "gudang_id" => ".0",
                    //     ),
                    //     "srcGateName" => "main",
                    //     "srcRawGateName" => "main",
                    // ),
                ),
                "detail" => array(

                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".produk",
                            "jml" => "qty",
                            "produk_id" => "id",
                            "hpp" => "harga_produk",
                            "jml_nilai" => "sub_harga_produk",
                            "hpp_riil" => "harga_produk",
                            "jml_nilai_riil" => "sub_harga_produk",
                            "ppv_riil" => "ppv",
                            "ppv_nilai_riil" => "sub_ppv",
                            "hpp_nppv" => "hpp_nppv",
                            "jml_nilai_nppv" => "sub_hpp_nppv",
                            "nama" => "name",
                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "gudangProjectID",
                            "ppn_in" => "ppn",
                            "ppn_in_nilai" => "sub_ppn",
                            "suppliers_id" => "pihakID",
                            "suppliers_nama" => "pihakName",
                            "produk_jenis" => ".lokal",
                        ),
                        "srcGateName" => "items10_sum",
                        "srcRawGateName" => "items10_sum",
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
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "gudangProjectID",
                        ),
                        "srcGateName" => "items10_sum",
                        "srcRawGateName" => "items10_sum",
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
                            "jenis" => "jenisTr",
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "gudangProjectID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items10_sum",
                        "srcRawGateName" => "items10_sum",
                    ),

                    // menambah persediaan supplies full
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".supplies",
                            "jml" => "qty",
                            "produk_id" => "id",
                            "hpp" => "harga_supplies",
                            "jml_nilai" => "sub_harga_supplies",
                            "hpp_riil" => "harga_supplies",
                            "jml_nilai_riil" => "sub_harga_supplies",
                            "ppv_riil" => "ppv",
                            "ppv_nilai_riil" => "sub_ppv",
                            "hpp_nppv" => "hpp_nppv",
                            "jml_nilai_nppv" => "sub_hpp_nppv",
                            "nama" => "name",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangProjectID",
                            "ppn_in" => "ppn",
                            "ppn_in_nilai" => "sub_ppn",
                            "suppliers_id" => "pihakID",
                            "suppliers_nama" => "pihakName",
                            "produk_jenis" => ".lokal",
                        ),
                        "srcGateName" => "items9_sum",
                        "srcRawGateName" => "items9_sum",
                    ),
                    // locker stok supplies reguler
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".active",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangProjectID",
                        ),
                        "srcGateName" => "items9_sum",
                        "srcRawGateName" => "items9_sum",
                    ),
                    // locker stok supplies mutasi
                    array(
                        "comName" => "LockerStockMutasiSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "qty_debet" => "qty",
                            "produk_nilai" => "hpp_supplies",
                            "gudang_id" => "gudangProjectID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items9_sum",
                        "srcRawGateName" => "items9_sum",
                    ),


                    array(
                        "comName" => "PriceProduk",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "nilai" => "hpp_nppv",
                            "cabang_id" => "placeID",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => ".produk",
                            "jenis_value" => ".hpp_nppv",

                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "PriceProduk",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "nilai" => "harga",
                            "cabang_id" => "placeID",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => ".produk",
                            "jenis_value" => ".hpp_grn",

                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    array(
                        "comName" => "TransaksiProduk",
                        "loop" => array(
                            "1467" => "qty*.1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "rekening_nama" => "targetJenisLabel",
                            "produk_qty" => "qty",
                            "produk_nilai" => ".1",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_name" => "olehName",
                            //         "transaksi_id"                        => "transaksi_id",
                            "master_id" => "transaksi_id",
                            "master_jenis" => "jenisTrMaster",
                            //------
//                            "_stepCode_placeID" => "_stepCode_placeID",
//                            "_stepCode_olehID" => "_stepCode_olehID",
//                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
//                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
//                            "_stepCode_customerID" => "_stepCode_customerID",
//                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
//                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
//                            "_stepCode" => "_stepCode",
//                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
//                            "_stepCode_supplierID" => "_stepCode_supplierID",
//                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
//                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "TransaksiProduk",
                        "loop" => array(
                            "1466r" => "-qty*.1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "rekening_nama" => "targetJenisLabel",
                            "produk_qty" => "-qty",
                            "produk_nilai" => ".1",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_name" => "olehName",
                            //         "transaksi_id"                        => "transaksi_id",
                            "master_id" => "transaksi_id",
                            "master_jenis" => "jenisTrMaster",
                            //------
//                            "_stepCode_placeID" => "_stepCode_placeID",
//                            "_stepCode_olehID" => "_stepCode_olehID",
//                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
//                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
//                            "_stepCode_customerID" => "_stepCode_customerID",
//                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
//                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
//                            "_stepCode" => "_stepCode",
//                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
//                            "_stepCode_supplierID" => "_stepCode_supplierID",
//                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
//                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    // update relasi produk dengan supplier
                    array(
                        "comName" => "ProdukPerSupplier",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "produk_kode" => "barcode",
                            "suppliers_id" => "pihakID",
                            "suppliers_nama" => "pihakName",
                            "cabang_id" => "placeID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    //debet trasnaksi data pembelian order
                    array(
                        "comName" => "RekeningTransaksiDataPembelian",
                        "loop" => array(
                            "1467r" => "-sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => "-qty",
                            "extern_id" => "transaksi_id",
                        ),
                        "reversable" => false,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "TransaksiDataPembelian",
                        "loop" => array(
                            "1467r" => "-sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => "-qty",
                            "extern_id" => "transaksi_id",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    //masuk otorisasi
                    array(
                        "comName" => "RekeningTransaksiDataPembelian",
                        "loop" => array(
                            "1467" => "sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => "qty",
                            "extern_id" => "transaksi_id",
                        ),
                        "reversable" => false,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "TransaksiDataPembelian",
                        "loop" => array(
                            "1467" => "sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => "qty",
                            "extern_id" => "transaksi_id",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
//            "1111" => array(
//                "master" => array(
//                    array(
//                        "comName" => "PaymentSource",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "label" => ".hutang dagang",
////                            "target_jenis" => "jenisTr",
//                            "jenis" => ".1467",
//                            "transaksi_id" => "currentID",
//                            "ppn_approved" => "ppn_realisasi",
////                            "sisa" => "new_sisa",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "PaymentSource",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "label" => ".hutang dagang",
////                            "target_jenis" => ".489",
//                            "jenis" => ".1467",
//                            "transaksi_id" => "currentID",
//                            "terbayar" => "selisih_ppn_realisasi",
//                            "sisa" => "new_sisa",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//
//                ),
//                "detail" => array(),
//            ),
        ),

        "closedRequest" => array(
            "466" => array(
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
    ),

    "9967" => array(
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
                "gudangProjectID" => "gudangProject",
                "gudangProjectName" => "gudangProject__nama",
                "gudangProjectNama" => "gudangProject__nama",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "ppn" => "(ppnFactor*harga)/100",
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
            "9967sc" => array(
                "master" => array(
                    array(
                        "comName" => "ProdukSerialNumberExtractor",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "jenisTr" => "jenisTrMaster",
                            "step_number" => "step_number",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
            "9967" => array(
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
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "gudangProjectID",
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
                                "produk_jenis_id" => "produk_jenis_id",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
//                    array(
//                        "comName" => "FifoProdukJadi",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "produk_qty" => "qty",
////                            "gudang_id" => "gudangID",
//                            "gudang_id" => "gudangProjectID",
//                        ),
//                        "resultParams" => array(
//                            "rsltItems" => array(
//                                "id" => "produk_id",
//                                "nama" => "nama",
//                                "name" => "nama",
////                                "harga" => "hpp",
//                                "hpp" => "hpp",
//                                "jml" => "qty",
//                                "qty" => "qty",
//                                "hpp_riil" => "hpp_riil",
//                                "ppv_riil" => "ppv_riil",
//                                "subtotal" => "subtotal",
//                                "ppn_in" => "ppn_in",
//                                "ppn_in_nilai" => "ppn_in_nilai",
//                                "suppliers_id" => "suppliers_id",
//                                "suppliers_nama" => "suppliers_nama",
//                                "hpp_nppv" => "hpp_nppv",
//                                "produk_jenis" => "produk_jenis",
//                                "produk_jenis_id" => "produk_jenis_id",
//                            ),
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
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
            "9967sc" => array(
                "master" => array(),
                "detail" => array(),
            ),
            "9967" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030030" => "-hpp",//persediaan produk
                            "1010030040" => "hpp_riil",//persediaan produk riil
//                            "laba(rugi) selisih fifo return pembelian" => "selisih_fifo",
//                            "2010090010" => "-ppv_riil",//hutang lain ppv
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
                            "1010030030" => "-hpp",//persediaan produk
                            "1010030040" => "hpp_riil",//persediaan produk riil
                            //                            "laba(rugi) selisih fifo return pembelian" => "selisih_fifo",
//                            "2010090010" => "-ppv_riil",//hutang lain ppv
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
//                            "persediaan produk"                        => "-hpp",
                            "1010030040" => "-hpp_riil",//persediaan produk riil
                            "1010020030" => "nett",//piutang pembelian
                            "1010040050" => "-ppn",//ppn in belum ada faktur
//                            "laba(rugi) selisih fifo return pembelian" => "(hpp+ppn)-nett",
                            "7010050" => "(hpp_riil+ppn)-nett",//laba(rugi) selisih fifo return pembelian
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
                            //                            "persediaan produk"                        => "-hpp",
                            "1010030040" => "-hpp_riil",//persediaan produk riil
                            "1010020030" => "nett",//piutang pembelian
                            "1010040050" => "-ppn",//ppn in belum ada faktur
                            //                            "laba(rugi) selisih fifo return pembelian" => "(hpp+ppn)-nett",
                            "7010050" => "(hpp_riil+ppn)-nett",//laba(rugi) selisih fifo return pembelian
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
                            "1010020030" => "nett",//piutang pembelian
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
                            "1010040050" => "-ppn",//ppn in bekum ada faktur
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
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "gudangProjectID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
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
                            //							"produk_nilai" => "harga",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            //                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
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
                            //							"produk_nilai" => "harga",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            //                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "9967r" => array(
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
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "gudangProjectID",
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
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "gudangProjectID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                ),
            ),
            "9967sc" => array(
                "master" => array(),
                "detail" => array(
                    // rekening pembantu produk serial
                    array(
                        "comName" => "RekeningPembantuProdukPerSerial",
                        "loop" => array(
                            "1010030030" => ".-1",//persediaan produk, sub_diskon_nilai_total
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangProjectID",
                            "extern_id" => ".0",
                            "extern_nama" => "produk_serial",
                            "extern2_id" => ".0",
                            "extern2_nama" => "produk_sku_part_nama",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "produk_qty" => "-jml",
                            "produk_nilai" => ".1",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "supplierID" => "pihakID",
                        ),
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),
                ),
            ),
            "9967" => array(
                "master" => array(
                    // post procc payment anti source
                    array(
                        "comName" => "PaymentAntiSource",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "transaksi_id" => ".0",
                            "jenis" => ".0",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
//                            "label" => ".hutang dagang",
                            "label" => ".piutang pembelian",
                            "sisa" => "nett",
                        ),
                        "reversable" => true,
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
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "gudangProjectID",
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
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "gudangProjectID",
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
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "gudangProjectID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "ProdukSerialNumberLocker",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "produk_serial_number" => "produk_serial",
                            "jumlah" => ".0",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "qty_debet" => "-qty",
//                            "produk_nilai" => "hpp",
//                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
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
    "19967" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|placeID|olehID|supplierID",
            "stepCode|supplierID",
            "stepCode|placeID|supplierID",
            "stepCode|olehID|supplierID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "supplierID" => "pihakID",
                "supplierName" => "pihakName",

            ),
            "detail" => array(//===sumber nilai berupa rincian
                "ppn" => "(ppnFactor*harga)/100",
                "hpp_nppn" => "harga+ppn",
                "hpp_nppv" => "harga*ppv_index__nilai",
                "ppv" => "hpp_nppv-harga",
                "hpp_nppn_nppv" => "hpp_nppn+ppv",
                "nett" => "harga+ppn",
            ),
            "rsltItems" => array(//===sumber nilai berupa rincian

            ),
        ),
        "valueBuilders" => array(
            "grand_total" => "harga+ppn",
            "tagihan" => "grand_total-discount",
        ),
        "valueBuilders_rsltItems" => array(),
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


    ),
    //pembelian fg project
    // config po jasa projek
    "3463" => array(
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
                "place2ID" => "branch",
                "place2Name" => "branch__label",
                "customerID" => "customerProjek",
                "customerName" => "customerProjek__label",
//                "transaksi_id_target" => "transaksiData",
//                "transaksi_nomer_target" => "transaksiData__label",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "disc" => "(discPersen*harga)/100",
                "harga_disc" => "harga-disc",
                "dppPPn" => "harga_disc*(dpp_persen/100)",
                "dppPPh" => "harga_disc*pph",
                "ppn_persen" => ".10",
                "ppn" => "(ppn_persen/100)*dppPPn",
                "hpp_nppn" => "harga_disc+ppn",
                "nett" => "hpp_nppn",
                "max_dpp_persen" => ".100",
            ),
            "master_dependent" => array(
                "paymentMethod" => array(
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
            "ppn_value" => "nilai_dpp_ppn*ppnFactor/100",
            "payment_out" => "nett",
            "dppPph_dipakai" => "valid_pph_key*dppPPh",

        ),
        "preProcessor" => array(
            "3463" => array(
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
                "customers_id" => "customerID",
                "customers_nama" => "customerName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "harga",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "ppnPersenCheck" => "ppnPersenCheck",
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
                "keterangan" => "note",
            ),

        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "service",
            ),
        ),

        "components" => array(
            "3463" => array(
                "master" => array(
                    //region PO PUSAT
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030060" => "harga_disc",//projek cost
                            "1010040070" => "nilai_tambah_ppn_in",//ppn in jasa
                            "2010010" => "nilai_tambah_piutang_pembelian",//hutang dagang
                            "1010020030" => "-nilai_dipakai_piutang_pembelian",//piutang pembelian
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
                            "1010030060" => "harga_disc",//projek cost
                            "1010040070" => "nilai_tambah_ppn_in",//ppn in jasa
                            "2010010" => "nilai_tambah_piutang_pembelian",//hutang dagang
                            "1010020030" => "-nilai_dipakai_piutang_pembelian",//piutang pembelian
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
                            "1010020030" => "-nilai_dipakai_piutang_pembelian",//piutang pembelian
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
                            "2010010" => "nilai_tambah_piutang_pembelian",//hutang dagang
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
                            "1010040070" => "nilai_tambah_ppn_in",//ppn in jasa
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
                        "comName" => "RekeningPembantuProjek",
                        "loop" => array(
                            "1010030060" => "harga_disc",//projek cost
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "customerID",// konsumen
                            "extern_nama" => "customerName",// konsumen
//                            "extern2_id" => "transaksi_id_target",// so
//                            "extern2_nama" => "transaksi_nomer_target",// so
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion

                    //region PUSAT, PINDAH PROJEK COST
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030060" => "-harga_disc",//projek cost
                            "1010060010" => "harga_disc",//piutang cabang
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
                            "1010030060" => "-harga_disc",//projek cost
                            "1010060010" => "harga_disc",//piutang cabang
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
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060010" => "harga_disc",//piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "place2ID",
                            "cabang2_nama" => "place2Name",
                            "extern_id" => "place2ID",
                            "extern_nama" => "place2Name",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuProjek",
                        "loop" => array(
                            "1010030060" => "-harga_disc",//projek cost
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "customerID",// konsumen
                            "extern_nama" => "customerName",// konsumen
//                            "extern2_id" => "transaksi_id_target",// so
//                            "extern2_nama" => "transaksi_nomer_target",// so
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion PUSAT, PINDAH PROJEK COST

                    //region CABANG, TERIMA PROJEK COST
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030060" => "harga_disc",//projek cost
                            "2040010" => "harga_disc",//hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010030060" => "harga_disc",//projek cost
                            "2040010" => "harga_disc",//hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040010" => "harga_disc",//hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "cabang2_id" => "place2ID",
                            "cabang2_nama" => "place2Name",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuProjek",
                        "loop" => array(
                            "1010030060" => "harga_disc",//projek cost
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "customerID",// konsumen
                            "extern_nama" => "customerName",// konsumen
//                            "extern2_id" => "transaksi_id_target",// so
//                            "extern2_nama" => "transaksi_nomer_target",// so
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion

                    //region CABANG, KELUAR PROJEK COST ke HPP, HPP PROJEK
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030060" => "-harga_disc",//projek cost
                            "5030" => "harga_disc",//hpp projek
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010030060" => "-harga_disc",//projek cost
                            "5030" => "harga_disc",//hpp projek
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuProjek",
                        "loop" => array(
                            "1010030060" => "-harga_disc",//projek cost
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "customerID",// konsumen
                            "extern_nama" => "customerName",// konsumen
//                            "extern2_id" => "transaksi_id_target",// so
//                            "extern2_nama" => "transaksi_nomer_target",// so
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuHpp",
                        "loop" => array(
                            "5030" => "harga_disc",//hpp projek
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "customerID",// customer projek
                            "extern_nama" => "customerName",// customer projek
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion CABANG, KELUAR PROJEK COST ke HPP, HPP PROJEK
                ),
                "detail" => array(),
            ),
            "3113" => array(
                "master" => array(
                    //region seleish ppn 10 vs 11 %
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010040070" => "-selisih_ppn_realisasi",//ppn in jasa
                            "2010010" => "-selisih_ppn_realisasi",//hutang dagang

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
                            "1010040070" => "-selisih_ppn_realisasi",//ppn in jasa
                            "2010010" => "-selisih_ppn_realisasi",//hutang dagang
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
                            "2010010" => "-selisih_ppn_realisasi",//hutang dagang
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
                            "1010040070" => "-selisih_ppn_realisasi",//ppn in jasa
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
                    //endregion
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010040070" => "-nilai_tambah_ppn_in",//ppn in jasa
                            "1010040060" => "nilai_tambah_ppn_in",//ppn in realisasi
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
                            "1010040070" => "-nilai_tambah_ppn_in",//ppn in jasa
                            "1010040060" => "nilai_tambah_ppn_in",//ppn in realisasi
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
                            "1010040070" => "-nilai_tambah_ppn_in",//ppn in jasa
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
                ),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            "3463ro" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal_activity",
                        "loop" => array(
                            "activity" => ".1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".1",
//                            "step_number" => "step_number",
                            "nilai" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Jurnal_activityMain",
                        "loop" => array(
                            "activity" => ".1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".1",
//                            "step_number" => "step_number",
                            "nilai" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
            "3463o" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal_activity",
                        "loop" => array(
                            "activity" => ".1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".2",
//                            "step_number" => "step_number",
                            "nilai" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Jurnal_activityMain",
                        "loop" => array(
                            "activity" => ".1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".2",
//                            "step_number" => "step_number",
                            "nilai" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    array(
                        "comName" => "PriceSupplies",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "nilai" => "harga",
                            "cabang_id" => "placeID",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => ".jasa",
                            "jenis_value" => ".hpp",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
            "3463" => array(
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
                            "nilai" => "-nilai_dipakai_ppn_in",
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
                            "nilai" => "-nilai_dipakai_piutang_pembelian",
                            "transaksi_id" => "currentID",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "Jurnal_activity",
                        "loop" => array(
                            "activity" => ".1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".3",
//                            "step_number" => "step_number",
                            "nilai" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Jurnal_activityMain",
                        "loop" => array(
                            "activity" => ".1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".3",
//                            "step_number" => "step_number",
                            "nilai" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
            "3113" => array(
                "master" => array(
                    array(
                        "comName" => "PaymentSource",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".hutang dagang",
//                            "target_jenis" => "jenisTr",
                            "jenis" => ".463",
                            "transaksi_id" => "currentID",
                            "ppn_approved" => "nilai_tambah_ppn_in",
//                            "sisa" => "new_sisa",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "PaymentSource",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".hutang dagang",
//                            "target_jenis" => ".483",
                            "jenis" => ".463",
                            "transaksi_id" => "currentID",
                            "terbayar" => "selisih_ppn_realisasi",
                            "sisa" => "new_sisa",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Jurnal_activity",
                        "loop" => array(
                            "activity" => ".1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".4",
//                            "step_number" => "step_number",
                            "nilai" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Jurnal_activityMain",
                        "loop" => array(
                            "activity" => ".1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".4",
//                            "step_number" => "step_number",
                            "nilai" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
        ),
    ),

//pembelian barang bekas
    "468" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",
            "stepCode|referenceID",
        ),
//        "formatNota" => "stepCode,fulldate,stepCode|fulldate,placeID,stepCode|placeID,olehID,stepCode|olehID,pihakID,stepCode|pihakID",
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "supplierID" => "pihakID",
                "supplierName" => "pihakName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "hpp" => "harga",
                "ppn" => ".0",
                "hpp_nppn" => "harga+ppn",
//                "hpp_nppv" => "harga*ppv_index__nilai",
//                "ppv" => "hpp_nppv-harga",
                "hpp_nppv" => ".0",
                "ppv" => ".0",
                "hpp_nppn_nppv" => "hpp_nppn+ppv",
                "nett" => "harga+ppn", // yg dipakai di grand total
                //----------------------------
                "diskon_npph_nilai_total" => "diskon_nilai_total-diskon_pph23",
//                "laba_lain_lain" => "diskon_pph23",
                "laba_lain_lain" => "diskon_nilai_total",
            ),
            "master_dependent" => array(
                "paymentMethod" => array(
//                    "cash" => array(
//                        "nilai_cash" => "tagihan",
//                        "nilai_credit" => "0",
//                    ),
                    "credit" => array(
                        "nilai_credit" => "harga",
                        "nilai_cash" => "0",
                    ),
                    "cbd" => array(
                        "nilai_credit" => "harga",
                        "nilai_cash" => "0",
                    ),
                    "cia" => array(
                        "nilai_credit" => "harga",
                        "nilai_cash" => "0",
                    ),
                    "tt_adv" => array(
                        "nilai_credit" => "harga",
                        "nilai_cash" => "0",
                    ),
                ),
            ),
        ),
        "valueBuilders" => array(
            "grand_total" => "nett",
            "tagihan" => "grand_total",
//            "selisih_ppn_realisasi" => "nilai_tambah_ppn_in-ppn_realisasi",
//            "new_sisa" =>"nilai_tambah_piutang_pembelian-selisih_ppn_realisasi",
        ),
        "preProcessor" => array(
            "468" => array(
                "master" => array(
                    array(
                        "comName" => "GenerateVoucer",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".active",
//                          "jenis" => ".ppn in",
                            "jenis" => "jenisTr",
                            "produk_id" => "pihakID",
                            "nama" => "pihakName",
                            "nilai" => "harga",
//                            "transaksi_id" => "masterID",
                            "transaksi_id" => "currentID",
                            "oleh_id" => ".0",
                            "paymentMethod" => "paymentMethod",
                            "label" => "description",
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
            "468" => array(
                "master" => array(
                    //region jurnal pusat
                    #1
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030030" => "harga",//persediaan produk
                            "2010050" => "harga",//persediaan produk riil
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
                            "1010030030" => "harga",//persediaan produk
                            "2010050" => "harga",//persediaan produk riil
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    #rekening pembantu voucher/hutang ke konsumen masuk
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2010050" => "harga",//hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2010050070",//voucher
                            "extern_nama" => ".voucher",
                            "extern2_nama" => "paramVoucher",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
                            "2010050" => "harga",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".1125",//dimatikan selalu masuk ke konsumen generic
                            "extern_nama" => ".generic",
                            "extern2_id" => ".2010050070",// voucher
                            "extern2_nama" => ".voucher",
                            "extern3_nama" => "paramVoucher",//kode voucher
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //cache vouceher nya mask
                    array(
                        "comName" => "RekeningPembantuVoucher",
                        "loop" => array(
                            "2010050" => "harga",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "paramVoucher",//dimatikan selalu masuk ke generic
                            "extern_nama" => "paramVoucher",
                            "extern2_id" => "description_main_followup",// voucher
                            "extern2_nama" => "description_main_followup",
                            "extern3_nama" => "description",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    #2
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010060010" => "-harga",//piutang cabang
                            "2010050" => "-harga",//hutang voucher
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
                            "1010060010" => "-harga",//piutang cabang
                            "2010050" => "-harga",//hutang voucher
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    #rekening pembantu voucher/hutang ke konsumen dikeluarkan dari pusat masuk ke cabang
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2010050" => "-harga",//hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2010050070",//voucher
                            "extern_nama" => ".voucher",
                            "extern2_nama" => "paramVoucher",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
                            "2010050" => "-harga",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".1125",//dimatikan selalu masuk ke generic
                            "extern_nama" => ".generic",
                            "extern2_id" => ".2010050070",// voucher
                            "extern2_nama" => ".voucher",
                            "extern3_nama" => "paramVoucher",//kode voucher
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //cache vouceher nya keluar ke cabang
                    array(
                        "comName" => "RekeningPembantuVoucher",
                        "loop" => array(
                            "2010050" => "-harga",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "paramVoucher",//dimatikan selalu masuk ke generic
                            "extern_nama" => "paramVoucher",
                            "extern2_id" => "description_main_followup",// voucher
                            "extern2_nama" => "description_main_followup",
                            "extern3_nama" => "description",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    #rekening pembantu antar cabang
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060010" => "-harga",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "cabangTarget",
                            "cabang2_nama" => "cabangTarget__nama",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion


                    //region cabang
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "2040010" => "-harga",//hutang kepusat
                            "2010050" => "harga",//hutang voucher
                        ),
                        "static" => array(
                            "cabang_id" => "cabangTarget",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "2040010" => "-harga",//hutang kepusat
                            "2010050" => "harga",//hutang voucher
                        ),
                        "static" => array(
                            "cabang_id" => "cabangTarget",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    #rekening pembantu voucher/hutang ke konsumen  masuk ke cabang
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2010050" => "harga",//hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "cabangTarget",
                            "extern_id" => ".2010050070",//voucher
                            "extern_nama" => ".voucher",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
                            "2010050" => "harga",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "cabangTarget",
                            "extern_id" => ".1125",//dimatikan selalu masuk ke generic
                            "extern_nama" => ".generic",
                            "extern2_id" => ".2010050070",// voucher
                            "extern2_nama" => ".voucher",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuVoucher",
                        "loop" => array(
                            "2010050" => "harga",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "cabangTarget",
                            "extern_id" => "paramVoucher",//dimatikan selalu masuk ke generic
                            "extern_nama" => "paramVoucher",
                            "extern2_id" => "description_main_followup",// voucher
                            "extern2_nama" => "description_main_followup",
                            "extern3_nama" => "description",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    #rekening pembantu antar cabang
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040010" => "-harga",// hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "cabangTarget",
                            "cabang2_id" => "cabangTarget",
                            "cabang2_nama" => "cabangTarget__nama",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion

                ),
                "detail" => array(
                    //rekening pembantu produk di pusat
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "sub_harga",//persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "produk_nilai" => "harga",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "supplierID" => "pihakID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "468" => array(
                "master" => array(
                    array(
                        "comName" => "PaymentUangMukaCustomer",
                        "loop" => array(
                            "2010050" => "harga",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "cabangTarget",
                            "cabang_nama" => "cabangTarget__nama",
                            "gudang_id" => ".0",
                            "extern_id" => ".1125",//konsumen generic sengaja karena perlu pembantu
                            "extern_nama" => ".generic",
                            "nilai" => "harga",
                            "label" => ".voucher",
                            "extern_label2" => "paramVoucher",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
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
                            "produk_nilai" => "harga",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),

        ),

        "closedRequest" => array(
            "468" => array(
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
    ),
    "968" => array(
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
                "ppn" => "(ppnFactor*harga)/100",
                "hpp_nppn" => "harga+ppn",
                "hpp_nppv" => "harga*ppv_index__nilai",
                "ppv" => "hpp_nppv-harga",
                "hpp_nppn_nppv" => "hpp_nppn+ppv",
                "nett" => "harga+ppn",
            ),
            "rsltItems" => array(//===sumber nilai berupa rincian
//                "fifo_riil" => "hpp/1.25",
//                "ppv" => "hpp-fifo_riil",
            ),
        ),
        "valueBuilders" => array(
//            "ppv" => "hpp-hpp_riil",
//            "selisih_fifo" => "(hpp+ppn)-(nett+ppv)",
            "selisih_fifo" => "(hpp+ppn)-(nett)",
        ),
        "valueBuilders_rsltItems" => array(//            "hpp" => "sub_hpp",

        ),
        "preProcessor" => array(
            "967sc" => array(
                "master" => array(
                    //untuk reguler terbit items3_sum
                    array(
                        "comName" => "ProdukSerialNumberExtractor",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "jenisTr" => "jenisTrMaster",
                            "step_number" => "step_number",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
            "967" => array(
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
            "967" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030030" => "-hpp",//persediaan produk
                            "1010030040" => "hpp",//persediaan produk riil
//                            "laba(rugi) selisih fifo return pembelian" => "selisih_fifo",
//                            "2010090010" => "-ppv_riil",//hutang lain ppv
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
                            "1010030030" => "-hpp",//persediaan produk
                            "1010030040" => "hpp",//persediaan produk riil
                            //                            "laba(rugi) selisih fifo return pembelian" => "selisih_fifo",
//                            "2010090010" => "-ppv_riil",//hutang lain ppv
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
//                            "persediaan produk"                        => "-hpp",
                            "1010030040" => "-hpp",//persediaan produk riil
                            "1010020030" => "nett",//piutang pembelian
                            "1010040050" => "-ppn",//ppn in belum ada faktur
//                            "7010050" => "(hpp_riil+ppn)-nett",//laba(rugi) selisih fifo return pembelian
                            "7010050" => "nett-(hpp+ppn)",//laba(rugi) selisih fifo return pembelian
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
                            //                            "persediaan produk"                        => "-hpp",
                            "1010030040" => "-hpp",//persediaan produk riil
                            "1010020030" => "nett",//piutang pembelian
                            "1010040050" => "-ppn",//ppn in belum ada faktur
//                            "7010050" => "(hpp_riil+ppn)-nett",//laba(rugi) selisih fifo return pembelian
                            "7010050" => "nett-(hpp+ppn)",//laba(rugi) selisih fifo return pembelian
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
                        "comName" => "RekeningPembantuPiutangSupplierMain",
                        "loop" => array(
                            "1010020030" => "nett",//piutang pembelian
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "extern_id" => ".1010020030010",
//                            "extern_nama" => ".Return Pembelian",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierDetailMain",
                        "loop" => array(
                            "1010020030" => "nett",//piutang pembelian
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".1010020030010",
                            "extern_nama" => ".Return Pembelian",
                            "extern2_id" => "pihakID",
                            "extern2_nama" => "pihakName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "1010040050" => "-ppn",//ppn in bekum ada faktur
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
                    //<editor-fold desc="Post-rekening pembantu, detail">
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
                            //							"produk_nilai" => "harga",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            //                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030040" => "sub_hpp",//persediaan produk riil
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            //							"produk_nilai" => "harga",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            //                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030040" => "-sub_hpp",//persediaan produk riil
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-qty",
                            //							"produk_nilai" => "harga",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            //                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //</editor-fold>

                    // rekening pembantu produk serial
                    array(
                        "comName" => "RekeningPembantuProdukPerSerial",
                        "loop" => array(
                            "1010030030" => ".-1",//persediaan produk, sub_diskon_nilai_total
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "extern_id" => ".0",
                            "extern_nama" => "produk_serial",
                            "extern2_id" => ".0",
                            "extern2_nama" => "produk_sku_part_nama",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "produk_qty" => "-jml",
                            "produk_nilai" => ".1",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "supplierID" => "pihakID",
                            "kategori_id" => "kategori_id",//ini untuk skip produk jasa
                        ),
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "967r" => array(
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
            "967" => array(
                "master" => array(
                    // post procc payment anti source
                    array(
                        "comName" => "PaymentAntiSource",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "transaksi_id" => ".0",
                            "jenis" => ".0",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
//                            "label" => ".hutang dagang",
                            "label" => ".piutang pembelian",
                            "sisa" => "nett",
                        ),
                        "reversable" => true,
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
                    array(
                        "comName" => "ProdukSerialNumberLocker",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "produk_serial_number" => "produk_serial",
                            "jumlah" => ".0",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "qty_debet" => "-qty",
//                            "produk_nilai" => "hpp",
//                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
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
);