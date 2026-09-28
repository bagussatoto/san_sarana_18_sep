<?php

/**
 * Created by JetBrains PhpStorm.
 * User: azes
 * Date: 5/9/12
 * Time: 11:56 AM
 * To change this template use File | Settings | File Templates.
 */

class Transaksional
{
    protected $toko_id;

    public function getTokoId()
    {
        return $this->toko_id;
    }

    public function setTokoId($toko_id)
    {
        $this->toko_id = $toko_id;
    }

    public function __construct()
    {
        // parent::__construct();
        $this->CI =& get_instance();

    }

    public function gerbang_transaksi($toko_id, $transaksi_jenis, $array_data)
    {
        $CI =& get_instance();
        $CI->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $array_datas = blobDecode($array_data);

        cekBiru("$toko_id ***** $transaksi_jenis");
        arrPrintPink($array_datas);
        // $items = $array_datas['items'];

        $arrItems = isset($array_datas['items']) ? $array_datas['items'] : array();
        // id_produk => qty

        $arrTrID = isset($array_datas['trs']) ? $array_datas['trs'] : array();

        $arrMain = isset($array_datas['main']) ? $array_datas['main'] : array();

        $cCode = "_TR_" . $transaksi_jenis;
        // $toko_id = my_toko_id();

        // matiHere(__LINE__);

        $selectorModel = $CI->config->item('heTransaksi_ui')[$transaksi_jenis]['selectorModel'];
        $selectorSrcModel = $CI->config->item('heTransaksi_ui')[$transaksi_jenis]['selectorSrcModel'];

        $CI->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        $itemNumLabels = isset($CI->config->item('heTransaksi_ui')[$transaksi_jenis]['shoppingCartNumFields'][1]) ? $CI->config->item('heTransaksi_ui')[$transaksi_jenis]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($CI->config->item('heTransaksi_ui')[$transaksi_jenis]['selectedPrice']) ? $CI->config->item('heTransaksi_ui')[$transaksi_jenis]['selectedPrice'] : array();
        $lockerConfig = isset($CI->config->item('heTransaksi_ui')[$transaksi_jenis]['lockerCheck']) ? $CI->config->item('heTransaksi_ui')[$transaksi_jenis]['lockerCheck'] : array();
        $subAmountConfig = isset($CI->config->item('heTransaksi_ui')[$transaksi_jenis]['shoppingCartAmountValue'][1]) ? $CI->config->item('heTransaksi_ui')[$transaksi_jenis]['shoppingCartAmountValue'][1] : null;

        if (sizeof($arrItems) > 0) {
            arrPrintWebs($arrItems);
            foreach ($arrItems as $id => $jmlParam) {

                $tmpB = $b->lookupByID($id)->result();
//                cekHere($CI->db->last_query());
                arrPrint($tmpB);

                $jml = $jmlParam;
                if (sizeof($tmpB) > 0) {
                    foreach ($tmpB as $row) {
                        $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                        $tmpJml = $jmlParam;
                        if (isset($lockerConfig['enabled']) && $lockerConfig['enabled'] == true) {
//                            cekMerah("masuk locker config");

                            $mdlName = $lockerConfig['mdlName'];
                            $this->load->model("Mdls/" . $mdlName);
                            $c = new $mdlName();
                            $c->addFilter("produk_id='$id'");
                            $c->addFilter("state='active'");
                            $c->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                            $c->addFilter("gudang_id=" . $this->session->login['gudang_id']);
                            $tmpC = $c->lookupAll($id)->result();
//                            cekHere($this->db->last_query() . " " . __LINE__);


                            if (sizeof($tmpC) > 0) {
                                arrPrint($tmpC);
                                foreach ($tmpC as $row) {
                                    $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                                    $nama = $row->nama;

                                    $jml_now = $row->jumlah;
                                    if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                                        $jml_sudah_diambil = 0;
                                        $jml_diperlukan = 1;
                                        $jml_nambah = 1;
                                    }
                                    else {
                                        if (isset($_GET['newQty'])) {
                                            $jml_sudah_diambil = $_SESSION[$cCode]['items'][$id]['jml'];
                                            $jml_diperlukan = $_GET['newQty'];
                                            $jml_nambah = $jml_diperlukan - $jml_sudah_diambil;
                                        }
                                        else {
                                            $jml_sudah_diambil = $_SESSION[$cCode]['items'][$id]['jml'];
                                            $jml_diperlukan = $jml_sudah_diambil + $jml;
                                            $jml_nambah = $jml;
                                        }
                                    }
                                    //  region validasi stok
                                    if ($jml_nambah > $jml_now) {
                                        echo "<script>top.alert('stok $nama tidak cukup. (perlu $jml_diperlukan, nambah $jml_nambah stok $jml_now)')";
                                        echo "</script>";
                                        die();
                                    }
                                    //  endregion validasi stok


                                    $this->db->trans_start();

                                    //  region update locker active
                                    $where = array(
                                        "id" => $row->id,
                                    );
                                    $data_active = array(
                                        "jumlah" => $jml_now - $jml_nambah,
                                        "state" => "active",
                                    );
                                    $c->updateData($where, $data_active);
//                                    cekHere($this->db->last_query());
                                    //  endregion update locker active


                                    //  region locker hold
                                    $array_hold_sebelumnya = $c->cekLoker($this->session->login['cabang_id'], $id, "hold", $this->session->login['id'], "0", $this->session->login['gudang_id']);
                                    if (sizeof($array_hold_sebelumnya) > 0) {
                                        $where = array(
                                            "id" => $array_hold_sebelumnya['id'],
                                        );
                                        $data_hold = array(
                                            "jumlah" => $array_hold_sebelumnya['jumlah'] + $jml_nambah,
                                        );
                                        $c->updateData($where, $data_hold);
                                        cekHere($this->db->last_query());
                                    }
                                    else {
                                        $data_hold = array(
                                            "jenis" => "produk",
                                            "cabang_id" => $this->session->login['cabang_id'],
                                            "produk_id" => $id,
                                            "nama" => $nama,
                                            "satuan" => $row->satuan,
                                            "state" => "hold",
                                            "jumlah" => $jml_nambah,
                                            "oleh_id" => $this->session->login['id'],
                                            "oleh_nama" => $this->session->login['nama'],
                                            "gudang_id" => $this->session->login['gudang_id'],
                                        );
                                        $c->addData($data_hold);
                                        cekHere($this->db->last_query());
                                    }
                                    //  endregion locker hold


                                    $this->db->trans_complete() or die("Gagal bro");

                                    $tmpJml = $jml_diperlukan;

                                }
                            }
                            else {
                                mati_disini("tidak ditemukan item " . $row->nama . " di locker stock.");
                            }

                        }

                        /* ----------------------------------------------------------------------------------------------
                         * memasukan session items
                         * ----------------------------------------------------------------------------------------------*/
                        $fieldSrcs = isset($CI->config->item("heTransaksi_ui")[$CI->jenisTr]['shoppingCartFieldSrc']) ? $CI->config->item("heTransaksi_ui")[$transaksi_jenis]['shoppingCartFieldSrc'] : array("nama" => "nama");
                        if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                            $tmp = array(
                                "handler" => $CI->uri->segment(1) . "/" . $CI->uri->segment(2),
                                "id" => $id,
                                "jml" => $tmpJml,
                                "harga" => 0,
                                "subtotal" => 0,
                            );

                            if (sizeof($priceConfig) > 0) {
                                $mdlName = $priceConfig['model'];
                                $CI->load->model("Mdls/" . $mdlName);
                                $h = new $mdlName();
                                $h->addFilter("produk_id='$id'");
                                $h->addFilter("status='1'");
                                //                                $h->addFilter("jenis_value='" . $priceConfig['label'] . "'");
                                $h->addFilter("jenis_value in ('" . implode("','", $priceConfig['label']) . "')");
                                $h->addFilter("toko_id=" . $toko_id);
                                $tmpH = $h->lookupAll($id)->result();
//                                cekMerah($CI->db->last_query());

                                if (sizeof($tmpH) > 0) {
                                    $rawPrices = array();
                                    foreach ($tmpH as $hSpec) {
                                        foreach ($priceConfig['key_label'] as $key => $val) {
                                            if ($key == $hSpec->jenis_value) {
                                                $rawPrices[$key] = isset($hSpec->nilai) ? $hSpec->nilai : 0;
                                            }
                                        }
                                    }
                                    $prices = normalizePrices("produk", $rawPrices);
                                    if (sizeof($prices) > 0) {
                                        foreach ($prices as $k => $v) {
                                            $tmp[$k] = $v;
                                        }
                                        $tmp['harga'] = isset($tmp[$priceConfig['mainSrc']]) ? $tmp[$priceConfig['mainSrc']] : 0;
                                    }
                                }

                            }

                            foreach ($fieldSrcs as $key => $src) {
                                $tmpEx = $cal->multiExplode($src);
                                arrPrint($tmpEx);
                                if (sizeof($tmpEx) > 1) {//===berarti mengandung karakter simbol perhitungan
                                    cekBiru("$key perhitungan");
                                    $newSrc = $src;
                                    foreach ($tmpEx as $key2 => $val2) {
                                        echo "$key2 - $val2 <br>";
                                        if (!is_numeric($val2)) {
                                            if (isset($tmp[$val2]) && $tmp[$val2] > 0) {
                                                $newSrc = str_replace($val2, $tmp[$val2], $newSrc);
                                            }
                                            else {
                                                $newSrc = str_replace($val2, 0, $newSrc);
                                            }
                                        }

                                    }
                                    cekBiru("$$src -> $newSrc -> " . $cal->calculate($newSrc));
                                    $tmp[$key] = $cal->calculate($newSrc);
                                }
                                else {
                                    cekBiru("$key BUKAN perhitungan");
                                    $tmp[$key] = $row->$src;
                                }


                            }

                            //===perhitungan subtotal
                            $cal = new FieldCalculator();


                            if (sizeof($arrMain) > 0) {
                                foreach ($arrMain as $key => $val) {
                                    $_SESSION[$cCode][$key] = $val;
                                }
                            }

                            if ($subAmountConfig != null) {
                                $tmpEx = $cal->multiExplode($subAmountConfig);
                                if (sizeof($tmpEx) > 1) {
                                    $newSrc = $subAmountConfig;
                                    foreach ($tmpEx as $key2 => $val2) {
                                        if (isset($tmp[$val2])) {
                                            $newSrc = str_replace($val2, $tmp[$val2], $newSrc);
                                            cekKuning("$val2 direplace dengan " . $tmp[$val2]);
                                        }
                                        else {
                                            $newSrc = str_replace($val2, "0", $newSrc);
                                            cekKuning("$val2 direplace dengan NOL");
                                        }

                                    }
                                    $subtotal = $cal->calculate($newSrc);
                                    cekHijau("subtotal dari perhitungan $subAmountConfig $newSrc");

                                }
                                else {
                                    $subtotal = 0;
                                    cekHijau("subtotal dari perhitungan yang gak ada");
                                }
                            }
                            else {
                                $subtotal = 0;
                                cekHijau("subtotal NOL");
                            }
                            $tmp["subtotal"] = $subtotal;
                            $_SESSION[$cCode]['items'][$id] = $tmp;

                            //                    die();
                        }
                        else {
                            if (isset($_GET['newQty'])) {
                                $_SESSION[$cCode]['items'][$id]['jml'] = $_GET['newQty'];
                                $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
                            }
                            else {
                                $_SESSION[$cCode]['items'][$id]['jml'] += $jml;
                                $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
                            }

                            if (sizeof($itemNumLabels) > 0) {
                                echo("iterating subNums.. @" . __LINE__);
                                foreach ($itemNumLabels as $key => $label) {
                                    if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                        $newValue = $_GET[$key];
                                        $tmp[$key] = $newValue;
                                        $_SESSION[$cCode]['items'][$id][$key] = $newValue;
                                        echo "replacing value for $key with " . $newValue . "<br>";
                                    }

                                }

                                foreach ($itemNumLabels as $key => $label) {
                                    $_SESSION[$cCode]['items'][$id]["sub_" . $key] = ($_SESSION[$cCode]['items'][$id][$key] * $_SESSION[$cCode]['items'][$id]["jml"]);
                                }
                                $_SESSION[$cCode]['items'][$id]['sub_nett'] = ($_SESSION[$cCode]['items'][$id]['nett'] * $_SESSION[$cCode]['items'][$id]['jml']);

                                $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
                            }


                        }
                    }

                    if (sizeof($_SESSION[$cCode]['items']) > 0) {
                        $_SESSION[$cCode]['main']['harga'] = 0;
                        $_SESSION[$cCode]['out_master']['harga'] = 0;

                        /*
                         * akumulasi item ke main
                         * */
                        foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                            $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
                            $_SESSION[$cCode]['out_master']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
                        }
                    }

                }
                else {
                    cekMerah("tidak ada itemnya!");
                    die();
                }

            }
        }

        if (sizeof($arrTrID) > 0) {
            $_SESSION[$cCode]['main']['references'] = $arrTrID;
            $_SESSION[$cCode]['out_master']['references'] = $arrTrID;
        }
        if (isset($_GET['singleRefID']) && strlen($_GET['singleRefID']) > 0) {
            $_SESSION[$cCode]['main']['singleReference'] = $_GET['singleRefID'];
            $_SESSION[$cCode]['out_master']['singleReference'] = $_GET['singleRefID'];
        }

    }

    public function wizard_startup()
    {
        /* ------------------------------------------------
        *  companu profile cek
        * ------------------------------------------------*/
        $this->CI->load->model("Mdls/MdlCompany");
        $cp = new MdlCompany();
        $cp->setTokoId(my_toko_id());

        $cpSrc = $cp->callDatas();
        $neracaStatus = $cpSrc->neraca_ok;

        $cp_koloms = array(
//            "supplies_ok"  => array(
//                "label" => "bahan",
//                "link"  => "Converter/index/formSupplies"
//            ),
//            "produk_ok"    => array(
//                "label" => "produk",
//                "link"  => "Converter/index/formProduk"
//            ),
//            "komposisi_ok" => array(
//                "label" => "komposisi",
//                "link"  => "Converter/index/formProdukKomposisi",
//            ),
//            "stok_ok"      => array(
//                "label" => "persediaan",
//                "link"  => "Converter/index/formSuppliesRek",
//            ),
            "neraca_ok" => array(
                "label" => "neraca",
                "icon" => "fa-balance-scale",
                "link" => "TransaksiPindahBuku/index",
            ),
        );

        $link_now = "";
        $vas_ok = array();
        $strFree = "";
        $nom = 0;
        $next_on = 1;
        foreach ($cp_koloms as $cp_kolom => $cp_datum) {
            $nom++;
            $ok = $cpSrc->$cp_kolom;
            $badge_done = $ok == 1 ? "badge-green" : "";
            $var_ok[$cp_kolom] = $ok;

            $link_data = isset($cp_datum['link']) ? base_url() . $cp_datum['link'] : "#";

            if ($nom == 1 && $ok == 1) {
                $next_ok = "text-red";
            }
            else {
                $next_ok = "text-red";
            }

            $next_ok = ($nom + $ok) == ($next_on + 1) ? "" : "text-red";
            // if(($nom + $ok) == ($next_on + 1)){
            if ($ok == 0 && (($nom + $ok) == ($next_on + 1))) {
                $text_color = "text-grey";
                $link_data_f = "#";
            }
            else {
                $text_color = "";
                $link_now = $link_data_f = $link_data;
            }

            $next_on = $nom + $ok;

            // $text_color = $ok == 0 ? "text-grey" : "";

            $cp_label = $cp_datum['label'];
            $cp_icon = isset($cp_datum['icon']) ? $cp_datum['icon'] : 'fa-database';
            $strFree .= "<a href='$link_data_f' title='go to $cp_label' data-toggle='tooltip' class='btn btn-app text-uppercase active $next_ok $text_color'><span class='badge $badge_done'>$nom</span><i class='fa $cp_icon'></i>$cp_label</a>";
        }

        $vars['html'] = $strFree;
        $vars['link_now'] = $link_now;
        $vars['step_status'] = $var_ok;

        return $vars;

    }

    public function undoneItem()
    {
        $toko_id = isset($this->toko_id) ? $this->toko_id : matiHere("toko_id harap diset");

        $this->CI->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $registryFields = $tr->getRegistryFields();


    }

    public function callJmlTransakional($TAHUN)
    {
        $code_aliasing = arrCodeAliasing(-1);
        $this->CI->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $condites = array(
            "year(dtime)" => $TAHUN,
            "link_id" => 0,
        );
        $src_0 = $tr->lookupByCondition($condites)->result();
        // showLast_query("kuning");
        /* -----------------------------------------------------------
         * raw data transaksi
         * -----------------------------------------------------------*/
        foreach ($src_0 as $item) {
            $bln = formatTanggal($item->dtime, 'm');
            $thn = formatTanggal($item->dtime, 'Y');
            $thn_bln = "$thn-$bln";

            $jenis[$thn_bln][$item->jenis][] = $item->id;
            $jenis_ytd[$item->jenis][] = $item->id;
        }
        // cekKuning(sizeof($jenis['2022-08']['582']));
        /* -----------------------------------------------------------
         * jml data per bulan
         * -----------------------------------------------------------*/
        $jml_bulan = (sizeof($jenis));
        foreach ($jenis as $tahun => $jenis_data) {
            foreach ($jenis_data as $jenisTr => $jenis_datum) {
                $avg_harian = sizeof($jenis_datum) / formatTanggal($tahun, 'y');
                $bulanan[$tahun][$jenisTr]['total'] = sizeof($jenis_datum);
                $bulanan[$tahun][$jenisTr]['avg_harian'] = $avg_harian;

                if (!isset($sum_avg_bulanan[$jenisTr]['avg_harian'])) {
                    $sum_avg_bulanan[$jenisTr]['avg_harian'] = 0;
                }
                $sum_avg_bulanan[$jenisTr]['avg_harian'] += $avg_harian;
            }
        }
        /* -----------------------------------------------------------
         * jml data YTD
         * -----------------------------------------------------------*/
        foreach ($jenis_ytd as $jenisTr => $item) {
            $ytd[$jenisTr]['total'] = sizeof($item);
            $ytd[$jenisTr]['avg_harian'] = $sum_avg_bulanan[$jenisTr]['avg_harian'] / $jml_bulan;
        }

        $jml_transaksi['total'] = sizeof($src_0);
        /* -----------------------------------------------------------
         * omset YTD
         * -----------------------------------------------------------*/
        $koloms = array(
            "DATE_FORMAT(dtime,'%Y-%m') as 'thn_bln'",
            "sum(debet) as sum_debet",
            "sum(kredit) as sum_kredit",
        );
        $condites = array(
            "year(dtime)" => $TAHUN,
            "cabang_id>" => 0,
            "transaksi_id>" => 0,
        );

        $this->CI->db->select($koloms);
        $this->CI->db->where($condites);
        // $this->CI->db->group_by("month(dtime),year(dtime)");
        $this->CI->db->group_by("thn_bln");
        $tableName = "__rek_master__penjualan";
        $juals = $this->CI->db->get($tableName)->result_array();
        // showLast_query("kuning");
        $omset_bulanan = array();
        foreach ($juals as $jual_data) {
            $omset_bulanan[$jual_data['thn_bln']] = $jual_data['sum_kredit'] * 1;

            if (!isset($jual["sum_debet_ytd"])) {
                $jual["sum_debet_ytd"] = 0;
            }
            $jual["sum_debet_ytd"] += $jual_data['sum_debet'];

            if (!isset($jual["sum_kredit_ytd"])) {
                $jual["sum_kredit_ytd"] = 0;
            }
            $jual["sum_kredit_ytd"] += $jual_data['sum_kredit'];
        }

        // arrPrint($jual);
        $omset_ytd = ($jual["sum_kredit_ytd"] * 1) - ($jual["sum_debet_ytd"] * 1);

        /* -----------------------------------------------------------
         * return YTD
         * -----------------------------------------------------------*/
        $this->CI->db->select($koloms);
        $this->CI->db->where($condites);
        $this->CI->db->group_by("thn_bln");
        $tableName = "__rek_master__return_penjualan";
        $returns = $this->CI->db->get($tableName)->result_array();
        // showLast_query("here");
        $return = array();
        $return_bulanan = array();
        foreach ($returns as $return_data) {
            $return_bulanan[$return_data['thn_bln']] = $return_data['sum_debet'] * 1;

            if (!isset($return["sum_debet_ytd"])) {
                $return["sum_debet_ytd"] = 0;
            }
            $return["sum_debet_ytd"] += $return_data['sum_debet'];
        }
        $return_ytd = isset($return["sum_debet_ytd"]) ? $return["sum_debet_ytd"] * 1 : 0;
        // $jual_netto = $omset_ytd - $return_ytd;

        // cekHijau($jual_netto);
        // arrPrintHijau($code_aliasing);
        $datas = array();
        $datas = array(
            "trcode" => $code_aliasing,
            "omset_bulanan" => $omset_bulanan,
            "omset_ytd" => $omset_ytd,
            "return_ytd" => $return_ytd,
            "return_bulanan" => $return_bulanan,
            // "penjualan_netto_ytd" => $jual_netto,
            "jml_transaksi" => $jml_transaksi,
            "jml_jenistr_ytd" => $ytd,
            "jml_jenistr_bulanan" => $bulanan,
        );
        // arrPrintHijau($jenis);
        return $datas;
    }

    public function callTransaksiBeforeOpname()
    {
        $this->CI->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();

        $jenis_gantung = $tr->callGantunganTransaksi(true);

        $jml = sizeof($jenis_gantung);

        $var = array();
        $var["jml"] = $jml;
        $var["datas"] = $jenis_gantung;
        $var["link"] = "opname/Opname/cekTransaksiGantung";

        return $var;
    }

    public function cekOpnameAktive($cabang_id)
    {
        $this->CI->load->model("Mdls/MdlDashboardOpname");
        $do = new MdlDashboardOpname();

        $do->setCabangId($cabang_id);
        $src_do = $do->cekOpnameAktive();

        $var = array();
        $var["jml"] = sizeof($src_do);
        $var["data"] = $src_do;
        // $var["link"] = "opname/Opname/cekTransaksiGantung";

        return $var;
    }

    //------------------------------------
    public function cekSalesOrder($shortRequestFields2Config, $items = array(), $main = array(), $linkSelect = "")
    {
//        arrPrintWebs($items);
        $historyFields = $shortRequestFields2Config["fields"];
        $historyFieldsDetail = $shortRequestFields2Config["fieldsDetail"];
//        $linkSelect = $shortRequestFields2Config["linkSelect"];
//        cekHere("$linkSelect");

        $this->CI->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tmpHist = array();
        $link_swap = "";
        $tr->addFilter("transaksi.div_id='" . $this->CI->session->login['div_id'] . "'");
        $tr->addFilter("transaksi_data.valid_qty>0");
        if (sizeof($items) > 0) {
            $itemsKey = array_keys($items);
            $tr->addFilter("transaksi_data.produk_id in ('" . implode("','", $itemsKey) . "')");
        }
        if (isset($shortRequestFields2Config["filter"]) && (sizeof($shortRequestFields2Config["filter"] > 0))) {
            foreach ($shortRequestFields2Config["filter"] as $ff) {
                $tr->addFilter($ff);
            }
        }
        $tmpHistJoined = $tr->lookupJoined_OLD()->result();
//        showLast_query("lime");
        $tmpHist = $tr->lookupRecentUndoneEntries_joined(array())->result();
//        showLast_query("lime");
//        cekHijau(sizeof($tmpHist));
        $strOnprog = "";
        if (sizeof($tmpHist) > 0) {
            if (sizeof($tmpHistJoined) > 0) {
                $arrJoinedData = array();
                $arrJoinedDataSimple = array();
                $arrJoinedHasil = array();
                foreach ($tmpHistJoined as $joinedSpec) {
//                    arrPrintHijau($joinedSpec);
                    $arrJoinedData[$joinedSpec->transaksi_id][] = $joinedSpec;
                    $arrJoinedDataSimple[$joinedSpec->transaksi_id][$joinedSpec->produk_id] = array(
                        "produk_id" => $joinedSpec->produk_id,
                        "produk_nama" => $joinedSpec->produk_nama,
                        "produk_jml" => $joinedSpec->produk_ord_jml,
//                        "valid_qty" => $joinedSpec->valid_qty,
                        "reference_id_top" => $joinedSpec->id_top,
                        "reference_nomer_top" => $joinedSpec->nomer_top,
                        "reference_id" => $joinedSpec->transaksi_id,
                        "reference_nomer" => $joinedSpec->nomer,
                        "reference_customers_id" => $joinedSpec->customers_id,
                        "reference_customers_nama" => $joinedSpec->customers_nama,
                        "reference_cabang_id" => $joinedSpec->cabang_id,
                        "reference_cabang_nama" => $joinedSpec->cabang_nama,
                        "reference_gudang_id" => $joinedSpec->gudang_id,
                        "reference_gudang_nama" => $joinedSpec->gudang_nama,
                        "reference_salesman_id" => $joinedSpec->salesman_id,
                        "reference_salesman_nama" => $joinedSpec->salesman_nama,
                        "reference_gudang_status_id" => $joinedSpec->gudang_status_id,
                        "reference_gudang_status_nama" => $joinedSpec->gudang_status_nama,
                        "reference_gudang_status_jenis" => $joinedSpec->gudang_status_jenis,
                        "gudang_status_id" => $joinedSpec->gudang_status_id,
                        "gudang_status_nama" => $joinedSpec->gudang_status_nama,
                        "gudang_status_jenis" => $joinedSpec->gudang_status_jenis,
                    );
                }
                foreach ($arrJoinedData as $trID => $joinedSpec) {
                    $strJoined = "<div class='table-responsive '>";
                    $strJoined .= "<table id='arrayOnProgress_step' class='table datatables stripe compact nowarp order-column table-condensed table-bordered no-padding' 
                        style='border:solid red 0px;margin:0px;'>";
                    $strJoined .= "<thead>";
                    $strJoined .= "<tr class='text-uppercase' line=" . __LINE__ . ">";
                    if (sizeof($historyFieldsDetail) > 0) {
                        $strJoined .= "<th class=''>No.</th>";
                        foreach ($historyFieldsDetail as $key => $label) {
                            $strJoined .= "<th class=''>";
                            if (is_array($label)) {
                                $strJoined .= isset($label['label']) ? $label['label'] : "-";
                            }
                            else {
                                $strJoined .= $label;
                            }
                            $strJoined .= "</th>";
                        }
                    }
                    $strJoined .= "</tr>";
                    $strJoined .= "</thead>";

                    $strJoined .= "<tbody>";
                    $no = 0;
                    foreach ($joinedSpec as $iii => $val) {
                        $no++;
                        $strJoined .= "<tr line=" . __LINE__ . ">";
                        $strJoined .= "<td>$no</td>";
                        if (sizeof($historyFieldsDetail) > 0) {
                            foreach ($historyFieldsDetail as $key => $label) {
                                $strJoined .= "<td>";
                                $strJoined .= $val->$key;
                                $strJoined .= "</td>";
                            }
                        }
                        $strJoined .= "</tr>";
                    }
                    $strJoined .= "</tbody>";
                    $strJoined .= "</table>";
                    $strJoined .= "</div>";
                    $arrJoinedHasil[$trID] = $strJoined;
                }
            }
            //------------------------------------
//            arrPrintHijau($arrJoinedHasil);
            //------------------------------------
            $arrayOnProgress = array();
            $numb = 0;
            foreach ($tmpHist as $row) {
//                cekHere("pID: " . $row->produk_id . ", nama: " . $row->produk_nama . ", trID: " . $row->transaksi_id);
                $numb++;
                $tmp = array();
                foreach ($historyFields as $fName => $fLabel) {
                    if (isset($row->$fName)) {
                        if (is_numeric($row->$fName)) {
                            if (!isset($sumFooter[$fName])) {
                                $sumFooter[$fName] = 0;
                            }
                            $sumFooter[$fName] += $row->$fName;
                        }
                    }
                    if (is_array($fLabel)) {
                        $hisStep = $fLabel['step'];
                        $hisKey = $fLabel['key'];
                        if (isset($row->ids_his)) {
                            if ($hisKey == "nomer") {
                                $returnVal = showHistoriGlobalNumbers($row->ids_his, $hisStep, true, $row->jenis_master);
                                if ($returnVal == "") {
                                    $tmp[$fName] = "-";
                                }
                                else {
                                    $tmp[$fName] = $returnVal;
                                }
                            }
                            else {
                                $ids_his_decode = blobDecode($row->ids_his);
                                if (isset($ids_his_decode[$hisStep][$hisKey])) {
                                    $tmp[$fName] = $ids_his_decode[$hisStep][$hisKey];
                                }
                                else {
                                    $tmp[$fName] = "-";
                                }
                            }
                        }
                        else {
                            $tmp[$fName] = "-";
                        }
                    }
                    else {
                        $tmp[$fName] = isset($row->$fName) ? formatField_he_format($fName, $row->$fName) : formatField_he_format($fName, 0);
                    }
                    if ($fName == "no") {
                        $tmp[$fName] = formatField_he_format($fName, $numb);
                    }
                    if ($fName == "radio") {
                        $trID = $row->transaksi_id;
                        $transaksiKey = "transaksiIDSelected";
                        $dataSelected = isset($arrJoinedDataSimple[$trID]) ? $arrJoinedDataSimple[$trID] : array();
                        $dataSelectedBlob = blobEncode($dataSelected);
                        $checked = ($trID == $main[$transaksiKey]) ? "checked" : "";
                        // onclick=\"document.getElementById('result').src='$linkSelect?key=$transaksiKey&data=$dataSelectedBlob&val='+this.value\"
                        $tmp[$fName] = "<input type='radio' name='nota' value='$trID' $checked 
                            onclick=\"document.getElementById('result').src='$linkSelect?key=$transaksiKey&data=$dataSelectedBlob&val='+this.value\"
                            >";
                    }
                }
                if (isset($arrJoinedHasil[$row->transaksi_id])) {
                    $tmp["detail_fields"] = $arrJoinedHasil[$row->transaksi_id];
                }
                $arrayOnProgress[] = $tmp;
            }
            //------------------------------------


            if (sizeof($arrayOnProgress) > 0) {
                $strOnprog .= "<div class='table-responsive '>";
                $strOnprog .= "<table id='arrayOnProgress_step' class='table datatables stripe compact nowarp order-column table-condensed table-bordered no-padding' style='border:solid red 0px;'>";
                $strOnprog .= "<thead>";
                $strOnprog .= "<tr class='text-uppercase' line=" . __LINE__ . ">";
                if (sizeof($historyFields) > 0) {
                    $strOnprog .= "<th class=''>No.</th>";
                    foreach ($historyFields as $key => $label) {
                        $strOnprog .= "<th class=''>";
                        if (is_array($label)) {
                            $strOnprog .= isset($label['label']) ? $label['label'] : "-";
                        }
                        else {
                            $strOnprog .= $label;
                        }
                        $strOnprog .= "</th>";
                    }
                }
                $strOnprog .= "</tr>";
                $strOnprog .= "</thead>";

                $strOnprog .= "<tbody>";
                $no = 0;
                foreach ($arrayOnProgress as $key => $val) {
                    //----------------------
                    $background_color = isset($arrayOnprogressMarking[$key]['style']) ? $arrayOnprogressMarking[$key]['style'] : "";
                    $no++;
                    $strOnprog .= "<tr line=" . __LINE__ . " style='$background_color'>";
                    $strOnprog .= "<td>$no</td>";
                    if (sizeof($historyFields) > 0) {
                        foreach ($historyFields as $key => $label) {
                            $strOnprog .= "<td>";
                            $strOnprog .= $val[$key];
                            $strOnprog .= "</td>";
                        }
                    }
                    $strOnprog .= "</tr>";
                }
                $strOnprog .= "</tbody>";

                if (isset($sumFooter) && sizeof($sumFooter) > 0) {
                    $strOnprog .= "<tfoot>";
                    $strOnprog .= "<tr line=" . __LINE__ . ">";
                    if (sizeof($historyFields) > 0) {
                        foreach ($historyFields as $key => $label) {
                            $strOnprog .= "<th>";
                            $strOnprog .= "-";
                            $strOnprog .= "</th>";
                        }
                        $strOnprog .= "<th>-</th>";
                    }
                    $strOnprog .= "</tr>";
                    $strOnprog .= "</tfoot>";
                }
                $strOnprog .= "</table>";
                $strOnprog .= "</div>";
            }
        }
        return $strOnprog;

    }

    public function callSalesAutoPo()
    {
//
//        $this->CI->db->select("*,penjualan_transaksi.trash as trash,penjualan_transaksi.status as status");
//        $this->CI->db->where("penjualan_transaksi.jenis='5822so'");
//        $this->CI->db->where("penjualan_transaksi.link_id='0'");
//        $this->CI->db->where("penjualan_transaksi.sinkron='0'");
//        $this->CI->db->join("penjualan_transaksi_data_bridge", "penjualan_transaksi_data_bridge.transaksi_id=penjualan_transaksi.id", "inner");
//        $vars = $this->CI->db->get("penjualan_transaksi");
//        return $vars;
//

        $this->CI->db->select("*,penjualan_transaksi_data_bridge_master.trash as trash,penjualan_transaksi_data_bridge_master.status as status");
        $this->CI->db->where("penjualan_transaksi_data_bridge_master.status='1'");
        $this->CI->db->where("penjualan_transaksi_data_bridge_master.trash='0'");
        $this->CI->db->where("penjualan_transaksi_data_bridge_master.cli='0'");
        $this->CI->db->where("penjualan_transaksi_data_bridge_master.po_id='0'");
        $this->CI->db->where("penjualan_transaksi_data_bridge_master.so_id>'0'");
        $this->CI->db->limit(1);
        $this->CI->db->order_by("id", "ASC");
        $vars = $this->CI->db->get("penjualan_transaksi_data_bridge_master")->result();
        cekMerah($this->CI->db->last_query());
        if (sizeof($vars) > 0) {
//            arrPrint($vars);
            $so_id = $vars[0]->so_id;

            $this->CI->db->select("*");
            $this->CI->db->where("penjualan_transaksi_data_bridge.status='1'");
            $this->CI->db->where("penjualan_transaksi_data_bridge.trash='0'");
            $this->CI->db->where("penjualan_transaksi_data_bridge.auto_po_id='0'");
            $this->CI->db->where("penjualan_transaksi_data_bridge.transaksi_id='$so_id'");
            $varsData = $this->CI->db->get("penjualan_transaksi_data_bridge")->result();
//            cekKuning($this->CI->db->last_query());

        }
        else {
            $varsData = array();
        }


        return $varsData;

    }

    public function callPoAutoTerima()
    {

//        $this->CI->db->select("*,pembelian_transaksi.trash as trash,pembelian_transaksi.status as status");
//        $this->CI->db->where("pembelian_transaksi.jenis='466'");
//        $this->CI->db->where("pembelian_transaksi.link_id='0'");
//        $this->CI->db->where("pembelian_transaksi.sinkron='0'");
//        $this->CI->db->where("pembelian_transaksi_bridge.trash='0'");
//        $this->CI->db->where("pembelian_transaksi_bridge.principal_id_spd>'0'");
//        $this->CI->db->join("pembelian_transaksi_bridge", "pembelian_transaksi_bridge.auto_po_id=pembelian_transaksi.id", "inner");
//        $vars = $this->CI->db->get("pembelian_transaksi");
//        return $vars;


        $this->CI->db->select("*,pembelian_transaksi_bridge_terima_master.trash as trash,pembelian_transaksi_bridge_terima_master.status as status");
        $this->CI->db->where("pembelian_transaksi_bridge_terima_master.status='1'");
        $this->CI->db->where("pembelian_transaksi_bridge_terima_master.trash='0'");
        $this->CI->db->where("pembelian_transaksi_bridge_terima_master.po_id>'0'");
        $this->CI->db->where("pembelian_transaksi_bridge_terima_master.so_id>'0'");
        $this->CI->db->where("pembelian_transaksi_bridge_terima_master.principal_spd_id>'0'");
        $this->CI->db->where("pembelian_transaksi_bridge_terima_master.cli_grn_id='0'");// cli grn (0 belum grn, 1 sudah grn)
        $this->CI->db->limit(1);
        $this->CI->db->order_by("id", "ASC");
        $vars = $this->CI->db->get("pembelian_transaksi_bridge_terima_master")->result();
        cekMerah($this->CI->db->last_query());
        if (sizeof($vars) > 0) {
            $principal_spd_id = $vars[0]->principal_spd_id;

            $this->CI->db->select("*");
            $this->CI->db->where("pembelian_transaksi_bridge_terima.status='1'");
            $this->CI->db->where("pembelian_transaksi_bridge_terima.trash='0'");
//            $this->CI->db->where("pembelian_transaksi_bridge_terima.auto_po_id='0'");
            $this->CI->db->where("pembelian_transaksi_bridge_terima.principal_spd_id='$principal_spd_id'");
            $varsData = $this->CI->db->get("pembelian_transaksi_bridge_terima")->result();
            cekKuning($this->CI->db->last_query());

        }
        else {
            $varsData = array();
        }


        return $varsData;

    }

    public function cekExecSalesOrder($so_id, $po_id)
    {
        /**
         * untuk cek apakah nomer so dengan nomer po valid
         * untuk menghindari salah reject
         */
        $this->CI->db->select("*,penjualan_transaksi_data_bridge_master.trash as trash,penjualan_transaksi_data_bridge_master.status as status");
        $this->CI->db->where("penjualan_transaksi_data_bridge_master.status='1'");
        $this->CI->db->where("penjualan_transaksi_data_bridge_master.trash='0'");
        $this->CI->db->where("penjualan_transaksi_data_bridge_master.cli='1'");
        $this->CI->db->where("penjualan_transaksi_data_bridge_master.po_id='$po_id'");
        $this->CI->db->where("penjualan_transaksi_data_bridge_master.so_id='$so_id'");
        $this->CI->db->limit(1);
        $this->CI->db->order_by("id", "ASC");
        $vars = $this->CI->db->get("penjualan_transaksi_data_bridge_master")->result();
        cekLime($this->CI->db->last_query());
        return $vars;
//        cekMerah($this->CI->db->last_query());
    }

    public function cekRejectSalesOrder($so_id, $po_id)
    {
        $this->CI->db->select("*,penjualan_transaksi_holding_reject.trash as trash,penjualan_transaksi_holding_reject.status as status");
        $this->CI->db->where("penjualan_transaksi_holding_reject.status='1'");
        $this->CI->db->where("penjualan_transaksi_holding_reject.trash='0'");
        $this->CI->db->where("penjualan_transaksi_holding_reject.po_id='$po_id'");
        $this->CI->db->where("penjualan_transaksi_holding_reject.so_id='$so_id'");
        $this->CI->db->limit(1);
        $this->CI->db->order_by("id", "ASC");
        $vars = $this->CI->db->get("penjualan_transaksi_holding_reject")->result();
        $data=array();
        if(count($vars)>0){
            $data = array(
                "reject"=>"1",
                "reject_dtime"=>$vars[0]->dtime,
                "reject_ref_dtime"=>$vars[0]->dtime,
                "reject_ref_oleh_nama"=>$vars[0]->reject_ref_oleh_nama,
                "reject_ref_nomer"=>$vars[0]->reject_ref_nomer,
                "reject_ref_dtime"=>$vars[0]->reject_ref_dtime,
            );
        }
        else{
            $data = array(
                "reject"=>0,
                "reject_dtime"=>"",
                "reject_ref_dtime"=>"",
                "reject_ref_oleh_nama"=>"",
                "reject_ref_nomer"=>"",
                "reject_ref_dtime"=>"",
            );
        }
return $data;

    }


    public function callCrmAutoSalesOrder()
    {
        $this->CI->load->model("Mdls/MdlCrmDataBridge");
        $mdb = New MdlCrmDataBridge();
        $mdb->addFilter("qty_saldo>'0'");
        $mdbTmp = $mdb->lookupAll()->result();
        showLast_query("biru");
        $arrData = array();
        $varsData = array();
        if(sizeof($mdbTmp)>0){
            foreach ($mdbTmp as $mdbSpec){
                $arrData[$mdbSpec->estimate_id][] = $mdbSpec;
            }
            $arrDataKeys = array_keys($arrData);
            $varsData = $arrData[$arrDataKeys[0]];
        }





        return $varsData;

    }

}



