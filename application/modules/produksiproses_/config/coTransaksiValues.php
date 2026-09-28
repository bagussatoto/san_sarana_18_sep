<?php

$config["coTransaksiValues"] = array(
    //  config assembling / produksi
    "776_ORI" => array(
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
                            "persediaan supplies" => "-hpp",
                            "persediaan supplies proses" => "hpp",
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
                            "persediaan supplies" => "-hpp",
                            "persediaan supplies proses" => "hpp",
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
                            "persediaan supplies" => "-sub_hpp",
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
                            "persediaan supplies proses" => "sub_hpp",
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
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "persediaan supplies proses" => "-hpp",
                            "persediaan produk rakitan" => "hpp+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
                            "{costName_1}" => "-costNilai_1", // ex:overhead
                            "{costName_2}" => "-costNilai_2", // ex:biaya_kirim
                            "{costName_3}" => "-costNilai_3", // ex:tenaga_kerja
                            "{costName_4}" => "-costNilai_4", // ex:tenaga_kerja
                            "{costName_5}" => "-costNilai_5", // ex:tenaga_kerja
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
                            "persediaan supplies proses" => "-hpp",
                            "persediaan produk rakitan" => "hpp+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
                            "{costName_1}" => "-costNilai_1", // ex:overhead
                            "{costName_2}" => "-costNilai_2", // ex:biaya_kirim
                            "{costName_3}" => "-costNilai_3", // ex:tenaga_kerja
                            "{costName_4}" => "-costNilai_4", // ex:tenaga_kerja
                            "{costName_5}" => "-costNilai_5", // ex:tenaga_kerja
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
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "efisiensi biaya" => "(costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5)",
                            "{costName_1}" => "costNilai_1", // ex:overhead
                            "{costName_2}" => "costNilai_2", // ex:biaya_kirim
                            "{costName_3}" => "costNilai_3", // ex:tenaga_kerja
                            "{costName_4}" => "costNilai_4", // ex:tenaga_kerja
                            "{costName_5}" => "costNilai_5", // ex:tenaga_kerja
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
                            "efisiensi biaya" => "(costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5)",
                            "{costName_1}" => "costNilai_1", // ex:overhead
                            "{costName_2}" => "costNilai_2", // ex:biaya_kirim
                            "{costName_3}" => "costNilai_3", // ex:tenaga_kerja
                            "{costName_4}" => "costNilai_4", // ex:tenaga_kerja
                            "{costName_5}" => "costNilai_5", // ex:tenaga_kerja
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
                            "efisiensi biaya" => "costNilai_1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "costID_1",
                            "extern_nama" => "costName_1",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "costNilai_2",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "costID_2",
                            "extern_nama" => "costName_2",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "costNilai_3",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "costID_3",
                            "extern_nama" => "costName_3",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "costNilai_4",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "costID_4",
                            "extern_nama" => "costName_4",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "costNilai_5",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "costID_5",
                            "extern_nama" => "costName_5",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //
                ),
                "detail" => array(
                    //<editor-fold desc="Com-pembantu supplies">
                    array(
                        "comName" => "RekeningPembantuSuppliesProses",
                        "loop" => array(
                            "persediaan supplies proses" => "-sub_hpp",
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
                            "persediaan produk rakitan" => "sub_hpp+sub_costNilai_1+sub_costNilai_2+sub_costNilai_3+sub_costNilai_4+sub_costNilai_5",
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

            ),
            "detail2" => array(//===sumber nilai berupa rincian
                "name" => "nama",
                "qty" => "jml",
            ),
            "detail2_sum" => array(//===sumber nilai berupa rincian
                "name" => "nama",
                "qty" => "jml",
                "total_cost" => "costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
            ),
            "rsltItems" => array(//===sumber nilai berupa rincian
                "name" => "nama",
                "qty" => "jml",
            ),
            "rsltItems2" => array(//===sumber nilai berupa rincian
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
//            "776wip" => array(
//                "master" => array(),
//                "detail" => array(
//                    array(
//                        "comName"        => "FifoSuppliesAssembly",
//                        "loop"           => array(),
//                        "static"         => array(
//                            "cabang_id"   => "placeID",
//                            "extern_id"   => "id",
//                            "extern_nama" => "nama",
//                            "produk_qty"  => "jml",
//                            "gudang_id"   => ".17",//ditembak langsung karena pasti fase 1
//                            "jenisTr"     => "jenisTrMaster",
//                        ),
//                        "resultParams"   => array(
//                            "rsltItems"  => array( // berisi bahan/supplies yang dipakai
//                                "id"       => "bahan_id",
//                                "nama"     => "nama",
//                                "harga"    => "hpp",
//                                "hpp"      => "hpp",
//                                "jml"      => "diambil",
//                                "qty"      => "diambil",
//                                "subtotal" => "subHPP",
//                            ),
//                            "rsltItems2" => array( // berisi produk hasil assembling
//                                "id"       => "produk_id",
//                                "nama"     => "produk_nama",
//                                "name"     => "produk_nama",
//                                "harga"    => "hpp",
//                                "hpp"      => "hpp",
//                                "jml"      => "jml",
//                                "qty"      => "jml",
//                                "subtotal" => "subHPP",
//                            ),
//                        ),
//                        "srcGateName"    => "items",
//                        "srcRawGateName" => "items",
//                    ),
//                ),
//            ),
            "776" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => ".18",
                        ),
                        "resultParams" => array(
                            "rsltItems" => array(
                                "id" => "produk_id",
                                "nama" => "nama",
                                "name" => "nama",
                                "harga" => "hpp",
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
                "produk_ord_jml" => "jml",
                "valid_qty" => "jml",
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
                "valid_qty" => "jml",
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
            "776wip" => array(
                "master" => array(
                    // jurnal reguler produksi
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "bb bom" => "-harga_bom",
                            "persediaan wip1" => "harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
                            "{costName_1}" => "-costNilai_1", // ex:overhead
                            "{costName_2}" => "-costNilai_2", // ex:direct labor
                            "{costName_3}" => "-costNilai_3", // ex:dst
                            "{costName_4}" => "-costNilai_4", // ex:dst
                            "{costName_5}" => "-costNilai_5", // ex:dst
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "bb bom" => "-harga_bom",
                            "persediaan wip1" => "harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
                            "{costName_1}" => "-costNilai_1", // ex:overhead
                            "{costName_2}" => "-costNilai_2", // ex:biaya_kirim
                            "{costName_3}" => "-costNilai_3", // ex:tenaga_kerja
                            "{costName_4}" => "-costNilai_4", // ex:tenaga_kerja
                            "{costName_5}" => "-costNilai_5", // ex:tenaga_kerja
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //juranal ori fifo ke persediaan
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "persediaan supplies" => "-hpp",
                            "bb1" => "hpp",
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
                            "persediaan supplies" => "-hpp",
                            "bb1" => "hpp",
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
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "efisiensi biaya" => "(harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5)",
                            "bb bom" => "harga_bom", // bb bom
                            "{costName_1}" => "costNilai_1", // ex:overhead
                            "{costName_2}" => "costNilai_2", // ex:biaya_kirim
                            "{costName_3}" => "costNilai_3", // ex:tenaga_kerja
                            "{costName_4}" => "costNilai_4", // ex:tenaga_kerja
                            "{costName_5}" => "costNilai_5", // ex:tenaga_kerja
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "efisiensi biaya" => "harga_bom+(costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5)",
                            "bb bom" => "harga_bom", // bb bom
                            "{costName_1}" => "costNilai_1", // ex:overhead
                            "{costName_2}" => "costNilai_2", // ex:biaya_kirim
                            "{costName_3}" => "costNilai_3", // ex:tenaga_kerja
                            "{costName_4}" => "costNilai_4", // ex:tenaga_kerja
                            "{costName_5}" => "costNilai_5", // ex:tenaga_kerja
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // pembantu efisiensi bom (cost)
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "harga_bom",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".7",
                            "extern_nama" => ".bb bom",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "costNilai_1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "costID_1",
                            "extern_nama" => "costName_1",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "costNilai_2",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "costID_2",
                            "extern_nama" => "costName_2",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "costNilai_3",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "costID_3",
                            "extern_nama" => "costName_3",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "costNilai_4",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "costID_4",
                            "extern_nama" => "costName_4",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "costNilai_5",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "costID_5",
                            "extern_nama" => "costName_5",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //pembantu gudang supplies bb out
                    array(
                        "comName" => "RekeningPembantuGudang",
                        "loop" => array(
                            "persediaan supplies" => "-hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "gudangID",//gudang bj fase 1
                            "extern_nama" => "gudangName",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //jurnal region pembantu gudang supplies wip
                    array(
                        "comName" => "RekeningPembantuGudang",
                        "loop" => array(
                            "persediaan wip1" => "harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".18",//gudang bj fase 1
                            "extern_nama" => ".Gudang BJ Fase 1",
                            "produk_qty" => "qty",
                            "produk_nilai" => "harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
                            "gudang_id" => ".20",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //jurnal region pembantu gudang supplies bb1
                    array(
                        "comName" => "RekeningPembantuGudang",
                        "loop" => array(
                            "bb1" => "hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".17",//gudang bj fase 1
                            "extern_nama" => ".Gudang BB Fase 1",
                            "produk_qty" => "qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => ".17",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    //region Com-pembantu supplies
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "persediaan supplies" => "-sub_hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "-jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => ".17",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    //region pembantu bb1 vs supplies fifo
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "bb1" => "sub_hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => ".17",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    //endregion
                    //supplies fase
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "persediaan wip1" => "sub_harga_bom+sub_costNilai_1+sub_costNilai_2+sub_costNilai_3+sub_costNilai_4+sub_costNilai_5",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "jml",
                            "produk_nilai" => "harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
                            "gudang_id" => ".18",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //endregion

                ),
            ),
            "776" => array(
                "master" => array(
                    //jurnal geser peresediaan wip ke supplies
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "persediaan wip1" => "-hpp",
                            "persediaan supplies" => "hpp",
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
                            "persediaan wip1" => "-hpp",
                            "persediaan supplies" => "hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //jurnal region pembantu gudang supplies wip berkurang geser ke gudang selanjutnya
                    array(
                        "comName" => "RekeningPembantuGudang",
                        "loop" => array(
                            "persediaan wip1" => "-hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".18",//gudang bj fase 1
                            "extern_nama" => ".Gudang BJ Fase 1",
                            "produk_qty" => "qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => ".18",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //pembantu gudang supplies fase 2
                    array(
                        "comName" => "RekeningPembantuGudang",
                        "loop" => array(
                            "persediaan supplies" => "hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".19",//gudang bj fase 1
                            "extern_nama" => ".Gudang BB Fase 2",
                            "produk_qty" => "qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => ".19",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    //auto geser gudang ke fase 2 pembantu
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "persediaan wip1" => "-sub_hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => ".18",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "persediaan supplies" => "sub_hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "produk_nilai" => "hpp",
                            //                            "gudang_id" => "pihakID",
                            "gudang_id" => ".19",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "776r" => array(
                "master" => array(),
                "detail" => array(
                    //fifo masuk wip supplies
//                    array(
//                        "comName"        => "FifoSupplies",
//                        "loop"           => array(),
//                        "static"         => array(
//                            "unit"           => "qty",
//                            "produk_id"      => "id",
//                            "produk_nama"    => "name",
//                            "hpp"            => "harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
//                            "jml_nilai"      => "sub_harga_bom+sub_costNilai_1+sub_costNilai_2+sub_costNilai_3+sub_costNilai_4+sub_costNilai_5",
//                            "hpp_riil"       => "hpp_riil",
//                            "jml_nilai_riil" => "sub_hpp_riil",
//                            "ppv_riil"       => "ppv_riil",
//                            "ppv_nilai_riil" => "sub_ppv_riil",
//                            "cabang_id"      => "placeID",
//                            "gudang_id"      => "gudangID",
//                            "ppn_in"         => "ppn_in",
//                            "ppn_in_nilai"   => "sub_ppn_in",
//                            "suppliers_id"   => "suppliers_id",
//                            "suppliers_nama" => "suppliers_nama",
//                        ),
//                        "srcGateName"    => "items",
//                        "srcRawGateName" => "items",
//                    ),

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
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
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
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    //locker stok gudang wip
                    //region post-locker stock dari supplies source
//                    array(
//                        "comName"        => "LockerStockSupplies",
//                        "loop"           => array(),
//                        "static"         => array(
//                            "cabang_id"    => "placeID",
//                            "jenis"        => ".supplies",
//                            "state"        => ".active",
//                            "jumlah"       => "-jml",
//                            "produk_id"    => "id",
//                            "nama"         => "name",
//                            "satuan"       => "satuan",
//                            "oleh_id"      => ".0",
//                            "oleh_nama"    => ".0",
//                            "transaksi_id" => ".0",
//                            "nomer"        => ".0",
//                            "gudang_id"    => "gudangID",
////                            "gudang_id"    => ".17",
//                        ),
//                        "reversable"     => true,
//                        "srcGateName"    => "rsltItems",
//                        "srcRawGateName" => "rsltItems",
//                    ),

                ),
            ),
            "776wip" => array(
                "master" => array(),
                "detail" => array(
                    //fifo masuk wip supplies
//                    array(
//                        "comName"        => "FifoSupplies",
//                        "loop"           => array(),
//                        "static"         => array(
//                            "unit"           => "qty",
//                            "produk_id"      => "id",
//                            "produk_nama"    => "name",
//                            "hpp"            => "harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
//                            "jml_nilai"      => "sub_harga_bom+sub_costNilai_1+sub_costNilai_2+sub_costNilai_3+sub_costNilai_4+sub_costNilai_5",
//                            "hpp_riil"       => "hpp_riil",
//                            "jml_nilai_riil" => "sub_hpp_riil",
//                            "ppv_riil"       => "ppv_riil",
//                            "ppv_nilai_riil" => "sub_ppv_riil",
//                            "cabang_id"      => "placeID",
//                            "gudang_id"      => ".7001",
//                            "ppn_in"         => "ppn_in",
//                            "ppn_in_nilai"   => "sub_ppn_in",
//                            "suppliers_id"   => "suppliers_id",
//                            "suppliers_nama" => "suppliers_nama",
//                        ),
//                        "srcGateName"    => "items",
//                        "srcRawGateName" => "items",
//                    ),
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "jenis" => ".supplies",
                            "state" => ".hold",
                            "jumlah" => "-jml",
                            "produk_id" => "id",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => ".0",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    //locker stok gudang wip
                    //region post-locker stock dari supplies source
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".active",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterID",
                            "nomer" => ".0",
                            "gudang_id" => ".7000",
//                            "gudang_id"    => ".17",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    //region locker stok mutasi
                    // array(
                    //     "comName"        => "LockerStockMutasi",
                    //     "loop"           => array(),
                    //     "static"         => array(
                    //         "cabang_id"    => "placeID",
                    //         "extern_id"    => "id",
                    //         "extern_nama"  => "name",
                    //         "qty_debet"    => "-qty",
                    //         "produk_nilai" => "hpp",
                    //         "gudang_id"    => "gudangID",
                    //         "jenis"        => "jenisTr",
                    //     ),
                    //     "reversable"     => true,
                    //     "srcGateName"    => "items",
                    //     "srcRawGateName" => "items",
                    // ),

                    //endregion


                ),
            ),
            "776" => array(
                "master" => array(),
                "detail" => array(
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
                            // "gudang_id" => "gudangID",
                            "gudang_id" => ".18",
                        ),
                        "reversable" => true,
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".moved",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => ".0",
                            "nomer" => ".0",
                            // "gudang_id" => "gudangID",
                            "gudang_id" => ".18",
                        ),
                        "reversable" => true,
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    //stok supplies gudang 2 fase 2
                    array(
                        "comName" => "FifoSupplies",
                        "loop" => array(),
                        "static" => array(
                            "unit" => "qty",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "hpp" => "hpp",
                            "jml_nilai" => "sub_hpp",
                            "hpp_riil" => "hpp_riil",
                            "jml_nilai_riil" => "sub_hpp_riil",
                            "ppv_riil" => "ppv_riil",
                            "ppv_nilai_riil" => "sub_ppv_riil",
                            "cabang_id" => "placeID",
                            "gudang_id" => ".19",
                            "ppn_in" => "ppn_in",
                            "ppn_in_nilai" => "sub_ppn_in",
                            "suppliers_id" => "suppliers_id",
                            "suppliers_nama" => "suppliers_nama",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
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
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => ".0",
                            "nomer" => ".0",
                            // "gudang_id" => "gudangID",
                            "gudang_id" => ".19",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
    ),
    "7776" => array(
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

            ),
            "detail2" => array(//===sumber nilai berupa rincian
                "name" => "nama",
                "qty" => "jml",
            ),
            "detail2_sum" => array(//===sumber nilai berupa rincian
                "name" => "nama",
                "qty" => "jml",
                "total_cost" => "costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
            ),
            "rsltItems" => array(//===sumber nilai berupa rincian
                "name" => "nama",
                "qty" => "jml",
            ),
            "rsltItems2" => array(//===sumber nilai berupa rincian
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

//            "776" => array(
//                "master" => array(),
//                "detail" => array(
//                    array(
//                        "comName" => "FifoSupplies",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "produk_qty" => "qty",
//                            "gudang_id" => ".18",
//                        ),
//                        "resultParams" => array(
//                            "rsltItems" => array(
//                                "id" => "produk_id",
//                                "nama" => "nama",
//                                "name" => "nama",
//                                "harga" => "hpp",
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
//                            ),
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
//                ),
//
//            ),
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
                "produk_ord_jml" => "jml",
                "valid_qty" => "jml",
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
                "valid_qty" => "jml",
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
//            "776wip" => array(
//                "master" => array(
//                    // jurnal reguler produksi
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "bb bom" => "-harga_bom",
//                            "persediaan wip1" => "harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
//                            "{costName_1}" => "-costNilai_1", // ex:overhead
//                            "{costName_2}" => "-costNilai_2", // ex:direct labor
//                            "{costName_3}" => "-costNilai_3", // ex:dst
//                            "{costName_4}" => "-costNilai_4", // ex:dst
//                            "{costName_5}" => "-costNilai_5", // ex:dst
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "gudang_id" => "gudangID",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "bb bom" => "-harga_bom",
//                            "persediaan wip1" => "harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
//                            "{costName_1}" => "-costNilai_1", // ex:overhead
//                            "{costName_2}" => "-costNilai_2", // ex:biaya_kirim
//                            "{costName_3}" => "-costNilai_3", // ex:tenaga_kerja
//                            "{costName_4}" => "-costNilai_4", // ex:tenaga_kerja
//                            "{costName_5}" => "-costNilai_5", // ex:tenaga_kerja
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    //juranal ori fifo ke persediaan
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "persediaan supplies" => "-hpp",
//                            "bb1" => "hpp",
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
//                            "persediaan supplies" => "-hpp",
//                            "bb1" => "hpp",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    // jurnal main cost vs efisiensi bom
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "efisiensi biaya" => "(harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5)",
//                            "bb bom" => "harga_bom", // bb bom
//                            "{costName_1}" => "costNilai_1", // ex:overhead
//                            "{costName_2}" => "costNilai_2", // ex:biaya_kirim
//                            "{costName_3}" => "costNilai_3", // ex:tenaga_kerja
//                            "{costName_4}" => "costNilai_4", // ex:tenaga_kerja
//                            "{costName_5}" => "costNilai_5", // ex:tenaga_kerja
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "gudang_id" => "gudangID",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "efisiensi biaya" => "harga_bom+(costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5)",
//                            "bb bom" => "harga_bom", // bb bom
//                            "{costName_1}" => "costNilai_1", // ex:overhead
//                            "{costName_2}" => "costNilai_2", // ex:biaya_kirim
//                            "{costName_3}" => "costNilai_3", // ex:tenaga_kerja
//                            "{costName_4}" => "costNilai_4", // ex:tenaga_kerja
//                            "{costName_5}" => "costNilai_5", // ex:tenaga_kerja
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "gudang_id" => "gudangID",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    // pembantu efisiensi bom (cost)
//                    array(
//                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
//                        "loop" => array(
//                            "efisiensi biaya" => "harga_bom",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => ".7",
//                            "extern_nama" => ".bb bom",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
//                        "loop" => array(
//                            "efisiensi biaya" => "costNilai_1",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "costID_1",
//                            "extern_nama" => "costName_1",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
//                        "loop" => array(
//                            "efisiensi biaya" => "costNilai_2",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "costID_2",
//                            "extern_nama" => "costName_2",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
//                        "loop" => array(
//                            "efisiensi biaya" => "costNilai_3",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "costID_3",
//                            "extern_nama" => "costName_3",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
//                        "loop" => array(
//                            "efisiensi biaya" => "costNilai_4",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "costID_4",
//                            "extern_nama" => "costName_4",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
//                        "loop" => array(
//                            "efisiensi biaya" => "costNilai_5",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "costID_5",
//                            "extern_nama" => "costName_5",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    //pembantu gudang supplies bb out
//                    array(
//                        "comName" => "RekeningPembantuGudang",
//                        "loop" => array(
//                            "persediaan supplies" => "-hpp",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "gudangID",//gudang bj fase 1
//                            "extern_nama" => "gudangName",
//                            "produk_qty" => "-qty",
//                            "produk_nilai" => "hpp",
//                            "gudang_id" => "gudangID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    //jurnal region pembantu gudang supplies wip
//                    array(
//                        "comName" => "RekeningPembantuGudang",
//                        "loop" => array(
//                            "persediaan wip1" => "harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => ".18",//gudang bj fase 1
//                            "extern_nama" => ".Gudang BJ Fase 1",
//                            "produk_qty" => "qty",
//                            "produk_nilai" => "harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
//                            "gudang_id" => ".20",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    //jurnal region pembantu gudang supplies bb1
//                    array(
//                        "comName" => "RekeningPembantuGudang",
//                        "loop" => array(
//                            "bb1" => "hpp",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => ".17",//gudang bj fase 1
//                            "extern_nama" => ".Gudang BB Fase 1",
//                            "produk_qty" => "qty",
//                            "produk_nilai" => "hpp",
//                            "gudang_id" => ".17",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                ),
//                "detail" => array(
//                    //region Com-pembantu supplies
//                    array(
//                        "comName" => "RekeningPembantuSupplies",
//                        "loop" => array(
//                            "persediaan supplies" => "-sub_hpp",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "nama",
//                            "produk_qty" => "-jml",
//                            "produk_nilai" => "hpp",
//                            "gudang_id" => ".17",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "rsltItems",
//                        "srcRawGateName" => "rsltItems",
//                    ),
//                    //region pembantu bb1 vs supplies fifo
//                    array(
//                        "comName" => "RekeningPembantuSupplies",
//                        "loop" => array(
//                            "bb1" => "sub_hpp",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "nama",
//                            "produk_qty" => "jml",
//                            "produk_nilai" => "hpp",
//                            "gudang_id" => ".17",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "rsltItems",
//                        "srcRawGateName" => "rsltItems",
//                    ),
//                    //endregion
//                    //supplies fase
//                    array(
//                        "comName" => "RekeningPembantuSupplies",
//                        "loop" => array(
//                            "persediaan wip1" => "sub_harga_bom+sub_costNilai_1+sub_costNilai_2+sub_costNilai_3+sub_costNilai_4+sub_costNilai_5",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "nama",
//                            "produk_qty" => "jml",
//                            "produk_nilai" => "harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
//                            "gudang_id" => ".18",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
//                    //endregion
//
//                ),
//            ),
//            "776" => array(
//                "master" => array(
//                    //jurnal geser peresediaan wip ke supplies
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "persediaan wip1" => "-hpp",
//                            "persediaan supplies" => "hpp",
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
//                            "persediaan wip1" => "-hpp",
//                            "persediaan supplies" => "hpp",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    //jurnal region pembantu gudang supplies wip berkurang geser ke gudang selanjutnya
//                    array(
//                        "comName" => "RekeningPembantuGudang",
//                        "loop" => array(
//                            "persediaan wip1" => "-hpp",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => ".18",//gudang bj fase 1
//                            "extern_nama" => ".Gudang BJ Fase 1",
//                            "produk_qty" => "qty",
//                            "produk_nilai" => "hpp",
//                            "gudang_id" => ".18",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    //pembantu gudang supplies fase 2
//                    array(
//                        "comName" => "RekeningPembantuGudang",
//                        "loop" => array(
//                            "persediaan supplies" => "hpp",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => ".19",//gudang bj fase 1
//                            "extern_nama" => ".Gudang BB Fase 2",
//                            "produk_qty" => "qty",
//                            "produk_nilai" => "hpp",
//                            "gudang_id" => ".19",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                ),
//                "detail" => array(
//                    //auto geser gudang ke fase 2 pembantu
//                    array(
//                        "comName" => "RekeningPembantuSupplies",
//                        "loop" => array(
//                            "persediaan wip1" => "-sub_hpp",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "produk_qty" => "-qty",
//                            "produk_nilai" => "hpp",
//                            "gudang_id" => ".18",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "rsltItems",
//                        "srcRawGateName" => "rsltItems",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuSupplies",
//                        "loop" => array(
//                            "persediaan supplies" => "sub_hpp",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "produk_qty" => "qty",
//                            "produk_nilai" => "hpp",
//                            //                            "gudang_id" => "pihakID",
//                            "gudang_id" => ".19",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "rsltItems",
//                        "srcRawGateName" => "rsltItems",
//                    ),
//                ),
//            ),
        ),
        "postProcessor" => array(
            "master" => array(),
            "detail" => array(
                array(
                    "comName" => "",
                ),
            ),

//            "776r" => array(
//                "master" => array(),
//                "detail" => array(
//                    //fifo masuk wip supplies
////                    array(
////                        "comName"        => "FifoSupplies",
////                        "loop"           => array(),
////                        "static"         => array(
////                            "unit"           => "qty",
////                            "produk_id"      => "id",
////                            "produk_nama"    => "name",
////                            "hpp"            => "harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
////                            "jml_nilai"      => "sub_harga_bom+sub_costNilai_1+sub_costNilai_2+sub_costNilai_3+sub_costNilai_4+sub_costNilai_5",
////                            "hpp_riil"       => "hpp_riil",
////                            "jml_nilai_riil" => "sub_hpp_riil",
////                            "ppv_riil"       => "ppv_riil",
////                            "ppv_nilai_riil" => "sub_ppv_riil",
////                            "cabang_id"      => "placeID",
////                            "gudang_id"      => "gudangID",
////                            "ppn_in"         => "ppn_in",
////                            "ppn_in_nilai"   => "sub_ppn_in",
////                            "suppliers_id"   => "suppliers_id",
////                            "suppliers_nama" => "suppliers_nama",
////                        ),
////                        "srcGateName"    => "items",
////                        "srcRawGateName" => "items",
////                    ),
//
//                    array(
//                        "comName" => "LockerStockSupplies",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => ".supplies",
//                            "state" => ".active",
//                            "jumlah" => "-jml",
//                            "produk_id" => "id",
//                            "nama" => "nama",
//                            "satuan" => "satuan",
//                            "transaksi_id" => ".0",
//                            "oleh_id" => ".0",
//                            "oleh_nama" => ".0",
//                            "gudang_id" => "gudangID",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "items2_sum",
//                        "srcRawGateName" => "items2_sum",
//                    ),
//
//                    array(
//                        "comName" => "LockerStockSupplies",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => ".supplies",
//                            "state" => ".hold",
//                            "jumlah" => "jml",
//                            "produk_id" => "id",
//                            "nama" => "nama",
//                            "satuan" => "satuan",
//                            "transaksi_id" => ".0",
//                            "oleh_id" => ".0",
//                            "oleh_nama" => ".0",
//                            "gudang_id" => "gudangID",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "items2_sum",
//                        "srcRawGateName" => "items2_sum",
//                    ),
//
//                    //locker stok gudang wip
//                    //region post-locker stock dari supplies source
////                    array(
////                        "comName"        => "LockerStockSupplies",
////                        "loop"           => array(),
////                        "static"         => array(
////                            "cabang_id"    => "placeID",
////                            "jenis"        => ".supplies",
////                            "state"        => ".active",
////                            "jumlah"       => "-jml",
////                            "produk_id"    => "id",
////                            "nama"         => "name",
////                            "satuan"       => "satuan",
////                            "oleh_id"      => ".0",
////                            "oleh_nama"    => ".0",
////                            "transaksi_id" => ".0",
////                            "nomer"        => ".0",
////                            "gudang_id"    => "gudangID",
//////                            "gudang_id"    => ".17",
////                        ),
////                        "reversable"     => true,
////                        "srcGateName"    => "rsltItems",
////                        "srcRawGateName" => "rsltItems",
////                    ),
//
//                ),
//            ),
//            "776wip" => array(
//                "master" => array(),
//                "detail" => array(
//                    //fifo masuk wip supplies
////                    array(
////                        "comName"        => "FifoSupplies",
////                        "loop"           => array(),
////                        "static"         => array(
////                            "unit"           => "qty",
////                            "produk_id"      => "id",
////                            "produk_nama"    => "name",
////                            "hpp"            => "harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
////                            "jml_nilai"      => "sub_harga_bom+sub_costNilai_1+sub_costNilai_2+sub_costNilai_3+sub_costNilai_4+sub_costNilai_5",
////                            "hpp_riil"       => "hpp_riil",
////                            "jml_nilai_riil" => "sub_hpp_riil",
////                            "ppv_riil"       => "ppv_riil",
////                            "ppv_nilai_riil" => "sub_ppv_riil",
////                            "cabang_id"      => "placeID",
////                            "gudang_id"      => ".7001",
////                            "ppn_in"         => "ppn_in",
////                            "ppn_in_nilai"   => "sub_ppn_in",
////                            "suppliers_id"   => "suppliers_id",
////                            "suppliers_nama" => "suppliers_nama",
////                        ),
////                        "srcGateName"    => "items",
////                        "srcRawGateName" => "items",
////                    ),
//                    array(
//                        "comName" => "LockerStockSupplies",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "jenis" => ".supplies",
//                            "state" => ".hold",
//                            "jumlah" => "-jml",
//                            "produk_id" => "id",
//                            "oleh_id" => ".0",
//                            "oleh_nama" => ".0",
//                            "transaksi_id" => ".0",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "items2_sum",
//                        "srcRawGateName" => "items2_sum",
//                    ),
//
//                    //locker stok gudang wip
//                    //region post-locker stock dari supplies source
//                    array(
//                        "comName" => "LockerStockSupplies",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => ".supplies",
//                            "state" => ".active",
//                            "jumlah" => "jml",
//                            "produk_id" => "id",
//                            "nama" => "nama",
//                            "satuan" => "satuan",
//                            "oleh_id" => ".0",
//                            "oleh_nama" => ".0",
//                            "transaksi_id" => "masterID",
//                            "nomer" => ".0",
//                            "gudang_id" => ".7000",
////                            "gudang_id"    => ".17",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "items2_sum",
//                        "srcRawGateName" => "items2_sum",
//                    ),
//
//                    //region locker stok mutasi
//                    // array(
//                    //     "comName"        => "LockerStockMutasi",
//                    //     "loop"           => array(),
//                    //     "static"         => array(
//                    //         "cabang_id"    => "placeID",
//                    //         "extern_id"    => "id",
//                    //         "extern_nama"  => "name",
//                    //         "qty_debet"    => "-qty",
//                    //         "produk_nilai" => "hpp",
//                    //         "gudang_id"    => "gudangID",
//                    //         "jenis"        => "jenisTr",
//                    //     ),
//                    //     "reversable"     => true,
//                    //     "srcGateName"    => "items",
//                    //     "srcRawGateName" => "items",
//                    // ),
//
//                    //endregion
//
//
//                ),
//            ),
//            "776" => array(
//                "master" => array(),
//                "detail" => array(
//                    array(
//                        "comName" => "LockerStockSupplies",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => ".supplies",
//                            "state" => ".active",
//                            "jumlah" => "-qty",
//                            "produk_id" => "id",
//                            "nama" => "name",
//                            "satuan" => "satuan",
//                            "oleh_id" => ".0",
//                            "oleh_nama" => ".0",
//                            "transaksi_id" => ".0",
//                            "nomer" => ".0",
//                            // "gudang_id" => "gudangID",
//                            "gudang_id" => ".18",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "rsltItems",
//                        "srcRawGateName" => "rsltItems",
//                    ),
//                    array(
//                        "comName" => "LockerStockSupplies",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => ".supplies",
//                            "state" => ".moved",
//                            "jumlah" => "qty",
//                            "produk_id" => "id",
//                            "nama" => "name",
//                            "satuan" => "satuan",
//                            "oleh_id" => ".0",
//                            "oleh_nama" => ".0",
//                            "transaksi_id" => ".0",
//                            "nomer" => ".0",
//                            // "gudang_id" => "gudangID",
//                            "gudang_id" => ".18",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "rsltItems",
//                        "srcRawGateName" => "rsltItems",
//                    ),
//                    //stok supplies gudang 2 fase 2
//                    array(
//                        "comName" => "FifoSupplies",
//                        "loop" => array(),
//                        "static" => array(
//                            "unit" => "qty",
//                            "produk_id" => "id",
//                            "produk_nama" => "name",
//                            "hpp" => "hpp",
//                            "jml_nilai" => "sub_hpp",
//                            "hpp_riil" => "hpp_riil",
//                            "jml_nilai_riil" => "sub_hpp_riil",
//                            "ppv_riil" => "ppv_riil",
//                            "ppv_nilai_riil" => "sub_ppv_riil",
//                            "cabang_id" => "placeID",
//                            "gudang_id" => ".19",
//                            "ppn_in" => "ppn_in",
//                            "ppn_in_nilai" => "sub_ppn_in",
//                            "suppliers_id" => "suppliers_id",
//                            "suppliers_nama" => "suppliers_nama",
//                        ),
//                        "srcGateName" => "rsltItems",
//                        "srcRawGateName" => "rsltItems",
//                    ),
//                    array(
//                        "comName" => "LockerStockSupplies",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => ".supplies",
//                            "state" => ".active",
//                            "jumlah" => "qty",
//                            "produk_id" => "id",
//                            "nama" => "name",
//                            "satuan" => "satuan",
//                            "oleh_id" => ".0",
//                            "oleh_nama" => ".0",
//                            "transaksi_id" => ".0",
//                            "nomer" => ".0",
//                            // "gudang_id" => "gudangID",
//                            "gudang_id" => ".19",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
//                ),
//            ),
        ),
    ),
    "7778" => array(// bergudang-gudang
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
                "gudang2_id" => "gudang_target_id",
            ),
            "detail2" => array(//===sumber nilai berupa rincian
                "name" => "nama",
                "qty" => "jml",
            ),
            "detail2_sum" => array(//===sumber nilai berupa rincian
                "name" => "nama",
                "qty" => "jml",
                "total_cost" => "costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
            ),
            "rsltItems" => array(//===sumber nilai berupa rincian
                "name" => "nama",
                "qty" => "jml",
            ),
            "rsltItems2" => array(//===sumber nilai berupa rincian
                "name" => "nama",
                "qty" => "jml",
            ),
            "rsltItems3" => array(
                //===sumber nilai berupa rincian
                "name" => "nama",
                "qty" => "jml",
                "harga_biaya" => "harga",
            ),
            "master_dependent" => array(
                "gudangBahanBakuMethode" => array(
                    "single" => array(
                        "gudang_id" => "gudangID_produk",
                        "gudang_nama" => "gudangName_produk",
                        "gudang_source_id" => "gudangID_produk",
                        "gudang_source_nama" => "gudangName_produk",
                        "gudang_target_id" => "gudangID_produk",
                        "gudang_target_nama" => "gudangName_produk",
                        "gudangID" => "gudangID_produk",
                        "gudangName" => "gudangName_produk",
                    ),
                ),
            ),
        ),
        "valueBuilders" => array(),
        "valueBuilders2" => array(),
        "valueBuilders2_sum" => array(),
        "valueBuilders_rsltItems" => array(),
        "valueBuilders_rsltItems2" => array(),
        "preProcessor" => array(
            "master" => array(),
            "detail" => array(
                array(
                    "comName" => "FifoAverageSuppliesAssembly",
                    "loop" => array(),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "produk_dasar_id",
                        "extern_nama" => "produk_dasar_nama",
                        "produk_qty" => "jml",
                        "gudang_id" => "gudangID",
                        "jenisTr" => "jenisTrMaster",
                    ),
                    "resultParams" => array(
                        // "items" => array(
                        //     "harga" => "hpp",
                        //     "hpp" => "hpp",
                        // ),
                        "items2_sum" => array(
                            "harga" => "hpp",
                            "hpp" => "hpp",
                        ),
                    ),
                    "srcGateName" => "items2_sum",
                    "srcRawGateName" => "items2_sum",
                ),
                array(
                    "comName" => "FifoSuppliesAssembly",
                    "loop" => array(),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "produk_dasar_id",
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
                            //---
                            "purchase_oleh_id" => "oleh_id",
                            "purchase_oleh_nama" => "oleh_nama",
                            "purchase_id" => "purchase_id",
                            "purchase_nomer" => "purchase_nomer",
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

                "kode_produksi" => "kode_produksi",
            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "jml",
                "valid_qty" => "jml",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "detail2_sum" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "jml",
                "valid_qty" => "jml",
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
            "master" => array(
                // jurnal ke 1 reguler produksi/bom
                /*
                 * realtive costnem masuk ke COA Hpp produk
                 * costing  masuk ke kategory
                 */
                array(
                    "comName" => "Jurnal",
                    "loop" => array(
                        //                        "1010030070" => "(harga_bahan+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5)",//persediaan produk rakitan
                        //                        "5020050" => "-harga_bahan",//supplies BOM
                        "1010030010" => "(nilai_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5)",//persediaan produk rakitan
                        "5020050" => "-nilai_bom",//supplies BOM
                        "{costID_1_coa}" => "-costNilai_1", // ex:overhead
                        "{costID_2_coa}" => "-costNilai_2", // ex:biaya_kirim
                        "{costID_3_coa}" => "-costNilai_3", // ex:tenaga_kerja
                        "{costID_4_coa}" => "-costNilai_4", // ex:tenaga_kerja
                        "{costID_5_coa}" => "-costNilai_5", // ex:bahan baku
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
                        //                        "1010030070" => "(harga_bahan+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5)",//persediaan produk rakitan
                        //                        "5020050" => "-harga_bahan",//supplies BOM
                        "1010030010" => "(nilai_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5)",//persediaan produk rakitan
                        "5020050" => "-nilai_bom",//supplies BOM
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

                // jurnal ke 2 efisiensi pada supplies riil yang diambil
                array(
                    "comName" => "Jurnal",
                    "loop" => array(
                        "3020010" => "-hpp",//efisiensi
                        "1010030010" => "-hpp",//persediaan supplies proses yang diambil
                        //                            "{costID_1_coa}" => "-costNilai_1", // ex:overhead
                        //                            "{costID_2_coa}" => "-costNilai_2", // ex:biaya_kirim
                        //                            "{costID_3_coa}" => "-costNilai_3", // ex:tenaga_kerja
                        //                            "{costID_4_coa}" => "-costNilai_4", // ex:tenaga_kerja
                        //                            "{costID_5_coa}" => "-costNilai_5", // ex:tenaga_kerja
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
                        "3020010" => "-hpp",//efisiensi
                        "1010030010" => "-hpp",//persediaan supplies proses yang diambil
                        //                            "{costID_1_coa}" => "-costNilai_1", // ex:overhead
                        //                            "{costID_2_coa}" => "-costNilai_2", // ex:biaya_kirim
                        //                            "{costID_3_coa}" => "-costNilai_3", // ex:tenaga_kerja
                        //                            "{costID_4_coa}" => "-costNilai_4", // ex:tenaga_kerja
                        //                            "{costID_5_coa}" => "-costNilai_5", // ex:tenaga_kerja
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),

                // pembantu efisiensi riil dari jurnal ke 2
                array(
                    "comName" => "RekeningPembantuEfisiensiBiayaMain",
                    "loop" => array(
                        "3020010" => "-hpp",//supplies BOM
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        //                            "extern2_id" => "costID_1",
                        //                            "extern2_nama" => "costName_1",
                        //                            "extern_id" => "efisiensiID_1_coa",
                        //                            "extern_id" => "costID_1",
                        //                            "extern_nama" => "efisiensiName_1_coa",
                        "extern_id" => ".6",
                        "extern_nama" => ".bahan baku",
                        "extern2_id" => ".6",
                        "extern2_nama" => ".bahan baku",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),

                // jurnal ke 3 main cost BOM vs efisiensi
                /*
                 * relative costname pembantu efisiensi biaya jagan terbalik dengan hpp beda coa
                 * costing  dikeluarkan dari kategory hpp masuk ke efisiensi biaya
                 */
                array(
                    "comName" => "Jurnal",
                    "loop" => array(
                        //                        "3020010" => "(harga_bahan+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5)",//efisiensi biaya
                        //                        "5020050" => "harga_bahan",//supplies BOM
                        "3020010" => "(nilai_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5)",//efisiensi biaya
                        "5020050" => "nilai_bom",//supplies BOM
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
                        //                        "3020010" => "(harga_bahan+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5)",//efisiensi biaya
                        //                        "5020050" => "harga_bahan",//supplies BOM
                        "3020010" => "(nilai_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5)",//efisiensi biaya
                        "5020050" => "nilai_bom",//supplies BOM
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
                // pembantu efisiensi bom (cost) dari jurnal ke 3
                array(
                    "comName" => "RekeningPembantuEfisiensiBiayaMain",
                    "loop" => array(
                        //                        "3020010" => "harga_bahan",//supplies BOM
                        "3020010" => "nilai_bom",//supplies BOM
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern2_id" => ".6",
                        "extern2_nama" => ".bahan baku",
                        //                            "extern_id" => "efisiensiID_1_coa",
                        //                            "extern_id" => "costID_1",
                        //                            "extern_nama" => "efisiensiName_1_coa",
                        "extern_id" => ".6",
                        "extern_nama" => ".bahan baku",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),

                array(
                    "comName" => "RekeningPembantuEfisiensiBiayaMain",
                    "loop" => array(
                        "3020010" => "costNilai_1",//efisiensi biaya
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern2_id" => "costID_1",
                        "extern2_nama" => "costName_1",
                        //                            "extern_id" => "efisiensiID_1_coa",
                        "extern_id" => "costID_1",
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
                        "3020010" => "costNilai_2",//efisiensi biaya
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        //                            "extern_id" => "efisiensiID_2_coa",
                        "extern_id" => "costID_2",
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
                        "3020010" => "costNilai_3",//efisiensi biaya
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        //                            "extern_id" => "efisiensiID_3_coa",
                        "extern_id" => "costID_3",
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
                        "3020010" => "costNilai_4",//efisiensi biaya
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        //                            "extern_id" => "efisiensiID_4_coa",
                        "extern_id" => "costID_4",
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
                        "3020010" => "costNilai_5",//efisiensi biaya
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        //                            "extern_id" => "efisiensiID_5_coa",
                        "extern_id" => "costID_5",
                        "extern_nama" => "efisiensiName_5_coa",
                        "extern2_id" => "costID_5",
                        "extern2_nama" => "costName_5",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),

                // pembantu efisiensi per-fase, per-produk (bom)
                array(
                    "comName" => "RekeningPembantuEfisiensiBiayaFaseMain",
                    "loop" => array(
                        "3020010" => "nilai_bom",//supplies BOM
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => ".6",
                        "extern_nama" => ".bahan baku",
                        "extern2_id" => "fase_id",
                        "extern2_nama" => "fase_nama",
                        "produk_id" => "bomProdukID",
                        "produk_nama" => "bomProdukNama",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                // pembantu efisiensi per-fase, per-produk (riil)
                array(
                    "comName" => "RekeningPembantuEfisiensiBiayaFaseMain",
                    "loop" => array(
                        "3020010" => "-hpp",//supplies BOM
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => ".6",
                        "extern_nama" => ".bahan baku",
                        "extern2_id" => "fase_id",
                        "extern2_nama" => "fase_nama",
                        "produk_id" => "bomProdukID",
                        "produk_nama" => "bomProdukNama",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                array(
                    "comName" => "RekeningPembantuEfisiensiBiayaFaseMain",
                    "loop" => array(
                        "3020010" => "costNilai_1",//efisiensi biaya
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "costID_1",
                        "extern_nama" => "efisiensiName_1_coa",
                        "extern2_id" => "fase_id",
                        "extern2_nama" => "fase_nama",
                        "produk_id" => "bomProdukID",
                        "produk_nama" => "bomProdukNama",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                array(
                    "comName" => "RekeningPembantuEfisiensiBiayaFaseMain",
                    "loop" => array(
                        "3020010" => "costNilai_2",//efisiensi biaya
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "costID_2",
                        "extern_nama" => "efisiensiName_2_coa",
                        "extern2_id" => "fase_id",
                        "extern2_nama" => "fase_nama",
                        "produk_id" => "bomProdukID",
                        "produk_nama" => "bomProdukNama",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                array(
                    "comName" => "RekeningPembantuEfisiensiBiayaFaseMain",
                    "loop" => array(
                        "3020010" => "costNilai_3",//efisiensi biaya
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "costID_3",
                        "extern_nama" => "efisiensiName_3_coa",
                        "extern2_id" => "fase_id",
                        "extern2_nama" => "fase_nama",
                        "produk_id" => "bomProdukID",
                        "produk_nama" => "bomProdukNama",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                array(
                    "comName" => "RekeningPembantuEfisiensiBiayaFaseMain",
                    "loop" => array(
                        "3020010" => "costNilai_4",//efisiensi biaya
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "costID_4",
                        "extern_nama" => "efisiensiName_4_coa",
                        "extern2_id" => "fase_id",
                        "extern2_nama" => "fase_nama",
                        "produk_id" => "bomProdukID",
                        "produk_nama" => "bomProdukNama",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                array(
                    "comName" => "RekeningPembantuEfisiensiBiayaFaseMain",
                    "loop" => array(
                        "3020010" => "costNilai_5",//efisiensi biaya
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "costID_5",
                        "extern_nama" => "efisiensiName_5_coa",
                        "extern2_id" => "fase_id",
                        "extern2_nama" => "fase_nama",
                        "produk_id" => "bomProdukID",
                        "produk_nama" => "bomProdukNama",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
            ),
            "detail" => array(
                // mengurangi supplies riil yang diambil
                array(
                    "comName" => "RekeningPembantuSupplies",
                    "loop" => array(
                        "1010030010" => "-sub_hpp",//persediaan supplies
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
                // pembantu hasil produksi sesuai bom (ke gudang sendiri, masuk)
                array(
                    //                    "comName" => "RekeningPembantuProduk",
                    "comName" => "RekeningPembantuSupplies",
                    "loop" => array(
                        //                        "1010030070" => "sub_harga_bom",//persediaan produk rakitan
                        "1010030010" => "sub_harga",//persediaan produk rakitan
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "id",
                        "extern_nama" => "nama",
                        "produk_qty" => "jml",
                        "produk_nilai" => "harga",
                        "gudang_id" => "gudangID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),
                // pembantu hasil produksi sesuai bom (ke gudang sendiri, keluar)
                array(
                    //                    "comName" => "RekeningPembantuProduk",
                    "comName" => "RekeningPembantuSupplies",
                    "loop" => array(
                        //                        "1010030070" => "sub_harga_bom",//persediaan produk rakitan
                        "1010030010" => "-sub_harga",//persediaan produk rakitan
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "id",
                        "extern_nama" => "nama",
                        "produk_qty" => "-jml",
                        "produk_nilai" => "harga",
                        "gudang_id" => "gudangID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),
                // pembantu hasil produksi sesuai bom (ke gudang target, masuk)
                array(
                    //                    "comName" => "RekeningPembantuProduk",
                    "comName" => "RekeningPembantuSupplies",
                    "loop" => array(
                        //                        "1010030070" => "sub_harga_bom",//persediaan produk rakitan
                        "1010030010" => "sub_harga",//persediaan produk rakitan
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "id",
                        "extern_nama" => "nama",
                        "produk_qty" => "jml",
                        "produk_nilai" => "harga",
                        "gudang_id" => "gudang_target_id",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                        // "transaksi_no" => ".777777",
                        "kode_produksi" => "kode_produksi",
                    ),
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),


                // pembantu efisiensi bahan baku (ambil riil) => debet
                array(
                    "comName" => "RekeningPembantuEfisiensiBiaya",
                    "loop" => array(
                        "3020010" => "-sub_hpp",//persediaan supplies proses yang diambil
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "id",
                        "extern_nama" => "nama",
                        "extern2_id" => ".6",
                        "extern2_nama" => ".bahan baku",
                        "produk_qty" => "-jml",
                        "produk_nilai" => "hpp",
                        "gudang_id" => "gudangID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "rsltItems",
                    "srcRawGateName" => "rsltItems",
                ),
                // pembantu efisiensi bahan baku (bom) => kredit
                array(
                    "comName" => "RekeningPembantuEfisiensiBiaya",
                    "loop" => array(
                        //                        "3020010" => "sub_nilai_supplies",//bahan baku dengan nilai bom masih single produk belum suport multi, jik sudah suport multi pakai yang ke 2
                        "3020010" => "sub_nilai_bom",//bahan baku dengan nilai bom masih single produk belum suport multi, jik sudah suport multi pakai yang ke 2
                        // "3020010" => "supplies_bom",//bahan baku dengan nilai bom
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "id",
                        "extern_nama" => "nama",
                        "extern2_id" => ".6",
                        "extern2_nama" => ".bahan baku",
                        "produk_qty" => "jml",
                        //                        "produk_nilai" => "nilai_supplies",
                        "produk_nilai" => "nilai_bom",
                        "gudang_id" => "gudangID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "items2_sum",
                    "srcRawGateName" => "items2_sum",
                ),
                //pembantu hpp produksi bahan baku in
                array(
                    "comName" => "RekeningPembantuBiayaKomposisiProduksi",
                    "loop" => array(
                        //                        "5020050" => "-sub_nilai_supplies", // isi loop adalah overhead,tenaga kerja,biaya kirim
                        "5020050" => "-sub_nilai_bom", // isi loop adalah overhead,tenaga kerja,biaya kirim
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "id", // ID supplies
                        "extern_nama" => "nama", // NAME supplies
                        "extern2_id" => ".6", // ID biaya
                        "extern2_nama" => ".bahan baku", // label biaya
                        "jenis" => "jenisTr",
                        "produk_qty" => "-jml",
                        //                        "produk_nilai" => "nilai_supplies",
                        "produk_nilai" => "nilai_bom",
                        "gudang_id" => "gudangID",
                    ),
                    "srcGateName" => "items2_sum",
                    "srcRawGateName" => "items2_sum",
                ),
                //bahan baku out
                array(
                    "comName" => "RekeningPembantuBiayaKomposisiProduksi",
                    "loop" => array(
                        //                        "5020050" => "sub_nilai_supplies", // isi loop adalah overhead,tenaga kerja,biaya kirim
                        "5020050" => "sub_nilai_bom", // isi loop adalah overhead,tenaga kerja,biaya kirim
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "id", // ID supplies
                        "extern_nama" => "nama", // NAME supplies
                        "extern2_id" => ".6", // ID biaya
                        "extern2_nama" => ".bahan baku", // label biaya
                        "jenis" => "jenisTr",
                        "produk_qty" => "jml",
                        //                        "produk_nilai" => "nilai_supplies",
                        "produk_nilai" => "nilai_bom",
                        "gudang_id" => "gudangID",
                    ),
                    "srcGateName" => "items2_sum",
                    "srcRawGateName" => "items2_sum",
                ),


                // pembantu efisiensi bahan baku per-fase, per-bom (riil) => debet
                array(
                    "comName" => "RekeningPembantuEfisiensiBiayaFase",
                    "loop" => array(
                        "3020010" => "-sub_hpp",//persediaan supplies proses yang diambil
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "bomProdukID",
                        "extern_nama" => "bomProdukNama",
                        "extern2_id" => ".6",
                        "extern2_nama" => ".bahan baku",
                        "extern3_id" => "fase_id",
                        "extern3_nama" => "fase_nama",
                        "produk_id" => "id",//supplies
                        "produk_nama" => "nama",
                        "produk_qty" => "-jml",
                        "produk_nilai" => "hpp",
                        "gudang_id" => "gudangID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "rsltItems",
                    "srcRawGateName" => "rsltItems",
                ),
                // pembantu efisiensi bahan baku per-fase, per-bom(bom) => kredit
                array(
                    "comName" => "RekeningPembantuEfisiensiBiayaFase",
                    "loop" => array(
                        //                        "3020010" => "sub_nilai_supplies",//bahan baku dengan nilai bom masih single produk belum suport multi, jik sudah suport multi pakai yang ke 2
                        "3020010" => "sub_nilai_bom",//bahan baku dengan nilai bom masih single produk belum suport multi, jik sudah suport multi pakai yang ke 2
                        // "3020010" => "supplies_bom",//bahan baku dengan nilai bom
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "bomProdukID",
                        "extern_nama" => "bomProdukNama",
                        "extern2_id" => ".6",
                        "extern2_nama" => ".bahan baku",
                        "extern3_id" => "fase_id",
                        "extern3_nama" => "fase_nama",
                        "produk_id" => "id",//supplies
                        "produk_nama" => "nama",

                        "produk_qty" => "jml",
                        //                        "produk_nilai" => "nilai_supplies",
                        "produk_nilai" => "nilai_bom",
                        "gudang_id" => "gudangID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "items2_sum",
                    "srcRawGateName" => "items2_sum",
                ),
                // pembantu efisiensi biaya per-fase, per-bom(bom) => kredit
                array(
                    "comName" => "RekeningPembantuEfisiensiBiayaFase",
                    "loop" => array(
                        "3020010" => "sub_nilai",//biaya dengan nilai bom masih single produk belum suport multi, jik sudah suport multi pakai yang ke 2
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        //                        "extern_id" => "bomProdukID",
                        //                        "extern_nama" => "bomProdukNama",
                        "extern_id" => "produk_id",
                        "extern_nama" => "produk_nama",
                        "extern2_id" => "cat_id",
                        "extern2_nama" => "cat_nama",
                        "extern3_id" => "fase_id",
                        "extern3_nama" => "fase_nama",
                        "produk_id" => "produk_dasar_id",//supplies
                        "produk_nama" => "produk_dasar_nama",

                        "produk_qty" => "jml",
                        "produk_nilai" => "nilai",
                        "gudang_id" => "gudangID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "items3_sum",
                    "srcRawGateName" => "items3_sum",
                ),


                //pembantu raw produksi detail per-fase, per-bom
                array(
                    "comName" => "RekeningPembantuRaw",
                    "loop" => array(
                        "1010030010" => "sub_harga",//rekening pembelian untuk keperluan lap
                    ),
                    /*
                     * untuk gerbang coa nantinya dibuat relative ya
                     */
                    "static" => array(
                        "toko_id" => ".0",
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudang_source_id",
                        "extern_id" => ".1010030010",// rek bahan baku
                        "extern_nama" => "",
                        "extern2_id" => "bomProdukID",//bom id produksi
                        "extern2_nama" => "bomProdukNama",
                        "extern3_id" => "fase_id",//fase id produksi
                        "extern3_nama" => "",
                        "extern4_id" => "",
                        "extern4_nama" => "",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                        "produk_id" => "id",//hasil per-fase
                        "produk_nama" => "nama",
                        "jml" => "jml",
                        "harga" => "harga",
                        "hpp" => "nilai",
                        "oleh_id" => "olehID",
                        "oleh_nama" => "olehNama",
                        "pihak_id" => "pihakID",
                        "pihak_nama" => "pihakName",
                        "oleh_top_id" => "sellerID",
                        "oleh_top_nama" => "sellerName",
                        "kode_produksi" => "kode_produksi",
                    ),
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),

            ),
        ),
        "postProcessor" => array(
            "master" => array(
                array(
                    "comName" => "ProdukSerialNumber",
                    "loop" => array(),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "serial_number" => "kode_produksi",
                        "produk_id" => "bomProdukID",
                        "produk_nama" => "bomProdukNama",
                        "oleh_id" => "olehID",
                        "oleh_nama" => "olehName",
                        "transaksi_id" => ".0",
                        "nomer" => ".0",
                        "fase_id" => "fase_id",

                    ),
                    "reversable" => true,
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
            ),
            "detail" => array(
                //<editor-fold desc="mengurangi stok supplies dalam proses">
                array(
                    "comName" => "LockerStockSupplies",
                    "loop" => array(),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "jenis" => ".supplies",
                        "state" => ".active",
                        "jumlah" => "-jml",
                        "produk_id" => "produk_dasar_id",
                        "nama" => "produk_nama",
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
                        "state" => ".assembled",
                        "jumlah" => "jml",
                        "produk_id" => "produk_dasar_id",
                        "nama" => "produk_nama",
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
                    "comName" => "LockerStockMutasiSupplies",
                    "loop" => array(),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "produk_dasar_id",
                        "extern_nama" => "produk_nama",
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

                //<editor-fold desc="Com-fifo average dan fifo murni ke gudang target bom">
                array(
                    "comName" => "FifoAverage",
                    "loop" => array(),
                    "static" => array(
                        "jenis" => ".supplies",
                        "jml" => "jml",
                        "produk_id" => "produk_dasar_id",
                        "hpp" => "harga",
                        "jml_nilai" => "sub_harga",
                        "nama" => "produk_dasar_nama",
                        "cabang_id" => "placeID",
                        //                        "gudang_id" => "gudangID",
                        "gudang_id" => "gudang_target_id",
                    ),
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),
                array(
                    "comName" => "FifoSupplies",//hasil produksi per-fase masih berupa bahan baku
                    "loop" => array(),
                    "static" => array(
                        "unit" => "jml",
                        "produk_id" => "produk_dasar_id",
                        "produk_nama" => "produk_dasar_nama",
                        "hpp" => "harga",
                        "jml_nilai" => "sub_harga",
                        "cabang_id" => "placeID",
                        //                        "gudang_id" => "gudangID",
                        "gudang_id" => "gudang_target_id",
                        "hpp_riil" => "harga_bom",
                        "jml_nilai_riil" => "sub_harga_bom",
                        "ppv_riil" => .0,
                        "ppv_nilai_riil" => .0,
                    ),
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),
                //</editor-fold>

                array(
                    "comName" => "LockerStockSupplies",//hasil produksi per-fase masih berupa bahan baku
                    "loop" => array(),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "jenis" => ".supplies",
                        "state" => ".active",
                        "jumlah" => "qty",
                        "produk_id" => "produk_dasar_id",
                        "nama" => "produk_dasar_nama",
                        "satuan" => "satuan",
                        "oleh_id" => ".0",
                        "oleh_nama" => ".0",
                        "transaksi_id" => ".0",
                        "nomer" => ".0",
                        //                        "gudang_id" => "gudangID",
                        "gudang_id" => "gudang_target_id",
                    ),
                    "reversable" => true,
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),
                // locker stok mutasi
                array(
                    "comName" => "LockerStockMutasiSupplies",
                    "loop" => array(),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "produk_dasar_id",
                        "extern_nama" => "produk_dasar_nama",
                        "qty_debet" => "qty",
                        "produk_nilai" => "hpp",
                        //                        "gudang_id" => "gudangID",
                        "gudang_id" => "gudang_target_id",
                        "jenis" => "jenisTr",
                    ),
                    "reversable" => true,
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),
                //tambahan untuk databarcode produksi
                array(
                    "comName" => "ManufacturIdentity",
                    "loop" => array(),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "cabang_nama" => "cabangName",
                        "produk_id" => "produk_id",
                        "produk_nama" => "produk_nama",
                        "produk_fase_id" => "produk_dasar_id",
                        "produk_fase_nama" => "produk_dasar_nama",
                        "qty_fase" => "jml",
                        "harga_fase" => "harga",
                        "gudang_id" => "gudangID",
                        "gudang_nama" => "gudangName",
                        "toko_id" => ".0",
                        "oleh_id" => "olehID",
                        "oleh_nama" => "olehName",
                        "saldo_qty" => "jml",
                        "fase_id" => "fase_id",
                        "fase_nama" => "fase_nama",
                        "jenis_tr" => "jenisTr",
                        "kode_produksi" => "kode_produksi",
                    ),
                    "reversable" => true,
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),

            ),
        ),
        //-----
        "last_preProcessor" => array(
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
                        "jenisTr" => "jenisTrMaster",
                    ),
                    "resultParams" => array(
                        //                        "items" => array(
                        //                            "harga" => "hpp",
                        //                            "hpp" => "hpp",
                        //                        ),
                        //                        "items2_sum" => array(
                        //                            "harga" => "hpp",
                        //                            "hpp" => "hpp",
                        //                        ),
                    ),
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),
                array(
                    "comName" => "FifoSupplies",
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
                        //                        "rsltItems" => array( // berisi bahan/supplies yang dipakai
                        //                            "id" => "bahan_id",
                        //                            "nama" => "nama",
                        //                            "harga" => "hpp",
                        //                            "hpp" => "hpp",
                        //                            "jml" => "diambil",
                        //                            "qty" => "diambil",
                        //                            "subtotal" => "subHPP",
                        //                            "hpp_riil" => "hpp_riil",
                        //                            "ppv_riil" => "ppv_riil",
                        //                            "subHpp_riil" => "subHpp_riil",
                        //                            "subPpv_riil" => "subPpv_riil",
                        //                            "ppn_in" => "ppn_in",
                        //                            "ppn_in_nilai" => "ppn_in_nilai",
                        //                            "suppliers_id" => "suppliers_id",
                        //                            "suppliers_nama" => "suppliers_nama",
                        //                        ),
                        //                        "rsltItems2" => array( // berisi produk hasil assembling
                        //                            "id" => "produk_id",
                        //                            "nama" => "produk_nama",
                        //                            "name" => "produk_nama",
                        //                            "harga" => "hpp",
                        //                            "hpp" => "hpp",
                        //                            "jml" => "jml",
                        //                            "qty" => "jml",
                        //                            "subtotal" => "subHPP",
                        //                        ),
                    ),
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),
            ),
        ),
        "last_components" => array(
            "master" => array(
                // jurnal ke 1 reguler produksi/bom
                /*
                 * realtive costnem masuk ke COA Hpp produk
                 * costing  masuk ke kategory
                 */
                array(
                    "comName" => "Jurnal",
                    "loop" => array(
                        "1010030010" => "-(nilai_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5)",//persediaan bahan baku
                        "1010030070" => "(nilai_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5)",//persediaan produk
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
                        "1010030010" => "-(nilai_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5)",//persediaan bahan baku
                        "1010030070" => "(nilai_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5)",//persediaan produk
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
                // pembantu hasil produksi sesuai bom (keluar)
                array(
                    //                    "comName" => "RekeningPembantuProduk",
                    "comName" => "RekeningPembantuSupplies",
                    "loop" => array(
                        //                        "1010030030" => "sub_harga_bom",//persediaan produk
                        "1010030010" => "-sub_harga",//persediaan bahan baku
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "id",
                        "extern_nama" => "nama",
                        "produk_qty" => "-jml",
                        "produk_nilai" => "harga",
                        "gudang_id" => "gudangID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                        "kode_produksi" => "kode_produksi",
                    ),
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),
                // pembantu hasil produksi sesuai bom (masuk) ke produk
                array(
                    "comName" => "RekeningPembantuProduk",
                    //                    "comName" => "RekeningPembantuSupplies",
                    "loop" => array(
                        "1010030070" => "sub_harga",//persediaan produk

                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        //                        "extern_id" => "id",
                        //                        "extern_nama" => "nama",
                        "extern_id" => "bomProdukID",
                        "extern_nama" => "bomProdukNama",
                        "produk_qty" => "jml",
                        "produk_nilai" => "harga",
                        //                        "gudang_id" => "gudangID",
                        "gudang_id" => "gudangID_produk",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                        "kode_produksi" => "kode_produksi",
                    ),
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),
            ),
        ),
        "last_postProcessor" => array(
            "master" => array(),
            "detail" => array(
                // bagian bahan baku
                array(
                    "comName" => "LockerStockSupplies",//hasil produksi per-fase masih berupa bahan baku
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

                // bagian produk jadi
                array(
                    "comName" => "FifoAverage",
                    "loop" => array(),
                    "static" => array(
                        "jenis" => ".produk",
                        "jml" => "jml",
                        "produk_id" => "bomProdukID",
                        "nama" => "bomProdukNama",
                        "hpp" => "harga",
                        "jml_nilai" => "sub_harga",
                        "cabang_id" => "placeID",
                        //                        "gudang_id" => "gudangID",
                        "gudang_id" => "gudangID_produk",
                    ),
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),
                array(
                    "comName" => "FifoProdukJadiRakitan",//hasil produksi per-fase masih berupa bahan baku
                    "loop" => array(),
                    "static" => array(
                        "unit" => "jml",
                        "produk_id" => "bomProdukID",
                        "produk_nama" => "bomProdukNama",
                        "hpp" => "harga",
                        "jml_nilai" => "sub_harga",
                        "cabang_id" => "placeID",
                        //                        "gudang_id" => "gudangID",
                        "gudang_id" => "gudangID_produk",
                        "hpp_riil" => "harga",
                        "jml_nilai_riil" => "sub_harga",
                        "ppv_riil" => .0,
                        "ppv_nilai_riil" => .0,
                        "kode_produksi" => "kode_produksi",
                    ),
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),

                array(
                    "comName" => "LockerStock",//hasil produksi per-fase masih berupa bahan baku
                    "loop" => array(),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "jenis" => ".produk rakitan",
                        "state" => ".active",
                        "jumlah" => "qty",
                        "produk_id" => "bomProdukID",
                        "nama" => "bomProdukNama",
                        "satuan" => "satuan",
                        "oleh_id" => ".0",
                        "oleh_nama" => ".0",
                        "transaksi_id" => ".0",
                        "nomer" => ".0",
                        //                        "gudang_id" => "gudangID",
                        "gudang_id" => "gudangID_produk",
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
                        "extern_id" => "bomProdukID",
                        "extern_nama" => "bomProdukNama",
                        "qty_debet" => "qty",
                        "produk_nilai" => "harga",
                        //                        "gudang_id" => "gudangID",
                        "gudang_id" => "gudangID_produk",
                        "jenis" => "jenisTr",
                    ),
                    "reversable" => true,
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),
                //manufactur identity tidak dibuatkan karena sudah tercover dari postproc reguler fase

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
        "formatNotaReject" => "stepCode|placeID|gudangID",
    ),

    //sample project
    "580" => array(
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
            // "776" => array(
            //     "master" => array(),
            //     "detail" => array(
            //         //                    array(
            //         //                        "comName" => "FifoAverageSuppliesProsesAssembly",
            //         //                        "loop" => array(),
            //         //                        "static" => array(
            //         //                            "cabang_id" => "placeID",
            //         //                            "extern_id" => "id",
            //         //                            "extern_nama" => "name",
            //         //                            "produk_qty" => "qty",
            //         //                            "gudang_id" => "gudangID",
            //         //                            "jenisTr" => "jenisTrMaster",
            //         //                        ),
            //         //                        "resultParams" => array(
            //         //                            "items" => array(
            //         //                                "harga" => "hpp",
            //         //                                "hpp" => "hpp",
            //         //                            ),
            //         //                            "items2_sum" => array(
            //         //                                "harga" => "hpp",
            //         //                                "hpp" => "hpp",
            //         //                            ),
            //         //                        ),
            //         //                        "srcGateName" => "items",
            //         //                        "srcRawGateName" => "items",
            //         //                    ),
            //         array(
            //             "comName" => "FifoSuppliesProsesAssembly",
            //             "loop" => array(),
            //             "static" => array(
            //                 "cabang_id" => "placeID",
            //                 "extern_id" => "id",
            //                 "extern_nama" => "name",
            //                 "produk_qty" => "qty",
            //                 "gudang_id" => "gudangID",
            //                 "jenisTr" => "jenisTrMaster",
            //             ),
            //             "resultParams" => array(
            //                 "rsltItems" => array( // berisi bahan/supplies yang dipakai
            //                     "id" => "bahan_id",
            //                     "nama" => "nama",
            //                     "harga" => "hpp",
            //                     "hpp" => "hpp",
            //                     "jml" => "diambil",
            //                     "qty" => "diambil",
            //                     "subtotal" => "subHPP",
            //                 ),
            //                 "rsltItems2" => array( // berisi produk hasil assembling
            //                     "id" => "produk_id",
            //                     "nama" => "produk_nama",
            //                     "name" => "produk_nama",
            //                     "harga" => "hpp",
            //                     "hpp" => "hpp",
            //                     "jml" => "jml",
            //                     "qty" => "jml",
            //                     "subtotal" => "subHPP",
            //                 ),
            //             ),
            //             "srcGateName" => "items",
            //             "srcRawGateName" => "items",
            //         ),
            //     ),
            // ),
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

            //            "776" => array(
            //                "master" => array(
            //                    // jurnal reguler produksi/bom
            //                    array(
            //                        "comName" => "Jurnal",
            //                        "loop" => array(
            //                            "persediaan supplies proses" => "-hpp",
            //                            "persediaan produk rakitan" => "hpp+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
            //                            "{costName_1}" => "-costNilai_1", // ex:overhead
            //                            "{costName_2}" => "-costNilai_2", // ex:biaya_kirim
            //                            "{costName_3}" => "-costNilai_3", // ex:tenaga_kerja
            //                            "{costName_4}" => "-costNilai_4", // ex:tenaga_kerja
            //                            "{costName_5}" => "-costNilai_5", // ex:tenaga_kerja
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
            //                            "persediaan supplies proses" => "-hpp",
            //                            "persediaan produk rakitan" => "hpp+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
            //                            "{costName_1}" => "-costNilai_1", // ex:overhead
            //                            "{costName_2}" => "-costNilai_2", // ex:biaya_kirim
            //                            "{costName_3}" => "-costNilai_3", // ex:tenaga_kerja
            //                            "{costName_4}" => "-costNilai_4", // ex:tenaga_kerja
            //                            "{costName_5}" => "-costNilai_5", // ex:tenaga_kerja
            //                        ),
            //                        "static" => array(
            //                            "cabang_id" => "placeID",
            //                            "jenis" => "jenisTr",
            //                            "transaksi_no" => "nomer",
            //                        ),
            //                        "srcGateName" => "main",
            //                        "srcRawGateName" => "main",
            //                    ),
            //
            //                    // jurnal main cost vs efisiensi bom
            //                    array(
            //                        "comName" => "Jurnal",
            //                        "loop" => array(
            //                            "efisiensi biaya" => "(costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5)",
            //                            "{costName_1}" => "costNilai_1", // ex:overhead
            //                            "{costName_2}" => "costNilai_2", // ex:biaya_kirim
            //                            "{costName_3}" => "costNilai_3", // ex:tenaga_kerja
            //                            "{costName_4}" => "costNilai_4", // ex:tenaga_kerja
            //                            "{costName_5}" => "costNilai_5", // ex:tenaga_kerja
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
            //                            "efisiensi biaya" => "(costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5)",
            //                            "{costName_1}" => "costNilai_1", // ex:overhead
            //                            "{costName_2}" => "costNilai_2", // ex:biaya_kirim
            //                            "{costName_3}" => "costNilai_3", // ex:tenaga_kerja
            //                            "{costName_4}" => "costNilai_4", // ex:tenaga_kerja
            //                            "{costName_5}" => "costNilai_5", // ex:tenaga_kerja
            //                        ),
            //                        "static" => array(
            //                            "cabang_id" => "placeID",
            //                            "jenis" => "jenisTr",
            //                            "transaksi_no" => "nomer",
            //                        ),
            //                        "srcGateName" => "main",
            //                        "srcRawGateName" => "main",
            //                    ),
            //                    // pembantu efisiensi bom (cost)
            //                    array(
            //                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
            //                        "loop" => array(
            //                            "efisiensi biaya" => "costNilai_1",
            //                        ),
            //                        "static" => array(
            //                            "cabang_id" => "placeID",
            //                            "extern_id" => "costID_1",
            //                            "extern_nama" => "costName_1",
            //                            "jenis" => "jenisTr",
            //                            "transaksi_no" => "nomer",
            //                        ),
            //                        "srcGateName" => "main",
            //                        "srcRawGateName" => "main",
            //                    ),
            //                    array(
            //                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
            //                        "loop" => array(
            //                            "efisiensi biaya" => "costNilai_2",
            //                        ),
            //                        "static" => array(
            //                            "cabang_id" => "placeID",
            //                            "extern_id" => "costID_2",
            //                            "extern_nama" => "costName_2",
            //                            "jenis" => "jenisTr",
            //                            "transaksi_no" => "nomer",
            //                        ),
            //                        "srcGateName" => "main",
            //                        "srcRawGateName" => "main",
            //                    ),
            //                    array(
            //                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
            //                        "loop" => array(
            //                            "efisiensi biaya" => "costNilai_3",
            //                        ),
            //                        "static" => array(
            //                            "cabang_id" => "placeID",
            //                            "extern_id" => "costID_3",
            //                            "extern_nama" => "costName_3",
            //                            "jenis" => "jenisTr",
            //                            "transaksi_no" => "nomer",
            //                        ),
            //                        "srcGateName" => "main",
            //                        "srcRawGateName" => "main",
            //                    ),
            //                    array(
            //                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
            //                        "loop" => array(
            //                            "efisiensi biaya" => "costNilai_4",
            //                        ),
            //                        "static" => array(
            //                            "cabang_id" => "placeID",
            //                            "extern_id" => "costID_4",
            //                            "extern_nama" => "costName_4",
            //                            "jenis" => "jenisTr",
            //                            "transaksi_no" => "nomer",
            //                        ),
            //                        "srcGateName" => "main",
            //                        "srcRawGateName" => "main",
            //                    ),
            //                    array(
            //                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
            //                        "loop" => array(
            //                            "efisiensi biaya" => "costNilai_5",
            //                        ),
            //                        "static" => array(
            //                            "cabang_id" => "placeID",
            //                            "extern_id" => "costID_5",
            //                            "extern_nama" => "costName_5",
            //                            "jenis" => "jenisTr",
            //                            "transaksi_no" => "nomer",
            //                        ),
            //                        "srcGateName" => "main",
            //                        "srcRawGateName" => "main",
            //                    ),
            //                    //
            //                ),
            //                "detail" => array(
            //                    //<editor-fold desc="Com-pembantu supplies">
            //                    array(
            //                        "comName" => "RekeningPembantuSuppliesProses",
            //                        "loop" => array(
            //                            "persediaan supplies proses" => "-sub_hpp",
            //                        ),
            //                        "static" => array(
            //                            "cabang_id" => "placeID",
            //                            "extern_id" => "id",
            //                            "extern_nama" => "nama",
            //                            "produk_qty" => "-jml",
            //                            "produk_nilai" => "hpp",
            //                            "gudang_id" => "gudangID",
            //                            "jenis" => "jenisTr",
            //                            "transaksi_no" => "nomer",
            //                        ),
            //                        "srcGateName" => "rsltItems",
            //                        "srcRawGateName" => "rsltItems",
            //                    ),
            //                    //</editor-fold>
            //
            //                    //<editor-fold desc="Com-pembantu produk">
            //                    array(
            //                        "comName" => "RekeningPembantuProduk",
            //                        "loop" => array(
            //                            "persediaan produk rakitan" => "sub_hpp+sub_costNilai_1+sub_costNilai_2+sub_costNilai_3+sub_costNilai_4+sub_costNilai_5",
            //                        ),
            //                        "static" => array(
            //                            "cabang_id" => "placeID",
            //                            "extern_id" => "id",
            //                            "extern_nama" => "nama",
            //                            "produk_qty" => "jml",
            //                            "produk_nilai" => "hpp+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
            //                            "gudang_id" => "gudangID",
            //                            "jenis" => "jenisTr",
            //                            "transaksi_no" => "nomer",
            //                        ),
            //                        "srcGateName" => "rsltItems2",
            //                        "srcRawGateName" => "rsltItems2",
            //                    ),
            //                    //</editor-fold>
            //
            //
            //                    //<editor-fold desc="Com-pembantu overhead,tenaga kerja,biaya kirim">
            ////                    array(
            ////                        "comName" => "RekeningPembantuBiayaKomposisiProduksi",
            ////                        "loop" => array(
            ////                            "{costName_1}" => "-sub_costNilai_1", // isi loop adalah overhead,tenaga kerja,biaya kirim
            ////                        ),
            ////                        "static" => array(
            ////                            "cabang_id" => "placeID",
            ////                            "extern_id" => "id", // ID pembantunya adalah produk hasil produksi
            ////                            "extern_nama" => "nama", // NAME pembantunya adalah produk hasil produksi
            ////                            "jenis" => "jenisTr",
            ////                        ),
            ////                        "srcGateName" => "rsltItems2",
            ////                        "srcRawGateName" => "rsltItems2",
            ////                    ),
            ////                    array(
            ////                        "comName" => "RekeningPembantuBiayaKomposisiProduksi",
            ////                        "loop" => array(
            ////                            "{costName_2}" => "-sub_costNilai_2", // isi loop adalah overhead,tenaga kerja,biaya kirim
            ////                        ),
            ////                        "static" => array(
            ////                            "cabang_id" => "placeID",
            ////                            "extern_id" => "id", // ID pembantunya adalah produk hasil produksi
            ////                            "extern_nama" => "nama", // NAME pembantunya adalah produk hasil produksi
            ////                            "jenis" => "jenisTr",
            ////                        ),
            ////                        "srcGateName" => "rsltItems2",
            ////                        "srcRawGateName" => "rsltItems2",
            ////                    ),
            ////                    array(
            ////                        "comName" => "RekeningPembantuBiayaKomposisiProduksi",
            ////                        "loop" => array(
            ////                            "{costName_3}" => "-sub_costNilai_3", // isi loop adalah overhead,tenaga kerja,biaya kirim
            ////                        ),
            ////                        "static" => array(
            ////                            "cabang_id" => "placeID",
            ////                            "extern_id" => "id", // ID pembantunya adalah produk hasil produksi
            ////                            "extern_nama" => "nama", // NAME pembantunya adalah produk hasil produksi
            ////                            "jenis" => "jenisTr",
            ////                        ),
            ////                        "srcGateName" => "rsltItems2",
            ////                        "srcRawGateName" => "rsltItems2",
            ////                    ),
            ////                    array(
            ////                        "comName" => "RekeningPembantuBiayaKomposisiProduksi",
            ////                        "loop" => array(
            ////                            "{costName_4}" => "-sub_costNilai_4", // isi loop adalah overhead,tenaga kerja,biaya kirim
            ////                        ),
            ////                        "static" => array(
            ////                            "cabang_id" => "placeID",
            ////                            "extern_id" => "id", // ID pembantunya adalah produk hasil produksi
            ////                            "extern_nama" => "nama", // NAME pembantunya adalah produk hasil produksi
            ////                            "jenis" => "jenisTr",
            ////                        ),
            ////                        "srcGateName" => "rsltItems2",
            ////                        "srcRawGateName" => "rsltItems2",
            ////                    ),
            ////                    array(
            ////                        "comName" => "RekeningPembantuBiayaKomposisiProduksi",
            ////                        "loop" => array(
            ////                            "{costName_5}" => "-sub_costNilai_5", // isi loop adalah overhead,tenaga kerja,biaya kirim
            ////                        ),
            ////                        "static" => array(
            ////                            "cabang_id" => "placeID",
            ////                            "extern_id" => "id", // ID pembantunya adalah produk hasil produksi
            ////                            "extern_nama" => "nama", // NAME pembantunya adalah produk hasil produksi
            ////                            "jenis" => "jenisTr",
            ////                        ),
            ////                        "srcGateName" => "rsltItems2",
            ////                        "srcRawGateName" => "rsltItems2",
            ////                    ),
            ////
            ////
            ////
            ////                    array(
            ////                        "comName" => "RekeningPembantuBiayaKomposisiProduksi",
            ////                        "loop" => array(
            ////                            "{costName_1}" => "sub_costNilai_1", // isi loop adalah overhead,tenaga kerja,biaya kirim
            ////                        ),
            ////                        "static" => array(
            ////                            "cabang_id" => "placeID",
            ////                            "extern_id" => "id", // ID pembantunya adalah produk hasil produksi
            ////                            "extern_nama" => "nama", // NAME pembantunya adalah produk hasil produksi
            ////                            "jenis" => "jenisTr",
            ////                        ),
            ////                        "srcGateName" => "rsltItems2",
            ////                        "srcRawGateName" => "rsltItems2",
            ////                    ),
            ////                    array(
            ////                        "comName" => "RekeningPembantuBiayaKomposisiProduksi",
            ////                        "loop" => array(
            ////                            "{costName_2}" => "sub_costNilai_2", // isi loop adalah overhead,tenaga kerja,biaya kirim
            ////                        ),
            ////                        "static" => array(
            ////                            "cabang_id" => "placeID",
            ////                            "extern_id" => "id", // ID pembantunya adalah produk hasil produksi
            ////                            "extern_nama" => "nama", // NAME pembantunya adalah produk hasil produksi
            ////                            "jenis" => "jenisTr",
            ////                        ),
            ////                        "srcGateName" => "rsltItems2",
            ////                        "srcRawGateName" => "rsltItems2",
            ////                    ),
            ////                    array(
            ////                        "comName" => "RekeningPembantuBiayaKomposisiProduksi",
            ////                        "loop" => array(
            ////                            "{costName_3}" => "sub_costNilai_3", // isi loop adalah overhead,tenaga kerja,biaya kirim
            ////                        ),
            ////                        "static" => array(
            ////                            "cabang_id" => "placeID",
            ////                            "extern_id" => "id", // ID pembantunya adalah produk hasil produksi
            ////                            "extern_nama" => "nama", // NAME pembantunya adalah produk hasil produksi
            ////                            "jenis" => "jenisTr",
            ////                        ),
            ////                        "srcGateName" => "rsltItems2",
            ////                        "srcRawGateName" => "rsltItems2",
            ////                    ),
            ////                    array(
            ////                        "comName" => "RekeningPembantuBiayaKomposisiProduksi",
            ////                        "loop" => array(
            ////                            "{costName_4}" => "sub_costNilai_4", // isi loop adalah overhead,tenaga kerja,biaya kirim
            ////                        ),
            ////                        "static" => array(
            ////                            "cabang_id" => "placeID",
            ////                            "extern_id" => "id", // ID pembantunya adalah produk hasil produksi
            ////                            "extern_nama" => "nama", // NAME pembantunya adalah produk hasil produksi
            ////                            "jenis" => "jenisTr",
            ////                        ),
            ////                        "srcGateName" => "rsltItems2",
            ////                        "srcRawGateName" => "rsltItems2",
            ////                    ),
            ////                    array(
            ////                        "comName" => "RekeningPembantuBiayaKomposisiProduksi",
            ////                        "loop" => array(
            ////                            "{costName_5}" => "sub_costNilai_5", // isi loop adalah overhead,tenaga kerja,biaya kirim
            ////                        ),
            ////                        "static" => array(
            ////                            "cabang_id" => "placeID",
            ////                            "extern_id" => "id", // ID pembantunya adalah produk hasil produksi
            ////                            "extern_nama" => "nama", // NAME pembantunya adalah produk hasil produksi
            ////                            "jenis" => "jenisTr",
            ////                        ),
            ////                        "srcGateName" => "rsltItems2",
            ////                        "srcRawGateName" => "rsltItems2",
            ////                    ),
            ////
            ////                    array(
            ////                        "comName" => "RekeningPembantuEfisiensiBiaya",
            ////                        "loop" => array(
            ////                            "efisiensi biaya" => "sub_costNilai_1", // isi loop adalah overhead,tenaga kerja,biaya kirim
            ////                        ),
            ////                        "static" => array(
            ////                            "cabang_id" => "placeID",
            ////                            "extern_id" => "id", // ID pembantunya adalah produk hasil produksi
            ////                            "extern_nama" => "nama", // NAME pembantunya adalah produk hasil produksi
            ////                            "extern_id" => "costID_1",
            ////                            "extern_nama" => "costName_1",
            ////                            "jenis" => "jenisTr",
            ////                        ),
            ////                        "srcGateName" => "rsltItems2",
            ////                        "srcRawGateName" => "rsltItems2",
            ////                    ),
            ////                    array(
            ////                        "comName" => "RekeningPembantuEfisiensiBiaya",
            ////                        "loop" => array(
            ////                            "efisiensi biaya" => "sub_costNilai_2", // isi loop adalah overhead,tenaga kerja,biaya kirim
            ////                        ),
            ////                        "static" => array(
            ////                            "cabang_id" => "placeID",
            ////                            "extern_id" => "id", // ID pembantunya adalah produk hasil produksi
            ////                            "extern_nama" => "nama", // NAME pembantunya adalah produk hasil produksi
            ////                            "extern_id" => "costID_2",
            ////                            "extern_nama" => "costName_2",
            ////                            "jenis" => "jenisTr",
            ////                        ),
            ////                        "srcGateName" => "rsltItems2",
            ////                        "srcRawGateName" => "rsltItems2",
            ////                    ),
            ////                    array(
            ////                        "comName" => "RekeningPembantuEfisiensiBiaya",
            ////                        "loop" => array(
            ////                            "efisiensi biaya" => "sub_costNilai_3", // isi loop adalah overhead,tenaga kerja,biaya kirim
            ////                        ),
            ////                        "static" => array(
            ////                            "cabang_id" => "placeID",
            ////                            "extern_id" => "id", // ID pembantunya adalah produk hasil produksi
            ////                            "extern_nama" => "nama", // NAME pembantunya adalah produk hasil produksi
            ////                            "extern_id" => "costID_3",
            ////                            "extern_nama" => "costName_3",
            ////                            "jenis" => "jenisTr",
            ////                        ),
            ////                        "srcGateName" => "rsltItems2",
            ////                        "srcRawGateName" => "rsltItems2",
            ////                    ),
            ////                    array(
            ////                        "comName" => "RekeningPembantuEfisiensiBiaya",
            ////                        "loop" => array(
            ////                            "efisiensi biaya" => "sub_costNilai_4", // isi loop adalah overhead,tenaga kerja,biaya kirim
            ////                        ),
            ////                        "static" => array(
            ////                            "cabang_id" => "placeID",
            ////                            "extern_id" => "id", // ID pembantunya adalah produk hasil produksi
            ////                            "extern_nama" => "nama", // NAME pembantunya adalah produk hasil produksi
            ////                            "extern_id" => "costID_4",
            ////                            "extern_nama" => "costName_4",
            ////                            "jenis" => "jenisTr",
            ////                        ),
            ////                        "srcGateName" => "rsltItems2",
            ////                        "srcRawGateName" => "rsltItems2",
            ////                    ),
            ////                    array(
            ////                        "comName" => "RekeningPembantuEfisiensiBiaya",
            ////                        "loop" => array(
            ////                            "efisiensi biaya" => "sub_costNilai_5", // isi loop adalah overhead,tenaga kerja,biaya kirim
            ////                        ),
            ////                        "static" => array(
            ////                            "cabang_id" => "placeID",
            ////                            "extern_id" => "id", // ID pembantunya adalah produk hasil produksi
            ////                            "extern_nama" => "nama", // NAME pembantunya adalah produk hasil produksi
            ////                            "extern_id" => "costID_5",
            ////                            "extern_nama" => "costName_5",
            ////                            "jenis" => "jenisTr",
            ////                        ),
            ////                        "srcGateName" => "rsltItems2",
            ////                        "srcRawGateName" => "rsltItems2",
            ////                    ),
            //                    //</editor-fold>
            //                ),
            //            ),
        ),
        "postProcessor" => array(
            //            "776r" => array(
            //                "master" => array(
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
            //                            "nilai" => ".1",
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
            //                            "nilai" => ".1",
            //                        ),
            //                        "srcGateName" => "main",
            //                        "srcRawGateName" => "main",
            //                    ),
            //                ),
            //                "detail" => array(
            //                    array(
            //                        "comName" => "LockerStockSupplies",
            //                        "loop" => array(),
            //                        "static" => array(
            //                            "cabang_id" => "placeID",
            //                            "jenis" => ".supplies",
            //                            "state" => ".active",
            //                            "jumlah" => "-jml",
            //                            "produk_id" => "id",
            //                            "nama" => "nama",
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
            //                        "comName" => "LockerStockSupplies",
            //                        "loop" => array(),
            //                        "static" => array(
            //                            "cabang_id" => "placeID",
            //                            "jenis" => ".supplies",
            //                            "state" => ".hold",
            //                            "jumlah" => "jml",
            //                            "produk_id" => "id",
            //                            "nama" => "nama",
            //                            "satuan" => "satuan",
            //                            "oleh_id" => ".0",
            //                            "oleh_nama" => ".0",
            //                            "transaksi_id" => "transaksi_id",
            //                            "nomer" => "nomer",
            //                            "gudang_id" => "gudangID",
            //                        ),
            //                        "reversable" => true,
            //                        "srcGateName" => "items2_sum",
            //                        "srcRawGateName" => "items2_sum",
            //                    ),
            //                ),
            //            ),

            // "776" => array(
            //     "master" => array(
            //         // TRANSAKSI
            //         /*
            //          * tanda minus untuk posisi kredit
            //          */
            //         array(
            //             "comName" => "RekeningTransaksiDebet",
            //             "loop" => array(
            //                 //                            "{sourceJenis}" => "nilai_credit",
            //                 "776" => "nilai_credit",
            //             ),
            //             "static" => array(
            //                 "cabang_id" => "placeID",
            //                 "cabang_nama" => "placeName",
            //                 "gudang_id" => "gudangID",
            //                 "gudang_nama" => "gudangName",
            //                 "rekening_nama" => "sourceJenisLabel",
            //                 "produk_qty" => "qty",
            //                 "produk_nilai" => "hpp",
            //                 "harga_bruto" => "harga",
            //                 "harga_netto" => "nett1",
            //                 "diskon_nilai" => "disc",
            //                 "premi_nilai" => "premi",
            //                 "ongkir_nilai" => "shipping_service",
            //                 "extern_id" => "transaksi_id",
            //                 "extern_nama" => "nomer",
            //                 "satuan" => "satuan",
            //                 "oleh_id" => "olehID",
            //                 "oleh_name" => "olehName",
            //                 "seller_id" => "sellerID",
            //                 "seller_nama" => "sellerName",
            //                 "master_id" => "transaksi_id",
            //                 "master_jenis" => "jenisTrMaster",
            //                 "jenis" => "sourceJenis",
            //                 "step_current" => "step_number",
            //                 "step_number" => "step_current",
            //                 "next_step_num" => "targetJenisNextStep",
            //                 "referenceID" => "referenceID",
            //                 //------
            //                 "_stepCode_placeID" => "_stepCode_placeID",
            //                 "_stepCode_olehID" => "_stepCode_olehID",
            //                 "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
            //                 "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
            //                 "_stepCode_customerID" => "_stepCode_customerID",
            //                 "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
            //                 "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
            //                 "_stepCode" => "_stepCode",
            //                 "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
            //                 "_stepCode_supplierID" => "_stepCode_supplierID",
            //                 "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
            //                 "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
            //                 "_step_1_nomer" => "_step_1_nomer",
            //                 "_step_1_olehName" => "_step_1_olehName",
            //                 "_step_2_olehName" => "_step_2_nomer",
            //                 "_step_3_nomer" => "_step_3_nomer",
            //                 "_step_3_olehName" => "_step_3_olehName",
            //                 "_step_4_nomer" => "_step_4_nomer",
            //                 "_step_4_olehName" => "_step_4_olehName",
            //                 "rel_target_jenis" => "rel_target_jenis",
            //                 //------
            //             ),
            //             "reversable" => true,
            //             "srcGateName" => "main",
            //             "srcRawGateName" => "main",
            //         ),
            //         array(
            //             "comName" => "RekeningTransaksiKredit",
            //             "loop" => array(
            //                 //                            "{targetJenis}" => "-nilai_credit",
            //                 "582spd" => "-nilai_credit",
            //             ),
            //             "static" => array(
            //                 "cabang_id" => "placeID",
            //                 "cabang_nama" => "placeName",
            //                 "gudang_id" => "gudangID",
            //                 "gudang_nama" => "gudangName",
            //                 "rekening_nama" => "sourceJenisLabel",
            //                 "produk_qty" => "qty",
            //                 "produk_nilai" => "hpp",
            //                 "harga_bruto" => "harga",
            //                 "harga_netto" => "nett1",
            //                 "diskon_nilai" => "disc",
            //                 "premi_nilai" => "premi",
            //                 "ongkir_nilai" => "shipping_service",
            //                 "extern_id" => "transaksi_id",
            //                 "extern_nama" => "nomer",
            //                 "satuan" => "satuan",
            //                 "oleh_id" => "olehID",
            //                 "oleh_name" => "olehName",
            //                 "seller_id" => "sellerID",
            //                 "seller_nama" => "sellerName",
            //                 // "transaksi_id" => "transaksi_id",
            //
            //                 "master_id" => "transaksi_id",
            //                 "referenceID" => "referenceIDtarget",
            //                 "master_jenis" => "jenisTrMaster",
            //                 "jenis" => "sourceJenis",
            //                 "step_current" => "step_number",
            //                 "step_number" => "step_current",
            //                 "next_step_num" => "targetJenisNextStep",
            //                 //------
            //                 "_stepCode_placeID" => "_stepCode_placeID",
            //                 "_stepCode_olehID" => "_stepCode_olehID",
            //                 "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
            //                 "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
            //                 "_stepCode_customerID" => "_stepCode_customerID",
            //                 "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
            //                 "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
            //                 "_stepCode" => "_stepCode",
            //                 "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
            //                 "_stepCode_supplierID" => "_stepCode_supplierID",
            //                 "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
            //                 "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
            //                 //------
            //                 "_step_1_nomer" => "_step_1_nomer",
            //                 "_step_1_olehName" => "_step_1_olehName",
            //                 "_step_2_nomer" => "_step_2_nomer",
            //                 "_step_2_olehName" => "_step_2_olehName",
            //                 "_step_3_nomer" => "_step_3_nomer",
            //                 "_step_3_olehName" => "_step_3_olehName",
            //                 "_step_4_nomer" => "_step_4_nomer",
            //                 "_step_4_olehName" => "_step_4_olehName",
            //                 // "rel_target_jenis"=>"rel_target_jenis",//step akhir kredit spo jadi tidak punya rel target
            //             ),
            //             "reversable" => true,
            //             "srcGateName" => "main",
            //             "srcRawGateName" => "main",
            //         ),
            //
            //         // TRANSAKSI JENIS
            //         array(
            //             "comName" => "RekeningTransaksiJenis",
            //             "loop" => array(
            //                 //                            "{sourceJenis}" => "nilai_credit",
            //                 "776" => "nilai_credit",
            //             ),
            //             "static" => array(
            //                 "cabang_id" => "placeID",
            //
            //                 "gudang_id" => "gudangID",
            //                 "cabang_nama" => "placeName",
            //                 "gudang_nama" => "gudangName",
            //                 "rekening_nama" => "sourceJenisLabel",
            //                 "produk_qty" => "qty",
            //                 "produk_nilai" => "nilai_credit",
            //                 "extern_id" => "targetJenis",
            //                 "extern_nama" => "targetJenis",
            //                 "satuan" => "satuan",
            //                 "oleh_id" => "olehID",
            //                 "oleh_name" => "olehName",
            //                 "transaksi_id" => "transaksi_id",
            //                 "master_id" => "transaksi_id",
            //                 "jenis" => "sourceJenis",
            //                 "master_jenis" => "jenisTrMaster",
            //             ),
            //             "reversable" => true,
            //             "srcGateName" => "main",
            //             "srcRawGateName" => "main",
            //         ),
            //         array(
            //             "comName" => "RekeningTransaksiJenis",
            //             "loop" => array(
            //                 //                            "{targetJenis}" => "-nilai_credit",
            //                 "582spd" => "-nilai_credit",
            //             ),
            //             "static" => array(
            //                 "cabang_id" => "placeID",
            //                 "gudang_id" => "gudangID",
            //                 "cabang_nama" => "placeName",
            //                 "gudang_nama" => "gudangName",
            //                 "rekening_nama" => "targetJenisLabel",
            //                 "produk_qty" => "-qty",
            //                 "produk_nilai" => "-nilai_credit",
            //                 "extern_id" => "targetJenis",
            //                 "extern_nama" => "targetJenis",
            //                 "satuan" => "satuan",
            //                 "oleh_id" => "olehID",
            //                 "oleh_name" => "olehName",
            //                 "transaksi_id" => "transaksi_id",
            //                 "master_id" => "transaksi_id",
            //                 "jenis" => "targetJenis",
            //                 "master_jenis" => "jenisTrMaster",
            //             ),
            //             "reversable" => true,
            //             "srcGateName" => "main",
            //             "srcRawGateName" => "main",
            //         ),
            //
            //     ),
            //     "detail" => array(
            //         // PRODUK
            //         array(
            //             "comName" => "RekeningTransaksiPembantu",
            //             "loop" => array(
            //                 //                            "{sourceJenis}" => "nett1",
            //                 "776" => "nett1",
            //             ),
            //             "static" => array(
            //                 "rekening_nama" => "sourceJenisLabel",
            //                 "cabang_id" => "placeID",
            //                 "gudang_id" => "gudangID",
            //                 "gudang_nama" => "gudangName",
            //                 "cabang_nama" => "placeName",
            //                 "produk_qty" => "qty",
            //                 "produk_nilai" => "harga",
            //                 "harga_bruto" => "harga",
            //                 "harga_netto" => "nett1",
            //                 "diskon_nilai" => "disc",
            //                 "premi_nilai" => "premi",
            //                 "extern_id" => "id",
            //                 "extern_nama" => "name",
            //
            //                 "produk_kode" => "code",
            //                 "produk_part" => "no_part",
            //                 "produk_label" => "label",
            //                 "produk_jenis" => "jenis",
            //                 "produk_satuan" => "satuan",
            //                 "satuan" => "satuan",
            //                 "oleh_id" => "olehID",
            //                 "oleh_name" => "olehName",
            //                 "seller_id" => "sellerID",
            //                 "seller_nama" => "sellerName",
            //                 "transaksi_id" => "transaksi_id",
            //                 "master_id" => "transaksi_id",
            //                 "master_jenis" => "jenisTrMaster",
            //                 //------
            //                 "_stepCode_placeID" => "_stepCode_placeID",
            //                 "_stepCode_olehID" => "_stepCode_olehID",
            //                 "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
            //                 "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
            //                 "_stepCode_customerID" => "_stepCode_customerID",
            //                 "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
            //                 "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
            //                 "_stepCode" => "_stepCode",
            //                 "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
            //                 "_stepCode_supplierID" => "_stepCode_supplierID",
            //                 "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
            //                 "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
            //                 //------
            //             ),
            //             "reversable" => true,
            //             "srcGateName" => "items",
            //             "srcRawGateName" => "items",
            //         ),
            //         array(
            //             "comName" => "RekeningTransaksiPembantu",
            //             "loop" => array(
            //                 //                            "{targetJenis}" => "-nett1",
            //                 "582spd" => "-nett1",
            //             ),
            //             "static" => array(
            //                 "cabang_id" => "placeID",
            //                 "gudang_id" => "gudangID",
            //                 "gudang_nama" => "gudangName",
            //                 "cabang_nama" => "placeName",
            //                 "rekening_nama" => "targetJenisLabel",
            //                 "produk_qty" => "-qty",
            //                 "produk_nilai" => "hpp",
            //                 "extern_id" => "id",
            //                 "extern_nama" => "name",
            //
            //                 "produk_kode" => "code",
            //                 "produk_part" => "no_part",
            //                 "produk_label" => "label",
            //                 "produk_jenis" => "jenis",
            //                 "produk_satuan" => "satuan",
            //                 "satuan" => "satuan",
            //
            //                 "oleh_id" => "olehID",
            //                 "oleh_name" => "olehName",
            //                 "transaksi_id" => "transaksi_id",
            //                 "master_id" => "transaksi_id",
            //                 "master_jenis" => "jenisTrMaster",
            //                 //------
            //                 "_stepCode_placeID" => "_stepCode_placeID",
            //                 "_stepCode_olehID" => "_stepCode_olehID",
            //                 "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
            //                 "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
            //                 "_stepCode_customerID" => "_stepCode_customerID",
            //                 "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
            //                 "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
            //                 "_stepCode" => "_stepCode",
            //                 "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
            //                 "_stepCode_supplierID" => "_stepCode_supplierID",
            //                 "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
            //                 "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
            //                 //------
            //             ),
            //             "reversable" => true,
            //             "srcGateName" => "items",
            //             "srcRawGateName" => "items",
            //         ),
            //     ),
            // ),

        ),
    ),

    //region bagian pecah produksi
    "7761" => array(
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
            "7761a" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoSuppliesAssembly",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => ".17",//ditembak langsung karena pasti fase 1
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
                                // "harga_bom"=>"harga_bom",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
            "7761" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => ".18",
                        ),
                        "resultParams" => array(
                            "rsltItems" => array(
                                "id" => "produk_id",
                                "nama" => "nama",
                                "name" => "nama",
                                "harga" => "hpp",
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
                "produk_ord_jml" => "jml",
                "valid_qty" => "jml",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "detail2_sum" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "jml",
                "valid_qty" => "jml",
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
            "7761a" => array(
                "master" => array(
                    // jurnal reguler produksi
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "bb bom" => "-harga_bom",
                            "persediaan wip1" => "harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
                            "{costName_1}" => "-costNilai_1", // ex:overhead
                            "{costName_2}" => "-costNilai_2", // ex:direct labor
                            "{costName_3}" => "-costNilai_3", // ex:dst
                            "{costName_4}" => "-costNilai_4", // ex:dst
                            "{costName_5}" => "-costNilai_5", // ex:dst
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "bb bom" => "-harga_bom",
                            "persediaan wip1" => "harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
                            "{costName_1}" => "-costNilai_1", // ex:overhead
                            "{costName_2}" => "-costNilai_2", // ex:biaya_kirim
                            "{costName_3}" => "-costNilai_3", // ex:tenaga_kerja
                            "{costName_4}" => "-costNilai_4", // ex:tenaga_kerja
                            "{costName_5}" => "-costNilai_5", // ex:tenaga_kerja
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //juranal ori fifo ke persediaan
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "persediaan supplies" => "-hpp",
                            "bb1" => "hpp",
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
                            "persediaan supplies" => "-hpp",
                            "bb1" => "hpp",
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
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "efisiensi biaya" => "(harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5)",
                            "bb bom" => "harga_bom", // bb bom
                            "{costName_1}" => "costNilai_1", // ex:overhead
                            "{costName_2}" => "costNilai_2", // ex:biaya_kirim
                            "{costName_3}" => "costNilai_3", // ex:tenaga_kerja
                            "{costName_4}" => "costNilai_4", // ex:tenaga_kerja
                            "{costName_5}" => "costNilai_5", // ex:tenaga_kerja
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "efisiensi biaya" => "harga_bom+(costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5)",
                            "bb bom" => "harga_bom", // bb bom
                            "{costName_1}" => "costNilai_1", // ex:overhead
                            "{costName_2}" => "costNilai_2", // ex:biaya_kirim
                            "{costName_3}" => "costNilai_3", // ex:tenaga_kerja
                            "{costName_4}" => "costNilai_4", // ex:tenaga_kerja
                            "{costName_5}" => "costNilai_5", // ex:tenaga_kerja
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // pembantu efisiensi bom (cost)
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "harga_bom",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".7",
                            "extern_nama" => ".bb bom",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "costNilai_1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "costID_1",
                            "extern_nama" => "costName_1",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "costNilai_2",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "costID_2",
                            "extern_nama" => "costName_2",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "costNilai_3",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "costID_3",
                            "extern_nama" => "costName_3",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "costNilai_4",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "costID_4",
                            "extern_nama" => "costName_4",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "costNilai_5",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "costID_5",
                            "extern_nama" => "costName_5",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //pembantu gudang supplies bb out
                    array(
                        "comName" => "RekeningPembantuGudang",
                        "loop" => array(
                            "persediaan supplies" => "-hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "gudangID",//gudang bj fase 1
                            "extern_nama" => "gudangName",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //jurnal region pembantu gudang supplies wip
                    array(
                        "comName" => "RekeningPembantuGudang",
                        "loop" => array(
                            "persediaan wip1" => "harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".18",//gudang bj fase 1
                            "extern_nama" => ".Gudang BJ Fase 1",
                            "produk_qty" => "qty",
                            "produk_nilai" => "harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
                            "gudang_id" => ".20",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //jurnal region pembantu gudang supplies bb1
                    array(
                        "comName" => "RekeningPembantuGudang",
                        "loop" => array(
                            "bb1" => "hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".17",//gudang bj fase 1
                            "extern_nama" => ".Gudang BB Fase 1",
                            "produk_qty" => "qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => ".17",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    //region Com-pembantu supplies
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "persediaan supplies" => "-sub_hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "-jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => ".17",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    //region pembantu bb1 vs supplies fifo
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "bb1" => "sub_hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => ".17",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    //endregion
                    //supplies fase
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "persediaan wip1" => "sub_harga_bom+sub_costNilai_1+sub_costNilai_2+sub_costNilai_3+sub_costNilai_4+sub_costNilai_5",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "jml",
                            "produk_nilai" => "harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
                            "gudang_id" => ".18",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //endregion

                ),
            ),
            "7761" => array(
                "master" => array(
                    //jurnal geser peresediaan wip ke supplies
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "persediaan wip1" => "-hpp",
                            "persediaan supplies" => "hpp",
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
                            "persediaan wip1" => "-hpp",
                            "persediaan supplies" => "hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //jurnal region pembantu gudang supplies wip berkurang geser ke gudang selanjutnya
                    array(
                        "comName" => "RekeningPembantuGudang",
                        "loop" => array(
                            "persediaan wip1" => "-hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".18",//gudang bj fase 1
                            "extern_nama" => ".Gudang BJ Fase 1",
                            "produk_qty" => "qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => ".18",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //pembantu gudang supplies fase 2
                    array(
                        "comName" => "RekeningPembantuGudang",
                        "loop" => array(
                            "persediaan supplies" => "hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".19",//gudang bj fase 1
                            "extern_nama" => ".Gudang BB Fase 2",
                            "produk_qty" => "qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => ".19",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    //auto geser gudang ke fase 2 pembantu
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "persediaan wip1" => "-sub_hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => ".18",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "persediaan supplies" => "sub_hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "produk_nilai" => "hpp",
                            //                            "gudang_id" => "pihakID",
                            "gudang_id" => ".19",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "7761r" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".7000",
                            "jenis" => ".supplies",
                            "state" => ".active",
                            "jumlah" => "-jml",
                            "produk_id" => "id",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterIDPrev",
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
                            "transaksi_id" => "masterIDPrev",
                            "nomer" => ".0",
                            "gudang_id" => ".7000",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                ),
            ),
            "7761a" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".7000",
                            "jenis" => ".supplies",
                            "state" => ".hold",
                            "jumlah" => "-jml",
                            "produk_id" => "id",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterIDPrev",
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
                            "state" => ".moved",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterIDPrev",
                            "nomer" => ".0",
                            "gudang_id" => ".7000",
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
                            "state" => ".active",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterIDPrev",
                            "nomer" => ".0",
                            "gudang_id" => ".7001",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                ),
            ),
            "7761" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".7001",
                            "jenis" => ".supplies",
                            "state" => ".active",
                            "jumlah" => "-jml",
                            "produk_id" => "id",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterIDPrev",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    //locker stok gudang wip
                    //region post-locker stock dari supplies source
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".done",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterIDPrev",
                            "nomer" => ".0",
                            "gudang_id" => ".7001",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    array(
                        "comName" => "LockerStockJadiPh1",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".jadi_ph1",
                            "state" => ".active",
                            "jumlah" => "jml*1000", //dari KG ke GRAM sementara di inject langsung
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterIDPrev",
                            "nomer" => ".0",
                            "gudang_id" => ".7001",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),


                    array(
                        "comName" => "LockerStockJadiPh1",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".jadi_ph1",
                            "state" => ".done",
                            "jumlah" => "jml*1000", //dari KG ke GRAM sementara di inject langsung
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterIDPrev",
                            "nomer" => ".0",
                            "gudang_id" => ".7001",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                ),
            ),
        ),
    ),
    "7762" => array(
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
            "7762a" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoSuppliesAssembly",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => ".19",//ditembak langsung karena pasti fase 1
                            "gudang_nama" => ".Gudang BB Fase 2",//ditembak langsung karena pasti fase 1
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
            "7762" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => ".20",
                        ),
                        "resultParams" => array(
                            "rsltItems" => array(
                                "id" => "produk_id",
                                "nama" => "nama",
                                "name" => "nama",
                                "harga" => "hpp",
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
                "produk_ord_jml" => "jml",
                "valid_qty" => "jml",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "detail2_sum" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "jml",
                "valid_qty" => "jml",
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
            "7762a" => array(
                "master" => array(
                    // jurnal reguler produksi/bom
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "bb bom" => "-harga_bom",
                            "persediaan wip1" => "harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
                            "{costName_1}" => "-costNilai_1", // ex:overhead
                            "{costName_2}" => "-costNilai_2", // ex:biaya_kirim
                            "{costName_3}" => "-costNilai_3", // ex:tenaga_kerja
                            "{costName_4}" => "-costNilai_4", // ex:tenaga_kerja
                            "{costName_5}" => "-costNilai_5", // ex:tenaga_kerja
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
                            "bb bom" => "-harga_bom",
                            "persediaan wip1" => "harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
                            "{costName_1}" => "-costNilai_1", // ex:overhead
                            "{costName_2}" => "-costNilai_2", // ex:biaya_kirim
                            "{costName_3}" => "-costNilai_3", // ex:tenaga_kerja
                            "{costName_4}" => "-costNilai_4", // ex:tenaga_kerja
                            "{costName_5}" => "-costNilai_5", // ex:tenaga_kerja
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //juranal ori fifo ke persediaan
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "persediaan supplies" => "-hpp",
                            "bb1" => "hpp",
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
                            "persediaan supplies" => "-hpp",
                            "bb1" => "hpp",
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
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "efisiensi biaya" => "(harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5)",
                            "bb bom" => "harga_bom", // bb bom
                            "{costName_1}" => "costNilai_1", // ex:overhead
                            "{costName_2}" => "costNilai_2", // ex:biaya_kirim
                            "{costName_3}" => "costNilai_3", // ex:tenaga_kerja
                            "{costName_4}" => "costNilai_4", // ex:tenaga_kerja
                            "{costName_5}" => "costNilai_5", // ex:tenaga_kerja
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
                            "efisiensi biaya" => "(harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5)",
                            "bb bom" => "harga_bom", // bb bom
                            "{costName_1}" => "costNilai_1", // ex:overhead
                            "{costName_2}" => "costNilai_2", // ex:biaya_kirim
                            "{costName_3}" => "costNilai_3", // ex:tenaga_kerja
                            "{costName_4}" => "costNilai_4", // ex:tenaga_kerja
                            "{costName_5}" => "costNilai_5", // ex:tenaga_kerja
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
                            "efisiensi biaya" => "harga_bom",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".7",
                            "extern_nama" => ".bb bom",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "costNilai_1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "costID_1",
                            "extern_nama" => "costName_1",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "costNilai_2",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "costID_2",
                            "extern_nama" => "costName_2",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "costNilai_3",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "costID_3",
                            "extern_nama" => "costName_3",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "costNilai_4",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "costID_4",
                            "extern_nama" => "costName_4",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "costNilai_5",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "costID_5",
                            "extern_nama" => "costName_5",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    //jurnal region pembantu supplies per gudang
                    array(
                        "comName" => "RekeningPembantuGudang",
                        "loop" => array(
                            "persediaan supplies" => "-hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "gudangID",
                            "extern_nama" => "gudangName",
                            "produk_qty" => "qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //jurnal region pembantu wip per gudang
                    array(
                        "comName" => "RekeningPembantuGudang",
                        "loop" => array(
                            "persediaan wip1" => "harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".20",
                            "extern_nama" => ".Gudang BJ Fase 2",
                            "produk_qty" => "qty",
                            "produk_nilai" => "harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
                            "gudang_id" => ".20",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //jurnal region pembantu gudang supplies bb1
                    array(
                        "comName" => "RekeningPembantuGudang",
                        "loop" => array(
                            "bb1" => "hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".19",//gudang bj fase 1
                            "extern_nama" => ".Gudang BB Fase 2",
                            "produk_qty" => "qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => ".19",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(
                    //region Com-pembantu supplies
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "persediaan supplies" => "-sub_hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "-jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => ".19",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    //region pembantu bb1 vs supplies fifo
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "bb1" => "sub_hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => ".19",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    //supplies fase
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "persediaan wip1" => "sub_harga_bom+sub_costNilai_1+sub_costNilai_2+sub_costNilai_3+sub_costNilai_4+sub_costNilai_5",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "jml",
                            "produk_nilai" => "harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
                            "gudang_id" => ".20",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //endregion
                ),

            ),
            "7762" => array(
                "master" => array(
                    //jurnal geser peresediaan wip ke supplies
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "persediaan wip1" => "-hpp",
                            "persediaan supplies" => "hpp",
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
                            "persediaan wip1" => "-hpp",
                            "persediaan supplies" => "hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //jurnal region pembantu gudang supplies wip berkurang geser ke gudang selanjutnya
                    array(
                        "comName" => "RekeningPembantuGudang",
                        "loop" => array(
                            "persediaan wip1" => "-hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".20",//gudang bj fase 2
                            "extern_nama" => ".Gudang BJ Fase 2",
                            "produk_qty" => "qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => ".20",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //pembantu gudang supplies fase 2
                    array(
                        "comName" => "RekeningPembantuGudang",
                        "loop" => array(
                            "persediaan supplies" => "hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".21",//gudang bj fase 1
                            "extern_nama" => ".Gudang BB Fase 3",
                            "produk_qty" => "qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => ".20",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    //auto geser gudang ke fase 2 pembantu
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "persediaan wip1" => "-sub_hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => ".20",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "persediaan supplies" => "sub_hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "produk_nilai" => "hpp",
                            //                            "gudang_id" => "pihakID",
                            "gudang_id" => ".21",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "7762r" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerStockJadiPh1",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".jadi_ph1",
                            "state" => ".active",
                            "jumlah" => "-jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "transaksi_id" => "masterIDPrev",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "gudang_id" => ".7001",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    //locker stok gudang wip
                    //region post-locker stock dari supplies source
                    array(
                        "comName" => "LockerStockJadiPh1",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".jadi_ph1",
                            "state" => ".hold",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterIDPrev",
                            "nomer" => ".0",
                            "gudang_id" => ".7001",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    //endregion
                ),
            ),
            "7762a" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerStockJadiPh1",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".jadi_ph1",
                            "state" => ".hold",
                            "jumlah" => "-jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "transaksi_id" => "masterIDPrev",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "gudang_id" => ".7001",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    //locker stok gudang wip
                    //region post-locker stock dari supplies source
                    array(
                        "comName" => "LockerStockJadiPh1",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".jadi_ph1",
                            "state" => ".active",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterIDPrev",
                            "nomer" => ".0",
                            "gudang_id" => ".7002",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    //endregion
                ),
            ),
            "7762" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerStockJadiPh1",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".jadi_ph1",
                            "state" => ".active",
                            "jumlah" => "-jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "transaksi_id" => "masterIDPrev",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "gudang_id" => ".7002",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    //locker stok gudang wip
                    //region post-locker stock dari supplies source
                    array(
                        "comName" => "LockerStockJadiPh1",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".jadi_ph1",
                            "state" => ".done",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterIDPrev",
                            "nomer" => ".0",
                            "gudang_id" => ".7002",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    //endregion

                    //locker stok gudang wip
                    //region post-locker stock dari supplies source
                    array(
                        "comName" => "LockerStockJadiPh2",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".jadi_ph2",
                            "state" => ".active",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterIDPrev",
                            "nomer" => ".0",
                            "gudang_id" => ".7002",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),


                    array(
                        "comName" => "LockerStockJadiPh2",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".jadi_ph2",
                            "state" => ".done",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterIDPrev",
                            "nomer" => ".0",
                            "gudang_id" => ".7002",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //endregion
                ),
            ),
        ),
    ),
    "7763" => array(
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
            "7763a" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoSuppliesAssembly",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => ".21",//ditembak langsung karena pasti fase 1
                            "gudang_nama" => ".Gudang BB Fase 3",//ditembak langsung karena pasti fase 1
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
            "7763" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => ".22",
                        ),
                        "resultParams" => array(
                            "rsltItems" => array(
                                "id" => "produk_id",
                                "nama" => "nama",
                                "name" => "nama",
                                "harga" => "hpp",
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
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "ProdukConversion",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "harga" => "harga",
                            "hpp" => "hpp",
                            "gudang_id" => ".22",
                            "jenisTr" => "jenisTrMaster",
                            "srcRow" => ".rsltItems",
                        ),
                        "resultParams" => array(
                            "rsltItems3_sub" => array(
                                "id" => "id",
                                "nama" => "nama",
                                "name" => "nama",
                                "harga" => "hpp",
                                "hpp" => "hpp",
                                "jml" => "qty",
                                "qty" => "qty",
                            ),
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
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
                "produk_ord_jml" => "jml",
                "valid_qty" => "jml",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "detail2_sum" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "jml",
                "valid_qty" => "jml",
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
            "7763a" => array(
                "master" => array(
                    // jurnal reguler produksi
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "bb bom" => "-harga_bom",
                            "persediaan wip1" => "harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
                            "{costName_1}" => "-costNilai_1", // ex:overhead
                            "{costName_2}" => "-costNilai_2", // ex:biaya_kirim
                            "{costName_3}" => "-costNilai_3", // ex:tenaga_kerja
                            "{costName_4}" => "-costNilai_4", // ex:tenaga_kerja
                            "{costName_5}" => "-costNilai_5", // ex:tenaga_kerja
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
                            "bb bom" => "-harga_bom",
                            "persediaan wip1" => "harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
                            "{costName_1}" => "-costNilai_1", // ex:overhead
                            "{costName_2}" => "-costNilai_2", // ex:biaya_kirim
                            "{costName_3}" => "-costNilai_3", // ex:tenaga_kerja
                            "{costName_4}" => "-costNilai_4", // ex:tenaga_kerja
                            "{costName_5}" => "-costNilai_5", // ex:tenaga_kerja
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // juranal ori fifo ke persediaan
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "persediaan supplies" => "-hpp",
                            "bb1" => "hpp",
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
                            "persediaan supplies" => "-hpp",
                            "bb1" => "hpp",
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
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "efisiensi biaya" => "(harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5)",
                            "bb bom" => "harga_bom", // bb bom
                            "{costName_1}" => "costNilai_1", // ex:overhead
                            "{costName_2}" => "costNilai_2", // ex:biaya_kirim
                            "{costName_3}" => "costNilai_3", // ex:tenaga_kerja
                            "{costName_4}" => "costNilai_4", // ex:tenaga_kerja
                            "{costName_5}" => "costNilai_5", // ex:tenaga_kerja
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
                            "efisiensi biaya" => "(harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5)",
                            "bb bom" => "harga_bom", // bb bom
                            "{costName_1}" => "costNilai_1", // ex:overhead
                            "{costName_2}" => "costNilai_2", // ex:biaya_kirim
                            "{costName_3}" => "costNilai_3", // ex:tenaga_kerja
                            "{costName_4}" => "costNilai_4", // ex:tenaga_kerja
                            "{costName_5}" => "costNilai_5", // ex:tenaga_kerja
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
                            "efisiensi biaya" => "harga_bom",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".7",
                            "extern_nama" => ".bb bom",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "costNilai_1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "costID_1",
                            "extern_nama" => "costName_1",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "costNilai_2",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "costID_2",
                            "extern_nama" => "costName_2",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "costNilai_3",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "costID_3",
                            "extern_nama" => "costName_3",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "costNilai_4",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "costID_4",
                            "extern_nama" => "costName_4",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "costNilai_5",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "costID_5",
                            "extern_nama" => "costName_5",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    //jurnal region pembantu supplies per gudang
                    array(
                        "comName" => "RekeningPembantuGudang",
                        "loop" => array(
                            "persediaan supplies" => "-hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "gudangID",
                            "extern_nama" => "gudangName",
                            "produk_qty" => "qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //jurnal region pembantu wip per gudang
                    array(
                        "comName" => "RekeningPembantuGudang",
                        "loop" => array(
                            "persediaan wip1" => "harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".22",
                            "extern_nama" => ".Gudang BJ Fase 2",
                            "produk_qty" => "qty",
                            "produk_nilai" => "hpp+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
                            "gudang_id" => ".22",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //jurnal region pembantu gudang supplies bb1
                    array(
                        "comName" => "RekeningPembantuGudang",
                        "loop" => array(
                            "bb1" => "hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".21",//gudang bj fase 1
                            "extern_nama" => ".Gudang BB Fase 3",
                            "extern_nama" => ".Gudang BB Fase 3",
                            "produk_qty" => "qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => ".21",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    //region Com-pembantu supplies
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "persediaan supplies" => "-sub_hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "-jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => ".21",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    //region pembantu bb1 vs supplies fifo
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "bb1" => "sub_hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => ".21",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    //supplies fase
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "persediaan wip1" => "sub_harga_bom+sub_costNilai_1+sub_costNilai_2+sub_costNilai_3+sub_costNilai_4+sub_costNilai_5",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "jml",
                            "produk_nilai" => "harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
                            "gudang_id" => ".22",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //endregion
                ),
            ),
            "7763" => array(
                "master" => array(
                    //region geser hasil wip ke roduk jadi
                    // jurnal reguler produksi/bom
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "persediaan wip1" => "-hpp",
                            "persediaan produk" => "hpp",

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
                            "persediaan wip1" => "-hpp",
                            "persediaan produk" => "hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //jurnal region pembantu gudang supplies wip berkurang geser ke gudang selanjutnya
                    array(
                        "comName" => "RekeningPembantuGudang",
                        "loop" => array(
                            "persediaan wip1" => "-hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".22",//gudang bj fase 1
                            "extern_nama" => ".Gudang BJ Fase 3",
                            "produk_qty" => "qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => ".22",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //pembantu gudang produk fase 2
                    array(
                        "comName" => "RekeningPembantuGudang",
                        "loop" => array(
                            "persediaan produk" => "hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".27",//gudang produksi
                            "extern_nama" => ".Gudang Produksi",
                            "produk_qty" => "qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => ".27",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    //auto geser gudang ke fase 2 pembantu
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "persediaan wip1" => "-sub_hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => ".22",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "persediaan produk" => "sub_hpp",
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "produk_nilai" => "hpp",
                            //"gudang_id" => "pihakID",
                            "gudang_id" => ".27",//gudang produksi
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "rsltItems3_sub",
                        "srcRawGateName" => "rsltItems3_sub",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "7763r" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerStockJadiPh2",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".jadi_ph2",
                            "state" => ".active",
                            "jumlah" => "-jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "transaksi_id" => "masterIDPrev",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "gudang_id" => ".7002",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    //locker stok gudang wip
                    //region post-locker stock dari supplies source
                    array(
                        "comName" => "LockerStockJadiPh2",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".jadi_ph2",
                            "state" => ".hold",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterIDPrev",
                            "nomer" => ".0",
                            "gudang_id" => ".7002",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    //endregion
                ),
            ),
            "7763a" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerStockJadiPh2",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".jadi_ph2",
                            "state" => ".hold",
                            "jumlah" => "-jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "transaksi_id" => "masterIDPrev",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "gudang_id" => ".7002",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    //locker stok gudang wip
                    //region post-locker stock dari supplies source
                    array(
                        "comName" => "LockerStockJadiPh2",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".jadi_ph2",
                            "state" => ".active",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterIDPrev",
                            "nomer" => ".0",
                            "gudang_id" => ".7003",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    //endregion
                ),
            ),
            "7763" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerStockJadiPh2",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".jadi_ph2",
                            "state" => ".active",
                            "jumlah" => "-jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "transaksi_id" => "masterIDPrev",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "gudang_id" => ".7003",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    //locker stok gudang wip
                    //region post-locker stock dari supplies source
                    array(
                        "comName" => "LockerStockJadiPh2",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".jadi_ph2",
                            "state" => ".done",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterIDPrev",
                            "nomer" => ".0",
                            "gudang_id" => ".7003",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    //endregion

                    //locker stok gudang wip
                    //region post-locker stock dari supplies source
                    array(
                        "comName" => "LockerStockJadiPh3",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".jadi_ph3",
                            "state" => ".active",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterIDPrev",
                            "nomer" => ".0",
                            "gudang_id" => ".7003",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    array(
                        "comName" => "LockerStockJadiPh3",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".jadi_ph3",
                            "state" => ".done",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterIDPrev",
                            "nomer" => ".0",
                            "gudang_id" => ".7003",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //endregion
                ),
            ),
        ),
    ),
    "7764" => array(
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
            "7764a" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoSuppliesAssembly",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => ".21",//ditembak langsung karena pasti fase 1
                            "gudang_nama" => ".Gudang BB Fase 3",//ditembak langsung karena pasti fase 1
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
            "7764" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => ".22",
                        ),
                        "resultParams" => array(
                            "rsltItems" => array(
                                "id" => "produk_id",
                                "nama" => "nama",
                                "name" => "nama",
                                "harga" => "hpp",
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
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "ProdukConversion",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "harga" => "harga",
                            "hpp" => "hpp",
                            "gudang_id" => ".22",
                            "jenisTr" => "jenisTrMaster",
                            "srcRow" => ".rsltItems",
                        ),
                        "resultParams" => array(
                            "rsltItems3_sub" => array(
                                "id" => "id",
                                "nama" => "nama",
                                "name" => "nama",
                                "harga" => "hpp",
                                "hpp" => "hpp",
                                "jml" => "qty",
                                "qty" => "qty",
                            ),
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
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
                "produk_ord_jml" => "jml",
                "valid_qty" => "jml",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "detail2_sum" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "jml",
                "valid_qty" => "jml",
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
            "7764a" => array(
                "master" => array(
                    // jurnal reguler produksi
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "bb bom" => "-harga_bom",
                            "persediaan wip1" => "harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
                            "{costName_1}" => "-costNilai_1", // ex:overhead
                            "{costName_2}" => "-costNilai_2", // ex:biaya_kirim
                            "{costName_3}" => "-costNilai_3", // ex:tenaga_kerja
                            "{costName_4}" => "-costNilai_4", // ex:tenaga_kerja
                            "{costName_5}" => "-costNilai_5", // ex:tenaga_kerja
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
                            "bb bom" => "-harga_bom",
                            "persediaan wip1" => "harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
                            "{costName_1}" => "-costNilai_1", // ex:overhead
                            "{costName_2}" => "-costNilai_2", // ex:biaya_kirim
                            "{costName_3}" => "-costNilai_3", // ex:tenaga_kerja
                            "{costName_4}" => "-costNilai_4", // ex:tenaga_kerja
                            "{costName_5}" => "-costNilai_5", // ex:tenaga_kerja
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // juranal ori fifo ke persediaan
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "persediaan supplies" => "-hpp",
                            "bb1" => "hpp",
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
                            "persediaan supplies" => "-hpp",
                            "bb1" => "hpp",
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
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "efisiensi biaya" => "(harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5)",
                            "bb bom" => "harga_bom", // bb bom
                            "{costName_1}" => "costNilai_1", // ex:overhead
                            "{costName_2}" => "costNilai_2", // ex:biaya_kirim
                            "{costName_3}" => "costNilai_3", // ex:tenaga_kerja
                            "{costName_4}" => "costNilai_4", // ex:tenaga_kerja
                            "{costName_5}" => "costNilai_5", // ex:tenaga_kerja
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
                            "efisiensi biaya" => "(harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5)",
                            "bb bom" => "harga_bom", // bb bom
                            "{costName_1}" => "costNilai_1", // ex:overhead
                            "{costName_2}" => "costNilai_2", // ex:biaya_kirim
                            "{costName_3}" => "costNilai_3", // ex:tenaga_kerja
                            "{costName_4}" => "costNilai_4", // ex:tenaga_kerja
                            "{costName_5}" => "costNilai_5", // ex:tenaga_kerja
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
                            "efisiensi biaya" => "harga_bom",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".7",
                            "extern_nama" => ".bb bom",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "costNilai_1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "costID_1",
                            "extern_nama" => "costName_1",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "costNilai_2",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "costID_2",
                            "extern_nama" => "costName_2",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "costNilai_3",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "costID_3",
                            "extern_nama" => "costName_3",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "costNilai_4",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "costID_4",
                            "extern_nama" => "costName_4",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "costNilai_5",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "costID_5",
                            "extern_nama" => "costName_5",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    //jurnal region pembantu supplies per gudang
                    array(
                        "comName" => "RekeningPembantuGudang",
                        "loop" => array(
                            "persediaan supplies" => "-hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "gudangID",
                            "extern_nama" => "gudangName",
                            "produk_qty" => "qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //jurnal region pembantu wip per gudang
                    array(
                        "comName" => "RekeningPembantuGudang",
                        "loop" => array(
                            "persediaan wip1" => "harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".22",
                            "extern_nama" => ".Gudang BJ Fase 2",
                            "produk_qty" => "qty",
                            "produk_nilai" => "hpp+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
                            "gudang_id" => ".22",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //jurnal region pembantu gudang supplies bb1
                    array(
                        "comName" => "RekeningPembantuGudang",
                        "loop" => array(
                            "bb1" => "hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".21",//gudang bj fase 1
                            "extern_nama" => ".Gudang BB Fase 3",
                            "extern_nama" => ".Gudang BB Fase 3",
                            "produk_qty" => "qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => ".21",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    //region Com-pembantu supplies
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "persediaan supplies" => "-sub_hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "-jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => ".21",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    //region pembantu bb1 vs supplies fifo
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "bb1" => "sub_hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => ".21",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    //supplies fase
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "persediaan wip1" => "sub_harga_bom+sub_costNilai_1+sub_costNilai_2+sub_costNilai_3+sub_costNilai_4+sub_costNilai_5",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "jml",
                            "produk_nilai" => "harga_bom+costNilai_1+costNilai_2+costNilai_3+costNilai_4+costNilai_5",
                            "gudang_id" => ".22",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //endregion

                ),

            ),
            "7764" => array(
                "master" => array(
                    //region geser hasil wip ke roduk jadi
                    // jurnal reguler produksi/bom
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "persediaan wip1" => "-hpp",
                            "persediaan produk" => "hpp",

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
                            "persediaan wip1" => "-hpp",
                            "persediaan produk" => "hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //jurnal region pembantu gudang supplies wip berkurang geser ke gudang selanjutnya
                    array(
                        "comName" => "RekeningPembantuGudang",
                        "loop" => array(
                            "persediaan wip1" => "-hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".22",//gudang bj fase 1
                            "extern_nama" => ".Gudang BJ Fase 3",
                            "produk_qty" => "qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => ".22",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //pembantu gudang produk fase 2
                    array(
                        "comName" => "RekeningPembantuGudang",
                        "loop" => array(
                            "persediaan produk" => "hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".27",//gudang produksi
                            "extern_nama" => ".Gudang Produksi",
                            "produk_qty" => "qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => ".27",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    //auto geser gudang ke fase 2 pembantu
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "persediaan wip1" => "-sub_hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => ".22",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "persediaan produk" => "sub_hpp",
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "produk_nilai" => "hpp",
                            //"gudang_id" => "pihakID",
                            "gudang_id" => ".27",//gudang produksi
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "rsltItems3_sub",
                        "srcRawGateName" => "rsltItems3_sub",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "7764r" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerStockJadiPh3",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".jadi_ph3",
                            "state" => ".active",
                            "jumlah" => "-jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "transaksi_id" => "masterIDPrev",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "gudang_id" => ".7003",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    //locker stok gudang wip
                    //region post-locker stock dari supplies source
                    array(
                        "comName" => "LockerStockJadiPh3",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".jadi_ph3",
                            "state" => ".hold",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterIDPrev",
                            "nomer" => ".0",
                            "gudang_id" => ".7003",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    //endregion
                ),
            ),
            "7764a" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerStockJadiPh3",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".jadi_ph3",
                            "state" => ".hold",
                            "jumlah" => "-jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "transaksi_id" => "masterIDPrev",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "gudang_id" => ".7003",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    array(
                        "comName" => "LockerStockJadiPh3",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".jadi_ph3",
                            "state" => ".moved",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterIDPrev",
                            "nomer" => ".0",
                            "gudang_id" => ".7003",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    //locker stok gudang wip
                    //region post-locker stock dari supplies source
                    array(
                        "comName" => "LockerStockJadiPh3",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".jadi_ph3",
                            "state" => ".active",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterIDPrev",
                            "nomer" => ".0",
                            "gudang_id" => ".7004",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    //endregion
                ),
            ),
            "7764" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerStockJadiPh3",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".jadi_ph3",
                            "state" => ".active",
                            "jumlah" => "-jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "transaksi_id" => "masterIDPrev",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "gudang_id" => ".7004",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    //locker stok gudang wip
                    //region post-locker stock dari supplies source
                    array(
                        "comName" => "LockerStockJadiPh3",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".jadi_ph3",
                            "state" => ".done",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterIDPrev",
                            "nomer" => ".0",
                            "gudang_id" => ".7004",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    //endregion

                    //locker stok gudang wip
                    //region post-locker stock dari supplies source
                    array(
                        "comName" => "LockerStockJadiPh4",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".jadi_ph4",
                            "state" => ".active",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterIDPrev",
                            "nomer" => ".0",
                            "gudang_id" => ".7004",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    array(
                        "comName" => "LockerStockJadiPh4",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".jadi_ph4",
                            "state" => ".done",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterIDPrev",
                            "nomer" => ".0",
                            "gudang_id" => ".7004",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //endregion
                ),
            ),
        ),
    ),
);