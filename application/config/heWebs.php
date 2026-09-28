<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 12/8/2018
 * Time: 4:12 PM
 */
$config['heWebs'] = array(
    "produkSpecFields" => array(
        "nama",
        "id",
        "kode",
        "label",
        "deskripsi",
        "keterangan",
        "nopart",
        "harga",
        "hpp",
        "diskon",
    ),
);

$config["shopingCartWebs"] = array(
    "mains" => array(
        "olehID" => array(
            "type" => "number",
            "label" => "oleh id",
        ),
        "olehName" => array(
            "type" => "text",
            "label" => "oleh",
        ),
        "pihakID" => array(
            "type" => "number",
            "label" => "pihak id",
        ),
        "pihakName" => array(
            "type" => "text",
            "label" => "pihak",
        ),
        "placeID" => array(
            "type" => "number",
            "label" => "cabang id",
        ),
        "placeName" => array(
            "type" => "text",
            "label" => "cabang",
        ),
        "cabangID" => array(
            "type" => "number",
            "label" => "cabang id",
        ),
        "cabangName" => array(
            "type" => "text",
            "label" => "cabang",
        ),
        "gudangID" => array(
            "type" => "number",
            "label" => "gudang id",
        ),
        "gudangName" => array(
            "type" => "text",
            "label" => "gudang",
        ),
    ),
    "items" => array(
        "id" => array(
            "type" => "number",
            "label" => "id",
        ),
        "nama" => array(
            "type" => "text",
            "label" => "produk",
        ),
        "label" => array(
            "type" => "text",
            "label" => "label",
        ),
        "kode" => array(
            "type" => "text",
            "label" => "kode",
        ),
        "satuan" => array(
            "type" => "text",
            "label" => "satuan",
        ),
        "jml" => array(
            "type" => "number",
            "label" => "qty",
        ),
        "harga" => array(
            "type" => "number",
            "label" => "harga",
        ),
        "disc" => array(
            "type" => "number",
            "label" => "discount",
        ),
    ),
);

$config["customers"] = array(
    "fields" => array(
        "nama" => array(
            "label" => "nama",
        ),
        "nama_login" => array(
            "label" => "nama",
            "value_alt" => "nama",
            "replaces" => array(" " => "_"),
        ),
        "email" => array(
            "label" => "nama",
        ),
        "tlp_1" => array(
            "label" => "hanphone",
            "value_alt" => "hanphone",
        ),
        "npwp" => array(
            "label" => "npwp",
        ),
        "no_ktp" => array(
            "label" => "NIK",
        ),
        "password" => array(
            "label" => "password",
            "encryp" => "md5",
        ),

    ),
    "registration" => array(
        "nama" => array(
            "label" => "name",
            "icon" => "fa-user",
            "conf" => array(
                "type" => "text",
                "placeholder" => "name",
                "class" => "form-control",
                "name" => "nama",
                "required" => "required",
                "tabindex" => "1",
            ),
        ),
        "email" => array(
            "label" => "email",
            "icon" => "fa-envelope",
            "conf" => array(
                "type" => "email",
                "placeholder" => "email address",
                "class" => "form-control",
                "name" => "email",
                "required" => "required",
                "tabindex" => "0",
            ),
        ),
        "tlp_1" => array(
            "label" => "phone",
            "icon" => "fa-phone",
            "conf" => array(
                "type" => "text",
                "placeholder" => "hanphone",
                "class" => "form-control",
                "name" => "tlp_1",
                "required" => "required",
                "tabindex" => "2",
            ),
        ),
        "password" => array(
            "label" => "password",
            "icon" => "fa-key",
            "conf" => array(
                "type" => "password",
                "placeholder" => "password",
                "class" => "form-control",
                "name" => "password",
                "required" => "required",
                "tabindex" => "4",
            ),
        ),
        "password_2" => array(
            "label" => "password",
            "icon" => "fa-key",
            "conf" => array(
                "type" => "password",
                "placeholder" => "retype password",
                "class" => "form-control",
                "name" => "password_2",
                "required" => "required",
                "tabindex" => "4",
            ),
        ),
    ),
    "logins" => array(
        "nama" => array(
            "label" => "nama",
            "value_alt" => "nama",
            // "replaces"  => array(" " => "_"),
        ),
        "password" => array(
            "label" => "password",
            "encryp" => "md5",
        ),
    ),
);

/* ===========================================================================================================
 * $config["maintenance"]
 * false : aplikasi berjalan normal
 * 1 ~   : masuk mode maintenance (merupakan key pada config maintenaceOptions)
 * ===========================================================================================================*/
//region maintenace
// $config["maintenance"] = true;
$config["maintenance"] = false;


//$config["maintenanceTransaksi"] = true;
$config["maintenanceTransaksi"] = false;


$config["maintenanceOptions"] = array(
    1 => array(
        "status" => "underMaintenance",
        "title" => "Under Maintenance",
        "mesage" => "Silahkan kembali beberapa saat lagi ... ",
        "reload" => "60", // Second
    ),
);
//endregion

// $config["bypassPassword"] = array(
//     "202.65.117.72" => "san.maya",
//     "192.168.1.44"  => "demo.maya",
// );

// $config["multySessionLogin"] = array(// "69" => "yanty jkt",
// );

$config['logins'] = array(
    "idleTime" => 120, // dalam menit //
    "defaultPassword" => "123456",
    "allowedMultySession" => array(
        // "175" => "san.ddputra.medan_2",
        // "140" => "san.ddputra.medan",
    ),
    "allowedPasswordBypass" => array(
        "202.65.117.72" => "network.maya",
//        "192.168.1.44"  => "demo.maya",
//         "192.168.5.1"  => "coba.maya",
//         "202.65.117.80"  => "coba.maya",
    ),
);

$config['katalog'] = array(
    "modal" => array(
        // yang tidak ada disini berarti langsung di viewernya
        "fields" => array(
            "panjang_gross" => array(
                "label" => "panjang",
            ),
            "lebar_gross" => array(
                "label" => "lebar",
            ),
            "tinggi_gross" => array(
                "label" => "tinggi",
            ),
            "volume_gross" => array(
                "label" => "capacity (M3)",
                "format" => "m3",
            ),
            "berat_gross" => array(
                "label" => "weight (KG)",
                "format" => "kg",
            ),
            "kode" => array(
                "label" => "code",
            ),
            "nama" => array(
                "label" => "produk name",
            ),
        ),

    ),

    "page" => array(
        // yang tidak ada disini berarti langsung di viewernya
        "fields" => array(
            "panjang_gross" => array(
                "label" => "panjang",
            ),
            "lebar_gross" => array(
                "label" => "lebar",
            ),
            "tinggi_gross" => array(
                "label" => "tinggi",
            ),
            "volume_gross" => array(
                "label" => "capacity (M3)",
                "format" => "m3",
            ),
            "berat_gross" => array(
                "label" => "weight (KG)",
                "format" => "kg",
            ),
            "kode" => array(
                "label" => "code",
            ),
            "nama" => array(
                "label" => "produk name",
            ),
        ),
    ),
);

/*
 * pengaturan deteksi mobile
 * logic dikerjakan oleh he_misc_helper
 * */
$config['mobile'] = array(
    /* ----------------------------------------------------------------------
     * kala id user masuk list ini maka tidaka pernah dapat fitur mobile
     * ----------------------------------------------------------------------*/
    "disallowedMobile" => array(
        // "69" => "yanty jkt",
        // "241" => "jkt1 jkt",
    ),
    /* ----------------------------------------------------------------------
     * default fitur mobil jalan atau tidak
     * true untuk yes
     * false untuk no
     * ----------------------------------------------------------------------*/
    "autoDetect" => false,
    /* ----------------------------------------------------------------------
     * kala id user masuk list ini maka akan dipaksa masuk mobil terus menerus
     * kecuali id masuk juga disallowed maka idi disini tidak berlaku
     * ----------------------------------------------------------------------*/
    "forcedMobile" => array(
        // "17" => "holding_",
        // "241" => "jkt1 jkt",
        // "69" => "yanty jkt",
    ),
);

$config['mongo'] = array(
    /*--true :: memanfaatkan data semi statis--*/
    /*--false :: data live--*/
    "connection" => true,
    // "connection" => false,
);