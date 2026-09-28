<?php

$config["coTransaksiLayout"] = array(

    //  config produksi (supplies ke finish goods rakitan)
    "776" => array(
        "receiptTemplate"   => array(
            1 => "template/776r.html",
            2 => "template/776.html",
            3 => "template/776.html",
        ),
        "fixedElements"     => array(
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
        "fixedSignatures"   => array(
            1 => array(),
            2 => array(),
            3 => array(),
        ),
        "headerNota"        => array(
            "dtime"       => "date",
            "cabang_nama" => "branch",
            "tlp_1"       => "phone",
            "alamat_1"    => "address",
        ),
        "headerTables"      => array(
            "produk_nama"    => "product name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total"      => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label"  => "activity",
            "nomer"        => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang2_nama" => "branch",
            "dtime"        => "date",
        ),

        "receiptDetailFields"  => array(
            1 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "uom",
                "harga_bom"=>"harga",
                "sub_harga_bom"=>"subtotal",
            ),
            2 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "uom",
                "harga_bom"=>"harga",
                "sub_harga_bom"=>"subtotal",
            ),
            3 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "uom",
                "harga_bom"=>"harga",
                "sub_harga_bom"=>"subtotal",
            ),
        ),
        "receiptDetailFields2" => array(
            1 => array(
                //                "produk_nama" => "item source name",
                //                "produk_ord_jml" => "qty",
                "nama"   => "item source name",
                "jml"    => "qty",
                "satuan" => "uom",
                "nilai_supplies"     => "harga",
                "sub_nilai_supplies" => "sub harga",
            ),
            2 => array(
                //                "produk_nama" => "item source name",
                //                "produk_ord_jml" => "qty",
                "nama"               => "item source name",
                "jml"                => "qty",
                "satuan"             => "uom",
                "nilai_supplies"     => "harga",
                "sub_nilai_supplies" => "sub harga",
            ),
            3 => array(
                //                "produk_nama" => "item source name",
                //                "produk_ord_jml" => "qty",
                "nama"               => "item source name",
                "jml"                => "qty",
                "satuan"             => "uom",
                "nilai_supplies"     => "harga",
                "sub_nilai_supplies" => "sub harga",
            ),
        ),
        "receiptDetailFields3" => array(
            1 => array(
                "nama"      => "summary standard cost",
                "sub_nilai" => "amount",
            ),
            2 => array(
                "nama"      => "summary standard cost",
                "sub_nilai" => "amount",
            ),
            3 => array(
                "nama"      => "summary standard cost",
                "sub_nilai" => "amount",
            ),
        ),
        "receiptNumFields"     => array(
            1 => array(),
            2 => array(),
            3 => array(),
        ),
        "receiptSumFields"     => array(
            1 => array(
                "harga_bom"=>"grand total",
            ),
            2 => array(
                "harga_bom"=>"grand total",
            ),
            3 => array(
                "harga_bom"=>"grand total",
            ),
        ),
        "receiptSumFields2"    => array(
            1 => array(
                "nilai_supplies" => "total amount"
            ),
            2 => array(
                "nilai_supplies" => "total amount"
            ),
            3 => array(
                "nilai_supplies" => "total amount"
            ),
        ),
        "receiptSumFields3"    => array(
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
        // "printLocation"   => "Printing/viewReceiptProduksi/",
        "printLocation"   => "Printing/viewReceiptReg/",
        "receiptInword"   => array(
            1 => array(
                "in_word" => array("inWordInd" => "harga_bom"),
            ),
            2 => array(
                "in_word" => array("inWordInd" => "harga_bom"),
            ),
            3 => array(
                "in_word" => array("inWordInd" => "harga_bom"),
            ),
        ),
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
            3 => array("size" => "normal"),
        ),
    ),
);