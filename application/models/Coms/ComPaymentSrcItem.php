<?php


class ComPaymentSrcItem extends MdlMother
{

    protected $filters = array();
    private $tableName;
    private $tableName_mutasi;
    private $tableName_fifoAvg;
    private $tableName_master = array();
    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $outFields = array( // dari tabel cache
        "id",
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
        "tagihan_valas",
        "terbayar_valas",
        "sisa_valas",
        "cabang_id",
        "cabang_nama",
        "oleh_id",
        "oleh_nama",
        "dtime",
        "fulldate",
    );
    private $koloms = array(
        "id",
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
        "tagihan_valas",
        "terbayar_valas",
        "sisa_valas",
        "cabang_id",
        "cabang_nama",
        "oleh_id",
        "oleh_nama",
        "dtime",
        "fulldate",
    );
    private $pathModul;
    private $modelModul;


    public function getPathModul()
    {
        return $this->pathModul;
    }


    public function setPathModul($pathModul)
    {
        $this->pathModul = $pathModul;
    }


    public function getModelModul()
    {
        return $this->modelModul;
    }


    public function setModelModul($modelModul)
    {
        $this->modelModul = $modelModul;
    }

    public function __construct()
    {
        $this->jenisPembatalan = array(9911, 9912);
    }


    public function pair($inParams)
    {

        $this->inParams = $inParams;


        if (sizeof($this->inParams) > 0) {

            $lCounter = 0;
            foreach ($this->inParams as $cnt => $inSpec) {
                arrprint($inSpec);
                if (isset($inSpec['static']) && sizeof($inSpec['static']) > 0) {
                    $lCounter++;
                    $jenis = isset($inSpec['static']['reverted_target']) ? $inSpec['static']['reverted_target'] : $inSpec['static']['target_jenis'];
                    $jenisPembatalan = isset($inSpec['static']['jenisPembatalan']) ? $inSpec['static']['jenisPembatalan'] : NULL;
//                    $prev = $this->cekPreValue($inSpec['static']['target_jenis'], $inSpec['static']['transaksi_id'], $inSpec['static']['label'], $inSpec['static']['cabang_id']);
                    $prev = $this->cekPreValue(
                        $jenis,
                        $inSpec['static']['transaksi_id'],
                        $inSpec['static']['label'],
                        $inSpec['static']['cabang_id'],
                        $inSpec['static']['tabel_id']
                    );
                    showLast_query("biru");
                    arrPrintWebs($prev);
                    if (isset($inSpec['static']['diskon'])) {
//                            $targetKey = "diskon";
                        $targetVal = $inSpec['static']['diskon'];
                    }
                    else {
                        $targetVal = 0;
                    }


                    //  bagian valas......
                    if ($prev['id'] > 0) {
                        if ($prev['sisa_valas'] >= $inSpec['static']['bayar_valas']) {
                            $qtyBayar = $inSpec['static']['bayar_valas'];
                        }
                        else {
                            $qtyBayar = $prev['sisa_valas'];
                        }
                        $qtyTerbayar = ($prev['terbayar_valas'] + $qtyBayar);
                        $newSisa_valas = ($prev['sisa_valas'] - $qtyBayar);

                        if ($newSisa_valas < 0) {
                            $msg = "Transaksi gagal, karena saldo valas tidak cukup. Silahkan diperiksa kembali transaksi ini.";
                            die(lgShowAlert($msg));
                        }

                    }


                    //  bagian reguler dengan rupiah......
//                    if ($prev['id'] > 0) {
                    if ($prev['id'] != NULL) {
                        $jmlBayar = 0;
                        $jmlHapus = 0;

                        if (isset($inSpec['static']['terbayar'])) {

                            if ($prev['sisa'] >= $inSpec['static']['terbayar']) {
                                $jmlBayar = $inSpec['static']['terbayar'];
                            }
                            else {
                                $jmlBayar = $prev['sisa'];
                            }

                        }
//                        cekMerah("bayar: $jmlBayar");
                        $newTerbayar = ($prev['terbayar'] + $jmlBayar);
                        if (isset($inSpec['static']['dihapus'])) {
                            if ($prev['sisa'] >= $inSpec['static']['dihapus']) {
                                $jmlHapus = $inSpec['static']['dihapus'];
                            }
                            else {
                                $jmlHapus = $prev['sisa'];
                            }
                        }
                        $newDihapus = ($prev['dihapus'] + $jmlHapus);
                        $tagihan = $prev['tagihan'];

                        cekMerah(":: sisa :::: " . $prev['sisa']);
                        cekMerah(":: newTerbayar $newTerbayar :: $jmlBayar :: " . $prev['terbayar']);
                        cekMerah(":: newDihapus $newDihapus :: $jmlHapus :: " . $prev['dihapus']);

                        $newSisa = ($prev['sisa'] - $jmlBayar - $jmlHapus);
                        cekKuning(":: newSisa :: $newSisa ::");
                        //-----------------------------
                        if ($newTerbayar == 0) {
                            if (isset($inSpec['static']['reverted_target'])) {
                            }
                            else {
                                if (in_array($jenisPembatalan, $this->jenisPembatalan)) {

                                }
                                else {
                                    $msg = "Transaksi gagal, karena nilai pembayaran atau penerimaan sebesar 0. Silahkan diperiksa kembali transaksi ini.";
                                    cekMerah($msg);
                                    die(lgShowAlert($msg));
                                }
                            }
                        }
                        //-----------------------------
                        if ($newSisa < 0) {
                            $msg = "Transaksi gagal, karena saldo tidak cukup. Silahkan diperiksa kembali transaksi ini.";
                            cekMerah($msg);
                            die(lgShowAlert($msg));
                        }
                        elseif ($newSisa == $prev['sisa']) {
                            if (($inSpec['static']['terbayar'] > 0)) {
//                                cekHere(ipadd());
//                                if(ipadd()=="202.65.117.72"){
//
//                                }
//                                else{
//                                }
                                $msg = "Transaksi gagal, karena tagihan tidak berkurang. Silahkan diperiksa kembali transaksi ini. [$jmlBayar] [$newSisa] **";
                                cekMerah($msg);
                                die(lgShowAlert($msg));
                            }
                        }
                        elseif ($newTerbayar < 0) {
                            $newTerbayarCek = $newTerbayar;
                            if ($newTerbayarCek < 0) {
                                $newTerbayarCek = $newTerbayarCek * -1;
                            }
                            if ($newTerbayarCek > 10) {
                                $msg = "Transaksi gagal, karena nilai pembayaran atau penerimaan tidak valid. Silahkan diperiksa kembali transaksi ini.";
                                cekMerah($msg);
                                die(lgShowAlert($msg));
                            }
                        }
                        elseif ($newTerbayar > $tagihan) {
//                            cekhitam(__LINE__);
//                            cekHitam($prev['terbayar'] . " :: $newTerbayar > $tagihan ::");
//                            cekHitam($newTerbayar - $tagihan);
//
//
//                            $newTerbayarBulat = pembulatanDiskon($newTerbayar);
//                            $tagihanBulat = pembulatanDiskon($tagihan);
//                            $selisih = $newTerbayarBulat - $tagihanBulat;
                            $selisih = $newTerbayar - $tagihan;
                            if ($selisih > 5) {
                                $msg = "Transaksi gagal, karena nilai pembayaran atau penerimaan tidak valid. Silahkan diperiksa kembali transaksi ini.";
                                cekMerah($msg);
                                die(lgShowAlert($msg));
                            }
                        }

                        $this->outParams[$lCounter]['where'] = array(
                            "id" => $prev['id'],
                            "cabang_id" => $inSpec['static']['cabang_id'],

                        );
                        $this->outParams[$lCounter]['update'] = array(
                            "terbayar" => $newTerbayar,
                            "dihapus" => $newDihapus,
                            "diskon" => $targetVal,
                            "sisa" => $newSisa,

                            "terbayar_valas" => $qtyTerbayar,
                            "sisa_valas" => $newSisa_valas,

                            "transaksi_ref_id" => isset($inSpec['static']['transaksi_ref_id']) ? $inSpec['static']['transaksi_ref_id'] : 0,
                            "transaksi_ref_no" => isset($inSpec['static']['transaksi_ref_no']) ? $inSpec['static']['transaksi_ref_no'] : 0,

                        );
                    }


                }
            }
        }


        if (sizeof($this->outParams) > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    private function cekPreValue($targetJenis, $transaksiID, $label, $cabangID, $tabelID)
    {

        if(isset($this->modelModul)){
            $this->load->model($this->pathModul . "/" . $this->modelModul);
            $tr = new $this->modelModul;
        }
        else{

            $this->load->model("MdlTransaksi");
            $tr = new MdlTransaksi();
        }
        $tr->setFilters(array());
        $tr->addFilter("label='$label'");
        $tr->addFilter("cabang_id='$cabangID'");
        $tr->addFilter("target_jenis='$targetJenis'");
        $tr->addFilter("transaksi_id='$transaksiID'");
        if (($tabelID != null) || ($tabelID != 0)) {
            $tr->addFilter("id='$tabelID'");
        }
        $result = array();
        $localFilters = array();
        if (sizeof($tr->getFilters()) > 0) {
            foreach ($tr->getFilters() as $f) {
                $tmpArr = explode("=", $f);
                $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");

            }
        }
        $query = $this->db->select()
            ->from($tr->getTableNames()['paymentSrc'])
            ->where($localFilters)
            ->limit(1)
            ->get_compiled_select();
        $tmpR = $this->db->query("{$query} FOR UPDATE")->result();
        if (sizeof($tmpR) > 0) {
            foreach ($tmpR as $row) {
                $result = array(
                    "id" => $row->id,
                    "tagihan" => $row->tagihan,
                    "terbayar" => $row->terbayar,
                    "returned" => $row->returned,
                    "sisa" => $row->sisa,
                    "tagihan_valas" => $row->tagihan_valas,
                    "terbayar_valas" => $row->terbayar_valas,
//                    "return_valas" => $row->return_valas,
                    "sisa_valas" => $row->sisa_valas,
                    "dihapus" => $row->dihapus,
                );
            }
        }
        else {
            $result = array(
                "id" => 0,
                "tagihan" => 0,
                "terbayar" => 0,
                "returned" => 0,
                "sisa" => 0,
                "tagihan_valas" => 0,
                "terbayar_valas" => 0,
                "returned_valas" => 0,
                "sisa_valas" => 0,
                "dihapus" => 0,
            );
        }

        return $result;
    }

    public function addFilter($f)
    {
        $this->filters[] = $f;
    }

    public function exec()
    {
        cekHitam("::: " . $this->modelModul);
        if(isset($this->modelModul)){
            $this->load->model($this->pathModul . "/" . $this->modelModul);
            $modelTransaksi = $this->modelModul;
        }
        else{

            $this->load->model("MdlTransaksi");
            $modelTransaksi = "MdlTransaksi";
        }
        if (sizeof($this->outParams) > 0) {
            $insertIDs = array();
            foreach ($this->outParams as $cnt => $oSpec) {

                $tr = new $modelTransaksi();
                $insID = $tr->updatePaymentSrc($oSpec['where'], $oSpec['update']) or die("can not update paymentSrc");
                $insertIDs[] = $insID;

            }


            if (sizeof($insertIDs) > 0) {
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


}