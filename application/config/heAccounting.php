<?php
/**
 * Created by PhpStorm.
 * User: jasmanto
 * Date: 22/08/2018
 * Time: 16.02
 */


$config["decimalNumberPrecision"] = array(
    "main" => 24,
    "precision" => 17,
);

// menambah rekening di config account struktur
// harus menambah juga di $config['accountRekeningSort'], $config['categoryRL'] di bawah (untuk view ke rugilaba dan neraca)
$config['accountStructure'] = array(
    "aktiva" => array(
        "kas",
        "valas",
        "pettycash",
        "piutang cabang",
        "piutang ke pusat",
        "piutang biaya cabang",
        "piutang dagang",
        "piutang valas",//valas
        "piutang dagang jasa",
        "piutang pembelian",
        "piutang retensi",
        "uang muka dibayar",
        "uang muka valas",
        "sewa dibayar dimuka",
        "piutang lain",
        "credit note",
        "persediaan produk",
        "persediaan produk riil",
//        "selisih persediaan karena fifo",//ini untuk selisih pembatalan maupun return yg melibatakan fifo
//        "selisih pembulatan",//ini untuk selisih pembatalan maupun return yg melibatakan fifo
//        "laba(rugi) selisih persediaan karena fifo", // return pembelian
//        "laba(rugi) selisih persediaan karena fifo distribusi",
//        "laba(rugi) selisih persediaan karena fifo pemindahan dc",
//        "laba(rugi) selisih fifo pembatalan transaksi",
//        "laba(rugi) selisih fifo return pembelian",

//        "biaya bunga",

        "persediaan produk rakitan",
        "persediaan produk nonactive",
        "persediaan supplies",
        "persediaan supplies produksi",
        "persediaan supplies proses",
        "persediaan supplies riil",
        "aktiva tetap",
        "aktiva tetap tak berwujud",
        "piutang aktiva tetap cabang",
        "ppn in",// ppn masukan (saat terjadi pembelian)
        "ppn in jasa",// ppn masukan (saat terjadi pembelian jasa)
        "ppn in realisasi",// ppn masukan (saat terjadi pembelian)
        "ppn in jasa realisasi",// ppn masukan (saat terjadi pembelian jasa)
        "pib",// pajak import barang(setara ppn in)
        "pph22",// )
        "pph25",// )
        "pph 23 dibayar di muka",
        "pph 25 dibayar di muka",
        "pph22 dibayar dimuka",
        "ppn dibayar bendahara negara",
        "pph4 ayat 2",
        "pph29",
        "kendaraan",
        "peralatan",
        "peralatan kantor",
        "tanah dan bangunan",
        "mesin produksi",
        "mesin",
        "peralatan produksi",
        //------
        "mesin produksi jkt",
        "kendaraan jkt",
        "kendaraan solo",
        //------

//        "biaya import",
        //        "akum penyu aktiva tetap",

//        "direct labor",
//        "delivery cost",
//        "quality",
//        "overhead",

        "aktiva belum ditempatkan",
//        "biaya",
//        "biaya sewa",
        "deposit pajak",
//        "efisiensi cabang",
//        "efisiensi biaya",
        "projek cost",
    ),
    "hutang" => array(
        "hutang bank",
        "hutang dagang",
        "hutang ke pusat",
        "hutang ke cabang",
        "hutang jangka panjang",
        "hutang ppn",
        "hutang ke konsumen",
        "hutang valas ke konsumen",
        "hutang biaya",
        "hutang ongkir",
        "hutang biaya ke pusat",
        "hutang gaji",
        "hutang aktiva tetap",
        "hutang aktiva tetap pada dc",
        "ongkir",
        "hutang install",
        "ppn out",
        "ppn out sudah ada faktur",
        "hutang bpjs",
        "hutang pph21",
        "hutang pph23",
        "hutang pph29",
        "hutang pph4 ayat 2",
        "pph25_29",
        "hutang kontijensi biaya",
        "hutang lain ppv",
        "hutang lain ppv cabang",
        "hutang jangka panjang",
        "beban harus dibayar",
        "hutang uang muka",
        "hutang sewa",
        "hutang ke pemegang saham",
        "hutang ke pihak lain",
        "hutang biaya bunga",
//        "efisiensi biaya",
        "efisiensi ditempatkan pusat",
        "rekening koran",
        "non rekening koran",
        "hutang garansi",
    ),
    "modal" => array(
        "modal",
        "modal saham disetor",
        "laba ditahan",
        "laba",
        "rugi",
        "laba ditempatkan pusat",
        "laba ditempatkan pusatt",
    ),

    "penghasilan" => array(
        "penjualan",
        "penjualan jasa",
        "penjualan projek",
        "jasa kirim",
        "pendapatan",
        "pendapatan lain_lain",
        "pendapatan dari piutang dihapus",
        "laba(rugi) perubahan grade produk",
        "laba(rugi) perubahan grade supplies",
        "laba(rugi) opname produk",
        "laba(rugi) opname supplies",
        "laba(rugi) return produksi",
        "laba(rugi) selisih adjustment",
        "laba(rugi) selisih kurs",
        "rugi laba pembulatan ganjil",
        "efisiensi operasional",
        "efisiensi biaya",
        "efisiensi cabang",
        "selisih biaya produksi",
        "keutungan kurs",
        "transfer stok",
        "bunga dan jasa giro",
//        "pendapatan lain-lain",

        "laba selisih kurs",
        "laba penjualan aset",
        "laba lain lain",
        "laba lain lain cabang",
        "rugilaba konversi valas",
        "laba efisiensi produksi",
    ),
    "penghasilan lain lain" => array(
        "penjualan valas",
    ),
    "biaya lain lain" => array(
        "harga perolehan valas",
        "rugi selisih kurs"
    ),
    "biaya" => array(
        "hpp",
        "hpp projek",

        "biaya",
        "biaya gaji",
        "biaya bpjs",
        "biaya pph21",
        "biaya umum",
        "biaya usaha",
        "biaya jasa",
        "biaya supplies",
        "biaya produksi",
        "biaya operasional",
        "biaya transfer",
        "biaya garansi",
        "biaya import",
        "biaya bunga",
        "biaya sewa",
        "kerugian",
        "return penjualan",

        "selisih persediaan karena fifo",//ini untuk selisih pembatalan maupun return yg melibatakan fifo
        "selisih pembulatan",//ini untuk selisih pembatalan maupun return yg melibatakan fifo
        "laba(rugi) selisih persediaan karena fifo", // return pembelian
        "laba(rugi) selisih persediaan karena fifo distribusi",
        "laba(rugi) selisih persediaan karena fifo pemindahan dc",
        "laba(rugi) selisih fifo pembatalan transaksi",
        "laba(rugi) selisih fifo return pembelian",
//        "selisih biaya produksi",
        "efisiensi biaya",
//        "efisiensi cabang",

        "diskon",
        "kerugian kurs",
        "beban lain lain",
        "rugi piutang dihapus",

        //		"laba(rugi) selisih persediaan karena fifo produksi",
        //        "laba(rugi) perubahan grade produk",
        //		"laba(rugi) perubahan grade bahan",
        //        "ongkir dibayar konsumen",
        //        "ongkos install",

        "biaya kirim",
        "tenaga kerja",

//        "penyusutan",
        "penyusutan kendaraan",
        "penyusutan peralatan kantor",
        "penyusutan peralatan produksi",
        "penyusutan mesin produksi",
        "penyusutan mesin",
        "penyusutan tanah dan bangunan",

        "direct labor",
        "delivery cost",
        "quality",
        "overhead",
    ),

    "lain-lain-deb" => array(
        "alokasi biaya",
        //        "ppn in",// ppn masukan (saat terjadi pembelian)
        //        "ppn in jasa",// ppn masukan (saat terjadi pembelian jasa)
        //        "ongkir dibayar konsumen",
        //        "ongkos install",
    ),
    "lain-lain-kr" => array(
        //        "ppn out",// ppn keluaran (saat terjadi penjualan)
        "ongkir dibayar konsumen",
        "ongkos install",
        "akum penyu aktiva tetap",
        "akum penyu kendaraan",
        "akum penyu mesin",
        "akum penyu peralatan kantor",
        "akum penyu peralatan produksi",
        "akum penyu mesin produksi",
        "akum penyu tanah dan bangunan",
    ),
    "laba(rugi)" => array(
        "laba",
        "rugi",
        "rugilaba",
//        "laba lain lain",
        "rugi lain lain",
        "rugilaba lain lain",
//        "laba lain lain cabang",
        "labarugi kotor",
        "labarugi bersih",
    ),


);
$config['accountAlias'] = array(
    "kas" => "kas",
    //    "valas" => "valas",
    //    "pettycash" => "pettycash",
    "piutang cabang" => "piutang cabang",
    "piutang ke pusat" => "piutang ke pusat",
    "piutang biaya cabang" => "piutang biaya cabang",
    "piutang dagang" => "piutang usaha lokal",
    "piutang valas" => "piutang usaha ekspor",//valas
    "piutang dagang jasa" => "piutang usaha jasa",//valas
    //    "piutang pembelian" => "uang muka pembelian",
//    "piutang pembelian" => "pembelian dibayar  dimuka",
    "piutang pembelian" => "credit note (from return)",
    "piutang lain" => "piutang lain",

    //uang muka
//    "uang muka dibayar" => "uang muka dibayar",
    "uang muka dibayar" => "uang muka ke supplier",
    "uang muka valas" => "uang muka (valas) ke supplier",

    "credit note" => "credit note",
    "persediaan produk" => "persediaan barang jadi",
    "persediaan produk riil" => "persediaan barang jadi beli riil",
    "persediaan produk rakitan" => "persediaan barang jadi produksi",

    "persediaan supplies" => "persediaan bahan baku",
    "persediaan supplies proses" => "persediaan bahan baku dalam proses",
    "persediaan supplies riil" => "persediaan bahan baku riil",

    "aktiva tetap" => "aktiva tetap",
    "aktiva tetap tak berwujud" => "aktiva tetap tak berwujud",
    "ppn in" => "ppn masukan (belum ada faktur)",// ppn masukan (saat terjadi pembelian)
    "ppn in jasa" => "ppn masukan jasa (belum ada faktur)",// ppn masukan (saat terjadi pembelian jasa)
    "ppn in realisasi" => "ppn masukan (sudah ada faktur)",// ppn masukan (saat terjadi pembelian)
    "ppn in jasa realisasi" => "ppn masukan jasa (sudah ada faktur)",// ppn masukan (saat terjadi pembelian jasa)

    "hutang bank" => "hutang bank",
    "hutang dagang" => "hutang usaha",
    "hutang aktiva tetap" => "hutang aktiva tetap",
    "hutang ke pusat" => "hutang ke pusat",
    "hutang ke cabang" => "hutang ke cabang",
    "hutang jangka panjang" => "hutang jangka panjang",
    "hutang ppn" => "hutang ppn",
    "hutang biaya ke pusat" => "hutang biaya ke pusat",
    "hutang ke konsumen" => "hutang ke konsumen",
    "hutang valas ke konsumen" => "uang muka penjualan ekspor",
    "hutang ke pemegang saham" => "hutang ke pemegang saham",
    "hutang ke pihak lain" => "hutang ke pihak lain",
    //    "hutang biaya" => "",
    //    "hutang ongkir" => "",
    //    "ongkir" => "",
    //    "hutang install" => "",
    "ppn out" => "ppn keluaran",
    "pph25" => "pph ps.25",
    "pph4 ayat 2" => "pph ps.4(2)",
    "pph29" => "pph ps.29",
    "pph 23 dibayar di muka" => "pph 23 dibayar di muka",
    "hutang pph23" => "hutang pph 23",
    "hutang pph4 ayat 2" => "hutang pph ps.4(2)",
    "pph25_29" => "pph 25/29",
    "pph22 dibayar dimuka" => "pph 22 dibayar di muka",
    "ppn dibayar bendahara negara" => "ppn dibayar bendahara negara",
    //    "hutang kontijensi biaya" => "",
    "hutang lain ppv" => "hutang lain ppv",
    "hutang lain ppv cabang" => "hutang lain ppv cabang",
    //    "hutang jangka panjang" => "",
    "beban harus dibayar" => "beban harus dibayar",
    "rugi laba pembulatan ganjil" => "laba(rugi) pembulatan ganjil",

    "modal" => "modal",
    "modal saham disetor" => "modal saham disetor",
    "laba ditahan" => "laba ditahan",
    //    "laba" => "",
    //    "rugi" => "",
    "laba ditempatkan pusat" => "laba ditempatkan pusat",
    "laba ditempatkan pusatt" => "laba ditempatkan pusatt",

    "penjualan" => "penjualan",
    "penjualan jasa" => "penjualan jasa",
    "penjualan projek" => "penjualan project",
    //    "jasa kirim" => "",
    "pendapatan" => "pendapatan",
    "pendapatan lain_lain" => "pendapatan lain-lain",
    "laba(rugi) perubahan grade produk" => "laba(rugi) konversi produk",
    "laba(rugi) perubahan grade supplies" => "laba(rugi) konversi supplies",
    //    "laba(rugi) return produksi" => "",
    //    "efisiensi operasional" => "",
    //    "keutungan kurs" => "",
    //
    //    "penjualan valas" => "",

    "hpp" => "harga pokok penjualan",
    "hpp projek" => "harga pokok penjualan project",
    "projek cost" => "jasa pihak ke-3 project",
    "biaya" => "biaya belum dipindahkan",
    "biaya umum" => "beban umum",
    "biaya usaha" => "beban usaha",
    "biaya bunga" => "biaya bunga",
    //    "biaya jasa" => "",
    "biaya transfer" => "beban transfer",
    "biaya produksi" => "beban produksi",
    "biaya operasional" => "beban operasional",
    "kerugian" => "kerugian karena stok opname",
    "return penjualan" => "return penjualan",
    //    "laba(rugi) selisih persediaan karena fifo" => "", // return pembelian
    //    "laba(rugi) selisih persediaan karena fifo distribusi" => "",
    //    "laba(rugi) selisih persediaan karena fifo pemindahan dc" => "",
    //    "laba(rugi) selisih fifo return pembelian" => "",
    "diskon" => "diskon",
    "keuntungan kurs" => "laba selisih kurs",
    "kerugian kurs" => "rugi selisih kurs",
    "beban lain lain" => "beban lain-lain",
    "transfer stok" => "transfer stok",

    //    "ongkir dibayar konsumen" => "",
    //    "ongkos install" => "",
    "akum penyu aktiva tetap" => "akumulasi penyusutan aktiva tetap",
    "akum penyu kendaraan" => "akumulasi penyusutan kendaraan",
    "akum penyu peralatan kantor" => "akumulasi penyusutan peralatan kantor",
    "akum penyu peralatan produksi" => "akumulasi penyusutan peralatan produksi",
    "akum penyu mesin produksi" => "akumulasi penyusutan mesin produksi",
    "akum penyu mesin" => "akumulasi penyusutan mesin",
    "akum penyu tanah dan bangunan" => "akumulasi penyusutan tanah dan bangunan",
    "bunga dan jasa giro" => "bunga dan jasa giro",
    "rugi selisih kurs" => "rugi selisih kurs",
    "pph22 " => "pph22",

    "rugilaba" => "laba(rugi)",
    "penghasilan" => "penghasilan",
//    "biaya" => "biaya",
    "piutang aktiva tetap cabang" => "piutang aktiva tetap(cab**)",
    "hutang aktiva tetap pada dc" => "hutang aktiva tetap pada dc",
    "penyusutan kendaraan" => "penyusutan kendaraan",
    "penyusutan alat kantor" => "penyusutan alat kantor",
    "penyusutan peralatan produksi" => "penyusutan peralatan produksi",
    "penyusutan sewa" => "penyusutan sewa",
    "penyusutan mesin produksi" => "penyusutan mesin produksi",
    "penyusutan mesin" => "penyusutan mesin",
    "penyusutan tanah dan bangunan" => "penyusutan anah dan bangunan",
    "biaya sewa " => "biaya sewa",
    "biaya import " => "biaya import",
    "hutang uang muka" => "hutang uang muka",
//    "uang muka dibayar" => "biaya sewa",
    "laba(rugi) selisih persediaan karena fifo" => "selisih persediaan karena fifo", // return pembelian
    "laba(rugi) selisih persediaan karena fifo distribusi" => "selisih persediaan karena fifo distribusi",
    "laba(rugi) selisih persediaan karena fifo pemindahan dc" => "selisih persediaan karena fifo pemindahan dc",
    "laba(rugi) selisih fifo return pembelian" => "selisih fifo return pembelian",
    "efisiensi biaya" => "efisiensi produksi bom",
    "deposit pajak" => "deposit pajak",
    "rugi piutang dihapus" => "rugi karena penghapusan piutang",
    "pendapatan dari piutang dihapus" => "pendapatan dari piutang dihapus",
    "rugilaba konversi valas" => "laba(rugi) konversi valas",
    "hutang bpjs" => "hutang bpjs",
    "hutang garansi" => "hutang garansi",
    "biaya garansi" => "beban garansi",
    "selisih biaya produksi" => "selisih biaya produksi",
//    "laba efisiensi produksi" => "laba solo",
    "efisiensi cabang" => "efisiensi solo",
    "piutang retensi" => "piutang retensi",
    "laba(rugi) selisih kurs" => "laba(rugi) selisih kurs",
    "biaya bpjs" => "biaya bpjs",
    "biaya pph21" => "biaya pph21",
);
$config['categoryRL'] = array(
    1 => array(
        "penjualan" => "penjualan",
        "return penjualan" => "return penjualan",
//        "penjualan netto" => "penjualan netto",

//        "penjualan projek" => "penjualan projek",
//        "transfer stok" => "transfer stok",
        "hpp" => "hpp",
//        "hpp projek" => "hpp projek",
//        "projek cost" => "projek cost",
//        "selisih biaya produksi" => "selisih biaya produksi",
        "efisiensi biaya" => "efisiensi produksi bom",
    ),
    2 => array(
        "biaya" => "biaya",
        "biaya import" => "biaya import",
        "biaya bunga" => "biaya bunga",
        "biaya produksi" => "biaya produksi",
        "biaya umum" => "biaya umum",
        "biaya usaha" => "biaya usaha",
        "biaya gaji" => "biaya gaji",
        "biaya jasa" => "biaya jasa",
        "biaya sewa" => "biaya sewa",
        "biaya garansi" => "biaya garansi",
        "biaya bpjs" => "biaya bpjs",
        "biaya pph21" => "biaya pph21",
    ),
    3 => array(
//        "pendapatan" => "pendapatan",
        "pendapatan lain_lain" => "pendapatan lain_lain",
        "pendapatan dari piutang dihapus" => "pendapatan dari piutang dihapus",
        "laba penjualan aset" => "laba penjualan aset",
        "bunga dan jasa giro" => "bunga dan jasa giro",
        "beban lain lain" => "beban lain lain",
        "biaya transfer" => "beban transfer",
        "jasa kirim" => "jasa kirim",

        "overhead" => "overhead",
        "direct labor" => "direct labor",
        "delivery cost" => "delivery cost",
        "quality" => "quality",

        "kerugian" => "kerugian",
        "laba lain lain" => "laba lain lain",
        "laba lain lain cabang" => "laba lain lain cabang",
        "kerugian kurs" => "kerugian kurs",
//        "rugi selisih kurs" => "rugi selisih kurs",
//        "laba selisih kurs" => "laba selisih kurs",
        "keutungan kurs" => "keuntungan kurs",
        "laba(rugi) selisih kurs" => "laba(rugi) selisih kurs",
        "rugilaba konversi valas" => "laba(rugi) konversi valas",
        //-----
        "selisih persediaan karena fifo" => "selisih persediaan karena fifo",//ini untuk selisih pembatalan maupun return yg melibatakan fifo
        "selisih pembulatan" => "selisih pembulatan",//ini untuk selisih pembatalan maupun return yg melibatakan fifo
//        "laba(rugi) selisih persediaan karena fifo" => "laba(rugi) selisih persediaan karena fifo", // return pembelian
//        "laba(rugi) selisih persediaan karena fifo distribusi" => "laba(rugi) selisih persediaan karena fifo distribusi",
//        "laba(rugi) selisih persediaan karena fifo pemindahan dc" => "laba(rugi) selisih persediaan karena fifo pemindahan dc",
//        "laba(rugi) selisih fifo pembatalan transaksi" => "laba(rugi) selisih fifo pembatalan transaksi",
        "laba(rugi) selisih fifo return pembelian" => "laba(rugi) selisih fifo return pembelian",
        //-----
        "laba(rugi) perubahan grade produk" => "laba(rugi) perubahan grade produk",
        "laba(rugi) perubahan grade supplies" => "laba(rugi) perubahan grade supplies",
        "laba(rugi) opname produk" => "laba(rugi) opname produk",
        "laba(rugi) opname supplies" => "laba(rugi) opname supplies",
//        "laba(rugi) return produksi" => "laba(rugi) return produksi",
        "laba(rugi) selisih adjustment" => "laba(rugi) selisih adjustment",
        "rugi laba pembulatan ganjil" => "laba(rugi) pembulatan ganjil",
        "rugi piutang dihapus" => "rugi karena penghapusan piutang",

//        "laba efisiensi produksi" => "laba solo",
        "efisiensi cabang" => "efisiensi solo",
    ),
    4 => array(
        "rugilaba" => "(rugi) laba",
    ),
);
$config['accountRekeningSort'] = array(
    // ===================================== /AKTIVA/ =============================================
    "aktiva" => array(
        "kas",
        "valas",
        "pettycash",

        "piutang dagang",
        "piutang retensi",
        "piutang valas",//valas
        "piutang cabang",
        "piutang ke pusat",
        "piutang pembelian",
        "piutang aktiva tetap cabang",
        "uang muka dibayar",
        "uang muka valas",
        "sewa dibayar dimuka",
        "piutang lain",
        "piutang biaya cabang",
        "piutang aktiva tetap cabang",
        "persediaan produk",
        "persediaan produk rakitan",
        "persediaan produk nonactive",
        "persediaan supplies",
        "persediaan supplies produksi",
        "persediaan supplies proses",

        "ppn in",// ppn masukan (saat terjadi pembelian)
        "ppn in jasa",// ppn masukan (saat terjadi pembelian jasa)
        "ppn in realisasi",// ppn masukan (saat terjadi pembelian)
        "ppn in jasa realisasi",// ppn masukan (saat terjadi pembelian jasa)
        "ppn dibayar bendahara negara",
        "pib",// pajak import barang(setara ppn in)
        "pph22",// )
        "pph22 dibayar dimuka",
        "pph25",// )
        "pph29",
        "pph 23 dibayar di muka",
        "pph4 ayat 2",
        "credit note",
        "deposit pajak",

        "aktiva tetap",
        "akum penyu aktiva tetap",
        "akum penyu kendaraan",
        "akum penyu peralatan kantor",
        "akum penyu peralatan produksi",
        "akum penyu mesin produksi",
        "akum penyu mesin",
        "akum penyu tanah dan bangunan",

        "aktiva tetap tak berwujud",
        "aktiva belum ditempatkan",

        "kendaraan",
        "peralatan",
        "peralatan kantor",
        "tanah dan bangunan",
        "mesin produksi",
        "mesin",
        "peralatan produksi",
        "projek cost",
//        "biaya import",
//        "biaya sewa",
//        "biaya",
//        "biaya bunga",

//        "direct labor",
//        "delivery cost",
//        "quality",
//        "overhead",

//        "efisiensi cabang",
//        "laba(rugi) selisih persediaan karena fifo", // return pembelian
//        "laba(rugi) selisih persediaan karena fifo distribusi",
//        "laba(rugi) selisih persediaan karena fifo pemindahan dc",
//        "laba(rugi) selisih fifo return pembelian",
    ),

    // ===================================== /PASIVA/ =============================================
    "hutang" => array(
        "hutang bank",
        "hutang dagang",
        "hutang ke pusat",
        "hutang ke cabang",
        "hutang ke konsumen",
        "hutang valas ke konsumen",
        "hutang biaya",
        "hutang ongkir",
        "hutang biaya ke pusat",
        "hutang gaji",
        "hutang aktiva tetap",
        "hutang aktiva tetap pada dc",
        "ongkir",
        "hutang install",
        "beban harus dibayar",

        "hutang ppn",
        "ppn out",
        "hutang pph21",
        "hutang pph23",
        "hutang pph4 ayat 2",
        "hutang bpjs",
        "pph_29",
        "pph25_29",


        "hutang kontijensi biaya",
        "hutang lain ppv",
        "hutang lain ppv cabang",
        "hutang jangka panjang",
        "hutang aktiva tetap pada dc",
        "hutang uang muka",
        "hutang sewa",
        "hutang ke pemegang saham",
        "hutang ke pihak lain",
        "hutang biaya bunga",
        "hutang garansi",
        "efisiensi ditempatkan pusat",
    ),

    // ===================================== /MODAL/ =============================================
    "modal" => array(
        "modal",
        "modal saham disetor",
        "laba",
        "rugi",
        "laba ditempatkan pusat",
        "laba ditempatkan pusatt",
        "laba ditahan",

    ),

    // ===================================== /PENGHASILAN/ ========================================
    "penghasilan" => array(
        "penjualan",
        "penjualan projek",
        //"pendapatan",
        "pendapatan lain_lain",
        "laba(rugi) perubahan grade produk",
        "laba(rugi) perubahan grade supplies",
        "laba(rugi) opname produk",
        "laba(rugi) opname supplies",
        "laba(rugi) return produksi",
        "laba(rugi) selisih kurs",
        "rugilaba konversi valas",
        "jasa kirim",
        "efisiensi operasional",
        "keutungan kurs",
        "transfer stok",

        "penjualan valas",
        "pendapatan dari piutang dihapus",
        "selisih biaya produksi",
        //"laba efisiensi produksi",
    ),

    // ===================================== /BIAYA/ =============================================
    "biaya" => array(
        "hpp",
        "hpp projek",
        "projek cost",
        "biaya",
        "biaya gaji",
        "biaya umum",
        "biaya usaha",
        "biaya jasa",
        "biaya transfer",
        "biaya supplies",
        "biaya produksi",
        "biaya operasional",
        "biaya garansi",
        "biaya bpjs",
        "biaya pph21",
        "biaya sewa",
        "biaya import",
        "biaya bunga",
        "efisiensi cabang",

        "kerugian",
        "return penjualan",
        "laba(rugi) selisih persediaan karena fifo", // return pembelian
        "laba(rugi) selisih persediaan karena fifo distribusi",
        "laba(rugi) selisih persediaan karena fifo pemindahan dc",
        "laba(rugi) selisih fifo return pembelian",
        "laba(rugi) selisih adjustment",
        "laba(rugi) karena koreksi hpp",
        "diskon",
        "kerugian kurs",
        "beban lain lain",
        "rugi piutang dihapus",
        "harga perolehan valas",

        "overhead" => "overhead",
        "direct labour" => "direct labour",
        "delivery cost" => "delivery cost",
        "quality" => "quality",

        "penyusutan kendaraan",
        "penyusutan peralatan kantor",
        "penyusutan peralatan produksi",
        "penyusutan mesin produksi",
        "penyusutan mesin",
        "penyusutan tanah dan bangunan",

//        "direct labor",
//        "delivery cost",
//        "quality",
//        "overhead",
    ),

    // ===================================== /laba(rugi)/ =============================================
    "laba(rugi)" => array(
        "laba",
        "rugi",
        "rugilaba",
        "laba lain lain",
        "laba lain lain cabang",
        "rugi lain lain",
        "rugilaba lain lain",
        "labarugi kotor",
        "labarugi bersih",
    ),

);
$config['categoryRLBottom'] = array(

    1 => "laba kotor",
    2 => "total biaya operasional",
    3 => "laba(rugi) lain-lain netto",
    4 => "laba(rugi) bersih netto",
);
$config['accountNetto'] = array(
    "penjualan" => "penjualan",
    "return penjualan" => "penjualan",
    "penjualan projek" => "penjualan",
    //----
    "hpp" => "hpp",
    "hpp projek" => "hpp",
);
$config['accountPersediaan'] = array(
    "persediaan produk" => "persediaan",
    "persediaan produk rakitan" => "persediaan",
    "persediaan supplies" => "persediaan",
    "persediaan supplies proses" => "persediaan",
);

//---------------------------------------------------
$config['accountTypes'] = array(
    "riil" => array(
        "aktiva",
        "hutang",
        "modal",
    ),
    "nominal" => array(
        "penghasilan",
        "biaya",
    ),
);
$config['accountBehavior'] = array(
    "aktiva" => array(
        "debet",
        "kredit",
    ),
    "hutang" => array(
        "kredit",
        "debet",
    ),
    "modal" => array(
        "kredit",
        "debet",
    ),
    "penghasilan" => array(
        "kredit",
        "debet",
    ),
    "penghasilan lain lain" => array(
        "kredit",
        "debet",
    ),
    "biaya" => array(
        "debet",
        "kredit",
    ),
    "biaya lain lain" => array(
        "debet",
        "kredit",
    ),
    "lain-lain-deb" => array(
        "debet",
        "kredit",
    ),
    "lain-lain-kr" => array(
        "kredit",
        "debet",
    ),
    "laba ditempatkan pusat(c)" => array(
        "kredit",
        "debet",
    ),
    "laba ditempatkan pusat(b)" => array(
        "debet",
        "kredit",
    ),
    "laba(rugi)" => array(
        "debet",
        "kredit",
        //        "debet",
    ),

    "kredit lain lain" => array(
        "kredit",
        "debet",
    ),
);
$config['accountBehaviorName'] = array();
$config['accountBehaviorPosition'] = array(
    "debit" => "debet",
    "kredit" => "kredit",
);
$config['accountMinusProtections'] = array(
    "kas",
    "persediaan",
);
$config['accountNeracaExceptions'] = array(
    //	"piutang cabang",
    "hutang ke pusat",
    //	"piutang biaya cabang",
    "hutang biaya ke pusat",
    "laba ditempatkan pusat",
);

$config['accountNeracaExceptions_cabang'] = array(
    "piutang cabang",
    "piutang biaya cabang",
    "laba ditempatkan pusatt",
);

//---------------------------------------------
$config['accountNeracaExceptions_konsolidasi'] = array(
    "piutang cabang",
    "hutang ke pusat",

    "piutang biaya cabang",
    "hutang biaya ke pusat",

    "piutang aktiva tetap cabang",
    "hutang aktiva tetap pada dc",

    "laba ditempatkan pusat",
    "laba ditempatkan pusatt",
);
$config['accountNeracaTipe_konsolidasi'] = array(
    "cost" => array(),
    "riil" => array(
        "hutang lain ppv" => ".0",
        "persediaan produk" => "persediaan produk-hutang lain ppv",
    ),
);
//---------------------------------------------
$config['accountLabarugiExceptions'] = array();
$config['accountLabarugiExceptionsPosition'] = array(
    "laba(rugi) selisih persediaan karena fifo pemindahan dc", // minus maka di debet, plus maka di kredit....
    "rugi laba pembulatan ganjil",
);
$config['accountLabarugiExceptionsPusat'] = array(
    "laba(rugi) selisih persediaan karena fifo distribusi",
    "return penjualan",
    "hpp",
);
$config['accountLabarugiExceptionsCabang'] = array(
    "laba(rugi) selisih persediaan karena fifo",
    "laba(rugi) selisih persediaan karena fifo pemindahan dc",
    "laba(rugi) selisih persediaan karena fifo produksi",
    "laba(rugi) perubahan grade bahan",
    "biaya kontainer",
);
$config['accountAliasExceptions'] = array(
    "ppn in" => "ppn masukan",
    "ppn out" => "ppn keluaran",

    "selisih persediaan karena fifo" => "laba(rugi) selisih persediaan karena fifo",
    "selisih pembulatan" => "selisih pembulatan",
    "selisih persediaan karena fifo distribusi" => "laba(rugi) selisih persediaan karena fifo distribusi",
    "selisih persediaan karena fifo pemindahan dc" => "laba(rugi) selisih persediaan karena fifo pemindahan dc",
    "selisih persediaan karena fifo produksi" => "laba(rugi) selisih persediaan karena fifo produksi",
    "perubahan grade bahan" => "laba(rugi) perubahan grade bahan",
    "perubahan grade produk" => "laba(rugi) perubahan grade produk",
    "perubahan grade supplies" => "laba(rugi) perubahan grade supplies",
);
$config['accountInverterCabang'] = array(
    "laba ditempatkan pusat",
);
$config['accountRekExceptionsCabang'] = array(
    "pettycash",
    "piutang cabang",
    "piutang biaya cabang",
    "persediaan bahan",
    "persediaan produk rakitan",
    "hutang dagang",
    "hutang pph",
    "hutang kontainer",
    "modal",
);
$config['accountRekExceptionsPusat'] = array(
    "piutang dagang",
    "hutang ke pusat",
    "hutang biaya ke pusat",
    "hutang ke konsumen",
    "laba ditahan dicabang",
);

$config['accountRekDetailAdditional'] = array(
    "aktiva tetap" => array(
        "akum penyu aktiva tetap" => array(
            "mainLink" => "Ledger/viewMoveDetails_1/Rekening/",
            "detailLink" => "Ledger/viewMoveDetails/",
        ),
        "akum penyu kendaraan" => array(
            "mainLink" => "Ledger/viewMoveDetails_1/Rekening/",
            "detailLink" => "Ledger/viewMoveDetails/",
        ),
        "akum penyu peralatan kantor" => array(
            "mainLink" => "Ledger/viewMoveDetails_1/Rekening/",
            "detailLink" => "Ledger/viewMoveDetails/",
        ),
        "akum penyu peralatan produksi" => array(
            "mainLink" => "Ledger/viewMoveDetails_1/Rekening/",
            "detailLink" => "Ledger/viewMoveDetails/",
        ),
        "akum penyu mesin produksi" => array(
            "mainLink" => "Ledger/viewMoveDetails_1/Rekening/",
            "detailLink" => "Ledger/viewMoveDetails/",
        ),
        "akum penyu mesin" => array(
            "mainLink" => "Ledger/viewMoveDetails_1/Rekening/",
            "detailLink" => "Ledger/viewMoveDetails/",
        ),
        "akum penyu tanah dan bangunan" => array(
            "mainLink" => "Ledger/viewMoveDetails_1/Rekening/",
            "detailLink" => "Ledger/viewMoveDetails/",
        ),
    ),
);
$config['accountAkumulasiPenyusutan'] = array(
    "akum penyu aktiva tetap",
    "akum penyu kendaraan",
    "akum penyu peralatan kantor",
    "akum penyu peralatan produksi",
    "akum penyu mesin produksi",
    "akum penyu mesin",
    "akum penyu tanah dan bangunan",
);
$config['accountRekOppositeExceptions'] = array(
    "akum penyu aktiva tetap",
    "akum penyu kendaraan",
    "akum penyu peralatan kantor",
    "akum penyu peralatan produksi",
    "akum penyu mesin produksi",
    "akum penyu mesin",
    "akum penyu tanah dan bangunan",
    //versi coa
    "1020010020",//akum kendaraan
    "1020020020",//akum peralatan kantor
    "1020030020",//akum mesin
    "1020040020",//akum mesin produksi
    "1020041020",//akum perlatan produksi
    "1020050020",//akum bangunan
    "1020060020",//akum tanah
    "1020070020",//akum aset belum ditempatkan
    // "1020090020",//akum kendaraan jkt
    // "1020100020",//akum kendaraan solo
    // "1020110020",//akum  Mesin Produksi Jkt

);
$config['accountCatOppositeExceptions'] = array(
    "aktiva" => array(
        "akum penyu aktiva tetap",
        "akum penyu kendaraan",
        "akum penyu peralatan kantor",
        "akum penyu peralatan produksi",
        "akum penyu mesin produksi",
        "akum penyu mesin",
        "akum penyu tanah dan bangunan",
        //versi coa
        "1020010020",//akum kendaraan
        "1020020020",//akum peralatan kantor
        "1020030020",//akum mesin
        "1020040020",//akum mesin produksi
        "1020041020",//akum perlatan produksi
        "1020050020",//akum bangunan
        "1020060020",//akum tanah
        "1020070020",//akum aset belum ditempatkan
    ),
);
$config['accountRekForeverExceptions'] = array(
    "laba ditahan",
);
$config['accountCatExceptions'] = array(
    "penghasilan",
    "penghasilan lain lain",
    "biaya lain lain",
    "biaya",
);

// ini rekening pembantu level 1
$config['accountChilds'] = array(
    "1010010010" => "RekeningPembantuKas",//kas
    "1010010030" => "RekeningPembantuCreditNote",//piutang pembelian / credit note
    "1010010040" => "RekeningPembantuKas",//pettycash
    "1010010020" => "RekeningPembantuValas",//valas
    "1010030030" => "RekeningPembantuProduk",//persediaan produk
    "1010030070" => "RekeningPembantuProduk",//persediaan produk rakitan
    "1010030010" => "RekeningPembantuSupplies",//persediaan supplies
    "2020020" => "RekeningPembantuBank",//hutang bank
    "2010010" => "RekeningPembantuSupplier",//hutang dagang
    "hutang uang muka" => "RekeningPembantuSupplier",
    "1010040050" => "RekeningPembantuSupplier",//ppn in
    "1010040070" => "RekeningPembantuSupplier",//ppn in jasa
    "1010020030" => "RekeningPembantuPiutangSupplierMain",//piutang pembelian
    "1010020010" => "RekeningPembantuCustomer",//piutang dagang
    "1010020050" => "RekeningPembantuCustomer",//piutang dagang jasa
    "piutang lain" => "RekeningPembantuPiutangLain",
    "1010050010" => "RekeningPembantuUangMuka",//uang muka dibayar
    "1010050020" => "RekeningPembantuUangMukaMain",//uang muka valas
    "sewa dibayar dimuka" => "RekeningPembantuSewa",
    "2010050" => "RekeningPembantuCustomer",//hutang ke konsumen
//    "2010060" => "RekeningPembantuCustomer",//hutang ke konsumen
    "2010100" => "RekeningPembantuCustomerValas",//hutang valas ke konsumen
    "efisiensi operasional" => "RekeningPembantuEfisiensi",
    "1010060010" => "RekeningPembantuAntarcabang",//piutang cabang
    "1010060040" => "RekeningPembantuAntarcabang",//piutang biaya cabang
    "2040010" => "RekeningPembantuAntarcabang",//hutang ke pusat
    "2010040" => "RekeningPembantuSupplier",//hutang biaya
    "2010020" => "RekeningPembantuSupplier",//hutang sewa
    "3010020" => "RekeningPembantuModal",//hutang modal
    "2020010" => "RekeningPembantuHutangSaham",//hutang sewa
    "1010050030" => "RekeningPembantuUangMukaMain",
    "aktiva tetap" => "RekeningPembantuAktivaTetap",
    "aktiva belum ditempatkan" => "RekeningPembantuAktivaBelumDitempatkan",//pindah semebntara
    "akum penyu aktiva tetap" => "RekeningPembantuAkumPenyusutanAktivaTetap",
    "akum penyu kendaraan" => "RekeningPembantuAkumPenyusutanKendaraan",
    "akum penyu peralatan kantor" => "RekeningPembantuAkumPenyusutanPeralatanKantor",
    "akum penyu peralatan produksi" => "RekeningPembantuAkumPenyusutanPeralatanProduksi",
    "akum penyu mesin" => "RekeningPembantuAkumPenyusutanMesin",
//    "akum penyu aktiva tetap" => "RekeningPembantuAkumPenyusutanAktivaTetapAdjust",
//    "aktiva tetap tak berwujud" => "RekeningPembantuAktivaTetapTakBerwujud",
    "piutang valas" => "RekeningPembantuCustomerValas",
    "biaya operasional" => "RekeningPembantuBiayaOperasional",
    "modal" => "RekeningPembantuModal",
    "hutang jangka panjang" => "RekeningPembantuHutangJangkaPanjang",
    "biaya bunga" => "RekeningPembantuLoanItem",

    "biaya" => "RekeningPembantuBiaya",
    "biaya import" => "RekeningPembantuBiayaImport",
    "biaya umum" => "RekeningPembantuBiayaUmum",
    "6030" => "RekeningPembantuBiayaUmum",
    "biaya usaha" => "RekeningPembantuBiayaUsaha",
    "6010" => "RekeningPembantuBiayaUsaha",
    "biaya produksi" => "RekeningPembantuBiayaProduksi",
    "beban harus dibayar" => "RekeningPembantuBebanHarusDibayar",
    "pendapatan" => "RekeningPembantuPendapatan",
    "laba ditahan" => "RekeningPembantuLabaDitahan",
    "beban lain lain" => "RekeningPembantuBebanLainLain",
    "hutang gaji" => "RekeningPembantuAntarcabang",
    "2040020" => "RekeningPembantuAntarcabang",//hutang biaya ke pusat
    "2040030" => "RekeningPembantuAntarcabang",//hutang aktiva tetap pada dc
    "2010030" => "RekeningPembantuSupplier",//hutang aktiva tetap
    "piutang aktiva tetap cabang" => "RekeningPembantuAntarcabang",
//    "penyusutan" => "RekeningPembantuDepresiasi",
    "penyusutan kendaraan" => "RekeningPembantuDepresiasi",//p
    "penyusutan peralatan kantor" => "RekeningPembantuDepresiasi",//p
    "biaya sewa" => "RekeningPembantuBiayaSewa",
    "penyusutan mesin produksi" => "RekeningPembantuDepresiasi",
    "penyusutan mesin" => "RekeningPembantuDepresiasi",
    "penyusutan bangunan" => "RekeningPembantuDepresiasi",
    "perlengkapan umum" => "RekeningPembantuDepresiasi",
    "penyusutan tanah dan bangunan" => "RekeningPembantuDepresiasi",

    "6040010" => "RekeningPembantuDepresiasi",
    "6040020" => "RekeningPembantuDepresiasi",
    "6040030" => "RekeningPembantuDepresiasi",
    "6040040" => "RekeningPembantuDepresiasi",
    "6040050" => "RekeningPembantuDepresiasi",
    "6040060" => "RekeningPembantuDepresiasi",


    "overhead" => "RekeningPembantuBiayaKomposisiProduksi",
    "5020030" => "RekeningPembantuBiayaKomposisiProduksi",//direct labor
    "5020020" => "RekeningPembantuBiayaKomposisiProduksi",//delivery cost
    "5020040" => "RekeningPembantuBiayaKomposisiProduksi",//quality
    "5020050" => "RekeningPembantuBiayaKomposisiProduksi",//bahan baku
    "7010150" => "RekeningPembantuLRLainlainDetail",//pendapatan lain lain
    "7010170" => "RekeningPembantuPendapatanItem",//laba lain lain
    // "7010150" => "RekeningPembantuLRLainlain",//laba lain lain unutk builder auto adjustment jadi digeser ke detail


//    "overhead" => "RekeningPembantuEfisiensiBiaya",

    "3020010" => "RekeningPembantuEfisiensiBiayaMain",
    //"3020010010" => "RekeningPembantuEfisiensiBiaya",
    "3020010020" => "RekeningPembantuEfisiensiBiaya",
    "3020010030" => "RekeningPembantuEfisiensiBiaya",

    "pph25" => "RekeningPembantuPph",
    "pph4 ayat 2" => "RekeningPembantuPph",
    "hutang ke pemegang saham" => "RekeningPembantuHutangSaham",
    "hutang ke pihak lain" => "RekeningPembantuHutangPihakLain",
    "hutang biaya bunga" => "RekeningPembantuHutangBiayaBunga",
    "hutang pph23" => "RekeningPembantuPph",
//    "hutang pph4 ayat 2" => "RekeningPembantuPph",
    "efisiensi biaya" => "RekeningPembantuEfisiensiBiayaMain",
    "hutang lain ppv cabang" => "RekeningPembantuAntarcabang",
    // "rugilaba lain lain" => "RekeningPembantuLRLainlain",
//    "aktiva belum ditempatkan" => "RekeningPembantuAktivaBelumDitempatkan",
    "laba lain lain" => "RekeningPembantuLRLainlain",
    //---
    "4010" => "RekeningPembantuPenjualan",
    "5010" => "RekeningPembantuHpp",
    "6050" => "RekeningPembantuBiayaGaji",
    "6080" => "RekeningPembantuBiayaBpjs",
    "6090" => "RekeningPembantuBiayaPph21",
    "6100010" => "RekeningPembantuBiaya",
    "6100020" => "RekeningPembantuBiayaImport",
    "2010090030" => "RekeningPembantuHutangDevidenItem",
    //akum penyusutan
    "1020010020" => "RekeningPembantuAkumPenyusutanKendaraan",
    "1020020020" => "RekeningPembantuAkumPenyusutanPeralatanKantor",
    "1020040020" => "RekeningPembantuAkumPenyusutanMesinProduksi",
    "1020030020" => "RekeningPembantuAkumPenyusutanMesin",
    "1020041020" => "RekeningPembantuAkumPenyusutanPeralatanProduksi",
    "1020050020" => "RekeningPembantuAkumPenyusutanBangunan",
);
$config['accountChildsItems'] = array(
    "1010010010" => "RekeningPembantuKasItem",//kas
    "1010010040" => "RekeningPembantuKasItem",//pettycash
    "1010010020" => "RekeningPembantuValasItem",//valas
    "1010030030" => "RekeningPembantuProduk",//persediaan produk
    "1010030070" => "RekeningPembantuProduk",//persediaan produk rakitan
    "1010030010" => "RekeningPembantuSupplies",//persediaan supplies
    "2020020" => "RekeningPembantuBank",//hutang bank
    "2010010" => "RekeningPembantuSupplierItem",//hutang dagang
    "hutang uang muka" => "RekeningPembantuSupplierItem",
    "1010040050" => "RekeningPembantuSupplierItem",//ppn in
    "1010040070" => "RekeningPembantuSupplierItem",//ppn in jasa
    "1010020030" => "ComRekeningPembantuPiutangSupplierMain",//piutang pembelian
    "1010020010" => "RekeningPembantuCustomerItem",//piutang dagang
    "1010020050" => "RekeningPembantuCustomerItem",//piutang dagang jasa
    "piutang lain" => "RekeningPembantuPiutangLain",
    "1010050010" => "RekeningPembantuUangMuka",//uang muka dibayar
    "1010050020" => "RekeningPembantuUangMukaMain",//uang muka valas
    "sewa dibayar dimuka" => "RekeningPembantuSewa",
    "2010050" => "RekeningPembantuCustomerItem",//hutang ke konsumen
//    "2010060" => "RekeningPembantuCustomerItem",//hutang ke konsumen
    "2010100" => "RekeningPembantuCustomerValasItem",//hutang valas ke konsumen
    "efisiensi operasional" => "RekeningPembantuEfisiensi",
    "1010060010" => "RekeningPembantuAntarcabangItem",//piutang cabang
    "1010060040" => "RekeningPembantuAntarcabangItem",//piutang biaya cabang
    "2040010" => "RekeningPembantuAntarcabangItem",//hutang ke pusat
    "2010040" => "RekeningPembantuSupplierItem",//hutang biaya
    "2010020" => "RekeningPembantuSupplierItem",//hutang sewa
    "2020010" => "RekeningPembantuHutangSahamItem",//hutang saham
    "3010020" => "RekeningPembantuModalItem",//hutang saham
    "aktiva tetap" => "RekeningPembantuAktivaTetap",
    "aktiva belum ditempatkan" => "RekeningPembantuAktivaBelumDitempatkan",//pindah semebntara
    "akum penyu aktiva tetap" => "RekeningPembantuAkumPenyusutanAktivaTetap",
    "akum penyu kendaraan" => "RekeningPembantuAkumPenyusutanKendaraan",
    "akum penyu peralatan kantor" => "RekeningPembantuAkumPenyusutanPeralatanKantor",
    "akum penyu peralatan produksi" => "RekeningPembantuAkumPenyusutanPeralatanProduksi",
    "akum penyu mesin" => "RekeningPembantuAkumPenyusutanMesin",
//    "akum penyu aktiva tetap" => "RekeningPembantuAkumPenyusutanAktivaTetapAdjust",
//    "aktiva tetap tak berwujud" => "RekeningPembantuAktivaTetapTakBerwujud",
    "piutang valas" => "RekeningPembantuCustomerValas",
    "biaya operasional" => "RekeningPembantuBiayaOperasional",
    "modal" => "RekeningPembantuModal",
    "hutang jangka panjang" => "RekeningPembantuHutangJangkaPanjang",
    "biaya bunga" => "RekeningPembantuLoanItem",

    "biaya" => "RekeningPembantuBiaya",
    "biaya import" => "RekeningPembantuBiayaImport",
    "biaya umum" => "RekeningPembantuBiayaUmum",
    "6030" => "RekeningPembantuBiayaUmum",
    "biaya usaha" => "RekeningPembantuBiayaUsaha",
    "6010" => "RekeningPembantuBiayaUsaha",
    "biaya produksi" => "RekeningPembantuBiayaProduksi",
    "6020" => "RekeningPembantuBiayaProduksi",
    "beban harus dibayar" => "RekeningPembantuBebanHarusDibayar",
    "pendapatan" => "RekeningPembantuPendapatan",
    "laba ditahan" => "RekeningPembantuLabaDitahan",
    "beban lain lain" => "RekeningPembantuBebanLainLain",
    "hutang gaji" => "RekeningPembantuAntarcabang",
    "2040020" => "RekeningPembantuAntarcabang",//hutang biaya ke pusat
    "2040030" => "RekeningPembantuAntarcabang",//hutang aktiva tetap pada dc
    "2010030" => "RekeningPembantuSupplierItem",//hutang aktiva tetap
    "piutang aktiva tetap cabang" => "RekeningPembantuAntarcabang",
//    "penyusutan" => "RekeningPembantuDepresiasi",
    "penyusutan kendaraan" => "RekeningPembantuDepresiasi",//p
    "penyusutan peralatan kantor" => "RekeningPembantuDepresiasi",//p
    "biaya sewa" => "RekeningPembantuBiayaSewa",
    "penyusutan mesin produksi" => "RekeningPembantuDepresiasi",
    "penyusutan mesin" => "RekeningPembantuDepresiasi",
    "penyusutan bangunan" => "RekeningPembantuDepresiasi",
    "perlengkapan umum" => "RekeningPembantuDepresiasi",
    "penyusutan tanah dan bangunan" => "RekeningPembantuDepresiasi",

    "6040010" => "RekeningPembantuDepresiasi",
    "6040020" => "RekeningPembantuDepresiasi",
    "6040030" => "RekeningPembantuDepresiasi",
    "6040040" => "RekeningPembantuDepresiasi",
    "6040050" => "RekeningPembantuDepresiasi",
    "6040060" => "RekeningPembantuDepresiasi",


    "overhead" => "RekeningPembantuBiayaKomposisiProduksi",
    "5020030" => "RekeningPembantuBiayaKomposisiProduksi",//direct labor
    "5020020" => "RekeningPembantuBiayaKomposisiProduksi",//delivery cost
    "5020040" => "RekeningPembantuBiayaKomposisiProduksi",//quality
    "5020050" => "RekeningPembantuBiayaKomposisiProduksi",//bahan baku
    "7010150" => "RekeningPembantuLRLainlainDetail",//pendapatan lain lain
    "7010170" => "RekeningPembantuPendapatanItem",//laba lain lain
    // "7010150" => "RekeningPembantuLRLainlain",//laba lain lain unutk builder auto adjustment jadi digeser ke detail


//    "overhead" => "RekeningPembantuEfisiensiBiaya",

    "3020010" => "RekeningPembantuEfisiensiBiayaMain",
    "3020010020" => "RekeningPembantuEfisiensiBiaya",
    "3020010030" => "RekeningPembantuEfisiensiBiaya",

    "pph25" => "RekeningPembantuPph",
    "pph4 ayat 2" => "RekeningPembantuPph",
    "hutang ke pemegang saham" => "RekeningPembantuHutangSaham",
    "hutang ke pihak lain" => "RekeningPembantuHutangPihakLain",
    "hutang biaya bunga" => "RekeningPembantuHutangBiayaBunga",
    "hutang pph23" => "RekeningPembantuPph",
//    "hutang pph4 ayat 2" => "RekeningPembantuPph",
    "efisiensi biaya" => "RekeningPembantuEfisiensiBiayaMain",
    "hutang lain ppv cabang" => "RekeningPembantuAntarcabang",
    // "rugilaba lain lain" => "RekeningPembantuLRLainlain",
//    "aktiva belum ditempatkan" => "RekeningPembantuAktivaBelumDitempatkan",
    "laba lain lain" => "RekeningPembantuLRLainlain",
    //---
    "4010" => "RekeningPembantuPenjualanItem",
    "5010" => "RekeningPembantuHppItem",
    "6050" => "RekeningPembantuBiayaGajiItem",
    "6080" => "RekeningPembantuBiayaBpjsItem",
    "6090" => "RekeningPembantuBiayaPph21Item",
    "6100010" => "RekeningPembantuBiaya",
    "6100020" => "RekeningPembantuBiayaImport",
);
$config['accountSubChilds__'] = array(
    "kendaraan" => "RekeningPembantuAktivaBerwujud",
    "peralatan" => "RekeningPembantuAktivaBerwujud",
    "peralatan kantor" => "RekeningPembantuAktivaBerwujud",
    "tanah dan bangunan" => "RekeningPembantuAktivaBerwujud",
    "mesin produksi" => "RekeningPembantuAktivaBerwujud",
    "peralatan produksi" => "RekeningPembantuAktivaBerwujud",
    "akum penyu kendaraan" => "RekeningPembantuAkumPenyusutanKendaraan",
    "akum penyu peralatan kantor" => "RekeningPembantuAkumPenyusutanPeralatanKantor",
    "akum penyu peralatan produksi" => "RekeningPembantuAkumPenyusutanPeralatanProduksi",
    "akum penyu mesin produksi" => "RekeningPembantuAkumPenyusutanMesinProduksi",
    "akum penyu mesin" => "RekeningPembantuAkumPenyusutanMesin",
    "hutang bank" => "RekeningPembantuRelasiRekeningKoran",
    //---------------------------------------
    "2010050" => "RekeningPembantuCustomerDetail",//hutang ke konsumen
    "1010020030" => "RekeningPembantuPiutangSupplierDetailMain",//piutang pembelian
);
$config['accountSubChilds'] = array(
//    "kendaraan" => "RekeningPembantuAktivaBerwujud",
//    "peralatan" => "RekeningPembantuAktivaBerwujud",
//    "peralatan kantor" => "RekeningPembantuAktivaBerwujud",
//    "tanah dan bangunan" => "RekeningPembantuAktivaBerwujud",
//    "mesin produksi" => "RekeningPembantuAktivaBerwujud",
//    "peralatan produksi" => "RekeningPembantuAktivaBerwujud",
//
//    "akum penyu kendaraan" => "RekeningPembantuAkumPenyusutanKendaraan",
//    "akum penyu peralatan kantor" => "RekeningPembantuAkumPenyusutanPeralatanKantor",
//    "akum penyu peralatan produksi" => "RekeningPembantuAkumPenyusutanPeralatanProduksi",
//    "akum penyu mesin produksi" => "RekeningPembantuAkumPenyusutanMesinProduksi",
//
//    "hutang bank" => "RekeningPembantuRelasiRekeningKoran",
    // "1010030" => "RekeningPembantuProduk",//persediaan

    "1010010" => "RekeningPembantuKas",//kas
    "1010010010" => "RekeningPembantuKas",//kas
    "1010010020" => "RekeningPembantuValas",//valas
    "1010010030" => "RekeningPembantuSupplier",//piutang pembelian / credit note
    "1010010040" => "RekeningPembantuKas",//pettycash
    "1010020010" => "RekeningPembantuCustomer",//piutang dagang lokal
    "1010020020" => "RekeningPembantuCustomer",//piutang dagang eksport
    "1010020030" => "RekeningPembantuPiutangSupplierDetailItem",///klaim hadiah
    "1010020040" => "RekeningPembantuSupplier",//piutang usaha jasa
    "1010020050" => "RekeningPembantuCustomer",//piutang retensi
//    "1010020060" => "RekeningPembantuSupplier",//piutang pembelian, cn vendor

    "1010030010" => "RekeningPembantuSupplies",//persediaan supplies
    "1010030020" => "RekeningPembantuSuppliesRiil",//persediaan supplies riil
    "1010030" => "RekeningPembantuProduk",//persediaan produk
    // "1010030030" => "RekeningPembantuProduk",//persediaan produk
    "1010030040" => "RekeningPembantuProdukRiil",//persediaan produk riil
//    "1010030050" => "RekeningPembantuProdukRiil",//persediaan produk riil
//    "1010030060" => "RekeningPembantuProdukRiil",//persediaan produk riil
//    "1010030070" => "RekeningPembantuProdukRiil",//persediaan produk riil


    "1010050010" => "RekeningPembantuSupplier",    // uang muka dibayar ke vendor nonvalas
    "1010050020" => "RekeningPembantuSupplier",    // uang muka dibayar ke vendor valas

    "1010060010" => "RekeningPembantuAntarcabang",//piutang cabang
    "1010060020" => "RekeningPembantuAntarcabang",//piutang aktiva tetap
    "1010060030" => "RekeningPembantuAntarcabang",//piutang ke pusat
    "1010060040" => "RekeningPembantuAntarcabang",//piutang biaya cabang


    "2010010" => "RekeningPembantuSupplier",//hutang dagang
    "2010020" => "RekeningPembantuSupplier",//hutang sewa
    "2010030" => "RekeningPembantuSupplier",//hutang aktiva tetap
    "2010040" => "RekeningPembantuSupplier",//hutang biaya
    "2010050" => "RekeningPembantuCustomerDetail",//hutang ke konsumen, dipakai oleh PreRekeningValueDetail saat penjualan 03 februari 2024
    "2010060" => "RekeningPembantuSupplier",//hutang bpjs
    "2010070" => "RekeningPembantuSupplier",//hutang biaya bunga
    "2010080" => "RekeningPembantuSupplier",//hutang gaji
    "2010090" => "RekeningPembantuSupplier",//hutang lancar lainnya
    "2010100" => "RekeningPembantuCustomer",//hutang valas ke konsumen
    "2010110" => "RekeningPembantuCustomer",//hutang jasa ke konsumen


    "01040100005" => "RekeningPembantuSupplier", // ppn masukan belum ada faktur
    "01040100006" => "RekeningPembantuSupplier", // ppn masukan sudah ada faktur
    "010405" => "RekeningPembantuSupplier",//piutang pembelian
    "020201" => "RekeningPembantuHutangSaham",//hutang ke pemegang saham
    "020401" => "RekeningPembantuAntarcabang",//hutang ke pusat


//    "2020010" => "RekeningPembantuAntarcabang",//hutang ke pemegang saham
    "2020020" => "RekeningPembantuBank",//hutang bank
    "2020030" => "RekeningPembantuBank",//hutang ke pihak laiin


    "2040010" => "RekeningPembantuAntarcabang",//hutang ke pusat
    "2040020" => "RekeningPembantuAntarcabang",//hutang biaya ke pusat
    "2040030" => "RekeningPembantuAntarcabang",//hutang aktiva tetap ke dc
    "2040040" => "RekeningPembantuAntarcabang",//hutang ke cabang


    "3010010" => "RekeningPembantuModal",//modal saham disetor


    "6010" => "RekeningPembantuBiayaUsaha",//biaya usaha
    "6020" => "RekeningPembantuBiayaProduksi",//biaya produksi
    "6030" => "RekeningPembantuBiayaUmum",//biaya umum
//    "6040" => "RekeningPembantuBiayaUmum",//biaya penyusutan
//    "6050" => "RekeningPembantuBiayaUmum",//biaya gaji
//    "6060" => "RekeningPembantuBiayaUmum",//biaya bunga
//    "6070" => "RekeningPembantuBiayaUmum",//biaya transfer
//    "6080" => "RekeningPembantuBiayaUmum",//biaya bpjs
//    "6090" => "RekeningPembantuBiayaUmum",//biaya pph 21
//    "6100" => "RekeningPembantuBiayaUmum",//biaya

//akumpenyusutan
    "1020010020" => "RekeningPembantuAkumPenyusutanKendaraan",
    "1020020020" => "RekeningPembantuAkumPenyusutanPeralatanKantor",
    "1020040020" => "RekeningPembantuAkumPenyusutanMesinProduksi",
    "1020030020" => "RekeningPembantuAkumPenyusutanMesin",
    "1020041020" => "RekeningPembantuAkumPenyusutanPeralatanProduksi",
    "1020050020" => "RekeningPembantuAkumPenyusutanBangunan",

);
// rekening pembantu level 3 (key berupa rekening)
$config['accountSuperSubChilds'] = array(
    "kendaraan" => "RekeningPembantuAktivaBerwujud",
    "peralatan" => "RekeningPembantuAktivaBerwujud",
    "peralatan kantor" => "RekeningPembantuAktivaBerwujud",
    "tanah dan bangunan" => "RekeningPembantuAktivaBerwujud",
    "mesin produksi" => "RekeningPembantuAktivaBerwujud",
    "mesin" => "RekeningPembantuAktivaBerwujud",
    "peralatan produksi" => "RekeningPembantuAktivaBerwujud",

//    "akum penyu kendaraan" => "RekeningPembantuAkumPenyusutanKendaraan",
//    "akum penyu peralatan kantor" => "RekeningPembantuAkumPenyusutanPeralatanKantor",
//    "akum penyu peralatan produksi" => "RekeningPembantuAkumPenyusutanPeralatanProduksi",
//    "akum penyu mesin produksi" => "RekeningPembantuAkumPenyusutanMesinProduksi",

    "rekening koran" => "RekeningPembantuRekeningKoran",
    "non rekening koran" => "RekeningPembantuRekeningKoran",
//    "uang muka valas" => "RekeningPembantuUangMukaExternMain",

);
// rekening pembantu level 3 (key pembantu berupa non rekening)
$config['accountSuperSubChildsNonRekening'] = array(
    "uang muka valas" => "RekeningPembantuUangMukaExternMain",
);


$config['accountChildItems'] = array(

    "kas" => "RekeningPembantuKasItem",
    "valas" => "RekeningPembantuValasItem",
    "persediaan produk" => "RekeningPembantuProduk",
    "persediaan supplies" => "RekeningPembantuSupplies",
    "hutang dagang" => "RekeningPembantuSupplierItem",
    "ppn in" => "RekeningPembantuSupplierItem",
    "ppn in jasa" => "RekeningPembantuSupplierItem",
    "piutang pembelian" => "RekeningPembantuSupplierItem",
    "piutang dagang" => "RekeningPembantuCustomerItem",
    "hutang ke konsumen" => "RekeningPembantuCustomerItem",
//    "ppn out" => "RekeningPembantuCustomerItem",
    "hutang ke pihak lain" => "RekeningPembantuHutangPihak3Item",
    "efisiensi operasional" => "RekeningPembantuEfisiensi",
    "piutang cabang" => "RekeningPembantuAntarcabangItem",
    "piutang ke pusat" => "RekeningPembantuAntarcabangItem",
    "hutang ke pusat" => "RekeningPembantuAntarcabangItem",
    "hutang ke cabang" => "RekeningPembantuAntarcabangItem",
    "hutang biaya" => "RekeningPembantuSupplierItem",
    "hutang uang muka" => "RekeningPembantuSupplier",
    "hutang sewa" => "RekeningPembantuSupplier",
    "piutang valas" => "RekeningPembantuCustomerValasItem",
    "biaya umum" => "RekeningPembantuBiayaUmum",
    "biaya produksi" => "RekeningPembantuBiayaProduksi",
    "biaya usaha" => "RekeningPembantuBiayaUsaha",
//    "aktiva tetap" => "RekeningPembantuAktivaTetap",
    "aktiva tetap" => "RekeningPembantuAsetBerwujud",
    "piutang lain" => "RekeningPembantuPiutangLainItem",
    "akum penyu aktiva tetap" => "RekeningPembantuAkumPenyusutanAktivaTetapItem",
    "modal" => "RekeningPembantuModalItem",
    "beban harus dibayar" => "RekeningPembantuBebanHarusDibayarItem",
    "pendapatan" => "RekeningPembantuPendapatanItem",
    "laba ditahan" => "RekeningPembantuLabaDitahanItem",
    "beban lain lain" => "RekeningPembantuBebanLainLainItem",
//    "penyusutan" => "RekeningPembantuDepresiasiItem",
    "penyusutan kendaraan" => "RekeningPembantuDepresiasiItem",
    "penyusutan peralatan kantor" => "RekeningPembantuDepresiasiItem",
    "penyusutan peralatan produksi" => "RekeningPembantuDepresiasiItem",
    "biaya sewa" => "RekeningPembantuBiayaSewa",
    "penyusutan mesin produksi" => "RekeningPembantuDepresiasiItem",
    "penyusutan mesin" => "RekeningPembantuDepresiasiItem",
    "penyusutan tanah dan bangunan" => "RekeningPembantuDepresiasiItem",

    "akum penyu kendaraan" => "RekeningPembantuDepresiasiItem",
    "akum penyu peralatan kantor" => "RekeningPembantuDepresiasiItem",
    "akum penyu peralatan produksi" => "RekeningPembantuDepresiasiItem",
    "akum penyu mesin produksi" => "RekeningPembantuDepresiasiItem",
    "akum penyu mesin" => "RekeningPembantuDepresiasiItem",
    "akum penyu tanah dan bangunan" => "RekeningPembantuDepresiasiItem",

    "biaya import" => "RekeningPembantuBiayaImport",
    "biaya sewa" => "RekeningPembantuBiayaSewa",
    "efisiensi biaya" => "RekeningPembantuEfisiensiBiayaMain",
    "rugilaba lain lain" => "RekeningPembantuLRLainlain",
//    "kendaraan" =>"RekeningPembantuAsetBerwujud",
);

$config['accountChildSources'] = array(
    "kas" => "MdlBankAccount",
    "valas" => "MdlCurrency",
    "1010030030" => "MdlProduk2",
    "1010030070" => "MdlProdukRakitan",
    "1010030010" => "MdlSupplies",
    "hutang dagang" => "MdlSupplier",
    "ppn in" => "MdlSupplier",
    "ppn in jasa" => "MdlSupplier",
    "piutang pembelian" => "MdlSupplier",
    "piutang dagang" => "MdlCustomer",
    "hutang ke konsumen" => "MdlCustomer",
    "ppn out" => "MdlCustomer",
    "efisiensi operasional" => "MdlProduk",
    "piutang cabang" => "MdlCabang",
    "piutang ke pusat" => "MdlCabang",
    "hutang ke pusat" => "MdlCabang",
    "hutang ke cabang" => "MdlCabang",
    "hutang biaya" => "MdlSupplier",
    "piutang valas" => "MdlCustomer",
    "aktiva tetap" => "MdlAktivaTetap",
    "aktiva belum ditempatkan" => "MdlFolderAset",
    "akum penyu aktiva tetap" => "MdlDtaAkumPenyusutanAktivaTetap",
    "akum penyu peralatan kantor" => "MdlDtaAkumPenyusutanAktivaTetap",
    "akum penyu peralatan produksi" => "MdlDtaAkumPenyusutanAktivaTetap",
    "akum penyu mesin produksi" => "MdlDtaAkumPenyusutanAktivaTetap",
    "akum penyu mesin" => "MdlDtaAkumPenyusutanAktivaTetap",
    "aktiva tetap tak berwujud" => "MdlDtaAktivaTakBerwujud",
    "biaya operasional" => "MdlDtaBiayaOperasional",
    "modal" => "MdlDtaModal",
    //    "hutang jangka panjang"        => "MdlDtaHutangJangkaPanjang",
    "piutang lain" => "MdlDtaPerson2",
    "biaya" => "MdlExpense",
    "biaya umum" => "MdlDtaBiayaUmum",
    "biaya produksi" => "MdlDtaBiayaProduksi",
    "biaya usaha" => "MdlDtaBiayaUsaha",
    "hutang jangka panjang" => "MdlDtaSupplier2",
    "beban harus dibayar" => "MdlDtaSupplier2",
    "pendapatan" => "MdlDtaSubPendapatan",
    "laba ditahan" => "MdlDtaLabaDitahan",
    "beban lain lain" => "MdlDtaBebanLainLain",
    "uang muka dibayar" => "MdlUangMuka",
    "sewa dibayar dimuka" => "MdlFolderSewa",
    "biaya sewa" => "MdlDtaBiayaSewa",
    "biaya import" => "MdlExpense",
    "hutang bank" => "MdlRekeningKoran",
    "hutang ke pemegang saham" => "MdlDtaModal",
    "hutang ke pihak lain" => "MdlDtaHutangPihak3",
    "hutang biaya bunga" => "MdlDtaModal",
    "hutang pph23" => "MdlDtaModal",
    "hutang pph4 ayat 2" => "MdlDtaModal",
    "efisiensi biaya" => "MdlProdukRakitanPreBiaya",
);

$config['accountChildsLinks'] = array(
    "piutang dagang" => "Ledger/viewMoveDetails/RekeningPembantuCustomer/piutang%20dagang",
    "hutang dagang" => "Ledger/viewMoveDetails/RekeningPembantuSupplier/hutang%20dagang",
    "kas" => "Ledger/viewMoveDetails/RekeningPembantuKas/kas",

    //    "valas" => "valas",
    //    "pettycash" => "pettycash",
    // "piutang cabang" => "piutang cabang",
    // "piutang biaya cabang" => "piutang biaya cabang",
    // "piutang dagang" => "piutang usaha lokal",
    // "piutang valas" => "piutang usaha ekspor",//valas
    //    "piutang pembelian" => "uang muka pembelian",
    // "piutang pembelian" => "pembelian dibayar  dimuka",
    // "piutang lain" => "piutang lain",
    // "credit note" => "credit note",
    // "persediaan produk" => "persediaan barang jadi beli",
    // "persediaan produk rakitan" => "persediaan barang jadi produksi",
    //    "persediaan produk nonactive" => "",
    // "persediaan supplies" => "persediaan bahan baku",
    //    "persediaan supplies produksi" => "",
    // "aktiva tetap" => "aktiva tetap",
    // "aktiva tetap tak berwujud" => "aktiva tetap tak berwujud",
    // "ppn in" => "ppn masukan",
    // ppn masukan (saat terjadi pembelian)
    //    "ppn in jasa" => "",// ppn masukan (saat terjadi pembelian jasa)
    //    "aktiva tetap" => "",
    //

    // "hutang dagang" => "hutang usaha",
    // "hutang ke pusat" => "hutang ke pusat",
    // "hutang jangka panjang" => "hutang jangka panjang",
    // "hutang ppn" => "hutang ppn",
    // "hutang biaya ke pusat" => "hutang biaya ke pusat",
    // "hutang ke konsumen" => "uang muka penjualan lokal",
    // "hutang valas ke konsumen" => "uang muka penjualan ekspor",
    //    "hutang biaya" => "",
    //    "hutang ongkir" => "",
    //    "ongkir" => "",
    //    "hutang install" => "",
    // "ppn out" => "ppn keluaran",
    // "pph25_29" => "pph ps.25/29",
    //    "hutang kontijensi biaya" => "",
    // "hutang lain ppv" => "hutang lain ppv",
    //    "hutang jangka panjang" => "",
    // "beban harus dibayar" => "beban harus dibayar",

    // "modal" => "modal",
    // "modal saham disetor" => "modal saham disetor",
    // "laba ditahan" => "laba ditahan",
    //    "laba" => "",
    //    "rugi" => "",
    // "laba ditempatkan pusat" => "laba ditempatkan pusat",
    // "laba ditempatkan pusatt" => "laba ditempatkan pusatt",

    // "penjualan" => "penjualan",
    //    "jasa kirim" => "",
    // "pendapatan" => "pendapatan",
    // "pendapatan lain_lain" => "pendapatan lain-lain",
    // "laba(rugi) perubahan grade produk" => "(rugi)laba konversi",
    //    "laba(rugi) return produksi" => "",
    //    "efisiensi operasional" => "",
    //    "keutungan kurs" => "",
    //
    //    "penjualan valas" => "",

    // "hpp" => "harga pokok penjualan",
    //    "biaya" => "",
    // "biaya umum" => "beban umum",
    // "biaya usaha" => "beban usaha",
    //    "biaya jasa" => "",
    //    "biaya supplies" => "",
    // "biaya produksi" => "beban produksi",
    // "biaya operasional" => "beban operasional",
    //    "kerugian" => "",
    // "return penjualan" => "return penjualan",
    //    "laba(rugi) selisih persediaan karena fifo" => "", // return pembelian
    //    "laba(rugi) selisih persediaan karena fifo distribusi" => "",
    //    "laba(rugi) selisih persediaan karena fifo pemindahan dc" => "",
    //    "laba(rugi) selisih fifo return pembelian" => "",
    // "diskon" => "diskon",
    // "keuntungan kurs" => "laba selisih kurs",
    // "kerugian kurs" => "rugi selisih kurs",
    // "beban lain lain" => "beban lain-lain",
    // "transfer stok" => "transfer stok",

    //    "ongkir dibayar konsumen" => "",
    //    "ongkos install" => "",
    // "akum penyu aktiva tetap" => "akumulasi penyusutan aktiva tetap",

    //    "laba" => "",
    //    "rugi" => "",
    //    "laba lain lain" => "",
    //    "rugi lain lain" => "",
    //    "rugilaba lain lain" => "",
    //    "labarugi kotor" => "",
    //    "labarugi bersih" => "",
    // "rugilaba" => "laba(rugi)",
    // "penghasilan" => "penghasilan",
    // "biaya" => "biaya",
);

$config['accountChilds2'] = array(
    "kas" => array(
        "RekeningPembantuKas",
    ),
    "pettycash" => array(
        "RekeningPembantuKas",
    ),
    "valas" => array(
        "RekeningPembantuValas",
    ),
    "persediaan produk" => array(
        "RekeningPembantuProduk",
    ),
    "persediaan supplies" => array(
        "RekeningPembantuSupplies",
    ),
    "hutang dagang" => array(
        "RekeningPembantuSupplier",
    ),
    "ppn in" => array(
        "RekeningPembantuSupplier",
    ),
    "piutang pembelian" => array(
        "RekeningPembantuSupplier",
    ),
    "piutang dagang" => array(
        "RekeningPembantuCustomer",
    ),
    "piutang lain" => array(
        "RekeningPembantuPiutangLain",
    ),
    "hutang ke konsumen" => array(
        "RekeningPembantuCustomer",
    ),
//    "ppn out" => array(
//        "RekeningPembantuCustomer",
//    ),
    "hutang valas ke konsumen" => array(
        "RekeningPembantuCustomerValas",
    ),
    "efisiensi operasional" => array(
        "RekeningPembantuEfisiensi",
    ),
    "piutang cabang" => array(
        "RekeningPembantuAntarcabang",
    ),
    "hutang ke pusat" => array(
        "RekeningPembantuAntarcabang",
    ),
    "hutang biaya" => array(
        "RekeningPembantuSupplier",
        "RekeningPembantuAntarcabang",
    ),
    "aktiva tetap" => array(
        "RekeningPembantuAktivaTetap",
    ),
    "akum penyu aktiva tetap" => array(
        "RekeningPembantuAkumPenyusutanAktivaTetap",
    ),
    "aktiva tetap tak berwujud" => array(
        "RekeningPembantuAktivaTetapTakBerwujud",
    ),
    "piutang valas" => array(
        "RekeningPembantuCustomerValas",
    ),
    "biaya operasional" => array(
        "RekeningPembantuBiayaOperasional",
    ),
    "modal" => array(
        "RekeningPembantuModal",
    ),
    "hutang jangka panjang" => array(
        "RekeningPembantuHutangJangkaPanjang",
    ),

    "biaya" => array(
        "RekeningPembantuBiaya",
    ),
    "biaya umum" => array(
        "RekeningPembantuBiayaUmum",
    ),
    "biaya usaha" => array(
        "RekeningPembantuBiayaUsaha",
    ),
    "biaya produksi" => array(
        "RekeningPembantuBiayaProduksi",
    ),
    "beban harus dibayar" => array(
        "RekeningPembantuBebanHarusDibayar",
    ),
    "pendapatan" => array(
        "RekeningPembantuPendapatan",
    ),
//    "laba ditahan" => array(
//"RekeningPembantuLabaDitahan",),
//    "beban lain lain" => array(
//"RekeningPembantuBebanLainLain",),
    "hutang gaji" => array(
        "RekeningPembantuAntarcabang",
    ),
    "hutang biaya ke pusat" => array(
        "RekeningPembantuAntarcabang",
    ),
    "piutang biaya cabang" => array(
        "RekeningPembantuAntarcabang",
    ),

    "overhead" => array(
        "RekeningPembantuBiayaKomposisiProduksi",
    ),
    "direct labor" => array(
        "RekeningPembantuBiayaKomposisiProduksi",
    ),
    "delivery cost" => array(
        "RekeningPembantuBiayaKomposisiProduksi",
    ),
    "quality" => array(
        "RekeningPembantuBiayaKomposisiProduksi",
    ),
    "aktiva belum ditempatkan" => array(
        "MdlFolderAset"
    ),
);


//===config untuk saldo rekening
$config['accountBalanceColumns'] = array(
    "RekeningPembantuKas" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "header" => array(
            "debet" => "balance (IDR)",
        ),
    ),
    "RekeningPembantuPendapatan" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuValas" => array(
        "showValue" => true,
        "showQty" => true,
        "viewedColumns" => array(
            "extern_nama" => "customer name",
            "extern2_nama" => "valas name",
            "qty_kredit" => "kredit (valas)",
            "kredit" => "kredit (IDR)",
            "qty_debet" => "debet (valas)",
            "debet" => "debet (valas)",
        ),
    ),
    "Rekening" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuSupplier" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuPiutangSupplierMain" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuPiutangSupplierDetailMain" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuBank" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuCustomer" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuCustomerDetail" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuAntarcabang" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuProduk" => array(
        "showValue" => true,
        "showQty" => true,
        "viewedColumns" => array(
            //            "kode" => "product code",
            "extern_nama" => "item name",
            //			"qty_kredit"  => "balance (qty)",
            //            "kredit"      => "balance (IDR)",
            "qty_kredit" => "kredit (qty)",
            "kredit" => "kredit (IDR)",
            "qty_debet" => "debet (qty)",
            "debet" => "debet (qty)",
        ),
        "viewFilters" => array(
            "gudang_id=gudang_id",
        ),
        "pairedModel" => array(
            "mdlName" => "MdlProduk2",
            "mdlMethod" => "lookupAll",
            "key" => "id",
            "filters" => array(
                "jenis in ('item', 'item_rakitan', 'item_komposit')",
            ),
            "fieldName" => array(
                // "id" => "pID",
                "kode" => "kode",
                "extern_nama" => "nama",
                "jenis" => "jenis",
                "status" => "status",
                "trash" => "trash",
                "kategori_id" => "kategori_id",
                "kategori_nama" => "kategori_nama",
                "tipe_produk" => "jml_serial",
                "size_nama" => "size_nama",
                "merek_nama" => "merek_nama",
            ),
            "viewedColumns" => array(
                "kode" => "product code",
                "merek_nama" => "merek",
                "kategori_nama" => "category",
                "tipe_produk" => "tipe produk",
            ),
            "jenisItems" => array(
                "item" => "Produk",
                "item_rakitan" => "ProdukRakitan",
                "item_komposit" => "ProdukKomposit",
            ),
            "linkData_history" => "Data/viewHistories/",
        ),

        "additionalPairedModel" => array(
            "mdlNameRek" => "ComRekeningPembantuProduk",
            "mdlMethodRek" => "fetchBalances",
            "mdlMethodRek_moves" => "fetchMoves2_periode",
            "prefix" => "ng_",

            "mdlNameData" => "MdlGudang",
            "mdlMethodData" => "lookupAll",
        ),
        "additionalViewedColumns" => array(
//            "ng_qty_debet" => "qty not good<br>(qty)",
//            "ng_debet" => "balance not good<br>(IDR)",
            "ng_qty_debet" => "gudang project<br>(qty)",
            "ng_debet" => "gudang project<br>(IDR)",
        ),
        "additionalTotalViewedColumns" => array(
            "total_qty_debet" => "total qty<br>(qty)",
            "total_debet" => "total balance<br>(IDR)",
        ),
        "additionalPairSerialViewedColums" => array(
            "jumlah_serial" => "stok serial",
//            "total_debet" => "total balance<br>(IDR)",
        ),
        "additionalPairSerial" => array(
            "mdlSparator" => "Coms",
            "mdlMethod" => "fetchBalances",
            "rekening" => "1010030030",
            "mdlName" => "ComRekeningPembantuProdukPerSerial",
            "ctrlMethode" => "viewSerial",
            "viewedColumns" => array(
//                "extern2_nama"=>"serial",
                "jml_serial" => "serial",
            ),
            "filter" => array(
                "qty_debet>0",
            ),
        ),
        "customLink" => array(
            "qty_debet", "debet", "ng_debet", "ng_qty_debet"
        ),
    ),
    "RekeningPembantuCustomerValas" => array(
        "showValue" => true,
        "showQty" => true,
        "viewedColumns" => array(
            "extern_nama" => "customer name",
            "extern2_nama" => "valas name",
            //			"qty_kredit"  => "balance (qty)",
            //            "kredit"      => "balance (IDR)",
            "qty_kredit" => "kredit (valas)",
            "kredit" => "kredit (IDR)",
            "qty_debet" => "debet (valas)",
            "debet" => "debet (valas)",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuSupplies" => array(
        "showValue" => true,
        "showQty" => true,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(
            "gudang_id=gudang_id",
        ),
        "mdlData" => "MdlSupplies",
        "mdlDataKeys" => array(
            "id", "satuan", "nama"
        ),
        "pairedModel" => array(
            "mdlName" => "MdlSupplies",
            "mdlMethod" => "lookupAll",
            "key" => "id",
            "filters" => array(
                "jenis in ('item')",
            ),
            "fieldName" => array(
                // "id" => "pID",
                "kode" => "kode",
                "extern_nama" => "nama",
                "jenis" => "jenis",
                "status" => "status",
                "trash" => "trash",
            ),
//            "viewedColumns" => array(
//                "kode" => "product code",
//            ),
            "jenisItems" => array(
                "item" => "Supplies",
//                "item_rakitan" => "ProdukRakitan",
//                "item_komposit" => "ProdukKomposit",
            ),
            "linkData_history" => "Data/viewHistories/",
        ),
    ),
    "RekeningPembantuEfisiensi" => array(
        "showValue" => true,
        "showQty" => true,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(
            "gudang_id=gudang_id",
        ),
    ),

    "RekeningPembantuAktivaTetap" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumnsStatus" => true,
        "viewedColumns" => array(
            "extern_nama" => "account name",

            "debet" => "aktiva (IDR)",
            "kredit" => "akumulasi depresiasi (IDR)",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuAkumPenyusutanAktivaTetap" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuBiayaUmum" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuPiutangLain" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuModal" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuBiaya" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuBebanLainLain" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuBiayaUsaha" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuHutangAktivaTetapDc" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuBiayaProduksi" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuBiayaKomposisiProduksi" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuEfisiensiBiayaMain" => array(
        "showValue" => true,
        "showQty" => true,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuAktivaBerwujud" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuDepresiasi" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuPph" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuUangMuka" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuUangMukaMain" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuUangMukaExternMain" => array(
        "showValue" => true,
        "showQty" => true,
        "viewedColumns" => array(
            "extern2_id" => "account ID",
            "extern2_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
            "qty_kredit" => "kredit",
            "qty_debet" => "debet",
        ),
        "viewed2Columns" => array(
            "extern2_id" => "account ID",
            "extern2_nama" => "account name",
//            "kredit" => "kredit",
//            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuBiayaSewa" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuSewa" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(
            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuBiayaImport" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuAktivaBelumDitempatkan" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuAkumPenyusutanAktivaTetapAdjust" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "tCode",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuAkumPenyusutanKendaraan" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "tCode",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "pairedModel" => array(
            "mdlName" => "MdlAsetDetail",
            "mdlMethod" => "lookupAll",
            "key" => "id",
            "filters" => array(
                "jenis in ('item')",
            ),
            "fieldName" => array(
                // "id" => "pID",
                "kode" => "kode",
                "extern_nama" => "nama",
                "serial_no" => "serial_no",
                "status" => "status",
                "trash" => "trash",
//                "kategori_id" => "kategori_id",
//                "kategori_nama" => "kategori_nama",
//                "tipe_produk" => "jml_serial",
//                "size_nama" => "size_nama",
//                "merek_nama" => "merek_nama",
            ),
            "viewedColumns" => array(
                "kode" => "kode*",
                "merek_nama" => "merek",
                "serial_no" => "no seri",
//                "tipe_produk" => "tipe produk",
            ),
            "jenisItems" => array(
                "item" => "AsetDetail",
//                "item_rakitan" => "ProdukRakitan",
//                "item_komposit" => "ProdukKomposit",
            ),
            "linkData_history" => "Data/viewHistories/",
        ),
    ),
    "RekeningPembantuAkumPenyusutanPeralatanProduksi" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "tCode",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuAkumPenyusutanPeralatanKantor" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "tCode",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuAkumPenyusutanBangunan" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "tCode",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuHutangSaham" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "tCode",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuHutangPihakLain" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "tCode",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuHutangBiayaBunga" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "tCode",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuLoanItem" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "tCode",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuPph" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "tCode",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuEfisiensiBiayaMain" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "tCode",
//            "qty_kredit" => "kredit (qty)",
//            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuLRLainlain" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "tCode",
//            "qty_kredit" => "kredit (qty)",
//            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuCreditNote" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "tCode",
//            "qty_kredit" => "kredit (qty)",
//            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),

    "RekeningPembantuRelasiRekeningKoran" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuRekeningKoran" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuLabaDitahan" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuPiutangSupplierDetailItem" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
);
$config['accountBalanceAdditionalColumns'] = array(
    "aktiva tetap" => array(
        "netto" => "netto (IDR)",
    ),

);

$config['accountBalanceAdvanceColumns'] = array(
    "piutang dagang" => array(
        "loadModel" => "MdlTransaksi",
        "model" => "MdlTransaksi",
        "method" => "lookupAllDueDate",
        "filter" => array(
            "status=1",
        ),
        "header" => array(
            "due_date" => "Due Date",
            "aging" => "Aging (Days)",
            "over_due" => "Over Due",
        ),
    ),
);

//==config untuk mutasi rekening
$config['accountMoveColumns'] = array(

    "Rekening" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "dtime" => "date",
            "urut" => "no urut",
            "ids_his" => "reference number",
            "transaksi_no" => "invoice number",
            "description" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "cabang_nama" => "branch",


            "referenceNomer" => "cancelled number",
            "description_main_followup" => "vendor's invoice referral",
            "contra_account" => "contra accounts",

            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "linkToDetail" => array(
            "suppliers_nama" => array(
                "key" => "suppliers_id",
                "rekening" => array(
                    // rekening (location page yang dibuka) => pairing arah link
                    "hutang dagang" => array(
                        "com" => "RekeningPembantuSupplier",
                        "rek" => "hutang dagang",
                    ),
                    "persediaan produk" => array(
                        "com" => "RekeningPembantuSupplier",
                        "rek" => "hutang dagang",
                    ),
                    "persediaan supplies" => array(
                        "com" => "RekeningPembantuSupplier",
                        "rek" => "hutang dagang",
                    ),
                ),
            ),
            "customers_nama" => array(
                "key" => "customers_id",
                "rekening" => array(
                    // rekening (location page yang dibuka) => pairing arah link
                    "piutang dagang" => array(
                        "com" => "RekeningPembantuCustomer",
                        "rek" => "piutang dagang",
                    ),
                    "persediaan produk" => array(
                        "com" => "RekeningPembantuCustomer",
                        "rek" => "piutang dagang",
                    ),
                    "persediaan supplies" => array(
                        "com" => "RekeningPembantuCustomer",
                        "rek" => "piutang dagang",
                    ),
                ),
            ),
        ),
        "baselink" => "Ledger/viewMoveDetails/",
        "extHistoryFields" => array(
            "review_details" => "transaksi_id",
        ),

        "viewedColumnsAdditional" => array(
            // rekening
            "kas" => array(
//                "pairRegistries" => "main",
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
                    "cash_account" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "cash_account" => array(
                        "cash_account__label" => NULL,
                        "cash_account_source__label" => "sumber kas",
                        "cash_account_target__label" => "kas tujuan",
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),
            "piutang cabang" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),
            "piutang dagang" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),
            "piutang biaya cabang" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
//                    "cash_account" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
//                    "cash_account" => array(
//                        "cash_account__label" => NULL,
//                        "cash_account_source__label" => "sumber kas",
//                        "cash_account_target__label" => "kas tujuan",
//                    ),
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),
            "piutang pembelian" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
//                    "cash_account" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
//                    "cash_account" => array(
//                        "cash_account__label" => NULL,
//                        "cash_account_source__label" => "sumber kas",
//                        "cash_account_target__label" => "kas tujuan",
//                    ),
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),
            "uang muka dibayar" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
                    "cash_account" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "cash_account" => array(
                        "cash_account__label" => NULL,
                        "cash_account_source__label" => "sumber kas",
                        "cash_account_target__label" => "kas tujuan",
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),

            "persediaan produk" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),
            "persediaan supplies" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
//                    "cash_account" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
//                    "cash_account" => array(
//                        "cash_account__label" => NULL,
//                        "cash_account_source__label" => "sumber kas",
//                        "cash_account_target__label" => "kas tujuan",
//                    ),
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),

            "hutang dagang" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
                    "description_main_followup" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
//                    "cash_account" => array(
//                        "cash_account__label" => NULL,
//                        "cash_account_source__label" => "sumber kas",
//                        "cash_account_target__label" => "kas tujuan",
//                    ),
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                    "description_main_followup" => array(
                        "description_main_followup" => NULL,
                    ),
                ),
            ),
            "hutang biaya" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
//                    "cash_account" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
//                    "cash_account" => array(
//                        "cash_account__label" => NULL,
//                        "cash_account_source__label" => "sumber kas",
//                        "cash_account_target__label" => "kas tujuan",
//                    ),
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),
            "hutang bank" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
//                    "cash_account" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
//                    "cash_account" => array(
//                        "cash_account__label" => NULL,
//                        "cash_account_source__label" => "sumber kas",
//                        "cash_account_target__label" => "kas tujuan",
//                    ),
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),
            "hutang aktiva tetap" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
//                    "cash_account" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
//                    "cash_account" => array(
//                        "cash_account__label" => NULL,
//                        "cash_account_source__label" => "sumber kas",
//                        "cash_account_target__label" => "kas tujuan",
//                    ),
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),
            "hutang ke pemegang saham" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
//                    "cash_account" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
//                    "cash_account" => array(
//                        "cash_account__label" => NULL,
//                        "cash_account_source__label" => "sumber kas",
//                        "cash_account_target__label" => "kas tujuan",
//                    ),
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),
            "hutang biaya bunga" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
//                    "cash_account" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
//                    "cash_account" => array(
//                        "cash_account__label" => NULL,
//                        "cash_account_source__label" => "sumber kas",
//                        "cash_account_target__label" => "kas tujuan",
//                    ),
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),
            "hutang ke pusat" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
                    "description_main_followup" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
//                    "cash_account" => array(
//                        "cash_account__label" => NULL,
//                        "cash_account_source__label" => "sumber kas",
//                        "cash_account_target__label" => "kas tujuan",
//                    ),
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                    "description_main_followup" => array(
                        "description_main_followup" => NULL,
                    ),
                ),
            ),

            "biaya bunga" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
//                    "cash_account" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
//                    "cash_account" => array(
//                        "cash_account__label" => NULL,
//                        "cash_account_source__label" => "sumber kas",
//                        "cash_account_target__label" => "kas tujuan",
//                    ),
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),
            "biaya umum" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
//                    "cash_account" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
//                    "cash_account" => array(
//                        "cash_account__label" => NULL,
//                        "cash_account_source__label" => "sumber kas",
//                        "cash_account_target__label" => "kas tujuan",
//                    ),
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),
            "biaya usaha" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
//                    "cash_account" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
//                    "cash_account" => array(
//                        "cash_account__label" => NULL,
//                        "cash_account_source__label" => "sumber kas",
//                        "cash_account_target__label" => "kas tujuan",
//                    ),
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),
            "biaya produksi" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
//                    "cash_account" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
//                    "cash_account" => array(
//                        "cash_account__label" => NULL,
//                        "cash_account_source__label" => "sumber kas",
//                        "cash_account_target__label" => "kas tujuan",
//                    ),
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),
            "beban lain lain" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
//                    "cash_account" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
//                    "cash_account" => array(
//                        "cash_account__label" => NULL,
//                        "cash_account_source__label" => "sumber kas",
//                        "cash_account_target__label" => "kas tujuan",
//                    ),
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),

            "laba(rugi) perubahan grade produk" => array(
//                "pairRegistries" => "main",
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
//                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
//                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
                    "referenceNomer" => array(
//                        "cash_account__label" => NULL,
//                        "cash_account_source__label" => "sumber kas",
                        "referenceNomer" => NULL,
                    ),
//                    "details_item" => array(
//                        "nama" => NULL,
//                    ),
                ),
            ),
            "selisih persediaan karena fifo" => array(
//                "pairRegistries" => "main",
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
//                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
//                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
//                    "details_item" => array(
//                        "nama" => NULL,
//                    ),
                ),
            ),
        ),
    ),
    "RekeningPembantuModal" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
//            "keterangan" => "description",
            "jenis" => "note",
//            "suppliers_nama" => "vendor",
//            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuKas" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
//            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuPettycash" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "dtime" => "date",
            "jenis" => "note",
//            "suppliers_nama" => "vendor",
//            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "items_fields" => "isi",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuValas" => array(
        "showValue" => true,
        "showQty" => true,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
//            "keterangan" => "description",
            "jenis" => "note",
//            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
            "qty_kredit_akhir" => "kredit bal (valas)",
            "qty_debet_akhir" => "debet bal (valas)",
            "kredit_akhir" => "kredit bal (IDR)",
            "debet_akhir" => "debet bal (IDR)",
        ),

    ),
    "RekeningPembantuSupplier" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
            //            "kredit_akhir"      => "BAL (kredit)",
            //            "debet_akhir"       => "BAL (debet)",
        ),

    ),
    "RekeningPembantuPiutangSupplierMain" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
            //            "kredit_akhir"      => "BAL (kredit)",
            //            "debet_akhir"       => "BAL (debet)",
        ),

    ),
    "RekeningPembantuPiutangSupplierDetailMain" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
            //            "kredit_akhir"      => "BAL (kredit)",
            //            "debet_akhir"       => "BAL (debet)",
        ),

    ),
    "RekeningPembantuPiutangSupplierDetailItem" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
            //            "kredit_akhir"      => "BAL (kredit)",
            //            "debet_akhir"       => "BAL (debet)",
        ),

    ),
    "RekeningPembantuBank" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
//            "suppliers_nama" => "vendor",
//            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuCustomer" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
//            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuCustomerDetail" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
//            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuAntarcabang" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
//            "keterangan" => "description",
            "jenis" => "note",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuProduk" => array(
        "showValue" => false,
        "showQty" => true,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
//            "keterangan" => "description",
            "description" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor/supplier",
            "customers_nama" => "customer",
            "cabang_nama" => "branch",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit (IDR)",
            "debet" => "debet (IDR)",
            //
            "qty_kredit_akhir" => "kredit bal (qty)",
            "qty_debet_akhir" => "debet bal (qty)",
            "kredit_akhir" => "kredit bal (IDR)",
            "debet_akhir" => "debet bal (IDR)",

        ),
        "headerLooping" => array(
            "unit" => array(
                "label" => "unit",
                "attrHeader" => "class='bg-info text-center'",
            ),
            "unitPrice" => array(
                "label" => "price per unit",
                "attrHeader" => "class='bg-info text-center'",
            ),
            "sumPrice" => array(
                "label" => "total value",
                "attrHeader" => "class='bg-info text-center'",
            ),
        ),

    ),
    "RekeningPembantuCustomerValas" => array(
        "showValue" => true,
        "showQty" => true,
        "viewedColumns" => array(
            "extern_nama" => "customer name",
            "extern2_nama" => "valas name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
//            "suppliers_nama" => "vendor",
//            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (valas)",
            "qty_debet" => "debet (valas)",
            "kredit" => "kredit (IDR)",
            "debet" => "debet (IDR)",
            //
            "qty_kredit_akhir" => "kredit bal (valas)",
            "qty_debet_akhir" => "debet bal (valas)",
            "kredit_akhir" => "kredit bal (IDR)",
            "debet_akhir" => "debet bal (IDR)",

        ),

    ),
    "RekeningPembantuSupplies" => array(
        "showValue" => false,
        "showQty" => true,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
//            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
//            "customers_nama" => "customer",
            "cabang_nama" => "branch",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit (IDR)",
            "debet" => "debet (IDR)",
            //
            "qty_kredit_akhir" => "kredit bal (qty)",
            "qty_debet_akhir" => "debet bal (qty)",
            "kredit_akhir" => "kredit bal (IDR)",
            "debet_akhir" => "debet bal (IDR)",
        ),
        "headerLooping" => array(
            "unit" => array(
                "label" => "unit",
                "attrHeader" => "class='bg-info text-center'",
            ),
            "unitPrice" => array(
                "label" => "price per unit",
                "attrHeader" => "class='bg-info text-center'",
            ),
            "sumPrice" => array(
                "label" => "total value",
                "attrHeader" => "class='bg-info text-center'",
            ),
        ),
    ),
    "RekeningPembantuEfisiensi" => array(
        "showValue" => false,
        "showQty" => true,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "tCode",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuBiaya" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuBebanLainLain" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuBiayaUmum" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuBiayaUsaha" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuBiayaProduksi" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuHutangAktivaTetapDc" => array(
        "showValue" => false,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuDepresiasi" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuAktivaTetap" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuPph" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuUangMuka" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
//            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
//            "qty_kredit" => "kredit (qty)",
//            "qty_debet" => "debet (qty)",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuUangMukaMain" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
//            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
//            "qty_kredit" => "kredit (qty)",
//            "qty_debet" => "debet (qty)",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuUangMukaExternMain" => array(
        "showValue" => true,
        "showQty" => true,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
//            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuBiayaSewa" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuSewa" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuBiayaImport" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuAkumPenyusutanKendaraan" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
//            "suppliers_nama" => "vendor",
//            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuAkumPenyusutanPeralatanProduksi" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
//            "suppliers_nama" => "vendor",
//            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuAkumPenyusutanPeralatanKantor" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
//            "suppliers_nama" => "vendor",
//            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuAkumPenyusutanBangunan" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
//            "suppliers_nama" => "vendor",
//            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuAktivaBelumDitempatkan" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
//            "suppliers_nama" => "vendor",
//            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuAkumPenyusutanAktivaTetap" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
//            "suppliers_nama" => "vendor",
//            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuHutangSaham" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
//            "suppliers_nama" => "vendor",
//            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuHutangPihakLain" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
//            "suppliers_nama" => "vendor",
//            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuHutangBiayaBunga" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
//            "suppliers_nama" => "vendor",
//            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuLoanItem" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuCreditNote" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
//    "RekeningPembantuPph" => array(
//        "showValue" => true,
//        "showQty" => false,
//        "viewedColumns" => array(
//            "extern_nama" => "account name",
//            "dtime" => "date",
//            "keterangan" => "description",
//            "jenis" => "tCode",
//            "qty_kredit" => "kredit (qty)",
//            "qty_debet" => "debet (qty)",
//            "kredit" => "kredit",
//            "debet" => "debet",
//        ),
//    ),
    "RekeningPembantuEfisiensiBiayaMain" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "tCode",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuEfisiensiBiaya" => array(
        "showValue" => true,
        "showQty" => true,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "tCode",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),

    "RekeningPembantuRelasiRekeningKoran" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
//            "keterangan" => "description",
            "jenis" => "tCode",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuRekeningKoran" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
//            "keterangan" => "description",
            "jenis" => "tCode",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuLRLainlain" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
//            "keterangan" => "description",
            "jenis" => "tCode",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
);

$config['accountBalanceProtections'] = array(
//    "kas",
//    "valas",
//    "persediaan produk",
//    "persediaan produk riil",
//    "persediaan produk rakitan",
//    "persediaan supplies",
//    "persediaan supplies riil",
//
//    "piutang dagang",
//    "piutang cabang",
//    "piutang pembelian",
//    "piutang valas",
//    "piutang supplier",
//    "hutang ke konsumen",
//    "hutang ke pusat",
//    "hutang dagang",
//    "hutang bpjs",
//    "hutang aktiva tetap",
//    "piutang aktiva tetap cabang",
//    "aktiva belum ditempatkan",
//    "uang muka",
//    "uang muka dibayar",
//    "uang muka valas",

    "1010010010",//    "kas",
    "1010010020",//    "valas",
    "1010010030",//    "credit note",

    "1010020010",//Piutang Usaha Lokal

    "1010030030",//Persediaan Produk
    "1010030040",//Persediaan Produk Riil
    "1010030060",//Persediaan Project Cost
    "1010030070",
    "1010030010",
    "1010030020",

    "1010020010",
    "1010060010",//Piutang Cabang
    "1010020030",
    "1010020040",
//    "piutang supplier",
    "2010050",//Hutang Ke Konsumen

    "2040010",//    "hutang ke pusat",
    "2040020",//    "hutang biaya ke pusat",
    "2040030",//Hutang Aktiva Tetap Pada Dc
    "2040040",//Hutang Ke Cabang

    "2010010",
    "2010060",
    "2010030",
    "2010080",
    "1010060020",//Piutang Aktiva Tetap Cabang
    "1020070010",
//    "uang muka",
    "1010050010",
    "1010050020",
    "1010060030",//Piutang Ke Pusat
    "1010060040",//Piutang Biaya Cabang
);
$config['accountBalanceConsolidation'] = array(
    "piutang cabang",
    "hutang ke pusat",
    "piutang biaya cabang",
    "hutang biaya ke pusat",
    "piutang ke pusat",
    "hutang ke cabang",
    "piutang aktiva tetap cabang",
    "hutang aktiva tetap pada dc",
    //----
    "1010060010",
    "2040010",
    "1010060040",
    "2040020",
    "1010060030",
    "2040040",
    "1010060020",
    "2040030",
    //----label persediaan riil
    "1010030020",
    "1010030040",
    //----
    "1010060050",
    "2040050",
);

$config['accountBalanceColumLocker'] = array(
    "RekeningPembantuKas" => array(
        "enabledView" => true,
        "mdlName" => "MdlLockerValue",
        "state" => array(
            "hold" => array(
                "label" => "deposit in transit (idr)",
                "filters" => array(
                    "jenis=.kas",
                    "state=.hold",
                    "transaksi_id >0",
//                    "nilai >0"
                ),
                "viewedColums" => array(
                    "nilai" => "on proses otorisasi",
                ),
            ),
            "active" => array(
                "label" => "effective balance (idr)",
                "filters" => array(
                    "jenis=.kas",
                    "state=.active",
                    "transaksi_id=.0",
                ),
                "viewedColums" => array(
                    "nilai" => "available",
                ),
            ),

        ),

        "mdlNameRekeningCache" => "ComRekeningPembantuKas",
        "filter" => array(
            "periode='forever'",
            "rekening='kas'",
        ),
        "label" => "deposit in transit",
    ),
    "RekeningPembantuProduk" => array(
        "enabledView" => false,
        "mdlName" => "MdlLockerStock",
        "state" => array(
            "hold" => array(
                "label" => "stock in transit",
                "filters" => array(
                    "jenis=.produk",
                    "state=.hold",
                    "transaksi_id >0",
                ),
                "viewedColums" => array(
                    "jumlah" => "",
                ),
            ),
            "active" => array(
                "label" => "effective stock",
                "filters" => array(
                    "jenis=.produk",
                    "state=.active",
                    "transaksi_id=.0",
                ),
                "viewedColums" => array(
                    "jumlah" => "",
                ),
            ),

        ),

        "mdlNameRekeningCache" => "ComRekeningPembantuProduk",
        "filter" => array(
            "periode='forever'",
            "rekening='persediaan produk'",
        ),
        "label" => "stock in transit",
    ),
);

$config['accountRekeningBypass'] = array(
    "hutang biaya",
    "kas",
);

//--------------------------------------------------------
$config['accountElementMutasi'] = array(
    "center" => array(
        "creditAmount" => array(
            "link" => "Ledger/viewMoveDetails/RekeningPembantuSupplier/piutang pembelian/",
        ),
        "uangMuka" => array(
            "link" => "Ledger/viewMoveDetails/RekeningPembantuUangMuka/uang muka dibayar/",
        ),
    ),
    "branch" => array(
        "uangMuka" => array(
            "link" => "Ledger/viewMoveDetails/RekeningPembantuCustomer/hutang ke konsumen/",
        ),
        "customerDetails" => array(
            "link" => "Ledger/viewMoveDetails/RekeningPembantuCustomer/piutang dagang/",
        ),
        "cash_account_source" => array(
            "source" => "cash_account_source",
            "link" => "Ledger/viewMoveDetails/RekeningPembantuKas/kas/",
        ),
    ),

);

//config yang dibaca oleh pajak
$config['pairPajak'] = array(
    //akan diselect dari session login
    "pkp" => array(
        "overWriteValidate" => array(
            "barang" => array(
                "purcashing" => array(
                    "pkp" => "11",
                    "non_pkp" => "0",
                ),
                "sales" => array(
                    "pkp" => "11",
                    "non_pkp" => "0",
                ),
            ),
            "jasa" => array(
                "purcashing" => array(
                    "npwp" => "11",
                    "non_pkp" => "0",
                ),
                "sales" => array(
                    "pkp" => "11",
                    "non_pkp" => "0",
                ),
            ),
        ),
        "over_write" => array(
            //list Transksi pengecualian true akan di over ride dengan ppn 0%, untuk jembatan pkp tapi ppn 0
            //            "582" => "enable",
            //            "580" => "true",
            //            "466" => "true",
            //            "461" => "true",
            //            "2463" => "true",
        ),
        "value" => array(
            "enable" => array("ppnFactor" => "0"),//pkp tapi ppn 0%
            "default" => array("ppnFactor" => "12","ppnConstanta"=>"11/12"),//default
//            "ppnKonstatnta" => array("ppnFactor" => "11/12"),//default
        ),


    ),

    "non_pkp" => array(
        "overWriteValidate" => array(
            "purcashing" => array(
                "pkp" => "0",//untuk hitung gerbang ppn
                "non_pkp" => "0",
            ),
            "sales" => array(
                "pkp" => "0",//untuk hitung gerbang ppn
                "non_pkp" => "0",
            ),
        ),
        "over_write" => array(
            //listransksi pengecualian
            //            "582"=>"false",
            //            "580"=>"false",
            //            "466"=>"true",
            //            "461"=>"false",
        ),
        "value" => array(
            "enable" => array("ppnFactor" => "0"),//pkp tapi ppn 0%
            "default" => array("ppnFactor" => "0"),//default
        ),
    ),
);
//-----------------------------------------------------
$config['accountChildUmumItem'] = array(
    "1010010" => "RekeningPembantuKasSetaraKas",//kas setara kas
    "1010010010" => "RekeningPembantuKas",//kas
//    "010102" => "RekeningPembantuValas",//valas
//    "010304" => "RekeningPembantuProduk",//persediaan produk
//    "020202" => "RekeningPembantuBank",//hutang bank
//    "020101" => "RekeningPembantuSupplier",//hutang dagang
//    "01040105" => "RekeningPembantuSupplier",//
//    "01040107" => "RekeningPembantuSupplier",//
//    "010405" => "RekeningPembantuSupplier",//piutang pembelian
//    "020201" => "RekeningPembantuHutangSaham",//hutang ke pemegang saham
//    "020401" => "RekeningPembantuAntarcabang",//hutang ke pusat
//    "010201" => "RekeningPembantuCustomer",//piutang dagang
    "3010020" => "RekeningPembantuModal",//modal
    "1010010040" => "RekeningPembantuKas",//pettycash
//    "011002" => "RekeningPembantuAntarcabang",//piutang cabang
//    "0109" => "RekeningPembantuAntarcabang",//piutang biaya cabang
//    "020402" => "RekeningPembantuAntarcabang",//hutang biaya ke pusat
//    "1003" => "RekeningPembantuPendapatanLainLain",//pendapatan beban lain ke pusat
//    "1004" => "RekeningPembantuBebanLainLain",//beban lain ke pusat
//    "07" => "RekeningPembantuBiayaUsaha",// beban usaha ke pusat
//    "08" => "RekeningPembantuBiayaUmum",//beban umum ke pusat
//    "09" => "RekeningPembantuBiayaOperasional",// beban Operasiona ke pusat
    "7010" => "RekeningPembantuLRLainlain",
    //-----
    "1020010010" => "RekeningPembantuAktivaBerwujud",//kendaraan
    "1020020010" => "RekeningPembantuAktivaBerwujud",//Peralatan Kantor
    "1020050010" => "RekeningPembantuAktivaBerwujud",//Bangunan
    "1020060010" => "RekeningPembantuAktivaBerwujud",//Tanah
);
$config['accountKasInAllowed'] = array(
    "3010020",// => "RekeningPembantuModal",//modal
    "7010",// => "RekeningPembantuLRLainlain",//modal
);

$config['accountModalAllowed'] = array(
//    "3010020",// => "RekeningPembantuModal",//modal
    "1010010",// => "RekeningPembantuLRLainlain",//modal

    "1020010010",//kendaraan
    "1020020010",//Peralatan Kantor
    "1020050010",//Bangunan
    "1020060010",//Tanah
);

//--------------------------------------
$config['pemindahbukuan'] = array(
    // kas
    "1010010010" => array(
        "LockerValue" => array(
            "comName" => "LockerValueItem",
            "jenis_locker" => "kas",
        ),
    ),
    // persediaan produk
    "1010030030" => array(
        "LockerStock" => array(
            "comName" => "LockerStock",
            "jenis_locker" => "stock",
        ),
        "LockerStockMutasi" => array(
            "comName" => "LockerStockMutasi",
            "jenis_locker" => "stock",
        ),
        "FifoAverage" => array(
            "comName" => "FifoAverage",
            "jenis_locker" => "produk",
        ),
    ),
    // persediaan bahan baku
    "1010030010" => array(
        "LockerStock" => array(
            "comName" => "LockerStock",
            "jenis_locker" => "stock",
        ),
        "FifoAverage" => array(
            "comName" => "FifoAverage",
            "jenis_locker" => "supplies",
        ),
    ),
);

//--------------------------------------
$config['pemindahbukuanTransisi'] = array(
    // hutang dagang
    "2010010" => array(
        "LockerValue" => array(
            "comName" => "LockerValueItem",
            "jenis_locker" => "hutang dagang transisi",
        ),
    ),
    // piutang dagang
    "1010020" => array(
        "LockerValue" => array(
            "comName" => "LockerValueItem",
            "jenis_locker" => "piutang dagang transisi",
        ),
    ),
);
//--------------------------------------
$config['shortItemsFields'] = array(
    "nama" => array(
        "label" => "nama",
        "addKey" => "keterangan",
    ),
    "harga" => "nilai",
);

$config['accountMinusAllowedJenisTr'] = array(
    "rekening" => array(
        "1010060010",// piutang cabang
        "2040010",// hutang ke pusat
    ),
    "jenisTransaksi" => array(
        "1985",//return distribusi
        "585",//distribusi
        "749",//penerimaan piutang
        "4464",//penerimaan penjualan tunai
        "4467",//uang muka konsumen
    ),

);


$config['kodePajak'] = array(
    "01" => "Kode faktur pajak 010 adalah digunakan untuk Penyerahan Barang Kena Pajak ( BKP ) atau Jasa Kena Pajak (JKP) yang PPN-nya terutang dipungut oleh PKP penjual.",
    "02" => "Kode faktur pajak 020 adalah digunakan jika Penyerahan BKP atau JKP kepada pemungut PPN seperti bendahara pemerintah, BUMN, badan usaha tertentu, yang PPN-nya dipungut oleh pemungut PPN bendahara pemerintah.",
    "03" => "Kode faktur pajak 030 adalah digunakan untuk Penyerahan BKP/JKP kepada pemungut PPN lainnya selain bendahara pemerintah, dan PPN-nya dipungut oleh pemungut PPN lainnya selain bendahara pemerintah.",
    "04" => "Kode faktur pajak 040 adalah digunakan untuk Penyerahan BKP/JKP yang menggunakan DPP nilai lain yang PPNnya dipungut oleh PKP penjual yang melakukan penyerahan.",
    "05" => "Tidak digunakan.",
    "06" => "Kode faktur pajak 060 adalah digunakan untuk penyerahan lainnya dan PPN-nya dipungut oleh PKP penjual yang menyerahkan BKP/JKP, dan juga penyerahan BKP/JKP dilakukan kepada orang pribadi pemegang paspor luar negeri sesuai ketentuan dalam Pasal 16E UU PPN.",
    "07" => "Kode faktur pajak 070 adalah digunakan untuk Penyerahan BKP/JKP yang mendapat fasilitas PPN Tidak Dipungut atau Ditanggung Pemerintah (DTP).",
    "08" => "Kode faktur pajak 080 adalah digunakan untuk penyerahan BKP/JKP yang mendapat fasilitas bebas PPN.",
    "09" => "Kode faktur pajak 090 adalah digunakan untuk penyerahan aktiva Pasal 16D yang PPN-nya dipungut oleh PKP penjual yang menyerahkan BKP.",
);
?>