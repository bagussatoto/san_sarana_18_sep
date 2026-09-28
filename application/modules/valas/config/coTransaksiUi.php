<?php
//region urusan tanggal-menanggal
date_default_timezone_set('asia/jakarta');
$date = new DateTime(date("Y-m-d")); // Y-m-d
$date->add(new DateInterval('P30D'));
//$date->format('Y-m-d') . "\n";
//endregion


$config["coTransaksiUi"] = array(
    //penjualan valas
    "383" => array(
        "icon" => "fa fa-opencart",
        "label" => "valas exchange",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "money exchange",
                "actionLabel" => "make exchange",
                "source" => "",
                "target" => "383",
                "userGroup" => "o_export",
                "stateLabel" => "complete",
                "stateColor" => "#dd3300",
                "stateCaption" => "complated by",
            ),
            //            2 => array(
            //                "label" => "valas exchange",
            //                "actionLabel" => "approve order",
            //                "source" => "383",
            //                "target" => "382so",
            //                "userGroup" => "c_export_spv",
            //                "stateLabel" => "ordered",
            //                //				"optTarget"        => "582spod", // sales pre-order diskon, ada DP/Cash in Advance (ke penerimaan uang)
            //                //				"optCriteriaField" => "total_diskon", // cek diskon bila lebih dari ketentuan maka 582spod, ada DP/Cash in Advance (ke penerimaan uang)
            //                //				"optStateLabel"    => "pending disc. approval",
            //                "stateColor" => "#ff7700",
            //                "stateCaption" => "approved by",
            //                "allowEdit" => true,
            //            ),
            //
            //            3 => array(
            //                "label" => "pre packing",
            //                "actionLabel" => "process packing",
            //                "source" => "382so",
            //                "target" => "382pkd", // packed
            //                "userGroup" => "c_export_spv",
            //                "stateLabel" => "packed",
            //                "stateColor" => "#009900",
            //                "stateCaption" => "packed by",
            //                "allowEdit" => true,
            //            ),
            //            4 => array(
            //                "label" => "shipment",
            //                "actionLabel" => "process shipment",
            //                "source" => "382pkd",
            //                "target" => "382spd", // shipped
            //                "userGroup" => "c_export_spv",
            //                "stateLabel" => "shipped",
            //                "stateColor" => "#009900",
            //                "stateCaption" => "shipped by",
            //
            //            ),
            //            5 => array(
            //                "label" => "invoicing",
            //                "actionLabel" => "create invoice",
            //                "source" => "382spd",
            //                "target" => "382", // invoice
            //                "userGroup" => "c_export_spv",
            //                "stateLabel" => "completed",
            //                "stateColor" => "#009900",
            //                "stateCaption" => "completed by",
            //                "allowJoin" => true,
            //            ),
        ),
        "template" => "template/transaksi_nopihak.html",
        //        "template" => "template/transaksi.html",
        "selectorModel" => "MdlValasSaldo",
        "selectorSrcModel" => "MdlCurrency",
        "selectedPrice" => array(),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "bank.cabang_id=placeID",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "extern_id",
            "nama" => "nama",
            "valas" => "extern_nama",
            "valas_value" => "qty_debet",
            "bank_id" => "id",
            //            "satuan" => "satuan",
            //            "jumlah" => "jumlah",
        ),
        "selectorViewedFields" => array(
            "nama",
            "extern_nama",
            "qty_debet",
            //            "satuan",
            //            "jumlah",
        ),
        "selectorView" => "_selectorValas",
        "selectorProcessor" => "_processSelectValas/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlBankAccount_in",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "Bank",
        "pihakFilters" => array(
            "bank.id=bank_id",
            "cabang_id=placeID",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "pihakProcessor" => "_processPihak/select",

        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),

        "shoppingCartFields" => array(
            1 => array(
                "nama" => "valas",
                "jml" => "nilai valas",
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
            4 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            5 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "kode" => "kode",
            "label" => "label",
            "satuan" => "satuan",
            "ppn" => "harga*(10/100)",


            //            "berat_gross" => "berat_gross",
            //            "lebar_gross" => "lebar_gross",
            //            "panjang_gross" => "panjang_gross",
            //            "tinggi_gross" => "tinggi_gross",
            //            "volume_gross"  => "volume_gross",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "price",
                //                "grand_total" => "sub-total"

            ),
            //            2 => array(
            //                "stok" => "stok",
            //                "valas_nilai" => "price",
            //                "sub_nett2_valas" => "sub-total"
            //            ),
            //
            //            3 => array(
            ////                "stok" => "stok",
            ////                "harga" => "price",
            ////                "ppn"   => "VAT",
            //            ),
            //            4 => array(
            ////                "harga" => "price",
            ////                "ppn"   => "VAT",
            //            ),
            //            5 => array(
            //                "valas_nilai" => "price",
            //                "disc" => "disc",
            //                "sub_nett2_valas" => "sub-total"
            //
            //            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "jml",
                "produk_ord_jml",
                "harga",
            ),
            2 => array(
                "jml",
                "produk_ord_jml",
            ),

            3 => array(
                "jml",
                "produk_ord_jml",
            ),
            4 => array(
                "jml",
                "produk_ord_jml",
            ),
            5 => array(
                "jml",
                "produk_ord_jml",
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "price",
            //            "valas_nilai" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "customer ID",
            "pihakName" => "customer name",
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            //            2 => "jml*(harga+ppn)",
            //            3 => "jml",
            //            4 => "jml",
            //            5 => "jml*(harga+ppn)",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
        ),
        "receiptElements" => array(
            "paymentMethod_cash" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Moved to account",
                "mdlName" => "MdlBankAccount_in",
                "mdlFilter" => array(
                    "bank.cabang_id=placeID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account",
                    "currency" => "currency",
                ),
                "editPoints" => array(1,),
            ),

        ),
        "relativeElements" => array(
            //            "paymentMethod" => array(
            //                "cash" => array(
            //                    "cash_account" => array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "combo",
            //                        "label" => "bank account",
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
            //                        "inputType" => "combo",
            //                        "label" => "bank account",
            //                        "mdlName" => "MdlBankAccount",
            //                        "key" => "id",
            //                        "labelSrc" => "nama",
            //                        "usedFields" => array(
            //                            "nama" => "",
            //                        ),
            //                        "editPoints" => array(1,),
            //                    ),
            //                ),
            //                "credit" => array(
            //                    "top" => array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "combo",
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
            //                    ),
            //                ),
            //                "debit_card" => array(
            //                    "debit_account" => array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "combo",
            //                        "label" => "debit account",
            //                        "mdlName" => "MdlBankAccount",
            //                        "key" => "id",
            //                        "labelSrc" => "name",
            //                        "usedFields" => array(
            //                            "name" => "",
            //                        ),
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "cash_account" => array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "combo",
            //                        "label" => "bank account",
            //                        "mdlName" => "MdlBankAccount",
            //                        "key" => "id",
            //                        "labelSrc" => "nama",
            //                        "usedFields" => array(
            //                            "nama" => "",
            //                        ),
            //                        "editPoints" => array(1,),
            //                    ),
            //                ),
            //
            //                "credit_card" => array(
            //                    "credit_account" => array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "combo",
            //                        "label" => "credit account",
            //                        "mdlName" => "MdlCreditCard",
            //                        "key" => "id",
            //                        "labelSrc" => "name",
            //                        "usedFields" => array(
            //                            "name" => "",
            //                        ),
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "cash_account" => array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "combo",
            //                        "label" => "bank account",
            //                        "mdlName" => "MdlBankAccount",
            //                        "key" => "id",
            //                        "labelSrc" => "nama",
            //                        "usedFields" => array(
            //                            "nama" => "",
            //                        ),
            //                        "editPoints" => array(1,),
            //                    ),
            //                ),
            //            ),
            //            "credit_account" => array(
            //                "visa_card" => array(
            //                    "card_number" => array(
            //                        "elementType" => "dataField",
            //                        "inputType" => "combo",
            //                        "label" => "card number",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "valid_period" => array(
            //                        "elementType" => "dataField",
            //                        "inputType" => "combo",
            //                        "label" => "valid trough",
            //                        "inputType" => "date",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "card_name" => array(
            //                        "elementType" => "dataField",
            //                        "inputType" => "combo",
            //                        "label" => "name on card",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //
            //
            //                ),
            //                "master_card" => array(
            //                    "card_number" => array(
            //                        "elementType" => "dataField",
            //                        "inputType" => "combo",
            //                        "label" => "card number",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "valid_period" => array(
            //                        "elementType" => "dataField",
            //                        "inputType" => "combo",
            //                        "label" => "valid trough",
            //                        "inputType" => "date",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "card_name" => array(
            //                        "elementType" => "dataField",
            //                        "inputType" => "combo",
            //                        "label" => "name on card",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                ),
            //
            //            ),

        ),
        "relativeOptions" => array(
            //            "paymentMethod" => array(
            //                "credit" => array(
            //                    "discount" => array(
            //                        "label" => "open discount",
            //                        "defaultValue" => ".0",
            //                        "maxValue" => "nett2*50/100",
            //                        "auth" => array(
            //                            "groupID" => "c_holding"
            //                        ),
            //                        "addPoints" => array(1, 2),
            //                    ),
            //                    "dp" => array(
            //                        "label" => "down payment",
            //                        "defaultValue" => ".0",
            //                        "maxValue" => "nett2*50/100",
            //                        "auth" => array(
            //                            "groupID" => "c_finance"
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
            //                            "groupID" => "c_holding"
            //                        ),
            //                        "addPoints" => array(2),
            //                    ),
            //
            //                ),
            //                "cia" => array(
            //                    "nilai_cia" => array(
            //                        "label" => "cash amount",
            //                        "defaultValue" => "nett2",
            //                        "minValue" => "nett2",
            //                        "maxValue" => "nett2",
            //                        "auth" => array(
            //                            "groupID" => "c_finance"
            //                        ),
            //                        "addPoints" => array(1,),
            //                    ),
            //                    "discount" => array(
            //                        "label" => "open discount",
            //                        "defaultValue" => ".0",
            //                        "maxValue" => "nett2*50/100",
            //                        "auth" => array(
            //                            "groupID" => "c_holding"
            //                        ),
            //                        "addPoints" => array(1, 2),
            //                    ),
            //
            //                ),
            //
            //            ),
        ),
        "requestCode" => array(
            "masterCode" => "382",
            "stateCode" => "382r",
            "stepNumber" => "1",
            "allowMultiSelect" => false,
        ),

        "pairMakers" => array(
            1 => array(
                "stokValas" => array(
                    "helperName" => "he_cek_stock_valas",
                    "functionName" => "cekStockValas",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
            ),
        ),
        "pairInjectors" => array(
            2 => array(
                "stokValas" => array(
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
        "validationRules" => array(
            "items" => array(
                "target" => "stok",
                "source" => "jml",
            ),
        ),
        "connectedDiscount" => array(),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "383e",
                "label" => "EDIT money exchange",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "383rj",
                "label" => "REJECT money exchange",
            ),
        ),
    ),
    //  purchasing valas
    "384" => array(
        "icon" => "fa fa-opencart",
        "label" => "purchasing valas",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "purchasing valas",
                "actionLabel" => "purchasing valas",
                "source" => "",
                "target" => "384r",
                "userGroup" => "c_finance",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "purchasing valas authorization",
                "actionLabel" => "approve & receive",
                "source" => "384r",
                "target" => "384",
                "userGroup" => "c_finance",
                "stateLabel" => "complete",
                "stateColor" => "#dd3300",
                "stateCaption" => "completed by",
            ),
        ),
        "template" => "template/transaksi_nopihak.html",
        "selectorModel" => "MdlCurrency",
        "selectorSrcModel" => "MdlCurrency",
        "selectedPrice" => array(),
        "lockerCheck" => array(),
        "selectorFilters" => array(//            "bank.cabang_id=placeID",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            //            "valas" => "extern_nama",
            //            "valas_value" => "qty_debet",
            //            "bank_id" => "id",
            //            "satuan" => "satuan",
            //            "jumlah" => "jumlah",
        ),
        "selectorViewedFields" => array(
            "nama",
            //            "extern_nama",
            //            "qty_debet",
        ),
        "selectorView" => "_selectorValas",
        "selectorProcessor" => "_processSelectValas/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlBankAccount_cash_and_in",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "Bank",
        "pihakFilters" => array(
            //            "bank.id=bank_id",
            //            "cabang_id=placeID",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "pihakProcessor" => "_processPihak/select",

        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            //            "biaya_bank" => "biaya bank",
            //            "netto" => "netto",
        ),
        "historyFields" => array(
            1 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "nomer" => "request number",
                "oleh_nama" => "person",
                "harga" => "amount",
                //            "biaya_bank" => "biaya bank",
                //            "netto" => "netto",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "nomer_top" => "request number",
                "nomer" => "approval number",
                "oleh_nama" => "person",
                "harga" => "amount",
                //            "biaya_bank" => "biaya bank",
                //            "netto" => "netto",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "biaya_bank" => "biaya bank",
            "netto" => "netto",
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

        "shoppingCartFields" => array(
            1 => array(
                "nama" => "valas",
                //                "stok" => "available valas",
                "harga" => "kurs",
                "jml" => "qty valas",
                //                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "valas",
                //                "stok" => "available valas",
                "harga" => "kurs",
                "jml" => "qty valas",
                //                "satuan" => "satuan",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "kode" => "kode",
            "label" => "label",
            "satuan" => "satuan",
            //            "ppn" => "harga*(10/100)",
            "harga" => "kurs",

        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "price",
                //                "grand_total" => "sub-total"

            ),
            2 => array(
                "harga" => "price",
                //                "grand_total" => "sub-total"

            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "jml",
                "produk_ord_jml",
                "produk_ord_hrg",
                "harga",
            ),
            2 => array(
                "jml",
                "produk_ord_jml",
                "produk_ord_hrg",
                "harga",
            ),

        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "Total",
            ),
            2 => array(
                "harga" => "Total",
            ),

        ),
        "shoppingCartHideSubamount" => array(
            1 => false,
            2 => false,
        ),
        "receiptElements" => array(
            "cash_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "cash account",
                "pairedModel" => array(
                    "mdlName" => "ComLockerValue",
                    "mdlMethod" => "fetchBalances",
                    "mdlFilter" => array(
                        "cabang_id" => "placeID",
                        "state" => ".active",
                    ),
                    "key" => "produk_id",
                    //                    "rekening" => "kas",// kolom jenis di locker
                    "rekening" => array(
                        "kas", "plafon hutang bank",
                    ),
                    "fieldID" => "nilai",
                    "fieldLabel" => "saldo",
                ),
                "mdlName" => "MdlBankAccount_cash_and_in",
                "mdlFilter" => array(
                    "cabang_id=placeID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account number",
                    "alias" => "holder alias",
                    "saldo" => "balance",
                    "folders" => "acountMasterID",
                    "folders_nama" => "accountMaster",
                ),
                "editPoints" => array(),
                //                "pairMethod" => array(
                //                    "recom" => "ReComCashMethode",
                //                    "calculate" => array(
                //                        "source" => "cash_account",
                //                        "prefix" => "cashMethode",
                //                        "target" => "",
                //                    ),
                //                ),
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
            //            "paymentMethod" => array(
            //                "cash" => array(
            //                    "cash_account" => array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "combo",
            //                        "label" => "bank account",
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
            //                        "inputType" => "combo",
            //                        "label" => "bank account",
            //                        "mdlName" => "MdlBankAccount",
            //                        "key" => "id",
            //                        "labelSrc" => "nama",
            //                        "usedFields" => array(
            //                            "nama" => "",
            //                        ),
            //                        "editPoints" => array(1,),
            //                    ),
            //                ),
            //                "credit" => array(
            //                    "top" => array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "combo",
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
            //                    ),
            //                ),
            //                "debit_card" => array(
            //                    "debit_account" => array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "combo",
            //                        "label" => "debit account",
            //                        "mdlName" => "MdlBankAccount",
            //                        "key" => "id",
            //                        "labelSrc" => "name",
            //                        "usedFields" => array(
            //                            "name" => "",
            //                        ),
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "cash_account" => array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "combo",
            //                        "label" => "bank account",
            //                        "mdlName" => "MdlBankAccount",
            //                        "key" => "id",
            //                        "labelSrc" => "nama",
            //                        "usedFields" => array(
            //                            "nama" => "",
            //                        ),
            //                        "editPoints" => array(1,),
            //                    ),
            //                ),
            //
            //                "credit_card" => array(
            //                    "credit_account" => array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "combo",
            //                        "label" => "credit account",
            //                        "mdlName" => "MdlCreditCard",
            //                        "key" => "id",
            //                        "labelSrc" => "name",
            //                        "usedFields" => array(
            //                            "name" => "",
            //                        ),
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "cash_account" => array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "combo",
            //                        "label" => "bank account",
            //                        "mdlName" => "MdlBankAccount",
            //                        "key" => "id",
            //                        "labelSrc" => "nama",
            //                        "usedFields" => array(
            //                            "nama" => "",
            //                        ),
            //                        "editPoints" => array(1,),
            //                    ),
            //                ),
            //            ),
            //            "credit_account" => array(
            //                "visa_card" => array(
            //                    "card_number" => array(
            //                        "elementType" => "dataField",
            //                        "inputType" => "combo",
            //                        "label" => "card number",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "valid_period" => array(
            //                        "elementType" => "dataField",
            //                        "inputType" => "combo",
            //                        "label" => "valid trough",
            //                        "inputType" => "date",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "card_name" => array(
            //                        "elementType" => "dataField",
            //                        "inputType" => "combo",
            //                        "label" => "name on card",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //
            //
            //                ),
            //                "master_card" => array(
            //                    "card_number" => array(
            //                        "elementType" => "dataField",
            //                        "inputType" => "combo",
            //                        "label" => "card number",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "valid_period" => array(
            //                        "elementType" => "dataField",
            //                        "inputType" => "combo",
            //                        "label" => "valid trough",
            //                        "inputType" => "date",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "card_name" => array(
            //                        "elementType" => "dataField",
            //                        "inputType" => "combo",
            //                        "label" => "name on card",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                ),
            //
            //            ),

        ),
        "relativeOptions" => array(
            //            "paymentMethod" => array(
            //                "credit" => array(
            //                    "discount" => array(
            //                        "label" => "open discount",
            //                        "defaultValue" => ".0",
            //                        "maxValue" => "nett2*50/100",
            //                        "auth" => array(
            //                            "groupID" => "c_holding"
            //                        ),
            //                        "addPoints" => array(1, 2),
            //                    ),
            //                    "dp" => array(
            //                        "label" => "down payment",
            //                        "defaultValue" => ".0",
            //                        "maxValue" => "nett2*50/100",
            //                        "auth" => array(
            //                            "groupID" => "c_finance"
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
            //                            "groupID" => "c_holding"
            //                        ),
            //                        "addPoints" => array(2),
            //                    ),
            //
            //                ),
            //                "cia" => array(
            //                    "nilai_cia" => array(
            //                        "label" => "cash amount",
            //                        "defaultValue" => "nett2",
            //                        "minValue" => "nett2",
            //                        "maxValue" => "nett2",
            //                        "auth" => array(
            //                            "groupID" => "c_finance"
            //                        ),
            //                        "addPoints" => array(1,),
            //                    ),
            //                    "discount" => array(
            //                        "label" => "open discount",
            //                        "defaultValue" => ".0",
            //                        "maxValue" => "nett2*50/100",
            //                        "auth" => array(
            //                            "groupID" => "c_holding"
            //                        ),
            //                        "addPoints" => array(1, 2),
            //                    ),
            //
            //                ),
            //
            //            ),
        ),
        "additionalRows" => array(
            //            "dummyElement" => array(
            //                "yes" => array(
            //                    "biaya_bank" => array(
            //                        "label" => "Biaya Bank",
            //                        "defaultValue" => "0",
            //                        "keyupAction" => "
            //    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harga').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harga').value;}
            //                            ",
            ////                        'disabled' => "disabled",
            //                        "addPoints" => array(1),
            //                    ),
            //                    "netto" => array(
            //                        "label" => "Netto",
            //                        "defaultValue" => "netto",
            //                        "maxValue" => "netto",
            //                        "minValue" => "netto",
            //                        "keyPressAction" => "",
            //                        'disabled' => "disabled",
            //                        "addPoints" => array(1),
            //                    ),
            //
            //
            //                ),
            //            ),
        ),
        "pairRegistries" => array(
            "main",
        ),
        "pairMakers" => array(
            1 => array(
                "stokValas" => array(
                    "helperName" => "he_cek_stock_valas",
                    "functionName" => "cekStockValas",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
                "kurs" => array(
                    "helperName" => "he_cek_kurs_valas",
                    "functionName" => "cekKursValas",
                    "params" => array(//                        "id" => "id",
                    ),
                ),
            ),
        ),
        "pairInjectors" => array(
            1 => array(
                "stokValas" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                //                "kurs" => array(
                //                    "items" => array(
                //                        "targetKey" => "id",
                //                        "targetColumn" => "harga",
                //                    ),
                //                ),
            ),
        ),
        "validationRules" => array(
            //            "items" => array(
            //                "target" => "stok",
            //                "source" => "jml",
            //            ),
        ),
        "connectedDiscount" => array(),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "384re",
                "label" => "EDIT purchasing valas",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "384rrj",
                "label" => "REJECT purchasing valas",
            ),
        ),
    ),
    //  config penyetoran mata uang asing
    "1759" => array(
        "icon" => "fa fa-money",
        "label" => "Penyetoran (Mata Uang Asing)",
        "place" => "branch",
        "paymentConfig" => true,
        "steps" => array(
            1 => array(
                "label" => "setoran mata uang asing",
                "actionLabel" => "penyetoran",
                "source" => "",
                "target" => "1759r",
                "userGroup" => "o_finance",
                "stateLabel" => "prepare by",
                "stateColor" => "#dd3300",
            ),
        ),
        "template" => "template/transaksi_payment.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.1749",
            "transaksi_nilai_sisa>.0",
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
        "pihakModel" => "MdlCustomer",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "nilai_bayar" => "amount",
            "cash_account_source__label" => "bank account source",
            "cash_account_target__label" => "bank account target",
        ),
        "shortStepHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "sender",
            "cabang_nama" => "recipient",
            "759r" => "request number",
            //            "759" => "approval number",
            //            "758r" => "request number",
            "758" => "receipt number",

            "oleh_nama" => "person",
            "nilai_bayar" => "amount",
            "cash_account_source__label" => "bank account source",
            "cash_account_target__label" => "bank account target",
            "next_pic" => "next step otorisator",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "sender",
            "cabang_nama" => "recipient",
            "1759r" => "request number",
            //            "759" => "approval number",
            //            "758r" => "request number",
            "1758" => "receipt number",

            "oleh_nama" => "person",
            "nilai_bayar" => "amount",
            "cash_account_source__label" => "bank account source",
            "cash_account_target__label" => "bank account target",
            //            "next_pic" => "next step otorisator",
        ),

        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "nilai_bayar" => "amount",
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
                "valas_nama" => "currency",
            ),

        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nomer",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            "tagihan_valas" => "tagihan_valas",
            "terbayar_valas" => "terbayar_valas",
            "sisa_valas" => "sisa_valas",

        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "sisa_valas" => "sisa",
            ),
        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            1 => "sisa_valas",
        ),
        //        "shoppingCartSumFields" => array(
        //            1 => array(
        //                                "sisa" => "debt amount",
        //                                "creditAmount" => "paid using credit",
        //                                "nilai_entry" => "paid using cash account",
        //                                "nilai_bayar" => "total amount of payment",
        //                                "new_sisa" => "remain debt (from list)",
        //            ),
        //        ),
        "shoppingCartAvoidRemove" => true,
        "tagihanSrc" => "harus_bayar",
        "receiptElements" => array(
            "centerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "center details",
                "mdlName" => "MdlCabang",
                "mdlFilter" => array("id<0"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "name",
                ),
                "editPoints" => array(1,),
            ),
            "gudang2ID" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "stock warehouse",
                "mdlName" => "MdlGudangDefault_center",
                //                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            //            "cash_account_source" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "branch cash account",
            //                "pairedModel" => array(
            //                    "mdlName" => "ComLockerValue",
            //                    "mdlMethod" => "fetchBalances",
            //                    "mdlFilter" => array(
            //                        "cabang_id" => "placeID",
            //                        "state" => ".active",
            //                    ),
            //                    "key" => "produk_id",
            //                    "rekening" => "kas_valas",
            //                    "fieldID" => "nilai",
            //                    "fieldLabel" => "saldo",
            //                ),
            //                "mdlName" => "MdlBankAccount_in", // MdlBankAccount_out
            //                "mdlFilter" => array(
            //                    "cabang_id=placeID",
            //                    "currency_id=valasDetails",
            //                ),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "usedFields" => array(
            //                    "nama" => "account",
            //                    "saldo" => "balance",
            //                ),
            //                "editPoints" => array(1,),
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
        "relativeElements" => array(
            "centerDetails" => array(
                "-1" => array(
                    "cash_account_target" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "center cash account",
                        "mdlName" => "MdlBankAccount_in",
                        "mdlFilter" => array(
                            "cabang_id=centerDetails",
                            "currency_id=valasDetails",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "account",
                            "folders" => "acountMasterID",
                            "folders_nama" => "accountMaster",
                        ),
                        "editPoints" => array(1,),
                        "pairMethod" => array(
                            "recom" => "ReComCashMethode",
                            "calculate" => array(
                                "source" => "cash_account",
                                "prefix" => "cashMethode",
                                "target" => "",
                            ),
                        ),
                    ),
                ),
            ),

            //            "cashMethode" => array(
            //                "reguler" => array(
            //                    "cash_account_target" => array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "radio",
            //                        "label" => "center cash account",
            //                        "mdlName" => "MdlBankAccount_in", // MdlBankAccount_in
            //                        "mdlFilter" => array(
            //                            "cabang_id=centerDetails",
            //                        ),
            //                        "key" => "id",
            //                        "labelSrc" => "nama",
            //                        "usedFields" => array(
            //                            "nama" => "account",
            //                        ),
            //                        "editPoints" => array(1,),
            //                    ),
            //                ),
            //                "rekening_koran" => array(
            //                    "cash_account_target" => array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "radio",
            //                        "label" => "rekening koran",
            ////                        "pairedModel" => array(
            ////                            "mdlName" => "ComLockerValue",
            ////                            "mdlMethod" => "fetchBalances",
            ////                            "mdlFilter" => array(
            ////                                "cabang_id" => "placeID",
            ////                                "state" => ".active",
            ////                            ),
            ////                            "key" => "produk_id",
            ////                            "rekening" => "hutang bank",
            ////                            "fieldID" => "nilai",
            ////                            "fieldLabel" => "saldo",
            ////                        ),
            //                        "mdlName" => "MdlRekeningKoran",
            //                        "mdlFilter" => array(
            //                            "cabang_id=centerDetails",
            ////                     "id=pihakRelId",
            //                        ),
            //                        "key" => "id",
            //                        "labelSrc" => "nama",
            //                        "usedFields" => array(
            //                            "nama" => "account",
            ////                            "saldo" => "balance",
            //                            "folders" => "acountMasterID",
            //                            "folders_nama" => "accountMaster",
            //                        ),
            //                        "editPoints" => array(1),
            //                        "noValidate" => true,
            //                        //perhitungan rekening koran hutang vs kas(CN rekening koran)
            ////                        "pairMethod" => array(
            ////                            "recom" => "ReComRekeningKoran",
            ////                            "calculate" => array(
            ////                                "source" => "nilai_entry",
            ////                                "target" => "credit_note_dipakai",
            ////                                "pair_source" => "nilai_entry",//sumber yang dibandingkan
            ////                                "id" =>"cash_account",
            ////                            ),
            ////                        ),
            //                    ),
            //                ),
            //            ),


            //            "branch" => array(
            //                "1" => array(
            //                    "cash_account_source" => array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "radio",
            //                        "label" => "branch cash account",
            //                        "mdlName" => "MdlBankAccountSaldo", // MdlBankAccount_out
            //                        "mdlFilter" => array(
            //                            "bank.cabang_id=placeID",
            //                        ),
            //                        "key" => "id",
            //                        "labelSrc" => "nama",
            //                        "usedFields" => array(
            //                            "nama" => "account number",
            //                            "alias" => "holder alias",
            //                            "debet" => "balance",
            //                        ),
            //                        "editPoints" => array(1,),
            //                    ),
            //                ),
            //            ),
        ),
        //        "pairMakers" => array(
        //            1 => array(
        //                "stock" => array(
        //                    "helperName" => "he_cek_saldo_kas",
        //                    "functionName" => "cekStockSaldoKas",
        //                    "params" => array(
        //                        "cabang_id" => "placeID",
        //                        //                        "gudang_id" => "gudangID",
        //                    ),
        //                ),
        //            ),
        //        ),
        //        "mainValueInjectors" => array(
        //            "amount" => "sisa",
        //            "creditAmount" => "creditAmount",
        //            "harus_bayar" => "harus_bayar",
        //        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartRowNumValidators" => array(
            "nilai_entry" => "amount of payment",
        ),
        "shoppingCartReferenceFields" => array(
            "nomer" => "sales number",
            "nomer_top" => "sales ref.",
            "valas_nama" => "currency",
            "fulldate" => "date",
            "tagihan_valas" => "due amount",
            "terbayar_valas" => "paid",
            "sisa_valas" => "due remain",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "person",
            //            "valas_nama" => "currency",
            "tagihan_valas" => "due amount",
            "terbayar_valas" => "paid",
            "sisa_valas" => "due remain",
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "amount" => array(
                        "label" => "total amount",
                        "defaultValue" => "sisa_valas",
                        "maxValue" => "sisa_valas",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "harus_bayar" => array(
                        "label" => "amount remains to pay",
                        "defaultValue" => "sisa_valas",
                        //                        "maxValue" => "sisa_valas",
                        //                        "minValue" => "sisa_valas",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),

                    ),
                    "nilai_entry" => array(
                        "label" => "amount of payment",
                        "defaultValue" => ".0",
                        "keyupAction" => "
    if(parseInt(this.value)>parseInt(document.getElementById('harus_bayar').value) || parseInt(this.value)<0){this.value=document.getElementById('harus_bayar').value;} 
                            ",
                        "addPoints" => array(1,),
                    ),
                ),
            ),
        ),
        "shoppingCartFieldValidatorsComparison" => array(
            "nilai_entry" => "sumber",
            "nilai_bayar" => "target",
        ),
        "pairRegistries" => array(
            "main",
        ),
        "previewCtr" => "Create",
        "connectTo" => "1758",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1759re",
                "label" => "EDIT setoran mata uang asing",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1759rrj",
                "label" => "REJECT setoran mata uang asing",
            ),
        ),
    ),
    "1758" => array(
        "icon" => "fa fa-money",
        "label" => "Penerimaan Setoran (Mata Uang Asing)",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "setoran mata uang asing",
                "actionLabel" => "setoran mata uang asing",
                "source" => "",
                "target" => "1758r",
                "userGroup" => "sys",
                "stateLabel" => "pending acceptance",
                "stateColor" => "#dd3300",
                "stateCaption" => "initiated by",
            ),
            2 => array(
                "label" => "Penerimaan Setoran (Mata Uang Asing)",
                "actionLabel" => "receive",
                "source" => "1758r",
                "target" => "1758",
                "userGroup" => "c_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "received by",
            ),
        ),
        "template" => "template/transaksi_payment.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.582",
            "transaksi_nilai_sisa>.0",
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
        "pihakModel" => "MdlCustomer",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "branch",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "nilai_bayar" => "amount",
            "cash_account_source__label" => "bank account source",
            "cashMethode__label" => "target method account",
            "cash_account_target__label" => "bank account target",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "sender",
            "cabang_nama" => "recipient",
            "759r" => "request number",
            //            "759" => "approval number",
            //            "758r" => "request number",
            "758" => "receipt number",

            "oleh_nama" => "person",
            "nilai_bayar" => "amount",
            "cash_account_source__label" => "bank account source",
            "cash_account_target__label" => "bank account target",
            //            "next_pic" => "next step otorisator",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "branch",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "nilai_bayar" => "amount",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                //                "jml" => "qty",
            ),

        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nomer",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",

        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "sisa_valas" => "due remain",
            ),
        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            2 => "nilai_bayar",
        ),
        "shoppingCartSumFields" => array(
            2 => array(
                "sisa_valas" => "total amount",
                //                "creditAmount" => "paid using credit",
                //                "nilai_entry" => "paid using cash account",
                //                "nilai_bayar" => "total amount of payment",
                //                "new_sisa" => "remain debt (from list)",
            ),
        ),
        "shoppingCartAvoidRemove" => true,
        "tagihanSrc" => "harus_bayar",
        "receiptElements" => array(
            //            "cash_account" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "branch cash account",
            //                "mdlName" => "MdlBankAccount_out",
            //                "mdlFilter" => array(
            //                    "bank.cabang_id=placeID",
            //                ),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "usedFields" => array(
            //                    "nama" => "account",
            //                ),
            //                "editPoints" => array(1),
            //                "noValidatate" =>true,
            //            ),
            //            "cash_account_tujuan" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "center cash account",
            //                "mdlName" => "MdlBankAccount_in",
            //                "mdlFilter" => array(
            //                    "cabang_id=place2ID",
            //                ),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "usedFields" => array(
            //                    "nama" => "account",
            //                ),
            //                "editPoints" => array(1, 2),
            //                "noValidatate" =>true,
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
            //                "noValidatate" =>true,
            //            ),
        ),
        "pairMakers" => array(
            1 => array(
                "stock" => array(
                    "helperName" => "he_cek_saldo_kas",
                    "functionName" => "cekStockSaldoKas",
                    "params" => array(
                        "cabang_id" => "placeID",
                        //                        "gudang_id" => "gudangID",
                    ),
                ),
            ),
        ),
        "mainValueInjectors" => array(
            "amount" => "sisa",
            "creditAmount" => "creditAmount",
            "harus_bayar" => "harus_bayar",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartRowNumValidators" => array(
            "nilai_entry" => "amount of payment",
        ),

        "pairRegistries" => array(
            "main",
        ),
        "revertException" => true,
        "previewCtr" => "Create",
    ),
);