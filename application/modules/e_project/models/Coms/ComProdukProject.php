<?php


class ComProdukProject extends CI_Model
{
    protected $tableName;
    private $requiredParams = array(
        "id",
        "qty",
    );
    private $resultParams = array();
    private $result;
    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $outFields = array( // dari tabel cache
        "id",
        "nama",
        "kode",
        "transaksi_id",
        "transaksi_no",
        "oleh_id",
        "oleh_nama",
        "customer_id",
        "customer_nama",
        "closing_status",
        "closing_oleh_id",
        "closing_oleh_nama",
        "closing_dtime",
        "closing_transaksi_id",
        "closing_transksi_no",
        "cabang_id",
        "cabang_nama",
        "dtime",
        "start_dtime",
        "end_dtime",
        "harga",
        "create_by_id",
        "create_by_name",
    );


    public function __construct($resultParams = array())
    {
        parent::__construct();
        $this->tableName = "e_project_intern_transaksi";
        $this->resultParams = $resultParams;
    }

    //<editor-fold desc="getter-setter">

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

    public function getTableName()
    {
        return $this->tableName;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    //</editor-fold>

    public function setResultParams($resultParams)
    {
        $this->resultParams = $resultParams;
    }

    public function pair($inParams)
    {
//        $this->load->model("Mdls/MdlProjectInternTransaksi");
        $this->load->helper("he_mass_table");
        $this->load->model("Mdls/MdlProjectInternTransaksi");
        
        if (!is_array($inParams)) {
            mati_disini("params/kiriman data required!");
        }

        $needles = array();
        $ids = array();
        $tmp = array();
        $arrHasil = array();
        if (sizeof($inParams) > 0) {
            foreach ($inParams as $cnt => $sentParams) {
                $cCode = "_TR_" . $inParams["static"]["jenis"];
                $_preValues = $this->cekPreValue(
                    $inParams['static']['transaksi_id'],
                    $inParams['static']['project_id']
                );
                if (array_key_exists("id", $_preValues["cache"]) && ($_preValues["cache"]["id"] > 0)) {
                    $pSpec_mode_data = array(
                        "transaksi_id" => $inParams['static']['transaksi_id'],
                        "transaksi_no" => $inParams['static']['transaksi_no'],
                    );
                    $this->db->where('id', $inParams['static']['project_id']);
                    $insertIDs[] = $this->db->update($this->getTableName(), $pSpec_mode_data);
                }
                else {
                    $mode = "insert";
                    $msg = "Transaksi gagal disimpan karena data otorisasi tidak valid. Silahkan relogin akun anda. [".__CLASS__."] line: " . __LINE__;
                    mati_disini($msg);
                }
            }
        }
        return true;
    }

    private function cekPreValue($transaksi_id, $produk_id)
    {
        $this->filters = array();
//        $this->addFilter("transaksi_id='$transaksi_id'");
        $this->addFilter("id='$produk_id'");
        $this->addFilter("trash='0'");
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

        cekHitam($this->db->last_query());
        arrPrintWebs($tmp);

        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $result["cache"] = array(
                    "id" => $row->id,
                );
            }
        }
        else {
            // bila count($tmp) == 0, belum ada data
            $result["cache"] = array(
                "debet" => 0,
                "kredit" => 0,
                "qty_debet" => 0,
                "qty_kredit" => 0,
                "saldo" => 0,
                "qty_saldo" => 0,
                "harga" => 0,
                "approve" => 0,
                "reject" => 0,
                "return" => 0,
                "batal" => 0,
                "qty_approve" => 0,
                "qty_reject" => 0,
                "qty_return" => 0,
                "qty_batal" => 0,
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
        return true;
    }


}

?>