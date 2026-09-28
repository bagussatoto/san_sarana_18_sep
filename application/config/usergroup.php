<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 8/16/2018
 * Time: 9:04 PM
 */

$config['userJenis'] = array(//==untuk keperluan konversi yang lama ke multi
    "admin" => "admin",
    "diskon_oto" => "diskon_oto",
    "finance" => "finance",
    "kasir_in" => "kasir_in",
    "kasir_penj" => "kasir_penj",
    "manager" => "manager",
    "seller" => "seller",
    "spv_gudang" => "spv_gudang",
    "spv_pemb" => "spv_pemb",
    "spv_penj" => "spv_penj",
    "spv_prod" => "spv_prod",
    "superman" => "superman",
    "supplier" => "supplier",
);

$config['userGroup_dev'] = array(
    "root" => "root",
);

$config['userGroup'] = array(
    "c_owner" => "owner",
    "c_holding" => "holding",
    "c_special" => "special",//dimatiin dulu
    "c_finance" => "finance (center)",
    "c_finance_spv" => "finance spv (center)",
    "c_gudang" => "center warehousing",
    "c_gudang_spv" => "center warehousing spv",
    "c_purchasing" => "purchasing",
    "c_purchasing_adm" => "purchasing admin",
    "c_purchasing_spv" => "purchasing spv",

    "c_data" => "data manager",

//    "c_export" => "sales export",
//    "c_export_spv" => "export spv",
//    "c_seller" => "seller",
//    "c_seller_spv" => "spv seller",
//    "c_seller_entry" => "seller (data entry)",
//    "c_kasir" => "cashier",
//    "c_gudang"        => "warehousing",


//    "o_gudang_spv"    => "branch warehousing spv",
    "c_gudang_out" => "center warehousing (out)",
    "c_katalog" => "view katalog",

//    "superman" => "superman",
);
$config['userGroup_cabang'] = array(
    "o_seller" => "seller",
    "o_seller_spv" => "seller spv",
    "o_seller_entry" => "seller (data entry)",
    "o_kasir" => "branch cashier",
    "o_gudang" => "branch warehousing",
    "o_finance" => "finance (branch)",
    "o_finance_spv" => "finance spv(branch)",
    "o_gudang_spv" => "branch warehousing spv",
    "o_gudang_out" => "branch warehousing (stock confirm)",
    "o_export" => "international seller",
    "o_export_spv" => "international spv",
    "o_project" => "pelaksana project",
    "o_project_spv" => "spv project",
    "p_gudang" => "production warehousing",
    "p_gudang_spv" => "production warehousing spv",
    "p_produksi" => "production",
    "p_produksi_spv" => "production spv",
);

$config['userGroup_gudang'] = array(
    "w_gudang" => "warehousing",
    "w_gudang_spv" => "warehousing spv",
);

$config['userGroup_editElementAllowed'] = array(
    "c_purchasing_spv",
    "c_owner",
    "c_holding",
    "c_finance",
    "c_finance_spv",
    "o_seller_spv",
    "o_finance",
    "o_finance_spv",
    "o_export_spv",
    "o_gudang_spv",
    "w_gudang_spv",
);

$config['userGroup_root'] = array(
    "root" => "system administrator",
);

$config['groupLandingPages'] = array(
    "o_kasir" => "Transaksi/createForm/582",
    "o_seller" => "Transaksi/createForm/582",
    "o_seller_entry" => "Transaksi/createForm/581",
);

$config['validatedPages'] = array(
    "Transaksi/createForm",
    "Transaksi/selectPaymentExternSrc",
    "Ledger/viewBalances_l1",
    "Ledger/viewMoves_l1",
    "Ledger/viewMoves_l2",
);

$config['userGroup_jurnal'] = array(
    "c_finance",
    "c_holding",
    "c_owner",
    "o_finance",
    "o_seller_spv",
);

$config['userGroup_blacklist'] = array(
    "c_gudang",
    "c_gudang_spv",
    "o_gudang",
    "o_gudang_spv",
    "o_gudang_out",
//    "c_owner",
//    "o_finance",
//    "o_seller_spv",
);

$config['userPlace_allowed'] = array(
    "center",
    "branch",
);
$config['limitBrach'] = array(
    "MdlCabang" => array(
        "limit" => "8",
        "limitNotif" => "(Limit jumlah cabang sudah tercapai, silahkan Non aktifkan cabang yang sudah tidak digunakan/ hubungi developer jika ingin tambahan limit)"
    ),

);
