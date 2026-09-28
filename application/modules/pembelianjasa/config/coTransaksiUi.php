<?php
//region urusan tanggal-menanggal
// date_default_timezone_set('asia/jakarta');
// $date = new DateTime(date("Y-m-d")); // Y-m-d
// $date->add(new DateInterval('P30D'));
//$date->format('Y-m-d') . "\n";
//endregion

//tambahin filter "461ro untuk selectornota taxes 681
$config["coTransaksiUi"] = array(

//    "1763" => array(
//        "icon" => "fa fa-cart-arrow-down",
//        "label" => "# supplies request(dont check auto by system)",
//        "place" => "center",
//        "hideMenu" => true,
//        "steps" => array(
//            1 => array(
//                "label" => "new supplies pre purchase request",
//                "actionLabel" => "new supplies request",
//                "source" => "",
//                "target" => "1763r",
//                "userGroup" => "sys",
//                "stateLabel" => "pending approval",
//                "stateColor" => "#dd3300",
//                "stateCaption" => "prepared by",
//            ),
//            2 => array(
//                //                "label" => "supplies distribution",
//                "label" => "authorization",
//                "actionLabel" => "approve supplies pre purchase request",
//                "source" => "1763r",
//                "target" => "1763",
//                "userGroup" => "sys",
//                "stateLabel" => "approved",
//                "stateColor" => "#009900",
//                "stateCaption" => "approved by",
//                "allowEdit" => true,
//                "allowFollowup" => false,
//            ),
//        ),
//        "template" => "template/transaksi.html",
//        "selectorModel" => "MdlLockerStockSupplies",
//        "selectorSrcModel" => "MdlSupplies",
//        "selectedPrice" => array(
//            "model" => "MdlHargaSupplies",
//            "label" => array("jual"),
//            "key_label" => array(
//                "jual" => "harga",
//            ),
//            "mainSrc" => "hpp",
//        ),
//        "lockerCheck" => array(
//            "enabled" => true,
//            "mdlName" => "MdlLockerStockSupplies",
//        ),
//        "lockerCheckAppr" => array(
//            "enabled" => true,
//            "mdlName" => "MdlLockerStockSupplies",
//            "jenis" => "supplies",
//            "jenis_locker" => "stock",
//        ),
//        "qtips" => array(
//            "nama" => "Product",
//            "stockNeed" => "request",
//            "stock" => "avail",
//            "valid_qty" => "purch",
//        ),
//        "selectorFilters" => array(
//            "cabang_id=placeID",
//            "gudang_id=gudangID",
//            "jumlah>.0",
//            "state=.active",
//        ),
//        "selectorProcessor" => "_processSelectProduct/select",
//        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
//        "selectorLabel" => "item",
//        "selectorParamFields" => array(
//            "id" => "produk_id",
//            "nama" => "nama",
//            "satuan" => "satuan",
//            "jumlah" => "jumlah",
//            "produk_kode" => "kode",
//        ),
//        "selectorViewedFields" => array(
//            "keterangan",
//            "kode",
//            "satuan",
//            "jumlah",
//        ),
//        "swappedKeys" => array("pihakID", "pihakName"),
//        "editHandlerMethod" => "select",
//        "pihakModel" => "MdlCabang",
//        "pihakCaller" => "Selectors/_selectorPihak/selectPihak",
//        "pihakLabel" => "cabang",
//        "pihakFilters" => array(
//            "id<>cabang_id",
//        ),
//        "pihakProcessor" => "Selectors/_processPihak/select",
//        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
//            "dtime" => "date",
//            "cabang2_nama" => "recipient",
//            "nomer" => "receipt number",
//            "oleh_nama" => "person",
//        ),
//        "compactHistoryFields" => array(
//            "jenis_label" => "activity",
//            "dtime" => "date",
//            "cabang2_nama" => "recipient",
//            "nomer" => "receipt number",
//            "oleh_nama" => "person",
//        ),
//        "selectorFields" => array("id", "nama", "satuan"),
//        "pihakFields" => array("id", "nama"),
//        "shoppingCart" => array(
//            "initPrices" => "beli",
//        ),
//
//        "shoppingCartFields" => array(
//            1 => array(
//                "nama" => "product name",
//                "produk_kode" => "product code",
//                "stok" => "stock",
//                "jml" => "qty",
//                "satuan" => "uom",
//                //            "harga" => "harga",
//            ),
//            2 => array(
//                "nama" => "product name",
//                "produk_kode" => "product code",
//                "stok" => "stock",
//                "jml" => "qty",
//                "satuan" => "uom",
//                //            "harga" => "harga",
//            ),
//        ),
//        "shoppingCartFieldSrc" => array(
//            "nama" => "nama",
//            "produk_kode" => "kode",
//            "label" => "label",
//            "satuan" => "satuan",
//            "stok" => "stock",
//            //"berat"         => "berat",
//            //          "lebar"         => "lebar",
//            //        "panjang"       => "panjang",
//            //      "tinggi"        => "tinggi",
//            //    "volume"        => "volume",
//            "berat_gross" => "berat_gross",
//            "lebar_gross" => "lebar_gross",
//            "panjang_gross" => "panjang_gross",
//            "tinggi_gross" => "tinggi_gross",
//            "volume_gross" => "volume_gross",
//        ),
//        "shoppingCartNumFields" => array(
//            1 => array(
//                //                "hpp" => "hpp",
//                //            "harga" => "price",
//            ),
//            2 => array(
//                //                "hpp" => "hpp",
//                //            "harga" => "price",
//            ),
//        ),
//        "shoppingCartEditableFields" => array(
//            1 => array(
//                //            "harga",
//                //            "ppn",
//                "jml",
//            ),
//            2 => array(
//                //            "harga",
//                //            "ppn",
//                "jml",
//            ),
//        ),
//        "shoppingCartAmountValue" => array(
//            1 => "jml*hpp",
//            2 => "jml*hpp",
//        ),
//        "shoppingCartHideSubamount" => array(
//            1 => true,
//            2 => true,
//        ),
//
//        "pairChild" => array(
//            "461"
//        ),
//
//        "receiptElements" => array(
//            "gudang" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "target warehouse",
//                "mdlName" => "MdlGudangDefault",
//                "mdlFilter" => array("cabang_id=pihakID"),
//                "key" => "id",
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "name" => "",
//                ),
//                "editPoints" => array(1, 2, 3),
//            ),
//        ),
//
//        "resumeFieldNames" => array(
//            "selectFields" => "cabang2_nama",
//            "title" => "brach ",
//        ),
//        "pairMakers" => array(
//            2 => array(
//                "stokSupplies" => array(
//                    "helperName" => "he_cek_stock_supplies_locker",
//                    "functionName" => "cekStockSuppliesLocker",
//                    "params" => array(
//                        "cabang_id" => "placeID",
//                        "gudang_id" => "gudangID",
//                        "state" => ".active",
//                    ),
//                ),
//            ),
//        ),
//        "pairInjectors" => array(
//            2 => array(
//                "stokSupplies" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "stok",
//                    ),
//                ),
//            ),
//        ),
//        "settlementHistoryFields" => array(
//            "dtime" => "time",
//            "nomer" => "receipt number",
//            "cabang_nama" => "sender",
//            "cabang2_nama" => "recipient",
//            //            "suppliers_nama" =>"vendor",
//            "jenis_label" => "activity",
//            //            "transaksi_nilai" => "orig. value",
//            //            "add_disc"        => "discount",
//            //            "grand_total"     => "nett",
//        ),
//        "validationRules" => array(
//            "items" => array(
//                "target" => "stok",
//                "source" => "jml",
//            ),
//        ),
//        "glanceHistoryFields" => array(
//            "dtime" => "time",
//            "nomer" => "receipt",
//            "cabang2_nama" => "branch",
//            "oleh_nama" => "person",
//        ),
//        "allowedMainEdit" => array("1"),
//        "tabHistoryFields" => array(
//            "transaksi_id" => array(
//                "label" => "By Transaksi",
//                "allowFollowup" => false,
//            ),
//            "produk_id" => array(
//                "label" => "By Produk",
//                "allowFollowup" => true,
//            ),
//        ),
//        "tabFieldsItems" => array(
//
//            "transaksi_id" => array(
//                "select" => "tick",
//                "dtime" => "tanggal",
//                "nomer" => "PRE PO Number",
//                "nomer_top" => "Supplies Request No",
//                "arrProduk" => "Produk",
//                "cabang2_nama" => "Cabang",
//                "oleh_nama" => "PIC",
//                "action" => "Action",
//            ),
//            "produk_id" => array(
//                //                "select" => "All",
//                "dtime" => "tanggal",
//                "produk_nama" => "Produk Nama",
//                "nomer_top" => "Transaksi No",
//                "produk_ord_jml" => "PRE PO Jml",
//                //                "purchased" => "On Purchase",
//                //                "valid_qty" => "Outstanding",
//
//            ),
//        ),
//        "itemSwapper" => "_processSelectProduct/multiSelect",
//        // ======== =========
//        "xShipmentConfig" => array(
//            1 => array(
//                "enabled" => true,
//                "label" => "close/fullfillment auto pre purchase request",
//                "targetJenisMaster" => "11763",
//                "warning" => "You may cancel this transaction with the remaining items. continue cancel this transaction?",
//                "allowedGroups" => array(
//                    "c_holding",
//                    "c_gudang",
//                    "c_gudang_spv",
//                    "c_finance",
//                    "c_purchasing",
//                    "c_purchasing_adm",
//                    "c_purchasing_spv",
//                ),
//            ),
//        ),
//        "previewCtr" => "Create",
//    ),
//    "11763" => array(
//        "icon" => "fa fa-cart-arrow-down",
//        "label" => "# close/fullfillment supplies request(dont check auto by system)",
//        "place" => "center",
//        "hideMenu" => true,
//        "steps" => array(
//            1 => array(
//                "label" => "close/fullfillment supplies pre purchase request",
//                "actionLabel" => "close/fullfillment supplies request",
//                "source" => "",
//                "target" => "11763",
//                "userGroup" => "_c_gudang_spv",
//                "stateLabel" => "pending approval",
//                "stateColor" => "#dd3300",
//                "stateCaption" => "prepared by",
//                "isCancelPacking" => true,
//            ),
//            //            2 => array(
//            //                //                "label" => "supplies distribution",
//            //                "label" => "authorization",
//            //                "actionLabel" => "approve supplies pre purchase request",
//            //                "source" => "1763r",
//            //                "target" => "1763",
//            //                "userGroup" => "c_gudang_spv",
//            //                "stateLabel" => "approved",
//            //                "stateColor" => "#009900",
//            //                "stateCaption" => "approved by",
//            //                "allowEdit" => true,
//            //                "allowFollowup" => false,
//            //            ),
//        ),
//        "template" => "template/transaksi_fullfill.html",
//        "isDisableMakeTrans" => true,
//        "selectorModel" => "MdlLockerStockSupplies",
//        "selectorSrcModel" => "MdlSupplies",
//        "selectedPrice" => array(
//            "model" => "MdlHargaSupplies",
//            "label" => array("jual"),
//            "key_label" => array(
//                "jual" => "harga",
//            ),
//            "mainSrc" => "hpp",
//        ),
//        "lockerCheck" => array(
//            "enabled" => true,
//            "mdlName" => "MdlLockerStockSupplies",
//        ),
//        "lockerCheckAppr" => array(
//            "enabled" => true,
//            "mdlName" => "MdlLockerStockSupplies",
//            "jenis" => "supplies",
//            "jenis_locker" => "stock",
//        ),
//        "qtips" => array(
//            "nama" => "Product",
//            "stockNeed" => "request",
//            "stock" => "avail",
//            "valid_qty" => "purch",
//        ),
//        "selectorFilters" => array(
//            "cabang_id=placeID",
//            "gudang_id=gudangID",
//            "jumlah>.0",
//            "state=.active",
//        ),
//        "selectorProcessor" => "_processSelectProduct/select",
//        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
//        "selectorLabel" => "item",
//        "selectorParamFields" => array(
//            "id" => "produk_id",
//            "nama" => "nama",
//            "satuan" => "satuan",
//            "jumlah" => "jumlah",
//            "produk_kode" => "kode",
//        ),
//        "selectorViewedFields" => array(
//            "keterangan",
//            "kode",
//            "satuan",
//            "jumlah",
//        ),
//        "swappedKeys" => array("pihakID", "pihakName"),
//        "editHandlerMethod" => "select",
//        "pihakModel" => "MdlCabang",
//        "pihakCaller" => "Selectors/_selectorPihak/selectPihak",
//        "pihakLabel" => "cabang",
//        "pihakFilters" => array(
//            "id<>cabang_id",
//        ),
//        "pihakProcessor" => "Selectors/_processPihak/select",
//        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
//            "dtime" => "date",
//            "cabang2_nama" => "recipient",
//            "nomer" => "receipt number",
//            "oleh_nama" => "person",
//        ),
//        "compactHistoryFields" => array(
//            "jenis_label" => "activity",
//            "dtime" => "date",
//            "cabang2_nama" => "recipient",
//            "nomer" => "receipt number",
//            "oleh_nama" => "person",
//        ),
//        "selectorFields" => array("id", "nama", "satuan"),
//        "pihakFields" => array("id", "nama"),
//        "shoppingCart" => array(
//            "initPrices" => "beli",
//        ),
//
//        "shoppingCartFields" => array(
//            1 => array(
//                "nama" => "product name",
//                "produk_kode" => "product code",
//                //                "stok" => "stock",
//                "jml" => "qty",
//                "satuan" => "uom",
//                //            "harga" => "harga",
//            ),
//            2 => array(
//                "nama" => "product name",
//                "produk_kode" => "product code",
//                //                "stok" => "stock",
//                "jml" => "qty",
//                "satuan" => "uom",
//                //            "harga" => "harga",
//            ),
//        ),
//        "shoppingCartFieldSrc" => array(
//            "nama" => "nama",
//            "produk_kode" => "kode",
//            "label" => "label",
//            "satuan" => "satuan",
//            "stok" => "stock",
//            //"berat"         => "berat",
//            //          "lebar"         => "lebar",
//            //        "panjang"       => "panjang",
//            //      "tinggi"        => "tinggi",
//            //    "volume"        => "volume",
//            "berat_gross" => "berat_gross",
//            "lebar_gross" => "lebar_gross",
//            "panjang_gross" => "panjang_gross",
//            "tinggi_gross" => "tinggi_gross",
//            "volume_gross" => "volume_gross",
//        ),
//        "shoppingCartNumFields" => array(
//            1 => array(
//                //                "hpp" => "hpp",
//                //            "harga" => "price",
//            ),
//            2 => array(
//                //                "hpp" => "hpp",
//                //            "harga" => "price",
//            ),
//        ),
//        "shoppingCartEditableFields" => array(
//            1 => array(
//                //            "harga",
//                //            "ppn",
//                "jml",
//            ),
//            2 => array(
//                //            "harga",
//                //            "ppn",
//                "jml",
//            ),
//        ),
//        "shoppingCartAmountValue" => array(
//            1 => "jml*hpp",
//            2 => "jml*hpp",
//        ),
//        "shoppingCartHideSubamount" => array(
//            1 => true,
//            2 => true,
//        ),
//
//        //        "pairChild" => array(
//        //            "461"
//        //        ),
//
//        "receiptElements" => array(
//            //            "gudang" => array(
//            //                "elementType" => "dataModel",
//            //                "inputType" => "radio",
//            //                "label" => "target warehouse",
//            //                "mdlName" => "MdlGudangDefault",
//            //                "mdlFilter" => array("cabang_id=.-1"),
//            //                "key" => "id",
//            //                "labelSrc" => "name",
//            //                "usedFields" => array(
//            //                    "name" => "",
//            //                ),
//            //                "editPoints" => array(1, 2, 3),
//            //            ),
//        ),
//
//        "resumeFieldNames" => array(
//            "selectFields" => "cabang2_nama",
//            "title" => "brach ",
//        ),
//        "pairMakers" => array(
//            2 => array(
//                "stokSupplies" => array(
//                    "helperName" => "he_cek_stock_supplies_locker",
//                    "functionName" => "cekStockSuppliesLocker",
//                    "params" => array(
//                        "cabang_id" => "placeID",
//                        "gudang_id" => "gudangID",
//                        "state" => ".active",
//                    ),
//                ),
//            ),
//        ),
//        "pairInjectors" => array(
//            2 => array(
//                "stokSupplies" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "stok",
//                    ),
//                ),
//            ),
//        ),
//        "settlementHistoryFields" => array(
//            "dtime" => "time",
//            "nomer" => "receipt number",
//            "cabang_nama" => "sender",
//            "cabang2_nama" => "recipient",
//            //            "suppliers_nama" =>"vendor",
//            "jenis_label" => "activity",
//            //            "transaksi_nilai" => "orig. value",
//            //            "add_disc"        => "discount",
//            //            "grand_total"     => "nett",
//        ),
//        "validationRules" => array(
//            "items" => array(
//                "target" => "stok",
//                "source" => "jml",
//            ),
//        ),
//        "glanceHistoryFields" => array(
//            "dtime" => "time",
//            "nomer" => "receipt",
//            "cabang2_nama" => "branch",
//            "oleh_nama" => "person",
//        ),
//        "allowedMainEdit" => array("1"),
//        "tabHistoryFields" => array(
//            "produk_id" => array(
//                "label" => "By Produk",
//                "allowFollowup" => true,
//            ),
//            "transaksi_id" => array(
//                "label" => "By Transaksi",
//                "allowFollowup" => false,
//            ),
//        ),
//        "tabFieldsItems" => array(
//            "produk_id" => array(
//                "select" => "All",
//                "dtime" => "tanggal",
//                "produk_nama" => "Produk Nama",
//                "nomer_top" => "Transaksi No",
//                "produk_ord_jml" => "PRE PO Jml",
//                //                "purchased" => "On Purchase",
//                //                "valid_qty" => "Outstanding",
//
//            ),
//            "transaksi_id" => array(
//                //                "select" => "tic",
//                "dtime" => "tanggal",
//                "nomer" => "PRE PO Number",
//                "nomer_top" => "Supplies Request No",
//                "arrProduk" => "Produk",
//                "cabang2_nama" => "Cabang",
//                "oleh_nama" => "PIC",
//                "action" => "Action",
//            ),
//        ),
//        "itemSwapper" => "_processSelectProduct/multiSelect",
//        "previewCtr" => "Create",
//    ),

    // config po jasa
    "463" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "service purchasing",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "PURCHASE PRE ORDER",
                "actionLabel" => "make purchasing order",
                "source" => "",
                "target" => "463ro",
                "userGroup" => "c_purchasing",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
            ),
            2 => array(
                "label" => "PURCHASE ORDER",
                "actionLabel" => "approve purchasing and dpp ppn",
                "source" => "463ro",
                "target" => "463o",
                "userGroup" => "c_purchasing_adm",
                "stateLabel" => "purchased",
                "stateColor" => "#ff7700",
                "stateCaption" => "Approval by",
                //                "paymentSrc" => array(
                //                    "enabled" => true,
                //                    "filter" => array(
                //                        "label='incoming cash'",
                //                    ),
                //                    //                    "label" => "This order requires approval from the Finance Department. You don't need to follow up on this order.",
                //                    "label" => "Cash in Advance belum difollow up. Segera hubungi pihak Finance.",
                //                ),
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
            3 => array(
                "label" => "SERVICE RECEIVED NOTE",
                //                "actionLabel" => "make service receipt note",
                "actionLabel" => "undo/reject/GRN",
                "buttonLabel" => "make service receipt note",
                "source" => "463o",
                "target" => "463",
                "userGroup" => "c_holding",
                "stateLabel" => "service receipt note made",
                "stateColor" => "#009900",
                "stateCaption" => "Receipt by",
                //                "allowEdit" => true,
                //                "allowIncrement" => true,
            ),
//            4 => array(
//                "label" => "realisasi ppn masukan",
//                "actionLabel" => "approve ppn masukan",
//                "source" => "463",
//                "target" => "113",
//                "userGroup" => "c_finance",
//                "stateLabel" => "approved",
//                "stateColor" => "#009900",
//                "stateCaption" => "PT. Everest Electronic",
//            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlJasa",
        "selectorSrcModel" => "MdlJasa",
        "selectedPrice" => array(),
        "lockerCheck" => array(),
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
            "satuan",
        ),
        "selectorProcessor" => "_processSelectProductException/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakProcessor" => "_processPihak/select",
        "pihakMainValueSrc" => array(
            "npwp" => "npwp",
        ),

//        "pihakValidate" => array(
//            "wajib_pajak" => array(
//                "model" => "MdlWajibPajak",
//                "result" => array(
//                    1 => array(
//                        "kolom" => "npwp",
//                        "label" => "NPWP belum ditentukan. Silahkan perbaiki data Vendor.",
//                    ),
//                    2 => array(
//                        "kolom" => "no_ktp",
//                        "label" => "NIK belum ditentukan. Silahkan perbaiki data Vendor.",
//                    ),
//                    3 => array(
////                        "kolom" => "no_ktp",
//                        "label" => "(NPWP/NON NPWP) belum ditentukan. Silahkan perbaiki data Vendor.",
//                    ),
//                    "none" => array(
////                        "kolom" => "no_ktp",
//                        "label" => "(NPWP/NON NPWP) belum ditentukan. Silahkan perbaiki data Vendor.",
//                    ),
//                ),
//            ),
//        ),

        "shortHistoryFields" => array(
            //            "no" => "no",
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer_top" => "PRE PO number",
            // sumber dari kolom id_his
            "nomer_po" => array(
                "step" => 2,
                "key" => "nomer",
                "label" => "PO number",
            ),
            "nomer_grn" => array(
                "step" => 3,
                "key" => "nomer",
                "label" => "SRN number",
            ),
            "nomer_ppn" => array(
                "step" => 4,
                "key" => "nomer",
                "label" => "realisasi ppn number",
            ),
            //            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett" => "total amount",
            "pph23MethodPotongan__label" => "status pph 23",
            "next_pic" => "Next step otorisator",
            "keterangan" => "keterangan",
        ),
        "shortStatusFields" => array(
            "no" => "no",
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "suppliers_nama" => "vendor",
            //            "customers_nama" => "customer",
            "nomer_top" => "PO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett" => "total amount",
            "pph23MethodPotongan__label" => "status pph 23",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett" => "total amount",
                "pph23MethodPotongan__label" => "status pph 23",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
                "no" => "no",
                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PRE PO number",
                "nomer" => "PO number",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett" => "total amount",
                "pph23MethodPotongan__label" => "status pph 23",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            3 => array(
                "no" => "no",
                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PRE PO number",
                "ids_his" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "PO number",
                ),
                "nomer" => "receipt number",
                "description_main_followup" => "VENDOR'S INVOICE REFERRAL",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett" => "total amount",
                "pph23MethodPotongan__label" => "status pph 23",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            4 => array(
                "no" => "no",
                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PRE PO number",
                "ids_his" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "PO number",
                ),
                "nomer_srn" => array(
                    "step" => 3,
                    "key" => "nomer",
                    "label" => "SRN number",
                ),
                "description_main_followup" => "INV<br>from vendor",
                "nomer" => "realisasi ppn number",
                "oleh_nama" => "person",

                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett" => "total amount",
                "eFaktur" => "e-faktur",
                //                "ppn" => "ppn",
                "pph23MethodPotongan__label" => "status pph 23",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array(
                //                "review_details" =>"id",
                "print_label" => "nomer",
            ),
            2 => array(
                //                "review_details" =>"id",
                "print_label" => "nomer",
            ),
            3 => array(
                "print_label" => "nomer",
            ),
            4 => array(
                "print_label" => "nomer",
            ),
            5 => array(
                "print_label" => "nomer",
            ),
        ),
        "compactHistoryFields" => array(
            "no" => "no",
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "pph23MethodPotongan__label" => "status pph 23",
        ),

        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),

        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "code" => "kode",
            "label" => "label",
            "satuan" => "satuan",
            "dpp_persen" => "dpp_persen",
            "pph" => "pph",
        ),
        "shopingCartCompareFields" => array(
            1 => array(
                "main" => "pph",
                "slave" => "dpp_persen",
                //                "target" =>"valid_pph_key",
            ),

            2 => array(
                "main" => "pph",
                "slave" => "dpp_persen",
                //                "target" =>"valid_pph_key",
            ),

        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "Description",
                "jml" => "Qty",
                "satuan" => "Satuan",
            ),
            2 => array(
                "nama" => "Description",
                "jml" => "Qty",
                "satuan" => "Satuan",
            ),
            3 => array(
                "nama" => "Description",
                "jml" => "Qty",
                "satuan" => "Satuan",
            ),
            4 => array(
                "nama" => "Description",
                "jml" => "Qty",
                "satuan" => "Satuan",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
                "discPersen" => "DISC(%)",
                "disc" => "DISC(Rp)",
                "harga_disc" => "Netto",
                "dpp_persen" => "Prosentase DPP",
                "dppPPn" => "nilai dpp",
                "ppn" => "PPN 11%",
            ),
            2 => array(
                "harga" => "Unit Price",
                "discPersen" => "DISC(%)",
                "disc" => "DISC(Rp)",
                "harga_disc" => "Netto",
                "dpp_persen" => "Prosentase DPP",
                "dppPPn" => "nilai dpp",
                "ppn" => "PPN 11%",
            ),
            3 => array(
                "harga" => "Unit Price",
                "discPersen" => "DISC(%)",
                "disc" => "DISC(Rp)",
                "harga_disc" => "Netto",
                "dpp_persen" => "Prosentase DPP",
                "dppPPn" => "nilai dpp",
                "ppn" => "PPN 11%",
            ),
            4 => array(
                "harga" => "Unit Price",
                "discPersen" => "DISC(%)",
                "disc" => "DISC(Rp)",
                "harga_disc" => "Netto",
                "dpp_persen" => "Prosentase DPP",
                "dppPPn" => "nilai dpp",
                "ppn" => "PPN 11%",
            ),
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "Total Amount",
                "disc" => "DISC",
                //                "ppn" => "VAT",
                //                "nett" => "Total",
            ),
            2 => array(
                "harga" => "Total Amount",
                "disc" => "DISC",
                //                "ppn" => "VAT",
                //                "nett" => "Total",
            ),
            3 => array(
                "harga" => "Total Amount",
                "disc" => "DISC",
                //                "ppn" => "VAT",
                //                "nett" => "Total",
            ),
            4 => array(
                "harga" => "Total Amount",
                "disc" => "DISC",
                //                "ppn" => "VAT",
                //                "nett" => "Total",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartNoteEditabled" => array(
            2 => true,
            3 => true,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
                "dpp_persen",
                //                "ppn_persen",
                "discPersen",
            ),
            2 => array(
                "harga",
                //                "jml",
                "dpp_persen",
                //                "ppn_persen",
                "discPersen",
            ),
            3 => array(
                "harga",
                //                "jml",
                "dpp_persen",
                //                "ppn_persen",
                "discPersen",
            ),
            4 => array(
                "dpp_persen",
                //                                "jml",
                //                "harga",
            ),
        ),
        "shopingCartParamForceEditable" => array(
            //ini untuk force editable fields
            1 => array(
                "allow_params_edit" => "dpp_persen"
            ),
            2 => array(
                "allow_params_edit" => "dpp_persen"
            ),
            3 => array(
                "allow_params_edit" => "dpp_persen"
            ),
            4 => array(
                "allow_params_edit" => "dpp_persen"
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "vendor ID",
            "pihakName" => "vendor name",
            //            "nilai_dpp_ppn" =>"DPP PPN"
        ),
        "shoppingCartAmountValue" => array(
            //            1 => "jml*(harga_disc+ppn)",
            //            2 => "jml*(harga_disc+ppn)",
            //            3 => "jml*(harga_disc+ppn)",
            //            4 => "jml*(harga_disc+ppn)",
            1 => "jml*(harga)",
            2 => "jml*(harga)",
            3 => "jml*(harga)",
            4 => "jml*(harga)",
        ),
        "shoppingCartHideSubamount" => array(
            1 => false,
            2 => false,
            3 => false,
            4 => false,
        ),
        "shopingCartEditableCompare" => array(
            "dpp_persen" => array(
                "npwp_allowed" => array(
                    0 => false,
                    1 => true
                ),

            ),
        ),
        "shopingCartErrorEditable" => array(
            "npwp" => "vendor/supplier tidak memiliki npwp. prosentase dpp tidak dapat diubah. Silahkan lengkapi data npwp vendor untuk menggunakan dpp pengganti"
//            "npwp" => "vendor/supplier tidak memiliki npwp.",
        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "receiptElements" => array(
            "vendorDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "VENDOR",
                "mdlName" => "MdlSupplier",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "",
                    "alamat_1" => "",
                    "country" => "Country",
                    "tlp_1" => "Phone",
                    "tlp_2" => "Fax",
                    "npwp" => "NPWP",
                    //                    "alias" => "Attn",
                    "contact_person" => "Attn",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            "pph23MethodPotongan" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "method of pph 23",
                "mdlName" => "MdlPph23MethodPotongan",
                "key" => "id",
                //                "defaultValue" => "item",
                "disabled_select" => array(
                    "gate" => "valid_pph_key",
                    "value" => array(
                        "0" => "disabled",
                        "1" => "",
                    ),
                    "disabled_msg" => "tidak dapat dipilih karena jasa sudah mengandung ppn",
                ),


                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "method",
                    //                    "tarif" => "tarif (%)",
                ),
                "editPoints" => array(1, 2),
            ),
            "deliveryDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "DELIVERY DETAILS",
                "mdlName" => "MdlSupplierAddress",
                //                "mdlFilter"   => array("extern_id=pihakID"),
                "key" => "id",
                "labelSrc" => "alias",
                "usedFields" => array(
                    "extern_name" => "",
                    "alamat" => "",
                    "tlp" => "Phone",
                    "alias" => "Attn",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            "paymentMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Payment Method",
                "mdlName" => "MdlPaymentMethod1",
                //                "mdlName" => "MdlPaymentMethodCredit",
                //                "mdlFilter"   => array("extern_id=pihakID"),
                "key" => "id",
                "defaultValue" => "credit",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1,),
            ),

            "dummyElement" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "auto-validation",
                "mdlName" => "MdlDummyElement",
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",

                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),
        "relativeElements" => array(
            "paymentMethod" => array(
                //                "cash" => array(
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "cash account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
                //                "cia" => array(
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "cash account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
                "credit" => array(
                    "top" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "term of payment",
                        "mdlName" => "MdlTop",
                        "mdlFilter" => array(),
                        "key" => "kode",
                        "labelSrc" => "nama",
                        "description" => "",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1,),
                        "noValidate" => true,
                    ),
                ),
            ),
        ),
        "relativeOptions" => array(
            //            "paymentMethod" => array(
            //                "credit" => array(
            //                    "discount" => array(
            //                        "label" => "open discount",
            //                        "defaultValue" => ".0",
            //                        "maxValue" => "nett2*50/100",
            //                        "auth" => array(
            //                            //                            "groupID" => "c_holding",
            //                            "groupID" => "o_finance",
            //                        ),
            //                        "addPoints" => array(1, 2),
            //                    ),
            //                    "dp" => array(
            //                        "label" => "down payment",
            //                        "defaultValue" => ".0",
            //                        "maxValue" => "nett2*50/100",
            //                        "auth" => array(
            //                            //                            "groupID" => "c_finance",
            //                            "groupID" => "o_finance",
            //                        ),
            //                        "addPoints" => array(1,),
            //                    ),
            //                ),
            //                "cash" => array(
            //                    "discount" => array(
            //                        "label" => "open discount",
            //                        "defaultValue" => ".0",
            //                        "maxValue" => "nett2*50/100",
            //                        "auth" => array(
            //                            //                            "groupID" => "c_holding",
            //                            "groupID" => "o_finance",
            //                        ),
            //                        "addPoints" => array(1, 2),
            //                    ),
            //                    "dp" => array(
            //                        "label" => "down payment",
            //                        "defaultValue" => ".0",
            //                        "maxValue" => "nett2*50/100",
            //                        "auth" => array(
            //                            //                            "groupID" => "c_finance",
            //                            "groupID" => "o_finance",
            //                        ),
            //                        "addPoints" => array(1,),
            //                    ),
            //                ),
            //                "cia" => array(
            //                    "nilai_cia" => array(
            //                        "label" => "cash amount",
            ////                        "defaultValue" => "nett2",
            ////                        "minValue" => "nett2",
            ////                        "maxValue" => "nett2",
            ////                        "defaultValue" => "new_net3",
            //                        "defaultValue" => "nett",
            //                        "minValue" => "nett",
            //                        "maxValue" => "nett",
            //                        "auth" => array(
            //                            //                            "groupID" => "c_finance",
            //                            "groupID" => "c_finance",
            //                        ),
            //                        "addPoints" => array(1,),
            //                    ),
            ////                    "discount" => array(
            ////                        "label" => "open discount",
            ////                        "defaultValue" => ".0",
            ////                        "maxValue" => "nett2*50/100",
            ////                        "auth" => array(
            ////                            //                            "groupID" => "c_admin",
            ////                            "groupID" => "o_finance",
            ////                        ),
            ////                        "addPoints" => array(1, 2),
            ////                    ),
            //
            //                ),
            //
            //            ),
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "dppPPn" => array(
                        "label" => "Dpp",
                        "defaultValue" => "dppPPn",
                        "keyupAction" => "
    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harga').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harga').value;}
                            ",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "ppn" => array(
                        "label" => "Ppn 11%",
                        "defaultValue" => "ppn",
                        "maxValue" => "ppn_value",
                        "minValue" => "ppn_value",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),

                    "payment_out" => array(
                        "label" => "Grand total",
                        "defaultValue" => "payment_out",
                        "maxValue" => "payment_out",
                        "minValue" => "payment_out",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),

                ),
            ),
        ),
        "resumeFieldNames" => array(
            "selectFields" => "suppliers_nama",
            "title" => "vendor",
        ),
        "settlementHistoryFields" => array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "suppliers_nama" => "vendor",
            "jenis_label" => "activity",
            "transaksi_nilai" => "orig. value",
            "add_disc" => "discount",
            "grand_total" => "nett",
        ),
        "validatePaymentSource" => array(
            "3" => "MdlLockerValue",
        ),
        "allowedMainEdit" => array("1", "4"),
        "addMainSource" => array(
            4 => array(
                "fields" => array(
                    "nomer" => "INV",
                    "dppPPn" => "DPP",
                    "ppn" => "PPN (belum ada faktur)",
                    "ppn_realisasi" => "PPN Realisasi",
                    "dateFaktur" => "Tgl faktur ",
                    "eFaktur" => "e-faktur",
                ),
                "editableFields" => array(
                    "harga" => "number",
                    "ppn_realisasi" => "number",
                    "eFaktur" => "text",
                    "dateFaktur" => "date",
                ),
            ),
        ),
        "receiptEdit" => array(
            4 => true,
        ),
        // berada di midValidate() Transaksi
        "efakturValidator" => array(
            4 => array(
                "enabled" => true,
                "kolom" => array(
                    "dateFaktur" => "tanggal e-faktur belum diisikan.",
                    "eFaktur" => "nomer e-faktur belum diisikan.",
                ),
                "source" => array(
                    "ppn", // lebih dari 0
                    //                "ppnfactor",
                ),
            ),
        ),
        "detailForceMain" => array(
            2 => array(
                "source" => "pph",
                "target" => "valid_pph_key",
                "elemenReset" => "MdlPph23MethodPotongan",
                "current_element" => "pph23MethodPotongan",
            ),
        ),
        // ======== =========
        "followupMainNoteValidator" => array(
            3 => array(
                "enabled" => true,
                "kolom" => array(
                    "description_main_followup" => "nomer invoice dari vendor belum diisikan.",
                ),
                "source" => array(
                    "description_main_followup",
                ),
            ),
        ),
        "followupMainNote" => array(
            3 => array(
                "previews" => true,
                "enabled" => true,
                "editabled" => true,
                "label" => "INVOICE FROM VENDOR (*)",
            ),
            4 => array(
                "previews" => true,
                "enabled" => true,
                "editabled" => false,
                "label" => "INVOICE FROM VENDOR (*)",
            ),

        ),
        //        "followupMainEditable" => "_followupLiveEdit/updateMainFieldByStep/",
        "followupMainEditable" => "_followupLiveEdit/updateMainField/",
        // ======== =========
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di {cabang_nama}",
            2 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_actionLabel} ulang di {cabang_nama}",
            3 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di {cabang_nama}",
        ),
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "463roe",
                "label" => "EDIT PURCHASE PRE ORDER",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "463rorj",
                "label" => "REJECT PURCHASE PRE ORDER",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "463orj",
                "label" => "REJECT PURCHASE ORDER",
            ),
        ),
    ),
    "1463" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "service purchasing(pusat)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "PURCHASE PRE ORDER",
                "actionLabel" => "make purchasing order",
                "source" => "",
                "target" => "1463r",
                "userGroup" => "c_purchasing",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
            ),
            2 => array(
                "label" => "PURCHASE ORDER",
                "actionLabel" => "approve purchasing",
                "source" => "1463r",
                "target" => "1463o",
                "userGroup" => "c_purchasing_adm",
                "stateLabel" => "purchased",
                "stateColor" => "#ff7700",
                "stateCaption" => "Approval by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
            3 => array(
                "label" => "SERVICE RECEIVED NOTE",
                //                "actionLabel" => "make service receipt note",
                "actionLabel" => "undo/reject/GRN",
                "buttonLabel" => "make service receipt note",
                "source" => "1463o",
                "target" => "1463",
                "userGroup" => "c_holding",
                "stateLabel" => "service receipt note made",
                "stateColor" => "#009900",
                "stateCaption" => "Receipt by",
            ),
//            4 => array(
//                "label" => "realisasi ppn masukan",
//                "actionLabel" => "approve ppn masukan",
//                "source" => "1463",
//                "target" => "113",
//                "userGroup" => "c_finance",
//                "stateLabel" => "approved",
//                "stateColor" => "#009900",
//                "stateCaption" => "PT. Everest Electronic",
//            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlExpense",
        "selectorSrcModel" => "MdlExpense",
        "selectedPrice" => array(
            "model" => "MdlHargaSupplies",
            "label" => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "tipe=.import",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "nama",
            "satuan",
        ),
        "selectorProcessor" => "_processSelectProduct/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakProcessor" => "_processPihak/select",
//        "pihakValidate" => array(
//            "wajib_pajak" => array(
//                "model" => "MdlWajibPajak",
//                "result" => array(
//                    1 => array(
//                        "kolom" => "npwp",
//                        "label" => "NPWP belum ditentukan. Silahkan perbaiki data Vendor.",
//                    ),
//                    2 => array(
//                        "kolom" => "no_ktp",
//                        "label" => "NIK belum ditentukan. Silahkan perbaiki data Vendor.",
//                    ),
//                    3 => array(
////                        "kolom" => "no_ktp",
//                        "label" => "status NPWP/NON NPWP belum ditentukan. Silahkan perbaiki data Vendor.",
//                    ),
//                    "none" => array(
////                        "kolom" => "no_ktp",
//                        "label" => "status NPWP/NON NPWP belum ditentukan. Silahkan perbaiki data Vendor.",
//                    ),
//                ),
//            ),
//        ),
        "shortHistoryFields" => array(
            //            "no" => "no",
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer_top" => "PO number",
            // sumber dari kolom id_his
            "nomer_po" => array(
                "step" => 2,
                "key" => "nomer",
                "label" => "PO number",
            ),
            "nomer_grn" => array(
                "step" => 3,
                "key" => "nomer",
                "label" => "SRN number",
            ),
            "nomer_ppn" => array(
                "step" => 4,
                "key" => "nomer",
                "label" => "realisasi ppn number",
            ),
            //            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett" => "total amount",
            "pph23MethodPotongan__label" => "status pph 23",
            "keterangan" => "keterangan",
        ),
        "shortStatusFields" => array(
            "no" => "no",
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "suppliers_nama" => "vendor",
            //            "customers_nama" => "customer",
            "nomer_top" => "PO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett" => "total amount",
            "pph23MethodPotongan__label" => "status pph 23",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett" => "total amount",
                "pph23MethodPotongan__label" => "status pph 23",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
                "no" => "no",
                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PRE PO number",
                "nomer" => "PO number",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett" => "total amount",
                "pph23MethodPotongan__label" => "status pph 23",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            3 => array(
                "no" => "no",
                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PRE PO number",
                "nomer" => "receipt number",
                "description_main_followup" => "INV<br>from vendor",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett" => "total amount",
                "pph23MethodPotongan__label" => "status pph 23",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            4 => array(
                "no" => "no",
                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PRE PO number",
                "nomer" => "receipt number",
                "description_main_followup" => "INV<br>from vendor",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett" => "total amount",
                "eFaktur" => "e-faktur",
                //                "ppn" => "ppn",
                "pph23MethodPotongan__label" => "status pph 23",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array(
                //                "review_details" =>"id",
                "print_label" => "nomer",
            ),
            2 => array(
                //                "review_details" =>"id",
                "print_label" => "nomer",
            ),
            3 => array(
                "print_label" => "nomer",
            ),
            4 => array(
                "print_label" => "nomer",
            ),
            5 => array(
                "print_label" => "nomer",
            ),
        ),
        "compactHistoryFields" => array(
            "no" => "no",
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "pph23MethodPotongan__label" => "status pph 23",
        ),

        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),

        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "code" => "kode",
            "label" => "label",
            "satuan" => "satuan",
            "dpp_persen" => "dpp_persen",
            "pph" => "pph",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "Description",
                "jml" => "Qty",
                "satuan" => "Satuan",
            ),
            2 => array(
                "nama" => "Description",
                "jml" => "Qty",
                "satuan" => "Satuan",
            ),
            3 => array(
                "nama" => "Description",
                "jml" => "Qty",
                "satuan" => "Satuan",
            ),
            4 => array(
                "nama" => "Description",
                "jml" => "Qty",
                "satuan" => "Satuan",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
                //                "discPersen" => "DISC(%)",
                //                "disc" => "DISC(Rp)",
                "ppnPersen" => "VAT(%)",// ppnFactor
                "ppn" => "VAT(Rp)",
            ),
            2 => array(
                "harga" => "Unit Price",
                //                "discPersen" => "DISC(%)",
                //                "disc" => "DISC(Rp)",
                "ppnPersen" => "VAT(%)*",// ppnFactor
                "ppn" => "VAT(Rp)",
            ),
            3 => array(
                "harga" => "Unit Price",
                //                "discPersen" => "DISC(%)",
                //                "disc" => "DISC(Rp)",
                "ppnPersen" => "VAT(%)",// ppnFactor
                "ppn" => "VAT(Rp)",
            ),
            4 => array(
                "harga" => "Unit Price",
                //                "discPersen" => "DISC(%)",
                //                "disc" => "DISC(Rp)",
                "ppnPersen" => "VAT(%)",// ppnFactor
                "ppn" => "VAT(Rp)",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartNoteEditabled" => array(
            2 => true,
            3 => true,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
                //                "ppnFactor",
                "ppnPersen",
                "discPersen",
            ),
            2 => array(
                "harga",
                "jml",
                //                "ppnFactor",
                "ppnPersen",
                "discPersen",
            ),
            3 => array(
                "harga",
                "jml",
                //                "ppnFactor",
                "ppnPersen",
                "discPersen",
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*(harga-disc+ppn)",
            2 => "jml*(harga-disc+ppn)",
            3 => "jml*(harga-disc+ppn)",
            4 => "jml*(harga-disc+ppn)",
        ),

        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "receiptElements" => array(
            "vendorDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "VENDOR",
                "mdlName" => "MdlSupplier",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "",
                    "alamat_1" => "",
                    "country" => "Country",
                    "tlp_1" => "Phone",
                    "tlp_2" => "Fax",
                    //                    "npwp" => "NPWP",
                    //                    "alias" => "Attn",
                    "contact_person" => "Attn",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            "pph23MethodPotongan" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "method of pph 23",
                "mdlName" => "MdlPph23MethodPotongan",
                "key" => "id",
                //                "defaultValue" => "item",
                "disabled_select" => array(
                    "gate" => "valid_pph_key",
                    "value" => array(
                        "0" => "disabled",
                        "1" => "",
                    ),
                    "disabled_msg" => "tidak dapat dipilih karena jasa sudah mengandung ppn",
                ),


                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "method",
                    //                    "tarif" => "tarif (%)",
                ),
                "editPoints" => array(1, 2),
            ),
            "deliveryDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "DELIVERY DETAILS",
                "mdlName" => "MdlSupplierAddress",
                //                "mdlFilter"   => array("extern_id=pihakID"),
                "key" => "id",
                "labelSrc" => "alias",
                "usedFields" => array(
                    "extern_name" => "",
                    "alamat" => "",
                    "tlp" => "Phone",
                    "alias" => "Attn",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            //            "paymentMethod" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "payment method",
            //                "mdlName" => "MdlPaymentMethodCredit",
            //                "key" => "id",
            //                "labelSrc" => "name",
            //                "usedFields" => array(
            //                    "name" => "",
            //                ),
            //                "editPoints" => array(1,),
            //            ),

            "paymentMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Payment Method",
                "mdlName" => "MdlPaymentMethod1",
                //                "mdlName" => "MdlPaymentMethodCredit",
                //                "mdlFilter"   => array("extern_id=pihakID"),
                "key" => "id",
                "defaultValue" => "credit",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1,),
            ),
        ),
        "relativeElements" => array(
            "paymentMethod" => array(
                //                "cash" => array(
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "cash account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
                //                "cia" => array(
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "cash account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
                "credit" => array(
                    "top" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "term of payment",
                        "mdlName" => "MdlTop",
                        "mdlFilter" => array(),
                        "key" => "kode",
                        "labelSrc" => "nama",
                        "description" => "",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1,),
                        "noValidate" => true,
                    ),
                ),
            ),
        ),
        "relativeOptions" => array(
            //            "paymentMethod" => array(
            //                "cia" => array(
            //                    "nilai_cia" => array(
            //                        "label" => "cash amount",
            //                        "defaultValue" => "nett",
            //                        "minValue" => "nett",
            //                        "maxValue" => "nett",
            //                    ),
            //                ),
            //            ),
        ),
        "resumeFieldNames" => array(
            "selectFields" => "suppliers_nama",
            "title" => "vendor",
        ),
        "settlementHistoryFields" => array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "suppliers_nama" => "vendor",
            "jenis_label" => "activity",
            "transaksi_nilai" => "orig. value",
            "add_disc" => "discount",
            "grand_total" => "nett",
        ),
        "validatePaymentSource" => array(
            "3" => "MdlLockerValue",
        ),
        "allowedMainEdit" => array("1"),
        "addMainSource" => array(
            4 => array(
                "fields" => array(
                    "nomer" => "INV",
                    "harga" => "DPP",
                    "ppn" => "PPN (belum ada faktur)",
                    "ppn_realisasi" => "PPN Realisasi",
                    "dateFaktur" => "Tgl faktur ",
                    "eFaktur" => "e-faktur",
                ),
                "editableFields" => array(
                    "harga" => "number",
                    "ppn_realisasi" => "number",
                    "eFaktur" => "text",
                    "dateFaktur" => "date",
                ),
            ),
        ),
        "receiptEdit" => array(
            4 => true,
        ),
        // berada di midValidate() Transaksi
        "efakturValidator" => array(
            4 => array(
                "enabled" => true,
                "kolom" => array(
                    "dateFaktur" => "tanggal e-faktur belum diisikan.",
                    "eFaktur" => "nomer e-faktur belum diisikan.",
                ),
                "source" => array(
                    "ppn", // lebih dari 0
                    //                "ppnfactor",
                ),
            ),
        ),
        // ======== =========
        "followupMainNoteValidator" => array(
            3 => array(
                "enabled" => true,
                "kolom" => array(
                    "description_main_followup" => "nomer invoice dari vendor belum diisikan.",
                ),
                "source" => array(
                    "description_main_followup",
                ),
            ),
        ),
        "followupMainNote" => array(
            3 => array(
                "previews" => true,
                "enabled" => true,
                "editabled" => true,
                "label" => "INVOICE FROM VENDOR (*)",
            ),
            4 => array(
                "previews" => true,
                "enabled" => true,
                "editabled" => false,
                "label" => "INVOICE FROM VENDOR (*)",
            ),

        ),
        //        "followupMainEditable" => "_followupLiveEdit/updateMainFieldByStep/",
        "followupMainEditable" => "_followupLiveEdit/updateMainField/",
        // ======== =========
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di {cabang_nama}",
            2 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_actionLabel} ulang di {cabang_nama}",
            3 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di {cabang_nama}",
        ),
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1463re",
                "label" => "EDIT PURCHASE PRE ORDER",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1463rrj",
                "label" => "REJECT PURCHASE PRE ORDER",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "1463orj",
                "label" => "REJECT PURCHASE ORDER",
            ),
        ),
    ),


    // purchasing project cost

//    "3463" => array(
//        "icon" => "fa fa-cart-arrow-down",
//        "label" => "service project purchasing",
//        "place" => "center",
//        "steps" => array(
//            1 => array(
//                "label" => "SERVICE PROJECT PURCHASE PRE ORDER",
//                "actionLabel" => "make purchasing order",
//                "source" => "",
//                "target" => "3463ro",
//                "userGroup" => "c_purchasing",
//                "stateLabel" => "pending approval",
//                "stateColor" => "#dd3300",
//                "stateCaption" => "Prepare by",
//            ),
//            2 => array(
//                "label" => "SERVICE PROJECT PURCHASE ORDER",
//                "actionLabel" => "approve purchasing and dpp ppn",
//                "source" => "3463ro",
//                "target" => "3463o",
//                "userGroup" => "c_purchasing_adm",
//                "stateLabel" => "purchased",
//                "stateColor" => "#ff7700",
//                "stateCaption" => "Approval by",
//                "allowEdit" => true,
//                "allowIncrement" => true,
//            ),
//            3 => array(
//                "label" => "SERVICE PROJECT RECEIVED NOTE",
//                "actionLabel" => "undo/reject/GRN",
//                "buttonLabel" => "make service receipt note",
//                "source" => "3463o",
//                "target" => "3463",
//                "userGroup" => "c_holding",
//                "stateLabel" => "service receipt note made",
//                "stateColor" => "#009900",
//                "stateCaption" => "Receipt by",
//            ),
//            4 => array(
//                "label" => "realisasi ppn masukan",
//                "actionLabel" => "approve ppn masukan",
//                "source" => "3463",
//                "target" => "3113",
//                "userGroup" => "c_finance",
//                "stateLabel" => "approved",
//                "stateColor" => "#009900",
//                "stateCaption" => "PT. Everest Electronic",
//            ),
//        ),
//        "template" => "template/transaksi_4.html",
//        "selectedPrice" => array(),
//        "lockerCheck" => array(),
//
//        "selectorModel" => "MdlJasa",
//        "selectorSrcModel" => "MdlJasa",
//        "selectorFilters" => array(),
//        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
//        "selectorLabel" => "item",
//        "selectorParamFields" => array(
//            "id" => "id",
//            "nama" => "nama",
//            "satuan" => "satuan",
//        ),
//        "selectorViewedFields" => array(
//            "nama",
//            "satuan",
//        ),
//        "selectorProcessor" => "_processSelectProductException/select",
//        "editHandlerMethod" => "select",
//        // PIHAK I
//        "pihakModel" => "MdlSupplier",
//        "pihakCaller" => "_selectorPihak/selectPihak",
//        "pihakLabel" => "vendor",
//        "pihakProcessor" => "_processPihak/select",
//        // PIHAK II
//        "pihakModelMain" => "MdlCabang",
//        "pihakMainCaller" => "_selectorPihakMain/selectPihak",
//        "pihakMainLabel" => "outlet / cabang",
//        "pihakMainFilters" => array(
//            "id<>.-1",
//
//        ),
//        "pihakMainProcessor" => "_processPihakMain/select",
//        // PIHAK III
//        "pihakModelExtern" => "MdlCustomerProjek",
//        "pihakExternCaller" => "_selectorPihak/selectPihakExtern",
//        "pihakExternLabel" => "customer project",
//        "pihakExternViewedFields" => array(
//            "nama",
//        ),
//        "pihakExternFilters" => array(
//            "status=.1",
//            "trash=.0",
//            "kategori_nama=.projek",
//        ),
//        "pihakExternProcessor" => "_processPihak/selectExtern",
////        "pihakExternNota" => true,
//
//        //        "pihakMainValueSrc" => array(
//        //            "npwp" => "npwp",
//        //        ),
////        "pihakValidate" => array(
////            "wajib_pajak" => array(
////                "model" => "MdlWajibPajak",
////                "result" => array(
////                    1 => array(
////                        "kolom" => "npwp",
////                        "label" => "NPWP belum ditentukan. Silahkan perbaiki data Vendor.",
////                    ),
////                    2 => array(
////                        "kolom" => "no_ktp",
////                        "label" => "NIK belum ditentukan. Silahkan perbaiki data Vendor.",
////                    ),
////                    3 => array(
//////                        "kolom" => "no_ktp",
////                        "label" => "(NPWP/NON NPWP) belum ditentukan. Silahkan perbaiki data Vendor.",
////                    ),
////                    "none" => array(
//////                        "kolom" => "no_ktp",
////                        "label" => "(NPWP/NON NPWP) belum ditentukan. Silahkan perbaiki data Vendor.",
////                    ),
////                ),
////            ),
////        ),
//
//        "shortHistoryFields" => array(
//            //            "no" => "no",
//            "jenis_label" => "activity",
//            "dtime" => "date",
//            "suppliers_nama" => "vendor",
//            "customerName" => "customer",
//            "nomer_top" => "PRE PO number",
//            // sumber dari kolom id_his
//            "nomer_po" => array(
//                "step" => 2,
//                "key" => "nomer",
//                "label" => "PO number",
//            ),
//            "nomer_grn" => array(
//                "step" => 3,
//                "key" => "nomer",
//                "label" => "SRN number",
//            ),
//            "nomer_ppn" => array(
//                "step" => 4,
//                "key" => "nomer",
//                "label" => "realisasi ppn number",
//            ),
//            //            "nomer" => "receipt number",
//            "oleh_nama" => "person",
//            "harga" => "amount",
//            "disc" => "discount",
//            "ppn" => "ppn",
//            "nett" => "total amount",
//            "pph23MethodPotongan__label" => "status pph 23",
//            "next_pic" => "Next step otorisator",
//            "keterangan" => "keterangan",
//        ),
//        "shortStatusFields" => array(
//            "no" => "no",
//            "jenis_label" => "activity",
//            "dtime" => "date",
//            "status_next" => "status",
//            "suppliers_nama" => "vendor",
//            "customerName" => "customer",
//            "nomer_top" => "PO number",
//            "nomer" => "receipt number",
//            "oleh_nama" => "person",
//            "harga" => "amount",
//            "disc" => "discount",
//            "ppn" => "ppn",
//            "nett" => "total amount",
//            "pph23MethodPotongan__label" => "status pph 23",
//            //            "trash_4" => "trash 4",
//            //            "id" => "ID",
//        ),
//        "historyFields" => array(
//            1 => array(
//                "no" => "no",
//                "jenis_label" => "activity",
//                "dtime" => "date",
//                "suppliers_nama" => "vendor",
//                "customerName" => "customer",
//                "nomer_top" => "PO number",
//                //                "nomer" => "receipt number",
//                "oleh_nama" => "person",
//                //                "transaksi_nilai" => "amount",
//                "harga" => "amount",
//                "disc" => "discount",
//                "ppn" => "ppn",
//                "nett" => "total amount",
//                "pph23MethodPotongan__label" => "status pph 23",
//                "keterangan" => "keterangan",
//                "print_label" => "tool",
//            ),
//            2 => array(
//                "no" => "no",
//                "jenis_label" => "activity",
//                "dtime" => "date",
//                "suppliers_nama" => "vendor",
//                "customerName" => "customer",
//                "nomer_top" => "PRE PO number",
//                "nomer" => "PO number",
//                "oleh_nama" => "person",
//                //                "transaksi_nilai" => "amount",
//                "harga" => "amount",
//                "disc" => "discount",
//                "ppn" => "ppn",
//                "nett" => "total amount",
//                "pph23MethodPotongan__label" => "status pph 23",
//                "keterangan" => "keterangan",
//                "print_label" => "tool",
//            ),
//            3 => array(
//                "no" => "no",
//                "jenis_label" => "activity",
//                "dtime" => "date",
//                "suppliers_nama" => "vendor",
//                "customerName" => "customer",
//                "nomer_top" => "PRE PO number",
//                "ids_his" => array(
//                    "step" => 2,
//                    "key" => "nomer",
//                    "label" => "PO number",
//                ),
//                "nomer" => "receipt number",
//                "description_main_followup" => "VENDOR'S INVOICE REFERRAL",
//                "oleh_nama" => "person",
//                //                "transaksi_nilai" => "amount",
//                "harga" => "amount",
//                "disc" => "discount",
//                "ppn" => "ppn",
//                "nett" => "total amount",
//                "pph23MethodPotongan__label" => "status pph 23",
//                "keterangan" => "keterangan",
//                "print_label" => "tool",
//            ),
//            4 => array(
//                "no" => "no",
//                "jenis_label" => "activity",
//                "dtime" => "date",
//                "suppliers_nama" => "vendor",
//                "customerName" => "customer",
//                "nomer_top" => "PRE PO number",
//                "ids_his" => array(
//                    "step" => 2,
//                    "key" => "nomer",
//                    "label" => "PO number",
//                ),
//                "nomer_srn" => array(
//                    "step" => 3,
//                    "key" => "nomer",
//                    "label" => "SRN number",
//                ),
//                "description_main_followup" => "INV<br>from vendor",
//                "nomer" => "realisasi ppn number",
//                "oleh_nama" => "person",
//
//                "harga" => "amount",
//                "disc" => "discount",
//                "ppn" => "ppn",
//                "nett" => "total amount",
//                "eFaktur" => "e-faktur",
//                //                "ppn" => "ppn",
//                "pph23MethodPotongan__label" => "status pph 23",
//                "keterangan" => "keterangan",
//                "print_label" => "tool",
//            ),
//        ),
//        "extHistoryFields" => array(
//            1 => array(
//                //                "review_details" =>"id",
//                "print_label" => "nomer",
//            ),
//            2 => array(
//                //                "review_details" =>"id",
//                "print_label" => "nomer",
//            ),
//            3 => array(
//                "print_label" => "nomer",
//            ),
//            4 => array(
//                "print_label" => "nomer",
//            ),
//            5 => array(
//                "print_label" => "nomer",
//            ),
//        ),
//        "compactHistoryFields" => array(
//            "no" => "no",
//            "jenis_label" => "activity",
//            "dtime" => "date",
//            "suppliers_nama" => "vendor",
//            "nomer" => "receipt number",
//            "oleh_nama" => "person",
//            "pph23MethodPotongan__label" => "status pph 23",
//        ),
//
//        "selectorFields" => array("id", "nama", "satuan"),
//        "pihakFields" => array("id", "nama"),
//
//        "shoppingCart" => array(
//            "initPrices" => "beli",
//        ),
//        "shoppingCartFieldSrc" => array(
//            "nama" => "nama",
//            "code" => "kode",
//            "label" => "label",
//            "satuan" => "satuan",
//            "dpp_persen" => "dpp_persen",
//            "pph" => "pph",
//        ),
//        "shopingCartCompareFields" => array(
//            1 => array(
//                "main" => "pph",
//                "slave" => "dpp_persen",
//                //                "target" =>"valid_pph_key",
//            ),
//
//            2 => array(
//                "main" => "pph",
//                "slave" => "dpp_persen",
//                //                "target" =>"valid_pph_key",
//            ),
//
//        ),
//        "shoppingCartFields" => array(
//            1 => array(
//                "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
//            ),
//            2 => array(
//                "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
//            ),
//            3 => array(
//                "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
//            ),
//            4 => array(
//                "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
//            ),
//        ),
//        "shoppingCartNumFields" => array(
//            1 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
//                "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
//                "dppPPn" => "dpp ppn",
//                "ppn" => "PPN(Rp)",
//            ),
//            2 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
//                "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
//                "dppPPn" => "dpp ppn",
//                "ppn" => "PPN(Rp)",
//            ),
//            3 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
//                "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
//                "dppPPn" => "dpp ppn",
//                "ppn" => "PPN(Rp)",
//            ),
//            4 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
//                "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
//                "dppPPn" => "dpp ppn",
//                "ppn" => "PPN(Rp)",
//            ),
//        ),
//        "shoppingCartSumFields" => array(
//            1 => array(
//                "harga" => "Total Amount",
//                "disc" => "DISC",
//                //                "ppn" => "VAT",
//                //                "nett" => "Total",
//            ),
//            2 => array(
//                "harga" => "Total Amount",
//                "disc" => "DISC",
//                //                "ppn" => "VAT",
//                //                "nett" => "Total",
//            ),
//            3 => array(
//                "harga" => "Total Amount",
//                "disc" => "DISC",
//                //                "ppn" => "VAT",
//                //                "nett" => "Total",
//            ),
//            4 => array(
//                "harga" => "Total Amount",
//                "disc" => "DISC",
//                //                "ppn" => "VAT",
//                //                "nett" => "Total",
//            ),
//        ),
//        "shoppingCartNoteEnabled" => true,
//        "shoppingCartNoteType" => "textarea",
//        "shoppingCartNoteEditabled" => array(
//            2 => true,
//            3 => true,
//        ),
//        "shoppingCartEditableFields" => array(
//            1 => array(
//                "harga",
//                "jml",
//                "dpp_persen",
//                //                "ppn_persen",
//                "discPersen",
//            ),
//            2 => array(
//                "harga",
//                //                "jml",
//                "dpp_persen",
//                //                "ppn_persen",
//                "discPersen",
//            ),
//            3 => array(
//                "harga",
//                //                "jml",
//                "dpp_persen",
//                //                "ppn_persen",
//                "discPersen",
//            ),
//            4 => array(
//                "dpp_persen",
//                //                                "jml",
//                //                "harga",
//            ),
//        ),
//        "shopingCartParamForceEditable" => array(
//            //ini untuk force editable fields
//            1 => array(
//                "allow_params_edit" => "dpp_persen"
//            ),
//            2 => array(
//                "allow_params_edit" => "dpp_persen"
//            ),
//            3 => array(
//                "allow_params_edit" => "dpp_persen"
//            ),
//            4 => array(
//                "allow_params_edit" => "dpp_persen"
//            ),
//        ),
//        "shoppingCartFieldValidators" => array(
//            "jml" => "quantity",
//            "harga" => "price",
//        ),
//        "shoppingCartRowValidators" => array(
//            "pihakID" => "vendor ID",
//            "pihakName" => "vendor name",
//            //            "nilai_dpp_ppn" =>"DPP PPN"
//        ),
//        "shoppingCartAmountValue" => array(
//            //            1 => "jml*(harga_disc+ppn)",
//            //            2 => "jml*(harga_disc+ppn)",
//            //            3 => "jml*(harga_disc+ppn)",
//            //            4 => "jml*(harga_disc+ppn)",
//            1 => "jml*(harga)",
//            2 => "jml*(harga)",
//            3 => "jml*(harga)",
//            4 => "jml*(harga)",
//        ),
//        "shoppingCartHideSubamount" => array(
//            1 => false,
//            2 => false,
//            3 => false,
//            4 => false,
//        ),
//        "shopingCartEditableCompare" => array(
//            "dpp_persen" => array(
//                "npwp_allowed" => array(
//                    0 => false,
//                    1 => true
//                ),
//
//            ),
//        ),
//        "pairRegistries" => array(
//            "tableIn_master_values", "main", "items"
//        ),
//        "receiptElements" => array(
//            "vendorDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "VENDOR",
//                "mdlName" => "MdlSupplier",
//                "mdlFilter" => array("id=pihakID"),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "nama" => "",
//                    "alamat_1" => "",
//                    "country" => "Country",
//                    "tlp_1" => "Phone",
//                    "tlp_2" => "Fax",
//                    //                    "npwp" => "NPWP",
//                    //                    "alias" => "Attn",
//                    "contact_person" => "Attn",
//                ),
//                "editPoints" => array(1, 2, 3),
//            ),
//            "customerProjek" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "Customer Project",
//                "mdlName" => "MdlCustomerProjek",
//                "mdlFilter" => array("id=pihakExternID"),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "nama" => "Nama",
//                    "alamat_1" => "Alamat",
//                    "country" => "Country",
//                    "tlp_1" => "Phone",
//                    "tlp_2" => "Fax",
//                    "npwp" => "npwp",
//                ),
//                "editPoints" => array(),
//            ),
//            "branch" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "Branch",
//                "mdlName" => "MdlCabang",
//                "mdlFilter" => array("id=pihakMainID"),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "nama" => "nama",
//                ),
//                "noValidate" => false,
//                "editPoints" => array(),
//            ),
//            "pph23MethodPotongan" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "method of pph 23",
//                "mdlName" => "MdlPph23MethodPotongan",
//                "key" => "id",
//                //                "defaultValue" => "item",
//                "disabled_select" => array(
//                    "gate" => "valid_pph_key",
//                    "value" => array(
//                        "0" => "disabled",
//                        "1" => "",
//                    ),
//                    "disabled_msg" => "tidak dapat dipilih karena jasa sudah mengandung ppn",
//                ),
//
//
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "name" => "method",
//                    //                    "tarif" => "tarif (%)",
//                ),
//                "editPoints" => array(1, 2),
//            ),
//            "deliveryDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "DELIVERY DETAILS",
//                "mdlName" => "MdlSupplierAddress",
//                //                "mdlFilter"   => array("extern_id=pihakID"),
//                "key" => "id",
//                "labelSrc" => "alias",
//                "usedFields" => array(
//                    "extern_name" => "",
//                    "alamat" => "",
//                    "tlp" => "Phone",
//                    "alias" => "Attn",
//                ),
//                "editPoints" => array(1, 2, 3),
//            ),
//            "paymentMethod" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "Payment Method",
//                "mdlName" => "MdlPaymentMethod1",
//                //                "mdlName" => "MdlPaymentMethodCredit",
//                //                "mdlFilter"   => array("extern_id=pihakID"),
//                "key" => "id",
//                "defaultValue" => "credit",
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "name" => "",
//                ),
//                "editPoints" => array(1,),
//            ),
//            "dummyElement" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "auto-validation",
//                "mdlName" => "MdlDummyElement",
//                "key" => "id",
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "name" => "name",
//
//                ),
//                "editPoints" => array(1, 2, 3),
//            ),
//        ),
//        "relativeElements" => array(
//            "paymentMethod" => array(
//                //                "cash" => array(
//                //                    "cash_account" => array(
//                //                        "elementType" => "dataModel",
//                //                        "inputType" => "radio",
//                //                        "label" => "cash account",
//                //                        "mdlName" => "MdlBankAccount",
//                //                        "key" => "id",
//                //                        "labelSrc" => "nama",
//                //                        "usedFields" => array(
//                //                            "nama" => "",
//                //                        ),
//                //                        "editPoints" => array(1,),
//                //                    ),
//                //                ),
//                //                "cia" => array(
//                //                    "cash_account" => array(
//                //                        "elementType" => "dataModel",
//                //                        "inputType" => "radio",
//                //                        "label" => "cash account",
//                //                        "mdlName" => "MdlBankAccount",
//                //                        "key" => "id",
//                //                        "labelSrc" => "nama",
//                //                        "usedFields" => array(
//                //                            "nama" => "",
//                //                        ),
//                //                        "editPoints" => array(1,),
//                //                    ),
//                //                ),
//                "credit" => array(
//                    "top" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "term of payment",
//                        "mdlName" => "MdlTop",
//                        "mdlFilter" => array(),
//                        "key" => "kode",
//                        "labelSrc" => "nama",
//                        "description" => "",
//                        "usedFields" => array(
//                            "nama" => "",
//                        ),
//                        "editPoints" => array(1,),
//                        "noValidate" => true,
//                    ),
//                ),
//            ),
//        ),
//        "relativeOptions" => array(
//            //            "paymentMethod" => array(
//            //                "credit" => array(
//            //                    "discount" => array(
//            //                        "label" => "open discount",
//            //                        "defaultValue" => ".0",
//            //                        "maxValue" => "nett2*50/100",
//            //                        "auth" => array(
//            //                            //                            "groupID" => "c_holding",
//            //                            "groupID" => "o_finance",
//            //                        ),
//            //                        "addPoints" => array(1, 2),
//            //                    ),
//            //                    "dp" => array(
//            //                        "label" => "down payment",
//            //                        "defaultValue" => ".0",
//            //                        "maxValue" => "nett2*50/100",
//            //                        "auth" => array(
//            //                            //                            "groupID" => "c_finance",
//            //                            "groupID" => "o_finance",
//            //                        ),
//            //                        "addPoints" => array(1,),
//            //                    ),
//            //                ),
//            //                "cash" => array(
//            //                    "discount" => array(
//            //                        "label" => "open discount",
//            //                        "defaultValue" => ".0",
//            //                        "maxValue" => "nett2*50/100",
//            //                        "auth" => array(
//            //                            //                            "groupID" => "c_holding",
//            //                            "groupID" => "o_finance",
//            //                        ),
//            //                        "addPoints" => array(1, 2),
//            //                    ),
//            //                    "dp" => array(
//            //                        "label" => "down payment",
//            //                        "defaultValue" => ".0",
//            //                        "maxValue" => "nett2*50/100",
//            //                        "auth" => array(
//            //                            //                            "groupID" => "c_finance",
//            //                            "groupID" => "o_finance",
//            //                        ),
//            //                        "addPoints" => array(1,),
//            //                    ),
//            //                ),
//            //                "cia" => array(
//            //                    "nilai_cia" => array(
//            //                        "label" => "cash amount",
//            ////                        "defaultValue" => "nett2",
//            ////                        "minValue" => "nett2",
//            ////                        "maxValue" => "nett2",
//            ////                        "defaultValue" => "new_net3",
//            //                        "defaultValue" => "nett",
//            //                        "minValue" => "nett",
//            //                        "maxValue" => "nett",
//            //                        "auth" => array(
//            //                            //                            "groupID" => "c_finance",
//            //                            "groupID" => "c_finance",
//            //                        ),
//            //                        "addPoints" => array(1,),
//            //                    ),
//            ////                    "discount" => array(
//            ////                        "label" => "open discount",
//            ////                        "defaultValue" => ".0",
//            ////                        "maxValue" => "nett2*50/100",
//            ////                        "auth" => array(
//            ////                            //                            "groupID" => "c_admin",
//            ////                            "groupID" => "o_finance",
//            ////                        ),
//            ////                        "addPoints" => array(1, 2),
//            ////                    ),
//            //
//            //                ),
//            //
//            //            ),
//        ),
//        "additionalRows" => array(
//            "dummyElement" => array(
//                "yes" => array(
//                    "dppPPn" => array(
//                        "label" => "Dpp ppn",
//                        "defaultValue" => "dppPPn",
//                        "keyupAction" => "
//    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harga').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harga').value;}
//                            ",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                    ),
//                    "ppn" => array(
//                        "label" => "Ppn",
//                        "defaultValue" => "ppn",
//                        "maxValue" => "ppn_value",
//                        "minValue" => "ppn_value",
//                        "keyPressAction" => "",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                    ),
//
//                    "payment_out" => array(
//                        "label" => "Grand total",
//                        "defaultValue" => "payment_out",
//                        "maxValue" => "payment_out",
//                        "minValue" => "payment_out",
//                        "keyPressAction" => "",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                    ),
//
//                ),
//            ),
//        ),
//        "resumeFieldNames" => array(
//            "selectFields" => "suppliers_nama",
//            "title" => "vendor",
//        ),
//        "settlementHistoryFields" => array(
//            "dtime" => "time",
//            "nomer" => "receipt number",
//            "suppliers_nama" => "vendor",
//            "jenis_label" => "activity",
//            "transaksi_nilai" => "orig. value",
//            "add_disc" => "discount",
//            "grand_total" => "nett",
//        ),
//        "validatePaymentSource" => array(
//            "3" => "MdlLockerValue",
//        ),
//        "allowedMainEdit" => array("1", "4"),
//        "addMainSource" => array(
//            4 => array(
//                "fields" => array(
//                    "nomer" => "INV",
//                    "dppPPn" => "DPP",
//                    "ppn" => "PPN",
//                    "dateFaktur" => "Tgl faktur ",
//                    "eFaktur" => "e-faktur",
//                ),
//                "editableFields" => array(
//                    "eFaktur" => "text",
//                    "dateFaktur" => "date",
//                ),
//            ),
//        ),
//        "receiptEdit" => array(
//            4 => true,
//        ),
//        // berada di midValidate() Transaksi
//        "efakturValidator" => array(
//            4 => array(
//                "enabled" => true,
//                "kolom" => array(
//                    "dateFaktur" => "tanggal e-faktur belum diisikan.",
//                    "eFaktur" => "nomer e-faktur belum diisikan.",
//                ),
//                "source" => array(
//                    "ppn", // lebih dari 0
//                    //                "ppnfactor",
//                ),
//            ),
//        ),
//        "detailForceMain" => array(
//            2 => array(
//                "source" => "pph",
//                "target" => "valid_pph_key",
//                "elemenReset" => "MdlPph23MethodPotongan",
//                "current_element" => "pph23MethodPotongan",
//            ),
//        ),
//        // ======== =========
//        "followupMainNoteValidator" => array(
//            3 => array(
//                "enabled" => true,
//                "kolom" => array(
//                    "description_main_followup" => "nomer invoice dari vendor belum diisikan.",
//                ),
//                "source" => array(
//                    "description_main_followup",
//                ),
//            ),
//        ),
//        "followupMainNote" => array(
//            3 => array(
//                "previews" => true,
//                "enabled" => true,
//                "editabled" => true,
//                "label" => "INVOICE FROM VENDOR (*)",
//            ),
//            4 => array(
//                "previews" => true,
//                "enabled" => true,
//                "editabled" => false,
//                "label" => "INVOICE FROM VENDOR (*)",
//            ),
//
//        ),
//        //        "followupMainEditable" => "_followupLiveEdit/updateMainFieldByStep/",
//        "followupMainEditable" => "_followupLiveEdit/updateMainField/",
//        // ======== =========
//        "previewCtr" => "Create",
//        "canceledLabel" => array(
//            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
//                    <br>Silahkan melakukan {transaksi_nama} ulang di {cabang_nama}",
//            2 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
//                    <br>Silahkan melakukan {transaksi_actionLabel} ulang di {cabang_nama}",
//            3 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
//                    <br>Silahkan melakukan {transaksi_nama} ulang di {cabang_nama}",
//        ),
//    ),

);