<?php
//region urusan tanggal-menanggal
// date_default_timezone_set('asia/jakarta');
// $date = new DateTime(date("Y-m-d")); // Y-m-d
// $date->add(new DateInterval('P30D'));
//$date->format('Y-m-d') . "\n";
//endregion


$config["coTransaksiUi"] = array(

    //config assembling / produksi
    "776" => array(
        "transaksiMode" => "forward",
        "icon" => "fa fa-cube",
        "label" => "BOM",
        "place" => "center",
        "steps" => array(
            1 => array(
                "conntroller" => "Create",
                "label" => "BOM",
                "actionLabel" => "BOM",
                "source" => "",
                "target" => "776r",
                "userGroup" => "c_holding",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
            ),
            2 => array(
                "label" => "COMPLETE",
                "actionLabel" => "COMPLETE",
                "source" => "776r",
                "target" => "776",
                "userGroup" => "c_holding",
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
//            "jenis_label" => "activity",
            "dtime" => "date",
            "bomProdukNama" => "product",
//            "cabang2_nama" => "recipient",
            "nomer_top" => "reference number",
            "nomer" => "number",
            "oleh_nama" => "person",
//            "gudang_nama" => "warehouse",
            "next_pic" => "Next step otorisator",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
                "dtime" => "date",
                "bomProdukNama" => "product",
//                "cabang2_nama" => "recipient",
                "nomer_top" => "reference number",
                "nomer" => "receipt number",
                "oleh_nama" => "person",
//                "gudang_nama" => "warehouse",
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
                "nama" => "summary bahan baku",
                "satuan" => "satuan",
//                "stok" => "stok tersedia",
//                "sisa" => "sisa stok",
                "nilai" => "harga",
                "jml" => "qty",
                "sub_nilai" => "subtotal",
            ),
            2 => array(
                "nama" => "bahan baku",
                "satuan" => "satuan",
                "nilai" => "harga",
                "jml" => "qty",
                "sub_nilai" => "subtotal",
            ),
            3 => array(
                "nama" => "item source name",
                "satuan" => "satuan",
                "nilai" => "harga",
                "jml" => "qty",
                "sub_nilai" => "subtotal",
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
        "shoppingCartFields2_fase" => array(
            1 => array(
                "produk_dasar_nama" => "bahan baku",
                "satuan_nama" => "satuan",
                "nilai" => "harga",
                "sub_jml" => "qty",
                "sub_nilai" => "subtotal",
            ),
            2 => array(
                "produk_dasar_nama" => "bahan baku",
                "satuan_nama" => "satuan",
                "nilai" => "harga",
                "sub_jml" => "qty",
                "sub_nilai" => "subtotal",
            ),
            3 => array(
                "produk_dasar_nama" => "bahan baku",
                "satuan_nama" => "satuan",
                "nilai" => "harga",
                "sub_jml" => "qty",
                "sub_nilai" => "subtotal",
            ),
        ),
        "shoppingCartFields3_fase" => array(
            1 => array(
                "produk_dasar_nama" => "summary standard cost",
                "sub_nilai" => "amount",
            ),
            2 => array(
                "produk_dasar_nama" => "summary standard cost",
                "sub_nilai" => "amount",
            ),
            3 => array(
                "produk_dasar_nama" => "summary standard cost",
                "sub_nilai" => "amount",
            ),
        ),
        "shoppingCartFieldsHasil_fase" => array(
            1 => array(
                "produk_dasar_nama" => "hasil bom",
                "satuan_nama" => "satuan",
                "sub_jml" => "qty",
                "nilai" => "harga",
                "sub_nilai" => "subtotal",
            ),
            2 => array(
                "produk_dasar_nama" => "hasil bom",
                "satuan_nama" => "satuan",
                "sub_jml" => "qty",
                "nilai" => "harga",
                "sub_nilai" => "subtotal",
            ),
            3 => array(
                "produk_dasar_nama" => "hasil bom",
                "satuan_nama" => "satuan",
                "sub_jml" => "qty",
                "nilai" => "harga",
                "sub_nilai" => "subtotal",
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
        "shoppingCartNumFields" => array(
            1 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
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
            1 => "jml*hpp",
            2 => "jml*hpp",
            3 => "jml*hpp",
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
        "componentsFase" => array(
            "model" => "MdlProdukKomposisiFase",
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
            2 => array(
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
            2 => array(
                "stokSupplies" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
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
                    "costID" => "produk_dasar_id",
                    "costName" => "produk_dasar_nama",
                    "costNilai" => "nilai",
                ),
            ),
            2 => array(
                "source" => "items2",
                "target" => "rsltItems2",
                "jenis" => "biaya",
                "kolom" => array(
                    "costID" => "produk_dasar_id",
                    "costName" => "produk_dasar_nama",
                    "costNilai" => "nilai",
                ),
            ),
            3 => array(
                "source" => "items2",
                "target" => "rsltItems2",
                "jenis" => "biaya",
                "kolom" => array(
                    "costID" => "produk_dasar_id",
                    "costName" => "produk_dasar_nama",
                    "costNilai" => "nilai",
                ),
            ),
        ),
        "pairRegistries" => array(
            "main",
        ),
        "previewCtr" => "Create",
        //----
        "clonerTransaction" => array(
            1 => array(
                "main" => array(
                    "cloner" => true,
                ),
                "connector" => "776",

//                "phase" => array(// gudang id bahan baku per-phase
//                    "phase_1" => array(
//                        "bahan_baku" => array(
//                            "id" => "17",
//                            "nama" => "Gudang BB Fase 1",
//                        ),
//                        "bahan_jadi" => array(
//                            "id" => "18",
//                            "nama" => "Gudang BB Fase 1",
//                        ),
//
//                    ),
//                    "phase_2" => array(
//                        "bahan_baku" => array(
//                            "id" => "19",
//                            "nama" => "Gudang BB Fase 2",
//                        ),
//                        "bahan_jadi" => array(
//                            "id" => "20",
//                            "nama" => "Gudang BJ Fase 2",
//                        ),
//                    ),
//                    "phase_3" => array(
//                        "bahan_baku" => array(
//                            "id" => "21",
//                            "nama" => "Gudang BB Fase 3",
//                        ),
//                        "bahan_jadi" => array(
//                            "id" => "22",
//                            "nama" => "Gudang BJ Fase 3",
//                        ),
//
//                    ),
//                    "phase_4" => array(
//                        "bahan_baku" => array(
//                            "id" => "23",
//                            "nama" => "Gudang BB Fase 4",
//                        ),
//                        "bahan_jadi" => array(
//                            "id" => "24",
//                            "nama" => "Gudang BJ Fase 4",
//                        ),
//                    ),
//                    "phase_5" => array(
//                        "bahan_baku" => array(
//                            "id" => "25",
//                            "nama" => "Gudang BB Fase 5",
//                        ),
//                        "bahan_jadi" => array(
//                            "id" => "26",
//                            "nama" => "Gudang BJ Fase 5",
//                        ),
//                    ),
//                ),

            ),

        ),
        "connectTo" => array(
            1 => true,
        ),
    ),

    "7776" => array(
        "transaksiMode" => "forward",
        "icon" => "fa fa-cube",
        "label" => "BOM",
        "place" => "center",
        "steps" => array(
            1 => array(
                "conntroller" => "Create",
                "label" => "BOM",
                "actionLabel" => "SIMPAN",
                "source" => "",
                "target" => "7776r",
                "userGroup" => "c_holding",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
            ),
            2 => array(
                "label" => "PRODUKSI",
                "actionLabel" => "SIMPAN",
                "subActionLabel" => "SIMPAN",
                "source" => "7776r",
                "target" => "7776", //
                "userGroup" => "c_holding",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "Approved By",
                "allowEdit" => true,
                "showProduksiStatus" => true,
            ),
//            3 => array(
//                "label" => "WIP COMPLETE",
//                "actionLabel" => "WIP COMPLETE",
//                "source" => "776wip",
//                "target" => "776",
//                "userGroup" => "c_holding",
//                "stateLabel" => "complete",
//                "stateColor" => "#009900",
//                "stateCaption" => "complete by",
//                "allowEdit" => true,
//                "allowIncrement" => false,
//            ),
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
//            "jenis_label" => "activity",
            "dtime" => "date",
            "bomProdukNama" => "product",
//            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
//            "gudang_nama" => "warehouse",
            "next_pic" => "Next step otorisator",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
                "dtime" => "date",
                "bomProdukNama" => "product",
//                "cabang2_nama" => "recipient",
                "nomer_top" => "reference number",
                "nomer" => "receipt number",
                "oleh_nama" => "person",
//                "gudang_nama" => "warehouse",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
                "no" => "no",
                "dtime" => "date",
                "bomProdukNama" => "BOM",
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
                "bomProdukNama" => "BOM",
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
                "nama" => "summary bahan baku",
                "satuan" => "satuan",
                "stok" => "stok",
                "jml" => "qty pemakakain",
                "sisa" => "sisa stok",
            ),
            2 => array(
                "nama" => "item source name",
                "satuan" => "satuan",
                "stok" => "stok",
                "jml" => "qty",
                "sisa" => "sisa stok",
            ),
            3 => array(
                "nama" => "item source name",
                "satuan" => "satuan",
                "stok" => "stok",
                "jml" => "qty",
                "sisa" => "sisa stok",
            ),
        ),
        "shoppingCartFields3" => array(
            1 => array(
                "produk_dasar_nama" => "summary standard cost",
                "sub_nilai" => "amount",
            ),
            2 => array(
                "produk_dasar_nama" => "summary standard cost",
                "sub_nilai" => "amount",
            ),
            3 => array(
                "produk_dasar_nama" => "summary standard cost",
                "sub_nilai" => "amount",
            ),
        ),
        "shoppingCartFields2_fase" => array(
            1 => array(
                "produk_dasar_nama" => "summary bahan baku",
                "sub_jml" => "qty pemakakain",
                "satuan_nama" => "satuan",
            ),
            2 => array(
                "produk_dasar_nama" => "item source name",
                "sub_jml" => "qty",
                "satuan_nama" => "satuan",
            ),
            3 => array(
                "produk_dasar_nama" => "item source name",
                "sub_jml" => "qty",
                "satuan_nama" => "satuan",
            ),
        ),
        "shoppingCartFields3_fase" => array(
            1 => array(
                "produk_dasar_nama" => "summary standard cost",
                "sub_nilai" => "amount",
            ),
            2 => array(
                "produk_dasar_nama" => "summary standard cost",
                "sub_nilai" => "amount",
            ),
            3 => array(
                "produk_dasar_nama" => "summary standard cost",
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
        "shoppingCartNumFields" => array(
            1 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartNoteEnabled" => false,
        "shoppingCartEditableFields" => array(
            1 => array(
                "jml",
            ),
            2 => array(
                "jml",
            ),
            3 => array(
                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
            3 => "jml*hpp",
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
        "componentsFase" => array(
            "model" => "MdlProdukKomposisiFase",
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
            2 => array(
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
            2 => array(
                "stokSupplies" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
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
                    "costID" => "produk_dasar_id",
                    "costName" => "produk_dasar_nama",
                    "costNilai" => "nilai",
                ),
            ),
            2 => array(
                "source" => "items2",
                "target" => "rsltItems2",
                "jenis" => "biaya",
                "kolom" => array(
                    "costID" => "produk_dasar_id",
                    "costName" => "produk_dasar_nama",
                    "costNilai" => "nilai",
                ),
            ),
            3 => array(
                "source" => "items2",
                "target" => "rsltItems2",
                "jenis" => "biaya",
                "kolom" => array(
                    "costID" => "produk_dasar_id",
                    "costName" => "produk_dasar_nama",
                    "costNilai" => "nilai",
                ),
            ),
        ),
        "pairRegistries" => array(
            "main",
        ),
        "previewCtr" => "Create",
        //----
//        "clonerTransaction" => array(
//            1 => array(
//                "main" => array(
//                    "cloner" => true,
//                ),
//                "connector" => "776",
//
////                "phase" => array(// gudang id bahan baku per-phase
////                    "phase_1" => array(
////                        "bahan_baku" => array(
////                            "id" => "17",
////                            "nama" => "Gudang BB Fase 1",
////                        ),
////                        "bahan_jadi" => array(
////                            "id" => "18",
////                            "nama" => "Gudang BB Fase 1",
////                        ),
////
////                    ),
////                    "phase_2" => array(
////                        "bahan_baku" => array(
////                            "id" => "19",
////                            "nama" => "Gudang BB Fase 2",
////                        ),
////                        "bahan_jadi" => array(
////                            "id" => "20",
////                            "nama" => "Gudang BJ Fase 2",
////                        ),
////                    ),
////                    "phase_3" => array(
////                        "bahan_baku" => array(
////                            "id" => "21",
////                            "nama" => "Gudang BB Fase 3",
////                        ),
////                        "bahan_jadi" => array(
////                            "id" => "22",
////                            "nama" => "Gudang BJ Fase 3",
////                        ),
////
////                    ),
////                    "phase_4" => array(
////                        "bahan_baku" => array(
////                            "id" => "23",
////                            "nama" => "Gudang BB Fase 4",
////                        ),
////                        "bahan_jadi" => array(
////                            "id" => "24",
////                            "nama" => "Gudang BJ Fase 4",
////                        ),
////                    ),
////                    "phase_5" => array(
////                        "bahan_baku" => array(
////                            "id" => "25",
////                            "nama" => "Gudang BB Fase 5",
////                        ),
////                        "bahan_jadi" => array(
////                            "id" => "26",
////                            "nama" => "Gudang BJ Fase 5",
////                        ),
////                    ),
////                ),
//
//            ),
//
//        ),
//        "connectTo" => array(
//            1 => true,
//        ),
    ),
    "7778" => array(
        "transaksiMode" => "forward",
        "icon" => "fa fa-cube",
        "label" => "BOM",
        "place" => "center",
        "steps" => array(
            1 => array(
                "conntroller" => "Create",
                "label" => "BOM",
                "actionLabel" => "SIMPAN",
                "subActionLabel" => "SIMPAN",
                "source" => "",
                "target" => "7778",
                "userGroup" => "c_holding",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
                "allowEdit" => true,
                "showProduksiStatus" => true,
            ),
//            2 => array(
//                "label" => "PRODUKSI",
//                "actionLabel" => "SIMPAN",
//                "subActionLabel" => "SIMPAN",
//                "source" => "7776r",
//                "target" => "7776", //
//                "userGroup" => "c_holding",
//                "stateLabel" => "completed",
//                "stateColor" => "#009900",
//                "stateCaption" => "Approved By",
//                "allowEdit" => true,
//                "showProduksiStatus" => true,
//            ),
//            3 => array(
//                "label" => "WIP COMPLETE",
//                "actionLabel" => "WIP COMPLETE",
//                "source" => "776wip",
//                "target" => "776",
//                "userGroup" => "c_holding",
//                "stateLabel" => "complete",
//                "stateColor" => "#009900",
//                "stateCaption" => "complete by",
//                "allowEdit" => true,
//                "allowIncrement" => false,
//            ),
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
            // "enabled" => true,
            // "mdlName" => "MdlLockerStock",
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
        // "selectorProcessor" => "_processSelectProductAssembling/selectFase",
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
//            "jenis_label" => "activity",
            "dtime" => "date",
            "bomProdukNama" => "product",
//            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
//            "gudang_nama" => "warehouse",
            "next_pic" => "Next step otorisator",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
                "dtime" => "date",
                "bomProdukNama" => "product",
                "kode_produksi" => "Kode/Nomer Seri",
                "fase_nama" => "fase produksi",
//                "cabang2_nama" => "recipient",
                "nomer_top" => "reference number",
                "nomer" => "receipt number",
                "oleh_nama" => "person",
//                "gudang_nama" => "warehouse",
                "keterangan" => "keterangan",
                "print_label" => "tool",
                "print_barcode" => "print <i class='fa fa-barcode'></i>",
            ),
            2 => array(
                "no" => "no",
                "dtime" => "date",
                "bomProdukNama" => "BOM",
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
                "bomProdukNama" => "BOM",
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
            1 => array(
                "print_label" => "nomer",
                "print_barcode" => "id",
            ),
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
                "nama" => "summary bahan baku",
                "serial_bahan_baku" => "kode/seri bahan baku",
                "satuan" => "satuan",
                "stok" => "stok tersedia",
//                "sisa" => "sisa stok",
                "nilai" => "harga",
                "jml" => "qty",
                "sub_nilai" => "subtotal",
            ),
            2 => array(
                "nama" => "bahan baku",
                "satuan" => "satuan",
                "nilai" => "harga",
                "jml" => "qty",
                "sub_nilai" => "subtotal",
            ),
            3 => array(
                "nama" => "item source name",
                "satuan" => "satuan",
                "nilai" => "harga",
                "jml" => "qty",
                "sub_nilai" => "subtotal",
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
        "shoppingCartFields2_fase" => array(
            1 => array(
                "produk_dasar_nama" => "bahan baku",
                "satuan_nama" => "satuan",
                "nilai" => "harga",
                "sub_jml" => "qty",
                "sub_nilai" => "subtotal",
            ),
            2 => array(
                "produk_dasar_nama" => "bahan baku",
                "satuan_nama" => "satuan",
                "nilai" => "harga",
                "sub_jml" => "qty",
                "sub_nilai" => "subtotal",
            ),
            3 => array(
                "produk_dasar_nama" => "bahan baku",
                "satuan_nama" => "satuan",
                "nilai" => "harga",
                "sub_jml" => "qty",
                "sub_nilai" => "subtotal",
            ),
        ),
        "shoppingCartFields3_fase" => array(
            1 => array(
                "produk_dasar_nama" => "summary standard cost",
                "sub_nilai" => "amount",
            ),
            2 => array(
                "produk_dasar_nama" => "summary standard cost",
                "sub_nilai" => "amount",
            ),
            3 => array(
                "produk_dasar_nama" => "summary standard cost",
                "sub_nilai" => "amount",
            ),
        ),
        "shoppingCartFieldsHasil_fase" => array(
            1 => array(
                "produk_dasar_nama" => "hasil bom",
                "satuan_nama" => "satuan",
                "sub_jml" => "qty",
                "nilai" => "harga",
                "sub_nilai" => "subtotal",
            ),
            2 => array(
                "produk_dasar_nama" => "hasil bom",
                "satuan_nama" => "satuan",
                "sub_jml" => "qty",
                "nilai" => "harga",
                "sub_nilai" => "subtotal",
            ),
            3 => array(
                "produk_dasar_nama" => "hasil bom",
                "satuan_nama" => "satuan",
                "sub_jml" => "qty",
                "nilai" => "harga",
                "sub_nilai" => "subtotal",
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
        "shoppingCartNumFields" => array(
            1 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartNoteEnabled" => false,
        "shoppingCartAvoidRemove" => true,
        "shoppingCartAvoidRemoveAll_items" => true,
        "shoppingCartEditableFields" => array(
            1 => array(//                "jml",
            ),
            2 => array(//                "jml",
            ),
            3 => array(//                "jml",
            ),
        ),
        "shoppingCartEditableFields2" => array(
            1 => array(
                "serial_bahan_baku",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
            3 => "jml*hpp",
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
        //-------
        "shoppingCartKodeProduksi" => array(
            "enabled" => true,
            "label" => "Kode/Nomer Seri Produk (*)",
            "link" => "_shoppingCart/recordProduksi/",
            "jenisTr" => "7778",
            "key" => "kode_produksi",

            "validate" => true,
            "validateLabel" => "Kode/Nomer Seri Produksi harus ditentukan. Silahkan ditentukan dahulu.",

            "mdlName" => "MdlManufacturIdentity",
        ),
        "shoppingCartKodeBahanBaku" => array(
            "enabled" => true,
            "label" => "Kode/Nomer Seri Bahan Baku (*)",
            "link" => "_shoppingCart/recordProduksiBahanBaku/",
            "jenisTr" => "7778",
            "key" => "serial_bahan_baku",
            "gateTarget" => "items2_sum",

            "validate" => true,
            "validateKey" => array(
                "serial_bahan_baku" => "Kode/Nomer Seri Bahan Baku {produk_nama} harus ditentukan. Silahkan ditentukan dahulu.",
            ),
//            "validateLabel" => "Kode/Nomer Seri Bahan Baku {produk_nama} harus ditentukan. Silahkan ditentukan dahulu.",
//
//            "mdlName" => "MdlManufacturIdentity",
//
        ),
        //-------
        "componentsAss" => array(
            "model" => "MdlProdukKomposisiFase",
            "modelSrc" => "MdlSupplies",
        ),
        "componentsFase" => array(
            "model" => "MdlProdukKomposisiFase",
            "modelSrc" => "MdlSupplies",
        ),

        "followupItemEditable" => "_followupLiveEdit/updateItemFieldProduksi/",
        "followupItemRemove" => "_followupLiveEdit/removeItemProduksi/",
        "changeSelectorProcessor" => true,

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
            2 => array(
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
            2 => array(
                "stokSupplies" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
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
                    "costID" => "produk_dasar_id",
                    "costName" => "produk_dasar_nama",
                    "costNilai" => "subnilai",
                ),
            ),
            2 => array(
                "source" => "items2",
                "target" => "rsltItems2",
                "jenis" => "biaya",
                "kolom" => array(
                    "costID" => "produk_dasar_id",
                    "costName" => "produk_dasar_nama",
                    "costNilai" => "nilai",
                ),
            ),
            3 => array(
                "source" => "items2",
                "target" => "rsltItems2",
                "jenis" => "biaya",
                "kolom" => array(
                    "costID" => "produk_dasar_id",
                    "costName" => "produk_dasar_nama",
                    "costNilai" => "nilai",
                ),
            ),
        ),
        "pairRegistries" => array(
            "main",
        ),
        "previewCtr" => "Create",
        //----
//        "clonerTransaction" => array(
//            1 => array(
//                "main" => array(
//                    "cloner" => true,
//                ),
//                "connector" => "776",
//
////                "phase" => array(// gudang id bahan baku per-phase
////                    "phase_1" => array(
////                        "bahan_baku" => array(
////                            "id" => "17",
////                            "nama" => "Gudang BB Fase 1",
////                        ),
////                        "bahan_jadi" => array(
////                            "id" => "18",
////                            "nama" => "Gudang BB Fase 1",
////                        ),
////
////                    ),
////                    "phase_2" => array(
////                        "bahan_baku" => array(
////                            "id" => "19",
////                            "nama" => "Gudang BB Fase 2",
////                        ),
////                        "bahan_jadi" => array(
////                            "id" => "20",
////                            "nama" => "Gudang BJ Fase 2",
////                        ),
////                    ),
////                    "phase_3" => array(
////                        "bahan_baku" => array(
////                            "id" => "21",
////                            "nama" => "Gudang BB Fase 3",
////                        ),
////                        "bahan_jadi" => array(
////                            "id" => "22",
////                            "nama" => "Gudang BJ Fase 3",
////                        ),
////
////                    ),
////                    "phase_4" => array(
////                        "bahan_baku" => array(
////                            "id" => "23",
////                            "nama" => "Gudang BB Fase 4",
////                        ),
////                        "bahan_jadi" => array(
////                            "id" => "24",
////                            "nama" => "Gudang BJ Fase 4",
////                        ),
////                    ),
////                    "phase_5" => array(
////                        "bahan_baku" => array(
////                            "id" => "25",
////                            "nama" => "Gudang BB Fase 5",
////                        ),
////                        "bahan_jadi" => array(
////                            "id" => "26",
////                            "nama" => "Gudang BJ Fase 5",
////                        ),
////                    ),
////                ),
//
//            ),
//
//        ),
//        "connectTo" => array(
//            1 => true,
//        ),

        "tabFieldsItems" => array(
            "produk_id" => array(
                "select" => "All",
//                "dtime" => "tanggal",
                "extern_id" => "pID",
                "extern_nama" => "Bahan Baku",
                "qty_debet" => "Qty",
                "satuan" => "Satuan",
//                "cabang_nama" =>"cabang",
                //                "purchased" => "On Purchase",
                //                "valid_qty" => "Outstanding",
            ),
//            "transaksi_id" => array(
//                //                "select" => "tic",
//                "dtime" => "tanggal",
//                "nomer" => "Approval no",
//                "nomer_top" => "Supplies Request No",
//                "arrProduk" => "Produk",
//                "cabang2_nama" => "Cabang",
//                "oleh_nama" => "PIC",
//                "action" => "Action",
//            ),
        ),
        "settingGudang" => array(
//            "setting" => "single",
            "setting" => "multi",
        ),
    ),

    //region fase asembling
    "7761" => array(
        "phase" => "phase_1",
        "transaksiMode" => "forward",
        "icon" => "fa fa-gear",
        "label" => "PRODUKSI PH-1",
        "place" => "center",
        "default_warehouse" => "7001",//udang bb fase 1
        "steps" => array(
            1 => array(
                "label" => "REQUEST PH-1",
                "actionLabel" => "request produksi",
                "source" => "",
                "target" => "7761r",
                "userGroup" => "c_produksi",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
                "subplace" => "warehouse_ng",
            ),
            2 => array(
                "label" => "PRODUK WIP PH-1",
                "actionLabel" => "prosess",
                "source" => "7761r",
                "target" => "7761a",
                "userGroup" => "c_produksi_spv",
                "stateLabel" => "prosess",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                "allowEdit" => true,
                "allowIncrement" => false,
                "subplace" => "warehouse_ng",
            ),
            3 => array(
                "label" => "QC PH-1",
                "actionLabel" => "approve & selesai",
                "source" => "7761a",
                "target" => "7761",
                "userGroup" => "c_produksi_spv",
                "stateLabel" => "receive on warehouse",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                "subplace" => "warehouse_ng",
                "allowEdit" => true,
                "allowIncrement" => false,
            ),
        ),

        "template" => "template/transaksi_nopihak_phase.html",
        "selectorModel" => "MdlProdukRakitanPhase1",
        "selectorSrcModel" => "MdlProdukRakitanPhase1",

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

        "selectorProject" => "_selectorProject/select",
        "projectProcessor" => "_processProject/select",

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
                "print_barcode" => "print <i class='fa fa-barcode'></i>",
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
                "print_barcode" => "print <i class='fa fa-barcode'></i>",
            ),
        ),
        "extHistoryFields" => array(
            1 => array(
                "print_label" => "nomer",
                "print_barcode" => "id",
            ),
            2 => array("print_label" => "nomer"),
            3 => array(
                "print_label" => "nomer",
                "print_barcode" => "id",
            ),
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
            ),
            2 => array(
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "satuan",
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
        "shoppingCartNumFields" => array(
            1 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartNoteEnabled" => false,
        "shoppingCartEditableFields" => array(
            1 => array(
                "jml",
            ),
            2 => array(
                "jml",
            ),
            3 => array(
                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
            3 => "jml*hpp",
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
                        "gudang_id" => ".7000",
                        "state" => ".active",
                        "transaksi_id" => "masterIDPrev",
                    ),
                    "gate" => "items2_sum",
                ),
                "priceSupplies" => array(
                    "helperName" => "he_cek_price_supplies",
                    "functionName" => "cekPriceSupplies",
                    "params" => array(
                        "cabang_id" => "placeID",
                    ),
                    "gate" => "items2_sum",
                ),
            ),
            2 => array(
                "stokSupplies" => array(
                    "helperName" => "he_cek_stock_supplies_locker",
                    "functionName" => "cekStockSuppliesLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => ".7000",
                        "state" => ".hold",
                        "transaksi_id" => "masterIDPrev",
                    ),
                    "gate" => "items2_sum",
                ),
                "priceSupplies" => array(
                    "helperName" => "he_cek_price_supplies",
                    "functionName" => "cekPriceSupplies",
                    "params" => array(
                        "cabang_id" => "placeID",
                    ),
                    "gate" => "items2_sum",
                ),
            ),
            3 => array(
                "stokSupplies" => array(
                    "helperName" => "he_cek_stock_supplies_locker",
                    "functionName" => "cekStockSuppliesLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => ".7001",
                        "state" => ".active",
                        "transaksi_id" => "masterIDPrev",
                    ),
                    "gate" => "items2_sum",
                ),
                "priceSupplies" => array(
                    "helperName" => "he_cek_price_supplies",
                    "functionName" => "cekPriceSupplies",
                    "params" => array(
                        "cabang_id" => "placeID",
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
            2 => array(
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
            3 => array(
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
                "costItems" => array(
                    "costID" => "id",
                    "costName" => "nama",
                    "costNilai" => "nilai",
                ),
            ),
            2 => array(
                "source" => "items2",
                "target" => "rsltItems2",
                "jenis" => "biaya",
                "kolom" => array(
                    "costID" => "id",
                    "costName" => "nama",
                    "costNilai" => "nilai",
                ),
                "costMain" => array(
                    "costID" => "id",
                    "costName" => "nama",
                ),
                "costItems" => array(
                    "costID" => "id",
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
                "costMain" => array(
                    "costID" => "id",
                    "costName" => "nama",
                ),
                "costItems" => array(
                    "costID" => "id",
                    "costName" => "nama",
                    "costNilai" => "nilai",
                ),
            ),
        ),
        "pairRegistries" => array(
            "main",
        ),
        "previewCtr" => "Create",
    ),
    "7762" => array(
        "phase" => "phase_2",
        "transaksiMode" => "forward",
        "icon" => "fa fa-cube",
        "label" => "PRODUKSI PH-2",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "REQUEST PH-2",
                "actionLabel" => "request produksi",
                "source" => "",
                "target" => "7762r",
                "userGroup" => "c_produksi",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
                "subplace" => "warehouse_ng",
            ),
            2 => array(
                "label" => "PRODUK WIP PH-2",
                "actionLabel" => "prosess",
                "source" => "7762r",
                "target" => "7762a",
                "userGroup" => "c_produksi_spv",
                "stateLabel" => "prosess",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                "allowEdit" => true,
                "allowIncrement" => false,
                "subplace" => "warehouse_ng",
            ),
            3 => array(
                "label" => "QC PH-2",
                "actionLabel" => "approve & selesai",
                "source" => "7762a",
                "target" => "7762",
                "userGroup" => "c_produksi_spv",
                "stateLabel" => "receive on warehouse",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                "subplace" => "warehouse_ng",
                "allowEdit" => true,
                "allowIncrement" => false,
            ),
        ),
        "template" => "template/transaksi_nopihak_phase.html",
        "selectorModel" => "MdlProdukRakitanPhase2",
        "selectorSrcModel" => "MdlProdukRakitanPhase2",
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

        "selectorProject" => "_selectorProject/select",
        "projectProcessor" => "_processProject/select",

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
                "nama" => "hasil produksi fase ini",
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
            ),
            2 => array(
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "satuan",
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
        "shoppingCartNumFields" => array(
            1 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartNoteEnabled" => false,
        "shoppingCartEditableFields" => array(
            1 => array(
                "jml",
            ),
            2 => array(
                "jml",
            ),
            3 => array(
                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
            3 => "jml*hpp",
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
                        "jenis" => ".jadi_ph1",
                        "gudang_id" => ".7001",
                        "state" => ".active",
                        "transaksi_id" => "masterIDPrev",
                    ),
                    "gate" => "items2_sum",
                ),
                "priceSupplies" => array(
                    "helperName" => "he_cek_price_supplies",
                    "functionName" => "cekPriceSupplies",
                    "params" => array(
                        "cabang_id" => "placeID",
                    ),
                    "gate" => "items2_sum",
                ),
            ),

            2 => array(
                "stokSupplies" => array(
                    "helperName" => "he_cek_stock_supplies_locker",
                    "functionName" => "cekStockSuppliesLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => ".7000",
                        "state" => ".hold",
                        "transaksi_id" => "masterIDPrev",
                    ),
                    "gate" => "items2_sum",
                ),
                "priceSupplies" => array(
                    "helperName" => "he_cek_price_supplies",
                    "functionName" => "cekPriceSupplies",
                    "params" => array(
                        "cabang_id" => "placeID",
                    ),
                    "gate" => "items2_sum",
                ),
            ),

            3 => array(
                "stokSupplies" => array(
                    "helperName" => "he_cek_stock_supplies_locker",
                    "functionName" => "cekStockSuppliesLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => ".7001",
                        "state" => ".active",
                        "transaksi_id" => "masterIDPrev",
                    ),
                    "gate" => "items2_sum",
                ),
                "priceSupplies" => array(
                    "helperName" => "he_cek_price_supplies",
                    "functionName" => "cekPriceSupplies",
                    "params" => array(
                        "cabang_id" => "placeID",
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
            2 => array(
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
            3 => array(
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
                "costItems" => array(
                    "costID" => "id",
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
                "costMain" => array(
                    "costID" => "id",
                    "costName" => "nama",
                ),
                "costItems" => array(
                    "costID" => "id",
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
                "costMain" => array(
                    "costID" => "id",
                    "costName" => "nama",
                ),
                "costItems" => array(
                    "costID" => "id",
                    "costName" => "nama",
                    "costNilai" => "nilai",
                ),
            ),
        ),
        "pairRegistries" => array(
            "main",
        ),
        "previewCtr" => "Create",
    ),
    "7763" => array(
        "phase" => "phase_3",
        "transaksiMode" => "forward",
        "icon" => "fa fa-cube",
        "label" => "PRODUKSI PH-3",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "REQUEST PH-3",
                "actionLabel" => "request produksi",
                "source" => "",
                "target" => "7763r",
                "userGroup" => "c_produksi",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
                "subplace" => "warehouse_ng",
            ),
            2 => array(
                "label" => "PRODUK WIP PH-3",
                "actionLabel" => "prosess",
                "source" => "7763r",
                "target" => "7763a",
                "userGroup" => "c_produksi_spv",
                "stateLabel" => "prosess",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                "allowEdit" => true,
                "allowIncrement" => false,
                "subplace" => "warehouse_ng",
            ),
            3 => array(
                "label" => "QC PH-3",
                "actionLabel" => "approve & selesai",
                "source" => "7763a",
                "target" => "7763",
                "userGroup" => "c_produksi_spv",
                "stateLabel" => "receive on warehouse",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                "subplace" => "warehouse_ng",
                "allowEdit" => true,
                "allowIncrement" => false,
            ),
        ),
        "template" => "template/transaksi_nopihak_phase.html",
        "selectorModel" => "MdlProdukRakitanPhase3",
        "selectorSrcModel" => "MdlProdukRakitanPhase3",
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

        "selectorProject" => "_selectorProject/select",
        "projectProcessor" => "_processProject/select",

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
            ),
            2 => array(
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "satuan",
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
        "shoppingCartNumFields" => array(
            1 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartNoteEnabled" => false,
        "shoppingCartEditableFields" => array(
            1 => array(
                "jml",
            ),
            2 => array(
                "jml",
            ),
            3 => array(
                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
            3 => "jml*hpp",
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
                        "jenis" => ".jadi_ph2",
                        "gudang_id" => ".7002",
                        "state" => ".active",
                        "transaksi_id" => "masterIDPrev",
                    ),
                    "gate" => "items2_sum",
                ),
                "priceSupplies" => array(
                    "helperName" => "he_cek_price_supplies",
                    "functionName" => "cekPriceSupplies",
                    "params" => array(
                        "cabang_id" => "placeID",
                    ),
                    "gate" => "items2_sum",
                ),
            ),
            2 => array(
                "stokSupplies" => array(
                    "helperName" => "he_cek_stock_supplies_locker",
                    "functionName" => "cekStockSuppliesLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => ".7001",
                        "state" => ".hold",
                        "transaksi_id" => "masterIDPrev",
                    ),
                    "gate" => "items2_sum",
                ),
                "priceSupplies" => array(
                    "helperName" => "he_cek_price_supplies",
                    "functionName" => "cekPriceSupplies",
                    "params" => array(
                        "cabang_id" => "placeID",
                    ),
                    "gate" => "items2_sum",
                ),
            ),
            3 => array(
                "stokSupplies" => array(
                    "helperName" => "he_cek_stock_supplies_locker",
                    "functionName" => "cekStockSuppliesLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => ".7001",
                        "state" => ".active",
                        "transaksi_id" => "masterIDPrev",
                    ),
                    "gate" => "items2_sum",
                ),
                "priceSupplies" => array(
                    "helperName" => "he_cek_price_supplies",
                    "functionName" => "cekPriceSupplies",
                    "params" => array(
                        "cabang_id" => "placeID",
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
            2 => array(
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
            3 => array(
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
                "costItems" => array(
                    "costID" => "id",
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
                "costMain" => array(
                    "costID" => "id",
                    "costName" => "nama",
                ),
                "costItems" => array(
                    "costID" => "id",
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
                "costMain" => array(
                    "costID" => "id",
                    "costName" => "nama",
                ),
                "costItems" => array(
                    "costID" => "id",
                    "costName" => "nama",
                    "costNilai" => "nilai",
                ),
            ),
        ),
        "pairRegistries" => array(
            "main",
        ),
        "previewCtr" => "Create",
    ),
    "7764" => array(
        "phase" => "phase_4",
        "icon" => "fa fa-cube",
        "transaksiMode" => "forward",
        "label" => "PRODUKSI PH-4",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "REQUEST PH-4",
                "actionLabel" => "request produksi",
                "source" => "",
                "target" => "7764r",
                "userGroup" => "c_produksi",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
                "subplace" => "warehouse_ng",
            ),
            2 => array(
                "label" => "PRODUK WIP PH-4",
                "actionLabel" => "prosess",
                "source" => "7764r",
                "target" => "7764a",
                "userGroup" => "c_produksi_spv",
                "stateLabel" => "prosess",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                "allowEdit" => true,
                "allowIncrement" => false,
                "subplace" => "warehouse_ng",
            ),
            3 => array(
                "label" => "QC PH-4",
                "actionLabel" => "approve & selesai",
                "source" => "7764a",
                "target" => "7764",
                "userGroup" => "c_produksi_spv",
                "stateLabel" => "receive on warehouse",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                "subplace" => "warehouse_ng",
                "allowEdit" => true,
                "allowIncrement" => false,
            ),
        ),
        "template" => "template/transaksi_nopihak_phase.html",
        "selectorModel" => "MdlProdukRakitanPhase4",
        "selectorSrcModel" => "MdlProdukRakitanPhase4",
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

        "selectorProject" => "_selectorProject/select",
        "projectProcessor" => "_processProject/select",

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
            ),
            2 => array(
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "satuan",
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
        "shoppingCartNumFields" => array(
            1 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
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
            1 => "jml*hpp",
            2 => "jml*hpp",
            3 => "jml*hpp",
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
                        "jenis" => ".jadi_ph3",
                        "gudang_id" => ".7003",
                        "state" => ".active",
                        "transaksi_id" => "masterIDPrev",
                    ),
                    "gate" => "items2_sum",
                ),
                "priceSupplies" => array(
                    "helperName" => "he_cek_price_supplies",
                    "functionName" => "cekPriceSupplies",
                    "params" => array(
                        "cabang_id" => "placeID",
                    ),
                    "gate" => "items2_sum",
                ),
            ),

            2 => array(
                "stokSupplies" => array(
                    "helperName" => "he_cek_stock_supplies_locker",
                    "functionName" => "cekStockSuppliesLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => ".7000",
                        "state" => ".hold",
                        "transaksi_id" => "masterIDPrev",
                    ),
                    "gate" => "items2_sum",
                ),
                "priceSupplies" => array(
                    "helperName" => "he_cek_price_supplies",
                    "functionName" => "cekPriceSupplies",
                    "params" => array(
                        "cabang_id" => "placeID",
                    ),
                    "gate" => "items2_sum",
                ),
            ),

            3 => array(
                "stokSupplies" => array(
                    "helperName" => "he_cek_stock_supplies_locker",
                    "functionName" => "cekStockSuppliesLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => ".7001",
                        "state" => ".active",
                        "transaksi_id" => "masterIDPrev",
                    ),
                    "gate" => "items2_sum",
                ),
                "priceSupplies" => array(
                    "helperName" => "he_cek_price_supplies",
                    "functionName" => "cekPriceSupplies",
                    "params" => array(
                        "cabang_id" => "placeID",
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
            2 => array(
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
            3 => array(
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
                "costMain" => array(
                    "costID" => "id",
                    "costName" => "nama",
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
                "costMain" => array(
                    "costID" => "id",
                    "costName" => "nama",
                ),
            ),
        ),
        "pairRegistries" => array(
            "main",
        ),
        "previewCtr" => "Create",
    ),
    "7765" => array(
        "phase" => "phase_5",
        "transaksiMode" => "forward",
        "icon" => "fa fa-cube",
        "label" => "PRODUKSI PH-5",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "REQUEST PH-5",
                "actionLabel" => "request produksi",
                "source" => "",
                "target" => "7765r",
                "userGroup" => "c_produksi",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
                "subplace" => "warehouse_ng",
            ),
            2 => array(
                "label" => "PRODUK WIP PH-5",
                "actionLabel" => "prosess",
                "source" => "7765r",
                "target" => "7765a",
                "userGroup" => "c_produksi_spv",
                "stateLabel" => "prosess",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                "allowEdit" => true,
                "allowIncrement" => false,
                "subplace" => "warehouse_ng",
            ),
            3 => array(
                "label" => "QC PH-5",
                "actionLabel" => "approve & selesai",
                "source" => "7765a",
                "target" => "7765",
                "userGroup" => "c_produksi_spv",
                "stateLabel" => "receive on warehouse",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                "subplace" => "warehouse_ng",
                "allowEdit" => true,
                "allowIncrement" => false,
            ),
        ),
        "template" => "template/transaksi_nopihak_phase.html",
        "selectorModel" => "MdlProdukRakitanPhase5",
        "selectorSrcModel" => "MdlProdukRakitanPhase5",
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

        "selectorProject" => "_selectorProject/select",
        "projectProcessor" => "_processProject/select",

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
            ),
            2 => array(
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "satuan",
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
        "shoppingCartNumFields" => array(
            1 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
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
            1 => "jml*hpp",
            2 => "jml*hpp",
            3 => "jml*hpp",
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
            2 => array(
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
                        "cabang_id" => "placeID",
                    ),
                    "gate" => "items2_sum",
                ),
            ),
        ),
        "pairInjectors" => array(
            2 => array(
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
                "costMain" => array(
                    "costID" => "id",
                    "costName" => "nama",
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
                "costMain" => array(
                    "costID" => "id",
                    "costName" => "nama",
                ),
            ),
        ),
        "pairRegistries" => array(
            "main",
        ),
        "previewCtr" => "Create",
    ),
    //endregion fase assembling
);