<?php

$config["coTransaksiLayout"] = array(

    "582" => array(
        "receiptTemplate" => array(
            1 => "template/582spo.html",
            2 => "template/582so.html",
            3 => "template/582pkd.html",
            4 => "template/582spd.html",
            5 => "template/582.html",
        ),
        "headerNota" => array(
            "customer" => array(
                "customers_nam" => "name",
                "alamat_1" => "address",
                "tlp_1" => "phone",
                "tlp_2" => "handphone",
                "fax" => "fax",
            ),
            "delivery address" => array(
                "dtime" => "date",
                "customers_nama" => "Customer",
                "tlp_1" => "phone",
                "alamat_1" => "address",
                "dtime_jatuh_tempo" => "jatuh tempo",
                "pembayaran" => "payment method",
                "alias" => "attn",

            ),
            "purchase order" => array(
                "nomer" => "receipt no.",
                "currency" => "currency",
                "delivery_date" => "delivery date",
                "top" => "term of payment",
                "tos" => "term of shipment",
                "capacity" => "address",
            ),
        ),
        "customButton" => array(
            1 => array(
                1 => array(
                    "label" => "Export SO",
                    "target" => "ExcelWriter/exp/",
                ),
                // 2 => array(
                //     "label" => "Export SO Browwwww",
                //     "target" => "ExcelWriter/exp/",
                // ),
            ),
            2 => array(
                1 => array(
                    "label" => "Export APP SO",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
            3 => array(
                1 => array(
                    "label" => "Export PRE PACKING",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
            4 => array(
                1 => array(
                    "label" => "Export PACKING LIST",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
            5 => array(
                1 => array(
                    "label" => "Export INVOICE",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
        ),
        "elementFixedNumberSO" => array(
            1 => array(
                "nomer" => "No",
            ),
            2 => array(
                "nomer" => "",
            ),

            3 => array(
                "nomer" => "No",
            ),
            4 => array(
                "nomer" => "No",
            ),
            5 => array(
                "nomer" => "INV No",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "customerDetails_alamat_1" => "Billing Address",
                "customerDetails_nama" => "PIC name",
                "customerDetails_tlp_1" => "Phone",
                "customerDetails_tlp_2" => "Handphone",
                "customerDetails_email" => "Email",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment Method",
                "shippingDate_value" => "Delivery Date",
                "shippingService_name" => "shipping service",
                "transaksi_jenis2_label" => "Paket",
            ),
            2 => array(
                "nomer" => "No",
                "nomer_top" => "SO No.",
                "dtime" => "Date",
                "customerDetails_alamat_1" => "Billing address",
                "customerDetails_nama" => "PIC name",
                "customerDetails_tlp_1" => "Phone",
                "customerDetails_tlp_2" => "Handphone",
                "customerDetails_email" => "Email",
                //                "customerDetails_npwp" => "Tax ID/NPWP",
                "paymentMethod_name" => "Payment Method",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                "top_nama" => "Term of Payment",
                //                "dueDate_value" => "Due Date",
                "shippingDate_value" => "Delivery Date",
                "shippingService_name" => "shipping service",
                "transaksi_jenis2_label" => "Paket",
            ),
            3 => array(
                "nomer" => "No",
                "nomer_top" => "SO No.",
                "shippingDate_value" => "Delivery Date",
                "shippingService_name" => "shipping service",

                "tos_nama" => "Term of Shipment",
                "keterangan" => "Remark",
                //                "top_nama" => "Term of Payment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
                //                "dtime" => "Date",
                "transaksi_jenis2_label" => "Paket",
            ),
            4 => array(
                "nomer" => "No",
                "nomers_prev" => "PRE-PL No",
                "nomer_top" => "SO No",
                "dtime" => "Packing list date",
//                "shippingDate_value" => "Delivery Date",

                "tos_nama" => "Term of Shipment",
                "keterangan" => "Remark",
                "description_additional" => "Note",

                //                "shippingService_name" => "shipping service",
                "transaksi_jenis2_label" => "Paket",
            ),
            5 => array(
                "nomer" => "INV No",
                "nomers_prev" => "PL No",
                "nomer_top" => "SO No",
                "dtime" => "Date",
                "paymentMethod_name" => "Payment Method",
                "dueDate_value" => "Due Date",
                "shippingService_name" => "shipping service",
                //                "shippingService_name" => "shipping service",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "shippingDate_value" => "Delivery Date",
                "transaksi_jenis2_label" => "Paket",
            ),
        ),
        "hideFixedElements" => array(
            5 => array(
                array(
                    "key" => "paymentMethod_name",
                    "keyResult" => array("cash", "cash in advance"),
                    "label" => array(
                        "dueDate_value" => "Due Date",
                    ),
                ),
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails__nama",
                    //                "caption_department" => "",
                ),
            ),
            2 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            4 => array(
                "customer" => array(
                    "label" => "Receipt",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_kode" => "product no",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "cabang_nama" => "cabang",
            "pihakMainName" => "pengiriman",
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "customers_nama" => "customer",
            "dtime" => "date",
            "transaksi_jenis2" => "type of sales",
            "transaksi_jenis2_label" => "type of product",
        ),
        "subAmountValue" => array(
            1 => "jml*(harga-disc)",//nett2
            2 => "jml*(harga-disc)",
            3 => "jml",
            4 => "jml",
            5 => "jml*nett1",
//            5 => "jml*(harga-disc)",
        ),
        "receipNumFields" => array(
            1 => array(
                "nett1" => "Price",
                //                "disc" => "disc",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
            2 => array(

                "nett1" => "Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
            3 => array(
                "stok" => "Stok available",
                "stok_center" => "Stok dc",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            4 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            5 => array(
                //                "harga" => "price",
                "nett1" => "Price",
                //                "ppn" => "VAT",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "price",
                //                "disc" => "disc",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "ppn" => "VAT",
            ),
            2 => array(
                "stok_center" => "stok dc",
                "stok" => "stok available",
                "harga" => "price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "premi_percent" => "premi%",
                "premi" => "premi",
                "nett1" => "price(net)",
            ),
            3 => array(
                "stok_center" => "stok dc",
                "stok" => "stok available",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            4 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            5 => array(
                //                "harga" => "price",
                "nett1" => "price",
                //                "ppn" => "VAT",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                "id" => "PID",
                "produk_kode" => "Product code",
                "no_part" => "part number",
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),
            2 => array(
                "id" => "PID",
                "produk_kode" => "Product code",
                "no_part" => "part number",
                "produk_nama" => "Description",
//                "stok_center" => "Stok dc",
//                "stok" => "Stok<br>available",
                "produk_ord_jml" => "Qty",
                //                "satuan" => "uom",
            ),
            3 => array(
                "id" => "PID",
                "produk_kode" => "Product code",
                "no_part" => "part number",
                "produk_nama" => "Description",
                "berat_new" => "W(KG)",
                "volume_new" => "CBM",
                "max_jml" => "SO",
                "req_cancel_jml" => "cancel request",
                "cancel_jml" => "dicancel",
                "packed_jml" => "dipacking",
                "sent_jml" => "dikirim",
                "produk_ord_jml" => "Qty",
                "sub_berat_new" => "Sub Berat",
//                "sub_berat_gross"  => "Sub Berat",
//                "satuan" => "uom",
                "sub_volume_new" => "Sub Volume",
//                "sub_volume_gross" => "Sub Volume",
            ),
            4 => array(
                "id" => "PID",
                "produk_ord_jml" => "Qty (Pcs)",
                "produk_kode" => "Product code",
                "no_part" => "part number",
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
                "produk_kode" => "Product code",
                "no_part" => "part number",
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "nett1" => "amount",
                //                "disc" => "disc",
                "ongkir_ui" => "Shipping Service",
                //                "grand_total" => "total amount",
//                "grand_total_ui" => "Total Amount",
                "nilai_pembulatan" => "pembulatan",
                "nett1_bulat" => "Total Amount(excl vat)",
//                "grand_ppn" => "VAT",
                "ppn_out_bulat" => "VAT",
                //                "dp" => "DOWNPAYMENT",
//                "new_net3" => "Grand Total",
                "grand_pembulatan" => "Grand Total",
            ),
            2 => array(
                //                "nett1" => "amount",
                //                "disc" => "disc",
                "ongkir_ui" => "Shipping Service",
                //                "grand_total" => "total amount",
//                "grand_total_ui" => "Total Amount",
                "nilai_pembulatan" => "pembulatan",
                "nett1_bulat" => "Total Amount(excl vat)",
//                "grand_ppn" => "VAT",
                "ppn_out_bulat" => "VAT",
                //                "dp" => "DOWNPAYMENT",
//                "new_net3" => "Grand Total",
                "grand_pembulatan" => "Grand Total",
            ),
            3 => array(

                "berat_new" => "Berat",
                "volume_new" => "Volume",
                //                "harga" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "total",
            ),
            4 => array(
                //                "harga" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "total",
                //                "shipping_service" => "shipping service",
            ),
            5 => array(
                //                "nett1" => "amount",
                "ongkir" => "Shipping Service",
                "new_net1" => "Amount",
                //                "new_net2" => "grand total",
                "dp_value" => "Downpayment",
                "dp_ppn_value" => "Dp Vat",
//                "total_ui" => "Sub Amount",
                "nilai_pembulatan" => "pembulatan",
                "total_ui" => "total Amount(excl vat)",
                "new_grand_ppn" => "VAT ",
                "tagihan" => "Grand Total",
            ),

        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",
        ),
        "receiptAddDpp" => array(
            1 => array(
                "ppn_out_bulat" => array(
                    "dpp_pengganti" => "Tax Basis"
                ),
            ),
            2 => array(
                "ppn_out_bulat" => array(
                    "dpp_pengganti" => "Tax Basis"
                ),
            ),
            3 => array(),
            4 => array(),
            5 => array(
                "ppn_out_bulat" => array(
                    "dpp_pengganti" => "Tax Basis"
                ),
            ),

        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
            3 => array("size" => "normal"),
            4 => array("size" => "normal"),
            5 => array("size" => "normal"),
        ),
        "staticFooter" => array(
            2 => "SAN/F/SA001/R00",
            3 => "SAN/F/LOG001/R00",
            4 => "SAN/F/LOG001/R00",
            5 => "SAN/F/FA005/R00",
        ),
        "staticNotes" => array(
            3 => "",
            5 => "true",
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "grand_pembulatan",),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "new_net3",),
            ),
            "3" => array(),
            "4" => array(),
            "5" => array(
                "in_word" => array("inWordInd" => "grand_pembulatan",),
            ),
        ),
        "reviewDetailCompactListsLabel" => array(
            "produk_kode" => "part no",
            "nama" => "product name",
            "harga" => "unit price",
            "harganppn" => "unit price + ppn",
            "disc_percent" => "unit disc (%)",
            "disc" => "unit disc",
            "qty" => "qty",
            "sub_harga" => "sub bruto",
            "sub_disc" => "sub diskon",
            "sub_nett1" => "sub netto",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "customerDetails__alamat_1" => "address",
            "customerDetails__tlp_1" => "phone",
            "customerDetails__tlp_2" => "handphone",
            "customerDetails__npwp" => "npwp",
            "billingDetails__nik" => "nik",
            "valas_nama" => "currency",
        ),
        "reviewCompactListDetailSum" => array(
            "qty" => "qty",
            "jual" => "jual",
            "disc" => "disc",
            "nett1" => "grand total",
        ),
        "fixedFieldHoldConsolidate" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "addFields" => "sales",
                "fields" => array(
                    "cabang_nama" => "cabang",
                    "nomer_top" => "nomer",
                    "nomer" => "nomer otorisasi",
                    "dtime" => "approved",
                    // "seller_nama" => array(
                    //     "step" => 1,
                    //     "key" => "olehName",
                    //     "label" => "salesman",
                    // ),
                    "seller_nama" => "salesman",
                    "oleh_nama" => "approval",
                    "customers_nama" => "customer",
                    // "outstanding_nilai_items" => "nilai",
                    "outstanding_items" => "detail items*",
                    "sub_outstanding_items" => "nilai",
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(),
                "items" => array(
                    "outstanding_items" => array(
                        "nett1",
                    ),
                ),
            ),
            "produk" => array(

                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    //                    "no" =>"No",
                    "cabang_nama" => "cabang",
                    "produk_nama" => "product",
                    "produk_kode" => "product_no",
                    "customers_nama" => "customers nama",
                    "nomer_top" => "Transaksi",
                    "ord_qty" => "Order",
                    "ord_sent_qty" => "Dikirim",
                    "ord_valid_qty" => "Outstanding",
                    "stok" => "Tersedia",
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(
                    "customers_nama" => "customers_nama",
                    "nomer_top" => "nomer_top",
                    "ord_qty" => "produk_ord_jml",
                    "ord_valid_qty" => "valid_qty",
                    "ord_sent_qty" => "produk_ord_jml-valid_qty",
                ),

            ),
            "customer" => array(
                "cabang_nama" => "cabang",
                "label" => "customer",
                "target" => "customer",
                "srcKey" => "customers_id",
                "fields" => array(
                    "customers_nama" => "Customer",
                    "nomer_top" => "Transaksi SO",
                    // "transaksi_nilai" => "nilai",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode" => "produk kode",
                    "produk_ord_jml" => "order",
                    "ord_sent_qty" => "dikirim",
                    "ord_valid_qty" => "<span class='text-red'>Outstanding</span>",
                ),
                "loop" => array(
                    "nomer_top" => "nomer_top",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode" => "produk_kode",
                    "produk_ord_jml" => "produk_ord_jml",
                    "ord_sent_qty" => "produk_ord_jml-valid_qty",
                    "ord_valid_qty" => "valid_qty",
                ),
                "array_flip" => array(
                    1,
                ),
            ),

        ),
        "reviewCompactListSum" => array(
            "shipping_service" => "shipping service",
            "grand_total_ui" => "total amount",
            "grand_ppn" => "VAT 11%",
            "new_net3" => "grand total",
        ),
        "reviewAddRows" => array(
            "top__nama" => "pembayaran",
            "dp" => "downpayment",
            "paymentMethod" => "paymentMethod",
        ),
        "reviewSign" => array(
            1 => array(
                "sign_1",
            ),
            2 => array(
                "sign_1",
                "sign_2",
            ),
        ),
        "fixedFieldHold" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "fields" => array(
                    "nomer_top" => "nomer",
                    "dtime" => "approved",
                    "seller_nama" => array(
                        "step" => 1,
                        "key" => "olehName",
                        "label" => "salesman",
                    ),
                    "oleh_nama" => "approval",
                    "customers_nama" => "customer",
//                    "transaksi_nilai" => "nilai",
                    "outstanding_items" => "detail items*",
                    "sub_outstanding_items" => "nilai",
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(),
                "items" => array(
                    "outstanding_items" => array(
                        "nett1",
                    ),
                ),
            ),
            "produk" => array(
                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    //                    "no" =>"No",
                    "produk_nama" => "product",
                    "produk_kode" => "product_no",
                    "customers_nama" => "customers nama",
                    "nomer_top" => "Transaksi",
                    "ord_qty" => "Order",
                    "ord_sent_qty" => "Dikirim",
                    "ord_valid_qty" => "Outstanding",
                    "stok" => "Tersedia",
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(
                    "customers_nama" => "customers_nama",
                    "nomer_top" => "nomer_top",
                    "ord_qty" => "produk_ord_jml",
                    "ord_valid_qty" => "valid_qty",
                    "ord_sent_qty" => "produk_ord_jml-valid_qty",
                ),

            ),
            "customer" => array(
                "label" => "customer",
                "target" => "customer",
                "srcKey" => "customers_id",
                "fields" => array(
                    "customers_nama" => "Customer",
                    "nomer_top" => "Transaksi SO",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode" => "produk kode",
                    "produk_ord_jml" => "order",
                    "ord_sent_qty" => "dikirim",
                    "ord_valid_qty" => "<span class='text-red'>Outstanding</span>",
                ),
                "loop" => array(
                    "nomer_top" => "nomer_top",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode" => "produk_kode",
                    "produk_ord_jml" => "produk_ord_jml",
                    "ord_sent_qty" => "produk_ord_jml-valid_qty",
                    "ord_valid_qty" => "valid_qty",
                ),
                "array_flip" => array(
                    1,
                ),
            ),

        ),
        "print_nvalas" => false,
        "print_lable" => array(
            "steps" => array(
                1 => array(
                    "label" => "pre order",
                    "labelPre" => "invoice",
                ),
            ),
        ),
        // "printException" => array(
        //     5 => "bulat",
        // ),
        "print_hitung" => array(
            5 => false,
        ),
        "print_hitung_itemRecap" => array(
            5 => array(
                "nett1" => "jml*nett1",
            ),
        ),
        "print_hitung_mainReplacer" => array(
            5 => array(
                "ongkir" => "ongkir",
                "new_net1" => "nett1+ongkir",
//                "dp_value" => "dp_value",
//                "dp_ppn_value" => "dp_ppn_value",
//                "total_ui" => "total_ui",
                "nett1_bulat" => "new_net1",
                "ppn_out_bulat" => "ongkir_ppn+(10/100*nett1)-dp_ppn_value",
                "ppn_net" => "ppn",
//                "tagihan" => "new_net1+ppn_out_bulat-dp-nilai_cia",
                "tagihan" => "new_net1+ppn_net-dp-nilai_cia",
                "grand_pembulatan" => "grand_pembulatan",
            ),
        ),
        "print_hitung_unsetSumFields" => array(
            5 => array(
                "nilai_pembulatan",
                "nett1_bulat",
            ),
        ),
        "print_hitung_roundDown" => array(
            5 => array(
                "ppn_out_bulat",
                "tagihan",
            ),
        ),

        "receiptElementInjector" => array(
            "source" => array(
                "element" => "customerDetails",
                "fields" => array(
                    "nama" => "customer_nama",
//                    "tlp_1" => "customer_tlp",
//                    "npwp" => "customer_npwp",
                ),
                "usedFields" => array(
                    "customer_nama" => "Customer",
                ),
            ),
            "target" => array(
                "element" => "deliveryDetails",
            ),
        ),
        "showCabangInvoice" => array(
            1 => true,
            2 => true,
            3 => true,
            4 => true,
            5 => false,
        ),
    ),
    "982" => array(
        "receiptTemplate" => array(
            1 => "template/982r.html",
            2 => "template/982g.html",
            3 => "template/982.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "customers_nama" => "Customer",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "shippingDate_value" => "Delivery Date",
                "top_nama" => "Term of Payment",
                "tos_nama" => "Term of Shipment",
                "capacity_nama" => "Capacity",
                "dueDate_value" => "Due Date",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "shippingDate_value" => "Delivery Date",
                "top_nama" => "Term of Payment",
                "tos_nama" => "Term of Shipment",
                "capacity_nama" => "Capacity",
                "dueDate_value" => "Due Date",
            ),
            3 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "shippingDate_value" => "Delivery Date",
                "top_nama" => "Term of Payment",
                "tos_nama" => "Term of Shipment",
                "capacity_nama" => "Capacity",
                "dueDate_value" => "Due Date",
            ),
            4 => array(
                "nomer" => "No",
                "nomer_top" => "SO No.",
                "dtime" => "Date",
                "shippingDate_value" => "Delivery Date",
                //                "top_nama" => "Term of Payment",
                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
            ),
            5 => array(
                "nomer" => "No",
                "nomer_top" => "SO No.",
                "dtime" => "Date",
                "shippingDate_value" => "Delivery Date",
                //                "top_nama" => "Term of Payment",
                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
            ),
            6 => array(
                "nomer" => "INV No",
                "nomer_top" => "SO No.",
                "dtime" => "Date",
                //                "shippingDate_value" => "Delivery Date",
                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                "dueDate_value" => "Due Date",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            2 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            3 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "cabang_nama" => "cabang",
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "customers_nama" => "customer",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_kode" => "part name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            2 => array(
                "produk_nama" => "product name",
                "produk_kode" => "part name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
                //            "ppn" => "ppn",
            ),
            3 => array(
                "produk_nama" => "product name",
                "produk_kode" => "part name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
                //            "ppn" => "ppn",
            ),
        ),
        "receipNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
            2 => array(
                "harga" => "Unit Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
            3 => array(
                "harga" => "Unit Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "Price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
            2 => array(
                "harga" => "Price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
            3 => array(
                "harga" => "Price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "Amount",
                "disc" => "DISC",
                "ppn" => "VAT",
                "nett2" => "Grand Total",
            ),
            2 => array(
                "harga" => "Amount",
                "disc" => "DISC",
                "ppn" => "VAT",
                "nett2" => "Grand Total",
            ),
            3 => array(
                "harga" => "Amount",
                "disc" => "DISC",
                "ppn" => "VAT",
                "nett2" => "Grand Total",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",

        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
            3 => array("size" => "normal"),
        ),
        //        "receiptInword" => array(
        //            "in_word" => array("inWordInd" => "nett2",),
        //
        //        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "nett2"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "nett2"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "nett2"),
            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                "sub_harga" => "Total Price",
            ),
            2 => array(
                "sub_harga" => "Total Price",
            ),
            3 => array(
                "sub_harga" => "Total Price",
            ),
        ),

        "reviewDetailCompactListsLabel" => array(
            "produk_kode" => "part no",
            "nama" => "product name",
            "harga" => "unit price",
            "harganppn" => "unit price + ppn",
            "disc_percent" => "unit disc (%)",
            "disc" => "unit disc",
            "qty" => "qty",
            "sub_harga" => "sub bruto",
            "sub_disc" => "sub diskon",
            "sub_nett1" => "sub netto",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "customerDetails__alamat_1" => "address",
            "customerDetails__tlp_1" => "phone",
            "customerDetails__tlp_2" => "handphone",
            "customerDetails__npwp" => "npwp",
            "billingDetails__nik" => "nik",
            "valas_nama" => "currency",
        ),
        "reviewCompactListDetailSum" => array(
            "qty" => "qty",
            "jual" => "jual",
            "disc" => "disc",
            "nett1" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "shipping_service" => "shipping service",
            "grand_total_ui" => "total amount",
            "grand_ppn" => "VAT 10%",
            "new_net3" => "grand total",
        ),
        "reviewAddRows" => array(
            "top__nama" => "pembayaran",
            "dp" => "downpayment",
            "paymentMethod" => "paymentMethod",
        ),
        "reviewSign" => array(
            1 => array(
                "sign_1",
            ),
            2 => array(
                "sign_1",
                "sign_2",
            ),
        ),
    ),
    //export
    "382" => array(
        "receiptTemplate" => array(
            1 => "template/582spo.html",
            2 => "template/582so.html",
            3 => "template/582pkd.html",
            4 => "template/582spd.html",
            5 => "template/382.html",
        ),
        "headerNota" => array(
            "customer" => array(
                "customers_nam" => "name",
                "alamat_1" => "address",
                "country_label" => "country",
                "tlp_1" => "phone",
                "tlp_2" => "handphone",
                "fax" => "fax",
            ),
            "delivery addrress" => array(
                "dtime" => "date",
                "suppliers_nama" => "Supplier",
                "tlp_1" => "phone",
                "alamat_1" => "address",
                "country_label" => "country",
                "dtime_jatuh_tempo" => "due date",
                "pembayaran" => "payment method",
                "alias" => "attn",

            ),
            "purchase order" => array(
                "nomer" => "receipt no.",
                "currency" => "currency",
                "delivery_date" => "delivery date",
                "top" => "term of payment",
                "tos" => "term of shipment",
                "capacity" => "address",
            ),

        ),
        "elementFixedNumberSO" => array(
            1 => array(
                "nomer" => "No",
            ),
            2 => array(
                "nomer" => "",
            ),

            3 => array(
                "nomer" => "No",
            ),
            4 => array(
                "nomer" => "No",
            ),
            5 => array(
                "nomer" => "INV No",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "customerDetails_alamat_1" => "Billing address",
                "customerDetails_nama" => "PIC name",
                "customerDetails_tlp_1" => "Phone",
                "customerDetails_tlp_2" => "Handphone",
                "customerDetails_email" => "Email",
                "top_nama" => "Term of Payment",
                "shippingDate_value" => "Delivery Date",
            ),
            2 => array(
                //                "nomer" => "No",
                "dtime" => "Date",
                "customerDetails_alamat_1" => "Billing address",
                "customerDetails_nama" => "PIC name",
                "customerDetails_tlp_1" => "Phone",
                "customerDetails_tlp_2" => "Handphone",
                "customerDetails_email" => "Email",
                //                "customerDetails_npwp"     => "Tax ID/NPWP",
                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value"            => "Due Date",
                "shippingDate_value" => "Delivery Date",
            ),

            3 => array(
                "nomer" => "No",
                "shippingDate_value" => "Delivery Date",
                "nomer_top" => "SO No.",
                "tos_nama" => "Term of Shipment",
                "keterangan" => "Remark",
                //                "top_nama" => "Term of Payment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
                //                "dtime" => "Date",
            ),
            4 => array(
                "nomer" => "No",
                "shippingDate_value" => "Delivery Date",
                "nomer_top" => "SO No.",
                "tos_nama" => "Term of Shipment",
                "keterangan" => "Remark",
                //                "top_nama" => "Term of Payment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
                //                "dtime" => "Date",
            ),
            5 => array(
                "nomer" => "INV No",
                "nomer_top" => "SO No.",
                "dtime" => "Date",
                "top_nama" => "Term of Payment",
                //                "dueDate_value" => "Due Date",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "shippingDate_value" => "Delivery Date",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            2 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),

        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "cabang_nama" => "cabang",
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "customers_nama" => "customer",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_kode" => "product no",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "sub_nett2_valas" => "sub-total"
            ),
            2 => array(
                "produk_nama" => "product name",
                "produk_kode" => "product no",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
            ),

            3 => array(
                "produk_nama" => "product name",
                "produk_kode" => "product no",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                "berat_gross" => "weight",
                "volume_gross" => "volume",
            ),
            4 => array(
                "produk_nama" => "product name",
                "produk_kode" => "product no",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                "berat_gross" => "weight",
                "volume_gross" => "volume",
            ),
            5 => array(
                "produk_nama" => "product name",
                "produk_kode" => "product no",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
            ),
        ),
        "receipNumFields" => array(
            1 => array(
                "nett1_valas" => "price",
                "sub_nett1_valas" => "sub-total",
                //                "disc" => "disc",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
            2 => array(
                //                "stok" => "stok",
                "nett1_valas" => "price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
                "sub_nett1_valas" => "sub-total",
            ),
            3 => array(
                "stok" => "stok",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            4 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            5 => array(
                //                "harga" => "price",
                "nett1_valas" => "price",
                //                "ppn" => "VAT",
                "sub_nett1_valas" => "sub-total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                //                "harga" => "price",
                "valas_nilai" => "price",
                "disc_percent" => "disc (%)",
                "disc_valas" => "disc",
                "sub_harga_valas" => "sub-total"
                //                "ppn" => "VAT",
            ),
            2 => array(
                "stok_center" => "stok dc",
                "stok" => "stok available",
                "valas_nilai" => "price",
                "disc_percent" => "disc (%)",
                "disc_valas" => "disc",
                "sub_harga_valas" => "sub-total",
            ),

            3 => array(
                "stok_center" => "stok dc",
                "stok" => "stok available",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            4 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            5 => array(
                "valas_nilai" => "price",
                "disc_percent" => "disc (%)",
                "disc_valas" => "disc",
                "sub_harga_valas" => "sub-total",

            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "nett2_valas" => "total",
                "ongkir" => "shipping service",
                "grand_total_valas" => "grand total",
            ),
            2 => array(
                "nett2_valas" => "total",
                "ongkir" => "shipping service",
                "grand_total_valas" => "grand total",
            ),

            3 => array(
                //                "harga" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "total",
            ),
            4 => array(
                //                "harga" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "total",
            ),
            5 => array(
                "nett2_valas" => "total amount",
                "ppn" => "vat (0%)",
                "ongkir" => "shipping service",
                "grand_total_valas" => "grand total",
            ),
        ),
        "receiptSumFieldsZeroAllowed" => array(
            5 => array(
                "ppn",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",

        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
            3 => array("size" => "normal"),
            4 => array("size" => "normal"),
            5 => array("size" => "normal"),
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordEng" => "grand_total_valas",),
                "currency_id" => "valasDetails",
            ),
            "2" => array(
                "in_word" => array("inWordEng" => "grand_total_valas",),
                "currency_id" => "valasDetails",
            ),
            "3" => array(
                "in_word" => array("inWordEng" => "grand_total_valas",),
                "currency_id" => "valasDetails",
            ),
            "4" => array(),
            "5" => array(
                "in_word" => array("inWordEng" => "grand_total_valas",),
                "currency_id" => "valasDetails",
            ),
        ),
        "print_nvalas" => true,
        "staticFooter" => array(
            3 => "SAN/F/LOG001/R00",
            5 => "SAN/F/FA005/R00",
        ),
        "staticNotes" => array(
            3 => "",
            5 => "true",
        ),

        "fixedFieldHold" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "fields" => array(
                    "nomer_top" => "nomer",
                    "dtime" => "approved",
                    "oleh_nama" => "salesman",
                    "customers_nama" => "customer",
                    "print_label" => "tool",
                ),
                "loop" => array(),
            ),
            "produk" => array(
                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    //                    "no" =>"No",
                    "produk_nama" => "product",
                    "produk_kode" => "product_no",
                    "customers_nama" => "customers nama",
                    "nomer_top" => "Transaksi",
                    "ord_qty" => "Order",
                    "ord_sent_qty" => "Dikirim",
                    "ord_valid_qty" => "Outstanding",
                    "avail_qty" => "Tersedia",
                    "print_label" => "tool",

                ),
                "loop" => array(
                    "customers_nama" => "customers_nama",
                    "nomer_top" => "nomer_top",
                    "ord_qty" => "produk_ord_jml",
                    "ord_valid_qty" => "valid_qty",
                    "ord_sent_qty" => "produk_ord_jml-valid_qty",
                ),

            ),
            "customer" => array(
                "label" => "customer",
                "target" => "customer",
                "srcKey" => "customers_id",
                "fields" => array(
                    "customers_nama" => "Customer",
                    "nomer_top" => "Transaksi SO",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode" => "produk kode",
                    "produk_ord_jml" => "order",
                    "ord_sent_qty" => "dikirim",
                    "ord_valid_qty" => "<span class='text-red'>Outstanding</span>",
                ),
                "loop" => array(
                    "nomer_top" => "nomer_top",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode" => "produk_kode",
                    "produk_ord_jml" => "produk_ord_jml",
                    "ord_sent_qty" => "produk_ord_jml-valid_qty",
                    "ord_valid_qty" => "valid_qty",
                ),
                "array_flip" => array(
                    1,
                ),
            ),

        ),

    ),
    "1982" => array(
        "receiptTemplate" => array(
            1 => "template/982r.html",
            2 => "template/982r.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "customers_nama" => "Customer",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "nomer2" => "No SO",
//                "ids_his"            => "No SO-",
                "dtime" => "Date",
                "shippingDate_value" => "Delivery Date",
                "top_nama" => "Term of Payment",
                "tos_nama" => "Term of Shipment",
                "capacity_nama" => "Capacity",
                "dueDate_value" => "Due Date",
            ),
            2 => array(
                "nomer" => "No",
                "nomer2" => "No SO",
                "dtime" => "Date",
                "shippingDate_value" => "Delivery Date",
                "top_nama" => "Term of Payment",
                "tos_nama" => "Term of Shipment",
                "capacity_nama" => "Capacity",
                "dueDate_value" => "Due Date",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "cabang_nama" => "cabang",
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "customers_nama" => "customer",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "Description",
                "produk_kode" => "Product No.",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            2 => array(
                "produk_nama" => "Description",
                "produk_kode" => "Product No.",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
        ),
        "receipNumFields" => array(
            1 => array(
//                "harga" => "Unit Price",
//                                "disc_percent" => "disc (%)",
//                                "disc" => "disc (IDR)",
//                                "ppn" => "VAT",
//                            "avail" => "current stock",
            ),
            2 => array(
//                "harga" => "Unit Price",
//                                "disc_percent" => "disc (%)",
//                                "disc" => "disc (IDR)",
//                                "ppn" => "VAT",
//                            "avail" => "current stock",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
//                "harga" => "Amount",
//                "disc"  => "DISC",
//                "ppn"   => "VAT",
//                "nett2" => "Grand Total",
            ),
            2 => array(
//                "harga" => "Amount",
//                "disc"  => "DISC",
//                "ppn"   => "VAT",
//                "nett2" => "Grand Total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(),
            2 => array(),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
//            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
        ),
        //        "receiptInword" => array(
        //            "in_word" => array("inWordInd" => "nett2",),
        //
        //        ),
        "receiptInword" => array(
            1 => array(//                "in_word" => array("inWordInd" => "nett2"),
            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(//                "sub_harga" => "Total Price",
            ),
        ),
    ),
    // paket
    "1582" => array(
        "receiptTemplate" => array(
            1 => "template/1582spo.html",
            2 => "template/1582spo.html",
        ),
        "headerNota" => array(
            "customer" => array(
                "customers_nam" => "name",
                "alamat_1" => "address",
                "tlp_1" => "phone",
                "tlp_2" => "handphone",
                "fax" => "fax",
            ),
            "delivery addrress" => array(
                "dtime" => "date",
                "suppliers_nama" => "Supplier",
                "tlp_1" => "phone",
                "alamat_1" => "address",
                "dtime_jatuh_tempo" => "jatuh tempo",
                "pembayaran" => "payment method",
                "alias" => "attn",

            ),
            "purchase order" => array(
                "nomer" => "receipt no.",
                "currency" => "currency",
                "delivery_date" => "delivery date",
                "top" => "term of payment",
                "tos" => "term of shipment",
                "capacity" => "address",
            ),

        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "oleh_name" => "",
                //                "customerDetails_alamat_1" => "Billing address",
                //                "top_nama" => "Term of Payment",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                //                "shippingDate_value" => "Consignment notesss",
                "nomer_top" => "Ref No.",
                //                "tos_nama" => "Term of Shipment",
                "keterangan" => "Remark",
            ),

            //            3 => array(
            //                "nomer" => "No",
            //                "shippingDate_value" => "Delivery Date",
            //                "nomer_top" => "SO No.",
            //                "tos_nama" => "Term of Shipment",
            //                "keterangan" => "Remark",
            //                //                "top_nama" => "Term of Payment",
            //                //                "capacity_nama" => "Capacity",
            //                //                "dueDate_value" => "Due Date",
            //                //                "dtime" => "Date",
            //            ),
            //            4 => array(
            //                "nomer" => "No",
            //                "shippingDate_value" => "Delivery Date",
            //                "nomer_top" => "SO No.",
            //                "tos_nama" => "Term of Shipment",
            //                "keterangan" => "Remark",
            //                //                "top_nama" => "Term of Payment",
            //                //                "capacity_nama" => "Capacity",
            //                //                "dueDate_value" => "Due Date",
            //                //                "dtime" => "Date",
            //            ),
            //            5 => array(
            //                "nomer" => "INV No",
            //                "nomer_top" => "SO No.",
            //                "dtime" => "Date",
            //                "top_nama" => "Term of Payment",
            //                "dueDate_value" => "Due Date",
            //                //                "tos_nama" => "Term of Shipment",
            //                //                "capacity_nama" => "Capacity",
            //                //                "shippingDate_value" => "Delivery Date",
            //            ),
            //            6 => array(
            //                "nomer" => "No",
            //                "dtime" => "Date",
            //                "customerDetails_alamat_1" => "Billing address",
            //                "customerDetails_nama" => "PIC name",
            //                "customerDetails_tlp_1" => "Phone",
            //                "customerDetails_tlp_2" => "Handphone",
            //                "customerDetails_email" => "Email",
            //                "customerDetails_npwp" => "Tax ID/NPWP",
            //                "top_nama" => "Term of Payment",
            //                //                "tos_nama" => "Term of Shipment",
            //                //                "capacity_nama" => "Capacity",
            //                "dueDate_value" => "Due Date",
            //                "shippingDate_value" => "Delivery Date",
            //            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            2 => array(
                "customer" => array(
                    "label" => ".Confirmed and received by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),

        ),
        "headerTables" => array(
            "produk_nama" => "item name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "cabang_nama" => "cabang",
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "customers_nama" => "customer",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                //                "produk_nama+produk_ord_jml" => "item name",
                //                "produk_nama"    => "item name",
                "nama" => "item name",
                //                "produk_ord_jml" => "qty",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "produk_nama" => "item name",
                "produk_ord_jml" => "qty",
                "satuan" => "satuan",
                //                "berat_gross" => "berat",
                //                "volume_gross" => "volume",
            ),
        ),
        "receiptDetailFields2" => array(
            1 => array(
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "uom",

                //                "harga_ori"    => "price",
                //                "disc_percent" => "disc(%)",
                //                "disc"         => "disc(idr)",
                //                "ppn"          => "vat",

                "nett1" => "price",

            ),
            2 => array(
                //                "produk_nama" => "item source name",
                //                "produk_ord_jml" => "qty",
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            3 => array(
                //                "produk_nama" => "item source name",
                //                "produk_ord_jml" => "qty",
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "uom",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                //                                "harga_ori" => "amount",
                //                                "disc" => "disc",
                //                "ongkir_ui" => "Shipping Service",
                //                //                "grand_total" => "total amount",
                ////                "grand_total_ui" => "Total Amount",
                //                "nilai_pembulatan" => "pembulatan",
                //                "nett1_bulat" => "Total Amount",
                ////                "grand_ppn" => "VAT",
                //                "ppn_out_bulat" => "VAT",
                //                //                "dp" => "DOWNPAYMENT",
                ////                "new_net3" => "Grand Total",
                //                "grand_pembulatan" => "Grand Total",
            ),
            2 => array(
                //                "harga" => "amount",
                //                "ppn" => "VAT",
                //                "disc" => "discount",
                //                "nett2" => "total",
            ),
        ),
        "receiptAddDpp" => array(
            1 => array(
                "grand_ppn" => array(
                    "dpp_pengganti" => "Tax Basis"
                ),
            ),
            2 => array(
                "grand_ppn" => array(
                    "dpp_pengganti" => "Tax Basis"
                ),
            ),
            3 => array(
                "grand_ppn" => array(
                    "dpp_pengganti" => "Tax Basis"
                ),
            ),

        ),
//        "receiptSumFields2" => array(
//            1 => array(
//                "harga_ori" => "amount",
//                "disc" => "disc",
//                "ongkir_ui" => "Shipping Service",
//                "nilai_pembulatan" => "pembulatan",
//                "nett1_bulat" => "Total Amount",
//                "ppn_out_bulat" => "VAT",
//                "grand_pembulatan" => "Grand Total",
//            ),
//            2 => array(//                "hpp" => "grand total"
//            ),
//            3 => array(//                "hpp" => "grand total"
//            ),
//        ),
        "receiptNumFields" => array(
            1 => array(
                "nett1_bulat" => "price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "ppn_out_bulat" => "VAT",
            ),
            2 => array(
                "nett1_bulat" => "price",
                //                "disc" => "disc",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "ppn_out_bulat" => "VAT",
            ),
            3 => array(
                "stok" => "stok",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            4 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            5 => array(
                //                "harga" => "price",
                "nett1" => "price",
                //                "ppn" => "VAT",
            ),
        ),
        "receipNumFields" => array(
            1 => array(
                "nett1_bulat" => "price",
                //                "disc" => "disc",
                //                "disc_percent"  => "disc (%)",
                //                "disc"          => "disc (IDR)",
                //                "ppn_out_bulat" => "VAT",
            ),
            2 => array(
                "nett1_bulat" => "price",
                //                "disc" => "disc",
                //                "disc_percent"  => "disc (%)",
                //                "disc"          => "disc (IDR)",
                //                "ppn_out_bulat" => "VAT",
            ),
            3 => array(
                "stok" => "stok",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            4 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            5 => array(
                //                "harga" => "price",
                "nett1" => "price",
                //                "ppn" => "VAT",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "staticFooter" => array(
            //            3 => "GROSIR/I/LOG001/R00",
            //            5 => "GROSIR/I/FA005/R00",
        ),
        "allowPrint" => array(
            1 => array("size" => "normal"),
            //            2 => array("size" => "normal"),
        ),
        //        "smallPrint" => array(
        //            1 => array(
        //                "dtime" => "date",
        //                "nomer" => "no nota",
        //                "oleh_nama" => "kasir",
        //
        //            ),
        //        ),
    ),
    //penjaualan jasa
    "584" => array(
        "receiptTemplate" => array(
            1 => "template/582spo.html",
            2 => "template/582so.html",
            3 => "template/582.html",
            //            4 => "template/582spd.html",
            //            5 => "template/582.html",
        ),
        "headerNota" => array(
            "customer" => array(
                "customers_nam" => "name",
                "alamat_1" => "address",
                "tlp_1" => "phone",
                "tlp_2" => "handphone",
                "fax" => "fax",
            ),
            "delivery address" => array(
                "dtime" => "date",
                "customers_nama" => "Customer",
                "tlp_1" => "phone",
                "alamat_1" => "address",
                "dtime_jatuh_tempo" => "jatuh tempo",
                "pembayaran" => "payment method",
                "alias" => "attn",
            ),

            "purchase order" => array(
                "nomer" => "receipt no.",
                "currency" => "currency",
                "delivery_date" => "delivery date",
                "top" => "term of payment",
                "tos" => "term of shipment",
                "capacity" => "address",
            ),

        ),
        "customButton" => array(
            1 => array(
                1 => array(
                    "label" => "Export SO",
                    "target" => "ExcelWriter/exp/",
                ),
                // 2 => array(
                //     "label" => "Export SO Browwwww",
                //     "target" => "ExcelWriter/exp/",
                // ),
            ),
            2 => array(
                1 => array(
                    "label" => "Export APP SO",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
            3 => array(
                1 => array(
                    "label" => "Export PRE PACKING",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
            4 => array(
                1 => array(
                    "label" => "Export PACKING LIST",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
            5 => array(
                1 => array(
                    "label" => "Export INVOICE",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
        ),
        "elementFixedNumberSO" => array(
            1 => array(
                "nomer" => "No",
            ),
            2 => array(
                "nomer" => "",
            ),

            3 => array(
                "nomer" => "No",
            ),
            4 => array(
                "nomer" => "No",
            ),
            5 => array(
                "nomer" => "INV No",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "customerDetails_alamat_1" => "Billing Address",
                "customerDetails_nama" => "PIC name",
                "customerDetails_tlp_1" => "Phone",
                "customerDetails_tlp_2" => "Handphone",
                "customerDetails_email" => "Email",
                //                "customerDetails_npwp" => "Tax ID/NPWP",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment Method",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
                "shippingDate_value" => "Delivery Date",
                "shippingService_name" => "shipping service",
            ),
            2 => array(
                "nomer" => "No",
                "nomer_top" => "SO No.",
                "dtime" => "Date",
                "customerDetails_alamat_1" => "Billing address",
                "customerDetails_nama" => "PIC name",
                "customerDetails_tlp_1" => "Phone",
                "customerDetails_tlp_2" => "Handphone",
                "customerDetails_email" => "Email",
                //                "customerDetails_npwp" => "Tax ID/NPWP",
                "paymentMethod_name" => "Payment Method",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                "top_nama" => "Term of Payment",
                //                "dueDate_value" => "Due Date",
                "shippingDate_value" => "Delivery Date",
                //                "shippingService_name" => "shipping service",
            ),
            3 => array(
                "nomer_top" => "SO No",
                "nomer" => "INV No",
                "dtime" => "Date",
                "paymentMethod_name" => "Payment Method",
                //                "dueDate_value" => "Due Date",
                //                "shippingService_name" => "shipping service",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            2 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            3 => array(
                "customer" => array(
                    "label" => "Receipt",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_kode" => "part number",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "cabang_nama" => "cabang",
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "customers_nama" => "customer",
            "dtime" => "date",
        ),
        "subAmountValue" => array(
            1 => "jml*(harga-disc)",//nett2
            2 => "jml*(harga-disc)",
            3 => "jml*(harga-disc)",
            //            4 => "jml",
            //            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        "receipNumFields" => array(
            1 => array(
                "nett1" => "Price",
                //                "disc" => "disc",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
            2 => array(
                //                "stok" => "stok",
                "nett1" => "Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
            3 => array(
                //                "stok" => "Stok",
                "nett1" => "Price",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            4 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            5 => array(
                //                "harga" => "price",
                "nett1" => "Price",
                //                "ppn" => "VAT",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "price",
                //                "disc" => "disc",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                "ppn" => "VAT",
            ),
            2 => array(
                "harga" => "price",
                //                "disc" => "disc",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                "ppn" => "VAT",
            ),
            3 => array(
                "harga" => "price",
                //                "disc" => "disc",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                "ppn" => "VAT",
            ),

        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_kode" => "Product No.",
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
                //                "satuan" => "UOM",
            ),
            2 => array(
                "produk_kode" => "Product No.",
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
                //                "satuan" => "uom",
            ),

            3 => array(
                "produk_kode" => "Product No.",
                "produk_nama" => "Description",
                //                "berat_new" => "W(KG)",
                //                "volume_new" => "CBM",
                //                "max_jml" => "SO",
                //                "sent_jml" => "Tekirim",
                //                "produk_ord_jml" => "Qty",
                //                "sub_berat_new" => "Sub Berat",
                //                //                "satuan" => "uom",
                //                "sub_volume_new" => "Sub Volume",
            ),
            4 => array(
                "produk_ord_jml" => "Qty (Pcs)",
                "produk_kode" => "Description",
                //                "produk_kode"       => "part number",
                //                "satuan"            => "uom",
                "jml" => "Quantity Per Pkg (Ctns)",
                "berat_new" => "Net/Pkg (Kgs)",
                "sub_berat_new" => "Total (Kgs)",
                "volume_new" => "Net/Pkg (Cbm)",
                "sub_volume_new" => "Total (Cbm)",
            ),
            5 => array(
                "produk_kode" => "Product No.",
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "amount",
                //                "disc" => "disc",
                //                "ongkir_ui" => "shipping service",
                //                "grand_total_ui" => "total amount",
                "grand_ppn" => "vat",
                //                "new_net3" => "total amount",
                //                "pph_net_23" => "pph 23",
                "new_net4" => "Grand Total"
            ),
            2 => array(
                "harga" => "amount",
                //                "disc" => "disc",
                //                "ongkir_ui" => "shipping service",
                //                "grand_total_ui" => "total amount",
                "grand_ppn" => "vat",
                //                "new_net3" => "total amount",
                //                "pph_net_23" => "pph 23",
                "new_net4" => "Grand Total"
            ),

            3 => array(
                "harga" => "amount",
                //                "disc" => "disc",
                //                "ongkir_ui" => "shipping service",
                //                "grand_total_ui" => "total amount",
                "grand_ppn" => "vat",
                //                "new_net3" => "total amount",
                //                "pph_net_23" => "pph 23",
                "new_net4" => "Grand Total"
            ),

        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",

        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
            5 => array("size" => "normal"),
        ),
        "staticFooter" => array(
            2 => "SAN/F/SA001/R00",
            3 => "SAN/F/LOG001/R00",
            5 => "SAN/F/FA005/R00",
        ),
        "staticNotes" => array(
            3 => "",
            5 => "true",
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "new_net4",),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "new_net4",),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "new_net4",),
            ),

        ),
        "reviewDetailCompactListsLabel" => array(
            "produk_kode" => "part no",
            "nama" => "product name",
            "harga" => "unit price",
            "harganppn" => "unit price + ppn",
            "disc_percent" => "unit disc (%)",
            "disc" => "unit disc",
            "qty" => "qty",
            "sub_harga" => "sub bruto",
            "sub_disc" => "sub diskon",
            "sub_nett1" => "sub netto",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "customerDetails__alamat_1" => "address",
            "customerDetails__tlp_1" => "phone",
            "customerDetails__tlp_2" => "handphone",
            "customerDetails__npwp" => "npwp",
            "billingDetails__nik" => "nik",
            "valas_nama" => "currency",
        ),
        "reviewCompactListDetailSum" => array(
            "qty" => "qty",
            "jual" => "jual",
            "disc" => "disc",
            "nett1" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "shipping_service" => "shipping service",
            "grand_total_ui" => "total amount",
            "grand_ppn" => "VAT 10%",
            "new_net3" => "grand total",
        ),
        "reviewAddRows" => array(
            "top__nama" => "pembayaran",
            "dp" => "downpayment",
            "paymentMethod" => "paymentMethod",
        ),
        "reviewSign" => array(
            1 => array(
                "sign_1",
            ),
            2 => array(
                "sign_1",
                "sign_2",
            ),
        ),
        "fixedFieldHold" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "fields" => array(
                    "nomer_top" => "nomer",
                    "dtime" => "approved",
                    "oleh_nama" => "salesman",
                    "customers_nama" => "customer",
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(),
            ),
            "produk" => array(
                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    //                    "no" =>"No",
                    "produk_nama" => "product",
                    "produk_kode" => "product_no",
                    "customers_nama" => "customers nama",
                    "nomer_top" => "Transaksi",
                    "ord_qty" => "Order",
                    "ord_sent_qty" => "Dikirim",
                    "ord_valid_qty" => "Outstanding",
                    "stok" => "Tersedia",
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(
                    "customers_nama" => "customers_nama",
                    "nomer_top" => "nomer_top",
                    "ord_qty" => "produk_ord_jml",
                    "ord_valid_qty" => "valid_qty",
                    "ord_sent_qty" => "produk_ord_jml-valid_qty",
                ),

            ),
            "customer" => array(
                "label" => "customer",
                "target" => "customer",
                "srcKey" => "customers_id",
                "fields" => array(
                    "customers_nama" => "Customer",
                    "nomer_top" => "Transaksi SO",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode" => "produk kode",
                    "produk_ord_jml" => "order",
                    "ord_sent_qty" => "dikirim",
                    "ord_valid_qty" => "<span class='text-red'>Outstanding</span>",
                ),
                "loop" => array(
                    "nomer_top" => "nomer_top",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode" => "produk_kode",
                    "produk_ord_jml" => "produk_ord_jml",
                    "ord_sent_qty" => "produk_ord_jml-valid_qty",
                    "ord_valid_qty" => "valid_qty",
                ),
                "array_flip" => array(
                    1,
                ),
            ),

        ),
        "print_nvalas" => false,
        "print_lable" => array(
            "steps" => array(
                1 => array(
                    "label" => "pre order",
                    "labelPre" => "invoice",
                ),
            ),
        ),
        // "printException" => array(
        //     5 => "bulat",
        // ),
    ),

    "5822" => array(
        "receiptTemplate" => array(
            1 => "template/582spo.html",
            2 => "template/582so.html",
            3 => "template/582pkd.html",
            4 => "template/582spd.html",
            5 => "template/582.html",
        ),
        "headerNota" => array(
            "customer" => array(
                "customers_nam" => "name",
                "alamat_1" => "address",
                "tlp_1" => "phone",
                "tlp_2" => "handphone",
                "fax" => "fax",
            ),
            "delivery address" => array(
                "dtime" => "date",
                "customers_nama" => "Customer",
                "tlp_1" => "phone",
                "alamat_1" => "address",
                "dtime_jatuh_tempo" => "jatuh tempo",
                "pembayaran" => "payment method",
                "alias" => "attn",

            ),
            "purchase order" => array(
                "nomer" => "receipt no.",
                "currency" => "currency",
                "delivery_date" => "delivery date",
                "top" => "term of payment",
                "tos" => "term of shipment",
                "capacity" => "address",
            ),
        ),
        "customButton" => array(
            1 => array(
                1 => array(
                    "label" => "Export SO",
                    "target" => "ExcelWriter/exp/",
                ),
                // 2 => array(
                //     "label" => "Export SO Browwwww",
                //     "target" => "ExcelWriter/exp/",
                // ),
            ),
            2 => array(
                1 => array(
                    "label" => "Export APP SO",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
            3 => array(
                1 => array(
                    "label" => "Export PRE PACKING",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
            4 => array(
                1 => array(
                    "label" => "Export PACKING LIST",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
            5 => array(
                1 => array(
                    "label" => "Export INVOICE",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
        ),
        "elementFixedNumberSO" => array(
            1 => array(
                "nomer" => "No",
            ),
            2 => array(
                "nomer" => "",
            ),

            3 => array(
                "nomer" => "No",
            ),
            4 => array(
                "nomer" => "No",
            ),
            5 => array(
                "nomer" => "INV No",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "customerDetails__alamat_1" => "Billing Address",
                "customerDetails__nama" => "PIC name",
                "customerDetails__tlp_1" => "Phone",
                "customerDetails__tlp_2" => "Handphone",
                "customerDetails__email" => "Email",
                "top__nama" => "Term of Payment",
                "paymentMethod__name" => "Payment Method",
                "shippingDate__value" => "Delivery Date",
                "shippingService__nama" => "shipping service",
                "transaksi_jenis2__label" => "Paket",
                "pihakMainName" => "Pengiriman",
            ),
            2 => array(
                "nomer" => "No",
                "nomer_top" => "SO No.",
                "dtime" => "Date",
                "customerDetails__alamat_1" => "Billing Address",
                "customerDetails__nama" => "PIC name",
                "customerDetails__tlp_1" => "Phone",
                "customerDetails__tlp_2" => "Handphone",
                "customerDetails__email" => "Email",
                "top__nama" => "Term of Payment",
                "paymentMethod__name" => "Payment Method",
                "shippingDate__value" => "Delivery Date",
                "shippingService__nama" => "shipping service",
                "transaksi_jenis2__label" => "Paket",
                "pihakMainName" => "Pengiriman",
            ),
            3 => array(
                "nomer" => "No",
                "nomer_top" => "SO No.",
                "shippingDate__value" => "Delivery Date",
                "shippingService__name" => "shipping service",

                "tos__nama" => "Term of Shipment",
                "keterangan" => "Remark",
                //                "top_nama" => "Term of Payment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
                //                "dtime" => "Date",
                "transaksi_jenis2__label" => "Paket",
            ),
            4 => array(
                "nomer" => "No",
                "nomers_prev" => "PRE-PL No",
                "nomer_top" => "SO No",
                "dtime" => "Packing list date",
                //                "shippingDate_value" => "Delivery Date",

                "tos__nama" => "Term of Shipment",
                "keterangan" => "Remark",
                "description_additional" => "Note",

                //                "shippingService_name" => "shipping service",
                "transaksi_jenis2_label" => "Paket",
            ),
            5 => array(
                "nomer" => "INV No",
                "nomers_prev" => "PL No",
                "nomer_top" => "SO No",
                "dtime" => "Date",
                "paymentMethod__name" => "Payment Method",
                "dueDate__value" => "Due Date",
                "shippingService__name" => "shipping service",
                //                "shippingService_name" => "shipping service",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "shippingDate_value" => "Delivery Date",
                "transaksi_jenis2__label" => "Paket",
            ),
        ),
        "hideFixedElements" => array(
            5 => array(
                array(
                    "key" => "paymentMethod_name",
                    "keyResult" => array("cash", "cash in advance"),
                    "label" => array(
                        "dueDate__value" => "Due Date",
                    ),
                ),
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails__nama",
                    //                "caption_department" => "",
                ),
            ),
            2 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails__nama",
                    //                "caption_department" => "",
                ),
            ),
            4 => array(
                "customer" => array(
                    "label" => "Receipt",
                    "contents" => "customerDetails__nama",
                    //                "caption_department" => "",
                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_kode" => "product no",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "cabang_nama" => "cabang",
            "pihakMainName" => "pengiriman",
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "customers_nama" => "customer",
            "dtime" => "date",
            "transaksi_jenis2" => "type of sales",
            "transaksi_jenis2_label" => "type of product",
        ),
        "subAmountValue" => array(
            1 => "jml*(harga-disc)",//nett2
            2 => "jml*(harga-disc)",
            3 => "jml",
            4 => "jml",
            5 => "jml*nett1",
//            5 => "jml*(harga-disc)",
        ),
        "receipNumFields" => array(
            1 => array(
                "nett1" => "Price",
                "sub_nett1" => "subtotal",
                //                "disc" => "disc",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
            2 => array(

                "nett1" => "Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
            3 => array(
                "stok" => "Stok available",
                "stok_center" => "Stok dc",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            4 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            5 => array(
                //                "harga" => "price",
                "nett1" => "Price",
                //                "ppn" => "VAT",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "price",
                //                "disc" => "disc",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "ppn" => "VAT",
            ),
            2 => array(
                "stok_center" => "stok dc",
                "stok" => "stok available",
                "harga" => "price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "premi_percent" => "premi%",
                "premi" => "premi",
                "nett1" => "price(net)",
            ),
            3 => array(
                "stok_center" => "stok dc",
                "stok" => "stok available",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            4 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            5 => array(
                //                "harga" => "price",
                "nett1" => "price",
                //                "ppn" => "VAT",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                "id" => "PID",
                "produk_kode" => "Product code",
                // "no_part" => "part number", // Hidden - no data (edited by glg, 2025-01-22)
//                "produk_nama" => "Description",
                "nama" => "Description",// kolom yang ditampilkan do nota dari tabel penjualan_transaksi_data_items karena kolom produk_nama=0 (10 maret 2026)
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),
            2 => array(
                "id" => "PID",
                "produk_kode" => "Product code",
                // "no_part" => "part number", // Hidden - no data (edited by glg, 2025-01-22)
//                "produk_nama" => "Description",
                "nama" => "Description",
                //                "stok_center" => "Stok dc",
                //                "stok" => "Stok<br>available",
                "produk_ord_jml" => "Qty",
                //                "satuan" => "uom",
            ),
            3 => array(
                "id" => "PID",
                "produk_kode" => "Product code",
                // "no_part" => "part number", // Hidden - no data (edited by glg, 2025-01-22)
//                "produk_nama" => "Description",
                "nama" => "Description",
                "berat_new" => "W(KG)",
                "volume_new" => "CBM",
                "max_jml" => "SO",
                "req_cancel_jml" => "cancel request",
                "cancel_jml" => "dicancel",
                "packed_jml" => "dipacking",
                "sent_jml" => "dikirim",
                "produk_ord_jml" => "Qty",
                "sub_berat_new" => "Sub Berat",
                //                "sub_berat_gross"  => "Sub Berat",
                //                "satuan" => "uom",
                "sub_volume_new" => "Sub Volume",
                //                "sub_volume_gross" => "Sub Volume",
            ),
            4 => array(
                "id" => "PID",
                "produk_ord_jml" => "Qty (Pcs)",
                "produk_kode" => "Product code",
                // "no_part" => "part number", // Hidden - no data (edited by glg, 2025-01-22)
//                "produk_nama" => "Description",
                "nama" => "Description",
                //                "produk_kode"       => "part number",
                //                "satuan"            => "uom",
                "jml" => "Quantity Per Pkg (Ctns)",
                "berat_new" => "Net/Pkg (Kgs)",
                "sub_berat_new" => "Total (Kgs)",
                "volume_new" => "Net/Pkg (Cbm)",
                "sub_volume_new" => "Total (Cbm)",
            ),
            5 => array(
                "produk_kode" => "Product code",
                // "no_part" => "part number", // Hidden - no data (edited by glg, 2025-01-22)
//                "produk_nama" => "Description",
                "nama" => "Description",
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "nett1" => "amount",
                //                "disc" => "disc",
                "ongkir_ui" => "Shipping Service",
                //                "grand_total" => "total amount",
                //                "grand_total_ui" => "Total Amount",
                "nilai_pembulatan" => "pembulatan",
                "nett1_bulat" => "Total Amount(excl vat)",
                //                "grand_ppn" => "VAT",
                "ppn_out_bulat" => "VAT",
                //                "dp" => "DOWNPAYMENT",
                //                "new_net3" => "Grand Total",
                "grand_pembulatan" => "Grand Total",
            ),
            2 => array(
                //                "nett1" => "amount",
                //                "disc" => "disc",
                "ongkir_ui" => "Shipping Service",
                //                "grand_total" => "total amount",
                //                "grand_total_ui" => "Total Amount",
                "nilai_pembulatan" => "pembulatan",
                "nett1_bulat" => "Total Amount(excl vat)",
                //                "grand_ppn" => "VAT",
                "ppn_out_bulat" => "VAT",
                //                "dp" => "DOWNPAYMENT",
                //                "new_net3" => "Grand Total",
                "grand_pembulatan" => "Grand Total",
            ),
            3 => array(

                "berat_new" => "Berat",
                "volume_new" => "Volume",
                //                "harga" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "total",
            ),
            4 => array(
                //                "harga" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "total",
                //                "shipping_service" => "shipping service",
            ),
            5 => array(
                //                "nett1" => "amount",
                "ongkir" => "Shipping Service",
                "new_net1" => "Amount",
                //                "new_net2" => "grand total",
//                "dp_value" => "Downpayment",
//                "dp_ppn_value" => "Dp Vat",
                //                "total_ui" => "Sub Amount",
                "nilai_pembulatan" => "pembulatan",
                "total_ui" => "total Amount(excl vat)",
//                "new_grand_ppn" => "VAT ",
                "ppn_out_bulat" => "VAT ",
//                "tagihan" => "Grand Total",
                "grand_pembulatan" => "Grand Total",
            ),

        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",
        ),
        "receiptAddDpp" => array(
            1 => array(
                "ppn_out_bulat" => array(
                    "dpp_pengganti" => "Tax Basis"
                ),
            ),
            2 => array(
                "ppn_out_bulat" => array(
                    "dpp_pengganti" => "Tax Basis"
                ),
            ),
            3 => array(
                "grand_ppn" => array(
                    "dpp_pengganti" => "Tax Basis"
                ),
            ),
            4 => array(
                "ppn_out_bulat" => array(
                    "dpp_pengganti" => "Tax Basis"
                ),
            ),
            5 => array(
                "ppn_out_bulat" => array(
                    "dpp_pengganti" => "Tax Basis"
                ),
            ),

        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
            3 => array("size" => "normal"),
            4 => array("size" => "normal"),
            5 => array("size" => "normal"),
        ),
        "staticFooter" => array(
            2 => "SAN/F/SA001/R00",
            3 => "SAN/F/LOG001/R00",
            4 => "SAN/F/LOG001/R00",
            5 => "SAN/F/FA005/R00",
        ),
        "staticNotes" => array(
            3 => "",
            5 => "true",
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "grand_pembulatan",),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "new_net3",),
            ),
            "3" => array(),
            "4" => array(),
            "5" => array(
                "in_word" => array("inWordInd" => "grand_pembulatan",),
            ),
        ),
        "reviewDetailCompactListsLabel" => array(
            "produk_kode" => "part no",
            "nama" => "product name",
            "harga" => "unit price",
            "harganppn" => "unit price + ppn",
            "disc_percent" => "unit disc (%)",
            "disc" => "unit disc",
            "qty" => "qty",
            "sub_harga" => "sub bruto",
            "sub_disc" => "sub diskon",
            "sub_nett1" => "sub netto",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "customerDetails__alamat_1" => "address",
            "customerDetails__tlp_1" => "phone",
            "customerDetails__tlp_2" => "handphone",
            "customerDetails__npwp" => "npwp",
            "billingDetails__nik" => "nik",
            "valas_nama" => "currency",
        ),
        "reviewCompactListDetailSum" => array(
            "qty" => "qty",
            "jual" => "jual",
            "disc" => "disc",
            "nett1" => "grand total",
        ),
        "fixedFieldHoldConsolidate" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "addFields" => "sales",
                "fields" => array(
                    "cabang_nama" => "cabang",
                    "nomer_top" => "nomer",
                    "nomer" => "nomer otorisasi",
                    "dtime" => "approved",
                    // "seller_nama" => array(
                    //     "step" => 1,
                    //     "key" => "olehName",
                    //     "label" => "salesman",
                    // ),
                    "seller_nama" => "salesman",
                    "oleh_nama" => "approval",
                    "customers_nama" => "customer",
                    // "outstanding_nilai_items" => "nilai",
                    "outstanding_items" => "detail items*",
                    "sub_outstanding_items" => "nilai",
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(),
                "items" => array(
                    "outstanding_items" => array(
                        "nett1",
                    ),
                ),
            ),
            "produk" => array(

                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    //                    "no" =>"No",
                    "cabang_nama" => "cabang",
                    "produk_nama" => "product",
                    "produk_kode" => "product_no",
                    "customers_nama" => "customers nama",
                    "nomer_top" => "Transaksi",
                    "ord_qty" => "Order",
                    "ord_sent_qty" => "Dikirim",
                    "ord_valid_qty" => "Outstanding",
                    "stok" => "Tersedia",
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(
                    "customers_nama" => "customers_nama",
                    "nomer_top" => "nomer_top",
                    "ord_qty" => "produk_ord_jml",
                    "ord_valid_qty" => "valid_qty",
                    "ord_sent_qty" => "produk_ord_jml-valid_qty",
                ),

            ),
            "customer" => array(
                "cabang_nama" => "cabang",
                "label" => "customer",
                "target" => "customer",
                "srcKey" => "customers_id",
                "fields" => array(
                    "customers_nama" => "Customer",
                    "nomer_top" => "Transaksi SO",
                    // "transaksi_nilai" => "nilai",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode" => "produk kode",
                    "produk_ord_jml" => "order",
                    "ord_sent_qty" => "dikirim",
                    "ord_valid_qty" => "<span class='text-red'>Outstanding</span>",
                ),
                "loop" => array(
                    "nomer_top" => "nomer_top",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode" => "produk_kode",
                    "produk_ord_jml" => "produk_ord_jml",
                    "ord_sent_qty" => "produk_ord_jml-valid_qty",
                    "ord_valid_qty" => "valid_qty",
                ),
                "array_flip" => array(
                    1,
                ),
            ),

        ),
        "reviewCompactListSum" => array(
            "shipping_service" => "shipping service",
            "grand_total_ui" => "total amount",
            "grand_ppn" => "VAT 11%",
            "new_net3" => "grand total",
        ),
        "reviewAddRows" => array(
            "top__nama" => "pembayaran",
            "dp" => "downpayment",
            "paymentMethod" => "paymentMethod",
        ),
        "reviewSign" => array(
            1 => array(
                "sign_1",
            ),
            2 => array(
                "sign_1",
                "sign_2",
            ),
        ),
        "fixedFieldHold" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "fields" => array(
                    "nomer_top" => "nomer",
                    "dtime" => "approved",
                    "seller_nama" => array(
                        "step" => 1,
                        "key" => "olehName",
                        "label" => "salesman",
                    ),
                    "oleh_nama" => "approval",
                    "customers_nama" => "customer",
                    //                    "transaksi_nilai" => "nilai",
                    "outstanding_items" => "detail items*",
                    "sub_outstanding_items" => "nilai",
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(),
                "items" => array(
                    "outstanding_items" => array(
                        "nett1",
                    ),
                ),
            ),
            "produk" => array(
                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    //                    "no" =>"No",
                    "produk_nama" => "product",
                    "produk_kode" => "product_no",
                    "customers_nama" => "customers nama",
                    "nomer_top" => "Transaksi",
                    "ord_qty" => "Order",
                    "ord_sent_qty" => "Dikirim",
                    "ord_valid_qty" => "Outstanding",
                    "stok" => "Tersedia",
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(
                    "customers_nama" => "customers_nama",
                    "nomer_top" => "nomer_top",
                    "ord_qty" => "produk_ord_jml",
                    "ord_valid_qty" => "valid_qty",
                    "ord_sent_qty" => "produk_ord_jml-valid_qty",
                ),

            ),
            "customer" => array(
                "label" => "customer",
                "target" => "customer",
                "srcKey" => "customers_id",
                "fields" => array(
                    "customers_nama" => "Customer",
                    "nomer_top" => "Transaksi SO",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode" => "produk kode",
                    "produk_ord_jml" => "order",
                    "ord_sent_qty" => "dikirim",
                    "ord_valid_qty" => "<span class='text-red'>Outstanding</span>",
                ),
                "loop" => array(
                    "nomer_top" => "nomer_top",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode" => "produk_kode",
                    "produk_ord_jml" => "produk_ord_jml",
                    "ord_sent_qty" => "produk_ord_jml-valid_qty",
                    "ord_valid_qty" => "valid_qty",
                ),
                "array_flip" => array(
                    1,
                ),
            ),

        ),
        "print_nvalas" => false,
        "print_lable" => array(
            "steps" => array(
                1 => array(
                    "label" => "pre order",
                    "labelPre" => "invoice",
                ),
            ),
        ),
        // "printException" => array(
        //     5 => "bulat",
        // ),
        "print_hitung" => array(
            5 => false,
        ),
        "print_hitung_itemRecap" => array(
            5 => array(
                "nett1" => "jml*nett1",
            ),
        ),
        "print_hitung_mainReplacer" => array(
            5 => array(
                "ongkir" => "ongkir",
                "new_net1" => "nett1+ongkir",
                //                "dp_value" => "dp_value",
                //                "dp_ppn_value" => "dp_ppn_value",
                //                "total_ui" => "total_ui",
                "nett1_bulat" => "new_net1",
                "ppn_out_bulat" => "ongkir_ppn+(10/100*nett1)-dp_ppn_value",
                "ppn_net" => "ppn",
                //                "tagihan" => "new_net1+ppn_out_bulat-dp-nilai_cia",
                "tagihan" => "new_net1+ppn_net-dp-nilai_cia",
                "grand_pembulatan" => "grand_pembulatan",
            ),
        ),
        "print_hitung_unsetSumFields" => array(
            5 => array(
                "nilai_pembulatan",
                "nett1_bulat",
            ),
        ),
        "print_hitung_roundDown" => array(
            5 => array(
                "ppn_out_bulat",
                "tagihan",
            ),
        ),

        "receiptElementInjector" => array(
            "source" => array(
                "element" => "customerDetails",
                "fields" => array(
                    "nama" => "customer_nama",
                    //                    "tlp_1" => "customer_tlp",
                    //                    "npwp" => "customer_npwp",
                ),
                "usedFields" => array(
                    "customer_nama" => "Customer",
                ),
            ),
            "target" => array(
                "element" => "deliveryDetails",
            ),
        ),
        "showCabangInvoice" => array(
            1 => true,
            2 => true,
            3 => true,
            4 => true,
            5 => false,
        ),
    ),


);