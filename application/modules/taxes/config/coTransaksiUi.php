<?php
//region urusan tanggal-menanggal
date_default_timezone_set('asia/jakarta');
$date = new DateTime(date("Y-m-d")); // Y-m-d
$date->add(new DateInterval('P30D'));
$date->format('Y-m-d') . "\n";
//endregion

//tambahin filter "461ro untuk selectornota taxes 681
$config["coTransaksiUi"] = array(
    "681" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "taxes",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "Request taxes",
                "actionLabel" => "save",
                "source" => "",
                "target" => "681r",
                "userGroup" => "c_finance",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                //                "label" => "expense. authorization",
                "label" => "Taxes Authorization",
                "actionLabel" => "approve request",
                "source" => "651r",
                "target" => "681",
                "userGroup" => "c_finance",
                "stateLabel" => "make claim",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlObjekPajak1",
        "selectorSrcModel" => "MdlObjekPajak1",
        "selectedPrice" => array(
            "model" => "MdlHargaProduk",
            "label" => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "status=.1",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "objek pajak",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            //            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "nama"
            //            "satuan",
        ),
        "selectorProcessor" => "_processSelectProduct/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlNota",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "select nota",
        "pihakView" => "suppliers_nama",
        "pihakFilters" => array(
            // "jenis=.466r",
            "jenis in ('466r','460r','461ro')",
        ),
        "pihakProcessor" => "_processPihak/select",
        "pihakMainNota" => true,
        "pihakMainValueSrc" => array(
            "supplierID" => "suppliers_id", "supplierNama" => "suppliers_nama",
        ),
        "shortHistoryFields" => array(
            // "jenis_label" => "Activity",
            "dtime" => "Date",
            "customers_nama" => "GRN",
            "nomer_top" => "PO number",
            "nomer" => "Receipt number",
            "oleh_nama" => "Person",
            "transaksi_nilai" => "Amount",
            //            "ppn"      => "ppn",
            //            "nett"      => "netto",
        ),
        "selectorFields" => array("id", "nama"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "label" => "label",
            "reference" => "reference",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",
                //                "reference" => "reference",
            ),
            2 => array(
                "nama" => "item name",
                "jml" => "qty",
                //                "reference" => "reference",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "Price",
            ),
            2 => array(
                "harga" => "Price",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "shoppingCartNoteEditabled" => array(
            2 => true,
            3 => true,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
                //                "reference",
            ),
            2 => array(),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(),
        "pairRegistries" => array(
            "main", "items"
        ),
        "receiptElements" => array(
            //            "pettycash_account" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "pettycash amount",
            //                "pairedModel" => array(
            //                    "mdlName" => "ComLockerValue",
            //                    "mdlMethod" => "fetchBalances",
            //                    "mdlFilter" => array(
            //                        "cabang_id" => "placeID",
            //                        "state" => ".active",
            //                    ),
            //                    "key" => "produk_id",
            //                    "rekening" => "pettycash",
            //                    "fieldID" => "nilai",
            //                    "fieldLabel" => "saldo",
            //                ),
            //                "mdlName" => "MdlPettycashAccount",
            //                "mdlFilter" => array(
            //                    "cabang_id=placeID",
            //                ),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "usedFields" => array(
            //                    "nama" => "account",
            //                    "saldo" => "balance",
            //                ),
            //                "editPoints" => array(1,),
            //                "noValidate" => true,
            //            ),
        ),
        "relativeElements" => array(),
        "relativeOptions" => array(),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "",
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "681re",
                "label" => "EDIT Request taxes",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "681rrj",
                "label" => "REJECT Request taxes",
            ),
        ),
    ),
    //request objek pajak pph
    "5681" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "pph22",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "pph22 taxes",
                "actionLabel" => "save",
                "source" => "",
                "target" => "5681r",
                "userGroup" => "c_finance",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                //                "label" => "expense. authorization",
                "label" => "pph22 Authorization",
                "actionLabel" => "approve request",
                "source" => "5681r",
                "target" => "5681",
                "userGroup" => "c_finance",
                "stateLabel" => "make claim",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlObjekPajak",
        "selectorSrcModel" => "MdlObjekPajak",
        "selectedPrice" => array(
            "model" => "MdlHargaProduk",
            "label" => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "status=.1",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "objek pajak",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            //            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "nama",
            //            "satuan",
        ),
        "selectorProcessor" => "_processSelectProduct/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlNota",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "select nota",
        "pihakView" => "suppliers_nama",
        "pihakFilters" => array(
            //            "jenis=.466r",
            "jenis in ('466r', '460r')",
        ),
        "pihakProcessor" => "_processPihak/select",
        "pihakMainNota" => true,
        "shortHistoryFields" => array(
            "jenis_label" => "Activity",
            "dtime" => "Date",
            "customers_nama" => "GRN",
            "nomer_top" => "PO number",
            "nomer" => "Receipt number",
            "oleh_nama" => "Person",
            "transaksi_nilai" => "Amount",
            //            "ppn"      => "ppn",
            //            "nett"      => "netto",
        ),
        "selectorFields" => array("id", "nama"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "label" => "label",
            "reference" => "reference",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",
                //                "reference" => "reference",
            ),
            2 => array(
                "nama" => "item name",
                "jml" => "qty",
                //                "reference" => "reference",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "Price",
            ),
            2 => array(
                "harga" => "Price",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "shoppingCartNoteEditabled" => array(
            2 => true,
            3 => true,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
                //                "reference",
            ),
            2 => array(),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),

        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(),
        //        "shoppingCartFieldMidValidatorsComparison" => array(
        //            "harga" => "sumber",
        //            "pettycashSaldo__saldo" => "target",
        //        ),

        "receiptElements" => array(
            //            "pettycash_account" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "pettycash amount",
            //                "pairedModel" => array(
            //                    "mdlName" => "ComLockerValue",
            //                    "mdlMethod" => "fetchBalances",
            //                    "mdlFilter" => array(
            //                        "cabang_id" => "placeID",
            //                        "state" => ".active",
            //                    ),
            //                    "key" => "produk_id",
            //                    "rekening" => "pettycash",
            //                    "fieldID" => "nilai",
            //                    "fieldLabel" => "saldo",
            //                ),
            //                "mdlName" => "MdlPettycashAccount",
            //                "mdlFilter" => array(
            //                    "cabang_id=placeID",
            //                ),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "usedFields" => array(
            //                    "nama" => "account",
            //                    "saldo" => "balance",
            //                ),
            //                "editPoints" => array(1,),
            //                "noValidate" => true,
            //            ),
        ),
        "relativeElements" => array(),
        "relativeOptions" => array(),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "",
        ),
        "pairCostInjectors" => array(
            1 => array(
                "source" => "items",
                "target" => "items",
                "jenis" => "biaya",
                "kolom" => array(
                    "costName" => "nama",
                    "costNilai" => "nilai",
                ),
            ),
            2 => array(
                "source" => "items",
                "target" => "rsltItems2",
                "jenis" => "biaya",
                "kolom" => array(
                    "costName" => "nama",
                    "costNilai" => "nilai",
                ),
            ),
        ),
        "previewCtr" => "Create",
        "pairRegistries" => array(
            "main", "items"
        ),
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "5681re",
                "label" => "EDIT pph22 taxes",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "5681rrj",
                "label" => "REJECT pph22 taxes",
            ),
        ),
    ),
    "110" => array(
        "icon" => "fa fa-opencart",
        "label" => "e-faktur ppn keluaran",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "PREPARE E-FAKTUR",
                "actionLabel" => "PREPARED",
                "source" => "",
                "target" => "110r",
                "userGroup" => "sys",
                "stateLabel" => "create faktur pajak",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepared by",
            ),
            2 => array(
                "label" => "ENTRY E-FAKTUR",
                "actionLabel" => "entry efaktur",
                "source" => "110r",
                "target" => "110e",
                "userGroup" => "c_finance",
                "stateLabel" => "entry e-faktur",
                "stateColor" => "#ff7700",
                "stateCaption" => "Entry by",
                //                "allowEdit" => true,
            ),
            3 => array(
                "label" => "OTORISASI E-FAKTUR",
                "actionLabel" => "approve efaktur",
                "source" => "110e",
                "target" => "110",
                "userGroup" => "c_finance_spv",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "Approved by",
                //                "allowEdit" => true,
            ),

        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlProduk2",
        "selectorSrcModel" => "MdlProduk2",
        "selectedPrice" => array(
            "model" => "MdlHargaProduk",
            "label" => array("jual", "ppv", "disc", "disc_percent"),
            "key_label" => array(
                "jual" => "harga",
                "ppv" => "ppv",
                "disc" => "disc",
                "disc_percent" => "disc (%)",
            ),
            "mainSrc" => "jual",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            //            "cabang_id='1'", // mengambil dari $this->session->login(cabang_id) JANGAN LUPA DIGANTI YA..
            //            "jumlah>0",
            //            "state='active'",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            "produk_kode" => "kode",
            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            //            "nama", "satuan",// "jumlah"
            //            "keterangan", "kode", "satuan",// "jumlah"
            "nama",
            "kode",
            "satuan",// "jumlah"
        ),
        "selectorProcessor" => "_processSelectProduct/select",
        "itemSwapper" => "_processSelectProduct/multiSelect",
        "swappedKeys" => array("pihakID", "pihakName"),
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCustomer_and_pre",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
        "pihakProcessor" => "Selectors/_processPihak/select",

        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nomer_top" => "SO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            //            "transaksi_nilai" => "amount",
            // "jual" => "amount",
            "harga" => "amount",
            "disc" => "discount",
            "dpp_ppn" => "dpp",
            "new_grand_ppn" => "ppn",
            "downpayment" => "downpayment",
            //            "nett2" => "total amount",
            "tagihan" => "total amount",

        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nomer_top" => "SO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "disc" => "discount",
            "dpp_ppn" => "dpp",
            "ppn" => "ppn",
            "nett2" => "total amount",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        "historyFields" => array(
            1 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "SO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                "nett1" => "netto",
                "ongkir" => "shipping service",
                // "ongkir" => "shiping",
                // "ppn" => "ppn",
                "dpp_ppn" => "dpp",
                "grand_ppn" => "ppn",
                // "nett2" => "total amount",
                "new_net3" => "total amount",
                "print_label" => "tool",
            ),
            2 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "SO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                "nett1" => "netto",
                // "ppn" => "ppn",
                // "nett2" => "total amount",

                "ongkir" => "shipping service",
                "dpp_ppn" => "dpp",
                "grand_ppn" => "ppn",
                "new_net3" => "total amount",

                "print_label" => "tool",
            ),
        ),

        "extHistoryFields" => array(
            1 => array(
                "review_details" => "id",
                "print_label" => "nomer",
            ),
            2 => array(
                "review_details" => "id",
                "print_label" => "nomer",
            ),

        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "produk name",
                "produk_kode" => "part number",
                "stok" => "stok",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "nama" => "produk name",
                "produk_kode" => "part number",
                "stok" => "stok",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            3 => array(
                "nama" => "produk name",
                "produk_kode" => "part number",
                //                "stok" => "stok",
                "jml" => "qty",
                "satuan" => "uom",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "label" => "label",
            "satuan" => "satuan",
            "ppn" => "harga*(10/100)",
            "berat_gross" => "berat_gross",
            "lebar_gross" => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross" => "tinggi_gross",
            "volume_gross" => "volume_gross",

            "volume" => "volume",
            "berat" => "berat",
            "lebar" => "lebar",
            "tinggi" => "tinggi",
            "panjang" => "panjang",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "nett1" => "price",
            ),
            2 => array(
                "nett1" => "price",
            ),
            3 => array(
                "nett1" => "price",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "jml",
                "produk_ord_jml",
                "disc_percent",
                "disc",
            ),
            2 => array(
                //                "jml",
                //                "produk_ord_jml",
                //                "disc_percent",
                //                "disc",
            ),
        ),
        "shoppingCartUnionSelectors" => array(
            //            1 => array(
            //                "base" => "disc_percent",
            //                "members" => array(
            //                    "disc_percent",
            //                    "disc",
            //                ),
            //            ),
        ),
        "shoppingCartKeyUpEvents" => array(
            1 => array(
                "disc_percent" => "document.getElementById('{disc}').value=((parseFloat(removeCommas(this.value))*parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))/100)",
                "disc" => "document.getElementById('{disc_percent}').value=((parseFloat(removeCommas(this.value))/parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))*100)",
            ),
        ),
        "shoppingCartFieldValidators" => array(
            //            "jml" => "quantity",
            //            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "customer ID",
            "pihakName" => "customer name",

        ),
        "shoppingCartRowOptionalValidators" => array(
            //            "shippingService" => array(
            //                "ongkir_ppn_by_cust" => array(
            //                    "shipping_service" => "ongkir",
            //                ),
            //                "ongkir_tanpa_ppn_by_cust" => array(
            //                    "shipping_service" => "ongkir",
            //                ),
            //                //                "ongkir_tanpa_ppn_by_company" => array(
            //                //                    "shipping_service" => "ongkir",
            //                //
            //                //                ),
            //            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*(harga-disc)",//nett2
            2 => "jml*(harga-disc)",
            3 => "jml*(harga-disc)",
        ),
        "shoppingCartHideSubamount" => array(
            1 => false,
            2 => false,
            3 => false,
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "shipping_service" => "Shipping Service",
                "grand_total_ui" => "Total Amount",
                "grand_ppn" => "PPN",
                //                "tagihan_ui" => "Grand Total",
                "nilai_pembulatan" => "Pembulatan",
                "grand_pembulatan" => "Total+PPN",
            ),
            2 => array(
                "shipping_service" => "Shipping Service",
                "grand_total_ui" => "Amount",
                "nilai_pembulatan" => "Pembulatan",
                "nett1_bulat" => "Total Amount",
                "grand_ppn" => "PPN",
                // "grand_pembulatan" => "Total+PPN*",
            ),
            3 => array(
                "shipping_service" => "Shipping Service",
                "grand_total_ui" => "Amount",
                "nilai_pembulatan" => "Pembulatan",
                "nett1_bulat" => "Total Amount",
                "grand_ppn" => "PPN",
                "grand_pembulatan" => "Total+PPN",
                //                "tagihan" => "Grand Total",
            ),
        ),
        "shoppingCartSumFieldsAdditional" => array(
            1 => array(
                "tagihan" => "Grand Total",
            ),
            2 => array(
                "tagihan" => "Grand Total",
            ),
            3 => array(
                "tagihan" => "Grand Total",
            ),
        ),
        "receiptElements" => array(
            //            "discountMethod" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "discount method",
            //                "mdlName" => "MdlDiskonMethod",
            //                "key" => "id",
            //                "defaultValue" => "item",
            //                "labelSrc" => "name",
            //                "usedFields" => array(
            //                    "name" => "method",
            //                ),
            //                "editPoints" => array(1,),
            //                "targetMethod" => array(
            //                    "item" => "ReComDiscItem",
            //                    "customer" => "ReComDiscCustomer",
            //                ),
            //                "noValidate" =>true,
            //            ),
            //            "shippingService" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "shipping service",
            //                "mdlName" => "MdlOngkir",
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "labelSrc" => "name",
            //                "description" => "",
            //                "usedFields" => array(
            //                    "name" => "",
            //                ),
            //                "editPoints" => array(1),
            //                "noValidate" =>true,
            //            ),
            //            "customerDetails" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "CUSTOMER DETAILS",
            //                "mdlName" => "MdlCustomer_and_pre",
            //                "mdlFilter" => array("id=pihakID"),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "usedFields" => array(
            //                    "nama" => "Name",
            //                    "alamat_1" => "Addr",
            //                    "kelurahan" => "Kel",
            //                    "kecamatan" => "Kec",
            //                    "kabupaten" => "Kab",
            //                    "propinsi" => "Prop",
            //                    "tlp" => "Phone",
            //                    "tlp_1" => "Phone",
            //                    "tlp_2" => "Handphone",
            //                    "npwp" => "NPWP",
            //                    "no_ktp" => "nik",
            //                    "nik" => "NIK",
            //                ),
            //                "editPoints" => array(1, 2, 3, 4),
            //                "noValidate" =>true,
            //            ),
            //            "billingDetails" => array(
            //                "elementType" => "dataModel",
            //                "elementJoint" => array(
            //                    "method" => "lookUpJointCustomer",
            //                    "mdlFilter" => "id=pihakID",
            //                    "usedFields" => array(
            //                        "no_ktp" => "nik",
            //                        "nik" => "nik",
            //                        "npwp" => "npwp",
            //                    ),
            //                ),
            //                "inputType" => "radio",
            //                "label" => "BILLING DETAILS",
            //                "mdlName" => "MdlCustomerBillAddress",
            //                "mdlFilter" => array("extern_id=pihakID"),
            //                //                "mdlName" => "MdlCustomer_and_pre",
            //                //                "mdlFilter" => array("id=pihakID"),
            //                "key" => "id",
            //                //                "labelSrc" => "alias",
            //                "labelSrc" => "alias",
            //                "usedFields" => array(
            //                    //                    "alias" => "Name",
            //                    "alias" => "Name",
            //                    "alamat" => "Addr",
            //                    "kelurahan" => "Kel",
            //                    "kecamatan" => "Kec",
            //                    "kabupaten" => "Kab",
            //                    "propinsi" => "Prop",
            //                    "tlp" => "Phone",
            //                    "tlp_1" => "Phone",
            //                    "tlp_2" => "Handphone",
            //                    "npwp" => "NPWP",
            //                    "no_ktp" => "NIK",
            //                ),
            //                "editPoints" => array("5"),
            //                "noValidate" =>true,
            //            ),
            //            "deliveryDetails" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "DELIVERY DETAILS",
            //                "mdlName" => "MdlCustomerAddress",
            //                "mdlFilter" => array("extern_id=pihakID"),
            //                "key" => "id",
            //                "labelSrc" => "alias",
            //                "usedFields" => array(
            //                    "alias" => "Attn",
            //                    "alamat" => "Address",
            //                    "kecamatan" => "Kec",
            //                    "kabupaten" => "Kab",
            //                    "propinsi" => "propinsi",
            //                    "tlp" => "Phone",
            //                    "tlp_2" => "Handphone",
            //                    //                    "npwp" => "NPWP",
            //                    //                    "propinsi" =>"",
            //                ),
            //                "editPoints" => array(1, 2, 3, 4),
            //            ),
            //            "detilSize" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "Data ukuran",
            //                "mdlName" => "MdlMeasurement",
            //                "mdlFilter" => array("extern_id=pihakID"),
            //                "key" => "id",
            //                "labelSrc" => "name",
            //                "defaultValue" => "ckd",
            //                "usedFields" => array(
            //                    "name" => "",
            //                ),
            //                "editPoints" => array(1, 2, 3),
            //            ),
            //            "tos" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "term of shipment",
            //                "mdlName" => "MdlTos",
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "defaultValue" => "20",
            //                "labelSrc" => "nama",
            //                "description" => "",
            //                "usedFields" => array(
            //                    "nama" => "",
            //                ),
            //                "editPoints" => array(1, 2, 3, 4),
            //            ),
            //            "shippingDate" => array(
            //                "elementType" => "dataField",
            //                // "inputType" => "combo",
            //                "label" => "shipping date",
            //                "inputType" => "date",
            //                "defaultValue" => date("Y-m-d"),
            //                //                "editPoints" => array(1),
            //                "editPoints" => array(1, 2, 3, 4, 5),
            //            ),
            //            "capacity" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "capacity",
            //                "mdlName" => "MdlCapacity",
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "usedFields" => array(
            //                    "nama" => "",
            //                ),
            //                "editPoints" => array(1, 2, 3, 4),
            //            ),
            //            "dueDate" => array(
            //                "elementType" => "dataField",
            //                "label" => "due date",
            //                "inputType" => "date",
            //                "defaultValue" => $date->format('Y-m-d'),
            //                "editPoints" => array(1, 2, 3, 4, 5),
            //            ),
            //            "paymentMethod" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "payment method",
            //                "mdlName" => "MdlPaymentMethod",
            //                "key" => "id",
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
            //                //                "mdlFilter"   => array("id=pihakID"),
            //                "key" => "id",
            //                "labelSrc" => "name",
            //                "usedFields" => array(
            //                    "name" => "name",
            //
            //                ),
            //                "editPoints" => array(1, 2, 3),
            //            ),
        ),
        "relativeElements" => array(),
        "pairRegistries" => array(
            "main", "items"
        ),
        "pairMakers" => array(
            1 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
            ),
            3 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
            ),
        ),
        "pairInjectors" => array(
            1 => array(
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                    "out_detail" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
            ),
            3 => array(
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                    "out_detail" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
            ),
        ),
        "validationRules" => array(),
        "connectedDiscount" => array(
            //            "enabled" => true,
            //            "mdlNameRelation" => "MdlConnectedDiscount",
            //            "mdlNameSource" => "MdlAddDiscount",
            //            "jenis" => "produk",
            //            "jenis_locker" => "stock",
        ),
        "additionalRows" => array(
            //            "shippingService" => array(
            //                "ongkir_ppn_by_cust" => array(
            //                    "shipping_service" => array(
            //                        "label" => "shipping service",
            //                        "defaultValue" => "",
            //                        "maxValue" => "",
            //                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
            //                        'disabled' => "",
            //                        "addPoints" => array(1),
            //                        "editPoints" => array(4),
            //                    ),
            //                ),
            //                "ongkir_tanpa_ppn_by_cust" => array(
            //                    "shipping_service" => array(
            //                        "label" => "shipping service",
            //                        "defaultValue" => "",
            //                        "maxValue" => "",
            //                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
            //                        'disabled' => "",
            //                        "addPoints" => array(1, 4),
            //                        "editPoints" => array(4),
            //                    ),
            //                ),
            //                "ongkir_tanpa_ppn_by_company" =>array(
            //                    "shipping_service" => array(
            //                        "label" => "shipping service",
            //                        "defaultValue" => "",
            //                        "maxValue" => "",
            //                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
            //                        'disabled' => "",
            //                        "addPoints" => array(1,),
            //                    ),
            //
            //                ),
            //            ),
        ),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "settlementHistoryFields" => array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "customers_nama" => "customer",
            "jenis_label" => "activity",
            //            "transaksi_nilai" => "orig. value",
            //            "add_disc" => "discount",
            //            "grand_total" => "nett",
            "harga" => "orig. value",
            "disc" => "discount",
            "nett1" => "nett",
            "ppn" => "ppn",
            "nett2" => "total",
        ),
        "allowedMainEdit" => array("1"),
        "addMainSource" => array(
            1 => array(
                "fields" => array(
                    "nomer" => "INV",
                    "total_ui" => "DPP",
                    "new_grand_ppn" => "PPN",
                    "dateFaktur" => "Tgl faktur ",
                    "eFaktur" => "Nomer faktur",
                    //                    "nilai_faktur" => "nilai(RP)",
                ),
                "editableFields" => array(
                    //                    "eFaktur" => "",
                    //                    "dateFaktur" => "",
                ),
            ),
            2 => array(
                "fields" => array(
                    "efaktur_source" => "INV",
                    "total_ui" => "DPP",
                    "new_grand_ppn" => "PPN",
                    "dateFaktur" => "Tgl faktur ",
                    "eFaktur" => "nomer faktur",
                    //                    "nilai_faktur" => "nilai(RP)",
                ),
                "editableFields" => array(
                    "eFaktur" => "text",
                    "dateFaktur" => "date",
                    //                    "nilai_faktur" => "",
                ),
            ),
            3 => array(
                "fields" => array(
                    "efaktur_source" => "INV",
                    "total_ui" => "DPP",
                    "new_grand_ppn" => "PPN",
                    "dateFaktur" => "Tgl faktur ",
                    "eFaktur" => "nomer faktur",
                ),
                "editableFields" => array(
                    "eFaktur" => "text",
                    "dateFaktur" => "date",
                ),
            ),
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "110re",
                "label" => "EDIT PREPARE E-FAKTUR",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "110rrj",
                "label" => "REJECT PREPARE E-FAKTUR",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "110erj",
                "label" => "REJECT ENTRY E-FAKTUR",
            ),
        ),
    ),
    "111" => array(
        "icon" => "fa fa-money",
        "label" => "Realisasi ppn masukan",
//        "paymentConfig" => true,
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "realisasi ppn masukan",
                "actionLabel" => "entry faktur ppn masukan",
                "source" => "",
                "target" => "111r",
                "userGroup" => "sys",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "",
                "stateFooter" => "entry by",
            ),
            2 => array(
                "label" => "isi faktur ppn masukan",
                "actionLabel" => "entry faktur ppn masukan",
                "source" => "111r",
                "target" => "111",
                "userGroup" => "c_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "",
                "stateFooter" => "entry by",
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
//            "cabang_id=placeID",
//            "jenis=.467",
//            "ppn_sisa>.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer",
            "dtime",
        ),

        "selectorProcessor" => "_processSelectNota/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            //            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "details" => "detail",
            "oleh_nama" => "person",
            "cash_account__label" => "account",
            "dpp_final" => "DPP",
            "ppn_belum_faktur" => "PPN belum faktur",
            "nilai_bayar" => "paid",
            "eFaktur" => "faktur",
            "keterangan" => "keterangan",
            "print_label" => "tool",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "extHistoryFields" => array(
            1 => array(
                //                "review_details" => "id",
                "print_label" => "nomer",
            ),
        ),
        "extHistoryFields2" => array(
            1 => array(
                "details" => "nama",
            ),
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",
            ),

        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nomer",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar*",
            "sisa" => "sisa",
            "creditValue" => "diskon",
            "dpp_ppn" => "dpp_ppn",
            "ppn_approved" => "ppn_approved",
            "ppn_sisa" => "ppn_sisa",
//            "ppn" => "diskon",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "dpp_ppn" => "dpp ppn",
                "ppn_approved" => "sudah faktur",
                "ppn_sisa" => "belum faktur",
            ),
            2 => array(
                "sisa" => "dpp ppn",
//                "ppn_belum_faktur" => "sudah faktur",
//                "ppn_sisa" => "belum faktur",
            ),

        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "sisa" => "tagihan bruto",
                "selisih_koreksi" => "Koreksi -",
                "selisih_koreksi_plus" => "Koreksi +",
                "uang_muka_dipakai_ppn" => "Uangmuka + ppn",
                "dpp_final" => "Dpp",
                "ppn_final" => "Ppn",
            ),
            2 => array(
                "sisa" => "tagihan bruto",
                "selisih_koreksi" => "Koreksi -",
                "selisih_koreksi_plus" => "Koreksi +",
                "uang_muka_dipakai_ppn" => "Uangmuka + ppn",
                "dpp_final" => "Dpp",
                "ppn_final" => "Ppn",
            ),

        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
            2 => "sisa",
        ),
        "showItems" => "false",
        "shoppingCartAvoidRemove" => true,
        "viewDescriptionNote" => true,
        /*
         * untuk menampilkan produk yang dibeli tidak hanya nomer GRN nya
         */
        "shopingCartPairProdukSrc" => array(
//            "id" => "id",
            "barcode" => "sku",
            "nama" => "description",
            "produk_kode" => "produk code",
            "satuan" => "uom",
//            "jml" => "jml",
            "harga" => "DPP",
//            "disc" => "diskon",
//            "nett1" => "dpp",


        ),
        "shopingCartPairProdukGate" => "items",
        "addMainSource" => array(
            2 => array(
                "fields" => array(
//                    "nomer" => "INV",
                    "dpp_final" => "Dasar Pengenaan pajak",
                    "ppn_belum_faktur" => "Total ppn",
                    "dateFaktur" => "Tgl faktur ",
                    "eFaktur" => "e-faktur",
                ),
                "editableFields" => array(
//                    "dpp_ppn" => "number",
//                    "ppn_realisasi" => "number",
                    "eFaktur" => "text",
                    "dateFaktur" => "date",
                ),
                "editProcess" => "_processPihak/addTaxData"
            ),
        ),
        "efakturValidator" => array(
            2 => array(
                "enabled" => true,
                "kolom" => array(
                    "dateFaktur" => "tanggal e-faktur belum diisikan.",
                    "eFaktur" => "nomer e-faktur belum diisikan.",
                ),
                "source" => array(
                    "ppn_belum_faktur",
                ),
            ),
        ),
        /*END PAIR PRODUK SRC
         * ------------------------------------------------------------
         */
        "tagihanSrc" => "ppn_sisa",
        "receiptElements" => array(
            "vendorDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "vendor details",
                "mdlName" => "MdlSupplier",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "name",
                    "npwp" => "tax-ID",
                    "alamat_1" => "address",
                    "tlp_1" => "phone",
                ),
                "editPoints" => array(1, 2, 3),
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
            //            "cashMethode" => array(
            //                "reguler" => array(
            //                    "cash_account" => array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "radio",
            //                        "label" => "cash account",
            //                        "pairedModel" => array(
            //                            "mdlName" => "ComLockerValue",
            //                            "mdlMethod" => "fetchBalances",
            //                            "mdlFilter" => array(
            //                                "cabang_id" => "placeID",
            //                                "state" => ".active",
            //                            ),
            //                            "key" => "produk_id",
            //                            "rekening" => "kas",
            //                            "fieldID" => "nilai",
            //                            "fieldLabel" => "saldo",
            //                        ),
            //                        "mdlName" => "MdlBankAccount_cash_and_in",
            //                        "mdlFilter" => array(
            //                            "cabang_id=placeID",
            //                        ),
            //                        "key" => "id",
            //                        "labelSrc" => "nama",
            //                        "usedFields" => array(
            //                            "nama" => "account",
            //                            "saldo" => "balance",
            //                            "folders" => "acountMasterID",
            //                            "folders_nama" => "accountMaster",
            //                        ),
            //                        "editPoints" => array(1,),
            //                        "noValidate" => true,
            //                    ),
            //                ),
            //                "rekening_koran" => array(
            //                    "cash_account" => array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "radio",
            //                        "label" => "rekening koran",
            //                        "pairedModel" => array(
            //                            "mdlName" => "ComLockerValue",
            //                            "mdlMethod" => "fetchBalances",
            //                            "mdlFilter" => array(
            //                                "cabang_id" => "placeID",
            //                                "state" => ".active",
            //                            ),
            //                            "key" => "produk_id",
            //                            "rekening" => "plafon hutang bank",
            //                            "fieldID" => "nilai",
            //                            "fieldLabel" => "saldo",
            //                        ),
            //                        "mdlName" => "MdlRekeningKoran",
            //                        "mdlFilter" => array(
            //                            "cabang_id=placeID",
            ////                     "id=pihakRelId",
            //                        ),
            //                        "key" => "id",
            //                        "labelSrc" => "nama",
            //                        "usedFields" => array(
            //                            "nama" => "account",
            //                            "saldo" => "balance",
            //                            "folders" => "acountMasterID",
            //                            "folders_nama" => "accountMaster",
            //                        ),
            //                        "editPoints" => array(1),
            //                        "noValidate" => true,
            //
            //                        //perhitungan rekening koran hutang vs kas(CN rekening koran)
            ////                        "pairMethod" => array(
            ////                            "recom" => "ReComRekeningKoran",
            ////                            "calculate" => array(
            ////                                "jenis_source" => "cashMethode",
            ////                                "source" => "nilai_entry",
            ////                                "target" => "credit_note_dipakai",
            ////                                "pair_source" => "nilai_entry",//sumber yang dibandingkan
            ////                                "id" => "cash_account",
            ////                                "mdlName" => "ComRekeningPembantuKas",
            ////                            ),
            ////                        ),
            //                    ),
            //                ),
            //            ),

        ),
        "pairMakers" => array(
            1 => array(
                "saldoRekening" => array(
                    "helperName" => "he_cek_saldo_kas",
                    "functionName" => "cekStockSaldoKas",
                    "params" => array(
                        "cabang_id" => "placeID",
                    ),
                    "target" => array("main", "out_master"),
                ),
            ),
        ),
        "pairRegistries" => array(
            "main", "items"
        ),

        "shoppingCartRowValidators" => array(
            "pihakID" => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartRowNumValidators" => array(//            "nilai_entry" => "amount of payment",//sementara untuk lolosin bayar pakai keuntungan kurs
        ),
        "shoppingCartUnionValidators" => array(),

        "shopingCartUnionComparison" => array(),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(),
            ),
        ),
        "shoppingCartReferenceFields" => array(
            "nomer" => "receipt number",
            "nomer_top" => "receipt ref.",
            "refNum" => "return ref.",
            "fulldate" => "date",
//            "tagihan" => "due amount",
//            "refValue" => "returned",
            "dpp_ppn" => "dpp",
            "ppn_approved" => "sudah faktur",
            "ppn_sisa" => "ppn belum faktur",
            "notes" => "description",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "vendor",
            "dpp_ppn" => "DPP",
            "ppn" => "Ppn",
            "ppn_approved" => "sudah faktur",
            "ppn_sisa" => "belum faktur",
//            "sisa" => "due remain***",
        ),
        "resumeFieldNames" => array(
            "selectFields" => "suppliers_nama",
            "title" => "vendor*",
        ),
        "ppnDisabled" => array(
//            "enabled" => true,
            "enabled" => false,
            "notes" => "PPN masukan belum diapprove oleh Finance.",
        ),
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di  {cabang_nama}",
        ),
    ),
    //request pajak pph 29
    "5683" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "pph 29",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "request",
                "actionLabel" => "save",
                "source" => "",
                "target" => "5683r",
                "userGroup" => "c_finance",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                //                "label" => "expense. authorization",
                "label" => "Authorization",
                "actionLabel" => "approve request",
                "source" => "5683r",
                "target" => "5683",
                "userGroup" => "c_finance_spv",
                "stateLabel" => "make claim",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
        ),
        "template" => "template/transaksi_nopihak.html",
        "selectorModel" => "MdlPph29Static",
        "selectorSrcModel" => "MdlPph29Static",
        "selectedPrice" => array(
            "model" => "MdlHargaProduk",
            "label" => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "status=.1",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "objek pajak",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            //            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "nama",
            //            "satuan",
        ),
        "selectorProcessor" => "_processSelectBiaya/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlNota",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "select nota",
        "pihakFilters" => array(
            "jenis=.463o",
        ),
        "pihakProcessor" => "_processPihak/select",
        "pihakMainNota" => true,
        "shortHistoryFields" => array(
            "jenis_label" => "Activity",
            "dtime" => "Date",
            //            "customers_nama" => "GRN",
            "nomer_top" => "PO number",
            "nomer" => "Receipt number",
            "oleh_nama" => "Person",
            "harga" => "Amount",
            //            "ppn"      => "ppn",
            //            "nett"      => "netto",
        ),
        "selectorFields" => array("id", "nama"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "label" => "label",
            "reference" => "reference",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",
                //                "harga_source" =>"dpp",
                //                "reference" => "reference",
            ),
            2 => array(
                "nama" => "item name",
                "jml" => "qty",
                //                "harga_source" =>"dpp",
                //                                "reference" => "reference",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                //                "harga_source" =>"dpp",
                "harga" => "Price",
            ),
            2 => array(
                //                "harga_source" =>"dpp",
                "harga" => "Price",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "shoppingCartNoteEditabled" => array(
            2 => true,
            3 => true,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                //                "jml",
                //                "reference",
            ),
            2 => array(),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => false,
            3 => true,

        ),
        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(),

        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "receiptElements" => array(
            //            "cash_account" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "cash account",
            //                "pairedModel" => array(
            //                    "mdlName" => "ComRekeningPembantuKas",
            //                    "mdlMethod" => "fetchBalances",
            //                    "mdlFilter" => array(
            //                        "cabang_id=placeID",
            //                    ),
            //                    "key" => "extern_id",
            //                    "rekening" => "kas",
            //                    "fieldID" => "debet",
            //                    "fieldLabel" => "saldo",
            //                ),
            //                "mdlName" => "MdlBankAccount_cash_and_in",
            //                "mdlFilter" => array(
            //                    "cabang_id=placeID",
            //                ),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "usedFields" => array(
            //                    "nama" => "account",
            //                    "saldo" => "balance",
            //                ),
            //                "editPoints" => array(1,),
            //                "noValidate" => true,
            //            ),
            "dummyElement" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "auto-validation",
                "mdlName" => "MdlDummyElement",
                //                "mdlFilter"   => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",

                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),
        "pairMakers" => array(
            1 => array(
                "saldoRekening" => array(
                    "helperName" => "he_cek_saldo_kas",
                    "functionName" => "cekStockSaldoKas",
                    "params" => array(
                        "cabang_id" => "placeID",
                    ),
                    "target" => array("main", "out_master"),
                ),

            ),
        ),
        "relativeElements" => array(),
        "relativeOptions" => array(),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "",
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "5683re",
                "label" => "EDIT request pph 29",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "5683rrj",
                "label" => "REJECT request pph 29",
            ),
        ),
    ),
    //config pph 25 center
    "117" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "pph 25",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "request ",
                "actionLabel" => "save",
                "source" => "",
                "target" => "117r",
                "userGroup" => "c_finance",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                //                "label" => "expense. authorization",
                "label" => "Authorization",
                "actionLabel" => "approve request",
                "source" => "117r",
                "target" => "117",
                "userGroup" => "c_finance_spv",
                "stateLabel" => "make claim",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
        ),
        "template" => "template/transaksi_nopihak.html",
        "selectorModel" => "MdlPph25Static",
        "selectorSrcModel" => "MdlPph25Static",
        "selectedPrice" => array(
            "model" => "MdlHargaProduk",
            "label" => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "status=.1",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "objek pajak",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            //            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "nama",
            //            "satuan",
        ),
        "selectorProcessor" => "_processSelectBiaya/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlNota",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "select nota",
        "pihakFilters" => array(
            "jenis=.463o",
        ),
        "pihakProcessor" => "_processPihak/select",
        "pihakMainNota" => true,
        "shortHistoryFields" => array(
            "jenis_label" => "Activity",
            "dtime" => "Date",
            //            "customers_nama" => "GRN",
            "nomer_top" => "request number",
            "nomer" => "approval number",
            "oleh_nama" => "pic",
            "cash_account__label" => "sumber pembayaran",
            "harga" => "Amount",
            //            "ppn"      => "ppn",
            //            "nett"      => "netto",
        ),
        "selectorFields" => array("id", "nama"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "label" => "label",
            "reference" => "reference",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",
                //                "harga_source" =>"dpp",
                //                "reference" => "reference",
            ),
            2 => array(
                "nama" => "item name",
                "jml" => "qty",
                //                "harga_source" =>"dpp",
                //                                "reference" => "reference",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                //                "harga_source" =>"dpp",
                "harga" => "Price",
            ),
            2 => array(
                //                "harga_source" =>"dpp",
                "harga" => "Price",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "shoppingCartNoteEditabled" => array(
            2 => true,
            3 => true,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                //                "jml",
                //                "reference",
            ),
            2 => array(),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => false,
            3 => true,

        ),
        "shoppingCartFieldValidators" => array(
            "harga" => "nilai pembayaran",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "receiptElements" => array(
            "cash_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "cash account",
                "pairedModel" => array(
                    "mdlName" => "ComRekeningPembantuKas",
                    "mdlMethod" => "fetchBalances",
                    "mdlFilter" => array(
                        "cabang_id=placeID",
                    ),
                    "key" => "extern_id",
                    "rekening" => "kas",
                    "fieldID" => "debet",
                    "fieldLabel" => "saldo",
                ),
                "mdlName" => "MdlBankAccount_cash_and_in",
                "mdlFilter" => array(
                    "cabang_id=placeID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account",
                    "saldo" => "balance",
                ),
                "editPoints" => array(1, 2),
//                "noValidate" => true,
                "labelValidate" => "Silahkan memilih sumber pembayaran sebelum melanjutkan transaksi.",
            ),
            "dummyElement" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "auto-validation",
                "mdlName" => "MdlDummyElement",
                //                "mdlFilter"   => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",

                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),
        "pairMakers" => array(
            1 => array(
                "saldoRekening" => array(
                    "helperName" => "he_cek_saldo_kas",
                    "functionName" => "cekStockSaldoKas",
                    "params" => array(
                        "cabang_id" => "placeID",
                    ),
                    "target" => array("main", "out_master"),
                ),

            ),
        ),
        "relativeElements" => array(),
        "relativeOptions" => array(),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "",
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "117re",
                "label" => "EDIT request pph 25",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "117rrj",
                "label" => "REJECT request pph 25",
            ),
        ),
    ),
    //config pph ps 4(2) center
    "118" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "pph pasal 4(2)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "request ",
                "actionLabel" => "save",
                "source" => "",
                "target" => "118r",
                "userGroup" => "c_finance",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                //                "label" => "expense. authorization",
                "label" => "Authorization",
                "actionLabel" => "approve request",
                "source" => "118r",
                "target" => "118",
                "userGroup" => "c_finance_spv",
                "stateLabel" => "approve",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
        ),
        "template" => "template/transaksi_nopihak.html",
        "selectorModel" => "MdlPphPasal",
        "selectorSrcModel" => "MdlPphPasal",
        "selectedPrice" => array(
            "model" => "MdlHargaProduk",
            "label" => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "status=.1",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "objek pajak",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            //            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "nama",
            //            "satuan",
        ),
        "selectorProcessor" => "_processSelectBiaya/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlNota",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "select nota",
        "pihakFilters" => array(
            "jenis=.463o",
        ),
        "pihakProcessor" => "_processPihak/select",
        "pihakMainNota" => true,
        "shortHistoryFields" => array(
            "jenis_label" => "Activity",
            "dtime" => "Date",
            //            "customers_nama" => "GRN",
            "nomer_top" => "request number",
            "nomer" => "approval number",
            "oleh_nama" => "pic",
            "cash_account__label" => "sumber pembayaran",
            "harga" => "Amount",
            //            "ppn"      => "ppn",
            //            "nett"      => "netto",
        ),
        "selectorFields" => array("id", "nama"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "label" => "label",
            "reference" => "reference",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",
                //                "harga_source" =>"dpp",
                //                "reference" => "reference",
            ),
            2 => array(
                "nama" => "item name",
                "jml" => "qty",
                //                "harga_source" =>"dpp",
                //                                "reference" => "reference",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                //                "harga_source" =>"dpp",
                "harga" => "Price",
            ),
            2 => array(
                //                "harga_source" =>"dpp",
                "harga" => "Price",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "shoppingCartNoteEditabled" => array(
            2 => true,
            3 => true,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                //                "jml",
                //                "reference",
            ),
            2 => array(),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => false,
            3 => true,

        ),
        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "receiptElements" => array(
            "cash_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "cash account",
                "pairedModel" => array(
                    "mdlName" => "ComRekeningPembantuKas",
                    "mdlMethod" => "fetchBalances",
                    "mdlFilter" => array(
                        "cabang_id=placeID",
                    ),
                    "key" => "extern_id",
                    "rekening" => "kas",
                    "fieldID" => "debet",
                    "fieldLabel" => "saldo",
                ),
                "mdlName" => "MdlBankAccount_cash_and_in",
                "mdlFilter" => array(
                    "cabang_id=placeID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account",
                    "saldo" => "balance",
                ),
                "editPoints" => array(1,),
                "noValidate" => false,
                "labelValidate" => "Silahkan memilih sumber pembayaran sebelum melanjutkan transaksi.",
            ),
            "dummyElement" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "auto-validation",
                "mdlName" => "MdlDummyElement",
                //                "mdlFilter"   => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",

                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),
        "pairMakers" => array(
            1 => array(
                "saldoRekening" => array(
                    "helperName" => "he_cek_saldo_kas",
                    "functionName" => "cekStockSaldoKas",
                    "params" => array(
                        "cabang_id" => "placeID",
                    ),
                    "target" => array("main", "out_master"),
                ),

            ),
        ),
        "relativeElements" => array(),
        "relativeOptions" => array(),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "",
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "118re",
                "label" => "EDIT request pph pasal 4(2)",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "118rrj",
                "label" => "REJECT request pph pasal 4(2)",
            ),
        ),
    ),
    "116" => array(
        "icon" => "fa fa-opencart",
        "label" => "bukti bayar pph23",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "ENTRY E-FAKTUR",
                "actionLabel" => "PREPARED",
                "source" => "",
                "target" => "116r",
                "userGroup" => "sys",
                "stateLabel" => "create faktur pajak",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepared by",
            ),
            2 => array(
                "label" => "APPROVAL E-FAKTUR",
                "actionLabel" => "approval efaktur",
                "source" => "116r",
                "target" => "116",
                "userGroup" => "c_finance",
                "stateLabel" => "approval e-faktur",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
                //                "allowEdit" => true,
            ),


        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlProduk2",
        "selectorSrcModel" => "MdlProduk2",
        "selectedPrice" => array(
            "model" => "MdlHargaProduk",
            "label" => array("jual", "ppv", "disc", "disc_percent"),
            "key_label" => array(
                "jual" => "harga",
                "ppv" => "ppv",
                "disc" => "disc",
                "disc_percent" => "disc (%)",
            ),
            "mainSrc" => "jual",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            //            "cabang_id='1'", // mengambil dari $this->session->login(cabang_id) JANGAN LUPA DIGANTI YA..
            //            "jumlah>0",
            //            "state='active'",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            "produk_kode" => "kode",
            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            //            "nama", "satuan",// "jumlah"
            //            "keterangan", "kode", "satuan",// "jumlah"
            "nama",
            "kode",
            "satuan",// "jumlah"
        ),
        "selectorProcessor" => "_processSelectProduct/select",
        "itemSwapper" => "_processSelectProduct/multiSelect",
        "swappedKeys" => array("pihakID", "pihakName"),
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCustomer_and_pre",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nomer_top" => "SO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            //            "transaksi_nilai" => "amount",
            // "jual" => "amount",
            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett2" => "total amount",

        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nomer_top" => "SO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett2" => "total amount",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        "historyFields" => array(
            1 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "SO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                "nett1" => "netto",
                "ongkir" => "shipping service",
                // "ongkir" => "shiping",
                // "ppn" => "ppn",
                "grand_ppn" => "ppn",
                // "nett2" => "total amount",
                "new_net3" => "total amount",
                "print_label" => "tool",
            ),
            2 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "SO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                "nett1" => "netto",
                // "ppn" => "ppn",
                // "nett2" => "total amount",

                "ongkir" => "shipping service",
                "grand_ppn" => "ppn",
                "new_net3" => "total amount",

                "print_label" => "tool",
            ),
        ),

        "extHistoryFields" => array(
            1 => array(
                "review_details" => "id",
                "print_label" => "nomer",
            ),
            2 => array(
                "review_details" => "id",
                "print_label" => "nomer",
            ),

        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "produk name",
                "produk_kode" => "part number",
                "stok" => "stok",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "nama" => "produk name",
                "produk_kode" => "part number",
                "stok" => "stok",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            3 => array(
                "nama" => "produk name",
                "produk_kode" => "part number",
                //                "stok" => "stok",
                "jml" => "qty",
                "satuan" => "uom",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "label" => "label",
            "satuan" => "satuan",
            "ppn" => "harga*(10/100)",
            "berat_gross" => "berat_gross",
            "lebar_gross" => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross" => "tinggi_gross",
            "volume_gross" => "volume_gross",

            "volume" => "volume",
            "berat" => "berat",
            "lebar" => "lebar",
            "tinggi" => "tinggi",
            "panjang" => "panjang",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "nett1" => "price",
            ),
            2 => array(
                "nett1" => "price",
            ),
            3 => array(
                "nett1" => "price",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "jml",
                "produk_ord_jml",
                "disc_percent",
                "disc",
            ),
            2 => array(
                //                "jml",
                //                "produk_ord_jml",
                //                "disc_percent",
                //                "disc",
            ),
        ),
        "shoppingCartUnionSelectors" => array(
            1 => array(
                "base" => "disc_percent",
                "members" => array(
                    "disc_percent",
                    "disc",
                ),
            ),
        ),
        "shoppingCartKeyUpEvents" => array(
            1 => array(
                "disc_percent" => "document.getElementById('{disc}').value=((parseFloat(removeCommas(this.value))*parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))/100)",
                "disc" => "document.getElementById('{disc_percent}').value=((parseFloat(removeCommas(this.value))/parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))*100)",
            ),
        ),
        "shoppingCartFieldValidators" => array(
            //            "jml" => "quantity",
            //            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "customer ID",
            "pihakName" => "customer name",

        ),
        "shoppingCartRowOptionalValidators" => array(
            "shippingService" => array(
                "ongkir_ppn_by_cust" => array(
                    "shipping_service" => "ongkir",
                ),
                "ongkir_tanpa_ppn_by_cust" => array(
                    "shipping_service" => "ongkir",
                ),
                //                "ongkir_tanpa_ppn_by_company" => array(
                //                    "shipping_service" => "ongkir",
                //
                //                ),
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*(harga-disc+ppn)",//nett2
            2 => "jml*(harga-disc+ppn)",
            3 => "jml*(harga-disc+ppn)",
        ),
        "shoppingCartHideSubamount" => array(
            1 => false,
            2 => false,
            3 => false,
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "shipping_service" => "Shipping Service",
                "grand_total_ui" => "Total Amount",
                "grand_ppn" => "VAT",
                //                "tagihan_ui" => "Grand Total",
                "nilai_pembulatan" => "Pembulatan",
                "tagihan" => "Grand Total",
            ),
            2 => array(
                "shipping_service" => "Shipping Service",
                "grand_total_ui" => "Amount",
                "nilai_pembulatan" => "Pembulatan",
                "nett1_bulat" => "Total Amount",

                "grand_ppn" => "VAT",
                "pph_23" => "pph 23",
                "grand_pembulatan" => "Grand Total",
            ),
            3 => array(
                "shipping_service" => "Shipping Service",
                "grand_total_ui" => "Amount",
                "nilai_pembulatan" => "Pembulatan",
                "nett1_bulat" => "Total Amount",
                "grand_ppn" => "VAT",
                "grand_pembulatan" => "Grand Total",
            ),
        ),
        "receiptElements" => array(
            "discountMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "discount method",
                "mdlName" => "MdlDiskonMethod",
                "key" => "id",
                "defaultValue" => "item",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "method",
                ),
                "editPoints" => array(1,),
                "targetMethod" => array(
                    "item" => "ReComDiscItem",
                    "customer" => "ReComDiscCustomer",
                ),
            ),
            "shippingService" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "shipping service",
                "mdlName" => "MdlOngkir",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "name",
                "description" => "",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1),
            ),
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "CUSTOMER DETAILS",
                "mdlName" => "MdlCustomer_and_pre",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "Name",
                    "alamat_1" => "Addr",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Phone",
                    "tlp_1" => "Phone",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    "nik" => "NIK",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
            "billingDetails" => array(
                "elementType" => "dataModel",
                "elementJoint" => array(
                    "method" => "lookUpJointCustomer",
                    "mdlFilter" => "id=pihakID",
                    "usedFields" => array(
                        "no_ktp" => "nik",
                        "nik" => "nik",
                        "npwp" => "npwp",
                    ),
                ),
                "inputType" => "radio",
                "label" => "BILLING DETAILS",
                "mdlName" => "MdlCustomerBillAddress",
                "mdlFilter" => array("extern_id=pihakID"),
                //                "mdlName" => "MdlCustomer_and_pre",
                //                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                //                "labelSrc" => "alias",
                "labelSrc" => "alias",
                "usedFields" => array(
                    //                    "alias" => "Name",
                    "alias" => "Name",
                    "alamat" => "Addr",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Phone",
                    "tlp_1" => "Phone",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "NIK",
                ),
                "editPoints" => array("5"),
            ),
            "deliveryDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "DELIVERY DETAILS",
                "mdlName" => "MdlCustomerAddress",
                "mdlFilter" => array("extern_id=pihakID"),
                "key" => "id",
                "labelSrc" => "alias",
                "usedFields" => array(
                    "alias" => "Attn",
                    "alamat" => "Address",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "propinsi",
                    "tlp" => "Phone",
                    "tlp_2" => "Handphone",
                    //                    "npwp" => "NPWP",
                    //                    "propinsi" =>"",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
            "detilSize" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Data ukuran",
                "mdlName" => "MdlMeasurement",
                "mdlFilter" => array("extern_id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "defaultValue" => "ckd",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            "tos" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "term of shipment",
                "mdlName" => "MdlTos",
                "mdlFilter" => array(),
                "key" => "id",
                "defaultValue" => "20",
                "labelSrc" => "nama",
                "description" => "",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
            "shippingDate" => array(
                "elementType" => "dataField",
                // "inputType" => "combo",
                "label" => "shipping date",
                "inputType" => "date",
                "defaultValue" => date("Y-m-d"),
                //                "editPoints" => array(1),
                "editPoints" => array(1, 2, 3, 4, 5),
            ),
            "capacity" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "capacity",
                "mdlName" => "MdlCapacity",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
            "dueDate" => array(
                "elementType" => "dataField",
                "label" => "due date",
                "inputType" => "date",
                "defaultValue" => $date->format('Y-m-d'),
                "editPoints" => array(1, 2, 3, 4, 5),
            ),
            "paymentMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "payment method",
                "mdlName" => "MdlPaymentMethod",
                "key" => "id",
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
                //                "mdlFilter"   => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",

                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),
        "relativeElements" => array(),

        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "pairMakers" => array(
            1 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
            ),
            3 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
            ),
        ),
        "pairInjectors" => array(
            1 => array(
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                    "out_detail" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
            ),
            3 => array(
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                    "out_detail" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
            ),
        ),
        "validationRules" => array(),
        "connectedDiscount" => array(
            "enabled" => true,
            "mdlNameRelation" => "MdlConnectedDiscount",
            "mdlNameSource" => "MdlAddDiscount",
            //            "jenis" => "produk",
            //            "jenis_locker" => "stock",
        ),
        "additionalRows" => array(
            "shippingService" => array(
                "ongkir_ppn_by_cust" => array(
                    "shipping_service" => array(
                        "label" => "shipping service",
                        "defaultValue" => "",
                        "maxValue" => "",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
                        'disabled' => "",
                        "addPoints" => array(1),
                        "editPoints" => array(4),
                    ),
                ),
                "ongkir_tanpa_ppn_by_cust" => array(
                    "shipping_service" => array(
                        "label" => "shipping service",
                        "defaultValue" => "",
                        "maxValue" => "",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
                        'disabled' => "",
                        "addPoints" => array(1, 4),
                        "editPoints" => array(4),
                    ),
                ),
                //                "ongkir_tanpa_ppn_by_company" =>array(
                //                    "shipping_service" => array(
                //                        "label" => "shipping service",
                //                        "defaultValue" => "",
                //                        "maxValue" => "",
                //                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
                //                        'disabled' => "",
                //                        "addPoints" => array(1,),
                //                    ),
                //
                //                ),
            ),
        ),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "settlementHistoryFields" => array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "customers_nama" => "customer",
            "jenis_label" => "activity",
            //            "transaksi_nilai" => "orig. value",
            //            "add_disc" => "discount",
            //            "grand_total" => "nett",
            "harga" => "orig. value",
            "disc" => "discount",
            "nett1" => "nett",
            "ppn" => "ppn",
            "nett2" => "total",
        ),
        "allowedMainEdit" => array("1"),
        "addMainSource" => array(
            1 => array(
                "fields" => array(
                    "nomer" => "INV",
                    "grand_total_ui" => "DPP",
                    "new_grand_ppn" => "PPN",
                    "dateFaktur" => "Tgl faktur ",
                    "eFaktur" => "nomer faktur",
                    "nilai_faktur" => "nilai(RP)",
                ),
                "editableFields" => array(
                    //                    "eFaktur" => "",
                    //                    "dateFaktur" => "",
                ),
            ),
            2 => array(
                "fields" => array(
                    "efaktur_source" => "INV",
                    "nett1_bulat" => "DPP",
                    "pph_23" => "pph23",
                    "dateFaktur" => "Tgl faktur ",
                    "eFaktur" => "nomer faktur",
                    //                    "nilai_faktur" => "nilai(RP)",
                ),
                "editableFields" => array(
                    "eFaktur" => "text",
                    "dateFaktur" => "date",
                    //                    "nilai_faktur" => "",
                ),
            ),
            3 => array(
                "fields" => array(
                    "efaktur_source" => "INV",
                    "nett1_bulat" => "DPP",
                    "grand_ppn" => "PPN",
                    "dateFaktur" => "Tgl faktur ",
                    "eFaktur" => "nomer faktur",
                ),
                "editableFields" => array(
                    //                    "eFaktur" => "",
                    //                    "dateFaktur" => "",
                ),
            ),
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "116re",
                "label" => "EDIT bukti bayar pph23",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "116rrj",
                "label" => "REJECT bukti bayar pph23",
            ),
        ),
    ),
    //--------- ke atas sudah modul ---------------------------

);