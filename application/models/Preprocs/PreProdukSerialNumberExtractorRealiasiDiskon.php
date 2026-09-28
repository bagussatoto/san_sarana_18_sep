<?php


class PreProdukSerialNumberExtractorRealiasiDiskon extends CI_Model
{
    private $requiredParams = array(
        "id",
        "qty",
    );
    private $resultParams = array();
    private $inParams;
    private $outParams;
    private $result;
    private $paymentMethod = array();


    public function __construct($resultParams = array())
    {
        parent::__construct();
        $this->resultParams = $resultParams;


    }

    //<editor-fold desc="getter-setter">
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function getRequiredParams()
    {
        return $this->requiredParams;
    }

    public function setRequiredParams($requiredParams)
    {
        $this->requiredParams = $requiredParams;
    }

    public function getInParams()
    {
        return $this->inParams;
    }

    public function setInParams($inParams)
    {
        $this->inParams = $inParams;
    }

    public function getOutParams()
    {
        return $this->outParams;
    }

    public function setOutParams($outParams)
    {
        $this->outParams = $outParams;
    }

    public function getResultParams()
    {
        return $this->resultParams;
    }

    //</editor-fold>

    public function setResultParams($resultParams)
    {
        $this->resultParams = $resultParams;
    }

    public function pair($master_id, $inParams)
    {
        if (!is_array($inParams)) {
            die("params required!");
        }

//        arrPrint($inParams);
        if (sizeof($inParams) > 0) {
            $pakai_ini = 0;
            if (isset($inParams["static"]["kompensasiMethod"]) && $inParams["static"]["kompensasiMethod"] == "4") {
                $pakai_ini = 1;
            }
//            matiHere($pakai_ini);
            if ($pakai_ini == 1) {
                $jenisTr = $inParams["static"]["jenisTr"];
                $cabangID = $inParams["static"]["cabang_id"];
                $step_number = $inParams["static"]["step_number"];
                $cCode = "_TR_" . $jenisTr;
                $_SESSION[$cCode]["items3_sum"] = array();
//                cekHitam("masuk disini, tidak scan serial saat grn...");
                if (isset($_SESSION[$cCode]["items2"])) {
                    if (isset($_SESSION[$cCode]["items2"])) {
                        foreach ($_SESSION[$cCode]["items2"] as $produk_id => $spec) {
//                            arrPrint($spec);
//                            matiHere(__LINE__);
                            foreach ($spec as $produk_sku => $subSpec) {
//cekMErah(__LINE__);
                                if (isset($_SESSION[$cCode]["items5_sum"][$produk_id])) {
                                    $konversiSpec = $_SESSION[$cCode]["items5_sum"][$produk_id];
//                                    foreach ($_SESSION[$cCode]["items5_sum"][$produk_id] as $konversiSpec) {
//                                        arrprint($konversiSpec);
                                        $jml = $konversiSpec["qty"];
                                        for ($x = 1; $x <= $jml; $x++) {
                                            unset($konversiSpec["jml"]);
                                            unset($konversiSpec["qty"]);
                                            $data = $konversiSpec + array(
                                                    "jml" => 1,
                                                    "qty" => 1,
                                                    "serial_number" => "",
                                                    "produk_serial" => "",
                                                    "produk_sku" => $konversiSpec["kode"],
                                                    "produk_sku_serial" => "",
                                                    "produk_sku_part_id" => "",
                                                    "produk_sku_part_nama" => $konversiSpec["kode"],
                                                    "produk_sku_part_serial" => "",
                                                    "transaksi_reference_dtime" => date("Y-m-d H:i:s"),
                                                    "transaksi_reference_fulldate" => date("Y-m-d"),
                                                    "transaksi_reference_count" => 1,
                                                    "part_keterangan"=>"PART",
                                                );
                                            $_SESSION[$cCode]["items3_sum"][] = $data;
                                        }
//                                        matiHere($konversiSpec["kode"]);
//                                    }


                                }
                            }
                        }
                    }
                }
            }
        }
//        arrPrintPink($_SESSION[$cCode]["items3_sum"]);
//        mati_disini(__LINE__);
        return true;
    }

    public function exec()
    {
        return $this->result;
    }
}