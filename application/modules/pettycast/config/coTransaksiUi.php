<?php
//region urusan tanggal-menanggal
// date_default_timezone_set('asia/jakarta');
// $date = new DateTime(date("Y-m-d")); // Y-m-d
// $date->add(new DateInterval('P30D'));
//$date->format('Y-m-d') . "\n";
//endregion

//tambahin filter "461ro untuk selectornota taxes 681
$config["coTransaksiUi"] = array(
    "671" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "pettycash (branch)",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "pettycash",
                "actionLabel" => "save",
                "source" => "",
                "target" => "671r",
                "userGroup" => "o_kasir",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
//            2 => array(
//                "label" => "pettycash. authorization",
//                "actionLabel" => "approve request",
//                "source" => "671r",
//                "target" => "671",
//                "userGroup" => "o_kasir",
//                "stateLabel" => "make claim",
//                "stateColor" => "#ff7700",
//                "stateCaption" => "approved by",
//                "allowEdit" => true,
//                "allowIncrement" => true,
//            ),
        ),
        "template" => "template/transaksi_pettycash.html",
        "selectorModel" => "MdlExpense",
        "selectorSrcModel" => "MdlExpense",
        "selectedPrice" => array(
            "model" => "MdlHargaProduk",
            "label" => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(//            "pettycash=.1",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pettycash",
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
        //        "selectorProcessor" => "_processSelectProduct/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "kantor pusat harus ditentukan...",
        "pihakFilters" => array(
            //            "id=cabang_id",
            //            "id<>cabang_id",
            "id=.-1",
        ),
        "pihakMainValueSrc" => array(
            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",
        "shortStepHistoryFields" => array(
            // "jenis_label" => "activity",
            "dtime" => "tanggal",
            "cabang2_nama" => "dari",
            "cabang_nama" => "tujuan",
            "nomer" => "nomer",
            //            "759" => "approval number",
            //            "758r" => "request number",
            // "758" => "receipt number",

            "item_fields" => "isi",
            "oleh_nama" => "pic",
            "transaksi_nilai" => "nilai",
            // "cash_account_source__label" => "bank account source",
            // "cash_account_target__label" => "bank account target",
            "next_pic" => "next step otorisator",
        ),

        //tambahan pihak2

        "mainselectorModel" => array(
            "MdlDtaBiayaProduksi" => array(
                "label" => "biaya produksi",
                "allowed_branch" => array(25)
            ),
            "MdlDtaBiayaUsaha" => array(
                "label" => "biaya usaha",
                "allowed_branch" => array(1, 21),
            ),
            "MdlDtaBiayaUmum" => array(
                "label" => "biaya umum",
                "allowed_branch" => array(1, 21, 25),
            ),

        ),

        "pihakModelMain" => "MdlPettycashStatic",
        "pihakMainCaller" => "_selectorPihakMain/selectPihak",
        "pihakMainLabel" => "jenis biaya",
        "pihakMainFilters" => array(
            //            "id=cabang_id",
            //            "id<>cabang_id",
            //            "id=.-1",
        ),
        "pihakMainValueSrc2" => array(
            "pihakMdlName" => "mdl_name",
        ),
        "pihakMainProcessor" => "_processPihakMain/select",

        //

        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "tanggal",
//            "suppliers_nama" => "vendor",
            "nomer_top" => "request number",
            "nomer" => "receipt number",
            "item_fields" => "isi",
            "oleh_nama" => "pic",
            "transaksi_nilai" => "nilai",
            //            "ppn"      => "ppn",
            //            "nett"      => "netto",
        ),
        "historyFields" => array(
            1 => array(
//                "jenis_label" => "activity",
                "dtime" => "tanggal",
                "suppliers_nama" => "vendor",
                "nomer_top" => "request number",
                //                "nomer" => "receipt number",
                "item_fields" => "isi",
                "oleh_nama" => "pic",
                "transaksi_nilai" => "nilai",
                "print_label" => "tool",
            ),
            2 => array(
//                "jenis_label" => "activity",
                "dtime" => "tanggal",
                "suppliers_nama" => "vendor",
                "nomer_top" => "request number",
                "nomer" => "receipt number",
                //                "nomer" => "receipt number",
                "item_fields" => "isi",
                "oleh_nama" => "pic",
                "transaksi_nilai" => "nilai",
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
                "nama" => "nama",
                //                "jml" => "qty",
//                "reference" => "reference",
            ),
            2 => array(
                "nama" => "nama",
                "jml" => "qty",
//                "reference" => "reference",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "reference" => "reference",
                "harga" => "harga",
            ),
            2 => array(
                "reference" => "reference",
                "harga" => "harga",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteEditabled" => array(
            2 => true,
            //            3 => true,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
                "reference",
            ),
            2 => array(),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",

        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),
        //        "shoppingCartRowValidators" => array(),
        "shoppingCartRowValidators" => array(
            "pihakID" => "pihak ID",
            "pihakName" => "pihak name",
        ),
        "shoppingCartFieldMidValidatorsComparison" => array(
            "harga" => "sumber",
            "pettycash_account__saldo" => "target",
        ),
        "shoppingCartFieldMidValidatorsComparisonLabel" => array(
            "harga" => "request klaim",
            "pettycash_account__saldo" => "saldo pettycash",
        ),
        "shoppingCartValidatorsComparison" => array(
            "harga" => "sumber",
            "pettycash_account__saldo" => "target",
        ),
        "shoppingCartValidatorsComparisonLabel" => array(
            "harga" => "request klaim",
            "pettycash_account__saldo" => "saldo pettycash",
        ),

        "receiptElements" => array(
            "pettycash_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "pettycash amount",
                "pairedModel" => array(
                    "mdlName" => "ComLockerValue",
                    "mdlMethod" => "fetchBalances",
                    "mdlFilter" => array(
                        "cabang_id" => "placeID",
                        "state" => ".active",
                    ),
                    "key" => "produk_id",
                    "rekening" => "pettycash",
                    "fieldID" => "nilai",
                    "fieldLabel" => "saldo",
                ),
                "mdlName" => "MdlPettycashAccount",
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
                "noValidate" => true,
            ),
            "gudang2" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "gudang dc",
                "mdlName" => "MdlGudangDefault_center",
                "mdlFilter" => array("cabang_id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),
        "relativeElements" => array(),
        "relativeOptions" => array(),

        "pairRegistries" => array(
            "main", "items"
        ),
        "connectTo" => "672",
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "671re",
                "label" => "EDIT pettycash",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "671rrj",
                "label" => "REJECT pettycash",
            ),
        ),
        "shortItemsFields" => array(
//            "produk_kode" => "sku",
//            "barcode" => "barcode",
            "nama" => array(
                "label" => "nama",
                "addKey" => "keterangan",
            ),
            "harga" => "nilai",
//            "jml" => "qty",
            // "produk_ord_diterima"=>"send",
            // "valid_qty"=>"outstanding",
        ),
        "pettycash" => array(
            "plafon" => array(
                "comModel" => "ComRekeningPembantuKas",
                "rekening" => "1010010040",
                "comFilter" => array(
                    "cabang_id=.-1",
                    "extern_id=pettycash_account",
                ),
            ),

        ),
        "pettycashHeader" => array(
            "pettycash_plafon" => array(
                "key_id" => "pettycash_account",
                "key" => "pettycash_account__plafon",
                "label" => "plafon",
            ),
            "pettycash_terpakai" => array(
                "cabang_id" => "placeID",
                "key_id" => "pettycash_account",
                "key" => "pettycash_account__terpakai",
                "label" => "dipakai",
            ),
            "pettycash_saldo" => array(
                "cabang_id" => "placeID",
                "key_id" => "pettycash_account",
                "key" => "pettycash_account__saldo",
                "label" => "saldo",
                "link_mutasi" => "Ledger/viewMoveDetailsPettycash/RekeningPembantuPettycash/1010010040/",
            ),
        ),
        "shopingCartReload" => true,
    ),
    "672" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "otorisasi pettycash (branch)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "pettycash",
                "actionLabel" => "save",
                "source" => "",
                "target" => "672r",
                "userGroup" => "sys",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "otorisasi pettycash",
                "actionLabel" => "otorisasi claim pettycash",
                "source" => "672r",
                "target" => "672",
                "userGroup" => "c_holding",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlPettycash",
        "selectorSrcModel" => "MdlPettycash",
        "selectedPrice" => array(
            "model" => "MdlHargaProduk",
            "label" => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(//            "suppliers_id=pihakID",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pettycash",
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

        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id<>cabang_id",
        ),
        "pihakMainValueSrc" => array(
            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",

        "shortHistoryFields" => array(
            "dtime" => "tanggal",
            "cabang2_nama" => "cabang",
            "nomer_top" => "request number",
            "nomer" => "receipt number",
            "item_fields" => "isi",
            "oleh_nama" => "pic",
            "transaksi_nilai" => "nilai",
        ),
        "historyFields" => array(
            1 => array(
//                "jenis_label" => "activity",
                "dtime" => "tanggal",
                "suppliers_nama" => "vendor",
                "nomer_top" => "request number",
                "nomer" => "receipt number",
                "item_fields" => "isi",
                "oleh_nama" => "pic",
                "transaksi_nilai" => "nilai",
                "print_label" => "tool",
            ),
            2 => array(
//                "jenis_label" => "activity",
                "dtime" => "tanggal",
                "suppliers_nama" => "vendor",
                "nomer_top" => "request number",
                "nomer" => "receipt number",
                "item_fields" => "isi",
                "oleh_nama" => "pic",
                "transaksi_nilai" => "nilai",
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
                "nama" => "nama",
                //                "jml" => "qty",
//                "reference" => "reference",
            ),
            2 => array(
                "nama" => "nama",
                "jml" => "qty",
//                "reference" => "reference",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "harga",
            ),
            2 => array(
                "harga" => "harga",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteEditabled" => array(
            2 => false,
            3 => false,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
                "reference",
            ),
            2 => array(),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",

        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(),

        "pairRegistries" => array(
            "main", "items"
        ),
        "receiptElements" => array(),
        "relativeElements" => array(),
        "relativeOptions" => array(),
        "previewCtr" => "Create",
        "shortItemsFields" => array(
//            "produk_kode" => "sku",
//            "barcode" => "barcode",
            "nama" => array(
                "label" => "nama",
                "addKey" => "keterangan",
            ),
            "harga" => "nilai",
//            "jml" => "qty",
            // "produk_ord_diterima"=>"send",
            // "valid_qty"=>"outstanding",
        ),
    ),
    "771" => array(
        "icon" => "fa fa-money",
        "label" => "pettycash refill (branch)",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "refill pettycash",
                "actionLabel" => "process refill",
                "source" => "",
                "target" => "771",
                "userGroup" => "c_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "-",
            ),
        ),
        "paymentConfig" => true,
        "isPaymentRadioSelect" => true,
        "template" => "template/transaksi_payment.html",
        //        "template" => "template/transaksi.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.671",
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
//            "jenis_label" => "activity",
            "dtime" => "tanggal",
            "customers_nama" => "cabang",
            "nomer" => "nomer",
            "nilai_entry" => "nilai",
            "oleh_nama" => "person",
        ),
        "historyFields" => array(
            1 => array(
//                "jenis_label" => "activity",
                "dtime" => "tanggal",
                "customers_nama" => "cabang",
                "nomer" => "nomer",
                "nilai_entry" => "nilai",
                "oleh_nama" => "person",
                "print_label" => "tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array(
                //                "review_details" =>"id",
                "print_label" => "nomer",
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
        "shoppingCartFields2" => array(
            1 => array(
                "nama" => "item source name",
                "jml" => "qty",
                "harga" => "price",
                //                "referensi" => "reference",
            ),
            2 => array(
                "nama" => "item source name",
                "jml" => "qty",
                //                "satuan" => "satuan",
                //                "referensi" => "reference",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nomer",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            "id_master" => "id_master",
            "extern_label2" => "pihakMainName",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "sisa" => "sisa",
            ),
        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartAvoidRemove" => true,
        "tagihanSrc" => "harus_bayar",
        "receiptElements" => array(
            "cash_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "cash account",
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
                "pairedModel" => array(
                    "mdlName" => "ComLockerValue",
                    "mdlMethod" => "fetchBalances",
                    "mdlFilter" => array(
                        "cabang_id" => "placeID",
                        "state" => ".active",
                    ),
                    "key" => "produk_id",
                    "rekening" => "kas",
                    "fieldID" => "nilai",
                    "fieldLabel" => "saldo",
                ),
                //                "mdlName" => "MdlBankAccount_cash_and_out",
                "mdlName" => "MdlBankAccount_cash_and_in",
                "mdlFilter" => array(//                    "cabang_id=placeID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account",
                    "saldo" => "balance",
                ),
                "editPoints" => array(1,),
            ),
            "pettycash_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "pettycash amount",
                "mdlName" => "MdlPettycashAccount",
                "mdlFilter" => array(
                    "cabang_id=pihakID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
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
                ),
                "preBiaya" => array(
                    "helperName" => "he_pair_produksi_prebiaya_helper",
                    "functionName" => "cekPairProduksiPreBiaya",
                    "source" => "items2_sum",
                ),
            ),
        ),
        "pairInjectors" => array(
            1 => array(
                "preBiaya" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "costName",
                    ),
                ),
            ),
        ),

        "pairRegistries" => array(
            "main", "items"
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "branch ID",
            "pihakName" => "branch name",
        ),
        "shoppingCartRowNumValidators" => array(
            "nilai_entry" => "amount of payment",
        ),
        "shoppingCartUnionValidators" => array(
            array(
                //                "creditAmount" => "credit amount",
                "nilai_entry" => "amount value",
            ),
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "amount" => array(
                        "label" => "total amount",
                        "defaultValue" => "sisa",
                        "maxValue" => "sisa",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "harus_bayar" => array(
                        "label" => "amount remains to pay",
                        "defaultValue" => "(sisa-creditAmount-creditValue)",
                        "maxValue" => "(sisa-creditAmount-creditValue)",
                        "minValue" => "(sisa-creditAmount-creditValue)",
                        //                        "keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(removeCommas(document.getElementById('bayar').value)-parseFloat(gt))",
                        //                        "keyupAction" => "var gt=this.min,bayar=this.value,kembali=document.getElementById('kembali'); kembali.value=parseFloat(bayar)-parseFloat(gt);if(parseFloat(bayar)<parseFloat(gt)){kembali.style.color='red',kembali.style.fontWeight='700'}else{kembali.style.color='green',kembali.style.fontWeight='700'}",

                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),

                    ),
                    "nilai_entry" => array(
                        "label" => "amount of payment",
//                        "defaultValue" => ".0",
                        "defaultValue" => "harus_bayar",
                        "keyupAction" => "
    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harus_bayar').value;}
                            ",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                ),
            ),
        ),
        "shoppingCartReferenceFields" => array(
            "extern_label2" => "jenis",
            "nomer" => "receipt number",
            "nomer_top" => "receipt ref.",
            //            "refNum" => "return ref.",
            "fulldate" => "date",
            "tagihan" => "due amount",
            //            "refValue" => "returned",
            "terbayar" => "paid",
            //            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "branch",
            //            "extern_label2" => "jenis",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            //            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "mainCloner" => array(
            "items" => array(
                "pettycash_account" => "pettycash_account",
                "pettycash_account__label" => "pettycash_account__label",
            ),
        ),
        "componentsAss" => array(
            "model" => "MdlTransaksi",
            "modelSrc" => "MdlNotaItem",
        ),
        "relativeComNameDetails" => array(
            "biaya usaha" => "RekeningPembantuBiayaUsaha",
            "biaya produksi" => "RekeningPembantuBiayaProduksi",
            "biaya umum" => "RekeningPembantuBiayaUmum",
        ),
        "shoppingCartPairedItem" => array(
            //            "enabled" => true,
            //            "mdlName" => "MdlProduk",
            //            "srcKey" => "id",
            //            "srcLabel" => array("nama"),
            //            "mdlFilter" => array("id<>id"),
            "targetGateName" => "items2_sum",
        ),
        "previewCtr" => "Create",
        "shortItemsFields" => array(
//            "produk_kode" => "sku",
//            "barcode" => "barcode",
            "nama" => array(
                "label" => "nama",
                "addKey" => "keterangan",
            ),
            "harga" => "nilai",
//            "jml" => "qty",
            // "produk_ord_diterima"=>"send",
            // "valid_qty"=>"outstanding",
        ),
        "shopingCartReload" => true,
    ),
    //  config pettycash center
    "1671" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "pettycash (pusat)",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "pettycash",
                "actionLabel" => "save",
                "source" => "",
                "target" => "1671r",
                "userGroup" => "c_holding",
                //                "userGroup" => "disabled",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "pettycash. authorization",
                "actionLabel" => "approve request",
                "source" => "1671r",
                "target" => "1671",
                "userGroup" => "c_holding",
                //                "userGroup" => "disabled",
                "stateLabel" => "make claim",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
        ),
        "template" => "template/transaksi_pettycash.html",
        "selectorModel" => "MdlExpense",
        "selectorSrcModel" => "MdlExpense",
        "selectedPrice" => array(
            "model" => "MdlHargaProduk",
            "label" => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(//            "pettycash=.1",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pettycash",
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
        //        "selectorProcessor" => "_processSelectProduct/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            //            "id=cabang_id",
            //            "id<>cabang_id",
            "id=.-1",
        ),
        "pihakMainValueSrc" => array(
            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",
        "shortStepHistoryFields" => array(
            // "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "sender",
            "cabang_nama" => "recipient",
            "671" => "number",
            //            "759" => "approval number",
            //            "758r" => "request number",
            // "758" => "receipt number",

            "oleh_nama" => "person",
            "nilai_bayar" => "amount",
            // "cash_account_source__label" => "bank account source",
            // "cash_account_target__label" => "bank account target",
            "next_pic" => "next step otorisator",
        ),

        //tambahan pihak2

        "mainselectorModel" => array(
            "MdlDtaBiayaProduksi" => array(
                "label" => "biaya produksi",
                "allowed_branch" => array(25)
            ),
            "MdlDtaBiayaUsaha" => array(
                "label" => "biaya usaha",
                "allowed_branch" => array(1, 21),
            ),
            "MdlDtaBiayaUmum" => array(
                "label" => "biaya umum",
                "allowed_branch" => array(1, 21, 25),
            ),

        ),

        "pihakModelMain" => "MdlPettycashStatic",
        "pihakMainCaller" => "_selectorPihakMain/selectPihak",
        "pihakMainLabel" => "jenis biaya",
        "pihakMainFilters" => array(
            //            "id=cabang_id",
            //            "id<>cabang_id",
            //            "id=.-1",
        ),
        "pihakMainValueSrc2" => array(
            "pihakMdlName" => "mdl_name",
        ),
        "pihakMainProcessor" => "_processPihakMain/select",

        //

        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer_top" => "PO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "transaksi_nilai" => "amount",
            //            "ppn"      => "ppn",
            //            "nett"      => "netto",
        ),
        "historyFields" => array(
            1 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "transaksi_nilai" => "amount",
                "print_label" => "tool",
            ),
            2 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "transaksi_nilai" => "amount",
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
                "nama" => "nama",
                //                "jml" => "qty",
//                "reference" => "reference",
            ),
            2 => array(
                "nama" => "nama",
                "jml" => "qty",
//                "reference" => "reference",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "reference" => "reference",
                "harga" => "harga",
            ),
            2 => array(
                "reference" => "reference",
                "harga" => "harga",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteEditabled" => array(
            2 => true,
            //            3 => true,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
                "reference",
            ),
            2 => array(),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",

        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),
        //        "shoppingCartRowValidators" => array(),
        "shoppingCartRowValidators" => array(
            "pihakID" => "pihak ID",
            "pihakName" => "pihak name",
        ),
        "shoppingCartFieldMidValidatorsComparison" => array(
            "harga" => "sumber",
            "pettycash_account__saldo" => "target",
        ),

        "receiptElements" => array(
            "pettycash_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "pettycash amount",
                "pairedModel" => array(
                    "mdlName" => "ComLockerValue",
                    "mdlMethod" => "fetchBalances",
                    "mdlFilter" => array(
                        "cabang_id" => "placeID",
                        "state" => ".active",
                    ),
                    "key" => "produk_id",
                    "rekening" => "pettycash",
                    "fieldID" => "nilai",
                    "fieldLabel" => "saldo",
                ),
                "mdlName" => "MdlPettycashAccount",
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
                "noValidate" => true,
            ),
        ),
        "relativeElements" => array(),
        "relativeOptions" => array(),

        "pairRegistries" => array(
            "main", "items"
        ),
        "connectTo" => "1672",
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1671re",
                "label" => "EDIT pettycash",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1671rrj",
                "label" => "REJECT pettycash",
            ),
        ),
        "shortItemsFields" => array(
//            "produk_kode" => "sku",
//            "barcode" => "barcode",
            "nama" => array(
                "label" => "nama",
                "addKey" => "keterangan",
            ),
            "harga" => "nilai",
//            "jml" => "qty",
            // "produk_ord_diterima"=>"send",
            // "valid_qty"=>"outstanding",
        ),
        "shopingCartReload" => true,
        "pettycash" => array(
            "plafon" => array(
                "comModel" => "ComRekeningPembantuKas",
                "rekening" => "1010010040",
                "comFilter" => array(
                    "cabang_id=.-1",
                    "extern_id=pettycash_account",
                ),
            ),

        ),
        "pettycashHeader" => array(
            "pettycash_plafon" => array(
                "key_id" => "pettycash_account",
                "key" => "pettycash_account__plafon",
                "label" => "plafon",
            ),
            "pettycash_terpakai" => array(
                "cabang_id" => "placeID",
                "key_id" => "pettycash_account",
                "key" => "pettycash_account__terpakai",
                "label" => "dipakai",
            ),
            "pettycash_saldo" => array(
                "cabang_id" => "placeID",
                "key_id" => "pettycash_account",
                "key" => "pettycash_account__saldo",
                "label" => "saldo",
                "link_mutasi" => "Ledger/viewMoveDetailsPettycash/RekeningPembantuPettycash/1010010040/",
            ),
        ),
    ),
    "1672" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "pettycash authorization (pusat)",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "pettycash",
                "actionLabel" => "save",
                "source" => "",
                "target" => "1672r",
                "userGroup" => "sys",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "pettycash. authorization",
                "actionLabel" => "approve claim pettycash",
                "source" => "1672r",
                "target" => "1672",
                "userGroup" => "c_holding",
                //                "userGroup" => "disabled",
                "stateLabel" => "make claim",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlPettycash",
        "selectorSrcModel" => "MdlPettycash",
        "selectedPrice" => array(
            "model" => "MdlHargaProduk",
            "label" => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(//            "suppliers_id=pihakID",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pettycash",
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

        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id<>cabang_id",
        ),
        "pihakMainValueSrc" => array(
            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",

        "shortHistoryFields" => array(
            //            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "branch",
            "nomer_top" => "PO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "transaksi_nilai" => "amount",
            //            "ppn"      => "ppn",
            //            "nett"      => "netto",
        ),
        "historyFields" => array(
            1 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PO number",
                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "transaksi_nilai" => "amount",
                "print_label" => "tool",
            ),
            2 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PO number",
                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "transaksi_nilai" => "amount",
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
        "shoppingCartNoteEditabled" => array(
            2 => false,
            3 => false,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
                "reference",
            ),
            2 => array(),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",

        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(),

        "pairRegistries" => array(
            "main", "items"
        ),
        "receiptElements" => array(),
        "relativeElements" => array(),
        "relativeOptions" => array(),
        "previewCtr" => "Create",
        "shortItemsFields" => array(
//            "produk_kode" => "sku",
//            "barcode" => "barcode",
            "nama" => array(
                "label" => "nama",
                "addKey" => "keterangan",
            ),
            "harga" => "nilai",
//            "jml" => "qty",
            // "produk_ord_diterima"=>"send",
            // "valid_qty"=>"outstanding",
        ),
    ),
    "1771" => array(
        "icon" => "fa fa-money",
        "label" => "pettycash refill (pusat)",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "refill pettycash",
                "actionLabel" => "process refill",
                "source" => "",
                "target" => "1771",
                "userGroup" => "c_finance",
                //                "userGroup" => "disabled",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "-",
            ),
        ),
        "paymentConfig" => true,
        "isPaymentRadioSelect" => true,
        "template" => "template/transaksi_payment.html",
        //        "template" => "template/transaksi.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.671",
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
//            "jenis_label" => "activity",
            "dtime" => "tanggal",
            "customers_nama" => "cabang",
            "nomer" => "nomer",
            "nilai_entry" => "nilai",
            "oleh_nama" => "person",
        ),
        "historyFields" => array(
            1 => array(
//                "jenis_label" => "activity",
                "dtime" => "tanggal",
                "customers_nama" => "cabang",
                "nomer" => "nomer",
                "nilai_entry" => "nilai",
                "oleh_nama" => "person",
                "print_label" => "tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array(
                //                "review_details" =>"id",
                "print_label" => "nomer",
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
        "shoppingCartFields2" => array(
            1 => array(
                "nama" => "item source name",
                "jml" => "qty",
                "harga" => "price",
                //                "referensi" => "reference",
            ),
            2 => array(
                "nama" => "item source name",
                "jml" => "qty",
                //                "satuan" => "satuan",
                //                "referensi" => "reference",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nomer",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            "id_master" => "id_master",
            "extern_label2" => "pihakMainName",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "sisa" => "sisa",
            ),
        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartAvoidRemove" => true,
        "tagihanSrc" => "harus_bayar",
        "receiptElements" => array(
            "cash_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "cash account",
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
                "pairedModel" => array(
                    "mdlName" => "ComLockerValue",
                    "mdlMethod" => "fetchBalances",
                    "mdlFilter" => array(
                        "cabang_id" => "placeID",
                        "state" => ".active",
                    ),
                    "key" => "produk_id",
                    "rekening" => "kas",
                    "fieldID" => "nilai",
                    "fieldLabel" => "saldo",
                ),
                //                "mdlName" => "MdlBankAccount_cash_and_out",
                "mdlName" => "MdlBankAccount_cash_and_in",
                "mdlFilter" => array(//                    "cabang_id=placeID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account",
                    "saldo" => "balance",
                ),
                "editPoints" => array(1,),
            ),
            "pettycash_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "pettycash amount",
                "mdlName" => "MdlPettycashAccount",
                "mdlFilter" => array(
                    "cabang_id=placeID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
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
                ),
                "preBiaya" => array(
                    "helperName" => "he_pair_produksi_prebiaya_helper",
                    "functionName" => "cekPairProduksiPreBiaya",
                    "source" => "items2_sum",
                ),
            ),
        ),
        "pairInjectors" => array(
            1 => array(
                "preBiaya" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "costName",
                    ),
                ),
            ),
        ),

        "pairRegistries" => array(
            "main", "items"
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "branch ID",
            "pihakName" => "branch name",
        ),
        "shoppingCartRowNumValidators" => array(
            "nilai_entry" => "amount of payment",
        ),
        "shoppingCartUnionValidators" => array(
            array(
                //                "creditAmount" => "credit amount",
                "nilai_entry" => "amount value",
            ),
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "amount" => array(
                        "label" => "total amount",
                        "defaultValue" => "sisa",
                        "maxValue" => "sisa",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "harus_bayar" => array(
                        "label" => "amount remains to pay",
                        "defaultValue" => "(sisa-creditAmount-creditValue)",
                        "maxValue" => "(sisa-creditAmount-creditValue)",
                        "minValue" => "(sisa-creditAmount-creditValue)",
                        //                        "keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(removeCommas(document.getElementById('bayar').value)-parseFloat(gt))",
                        //                        "keyupAction" => "var gt=this.min,bayar=this.value,kembali=document.getElementById('kembali'); kembali.value=parseFloat(bayar)-parseFloat(gt);if(parseFloat(bayar)<parseFloat(gt)){kembali.style.color='red',kembali.style.fontWeight='700'}else{kembali.style.color='green',kembali.style.fontWeight='700'}",

                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),

                    ),
                    "nilai_entry" => array(
                        "label" => "amount of payment",
                        "defaultValue" => ".0",
                        "keyupAction" => "
    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harus_bayar').value;}
                            ",
                        //                        'disabled'     => "disabled",
                        "addPoints" => array(1,),
                    ),
                ),
            ),
        ),
        "shoppingCartReferenceFields" => array(
            "extern_label2" => "jenis",
            "nomer" => "receipt number",
            "nomer_top" => "receipt ref.",
            //            "refNum" => "return ref.",
            "fulldate" => "date",
            "tagihan" => "due amount",
            //            "refValue" => "returned",
            "terbayar" => "paid",
            //            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "branch",
            //            "extern_label2" => "jenis",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            //            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "mainCloner" => array(
            "items" => array(
                "pettycash_account" => "pettycash_account",
                "pettycash_account__label" => "pettycash_account__label",
            ),
        ),
        "componentsAss" => array(
            "model" => "MdlTransaksi",
            "modelSrc" => "MdlNotaItem",
        ),
        "relativeComNameDetails" => array(
            "biaya usaha" => "RekeningPembantuBiayaUsaha",
            "biaya produksi" => "RekeningPembantuBiayaProduksi",
            "biaya umum" => "RekeningPembantuBiayaUmum",
        ),
        "shoppingCartPairedItem" => array(
            //            "enabled" => true,
            //            "mdlName" => "MdlProduk",
            //            "srcKey" => "id",
            //            "srcLabel" => array("nama"),
            //            "mdlFilter" => array("id<>id"),
            "targetGateName" => "items2_sum",
        ),
        "previewCtr" => "Create",
        "shortItemsFields" => array(
//            "produk_kode" => "sku",
//            "barcode" => "barcode",
            "nama" => array(
                "label" => "nama",
                "addKey" => "keterangan",
            ),
            "harga" => "nilai",
//            "jml" => "qty",
            // "produk_ord_diterima"=>"send",
            // "valid_qty"=>"outstanding",
        ),
    ),

    "770" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "penambahan plafon pettycash",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "penambahan plafon pettycash",
                "actionLabel" => "simpan",
                "source" => "",
                "target" => "770",
                "userGroup" => "c_holding",
                "stateLabel" => "complete",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
        ),
        "template" => "template/transaksi_nopihak.html",
        "selectorModel" => "MdlCabang",
        "selectorSrcModel" => "MdlCabang",
        "selectedPrice" => array(),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "jenis=.cabang",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "cabang",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            //            "lastPlafon" => "lastPlafon",
            //            "newPlafon" => "newPlafon",
        ),
        "selectorViewedFields" => array(
            "nama",
            //            "lastPlafon" => "lastPlafon",
            //            "newPlafon" => "newPlafon",
        ),
        "selectorProcessor" => "_processSelectPlafonPettycash/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id<>cabang_id",
        ),
        "pihakMainValueSrc" => array(
            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",

        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "tanggal",
            "cabang_nama" => "cabang",
//            "nomer_top" => "add plafon number",
            "nomer" => "nomer",
            "oleh_nama" => "person",
            "lastPlafon" => "plafon awal",
            "addPlafon" => "jumlah penambahan",
            "newPlafon" => "plafon akhir",
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
            "lastPlafon" => "lastPlafon",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",
                //                "lastPlafon" => "lastPlafon",
                //                "newPlafon" => "newPlafon",
            ),
            2 => array(
                "nama" => "item name",
                "jml" => "qty",
                //                "lastPlafon" => "lastPlafon",
                //                "newPlafon" => "newPlafon",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "lastPlafon" => "lastPlafon",
                "addPlafon" => "amount",
                "newPlafon" => "newPlafon",
            ),
            2 => array(
                "lastPlafon" => "lastPlafon",
                "addPlafon" => "amount",
                "newPlafon" => "newPlafon",
            ),
        ),
        "shoppingCartNoteEnabled" => false,
        "shoppingCartEditableFields" => array(
            1 => array(
                //                "harga",
                "jml",
                "addPlafon",
            ),
            2 => array(),
        ),
        "shoppingCartAmountValue" => array(
            //            1 => "newPlafon",
            //            2 => "newPlafon",
        ),

        "shoppingCartFieldValidators" => array(
            //            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(),
        "shoppingCartFieldMidValidatorsComparison" => array(
            "lastPlafon" => "sumber",
            "newPlafon" => "target",
            //            "lastPlafon" => "target",
            //            "newPlafon" => "sumber",
        ),

        "shoppingCartHideSubamount" => array(
            1 => true,
        ),
        "receiptElements" => array(
            "paymentMethod_cash" => array(
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
                    "rekening" => "1010010010",
                    "fieldID" => "debet",
                    "fieldLabel" => "saldo",
                ),
                "mdlName" => "MdlBankAccount_cash_and_in",
//                "mdlName" => "MdlBankAccount_cash",
                "mdlFilter" => array(
//                    "cabang_id=placeID",
                    "jenis=.account_cash",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account",
                    "saldo" => "saldo",
                ),
                "editPoints" => array(1,),
            ),
            "paymentMethod_pettycash" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "pettycash account",
                "mdlName" => "MdlPettycashAccount",
                "mdlFilter" => array(
                    "cabang_id=cabang2ID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account",
                ),
                "editPoints" => array(1,),
            ),
        ),
        "relativeElements" => array(),
        "relativeOptions" => array(),
        "cloner" => array(
            "srcGateName" => "items",
            "cloneLabel" => array(
                "id",
                "nama",
            ),
        ),
        "mainCloner" => array(
            "items" => array(
                "cabang2ID" => "id",
                "cabang2Name" => "nama",
            ),
            //            "items2" => array(
            //                "rek2ID" => "id",
            //                "rek2Name" => "nama",
            //            ),
        ),
        "previewCtr" => "Create",
        "pairRegistries" => array(
            "main", "items"
        ),
    ),
    "970" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "pengurangan plafon pettycash",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "pengurangan plafon pettycash",
                "actionLabel" => "simpan",
                "source" => "",
                "target" => "970",
                "userGroup" => "c_holding",
                "stateLabel" => "complete",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
        ),
        "template" => "template/transaksi_nopihak.html",
        "selectorModel" => "MdlCabang",
        "selectorSrcModel" => "MdlCabang",
        "selectedPrice" => array(),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "jenis=.cabang",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "cabang",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            //            "lastPlafon" => "lastPlafon",
            //            "newPlafon" => "newPlafon",
        ),
        "selectorViewedFields" => array(
            "nama",
            //            "lastPlafon" => "lastPlafon",
            //            "newPlafon" => "newPlafon",
        ),
        "selectorProcessor" => "_processSelectPlafonPettycash/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id<>cabang_id",
        ),
        "pihakMainValueSrc" => array(
            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",

        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "tanggal",
//            "suppliers_nama" => "vendor",
//            "nomer_top" => "PO number",
            "nomer" => "nomer",
            "oleh_nama" => "person",
            "addPlafon" => "plafon awal",
            "lastPlafon" => "jumlah penambahan",
            "newPlafon" => "plafon akhir",
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
            "lastPlafon" => "lastPlafon",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",
            ),
            2 => array(
                "nama" => "item name",
                "jml" => "qty",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "lastPlafon" => "lastPlafon",
                "addPlafon" => "amount",
                "newPlafon" => "newPlafon",
            ),
            2 => array(
                "lastPlafon" => "lastPlafon",
                "addPlafon" => "amount",
                "newPlafon" => "newPlafon",
            ),
        ),
        "shoppingCartNoteEnabled" => false,
        "shoppingCartEditableFields" => array(
            1 => array(
                //                "harga",
                "jml",
                "addPlafon",
            ),
            2 => array(),
        ),
        "shoppingCartAmountValue" => array(
            //            1 => "newPlafon",
            //            2 => "newPlafon",
        ),

        "shoppingCartFieldValidators" => array(
            //            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(),
        "shoppingCartFieldMidValidatorsComparison" => array(
            //            "lastPlafon" => "sumber",
            //            "newPlafon" => "target",

//            "lastPlafon" => "target",
//            "newPlafon" => "sumber",
        ),

        "shoppingCartHideSubamount" => array(
            1 => true,
        ),
        "receiptElements" => array(
            "pettycash_plafon" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "pettycash plafon",
                "pairedModel" => array(
                    "mdlName" => "ComRekeningPembantuKas",
                    "mdlMethod" => "fetchBalances",
                    "mdlFilter" => array(
                        "cabang_id=cabangID",//pihakID
                    ),
                    "key" => "extern_id",
                    "rekening" => "1010010040",//pettycash
                    "fieldID" => "debet",
                    "fieldLabel" => "saldo",
                ),
                "mdlName" => "MdlPettycashAccount",
                "mdlFilter" => array(
                    "cabang_id=cabang2ID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account",
                    "saldo" => "balance",
                ),
                "editPoints" => array(1,),
            ),
            "pettycash_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "pettycash account",
                "mdlName" => "MdlPettycashAccount",
                "mdlFilter" => array(
//                    "cabang_id=placeID",
                    "cabang_id=cabang2ID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account",
                ),
                "editPoints" => array(1,),
            ),
            "paymentMethod_cash" => array(
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
                    "rekening" => "1010010010",
                    "fieldID" => "debet",
                    "fieldLabel" => "saldo",
                ),
                "mdlName" => "MdlBankAccount_cash_and_in",
                "mdlFilter" => array(
//                    "cabang_id=placeID",
                    //                    "jenis<>.pettycash",
                    //                    "jenis<>.bank",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account",
                    "saldo" => "saldo",
                ),
                "editPoints" => array(1,),
            ),

        ),
        "relativeElements" => array(),
        "relativeOptions" => array(),
        "cloner" => array(
            "srcGateName" => "items",
            "cloneLabel" => array(
                "id",
                "nama",
            ),
        ),
        "mainCloner" => array(
            "items" => array(
                "cabang2ID" => "id",
                "cabang2Name" => "nama",
            ),
            //            "items2" => array(
            //                "rek2ID" => "id",
            //                "rek2Name" => "nama",
            //            ),
        ),
        "previewCtr" => "Create",
        "pairRegistries" => array(
            "main", "items"
        ),
    ),
);