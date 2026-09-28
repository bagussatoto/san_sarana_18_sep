<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 9/17/2018
 * Time: 4:37 PM
 */
class MdlPembelianTransaksi extends MdlMother
{
    protected $tableName = "pembelian_transaksi";
    protected $filters = array(
        "pembelian_transaksi.status='1'",
        "pembelian_transaksi.trash='0'",
        "pembelian_transaksi.link_id='0'",
    );
    protected $tableNames = array(
        "tmp" => "pembelian_transaksi_tmpcart",
        "main" => "pembelian_transaksi",
        "main_entries" => "pembelian_transaksi_data_main",
        "detail" => "pembelian_transaksi_data",
        "detail_rsltItems" => "pembelian_transaksi_data_rslt_items",
        "items" => "pembelian_transaksi_data_items",
        "items2" => "pembelian_transaksi_data_items2",
        "items2_sum" => "pembelian_transaksi_data_items2_sum",
//        "items_values2_sum" => "pembelian_transaksi_data",
        "items3" => "pembelian_transaksi_data_items3",
        "items3_sum" => "pembelian_transaksi_data_items3_sum",
        "items4" => "pembelian_transaksi_data_items4",
        "items4_sum" => "pembelian_transaksi_data_items4_sum",
        "items5" => "pembelian_transaksi_data_items5",
        "items5_sum" => "pembelian_transaksi_data_items5_sum",
        "items6" => "pembelian_transaksi_data_items6",
        "items6_sum" => "pembelian_transaksi_data_items6_sum",
        "items7" => "pembelian_transaksi_data_items7",
        "items7_sum" => "pembelian_transaksi_data_items7_sum",
        "items8" => "pembelian_transaksi_data_items8",
        "items8_sum" => "pembelian_transaksi_data_items8_sum",
        "items9_sum" => "pembelian_transaksi_data_items9_sum",
        "items10_sum" => "pembelian_transaksi_data_items10_sum",

//        "items3_sum" => "pembelian_transaksi_data_item3_sum",
        "mainFields" => "pembelian_transaksi_fields",
        "detailFields" => "pembelian_transaksi_data_fields",
        "mainValues" => "pembelian_transaksi_values",
        "detailValues" => "pembelian_transaksi_data_values",
        "applets" => "pembelian_transaksi_applets",
        "sign" => "pembelian_transaksi_sign",
        "paymentSrc" => "pembelian_transaksi_payment_source",
        "paymentAntiSrc" => "pembelian_transaksi_payment_antisource",
        "registry" => "pembelian_transaksi_registry",
        "dataRegistry" => "pembelian_transaksi_data_registry",
        "elements" => "pembelian_transaksi_element",
//        "extras" => "transaksi_extstep",
        "dueDate" => "pembelian_transaksi_due_date",
        "uangMuka" => "pembelian_transaksi_uang_muka_source",
        "efaktur" => "pembelian_transaksi_efaktur",
        "garansi" => "pembelian_transaksi_data_garansi",
        "rekening" => "_rek_pembelian_transaksi_data",
        //------
        "counters" => "pembelian_transaksi_counters",
        //------
//        "items9_sum" => "pembelian_transaksi_data_item9_sum",
//        "items10_sum" => "pembelian_transaksi_data_item10_sum",
    );
    private $fields = array(
        "tmp" => array(
            "id",
            "jenis",
            "content",
            "content_intext",
            "date_created",
            "created_by",
            "date_modified",
            "modified_by",
            "cabang_id",
            "gudang_id",

        ),
        "main" => array(
            "id",
            "id_master",
            "id_top",
            "link_id",
            "jenis",
            "jenis_top",
            "nomer",
            "nomer_top",
            "nomer_new",
            "nomer_top_new",
            "nomer2",
            "dtime",
            "jenis_label",
            "jenis_master",
            "oleh_id",
            "oleh_nama",
            "customers_id",
            "customers_nama",
            "suppliers_id",
            "suppliers_nama",
            "cabang_id",
            "cabang_nama",
            "cabang2_id",
            "cabang2_nama",
            "gudang_id",
            "gudang_nama",
            "gudang2_id",
            "gudang2_nama",
            "status",
            "trash",
            "ids_prev",
            "ids_prev_intext",
            "ids_his",
            "ids_his_intext",
            "referensi_id",
            "referensi_nomer",
            "referensi_jenis",
            "ids_ref",
            "ids_ref_intext",
            "jenises_prev",
            "jenises_prev_intext",
            "nomers_prev",
            "nomers_prev_intext",
            "pembayaran_sys",
            "keterangan",
            "pembayaran",
            "pembayaran_tunai",
            "transaksi_jenis",
            "seller_id",
            "seller_nama",
            "fulldate",
            "step_avail",
            "step_current",
            "step_number",
            "next_step_num",
            "next_step_code",
            "next_step_label",
            "next_group_code",
            "tail_number",
            "tail_code",
            "counters",
            "counters_intext",
            "returned",
            "div_id",
            "div_nama",
            "sinkron",
            "approvals_id",
            "approvals_nama",
            "approvals_dtime",
            "penerima_id",
            "penerima_nama",
            "penerima_dtime",
            "approvals_batal_id",
            "approvals_batal_nama",
            "approvals_batal_dtime",
            "approvals_batal_alasan",
            "trash2",
            "tipe",
            "status_edit",
            "cabang_id_tujuan",
            "cabang_nama_tujuan",
            "nomer_surat_jalan",
            "print_counter",
            "print_status",
            "print_dtime_last",
            "print_oleh_id",
            "print_oleh_nama",
            "approvals_harga_id",
            "approvals_harga_nama",
            "approvals_harga_dtime",
            "penerimaan",
            "penerimaan_tipe",
            "batal_kirim",
            "approvals_batal_kirim_id",
            "approvals_batal_kirim_nama",
            "approvals_batal_kirim_dtime",
            "valas_nama",
            "valas_id",
            "pembayaran_note",
            "tambahan_jenis",
            "edit_dtime",
            "edit_id",
            "edit_name",
            "status_4",
            "trash_4",
            "deskripsi",
            "cancel_dtime",
            "cancel_name",
            "cancel_id",
            "cancel_transaksi_jenis",
            "cancel_transaksi_id",
            "cancel_transaksi_nomer",
            "status_cancel",
            "cancel_packing_source_id",
            "cli",
            "partial",
            "top",
            "top_nama",
            "tos",
            "tos_nama",
            "rekening",
            "reference_jenis",
            "reference_id",
            "reference_nomer",
            "reference_id_top",
            "reference_nomer_top",
            "reference_jenis_top",
            "fullfillment_id",
            "fullfillment_nomer",
            "fullfillment_dtime",
            "fullfillment_jenis",
            "fullfillment_jenis_master",
            "fullfillment_oleh_id",
            "fullfillment_oleh_nama",
            "project_id",
            "project_nama",
            "pengirim_id",
            "pengirim_nama",
            "settlement_id",
            "salesman_id",
            "salesman_nama",
            "gudang_status_id",
            "gudang_status_nama",
            "gudang_status_jenis",
            "reference_jenis_master",
            "reference_cabang_id",
            "reference_cabang_nama",
            "reference_gudang_id",
            "reference_gudang_nama",
            "reference_terima_barang",
            "dtime_kirim",
            "kirim_metode_id",
            "kirim_metode_nama",
            "modul",
            "subModul",
            "ppnFactor",
            "ppn_jenis",
            "diskon_jenis",
            "valas_nilai",
            "diskon_persen",
            "uang_muka_ppn",
            "uang_muka_nilai_non_ppn",
            "credit_note_return",
            "deposit_persen_in",
            "hpp_paket",
            "hpp",
            "ppv",
            "hpp_ppv",
            "transaksi_bruto",
            "point_nilai",
            "diskon_nilai",
            "deposit_nilai_in",
            "premi",
            "biaya",
            "biaya_kirim",
            "ppn_nilai",
            "laba_lain_lain",
            "transaksi_net",
            "transaksi_pembulatan",
            "transaksi_bulat",
            "transaksi_dibayar",
            "transaksi_dibayar_return",
            "transaksi_netto",
            "diskon_unit",
            "ppnPersenCheck",
            "ppnTransaksi",
            "ppv_index",
            "pembayaran_sys",
        ),
        "detail" => array(
            "id",
            "transaksi_id",
            "jenis",
            "produk_jenis",
            "produk_id",
            "produk_nama",
            "produk_nama_2",
            "produk_sku",
            "produk_sku_qty",
            "produk_ord_label",
            "produk_ord_keterangan",
            "produk_ord_folders",
            "produk_ord_kode",
            "produk_ord_satuan",
            "produk_ord_jml",
            "produk_ord_jml_return",
            "produk_ord_hrg",
            "produk_ord_stok",
            "produk_ord_diskon",
            "produk_ord_diskon_persen",
            "produk_ord_diskon_khusus",
            "produk_ord_diterima",
            "produk_ord_kurang",
            "produk_ord_hrg_net_ppn",
            "produk_ord_hrg_sub",
            "produk_ord_hrg_sub_nppn",
            "produk_ord_hrg_include_ppn",
            "produk_ord_hrg_exclude_ppn",
            "produk_ord_hrg_gap",
            "produk_ord_premi",
            "produk_ord_ppn",
            "produk_ord_ppn_persen",
            "produk_ord_hpp",
            "produk_ord_hpp_ori",
            "produk_ord_diskon_persen_ori",
            "produk_ord_diskon_ori",
            "produk_ord_diskon_khusus_ori",
            "produk_ord_komisi",
            "produk_ord_bunga",
            "produk_ord_valas_nilai",
            "produk_ord_valas_nama",
            "produk_ord_batal",
            "produk_ord_berat",
            "produk_ord_berat_gross",
            "produk_ord_volume",
            "produk_ord_volume_gross",
            "sorting",
            "parent_id",
            "trash",
            "transaksi_jenis",
            "status",
            "kirim",
            "keterangan",
            "produk_no_part",
            "sub_step_number",
            "sub_step_current",
            "sub_step_avail",
            "next_substep_num",
            "next_substep_code",
            "next_substep_label",
            "next_subgroup_code",
            "sub_tail_number",
            "sub_tail_code",
            "detail_tipe",
            "bulan",
            "tahun",
            "dtime",
//            "oleh_id",
//            "oleh_nama",
//            "cabang_id",
            "batal_dtime",
            "batal_oleh_id",
            "batal_oleh_nama",
            "valas_ord_nama",
            "valas_ord_hrg",
            "valas_produk_diskon",
            "valas_produk_diskon_khusus",
            "cancel_qty",
            "cancel_id",
            "status_cancel",
            "cancel_name",
            "req_cancel_qty",
            "approve",
            "return",
            "reject",
            "batal",
            "qty_approve",
            "qty_return",
            "qty_reject",
            "qty_batal",
            "debet",
            "kredit",
            "saldo",
            "qty_debet",
            "qty_kredit",
            "qty_saldo",
        ),
        "sub_data" => array(
            "id",
            "transaksi_id",
            "tds_jenis",
            "tds_produk_id",
            "tds_produk_nama",
            "tds_produk_nama_2",
            "tds_produk_sku",
            "tds_produk_sku_qty",
            "tds_produk_ord_label",
            "tds_produk_ord_keterangan",
            "tds_produk_ord_folders",
            "tds_produk_ord_kode",
            "tds_produk_ord_satuan",
            "tds_produk_ord_jml",
            "tds_produk_ord_jml_return",
            "tds_produk_ord_hrg",
            "tds_produk_ord_stok",
            "tds_produk_ord_diskon",
            "tds_produk_ord_diskon_persen",
            "tds_produk_ord_diskon_khusus",
            "tds_produk_ord_diterima",
            "tds_produk_ord_kurang",
            "tds_produk_ord_hrg_net_ppn",
            "tds_produk_ord_hrg_sub",
            "tds_produk_ord_hrg_sub_nppn",
            "tds_produk_ord_hrg_include_ppn",
            "tds_produk_ord_hrg_exclude_ppn",
            "tds_produk_ord_hrg_gap",
            "tds_produk_ord_premi",
            "tds_produk_ord_ppn",
            "tds_produk_ord_ppn_persen",
            "tds_produk_ord_hpp",
            "tds_produk_ord_hpp_ori",
            "tds_produk_ord_diskon_persen_ori",
            "tds_produk_ord_diskon_ori",
            "tds_produk_ord_diskon_khusus_ori",
            "tds_produk_ord_komisi",
            "tds_produk_ord_bunga",
            "tds_produk_ord_valas_nilai",
            "tds_produk_ord_valas_nama",
            "tds_produk_ord_batal",
            "tds_produk_ord_berat",
            "tds_produk_ord_berat_gross",
            "tds_produk_ord_volume",
            "tds_produk_ord_volume_gross",
            "tds_sorting",
            "tds_parent_id",
            "tds_trash",
            "tds_transaksi_jenis",
            "tds_status",
            "tds_kirim",
            "tds_keterangan",
            "tds_produk_no_part",
            "tds_sub_step_number",
            "tds_sub_step_current",
            "tds_sub_step_avail",
            "tds_next_substep_num",
            "tds_next_substep_code",
            "tds_next_substep_label",
            "tds_next_subgroup_code",
            "tds_sub_tail_number",
            "tds_sub_tail_code",
            "tds_detail_tipe",
            "tds_bulan",
            "tds_tahun",
            "tds_dtime",
            "tds_oleh_id",
            "tds_oleh_nama",
            "tds_cabang_id",
            "tds_batal_dtime",
            "tds_batal_oleh_id",
            "tds_batal_oleh_nama",
            "tds_valas_ord_nama",
            "tds_valas_ord_hrg",
            "tds_valas_produk_diskon",
            "tds_valas_produk_diskon_khusus",
            "tds_cancel_qty",
            "tds_cancel_id",
            "tds_status_cancel",
            "tds_cancel_name",
            "tds_req_cancel_qty",
            "tds_approve",
            "tds_return",
            "tds_reject",
            "tds_batal",
            "tds_qty_approve",
            "tds_qty_return",
            "tds_qty_reject",
            "tds_qty_batal",
            "tds_debet",
            "tds_kredit",
            "tds_saldo",
            "tds_qty_debet",
            "tds_qty_kredit",
            "tds_qty_saldo",
        ),
        "main_entries" => array(
//            "placeID",
//            "placeName",
//            "place2ID",
//            "place2Name",
//            "gudang2ID",
//            "gudang2Name",
            "stepCode",
            "pihakID",
            "pihakName",
            "description_main_followup",
            "description",
            "currentID",
            "currentNomer",
            "ids_prev",
            "nomer_top",
            "nomers_prev",
            "jenises_prev",
            "ids_his",
            //-----
            "harga_produk",//persediaan produk riil
            "nilai_tambah_ppn_in",//ppn in belum ada faktur
            "nilai_tambah_piutang_pembelian",//hutang dagan
            "nilai_dipakai_piutang_pembelian",//piutang pembelian
            "diskon_nilai_total",// piutang supplier
            "laba_lain_lain",// laba lain-lain
            "produk_rel_harga",// piutang supplier
            "ppn_out_bulat",
            "ppn",
            "ppn_0",
            "ppn_11",
            "ppn_12",
            "dppPpn_0",
            "dppPpn_11",
            "dppPpn_12",
            "grand_total",
            "tagihan",
            //-----
            "shippingDate",
            "paymentMethod",
            "paymentMethod__label",
            "paymentMethod__name",
            "ppv_index",
            "ppv_index__label",
            "ppv_index__nilai",
            "deliveryDetails",
            "deliveryDetails__label",
            "capacity",
            "capacity__label",
            "capacity__nama",
            "tos",
            "tos__label",
            "tos__nama",
            "top",
            "top__label",
            "top__nama",
            //-----
            "hpp",
            "nett",
            "dpp_pengganti",
            "hpp_nppn",
            //-----
            "ppnFactor",
            "ppnConstanta",
            "ppnConstantaStr",
            //-----
            "referenceID_so",
            "referenceNomer_so",
            "ppnPersenCheck",
            "ppnTransaksi",
        ),
        "items" => array(
//            "id",
            "handler",
            "name",
            "nama",
            "produk_id",
            "produk_nama",
            "produk_kode",
            "produk_label",
            "jml",
            "qty",
            "discount_qty",
            "harga",
            "subtotal",
            "satuan",
            "produk_sku",
            "label",
            "ppn",
            "ppnFactorDesimal_item",
            "ppnFactorInclude_item",
            "barcode",
            "jenis",
            "produk_jenis_id",
            "produk_jenis_nama",
            "jml_serial",
            "kategori_id",
            "kategori_nama",
            "part_id_1",
            "part_nama_1",
            "part_barcode_1",
            "part_id_2",
            "part_nama_2",
            "part_barcode_2",
            "heater_id",
            "heater_nama",
            "heater_barcode",
            "outdoor_id",
            "outdoor_nama",
            "outdoor_barcode",
            "outdoor_sku",
            "indoor_id_1",
            "indoor_nama_1",
            "indoor_barcode_1",
            "indoor_sku_1",
            "indoor_id_2",
            "indoor_nama_2",
            "indoor_barcode_2",
            "indoor_sku_2",
            "indoor_id_3",
            "indoor_nama_3",
            "indoor_barcode_3",
            "indoor_sku_3",
            "indoor_id_4",
            "indoor_nama_4",
            "indoor_barcode_4",
            "indoor_sku_4",
            "qty_outdoor",
            "qty_indoor",
            "keterangan",
            "static_keterangan",
            "sub_qty_indoor",
            "sub_qty_outdoor",
            "discPersen",
            "lastNett",
            "jual_dipakai",
            "harga_jual",
            "harga_disc",
            "discNilai",
            "scan_mode",
            "qty_barcode",
            "nett1",
            "harga_include_ppn",
            "nett1_include_ppn",
            "_harga_non_ppn",
            "_diskon_non_ppn",
            "_harga_ppn",
            "disc",
            "_grand_total",
            "nett1_ppn",
            "sub_harga",
            "sub_subtotal",
            "sub_discount_persen",
            "sub_discount_qty",
            "sub_harga_jasa",
            "sub_jual_online",
            "sub_jual",
            "sub_jual_reseller",
            "sub_ppn",
            "sub_discPersen",
            "sub_lastNett",
            "sub_jual_dipakai",
            "sub_harga_jual",
            "sub_harga_disc",
            "sub_discNilai",
            "sub_qty_barcode",
            "sub_nett1",
            "sub_harga_include_ppn",
            "sub__harga_non_ppn",
            "sub__diskon_non_ppn",
            "sub__harga_ppn",
            "sub_disc",
            "sub__grand_total",
            "sub_nett1_ppn",
            "sub_jml_serial",
            "harga_jasa",
            "jual_online",
            "jual",
            "jual_reseller",
            "cabang_id",
            "gudang_id",
            "transaksi_id",
            "ppnFactorDesimal_item",
            "ppnFactorInclude_item",
            "ppnFactor_item",
            //-----
            "hpp_nppn",
            "hpp_nppv",
            "ppv",
            "hpp_nppn_nppv",
            "nett",
            "diskon_npph_nilai_total",
            "laba_lain_lain",
            "diskon_nilai_total",
            "diskon_pph23",
            //-----
            "sub_hpp",
            //-----
            "harga_order",
            "harga_list",
            "diskon_persen_jual",
            "diskon_nilai_jual",
        ),
        "items2_sum" => array(
            "produk_id",
            "produk_kode",
            "produk_label",
            "produk_nama",
            "produk_ord_jml",
            "produk_ord_kurang",
            "produk_satuan",
            "produk_sku",
            "produk_ord_hrg",
            "produk_ord_diskon",
            "produk_ord_diskon_persen",
            "produk_ord_diskon_khusus",
            "produk_ord_ppn",
            "produk_ord_ppn_persen",
            "produk_ord_hpp",
            "produk_ord_hrg_net_ppn",
            "produk_ord_hrg_sub",
            "produk_ord_hrg_sub_nppn",
            "produk_ord_premi",
            "produk_ord_hrg_ori",
            "produk_ord_hrg_include_ppn",
            "produk_ord_hrg_exclude_ppn",
            "cabang_id",
            "gudang_id",
            "transaksi_id",
        ),
        "items4_sum" => array(
//            "id",
            "handler",
            "name",
            "nama",
            "produk_id",
            "produk_nama",
            "produk_kode",
            "produk_label",
            "jml",
            "qty",
            "discount_qty",
            "harga",
            "subtotal",
            "satuan",
            "produk_sku",
            "label",
            "ppn",
            "barcode",
            "jenis",
            "produk_jenis_id",
            "produk_jenis_nama",
            "jml_serial",
            "kategori_id",
            "kategori_nama",
            "part_id_1",
            "part_nama_1",
            "part_barcode_1",
            "part_id_2",
            "part_nama_2",
            "part_barcode_2",
            "heater_id",
            "heater_nama",
            "heater_barcode",
            "outdoor_id",
            "outdoor_nama",
            "outdoor_barcode",
            "outdoor_sku",
            "indoor_id_1",
            "indoor_nama_1",
            "indoor_barcode_1",
            "indoor_sku_1",
            "indoor_id_2",
            "indoor_nama_2",
            "indoor_barcode_2",
            "indoor_sku_2",
            "indoor_id_3",
            "indoor_nama_3",
            "indoor_barcode_3",
            "indoor_sku_3",
            "indoor_id_4",
            "indoor_nama_4",
            "indoor_barcode_4",
            "indoor_sku_4",
            "qty_outdoor",
            "qty_indoor",
            "keterangan",
            "static_keterangan",
            "sub_qty_indoor",
            "sub_qty_outdoor",
            "discPersen",
            "lastNett",
            "jual_dipakai",
            "harga_jual",
            "harga_disc",
            "discNilai",
            "scan_mode",
            "qty_barcode",
            "nett1",
            "harga_include_ppn",
            "nett1_include_ppn",
            "_harga_non_ppn",
            "_diskon_non_ppn",
            "_harga_ppn",
            "disc",
            "_grand_total",
            "nett1_ppn",
            "sub_harga",
            "sub_subtotal",
            "sub_discount_persen",
            "sub_discount_qty",
            "sub_harga_jasa",
            "sub_jual_online",
            "sub_jual",
            "sub_jual_reseller",
            "sub_ppn",
            "sub_discPersen",
            "sub_lastNett",
            "sub_jual_dipakai",
            "sub_harga_jual",
            "sub_harga_disc",
            "sub_discNilai",
            "sub_qty_barcode",
            "sub_nett1",
            "sub_harga_include_ppn",
            "sub__harga_non_ppn",
            "sub__diskon_non_ppn",
            "sub__harga_ppn",
            "sub_disc",
            "sub__grand_total",
            "sub_nett1_ppn",
            "sub_jml_serial",
            "harga_jasa",
            "jual_online",
            "jual",
            "jual_reseller",
            "cabang_id",
            "gudang_id",
            "transaksi_id",
            //-----
            "sub_diskon_nilai",// piutang supplier
            "diskon_id",
            "diskon_nama",
            "diskon_nilai",// jenis diskon

            //-----
        ),
        "items5_sum" => array(
//            "id",
            "handler",
            "name",
            "nama",
            "produk_id",
            "produk_nama",
            "produk_kode",
            "produk_label",
            "jml",
            "qty",
            "discount_qty",
            "harga",
            "subtotal",
            "satuan",
            "produk_sku",
            "label",
            "ppn",
            "barcode",
            "jenis",
            "produk_jenis_id",
            "produk_jenis_nama",
            "jml_serial",
            "kategori_id",
            "kategori_nama",
            "part_id_1",
            "part_nama_1",
            "part_barcode_1",
            "part_id_2",
            "part_nama_2",
            "part_barcode_2",
            "heater_id",
            "heater_nama",
            "heater_barcode",
            "outdoor_id",
            "outdoor_nama",
            "outdoor_barcode",
            "outdoor_sku",
            "indoor_id_1",
            "indoor_nama_1",
            "indoor_barcode_1",
            "indoor_sku_1",
            "indoor_id_2",
            "indoor_nama_2",
            "indoor_barcode_2",
            "indoor_sku_2",
            "indoor_id_3",
            "indoor_nama_3",
            "indoor_barcode_3",
            "indoor_sku_3",
            "indoor_id_4",
            "indoor_nama_4",
            "indoor_barcode_4",
            "indoor_sku_4",
            "qty_outdoor",
            "qty_indoor",
            "keterangan",
            "static_keterangan",
            "sub_qty_indoor",
            "sub_qty_outdoor",
            "discPersen",
            "lastNett",
            "jual_dipakai",
            "harga_jual",
            "harga_disc",
            "discNilai",
            "scan_mode",
            "qty_barcode",
            "nett1",
            "harga_include_ppn",
            "nett1_include_ppn",
            "_harga_non_ppn",
            "_diskon_non_ppn",
            "_harga_ppn",
            "disc",
            "_grand_total",
            "nett1_ppn",
            "sub_harga",
            "sub_subtotal",
            "sub_discount_persen",
            "sub_discount_qty",
            "sub_harga_jasa",
            "sub_jual_online",
            "sub_jual",
            "sub_jual_reseller",
            "sub_ppn",
            "sub_discPersen",
            "sub_lastNett",
            "sub_jual_dipakai",
            "sub_harga_jual",
            "sub_harga_disc",
            "sub_discNilai",
            "sub_qty_barcode",
            "sub_nett1",
            "sub_harga_include_ppn",
            "sub__harga_non_ppn",
            "sub__diskon_non_ppn",
            "sub__harga_ppn",
            "sub_disc",
            "sub__grand_total",
            "sub_nett1_ppn",
            "sub_jml_serial",
            "harga_jasa",
            "jual_online",
            "jual",
            "jual_reseller",
            "cabang_id",
            "gudang_id",
            "transaksi_id",
            //-----
            "sub_produk_rel_harga",// piutang supplier
            "per_supplier_diskon_id",
            "per_supplier_diskon_nama",
            "produk_rel_id",// hadiahnya produknya(kabel,selang)
            "produk_rel_nama",// jenis diskon
            "produk_rel_harga",// jenis diskon

            //-----
        ),
        "items6_sum" => array(
//            "id",
            "handler",
            "name",
            "nama",
            "produk_id",
            "produk_nama",
            "produk_kode",
            "produk_label",
            "jml",
            "qty",
            "discount_qty",
            "harga",
            "subtotal",
            "satuan",
            "produk_sku",
            "label",
            "ppn",
            "barcode",
            "jenis",
            "produk_jenis_id",
            "produk_jenis_nama",
            "jml_serial",
            "kategori_id",
            "kategori_nama",
            "part_id_1",
            "part_nama_1",
            "part_barcode_1",
            "part_id_2",
            "part_nama_2",
            "part_barcode_2",
            "heater_id",
            "heater_nama",
            "heater_barcode",
            "outdoor_id",
            "outdoor_nama",
            "outdoor_barcode",
            "outdoor_sku",
            "indoor_id_1",
            "indoor_nama_1",
            "indoor_barcode_1",
            "indoor_sku_1",
            "indoor_id_2",
            "indoor_nama_2",
            "indoor_barcode_2",
            "indoor_sku_2",
            "indoor_id_3",
            "indoor_nama_3",
            "indoor_barcode_3",
            "indoor_sku_3",
            "indoor_id_4",
            "indoor_nama_4",
            "indoor_barcode_4",
            "indoor_sku_4",
            "qty_outdoor",
            "qty_indoor",
            "keterangan",
            "static_keterangan",
            "sub_qty_indoor",
            "sub_qty_outdoor",
            "discPersen",
            "lastNett",
            "jual_dipakai",
            "harga_jual",
            "harga_disc",
            "discNilai",
            "scan_mode",
            "qty_barcode",
            "nett1",
            "harga_include_ppn",
            "nett1_include_ppn",
            "_harga_non_ppn",
            "_diskon_non_ppn",
            "_harga_ppn",
            "disc",
            "_grand_total",
            "nett1_ppn",
            "sub_harga",
            "sub_subtotal",
            "sub_discount_persen",
            "sub_discount_qty",
            "sub_harga_jasa",
            "sub_jual_online",
            "sub_jual",
            "sub_jual_reseller",
            "sub_ppn",
            "sub_discPersen",
            "sub_lastNett",
            "sub_jual_dipakai",
            "sub_harga_jual",
            "sub_harga_disc",
            "sub_discNilai",
            "sub_qty_barcode",
            "sub_nett1",
            "sub_harga_include_ppn",
            "sub__harga_non_ppn",
            "sub__diskon_non_ppn",
            "sub__harga_ppn",
            "sub_disc",
            "sub__grand_total",
            "sub_nett1_ppn",
            "sub_jml_serial",
            "harga_jasa",
            "jual_online",
            "jual",
            "jual_reseller",
            "cabang_id",
            "gudang_id",
            "transaksi_id",
        ),
        "items7_sum" => array(
//            "id",
            "handler",
            "name",
            "nama",
            "produk_id",
            "produk_nama",
            "produk_kode",
            "produk_label",
            "jml",
            "qty",
            "discount_qty",
            "harga",
            "subtotal",
            "satuan",
            "produk_sku",
            "label",
            "ppn",
            "barcode",
            "jenis",
            "produk_jenis_id",
            "produk_jenis_nama",
            "jml_serial",
            "kategori_id",
            "kategori_nama",
            "part_id_1",
            "part_nama_1",
            "part_barcode_1",
            "part_id_2",
            "part_nama_2",
            "part_barcode_2",
            "heater_id",
            "heater_nama",
            "heater_barcode",
            "outdoor_id",
            "outdoor_nama",
            "outdoor_barcode",
            "outdoor_sku",
            "indoor_id_1",
            "indoor_nama_1",
            "indoor_barcode_1",
            "indoor_sku_1",
            "indoor_id_2",
            "indoor_nama_2",
            "indoor_barcode_2",
            "indoor_sku_2",
            "indoor_id_3",
            "indoor_nama_3",
            "indoor_barcode_3",
            "indoor_sku_3",
            "indoor_id_4",
            "indoor_nama_4",
            "indoor_barcode_4",
            "indoor_sku_4",
            "qty_outdoor",
            "qty_indoor",
            "keterangan",
            "static_keterangan",
            "sub_qty_indoor",
            "sub_qty_outdoor",
            "discPersen",
            "lastNett",
            "jual_dipakai",
            "harga_jual",
            "harga_disc",
            "discNilai",
            "scan_mode",
            "qty_barcode",
            "nett1",
            "harga_include_ppn",
            "nett1_include_ppn",
            "_harga_non_ppn",
            "_diskon_non_ppn",
            "_harga_ppn",
            "disc",
            "_grand_total",
            "nett1_ppn",
            "sub_harga",
            "sub_subtotal",
            "sub_discount_persen",
            "sub_discount_qty",
            "sub_harga_jasa",
            "sub_jual_online",
            "sub_jual",
            "sub_jual_reseller",
            "sub_ppn",
            "sub_discPersen",
            "sub_lastNett",
            "sub_jual_dipakai",
            "sub_harga_jual",
            "sub_harga_disc",
            "sub_discNilai",
            "sub_qty_barcode",
            "sub_nett1",
            "sub_harga_include_ppn",
            "sub__harga_non_ppn",
            "sub__diskon_non_ppn",
            "sub__harga_ppn",
            "sub_disc",
            "sub__grand_total",
            "sub_nett1_ppn",
            "sub_jml_serial",
            "harga_jasa",
            "jual_online",
            "jual",
            "jual_reseller",
            "cabang_id",
            "gudang_id",
            "transaksi_id",
        ),
        "items8_sum" => array(
//            "id",
            "handler",
            "name",
            "nama",
            "produk_id",
            "produk_nama",
            "produk_kode",
            "produk_label",
            "jml",
            "qty",
            "discount_qty",
            "harga",
            "subtotal",
            "satuan",
            "produk_sku",
            "label",
            "ppn",
            "barcode",
            "jenis",
            "produk_jenis_id",
            "produk_jenis_nama",
            "jml_serial",
            "kategori_id",
            "kategori_nama",
            "part_id_1",
            "part_nama_1",
            "part_barcode_1",
            "part_id_2",
            "part_nama_2",
            "part_barcode_2",
            "heater_id",
            "heater_nama",
            "heater_barcode",
            "outdoor_id",
            "outdoor_nama",
            "outdoor_barcode",
            "outdoor_sku",
            "indoor_id_1",
            "indoor_nama_1",
            "indoor_barcode_1",
            "indoor_sku_1",
            "indoor_id_2",
            "indoor_nama_2",
            "indoor_barcode_2",
            "indoor_sku_2",
            "indoor_id_3",
            "indoor_nama_3",
            "indoor_barcode_3",
            "indoor_sku_3",
            "indoor_id_4",
            "indoor_nama_4",
            "indoor_barcode_4",
            "indoor_sku_4",
            "qty_outdoor",
            "qty_indoor",
            "keterangan",
            "static_keterangan",
            "sub_qty_indoor",
            "sub_qty_outdoor",
            "discPersen",
            "lastNett",
            "jual_dipakai",
            "harga_jual",
            "harga_disc",
            "discNilai",
            "scan_mode",
            "qty_barcode",
            "nett1",
            "harga_include_ppn",
            "nett1_include_ppn",
            "_harga_non_ppn",
            "_diskon_non_ppn",
            "_harga_ppn",
            "disc",
            "_grand_total",
            "nett1_ppn",
            "sub_harga",
            "sub_subtotal",
            "sub_discount_persen",
            "sub_discount_qty",
            "sub_harga_jasa",
            "sub_jual_online",
            "sub_jual",
            "sub_jual_reseller",
            "sub_ppn",
            "sub_discPersen",
            "sub_lastNett",
            "sub_jual_dipakai",
            "sub_harga_jual",
            "sub_harga_disc",
            "sub_discNilai",
            "sub_qty_barcode",
            "sub_nett1",
            "sub_harga_include_ppn",
            "sub__harga_non_ppn",
            "sub__diskon_non_ppn",
            "sub__harga_ppn",
            "sub_disc",
            "sub__grand_total",
            "sub_nett1_ppn",
            "sub_jml_serial",
            "harga_jasa",
            "jual_online",
            "jual",
            "jual_reseller",
            "cabang_id",
            "gudang_id",
            "transaksi_id",
        ),
        "items9_sum" => array(
//            "id",
            "handler",
            "name",
            "nama",
            "produk_id",
            "produk_nama",
            "produk_kode",
            "produk_label",
            "jml",
            "qty",
            "discount_qty",
            "harga",
            "subtotal",
            "satuan",
            "produk_sku",
            "label",
            "ppn",
            "barcode",
            "jenis",
            "produk_jenis_id",
            "produk_jenis_nama",
            "jml_serial",
            "kategori_id",
            "kategori_nama",
            "part_id_1",
            "part_nama_1",
            "part_barcode_1",
            "part_id_2",
            "part_nama_2",
            "part_barcode_2",
            "heater_id",
            "heater_nama",
            "heater_barcode",
            "outdoor_id",
            "outdoor_nama",
            "outdoor_barcode",
            "outdoor_sku",
            "indoor_id_1",
            "indoor_nama_1",
            "indoor_barcode_1",
            "indoor_sku_1",
            "indoor_id_2",
            "indoor_nama_2",
            "indoor_barcode_2",
            "indoor_sku_2",
            "indoor_id_3",
            "indoor_nama_3",
            "indoor_barcode_3",
            "indoor_sku_3",
            "indoor_id_4",
            "indoor_nama_4",
            "indoor_barcode_4",
            "indoor_sku_4",
            "qty_outdoor",
            "qty_indoor",
            "keterangan",
            "static_keterangan",
            "sub_qty_indoor",
            "sub_qty_outdoor",
            "discPersen",
            "lastNett",
            "jual_dipakai",
            "harga_jual",
            "harga_disc",
            "discNilai",
            "scan_mode",
            "qty_barcode",
            "nett1",
            "harga_include_ppn",
            "nett1_include_ppn",
            "_harga_non_ppn",
            "_diskon_non_ppn",
            "_harga_ppn",
            "disc",
            "_grand_total",
            "nett1_ppn",
            "sub_harga",
            "sub_subtotal",
            "sub_discount_persen",
            "sub_discount_qty",
            "sub_harga_jasa",
            "sub_jual_online",
            "sub_jual",
            "sub_jual_reseller",
            "sub_ppn",
            "sub_discPersen",
            "sub_lastNett",
            "sub_jual_dipakai",
            "sub_harga_jual",
            "sub_harga_disc",
            "sub_discNilai",
            "sub_qty_barcode",
            "sub_nett1",
            "sub_harga_include_ppn",
            "sub__harga_non_ppn",
            "sub__diskon_non_ppn",
            "sub__harga_ppn",
            "sub_disc",
            "sub__grand_total",
            "sub_nett1_ppn",
            "sub_jml_serial",
            "harga_jasa",
            "jual_online",
            "jual",
            "jual_reseller",
            "cabang_id",
            "gudang_id",
            "transaksi_id",

            "ppnFactorDesimal_item",
            "ppnFactorInclude_item",
            "ppnFactor_item",
            //-----
            "hpp_nppn",
            "hpp_nppv",
            "ppv",
            "hpp_nppn_nppv",
            "nett",
            "diskon_npph_nilai_total",
            "laba_lain_lain",
            "diskon_nilai_total",
            "diskon_pph23",
            //-----
        ),
        "items10_sum" => array(
//            "id",
            "handler",
            "name",
            "nama",
            "produk_id",
            "produk_nama",
            "produk_kode",
            "produk_label",
            "jml",
            "qty",
            "discount_qty",
            "harga",
            "subtotal",
            "satuan",
            "produk_sku",
            "label",
            "ppn",
            "barcode",
            "jenis",
            "produk_jenis_id",
            "produk_jenis_nama",
            "jml_serial",
            "kategori_id",
            "kategori_nama",
            "part_id_1",
            "part_nama_1",
            "part_barcode_1",
            "part_id_2",
            "part_nama_2",
            "part_barcode_2",
            "heater_id",
            "heater_nama",
            "heater_barcode",
            "outdoor_id",
            "outdoor_nama",
            "outdoor_barcode",
            "outdoor_sku",
            "indoor_id_1",
            "indoor_nama_1",
            "indoor_barcode_1",
            "indoor_sku_1",
            "indoor_id_2",
            "indoor_nama_2",
            "indoor_barcode_2",
            "indoor_sku_2",
            "indoor_id_3",
            "indoor_nama_3",
            "indoor_barcode_3",
            "indoor_sku_3",
            "indoor_id_4",
            "indoor_nama_4",
            "indoor_barcode_4",
            "indoor_sku_4",
            "qty_outdoor",
            "qty_indoor",
            "keterangan",
            "static_keterangan",
            "sub_qty_indoor",
            "sub_qty_outdoor",
            "discPersen",
            "lastNett",
            "jual_dipakai",
            "harga_jual",
            "harga_disc",
            "discNilai",
            "scan_mode",
            "qty_barcode",
            "nett1",
            "harga_include_ppn",
            "nett1_include_ppn",
            "_harga_non_ppn",
            "_diskon_non_ppn",
            "_harga_ppn",
            "disc",
            "_grand_total",
            "nett1_ppn",
            "sub_harga",
            "sub_subtotal",
            "sub_discount_persen",
            "sub_discount_qty",
            "sub_harga_jasa",
            "sub_jual_online",
            "sub_jual",
            "sub_jual_reseller",
            "sub_ppn",
            "sub_discPersen",
            "sub_lastNett",
            "sub_jual_dipakai",
            "sub_harga_jual",
            "sub_harga_disc",
            "sub_discNilai",
            "sub_qty_barcode",
            "sub_nett1",
            "sub_harga_include_ppn",
            "sub__harga_non_ppn",
            "sub__diskon_non_ppn",
            "sub__harga_ppn",
            "sub_disc",
            "sub__grand_total",
            "sub_nett1_ppn",
            "sub_jml_serial",
            "harga_jasa",
            "jual_online",
            "jual",
            "jual_reseller",
            "cabang_id",
            "gudang_id",
            "transaksi_id",
            "ppnFactorDesimal_item",
            "ppnFactorInclude_item",
            "ppnFactor_item",
            //-----
            "hpp_nppn",
            "hpp_nppv",
            "ppv",
            "hpp_nppn_nppv",
            "nett",
            "diskon_npph_nilai_total",
            "laba_lain_lain",
            "diskon_nilai_total",
            "diskon_pph23",
            //-----
        ),
        "items3_sum" => array(
//            "id",
            "handler",
            "name",
            "nama",
            "produk_id",
            "produk_nama",
            "produk_kode",
            "produk_label",
            "jml",
            "qty",
            "discount_qty",
            "harga",
            "subtotal",
            "satuan",
            "produk_sku",
            "label",
            "ppn",
            "barcode",
            "jenis",
            "produk_jenis_id",
            "produk_jenis_nama",
            "jml_serial",
            "kategori_id",
            "kategori_nama",
            "part_id_1",
            "part_nama_1",
            "part_barcode_1",
            "part_id_2",
            "part_nama_2",
            "part_barcode_2",
            "heater_id",
            "heater_nama",
            "heater_barcode",
            "outdoor_id",
            "outdoor_nama",
            "outdoor_barcode",
            "outdoor_sku",
            "indoor_id_1",
            "indoor_nama_1",
            "indoor_barcode_1",
            "indoor_sku_1",
            "indoor_id_2",
            "indoor_nama_2",
            "indoor_barcode_2",
            "indoor_sku_2",
            "indoor_id_3",
            "indoor_nama_3",
            "indoor_barcode_3",
            "indoor_sku_3",
            "indoor_id_4",
            "indoor_nama_4",
            "indoor_barcode_4",
            "indoor_sku_4",
            "qty_outdoor",
            "qty_indoor",
            "keterangan",
            "static_keterangan",
            "sub_qty_indoor",
            "sub_qty_outdoor",
            "discPersen",
            "lastNett",
            "jual_dipakai",
            "harga_jual",
            "harga_disc",
            "discNilai",
            "scan_mode",
            "qty_barcode",
            "nett1",
            "harga_include_ppn",
            "nett1_include_ppn",
            "_harga_non_ppn",
            "_diskon_non_ppn",
            "_harga_ppn",
            "disc",
            "_grand_total",
            "nett1_ppn",
            "sub_harga",
            "sub_subtotal",
            "sub_discount_persen",
            "sub_discount_qty",
            "sub_harga_jasa",
            "sub_jual_online",
            "sub_jual",
            "sub_jual_reseller",
            "sub_ppn",
            "sub_discPersen",
            "sub_lastNett",
            "sub_jual_dipakai",
            "sub_harga_jual",
            "sub_harga_disc",
            "sub_discNilai",
            "sub_qty_barcode",
            "sub_nett1",
            "sub_harga_include_ppn",
            "sub__harga_non_ppn",
            "sub__diskon_non_ppn",
            "sub__harga_ppn",
            "sub_disc",
            "sub__grand_total",
            "sub_nett1_ppn",
            "sub_jml_serial",
            "harga_jasa",
            "jual_online",
            "jual",
            "jual_reseller",
            "cabang_id",
            "gudang_id",
            "transaksi_id",
        ),

        "sign" => array(
            "id",
            "dtime",
            "step_number",
            "step_name",
            "group_code",
            "oleh_id",
            "oleh_nama",
            "keterangan",
        ),
//        "mainFields" => array(
//            "transaksi_id",
//            "key",
//            "value",
//        ),
//        "detailFields" => array(
//            "transaksi_id",
//            "produk_id",
//            "key",
//            "value",
//        ),
//        "mainValues" => array(
//            "transaksi_id",
//            "key",
//            "value",
//        ),
//        "detailValues" => array(
//            "transaksi_id",
//            "produk_jenis",
//            "produk_id",
//            "key",
//            "value",
//        ),
//        "applets" => array(
//            "transaksi_id",
//            "mdl_name",
//            "key",
//            "label",
//            "description",
//
//        ),
        "elements" => array(
            "transaksi_id",
            "mdl_name",
            "key",
            "labelValue",
            "nama",
            "name",
            "label",
            "element_type",
            "element_name",
            "element_label",
            "labelValue",
            "label",
            "produk_id",
            "produk_nama",
            "produk_label",
            "produk_ord_hrg",
            "produk_ord_jml",
            "alias",
            "alamat_1",
            "alamat_2",
            "kelurahan",
            "kecamatan",
            "kabupaten",
            "propinsi",
            "tlp",
            "tlp_1",
            "tlp_2",
            "folders",
            "folders_nama",
            "npwp",
            "no_ktp",
            "kredit_limit",
            "value",
            "alias",
            "contact_person",
            "extern_name",
//            "contents",

//            "contents_intext",


        ),
        "paymentSrc" => array(
            "id",
            "_key",
            "jenis",
            "target_jenis",
            "reference_jenis",
            "transaksi_id",
            "extern_id",
            "extern_nama",
            "nomer",
            "nomer_top",
            "label",
            "tagihan",
            "terbayar",
            "sisa",
            "cabang_id",
            "cabang_nama",
            "oleh_id",
            "oleh_nama",
            "dtime",
            "fulldate",
            "valas_id",
            "valas_nama",
            "valas_nilai",
            "tagihan_valas",
            "terbayar_valas",
            "sisa_valas",
            "pph_23",
            "terbayar_pph23",
            "extern_label2",

            "dpp_ppn",
            "ppn",
            "ppn_approved",
            "ppn_sisa",
            "ppn_status",
            "extern_nilai2",
            "extern_date2",
            "extern2_id",
            "extern2_nama",
            "extern_jenis",
            "ppn_pph_faktor",
            "extern_nilai2",
            "extern_nilai3",
            "extern_nilai4",
            "extern_nilai5",
            "npwp",

            "payment_locked",
            "cash_account",
            "cash_account_nama",
            "project_id",
            "project_nama",
            "customers_id",
            "customers_nama",
            "suppliers_id",
            "suppliers_nama",
        ),
        "paymentAntiSrc" => array(
            "id",
            "_key",
            "jenis",
            "target_jenis",
            "reference_jenis",
            "transaksi_id",
            "extern_id",
            "extern_nama",
            "nomer",
            "label",
            "tagihan",
            "terbayar",
            "sisa",
            "cabang_id",
            "cabang_nama",
            "oleh_id",
            "oleh_nama",
            "dtime",
            "fulldate",
            "valas_id",
            "valas_nama",
            "valas_nilai",
            "tagihan_valas",
            "terbayar_valas",
            "sisa_valas",
            "pph_23",
            "terbayar_pph23",
            "extern_label2",
            "extern2_id",
            "extern2_nama",
        ),
        "extras" => array(
            "id",
            "master_id",
            "transaksi_id",
            "_key",
            "_label",
            "_value",
            "group_id",
            "state",
            "proposed_by",
            "proposed_dtime",
            "done_by",
            "done_dtime",
        ),
        "dueDate" => array(
            "id",
            "transaksi_id",
            "customers_id",
            "customers_nama",
            "cabang_id",
            "cabang_nama",
            "nomer",
            "dtime",
            "due_date",
            "oleh_nama",
            "oleh_id",
            "transaksi_nilai",
            "release_id",//id payment
            "keterangan",
            "status",//1 active
            "trash",
        ),
        "report" => array(
            "id",
            "id_master",
            "id_top",
            "nomer_top",
            "nomer",
            "dtime",
            "cabang_id",
            "cabang_nama",
            "cabang2_id",
            "cabang2_nama",
            "gudang_id",
            "gudang_nama",
            "oleh_id",
            "oleh_nama",
            "customers_id",
            "customers_nama",
            "suppliers_id",
            "suppliers_nama",
            "jenis",
            "jenis_master",
            "trash",
            "trash_4",
            "counters",
        ),
        "uangMuka" => array(
            "id",
            "_key",
            "jenis",
            "target_jenis",
            "reference_jenis",
            "transaksi_id",
            "extern_id",
            "extern_nama",
            "nomer",
            "note",
            "label",
            "tagihan",
            "terbayar",
            "sisa",
            "cabang_id",
            "cabang_nama",
            "oleh_id",
            "oleh_nama",
            "dtime",
            "fulldate",
            "valas_id",
            "valas_nama",
            "valas_nilai",
            "tagihan_valas",
            "terbayar_valas",
            "sisa_valas",
            "pph_23",
            "terbayar_pph23",
            "extern_label2",
            "ppn",
            "ppn_approved",
            "ppn_sisa",
            "ppn_status",
            "extern_nilai2",
            "extern_date2",
            //----
            "tagihan_ppn",
            "diskon_ppn",
            "dihapus_ppn",
            "returned_ppn",
            "terbayar_ppn",
            "sisa_ppn",
        ),
        "dataRegistry" => array(
            "transaksi_id",
            "main",
            "items",
            "items2",
            "items2_sum",
            "itemSrc",
            "itemSrc_sum",
            "items3",
            "items3_sum",
            "items4",
            "items4_sum",
            "items_noapprove",
            "rsltItems",
            "rsltItems2",
            "rsltItems3",
            "rsltItems3_sub",
            "tableIn_master",
            "tableIn_detail",
            "tableIn_detail2_sum",
            "tableIn_detail_rsltItems",
            "tableIn_detail_rsltItems2",
            "tableIn_master_values",
            "tableIn_detail_values",
            "tableIn_detail_values_rsltItems",
            "tableIn_detail_values_rsltItems2",
            "tableIn_detail_values2_sum",
            "rsltItems3_sub",
            "main_add_values",
            "main_add_fields",
            "main_elements",
            "main_inputs",
            "main_inputs_orig",
            "receiptDetailFields",
            "receiptSumFields",
            "receiptDetailFields2",
            "receiptDetailSrcFields",
            "receiptSumFields2",
            "jurnal_index",
            "postProcessor",
            "preProcessor",
            "revert",
            "items_komposisi",
            "jurnalItems",
            "componentsBuilder",
            "items5_sum",
            "items6_sum",
            "items6",
            "items7_sum",
            "items7",
            "items8_sum",
            "items9_sum",
            "items10_sum",
            "rsltItems3_sub",
            "rsltItems_revert",
            "rsltItems2_revert",
            "mainOriginal",
            "itemsOriginal",
        ),
        "garansi" => array(
            "transaksi_id",
            "produk_jenis",
            "produk_id",
            "produk_nama",
            "valid_qty",
            "produk_label",
            "produk_keterangan",
            "produk_folders",
            "produk_kode",
            "satuan",
            "trash",
            "status",
            "keterangan",
            "oleh_id",
            "oleh_nama",
            "cabang_id",
            "cabang_nama",
            "garansi_tarif",
            "garansi_nilai",
            "garansi_dtime",
            "customers_id",
            "customers_nama",
            "produk_ord_hrg",
            "produk_ord_ppn",
            "produk_ord_netto",
        ),
        "rekening" => array(
            "rekening",
            "transaksi_id",
            "produk_id",
            "produk_nama",
            "extern_id",
            "extern_nama",
            "debet",
            "kredit",
            "debet_awal",
            "debet_akhir",
            "kredit_awal",
            "kredit_akhir",
            "qty_debet_awal",
            "qty_debet",
            "qty_debet_akhir",
            "qty_kredit_awal",
            "qty_kredit",
            "qty_kredit_akhir",
            "harga",


        ),
        //-------
        "counters" => array(
            "transaksi_id",
            "_company",
            "_company_jenisTr",
            "_company_jenisTrMaster",
            "_company_stepCode",
            "_company_supplierID",
            "_company_customerID",
            "_company_olehID",
            "_company_sellerID",
            "_company_cabangID",
            "_company_cabang2ID",
            "_company_gudangID",
            "_company_gudang2ID",
            "_company_modul",
            "_company_subModul",
            "_company_cabangID_jenisTr",
            "_company_cabangID_jenisTrMaster",
            "_company_cabangID_stepCode",
            "_company_cabangID_supplierID",
            "_company_cabangID_customerID",
            "_company_cabangID_olehID",
            "_company_cabangID_sellerID",
            "_company_cabangID_cabangID",
            "_company_cabangID_cabang2ID",
            "_company_cabangID_gudangID",
            "_company_cabangID_gudang2ID",
            "_company_cabangID_modul",
            "_company_cabangID_subModul",
            "_company_cabangID_modul_jenisTr",
            "_company_cabangID_modul_jenisTrMaster",
            "_company_cabangID_modul_stepCode",
            "_company_cabangID_modul_supplierID",
            "_company_cabangID_modul_customerID",
            "_company_cabangID_modul_olehID",
            "_company_cabangID_modul_sellerID",
            "_company_cabangID_modul_cabangID",
            "_company_cabangID_modul_cabang2ID",
            "_company_cabangID_modul_gudangID",
            "_company_cabangID_modul_gudang2ID",
            "_company_cabangID_modul_subModul",
            "_company_cabangID_modul_subModul_jenisTr",
            "_company_cabangID_modul_subModul_jenisTrMaster",
            "_company_cabangID_modul_subModul_stepCode",
            "_company_cabangID_modul_subModul_supplierID",
            "_company_cabangID_modul_subModul_customerID",
            "_company_cabangID_modul_subModul_olehID",
            "_company_cabangID_modul_subModul_sellerID",
            "_company_cabangID_modul_subModul_cabangID",
            "_company_cabangID_modul_subModul_cabang2ID",
            "_company_cabangID_modul_subModul_gudangID",
            "_company_cabangID_modul_subModul_gudang2ID",
            "_company_cabangID_modul_subModul_jenisTr_jenisTrMaster",
            "_company_cabangID_modul_subModul_jenisTr_stepCode",
            "_company_cabangID_modul_subModul_jenisTr_supplierID",
            "_company_cabangID_modul_subModul_jenisTr_customerID",
            "_company_cabangID_modul_subModul_jenisTr_olehID",
            "_company_cabangID_modul_subModul_jenisTr_sellerID",
            "_company_cabangID_modul_subModul_jenisTr_cabangID",
            "_company_cabangID_modul_subModul_jenisTr_cabang2ID",
            "_company_cabangID_modul_subModul_jenisTr_gudangID",
            "_company_cabangID_modul_subModul_jenisTr_gudang2ID",
            "_company_cabangID_modul_subModul_jenisTr_stepCode_jenisTrMaster",
            "_company_cabangID_modul_subModul_jenisTr_stepCode_supplierID",
            "_company_cabangID_modul_subModul_jenisTr_stepCode_customerID",
            "_company_cabangID_modul_subModul_jenisTr_stepCode_olehID",
            "_company_cabangID_modul_subModul_jenisTr_stepCode_sellerID",
            "_company_cabangID_modul_subModul_jenisTr_stepCode_cabangID",
            "_company_cabangID_modul_subModul_jenisTr_stepCode_cabang2ID",
            "_company_cabangID_modul_subModul_jenisTr_stepCode_gudangID",
            "_company_cabangID_modul_subModul_jenisTr_stepCode_gudang2ID",
            "company_id",
            "modul",
            "subModul",
        ),
        "payment" => array(
            "transaksi_id",
            "bank_id",
            "bank_nama",
            "bank_rekening_id",
            "bank_rekening_nama",
            "bank_from",
            "bank_id_from",
            "bank_nama_from",
            "bank_rekening_id_from",
            "bank_rekening_nama_from",
        ),
    );
    protected $sortBy = array(
        "kolom" => "dtime",
        "mode" => "ASC",
    );
    protected $aliasName = array(
        "tmp" => array(
            "id" => "id_tmp",
            "jenis" => "jenis_tmp",
        ),
        "main" => array(),
        "detail" => array(
            "id" => "id_detail",
            "trash" => "trash_detail",
            "status" => "status_detail",
        ),
        "sub_detail" => array(
            "id" => "id_sub_detail",
            "trash" => "trash_sub_detail",
            "status" => "status_sub_detail",
        ),
        "items3_sum" => array(
            "id" => "id_sub_detail",
            "trash" => "trash_sub_detail",
            "status" => "status_sub_detail",
        ),
        "rekening" => array(
            "id" => "id_detail",
        ),

    );
    protected $joinedFilter = array();
    protected $jointSelectFields;
    protected $blockFields;
    protected $cekPrevalue;
    protected $registryFields = array(
        "main",
        "items",
        "items2",
        "items2_sum",
        "itemSrc",
        "itemSrc_sum",
        "items3",
        "items3_sum",
        "items4",
        "items4_sum",
        "items_noapprove",
        "rsltItems",
        "rsltItems2",
        "rsltItems3",
        "tableIn_master",
        "tableIn_detail",
        "tableIn_detail2_sum",
        "tableIn_detail_rsltItems",
        "tableIn_detail_rsltItems2",
        "tableIn_master_values",
        "tableIn_detail_values",
        "tableIn_detail_values_rsltItems",
        "tableIn_detail_values_rsltItems2",
        "tableIn_detail_values2_sum",
        "main_add_values",
        "main_add_fields",
        "main_elements",
        "main_inputs",
        "main_inputs_orig",
        "receiptDetailFields",
        "receiptSumFields",
        "receiptDetailFields2",
        "receiptDetailSrcFields",
        "receiptDetailFields2",
        "receiptSumFields2",
        "jurnal_index",
        "postProcessor",
        "preProcessor",
        "revert",
        "items_komposisi",
        "jurnalItems",
        "componentsBuilder",
        "items5_sum",
        "items6_sum",
        "items6",
        "items7",
        "items7_sum",
        "items8_sum",
        "items9_sum",
        "items10_sum",
        "rsltItems3_sub",
        "rsltItems_revert",
        "rsltItems2_revert",
        "requiredParam",
        "componentsBuilder",
        "items_elements",
        "itemPrice_sum",
        "mainOriginal",
        "itemsOriginal",
        "coreBuilder",
        "diskon_event",
        "cashback_event",
    );
    protected $selectTableNames = array(
//        "tmp" => "pembelian_transaksi_tmpcart",
        "main" => "pembelian_transaksi",
        "detail" => "pembelian_transaksi_data",
        "sub_detail" => "pembelian_transaksi_data_item",
        "items3_sum" => "pembelian_transaksi_data_item3_sum",
//        "mainFields" => "pembelian_transaksi_fields",
//        "detailFields" => "pembelian_transaksi_data_fields",
//        "mainValues" => "pembelian_transaksi_values",
//        "detailValues" => "pembelian_transaksi_data_values",
//        "applets" => "pembelian_transaksi_applets",
        "sign" => "pembelian_transaksi_sign",
//        "paymentSrc" => "pembelian_transaksi_payment_source",
//        "paymentAntiSrc" => "pembelian_transaksi_payment_antisource",
//        "registry" => "pembelian_transaksi_registry",
        "dataRegistry" => "pembelian_transaksi_data_registry",
        "elements" => "pembelian_transaksi_element",
//        "extras" => "transaksi_extstep",
//        "dueDate" => "pembelian_transaksi_due_date",
//        "uangMuka" => "pembelian_transaksi_uang_muka_source",
//        "efaktur" => "pembelian_transaksi_efaktur",
//        "garansi" => "pembelian_transaksi_data_garansi",
//        "rekening" => "_rek_pembelian_transaksi_data",
        //------
        "counters" => "pembelian_transaksi_counters",
    );
    protected $childData = array(
        "main_entries",
        "items",
//        "items2",
        "items2_sum",
//        "items_values2_sum",
//        "items3",
        "items3_sum",
//        "items4",
        "items4_sum",
//        "items5",
        "items5_sum",
//        "items6",
        "items6_sum",
//        "items7",
        "items7_sum",
//        "items8",
        "items8_sum",
        "items9_sum",
        "items10_sum",
//        "elements",
//        "elements",
    );


    public function getChildData()
    {
        return $this->childData;
    }

    public function setChildData($childData)
    {
        $this->childData = $childData;
    }


    public function getSelectTableNames()
    {
        return $this->selectTableNames;
    }

    public function setSelectTableNames($selectTableNames)
    {
        $this->selectTableNames = $selectTableNames;
    }

    public function getRegistryFields()
    {
        return $this->registryFields;
    }

    public function setRegistryFields($registryFields)
    {
        $this->registryFields = $registryFields;
    }

    public function getJointSelectFields()
    {
        return $this->jointSelectFields;
    }

    public function setJointSelectFields($jointSelectFields)
    {
        //string setJointSelectFields("main,detail") untuk memlilih kolom yang diselect, jika tidak diset akan diambil dari field yang ada lihat array fields
        $this->jointSelectFields = $jointSelectFields;
    }

    public function getBlockFields()
    {
        return $this->blockFields;
    }

    public function setBlockFields($blockFields)
    {
        $this->blockFields = $blockFields;
    }

    public function addFilterJoin($f)
    {
        $this->joinedFilter[] = $f;
    }

    public function getJoinedFilter()
    {
        return $this->joinedFilter;
    }

    public function setJoinedFilter($joinedFilter)
    {
        $this->joinedFilter = $joinedFilter;
    }

    public function getAliasName()
    {
        return $this->aliasName;
    }

    public function setAliasName($aliasName)
    {
        $this->aliasName = $aliasName;
    }

    private $keyWord;

    //region getter-setter
    public function getKeyWord()
    {
        return $this->keyWord;
    }

    public function setKeyWord($keyWord)
    {
        $this->keyWord = $keyWord;
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function getTableNames()
    {
        return $this->tableNames;
    }

    public function setTableNames($tableNames)
    {
        $this->tableNames = $tableNames;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    public function addFilter($f)
    {
        $this->filters[] = $f;
    }

    public function lookupRecentHistories($limit = 0)
    {
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $this->db->select("*,pembelian_transaksi.oleh_id as oleh_id,pembelian_transaksi.oleh_nama as oleh_nama,pembelian_transaksi.cabang_id as cabang_id");
        // $this->db->from($this->tableNames['main']);
        if ($limit > 0) {
            $this->db->limit((int)$limit);
        }

        $this->db->order_by("id", "desc");
        $result = $this->db->get($this->tableNames['main']);
        //        cekBiru($this->db->last_query());
        return $result;
    }

    //endregion

    public function getCekPrevalue()
    {
        $this->addFilter("id=" . $this->cekPrevalue);
        $result = array();
        $localFilters = array();
        if (sizeof($this->filters) > 0) {
            foreach ($this->filters as $f) {
                $tmpArr = explode("=", $f);
                $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");

            }
        }
        $query = $this->db->select()
            ->from($this->tableName)
            ->where($localFilters)
            ->limit(1)
            ->get_compiled_select();
        $tmp = $this->db->query("{$query} FOR UPDATE")->result();

        return $tmp;
    }

    public function setCekPrevalue($cekPrevalue)
    {
        $this->cekPrevalue = $cekPrevalue;
    }

    //--menambahkan filter kolom

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getAvailTable($historyFields)
    {
        $columnFilter = array();
        if (sizeof($historyFields) > 0) {
            $noKey = 0;
            foreach ($historyFields as $key => $data) {
                if (in_array($key, $this->fields['main'])) {
                    $columnFilter[$noKey] = $key;
                }
                $noKey++;
            }
        }
        return $columnFilter;
    }

    //--menghasilkan beberapa baris entri terbaru

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }


    public function lookupRecentUndoneEntries_joined__($cab, $gud)
    {
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        $this->db->group_start();
        $this->db->where(array("pembelian_transaksi.cabang_id" => $cab));
        $this->db->or_where(array("pembelian_transaksi.cabang2_id" => $cab));
        $this->db->group_end();

        $this->db->group_start();
        $this->db->where(array("gudang_id" => $gud));
        $this->db->or_where(array("gudang2_id" => $gud));
        $this->db->group_end();

        $this->db->select("*,pembelian_transaksi.oleh_id as oleh_id,pembelian_transaksi.oleh_nama as oleh_nama,pembelian_transaksi.cabang_id as cabang_id");
        // $this->db->from($this->tableNames['main']);
        //        $this->db->limit(10);
        $this->db->group_by(array("transaksi_id", "next_substep_code"));
        $this->db->order_by($this->tableNames['main'] . ".id", "desc");


        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id ");
        //        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id and ((gudang_id<>gudang2_id and gudang2_id='$gud') or (gudang_id=gudang2_id and gudang_id='$gud'))");
        $result = $this->db->get($this->tableNames['main']);
        //        echo($this->db->last_query());
        return $result;
    }

    public function lookupRecentUndoneEntries_joined($array)
    {
        $criteria = array();
        $criteria2 = "";
//        $this->filters[99] = $this->tableNames['detail'] . ".trash='0'";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        //        arrPrint(sizeof($array));

        if (sizeof($array) > 0) {
            $cab = isset($array['cabang_id']) ? $array['cabang_id'] : NULL;
            $gud = isset($array['gudang_id']) ? $array['gudang_id'] : NULL;
            if ($cab != NULL) {

                $this->db->group_start();
                $this->db->where(array("pembelian_transaksi.cabang_id" => $cab));
                $this->db->or_where(array("pembelian_transaksi.cabang2_id" => $cab));
                $this->db->group_end();
            }
            if ($gud != NULL) {

                $this->db->group_start();
                $this->db->where(array("gudang_id" => $gud));
                $this->db->or_where(array("gudang2_id" => $gud));
                $this->db->group_end();
            }
        }


        $this->db->select("*,pembelian_transaksi.oleh_id as oleh_id,pembelian_transaksi.oleh_nama as oleh_nama,pembelian_transaksi.cabang_id as cabang_id, pembelian_transaksi.dtime as dtime");
        // $this->db->from($this->tableNames['main']);
        //        $this->db->limit(100);
        $this->db->group_by(array("transaksi_id", "next_substep_code"));
        $this->db->order_by($this->tableNames['main'] . ".id", "desc");


        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id and " . $this->tableNames['detail'] . ".trash=0 ");
        //        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id " and $this->tableNames['detail'] . ".trash =0 ");
        //        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id and ((gudang_id<>gudang2_id and gudang2_id='$gud') or (gudang_id=gudang2_id and gudang_id='$gud'))");
        $result = $this->db->get($this->tableNames['main']);
        //        echo($this->db->last_query());
        return $result;
    }

    public function lookupRecentUndoneEntriesNoGroup_joined($array)
    {
        $criteria = array();
        $criteria2 = "";
//        $this->filters[99] = $this->tableNames['detail'] . ".trash='0'";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        //        arrPrint(sizeof($array));

        if (sizeof($array) > 0) {
            $cab = isset($array['cabang_id']) ? $array['cabang_id'] : NULL;
            $gud = isset($array['gudang_id']) ? $array['gudang_id'] : NULL;
            if ($cab != NULL) {

                $this->db->group_start();
                $this->db->where(array("pembelian_transaksi.cabang_id" => $cab));
                $this->db->or_where(array("pembelian_transaksi.cabang2_id" => $cab));
                $this->db->group_end();
            }
            if ($gud != NULL) {

                $this->db->group_start();
                $this->db->where(array("gudang_id" => $gud));
                $this->db->or_where(array("gudang2_id" => $gud));
                $this->db->group_end();
            }
        }


        $this->db->select("*,pembelian_transaksi.oleh_id as oleh_id,pembelian_transaksi.oleh_nama as oleh_nama,pembelian_transaksi.cabang_id as cabang_id, pembelian_transaksi.dtime as dtime");
        // $this->db->from($this->tableNames['main']);
        //        $this->db->limit(100);
//        $this->db->group_by(array("transaksi_id", "next_substep_code"));
        $this->db->order_by($this->tableNames['main'] . ".id", "desc");


        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id and " . $this->tableNames['detail'] . ".trash =0 ");
        //        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id " and $this->tableNames['detail'] . ".trash =0 ");
        //        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id and ((gudang_id<>gudang2_id and gudang2_id='$gud') or (gudang_id=gudang2_id and gudang_id='$gud'))");
        $result = $this->db->get($this->tableNames['main']);
        //        echo($this->db->last_query());
        return $result;
    }

    public function lookupRecentUndoneEntries_joinedRekening($array)
    {
        $criteria = array();
        $criteria2 = "";
//        $this->filters[99] = $this->tableNames['detail'] . ".trash='0'";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        //        arrPrint(sizeof($array));

        if (sizeof($array) > 0) {
            $cab = isset($array['cabang_id']) ? $array['cabang_id'] : NULL;
            $gud = isset($array['gudang_id']) ? $array['gudang_id'] : NULL;
            if ($cab != NULL) {

                $this->db->group_start();
                $this->db->where(array("pembelian_transaksi.cabang_id" => $cab));
                $this->db->or_where(array("pembelian_transaksi.cabang2_id" => $cab));
                $this->db->group_end();
            }
            if ($gud != NULL) {

                $this->db->group_start();
                $this->db->where(array("gudang_id" => $gud));
                $this->db->or_where(array("gudang2_id" => $gud));
                $this->db->group_end();
            }
        }


        $this->db->select("*,pembelian_transaksi.oleh_id as oleh_id,pembelian_transaksi.oleh_nama as oleh_nama,pembelian_transaksi.cabang_id as cabang_id, pembelian_transaksi.dtime as dtime");
        // $this->db->from($this->tableNames['main']);
        //        $this->db->limit(100);
        $this->db->group_by(array("transaksi_id", "next_step_code"));
        $this->db->order_by($this->tableNames['main'] . ".id", "desc");


        $this->db->join($this->tableNames['rekening'], $this->tableNames['rekening'] . ".transaksi_id = " . $this->tableNames['main'] . ".id ");

        //        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id " and $this->tableNames['detail'] . ".trash =0 ");
        //        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id and ((gudang_id<>gudang2_id and gudang2_id='$gud') or (gudang_id=gudang2_id and gudang_id='$gud'))");

        $result = $this->db->get($this->tableNames['main']);
//                echo($this->db->last_query());
        return $result;
    }

    public function lookupUndoneEntries_joined__($cab, $gud)
    {
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        $this->db->group_start();
        $this->db->where(array("pembelian_transaksi.cabang_id" => $cab));
        $this->db->or_where(array("pembelian_transaksi.cabang2_id" => $cab));
        $this->db->group_end();

        $this->db->group_start();
        $this->db->where(array("pembelian_transaksi.gudang_id" => $gud));
        $this->db->or_where(array("pembelian_transaksi.gudang2_id" => $gud));
        $this->db->group_end();

        $this->db->select("*,pembelian_transaksi.oleh_id as oleh_id,pembelian_transaksi.oleh_nama as oleh_nama,pembelian_transaksi.cabang_id as cabang_id");
        // $this->db->from($this->tableNames['main']);
        //        $this->db->limit(20);
        $this->db->group_by(array("transaksi_id", "next_substep_code"));
        $this->db->order_by($this->tableNames['main'] . ".id", "desc");


        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id ");
        //        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id and ((gudang_id<>gudang2_id and gudang2_id='$gud') or (gudang_id=gudang2_id and gudang_id='$gud'))");
        //        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id and ((gudang_id<>gudang2_id and gudang2_id='$gud') or (gudang_id=gudang2_id))");
        $result = $this->db->get($this->tableNames['main']);
        //        cekmerah($this->db->last_query());
        return $result;
    }

    public function lookupUndoneEntries_joined($array)
    {
        $criteria = array();
        $criteria2 = "";
        $this->filters[99] = $this->tableNames['detail'] . ".trash='0'";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        //cekHitam(sizeof($array));
        if (sizeof($array) > 0) {
            $cab = $array['cabang_id'];
            $gud = $array['gudang_id'];


            $this->db->group_start();
            $this->db->where(array("pembelian_transaksi.cabang_id" => $cab));
            $this->db->or_where(array("pembelian_transaksi.cabang2_id" => $cab));
            $this->db->group_end();

            $this->db->group_start();
            $this->db->where(array("pembelian_transaksi.gudang_id" => $gud));
            $this->db->or_where(array("pembelian_transaksi.gudang2_id" => $gud));
            $this->db->group_end();
        }

        if (isset($this->keyWord)) {
            $key = isset($this->keyWord) ? $this->keyWord : "";
            $this->createSmartSearch($key, array("pembelian_transaksi.customers_nama", "pembelian_transaksi.oleh_nama", "pembelian_transaksi.suppliers_nama"));
        }

        $this->db->select("*,pembelian_transaksi.oleh_id as oleh_id,pembelian_transaksi.oleh_nama as oleh_nama,pembelian_transaksi.cabang_id as cabang_id,pembelian_transaksi.dtime as dtime");
        // $this->db->from($this->tableNames['main']);
        //        $this->db->limit(20);
        $this->db->group_by(array("transaksi_id", "next_substep_code"));
        $this->db->order_by($this->tableNames['main'] . ".id", "desc");


        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id ");
        //        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id and ((gudang_id<>gudang2_id and gudang2_id='$gud') or (gudang_id=gudang2_id and gudang_id='$gud'))");
        //        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id and ((gudang_id<>gudang2_id and gudang2_id='$gud') or (gudang_id=gudang2_id))");
        $result = $this->db->get($this->tableNames['main']);
        //        cekmerah($this->db->last_query());
        return $result;
    }


    public function lookupRecentUndoneEntries_joinedAsset($array)
    {
        $criteria = array();
        $criteria2 = "";
        //        $this->filters[99] = $this->tableNames['detail'] . ".trash='0'";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        //        arrPrint(sizeof($array));

        if (sizeof($array) > 0) {
            $cab = $array['cabang_id'];
            $gud = $array['gudang_id'];

            $this->db->group_start();
            $this->db->where(array("pembelian_transaksi.cabang_id" => $cab));
            $this->db->or_where(array("pembelian_transaksi.cabang2_id" => $cab));
            $this->db->group_end();

            $this->db->group_start();
            $this->db->where(array("gudang_id" => $gud));
            $this->db->or_where(array("gudang2_id" => $gud));
            $this->db->group_end();
        }


        $this->db->select("*,pembelian_transaksi.oleh_id as oleh_id,pembelian_transaksi.oleh_nama as oleh_nama,pembelian_transaksi.cabang_id as cabang_id");
        // $this->db->from($this->tableNames['main']);
        //        $this->db->limit(100);
        //        $this->db->group_by(array("transaksi_id", "next_substep_code"));
        $this->db->order_by($this->tableNames['main'] . ".id", "desc");


        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id ");
        //        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id " and $this->tableNames['detail'] . ".trash =0 ");
        //        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id and ((gudang_id<>gudang2_id and gudang2_id='$gud') or (gudang_id=gudang2_id and gudang_id='$gud'))");
        $result = $this->db->get($this->tableNames['main']);
        //        echo($this->db->last_query());
        return $result;
    }

    //--menghasilkan beberapa baris entri  sesuai jumlah, limit, dan nomor halaman
    public function lookupHistories($jmlData, $limit, $page)
    {
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        // $jmlData=$this->lookupHistoryCount();
        if (isset($this->keyWord)) {
            $key = isset($this->keyWord) ? $this->keyWord : "";

            $this->createSmartSearch($key, array("customers_nama", "oleh_nama", "suppliers_nama"));
        }

        $numPages = ceil($jmlData / $limit);
        // $page = $_GET[page] ? $_GET[page] : 1;
        $offset = ($page - 1) * $limit;

        $this->db->select("*,pembelian_transaksi.oleh_id as oleh_id,pembelian_transaksi.oleh_nama as oleh_nama,pembelian_transaksi.cabang_id as cabang_id,pembelian_transaksi.id as tid");
        $this->db->limit($limit, $offset);
        $this->db->order_by($this->tableNames['main'] . ".id", "desc");

        return $this->db->get($this->tableNames['main']);
    }

    public function lookupHistories4Api($jmlData, $limit, $page)
    {
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $numPages = ceil($jmlData / $limit);
        $offset = ($page - 1) * $limit;
        $this->db->select("*,pembelian_transaksi.oleh_id as oleh_id,pembelian_transaksi.oleh_nama as oleh_nama,pembelian_transaksi.id as tid");
        $this->db->limit($limit, $offset);
        $this->db->order_by($this->tableNames['main'] . ".id", "desc");
        return $this->db->get($this->tableNames['main']);
    }

    public function lookupHistoriesDtAll()
    {
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $this->db->select("*,pembelian_transaksi.oleh_id as oleh_id,pembelian_transaksi.oleh_nama as oleh_nama,pembelian_transaksi.cabang_id as cabang_id");
        $this->db->order_by("id", "desc");
        $rslt = $this->db->get($this->tableNames['main'])->num_rows();
        return ($rslt);
    }

    public function lookupHistoriesDtFil($jmlData, $limit, $length, $historyFields)
    {
        $arrAvailColumn = $this->getAvailTable($historyFields);
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        if (isset($this->keyWord)) {
            $key = isset($this->keyWord) ? $this->keyWord : "";
            $this->createSmartSearch($key, $arrAvailColumn);
        }
        $this->db->select("*,pembelian_transaksi.oleh_id as oleh_id,pembelian_transaksi.oleh_nama as oleh_nama,pembelian_transaksi.id as tid");
        $this->db->order_by($this->tableNames['main'] . ".id", "desc");
        return $this->db->get($this->tableNames['main'])->num_rows();
    }

    public function lookupHistoriesDt($jmlData, $limit, $length, $historyFields)
    {
        $arrAvailColumn = $this->getAvailTable($historyFields);
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        if (isset($this->keyWord)) {
            $key = isset($this->keyWord) ? $this->keyWord : "";
            $this->createSmartSearch($key, $arrAvailColumn);
        }
        if (isset($_GET['startDate']) && isset($_GET['startDate'])) {

        }
        $this->db->select("*,pembelian_transaksi.oleh_id as oleh_id,pembelian_transaksi.oleh_nama as oleh_nama,pembelian_transaksi.id as tid");
        $this->db->limit($length, $limit);
        //jika ada perintah sorting
        if (isset($_GET['order'][0]['column']) && $_GET['order'][0]['column'] != '') {
            $column = isset($arrAvailColumn[$_GET['order'][0]['column']]) ? $arrAvailColumn[$_GET['order'][0]['column']] : "id";
            $sortMode = isset($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : "desc";
            $this->db->order_by($this->tableNames['main'] . ".$column", "$sortMode");
        }
        else {
            $this->db->order_by($this->tableNames['main'] . ".id", "desc");
        }
        return $this->db->get($this->tableNames['main']);
    }

    public function lookupHistories_joined($jmlData, $limit, $page)
    {
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        $numPages = ceil($jmlData / $limit);
        $offset = ($page - 1) * $limit;

        $this->db->select("*,pembelian_transaksi.oleh_id as oleh_id,pembelian_transaksi.oleh_nama as oleh_nama,pembelian_transaksi.cabang_id as cabang_id");

        $this->db->group_start();
        $this->db->where(array("pembelian_transaksi.cabang_id" => $this->session->login['cabang_id']));
        $this->db->or_where(array("pembelian_transaksi.cabang2_id" => $this->session->login['cabang_id']));
        $this->db->group_end();

        $this->db->group_start();
        $this->db->where(array("gudang_id" => $this->session->login['gudang_id']));
        $this->db->or_where(array("gudang2_id" => $this->session->login['gudang_id']));
        $this->db->group_end();


        $this->db->limit($limit, $offset);
        $this->db->order_by("pembelian_transaksi.id", "desc");
        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id ");
        $result = $this->db->get($this->tableNames['main']);


        //        arrPrint($result->result());
        return $result;
    }

    public function lookupHistories_joined_all()
    {
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }


        $this->db->select("*,pembelian_transaksi.oleh_id as oleh_id,pembelian_transaksi.oleh_nama as oleh_nama,pembelian_transaksi.cabang_id as cabang_id");
        //        $this->db->limit($limit, $offset);

        $this->db->group_start();
        $this->db->where(array("pembelian_transaksi.cabang_id" => $this->session->login['cabang_id']));
        $this->db->or_where(array("pembelian_transaksi.cabang2_id" => $this->session->login['cabang_id']));
        $this->db->group_end();

        $this->db->group_start();
        $this->db->where(array("gudang_id" => $this->session->login['gudang_id']));
        $this->db->or_where(array("gudang2_id" => $this->session->login['gudang_id']));
        $this->db->group_end();

        $this->db->order_by("pembelian_transaksi.id", "desc");
        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id ");
        $result = $this->db->get($this->tableNames['main']);


        //        arrPrint($result->result());
        return $result;
    }

    public function lookupHistoryCount()
    {
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $this->db->select("*,pembelian_transaksi.oleh_id as oleh_id,pembelian_transaksi.oleh_nama as oleh_nama,pembelian_transaksi.cabang_id as cabang_id");
        $this->db->order_by("id", "desc");
        $rslt = $this->db->get($this->tableNames['main'])->num_rows();
        return ($rslt);
    }

    public function lookupHistoryCount_joined()
    {
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $this->db->select("*,pembelian_transaksi.oleh_id as oleh_id,pembelian_transaksi.oleh_nama as oleh_nama,pembelian_transaksi.cabang_id as cabang_id");
        $this->db->order_by("pembelian_transaksi.id", "desc");
        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id ");
        $rslt = $this->db->get($this->tableNames['main'])->num_rows();
        return ($rslt);
    }

    public function lookupMainTransaksi()
    {
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $this->db->select("*,pembelian_transaksi.oleh_id as oleh_id,pembelian_transaksi.oleh_nama as oleh_nama,pembelian_transaksi.cabang_id as cabang_id");
        return $this->db->get($this->tableNames['main']);
    }

    public function lookupMainTransaksiMultiIDs()
    {
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $this->db->select("*,pembelian_transaksi.oleh_id as oleh_id,pembelian_transaksi.oleh_nama as oleh_nama,pembelian_transaksi.cabang_id as cabang_id");
        $tmp = $this->db->get($this->tableNames['main'])->result();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $spec) {
                $result[$spec->id] = (array)$spec;
            }
        }
        else {
            $result = array();
        }
    }

    public function lookupDetailTransaksi($transaksi_id)
    {
        if (is_array($transaksi_id)) {
            $this->addFilter("transaksi_id in ('" . implode("','", $transaksi_id) . "')");
        }
        else {
            $this->addFilter("transaksi_id='$transaksi_id'");
//            $criteria = array(
//                "transaksi_id" => $transaksi_id,
//                "produk_jenis" => "produk",
//                "trash" => "0",
//            );
        }
//        $this->addFilter("trash='0'");
//        $this->addFilter("qty_kredit >'0'");
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $temp = $this->db->get($this->tableNames['detail'])->result();
        $hasil = array();
        if (count($temp) > 0) {
            foreach ($temp as $temp_0) {
                $hasil[$temp_0->transaksi_id][] = $temp_0;
            }
        }
        return $hasil;
    }


    public function lookupDetailTransaksiMultiIDs($transaksi_id)
    {
        if (is_array($transaksi_id)) {
            $this->addFilter("transaksi_id in ('" . implode("','", $transaksi_id) . "')");
        }
        else {
            $this->addFilter("transaksi_id='$transaksi_id'");
        }
        $this->addFilter($this->tableNames['detail'] . ".trash='0'");
        $this->addFilter($this->tableNames['detail'] . ".qty_kredit >'0'");
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $temp = $this->db->get($this->tableNames['detail'])->result();
        $hasil = array();
        if (count($temp) > 0) {
            foreach ($temp as $temp_0) {
                $hasil[$temp_0->transaksi_id][] = $temp_0;
            }
        }
        return $hasil;
    }

    public function lookupSubDetailTransaksiMultiIDs($transaksi_id)
    {
        if (is_array($transaksi_id)) {
            $this->addFilter("transaksi_id in ('" . implode("','", $transaksi_id) . "')");
        }
        else {
            $this->addFilter("transaksi_id='$transaksi_id'");
        }
        $this->addFilter($this->tableNames['sub_detail'] . ".trash='0'");
        $this->addFilter($this->tableNames['sub_detail'] . ".qty_kredit >'0'");
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $temp = $this->db->get($this->tableNames['sub_detail'])->result();
        $hasil = array();
        if (count($temp) > 0) {
            foreach ($temp as $temp_0) {
                $hasil[$temp_0->transaksi_id][] = $temp_0;
            }
        }
        return $hasil;
    }

    public function lookupDetailTransaksiNoJenis($transaksi_id)
    {
        $criteria = array(
            "transaksi_id" => $transaksi_id,
            //            "produk_jenis" => "produk",
//            "trash" => "0",
        );

        return $this->db->get_where($this->tableNames['detail'], $criteria);


        die();
    }

    public function lookupJoinedByID($id)
    {
        $this->db->select("*,pembelian_transaksi.id as tid");

        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        //        $criteria2 ="transaksi_data.trash='0'";
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id and " . $this->tableNames['main'] . ".id='$id'");
        return $this->db->get($this->tableNames['main']);
    }

    public function lookupJoinedByID__($id)
    {
        $this->db->select("*,pembelian_transaksi.id as tid");
        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id and " . $this->tableNames['main'] . ".id='$id'");
        return $this->db->get($this->tableNames['main']);
    }

    public function lookupJoinedInspectionByMasterID($id)
    {
        $this->db->select("*,pembelian_transaksi.oleh_id as oleh_id,pembelian_transaksi.oleh_nama as oleh_nama,pembelian_transaksi.cabang_id as cabang_id");
        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id and " . $this->tableNames['main'] . ".id_master='$id'");
        return $this->db->get($this->tableNames['main']);
    }

    public function lookupJoinedByReceiptNO($id)
    {
        $this->db->select("*,pembelian_transaksi.oleh_id as oleh_id,pembelian_transaksi.oleh_nama as oleh_nama,pembelian_transaksi.cabang_id as cabang_id");
        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id and nomer='$id'");
        //        $this->db->join($this->tableNames['mainValues'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id and nomer='$id'");
        //        $this->db->join($this->tableNames['detailValues'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id and nomer='$id'");
        $result = $this->db->get($this->tableNames['main']);
        //        cekMerah($this->db->last_query());
        return $result;
    }

    public function lookupJoined_OLD()
    {
        $this->db->select("*,pembelian_transaksi.oleh_id as oleh_id,pembelian_transaksi.oleh_nama as oleh_nama,pembelian_transaksi.seller_id as seller_id,pembelian_transaksi.seller_nama as seller_nama,pembelian_transaksi.cabang_id as cabang_id,pembelian_transaksi.dtime as dtime");
        $this->filters[99] = $this->tableNames['detail'] . ".trash='0'";

        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        //                $criteria2 ="transaksi_data.trash='0'";
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id");
        return $this->db->get($this->tableNames['main']);
    }

    public function lookupJoined_meode_array()
    {
        // $this->load->model("Mdls/MdlCountry");
        // $mc = new MdlCountry();
        // arrPrint($mc->getStaticData());
        // mati_disini();
        // $this->db->select("*,pembelian_transaksi.oleh_id as oleh_id,pembelian_transaksi.oleh_nama as oleh_nama");
        $starttime = microtime(true);
        $tmpExpl = implode(",", $this->getFields()["main"]);
        $this->db->select($tmpExpl);
        // arrPrint($tmpExpl);
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        // $this->db->limit(1000);
        $main = $this->db->get($this->tableNames['main'])->result();
        // arrPrint($main);
        //        cekBiru($this->db->last_query());
        //        arrPrintPink($this->getFields()["main"]);
        //         arrprint($main);
        //        cekHitam(sizeof($main));
        $retunTrans = array();
        if (sizeof($main) > 0) {
            $selectedAlias = $this->getAliasName()["detail"];
            $allDetailFields = $this->getFields()["detail"];
            $tmpAlias = array();
            $aliasFields = array();
            foreach ($allDetailFields as $fileds) {
                if (isset($selectedAlias[$fileds])) {
                    $alias_fields = $selectedAlias[$fileds];
                    $fileds = "$fileds as '" . $selectedAlias[$fileds] . "'";

                }
                else {
                    $alias_fields = $fileds;
                }
                $tmpAlias[] = $fileds;
                $aliasFields[] = $alias_fields;

            }

            $dataTranss = array();
            $fieldsSelected = array_merge($this->getFields()["main"], $aliasFields);

            //            arrPrint($main);

            foreach ($main as $i => $mainData_0) {
//                $idexingDetail = blobDecode($mainData_0->indexing_details);
                $this->db->select($tmpAlias);
                $criteria = array();
                $criteria2 = "";

                if (sizeof($this->joinedFilter) > 0) {
                    $this->fetchCriteriaJoined();
                    $criteria = $this->getCriteria();
                }

                if (sizeof($criteria) > 0) {
                    $this->db->where($criteria);
                }

                //                arrPrint($idexingDetail);
                //                $this->db->where("trash='0'");

                if (!empty($idexingDetail)) {
                    $this->db->where_in("id", $idexingDetail);
                }

                $itemTmp = $this->db->get($this->tableNames['detail'])->result();

                //cekMerah($this->db->last_query());

                foreach ($itemTmp as $detailItemsTmp) {
                    $dataTranss[] = (array)$mainData_0 + (array)$detailItemsTmp;
                }


            }
            //arrPrintWebs($itemTmp);
            //            matiHere("mati dulu");

            $dataFinal = array();
            if (sizeof($dataTranss) > 0) {
                foreach ($dataTranss as $ix => $dataTranss) {
                    $dataFinal[$ix] = (object)$dataTranss;
                }
            }

            return $dataFinal;

        }

    }

    /*
     * pakai metode join
     */
    public function lookupJoined()
    {

        $starttime = microtime(true);
        $this->db->select("*," . $this->getTableName() . ".id as main_tr_id, " . $this->getTableName() . ".jenis as jenis, " . $this->getTableNames()["detail"] . ".id as id_detail," . $this->getTableName() . ".status as status," . $this->getTableName() . ".trash as trash, " . $this->getTableName() . ".dtime as dtime, " . $this->getTableName() . ".fulldate as fulldate");
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $this->db->join($this->getTableNames()["detail"], $this->getTableNames()["detail"] . ".transaksi_id=" . $this->getTableName() . ".id and " . $this->tableNames['detail'] . ".trash = 0");
//        $this->db->join($this->getTableNames()["detail"],"pembelian_transaksi_data.transaksi_id=pembelian_transaksi.id");
        // $this->db->limit(1000);
        $main = $this->db->get($this->tableNames['main'])->result();
//        arrPrint($main);
//        matiHEre($this->db->last_query());
        return $main;
//        cekLime($this->db->last_query());
//        arrPrint($main);
//        cekmerah(count($main));
//        matiHere(__LINE__);
//        $retunTrans = array();
//        if (sizeof($main) > 0) {
//            $selectedAlias = $this->getAliasName()["detail"];
//            $allDetailFields = $this->getFields()["detail"];
//            $tmpAlias = array();
//            $aliasFields = array();
//            foreach ($allDetailFields as $fileds) {
//                if (isset($selectedAlias[$fileds])) {
//                    $alias_fields = $selectedAlias[$fileds];
//                    $fileds = "$fileds as '" . $selectedAlias[$fileds] . "'";
//
//                }
//                else {
//                    $alias_fields = $fileds;
//                }
//                $tmpAlias[] = $fileds;
//                $aliasFields[] = $alias_fields;
//
//            }
//
//            $dataTranss = array();
//            $fieldsSelected = array_merge($this->getFields()["main"], $aliasFields);
//
//            //            arrPrint($main);
//
//            foreach ($main as $i => $mainData_0) {
////                $idexingDetail = blobDecode($mainData_0->indexing_details);
//                $this->db->select($tmpAlias);
//                $criteria = array();
//                $criteria2 = "";
//
//                if (sizeof($this->joinedFilter) > 0) {
//                    $this->fetchCriteriaJoined();
//                    $criteria = $this->getCriteria();
//                }
//
//                if (sizeof($criteria) > 0) {
//                    $this->db->where($criteria);
//                }
//
//                //                arrPrint($idexingDetail);
//                //                $this->db->where("trash='0'");
//
//                if (!empty($idexingDetail)) {
//                    $this->db->where_in("id", $idexingDetail);
//                }
//
//                $itemTmp = $this->db->get($this->tableNames['detail'])->result();
//
//                //cekMerah($this->db->last_query());
//
//                foreach ($itemTmp as $detailItemsTmp) {
//                    $dataTranss[] = (array)$mainData_0 + (array)$detailItemsTmp;
//                }
//
//
//            }
//            //arrPrintWebs($itemTmp);
//            //            matiHere("mati dulu");
//
//            $dataFinal = array();
//            if (sizeof($dataTranss) > 0) {
//                foreach ($dataTranss as $ix => $dataTranss) {
//                    $dataFinal[$ix] = (object)$dataTranss;
//                }
//            }
//
//            return $dataFinal;
//
//        }

    }

    public function lookupJoinedRekTransaksiData_OLD()
    {
        // $this->load->model("Mdls/MdlCountry");
        // $mc = new MdlCountry();
        // arrPrint($mc->getStaticData());
        // mati_disini();
        // $this->db->select("*,pembelian_transaksi.oleh_id as oleh_id,pembelian_transaksi.oleh_nama as oleh_nama");
        $starttime = microtime(true);
        $tmpExpl = implode(",", $this->getFields()["main"]);
        $this->db->select($tmpExpl);
        // arrPrint($tmpExpl);
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        // $this->db->limit(1000);
        $main = $this->db->get($this->tableNames['main'])->result();

        $retunTrans = array();
        if (sizeof($main) > 0) {
            $selectedAlias = $this->getAliasName()["rekening"];
            $allDetailFields = $this->getFields()["rekening"];
            $tmpAlias = array();
            $aliasFields = array();
            foreach ($allDetailFields as $fileds) {
                if (isset($selectedAlias[$fileds])) {
                    $alias_fields = $selectedAlias[$fileds];
                    $fileds = "$fileds as '" . $selectedAlias[$fileds] . "'";

                }
                else {
                    $alias_fields = $fileds;
                }
                $tmpAlias[] = $fileds;
                $aliasFields[] = $alias_fields;

            }

            $dataTranss = array();
            $fieldsSelected = array_merge($this->getFields()["main"], $aliasFields);

//                        arrPrint($main);
//matiHere(__LINE__);
            foreach ($main as $i => $mainData_0) {
//                arrprint($mainData_0);
                $idexingDetail = blobDecode($mainData_0->indexing_details);

                $this->db->select($tmpAlias);
                $criteria = array();
                $criteria2 = "";

                if (sizeof($this->joinedFilter) > 0) {
                    $this->fetchCriteriaJoined();
                    $criteria = $this->getCriteria();
                }

                if (sizeof($criteria) > 0) {
                    $this->db->where($criteria);
                }

                //                arrPrint($idexingDetail);
//                                $this->db->where("trash='0'");

//                if (!empty($idexingDetail)) {
//                    matiHEre(__LINE__." ada indexingbnya");
//                    $this->db->where_in("id", $idexingDetail);
//                }

                $this->setFilters(array());
                $this->addFilter("transaksi_id='" . $mainData_0->id . "'");
                $localFilters = array();
                if (sizeof($this->filters) > 0) {
                    foreach ($this->filters as $f) {
                        $tmpArr = explode("=", $f);
                        //                    $localFilters[$tmpArr[0]]=$tmpArr[1];
                        $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");
                    }
                }
                $this->db->where($localFilters);

                $itemTmp = $this->db->get($this->tableNames['rekening'])->result();
//                matiHEre(__LINE__);

//                cekMerah($this->db->last_query());

                foreach ($itemTmp as $detailItemsTmp) {
                    $dataTranss[] = (array)$mainData_0 + (array)$detailItemsTmp;
                }


            }
            //arrPrintWebs($itemTmp);
            //            matiHere("mati dulu");

            $dataFinal = array();
            if (sizeof($dataTranss) > 0) {
                foreach ($dataTranss as $ix => $dataTranss) {
                    $dataFinal[$ix] = (object)$dataTranss;
                }
            }

            return $dataFinal;

        }

    }

    public function lookupJoinedRekTransaksiData()
    {
        // $this->load->model("Mdls/MdlCountry");
        // $mc = new MdlCountry();
        // arrPrint($mc->getStaticData());
        // mati_disini();
        // $this->db->select("*,pembelian_transaksi.oleh_id as oleh_id,pembelian_transaksi.oleh_nama as oleh_nama");
        $starttime = microtime(true);
//        $tmpExpl = implode(",", $this->getFields()["main"]);
//        $this->db->select($tmpExpl);
        $this->db->select("*, pembelian_transaksi.id as id");
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        // $this->db->limit(1000);


//        $this->db->join($this->tableNames['rekening'], $this->tableNames['rekening'].".transaksi_id=".$this->tableNames['main'].".id");
//        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'].".transaksi_id=".$this->tableNames['main'].".id AND " . $this->tableNames['detail'].".produk_id=".$this->tableNames['rekening'].".extern_id", "right");
        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id=" . $this->tableNames['main'] . ".id");
        $main = $this->db->get($this->tableNames['main'])->result();

        return $main;


//        arrPrintPink($main);
//        $retunTrans = array();
//        if (sizeof($main) > 0) {
//            $selectedAlias = $this->getAliasName()["rekening"];
//            $allDetailFields = $this->getFields()["rekening"];
//            $tmpAlias = array();
//            $aliasFields = array();
//            foreach ($allDetailFields as $fileds) {
//                if (isset($selectedAlias[$fileds])) {
//                    $alias_fields = $selectedAlias[$fileds];
//                    $fileds = "$fileds as '" . $selectedAlias[$fileds] . "'";
//
//                }
//                else {
//                    $alias_fields = $fileds;
//                }
//                $tmpAlias[] = $fileds;
//                $aliasFields[] = $alias_fields;
//
//            }
//
//            $dataTranss = array();
//            $fieldsSelected = array_merge($this->getFields()["main"], $aliasFields);
//
////                        arrPrint($main);
////matiHere(__LINE__);
//            foreach ($main as $i => $mainData_0) {
////                arrprint($mainData_0);
//                $idexingDetail = blobDecode($mainData_0->indexing_details);
//
//                $this->db->select($tmpAlias);
//                $criteria = array();
//                $criteria2 = "";
//
//                if (sizeof($this->joinedFilter) > 0) {
//                    $this->fetchCriteriaJoined();
//                    $criteria = $this->getCriteria();
//                }
//
//                if (sizeof($criteria) > 0) {
//                    $this->db->where($criteria);
//                }
//
//                //                arrPrint($idexingDetail);
////                                $this->db->where("trash='0'");
//
////                if (!empty($idexingDetail)) {
////                    matiHEre(__LINE__." ada indexingbnya");
////                    $this->db->where_in("id", $idexingDetail);
////                }
//
//                $this->setFilters(array());
//                $this->addFilter("transaksi_id='" . $mainData_0->id . "'");
//                $localFilters = array();
//                if (sizeof($this->filters) > 0) {
//                    foreach ($this->filters as $f) {
//                        $tmpArr = explode("=", $f);
//                        //                    $localFilters[$tmpArr[0]]=$tmpArr[1];
//                        $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");
//                    }
//                }
//                $this->db->where($localFilters);
//
//                $itemTmp = $this->db->get($this->tableNames['rekening'])->result();
////                matiHEre(__LINE__);
//
////                cekMerah($this->db->last_query());
//
//                foreach ($itemTmp as $detailItemsTmp) {
//                    $dataTranss[] = (array)$mainData_0 + (array)$detailItemsTmp;
//                }
//
//
//            }
//            //arrPrintWebs($itemTmp);
//            //            matiHere("mati dulu");
//
//            $dataFinal = array();
//            if (sizeof($dataTranss) > 0) {
//                foreach ($dataTranss as $ix => $dataTranss) {
//                    $dataFinal[$ix] = (object)$dataTranss;
//                }
//            }
//
//            return $dataFinal;
//
//        }

    }


    public function lookupJoinedSubItems()
    {

        $starttime = microtime(true);
        $tmpExpl = implode(",", $this->getFields()["main"]);
        $this->db->select($tmpExpl);
        // arrPrint($tmpExpl);
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        $main = $this->db->get($this->tableNames['main'])->result();
        //cekLime($this->db->last_query());
        $retunTrans = array();
        if (sizeof($main) > 0) {
            $selectedAlias = $this->getAliasName()["sub_detail"];
            $allDetailFields = $this->getFields()["sub_detail"];
            $tmpAlias = array();
            $aliasFields = array();
            foreach ($allDetailFields as $fileds) {
                if (isset($selectedAlias[$fileds])) {
                    $alias_fields = $selectedAlias[$fileds];
                    $fileds = "$fileds as '" . $selectedAlias[$fileds] . "'";

                }
                else {
                    $alias_fields = $fileds;
                }
                $tmpAlias[] = $fileds;
                $aliasFields[] = $alias_fields;
            }

            // arrPrint(blobDecode($main[0]->indexing_sub_details));
            // matiHEre();
            $dataTranss = array();
            $fieldsSelected = array_merge($this->getFields()["main"], $aliasFields);
            foreach ($main as $i => $mainData_0) {
                $idexingDetail = blobDecode($mainData_0->indexing_sub_details);
                arrPrint($idexingDetail);
                // matiHEre(sizeof($idexingDetail));
//                if (sizeof($idexingDetail) == 0) {
//                    matiHere("indexing sub detil tidak terbaca! " . __LINE__);
//                }
                $this->db->select($tmpAlias);

                $criteria = array();
                $criteria2 = "";
                if (sizeof($this->joinedFilter) > 0) {
                    $this->fetchCriteriaJoined();
                    $criteria = $this->getCriteria();

                }
                if (sizeof($criteria) > 0) {
                    $this->db->where($criteria);
                }
                // matiHEre();

                //                $this->db->where("trash='0'");
                $this->db->where_in("id", $idexingDetail);
                $itemTmp = $this->db->get($this->tableNames['sub_detail'])->result();
                cekKuning($this->db->last_query());
                //                arrPrintPink($itemTmp);
                foreach ($itemTmp as $detailItemsTmp) {
                    $dataTranss[] = (array)$mainData_0 + (array)$detailItemsTmp;

                }
            }
            $dataFinal = array();
            if (sizeof($dataTranss) > 0) {
                foreach ($dataTranss as $ix => $dataTranss) {
                    $dataFinal[$ix] = (object)$dataTranss;
                }
            }
            // arrPrint($dataFinal);
            // matiHEre();
            return $dataFinal;
            //
            // $sql ="";
            // $iCtr=0;
            // if(sizeof($dataTranss)>0){
            //     cekMerah(sizeof($dataTranss));
            //     // cekBiru($fieldsSelected);
            //     // cekHitam(sizeof($fieldsSelected));
            //     foreach($dataTranss as $iSpec){
            //         $iCtr++;
            //         $subSql = 'SELECT ';
            //         $fCtr = 0;
            //         $inclCtr = 0;
            //         foreach($fieldsSelected as $keyID){
            //             $fCtr++;
            //             $subSql .= "'" . $iSpec[$keyID] . "' as $keyID";
            //             if ($fCtr < sizeof($fieldsSelected)) {
            //                 $subSql .= ",";
            //             }
            //         }
            //         $subSql .= " union ";
            //         $sql .= $subSql;
            //     }
            //
            //     // cekBiru($sql);
            //     $sql = rtrim($sql, " union ");
            //     return $this->db->query($sql);
            // }

        }
//        else{
//            cekHitam(":: KOSONG ::");
//        }

    }

    public function lookupJoinedSubItems2()
    {

        $starttime = microtime(true);
        $tmpExpl = implode(",", $this->getFields()["main"]);
        $this->db->select($tmpExpl);
        // arrPrint($tmpExpl);
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        $main = $this->db->get($this->tableNames['main'])->result();
        //cekLime($this->db->last_query());
        $retunTrans = array();
        if (sizeof($main) > 0) {
            $selectedAlias = $this->getAliasName()["items3_sum"];
            $allDetailFields = $this->getFields()["items3_sum"];
            $tmpAlias = array();
            $aliasFields = array();
            foreach ($allDetailFields as $fileds) {
                if (isset($selectedAlias[$fileds])) {
                    $alias_fields = $selectedAlias[$fileds];
                    $fileds = "$fileds as '" . $selectedAlias[$fileds] . "'";

                }
                else {
                    $alias_fields = $fileds;
                }
                $tmpAlias[] = $fileds;
                $aliasFields[] = $alias_fields;
            }

            // arrPrint(blobDecode($main[0]->indexing_sub_details));
            // matiHEre();
            $dataTranss = array();
            $fieldsSelected = array_merge($this->getFields()["main"], $aliasFields);
            foreach ($main as $i => $mainData_0) {
                $idexingDetail = blobDecode($mainData_0->indexing_items3_sum);
                // arrPrint($idexingDetail);
                // matiHEre(sizeof($idexingDetail));
                if (sizeof($idexingDetail) == 0) {
                    matiHere("indexing sub detil tidak terbaca! " . __LINE__);
                }
                $this->db->select($tmpAlias);

                $criteria = array();
                $criteria2 = "";
                if (sizeof($this->joinedFilter) > 0) {
                    $this->fetchCriteriaJoined();
                    $criteria = $this->getCriteria();

                }
                if (sizeof($criteria) > 0) {
                    $this->db->where($criteria);
                }
                // matiHEre();

                //                $this->db->where("trash='0'");
                $this->db->where_in("id", $idexingDetail);
                $itemTmp = $this->db->get($this->tableNames['items3_sum'])->result();
                cekKuning($this->db->last_query());
                //                arrPrintPink($itemTmp);
                foreach ($itemTmp as $detailItemsTmp) {
                    $dataTranss[] = (array)$mainData_0 + (array)$detailItemsTmp;

                }
            }
            $dataFinal = array();
            if (sizeof($dataTranss) > 0) {
                foreach ($dataTranss as $ix => $dataTranss) {
                    $dataFinal[$ix] = (object)$dataTranss;
                }
            }
            // arrPrint($dataFinal);
            // matiHEre();
            return $dataFinal;
            //
            // $sql ="";
            // $iCtr=0;
            // if(sizeof($dataTranss)>0){
            //     cekMerah(sizeof($dataTranss));
            //     // cekBiru($fieldsSelected);
            //     // cekHitam(sizeof($fieldsSelected));
            //     foreach($dataTranss as $iSpec){
            //         $iCtr++;
            //         $subSql = 'SELECT ';
            //         $fCtr = 0;
            //         $inclCtr = 0;
            //         foreach($fieldsSelected as $keyID){
            //             $fCtr++;
            //             $subSql .= "'" . $iSpec[$keyID] . "' as $keyID";
            //             if ($fCtr < sizeof($fieldsSelected)) {
            //                 $subSql .= ",";
            //             }
            //         }
            //         $subSql .= " union ";
            //         $sql .= $subSql;
            //     }
            //
            //     // cekBiru($sql);
            //     $sql = rtrim($sql, " union ");
            //     return $this->db->query($sql);
            // }

        }


    }

    public function lookupMainValuesByTransID($id)
    {
        $this->db->select("*");
        $result = $this->db->get_where($this->tableNames['mainValues'], array("transaksi_id" => $id));
        //        cekMerah($this->db->last_query());
        return $result;
    }

    public function lookupMainValues()
    {
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $this->db->select("*");
        $result = $this->db->get($this->tableNames['mainValues']);
        //        cekMerah($this->db->last_query());
        return $result;
    }

    public function lookupDetailValuesByTransID($id)
    {
        $this->db->select("*");
        $result = $this->db->get_where($this->tableNames['detailValues'], array("transaksi_id" => $id));
        //        cekMerah($this->db->last_query());
        return $result;
    }

    public function lookupDates()
    {
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        // $jmlData=$this->lookupHistoryCount();


        $this->db->select("fulldate");
        $this->db->group_by("fulldate");
        $this->db->order_by("fulldate");
        // $this->db->from($this->tableNames['main']);

        $tmp = $this->db->get($this->tableNames['main'])->result();
        $results = array(
            "start" => "0000-00-00",
            "end" => "0000-00-00",
            "entries" => array(),
        );
        if (sizeof($tmp) > 0) {
            $cnt = 0;
            foreach ($tmp as $row) {
                $cnt++;
                if ($cnt == 1) {
                    $results['start'] = $row->fulldate;
                }
                $results['entries'][$row->fulldate] = $row->fulldate;
                $results['end'] = $row->fulldate;
            }
        }
        return $results;
    }


    public function lookupEntryPoints_joined($id)
    {


        $this->removeFilter("pembelian_transaksi.link_id='0'");
        $this->addFilter("pembelian_transaksi.link_id>'0'");
        //        $this->addFilter("pembelian_transaksi.link_id='$id'");
        $this->addFilter("pembelian_transaksi.id_master='$id'");
        $criteria = array();
        $criteria2 = "";
        $this->filters[99] = $this->tableNames['detail'] . ".trash='0'";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        //        $numPages = ceil($jmlData / $limit);
        //        $offset = ($page - 1) * $limit;

        $this->db->select("*,pembelian_transaksi.oleh_id as oleh_id,pembelian_transaksi.oleh_nama as oleh_nama,pembelian_transaksi.cabang_id as cabang_id,pembelian_transaksi.dtime as dtime");

//        $this->db->group_start();
//        $this->db->where(array("pembelian_transaksi.cabang_id" => $this->session->login['cabang_id']));
//        $this->db->or_where(array("pembelian_transaksi.cabang2_id" => $this->session->login['cabang_id']));
//        $this->db->group_end();
//
//        $this->db->group_start();
//        $this->db->where(array("gudang_id" => $this->session->login['gudang_id']));
//        $this->db->or_where(array("gudang2_id" => $this->session->login['gudang_id']));
//        $this->db->group_end();


        //        $this->db->limit($limit, $offset);
        $this->db->order_by("pembelian_transaksi.id", "asc");
        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id ");
        $result = $this->db->get($this->tableNames['main']);


        //        arrPrint($result->result());
        return $result;
    }

    public function lookupEntryPoints($id)
    {


        $this->removeFilter("pembelian_transaksi.link_id='0'");
        $this->addFilter("pembelian_transaksi.link_id>'0'");
        //        $this->addFilter("pembelian_transaksi.link_id='$id'");
        $this->addFilter("pembelian_transaksi.id_master='$id'");
        $criteria = array();
        $criteria2 = "";
        //        $this->filters[99] = $this->tableNames['detail'] . ".trash='0'";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        //        $numPages = ceil($jmlData / $limit);
        //        $offset = ($page - 1) * $limit;

        $this->db->select("*,pembelian_transaksi.oleh_id as oleh_id,pembelian_transaksi.oleh_nama as oleh_nama,pembelian_transaksi.cabang_id as cabang_id");
        //        $this->db->limit($limit, $offset);
        $this->db->order_by("pembelian_transaksi.id", "asc");
        //        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id ");
        $result = $this->db->get($this->tableNames['main']);


        //        arrPrint($result->result());
        return $result;
    }

    //  write temporary entries
    public function writeTmpEntries($params)
    {
        if (is_array($params)) {
            if (sizeof($params) > 0) {

                //  region transaksi main
                $data = array();
                foreach ($params as $fName => $fValue) {
                    if (in_array($fName, $this->fields['tmp'])) {
                        $data[$fName] = $fValue;
                    }
                }
                //                foreach ($this->fields['main'] as $kolom) {
                //                    $isi = isset($params[$kolom]) ? $params[$kolom] : "";
                //
                //                    $data[$kolom] = $isi;
                //                }
                $this->db->insert($this->tableNames['tmp'], $data);

                //cekKuning($this->db->last_query());
                //  endregion transaksi main

                return $this->db->insert_id();
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    //  insert transaksi main
    public function writeMainEntries($params)
    {
        if (is_array($params)) {
            if (sizeof($params) > 0) {

                $data = array();
                foreach ($params as $fName => $fValue) {
                    if (in_array($fName, $this->fields['main'])) {
                        $data[$fName] = $fValue;
                    }
                }

                $this->db->insert($this->tableNames['main'], $data);
                $insertID = $this->db->insert_id();
                return $insertID;
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    //insert transaksi_data main setara dengan mainrgistry
    public function writeDetailMainEntries($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {

                $data = array();
                foreach ($this->fields['main_entries'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames['main_entries'], $data);
                $insertID = $this->db->insert_id();

                //                if( $detailParams['link_id']<1){
                //                    $epData=$detailParams;
                //                    $replacers=array(
                //                        "transaksi_id"=>$detailParams['link_id'],
                //
                //                    );
                //                    foreach($replacers as $key=>$newVal){
                //                        $epData[$key]=$newVal;
                //                    }
                //                    $this->writeDetailEntries($transaksi_id,$epData);
                //                }

                return $insertID;
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function writeMainEntries_entryPoint($insertID, $masterID, $params)
    {
        //pembelian_transaksi.link_id='0'
        if (is_array($params)) {
            if (sizeof($params) > 0) {

                //  region transaksi main
                $data = array();
                foreach ($params as $fName => $fValue) {
                    if (in_array($fName, $this->fields['main'])) {
                        $data[$fName] = $fValue;
                    }
                }

                //                $this->db->insert($this->tableNames['main'], $data);
                //                $insertID=$this->db->insert_id();

                //===nulis entry-point
                if (strpos($params['jenis'], '_') == false) {
                    $epData = $data;
                    $replacers = array(
                        "id_top" => 0,
                        "id_master" => $masterID,
                        "link_id" => $insertID,
                        "jenis" => $data['jenis'] . "_" . $data['step_number'],
                        "nomer" => $data['nomer'] . "_" . $data['step_number'] . "_" . date("YmdHis"),
                    );
                    foreach ($replacers as $key => $newVal) {
                        $epData[$key] = $newVal;
                    }
                    //                    $this->writeMainEntries($epData);
                    //                    $insertID2=$this->db->insert_id();
                    //remove filter
                    $this->db->insert($this->tableNames['main'], $epData);
                    $insertID2 = $this->db->insert_id();
                }
                else {
                    $insertID2 = 999;
                }

                return $insertID2;
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    //  insert transaksi childs
    public function writeDetailEntries($transaksi_id, $detailParams)
    {
        arrprintWebs($this->fields['detail']);
        arrprintWebs($detailParams);
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {

                $data = array();
                foreach ($this->fields['detail'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames['detail'], $data);

                ////cekLime($this->db->last_query());

                //                return $this->db->insert_id();
                $insertID = $this->db->insert_id();

                //                if( $detailParams['link_id']<1){
                //                    $epData=$detailParams;
                //                    $replacers=array(
                //                        "transaksi_id"=>$detailParams['link_id'],
                //
                //                    );
                //                    foreach($replacers as $key=>$newVal){
                //                        $epData[$key]=$newVal;
                //                    }
                //                    $this->writeDetailEntries($transaksi_id,$epData);
                //                }

                return $insertID;
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    // region insert sub transaksi data untuk items2 yang ditabelkan contoh item produk project (INSERT)
    public function writeDetailItemsEntries($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {

                $data = array();
                foreach ($this->fields['items'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames['items'], $data);
                $insertID = $this->db->insert_id();
//                showLast_query("biru");

                //                if( $detailParams['link_id']<1){
                //                    $epData=$detailParams;
                //                    $replacers=array(
                //                        "transaksi_id"=>$detailParams['link_id'],
                //
                //                    );
                //                    foreach($replacers as $key=>$newVal){
                //                        $epData[$key]=$newVal;
                //                    }
                //                    $this->writeDetailEntries($transaksi_id,$epData);
                //                }

                return $insertID;
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function writeDetailSubEntries_items($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {

                $data = array();
                foreach ($this->fields['items3_sum'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames['items3_sum'], $data);
                $insertID = $this->db->insert_id();

                //                if( $detailParams['link_id']<1){
                //                    $epData=$detailParams;
                //                    $replacers=array(
                //                        "transaksi_id"=>$detailParams['link_id'],
                //
                //                    );
                //                    foreach($replacers as $key=>$newVal){
                //                        $epData[$key]=$newVal;
                //                    }
                //                    $this->writeDetailEntries($transaksi_id,$epData);
                //                }

                return $insertID;
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function writeSubDetailEntries($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {

                $data = array();
                foreach ($this->fields['sub_detail'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames['sub_detail'], $data);
                $insertID = $this->db->insert_id();

                //                if( $detailParams['link_id']<1){
                //                    $epData=$detailParams;
                //                    $replacers=array(
                //                        "transaksi_id"=>$detailParams['link_id'],
                //
                //                    );
                //                    foreach($replacers as $key=>$newVal){
                //                        $epData[$key]=$newVal;
                //                    }
                //                    $this->writeDetailEntries($transaksi_id,$epData);
                //                }

                return $insertID;
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function writeEntriesDetailItems2($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {
                $data = array();
                if (isset($this->fields['items2'])) {

                    foreach ($this->fields['items2'] as $kolom) {
                        $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                        $data[$kolom] = $isi;
                    }
                    $data['transaksi_id'] = $transaksi_id;
                    if ($this->db->table_exists($this->tableNames['items2'])) {

                        $this->db->insert($this->tableNames['items2'], $data);
                        $insertID = $this->db->insert_id();
                        return $insertID;
                    }
                    else {
                        mati_disini("transaksi gagal disimpan, tabel " . $this->tableNames['items2'] . " tidak tersedia. silahkan hubungi admin.");
                        return null;
                    }
                }
                else {
                    return null;
                }
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function writeEntriesDetailItems2_sum($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {
                $data = array();
                if (isset($this->fields['items2_sum'])) {

                    foreach ($this->fields['items2_sum'] as $kolom) {
                        $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                        $data[$kolom] = $isi;
                    }
                    $data['transaksi_id'] = $transaksi_id;
                    if ($this->db->table_exists($this->tableNames['items2_sum'])) {

                        $this->db->insert($this->tableNames['items2_sum'], $data);
                        $insertID = $this->db->insert_id();
                        return $insertID;
                    }
                    else {
                        mati_disini("transaksi gagal disimpan, tabel " . $this->tableNames['items2_sum'] . " tidak tersedia. silahkan hubungi admin.");
                        return null;
                    }
                }
                else {
                    return null;
                }
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function writeEntriesDetailItems3($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {
                $data = array();
                if (isset($this->fields['items3'])) {

                    foreach ($this->fields['items3'] as $kolom) {
                        $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                        $data[$kolom] = $isi;
                    }
                    $data['transaksi_id'] = $transaksi_id;
                    if ($this->db->table_exists($this->tableNames['items3'])) {

                        $this->db->insert($this->tableNames['items3'], $data);
                        $insertID = $this->db->insert_id();
                        return $insertID;
                    }
                    else {
                        mati_disini("transaksi gagal disimpan, tabel " . $this->tableNames['items3'] . " tidak tersedia. silahkan hubungi admin.");
                        return null;
                    }
                }
                else {
                    return null;
                }
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function writeEntriesDetailItems3_sum($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {
                $data = array();
                if (isset($this->fields['items3_sum'])) {

                    foreach ($this->fields['items3_sum'] as $kolom) {
                        $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                        $data[$kolom] = $isi;
                    }
                    $data['transaksi_id'] = $transaksi_id;
                    if ($this->db->table_exists($this->tableNames['items3_sum'])) {

                        $this->db->insert($this->tableNames['items3_sum'], $data);
                        $insertID = $this->db->insert_id();
                        return $insertID;
                    }
                    else {
                        mati_disini("transaksi gagal disimpan, tabel " . $this->tableNames['detail3_sum'] . " tidak tersedia. silahkan hubungi admin.");
                        return null;
                    }
                }
                else {
                    return null;
                }
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function writeEntriesDetailItems4($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {
                $data = array();
                if (isset($this->fields['items4'])) {

                    foreach ($this->fields['items4'] as $kolom) {
                        $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                        $data[$kolom] = $isi;
                    }
                    $data['transaksi_id'] = $transaksi_id;
                    if ($this->db->table_exists($this->tableNames['items4'])) {

                        $this->db->insert($this->tableNames['items4'], $data);
                        $insertID = $this->db->insert_id();
                        return $insertID;
                    }
                    else {
                        mati_disini("transaksi gagal disimpan, tabel " . $this->tableNames['detail4'] . " tidak tersedia. silahkan hubungi admin.");
                        return null;
                    }
                }
                else {
                    return null;
                }
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function writeEntriesDetailItems4_sum($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {
                $data = array();
                if (isset($this->fields['items4_sum'])) {

                    foreach ($this->fields['items4_sum'] as $kolom) {
                        $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                        $data[$kolom] = $isi;
                    }
                    $data['transaksi_id'] = $transaksi_id;
                    if ($this->db->table_exists($this->tableNames['items4_sum'])) {

                        $this->db->insert($this->tableNames['detail4_sum'], $data);
                        $insertID = $this->db->insert_id();
                        return $insertID;
                    }
                    else {
                        mati_disini("transaksi gagal disimpan, tabel " . $this->tableNames['detail4_sum'] . " tidak tersedia. silahkan hubungi admin.");
                        return null;
                    }
                }
                else {
                    return null;
                }
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function writeEntriesDetailItems5($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {
                $data = array();
                if (isset($this->fields['items5'])) {

                    foreach ($this->fields['items5'] as $kolom) {
                        $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                        $data[$kolom] = $isi;
                    }
                    $data['transaksi_id'] = $transaksi_id;
                    if ($this->db->table_exists($this->tableNames['items5'])) {

                        $this->db->insert($this->tableNames['detail5'], $data);
                        $insertID = $this->db->insert_id();
                        return $insertID;
                    }
                    else {
                        mati_disini("transaksi gagal disimpan, tabel " . $this->tableNames['detail5'] . " tidak tersedia. silahkan hubungi admin.");
                        return null;
                    }
                }
                else {
                    return null;
                }
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function writeEntriesDetailItems5_sum($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {
                $data = array();
                if (isset($this->fields['items5_sum'])) {

                    foreach ($this->fields['items5_sum'] as $kolom) {
                        $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                        $data[$kolom] = $isi;
                    }
                    $data['transaksi_id'] = $transaksi_id;
                    if ($this->db->table_exists($this->tableNames['items5_sum'])) {

                        $this->db->insert($this->tableNames['items5_sum'], $data);
                        $insertID = $this->db->insert_id();
                        return $insertID;
                    }
                    else {
                        mati_disini("transaksi gagal disimpan, tabel " . $this->tableNames['detail5_sum'] . " tidak tersedia. silahkan hubungi admin.");
                        return null;
                    }
                }
                else {
                    return null;
                }
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function writeEntriesDetailItems6($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {
                $data = array();
                if (isset($this->fields['items6'])) {

                    foreach ($this->fields['items6'] as $kolom) {
                        $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                        $data[$kolom] = $isi;
                    }
                    $data['transaksi_id'] = $transaksi_id;
                    if ($this->db->table_exists($this->tableNames['items6'])) {

                        $this->db->insert($this->tableNames['items6'], $data);
                        $insertID = $this->db->insert_id();
                        return $insertID;
                    }
                    else {
                        mati_disini("transaksi gagal disimpan, tabel " . $this->tableNames['detail6'] . " tidak tersedia. silahkan hubungi admin.");
                        return null;
                    }
                }
                else {
                    return null;
                }
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function writeEntriesDetailItems6_sum($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {
                $data = array();
                if (isset($this->fields['items6_sum'])) {

                    foreach ($this->fields['items6_sum'] as $kolom) {
                        $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                        $data[$kolom] = $isi;
                    }
                    $data['transaksi_id'] = $transaksi_id;
                    if ($this->db->table_exists($this->tableNames['items6_sum'])) {

                        $this->db->insert($this->tableNames['items6_sum'], $data);
                        $insertID = $this->db->insert_id();
                        return $insertID;
                    }
                    else {
                        mati_disini("transaksi gagal disimpan, tabel " . $this->tableNames['items6_sum'] . " tidak tersedia. silahkan hubungi admin.");
                        return null;
                    }
                }
                else {
                    return null;
                }
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function writeEntriesDetailItems7($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {
                $data = array();
                if (isset($this->fields['items7'])) {

                    foreach ($this->fields['items7'] as $kolom) {
                        $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                        $data[$kolom] = $isi;
                    }
                    $data['transaksi_id'] = $transaksi_id;
                    if ($this->db->table_exists($this->tableNames['items7'])) {

                        $this->db->insert($this->tableNames['items7'], $data);
                        $insertID = $this->db->insert_id();
                        return $insertID;
                    }
                    else {
                        mati_disini("transaksi gagal disimpan, tabel " . $this->tableNames['detail6'] . " tidak tersedia. silahkan hubungi admin.");
                        return null;
                    }
                }
                else {
                    return null;
                }
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function writeEntriesDetailItems7_sum($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {
                $data = array();
                if (isset($this->fields['items7_sum'])) {

                    foreach ($this->fields['items7_sum'] as $kolom) {
                        $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                        $data[$kolom] = $isi;
                    }
                    $data['transaksi_id'] = $transaksi_id;
                    if ($this->db->table_exists($this->tableNames['items7_sum'])) {

                        $this->db->insert($this->tableNames['items7_sum'], $data);
                        $insertID = $this->db->insert_id();
                        return $insertID;
                    }
                    else {
                        mati_disini("transaksi gagal disimpan, tabel " . $this->tableNames['items7_sum'] . " tidak tersedia. silahkan hubungi admin.");
                        return null;
                    }
                }
                else {
                    return null;
                }
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function writeEntriesDetailItems8($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {
                $data = array();
                if (isset($this->fields['items8'])) {

                    foreach ($this->fields['items8'] as $kolom) {
                        $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                        $data[$kolom] = $isi;
                    }
                    $data['transaksi_id'] = $transaksi_id;
                    if ($this->db->table_exists($this->tableNames['items8'])) {

                        $this->db->insert($this->tableNames['items8'], $data);
                        $insertID = $this->db->insert_id();
                        return $insertID;
                    }
                    else {
                        mati_disini("transaksi gagal disimpan, tabel " . $this->tableNames['detail8'] . " tidak tersedia. silahkan hubungi admin.");
                        return null;
                    }
                }
                else {
                    return null;
                }
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function writeEntriesDetailItems8_sum($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {
                $data = array();
                if (isset($this->fields['items6_sum'])) {

                    foreach ($this->fields['items6_sum'] as $kolom) {
                        $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                        $data[$kolom] = $isi;
                    }
                    $data['transaksi_id'] = $transaksi_id;
                    if ($this->db->table_exists($this->tableNames['items6_sum'])) {

                        $this->db->insert($this->tableNames['items6_sum'], $data);
                        $insertID = $this->db->insert_id();
                        return $insertID;
                    }
                    else {
                        mati_disini("transaksi gagal disimpan, tabel " . $this->tableNames['items6_sum'] . " tidak tersedia. silahkan hubungi admin.");
                        return null;
                    }
                }
                else {
                    return null;
                }
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function writeEntriesDetailItems9_sum($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {
                $data = array();
                if (isset($this->fields['items9_sum'])) {

                    foreach ($this->fields['items9_sum'] as $kolom) {
                        $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                        $data[$kolom] = $isi;
                    }
                    $data['transaksi_id'] = $transaksi_id;
                    if ($this->db->table_exists($this->tableNames['items9_sum'])) {

                        $this->db->insert($this->tableNames['items9_sum'], $data);
                        $insertID = $this->db->insert_id();
                        return $insertID;
                    }
                    else {
                        mati_disini("transaksi gagal disimpan, tabel " . $this->tableNames['items9_sum'] . " tidak tersedia. silahkan hubungi admin.");
                        return null;
                    }
                }
                else {
                    return null;
                }
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function writeEntriesDetailItems10_sum($transaksi_id, $itemsParams)
    {
        if (is_array($itemsParams)) {
            if (sizeof($itemsParams) > 0) {
                $data = array();
                if (isset($this->fields['items10_sum'])) {

                    foreach ($this->fields['items10_sum'] as $kolom) {
                        $isi = isset($itemsParams[$kolom]) ? $itemsParams[$kolom] : "";

                        $data[$kolom] = $isi;
                    }
                    $data['transaksi_id'] = $transaksi_id;
                    if ($this->db->table_exists($this->tableNames['items10_sum'])) {

                        $this->db->insert($this->tableNames['items10_sum'], $data);
                        $insertID = $this->db->insert_id();
                        return $insertID;
                    }
                    else {
                        mati_disini("transaksi gagal disimpan, tabel " . $this->tableNames['items10_sum'] . " tidak tersedia. silahkan hubungi admin.");
                        return null;
                    }
                }
                else {
                    return null;
                }
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }
    // endregion


    //---------------------------------------------------------------------------
    //--menulis nilai2 utama tapi dipisah tabel
    public function writeMainValues($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {
                $data = array();
                if (isset($this->fields['mainValues'])) {

                    foreach ($this->fields['mainValues'] as $kolom) {
                        $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";
                        $data[$kolom] = "$isi";

                    }
                    $data['transaksi_id'] = $transaksi_id;

                    $this->db->insert($this->tableNames['mainValues'], $data);

                    return $this->db->insert_id();
                }
                else {
                    return null;
                }
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    //--menulis nilai2 rincian tapi dipisah tabel
    public function writeDetailValues($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {

                $data = array();
                foreach ($this->fields['detailValues'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames['detailValues'], $data);

                ////cekLime($this->db->last_query());

                return $this->db->insert_id();
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    //--menulis kolom2 utama tapi dipisah tabel
    public function writeMainFields($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {
                $data = array();
                foreach ($this->fields['mainFields'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";
                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames['mainFields'], $data);

                ////cekLime($this->db->last_query());

                return $this->db->insert_id();
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    //--menulis kolom2 rincian tapi dipisah tabel
    public function writeDetailFields($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {

                $data = array();
                foreach ($this->fields['detailFields'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames['detailFields'], $data);

                ////cekLime($this->db->last_query());

                return $this->db->insert_id();
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    //--menulis kolom2 utama tapi dipisah tabel
    public function writeMainApplets($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {
                $data = array();
                foreach ($this->fields['applets'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";
                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames['applets'], $data);

                ////cekLime($this->db->last_query());

                return $this->db->insert_id();
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function writeMainElements($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {
                $data = array();
                foreach ($this->fields['elements'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";
                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames['elements'], $data);

                //cekKuning($this->db->last_query());

                return $this->db->insert_id();
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function lookupMainElementsByTransID($id)
    {
        $this->db->select("*");
        $result = $this->db->get_where($this->tableNames['elements'], array("transaksi_id" => $id));
//        $result=array();
//        foreach($resultTmp as $resultTmp_0){
//            $result[$resultTmp_0->element_name]=(array)$resultTmp_0;
//        }
//arrprint($result);
//        matiHEre(__LINE__);

        return $result;
    }

    //
    //region extended steps
    public function writeExtStep($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {
                $data = array();
                foreach ($this->fields['extras'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";
                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames['extras'], $data);

                //cekKuning($this->db->last_query());

                return $this->db->insert_id();
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function lookupExtSteps($masterID)
    {
        $this->db->select("*");

        $tmp = $this->db->get_where(
            $this->tableNames['extras'],
            array(
                "master_id" => $masterID,
                "state" => "0",
            )
        )->result();
        if (sizeof($tmp) > 0) {
            $results = array();
            foreach ($tmp as $row) {
                $results[] = array(
                    "id" => $row->id,
                    "key" => $row->_key,
                    "label" => $row->_label,
                    "value" => $row->_value,
                    "groupID" => $row->group_id,
                );
            }
            return $results;
        }
        else {
            return array();
        }

    }

    public function lookupExtStepByID($valID)
    {
        $this->db->select("*");

        $tmp = $this->db->get_where(
            $this->tableNames['extras'],
            array(
                "id" => $valID,
                "state" => "0",
            )
        )->result();
        if (sizeof($tmp) > 0) {
            $results = array();
            foreach ($tmp as $row) {
                $results[] = array(
                    "id" => $row->id,
                    "key" => $row->_key,
                    "label" => $row->_label,
                    "value" => $row->_value,
                    "groupID" => $row->group_id,
                    "proposed" => array(
                        "personID" => $row->proposed_by,
                        "time" => $row->proposed_dtime,
                    ),
                );
            }
            return $results;
        }
        else {
            return array();
        }

    }

    public function lookupExtStepByTrID($valID)
    {
//        $this->db->select("*");
//
//        $tmp = $this->db->get_where(
//            $this->tableNames['extras'],
//            array(
//                "transaksi_id" => $valID,
//                "state" => "0",
//            )
//        )->result();
//        if (sizeof($tmp) > 0) {
//            $results = array();
//            foreach ($tmp as $row) {
//                $results[] = array(
//                    "id" => $row->id,
//                    "key" => $row->_key,
//                    "label" => $row->_label,
//                    "value" => $row->_value,
//                    "groupID" => $row->group_id,
//                    "proposed" => array(
//                        "personID" => $row->proposed_by,
//                        "time" => $row->proposed_dtime,
//                    ),
//                );
//            }
//            return $results;
//        }
//        else {
            return array();
//        }

    }

    public function approveExtStepByID($valID)
    {
        $this->setTableName($this->getTableNames()['extras']);
        $this->setFilters(array());
        $this->updateData(
            array(
                "id" => $valID,
            ),
            array(
                "state" => "1",
                "done_by" => $this->session->login['id'],
                "done_dtime" => date("Y-m-d H:i:s"),
            )
        );
        return true;

    }

    public function rejectExtStepByID($valID)
    {
        $this->setTableName($this->getTableNames()['extras']);
        $this->setFilters(array());
        $this->updateData(
            array(
                "id" => $valID,
            ),
            array(
                "state" => "-1",
            )
        );
        return true;

    }

    public function resetExtStepByID($valID)
    {
        $this->setTableName($this->getTableNames()['extras']);
        $this->setFilters(array());
        $this->updateData(
            array(
                "id" => $valID,
            ),
            array(
                "state" => "0",
            )
        );
        return true;

    }

    public function extStepExistsInMaster($masterID, $iKey)
    {
        $this->db->select("*");

        $tmp = $this->db->get_where(
            $this->tableNames['extras'],
            array(
                "master_id" => $masterID,
                "_key" => $iKey,
                "state" => 0,
            )
        )->result();
        if (sizeof($tmp) > 0) {
            return true;
        }
        else {
            return false;
        }

    }
    //endregion

    //
    //region payment-source
    public function writePaymentSrc($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {

                $data = array();
                foreach ($this->fields['paymentSrc'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames['paymentSrc'], $data);

                ////cekLime($this->db->last_query());

                return $this->db->insert_id();
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function updatePaymentSrc($where, $data)
    {
        $this->db->where($where);
        $this->db->update($this->tableNames['paymentSrc'], $data);
        return true;

    }

    public function lookupPaymentSrcs($masterID, $jenis, $key = "")
    {
        $this->db->select("*");
        $where = array(
            "transaksi_id" => $masterID,
            "jenis" => $jenis,

        );
        if ($key != "") {
            $where["_key"] = $key;
        }
        $tmp = $this->db->get_where(
            $this->tableNames['paymentSrc'],
            $where
        )->result();
        if (sizeof($tmp) > 0) {
            $results = array();
            foreach ($tmp as $row) {
                $results[$row->target_jenis] = array(
                    "id" => $row->id,
                    "targetJenis" => $row->target_jenis,
                    "label" => $row->label,
                    "tagihan" => $row->tagihan,
                    "sisa" => $row->sisa,
                    "extID" => $row->extern_id,
                    "extName" => $row->extern_nama,
                );
            }
            return $results;

        }
        else {
            return array();
        }
    }

    public function paymentSrcExistsInMaster($masterID, $iCode, $iLabel)
    {
        $this->db->select("*");

        $tmp = $this->db->get_where($this->tableNames['paymentSrc'], array("transaksi_id" => $masterID, "target_jenis" => $iCode, "label" => $iLabel))->result();
        if (sizeof($tmp) > 0) {
            return true;
        }
        else {
            return false;
        }

    }

    public function lookupPaymentSrcByID($id)
    {
        $this->db->select("*");
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $result = $this->db->get_where($this->tableNames['paymentSrc'], array("id" => $id));
        //        cekMerah($this->db->last_query());
        return $result;
    }

    public function lookupPaymentSrcByTransID($id)
    {
        $this->db->select("*");
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $result = $this->db->get_where($this->tableNames['paymentSrc'], array("transaksi_id" => $id));
        //        cekMerah($this->db->last_query());
        return $result;
    }

    public function lookupPaymentSrcByJenis($jenis)
    {

        $this->db->select("*");
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $result = $this->db->get_where($this->tableNames['paymentSrc'], array("target_jenis" => $jenis));
        //        cekMerah($this->db->last_query());
        return $result;
    }


    public function lookupPaymentSrcByJenis_joined($jenis)
    {
        //        $this->db->select("*");
        $this->db->select("*,
        pembelian_transaksi_payment_source.valas_id as valas_id,
        pembelian_transaksi_payment_source.valas_nama as valas_nama,
        pembelian_transaksi_payment_source.extern_label2 as extern_label2,
        pembelian_transaksi_payment_source.pph_23 as pph_23,
        pembelian_transaksi_payment_source.terbayar_pph23 as terbayar_pph23,
        pembelian_transaksi_payment_source.valas_nilai as valas_nilai,
        pembelian_transaksi_payment_source.id as id,
        pembelian_transaksi_payment_source.id as tabel_id,
        pembelian_transaksi_payment_source.project_id as project_id,
        pembelian_transaksi_payment_source.ppn as ppn,
        pembelian_transaksi_payment_source.project_nama as project_nama");
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        $this->db->join($this->tableNames['main'], $this->tableNames['paymentSrc'] . ".transaksi_id = " . $this->tableNames['main'] . ".id ");
        $result = $this->db->get_where($this->tableNames['paymentSrc'], array("target_jenis" => $jenis));
        //        cekMerah($this->db->last_query());
        return $result;
    }

    public function lookUpAllPaymentSrc()
    {
        $this->db->select("*");
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $result = $this->db->get_where($this->tableNames['paymentSrc']);
        //        cekMerah($this->db->last_query());
        return $result;
    }

    public function lookUpPayment()
    {
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $res = $this->db->get($this->tableNames['paymentSrc']);
        return $res;
    }

    //endregion

    //region payment-antisource
    public function writePaymentAntiSrc($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {

                $data = array();
                foreach ($this->fields['paymentAntiSrc'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames['paymentAntiSrc'], $data);

                //                //cekLime($this->db->last_query());

                return $this->db->insert_id();
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function updatePaymentAntiSrc($where, $data)
    {
        $this->db->where($where);
        $this->db->update($this->tableNames['paymentAntiSrc'], $data);
        return true;

    }

    public function lookupPaymentAntiSrcs($masterID, $jenis, $key = "")
    {
        $this->db->select("*");
        $where = array(
            "transaksi_id" => $masterID,
            "jenis" => $jenis,

        );
        if ($key != "") {
            $where["_key"] = $key;
        }
        $tmp = $this->db->get_where(
            $this->tableNames['paymentAntiSrc'],
            $where
        )->result();
        if (sizeof($tmp) > 0) {
            $results = array();
            foreach ($tmp as $row) {
                $results[$row->target_jenis] = array(
                    "id" => $row->id,
                    "targetJenis" => $row->target_jenis,
                    "label" => $row->label,
                    "tagihan" => $row->tagihan,
                    "sisa" => $row->sisa,
                    "extID" => $row->extern_id,
                    "extName" => $row->extern_nama,
                );
            }
            return $results;

        }
        else {
            return array();
        }
    }

    public function paymentAntiSrcExistsInMaster($masterID, $iCode, $iLabel)
    {
        $this->db->select("*");

        $tmp = $this->db->get_where($this->tableNames['paymentAntiSrc'], array("transaksi_id" => $masterID, "target_jenis" => $iCode, "label" => $iLabel))->result();
        if (sizeof($tmp) > 0) {
            return true;
        }
        else {
            return false;
        }

    }

    public function lookupPaymentAntiSrcByTransID($id)
    {
        $this->db->select("*");
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $result = $this->db->get_where($this->tableNames['paymentAntiSrc'], array("transaksi_id" => $id));
        //        cekMerah($this->db->last_query());
        return $result;
    }

    public function lookupPaymentAntiSrcByJenis($jenis)
    {
        $this->db->select("*");
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $result = $this->db->get_where($this->tableNames['paymentAntiSrc'], array("target_jenis" => $jenis));
        //        cekMerah($this->db->last_query());
        return $result;
    }

    public function lookupPaymentAntiSrcByJenis_joined($jenis)
    {
        $this->db->select("*");
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $this->db->join($this->tableNames['main'], $this->tableNames['paymentAntiSrc'] . ".transaksi_id = " . $this->tableNames['main'] . ".id ");
        $result = $this->db->get_where($this->tableNames['paymentAntiSrc'], array("target_jenis" => $jenis));
        //        cekMerah($this->db->last_query());
        return $result;
    }

    public function lookupPaymentAntiSrcByLabel($jenis)
    {
        $this->db->select("*");
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $result = $this->db->get_where($this->tableNames['paymentAntiSrc'], array("label" => $jenis));
        //        cekMerah($this->db->last_query());
        return $result;
    }

    //endregion

    //region uang muka-source
    public function writeUangMukaSrc($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {

                $data = array();
                foreach ($this->fields['uangMuka'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames['uangMuka'], $data);

                ////cekLime($this->db->last_query());

                return $this->db->insert_id();
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function updateUangMukaSrc($where, $data)
    {
        $this->db->where($where);
        $this->db->update($this->tableNames['uangMuka'], $data);
        return true;

    }

    public function lookupUangMukaSrc($jenis)
    {// $jenis = customer/supplier/vendor
        $this->db->select("*");
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $result = $this->db->get_where($this->tableNames['uangMuka'], array("extern_label2" => $jenis));
        //        cekMerah($this->db->last_query());
        return $result;
    }
    //endregion

    //
    //region signatures
    public function writeSignature($transaksi_id, $params)
    {
        if (is_array($params)) {
            $data = array();
            foreach ($params as $key => $value) {
                $data[$key] = $value;
            }
            $data['transaksi_id'] = $transaksi_id;
            $this->db->insert($this->tableNames['sign'], $data);
            $insertID = $this->db->insert_id();
            //cekHijau($this->db->last_query());


            if ($insertID > 0) {
                return $insertID;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }//--nulis signature ERP setiap step

    public function lookupSignaturesByMasterID($id)
    {
        $this->db->select("*");
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $this->db->order_by("id", "asc");
        return $this->db->get_where($this->tableNames['sign'], array("transaksi_id" => $id));
    }//--baca signature berdasarkan ID master

    public function lookupSignatures($id_master)
    {
        // arrPrint($this->fields['main']);
        $this->db->select($this->fields['main']);
        $criteria = array(
            "link_id >" => 0,
            // "id_master" => $id_master
        );


        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        // $this->tableName$this->tableNames['main'];
        // $this->tableName('transaksi');
        // $this->setTableName('transakai');
        $this->db->order_by("id", "asc");
        $var = $this->db->get_where($this->tableNames['main'], array("id_master" => $id_master));

        return $var;
    }//--baca signature berdasarkan ID master
    //endregion

    //
    //region registries
    public function writeRegistries($insertID, $batchParams)
    {
        //    print_r($batchParams);die();
        if (is_array($batchParams)) {
            $data = array();
            $insertIDs = array();
            $indexing_registry = array();
            foreach ($batchParams as $param => $value) {
                //                echo "param: $param<br>";
                //                echo "value: $value<br>";
                $data['param'] = $param;
                $data['values'] = base64_encode(serialize($value));
                //                $data['values'] = $value;
                //                $data['values_intext'] = print_r($value, true);

                $data['transaksi_id'] = $insertID;
                $this->db->insert($this->tableNames['registry'], $data);
                $insertIDs[] = $this->db->insert_id();
                $indexing_registry[$param] = $this->db->insert_id();

                //                cekUngu($this->db->last_query());

            }
            if (sizeof($insertIDs) > 0) {
                $arrBlob = blobEncode($indexing_registry);
                $this->db->query("UPDATE transaksi SET indexing_registry = '$arrBlob' WHERE id=$insertID");
                return $indexing_registry;
                //                return implode(",", explode("-", $insertIDs));
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }//--nulis registri transaksi

    public function lookupBaseRegistries($ids = "")
    {
        if (is_array($ids)) {
            if (sizeof($ids) == 0) {
                matiHere("undefine iDs " . __LINE__);
            }
            $this->db->where_in("id", $ids);
        }
        elseif ($ids > 0) {
            $this->db->where("id", $ids);
        }

        // }
        return $this->db->get($this->tableNames['registry']);
    }//--baca registri berdasarkan ID registri

    public function lookupRegistries()
    {
        $this->filters[] = "trash=0";

        $criteria = array();
        $criteria2 = "";

        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $this->db->select("*");
        return $this->db->get($this->tableNames['registry']);
    }//--baca registri berdasarkan ID master

    public function lookupRegistries_joined()
    {
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $this->db->select("*");
        //        $this->db->join($this->tableNames['main'], $this->tableNames['registry'] . ".transaksi_id = " . $this->tableNames['main'] . ".id ");
        $this->db->join($this->tableNames['main'], $this->tableNames['registry'] . ".transaksi_id = " . $this->tableNames['main'] . ".id  and " . $this->tableNames['registry'] . ".trash = 0");
        return $this->db->get($this->tableNames['registry']);
    }//--baca registri berdasarkan ID master

    public function lookupRegistriesByMasterID($id)
    {
        $criteria2 = array("trash" => "0");
        $this->db->where($criteria2);

        return $this->db->get_where($this->tableNames['registry'], array("transaksi_id" => $id));
    }//--baca registri berdasarkan ID master

    public function lookupRegistriesByNumber($id)
    {
        //        $this->db->join($this->tableNames['main'], $this->tableNames['registry'] . ".transaksi_id = " . $this->tableNames['main'] . ".id and pembelian_transaksi.nomer='$id'");
        $this->db->join($this->tableNames['main'], $this->tableNames['registry'] . ".transaksi_id = " . $this->tableNames['main'] . ".id and pembelian_transaksi.nomer='$id' and " . $this->tableNames['registry'] . ".trash = 0");
        $res = $this->db->get($this->tableNames['registry']);
        return $res;
    }//--baca registri berdasarkan ID master

    public function updateRegistry($where, $data)
    {
        $this->db->where($where);
        $this->db->update($this->tableNames['registry'], $data);
        return true;
    }
    //endregion

    //region registry metode baru untuk transaksi berjalan
    public function writeDataRegistries($insertID, $batchParams)
    {

        if (is_array($batchParams)) {
            foreach ($batchParams as $kolom => $values) {
                $batchParamsEncode[$kolom] = blobEncode($values);

            }
            $batchParamsEncode['transaksi_id'] = $insertID;
            $this->db->insert($this->tableNames['dataRegistry'], $batchParamsEncode);

            return $insertID;
        }
        else {
            return false;
        }

    }//--nulis registri transaksi

    public function writeDataRegistriesHistory($batchParams)
    {

        if (is_array($batchParams)) {
            //            foreach ($batchParams as $kolom => $values) {
            //                $batchParamsEncode[$kolom] = blobEncode($values);
            //            }
            //            $batchParamsEncode['transaksi_id'] = $insertID;
            $insertID = $this->db->insert($this->tableNames['registry'], $batchParams);

            return $insertID;
        }
        else {
            return false;
        }
    }//--nulis registri transaksi

    public function updateDataRegistry($where, $data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                $dataEncode[$key] = blobEncode($val);
            }

            $this->db->where($where);
            $this->db->update($this->tableNames['dataRegistry'], $dataEncode);

            return true;
        }
        else {
            return false;
        }
    }

    public function lookupBaseDataRegistries($ids = "")
    {
        if (isset($this->jointSelectFields)) {
            $this->db->select($this->jointSelectFields);
        }
        else {
            $tmpExpl = implode(",", $this->getFields()["dataRegistry"]);
            $this->db->select($tmpExpl);
        }

        if (is_array($ids)) {
            $this->db->where_in("transaksi_id", $ids);
        }
        elseif ($ids > 0) {
            $this->db->where("transaksi_id", $ids);
        }


        return $this->db->get($this->tableNames['dataRegistry']);
    }//--baca registri berdasarkan ID registri versi lama


    public function lookupDataRegistriesPrev()
    {
        $criteria = array();
        $criteria2 = "";

        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        if (isset($this->jointSelectFields)) {

            $this->db->select($this->jointSelectFields);
        }
        else {
            $tmpExpl = implode(",", $this->getFields()["dataRegistry"]);
            $this->db->select($tmpExpl);
        }
        // $this->db->select("*");

        return $this->db->get($this->tableNames['dataRegistry']);
    }//--baca registri berdasarkan ID master

    public function lookUpDataRegistries()
    {
//untuk ngasih index per transaksi_id
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }


        $result0 = array();
        foreach ($this->getChildData() as $table_index) {
            $tbl = $this->getTableNames()[$table_index];

            if (sizeof($criteria) > 0) {
                $this->db->where($criteria);
            }
            if ($criteria2 != "") {
                $this->db->where($criteria2);
            }
            $this->db->where("$tbl.trash=0");

            $tmp = array();
            $tmp = $this->db->get($tbl)->result();
//            cekHitam($this->db->last_query());
//            arrPrint($tmp);
            if (count($tmp) > 0) {
                foreach ($tmp as $tmp_0) {
                    unset($tmp_0->id);
                    if (isset($tmp_0->produk_id)) {
                        $result0[$tmp_0->transaksi_id][$table_index][$tmp_0->produk_id] = array("id" => $tmp_0->produk_id) + (array)$tmp_0;
                    }
                    else {
                        $result0[$tmp_0->transaksi_id][$table_index] = (array)$tmp_0;
                    }
//                    $result0[$tmp_0->transaksi_id][$table_index][$tmp_0->produk_id] = array("id"=>$tmp_0->produk_id)+(array)$tmp_0;

                }
            }
        }
//        arrprint($result0);
//        arrPrint($this->getTableNames());
//        matiHEre(__LINE__);
        return $result0;
    }

    public function lookupDataRegistriesByMasterID($id)
    {

        $where = "transaksi_id='$id'";

        $result0 = array();
        foreach ($this->getChildData() as $table_index) {
//            cekHitam($table_index);
            $tbl = $this->getTableNames()[$table_index];
            $this->db->where($where);
            $this->db->where("$tbl.trash=0");
            $tmp = $this->db->get($tbl)->result();

//            cekMerah(count($tmp));
//            cekKuning($this->db->last_query());

            if (count($tmp) > 0) {
//                arrPrint($tmp);
//                cekMerah($table_index);
                foreach ($tmp as $tmp_0) {
                    unset($tmp_0->id);//reset tableindex
                    if (isset($tmp_0->produk_id)) {
                        $result0[$table_index][$tmp_0->produk_id] = array("id" => $tmp_0->produk_id) + (array)$tmp_0;
                    }
                    else {
                        $result0[$table_index] = (array)$tmp_0;
                    }

                }
            }
        }
        return $result0;
//        return $this->db->get_where($this->tableNames['dataRegistry'], array("transaksi_id" => $id));
    }//--baca registri berdasarkan ID master

    public function lookupDataRegistries_joined()
    {
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $tmpExpl = implode(",", $this->getFields()["main"]);
        $this->db->select($tmpExpl);
        $main = $this->db->get($this->tableNames['main'])->result();
        // cekHitam($this->db->last_query());
        // arrPrint($main);
        // matiHEre();
        $dataTrans = array();
        $dataFinal = array();
        if (sizeof($main) > 0) {
            foreach ($main as $i => $mainData_0) {
                if (isset($this->jointSelectFields)) {
                    $this->db->select($this->jointSelectFields);
                }
                else {
                    $tmpExpl = implode(",", $this->getFields()["dataRegistry"]);
                    $this->db->select($tmpExpl);
                }

                $criteria = array();
                $criteria2 = "";
                if (sizeof($this->joinedFilter) > 0) {
                    $this->fetchCriteriaJoined();
                    $criteria = $this->getCriteria();

                }
                //                arrPrintPink($criteria);
                if (sizeof($criteria) > 0) {
                    $this->db->where($criteria);
                }
                // matiHEre();

                //                $this->db->where("trash='0'");
                $this->db->where("transaksi_id", $mainData_0->id);
                $itemTmp = $this->db->get($this->tableNames['dataRegistry'])->result();
                // arrPrintPink($itemTmp);
                // //cekLime($this->db->last_query());
                // matiHEre();
                foreach ($itemTmp as $detailItemsTmp) {
                    $dataTrans[] = (array)$mainData_0 + (array)$detailItemsTmp;
                }
            }
            // arrPrint($dataTrans);
            $dataFinal = array();
            if (sizeof($dataTrans) > 0) {
                foreach ($dataTrans as $ix => $dataTrans) {
                    $dataFinal[$ix] = (object)$dataTrans;
                }
            }
        }

        return $dataFinal;
        // cekHitam($this->db->last_query());
        // arrPrint($main);
        // matiHEre();
        // $this->db->join($this->tableNames['main'], $this->tableNames['registry'] . ".transaksi_id = " . $this->tableNames['main'] . ".id ");
        // return $this->db->get($this->tableNames['registry']);
    }//--baca registri berdasarkan ID master

    public function lookupTransaksiDataRegistriesOLD($transaksi_id = "")
    {
        $field_main = $this->fields['main'];
        $field_slave = $this->fields['dataRegistry'];

        $selectedFields = $allFields = array_merge($field_main, $field_slave);

        if (isset($this->blockFields)) {
            // cekBiru($this->blockFields);
            $selectedFields = array_diff($allFields, $this->blockFields);
        }
        // cekHijau(sizeof($selectedFields));
        $this->db->select($selectedFields);

        $this->filters[99] = $this->tableNames['detail'] . ".trash='0'";
        $tbl_main = $this->tableNames['main'];
        $tbl_slave = $this->tableNames['dataRegistry'];

        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            // $this->db->where($criteria);
        }
        //                $criteria2 ="transaksi_data.trash='0'";
        if ($criteria2 != "") {
            // $this->db->where($criteria2);
        }

        if ($transaksi_id != "") {
            if (is_array($transaksi_id)) {
                $this->db->where_in("$tbl_main.id", $transaksi_id);
            }
            else {
                $this->db->where("$tbl_main.id", $transaksi_id);
            }
        }

        $this->db->join($tbl_slave, $tbl_slave . ".transaksi_id = " . $tbl_main . ".id");
        return $this->db->get($tbl_main);
    }//versi registry

    public function lookupTransaksiDataRegistries($transaksi_id = "")
    {

    }//versi registry

    public function updateDataRegistriesByMasterID($id, $updateData)
    {

        $where = "transaksi_id='$id'";

        $result0 = array();
        foreach ($this->getChildData() as $table_index) {
            $tbl = $this->getTableNames()[$table_index];
            $this->db->where($where);
            $this->db->where("$tbl.trash=0");
//            $tmp = $this->db->get($tbl)->result();
            $tmp = $this->db->update($tbl, $updateData);
            cekKuning($this->db->last_query());


        }

//        return $this->db->get_where($this->tableNames['dataRegistry'], array("transaksi_id" => $id));
    }//--baca registri berdasarkan ID master

    //endregion

    //region duedate
    public function writeDueDate($insertID, $params)
    {
        if (is_array($params)) {
            $data = array();
            foreach ($params as $key => $value) {
                $data[$key] = $value;
            }
            $data['transaksi_id'] = $insertID;
            $this->db->insert($this->tableNames['dueDate'], $data);
            $insertID = $this->db->insert_id();
            //cekHijau($this->db->last_query());
            if ($insertID > 0) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    public function updateDueDate($where, $data)
    {
        $this->db->where($where);
        $this->db->update($this->tableNames['dueDate'], $data);
        return true;
    }

    public function lookupDueDate($id)
    {
        $this->db->select("due_date,transaksi_nilai,nomer,transaksi_id");
        $result = $this->db->get_where($this->tableNames['dueDate'], array("customers_id" => $id, "status" => "1", "trash" => "0"))->result();
        $temp = array();
        $tempDate = array();
        $total = 0;
        foreach ($result as $result_0) {
            //            arrPrint($result_0);
            $date = $result_0->due_date;
            $nilai = $result_0->transaksi_nilai;
            $nomer = $result_0->nomer;
            $total += $nilai;
            $dateSecond = strtotime($date);
            $tempDate[] = $dateSecond;
            $temp[$dateSecond] = array(
                "date" => $date,
                "nomer" => $nomer,
                "transaksi_id" => $result_0->transaksi_id,
            );
            //           cekHere("$date || $nilai||$dateSecond");
        }

        if (sizeof($tempDate) > 0) {
            sort($tempDate);
        }
        //arrPrint($tempDate);
        $due = isset($tempDate['0']) ? $tempDate['0'] : "0";
        $data['due_date'] = isset($temp[$due]['date']) ? $temp[$due]['date'] : "0";
        $data['nomer'] = isset($temp[$due]['nomer']) ? $temp[$due]['nomer'] : "0";
        $data['transaksi_id'] = isset($temp[$due]['transaksi_id']) ? $temp[$due]['transaksi_id'] : "0";
        $data['total'] = $total;

        return $data;
    }

    public function lookupAllDueDate()
    {
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $this->db->select("*");
        return $this->db->get($this->tableNames['dueDate']);
    }

    //endregion

    //region ABR-like recap
    public function fetchIdentifiers()
    {
        $resultFields = array();
        $mainFields = $this->db->list_fields($this->tableNames['main']);

        if (sizeof($mainFields) > 0) {
            foreach ($mainFields as $f) {
                $lastChar = substr($f, -3);
                if ($lastChar == "_id") {
                    $tmpKey = $f;
                    $tmpPairName = str_replace("_id", "_nama", $f);
                    if (in_array($tmpPairName, $mainFields)) {
                        $resultFields[$tmpKey] = $tmpPairName;
                    }
                    else {
                        //                        $resultFields[$tmpKey] = "unknown";
                    }
                }

            }
        }
        $childFields = $this->db->list_fields($this->tableNames['detail']);
        if (sizeof($childFields) > 0) {
            foreach ($childFields as $f) {
                $lastChar = substr($f, -3);
                if ($lastChar == "_id") {
                    $tmpKey = $f;
                    $tmpPairName = str_replace("_id", "_nama", $f);
                    if (in_array($tmpPairName, $childFields)) {
                        $resultFields[$tmpKey] = $tmpPairName;
                    }
                    else {
                        //                        $resultFields[$tmpKey] = "unknown";
                    }
                }

            }
        }
        return $resultFields;

    }

    public function fetchMasterIdentifiers()
    {
        $resultFields = array();
        $childFields = $this->db->list_fields($this->tableNames['main']);
        if (sizeof($childFields) > 0) {
            foreach ($childFields as $f) {
                $lastChar = substr($f, -3);
                if ($lastChar == "_id") {
                    $tmpKey = $f;
                    $tmpPairName = str_replace("_id", "_nama", $f);
                    if (in_array($tmpPairName, $childFields)) {
                        $resultFields[$tmpKey] = $tmpPairName;
                    }
                    else {
                        //                        $resultFields[$tmpKey] = "unknown";
                    }
                }

            }
        }
        return $resultFields;

    }

    public function fetchChildIdentifiers()
    {
        $resultFields = array();
        $childFields = $this->db->list_fields($this->tableNames['detail']);
        if (sizeof($childFields) > 0) {
            foreach ($childFields as $f) {
                $lastChar = substr($f, -3);
                if ($lastChar == "_id") {
                    $tmpKey = $f;
                    $tmpPairName = str_replace("_id", "_nama", $f);
                    if (in_array($tmpPairName, $childFields)) {
                        $resultFields[$tmpKey] = $tmpPairName;
                    }
                    else {
                        //                        $resultFields[$tmpKey] = "unknown";
                    }
                }

            }
        }
        return $resultFields;

    }

    //endregion

    public function _resetorReport($jenis_array)
    {

        if (is_array($jenis_array)) {
            $jenis = implode("','", $jenis_array);
        }
        else {
            $jenis = $jenis_array;
            strlen($jenis_array) > 0 ? $jenis_array : matiHere(__METHOD__ . " isikan <b>jenis</b> dalam format array lebih dulu");
        }


        $condite = array(
            "r_jenis" => 1,
        );
        $this->db->where("jenis in('" . $jenis . "')");
        $datas = array(
            "r_jenis" => 0,
        );
        $this->updateData($condite, $datas);
    }

    public function lookupOutstandingStocks()
    {
        $fields = array(
            "valid_qty",
            "pembelian_transaksi.cabang_id as place_id",
            "produk_id",
            "produk_nama",
            "produk_kode",
            "customers_id",
            "customers_nama",
            "pembelian_transaksi.oleh_id as seller_id",
        );
        $wheres = array(
            "jenis" => "582so",
            "valid_qty >" => "0",
            "sub_step_number >" => "0",
        );
        $where_spos = array(
            "jenis" => "582spo",
            "valid_qty >" => "0",
            "sub_step_number" => "0",
        );

        $this->db->select($fields);
        $this->db->where($wheres);
        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id");
        $tmpso = $this->db->get($this->tableNames['main'])->result();
        // showLast_query("lime");

        $this->db->select($fields);
        $this->db->where($where_spos);
        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id");
        $tmpspo = $this->db->get($this->tableNames['main'])->result();
        // showLast_query("kuning");
        $tmpspo = array();
        // arrPrint($tmpso);
        // arrPrintWebs($tmpspo);
        $tmp = array_merge($tmpso, $tmpspo);

        // arrPrint($tmp);
        foreach ($tmp as $item) {

            if (!isset($byCabangId[$item->place_id][$item->produk_id]["valid_qty"])) {
                $byCabangId[$item->place_id][$item->produk_id]["valid_qty"] = 0;
            }
            $byCabangId[$item->place_id][$item->produk_id]["valid_qty"] += $item->valid_qty;
            $byCabangId[$item->place_id][$item->produk_id]["produk_nama"] = $item->produk_nama;
            $byCabangId[$item->place_id][$item->produk_id]["produk_kode"] = $item->produk_kode;
        }
        // arrPrint($byCabangId);
        $vars['row'] = $tmp;
        $vars['byCabang'] = $byCabangId;

        return $vars;
    }


    /* =====================================================================================
     * transaksi sign
     * =====================================================================================*/
    public function lookupCanceledSo()
    {
        $wheres = array(
            // "step_code" => "582spo",
            "step_number" => "-1",
        );
        // $this->db->table_exists("transaksi_sign");
        // $vars = parent::lookupAll(); // TODO: Change the autogenerated stub
        // $this->db->select($fields);
        $this->db->where($wheres);
        // $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id");
        $vars = $this->db->get($this->tableNames['sign']);

        return $vars;
    }

    public function markingCanceledSo($id, $datas)
    {

        $this->db->where("id = '$id'");
        $this->db->update($this->tableNames['sign'], $datas);
    }

    public function _resetorReportSign($jenis_array)
    {

        if (is_array($jenis_array)) {
            $jenis = implode("','", $jenis_array);
        }
        else {
            $jenis = $jenis_array;
            strlen($jenis_array) > 0 ? $jenis_array : matiHere(__METHOD__ . " isikan <b>jenis</b> dalam format array lebih dulu");
        }


        $condites = array(
            "r_jenis" => 1,
        );
        $this->db->where($condites);
        $this->db->where("step_code in('" . $jenis . "')");
        $datas = array(
            "r_jenis" => 0,
        );

        $this->db->update($this->tableNames['sign'], $datas);
    }

    //---------------------------
    public function lookupHistoryEfakturByMasterID($id)
    {
        $this->db->select("*");
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $result = $this->db->get_where($this->tableNames['efaktur'], array("id_master" => $id));

        return $result;
    }

    public function callSpecs($transaksiIds = "", $anu = "")
    {
        $selecteds = array(
            "id",
            "oleh_nama",
            "jenis_label",
            "customers_nama",
            "cabang_nama",
            "suppliers_id",
            "suppliers_nama",
            "nomer",
            "nomer_top",
            "seller_nama",
            "oleh_nama",
            // "ids_his",
        );
        $this->db->select($selecteds);

        // if (isset($transaksiIds)) {
        if (is_array($transaksiIds)) {
            $this->db->where_in("id", $transaksiIds);
        }
        else {
            if ($transaksiIds > 0) {
                $this->db->where("id", $transaksiIds);
            }
        }

        $vars_0 = $this->lookupAll()->result();
        // showLast_query("orange");
        $vars = array();
        foreach ($vars_0 as $item) {
            $vars[$item->id] = $item;
        }


        return $vars;
    }

    /* ---------------------------------------
* untuk setelment
* ---------------------------------------*/
    public function callMyTransaksi($myId, $cabangId)
    {
        $selecteds = array(
            "id",
            "oleh_nama",
            "jenis_label",
            "customers_nama",
            "jenis",
            "jenis_master",
            "indexing_registry",
            // "merek_nama",
            // "model_nama",
            // "type_nama",
            // "tahun",
            // "lokasi_nama",
            // "satuan",
        );
        $this->db->select($selecteds);
        $condites = array(
            "oleh_id" => $myId,
            "cabang_id" => $cabangId,
            //            "settlement_id " => "0",
        );
        $this->db->where($condites);

        $vars_0 = $this->lookupAll()->result();
        // showLast_query("orange");
        $vars = array();
        foreach ($vars_0 as $item) {
            $vars[$item->id] = $item;
        }


        return $vars;
    }

    /*---design for opname--*/
    public function callGantunganTransaksi($blacklist_jenis = false)
    {

        $tb_transaksi = "transaksi";
        $tb_data = "transaksi_data";
        /* ------------------------------------------------------------------------
         * $blacklist_jenis ::
         * untuk memblacklist berdasar kolom jenis, sehingga tidak akan masuk query
         * true :: untuk mengunakan default blaklist untuk opname
         * false :: seluruh jenis transaksi dipanggil
         * ------------------------------------------------------------------------*/
        if ($blacklist_jenis == true) {
            /*-----untuk bloking transaksi saat stok opname----*/
            $unjenis = array(
                // "461",
                "110e",
                "110r",
                "1337r",
                "1463",
                "1463o",
                "1463r",
                "1674r",
                "1675r",
                "1677r",
                "1757r",
                "1763r",
                "1960",
                "2675r",
                "2676r",
                "2677r",
                "3461r",
                "3463",
                "3463o",
                "3463ro",
                "3465r",
                "4449r",
                "4464r",
                "446r",
                "460a",
                "460r",
                "461",
                "461r",
                "461ro",
                "463o",
                "463ro",
                "464r",
                "466",
                "466r",
                "467",
                "582so",
                "582spo",
                "588so",
                "588spd",
                "671r",
                "672r",
                "675r",
                "676r",
                "677r",
                "681r",
                "758r",
                "763r",
                "7758r",
                "8786r",
                "8787r",
                "8788r",
                //--
                "1119r",
                "2229r",
                "1118r",
                "2228r",
                "2227r",
                "3339r",
                "5559r",
                "1119ro",
                "2229ro",
                "1118ro",
                "2228ro",
                "2227ro",
                "3339ro",
                "5559ro",
                "117r",
                "4466r",
                "5681r",
                // "960r", // return import
            );
            $this->db->where_not_in("jenis", $unjenis);
        }
        elseif (is_array($blacklist_jenis)) {
            $this->db->where_not_in("jenis", $blacklist_jenis);
        }

        $koloms = array(
            "$tb_pembelian_transaksi.id",
            "$tb_pembelian_transaksi.jenis_master",
            "$tb_pembelian_transaksi.jenis",
            "$tb_pembelian_transaksi.jenis_label",
            "$tb_pembelian_transaksi.nomer",
            "$tb_pembelian_transaksi.dtime",
            "$tb_pembelian_transaksi.oleh_nama",
            "$tb_pembelian_transaksi.cabang_nama",
            "$tb_pembelian_transaksi.next_step_num",
            "$tb_pembelian_transaksi.next_step_code",
            // "$tb_pembelian_transaksi.trash2",
            // "$tb_pembelian_transaksi.trash_4",
            // "$tb_data.valid_qty",
        );

        $this->db->select($koloms);
        // $this->db->limit(5);
        $join_condites = array(
            // $tb_data.".transaksi_id" => $tb_pembelian_transaksi.".id",
            "$tb_pembelian_transaksi.link_id" => 0,
            "$tb_pembelian_transaksi.status" => 1,
            "$tb_pembelian_transaksi.trash" => 0,
            "$tb_pembelian_transaksi.trash2" => 0,
            "$tb_pembelian_transaksi.trash_4" => 0,
            "$tb_pembelian_transaksi.div_id" => 18,
            "$tb_data.valid_qty >" => 0,
            "$tb_data.next_substep_code !=" => "",
            "$tb_data.sub_step_number >" => "0",
        );
        $this->db->where($join_condites);
        // $this->db->group_by("$tb_pembelian_transaksi.id");
        $this->db->group_by("$tb_pembelian_transaksi.id,$tb_data.next_substep_code");
        $this->db->join($tb_data, $tb_data . ".transaksi_id = " . $tb_transaksi . ".id");
        // $this->db->join($tb_data, $join_condites);
        $src_tr_gantung = $this->db->get($tb_transaksi)->result();
        // showLast_query("hijau");
        // //cekLime(sizeof($src_tr_gantung));
        // arrPrintHijau($src_tr_gantung);

        $jenis_gantung = array();
        foreach ($src_tr_gantung as $src_item) {
            $jenis = $src_item->jenis;
            $tr_id = $src_item->id;
            // $jenis_gantung[$jenis][] = $tr_id;
            $jenis_gantung[$jenis][] = $src_item;
        }

        return $jenis_gantung;
    }

    public function lookupTransaksiData()
    {
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }

        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        return $this->db->get($this->tableNames['detail']);

    }

    public function callTransaksiCounterJenis($jenis)
    {

        $tbl_1 = "transaksi";
        $coloms = array(
            "id",
            "_company_stepCode",
            "_company_jenisTr",
        );
        $this->db->select($coloms);
        $wheres = array(
            // "jenis" => "4822",
            "jenis" => $jenis,
        );
        $this->db->where($wheres);
        $this->db->order_by("dtime", "asc");
        $srcs = $this->db->get($tbl_1)->result_array();

        foreach ($srcs as $src) {
            $tr_id = $src['id'];
            $sisa = $src['sisa'];

            $src_datas[$tr_id] = $src;
        }

        return $src_datas;
    }

    //---------------------------
    public function lookupTransaksionalModulesByID($transaksi_id)
    {
        $hasil = array();
        if (sizeof($this->tableNames) > 0) {
            foreach ($this->tableNames as $key => $tabel) {
//                cekHere("$key");
                switch ($key) {
                    case "main":
                        $this->db->reset_query();

                        $this->setFilters(array());
                        $this->addFilter("id='$transaksi_id'");
                        $criteria = array();
                        $criteria2 = "";
                        if (sizeof($this->filters) > 0) {
                            $this->fetchCriteria();
                            $criteria = $this->getCriteria();
                            $criteria2 = $this->getCriteria2();
                        }
                        if (sizeof($criteria) > 0) {
                            $this->db->where($criteria);
                        }
                        if ($criteria2 != "") {
                            $this->db->where($criteria2);
                        }
                        $data = $this->db->get($this->tableNames[$key])->result();
                        if (sizeof($data) > 0) {
                            $hasil[$key] = (array)$data[0];
                        }
                        else {
                            $hasil[$key] = array();
                        }

                        break;
                    case "sign":
                    case "dataRegistry":
                        $this->db->reset_query();
                        $this->setFilters(array());
                        $this->addFilter("transaksi_id='$transaksi_id'");
                        $criteria = array();
                        $criteria2 = "";
                        if (sizeof($this->filters) > 0) {
                            $this->fetchCriteria();
                            $criteria = $this->getCriteria();
                            $criteria2 = $this->getCriteria2();
                        }
                        if (sizeof($criteria) > 0) {
                            $this->db->where($criteria);
                        }
                        if ($criteria2 != "") {
                            $this->db->where($criteria2);
                        }
                        $data = $this->db->get($this->tableNames[$key])->result();
                        if (sizeof($data) > 0) {
                            $hasil[$key] = (array)$data[0];
                        }
                        else {
                            $hasil[$key] = array();
                        }
                        break;
                    case "elements":
                        $this->db->reset_query();
                        $this->setFilters(array());
                        $this->addFilter("transaksi_id='$transaksi_id'");
                        $criteria = array();
                        $criteria2 = "";
                        if (sizeof($this->filters) > 0) {
                            $this->fetchCriteria();
                            $criteria = $this->getCriteria();
                            $criteria2 = $this->getCriteria2();
                        }
                        if (sizeof($criteria) > 0) {
                            $this->db->where($criteria);
                        }
                        if ($criteria2 != "") {
                            $this->db->where($criteria2);
                        }
                        $data = $this->db->get($this->tableNames[$key])->result();
                        if (sizeof($data) > 0) {
                            foreach ($data as $kk => $kkSpec) {
                                $hasil[$key][$kkSpec->element_name] = (array)$kkSpec;
                            }
                        }
                        else {
                            $hasil[$key] = array();
                        }
                        break;
                    case "rekening":
                        $this->db->reset_query();
                        $this->setFilters(array());
                        $this->addFilter("transaksi_id='$transaksi_id'");
                        $criteria = array();
                        $criteria2 = "";
                        if (sizeof($this->filters) > 0) {
                            $this->fetchCriteria();
                            $criteria = $this->getCriteria();
                            $criteria2 = $this->getCriteria2();
                        }
                        if (sizeof($criteria) > 0) {
                            $this->db->where($criteria);
                        }
                        if ($criteria2 != "") {
                            $this->db->where($criteria2);
                        }
                        if ($this->db->table_exists($this->tableNames[$key])) {
                            $data = $this->db->get($this->tableNames[$key])->result();
                            if (sizeof($data) > 0) {
                                foreach ($data as $kk => $kkSpec) {
                                    $hasil[$key][$kkSpec->produk_id] = (array)$kkSpec;
                                }
                            }
                        }
                        else {
                            $hasil[$key] = array();
                        }
                        break;
                    default:
                        $this->db->reset_query();
                        $this->setFilters(array());
                        $this->addFilter("transaksi_id='$transaksi_id'");
                        $criteria = array();
                        $criteria2 = "";
                        if (sizeof($this->filters) > 0) {
                            $this->fetchCriteria();
                            $criteria = $this->getCriteria();
                            $criteria2 = $this->getCriteria2();
                        }
                        if (sizeof($criteria) > 0) {
                            $this->db->where($criteria);
                        }
                        if ($criteria2 != "") {
                            $this->db->where($criteria2);
                        }
                        if ($this->db->table_exists($this->tableNames[$key])) {
                            $data = $this->db->get($this->tableNames[$key])->result();
                            if (sizeof($data) > 0) {
                                foreach ($data as $kk => $kkSpec) {
                                    $hasil[$key][$kkSpec->td_produk_id] = (array)$kkSpec;
                                }
                            }
                        }
                        else {
                            $hasil[$key] = array();
                        }
                        break;
                }
            }
        }

//        arrPrintWebs($hasil);
//        mati_disini(__LINE__);

    }

    public function lookUpAllChild($transaksi_id)
    {

//        arrprint($this->getChildData());
        if (is_array($transaksi_id)) {
            $where = array(
                "transaksi_id in(" . implode(',', $transaksi_id) . ") "
            );
        }
        else {
            $where = "transaksi_id='$transaksi_id'";
        }
//        arrPrint($this->getTableNames());
//        matiHEre(__LINE__);
        $result0 = array();
        foreach ($this->getChildData() as $table_index) {
            $this->db->where($where);
            $tmp = $this->db->get($this->getTableNames()[$table_index])->result();
//            cekhitam($this->db->last_query());

            if (count($tmp) > 0) {
//                arrPrint($tmp);
//                cekMerah($table_index);
                foreach ($tmp as $tmp_0) {

                    $result0[$table_index][$tmp_0->produk_id] = (array)$tmp_0;
                }
            }
        }

        return $result0;
//        arrprint($result0);
//        matiHEre();

//        $coloumb = "select ";

    }


}

