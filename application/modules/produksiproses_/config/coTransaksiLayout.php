<?php

$config["coTransaksiLayout"] = array(

    //  config produksi (supplies ke finish goods rakitan)
    "776" => array(
        "receiptTemplate" => array(
            1 => "template/776r.html",
            2 => "template/776.html",
            3 => "template/776.html",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            3 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(),
            2 => array(),
            3 => array(),
        ),
        "headerNota" => array(
            "dtime" => "date",
            "cabang_nama" => "branch",
            "tlp_1" => "phone",
            "alamat_1" => "address",
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang2_nama" => "branch",
            "dtime" => "date",
        ),

        "receiptDetailFields" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "receiptDetailFields2" => array(
            1 => array(
                "nama" => "bahan baku",
                "satuan" => "satuan",
                "nilai" => "harga",
                "jml" => "qty",
                "sub_nilai" => "subtotal",
            ),
            2 => array(
                "nama" => "bahan baku",
                "satuan" => "satuan",
                "nilai" => "harga",
                "jml" => "qty",
                "sub_nilai" => "subtotal",
            ),
            3 => array(
                "nama" => "bahan baku",
                "satuan" => "satuan",
                "nilai" => "harga",
                "jml" => "qty",
                "sub_nilai" => "subtotal",
            ),
        ),
        "receiptDetailFields3" => array(
            1 => array(
                "nama" => "summary standard cost",
                "sub_nilai" => "amount",
            ),
            2 => array(
                "nama" => "summary standard cost",
                "sub_nilai" => "amount",
            ),
            3 => array(
                "nama" => "summary standard cost",
                "sub_nilai" => "amount",
            ),
        ),
        "shoppingCartFieldsHasil_fase" => array(
            1 => array(
                "produk_dasar_nama" => "hasil bom",
                "jml" => "qty",
                "satuan_nama" => "satuan",
            ),
            2 => array(
                "produk_dasar_nama" => "hasil bom",
                "jml" => "qty",
                "satuan_nama" => "satuan",
            ),
            3 => array(
                "produk_dasar_nama" => "hasil bom",
                "jml" => "qty",
                "satuan_nama" => "satuan",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(),
            2 => array(),
            3 => array(),
        ),
        "receiptSumFields" => array(
            1 => array(//                "hpp" => "grand total"
            ),
            2 => array(//                "hpp" => "grand total"
            ),
            3 => array(//                "hpp" => "grand total"
            ),
        ),
        "receiptSumFields2" => array(
            1 => array(//                "hpp" => "grand total"
            ),
            2 => array(//                "hpp" => "grand total"
            ),
            3 => array(//                "hpp" => "grand total"
            ),
        ),
//        "receiptSumFields2_fase" => array(
//            1 => array(
//                "hpp" => "total bahan baku"
//            ),
//            2 => array(
//                "hpp" => "total bahan baku"
//            ),
//            3 => array(
//                "hpp" => "total bahan baku"
//            ),
//        ),
        "receiptSumFields3" => array(
            1 => array(
                "nilai" => "total amount",
            ),
            2 => array(
                "nilai" => "total amount",
            ),
            3 => array(
                "nilai" => "total amount",
            ),
        ),
        "receiptSumFields3_fase" => array(
            1 => array(
                "sub_nilai" => "total amount",
            ),
            2 => array(
                "sub_nilai" => "total amount",
            ),
            3 => array(
                "sub_nilai" => "total amount",
            ),
        ),

        "reportSumFields" => array(
            "cabang2_id" => "cabang2_nama",
        ),
        "printLocation" => "Printing/viewReceiptProduksi/",
        "receiptInword" => array(
            1 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            2 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            3 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
        ),
    ),
    "7776" => array(
        "receiptTemplate" => array(
            1 => "template/776r.html",
            2 => "template/776.html",
            3 => "template/776.html",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            3 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(),
            2 => array(),
            3 => array(),
        ),
        "headerNota" => array(
            "dtime" => "date",
            "cabang_nama" => "branch",
            "tlp_1" => "phone",
            "alamat_1" => "address",
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang2_nama" => "branch",
            "dtime" => "date",
        ),

        "receiptDetailFields" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "receiptDetailFields2" => array(
            1 => array(
                //                "produk_nama" => "item source name",
                //                "produk_ord_jml" => "qty",
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                //                "produk_nama" => "item source name",
                //                "produk_ord_jml" => "qty",
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                //                "produk_nama" => "item source name",
                //                "produk_ord_jml" => "qty",
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "receiptDetailFields3" => array(
            1 => array(
                "nama" => "summary standard cost",
                "sub_nilai" => "amount",
            ),
            2 => array(
                "nama" => "summary standard cost",
                "sub_nilai" => "amount",
            ),
            3 => array(
                "nama" => "summary standard cost",
                "sub_nilai" => "amount",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(),
            2 => array(),
            3 => array(),
        ),
        "receiptSumFields" => array(
            1 => array(//                "hpp" => "grand total"
            ),
            2 => array(//                "hpp" => "grand total"
            ),
            3 => array(//                "hpp" => "grand total"
            ),
        ),
        "receiptSumFields2" => array(
            1 => array(//                "hpp" => "grand total"
            ),
            2 => array(//                "hpp" => "grand total"
            ),
            3 => array(//                "hpp" => "grand total"
            ),
        ),
        "receiptSumFields3" => array(
            1 => array(
                "nilai" => "total amount",
            ),
            2 => array(
                "nilai" => "total amount",
            ),
            3 => array(
                "nilai" => "total amount",
            ),
        ),
        "receiptSumFields3_fase" => array(
            1 => array(
                "sub_nilai" => "total amount",
            ),
            2 => array(
                "sub_nilai" => "total amount",
            ),
            3 => array(
                "sub_nilai" => "total amount",
            ),
        ),

        "reportSumFields" => array(
            "cabang2_id" => "cabang2_nama",
        ),
        "printLocation" => "Printing/viewReceiptProduksi/",
        "receiptInword" => array(
            1 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            2 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            3 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
        ),
    ),
    "7778" => array(
        "receiptTemplate" => array(
            1 => "template/776r.html",
            2 => "template/776.html",
            3 => "template/776.html",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            3 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(),
            2 => array(),
            3 => array(),
        ),
        "headerNota" => array(
            "dtime" => "date",
            "cabang_nama" => "branch",
            "tlp_1" => "phone",
            "alamat_1" => "address",
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang2_nama" => "branch",
            "dtime" => "date",
        ),

        "receiptDetailFields" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "receiptDetailFields2" => array(
            1 => array(
                //                "produk_nama" => "item source name",
                //                "produk_ord_jml" => "qty",
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                //                "produk_nama" => "item source name",
                //                "produk_ord_jml" => "qty",
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                //                "produk_nama" => "item source name",
                //                "produk_ord_jml" => "qty",
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "receiptDetailFields3" => array(
            1 => array(
                "nama" => "summary standard cost",
                "sub_nilai" => "amount",
            ),
            2 => array(
                "nama" => "summary standard cost",
                "sub_nilai" => "amount",
            ),
            3 => array(
                "nama" => "summary standard cost",
                "sub_nilai" => "amount",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(),
            2 => array(),
            3 => array(),
        ),
        "receiptSumFields" => array(
            1 => array(//                "hpp" => "grand total"
            ),
            2 => array(//                "hpp" => "grand total"
            ),
            3 => array(//                "hpp" => "grand total"
            ),
        ),
        "receiptSumFields2" => array(
            1 => array(
                "nilai_bom" => "total amount"
            ),
            2 => array(
                "nilai_bom" => "total amount"
            ),
            3 => array(
                "nilai_bom" => "total amount"
            ),
        ),
        "receiptSumFields3" => array(
            1 => array(
                "nilai" => "total amount",
            ),
            2 => array(
                "nilai" => "total amount",
            ),
            3 => array(
                "nilai" => "total amount",
            ),
        ),
        "receiptSumFields3_fase" => array(
            1 => array(
                "sub_nilai" => "total amount",
            ),
            2 => array(
                "sub_nilai" => "total amount",
            ),
            3 => array(
                "sub_nilai" => "total amount",
            ),
        ),

        "reportSumFields" => array(
            "cabang2_id" => "cabang2_nama",
        ),
        "printLocation" => "Printing/viewReceiptProduksi/",
        "receiptInword" => array(
            1 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            2 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            3 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
        ),
    ),

    "580" => array(
        "receiptTemplate" => array(
            1 => "template/776r.html",
            2 => "template/776.html",
            3 => "template/776.html",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            3 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(),
            2 => array(),
            3 => array(),
        ),
        "headerNota" => array(
            "dtime" => "date",
            "cabang_nama" => "branch",
            "tlp_1" => "phone",
            "alamat_1" => "address",
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang2_nama" => "branch",
            "dtime" => "date",
        ),

        "receiptDetailFields" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "receiptDetailFields2" => array(
            1 => array(
                //                "produk_nama" => "item source name",
                //                "produk_ord_jml" => "qty",
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                //                "produk_nama" => "item source name",
                //                "produk_ord_jml" => "qty",
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                //                "produk_nama" => "item source name",
                //                "produk_ord_jml" => "qty",
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "receiptDetailFields3" => array(
            1 => array(
                "nama" => "summary standard cost",
                "sub_nilai" => "amount",
            ),
            2 => array(
                "nama" => "summary standard cost",
                "sub_nilai" => "amount",
            ),
            3 => array(
                "nama" => "summary standard cost",
                "sub_nilai" => "amount",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(),
            2 => array(),
            3 => array(),
        ),
        "receiptSumFields" => array(
            1 => array(//                "hpp" => "grand total"
            ),
            2 => array(//                "hpp" => "grand total"
            ),
            3 => array(//                "hpp" => "grand total"
            ),
        ),
        "receiptSumFields2" => array(
            1 => array(//                "hpp" => "grand total"
            ),
            2 => array(//                "hpp" => "grand total"
            ),
            3 => array(//                "hpp" => "grand total"
            ),
        ),
        "receiptSumFields3" => array(
            1 => array(
                "nilai" => "total amount",
            ),
            2 => array(
                "nilai" => "total amount",
            ),
            3 => array(
                "nilai" => "total amount",
            ),
        ),

        "reportSumFields" => array(
            "cabang2_id" => "cabang2_nama",
        ),
        "printLocation" => "Printing/viewReceiptProduksi/",
        "receiptInword" => array(
            1 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            2 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            3 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
        ),
    ),
    "7761" => array(
        "receiptTemplate" => array(
            1 => "template/776r.html",
            2 => "template/776.html",
            3 => "template/776.html",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            3 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(),
            2 => array(),
            3 => array(),
        ),
        "headerNota" => array(
            "dtime" => "date",
            "cabang_nama" => "branch",
            "tlp_1" => "phone",
            "alamat_1" => "address",
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang2_nama" => "branch",
            "dtime" => "date",
        ),

        "receiptDetailFields" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "receiptDetailFields2" => array(
            1 => array(
                //                "produk_nama" => "item source name",
                //                "produk_ord_jml" => "qty",
                "nama" => "item source name*",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                //                "produk_nama" => "item source name",
                //                "produk_ord_jml" => "qty",
                "nama" => "nama bahan baku",
                "stok" => "avail stock",
                "jml" => "dibutuhkan",
                "satuan" => "satuan",
                "kurang" => "kekurangan",
            ),
            3 => array(
                //                "produk_nama" => "item source name",
                //                "produk_ord_jml" => "qty",
                "nama" => "item source name***",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
//        "receiptDetailFields3" => array(
//            1 => array(
//                "nama" => "summary standard cost",
//                "sub_nilai" => "amount",
//            ),
//            2 => array(
//                "nama" => "summary standard cost",
//                "sub_nilai" => "amount",
//            ),
//            3 => array(
//                "nama" => "summary standard cost",
//                "sub_nilai" => "amount",
//            ),
//        ),
        "receiptNumFields" => array(
            1 => array(),
            2 => array(),
            3 => array(),
        ),
        "receiptSumFields" => array(
            1 => array(//                "hpp" => "grand total"
            ),
            2 => array(//                "hpp" => "grand total"
            ),
            3 => array(//                "hpp" => "grand total"
            ),
        ),
        "receiptSumFields2" => array(
            1 => array(//                "hpp" => "grand total"
            ),
            2 => array(//                "hpp" => "grand total"
            ),
            3 => array(//                "hpp" => "grand total"
            ),
        ),
//        "receiptSumFields3" => array(
//            1 => array(
//                "nilai" => "total amount",
//            ),
//            2 => array(
//                "nilai" => "total amount",
//            ),
//            3 => array(
//                "nilai" => "total amount",
//            ),
//        ),

        "reportSumFields" => array(
            "cabang2_id" => "cabang2_nama",
        ),
        "printLocation" => "Printing/viewReceiptProduksi/",
        "receiptInword" => array(
            1 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            2 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            3 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
        ),
    ),
    "7762" => array(
        "receiptTemplate" => array(
            1 => "template/776r.html",
            2 => "template/776.html",
            3 => "template/776.html",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            3 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(),
            2 => array(),
            3 => array(),
        ),
        "headerNota" => array(
            "dtime" => "date",
            "cabang_nama" => "branch",
            "tlp_1" => "phone",
            "alamat_1" => "address",
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang2_nama" => "branch",
            "dtime" => "date",
        ),

        "receiptDetailFields" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "receiptDetailFields2" => array(
            1 => array(
                //                "produk_nama" => "item source name",
                //                "produk_ord_jml" => "qty",
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                //                "produk_nama" => "item source name",
                //                "produk_ord_jml" => "qty",
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                //                "produk_nama" => "item source name",
                //                "produk_ord_jml" => "qty",
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "receiptDetailFields3" => array(
//            1 => array(
//                "nama" => "summary standard cost",
//                "sub_nilai" => "amount",
//            ),
//            2 => array(
//                "nama" => "summary standard cost",
//                "sub_nilai" => "amount",
//            ),
//            3 => array(
//                "nama" => "summary standard cost",
//                "sub_nilai" => "amount",
//            ),
        ),
        "receiptNumFields" => array(
            1 => array(),
            2 => array(),
            3 => array(),
        ),
        "receiptSumFields" => array(
            1 => array(//                "hpp" => "grand total"
            ),
            2 => array(//                "hpp" => "grand total"
            ),
            3 => array(//                "hpp" => "grand total"
            ),
        ),
        "receiptSumFields2" => array(
            1 => array(//                "hpp" => "grand total"
            ),
            2 => array(//                "hpp" => "grand total"
            ),
            3 => array(//                "hpp" => "grand total"
            ),
        ),
        "receiptSumFields3" => array(
            1 => array(
                "nilai" => "total amount",
            ),
            2 => array(
                "nilai" => "total amount",
            ),
            3 => array(
                "nilai" => "total amount",
            ),
        ),

        "reportSumFields" => array(
            "cabang2_id" => "cabang2_nama",
        ),
        "printLocation" => "Printing/viewReceiptProduksi/",
        "receiptInword" => array(
            1 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            2 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            3 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
        ),
    ),
    "7763" => array(
        "receiptTemplate" => array(
            1 => "template/776r.html",
            2 => "template/776.html",
            3 => "template/776.html",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            3 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(),
            2 => array(),
            3 => array(),
        ),
        "headerNota" => array(
            "dtime" => "date",
            "cabang_nama" => "branch",
            "tlp_1" => "phone",
            "alamat_1" => "address",
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang2_nama" => "branch",
            "dtime" => "date",
        ),

        "receiptDetailFields" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "receiptDetailFields2" => array(
            1 => array(
                //                "produk_nama" => "item source name",
                //                "produk_ord_jml" => "qty",
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                //                "produk_nama" => "item source name",
                //                "produk_ord_jml" => "qty",
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                //                "produk_nama" => "item source name",
                //                "produk_ord_jml" => "qty",
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "receiptDetailFields3" => array(
//            1 => array(
//                "nama" => "summary standard cost",
//                "sub_nilai" => "amount",
//            ),
//            2 => array(
//                "nama" => "summary standard cost",
//                "sub_nilai" => "amount",
//            ),
//            3 => array(
//                "nama" => "summary standard cost",
//                "sub_nilai" => "amount",
//            ),
        ),
        "receiptNumFields" => array(
            1 => array(),
            2 => array(),
            3 => array(),
        ),
        "receiptSumFields" => array(
            1 => array(//                "hpp" => "grand total"
            ),
            2 => array(//                "hpp" => "grand total"
            ),
            3 => array(//                "hpp" => "grand total"
            ),
        ),
        "receiptSumFields2" => array(
            1 => array(//                "hpp" => "grand total"
            ),
            2 => array(//                "hpp" => "grand total"
            ),
            3 => array(//                "hpp" => "grand total"
            ),
        ),
        "receiptSumFields3" => array(
            1 => array(
                "nilai" => "total amount",
            ),
            2 => array(
                "nilai" => "total amount",
            ),
            3 => array(
                "nilai" => "total amount",
            ),
        ),

        "reportSumFields" => array(
            "cabang2_id" => "cabang2_nama",
        ),
        "printLocation" => "Printing/viewReceiptProduksi/",
        "receiptInword" => array(
            1 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            2 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            3 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
        ),
    ),
    "7764" => array(
        "receiptTemplate" => array(
            1 => "template/776r.html",
            2 => "template/776.html",
            3 => "template/776.html",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            3 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(),
            2 => array(),
            3 => array(),
        ),
        "headerNota" => array(
            "dtime" => "date",
            "cabang_nama" => "branch",
            "tlp_1" => "phone",
            "alamat_1" => "address",
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang2_nama" => "branch",
            "dtime" => "date",
        ),

        "receiptDetailFields" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "receiptDetailFields2" => array(
            1 => array(
                //                "produk_nama" => "item source name",
                //                "produk_ord_jml" => "qty",
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                //                "produk_nama" => "item source name",
                //                "produk_ord_jml" => "qty",
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                //                "produk_nama" => "item source name",
                //                "produk_ord_jml" => "qty",
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "receiptDetailFields3" => array(
//            1 => array(
//                "nama" => "summary standard cost",
//                "sub_nilai" => "amount",
//            ),
//            2 => array(
//                "nama" => "summary standard cost",
//                "sub_nilai" => "amount",
//            ),
//            3 => array(
//                "nama" => "summary standard cost",
//                "sub_nilai" => "amount",
//            ),
        ),
        "receiptNumFields" => array(
            1 => array(),
            2 => array(),
            3 => array(),
        ),
        "receiptSumFields" => array(
            1 => array(//                "hpp" => "grand total"
            ),
            2 => array(//                "hpp" => "grand total"
            ),
            3 => array(//                "hpp" => "grand total"
            ),
        ),
        "receiptSumFields2" => array(
            1 => array(//                "hpp" => "grand total"
            ),
            2 => array(//                "hpp" => "grand total"
            ),
            3 => array(//                "hpp" => "grand total"
            ),
        ),
        "receiptSumFields3" => array(
            1 => array(
                "nilai" => "total amount",
            ),
            2 => array(
                "nilai" => "total amount",
            ),
            3 => array(
                "nilai" => "total amount",
            ),
        ),

        "reportSumFields" => array(
            "cabang2_id" => "cabang2_nama",
        ),
        "printLocation" => "Printing/viewReceiptProduksi/",
        "receiptInword" => array(
            1 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            2 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            3 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
        ),
    ),
    "7765" => array(
        "receiptTemplate" => array(
            1 => "template/776r.html",
            2 => "template/776.html",
            3 => "template/776.html",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            3 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(),
            2 => array(),
            3 => array(),
        ),
        "headerNota" => array(
            "dtime" => "date",
            "cabang_nama" => "branch",
            "tlp_1" => "phone",
            "alamat_1" => "address",
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang2_nama" => "branch",
            "dtime" => "date",
        ),

        "receiptDetailFields" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "receiptDetailFields2" => array(
            1 => array(
                //                "produk_nama" => "item source name",
                //                "produk_ord_jml" => "qty",
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                //                "produk_nama" => "item source name",
                //                "produk_ord_jml" => "qty",
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                //                "produk_nama" => "item source name",
                //                "produk_ord_jml" => "qty",
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "receiptDetailFields3" => array(
//            1 => array(
//                "nama" => "summary standard cost",
//                "sub_nilai" => "amount",
//            ),
//            2 => array(
//                "nama" => "summary standard cost",
//                "sub_nilai" => "amount",
//            ),
//            3 => array(
//                "nama" => "summary standard cost",
//                "sub_nilai" => "amount",
//            ),
        ),
        "receiptNumFields" => array(
            1 => array(),
            2 => array(),
            3 => array(),
        ),
        "receiptSumFields" => array(
            1 => array(//                "hpp" => "grand total"
            ),
            2 => array(//                "hpp" => "grand total"
            ),
            3 => array(//                "hpp" => "grand total"
            ),
        ),
        "receiptSumFields2" => array(
            1 => array(//                "hpp" => "grand total"
            ),
            2 => array(//                "hpp" => "grand total"
            ),
            3 => array(//                "hpp" => "grand total"
            ),
        ),
        "receiptSumFields3" => array(
            1 => array(
                "nilai" => "total amount",
            ),
            2 => array(
                "nilai" => "total amount",
            ),
            3 => array(
                "nilai" => "total amount",
            ),
        ),

        "reportSumFields" => array(
            "cabang2_id" => "cabang2_nama",
        ),
        "printLocation" => "Printing/viewReceiptProduksi/",
        "receiptInword" => array(
            1 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            2 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            3 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
        ),
    ),
);