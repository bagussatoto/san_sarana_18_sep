<?php
/*
 * cache per transaki_id, produkid sebagai index
 */


class ComRekeningTransaksiDataProject extends MdlMother
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
    );
    private $koloms = array(
        "cabang_id",
        "produk_id",
        "nama",
        "jml",
        "hpp",
        "jml_nilai",
        //        "jml_ot",
        //        "jml_nilai_ot",
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
    private $periode = array("harian", "bulanan", "tahunan", "forever");
    protected $jenisTr;
    protected $sortBy = array(
        "kolom" => "id",
        "mode" => "asc",
    );

    public function __construct()
    {
        $this->tableName = "project_pembantu_transaksi_data_cache";
        $this->tableName_master = array(
            "mutasi" => "project_pembantu_transaksi_data_cache_mutasi",
        );
    }

    //  region setter, getter
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
                $arrRekening = array();
                foreach ($array_params['loop'] as $key => $x) {

                    $value_item = trim($array_params['static']['produk_nilai']);
                    $unit = trim($array_params['static']['produk_qty']);

                    $value = $x;
//                    $position = $value > 0 ? "kredit" : "debet";
                    $position = detectRekPositionModul($key, $value, $array_params["static"]["master_jenis"]);
                    $arrRekening[] = $key;
                    $table = $this->tableName;
                    $this->tableName_mutasi = $this->tableName_master["mutasi"];

                    $_preValues = $this->cekPreValue(
                        $key,
                        $array_params['static']['cabang_id'],
                        $array_params['static']['extern_id'],// masterID
                        $array_params['static']['produk_id'],// produkID
                        $array_params['static']['fulldate']
                    );
                    showLast_query("biru");
                    if (array_key_exists("id", $_preValues["cache"]) && ($_preValues["cache"]["id"] > 0)) {
                        $mode = "update";
                        $_preValues_id = $_preValues["cache"]["id"];
                    }
                    else {
                        $mode = "insert";
                        $_preValues_id = 0;
//                        $this->outParams[$lCounter]["cache"][$mode]["tgl"] = isset($date_ex[2]) ? $date_ex[2] : date("d");
//                        $this->outParams[$lCounter]["cache"][$mode]["bln"] = isset($date_ex[1]) ? $date_ex[1] : date("m");
//                        $this->outParams[$lCounter]["cache"][$mode]["thn"] = isset($date_ex[0]) ? $date_ex[0] : date("Y");
//
                    }

                    if ($_preValues['cache']['debet'] > 0) {
                        $preNumber = detectRekByPositionModul($key, $_preValues['cache']['debet'], "debet", $array_params["static"]["master_jenis"]);
//                        $preNumber = $_preValues['cache']['debet'] * -1;
                    }
                    else {
                        $preNumber = detectRekByPositionModul($key, $_preValues['cache']['kredit'], "kredit", $array_params["static"]["master_jenis"]);
//                        $preNumber = $_preValues['cache']['kredit'];
                    }
                    if ($_preValues['cache']['qty_debet'] > 0) {
                        $preQtyNumber = detectRekByPositionModul($key, $_preValues['cache']['qty_debet'], "debet", $array_params["static"]["master_jenis"]);
//                        $preQtyNumber = $_preValues['cache']['qty_debet'] * -1;
                    }
                    else {
                        $preQtyNumber = detectRekByPositionModul($key, $_preValues['cache']['qty_kredit'], "kredit", $array_params["static"]["master_jenis"]);
//                        $preQtyNumber = $_preValues['cache']['qty_kredit'];
                    }

                    $afterNumber = $preNumber + $value;
                    $afterQtyNumber = $preQtyNumber + $unit;

//                    $afterPosition = detectRekPosition($key, $afterNumber);
//                    $afterQtyPosition = detectRekPosition($key, $afterQtyNumber);
//                    $afterPosition = $afterNumber > 0 ? "kredit" : "debet";
//                    $afterQtyPosition = $afterQtyNumber > 0 ? "kredit" : "debet";
                    $afterPosition = detectRekPositionModul($key, $afterNumber, $array_params["static"]["master_jenis"]);
                    $afterQtyPosition = detectRekPositionModul($key, $afterQtyNumber, $array_params["static"]["master_jenis"]);


//matiHEre($afterPosition);

                    $afterNumberAvg = $afterQtyNumber == 0 ? 0 : $afterNumber / $afterQtyNumber;

                    cekhitam(":: $afterNumber, val: $value,  $afterPosition, preQty: $preQtyNumber, afterQty: $afterQtyNumber");


                    //  region cache rekening pembantu
                    $pakai_cache = 1;
                    if ($pakai_cache == 1) {
                        switch ($afterPosition) {
                            case "kredit":
                                $this->outParams[$lCounter]["cache"][$mode]["kredit"] = abs($afterNumber);
                                $this->outParams[$lCounter]["cache"][$mode]["debet"] = 0;
                                break;
                            case "debet":
                                $this->outParams[$lCounter]["cache"][$mode]["kredit"] = 0;
                                $this->outParams[$lCounter]["cache"][$mode]["debet"] = abs($afterNumber);
                                break;
                            default:
                                die(lgShowAlert(__LINE__ . " gagal menentukan posisi rekening DEBET / KREDIT " . __FUNCTION__ . " on file " . __FILE__));
                                break;
                        }
                        switch ($afterQtyPosition) {
                            case "kredit":
                                $this->outParams[$lCounter]["cache"][$mode]["qty_kredit"] = abs($afterQtyNumber);
                                $this->outParams[$lCounter]["cache"][$mode]["qty_debet"] = 0;
                                break;
                            case "debet":
                                $this->outParams[$lCounter]["cache"][$mode]["qty_kredit"] = 0;
                                $this->outParams[$lCounter]["cache"][$mode]["qty_debet"] = abs($afterQtyNumber);
                                break;
                            default:
                                die(lgShowAlert(__LINE__ . " gagal menentukan posisi rekening DEBET / KREDIT " . __FUNCTION__ . " on file " . __FILE__));
                                break;
                        }
//                        switch ($position) {
//                            case "kredit":
//                                $this->outParams[$lCounter]["cache"][$mode]["saldo_kredit"] = $_preValues["cache"]["saldo_kredit"] + abs($value);
//                                $this->outParams[$lCounter]["cache"][$mode]["saldo_kredit_periode"] = $_preValues["cache"]["saldo_kredit_periode"] + abs($value);
//                                $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_kredit"] = $_preValues["cache"]["saldo_qty_kredit"] + abs($unit);
//                                $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_kredit_periode"] = $_preValues["cache"]["saldo_qty_kredit_periode"] + abs($unit);
//                                break;
//                            case "debet":
//                                $this->outParams[$lCounter]["cache"][$mode]["saldo_debet"] = $_preValues["cache"]["saldo_debet"] + abs($value);
//                                $this->outParams[$lCounter]["cache"][$mode]["saldo_debet_periode"] = $_preValues["cache"]["saldo_debet_periode"] + abs($value);
//                                $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_debet"] = $_preValues["cache"]["saldo_qty_debet"] + abs($unit);
//                                $this->outParams[$lCounter]["cache"][$mode]["saldo_qty_debet_periode"] = $_preValues["cache"]["saldo_qty_debet_periode"] + abs($unit);
//
//                                break;
//                            default:
//                                die(lgShowAlert(__LINE__ . " gagal menentukan posisi rekening DEBET / KREDIT " . __FUNCTION__ . " " . __FILE__));
//                                break;
//                        }
                        $this->outParams[$lCounter]["cache"][$mode]["rek_id"] = createRekCode($key, $array_params['static']['extern_id']);
                        $this->outParams[$lCounter]["cache"][$mode]["rekening"] = $key;
//                        $this->outParams[$lCounter]["cache"][$mode]["periode"] = $periode;
                        $this->outParams[$lCounter]["cache"][$mode]["id"] = $_preValues_id;
                        $this->outParams[$lCounter]["cache"][$mode]["harga"] = $value_item;
//                        $this->outParams[$lCounter]["cache"][$mode]["harga_avg"] = abs($afterNumberAvg);
//                        $this->outParams[$lCounter]["cache"][$mode]["harga_awal"] = abs($_preValues['cache']['harga']);

                        foreach ($array_params['static'] as $key_static => $value_static) {
                            if (in_array($key_static, $this->outFields)) {
                                $this->outParams[$lCounter]["cache"][$mode][$key_static] = $value_static;
                            }
                        }


                        $method = isset($array_params['static']['method']) ? $array_params['static']['method'] : NULL;
                        cekHere("[method: $method]");
                        switch ($method) {
                            case "approve":
                                $this->outParams[$lCounter]["cache"][$mode]["" . $method] = $_preValues['cache']['approve'] + abs($value);
                                $this->outParams[$lCounter]["cache"][$mode]["qty_approve"] = $_preValues['cache']['qty_approve'] + abs($unit);
                                break;
                            case "return":
                                $this->outParams[$lCounter]["cache"][$mode]["" . $method] = $_preValues['cache']['return'] + abs($value);
                                $this->outParams[$lCounter]["cache"][$mode]["qty_return"] = $_preValues['cache']['qty_return'] + abs($unit);
                                break;
                            case "reject":
                                $this->outParams[$lCounter]["cache"][$mode]["" . $method] = $_preValues['cache']['reject'] + abs($value);
                                $this->outParams[$lCounter]["cache"][$mode]["qty_reject"] = $_preValues['cache']['qty_reject'] + abs($unit);
                                break;
                            case "batal":
                                $this->outParams[$lCounter]["cache"][$mode]["" . $method] = $_preValues['cache']['batal'] + abs($value);
                                $this->outParams[$lCounter]["cache"][$mode]["qty_batal"] = $_preValues['cache']['qty_batal'] + abs($unit);
                                break;
                            case "create":
                            default:
//                            $this->outParams[$lCounter]["cache"][$mode]["saldo"] = $_preValues['cache']['saldo'] + abs($value);
//                            $this->outParams[$lCounter]["cache"][$mode]["qty_saldo"] = $_preValues['cache']['qty_saldo'] + abs($unit);
                                break;
                        }


                    }
                    //  endregion cache rekening pembantu

                    //  region mutasi rekening pembantu
                    $pakai_mutasi = 1;
                    if ($pakai_mutasi == 1) {
                        switch ($afterPosition) {
                            case "kredit":
                                $this->outParams[$lCounter]["mutasi"]["kredit_awal"] = $_preValues["cache"]["kredit"];
                                $this->outParams[$lCounter]["mutasi"]["kredit_akhir"] = abs($afterNumber);
                                $this->outParams[$lCounter]["mutasi"]["debet_awal"] = $_preValues["cache"]["debet"];
                                $this->outParams[$lCounter]["mutasi"]["debet_akhir"] = 0;
                                break;
                            case "debet":
                                $this->outParams[$lCounter]["mutasi"]["kredit_awal"] = $_preValues["cache"]["kredit"];
                                $this->outParams[$lCounter]["mutasi"]["kredit_akhir"] = 0;
                                $this->outParams[$lCounter]["mutasi"]["debet_awal"] = $_preValues["cache"]["debet"];
                                $this->outParams[$lCounter]["mutasi"]["debet_akhir"] = abs($afterNumber);
                                break;
                            default:
                                $this->outParams[$lCounter]["mutasi"]["kredit_awal"] = $_preValues["cache"]["kredit"];
                                $this->outParams[$lCounter]["mutasi"]["kredit_akhir"] = 0;
                                $this->outParams[$lCounter]["mutasi"]["debet_awal"] = $_preValues["cache"]["debet"];
                                $this->outParams[$lCounter]["mutasi"]["debet_akhir"] = 0;
                                break;
                        }
                        switch ($afterQtyPosition) {
                            case "kredit":
                                $this->outParams[$lCounter]["mutasi"]["qty_kredit_awal"] = $_preValues["cache"]["qty_kredit"];
                                $this->outParams[$lCounter]["mutasi"]["qty_kredit_akhir"] = abs($afterQtyNumber);
                                $this->outParams[$lCounter]["mutasi"]["qty_debet_awal"] = $_preValues["cache"]["qty_debet"];
                                $this->outParams[$lCounter]["mutasi"]["qty_debet_akhir"] = 0;
                                break;
                            case "debet":
                                $this->outParams[$lCounter]["mutasi"]["qty_kredit_awal"] = $_preValues["cache"]["qty_kredit"];
                                $this->outParams[$lCounter]["mutasi"]["qty_kredit_akhir"] = 0;
                                $this->outParams[$lCounter]["mutasi"]["qty_debet_awal"] = $_preValues["cache"]["qty_debet"];
                                $this->outParams[$lCounter]["mutasi"]["qty_debet_akhir"] = abs($afterQtyNumber);
                                break;
                            default:
                                $this->outParams[$lCounter]["mutasi"]["qty_kredit_awal"] = $_preValues["cache"]["qty_kredit"];
                                $this->outParams[$lCounter]["mutasi"]["qty_kredit_akhir"] = 0;
                                $this->outParams[$lCounter]["mutasi"]["qty_debet_awal"] = $_preValues["cache"]["qty_debet"];
                                $this->outParams[$lCounter]["mutasi"]["qty_debet_akhir"] = 0;
                                break;
                        }
                        switch ($position) {
                            case "kredit":
                                $this->outParams[$lCounter]["mutasi"]["kredit"] = abs($value);
                                $this->outParams[$lCounter]["mutasi"]["debet"] = 0;
                                $this->outParams[$lCounter]["mutasi"]["qty_kredit"] = abs($unit);
                                $this->outParams[$lCounter]["mutasi"]["qty_debet"] = 0;
                                break;
                            case "debet":
                                $this->outParams[$lCounter]["mutasi"]["debet"] = abs($value);
                                $this->outParams[$lCounter]["mutasi"]["kredit"] = 0;
                                $this->outParams[$lCounter]["mutasi"]["qty_debet"] = abs($unit);
                                $this->outParams[$lCounter]["mutasi"]["qty_kredit"] = 0;
                                break;
                            default:
                                die(lgShowAlert("Transaksi gagal, karena rekening $key gagal menentukan posisi DEBET/KREDIT."));
                                break;
                        }
                        foreach ($array_params['static'] as $key_static_mutasi => $value_static_mutasi) {
                            if (in_array($key_static_mutasi, $this->outFieldsMutasi)) {
                                $this->outParams[$lCounter]["mutasi"][$key_static_mutasi] = $value_static_mutasi;
                            }
                        }
                        $this->outParams[$lCounter]["mutasi"]["rek_id"] = createRekCode($key, $array_params['static']['extern_id']);
                        $this->outParams[$lCounter]["mutasi"]["rekening"] = $key;
                        $this->outParams[$lCounter]["mutasi"]["harga"] = abs($value_item);
                    }
                    //  endregion mutasi rekening pembantu

                    //region Exec()
                    $pakai_exec = 1;
                    if ($pakai_exec == 1) {
                        $tableName = $this->tableName;
                        $tableName_mutasi = $this->tableName_mutasi;

                        $insertIDs = array();
                        if (sizeof($this->outParams) > 0) {
                            arrPrintHijau($this->outParams);
                            foreach ($this->outParams as $lCounter => $pSpec) {
                                foreach ($pSpec as $mode => $pSpec_mode) {
                                    switch ($mode) {
                                        case "cache":
                                            foreach ($pSpec_mode as $sub_mode => $pSpec_mode_data) {
                                                $id = $pSpec_mode_data["id"];
                                                unset($pSpec_mode_data["id"]);
                                                switch ($sub_mode) {
                                                    case "insert":
                                                        $this->db->insert($tableName, $pSpec_mode_data);
                                                        $insertIDs[] = $this->db->insert_id();
                                                        cekUngu("$sub_mode :: " . $this->db->last_query());
                                                        break;
                                                    case "update":
                                                        $this->db->where('id', $id);
                                                        $insertIDs[] = $this->db->update($tableName, $pSpec_mode_data);
                                                        cekOrange("$sub_mode :: " . $this->db->last_query());
                                                        break;
                                                }
                                            }
                                            break;
                                        case "mutasi":

                                            unset($pSpec_mode["tabel"]);

                                            $this->db->insert($tableName_mutasi, $pSpec_mode);
                                            $insertIDs[] = $this->db->insert_id();
                                            cekHijau("$mode :: " . $this->db->last_query());
                                            break;
                                    }
                                }
                            }
                            $this->outParams = array();

                            if (sizeof($insertIDs) == 0) {
                                cekMerah("::: PERIODE : $periode :::");
                                return false;
                            }
                        }
                        else {
                            cekMerah("::: PERIODE : $periode :::");
                            return false;
                        }
                    }
                    //endregion

                }
            }
        }
//        mati_disini(__LINE__);
        return true;
    }

    private function cekPreValue($rek, $cabang_id, $extern_id, $produk_id, $date = NULL)
    {
        if ($date != NULL) {
            $date_ex = explode("-", $date);
            $tgl = $date_ex[2];
            $bln = $date_ex[1];
            $thn = $date_ex[0];
        }
        else {
            $tgl = date("d");
            $bln = date("m");
            $thn = date("Y");
        }
        $this->filters = array();
//        switch ($periode) {
//            case "harian":
//                $this->addFilter("tgl='$tgl'");
//                $this->addFilter("bln='$bln'");
//                $this->addFilter("thn='$thn'");
//                break;
//            case "bulanan":
//                $this->addFilter("bln='$bln'");
//                $this->addFilter("thn='$thn'");
//                break;
//            case "tahunan":
//                $this->addFilter("thn='$thn'");
//                break;
//            case "forever":
//                break;
//        }
        $this->addFilter("rekening='$rek'");
        $this->addFilter("cabang_id='$cabang_id'");
        $this->addFilter("extern_id='$extern_id'");
        $this->addFilter("produk_id='$produk_id'");
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
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $result["cache"] = array(
                    "id" => $row->id,
                    "debet" => $row->debet,
                    "kredit" => $row->kredit,
                    "qty_debet" => $row->qty_debet,
                    "qty_kredit" => $row->qty_kredit,
                    "harga" => $row->harga,
                );
            }
        }
        else {
            // bila count($tmp) == 0, maka ambil saldo periode forever dan mode insert
            $this->filters = array();
            $this->addFilter("rekening='$rek'");
            $this->addFilter("cabang_id='$cabang_id'");
            $this->addFilter("extern_id='$extern_id'");
            $this->addFilter("produk_id='$produk_id'");
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
            if (sizeof($tmp) > 0) {
                foreach ($tmp as $row) {
                    $result["cache"] = array(
                        "debet" => $row->debet,
                        "kredit" => $row->kredit,
                        "qty_debet" => $row->qty_debet,
                        "qty_kredit" => $row->qty_kredit,
                        "harga" => $row->harga,
                    );
                }
            }
            else {
                $result["cache"] = array(
                    "debet" => 0,
                    "kredit" => 0,
                    "qty_debet" => 0,
                    "qty_kredit" => 0,
                    "harga" => 0,
                );
            }
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

    //region tambahan widi cek last stok ambil dari fifo avg

    public function setKoloms($koloms)
    {
        $this->koloms = $koloms;
    }

    //endregion

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


}