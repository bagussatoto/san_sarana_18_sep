<?php

/**
 * Created by JetBrains PhpStorm.
 * User: azes
 * Date: 5/9/12
 * Time: 11:56 AM
 * To change this template use File | Settings | File Templates.
 */

//include_once "Bs_37.php";


class Locker
{
    protected $loginSessions;

    public function getLoginSessions()
    {
        return $this->loginSessions;
    }

    public function setLoginSessions($loginSessions)
    {
        $this->loginSessions = $loginSessions;
    }

    public function __construct()
    {
        // parent::__construct();
        $this->CI =& get_instance();

    }

    public function normalisasiStok()
    {
        $login_session = isset($this->loginSessions) ? $this->loginSessions : matiDisini("prameter session login harap diset dulu");

        $this->CI->load->model("Mdls/MdlLockerStock");
        $this->CI->load->model("Coms/ComLockerStock");
        //region locker finish goods
        $c = new MdlLockerStock();
        $c->addFilter("stock_locker.jenis='produk'");
        $c->addFilter("state='hold'");
        $c->addFilter("jumlah>'0'");
        $c->addFilter("cabang_id=" . $login_session['cabang_id']);
        $c->addFilter("gudang_id=" . $login_session['gudang_id']);
        $c->addFilter("oleh_id=" . $login_session['id']);
        $c->addFilter("transaksi_id='0'");
        $tmpC = $c->lookupAll()->result();
        if (sizeof($tmpC) > 0) {
            $sentParams = array();
            $sentParams2 = array();
            foreach ($tmpC as $row) {
                $pID = $row->produk_id;
                $jml = $row->jumlah;

                //==param untuk melepas stok HOLD
                $subParams = array(
                    "static" => array(
                        "cabang_id" => $row->cabang_id,
                        "gudang_id" => $row->gudang_id,
                        "jenis" => $row->jenis,
                        "state" => "hold",
                        "jumlah" => -($jml),
                        "produk_id" => $pID,
                        "oleh_id" => $login_session['id'],
                        "transaksi_id" => 0,

                    ),
                );
                $sentParams[] = $subParams;

                //==param untuk mengembalikan stok aktiv
                $subParams2 = array(
                    "static" => array(
                        "cabang_id" => $row->cabang_id,
                        "gudang_id" => $row->gudang_id,
                        "jenis" => $row->jenis,
                        "state" => "active",
                        "jumlah" => $jml,
                        "produk_id" => $pID,
                        "oleh_id" => 0,
                        "transaksi_id" => 0,

                    ),
                );
                $sentParams2[] = $subParams2;

            }
            $cs = new ComLockerStock();
            $cs->pair($sentParams) or die("Unable to pair locker for releasing");
            $cs->exec();
            //
            $cs = new ComLockerStock();
            $cs->pair($sentParams2) or die("Unable to pair locker for putting back");
            $cs->exec();

        }
        //endregion

        $this->CI->load->model("Mdls/MdlLockerStockSupplies");
        $this->CI->load->model("Coms/ComLockerStockSupplies");
        //region locker supplies
        $s = new MdlLockerStockSupplies();
        //            $s->addFilter("jenis='supplies'");
        $s->addFilter("stock_locker.jenis='supplies'");
        $s->addFilter("state='hold'");
        $s->addFilter("cabang_id=" . $login_session['cabang_id']);
        $s->addFilter("gudang_id=" . $login_session['gudang_id']);
        $s->addFilter("oleh_id=" . $login_session['id']);
        $s->addFilter("transaksi_id='0'");
        $s->addFilter("jumlah>'0'");
        $tmpS = $s->lookupAll()->result();

        if (sizeof($tmpS) > 0) {
            $sentParams = array();
            $sentParams2 = array();
            foreach ($tmpS as $row) {
                $pID = $row->produk_id;
                $jml = $row->jumlah;

                $subParams = array(
                    "static" => array(
                        "cabang_id" => $row->cabang_id,
                        "gudang_id" => $row->gudang_id,
                        "jenis" => $row->jenis,
                        "state" => "hold",
                        "jumlah" => -($jml),
                        "produk_id" => $pID,
                        "oleh_id" => $login_session['id'],
                        "transaksi_id" => 0,

                    ),
                );
                $sentParams[] = $subParams;

                $subParams2 = array(
                    "static" => array(
                        "cabang_id" => $row->cabang_id,
                        "gudang_id" => $row->gudang_id,
                        "jenis" => $row->jenis,
                        "state" => "active",
                        "jumlah" => $jml,
                        "produk_id" => $pID,
                        "oleh_id" => 0,
                        "transaksi_id" => 0,

                    ),
                );
                $sentParams2[] = $subParams2;

            }
            $ss = new ComLockerStockSupplies();
            $ss->pair($sentParams) or die("Unable to pair locker for releasing");
            $ss->exec();
            //
            $ss = new ComLockerStockSupplies();
            $ss->pair($sentParams2) or die("Unable to pair locker for putting back");
            $ss->exec();
        }
        //endregion

        $this->CI->load->model("Mdls/MdlLockerStockAktiva");
        $this->CI->load->model("Coms/ComLockerStockAktiva");
        //region locker asset tetap
        $s = new MdlLockerStockAktiva();
        //        $s->addFilter("jenis='supplies'");
        $s->addFilter("stock_locker.jenis='aktiva'");
        $s->addFilter("state='hold'");
        $s->addFilter("cabang_id=" . $login_session['cabang_id']);
        $s->addFilter("gudang_id=" . $login_session['gudang_id']);
        $s->addFilter("oleh_id=" . $login_session['id']);
        $s->addFilter("transaksi_id='0'");
        $tmpS = $s->lookupAll()->result();
        if (sizeof($tmpS) > 0) {

            $sentParams = array();
            $sentParams2 = array();
            foreach ($tmpS as $row) {
                $pID = $row->produk_id;
                $jml = $row->jumlah;

                //==param untuk melepas stok HOLD
                $subParams = array(
                    "static" => array(
                        "cabang_id" => $row->cabang_id,
                        "gudang_id" => $row->gudang_id,
                        "jenis" => $row->jenis,
                        "state" => "hold",
                        "jumlah" => -($jml),
                        "produk_id" => $pID,
                        "oleh_id" => $login_session['id'],
                        "transaksi_id" => 0,
                    ),
                );
                $sentParams[] = $subParams;

                //==param untuk mengembalikan stok aktiv
                $subParams2 = array(
                    "static" => array(
                        "cabang_id" => $row->cabang_id,
                        "gudang_id" => $row->gudang_id,
                        "jenis" => $row->jenis,
                        "state" => "active",
                        "jumlah" => $jml,
                        "produk_id" => $pID,
                        "oleh_id" => 0,
                        "transaksi_id" => 0,

                    ),
                );
                $sentParams2[] = $subParams2;

            }
            $ss = new ComLockerStockAktiva();
            $ss->pair($sentParams) or die("Unable to pair locker for releasing");
            $ss->exec();
            //
            $ss = new ComLockerStockAktiva();
            $ss->pair($sentParams2) or die("Unable to pair locker for putting back");
            $ss->exec();
        }
        //endregion

        $this->CI->load->model("Mdls/MdlLockerTransaksi");
        $this->CI->load->model("Coms/ComLockerTransaksi");

        //region locker transaksi
        $jenisLockerTransaksi = array("transaksi", "crm");
        $s = new MdlLockerTransaksi();
//        $s->addFilter("stock_locker_transaksi.jenis='transaksi'");
//        $s->addFilter("stock_locker_transaksi.jenis_locker='transaksi'");
        $s->addFilter("stock_locker_transaksi.jenis in ('" . implode("','", $jenisLockerTransaksi) . "')");
        $s->addFilter("state='hold'");
        $s->addFilter("cabang_id=" . $login_session['cabang_id']);
        $s->addFilter("oleh_id=" . $login_session['id']);
        $s->addFilter("transaksi_id>'0'");
        $s->addFilter("jumlah>'0'");
        $tmpS = $s->lookupAll()->result();
//        showLast_query("biru");
//        cekBiru(count($tmpS));
        if (sizeof($tmpS) > 0) {
            $sentParams = array();
            $sentParams2 = array();
            foreach ($tmpS as $row) {
                $pID = $row->produk_id;
                $jml = $row->jumlah;

                //==param untuk melepas stok HOLD
                $subParams = array(
                    "static" => array(
                        "cabang_id" => $row->cabang_id,
                        "gudang_id" => 0,
                        "jenis" => $row->jenis,
                        "jenis_locker" => $row->jenis_locker,
                        "state" => "hold",
                        "jumlah" => -($jml),
                        "produk_id" => $pID,
                        "oleh_id" => $login_session['id'],
                        "transaksi_id" => $row->transaksi_id,
                    ),
                );
                $sentParams[] = $subParams;

                //==param untuk mengembalikan stok aktiv
//                $subParams2 = array(
//                    "static" => array(
//                        "cabang_id" => $row->cabang_id,
//                        "gudang_id" => 0,
//                        "jenis" => $row->jenis,
//                        "jenis_locker" => $row->jenis_locker,
//                        "state" => "active",
//                        "jumlah" => $jml,
//                        "produk_id" => $pID,
//                        "oleh_id" => 0,
//                        "transaksi_id" => $row->transaksi_id,
//                    ),
//                );
//                $sentParams2[] = $subParams2;

            }
            $ss = new ComLockerTransaksi();
            $ss->pair($sentParams) or die("Unable to pair locker for releasing");
            $ss->exec();
            //
//            $ss = new ComLockerTransaksi();
//            $ss->pair($sentParams2) or die("Unable to pair locker for putting back");
//            $ss->exec();
        }
        //endregion


        $this->CI->load->model("Mdls/MdlProdukPerSerialNumberLocker");
        $sl = new MdlProdukPerSerialNumberLocker();
        $sl->addFilter("oleh_id=" . $login_session['id']);
        $sl->addFilter("jumlah>'0'");
        $tmpSl = $sl->lookupAll()->result();
        if (sizeof($tmpSl) > 0) {
            foreach ($tmpSl as $row) {
                $id_tbl = $row->id;
                $data = array(
                    "jumlah" => 0,
                );
                $where = array(
                    "id" => $id_tbl,
                );
                $sl->setFilters(array());
                $sl->updateData($where, $data);
            }
        }


    }

    public function normalisasiStokNoCabang()
    {
        $login_session = isset($this->loginSessions) ? $this->loginSessions : matiDisini("prameter session login harap diset dulu");

        $this->CI->load->model("Mdls/MdlLockerStock");
        $this->CI->load->model("Coms/ComLockerStock");
        //region locker finish goods
        $c = new MdlLockerStock();
        $c->addFilter("stock_locker.jenis='produk'");
        $c->addFilter("state='hold'");
        $c->addFilter("jumlah>'0'");
//        $c->addFilter("cabang_id=" . $login_session['cabang_id']);
//        $c->addFilter("gudang_id=" . $login_session['gudang_id']);
        $c->addFilter("oleh_id=" . $login_session['id']);
        $c->addFilter("transaksi_id='0'");
        $tmpC = $c->lookupAll()->result();
        if (sizeof($tmpC) > 0) {
            $sentParams = array();
            $sentParams2 = array();
            foreach ($tmpC as $row) {
                $pID = $row->produk_id;
                $jml = $row->jumlah;
                //==param untuk melepas stok HOLD
                $subParams = array(
                    "static" => array(
                        "cabang_id" => $row->cabang_id,
                        "gudang_id" => $row->gudang_id,
                        "jenis" => $row->jenis,
                        "state" => "hold",
                        "jumlah" => -($jml),
                        "produk_id" => $pID,
                        "oleh_id" => $login_session['id'],
                        "transaksi_id" => 0,
                    ),
                );
                $sentParams[] = $subParams;
                //==param untuk mengembalikan stok aktiv
                $subParams2 = array(
                    "static" => array(
                        "cabang_id" => $row->cabang_id,
                        "gudang_id" => $row->gudang_id,
                        "jenis" => $row->jenis,
                        "state" => "active",
                        "jumlah" => $jml,
                        "produk_id" => $pID,
                        "oleh_id" => 0,
                        "transaksi_id" => 0,
                    ),
                );
                $sentParams2[] = $subParams2;

            }
            $cs = new ComLockerStock();
            $cs->pair($sentParams) or die("Unable to pair locker for releasing");
            $cs->exec();
            //
            $cs = new ComLockerStock();
            $cs->pair($sentParams2) or die("Unable to pair locker for putting back");
            $cs->exec();
        }
        //endregion

        $this->CI->load->model("Mdls/MdlLockerStockSupplies");
        $this->CI->load->model("Coms/ComLockerStockSupplies");
        //region locker supplies
        $s = new MdlLockerStockSupplies();
        $s->addFilter("stock_locker.jenis='supplies'");
        $s->addFilter("state='hold'");
//        $s->addFilter("cabang_id=" . $login_session['cabang_id']);
//        $s->addFilter("gudang_id=" . $login_session['gudang_id']);
        $s->addFilter("oleh_id=" . $login_session['id']);
        $s->addFilter("transaksi_id='0'");
        $s->addFilter("jumlah>'0'");
        $tmpS = $s->lookupAll()->result();
        if (sizeof($tmpS) > 0) {
            $sentParams = array();
            $sentParams2 = array();
            foreach ($tmpS as $row) {
                $pID = $row->produk_id;
                $jml = $row->jumlah;

                $subParams = array(
                    "static" => array(
                        "cabang_id" => $row->cabang_id,
                        "gudang_id" => $row->gudang_id,
                        "jenis" => $row->jenis,
                        "state" => "hold",
                        "jumlah" => -($jml),
                        "produk_id" => $pID,
                        "oleh_id" => $login_session['id'],
                        "transaksi_id" => 0,

                    ),
                );
                $sentParams[] = $subParams;

                $subParams2 = array(
                    "static" => array(
                        "cabang_id" => $row->cabang_id,
                        "gudang_id" => $row->gudang_id,
                        "jenis" => $row->jenis,
                        "state" => "active",
                        "jumlah" => $jml,
                        "produk_id" => $pID,
                        "oleh_id" => 0,
                        "transaksi_id" => 0,

                    ),
                );
                $sentParams2[] = $subParams2;

            }
            $ss = new ComLockerStockSupplies();
            $ss->pair($sentParams) or die("Unable to pair locker for releasing");
            $ss->exec();
            //
            $ss = new ComLockerStockSupplies();
            $ss->pair($sentParams2) or die("Unable to pair locker for putting back");
            $ss->exec();
        }
        //endregion

        $this->CI->load->model("Mdls/MdlLockerStockAktiva");
        $this->CI->load->model("Coms/ComLockerStockAktiva");
        //region locker asset tetap
        $s = new MdlLockerStockAktiva();
        $s->addFilter("stock_locker.jenis='aktiva'");
        $s->addFilter("state='hold'");
//        $s->addFilter("cabang_id=" . $login_session['cabang_id']);
//        $s->addFilter("gudang_id=" . $login_session['gudang_id']);
        $s->addFilter("oleh_id=" . $login_session['id']);
        $s->addFilter("transaksi_id='0'");
        $tmpS = $s->lookupAll()->result();
        if (sizeof($tmpS) > 0) {
            $sentParams = array();
            $sentParams2 = array();
            foreach ($tmpS as $row) {
                $pID = $row->produk_id;
                $jml = $row->jumlah;
                //==param untuk melepas stok HOLD
                $subParams = array(
                    "static" => array(
                        "cabang_id" => $row->cabang_id,
                        "gudang_id" => $row->gudang_id,
                        "jenis" => $row->jenis,
                        "state" => "hold",
                        "jumlah" => -($jml),
                        "produk_id" => $pID,
                        "oleh_id" => $login_session['id'],
                        "transaksi_id" => 0,
                    ),
                );
                $sentParams[] = $subParams;
                //==param untuk mengembalikan stok aktiv
                $subParams2 = array(
                    "static" => array(
                        "cabang_id" => $row->cabang_id,
                        "gudang_id" => $row->gudang_id,
                        "jenis" => $row->jenis,
                        "state" => "active",
                        "jumlah" => $jml,
                        "produk_id" => $pID,
                        "oleh_id" => 0,
                        "transaksi_id" => 0,

                    ),
                );
                $sentParams2[] = $subParams2;
            }
            $ss = new ComLockerStockAktiva();
            $ss->pair($sentParams) or die("Unable to pair locker for releasing");
            $ss->exec();
            //
            $ss = new ComLockerStockAktiva();
            $ss->pair($sentParams2) or die("Unable to pair locker for putting back");
            $ss->exec();
        }
        //endregion

        $this->CI->load->model("Mdls/MdlLockerTransaksi");
        $this->CI->load->model("Coms/ComLockerTransaksi");

        //region locker transaksi
        $jenisLockerTransaksi = array("transaksi", "crm");
        $s = new MdlLockerTransaksi();
//        $s->addFilter("stock_locker_transaksi.jenis='transaksi'");
//        $s->addFilter("stock_locker_transaksi.jenis_locker='transaksi'");
        $s->addFilter("stock_locker_transaksi.jenis in ('" . implode("','", $jenisLockerTransaksi) . "')");
        $s->addFilter("state='hold'");
//        $s->addFilter("cabang_id=" . $login_session['cabang_id']);
        $s->addFilter("oleh_id=" . $login_session['id']);
        $s->addFilter("transaksi_id>'0'");
        $s->addFilter("jumlah>'0'");
        $tmpS = $s->lookupAll()->result();
        if (sizeof($tmpS) > 0) {
            $sentParams = array();
            $sentParams2 = array();
            foreach ($tmpS as $row) {
                $pID = $row->produk_id;
                $jml = $row->jumlah;

                //==param untuk melepas stok HOLD
                $subParams = array(
                    "static" => array(
                        "cabang_id" => $row->cabang_id,
                        "gudang_id" => 0,
                        "jenis" => $row->jenis,
                        "jenis_locker" => $row->jenis_locker,
                        "state" => "hold",
                        "jumlah" => -($jml),
                        "produk_id" => $pID,
                        "oleh_id" => $login_session['id'],
                        "transaksi_id" => $row->transaksi_id,
                    ),
                );
                $sentParams[] = $subParams;

                //==param untuk mengembalikan stok aktiv
                $subParams2 = array(
                    "static" => array(
                        "cabang_id" => $row->cabang_id,
                        "gudang_id" => 0,
                        "jenis" => $row->jenis,
                        "jenis_locker" => $row->jenis_locker,
                        "state" => "active",
                        "jumlah" => $jml,
                        "produk_id" => $pID,
                        "oleh_id" => 0,
                        "transaksi_id" => $row->transaksi_id,
                    ),
                );
                $sentParams2[] = $subParams2;

            }
            $ss = new ComLockerTransaksi();
            $ss->pair($sentParams) or die("Unable to pair locker for releasing");
            $ss->exec();
            //
            $ss = new ComLockerTransaksi();
            $ss->pair($sentParams2) or die("Unable to pair locker for putting back");
            $ss->exec();
        }
        //endregion


        $this->CI->load->model("Mdls/MdlProdukPerSerialNumberLocker");
        $sl = new MdlProdukPerSerialNumberLocker();
        $sl->addFilter("oleh_id=" . $login_session['id']);
        $sl->addFilter("jumlah>'0'");
        $tmpSl = $sl->lookupAll()->result();
        if (sizeof($tmpSl) > 0) {
            foreach ($tmpSl as $row) {
                $id_tbl = $row->id;
                $data = array(
                    "jumlah" => 0,
                );
                $where = array(
                    "id" => $id_tbl,
                );
                $sl->setFilters(array());
                $sl->updateData($where, $data);
            }
        }


    }

    public function autoNormalisasiStok()
    {
        // matiDisini("testing");
        // $this->CI->load->helper("heWebs");
        $this->CI->load->config("heWebs");
        $coLogins = $this->CI->config->item('logins');
        // arrPrint($coLogins);
        $idleTime = $coLogins['idleTime'];
        $holdTimeLocker = $idleTime * 1.5;
        // cekBiru($holdTimeLocker);

        $this->CI->load->model("Mdls/MdlLockerStock");
        $ls = new MdlLockerStock();

        // $lockers = $ls->cekLoker(my_cabang_id(),)
        // arrPrint($_SESSION['login']);

        $paramLogins = array(
            "id",
            "cabang_id",
            "gudang_id",
            "nama",
        );

        $this->CI->load->model("Mdls/MdlEmployee");
        $em = new MdlEmployee();

        $Srcs = $em->callLastActive();
        $cou = 0;
        foreach ($Srcs as $src) {
            // arrPrintWebs($src);
            $gudang_id = getDefaultWarehouseID($src->cabang_id);
            // arrPrint($gudang_id);
            $cou++;
            foreach ($paramLogins as $kolom) {
                $nilai_kolom = $kolom == "gudang_id" ? $gudang_id["gudang_id"] : $src->$kolom;

                $paramLogin[$kolom] = $nilai_kolom;
            }
            // arrPrintPink($paramLogin);
            $id = $src->id;
            $nama = $src->nama;
            $last_dtime_active = $src->last_dtime_active;
            // $umurs = dtimeToSecond($last_dtime_active);
            $umur_last_active = umurHour($last_dtime_active, 'i');

            // cekUngu("$umur_last_active > $holdTimeLocker");
            if ($umur_last_active > $holdTimeLocker) {
                $str = ("reset locker dan ditendang");
                // $this->CI->load->library("locker");
                // $lls = new Locker();
                $this->setLoginSessions($paramLogin);
                $this->normalisasiStok();

                /* ---------------------------------------------
                 * login dilogoutkan
                 * ---------------------------------------------*/
                // if (!isset($this->CI->session->login['id'])) {
                $em->forceLogout($id);
                showLast_query("biru");
                // }
            }
            else {
                $str = ("masih aktif");
            }


            cekHitam("$id $nama $last_dtime_active :: $umur_last_active > $holdTimeLocker $str");
            // break;

        }
    }

    public function lockTransaksi($transaksi_id, $transaksi_jenis, $modul)
    {
        $this->CI->load->model("Mdls/MdlLockerTransaksi");
        $lt = new MdlLockerTransaksi();
        $lt->addFilter("transaksi_id='$transaksi_id'");
        $lt->addFilter("transaksi_jenis='$transaksi_jenis'");
        $lt->addFilter("modul='$modul'");
        $ltTmp = $lt->lookupAll()->result();
        if (sizeof($ltTmp) == 0) {
            $ltHold = array(
                "state" => "hold",
                "produk_id" => "$transaksi_id",
                "transaksi_id" => "$transaksi_id",
                "cabang_id" => $this->CI->session->login['cabang_id'],
                "oleh_id" => $this->CI->session->login['id'],
                "oleh_nama" => $this->CI->session->login['nama'],
                "jenis" => "transaksi",
                "jenis_locker" => "transaksi",
                "jumlah" => "1",
                "gudang_id" => "0",
                "transaksi_jenis" => $transaksi_jenis,
                "modul" => $modul,
            );
            // insert ke tabel locker transaksi
            $lt->addData($ltHold);
        }
        else {
            $byUpdateHold = array();
            $totalUpdateHold = 0;
            $insertHold = true;
            foreach ($ltTmp as $ltSpec) {
                if (($ltSpec->state == "hold") && ($ltSpec->jumlah == "1")) {
                    $insertHold = false;
                    break;
                }
                elseif (($ltSpec->state == "hold")) {
                    $totalUpdateHold += isset($ltSpec->jumlah) ? $ltSpec->jumlah : 0;
                    $byUpdateHold[] = $ltSpec->oleh_id;
                }
            }
            if ($insertHold == true) {
                if (($totalUpdateHold == 0) && (in_array($this->CI->session->login['id'], $byUpdateHold))) {
                    $ltHold = array(
                        "jumlah" => "1",
                    );
                    $ltWhere = array(
                        "state" => "hold",
                        "produk_id" => "$transaksi_id",
                        "transaksi_id" => "$transaksi_id",
                        "jenis" => "transaksi",
                        "jenis_locker" => "transaksi",
                        "oleh_id" => $this->CI->session->login['id'],
                        "transaksi_jenis" => $transaksi_jenis,
                        "modul" => $modul,
                    );
                    $lt->updateData($ltWhere, $ltHold);
                }
                else {
                    // cekPink("total HOLDnya 0 dan saya BELUM pernah HOLD transaksi ini");
                    $ltHold = array(
                        "state" => "hold",
                        "produk_id" => "$transaksi_id",
                        "transaksi_id" => "$transaksi_id",
                        "cabang_id" => $this->CI->session->login['cabang_id'],
                        "oleh_id" => $this->CI->session->login['id'],
                        "oleh_nama" => $this->CI->session->login['nama'],
                        "jenis" => "transaksi",
                        "jenis_locker" => "transaksi",
                        "jumlah" => "1",
                        "gudang_id" => "0",
                        "transaksi_jenis" => $transaksi_jenis,
                        "modul" => $modul,
                    );
                    $lt->addData($ltHold);
                }

                $ltActive = array(
                    "jumlah" => "0",
                );
                $ltWhere = array(
                    "state" => "active",
                    "produk_id" => "$transaksi_id",
                    "transaksi_id" => "$transaksi_id",
                    "jenis" => "transaksi",
                    "jenis_locker" => "transaksi",
                    "transaksi_jenis" => $transaksi_jenis,
                    "modul" => $modul,
                );
                $lt->updateData($ltWhere, $ltActive);
            }
            else {
                //                cekPink("sudah ada yang HOLD");
            }
        }

    }

    public function releaseTransaksi($transaksi_id, $transaksi_jenis, $modul)
    {
        if ($transaksi_id != NULL) {
            // meng-nol-kan HOLD oleh saya
            $arrFilter = array(
                "jenis='transaksi'",
                "jenis_locker='transaksi'",
                "state='hold'",
                "oleh_id=" . $this->CI->session->login['id'],
                "transaksi_id='$transaksi_id'",
                "jumlah>'0'",
                "transaksi_jenis='$transaksi_jenis'",
                "modul='$modul'",
            );
            $this->CI->load->model("Mdls/MdlLockerTransaksi");
            $lt = new MdlLockerTransaksi();
            $lt->setFilters(array());
            foreach ($arrFilter as $f) {
                $lt->addFilter($f);
            }
            $tmpS = $lt->lookupAll()->result();
            showLast_query("biru");
            if (sizeof($tmpS) > 0) {
                $where = array(
                    "id" => $tmpS[0]->id
                );
                $data = array(
                    "jumlah" => "0"
                );
                $lt->setFilters(array());
                $lt->updateData($where, $data);
                cekOrange($this->CI->db->last_query());
            }
        }


    }


    public function lockTransaksiCrm($transaksi_id, $transaksi_jenis, $modul)
    {
        $this->CI->load->model("Mdls/MdlLockerTransaksi");
        $lt = new MdlLockerTransaksi();
        $lt->addFilter("transaksi_id='$transaksi_id'");
        $lt->addFilter("transaksi_jenis='$transaksi_jenis'");
        $lt->addFilter("modul='$modul'");
        $lt->addFilter("jenis='crm'");
        $lt->addFilter("jenis_locker='crm'");
        $ltTmp = $lt->lookupAll()->result();
        if (sizeof($ltTmp) == 0) {
            $ltHold = array(
                "state" => "hold",
                "produk_id" => "$transaksi_id",
                "transaksi_id" => "$transaksi_id",
                "cabang_id" => $this->CI->session->login['cabang_id'],
                "oleh_id" => $this->CI->session->login['id'],
                "oleh_nama" => $this->CI->session->login['nama'],
                "jenis" => "crm",
                "jenis_locker" => "crm",
                "jumlah" => "1",
                "gudang_id" => "0",
                "transaksi_jenis" => $transaksi_jenis,
                "modul" => $modul,
            );
            // insert ke tabel locker transaksi
            $lt->addData($ltHold);
        }
        else {
            $byUpdateHold = array();
            $totalUpdateHold = 0;
            $insertHold = true;
            foreach ($ltTmp as $ltSpec) {
                if (($ltSpec->state == "hold") && ($ltSpec->jumlah == "1")) {
                    $insertHold = false;
                    break;
                }
                elseif (($ltSpec->state == "hold")) {
                    $totalUpdateHold += isset($ltSpec->jumlah) ? $ltSpec->jumlah : 0;
                    $byUpdateHold[] = $ltSpec->oleh_id;
                }
            }
            if ($insertHold == true) {
                if (($totalUpdateHold == 0) && (in_array($this->CI->session->login['id'], $byUpdateHold))) {
                    $ltHold = array(
                        "jumlah" => "1",
                    );
                    $ltWhere = array(
                        "state" => "hold",
                        "produk_id" => "$transaksi_id",
                        "transaksi_id" => "$transaksi_id",
                        "jenis" => "crm",
                        "jenis_locker" => "crm",
                        "oleh_id" => $this->CI->session->login['id'],
                        "transaksi_jenis" => $transaksi_jenis,
                        "modul" => $modul,
                    );
                    $lt->updateData($ltWhere, $ltHold);
                }
                else {
                    // cekPink("total HOLDnya 0 dan saya BELUM pernah HOLD transaksi ini");
                    $ltHold = array(
                        "state" => "hold",
                        "produk_id" => "$transaksi_id",
                        "transaksi_id" => "$transaksi_id",
                        "cabang_id" => $this->CI->session->login['cabang_id'],
                        "oleh_id" => $this->CI->session->login['id'],
                        "oleh_nama" => $this->CI->session->login['nama'],
                        "jenis" => "crm",
                        "jenis_locker" => "crm",
                        "jumlah" => "1",
                        "gudang_id" => "0",
                        "transaksi_jenis" => $transaksi_jenis,
                        "modul" => $modul,
                    );
                    $lt->addData($ltHold);
                }

                $ltActive = array(
                    "jumlah" => "0",
                );
                $ltWhere = array(
                    "state" => "active",
                    "produk_id" => "$transaksi_id",
                    "transaksi_id" => "$transaksi_id",
                    "jenis" => "crm",
                    "jenis_locker" => "crm",
                    "transaksi_jenis" => $transaksi_jenis,
                    "modul" => $modul,
                );
                $lt->updateData($ltWhere, $ltActive);
            }
            else {
                //                cekPink("sudah ada yang HOLD");
            }
        }

    }

    public function releaseTransaksiCrm($transaksi_id, $transaksi_jenis, $modul)
    {
        if ($transaksi_id != NULL) {
            // meng-nol-kan HOLD oleh saya
            $arrFilter = array(
                "jenis='crm'",
                "jenis_locker='crm'",
                "state='hold'",
                "oleh_id=" . $this->CI->session->login['id'],
                "transaksi_id='$transaksi_id'",
                "jumlah>'0'",
                "transaksi_jenis='$transaksi_jenis'",
                "modul='$modul'",
            );
            $this->CI->load->model("Mdls/MdlLockerTransaksi");
            $lt = new MdlLockerTransaksi();
            $lt->setFilters(array());
            foreach ($arrFilter as $f) {
                $lt->addFilter($f);
            }
            $tmpS = $lt->lookupAll()->result();
            showLast_query("biru");
            if (sizeof($tmpS) > 0) {
                $where = array(
                    "id" => $tmpS[0]->id
                );
                $data = array(
                    "jumlah" => "0"
                );
                $lt->setFilters(array());
                $lt->updateData($where, $data);
                cekOrange($this->CI->db->last_query());
            }
        }


    }


}
