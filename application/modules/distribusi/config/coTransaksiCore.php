<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/22/2018
 * Time: 8:38 PM
 */
$config["coTransaksiCore"] = array(

    //  config distribusi dari dc ke cabang
    "583" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabang2ID",
            "stepCode|placeID|cabang2ID",
        ),
        "formatNota" => "stepCode|placeID|cabang2ID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                //                "pihakID" => "pihakID",
                //                "pihakName" => "pihakName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                "cabang2ID" => "pihakID",
                "cabang2Name" => "pihakName",
                "place2ID" => "pihakID",
                "place2Name" => "pihakName",
                "gudang2ID" => "gudang",
                "gudang2Name" => "gudang__label",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                "stok" => "current_stok-intransit_stok-jml",
//                "stok_tersedia" => "current_stok-intransit_stok",

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

                //                "pihakID" => "pihakID",
                //                "pihakName" => "pihakName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
            ),
            "detail_rsltItems" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",

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
        "preProcessor" => array(
            "583r" => array(
                "master" => array(),
                "detail" => array(),

            ),
            "583sc" => array(
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
                    //untuk paket terbit items8_sum
                    array(
                        "comName" => "ProdukSerialNumberExtractorPaket",
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
                "sub_detail" => array(
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "jml",
                            "gudang_id" => "gudangID",
                            "kategori_id" => "kategori_id",
                            "kategori_nama" => "kategori_nama",//untuk skiper jika produk adalah jasa
                            "harga" => "harga",
                            "transaksi_id" => "masterID",
                        ),
                        "srcGateName" => "items10_sum",// barang dari paket
                        "srcRawGateName" => "items10_sum",// barang dari paket
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

                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "hpp",
                //                "transaksi_net" => "hpp",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "gudang2_id" => "gudang2ID",
                "gudang2_nama" => "gudang2Name",
                //---------------
                "salesman_id" => "pihakMain2ID",
                "salesman_nama" => "pihakMain2Name",
                "gudang_status_id" => "pihakMainID",
                "gudang_status_nama" => "pihakMainName",
                "gudang_status_jenis" => "pihakMainJenis",
                "reference_jenis" => "requestReferenceJenis",
                "reference_id" => "requestReferenceID",
                "reference_nomer" => "requestReferenceNomer",
                "reference_id_top" => "requestReferenceIDTop",
                "reference_nomer_top" => "requestReferenceNomerTop",
                "reference_jenis_top" => "requestReferenceJenisTop",
                "reference_jenis_master" => "requestReferenceJenisMaster",

                "reference_cabang_id" => "reference_cabang_id",
                "reference_cabang_nama" => "reference_cabang_nama",
                "reference_gudang_id" => "reference_gudang_id",
                "reference_gudang_nama" => "reference_gudang_nama",
                "reference_terima_barang" => "terima_barang",
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
                "satuan" => "satuan",
            ),
            "items" => array(
                "handler" => "handler",
                "jml" => "jml",
                "harga" => "harga",
                "subtotal" => "subtotal",
                "jml_serial" => "jml_serial",
                "satuan" => "satuan",
                "discount_persen" => "discount_persen",
                "discount_qty" => "discount_qty",
                "jual" => "jual",
                "jenis" => "jenis",
                "nama" => "nama",
                "produk_kode" => "produk_kode",
                "no_part" => "no_part",
                "label" => "label",
                "current_stok" => "current_stok",
                "intransit_stok" => "intransit_stok",
                "berat_gross" => "berat_gross",
                "lebar_gross" => "lebar_gross",
                "panjang_gross" => "panjang_gross",
                "tinggi_gross" => "tinggi_gross",
                "volume_gross" => "volume_gross",
                "produk_jenis_id" => "produk_jenis_id",
                "produk_jenis_nama" => "produk_jenis_nama",
                "barcode" => "barcode",
                "kategori_id" => "kategori_id",
                "kategori_nama" => "kategori_nama",
                "outdoor_id" => "outdoor_id",
                "outdoor_nama" => "outdoor_nama",
                "outdoor_barcode" => "outdoor_barcode",
                "indoor_id_1" => "indoor_id_1",
                "indoor_nama_1" => "indoor_nama_1",
                "indoor_barcode_1" => "indoor_barcode_1",
                "indoor_id_2" => "indoor_id_2",
                "indoor_nama_2" => "indoor_nama_2",
                "indoor_barcode_2" => "indoor_barcode_2",
                "indoor_id_3" => "indoor_id_3",
                "indoor_nama_3" => "indoor_nama_3",
                "indoor_barcode_3" => "indoor_barcode_3",
                "indoor_id_4" => "indoor_id_4",
                "indoor_nama_4" => "indoor_nama_4",
                "indoor_barcode_4" => "indoor_barcode_4",
                "qty_outdoor" => "qty_outdoor",
                "qty_indoor" => "qty_indoor",
                "keterangan" => "keterangan",
                "static_keterangan" => "static_keterangan",
                "sub_qty_indoor" => "sub_qty_indoor",
                "sub_qty_outdoor" => "sub_qty_outdoor",
                "produk_part_id_1" => "produk_part_id_1",
                "produk_part_nama_1" => "produk_part_nama_1",
                "produk_part_barcode_1" => "produk_part_barcode_1",
                "produk_part_id_2" => "produk_part_id_2",
                "produk_part_nama_2" => "produk_part_nama_2",
                "produk_part_barcode_2" => "produk_part_barcode_2",
                "heater_id" => "heater_id",
                "heater_nama" => "heater_nama",
                "heater_barcode" => "heater_barcode",
                "scan_mode" => "scan_mode",
                "olehID" => "olehID",
                "olehName" => "olehName",
                "sellerID" => "sellerID",
                "sellerName" => "sellerName",
                "pihakID" => "pihakID",
                "pihakName" => "pihakName",
                "placeID" => "placeID",
                "placeName" => "placeName",
                "cabangID" => "cabangID",
                "cabangName" => "cabangName",
                "gudangID" => "gudangID",
                "gudangName" => "gudangName",
                "place2ID" => "place2ID",
                "place2Name" => "place2Name",
                "cabang2ID" => "cabang2ID",
                "cabang2Name" => "cabang2Name",
                "gudang2ID" => "gudang2ID",
                "gudang2Name" => "gudang2Name",
                "jenisTr" => "jenisTr",
                "jenisTrMaster" => "jenisTrMaster",
                "ppnFactor" => "ppnFactor",
                "stok" => "stok",
                "qty" => "qty",
                "name" => "name",
                "sub_harga" => "sub_harga",
                "sub_subtotal" => "sub_subtotal",
                "sub_jml_serial" => "sub_jml_serial",
                "sub_discount_persen" => "sub_discount_persen",
                "sub_discount_qty" => "sub_discount_qty",
                "sub_jual" => "sub_jual",
                "sub_current_stok" => "sub_current_stok",
                "sub_intransit_stok" => "sub_intransit_stok",
                "sub_berat_gross" => "sub_berat_gross",
                "sub_lebar_gross" => "sub_lebar_gross",
                "sub_panjang_gross" => "sub_panjang_gross",
                "sub_tinggi_gross" => "sub_tinggi_gross",
                "sub_volume_gross" => "sub_volume_gross",
                "sub_produk_jenis_id" => "sub_produk_jenis_id",
                "sub_produk_jenis_nama" => "sub_produk_jenis_nama",
                "sub_kategori_id" => "sub_kategori_id",
                "sub_produk_part_id_1" => "sub_produk_part_id_1",
                "sub_produk_part_nama_1" => "sub_produk_part_nama_1",
                "sub_produk_part_barcode_1" => "sub_produk_part_barcode_1",
                "sub_produk_part_id_2" => "sub_produk_part_id_2",
                "sub_produk_part_nama_2" => "sub_produk_part_nama_2",
                "sub_produk_part_barcode_2" => "sub_produk_part_barcode_2",
                "sub_stok" => "sub_stok",
                "next_substep_code" => "next_substep_code",
                "next_subgroup_code" => "next_subgroup_code",
                "sub_step_number" => "sub_step_number",
                "sub_step_current" => "sub_step_current"
            ),
            "items9_sum" => array(
                "handler" => "handler",
                "jml" => "jml",
                "harga" => "harga",
                "subtotal" => "subtotal",
                "jml_serial" => "jml_serial",
                "satuan" => "satuan",
                "discount_persen" => "discount_persen",
                "discount_qty" => "discount_qty",
                "jual" => "jual",
                "jenis" => "jenis",
                "nama" => "nama",
                "produk_kode" => "produk_kode",
                "no_part" => "no_part",
                "label" => "label",
                "current_stok" => "current_stok",
                "intransit_stok" => "intransit_stok",
                "berat_gross" => "berat_gross",
                "lebar_gross" => "",
                "panjang_gross" => "lebar_gross",
                "tinggi_gross" => "tinggi_gross",
                "volume_gross" => "volume_gross",
                "produk_jenis_id" => "produk_jenis_id",
                "produk_jenis_nama" => "produk_jenis_nama",
                "barcode" => "barcode",
                "kategori_id" => "kategori_id",
                "kategori_nama" => "kategori_nama",
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
                "produk_part_id_1" => "produk_part_id_1",
                "produk_part_nama_1" => "produk_part_nama_1",
                "produk_part_barcode_1" => "produk_part_barcode_1",
                "produk_part_id_2" => "produk_part_id_2",
                "produk_part_nama_2" => "produk_part_nama_2",
                "produk_part_barcode_2" => "produk_part_barcode_2",
                "heater_id" => "heater_id",
                "heater_nama" => "heater_nama",
                "heater_barcode" => "heater_barcode",
                "scan_mode" => "scan_mode",
                "olehID" => "olehID",
                "olehName" => "olehName",
                "sellerID" => "sellerID",
                "sellerName" => "sellerName",
                "pihakID" => "pihakID",
                "pihakName" => "pihakName",
                "placeID" => "placeID",
                "placeName" => "placeName",
                "cabangID" => "cabangID",
                "cabangName" => "cabangName",
                "gudangID" => "gudangID",
                "gudangName" => "gudangName",
                "place2ID" => "place2ID",
                "place2Name" => "place2Name",
                "cabang2ID" => "cabang2ID",
                "cabang2Name" => "cabang2Name",
                "gudang2ID" => "gudang2ID",
                "gudang2Name" => "gudang2Name",
                "jenisTr" => "jenisTr",
                "jenisTrMaster" => "jenisTrMaster",
                "ppnFactor" => "ppnFactor",
                "stok" => "stok",
                "qty" => "qty",
                "name" => "name",
                "sub_harga" => "sub_harga",
                "sub_subtotal" => "sub_subtotal",
                "sub_jml_serial" => "sub_jml_serial",
                "sub_discount_persen" => "sub_discount_persen",
                "sub_discount_qty" => "sub_discount_qty",
                "sub_jual" => "sub_jual",
                "sub_current_stok" => "sub_current_stok",
                "sub_intransit_stok" => "sub_intransit_stok",
                "sub_berat_gross" => "sub_berat_gross",
                "sub_lebar_gross" => "sub_lebar_gross",
                "sub_panjang_gross" => "sub_panjang_gross",
                "sub_tinggi_gross" => "sub_tinggi_gross",
                "sub_volume_gross" => "sub_volume_gross",
                "sub_produk_jenis_id" => "sub_produk_jenis_id",
                "sub_produk_jenis_nama" => "sub_produk_jenis_nama",
                "sub_kategori_id" => "sub_kategori_id",
                "sub_produk_part_id_1" => "sub_produk_part_id_1",
                "sub_produk_part_nama_1" => "sub_produk_part_nama_1",
                "sub_produk_part_barcode_1" => "sub_produk_part_barcode_1",
                "sub_produk_part_id_2" => "sub_produk_part_id_2",
                "sub_produk_part_nama_2" => "sub_produk_part_nama_2",
                "sub_produk_part_barcode_2" => "sub_produk_part_barcode_2",
                "sub_stok" => "sub_stok",
                "next_substep_code" => "next_substep_code",
                "next_subgroup_code" => "next_subgroup_code",
                "sub_step_number" => "sub_step_number",
                "sub_step_current" => "sub_step_current",
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
        "components" => array(
            "583r" => array(
                "master" => array(),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            "583r" => array(
                "master" => array(
                    //region ERP
                    array(
                        "comName" => "RekeningPembantuTransaksiDistribusi",// oke
                        "loop" => array(
                            "583r" => "harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "transaksi_id",
                            "produk_nama" => "transaksi_no",
                            "produk_nilai" => "hpp",
                            "extern_id" => "pihakID",
                        ),
                        "reversable" => false,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "loadByModules" => true,
                    ),//nulis ke table pembelian_pembantu_transaksi_cache dan mutasi
                    //endregionERP
                    array(
                        "comName" => "LockerTransaksiMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "jenis" => ".transaksi",
                            "jenis_locker" => ".transaksi",
                            "state" => ".hold",
                            "jumlah" => ".1",
                            "produk_id" => "requestReferenceID",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "requestReferenceID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",// barang dari reguler
                        "srcRawGateName" => "main",// barang dari reguler
                    ),
                ),
                "detail" => array(
                    //region ERP
                    array(
                        "comName" => "RekeningTransaksiDataDistribusi",
                        "loop" => array(
                            "583r" => "sub_harga",
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
                        "loadByModules" => true,
                    ),//nulis ke table _rek_penjualan_transkasi_data dan mutasi
                    array(
                        "comName" => "RekeningTransaksiDataDistribusiCache",
                        "loop" => array(
                            "583r" => "sub_harga",
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
                        "loadByModules" => true,
                    ),//nulis ke table casche produk sebagai index,rekening dan mutasi
                    array(
                        "comName" => "TransaksiDataDistribusi",
                        "loop" => array(
                            "583r" => "sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => "qty",
                            "extern_id" => "transaksi_id",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                        "loadByModules" => true,
                    ),//untuk update pembelian_transaki_data
                    //endregion
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
                            "oleh_nama" => "",
                            "transaksi_id" => ".0",
                            "nomer" => ".0",
                            "gudang_id" => "gudangID",
                            "kategori_id" => "kategori_id",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items9_sum",// barang dari reguler
                        "srcRawGateName" => "items9_sum",// barang dari reguler
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
                            "oleh_nama" => "",
//                            "transaksi_id" => "transaksi_id",//kalau butuh sesuai transaksi yang tercipta ,disini dimatikan biar dinject oleh controller
                            "nomer" => "nomer",
                            "gudang_id" => "gudangID",
                            "kategori_id" => "kategori_id",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items9_sum",// barang dari reguler
                        "srcRawGateName" => "items9_sum",// barang dari reguler
                    ),

//                    array(
//                        "comName" => "TransaksiItemUpdate",
//                        "loop" => array(),
//                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "gudang_id" => "gudangID",
//                            "jenis" => ".produk",
//                            "jumlah" => "qty",
//                            "produk_id" => "id",
//                            "produk_nama" => "name",
//                            "reference_id_so_so" => "requestReferenceID",
//                            "reference_id_so_po" => "requestReferenceID",
////                            "satuan" => "satuan",
////                            "oleh_id" => ".0",
////                            "oleh_nama" => "",
////                            "transaksi_id" => "transaksi_id",
////                            "nomer" => "nomer",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
                ),
            ),
            "583" => array(
                "master" => array(
                    //region ERP
                    array(
                        "comName" => "RekeningPembantuTransaksiDistribusi",
                        "loop" => array(
                            "583r" => "-harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTrMaster",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "currentID",//klau posisi mengurangi wajib bawa ini
//                            "produk_nama" => "transaksi_no",
                            "produk_nilai" => "harga",
                            "extern_id" => "pihakID",
                        ),
                        "reversable" => false,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "loadByModules" => true,
                    ),//nulis ke table pembelian_pembantu_transaksi_cache dan mutasi
                    array(
                        "comName" => "RekeningPembantuTransaksiDistribusi",
                        "loop" => array(
                            "583" => "harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTrMaster",
                            "master_jenis" => "jenisTrMaster",
//                            "produk_id" => "trasnaksi_id",//masuk tidak perlu karena akan diinject pakai transkai+id hasul followup
//                            "produk_nama" => "transaksi_no",
                            "produk_nilai" => "harga",
                            "extern_id" => "pihakID",
                        ),
                        "reversable" => false,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "loadByModules" => true,
                    ),//nulis ke table pembelian_pembantu_transaksi_cache dan mutasi
                    //endregionERP
                ),
                "detail" => array(
                    //region ERP

                    array(
                        "comName" => "RekeningTransaksiDataDistribusi",
                        "loop" => array(
                            "583r" => "-sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTrMaster",
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
                        "loadByModules" => true,
                    ),//nulis ke table _rek_penjualan_transkasi_data dan mutasi
                    array(
                        "comName" => "RekeningTransaksiDataDistribusi",
                        "loop" => array(
                            "583" => "sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTrMaster",
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
                        "loadByModules" => true,
                    ),//nulis ke table _rek_penjualan_transkasi_data dan mutasi

                    array(
                        "comName" => "RekeningTransaksiDataDistribusiCache",
                        "loop" => array(
                            "583r" => "-sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTrMaster",
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
                        "loadByModules" => true,
                    ),//nulis ke table casche produk sebagai index,rekening dan mutasi
                    array(
                        "comName" => "RekeningTransaksiDataDistribusiCache",
                        "loop" => array(
                            "583" => "sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTrMaster",
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
                        "loadByModules" => true,
                    ),//nulis ke table casche produk sebagai index,rekening dan mutasi

                    array(
                        "comName" => "TransaksiDataDistribusi",
                        "loop" => array(
                            "583r" => "-sub_harga",
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
                        "loadByModules" => true,
                    ),//untuk update distribusi_transaki_data
                    array(
                        "comName" => "TransaksiDataDistribusi",
                        "loop" => array(
                            "583" => "sub_harga",
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
                        "loadByModules" => true,
                    ),//untuk update distribusi_transaki_data

                    //endregion
                ),

                //endregionERP
            ),
        ),
        //-----
        "countersEdit" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabang2ID",
            "stepCode|placeID|cabang2ID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|cabang2ID",
            "stepCode|masterID|placeID|cabang2ID",
        ),
        "formatNotaEdit" => "stepCode|placeID|cabang2ID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabang2ID",
            "stepCode|placeID|cabang2ID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|cabang2ID",
            "stepCode|masterID|placeID|cabang2ID",
        ),
        "formatNotaReject" => "stepCode|placeID|cabang2ID",
    ),
    //  config penerimaan distribusi
    "585__old" => array(
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
                "pihakID" => "pihakID",
                "pihakName" => "pihakName",
                "cabangID" => "placeID",
                "cabangName" => "placeName",
                "gudangID" => "gudangID",
                "gudangName" => "gudangName",

                "cabang2ID" => "cabang2ID",
                "cabang2Name" => "cabang2Name",
                "place2ID" => "place2ID",
                "place2Name" => "place2Name",
                "gudang2ID" => "gudang2ID",
                "gudang2Name" => "gudang2Name",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "dtime" => "dtime",
                "id" => "id",
                "code" => "code",
                "label" => "label",
                "name" => "nama",
                "qty" => "jml",
                "satuan" => "satuan",
                "note" => "note",

                //"berat"         => "berat",
                //"lebar"         => "lebar",
                //"panjang"       => "panjang",
                //"tinggi"        => "tinggi",
                //"volume"        => "volume",
                "berat_gross" => "berat_gross",
                "lebar_gross" => "lebar_gross",
                "panjang_gross" => "panjang_gross",
                "tinggi_gross" => "tinggi_gross",
                "volume_gross" => "volume_gross",

                "hpp" => "hpp",
                "harga" => "harga",
                //                "sub_hpp" => "sub_hpp",
                //                "sub_harga" => "sub_harga",

                "pihakID" => "pihakID",
                "pihakName" => "pihakName",
                "cabangID" => "placeID",
                "cabangName" => "placeName",
                "olehID" => "olehID",
                "olehName" => "olehName",
            ),
            "detail_rsltItems" => array(//===sumber nilai berupa rincian
                "dtime" => "dtime",
                "id" => "id",
                "code" => "code",
                "label" => "label",
                "name" => "nama",
                "qty" => "jml",
                "satuan" => "satuan",
                "note" => "note",

                //"berat"         => "berat",
                //"lebar"         => "lebar",
                //"panjang"       => "panjang",
                //"tinggi"        => "tinggi",
                //"volume"        => "volume",
                "berat_gross" => "berat_gross",
                "lebar_gross" => "lebar_gross",
                "panjang_gross" => "panjang_gross",
                "tinggi_gross" => "tinggi_gross",
                "volume_gross" => "volume_gross",

                "hpp" => "hpp",
                "harga" => "harga",
                //                "sub_hpp" => "sub_hpp",
                //                "sub_harga" => "sub_harga",

                "pihakID" => "pihakID",
                "pihakName" => "pihakName",
                "cabangID" => "placeID",
                "cabangName" => "placeName",
                "olehID" => "olehID",
                "olehName" => "olehName",
            ),
        ),
        "valueBuilders" => array(),
        "valueBuilders_rsltItems" => array(),
        "preProcessor" => array(
            "585" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => "gudang2ID",
                            "kategori_id" => "kategori_id",
                        ),
                        "resultParams" => array(
                            "items" => array(
                                "harga" => "hpp",
                                "hpp" => "hpp",
                                "hpp_riil" => "hpp_riil",
                                "ppv_riil" => "ppv_riil",

                                "ppn_in" => "ppn_in",
                                "ppn_in_nilai" => "ppn_in_nilai",
                                "suppliers_id" => "suppliers_id",
                                "suppliers_nama" => "suppliers_nama",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
                "sub_detail" => array(
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "jml",
                            "gudang_id" => "gudang2ID",
                            "kategori_id" => "kategori_id",
                            "kategori_nama" => "kategori_nama",//untuk skiper jika produk adalah jasa
                            "harga" => "harga",
                        ),
                        "resultParams" => array(
                            "items6" => array(
                                //target=>builder preproc
                                "hpp_paket" => "hpp",
                                "hpp_riil_paket" => "hpp_riil",
//                                "ppv_riil" => "ppv_riil",
                                "ppn_in_paket" => "ppn_in",
                                "ppn_in_nilai_paket" => "ppn_in_nilai",
                                "suppliers_id_paket" => "suppliers_id",
                                "suppliers_nama_paket" => "suppliers_nama",
                                "harga_jasa_paket" => "harga_jasa",
                            ),
                        ),
                        "srcGateName" => "items6",
                        "srcRawGateName" => "items6",
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

                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "hpp",
                //                "transaksi_net" => "hpp",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                //---------------
                "salesman_id" => "pihakMain2ID",
                "salesman_nama" => "pihakMain2Name",
                "gudang_status_id" => "pihakMainID",
                "gudang_status_nama" => "pihakMainName",
                "gudang_status_jenis" => "pihakMainJenis",
                "reference_jenis" => "requestReferenceJenis",
                "reference_id" => "requestReferenceID",
                "reference_nomer" => "requestReferenceNomer",
                "reference_id_top" => "requestReferenceIDTop",
                "reference_nomer_top" => "requestReferenceNomerTop",
                "reference_jenis_top" => "requestReferenceJenisTop",
                "reference_jenis_master" => "requestReferenceJenisMaster",
            ),
            "detail" => array(
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
            "rsltItemsValues" => array(
                "hpp" => "hpp",
                //                "nett" => "hpp",
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
            "585" => array(
                "master" => array(
                    //<editor-fold desc="komponen milik pusat">
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030030" => "-hpp",// persediaan produk
                            "1010060010" => "hpp",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010030030" => "-hpp",// persediaan produk
                            "1010060010" => "hpp",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060010" => "hpp",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "cabang2_id" => "pihakID",
                            "cabang2_nama" => "pihakName",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //</editor-fold>

                    //region jurnal kelaurain stok dari paket
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030030" => "-hpp_paket",// persediaan produk
                            "1010060010" => "hpp_paket",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010030030" => "-hpp_paket",// persediaan produk
                            "1010060010" => "hpp_paket",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060010" => "hpp_paket",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "cabang2_id" => "pihakID",
                            "cabang2_nama" => "pihakName",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    //endregion

                    //<editor-fold desc="komponen milik cabang">
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030030" => "hpp",// persediaan produk
                            "2040010" => "hpp",// hutang ke pusat

                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010030030" => "hpp",// persediaan produk
                            "2040010" => "hpp",// hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040010" => "hpp",// hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "extern_id" => "place2ID",
                            "extern_nama" => "place2Name",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //</editor-fold>

                    //region stok masuk dari paket
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030030" => "hpp_paket",// persediaan produk
                            "2040010" => "hpp_paket",// hutang ke pusat

                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010030030" => "hpp_paket",// persediaan produk
                            "2040010" => "hpp_paket",// hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040010" => "hpp_paket",// hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "extern_id" => "place2ID",
                            "extern_nama" => "place2Name",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion

                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "-sub_hpp",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudang2ID",
                            "kategori_id" => "kategori_id",
                            "kategori_nama" => "kategori_nama",//untuk skiper jika produk adalah jasa
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // rekening pembantu produk serial
                    array(
                        "comName" => "RekeningPembantuProdukPerSerial",
                        "loop" => array(
                            "1010030030" => ".-1",//persediaan produk, sub_diskon_nilai_total
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "gudang_id" => "gudang2ID",
                            "extern_id" => ".0",
                            "extern_nama" => "produk_serial",
                            "extern2_id" => ".0",
                            "extern2_nama" => "produk_sku_part_nama",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "produk_qty" => "-jml",
                            "produk_nilai" => ".1",
                        ),
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),
                    // rekening pembantu produk serial dari paket
                    array(
                        "comName" => "RekeningPembantuProdukPerSerial",
                        "loop" => array(
                            "1010030030" => ".1",//persediaan produk, sub_diskon_nilai_total
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "gudang_id" => "gudang2ID",
                            "extern_id" => ".0",
                            "extern_nama" => "produk_serial",
                            "extern2_id" => ".0",
                            "extern2_nama" => "produk_sku_part_nama",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "produk_qty" => "jml",
                            "produk_nilai" => ".1",
                        ),
                        "srcGateName" => "items8_sum",
                        "srcRawGateName" => "items8_sum",
                    ),


                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "sub_hpp",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "jml",
                            "produk_nilai" => "hpp",
                            //                            "gudang_id" => ".0",
                            "gudang_id" => "gudangID",
                            "kategori_id" => "kategori_id",
                            "kategori_nama" => "kategori_nama",//untuk skiper jika produk adalah jasa
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // rekening pembantu produk serial dari reguler
                    array(
                        "comName" => "RekeningPembantuProdukPerSerial",
                        "loop" => array(
                            "1010030030" => ".1",//persediaan produk, sub_diskon_nilai_total
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
                            "produk_qty" => "jml",
                            "produk_nilai" => ".1",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "supplierID" => "pihakID",
                        ),
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),
                    // rekening pembantu produk serial dari paket
                    array(
                        "comName" => "RekeningPembantuProdukPerSerial",
                        "loop" => array(
                            "1010030030" => ".1",//persediaan produk, sub_diskon_nilai_total
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
                            "produk_qty" => "jml",
                            "produk_nilai" => ".1",
                        ),
                        "srcGateName" => "items8_sum",
                        "srcRawGateName" => "items8_sum",
                    ),

                ),
                "sub_detail" => array(
                    //rekening pembantu produk dari paket pusat
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "-sub_hpp_paket",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "-jml",
                            "produk_nilai" => "hpp_paket",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "kategori_id" => "kategori_id",//ini untuk skip jika produk jasa
                            "extern2_id" => "produk_paket_id",
                            "extern2_nama" => "produk_paket_nama",
                        ),
                        "srcGateName" => "items6",
                        "srcRawGateName" => "items6",
                    ),
                    //rekening pembantu produk masuk paket cabang
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "sub_hpp_paket",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "jml",
                            "produk_nilai" => "hpp_paket",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "kategori_id" => "kategori_id",//ini untuk skip jika produk jasa
                            "extern2_id" => "produk_paket_id",
                            "extern2_nama" => "produk_paket_nama",
                        ),
                        "srcGateName" => "items6",
                        "srcRawGateName" => "items6",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "585" => array(
                "master" => array(),
                "detail" => array(
                    //<editor-fold desc="Postproc-locker milik pusat">
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => "",
                            "transaksi_id" => "masterID",
                            "nomer" => "nomer",
                            "gudang_id" => "gudang2ID",
                            "kategori_id" => "kategori_id",
                            "kategori_nama" => "kategori_nama",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => ".produk",
                            "state" => ".distribute",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "transaksi_id" => ".0",
                            "gudang_id" => "gudang2ID",
                            "kategori_id" => "kategori_id",
                            "kategori_nama" => "kategori_nama",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "qty_debet" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                            "kategori_id" => "kategori_id",
                            "kategori_nama" => "kategori_nama",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "ProdukSerialNumberLocker",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "gudang_id" => "gudang2ID",
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
                    array(
                        "comName" => "ProdukSerialNumberLocker",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "gudang_id" => "gudang2ID",
                            "produk_serial_number" => "produk_serial",
                            "jumlah" => ".0",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "qty_debet" => "-qty",
//                            "produk_nilai" => "hpp",
//                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items8_sum",
                        "srcRawGateName" => "items8_sum",
                    ),
                    //</editor-fold>


                    //<editor-fold desc="Postproc-locker milik cabang">
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
                            "oleh_id" => ".0",
                            "transaksi_id" => ".0",
                            "gudang_id" => "gudangID",
                            "kategori_id" => "kategori_id",
                            "kategori_nama" => "kategori_nama",
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
                            "kategori_id" => "kategori_id",
                            "kategori_nama" => "kategori_nama",//untuk skiper jika produk adalah jasa
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".produk",
                            "jml" => "jml",
                            "produk_id" => "id",
                            "hpp" => "hpp",
                            "jml_nilai" => "sub_hpp",
                            "hpp_riil" => "hpp_riil",
                            "jml_nilai_riil" => "sub_hpp_riil",
                            "ppv_riil" => "ppv_riil",
                            "ppv_nilai_riil" => "sub_ppv_riil",
                            "nama" => "name",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "ppn_in" => "ppn_in",
                            "ppn_in_nilai" => "sub_ppn_in",
                            "suppliers_id" => "suppliers_id",
                            "suppliers_nama" => "suppliers_nama",
                            "hpp_nppv" => "hpp_nppv",
                            "jml_nilai_nppv" => "sub_hpp_nppv",
                            "produk_jenis" => "produk_jenis",
                            "kategori_id" => "kategori_id",
                            "kategori_nama" => "kategori_nama",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //</editor-fold>
                ),
                "sub_detail" => array(
                    //milik pusat
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "-jml",// items6 berhubungan dengan paket, jumlah produk ambil dari gerbang jml.
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => "masterID",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudang2ID",
                            "kategori_id" => "kategori_id",
                        ),
                        "srcGateName" => "items6",// items6 berhubungan dengan paket
                        "srcRawGateName" => "items6",
                    ),
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => ".produk",
                            "state" => ".sold",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudang2ID",
                            "kategori_id" => "kategori_id",
                        ),
                        "srcGateName" => "items6",
                        "srcRawGateName" => "items6",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "qty_debet" => "-jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                            "kategori_id" => "kategori_id",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items6",
                        "srcRawGateName" => "items6",
                    ),

                    //milik cabang
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".active",
//                            "jumlah" => "qty",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
//                            "transaksi_id" => "masterID",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                            "kategori_id" => "kategori_id",
                        ),
                        "srcGateName" => "items6",
                        "srcRawGateName" => "items6",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "qty_debet" => "jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "kategori_id" => "kategori_id",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items6",
                        "srcRawGateName" => "items6",
                    ),
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".produk",
                            "jml" => "jml",
                            "produk_id" => "id",
                            "hpp" => "hpp",
                            "jml_nilai" => "sub_hpp",
                            "hpp_riil" => "hpp_riil",
                            "jml_nilai_riil" => "sub_hpp_riil",
                            "ppv_riil" => "ppv_riil",
                            "ppv_nilai_riil" => "sub_ppv_riil",
                            "nama" => "name",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "ppn_in" => "ppn_in",
                            "ppn_in_nilai" => "sub_ppn_in",
                            "suppliers_id" => "suppliers_id",
                            "suppliers_nama" => "suppliers_nama",
                            "hpp_nppv" => "hpp_nppv",
                            "jml_nilai_nppv" => "sub_hpp_nppv",
                            "produk_jenis" => "produk_jenis",
                            "kategori_id" => "kategori_id",
                        ),
                        "srcGateName" => "items6",
                        "srcRawGateName" => "items6",
                    ),
                ),
            ),
        ),
    ),
    "585" => array(
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
                "pihakID" => "pihakID",
                "pihakName" => "pihakName",
                "cabangID" => "placeID",
                "cabangName" => "placeName",
                "gudangID" => "gudangID",
                "gudangName" => "gudangName",

                "cabang2ID" => "place2ID",
                "cabang2Name" => "place2Name",
                "place2ID" => "place2ID",
                "place2Name" => "place2Name",
                "gudang2ID" => "gudang2ID",
                "gudang2Name" => "gudang2Name",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "dtime" => "dtime",
                "id" => "id",
                "code" => "code",
                "label" => "label",
                "name" => "nama",
                "qty" => "jml",
                "satuan" => "satuan",
                "note" => "note",

                //"berat"         => "berat",
                //"lebar"         => "lebar",
                //"panjang"       => "panjang",
                //"tinggi"        => "tinggi",
                //"volume"        => "volume",
                "berat_gross" => "berat_gross",
                "lebar_gross" => "lebar_gross",
                "panjang_gross" => "panjang_gross",
                "tinggi_gross" => "tinggi_gross",
                "volume_gross" => "volume_gross",

//                "hpp" => "hpp",
//                "harga" => "harga",
                //                "sub_hpp" => "sub_hpp",
                //                "sub_harga" => "sub_harga",

                "pihakID" => "pihakID",
                "pihakName" => "pihakName",
                "cabangID" => "placeID",
                "cabangName" => "placeName",
                "olehID" => "olehID",
                "olehName" => "olehName",
            ),
            "detail_rsltItems" => array(//===sumber nilai berupa rincian
                "dtime" => "dtime",
                "id" => "id",
                "code" => "code",
                "label" => "label",
                "name" => "nama",
                "qty" => "jml",
                "satuan" => "satuan",
                "note" => "note",

                //"berat"         => "berat",
                //"lebar"         => "lebar",
                //"panjang"       => "panjang",
                //"tinggi"        => "tinggi",
                //"volume"        => "volume",
                "berat_gross" => "berat_gross",
                "lebar_gross" => "lebar_gross",
                "panjang_gross" => "panjang_gross",
                "tinggi_gross" => "tinggi_gross",
                "volume_gross" => "volume_gross",

                "hpp" => "hpp",
                "harga" => "harga",
                //                "sub_hpp" => "sub_hpp",
                //                "sub_harga" => "sub_harga",

                "pihakID" => "pihakID",
                "pihakName" => "pihakName",
                "cabangID" => "placeID",
                "cabangName" => "placeName",
                "olehID" => "olehID",
                "olehName" => "olehName",
            ),
        ),
        "valueBuilders" => array(),
        "valueBuilders_rsltItems" => array(),
        "preProcessor" => array(
            "585" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => "gudang2ID",
                            "kategori_id" => "kategori_id",
                        ),
                        "resultParams" => array(
                            "items" => array(
                                "harga" => "hpp",
                                "hpp" => "hpp",
                                "hpp_riil" => "hpp_riil",
                                "ppv_riil" => "ppv_riil",

                                "ppn_in" => "ppn_in",
                                "ppn_in_nilai" => "ppn_in_nilai",
                                "suppliers_id" => "suppliers_id",
                                "suppliers_nama" => "suppliers_nama",
                            ),
//                            "items9_sum" => array(
//                                "harga" => "hpp",
//                                "hpp" => "hpp",
//                                "hpp_riil" => "hpp_riil",
//                                "ppv_riil" => "ppv_riil",
//
//                                "ppn_in" => "ppn_in",
//                                "ppn_in_nilai" => "ppn_in_nilai",
//                                "suppliers_id" => "suppliers_id",
//                                "suppliers_nama" => "suppliers_nama",
//                            ),
                        ),
                        "srcGateName" => "items",// barang dari reguler
                        "srcRawGateName" => "items",// barang dari reguler
                    ),
                ),
//                "sub_detail" => array(
//                    array(
//                        "comName" => "FifoAverage",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "extern_id" => "id",
//                            "extern_nama" => "nama",
//                            "produk_qty" => "jml",
//                            "gudang_id" => "gudang2ID",
//                            "kategori_id" => "kategori_id",
//                            "kategori_nama" => "kategori_nama",//untuk skiper jika produk adalah jasa
//                            "harga" => "harga",
//                        ),
//                        "resultParams" => array(
//                            "items6" => array(
//                                //target=>builder preproc
//                                "hpp_paket" => "hpp",
//                                "hpp_riil_paket" => "hpp_riil",
////                                "ppv_riil" => "ppv_riil",
//                                "ppn_in_paket" => "ppn_in",
//                                "ppn_in_nilai_paket" => "ppn_in_nilai",
//                                "suppliers_id_paket" => "suppliers_id",
//                                "suppliers_nama_paket" => "suppliers_nama",
//                                "harga_jasa_paket" => "harga_jasa",
//                            ),
//                        ),
//                        "srcGateName" => "items",// barang dari paket
//                        "srcRawGateName" => "items",// barang dari paket
//                    ),
//                ),
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

                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "hpp",
                //                "transaksi_net" => "hpp",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "gudang2_id" => "gudang2ID",
                "gudang2_nama" => "gudang2Name",
                //---------------
                "salesman_id" => "pihakMain2ID",
                "salesman_nama" => "pihakMain2Name",
                "gudang_status_id" => "pihakMainID",
                "gudang_status_nama" => "pihakMainName",
                "gudang_status_jenis" => "pihakMainJenis",
                "reference_jenis" => "requestReferenceJenis",
                "reference_id" => "requestReferenceID",
                "reference_nomer" => "requestReferenceNomer",
                "reference_id_top" => "requestReferenceIDTop",
                "reference_nomer_top" => "requestReferenceNomerTop",
                "reference_jenis_top" => "requestReferenceJenisTop",
                "reference_jenis_master" => "requestReferenceJenisMaster",

                "reference_cabang_id" => "reference_cabang_id",
                "reference_cabang_nama" => "reference_cabang_nama",
                "reference_gudang_id" => "reference_gudang_id",
                "reference_gudang_nama" => "reference_gudang_nama",
                "reference_terima_barang" => "terima_barang",
            ),
            "detail" => array(
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
            "rsltItemsValues" => array(
                "hpp" => "hpp",
                //                "nett" => "hpp",
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
            "585" => array(
                "master" => array(
                    //<editor-fold desc="komponen milik pusat">
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030030" => "-harga",// persediaan produk
                            "1010060010" => "harga",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010030030" => "-harga",// persediaan produk
                            "1010060010" => "harga",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060010" => "harga",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "cabang2_id" => "pihakID",
                            "cabang2_nama" => "pihakName",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //</editor-fold>

                    //region jurnal kelaurain stok dari paket

                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030030" => "-hpp_paket",// persediaan produk
                            "1010060010" => "hpp_paket",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010030030" => "-hpp_paket",// persediaan produk
                            "1010060010" => "hpp_paket",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060010" => "hpp_paket",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "cabang2_id" => "pihakID",
                            "cabang2_nama" => "pihakName",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    //endregion

                    //<editor-fold desc="komponen milik cabang">

                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030030" => "harga",// persediaan produk
                            "2040010" => "harga",// hutang ke pusat

                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010030030" => "harga",// persediaan produk
                            "2040010" => "harga",// hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040010" => "harga",// hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "extern_id" => "place2ID",
                            "extern_nama" => "place2Name",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // </editor-fold>

                    //region stok masuk dari paket

                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030030" => "hpp_paket",// persediaan produk
                            "2040010" => "hpp_paket",// hutang ke pusat

                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010030030" => "hpp_paket",// persediaan produk
                            "2040010" => "hpp_paket",// hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040010" => "hpp_paket",// hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "extern_id" => "place2ID",
                            "extern_nama" => "place2Name",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //endregion

                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "-sub_harga",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-jml",
                            "produk_nilai" => "harga",
                            "gudang_id" => "gudang2ID",
                        ),
                        "srcGateName" => "items",// barang dari reguler
                        "srcRawGateName" => "items",// barang dari reguler
                    ),

                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "sub_harga",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "jml",
                            "produk_nilai" => "harga",
                            //                            "gudang_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",// barang dari reguler
                        "srcRawGateName" => "items",// barang dari reguler
                    ),


                ),
//                "sub_detail" => array(
//                    //rekening pembantu produk dari paket pusat
//                    array(
//                        "comName" => "RekeningPembantuProduk",
//                        "loop" => array(
//                            "1010030030" => "-sub_hpp_paket",// persediaan produk
//                        ),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "extern_id" => "id",
//                            "extern_nama" => "nama",
//                            "produk_qty" => "-jml",
//                            "produk_nilai" => "hpp_paket",
//                            "gudang_id" => "gudang2ID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "kategori_id" => "kategori_id",//ini untuk skip jika produk jasa
//                            "extern2_id" => "produk_paket_id",
//                            "extern2_nama" => "produk_paket_nama",
//                        ),
//                        "srcGateName" => "items10_sum",// barang dari paket
//                        "srcRawGateName" => "items10_sum",// barang dari paket
//                    ),
//                    //rekening pembantu produk masuk paket cabang
//                    array(
//                        "comName" => "RekeningPembantuProduk",
//                        "loop" => array(
//                            "1010030030" => "sub_hpp_paket",// persediaan produk
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "nama",
//                            "produk_qty" => "jml",
//                            "produk_nilai" => "hpp_paket",
//                            "gudang_id" => "gudangID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "kategori_id" => "kategori_id",//ini untuk skip jika produk jasa
//                            "extern2_id" => "produk_paket_id",
//                            "extern2_nama" => "produk_paket_nama",
//                        ),
//                        "srcGateName" => "items10_sum",// barang dari paket
//                        "srcRawGateName" => "items10_sum",// barang dari paket
//                    ),
//                ),
            ),
        ),
        "postProcessor" => array(
            "585r" => array(
                "master" => array(
                    //region ERP
                    array(
                        "comName" => "RekeningPembantuTransaksiDistribusi",// oke
                        "loop" => array(
                            "585r" => "harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTrMaster",
                            "master_jenis" => "jenisTrMaster",
//                            "produk_id" => "transaksi_id",
//                            "produk_nama" => "transaksi_no",
                            "produk_nilai" => "hpp",
                            "extern_id" => "pihakID",
                        ),
                        "reversable" => false,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "loadByModules" => true,
                    ),//nulis ke table pembelian_pembantu_transaksi_cache dan mutasi
                    //endregionERP
                ),
                "detail" => array(
                    //region ERP
                    array(
                        "comName" => "RekeningTransaksiDataDistribusi",
                        "loop" => array(
                            "585r" => "sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTrMaster",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => "qty",
                            "extern_id" => "transaksi_id",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                        "loadByModules" => true,
                    ),//nulis ke table _rek_penjualan_transkasi_data dan mutasi
                    array(
                        "comName" => "RekeningTransaksiDataDistribusiCache",
                        "loop" => array(
                            "585r" => "sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTrMaster",
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
                        "loadByModules" => true,
                    ),//nulis ke table casche produk sebagai index,rekening dan mutasi
                    array(
                        "comName" => "TransaksiDataDistribusi",
                        "loop" => array(
                            "585r" => "sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTrMaster",
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
                        "loadByModules" => true,
                    ),//untuk update pembelian_transaki_data
                    //endregion
                ),
            ),
            "585" => array(
                "master" => array(
                    //region ERP
                    array(
                        "comName" => "RekeningPembantuTransaksiDistribusi",
                        "loop" => array(
                            "585r" => "-harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTrMaster",
                            "master_jenis" => "jenisTrMaster",
                            "produk_id" => "currentID",//klau posisi mengurangi wajib bawa ini
//                            "produk_nama" => "transaksi_no",
                            "produk_nilai" => "harga",
                            "extern_id" => "pihakID",
                        ),
                        "reversable" => false,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "loadByModules" => true,
                    ),//nulis ke table pembelian_pembantu_transaksi_cache dan mutasi
                    array(
                        "comName" => "RekeningPembantuTransaksiDistribusi",
                        "loop" => array(
                            "585" => "harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTrMaster",
                            "master_jenis" => "jenisTrMaster",
//                            "produk_id" => "trasnaksi_id",//masuk tidak perlu karena akan diinject pakai transkai+id hasul followup
//                            "produk_nama" => "transaksi_no",
                            "produk_nilai" => "harga",
                            "extern_id" => "pihakID",
                        ),
                        "reversable" => false,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "loadByModules" => true,
                    ),//nulis ke table pembelian_pembantu_transaksi_cache dan mutasi
                    //endregionERP
                ),
                "detail" => array(
                    //region ERP
                    array(
                        "comName" => "RekeningTransaksiDataDistribusi",
                        "loop" => array(
                            "585r" => "-sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTrMaster",
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
                        "loadByModules" => true,
                    ),//nulis ke table _rek_penjualan_transkasi_data dan mutasi
                    array(
                        "comName" => "RekeningTransaksiDataDistribusi",
                        "loop" => array(
                            "585" => "sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTrMaster",
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
                        "loadByModules" => true,
                    ),//nulis ke table _rek_penjualan_transkasi_data dan mutasi

                    array(
                        "comName" => "RekeningTransaksiDataDistribusiCache",
                        "loop" => array(
                            "585r" => "-sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTrMaster",
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
                        "loadByModules" => true,
                    ),//nulis ke table casche produk sebagai index,rekening dan mutasi
                    array(
                        "comName" => "RekeningTransaksiDataDistribusiCache",
                        "loop" => array(
                            "585" => "sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTrMaster",
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
                        "loadByModules" => true,
                    ),//nulis ke table casche produk sebagai index,rekening dan mutasi
//
                    array(
                        "comName" => "TransaksiDataDistribusi",
                        "loop" => array(
                            "585r" => "-sub_harga",
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
                        "loadByModules" => true,
                    ),//untuk update pembelian_transaki_data
                    array(
                        "comName" => "TransaksiDataDistribusi",
                        "loop" => array(
                            "585" => "sub_harga",
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
                        "loadByModules" => true,
                    ),//untuk update pembelian_transaki_data
                    //endregion

                    //<editor-fold desc="Postproc-locker milik pusat">
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => "",
                            "transaksi_id" => "masterID",
                            "nomer" => "nomer",
                            "gudang_id" => "gudang2ID",
                            "kategori_id" => "kategori_id",
                            "kategori_nama" => "kategori_nama",
                        ),
                        "srcGateName" => "items",// barang dari reguler
                        "srcRawGateName" => "items",// barang dari reguler
                    ),
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => ".produk",
                            "state" => ".distribute",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "transaksi_id" => ".0",
                            "gudang_id" => "gudang2ID",
                            "kategori_id" => "kategori_id",
                            "kategori_nama" => "kategori_nama",
                        ),
                        "srcGateName" => "items",// barang dari reguler
                        "srcRawGateName" => "items",// barang dari reguler
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "qty_debet" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",// barang dari reguler
                        "srcRawGateName" => "items",// barang dari reguler
                    ),
//                    array(
//                        "comName" => "ProdukSerialNumberLocker",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "gudang_id" => "gudang2ID",
//                            "produk_serial_number" => "produk_serial",
//                            "jumlah" => ".0",
////                            "extern_id" => "id",
////                            "extern_nama" => "name",
////                            "qty_debet" => "-qty",
////                            "produk_nilai" => "hpp",
////                            "jenis" => "jenisTr",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "items3_sum",
//                        "srcRawGateName" => "items3_sum",
//                    ),
//                    array(
//                        "comName" => "ProdukSerialNumberLocker",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "gudang_id" => "gudang2ID",
//                            "produk_serial_number" => "produk_serial",
//                            "jumlah" => ".0",
////                            "extern_id" => "id",
////                            "extern_nama" => "name",
////                            "qty_debet" => "-qty",
////                            "produk_nilai" => "hpp",
////                            "jenis" => "jenisTr",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "items8_sum",
//                        "srcRawGateName" => "items8_sum",
//                    ),
                    //</editor-fold>

                    //<editor-fold desc="Postproc-locker milik cabang">
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
                            "oleh_id" => ".0",
                            "transaksi_id" => ".0",
                            "gudang_id" => "gudangID",
                            "kategori_id" => "kategori_id",
                            "kategori_nama" => "kategori_nama",
                        ),
                        "srcGateName" => "items",// barang dari reguler
                        "srcRawGateName" => "items",// barang dari reguler
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
                        ),
                        "reversable" => true,
                        "srcGateName" => "itemsm",// barang dari reguler
                        "srcRawGateName" => "items",// barang dari reguler
                    ),
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".produk",
                            "jml" => "jml",
                            "produk_id" => "id",
//                            "hpp" => "hpp",
//                            "jml_nilai" => "sub_hpp",
                            "hpp" => "harga",
                            "jml_nilai" => "sub_harga",
                            "hpp_riil" => "hpp_riil",
                            "jml_nilai_riil" => "sub_hpp_riil",
                            "ppv_riil" => "ppv_riil",
                            "ppv_nilai_riil" => "sub_ppv_riil",
                            "nama" => "name",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "ppn_in" => "ppn_in",
                            "ppn_in_nilai" => "sub_ppn_in",
                            "suppliers_id" => "suppliers_id",
                            "suppliers_nama" => "suppliers_nama",
                            "hpp_nppv" => "hpp_nppv",
                            "jml_nilai_nppv" => "sub_hpp_nppv",
                            "produk_jenis" => "produk_jenis",
                            "kategori_id" => "kategori_id",
                            "kategori_nama" => "kategori_nama",
                        ),
                        "srcGateName" => "items",// barang dari reguler
                        "srcRawGateName" => "items",// barang dari reguler
                    ),
                    //</editor-fold>

//
////                    // rekening pembantu produk serial
//                    array(
//                        "comName" => "RekeningPembantuProdukPerSerialIntransit",
//                        "loop" => array(
//                            "1010030030" => ".-1",//persediaan produk, sub_diskon_nilai_total
//                        ),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "gudang_id" => "gudang2ID",
//                            "extern_id" => ".0",
//                            "extern_nama" => "produk_serial",
//                            "extern2_id" => ".0",
//                            "extern2_nama" => "produk_sku_part_nama",
//                            "produk_id" => "id",
//                            "produk_nama" => "name",
//                            "produk_qty" => "-jml",
//                            "produk_nilai" => ".1",
//                            "transaksi_id" => "masterID",
//                        ),
//                        "srcGateName" => "items3_sum",
//                        "srcRawGateName" => "items3_sum",
//                    ),
////                    // rekening pembantu produk serial dari paket
//                    array(
//                        "comName" => "RekeningPembantuProdukPerSerialIntransit",
//                        "loop" => array(
//                            "1010030030" => ".-1",//persediaan produk, sub_diskon_nilai_total
//                        ),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "gudang_id" => "gudang2ID",
//                            "extern_id" => ".0",
//                            "extern_nama" => "produk_serial",
//                            "extern2_id" => ".0",
//                            "extern2_nama" => "produk_sku_part_nama",
//                            "produk_id" => "id",
//                            "produk_nama" => "name",
//                            "produk_qty" => "-jml",
//                            "produk_nilai" => ".1",
//                            "transaksi_id" => "masterID",
//                        ),
//                        "srcGateName" => "items8_sum",
//                        "srcRawGateName" => "items8_sum",
//                    ),
//                    // rekening pembantu produk serial dari reguler
//                    array(
//                        "comName" => "RekeningPembantuProdukPerSerial",
//                        "loop" => array(
//                            "1010030030" => ".1",//persediaan produk, sub_diskon_nilai_total
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "extern_id" => ".0",
//                            "extern_nama" => "produk_serial",
//                            "extern2_id" => ".0",
//                            "extern2_nama" => "produk_sku_part_nama",
//                            "produk_id" => "id",
//                            "produk_nama" => "name",
//                            "produk_qty" => "jml",
//                            "produk_nilai" => ".1",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                            "supplierID" => "pihakID",
//                        ),
//                        "srcGateName" => "items3_sum",
//                        "srcRawGateName" => "items3_sum",
//                    ),
//                    // rekening pembantu produk serial dari paket
//                    array(
//                        "comName" => "RekeningPembantuProdukPerSerial",
//                        "loop" => array(
//                            "1010030030" => ".1",//persediaan produk, sub_diskon_nilai_total
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "extern_id" => ".0",
//                            "extern_nama" => "produk_serial",
//                            "extern2_id" => ".0",
//                            "extern2_nama" => "produk_sku_part_nama",
//                            "produk_id" => "id",
//                            "produk_nama" => "name",
//                            "produk_qty" => "jml",
//                            "produk_nilai" => ".1",
//                        ),
//                        "srcGateName" => "items8_sum",
//                        "srcRawGateName" => "items8_sum",
//                    ),

                ),
//                "sub_detail" => array(
//                    //milik pusat
//                    array(
//                        "comName" => "LockerStock",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "jenis" => ".produk",
//                            "state" => ".hold",
//                            "jumlah" => "-jml",
//                            "produk_id" => "id",
//                            "nama" => "name",
//                            "satuan" => "satuan",
//                            "transaksi_id" => "masterID",
//                            "oleh_id" => ".0",
//                            "gudang_id" => "gudang2ID",
//                            "kategori_id" => "kategori_id",
//                        ),
//                        "srcGateName" => "items10_sum",// barang dari paket
//                        "srcRawGateName" => "items10_sum",// barang dari paket
//                    ),
//                    array(
//                        "comName" => "LockerStock",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "jenis" => ".produk",
//                            "state" => ".sold",
//                            "jumlah" => "jml",
//                            "produk_id" => "id",
//                            "nama" => "name",
//                            "satuan" => "satuan",
//                            "transaksi_id" => ".0",
//                            "oleh_id" => ".0",
//                            "gudang_id" => "gudang2ID",
//                            "kategori_id" => "kategori_id",
//                        ),
//                        "srcGateName" => "items10_sum",// barang dari paket
//                        "srcRawGateName" => "items10_sum",// barang dari paket
//                    ),
//                    // locker stok mutasi
//                    array(
//                        "comName" => "LockerStockMutasi",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "qty_debet" => "-jml",
//                            "produk_nilai" => "hpp",
//                            "gudang_id" => "gudang2ID",
//                            "jenis" => "jenisTr",
//                            "kategori_id" => "kategori_id",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "items10_sum",// barang dari paket
//                        "srcRawGateName" => "items10_sum",// barang dari paket
//                    ),
//
//                    //milik cabang
//                    array(
//                        "comName" => "LockerStock",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => ".produk",
//                            "state" => ".active",
////                            "jumlah" => "qty",
//                            "jumlah" => "jml",
//                            "produk_id" => "id",
//                            "nama" => "name",
//                            "satuan" => "satuan",
////                            "transaksi_id" => "masterID",
//                            "transaksi_id" => ".0",
//                            "oleh_id" => ".0",
//                            "gudang_id" => "gudangID",
//                            "kategori_id" => "kategori_id",
//                        ),
//                        "srcGateName" => "items10_sum",// barang dari paket
//                        "srcRawGateName" => "items10_sum",// barang dari paket
//                    ),
//                    // locker stok mutasi
//                    array(
//                        "comName" => "LockerStockMutasi",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "qty_debet" => "jml",
//                            "produk_nilai" => "hpp",
//                            "gudang_id" => "gudangID",
//                            "jenis" => "jenisTr",
//                            "kategori_id" => "kategori_id",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "items10_sum",// barang dari paket
//                        "srcRawGateName" => "items10_sum",// barang dari paket
//                    ),
//                    array(
//                        "comName" => "FifoAverage",
//                        "loop" => array(),
//                        "static" => array(
//                            "jenis" => ".produk",
//                            "jml" => "jml",
//                            "produk_id" => "id",
//                            "hpp" => "hpp_paket",
//                            "jml_nilai" => "sub_hpp_paket",
//                            "hpp_riil" => "hpp_riil_paket",
//                            "jml_nilai_riil" => "sub_hpp_riil_paket",
//                            "ppv_riil" => "ppv_riil",
//                            "ppv_nilai_riil" => "sub_ppv_riil",
//                            "nama" => "name",
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "ppn_in" => "ppn_in",
//                            "ppn_in_nilai" => "sub_ppn_in",
//                            "suppliers_id" => "suppliers_id",
//                            "suppliers_nama" => "suppliers_nama",
//                            "hpp_nppv" => "hpp_nppv",
//                            "jml_nilai_nppv" => "sub_hpp_nppv",
//                            "produk_jenis" => "produk_jenis",
//                            "kategori_id" => "kategori_id",
//                        ),
//                        "srcGateName" => "items10_sum",// barang dari paket
//                        "srcRawGateName" => "items10_sum",// barang dari paket
//                    ),
//                ),
            ),
        ),
    ),
    //  config return distribusi dari cabang ke pusat
    "983" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabang2ID",
            "stepCode|placeID|cabang2ID",
        ),
        "formatNota" => "stepCode|placeID|cabang2ID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                //                "pihakID" => "pihakID",
                //                "pihakName" => "pihakName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                "cabang2ID" => "pihakID",
                "cabang2Name" => "pihakName",
                "place2ID" => "pihakID",
                "place2Name" => "pihakName",
                "referenceID" => "referenceID",
                "referenceJenis" => "referenceJenis",
                "referenceNomer" => "referenceNomer",
                //                "gudangID" => "gudangID",
                //                "gudangName" => "gudangName",
                "gudangID" => "gudang",
                "gudangName" => "gudang__label",
                "gudang2ID" => "gudang2",
                "gudang2Name" => "gudang2__label",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                "name" => "nama",
                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",

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

                //                "pihakID" => "pihakID",
                //                "pihakName" => "pihakName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
            ),
            "rsltItems" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                "name" => "nama",
                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",

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

                //                "pihakID" => "pihakID",
                //                "pihakName" => "pihakName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
            ),
        ),
        "valueBuilders" => array(
            //            "hpp"   => "sub_hpp",
            //            "harga" => "sub_harga",
        ),
        "valueBuilders_rsltItems" => array(
            //            "hpp"   => "sub_hpp",
            //            "harga" => "sub_harga",
        ),
        "preProcessor" => array(
            "983sc" => array(
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

                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "hpp",
                //                "transaksi_net" => "hpp",
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
            "rsltItemsValues" => array(
                "hpp" => "hpp",
                //                "nett" => "hpp",
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
        "components" => array(
            "983r" => array(
                "master" => array(),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            "983r" => array(
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
            "983sc" => array(
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
                            "gudang_id" => "gudangID",
                            "cabang_nama" => "placeName",
                            "gudang_nama" => "gudangName",
                            "extern_id" => ".0",
                            "extern_nama" => "produk_serial",
                            "extern2_id" => ".0",
                            "extern2_nama" => "produk_sku_part_nama",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "produk_qty" => "-jml",
                            "produk_nilai" => ".1",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "supplierID" => "pihakID",
                        ),
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),
                    // serial intransit dibawah sini....
                    array(
                        "comName" => "RekeningPembantuProdukPerSerialIntransit",
                        "loop" => array(
                            "1010030030" => ".1",//persediaan produk, sub_diskon_nilai_total
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "cabang_nama" => "placeName",
                            "gudang_nama" => "gudangName",
                            "extern_id" => ".0",
                            "extern_nama" => "produk_serial",
                            "extern2_id" => ".0",
                            "extern2_nama" => "produk_sku_part_nama",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "produk_qty" => "jml",
                            "produk_nilai" => ".1",
                            "transaksi_id" => "masterID",
                        ),
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
            "stepCode|cabang2ID",
            "stepCode|placeID|cabang2ID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|cabang2ID",
            "stepCode|masterID|placeID|cabang2ID",
        ),
        "formatNotaEdit" => "stepCode|placeID|cabang2ID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabang2ID",
            "stepCode|placeID|cabang2ID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|cabang2ID",
            "stepCode|masterID|placeID|cabang2ID",
        ),
        "formatNotaReject" => "stepCode|placeID|cabang2ID",
    ),
    //  config penerimaan return distribusi
    "985" => array(
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
                //                "pihakID" => "pihakID",
                //                "pihakName" => "pihakName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //
                //                "cabang2ID" => "cabang2ID",
                //                "cabang2Name" => "cabang2Name",
                //                "place2ID" => "place2ID",
                //                "place2Name" => "place2Name",
                //
                //                "gudang2ID" => "gudang2ID",
                //                "gudang2Name" => "gudang2Name",
                //                "gudangID" => "gudangID",
                //                "gudangName" => "gudangName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                "name" => "nama",
                "qty" => "jml",
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
            "rsltItems" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                "name" => "nama",
                "qty" => "jml",
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
        "preProcessor" => array(
            "985" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => "gudang2ID",
                        ),
                        "resultParams" => array(
                            "items" => array(
                                "harga" => "hpp",
                                "hpp" => "hpp",
                                "hpp_riil" => "hpp_riil",
                                "ppv_riil" => "ppv_riil",

                                "ppn_in" => "ppn_in",
                                "ppn_in_nilai" => "ppn_in_nilai",
                                "suppliers_id" => "suppliers_id",
                                "suppliers_nama" => "suppliers_nama",
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

                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "hpp",
                //                "transaksi_net" => "hpp",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

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
        "components" => array(
            "985" => array(
                "master" => array(
                    //<editor-fold desc="komponen milik pusat">
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030030" => "hpp",// persediaan produk
                            "1010060010" => "-hpp",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010030030" => "hpp",// persediaan produk
                            "1010060010" => "-hpp",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060010" => "-hpp",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "extern_id" => "place2ID",
                            "extern_nama" => "place2Name",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //</editor-fold>

                    //<editor-fold desc="komponen milik cabang">
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030030" => "-hpp",// persediaan produk
                            "2040010" => "-hpp",// hutang ke pusat

                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010030030" => "-hpp",// persediaan produk
                            "2040010" => "-hpp",// hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040010" => "-hpp",// hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "cabang2_id" => "pihakID",
                            "cabang2_nama" => "pihakName",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //</editor-fold>

                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "sub_hpp",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // rekening pembantu produk serial
                    array(
                        "comName" => "RekeningPembantuProdukPerSerial",
                        "loop" => array(
                            "1010030030" => ".1",//persediaan produk, sub_diskon_nilai_total
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
                            "produk_qty" => "jml",
                            "produk_nilai" => ".1",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "supplierID" => "pihakID",
                        ),
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),

                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "-sub_hpp",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "hpp",
                            //                            "gudang_id" => ".0",
                            "gudang_id" => "gudang2ID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // rekening pembantu produk serial
                    array(
                        "comName" => "RekeningPembantuProdukPerSerialIntransit",
                        "loop" => array(
                            "1010030030" => ".-1",//persediaan produk, sub_diskon_nilai_total
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "gudang_id" => "gudang2ID",
                            "extern_id" => ".0",
                            "extern_nama" => "produk_serial",
                            "extern2_id" => ".0",
                            "extern2_nama" => "produk_sku_part_nama",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "produk_qty" => "-jml",
                            "produk_nilai" => ".1",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "supplierID" => "pihakID",
                            "transaksi_id" => "masterID",
                        ),
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "985" => array(
                "master" => array(),
                "detail" => array(

                    //<editor-fold desc="Postproc-locker milik cabang">
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterID",
                            "nomer" => "nomer",
                            //                            "gudang_id" => ".0",
                            "gudang_id" => "gudang2ID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => ".produk",
                            "state" => ".returned",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "transaksi_id" => ".0",
                            //                            "gudang_id" => ".0",
                            "gudang_id" => "gudang2ID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "qty_debet" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudang2ID",
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
                            "cabang_id" => "place2ID",
                            "produk_serial_number" => "produk_serial",
                            "jumlah" => ".0",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "qty_debet" => "-qty",
//                            "produk_nilai" => "hpp",
//                            "gudang_id" => "gudangID",
//                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),
                    //</editor-fold>

                    //<editor-fold desc="Postproc-locker milik pusat">
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
                            "oleh_id" => ".0",
                            "transaksi_id" => ".0",
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
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".produk",
                            "jml" => "qty",
                            "produk_id" => "id",
                            "hpp" => "hpp",
                            "jml_nilai" => "sub_hpp",
                            "hpp_riil" => "hpp_riil",
                            "jml_nilai_riil" => "sub_hpp_riil",
                            "ppv_riil" => "ppv_riil",
                            "ppv_nilai_riil" => "sub_ppv_riil",
                            "nama" => "name",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "ppn_in" => "ppn_in",
                            "ppn_in_nilai" => "sub_ppn_in",
                            "suppliers_id" => "suppliers_id",
                            "suppliers_nama" => "suppliers_nama",
                            "hpp_nppv" => "hpp_nppv",
                            "jml_nilai_nppv" => "sub_hpp_nppv",
                            "produk_jenis" => "produk_jenis",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    //</editor-fold>
                ),
            ),
        ),
    ),
    //  config return distribusi dari cabang ke pusat produk
    "1983" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabang2ID",
            "stepCode|placeID|cabang2ID",
        ),
        "formatNota" => "stepCode|placeID|cabang2ID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                //                "pihakID" => "pihakID",
                //                "pihakName" => "pihakName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                "cabang2ID" => "pihakID",
                "cabang2Name" => "pihakName",
                "place2ID" => "pihakID",
                "place2Name" => "pihakName",
                "referenceID" => "referenceID",
                "referenceJenis" => "referenceJenis",
                "referenceNomer" => "referenceNomer",
                //                "gudangID" => "gudangID",
                //                "gudangName" => "gudangName",
                "gudangID" => "gudang",
                "gudangName" => "gudang__label",
                "gudang2ID" => "gudang2",
                "gudang2Name" => "gudang2__label",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                "name" => "nama",
                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",

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

                //                "pihakID" => "pihakID",
                //                "pihakName" => "pihakName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
            ),
            "rsltItems" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                "name" => "nama",
                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",

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

                //                "pihakID" => "pihakID",
                //                "pihakName" => "pihakName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
            ),
        ),
        "valueBuilders" => array(
            //            "hpp"   => "sub_hpp",
            //            "harga" => "sub_harga",
        ),
        "valueBuilders_rsltItems" => array(
            //            "hpp"   => "sub_hpp",
            //            "harga" => "sub_harga",
        ),
        "preProcessor" => array(
            "1983sc" => array(
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

                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "hpp",
                //                "transaksi_net" => "hpp",
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
            "rsltItemsValues" => array(
                "hpp" => "hpp",
                //                "nett" => "hpp",
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
        "components" => array(
            "1983r" => array(
                "master" => array(),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            "1983r" => array(
                "master" => array(),
                "detail" => array(

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
            "1983sc" => array(
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
                            "gudang_id" => "gudangID",
                            "cabang_nama" => "placeName",
                            "gudang_nama" => "gudangName",
                            "extern_id" => ".0",
                            "extern_nama" => "produk_serial",
                            "extern2_id" => ".0",
                            "extern2_nama" => "produk_sku_part_nama",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "produk_qty" => "-jml",
                            "produk_nilai" => ".1",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "supplierID" => "pihakID",
                        ),
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),
                    // serial intransit dibawah sini
                    array(
                        "comName" => "RekeningPembantuProdukPerSerialIntransit",
                        "loop" => array(
                            "1010030030" => ".1",//persediaan produk, sub_diskon_nilai_total
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "cabang_nama" => "placeName",
                            "gudang_nama" => "gudangName",
                            "extern_id" => ".0",
                            "extern_nama" => "produk_serial",
                            "extern2_id" => ".0",
                            "extern2_nama" => "produk_sku_part_nama",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "produk_qty" => "jml",
                            "produk_nilai" => ".1",
                            "transaksi_id" => "masterID",
                        ),
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
            "stepCode|cabang2ID",
            "stepCode|placeID|cabang2ID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|cabang2ID",
            "stepCode|masterID|placeID|cabang2ID",
        ),
        "formatNotaEdit" => "stepCode|placeID|cabang2ID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabang2ID",
            "stepCode|placeID|cabang2ID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|cabang2ID",
            "stepCode|masterID|placeID|cabang2ID",
        ),
        "formatNotaReject" => "stepCode|placeID|cabang2ID",
    ),
    //  config penerimaan return distribusi
    "1985" => array(
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
                //                "pihakID" => "pihakID",
                //                "pihakName" => "pihakName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //
                //                "cabang2ID" => "cabang2ID",
                //                "cabang2Name" => "cabang2Name",
                //                "place2ID" => "place2ID",
                //                "place2Name" => "place2Name",
                //
                //                "gudang2ID" => "gudang2ID",
                //                "gudang2Name" => "gudang2Name",
                //                "gudangID" => "gudangID",
                //                "gudangName" => "gudangName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                "name" => "nama",
                "qty" => "jml",
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
            "rsltItems" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                "name" => "nama",
                "qty" => "jml",
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
        "preProcessor" => array(
            "1985" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => "gudang2ID",
                        ),
                        "resultParams" => array(
                            "items" => array(
                                "harga" => "hpp",
                                "hpp" => "hpp",
                                "hpp_riil" => "hpp_riil",
                                "ppv_riil" => "ppv_riil",

                                "ppn_in" => "ppn_in",
                                "ppn_in_nilai" => "ppn_in_nilai",
                                "suppliers_id" => "suppliers_id",
                                "suppliers_nama" => "suppliers_nama",
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

                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "hpp",
                //                "transaksi_net" => "hpp",
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
        "components" => array(
            "1985" => array(
                "master" => array(
                    //<editor-fold desc="komponen milik pusat">
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030030" => "hpp",// persediaan produk
                            "1010060010" => "-hpp",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010030030" => "hpp",// persediaan produk
                            "1010060010" => "-hpp",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060010" => "-hpp",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "extern_id" => "place2ID",
                            "extern_nama" => "place2Name",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //</editor-fold>

                    //<editor-fold desc="komponen milik cabang">
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030030" => "-hpp",// persediaan produk
                            "2040010" => "-hpp",// hutang ke pusat

                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010030030" => "-hpp",// persediaan produk
                            "2040010" => "-hpp",// hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040010" => "-hpp",// hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "cabang2_id" => "pihakID",
                            "cabang2_nama" => "pihakName",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //</editor-fold>

                ),
                "detail" => array(
                    //<editor-fold desc="subkomponen milik pusat">
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "sub_hpp",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // rekening pembantu produk serial
                    array(
                        "comName" => "RekeningPembantuProdukPerSerial",
                        "loop" => array(
                            "1010030030" => ".1",//persediaan produk, sub_diskon_nilai_total
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
                            "produk_qty" => "jml",
                            "produk_nilai" => ".1",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "supplierID" => "pihakID",
                        ),
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),

                    //</editor-fold>

                    //<editor-fold desc="subkomponen milik cabang">
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "-sub_hpp",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "hpp",
                            //                            "gudang_id" => ".0",
                            "gudang_id" => "gudang2ID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // rekening pembantu produk serial
//                    array(
//                        "comName" => "RekeningPembantuProdukPerSerial",
//                        "loop" => array(
//                            "1010030030" => ".-1",//persediaan produk, sub_diskon_nilai_total
//                        ),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "gudang_id" => "gudang2ID",
//                            "extern_id" => ".0",
//                            "extern_nama" => "produk_serial",
//                            "extern2_id" => ".0",
//                            "extern2_nama" => "produk_sku_part_nama",
//                            "produk_id" => "id",
//                            "produk_nama" => "name",
//                            "produk_qty" => "-jml",
//                            "produk_nilai" => ".1",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                            "supplierID" => "pihakID",
//                        ),
//                        "srcGateName" => "items3_sum",
//                        "srcRawGateName" => "items3_sum",
//                    ),

                    // serial intransit dibawah sini
                    array(
                        "comName" => "RekeningPembantuProdukPerSerialIntransit",
                        "loop" => array(
                            "1010030030" => ".-1",//persediaan produk, sub_diskon_nilai_total
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "gudang_id" => "gudang2ID",
                            "extern_id" => ".0",
                            "extern_nama" => "produk_serial",
                            "extern2_id" => ".0",
                            "extern2_nama" => "produk_sku_part_nama",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "produk_qty" => "-jml",
                            "produk_nilai" => ".1",
                            "transaksi_id" => "masterID",
                        ),
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),

                    //</editor-fold>
                ),
            ),
        ),
        "postProcessor" => array(
            "1985" => array(
                "master" => array(),
                "detail" => array(

                    //<editor-fold desc="Postproc-locker milik cabang">
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterID",
                            "nomer" => "nomer",
                            //                            "gudang_id" => ".0",
                            "gudang_id" => "gudang2ID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => ".produk",
                            "state" => ".returned",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "transaksi_id" => ".0",
                            "gudang_id" => "gudang2ID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "qty_debet" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudang2ID",
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
                            "cabang_id" => "place2ID",
                            "produk_serial_number" => "produk_serial",
                            "jumlah" => ".0",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "qty_debet" => "-qty",
//                            "produk_nilai" => "hpp",
//                            "gudang_id" => "gudangID",
//                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),
                    //</editor-fold>

                    //<editor-fold desc="Postproc-locker milik pusat">
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".produk",
                            "jml" => "qty",
                            "produk_id" => "id",
                            "hpp" => "hpp",
                            "jml_nilai" => "sub_hpp",
                            "hpp_riil" => "hpp_riil",
                            "jml_nilai_riil" => "sub_hpp_riil",
                            "ppv_riil" => "ppv_riil",
                            "ppv_nilai_riil" => "sub_ppv_riil",
                            "nama" => "name",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "ppn_in" => "ppn_in",
                            "ppn_in_nilai" => "sub_ppn_in",
                            "suppliers_id" => "suppliers_id",
                            "suppliers_nama" => "suppliers_nama",
                            "hpp_nppv" => "hpp_nppv",
                            "jml_nilai_nppv" => "sub_hpp_nppv",
                            "produk_jenis" => "produk_jenis",
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
                            "state" => ".active",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "transaksi_id" => ".0",
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
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    //</editor-fold>
                ),
            ),
        ),
    ),
    //  config assembling / produksi
    "773" => array(
        "counters" => array(
            //            "stepCode",
            "stepCode|olehID",
            "stepCode|placeID",
            "stepCode|placeID|olehID",
            "stepCode|placeID|gudangID",
            "stepCode|placeID|gudangID|olehID",
        ),
        "formatNota" => "stepCode|placeID|gudangID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "pihakID" => "placeID",
                "pihakName" => "placeName",
                "cabangID" => "placeID",
                "cabangName" => "placeName",
                "place2ID" => "placeID",
                "place2Name" => "placeName",
                "gudangID" => "gudangID",
                "gudangName" => "gudangName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //                "id2"    => "id2",
                //                "code2"  => "code2",
                //                "label2" => "label2",
                //                "name2"  => "nama2",
                //                "berat_gross" => "berat_gross",
                //                "lebar_gross" => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross" => "tinggi_gross",
                //                "volume_gross" => "volume_gross",
                //
                //                "hpp" => "hpp",
                //                "harga" => "harga",
                //
                //                "pihakID" => "placeID",
                //                "pihakName" => "placeName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
                //
                //                "masterID" => "masterID",
                "total_cost" => "costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
            ),
            "detail2" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                "name" => "nama",
                "qty" => "jml",
            ),
            "detail2_sum" => array(//===sumber nilai berupa rincian
                "name" => "nama",
                "qty" => "jml",
            ),
            "rsltItems" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                "name" => "nama",
                "qty" => "jml",

            ),
            "rsltItems2" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                "name" => "nama",
                "qty" => "jml",

            ),
        ),
        "valueBuilders" => array(),
        "valueBuilders2" => array(),
        "valueBuilders2_sum" => array(// "sisa"   => "stok-qty",
        ),
        "valueBuilders_rsltItems" => array(),
        "valueBuilders_rsltItems2" => array(),
        "preProcessor" => array(
            "773sc" => array(
                "master" => array(
                    //untuk paket terbit items8_sum
                    array(
                        "comName" => "ProdukSerialNumberExtractorPaket",
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
                "sub_detail" => array(
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "jml",
                            "gudang_id" => "gudangID",
                            "kategori_id" => "kategori_id",
                            "kategori_nama" => "kategori_nama",//untuk skiper jika produk adalah jasa
                            "harga" => "harga",
                            "transaksi_id" => "masterID",
                        ),
                        "srcGateName" => "items10_sum",// barang dari paket
                        "srcRawGateName" => "items10_sum",// barang dari paket
                    ),
                ),
            ),
            "773" => array(
                "master" => array(
//                    array(
//                        "comName" => "KompositValues",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "jenisTr" => "jenisTr",
//                            "jenisTrMaster" => "jenisTrMaster",
//                            "jenisTrTop" => "jenisTrTop",
//                            "jenisTrName" => "jenisTrName",
//                            "olehID" => "olehID",
//                            "olehName" => "olehName",
//                            "dtime" => "dtime",
//                            "fulldate" => "fulldate",
//                            "stepNumber" => "stepNumber",
//                            "srcGateName" => ".jurnalItems",
//                            "srcRawGateName" => ".jurnalItems",
//                            "comName" => ".JurnalValuesItem",
//                            "rekening" => ".1010030030",
//                            "mdlName" => ".MdlProduk2",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
                    array(
                        "comName" => "SyncPaket",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "produk_qty" => "jml",
                            "gudang_id" => "gudangID",
//                            "kategori_id" => "kategori_id",
//                            "kategori_nama" => "kategori_nama",//untuk skiper jika produk adalah jasa
//                            "harga" => "harga",
                            "jenisTr" => "jenisTr",
                            "jenisTrMaster" => "jenisTrMaster",
                            "jenisTrTop" => "jenisTrTop",
                            "jenisTrName" => "jenisTrName",
                            "source" => ".items6",
                            "target" => ".items",
                        ),
                        "srcGateName" => "main",// barang dari paket
                        "srcRawGateName" => "main",// barang dari paket
                    ),
                ),
                "detail" => array(

//                    array(
//                        "comName" => "FifoProdukKomposit",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "produk_qty" => "qty",
//                            "gudang_id" => "gudangID",
//                            "jenisTr" => "jenisTrMaster",
//                        ),
//                        "resultParams" => array(
//                            "rsltItems" => array( // berisi bahan/supplies yang dipakai
//                                "id" => "bahan_id",
//                                "nama" => "nama",
//                                "harga" => "hpp",
//                                "hpp" => "hpp",
//                                "jml" => "diambil",
//                                "qty" => "diambil",
//                                "subtotal" => "subHPP",
//                            ),
//                            "rsltItems2" => array( // berisi produk hasil assembling
//                                "id" => "produk_id",
//                                "nama" => "produk_nama",
//                                "name" => "produk_nama",
//                                "harga" => "hpp",
//                                "hpp" => "hpp",
//                                "jml" => "jml",
//                                "qty" => "jml",
//                                "subtotal" => "subHPP",
//                            ),
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
                ),
                "sub_detail" => array(
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "jml",
                            "gudang_id" => "gudangID",
                            "kategori_id" => "kategori_id",
                            "kategori_nama" => "kategori_nama",//untuk skiper jika produk adalah jasa
                            "harga" => "harga",
                        ),
                        "resultParams" => array(
                            "items6" => array(
                                //target=>builder preproc
                                "hpp_paket" => "hpp",
                                "hpp_riil_paket" => "hpp_riil",
//                                "ppv_riil" => "ppv_riil",
                                "ppn_in_paket" => "ppn_in",
                                "ppn_in_nilai_paket" => "ppn_in_nilai",
                                "suppliers_id_paket" => "suppliers_id",
                                "suppliers_nama_paket" => "suppliers_nama",
                                "harga_jasa_paket" => "harga_jasa",
                            ),
                        ),
                        "srcGateName" => "items10_sum",// barang dari paket
                        "srcRawGateName" => "items10_sum",// barang dari paket
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

                "cabang2_id" => "placeID",
                "cabang2_nama" => "placeName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "hpp",
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
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "detail2_sum" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "nama",
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
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
                "satuan" => "satuan",
            ),
            "rsltItems2" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
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
            "detail2_sum" => array(
                "trash" => 0,
                "produk_jenis" => "bahan",
            ),
            "rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "bahan",
            ),
            "rsltItems2" => array(
                "trash" => 0,
                "produk_jenis" => "produk",
            ),
        ),
        "components" => array(
            "773" => array(
                "master" => array(),
                "detail" => array(
                    // hasil komposit (BERTAMBAH)
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "sub_hpp_paket_original",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "jml",
                            "produk_nilai" => "hpp_paket_original",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
                "sub_detail" => array(
                    // bahan komposit (BERKURANG)
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "-sub_hpp_paket",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "-jml",
                            "produk_nilai" => "hpp_paket",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items10_sum",// barang dari paket
                        "srcRawGateName" => "items10_sum",// barang dari paket
                    ),

                ),
            ),
        ),
        "postProcessor" => array(
            "773sc" => array(
                "master" => array(),
                "detail" => array(
                    // serial bahan komposit items8_sum
                    array(
                        "comName" => "RekeningPembantuProdukPerSerial",
                        "loop" => array(
                            "1010030030" => ".-1",//persediaan produk, sub_diskon_nilai_total
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "cabang_nama" => "placeName",
                            "gudang_nama" => "gudangName",
                            "extern_id" => ".0",
                            "extern_nama" => "produk_serial",
                            "extern2_id" => ".0",
                            "extern2_nama" => "produk_sku_part_nama",
                            "extern3_id" => "produk_paket_id",
                            "extern3_nama" => "produk_paket_nama",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "produk_qty" => "-jml",
                            "produk_nilai" => ".1",
                            "kategori_id" => "kategori_id",//ini untuk skip produk jasa

//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "supplierID" => "pihakID",
                        ),
                        "srcGateName" => "items8_sum",
                        "srcRawGateName" => "items8_sum",
                    ),
                    // serial hasil komposit
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
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),
                ),
            ),
            "773" => array(
                "master" => array(),
                "detail" => array(
                    // LOCKER produk source

//                    array(
//                        "comName" => "LockerStock",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => ".produk",
//                            "state" => ".active",
//                            "jumlah" => "-jml",
//                            "produk_id" => "id",
//                            "nama" => "name",
//                            "satuan" => "satuan",
//                            "oleh_id" => ".0",
//                            "oleh_nama" => ".0",
//                            "transaksi_id" => ".0",
//                            "nomer" => ".0",
//                            "gudang_id" => "gudangID",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "items2_sum",
//                        "srcRawGateName" => "items2_sum",
//                    ),

//                    array(
//                        "comName" => "LockerStockMutasi",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "qty_debet" => "-jml",
//                            "produk_nilai" => "hpp",
//                            "gudang_id" => "gudangID",
//                            "jenis" => "jenisTr",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "items2_sum",
//                        "srcRawGateName" => "items2_sum",
//                    ),

                    //region fifo masuk produk target
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".produk",
                            "jml" => "jml",
                            "produk_id" => "id",
                            "hpp" => "hpp_paket_original",
                            "jml_nilai" => "sub_hpp_paket_original",
                            "nama" => "nama",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //endregion

                    //region produk target
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".active",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "nama",
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
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //endregion

                ),
                "sub_detail" => array(
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "-jml",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => "masterID",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                            "kategori_id" => "kategori_id",
                        ),
                        "srcGateName" => "items10_sum",// barang dari paket
                        "srcRawGateName" => "items10_sum",// barang dari paket
                    ),
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".sold",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                            "kategori_id" => "kategori_id",
                        ),
                        "srcGateName" => "items10_sum",// barang dari paket
                        "srcRawGateName" => "items10_sum",// barang dari paket
                    ),
                ),
            ),

        ),
        //-----
        "countersEdit" => array(
            "stepCode|olehID",
            "stepCode|placeID",
            "stepCode|placeID|olehID",
            "stepCode|placeID|gudangID",
            "stepCode|placeID|gudangID|olehID",

            "stepCode|masterID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID|gudangID",
            "stepCode|masterID|placeID|gudangID|olehID",
        ),
        "formatNotaEdit" => "stepCode|placeID|gudangID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabang2ID",
            "stepCode|placeID|cabang2ID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|cabang2ID",
            "stepCode|masterID|placeID|cabang2ID",
        ),
        "formatNotaReject" => "stepCode|placeID|gudangID",
    ),


);

