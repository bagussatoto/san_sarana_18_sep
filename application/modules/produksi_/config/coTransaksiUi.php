<?php
//region urusan tanggal-menanggal
// date_default_timezone_set('asia/jakarta');
// $date = new DateTime(date("Y-m-d")); // Y-m-d
// $date->add(new DateInterval('P30D'));
//$date->format('Y-m-d') . "\n";
//endregion


$config["coTransaksiUi"] = array(

    //  config assembling / produksi
    "776" => array(
        "icon" => "fa fa-cube",
        "label" => "product assembling",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "assembling request",
                "actionLabel" => "make assembling request",
                "source" => "",
                "target" => "776r",
                "userGroup" => "p_produksi",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "authorization (work in proccess)",
                "actionLabel" => "approve",
                "source" => "776r",
                "target" => "776a",
                "userGroup" => "p_produksi_spv",
                "stateLabel" => "approved",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
            ),
            3 => array(
                "label" => "process assembling",
                "actionLabel" => "process assembling",
                "source" => "776a",
                "target" => "776",
                "userGroup" => "p_produksi_spv",
                "stateLabel" => "complete",
                "stateColor" => "#009900",
                "stateCaption" => "complete by",
                "allowEdit" => true,
                "allowIncrement" => false,
            ),
        ),
        "template" => "template/transaksi_nopihak.html",
        "selectorModel" => "MdlProdukRakitan",
        "selectorSrcModel" => "MdlProdukRakitan",
        "selectedPrice" => array(
            "model" => "MdlHargaProduk",
            "label" => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(
            //            "enabled" => false,
            //            "mdlName" => "MdlLockerStock",
        ),
        "selectorFilters" => array(),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "nama",
            "kode",
            "satuan",
        ),

        "selectorProcessor" => "_processSelectProductAssembling/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlGudang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "gudang",
        "pihakFilters" => array(
            "cabang_id=cabang_id",
            "id<>gudang_id",
        ),
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "gudang_nama" => "warehouse",
            "next_pic" => "Next step otorisator",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang2_nama" => "recipient",
                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "gudang_nama" => "warehouse",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang2_nama" => "recipient",
                "nomer_top" => "request number",
                "nomer" => "approval number",
                "oleh_nama" => "person",
                "gudang_nama" => "warehouse",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            3 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang2_nama" => "recipient",
                "nomer_top" => "request number",
                "nomer_approve" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "approval number",
                ),
                "nomer" => "inv number",
                "oleh_nama" => "person",
                "gudang_nama" => "warehouse",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array("print_label" => "nomer"),
            2 => array("print_label" => "nomer"),
            3 => array("print_label" => "nomer"),
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "gudang_nama" => "warehouse",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "gudang_nama" => "warehouse",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "produk_kode" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "item name",
                "produk_kode" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                "nama" => "item name",
                "produk_kode" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartFields2" => array(
            1 => array(
                "nama" => "item source name",
                "stok" => "stock",
                "jml" => "qty",
                "sisa" => "sisa",
                "satuan" => "satuan",
                "nilai_supplies"=>"harga",
                "sub_nilai_supplies"=>"sub_nilai",
            ),
            2 => array(
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "satuan",
//                "nilai"=>"harga"
                "nilai_supplies"=>"harga",
                "sub_nilai_supplies"=>"sub_nilai",
            ),
            3 => array(
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "satuan",
                "nilai_supplies"=>"harga",
                "sub_nilai_supplies"=>"sub_nilai",
            ),
        ),
        "shoppingCartFields3" => array(
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
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "label" => "label",
            "satuan" => "satuan",

            "berat_gross" => "berat_gross",
            "lebar_gross" => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross" => "tinggi_gross",
            "volume_gross" => "volume_gross",
            "volume" => "volume",
            "berat" => "berat",
        ),
        "shoppingCartSumFields"=>array(
            1=>array(),
            2=>array(),
            3=>array(
                "harga_bom"=>"grand total",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
                "harga_bom" => "price",
                "sub_harga_bom" => "sub total",
            ),
            2 => array(
                //                "hpp" => "hpp",
                           "harga_bom" => "price",
                           "sub_harga_bom" => "sub total",

            ),
            3 => array(
                //                "hpp" => "hpp",
                           "harga_bom" => "price",
                           "sub_harga_bom" => "sub total",

            ),
        ),
        "shoppingCartNoteEnabled" => false,
        "shoppingCartEditableFields" => array(
            1 => array(
                "jml",
            ),
            2 => array(//                "jml",
            ),
            3 => array(
                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga_bom",
            2 => "jml*harga_bom",
            3 => "jml*harga_bom",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
            3 => true,
        ),
        "shoppingCartPairedItem" => array(
            "enabled" => false,
            "mdlName" => "MdlProduk",
            "srcKey" => "id",
            "srcLabel" => array("nama"),
            "mdlFilter" => array("id<>id"),
            "targetGateName" => "items2_sum",
        ),
        "componentsAss" => array(
            "model" => "MdlProdukKomposisi_and_cost",
            "modelSrc" => "MdlSupplies",
        ),

        "followupItemEditable" => "_followupLiveEdit/updateItemFieldProduksi/",
        "followupItemRemove" => "_followupLiveEdit/removeItemProduksi/",

        "pairMakers" => array(
            1 => array(
                "stokSupplies" => array(
                    "helperName" => "he_cek_stock_supplies_locker",
                    "functionName" => "cekStockSuppliesLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state" => ".active",
                    ),
                    "gate" => "items2_sum",
                ),
                "priceSupplies" => array(
                    "helperName" => "he_cek_price_supplies",
                    "functionName" => "cekPriceSupplies",
                    "params" => array(
                        "cabang_id" => ".-1",
                    ),
                    "gate" => "items2_sum",
                ),
            ),
        ),
        "pairInjectors" => array(
            1 => array(
                "stokSupplies" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "priceSupplies" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "harga_last",
                    ),
                ),
            ),
        ),
        "pairCostInjectors" => array(
            1 => array(
                "source" => "items2",
                "target" => "items",
                "jenis" => "biaya",
                "kolom" => array(
                    "costName" => "nama",
                    "costNilai" => "nilai",
                ),
            ),
            2 => array(
                "source" => "items2",
                "target" => "rsltItems2",
                "jenis" => "biaya",
                "kolom" => array(
                    "costName" => "nama",
                    "costNilai" => "nilai",
                ),
            ),
            3 => array(
                "source" => "items2",
                "target" => "rsltItems2",
                "jenis" => "biaya",
                "kolom" => array(
                    "costName" => "nama",
                    "costNilai" => "nilai",
                ),
            ),
        ),
        "pairRegistries" => array(
            "main",
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "776re",
                "label" => "EDIT assembling request",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "776rrj",
                "label" => "REJECT assembling request",
            ),
        ),
    ),
);