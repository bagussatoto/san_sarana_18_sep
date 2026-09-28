<?php
//region urusan tanggal-menanggal
// date_default_timezone_set('asia/jakarta');
// $date = new DateTime(date("Y-m-d")); // Y-m-d
// $date->add(new DateInterval('P30D'));
//$date->format('Y-m-d') . "\n";
//endregion

//tambahin filter "461ro untuk selectornota taxes 681
$config["coTransaksiUi"] = array(
    //    payment source object pajak
    "682" => array(
        "icon" => "fa fa-money",
        "label" => "taxes A/P payment",
        "place" => "center",
        "paymentConfig" => true,
        "steps" => array(
            1 => array(
                "label" => "objek pajak A/P payment",
                "actionLabel" => "process payment",
                "source" => "",
                "target" => "682",
                "userGroup" => "c_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "confirmed by",
            ),
        ),
        "template" => "template/transaksi_payment.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.681",
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
            "suppliers_nama" => "grn*",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "cash_account__label" => "account",
            "sisa" => "amount",
            "nilai_bayar" => "paid",
            "new_sisa" => "remain amount",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "grn",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_kode" => "part number",
                "satuan" => "uom",
            ),
            2 => array(
                "produk_kode" => "Product No.",
                "produk_nama" => "Description",
                "produk_ord_jml" => "qty",
                //                "satuan" => "uom",
            ),

            3 => array(
                "produk_nama" => "product name",
                "produk_kode" => "part number",
                "berat_new" => "W(KG)",
                "volume_new" => "CBM",
                "max_jml" => "SO",
                "sent_jml" => "tekirim",
                "produk_ord_jml" => "qty",
                "sub_berat_new" => "sub berat",
                "sub_volume_new" => "sub volume",
                //                "satuan" => "uom",

            ),
            4 => array(
                "produk_ord_jml" => "Qty (Pcs)",
                "produk_nama" => "Description",
                //                "produk_kode"       => "part number",
                //                "satuan"            => "uom",
                "jml" => "Quantity Per Pkg (Ctns)",
                "berat_new" => "Net/Pkg (Kgs)",
                "sub_berat_new" => "Total (Kgs)",
                "volume_new" => "Net/Pkg (Cbm)",
                "sub_volume_new" => "Total (Cbm)",
            ),
            5 => array(
                "produk_nama" => "product name",
                "produk_kode" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
            ),
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "extern2_nama" => "vendor",
                "jml" => "qty",
            ),

        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nomer",
            "extern2_id" => "extern2_id",
            "extern2_nama" => "extern2_nama",
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
        "shoppingCartAvoidRemove" => true,
        "tagihanSrc" => "harus_bayar",
        "receiptElements" => array(
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
                    "nama" => "account number",
                    "alias" => "holder alias",
                    "saldo" => "balance",
                    "folders" => "acountMasterID",
                    "folders_nama" => "accountMaster",

                ),
                "editPoints" => array(1,),
                "noValidate" => false,
                "pairMethod" => array(
                    "recom" => "ReComCashMethode",
                    "calculate" => array(
                        "source" => "cash_account",
                        "prefix" => "cashMethode",
                        "target" => "",
                    ),
                ),
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
        "relativeElements" => array(
            //            "cashMethode" => array(
            //                "reguler" => array(
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
        "shoppingCartSumFields" => array(
            1 => array(
                "sisa" => "amount",
                //                "creditAmount" => "supplier credit amount",
                //                "creditValue" => "additional discount",
                //                "additional_value" => "additional price",
                //                "harus_bayar" => "amount remains to pay",
                "nilai_entry" => "amount of payment",
                "new_sisa" => "remain to pay (from list)",
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
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "mainValueInjectors" => array(
            "amount" => "sisa",
            "creditAmount" => "creditAmount",
            "harus_bayar" => "harus_bayar",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "customer ID",
            "pihakName" => "customer name",
        ),
        "shoppingCartRowNumValidators" => array(//            "nilai_entry" => "amount of payment",
        ),
        "shoppingCartUnionValidators" => array(
            array(
                "creditAmount" => "credit amount",
                "nilai_entry" => "amount value",
            ),
        ),
        "shoppingCartReferenceFields" => array(
            "nomer" => "sales number",
            "nomer_top" => "sales ref.",
            "refNum" => "return ref.",
            "fulldate" => "date",
            "tagihan" => "due amount",
            "refValue" => "returned",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "grn number",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    //                    "amount" => array(
                    //                        "label" => "total amount",
                    //                        "defaultValue" => "sisa",
                    //                        "maxValue" => "sisa",
                    //                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
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
        "componentsAss" => array(
            "model" => "MdlTransaksi",
            "modelSrc" => "MdlNotaItem",
        ),
        "previewCtr" => "Create",
//        "canceledLabel" => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
//                            <br>Silahkan melakukan {transaksi_nama} ulang di  {cabang_nama}",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di  {cabang_nama}",
        ),
    ),
    // config pembayaran hutang pph 21, 483
    "1483" => array(
        "icon" => "fa fa-money",
        "label" => "pph 21 A/P payment",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "pph 21 A/P payment",
                "actionLabel" => "process payment",
                "source" => "",
                "target" => "1483",
                "userGroup" => "c_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "paid by",
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.674",
            "label=.hutang pph 21",
            "transaksi_nilai_sisa>.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer", "dtime",
        ),

        "selectorProcessor" => "_processSelectNota/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "branch",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "branch",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "branch",
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
                "jml" => "qty",
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
                "sisa" => "sisa",
            ),
        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartAvoidRemove" => true,
        "receiptElements" => array(
            "branchDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "branch details",
                "mdlName" => "MdlCabang",
//                "mdlName" => "MdlCabang_and_supplier",
                "mdlFilter" => array(
                    "id=pihakID",
//                    "pair_pihak_id=pairPihakID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "name",
                ),
                "editPoints" => array(1),
                "noValidate" => true,
            ),
            "vendorDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "vendor details",
                "mdlName" => "MdlSupplier",
                "mdlFilter" => array(
                    "id=pihakID",
//                    "pair_pihak_id=pairPihakID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "name",
                    "npwp" => "npwp",
                    "alamat_1" => "alamat",
                    "tlp_1" => "phone",
                ),
                "editPoints" => array(1),
                "noValidate" => true,
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
                "noValidate" => false,
                "pairMethod" => array(
                    "recom" => "ReComCashMethode",
                    "calculate" => array(
                        "source" => "cash_account",
                        "prefix" => "cashMethode",
                        "target" => "",
                    ),
                ),
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
        "shoppingCartRowValidators" => array(
            "pihakID" => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartRowNumValidators" => array(
            "nilai_entry" => "amount of payment",
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "origAmount" => array(
                        "label" => "orig. amount",
                        "defaultValue" => "tagihan",
                        "maxValue" => "tagihan",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "amount" => array(
                        "label" => "total amount",
                        "defaultValue" => "sisa",
                        "maxValue" => "sisa",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
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
                                "
                    ,
                        //                        'disabled'     => "disabled",
                        "addPoints" => array(1,),
                    ),
                ),
            ),
        ),
        "shoppingCartReferenceFields" => array(
            "nomer" => "receipt number",
            "fulldate" => "date",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "sisa" => "due remain",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "branch",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "sisa" => "due remain",
        ),
        "resumeFieldNames" => array(
            "selectFields" => "cabang2_nama",
            "title" => "branch"
        ),
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di  {cabang_nama}",
        ),
        "showNotes" => true,
    ),
    "1483_NEW" => array(
        "icon" => "fa fa-money",
        "label" => "pph 21 A/P payment",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "pph 21 A/P payment",
                "actionLabel" => "process payment",
                "source" => "",
                "target" => "1483",
                "userGroup" => "c_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "paid by",
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.674",
            "label=.hutang pph 21",
            "transaksi_nilai_sisa>.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer", "dtime",
        ),

        "selectorProcessor" => "_processSelectNota/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "branch",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "branch",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "branch",
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
                "jml" => "qty",
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
                "sisa" => "sisa",
            ),
        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartAvoidRemove" => true,
        "receiptElements" => array(
            "branchDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "branch details",
                "mdlName" => "MdlCabang",
//                "mdlName" => "MdlCabang_and_supplier",
                "mdlFilter" => array(
                    "id=pihakID",
//                    "pair_pihak_id=pairPihakID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "name",
                ),
                "editPoints" => array(1),
                "noValidate" => true,
            ),
            "vendorDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "vendor details",
                "mdlName" => "MdlSupplier",
                "mdlFilter" => array(
                    "id=pihakID",
//                    "pair_pihak_id=pairPihakID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "name",
                    "npwp" => "npwp",
                    "alamat_1" => "alamat",
                    "tlp_1" => "phone",
                ),
                "editPoints" => array(1),
                "noValidate" => true,
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
                "noValidate" => false,
                "pairMethod" => array(
                    "recom" => "ReComCashMethode",
                    "calculate" => array(
                        "source" => "cash_account",
                        "prefix" => "cashMethode",
                        "target" => "",
                    ),
                ),
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
        "shoppingCartRowValidators" => array(
            "pihakID" => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartRowNumValidators" => array(
            "nilai_entry" => "amount of payment",
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "origAmount" => array(
                        "label" => "orig. amount",
                        "defaultValue" => "tagihan",
                        "maxValue" => "tagihan",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "amount" => array(
                        "label" => "total amount",
                        "defaultValue" => "sisa",
                        "maxValue" => "sisa",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
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
                                "
                    ,
                        //                        'disabled'     => "disabled",
                        "addPoints" => array(1,),
                    ),
                ),
            ),
        ),
        "shoppingCartReferenceFields" => array(
            "nomer" => "receipt number",
            "fulldate" => "date",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "sisa" => "due remain",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "branch",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "sisa" => "due remain",
        ),
        "resumeFieldNames" => array(
            "selectFields" => "cabang2_nama",
            "title" => "branch"
        ),
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di  {cabang_nama}",
        ),
        "showNotes" => true,
    ),
    //bank A/P payment
    "4447" => array(
        "icon" => "fa fa-money",
        "label" => "Bank payable",
        "paymentConfig" => true,
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "Bank payable",
                "actionLabel" => "process payment",
                "source" => "",
                "target" => "4447",
                "userGroup" => "c_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "PT. Everest Electronic",
                "stateFooter" => "paid by",
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.444",
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
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "cash_account__label" => "account",
            "sisa" => "amount",
            "additionalFactor" => "keuntungan/kerugian kurs",
            "nilai_bayar" => "paid",
            "new_sisa" => "remain amount",
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

        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",
            ),

        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nomer",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            "creditValue" => "diskon",
            "pair_pihak_id" => "extern2_id",
            "pair_pihak_name" => "extern2_nama",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "sisa" => "sisa",
            ),

        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "sisa" => "amount",
                //                "creditAmount" => "supplier credit amount",
                //                "creditValue" => "additional discount",
                //                "additional_value" => "additional kurs",
                //                "additional_expense" => "additional expense",
                //                "harus_bayar" => "amount remains to pay",
                //                "nilai_entry" => "loan installment",
                //                "new_sisa"    => "remain to pay (from list)",
            ),
        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartAvoidRemove" => true,
        "tagihanSrc" => "harus_bayar",
        "receiptElements" => array(
            "vendorDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "vendor details",
                "mdlName" => "MdlBankKoran",
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
                "noValidate" => false,
                "pairMethod" => array(
                    "recom" => "ReComCashMethode",
                    "calculate" => array(
                        "source" => "cash_account",
                        "prefix" => "cashMethode",
                        "target" => "",
                    ),
                ),
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
            //                "editPoints" => array(1, 2, 3),
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
        "shoppingCartRowNumValidators" => array(
            "nilai_entry" => "amount of payment",//sementara untuk lolosin bayar pakai keuntungan kurs
        ),
        "shoppingCartUnionValidators" => array(
            array(
                "creditAmount" => "credit amount",
                "nilai_entry" => "amount value",//sementara untuk lolosin bayar pakai keuntungan kurs
            ),
        ),
        "shopingCartAddValidator" => array(
            "additional" => array(
                "1" => "add_diskon",
                "-1" => "add_diskon",
            ),
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    //                    "origAmount" => array(
                    //                        "label" => "original amount",
                    //                        "defaultValue" => "tagihan",
                    //                        "maxValue" => "tagihan",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    //                    "addDiscount" => array(
                    //                        "label" => "additional discount",
                    //                        "defaultValue" => "diskon",
                    //                        "maxValue" => "diskon",
                    //                        'disabled' => "disabled",
                    //                        "role" => "minus",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    //                    "amount"       => array(
                    //                        "label"        => "total amount",
                    //                        "defaultValue" => "sisa",
                    //                        "maxValue"     => "sisa",
                    //                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
                    //                        'disabled'     => "disabled",
                    //                        "addPoints"    => array(1,),
                    //                    ),
                    //                    "credit_amount" => array(
                    //                        "label" => "credit amount (from return)",
                    //                        "defaultValue" => "creditAmount",
                    //                        //                        "keyupAction" => "",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                        "role" => "minus",
                    //                    ),
                    //                    "credit_note"   => array(
                    //                        "label"        => "credit note",
                    //                        "defaultValue" => "creditValue",
                    //                        //                        "keyupAction" => "",
                    //                        'disabled'     => "disabled",
                    //                        "addPoints"    => array(1,),
                    //                    ),
                    //                    "additional_expense" => array(
                    //                        "label" => "additional expense",
                    //                        "defaultValue" => ".0",
                    //                        "keyupAction" => "
                    //                                if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('additional_expense').value)) || parseInt(removeCommas(this.value))<0){
                    //
                    //                                }
                    //
                    //                            ",
                    //                        //                        'disabled'     => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    //                    "additional_value" => array(
                    //                        "label" => "selisih nilai",
                    //                        "defaultValue" => ".0",
                    //                        "keyupAction" => "
                    //                            if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('additional_value').value)) || parseInt(removeCommas(this.value))<0){
                    //
                    //                            }
                    //
                    //                            ",
                    ////                        'disabled'     => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    "harus_bayar" => array(
                        "label" => "amount remains to pay",
                        "defaultValue" => "harus_bayar",
                        "maxValue" => "harus_bayar",
                        "minValue" => "harus_bayar",
                        //                        "keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(removeCommas(document.getElementById('bayar').value)-parseFloat(gt))",
                        //                        "keyupAction" => "var gt=this.min,bayar=this.value,kembali=document.getElementById('kembali'); kembali.value=parseFloat(bayar)-parseFloat(gt);if(parseFloat(bayar)<parseFloat(gt)){kembali.style.color='red',kembali.style.fontWeight='700'}else{kembali.style.color='green',kembali.style.fontWeight='700'}",

                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        "hideRow" => "true",
                    ),

                    "nilai_entry" => array(
                        "label" => "loan installment",
                        "defaultValue" => ".0",
                        "keyupAction" => "
    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harus_bayar').value;}
     
                            ",
                        //                        'disabled'     => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "new_sisa" => array(
                        "label" => "remain to pay (from list)",
                        "defaultValue" => "new_sisa",
                        "keyupAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                ),
            ),
        ),
        "shoppingCartReferenceFields" => array(
            "nomer" => "receipt number",
            "nomer_top" => "receipt ref.",
            "refNum" => "return ref.",
            "fulldate" => "date",
            "tagihan" => "due amount",
            "refValue" => "returned",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
            "notes" => "description",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "vendor",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "resumeFieldNames" => array(
            "selectFields" => "suppliers_nama",
            "title" => "vendor",
        ),
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di  {cabang_nama}",
        ),
    ),
    //config pembayaran service auto geser biaya
    "462_OLD" => array(
        "icon" => "fa fa-money",
        "label" => "service A/P payment",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "account payable payment",
                "actionLabel" => "process payment",
                "source" => "",
                "target" => "462",
                "userGroup" => "c_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
            ),
        ),
        "paymentConfig" => true,
        "isPaymentRadioSelect" => true,
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.463",
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
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "details" => "detail",
            "oleh_nama" => "person",
            "cash_account__label" => "account",
            "sisa" => "amount",
            //            "additionalFactor" => "keuntungan/kerugian kurs",
            "pph23_nilai" => "pph 23",
            "uang_muka_dipakai" => "deposit",
            "pay_out" => "paid",
            "nilai_bayar" => "total paid",
            "pphGateLabel" => "status pph 23",
            "keterangan" => "keterangan",
            "print_label" => "tool",
            //            "new_sisa" => "remain amount",
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
                // "review_details" => "id",
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
        "shopingCartReload" => "true",
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "extern2_nama" => "metode pph23",
                "jml" => "qty",
            ),

        ),
        "shoppingCartFields2" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",
                "harga" => "price",
                "ppn" => "PPN",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nomer",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            "extern_nilai2" => "extern_nilai2",
            "extern_nilai3" => "extern_nilai3",
            "ppn" => "ppn",
            "extern2_id" => "extern2_id",
            "extern2_nama" => "extern2_nama",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "extern_nilai2" => "DPP pph23",
                "extern_nilai3" => "DPP ppn",
                "ppn" => "ppn",
                //                "sisa" => "sisa",
            ),

        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "ppn" => "ppn",
                "sisa" => "due amount",
                //                "non_pph" =>"non pph23",
                //                "valid_dpp" =>"dpp pph23",
                //                "pph23_nilai" => "pph 23",
                //
                //                "creditAmount" => "supplier credit amount",
                ////                "harus_bayar" => "amount remains to pay",
                //                "payment_out" => "amount of payment",
                //
                //                "new_sisa" => "remain to pay (from list)",
            ),

        ),
        "shoppingCartEditableFields" => array(
            //            "harga",
            //            "ppn",
            //"jml",
        ),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartAvoidRemove" => true,
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
                    "npwp" => "npwp",
                    "alamat_1" => "address",
                    "tlp_1" => "phone",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            "pph23Method" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "method of pph 23",
                "mdlName" => "MdlPph23Method",
                "key" => "id",
                //                "defaultValue" => "item",
                "labelSrc" => "name",
                //dipanggil di _shopingCart, dimatikan dulu
                //                "autoFilter" => array(
                //                    "key" => "pairPihakName",
                //                    "srcRef" => array(
                //                        "mdl" => "MdlSupplier",
                //                        "filter" => "pihakID",
                //                        "srcField" => "npwp",
                //                    ),
                //                    "pairKey" => array(
                ////                        "validate" => "npwp",
                //                        "methode" => array(
                //                            "dipotong" => array(
                //                                "true" => "npwp",
                //                                "false" => "non_npwp",
                //                            ),
                //                            "tidak dipotong" => array(
                //                                "true" => "sket",
                //                                "false" => "non_npwp",
                //                            ),
                //
                //                        ),
                //                    ),
                //                ),//pasang di _shopingCart

                "usedFields" => array(
                    "name" => "method",
                    "tarif" => "tarif (%)",
                ),
                "editPoints" => array(1,),

                "targetMethod" => array(
                    "npwp" => "ReComPph23Npwp_purchasing",
                    "non_npwp" => "ReComPph23NonNpwp_purchasing",
                    "sket" => "ReComPph23None_purchasing",
                ),
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

            //            "cashMethode" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "Metode rekening",
            //                "mdlName" => "MdlCashAccountStatic",
            //                "mdlFilter" => array(
            ////                    "extern_id=pihakID",
            ////                    "cabang_id=cabangID",
            ////                    "sisa>.0",
            //                ),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "usedFields" => array(
            //                    "nama" => "method",
            ////                    "extern_id" => "pihakID",
            //
            //                ),
            //                "editPoints" => array(1,),
            //                "noValidate" => true,
            //                "noPrefetch" => true,
            ////                "pairMethod" => array(
            ////                    "recom" => "ReComUangMuka",
            ////                    "calculate" => array(
            ////                        "source" => "uangMuka",
            ////                        "target" => "uang_muka_dipakai",
            ////                        "pair_source" => "nilai_sisa",//sunbe sumber yang dibandingkan
            ////                    ),
            //
            //
            //            ),
            "uangMuka" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "credit from uang muka",
                "mdlName" => "MdlSupplierCreditUangMuka",
                "mdlFilter" => array(
                    "extern_id=pihakID",
                    "cabang_id=cabangID",
                ),
                "key" => "id",
                "labelSrc" => "sisa",
                "usedFields" => array(
                    "extern_nama" => "vendor",
                    "extern_id" => "pihakID",
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
                        "source" => "uangMuka",
                        "target" => "uang_muka_dipakai",
                        "pair_source" => "sisa",//sunbe sumber yang dibandingkan /// nilai_sisa
                    ),

                    //                    "customer" => "ReComDiscCustomer",
                ),
            ),

            //            "branchTarget" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "target pembebanan ",
            //                "mdlName" => "MdlCabang",
            //                "key" => "id",
            //                "mdlFilter" => array(
            //                    "id<>.-1",
            //                ),
            //                "labelSrc" => "nama",
            //                "usedFields" => array(
            //                    "nama" => "nama",
            ////                    "tarif" => "tarif (%)",
            //                ),
            //                "editPoints" => array(1),
            //            ),
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
            "pph23Method" => array(
                "sket" => array(
                    "pph23Method__desc" => array(
                        "elementType" => "dataField",
                        "label" => "SKB/SKET",
                        "inputType" => "text",
                        "defaultValue" => "",
                        "editPoints" => array(1),
                    ),
                ),
            ),
            //            "cashMethode" => array(
            //                "reguler" => array(
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
            "branchTarget" => array(
                "1" => array(
                    "externMain" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "transfer expense to",
                        "mdlName" => "MdlBiayaMethodSales",
                        "mdlFilter" => array(),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "description" => "",
                        "usedFields" => array(
                            "nama" => "kategori biaya",
                        ),

                        "editPoints" => array(1,),
                        "noValidate" => true,

                    ),
                ),
                "25" => array(
                    "externMain" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "transfer expense to",
                        //                        "mdlName" => "MdlBiayaMethodProduksi",
                        "mdlName" => "MdlProdukRakitanPreBiaya",
                        "mdlFilter" => array(),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "description" => "",
                        "usedFields" => array(
                            "nama" => "kategori biaya",
                        ),
                        "editPoints" => array(1,),
                        "noValidate" => true,
                        "targetMethod2" => array(
                            "biaya_umum" => "ReComBiayaProduksi_payment",
                        ),
                    ),
                ),
                "21" => array(
                    "externMain" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "transfer expense to",
                        "mdlName" => "MdlBiayaMethodSales",
                        "mdlFilter" => array(),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "description" => "",
                        "usedFields" => array(
                            "nama" => "kategori biaya",
                        ),
                        "editPoints" => array(1,),
                        "noValidate" => true,
                        "targetMethod2" => array(
                            "biaya_umum" => "ReComBiayaUsaha_payment",
                            "biaya_usaha" => "ReComBiayaUmum_payment",
                        ),
                    ),
                ),
            ),
            "externMain" => array(
                "biaya_umum" => array(
                    "dtaDetail" => array(
                        "elementType" => "dataModel",
                        "inputType" => "combo",
                        "label" => "expense details",
                        "mdlName" => "MdlDtaBiayaUmum",
                        "mdlFilter" => array(),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "description" => "",
                        "usedFields" => array(
                            "nama" => "beban biaya",
                        ),
                        "editPoints" => array(1,),
                        "noValidate" => true,
                        "targetMethod2" => array(
                            "biaya_umum" => "ReComBiayaUmum_payment",
                        ),

                    ),
                ),
                "biaya_usaha" => array(
                    "dtaDetail" => array(
                        "elementType" => "dataModel",
                        "inputType" => "combo",
                        "label" => "expense details",
                        "mdlName" => "MdlDtaBiayaUsaha",
                        "mdlFilter" => array(),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "description" => "",
                        "usedFields" => array(
                            "nama" => "beban biaya",
                        ),
                        "editPoints" => array(1,),
                        "noValidate" => true,
                        "targetMethod2" => array(
                            "biaya_usaha" => "ReComBiayaUsaha_payment",
                        ),
                    ),

                ),
                "1" => array(
                    "dtaDetail" => array(
                        "elementType" => "dataModel",
                        "inputType" => "combo",
                        "label" => "expense details",
                        "mdlName" => "MdlDtaBiayaProduksi",
                        "mdlFilter" => array(//                            "pre_biaya_id=externMain",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "description" => "",
                        "usedFields" => array(
                            "nama" => "beban biaya",
                        ),
                        "editPoints" => array(1,),
                        "noValidate" => true,
                        "targetMethod2" => array(
                            1 => "ReComBiayaProduksi_payment"
                        ),
                    ),
                ),
                "2" => array(
                    "dtaDetail" => array(
                        "elementType" => "dataModel",
                        "inputType" => "combo",
                        "label" => "expense details",
                        "mdlName" => "MdlDtaBiayaProduksi",
                        "mdlFilter" => array(),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "description" => "",
                        "usedFields" => array(
                            "nama" => "beban biaya",
                        ),
                        "editPoints" => array(1,),
                        "noValidate" => true,
                        "targetMethod2" => array(
                            2 => "ReComBiayaProduksi_payment"
                        ),

                    ),
                ),
                "4" => array(
                    "dtaDetail" => array(
                        "elementType" => "dataModel",
                        "inputType" => "combo",
                        "label" => "expense details",
                        "mdlName" => "MdlDtaBiayaProduksi",
                        "mdlFilter" => array(),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "description" => "",
                        "usedFields" => array(
                            "nama" => "beban biaya",
                        ),
                        "editPoints" => array(1,),
                        "noValidate" => true,
                        "targetMethod2" => array(
                            4 => "ReComBiayaProduksi_payment"
                        ),
                    ),

                ),
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
        "pairRegistries" => array(
            "main", "items"
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartRowNumValidators" => array(//            "nilai_entry" => "amount of payment",
        ),
        "shopingCartPaymentComparisonValidator" => array(
            array(
                "source" => "nilai_dipakai_hutang_biaya", // hutang dagang
                "target" => "nilai_bayar", // payment source
                "label" => "Pastikan penggunaan Kas, Uang Muka sudah sesuai untuk pelunasan Invoice ini.", //
            ),
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "creditAmount" => array(
                        "label" => "credit note",
                        "defaultValue" => "creditAmount",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    //                    "nilai_entry" => array(
                    //                        "label" => "amount of payment",
                    //                        "defaultValue" => ".0",
                    //                        "keyupAction" => "if(parseFloat(removeCommas(this.value))>parseFloat(removeCommas(document.getElementById('sisa').value)) || parseFloat(removeCommas(this.value))<0){this.value=document.getElementById('sisa').value;}",
                    ////                        "keyupAction" => "if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('payment_out').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('payment_out').value;}",
                    //                        //                        'disabled'     => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),

                    //                    "valid_dpp" => array(
                    //                        "label" => "DPP PPh 23",
                    //                        "defaultValue" => "valid_dpp",
                    //                        "maxValue" => "valid_dpp",
                    //                        "minValue" => "valid_dpp",
                    //                        "keyPressAction" => "",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    //                    "non_pph" => array(
                    //                        "label" => "non pph",
                    //                        "defaultValue" => ".0",
                    //                        "keyupAction" => "if(parseFloat(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('extern_nilai2_1').innerHTML) || parseFloat(removeCommas(this.value))<0){this.value=document.getElementById('extern_nilai2_1').innerHTML;}",
                    ////                        "keyupAction" => "if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('payment_out').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('payment_out').value;}",
                    //                        //                        'disabled'     => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    "pph23_nilai" => array(
                        "label" => "(PPh 23)",
                        "defaultValue" => "pph23_nilai",
                        "maxValue" => "pph23_nilai",
                        "minValue" => "pph23_nilai",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "uang_muka_dipakai" => array(
                        "label" => "(deposit)",
                        "defaultValue" => "0",
                        "maxValue" => "uang_muka_dipakai",
                        "minValue" => "uang_muka_dipakai",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "payment_out" => array(
                        "label" => "amount to be paid",
                        "defaultValue" => "payment_out",
                        "maxValue" => "payment_out",
                        "minValue" => "payment_out",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "nilai_entry" => array(
                        "label" => "amount of payment",
                        "defaultValue" => "nilai_entry",

                        "maxValue" => "nilai_entry",
                        "minValue" => "nilai_entry",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        'hideRow' => "true",

                        "addPoints" => array(1,),
                    ),
                    //                    "final_sisa" => array(
                    //                        "label" => "balance of invoice",
                    //                        "defaultValue" => "final_sisa",
                    //                        "maxValue" => "final_sisa",
                    //                        "minValue" => "final_sisa",
                    //                        "keyPressAction" => "",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    //                    "sisa_uang_muka" => array(
                    //                        "label" => "balance of deposit",
                    //                        "defaultValue" => "sisa_uang_muka",
                    //                        "maxValue" => "sisa_uang_muka",
                    //                        "minValue" => "sisa_uang_muka",
                    //                        "keyPressAction" => "",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),

                ),
            ),
        ),
        "shoppingCartReferenceFields" => array(
            "nomer" => "receipt number",
            "nomer_top" => "receipt ref.",
            "refNum" => "return ref.",
            "fulldate" => "date",
            "tagihan" => "due amount",
            "refValue" => "returned",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
            "notes" => "description",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "vendor",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "resumeFieldNames" => array(
            "selectFields" => "suppliers_nama",
            "title" => "vendor",
        ),
        "ppnDisabled" => array(
            "enabled" => true,
            "notes" => "PPN masukan belum diapprove oleh Finance.",
        ),
        "installment" => array(
            "enable" => false,
            "sourceValue" => "harus_bayar",
            "formID" => "nilai_entry"
        ),
        "componentsAss" => array(
            "model" => "MdlTransaksi",
            "modelSrc" => "MdlNotaItem",
        ),
        "shoppingCartPairedItem" => array(
            "targetGateName" => "items2_sum",
        ),
        "pairRecomDataElement" => array(
            "pph23Method" => array(
                "mdlname" => "MdlPph23MethodPotongan",
                "gateId" => "pphGateId",
                "target" => array(
                    "main" => "biayaJasa",
                ),
            ),

        ),
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di  {cabang_nama}",
        ),
    ),
    "462" => array(
        "icon" => "fa fa-money",
        "label" => "service A/P payment",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "account payable payment",
                "actionLabel" => "process payment",
                "source" => "",
                "target" => "462",
                "userGroup" => "c_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
            ),
        ),
        "paymentConfig" => true,
        "isPaymentRadioSelect" => true,
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.463",
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
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "details" => "detail",
            "oleh_nama" => "person",
            "cash_account__label" => "account",
            "sisa" => "amount",
            //            "additionalFactor" => "keuntungan/kerugian kurs",
            "pph23_nilai" => "pph 23",
            "uang_muka_dipakai" => "deposit",
            "pay_out" => "paid",
            "nilai_bayar" => "total paid",
            "pphGateLabel" => "status pph 23",
            "keterangan" => "keterangan",
            "print_label" => "tool",
            //            "new_sisa" => "remain amount",
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
                // "review_details" => "id",
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
        "shopingCartReload" => "true",
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "extern2_nama" => "metode pph23",
                "jml" => "qty",
            ),
        ),
        "shoppingCartFields2" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",
                "harga" => "price",
                "ppn" => "PPN",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nomer",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            "extern_nilai2" => "extern_nilai2",
            "extern_nilai3" => "extern_nilai3",
            "extern_nilai4" => "extern_nilai4",
            "extern_nilai5" => "extern_nilai5",
            "ppn" => "ppn",
            "extern2_id" => "extern2_id",
            "extern2_nama" => "extern2_nama",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "extern_nilai5" => "DPP pph21",
                "extern_nilai2" => "DPP pph23",
                "extern_nilai3" => "DPP ppn",
                "ppn" => "ppn",
                //                "sisa" => "sisa",
            ),
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "ppn" => "ppn",
                "sisa" => "due amount",
                //                "non_pph" =>"non pph23",
                //                "valid_dpp" =>"dpp pph23",
                //                "pph23_nilai" => "pph 23",
                //
                //                "creditAmount" => "supplier credit amount",
                ////                "harus_bayar" => "amount remains to pay",
                //                "payment_out" => "amount of payment",
                //
                //                "new_sisa" => "remain to pay (from list)",
            ),

        ),
        "shoppingCartEditableFields" => array(
            //            "harga",
            //            "ppn",
            //"jml",
        ),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartAvoidRemove" => true,

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
                    "npwp" => "npwp",
                    "alamat_1" => "address",
                    "tlp_1" => "phone",
//                    "kategori" => "kategoriID",
//                    "kategori_item" => "kategori Name",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            "pajakOption" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "pph 21 / pph 23 option",
                "mdlName" => "MdlPajakPPhOption",
//                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",
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

            "uangMuka" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "credit from uang muka",
                "mdlName" => "MdlSupplierCreditUangMuka",
                "mdlFilter" => array(
                    "extern_id=pihakID",
                    "cabang_id=cabangID",
                    "extern_label2=.vendor",
                    "sisa>.0"
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
                        "source" => "uangMuka",
                        "target" => "uang_muka_dipakai",
                        "pair_source" => "pay_out_no_um",//sunbe sumber yang dibandingkan /// nilai_sisa
//                        "pair_source" => "sisa",//sunbe sumber yang dibandingkan /// nilai_sisa
                    ),

                    //                    "customer" => "ReComDiscCustomer",
                ),
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

            "pajakOption" => array(
                "pph21" => array(
                    "pph21Method" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "method of pph 21",
                        "mdlName" => "MdlPph21Method",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "method",
                            "tarif" => "tarif (%)",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            "npwp" => "ReComPph21Npwp_purchasing",
                            "non_npwp" => "ReComPph21NonNpwp_purchasing",
                            "sket" => "ReComPph21None_purchasing",
                        ),
                    ),
                ),
                "pph23" => array(
                    "pph23Method" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "method of pph 23",
                        "mdlName" => "MdlPph23Method",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "method",
                            "tarif" => "tarif (%)",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            "npwp" => "ReComPph23Npwp_purchasing",
                            "non_npwp" => "ReComPph23NonNpwp_purchasing",
                            "sket" => "ReComPph23None_purchasing",
                        ),
                    ),
                ),
            ),

            "pph23Method" => array(
                "sket" => array(
                    "pph23Method__desc" => array(
                        "elementType" => "dataField",
                        "label" => "SKB/SKET",
                        "inputType" => "text",
                        "defaultValue" => "",
                        "editPoints" => array(1),
                    ),
                ),
            ),
            "pph21Method" => array(
                "sket" => array(
                    "pph21Method__desc" => array(
                        "elementType" => "dataField",
                        "label" => "SKB/SKET",
                        "inputType" => "text",
                        "defaultValue" => "",
                        "editPoints" => array(1),
                    ),
                ),
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
        "pairRegistries" => array(
            "main", "items"
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartRowNumValidators" => array(//            "nilai_entry" => "amount of payment",
        ),
        "shopingCartPaymentComparisonValidator" => array(
            array(
                "source" => "nilai_dipakai_hutang_biaya", // hutang dagang
                "target" => "nilai_bayar", // payment source
                "label" => "Pastikan penggunaan Kas, Uang Muka sudah sesuai untuk pelunasan Invoice ini.", //
            ),
        ),

        "shopingCartPaymentValueValidator" => array(
            "srcDana" => array(
                "uang_muka_dipakai",
                "kas_value",
                "rekening_koran_value",
            ),
            "srcTagihan" => array(
                "sisa",
            ),
        ),
        "shopingCartPaymentValueValidatorLabel" => "Cicilan AP Payment Jasa dimasukkan sebagai uang muka.<br>Uang muka hanya bisa dipakai saat pelunasan",


        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "creditAmount" => array(
                        "label" => "credit note",
                        "defaultValue" => "creditAmount",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),

                    //                    "nilai_entry" => array(
                    //                        "label" => "amount of payment",
                    //                        "defaultValue" => ".0",
                    //                        "keyupAction" => "if(parseFloat(removeCommas(this.value))>parseFloat(removeCommas(document.getElementById('sisa').value)) || parseFloat(removeCommas(this.value))<0){this.value=document.getElementById('sisa').value;}",
                    ////                        "keyupAction" => "if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('payment_out').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('payment_out').value;}",
                    //                        //                        'disabled'     => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),

                    //                    "valid_dpp" => array(
                    //                        "label" => "DPP PPh 23",
                    //                        "defaultValue" => "valid_dpp",
                    //                        "maxValue" => "valid_dpp",
                    //                        "minValue" => "valid_dpp",
                    //                        "keyPressAction" => "",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    //                    "non_pph" => array(
                    //                        "label" => "non pph",
                    //                        "defaultValue" => ".0",
                    //                        "keyupAction" => "if(parseFloat(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('extern_nilai2_1').innerHTML) || parseFloat(removeCommas(this.value))<0){this.value=document.getElementById('extern_nilai2_1').innerHTML;}",
                    ////                        "keyupAction" => "if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('payment_out').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('payment_out').value;}",
                    //                        //                        'disabled'     => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),

                    "pph21_nilai" => array(
                        "label" => "(PPh 21)",
                        "defaultValue" => "pph21_nilai",
                        "maxValue" => "pph21_nilai",
                        "minValue" => "pph21_nilai",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "pph23_nilai" => array(
                        "label" => "(PPh 23)",
                        "defaultValue" => "pph23_nilai",
                        "maxValue" => "pph23_nilai",
                        "minValue" => "pph23_nilai",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "uang_muka_dipakai" => array(
                        "label" => "(deposit)",
                        "defaultValue" => "0",
                        "maxValue" => "uang_muka_dipakai",
                        "minValue" => "uang_muka_dipakai",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "payment_out" => array(
                        "label" => "amount to be paid",
                        "defaultValue" => "payment_out",
                        "maxValue" => "payment_out",
                        "minValue" => "payment_out",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "nilai_entry" => array(
                        "label" => "amount of payment",
                        "defaultValue" => "nilai_entry",

                        "maxValue" => "nilai_entry",
                        "minValue" => "nilai_entry",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        'hideRow' => "true",

                        "addPoints" => array(1,),
                    ),
                    //                    "final_sisa" => array(
                    //                        "label" => "balance of invoice",
                    //                        "defaultValue" => "final_sisa",
                    //                        "maxValue" => "final_sisa",
                    //                        "minValue" => "final_sisa",
                    //                        "keyPressAction" => "",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    //                    "sisa_uang_muka" => array(
                    //                        "label" => "balance of deposit",
                    //                        "defaultValue" => "sisa_uang_muka",
                    //                        "maxValue" => "sisa_uang_muka",
                    //                        "minValue" => "sisa_uang_muka",
                    //                        "keyPressAction" => "",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),

                ),
            ),
        ),
        "shoppingCartReferenceFields" => array(
            "nomer" => "receipt number",
            "nomer_top" => "receipt ref.",
            "refNum" => "return ref.",
            "fulldate" => "date",
            "tagihan" => "due amount",
            "refValue" => "returned",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
            "notes" => "description",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "vendor",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "resumeFieldNames" => array(
            "selectFields" => "suppliers_nama",
            "title" => "vendor",
        ),
        "ppnDisabled" => array(
            "enabled" => true,
            "notes" => "PPN masukan belum diapprove oleh Finance.",
        ),
        "installment" => array(
            "enable" => false,
            "sourceValue" => "harus_bayar",
            "formID" => "nilai_entry"
        ),
        "componentsAss" => array(
            "model" => "MdlTransaksi",
            "modelSrc" => "MdlNotaItem",
        ),
        "shoppingCartPairedItem" => array(
            "targetGateName" => "items2_sum",
        ),
        "pairRecomDataElement" => array(
            "pph23Method" => array(
                "mdlname" => "MdlPph23MethodPotongan",
                "gateId" => "pphGateId",
                "target" => array(
                    "main" => "biayaJasa",
                ),
            ),
            "pph21Method" => array(
                "mdlname" => "MdlPph21MethodPotongan",
                "gateId" => "pphGateId",
                "target" => array(
                    "main" => "biayaJasa",
                ),
            ),
        ),
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di  {cabang_nama}",
        ),
    ),
    // config pembayaran hutang ke supplier (supplies)
    "487" => array(
        "icon" => "fa fa-money",
        "label" => "supplies A/P payment",
        "place" => "center",
        "paymentConfig" => true,
        "steps" => array(
            1 => array(
                "label" => "account payable payment",
                "actionLabel" => "process payment",
                "source" => "",
                "target" => "487",
                "userGroup" => "c_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "PT. Everest Electronic",
                "stateFooter" => "paid by",
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.461",
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
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            //            "review_details" => "review",
            "details" => "detail",
            "oleh_nama" => "person",
            "cash_account__label" => "account",
            "sisa" => "amount",
            "additionalFactor" => "keuntungan/kerugian kurs",
            "nilai_bayar" => "paid",
            "new_sisa" => "remain amount",
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
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            "creditValue" => "diskon",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "sisa" => "sisa",
            ),
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                //                "sisa" => "due amount",
                //                "creditAmount" => "credit note",
            ),
        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartAvoidRemove" => true,
        "tagihanSrc" => "harus_bayar",
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
            "additional" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "exchange rate",
                "mdlName" => "MdlStaticPayment",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "additional jenis",
                    //                            "currency" => "currency",
                ),
                "editPoints" => array(1,),
            ),

            "creditAmount" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "credit note",
                "mdlName" => "MdlPaymentAntiSource",
                "mdlFilter" => array(
                    "extern_id=pihakID",
                    "cabang_id=cabangID",
                    //                    "target_jenis=jenisTr",
                    "label=.piutang pembelian",
                    "sisa>.0",
                ),
                "key" => "sisa",
                "labelSrc" => "sisa",
                "usedFields" => array(
                    "extern_nama" => "vendor name",
                    //                    "transaksi_id" => "return ID",
                    //                    "nomer" => "return number",
                    "sisa" => "avail credit",
                    //                    "jenis" => "jenis",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
                "noPrefetch" => true,
                "pairMethod" => array(
                    "recom" => "ReComCreditNote",
                    "calculate" => array(
                        "source" => "creditNote",
                        "target" => "credit_note_dipakai",
                        "pair_source" => "nilai_round",//sunbe sumber yang dibandingkan
                        //                        "pair_source" => "nilai_sisa",//sunbe sumber yang dibandingkan
                    ),

                    //                    "customer" => "ReComDiscCustomer",
                ),
            ),
            "uangMuka" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "credit from uang muka",
                "mdlName" => "MdlSupplierCreditUangMuka",
                "mdlFilter" => array(
                    "extern_id=pihakID",
                    "cabang_id=cabangID",
                    "extern_label2=.vendor",
                    "sisa>.0",
                ),
                "key" => "id",//digeser dari "key"=>"sisa"
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
                        "source" => "sisa",
                        "target" => "uang_muka_dipakai",
                        "pair_source" => "nilai_sisa",//sunbe sumber yang dibandingkan
                    ),

                    //                    "customer" => "ReComDiscCustomer",
                ),
            ),
            //            "cashMethode" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "Metode rekening",
            //                "mdlName" => "MdlCashAccountStatic",
            //                "mdlFilter" => array(
            ////                    "extern_id=pihakID",
            ////                    "cabang_id=cabangID",
            ////                    "sisa>.0",
            //                ),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "usedFields" => array(
            //                    "nama" => "method",
            ////                    "extern_id" => "pihakID",
            //
            //                ),
            //                "editPoints" => array(1,),
            //                "noValidate" => true,
            //                "noPrefetch" => true,
            ////                "pairMethod" => array(
            ////                    "recom" => "ReComUangMuka",
            ////                    "calculate" => array(
            ////                        "source" => "uangMuka",
            ////                        "target" => "uang_muka_dipakai",
            ////                        "pair_source" => "nilai_sisa",//sunbe sumber yang dibandingkan
            ////                    ),
            //
            //
            //            ),

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
            //            "cashMethode" => array(
            //                "reguler" => array(
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
                "stock" => array(
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
        "shoppingCartRowNumValidators" => array(//            "nilai_entry" => "amount of payment",//off dulu baypass kerugian kurs
        ),
        "shoppingCartUnionValidators" => array(
            array(
                "credit_note_dipakai" => "credit amount",
                "nilai_entry" => "jumlah pembayaran",
                "uang_muka_dipakai" => "uang muka",
            ),
        ),
        "shopingCartUnionComparison" => array(
            array(
                "nilai_entry" => "payment belum diisi**",
                "cash_account__saldo" => "cash account belum dipilih",
            ),

        ),

        "shopingCartAddValidator" => array(
            "additional" => array(
                "1" => "add_diskon",
                "-1" => "add_diskon",
            ),
        ),
        "shopingCartPaymentComparisonValidator" => array(
            array(
                "source" => "nilai_dipakai_hutang_dagang", // hutang dagang
                "target" => "nilai_bayar", // payment source
                "label" => "Pastikan penggunaan Kas, Uang Muka, Credit Note dan Keuntungan/Kerugian Kurs sudah sesuai untuk pelunasan Invoice ini.", //
            ),
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "nilai_round" => array(
                        "label" => "value of invoice",
                        "defaultValue" => "nilai_round",
                        "maxValue" => "nilai_round",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "sisa" => array(
                        "label" => "due amount",
                        "defaultValue" => "sisa",
                        "maxValue" => "sisa",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        "hideRow" => "true",
                    ),
                    //                    "amount" => array(
                    //                        "label" => "total amount",
                    //                        "defaultValue" => "sisa",
                    //                        "maxValue" => "sisa",
                    ////                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                        "hideRow" => "true",
                    //                    ),

                    //"credit_amount" => array(
                    //    "label" => "credit amount",
                    //    "defaultValue" => "creditAmount",
                    //                        "keyupAction" => "",
                    //    'disabled' => "disabled",
                    //    "addPoints" => array(1,),
                    //),
                    //"credit_note" => array(
                    //    "label" => "credit note",
                    //    "defaultValue" => "creditValue",
                    //                        "keyupAction" => "",
                    //    'disabled' => "disabled",
                    //    "addPoints" => array(1,),
                    //),

                    //                    "additional_expense" => array(
                    //                        "label" => "additional expense",
                    //                        "defaultValue" => ".0",
                    //                        "keyupAction" => "
                    //                                if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('additional_expense').value)) || parseInt(removeCommas(this.value))<0){
                    //
                    //                                }
                    //
                    //                            ",
                    //                        //                        'disabled'     => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    "additional_value" => array(
                        "label" => "exchange rate difference",
                        "defaultValue" => ".0",
                        "keyupAction" => "
                            if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('additional_value').value)) || parseInt(removeCommas(this.value))<0){

                            }
                    
                            ",
                        //                        'disabled'     => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "credit_note_dipakai" => array(
                        "label" => "credit note",
                        "defaultValue" => "credit_note_dipakai",
                        "maxValue" => "credit_note_dipakai",
                        "minValue" => "credit_note_dipakai",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "uang_muka_dipakai" => array(
                        "label" => "paid by deposit",
                        "defaultValue" => "0",
                        "maxValue" => "uang_muka_dipakai",
                        "minValue" => "uang_muka_dipakai",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "nilai_entry" => array(
                        "label" => "payment",
                        "defaultValue" => ".0",
                        "keyupAction" => "
    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=addCommas(document.getElementById('harus_bayar').value);}
     
                            ",
                        //                        'disabled'     => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "harus_bayar" => array(
                        "label" => "netto",
                        "defaultValue" => "harus_bayar",
                        "maxValue" => "harus_bayar",
                        "minValue" => "harus_bayar",
                        //                        "keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(removeCommas(document.getElementById('bayar').value)-parseFloat(gt))",
                        //                        "keyupAction" => "var gt=this.min,bayar=this.value,kembali=document.getElementById('kembali'); kembali.value=parseFloat(bayar)-parseFloat(gt);if(parseFloat(bayar)<parseFloat(gt)){kembali.style.color='red',kembali.style.fontWeight='700'}else{kembali.style.color='green',kembali.style.fontWeight='700'}",
                        "hideRow" => false,
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "nilai_entry" => array(
                        "label" => "payment",
                        "defaultValue" => ".0",
                        "keyupAction" => "
    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=(document.getElementById('harus_bayar').value);}
     
                            ",
                        //                        'disabled'     => "disabled",
                        "addPoints" => array(1,),
                    ),

                    "new_sisa" => array(
                        "label" => "balance of invoice",
                        "defaultValue" => "new_sisa",
                        "maxValue" => "new_sisa",
                        "minValue" => "new_sisa",
                        //                        "keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(removeCommas(document.getElementById('bayar').value)-parseFloat(gt))",
                        //                        "keyupAction" => "var gt=this.min,bayar=this.value,kembali=document.getElementById('kembali'); kembali.value=parseFloat(bayar)-parseFloat(gt);if(parseFloat(bayar)<parseFloat(gt)){kembali.style.color='red',kembali.style.fontWeight='700'}else{kembali.style.color='green',kembali.style.fontWeight='700'}",
                        //                        "hideRow" => "true",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),


                ),
            ),
        ),
        "shoppingCartReferenceFields" => array(
            "nomer" => "receipt number",
            "nomer_top" => "receipt ref.",
            "refNum" => "return ref.",
            "fulldate" => "date",
            "tagihan" => "due amount",
            "refValue" => "returned",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
            "notes" => "description",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "vendor",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "resumeFieldNames" => array(
            "selectFields" => "suppliers_nama",
            "title" => "vendor",
        ),
        "ppnDisabled" => array(
            "enabled" => true,
            "notes" => "PPN masukan belum diapprove oleh Finance.",
        ),
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di  {cabang_nama}",
        ),
    ),
    // config pembayaran hutang ke supplier (finish goods)
    "489" => array(
        "icon" => "fa fa-money",
        "label" => "FG A/P payment",
        "paymentConfig" => true,
        "place" => "center",
        "steps" => array(
            //            1 => array(
            //                "label"       => "pembayaran hutang",
            //                "actionLabel" => "pembayaran hutang",
            //                "source"      => "",
            //                "target"      => "489r",
            //                "userGroup"   => "sys",
            //                "stateLabel"  => "ready to be paid",
            //                "stateColor"  => "#dd3300",
            //            ),
            1 => array(
                "label" => "account payable payment",
                "actionLabel" => "process payment",
                "source" => "",
                "target" => "489",
                "userGroup" => "c_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "PT. Everest Electronic",
                "stateFooter" => "paid by",
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.467",
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
            "sisa" => "amount",
            "additionalFactor" => "keuntungan/kerugian kurs",
            "nilai_bayar" => "paid",
            "new_sisa" => "remain amount",
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
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            "creditValue" => "diskon",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "sisa" => "sisa",
            ),

        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "nilai_round" => "due amount",
            ),

        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartAvoidRemove" => true,
        "tagihanSrc" => "harus_bayar",
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
//            "additional" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "exchange rate ",
//                "mdlName" => "MdlStaticPayment",
//                "mdlFilter" => array(),
//                "key" => "id",
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "name" => "additional jenis",
//                    //                            "currency" => "currency",
//                ),
//                "editPoints" => array(1,),
//            ),
            "creditAmount" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "credit note (return pembelian)",
                "mdlName" => "MdlPaymentAntiSource",
                "mdlFilter" => array(
                    "extern_id=pihakID",
                    "cabang_id=cabangID",
//                    "target_jenis=jenisTr",
                    "label=.piutang pembelian",
                    "sisa>.0",
                ),
                "key" => "sisa",
                "labelSrc" => "sisa",
                "usedFields" => array(
                    "extern_nama" => "nama supplier",
                    "sisa" => "avail credit",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
//                "noPrefetch" => true,
//                "pairMethod" => array(
//                    "recom" => "ReComCreditNote",
//                    "calculate" => array(
//                        "source" => "creditNote",
//                        "target" => "credit_note_dipakai",
//                        "pair_source" => "nilai_sisa",//sunbe sumber yang dibandingkan
//                    ),
//                ),
            ),
            "creditAmountKlaim" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "credit note (klaim kepada supplier)",
                "pairedModel" => array(
                    "mdlName" => "ComRekeningPembantuCreditNote",
                    "mdlMethod" => "fetchBalances",
                    "mdlFilter" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "pihakID",
                    ),
                    "key" => "extern_id",
                    "rekening" => "1010010030",
                    "fieldID" => "debet",
                    "fieldLabel" => "saldo",
                ),
                "mdlName" => "MdlSupplier",
                "mdlFilter" => array(
                    "id=pihakID",
//                    "cabang_id=cabangID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
//                    "extern_nama" => "nama supplier",
                    "saldo" => "saldo",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
//                "noPrefetch" => true,
//                "pairMethod" => array(
//                    "recom" => "ReComCreditNote",
//                    "calculate" => array(
//                        "source" => "creditNote",
//                        "target" => "credit_note_dipakai",
//                        "pair_source" => "nilai_sisa",//sunbe sumber yang dibandingkan
//                    ),
//                ),
            ),
            "uangMuka" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Deposit (Uang muka)",
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
                        "source" => "uangMuka",
                        "target" => "uang_muka_dipakai",
                        "pair_source" => "nilai_sisa",//sunbe sumber yang dibandingkan
                    ),

                    //                    "customer" => "ReComDiscCustomer",
                ),
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
                        "kas", "plafon hutang bank",
                    ),
                    "fieldID" => "nilai",
                    "fieldLabel" => "saldo",
                ),
                "mdlName" => "MdlBankAccount_cash_and_in_and_koran",
                "mdlFilter" => array(//                    "bank.cabang_id=placeID",
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
                "labelValidate" => "Silahkan memilih sumber pembayaran sebelum melanjutkan transaksi.",
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
        "shoppingCartUnionValidators" => array(
            array(
                "credit_note_dipakai" => "credit note",
                "uang_muka_dipakai" => "deposit",
                "nilai_entry" => "payment",//sementara untuk lolosin bayar pakai keuntungan kurs
            ),
        ),
//        "shopingCartAddValidator" => array(
//            "additional" => array(
//                "1" => "add_diskon",
//                "-1" => "add_diskon",
//            ),
//        ),
        "shopingCartUnionComparison" => array(
            array(
                "nilai_entry" => "payment belum diisi",
                "cash_account__saldo" => "cash account belum dipilih",
            ),

        ),
        "shopingCartPaymentValidator" => array(
            //            array(
            "nilai_entry" => array(
                "label" => "payment belum diisi",
            ),
            "cash_account" => array(
                "label" => "cash account belum dipilih",
            ),
            "uangMuka" => array(
                "label" => "cash account belum dipilih",
            ),
            "creditAmount" => array(
                "label" => "credit note (from return) belum dipilih",
            ),
            //            ),

        ),
        "shopingCartPaymentComparisonValidator" => array(
            array(
                "source" => "nilai_dipakai_hutang_dagang", // hutang dagang
                "target" => "nilai_bayar", // payment source
                "label" => "Pastikan penggunaan Kas, Uang Muka, Credit Note dan Keuntungan/Kerugian Kurs sudah sesuai untuk pelunasan Invoice ini.", //
            ),
        ),


        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    //                    "origAmount" => array(
                    //                        "label" => "original amount",
                    //                        "defaultValue" => "tagihan",
                    //                        "maxValue" => "tagihan",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    //                    "addDiscount" => array(
                    //                        "label" => "additional discount",
                    //                        "defaultValue" => "diskon",
                    //                        "maxValue" => "diskon",
                    //                        'disabled' => "disabled",
                    //                        "role" => "minus",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    //                    "amount"       => array(
                    //                        "label"        => "total amount",
                    //                        "defaultValue" => "sisa",
                    //                        "maxValue"     => "sisa",
                    //                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
                    //                        'disabled'     => "disabled",
                    //                        "addPoints"    => array(1,),
                    //                    ),
                    //                    "credit_amount" => array(
                    //                        "label" => "credit amount (from return)",
                    //                        "defaultValue" => "creditAmount",
                    //                        //                        "keyupAction" => "",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                        "role" => "minus",
                    //                    ),
                    //                    "credit_note"   => array(
                    //                        "label"        => "credit note",
                    //                        "defaultValue" => "creditValue",
                    //                        //                        "keyupAction" => "",
                    //                        'disabled'     => "disabled",
                    //                        "addPoints"    => array(1,),
                    //                    ),
                    "sisa" => array(
                        "label" => "sisa",
                        "defaultValue" => "sisa",
                        "keyupAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        'hideRow' => "true",
                    ),
                    "additional_expense" => array(
                        "label" => "additional expense",
                        "defaultValue" => ".0",
                        "keyupAction" => "
                                if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('additional_expense').value)) || parseInt(removeCommas(this.value))<0){
                                
                                }
                            
                            ",
                        //                        'disabled'     => "disabled",
                        "addPoints" => array(1,),
                    ),
//                    "additional_value" => array(
//                        "label" => "exchange rate difference",
//                        "defaultValue" => ".0",
//                        "keyupAction" => "
//                            if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('additional_value').value)) || parseInt(removeCommas(this.value))<0){
//
//                            }
//
//                            ",
//                        //                        'disabled'     => "disabled",
//                        "addPoints" => array(1,),
//                    ),
                    "uang_muka_dipakai" => array(
                        "label" => "Deposit (uang Muka)",
                        "defaultValue" => "0",
                        "maxValue" => "uang_muka_dipakai",
                        "minValue" => "uang_muka_dipakai",
                        "saldo" => "uangMuka__saldo",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "credit_note_dipakai" => array(
                        "label" => "credit note (return pembelian)",
                        "defaultValue" => "0",
                        "maxValue" => "credit_note_dipakai",
                        "minValue" => "credit_note_dipakai",
                        "saldo" => "creditAmount__sisa",
                        "keyPressAction" => "",
                        "addPoints" => array(1,),
                    ),
                    //-----------------

//                    "credit_note_diskon" => array(
//                        "label" => "klaim kepada supplier",
//                        "defaultValue" => "nilai_diskon_dipakai",
//                        "addPoints" => array(1,),
//                        "detail" => true,
//                        "detailFilter" => array(
//                            "supplier_id=pihakID",
//                            "is_pembayaran=.1",
//                        ),
//                        "detailKolom" => array(
//                            "id" => "per_supplier_diskon_id",
//                            "nama" => "per_supplier_diskon_nama",
//                            "label" => "per_supplier_diskon_alias",
//                        ),
//                        "detailModel" => "MdlDiskonPembelianSupplier",// MdlDiskonPembelianSupplier // MdlSupplierDiskon
//
//                        "detailModelSaldo" => "ComRekeningPembantuPiutangSupplierDetailMain",// ComRekeningPembantuPiutangSupplierDetailMain // ComRekeningPembantuCreditNoteDetail
//                        "detailModelFilter" => array(
//                            "periode=.forever",
//                            "extern2_id=pihakID",
//                        ),
//                        "detailModelKey" => "debet",
//                    ),
                    "credit_note_diskon" => array(
                        "label" => "credit note (klaim kepada supplier)",
                        "defaultValue" => "0",
                        "maxValue" => "credit_note_diskon",
                        "minValue" => "credit_note_diskon",
                        "saldo" => "creditAmountKlaim__saldo",
                        "keyPressAction" => "",
                        "addPoints" => array(1,),
                    ),
                    //-----------------
                    "harus_bayar" => array(
                        "label" => "netto",
                        "defaultValue" => "harus_bayar",
                        "maxValue" => "harus_bayar",
                        "minValue" => "harus_bayar",
                        //                        "keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(removeCommas(document.getElementById('bayar').value)-parseFloat(gt))",
                        //                        "keyupAction" => "var gt=this.min,bayar=this.value,kembali=document.getElementById('kembali'); kembali.value=parseFloat(bayar)-parseFloat(gt);if(parseFloat(bayar)<parseFloat(gt)){kembali.style.color='red',kembali.style.fontWeight='700'}else{kembali.style.color='green',kembali.style.fontWeight='700'}",
                        "hideRow" => false,
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
//                    "nilai_entry" => array(
//                        "label" => "payment",
//                        "defaultValue" => ".0",
//                        "keyupAction" => "
//    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=addCommas(document.getElementById('harus_bayar').value);}
//
//                            ",
//                        //                        'disabled'     => "disabled",
//                        "addPoints" => array(1,),
//                    ),

                    // dimatikan dulu, bikin ngisruh.. saat pakai uang muka, credit note (return) 08 feb 2024 jam 12.00
//                    "nilai_diskon_dipakai_add" => array(
//                        "label" => "diskon",
//                        "defaultValue" => "0",
//                        "keyupAction" => "",
//                        "maxValue" => "nilai_diskon_dipakai_add",
//                        "minValue" => "nilai_diskon_dipakai_add",
//                        "disabled"     => "disabled",
//                        "addPoints" => array(1,),
//                    ),

                    "nilai_entry" => array(
                        "label" => "payment",
                        "defaultValue" => ".0",
                        "keyupAction" => "
    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=(removeCommas(document.getElementById('harus_bayar').value));}
     
                            ",
                        //                        'disabled'     => "disabled",
                        "addPoints" => array(1,),
                    ),

                    "new_sisa" => array(
                        "label" => "balance of invoice",
                        "defaultValue" => "new_sisa",
                        "maxValue" => "new_sisa",
                        "minValue" => "new_sisa",
                        //                        "keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(removeCommas(document.getElementById('bayar').value)-parseFloat(gt))",
                        //                        "keyupAction" => "var gt=this.min,bayar=this.value,kembali=document.getElementById('kembali'); kembali.value=parseFloat(bayar)-parseFloat(gt);if(parseFloat(bayar)<parseFloat(gt)){kembali.style.color='red',kembali.style.fontWeight='700'}else{kembali.style.color='green',kembali.style.fontWeight='700'}",
                        //                        "hideRow" => "true",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),

                ),
            ),
        ),
        "shoppingCartReferenceFields" => array(
            "nomer" => "receipt number",
            "nomer_top" => "receipt ref.",
            "refNum" => "return ref.",
            "fulldate" => "date",
            "tagihan" => "due amount",
            "refValue" => "returned",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
            "notes" => "description",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "vendor",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "resumeFieldNames" => array(
            "selectFields" => "suppliers_nama",
            "title" => "vendor",
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
    "111" => array(
        "icon" => "fa fa-money",
        "label" => "Realisasi ppn masukan",
        "paymentConfig" => true,
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "realisasi ppn masukan",
                "actionLabel" => "entry faktur ppn masukan",
                "source" => "",
                "target" => "111",
                "userGroup" => "c_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "PT. Everest Electronic",
                "stateFooter" => "entry by",
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.467",
            "ppn_sisa>.0",
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
            "sisa" => "amount",
            "additionalFactor" => "keuntungan/kerugian kurs",
            "nilai_bayar" => "paid",
            "new_sisa" => "remain amount",
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

        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "dpp_ppn" => "Total",
            ),

        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            1 => "ppn_sisa",
        ),
        "showItems"=> "false",
        "shoppingCartAvoidRemove" => true,
        "viewDescriptionNote"=>true,
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
            1 => array(
                "fields" => array(
//                    "nomer" => "INV",
                    "dpp_ppn" => "Dasar Pengenaan pajak",
                    "ppn_sisa" => "Total ppn",
//                    "ppn_realisasi" => "PPN Realisasi",
                    "dateFaktur" => "Tgl faktur ",
                    "eFaktur" => "e-faktur",
                ),
                "editableFields" => array(
//                    "dpp_ppn" => "number",
//                    "ppn_realisasi" => "number",
                    "eFaktur" => "text",
                    "dateFaktur" => "date",
                ),
                "editProcess"=> "_processPihak/addTaxData"
            ),
        ),
        "efakturValidator" => array(
            1 => array(
                "enabled" => true,
                "kolom" => array(
                    "dateFaktur" => "tanggal e-faktur belum diisikan.",
                    "eFaktur" => "nomer e-faktur belum diisikan.",
                ),
                "source" => array(
                    "ppn_realisasi",
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

        "shopingCartUnionComparison" => array(


        ),
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
    //  config pembayaran expense/biaya usaha
    "477" => array(
        "icon" => "fa fa-money",
        "label" => "expense payment (biaya usaha cabang)",
        "place" => "center",
        "paymentConfig" => true,
        "steps" => array(
            1 => array(
                "label" => "expense payment",
                "actionLabel" => "process payment",
                "source" => "",
                "target" => "477",
                "userGroup" => "c_finance",
                "stateLabel" => "paid",
                "stateColor" => "#009900",
                "stateCaption" => "paid by",
            ),
        ),
        "template" => "template/transaksi_payment.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.463",
            "transaksi_nilai_sisa>.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer", "dtime",
        ),

        "selectorProcessor" => "_processSelectNota/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabangName" => "head office",
            "pihakName" => "branch",
            "nomer" => "receipt number",
            "details" => "detail",
            "oleh_nama" => "person",
            "nilai_entry" => "amount",
            "print_label" => "tool",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabangName" => "head office",
            "pihakName" => "branch",
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
            "terbayar" => "terbayar",
            "sisa" => "sisa",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "sisa" => "due remain",
            ),

        ),
        "shoppingCartEditableFields" => array(
            //            "harga",
            //            "ppn",
            //"jml",
        ),
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
        "tagihanSrc" => "harus_bayar",
        "receiptElements" => array(
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
                "mdlFilter" => array(//                    "bank.cabang_id=placeID",
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
                "noValidate" => false,
                "pairMethod" => array(
                    "recom" => "ReComCashMethode",
                    "calculate" => array(
                        "source" => "cash_account",
                        "prefix" => "cashMethode",
                        "target" => "",
                    ),
                ),
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
        "relativeElements" => array(
            //            "cashMethode" => array(
            //                "reguler" => array(
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
            //                "editPoints" => array(1, 2, 3),
            //            ),
        ),
        "pairRegistries" => array(
            "main", "items"
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
            "nomer" => "expense req. number",
            "fulldate" => "date",
            "tagihan" => "expense amount",
            "terbayar" => "paid",
            "sisa" => "remain",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "branch",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "sisa" => "due remain",
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "amount" => array(
                        "label" => "total amount",
                        "defaultValue" => "sisa",
                        "maxValue" => "sisa",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        'hideRow' => "true",
                    ),
                    //                    "credit_amount" => array(
                    //                        "label"        => "credit amount",
                    //                        "defaultValue" => "creditAmount",
                    //                        //                        "keyupAction" => "",
                    //                        'disabled'     => "disabled",
                    //                        "addPoints"    => array(1,),
                    //                    ),
                    //                    "credit_note"   => array(
                    //                        "label"        => "credit note",
                    //                        "defaultValue" => "creditValue",
                    //                        //                        "keyupAction" => "",
                    //                        'disabled'     => "disabled",
                    //                        "addPoints"    => array(1,),
                    //                    ),
                    "harus_bayar" => array(
                        "label" => "Total amount",
                        "defaultValue" => "sisa",
                        "maxValue" => "sisa",
                        "minValue" => "sisa",
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
                                    "
                    ,
                        //                        'disabled'     => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "sisa" => array(
                        "label" => "amount of payment",
                        "defaultValue" => "sisa",
                        "keyupAction" => "",
                        //                        'disabled'     => "disabled",
                        "addPoints" => array(1,),
                        'hideRow' => "true"
                    ),
                    "nilai_bayar" => array(
                        "label" => "amount of payment",
                        "defaultValue" => "nilai_bayar",
                        "keyupAction" => "",
                        //                        'disabled'     => "disabled",
                        "addPoints" => array(1,),
                        'hideRow' => "true"
                    ),
                    "new_sisa" => array(
                        "label" => "balance of invoice",
                        "defaultValue" => "new_sisa",
                        "maxValue" => "new_sisa",
                        "minValue" => "new_sisa",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                ),
            ),
        ),
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di  {cabang_nama}",
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
    ),
    "1477" => array(
        "icon" => "fa fa-money",
        "label" => "expense payment (biaya usaha pusat)",
        "place" => "center",
        "paymentConfig" => true,
        "steps" => array(
            1 => array(
                "label" => "expense payment",
                "actionLabel" => "process payment",
                "source" => "",
                "target" => "1477",
                "userGroup" => "c_finance",
                "stateLabel" => "paid",
                "stateColor" => "#009900",
                "stateCaption" => "paid by",
            ),
        ),
        "template" => "template/transaksi_payment.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.463",
            "transaksi_nilai_sisa>.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer", "dtime",
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
            //            "cabang2_nama" => "branch",
            "nomer" => "receipt number",
            "details" => "detail",
            "oleh_nama" => "person",
            "cash_account__label" => "bank account",
            "nilai_bayar" => "amount",
            //            "print_label" => "tool",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            //            "cabang2_nama" => "branch",
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
            "terbayar" => "terbayar",
            "sisa" => "sisa",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "sisa" => "due remain",
            ),

        ),
        "shoppingCartEditableFields" => array(
            //            "harga",
            //            "ppn",
            //"jml",
        ),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "nilai_round" => "Total amount",
                //                "creditAmount" => "paid using credit",
                //                "nilai_entry" => "paid using cash account",
                //                "nilai_bayar" => "total amount of payment",
                //                "new_sisa" => "remain debt (from list)",
            ),
        ),
        "tagihanSrc" => "harus_bayar",
        "receiptElements" => array(
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
                "mdlFilter" => array(//                    "bank.cabang_id=placeID",
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
                "noValidate" => false,
                "pairMethod" => array(
                    "recom" => "ReComCashMethode",
                    "calculate" => array(
                        "source" => "cash_account",
                        "prefix" => "cashMethode",
                        "target" => "",
                    ),
                ),
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
        "relativeElements" => array(
            //            "cashMethode" => array(
            //                "reguler" => array(
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
            //                "editPoints" => array(1, 2, 3),
            //            ),
        ),
        "pairRegistries" => array(
            "main", "items"
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
            "nomer" => "expense req. number",
            "fulldate" => "date",
            "tagihan" => "expense amount",
            "terbayar" => "paid",
            "sisa" => "remain",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "branch",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "sisa" => "due remain",
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "amount" => array(
                        "label" => "total amount",
                        "defaultValue" => "sisa",
                        "maxValue" => "sisa",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        'hideRow' => "true"
                    ),
                    //                    "credit_amount" => array(
                    //                        "label"        => "credit amount",
                    //                        "defaultValue" => "creditAmount",
                    //                        //                        "keyupAction" => "",
                    //                        'disabled'     => "disabled",
                    //                        "addPoints"    => array(1,),
                    //                    ),
                    //                    "credit_note"   => array(
                    //                        "label"        => "credit note",
                    //                        "defaultValue" => "creditValue",
                    //                        //                        "keyupAction" => "",
                    //                        'disabled'     => "disabled",
                    //                        "addPoints"    => array(1,),
                    //                    ),
                    "harus_bayar" => array(
                        "label" => "amount remains to pay",
                        "defaultValue" => "sisa",
                        "maxValue" => "sisa",
                        "minValue" => "sisa",
                        //                        "keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(removeCommas(document.getElementById('bayar').value)-parseFloat(gt))",
                        //                        "keyupAction" => "var gt=this.min,bayar=this.value,kembali=document.getElementById('kembali'); kembali.value=parseFloat(bayar)-parseFloat(gt);if(parseFloat(bayar)<parseFloat(gt)){kembali.style.color='red',kembali.style.fontWeight='700'}else{kembali.style.color='green',kembali.style.fontWeight='700'}",

                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        'hideRow' => "true"

                    ),
                    "nilai_entry" => array(
                        "label" => "amount of payment",
                        "defaultValue" => ".0",
                        "keyupAction" => "
    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harus_bayar').value;}
                            "
                    ,
                        //                        'disabled'     => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "sisa" => array(
                        "label" => "amount of payment",
                        "defaultValue" => "sisa",
                        "keyupAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        'hideRow' => "true",
                    ),
                    "new_sisa" => array(
                        "label" => "balance of invoice",
                        "defaultValue" => "new_sisa",
                        "maxValue" => "new_sisa",
                        "minValue" => "new_sisa",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                ),
            ),
        ),
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di  {cabang_nama}",
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
    ),
    //expense payment (biaya pusat)
    "6475" => array(
        "icon" => "fa fa-money",
        "label" => "expense payment (biaya pusat)",
        "place" => "center",
        "paymentConfig" => true,
        "steps" => array(
            1 => array(
                "label" => "expense payment",
                "actionLabel" => "process payment",
                "source" => "",
                "target" => "7475",
                "userGroup" => "c_finance",
                "stateLabel" => "paid",
                "stateColor" => "#009900",
                "stateCaption" => "paid by",
            ),
        ),
        "template" => "template/transaksi_payment.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.463",
            "transaksi_nilai_sisa>.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer", "dtime",
        ),

        "selectorProcessor" => "_processSelectNota/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
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

        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",
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
                "sisa" => "due remain",
            ),

        ),
        "shoppingCartEditableFields" => array(
            //            "harga",
            //            "ppn",
            //"jml",
        ),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                //                "sisa" => "debt amount",
                //                //                "creditAmount" => "paid using credit",
                //                "nilai_entry" => "paid using cash account",
                //                "nilai_bayar" => "total amount of payment",
                //                "new_sisa" => "remain debt (from list)",
            ),
        ),
        "tagihanSrc" => "harus_bayar",
        "receiptElements" => array(
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
                    "bank.cabang_id=placeID",
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
                "noValidate" => false,
                "pairMethod" => array(
                    "recom" => "ReComCashMethode",
                    "calculate" => array(
                        "source" => "cash_account",
                        "prefix" => "cashMethode",
                        "target" => "",
                    ),
                ),
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
        "relativeElements" => array(
            //            "cashMethode" => array(
            //                "reguler" => array(
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
            //                "editPoints" => array(1, 2, 3),
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
            "nomer" => "expense req. number",
            "fulldate" => "date",
            "tagihan" => "expense amount",
            "terbayar" => "paid",
            "sisa" => "remain",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "branch",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "sisa" => "due remain",
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "amount" => array(
                        "label" => "total amount",
                        "defaultValue" => "sisa",
                        "maxValue" => "sisa",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "sisa" => array(
                        "label" => "total amount",
                        "defaultValue" => "sisa",
                        "maxValue" => "sisa",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        'hideRow' => "true",
                    ),
                    //                    "credit_amount" => array(
                    //                        "label"        => "credit amount",
                    //                        "defaultValue" => "creditAmount",
                    //                        //                        "keyupAction" => "",
                    //                        'disabled'     => "disabled",
                    //                        "addPoints"    => array(1,),
                    //                    ),
                    //                    "credit_note"   => array(
                    //                        "label"        => "credit note",
                    //                        "defaultValue" => "creditValue",
                    //                        //                        "keyupAction" => "",
                    //                        'disabled'     => "disabled",
                    //                        "addPoints"    => array(1,),
                    //                    ),
                    "harus_bayar" => array(
                        "label" => "amount remains to pay",
                        "defaultValue" => "sisa",
                        "maxValue" => "sisa",
                        "minValue" => "sisa",
                        //                        "keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(removeCommas(document.getElementById('bayar').value)-parseFloat(gt))",
                        //                        "keyupAction" => "var gt=this.min,bayar=this.value,kembali=document.getElementById('kembali'); kembali.value=parseFloat(bayar)-parseFloat(gt);if(parseFloat(bayar)<parseFloat(gt)){kembali.style.color='red',kembali.style.fontWeight='700'}else{kembali.style.color='green',kembali.style.fontWeight='700'}",

                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        'hideRow' => "true",

                    ),
                    "nilai_entry" => array(
                        "label" => "amount of payment",
                        "defaultValue" => ".0",
                        "keyupAction" => "
    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harus_bayar').value;}
                            "
                    ,
                        //                        'disabled'     => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "new_sisa" => array(
                        "label" => "balance of invoice",
                        "defaultValue" => "new_sisa",
                        "maxValue" => "new_sisa",
                        "minValue" => "new_sisa",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "nilai_bayar" => array(
                        "label" => "balance of invoice",
                        "defaultValue" => "nilai_bayar",
                        "maxValue" => "nilai_bayar",
                        "minValue" => "nilai_bayar",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        'hideRow' => "true",
                    ),
                ),
            ),
        ),
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di  {cabang_nama}",
        ),
    ),
    //pembayaran hutang kepemegang saham
    "4448" => array(
        "icon" => "fa fa-money",
        "label" => "hutang kepemegang saham A/P payment",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "pembayaran hutang kepemegang saham",
                "actionLabel" => " LANJUT",
                "source" => "",
                "target" => "4448",
                "userGroup" => "c_finance",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
            ),
        ),
        "paymentConfig" => true,
        //"isPaymentRadioSelect" => true,//single payment aka pernota
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            //            "cabang_id=placeID",
            //            "jenis=.461",
            //            "transaksi_nilai_sisa>.0",
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
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "details" => "detail",
            "oleh_nama" => "person",
            "cash_account__label" => "account",
            "sisa" => "amount",
            //            "additionalFactor" => "keuntungan/kerugian kurs",
            "nilai_bayar" => "paid",
            "new_sisa" => "remain amount",
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
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            "nilai_bayar" => "nilai_bayar",
            "creditValue" => "diskon",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "sisa" => "nilai hutang",
                "nilai_bayar" => "nilai bayar",
                //                "new_sisa" => "nilai sisa",
                //                "terbayar" => "paid",
                //                "sisa" => "sisa",
            ),
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "sisa" => "total hutang",
                "nilai_bayar" => "total bayar",
                "new_sisa" => "sisa hutang",
            ),
        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => false,
        ),
        "shoppingCartAvoidRemove" => true,
        "tagihanSrc" => "harus_bayar",
        "receiptElements" => array(
            "vendorDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "details pemegang saham",
                "mdlName" => "MdlDtaModal",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "name",
                    "npwp" => "tax-ID",
                    "alamat_1" => "address",
                    "tlp_1" => "phone",
                ),
                "editPoints" => array(),
                "allowEdit" => false,
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
                "noValidate" => false,
                "pairMethod" => array(
                    "recom" => "ReComCashMethode",
                    "calculate" => array(
                        "source" => "cash_account",
                        "prefix" => "cashMethode",
                        "target" => "",
                    ),
                ),
                "labelValidate" => "Silahkan memilih sumber pembayaran sebelum melanjutkan transaksi.",
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
            //                "editPoints" => array(1, 2, 3),
            //            ),
        ),
        "pairMakers" => array(
            1 => array(
                "stock" => array(
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
        "shoppingCartRowNumValidators" => array(//            "nilai_entry" => "amount of payment",//off dulu baypass kerugian kurs
        ),
        "shoppingCartUnionValidators" => array(),
        "shopingCartAddValidator" => array(
            "additional" => array(
                "1" => "add_diskon",
                "-1" => "add_diskon",
            ),
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "harus_bayar" => array(
                        "label" => "amount remain to pay",
                        //                        "defaultValue" => "(sisa-nilai_entry)",
                        'disabled' => "disabled",
                        "addPoints" => array(1),
                    ),
                    "nilai_entry" => array(
                        "label" => "amount of payment",
                        "defaultValue" => ".0",
                        "keyupAction" => "if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harus_bayar').value;}",
                        "addPoints" => array(1),
                    ),
                    "new_sisa" => array(
                        "label" => "sisa setelah dibayar",
                        "defaultValue" => ".0",
                        "keyupAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1),
                    ),
                ),
            ),
        ),
        "shoppingCartReferenceFields" => array(
            "nomer" => "receipt number",
            "nomer_top" => "receipt ref.",
            "refNum" => "return ref.",
            "fulldate" => "date",
            "tagihan" => "due amount",
            "refValue" => "returned",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
            "notes" => "description",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "vendor",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "resumeFieldNames" => array(
            "selectFields" => "suppliers_nama",
            "title" => "vendor",
        ),
        "ppnDisabled" => array(
            "enabled" => false,
            "notes" => "PPN masukan belum diapprove oleh Finance.",
        ),
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di  {cabang_nama}",
        ),
    ),
    // config pembayaran hutang gaji
    "1485" => array(
        "icon" => "fa fa-money",
        "label" => "salary A/P payment",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "account payable payment",
                "actionLabel" => "process payment",
                "source" => "",
                "target" => "1485",
                "userGroup" => "c_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "paid by",
            ),
        ),
        "paymentConfig" => true,
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.674",
            "label=.hutang gaji",
            "transaksi_nilai_sisa>.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer", "dtime",
        ),

        "selectorProcessor" => "_processSelectNota/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "branch",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "branch",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "branch",
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
                "jml" => "qty",
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
                "sisa" => "sisa",
            ),
        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartAvoidRemove" => true,
        "receiptElements" => array(
            "branchDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "branch details",
                "mdlName" => "MdlCabang",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "name",
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
//                "pairedModel" => array(
//                    "mdlName" => "ComRekeningPembantuKas",
//                    "mdlMethod" => "fetchBalances",
//                    "mdlFilter" => array(
//                        "cabang_id=placeID",
//                    ),
//                    "key" => "extern_id",
//                    "rekening" => array(
//                        "kas", "plafon hutang bank",
//                    ),
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
                    //                    "rekening" => "kas",// kolom jenis di locker
                    "rekening" => array(
                        "kas", "plafon hutang bank",
                    ),
                    "fieldID" => "nilai",
                    "fieldLabel" => "saldo",
                ),
                "mdlName" => "MdlBankAccount_cash_and_in_and_koran",
                "mdlFilter" => array(
                    "bank.cabang_id=placeID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account number",
                    "saldo" => "balance",
                    "folders" => "acountMasterID",
                    "folders_nama" => "accountMaster",
                ),
                "editPoints" => array(1,),
                "noValidate" => false,
                "pairMethod" => array(
                    "recom" => "ReComCashMethode",
                    "calculate" => array(
                        "source" => "cash_account",
                        "prefix" => "cashMethode",
                        "target" => "",
                    ),
                ),
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
        "relativeElements" => array(
            //            "cashMethode" => array(
            //                "reguler" => array(
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
        "shoppingCartRowValidators" => array(
            "pihakID" => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartRowNumValidators" => array(
            "nilai_entry" => "amount of payment",
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "origAmount" => array(
                        "label" => "orig. amount",
                        "defaultValue" => "tagihan",
                        "maxValue" => "tagihan",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "amount" => array(
                        "label" => "total amount",
                        "defaultValue" => "sisa",
                        "maxValue" => "sisa",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
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
    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=removeCommas(document.getElementById('harus_bayar').value);}
                            "
                    ,
                        //                        'disabled'     => "disabled",
                        "addPoints" => array(1,),
                    ),
                ),
            ),
        ),
        "shoppingCartReferenceFields" => array(
            "nomer" => "receipt number",
            //            "nomer_top" => "receipt ref.",
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
            "tagihan" => "due amount",
            "terbayar" => "paid",
            //            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "resumeFieldNames" => array(
            "selectFields" => "cabang2_nama",
            "title" => "branch"
        ),
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di  {cabang_nama}",
        ),
    ),
    //  config pembayaran expense/biaya produksi
    "476" => array(
        "icon" => "fa fa-money",
        "label" => "expense payment (biaya produksi)",
        "place" => "center",
        "paymentConfig" => true,
        "steps" => array(
            1 => array(
                "label" => "expense payment",
                "actionLabel" => "process payment",
                "source" => "",
                "target" => "476",
                "userGroup" => "c_finance",
                "stateLabel" => "paid",
                "stateColor" => "#009900",
                "stateCaption" => "paid by",
            ),
        ),
        "template" => "template/transaksi_payment.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.463",
            "transaksi_nilai_sisa>.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer", "dtime",
        ),

        "selectorProcessor" => "_processSelectNota/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabangName" => "head office",
            "pihakName" => "branch",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "nilai_entry" => "amount",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabangName" => "head office",
            "pihakName" => "branch",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "nilai_entry" => "amount",
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
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "sisa" => "due remain",
            ),

        ),
        "shoppingCartEditableFields" => array(
            //            "harga",
            //            "ppn",
            //"jml",
        ),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "nilai_round" => "debt amount",
                //                "creditAmount" => "paid using credit",
                //                "nilai_entry" => "paid using cash account",
                //                "nilai_bayar" => "total amount of payment",
                //                "new_sisa" => "remain debt (from list)",
            ),
        ),
        "tagihanSrc" => "harus_bayar",
        "receiptElements" => array(
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
                    "bank.cabang_id=placeID",
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
                "noValidate" => false,
                "pairMethod" => array(
                    "recom" => "ReComCashMethode",
                    "calculate" => array(
                        "source" => "cash_account",
                        "prefix" => "cashMethode",
                        "target" => "",
                    ),
                ),
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
        "relativeElements" => array(
            //            "cashMethode" => array(
            //                "reguler" => array(
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
            //                "editPoints" => array(1, 2, 3),
            //            ),
        ),
        "pairRegistries" => array(
            "main", "items"
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
            "nomer" => "expense req. number",
            "fulldate" => "date",
            "tagihan" => "expense amount",
            "terbayar" => "paid",
            "sisa" => "remain",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "branch",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "sisa" => "due remain",
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "amount" => array(
                        "label" => "total amount",
                        "defaultValue" => "sisa",
                        "maxValue" => "sisa",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "sisa" => array(
                        "label" => "total amount",
                        "defaultValue" => "sisa",
                        "maxValue" => "sisa",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        'hideRow' => "true",
                    ),
                    "nilai_bayar" => array(
                        "label" => "total payment",
                        "defaultValue" => "nilai_bayar",
                        "maxValue" => "nilai_bayar",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        'hideRow' => "true",
                    ),
                    //                    "credit_amount" => array(
                    //                        "label"        => "credit amount",
                    //                        "defaultValue" => "creditAmount",
                    //                        //                        "keyupAction" => "",
                    //                        'disabled'     => "disabled",
                    //                        "addPoints"    => array(1,),
                    //                    ),
                    //                    "credit_note"   => array(
                    //                        "label"        => "credit note",
                    //                        "defaultValue" => "creditValue",
                    //                        //                        "keyupAction" => "",
                    //                        'disabled'     => "disabled",
                    //                        "addPoints"    => array(1,),
                    //                    ),
                    "harus_bayar" => array(
                        "label" => "amount remains to pay",
                        "defaultValue" => "sisa",
                        "maxValue" => "sisa",
                        "minValue" => "sisa",
                        //                        "keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(removeCommas(document.getElementById('bayar').value)-parseFloat(gt))",
                        //                        "keyupAction" => "var gt=this.min,bayar=this.value,kembali=document.getElementById('kembali'); kembali.value=parseFloat(bayar)-parseFloat(gt);if(parseFloat(bayar)<parseFloat(gt)){kembali.style.color='red',kembali.style.fontWeight='700'}else{kembali.style.color='green',kembali.style.fontWeight='700'}",

                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        "hideRow" => "true",

                    ),
                    "nilai_entry" => array(
                        "label" => "amount of payment",
                        "defaultValue" => ".0",
                        "keyupAction" => "
    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harus_bayar').value;}
                            "
                    ,
                        //                        'disabled'     => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "new_sisa" => array(
                        "label" => "balance of invoice",
                        "defaultValue" => "new_sisa",
                        "maxValue" => "new_sisa",
                        "minValue" => "new_sisa",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                ),
            ),
        ),
        "componentsAss" => array(
            "model" => "MdlTransaksi",
            "modelSrc" => "MdlNotaItem",
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
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di  {cabang_nama}",
        ),
    ),
    //  config pembayaran expense/biaya umum
    "475" => array(
        "icon" => "fa fa-money",
        "label" => "expense payment (biaya umum cabang)",
        "place" => "center",
        "paymentConfig" => true,
        "steps" => array(
            1 => array(
                "label" => "expense payment",
                "actionLabel" => "process payment",
                "source" => "",
                "target" => "475",
                "userGroup" => "c_finance",
                "stateLabel" => "paid",
                "stateColor" => "#009900",
                "stateCaption" => "paid by",
            ),
        ),
        "template" => "template/transaksi_payment.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.463",
            "transaksi_nilai_sisa>.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer", "dtime",
        ),
        "selectorProcessor" => "_processSelectNota/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabangName" => "head office",
            "pihakName" => "branch",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "nilai_entry" => "amount",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabangName" => "head office",
            "pihakName" => "branch",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "nilai_entry" => "amount",
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
            "terbayar" => "terbayar",
            "sisa" => "sisa",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "sisa" => "due remain",
            ),

        ),
        "shoppingCartEditableFields" => array(
            //            "harga",
            //            "ppn",
            //"jml",
        ),
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
        "tagihanSrc" => "harus_bayar",
        "receiptElements" => array(
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
                "mdlFilter" => array(//                    "bank.cabang_id=placeID",
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
                "noValidate" => false,
                "pairMethod" => array(
                    "recom" => "ReComCashMethode",
                    "calculate" => array(
                        "source" => "cash_account",
                        "prefix" => "cashMethode",
                        "target" => "",
                    ),
                ),
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
        "relativeElements" => array(
            //            "cashMethode" => array(
            //                "reguler" => array(
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
            //                "editPoints" => array(1, 2, 3),
            //            ),
        ),
        "pairRegistries" => array(
            "main", "items"
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
            "nomer" => "expense req. number",
            "fulldate" => "date",
            "tagihan" => "expense amount",
            "terbayar" => "paid",
            "sisa" => "remain",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "branch",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "sisa" => "due remain",
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "amount" => array(
                        "label" => "total amount",
                        "defaultValue" => "sisa",
                        "maxValue" => "sisa",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        'hideRow' => "true",
                    ),
                    "sisa" => array(
                        "label" => "total amount",
                        "defaultValue" => "sisa",
                        "maxValue" => "sisa",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        'hideRow' => "true",
                    ),
                    //                    "credit_amount" => array(
                    //                        "label"        => "credit amount",
                    //                        "defaultValue" => "creditAmount",
                    //                        //                        "keyupAction" => "",
                    //                        'disabled'     => "disabled",
                    //                        "addPoints"    => array(1,),
                    //                    ),
                    //                    "credit_note"   => array(
                    //                        "label"        => "credit note",
                    //                        "defaultValue" => "creditValue",
                    //                        //                        "keyupAction" => "",
                    //                        'disabled'     => "disabled",
                    //                        "addPoints"    => array(1,),
                    //                    ),
                    "harus_bayar" => array(
                        "label" => "amount remains to pay",
                        "defaultValue" => "sisa",
                        "maxValue" => "sisa",
                        "minValue" => "sisa",
                        //                        "keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(removeCommas(document.getElementById('bayar').value)-parseFloat(gt))",
                        //                        "keyupAction" => "var gt=this.min,bayar=this.value,kembali=document.getElementById('kembali'); kembali.value=parseFloat(bayar)-parseFloat(gt);if(parseFloat(bayar)<parseFloat(gt)){kembali.style.color='red',kembali.style.fontWeight='700'}else{kembali.style.color='green',kembali.style.fontWeight='700'}",

                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),

                    ),
                    "nilai_bayar" => array(
                        "label" => "amount remains to pay",
                        "defaultValue" => "nilai_bayar",
                        "maxValue" => "nilai_bayar",
                        "minValue" => "nilai_bayar",
                        //                        "keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(removeCommas(document.getElementById('bayar').value)-parseFloat(gt))",
                        //                        "keyupAction" => "var gt=this.min,bayar=this.value,kembali=document.getElementById('kembali'); kembali.value=parseFloat(bayar)-parseFloat(gt);if(parseFloat(bayar)<parseFloat(gt)){kembali.style.color='red',kembali.style.fontWeight='700'}else{kembali.style.color='green',kembali.style.fontWeight='700'}",

                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        'hideRow' => "true",

                    ),
                    "nilai_entry" => array(
                        "label" => "amount of payment",
                        "defaultValue" => ".0",
                        "keyupAction" => "
    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harus_bayar').value;}
                            "
                    ,
                        //                        'disabled'     => "disabled",
                        "addPoints" => array(1,),
                    ),
                    //                    "new_sisa" => "remain debt (from list)",
                    "new_sisa" => array(
                        "label" => "balance of invoice",
                        "defaultValue" => "new_sisa",
                        "maxValue" => "new_sisa",
                        "minValue" => "new_sisa",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                ),
            ),
        ),
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di  {cabang_nama}",
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
    ),
    "1475" => array(
        "icon" => "fa fa-money",
        "label" => "expense payment (biaya umum pusat)",
        "place" => "center",
        "paymentConfig" => true,
        "steps" => array(
            1 => array(
                "label" => "expense payment",
                "actionLabel" => "process payment",
                "source" => "",
                "target" => "1475",
                "userGroup" => "c_finance",
                "stateLabel" => "paid",
                "stateColor" => "#009900",
                "stateCaption" => "paid by",
            ),
        ),
        "template" => "template/transaksi_payment.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.463",
            "transaksi_nilai_sisa>.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer", "dtime",
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
            //            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "cash_account__label" => "bank account",
            "nilai_bayar" => "amount",
            //            "print_label" => "tool",
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
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",
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
                "sisa" => "due remain",
            ),

        ),
        "shoppingCartEditableFields" => array(
            //            "harga",
            //            "ppn",
            //"jml",
        ),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "nilai_round" => "amount",
                //                "creditAmount" => "paid using credit",
                //                "nilai_entry" => "paid using cash account",
                //                "nilai_bayar" => "total amount of payment",
                //                "new_sisa" => "remain debt (from list)",
            ),
        ),
        "tagihanSrc" => "harus_bayar",
        "receiptElements" => array(
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
                        "kas", "plafon hutang bank",
                    ),
                    "fieldID" => "nilai",
                    "fieldLabel" => "saldo",
                ),
                "mdlName" => "MdlBankAccount_cash_and_in_and_koran",
                "mdlFilter" => array(//                    "bank.cabang_id=placeID",
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
        "relativeElements" => array(
            //            "cashMethode" => array(
            //                "reguler" => array(
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
            //                "editPoints" => array(1, 2, 3),
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
            "nomer" => "expense req. number",
            "fulldate" => "date",
            "tagihan" => "expense amount",
            "terbayar" => "paid",
            "sisa" => "remain",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "branch",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "sisa" => "due remain",
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    //                    "amount" => array(
                    //                        "label" => "total amount",
                    //                        "defaultValue" => "sisa",
                    //                        "maxValue" => "sisa",
                    //                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    //                    "credit_amount" => array(
                    //                        "label"        => "credit amount",
                    //                        "defaultValue" => "creditAmount",
                    //                        //                        "keyupAction" => "",
                    //                        'disabled'     => "disabled",
                    //                        "addPoints"    => array(1,),
                    //                    ),
                    //                    "credit_note"   => array(
                    //                        "label"        => "credit note",
                    //                        "defaultValue" => "creditValue",
                    //                        //                        "keyupAction" => "",
                    //                        'disabled'     => "disabled",
                    //                        "addPoints"    => array(1,),
                    //                    ),
                    "harus_bayar" => array(
                        "label" => "amount remains to pay",
                        "defaultValue" => "sisa",
                        "maxValue" => "sisa",
                        "minValue" => "sisa",
                        //                        "keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(removeCommas(document.getElementById('bayar').value)-parseFloat(gt))",
                        //                        "keyupAction" => "var gt=this.min,bayar=this.value,kembali=document.getElementById('kembali'); kembali.value=parseFloat(bayar)-parseFloat(gt);if(parseFloat(bayar)<parseFloat(gt)){kembali.style.color='red',kembali.style.fontWeight='700'}else{kembali.style.color='green',kembali.style.fontWeight='700'}",

                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        'hideRow' => "true",

                    ),
                    "nilai_entry" => array(
                        "label" => "amount of payment",
                        "defaultValue" => ".0",
                        "keyupAction" => "
    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harus_bayar').value;}
                            "
                    ,
                        //                        'disabled'     => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "new_sisa" => array(
                        "label" => "balance of invoice",
                        "defaultValue" => "new_sisa",
                        "maxValue" => "new_sisa",
                        "minValue" => "new_sisa",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "sisa" => array(
                        "label" => "total amount",
                        "defaultValue" => "sisa",
                        "maxValue" => "sisa",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        'hideRow' => "true",
                    ),
                ),
            ),
        ),
        "pairRegistries" => array(
            "main", "items"
        ),
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di  {cabang_nama}",
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
    ),
    // config pembayaran hutang bpjs
    "1487" => array(
        "icon" => "fa fa-money",
        "label" => "BPJS payment",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "account payable payment",
                "actionLabel" => "process payment",
                "source" => "",
                "target" => "1487",
                "userGroup" => "c_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "paid by",
            ),
        ),
        "paymentConfig" => true,
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            //            "jenis=.674",
            "label=.hutang bpjs",
            "transaksi_nilai_sisa>.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer", "dtime",
        ),

        "selectorProcessor" => "_processSelectNota/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "branch",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "branch",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "branch",
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
                "jml" => "qty",
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
                "sisa" => "sisa",
            ),
        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartAvoidRemove" => true,
        "receiptElements" => array(
            "branchDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "branch details",
                "mdlName" => "MdlCabang",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "name",
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
                "noValidate" => false,
                "pairMethod" => array(
                    "recom" => "ReComCashMethode",
                    "calculate" => array(
                        "source" => "cash_account",
                        "prefix" => "cashMethode",
                        "target" => "",
                    ),
                ),
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
        "shoppingCartRowValidators" => array(
            "pihakID" => "cabang ID",
            "pihakName" => "cabang name",
        ),
        "shoppingCartRowNumValidators" => array(
            "nilai_entry" => "amount of payment",
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "origAmount" => array(
                        "label" => "orig. amount",
                        "defaultValue" => "tagihan",
                        "maxValue" => "tagihan",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "amount" => array(
                        "label" => "total amount",
                        "defaultValue" => "sisa",
                        "maxValue" => "sisa",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
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
    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=removeCommas(document.getElementById('harus_bayar').value);}
                            "
                    ,
                        //                        'disabled'     => "disabled",
                        "addPoints" => array(1,),
                    ),
                ),
            ),
        ),
        "shoppingCartReferenceFields" => array(
            "nomer" => "receipt number",
            "fulldate" => "date",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "sisa" => "due remain",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "branch",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "sisa" => "due remain",
        ),
        "resumeFieldNames" => array(
            "selectFields" => "cabang2_nama",
            "title" => "branch"
        ),
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di  {cabang_nama}",
        ),
    ),
    //pembayaran hutang pihak ke 3
    "4411" => array(
        "icon" => "fa fa-money",
        "label" => "hutang pihak lain A/P payment",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "pembayaran hutang pihak lain",
                "actionLabel" => " LANJUT",
                "source" => "",
                "target" => "4411",
                "userGroup" => "c_finance",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
            ),
        ),
        "paymentConfig" => true,
        //        "isPaymentRadioSelect" => true,//single payment aka pernota
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            //            "cabang_id=placeID",
            //            "jenis=.461",
            //            "transaksi_nilai_sisa>.0",
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
        "pihakProcessor" => "Selectors/_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "details" => "detail",
            "oleh_nama" => "person",
            "cash_account__label" => "account",
            "sisa" => "amount",
            //            "additionalFactor" => "keuntungan/kerugian kurs",
            "nilai_bayar" => "paid",
            "new_sisa" => "remain amount",
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
                "sisa" => "nilai hutang",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nomer",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            "nilai_bayar" => "nilai_bayar",
            "creditValue" => "diskon",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
//                "sisa" => "nilai hutang",
                //                "nilai_bayar" => "nilai bayar",
                //                "new_sisa" => "nilai sisa",
                //                "terbayar" => "paid",
                //                "sisa" => "sisa",
            ),
        ),
        "shoppingCartSumFields" => array(
            1 => array(
//                "sisa" => "total hutang",
                //                "nilai_bayar" => "total bayar",
                //                "new_sisa" => "sisa hutang",
            ),
        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => false,
        ),
        "shoppingCartAvoidRemove" => true,
        "tagihanSrc" => "harus_bayar",
        "receiptElements" => array(
            "vendorDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "details pihak lain",
                "mdlName" => "MdlDtaHutangPihak3",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "name",
                    "npwp" => "tax-ID",
                    "alamat_1" => "address",
                    "tlp_1" => "phone",
                ),
                "editPoints" => array(),
                "allowEdit" => false,
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
                "noValidate" => false,
                "pairMethod" => array(
                    "recom" => "ReComCashMethode",
                    "calculate" => array(
                        "source" => "cash_account",
                        "prefix" => "cashMethode",
                        "target" => "",
                    ),
                ),
                "labelValidate" => "Silahkan memilih sumber pembayaran sebelum melanjutkan transaksi.",
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
            //                "editPoints" => array(1, 2, 3),
            //            ),
        ),
        "pairMakers" => array(
            1 => array(
                "stock" => array(
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
        "shoppingCartRowNumValidators" => array(
            "nilai_entry" => "amount of payment",
        ),
        "shoppingCartUnionValidators" => array(),
        "shopingCartAddValidator" => array(
            "additional" => array(
                "1" => "add_diskon",
                "-1" => "add_diskon",
            ),
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "nilai_bayar" => array(
                        "label" => "total bayar",
                        'disabled' => "disabled",
                        "addPoints" => array(1),
                        "hideRow" => true,
                        "defaultValue" => "nilai_bayar",
                    ),
                    //                    "new_sisa" => array(
                    //                        "label" => "sisa hutang",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1),
                    //                        "hideRow" => true,
                    //                        "defaultValue" => "new_sisa",
                    //                    ),

                    "harus_bayar" => array(
                        "label" => "jumlah yang harus dibayar",
                        //                        "defaultValue" => "(sisa-nilai_entry)",
                        'disabled' => "disabled",
                        "addPoints" => array(1),
                    ),
                    "nilai_entry" => array(
                        "label" => "jumlah pembayaran",
                        "defaultValue" => ".0",
                        "keyupAction" => "if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harus_bayar').value;}",
                        "addPoints" => array(1),
                    ),
                    "new_sisa" => array(
                        "label" => "sisa setelah dibayar",
                        "defaultValue" => ".0",
                        "keyupAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1),
                    ),
                ),
            ),
        ),
        "shoppingCartReferenceFields" => array(
            "nomer" => "receipt number",
            "nomer_top" => "receipt ref.",
            //            "refNum" => "return ref.",
            "fulldate" => "date",
            "tagihan" => "due amount",
            //            "refValue" => "returned",
            "terbayar" => "paid",
            //            "diskon" => "discount",
            "sisa" => "due remain",
            "notes" => "description",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "vendor",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "resumeFieldNames" => array(
            "selectFields" => "suppliers_nama",
            "title" => "vendor",
        ),
        "ppnDisabled" => array(
            "enabled" => false,
            "notes" => "PPN masukan belum diapprove oleh Finance.",
        ),
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di  {cabang_nama}",
        ),
    ),
    //imbal jasa A/P payment
    "2119" => array(
        "icon" => "fa fa-money",
        "label" => "imbalan jasa A/P payment",
        //        "place" => "branch",
        "paymentConfig" => true,
        "isPaymentRadioSelect" => true,//single payment aka pernota
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "account payable payment",
                "actionLabel" => "process payment",
                "source" => "",
                "target" => "2119",
                "userGroup" => "c_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "PT. Everest Electronic",
                "stateFooter" => "paid by",
            ),
        ),
        "template" => "application/template/transaksi.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.462",
            "transaksi_nilai_sisa>.0",
        ),
        "selectorCaller" => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer",
            "dtime",
        ),

        "selectorProcessor" => "Selectors/_processSelectNota/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakProcessor" => "Selectors/_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
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

        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",
            ),

        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nomer",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            "extern_nilai2" => "extern_nilai2",
            "extern2_nama" => "extern2_nama",
        ),
        "shopingCartFieldsReplacer" => array(
            "pph_23" => "extern2_nama",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "extern_nilai2" => "DPP",
                "pph_23" => "pph21",
                "sisa" => "amount remaining",
            ),
        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartAvoidRemove" => true,
        "shoppingCartSumFields" => array(
            1 => array(),
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => false,
            3 => true,

        ),
        "tagihanSrc" => "harus_bayar",
        "receiptElements" => array(
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
                "noValidate" => false,
                "pairMethod" => array(
                    "recom" => "ReComCashMethode",
                    "calculate" => array(
                        "source" => "cash_account",
                        "prefix" => "cashMethode",
                        "target" => "",
                    ),
                ),
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
        "relativeElements" => array(
            //            "cashMethode" => array(
            //                "reguler" => array(
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
            //                "editPoints" => array(1, 2, 3),
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
        "shoppingCartRowValidators" => array(
            "pihakID" => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartRowNumValidators" => array(
            "nilai_entry" => "amount of payment",
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    //                    "amount" => array(
                    //                        "label" => "total amount",
                    //                        "defaultValue" => "sisa",
                    //                        "maxValue" => "sisa",
                    //                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
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
        "shoppingCartReferenceFields" => array(
            "nomer" => "receipt number",
            "nomer_top" => "receipt ref.",
            "refNum" => "return ref.",
            "fulldate" => "date",
            "tagihan" => "due amount",
            "refValue" => "returned",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
            "notes" => "description",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "vendor",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "resumeFieldNames" => array(
            "selectFields" => "suppliers_nama",
            "title" => "vendor",
        ),
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di  {cabang_nama}",
        ),
    ),
    //payment source object pajak
    "5684" => array(
        "icon" => "fa fa-money",
        "label" => "pph29 A/P payment",
        "place" => "center",
        "paymentConfig" => true,
        "steps" => array(
            1 => array(
                "label" => "pph29 A/P payment",
                "actionLabel" => "process",
                "source" => "",
                "target" => "5684",
                "userGroup" => "c_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "confirmed by",
            ),
        ),
        "template" => "template/transaksi_payment.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.5681",
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
            "suppliers_nama" => "grn",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "cash_account__label" => "account",
            "sisa" => "amount",
            //            "additionalFactor" => "keuntungan/kerugian kurs",
            "nilai_bayar" => "paid",
            //            "new_sisa" => "remain amount",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "grn",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_kode" => "part number",
                //                "produk_ord_jml" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "produk_kode" => "Product No.",
                "produk_nama" => "Description",
                "produk_ord_jml" => "qty",
                //                "satuan" => "uom",
            ),

            3 => array(
                "produk_nama" => "product name",
                "produk_kode" => "part number",
                "berat_new" => "W(KG)",
                "volume_new" => "CBM",
                "max_jml" => "SO",
                "sent_jml" => "tekirim",
                "produk_ord_jml" => "qty",
                "sub_berat_new" => "sub berat",
                "sub_volume_new" => "sub volume",
                //                "satuan" => "uom",

            ),
            4 => array(
                "produk_ord_jml" => "Qty (Pcs)",
                "produk_nama" => "Description",
                //                "produk_kode"       => "part number",
                //                "satuan"            => "uom",
                "jml" => "Quantity Per Pkg (Ctns)",
                "berat_new" => "Net/Pkg (Kgs)",
                "sub_berat_new" => "Total (Kgs)",
                "volume_new" => "Net/Pkg (Cbm)",
                "sub_volume_new" => "Total (Cbm)",
            ),
            5 => array(
                "produk_nama" => "product name",
                "produk_kode" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
            ),
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
        "shoppingCartAvoidRemove" => true,
        "tagihanSrc" => "harus_bayar",
        "receiptElements" => array(
            //            "customerDetails" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "customer details",
            //                "mdlName" => "MdlCustomer_and_pre",
            //                "mdlFilter" => array("id=pihakID"),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "usedFields" => array(
            //                    "nama" => "name",
            //                    "npwp" => "tax-ID",
            //                    "alamat_1" => "address",
            //                    "tlp_1" => "phone",
            //                ),
            //                "editPoints" => array(1, 2, 3, 4),
            //            ),

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
                    "nama" => "account number",
                    "alias" => "holder alias",
                    //                    "debet" => "balance",
                    "saldo" => "balance",
                    "folders" => "acountMasterID",
                    "folders_nama" => "accountMaster",
                ),
                "editPoints" => array(1,),
                "noValidate" => false,
                "pairMethod" => array(
                    "recom" => "ReComCashMethode",
                    "calculate" => array(
                        "source" => "cash_account",
                        "prefix" => "cashMethode",
                        "target" => "",
                    ),
                ),
                "labelValidate" => "Silahkan memilih sumber pembayaran sebelum melanjutkan transaksi.",
            ),

            //            "creditAmount" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "credit amount (from return)",
            //                "mdlName" => "MdlPaymentAntiSource",
            //                "mdlFilter" => array(
            //                    "extern_id=pihakID",
            //                    "cabang_id=cabangID",
            //                    "target_jenis=jenisTr",
            //                    "label=.piutang dagang",
            //                    "sisa>.0",
            //                ),
            //                "key" => "sisa",
            //                "labelSrc" => "nomer/sisa",
            //                "usedFields" => array(
            //                    "extern_nama" => "customer name",
            //                    "transaksi_id" => "return ID",
            //                    "nomer" => "return number",
            //                    "sisa" => "avail credit",
            //                    "jenis" => "jenis",
            //                ),
            //                "editPoints" => array(1,),
            //                "noValidate" => true,
            //                "autoSelect" => false,
            //                "noPrefetch" => true,
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
        "shoppingCartSumFields" => array(
            1 => array(
                "sisa" => "amount",
                //                "creditAmount" => "supplier credit amount",
                //                "creditValue" => "additional discount",
                //                "additional_value" => "additional price",
                //                "harus_bayar" => "amount remains to pay",
                "nilai_entry" => "amount of payment",
                "new_sisa" => "remain to pay (from list)",
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
        "pairRegistries" => array(
            "main", "items"
        ),
        "mainValueInjectors" => array(
            "amount" => "sisa",
            "creditAmount" => "creditAmount",
            "harus_bayar" => "harus_bayar",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "customer ID",
            "pihakName" => "customer name",
        ),
        "shoppingCartRowNumValidators" => array(//            "nilai_entry" => "amount of payment",
        ),
        "shoppingCartUnionValidators" => array(
            array(
                "creditAmount" => "credit amount",
                "nilai_entry" => "amount value",
            ),
        ),
        "shoppingCartReferenceFields" => array(
            "nomer" => "sales number",
            "nomer_top" => "sales ref.",
            "refNum" => "return ref.",
            "fulldate" => "date",
            "tagihan" => "due amount",
            "refValue" => "returned",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "branch",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    //                    "amount" => array(
                    //                        "label" => "total amount",
                    //                        "defaultValue" => "sisa",
                    //                        "maxValue" => "sisa",
                    //                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
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
        "componentsAss" => array(
            "model" => "MdlTransaksi",
            "modelSrc" => "MdlNotaItem",
        ),
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di  {cabang_nama}",
        ),
    ),
    //config setoran pph23 ke negara
    "115" => array(
        "icon" => "fa fa-money",
        "label" => "setor hutang pph23",
        //"place" => "branch",
        "paymentConfig" => true,
        "isPaymentRadioSelect" => true,//single payment aka pernota
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "setor hutang pph23",
                "actionLabel" => "simpan",
                "source" => "",
                "target" => "115",
                "userGroup" => "c_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "PT. Everest Electronic",
                "stateFooter" => "paid by",
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.462",
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
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "dateFaktur" => "date efaktur",
            "eFaktur" => "efaktur",
            "nilai_entry" => "nilai",
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
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",
            ),

        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nomer",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            "extern_nilai2" => "extern_nilai2",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "extern_nilai2" => "DPP",
                "sisa" => "pph23",
            ),
        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartAvoidRemove" => true,
        "shoppingCartSumFields" => array(
            1 => array(),
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => false,
            3 => true,

        ),
        "tagihanSrc" => "harus_bayar",
        "receiptElements" => array(
            "vendorDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "vendor details",
                "mdlName" => "MdlDtaModal2",
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
            "cash_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "cash account",
                "showNull" => true,
                "nullSrc" => "balance",
                "nullValue" => "<span class='text-red text-bold'>{saldo kosong}</span>",
                "pairedModel" => array(
                    "mdlName" => "ComRekeningPembantuKas",
                    "mdlMethod" => "fetchBalances",
                    "mdlFilter" => array(
                        "cabang_id=placeID",
                    ),
                    "key" => "extern_id",
                    "rekening" => array(
                        "kas", "plafon hutang bank",
                    ),
                    "fieldID" => "debet",
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
                "noValidate" => false,
                "pairMethod" => array(
                    "recom" => "ReComCashMethode",
                    "calculate" => array(
                        "source" => "cash_account",
                        "prefix" => "cashMethode",
                        "target" => "",
                    ),
                ),
                "labelValidate" => "Silahkan memilih sumber pembayaran sebelum melanjutkan transaksi.",
            ),
            "dateFaktur" => array(
                "elementType" => "dataField",
                "label" => "entry date",
                "inputType" => "date",
                "defaultValue" => date("Y-m-d"),
                "editPoints" => array(1),
            ),
            "eFaktur" => array(
                "elementType" => "dataField",
                "label" => "nomer faktur",
                "inputType" => "text",
                "defaultValue" => "",
                "editPoints" => array(1),
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
            "main", "tableIn_master_values",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartRowNumValidators" => array(
            "nilai_entry" => "amount of payment",
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    //                    "amount" => array(
                    //                        "label" => "total amount",
                    //                        "defaultValue" => "sisa",
                    //                        "maxValue" => "sisa",
                    //                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
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
        "shoppingCartReferenceFields" => array(
            "nomer" => "receipt number",
            "nomer_top" => "receipt ref.",
            "refNum" => "return ref.",
            "fulldate" => "date",
            "tagihan" => "due amount",
            "refValue" => "returned",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
            "notes" => "description",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "vendor",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "resumeFieldNames" => array(
            "selectFields" => "suppliers_nama",
            "title" => "vendor",
        ),
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di  {cabang_nama}",
        ),
    ),
    //config setor ppn ke negara masih di godong
    "114" => array(
        "modul" => "pembayaran",
        "icon" => "fa fa-money",
        "label" => "Setor PPN Bulanan",
        //        "place" => "branch",
        "paymentConfig" => true,
        "place" => "center",
        "showItems" => "false",
        "steps" => array(
            1 => array(
                "label" => "account payable payment",
                "actionLabel" => "process payment",
                "source" => "",
                "target" => "114",
                "userGroup" => "c_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "PT. Everest Electronic",
                "stateFooter" => "paid by",
            ),
        ),
        "template" => "template/transaksi_index2.html",
        "mode" => "index_multi",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.110",
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
        "selectorProcessor2" => "_processSelectNota2/select",
        "selectorProcessor3" => "_processSelectNota3/select",
        "selectorProcessor4" => "_processSelectNota4/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            //            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
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
        "shoppingCartFields" => array(
            1 => array(
                "extern_date2" => "tgl e-faktur",
                "extern_label2" => "e-faktur",
                "nama" => "realisasi ppn",
                "extern2_nama" => "invoice",
                // "jml" => "qty",
            ),

        ),
        "shoppingCartFieldItemSrc" => array(
            1 => array(
                "extern_date2" => "tgl e-faktur",
                "extern_label2" => "e-faktur",
                "extern_nama" => "vendor",
                "nama" => "realisasi",
                //                "jml" => "qty",
            ),

        ),
        "shoppingCartFieldSrc" => array(
            "extern_date2" => "extern_date2",
            "extern2_id" => "extern2_id",
            "extern2_nama" => "extern2_nama",
            "extern_id" => "extern_id",
            "extern_nama" => "extern_nama",
            "extern_label2" => "extern_label2",
            "extern_nilai2" => "extern_nilai2",
            "nama" => "nomer",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            "creditValue" => "diskon",
            "id" => "id",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "extern_nilai2" => "dpp",
                "sisa" => "ppn keluaran",
            ),

        ),
        "shoppingCartSumFields" => array(
            1 => array(//dikosongin biar yang tampil dari addrows
                //                "tagihan" => "amount",
                //                "ppn_masukan" => "input tax",
                //                "denda_nilai" => "penalty charge",
                //                "nilai_deposit_src_dipakai" => "paid by deposit",
                //                "nilai_entry" =>"paid by kas"
            ),

        ),
        "shoppingCartNumFieldItemSrc" => array(
            1 => array(
                "extern_nilai2" => "dpp",
                "sisa" => "ppn masukan",
            ),

        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartAvoidRemove" => true,
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "tagihanSrc" => "harus_bayar",
        "receiptElements" => array(
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
            "deposit_dipakai" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Deposit pajak ",
                "mdlName" => "MdlPaymentSource",
                "mdlFilter" => array(
                    //                    "cabang_id=placeID",
                    "cabang_id=placeID",
                    "target_jenis=.00001",
                    "jenis=jenisTr",
                    "sisa>.0",
                ),
                "key" => "id",
                "labelSrc" => "sisa",
                "usedFields" => array(
                    "label" => "deposit",
                    "id" => "relasi ID",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
                "noPrefetch" => true,
                "pairMethod" => array(
//                    "recom" => "ReComUangMuka",
                    "recom" => "ReComDepositPajak",
                    "calculate" => array(
                        "source" => "sisa",
                        "target" => "nilai_deposit_src_dipakai",
                        "pair_source" => "nilai_entry",//sunbe sumber yang dibandingkan
                    ),

                    //                    "customer" => "ReComDiscCustomer",
                ),
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
        "shoppingCartRowValidators" => array(),
        "shoppingCartRowNumValidators" => array(//            "nilai_entry" => "amount of payment",
        ),
        "shoppingCartUnionValidators" => array(
            array(
                "creditAmount" => "credit amount",
                "nilai_entry" => "amount value",
                "nilai_deposit_src_dipakai" => "no deposit selected",
                "saldo_deposit" => "no deposit selected",
            ),
        ),
        "shopingCartUnionComparison" => array(
            array(
                "nilai_entry" => "payment belum diisi",
                "cash_account" => "cash account belum dipilih",
            ),

        ),
        "shopingCartPaymentValidator" => array(

            //            "nilai_entry" => array(
            //                "label" => "payment belum diisi",
            //            ),
            //            "cash_account" => array(
            //                "label" => "cash account belum dipilih",
            //            ),
            //            "creditAmount" => array(
            //                "label" => "credit note (from return) belum dipilih",
            //            ),


        ),
        "shopingCartPairedPaymentValidator" => array(
            //            "nilai_entry" => array(
            //                "key" => "cash_account",
            //                "label" => "cash account belum dipilih.",
            //            ),
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "nilai_sisa" => array(
                        "label" => "ppn keluaran",
                        "defaultValue" => "nilai_sisa",
                        "maxValue" => "nilai_sisa",
                        "minValue" => "nilai_sisa",
                        //                        "keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(removeCommas(document.getElementById('bayar').value)-parseFloat(gt))",
                        //                        "keyupAction" => "var gt=this.min,bayar=this.value,kembali=document.getElementById('kembali'); kembali.value=parseFloat(bayar)-parseFloat(gt);if(parseFloat(bayar)<parseFloat(gt)){kembali.style.color='red',kembali.style.fontWeight='700'}else{kembali.style.color='green',kembali.style.fontWeight='700'}",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1),
                    ),
                    "ppn_bendahara_negara" => array(
                        "label" => "ppn dibayar bendahara negara",
                        "defaultValue" => "ppn_bendahara_negara",
                        "maxValue" => "ppn_bendahara_negara",
                        "minValue" => "ppn_bendahara_negara",
                        //                        "keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(removeCommas(document.getElementById('bayar').value)-parseFloat(gt))",
                        //                        "keyupAction" => "var gt=this.min,bayar=this.value,kembali=document.getElementById('kembali'); kembali.value=parseFloat(bayar)-parseFloat(gt);if(parseFloat(bayar)<parseFloat(gt)){kembali.style.color='red',kembali.style.fontWeight='700'}else{kembali.style.color='green',kembali.style.fontWeight='700'}",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "ppn_masukan" => array(
                        "label" => "ppn masukan",
                        "defaultValue" => "ppn_masukan",
                        "maxValue" => "ppn_masukan",
                        "minValue" => "ppn_masukan",
                        //                        "keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(removeCommas(document.getElementById('bayar').value)-parseFloat(gt))",
                        //                        "keyupAction" => "var gt=this.min,bayar=this.value,kembali=document.getElementById('kembali'); kembali.value=parseFloat(bayar)-parseFloat(gt);if(parseFloat(bayar)<parseFloat(gt)){kembali.style.color='red',kembali.style.fontWeight='700'}else{kembali.style.color='green',kembali.style.fontWeight='700'}",

                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1),
                    ),
                    "ppn_pib" => array(
                        "label" => "PIB",
                        "defaultValue" => "ppn_pib",
                        "maxValue" => "ppn_pib",
                        "minValue" => "ppn_pib",
                        //                        "keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(removeCommas(document.getElementById('bayar').value)-parseFloat(gt))",
                        //                        "keyupAction" => "var gt=this.min,bayar=this.value,kembali=document.getElementById('kembali'); kembali.value=parseFloat(bayar)-parseFloat(gt);if(parseFloat(bayar)<parseFloat(gt)){kembali.style.color='red',kembali.style.fontWeight='700'}else{kembali.style.color='green',kembali.style.fontWeight='700'}",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "nilai_deposit_src_dipakai" => array(
                        "label" => "deposit",
                        "defaultValue" => "nilai_deposit_src_dipakai",
                        "maxValue" => "nilai_deposit_src_dipakai",
                        "minValue" => "nilai_deposit_src_dipakai",
                        //                        "keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(removeCommas(document.getElementById('bayar').value)-parseFloat(gt))",
                        //                        "keyupAction" => "var gt=this.min,bayar=this.value,kembali=document.getElementById('kembali'); kembali.value=parseFloat(bayar)-parseFloat(gt);if(parseFloat(bayar)<parseFloat(gt)){kembali.style.color='red',kembali.style.fontWeight='700'}else{kembali.style.color='green',kembali.style.fontWeight='700'}",

                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1),
                    ),
                    "denda_nilai" => array(
                        "label" => "(optional) biaya pinalti",
                        "defaultValue" => ".0",
                        "maxValue" => "",
                        "minValue" => ".0",
                        //"keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(removeCommas(document.getElementById('bayar').value)-parseFloat(gt))",
                        "keyupAction" => "
                            if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('denda_nilai').value)) || parseInt(removeCommas(this.value))<0){

                            }
                        ",

                        "keyPressAction" => "",
                        "addPoints" => array(1),
                    ),
                    "harus_bayar" => array(
                        "label" => "disetor",
                        "defaultValue" => "harus_bayar",
                        "maxValue" => "harus_bayar",
                        "minValue" => "sisa",
                        //                        "keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(removeCommas(document.getElementById('bayar').value)-parseFloat(gt))",
                        //                        "keyupAction" => "var gt=this.min,bayar=this.value,kembali=document.getElementById('kembali'); kembali.value=parseFloat(bayar)-parseFloat(gt);if(parseFloat(bayar)<parseFloat(gt)){kembali.style.color='red',kembali.style.fontWeight='700'}else{kembali.style.color='green',kembali.style.fontWeight='700'}",

                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1),
                    ),

                    "nilai_entry" => array(
                        "label" => "nilai entry",
                        "defaultValue" => "nilai_entry",
                        "maxValue" => "nilai_entry",
                        "minValue" => ".0",
                        "keyupAction" => "",
                        //                        'disabled'     => "disabled",
                        'hideRow' => "true",
                        "addPoints" => array(1),
                    ),
                    "saldo_deposit" => array(
                        "label" => "saldo deposit",
                        "defaultValue" => "saldo_deposit",
                        "maxValue" => "saldo_deposit",
                        "minValue" => ".0",
                        "keyupAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1),
                    ),

                ),
            ),
        ),
        "shoppingCartReferenceFields" => array(
            "nomer" => "receipt number",
            //            "nomer_top" => "receipt ref.",
            //            "refNum" => "return ref.",
            "extern_date2" => "date",
            "extern_label2" => "e-faktur",
            "extern_nilai2" => "DPP",

            "tagihan" => "due amount",
            "refValue" => "returned",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "branch",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "resumeFieldNames" => array(
            "selectFields" => "suppliers_nama",
            "title" => "vendor",
        ),
        "shopingcartSource" => array(
            "target" => "0000",
            "filter" => array(
                "target='.0000'",
                "label='.ppn realisasi'",
                "jenis='.111'",
                "tagihan>.0",
            ),
        ),
        "shopingCartSourceFieldSrc" => array(
            "nama" => "nomer",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            "creditValue" => "diskon",
        ),
        "shopingCartSourceFields" => array(
            "nama" => "nomer",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            "creditValue" => "diskon",
        ),
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di  {cabang_nama}",
        ),
    ),
    //setor hutang pph ps4(2)
    "1120" => array(
        "icon" => "fa fa-money",
        "label" => "setor hutang pph ps4 ayat 2",
        //        "place" => "branch",
        "paymentConfig" => true,
        "isPaymentRadioSelect" => true,//single payment aka pernota
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "account payable payment",
                "actionLabel" => "process payment",
                "source" => "",
                "target" => "1120",
                "userGroup" => "c_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "PT. Everest Electronic",
                "stateFooter" => "paid by",
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.425",
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
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
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

        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",
            ),

        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nomer",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            "extern_nilai2" => "extern_nilai2",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "extern_nilai2" => "DPP",
                "sisa" => "pph23",
            ),
        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartAvoidRemove" => true,
        "shoppingCartSumFields" => array(
            1 => array(),
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => false,
            3 => true,

        ),
        "tagihanSrc" => "harus_bayar",
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
            "dateFaktur" => array(
                "elementType" => "dataField",
                "label" => "entry date",
                "inputType" => "date",
                "defaultValue" => date("Y-m-d"),
                "editPoints" => array(1),
            ),
            "eFaktur" => array(
                "elementType" => "dataField",
                "label" => "nomer faktur",
                "inputType" => "text",
                "defaultValue" => "",
                "editPoints" => array(1),
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
        "shoppingCartRowValidators" => array(
            "pihakID" => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartRowNumValidators" => array(
            "nilai_entry" => "amount of payment",
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    //                    "amount" => array(
                    //                        "label" => "total amount",
                    //                        "defaultValue" => "sisa",
                    //                        "maxValue" => "sisa",
                    //                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
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
        "shoppingCartReferenceFields" => array(
            "nomer" => "receipt number",
            "nomer_top" => "receipt ref.",
            "refNum" => "return ref.",
            "fulldate" => "date",
            "tagihan" => "due amount",
            "refValue" => "returned",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
            "notes" => "description",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "vendor",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "resumeFieldNames" => array(
            "selectFields" => "suppliers_nama",
            "title" => "vendor",
        ),
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di  {cabang_nama}",
        ),
    ),
    //pph22 A/P payment
    "5682" => array(
        "icon" => "fa fa-money",
        "label" => "pph22 A/P payment",
        "place" => "center",
        "paymentConfig" => true,
        "steps" => array(
            1 => array(
                "label" => "pph22 A/P payment",
                "actionLabel" => "process",
                "source" => "",
                "target" => "5682",
                "userGroup" => "c_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "confirmed by",
            ),
        ),
        "template" => "template/transaksi_payment.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.5681",
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
            "suppliers_nama" => "grn",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "cash_account__label" => "account",
            "sisa" => "amount",
            //            "additionalFactor" => "keuntungan/kerugian kurs",
            "nilai_bayar" => "paid",
            //            "new_sisa" => "remain amount",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "grn",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_kode" => "part number",
                //                "produk_ord_jml" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "produk_kode" => "Product No.",
                "produk_nama" => "Description",
                "produk_ord_jml" => "qty",
                //                "satuan" => "uom",
            ),

            3 => array(
                "produk_nama" => "product name",
                "produk_kode" => "part number",
                "berat_new" => "W(KG)",
                "volume_new" => "CBM",
                "max_jml" => "SO",
                "sent_jml" => "tekirim",
                "produk_ord_jml" => "qty",
                "sub_berat_new" => "sub berat",
                "sub_volume_new" => "sub volume",
                //                "satuan" => "uom",

            ),
            4 => array(
                "produk_ord_jml" => "Qty (Pcs)",
                "produk_nama" => "Description",
                //                "produk_kode"       => "part number",
                //                "satuan"            => "uom",
                "jml" => "Quantity Per Pkg (Ctns)",
                "berat_new" => "Net/Pkg (Kgs)",
                "sub_berat_new" => "Total (Kgs)",
                "volume_new" => "Net/Pkg (Cbm)",
                "sub_volume_new" => "Total (Cbm)",
            ),
            5 => array(
                "produk_nama" => "product name",
                "produk_kode" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
            ),
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
        "shoppingCartAvoidRemove" => true,
        "tagihanSrc" => "harus_bayar",
        "receiptElements" => array(

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
                    "nama" => "account number",
                    "alias" => "holder alias",
                    //                    "debet" => "balance",
                    "saldo" => "balance",
                    "folders" => "acountMasterID",
                    "folders_nama" => "accountMaster",
                ),
                "editPoints" => array(1,),
                "noValidate" => false,
                "pairMethod" => array(
                    "recom" => "ReComCashMethode",
                    "calculate" => array(
                        "source" => "cash_account",
                        "prefix" => "cashMethode",
                        "target" => "",
                    ),
                ),
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
        "shoppingCartSumFields" => array(
            1 => array(
                "sisa" => "amount",
                //                "creditAmount" => "supplier credit amount",
                //                "creditValue" => "additional discount",
                //                "additional_value" => "additional price",
                //                "harus_bayar" => "amount remains to pay",
                "nilai_entry" => "amount of payment",
                "new_sisa" => "remain to pay (from list)",
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
        "pairRegistries" => array(
            "main", "items"
        ),
        "mainValueInjectors" => array(
            "amount" => "sisa",
            "creditAmount" => "creditAmount",
            "harus_bayar" => "harus_bayar",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "customer ID",
            "pihakName" => "customer name",
        ),
        "shoppingCartRowNumValidators" => array(//            "nilai_entry" => "amount of payment",
        ),
        "shoppingCartUnionValidators" => array(
            array(
                "creditAmount" => "credit amount",
                "nilai_entry" => "amount value",
            ),
        ),
        "shoppingCartReferenceFields" => array(
            "nomer" => "sales number",
            "nomer_top" => "sales ref.",
            "refNum" => "return ref.",
            "fulldate" => "date",
            "tagihan" => "due amount",
            "refValue" => "returned",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "grn number",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    //                    "amount" => array(
                    //                        "label" => "total amount",
                    //                        "defaultValue" => "sisa",
                    //                        "maxValue" => "sisa",
                    //                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
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
        "componentsAss" => array(
            "model" => "MdlTransaksi",
            "modelSrc" => "MdlNotaItem",
        ),
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di  {cabang_nama}",
        ),
    ),
    //service A/P Payment pusat
    "1462" => array(
        "icon" => "fa fa-money",
        "label" => "service A/P payment(pusat)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "account payable payment",
                "actionLabel" => "process payment",
                "source" => "",
                "target" => "1462",
                "userGroup" => "c_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
            ),
        ),
        "paymentConfig" => true,
        "isPaymentRadioSelect" => true,
        "template" => "application/template/transaksi.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.1463",
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
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "details" => "detail",
            "oleh_nama" => "person",
            "sisa" => "amount",
            "pph23_nilai" => "pph 23",
            "uang_muka_dipakai" => "deposit",
            "pay_out" => "paid",
            "nilai_bayar" => "total paid",
            "pphGateLabel" => "status pph 23",
            "keterangan" => "keterangan",
            "print_label" => "tool",
            //            "new_sisa" => "remain amount",
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
        "shoppingCartFields2" => array(
            1 => array(
                "nama" => "item source name",
                "jml" => "qty",
                "harga" => "price",
                "ppn" => "ppn",
                //                "referensi" => "reference",
            ),
            2 => array(
                "nama" => "item source name",
                "jml" => "qty",
                //                "satuan" => "satuan",
                //                "referensi" => "reference",
            ),
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "extern2_nama" => "metode pph23",
                "jml" => "qty",
            ),

        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nomer",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            "extern_nilai2" => "extern_nilai2",//dpp pph
            "extern_nilai3" => "extern_nilai3",//dpp ppn
            "ppn" => "ppn",
            "extern2_id" => "extern2_id",
            "extern2_nama" => "extern2_nama",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "extern_nilai2" => "DPP pph",
                "extern_nilai3" => "DPP ppn",
                "ppn" => "ppn",
                //                "sisa" => "sisa",
            ),

        ),
        "shoppingCartSumFields" => array(
            1 => array(
                //                "extern_nilai2" => "DPP",
                //                "ppn" => "ppn",
                //                "sisa" => "sisa",
            ),

        ),
        "shopingCartReload" => "true",

        "shoppingCartEditableFields" => array(
            //            "harga",
            //            "ppn",
            //"jml",
        ),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartAvoidRemove" => true,
        "receiptElements" => array(
            "vendorDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "vendor details",
                "mdlName" => "MdlSupplier",
//                "mdlName" => "MdlSupplierAll",
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
            "pph23Method" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "method of pph 23",
                "mdlName" => "MdlPph23Method",
                "key" => "id",
                //                "defaultValue" => "item",
                "labelSrc" => "name",
                // sementara dimatikan dulu karena bug npwp
                //                "autoFilter" => array(
                //                    "key" => "pairPihakName",
                //                    "srcRef" => array(
                //                        "mdl" => "MdlSupplier",
                //                        "filter" => "pihakID",
                //                        "srcField" => "npwp",
                //                    ),
                //                    "pairKey" => array(
                ////                        "validate" => "npwp",
                //                        "methode" => array(
                //                            "dipotong" => array(
                //                                "true" => "npwp",
                //                                "false" => "non_npwp",
                //                            ),
                //                            "tidak dipotong" => array(
                //                                "true" => "sket",
                //                                "false" => "non_npwp",
                //                            ),
                //
                //                        ),
                //                    ),
                //                ),//pasang di _shopingCart
                "usedFields" => array(
                    "name" => "method",
                    "tarif" => "tarif (%)",
                ),
                "editPoints" => array(1,),
                "targetMethod" => array(
                    "npwp" => "ReComPph23Npwp_purchasing",
                    "non_npwp" => "ReComPph23NonNpwp_purchasing",
                    "sket" => "ReComPph23None_purchasing",
                ),
            ),
            //            "cashMethode" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "Metode rekening",
            //                "mdlName" => "MdlCashAccountStatic",
            //                "mdlFilter" => array(
            ////                    "extern_id=pihakID",
            ////                    "cabang_id=cabangID",
            ////                    "sisa>.0",
            //                ),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "usedFields" => array(
            //                    "nama" => "method",
            ////                    "extern_id" => "pihakID",
            //
            //                ),
            //                "editPoints" => array(1,),
            //                "noValidate" => true,
            //                "noPrefetch" => true,
            ////                "pairMethod" => array(
            ////                    "recom" => "ReComUangMuka",
            ////                    "calculate" => array(
            ////                        "source" => "uangMuka",
            ////                        "target" => "uang_muka_dipakai",
            ////                        "pair_source" => "nilai_sisa",//sunbe sumber yang dibandingkan
            ////                    ),
            //
            //
            //            ),

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
            "uangMuka" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "credit from uang muka",
                "mdlName" => "MdlSupplierCreditUangMuka",
                "mdlFilter" => array(
                    "extern_id=pihakID",
                    "cabang_id=cabangID",
                    "extern_label2=.vendor",
                    "sisa>.0"
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
                        "source" => "sisa",
                        "target" => "uang_muka_dipakai",
                        "pair_source" => "sisa",//sunbe sumber yang dibandingkan /// nilai_sisa
                    ),

                    //                    "customer" => "ReComDiscCustomer",
                ),
            ),

            //            "branchTarget" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "target pembebanan ",
            //                "mdlName" => "MdlCabang",
            //                "key" => "id",
            //                "mdlFilter" => array(
            //                    "id=.-1",
            //                ),
            //                "labelSrc" => "nama",
            //                "usedFields" => array(
            //                    "nama" => "nama",
            ////                    "tarif" => "tarif (%)",
            //                ),
            //                "editPoints" => array(1,),
            //            ),
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
            "pph23Method" => array(
                "sket" => array(
                    "pph23Method__desc" => array(
                        "elementType" => "dataField",
                        "label" => "SKB/ESKET",
                        "inputType" => "text",
                        "defaultValue" => "",
                        "editPoints" => array(1),
                    ),
                ),
            ),

            //            "branchTarget" =>array(
            //                "-1" =>array(
            //                    "externMain"=>array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "radio",
            //                        "label" => "transfer expense to",
            //                        "mdlName" => "MdlBiayaMethodGeneral",
            //                        "mdlFilter" => array(),
            //                        "key" => "nama",
            //                        "labelSrc" => "nama",
            //                        "description" => "",
            //                        "usedFields" => array(
            //                            "nama" => "kategori biaya",
            //                        ),
            //                        "editPoints" => array(1,),
            //                        "noValidate" => true,
            //                    ),
            //                ),
            //            ),
            //            "externMain" =>array(
            //                "biaya umum" =>array(
            //                    "dtaDetail" =>array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "combo",
            //                        "label" => "expense details",
            //                        "mdlName" => "MdlDtaBiayaUmum",
            //                        "mdlFilter" => array(),
            //                        "key" => "id",
            //                        "labelSrc" => "nama",
            //                        "description" => "",
            //                        "usedFields" => array(
            //                            "nama" => "beban biaya",
            //                        ),
            //                        "targetMethod" => array(
            ////                            "biaya usaha" => "ReComBiayaUsaha_payment",
            //                            "biaya umum" => "ReComBiayaUmum_payment",
            ////                            "non_npwp" => "ReComPph23NonNpwp_purchasing",
            ////                            "none" => "ReComPph23None_purchasing",
            //                        ),
            //                        "editPoints" => array(1,),
            //                        "noValidate" => true,
            //                    ),
            //                ),
            //                "biaya usaha" =>array(
            //                    "dtaDetail" =>array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "combo",
            //                        "label" => "expense details",
            //                        "mdlName" => "MdlDtaBiayaUsaha",
            //                        "mdlFilter" => array(),
            //                        "key" => "id",
            //                        "labelSrc" => "nama",
            //                        "description" => "",
            //                        "usedFields" => array(
            //                            "nama" => "beban biaya",
            //                        ),
            //                        "targetMethod" => array(
            //                            "biaya usaha" => "ReComBiayaUsaha_payment",
            ////                            "biaya umum" => "ReComBiayaUmum_payment",
            ////                            "non_npwp" => "ReComPph23NonNpwp_purchasing",
            ////                            "none" => "ReComPph23None_purchasing",
            //                        ),
            //                        "editPoints" => array(1,),
            //                        "noValidate" => true,
            //                    ),
            //
            //                ),
            //                "biaya produksi" =>array(
            //                    "dtaDetail" =>array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "combo",
            //                        "label" => "expense details",
            //                        "mdlName" => "MdlDtaBiayaProduksi",
            //                        "mdlFilter" => array(),
            //                        "key" => "id",
            //                        "labelSrc" => "nama",
            //                        "description" => "",
            //                        "usedFields" => array(
            //                            "nama" => "beban biaya",
            //                        ),
            //                        "editPoints" => array(1,),
            //                        "noValidate" => true,
            //                    ),
            //
            //                ),
            //            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_kode" => "part number",
                //                "produk_ord_jml" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "produk_kode" => "Product No.",
                "produk_nama" => "Description",
                "produk_ord_jml" => "qty",
                //                "satuan" => "uom",
            ),

            3 => array(
                "produk_nama" => "product name",
                "produk_kode" => "part number",
                "berat_new" => "W(KG)",
                "volume_new" => "CBM",
                "max_jml" => "SO",
                "sent_jml" => "tekirim",
                "produk_ord_jml" => "qty",
                "sub_berat_new" => "sub berat",
                "sub_volume_new" => "sub volume",
                //                "satuan" => "uom",

            ),
            4 => array(
                "produk_ord_jml" => "Qty (Pcs)",
                "produk_nama" => "Description",
                //                "produk_kode"       => "part number",
                //                "satuan"            => "uom",
                "jml" => "Quantity Per Pkg (Ctns)",
                "berat_new" => "Net/Pkg (Kgs)",
                "sub_berat_new" => "Total (Kgs)",
                "volume_new" => "Net/Pkg (Cbm)",
                "sub_volume_new" => "Total (Cbm)",
            ),
            5 => array(
                "produk_nama" => "product name",
                "produk_kode" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
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
        "pairRegistries" => array(
            "main", "items"
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "vendor ID",
            "pihakName" => "vendor name",
            "pphGateId" => "metode potongan pph tidak terdeteksi",
        ),
        "shoppingCartRowNumValidators" => array(
            "nilai_entry" => "amount of payment",
        ),
        "shopingCartPaymentComparisonValidator" => array(
            array(
                "source" => "nilai_dipakai_hutang_biaya", // hutang dagang
                "target" => "nilai_bayar", // payment source
                "label" => "Pastikan penggunaan Kas, Uang Muka sudah sesuai untuk pelunasan Invoice ini.", //
            ),

        ),
        "shopingCartValueValidator" => array(
            "pphGateId" => "metode pph 23 tidak dikenal",
        ),

        "shopingCartPaymentValueValidator" => array(
            "srcDana" => array(
                "uang_muka_dipakai",
                "kas_value",
                "rekening_koran_value",
            ),
            "srcTagihan" => array(
                "sisa",
            ),
        ),
        "shopingCartPaymentValueValidatorLabel" => "Cicilan AP Payment Jasa dimasukkan sebagai uang muka.<br>Uang muka hanya bisa dipakai saat pelunasan",

        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "amount" => array(
                        "label" => "total amount",
                        "defaultValue" => "sisa",
                        "maxValue" => "sisa",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),

                    "credit_amount" => array(
                        "label" => "credit amount",
                        "defaultValue" => "creditAmount",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),

                    //                    "harus_bayar" => array(
                    //                        "label" => "amount remains to pay",
                    //                        "defaultValue" => "sisa-creditAmount-creditValue",
                    ////                        "defaultValue" => ".0",
                    //                        "maxValue" => "sisa-creditAmount-creditValue",
                    //                        "minValue" => "sisa-creditAmount-creditValue",
                    //                        "keyPressAction" => "",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    "nilai_entry" => array(
                        "label" => "amount of payment",
                        "defaultValue" => "sisa",
                        "keyupAction" => "",
                        "hideRow" => "true",
                        //                        "keyupAction" => "if(parseFloat(removeCommas(this.value))>parseFloat(removeCommas(document.getElementById('amount').value)) || parseFloat(removeCommas(this.value))<0){this.value=document.getElementById('amount').value;}",
                        //                        "keyupAction" => "if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('payment_out').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('payment_out').value;}",
                        //                        'disabled'     => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "sisa" => array(
                        "label" => "amount of payment",
                        "defaultValue" => "sisa",
                        "keyupAction" => "",
                        "hideRow" => "true",
                        //                        "keyupAction" => "if(parseFloat(removeCommas(this.value))>parseFloat(removeCommas(document.getElementById('amount').value)) || parseFloat(removeCommas(this.value))<0){this.value=document.getElementById('amount').value;}",
                        //                        "keyupAction" => "if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('payment_out').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('payment_out').value;}",
                        //                        'disabled'     => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "non_pph" => array(
                        "label" => "non pph",
                        "defaultValue" => ".0",
                        "keyupAction" => "if(parseFloat(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('extern_nilai2_1').innerHTML) || parseFloat(removeCommas(this.value))<0){this.value=addCommas(document.getElementById('extern_nilai2_1').innerHTML);}",
                        //                        "keyupAction" => "if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('payment_out').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('payment_out').value;}",
                        //                        'disabled'     => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "valid_dpp" => array(
                        "label" => "DPP PPh 23",
                        "defaultValue" => "valid_dpp",
                        "maxValue" => "valid_dpp",
                        "minValue" => "valid_dpp",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "pph23_nilai" => array(
                        "label" => "PPh 23",
                        "defaultValue" => "pph23_nilai",
                        "maxValue" => "pph23_nilai",
                        "minValue" => "pph23_nilai",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "uang_muka_dipakai" => array(
                        "label" => "(deposit)",
                        "defaultValue" => "0",
                        "maxValue" => "uang_muka_dipakai",
                        "minValue" => "uang_muka_dipakai",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "payment_out" => array(
                        "label" => "cash payout",
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
        "shoppingCartReferenceFields" => array(
            "nomer" => "receipt number",
            "nomer_top" => "receipt ref.",
            "refNum" => "return ref.",
            "fulldate" => "date",
            "tagihan" => "due amount",
            "refValue" => "returned",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
            "notes" => "description",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_id" => "vendor id",
            "extern_nama" => "vendor",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
        ),

        "resumeFieldNames" => array(
            "selectFields" => "suppliers_nama",
            "title" => "vendor",
        ),
        "ppnDisabled" => array(
            "enabled" => true,
            "notes" => "PPN masukan belum diapprove oleh Finance.",
        ),
        "installment" => array(
            "enable" => false,
            "sourceValue" => "harus_bayar",
            "formID" => "nilai_entry"
        ),
        "componentsAss" => array(
            "model" => "MdlTransaksi",
            "modelSrc" => "MdlNotaItem",
        ),
        "pairRecomDataElement" => array(
            "pph23Method" => array(
                "mdlname" => "MdlPph23MethodPotongan",
                "gateId" => "pphGateId",
                "target" => array(
                    "main" => "biayaJasa",
                ),
            ),

        ),
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di  {cabang_nama}",
        ),
    ),
    //config A/P payment import
    "4891" => array(
        "icon" => "fa fa-money",
        "label" => "FG A/P payment import",
        "paymentConfig" => true,
        "place" => "center",
        "steps" => array(
            //            1 => array(
            //                "label"       => "pembayaran hutang",
            //                "actionLabel" => "pembayaran hutang",
            //                "source"      => "",
            //                "target"      => "489r",
            //                "userGroup"   => "sys",
            //                "stateLabel"  => "ready to be paid",
            //                "stateColor"  => "#dd3300",
            //            ),
            1 => array(
                "label" => "account payable payment",
                "actionLabel" => "process payment",
                "source" => "",
                "target" => "4891",
                "userGroup" => "c_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "PT. Everest Electronic",
                "stateFooter" => "paid by",
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.467",
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
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "details" => "detail",
            "oleh_nama" => "person",
            "cash_account__label" => "account",
            "sisa" => "amount",
            "additionalFactor" => "keuntungan/kerugian kurs",
            "nilai_bayar" => "paid",
            "new_sisa" => "remain amount",
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
                "valas_nama" => "exchange",
                "extern_nilai2" => "exchange rate",
                "jml" => "qty",
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

            "creditValue" => "diskon",
            "valas_nama" => "valas_nama",
            "extern_nilai2" => "extern_nilai2",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "extern_nilai2" => "extern_nilai2",
                "sisa_valas" => "sisa (valas)",
                "sisa" => "sisa (IDR)",
            ),

        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "sisa_valas" => "due amount",
                "sisa" => "due amount (IDR)",
            ),

        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartAvoidRemove" => true,
        "tagihanSrc" => "harus_bayar",
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
            //            "cashMethodeOption" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "Metode rekening",
            //                "mdlName" => "MdlCashMethodeStatic",
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "usedFields" => array(
            //                    "nama" => "method",
            //                ),
            //                "editPoints" => array(1,),
            //                "noValidate" => true,
            //                "noPrefetch" => true,
            //            ),
            //            "kurs_actual" => array(
            //                "elementType" => "dataField",
            //                "label" => "kurs saat ini",
            //                "inputType" => "number",
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "usedFields" => array(
            //                    "nama" => "",
            //                ),
            //                "defaultValue" => "0",
            //                "noPrefetch" => true,
            //                "noValidate" => true,
            //                //                "editPoints" => array(1),
            //            ),
            "additional" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "exchange rate ",
                "mdlName" => "MdlStaticPayment",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "additional jenis",
                    //                            "currency" => "currency",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
            ),

            "creditAmount" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "credit note",
                "mdlName" => "MdlPaymentAntiSource",
                "mdlFilter" => array(
                    "extern_id=pihakID",
                    "cabang_id=cabangID",
                    "target_jenis=.4891",
                    "label=.piutang pembelian",
                    "sisa_valas>.0",
                    "valas_id=valasDetails",
                    "trash=.0",
                ),
                "key" => "sisa_valas",
                "labelSrc" => "valas_nama/sisa_valas",
                "usedFields" => array(
                    "extern_nama" => "vendor name",
                    "sisa_valas" => "avail credit",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
                "noPrefetch" => true,
                "pairMethod" => array(
                    "recom" => "ReComCreditNote",
                    "calculate" => array(
                        "source" => "creditNote",
                        "target" => "credit_note_dipakai",
                        "pair_source" => "sisa_valas",//sunbe sumber yang dibandingkan
                    ),
                ),
            ),

            "uangMukaValas" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Uang Muka Valas",
                "mdlName" => "MdlSupplierCreditUangMukaValas",
                "mdlFilter" => array(
                    "extern_id=pihakID",
                    "extern2_id=valas_id",
                    "cabang_id=cabangID",
                    "sisa>.0",
                ),
                "key" => "sisa",
                "labelSrc" => "extern2_nama/sisa_valas",
                "usedFields" => array(
                    "extern_nama" => "vendor",
                    "extern_id" => "pihakID",
                    "extern2_id" => "account ID",
                    "extern2_nama" => "account Name",
                    "sisa_valas" => "saldo valas",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
                //                "noPrefetch" => true,
                //                        "pairMethod" => array(
                //                            "recom" => "ReComUangMuka",
                //                            "calculate" => array(
                //                                "source" => "uangMuka",
                //                                "target" => "uang_muka_dipakai",
                //                                "pair_source" => "nilai_sisa",//sunbe sumber yang dibandingkan
                //                            ),
                //                        ),
            ),
            "valas_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "stock valas",
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
                    "id=valas_id",
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
                //                "noPrefetch" => true,
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
            //            "cashMethodeOption" => array(
            //                "cash" => array(
            //
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
            //                    "additional" => array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "radio",
            //                        "label" => "exchange rate ",
            //                        "mdlName" => "MdlStaticPayment",
            //                        "mdlFilter" => array(),
            //                        "key" => "id",
            //                        "labelSrc" => "name",
            //                        "usedFields" => array(
            //                            "name" => "additional jenis",
            //                            //                            "currency" => "currency",
            //                        ),
            //                        "editPoints" => array(1,),
            //                    ),
            //
            //                    "kurs_actual" => array(
            //                        "elementType" => "dataField",
            //                        "label" => "kurs saat ini",
            //                        "inputType" => "number",
            //                        "key" => "id",
            //                        "labelSrc" => "nama",
            //                        "usedFields" => array(
            //                            "nama" => "",
            //                        ),
            ////                        "defaultValue" => "0",
            //                        //                "editPoints" => array(1),
            //                    ),
            //                ),
            //                "valas" => array(
            //                    "uangMukaValas" => array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "radio",
            //                        "label" => "Uang Muka Valas",
            //                        "mdlName" => "MdlSupplierCreditUangMukaValas",
            //                        "mdlFilter" => array(
            //                            "extern_id=pihakID",
            //                            "extern2_id=valas_id",
            //                            "cabang_id=cabangID",
            //                            "sisa>.0",
            //                        ),
            //                        "key" => "sisa",
            //                        "labelSrc" => "sisa_valas",
            //                        "usedFields" => array(
            //                            "extern_nama" => "vendor",
            //                            "extern_id" => "pihakID",
            //                            "extern2_id" => "account ID",
            //                            "extern2_nama" => "account Name",
            //                            "sisa_valas" => "saldo valas",
            //                        ),
            //                        "editPoints" => array(1,),
            //                        "noValidate" => true,
            //                        "noPrefetch" => true,
            ////                        "pairMethod" => array(
            ////                            "recom" => "ReComUangMuka",
            ////                            "calculate" => array(
            ////                                "source" => "uangMuka",
            ////                                "target" => "uang_muka_dipakai",
            ////                                "pair_source" => "nilai_sisa",//sunbe sumber yang dibandingkan
            ////                            ),
            ////                        ),
            //                    ),
            //
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
            //                            "id=valas_id",
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
        "shoppingCartUnionValidators" => array(
            //            array(
            //                //sementara dimatikan karena masih selalu validate..............
            ////                "credit_note_dipakai" => "credit note",
            ////                "uang_muka_dipakai" => "deposit",
            ////                "nilai_entry" => "payment",//sementara untuk lolosin bayar pakai keuntungan kurs
            //            ),
        ),
        "shopingCartAddValidator" => array(
            //            "additional" => array(
            //                "1" => "add_diskon",
            //                "-1" => "add_diskon",
            //            ),
        ),
        "shopingCartUnionComparison" => array(
            //            array(
            //                "nilai_entry" => "payment belum diisi",
            //                "cash_account__saldo" => "cash account belum dipilih",
            //            ),
        ),
        //--------------------------------------------
        // bila nilai key lebih dari 0, value harus ada dan lebih dari 0
        "shoppingCartPaymentValidator" => array(
            "validate" => array(
                "uang_muka_valas_dipakai" => "uangMukaValas__extern2_id",
                "valas_nilai_stock" => "valas_account",
                "nilai_entry" => "kurs_actual",
            ),
            "label" => array(
                "uang_muka_valas_dipakai" => "Account Uang Muka Valas belum dipilih. Silahkan dipilih dahulu.",
                "valas_nilai_stock" => "Account Stock Valas belum dipilih. Silahkan dipilih dahulu.",
                "nilai_entry" => "Nilai Kurs saat ini belum diisi. Silahkan diisi dahulu.",
            ),
        ),
        "shoppingCartPaymentValueValidator" => array(
            // bila nilai key lebih besar dari val, maka berhenti [main]
            "validate" => array(
                "valas_nilai_bayar" => "sisa_valas",
            ),
            "label" => array(
                "valas_nilai_bayar" => "Jumlah pembayaran lebih besar dari akumulasi nota terpilih. Silahkan dikoreksi dahulu.",
            ),
        ),
        //--------------------------------------------
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(

                    "uang_muka_valas_dipakai" => array(
                        "label" => "uang muka valas",
                        "defaultValue" => ".0",
                        "keyupAction" => "
    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('uangMukaValas__sisa_valas').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('uangMukaValas__sisa_valas').value;}
                            ",
                        //                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),

                    "credit_note_dipakai" => array(
                        "label" => "credit note valas",
                        "defaultValue" => "credit_note_dipakai",
                        "maxValue" => "credit_note_dipakai",
                        "minValue" => "credit_note_dipakai",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),

                    "valas_nilai_stock" => array(
                        "label" => "stock valas",
                        "defaultValue" => ".0",
                        "keyupAction" => "
    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('valas_harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('valas_harus_bayar').value;}
                            ",
                        //                        'disabled'     => "disabled",
                        "addPoints" => array(1,),
                    ),
                    // kekurangan valas---------------------------
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
                    //--------------------------------------------
                    // kurs actual
                    "kurs_actual" => array(
                        "label" => "kurs beli",
                        "defaultValue" => "kurs_actual",
                        "maxValue" => "",
                        "minValue" => "",
                        "keyPressAction" => "
    if(parseInt(removeCommas(this.value))<0){this.value=document.getElementById('kurs_actual').value;}
                        ",
                        //                        'disabled' => "disabled",
                        "addPoints" => array(1,),

                    ),

                    "additional_value" => array(
                        "label" => "exchange rate difference (idr)",
                        "defaultValue" => ".0",
                        "keyupAction" => "
                            if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('additional_value').value)) || parseInt(removeCommas(this.value))<0){

                            }",
                        //                        'disabled'     => "disabled",
                        "addPoints" => array(1,),
                    ),

                    "nilai_entry" => array(
                        "label" => "payment (IDR)",
                        "defaultValue" => ".0",
                        "keyupAction" => "
    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harus_bayar').value;}
                            ",
                        //                        'disabled'     => "disabled",
                        "addPoints" => array(1,),
                    ),

                    "harus_bayar" => array(
                        "label" => "balance of invoice",
                        "defaultValue" => "harus_bayar",
                        "maxValue" => "harus_bayar",
                        "minValue" => "harus_bayar",
                        "hideRow" => true,
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),

                    "valas_new_sisa" => array(
                        "label" => "balance of invoice (valas)",
                        "defaultValue" => "valas_new_sisa",
                        "maxValue" => "valas_new_sisa",
                        "minValue" => "valas_new_sisa",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),

                    "new_sisa_ui" => array(
                        "label" => "balance of invoice (idr)",
                        "defaultValue" => "new_sisa_ui",
                        "maxValue" => "new_sisa_ui",
                        "minValue" => "new_sisa_ui",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),

                    "new_sisa" => array(
                        "label" => "balance of invoice (idr)",
                        "defaultValue" => "new_sisa",
                        "maxValue" => "new_sisa",
                        "minValue" => "new_sisa",
                        "hideRow" => true,
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    //---------------------------
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
                ),
            ),
        ),
        "shoppingCartReferenceFields" => array(
            "nomer" => "receipt number",
            "nomer_top" => "receipt ref.",
            //            "refNum" => "return ref.",
            "fulldate" => "date",
            "valas_nama" => "exchange",
            "extern_nilai2" => "exchange rate ",
            "tagihan" => "due amount",
            //            "refValue" => "returned",
            "terbayar" => "paid",
            //            "diskon" => "discount",
            "sisa" => "due remain",

            "tagihan_valas" => "due amount (valas)",
            "terbayar_valas" => "paid (valas)",
            //            "diskon_valas" => "discount (valas)",
            "sisa_valas" => "due remain (valas)",

            "notes" => "description",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "vendor",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "sisa" => "due remain",
            "tagihan_valas" => "due amount (valas)",
            "terbayar_valas" => "paid (valas)",
            "sisa_valas" => "due remain (valas)",
        ),
        "resumeFieldNames" => array(
            "selectFields" => "suppliers_nama",
            "title" => "vendor",
        ),
        "ppnDisabled" => array(
            "enabled" => true,
            "notes" => "PPN masukan belum diapprove oleh Finance.",
        ),
        "gateExchange" => array(
            array(
                "enabled" => false,
                "source" => "extern_nilai2",
                "postfix" => "exchange",
                "blacklist" => array(
                    "exchange", "sub_exchange", "newExchange", "sub_newExchange", // selalu ditambah __
                ),
            ),
            //            array(
            //                "enabled" => true,
            //                "source" => "kurs__exchange",
            //                "postfix" => "newExchange",
            //                "blacklist" => array(
            //                    "exchange", "sub_exchange", "newExchange", "sub_newExchange", // selalu ditambah __
            //                ),
            //            ),
        ),
        "exchangeValidate" => array( // validasi untuk gerbang items
            "enabled" => true,
            //            "key" => array(
            //                // source => target (items)
            //                "valas_id" => "valas_id",
            //            ),
            "label" => "mata uang asing terdeteksi tidak sama, silahkan pilih yang sesuai.",
        ),
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di  {cabang_nama}",
        ),
    ),
    // config payment UM sewa
    "1424" => array(
        "icon" => "fa fa-money",
        "label" => "sewa A/P payment(pusat)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "sewa a/p payment",
                "actionLabel" => "process payment sewa",
                "source" => "",
                "target" => "1424",
                "userGroup" => "c_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
            ),
        ),
        "paymentConfig" => true, //pengenal bahwa ini transaksi payment/receive
        "isPaymentRadioSelect" => true, // hanya pilih satu saat pembayaran
        "isDisableMakeTrans" => true, // tidak bisa bikin new transaksi, hanya bisa followup yg ada
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.1425",
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
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
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
        "shoppingCartFields2" => array(
            1 => array(
                "nama" => "item source name",
                "jml" => "qty",
                "harga" => "price",
                //                "ppn" => "ppn",
                //                "harga" => "sub-total",
            ),
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
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            "extern_nilai2" => "extern_nilai2",
            "ppn" => "ppn",
            "extern_jenis" => "extern_jenis",
            "extern2_nama" => "extern2_nama",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "extern_nilai2" => "DPP PPH",
                "ppn" => "PPN",
                //                "sisa" => "sisa",
            ),

        ),
        "shoppingCartSumFields" => array(
            1 => array(
                //                "ppn" => "ppn",
                "pph_value" => "dipotong pph",
                "sisa" => "due amount",
                //                "non_pph" =>"non pph23",
                //                "valid_dpp" =>"dpp pph23",
                //                "pph23_nilai" => "pph 23",
                //
                //                "creditAmount" => "supplier credit amount",
                ////                "harus_bayar" => "amount remains to pay",
                //                "payment_out" => "amount of payment",
                //
                //                "new_sisa" => "remain to pay (from list)",
            ),

        ),
        "shoppingCartEditableFields" => array(
            //            "harga",
            //            "ppn",
            //"jml",
        ),
        "shoppingCartAmountValue" => array(
            1 => "extern_nilai2+ppn",
        ),
        "shoppingCartAvoidRemove" => true,
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
            "creditAmount" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "credit note",
                "mdlName" => "MdlPaymentAntiSource",
                "mdlFilter" => array(
                    "extern_id=pihakID",
                    "cabang_id=cabangID",
                    //                    "target_jenis=jenisTr",
                    "label=.piutang pembelian",
                    "sisa>.0",
                ),
                "key" => "sisa",
                "labelSrc" => "sisa",
                "usedFields" => array(
                    "extern_nama" => "vendor name",
                    //                    "transaksi_id" => "return ID",
                    //                    "nomer" => "return number",
                    "sisa" => "avail credit",
                    //                    "jenis" => "jenis",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
                "noPrefetch" => true,
                "pairMethod" => array(
                    "recom" => "ReComCreditNote",
                    "calculate" => array(
                        "source" => "creditNote",
                        "target" => "credit_note_dipakai",
                        "pair_source" => "nilai_sisa",//sunbe sumber yang dibandingkan
                    ),

                    //                    "customer" => "ReComDiscCustomer",
                ),
            ),
            "uangMuka" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "credit from uang muka",
                "mdlName" => "MdlSupplierCreditUangMuka",
                "mdlFilter" => array(
                    "extern_label2=.vendor",
                    "extern_id=pihakID",
                    "cabang_id=cabangID",
                    "sisa>.0",
                ),
                // "key" => "sisa",
                "key" => "id",
                "labelSrc" => "sisa",
                "usedFields" => array(
                    "extern_nama" => "vendor",
                    "extern_id" => "vendorID",
                    "sisa" => "balance",
                    "extern_label2" => "tipe",

                    // "id"=>"idc",
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
                        "source" => "uangMuka__sisa",
                        "target" => "uang_muka_dipakai",
                        "pair_source" => "nilai_sisa",//sunbe sumber yang dibandingkan
                    ),

                    //                    "customer" => "ReComDiscCustomer",
                ),
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

            "pph23Method" => array(
                "1" => array(
                    "pph23Method__desc" => array(
                        "elementType" => "dataField",
                        "label" => "SKB",
                        "inputType" => "text",
                        "defaultValue" => "",
                        "editPoints" => array(1),
                    ),
                ),
            ),
            //            "cashMethode" => array(
            //                "reguler" => array(
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
            //            "branchTarget" =>array(
            //                "-1" =>array(
            //                    "externMain"=>array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "radio",
            //                        "label" => "transfer expense to",
            //                        "mdlName" => "MdlBiayaMethodGeneral",
            //                        "mdlFilter" => array(),
            //                        "key" => "nama",
            //                        "labelSrc" => "nama",
            //                        "description" => "",
            //                        "usedFields" => array(
            //                            "nama" => "kategori biaya",
            //                        ),
            //                        "editPoints" => array(1,),
            //                        "noValidate" => true,
            //                    ),
            //                ),
            //            ),
            //            "externMain" =>array(
            //                "biaya umum" =>array(
            //                    "dtaDetail" =>array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "combo",
            //                        "label" => "expense details",
            //                        "mdlName" => "MdlDtaBiayaUmum",
            //                        "mdlFilter" => array(),
            //                        "key" => "id",
            //                        "labelSrc" => "nama",
            //                        "description" => "",
            //                        "usedFields" => array(
            //                            "nama" => "beban biaya",
            //                        ),
            //                        "targetMethod" => array(
            ////                            "biaya usaha" => "ReComBiayaUsaha_payment",
            //                            "biaya umum" => "ReComBiayaUmum_payment",
            ////                            "non_npwp" => "ReComPph23NonNpwp_purchasing",
            ////                            "none" => "ReComPph23None_purchasing",
            //                        ),
            //                        "editPoints" => array(1,),
            //                        "noValidate" => true,
            //                    ),
            //                ),
            //                "biaya usaha" =>array(
            //                    "dtaDetail" =>array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "combo",
            //                        "label" => "expense details",
            //                        "mdlName" => "MdlDtaBiayaUsaha",
            //                        "mdlFilter" => array(),
            //                        "key" => "id",
            //                        "labelSrc" => "nama",
            //                        "description" => "",
            //                        "usedFields" => array(
            //                            "nama" => "beban biaya",
            //                        ),
            //                        "targetMethod" => array(
            //                            "biaya usaha" => "ReComBiayaUsaha_payment",
            ////                            "biaya umum" => "ReComBiayaUmum_payment",
            ////                            "non_npwp" => "ReComPph23NonNpwp_purchasing",
            ////                            "none" => "ReComPph23None_purchasing",
            //                        ),
            //                        "editPoints" => array(1,),
            //                        "noValidate" => true,
            //                    ),
            //
            //                ),
            //                "biaya produksi" =>array(
            //                    "dtaDetail" =>array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "combo",
            //                        "label" => "expense details",
            //                        "mdlName" => "MdlDtaBiayaProduksi",
            //                        "mdlFilter" => array(),
            //                        "key" => "id",
            //                        "labelSrc" => "nama",
            //                        "description" => "",
            //                        "usedFields" => array(
            //                            "nama" => "beban biaya",
            //                        ),
            //                        "editPoints" => array(1,),
            //                        "noValidate" => true,
            //                    ),
            //
            //                ),
            //            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_kode" => "part number",
                //                "produk_ord_jml" => "qty",
                "satuan" => "uom",
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
        "shoppingCartRowValidators" => array(
            "pihakID" => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartRowNumValidators" => array(
            "nilai_entry" => "amount of payment",
        ),
        "shoppingCartUnionValidators" => array(
            array(
                "credit_note_dipakai" => "credit amount",
                "payment_out" => "amount value",
                "uang_muka_dipakai" => "amount value",
            ),
        ),
        "shopingCartUnionComparison" => array(
            array(
                //                "payment_out" =>"payment belum diisi",
                //                "cash_account__saldo" =>"cash account belum dipilih",
            ),

        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(

                    "credit_note_dipakai" => array(
                        "label" => "credit note",
                        "defaultValue" => "credit_note_dipakai",
                        "maxValue" => "credit_note_dipakai",
                        "minValue" => "credit_note_dipakai",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "uang_muka_dipakai" => array(
                        "label" => "(Deposit)",
                        "defaultValue" => "0",
                        "maxValue" => "uang_muka_dipakai",
                        "minValue" => "uang_muka_dipakai",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "nilai_entry" => array(
                        "label" => "payment",
                        "defaultValue" => "sisa",
                        "keyupAction" => "",
                        "disabled" => "disabled",
                        //                        "hideRow" => "true",
                        "addPoints" => array(1,),
                    ),
                    "new_sisa" => array(
                        "label" => "balance of invoice",
                        "defaultValue" => "new_sisa",
                        "maxValue" => "new_sisa",
                        "minValue" => "new_sisa",
                        //                        "keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(removeCommas(document.getElementById('bayar').value)-parseFloat(gt))",
                        //                        "keyupAction" => "var gt=this.min,bayar=this.value,kembali=document.getElementById('kembali'); kembali.value=parseFloat(bayar)-parseFloat(gt);if(parseFloat(bayar)<parseFloat(gt)){kembali.style.color='red',kembali.style.fontWeight='700'}else{kembali.style.color='green',kembali.style.fontWeight='700'}",
                        //                        "hideRow" => "true",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    //                    "non_pph" => array(
                    //                        "label" => "non pph",
                    //                        "defaultValue" => ".0",
                    //                        "keyupAction" => "if(parseFloat(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('extern_nilai2_1').innerHTML) || parseFloat(removeCommas(this.value))<0){this.value=document.getElementById('extern_nilai2_1').innerHTML;}",
                    ////                        "keyupAction" => "if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('payment_out').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('payment_out').value;}",
                    //                        //                        'disabled'     => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    //                    "pph23_nilai" => array(
                    //                        "label" => "(PPh 23)",
                    //                        "defaultValue" => "pph23_nilai",
                    //                        "maxValue" => "pph23_nilai",
                    //                        "minValue" => "pph23_nilai",
                    //                        "keyPressAction" => "",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),

                    //                    "payment_out" => array(
                    //                        "label" => "amount to be paid",
                    //                        "defaultValue" => "payment_out",
                    //                        "maxValue" => "payment_out",
                    //                        "minValue" => "payment_out",
                    //                        "keyPressAction" => "",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                ),
            ),
        ),
        "shoppingCartReferenceFields" => array(
            "nomer" => "receipt number",
            "nomer_top" => "receipt ref.",
            "refNum" => "return ref.",
            "fulldate" => "date",
            "tagihan" => "due amount",
            "refValue" => "returned",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
            "notes" => "description",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "vendor",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "resumeFieldNames" => array(
            "selectFields" => "suppliers_nama",
            "title" => "vendor",
        ),
        "ppnDisabled" => array(
            "enabled" => true,
            "notes" => "PPN masukan belum diapprove oleh Finance.",
        ),
        "installment" => array(
            "enable" => false,
            "sourceValue" => "harus_bayar",
            "formID" => "nilai_entry"
        ),
        "componentsAss" => array(
            "model" => "MdlTransaksi",
            "modelSrc" => "MdlNotaItem",
        ),
        "shoppingCartPairedItem" => array(
            "targetGateName" => "items2_sum",
        ),
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di  {cabang_nama}",
        ),
    ),
    //config pembayaran service projek
    "483" => array(
        "icon" => "fa fa-money",
        "label" => "service Projek A/P payment",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "account payable payment",
                "actionLabel" => "process payment",
                "source" => "",
                "target" => "483",
                "userGroup" => "c_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
            ),
        ),
        "paymentConfig" => true,
        "isPaymentRadioSelect" => true,
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.463",
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
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "details" => "detail",
            "oleh_nama" => "person",
            "cash_account__label" => "account",
            "sisa" => "amount",
            //            "additionalFactor" => "keuntungan/kerugian kurs",
            "pph23_nilai" => "pph 23",
            "uang_muka_dipakai" => "deposit",
            "pay_out" => "paid",
            "nilai_bayar" => "total paid",
            "pphGateLabel" => "status pph 23",
            "keterangan" => "keterangan",
            "print_label" => "tool",
            //            "new_sisa" => "remain amount",
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
                // "review_details" => "id",
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
        "shopingCartReload" => "true",
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "extern2_nama" => "metode pph23",
                "jml" => "qty",
            ),

        ),
        "shoppingCartFields2" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",
                "harga" => "price",
                "ppn" => "PPN",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nomer",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            "extern_nilai2" => "extern_nilai2",
            "extern_nilai3" => "extern_nilai3",
            "ppn" => "ppn",
            "extern2_id" => "extern2_id",
            "extern2_nama" => "extern2_nama",
            //----------
            "dpp_ppn_persen" => "dpp_ppn_persen",
            "dpp_pph_persen" => "dpp_pph_persen",
            "pph" => "pph",
            "harga_disc" => "harga_disc",
            "dppPPh" => "dppPPh",
            "pph_nilai" => "pph_nilai",
            "dppPPn" => "dppPPn",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "extern_nilai5" => "DPP pph21",
                "extern_nilai2" => "DPP pph23",
                "extern_nilai3" => "DPP ppn",
                "ppn" => "ppn",
                //                "sisa" => "sisa",
            ),

        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "ppn" => "ppn",
                "sisa" => "due amount",
                //                "non_pph" =>"non pph23",
                //                "valid_dpp" =>"dpp pph23",
                //                "pph23_nilai" => "pph 23",
                //
                //                "creditAmount" => "supplier credit amount",
                ////                "harus_bayar" => "amount remains to pay",
                //                "payment_out" => "amount of payment",
                //
                //                "new_sisa" => "remain to pay (from list)",
            ),

        ),
        "shoppingCartEditableFields" => array(
            //            "harga",
            //            "ppn",
            //"jml",
        ),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartAvoidRemove" => true,
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
                    "npwp" => "npwp",
                    "alamat_1" => "address",
                    "tlp_1" => "phone",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            "pajakOption" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "pph 21 / pph 23 option",
                "mdlName" => "MdlPajakPPhOption",
//                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",
                ),
                "editPoints" => array(1, 2, 3),
            ),

//            "pph23Method" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "method of pph 23",
//                "mdlName" => "MdlPph23Method",
//                "key" => "id",
//                //                "defaultValue" => "item",
//                "labelSrc" => "name",
//                //dipanggil di _shopingCart, dimatikan dulu
//                //                "autoFilter" => array(
//                //                    "key" => "pairPihakName",
//                //                    "srcRef" => array(
//                //                        "mdl" => "MdlSupplier",
//                //                        "filter" => "pihakID",
//                //                        "srcField" => "npwp",
//                //                    ),
//                //                    "pairKey" => array(
//                ////                        "validate" => "npwp",
//                //                        "methode" => array(
//                //                            "dipotong" => array(
//                //                                "true" => "npwp",
//                //                                "false" => "non_npwp",
//                //                            ),
//                //                            "tidak dipotong" => array(
//                //                                "true" => "sket",
//                //                                "false" => "non_npwp",
//                //                            ),
//                //
//                //                        ),
//                //                    ),
//                //                ),//pasang di _shopingCart
//
//                "usedFields" => array(
//                    "name" => "method",
//                    "tarif" => "tarif (%)",
//                ),
//                "editPoints" => array(1,),
//
//                "targetMethod" => array(
//                    "npwp" => "ReComPph23Npwp_purchasing",
//                    "non_npwp" => "ReComPph23NonNpwp_purchasing",
//                    "sket" => "ReComPph23None_purchasing",
//                ),
//            ),
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

            "uangMuka" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "credit from uang muka",
                "mdlName" => "MdlSupplierCreditUangMuka",
                "mdlFilter" => array(
                    "extern_id=pihakID",
                    "cabang_id=cabangID",
                    "extern_label2=.vendor",
                    "sisa>.0"
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
                        "source" => "uangMuka",
                        "target" => "uang_muka_dipakai",
                        "pair_source" => "sisa",//sunbe sumber yang dibandingkan /// nilai_sisa
                    ),

                    //                    "customer" => "ReComDiscCustomer",
                ),
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
            "pajakOption" => array(
                "pph21" => array(
                    "pph21Method" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "method of pph 21",
                        "mdlName" => "MdlPph21Method",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "method",
                            "tarif" => "tarif (%)",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            "npwp" => "ReComPph21Npwp_purchasing",
                            "non_npwp" => "ReComPph21NonNpwp_purchasing",
                            "sket" => "ReComPph21None_purchasing",
                        ),
                    ),
                ),
                "pph23" => array(
                    "pph23Method" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "method of pph 23",
                        "mdlName" => "MdlPph23Method",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "method",
                            "tarif" => "tarif (%)",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            "npwp" => "ReComPph23Npwp_purchasing",
                            "non_npwp" => "ReComPph23NonNpwp_purchasing",
                            "sket" => "ReComPph23None_purchasing",
                        ),
                    ),
                ),
            ),
            "pph23Method" => array(
                "sket" => array(
                    "pph23Method__desc" => array(
                        "elementType" => "dataField",
                        "label" => "SKB/SKET",
                        "inputType" => "text",
                        "defaultValue" => "",
                        "editPoints" => array(1),
                    ),
                ),
            ),
            "pph21Method" => array(
                "sket" => array(
                    "pph21Method__desc" => array(
                        "elementType" => "dataField",
                        "label" => "SKB/SKET",
                        "inputType" => "text",
                        "defaultValue" => "",
                        "editPoints" => array(1),
                    ),
                ),
            ),

//            "branchTarget" => array(
//                "1" => array(
//                    "externMain" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "transfer expense to",
//                        "mdlName" => "MdlBiayaMethodSales",
//                        "mdlFilter" => array(),
//                        "key" => "id",
//                        "labelSrc" => "nama",
//                        "description" => "",
//                        "usedFields" => array(
//                            "nama" => "kategori biaya",
//                        ),
//
//                        "editPoints" => array(1,),
//                        "noValidate" => true,
//
//                    ),
//                ),
//                "25" => array(
//                    "externMain" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "transfer expense to",
//                        //                        "mdlName" => "MdlBiayaMethodProduksi",
//                        "mdlName" => "MdlProdukRakitanPreBiaya",
//                        "mdlFilter" => array(),
//                        "key" => "id",
//                        "labelSrc" => "nama",
//                        "description" => "",
//                        "usedFields" => array(
//                            "nama" => "kategori biaya",
//                        ),
//                        "editPoints" => array(1,),
//                        "noValidate" => true,
//                        "targetMethod2" => array(
//                            "biaya_umum" => "ReComBiayaProduksi_payment",
//                        ),
//                    ),
//                ),
//                "21" => array(
//                    "externMain" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "transfer expense to",
//                        "mdlName" => "MdlBiayaMethodSales",
//                        "mdlFilter" => array(),
//                        "key" => "id",
//                        "labelSrc" => "nama",
//                        "description" => "",
//                        "usedFields" => array(
//                            "nama" => "kategori biaya",
//                        ),
//                        "editPoints" => array(1,),
//                        "noValidate" => true,
//                        "targetMethod2" => array(
//                            "biaya_umum" => "ReComBiayaUsaha_payment",
//                            "biaya_usaha" => "ReComBiayaUmum_payment",
//                        ),
//                    ),
//                ),
//            ),
//            "externMain" => array(
//                "biaya_umum" => array(
//                    "dtaDetail" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "combo",
//                        "label" => "expense details",
//                        "mdlName" => "MdlDtaBiayaUmum",
//                        "mdlFilter" => array(),
//                        "key" => "id",
//                        "labelSrc" => "nama",
//                        "description" => "",
//                        "usedFields" => array(
//                            "nama" => "beban biaya",
//                        ),
//                        "editPoints" => array(1,),
//                        "noValidate" => true,
//                        "targetMethod2" => array(
//                            "biaya_umum" => "ReComBiayaUmum_payment",
//                        ),
//
//                    ),
//                ),
//                "biaya_usaha" => array(
//                    "dtaDetail" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "combo",
//                        "label" => "expense details",
//                        "mdlName" => "MdlDtaBiayaUsaha",
//                        "mdlFilter" => array(),
//                        "key" => "id",
//                        "labelSrc" => "nama",
//                        "description" => "",
//                        "usedFields" => array(
//                            "nama" => "beban biaya",
//                        ),
//                        "editPoints" => array(1,),
//                        "noValidate" => true,
//                        "targetMethod2" => array(
//                            "biaya_usaha" => "ReComBiayaUsaha_payment",
//                        ),
//                    ),
//
//                ),
//                "1" => array(
//                    "dtaDetail" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "combo",
//                        "label" => "expense details",
//                        "mdlName" => "MdlDtaBiayaProduksi",
//                        "mdlFilter" => array(//                            "pre_biaya_id=externMain",
//                        ),
//                        "key" => "id",
//                        "labelSrc" => "nama",
//                        "description" => "",
//                        "usedFields" => array(
//                            "nama" => "beban biaya",
//                        ),
//                        "editPoints" => array(1,),
//                        "noValidate" => true,
//                        "targetMethod2" => array(
//                            1 => "ReComBiayaProduksi_payment"
//                        ),
//                    ),
//                ),
//                "2" => array(
//                    "dtaDetail" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "combo",
//                        "label" => "expense details",
//                        "mdlName" => "MdlDtaBiayaProduksi",
//                        "mdlFilter" => array(),
//                        "key" => "id",
//                        "labelSrc" => "nama",
//                        "description" => "",
//                        "usedFields" => array(
//                            "nama" => "beban biaya",
//                        ),
//                        "editPoints" => array(1,),
//                        "noValidate" => true,
//                        "targetMethod2" => array(
//                            2 => "ReComBiayaProduksi_payment"
//                        ),
//
//                    ),
//                ),
//                "4" => array(
//                    "dtaDetail" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "combo",
//                        "label" => "expense details",
//                        "mdlName" => "MdlDtaBiayaProduksi",
//                        "mdlFilter" => array(),
//                        "key" => "id",
//                        "labelSrc" => "nama",
//                        "description" => "",
//                        "usedFields" => array(
//                            "nama" => "beban biaya",
//                        ),
//                        "editPoints" => array(1,),
//                        "noValidate" => true,
//                        "targetMethod2" => array(
//                            4 => "ReComBiayaProduksi_payment"
//                        ),
//                    ),
//
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
        "pairRegistries" => array(
            "main", "items"
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartRowNumValidators" => array(//            "nilai_entry" => "amount of payment",
        ),
        "shopingCartPaymentComparisonValidator" => array(
            array(
                "source" => "nilai_dipakai_hutang_biaya", // hutang dagang
                "target" => "nilai_bayar", // payment source
                "label" => "Pastikan penggunaan Kas, Uang Muka sudah sesuai untuk pelunasan Invoice ini.", //
            ),
        ),
        "shopingCartUnionComparison" => array(
            array(
                "payment_out" => "payment belum diisi",
                "cash_account" => "cash account belum dipilih untuk pelunasan",
            ),

        ),
        "shopingCartPaymentValueValidator" => array(
            "srcDana" => array(
                "uang_muka_dipakai",
                "kas_value",
                "rekening_koran_value",
            ),
            "srcTagihan" => array(
                "sisa",
            ),
        ),
        "shopingCartPaymentValueValidatorLabel" => "Cicilan AP Payment Jasa dimasukkan sebagai uang muka.<br>Uang muka hanya bisa dipakai saat pelunasan",
        "shoppingCartUnionValidators" => array(
            array(
                "nilai_entry" => "amount value",
                "uang_muka_dipakai" => "uang muka",
            ),
        ),

        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "creditAmount" => array(
                        "label" => "credit note",
                        "defaultValue" => "creditAmount",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    //                    "nilai_entry" => array(
                    //                        "label" => "amount of payment",
                    //                        "defaultValue" => ".0",
                    //                        "keyupAction" => "if(parseFloat(removeCommas(this.value))>parseFloat(removeCommas(document.getElementById('sisa').value)) || parseFloat(removeCommas(this.value))<0){this.value=document.getElementById('sisa').value;}",
                    ////                        "keyupAction" => "if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('payment_out').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('payment_out').value;}",
                    //                        //                        'disabled'     => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),

                    //                    "valid_dpp" => array(
                    //                        "label" => "DPP PPh 23",
                    //                        "defaultValue" => "valid_dpp",
                    //                        "maxValue" => "valid_dpp",
                    //                        "minValue" => "valid_dpp",
                    //                        "keyPressAction" => "",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    //                    "non_pph" => array(
                    //                        "label" => "non pph",
                    //                        "defaultValue" => ".0",
                    //                        "keyupAction" => "if(parseFloat(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('extern_nilai2_1').innerHTML) || parseFloat(removeCommas(this.value))<0){this.value=document.getElementById('extern_nilai2_1').innerHTML;}",
                    ////                        "keyupAction" => "if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('payment_out').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('payment_out').value;}",
                    //                        //                        'disabled'     => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),

                    "pph21_nilai" => array(
                        "label" => "(PPh 21)",
                        "defaultValue" => "pph21_nilai",
                        "maxValue" => "pph21_nilai",
                        "minValue" => "pph21_nilai",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "pph23_nilai" => array(
                        "label" => "(PPh 23)",
                        "defaultValue" => "pph23_nilai",
                        "maxValue" => "pph23_nilai",
                        "minValue" => "pph23_nilai",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "uang_muka_dipakai" => array(
                        "label" => "(deposit)",
                        "defaultValue" => "0",
                        "maxValue" => "uang_muka_dipakai",
                        "minValue" => "uang_muka_dipakai",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "payment_out" => array(
                        "label" => "amount to be paid",
                        "defaultValue" => "payment_out",
                        "maxValue" => "payment_out",
                        "minValue" => "payment_out",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "nilai_entry" => array(
                        "label" => "amount of payment",
                        "defaultValue" => "nilai_entry",

                        "maxValue" => "nilai_entry",
                        "minValue" => "nilai_entry",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        'hideRow' => "true",

                        "addPoints" => array(1,),
                    ),
                    //                    "final_sisa" => array(
                    //                        "label" => "balance of invoice",
                    //                        "defaultValue" => "final_sisa",
                    //                        "maxValue" => "final_sisa",
                    //                        "minValue" => "final_sisa",
                    //                        "keyPressAction" => "",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    //                    "sisa_uang_muka" => array(
                    //                        "label" => "balance of deposit",
                    //                        "defaultValue" => "sisa_uang_muka",
                    //                        "maxValue" => "sisa_uang_muka",
                    //                        "minValue" => "sisa_uang_muka",
                    //                        "keyPressAction" => "",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),

                ),
            ),
        ),
        "shoppingCartReferenceFields" => array(
            "nomer" => "receipt number",
            "nomer_top" => "receipt ref.",
//            "refNum" => "return ref.",
            "fulldate" => "date",
            "extern2_nama" => "method pph23",
            "tagihan" => "due amount",
//            "refValue" => "returned",
            "terbayar" => "paid",
//            "diskon" => "discount",
            "sisa" => "due remain",
            "notes" => "description",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "vendor",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "resumeFieldNames" => array(
            "selectFields" => "suppliers_nama",
            "title" => "vendor",
        ),
        "ppnDisabled" => array(
            "enabled" => true,
            "notes" => "PPN masukan belum diapprove oleh Finance.",
        ),
        "installment" => array(
            "enable" => false,
            "sourceValue" => "harus_bayar",
            "formID" => "nilai_entry"
        ),
        "componentsAss" => array(
            "model" => "MdlTransaksi",
            "modelSrc" => "MdlNotaItem",
        ),
        "shoppingCartPairedItem" => array(
            "targetGateName" => "items2_sum",
        ),
        "pairRecomDataElement" => array(
            "pph23Method" => array(
                "mdlname" => "MdlPph23MethodPotongan",
                "gateId" => "pphGateId",
                "target" => array(
                    "main" => "biayaJasa",
                ),
            ),
            "pph21Method" => array(
                "mdlname" => "MdlPph21MethodPotongan",
                "gateId" => "pphGateId",
                "target" => array(
                    "main" => "biayaJasa",
                ),
            ),

        ),
        "previewCtr" => "Create",
        //---------------
        "shoppingCartAdvanceItems" => true,
        "shoppingCartAdvanceItemsKey" => "pph",
//        "shoppingCartAdvanceItemsSelector" => "_processSelectProductException/subSelect",
//        "shoppingCartAdvanceItemsRemove" => "_processSelectProductException/subRemove",
//        "shoppingCartAdvanceItemsAdd" => "_processSelectProductException/subAdd",
//        "followupAdvanceItemsSelector" => "_followupLiveEdit/subSelect",
//        "followupAdvanceItemsRemove" => "_followupLiveEdit/subRemove",
//        "followupAdvanceItemsAdd" => "_followupLiveEdit/subAdd",
        "shoppingCartAdvanceFields" => array(
            1 => array( // ini bila ada pph 23, atau biaya/jasa
                1 => array(
                    "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
                ),
                2 => array(
                    "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
                ),
                3 => array(
                    "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
                ),
                4 => array(
                    "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
                ),
            ),
            0 => array(
                1 => array(
                    "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
                ),
                2 => array(
                    "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
                ),
                3 => array(
                    "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
                ),
                4 => array(
                    "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
                ),
            ),
        ),
        "shoppingCartAdvanceNumFields" => array(
            1 => array(
                1 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
                    "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
                    "dppPPh" => "dpp pph",
                    "pph_nilai" => "PPH(Rp)",
                ),
                2 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
                    "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
                    "dppPPh" => "dpp pph",
                    "pph_nilai" => "PPH(Rp)",
                ),
                3 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
                    "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
                    "dppPPh" => "dpp pph",
                    "pph_nilai" => "PPH(Rp)",
                ),
                4 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
                    "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
                    "dppPPh" => "dpp pph",
                    "pph_nilai" => "PPH(Rp)",
                ),
            ),
            0 => array(
                1 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
                    "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                2 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
                    "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                3 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
                    "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                4 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
                    "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
            ),
        ),
        "shoppingCartAdvanceAmountValue" => array(
            1 => array(
                1 => "jml*(harga_disc+ppn)",
                2 => "jml*(harga_disc+ppn)",
                3 => "jml*(harga_disc+ppn)",
                4 => "jml*(harga_disc+ppn)",
            ),
            0 => array(
                1 => "jml*(harga_disc+ppn)",
                2 => "jml*(harga_disc+ppn)",
                3 => "jml*(harga_disc+ppn)",
                4 => "jml*(harga_disc+ppn)",
            ),

        ),
        "shoppingCartAdvanceSubFields" => array(
            1 => array( // ini bila ada pph 23, atau biaya/jasa
                1 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                2 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                3 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                4 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
            ),
            0 => array(
                1 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                2 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                3 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                4 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
            ),
        ),
        "shoppingCartAdvanceSubNumFields" => array(
            1 => array( // ini bila ada pph 23, atau biaya/jasa
                1 => array(
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_pph_persen" => "DPP PPH 23(%)",
                    "dppPPh" => "dpp pph 23",
                    "pph" => "PPH(Rp)",
                ),
                2 => array(
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_pph_persen" => "DPP PPH 23(%)",
                    "dppPPh" => "dpp pph 23",
                    "pph" => "PPH(Rp)",
                ),
                3 => array(
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_pph_persen" => "DPP PPH 23(%)",
                    "dppPPh" => "dpp pph 23",
                    "pph" => "PPH(Rp)",
                ),
                4 => array(
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_pph_persen" => "DPP PPH 23(%)",
                    "dppPPh" => "dpp pph 23",
                    "pph" => "PPH(Rp)",
                ),
            ),
            0 => array(
                1 => array(
                    "jml" => "Qty",
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_ppn_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                2 => array(
                    "jml" => "Qty",
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_ppn_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                3 => array(
                    "jml" => "Qty",
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_ppn_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                4 => array(
                    "jml" => "Qty",
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_ppn_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
            ),
        ),
        //---------------
        "referenceGateFields" => array(
            "target" => "main",
            "fields" => array(
                "produkProjek" => "produkProjek",
                "produkProjek__nama" => "produkProjek__nama",
                "produkProjek__transaksi_id_app" => "produkProjek__transaksi_id_app",
                "produkProjek__transaksi_no_app" => "produkProjek__transaksi_no_app",
                "customerID" => "customerID",
                "customerName" => "customerName",
                "branch" => "place2ID",
                "branch__nama" => "place2Name",
            ),
        ),
    ),
    //Aset A/P Payment
    "4821" => array(
        "icon" => "fa fa-money",
        "label" => "Aset A/P payment",
        //        "place" => "branch",
        "paymentConfig" => true,
        "place" => "center",
        "steps" => array(
            //            1 => array(
            //                "label"       => "pembayaran hutang",
            //                "actionLabel" => "pembayaran hutang",
            //                "source"      => "",
            //                "target"      => "489r",
            //                "userGroup"   => "sys",
            //                "stateLabel"  => "ready to be paid",
            //                "stateColor"  => "#dd3300",
            //            ),
            1 => array(
                "label" => "account payable payment",
                "actionLabel" => "process payment",
                "source" => "",
                "target" => "4821",
                "userGroup" => "c_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "PT. Everest Electronic",
                "stateFooter" => "paid by",
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.467",
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
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            // "details" => "detail",
            "oleh_nama" => "person",
            "cash_account__label" => "account",

            "sisa" => "amount",
            "nilai_bayar" => "paid",
            "new_sisa" => "remain amount",
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
        // "extHistoryFields2" => array(
        //     1 => array(
        //         "details" => "nama",
        //     ),
        // ),
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
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            "creditValue" => "diskon",
            "extern_nilai2" => "extern_nilai2",
            "extern_nilai3" => "extern_nilai3",
            "extern_nilai4" => "extern_nilai4",
            "ppn" => "ppn",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "sisa" => "sisa",
                "extern_nilai2" => "DPP pph23*",
                "extern_nilai3" => "DPP ppn",
                "ppn" => "ppn",
                "extern_nilai4" => "Other (+)",
            ),

        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "nilai_round" => "due amount",
                //                "sisa" => "due amount",
                //                "non_pph" =>"non pph23",
                //                "valid_dpp" =>"dpp pph23",
                //                "pph23_nilai" => "pph 23",
                //
                //                "creditAmount" => "supplier credit amount",
                ////                "harus_bayar" => "amount remains to pay",
                //                "payment_out" => "amount of payment",
                //
                //                "new_sisa" => "remain to pay (from list)",
            ),

        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartAvoidRemove" => true,
        "tagihanSrc" => "sisa",
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
            //            "additional" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "additional",
            //                "mdlName" => "MdlStaticPayment",
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "labelSrc" => "name",
            //                "usedFields" => array(
            //                    "name" => "additional jenis",
            //                    //                            "currency" => "currency",
            //                ),
            //                "editPoints" => array(1,),
            //            ),
            //            "pph23Method" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "method of pph 23",
            //                "mdlName" => "MdlPph23Method",
            //                "key" => "id",
            ////                "defaultValue" => "item",
            //                "labelSrc" => "name",
            //                "usedFields" => array(
            //                    "name" => "method",
            //                    "tarif" => "tarif (%)",
            //                ),
            //                "editPoints" => array(1,),
            //                "targetMethod" => array(
            //                    "npwp" => "ReComPph23Npwp_purchasing",
            //                    "non_npwp" => "ReComPph23NonNpwp_purchasing",
            //                    "none" => "ReComPph23None_purchasing",
            //                ),
            //            ),

            //            "creditAmount" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "supplier credit amount",
            //                "mdlName" => "MdlSupplierCredit",
            //                "mdlFilter" => array(
            //                    "extern_id=pihakID",
            //                    "cabang_id=cabangID",
            //                ),
            //                "key" => "kredit",
            //                "labelSrc" => "kredit",
            //                "usedFields" => array(
            //                    "nama" => "",
            //                ),
            //                "editPoints" => array(1,),
            //                "noValidate" => true,
            //                "noPrefetch" => true,
            //            ),
            "uangMuka" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Deposit(Uang muka)",
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
                    "note" => "note",
                    "id" => "rel_id",
                    "transaksi_id" => "transaksi_id",
                    "jenis" => "jenis",
                    "extern2_id" => "label_id",
                    "extern2_nama" => "label",
                    "extern_label2" => "tipe",
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

                    //                    "customer" => "ReComDiscCustomer",
                ),
            ),
            "creditAmount" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "credit note",
                "mdlName" => "MdlPaymentAntiSource",
                "mdlFilter" => array(
                    "extern_id=pihakID",
                    "cabang_id=cabangID",
                    //                    "target_jenis=jenisTr",
                    "label=.piutang pembelian",
                    "sisa>.0",
                ),
                "key" => "sisa",
                "labelSrc" => "sisa",
                "usedFields" => array(
                    "extern_nama" => "vendor name",
                    //                    "transaksi_id" => "return ID",
                    //                    "nomer" => "return number",
                    "sisa" => "avail credit",
                    //                    "jenis" => "jenis",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
                "noPrefetch" => true,
                "pairMethod" => array(
                    "recom" => "ReComCreditNote",
                    "calculate" => array(
                        "source" => "creditNote",
                        "target" => "credit_note_dipakai",
                        "pair_source" => "sisa",//sunbe sumber yang dibandingkan
                    ),

                    //                    "customer" => "ReComDiscCustomer",
                ),
            ),
            //            "cashMethode" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "Metode rekening",
            //                "mdlName" => "MdlCashAccountStatic",
            //                "mdlFilter" => array(
            ////                    "extern_id=pihakID",
            ////                    "cabang_id=cabangID",
            ////                    "sisa>.0",
            //                ),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "usedFields" => array(
            //                    "nama" => "method",
            ////                    "extern_id" => "pihakID",
            //
            //                ),
            //                "editPoints" => array(1,),
            //                "noValidate" => true,
            //                "noPrefetch" => true,
            ////                "pairMethod" => array(
            ////                    "recom" => "ReComUangMuka",
            ////                    "calculate" => array(
            ////                        "source" => "uangMuka",
            ////                        "target" => "uang_muka_dipakai",
            ////                        "pair_source" => "nilai_sisa",//sunbe sumber yang dibandingkan
            ////                    ),
            //
            //
            //            ),

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

                    "label" => "cash account", "rekening" => array(
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
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartRowNumValidators" => array(
            //            "nilai_entry" => "amount of payment",
            //            "cash_account__saldo" => "saldo bank kurang",
        ),
        "shoppingCartUnionValidators" => array(
            array(
                "creditAmount" => "credit note",
                "uang_muka_dipakai" => "deposit",
                "nilai_entry" => "payment",//sementara untuk lolosin bayar pakai keuntungan kurs
                //                "cash_account__saldo" => "saldo bank kurang",
            ),
        ),
        "shopingCartUnionComparison" => array(
            array(
                "nilai_entry" => "payment belum diisi",
                "cash_account__saldo" => "cash account belum dipilih",
            ),

        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    //                    "amount" => array(
                    //                        "label" => "total amount+",
                    //                        "defaultValue" => "sisa",
                    //                        "maxValue" => "sisa",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),

                    //                    "non_pph" => array(
                    //                        "label" => "non pph+",
                    //                        "defaultValue" => ".0",
                    //                        "keyupAction" => "if(parseFloat(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('extern_nilai2_1').innerHTML) || parseFloat(removeCommas(this.value))<0){this.value=document.getElementById('extern_nilai2_1').innerHTML;}",//dppp belum kesimpen jadinya off dulu
                    ////                        "keyupAction" => "if(parseFloat(removeCommas(this.value))>parseFloat(removeCommas(document.getElementById('nilai_entry').innerHTML) || parseFloat(removeCommas(this.value))<0){this.value=document.getElementById('nilai_entry').innerHTML;}",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    //                    "valid_dpp" => array(
                    //                        "label" => "DPP PPh 23",
                    //                        "defaultValue" => "valid_dpp",
                    //                        "maxValue" => "valid_dpp",
                    //                        "minValue" => "valid_dpp",
                    //                        "keyPressAction" => "",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    //                    "pph23_nilai" => array(
                    //                        "label" => "PPh 23",
                    //                        "defaultValue" => "pph23_nilai",
                    //                        "maxValue" => "pph23_nilai",
                    //                        "minValue" => "pph23_nilai",
                    //                        "keyPressAction" => "",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    //                    "uangMuka" => array(
                    //                        "label" => "paid by deposit",
                    //                        "defaultValue" => "uangMuka",
                    //                        "maxValue" => "uangMuka",
                    //                        "minValue" => "uangMuka",
                    //                        "keyPressAction" => "",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),

                    "sisa" => array(
                        "label" => "sisa",
                        "defaultValue" => "sisa",
                        "maxValue" => "sisa",
                        "minValue" => "sisa",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        'hideRow' => "true",
                    ),
                    "uang_muka_dipakai" => array(
                        "label" => "paid by deposit",
                        "defaultValue" => "0",
                        "maxValue" => "uang_muka_dipakai",
                        "minValue" => "uang_muka_dipakai",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "credit_note_dipakai" => array(
                        "label" => "credit note",
                        "defaultValue" => "credit_note_dipakai",
                        "maxValue" => "credit_note_dipakai",
                        "minValue" => "credit_note_dipakai",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "nilai_entry" => array(
                        "label" => "payment",
                        "defaultValue" => ".0",
                        "keyupAction" => "if(parseFloat(removeCommas(this.value))>parseFloat(removeCommas(document.getElementById('sisa').value)) || parseFloat(removeCommas(this.value))<0){this.value=document.getElementById('sisa').value;}",
                        //                        "keyupAction" => "if(parseFloat(removeCommas(this.value))>parseFloat(removeCommas(document.getElementById('sisa').value)) || parseFloat(removeCommas(this.value))<0){this.value=document.getElementById('new_sisa').value;}",
                        "addPoints" => array(1,),
                    ),

                    //                    "payment_out" => array(
                    //                        "label" => "cash payout",
                    //                        "defaultValue" => ".0",
                    //                        "maxValue" => "new_sisa",
                    //                        "minValue" => "",
                    //                        "keyupAction" => "if(parseFloat(removeCommas(this.value))>parseFloat(removeCommas(document.getElementById('new_sisa').value)) || parseFloat(removeCommas(this.value))<0){this.value=document.getElementById('new_sisa').value;}",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    //                    "payment_out" => array(
                    //                        "label" => "payment",
                    //                        "defaultValue" => "pay_out",
                    //                        "maxValue" => "new_sisa",
                    //                        "minValue" => "",
                    //                        'disabled' => "disabled",
                    ////                        "keyupAction" => "if(parseFloat(removeCommas(this.value))>parseFloat(removeCommas(document.getElementById('new_sisa').value)) || parseFloat(removeCommas(this.value))<0){this.value=document.getElementById('new_sisa').value;}",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    "new_sisa" => array(
                        "label" => "balance of invoice",
                        "defaultValue" => "new_sisa",
                        "maxValue" => "new_sisa",
                        "minValue" => "new_sisa",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                ),
            ),
        ),
        "relativeElements" => array(
            "pph23Method" => array(
                "1" => array(
                    "pph23Method__desc" => array(
                        "elementType" => "dataField",
                        "label" => "SKB",
                        "inputType" => "text",
                        "defaultValue" => "",
                        "editPoints" => array(1),
                    ),
                ),
            ),
            //            "cashMethode" => array(
            //                "reguler" => array(
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

            //            "branchTarget" =>array(
            //                "-1" =>array(
            //                    "externMain"=>array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "radio",
            //                        "label" => "transfer expense to",
            //                        "mdlName" => "MdlBiayaMethodGeneral",
            //                        "mdlFilter" => array(),
            //                        "key" => "nama",
            //                        "labelSrc" => "nama",
            //                        "description" => "",
            //                        "usedFields" => array(
            //                            "nama" => "kategori biaya",
            //                        ),
            //                        "editPoints" => array(1,),
            //                        "noValidate" => true,
            //                    ),
            //                ),
            //            ),
            //            "externMain" =>array(
            //                "biaya umum" =>array(
            //                    "dtaDetail" =>array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "combo",
            //                        "label" => "expense details",
            //                        "mdlName" => "MdlDtaBiayaUmum",
            //                        "mdlFilter" => array(),
            //                        "key" => "id",
            //                        "labelSrc" => "nama",
            //                        "description" => "",
            //                        "usedFields" => array(
            //                            "nama" => "beban biaya",
            //                        ),
            //                        "targetMethod" => array(
            ////                            "biaya usaha" => "ReComBiayaUsaha_payment",
            //                            "biaya umum" => "ReComBiayaUmum_payment",
            ////                            "non_npwp" => "ReComPph23NonNpwp_purchasing",
            ////                            "none" => "ReComPph23None_purchasing",
            //                        ),
            //                        "editPoints" => array(1,),
            //                        "noValidate" => true,
            //                    ),
            //                ),
            //                "biaya usaha" =>array(
            //                    "dtaDetail" =>array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "combo",
            //                        "label" => "expense details",
            //                        "mdlName" => "MdlDtaBiayaUsaha",
            //                        "mdlFilter" => array(),
            //                        "key" => "id",
            //                        "labelSrc" => "nama",
            //                        "description" => "",
            //                        "usedFields" => array(
            //                            "nama" => "beban biaya",
            //                        ),
            //                        "targetMethod" => array(
            //                            "biaya usaha" => "ReComBiayaUsaha_payment",
            ////                            "biaya umum" => "ReComBiayaUmum_payment",
            ////                            "non_npwp" => "ReComPph23NonNpwp_purchasing",
            ////                            "none" => "ReComPph23None_purchasing",
            //                        ),
            //                        "editPoints" => array(1,),
            //                        "noValidate" => true,
            //                    ),
            //
            //                ),
            //                "biaya produksi" =>array(
            //                    "dtaDetail" =>array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "combo",
            //                        "label" => "expense details",
            //                        "mdlName" => "MdlDtaBiayaProduksi",
            //                        "mdlFilter" => array(),
            //                        "key" => "id",
            //                        "labelSrc" => "nama",
            //                        "description" => "",
            //                        "usedFields" => array(
            //                            "nama" => "beban biaya",
            //                        ),
            //                        "editPoints" => array(1,),
            //                        "noValidate" => true,
            //                    ),
            //
            //                ),
            //            ),
        ),
        "shoppingCartReferenceFields" => array(
            "nomer" => "receipt number",
            "nomer_top" => "receipt ref.",
            "refNum" => "return ref.",
            "fulldate" => "date",
            "tagihan" => "due amount",
            "refValue" => "returned",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "vendor",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "resumeFieldNames" => array(
            "selectFields" => "suppliers_nama",
            "title" => "vendor",
        ),
        "previewCtr" => "Create",
        //        "dueDateReader" => true,
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di  {cabang_nama}",
        ),
    ),


    //penutupan rekening koran
    "4440" => array(
        "icon" => "fa fa-money",
        "label" => "rekening koran payable",
        //        "place" => "branch",
        "paymentConfig" => true,
        "place" => "center",
        "steps" => array(
            //            1 => array(
            //                "label"       => "pembayaran hutang",
            //                "actionLabel" => "pembayaran hutang",
            //                "source"      => "",
            //                "target"      => "489r",
            //                "userGroup"   => "sys",
            //                "stateLabel"  => "ready to be paid",
            //                "stateColor"  => "#dd3300",
            //            ),
            1 => array(
                "label" => "rekening koran payable",
                "actionLabel" => "process payment",
                "source" => "",
                "target" => "4440",
                "userGroup" => "c_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "PT. Everest Electronic",
                "stateFooter" => "paid by",
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.444",
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
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "cash_account__label" => "account",
            "sisa" => "amount",
            "additionalFactor" => "keuntungan/kerugian kurs",
            "nilai_bayar" => "paid",
            "new_sisa" => "remain amount",
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

        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",
            ),

        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nomer",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            "creditValue" => "diskon",
            "pair_pihak_id" => "extern2_id",
            "pair_pihak_name" => "extern2_nama",

        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "sisa" => "due amount",
            ),

        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "sisa" => "amount",
                //                "creditAmount" => "supplier credit amount",
                //                "creditValue" => "additional discount",
                //                "additional_value" => "additional kurs",
                //                "additional_expense" => "additional expense",
                //                "harus_bayar" => "amount remains to pay",
                //                "nilai_entry" => "loan installment",
                //                "new_sisa"    => "remain to pay (from list)",
            ),
        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartAvoidRemove" => true,
        "tagihanSrc" => "harus_bayar",
        "receiptElements" => array(
            "vendorDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "rekening koran",
                "mdlName" => "MdlRekeningKoran",
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
        "pairRegistries" => array(
            "main",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartRowNumValidators" => array(
            "nilai_entry" => "amount of payment",//sementara untuk lolosin bayar pakai keuntungan kurs
        ),
        "shoppingCartUnionValidators" => array(
            array(
                "creditAmount" => "credit amount",
                "nilai_entry" => "amount value",//sementara untuk lolosin bayar pakai keuntungan kurs
            ),
        ),
        "shopingCartAddValidator" => array(
            "additional" => array(
                "1" => "add_diskon",
                "-1" => "add_diskon",
            ),
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    //                    "origAmount" => array(
                    //                        "label" => "original amount",
                    //                        "defaultValue" => "tagihan",
                    //                        "maxValue" => "tagihan",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    //                    "addDiscount" => array(
                    //                        "label" => "additional discount",
                    //                        "defaultValue" => "diskon",
                    //                        "maxValue" => "diskon",
                    //                        'disabled' => "disabled",
                    //                        "role" => "minus",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    //                    "amount"       => array(
                    //                        "label"        => "total amount",
                    //                        "defaultValue" => "sisa",
                    //                        "maxValue"     => "sisa",
                    //                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
                    //                        'disabled'     => "disabled",
                    //                        "addPoints"    => array(1,),
                    //                    ),
                    //                    "credit_amount" => array(
                    //                        "label" => "credit amount (from return)",
                    //                        "defaultValue" => "creditAmount",
                    //                        //                        "keyupAction" => "",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                        "role" => "minus",
                    //                    ),
                    //                    "credit_note"   => array(
                    //                        "label"        => "credit note",
                    //                        "defaultValue" => "creditValue",
                    //                        //                        "keyupAction" => "",
                    //                        'disabled'     => "disabled",
                    //                        "addPoints"    => array(1,),
                    //                    ),
                    //                    "additional_expense" => array(
                    //                        "label" => "additional expense",
                    //                        "defaultValue" => ".0",
                    //                        "keyupAction" => "
                    //                                if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('additional_expense').value)) || parseInt(removeCommas(this.value))<0){
                    //
                    //                                }
                    //
                    //                            ",
                    //                        //                        'disabled'     => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    //                    "additional_value" => array(
                    //                        "label" => "selisih nilai",
                    //                        "defaultValue" => ".0",
                    //                        "keyupAction" => "
                    //                            if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('additional_value').value)) || parseInt(removeCommas(this.value))<0){
                    //
                    //                            }
                    //
                    //                            ",
                    ////                        'disabled'     => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    "harus_bayar" => array(
                        "label" => "amount remains to pay",
                        "defaultValue" => "harus_bayar",
                        "maxValue" => "harus_bayar",
                        "minValue" => "harus_bayar",
                        //                        "keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(removeCommas(document.getElementById('bayar').value)-parseFloat(gt))",
                        //                        "keyupAction" => "var gt=this.min,bayar=this.value,kembali=document.getElementById('kembali'); kembali.value=parseFloat(bayar)-parseFloat(gt);if(parseFloat(bayar)<parseFloat(gt)){kembali.style.color='red',kembali.style.fontWeight='700'}else{kembali.style.color='green',kembali.style.fontWeight='700'}",

                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        "hideRow" => "true",
                    ),

                    "nilai_entry" => array(
                        "label" => "loan installment",
                        "defaultValue" => ".0",
                        "keyupAction" => "
    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harus_bayar').value;}

                            ",
                        //                        'disabled'     => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "new_sisa" => array(
                        "label" => "remain to pay (from list)",
                        "defaultValue" => "new_sisa",
                        "keyupAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),

                ),
            ),
        ),
        "shoppingCartReferenceFields" => array(
            "nomer" => "receipt number",
            "nomer_top" => "receipt ref.",
            "refNum" => "return ref.",
            "fulldate" => "date",
            "tagihan" => "due amount",
            "refValue" => "returned",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
            "notes" => "description",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "vendor",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "resumeFieldNames" => array(
            "selectFields" => "suppliers_nama",
            "title" => "vendor",
        ),
        "previewCtr" => "Create",
    ),
);