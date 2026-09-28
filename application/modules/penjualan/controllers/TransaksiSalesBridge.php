<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once "Modul_Controller.php";
class TransaksiSalesBridge extends Modul_Controller
//class TransaksiSalesBridge extends CI_Controller
{
//    private $template;
//    private $jenisTr;
//    private $jenisTrName;
//    private $trConfig;
//    private $tableInConfig;
//    private $tableInConfig_static;
//
//    private $arrButtonAction;
//    private $dates = array();


    public function __construct()
    {
        parent::__construct();
        $this->load->config("heWebs");
        $this->load->helper("he_stepping");
        $this->load->helper("he_access_right");
        $this->load->library("MobileDetect");
        $this->load->helper("he_session_replacer");
        $this->load->model("Mdls/MdlCurrency");
        $this->load->model("Mdls/MdlMongoMother");
        $this->load->helper('he_angka');
        $this->load->helper('path');
        // $this->load->model('penjualan/Coms/ComRekeningPenjualan1');
//        $this->load->model('penjualan/models/Coms/ComRekeningPenjualan1');

    }

    function numbering($dt_table){
        $arr = array();
        //region penomoran receipt
        $this->load->model("CustomCounter");
        $cn = new CustomCounter("transaksi");
        $cn->setType("file");
        //$this->db->trans_start();
        $trAlias = "f";
        $numBatch = array(
            "$trAlias" => array(
                "counters" => array(
                    "stepCode", //global number
                    "machine_id",
                    "dt_table",
                    "stepCode|machine_id",
                    "stepCode|dt_table",
                ),
                "formatNota" => ".stepCode,.machine_id,stepCode|dt_table,stepCode|machine_id,.dt_table",
            )
        );
        $arr['stepCode'] = $trAlias;
        $arr['machine_id'] = MACHINE_ID;
        $arr['dt_table'] = $dt_table;
        $counterForNumber = array($numBatch[$trAlias]['formatNota']);
        $cn = new CustomCounter("transaksi");
        $cn->setType("file");
        $configCustomParams = $numBatch[$trAlias]['counters'];
        if (sizeof($configCustomParams) > 0) {
            $cContent = array();
            foreach ($configCustomParams as $i => $cRawParams) {
                $cParams = explode("|", $cRawParams);
                $cValues = array();
                foreach ($cParams as $param) {
                    $cValues[$i][$param] = $arr[$param];
                }
                $cRawValues = implode("|", $cValues[$i]);
                $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);
                $cContent[$cRawParams][$cRawValues] = $paramSpec['value'];
                switch ($paramSpec['id']) {
                    case 0: //===counter type is new
                        $paramKeyRaw = print_r($cParams, true);
                        $paramValuesRaw = print_r($cValues[$i], true);
                        $cn->writeNewCount($cParams, $cValues[$i], $paramKeyRaw, $paramValuesRaw);
                        break;
                    default: //===counter to be updated
                        $cn->updateCount($paramSpec['id'], $paramSpec['value']);
                        break;
                }
            }
        }
        $appliedCounters = base64_encode(serialize($cContent));
        $appliedCounters_inText = print_r($cContent, true);
        $this->load->model("CustomCounter");
        $cn = new CustomCounter("transaksi");
        $cn->setType("file");
        $counterForNumber = array($numBatch[$trAlias]['formatNota']);
        $tmpNomorNota="";
        $arrNomorNota=array();
        foreach ($counterForNumber as $i => $c0RawParams) {
            $c0Params = explode(",", $c0RawParams);
            $c0Values = array();
            foreach($c0Params as $k=>$cRawParams){
                $arrRawParams = explode("|", $cRawParams);
                if(sizeof($arrRawParams)>1){
                    $cRawParamsValues = array();
                    foreach($arrRawParams as $key){
                        $cRawParamsValues[$key] = $arr[$key];
                    }
                    $cRawParamsValuesK = implode("|", array_keys($cRawParamsValues));
                    $cRawParamsValuesV = implode("|", $cRawParamsValues);
                    $arrNomorNota[] = digit_4($cContent[$cRawParamsValuesK][$cRawParamsValuesV]);
                }
                else{
                    if(isset($arr[$arrRawParams[0]])){
                        $cRawParamsValuesV = $arr[$arrRawParams[0]];
                        $cRawParamsValuesK = $arrRawParams[0];
                        if($arrRawParams[0]=="startDate"){
                            $arrNomorNota[] = $cRawParamsValuesV;
                        }
                        elseif($arrRawParams[0]=="toko_id"){
                            $arrNomorNota[] = $cRawParamsValuesV;
                        }
                        elseif($arrRawParams[0]=="stepCode"){
                            $arrNomorNota[] = $cRawParamsValuesV;
                        }
                        elseif($arrRawParams[0]=="machine_id"){
                            $arrNomorNota[] = $cRawParamsValuesV;
                        }
                        else{
                            if(isset($cContent[$cRawParamsValuesK])){
                                $arrNomorNota[] = $cContent[$cRawParamsValuesK][$cRawParamsValuesV];
                            }
                            else{
                                $arrNomorNota[] = $cRawParamsValuesV;
                            }
                        }
                    }
                    else{
                        $cc = explode(".", $arrRawParams[0]);
                        $arrNomorNota[] = $arr[$cc[1]];
                    }
                }
            }
        }
        $tmpNomorNota = implode("_", $arrNomorNota);

//        $this->db->trans_complete();
        return $tmpNomorNota;
    }

    public function index()
    {
        $fulldate = "2024-10-30";
        if (!file_exists(APPPATH.'modules/penjualan/models/ComRekeningPenjualan.php')) {
            cekHitam('Model ComRekeningPenjualan tidak ditemukan di lokasi yang diharapkan.');
        }
        else{
            cekKuning("file ada : ".APPPATH.'modules/penjualan/models/ComRekeningPenjualan.php');
            cekMerah("path sudah benar");
        }
//        $this->load->model("../pembelian/models/Coms/ComRekeningPembelian");
//        $this->load->model("MdlTransaksi");

        $this->load->model("Coms/ComRekeningPenjualan");//rekening main
        $this->load->model("Coms/ComRekeningPembantuTransaksiPenjualanKas");//rekening pembantu kas
        $this->load->model("Coms/ComRekeningTransaksiDataPenjualanCache");//rekening penjualan
        $r = new ComRekeningPenjualan();
        $k = new ComRekeningPembantuTransaksiPenjualanKas();
        $p = new ComRekeningTransaksiDataPenjualanCache();

        $r->setFilters(array());
//        $r->addFilter("periode='harian'");
        $r->addFilter("fulldate='$fulldate'");
//        $r->addFilter("rekening='$fulldate'");
        $temp =$r->fetchAllBalancesHarian();//fetch all harian rekening

        $p->setFilters(array());
//        $p->addFilter("periode='forever'");
        $p->addFilter("fulldate='$fulldate'");
        $tempKas = $p->fetchAllBalancesHarian();
        $main = array();
        $pembantu = array();


        //region build file



        $numbering = "lajurPos_";
        $namaFile = $numbering . "_" . date("YmdHis") . ".json";
//        $filename = __DIR__ . "/eusvc/NonRest/" . $namaFile;
        $filename = APPPATH . "modules/".$this->modul."/files/" . $namaFile;//digeser dari folder controller luar masuk ke per modul

//        matiHEre($filename);
        $file = fopen($filename, "w");
        $writed = fwrite($file, json_encode($temp));
        fclose($file);

        if($writed){

        }
        else{
            matiHere("gagal write json on ($filename)");
        }
        //endregion

        cekBiru($this->db->last_query());
        arrPrint($temp);
        arrPrintWebs($tempKas);


    cekBiru($this->db->last_query());
    cekLime(__LINE__);
    }


}
