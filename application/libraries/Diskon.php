<?php

/**
 * Created by JetBrains PhpStorm.
 * User: azes
 * Date: 5/9/12
 * Time: 11:56 AM
 * To change this template use File | Settings | File Templates.
 */


class Diskon
{
    protected $harga;
    protected $event_dis;
    protected $toko_id;
    protected $customer_level_condite;
    protected $produk_id;

    public function getProdukId()
    {
        return $this->produk_id;
    }

    public function setProdukId($produk_id)
    {
        $this->produk_id = $produk_id;
    }

    // protected $produk_id;

    public function getCustomerLevelCondite()
    {
        return $this->customer_level_condite;
    }

    public function setCustomerLevelCondite($customer_level_condite)
    {
        $this->customer_level_condite = $customer_level_condite;
    }

    public function getTokoId()
    {
        return $this->toko_id;
    }

    public function setTokoId($toko_id)
    {
        $this->toko_id = $toko_id;
    }

    public function getEventDis()
    {
        return $this->event_dis;
    }

    public function setEventDis($event_dis)
    {
        $this->event_dis = $event_dis;
    }

    public function __construct()
    {
        // parent::__construct();
        $this->CI =& get_instance();

    }

    public function formulasiPersen($diskon)
    {
        $var = 1 * (1 - ($diskon / 100));
        return $var;
    }

    public function formulasiAngka($potongan)
    {
        // $var = 1 * (1 - ($potongan / 100));
        $var = $potongan;
        return $var;
    }

    public function calcDiskon($harga, $diskon_pokoks, $diskon_events = array(), $diskon_free_produk = array(), $type_premi_diskon = "", $biaya = "0")
    {

        $diskon_poko_sub = 0;
        foreach ($diskon_pokoks as $diskon_nama => $diskon_persen) {
            // cekKuning("diskon_pokoks ($diskon_nama):" . $diskon_persen);
            $diskon_poko_sub += $diskon_persen;
        }

        //         arrPrint($diskon_pokoks);
        //         cekUngu($diskon_free_produk);
        // cekBiru("diskon_pokoks: $diskon_poko_sub");

        $diskon_pokok_sub_desimal = $this->formulasiPersen($diskon_poko_sub);
        // cekHere("diskon_pokok_sub_desimal: " . $diskon_pokok_sub_desimal);

        // echo "<hr>";

        $diskon_event_sub = 1;
        foreach ($diskon_events as $diskon_nama => $diskon_persen) {
            $diskon_event = $this->formulasiPersen($diskon_persen);
            // cekKuning("diskon_event ($diskon_nama): " . $diskon_event);
            $diskon_event_sub *= $diskon_event;
        }

        // cekBiru("diskon_event_sub: " . $diskon_event_sub);
        $this->CI->load->helper("he_angka");

        $diskon_desimal = 1 - (($diskon_pokok_sub_desimal) * ($diskon_event_sub));
        // cekHere("$diskon_desimal = 1 - (($diskon_pokok_sub_desimal) * ($diskon_event_sub));");

        $tipe = $diskon_desimal < 0 ? "premi" : "diskon";
        $type = $type_premi_diskon == "" ? $tipe : $type_premi_diskon;
        $f_kali = $type == "premi" ? "-1" : "1";
        // $diskon_nilai = pembulatanDiskon($harga * $diskon_desimal * $f_kali);
        // $diskon_nilai = $diskon_nilais["hasil"];
        $diskon_nilai = $harga * $diskon_desimal * $f_kali;

        // arrPrintHijau($diskon_nilais);
        // $diskon_nilai = $diskon_nilais["pecahan"];

        // cekKuning($harga * $diskon_desimal . " ==== $diskon_nilai = (int)($harga * $diskon_desimal);");
        // $diskon_nilai = ($harga * $diskon_desimal);
        // cekBiru("$diskon_nilai * $f_kali");

        $harga_af_diskon = $harga - $diskon_nilai;

        $vars = array();
        $vars['type'] = $type;
        $vars['persen'] = ($diskon_desimal * 100);
        $vars['nilai'] = $diskon_nilai * $f_kali;
        $vars['biaya'] = $biaya;
        $vars['harga_be'] = $harga;
        $vars['harga_af'] = $harga_af_diskon + $biaya;

        //        cekUngu( $diskon_free_produk );

        if (isset($diskon_free_produk['free_produk']) && count($diskon_free_produk['free_produk']) > 0) {
            foreach ($diskon_free_produk as $kyRow => $kRow) {
                if ($kyRow == "free_produk") {
                    $vars['free_produk'] = 1;
                    $vars['nomer_diskon'] = $kRow['nomer_diskon'];
                    $vars['ori_produk_id'] = $kRow['produk_id'];
                    $vars['ori_produk_nama'] = $kRow['produk_nama'];
                    $vars['free_produk_id'] = $kRow['free_produk_id'];
                    $vars['free_produk_nama'] = $kRow['free_produk_nama'];
                    $vars['free_produk_qty'] = $kRow['free_produk_qty'];
                    $vars['kelipatan'] = $kRow['kelipatan'];
                    $vars['minim'] = $kRow['minim'];
                    $vars['free_qty'] = $kRow['free_produk_qty'];
                    $vars['produk_beli_jml'] = $kRow['produk_beli_jml'];
                    $vars['dtime'] = date("Y-m-d H:i:s");
                    $vars['quota_global'] = $kRow['quota_global'];
                    $vars['quota_used'] = $kRow['quota_used'];
                    $vars['quota_sisa'] = $kRow['quota_sisa'];

                    if (($kRow['quota_used'] + $kRow['free_produk_qty']) > $kRow['quota_global']) {
                        $vars['free_produk_note'] = "Limit Quota Tercapai (" . $kRow['free_produk_qty'] . "x " . $kRow['free_produk_nama'] . ") minimal pembelian (" . $kRow['minim'] . "x " . $kRow['produk_nama'] . ")" . ($kRow['kelipatan'] == 1 ? " berlaku kelipatan (" . ($kRow['quota_global'] - $kRow['quota_used']) . ")" : " tidak berlaku kelipatan (" . ($kRow['quota_global'] - $kRow['quota_used']) . ")");
                    }
                    else {
                        $vars['free_produk_note'] = "Free (" . $kRow['free_produk_qty'] . "x " . $kRow['free_produk_nama'] . ") minimal pembelian (" . $kRow['minim'] . "x " . $kRow['produk_nama'] . ")" . ($kRow['kelipatan'] == 1 ? " berlaku kelipatan (" . ($kRow['quota_global'] - $kRow['quota_used']) . ")" : " tidak berlaku kelipatan (" . ($kRow['quota_global'] - $kRow['quota_used']) . ")");
                    }

                }
            }
        }

        if (isset($diskon_free_produk['note_free_produk']) && count($diskon_free_produk['note_free_produk']) > 0) {
            foreach ($diskon_free_produk['note_free_produk'] as $kyRow => $kRow) {
                if ($kyRow == "note_free_produk") {
                    if (($kRow['quota_used'] + $kRow['free_produk_qty']) > $kRow['quota_global']) {
                        $vars['free_produk_note'] = "Limit Quota Tercapai (" . $kRow['free_produk_qty'] . "x " . $kRow['free_produk_nama'] . ") minimal pembelian (" . $kRow['minim'] . "x " . $kRow['produk_nama'] . ")" . ($kRow['kelipatan'] == 1 ? " berlaku kelipatan (" . ($kRow['quota_global'] - $kRow['quota_used']) . ")" : " tidak berlaku kelipatan (" . ($kRow['quota_global'] - $kRow['quota_used']) . ")");
                    }
                    else {
                        // $vars['free_produk_note'] = "Free (" . $kRow['free_produk_qty'] . "x " . $kRow['free_produk_nama'] . ") minimal pembelian (" . $kRow['minim'] . "x " . $kRow['produk_nama'] . ")" . ($kRow['kelipatan'] == 1 ? " berlaku kelipatan (" . ($kRow['quota_global'] - $kRow['quota_used']) . ")" : " tidak berlaku kelipatan (" . ($kRow['quota_global'] - $kRow['quota_used']) . ")");
                        $vars['free_produk_note'] = "<b>" . $kRow['free_produk_qty'] . "</b>x " . $kRow['free_produk_nama'] . " <br>minimal pembelian <b>" . $kRow['minim'] . " " . $kRow['produk_satuan'] . "</b>x " . ($kRow['kelipatan'] == 1 ? " berlaku kelipatan (" . ($kRow['quota_global'] - $kRow['quota_used']) . "" : " <span class='meta'>(tidak berlaku kelipatan)</span>");
                    }
                }
            }
        }

        // ==============Free Produk =============

        //        $vars = array();
        //        $vars['type'] = "free_produk";
        //        $vars['persen'] = ($diskon_desimal * 100);
        //        $vars['nilai'] = $diskon_nilai * $f_kali;
        //        $vars['biaya'] = $biaya;
        //        $vars['harga_be'] = $harga;
        //        $vars['harga_af'] = $harga_af_diskon + $biaya;

        // cekMerah($diskon_pokok_sub_desimal);
        // cekMerah($diskon_event_sub);
        // cekMerah($diskon_desimal);

        return $vars;
    }

    public function calcPotongan($harga, $potongan, $biaya = 0)
    {

        $harga_af_diskon = $harga - $potongan;
        $diskon_desimal = $potongan / $harga;
        $diskon_nilai = $potongan;

        $vars = array();
        // $vars['type'] = $type;
        $vars['persen'] = ($diskon_desimal * 100);
        $vars['nilai'] = $diskon_nilai;
        // $vars['biaya'] = $biaya;
        $vars['harga_be'] = $harga;
        $vars['harga_af'] = $harga_af_diskon + $biaya;

        return $vars;
    }

    public function callProdukDiskon($produk_id = "")
    {
        $toko_id = isset($this->toko_id) ? $this->toko_id : matiHere("toko_id harus diset @" . __FUNCTION__);
        $this->CI->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        $prods = $pr->callSpecs($produk_id);
        // showLast_query("kuning");
        // arrPrintKuning($prods);
        foreach ($prods as $prod) {
            $prod_speks[$prod->id] = (array)$prod;
        }
        // cekKuning($produk_id);
        if ($produk_id == "") {
            // cekHijau();
        }
        else {
            $prod_speks = $prod->diskon_persen;
        }

        $this->CI->load->model("Mdls/MdlDiskonGrosir");
        $dg = new MdlDiskonGrosir();
        $dg->setTokoId($toko_id);
        $grosier = $dg->callProdukGrosir($produk_id);
        // showLast_query("hijau");
        // arrPrintHijau($grosier);
        $grosir_speks = array();
        foreach ($grosier as $item) {
            $produk_id = $item->produk_id;
            $persen = $item->persen;
            /*----data produk----*/
            $prod_spek = isset($prod_speks[$produk_id]) ? $prod_speks[$produk_id] : array();
            $premi_jual = isset($prod_speks["premi_jual"]) ? $prod_speks["premi_jual"] * 1 : 0;
            $diskon_persen = isset($prod_speks["diskon_persen"]) ? $prod_speks["diskon_persen"] : 0;

            // arrPrint($prod_spek);
            // $gro_speks['minimum'] = $item->minim;
            // $gro_speks['diskon_persen'] = $item->persen;

            $gro_speks = (array)$item;
            // arrPrintKuning($gro_speks);
            $grosir_speks[] = $gro_speks;
        }

        $this->CI->load->model("Mdls/MdlDiskonFreeProduk");
        $fp = new MdlDiskonFreeProduk();
        $fp->setTokoId($toko_id);
        $fp->addFilter('expired>' . strtotime(date('Y-m-d H:i:s')));
        $fp->addFilter('date(dtime_start)>' . date('Y-m-d H:i:s'));
        $freeproduk = $fp->callFreeProduk($produk_id);

        //         showLast_query("ungu");
        //         arrPrintHijau($freeproduk);
        $freeproduk_speks = array();
        foreach ($freeproduk as $item) {
            $fro_speks = (array)$item;
            $freeproduk_speks[] = $fro_speks;
        }

        foreach ($prods as $prod) {
            // $datas['produk'] = $prod->diskon_persen;
            $datas['produk'] = $prod_speks;
            $datas['grosir'] = $grosir_speks;
            $datas['freeproduk'] = $freeproduk_speks;
        }

        return $datas;
    }

    public function callProdukDiskonPembelian($produk_id = "")
    {
        $toko_id = isset($this->toko_id) ? $this->toko_id : matiHere("toko_id harus diset @" . __FUNCTION__);
        $this->CI->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        $prods = $pr->callSpecs($produk_id);

        foreach ($prods as $prod) {
            $prod_speks[$prod->id] = (array)$prod;
        }
        // cekKuning($produk_id);
        if ($produk_id == "") {
            // cekHijau();
        }
        else {
            $prod_speks = $prod->diskon_persen;
        }

        $this->CI->load->model("Mdls/MdlDiskonPembelian");
        $dg = new MdlDiskonPembelian();

        $dg->setTokoId($toko_id);
        $this->CI->db->where("nilai > 0");
        $grosier = $dg->callSpecs($produk_id);
        // cekHere(count($grosier));
        // showLast_query("hijau");
        // arrPrint($grosier);
        $grosir_speks = array();
        foreach ($grosier as $prod_id => $item) {
            // $prod_id = $item->produk_id;
            $gro_speks = (array)$item;
            $grosir_speks[$prod_id] = $gro_speks;
        }

        $this->CI->load->model("Mdls/MdlDiskonPembelianPairSupplier");
        $this->CI->load->model("Mdls/MdlSupplier");
        $s = new MdlSupplier();
        $allSupplier_tmp = $s->lookUpAll()->result();
        $supplier = array();
        foreach ($allSupplier_tmp as $allSupplier_tmp_0){
            $supplier[$allSupplier_tmp_0->id]=$allSupplier_tmp_0->nama;
        }
        $fp = new MdlDiskonPembelianPairSupplier();
        $fp->setTokoId($toko_id);
//        $fp->setTokoId($toko_id);
//        $fp->addFilter("supplier_id='$pihakID'");
        $fp->addFilter('date(start_date)<' . date('Y-m-d'));
        $fp->addFilter('expired_date>=' . date('Y-m-d'));
        $freeproduk = $fp->callSpecs($produk_id);
        // ceklIme($this->CI->db->last_query());

        $freeproduk_speks = array();
        foreach ($freeproduk as $item) {
            $fro_speks = (array)$item;
            $freeproduk_speks[$item->produk_id] = $fro_speks+array("supplier_nama"=>$supplier[$item->supplier_id]);
        }

        foreach ($prods as $prod) {
            // $datas['produk'] = $prod->diskon_persen;
            $datas['produk'] = $prod_speks;
            $datas['grosir'] = $grosir_speks;
            $datas['freeproduk'] = $freeproduk_speks;
        }

        // arrPrint($datas);
        // matiHere();
        return $datas;
    }

    public function callProdukDiskonPembelianSaldo($produk_id = "")
    {
        $toko_id = isset($this->toko_id) ? $this->toko_id : matiHere("toko_id harus diset @" . __FUNCTION__);
        $this->CI->load->model("Coms/ComRekeningPembantuPiutangSupplierMain");
        $pr = new ComRekeningPembantuPiutangSupplierMain();
        $pr->addFilter("extern_id = $produk_id");
        $prods = $pr->fetchBalances('1010020030');
        // showLast_query("merah");
        // arrPrintWebs($prods);

        $datas = $prods;

        return $datas;
    }

    public function callCustomerLevelDiskon($level_id = "")
    {
        $toko_id = isset($this->toko_id) ? $this->toko_id : matiHere("toko_id harus diset @" . __FUNCTION__);
        $this->CI->load->model("Mdls/MdlCustomerLevel");
        $cl = new MdlCustomerLevel();
        if ($level_id != "") {
            if (is_array($level_id)) {
                $this->CI->db->or_where_in("id", $level_id);
            }
            else {
                $this->CI->db->where("id", $level_id);
            }
        }
        $condites = array(
            "toko_id" => $toko_id
        );
        $this->CI->db->where($condites);
        $src_cls = $cl->lookupAll()->result();
        // showLast_query("hijau");

        /*------------customer diskon------------------*/
        $this->CI->load->model("Mdls/MdlDiskonCustomer");
        $cd = new MdlDiskonCustomer();
        if ($level_id != "") {
            if (is_array($level_id)) {
                $this->CI->db->or_where_in("customer_level", $level_id);
            }
            else {
                $this->CI->db->where("customer_level", $level_id);
            }
        }
        // $filters = $cd->getCiFilters();
        $filters = array(
            "trash" => 0
        );
        $cd->setFilters(array());
        // arrPrintKuning($filters);
        $other_condites = isset($this->customer_level_condite) ? $this->customer_level_condite : array();
        if ($level_id > 0) {
            $other_condites = array(
                "customer_level" => $level_id
            );
        }
        $condites = array(
                "toko_id" => $toko_id
            ) + $filters + $other_condites;
        // arrPrintPink($condites);
        $this->CI->db->where($condites);
        $this->CI->db->order_by("minim", "asc");
        $src_cds_0 = $cd->lookupAll()->result_array();
        $src_cds = sizeof($src_cds_0) > 0 ? $src_cds_0 : array();
        // showLast_query("kuning");

        $datas['customer_level'] = $src_cls;
        $datas['customer_level_diskon'] = $src_cds;

        return $datas;
    }

    public function callDiskonFreeProduk($show_non_aktif = 0)
    {
        $toko_id = isset($this->toko_id) ? $this->toko_id : matiHere("toko_id harus diset @" . __FUNCTION__);

        /*------------ diskon free produk ------------*/
        $this->CI->load->model("Mdls/MdlDiskonFreeProduk");
        $cd = new MdlDiskonFreeProduk();

        if ($show_non_aktif != 1) {
            $this->CI->db->where("expired>" . strtotime(date("Y-m-d H:i:s")));
        }

        $filters = $cd->getCiFilters();
        $cd->setFilters(array());

        $condites = array(
            "toko_id" => $toko_id,
            "jenis" => "free_produk",
            "trash" => "0",
        );

        $this->CI->db->where($condites);

        //        $this->CI->db->where("expired=".strtotime(date("Y-m-d H:i:s")));
        //        $this->CI->db->where("expired>".strtotime(date("Y-m-d H:i:s")));

        $this->CI->db->order_by("expired", "asc");
        $src_cds_0 = $cd->lookupAll()->result_array();
        $src_cds = sizeof($src_cds_0) > 0 ? $src_cds_0 : array();

        $datas['customer_level'] = array();
        $datas['diskon_free_produk'] = $src_cds;

        return $datas;
    }

    public function doSaveCustomerLevelDiskon($level_id, $datas)
    {
        // $dt_minim_be = isset($datas['minim_be']) && ($datas['minim_be'] > 0) ? $datas['minim_be'] : matiHere("minim_be belum terdeteksi");
        $dt_minim_be = isset($datas['minim_be']) && ($datas['minim_be'] > 0) ? $datas['minim_be'] : "";
        $dt_minim = $datas['minim'];
        $dt_jenis = $datas['jenis'];
        $dt_persen = $datas['persen'];
        $dt_clevel = isset($datas['customer_level']) ? $datas['customer_level'] : null;

        $toko_id = isset($this->toko_id) ? $this->toko_id : matiHere("toko_id harap diset");
        $this->CI->load->model("Mdls/MdlDiskonCustomer");
        $cl = new MdlDiskonCustomer();
        $blacklist = array(
            "minim_be"
        );
        $data_upds = array_diff_key($datas, array_flip($blacklist));
        // UPDATE `diskon_customer` SET `maxim` = '100000' WHERE `jenis` = 'transaksi' AND `customer_level` = 2 AND `minim` = '' AND `toko_id` = '1001'
        $srcs = $this->callCustomerLevelDiskon($level_id);
        showLast_query("orange");
        // arrPrint($srcs);
        $src_datas = isset($srcs['customer_level_diskon']) ? $srcs['customer_level_diskon'] : array();
        $src_data = array();
        foreach ($src_datas as $src) {
            $s_jenis = $src["jenis"];
            $s_persen = $src["persen"] * 1;

            if ($dt_jenis == $s_jenis && $dt_persen == $s_persen) {
                $src_data = $src;
            }
        }
        arrPrintHijau($src_data);

        $db_minim = isset($src_data['minim']) ? $src_data['minim'] : null;
        $db_jenis = isset($src_data['jenis']) ? $src_data['jenis'] : null;
        $db_clevel = isset($src_data['customer_level']) ? $src_data['customer_level'] : null;


        // arrPrintPink($datas);
        // arrPrintKuning($data_upds);
        // arrPrintPink($src_datas);
        // matiHere();
        cekBiru("($db_clevel == $dt_clevel) && ($db_jenis == $dt_jenis) && ($db_minim == $dt_minim)");
        if (($db_clevel == $dt_clevel) && ($db_jenis == $dt_jenis) && ($db_minim == $dt_minim)) {
            // $data_upds

            $cl->setFilters(array());
            $condites = array(
                "jenis" => $dt_jenis,
                "customer_level" => $level_id,
                "minim" => $db_minim,
                "toko_id" => $toko_id,
            );
            $cl->updateData($condites, $data_upds);
            showLast_query("biru");
        }
        else {
            cekMerah("tidak ketemu id $level_id");
            /* ----------------------------------
             * update data sebelumnya
             * ---------------------------------*/
            $cl->setFilters(array());
            $data_last = array(
                // "maxim" => $dt_minim,
                "trash" => "1",
            );
            $condite_last = array(
                "jenis" => $dt_jenis,
                "customer_level" => $level_id,
                "minim" => $dt_minim,
                "toko_id" => $toko_id,
            );
            $cl->updateData($condite_last, $data_last);
            showLast_query("kuning");

            // ------------------------------------------------------------------

            $data_upds['toko_id'] = $toko_id;
            $data_upds['status'] = 1;
            // arrPrintHijau($data_upds);

            $cl->addData($data_upds);
            showLast_query("hijau");


        }


    }

    // ini masih lama sebelum diedit headernya
    public function connectPurcash_lama($itemData, $cCode, $toko_id, $modul, $addEvent)
    {
        // arrPrint($addEvent);
        // matiHEre();
        $filds = array(
            "nama" => "produk",
            "no_part" => "kode",
            "harga_list" => "harga list",
            "hpp" => "harga beli",
            "disc_persen_jual" => "diskon(%)",
            "premi_persen_jual" => "premi(%)",
            "jual_nppn" => "harga jual",
        );
        $editAbleFields = array(
            "disc_persen_jual" => array(
                "min" => "0",
                "max" => "100",

                "jscript" => "
                $(\"input#{key}_{id}\").on('keyup', function(){
                    var premi_ = $('input#premi_persen_jual_{id}').val();
                    var harga_ = removeCommas($('input#harga_list_{id}').val());
                    var diskon_ = $(this).val();
                    
                    var pre_diskon_ = (harga_*1) * ((diskon_*1)/100);
                    var pre_premi_ = (harga_*1) * ((premi_*1)/100);
                    var jual_final = (harga_*1) - (pre_diskon_*1) + (pre_premi_*1);
                    
                    if((diskon_*1)>100){
                        swal('ERROR','Diskon lebih besar dari 100% tidak diijinkan, silahkan diperbaiki')
                        $(this).val( $(this)[0].defaultValue )
                        console.log( $(this).prop('id') );
                        console.log( $(this)[0].defaultValue );
                    }
                    else if(isNaN(jual_final)){
                        swal('ERROR','Silahkan Gunakan Titik (.) untuk desimal')
                        $(this).val( $(this)[0].defaultValue )
                        console.log( $(this).prop('id') );
                        console.log( $(this)[0].defaultValue );
                    }
                    else{
                        $('#row_jual_nppn_{id}').html( addCommas(jual_final.toFixed(0)) );
                    }
                    
                })

                ",
            ),
            "premi_persen_jual" => array(
                "min" => "0",
                "max" => "100",

                "jscript" => "
                $(\"input#{key}_{id}\").on('keyup', function(){
                    var diskon_ = $('input#disc_persen_jual_{id}').val();
                    var harga_ = removeCommas($('input#harga_list_{id}').val());
                    var premi_ = $(this).val();
                    
                    var pre_diskon_ = (harga_*1) * ((diskon_*1)/100);
                    var pre_premi_ = (harga_*1) * ((premi_*1)/100);
                    var jual_final = (harga_*1) - (pre_diskon_*1) + (pre_premi_*1);
                    
                    if((premi_*1)<0){
                        swal({title:'ERROR', html:'Premi lebih kecil dari 0 tidak diijinkan, silahkan periksa kembali'})
                        $(this).val( $(this)[0].defaultValue )
                        console.log( $(this).prop('id') );
                        console.log( $(this)[0].defaultValue );
                    }
                    else{
                        $('#row_jual_nppn_{id}').html( addCommas(jual_final.toFixed(0)) );
                    }
                })

                ",
            ),
            "harga_list" => array(
                "default" => "disabled",
                "jscript" => "
                $(\"input[name='{key}_{id}']\").on('change', function(){
                    if( $(this).is(':checked') ){
                        $('#{key}_{id}').prop('disabled', false);    
                    }
                    else{
                        $('#{key}_{id}').prop('disabled', true);  
                    }
                })

                ",
            ),
        );

        $targetLink = $this->updateDiskonPurcashConnected($cCode);

        $pairPrice = array("price_setting" => "jual_nppn");
        $list = "<div class='panel-heading'></div>";
        $list .= "<div class='table-responsive'>";
        $list .= "<H4 >Setting Diskon harga jual</H4>";
        $list .= "<table class='table table-bordered table-hover table-condensed table-striped'>";
        $list .= "<tr  bgcolor='#f5f5f5'>";
        $list .= "<th class='text-muted'  style='font-weight:bold;' align='right' width='5'>No</th>";
        $list .= "<th class='text-green text-bold text-center' width='150'>update <br>harga list</th>";
        foreach ($filds as $keys => $label) {
            $list .= "<th>$label</th>";
        }
        $list .= "</tr>";
        $i = 0;
        $data = array();
        foreach ($itemData as $id => $itemData_0) {
            $i++;
            $list .= "<tr>";
            $list .= "<th>$i</th>";
            $list .= "<th><input type='checkbox' name='harga_list_$id'></th>";
            $data_temp = array();
            $listedKeyJs = array();
            foreach ($filds as $key => $label) {
                $fieldForm = "";
                if (isset($editAbleFields[$key])) {
                    $listedKeyJs[] = $key;
                    $min = isset($editAbleFields[$key]["min"]) ? $editAbleFields[$key]["min"] : "";
                    $max = isset($editAbleFields[$key]["max"]) ? $editAbleFields[$key]["max"] : "";
                    $disabled = isset($editAbleFields[$key]["default"]) ? $editAbleFields[$key]["default"] : "";
                    if (isset($editAbleFields[$key]["jscript"])) {
                        $performReplace1 = str_replace("{key}", $key, $editAbleFields[$key]["jscript"]);
                        $performReplace2 = str_replace("{id}", $id, $performReplace1);
                        echo "<script>$performReplace2</script>";
                        // cekMerah($performReplace2);
                    }

                    $foramtFields = number_format(0 + $itemData_0[$key]);
                    // $addEventes = "onchange=\"$('#result').load('uri_encode($tt&val='+parseFloat(removeCommas(this.value))))\" ";
                    // $fieldForm = "<input type='decimal' id='$key"."_"."$id' value='".$itemData_0[$key]."' min='$min' max='$max' onkeyup=\"$addEventes?\" onclick='this.select()'>";
                    $fieldForm = "<input autocomplete='off' type='decimal' id='$key" . "_" . "$id' value='" . $foramtFields . "' min='$min' max='$max' onblur=\"$('#result').load('$addEvent?pid=$id&key=$key&val='+parseFloat(removeCommas(this.value)))\" onclick='this.select()' $disabled>";

                    // $values = $itemData_0[$key];
                }
                else {
                    $disabled = "disabled";
                    $min = "";
                    $max = "";
                    // cekBiru($key);
                    if (isset($itemData_0[$key])) {
                        // $fieldForm =formatField_he_format($key,$itemData_0[$key]);
                        $values = $itemData_0[$key];
                    }
                    else {
                        if ($key == "jual_nppn") {
                            $values = pembulatanDiskon($itemData_0["harga_list"] - ($itemData_0["harga_list"] * ($itemData_0["disc_persen_jual"] / 100)));
                        }
                        else {
                            $values = $itemData_0[$key];
                        }


                    }
                    $fieldForm = formatField_he_format($key, $values);

                }

                // $value_1 = is_numeric($values) ? number_format($values) : $values;
                // $addEvent
                // $fieldForm = "<input type='decimal' id='$key"."_"."$id' value=\"$value_1\" min='$min' max='$max'  onchange=\"\" $disabled>";
                $list .= "<th id='row_" . $key . "_" . $id . "'>" . $fieldForm . "</th>";
                $data_temp[$key] = $values;
            }
            $data[$id] = $data_temp;
            $list .= "</tr>";
        }
        $list .= "</table>";

        // echo $list;
        //         matiHEre();
        return array("ui" => $list, "data" => $data);
        // showLast_query("biru");

    }

    public function connectPurcash($itemData, $cCode, $toko_id, $modul, $addEvent)
    {
        // arrPrint($addEvent);
        // matiHEre($cCode);
        $filds = array(
            "nama" => "produk",
            "no_part" => "kode",
            "hpp" => "harga beli",
            "harga_list" => "harga jual sekarang",
            "jual_nppn" => "harga jual baru",
            // "disc_persen_jual"  => "diskon(%)",
            // "premi_persen_jual" => "premi(%)",
        );
        $editAbleFields = array(
            // "disc_persen_jual"  => array(
            //     "min" => "0",
            //     "max" => "100",
            //
            //     "jscript" => "
            //     $(\"input#{key}_{id}\").on('keyup', function(){
            //         var premi_ = $('input#premi_persen_jual_{id}').val();
            //         var harga_ = removeCommas($('input#harga_list_{id}').val());
            //         var diskon_ = $(this).val();
            //
            //         var pre_diskon_ = (harga_*1) * ((diskon_*1)/100);
            //         var pre_premi_ = (harga_*1) * ((premi_*1)/100);
            //         var jual_final = (harga_*1) - (pre_diskon_*1) + (pre_premi_*1);
            //
            //         if((diskon_*1)>100){
            //             swal('ERROR','Diskon lebih besar dari 100% tidak diijinkan, silahkan diperbaiki')
            //             $(this).val( $(this)[0].defaultValue )
            //             console.log( $(this).prop('id') );
            //             console.log( $(this)[0].defaultValue );
            //         }
            //         else if(isNaN(jual_final)){
            //             swal('ERROR','Silahkan Gunakan Titik (.) untuk desimal')
            //             $(this).val( $(this)[0].defaultValue )
            //             console.log( $(this).prop('id') );
            //             console.log( $(this)[0].defaultValue );
            //         }
            //         else{
            //             $('#row_jual_nppn_{id}').html( addCommas(jual_final.toFixed(0)) );
            //         }
            //
            //     })
            //
            //     ",
            // ),
            // "premi_persen_jual" => array(
            //     "min" => "0",
            //     "max" => "100",
            //
            //     "jscript" => "
            //     $(\"input#{key}_{id}\").on('keyup', function(){
            //         var diskon_ = $('input#disc_persen_jual_{id}').val();
            //         var harga_ = removeCommas($('input#harga_list_{id}').val());
            //         var premi_ = $(this).val();
            //
            //         var pre_diskon_ = (harga_*1) * ((diskon_*1)/100);
            //         var pre_premi_ = (harga_*1) * ((premi_*1)/100);
            //         var jual_final = (harga_*1) - (pre_diskon_*1) + (pre_premi_*1);
            //
            //         if((premi_*1)<0){
            //             swal({title:'ERROR', html:'Premi lebih kecil dari 0 tidak diijinkan, silahkan periksa kembali'})
            //             $(this).val( $(this)[0].defaultValue )
            //             console.log( $(this).prop('id') );
            //             console.log( $(this)[0].defaultValue );
            //         }
            //         else{
            //             $('#row_jual_nppn_{id}').html( addCommas(jual_final.toFixed(0)) );
            //         }
            //     })
            //
            //     ",
            // ),
            "jual_nppn" => array(
                "min" => "0",
                "max" => "100",

                "jscript" => "
                $(\"input#{key}_{id}\").on('keyup', function(){
                    var diskon_ = $('input#disc_persen_jual_{id}').val();
                    var harga_ = removeCommas($('input#harga_list_{id}').val());
                    var premi_ = $(this).val();
                    
                    var pre_diskon_ = (harga_*1) * ((diskon_*1)/100);
                    var pre_premi_ = (harga_*1) * ((premi_*1)/100);
                    var jual_final = (harga_*1) - (pre_diskon_*1) + (pre_premi_*1);
                    
                    // if((premi_*1)<0){
                    //     swal({title:'ERROR', html:'Premi lebih kecil dari 0 tidak diijinkan, silahkan periksa kembali'})
                    //     $(this).val( $(this)[0].defaultValue )
                    //     console.log( $(this).prop('id') );
                    //     console.log( $(this)[0].defaultValue );
                    // }
                    // else{
                    //     $('#row_jual_nppn_{id}').html( addCommas(jual_final.toFixed(0)) );
                    // }
                })

                ",
            ),
            "harga_list" => array(
                "default" => "disabled",
                "jscript" => "
                $(\"input[name='{key}_{id}']\").on('change', function(){
                    if( $(this).is(':checked') ){
                        $('#{key}_{id}').prop('disabled', false);    
                    }
                    else{
                        $('#{key}_{id}').prop('disabled', true);  
                    }
                })

                ",
            ),
        );

        $targetLink = $this->updateDiskonPurcashConnected($cCode);

        $pairPrice = array("price_setting" => "jual_nppn");
        $list = "<div class='panel-heading'></div>";
        $list .= "<div class='table-responsive'>";
        $list .= "<H4 >Setting harga jual</H4>";
        $list .= "<table class='table table-bordered table-hover table-condensed table-striped'>";
        $list .= "<tr  bgcolor='#f5f5f5'>";
        $list .= "<th class='text-muted'  style='font-weight:bold;' align='right' width='5'>No</th>";
        //        $list .= "<th class='text-green text-bold text-center' width='150'>update <br>harga list</th>";
        foreach ($filds as $keys => $label) {
            $list .= "<th>$label</th>";
        }
        $list .= "</tr>";
        $i = 0;
        $data = array();
        foreach ($itemData as $id => $itemData_0) {
            arrPrintWebs($itemData_0);
            $i++;
            $list .= "<tr>";
            $list .= "<th>$i</th>";
            //            $list .= "<th><input type='checkbox' name='harga_list_$id'></th>";

            $data_temp = array();
            $listedKeyJs = array();
            foreach ($filds as $key => $label) {
                $fieldForm = "";
                if (isset($editAbleFields[$key])) {
                    $listedKeyJs[] = $key;
                    $min = isset($editAbleFields[$key]["min"]) ? $editAbleFields[$key]["min"] : "";
                    $max = isset($editAbleFields[$key]["max"]) ? $editAbleFields[$key]["max"] : "";
                    $disabled = isset($editAbleFields[$key]["default"]) ? $editAbleFields[$key]["default"] : "";
                    if (isset($editAbleFields[$key]["jscript"])) {
                        $performReplace1 = str_replace("{key}", $key, $editAbleFields[$key]["jscript"]);
                        $performReplace2 = str_replace("{id}", $id, $performReplace1);
                        echo "<script>$performReplace2</script>";
                        // cekMerah($performReplace2);
                    }

                    $foramtFields = isset($itemData_0[$key]) ? number_format(0 + $itemData_0[$key]) : "";
                    // $addEventes = "onchange=\"$('#result').load('uri_encode($tt&val='+parseFloat(removeCommas(this.value))))\" ";
                    // $fieldForm = "<input type='decimal' id='$key"."_"."$id' value='".$itemData_0[$key]."' min='$min' max='$max' onkeyup=\"$addEventes?\" onclick='this.select()'>";
                    $fieldForm = "<input autocomplete='off' type='decimal' id='$key" . "_" . "$id' value='" . $foramtFields . "' min='$min' max='$max' 
                    onblur=\"$('#result').load('$addEvent?pid=$id&key=$key&val='+parseFloat(removeCommas(this.value)))\" 
                    onclick='this.select()' $disabled>";

                    // $values = $itemData_0[$key];
                }
                else {
                    $disabled = "disabled";
                    $min = "";
                    $max = "";
                    // cekBiru($key);
                    if (isset($itemData_0[$key])) {
                        // $fieldForm =formatField_he_format($key,$itemData_0[$key]);
                        $values = $itemData_0[$key];
                    }
                    else {
                        if ($key == "jual_nppn") {
                            $values = pembulatanDiskon($itemData_0["harga_list"] - ($itemData_0["harga_list"] * ($itemData_0["disc_persen_jual"] / 100)));
                        }
                        else {
                            $values = $itemData_0[$key];
                        }


                    }
                    $fieldForm = formatField_he_format($key, $values);

                }

                // $value_1 = is_numeric($values) ? number_format($values) : $values;
                // $addEvent
                // $fieldForm = "<input type='decimal' id='$key"."_"."$id' value=\"$value_1\" min='$min' max='$max'  onchange=\"\" $disabled>";
                $list .= "<th id='row_" . $key . "_" . $id . "' title='$id'>" . $fieldForm . "</th>";
                $data_temp[$key] = $values;
            }
            $data[$id] = $data_temp;
            $list .= "</tr>";
        }
        $list .= "</table>";

        // echo $list;
        //         matiHEre();
        return array("ui" => $list, "data" => $data);
        // showLast_query("biru");

    }

    public function connectPurcashBaru($itemData, $cCode, $toko_id, $modul, $addEvent, $cabang_id)
    {
        $this->CI =& get_instance();
        $this->CI->load->model("Mdls/MdlHargaProduk");
        $hp = new MdlHargaProduk();
        $hp->setTokoId($toko_id);
        $hp->setCabangId($cabang_id);
        //        $condites = array(
        //            "jenis_value" => "harga_list"
        //        );
        //
        //        $this->CI->db->where($condites);
        $prod_hargas = $hp->callSpecs();
        //        showLast_query("biru");
        //        arrPrint($prod_hargas);
        $prod_hargas_data_blacklist = array(
            "hpp_nppv", "hpp"
        );
        $prod_hargas_data = array();
        if (sizeof($prod_hargas) > 0) {
            foreach ($prod_hargas as $pID => $pSpec) {
                foreach ($pSpec as $ppSpec) {
                    if (!in_array($ppSpec->jenis_value, $prod_hargas_data_blacklist)) {

                        $prod_hargas_data[$pID][$ppSpec->jenis_value] = $ppSpec->nilai;
                    }
                }
            }
        }
        //        arrPrintWebs($prod_hargas_data);

        //arrPrint($this->CI->config->item("hePrices")["produk"]);
        $fildsConfig = array();
        $arrFields = $this->CI->config->item("hePrices")["produk"];
        foreach ($arrFields as $key => $spec) {
            if (isset($spec["purchase"]) && ($spec["purchase"] == false)) {

            }
            else {
                $fildsConfig[$key] = $spec["label"];
            }
        }
        //arrPrint($fildsConfig);
        $filds = array(
            "nama" => "produk",
            "no_part" => "kode",
            "hpp" => "harga beli",
            //            "hpp_nppv" => "harga tandas",
            //            "jual" => "harga jual sekarang",
            //            "jual_baru" => "harga jual baru",
            // "disc_persen_jual"  => "diskon(%)",
            // "premi_persen_jual" => "premi(%)",
        );
        if (sizeof($fildsConfig) > 0) {
            $filds = $filds + $fildsConfig;
        }
        $filds["jual_baru"] = "harga jual baru*";
        $editAbleFields = array(
            // "disc_persen_jual"  => array(
            //     "min" => "0",
            //     "max" => "100",
            //
            //     "jscript" => "
            //     $(\"input#{key}_{id}\").on('keyup', function(){
            //         var premi_ = $('input#premi_persen_jual_{id}').val();
            //         var harga_ = removeCommas($('input#harga_list_{id}').val());
            //         var diskon_ = $(this).val();
            //
            //         var pre_diskon_ = (harga_*1) * ((diskon_*1)/100);
            //         var pre_premi_ = (harga_*1) * ((premi_*1)/100);
            //         var jual_final = (harga_*1) - (pre_diskon_*1) + (pre_premi_*1);
            //
            //         if((diskon_*1)>100){
            //             swal('ERROR','Diskon lebih besar dari 100% tidak diijinkan, silahkan diperbaiki')
            //             $(this).val( $(this)[0].defaultValue )
            //             console.log( $(this).prop('id') );
            //             console.log( $(this)[0].defaultValue );
            //         }
            //         else if(isNaN(jual_final)){
            //             swal('ERROR','Silahkan Gunakan Titik (.) untuk desimal')
            //             $(this).val( $(this)[0].defaultValue )
            //             console.log( $(this).prop('id') );
            //             console.log( $(this)[0].defaultValue );
            //         }
            //         else{
            //             $('#row_jual_nppn_{id}').html( addCommas(jual_final.toFixed(0)) );
            //         }
            //
            //     })
            //
            //     ",
            // ),
            // "premi_persen_jual" => array(
            //     "min" => "0",
            //     "max" => "100",
            //
            //     "jscript" => "
            //     $(\"input#{key}_{id}\").on('keyup', function(){
            //         var diskon_ = $('input#disc_persen_jual_{id}').val();
            //         var harga_ = removeCommas($('input#harga_list_{id}').val());
            //         var premi_ = $(this).val();
            //
            //         var pre_diskon_ = (harga_*1) * ((diskon_*1)/100);
            //         var pre_premi_ = (harga_*1) * ((premi_*1)/100);
            //         var jual_final = (harga_*1) - (pre_diskon_*1) + (pre_premi_*1);
            //
            //         if((premi_*1)<0){
            //             swal({title:'ERROR', html:'Premi lebih kecil dari 0 tidak diijinkan, silahkan periksa kembali'})
            //             $(this).val( $(this)[0].defaultValue )
            //             console.log( $(this).prop('id') );
            //             console.log( $(this)[0].defaultValue );
            //         }
            //         else{
            //             $('#row_jual_nppn_{id}').html( addCommas(jual_final.toFixed(0)) );
            //         }
            //     })
            //
            //     ",
            // ),

            "hpp_nppv" => array(
                //                "min" => "0",
                //                "max" => "100",
                //
                //                "jscript" => "
                //                $(\"input#{key}_{id}\").on('keyup', function(){
                //                    var diskon_ = $('input#disc_persen_jual_{id}').val();
                //                    var harga_ = removeCommas($('input#harga_list_{id}').val());
                //                    var premi_ = $(this).val();
                //
                //                    var pre_diskon_ = (harga_*1) * ((diskon_*1)/100);
                //                    var pre_premi_ = (harga_*1) * ((premi_*1)/100);
                //                    var jual_final = (harga_*1) - (pre_diskon_*1) + (pre_premi_*1);
                //
                //                    // if((premi_*1)<0){
                //                    //     swal({title:'ERROR', html:'Premi lebih kecil dari 0 tidak diijinkan, silahkan periksa kembali'})
                //                    //     $(this).val( $(this)[0].defaultValue )
                //                    //     console.log( $(this).prop('id') );
                //                    //     console.log( $(this)[0].defaultValue );
                //                    // }
                //                    // else{
                //                    //     $('#row_jual_nppn_{id}').html( addCommas(jual_final.toFixed(0)) );
                //                    // }
                //                }
                //                )
                //
                //                "
                //                ,
            ),
            "jual_baru" => array(
                "min" => "0",
                "max" => "100",

                "jscript" => "
                $(\"input#{key}_{id}\").on('keyup', function(){
                    var diskon_ = $('input#disc_persen_jual_{id}').val();
                    var harga_ = removeCommas($('input#harga_list_{id}').val());
                    var premi_ = $(this).val();
                    
                    var pre_diskon_ = (harga_*1) * ((diskon_*1)/100);
                    var pre_premi_ = (harga_*1) * ((premi_*1)/100);
                    var jual_final = (harga_*1) - (pre_diskon_*1) + (pre_premi_*1);
                    
                    // if((premi_*1)<0){
                    //     swal({title:'ERROR', html:'Premi lebih kecil dari 0 tidak diijinkan, silahkan periksa kembali'})
                    //     $(this).val( $(this)[0].defaultValue )
                    //     console.log( $(this).prop('id') );
                    //     console.log( $(this)[0].defaultValue );
                    // }
                    // else{
                    //     $('#row_jual_nppn_{id}').html( addCommas(jual_final.toFixed(0)) );
                    // }
                })

                ",
            ),

            //            "harga_list" => array(
            //                "default" => "disabled",
            //                "jscript" => "
            //                $(\"input[name='{key}_{id}']\").on('change', function(){
            //                    if( $(this).is(':checked') ){
            //                        $('#{key}_{id}').prop('disabled', false);
            //                    }
            //                    else{
            //                        $('#{key}_{id}').prop('disabled', true);
            //                    }
            //                })
            //
            //                ",
            //            ),

        );

        $targetLink = $this->updateDiskonPurcashConnected($cCode);
        //arrPrintWebs($itemData);
        $pairPrice = array("price_setting" => "jual_nppn");
        $list = "<div class='panel-heading'></div>";
        $list .= "<div class='table-responsive'>";
        $list .= "<H4 >Setting harga jual</H4>";
        $list .= "<table class='table table-bordered table-hover table-condensed table-striped'>";
        $list .= "<tr  bgcolor='#f5f5f5'>";
        $list .= "<th class='text-muted'  style='font-weight:bold;' align='right' width='5'>No</th>";
        //        $list .= "<th class='text-green text-bold text-center' width='150'>update <br>harga list</th>";
        foreach ($filds as $keys => $label) {
            $list .= "<th>$label</th>";
        }
        $list .= "</tr>";
        $i = 0;
        $data = array();
        foreach ($itemData as $id => $itemData_0) {
            if (isset($prod_hargas_data[$id])) {
                foreach ($prod_hargas_data[$id] as $kk => $vv) {
                    //                    cekHere("$id :: $kk => $vv");
                    $itemData_0[$kk] = $vv;
                }
            }
            $i++;
            $list .= "<tr>";
            $list .= "<th>$i</th>";
            //            $list .= "<th><input type='checkbox' name='harga_list_$id'></th>";

            $data_temp = array();
            $listedKeyJs = array();
            foreach ($filds as $key => $label) {
                $fieldForm = "";
                if (isset($editAbleFields[$key])) {
                    $listedKeyJs[] = $key;
                    $min = isset($editAbleFields[$key]["min"]) ? $editAbleFields[$key]["min"] : "";
                    $max = isset($editAbleFields[$key]["max"]) ? $editAbleFields[$key]["max"] : "";
                    $disabled = isset($editAbleFields[$key]["default"]) ? $editAbleFields[$key]["default"] : "";
                    if (isset($editAbleFields[$key]["jscript"])) {
                        $performReplace1 = str_replace("{key}", $key, $editAbleFields[$key]["jscript"]);
                        $performReplace2 = str_replace("{id}", $id, $performReplace1);
                        echo "<script>$performReplace2</script>";
                        // cekMerah($performReplace2);
                    }

                    $foramtFields = isset($itemData_0[$key]) ? number_format(0 + $itemData_0[$key]) : "";
                    // $addEventes = "onchange=\"$('#result').load('uri_encode($tt&val='+parseFloat(removeCommas(this.value))))\" ";
                    // $fieldForm = "<input type='decimal' id='$key"."_"."$id' value='".$itemData_0[$key]."' min='$min' max='$max' onkeyup=\"$addEventes?\" onclick='this.select()'>";
                    $fieldForm = "<input autocomplete='off' type='decimal' id='$key" . "_" . "$id' value='" . $foramtFields . "' min='$min' max='$max' 
                    onblur=\"$('#result').load('$addEvent?pid=$id&key=$key&val='+parseFloat(removeCommas(this.value)))\" 
                    onclick='this.select()' $disabled>";

                    // $values = $itemData_0[$key];
                }
                else {
                    $disabled = "disabled";
                    $min = "";
                    $max = "";
                    // cekBiru($key);
                    if (isset($itemData_0[$key])) {
                        // $fieldForm =formatField_he_format($key,$itemData_0[$key]);
                        $values = $itemData_0[$key];
                    }
                    else {
                        if ($key == "jual_nppn") {
                            $values = pembulatanDiskon($itemData_0["harga_list"] - ($itemData_0["harga_list"] * ($itemData_0["disc_persen_jual"] / 100)));
                        }
                        else {
                            $values = $itemData_0[$key];
                        }


                    }
                    $fieldForm = formatField_he_format($key, $values);

                }

                // $value_1 = is_numeric($values) ? number_format($values) : $values;
                // $addEvent
                // $fieldForm = "<input type='decimal' id='$key"."_"."$id' value=\"$value_1\" min='$min' max='$max'  onchange=\"\" $disabled>";
                $list .= "<th id='row_" . $key . "_" . $id . "' title='$id'>" . $fieldForm . "</th>";
                $data_temp[$key] = $values;
            }
            $data[$id] = $data_temp;
            $list .= "</tr>";
        }
        $list .= "</table>";

        // echo $list;
        //         matiHEre();
        return array("ui" => $list, "data" => $data);
        // showLast_query("biru");

    }

    public function updateDiskonPurcashConnected()
    {

    }

    public function callDiskonTebusMurah()
    {
        $toko_id = isset($this->toko_id) ? $this->toko_id : matiHere("toko_id harus diset @" . __FUNCTION__);
        $this->customer_level_condite = array(
            "tipe" => "tebus_murah",
        );
        $mainDiskon = $this->callCustomerLevelDiskon();
        $tebusMurah = $mainDiskon['customer_level_diskon'];

        /* ---------------------------------------------------------------------------------------------
         * produk yg dijadikan hadiah
         * ---------------------------------------------------------------------------------------------*/
        $this->CI->load->model("Mdls/MdlDiskonTebusMurah");
        $dtm = new MdlDiskonTebusMurah();
        $transaksi_minim = "300000";
        $dtm->setTokoId($toko_id);
        $srcs = $dtm->callDiskon($transaksi_minim);
        // arrPrintKuning($srcs);
        foreach ($srcs as $src) {
            $produk_id = $src->produk_id;
            $produkIds[] = $produk_id;
        }

        /* ---------------------------------------------------------------------------------------------
        * harga
        * ---------------------------------------------------------------------------------------------
        */
        $this->CI->load->model("Mdls/MdlHargaProduk");
        $hp = new MdlHargaProduk();

        $hp->setTokoId(my_toko_id());
        $hp->setCabangId(my_cabang_id());
        $this->CI->db->where("jenis_value", "harga_list");
        $prod_hargas = $hp->callSpecs($produkIds);
        // $src_harga = $dtm->callhargaJual($produkIds);
        $harga_list = array();
        foreach ($prod_hargas as $prod_id => $prod_harga_00s) {
            foreach ($prod_harga_00s as $prod_harga) {
                $nilai = $prod_harga->nilai * 1;
                $harga_list[$prod_id] = $nilai;
            }
        }

        /* ---------------------------------------------------------------------------------------------
        * gabungan
        * ---------------------------------------------------------------------------------------------
        */
        foreach ($srcs as $src) {

            $harga = isset($harga_list[$src->produk_id]) ? $harga_list[$src->produk_id] : 0;
            $cminim = $src->minim;
            $hargas['produk_harga'] = $harga;

            $arr_src[$cminim][] = (array)$src + $hargas;
        }

        // arrPrintPink($arr_src);
        $vars = array();
        $vars['parents'] = $tebusMurah;
        $vars['childs'] = $arr_src;


        return $vars;
    }

    public function cekDiskonProduk($sesItems, $cabang_id, $toko_id)
    {
        /* -----------------------------------------------------
        * diskon diskonan produk
        * // loader ditanam di view/shopingcart
        * -----------------------------------------------------*/
        $diskon_produks = array();
        $this->setTokoId($toko_id);
        $src_diskon = $this->CallProdukDiskon();
        $src_grosiers = $src_diskon["grosir"];
        $src_produks = $src_diskon["produk"];
        // showLast_query("hijau");
        // arrPrint($src_grosiers);
        foreach ($src_grosiers as $src_grosier) {
            $proId = $src_grosier['produk_id'];

            $diskon_produks[$proId][] = $src_grosier;
        }
        $diskon_produk_ids = array_keys($diskon_produks);
        // $sesItemBerdiskon = $sesItems
        // arrPrint($sesItems);
        /*---produk price untuk menganbil hpp----*/
        $this->CI->load->model("Mdls/MdlHargaProduk");
        $pp = new MdlHargaProduk();
        $pp_condites = array(
            "jenis_value" => "hpp_nppv",
        );
        $this->CI->db->where($pp_condites);
        $pp->setTokoId($toko_id);
        $pp->setCabangId($cabang_id);
        $srcPps = $pp->callSpecs();
        // showLast_query("kuning");
        // cekBiru(count($srcPps));
        // cekBiru($srcPps);
        foreach ($srcPps as $ppProdId => $srcPp) {
            $dataPps[$ppProdId] = $srcPp[0]->nilai * 1;
            // $dataPpsPpn[$ppProdId] = ($srcPp[0]->nilai * 1) * ((100 + $ppnFactor) / 100);
        }
        // arrPrintKuning($dataPps);
        // arrPrintKuning($dataPpsPpn);
        // -------------------------------------------end

        // $sesItems =
        $strDiskon = "";
        $strDiskon .= "<style>
            .box{
                margin-bottom: 10px !important;
            }
            .box-danger .box-header{
                padding: 3px 10px !important;    
            }
            .box-danger .box-body{
                padding: 3px 10px 10px !important;    
            }
            label{
                font-weight: unset;
                margin-bottom:unset;
            }
        </style>";
        if (sizeof($sesItems) > 0) {
            // $strDiskon .= "<h3>Info Diskon <small>Setiap pembelian*</small></h3>";
            //            $strDiskon .= "<h3>Info Harga Jual<small> Tanpa PPN</small></h3>";
            $strDiskon .= "<h3>Info Harga Jual<small> Include PPN</small></h3>";

            /* -----------------------
             * pilihan harga masnual true = aktif
             * ---------------------------------------------*/
            $link_active = true;
            // $link_active = false;

            /* -------------------------------------------------------
             * produk harga
             * -------------------------------------------------------*/
            foreach ($sesItems as $prod_id => $sesItem) {
                // arrPrintHijau($sesItem['jenisTr']);
                // arrPrintPink($sesItem);
                $jenisTrItems = $sesItem["jenisTr"];
                $row_harga_id = $sesItem["row_harga_id"];
                // $ppnFactor = ($sesItem["ppnFactor"]);
                $ppnFactor = isset($sesItem["ppnFactor_seting"]) ? $sesItem["ppnFactor_seting"] : $sesItem["ppnFactor"];
                $jml = ($sesItem["jml"]);
                $hppnppv = $dataPps[$prod_id];
                $hppnppv_f = number_format($hppnppv);
                $hppnppvnppn = $dataPps[$prod_id] * ((100 + $ppnFactor) / 100);
                $hppnppvnppn_f = number_format($hppnppvnppn);
                $prod_speks = $src_produks[$prod_id];
                // arrPrint($prod_speks);
                $premi_persen = $prod_speks['premi_jual'];
                $premi_persen_f = format_harga($premi_persen);
                $diskon_persen = $prod_speks['diskon_persen'];
                $diskon_persen_f = format_harga($diskon_persen);
                $prodNama = $sesItem['nama'];
                // $prodHarga_ori = $sesItem['harga'];
                $prodHarga_ori = $sesItem['jual'];
                $prodHarga_ori_reseller = $sesItem['jual_reseller'];
                $prodHarga_ori_online = $sesItem['jual_online'];
                $prodHarga_ori_reseller_f = format_harga($prodHarga_ori_reseller);
                $prodHarga_ori_f = format_harga($prodHarga_ori);

                /* -----------------------------------------------------------------
                 * mengunakan harga_asli supaya dlm info reguler tidak berubah-rubah
                 * -----------------------------------------------------------------*/
                $prodHarga_asli = $sesItem['harga_reguler'];
                $prodHarga_asli_ppn = $prodHarga_asli * ((100 + $ppnFactor) / 100);
                $prodHarga_ori_ppn = $sesItem['jual'] * ((100 + $ppnFactor) / 100);
                $prodHarga_ori_reseller_ppn = $sesItem['jual_reseller'] * ((100 + $ppnFactor) / 100);
                $prodHarga_ori_online_ppn = $prodHarga_ori_online * ((100 + $ppnFactor) / 100);
                $prodHarga_ori_reseller_ppn_f = format_harga($prodHarga_ori_reseller_ppn);
                $prodHarga_ori_ppn_f = format_harga($prodHarga_asli_ppn);
                // $prodHarga_ori_ppn_f = format_harga($prodHarga_ori_ppn);
                $prodHarga_ori_online_ppn_f = format_harga($prodHarga_ori_online_ppn);

                $spek_diskon = $this->calcDiskon($prodHarga_ori, array("satu" => $diskon_persen), "", "", "diskon");
                $spek_premi = $this->calcDiskon($prodHarga_ori, array("satu" => $premi_persen), "", "", "premi");
                // arrPrintHijau($spek_diskon);
                // arrPrintKuning($spek_premi);
                if ($premi_persen > 0) {
                    $premiNilai = $spek_premi["nilai"];
                    $premiNilai_f = format_harga($premiNilai);
                    $prodHarga = $spek_premi["harga_af"];

                    $faktor_x = "premi ($premi_persen_f%) <r>@$premiNilai_f</r>";
                }
                elseif ($diskon_persen > 0) {
                    $diskoniNilai = $spek_diskon["nilai"];
                    $diskoniNilai_f = format_harga($diskoniNilai);
                    $prodHarga = $spek_diskon["harga_af"];

                    $faktor_x = "diskon ($diskon_persen_f%) <r>@$diskoniNilai_f</r>";
                }
                else {
                    $prodHarga = $prodHarga_ori;

                    $faktor_x = "";
                    $diskon_simple = false;
                }
                $prodHarga_f = number_format($prodHarga);
                $prodHarga_ppn = $prodHarga * ((100 + $ppnFactor) / 100);
                $prodHarga_ppn_f = number_format($prodHarga_ppn);
                $ppn_setting = "<small style='color: yellow;'>(PPN: $ppnFactor%)</small>";

                // arrPrintHijau($sesItem);
                $strDiskon .= "<div class='box box-danger box-solid'>";
                $strDiskon .= "<div class='box-header with-border'>";
                $strDiskon .= "<h3 class='box-title text-uppercase' title='$prod_id'>$prodNama $ppn_setting</h3>";
                $strDiskon .= "</div>";
                //-------------------------------------------body--
                $strDiskon .= "<div class='box-body'>";
                $strDiskon .= "<div class='info'>";
                $hasil = "";
                if (in_array($prod_id, $diskon_produk_ids)) {
                    $diskonSpeks = $diskon_produks[$prod_id];
                    // arrPrint($diskonSpeks);
                    $ix = 1;
                    foreach ($diskonSpeks as $diskonSpek) {
                        $ix++;
                        $minim = $diskonSpek['minim'];
                        $maxim = $diskonSpek['maxim'];
                        $persen = $diskonSpek['persen'] * 1;
                        // $hargaa = $diskonSpek['harga'] * 1;

                        $harga_deler = "harga_deler_$ix" . "_$prod_id";
                        /*----mereplace harga dari db diskon dengan harga yg relatif---*/
                        $spek_diskon = $this->calcDiskon($prodHarga_ori_reseller, array("satu" => $persen), "", "", "diskon");
                        // $spek_premi = $this->calcDiskon($prodHarga, array("satu" => $premi_persen), "", "", "premi");
                        $hargaa = $spek_diskon['harga_af'];
                        $hargaa_ppn = $spek_diskon['harga_af'] * ((100 + $ppnFactor) / 100);
                        // ------------------------------------------------------------end

                        $persen_f = number_format($persen, 2);
                        $hargaa_f = number_format($hargaa);
                        $hargaa_ppn_f = number_format($hargaa_ppn);
                        $maxim_f = $maxim == 0 ? "unit keatas" : " - <r>$maxim</r> unit";
                        $var_0 = "<r>$minim</r> $maxim_f diskon $persen_f% harga <r>@$hargaa_ppn_f</r>";
                        if ($link_active == true) {
                            $var = "<label>$var_0 <input type='radio' name='harga_pilihan_$prod_id' id='$harga_deler' data-pid='$prod_id' data-qty='$jml' value='$hargaa'></label>";
                        }
                        else {
                            $var = $var_0;
                        }

                        if ($hasil == "") {
                            $hasil .= "$var";
                        }
                        else {
                            $hasil = "$hasil<br>$var";
                        }
                    }
                }

                // $strDiskon .= "Setiap pembelian<br>";
                $hrg_reguler_str = "<r>1</r> Harga list Reguler <r>@$prodHarga_ori_ppn_f</r> $faktor_x </r> unit harga <r>@$prodHarga_ppn_f</r>";
                if ($link_active == true) {
                    // $hrg_reguler = "<label><input type='radio' name='harga_pilihan_$prod_id' id='harga_reguler_$prod_id' data-pid='$prod_id' data-qty='$jml' value='$prodHarga'> $hrg_reguler_str</label>";
                    $hrg_reguler = "<label><input type='radio' name='harga_pilihan_$prod_id' id='harga_reguler_$prod_id' data-pid='$prod_id' data-qty='$jml' value='$prodHarga_asli'> $hrg_reguler_str</label>";
                }
                else {
                    $hrg_reguler = $hrg_reguler_str;
                }

                $strDiskon .= "Harga <r>tandas</r> <i>($hppnppv_f)</i> <b>$hppnppvnppn_f</b><br>";
                $strDiskon .= "$hrg_reguler<br>";
                $strDiskon .= "<hr style='margin: 2px;'>";
                /*--------reseller------------*/
                $strDiskon .= "<div class='anu bg-success' style='padding: 0 10px;text-align: right;'>";
                $var_1 = "<r>1</r> Harga list Dealer <r>@$prodHarga_ori_reseller_ppn_f</r>";

                if ($link_active == true) {
                    $strDiskon .= "<label>$var_1 <input type='radio' name='harga_pilihan_$prod_id' id='harga_deler_1_$prod_id' data-pid='$prod_id' data-qty='$jml' value='$prodHarga_ori_reseller'></label>";
                }
                else {
                    $strDiskon .= $var_1 . "<br>";
                }
                // if ($premi_persen > 0) {
                //     $strDiskon .= "$hasil</div>";
                // }
                // else {
                // }
                $strDiskon .= "$hasil";
                $strDiskon .= "</div>";
                $strDiskon .= "</div>";

                /*--------online------------*/
                $strDiskon .= "<div class='anu bg-info info' style='padding: 0 10px;margin-top: 2px;ttext-align: right;'>";
                $strOnline = "<r>1</r> Harga list Online <r>@$prodHarga_ori_online_ppn_f</r>";
                if ($link_active == true) {
                    $strDiskon .= "<label><input type='radio' name='harga_pilihan_$prod_id' id='harga_online_$prod_id' data-pid='$prod_id' data-qty='$jml' value='$prodHarga_ori_online'>$strOnline<label for='online'></label>";
                }
                else {
                    $strDiskon .= $strOnline . "<br>";
                }
                // if ($premi_persen > 0) {
                //     $strDiskon .= "$hasil</div>";
                // }
                // else {
                // }
                // $strDiskon .= "$hasil";
                $strDiskon .= "</div>";


                $strDiskon .= "</div>"; // body

                // <script>$('div .anu r').css('color','#da0');</script>
                // ------------------------------------------
                $strDiskon .= "</div>";
                //---------------------------------end-----------------------

                cekHere("$row_harga_id");
                $strDiskon .= "<script>$('#$row_harga_id').prop('checked', true);</script>";
            }

            $link_selector = MODUL_PATH . "_processSelectProduct/select/$jenisTrItems?id=";
            $strDiskon .= "<script>
                   $('.box-body .info input[type=\"radio\"]').change(function() {

                    var rowid = $(this).attr('id');
                    var productnama = $(this).attr('name');
                    var productQty = $(this).data('qty');
                    var productValue = $(this).val();
                    var productId = $(this).data('pid');
                    $('.box-body label').removeClass('bg-danger');
                    $(this).closest('label').addClass('bg-danger');

                    
                    // $(this).prop('checked', true);
                    // Perform any action with productId and productValue
                    console.log(\"jenisTr:\", $jenisTr);
                    console.log(\"rowid:\", rowid);
                    console.log(\"productnama:\", productnama);
                    console.log(\"Product Value:\", productValue);
                    console.log(\"productId:\", productId);
                
                    // Here, you can send the data to the server using AJAX or perform any other actions
                    $('#result').load('$link_selector' + productId + '&harga=' +productValue+'&rowid=' + rowid + '&newQty=' + productQty);
                    
                  });
                    
                    
                </script>";
        }
        // echo $strDiskon;
        $vars = array();
        $vars['srcs'] = $sesItems;
        $vars['html'] = $strDiskon;

        // return $strDiskon;
        return $vars;
    }

    public function cekDiskonProdukKategori($sesItems)
    {

        $this->CI->load->model("Mdls/MdlDiskonCustomer");
        $dcu = new MdlDiskonCustomer();
        $dc_params = $dcu_srcs = $dcu->callDiskonAktive();

        // arrPrint($dc_params);

        $strDiskon = "";
        $strDiskon .= "<style>
            .box{
                margin-bottom: 10px !important;
            }
            .box-info .box-header{
                padding: 3px 10px !important;    
            }
            .box-info .box-body{
                padding: 3px 10px 10px !important;    
            }
            .text-grey-2 {
                color: cornflowerblue;
            }
        </style>";
        if (sizeof($sesItems) > 0) {
            // $strDiskon .= "<h3>Info Diskon <small>Setiap pembelian*</small></h3>";
            $strDiskon .= "<h3>Info Diskon Kategori</h3>";
            if (count($dc_params) > 0) {
                foreach ($dc_params as $dc_kategori => $dc_params_2) {
                    $dc_kategori_label = str_replace("_", " ", $dc_kategori);
                    // arrPrintHijau($sesItem);
                    $strDiskon .= "<div class='box box-info box-solid'>";
                    $strDiskon .= "<div class='box-header with-border'>";
                    $strDiskon .= "<h3 class='box-title text-uppercase' title='$dc_kategori'>$dc_kategori_label</h3>";
                    $strDiskon .= "</div>";
                    //-------------------------------------------body--
                    // arrPrintHijau($dc_params_2);
                    $strDiskon .= "<div class='box-body'>";
                    $hasil = "";
                    foreach ($dc_params_2 as $urutan => $diskonSpek) {
                        $minim = $diskonSpek['minim'];
                        $maxim = $diskonSpek['maxim'];
                        $persen = $diskonSpek['persen'] * 1;
                        $nilai = $diskonSpek['nilai'] * 1;
                        // $hargaa = $diskonSpek['harga'] * 1;

                        $nilai_f = number_format($nilai);
                        $maxim_f = $maxim == 0 ? "unit keatas" : " - <r>$maxim</r> unit";

                        $var = "<r>$minim</r> $maxim_f diskon <r>$nilai_f</r>";
                        if ($hasil == "") {
                            $hasil .= "$var";
                        }
                        else {
                            $hasil = "$hasil<br>$var";
                        }
                    }

                    // $strDiskon .= "<div class='anu bg-success' style='padding-left:10px;'>";
                    $strDiskon .= "Diskon akumulatif pembelian unit:<br>";
                    $strDiskon .= "$hasil";
                    $strDiskon .= "</div>";

                    $strDiskon .= "</div>"; // body
                    // <script>$('div .anu r').css('color','#da0');</script>
                    // ------------------------------------------
                    $strDiskon .= "</div>";
                }
            }
            else {
                // $strDiskon .= "<div class='box box-info box-solid'>";
                $strDiskon .= "<span class='text-grey-2'>none</span>";
                // $strDiskon .= "</div>";
            }

            //---------------------------------end-----------------------
        }
        // echo $strDiskon;
        $vars = array();
        // $vars['srcs'] = $sesItems;
        $vars['html'] = $strDiskon;

        return $vars;
    }

    public function cekDiskonPembelian($sesItems, $cabang_id, $toko_id)
    {
        // arrPrint($sesItems);

        $pids = array_keys($sesItems);
        //        arrprint($pids);
        $src_grosiers = array();
        if (count($sesItems) > 0) {

            $diskon_produks = array();
            $this->setTokoId($toko_id);
            $src_diskon = $this->callProdukDiskonPembelian($pids);
            //        matiHere();
            $src_grosiers = $src_diskon["grosir"];
            $src_produks = $src_diskon["produk"];
            $src_produkFree = $src_diskon["freeproduk"];
        }
        // arrprintwebs($src_produkFree);
        // showLast_query("hijau");
        // arrPrint($src_grosiers);
        $diskon_produks = array();
        foreach ($src_grosiers as $src_grosier) {
            $proId = $src_grosier['produk_id'];

            $diskon_produks[$proId][] = $src_grosier;
        }
        $diskon_produk_ids = array_keys($diskon_produks);


        // $sesItems =
        $strDiskon = "";
        $strDiskon .= "<style>
            .box{
                margin-bottom: 10px !important;
            }
            .box-info .box-header, .box-success .box-header{
                padding: 3px 10px !important;    
            }
            .box-info .box-body, .box-success .box-body{
                padding: 3px 10px 10px !important;    
            }
        </style>";
        //        arrPrint($sesItems);
        if (sizeof($sesItems) > 0) {
            // $strDiskon .= "<h3>Info Diskon <small>Setiap pembelian*</small></h3>";
            $strDiskon .= "<h3>Info Promo Supplier</h3>";

            /* -------------------------------------------------------
             * produk harga
             * -------------------------------------------------------*/
//                        arrPrint($src_produkFree);
            foreach ($sesItems as $prod_id => $sesItem) {
                // cekHere($prod_id);

                $prodNama = $sesItem["nama"];
                $satuan = $sesItem["satuan"] == "n/a" ? "unit" : $sesItem["satuan"];
                /* ---------------------------------------------
                 * bagian freeproduk
                 * ---------------------------------------------*/
                // $src_produkFree[1888]["produk_rel_satuan_nama"] = "ok";
                if (isset($src_produkFree[$prod_id])) {
                    // arrPrintHijau($sesItem);
                    // arrPrint($src_produkFree[$prod_id]);
                    $supplier_nama = $src_produkFree[$prod_id]["supplier_nama"];
                    $minimum_qty = $src_produkFree[$prod_id]["qty_min"];
                    $produk_rel_nama = $src_produkFree[$prod_id]["produk_rel_nama"];
                    $produk_rel_qty = $src_produkFree[$prod_id]["produk_rel_qty"];
                    $produk_rel_harga = formatField_he_format("harga", $src_produkFree[$prod_id]["produk_rel_harga"]);
                    $produk_rel_satuan_nama = $src_produkFree[$prod_id]["produk_rel_satuan_nama"];
                    $produk_rel_satuan_nama_f = isset($produk_rel_satuan_nama) ? "($produk_rel_satuan_nama)" : "";
                    $produk_rel_promo = $src_produkFree[$prod_id]["start_date"] . " - " . $src_produkFree[$prod_id]["start_date"];
                    $strDiskon .= "<div class='box box-success box-solid'>";
                    $strDiskon .= "<div class='box-header with-border'>";
                    $strDiskon .= "<h1 class='box-title text-uppercase text-bold text-red' title='$prod_id'>($supplier_nama) </h1><br>";
                    $strDiskon .= "<h5 class='box-title text-uppercase' style='font-size: 1em!important;' title='$prod_id'>$prodNama </h5>";
                    $strDiskon .= "</div>";
                    //-------------------------------------------body--
                    $strDiskon .= "<div class='box-body'>";
                    $hasil = "";
                    // $strDiskon .= "Setiap pembelian<br>";
                    $strDiskon .= "Pembelian <r>minimum</r> $minimum_qty $satuan<br>";
                    $strDiskon .= "<r>1.</r> Bonus <r>@$produk_rel_nama</r> $produk_rel_qty $produk_rel_satuan_nama_f </r>  harga <r>@$produk_rel_harga</r><br>";
                    $strDiskon .= "<hr style='margin: 2px;'>";
                    /*--------reseller------------*/
                    //                    $strDiskon .= "<div class='anu bg-success' style='padding-left:10px;'>";
                    //                    $strDiskon .= "<r>1</r> Harga list Dealer <r>@$prodHarga_ori_reseller_f</r><br>";
                    //                    // if ($premi_persen > 0) {
                    //                    //     $strDiskon .= "$hasil</div>";
                    //                    // }
                    //                    // else {
                    //                    // }
                    //                    $strDiskon .= "$hasil";
                    //                    $strDiskon .= "</div>";

                    $strDiskon .= "</div>"; // body
                    // <script>$('div .anu r').css('color','#da0');</script>
                    // ------------------------------------------
                    $strDiskon .= "</div>";
                    //---------------------------------end-----------------------
                }

                /* ---------------------------------------------
                 * bagian diskon 123
                 * ---------------------------------------------*/
                if (isset($src_grosiers[$prod_id])) {
                    // arrPrintHijau($sesItem);
                    $spek_grosiers = $src_grosiers[$prod_id];
                    $minimum_qty = $src_produkFree[$prod_id]["qty_min"];
                    $produk_rel_nama = $src_produkFree[$prod_id]["produk_rel_nama"];
                    $produk_rel_qty = $src_produkFree[$prod_id]["produk_rel_qty"];
                    $produk_rel_harga = formatField_he_format("harga", $src_produkFree[$prod_id]["produk_rel_harga"]);
                    $produk_rel_satuan_nama = $src_produkFree[$prod_id]["produk_rel_satuan_nama"];
                    $produk_rel_promo = $src_produkFree[$prod_id]["start_date"] . " - " . $src_produkFree[$prod_id]["start_date"];
                    $strDiskon .= "<div class='box box-info box-solid'>";
                    $strDiskon .= "<div class='box-header with-border'>";
                    $strDiskon .= "<h3 class='box-title text-uppercase' title='$prod_id'>$prodNama </h3>";
                    $strDiskon .= "</div>";
                    //-------------------------------------------body--
                    $strDiskon .= "<div class='box-body'>";
                    $hasil = "";
                    foreach ($spek_grosiers as $k_diskon => $item) {
                        // $strDiskon .= "<div class='row'>";
                        $per_supplier_diskon_nama = $item->per_supplier_diskon_nama;
                        $per_supplier_diskon_nama_f = strtoupper(str_replace("_", " ", $per_supplier_diskon_nama));
                        $persen = $item->persen * 1;
                        $persen_f = number_format($persen, 2) . "%";
                        $nilai = $item->nilai * 1;
                        $nilai_f = number_format($nilai);
                        $var = "<div class='col-md-6'><b>$per_supplier_diskon_nama_f</b>: $nilai_f/$persen_f</div>";

                        if ($hasil == "") {
                            $hasil .= "$var";
                        }
                        else {
                            $hasil = "$hasil $var";
                        }

                    }

                    // $strDiskon .= "Setiap pembelian<br>";
                    // $strDiskon .= "Pembelian <r>minimum</r> $minimum_qty $satuan<br>";
                    // $strDiskon .= "<r>1</r> Bonus <r>@$produk_rel_nama</r> $produk_rel_qty($produk_rel_satuan_nama) </r>  harga <r>@$produk_rel_harga</r><br>";
                    // $strDiskon .= "<hr style='margin: 2px;'>";
                    $strDiskon .= "<div class='row'>$hasil</div>";
                    /*--------reseller------------*/
                    //                    $strDiskon .= "<div class='anu bg-success' style='padding-left:10px;'>";
                    //                    $strDiskon .= "<r>1</r> Harga list Dealer <r>@$prodHarga_ori_reseller_f</r><br>";
                    //                    // if ($premi_persen > 0) {
                    //                    //     $strDiskon .= "$hasil</div>";
                    //                    // }
                    //                    // else {
                    //                    // }
                    //                    $strDiskon .= "$hasil";
                    //                    $strDiskon .= "</div>";

                    $strDiskon .= "</div>"; // body
                    // <script>$('div .anu r').css('color','#da0');</script>
                    // ------------------------------------------
                    $strDiskon .= "</div>";
                    //---------------------------------end-----------------------
                }

            }
        }
        //         echo $strDiskon;
        //        matiHere(__LINE__);
        $vars = array();
        $vars['srcs'] = $sesItems;
        $vars['html'] = $strDiskon;

        //        arrprint($vars["html"]);
        // return $strDiskon;
        return $vars;
        //        cekHitam($this->CI->db->last_query());
        //echo"**INI**";
    }

    public function cekDiskonPembelianSaldo($sesItems, $cabang_id, $toko_id)
    {
        // arrPrint($sesItems);
        $pids = $sesItems['pihakID'];
        $supplierName = $sesItems['supplierName'];
        // arrPrintPink($pids);

        $src_grosiers = array();
        if (count($sesItems) > 0) {

            $diskon_produks = array();
            $this->setTokoId($toko_id);
            $src_diskon = $this->callProdukDiskonPembelianSaldo($pids);
            // matiHere(__LINE__);
            // arrPrint($src_diskon);
            $src_grosiers = $src_diskon[0]->debet;
            $extern_nama = $src_diskon[0]->extern_nama;
        }

        // showLast_query("hijau");
        // arrPrint($src_grosiers);

        $strDiskon = "";
        $strDiskon .= "<style>
            .box{
                margin-bottom: 10px !important;
            }
            .box-info .box-header, .box-success .box-header{
                padding: 3px 10px !important;    
            }
            .box-info .box-body, .box-success .box-body{
                padding: 3px 10px 10px !important;    
            }
        </style>";
        // arrPrint($sesItems);
        if (sizeof($sesItems) > 0 && isset($sesItems['pihakID'])) {
            // $strDiskon .= "<h3>Info Diskon <small>Setiap pembelian*</small></h3>";
            $strDiskon .= "<h3>Info saldo klaim ke supplier</h3>";

            $produk_rel_harga = formatField_he_format("harga", $src_grosiers);

            $strDiskon .= "<div class='box box-info box-solid'>";
            $strDiskon .= "<div class='box-header with-border'>";
            $strDiskon .= "<h3 class='box-title text-uppercase' title='$pids'>$supplierName</h3>";
            $strDiskon .= "</div>";
            //-------------------------------------------body--
            $strDiskon .= "<div class='box-body'>";
            $link = base_url() . "Ledger/viewMoveDetails/RekeningPembantuPiutangSupplierMain/1010020030/$pids/?o=-1&main_ext2_id=32&blob_ext=czoxMToiIFBULiBTRURBWVUiOw==";
            $produk_rel_harga_l = "<a href='$link' target='_blank'>$produk_rel_harga</a>";

            $var = "<div class='col-md-12'><h2 class='no-margin'>$produk_rel_harga_l</h2></div>";

            $strDiskon .= "<div class='row'>$var</div>";

            $strDiskon .= "</div>"; // body
            // <script>$('div .anu r').css('color','#da0');</script>
            // ------------------------------------------
            $strDiskon .= "</div>";
            //---------------------------------end-----------------------
        }
        //         echo $strDiskon;
        //        matiHere(__LINE__);
        $vars = array();
        $vars['srcs'] = $sesItems;
        $vars['html'] = $strDiskon;

        //        arrprint($vars["html"]);
        // return $strDiskon;
        return $vars;
        //        cekHitam($this->CI->db->last_query());
        //echo"**INI**";
    }

    public function selectorDiskon($produk_id, $produk_harga, $produk_jml, $produk_speks = array(), $ses_mains = array())
    {
        // $this->setTokoId(my_toko_id());
        $src_diskon_00 = $src_diskon = $this->CallProdukDiskon($produk_id);

        /* ---------------------------------------------------------
         * untuk mendeteksi ada tidaknya diskon unit
         * jika ada akan mengambil diskon dari setingan grosir level pertama [0]
         * ---------------------------------------------------------*/
        if (isset($ses_mains['diskon_kategori_unit']) && ($ses_mains['diskon_kategori_unit'] > 0)) {
            // arrPrintHijau($src_diskon['grosir']['0']);
            $src_diskon = array();
            foreach ($src_diskon_00 as $key => $ddatas_0) {

                if ($key == 'grosir') {
                    $mod_datas = $ddatas_0[0];
                    $mod_datas['minim'] = 1;
                    $mod_datas['maxim'] = 0;
                    $ddatas[0] = $mod_datas;
                }
                $src_diskon[$key] = $ddatas;
            }
        }
        //-----end diskon unit-------------------------------------------

        // arrPrintKuning($src_diskon);
        // diskon
        $pro_harga = $produk_harga * 1;
        $pro_jml = $produk_jml;
        // cekPink("$pro_harga || $pro_jml");
        /*---menentukan diskon pokok dari produk atau grosir----*/
        $pihak_kategori = $ses_mains['kategoriNama'];

        $d_pokok = isset($src_diskon['produk']) ? $src_diskon['produk'] : 0;

        if ($pihak_kategori == "distributor") {
            $d_pokok = 0;
        }

        $jml_spek_grosir = sizeof($src_diskon['grosir']);
        if ($jml_spek_grosir > 0) {
            $gro_count = 0;
            foreach ($src_diskon['grosir'] as $item) {
                $gro_count++;
                $gro_minim = $item['minim'];
                $gro_maxim = $gro_count == $jml_spek_grosir ? INF : $item['maxim'];
                $gro_persen = $item['persen'];
                // cekPink2("$pro_jml >= $gro_minim) && ($pro_jml <= $gro_maxim)");

                if (($pro_jml >= $gro_minim) && ($pro_jml <= $gro_maxim)) {
                    $d_pokok = $gro_persen;
                    // cekBiru("---- $d_pokok");
                    break;
                }
            }
        }
        else {
            // $d_pokok = isset($src_diskon['produk']) ? $src_diskon['produk'] : 0;
        }

        $diskon_pokok["produk"] = $d_pokok;
        $diskon_event = array();

        $calc_hasil_grosir = $this->calcDiskon($pro_harga, $diskon_pokok, $diskon_event, "diskon");

        if (count($produk_speks) > 0) {
            $pro_diskon = $produk_speks->diskon_persen;
            // $pro_premi = $produk_speks->premi_jual;
            $diskon_pokok["produk"] = $pro_diskon;

            $calc_hasil_simple = $this->calcDiskon($pro_harga, $diskon_pokok, $diskon_event, "diskon");
        }

        $calc_hasil = array();
        $calc_hasil["grosir"] = $calc_hasil_grosir;
        $calc_hasil["simple"] = $calc_hasil_simple;

        return $calc_hasil;
    }

    public function selectorDiskonKategori($sessioncCode)
    {
        //        cekHitam(__LINE__);
        $this->CI->load->model("Mdls/MdlDiskonCustomer");
        $dcu = new MdlDiskonCustomer();
        $dc_params = $dcu_srcs = $dcu->callDiskonAktive();
        // arrPrint($row);
        // arrPrint($dcu_srcs);
        //         arrPrintWebs($dc_params);
        /* ------------------------------------------
         * nyocokin dr item ada yg masuk setting diskon atau tidak
         * ------------------------------------------*/
        $sess_item_kategories = isset($sessioncCode['items_kategori']) ? $sessioncCode['items_kategori'] : array();
        $potongan_nilai = array();
        foreach ($dc_params as $dc_jenis_0 => $dc_param) {
            cekBiru("$dc_jenis_0:: " . $dc_jenis_0);
            /*-------kalau ada diskonnya dihitung----------*/
            if ((count($sess_item_kategories) > 0) && array_key_exists($dc_jenis_0, $sess_item_kategories)) {
                $jml_kategori = $sess_item_kategories[$dc_jenis_0]['jml'];

                $jml_dcu = count($dc_param);
                $dcu_count = 0;
                foreach ($dc_param as $x => $item) {
                    $dcu_count++;
                    // arrPrintKuning($item);
                    $minim = $item["minim"];
                    $maxim = $jml_dcu == $dcu_count ? INF : $item["maxim"];
                    $nilai = $item["nilai"];

                    cekOrange(" //// $minim <= $jml_kategori <= $maxim /////");
                    if ($jml_kategori >= $minim && $jml_kategori <= $maxim) {
                        cekHijau("nilai:: $nilai");
                        $potongan_nilai[$dc_jenis_0]['nilai'] = $nilai;
                        $potongan_nilai[$dc_jenis_0]['jml'] = $jml_kategori;

                        break;
                    }
                    else {
                        $potongan_nilai[$dc_jenis_0]['nilai'] = 0;
                        $potongan_nilai[$dc_jenis_0]['jml'] = $jml_kategori;
                    }
                }
            }
            else {
                cekKuning("tidak ada diskon " . __LINE__);
            }

        }

        return $potongan_nilai;
    }

    public function diskonCadanganTarifSupplier($supplierID)
    {
        $this->CI->load->model("Mdls/MdlDiskonCadanganSupplier");
        $dcu = new MdlDiskonCadanganSupplier();
        $dcu->addFilter("supplier_id='$supplierID'");
        $allData = $dcu->lookUpAll()->result();
//        $returnData= array();
        if (count($allData) > 0) {
            foreach ($allData as $allData_0) {
                $returnData["tarif"] = $allData_0->persen * 1;
            }
        }
        else {
            $returnData["tarif"] = 0;
        }

        return $returnData;


    }

    public function callAllDiskonCadanganSupplier()
    {
        $toko_id = isset($this->toko_id) ? $this->toko_id : matiHere("toko_id harus diset @" . __FUNCTION__);

        /*------------ diskon free produk ------------*/
        $this->CI->load->model("Mdls/MdlDiskonCadanganSupplier");
        $cd = new MdlDiskonCadanganSupplier();


        $filters = $cd->getCiFilters();
        $cd->setFilters(array());

        $condites = array(
//            "toko_id" => $toko_id,
//            "jenis" => "free_produk",
            "trash" => "0",
        );

        $this->CI->db->where($condites);

        //        $this->CI->db->where("expired=".strtotime(date("Y-m-d H:i:s")));
        //        $this->CI->db->where("expired>".strtotime(date("Y-m-d H:i:s")));

//        $this->CI->db->order_by("expired", "asc");
        $src_cds_0 = $cd->lookupAll()->result_array();
        $src_cds = sizeof($src_cds_0) > 0 ? $src_cds_0 : array();

        $datas['customer_level_diskon'] = $src_cds;
//        $datas['diskon_free_produk'] = $src_cds;

        return $datas;
    }
}