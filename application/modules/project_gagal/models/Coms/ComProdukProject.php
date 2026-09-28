<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComProdukProject extends MdlMother
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
//        "nama",
//        "kode",
//        "transaksi_id",
//        "transaksi_no",
//        "oleh_id",
//        "oleh_nama",
//        "customer_id",
//        "customer_nama",
//        "closing_status",
//        "closing_oleh_id",
//        "closing_oleh_nama",
//        "closing_dtime",
//        "closing_transaksi_id",
//        "closing_transksi_no",
//        "cabang_id",
//        "cabang_nama",
//        "dtime",
//        "start_dtime",
//        "end_dtime",
//        "harga",
//        "spek",

"id",
"last_update",
"nama",
"kode",
"cabang_id",
"cabang_nama",
"create_by_id",
"create_by_name",
"persen_progress",
"harga_progress",
"lock",
"lock_id",
"lock_nama",
"lock_dtime",
"quot_nomer",
"quot_desc",
"quot_status",
"quot_id",
"quot_appr_id",
"quot_appr_nama",
"quot_appr_dtime",
"project_start_nomer",
"project_start",
"project_start_dtime",
"project_start_id",
"project_start_name",
"label",
"dtime",
"satuan",
"satuan_label",
"transaksi_dtime",
"transaksi_id",
"transaksi_no",
"nomor_kontrak",
"tanggal_kontral",
"kontrak_oleh_id",
"kontrak_oleh_nama",
"kontrak_customer_id",
"kontrak_customer_nama",
"oleh_id",
"oleh_nama",
"customer_id",
"customer_nama",
"closing_status",
"closing_oleh_id",
"closing_oleh_nama",
"closing_dtime",
"closing_transaksi_id",
"closing_transaksi_nomer",
"transaksi_id_app",
"transaksi_no_app",
"garansi",
"folders",
"folders_nama",
"status",
"trash",
"alamat",
"keterangan",
"spek",
"start_dtime",
"end_dtime",
"harga",
"tarif_ppn",
"ppn",
"harga_nppn",
"uang_muka_request",
"uang_muka_approved",
"project_started_id",
"project_started_name",
"project_started_dtime",
"project_started_desc",
"tanggal_kontrak",
"gen_tasklist"

    );

    public function getOutFields()
    {
        return $this->outFields;
    }

    public function setOutFields($outFields)
    {
        $this->outFields = $outFields;
    }

    public function getOutParams()
    {
        return $this->outParams;
    }

    public function setOutParams($outParams)
    {
        $this->outParams = $outParams;
    }

    private $koloms = array(

        "id",
        "last_update",
        "nama",
        "kode",
        "cabang_id",
        "cabang_nama",
        "create_by_id",
        "create_by_name",
        "persen_progress",
        "harga_progress",
        "lock",
        "lock_id",
        "lock_nama",
        "lock_dtime",
        "quot_nomer",
        "quot_desc",
        "quot_status",
        "quot_id",
        "quot_appr_id",
        "quot_appr_nama",
        "quot_appr_dtime",
        "project_start_nomer",
        "project_start",
        "project_start_dtime",
        "project_start_id",
        "project_start_name",
        "label",
        "dtime",
        "satuan",
        "satuan_label",
        "transaksi_dtime",
        "transaksi_id",
        "transaksi_no",
        "nomor_kontrak",
        "tanggal_kontral",
        "kontrak_oleh_id",
        "kontrak_oleh_nama",
        "kontrak_customer_id",
        "kontrak_customer_nama",
        "oleh_id",
        "oleh_nama",
        "customer_id",
        "customer_nama",
        "closing_status",
        "closing_oleh_id",
        "closing_oleh_nama",
        "closing_dtime",
        "closing_transaksi_id",
        "closing_transaksi_nomer",
        "transaksi_id_app",
        "transaksi_no_app",
        "garansi",
        "folders",
        "folders_nama",
        "status",
        "trash",
        "alamat",
        "keterangan",
        "spek",
        "start_dtime",
        "end_dtime",
        "harga",
        "tarif_ppn",
        "ppn",
        "harga_nppn",
        "uang_muka_request",
        "uang_muka_approved",
        "project_started_id",
        "project_started_name",
        "project_started_dtime",
        "project_started_desc",
        "tanggal_kontrak",
        "gen_tasklist"

//        "id",
//        "jenis",
//        "target_jenis",
//        "reference_jenis",
//        "transaksi_id",
//        "extern_id",
//        "extern_nama",
//        "nomer",
//        "label",
//        "tagihan",
//        "terbayar",
//        "sisa",
//        "tagihan_valas",
//        "terbayar_valas",
//        "sisa_valas",
//        "cabang_id",
//        "cabang_nama",
//        "oleh_id",
//        "oleh_nama",
//        "dtime",
//        "fulldate",
    );

    public function __construct()
    {

    }

    public function pair($inParams)
    {
        $this->inParams = $inParams;
        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            foreach ($this->inParams as $cnt => $inSpec) {
                if (isset($inSpec['static']) && sizeof($inSpec['static']) > 0) {
                    $lCounter++;
                    $toUpdate = array();
                    foreach ($this->outFields as $kolom) {
                        if (isset($prev[$kolom])) {
                            $toUpdate[$kolom] = $prev[$kolom];
                        }
                    }
                    $this->load->model("Mdls/MdlProjectInternTransaksi");
                    $p = new MdlProjectInternTransaksi();
                    $dataBaru = array();
                    foreach ($inSpec['static'] as $keyy => $vall) {
                        if (in_array($keyy, $this->outFields)) {
                            $dataBaru[$keyy] = $vall;
                        }
                    }
                    arrPrintWebs($dataBaru);
                    $ins = $p->addData($dataBaru);
                    if(!$ins){
                        showlast_query("merah");
                        mati_disini("gagal menulis data project. Segera hubungi admin. LINE: " . __LINE__);
                    }
                }
            }
        }
        return true;
    }

    private function cekPreValue($array)
    {
        $this->load->model("Mdls/MdlProjectInternTransaksi");
        $tr = new MdlProjectInternTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("id='" . $array['id'] . "'");
        switch ($array['methode']) {
            case"open":
                $tr->addFilter("transaksi_id='0'");
                break;
            case "update":
                $tr->addFilter("id='" . $array['id'] . "'");
                break;
            case "close":
            case "revert":
                $tr->addFilter("transaksi_id='" . $array['transaksi_id'] . "'");
                break;
            default:
                matiHere("Gagal menyimpan transaksi (" . __CLASS__ . ") <br> ERROR CODE " . __LINE__ . "<br> ON " . date("Y-m-d H:i"));
                break;
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
            ->from($tr->getTableName())
            ->where($localFilters)
            ->limit(1)
            ->get_compiled_select();
        $tmpR = $this->db->query("{$query} FOR UPDATE")->result();
        switch ($array['methode']) {
            case"open":
                if (sizeof($tmpR) > 0) {
                    unset($array["id"]);
                    $data = $array;
                }
                else {
                    $data = null;
                }
                break;
            case "update":
                if (sizeof($tmpR) == 1) {
                    $data = array("transaksi_no_app" => $array['transaksi_no'], "transaksi_id_app" => $array['transaksi_id']);
                }
                else {
                    $data = null;
                }
                break;
            case "close":
                if (sizeof($tmpR) == 1) {
                    unset($array["id"]);
                    $data = $array;
                }
                else {
                    $data = null;
                }
                break;
            case "revert":
                if (sizeof($tmpR) > 0) {
                    unset($array["id"]);
                    $data = $array;
                }
                else {
                    $data = null;
                }
                break;
            default:
                $data = null;
                break;
        }
        return $data;
    }

    public function addFilter($f)
    {
        $this->filters[] = $f;
    }

    public function exec()
    {
        return true;
    }
}