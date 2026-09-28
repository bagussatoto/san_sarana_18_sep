<?php


class ComTransaksiDataPembelianTerimaBridging extends MdlMother
{

    protected $filters = array();
    protected $tableName;
    private $tableName_mutasi;
    private $tableName_fifoAvg;
    private $tableName_master = array();
    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $outFields = array( // dari tabel cache
        "supplier_id",
        "supplier_nama",
        "cabang_id",
        "produk_id",
        "produk_nama",
        "produk_ord_jml",
        "produk_ord_hrg",
        "transaksi_id",
        "transaksi_no",
        "referensi_id",
        "dtime",
        "jml_nilai",
        "auto_po_id",
        "auto_po_nomer",
        "auto_po_dtime",
        "qty_saldo",
        "oleh_id",
        "oleh_nama",
        "cli_grn_id",
        "cli_grn_nomer",
        "cli_grn_dtime",
        "principal_spd_id",
        "principal_spd_nomer",
        "principal_spd_dtime",
        "principal_produk_ord_hrg",
        "principal_produk_ord_jml",
    );
    private $outFieldsMasterBridge = array( // dari tabel cache
        "dtime",
        "fulldate",
        "referensi_id",
        "po_id",
        "po_nomer",
        "so_id",
        "so_nomer",
        "domain",
        "cli",
        "cli_id",
        "cli_nomer",
        "cli_dtime",
        "status",
        "trash",
        "cli_grn_id",
        "cli_grn_nomer",
        "cli_grn_dtime",
        "principal_spd_id",
        "principal_spd_nomer",
        "principal_spd_dtime",
        "principal_cabang_id",
        "cabang_id",
    );
    private $koloms = array(
        "cabang_id",
        "produk_id",
        "nama",
        "jml",
        "hpp",
        "jml_nilai",
    );
    private $outFieldsMutasi = array( // dari tabel rek mutasi rekening
        "rekening",
        "cabang_id",
        "debet",
        "kredit",
        "qty_debet",
        "qty_kredit",
        "dtime",
        "fulldate",
        "extern_id",
        "extern_nama",
        "jenis",
        "gudang_id",
        "harga",
        "transaksi_id",
        "transaksi_no",
        "keterangan",
        "extern2_id",
        "extern2_nama",
        "extern3_id",
        "extern3_nama",
        "extern4_id",
        "extern4_nama",
        "extern5_id",
        "extern5_nama",
        "produk_id",
        "produk_nama",
        "debet_awal",
        "debet_akhir",
        "kredit_awal",
        "kredit_akhir",
        "qty_debet_awal",
        "qty_debet_akhir",
        "qty_kredit_awal",
        "qty_kredit_akhir",
    );
    private $periode = array("forever");
    protected $jenisTr;
    protected $sortBy = array(
        "kolom" => "id",
        "mode" => "asc",
    );

    public function __construct()
    {
        $this->tableName = "pembelian_transaksi_bridge_terima";
        $this->tableName_master = array(
            "master" => "pembelian_transaksi",
            "master_bridge" => "pembelian_transaksi_bridge_terima_master",
        );
    }

    //  region setter, getter

    public function getOutFieldsMasterBridge()
    {
        return $this->outFieldsMasterBridge;
    }

    public function setOutFieldsMasterBridge($outFieldsMasterBridge)
    {
        $this->outFieldsMasterBridge = $outFieldsMasterBridge;
    }

    public function getJenisTr()
    {
        return $this->jenisTr;
    }

    public function setJenisTr($jenisTr)
    {
        $this->jenisTr = $jenisTr;
    }

    public function getSortBy()
    {
        return $this->sortBy;
    }

    public function setSortBy($sortBy)
    {
        $this->sortBy = $sortBy;
    }

    public function getTableNameMaster()
    {
        return $this->tableName_master;
    }

    public function setTableNameMaster($tableName_master)
    {
        $this->tableName_master = $tableName_master;
    }

    public function getPeriode()
    {
        return $this->periode;
    }

    public function setPeriode($periode)
    {
        $this->periode = $periode;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function getTableNameTmp()
    {
        return $this->tableName__tmp;
    }

    public function setTableNameTmp($tableName__tmp)
    {
        $this->tableName__tmp = $tableName__tmp;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function setFilters($filters)
    {
        $this->filters = $filters;
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

    public function getOutFields()
    {
        return $this->outFields;
    }

    public function setOutFields($outFields)
    {
        $this->outFields = $outFields;
    }

    public function getOutFieldsMutasi()
    {
        return $this->outFieldsMutasi;
    }

    public function setOutFieldsMutasi($outFieldsMutasi)
    {
        $this->outFieldsMutasi = $outFieldsMutasi;
    }

    public function getTableNameMutasi()
    {
        return $this->tableName_mutasi;
    }

    //  endregion setter, getter

    public function setTableNameMutasi($tableName_mutasi)
    {
        $this->tableName_mutasi = $tableName_mutasi;
    }

    public function pair($inParams)
    {
        $this->load->helper("he_mass_table");
        $this->inParams = $inParams;

        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            foreach ($this->inParams as $array_params) {

//                $select_curentID = isset($array_params['static']['extern_id']) ? $array_params['static']['extern_id'] : $array_params['static']['transaksi_id'];
                $so_id = isset($array_params['static']['so_id']) ? $array_params['static']['so_id'] : 0;
                $po_id = isset($array_params['static']['po_id']) ? $array_params['static']['po_id'] : 0;
                $spd_id = isset($array_params['static']['principal_spd_id']) ? $array_params['static']['principal_spd_id'] : 0;
                $cabang_id = isset($array_params['static']['cabang_id']) ? $array_params['static']['cabang_id'] : 0;
                $cabang_id = isset($array_params['static']['cabang_id']) ? $array_params['static']['cabang_id'] : 0;
                $produk_id = isset($array_params['static']['produk_id']) ? $array_params['static']['produk_id'] : 0;
                $supplier_id = isset($array_params['static']['principal_cabang_id']) ? $array_params['static']['principal_cabang_id'] : 0;

                $_preValues = $this->cekPreValue(
                    $so_id,//so_id
                    $po_id,//po_id
                    $spd_id,//spd_id
                    $cabang_id,//cabang_id
                    $produk_id// produkID
                );
                if (array_key_exists("id", $_preValues["cache"]) && ($_preValues["cache"]["id"] > 0)) {
                    $mode = "update";
                    $_preValues_id = $_preValues["cache"]["id"];
                }
                else {
                    $mode = "insert";
                    $_preValues_id = 0;
                }
                $lCounter++;
                cekHere("[$lCounter]");
                foreach ($array_params['static'] as $key_static => $value_static) {
                    if (in_array($key_static, $this->outFields)) {
                        $this->outParams[$lCounter]["cache"][$mode][$key_static] = $value_static;
                    }
                }
                if ($lCounter == 1) {
                    $_preValues = $this->cekPreValueBridge(
                        $so_id,//so_id
                        $po_id,//po_id
                        $spd_id,//spd_id
                        $cabang_id,//cabang_id
                        $supplier_id//supplier_id
                    );
                    if (sizeof($_preValues["cache"]) == 0) {
//                        $this->outParams[$lCounter]["master_bridge"][$mode]["referensi_id"] = $array_params['static']['referensi_id'];
//                        $this->outParams[$lCounter]["master_bridge"][$mode]["po_id"] = $array_params['static']['auto_po_id'];
//                        $this->outParams[$lCounter]["master_bridge"][$mode]["po_nomer"] = $array_params['static']['auto_po_nomer'];
//                        $this->outParams[$lCounter]["master_bridge"][$mode]["so_id"] = $array_params['static']['transaksi_id'];
//                        $this->outParams[$lCounter]["master_bridge"][$mode]["so_nomer"] = $array_params['static']['transaksi_no'];
//                        $this->outParams[$lCounter]["master_bridge"][$mode]["dtime"] = $array_params['static']['auto_po_dtime'];
//                        $this->outParams[$lCounter]["master_bridge"][$mode]["fulldate"] = $array_params['static']['auto_po_dtime'];
                        foreach ($array_params['static'] as $key_static => $value_static) {
                            if (in_array($key_static, $this->outFieldsMasterBridge)) {
                                $this->outParams[$lCounter]["master_bridge"][$mode][$key_static] = $value_static;
                            }
                        }
                    }
                }

            }



            //region Exec()
            $pakai_exec = 1;
            if ($pakai_exec == 1) {
                $tableName = $this->tableName;
                $tableName_master_bridge = $this->tableName_master["master_bridge"];

                $insertIDs = array();
                if (sizeof($this->outParams) > 0) {
                    foreach ($this->outParams as $lCounter => $pSpec) {
                        foreach ($pSpec as $mode => $pSpec_mode) {
                            switch ($mode) {
                                case "cache":// data bridge per-produk
                                    foreach ($pSpec_mode as $sub_mode => $pSpec_mode_data) {
                                        $id = isset($pSpec_mode_data["id"]) ? $pSpec_mode_data["id"] : 0;
                                        unset($pSpec_mode_data["id"]);
                                        switch ($sub_mode) {
                                            case "insert":
                                                $this->db->insert($tableName, $pSpec_mode_data);
                                                $insertIDs[] = $this->db->insert_id();
                                                cekUngu("$sub_mode :: " . $this->db->last_query());
                                                break;
                                            case "update":
                                                matiHEre("[$mode] not allowed to update code: " . __LINE__);
                                                $this->db->where('id', $id);
                                                $insertIDs[] = $this->db->update($tableName, $pSpec_mode_data);
                                                cekOrange("$sub_mode :: " . $this->db->last_query());
                                                break;
                                        }
                                    }
                                    break;
                                case "master_bridge":// data bridge per-so dan po
                                    if (sizeof($pSpec_mode) > 0) {
                                        foreach ($pSpec_mode as $sub_mode => $pSpec_mode_data) {
                                            $id = isset($pSpec_mode_data["id"]) ? $pSpec_mode_data["id"] : 0;
                                            unset($pSpec_mode_data["id"]);
                                            switch ($sub_mode) {
                                                case "insert":
                                                    $this->db->insert($tableName_master_bridge, $pSpec_mode_data);
                                                    $insertIDs[] = $this->db->insert_id();
                                                    cekUngu("$sub_mode [$tableName_master_bridge] :: " . $this->db->last_query());
                                                    break;
                                                case "update":
                                                    matiHEre("[$mode] not allowed to update code: " . __LINE__);
                                                    $this->db->where('id', $id);
                                                    $insertIDs[] = $this->db->update($tableName, $pSpec_mode_data);
                                                    cekOrange("$sub_mode :: " . $this->db->last_query());
                                                    break;
                                            }
                                        }
                                    }
                                    break;
                                default:
                                    matiHEre("unknown metode to write transaction " . __NAMESPACE__ . "::CODE " . __LINE__);
                                    break;
                            }
                        }
                    }
                    $this->outParams = array();

                    if (sizeof($insertIDs) == 0) {
                        cekMerah("::: PERIODE :  :::");
                        return false;
                    }
                }
                else {
                    matiHEre(__LINE__);
                    return false;
                }
            }
            //endregion

        }


        if (count($insertIDs) > 0) {
            return true;
        }
        else {
            matiEHre("gagal nulis data");
            return false;
        }

    }

    private function cekPreValue($so_id, $po_id, $spd_id, $cabang_id, $produk_id)
    {
        $this->filters = array();
        $this->addFilter("transaksi_id='$so_id'");
        $this->addFilter("auto_po_id='$po_id'");
        $this->addFilter("principal_spd_id='$spd_id'");
        $this->addFilter("cabang_id='$cabang_id'");
        $this->addFilter("produk_id='$produk_id'");
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
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $result["cache"] = array(
                    "id" => $row->id,
                );
            }
        }
        else {
            // bila count($tmp) == 0, belum ada data
            $result["cache"] = array();
        }

        return $result;
    }

    private function cekPreValueBridge($so_id, $po_id, $spd_id, $cabang_id, $supplier_id)
    {
        $this->filters = array();
        $this->addFilter("so_id='$so_id'");
        $this->addFilter("po_id='$po_id'");
        $this->addFilter("principal_spd_id='$spd_id'");
        $this->addFilter("cabang_id='$cabang_id'");
        $this->addFilter("principal_cabang_id='$supplier_id'");
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
            ->from($this->tableName_master["master_bridge"])
            ->where($localFilters)
            ->limit(1)
            ->get_compiled_select();
        $tmp = $this->db->query("{$query} FOR UPDATE")->result();
        cekHitam($this->db->last_query());
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $result["cache"] = array(
                    "id" => $row->id,
                );
            }
        }
        else {
            // bila count($tmp) == 0, belum ada data
            $result["cache"] = array();
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

    public function lookupLastEntries($cabang_id)
    {
        //        $periode = $this->getPeriode()['3'];
        $condite = array("trash" => "0", "cabang_id" => "$cabang_id");
        $arrKoloms = $this->getKoloms();
        $selectKolom = "";
        foreach ($arrKoloms as $kolom) {
            $selectKolom .= "$kolom,";
        }
        $selectKolom = rtrim($selectKolom, ",");

        $this->db->select($selectKolom);
        $this->db->where($condite);
        $q = $this->db->get($this->tableName_fifoAvg)->result();

        return $q;
    }

    public function getKoloms()
    {
        return $this->koloms;
    }

    public function buildTables($inParams)
    {

        $this->load->helper("he_mass_table");

        $arrRekening = array();
        $this->inParams = $inParams;
        if (sizeof($this->inParams['loop']) > 0) {
            foreach ($this->periode as $periode) {
                $arrRekening = array();
                foreach ($this->inParams['loop'] as $key => $value) {
                    $arrRekening[] = $key;
                }
            }
        }
        else {
            $arrRekening = array();
        }


        if (sizeof($arrRekening) > 0) {
            $result = heReturnTableName($this->tableName_master, $arrRekening);
            if (sizeof($result) > 0) {
                foreach ($result as $rek => $arrSpec) {
                    foreach ($arrSpec as $key => $val) {
                        //                        cekMerah("create tabel $val - $key");
                        $result_c = tableForceCheck($val, $this->tableName_master[$key]);
                    }
                }
            }
        }
    }


    public function getData()
    {


    }
}