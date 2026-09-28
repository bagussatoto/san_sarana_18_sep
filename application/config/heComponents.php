<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 6/22/2019
 * Time: 7:56 PM
 */

//==semua preProcessor adalah IRREVERSABLE kecuali yang terdaftar di sini
//==config di sini menyatakan parameter untuk pembalikan saat terjadi revertion
//==atau justru tidak perlu pembalikan SAMA SEKALI

$config['hePreProcessors'] = array(
//    "FifoAverage"=>array(
//        "jml"=>"qty",
//        "hpp"=>"hpp",
//        "jml_nilai"=>"(jml*hpp)"
//    ),
//    "FifoProdukJadi"=>array(
//        "unit"=>"qty",
//        "hpp"=>"hpp",
//        "jml_nilai"=>"(jml*hpp)"
//    ),
    "LockerStock" => array(
//        "jumlah"=>"-qty",
//        "jumlah",
        "produk_qty" => "qty",
    ),
    "LockerValue" => array(
        "nilai" => "qty",
    ),
    "SyncEfisiensi" => array(
        "nilai" => "harga",
    ),
    "RekeningKoranMinus" => array(
        "nilai" => "valas_kurang_nilai",
    ),
    "ProdukSerialNumberExtractor" => array(//        "nilai" => "valas_kurang_nilai",
    ),
    "ProdukSerialNumberExtractorPaket" => array(//        "nilai" => "valas_kurang_nilai",
    ),
    "ProdukSerialNumberExtractorKonversiSatuan" => array(//        "nilai" => "valas_kurang_nilai",
    ),
    "ProdukSerialNumberExtractorKonversiPotong" => array(//        "nilai" => "valas_kurang_nilai",
    ),

);

//==semua component adalah IRREVERSABLE kecuali yang terdaftar di sini
//==config di sini menyatakan parameter untuk pembalikan saat terjadi revertion
//==atau justru tidak perlu pembalikan SAMA SEKALI

$config['hePostProcessors'] = array(
    "TransaksiItemReturnUpdate" => array(
//        "jumlah"=>"-qty",
        "jumlah",
        "seluruhnya",
    ),
    "LockerStock" => array(
//        "jumlah"=>"-qty",
        "jumlah",
    ),
    "LockerStockMutasi" => array(
//        "jumlah"=>"-qty",
        "qty_debet",
    ),
    "LockerStockSupplies" => array(
//        "jumlah"=>"-qty",
        "jumlah",
    ),
    "LockerStockProduksi" => array(
        "jumlah",
    ),
    "PriceProduk" => array(
//        "jumlah"=>"-qty",
        "nilai",
    ),
    "PriceProtector" => array(
//        "jumlah"=>"-qty",
//        "nilai",
    ),
    "PriceProdukLastPurchase" => array(
//        "jumlah"=>"-qty",
        "nilai",
    ),
    "PriceProdukPerSupplier" => array(
//        "jumlah"=>"-qty",
        "nilai",
    ),
    "PriceSupplies" => array(
//        "jumlah"=>"-qty",
        "nilai",
    ),
    "LockerValue" => array(
        "nilai",
    ),
    "LockerStockPlafonBankMutasiMain" => array(
        "debet",
        "produk_nilai",
    ),

    "PaymentSrcItem" => array(
        "terbayar",
        "terbayar_valas",
    ),
    "ReleaserDueDate" => array(//
    ),
    "Jurnal_activityMain" => array(//
    ),
    "Jurnal_activityItem" => array(//
    ),
    "Jurnal_activity" => array(//
    ),
    "TransaksiItemUpdate" => array(//
        "jumlah",
    ),
    "Signature" => array(),
    "TransaksiProjekUpdate" => array(),
    "TransaksiItems3_sum" => array(
        "valid_qty",
    ),
    //----
    "TransaksiStepUpdater" => array(),
    "TransaksiProjectItem" => array(
        "terbayar",// kelompok static
        "project",// kelompok loop
    ),

    "Opname" => array(),
    "OpnameData" => array(),
    "TransaksiKreditLimit" => array(
        "produk_nilai",
    ),
    "ProdukSerialNumber" => array(
        "jumlah",
    ),
    //-----
    "RekeningPembantuPettycash" => array(),
    "LockerTransaksiMain" => array(
        "jumlah",
    ),
    "CrmLockerStock" => array(
        "jumlah",
    ),
    //-----

    //region bagian mutasi ERP
    "RekeningPembantuTransaksiPembelian" => array(
        "produk_qty",
    ),
    "RekeningTransaksiDataPembelian" => array(
        "produk_qty",
    ),
    "RekeningTransaksiDataPembelianCache" => array(
        "produk_qty",
    ),
    "TransaksiDataPembelian" => array(
        "produk_qty",
    ),

    "RekeningPembantuTransaksiPenjualan" => array(
        "produk_qty",
    ),
    "RekeningTransaksiDataPenjualan" => array(
        "produk_qty",
    ),
    "RekeningTransaksiDataPenjualanCache" => array(
        "produk_qty",
    ),
    "TransaksiDataPenjualan" => array(
        "produk_qty",
    ),
    "TransaksiDataPenjualanBridging" => array(
        "produk_qty",
    ),

    "RekeningPembantuTransaksiDistribusi" => array(
        "produk_qty",
    ),
    "RekeningTransaksiDataDistribusi" => array(
        "produk_qty",
    ),
    "RekeningTransaksiDataDistribusiCache" => array(
        "produk_qty",
    ),
    "TransaksiDataDistribusi" => array(
        "produk_qty",
    ),

    //endregion
);
$config['hePostProcessorsDenied'] = array(
    // ==== main
    "Jurnal_activity",
    "Jurnal_activityMain",
    // ==== detail
    "PriceProduk",
    "PriceProtector",
    "PriceProdukLastPurchase",
    "PriceProdukPerSupplier",
    "PriceSupplies",
);