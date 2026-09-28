<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/22/2018
 * Time: 8:38 PM
 */
$config["coTransaksiValues"] = array(

    //  config assembling / produksi
    "776" => array(
        "counters" => array(
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

//                "total_cost" => "costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
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
                "total_cost" => "costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
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

            "rsltItems3" => array(
                //===sumber nilai berupa rincian
                "name" => "nama",
                "qty" => "jml",
                "harga_biaya" => "harga",
            ),
        ),
        "valueBuilders" => array(),
        "valueBuilders2" => array(),
        "valueBuilders2_sum" => array(),
        "valueBuilders_rsltItems" => array(),
        "valueBuilders_rsltItems2" => array(),
        "preProcessor" => array(
            "776a" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoAverageSuppliesAssembly",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => "gudangID",
                            "jenisTr" => "jenisTrMaster",
                        ),
                        "resultParams" => array(
                            "items" => array(
                                "harga" => "hpp",
                                "hpp" => "hpp",
                            ),
                            "items2_sum" => array(
                                "harga" => "hpp",
                                "hpp" => "hpp",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "FifoSuppliesAssembly",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => "gudangID",
                            "jenisTr" => "jenisTrMaster",
                        ),
                        "resultParams" => array(
                            "rsltItems" => array( // berisi bahan/supplies yang dipakai
                                "id" => "bahan_id",
                                "nama" => "nama",
                                "harga" => "hpp",
                                "hpp" => "hpp",
                                "jml" => "diambil",
                                "qty" => "diambil",
                                "subtotal" => "subHPP",
                                "hpp_riil" => "hpp_riil",
                                "ppv_riil" => "ppv_riil",
                                "subHpp_riil" => "subHpp_riil",
                                "subPpv_riil" => "subPpv_riil",
                                "ppn_in" => "ppn_in",
                                "ppn_in_nilai" => "ppn_in_nilai",
                                "suppliers_id" => "suppliers_id",
                                "suppliers_nama" => "suppliers_nama",
                            ),
                            "rsltItems2" => array( // berisi produk hasil assembling
                                "id" => "produk_id",
                                "nama" => "produk_nama",
                                "name" => "produk_nama",
                                "harga" => "hpp",
                                "hpp" => "hpp",
                                "jml" => "jml",
                                "qty" => "jml",
                                "subtotal" => "subHPP",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
            "776" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoAverageSuppliesProsesAssembly",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => "gudangID",
                            "jenisTr" => "jenisTrMaster",
                        ),
                        "resultParams" => array(
                            "items" => array(
                                "harga" => "hpp",
                                "hpp" => "hpp",
                            ),
                            "items2_sum" => array(
                                "harga" => "hpp",
                                "hpp" => "hpp",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "FifoSuppliesProsesAssembly",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => "gudangID",
                            "jenisTr" => "jenisTrMaster",
                        ),
                        "resultParams" => array(
                            "rsltItems" => array( // berisi bahan/supplies yang dipakai
                                "id" => "bahan_id",
                                "nama" => "nama",
                                "harga" => "hpp",
                                "hpp" => "hpp",
                                "jml" => "diambil",
                                "qty" => "diambil",
                                "subtotal" => "subHPP",
                                "hpp_riil" => "hpp_riil",
                                "ppv_riil" => "ppv_riil",
                                "ppn_in" => "ppn_in",
                                "ppn_in_nilai" => "ppn_in_nilai",
                                "suppliers_id" => "suppliers_id",
                                "suppliers_nama" => "suppliers_nama",
                            ),
                            "rsltItems2" => array( // berisi produk hasil assembling
                                "id" => "produk_id",
                                "nama" => "produk_nama",
                                "name" => "produk_nama",
                                "harga" => "hpp",
                                "hpp" => "hpp",
                                "jml" => "jml",
                                "qty" => "jml",
                                "subtotal" => "subHPP",
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
            "776a" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "010303" => "-hpp",//persediaan supplies
                            "010308" => "hpp",//persediaan supplies proses
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
                            "010303" => "-hpp",//persediaan supplies
                            "010308" => "hpp",//persediaan supplies proses
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
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "010303" => "-sub_hpp",//persediaan supplies
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "-jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    array(
                        "comName" => "RekeningPembantuSuppliesProses",
                        "loop" => array(
                            "010308" => "sub_hpp",//persediaan supplies proses
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                ),
            ),
            "776" => array(
                "master" => array(
                    // jurnal reguler produksi/bom
                    /*
                     * realtive costnem masuk ke COA Hpp produk
                     * costing  masuk ke kategory
                     */
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "010308" => "-hpp",//persediaan supplies proses
                            "010305" => "hpp+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",//persediaan produk rakitan
                            "{costID_1_coa}" => "-costNilai_1", // ex:overhead
                            "{costID_2_coa}" => "-costNilai_2", // ex:biaya_kirim
                            "{costID_3_coa}" => "-costNilai_3", // ex:tenaga_kerja
                            "{costID_4_coa}" => "-costNilai_4", // ex:tenaga_kerja
                            "{costID_5_coa}" => "-costNilai_5", // ex:tenaga_kerja
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
                            "010308" => "-hpp",//persediaan supplies proses
                            "010305" => "hpp+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",//persediaan produk rakitan
                            "{costID_1_coa}" => "-costNilai_1", // ex:overhead
                            "{costID_2_coa}" => "-costNilai_2", // ex:biaya_kirim
                            "{costID_3_coa}" => "-costNilai_3", // ex:tenaga_kerja
                            "{costID_4_coa}" => "-costNilai_4", // ex:tenaga_kerja
                            "{costID_5_coa}" => "-costNilai_5", // ex:tenaga_kerja
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // jurnal main cost vs efisiensi bom
                    /*
                     * relative costname pembantu efisiensi biaya jagan terbalik dengan hpp beda coa
                     * costing  dikeluarkan dari kategory hpp masuk ke efisiensi biaya
                     */
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "030201" => "(costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5)",//efisiensi biaya
                            "{costID_1_coa}" => "costNilai_1", // ex:overhead
                            "{costID_2_coa}" => "costNilai_2", // ex:biaya_kirim
                            "{costID_3_coa}" => "costNilai_3", // ex:tenaga_kerja
                            "{costID_4_coa}" => "costNilai_4", // ex:tenaga_kerja
                            "{costID_5_coa}" => "costNilai_5", // ex:tenaga_kerja
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
                            "030201" => "(costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5)",//efisiensi biaya
                            "{costID_1_coa}" => "costNilai_1", // ex:overhead
                            "{costID_2_coa}" => "costNilai_2", // ex:biaya_kirim
                            "{costID_3_coa}" => "costNilai_3", // ex:tenaga_kerja
                            "{costID_4_coa}" => "costNilai_4", // ex:tenaga_kerja
                            "{costID_5_coa}" => "costNilai_5", // ex:tenaga_kerja
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // pembantu efisiensi bom (cost)
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "030201" => "costNilai_1",//efisiensi biaya
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern2_id" => "costID_1",
                            "extern2_nama" => "costName_1",
                            "extern_id" => "efisiensiID_1_coa",
                            "extern_nama" => "efisiensiName_1_coa",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "030201" => "costNilai_2",//efisiensi biaya
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "efisiensiID_2_coa",
                            "extern_nama" => "efisiensiName_2_coa",
                            "extern2_id" => "costID_2",
                            "extern2_nama" => "costName_2",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "030201" => "costNilai_3",//efisiensi biaya
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "efisiensiID_3_coa",
                            "extern_nama" => "efisiensiName_3_coa",
                            "extern2_id" => "costID_3",
                            "extern2_nama" => "costName_3",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "030201" => "costNilai_4",//efisiensi biaya
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "efisiensiID_4_coa",
                            "extern_nama" => "efisiensiName_4_coa",
                            "extern2_id" => "costID_4",
                            "extern2_nama" => "costName_4",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "030201" => "costNilai_5",//efisiensi biaya
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "efisiensiID_5_coa",
                            "extern_nama" => "efisiensiName_5_coa",
                            "extern2_id" => "costID_5",
                            "extern2_nama" => "costName_5",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //
                    //jurnal ppv pusat
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "020407" => "-ppv_riil",// hutang lain ppv
                            "0703" => "ppv_riil",// laba lain lain
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "020407" => "-ppv_riil",// hutang lain ppv
                            "0703" => "ppv_riil",// laba lain lain
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // detail laba lain-lain
                    array(
                        "comName" => "RekeningPembantuLRLainlain",
                        "loop" => array(
                            "0703" => "ppv_riil",// laba lain lain
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "extern_id" => ".3",// laba rugi lain-lain ppv
                            "extern_nama" => ".ppv", // laba rugi lain-lain ppv
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(
                    //<editor-fold desc="Com-pembantu supplies">
                    array(
                        "comName" => "RekeningPembantuSuppliesProses",
                        "loop" => array(
                            "010308" => "-sub_hpp",//persediaan supplies proses
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "-jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    //</editor-fold>

                    //<editor-fold desc="Com-pembantu produk">
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "010305" => "sub_hpp+sub_costNilai_1+sub_costNilai_2+sub_costNilai_3+sub_costNilai_4+sub_costNilai_5",//persediaan produk rakitan
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "jml",
                            "produk_nilai" => "hpp+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "rsltItems2",
                        "srcRawGateName" => "rsltItems2",
                    ),
                    //</editor-fold>


                    //<editor-fold desc="Com-pembantu overhead,tenaga kerja,biaya kirim">
//                    array(
//                        "comName" => "RekeningPembantuBiayaKomposisiProduksi",
//                        "loop" => array(
//                            "{costName_1}" => "-sub_costNilai_1", // isi loop adalah overhead,tenaga kerja,biaya kirim
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id", // ID pembantunya adalah produk hasil produksi
//                            "extern_nama" => "nama", // NAME pembantunya adalah produk hasil produksi
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "rsltItems2",
//                        "srcRawGateName" => "rsltItems2",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuBiayaKomposisiProduksi",
//                        "loop" => array(
//                            "{costName_2}" => "-sub_costNilai_2", // isi loop adalah overhead,tenaga kerja,biaya kirim
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id", // ID pembantunya adalah produk hasil produksi
//                            "extern_nama" => "nama", // NAME pembantunya adalah produk hasil produksi
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "rsltItems2",
//                        "srcRawGateName" => "rsltItems2",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuBiayaKomposisiProduksi",
//                        "loop" => array(
//                            "{costName_3}" => "-sub_costNilai_3", // isi loop adalah overhead,tenaga kerja,biaya kirim
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id", // ID pembantunya adalah produk hasil produksi
//                            "extern_nama" => "nama", // NAME pembantunya adalah produk hasil produksi
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "rsltItems2",
//                        "srcRawGateName" => "rsltItems2",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuBiayaKomposisiProduksi",
//                        "loop" => array(
//                            "{costName_4}" => "-sub_costNilai_4", // isi loop adalah overhead,tenaga kerja,biaya kirim
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id", // ID pembantunya adalah produk hasil produksi
//                            "extern_nama" => "nama", // NAME pembantunya adalah produk hasil produksi
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "rsltItems2",
//                        "srcRawGateName" => "rsltItems2",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuBiayaKomposisiProduksi",
//                        "loop" => array(
//                            "{costName_5}" => "-sub_costNilai_5", // isi loop adalah overhead,tenaga kerja,biaya kirim
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id", // ID pembantunya adalah produk hasil produksi
//                            "extern_nama" => "nama", // NAME pembantunya adalah produk hasil produksi
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "rsltItems2",
//                        "srcRawGateName" => "rsltItems2",
//                    ),
//
//
//
//                    array(
//                        "comName" => "RekeningPembantuBiayaKomposisiProduksi",
//                        "loop" => array(
//                            "{costName_1}" => "sub_costNilai_1", // isi loop adalah overhead,tenaga kerja,biaya kirim
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id", // ID pembantunya adalah produk hasil produksi
//                            "extern_nama" => "nama", // NAME pembantunya adalah produk hasil produksi
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "rsltItems2",
//                        "srcRawGateName" => "rsltItems2",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuBiayaKomposisiProduksi",
//                        "loop" => array(
//                            "{costName_2}" => "sub_costNilai_2", // isi loop adalah overhead,tenaga kerja,biaya kirim
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id", // ID pembantunya adalah produk hasil produksi
//                            "extern_nama" => "nama", // NAME pembantunya adalah produk hasil produksi
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "rsltItems2",
//                        "srcRawGateName" => "rsltItems2",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuBiayaKomposisiProduksi",
//                        "loop" => array(
//                            "{costName_3}" => "sub_costNilai_3", // isi loop adalah overhead,tenaga kerja,biaya kirim
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id", // ID pembantunya adalah produk hasil produksi
//                            "extern_nama" => "nama", // NAME pembantunya adalah produk hasil produksi
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "rsltItems2",
//                        "srcRawGateName" => "rsltItems2",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuBiayaKomposisiProduksi",
//                        "loop" => array(
//                            "{costName_4}" => "sub_costNilai_4", // isi loop adalah overhead,tenaga kerja,biaya kirim
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id", // ID pembantunya adalah produk hasil produksi
//                            "extern_nama" => "nama", // NAME pembantunya adalah produk hasil produksi
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "rsltItems2",
//                        "srcRawGateName" => "rsltItems2",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuBiayaKomposisiProduksi",
//                        "loop" => array(
//                            "{costName_5}" => "sub_costNilai_5", // isi loop adalah overhead,tenaga kerja,biaya kirim
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id", // ID pembantunya adalah produk hasil produksi
//                            "extern_nama" => "nama", // NAME pembantunya adalah produk hasil produksi
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "rsltItems2",
//                        "srcRawGateName" => "rsltItems2",
//                    ),
//
//                    array(
//                        "comName" => "RekeningPembantuEfisiensiBiaya",
//                        "loop" => array(
//                            "efisiensi biaya" => "sub_costNilai_1", // isi loop adalah overhead,tenaga kerja,biaya kirim
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id", // ID pembantunya adalah produk hasil produksi
//                            "extern_nama" => "nama", // NAME pembantunya adalah produk hasil produksi
//                            "extern_id" => "costID_1",
//                            "extern_nama" => "costName_1",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "rsltItems2",
//                        "srcRawGateName" => "rsltItems2",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuEfisiensiBiaya",
//                        "loop" => array(
//                            "efisiensi biaya" => "sub_costNilai_2", // isi loop adalah overhead,tenaga kerja,biaya kirim
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id", // ID pembantunya adalah produk hasil produksi
//                            "extern_nama" => "nama", // NAME pembantunya adalah produk hasil produksi
//                            "extern_id" => "costID_2",
//                            "extern_nama" => "costName_2",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "rsltItems2",
//                        "srcRawGateName" => "rsltItems2",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuEfisiensiBiaya",
//                        "loop" => array(
//                            "efisiensi biaya" => "sub_costNilai_3", // isi loop adalah overhead,tenaga kerja,biaya kirim
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id", // ID pembantunya adalah produk hasil produksi
//                            "extern_nama" => "nama", // NAME pembantunya adalah produk hasil produksi
//                            "extern_id" => "costID_3",
//                            "extern_nama" => "costName_3",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "rsltItems2",
//                        "srcRawGateName" => "rsltItems2",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuEfisiensiBiaya",
//                        "loop" => array(
//                            "efisiensi biaya" => "sub_costNilai_4", // isi loop adalah overhead,tenaga kerja,biaya kirim
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id", // ID pembantunya adalah produk hasil produksi
//                            "extern_nama" => "nama", // NAME pembantunya adalah produk hasil produksi
//                            "extern_id" => "costID_4",
//                            "extern_nama" => "costName_4",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "rsltItems2",
//                        "srcRawGateName" => "rsltItems2",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuEfisiensiBiaya",
//                        "loop" => array(
//                            "efisiensi biaya" => "sub_costNilai_5", // isi loop adalah overhead,tenaga kerja,biaya kirim
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id", // ID pembantunya adalah produk hasil produksi
//                            "extern_nama" => "nama", // NAME pembantunya adalah produk hasil produksi
//                            "extern_id" => "costID_5",
//                            "extern_nama" => "costName_5",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "rsltItems2",
//                        "srcRawGateName" => "rsltItems2",
//                    ),
                    //</editor-fold>
                ),
            ),
        ),
        "postProcessor" => array(
            "776r" => array(
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
                "detail" => array(
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".active",
                            "jumlah" => "-jml",
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
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".hold",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "transaksi_id",
                            "nomer" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                ),
            ),
            "776a" => array(
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
                            "step_number" => ".3",
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
                            "nilai" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    // menambah stok locker supplies dalam proses
                    array(
                        "comName" => "LockerStockSuppliesProses",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies_proses",
                            "state" => ".active",
                            "jumlah" => "jml",
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
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    // locker stok mutasi supplies dalam proses
                    array(
                        "comName" => "LockerStockMutasiSuppliesProses",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "qty_debet" => "jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    // locker stok supplies
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".hold",
                            "jumlah" => "-jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterID",
                            "nomer" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".assembled",
                            "jumlah" => "jml",
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
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    // locker stok mutasi supplies
                    array(
                        "comName" => "LockerStockMutasiSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "qty_debet" => "-jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    // menambah fifo average supplies dalam proses
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".supplies_proses",
                            "jml" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "hpp" => "hpp",
                            "jml_nilai" => "sub_hpp",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),

                    // menambah fifo real supplies dalam proses
                    array(
                        "comName" => "FifoSuppliesProses",
                        "loop" => array(),
                        "static" => array(
                            "unit" => "jml",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "hpp" => "hpp",
                            "jml_nilai" => "sub_hpp",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "hpp_riil" => "hpp_riil",
                            "ppv_riil" => "ppv_riil",
                            "jml_nilai_riil" => "sub_hpp_riil",
                            "ppv_nilai_riil" => "sub_ppv_riil",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                ),
            ),
            "776" => array(
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
                            "step_number" => ".3",
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
                            "nilai" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    //<editor-fold desc="mengurangi stok supplies dalam proses">
                    array(
                        "comName" => "LockerStockSuppliesProses",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies_proses",
                            "state" => ".active",
                            "jumlah" => "-jml",
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
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    array(
                        "comName" => "LockerStockSuppliesProses",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies_proses",
                            "state" => ".assembled",
                            "jumlah" => "jml",
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
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    // locker stok mutasi supplies dalam proses
                    array(
                        "comName" => "LockerStockMutasiSuppliesProses",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "qty_debet" => "-jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    //</editor-fold>

                    //<editor-fold desc="menambah stok">
                    //<editor-fold desc="Com-fifo average dan fifo murni">
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".produk",
                            "jml" => "jml",
                            "produk_id" => "id",
                            "hpp" => "hpp+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
                            "jml_nilai" => "sub_hpp+sub_costNilai_1+sub_costNilai_2+sub_costNilai_3+sub_costNilai_4+sub_costNilai_5",
                            "nama" => "nama",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "rsltItems2",
                        "srcRawGateName" => "rsltItems2",
                    ),
                    array(
                        "comName" => "FifoProdukJadiRakitan",
                        "loop" => array(),
                        "static" => array(
                            "unit" => "jml",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "hpp" => "hpp+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
                            "jml_nilai" => "sub_hpp+sub_costNilai_1+sub_costNilai_2+sub_costNilai_3+sub_costNilai_4+sub_costNilai_5",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "hpp_riil" => "hpp+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
                            "jml_nilai_riil" => "sub_hpp+sub_costNilai_1+sub_costNilai_2+sub_costNilai_3+sub_costNilai_4+sub_costNilai_5",
                            "ppv_riil" => .0,
                            "ppv_nilai_riil" => .0,
                        ),
                        "srcGateName" => "rsltItems2",
                        "srcRawGateName" => "rsltItems2",
                    ),
                    //</editor-fold>

                    array(
                        "comName" => "LockerStockProduksi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk rakitan",
                            "state" => ".active",
                            "jumlah" => "qty",
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
);