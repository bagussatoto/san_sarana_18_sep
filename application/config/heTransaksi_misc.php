<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 1/3/2019
 * Time: 8:16 PM
 */

$config['transaksi_returnRoutes'] = array(
    "467" => "967",
    "461" => "961",
    "582" => "982",
);

$config['payment_source'] = array(
    "466" => array(
        2 => array(
            array(
                "label" => "outgoing cash",
                "valueSrc" => "nilai_cash",
                "jenisTarget" => "488",
                "jenisSrc" => "466",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                ),
            ),
        ),
    ),
    "460" => array(
        //step ambil dari source 466 step 3
        3 => array(
            array(
                "label" => "hutang dagang",
                "valueSrc" => "exchange__nilai_credit", // nilai_credit --> nilai dalam rupiah
                "jenisTarget" => "4891",
                "jenisSrc" => "460",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "ppn" => "nilai_tambah_ppn_in",
                    "ppn_status" => "0", // butuh diapprove ppn masukannya...
                    "valasId" => "currencyDetails",
                    "valasLabel" => "currencyDetails__nama",
                    "extern_nilai2" => "currencyDetails__exchange",//kurs simpan sini karena valas_nilai bentrok dengan transaksi
                    "valasTagihan" => "nilai_credit", // nilai dalam valas
                    "valasSisa" => "nilai_credit", // nilai dalam valas
//                    "extLabel" => "vendor",
//                    "extLabel" => "vendor",
                ),
            ),
        ),
    ),
    "467" => array(
        //step ambil dari source 466 step 3
        4 => array(
            array(
                "label" => "hutang dagang",
                "valueSrc" => "nilai_credit",
                "jenisTarget" => "489",
                "jenisSrc" => "467",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "ppn" => "ppn",
                    "ppn_status" => "1", // butuh diapprove ppn masukannya...
                    "dpp_ppn" => "nilai_credit",
//                    "extLabel" => "vendor",
//                    "extLabel" => "vendor",
                    "extern2_id" => "referenceID__2",
                    "extern2_nama" => "referenceNomer__2",
                    "extern2_label" => "reguler",
                ),
            ),
        ),
    ),
    "1467" => array(
        //step ambil dari source 466 step 3
        4 => array(
            array(
                "label" => "hutang dagang",
                "valueSrc" => "nilai_credit",
                "jenisTarget" => "489",
                "jenisSrc" => "1467",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "ppn" => "ppn",
                    "ppn_status" => "1", // butuh diapprove ppn masukannya...
//                    "extLabel" => "vendor",
//                    "extLabel" => "vendor",
                    "extern2_id" => "referenceID__2",
                    "extern2_nama" => "referenceNomer__2",
                    "extern2_label" => "project",
                ),
            ),
        ),
    ),
    "489" => array(
        /*
         * vesi dari realisasi multi GRN
         */
        1 => array(
            array(
                "label" => "ppn realisasi",
                "label_key" => "ppn in realisasi",
                "valueSrc" => "ppn_sudah_faktur",
                "jenisTarget" => "0000",
                "jenisSrc" => "489",
                "externSrc" => array(
                    "id" => "pihakID",
                    "extern_id" => "cabangID",
                    "extern_nama" => "cabangName",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "ppn" => "ppn_sudah_faktur",
//                    "tagihan" => "ppn",//dpp ppn
                    "extern_nilai2" => "dpp_final",//dpp ppn
                    "extern_label2" => "eFaktur",//dpp ppn
                    "extern_date2" => "dateFaktur",//tgl faktur ppn masukan
                    "extern_nama2" => "efakturSource",//nomer grn
                    "npwp" => "vendorDetails__npwp", // npwp
//                    "extLabel" => "vendor",
//                    "extLabel" => "vendor",
                ),
            ),
        ),
    ),
    "111" => array(
        /*
         * vesi dari realisasi multi GRN
         */
        2 => array(
            array(
                "label" => "ppn realisasi",
                "label_key" => "ppn in realisasi",
                "valueSrc" => "ppn_belum_faktur",
                "jenisTarget" => "0000",
                "jenisSrc" => "111",
                "externSrc" => array(
                    "id" => "pihakID",
                    "extern_id" => "cabangID",
                    "extern_nama" => "cabangName",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "ppn" => "ppn_belum_faktur",
//                    "tagihan" => "ppn",//dpp ppn
                    "extern_nilai2" => "dpp_final",//dpp ppn
                    "extern_label2" => "eFaktur",//dpp ppn
                    "extern_date2" => "dateFaktur",//tgl faktur ppn masukan
                    "extern_nama2" => "efakturSource",//nomer grn
                    "npwp" => "vendorDetails__npwp", // npwp
//                    "extLabel" => "vendor",
//                    "extLabel" => "vendor",
                ),
            ),
        ),
        //"bikin payment source ppn masukan yang akan dicomapre dengan ppn keluaran"
        //step ambil dari source 466 step 3
//        5 => array(
//            array(
//                "label" => "ppn realisasi",
//                "label_key" => "ppn in realisasi",
//                "valueSrc" => "ppn_realisasi",
//                "jenisTarget" => "0000",
//                "jenisSrc" => "111",
//                "externSrc" => array(
//                    "id" => "pihakID",
//                    "extern_id" => "cabangID",
//                    "extern_nama" => "cabangName",
//                    "nama" => "pihakName",
//                    "extLabel" => "vendor",
//                    "ppn" => "ppn_realisasi",
////                    "tagihan" => "ppn",//dpp ppn
//                    "extern_nilai2" => "harga",//dpp ppn
//                    "extern_label2" => "eFaktur",//dpp ppn
//                    "extern_date2" => "dateFaktur",//tgl faktur ppn masukan
//                    "extern_nama2" => "efakturSource",//nomer grn
//                    "npwp" => "vendorDetails__npwp", // npwp
////                    "extLabel" => "vendor",
////                    "extLabel" => "vendor",
//                ),
//            ),
//        ),
    ),
    "1111" => array( //"bikin payment source ppn masukan yang akan dicomapre dengan ppn keluaran"
        //step ambil dari source 466 step 3
        5 => array(
            array(
                "label" => "ppn realisasi",
                "label_key" => "ppn in realisasi",
                "valueSrc" => "ppn_realisasi",
                "jenisTarget" => "0000",
                "jenisSrc" => "1111",
                "externSrc" => array(
                    "id" => "pihakID",
                    "extern_id" => "cabangID",
                    "extern_nama" => "cabangName",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "ppn" => "ppn_realisasi",
//                    "tagihan" => "ppn",//dpp ppn
                    "extern_nilai2" => "harga",//dpp ppn
                    "extern_label2" => "eFaktur",//dpp ppn
                    "extern_date2" => "dateFaktur",//tgl faktur ppn masukan
                    "extern_nama2" => "efakturSource",//nomer grn
                    "npwp" => "vendorDetails__npwp", // npwp
//                    "extLabel" => "vendor",
//                    "extLabel" => "vendor",
                ),
            ),
        ),
    ),
    "112" => array( //"bikin payment source ppn masukan yang akan dicomapre dengan ppn keluaran"
        //step ambil dari source 461 step 3
        4 => array(
            array(
                "label" => "ppn realisasi",
                "label_key" => "ppn in realisasi",
                "valueSrc" => "ppn_realisasi",
                "jenisTarget" => "0000",
                "jenisSrc" => "112",
                "externSrc" => array(
                    "id" => "pihakID",
                    "extern_id" => "cabangID",
                    "extern_nama" => "cabangName",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "ppn" => "ppn_realisasi",
//                    "tagihan" => "ppn",//dpp ppn
                    "extern_nilai2" => "harga",//dpp ppn
                    "extern_label2" => "eFaktur",//dpp ppn
                    "extern_date2" => "dateFaktur",//tgl faktur ppn masukan
                    "extern_nama2" => "efakturSource",//nomer grn
                    "npwp" => "vendorDetails__npwp", // npwp
//                    "extLabel" => "vendor",
//                    "extLabel" => "vendor",
                ),
            ),
        ),
    ),
    "113" => array( //"bikin payment source ppn masukan yang akan dicomapre dengan ppn keluaran"
        //step ambil dari source 463 dan 1463 step 4
        4 => array(
            array(
                "label" => "ppn realisasi",
                "label_key" => "ppn in realisasi",
                "valueSrc" => "ppn_realisasi",
                "jenisTarget" => "0000",
                "jenisSrc" => "113",
                "externSrc" => array(
                    "id" => "pihakID",
                    "extern_id" => "cabangID",
                    "extern_nama" => "cabangName",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "ppn" => "ppn_realisasi",
//                    "tagihan" => "ppn",//dpp ppn
                    "extern_nilai2" => "harga",//dpp ppn
                    "extern_label2" => "eFaktur",//dpp ppn
                    "extern_date2" => "dateFaktur",//tgl faktur ppn masukan
                    "extern_nama2" => "efakturSource",//nomer grn
                    "npwp" => "vendorDetails__npwp", // npwp
//                    "extLabel" => "vendor",
//                    "extLabel" => "vendor",
                ),
            ),
        ),
    ),
    "110" => array(
        3 => array(
            array(
                "label" => "ppn out",
                "valueSrc" => "ppn_out_bulat",
                "jenisTarget" => "114",
                "jenisSrc" => "110",
                "externSrc" => array(
                    "id" => "place2ID",
                    "nama" => "place2Name",
                    "extLabel" => "branch",
                    "ppn_approved" => "ppn_out_bulat",//ppn
                    "extern_nilai2" => "nett1_bulat",//dpp
                    "extern_label2" => "eFaktur",//nomer faktur
                    "extern_date2" => "dateFaktur",//tgl faktur
                    "extern2_id" => "referensi_id",//invoice penjualan
                    "extern2_nama" => "efaktur_source",//invoice penjualan
                    "customers_id" => "customerID",//customer penjualan
                    "customers_nama" => "customerName",//customer penjualan
                    "npwp" => "deliveryDetails__npwp",//customer penjualan
                ),
            ),
        ),
    ),
    //konversi supplies ke aset dan penambahan dibuatkan pymsrc untuk setor ppn keluaran
    "7622f" => array(
        4 => array(
            array(
                "label" => "ppn out",
                "valueSrc" => "ppn_realisasi",
                "jenisTarget" => "114",
                "jenisSrc" => "110",
                "externSrc" => array(
                    "id" => "customers_id",
                    "nama" => "customers_nama",
                    "extLabel" => "branch",
                    "ppn_approved" => "ppn_realisasi",//ppn
                    "extern_nilai2" => "dpp_ppn",//dpp
                    "extern_label2" => "eFaktur",//nomer faktur
                    "extern_date2" => "dateFaktur",//tgl faktur
                    "extern2_id" => "currentID",//invoice konversi
                    "extern2_nama" => "efakturSource",//invoice penjualan
                    "customers_id" => "customers_id",//customer penjualan
                    "customers_nama" => "customers_nama",//customer penjualan
                ),
            ),
        ),
    ),
    "7620f" => array(
        3 => array(
            array(
                "label" => "ppn out",
                "valueSrc" => "ppn_realisasi",
                "jenisTarget" => "114",
                "jenisSrc" => "110",
                "externSrc" => array(
                    "id" => "customers_id",
                    "nama" => "customers_nama",
                    "extLabel" => "branch",
                    "ppn_approved" => "ppn_realisasi",//ppn
                    "extern_nilai2" => "dpp_ppn",//dpp
                    "extern_label2" => "eFaktur",//nomer faktur
                    "extern_date2" => "dateFaktur",//tgl faktur
                    "extern2_id" => "currentID",//invoice konversi
                    "extern2_nama" => "efakturSource",//invoice penjualan
                    // "customers_id" => ".-1",//customer penjualan
                    // "customers_nama" => ".PT Indosan Berkat Bersama",//customer penjualan
                    "customers_id" => "customers_id",//customer penjualan
                    "customers_nama" => "customers_nama",//customer penjualan
                ),
            ),
        ),
    ),
    "3113" => array( //"bikin payment source ppn masukan yang akan dicomapre dengan ppn keluaran"
        //step ambil dari source 463 dan 1463 step 4
        4 => array(
            array(
                "label" => "ppn realisasi",
                "valueSrc" => "ppn_realisasi",
                "label_key" => "ppn in realisasi",
                "jenisTarget" => "0000",
                "jenisSrc" => "3113",
                "externSrc" => array(
                    "id" => "pihakID",
                    "extern_id" => "cabangID",
                    "extern_nama" => "cabangName",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "ppn" => "ppn_realisasi",
//                    "tagihan" => "ppn",//dpp ppn
                    "extern_nilai2" => "dppPPn",//dpp ppn
                    "extern_label2" => "eFaktur",//dpp ppn
                    "extern_date2" => "dateFaktur",//tgl faktur ppn masukan
                    "extern_nama2" => "efakturSource",//nomer grn
                    "npwp" => "vendorDetails__npwp", // npwp
//                    "extLabel" => "vendor",
//                    "extLabel" => "vendor",
                ),
            ),
        ),
    ),

//    "461r" => array(
//        2 => array(
//            array(
//                "label" => "outgoing cash",
//                "valueSrc" => "nilai_cash",
//                "jenisTarget" => "486",
//                "jenisSrc" => "461r",
//                "externSrc" => array(
//                    "id" => "pihakID",
//                    "nama" => "pihakName",
//                    "extLabel" => "vendor",
//                ),
//            ),
//        ),
//    ),
    "463o" => array(
//        2 => array(
//            array(
//                "label" => "outgoing cash",
//                "valueSrc" => "nilai_cash",
//                "jenisTarget" => "485",
//                "jenisSrc" => "463o",
//                "externSrc" => array(
//                    "id" => "pihakID",
//                    "nama" => "pihakName",
//                    "extLabel" => "vendor",
//                    "extern_nilai2" => "harga_disc",
//                    "ppn" => "ppn",
//                    "ppn_status" => "1", // butuh diapprove ppn masukannya...
//                ),
//            ),
//        ),
    ),
    "1463o" => array(
        2 => array(
            array(
                "label" => "outgoing cash",
                "valueSrc" => "nilai_cash",
                "jenisTarget" => "485",
                "jenisSrc" => "1463o",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "extern_nilai2" => "harga_disc",
                    "ppn" => "ppn",
                    "ppn_status" => "1", // butuh diapprove ppn masukannya...
                ),
            ),
        ),
    ),
    "461" => array(
        3 => array(
            array(
                "label" => "hutang dagang",
//                "valueSrc" => "nilai_credit",
                "valueSrc" => "harga_disc",
                "jenisTarget" => "487",
                "jenisSrc" => "461",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "ppn" => "ppn",
                    "ppn_status" => "1", // butuh diapprove ppn masukannya...
                    "dpp_ppn" => "harga_disc",
                    "extern2_id" => "referenceID__2",
                    "extern2_nama" => "referenceNomer__2",
                    "extern2_label" => "reguler",
                ),
            ),
        ),
    ),
    "463" => array(
        3 => array(
            array(
                "label" => "hutang biaya",
                "valueSrc" => "nilai_credit",
                "jenisTarget" => "462",
                "jenisSrc" => "463",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
//                    "extern_nilai2" => "dppPPh",//ini untuk dpp pph update versi baru
                    "extern_nilai2" => "dppPPh",//ini untuk dpp pph
                    "extern_nilai5" => "dppPPh21",//ini untuk dpp pph21
//                    "extern_nilai2" => "harga_disc",//ini untuk dpp pph
                    "ppn" => "ppn",
                    "extern_nilai3" => "dppPPn",//untuk nyimpen dpp ppn
                    "ppn_status" => "1", // butuh diapprove ppn masukannya...
                    "extern2_id" => "pph23MethodPotongan",//dipotong/tidak
                    "extern2_nama" => "pph23MethodPotongan__name",
                ),
            ),
        ),
    ),
    "1463" => array(
        3 => array(
            array(
                "label" => "hutang biaya",
                "valueSrc" => "nilai_credit",
                "jenisTarget" => "1462",
                "jenisSrc" => "1463",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "extern_nilai2" => "harga_disc",
                    "extern_nilai5" => "dppPPh21",//ini untuk dpp pph21
                    "ppn" => "ppn",
                    "extern_nilai3" => "nilai_dpp_ppn",
                    "ppn_status" => "1", // butuh diapprove ppn masukannya...
                    "extern2_id" => "pph23MethodPotongan",//dipotong/tidak
                    "extern2_nama" => "pph23MethodPotongan__name",
                ),
            ),
        ),
    ),
    "3463" => array(
        3 => array(
            array(
                "label" => "hutang dagang",
                "valueSrc" => "nilai_credit",
                "jenisTarget" => "483",
                "jenisSrc" => "3463",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "extern_nilai2" => "dppPPh",//ini untuk dpp pph
                    "extern_nilai5" => "dppPPh",//ini untuk dpp pph21
                    "ppn" => "ppn",
                    "extern_nilai3" => "dppPPn",//untuk nyimpen dpp ppn
                    "ppn_status" => "1", // butuh diapprove ppn masukannya...
                    "extern2_id" => "pph23MethodPotongan",//dipotong/tidak
                    "extern2_nama" => "pph23MethodPotongan__name",
                ),
            ),
        ),
    ),
    "462" => array(
        1 => array(
            array(
                "label" => "hutang pph23",
                "valueSrc" => "pph23_nilai",
                "jenisTarget" => "115",
                "jenisSrc" => "462",
                "model" => "MdlTaxesStatic",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                    "extern_id" => "pihakID",
                    "extern_nama" => "pihakName",
                    "npwp" => "vendorDetails__npwp",
                    "extern_nilai2" => "source_dpp",
                ),
            ),
            array(
                "label" => "hutang pph 21",
                "label_key" => "hutang pph21",
                "valueSrc" => "pph21_nilai",
                "jenisTarget" => "1483",
                "jenisSrc" => "462",
                "model" => "MdlTaxesStatic",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                    "extern_id" => "pihakID",
                    "extern_nama" => "pihakName",
                    "npwp" => "vendorDetails__npwp",
                    "extern_nilai2" => "source_dpp",
                    "extern_nilai5" => "source_dpp",
                    "extern2_id" => "2",
                    "extern2_nama" => "supplier",
                ),
            ),
        ),
    ),
    "483" => array(
        1 => array(
            array(
                "label" => "hutang pph23",
                "valueSrc" => "pph23_nilai",
                "jenisTarget" => "115",
                "jenisSrc" => "483",
                "model" => "MdlTaxesStatic",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                    "extern_id" => "pihakID",
                    "extern_nama" => "pihakName",
                    "npwp" => "vendorDetails__npwp",
                    "extern_nilai2" => "source_dpp",
                ),
            ),
            array(
                "label" => "hutang pph 21",
                "label_key" => "hutang pph21",
                "valueSrc" => "pph21_nilai",
                "jenisTarget" => "1483",
                "jenisSrc" => "483",
                "model" => "MdlTaxesStatic",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                    "extern_id" => "pihakID",
                    "extern_nama" => "pihakName",
                    "npwp" => "vendorDetails__npwp",
                    "extern_nilai2" => "source_dpp",
                    "extern_nilai5" => "source_dpp",
                    "extern2_id" => "2",
                    "extern2_nama" => "supplier",
                ),
            ),
        ),
    ),
    "1462" => array(
        1 => array(
            array(
                "label" => "hutang pph23",
                "valueSrc" => "pph23_nilai",
                "jenisTarget" => "115",
                "jenisSrc" => "1462",
                "model" => "MdlTaxesStatic",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                    "extern_id" => "pihakID",
                    "extern_nama" => "pihakName",
                    "npwp" => "vendorDetails__npwp",
                    "extern_nilai2" => "extern_nilai2",
                    "extern_nilai5" => "source_dpp",
                ),
            ),
            array(
                "label" => "hutang pph 21",
                "label_key" => "hutang pph21",
                "valueSrc" => "pph21_nilai",
                "jenisTarget" => "1483",
                "jenisSrc" => "1462",
                "model" => "MdlTaxesStatic",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                    "extern_id" => "pihakID",
                    "extern_nama" => "pihakName",
                    "npwp" => "vendorDetails__npwp",
                    "extern_nilai2" => "source_dpp",
                    "extern_nilai5" => "source_dpp",
                    "extern2_id" => "2",
                    "extern2_nama" => "supplier",
                ),
            ),
        ),
    ),
    "485" => array(
        1 => array(
            array(
                "label" => "hutang pph23",
                "valueSrc" => "pph23_nilai",
                "jenisTarget" => "115",
                "jenisSrc" => "485",
                "model" => "MdlTaxesStatic",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                    "extern_id" => "pihakID",
                    "extern_nama" => "pihakName",
                    "npwp" => "vendorDetails__npwp",
                    "extern_nilai2" => "source_dpp",
                ),
            ),
        ),
    ),
    "672" => array(
        2 => array(
            array(
                "label" => "refill pettycash",
                "valueSrc" => "harga",
                "jenisTarget" => "771",
                "jenisSrc" => "672",
                "externSrc" => array(
                    "id" => "cabang2ID",
                    "nama" => "cabang2Name",
                    "extLabel" => "vendor",
//                    "jenis" => "extern_label2",
                    "extern_label2" => "pihakMainName",
                ),
            ),
        ),
    ),
    "1672" => array(
        2 => array(
            array(
                "label" => "refill pettycash",
                "valueSrc" => "harga",
                "jenisTarget" => "1771",
                "jenisSrc" => "1672",
                "externSrc" => array(
                    "id" => "cabang2ID",
                    "nama" => "cabang2Name",
                    "extLabel" => "vendor",
                    "extern_label2" => "pihakMainName",
                ),
            ),
        ),
    ),
    //--------------------------------------
    "582so" => array(
        2 => array(
            array(
                "label" => "uang muka",
                "valueSrc" => "nilai_cash", // nilai yang dipakai piutang dagang
                "jenisTarget" => "4464",
                "jenisSrc" => "582",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "customer",
                    //-------------
                    "cash_account" => "cash_account",
                    "cash_account_label" => "cash_account__label",
                    //-------------
                    "dpp_ppn" => "dpp_ppn",
                    "ppn" => "ppn",
                    //-------------
                ),
                "addValueValidator" => "new_net1",
            ),
        ),
    ),
    "5822so" => array(
        2 => array(
            array(
                "label" => "uang muka",
                "valueSrc" => "nilai_cash", // nilai yang dipakai piutang dagang
                "jenisTarget" => "4464",
                "jenisSrc" => "5822",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "customer",
                    //-------------
                    "cash_account" => "cash_account",
                    "cash_account_label" => "cash_account__label",
                    //-------------
                    "dpp_ppn" => "dpp_ppn",
                    "ppn" => "ppn",
                    //-------------
                ),
                "addValueValidator" => "new_net1",
            ),
        ),
    ),
    "584so" => array(
        2 => array(
            array(
                "label" => "uang muka",
                "valueSrc" => "nilai_cash", // nilai yang dipakai piutang dagang
                "jenisTarget" => "4464",
                "jenisSrc" => "584",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "customer",
                    //-------------
                    "cash_account" => "cash_account",
                    "cash_account_label" => "cash_account__label",
                    //-------------
                    "dpp_ppn" => "dpp_ppn",
                    "ppn" => "ppn",
                    //-------------
                ),
                "addValueValidator" => "new_net1",
            ),
        ),
    ),
    //tambahan untuk jasa kirim
    "582spd" => array(
        4 => array(
            array(
                "label" => "piutang dagang",
                "valueSrc" => "srcOngkir",
                "jenisTarget" => "2749",
                "jenisSrc" => "582spd",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "customer",
                ),
            ),
            array(
                "label" => "piutang dagang",
                "valueSrc" => "nilai_tambah_2010050_2010050010", // nilai yang dipakai piutang dagang
                "jenisTarget" => "749",
                "jenisSrc" => "582",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "customer",
                    //-------------
                    "dpp_ppn" => "dpp_ppn",
                    "ppn" => "ppn",
                    //-------------
                ),
                "addValueValidator" => "new_net1",
            ),
        ),
    ),
    "5822spd" => array(
        4 => array(
            array(
                "label" => "piutang dagang",
                "valueSrc" => "srcOngkir",
                "jenisTarget" => "2749",
                "jenisSrc" => "5822spd",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "customer",
                ),
            ),
            array(
                "label" => "piutang dagang",
                "valueSrc" => "nilai_tambah_2010050_2010050010", // nilai yang dipakai piutang dagang
                "jenisTarget" => "749",
                "jenisSrc" => "5822",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "customer",
                    //-------------
                    "dpp_ppn" => "dpp_ppn",
                    "ppn" => "ppn",
                    "extern2_id" => "id_master",//id master
                    "extern2_nama" => "nomer_top",//nomer top
                    //-------------
                ),
                "addValueValidator" => "new_net1",
            ),
        ),
    ),
    "584" => array(
        3 => array(
            array(
                "label" => "piutang dagang",
                "valueSrc" => "srcOngkir",
                "jenisTarget" => "2749",
                "jenisSrc" => "584",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "customer",
                ),
            ),
            array(
                "label" => "piutang dagang",
                "valueSrc" => "nilai_tambah_2010050_2010050010", // nilai yang dipakai piutang dagang
                "jenisTarget" => "749",
                "jenisSrc" => "584",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "customer",
                    //-------------
                    "dpp_ppn" => "dpp_ppn",
                    "ppn" => "ppn",
                    //-------------
                ),
                "addValueValidator" => "new_net1",
            ),
        ),
    ),
    //--------------------------------------
    "580so" => array(
        2 => array(
            array(
                "label" => "uang muka",
                "valueSrc" => "nilai_cash", // nilai yang dipakai piutang dagang
                "jenisTarget" => "4464",
                "jenisSrc" => "580",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "customer",
                    //-------------
                    "cash_account" => "cash_account",
                    "cash_account_label" => "cash_account__label",
                    //-------------
                    "dpp_ppn" => "dpp_ppn",
                    "ppn" => "ppn",
                    //-------------
                ),
                "addValueValidator" => "new_net1",
            ),
        ),
    ),
    "580spd" => array(
        4 => array(
            array(
                "label" => "piutang dagang",
                "valueSrc" => "srcOngkir",
                "jenisTarget" => "2749",
                "jenisSrc" => "580spd",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "customer",
                ),
            ),
            array(
                "label" => "piutang dagang",
                "valueSrc" => "nilai_tambah_2010050_2010050010", // nilai yang dipakai piutang dagang
                "jenisTarget" => "749",
                "jenisSrc" => "580",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "customer",
                    //-------------
                    "dpp_ppn" => "dpp_ppn",
                    "ppn" => "ppn",
                    //-------------
                ),
                "addValueValidator" => "new_net1",
            ),
        ),
    ),
    //--------------------------------------
//    "584" => array(
//        3 => array(
//            array(
//                "label" => "hutang setoran jasa",
//                "valueSrc" => "nilai_cash",
//                "jenisTarget" => "759",
//                "jenisSrc" => "584",
//                "externSrc" => array(
//                    "id" => "olehID",
//                    "nama" => "olehName",
//                    "extLabel" => "person",
//                ),
//            ),
//            array(
//                "label" => "piutang dagang jasa",
//                "valueSrc" => "nilai_credit",
//                "jenisTarget" => "1784",
//                "jenisSrc" => "584",
//                "externSrc" => array(
//                    "id" => "pihakID",
//                    "nama" => "pihakName",
//                    "extLabel" => "customer",
//                    "pph_23" => "pph_23",
//                    "extern_nilai2" => "nett1_bulat",
//                    "ppn" => "ppn_out_bulat",
//                ),
//            ),
//        ),
//    ),

    "382" => array(
        5 => array(
            array(
                "label" => "piutang valas",
                "valueSrc" => "tagihan",
                "jenisTarget" => "1749",
                "jenisSrc" => "382",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "customer",
                    "valasId" => "valasDetails",
                    "valasLabel" => "valasDetails__nama",
                    "valasValue" => "valasDetails__exchange",
                    "valasTagihan" => "grand_total_valas",
                    "valasSisa" => "grand_total_valas",
                ),
            ),

        ),
    ),
    "383" => array(
        1 => array(
            array(
                "label" => "hutang setoran",
                "valueSrc" => "grand_total",
                "jenisTarget" => "759",
                "jenisSrc" => "749",
                "externSrc" => array(
                    "id" => "olehID",
                    "nama" => "olehName",
                    "extLabel" => "person",
                ),
            ),

        ),
    ),

    "749" => array(
        1 => array(
            array(
                "label" => "hutang setoran",
                "valueSrc" => "nilai_entry",
                "jenisTarget" => "759",
                "jenisSrc" => "749",
                "externSrc" => array(
                    "id" => "olehID",
                    "nama" => "olehName",
                    "extLabel" => "person",
                    "payment_locked" => "paymentSrcLock",
                    "cash_account" => "cash_account",
                    "cash_account_nama" => "cash_account__nama",
                    "extern2_id" => "pihakID",
                    "extern2_nama" => "pihakName",
                ),
            ),
            //uang muka pph auto terbit di pusat
            array(
                "label" => "uang muka pph23",
                "valueSrc" => "pph23",
                "jenisTarget" => "0000", //
                "jenisSrc" => "4464",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extern_nilai2" => "tagihan",
                    "cabang_id" => "place2ID",
                    "cabang_nama" => "place2Name",
                ),
            ),

        ),
    ),

    "4464" => array(
        1 => array(
            array(
                "label" => "hutang setoran",
                "valueSrc" => "nilai_entry",
                "jenisTarget" => "7759", // aslinya 759, ganti setoran uang muka...
                "jenisSrc" => "4464",
                "externSrc" => array(
                    "id" => "olehID",
                    "nama" => "olehName",
                    "extLabel" => "person",
                    "payment_locked" => "paymentSrcLock",
                    "cash_account" => "cash_account",
                    "cash_account_nama" => "cash_account__nama",
                ),
            ),
            //uang muka pph auto terbit di pusat
            array(
                "label" => "uang muka pph23",
                "valueSrc" => "pph23",
                "jenisTarget" => "0000", //
                "jenisSrc" => "4464",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extern_nilai2" => "tagihan",
                    "cabang_id" => "place2ID",
                    "cabang_nama" => "place2Name",
                ),
            ),
        ),
    ),
    "4465" => array(
        1 => array(
            array(
                "label" => "hutang setoran",
                "valueSrc" => "nett",
                "jenisTarget" => "7759", // aslinya 759, ganti setoran uang muka...
                "jenisSrc" => "4464",
                "externSrc" => array(
                    "id" => "olehID",
                    "nama" => "olehName",
                    "extLabel" => "person",
                    "payment_locked" => "paymentSrcLock",
                    "cash_account" => "cash_account",
                    "cash_account_nama" => "cash_account__nama",
                ),
            ),
            //uang muka pph auto terbit di pusat
//            array(
//                "label" => "uang muka pph23",
//                "valueSrc" => "pph23",
//                "jenisTarget" => "0000", //
//                "jenisSrc" => "4464",
//                "externSrc" => array(
//                    "id" => "pihakID",
//                    "nama" => "pihakName",
//                    "extern_nilai2" => "tagihan",
//                    "cabang_id"=>"place2ID",
//                    "cabang_nama"=>"place2Name",
//                ),
//            ),
        ),
    ),
    "4467" => array(
        1 => array(
            array(
                "label" => "uang muka konsumen",
                "valueSrc" => "nilai_payment_source",
                "jenisTarget" => "04467", // uangmuka ada ppn
                "jenisSrc" => "4467",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extern2_id" => "referensi_so__id",
                    "extern2_nama" => "referensi_so__nomer",
                    "extern_date2" => "referensi_so__fulldate",
                    "extLabel" => "customer",
                    "dpp_ppn" => "dpp_nilai",
                    "ppn" => "ppn",

                ),
            ),
            //uang muka pph auto terbit di pusat
//            array(
//                "label" => "uang muka pph23",
//                "valueSrc" => "pph23",
//                "jenisTarget" => "0000", //
//                "jenisSrc" => "4464",
//                "externSrc" => array(
//                    "id" => "pihakID",
//                    "nama" => "pihakName",
//                    "extern_nilai2" => "tagihan",
//                    "cabang_id"=>"place2ID",
//                    "cabang_nama"=>"place2Name",
//                ),
//            ),
        ),
    ),
    // uangmuka ke supplier dengan ppn
    "464" => array(
        1 => array(
            array(
                "label" => "uang muka supplier",
                "valueSrc" => "um_ppn_nilai",
                "jenisTarget" => "0464", // uangmuka ada ppn
                "jenisSrc" => "464",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extern2_id" => "referensi_so__id",
                    "extern2_nama" => "referensi_so__nomer",
                    "extern_date2" => "referensi_so__fulldate",
                    "extLabel" => "vendor",
                    "dpp_ppn" => "um_ppn_nilai",
                    "ppn" => "ppn_nilai",

                ),
            ),
        ),
    ),
    "464a" => array(
        //"bikin payment source ppn masukan yang akan dicomapre dengan ppn keluaran"
        //step ambil dari source 466 step 3
        2 => array(
            array(
                "label" => "ppn realisasi",
                "label_key" => "ppn in realisasi",
                "valueSrc" => "ppn_realisasi",
                "jenisTarget" => "0000",
                "jenisSrc" => "464",
                "externSrc" => array(
                    "id" => "pihakID",
                    "extern_id" => "cabangID",
                    "extern_nama" => "cabangName",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "ppn" => "ppn_realisasi",
//                    "tagihan" => "ppn",//dpp ppn
                    "extern_nilai2" => "dpp_ppn",//dpp ppn
                    "extern_label2" => "eFaktur",//dpp ppn
                    "extern_date2" => "dateFaktur",//tgl faktur ppn masukan
                    "extern_nama2" => "efakturSource",//nomer grn
                    "npwp" => "vendorDetails__npwp", // npwp
//                    "extLabel" => "vendor",
//                    "extLabel" => "vendor",
                    "extern2_id" => "referensi_so__id",//idpo
                    "extern2_nama" => "referensi_so__nomer",//nomer po
                ),
            ),
        ),
    ),


    "7761" => array(
        1 => array(
            array(
                "label" => "settlement uang muka konsumen (4464)",
                "valueSrc" => "nilai_entry",
                "jenisTarget" => "7760",
                "jenisSrc" => "7761",
                "externSrc" => array(
                    "id" => "olehID",
                    "nama" => "olehName",
                    "extLabel" => "person",
                    "payment_locked" => "paymentSrcLock",
                    "cash_account" => "cash_account",
                    "cash_account_nama" => "cash_account__nama",
                ),
            ),
        ),
    ),

    "1749" => array(
        1 => array(
            array(
                "label" => "hutang setoran",
                "valueSrc" => "nilai_entry",
                "jenisTarget" => "1759",
                "jenisSrc" => "1749",
                "externSrc" => array(
                    "id" => "olehID",
                    "nama" => "olehName",
                    "extLabel" => "person",
                    "valasId" => "valasDetails",
                    "valasLabel" => "valasDetails__nama",
                    "valasValue" => "valasDetails__exchange",
//                    "valasTagihan" => "grand_total_valas",
//                    "valasSisa" => "grand_total_valas",
                    "valasTagihan" => "nilai_entry",
                    "valasSisa" => "nilai_entry",
                ),
            ),
        ),
    ),

    "700" => array(
        1 => array(
            array(
                "label" => "hutang setoran",
                "valueSrc" => "nilai_cash",
                "jenisTarget" => "759",
                "jenisSrc" => "700",
                "externSrc" => array(
                    "id" => "olehID",
                    "nama" => "olehName",
                    "extLabel" => "person",
                    "payment_locked" => "paymentSrcLock",
                    "cash_account" => "cash_account",
                    "cash_account_nama" => "cash_account__nama",
                ),
            ),
//            array(
//                "label" => "setoran tunai",
//                "valueSrc" => "nilai_setoran_tunai",
//                "jenisTarget" => "751",
//                "jenisSrc" => "700",
//                "externSrc" => array(
//                    "id" => "olehID",
//                    "nama" => "olehName",
//                    "extLabel" => "person",
//                ),
//            ),
        ),
    ),
    "7001" => array(
        1 => array(
            array(
                "label" => "hutang setoran",
                "valueSrc" => "nilai_cash",
                "jenisTarget" => "759",
                "jenisSrc" => "7001",
                "externSrc" => array(
                    "id" => "olehID",
                    "nama" => "olehName",
                    "extLabel" => "person",
                    "pph_23" => "pph_23",
                    "bank_id" => "cash_account",
                    "bank_label" => "cash_account_label",
                ),
            ),
        ),
    ),

    "1674" => array(
        2 => array(
            array(
                "label" => "hutang gaji",
                "valueSrc" => "hutang_gaji_main",
                "jenisTarget" => "1485",
                "jenisSrc" => "1674",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                ),
            ),
            array(
                "label" => "hutang pph 21",
                "label_key" => "hutang pph21",
                "valueSrc" => "hutang_pph21_main",
                "jenisTarget" => "1483",
                "jenisSrc" => "1674",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                ),
            ),
            array(
                "label" => "hutang bpjs",
                "valueSrc" => "hutang_bpjs_main",
                "jenisTarget" => "1487",
                "jenisSrc" => "1674",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                ),
            ),
        ),

    ),
    "2674" => array(
        2 => array(
            array(
                "label" => "hutang gaji",
                "valueSrc" => "hutang_gaji_main",
                "jenisTarget" => "1485",
                "jenisSrc" => "2674",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                ),
            ),
            array(
                "label" => "hutang pph 21",
                "label_key" => "hutang pph21",
                "valueSrc" => "hutang_pph21_main",
                "jenisTarget" => "1483",
                "jenisSrc" => "2674",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                ),
            ),
            array(
                "label" => "hutang bpjs",
                "valueSrc" => "hutang_bpjs_main",
                "jenisTarget" => "1487",
                "jenisSrc" => "2674",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                ),
            ),
        ),

    ),
    "588so" => array(
        2 => array(
            array(
                "label" => "piutang dagang",
                "valueSrc" => "harga_nppn",
                "jenisTarget" => "7499",
                "jenisSrc" => "588so",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "customer",
                    "project_id" => "projectID",
                    "project_nama" => "projectName",
                ),
            ),
//            array(
//                "label" => "uang muka",
//                "valueSrc" => "nett1_bulat",
//                "jenisTarget" => "4469",
//                "jenisSrc" => "588",
//                "externSrc" => array(
//                    "id" => "customerDetails",
//                    "nama" => "customerDetails__nama",
//                    "extLabel" => "project",
////                    "ppn" => "nilai_tambah_ppn_in",
////                    "ppn_status" => "1", // butuh diapprove ppn masukannya...
//                    "project_id" => "projectID",
//                    "project_nama" => "projectName",
//                ),
//            ),
        ),
    ),
    "588" => array(
        4 => array(
            array(
                "label" => "hutang garansi",
                "valueSrc" => "garansi_nilai",
                "jenisTarget" => "4889",//
                "jenisSrc" => "588",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "customer",
                    "project_id" => "projectID",
                    "project_nama" => "projectName",
                    "extern_date2" => "dateGaransi",// tanggal masa garansi
                    "extern_nilai2" => "projectHarga",//nilai project
                    "ppn_pph_factor" => "tarifGaransi",//tarif garansi
                ),
            ),
        ),
    ),
    "7499" => array(
        1 => array(
            array(
                "label" => "piutang dagang",
                "valueSrc" => "piutang_dagang",
                "jenisTarget" => "749",//
                "jenisSrc" => "7499",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "customer",
                    "project_id" => "projectID",
                    "project_nama" => "projectName",
                    //-------------
                    "dpp_ppn" => "nilai_entry",
                    "ppn" => "grand_ppn",
                    //-------------
                ),
            ),
            /*
             * perlu ngobrol lagi
             */
            array(
                "label" => "piutang retensi",
                "valueSrc" => "piutang_retensi",
                "jenisTarget" => "7488",//
                "jenisSrc" => "7499",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "customer",
                    "project_id" => "projectID",
                    "project_nama" => "projectName",
                ),
            ),
        ),
    ),


    "114" => array(
        1 => array(
            array(
                "label" => "desposit pajak",
                "valueSrc" => "saldo_deposit",
                "jenisTarget" => "00001",
                "jenisSrc" => "114",
                "method" => "update",
                "externSrc" => array(
                    "id" => "placeID",
                    "nama" => "placeName",
                    "extLabel" => "branch",
//                    "ppn_approved" => "saldo_deposit",//ppn
//                    "extern_nilai2" => "",//dpp
//                    "extern_label2" => "",//nomer faktur
//                    "extern_date2" => "",//tgl faktur
                ),
            ),
        ),
    ),

    //biaya import -- expense
    "651" => array(
        2 => array(
            array(
                "label" => "import expense",
                "valueSrc" => "harga",
                "jenisTarget" => "652",
                "jenisSrc" => "651",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "import expense",
                ),
            ),
        ),
    ),

    "673" => array(
        1 => array(
            array(
                "label" => "hutang biaya",
                "valueSrc" => "harga",
                "jenisTarget" => "473",
                "jenisSrc" => "673",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                ),
            ),
        ),
    ),

    //biaya jasa / imbalan jasa by kategory produksi/umum/usaha
    "119" => array(
        2 => array(
            array(
                "label" => "hutang imbalan jasa",
                "valueSrc" => "nett",
                "jenisTarget" => "2119",
                "jenisSrc" => "119",
                "externSrc" => array(
                    "id" => "placeID",
                    "nama" => "placeName",
                    "extern_nilai2" => "harga",//dpp
                    "extern_label2" => "taxesMethod",//rek name
                    "extern2_nama" => "pihakMainRulesID",//pph 21/23
                    "pph_23" => "ppn", //nilai pajak
                    "extern_jenis" => "pihakMainName",
                    "ppn_pph_faktor" => "ppnPersen",
                ),
            ),
            array(
                "label" => "hutang pph 21",
                "label_key" => "hutang pph21",
                "valueSrc" => "nilai_pph21",
                "jenisTarget" => "1483",
                "jenisSrc" => "119",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                    "extern_nilai2" => "harga",//dpp
                    "extern_label2" => "taxesMethod",//rek name
                    "extern2_nama" => "pihakMainRulesID",//pph 21/23
                    "pph_23" => "ppn", //nilai pajak
                    "extern_jenis" => "pihakMainName",
                    "ppn_pph_faktor" => "ppnPersen",
                ),
            ),
        ),
    ),

    //objek pajak
    "681" => array(
        2 => array(
            array(
                "label" => "objek pajak",
                "valueSrc" => "harga",
                "jenisTarget" => "682",
                "jenisSrc" => "681",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName2",
                    "extLabel" => "asap",
                    "extern2_id" => "supplierID",
                    "extern2_nama" => "supplierNama",
                    "suppliers_id" => "supplierID",
                    "suppliers_nama" => "supplierNama",
                ),
            ),

        ),
    ),
    "5681" => array(
        2 => array(
            array(
                "label" => "objek pajak",
                "valueSrc" => "harga",
                "jenisTarget" => "5682",
                "jenisSrc" => "5681",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName2",
                    "extLabel" => "asap",
                ),
            ),

        ),
    ),

    //===582_ (pakai underscore) berarti dipasang pada 582 step bebas
//    "582_" => array(
//        array(
//            "label" => "incoming cash",
//            "valueSrc" => "nilai_cia",
//            "jenisTarget" => "700",
//            "jenisSrc" => "582_",//===pakai underscore berarti step bebas (tidak bernomor urut)
//            "externSrc" => array(
//                "id" => "pihakID",
//                "nama" => "pihakName",
//                "extLabel" => "customer",
//
//
//            ),
//        ),
//        array(
//            "label" => "incoming cash",
//            "valueSrc" => "dp",
//            "jenisTarget" => "700",
//            "jenisSrc" => "582_",//===pakai underscore berarti step bebas (tidak bernomor urut)
//            "externSrc" => array(
//                "id" => "pihakID",
//                "nama" => "pihakName",
//                "extLabel" => "customer",
//            ),
//        ),
//    ),
    //===584_ (pakai underscore) berarti dipasang pada 584 step bebas
    "584_" => array(
        array(
            "label" => "incoming cash",
            "valueSrc" => "nilai_cia",
            "jenisTarget" => "7001",
            "jenisSrc" => "584_",//===pakai underscore berarti step bebas (tidak bernomor urut)
            "externSrc" => array(
                "id" => "pihakID",
                "nama" => "pihakName",
                "extLabel" => "customer",
                "pph_23" => "pph_23",
                "bank_id" => "cash_account",
                "bank_label" => "cash_account__label",
            ),
        ),
        array(
            "label" => "incoming cash",
            "valueSrc" => "dp",
            "jenisTarget" => "7001",
            "jenisSrc" => "584_",//===pakai underscore berarti step bebas (tidak bernomor urut)
            "externSrc" => array(
                "id" => "pihakID",
                "nama" => "pihakName",
                "extLabel" => "customer",
                "pph_23" => "pph_23",
                "bank_id" => "cash_account",
                "bank_label" => "cash_account__label",
            ),
        ),
    ),
    //===466_ (pakai underscore) berarti dipasang pada 466 step bebas
    "466_" => array(

//        array(
//            "label" => "outgoing cash",
//            "valueSrc" => "nilai_cash",
//            "jenisTarget" => "400",
//            "jenisSrc" => "466_",//===pakai underscore berarti step bebas (tidak bernomor urut)
//            "externSrc" => array(
//                "id" => "pihakID",
//                "nama" => "pihakName",
//                "extLabel" => "supplier",
//            ),
//        ),

    ),
    //===461_ (pakai underscore) berarti dipasang pada 461 step bebas
    "461_" => array(

//        array(
//            "label" => "outgoing cash",
//            "valueSrc" => "nilai_cash",
//            "jenisTarget" => "401",
//            "jenisSrc" => "461_",//===pakai underscore berarti step bebas (tidak bernomor urut)
//            "externSrc" => array(
//                "id" => "pihakID",
//                "nama" => "pihakName",
//                "extLabel" => "supplier",
//            ),
//        ),

    ),
    //===463_ (pakai underscore) berarti dipasang pada 463 step bebas
    "463_" => array(

//        array(
//            "label" => "outgoing cash",
//            "valueSrc" => "nilai_cia",
//            "jenisTarget" => "485",
//            "jenisSrc" => "463_",//===pakai underscore berarti step bebas (tidak bernomor urut)
//            "externSrc" => array(
//                "id" => "pihakID",
//                "nama" => "pihakName",
//                "extLabel" => "supplier",
//            ),
//        ),

    ),

//    "1463o" => array(
//        2 => array(
//            array(
//                "label" => "outgoing cash",
//                "valueSrc" => "nilai_cash",
//                "jenisTarget" => "485",
//                "jenisSrc" => "1463o",
//                "externSrc" => array(
//                    "id" => "pihakID",
//                    "nama" => "pihakName",
//                    "extLabel" => "vendor",
//                    "extern_nilai2" => "harga_disc",
//                    "ppn" => "ppn",
//                    "ppn_status" => "1", // butuh diapprove ppn masukannya...
//                ),
//            ),
//        ),
//
//    ),

    //config biaya umum
    "2675" => array(
        2 => array(
            array(
                "label" => "hutang biaya umum",
                "valueSrc" => "harga",
                "jenisTarget" => "475",
                "jenisSrc" => "2675",
                "externSrc" => array(
                    "id" => "cabang2ID",
                    "nama" => "cabang2Name",
                    "extLabel" => "asap",
                ),
            ),
        ),
    ),
    "2676" => array(
        2 => array(
            array(
                "label" => "hutang biaya produksi",
                "valueSrc" => "harga",
                "jenisTarget" => "476",
                "jenisSrc" => "2676",
                "externSrc" => array(
                    "id" => "cabang2ID",
                    "nama" => "cabang2Name",
                    "extLabel" => "asap",
                ),
            ),
        ),
    ),
    "2677" => array(
        2 => array(
            array(
                "label" => "hutang biaya usaha",
                "valueSrc" => "harga",
                "jenisTarget" => "477",
                "jenisSrc" => "2677",
                "externSrc" => array(
                    "id" => "place2ID",
                    "nama" => "place2Name",
                    "extern_id" => "place2ID",
                    "extern_nama" => "place2Name",
                    "extLabel" => "asap",
                ),
            ),
        ),
    ),
    "1675r" => array(
        1 => array(
            array(
                "label" => "hutang biaya umum",
                "valueSrc" => "harga",
                "jenisTarget" => "1475",
                "jenisSrc" => "1675",
                "externSrc" => array(
                    "id" => "cabang2ID",
                    "nama" => "cabang2Name",
                    "extLabel" => "asap",
                ),
            ),
        ),
    ),
    "1676" => array(
        2 => array(
            array(
                "label" => "hutang biaya produksi",
                "valueSrc" => "harga",
                "jenisTarget" => "1476",
                "jenisSrc" => "1676",
                "externSrc" => array(
                    "id" => "cabang2ID",
                    "nama" => "cabang2Name",
                    "extLabel" => "asap",
                ),
            ),
        ),
    ),
    "1677r" => array(
        1 => array(
            array(
                "label" => "hutang biaya usaha",
                "valueSrc" => "harga",
                "jenisTarget" => "1477",
                "jenisSrc" => "1677",
                "externSrc" => array(
                    "id" => "cabang2ID",
                    "nama" => "cabang2Name",
                    "extLabel" => "asap",
                ),
            ),
        ),
    ),
    "4675" => array(
        2 => array(
            array(
                "label" => "hutang biaya",
                "valueSrc" => "harga",
                "jenisTarget" => "6475",
                "jenisSrc" => "4675",
                "externSrc" => array(
                    "id" => "cabang2ID",
                    "nama" => "cabang2Name",
                    "extLabel" => "asap",
                ),
            ),
        ),
    ),

    // CIA SEWA
    "424" => array(
//        2 => array(
//            array(
//                "label" => "outgoing cash",
//                "valueSrc" => "nilai_cash",
//                "jenisTarget" => "4241",
//                "jenisSrc" => "424",
//                "externSrc" => array(
//                    "id" => "pihakID",
//                    "nama" => "pihakName",
//                    "extLabel" => "vendor",
//                ),
//            ),
//        ),
    ),

    //config sewa
    "425" => array(
        3 => array(
            array(
                "label" => "hutang sewa",
                "valueSrc" => "nilai_credit",
                "jenisTarget" => "1424",
                "jenisSrc" => "424",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "ppn" => "ppn_out_bulat",//ppn
                    "extern_nilai2" => "nett1_bulat",//dpp pph
                    "pph_23" => "pph_value",
                    "ppn_pph_faktor" => "tarif_pph",
                    "extern_nilai3" => "nett1_bulat",//dpp ppn
                    "extern_jenis" => "pphGate",//tipe hutang pph pph23/pph4 2
                    "extern2_nama" => "pihakMainName",//tipe hutang pph pph23/pph4 2
                ),
            ),
            array(
                "label" => "{pphGate}",//contoh lihat di modul assetmanagenment controller followup bagian paymentsource
                "valueSrc" => "pph_value",
                "jenisTarget" => "{extern_target_jenis}",
                "jenisSrc" => "424",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "extern_nilai2" => "harga",//dpp pph
                    "pph_23" => "pph_value",
                    "target_jenis" => "extern_target_jenis",
                    "reference_jenis" => "jenisTr",
                    "ppn_pph_faktor" => "tarif_pph",//dpp ppn
                    "extern_jenis" => "pphGate",//tipe hutang pph pph23/pph4 2
                    "extern2_nama" => "pihakMainName",//tipe hutang pph pph23/pph4 2
                ),
            ),
        ),
    ),

    //config pembelian aset
    "423" => array(
        3 => array(
            array(
                "label" => "hutang aktiva tetap",
                "valueSrc" => "nilai_credit",
                "jenisTarget" => "4821",
                "jenisSrc" => "423",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "extern_nilai2" => "harga",//dpp
                    "extern_nilai4" => "other",
//                    "extern_label2" =>"taxesMethod",//rek name
                    "extern2_nama" => "pihakMainRulesID",//pph 21/23
//                    "pph_23" => "ppn", //nilai pajak
                    "ppn" => "ppn", //nilai pajak
//                    "extern_jenis" =>"pihakMainName",
//                    "ppn_pph_faktor" =>"ppnPersen",
                    "extern_jenis" => "pihakMainID_coa",
                ),
            ),
        ),
    ),

    //config hutang bank
    "444" => array(
        2 => array(
            array(
                "label" => "hutang bank",
                "valueSrc" => "harga",
                "jenisTarget" => "4447",
                "jenisSrc" => "444",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extern2_id" => "pihakID",
                    "extern2_nama" => "pihakName",
                    "extLabel" => "vendor",
                ),
            ),
        ),
    ),
    "448" => array(
        2 => array(
            array(
                "label" => "hutang bank",
                "valueSrc" => "harga",
                "jenisTarget" => "4440",
                "jenisSrc" => "448",
                "externSrc" => array(
                    "id" => "pihakRelId",
                    "nama" => "pihakRelName",
                    "extLabel" => "vendor",
                    "extern2_id" => "pihakID",
                    "extern2_nama" => "pihakName",
                ),
            ),
        ),
    ),
    "4449" => array(
        2 => array(
            array(
                "label" => "hutang biaya bunga",
                "valueSrc" => "nilai_bunga",
                "jenisTarget" => "4410",
                "jenisSrc" => "4449",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "pph_23" => "nilai_pph23",
                    "ppn_pph_faktor" => "pph_nilai",
                    "extern_nilai2" => "nilai_kas_dipakai",
                    "npwp" => "npwp",
                ),
            ),
            array(
                "label" => "hutang pph23",
                "valueSrc" => "nilai_pph23",
                "jenisTarget" => "115",
                "jenisSrc" => "4449",
//                "model" => "MdlTaxesStatic",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "pph_23" => "nilai_pph23",//nilai pph 23
                    "ppn_pph_faktor" => "pph_nilai",//prosentase
                    "extern_nilai2" => "nilai_bunga",//dpp
                    "npwp" => "npwp",
                ),
                //

//                "externSrc" => array(
//                    "id" => "pihakID",
//                    "nama" => "pihakName",
//                    "extLabel" => "vendor",
//                    "extern_id" => "pihakID",
//                    "extern_nama" => "pihakName",
//                    "npwp" => "vendorDetails__npwp",
//                    "extern_nilai2" => "source_dpp",
//                ),
            ),
        ),
    ),
    "4412" => array(
        2 => array(
            array(
                "label" => "hutang biaya bunga",
                "valueSrc" => "nilai_kas_dipakai",
                "jenisTarget" => "4410",
                "jenisSrc" => "4412",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "pph_23" => "nilai_pph23",
                    "ppn_pph_faktor" => "",
                    "extern_nilai_2" => "",
                    "npwp" => "",
                ),
            ),
        ),
    ),
    "5683" => array(
        2 => array(
            array(
                "label" => "hutang pph 29",
                "label_key" => "hutang pph29",
                "valueSrc" => "harga",
                "jenisTarget" => "5684",
                "jenisSrc" => "5683",
                "externSrc" => array(
                    "id" => "placeID",
                    "nama" => "placeName",
                    "extLabel" => "placeName",
                ),
            ),
        ),
    ),
    //pib
    "682" => array( //"bikin payment source ppn masukan yang akan dicomapre dengan ppn keluaran"
        //step ambil dari source 461 step 3
        1 => array(
            array(
                "label" => "pib",
                "label_key" => "pib",
                "valueSrc" => "nilai_entry",
                "jenisTarget" => "0000",
                "jenisSrc" => "682",
                "externSrc" => array(
                    "id" => "pairPihakID",
                    "extern_id" => "pairPihakID",
                    "extern_nama" => "pairPihakName",
                    "nama" => "pairPihakName",
                    "extLabel" => "vendor",
                    "ppn" => "nilai_entry",
                    //                    "tagihan" => "ppn",//dpp ppn
                    "extern_nilai2" => "harga",//dpp ppn
                    "extern_label2" => "eFaktur",//dpp ppn
                    "extern_date2" => "dateFaktur",//tgl faktur ppn masukan
                    "extern_nama2" => "efakturSource",//nomer grn
                    "extern2_id" => "pihakID",
                    "extern2_nama" => "pihakName",
                    "npwp" => "vendorDetails__npwp", // npwp
                    //                    "extLabel" => "vendor",
                    //                    "extLabel" => "vendor",
                ),
            ),
        ),
    ),

//    "588" => array(
//        2 => array(
//            array(
//                "label" => "uang muka",
//                "valueSrc" => "nett1_bulat",
//                "jenisTarget" => "4469",
//                "jenisSrc" => "588",
//                "externSrc" => array(
//                    "id" => "customerDetails",
//                    "nama" => "customerDetails__nama",
//                    "extLabel" => "project",
////                    "ppn" => "nilai_tambah_ppn_in",
////                    "ppn_status" => "1", // butuh diapprove ppn masukannya...
//                ),
//            ),
//        ),
//    ),
);

$config['payment_antiSource'] = array(
//    "967" => array(
//        array(
//            "label" => "hutang dagang",
//            "valueSrc" => "nett",
//            "jenisTarget" => "489",
//            "jenisSrc" => "467",
//            "externSrc" => array(
//                "id" => "pihakID",
//                "nama" => "pihakName",
//                "extLabel" => "vendor",
//            ),
//        ),
//    ),
//    "961" => array(
//        array(
//            "label" => "hutang dagang",
//            "valueSrc" => "nett",
//            "jenisTarget" => "487",
//            "jenisSrc" => "461",
//            "externSrc" => array(
//                "id" => "pihakID",
//                "nama" => "pihakName",
//                "extLabel" => "vendor",
//            ),
//        ),
//    ),

//    "982" => array(
//        array(
//            "label" => "piutang dagang",
//            //            "valueSrc" => "harga_nett3",
//            //            "valueSrc"    => "tagihan",
//            "valueSrc" => "nett2",
//            "jenisTarget" => "749",
//            "jenisSrc" => "582",
//            "externSrc" => array(
//                "id" => "pihakID",
//                "nama" => "pihakName",
//                "extLabel" => "customer",
//            ),
//        ),
//    ),
    "1984" => array(
        array(
            "label" => "piutang dagang jasa",
            //            "valueSrc" => "harga_nett3",
            //            "valueSrc"    => "tagihan",
            "valueSrc" => "nett2",
            "jenisTarget" => "1784",
            "jenisSrc" => "584",
            "externSrc" => array(
                "id" => "pihakID",
                "nama" => "pihakName",
                "extLabel" => "customer",
            ),
        ),
    ),
    "980" => array(
        array(
            "label" => "piutang dagang",
            "valueSrc" => "harga_nett3",
            "jenisTarget" => "749",
            "jenisSrc" => "580",
            "externSrc" => array(
                "id" => "pihakID",
                "nama" => "pihakName",
                "extLabel" => "customer",
            ),
        ),
    ),
);

$config['uang_muka'] = array(
    //ini off pindah ke ComUangMukaSourceDetail karena sumber berasal dari items....subject jenis uang muka, contoh uang muka pembelaian, uang muka asurnsi ....off dulu
    // direvisi balik lagi ke sini sebagai subject vendor
    "464" => array(
        1 => array(
            array(
                "label" => "uang muka",
                "valueSrc" => "um_noppn_nilai",
                "jenisTarget" => "1464", // target tidak dipakai
                "jenisSrc" => "464",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                ),
            ),
        ),
    ),
//    "4464" => array(
//        1 => array(
//            array(
//                "label" => "uang muka",
//                "valueSrc" => "tagihan",
//                "jenisTarget" => "04464", // target tidak dipakai
//                "jenisSrc" => "4464",
//                "externSrc" => array(
//                    "id" => "pihakID",
//                    "nama" => "pihakName",
//                    "extLabel" => "customer",
//                ),
//            ),
//        ),
//    ),

    "4465" => array(
        1 => array(
            array(
                "label" => "uang muka",
                "valueSrc" => "tagihan",
                "jenisTarget" => "04465", // target tidak dipakai
                "jenisSrc" => "4465",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "customer",
                ),
            ),
        ),
    ),
    "4467" => array(
        1 => array(
            array(
                "label" => "uang muka konsumen",
                "valueSrc" => "nilai_uang_muka_source",
                "jenisTarget" => "04467", // uangmuka ada ppn
                "jenisSrc" => "4467",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extern2_id" => "referensi_so__id",
                    "extern2_nama" => "referensi_so__nomer",
                    "extern_date2" => "referensi_so__fulldate",
                    "extLabel" => "customer",

                ),
            ),
        ),
    ),
    "4464" => array(
        1 => array(
            array(
                "label" => "uang muka konsumen",
                "valueSrc" => "deposit_konsumen",
                "jenisTarget" => "04464", // uangmuka ada ppn
                "jenisSrc" => "4464",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
//                    "extern2_id" => "referensi_so__id",
//                    "extern2_nama" => "referensi_so__nomer",
//                    "extern_date2" => "referensi_so__fulldate",
//
                    "extLabel" => "customer",

                ),
            ),
        ),
    ),

//    "4465" => array(
//        2 => array(
//            array(
//                "label" => "uang muka",
//                "valueSrc" => "uang_muka_dpp",
//                "valueSrcPpn" => "uang_muka_ppn",
//                "jenisTarget" => "04465", // target tidak dipakai
//                "jenisSrc" => "4465",
//                "externSrc" => array(
//                    "id" => "pihakID",
//                    "nama" => "pihakName",
//                    "extLabel" => "customer",
//                ),
//            ),
//        ),
//    ),
);

$config['transaksi_createIndex'] = array(
    "749" => "Transaksi/index",
    "1749" => "Transaksi/index",
    "2749" => "Transaksi/index",
    "489" => "Transaksi/index",
    "488" => "Transaksi/index",//FG prepaid
    "486" => "Transaksi/index",//RM prepaid
    "485" => "Transaksi/index",//SERVICE prepaid
    "1485" => "Transaksi/index",//SERVICE prepaid
    "1483" => "Transaksi/index",//SERVICE prepaid
    "1487" => "Transaksi/index",//SERVICE prepaid
    "487" => "Transaksi/index",
    "462" => "Transaksi/index",
    "400" => "Transaksi/index",
    "700" => "Transaksi/index",
    "7001" => "Transaksi/index",
    "759" => "Transaksi/index",
    "771" => "Transaksi/index",
    "1771" => "Transaksi/index",
    "473" => "Transaksi/index",
    "652" => "Transaksi/index",
    "682" => "Transaksi/index",
    "475" => "Transaksi/index",
    "477" => "Transaksi/index",
    "476" => "Transaksi/index",
    "1784" => "Transaksi/index",
    "5682" => "Transaksi/index",
    "4821" => "Transaksi/index",
    "114" => "Transaksi/index",
    "115" => "Transaksi/index",
    //    "1784" => "Transaksi/index",
    "4447" => "Transaksi/index",
    "4448" => "Transaksi/index",
    "4410" => "Transaksi/index",
    "5684" => "Transaksi/index",
    "483" => "Transaksi/index",
    "1424" => "Transaksi/index",
    "4891" => "Transaksi/index",
    "1462" => "Transaksi/index",
    "1120" => "Transaksi/index",
    "2119" => "Transaksi/index",
    "4411" => "Transaksi/index",
    "1475" => "Transaksi/index",
    "6475" => "Transaksi/index",
    "1477" => "Transaksi/index",
    "7499" => "Transaksi/index",
);

//config untuk mengabaikan nulis rekening pembantu jika nilai 0
$config['transaksi_value_required_components'] = array(
    "ComRekeningPembantuSupplies",
    "ComRekeningPembantuSuppliesProses",
    "ComRekeningPembantuBiayaSewa",
    "ComRekeningPembantuSupplier",
    "ComRekeningPembantuProduk",
    "ComRekeningPembantuValas",
    "ComRekeningPembantuKas",
    "ComRekeningPembantuPph",
    "ComRekeningPembantuEkspedisi",
    "ComRekeningPembantuCustomer",
    "ComRekeningPembantuAntarcabang",
    "ComRekening",
    "ComJurnal",
    "ComRekeningPembantuBiayaMain",

    "ComRekeningPembantuBiayaKomposisiProduksi",
    "ComRekeningPembantuBiaya",
    "ComRekeningPembantuBiayaProduksi",
    "ComRekeningPembantuBiayaUmum",
    "ComRekeningPembantuBiayaUsaha",
    "ComRekeningPembantuCustomerValas",
    "ComRekeningPembantuPendapatan",
    "ComRekeningPembantuAktivaTetap",
    "ComRekeningPembantuAkumPenyusutanAktivaTetap",
    "ComRekeningPembantuBiayaJasa",
    "ComRekeningPembantuBiayaOperasional",
    "ComRekeningPembantuEfisiensiBiaya",
    "ComRekeningPembantuEfisiensiBiayaMain",
    "ComRekeningPembantuEfisiensiBiayaSubMain",
    "ComRekeningPembantuAktivaBerwujudMain",//ini tanpa qty hanya value dari supplies ke aktiva
    "ComRekeningPembantuBank",
    "ComRekeningPembantuRelasiRekeningKoran",
    "ComRekeningPembantuRekeningKoran",
    "ComRekeningPembantuRekeningKoranMain",
    "ComRekeningPembantuBiayaMain",
    "ComRekeningPembantuBiaya",
    "ComRekeningPembantuUangMukaMain",
    "ComRekeningPembantuUangMuka",
    "ComRekeningPembantuPphMain",
    "ComRekeningPembantuLRLainlain",
    "ComRekeningPembantuBiayaUsahaMain",

    "ComPaymentUangMukaCustomer",
    "ComPaymentUangMukaSupplier",
    "ComPaymentUangMukaSupplierValas",
    "ComPaymentAntisourceCustomer",
    "ComRekeningPembantuEfisiensiBiayaFaseMain",
    "ComRekeningPembantuEfisiensiBiayaFase",
    "ComRekeningPembantuReseller",
    "ComRekeningPembantuPenjualan",
    "ComRekeningPembantuPenjualanKonsumen",
    "ComRekeningPembantuPenjualanSeller",
    "ComRekeningPembantuHpp",
    "ComRekeningPembantuCustomerDetail",
    "ComTransaksiKreditLimit",

    "ComRekeningPembantuPiutangSupplier",
    "ComRekeningPembantuPiutangSupplierMain",
    "ComRekeningPembantuPiutangSupplierItem",
    "ComRekeningPembantuPiutangSupplierDetail",
    "ComRekeningPembantuPiutangSupplierDetailMain",
    "ComRekeningPembantuPiutangSupplierDetailItem",
    "ComRekeningPembantuPiutangSupplierDetailTransMain",
    "ComRekeningPembantuPiutangSupplierDetailTransItem",
    "ComRekeningPembantuPiutangSupplierDetailTransProdukItem",
    "ComRekeningPembantuCreditNote",
    "ComLockerDiskonValue",
    "ComRekeningPembantuRawMainEfisiensi",//efiesnsi project
    //-----------------------
    "ComRekeningPembantuUangMukaMainReference",
    "ComRekeningPembantuUangMukaMain",
    //-----------------------
);

$config['transaksi_static_columns'] = array(
    "pihakID",
    "cabangID",
    "cabang2ID",
    "placeID",
    "place2ID",
    "gudangID",
    "gudang2ID",
    "customerID",
    "stepCode",
    "olehID",
    "customerID",
    "supplierID",
    "ppnFactor",
    "jenisTr",
    "masterID",

    "id", "jml", "refID",
);

$config['transaksi_global_validationRules'] = array(
    "main" => array(
        "cabangID",
        "placeID",
        "gudangID",
        "olehID",

    ),
    "items" => array(
        "jml",
        "harga",
        "id",

    ),
    "tableIn_master" => array(
        "cabang_id",
        "gudang_id",
        "oleh_id",

    ),
    "tableIn_detail" => array(
        "produk_ord_jml",
        "produk_ord_hrg",
        "produk_id",

    ),
);

$config['heGlobalPopulators'] = array(
    //untuk 1 level array
    "items" => "main",
    //        "items2" => "main",
    "items2_sum" => "main",
    "items3_sum" => "main",
    "items4_sum" => "main",
    "items5_sum" => "main",
    "items6_sum" => "main",
    "items8_sum" => "main",
    "rsltItems" => "main",
    "rsltItems2" => "main",
    "rsltItems3" => "main",
);
$config["GlobalPopulator_sub"] = array(
    //untuk 2 level array
    "items6" => "main",
);

$config['transaksi_itemRecapExceptions'] = array(
    "sellerDcID",
    "sellerDcName",
    "ppnTransaksi",
    "note",
    "supplierID",
    "supplierName",
    "customerID",
    "customerName",
    "id",
    "jml",
    "pihakMainCoa",
    "pihakMainAkum",
    "pihakMainAkumDetails",

    "rekName_2_coa",
    "pihakMainChild_coa",
    "qty",
    "produk_kode",
    "ppnFactor",
    "ppn_persen_dipakai",
    "handler",
    "nama",
    "name",
    "nomer",
    "label",
    "satuan",
    "next_substep_code",
    "next_subgroup_code",
    "sub_step_number",
    "sub_step_current",
    //--ini dari cloner dibaw akesini biar gak direkap
    "olehID",
    "olehName",
    "pihakID",
    "pihakName",
    "pihakMainID_coa",
    "pihakMainID_coa_name",
    "pihakMainAkumID_coa",
    "pihakMainAkumName_coa",
    "pihak2ID",
    "pihak2Name",
    "pihak2Mdl",
    "pihak2Com",
    "pihak2Coa_code",
    "pihak3Coa_code",
    "pihak3ID",
    "pihak3Name",
    "pihak3Mdl",
    "pihak3Com",
    "pihakMainID",
    "pihakMainName",

    "placeID",
    "placeName",
    "cabangID",
    "cabangName",
    "gudangID",
    "gudangName",
    "place2ID",
    "place2Name",
    "cabang2ID",
    "cabang2Name",
    "gudang2ID",
    "gudang2Name",
    "jenisTr",
    "jenisTrMaster",
    "nomer",
    "masterID",
    "referenceID",
    "referenceNumber",
    "referenceDtime",
    "referenceFulldate",
    "referenceCount",
    "referenceID_top",
    "referenceJenis",
    "referenceNomer",
    "referenceNomer_top",
    "pihakExternMasterID",
    "seluruhnya",
    "defWHID",
    "defWHID__label",
    "defWHID__name",
    "valasDetails__exchange",
    "valasDetails",
    "valasID",
    "valasName",
    "valasFactor",
    "pettycash_account",
    "pettycash_account__label",
    "pettycash_account__nama",
    "pettycash_plafon__saldo",
    "ppv_index__nilai",
    "transaksi_id",
    "sent_jml",
    "detilSize",
    "srcPosition",
    "srcAccount",
    "srcRel",
    "costID",
    "costName",

    "costID_1",
    "costID_2",
    "costID_3",
    "costID_4",
    "costID_5",
    "costID_coa",
    "costName_1",
    "costName_2",
    "costName_3",
    "costName_4",
    "costName_5",

    "costIdCoa_1",
    "costIdCoa_2",
    "costIdCoa_3",
    "costIdCoa_4",
    "costIdCoa_5",
    "costNameCoa_1",
    "costNameCoa_2",
    "costNameCoa_3",
    "costNameCoa_4",
    "costNameCoa_5",
    "costNameCoa",
    //------
    "pihakMainName",
    "pihakMainNameCoa",
    "pihakMainName2",
    "pihakMainName2Coa",
    "pihakMainName_rev",
    "pihakMainNameCoa_rev",
    "pihakMainName2_rev",
    "pihakMainName2Coa_rev",
    "comRekName_1_child_coa",
    "cost2IdCoa_1",
    "cost2IdCoa_2",
    "cost2IdCoa_3",
    "cost2IdCoa_4",
    "cost2IdCoa_5",
    "cost2NameCoa_1",
    "cost2NameCoa_2",
    "cost2NameCoa_3",
    "cost2NameCoa_4",
    "cost2NameCoa_5",
    //------
    "efisiensiID_1_coa",
    "efisiensiID_2_coa",
    "efisiensiID_3_coa",
    "efisiensiID_4_coa",
    "efisiensiID_5_coa",
    "cost2ID_1_coa",
    "cost2ID_2_coa",
    "cost2ID_3_coa",
    "cost2ID_4_coa",
    "cost2ID_5_coa",
    "costID_1_coa",
    "costID_2_coa",
    "costID_3_coa",
    "costID_4_coa",
    "costID_5_coa",
    "fase_id",
    //------
    "pihakMainChild",
    "comName_items",
    "extern2_id",
    "extern2_nama",
    "dtaDetail",
    "dtaDetail__label",
    "pph23_nilai",
    "non_pph",
    "reComs",
    "externMain",
    "externMain__label",
    "branchTarget",
    "branchTarget__nama",
    "nilai_dpp_ppn",
    "nilai_bayar",
    "nilai_bayar_valas",
    "valas_nilai_bayar",
    "uangMuka",

    "jenisTr_reference",
    "bunga",

    "comRekName_1_child",
    "comRekName_2_child",
    "comRekName_3_child",
    "rekName_1_child",
    "rekName_2_child",
    "rekName_3_child",
    "rekName1IDChild",
    "rekName2IDChild",
    "rekName3IDChild",
    "branchTarget__nilai_persediaan",
    "branchTarget__placeID",
    "branchTarget__gudangID",
    "sewaPeriode",
    "sewaDtime_start",
    "biayaJasa",
    "biaya_jasa",
    "akun_pph_id",
    "akun_pph_label",
//    "tarif_pph",

    "cashMethode",
    "cashMethodeOption",

//    "pph_persen_ext",
    "pihak2Exchange",
//    "extern_nilai2",//dioffkan karena pph 23 tidak tampil di servive purcahsing A/P ppayment by widi
    "kurs__exchange",
    "kurs_actual",
    "referenceID",

    "sellerID",
    "sellerName",
    "valid_ppn",
    //-----------
    "produkProjek__transaksi_id_app",
    "produkProjek__transaksi_no_app",

    "rel_target_num",
    "targetJenisNextStep",
    "targetJenisLabel",
    "targetJenis",
    "sourceJenis",

    "_stepCode_placeID",
    "_stepCode_olehID",
    "_stepCode_placeID_olehID",
    "_stepCode_placeID_olehID_customerID",
    "_stepCode_customerID",
    "_stepCode_placeID_customerID",
    "_stepCode_olehID_customerID",
    "_stepCode",
    "_stepCode_placeID_olehID_supplierID",
    "_stepCode_supplierID",
    "_stepCode_placeID_supplierID",
    "_stepCode_olehID_supplierID",

    //------------
    "shippingDate",
    "dtime_order",
    "dtime_kirim",
    "dtime_terima",
    "_step_1_nomer",
    "_step_1_olehName",
    "_step_2_nomer",
    "_step_2_olehName",
    "_step_3_nomer",
    "_step_3_olehName",
    "_step_4_nomer",
    "_step_4_olehName",
    "_step_5_nomer",
    "_step_5_olehName",
    "coa_code",
    //------ tambahan data isinya transaksi yang dibatalakan
    "reference_id",
    "reference_nomer",
    "reference_jenis",
    "reference_id_top",
    "reference_nomer_top",
    "reference_jenis_top",
    //------
    "rowPreFifo",
    "referensi_id",
    "referensi_jenis",
    "transaksi_id",
    "transaksi_count",
    "transaksi_jenis_count",

    "bomProdukID",
    "bomProdukNama",
    "bomProdukName",
    "bom_id",
    "bom_nama",
    "fase_id",
    "fase_nama",
    "gudang_source_id",
    "gudang_source_nama",
    "gudang_target_id",
    "gudang_target_nama",
    "currentID",
    "currentNomer",
    "gudangID_produk",
    "gudangName_produk",
    "kode_produksi",
    "serial_bahan_baku",

    "tanggalStart",
    "tenggatWaktu",
    "projectID",
    "projectName",
    "pihakProjekID",
    "pihakProjekMasterID",
    "pihakProjekName",
    "pihakProjekValueSrc",
    "pihakProjekRevertStep",
    "pihakProjekDetailGate",
    "pihakProjekGudangID",
    "pihakProjekGudangName",
    "pihakProjekGudangNama",
    "pihakProjekCustomerID",
    "pihakProjekCustomerNama",
    "pihakProjekStartDtime",
    "pihakProjekEndDtime",
    "pihakProjekWorkOrderID",
    "pihakProjekWorkOrderNama",
    "gudangProjectID",
    "gudangProjectName",
    "gudangProjectNama",
    "pihakProjekWorkorderGudangID",
    "pihakProjekWorkorderGudangNama",
    "pihakProjekWorkorderGudangName",
    "tipePenjualanID",
    "tipePenjualanNama",
    "tipePenjualanLabel",
    "pihakProjekWorkOrderSubID",
    "pihakProjekWorkOrderSubNama",
    "pihakProjekWorkorderSubGudangID",
    "pihakProjekWorkorderSubGudangName",
    "pihakProjekWorkorderSubGudangNama",
    "kompensasiTargetMethod",
    "kompensasiTargetMethod__label",
    "kompensasiTargetMethod__name",
    "kompensasiTargetMethod__coa_code",

    "barcode",
    "part_id_1",
    "part_nama_1",
    "part_barcode_1",
    "part_id_2",
    "part_nama_2",
    "part_barcode_2",
    "heater_id",
    "heater_nama",
    "heater_barcode",
    "outdoor_id",
    "outdoor_nama",
    "outdoor_barcode",
    "outdoor_sku",
    "indoor_sku_1",
    "indoor_sku_2",
    "indoor_sku_3",
    "indoor_sku_4",
    "indoor_id_1",
    "indoor_nama_1",
    "indoor_barcode_1",
    "indoor_id_2",
    "indoor_nama_2",
    "indoor_barcode_2",
    "indoor_id_3",
    "indoor_nama_3",
    "indoor_barcode_3",
    "indoor_id_4",
    "indoor_nama_4",
    "indoor_barcode_4",

    "produk_id",
    "produk_nama",
    "produk_jml",
    "reference_id_top",
    "reference_nomer_top",
    "reference_id",
    "reference_nomer",
    "reference_customers_id",
    "reference_customers_nama",
    "reference_cabang_id",
    "reference_cabang_nama",
    "reference_gudang_id",
    "reference_gudang_nama",
    "reference_gudang_status_id",
    "reference_gudang_status_nama",
    "reference_gudang_status_jenis",
    "reference_salesman_id",
    "reference_salesman_nama",
    "requestReferenceID",
    "requestReferenceNomer",
    "requestReferenceIDTop",
    "requestReferenceNomerTop",
    "requestReferenceJenis",
    "requestReferenceJenisTop",
    "requestReferenceJenisMaster",
    "diskon_id",// jenis diskon
    "diskon_nama",// jenis diskon
    //-----------------------
    "jenis_master",
    "jenis_top",
    "jenis",
    "jenis_label",
    "div_id",
    "div_nama",
    "dtime",
    "fulldate",
    "oleh_id",
    "oleh_nama",
    "customers_id",
    "customers_nama",
    "cabang_id",
    "cabang_nama",
    "transaksi_nilai",
    "transaksi_jenis",
    "keterangan",
    "gudang_id",
    "gudang_nama",
    "seller_id",
    "seller_nama",
    "top",
    "top_nama",
    "tos",
    "tos_nama",
    "referensi_id",
    "referensi_nomer",
    "referensi_jenis",
    "pembayaran",
    "pembayaran_sys",
    "pengirim_id",
    "pengirim_nama",
    "kirim_metode_id",
    "kirim_metode_nama",
    "salesman_id",
    "salesman_nama",
    "gudang_status_id",
    "gudang_status_nama",
    "gudang_status_jenis",
    "transaksi_bruto",
    "transaksi_netto",
//    "hpp",// ini hpp kok masuk pengecualian rekap ???
    "hpp_ppv",
    "hpp_paket",
    "uang_muka_ppn",
    "uang_muka_nilai_non_ppn",
    "credit_note_return",
    "point_nilai",
    "diskon_nilai",
    "diskon_unit",
    "deposit_nilai_in",
    "premi",
    "biaya",
    "biaya_kirim",
    "ppn_nilai",
    "transaksi_net",
    "transaksi_pembulatan",
    "transaksi_bulat",
    "laba_lain_lain",
    "transaksi_dibayar",
    "transaksi_dibayar_return",
    //-----------------------

    "ppnConstanta",
    "ppnConstantaStr",
    "ppnPersenCheck",
    "referenceID__1",
    "referenceNumber__1",
    "referenceNomer__1",
    "referenceDtime__1",
    "referenceFulldate__1",
    "referenceID__2",
    "referenceNumber__2",
    "referenceNomer__2",
    "referenceDtime__2",
    "referenceFulldate__2",
    "referenceID__3",
    "referenceNumber__3",
    "referenceNomer__3",
    "referenceDtime__3",
    "referenceFulldate__3",
    "referenceID__4",
    "referenceNumber__4",
    "referenceNomer__4",
    "referenceDtime__4",
    "referenceFulldate__4",
    "referenceID__5",
    "referenceNumber__5",
    "referenceNomer__5",
    "referenceDtime__5",
    "referenceFulldate__5",
    "description",
    "margin_subsidiary",
); // ke atas
//subitems untuk 2level array /items2,items6,items7
$config['transaksi_subitemRecapExceptions'] = array(
    "sellerDcID",
    "sellerDcName",
    "ppnTransaksi",
    "note",
    "supplierID",
    "supplierName",
    "customerID",
    "customerName",
    "id",
    "jml",
    "pihakMainCoa",
    "pihakMainAkum",
    "pihakMainAkumDetails",

    "rekName_2_coa",
    "pihakMainChild_coa",
    "qty",
    "produk_kode",
    "ppnFactor",
    "ppn_persen_dipakai",
    "handler",
    "nama",
    "name",
    "nomer",
    "label",
    "satuan",
    "next_substep_code",
    "next_subgroup_code",
    "sub_step_number",
    "sub_step_current",
    //--ini dari cloner dibaw akesini biar gak direkap
    "olehID",
    "olehName",
    "pihakID",
    "pihakName",
    "pihakMainID_coa",
    "pihakMainID_coa_name",
    "pihak2ID",
    "pihak2Name",
    "pihak2Mdl",
    "pihak2Com",
    "pihak2Coa_code",
    "pihak3Coa_code",
    "pihak3ID",
    "pihak3Name",
    "pihak3Mdl",
    "pihak3Com",
    "pihakMainID",
    "pihakMainName",

    "placeID",
    "placeName",
    "cabangID",
    "cabangName",
    "gudangID",
    "gudangName",
    "place2ID",
    "place2Name",
    "cabang2ID",
    "cabang2Name",
    "gudang2ID",
    "gudang2Name",
    "jenisTr",
    "jenisTrMaster",
    "nomer",
    "masterID",
    "referenceID",
    "referenceNumber",
    "referenceDtime",
    "referenceFulldate",
    "referenceCount",
    "referenceID_top",
    "referenceJenis",
    "referenceNomer",
    "referenceNomer_top",
    "pihakExternMasterID",
    "seluruhnya",
    "defWHID",
    "defWHID__label",
    "defWHID__name",
    "valasDetails__exchange",
    "valasDetails",
    "valasID",
    "valasName",
    "valasFactor",
    "pettycash_account",
    "pettycash_account__label",
    "pettycash_account__nama",
    "pettycash_plafon__saldo",
    "ppv_index__nilai",
    "transaksi_id",
    "sent_jml",
    "detilSize",
    "srcPosition",
    "srcAccount",
    "srcRel",
    "costID",
    "costName",

    "costID_1",
    "costID_2",
    "costID_3",
    "costID_4",
    "costID_5",
    "costID_coa",
    "costName_1",
    "costName_2",
    "costName_3",
    "costName_4",
    "costName_5",

    "costIdCoa_1",
    "costIdCoa_2",
    "costIdCoa_3",
    "costIdCoa_4",
    "costIdCoa_5",
    "costNameCoa_1",
    "costNameCoa_2",
    "costNameCoa_3",
    "costNameCoa_4",
    "costNameCoa_5",
    "costNameCoa",
    //------
    "pihakMainName",
    "pihakMainNameCoa",
    "pihakMainName2",
    "pihakMainName2Coa",
    "pihakMainName_rev",
    "pihakMainNameCoa_rev",
    "pihakMainName2_rev",
    "pihakMainName2Coa_rev",
    "cost2IdCoa_1",
    "cost2IdCoa_2",
    "cost2IdCoa_3",
    "cost2IdCoa_4",
    "cost2IdCoa_5",
    "cost2NameCoa_1",
    "cost2NameCoa_2",
    "cost2NameCoa_3",
    "cost2NameCoa_4",
    "cost2NameCoa_5",
    "comRekName_1_child_coa",
    //------
    "efisiensiID_1_coa",
    "efisiensiID_2_coa",
    "efisiensiID_3_coa",
    "efisiensiID_4_coa",
    "efisiensiID_5_coa",
    "cost2ID_1_coa",
    "cost2ID_2_coa",
    "cost2ID_3_coa",
    "cost2ID_4_coa",
    "cost2ID_5_coa",
    "costID_1_coa",
    "costID_2_coa",
    "costID_3_coa",
    "costID_4_coa",
    "costID_5_coa",
    "fase_id",
    //------
    "pihakMainChild",
    "comName_items",
    "extern2_id",
    "extern2_nama",
    "dtaDetail",
    "dtaDetail__label",
    "pph23_nilai",
    "non_pph",
    "reComs",
    "externMain",
    "externMain__label",
    "branchTarget",
    "branchTarget__nama",
    "nilai_dpp_ppn",
    "nilai_bayar",
    "nilai_bayar_valas",
    "valas_nilai_bayar",
    "uangMuka",

    "jenisTr_reference",
    "bunga",

    "comRekName_1_child",
    "comRekName_2_child",
    "comRekName_3_child",
    "rekName_1_child",
    "rekName_2_child",
    "rekName_3_child",
    "rekName1IDChild",
    "rekName2IDChild",
    "rekName3IDChild",
    "branchTarget__nilai_persediaan",
    "branchTarget__placeID",
    "branchTarget__gudangID",
    "sewaPeriode",
    "sewaDtime_start",
    "biayaJasa",
    "biaya_jasa",
    "akun_pph_id",
    "akun_pph_label",
//    "tarif_pph",

    "cashMethode",
    "cashMethodeOption",

//    "pph_persen_ext",
    "pihak2Exchange",
//    "extern_nilai2",//dioffkan karena pph 23 tidak tampil di servive purcahsing A/P ppayment by widi
    "kurs__exchange",
    "kurs_actual",
    "referenceID",

    "sellerID",
    "sellerName",
    "valid_ppn",
    //-----------
    "produkProjek__transaksi_id_app",
    "produkProjek__transaksi_no_app",

    "rel_target_num",
    "targetJenisNextStep",
    "targetJenisLabel",
    "targetJenis",
    "sourceJenis",

    "_stepCode_placeID",
    "_stepCode_olehID",
    "_stepCode_placeID_olehID",
    "_stepCode_placeID_olehID_customerID",
    "_stepCode_customerID",
    "_stepCode_placeID_customerID",
    "_stepCode_olehID_customerID",
    "_stepCode",
    "_stepCode_placeID_olehID_supplierID",
    "_stepCode_supplierID",
    "_stepCode_placeID_supplierID",
    "_stepCode_olehID_supplierID",

    //------------
    "shippingDate",
    "dtime_order",
    "dtime_kirim",
    "dtime_terima",
    "_step_1_nomer",
    "_step_1_olehName",
    "_step_2_nomer",
    "_step_2_olehName",
    "_step_3_nomer",
    "_step_3_olehName",
    "_step_4_nomer",
    "_step_4_olehName",
    "_step_5_nomer",
    "_step_5_olehName",
    "coa_code",
    //------ tambahan data isinya transaksi yang dibatalakan
    "reference_id",
    "reference_nomer",
    "reference_jenis",
    "reference_id_top",
    "reference_nomer_top",
    "reference_jenis_top",
    //------
    "rowPreFifo",
    "referensi_id",
    "referensi_jenis",
    "transaksi_id",
    "transaksi_count",
    "transaksi_jenis_count",

    "bomProdukID",
    "bomProdukNama",
    "bomProdukName",
    "bom_id",
    "bom_nama",
    "fase_id",
    "fase_nama",
    "gudang_source_id",
    "gudang_source_nama",
    "gudang_target_id",
    "gudang_target_nama",
    "currentID",
    "currentNomer",
    "gudangID_produk",
    "gudangName_produk",
    "kode_produksi",
    "serial_bahan_baku",

    "tanggalStart",
    "tenggatWaktu",
    "projectID",
    "projectName",
    "pihakProjekID",
    "pihakProjekMasterID",
    "pihakProjekName",
    "pihakProjekValueSrc",
    "pihakProjekRevertStep",
    "pihakProjekDetailGate",
    "pihakProjekGudangID",
    "pihakProjekGudangName",
    "pihakProjekGudangNama",
    "pihakProjekCustomerID",
    "pihakProjekCustomerNama",
    "pihakProjekStartDtime",
    "pihakProjekEndDtime",
    "pihakProjekWorkOrderID",
    "pihakProjekWorkOrderNama",
    "gudangProjectID",
    "gudangProjectName",
    "gudangProjectNama",
    "pihakProjekWorkorderGudangID",
    "pihakProjekWorkorderGudangNama",
    "pihakProjekWorkorderGudangName",
    "tipePenjualanID",
    "tipePenjualanNama",
    "tipePenjualanLabel",
    "pihakProjekWorkOrderSubID",
    "pihakProjekWorkOrderSubNama",
    "pihakProjekWorkorderSubGudangID",
    "pihakProjekWorkorderSubGudangName",
    "pihakProjekWorkorderSubGudangNama",
    "kompensasiTargetMethod",
    "kompensasiTargetMethod__label",
    "kompensasiTargetMethod__name",
    "kompensasiTargetMethod__coa_code",

    "barcode",
    "part_id_1",
    "part_nama_1",
    "part_barcode_1",
    "part_id_2",
    "part_nama_2",
    "part_barcode_2",
    "heater_id",
    "heater_nama",
    "heater_barcode",
    "outdoor_id",
    "outdoor_nama",
    "outdoor_barcode",
    "indoor_id_1",
    "indoor_nama_1",
    "indoor_barcode_1",
    "indoor_id_2",
    "indoor_nama_2",
    "indoor_barcode_2",
    "indoor_id_3",
    "indoor_nama_3",
    "indoor_barcode_3",
    "indoor_id_4",
    "indoor_nama_4",
    "indoor_barcode_4",

    "produk_id",
    "produk_nama",
    "produk_jml",
    "reference_id_top",
    "reference_nomer_top",
    "reference_id",
    "reference_nomer",
    "reference_customers_id",
    "reference_customers_nama",
    "reference_cabang_id",
    "reference_cabang_nama",
    "reference_gudang_id",
    "reference_gudang_nama",
    "reference_gudang_status_id",
    "reference_gudang_status_nama",
    "reference_gudang_status_jenis",
    "reference_salesman_id",
    "reference_salesman_nama",
    "requestReferenceID",
    "requestReferenceNomer",
    "requestReferenceIDTop",
    "requestReferenceNomerTop",
    "requestReferenceJenis",
    "requestReferenceJenisTop",
    "requestReferenceJenisMaster",
    "harga",
    "subtotal",
//    "harga",
    //-----------------------
    "jenis_master",
    "jenis_top",
    "jenis",
    "jenis_label",
    "div_id",
    "div_nama",
    "dtime",
    "fulldate",
    "oleh_id",
    "oleh_nama",
    "customers_id",
    "customers_nama",
    "cabang_id",
    "cabang_nama",
    "transaksi_nilai",
    "transaksi_jenis",
    "keterangan",
    "gudang_id",
    "gudang_nama",
    "seller_id",
    "seller_nama",
    "top",
    "top_nama",
    "tos",
    "tos_nama",
    "referensi_id",
    "referensi_nomer",
    "referensi_jenis",
    "pembayaran",
    "pembayaran_sys",
    "pengirim_id",
    "pengirim_nama",
    "kirim_metode_id",
    "kirim_metode_nama",
    "salesman_id",
    "salesman_nama",
    "gudang_status_id",
    "gudang_status_nama",
    "gudang_status_jenis",
    "transaksi_bruto",
    "transaksi_netto",
    "hpp",
    "hpp_ppv",
    "hpp_paket",
    "uang_muka_ppn",
    "uang_muka_nilai_non_ppn",
    "credit_note_return",
    "point_nilai",
    "diskon_nilai",
    "diskon_unit",
    "deposit_nilai_in",
    "premi",
    "biaya",
    "biaya_kirim",
    "ppn_nilai",
    "transaksi_net",
    "transaksi_pembulatan",
    "transaksi_bulat",
    "laba_lain_lain",
    "transaksi_dibayar",
    "transaksi_dibayar_return",
    //-----------------------

    "ppnConstanta",
    "ppnConstantaStr",
    "ppnPersenCheck",
    "description",
    "margin_subsidiary",

); // ke atas

$config['transaksi_itemPopulateExceptions'] = array(
    "sellerDcID",
    "sellerDcName",
    "ppnTransaksi",
    "note",
    "supplierID",
    "supplierName",
    "customerID",
    "customerName",
    "id",
    //    "jml",
    "pihakMainCoa",
    "pihakMainAkum",
    "pihak2Coa_code",

    "comRekName_2_coa",
//    "rekName_5_coa",
//    "comRekName_5_coa",

    "pihakMainChild_coa",
    "rekName_2_coa",
    "pihak3Coa_code",
    "costID_coa",
    "costNameCoa",
    "pihakMainAkumDetails",
    "pihakMainAkumID_coa",
    "pihakMainAkumName_coa",
    "comRekName_1_child_coa",
    "produk_kode",
    "ppnFactor",
    "ppn_persen_dipakai",
    "handler",
    "nama",
    "name",
    "nomer",
    "label",
    "satuan",
    "next_substep_code",
    "next_subgroup_code",
    "sub_step_number",
    "sub_step_current",
    //--ini dari cloner dibaw akesini biar gak direkap
    "olehID",
    "olehName",
    "pihakID",
    "pihakName",
    "pihakMainID_coa",
    "pihakMainID_coa_name",
    "placeID",
    "placeName",
    "cabangID",
    "cabangName",
    "gudangID",
    "gudangName",
    "place2ID",
    "place2Name",
    "cabang2ID",
    "cabang2Name",
    "gudang2ID",
    "gudang2Name",
    "jenisTr",
    "jenisTrMaster",
    "nomer",
    "masterID",
    "referenceID",
    "referenceNumber",
    "referenceDtime",
    "referenceFulldate",
    "referenceCount",
    "referenceID_top",
    "referenceJenis",
    "referenceNomer",
    "referenceNomer_top",
    "pihakExternMasterID",
    "jenisTr_reference",
    "seluruhnya",
    "valasID",
    "valasName",
    "valasFactor",
    "new_sisa",
    "new_sisa_valas",
    "sent_jml",
    "detilSize",
    "harga_source",
    "pihakMainID",
    "pihakMainName",
    "pihakMainChild",
    "source_ppn_persen",
    "pph_persen_ext",
    "extern2_id",
    "extern2_nama",
    "pph23_nilai",
    "non_pph",
    "reComs",
    "externMain__label",
    "branchTarget",
    "branchTarget__nama",
    "nilaiMasuk",
    "bunga",
    "sewaPeriode",
    "sewaDtime_start",
    "branchTarget__nilai_persediaan",
    "branchTarget__placeID",
    "branchTarget__gudangID",
    "tarif_pph",
    "biayaJasa",
    "biaya_jasa",
    "allow_params_edit",
    "akun_pph_id",
    "akun_pph_label",
    "kurs__exchange",

    "sellerID",
    "sellerName",
    //-------------
    "produkProjek__transaksi_id_app",
    "produkProjek__transaksi_no_app",
    "valid_ppn",
    // "premi",
    // "premi_percent",

    "rel_target_num",
    "targetJenisNextStep",
    "targetJenisLabel",
    "targetJenis",
    "sourceJenis",

    "_stepCode_placeID",
    "_stepCode_olehID",
    "_stepCode_placeID_olehID",
    "_stepCode_placeID_olehID_customerID",
    "_stepCode_customerID",
    "_stepCode_placeID_customerID",
    "_stepCode_olehID_customerID",
    "_stepCode",
    "_stepCode_placeID_olehID_supplierID",
    "_stepCode_supplierID",
    "_stepCode_placeID_supplierID",
    "_stepCode_olehID_supplierID",
    //------------
    "shippingDate",
    "dtime_order",
    "dtime_kirim",
    "dtime_terima",
    "_step_1_nomer",
    "_step_1_olehName",
    "_step_2_nomer",
    "_step_2_olehName",
    "_step_3_nomer",
    "_step_3_olehName",
    "_step_4_nomer",
    "_step_4_olehName",
    "_step_5_nomer",
    "_step_5_olehName",
    "coa_code",
    //------
    "pihakMainName",
    "pihakMainNameCoa",
    "pihakMainName2",
    "pihakMainName2Coa",
    "pihakMainName_rev",
    "pihakMainNameCoa_rev",
    "pihakMainName2_rev",
    "pihakMainName2Coa_rev",
    "cost2IdCoa_1",
    "cost2IdCoa_2",
    "cost2IdCoa_3",
    "cost2IdCoa_4",
    "cost2IdCoa_5",
    "cost2NameCoa_1",
    "cost2NameCoa_2",
    "cost2NameCoa_3",
    "cost2NameCoa_4",
    "cost2NameCoa_5",
    "fase_id",
    //------
    //------
    "efisiensiID_1_coa",
    "efisiensiID_2_coa",
    "efisiensiID_3_coa",
    "efisiensiID_4_coa",
    "efisiensiID_5_coa",
    "cost2ID_1_coa",
    "cost2ID_2_coa",
    "cost2ID_3_coa",
    "cost2ID_4_coa",
    "cost2ID_5_coa",
    "costID_1_coa",
    "costID_2_coa",
    "costID_3_coa",
    "costID_4_coa",
    "costID_5_coa",
    //------
    //------ tambahan data isinya transaksi yang dibatalakan
    "reference_id",
    "reference_nomer",
    "reference_jenis",
    "reference_id_top",
    "reference_nomer_top",
    "reference_jenis_top",
    //------
    "rowPreFifo",

    "referensi_id",
    "referensi_jenis",
    "transaksi_id",
    "transaksi_count",
    "transaksi_jenis_count",

    "bomProdukID",
    "bomProdukNama",
    "bomProdukName",
    "bom_id",
    "bom_nama",
    "fase_id",
    "fase_nama",
    "gudang_source_id",
    "gudang_source_nama",
    "gudang_target_id",
    "gudang_target_nama",
    "currentID",
    "currentNomer",
    "gudangID_produk",
    "gudangName_produk",
    "serial_bahan_baku",

    "tanggalStart",
    "tenggatWaktu",
    "projectID",
    "projectName",
    "pihakProjekID",
    "pihakProjekMasterID",
    "pihakProjekName",
    "pihakProjekValueSrc",
    "pihakProjekRevertStep",
    "pihakProjekDetailGate",
    "pihakProjekGudangID",
    "pihakProjekGudangName",
    "pihakProjekGudangNama",
    "pihakProjekCustomerID",
    "pihakProjekCustomerNama",
    "pihakProjekStartDtime",
    "pihakProjekEndDtime",
    "pihakProjekWorkOrderID",
    "pihakProjekWorkOrderNama",
    "gudangProjectID",
    "gudangProjectName",
    "gudangProjectNama",
    "pihakProjekWorkorderGudangID",
    "pihakProjekWorkorderGudangNama",
    "pihakProjekWorkorderGudangName",
    "tipePenjualanID",
    "tipePenjualanNama",
    "tipePenjualanLabel",
    "pihakProjekWorkOrderSubID",
    "pihakProjekWorkOrderSubNama",
    "pihakProjekWorkorderSubGudangID",
    "pihakProjekWorkorderSubGudangName",
    "pihakProjekWorkorderSubGudangNama",
    "kompensasiTargetMethod",
    "kompensasiTargetMethod__label",
    "kompensasiTargetMethod__name",
    "kompensasiTargetMethod__coa_code",

    "barcode",
    "part_id_1",
    "part_nama_1",
    "part_barcode_1",
    "part_id_2",
    "part_nama_2",
    "part_barcode_2",
    "heater_id",
    "heater_nama",
    "heater_barcode",
    "outdoor_id",
    "outdoor_nama",
    "outdoor_barcode",
    "outdoor_sku",
    "indoor_sku_1",
    "indoor_sku_2",
    "indoor_sku_3",
    "indoor_sku_4",
    "indoor_id_1",
    "indoor_nama_1",
    "indoor_barcode_1",
    "indoor_id_2",
    "indoor_nama_2",
    "indoor_barcode_2",
    "indoor_id_3",
    "indoor_nama_3",
    "indoor_barcode_3",
    "indoor_id_4",
    "indoor_nama_4",
    "indoor_barcode_4",

    "produk_id",
    "produk_nama",
    "produk_jml",
    "reference_id_top",
    "reference_nomer_top",
    "reference_id",
    "reference_nomer",
    "reference_customers_id",
    "reference_customers_nama",
    "reference_cabang_id",
    "reference_cabang_nama",
    "reference_gudang_id",
    "reference_gudang_nama",
    "reference_gudang_status_id",
    "reference_gudang_status_nama",
    "reference_gudang_status_jenis",
    "reference_salesman_id",
    "reference_salesman_nama",
    "requestReferenceID",
    "requestReferenceNomer",
    "requestReferenceIDTop",
    "requestReferenceNomerTop",
    "requestReferenceJenis",
    "requestReferenceJenisTop",
    "requestReferenceJenisMaster",
    "diskon_id",// jenis diskon
    "diskon_nama",// jenis diskon
    //-----------------------
    "jenis_master",
    "jenis_top",
    "jenis",
    "jenis_label",
    "div_id",
    "div_nama",
    "dtime",
    "fulldate",
    "oleh_id",
    "oleh_nama",
    "customers_id",
    "customers_nama",
    "cabang_id",
    "cabang_nama",
    "transaksi_nilai",
    "transaksi_jenis",
    "keterangan",
    "gudang_id",
    "gudang_nama",
    "seller_id",
    "seller_nama",
    "top",
    "top_nama",
    "tos",
    "tos_nama",
    "referensi_id",
    "referensi_nomer",
    "referensi_jenis",
    "pembayaran",
    "pembayaran_sys",
    "pengirim_id",
    "pengirim_nama",
    "kirim_metode_id",
    "kirim_metode_nama",
    "salesman_id",
    "salesman_nama",
    "gudang_status_id",
    "gudang_status_nama",
    "gudang_status_jenis",
    "transaksi_bruto",
    "transaksi_netto",
    "hpp",
    "hpp_ppv",
    "hpp_paket",
    "uang_muka_ppn",
    "uang_muka_nilai_non_ppn",
    "credit_note_return",
    "point_nilai",
    "diskon_nilai",
    "diskon_unit",
    "deposit_nilai_in",
    "premi",
    "biaya",
    "biaya_kirim",
    "ppn_nilai",
    "transaksi_net",
    "transaksi_pembulatan",
    "transaksi_bulat",
    "laba_lain_lain",
    "transaksi_dibayar",
    "transaksi_dibayar_return",
    //-----------------------

    "ppnConstanta",
    "ppnConstantaStr",
    "ppnPersenCheck",
    "referenceID__1",
    "referenceNumber__1",
    "referenceNomer__1",
    "referenceDtime__1",
    "referenceFulldate__1",
    "referenceID__2",
    "referenceNumber__2",
    "referenceNomer__2",
    "referenceDtime__2",
    "referenceFulldate__2",
    "referenceID__3",
    "referenceNumber__3",
    "referenceNomer__3",
    "referenceDtime__3",
    "referenceFulldate__3",
    "referenceID__4",
    "referenceNumber__4",
    "referenceNomer__4",
    "referenceDtime__4",
    "referenceFulldate__4",
    "referenceID__5",
    "referenceNumber__5",
    "referenceNomer__5",
    "referenceDtime__5",
    "referenceFulldate__5",
    "description",
    "margin_subsidiary",
); // ke kanan

$config['transaksi_masterPopulateExceptions'] = array(
    "sellerDcID",
    "sellerDcName",
    "note",
    "olehID",
    "pihakID",
    "placeID",
    "cabangID",
    "gudangID",
    "place2ID",
    "cabang2ID",
    "gudang2ID",
    "comRekName_1_child_coa",


//    "rekName_5_coa",
    "jenisTr",
    "jenisTrMaster",
    "masterID",
    "referenceID",
    "referenceJenis",
    "seluruhnya",
    "ppnFactor",
    "step_number",
    "step_current",
    "next_step_code",
    "next_group_code",
    "transaksi_jenis",
    "jenisTrMaster",
    "jenisTrTop",
    "stepCode",
    "stepNumber",
    "rekName_2_coa",


    //--ini dari cloner dibaw akesini biar gak direkap
    "olehID",
    "olehName",
    "pihakID",
    "pihakName",
    "placeID",
    "placeName",
    "cabangID",
    "cabangName",
    "gudangID",
    "gudangName",
    "place2ID",
    "place2Name",
    "cabang2ID",
    "cabang2Name",
    "gudang2ID",
    "gudang2Name",
    "jenisTr",
    "nomer",
    "masterID",
    "referenceID",
    "referenceNumber",
    "referenceDtime",
    "referenceFulldate",
    "referenceCount",
    "referenceJenis",
    "referenceNomer",
    "new_sisa",
    "new_sisa_valas",
    "ppv_index__nilai",
    "ppn_persen_dipakai",
    "harga_source",
    "extern_nilai2",
    "pph_23",
    "pph_persen_ext",
    "extern2_nama",
    //    "nilai_bayar",
    //    "valasID",
    //    "valasName",
    //    "valasFactor",
    // "premi",
    // "premi_percent",
    "sellerID",
    "sellerName",
    "valid_ppn",
    "coa_code",
    "pihakMainID_coa_name",
    "pihakName_name",
    "pihakMainCoa",
    //------ tambahan data isinya transaksi yang dibatalakan
    "reference_id",
    "reference_nomer",
    "reference_jenis",
    "reference_id_top",
    "reference_nomer_top",
    "reference_jenis_top",
    //------
    "rowPreFifo",

    "bomProdukID",
    "bomProdukNama",
    "bomProdukName",
    "bom_id",
    "bom_nama",
    "fase_id",
    "fase_nama",
    "gudang_source_id",
    "gudang_source_nama",
    "gudang_target_id",
    "gudang_target_nama",
    "currentID",
    "currentNomer",
    "gudangID_produk",
    "gudangName_produk",
    "kode_produksi",
    "serial_bahan_baku",

    "tanggalStart",
    "tenggatWaktu",
    "projectID",
    "projectName",
    "pihakProjekID",
    "pihakProjekMasterID",
    "pihakProjekName",
    "pihakProjekValueSrc",
    "pihakProjekRevertStep",
    "pihakProjekDetailGate",
    "pihakProjekGudangID",
    "pihakProjekGudangName",
    "pihakProjekGudangNama",
    "pihakProjekCustomerID",
    "pihakProjekCustomerNama",
    "pihakProjekStartDtime",
    "pihakProjekEndDtime",
    "pihakProjekWorkOrderID",
    "pihakProjekWorkOrderNama",
    "gudangProjectID",
    "gudangProjectName",
    "gudangProjectNama",
    "pihakProjekWorkorderGudangID",
    "pihakProjekWorkorderGudangNama",
    "pihakProjekWorkorderGudangName",
    "tipePenjualanID",
    "tipePenjualanNama",
    "tipePenjualanLabel",
    "pihakProjekWorkOrderSubID",
    "pihakProjekWorkOrderSubNama",
    "pihakProjekWorkorderSubGudangID",
    "pihakProjekWorkorderSubGudangName",
    "pihakProjekWorkorderSubGudangNama",
    "kompensasiTargetMethod",
    "kompensasiTargetMethod__label",
    "kompensasiTargetMethod__name",
    "kompensasiTargetMethod__coa_code",
    "transaksi_count",
    "transaksi_jenis_count",

    "produk_id",
    "produk_nama",
    "produk_jml",
    "reference_id_top",
    "reference_nomer_top",
    "reference_id",
    "reference_nomer",
    "reference_customers_id",
    "reference_customers_nama",
    "reference_cabang_id",
    "reference_cabang_nama",
    "reference_gudang_id",
    "reference_gudang_nama",
    "reference_gudang_status_id",
    "reference_gudang_status_nama",
    "reference_gudang_status_jenis",
    "reference_salesman_id",
    "reference_salesman_nama",
    "requestReferenceID",
    "requestReferenceNomer",
    "requestReferenceIDTop",
    "requestReferenceNomerTop",
    "requestReferenceJenis",
    "requestReferenceJenisTop",
    "requestReferenceJenisMaster",
    "diskon_id",// jenis diskon
    "diskon_nama",// jenis diskon
    //-----------------------
    "jenis_master",
    "jenis_top",
    "jenis",
    "jenis_label",
    "div_id",
    "div_nama",
    "dtime",
    "fulldate",
    "oleh_id",
    "oleh_nama",
    "customers_id",
    "customers_nama",
    "cabang_id",
    "cabang_nama",
    "transaksi_nilai",
    "transaksi_jenis",
    "keterangan",
    "gudang_id",
    "gudang_nama",
    "seller_id",
    "seller_nama",
    "top",
    "top_nama",
    "tos",
    "tos_nama",
    "referensi_id",
    "referensi_nomer",
    "referensi_jenis",
    "pembayaran",
    "pembayaran_sys",
    "pengirim_id",
    "pengirim_nama",
    "kirim_metode_id",
    "kirim_metode_nama",
    "salesman_id",
    "salesman_nama",
    "gudang_status_id",
    "gudang_status_nama",
    "gudang_status_jenis",
    "transaksi_bruto",
    "transaksi_netto",
    "hpp",
    "hpp_ppv",
    "hpp_paket",
    "uang_muka_ppn",
    "uang_muka_nilai_non_ppn",
    "credit_note_return",
    "point_nilai",
    "diskon_nilai",
    "diskon_unit",
    "deposit_nilai_in",
    "premi",
    "biaya",
    "biaya_kirim",
    "ppn_nilai",
    "transaksi_net",
    "transaksi_pembulatan",
    "transaksi_bulat",
    "laba_lain_lain",
    "transaksi_dibayar",
    "transaksi_dibayar_return",
    //-----------------------

    "ppnConstanta",
    "ppnConstantaStr",
    "ppnPersenCheck",
    "referenceID__1",
    "referenceNumber__1",
    "referenceNomer__1",
    "referenceDtime__1",
    "referenceFulldate__1",
    "referenceID__2",
    "referenceNumber__2",
    "referenceNomer__2",
    "referenceDtime__2",
    "referenceFulldate__2",
    "referenceID__3",
    "referenceNumber__3",
    "referenceNomer__3",
    "referenceDtime__3",
    "referenceFulldate__3",
    "referenceID__4",
    "referenceNumber__4",
    "referenceNomer__4",
    "referenceDtime__4",
    "referenceFulldate__4",
    "referenceID__5",
    "referenceNumber__5",
    "referenceNomer__5",
    "referenceDtime__5",
    "referenceFulldate__5",
    "margin_subsidiary",
);

$config['transaksi_masterToItemCloners'] = array(
    "sellerDcID",
    "sellerDcName",
    "olehID",
    "olehName",
    "sellerID",
    "sellerName",
    "pihakID",
    "pihakName",
    "pihakMainID_coa",
    "pihakMainID_coa_name",
    "pihakMainChild_coa",
    "rekName_2_coa",
//    "comRekName_5_coa",
//    "rekName_5_coa",
    "comRekName_2_coa",

    "jatuh_tempo",
    "supplierID",
    "supplierName",
    "customerID",
    "customerName",
    "nomer_top2",
    "pihakMainAkum",
    "pihakMainAkumDetails",

    "pihak2ID",
    "pihak2Name",
    "pihak2Mdl",
    "pihak2Com",
    "pihak2Coa_code",
    "pihak3Coa_code",
    "pihak3ID",
    "pihak3Name",
    "pihak3Mdl",
    "pihak3Com",

    "pihakMainCoa",
    "pihakMainID",
    "pihakMainName",
    "pihakMainChild",
    "pihakMainName",
    "pihakMainNameCoa",
    "pihakMainName2",
    "pihakMainName2Coa",
    "pihakMainName_rev",
    "pihakMainNameCoa_rev",
    "pihakMainName2_rev",
    "pihakMainName2Coa_rev",
    "pihakMainAkumID_coa",
    "pihakMainAkumName_coa",
    "comRekName_1_child_coa",
    "placeID",
    "placeName",
    "cabangID",
    "cabangName",
    "gudangID",
    "gudangName",
    "place2ID",
    "place2Name",
    "cabang2ID",
    "cabang2Name",
    "gudang2ID",
    "gudang2Name",
    "jenisTr",
    "jenisTrMaster",
    "nomer",
    "transaksi_id",
    "transaksi_no",
    "masterID",
    "referenceID",
    "referenceID_top",
    "referenceJenis",
    "referenceNomer",
    "referenceNumber",
    "referenceDtime",
    "referenceFulldate",
    "referenceCount",
    "referenceNomer_top",
    "pihakExternMasterID",
    "jenisTr_reference",
    "seluruhnya",
    "defWHID",
    "defWHID__label",
    "defWHID__name",
    "valasDetails__exchange",
    "valasDetails",
    "valasFactor",
    "valasID",
    "valasName",
    "ppv_index__nilai",
    //    "nilai_bayar",
    //    "new_sisa",
//    "new_sisa_valas",
//    "bayar_total",
    "pettycash_account",
    "pettycash_account__label",
    "pettycash_account__nama",
    "pettycash_plafon__saldo",
    "ppnFactor",
    "detilSize",

    "srcPosition",
    "srcAccount",
    "srcRel",
    "pph_23",
    "pph23_nilai",
    "non_pph",
    "terbayar_pph23",
    "costID",
    "costName",

    "costID_1",
    "costID_2",
    "costID_3",
    "costID_4",
    "costID_5",
    "costID_coa",

    "costName_1",
    "costName_2",
    "costName_3",
    "costName_4",
    "costName_5",
    "costNameCoa",

    "costIdCoa_1",
    "costIdCoa_2",
    "costIdCoa_3",
    "costIdCoa_4",
    "costIdCoa_5",
    "costNameCoa_1",
    "costNameCoa_2",
    "costNameCoa_3",
    "costNameCoa_4",
    "costNameCoa_5",
    //-----
    "cost2IdCoa_1",
    "cost2IdCoa_2",
    "cost2IdCoa_3",
    "cost2IdCoa_4",
    "cost2IdCoa_5",
    "cost2NameCoa_1",
    "cost2NameCoa_2",
    "cost2NameCoa_3",
    "cost2NameCoa_4",
    "cost2NameCoa_5",
    //-----
    "ppn_persen_dipakai",
    "harga_source",
    //dimatiin karena ppn geser dari item ke main hanya baca dari global aka main karena ada pembulatan dpp ke 1523-->1520
    // "valid_dpp",
    // "valid_ppn",
    "comName_items",
    "dtaDetail",
    "dtaDetail__label",
    "reComs",
    "externMain",
    "externMain__label",
    "branchTarget",
    "branchTarget__nama",
    "nilai_dpp_ppn",
    "uangMuka",
//    "extern2_nama",
    "jenisTr_reference",
    "branchTarget__nilai_persediaan",
    "branchTarget__placeID",
    "branchTarget__gudangID",
    "sewaPeriode",
    "sewaDtime_start",
    "tarif_pph",
    "biayaJasa",
    "biaya_jasa",
    "awal_pinjaman",
    "akun_pph_id",
    "akun_pph_label",
    "pihak2Exchange",
    "kurs__exchange",
    "kurs_actual",
    "cashMethode",
    "cashMethodeOption",
    "referenceID",
    //------------
    "produkProjek__transaksi_id_app",
    "produkProjek__transaksi_no_app",
    //------ tambahan data isinya transaksi yang dibatalakan
    "reference_id",
    "reference_nomer",
    "reference_jenis",
    "reference_id_top",
    "reference_nomer_top",
    "reference_jenis_top",
    //------

    "bomProdukID",
    "bomProdukNama",
    "bomProdukName",
    "bom_id",
    "bom_nama",
    "fase_id",
    "fase_nama",
    "gudang_source_id",
    "gudang_source_nama",
    "gudang_target_id",
    "gudang_target_nama",
    "currentID",
    "currentNomer",
    "gudangID_produk",
    "gudangName_produk",
    "kode_produksi",

    "tanggalStart",
    "tenggatWaktu",
    "projectID",
    "projectName",
    "pihakProjekID",
    "pihakProjekMasterID",
    "pihakProjekName",
    "pihakProjekValueSrc",
    "pihakProjekRevertStep",
    "pihakProjekDetailGate",
    "pihakProjekGudangID",
    "pihakProjekGudangName",
    "pihakProjekGudangNama",
    "pihakProjekCustomerID",
    "pihakProjekCustomerNama",
    "pihakProjekStartDtime",
    "pihakProjekEndDtime",
    "pihakProjekWorkOrderID",
    "pihakProjekWorkOrderNama",
    "gudangProjectID",
    "gudangProjectName",
    "gudangProjectNama",
    "pihakProjekWorkorderGudangID",
    "pihakProjekWorkorderGudangNama",
    "pihakProjekWorkorderGudangName",
    "tipePenjualanID",
    "tipePenjualanNama",
    "tipePenjualanLabel",
    "pihakProjekWorkOrderSubID",
    "pihakProjekWorkOrderSubNama",
    "pihakProjekWorkorderSubGudangID",
    "pihakProjekWorkorderSubGudangName",
    "pihakProjekWorkorderSubGudangNama",
    "kompensasiTargetMethod",
    "kompensasiTargetMethod__label",
    "kompensasiTargetMethod__name",
    "kompensasiTargetMethod__coa_code",
    "transaksi_count",
    "transaksi_jenis_count",

    "reference_id_top",
    "reference_nomer_top",
    "reference_id",
    "reference_nomer",
    "reference_customers_id",
    "reference_customers_nama",
    "reference_cabang_id",
    "reference_cabang_nama",
    "reference_gudang_id",
    "reference_gudang_nama",
    "reference_gudang_status_id",
    "reference_gudang_status_nama",
    "reference_gudang_status_jenis",
    "reference_salesman_id",
    "reference_salesman_nama",
    "requestReferenceID",
    "requestReferenceNomer",
    "requestReferenceIDTop",
    "requestReferenceNomerTop",
    "requestReferenceJenis",
    "requestReferenceJenisTop",
    "requestReferenceJenisMaster",

    "ppnConstanta",
    "ppnConstantaStr",
    "ppnPersenCheck",

    /*
     * ini kerekap di items multi diskon, sehingga diskon id jadi salah!
     * diskon _id dikeluarkan dari auto force item
     * diskon_nama dikeluarkan dari auto force item
     */

//    "diskon_id",// jenis diskon
//    "diskon_nama",// jenis diskon
    "referenceID__1",
    "referenceNumber__1",
    "referenceNomer__1",
    "referenceDtime__1",
    "referenceFulldate__1",
    "referenceID__2",
    "referenceNumber__2",
    "referenceNomer__2",
    "referenceDtime__2",
    "referenceFulldate__2",
    "referenceID__3",
    "referenceNumber__3",
    "referenceNomer__3",
    "referenceDtime__3",
    "referenceFulldate__3",
    "referenceID__4",
    "referenceNumber__4",
    "referenceNomer__4",
    "referenceDtime__4",
    "referenceFulldate__4",
    "referenceID__5",
    "referenceNumber__5",
    "referenceNomer__5",
    "referenceDtime__5",
    "referenceFulldate__5",
    "description",
    "margin_subsidiary",
);
$config['transaksi_fixedItem_subValues'] = array(
    "qty" => "jml",
    "name" => "nama",

);
$config['transaksi_fixedTableIn_subValues'] = array(
    "produk_nama" => "nama",
    "produk_ord_jml" => "jml",
    "valid_qty" => "jml",
);

$config['transaksi_fixedTableIn_values'] = array(
    "cabang_id" => "placeID",
    "cabang_nama" => "placeName",
    "gudang_id" => "gudangID",
    "gudang_nama" => "gudangName",
    "oleh_id" => "olehID",
    "oleh_nama" => "olehName",

);

$config['sessionToGateAlwaysUpdaters'] = array(//==key -> src
    "longitude" => "longitude",
    "lattitude" => "lattitude",
    "accuracy" => "accuracy",
);

$config['heTransaksi_paramPatchers'] = array(
    "RekeningPembantuSupplier" => array("transaksi_id" => "insertID"),
    "RekeningPembantuCustomerValasItem" => array("transaksi_id" => "insertID"),
    "RekeningPembantuSupplierItem" => array("transaksi_id" => "insertID"),
    "RekeningPembantuCustomerItem" => array("transaksi_id" => "insertID"),
    "RekeningPembatuKasItem" => array("transaksi_id" => "insertID"),
    "Rekening" => array("transaksi_id" => "insertID"),
    "RekeningPembantuCustomerValas" => array("transaksi_id" => "insertID"),
    "RekeningPembantuEfisiensi" => array("transaksi_id" => "insertID"),
    "RekeningPembantuEfisiensiBiaya" => array("transaksi_id" => "insertID"),
    "RekeningPembantuEfisiensiBiayaMain" => array("transaksi_id" => "insertID"),
    "RekeningPembantuEfisiensiBiayaSubMain" => array("transaksi_id" => "insertID"),
    "RekeningPembantuEkspedisi" => array("transaksi_id" => "insertID"),
    "RekeningPembantuKas" => array("transaksi_id" => "insertID"),
    "RekeningPembantuProduk" => array("transaksi_id" => "insertID"),
    "RekeningPembantuProdukRiil" => array("transaksi_id" => "insertID"),
    "RekeningPembantuSupplies" => array("transaksi_id" => "insertID"),
    "RekeningPembantuSuppliesProses" => array("transaksi_id" => "insertID"),
    "RekeningPembantuValas" => array("transaksi_id" => "insertID"),
    "RekeningPembantuCustomer" => array("transaksi_id" => "insertID"),
    "RekeningPembantuAktivaTetap" => array("transaksi_id" => "insertID"),
    "RekeningPembantuAktivaTetapTakBerwujud" => array("transaksi_id" => "insertID"),
    "RekeningPembantuAkumPenyusutanAktivaTetap" => array("transaksi_id" => "insertID"),
    "RekeningPembantuAntarcabang" => array("transaksi_id" => "insertID"),
    "RekeningPembantuBiayaMain" => array("transaksi_id" => "insertID"),
    "RekeningPembantuBiayaJasa" => array("transaksi_id" => "insertID"),
    "RekeningPembantuBiaya" => array("transaksi_id" => "insertID"),
    "RekeningPembantuBiayaUmum" => array("transaksi_id" => "insertID"),
    "RekeningPembantuBiayaProduksi" => array("transaksi_id" => "insertID"),
    "RekeningPembantuBiayaUsaha" => array("transaksi_id" => "insertID"),
    "RekeningPembantuBiayaKomposisiProduksi" => array("transaksi_id" => "insertID"),
    "RekeningPembantuDepresiasi" => array("transaksi_id" => "insertID"),
    "RekeningPembantuBiayaImport" => array("transaksi_id" => "insertID"),
    "RekeningPembantuRelasiRekeningKoran" => array("transaksi_id" => "insertID"),
    "RekeningPembantuRekeningKoran" => array("transaksi_id" => "insertID"),
    "RekeningPembantuRekeningKoranMain" => array("transaksi_id" => "insertID"),
    "RekeningPembantuBank" => array("transaksi_id" => "insertID"),
    "RekeningPembantuUangMukaMain" => array("transaksi_id" => "insertID"),
    "RekeningPembantuUangMukaExternMain" => array("transaksi_id" => "insertID"),

    "RekeningPembantuAkumPenyusutanBangunan" => array("transaksi_id" => "insertID"),
    "RekeningPembantuAkumPenyusutanKendaraan" => array("transaksi_id" => "insertID"),
    "RekeningPembantuAkumPenyusutanMesinProduksi" => array("transaksi_id" => "insertID"),
    "RekeningPembantuAkumPenyusutanPeralatanKantor" => array("transaksi_id" => "insertID"),
    "RekeningPembantuAkumPenyusutanPeralatanProduksi" => array("transaksi_id" => "insertID"),
    "RekeningPembantusewa" => array("transaksi_id" => "insertID"),
    "RekeningPembantuAktivaBerwujudMain" => array("transaksi_id" => "insertID"),
    "RekeningPembantuHutangPihak3Item" => array("transaksi_id" => "insertID"),
    "RekeningPembantuPph" => array("transaksi_id" => "insertID"),
    "RekeningPembantuBiayaUsahaMain" => array("transaksi_id" => "insertID"),
    "RekeningPembantuHutangSaham" => array("transaksi_id" => "insertID"),
    "RekeningPembantuHutangPihakLain" => array("transaksi_id" => "insertID"),
    "ManufacturIdentity" => array("transaksi_id" => "insertID"),

    //
    "FifoSupplies" => array("transaksi_id" => "insertID"),
    "FifoProdukJadi" => array("transaksi_id" => "insertID"),
    "FifoProdukJadiRakitan" => array("transaksi_id" => "insertID"),
    "FifoValas" => array("transaksi_id" => "insertID"),
    "Jurnal" => array("transaksi_id" => "insertID"),
    "JurnalItem" => array("transaksi_id" => "insertID"),
    "JurnalValuesItem" => array("transaksi_id" => "insertID"),
    "RekeningItem" => array("transaksi_id" => "insertID"),
    "Jurnal_activity" => array("transaksi_id" => "insertID"),
    "Jurnal_activityMain" => array("transaksi_id" => "insertID"),
    "Jurnal_activityItem" => array("transaksi_id" => "insertID"),
    #tambahan untuk modul dipasang per modul
    "JurnalModulSales" => array("transaksi_id" => "insertID"),
    "JurnalModulPurcashing" => array("transaksi_id" => "insertID"),
    "JurnalModulDistribution" => array("transaksi_id" => "insertID"),
    "JurnalModulManufactur" => array("transaksi_id" => "insertID"),
    "JurnalModulBanking" => array("transaksi_id" => "insertID"),
    "JurnalModulCash" => array("transaksi_id" => "insertID"),
    "JurnalModulPettycash" => array("transaksi_id" => "insertID"),
    "JurnalModulTax" => array("transaksi_id" => "insertID"),
    "JurnalModulConvert" => array("transaksi_id" => "insertID"),
    "JurnalModulAdjustment" => array("transaksi_id" => "insertID"),
    "JurnalModulAsetmanagement" => array("transaksi_id" => "insertID"),
    #end tambahan modul

    "LockerStockMutasi" => array("transaksi_id" => "insertID"),
    "LockerStockMutasiSupplies" => array("transaksi_id" => "insertID"),
    "LockerStockMutasiSuppliesProses" => array("transaksi_id" => "insertID"),
    "LockerStockMutasiAktiva" => array("transaksi_id" => "insertID"),
    "LockerStockPlafonBankMutasiMain" => array("transaksi_id" => "insertID"),
    "LockerTransaksi" => array("transaksi_id" => "insertID"),
    "PriceSupplies" => array("transaksi_id" => "insertID"),
    "PriceProduk" => array("transaksi_id" => "insertID"),
    "PaymentSourceAntarCabang" => array("transaksi_id" => "insertID"),
    "ProjectSales" => array("transaksi_id" => "insertID"),
    "ProjectSalesMain" => array("transaksi_id" => "insertID"),
    "LockerStockMain" => array("transaksi_id" => "insertID"),
    "TransaksiDataGaransi" => array("transaksi_id" => "insertID"),
    "RekeningPembantuHppProject" => array("transaksi_id" => "insertID"),
    "RekeningPembantuPenjualanProject" => array("transaksi_id" => "insertID"),
    //-----------
    "RekeningTransaksi" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningTransaksiPembantu" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningTransaksiDebet" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    //-----------
    "RekeningPembantuPenjualan" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuPenjualanKonsumen" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuPenjualanSeller" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuLRLainlain" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    //-----------
    "RekeningPembantuSupplierJenis" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuSupplierSubJenis" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    //-----------
    "PaymentUangMukaCustomer" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "PaymentUangMukaSupplier" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "PaymentUangMukaSupplierValas" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "PaymentAntisourceCustomer" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),

    "RekeningPembantuEfisiensiBiayaFaseMain" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuEfisiensiBiayaFase" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningModulSales" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuProdukModulSales" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "ProdukProject" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "ProdukSerialNumber" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),

    "RekeningPembantuReseller" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuPenjualan" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuPenjualanKonsumen" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuPenjualanSeller" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuHpp" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuCustomerDetail" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "TransaksiKreditLimit" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "PaymentSrcMain" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),

    "RekeningPembantuPiutangSupplier" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuPiutangSupplierMain" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuPiutangSupplierItem" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuPiutangSupplierDetail" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuPiutangSupplierDetailMain" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuPiutangSupplierDetailItem" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuPiutangSupplierDetailTransMain" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuPiutangSupplierDetailTransItem" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuPiutangSupplierDetailTransProdukItem" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuCreditNote" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuCreditNoteMain" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuCreditNoteItem" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuCreditNoteDetail" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuCreditNoteDetailMain" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuCreditNoteDetailItem" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "LockerDiskonValue" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuProdukPerSerial" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "ProdukSerialNumber" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuRawMainEfisiensi" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuVoucher" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),

    #pembantu modul pembelian
    "RekeningPembantuTransaksiPembelian" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "TransaksiDataPembelian" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningTransaksiDataPembelianCache" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningTransaksiDataPembelian" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),

    #pembantu modul pembelian Jasa
    "RekeningPembantuTransaksiPembelianJasa" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "TransaksiDataPembelianJasa" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningTransaksiDataPembelianJasaCache" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningTransaksiDataPembelianJasa" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),

    #modul distribusi

    #pembantu modul distribusi
    "RekeningPembantuTransaksiDistribusi" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "TransaksiDataDistribusi" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningTransaksiDataDistribusiCache" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningTransaksiDataDistribusi" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),

    #pembantu modul penjualan
    "RekeningPembantuTransaksiPenjualan" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "TransaksiDataPenjualan" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningTransaksiDataPenjualanCache" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningTransaksiDataPenjualan" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "TransaksiDataPenjualanBridging" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),

    #pembantu modul project
    "RekeningPembantuTransaksiProject" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "TransaksiDataProject" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningTransaksiDataProjectCache" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningTransaksiDataProject" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),

    #pembantu modul taxes
    "RekeningPembantuTransaksiTaxes" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "TransaksiDataTaxes" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningTransaksiDataTaxesCache" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningTransaksiDataTaxes" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),

    #pembantu modul kas
    "RekeningPembantuTransaksiKas" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "TransaksiDataKas" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningTransaksiDataKasCache" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningTransaksiDataKas" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),

    #pembantu modul banking
    "RekeningPembantuTransaksiKas" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "TransaksiDataKas" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningTransaksiDataKasCache" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningTransaksiDataKas" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),

);

$config['heTransaksi_paramForceFillers'] = array(
    "RekeningPembantuSupplier" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuCustomerValasItem" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuSupplierItem" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuCustomerItem" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuKasItem" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "Rekening" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuCustomerValas" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuEfisiensi" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuEfisiensiBiaya" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuEfisiensiBiayaMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuEfisiensiBiayaSubMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuEkspedisi" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuKas" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuProduk" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuProdukRiil" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuSupplies" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuSuppliesProses" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuValas" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuCustomer" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuAktivaTetap" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuAktivaTetapTakBerwujud" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuAkumPenyusutanAktivaTetap" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuAntarcabang" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuBiayaMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuBiayaJasa" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuBiaya" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuBiayaUmum" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuBiayaProduksi" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuBiayaUsaha" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuBiayaKomposisiProduksi" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuDepresiasi" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),

    "RekeningPembantuAkumPenyusutanBangunan" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuAkumPenyusutanKendaraan" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuAkumPenyusutanMesinProduksi" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuAkumPenyusutanPeralatanKantor" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuAkumPenyusutanPeralatanProduksi" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuSewa" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuAktivaBerwujudMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuHutangPihak3Item" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuPph" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuBiayaUsahaMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuUangMukaMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuUangMukaExternMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),

    //
    "Jurnal" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "Neraca" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RugiLaba" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum", "jenis" => "jenis"),
    "JurnalItem" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "JurnalValuesItem" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningItem" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuBiayaImport" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "Jurnal_activity" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "Jurnal_activityMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "Jurnal_activityItem" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    #tambahan untuk modul dipasang per modul
    "JurnalModulSales" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "JurnalModulPurcashing" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "JurnalModulDistribution" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "JurnalModulManufactur" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "JurnalModulBanking" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "JurnalModulCash" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "JurnalModulPettycash" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "JurnalModulTax" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "JurnalModulConvert" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "JurnalModulAdjustment" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "JurnalModulAsetmanagement" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    #end tambahan modul

    "RekeningPembantuRelasiRekeningKoran" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuRekeningKoran" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuRekeningKoranMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuBank" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuHutangSaham" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuHutangPihakLain" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),

    "PaymentSourceAntarCabang" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "LockerStockMutasi" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "LockerStockMutasiSupplies" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "LockerStockMutasiSuppliesProses" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "LockerStockMutasiAktiva" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "LockerStockPlafonBankMutasiMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "LockerTransaksi" => array("transaksi_no" => "insertNum"),
    "PriceSupplies" => array("transaksi_no" => "insertNum"),
    "PriceProduk" => array("transaksi_no" => "insertNum"),
    "FifoSupplies" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "FifoProdukJadi" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "FifoProdukJadiRakitan" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "FifoValas" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "ProjectSales" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "ProjectSalesMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "LockerStockMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "TransaksiDataGaransi" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuHppProject" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuPenjualanProject" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningTransaksi" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    //------
    "RekeningPembantuPenjualan" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuPenjualanKonsumen" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuPenjualanSeller" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuLRLainlain" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    //------
    "RekeningPembantuSupplierJenis" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuSupplierSubJenis" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    //------
    "PaymentUangMukaCustomer" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "PaymentUangMukaSupplier" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "PaymentUangMukaSupplierValas" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "PaymentAntisourceCustomer" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),

    "RekeningPembantuEfisiensiBiayaFaseMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuEfisiensiBiayaFase" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningModulSales" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuProdukModulSales" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "ManufacturIdentity" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "ProdukProject" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),

    "RekeningPembantuReseller" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuHpp" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuCustomerDetail" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "TransaksiKreditLimit" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "PaymentSrcMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),

    "RekeningPembantuPiutangSupplier" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuPiutangSupplierMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuPiutangSupplierItem" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuPiutangSupplierDetail" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuPiutangSupplierDetailMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuPiutangSupplierDetailItem" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuPiutangSupplierDetailTransMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuPiutangSupplierDetailTransItem" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuPiutangSupplierDetailTransProdukItem" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuCreditNote" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuCreditNoteMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuCreditNoteItem" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuCreditNoteDetail" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuCreditNoteDetailMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuCreditNoteDetailItem" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "LockerDiskonValue" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuProdukPerSerial" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "ProdukSerialNumber" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuRawMainEfisiensi" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuVoucher" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),

    #modul pembelian
    "RekeningPembantuTransaksiPembelian" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "TransaksiDataPembelian" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningTransaksiDataPembelianCache" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningTransaksiDataPembelian" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),

    #modul pembelian jasa
    "RekeningPembantuTransaksiPembelianJasa" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "TransaksiDataPembelianJasa" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningTransaksiDataPembelianJasaCache" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningTransaksiDataPembelianJasa" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),

    #modul penjualan
    "TransaksiDataPenjualan" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "TransaksiDataPenjualanBridging" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningTransaksiDataPenjualan" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    //"JurnalPenjualan" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    //"RekeningPenjualan" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuTransaksiPenjualan" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningTransaksiDataPenjualanCache" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    //"RekeningPembantuTransaksiPenjualanKas" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    //"RekeningPembantuTransaksiPenjualanPpn" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),

    #modul distribusi
    "RekeningPembantuTransaksiDistribusi" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "TransaksiDataDistribusi" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningTransaksiDataDistribusiCache" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningTransaksiDataDistribusi" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),

    #modul pembayaran
    "RekeningPembantuTransaksiPembayaran" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "TransaksiDataPembayaran" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningTransaksiDataPembayaranCache" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningTransaksiDataPembayaran" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),

    #modul taxes
    "RekeningPembantuTransaksiTaxes" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "TransaksiDataTaxes" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningTransaksiDataTaxesCache" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningTransaksiDataTaxes" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),

    #modul kas
    "RekeningPembantuTransaksiKas" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "TransaksiDataKas" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningTransaksiDataKasCache" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningTransaksiDataKas" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),


);

$config['heTransaksi_paramForceFillers_jenisTR'] = array(
    "FifoSupplies" => array(
        "461" => array(
            "purchase_id" => "insertID",
            "purchase_nomer" => "insertNum",
        ),

    ),

);


// ======================= ======================= =======================
$config['heTransaksi_regulerRoutes'] = array(
    // purchasing fg
    "466" => array(
        "1" => array(
            "debet" => "request",
            "kredit" => "otorisasi",
        ),
        "2" => array(
            "debet" => "otorisasi",
            "kredit" => "penerimaan barang",
        ),
        "3" => array(
            "debet" => "penerimaan barang",
            "kredit" => "realisasi ppn masukan",
        ),
        "4" => array(
            "debet" => "realisasi ppn masukan",
            "kredit" => "pembayaran",
        ),
        "5" => array(
            "debet" => "pembayaran",
            "kredit" => "request",
        ),
    ),
    // purchasing supplies
    "461" => array(
        "1" => array(
            "debet" => "request",
            "kredit" => "otorisasi",
        ),
        "2" => array(
            "debet" => "otorisasi",
            "kredit" => "penerimaan barang",
        ),
        "3" => array(
            "debet" => "penerimaan barang",
            "kredit" => "realisasi ppn masukan",
        ),
        "4" => array(
            "debet" => "realisasi ppn masukan",
            "kredit" => "pembayaran",
        ),
        "5" => array(
            "debet" => "pembayaran",
            "kredit" => "request",
        ),
    ),
    // purchasing service to branch
    "463" => array(
        "1" => array(
            "debet" => "request",
            "kredit" => "otorisasi",
        ),
        "2" => array(
            "debet" => "otorisasi",
            "kredit" => "penerimaan service",
        ),
        "3" => array(
            "debet" => "penerimaan service",
            "kredit" => "realisasi ppn masukan",
        ),
        "4" => array(
            "debet" => "realisasi ppn masukan",
            "kredit" => "pembayaran",
        ),
        "5" => array(
            "debet" => "pembayaran",
            "kredit" => "request",
        ),
    ),
    // purchasing service for center
    "1463" => array(
        "1" => array(
            "debet" => "request",
            "kredit" => "otorisasi",
        ),
        "2" => array(
            "debet" => "otorisasi",
            "kredit" => "penerimaan service",
        ),
        "3" => array(
            "debet" => "penerimaan service",
            "kredit" => "realisasi ppn masukan",
        ),
        "4" => array(
            "debet" => "realisasi ppn masukan",
            "kredit" => "pembayaran",
        ),
        "5" => array(
            "debet" => "pembayaran",
            "kredit" => "request",
        ),
    ),


    // distribusi fg ke cabang
    "583" => array(
        "1" => array(
            "debet" => "request",
            "kredit" => "otorisasi",
        ),
        "2" => array(
            "debet" => "otorisasi",
            "kredit" => "penerimaan barang",
        ),
        "3" => array(
            "debet" => "penerimaan barang",
            "kredit" => "request",
        ),
    ),
    "585" => array(
        "3" => array(
            "debet" => "penerimaan barang",
            "kredit" => "request",
        ),
    ),

    // distribusi supplies ke cabang
    "3583" => array(
        "1" => array(
            "debet" => "request",
            "kredit" => "otorisasi",
        ),
        "2" => array(
            "debet" => "otorisasi",
            "kredit" => "penerimaan barang",
        ),
        "3" => array(
            "debet" => "penerimaan barang",
            "kredit" => "request",
        ),
    ),
    "3585" => array(
        "3" => array(
            "debet" => "penerimaan barang",
            "kredit" => "request",
        ),
    ),

    // distribusi bom ke pusat
    "3683" => array(
        "1" => array(
            "debet" => "request",
            "kredit" => "otorisasi",
        ),
        "2" => array(
            "debet" => "otorisasi",
            "kredit" => "penerimaan barang",
        ),
        "3" => array(
            "debet" => "penerimaan barang",
            "kredit" => "request",
        ),
    ),
    "3685" => array(
        "3" => array(
            "debet" => "penerimaan barang",
            "kredit" => "request",
        ),
    ),

    // distribusi aktiva tetap ke cabang
    "2483" => array(
        "1" => array(
            "debet" => "request",
            "kredit" => "otorisasi",
        ),
        "2" => array(
            "debet" => "otorisasi",
            "kredit" => "penerimaan barang",
        ),
        "3" => array(
            "debet" => "penerimaan barang",
            "kredit" => "request",
        ),
    ),
    "2485" => array(
        "3" => array(
            "debet" => "penerimaan barang",
            "kredit" => "request",
        ),
    ),

    // sales, penjualan
    "582" => array(
        "1" => array(
            "debet" => "request",
            "kredit" => "otorisasi",
        ),
        "2" => array(
            "debet" => "otorisasi",
            "kredit" => "pre packinglist",
        ),
        "3" => array(
            "debet" => "pre packinglist",
            "kredit" => "packinglist",
        ),
        "4" => array(
            "debet" => "packinglist",
            "kredit" => "invoice",
        ),
        "5" => array(
            "debet" => "invoice",
            "kredit" => "pembayaran",
        ),
        "6" => array(
            "debet" => "pembayaran",
            "kredit" => "request",
        ),
    ),

    // penyetoran kas
    "759" => array(
        "1" => array(
            "debet" => "request",
            "kredit" => "penerimaan setoran",
        ),
        "2" => array(
            "debet" => "penerimaan setoran",
            "kredit" => "request",
        ),
    ),
    "758" => array(
        "2" => array(
            "debet" => "penerimaan setoran",
            "kredit" => "request",
        ),
    ),

    // produksi
    "776" => array(
        "1" => array(
            "debet" => "request",
            "kredit" => "otorisasi",
        ),
        "2" => array(
            "debet" => "otorisasi",
            "kredit" => "request",
        ),
    ),
    // pemindahan rekening pembantu kas branch
    "757" => array(
        "1" => array(
            "debet" => "request",
            "kredit" => "otorisasi",
        ),
        "2" => array(
            "debet" => "otorisasi",
            "kredit" => "request",
        ),
    ),
    // pemindahan rekening pembantu kas center
    "1757" => array(
        "1" => array(
            "debet" => "request",
            "kredit" => "otorisasi",
        ),
        "2" => array(
            "debet" => "otorisasi",
            "kredit" => "request",
        ),
    ),

);

$config['heTransaksi_headerStatus_fields'] = array(
    "jenis_label" => "activity",
    "dtime" => "date",
    "status_next" => "status",
    "cabang_nama" => "cabang pengirim",
    "cabang2_nama" => "cabang penerima",
    "customers_nama" => "customer",
    "suppliers_nama" => "vendor",
    "nomer_top" => "PO number",
    "nomer" => "receipt number",
    "oleh_nama" => "person",
    "harga" => "amount",
    "disc" => "discount",
    "ppn" => "ppn",
    "nett" => "total amount",
    //            "trash_4" => "trash 4",
    //            "id" => "ID",
);

$config['heTransaksi_source_internal_connect'] = array(
    "1582",
);

$config['heTransaksi_center_connect'] = array(
    "583",
    "3583",
    "3683",
    "2483",
    "759",
);

// ======================= ======================= =======================
$config['heTransaksi_pembatalanValidate'] = array(
    // validasi ini berjalan secara query adalah:
    // hasil dari query setelah difilter mendapatkan jumlah row > 0, maka STOP.
    // berdasarkan jenis master transaksi
    // purchasing service to branch
    "463" => array(
        array(
            "mdlName" => "MdlJurnalActivityCache",
            "mdlFilter" => array(
                "master_id=id_master",
                "jenis_master=.463",
                "activity=.pembayaran",
                "kredit=.0",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayarannya dahulu.",
        ),
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang biaya",
                "terbayar>.0",
                "jenis=.463",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayarannya dahulu.",
        ),
    ),
    // purchasing service to center
    "1463" => array(
        array(
            "mdlName" => "MdlJurnalActivityCache",
            "mdlFilter" => array(
                "master_id=id_master",
                "jenis_master=.1463",
                "activity=.pembayaran",
                "kredit=.0",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayarannya dahulu.",
        ),
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang biaya",
                "terbayar>.0",
                "jenis=.1463",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayarannya dahulu.",
        ),
    ),
    // purchasing finish goods
    "466" => array(
//        array(
//
//            "mdlName" => "MdlJurnalActivityCache",
//            "mdlFilter" => array(
//                "master_id=id_master",
//                "jenis_master=.466",
//                "activity=.pembayaran",
//                "kredit=.0",
//            ),
//            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayarannya dahulu.",
//        ),
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang dagang",
                "terbayar>.0",
                "jenis=.467",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayaran dahulu.",
        ),
        array(
            "mdlName" => "MdlTransaksiData",
            "mdlFilter" => array(
                "id=transaksi_id",
                "returned=.1",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan return purchasing.",
            "detailCekQty" => true,
        ),
        array(
            "mdlName" => "MdlTransaksiData",
            "mdlFilter" => array(
                "id=transaksi_id",
                "trash_4=.1",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembatalan transaksi.",
        ),
    ),
    // purchasing supplies
    "461" => array(
//        array(
//
//            "mdlName" => "MdlJurnalActivityCache",
//            "mdlFilter" => array(
//                "master_id=id_master",
//                "jenis_master=.461",
//                "activity=.pembayaran",
//                "kredit=.0",
//            ),
//            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayarannya dahulu.",
//        ),
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang dagang",
                "terbayar>.0",
                "jenis=.461",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayaran dahulu.",
        ),
    ),

    "2677" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang biaya usaha",
                "terbayar>.0",
                "jenis=.2677",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayarannya dahulu.",
        ),
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang biaya usaha",
                "returned>.0",
                "jenis=.2677",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayarannya dahulu.",
        ),
    ),
    "2676" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang biaya produksi",
                "terbayar>.0",
                "jenis=.2676",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayarannya dahulu.",
        ),

    ),
    "2675" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang biaya umum",
                "terbayar>.0",
                "jenis=.2675",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayarannya dahulu.",
        ),

    ),

    "1677" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang biaya usaha",
                "terbayar>.0",
                "jenis=.1677",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayarannya dahulu.",
        ),
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang biaya usaha",
                "returned>.0",
                "jenis=.1677",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayarannya dahulu.",
        ),
    ),
    "1676" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang biaya produksi",
                "terbayar>.0",
                "jenis=.1676",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayarannya dahulu.",
        ),

    ),
    "1675" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang biaya umum",
                "terbayar>.0",
                "jenis=.1675",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayarannya dahulu.",
        ),

    ),

    // penjualan/packinglist
    "5822" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.piutang dagang",
                "terbayar>.0",
                "jenis=.5822spd",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dibayar/sudah lunas dari menu Penerimaan AR. Silahkan untuk membatalkan Penerimaan AR dahulu.",
        ),
    ),

    // penerimaan piutang di cabang
//    "749" => array(
//        array(
//            "mdlName" => "MdlPaymentSource",
//            "mdlFilter" => array(
//                "transaksi_id=transaksi_id",
//                "label=.hutang setoran",
//                "terbayar>.0",
//                "jenis=.749",
//            ),
//            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan penyetoran ke pusat. Silahkan untuk membatalkan penyetorannya dahulu.",
//        ),
//    ),

    // penerimaan finish goods dari vendor
//    "466" => array(
//        array(
//            "mdlName" => "MdlPaymentSource",
//            "mdlFilter" => array(
//                "transaksi_id=transaksi_id",
//                "label=.hutang dagang",
//                "terbayar>.0",
//                "jenis=.467",
//            ),
//            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayaran dahulu.",
//        ),
//    ),

//    // penerimaan supplies dari vendor
//    "461" => array(
//        array(
//            "mdlName" => "MdlPaymentSource",
//            "mdlFilter" => array(
//                "transaksi_id=transaksi_id",
//                "label=.hutang dagang",
//                "terbayar>.0",
//                "jenis=.461",
//            ),
//            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayaran dahulu.",
//        ),
//    ),
    // hutang aktiva tetap
    "421" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang aktiva tetap",
                "terbayar>.0",
                "jenis=.423",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayaran dahulu.",
        ),
    ),
    // hutang bank
    "444" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang bank",
                "terbayar>.0",
                "jenis=.444",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayaran dahulu.",
        ),
    ),

    "3463" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang dagang",
                "terbayar>.0",
                "jenis=.3463",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayarannya dahulu.",
        ),
        array(
            "mdlName" => "MdlTransaksiDataItems3_sum",
            "mdlFilter" => array(
                "transaksi_id=produkProjek__transaksi_id_app",
                "produk_id=transaksi_id",
                "valid_qty=.0",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan Packinglist di cabang.",
        ),
    ),
    // pembatalan  project
    "588" => array(),
);

$config['heTransaksi_pembatalanFifoValidate'] = array(
    "460" => array(
        "mdlNameLoc" => "Preprocs",
        "mdlName" => "PreFifoProdukJadi_reverse",
        "method" => "lookupFifoById",
        "label" => "Silahkan menggunakan transaksi FG purchases return (import).",
    ),
//    "585" => array(
//        "mdlNameLoc" => "Preprocs",
//        "mdlName" => "PreFifoProdukJadi_reverse",
//        "method" => "lookupFifoById",
//        "label" => "Silahkan menggunakan transaksi Stock Return (by Product).",
//    ),
//    "1985" => array(
//        "mdlNameLoc" => "Preprocs",
//        "mdlName" => "PreFifoProdukJadi_reverse",
//        "method" => "lookupFifoById",
//        "label" => "",
//    ),
//    "3685" => array(
//        "mdlNameLoc" => "Preprocs",
//        "mdlName" => "PreFifoProdukJadi_reverse",
//        "method" => "lookupFifoById",
//        "label" => "Silahkan menggunakan transaksi Stock Distribution (Rakitan ke Pabrik).",
//    ),
);

$config['heTransaksi_revertJenisException'] = array(
//    "334",
//    "1334",
//    "585",// penerimaan distribusi
//    "1985",// penerimaan return distribusi
//    "3685",// penerimaan transfer stock
);
// ======================= ======================= =======================
$config['heTransaksi_revertMainGateReplacer'] = array(
    "3685" => array(
        "piutang cabang" => "nilai_cancel",
        "hutang ke pusat" => "nilai_cancel",
    ),
    "585" => array(
        "piutang cabang" => "nilai_cancel",
        "hutang ke pusat" => "nilai_cancel",
    ),
    "1985" => array(
        "piutang cabang" => "nilai_cancel",
        "hutang ke pusat" => "nilai_cancel",
    ),
);


// ======================= ======================= =======================
$config['heTransaksi_midValidatePreProcc'] = array(
    "enabled" => true,
    "jenisTrException" => array(
        "1119",
        "2229",
        "1118",
        "2228",
        "2227",
        "3339",
        "5559",
        "334",
        "1334",
        "335",
        "2334",
        "2335",
        "2336",
        "2337",
        "776",
    ),
    "preProcc" => array(
        "detail" => array(
            "FifoProdukJadi" => array(
                "sourceGate" => "srcGateName",
                "targetGate" => "resultParams",
            ),
            "FifoProdukJadiRakitan" => array(
                "sourceGate" => "srcGateName",
                "targetGate" => "resultParams",
            ),
            "FifoSupplies" => array(
                "sourceGate" => "srcGateName",
                "targetGate" => "resultParams",
            ),
        ),
    ),
);
$config['heTransaksi_validatePostProcc'] = array(
    "enabled" => true,
    "jenisTrException" => array(
        "1119",
        "2229",
        "1118",
        "2228",
        "2227",
        "3339",
        "5559",
        "334",
        "1334",
        "335",
        "2334",
        "2335",
        "2336",
        "2337",
        "776",
    ),
    "postProcc" => array(
        "detail" => array(
            "FifoProdukJadi" => array(
                "model" => "FifoProdukJadi",
            ),
            "FifoProdukJadiRakitan" => array(
                "model" => "FifoProdukJadiRakitan",
            ),
            "FifoSupplies" => array(
                "model" => "FifoSupplies",
            ),
        ),
    ),
);
$config['heTransaksi_validateComponentDetail'] = array(
    "enabled" => true,
    "jenisTrException" => array(
        "1119",
        "2229",
        "1118",
        "2228",
        "2227",
        "3339",
        "5559",
        "334",
        "1334",
        "335",
        "2334",
        "2335",
        "2336",
        "2337",
        "776",
        "1463",
        "463",
        "1462",
        "462",
        "677",
        "2677",
        "110r",
        "110",
        "8786",
        "8787",
        "8788",
        "1675",
        "2676",
        "771",
        "742",
        "682",
        "5682",
        "4447",
        "743",
        "2675",
        "464",
        "773",
        "9911",
        "9912",
        "117",
        "1337",
        "1677",
        "444",
        "681",
        "682",
    ),
    "subComponent" => array(
        "detail" => array(
            "RekeningPembantuProduk",
            "RekeningPembantuSupplies",

        ),
    ),
    "dobleValidate" => array(
        "585",
        "985",
        "1985",
        "2985",
        "3585",
    ),// validasi debet vs request dan kredit vs request

);

$config['heTransaksi_rejectException'] = array(
    "code" => array(
        "110",
        "582",
    ),
);


$config['heTransaksi_pembatalanChecker'] = array(
    "467" => array(
        "serial" => array(
            "mdlNameLoc" => "Mdls",
            "mdlName" => "MdlProdukPerSerialNumber",
//            "mdlFilterIn" => "",
            "mdlFilter" => array(
                "transaksi_id=referenceID__3",
            ),
            "label" => "",
            "targetGate" => "",
            "pairedModel" => array(
                "mdlNameLoc" => "Coms",
                "mdlName" => "ComRekeningPembantuProdukPerSerial",
                "mdlFilterInSrc" => "produk_serial_number_2",
                "mdlFilterIn" => "extern_nama",
                "mdlFilter" => array(
                    "cabang_id=placeID",
                    "gudang_id=gudangID",
                    "qty_debet>.0",
                ),
            ),
        ),

    ),
);

?>
