<?php
//region urusan tanggal-menanggal
// date_default_timezone_set('asia/jakarta');
// $date = new DateTime(date("Y-m-d")); // Y-m-d
// $date->add(new DateInterval('P30D'));
//$date->format('Y-m-d') . "\n";
//endregion

//tambahin filter "461ro untuk selectornota taxes 681
$config["coTransaksiUi"] = array(
    //  config penyetoran
    "759" => array(
        "icon" => "fa fa-money",
        "label" => "Penyetoran Kas",
        "place" => "branch",
        "paymentConfig" => true,
        "steps" => array(
            1 => array(
                "label" => "setoran kas",
                "actionLabel" => "penyetoran",
                "source" => "",
                "target" => "759r",
                "userGroup" => "o_finance",
                "stateLabel" => "prepare by",
                "stateColor" => "#dd3300",
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
            //            "jenis_label"                => "activity",
            "dtime" => "date",
            //            "customers_nama"             => "customer",
            "nomer" => "request number",
            "details" => "detail",
            "oleh_nama" => "person",
            "nilai_bayar" => "amount",
            "cash_account_source__label" => "bank account source",
            "cash_account_target__label" => "bank account target",
            "cashMethode__label" => "target method account",
        ),
        "shortStepHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "sender",
            "cabang_nama" => "recipient",
            "759r" => "request number",
            //            "759" => "approval number",
            //            "758r" => "request number",

            "details" => "invoice",
            "customerSetor" => "customer",
            "nilaiSetor" => "nilai",

            //            "758" => "receipt number",

            "oleh_nama" => "person",
            "nilai_bayar" => "amount",
            "cash_account_source__label" => "bank account source",
            "cash_account_target__label" => "bank account target",
            "cashMethode__label" => "target method account",
            "next_pic" => "next step otorisator",
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
        "historyFields" => array(
            1 => array(
                "no" => "no",
                "dtime" => "date",
                //            "customers_nama"             => "customer",
                "nomer" => "request number",
                "details" => "detail",
                "oleh_nama" => "person",
                "nilai_bayar" => "amount",
                "cash_account_source__label" => "bank account source",
                "cash_account_target__label" => "bank account target",
                "cashMethode__label" => "target method account",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
                "no" => "no",
                "dtime" => "date",
                //            "customers_nama"             => "customer",
                "nomer" => "request number",
                "details" => "detail",
                "oleh_nama" => "person",
                "nilai_bayar" => "amount",
                "cash_account_source__label" => "bank account source",
                "cash_account_target__label" => "bank account target",
                "cashMethode__label" => "target method account",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "nilai_bayar" => "amount",
        ),
        "extHistoryFields" => array(
            1 => array(
                //                "review_details" => "id",
                "print_label" => "nomer",
            ),
        ),
        "extHistoryFields2" => array(
            1 => array(
                //                "details" => "nama",
                "details" => array(
                    "kolom" => "nama",
                    "format" => "nomer",
                ),
                "customerSetor" => array(
                    "kolom" => "extern2_nama",
                    "format" => "nama",
                ),
                "nilaiSetor" => array(
                    "kolom" => "nilai_bayar",
                    "format" => "debet",
                ),
            ),
        ),

        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields" => array(
            1 => array(
                "extern2_nama" => "customer",
                "nama" => "item name",
                "jml" => "qty",
            ),

        ),
        "shoppingCartFieldSrc" => array(
            "extern2_id" => "extern2_id",
            "extern2_nama" => "extern2_nama",
            "nama" => "nomer",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",

        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "sisa" => "due remain",
            ),
        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                //                "sisa" => "debt amount",
                //                "creditAmount" => "paid using credit",
                //                "nilai_entry" => "paid using cash account",
                //                "nilai_bayar" => "total amount of payment",
                //                "new_sisa" => "remain debt (from list)",
            ),
        ),
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
            "cash_account_source" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "branch cash account",

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
                "mdlName" => "MdlBankAccount_cash_and_in", // MdlBankAccount_out
                "mdlFilter" => array(
                    "bank.cabang_id=placeID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account",
                    "saldo" => "balance",
                ),
                "editPoints" => array(1,),
                "labelValidate" => "Silahkan memilih rekening rekening bank sebelum melanjutkan transaksi.",
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
        "relativeElements" => array(
            "centerDetails" => array(
                "-1" => array(
                    //                    "cashMethode" => array(
                    //                        "elementType" => "dataModel",
                    //                        "inputType" => "radio",
                    //                        "label" => "Metode rekening",
                    //                        "mdlName" => "MdlCashAccountStatic",
                    //                        "mdlFilter" => array(
                    ////                    "extern_id=pihakID",
                    ////                    "cabang_id=cabangID",
                    ////                    "sisa>.0",
                    //                        ),
                    //                        "key" => "id",
                    //                        "labelSrc" => "nama",
                    //                        "usedFields" => array(
                    //                            "nama" => "method",
                    ////                    "extern_id" => "pihakID",
                    //
                    //                        ),
                    //                        "editPoints" => array(1,),
                    //                        "noValidate" => true,
                    //                        "noPrefetch" => true,
                    ////                "pairMethod" => array(
                    ////                    "recom" => "ReComUangMuka",
                    ////                    "calculate" => array(
                    ////                        "source" => "uangMuka",
                    ////                        "target" => "uang_muka_dipakai",
                    ////                        "pair_source" => "nilai_sisa",//sunbe sumber yang dibandingkan
                    ////                    ),
                    //
                    //
                    //                    ),

                    "cash_account_target" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "center cash account",
                        "mdlName" => "MdlBankAccount_in_and_koran",
                        "mdlFilter" => array(
                            "cabang_id=centerDetails",
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
                        "labelValidate" => "Silahkan memilih rekening rekening bank tujuan sebelum melanjutkan transaksi.",
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
        "shoppingCartReferenceFields" => array(
            "nomer" => "inv number",
            "nomer_top" => "reference number",
            //            "refNum" => "return ref.",
            "customers_nama" => "customer",
            "fulldate" => "date",
            "tagihan" => "due amount",
            //            "refValue" => "returned",
            "terbayar" => "paid",
            //            "diskon" => "discount",
            "sisa" => "due remain",
            "cash_account_nama" => "cash account",
            "notes" => "description",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "person",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            //            "diskon" => "discount",
            "sisa" => "due remain",
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
                    //                    "credit_amount" => array(
                    //                        "label" => "credit amount",
                    //                        "defaultValue" => "creditAmount",
                    //                        //                        "keyupAction" => "",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    //                    "credit_note" => array(
                    //                        "label" => "credit note",
                    //                        "defaultValue" => "creditValue",
                    //                        //                        "keyupAction" => "",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
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
        "shoppingCartFieldValidatorsComparison" => array(
            "nilai_entry" => "sumber",
            "nilai_bayar" => "target",
        ),

        "pairRegistries" => array(
            "main", "items"
        ),

        "connectTo" => "758",
        "paymentSrcLocked" => array(
            "enabled" => false,
            "notes" => "penerimaan tunai<br>belum dilakukan setoran ke bank",
        ),
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi Penyetoran Kas ke Pusat nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}. 
                            <br>Silahkan melakukan penyetoran ulang di cabang {cabang_nama}",
        ),
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "759re",
                "label" => "EDIT setoran kas",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "759rrj",
                "label" => "REJECT setoran kas",
            ),
        ),
    ),
    "758" => array(
        "icon" => "fa fa-money",
        "label" => "Penerimaan Setoran Kas",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "request setoran cabang",
                "actionLabel" => "setoran kas",
                "source" => "",
                "target" => "758r",
                "userGroup" => "sys",
                "stateLabel" => "pending acceptance",
                "stateColor" => "#dd3300",
                "stateCaption" => "initiated by",
            ),
            2 => array(
                "label" => "Penerimaan Setoran Kas",
                "actionLabel" => "receive",
                "source" => "758r",
                "target" => "758",
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
            //            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "branch",
            "nomer" => "receipt number",

            "details" => "invoice",
            "customerSetor" => "customer",
            "nilaiSetor" => "nilai",

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
        "historyFields" => array(
            1 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang2_nama" => "branch",
                "nomer" => "receipt number",

                "details" => "invoice",
                "customerSetor" => "customer",
                "nilaiSetor" => "nilai",

                "oleh_nama" => "person",
                "nilai_bayar" => "amount",
                "cash_account_source__label" => "bank account source",
                "cashMethode__label" => "target method account",
                "cash_account_target__label" => "bank account target",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang2_nama" => "branch",
                "nomer" => "receipt number",

                "details" => "invoice",
                "customerSetor" => "customer",
                "nilaiSetor" => "nilai",

                "oleh_nama" => "person",
                "nilai_bayar" => "amount",
                "cash_account_source__label" => "bank account source",
                "cashMethode__label" => "target method account",
                "cash_account_target__label" => "bank account target",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array(
                //                "review_details" => "id",
                "print_label" => "nomer",
            ),
        ),
        "extHistoryFields2" => array(
            1 => array(
                //                "details" => "nama",
                "details" => array(
                    "kolom" => "nama",
                    "format" => "nomer",
                ),
                "customerSetor" => array(
                    "kolom" => "extern2_nama",
                    "format" => "nama",
                ),
                "nilaiSetor" => array(
                    "kolom" => "nilai_bayar",
                    "format" => "debet",
                ),
            ),
            2 => array(
                //                "details" => "nama",
                "details" => array(
                    "kolom" => "nama",
                    "format" => "nomer",
                ),
                "customerSetor" => array(
                    "kolom" => "extern2_nama",
                    "format" => "nama",
                ),
                "nilaiSetor" => array(
                    "kolom" => "nilai_bayar",
                    "format" => "debet",
                ),
            ),
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields" => array(
            1 => array(
                "extern2_nama" => "customer",
                "nama" => "item name",
                //                "jml" => "qty",
            ),

        ),
        "shoppingCartFieldSrc" => array(
            "extern2_nama" => "customer",
            "nama" => "nomer",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",

        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "sisa" => "due remain",
            ),
        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            2 => "nilai_bayar",
        ),
        "shoppingCartSumFields" => array(
            2 => array(
                "subtotal" => "total amount",
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
            "main", "items"
        ),
        "revertException" => true,
        "canceledLabel" => array(
            1 => "Transaksi Penyetoran Kas ke Pusat nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}. Silahkan melakukan penyetoran ulang di cabang {cabang2_nama}",
        ),
    ),
    //uang muka
    "464" => array(
        "modul" => "kas",
        "icon" => "fa fa-cart-arrow-down",
        "label" => "uang muka ke supplier",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "uang muka ke supplier",
                "actionLabel" => "simpan",
                "source" => "",
                "target" => "464",
                "userGroup" => "c_finance",
                "stateLabel" => "complete",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
            ),
            2 => array(
                "label" => "approval uang muka ke supplier",
                "actionLabel" => "approve uang muka ke supplier",
                "source" => "464",
                "target" => "464a",
                "userGroup" => "c_finance",
                "stateLabel" => "uang muka approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "Approval by",
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlUangMuka",
        "selectorSrcModel" => "MdlUangMuka",
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
            "status=.1",
            "trash=.0",
            "ppn=.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array(
            "nama",
        ),
        "selectorProcessor" => "_processSelectBiaya/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor/supplier",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor/supplier",
            "nomer_top" => "nomer",
            "nomer" => "nomer approval",
            "oleh_nama" => "person",
            "dpp_pengganti" => "dpp",
            "ppn_pengganti" => "ppn",
            "nett" => "total",
            "option_nota__nama" => "po uang muka",
            "referensi_so__nomer" => "referensi po",
            "next_pic" => "Next step otorisator",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "nett" => "total amount",
            "keterangan" => "keterangan",
        ),
        "historyFields" => array(
            1 => array(
                "dtime" => "date",
                "suppliers_nama" => "vendor/supplier",
                "nomer_top" => "nomer",
                "oleh_nama" => "person",
                "cash_account__label" => "bank account",
//                "harga" => "amount",
                "dpp_pengganti" => "dpp",
                "ppn_pengganti" => "ppn",
                "nett" => "total amount",
                "selectedType_uangmuka" => array(
                    "label" => "UM Produk/Jasa",
                    "key_his" => "selectedType_uangmuka",
                ),
                "selectedType_konsumen" => array(
                    "label" => "Belum/Termasuk PPN",
                    "key_his" => "selectedType_konsumen",
                ),
                "referensi_so__nomer" => array(
                    "label" => "Referensi PO",
                    "key_his" => "referensi_so__nomer",
                    "format" => "nomer",
                ),
                "option_nota__nama" => array(
                    "label" => "PO Produk/Jasa/Project",
                    "key_his" => "option_nota__nama",
                ),
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
                "dtime" => "date",
                "suppliers_nama" => "vendor/supplier",
                "nomer_top" => "REQ Number",
                "nomer" => "Number",
                "oleh_nama" => "person",
                "cash_account__label" => "bank account",
//                "harga" => "amount",
                "dpp_pengganti" => "dpp",
                "ppn_pengganti" => "ppn",
                "nett" => "total amount",
                "dateFaktur" => "tanggal faktur",
                "eFaktur" => "nomer faktur",
//                "nett" => "total amount",
                "selectedType_uangmuka" => array(
                    "label" => "UM Produk/Jasa",
                    "key_his" => "selectedType_uangmuka",
                ),
                "selectedType_konsumen" => array(
                    "label" => "Belum/Termasuk PPN",
                    "key_his" => "selectedType_konsumen",
                ),
                "referensi_so__nomer" => array(
                    "label" => "Referensi PO",
                    "key_his" => "referensi_so__nomer",
                    "format" => "nomer",
                ),
                "option_nota__nama" => array(
                    "label" => "PO Produk/Jasa/Project",
                    "key_his" => "option_nota__nama",
                ),
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
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),

        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "Description",
                "jml" => "Qty",
            ),
            2 => array(
                "nama" => "Description",
                "jml" => "Qty",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
            ),
            2 => array(
                "harga" => "Unit Price",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "shoppingCartNoteEditabled" => array(
            1 => true,
            2 => true,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
            ),
            2 => array(
                "harga",
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "vendor/supplier ID",
            "pihakName" => "vendor/supplier name",
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*(harga-disc+ppn)",
            2 => "jml*(harga-disc+ppn)",
        ),
        "shoppingCartSumFields" => array(
            1 => array(

//                "dpp_nilai" => "DPP",
//                "ppn_pengganti" => "PPN",
//                "kas_nilai" => "<r class=\"meta\">kas</r> Dibayar",
            ),
            2 => array(
//                "total_diskon" => "diskon",
                "dpp_pengganti" => "DPP",
                "ppn_pengganti" => "PPN",
//                "grand_total" => "<r class=\"meta\">kas</r> Dibayar",
                "kas_nilai" => "<r class=\"meta\">kas</r> Dibayar",
            ),
        ),
        "shopingCartAddOption" => array(
            "fields" => array(
//                "non_ppn" => "non ppn",
                "uang_muka_produk" => "produk",
                "uang_muka_jasa" => "jasa",
            ),
            "inputType" => "checkbox",
            "selector" => "_selectorPihak/selectTax",
            "srcGateValue" => "harga",
            // "srcGateValue"=>"sisa",
            "label" => "tentukan uang muka produk/jasa",
            "key" => "selectedType_uangmuka",
        ),
        "shopingCartAddTax" => array(
            "fields" => array(
//                "non_ppn" => "non ppn",
                "include_ppn" => "termasuk ppn",
                "exclude_ppn" => "belum termasuk ppn",
            ),
            "inputType" => "checkbox",
            "selector" => "_selectorPihak/selectTax",
            "srcGateValue" => "harga",
            // "srcGateValue"=>"sisa",
            "label" => "tentukan uang muka termasuk/tidak termasuk ppn",
            "key" => "selectedType_konsumen",
        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "receiptElements" => array(
            "vendorDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "VENDOR/SUPPLIER",
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
            "cash_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "cash account",
                "showNull" => true,
                "nullSrc" => "balance",
                "nullValue" => "<span class='text-red text-bold'>{saldo kosong}</span>",
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
                        "kas",
                    ),
                    "fieldID" => "nilai",
                    "fieldLabel" => "saldo",
                ),
                "mdlName" => "MdlBankAccount_cash_and_in",
                "mdlFilter" => array(//                    "cabang_id=placeID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account",
                    "saldo" => "balance",
                    "folders" => "acountMasterID",
                    "folders_nama" => "accountMaster",
                ),
                "editPoints" => array(1),
                "pairMethod" => array(
                    "recom" => "ReComCashMethode",
                    "calculate" => array(
                        "source" => "cash_account",
                        "prefix" => "cashMethode",
                        "target" => "",
                    ),
                ),
                "labelValidate" => "Silahkan memilih sumber pembayaran sebelum melanjutkan transaksi.",
                "noValidate" => true,
            ),
            "referensiNota" => array(
//                "hiddenSelect" => true,
//                "hiddenBox" => true,
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "metode uang muka",
                "mdlName" => "MdlUangMukaSalesStatic",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "metode",
                    "keterangan" => "keterangan",
//                    "alamat_1" => "",
//                    "country" => "Country",
//                    "tlp_1" => "Phone",
//                    "tlp_2" => "Fax",
//                    //                    "npwp" => "NPWP",
//                    //                    "alias" => "Attn",
//                    "contact_person" => "Attn",
                ),
                "editPoints" => array(1, 2, 3),
                //untuk inject nilai dari triger element lihat di _Shopingcart->fetchElement
                "relInject" => array(
                    1 => array("selectedType_konsumen" => "exclude_ppn"),
//                    2=>array("selectedType_konsumen"=>"include_ppn"),
                ),
                "labelValidate" => "Silahkan memilih metode titipan atau uang muka.",
                "relResetor" => array(
                    1 => array(// titipan, exclude ppn
                        "uang_muka_tanpa_ppn_source_dipakai",
                    ),

                ),
            ),
//            "selectedTypeUangMuka" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "Jenis Uang Muka",
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

            "dummyElement" => array(
                "elementType" => "dataModel",
//                "hiddenSelect" => true,
                "hiddenBox" => true,
                "inputType" => "radio",
                "label" => "auto-validation",
                "mdlName" => "MdlDummyElement",
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",
                ),
                "hideRow" => false,
                "editPoints" => array(1, 2, 3),
            ),
        ),
        "relativeElements" => array(
            "referensiNota" => array(//ee
                2 => array(//ff
                    "option_nota" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "option po reguler/po project/po jasa/po supplies",
                        "mdlName" => "MdlUangMukaOptionPurchaseStatic",//ini klonengan mdltransaksi
                        "key" => "id",
                        "mdlFilter" => array(),
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "metode",
                            "jenis" => "kode",
//                            "id_master" => "mid",
//                            "id" => "referensi order",
//                            "fulldate" => "tgl order",
//                            "nomer" => "nomer po/order",
//                        "oleh_nama" => 'salesman'
                        ),
                        "editPoints" => array(1),
                        "labelValidate" => "Silahkan memilih PO yang sudah disetujui sesuai Supplier.",
                    ),

                    "referensi_so" => array(
                        "elementType" => "dataModel",
                        "inputType" => "combo",
                        "label" => "purchase order",
                        "mdlName" => "MdlTransaksi2",//ini klonengan mdltransaksi
                        "key" => "id",
                        "mdlFilter" => array(
                            "suppliers_id=pihakID",
                            "jenis=option_nota__jenis",
//                            "jenis=.466",
//                            "jenis in .(466,1466)",
                            "link_id=.0",
                        ),
                        "labelSrc" => "nomer",
                        "usedFields" => array(
                            "id_master" => "mid",
                            "id" => "referensi order",
                            "fulldate" => "tgl order",
                            "nomer" => "nomer po/order",
//                        "oleh_nama" => 'salesman'
                        ),
                        "editPoints" => array(1,),
                        "labelValidate" => "Silahkan memilih PO yang sudah disetujui sesuai Supplier.",
                    ),
                    "uangMukaTanpaPpnSource" => array(//gg
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Deposit (Uang Muka Tanpa PPN)",
                        "mdlName" => "MdlSupplierCreditUangMuka",
                        "mdlFilter" => array(
                            "extern_id=pihakID",
                            "cabang_id=cabangID",
                            "extern_label2=.vendor",
                            "sisa>.0",
                        ),
                        "key" => "id",
                        "labelSrc" => "sisa",
                        "usedFields" => array(
                            "extern_nama" => "vendor",
                            "extern_id" => "pihakID",
                            "extern_label2" => "tipe",
                            //                    "note" => "note",
                            //                    "id" => "rel_id",
                            //                    "transaksi_id" => "transaksi_id",
                            //                    "jenis" => "jenis",
                            //                    "extern2_id" => "label_id",
                            //                    "extern2_nama" => "label",
                        ),
                        "editPoints" => array(1,),
                        "noValidate" => true,
                        "noPrefetch" => true,
                        "pairMethod" => array(
                            "recom" => "ReComUangMuka",
                            "calculate" => array(
                                "source" => "uangMukaTanpaPpnSource",
                                "target" => "uang_muka_tanpa_ppn_source_dipakai",
                                "pair_source" => "nett",//sunbe sumber yang dibandingkan
                            ),
                        ),
                        "resetor" => array(
                            "uang_muka_tanpa_ppn_source_dipakai",
                        ),
                    ),
                ),
            ),
//            "selectedTypeUangMuka" => array(
//                "include_ppn" => array(
//                    "uangMukaTanpaPpnSource" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "Deposit (Uang Muka Tanpa PPN)",
//                        "mdlName" => "MdlSupplierCreditUangMuka",
//                        "mdlFilter" => array(
//                            "extern_id=pihakID",
//                            "cabang_id=cabangID",
//                            "extern_label2=.vendor",
//                            "sisa>.0",
//                        ),
//                        "key" => "id",
//                        "labelSrc" => "sisa",
//                        "usedFields" => array(
//                            "extern_nama" => "vendor",
//                            "extern_id" => "pihakID",
//                            "extern_label2" => "tipe",
//                            //                    "note" => "note",
//                            //                    "id" => "rel_id",
//                            //                    "transaksi_id" => "transaksi_id",
//                            //                    "jenis" => "jenis",
//                            //                    "extern2_id" => "label_id",
//                            //                    "extern2_nama" => "label",
//                        ),
//                        "editPoints" => array(1,),
//                        "noValidate" => true,
//                        "noPrefetch" => true,
//                        "pairMethod" => array(
//                            "recom" => "ReComUangMuka",
//                            "calculate" => array(
//                                "source" => "uangMukaTanpaPpnSource",
//                                "target" => "uang_muka_tanpa_ppn_source_dipakai",
//                                "pair_source" => "nett",//sunbe sumber yang dibandingkan
//                            ),
//                            //                    "customer" => "ReComDiscCustomer",
//                        ),
//                    ),
//                ),
//            ),


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
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "dpp_pengganti" => array(
                        "label" => "Dpp pengganti",
                        "defaultValue" => "dpp_nilai_pengganti",
//                        "saldo" => "uangMuka__sisa",
                        'disabled' => "disabled",
//                        'disabled' => "",
                        "addPoints" => array(1,),
                    ),
                    "ppn_pengganti" => array(
                        "label" => "ppn ",
//                        "label" => "ppn pengganti",
                        "defaultValue" => "ppn_nilai_pengganti",
//                        "saldo" => "uangMuka__sisa",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "add_source_uang_muka_dipakai" => array(
                        "label" => "Uang Muka Tanpa PPN",
                        "defaultValue" => "uang_muka_tanpa_ppn_source_dipakai",
//                        "saldo" => "uangMuka__sisa",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "kas_nilai" => array(
                        "label" => "dibayar",
                        "defaultValue" => "kas_nilai",
//                        "saldo" => "uangMuka__sisa",
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
        "validatePaymentSource" => array(//            "3" => "MdlLockerValue",
        ),
        "allowedMainEdit" => array(1, 2,),
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi Request Uang Muka nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}. Silahkan melakukan Request Uang Muka ulang di {cabang_nama}",
            2 => "Transaksi Request Uang Muka nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}. Silahkan melakukan Request Uang Muka ulang di {cabang_nama}",
        ),
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "464re",
                "label" => "EDIT request uang muka",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "464rrj",
                "label" => "REJECT request uang muka",
            ),
        ),
        //----
        "addMainSource" => array(
            2 => array(
                "fields" => array(
                    "nomer" => "INV",
                    "dpp_pengganti" => "DPP",
//                    "ppn_nilai" => "PPN (belum ada faktur)",
//                    "ppn_realisasi" => "PPN Realisasi (diisi dalam rupiah)",
                    "ppn_pengganti" => "PPN Realisasi",
                    "dateFaktur" => "Tgl faktur",
                    "eFaktur" => "Nomor e-faktur",
                ),
                "editableFields" => array(
//                    "harga" => "number",
//                    "ppn_realisasi" => "number",
                    "eFaktur" => "text",
                    "dateFaktur" => "date",
                ),
            ),
        ),
        "receiptEdit" => array(
            2 => true,
        ),
        "efakturValidator" => array(
            2 => array(
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
        "autoNextStepByValue" => array(
            1 => array(
                "auto" => true,
                "key" => "ppn_nilai",
                "nilai" => 0,// kalau bernilai 0 maka auto approve
            ),
        ),
    ),
    //  uang muka valas
    "4466" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "uang muka (valas)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "request uang muka (valas)",
                "actionLabel" => "make request uang muka (valas)",
                "source" => "",
                "target" => "4466r",
                "userGroup" => "c_finance",
                "stateLabel" => "need approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
            ),
            2 => array(
                "label" => "approval uang muka (valas)",
                "actionLabel" => "approve uang muka (valas)",
                "source" => "4466r",
                "target" => "4466",
                "userGroup" => "c_finance",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "Approval by",
            ),
        ),
        "template" => "template/transaksi_uangmuka_valas.html",
        "selectorModel" => "MdlUangMuka",
        "selectorSrcModel" => "MdlUangMuka",
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
            "status=.1",
            "trash=.0",
            //            "ppn=.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array(
            "nama",
        ),
        "selectorProcessor" => "_processSelectValas/select",
        "editHandlerMethod" => "select",
        // ==============
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakFilters" => array(
            "country<>.ID",
            //            "id=.-1",
        ),
        "pihakProcessor" => "_processPihak/select",
        // ==============
        "pihakModel2" => "MdlCurrency",
        "pihakCaller2" => "_selectorPihak/selectPihak2",
        "pihakLabel2" => "pilih mata uang asing",
        "pihakProcessor2" => "_processPihak/select2",
        "pihakFilters2" => array(),
        // ==============
        "pihakModel3" => "MdlCashMethodeStatic",
        "pihakCaller3" => "_selectorPihak/selectPihak3UM",
        "pihakLabel3" => "pilih sumber pembayaran",
        "pihakProcessor3" => "_processPihak/select3UM",
        "pihakFilters3" => array(//            "trash=.0",
        ),
        // ==============
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "pihak2Name" => "exchange",
            "harga" => "amount",
            //            "harga" => "total amount",
            "next_pic" => "Next step otorisator",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "nett" => "total amount",
            "keterangan" => "keterangan",
        ),
        "historyFields" => array(
            1 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "Number",
                "oleh_nama" => "person",
                "cash_account__label" => "bank account",
                "harga" => "amount",
                "nett" => "total amount",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "REQ Number",
                "nomer" => "Number",
                "oleh_nama" => "person",
                "cash_account__label" => "bank account",
                "harga" => "amount",
                "nett" => "total amount",
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
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "pihak2Name" => "pihak2Name",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "Description",
                "pihak2Name" => "Exchange",
                "jml" => "Qty",
            ),
            2 => array(
                "nama" => "Description",
                "pihak2Name" => "Exchange",
                "jml" => "Qty",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(//                "harga" => "Unit Price",
            ),
            2 => array(//                "harga" => "Unit Price",
            ),
        ),
        "shoppingCartNoteEnabled" => false,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartImageEnabled" => false,
        "shoppingCartImageType" => "images",
        "shoppingCartNoteEditabled" => array(
            1 => true,
            2 => true,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "jml",
                //                "harga",
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "vendor ID",
            "pihakName" => "vendor name",
            "pihak2ID" => "mata uang asing",
        ),
        "shoppingCartAmountValue" => array(
            // 1 => "sub_harga",
            // 2 => "sub_harga",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    // stock valas
                    "valas_nilai_bayar" => array(
                        "label" => "stock valas",
                        "defaultValue" => ".0",
                        "keyupAction" => "
    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('valas_harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('valas_harus_bayar').value;}
                            ",
                        //                        'disabled'     => "disabled",
                        "addPoints" => array(1,),
                    ),
                    // kekurangan valas
                    "valas_kurang" => array(
                        "label" => "kekurangan valas",
                        "defaultValue" => "valas_kurang",
                        "maxValue" => "valas_kurang",
                        "minValue" => "valas_kurang",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        //                        "hideRow" => true,
                    ),
                    // kurs actual
                    "kurs_actual" => array(
                        "label" => "kurs saat ini",
                        "defaultValue" => "kurs_actual",
                        "maxValue" => "",
                        "minValue" => "",
                        "keyPressAction" => "
    if(parseInt(removeCommas(this.value))<0){this.value=document.getElementById('kurs_actual').value;}
                        ",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),

                    ),
                    //--tambahan--biaya lain-lain yang mempengaruhi valas---------------------
                    "biaya_lain_lain" => array(
                        "label" => "biaya lain-lain",
                        "defaultValue" => "0",
                        "maxValue" => "",
                        "minValue" => "",
                        "keyPressAction" => "",
                        //                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    //-total pembelian valas------------------------
                    "valas_kurang_nilai" => array(
                        "label" => "nilai total pembelian valas",
                        "defaultValue" => "valas_kurang_nilai",
                        "maxValue" => "valas_kurang_nilai",
                        "minValue" => "valas_kurang_nilai",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        //                        "hideRow" => true,
                    ),
                    //--tambahan--biaya transfer---------------------
                    "biaya_transfer" => array(
                        "label" => "biaya transfer",
                        "defaultValue" => "0",
                        "maxValue" => "",
                        "minValue" => "",
                        "keyPressAction" => "",
                        //                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    //--tambahan--biaya lain-lain diluar valas---------------------
                    "biaya_lain_lain_novalas" => array(
                        "label" => "biaya lain-lain (tidak berkaitan dengan valas)",
                        "defaultValue" => "0",
                        "maxValue" => "",
                        "minValue" => "",
                        "keyPressAction" => "",
                        //                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    //--tambahan--total pembayaran---------------------
                    "total_bayar" => array(
                        "label" => "total bayar",
                        "defaultValue" => "total_bayar",
                        "maxValue" => "total_bayar",
                        "minValue" => "total_bayar",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),


                    // --- hide
                    "valas_dipakai" => array(
                        "label" => "stock valas (idr)",
                        "defaultValue" => "valas_dipakai",
                        "maxValue" => "valas_dipakai",
                        "minValue" => "valas_dipakai",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        "hideRow" => true,
                    ),
                    "valas_sisa" => array(
                        "label" => "total amount (idr)",
                        "defaultValue" => "valas_sisa",
                        "maxValue" => "valas_sisa",
                        "minValue" => "valas_sisa",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        "hideRow" => true,
                    ),
                ),
                "cash" => array(
                    // kekurangan valas
                    "valas_kurang" => array(
                        "label" => "kekurangan valas",
                        "defaultValue" => "valas_kurang",
                        "maxValue" => "valas_kurang",
                        "minValue" => "valas_kurang",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        //                        "hideRow" => true,
                    ),
                    // kurs actual
                    "kurs_actual" => array(
                        "label" => "kurs saat ini",
                        "defaultValue" => "kurs_actual",
                        "maxValue" => "",
                        "minValue" => "",
                        "keyPressAction" => "
    if(parseInt(removeCommas(this.value))<0){this.value=document.getElementById('kurs_actual').value;}
                        ",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),

                    ),
                    //--tambahan--biaya lain-lain yang mempengaruhi valas---------------------
                    "biaya_lain_lain" => array(
                        "label" => "biaya lain-lain",
                        "defaultValue" => "0",
                        "maxValue" => "",
                        "minValue" => "",
                        "keyPressAction" => "",
                        //                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    //-total pembelian valas------------------------
                    "valas_kurang_nilai" => array(
                        "label" => "nilai total pembelian valas",
                        "defaultValue" => "",
                        "maxValue" => "",
                        "minValue" => "",
                        "keyPressAction" => "",
                        //                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        //                        "hideRow" => true,
                    ),
                    //--tambahan--biaya transfer---------------------
                    "biaya_transfer" => array(
                        "label" => "biaya transfer",
                        "defaultValue" => "0",
                        "maxValue" => "",
                        "minValue" => "",
                        "keyPressAction" => "",
                        //                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    //--tambahan--biaya lain-lain diluar valas---------------------
                    "biaya_lain_lain_novalas" => array(
                        "label" => "biaya lain-lain (tidak berkaitan dengan valas)",
                        "defaultValue" => "0",
                        "maxValue" => "",
                        "minValue" => "",
                        "keyPressAction" => "",
                        //                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    //--tambahan--total pembayaran---------------------
                    "total_bayar" => array(
                        "label" => "total bayar",
                        "defaultValue" => "total_bayar",
                        "maxValue" => "total_bayar",
                        "minValue" => "total_bayar",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),

                    // --- hide
                    "valas_dipakai" => array(
                        "label" => "stock valas (idr)",
                        "defaultValue" => "valas_dipakai",
                        "maxValue" => "valas_dipakai",
                        "minValue" => "valas_dipakai",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        "hideRow" => true,
                    ),
                    "valas_sisa" => array(
                        "label" => "total amount (idr)",
                        "defaultValue" => "valas_sisa",
                        "maxValue" => "valas_sisa",
                        "minValue" => "valas_sisa",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        "hideRow" => true,
                    ),
                ),
                "valas" => array(
                    // stock valas
                    "valas_nilai_bayar" => array(
                        "label" => "stock valas",
                        "defaultValue" => ".0",
                        "keyupAction" => "
    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('valas_harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('valas_harus_bayar').value;}
                            ",
                        //                        'disabled'     => "disabled",
                        "addPoints" => array(1,),
                    ),
                    // kekurangan valas
                    "valas_kurang" => array(
                        "label" => "kekurangan valas",
                        "defaultValue" => "valas_kurang",
                        "maxValue" => "valas_kurang",
                        "minValue" => "valas_kurang",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        //                        "hideRow" => true,
                    ),
                    //--tambahan--biaya transfer---------------------
                    "biaya_transfer" => array(
                        "label" => "biaya transfer",
                        "defaultValue" => "0",
                        "maxValue" => "",
                        "minValue" => "",
                        "keyPressAction" => "",
                        //                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    //--tambahan--biaya lain-lain diluar valas---------------------
                    "biaya_lain_lain_novalas" => array(
                        "label" => "biaya lain-lain (tidak berkaitan dengan valas)",
                        "defaultValue" => "0",
                        "maxValue" => "",
                        "minValue" => "",
                        "keyPressAction" => "",
                        //                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),

                    // --- hide
                    "valas_dipakai" => array(
                        "label" => "stock valas (idr)",
                        "defaultValue" => "valas_dipakai",
                        "maxValue" => "valas_dipakai",
                        "minValue" => "valas_dipakai",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        "hideRow" => true,
                    ),
                    "valas_sisa" => array(
                        "label" => "total amount (idr)",
                        "defaultValue" => "valas_sisa",
                        "maxValue" => "valas_sisa",
                        "minValue" => "valas_sisa",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        "hideRow" => true,
                    ),
                ),
            ),
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

            "cash_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "cash account",
                "showNull" => true,
                "nullSrc" => "balance",
                "nullValue" => "<span class='text-red text-bold'>{saldo kosong}</span>",
                "pairedModel" => array(
                    "mdlName" => "ComLockerValue",
                    "mdlMethod" => "fetchBalances",
                    "mdlFilter" => array(
                        "cabang_id" => "placeID",
                        "state" => ".active",
                    ),
                    "key" => "produk_id",
                    "rekening" => array(
                        "kas", "plafon hutang bank",
                    ),
                    "fieldID" => "nilai",
                    "fieldLabel" => "saldo",
                ),
                "mdlName" => "MdlBankAccount_cash_and_in_and_koran",
                "mdlFilter" => array(
                    "cabang_id=placeID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account",
                    "saldo" => "balance",
                    "folders" => "acountMasterID",
                    "folders_nama" => "accountMaster",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
                "pairMethod" => array(
                    "recom" => "ReComCashMethode",
                    "calculate" => array(
                        "source" => "cash_account",
                        "prefix" => "cashMethode",
                        "target" => "",
                    ),
                ),
            ),
            "valas_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "valas",
                "pairedModel" => array(
                    "mdlName" => "ComLockerValue",
                    "mdlMethod" => "fetchBalances",
                    "mdlFilter" => array(
                        "cabang_id" => "placeID",
                        "state" => ".active",
                    ),
                    "key" => "produk_id",
                    "rekening" => "valas",
                    "fieldID" => "nilai",
                    "fieldLabel" => "saldo",
                ),
                "mdlName" => "MdlCurrency",
                "mdlFilter" => array(
                    "id=pihak2ID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account",
                    "saldo" => "balance",
                    "folders" => "acountMasterID",
                    "folders_nama" => "accountMaster",
                ),
                "editPoints" => array(1),
                "noValidate" => true,
            ),

            "dummyElement" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "auto-validation",
                "mdlName" => "MdlDummyCashStatic",
                "mdlFilter" => array("id=pihak3ID"),// hanya bisa 1 filter
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",
                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),
        "relativeElements" => array(
            //            "cashMethodeOption" => array(
            //                "cash" => array(
            //                    "cash_account" => array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "radio",
            //                        "label" => "cash account",
            //                        "showNull" => true,
            //                        "nullSrc" => "balance",
            //                        "nullValue" => "<span class='text-red text-bold'>{saldo kosong}</span>",
            //                        "pairedModel" => array(
            //                            "mdlName" => "ComLockerValue",
            //                            "mdlMethod" => "fetchBalances",
            //                            "mdlFilter" => array(
            //                                "cabang_id" => "placeID",
            //                                "state" => ".active",
            //                            ),
            //                            "key" => "produk_id",
            //                            "rekening" => array(
            //                                "kas", "plafon hutang bank",
            //                            ),
            //                            "fieldID" => "nilai",
            //                            "fieldLabel" => "saldo",
            //                        ),
            //                        "mdlName" => "MdlBankAccount_cash_and_in_and_koran",
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
            //                        "noValidate" => false,
            //                        "pairMethod" => array(
            //                            "recom" => "ReComCashMethode",
            //                            "calculate" => array(
            //                                "source" => "cash_account",
            //                                "prefix" => "cashMethode",
            //                                "target" => "",
            //                            ),
            //                        ),
            //                    ),
            //
            //                ),
            //                "valas" => array(
            //                    "cash_account" => array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "radio",
            //                        "label" => "valas",
            //                        "pairedModel" => array(
            //                            "mdlName" => "ComLockerValue",
            //                            "mdlMethod" => "fetchBalances",
            //                            "mdlFilter" => array(
            //                                "cabang_id" => "placeID",
            //                                "state" => ".active",
            //                            ),
            //                            "key" => "produk_id",
            //                            "rekening" => "valas",
            //                            "fieldID" => "nilai",
            //                            "fieldLabel" => "saldo",
            //                        ),
            //                        "mdlName" => "MdlCurrency",
            //                        "mdlFilter" => array(
            //                            "id=pihak2ID",
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
            //                    ),
            //                ),
            //            ),
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
        "validatePaymentSource" => array(//            "3" => "MdlLockerValue",
        ),
        //        "allowedMainEdit" => array(1, 2,),
        //--------------------------------------------
        // bila key lebih dari 0, value harus ada dan lebih dari 0
        "shoppingCartPaymentValidator" => array(
            "validate" => array(
                "valas_nilai_bayar" => "valas_account",
                "valas_kurang" => "kurs_actual",
                "kurs_actual" => "cash_account",
            ),
            "label" => array(
                "valas_nilai_bayar" => "Account Stock Valas belum dipilih. Silahkan dipilih dahulu.",
                "valas_kurang" => "kurs beli belum diisi. Silahkan diisi dahulu.",
                "kurs_actual" => "Account Bank belum dipilih. Silahkan dipilih dahulu.",
            ),
        ),
        // sifat validasi ini bila key lebih dari value, maka dimatikan.
        "shoppingCartPaymentNilaiValidator" => array(
            "validate" => array(
                "valas_nilai_bayar" => array(
                    "total_amount" => "total_amount",
                    "valas_account__saldo" => "valas_account__saldo",
                ),
            ),
            "label" => array(
                "valas_nilai_bayar" => array(
                    "total_amount" => "stok valas yang digunakan melebihi uang muka.",
                    "valas_account__saldo" => "stok valas yang digunakan melebihi stok yang tersedia.",
                ),
            ),
        ),
        //--------------------------------------------
        "dummyElementResetor" => array(
            "valas_nilai_bayar",
            "kurs_actual",
            "biaya_lain_lain",
            "biaya_transfer",
            "biaya_lain_lain_novalas",
            "total_bayar",
            "valas_dipakai",
            "valas_sisa",
            "valas_kurang_nilai",
        ),
        "saldoLocker" => array(
            "enabled" => true,
            "pairModel" => array(
                "cash" => array(
                    "mdlName" => "MdlLockerValue",
                    "mdlFilter" => array(
                        "state=.active",
                        "jenis=.kas",
                        "cabang_id=placeID",
                        "produk_id=cash_account",
                    ),
                    "sesKey" => "cash_account__nama",
                    //                    "target" => "total_bayar",
                    "label" => "Saldo kas tidak cukup untuk melanjutkan transaksi ini. Silahkan mengisi saldo kas atau melakukan edit.",
                ),
                "valas" => array(
                    "mdlName" => "MdlLockerValue",
                    "mdlFilter" => array(
                        "state=.active",
                        "jenis=.valas",
                        "cabang_id=placeID",
                        "produk_id=valas_account",
                    ),
                    "sesKey" => "valas_account__nama",
                    //                    "target" => "valas_nilai_bayar",
                    "label" => "Saldo valas tidak cukup untuk melanjutkan transaksi ini. Silahkan mengisi saldo valas atau melakukan edit.",
                ),
            ),
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "4466re",
                "label" => "EDIT request uang muka (valas)",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "4466rrj",
                "label" => "REJECT request uang muka (valas)",
            ),
        ),
    ),
    //intercahge Ho to Brance
    "453" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "transfer cash to brance",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "transfer cashrequest",
                "actionLabel" => "save",
                "source" => "",
                "target" => "453r",
                "userGroup" => "c_finance",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "transfer cash authorization",
                "actionLabel" => "approve request",
                "source" => "453r",
                "target" => "453",
                "userGroup" => "c_finance_spv",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),

        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlKasStatic",
        "selectorSrcModel" => "MdlKasStatic",
        "selectedPrice" => array(),
        "lockerCheck" => array(),
        "selectorFilters" => array(),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
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
        //        "selectorProcessor"       => "_processSelectProduct/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            //            "id=cabang_id",
            "id<>cabang_id",
            //            "id=.-1",
        ),
        "pihakMainValueSrc" => array(//            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",

        "shortStepHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "sender",
            "cabang_nama" => "recipient",
            "583r" => "request number",
            "583" => "approval number",
            //            "585r" => "request number",
            "585" => "receipt number",

            "oleh_nama" => "person",
            "next_pic" => "next step otorisator",
        ),
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer_top" => "PRE-PO number",
            //            "nomer" => "receipt number",
            "nomer_po" => array(
                "step" => 2,
                "key" => "nomer",
                "label" => "PO number",
            ),
            "nomer_grn" => array(
                "step" => 3,
                "key" => "nomer",
                "label" => "GRN number",
            ),
            "oleh_nama" => "person",
            "transaksi_nilai" => "price",
            //            "ppn"             => "ppn",
            //            "other"           => "other (+)",
            "grand_total" => "total amount",
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
                "keterangan" => "keterangan",
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
            //            "ppn_persen" => "ppn_persen",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",
                //                "reference" => "reference",
                //                "ppn_persen" => "vat(%)",
            ),
            2 => array(
                "nama" => "item name",
                "jml" => "qty",
                //                "reference" => "reference",
            ),
            3 => array(
                "nama" => "item name",
                "jml" => "qty",
                //                "reference" => "reference",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "Price",
                //                "non_ppn" => "Non PPN<br>PPN (-)",
                //                "other" => "other (+)",
            ),
            2 => array(
                "harga" => "Price",
                //                "other" => "other (+)",
            ),
            3 => array(
                //                "harga" => "Price",
                //                "ppn" => "VAT",
            ),
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                //                "harga_other" => "Total Amount",
                //                // "ppv" => "index",
                //                "dpp_vat"     => "DPP VAT",
                //                "ppn"         => "VAT",
                ////                "non_ppn" => "Total Non PPN",
                ////                "other" => "Total Other",
                //                "hpp_nppn"    => "Grand Total",
            ),
            2 => array(
                //                "harga_other" => "Total Amount",
                //                // "ppv" => "index",
                //                "dpp_vat"     => "DPP VAT",
                //                "ppn"         => "VAT",
                //                "hpp_nppn"    => "Grand Total",
            ),
            3 => array(
                //                "harga" => "Total Amount",
                //                // "ppv" => "index",
                //                "ppn" => "VAT",
                //                "hpp_nppn" => "Grand Total",
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
                //                "jml",
                //                "non_ppn",
                //                "other",
                //                "ppn_persen",
                //                "reference",
            ),
            2 => array(
                "harga",
            ),

        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga+(jml*other)",
            2 => "jml*harga+(jml*other)",
        ),
        "shoppingCartImageEnabled" => false,
        "shoppingCartImageType" => "images",
        "shoppingCartHideSubamount" => array(
            1 => false,
            2 => false,
            3 => true,
        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),

        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "pihak ID",
            "pihakName" => "pihak name",
        ),
        "receiptElements" => array(
            "cash_account_source" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "cash account source",
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
                "labelValidate" => "Silahkan memilih sumber pembayaran milik kantor pusat sebelum melanjutkan transaksi.",
            ),
            "cash_account_target" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "cash account target",
                "mdlName" => "MdlBankAccount_cash_and_in",
                "mdlFilter" => array(
                    "cabang_id=pihakID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account",
                    "saldo" => "balance",
                ),
                "editPoints" => array(1,),
                "noValidate" => false,
                "labelValidate" => "Silahkan memilih target rekening bank milik cabang sebelum melanjutkan transaksi.",
            ),
            "gudang_target" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "target warehouse",
                "mdlName" => "MdlGudangDefault",
                "mdlFilter" => array("cabang_id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1, 2, 3),

            ),
        ),
        "relativeElements" => array(),
        "relativeOptions" => array(),
        "connectTo" => "454",

        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "453re",
                "label" => "EDIT transfer cashrequest",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "453rrj",
                "label" => "REJECT transfer cashrequest",
            ),
        ),
    ),
    "454" => array(
        "icon" => "fa fa-ship",
        "label" => "cash reception",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "cash initiation",
                "actionLabel" => "init reception",
                "source" => "",
                "target" => "454r",
                "userGroup" => "sys",
                "stateLabel" => "pending acceptance",
                "stateColor" => "#dd3300",
                "stateCaption" => "received by",
            ),
            2 => array(
                "label" => "cash reception",
                //                "label" => "distribusi",
                "actionLabel" => "receive",
                "source" => "454r",
                "target" => "454",
                "userGroup" => "o_finance",
                "stateLabel" => "stock received",
                "stateColor" => "#009900",
                "stateCaption" => "received by",
            ),

        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlLockerStock",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jumlah>.0",
            "state=.active",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "produk_id",
            "nama" => "nama",
            "satuan" => "satuan",
            "jumlah" => "jumlah",
        ),
        "selectorViewedFields" => array(
            "nama",
            "satuan",
            "jumlah",
        ),

        "selectorProcessor" => "_processSelectProductStock/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            // "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "sender",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "shortStatusFields" => array(
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
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        "historyFields" => array(
            1 => array(
                // "jenis_label" => "activity",
                "dtime" => "date",
                "cabang2_nama" => "sender",
                // "nomer_top" => "SO number",
                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
                // "jenis_label" => "activity",
                "dtime" => "date",
                "cabang2_nama" => "sender",
                // "nomer_top" => "SO number",
                "nomer" => "GRN Number",
                "oleh_nama" => "person",
                "keterangan" => "keterangan",
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
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "sender",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),


        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "produk_kode" => "product code",
                "jml" => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
            2 => array(
                "nama" => "item name",
                "produk_kode" => "product code",
                "jml" => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "label" => "label",
            "satuan" => "satuan",
            //"berat"         => "berat",
            //          "lebar"         => "lebar",
            //        "panjang"       => "panjang",
            //      "tinggi"        => "tinggi",
            //    "volume"        => "volume",
            "berat_gross" => "berat_gross",
            "lebar_gross" => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross" => "tinggi_gross",
            "volume_gross" => "volume_gross",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
            2 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),
        "resumeFieldNames" => array(
            "selectFields" => "cabang2_nama",
            "title" => "branch",
        ),
        "settlementHistoryFields" => array(
            "fulldate" => "time",
            "nomer" => "receipt number",
            "cabang2_nama" => "sender",
            "cabang_nama" => "recipient",
            "jenis_label" => "activity",
            "transaksi_nilai" => "orig. value",
            //            "add_disc"        => "discount",
            //            "grand_total"     => "nett",
        ),
        "revertException" => "true",
        "previewCtr" => "Create",
    ),

    // uang muka dari konsumen
    "4464_OLD_" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "uang muka (dp tanpa ppn)",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "request uang muka (dp tanpa ppn)",
                "actionLabel" => "make request uang muka",
                "source" => "",
                "target" => "4464r",
                "userGroup" => "o_finance",
                "stateLabel" => "uang muka need approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
            ),
            2 => array(
                "label" => "approval uang muka (dp tanpa ppn)",
                "actionLabel" => "approve uang muka",
                "source" => "4464r",
                "target" => "4464",
                "userGroup" => "o_finance",
                "stateLabel" => "uang muka approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "Approval by",
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlUangMuka",
        "selectorSrcModel" => "MdlUangMuka",
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
            "status=.1",
            "trash=.0",
            "ppn=.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array(
            "nama",
        ),
        "selectorProcessor" => "_processSelectBiaya/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCustomer_and_pre",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "cash_account__label" => "bank account",
//            "harga" => "amount",
            "nett" => "amount",
            "next_pic" => "Next step otorisator",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "nett" => "total amount",
            "keterangan" => "keterangan",
        ),

        "historyFields" => array(
            1 => array(
//                "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "customers_nama" => "customer",
                "nomer_top" => "Number",
                "oleh_nama" => "person",
                "cash_account__label" => "bank account",
//                "harga" => "amount",
                "nett" => "amount",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
//                "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "customers_nama" => "customer",
                "nomer_top" => "REQ Number",
                "nomer" => "Number",
                "oleh_nama" => "person",
                "cash_account__label" => "bank account",
//                "harga" => "amount",
                "nett" => "amount",
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
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),

        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "Description",
                "jml" => "Qty",
            ),
            2 => array(
                "nama" => "Description",
                "jml" => "Qty",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
            ),
            2 => array(
                "harga" => "Unit Price",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "shoppingCartNoteEditabled" => array(
            1 => true,
            2 => true,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
            ),
            2 => array(
                "harga",
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
        ),

        "pairRegistries" => array(
            "main",
        ),
        "receiptElements" => array(
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "CUSTOMER DETAILS",
                "mdlName" => "MdlCustomer_and_pre",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
//                    "nama" => "",
//                    "alamat_1" => "",
//                    "country" => "Country",
//                    "tlp_1" => "Phone",
//                    "tlp_2" => "Fax",
//                    "npwp" => "NPWP",
//                    "alias" => "Attn",
//                    "contact_person" => "Attn",
                    "nama" => "nama",
                    "alamat_1" => "alamat",
                    "alamat_2" => "alamat",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Tlp",
                    "tlp_1" => "Tlp",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    "nik" => "NIK",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            "cash_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "cash account",
                "mdlName" => "MdlBankAccount_cash_and_in",
                "mdlFilter" => array(
                    "cabang_id=placeID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account number",
                    "alias" => "holder alias",
                    //                    "saldo" => "balance",
                ),
                "editPoints" => array(1, 2,),
//                "noValidate" => true,
                "pairMethod" => array(
                    "recom" => "ReComCashAccountJenis",
                    "calculate" => array(
                        "source" => "cash_account",
                        "filter" => array(
                            "cabang_id=placeID",
                        ),
                        "result" => array(
                            "nilai_setoran_tunai" => "nett",
                        ),
                    ),
                ),
                "labelValidate" => "Silahkan memilih rekening bank tujuan penerimaan uang muka sebelum melanjutkan transaksi.",
            ),
        ),
        "relativeElements" => array(
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
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "settlementHistoryFields" => array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "customers_nama" => "customer",
            "jenis_label" => "activity",
            "transaksi_nilai" => "orig. value",
            "add_disc" => "discount",
            "grand_total" => "nett",
        ),
        "validatePaymentSource" => array(//            "3" => "MdlLockerValue",
        ),
        "allowedMainEdit" => array(1, 2,),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "4464re",
                "label" => "EDIT request uang muka (dp tanpa ppn)",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "4464rrj",
                "label" => "REJECT request uang muka (dp tanpa ppn)",
            ),
        ),
    ),
    // uang muka dari konsumen dengan ppn
    "4465" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "uang muka (dp dengan ppn)",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "request uang muka (dp dengan ppn)",
                "actionLabel" => "make request uang muka",
                "source" => "",
                "target" => "4465r",
                "userGroup" => "o_finance",
                "stateLabel" => "uang muka need approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
            ),
            2 => array(
                "label" => "approval uang muka (dp dengan ppn)",
                "actionLabel" => "approve uang muka",
                "source" => "4465r",
                "target" => "4465",
                "userGroup" => "o_finance",
                "stateLabel" => "uang muka approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "Approval by",
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlUangMuka",
        "selectorSrcModel" => "MdlUangMuka",
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
            "status=.1",
            "trash=.0",
            "ppn=.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array(
            "nama",
        ),
        "selectorProcessor" => "_processSelectBiaya/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCustomer_and_pre",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "cash_account__label" => "bank account",
            "uang_muka_dpp" => "DPP",
            "uang_muka_ppn" => "PPN",
//            "harga" => "amount",
            "nett" => "amount",
            "next_pic" => "Next step otorisator",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "nett" => "total amount",
            "keterangan" => "keterangan",
        ),

        "historyFields" => array(
            1 => array(
//                "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "customers_nama" => "customer",
                "nomer_top" => "Number",
                "oleh_nama" => "person",
                "cash_account__label" => "bank account",
                "uang_muka_dpp" => "DPP",
                "uang_muka_ppn" => "PPN",
//                "harga" => "amount",
                "nett" => "amount",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
//                "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "customers_nama" => "customer",
                "nomer_top" => "REQ Number",
                "nomer" => "Number",
                "oleh_nama" => "person",
                "cash_account__label" => "bank account",
                "uang_muka_dpp" => "DPP",
                "uang_muka_ppn" => "PPN",
//                "harga" => "amount",
                "nett" => "amount",
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
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),

        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "uang_muka_dpp" => "uang_muka_dpp",
            "uang_muka_ppn" => "uang_muka_ppn",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "Description",
                "jml" => "Qty",
            ),
            2 => array(
                "nama" => "Description",
                "jml" => "Qty",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "uang_muka_dpp" => "DPP",
                "uang_muka_ppn" => "PPN",
                "harga" => "Unit Price",
            ),
            2 => array(
                "uang_muka_dpp" => "DPP",
                "uang_muka_ppn" => "PPN",
                "harga" => "Unit Price",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "shoppingCartNoteEditabled" => array(
            1 => true,
            2 => true,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
            ),
            2 => array(
                "harga",
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
        ),

        "pairRegistries" => array(
            "main",
        ),
        "receiptElements" => array(
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "CUSTOMER DETAILS",
                "mdlName" => "MdlCustomer_and_pre",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                    "alamat_1" => "alamat",
                    "alamat_2" => "alamat",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Tlp",
                    "tlp_1" => "Tlp",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    "nik" => "NIK",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            "cash_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "cash account",
                "mdlName" => "MdlBankAccount_cash_and_in",
                "mdlFilter" => array(
                    "cabang_id=placeID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account number",
                    "alias" => "holder alias",
                    //                    "saldo" => "balance",
                ),
                "editPoints" => array(1, 2,),
//                "noValidate" => true,
                "pairMethod" => array(
                    "recom" => "ReComCashAccountJenis",
                    "calculate" => array(
                        "source" => "cash_account",
                        "filter" => array(
                            "cabang_id=placeID",
                        ),
                        "result" => array(
                            "nilai_setoran_tunai" => "nett",
                        ),
                    ),
                ),
            ),
        ),
        "relativeElements" => array(
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
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "settlementHistoryFields" => array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "customers_nama" => "customer",
            "jenis_label" => "activity",
            "transaksi_nilai" => "orig. value",
            "add_disc" => "discount",
            "grand_total" => "nett",
        ),
        "validatePaymentSource" => array(//            "3" => "MdlLockerValue",
        ),
        "allowedMainEdit" => array(1, 2,),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "4465re",
                "label" => "EDIT request uang muka (dp dengan ppn)",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "4465rrj",
                "label" => "REJECT request uang muka (dp dengan ppn)",
            ),
        ),
    ),
    // setoran uang muka dari konsumen
    "7759" => array(
        "icon" => "fa fa-money",
        "label" => "Penyetoran Uang Muka Konsumen",
        "place" => "branch",
        "paymentConfig" => true,
        "steps" => array(
            1 => array(
                "label" => "setoran uang muka konsumen",
                "actionLabel" => "penyetoran",
                "source" => "",
                "target" => "7759r",
                "userGroup" => "o_finance",
                "stateLabel" => "prepare by",
                "stateColor" => "#dd3300",
            ),
        ),
        "template" => "template/transaksi_payment.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.753",
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
            //            "jenis_label"                => "activity",
            "dtime" => "date",
            //            "customers_nama"             => "customer",
            "nomer" => "request number",
            "details" => "detail",
            "oleh_nama" => "person",
            "nilai_bayar" => "amount",
            "cash_account_source__label" => "bank account source",
            "cash_account_target__label" => "bank account target",
            "cashMethode__label" => "target method account",
        ),
        "shortStepHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "sender",
            "cabang_nama" => "recipient",
            "7759r" => "request number",
            //            "759" => "approval number",
            //            "758r" => "request number",

            "details" => "invoice",
            "customerSetor" => "customer",
            "nilaiSetor" => "nilai",

            //            "758" => "receipt number",

            "oleh_nama" => "person",
            "nilai_bayar" => "amount",
            "cash_account_source__label" => "bank account source",
            "cash_account_target__label" => "bank account target",
            "cashMethode__label" => "target method account",
            "next_pic" => "next step otorisator",
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
        "historyFields" => array(
            1 => array(
                "no" => "no",
                "dtime" => "date",
                //            "customers_nama"             => "customer",
                "nomer" => "request number",
                "details" => "detail",
                "oleh_nama" => "person",
                "nilai_bayar" => "amount",
                "cash_account_source__label" => "bank account source",
                "cash_account_target__label" => "bank account target",
                "cashMethode__label" => "target method account",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
                "no" => "no",
                "dtime" => "date",
                //            "customers_nama"             => "customer",
                "nomer" => "request number",
                "details" => "detail",
                "oleh_nama" => "person",
                "nilai_bayar" => "amount",
                "cash_account_source__label" => "bank account source",
                "cash_account_target__label" => "bank account target",
                "cashMethode__label" => "target method account",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "nilai_bayar" => "amount",
        ),
        "extHistoryFields" => array(
            1 => array(
                //                "review_details" => "id",
                "print_label" => "nomer",
            ),
        ),
        "extHistoryFields2" => array(
            1 => array(
                //                "details" => "nama",
                "details" => array(
                    "kolom" => "nama",
                    "format" => "nomer",
                ),
                "customerSetor" => array(
                    "kolom" => "extern2_nama",
                    "format" => "nama",
                ),
                "nilaiSetor" => array(
                    "kolom" => "nilai_bayar",
                    "format" => "debet",
                ),
            ),
        ),

        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields" => array(
            1 => array(
//                "extern2_nama" => "customer",
                "customers_nama" => "customer",
                "nama" => "item name",
                "jml" => "qty",
            ),

        ),
        "shoppingCartFieldSrc" => array(
            "extern2_id" => "extern2_id",
            "extern2_nama" => "extern2_nama",
            "nama" => "nomer",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            //----
            "customers_id" => "customers_id",
            "customers_nama" => "customers_nama",
            "suppliers_id" => "suppliers_id",
            "suppliers_nama" => "suppliers_nama",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "sisa" => "due remain",
            ),
        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                //                "sisa" => "debt amount",
                //                "creditAmount" => "paid using credit",
                //                "nilai_entry" => "paid using cash account",
                //                "nilai_bayar" => "total amount of payment",
                //                "new_sisa" => "remain debt (from list)",
            ),
        ),
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
            "cash_account_source" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "branch cash account",

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
                "mdlName" => "MdlBankAccount_cash_and_in", // MdlBankAccount_out
                "mdlFilter" => array(
                    "bank.cabang_id=placeID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account",
                    "saldo" => "balance",
                ),
                "editPoints" => array(1,),
                "labelValidate" => "Silahkan memilih sumber pembayaran setoran uang muka sebelum melanjutkan transaksi.",
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
        "relativeElements" => array(
            "centerDetails" => array(
                "-1" => array(
                    //                    "cashMethode" => array(
                    //                        "elementType" => "dataModel",
                    //                        "inputType" => "radio",
                    //                        "label" => "Metode rekening",
                    //                        "mdlName" => "MdlCashAccountStatic",
                    //                        "mdlFilter" => array(
                    ////                    "extern_id=pihakID",
                    ////                    "cabang_id=cabangID",
                    ////                    "sisa>.0",
                    //                        ),
                    //                        "key" => "id",
                    //                        "labelSrc" => "nama",
                    //                        "usedFields" => array(
                    //                            "nama" => "method",
                    ////                    "extern_id" => "pihakID",
                    //
                    //                        ),
                    //                        "editPoints" => array(1,),
                    //                        "noValidate" => true,
                    //                        "noPrefetch" => true,
                    ////                "pairMethod" => array(
                    ////                    "recom" => "ReComUangMuka",
                    ////                    "calculate" => array(
                    ////                        "source" => "uangMuka",
                    ////                        "target" => "uang_muka_dipakai",
                    ////                        "pair_source" => "nilai_sisa",//sunbe sumber yang dibandingkan
                    ////                    ),
                    //
                    //
                    //                    ),

                    "cash_account_target" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "center cash account",
//                        "mdlName" => "MdlBankAccount_in_and_koran",
                        "mdlName" => "MdlBankAccount_in",
                        "mdlFilter" => array(
                            "cabang_id=centerDetails",
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
                        "labelValidate" => "Silahkan memilih rekening bank target penerimaan setoran uang muka sebelum melanjutkan transaksi.",
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
        "shoppingCartReferenceFields" => array(
            "nomer" => "sales number",
            "nomer_top" => "sales ref.",
            //            "refNum" => "return ref.",
            "customers_nama" => "customer",
            "fulldate" => "date",
            "tagihan" => "due amount",
            //            "refValue" => "returned",
            "terbayar" => "paid",
            //            "diskon" => "discount",
            "sisa" => "due remain",
            "cash_account_nama" => "cash account",
            "notes" => "description",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "person",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            //            "diskon" => "discount",
            "sisa" => "due remain",
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
                    //                    "credit_amount" => array(
                    //                        "label" => "credit amount",
                    //                        "defaultValue" => "creditAmount",
                    //                        //                        "keyupAction" => "",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    //                    "credit_note" => array(
                    //                        "label" => "credit note",
                    //                        "defaultValue" => "creditValue",
                    //                        //                        "keyupAction" => "",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
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
        "shoppingCartFieldValidatorsComparison" => array(
            "nilai_entry" => "sumber",
            "nilai_bayar" => "target",
        ),

        "pairRegistries" => array(
            "main", "items"
        ),

        "connectTo" => "7758",
        "paymentSrcLocked" => array(
            "enabled" => false,
            "notes" => "penerimaan tunai<br>belum dilakukan setoran ke bank",
        ),
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}. 
                            <br>Silahkan melakukan {transaksi_nama} ulang di cabang {cabang_nama}",
        ),
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "7759re",
                "label" => "EDIT setoran uang muka konsumen",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "7759rrj",
                "label" => "REJECT setoran uang muka konsumen",
            ),
        ),
    ),
    "7758" => array(
        "icon" => "fa fa-money",
        "label" => "Penerimaan Setoran Uang Muka Konsumen",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "setoran uang muka konsumen",
                "actionLabel" => "setoran uang muka konsumen",
                "source" => "",
                "target" => "7758r",
                "userGroup" => "sys",
                "stateLabel" => "pending acceptance",
                "stateColor" => "#dd3300",
                "stateCaption" => "initiated by",
            ),
            2 => array(
                "label" => "Penerimaan Setoran uang muka konsumen",
                "actionLabel" => "receive",
                "source" => "7758r",
                "target" => "7758",
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
            //            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "branch",
            "nomer" => "receipt number",

            "details" => "invoice",
            "customerSetor" => "customer",
            "nilaiSetor" => "nilai",

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
            "7759r" => "request number",
            //            "759" => "approval number",
            //            "758r" => "request number",
            "7758" => "receipt number",

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
        "historyFields" => array(
            1 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang2_nama" => "branch",
                "nomer" => "receipt number",

                "details" => "invoice",
                "customerSetor" => "customer",
                "nilaiSetor" => "nilai",

                "oleh_nama" => "person",
                "nilai_bayar" => "amount",
                "cash_account_source__label" => "bank account source",
                "cashMethode__label" => "target method account",
                "cash_account_target__label" => "bank account target",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang2_nama" => "branch",
                "nomer" => "receipt number",

                "details" => "invoice",
                "customerSetor" => "customer",
                "nilaiSetor" => "nilai",

                "oleh_nama" => "person",
                "nilai_bayar" => "amount",
                "cash_account_source__label" => "bank account source",
                "cashMethode__label" => "target method account",
                "cash_account_target__label" => "bank account target",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array(
                //                "review_details" => "id",
                "print_label" => "nomer",
            ),
        ),
        "extHistoryFields2" => array(
            1 => array(
                //                "details" => "nama",
                "details" => array(
                    "kolom" => "nama",
                    "format" => "nomer",
                ),
                "customerSetor" => array(
                    "kolom" => "extern2_nama",
                    "format" => "nama",
                ),
                "nilaiSetor" => array(
                    "kolom" => "nilai_bayar",
                    "format" => "debet",
                ),
            ),
            2 => array(
                //                "details" => "nama",
                "details" => array(
                    "kolom" => "nama",
                    "format" => "nomer",
                ),
                "customerSetor" => array(
                    "kolom" => "extern2_nama",
                    "format" => "nama",
                ),
                "nilaiSetor" => array(
                    "kolom" => "nilai_bayar",
                    "format" => "debet",
                ),
            ),
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields" => array(
            1 => array(
                "extern2_nama" => "customer",
                "nama" => "item name",
                //                "jml" => "qty",
            ),

        ),
        "shoppingCartFieldSrc" => array(
//            "extern2_nama" => "customer",
            "nama" => "nomer",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            //----
            "customers_id" => "customers_id",
            "customers_nama" => "customers_nama",
            "suppliers_id" => "suppliers_id",
            "suppliers_nama" => "suppliers_nama",

        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "sisa" => "due remain",
            ),
        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            2 => "nilai_bayar",
        ),
        "shoppingCartSumFields" => array(
            2 => array(
                "sisa" => "total amount",
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
            "main", "items"
        ),
        "revertException" => true,
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}. 
                            <br>Silahkan melakukan {transaksi_nama} ulang di cabang {cabang_nama}",
        ),
    ),

    // uang muka dari konsumen
    "4467_ori" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "uang muka",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "uang muka konsumen",
                "actionLabel" => "receive",
                "source" => "",
                "target" => "4467",
                "userGroup" => "o_finance",
                "stateLabel" => "receive",
                "stateColor" => "#ff7700",
                "stateCaption" => "received by",
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlUangMuka",
        "selectorSrcModel" => "MdlUangMuka",
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
            "status=.1",
            "trash=.0",
            "ppn=.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array(
            "nama",
        ),
        "selectorProcessor" => "_processSelectBiaya/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCustomer_and_pre",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "cash_account__label" => "bank account",
//            "harga" => "amount",
            "nett" => "amount",
            "next_pic" => "Next step otorisator",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "nett" => "total amount",
            "keterangan" => "keterangan",
        ),

        "historyFields" => array(
            1 => array(
//                "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "customers_nama" => "customer",
                "nomer_top" => "Number",
                "oleh_nama" => "person",
                "cash_account__label" => "bank account",
//                "harga" => "amount",
                "nett" => "amount",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
//                "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "customers_nama" => "customer",
                "nomer_top" => "REQ Number",
                "nomer" => "Number",
                "oleh_nama" => "person",
                "cash_account__label" => "bank account",
//                "harga" => "amount",
                "nett" => "amount",
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
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),

        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "Description",
                "jml" => "Qty",
            ),
            2 => array(
                "nama" => "Description",
                "jml" => "Qty",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "Nominal",
            ),
            2 => array(
                "harga" => "Nominal",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "shoppingCartNoteEditabled" => array(
            1 => true,
            2 => true,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
            ),
            2 => array(
                "harga",
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
        ),
        "shoppingCartSumFields" => array(
            1 => array(
//                "total_diskon" => "diskon",
                "dpp_nilai" => "DPP",
                "ppn" => "PPN",
                "grand_total" => "<r class=\"meta\">kas</r> Diterima",
            ),
        ),
        "shopingCartAddTax" => array(
            "fields" => array(
//                "non_ppn" => "non ppn",
                "include_ppn" => "termasuk ppn",
                "exclude_ppn" => "belum termasuk ppn",
            ),
            "inputType" => "checkbox",
            "selector" => "_selectorPihak/selectTax",
            "srcGateValue" => "harga",
            // "srcGateValue"=>"sisa",

        ),
        "pairRegistries" => array(
            "main",
        ),
        "receiptElements" => array(
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "CUSTOMER DETAILS",
                "mdlName" => "MdlCustomer_and_pre",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "",
                    "alamat_1" => "",
                    "alamat_2" => "alamat",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Tlp",
                    "tlp_1" => "Tlp",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    "nik" => "NIK",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            "paymentMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Payment Method",
                "mdlName" => "MdlPaymentMethod2",
                //                "mdlName" => "MdlPaymentMethodCredit",
                //                "mdlFilter"   => array("extern_id=pihakID"),
                "key" => "id",
//                "defaultValue" => "cash",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1,),
            ),
            "referensiNota" => array(
//                "hiddenSelect" => true,
//                "hiddenBox" => true,
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "metode uang muka",
                "mdlName" => "MdlUangMukaSalesStatic",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "metode",
                    "keterangan" => "keterangan",
//                    "alamat_1" => "",
//                    "country" => "Country",
//                    "tlp_1" => "Phone",
//                    "tlp_2" => "Fax",
//                    //                    "npwp" => "NPWP",
//                    //                    "alias" => "Attn",
//                    "contact_person" => "Attn",
                ),
                "editPoints" => array(1, 2, 3),
                //untuk inject nilai dari triger element lihat di _Shopingcart->fetchElement
                "relInject" => array(
                    1 => array("selectedType_konsumen" => "exclude_ppn"),
//                    2=>array("selectedType_konsumen"=>"include_ppn"),
                ),
            ),
        ),
        "relativeElements" => array(
            "paymentMethod" => array(
                "cash" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "cash account",
//                        "mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlName" => "MdlBankAccount_cash",
                        "mdlFilter" => array(
//                            "cabang_id=placeID",
//                            "jenis2=.1",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "account number",
                            "alias" => "holder alias",

                        ),
                        "editPoints" => array(1,),
                        "noValidate" => true,
                        /*
                         * pairMethod tak matiin dulu gak tau buat apa widi 1/11/2024
                         */

//                        "pairMethod" => array(
//                            "recom" => "ReComCashAccountJenis",
//                            "calculate" => array(
//                                "source" => "cash_account",
//                                "filter" => array(
//                                    "cabang_id=placeID",
//                                ),
//                                "result" => array(
//                                    "nilai_setoran_tunai" => "nilai_entry",
//                                ),
//                            ),
//                        ),
                    ),
                ),
                "cashless" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "combo",
                        "label" => "EDC",
//mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlName" => "MdlEdc",
                        "key" => "id",
                        "labelSrc" => "nama",
                        "mdlFilter" => array(),
                        "usedFields" => array(
                            "nama" => "alias",
                            "folders_nama" => "rekening",
                            "machine_id" => "MID",
                            "jenis" => "jenis",
                            "folders" => "relID"
                        ),
                        "editPoints" => array(1,),
                        "noValidate" => true,
                        /*
 * pairMethod tak matiin dulu gak tau buat apa widi 1/11/2024
 */
//                        "pairMethod" => array(
//                            "recom" => "ReComCashAccountJenis",
//                            "calculate" => array(
//                                "source" => "cash_account",
//                                "filter" => array(
//                                    "cabang_id=placeID",
//                                ),
//                                "result" => array(
//                                    "nilai_setoran_tunai" => "nilai_entry",
//                                ),
//                            ),
//                        ),
                    ),
                ),
                "transfer" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
//                        "inputType" => "radio",
                        "inputType" => "combo",
                        "label" => "rekening bank",
//                                        "mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlName" => "MdlBankAccount_in",
                        "key" => "id",
                        "labelSrc" => "nama",
                        "mdlFilter" => array(),
                        "usedFields" => array(
                            "folders_nama" => "bank",
                            "nama" => "rekening",
                            "machine_id" => "MID",
                            "jenis" => "jenis",
                            "folders" => "relID"
                        ),
                        "editPoints" => array(1,),
                        "noValidate" => true,
                        /*
 * pairMethod tak matiin dulu gak tau buat apa widi 1/11/2024
 */
//                        "pairMethod" => array(
//                            "recom" => "ReComCashAccountJenis",
//                            "calculate" => array(
//                                "source" => "cash_account",
//                                "filter" => array(
//                                    "cabang_id=placeID",
//                                ),
//                                "result" => array(
//                                    "nilai_setoran_tunai" => "nilai_entry",
//                                ),
//                            ),
//                        ),
                    ),
                ),
            ),
            "referensiNota" => array(
                "2" => array(
                    "referensi_so" => array(
                        "elementType" => "dataModel",
                        "inputType" => "combo",
                        "label" => "sales order",
                        "mdlName" => "MdlTransaksi2",//ini klonengan mdltransaksi
                        "key" => "id",
                        "mdlFilter" => array(
                            "customers_id=pihakID",
                            "jenis=.5822so",
                            "link_id=.0",

                        ),
                        "labelSrc" => "nomer",
                        "usedFields" => array(
                            "id_master" => "mid",
                            "id" => "referensi order",
                            "fulldate" => "tgl order",
                            "nomer" => "nomer po/order",
                            "oleh_nama" => 'salesman'
                        ),
                        "editPoints" => array(1,),
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
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "settlementHistoryFields" => array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "customers_nama" => "customer",
            "jenis_label" => "activity",
            "transaksi_nilai" => "orig. value",
            "add_disc" => "discount",
            "grand_total" => "nett",
        ),
        "validatePaymentSource" => array(//            "3" => "MdlLockerValue",
        ),
        "allowedMainEdit" => array(1, 2,),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "4467re",
                "label" => "EDIT request uang muka (dp tanpa ppn)",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "4467rrj",
                "label" => "REJECT request uang muka (dp tanpa ppn)",
            ),
        ),
        "connectTo" => "110",
        "connectoValidate" => array(
            1 => "nilai_payment_source",
        ),
        "replacerConnectTo" => array(
            "cabang2ID" => "-1",
            "cabang2Name" => "pusat",
            "place2ID" => "-1",
            "place2Name" => "pusat",
            "gudang2ID" => "-1",
            "gudang2Name" => "default center warehouse",
            "efaktur_source" => "nomer",//untuk ambil jika lintas cabang
            "harga" => "dpp_nilai",

            "pihakID" => "placeID",
            "pihakName" => "placeName",
        ),
        "replacerConnectToItems" => array(
            "nett1" => "harga",

        ),

    ),
    "4467" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "uang muka",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "uang muka konsumen",
                "actionLabel" => "receive",
                "source" => "",
                "target" => "4467",
                "userGroup" => "o_finance",
                "stateLabel" => "receive",
                "stateColor" => "#ff7700",
                "stateCaption" => "received by",
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlUangMuka",
        "selectorSrcModel" => "MdlUangMuka",
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
            "status=.1",
            "trash=.0",
            "ppn=.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array(
            "nama",
        ),
        "selectorProcessor" => "_processSelectBiaya/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCustomer_and_pre",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "cash_account__label" => "bank account",
//            "harga" => "amount",
            "nett" => "amount",
            "next_pic" => "Next step otorisator",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "nett" => "total amount",
            "keterangan" => "keterangan",
        ),

        "historyFields" => array(
            1 => array(
//                "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "customers_nama" => "customer",
                "nomer_top" => "Number",
                "oleh_nama" => "person",
                "cash_account__label" => "bank account",
//                "harga" => "amount",
                "nett" => "amount",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
//                "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "customers_nama" => "customer",
                "nomer_top" => "REQ Number",
                "nomer" => "Number",
                "oleh_nama" => "person",
                "cash_account__label" => "bank account",
//                "harga" => "amount",
                "nett" => "amount",
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
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),

        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "Description",
                "jml" => "Qty",
            ),
            2 => array(
                "nama" => "Description",
                "jml" => "Qty",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "Nominal",
            ),
            2 => array(
                "harga" => "Nominal",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "shoppingCartNoteEditabled" => array(
            1 => true,
            2 => true,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
            ),
            2 => array(
                "harga",
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
        ),
        "shoppingCartSumFields" => array(
            1 => array(
//                "total_diskon" => "diskon",
                "dpp_nilai" => "DPP",
                "ppn" => "PPN",
                "grand_total" => "<r class=\"meta\">kas</r> Diterima",
            ),
        ),
        "shopingCartAddTax" => array(
            "fields" => array(
//                "non_ppn" => "non ppn",
                "include_ppn" => "termasuk ppn",
                "exclude_ppn" => "belum termasuk ppn",
            ),
            "inputType" => "checkbox",
            "selector" => "_selectorPihak/selectTax",
            "srcGateValue" => "harga",
            // "srcGateValue"=>"sisa",

        ),
        "pairRegistries" => array(
            "main",
        ),
        "receiptElements" => array(
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "CUSTOMER DETAILS",
                "mdlName" => "MdlCustomer_and_pre",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "",
                    "alamat_1" => "",
                    "alamat_2" => "alamat",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Tlp",
                    "tlp_1" => "Tlp",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    "nik" => "NIK",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            "paymentMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Payment Method",
                "mdlName" => "MdlPaymentMethod2",
                //                "mdlName" => "MdlPaymentMethodCredit",
                //                "mdlFilter"   => array("extern_id=pihakID"),
                "key" => "id",
//                "defaultValue" => "cash",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1,),
            ),
            "referensiNota" => array(
//                "hiddenSelect" => true,
//                "hiddenBox" => true,
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "metode uang muka",
                "mdlName" => "MdlUangMukaSalesStatic",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "metode",
                    "keterangan" => "keterangan",
//                    "alamat_1" => "",
//                    "country" => "Country",
//                    "tlp_1" => "Phone",
//                    "tlp_2" => "Fax",
//                    //                    "npwp" => "NPWP",
//                    //                    "alias" => "Attn",
//                    "contact_person" => "Attn",
                ),
                "editPoints" => array(1, 2, 3),
                //untuk inject nilai dari triger element lihat di _Shopingcart->fetchElement
                "relInject" => array(
                    1 => array("selectedType_konsumen" => "exclude_ppn"),
//                    2=>array("selectedType_konsumen"=>"include_ppn"),
                ),
            ),
        ),
        "relativeElements" => array(
            "paymentMethod" => array(
                "cash" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "cash account",
//                        "mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlName" => "MdlBankAccount_cash",
                        "mdlFilter" => array(
//                            "cabang_id=placeID",
//                            "jenis2=.1",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "account number",
                            "alias" => "holder alias",

                        ),
                        "editPoints" => array(1,),
                        "noValidate" => true,
                        /*
                         * pairMethod tak matiin dulu gak tau buat apa widi 1/11/2024
                         */

//                        "pairMethod" => array(
//                            "recom" => "ReComCashAccountJenis",
//                            "calculate" => array(
//                                "source" => "cash_account",
//                                "filter" => array(
//                                    "cabang_id=placeID",
//                                ),
//                                "result" => array(
//                                    "nilai_setoran_tunai" => "nilai_entry",
//                                ),
//                            ),
//                        ),
                    ),
                ),
                "cashless" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "combo",
                        "label" => "EDC",
//mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlName" => "MdlEdc",
                        "key" => "id",
                        "labelSrc" => "nama",
                        "mdlFilter" => array(),
                        "usedFields" => array(
                            "nama" => "alias",
                            "folders_nama" => "rekening",
                            "machine_id" => "MID",
                            "jenis" => "jenis",
                            "folders" => "relID"
                        ),
                        "editPoints" => array(1,),
                        "noValidate" => true,
                        /*
 * pairMethod tak matiin dulu gak tau buat apa widi 1/11/2024
 */
//                        "pairMethod" => array(
//                            "recom" => "ReComCashAccountJenis",
//                            "calculate" => array(
//                                "source" => "cash_account",
//                                "filter" => array(
//                                    "cabang_id=placeID",
//                                ),
//                                "result" => array(
//                                    "nilai_setoran_tunai" => "nilai_entry",
//                                ),
//                            ),
//                        ),
                    ),
                ),
                "transfer" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
//                        "inputType" => "radio",
                        "inputType" => "combo",
                        "label" => "rekening bank",
//                                        "mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlName" => "MdlBankAccount_in",
                        "key" => "id",
                        "labelSrc" => "nama",
                        "mdlFilter" => array(),
                        "usedFields" => array(
                            "folders_nama" => "bank",
                            "nama" => "rekening",
                            "machine_id" => "MID",
                            "jenis" => "jenis",
                            "folders" => "relID"
                        ),
                        "editPoints" => array(1,),
                        "noValidate" => true,
                        /*
 * pairMethod tak matiin dulu gak tau buat apa widi 1/11/2024
 */
//                        "pairMethod" => array(
//                            "recom" => "ReComCashAccountJenis",
//                            "calculate" => array(
//                                "source" => "cash_account",
//                                "filter" => array(
//                                    "cabang_id=placeID",
//                                ),
//                                "result" => array(
//                                    "nilai_setoran_tunai" => "nilai_entry",
//                                ),
//                            ),
//                        ),
                    ),
                ),
            ),
            "referensiNota" => array(
                "2" => array(
                    "referensi_um" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Jenis transaksi",
                        "mdlName" => "MdlUangMukaOptionStatic",//ini klonengan mdltransaksi
                        "key" => "id",
                        "mdlFilter" => array(
//                            "customers_id=pihakID",
//                            "jenis=.5822so",
//                            "link_id=.0",

                        ),
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "mid",
                            "ref_jenis" => "referensi",

                        ),
                        "editPoints" => array(1,),
                    ),
                    "referensi_so" => array(
                        "elementType" => "dataModel",
                        "inputType" => "combo",
                        "label" => "sales order",
                        "mdlName" => "MdlTransaksi2",//ini klonengan mdltransaksi
                        "key" => "id",
                        "mdlFilter" => array(
                            "customers_id=pihakID",
                            "jenis=referensi_um__ref_jenis",
                            "link_id=.0",

                        ),
                        "labelSrc" => "nomer",
                        "usedFields" => array(
                            "id_master" => "mid",
                            "id" => "referensi order",
                            "fulldate" => "tgl order",
                            "nomer" => "nomer po/order",
                            "seller_nama" => 'salesman'
//                            "oleh_nama" => 'salesman'
                        ),
                        "editPoints" => array(1,),
                    ),
                ),
            ),
//            "referensi_um"=>array(
//                "11"=>array(
//                    "referensi_so" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "combo",
//                        "label" => "sales order",
//                        "mdlName" => "MdlTransaksi2",//ini klonengan mdltransaksi
//                        "key" => "id",
//                        "mdlFilter" => array(
//                            "customers_id=pihakID",
//                            "jenis=referensi_um__ref_jenis",
//                            "link_id=.0",
//
//                        ),
//                        "labelSrc" => "nomer",
//                        "usedFields" => array(
//                            "id_master" => "mid",
//                            "id" => "referensi order",
//                            "fulldate" => "tgl order",
//                            "nomer" => "nomer po/order",
//                            "seller_nama" => 'salesman'
////                            "oleh_nama" => 'salesman'
//                        ),
//                        "editPoints" => array(1,),
//                    ),
//                ),
//                "12"=>array(
//                    "referensi_so" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "combo",
//                        "label" => "Project",
//                        "mdlName" => "MdlProdukProject",//ini klonengan mdltransaksi
//                        "key" => "id",
//                        "mdlFilter" => array(
//                            "customer_id=pihakID",
//                            "jenis=referensi_um__ref_jenis",
//                            "quout_status=.1",
//
//                            "link_id=.0",
//
//                        ),
//                        "labelSrc" => "nomer",
//                        "usedFields" => array(
//                            "id_master" => "mid",
//                            "id" => "referensi order",
//                            "fulldate" => "tgl order",
//                            "nomer" => "nomer po/order",
//                            "seller_nama" => 'salesman'
////                            "oleh_nama" => 'salesman'
//                        ),
//                        "editPoints" => array(1,),
//                    ),
//                ),
//            ),
        ),
        "relativeOptions" => array(),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "settlementHistoryFields" => array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "customers_nama" => "customer",
            "jenis_label" => "activity",
            "transaksi_nilai" => "orig. value",
            "add_disc" => "discount",
            "grand_total" => "nett",
        ),
        "validatePaymentSource" => array(//            "3" => "MdlLockerValue",
        ),
        "allowedMainEdit" => array(1, 2,),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "4467re",
                "label" => "EDIT request uang muka (dp tanpa ppn)",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "4467rrj",
                "label" => "REJECT request uang muka (dp tanpa ppn)",
            ),
        ),
        "connectTo" => "110",
        "connectoValidate" => array(
            1 => "nilai_payment_source",
        ),
        "replacerConnectTo" => array(
            "cabang2ID" => "-1",
            "cabang2Name" => "pusat",
            "place2ID" => "-1",
            "place2Name" => "pusat",
            "gudang2ID" => "-1",
            "gudang2Name" => "default center warehouse",
            "efaktur_source" => "nomer",//untuk ambil jika lintas cabang
            "harga" => "dpp_nilai",

            "pihakID" => "placeID",
            "pihakName" => "placeName",
        ),
        "replacerConnectToItems" => array(
            "nett1" => "harga",

        ),

    ),
    "7761" => array(
        "icon" => "fa fa-money",
        "label" => "Penerimaan uang muka konsumen",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "settlement uang muka konsumen",
                "actionLabel" => "terima",
                "source" => "",
                "target" => "7761",
                "userGroup" => "sys",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "confirmed by",
            ),
        ),
        "paymentConfig" => true,
        "isPaymentRadioSelect" => true,
        "template" => "template/transaksi_payment.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.582",
            // "transaksi_nilai_sisa>.0",
            "transaksi_nilai_sisa>.1000",
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
            //            "jenis_label"    => "activity",
            "dtime" => "tanggal",
            "customers_nama" => "konsumen",
            "nomer" => "nomer",
            "details" => "detail",
            "oleh_nama" => "person",
            "cash_account__label" => "account",
//            "sisa" => "tagihan",
//            "nilai_biaya" => "biaya usaha",
//            "ppn_nilai_dibayar" => "PPN DIBAYAR BENDAHARA NEGARA",
//            "pph22_nilai" => "PPH 22 DIBAYAR DIMUKA",
//            "credit_note_dipakai" => "credit note<br>(from return)",
//            "uang_muka_dipakai" => "uang muka",
            "nilai_entry" => "uang diterima",
//            "new_sisa"            => "sisa tagihan",
//            "kelebihanBayar__label" => "kelebihan bayar",
//            "deposit_konsumen" => "deposit konsumen",
//            "pendapatan_lain_lain" => "pendapatan lain-lain",
        ),
        "historyFields" => array(
            1 => array(
                //            "jenis_label"    => "activity",
                "dtime" => "date",
                "customers_nama" => "customer",
                "nomer" => "receipt number",
                "details" => "detail",
                "oleh_nama" => "person",
                "cash_account__label" => "account",
//            "sisa" => "tagihan",
//            "nilai_biaya" => "biaya usaha",
//            "ppn_nilai_dibayar" => "PPN DIBAYAR BENDAHARA NEGARA",
//            "pph22_nilai" => "PPH 22 DIBAYAR DIMUKA",
//            "credit_note_dipakai" => "credit note<br>(from return)",
//            "uang_muka_dipakai" => "uang muka",
                "nilai_entry" => "CASH RECEIVED",
//            "new_sisa"            => "sisa tagihan",
//            "kelebihanBayar__label" => "kelebihan bayar",
//            "deposit_konsumen" => "deposit konsumen",
//            "pendapatan_lain_lain" => "pendapatan lain-lain",
            ),

        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "nilai_entry" => "amount",
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

        "shoppingCartFields" => array(
            1 => array(
                "nama" => "product name",
                "produk_kode" => "product code",
//                "no_part" => "part number",
//                "stok_center" => "stok dc",
//                "stok" => "stok available",
//
                "jml" => "qty",
                "satuan" => "uom",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "no_part" => "no_part",
            "label" => "label",
            "satuan" => "satuan",
            "ppn" => "harga*(ppnFactor/100)",
//            "berat_gross" => "berat_gross",
//            "lebar_gross" => "lebar_gross",
//            "panjang_gross" => "panjang_gross",
//            "tinggi_gross" => "tinggi_gross",
//            "volume_gross" => "volume_gross",

//            "volume" => "volume",
//            "berat" => "berat",
//            "lebar" => "lebar",
//            "tinggi" => "tinggi",
//            "panjang" => "panjang",
            "jenis" => "jenis",
            "produk_jenis_id" => "produk_jenis_id",
            "produk_jenis_nama" => "produk_jenis_nama",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                // "ppn" => "VAT",
                "nett1" => "price(net)",
            ),
        ),
        "shoppingCartEditableFields" => array(
//            1 => array(
//                "jml",
//                "produk_ord_jml",
//                "disc_percent",
//                "disc",
//                "nett1",
//            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*(harga-disc+ppn)",//nett2
        ),
        "shoppingCartHideSubamount" => array(
            1 => false,
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "total bruto",
//                "disc" => "disc",
//                "add_diskon" => "diskon tambahan",
                "total_diskon" => "diskon",
//                "ongkir_ui" => "shipping service",
//                "nilai_pembulatan" => "pembulatan",
                "nett1_bulat" => "total netto*",
                "ppn_out_bulat" => "PPN",
                "grand_pembulatan" => "grand total",
            ),

        ),

        "pairRegistries" => array(
            "main", "items"
        ),
        "shoppingCartAvoidRemove" => true,
        "shoppingCartAvoidRemoveAll_items" => false,
        "tagihanSrc" => "harus_bayar",
        "receiptElements" => array(
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "customer details",
                // "mdlName"     => "MdlCustomer_and_pre",
                "mdlName" => "MdlCustomerAll",//gak mandang trash dan status
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                    "alamat_1" => "alamat",
                    "alamat_2" => "alamat",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Tlp",
                    "tlp_1" => "Tlp",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    "nik" => "NIK",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
//            "kelebihanBayar" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "balance",
//                "mdlName" => "MdlStaticMethodLebihBayar",
//                "mdlFilter" => array(),
//                "key" => "id",
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "name" => "method",
//                ),
//                "editPoints" => array(1,),
//                "targetMethod" => array(
//                    //                    0 => "ReComMethodLebihBayar",
//                    1 => "ReComMethodLebihBayar",
//                    2 => "ReComMethodLebihBayar",
//                ),
//                "noValidate" => true,
//            ),
//            "uangMuka" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "Uang Muka (DP tanpa PPN)",
//                "mdlName" => "MdlCustomerUangMuka",
//                "mdlFilter" => array(
//                    "extern_id=pihakID",
//                    "cabang_id=cabangID",
//                    "extern_label2=.customer",
//                    "sisa>.0",
//                ),
//                "key" => "id",
//                "labelSrc" => "sisa",
//                "usedFields" => array(
//                    "extern_nama" => "customer",
//                    "extern_id" => "pihakID",
//                    "extern_label2" => "tipe",
//                ),
//                "editPoints" => array(1,),
//                "noValidate" => true,
//                "noPrefetch" => true,
//                "pairMethod" => array(
//                    "recom" => "ReComUangMuka",
//                    "calculate" => array(
//                        "source" => "sisa",
//                        "target" => "uang_muka_dipakai",
//                        "pair_source" => "nilai_sisa",//sunbe sumber yang dibandingkan
//                    ),
//                ),
//            ),
            "cash_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "cash account",
                "mdlName" => "MdlBankAccount_cash_and_in",
                "mdlFilter" => array(
                    "cabang_id=placeID",
                    "jenis2=.1",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "defaultValue" => "cash_account",
                "usedFields" => array(
                    "nama" => "account number",
                    "alias" => "holder alias",

                ),
                "editPoints" => array(1,),
                "noValidate" => true,
                "pairMethod" => array(
                    "recom" => "ReComCashAccountJenis",
                    "calculate" => array(
                        "source" => "cash_account",
                        "filter" => array(
                            "cabang_id=placeID",
                        ),
                        "result" => array(
                            "nilai_setoran_tunai" => "nilai_entry",
                        ),
                    ),
                ),
            ),
//            "creditAmount" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "credit amount (from return)",
//                "mdlName" => "MdlPaymentAntiSource",
//                "mdlFilter" => array(
//                    "extern_id=pihakID",
//                    "cabang_id=cabangID",
//                    //                    "target_jenis=jenisTr",
//                    "label=.piutang dagang",
//                    "sisa>.0",
//                    "trash=.0",
//                ),
//                "key" => "sisa",
//                "labelSrc" => "sisa",
//                "usedFields" => array(
//                    "extern_nama" => "customer name",
//                    //                    "transaksi_id" => "return ID",
//                    //                    "nomer" => "return number",
//                    "sisa" => "avail credit",
//                    //                    "jenis" => "jenis",
//                ),
//                "editPoints" => array(1,),
//                "noValidate" => true,
//                "autoSelect" => false,
//                "noPrefetch" => true,
//                "pairMethod" => array(
//                    "recom" => "ReComCreditNote",
//                    "calculate" => array(
//                        "source" => "creditAmount", // credit_note
//                        "target" => "credit_note_dipakai",
//                        "pair_source" => "nilai_round",//sunbe sumber yang dibandingkan /// nilai_sisa
//                    ),
//
//                    //                    "customer" => "ReComDiscCustomer",
//                ),
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
            "pihakID" => "customer ID",
            "pihakName" => "customer name",
//            "selectedType_konsumen" => "Tipe konsumen",
        ),
        "shoppingCartRowNumValidators" => array(//            "nilai_entry" => "amount of payment",
        ),

        "shoppingCartReferenceFields" => array(
            "fulldate" => "tanggal",
            "nomer_top" => "nomer inv",
            "nomer" => "nomer approval",
//            "refNum" => "return ref.",
//            "tagihan" => "due amount",
//            "refValue" => "returned",
//            "terbayar" => "paid",
//            "diskon" => "discount",
            "sisa" => "nilai",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "customer",
//            "qty_nota" => "jumlah<br>invoice",
//            "tagihan" => "due amount",
//            "terbayar" => "paid",
//            "diskon" => "discount",
            "sisa" => "nilai",
            // "a_sisa" => "return",
            // "a_saldo" => "saldo",
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
//                    "nilai_round" => array(
//                        "label" => "value of invoice",
//                        "defaultValue" => "nilai_round",
//                        "maxValue" => "nilai_round",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                    ),
//                    "uang_muka_dipakai" => array(
//                        "label" => "Uang Muka",
//                        "defaultValue" => "0",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                    ),
//                    "credit_amount" => array(
//                        "label" => "credit note(deposit)",
//                        "defaultValue" => "credit_note_dipakai",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                    ),
//                    "ppn_nilai_dibayar" => array(
//                        "label" => "(ppn dibayar bendahara negara)",
//                        "defaultValue" => "ppn_nilai_dibayar",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                    ),
//                    "pph22_nilai" => array(
//                        "label" => "(pph 22 dibayar dimuka)",
//                        "defaultValue" => "pph22_nilai",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                    ),
//                    "nilai_biaya" => array(
//                        "label" => "(biaya suport)",
//                        "defaultValue" => ".0",
//                        "keyupAction" => "
//    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harus_bayar').value;}
//                            ",
//
//                        //                        "keyupAction" =>
//                        //                            "
//                        //                                var gt=document.getElementById('harus_bayar').value;
//                        //                                var bayar=this.value;
//                        //                                var add_value=document.getElementById('lebih_bayar');
//                        //                                var nilai=(parseFloat(bayar)-parseFloat(gt));
//                        //
//                        //                                if(parseFloat(nilai)>=0){
//                        //                                    lebih_bayar.value=parseFloat(nilai);
//                        //                                }
//                        //                                else{
//                        //                                    lebih_bayar.value=parseFloat(0);
//                        //                                }
//                        //                            ",
//
//                        "addPoints" => array(1,),
//                    ),
//
//                    "harus_bayar" => array(
//                        "label" => "total invoice(netto)",
//                        "defaultValue" => "(sisa-credit_note_dipakai-creditValue-nilai_biaya)",
//                        "maxValue" => "(sisa-credit_note_dipakai-creditValue-nilai_biaya)",
//                        "minValue" => "(sisa-credit_note_dipakai-creditValue-nilai_biaya)",
//                        "keyPressAction" => "",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                        'hideRow' => "true",
//                    ),
//                    // "nilai_round" => array(
//                    //     "label" => "total invoice(netto)",
//                    //     "defaultValue" => "(nilai_round-credit_note_dipakai-creditValue-nilai_biaya)",
//                    //     "maxValue" => "(nilai_round-credit_note_dipakai-creditValue-nilai_biaya)",
//                    //     "minValue" => "(nilai_round-credit_note_dipakai-creditValue-nilai_biaya)",
//                    //     "keyPressAction" => "",
//                    //     'disabled' => "disabled",
//                    //     "addPoints" => array(1,),
//                    // ),
//
//                    "new_sisa_before_entry" => array(
//                        "label" => "netto",
//                        "defaultValue" => "new_sisa_before_entry",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                        'hideRow' => false,
//                    ),
                    "nilai_entry" => array(
                        "label" => "cash received",
                        "defaultValue" => "nilai_cash",
//                        "keyupAction" =>
//                            "
//                                var gt=removeCommas(document.getElementById('grand_pembulatan').value);
//                                var bayar=removeCommas(this.value);
//                                var add_value=document.getElementById('lebih_bayar');
//                                var nilai=parseFloat(gt)-(parseFloat(bayar));
//                                var nilai_biaya=removeCommas(document.getElementById('nilai_biaya').value);
//                                var harus_bayar=parseFloat(gt)-(parseFloat(nilai_biaya));
//                                if(parseFloat(nilai)>=0){
//
//                                    add_value.value=addCommas(parseFloat(nilai));
//                                }
//                                else{
//                                    add_value.value=parseFloat(0);
//                                }
//                            ",
                        "keyupAction" => "
    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('grand_pembulatan').value)) || parseInt(removeCommas(this.value))<0){this.value=addCommas(document.getElementById('grand_pembulatan').value);}
     
                            ",
                        "addPoints" => array(1,),
                    ),
//
//
//                    "new_sisa" => array(
//                        "label" => "balance",
//                        "defaultValue" => ".0",
//                        //                        "maxValue" => "new_sisa",
//                        //                        "minValue" => "new_sisa",
//                        "keyPressAction" => "",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                        //                        "hideRow" =>true,
//                    ),
//                    "lebih_bayar" => array(
//                        "label" => "lebih bayar",
//                        "defaultValue" => ".0",
//                        //                        "maxValue" => "new_sisa",
//                        //                        "minValue" => "new_sisa",
//                        "keyPressAction" => "",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                        //                        "hideRow" =>true,
//                    ),
                ),

            ),
        ),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "dueDateReader" => true,
        "kelebihanBayar" => true,
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di cabang {cabang_nama}",
        ),
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "4464re",
                "label" => "EDIT request uang muka (dp tanpa ppn)",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "4464rrj",
                "label" => "REJECT request uang muka (dp tanpa ppn)",
            ),
        ),
        //----
        "shoppingCartLoadReference" => true,
        // ----------------------------------------
        "shoppingCartPerTransaksi" => array(
            "dtime" => array(
                "label" => "tanggal",
                "format" => "formatField",
            ),
            "oleh_nama" => array(
                "label" => "pic",
                "format" => "formatField",
                "link" => "settlement/Settlement/selectPaymentSrc/759/",
                "linkType" => "modal",
                "modalHeader" => "oleh_nama",
                "linkSeg_4" => "extern_id",
            ),
            "extern_nama" => array(
                "label" => "konsumen",
                "format" => "formatField",
            ),
            "nomer_top" => array(
                "label" => "nomer inv",
                "format" => "formatField",
            ),
            "nomer" => array(
                "label" => "nomer approval",
                "format" => "formatField",
            ),
            "sisa" => array(
                "label" => "nilai",
                "format" => "formatField",
            ),
//            "nota_settled" => array(
//                "label" => "urut invoice",
//                "format" => "formatField",
//            ),
//            "jumlah_nota" => array(
//                "label" => "jumlah invoice",
//                "format" => "formatField",
//            ),
//            "extern_nilai5" => array(
//                "label" => "point",
//                "format" => "formatField",
//            ),
//            "extern_nilai6" => array(
//                "label" => "Bruto",
//                "format" => "formatField",
//            ),
//            "diskon" => array(
//                "label" => "Diskon",
//                "format" => "formatField",
//            ),
//            "extern_nilai2" => array(
//                "label" => "Netto",
//                "format" => "formatField",
//            ),
//            "extern_nilai3" => array(
//                "label" => "kelebihan setoran",
//                "format" => "formatField",
//            ),
//            "extern_nilai4" => array(
//                "label" => "kekurangan setoran",
//                "format" => "formatField",
//            ),
            "terima_setoran" => array(
                "label" => "terima",
                "format" => "formatField",
                "link" => "kas/Transaksi/selectPaymentSrc/4464/",
                "linkType" => "modal",
                "modalHeader" => "oleh_nama",
                "linkSeg_4" => "extern_id",
                "tipe" => "button",
            ),

//            "extern_nama" => array(
//                "label" => "konsumen",
//                "link" => "Transaksi/selectPaymentSrc/749/",
//                "linkType" => "modal",
//                "modalHeader" => "extern_nama",
//                "linkSeg_4" => "extern_id",
//                // "modalHeader" => "id",
//            ),

        ),
        "shoppingCartPerTransaksiBtn" => array(
            "btn_satu" => array(
                "label" => "per TRANSAKSI",
                "show_default" => "prog_satu",
            ),
            "btn_dua" => array(
                "label" => "per KONSUMEN",
                // "show_default" => true,
            ),
        ),
        "settlementBtn" => false,
    ),

    // mengembalikan uang muka dari konsumen ke konsumen
    "9467" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "pengembalian uang ke konsumen",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "pengembalian uang ke konsumen",
                "actionLabel" => "pengembalian uang ke konsumen",
                "source" => "",
                "target" => "9467",
                "userGroup" => "o_finance",
                "stateLabel" => "receive",
                "stateColor" => "#ff7700",
                "stateCaption" => "received by",
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlUangMuka",
        "selectorSrcModel" => "MdlUangMuka",
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
            "status=.1",
            "trash=.0",
            "ppn=.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array(
            "nama",
        ),
        "selectorProcessor" => "_processSelectBiaya/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCustomer_and_pre",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "dtime" => "date",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "cash_account__label" => "bank account",
//            "nett" => "amount",
            "next_pic" => "Next step otorisator",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "nett" => "total amount",
            "keterangan" => "keterangan",
        ),
        "shortStepHistoryFields" => array(
            "dtime" => "date",
            "cabang2_nama" => "sender",
            "cabang_nama" => "recipient",
            "referensi_nomer" => "request number",
//            "nomer" => "request number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "cash_account__label" => "bank account",
            "next_pic" => "next step otorisator",
        ),

        "historyFields" => array(
            1 => array(
//                "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "customers_nama" => "customer",
                "nomer_top" => "Number",
                "oleh_nama" => "person",
                "cash_account__label" => "bank account",
//                "harga" => "amount",
                "nett" => "amount",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
//                "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "customers_nama" => "customer",
                "nomer_top" => "REQ Number",
                "nomer" => "Number",
                "oleh_nama" => "person",
                "cash_account__label" => "bank account",
//                "harga" => "amount",
                "nett" => "amount",
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
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),

        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "saldo_uang_muka" => "saldo_uang_muka",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "Description",
                "jml" => "Qty",
            ),
            2 => array(
                "nama" => "Description",
                "jml" => "Qty",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "saldo_uang_muka" => "saldo",
                "harga" => "Nominal",
            ),
            2 => array(
                "saldo_uang_muka" => "saldo",
                "harga" => "Nominal",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "shoppingCartNoteEditabled" => array(
            1 => true,
            2 => true,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
            ),
            2 => array(
                "harga",
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
        ),
        "shoppingCartSumFields" => array(
            1 => array(
//                "total_diskon" => "diskon",
//                "dpp_nilai" => "DPP",
//                "ppn" => "PPN",
                "harga" => "<r class=\"meta\">kas</r> Dikembalikan",
            ),
        ),
//        "shopingCartAddTax" => array(
//            "fields" => array(
////                "non_ppn" => "non ppn",
//                "include_ppn" => "termasuk ppn",
//                "exclude_ppn" => "belum termasuk ppn",
//            ),
//            "inputType" => "checkbox",
//            "selector" => "_selectorPihak/selectTax",
//            "srcGateValue" => "harga",
//            // "srcGateValue"=>"sisa",
//
//        ),
        "pairRegistries" => array(
            "main",
        ),
        "receiptElements" => array(
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "CUSTOMER DETAILS",
                "mdlName" => "MdlCustomer_and_pre",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "Nama",
                    "alamat_1" => "Alamat",
                    "alamat_2" => "alamat",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Tlp",
                    "tlp_1" => "Tlp",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    "nik" => "NIK",
                ),
                "editPoints" => array(1, 2, 3),
            ),
//            "paymentMethod" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "Payment Method",
//                "mdlName" => "MdlPaymentMethod2",
//                //                "mdlName" => "MdlPaymentMethodCredit",
//                //                "mdlFilter"   => array("extern_id=pihakID"),
//                "key" => "id",
////                "defaultValue" => "cash",
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "name" => "",
//                ),
//                "editPoints" => array(1,),
//            ),
//            "referensiNota" => array(
////                "hiddenSelect" => true,
////                "hiddenBox" => true,
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "metode uang muka",
//                "mdlName" => "MdlUangMukaSalesStatic",
//                "mdlFilter" => array(),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "nama" => "metode",
//                    "keterangan" => "keterangan",
////                    "alamat_1" => "",
////                    "country" => "Country",
////                    "tlp_1" => "Phone",
////                    "tlp_2" => "Fax",
////                    //                    "npwp" => "NPWP",
////                    //                    "alias" => "Attn",
////                    "contact_person" => "Attn",
//                ),
//                "editPoints" => array(1, 2, 3),
//                //untuk inject nilai dari triger element lihat di _Shopingcart->fetchElement
//                "relInject" => array(
//                    1 => array("selectedType_konsumen" => "exclude_ppn"),
////                    2=>array("selectedType_konsumen"=>"include_ppn"),
//                ),
//            ),
            "uangMuka" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Uang Muka (DP tanpa PPN)",
                "mdlName" => "MdlCustomerUangMuka",
                "mdlFilter" => array(
                    "extern_id=pihakID",
                    "cabang_id=cabangID",
                    "extern_label2=.customer",
                    "sisa>.0",
                ),
                "key" => "id",
                "labelSrc" => "sisa",
                "usedFields" => array(
                    "extern_nama" => "customer",
                    "extern_id" => "pihakID",
                    "extern_label2" => "tipe",
                ),
                "editPoints" => array(1),
//                "noValidate" => true,
//                "noPrefetch" => true,
//                "pairMethod" => array(
//                    "recom" => "ReComUangMuka",
//                    "calculate" => array(
//                        "source" => "sisa",
//                        "target" => "uang_muka_dipakai",
//                        "pair_source" => "nilai_sisa",//sunbe sumber yang dibandingkan
//                    ),
//                ),
            ),
            "cash_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "cash account",
                "showNull" => true,
                "nullSrc" => "balance",
                "nullValue" => "<span class='text-red text-bold'>{saldo kosong}</span>",
                "pairedModel" => array(
                    "mdlName" => "ComLockerValue",
                    "mdlMethod" => "fetchBalances",
                    "mdlFilter" => array(
                        "cabang_id" => ".-1",
                        "state" => ".active",
                    ),
                    "key" => "produk_id",
                    "rekening" => array(
                        "kas",
                    ),
                    "fieldID" => "nilai",
                    "fieldLabel" => "saldo",
                ),
                "mdlName" => "MdlBankAccount_cash_and_in",
//                "mdlName" => "MdlBankAccount_cash",
                "mdlFilter" => array(
//                            "cabang_id=placeID",
//                            "jenis2=.1",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account number",
//                    "alias" => "holder alias",
                    "saldo" => "balance",
                    "folders" => "acountMasterID",
                    "folders_nama" => "accountMaster",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
                /*
                 * pairMethod tak matiin dulu gak tau buat apa widi 1/11/2024
                 */
//                        "pairMethod" => array(
//                            "recom" => "ReComCashAccountJenis",
//                            "calculate" => array(
//                                "source" => "cash_account",
//                                "filter" => array(
//                                    "cabang_id=placeID",
//                                ),
//                                "result" => array(
//                                    "nilai_setoran_tunai" => "nilai_entry",
//                                ),
//                            ),
//                        ),
            ),
        ),
        "relativeElements" => array(
//            "paymentMethod" => array(
//                "cash" => array(
//                    "cash_account" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "cash account",
////                        "mdlName" => "MdlBankAccount_cash_and_in",
//                        "mdlName" => "MdlBankAccount_cash",
//                        "mdlFilter" => array(
////                            "cabang_id=placeID",
////                            "jenis2=.1",
//                        ),
//                        "key" => "id",
//                        "labelSrc" => "nama",
//                        "usedFields" => array(
//                            "nama" => "account number",
//                            "alias" => "holder alias",
//
//                        ),
//                        "editPoints" => array(1,),
//                        "noValidate" => true,
//                        /*
//                         * pairMethod tak matiin dulu gak tau buat apa widi 1/11/2024
//                         */
//
////                        "pairMethod" => array(
////                            "recom" => "ReComCashAccountJenis",
////                            "calculate" => array(
////                                "source" => "cash_account",
////                                "filter" => array(
////                                    "cabang_id=placeID",
////                                ),
////                                "result" => array(
////                                    "nilai_setoran_tunai" => "nilai_entry",
////                                ),
////                            ),
////                        ),
//                    ),
//                ),
//                "cashless" => array(
//                    "cash_account" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "combo",
//                        "label" => "EDC",
////mdlName" => "MdlBankAccount_cash_and_in",
//                        "mdlName" => "MdlEdc",
//                        "key" => "id",
//                        "labelSrc" => "nama",
//                        "mdlFilter" => array(),
//                        "usedFields" => array(
//                            "nama" => "alias",
//                            "folders_nama" => "rekening",
//                            "machine_id" => "MID",
//                            "jenis" => "jenis",
//                            "folders" => "relID"
//                        ),
//                        "editPoints" => array(1,),
//                        "noValidate" => true,
//                        /*
// * pairMethod tak matiin dulu gak tau buat apa widi 1/11/2024
// */
////                        "pairMethod" => array(
////                            "recom" => "ReComCashAccountJenis",
////                            "calculate" => array(
////                                "source" => "cash_account",
////                                "filter" => array(
////                                    "cabang_id=placeID",
////                                ),
////                                "result" => array(
////                                    "nilai_setoran_tunai" => "nilai_entry",
////                                ),
////                            ),
////                        ),
//                    ),
//                ),
//                "transfer" => array(
//                    "cash_account" => array(
//                        "elementType" => "dataModel",
////                        "inputType" => "radio",
//                        "inputType" => "combo",
//                        "label" => "rekening bank",
////                                        "mdlName" => "MdlBankAccount_cash_and_in",
//                        "mdlName" => "MdlBankAccount_in",
//                        "key" => "id",
//                        "labelSrc" => "nama",
//                        "mdlFilter" => array(),
//                        "usedFields" => array(
//                            "folders_nama" => "bank",
//                            "nama" => "rekening",
//                            "machine_id" => "MID",
//                            "jenis" => "jenis",
//                            "folders" => "relID"
//                        ),
//                        "editPoints" => array(1,),
//                        "noValidate" => true,
//                        /*
// * pairMethod tak matiin dulu gak tau buat apa widi 1/11/2024
// */
////                        "pairMethod" => array(
////                            "recom" => "ReComCashAccountJenis",
////                            "calculate" => array(
////                                "source" => "cash_account",
////                                "filter" => array(
////                                    "cabang_id=placeID",
////                                ),
////                                "result" => array(
////                                    "nilai_setoran_tunai" => "nilai_entry",
////                                ),
////                            ),
////                        ),
//                    ),
//                ),
//            ),
//            "referensiNota" => array(
//                "2" => array(
//                    "referensi_so" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "combo",
//                        "label" => "sales order",
//                        "mdlName" => "MdlTransaksi2",//ini klonengan mdltransaksi
//                        "key" => "id",
//                        "mdlFilter" => array(
//                            "customers_id=pihakID",
//                            "jenis=.5822so",
//                            "link_id=.0",
//
//                        ),
//                        "labelSrc" => "nomer",
//                        "usedFields" => array(
//                            "id_master" => "mid",
//                            "id" => "referensi order",
//                            "fulldate" => "tgl order",
//                            "nomer" => "nomer po/order",
//                            "oleh_nama" => 'salesman'
//                        ),
//                        "editPoints" => array(1,),
//                    ),
//                ),
//            ),
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
        "pairMakers" => array(
            1 => array(
                "saldoRekening" => array(
                    "helperName" => "he_cek_uang_muka_source",
                    "functionName" => "cekUangMukaSource",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "pihakID",
                        "label" => ".uang muka konsumen",
                        "extern_label2" => ".customer",
                    ),
                    "target" => "items",
                ),
            ),
        ),
//        "pairInjectors" => array(
//            1 => array(
//                "stokProduk" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "stok",
//                    ),
//                ),
//                "stokProduk_center" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "stok_center",
//                    ),
//                ),
//            ),
//        ),

        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "settlementHistoryFields" => array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "customers_nama" => "customer",
            "jenis_label" => "activity",
            "transaksi_nilai" => "orig. value",
            "add_disc" => "discount",
            "grand_total" => "nett",
        ),
        "validatePaymentSource" => array(//            "3" => "MdlLockerValue",
        ),
        "allowedMainEdit" => array(1, 2,),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "9467e",
                "label" => "EDIT request uang muka (dp tanpa ppn)",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "9467rj",
                "label" => "REJECT request uang muka (dp tanpa ppn)",
            ),
        ),
        "connectTo" => "19467",
        "connectoValidate" => array(
            1 => "harga",
        ),
        "replacerConnectTo" => array(
            "cabang2ID" => "-1",
            "cabang2Name" => "pusat",
            "place2ID" => "-1",
            "place2Name" => "pusat",
            "gudang2ID" => "-1",
            "gudang2Name" => "default center warehouse",
//            "efaktur_source" => "nomer",//untuk ambil jika lintas cabang
//            "harga" => "dpp_nilai",
//
//            "pihakID" => "placeID",
//            "pihakName" => "placeName",
        ),
        "replacerConnectToItems" => array(
            "nett1" => "harga",

        ),
        "shoppingCartEditableValidator" => array(
            1 => array(
                "harga" => "saldo_uang_muka",
            ),
        ),
    ),
    "19467" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "otorisasi pengembalian uang ke konsumen",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "otorisasi pengembalian uang ke konsumen",
                "actionLabel" => "otorisasi pengembalian uang ke konsumen",
                "source" => "",
                "target" => "19467r",
                "userGroup" => "sys",
                "stateLabel" => "receive",
                "stateColor" => "#ff7700",
                "stateCaption" => "received by",
            ),
            2 => array(
                "label" => "pengembalian uang ke konsumen",
                "actionLabel" => "pengembalian uang ke konsumen",
                "source" => "19467r",
                "target" => "19467",
                "userGroup" => "c_finance",
                "stateLabel" => "receive",
                "stateColor" => "#ff7700",
                "stateCaption" => "received by",
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlUangMuka",
        "selectorSrcModel" => "MdlUangMuka",
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
            "status=.1",
            "trash=.0",
            "ppn=.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array(
            "nama",
        ),
        "selectorProcessor" => "_processSelectBiaya/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCustomer_and_pre",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "cabang",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "referensi_nomer" => "reference number",
            "oleh_nama" => "person",
            "cash_account__label" => "bank account",
//            "harga" => "amount",
            "nett" => "amount",
            "next_pic" => "Next step otorisator",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "nett" => "total amount",
            "keterangan" => "keterangan",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
                "dtime" => "date",
                "customers_nama" => "customer",
                "nomer_top" => "Number",
                "oleh_nama" => "person",
                "cash_account__label" => "bank account",
                "harga" => "nilai",
//                "nett" => "amount",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
                "no" => "no",
                "dtime" => "date",
                "customers_nama" => "customer",
                "nomer_top" => "REQ Number",
                "nomer" => "Number",
                "oleh_nama" => "person",
                "cash_account__label" => "bank account",
                "harga" => "nilai",
//                "nett" => "amount",
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
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),

        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "saldo_uang_muka" => "saldo_uang_muka",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "Description",
                "jml" => "Qty",
            ),
            2 => array(
                "nama" => "Description",
                "jml" => "Qty",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "saldo_uang_muka" => "saldo",
                "harga" => "Nominal",
            ),
            2 => array(
                "saldo_uang_muka" => "saldo",
                "harga" => "Nominal",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "shoppingCartNoteEditabled" => array(
            1 => true,
            2 => true,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
            ),
            2 => array(
                "harga",
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
        ),
        "shoppingCartSumFields" => array(
            1 => array(
//                "total_diskon" => "diskon",
//                "dpp_nilai" => "DPP",
//                "ppn" => "PPN",
                "harga" => "<r class=\"meta\">kas</r> Dikembalikan",
            ),
            2 => array(
//                "total_diskon" => "diskon",
//                "dpp_nilai" => "DPP",
//                "ppn" => "PPN",
                "harga" => "<r class=\"meta\">kas</r> Dikembalikan",
            ),
        ),
//        "shopingCartAddTax" => array(
//            "fields" => array(
////                "non_ppn" => "non ppn",
//                "include_ppn" => "termasuk ppn",
//                "exclude_ppn" => "belum termasuk ppn",
//            ),
//            "inputType" => "checkbox",
//            "selector" => "_selectorPihak/selectTax",
//            "srcGateValue" => "harga",
//            // "srcGateValue"=>"sisa",
//
//        ),
        "pairRegistries" => array(
            "main",
        ),
        "receiptElements" => array(
//            "customerDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "CUSTOMER DETAILS",
//                "mdlName" => "MdlCustomer_and_pre",
//                "mdlFilter" => array("id=pihakID"),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "nama" => "Nama",
//                    "alamat_1" => "Alamat",
//                    "tlp_1" => "Phone",
//                    "tlp_2" => "Fax",
//                    "npwp" => "NPWP",
//                    "alias" => "Attn",
//                    "contact_person" => "Attn",
//                ),
//                "editPoints" => array(1, 2, 3),
//            ),
//            "uangMuka" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "Uang Muka (DP tanpa PPN)",
//                "mdlName" => "MdlCustomerUangMuka",
//                "mdlFilter" => array(
//                    "extern_id=pihakID",
//                    "cabang_id=cabangID",
//                    "extern_label2=.customer",
//                    "sisa>.0",
//                ),
//                "key" => "id",
//                "labelSrc" => "sisa",
//                "usedFields" => array(
//                    "extern_nama" => "customer",
//                    "extern_id" => "pihakID",
//                    "extern_label2" => "tipe",
//                ),
//                "editPoints" => array(1),
////                "noValidate" => true,
////                "noPrefetch" => true,
////                "pairMethod" => array(
////                    "recom" => "ReComUangMuka",
////                    "calculate" => array(
////                        "source" => "sisa",
////                        "target" => "uang_muka_dipakai",
////                        "pair_source" => "nilai_sisa",//sunbe sumber yang dibandingkan
////                    ),
////                ),
//            ),
//            "cash_account" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "cash account",
//                "showNull" => true,
//                "nullSrc" => "balance",
//                "nullValue" => "<span class='text-red text-bold'>{saldo kosong}</span>",
//                "pairedModel" => array(
//                    "mdlName" => "ComLockerValue",
//                    "mdlMethod" => "fetchBalances",
//                    "mdlFilter" => array(
//                        "cabang_id" => ".-1",
//                        "state" => ".active",
//                    ),
//                    "key" => "produk_id",
//                    "rekening" => array(
//                        "kas",
//                    ),
//                    "fieldID" => "nilai",
//                    "fieldLabel" => "saldo",
//                ),
//                "mdlName" => "MdlBankAccount_cash_and_in",
////                "mdlName" => "MdlBankAccount_cash",
//                "mdlFilter" => array(
////                            "cabang_id=placeID",
////                            "jenis2=.1",
//                ),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "nama" => "account number",
////                    "alias" => "holder alias",
//                    "saldo" => "balance",
//                    "folders" => "acountMasterID",
//                    "folders_nama" => "accountMaster",
//                ),
//                "editPoints" => array(1,),
//                "noValidate" => true,
//                /*
//                 * pairMethod tak matiin dulu gak tau buat apa widi 1/11/2024
//                 */
////                        "pairMethod" => array(
////                            "recom" => "ReComCashAccountJenis",
////                            "calculate" => array(
////                                "source" => "cash_account",
////                                "filter" => array(
////                                    "cabang_id=placeID",
////                                ),
////                                "result" => array(
////                                    "nilai_setoran_tunai" => "nilai_entry",
////                                ),
////                            ),
////                        ),
//            ),

        ),
        "relativeElements" => array(),
        "relativeOptions" => array(),
        "pairMakers" => array(
            1 => array(
                "saldoRekening" => array(
                    "helperName" => "he_cek_uang_muka_source",
                    "functionName" => "cekUangMukaSource",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "pihakID",
                        "label" => ".uang muka konsumen",
                        "extern_label2" => ".customer",
                    ),
                    "target" => "items",
                ),
            ),
        ),
//        "pairInjectors" => array(
//            1 => array(
//                "stokProduk" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "stok",
//                    ),
//                ),
//                "stokProduk_center" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "stok_center",
//                    ),
//                ),
//            ),
//        ),

        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "settlementHistoryFields" => array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "customers_nama" => "customer",
            "jenis_label" => "activity",
            "transaksi_nilai" => "orig. value",
            "add_disc" => "discount",
            "grand_total" => "nett",
        ),
        "validatePaymentSource" => array(//            "3" => "MdlLockerValue",
        ),
        "allowedMainEdit" => array(1, 2,),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "9467e",
                "label" => "EDIT request uang muka (dp tanpa ppn)",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "9467rj",
                "label" => "REJECT request uang muka (dp tanpa ppn)",
            ),
        ),
//        "connectTo" => "19467",
//        "connectoValidate" => array(
//            1 => "nilai_payment_source",
//        ),
//        "replacerConnectTo" => array(
//            "cabang2ID" => ".-1",
//            "cabang2Name" => ".pusat",
//            "place2ID" => ".-1",
//            "place2Name" => ".pusat",
//            "gudang2ID" => ".-1",
//            "gudang2Name" => ".default center warehouse",
////            "efaktur_source" => "nomer",//untuk ambil jika lintas cabang
////            "harga" => "dpp_nilai",
////
////            "pihakID" => "placeID",
////            "pihakName" => "placeName",
//        ),
//        "replacerConnectToItems" => array(
//            "nett1" => "harga",
//
//        ),
//        "shoppingCartEditableValidator" => array(
//            1 => array(
//                "harga" => "saldo_uang_muka",
//            ),
//        ),
    ),

    // penerimaan penjualan tunai
    "4464_OLD" => array(
        "icon" => "fa fa-money",
        "label" => "Penerimaan penjualan tunai",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "Penerimaan penjualan tunai",
                "actionLabel" => "terima",
                "source" => "",
                "target" => "4464",
                "userGroup" => "o_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "finance",
            ),
        ),
        "paymentConfig" => true,
        "isPaymentRadioSelect" => true,
        "template" => "template/transaksi_payment.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.582",
            // "transaksi_nilai_sisa>.0",
            "transaksi_nilai_sisa>.1000",
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
            //            "jenis_label"    => "activity",
            "dtime" => "tanggal",
            "customers_nama" => "konsumen",
            "nomer" => "nomer",
            "details" => "detail",
            "oleh_nama" => "person",
            "cash_account__label" => "account",
//            "sisa" => "tagihan",
//            "nilai_biaya" => "biaya usaha",
//            "ppn_nilai_dibayar" => "PPN DIBAYAR BENDAHARA NEGARA",
//            "pph22_nilai" => "PPH 22 DIBAYAR DIMUKA",
//            "credit_note_dipakai" => "credit note<br>(from return)",
//            "uang_muka_dipakai" => "uang muka",
            "nilai_entry" => "uang diterima",
//            "new_sisa"            => "sisa tagihan",
//            "kelebihanBayar__label" => "kelebihan bayar",
//            "deposit_konsumen" => "deposit konsumen",
//            "pendapatan_lain_lain" => "pendapatan lain-lain",
            "credit_note_dipakai" => "credit note<br>(from return)",
            "uang_muka_dipakai" => "uang muka (dp tanpa ppn)",
//            "nilai_entry" => "CASH RECEIVED",
////            "new_sisa"            => "sisa tagihan",
//            "kelebihanBayar__label" => "kelebihan bayar",
//            "deposit_konsumen" => "deposit konsumen",
//            "pendapatan_lain_lain" => "pendapatan lain-lain",
            "description" => "catatan",
        ),
        "historyFields" => array(
            1 => array(
                //            "jenis_label"    => "activity",
                "dtime" => "date",
                "customers_nama" => "customer",
                "nomer" => "receipt number",
                "details" => "detail",
                "oleh_nama" => "person",
                "cash_account__label" => "account",
//            "sisa" => "tagihan",
//            "nilai_biaya" => "biaya usaha",
//            "ppn_nilai_dibayar" => "PPN DIBAYAR BENDAHARA NEGARA",
//            "pph22_nilai" => "PPH 22 DIBAYAR DIMUKA",
//            "credit_note_dipakai" => "credit note<br>(from return)",
//            "uang_muka_dipakai" => "uang muka",
                "nilai_entry" => "CASH RECEIVED",
//            "new_sisa"            => "sisa tagihan",
                "lebih_bayar" => "kelebihan bayar",
                "deposit_konsumen" => "deposit konsumen",
                "pendapatan_lain_lain" => "pendapatan lain-lain",
                "print_label" => "tool",
            ),

        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "nilai_entry" => "amount",
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
//        "shoppingCart" => array(
//            "initPrices" => "beli",
//        ),
//        "shoppingCartFields" => array(
//            1 => array(
//                "nama" => "item name",
//                "jml" => "qty",
//            ),
//
//        ),
//        "shoppingCartFieldSrc" => array(
//            "nama" => "nomer",
//            "tagihan" => "tagihan",
//            "terbayar" => "terbayar",
//            "sisa" => "sisa",
//            "ppn" => "ppn",
////            "ppn_nilai_dibayar" => "ppn",
//            "dpp_ppn" => "dpp_ppn",
////            "dpp_nilai" => "dpp_ppn",
//
//        ),
//        "shoppingCartNumFields" => array(
//            1 => array(
//                "sisa" => "due remain",
//            ),
//        ),
//        "shoppingCartEditableFields" => array(),
//        "shoppingCartAmountValue" => array(
//            1 => "sisa",
//        ),
//        "shoppingCartSumFields" => array(
//            1 => array(
//                //                "sisa" => "total amount",
//                //                "credit_note_dipakai" => "customer credit amount",
//                //                "nilai_entry" => "paid using cash account",
//                //                "nilai_bayar" => "total amount of payment",
//                //                "new_sisa" => "remain receivable (from list)",
//                //                "sisa-nilai_bayar" => "remain receivable (from list)",
//            ),
//        ),
        //untuk settlement format paymentsource denga extrnBlobValue
        "connectSettlement" => array(
            "modul" => "settlement",
            "target" => "7761",
            "targetPayment" => "7760",
            "btnLabel" => "Settlement",
        ),
        "settlementFields" => array(
            "dtime" => "tanggal",
//            "fulldate"        => "tanggal",
            "customers_nama" => "konsumen",
            "nomer" => "invoice",
            "trash_4" => "status",
            "oleh_nama" => "kasir",
            "transaksi_nilai" => "nilai",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "produk",
                "produk_kode" => "SKU",
//                "no_part" => "part number",
//                "stok_center" => "stok dc",
//                "stok" => "stok available",
//
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "no_part" => "no_part",
            "label" => "label",
            "satuan" => "satuan",
            "ppn" => "harga*(ppnFactor/100)",
//            "berat_gross" => "berat_gross",
//            "lebar_gross" => "lebar_gross",
//            "panjang_gross" => "panjang_gross",
//            "tinggi_gross" => "tinggi_gross",
//            "volume_gross" => "volume_gross",

//            "volume" => "volume",
//            "berat" => "berat",
//            "lebar" => "lebar",
//            "tinggi" => "tinggi",
//            "panjang" => "panjang",
            "jenis" => "jenis",
            "produk_jenis_id" => "produk_jenis_id",
            "produk_jenis_nama" => "produk_jenis_nama",
            "reference_jenis" => "reference_jenis",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "harga",
                "disc_percent" => "diskon (%)",
                "disc" => "diskon (IDR)",
                // "ppn" => "VAT",
                "nett1" => "harga(net)",
            ),
        ),
        "shoppingCartEditableFields" => array(
//            1 => array(
//                "jml",
//                "produk_ord_jml",
//                "disc_percent",
//                "disc",
//                "nett1",
//            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*(harga-disc+ppn)",//nett2
        ),
        "shoppingCartHideSubamount" => array(
            1 => false,
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "total amount bruto",
//                "disc" => "disc",
//                "add_diskon" => "diskon tambahan",
                "total_diskon" => "diskon",
                "ongkir_ui" => "shipping service",
//                "nilai_pembulatan" => "pembulatan",
                "nett1_bulat" => "total amount netto*",
                "ppn_out_bulat" => "PPN",
                "grand_pembulatan" => "grand total",
            ),

        ),
        "pairRegistries" => array(
            "main", "items"
        ),
        "shoppingCartAvoidRemove" => true,
        "shoppingCartAvoidRemoveAll_items" => false,
        "tagihanSrc" => "harus_bayar",

        /*-------------*/
        "receiptElements" => array(
            "customerDetails" => array(
                "elementType" => "dataModel",
                "hiddenSelect" => true,
                "inputType" => "radio",
                "label" => "customer details",
                // "mdlName"     => "MdlCustomer_and_pre",
                "mdlName" => "MdlCustomerAll",//gak mandang trash dan status
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "Name",
                    "alamat_1" => "alamat",
                    "alamat_2" => "alamat",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Tlp",
                    "tlp_1" => "Tlp",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    "nik" => "NIK",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
            "kelebihanBayar" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Kelebihan Bayar",
                "mdlName" => "MdlStaticMethodLebihBayar",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "method",
                ),
                "editPoints" => array(1,),
                "targetMethod" => array(
                    //                    0 => "ReComMethodLebihBayar",
                    1 => "ReComMethodLebihBayar",
                    2 => "ReComMethodLebihBayar",
                ),
                "noValidate" => true,
                "noPrefetch" => true,
            ),
            "paymentMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Payment Method",
                "mdlName" => "MdlPaymentMethod2",
                //                "mdlName" => "MdlPaymentMethodCredit",
                //                "mdlFilter"   => array("extern_id=pihakID"),
                "key" => "id",
//                "defaultValue" => "cash",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
            ),
            "creditAmount" => array(
                "elementType" => "dataModel",
                "hiddenSelect" => true,
                "inputType" => "radio",
                "label" => "credit note(deposit) return",
                "mdlName" => "MdlPaymentAntiSource",
                "mdlFilter" => array(
                    "extern_id=pihakID",
                    "cabang_id=cabangID",
                    "label=.piutang dagang",
                    "sisa>.0",
                    "trash=.0",
                ),
                "key" => "sisa",
                "labelSrc" => "sisa",
                "usedFields" => array(
                    "extern_nama" => "customer name",
                    "sisa" => "avail credit",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
//                "autoSelect" => false,
//                "noPrefetch" => true,
//                "pairMethod" => array(
//                    "recom" => "ReComCreditNote",
//                    "calculate" => array(
//                        "source" => "creditAmount", // credit_note
//                        "target" => "credit_note_dipakai",
//                        "pair_source" => "creditAmount",//sumber sumber yang dibandingkan /// nilai_sisa
//                    ),
//
//                    //                    "customer" => "ReComDiscCustomer",
//                ),
            ),
            "uangMuka" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Uang Muka (DP tanpa PPN)",
                "mdlName" => "MdlCustomerUangMuka",
                "mdlFilter" => array(
                    "extern_id=pihakID",
                    "cabang_id=cabangID",
                    "extern_label2=.customer",
                    "sisa>.0",
                ),
                "key" => "id",
                "labelSrc" => "sisa",
                "usedFields" => array(
                    "extern_nama" => "customer",
                    "extern_id" => "pihakID",
                    "extern_label2" => "tipe",
                    "sisa" => "saldo",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
                "noPrefetch" => true,
                "pairMethod" => array(
                    "recom" => "ReComUangMuka",
                    "calculate" => array(
                        "source" => "sisa",
                        "target" => "uang_muka_dipakai",
                        "pair_source" => "sisa",//sunbe sumber yang dibandingkan
                    ),
                ),
            ),
            "referensi_order" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Referensi SO",
                "mdlName" => "MdlTransaksi2",//ini klonengan mdltransaksi
                "key" => "id",
                "mdlFilter" => array(
                    "id=refID",
                ),
                "labelSrc" => "nomer",
                "usedFields" => array(
                    "id_top" => "id top",
                    "nomer" => "nomer sales order",
                    "nomer_top" => "nomer pre-order",
                    "jenis" => "jenis",
                    "jenis_top" => "jenis master",
                ),
                "editPoints" => array(1,),
            ),
            "dummyElement" => array(
                "elementType" => "dataModel",
//                "hiddenSelect" => true,
                "hiddenBox" => true,
                "inputType" => "radio",
                "label" => "auto-validation",
                "mdlName" => "MdlDummyElement",
                //                "mdlFilter"   => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",
                ),
                "hideRow" => false,
                "editPoints" => array(1, 2, 3),
            ),
        ),
        "relativeElements" => array(
            "paymentMethod" => array(
                "cash" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "cash account",
//mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlName" => "MdlBankAccount_cash",
                        "key" => "id",
                        "labelSrc" => "nama",
                        "mdlFilter" => array(),
                        "usedFields" => array(
                            "nama" => "alias",
                            "folders_nama" => "rekening",
                            "machine_id" => "MID",
                            "jenis" => "jenis",
                            "folders" => "relID"
                        ),
                        "editPoints" => array(1,),
                    ),
                ),
                "cashless" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "combo",
                        "label" => "EDC",
//mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlName" => "MdlEdc",
                        "key" => "id",
                        "labelSrc" => "nama",
                        "mdlFilter" => array(),
                        "usedFields" => array(
                            "nama" => "alias",
                            "folders_nama" => "rekening",
                            "machine_id" => "MID",
                            "jenis" => "jenis",
                            "folders" => "relID"
                        ),
                        "editPoints" => array(1,),
                    ),
                ),
                "transfer" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
//                        "inputType" => "radio",
                        "inputType" => "combo",
                        "label" => "rekening bank",
//                                        "mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlName" => "MdlBankAccount_in",
                        "key" => "id",
                        "labelSrc" => "nama",
                        "mdlFilter" => array(),
                        "usedFields" => array(
                            "folders_nama" => "bank",
                            "nama" => "rekening",
                            "machine_id" => "MID",
                            "jenis" => "jenis",
                            "folders" => "relID"
                        ),
                        "editPoints" => array(1,),
                    ),
                ),

            ),
        ),
        "relativeOptions" => array(
            "paymentMethod" => array(
                "transfer" => array(
                    "rekening_konsumen" => array(
                        "label" => "rekening konsumen",
                        "defaultValue" => "",
                        "inputMode" => "text",
                        "addPoints" => array(1, 2),
                    ),

                ),
            ),
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "uang_muka_dipakai" => array(
                        "label" => "Uang Muka",
                        "defaultValue" => "0",
                        "saldo" => "uangMuka__sisa",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "credit_amount" => array(
                        "label" => "credit note(deposit) return",
//                        "defaultValue" => "credit_note_dipakai",
                        "defaultValue" => "0",
                        "maxValue" => "creditAmount__sisa",
                        "saldo" => "creditAmount__sisa",
//                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "pph23" => array(
                        "label" => "potongan pph23",
                        "defaultValue" => "0",
                        "maxValue" => "",
//                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
                        "disabled" => "",
                        "addPoints" => array(1),
//                        "editPoints" => array(4),
//                        "validate_reference" => array(
//                            "src_reference" => "reference_jenis",
//                            "defaultView" => array(
//                                "584" => "show",
//                            ),
//                        ),
//                        "validate_value" => array(
//                            "reference_jenis" => "584",
//                        ),
//                        "defaultView" => "none",
                    ),
                    "nilai_bayar_netto" => array(
                        "label" => "netto",
//                        "defaultValue" => "credit_note_dipakai",
                        "defaultValue" => "0",
                        "maxValue" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "nilai_entry" => array(
                        "label" => "cash received",
                        "defaultValue" => "0",
//                        "defaultValue" => "nilai_cash",
//                        "keyupAction" => "if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('grand_pembulatan').value)) || parseInt(removeCommas(this.value))<0){this.value=addCommas(document.getElementById('grand_pembulatan').value);}",
                        "keyupAction" => "if(parseInt(removeCommas(this.value))<parseInt(removeCommas(document.getElementById('nilai_bayar_netto').value)) || parseInt(removeCommas(this.value))<0){this.value=addCommas(document.getElementById('nilai_bayar_netto').value);}",
//                        "keyupAction" => "if(parseInt(removeCommas(this.value))<0){this.value=addCommas(document.getElementById('grand_pembulatan').value);}",
                        "addPoints" => array(1,),
                    ),
                    "lebih_bayar" => array(
                        "label" => "lebih bayar",
                        "defaultValue" => ".0",
                        //                        "maxValue" => "new_sisa",
                        //                        "minValue" => "new_sisa",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        //                        "hideRow" =>true,
                    ),
                ),
            ),
        ),
        /*------------*/

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
//        "pairRegistries"                        => array(
//            "main",
//        ),
//
        "mainValueInjectors" => array(
            "amount" => "sisa",
            "creditAmount" => "creditAmount",
            "harus_bayar" => "harus_bayar",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "customer ID",
            "pihakName" => "customer name",
//            "selectedType_konsumen" => "Tipe konsumen",
        ),
        "shoppingCartRowNumValidators" => array(//            "nilai_entry" => "amount of payment",
        ),
//        "shopingCartAddTax" => array(
//            "fields" => array(
//                "reguler" => "reguler", "bendahara_negara" => "bendahara negara"
//            ),
//            "inputType" => "checkbox",
//            "selector" => "_selectorPihak/selectTax",
//            "srcGateValue" => "nilai_sisa",
//            // "srcGateValue"=>"sisa",
//
//        ),
        "shoppingCartUnionValidators" => array(
            array(
                "creditAmount" => "credit amount",
                "nilai_entry" => "amount value",
                "nilai_biaya" => "amount value",
                "uang_muka_dipakai" => "uang muka",
            ),
        ),
//        "shopingCartUnionComparison" => array(
//            array(
//                "nilai_entry" => "payment belum diisi",
//                "cash_account" => "cash account belum dipilih",
//            ),
//            array(
//                "lebih_bayar" => "kelebihan bayar nol (0)",
//                "kelebihanBayar" => "method kelebihan bayar belum dipilih",
//            ),
//        ),
        "shopingCartPaymentValidator" => array(
            "nilai_entry" => array(
                "label" => "payment belum diisi",
            ),
            "cash_account" => array(
                "label" => "cash account belum dipilih",
            ),
            "creditAmount" => array(
                "label" => "credit note (from return) belum dipilih",
            ),
        ),
//        "shopingCartPairedPaymentValidator" => array(
//            "nilai_entry" => array(
//                "key" => "cash_account",
//                "label" => "cash account belum dipilih.",
//            ),
//        ),
//        "shopingCartPaymentComparisonValidator" => array(
//            array(
//                "source" => "nilai_dipakai_piutang_dagang", // hutang dagang
//                "target" => "nilai_bayar", // payment source
//                "label"  => "Pastikan penggunaan Kas, Uang Muka, Credit Note (from return) sudah sesuai untuk pelunasan Invoice ini.", //
//            ),
//        ),
        "shoppingCartReferenceFields" => array(
            "fulldate" => "tanggal",
            "nomer_top" => "nomer inv",
            "nomer" => "nomer approval",
//            "refNum" => "return ref.",
//            "tagihan" => "due amount",
//            "refValue" => "returned",
//            "terbayar" => "paid",
//            "diskon" => "discount",
            "sisa" => "nilai",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "customer",
//            "qty_nota" => "jumlah<br>invoice",
//            "tagihan" => "due amount",
//            "terbayar" => "paid",
//            "diskon" => "discount",
            "sisa" => "nilai",
            // "a_sisa" => "return",
            // "a_saldo" => "saldo",
        ),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "dueDateReader" => true,
        "kelebihanBayar" => true,
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di cabang {cabang_nama}",
        ),
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "4464re",
                "label" => "EDIT request uang muka (dp tanpa ppn)",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "4464rrj",
                "label" => "REJECT request uang muka (dp tanpa ppn)",
            ),
        ),
        //----
        "shoppingCartLoadReference" => true,
        // ----------------------------------------
        "shoppingCartPerTransaksi" => array(
            "dtime" => array(
                "label" => "tanggal",
                "format" => "formatField",
            ),
            "oleh_nama" => array(
                "label" => "pic",
                "format" => "formatField",
                "link" => "settlement/Settlement/selectPaymentSrc/759/",
                "linkType" => "modal",
                "modalHeader" => "oleh_nama",
                "linkSeg_4" => "extern_id",
            ),
            "extern_nama" => array(
                "label" => "konsumen",
                "format" => "formatField",
            ),
            "nomer_top" => array(
                "label" => "nomer inv",
                "format" => "formatField",
            ),
            "nomer" => array(
                "label" => "nomer approval",
                "format" => "formatField",
            ),
            "sisa" => array(
                "label" => "nilai",
                "format" => "formatField",
            ),
//            "nota_settled" => array(
//                "label" => "urut invoice",
//                "format" => "formatField",
//            ),
//            "jumlah_nota" => array(
//                "label" => "jumlah invoice",
//                "format" => "formatField",
//            ),
//            "extern_nilai5" => array(
//                "label" => "point",
//                "format" => "formatField",
//            ),
//            "extern_nilai6" => array(
//                "label" => "Bruto",
//                "format" => "formatField",
//            ),
//            "diskon" => array(
//                "label" => "Diskon",
//                "format" => "formatField",
//            ),
//            "extern_nilai2" => array(
//                "label" => "Netto",
//                "format" => "formatField",
//            ),
//            "extern_nilai3" => array(
//                "label" => "kelebihan setoran",
//                "format" => "formatField",
//            ),
//            "extern_nilai4" => array(
//                "label" => "kekurangan setoran",
//                "format" => "formatField",
//            ),
            "terima_setoran" => array(
                "label" => "terima",
                "format" => "formatField",
                "link" => "kas/Transaksi/selectPaymentSrc/4464/",
                "linkType" => "modal",
                "modalHeader" => "oleh_nama",
                "linkSeg_4" => "extern_id",
                "tipe" => "button",
            ),

//            "extern_nama" => array(
//                "label" => "konsumen",
//                "link" => "Transaksi/selectPaymentSrc/749/",
//                "linkType" => "modal",
//                "modalHeader" => "extern_nama",
//                "linkSeg_4" => "extern_id",
//                // "modalHeader" => "id",
//            ),

        ),
        "shoppingCartPerTransaksiBtn" => array(
            "btn_satu" => array(
                "label" => "per TRANSAKSI",
                "show_default" => "prog_satu",
            ),
            "btn_dua" => array(
                "label" => "per KONSUMEN",
                // "show_default" => true,
            ),
        ),
        "settlementBtn" => false,
        "shopingCartReload" => true,
        "viewDescriptionNote" => true,
        "kelebihanBayarValidator" => array(
            "enabled" => true,
            "label" => "Terdapat lebih bayar pada pelunasan pembayaran ini. Kelebihan bayar harus dipilih Deposit / Pendapatan lain-lain.",
        ),
        "kurangBayarValidator" => array(
            "enabled" => true,
            "label" => "Terdapat lebih bayar pada pelunasan pembayaran ini. Kelebihan bayar harus dipilih Deposit / Pendapatan lain-lain.",
        ),
    ),
    "4464" => array(
        "icon" => "fa fa-money",
        "label" => "Penerimaan penjualan tunai",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "Penerimaan penjualan tunai",
                "actionLabel" => "terima",
                "source" => "",
                "target" => "4464",
                "userGroup" => "o_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "finance",
            ),
        ),
        "paymentConfig" => true,
        "isPaymentRadioSelect" => false,// true = hanya bisa pilih 1 // false = bisa pilih lebih dari 1
        "template" => "template/transaksi_payment.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.582",
            // "transaksi_nilai_sisa>.0",
            "transaksi_nilai_sisa>.1000",
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
            //            "jenis_label"    => "activity",
            "dtime" => "tanggal",
            "customers_nama" => "konsumen",
            "nomer" => "nomer",
            "details" => "detail",
            "oleh_nama" => "person",
            "cash_account__label" => "account",
//            "sisa" => "tagihan",
//            "nilai_biaya" => "biaya usaha",
//            "ppn_nilai_dibayar" => "PPN DIBAYAR BENDAHARA NEGARA",
//            "pph22_nilai" => "PPH 22 DIBAYAR DIMUKA",
//            "credit_note_dipakai" => "credit note<br>(from return)",
//            "uang_muka_dipakai" => "uang muka",
            "nilai_entry" => "uang diterima",
//            "new_sisa"            => "sisa tagihan",
//            "kelebihanBayar__label" => "kelebihan bayar",
//            "deposit_konsumen" => "deposit konsumen",
//            "pendapatan_lain_lain" => "pendapatan lain-lain",
            "credit_note_dipakai" => "credit note<br>(from return)",
            "uang_muka_dipakai" => "uang muka (dp tanpa ppn)",
//            "nilai_entry" => "CASH RECEIVED",
////            "new_sisa"            => "sisa tagihan",
//            "kelebihanBayar__label" => "kelebihan bayar",
//            "deposit_konsumen" => "deposit konsumen",
//            "pendapatan_lain_lain" => "pendapatan lain-lain",
            "description" => "catatan",
        ),
        "historyFields" => array(
            1 => array(
                //            "jenis_label"    => "activity",
                "dtime" => "date",
                "customers_nama" => "customer",
                "nomer" => "receipt number",
                "details" => "detail",
                "oleh_nama" => "person",
                "cash_account__label" => "account",
//            "sisa" => "tagihan",
//            "nilai_biaya" => "biaya usaha",
//            "ppn_nilai_dibayar" => "PPN DIBAYAR BENDAHARA NEGARA",
//            "pph22_nilai" => "PPH 22 DIBAYAR DIMUKA",
//            "credit_note_dipakai" => "credit note<br>(from return)",
//            "uang_muka_dipakai" => "uang muka",
                "nilai_entry" => "CASH RECEIVED",
//            "new_sisa"            => "sisa tagihan",
                "lebih_bayar" => "kelebihan bayar",
                "deposit_konsumen" => "deposit konsumen",
                "pendapatan_lain_lain" => "pendapatan lain-lain",
                "print_label" => "tool",
            ),

        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "nilai_entry" => "amount",
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
//        "shoppingCart" => array(
//            "initPrices" => "beli",
//        ),
//        "shoppingCartFields" => array(
//            1 => array(
//                "nama" => "item name",
//                "jml" => "qty",
//            ),
//
//        ),
//        "shoppingCartFieldSrc" => array(
//            "nama" => "nomer",
//            "tagihan" => "tagihan",
//            "terbayar" => "terbayar",
//            "sisa" => "sisa",
//            "ppn" => "ppn",
////            "ppn_nilai_dibayar" => "ppn",
//            "dpp_ppn" => "dpp_ppn",
////            "dpp_nilai" => "dpp_ppn",
//
//        ),
//        "shoppingCartNumFields" => array(
//            1 => array(
//                "sisa" => "due remain",
//            ),
//        ),
//        "shoppingCartEditableFields" => array(),
//        "shoppingCartAmountValue" => array(
//            1 => "sisa",
//        ),
//        "shoppingCartSumFields" => array(
//            1 => array(
//                //                "sisa" => "total amount",
//                //                "credit_note_dipakai" => "customer credit amount",
//                //                "nilai_entry" => "paid using cash account",
//                //                "nilai_bayar" => "total amount of payment",
//                //                "new_sisa" => "remain receivable (from list)",
//                //                "sisa-nilai_bayar" => "remain receivable (from list)",
//            ),
//        ),
        //untuk settlement format paymentsource denga extrnBlobValue
        "connectSettlement" => array(
            "modul" => "settlement",
            "target" => "7761",
            "targetPayment" => "7760",
            "btnLabel" => "Settlement",
        ),
        "settlementFields" => array(
            "dtime" => "tanggal",
//            "fulldate"        => "tanggal",
            "customers_nama" => "konsumen",
            "nomer" => "invoice",
            "trash_4" => "status",
            "oleh_nama" => "kasir",
            "transaksi_nilai" => "nilai",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
//                "nomer_top" => "nomer so",
                "jml" => "qty",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nomer",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            "ppn" => "ppn",
//            "ppn_nilai_dibayar" => "ppn",
            "dpp_ppn" => "dpp_ppn",
//            "dpp_nilai" => "dpp_ppn",
            "nomer_top" => "nomer_top",

            "jenis" => "jenis",
            "produk_jenis_id" => "produk_jenis_id",
            "produk_jenis_nama" => "produk_jenis_nama",
            "reference_jenis" => "reference_jenis",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "sisa" => "sisa tagihan",
            ),
        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartHideSubamount" => array(
            1 => false,
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "sisa" => "subtotal",
                //                "credit_note_dipakai" => "customer credit amount",
                //                "nilai_entry" => "paid using cash account",
                //                "nilai_bayar" => "total amount of payment",
                //                "new_sisa" => "remain receivable (from list)",
                //                "sisa-nilai_bayar" => "remain receivable (from list)",
            ),
        ),
        "pairRegistries" => array(
            "main", "items"
        ),
        "shoppingCartAvoidRemove" => true,
        "shoppingCartAvoidRemoveAll_items" => false,
        "tagihanSrc" => "harus_bayar",

        /*-------------*/
        "receiptElements" => array(
            "customerDetails" => array(
                "elementType" => "dataModel",
                "hiddenSelect" => true,
                "inputType" => "radio",
                "label" => "customer details",
                // "mdlName"     => "MdlCustomer_and_pre",
                "mdlName" => "MdlCustomerAll",//gak mandang trash dan status
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "Name",
                    "alamat_1" => "alamat",
                    "alamat_2" => "alamat",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Tlp",
                    "tlp_1" => "Tlp",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    "nik" => "NIK",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
            "kelebihanBayar" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Kelebihan Bayar",
                "mdlName" => "MdlStaticMethodLebihBayar",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "method",
                ),
                "editPoints" => array(1,),
                "targetMethod" => array(
                    //                    0 => "ReComMethodLebihBayar",
                    1 => "ReComMethodLebihBayar",
                    2 => "ReComMethodLebihBayar",
                ),
                "noValidate" => true,
                "noPrefetch" => true,
            ),
            "paymentMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Payment Method",
                "mdlName" => "MdlPaymentMethod2",
                //                "mdlName" => "MdlPaymentMethodCredit",
                //                "mdlFilter"   => array("extern_id=pihakID"),
                "key" => "id",
//                "defaultValue" => "cash",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
            ),
            "creditAmount" => array(
                "elementType" => "dataModel",
                "hiddenSelect" => true,
                "inputType" => "radio",
                "label" => "credit note(deposit) return",
                "mdlName" => "MdlPaymentAntiSource",
                "mdlFilter" => array(
                    "extern_id=pihakID",
                    "cabang_id=cabangID",
                    "label=.piutang dagang",
                    "sisa>.0",
                    "trash=.0",
                ),
                "key" => "sisa",
                "labelSrc" => "sisa",
                "usedFields" => array(
                    "extern_nama" => "customer name",
                    "sisa" => "avail credit",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
//                "autoSelect" => false,
//                "noPrefetch" => true,
//                "pairMethod" => array(
//                    "recom" => "ReComCreditNote",
//                    "calculate" => array(
//                        "source" => "creditAmount", // credit_note
//                        "target" => "credit_note_dipakai",
//                        "pair_source" => "creditAmount",//sumber sumber yang dibandingkan /// nilai_sisa
//                    ),
//
//                    //                    "customer" => "ReComDiscCustomer",
//                ),
            ),
            "uangMuka" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Uang Muka (DP tanpa PPN)",
                "mdlName" => "MdlCustomerUangMuka",
                "mdlFilter" => array(
                    "extern_id=pihakID",
                    "cabang_id=cabangID",
                    "extern_label2=.customer",
                    "sisa>.0",
                ),
                "key" => "id",
                "labelSrc" => "sisa",
                "usedFields" => array(
                    "extern_nama" => "customer",
                    "extern_id" => "pihakID",
                    "extern_label2" => "tipe",
                    "sisa" => "saldo",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
                "noPrefetch" => true,
                "pairMethod" => array(
                    "recom" => "ReComUangMuka",
                    "calculate" => array(
                        "source" => "sisa",
                        "target" => "uang_muka_dipakai",
                        "pair_source" => "sisa",//sunbe sumber yang dibandingkan
                    ),
                ),
            ),
//            "referensi_order" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "Referensi SO",
//                "mdlName" => "MdlTransaksi2",//ini klonengan mdltransaksi
//                "key" => "id",
//                "mdlFilter" => array(
//                    "id=refID",
//                ),
//                "labelSrc" => "nomer",
//                "usedFields" => array(
//                    "id_top" => "id top",
//                    "nomer" => "nomer sales order",
//                    "nomer_top" => "nomer pre-order",
//                    "jenis" => "jenis",
//                    "jenis_top" => "jenis master",
//                ),
//                "editPoints" => array(1,),
//            ),
            "dummyElement" => array(
                "elementType" => "dataModel",
//                "hiddenSelect" => true,
                "hiddenBox" => true,
                "inputType" => "radio",
                "label" => "auto-validation",
                "mdlName" => "MdlDummyElement",
                //                "mdlFilter"   => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",
                ),
                "hideRow" => false,
                "editPoints" => array(1, 2, 3),
            ),
        ),
        "relativeElements" => array(
            "paymentMethod" => array(
                "cash" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "cash account",
//mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlName" => "MdlBankAccount_cash",
                        "key" => "id",
                        "labelSrc" => "nama",
                        "mdlFilter" => array(),
                        "usedFields" => array(
                            "nama" => "alias",
                            "folders_nama" => "rekening",
                            "machine_id" => "MID",
                            "jenis" => "jenis",
                            "folders" => "relID"
                        ),
                        "editPoints" => array(1,),
                    ),
                ),
                "cashless" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "combo",
                        "label" => "EDC",
//mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlName" => "MdlEdc",
                        "key" => "id",
                        "labelSrc" => "nama",
                        "mdlFilter" => array(),
                        "usedFields" => array(
                            "nama" => "alias",
                            "folders_nama" => "rekening",
                            "machine_id" => "MID",
                            "jenis" => "jenis",
                            "folders" => "relID"
                        ),
                        "editPoints" => array(1,),
                    ),
                ),
                "transfer" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
//                        "inputType" => "radio",
                        "inputType" => "combo",
                        "label" => "rekening bank",
//                                        "mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlName" => "MdlBankAccount_in",
                        "key" => "id",
                        "labelSrc" => "nama",
                        "mdlFilter" => array(),
                        "usedFields" => array(
                            "folders_nama" => "bank",
                            "nama" => "rekening",
                            "machine_id" => "MID",
                            "jenis" => "jenis",
                            "folders" => "relID"
                        ),
                        "editPoints" => array(1,),
                    ),
                ),

            ),
        ),
        "relativeOptions" => array(
            "paymentMethod" => array(
                "transfer" => array(
                    "rekening_konsumen" => array(
                        "label" => "rekening konsumen",
                        "defaultValue" => "",
                        "inputMode" => "text",
                        "addPoints" => array(1, 2),
                    ),

                ),
            ),
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "uang_muka_dipakai" => array(
                        "label" => "Uang Muka",
                        "defaultValue" => "0",
                        "saldo" => "uangMuka__sisa",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "credit_amount" => array(
                        "label" => "credit note(deposit) return",
//                        "defaultValue" => "credit_note_dipakai",
                        "defaultValue" => "0",
                        "maxValue" => "creditAmount__sisa",
                        "saldo" => "creditAmount__sisa",
//                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "pph23" => array(
                        "label" => "potongan pph23",
                        "defaultValue" => "0",
                        "maxValue" => "",
//                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
                        "disabled" => "",
                        "addPoints" => array(1),
//                        "editPoints" => array(4),
//                        "validate_reference" => array(
//                            "src_reference" => "reference_jenis",
//                            "defaultView" => array(
//                                "584" => "show",
//                            ),
//                        ),
//                        "validate_value" => array(
//                            "reference_jenis" => "584",
//                        ),
//                        "defaultView" => "none",
                    ),
                    "nilai_bayar_netto" => array(
                        "label" => "netto",
//                        "defaultValue" => "credit_note_dipakai",
                        "defaultValue" => "0",
                        "maxValue" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "nilai_entry" => array(
                        "label" => "cash received",
                        "defaultValue" => "0",
//                        "defaultValue" => "nilai_cash",
//                        "keyupAction" => "if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('grand_pembulatan').value)) || parseInt(removeCommas(this.value))<0){this.value=addCommas(document.getElementById('grand_pembulatan').value);}",
                        "keyupAction" => "if(parseInt(removeCommas(this.value))<parseInt(removeCommas(document.getElementById('nilai_bayar_netto').value)) || parseInt(removeCommas(this.value))<0){this.value=addCommas(document.getElementById('nilai_bayar_netto').value);}",
//                        "keyupAction" => "if(parseInt(removeCommas(this.value))<0){this.value=addCommas(document.getElementById('grand_pembulatan').value);}",
                        "addPoints" => array(1,),
                    ),
                    "lebih_bayar" => array(
                        "label" => "lebih bayar",
                        "defaultValue" => ".0",
                        //                        "maxValue" => "new_sisa",
                        //                        "minValue" => "new_sisa",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        //                        "hideRow" =>true,
                    ),
                ),
            ),
        ),
        /*------------*/

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
//        "pairRegistries"                        => array(
//            "main",
//        ),
//
        "mainValueInjectors" => array(
            "amount" => "sisa",
            "creditAmount" => "creditAmount",
            "harus_bayar" => "harus_bayar",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "customer ID",
            "pihakName" => "customer name",
//            "selectedType_konsumen" => "Tipe konsumen",
        ),
        "shoppingCartRowNumValidators" => array(//            "nilai_entry" => "amount of payment",
        ),
//        "shopingCartAddTax" => array(
//            "fields" => array(
//                "reguler" => "reguler", "bendahara_negara" => "bendahara negara"
//            ),
//            "inputType" => "checkbox",
//            "selector" => "_selectorPihak/selectTax",
//            "srcGateValue" => "nilai_sisa",
//            // "srcGateValue"=>"sisa",
//
//        ),
        "shoppingCartUnionValidators" => array(
            array(
                "creditAmount" => "credit amount",
                "nilai_entry" => "amount value",
                "nilai_biaya" => "amount value",
                "uang_muka_dipakai" => "uang muka",
            ),
        ),
//        "shopingCartUnionComparison" => array(
//            array(
//                "nilai_entry" => "payment belum diisi",
//                "cash_account" => "cash account belum dipilih",
//            ),
//            array(
//                "lebih_bayar" => "kelebihan bayar nol (0)",
//                "kelebihanBayar" => "method kelebihan bayar belum dipilih",
//            ),
//        ),
        "shopingCartPaymentValidator" => array(
            "nilai_entry" => array(
                "label" => "payment belum diisi",
            ),
            "cash_account" => array(
                "label" => "cash account belum dipilih",
            ),
            "creditAmount" => array(
                "label" => "credit note (from return) belum dipilih",
            ),
        ),
//        "shopingCartPairedPaymentValidator" => array(
//            "nilai_entry" => array(
//                "key" => "cash_account",
//                "label" => "cash account belum dipilih.",
//            ),
//        ),
//        "shopingCartPaymentComparisonValidator" => array(
//            array(
//                "source" => "nilai_dipakai_piutang_dagang", // hutang dagang
//                "target" => "nilai_bayar", // payment source
//                "label"  => "Pastikan penggunaan Kas, Uang Muka, Credit Note (from return) sudah sesuai untuk pelunasan Invoice ini.", //
//            ),
//        ),
        "shoppingCartReferenceFields" => array(
            "fulldate" => "tanggal",
            "nomer_top" => "nomer inv",
            "nomer" => "nomer approval",
//            "refNum" => "return ref.",
            "tagihan" => "tagihan",
//            "refValue" => "returned",
            "terbayar" => "paid",
//            "diskon" => "discount",
            "sisa" => "nilai",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "customer",
//            "qty_nota" => "jumlah<br>invoice",
            "tagihan" => "tagihan",
            "terbayar" => "paid",
//            "diskon" => "discount",
            "sisa" => "nilai",
            // "a_sisa" => "return",
            // "a_saldo" => "saldo",
        ),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "dueDateReader" => true,
        "kelebihanBayar" => true,
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di cabang {cabang_nama}",
        ),
        "selectorNotaLocker" => array(
            "enabled" => true,
        ),
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "4464re",
                "label" => "EDIT request uang muka (dp tanpa ppn)",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "4464rrj",
                "label" => "REJECT request uang muka (dp tanpa ppn)",
            ),
        ),
        //----
//        "shoppingCartLoadReference" => true,// me-load data/isi dari nota sales order/nota terpilih.
        "shoppingCartLoadReference" => false,
        // ----------------------------------------
        "shoppingCartPerTransaksi" => array(
            "dtime" => array(
                "label" => "tanggal",
                "format" => "formatField",
            ),
            "oleh_nama" => array(
                "label" => "pic",
                "format" => "formatField",
                "link" => "settlement/Settlement/selectPaymentSrc/759/",
                "linkType" => "modal",
                "modalHeader" => "oleh_nama",
                "linkSeg_4" => "extern_id",
            ),
            "extern_nama" => array(
                "label" => "konsumen",
                "format" => "formatField",
            ),
            "nomer_top" => array(
                "label" => "nomer inv",
                "format" => "formatField",
            ),
            "nomer" => array(
                "label" => "nomer approval",
                "format" => "formatField",
            ),
            "sisa" => array(
                "label" => "nilai",
                "format" => "formatField",
            ),
//            "nota_settled" => array(
//                "label" => "urut invoice",
//                "format" => "formatField",
//            ),
//            "jumlah_nota" => array(
//                "label" => "jumlah invoice",
//                "format" => "formatField",
//            ),
//            "extern_nilai5" => array(
//                "label" => "point",
//                "format" => "formatField",
//            ),
//            "extern_nilai6" => array(
//                "label" => "Bruto",
//                "format" => "formatField",
//            ),
//            "diskon" => array(
//                "label" => "Diskon",
//                "format" => "formatField",
//            ),
//            "extern_nilai2" => array(
//                "label" => "Netto",
//                "format" => "formatField",
//            ),
//            "extern_nilai3" => array(
//                "label" => "kelebihan setoran",
//                "format" => "formatField",
//            ),
//            "extern_nilai4" => array(
//                "label" => "kekurangan setoran",
//                "format" => "formatField",
//            ),
            "terima_setoran" => array(
                "label" => "terima",
                "format" => "formatField",
                "link" => "kas/Transaksi/selectPaymentSrc/4464/",
                "linkType" => "modal",
                "modalHeader" => "oleh_nama",
                "linkSeg_4" => "extern_id",
                "tipe" => "button",
            ),

//            "extern_nama" => array(
//                "label" => "konsumen",
//                "link" => "Transaksi/selectPaymentSrc/749/",
//                "linkType" => "modal",
//                "modalHeader" => "extern_nama",
//                "linkSeg_4" => "extern_id",
//                // "modalHeader" => "id",
//            ),

        ),
        "shoppingCartPerTransaksiBtn" => array(
            "btn_satu" => array(
                "label" => "per TRANSAKSI",
                "show_default" => "prog_satu",
            ),
            "btn_dua" => array(
                "label" => "per KONSUMEN",
                // "show_default" => true,
            ),
        ),
        "settlementBtn" => false,
        "shopingCartReload" => true,
        "viewDescriptionNote" => true,
        "kelebihanBayarValidator" => array(
            "enabled" => true,
            "label" => "Terdapat lebih bayar pada pelunasan pembayaran ini. Kelebihan bayar harus dipilih Deposit / Pendapatan lain-lain.",
        ),
        "kurangBayarValidator" => array(
            "enabled" => true,
            "label" => "Terdapat lebih bayar pada pelunasan pembayaran ini. Kelebihan bayar harus dipilih Deposit / Pendapatan lain-lain.",
        ),
        // ----------------------------------------
        "shoppingCartDetail" => array(
            "enabled" => true,
            "targetGate" => "items5",
            "shoppingCartDetailFields" => array(
                1 => array(
                    "nama" => "produk",
                    "produk_kode" => "SKU",
                    "jml" => "qty",
                    "satuan" => "satuan",
                ),
            ),
            "shoppingCartDetailNumFields" => array(
                1 => array(
                    "harga" => "harga",
                    "disc_percent" => "diskon (%)",
                    "disc" => "diskon (IDR)",
                    "nett1" => "harga(net)",
                ),
            ),
            "shoppingCartDetailSumFields" => array(
                1 => array(
                    "harga" => "bruto",
                    "total_diskon" => "diskon",
//                "ongkir_ui" => "shipping service",
                    "nett1_bulat" => "netto",
                    "ppn_out_bulat" => "PPN",
                    "grand_pembulatan" => "grand total",
                ),

            ),
        ),
        "pembayaranBerurutan" => true,// true: pelunasan nota urut dari tertua, false: pelunasan nota bebas


    ),


);