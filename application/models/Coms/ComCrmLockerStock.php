<?php


class ComCrmLockerStock extends CI_Model
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $writeMode;
    private $outFields = array( // dari tabel rek_cache

//        "jenis",
        "produk_id",
        "cabang_id",
//        "nama",
//        "satuan",
//        "state",
        "qty_saldo",
//        "oleh_id",
//        "oleh_nama",
//        "transaksi_id",
//        "nomer",
//        "gudang_id",
//        "status",
//        "trash",
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function pair($inParams)
    {
        $this->inParams = $inParams;
        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            foreach ($this->inParams as $lCtr => $paramAsli) {
                /*
                 * kategori (4) jasa dan proudk paket tidak punya stok diskip aja
                 */
                $estimate_id = (isset($paramAsli['static']['estimate_id']) && $paramAsli['static']['estimate_id'] > 0) ? $paramAsli['static']['estimate_id'] : "0";
                if ($estimate_id == "0") {
                    //skip bukan dari CRM
                    $insertIDs[] = 1;
                }
                else {
                    foreach ($paramAsli['static'] as $key => $value) {
                        if (in_array($key, $this->outFields)) {
                            $this->outParams[$lCounter][$key] = $value;
                        }
                    }
                    $_preValues = $this->cekPreValue(
                        $paramAsli['static']['estimate_id'],
                        $paramAsli['static']['produk_id']
                    );
//                    ceklime($this->db->last_query());
                    if ($_preValues != null) {
                        $_preValue = $_preValues["jumlah"];
                        $_preValue_id = $_preValues["id"];
                        $this->outParams[$lCounter]["qty_saldo"] = ($paramAsli['static']['jumlah'] + $_preValue);
                        $this->outParams[$lCounter]["mode"] = "update";
                        $this->outParams[$lCounter]["id"] = $_preValue_id;
                        $this->outParams[$lCounter]["dtime_auto"] = $paramAsli['static']['dtime'];
                        $this->outParams[$lCounter]["order_id"] = $paramAsli['static']['transaksi_id'];
                        $this->outParams[$lCounter]["order_nomer"] = $paramAsli['static']['transaksi_no'];
                        $this->outParams[$lCounter]["order_oleh_id"] = $paramAsli['static']['oleh_id'];
                        $this->outParams[$lCounter]["order_dtime"] = $paramAsli['static']['dtime'];


                        if ($this->outParams[$lCounter]["qty_saldo"] < 0) {
                            arrPrint($paramAsli['static']);
                            $msg = "***stok crm bridge tidak cukup " . $paramAsli['static']['nama'] . " with state:  needed: " . $paramAsli['static']['qty_saldo'] . ", avail: " . $_preValue;
                            mati_disini($msg);
                            die(lgShowAlert($msg));

                        }
                    }
                    else {
                        $pakai_ini = 0;
                        if ($pakai_ini == 1) {
                            //gak boleh insert, insert hanya dilakukan oleh crm
                            matiHere("gagal menyimpan order dari CRM, silahkan hubungi admin untuk melakukan pengecekan.");
                            $this->outParams[$lCounter]["mode"] = "new";

                            if (isset($paramAsli['static']['rejection']) && ($paramAsli['static']['rejection'] == true)) {
                                if ($kategori_produk == "4") {
//                            $this->outParams[$lCounter]["skip"] = 1;
                                }
                                else {
                                    $msg = "pembatalan transaksi nomer " . $paramAsli['static']['transaksi_no'] . " gagal disimpan. silahkan periksa kembali atau hubungi admin.";
                                    mati_disini($msg);
                                }
                            }
                        }
                        else {
                            $this->outParams = array();
                        }


                    }

                    $pakai_exec = 1;
                    if ($pakai_exec == 1) {
                        if (sizeof($this->outParams) > 0) {
                            $insertIDs = array();
                            foreach ($this->outParams as $ctr => $params) {
                                $this->load->model("Mdls/MdlCrmDataBridge");
                                $l = new MdlCrmDataBridge();
                                $insertIDs = array();
                                $mode = $params['mode'];
                                unset($params['mode']);
                                if (isset($params["skip"]) && $params["skip"] == "1") {
                                    $insertIDs[] = 1;
                                }
                                else {
                                    switch ($mode) {
                                        case "new":
                                            $insertIDs[] = $l->addData($params);
                                            break;
                                        case "update":
                                            // mdl locker pakai where ID, dan filter default (dari model direset).
                                            $tbl_id = $params['id'];
                                            unset($params['id']);
                                            $where = array(
                                                "id" => $tbl_id,
                                            );
                                            $l->setFilters(array());
                                            $insertIDs[] = $l->updateData(
//                                            array(
//                                                "cabang_id" => $params['cabang_id'],
//                                                "gudang_id" => $params['gudang_id'],
//                                                "produk_id" => $params['produk_id'],
//                                                "state" => $params['state'],
//                                                "oleh_id" => $params['oleh_id'],
//                                                "transaksi_id" => $params['transaksi_id'],
//                                            ),
                                                $where,
                                                $params);
                                            break;
                                        default:
                                            die("unknown writemode!");
                                            break;
                                    }
                                    showLast_query("kuning");
                                    arrPrintPink($insertIDs);
                                }
                            }
                            $this->outParams = array();

                            if (sizeof($insertIDs) == 0) {
                                cekMerah("::: PERIODE : $periode :::");
                                return false;
                            }
                        }
                        else {
//                            cekMerah("::: PERIODE : $periode :::");
                            return false;
                        }
                    }

                    $pakai_cek = 0;
                    if ($pakai_cek == 1) {
                        $_preValue_locker = $this->cekLockerValidate(
                            $paramAsli['static']['jenis'],
                            $paramAsli['static']['jenis'],
                            $paramAsli['static']['cabang_id'],
                            $paramAsli['static']['produk_id'],
                            $defaultGudangID,
                            0
                        );
                    }
                }
            }
        }


        return true;

    }

    private function cekPreValue($estimate_id, $produk_id)
    {

        $this->load->model("Mdls/MdlCrmDataBridge");
        $l = new MdlCrmDataBridge();

        $l->addFilter("referensi_id='$estimate_id'");
        $l->addFilter("produk_id='$produk_id'");

        $result = array();
        $localFilters = array();
        if (sizeof($l->getfilters()) > 0) {
            foreach ($l->getfilters() as $f) {
                $tmpArr = explode("=", $f);
                $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");
            }
        }
        $query = $this->db->select()
            ->from($l->getTableName())
            ->where($localFilters)
            ->limit(1)
            ->get_compiled_select();
        $tmp = $this->db->query("{$query} FOR UPDATE")->row_array();
        if (sizeof($tmp) > 0) {
            $result = array(
                "id" => $tmp['id'],
                "jumlah" => $tmp['qty_saldo'],
            );
        }
        else {
            $result = null;
        }
        //  endregion mengambil saldo dari rek_cache

        return $result;
    }

    public function exec()
    {
        return true;
    }


}