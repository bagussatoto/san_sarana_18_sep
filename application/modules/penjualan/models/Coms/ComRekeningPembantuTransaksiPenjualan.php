<?php
/*
 * untuk rekening per transaksi_id
 *
 */

class ComRekeningPembantuTransaksiPenjualan extends MdlMother
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
//        "transaksi_id",
//        "transaksi_no",
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
        "approve",
        "return",
        "reject",
        "batal",
        "qty_approve",
        "qty_return",
        "qty_reject",
        "qty_batal",
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
//    private $periode = array("harian", "bulanan", "tahunan", "forever");
    private $periode = array("forever");
    protected $jenisTr;
    protected $sortBy = array(
        "kolom" => "id",
        "mode" => "asc",
    );

    public function __construct()
    {
        $this->tableName = "penjualan_pembantu_transaksi_cache";
        $this->tableName_master = array(
            "mutasi" => "penjualan_pembantu_transaksi_cache_mutasi",
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
        $configBalanceProtections = $this->config->item("accountBalanceProtections");
        $this->inParams = $inParams;
        arrPrintWebs($inParams);
        if (sizeof($this->inParams['loop']) > 0) {
//            $accountStrukturAlias = fetchAccountStructureAlias();
//            $accountStrukturAlias_old = fetchAccountStructureAlias_old();

            $arrTambahanMutasi = array();
            $lCounter = 0;
            foreach ($this->periode as $periode) {
                $akumJml[$periode] = array( //==define validasi debet vs kredit seimbang
                    "kredit" => 0,
                    "debet" => 0,
                );
                $arrRekening = array();
                foreach ($this->inParams['loop'] as $key => $value) {
                    $lCounter++;

                    $position = detectRekPositionModul($key, $value, $inParams["static"]["master_jenis"]);
//matiHere($position."::". $inParams["static"]["jenis"]);
                    if ($periode == "forever") {
//                        $rekName = $accountStrukturAlias[$key];
                        cekUngu("POSITION: $position :: REKENING :: [] => $value ::");
                    }

                    $arrRekening[] = $key;
//                    $table = heReturnTableName($this->tableName_master, $arrRekening);
                    $this->tableName_mutasi = $this->tableName_master["mutasi"];

                    $produk_id = isset($inParams['static']['produk_id']) ? $inParams['static']['produk_id'] : $inParams['static']['transaksi_id'];
                    $_preValues = $this->cekPreValue(
                        $key,
                        $inParams['static']['cabang_id'],
                        $periode,
                        $produk_id,
                        $inParams['static']['extern_id']
                    );
                    cekHitam($this->db->last_query());
//arrPrintWebs($_preValues);
//matiHEre();
                    if (array_key_exists("id", $_preValues["cache"]) && ($_preValues["cache"]["id"] > 0)) {
                        $mode = "update";
                        $_preValues_id = $_preValues["cache"]["id"];
                    } else {
                        $mode = "insert";
                        $_preValues_id = 0;
                        $this->outParams[$lCounter]["cache"][$mode]["tgl"] = date("d");
                        $this->outParams[$lCounter]["cache"][$mode]["bln"] = date("m");
                        $this->outParams[$lCounter]["cache"][$mode]["thn"] = date("Y");
                    }
                    $akumJml[$periode][$position] += abs($value);

                    arrPrint($_preValues);
                    if ($_preValues['cache']['debet'] > 0) {
                        $preNumber = detectRekByPositionModul($key, $_preValues['cache']['debet'], "debet", $inParams["static"]["master_jenis"]);
                    }
                    else {
                        $preNumber = detectRekByPositionModul($key, $_preValues['cache']['kredit'], "kredit", $inParams["static"]["master_jenis"]);
                    }


                    $afterNumber = $preNumber + $value;
                    cekKuning("[$preNumber] [$position] :: " . $value . " :: " . $afterNumber);
                    $afterPosition = detectRekPositionModul($key, $afterNumber, $inParams["static"]["master_jenis"]);

                    //  region cache rekening umum
                    switch ($afterPosition) {
                        case "kredit":
                            //  region cache rekening umum
                            $this->outParams[$lCounter]["cache"][$mode]["kredit"] = abs($afterNumber);
                            $this->outParams[$lCounter]["cache"][$mode]["debet"] = 0;
                            //  endregion cache rekening umum
                            break;
                        case "debet":
                            //  region cache rekening umum
                            $this->outParams[$lCounter]["cache"][$mode]["debet"] = abs($afterNumber);
                            $this->outParams[$lCounter]["cache"][$mode]["kredit"] = 0;
                            //  endregion cache rekening umum
                            break;
                        default:
                            die(lgShowAlert(__LINE__ . " gagal menentukan posisi rekening DEBET / KREDIT " . __FUNCTION__ . " " . __FILE__));
                            break;
                    }
                    switch ($position) {
                        case "kredit":
                            $this->outParams[$lCounter]["cache"][$mode]["saldo_kredit"] = $_preValues["cache"]["saldo_kredit"] + abs($value);
//                            $this->outParams[$lCounter]["cache"][$mode]["saldo_debet"] = 0;
                            $this->outParams[$lCounter]["cache"][$mode]["saldo_kredit_periode"] = $_preValues["cache"]["saldo_kredit_periode"] + abs($value);
//                            $this->outParams[$lCounter]["cache"][$mode]["saldo_debet_periode"] = 0;
                            break;
                        case "debet":
                            $this->outParams[$lCounter]["cache"][$mode]["saldo_debet"] = $_preValues["cache"]["saldo_debet"] + abs($value);
//                            $this->outParams[$lCounter]["cache"][$mode]["saldo_kredit"] = 0;
                            $this->outParams[$lCounter]["cache"][$mode]["saldo_debet_periode"] = $_preValues["cache"]["saldo_debet_periode"] + abs($value);
//                            $this->outParams[$lCounter]["cache"][$mode]["saldo_kredit_periode"] = 0;
                            break;
                        default:
                            die(lgShowAlert(__LINE__ . " gagal menentukan posisi rekening DEBET / KREDIT " . __FUNCTION__ . " " . __FILE__));
                            break;
                    }

                    $this->outParams[$lCounter]["cache"][$mode]["rek_id"] = createRekCode($key);
                    $this->outParams[$lCounter]["cache"][$mode]["rekening"] = $key;
                    $this->outParams[$lCounter]["cache"][$mode]["periode"] = $periode;
                    $this->outParams[$lCounter]["cache"][$mode]["id"] = $_preValues_id;
                    $this->outParams[$lCounter]["cache"][$mode]["tabel"] = $this->tableName;
//                    $this->outParams[$lCounter]["cache"][$mode]["rekening_2"] = isset($accountStrukturAlias_old[$key]) ? $accountStrukturAlias_old[$key] : "";
//                    $this->outParams[$lCounter]["cache"][$mode]["rekening_alias"] = isset($accountStrukturAlias[$key]) ? $accountStrukturAlias[$key] : "";

                    foreach ($this->inParams['static'] as $key_static => $value_static) {
                        if (in_array($key_static, $this->outFields)) {
                            $this->outParams[$lCounter]["cache"][$mode][$key_static] = $value_static;
                        }
                    }


                    $method = isset($this->inParams['static']['method']) ? $this->inParams['static']['method'] : NULL;
                    cekHere("[method: $method]");
                    switch ($method) {
                        case "approve":
                            $this->outParams[$lCounter]["cache"][$mode]["" . $method] = $_preValues['cache']['approve'] + abs($value);
//                            $this->outParams[$lCounter]["cache"][$mode]["qty_approve"] = $_preValues['cache']['qty_approve'] + abs($unit);
                            break;
                        case "return":
                            $this->outParams[$lCounter]["cache"][$mode]["" . $method] = $_preValues['cache']['return'] + abs($value);
//                            $this->outParams[$lCounter]["cache"][$mode]["qty_return"] = $_preValues['cache']['qty_return'] + abs($unit);
                            break;
                        case "reject":
                            $this->outParams[$lCounter]["cache"][$mode]["" . $method] = $_preValues['cache']['reject'] + abs($value);
//                            $this->outParams[$lCounter]["cache"][$mode]["qty_reject"] = $_preValues['cache']['qty_reject'] + abs($unit);
                            break;
                        case "batal":
                            $this->outParams[$lCounter]["cache"][$mode]["" . $method] = $_preValues['cache']['batal'] + abs($value);
//                            $this->outParams[$lCounter]["cache"][$mode]["qty_batal"] = $_preValues['cache']['qty_batal'] + abs($unit);
                            break;
                        case "create":
                        default:
//                            $this->outParams[$lCounter]["cache"][$mode]["saldo"] = $_preValues['cache']['saldo'] + abs($value);
//                            $this->outParams[$lCounter]["cache"][$mode]["qty_saldo"] = $_preValues['cache']['qty_saldo'] + abs($unit);
                            break;
                    }

                    //  endregion cache rekening umum


                    //  region mutasi rekening umum
                    $arrTambahanMutasi[$key][$periode . "_" . $afterPosition] = abs($afterNumber);
                    switch ($periode) {
                        case "forever":
                            switch ($afterPosition) {
                                case "kredit":
                                    //  region cache rekening umum
                                    $this->outParams[$lCounter]["mutasi"]["kredit_awal"] = $_preValues["cache"]["kredit"];
                                    $this->outParams[$lCounter]["mutasi"]["kredit_akhir"] = abs($afterNumber);

                                    $this->outParams[$lCounter]["mutasi"]["debet_awal"] = $_preValues["cache"]["debet"];
                                    $this->outParams[$lCounter]["mutasi"]["debet_akhir"] = 0;
                                    //  endregion cache rekening umum
                                    break;
                                case "debet":
                                    //  region cache rekening umum
                                    $this->outParams[$lCounter]["mutasi"]["debet_awal"] = $_preValues["cache"]["debet"];
                                    $this->outParams[$lCounter]["mutasi"]["debet_akhir"] = abs($afterNumber);

                                    $this->outParams[$lCounter]["mutasi"]["kredit_awal"] = $_preValues["cache"]["kredit"];
                                    $this->outParams[$lCounter]["mutasi"]["kredit_akhir"] = 0;
                                    //  endregion cache rekening umum
                                    break;
                                default:
                                    $this->outParams[$lCounter]["mutasi"]["debet_awal"] = $_preValues["cache"]["debet"];
                                    $this->outParams[$lCounter]["mutasi"]["debet_akhir"] = 0;

                                    $this->outParams[$lCounter]["mutasi"]["kredit_awal"] = $_preValues["cache"]["kredit"];
                                    $this->outParams[$lCounter]["mutasi"]["kredit_akhir"] = 0;
                                    break;
                            }
                            switch ($position) {
                                case "debet":
//                                    $cc = New CustomCounter();
//                                    $urut_debet = $cc->setCounterRekening($key, 0, $inParams['static']['cabang_id'], $position);
                                    $urut_kredit = 0;
                                    $this->outParams[$lCounter]["mutasi"]["debet"] = abs($value);
                                    $this->outParams[$lCounter]["mutasi"]["kredit"] = 0;
                                    break;
                                case "kredit":
//                                    $cc = New CustomCounter();
//                                    $urut_debet = 0;
//                                    $urut_kredit = $cc->setCounterRekening($key, 0, $inParams['static']['cabang_id'], $position);
                                    $this->outParams[$lCounter]["mutasi"]["kredit"] = abs($value);
                                    $this->outParams[$lCounter]["mutasi"]["debet"] = 0;
                                    break;
                                default:
                                    die(lgShowAlert("Transaksi gagal, karena rekening $key gagal menentukan posisi DEBET/KREDIT."));
                                    break;
                            }
                            foreach ($this->inParams['static'] as $key_static_mutasi => $value_static_mutasi) {
                                if (in_array($key_static_mutasi, $this->outFieldsMutasi)) {
                                    $this->outParams[$lCounter]["mutasi"][$key_static_mutasi] = $value_static_mutasi;
                                }
                            }
                            foreach ($arrTambahanMutasi[$key] as $period => $nilai_period) {
                                $this->outParams[$lCounter]["mutasi"][$period] = $nilai_period;
                            }

                            // counter urut mutasi sesuai rekeningnya
//                            $cc = New CustomCounter();
//                            $this->outParams[$lCounter]["mutasi"]["urut"] = $cc->setCounterRekening($key, 0, $inParams['static']['cabang_id'], "0");
//                            $this->outParams[$lCounter]["mutasi"]["urut_debet"] = $urut_debet;
//                            $this->outParams[$lCounter]["mutasi"]["urut_kredit"] = $urut_kredit;
                            //--------------
                            $this->outParams[$lCounter]["mutasi"]["tgl"] = date("d");
                            $this->outParams[$lCounter]["mutasi"]["bln"] = date("m");
                            $this->outParams[$lCounter]["mutasi"]["thn"] = date("Y");
                            //--------------
                            $this->outParams[$lCounter]["mutasi"]["rek_id"] = createRekCode($key);
                            $this->outParams[$lCounter]["mutasi"]["rekening"] = $key;
                            $this->outParams[$lCounter]["mutasi"]["tabel"] = $this->tableName_mutasi;
                            break;
                    }
                    //  endregion mutasi rekening umum
                }


            }

        }

        arrPrintWebs($this->outParams[$lCounter]);
//        matiHere(__LINE__);

        if (sizeof($this->outParams) > 0) {
            return true;
        } else {
            return false;
        }

        return true;
    }

    private function cekPreValue($rek, $cabang_id, $periode, $transaksi_id, $pihak_id)
    {
        $tgl = date("d");
        $bln = date("m");
        $thn = date("Y");

        $this->filters = array();

        switch ($periode) {
            case "harian":
                $this->addFilter("tgl='$tgl'");
                $this->addFilter("bln='$bln'");
                $this->addFilter("thn='$thn'");
                break;
            case "bulanan":
                $this->addFilter("bln='$bln'");
                $this->addFilter("thn='$thn'");
                break;
            case "tahunan":
                $this->addFilter("thn='$thn'");
                break;
            case "forever":
                break;
        }

        $this->addFilter("rekening='$rek'");
        $this->addFilter("cabang_id='$cabang_id'");
        $this->addFilter("periode='$periode'");
        $this->addFilter("extern_id='$pihak_id'");//pihakid vendor/supplier/customer
        $this->addFilter("produk_id='$transaksi_id'");//pihakid vendor/supplier/customer

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

        $tmp = $this->db->query("{$query} FOR UPDATE")->row_array();
//        cekBiru($this->db->last_query());
//matiHEre(__LINE__);

        if (sizeof($tmp) > 0) {
            // bila count($tmp) > 0, maka ambil saldo periode sendiri, dan mode update
//            foreach ($tmp as $row) {
//                $result["cache"] = array(
//                    "id"     => $row->id,
//                    "debet"  => $row->debet,
//                    "kredit" => $row->kredit,
//
//                );
//            }
            $result["cache"] = array(
                "id" => $tmp['id'],
                "debet" => $tmp['debet'],
                "kredit" => $tmp['kredit'],

                // saldo bawah
                "saldo_debet" => $tmp['saldo_debet'],
                "saldo_kredit" => $tmp['saldo_kredit'],
                "saldo_debet_periode" => $tmp['saldo_debet_periode'],
                "saldo_kredit_periode" => $tmp['saldo_kredit_periode'],
            );
        } else {
            // bila count($tmp) == 0, maka ambil saldo periode forever dan mode insert
            $rekCat = detectRekCategory($rek);


            $periode = "forever";
            $this->filters = array();
            $this->addFilter("rekening='$rek'");
            $this->addFilter("cabang_id='$cabang_id'");
            $this->addFilter("periode='$periode'");
            $this->addFilter("extern_id='$pihak_id'");//pihakid vendor/supplier/customer
            $this->addFilter("produk_id='$transaksi_id'");//pihakid vendor/supplier/customer
            switch ($periode) {
                case "harian":
                    $this->addFilter("tgl='$tgl'");
                    $this->addFilter("bln='$bln'");
                    $this->addFilter("thn='$thn'");
                    break;
                case "bulanan":
                    $this->addFilter("bln='$bln'");
                    $this->addFilter("thn='$thn'");
                    break;
                case "tahunan":
                    $this->addFilter("thn='$thn'");
                    break;
                case "forever":
                    break;
            }

            $localFilters = array();
            if (sizeof($this->filters) > 0) {
                foreach ($this->filters as $f) {
                    $tmpArr = explode("=", $f);
//                    $localFilters[$tmpArr[0]]=$tmpArr[1];
                    $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");
                }
            }

            $query = $this->db->select()
                ->from($this->tableName)
                ->where($localFilters)
                ->limit(1)
                ->get_compiled_select();

            $tmp = $this->db->query("{$query} FOR UPDATE")->row_array();

            if (sizeof($tmp) > 0) {
//                foreach ($tmp as $row) {
//                    $result["cache"] = array(
//                        "debet"  => $row->debet,
//                        "kredit" => $row->kredit,
//                    );
//                }
                $result["cache"] = array(
//                    "id"     => $tmp['id'],
                    "debet" => $tmp['debet'],
                    "kredit" => $tmp['kredit'],

                    // saldo bawah
                    "saldo_debet" => $tmp['saldo_debet'],
                    "saldo_kredit" => $tmp['saldo_kredit'],
                    "saldo_debet_periode" => 0,
                    "saldo_kredit_periode" => 0,
                );
            } else {
                $result["cache"] = array(
                    "debet" => 0,
                    "kredit" => 0,

                    // saldo bawah
                    "saldo_debet" => 0,
                    "saldo_kredit" => 0,
                    "saldo_debet_periode" => 0,
                    "saldo_kredit_periode" => 0,
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
        $insertIDs = array();
        if (sizeof($this->outParams) > 0) {
            foreach ($this->outParams as $lCounter => $pSpec) {
                foreach ($pSpec as $mode => $pSpec_mode) {
                    switch ($mode) {
                        case "cache":

                            foreach ($pSpec_mode as $sub_mode => $pSpec_mode_data) {
                                $id = $pSpec_mode_data["id"];
                                $tableName = $pSpec_mode_data["tabel"];
                                unset($pSpec_mode_data["id"]);
                                unset($pSpec_mode_data["tabel"]);

                                switch ($sub_mode) {
                                    case "insert":

                                        $this->db->insert($tableName, $pSpec_mode_data);
                                        $insertIDs[] = $this->db->insert_id();
                                        cekBiru($this->db->last_query());

                                        break;
                                    case "update":

                                        $this->db->where('id', $id);
                                        $insertIDs[] = $this->db->update($tableName, $pSpec_mode_data);
                                        cekOrange($this->db->last_query());

                                        break;
                                }
                            }
                            break;
                        case "mutasi":
                            $tableName_mutasi = $pSpec_mode["tabel"];
                            unset($pSpec_mode["tabel"]);

                            $this->db->insert($tableName_mutasi, $pSpec_mode);
                            $insertIDs[] = $this->db->insert_id();
                            cekHijau($this->db->last_query());
                            break;
                    }
                }
            }
//            matiHere(__LINE__ . " :: " . __FUNCTION__);
            if (sizeof($insertIDs) > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }


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
        } else {
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