<!--rewrite @2024-03-11-->
<?php

class Kas extends MX_Controller
{
    protected $transaksi_nilai;

    public function getTransaksiNilai()
    {
        return $this->transaksi_nilai;
    }

    public function setTransaksiNilai($transaksi_nilai)
    {
        $this->transaksi_nilai = $transaksi_nilai;
    }

    protected $nilai_key;

    public function getNilaiKey()
    {
        return $this->nilai_key;
    }

    public function setNilaiKey($nilai_key)
    {
        $this->nilai_key = $nilai_key;
    }

    protected $kolom_nilai;

    public function getKolomNilai()
    {
        return $this->kolom_nilai;
    }

    public function setKolomNilai($kolom_nilai)
    {
        $this->kolom_nilai = $kolom_nilai;
    }

    protected $pivotParent;

    protected $pivotChildFirst;

    public function getPivotParent()
    {
        return $this->pivotParent;
    }

    public function setPivotParent($pivotParent)
    {
        $this->pivotParent = $pivotParent;
    }

    public function getPivotChildFirst()
    {
        return $this->pivotChildFirst;
    }

    public function setPivotChildFirst($pivotChildFirst)
    {
        $this->pivotChildFirst = $pivotChildFirst;
    }

    //-----------------------------------------------------------geter-setter U+02191 \0041

    public function __construct()
    {
        $this->modul_path = base_url() . "penjualan/";
        $this->default_limit = 200;
        // $this->jenisTr = "582";
        $this->jenisTr_penjualan = "582spd";
        $this->jenisTrs = array("582spd", "982", "382spd");
    }

    public function callTransaksiCounterJenis($jenis = "")
    {
        $tbl_1 = "transaksi";
        $coloms = array(
            "id",
            "_company_stepCode",
            "_company_jenisTr",
            "customers_id",
            "customers_nama",
        );
        $this->db->select($coloms);
        $wheres = array(
            // "jenis" => "4822",
            "jenis" => $jenis,
        );
        // $this->db->where($wheres);
        $this->db->order_by("dtime", "asc");
        $srcs = $this->db->get($tbl_1)->result_array();

        foreach ($srcs as $src) {
            $tr_id = $src['id'];
            // $sisa = $src['sisa'];

            $src_datas[$tr_id] = $src;
        }

        return $src_datas;
    }

    public function createMasterData_ori($data_raw)
    {
        $counterJenis = $this->callTransaksiCounterJenis();
        $itemtambahan = array();
        foreach ($data_raw as $item) {
            $transaksi_nomer = $item['transaksi_no'];
            $transaksi_produk_id = $item['produk_id'];
            $transaksi_id = $item['transaksi_id'];
            $transaksi_id_so = $item['transaksi_id_2'];
            $transaksi_sub_ppn_nilai = $item['sub_ppn_nilai'];
            $transaksi_dtime = $item['dtime'];
            $transaksi_jam = formatTanggal($transaksi_dtime, "H:i");
            $transaksi_tanggal = formatTanggal($transaksi_dtime, "d F Y");

            $transaksi_kredit = $item['kredit'];
            // $transaksi_ppn = $transaksi_kredit * (11/100);
            // $transaksi_inc_ppn = $transaksi_kredit + $transaksi_ppn;
            $transaksi_inc_ppn = $transaksi_kredit + $transaksi_sub_ppn_nilai;

            // $tagihan = isset($tagihans[$transaksi_id]) ? $tagihans[$transaksi_id] : array();
            $pembayaran_nama = $item['pembayaran_nama'];

            // if ($pembayaran_nama == "cash") {
            //     $tagihan = isset($tagihans[$transaksi_id_so]) ? $tagihans[$transaksi_id_so] : array();
            //     // $itemtambahan['due_date'] = $transaksi_dtime;
            //     // $itemtambahan['umur_now'] = umurDay($transaksi_dtime);
            //     $umur_d = 0;
            //     $itemtambahan['due_date'] = "-";
            //     $itemtambahan['umur_now'] = "-";
            //
            // }
            // else {
            //     $tagihan = isset($tagihans[$transaksi_id]) ? $tagihans[$transaksi_id] : array();
            //     $tagihanDuedate = isset($tagihanDuedates[$transaksi_id]) ? $tagihanDuedates[$transaksi_id] : array();
            //     $dueDate = $tagihanDuedate['due_date'];
            //     if (strtotime($dueDate) !== false) {
            //         $umur_d = umurDay($dueDate);
            //         $itemtambahan['due_date'] = isset($tagihanDuedate['due_date']) ? $dueDate : null;
            //         $itemtambahan['umur_now'] = $umur_d;
            //     }
            //     else {
            //         $umur_d = '';
            //         $itemtambahan['due_date'] = null;
            //         $itemtambahan['umur_now'] = "";
            //     }
            // }
            // if ($transaksi_id == '27137') {
            //
            //     // cekLime($transaksi_id);
            //     // arrPrintPink($tagihan['sisa']);
            // }
            // break;
            // $tagNilai = isset($tagihan['sisa']) ? $tagihan['sisa'] : 0;
            //
            // if ($umur_d === 0) {
            //     $umur_status = "0 hari";
            // }
            // elseif ($umur_d < 0) {
            //     $umur_status = "<g>" . ($umur_d * -1) . " hari</g>";
            // }
            // elseif ($umur_d > 0 && $tagNilai > 100) {
            //     $umur_status = "<r>telat " . ($umur_d) . " hari</r>";
            // }
            // else {
            //     $umur_status = "-";
            // }
            // $itemtambahan['umur_status'] = $umur_status;


            // $itemtambahan['sisa_tagihan'] = $tagNilai;
            // $itemtambahan['total_tagihan'] = isset($tagihan['tagihan']) ? $tagihan['tagihan'] : 0;;
            // $itemtambahan['total_terbayar'] = isset($tagihan['terbayar']) ? $tagihan['terbayar'] : 0;;
            $itemtambahan['transaksi_tanggal'] = $transaksi_tanggal;
            $itemtambahan['transaksi_jam'] = $transaksi_jam;

            // $itemtambahan['c_ppn'] = $transaksi_ppn;
            // $itemtambahan['c_sub_total'] = $transaksi_inc_ppn;
            $counters = $counterJenis[$transaksi_id];
            $counterNum = $counters["_company_stepCode"];
            $itemtambahan['counter_spd'] = $counterNum;
            $itemtambahan['nomer_counter'] = $transaksi_nomer . "-" . $counterNum;
            // $produk_speks = $spekProduks[$transaksi_produk_id];
            // $itemtambahan['merek_nama'] = $produk_speks->merek_nama;

            $masterData[] = $item + $itemtambahan;
        }

        return $masterData;
    }

    public function createMasterData($data_raw, $jointTable = array())
    {

        // arrPrintWebs($jointTable);
        $configUiMaster = $this->config->item("heTransaksi_ui");
        //       arrprint($configUiMaster);
        $jnLabels = array();
        foreach ($configUiMaster as $jnt => $dataLabeltrans) {
            //            arrPrint($dataLabeltrasn);
            $jnLabels[$jnt] = $dataLabeltrans["label"];
            //            foreach ($dataLabeltrans["steps"] as $iiLabels){
            ////                arrPrint($iiLabels);
            //                $jnLabels[$iiLabels["target"]]=$iiLabels["label"];
            //            }
            //            arrPrint($dataLabeltrasn["steps"]);
        }
        //        arrprint($jnLabels);
        $counterJenis = $this->callTransaksiCounterJenis();
        //        arrPrint($counterJenis);
        //        arrPrint($data_raw);
        //        matiHere(__LINE__);
        $itemtambahan = array();
        $itembank = array();
        foreach ($data_raw as $item) {
            $transaksi_nomer = $item['transaksi_no'];
            $transaksi_produk_id = $item['produk_id'];
            $transaksi_id = $item['transaksi_id'];
            $transaksi_id_so = $item['transaksi_id_2'];
            $transaksi_sub_ppn_nilai = $item['sub_ppn_nilai'];
            $transaksi_dtime = $item['dtime'];
            $transaksi_jam = formatTanggal($transaksi_dtime, "H:i");
            $transaksi_tanggal = formatTanggal($transaksi_dtime, "d F Y");
            $jenisTr = $item['jenis'];


            $transaksi_kredit = $item['kredit'];
            // $transaksi_ppn = $transaksi_kredit * (11/100);
            // $transaksi_inc_ppn = $transaksi_kredit + $transaksi_ppn;
            $transaksi_inc_ppn = $transaksi_kredit + $transaksi_sub_ppn_nilai;

            // $tagihan = isset($tagihans[$transaksi_id]) ? $tagihans[$transaksi_id] : array();
            $pembayaran_nama = $item['pembayaran_nama'];

            // if ($pembayaran_nama == "cash") {
            //     $tagihan = isset($tagihans[$transaksi_id_so]) ? $tagihans[$transaksi_id_so] : array();
            //     // $itemtambahan['due_date'] = $transaksi_dtime;
            //     // $itemtambahan['umur_now'] = umurDay($transaksi_dtime);
            //     $umur_d = 0;
            //     $itemtambahan['due_date'] = "-";
            //     $itemtambahan['umur_now'] = "-";
            //
            // }
            // else {
            //     $tagihan = isset($tagihans[$transaksi_id]) ? $tagihans[$transaksi_id] : array();
            //     $tagihanDuedate = isset($tagihanDuedates[$transaksi_id]) ? $tagihanDuedates[$transaksi_id] : array();
            //     $dueDate = $tagihanDuedate['due_date'];
            //     if (strtotime($dueDate) !== false) {
            //         $umur_d = umurDay($dueDate);
            //         $itemtambahan['due_date'] = isset($tagihanDuedate['due_date']) ? $dueDate : null;
            //         $itemtambahan['umur_now'] = $umur_d;
            //     }
            //     else {
            //         $umur_d = '';
            //         $itemtambahan['due_date'] = null;
            //         $itemtambahan['umur_now'] = "";
            //     }
            // }
            // if ($transaksi_id == '27137') {
            //
            //     // cekLime($transaksi_id);
            //     // arrPrintPink($tagihan['sisa']);
            // }
            // break;
            // $tagNilai = isset($tagihan['sisa']) ? $tagihan['sisa'] : 0;
            //
            // if ($umur_d === 0) {
            //     $umur_status = "0 hari";
            // }
            // elseif ($umur_d < 0) {
            //     $umur_status = "<g>" . ($umur_d * -1) . " hari</g>";
            // }
            // elseif ($umur_d > 0 && $tagNilai > 100) {
            //     $umur_status = "<r>telat " . ($umur_d) . " hari</r>";
            // }
            // else {
            //     $umur_status = "-";
            // }
            // $itemtambahan['umur_status'] = $umur_status;


            // $itemtambahan['sisa_tagihan'] = $tagNilai;
            // $itemtambahan['total_tagihan'] = isset($tagihan['tagihan']) ? $tagihan['tagihan'] : 0;;
            // $itemtambahan['total_terbayar'] = isset($tagihan['terbayar']) ? $tagihan['terbayar'] : 0;;
            $itemtambahan['transaksi_tanggal'] = $transaksi_tanggal;
            $itemtambahan['transaksi_jam'] = $transaksi_jam;

            // $itemtambahan['c_ppn'] = $transaksi_ppn;
            // $itemtambahan['c_sub_total'] = $transaksi_inc_ppn;
            $counters = $counterJenis[$transaksi_id];
            $counterNum = $counters["_company_stepCode"];
            $itemtambahan['counter_spd'] = $counterNum;
            $itemtambahan['nomer_counter'] = $transaksi_nomer . "-" . $counterNum;
            if (!isset($item["pihak_id"])) {
                $itemtambahan['pihak_id'] = $counters["customers_id"];
                $itemtambahan['pihak_nama'] = $counters["customers_nama"];
            }
            if (!isset($item["extern2_id"])) {
                //                $jnLabels
                $itemtambahan['extern2_id'] = $jenisTr;
                $itemtambahan['extern2_nama'] = $jnLabels[$jenisTr];
            }
            else {
                if ($item["extern2_id"] == 0) {
                    $item['extern2_id'] = $jenisTr;
                    $item['extern2_nama'] = $jnLabels[$jenisTr];
                }
            }

            // $produk_speks = $spekProduks[$transaksi_produk_id];
            // $itemtambahan['merek_nama'] = $produk_speks->merek_nama;

            if (count($jointTable) > 0) {
                //joint bank
                // arrPrint($item["extern_id"]);
                // arrPrintPink($jointTable);
                $itembank = isset($jointTable[$item["extern_id"]]) ? $jointTable[$item["extern_id"]] : array();
            }
            else {
                $itembank = array();
            }
            // arrPrintHijau($item);
            // arrPrint($itemtambahan);
            // arrPrintKuning($itembank);

            $masterData[] = $item + $itemtambahan + $itembank;
        }

        return $masterData;
    }

    public function creatPivot($data)
    {
        // Fungsi untuk membuat laporan pivot
        $parent = isset($this->pivotParent) ? $this->pivotParent : matiHere("pivotParent harap di set");
        $childFirst = isset($this->pivotChildFirst) ? $this->pivotChildFirst : matiHere("pivotChildFirst harap di set");
        $pivot = array();
        $pivotKoloms = array(
            "dibayar" => array(
                "summary" => true
            )
        );

        foreach ($data as $row) {
            $transaksi_id = $row['transaksi_id'];
            $transaksi_no = $row['transaksi_no'];
            $extern_nama = $row['extern_nama'];
            // $merek_nama = $row['merek_nama'];
            $produk_nama = $row['nomer_counter'];
            $transaksi_description = $row['extern2_nama'];
            $transaksi_description_note = $row['extern_label2'];
            $ppn = $row['ppn'];
            $qty_kredit = $row['total_terbayar'];
            $sisa_tagihan = $row['sisa_tagihan'];
            $sub_harga_include_ppn = $row['sub_harga_include_ppn'];
            $harga = $row['harga'];
            $harga = $row['total_tagihan'];
            $pihak_nama = $row['pihak_nama'];
            $dibayar = $row['dibayar'];

            /* -------------------------------
             * menjadi konci pembentuk data pivot
             * --------------------- ----------------------*/
            $label_1 = $row[$parent];
            $label_2 = $produk_id = $row[$childFirst];

            foreach ($pivotKoloms as $pivotKolom => $params) {
                $nilai = $row[$pivotKolom];
                if (isset($params['summary']) && $params['summary'] == true) {
                    if (!isset($pivot[$label_1][$pivotKolom])) {
                        $pivot[$label_1][$pivotKolom] = 0;
                    }

                    $pivot[$label_1]["total_" . $pivotKolom] += $nilai;
                }
                else {

                }
            }

            // ---------------------------------------------

            if (!isset($pivot[$label_1])) {
                $pivot[$label_1] = array(
                    // 'total_'.$produk_nama => 0,
                    'total_qty_kredit'      => 0,
                    'total_harga'           => 0,
                    'count'                 => 0,
                    'sub_harga_include_ppn' => 0,
                );
            }

            $pivot[$label_1]['sub_harga_include_ppn'] += $sub_harga_include_ppn;
            $pivot[$label_1]['total_harga'] += $harga;
            $pivot[$label_1]['total_qty_kredit'] += $qty_kredit;
            $pivot[$label_1]['total_sisa_tagihan'] += $sisa_tagihan;
            $pivot[$label_1]['total_ppn'] += $ppn;
            $pivot[$label_1]['count']++;

            /* -----------------------------------
             * angkor untuk layer utama (objek)
             * ------------------------------------*/
            $pivot[$label_1]['label'] = $label_1;
            // --------------------------------------------------------------------layer kedua--------
            if (!isset($pivot[$label_1]['rincian'][$label_2]['sub_nilai'])) {
                $pivot[$label_1]['rincian'][$label_2]['sub_nilai'] = 0;
            }
            $pivot[$label_1]['rincian'][$label_2]['sub_nilai'] += $qty_kredit;

            if (!isset($pivot[$label_1]['rincian'][$label_2]['total'])) {
                $pivot[$label_1]['rincian'][$label_2]['total'] = 0;
            }
            $pivot[$label_1]['rincian'][$label_2]['total'] += $harga;

            if (!isset($pivot[$label_1]['rincian'][$label_2]['sub_sisa_tagihan'])) {
                $pivot[$label_1]['rincian'][$label_2]['sub_sisa_tagihan'] = 0;
            }
            $pivot[$label_1]['rincian'][$label_2]['sub_sisa_tagihan'] += $sisa_tagihan;

            if (!isset($pivot[$label_1]['rincian'][$label_2]['sub_ppn'])) {
                $pivot[$label_1]['rincian'][$label_2]['sub_ppn'] = 0;
            }
            $pivot[$label_1]['rincian'][$label_2]['sub_ppn'] += $ppn;

            if (!isset($pivot[$label_1]['rincian'][$label_2]['dibayar'])) {
                $pivot[$label_1]['rincian'][$label_2]['dibayar'] = 0;
            }
            $pivot[$label_1]['rincian'][$label_2]['dibayar'] += $dibayar;
            // ----------------------------------------------
            $pivot[$label_1]['rincian'][$label_2]['label'] = $produk_nama;
            $pivot[$label_1]['rincian'][$label_2]['pihak_nama'] = $pihak_nama;
            $pivot[$label_1]['rincian'][$label_2]['note'] = $transaksi_description;
            $pivot[$label_1]['rincian'][$label_2]['transaksi_id'] = $transaksi_id;
        }

        return $pivot;

    }

    public function viewSummary($row_datas)
    {
        // arrPrint($row_datas);
        $arrSubjects = array(
            "pembayaran_nama" => "pembayaran_nama",
            "salesman_id"     => "salesman_nama",
            "pihak_id"        => "pihak_nama",
            "produk_id"       => "produk_nama",
        );

        $kolom_transaki_nilai = isset($this->nilai_key) ? $this->nilai_key : matiHere("tolong diset nilai_key");
        // $kolom_nilai = isset($this->kolom_nilai) ? $this->kolom_nilai : matiHere("tolong diset kolom_nilai");
        $kolom_nilai = isset($this->kolom_nilai) ? $this->kolom_nilai : array();

        $arrSubjects = array(
            "kategori_id"     => array(
                "label" => "kategori",
                "kolom" => "kategori_nama",
            ),
            "pembayaran_nama" => array(
                "label" => "cara pembayaran",
                "kolom" => "pembayaran_nama",
            ),
            "sales_admin_id"  => array(
                "label" => "sales admin",
                "kolom" => "sales_admin_nama",
            ),
            "salesman_id"     => array(
                "label" => "salesman",
                "kolom" => "salesman_nama",
            ),
            "pihak_id"        => array(
                "label" => "konsumen",
                "kolom" => "pihak_nama",
            ),
            "transaksi_id"    => array(
                "label" => "transaksi",
                "kolom" => array(
                    "transaksi_no"        => array(
                        "label" => "no. pakinglist"
                    ),
                    "pihak_nama"          => array(
                        "label" => "konsumen",
                    ),
                    $kolom_transaki_nilai => array(
                        "label"   => "nilai",
                        "summary" => true,
                        "attr"    => "class='text-right'",
                    ),
                ),
            ),
            "produk_id"       => array(
                "label" => "produk",
                "kolom" => array(
                    "produk_nama"         => array(
                        "label" => "produk"
                    ),
                    "qty_kredit"          => array(
                        "label"   => "jml",
                        "summary" => true,
                        "attr"    => "class='text-right'",
                    ),
                    $kolom_transaki_nilai => array(
                        "label"   => "nilai",
                        "summary" => true,
                        "attr"    => "class='text-right'",
                    ),
                )
            ),
        );

        if (isset($this->kolom_nilai)) {
            foreach ($kolom_nilai as $kolom => $nilai) {
                if (is_array($nilai)) {
                    $arrSubjects[$kolom] = $nilai;
                }
                else {
                    unset($arrSubjects[$kolom]);
                }
            }
        }


        if (ipadd() == "202.65.117.72") {
            $arrSubjects["jenis"] =
                array(
                    "label" => "jenis",
                    "kolom" => "jenis",
                );
        }
        foreach ($row_datas as $row_data) {
            // arrPrint($row_data);
            // break;
            // $sub_harga_include_ppn = $row_data['sub_harga_include_ppn'];
            // $sub_harga_include_ppn = $row_data['c_sub_total'];
            $sub_harga_include_ppn = $row_data[$kolom_transaki_nilai];

            foreach ($arrSubjects as $keySubject => $valSubject) {
                // arrPrint($valSubject);
                $subject = $row_data[$keySubject];

                $koloms = $valSubject['kolom'];
                $strSubject = $row_data[$koloms];
                // arrPrintKuning($koloms);
                if (is_array($koloms)) {
                    // arrPrint($koloms);
                    $strSubjectLabel[$keySubject] = $valSubject['label'];
                    foreach ($koloms as $strSubject => $kolomParams) {
                        // cekHere("$keySubject||$strSubject" );
                        $label = $kolomParams['label'];
                        $sub_strSubject = $row_data[$strSubject];
                        if (isset($kolomParams['summary']) && $kolomParams['summary'] == true) {


                            if (!isset($summary[$keySubject][$subject][$strSubject])) {
                                $summary[$keySubject][$subject][$strSubject] = 0;
                            }
                            $summary[$keySubject][$subject][$strSubject] += $sub_strSubject;
                        }
                        else {
                            $summary[$keySubject][$subject][$strSubject] = $sub_strSubject;
                        }

                        $strSummary[$keySubject]['header'][$strSubject] = $kolomParams;
                    }
                    // if (!isset($summary[$keySubject][$subject])) {
                    //     $summary[$keySubject][$subject] = 0;
                    // }
                    // $summary[$keySubject][$subject] += $sub_harga_include_ppn;

                }
                else {
                    if (!isset($summary[$keySubject][$subject])) {
                        $summary[$keySubject][$subject] = 0;
                    }
                    $summary[$keySubject][$subject] += $sub_harga_include_ppn;

                    $strSummary[$keySubject][$subject] = $strSubject;
                    $strSubjectLabel[$keySubject] = $valSubject['label'];
                }

            }
        }

        // arrPrintKuning($strSummary);
        // arrPrintKuning($summary);
        $summari_datas = array();
        $summari_datas['nilai'] = $summary;
        $summari_datas['label'] = $strSummary;
        $summari_datas['kolom_key'] = $strSubjectLabel;

        return $summari_datas;
        // $data = array(
        //     "mode"        => "viewSummary",
        //     "summary"        => $summary,
        //     "strSummary"        => $strSummary,
        // );
        //
        // $this->load->view("laporan_periode", $data);
    }

    /* -------------------------------------------------------------
     * kas masuk
     * -------------------------------------------------------------*/
    public function getRawIn($date1, $date2)
    {
        // $jenis = "5822spd";
        $tbl_1 = "__raw_rek_pembantu__1010010010";
        // $where_2 = array(
        //     "link_id" => "0",
        // );
        // $this->db->select("produk_id,dtime,fulldate");
        $transaksi_jenis_masuk = array(
            "4464",
            "749",
            "4467",
        );
        $transaksi_jenis_keluar = array(
            "464", "489"
        );
        $this->db->where_in("jenis", $transaksi_jenis_masuk);
        $wheres = "DATE(dtime) >= '$date1' AND DATE(dtime) <= '$date2'";
        $this->db->where($wheres);
        $this->db->order_by("dtime", "asc");
        $tmpA = $this->db->get($tbl_1)->result_array();
        // showLast_query("biru");

        return $tmpA;
    }

    public function cekRowIn()
    {
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');

        $jenis = "467";
        // $tbl_1 = "__raw_rek_pembantu__4010";
        // // $where_2 = array(
        // //     "link_id" => "0",
        // // );
        // // $this->db->select("produk_id,dtime,fulldate");
        // $wheres = "jenis='$jenis' AND DATE(dtime) >= '$date1' AND DATE(dtime) <= '$date2'";
        // $this->db->where($wheres);
        // $this->db->order_by("dtime", "asc");
        // $tmpA = $this->db->get($tbl_1)->result_array();
        // showLast_query("biru");
        // arrPrintHijau(array_slice($tmpA,1,1));
        // $counterJenis = $this->callTransaksiCounterJenis(false);
        // $tagihans = $this->callPaymentSource($jenis);
        // showLast_query("kuning");
        // $tagihanDuedates = $this->callTransaksiDueDate();
        // showLast_query("merah");
        $tmpA = $this->getRawIn($date1, $date2);

        showLast_query("biru");
        // arrPrint(array_slice($tmpA,1,1));
        // $this->load->model("Mdls/MdlProduk");
        // $pr = new MdlProduk();
        // $spekProduks = $pr->callSpecs();

        // arrPrintKuning($tagihans);
        // matiHere(__LINE__);
        $masterData = $this->createMasterData($tmpA);
        // arrPrintHijau(array_slice($masterData,1,1));
        // arrPrint(array_slice($masterData,10));
        // arrPrint(($masterData));


        $arrHeaders = array(
            "pihak_nama"    => array(
                "label" => "diterima dari",
            ),
            "extern2_nama"  => array(
                "label"      => "keterangan",
                "data_order" => "due_date",
            ),
            "transaksi_id"  => array(
                "label" => "trid",
            ),
            "nomer_counter" => array(
                "label"      => "nomer",
                "data_order" => ""
            ),
            "dtime"         => array(
                "label"  => "tanggal",
                "format" => "formatField_he_format",
            ),
            // "nomer_counter"       => array(
            //     "label" => "no grn",
            // ),

            // "transaksi_jam"     => array(
            //     "label" => "jam",
            //     // "format"     => "formatField_he_format",
            // ),
            "produk_kode"   => array(
                "label" => "bank",
            ),
            "produk_nama"   => array(
                "label" => "rekening",
            ),


            // "transaksi_id"          => array(
            //     "label" => "trid",
            // ),


            // "transaksi_no_2"    => array(
            //     "label" => "no. SO",
            //     "type"  => "string",
            // ),
            // "transaksi_no_3"    => array(
            //     "label" => "no. PPL",
            //     "type"  => "string",
            // ),
            // "transaksi_no_4"    => array(
            //     "label" => "no. PL",
            //     "type"  => "string",
            //     // "attr"       => "class='text-right bg-warning'",
            // ),
            // "transaksi_no_inv"  => array(
            //     "label" => "no. invoice",
            //     "type"  => "string",
            // ),
            // "pembayaran_nama"   => array(
            //     "label" => "pembayaran",
            // ),
            // // -------------------------------------------
            // "merek_nama"       => array(
            //     "label" => "nama",
            //     "type"  => "string",
            //     "attr"  => "class='bg-warning'",
            // ),
            // "produk_kode"       => array(
            //     "label" => "produk sku",
            //     "type"  => "string",
            //     "attr"  => "class='bg-warning'",
            // ),
            // "produk_nama"       => array(
            //     "label" => "produk",
            //     "type"  => "string",
            //     "attr"  => "class='bg-warning'",
            // ),
            // "outdoor_nama"          => array(
            //     "label" => "outdoor",
            //     "type"  => "string",
            // ),
            // "indoor_nama_1"         => array(
            //     "label" => "intdoor",
            //     "type"  => "string",
            // ),
            // "qty_kredit"        => array(
            //     "label" => "jumlah",
            //     "type"  => "integer",
            //     "attr"  => "class='text-right bg-warning'",
            // ),
            // "harga"             => array(
            //     "label"      => "harga per unit",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            // ),
            // "kredit"            => array(
            //     "label"      => "jumlah kena pajak",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            // ),
            // "ppn_nilai"     => array(
            //     "label"      => "pajak nilai",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            // ),

            /*----pajak ppn---*/
            // "c_ppn"     => array(
            //     "label"      => "pajak",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            // ),

            // "sub_ppn_nilai"  => array(
            //     "label"      => "pajak",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            // ),
            // // ---------------------
            // "c_sub_total"    => array(
            //     "label"      => "total penjualan",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            // ),

            // "harga_include_ppn"     => array(
            //     "label"      => "harga jual",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            // ),
            // "sub_harga_include_ppn" => array(
            //     "label"      => "sub harga jual",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            // ),
            // "tagihan_include_ppn" => array(
            //     "label"      => "nilai tagihan",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "summary"    => false,
            //     // "attr"       => "class='text-right bg-warning'",
            // ),
            // "kredit"    => array(
            //     "label"      => "um",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     // "attr"       => "class='text-right bg-warning'",
            // ),
            "dibayar"       => array(
                "label"      => "diterima inc. ppn",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                // "attr"       => "class='text-right bg-warning'",
            ),
            // "sisa"          => array(
            //     "label"      => "ssia",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     // "attr"       => "class='text-right bg-warning'",
            // ),
            "ppn_nilai"     => array(
                "label"      => "ppn",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                // "attr"       => "class='text-right bg-warning'",
            ),
            "pph23"         => array(
                "label"      => "pph-23",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                // "attr"       => "class='text-right bg-warning'",
            ),
            "pph22"         => array(
                "label"      => "pph-22",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                // "attr"       => "class='text-right bg-warning'",
            ),
        );

        /* ---------------------------------------------
         * summary per-peran
         * ---------------------------------------------*/
        $this->setNilaiKey("dibayar");
        $kolomNilais = array(
            "pihak_id"        => array(
                "label" => "diterima dari",
                "kolom" => "pihak_nama",
            ),
            "salesman_id"     => false,
            "sales_admin_id"  => false,
            "kategori_id"     => false,
            "pembayaran_nama" => false,
            "transaksi_id"    => false,
            "produk_id"       => array(
                "label" => "rekening bank",
                "kolom" => array(
                    "produk_kode" => array(
                        "label" => "bank",
                    ),
                    "produk_nama" => array(
                        "label" => "rekening",
                    ),
                    "dibayar"     => array(
                        "label"   => "dibayar",
                        "summary" => true,
                    ),
                ),
            ),
            "extern2_nama"    => array(
                "label" => "Jenis penerimaan",
                "kolom" => "extern2_nama",
            )
        );
        $this->setKolomNilai($kolomNilais);
        $arrSummary = $this->viewSummary($masterData);
        // arrPrintPink($arrSummary);
        $summariNilais = $arrSummary['nilai'];
        $summariLabels = $arrSummary['label'];
        $summariSubjectLabels = $arrSummary['kolom_key'];

        $month_now = dtimeNow('Y-m');
        $month_req = formatTanggal($get_date1, 'Y-m');
        // cekHere("$month_req $month_now");
        if ($month_now == $month_req) {
            $judul_lap = "MTD " . dtimeNow('d F Y H:i:s');
        }
        elseif ($date1 == dtimeNow('Y-01-01') && $date2 == dtimeNow('Y-m-d')) {
            $judul_lap = "YTD " . dtimeNow('d F Y H:i');
        }
        else {
            $judul_lap = " " . formatTanggal($get_date1, 'd F Y') . " - " . formatTanggal($get_date2, 'd F Y');
        }
        // $judul_lap = "" . dtimeNow('d F Y H:i:s');

        $data = array(
            "mode"                 => "langsung_simple",
            "title"                => "Laporan Penerimaan kas " . $judul_lap,
            "subTitle"             => "Raw data",
            "modul_path"           => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"              => "582",
            "data_id"              => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "layout"               => isset($_GET['layout']) ? $_GET['layout'] : true,
            "arrHeaders"           => $arrHeaders,
            "master_data"          => $masterData,
            "summariNilais"        => $summariNilais,
            "summariLabels"        => $summariLabels,
            "summariSubjectLabels" => $summariSubjectLabels,
            // navigasi
            // "url"         => base_url(uri_string()) . "$strGet",
            // "strGet"      => $strGet,
            "date1"                => $date1,
            "date2"                => $date2,
            "date_min"             => 1,
            "date_max"             => dtimeNow('Y-m-d'),
            "loader_div"           => "sum_satu",
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingblnproduk" . "$strGet",
            // "sum_tiga"     => base_url() . "laporan/Crm/produkoutstandingblntransaksi" . "$strGet",
        );
        $this->load->view("hutang", $data);
    }

    public function cekSumRowIn()
    {
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');

        $jenis = "5822spd";
        // $tbl_1 = "__raw_rek_pembantu__4010";
        // // $where_2 = array(
        // //     "link_id" => "0",
        // // );
        // // $this->db->select("produk_id,dtime,fulldate");
        // $wheres = "jenis='$jenis' AND DATE(dtime) >= '$date1' AND DATE(dtime) <= '$date2'";
        // $this->db->where($wheres);
        // $this->db->order_by("dtime", "asc");
        // $this->db->group_by("transaksi_id");
        // $tmpA = $this->db->get($tbl_1)->result_array();
        $tmpA = $this->getRawIn($date1, $date2);

        // showLast_query("biru");
        // arrPrint(array_slice($tmpA,1,2));
        // $tagihans = $this->callPaymentSource();
        // $tagihanDuedates = $this->callTransaksiDueDate();
        // $counterSpd = $this->callTransaksiCounterJenis($jenis);
        // arrPrintKuning(array_slice($tagihans,2));
        // arrPrintKuning(array_slice($tagihanDuedates,2));
        // $this->load->model("Mdls/MdlProduk");
        // $pr = new MdlProduk();
        // $spekProduks = $pr->callSpecs();

        $masterData = $this->createMasterData($tmpA);
        // arrPrintPink(array_slice($masterData, 1, 1));
        // arrPrint(array_slice($masterData,10));
        // arrPrint(($masterData));

        $arrHeaders = array(
            "label"         => array(
                "label" => "Jenis penerimaan",
                "attr"  => "class='font-size-1-5 text-capitalize'",
            ),

            // "total_qty_kredit"      => array(
            //     "label"      => "total qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr" => "class='text-right'",
            //     "summary" => true,
            // ),
            "total_dibayar" => array(
                "label"      => "total nilai diterima",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right font-size-1-5'",
                "summary"    => true,
            ),
            "rincian"       => array(
                "label" => "diterima dari",
                "sub"   => array(
                    "pihak_nama" => array(/*----------------------------
                         * label akan mengunakan label pada key rincian
                         * --------------------------------------------*/
                    ),

                    // "note"         => array(
                    //     "label" => 'inv. supplier',
                    // ),
                    // "transaksi_id" => array(
                    //     "label" => 'trId',
                    // ),
                    // "label"        => array(
                    //     "label" => 'inv. supplier',
                    // ),

                    // "no_efaktur"       => array(
                    //     "label" => 'no. e-faktur',
                    // ),
                    // "total"      => array(
                    //     "label"      => 'nilai invoice<br>incl. ppn',
                    //     "format"     => "formatField_he_format",
                    //     "format_key" => "harga",
                    //     "summary"    => true,
                    // ),
                    "dibayar"    => array(
                        "label"      => 'diterima',
                        "format"     => "formatField_he_format",
                        "format_key" => "harga",
                        "summary"    => true,
                    ),
                    // "sub_sisa_tagihan" => array(
                    //     "label"      => 'sisa tagihan<br>w/o ppn',
                    //     "format"     => "formatField_he_format",
                    //     "format_key" => "harga",
                    //     "summary"    => true,
                    // ),
                    // "sub_ppn" => array(
                    //     "label"      => 'ppn',
                    //     "format"     => "formatField_he_format",
                    //     "format_key" => "harga",
                    //     "summary" => true,
                    // ),


                )
            ),
            // "sub" => array(
            //     "label"  => "qty",
            //     "sub" => array(
            //
            //     )
            // ),
            // "pembayaran_nama"   => array(
            //     "label" => "pembayaran",
            // ),
            // "transaksi_id"      => array(
            //     "label" => "trid",
            // ),
            // "pihak_nama"        => array(
            //     "label" => "konsumen",
            // ),
            // "due_date"          => array(
            //     "label"      => "tanggal jatuh tempo",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "fulldate",
            // ),
            // "tagihan_status"    => array(
            //     "label" => "tagihan",
            //     // "format"     => "formatField_he_format",
            //     // "format_key" => "fulldate",
            // ),
            // "umur_status"       => array(
            //     "label" => "overdue",
            // ),
            // "transaksi_no_1"    => array(
            //     "label" => "no. spo",
            // ),
            // "transaksi_no_2"    => array(
            //     "label" => "no. SO",
            //     "type"  => "string",
            // ),
            // "transaksi_no_3"    => array(
            //     "label" => "no. PPL",
            //     "type"  => "string",
            // ),
            // "transaksi_no_4"    => array(
            //     "label" => "no. PL",
            //     "type"  => "string",
            // ),
            // "transaksi_no_inv"  => array(
            //     "label" => "no. invoice",
            //     "type"  => "string",
            // ),
            // // ----------
            // // "produk_kode"           => array(
            // //     "label" => "produk sku",
            // //     "type"  => "string",
            // // ),
            // // "produk_nama"           => array(
            // //     "label" => "produk",
            // //     "type"  => "string",
            // // ),
            // // "outdoor_nama"          => array(
            // //     "label" => "outdoor",
            // //     "type"  => "string",
            // // ),
            // // "indoor_nama_1"         => array(
            // //     "label" => "intdoor",
            // //     "type"  => "string",
            // // ),
            // // "qty_kredit"            => array(
            // //     "label" => "jumlah",
            // //     "type"  => "integer",
            // // ),
            // // "harga_include_ppn"     => array(
            // //     "label"      => "harga jual",
            // //     "format"     => "formatField_he_format",
            // //     "format_key" => "harga",
            // // ),
            // // "sub_harga_include_ppn" => array(
            // //     "label"      => "sub harga jual",
            // //     "format"     => "formatField_he_format",
            // //     "format_key" => "harga",
            // // ),
            // //-----------------
            // "dpp_ppn"           => array(
            //     "label"      => "jml kena pajak",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //
            // ),
            // "total_ppn"         => array(
            //     "label"      => "total pajak",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //
            // ),
            // "total_tagihan"     => array(
            //     "label"      => "total",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //
            // ),
            // "total_terbayar"    => array(
            //     "label"      => "pembayaran",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //
            // ),
            // "sisa_tagihan"      => array(
            //     "label"      => "sisa tagihan",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //
            // ),
        );

        $this->setPivotParent("extern2_nama");
        $this->setPivotChildFirst("pihak_nama");
        $pivotDatas = $this->creatPivot($masterData);

        // arrPrintHijau(array_slice($pivotDatas, 1, 1));

        // matiHere(__LINE__);
        /* ---------------------------------------------
        * summary per-peran
        * ---------------------------------------------*/
        $this->setNilaiKey("dibayar");
        $kolomNilais = array(
            "pihak_id"        => array(
                "label" => "diterima dari",
                "kolom" => "pihak_nama",
            ),
            "salesman_id"     => false,
            "sales_admin_id"  => false,
            "kategori_id"     => false,
            "pembayaran_nama" => false,
            "transaksi_id"    => false,
            "produk_id"       => array(
                "label" => "rekening bank",
                "kolom" => array(
                    "produk_kode" => array(
                        "label" => "bank",
                    ),
                    "produk_nama" => array(
                        "label" => "rekening",
                    ),
                    "dibayar"     => array(
                        "label"   => "dibayar",
                        "summary" => true,
                    ),
                ),
            ),
            "extern2_nama"    => array(
                "label" => "Jenis penerimaan",
                "kolom" => "extern2_nama",
            )
        );
        $this->setKolomNilai($kolomNilais);
        $arrSummary = $this->viewSummary($masterData);
        // arrPrintPink($arrSummary);
        $summariNilais = $arrSummary['nilai'];
        $summariLabels = $arrSummary['label'];
        $summariSubjectLabels = $arrSummary['kolom_key'];

        $month_now = dtimeNow('Y-m-d');
        $month_req = formatTanggal($get_date1, 'Y-m-d');
        // cekHere("$month_req $month_now");
        // cekHere("$month_now == $month_req");
        if ($month_now == $month_req) {
            $judul_lap = "" . dtimeNow('d F Y H:i:s');
            // if ($date1 == $date2) {
            //     $judul_lap = formatTanggal($get_date1, 'd F Y');
            // }
        }
        elseif ($date1 == dtimeNow('Y-01-01') && $date2 == dtimeNow('Y-m-d')) {
            $judul_lap = "YTD " . dtimeNow('d F Y H:i');
        }
        else {
            if($get_date1 == $get_date2){
                $judul_lap = " " . formatTanggal($get_date1, 'd F Y');
            }
            else{

                $judul_lap = " " . formatTanggal($get_date1, 'd F Y') . " - " . formatTanggal($get_date2, 'd F Y');
            }
        }
        // $judul_lap = "so";
        $data = array(
            // "mode"        => "langsung_indek",
            "mode"                 => "pivot",
            "title"                => "Laporan Summary Penerimaan Kas " . $judul_lap,// isinya ada 749 (penerimaan ar), 4464 (penjualan tunai)
            "subTitle"             => "Raw data pembelian",
            "modul_path"           => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"              => "582",
            "data_id"              => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "layout"               => isset($_GET['layout']) ? $_GET['layout'] : true,
            "arrHeaders"           => $arrHeaders,
            "master_data"          => $masterData,
            "summariNilais"        => $summariNilais,
            "summariLabels"        => $summariLabels,
            "summariSubjectLabels" => $summariSubjectLabels,
            "pivotDatas"           => $pivotDatas,
            // navigasi
            // "url"         => base_url(uri_string()) . "$strGet",
            // "strGet"      => $strGet,
            "date1"                => $date1,
            "date2"                => $date2,
            "date_min"             => 1,
            "date_max"             => dtimeNow('Y-m-d'),
            "loader_div"           => "sum_satu",
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingblnproduk" . "$strGet",
            // "sum_tiga"     => base_url() . "laporan/Crm/produkoutstandingblntransaksi" . "$strGet",
        );
        // $this->load->view("laporan", $data);
        $this->load->view("penerimaan_periode", $data);
    }

    /* -------------------------------------------------------------
     * kas keluar
     * -------------------------------------------------------------*/
    public function getRawOt($date1, $date2)
    {
        // $jenis = "5822spd";
        $tbl_1 = "__raw_rek_pembantu__1010010010";
        // $where_2 = array(
        //     "link_id" => "0",
        // );
        // $this->db->select("produk_id,dtime,fulldate");
        $transaksi_jenis_masuk = array(
            "4464",
            "749",
            "4467",
        );
        $transaksi_jenis_keluar = array(
            "464", "489"
        );
        $this->db->where_in("jenis", $transaksi_jenis_keluar);
        $wheres = "DATE(dtime) >= '$date1' AND DATE(dtime) <= '$date2'";
        $this->db->where($wheres);
        $this->db->order_by("dtime", "asc");
        $tmpA = $this->db->get($tbl_1)->result_array();
        // showLast_query("biru");

        return $tmpA;
    }

    public function getRekening($date1, $date2, $cabang_id)
    {
        // $jenis = "5822spd";

        $this->load->helper("he_mass_table");

        $pakaiini = 1;
        if ($pakaiini == 0) {
            $tbl_1 = "__raw_rek_pembantu__1010010010";
            // $where_2 = array(
            //     "link_id" => "0",
            // );
            // $this->db->select("produk_id,dtime,fulldate");
            $transaksi_jenis_masuk = array(
                "4464",
                "749",
                "4467",
            );
            $transaksi_jenis_keluar = array(
                "464", "489"
            );
            $this->db->where_in("jenis", $transaksi_jenis_masuk);
            $wheres = "DATE(dtime) >= '$date1' AND DATE(dtime) <= '$date2'";
            $this->db->where($wheres);
            $this->db->order_by("dtime", "asc");
            $tmpA = $this->db->get($tbl_1)->result_array();
        }
        else {
            $this->load->model("Coms/ComRekeningPembantuKasItem");
            $m = new ComRekeningPembantuKasItem();
            $m->addFilter("dtime>=$date1");
            $m->addFilter("dtime<=$date2");
            $m->addFilter("cabang_id=$cabang_id");
            $m->addFilter("debet>0");
            $this->db->order_by("dtime", "asc");
            $tmpA = $m->fetchAllMoves("1010010010");
            //            arrPrint($tmpA);
            //            matiHEre($this->db->last_query());
        }

        // showLast_query("biru");
        //matiHere(__LINE__);
        return $tmpA;
    }

    public function cekRowOt()
    {
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');

        $jenis = "467";
        // $tbl_1 = "__raw_rek_pembantu__4010";
        // // $where_2 = array(
        // //     "link_id" => "0",
        // // );
        // // $this->db->select("produk_id,dtime,fulldate");
        // $wheres = "jenis='$jenis' AND DATE(dtime) >= '$date1' AND DATE(dtime) <= '$date2'";
        // $this->db->where($wheres);
        // $this->db->order_by("dtime", "asc");
        // $tmpA = $this->db->get($tbl_1)->result_array();
        // showLast_query("biru");
        // arrPrintHijau(array_slice($tmpA,1,1));
        // $counterJenis = $this->callTransaksiCounterJenis(false);
        // $tagihans = $this->callPaymentSource($jenis);
        // showLast_query("kuning");
        // $tagihanDuedates = $this->callTransaksiDueDate();
        // showLast_query("merah");
        $tmpA = $this->getRawOt($date1, $date2);

        showLast_query("biru");
        // arrPrint(array_slice($tmpA,1,1));
        // $this->load->model("Mdls/MdlProduk");
        // $pr = new MdlProduk();
        // $spekProduks = $pr->callSpecs();

        // arrPrintKuning($tagihans);
        // matiHere(__LINE__);
        $masterData = $this->createMasterData($tmpA);
        // arrPrintHijau(array_slice($masterData,1,1));
        // arrPrint(array_slice($masterData,10));
        // arrPrint(($masterData));


        $arrHeaders = array(
            "pihak_nama"    => array(
                "label" => "dikeluarkan untuk",
            ),
            "extern2_nama"  => array(
                "label"      => "keterangan",
                "data_order" => "due_date",
            ),
            "transaksi_id"  => array(
                "label" => "trid",
            ),
            "nomer_counter" => array(
                "label"      => "nomer",
                "data_order" => ""
            ),
            "dtime"         => array(
                "label"  => "tanggal",
                "format" => "formatField_he_format",
            ),
            // "nomer_counter"       => array(
            //     "label" => "no grn",
            // ),

            // "transaksi_jam"     => array(
            //     "label" => "jam",
            //     // "format"     => "formatField_he_format",
            // ),
            "produk_kode"   => array(
                "label" => "bank",
            ),
            "produk_nama"   => array(
                "label" => "rekening",
            ),


            // "transaksi_id"          => array(
            //     "label" => "trid",
            // ),


            // "transaksi_no_2"    => array(
            //     "label" => "no. SO",
            //     "type"  => "string",
            // ),
            // "transaksi_no_3"    => array(
            //     "label" => "no. PPL",
            //     "type"  => "string",
            // ),
            // "transaksi_no_4"    => array(
            //     "label" => "no. PL",
            //     "type"  => "string",
            //     // "attr"       => "class='text-right bg-warning'",
            // ),
            // "transaksi_no_inv"  => array(
            //     "label" => "no. invoice",
            //     "type"  => "string",
            // ),
            // "pembayaran_nama"   => array(
            //     "label" => "pembayaran",
            // ),
            // // -------------------------------------------
            // "merek_nama"       => array(
            //     "label" => "nama",
            //     "type"  => "string",
            //     "attr"  => "class='bg-warning'",
            // ),
            // "produk_kode"       => array(
            //     "label" => "produk sku",
            //     "type"  => "string",
            //     "attr"  => "class='bg-warning'",
            // ),
            // "produk_nama"       => array(
            //     "label" => "produk",
            //     "type"  => "string",
            //     "attr"  => "class='bg-warning'",
            // ),
            // "outdoor_nama"          => array(
            //     "label" => "outdoor",
            //     "type"  => "string",
            // ),
            // "indoor_nama_1"         => array(
            //     "label" => "intdoor",
            //     "type"  => "string",
            // ),
            // "qty_kredit"        => array(
            //     "label" => "jumlah",
            //     "type"  => "integer",
            //     "attr"  => "class='text-right bg-warning'",
            // ),
            // "harga"             => array(
            //     "label"      => "harga per unit",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            // ),
            // "kredit"            => array(
            //     "label"      => "jumlah kena pajak",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            // ),
            // "ppn_nilai"     => array(
            //     "label"      => "pajak nilai",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            // ),

            /*----pajak ppn---*/
            // "c_ppn"     => array(
            //     "label"      => "pajak",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            // ),

            // "sub_ppn_nilai"  => array(
            //     "label"      => "pajak",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            // ),
            // // ---------------------
            // "c_sub_total"    => array(
            //     "label"      => "total penjualan",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            // ),

            // "harga_include_ppn"     => array(
            //     "label"      => "harga jual",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            // ),
            // "sub_harga_include_ppn" => array(
            //     "label"      => "sub harga jual",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            // ),
            // "tagihan_include_ppn" => array(
            //     "label"      => "nilai tagihan",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "summary"    => false,
            //     // "attr"       => "class='text-right bg-warning'",
            // ),
            // "kredit"    => array(
            //     "label"      => "um",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     // "attr"       => "class='text-right bg-warning'",
            // ),
            "dibayar"       => array(
                "label"      => "dibayarkan inc. ppn",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                // "attr"       => "class='text-right bg-warning'",
            ),
            // "sisa"          => array(
            //     "label"      => "ssia",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     // "attr"       => "class='text-right bg-warning'",
            // ),
            "ppn_nilai"     => array(
                "label"      => "ppn",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                // "attr"       => "class='text-right bg-warning'",
            ),
            "pph23"         => array(
                "label"      => "pph-23",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                // "attr"       => "class='text-right bg-warning'",
            ),
            "pph22"         => array(
                "label"      => "pph-22",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                // "attr"       => "class='text-right bg-warning'",
            ),
        );

        /* ---------------------------------------------
         * summary per-peran
         * ---------------------------------------------*/
        $this->setNilaiKey("dibayar");
        $kolomNilais = array(
            "pihak_id"        => array(
                "label" => "dikeluarkan untuk",
                "kolom" => "pihak_nama",
            ),
            "salesman_id"     => false,
            "sales_admin_id"  => false,
            "kategori_id"     => false,
            "pembayaran_nama" => false,
            "transaksi_id"    => false,
            "produk_nama"     => array(
                "label" => "rekening bank",
                "kolom" => array(
                    "produk_kode" => array(
                        "label" => "bank",
                    ),
                    "produk_nama" => array(
                        "label" => "rekening",
                    ),
                    "dibayar"     => array(
                        "label"   => "dibayar",
                        "summary" => true,
                    ),
                ),
            ),
            "extern2_nama"    => array(
                "label" => "Jenis pengeluaran",
                "kolom" => "extern2_nama",
            ),
        );
        $this->setKolomNilai($kolomNilais);
        $arrSummary = $this->viewSummary($masterData);
        // arrPrintPink($arrSummary);
        $summariNilais = $arrSummary['nilai'];
        $summariLabels = $arrSummary['label'];
        $summariSubjectLabels = $arrSummary['kolom_key'];

        $month_now = dtimeNow('Y-m');
        $month_req = formatTanggal($get_date1, 'Y-m');
        // cekHere("$month_req $month_now");
        if ($month_now == $month_req) {
            $judul_lap = "MTD " . dtimeNow('d F Y H:i:s');
        }
        elseif ($date1 == dtimeNow('Y-01-01') && $date2 == dtimeNow('Y-m-d')) {
            $judul_lap = "YTD " . dtimeNow('d F Y H:i');
        }
        else {
            $judul_lap = " " . formatTanggal($get_date1, 'd F Y') . " - " . formatTanggal($get_date2, 'd F Y');
        }
        // $judul_lap = "" . dtimeNow('d F Y H:i:s');

        $data = array(
            "mode"                 => "langsung_simple",
            "title"                => "Laporan pengeluaran kas " . $judul_lap,
            "subTitle"             => "Raw data pembelian",
            "modul_path"           => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"              => "582",
            "data_id"              => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "layout"               => isset($_GET['layout']) ? $_GET['layout'] : true,
            "arrHeaders"           => $arrHeaders,
            "master_data"          => $masterData,
            "summariNilais"        => $summariNilais,
            "summariLabels"        => $summariLabels,
            "summariSubjectLabels" => $summariSubjectLabels,
            // navigasi
            // "url"         => base_url(uri_string()) . "$strGet",
            // "strGet"      => $strGet,
            "date1"                => $date1,
            "date2"                => $date2,
            "date_min"             => 1,
            "date_max"             => dtimeNow('Y-m-d'),
            "loader_div"           => "sum_satu",
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingblnproduk" . "$strGet",
            // "sum_tiga"     => base_url() . "laporan/Crm/produkoutstandingblntransaksi" . "$strGet",
        );
        $this->load->view("hutang", $data);
    }

    public function cekRekening()
    {
        $this->load->model("Mdls/MdlBank");
        $bk = new MdlBank();
        $srcBk = $bk->lookupAll()->result();
        foreach ($srcBk as $item) {
            $banks[$item->id] = $item;
        }
        // showLast_query("kuning");
        // arrPrint($srcBk);
        // arrPrintPink($_GET);

        $this->load->model("Mdls/MdlBankAccount_cash_and_in");
        $b = new MdlBankAccount_cash_and_in();
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');

        // $jenis = "467";
        $cabang_id = $this->session->login["cabang_id"];
        // $tbl_1 = "__raw_rek_pembantu__4010";
        // // $where_2 = array(
        // //     "link_id" => "0",
        // // );
        // // $this->db->select("produk_id,dtime,fulldate");
        // $wheres = array(
        //         "extern_id" => "1160",
        // );
        // $this->db->where($wheres);
        // $this->db->order_by("dtime", "asc");
        // $tmpA = $this->db->get($tbl_1)->result_array();
        // showLast_query("biru");
        // arrPrintHijau(array_slice($tmpA,1,1));
        // $counterJenis = $this->callTransaksiCounterJenis(false);
        // $tagihans = $this->callPaymentSource($jenis);
        // showLast_query("kuning");
        // $tagihanDuedates = $this->callTransaksiDueDate();
        // showLast_query("merah");

        $tempBank = $b->lookUpAll()->result_array();
        $bankData = array();
        foreach ($tempBank as $tempBank_0) {
            $bankData[$tempBank_0["id"]]["produk_kode"] = $tempBank_0["folders_nama"];
            $bankDataInduk[$tempBank_0["folders"]][$tempBank_0["id"]] = $tempBank_0["nama"];
        }

        // arrPrintHijau($bankDataInduk);
        $arrHeaders_1 = array();
        $masterData = array();
        // arrPrintHijau($_GET);
        if (isset($_GET['xt'])) {
            // $gbid = blobDecode($_GET['xt']);
            $bid = $_GET['xt'];
            $brekid = $_GET['xta'];
            $bank_nama = $banks[$bid]->nama;
            $rekening_nama = $bankDataInduk[$bid][$brekid];
            // arrPrint($bankDataInduk[$bid]);
            $gbids = array_keys($bankDataInduk[$bid]);

            if (isset($_GET['xta']) && $_GET['xta'] != 0) {
                // cekBiru(__LINE__);
                $this->db->where('extern_id', $brekid);

                $bank_nama .= " <r>$rekening_nama</r>";
            }
            elseif ($_GET['xt'] > 0 && count($gbids)) {
                // cekKuning(__LINE__);
                // arrPrintHijau($gbids);
                $this->db->where_in('extern_id', $gbids);

                $arrHeaders_1 = array(
                    // "produk_kode"   => array(
                    //     "label" => "bank",
                    // ),
                    "extern_nama"   => array(
                        "label" => "rekening",
                        "attr" => "class='bg-warning text-center'",
                    ),
                );

                $bank_nama = " <r>$bank_nama</r>";
            }
            elseif ($_GET['xt'] == 0){
                // cekBiru(__LINE__);

                $arrHeaders_1 = array(
                    "produk_kode"   => array(
                        "label" => "bank",
                        "attr" => "class='bg-warning'",
                    ),
                    "extern_nama"   => array(
                        "label" => "rekening",
                        "attr" => "class='bg-warning'",
                    ),
                );
            }
            else{
                // cekHere(__LINE__);
                $this->db->where('extern_id', $brekid);
            }

            $tmpA = $this->getRekening($date1, $date2, $cabang_id);
            //arrPrint($tmpA);
            // showLast_query("biru");
            // arrPrint(array_slice($tmpA,1,1));
            // $this->load->model("Mdls/MdlProduk");
            // $pr = new MdlProduk();
            // $spekProduks = $pr->callSpecs();

            // arrPrintKuning($tagihans);
            // matiHere(__LINE__);
            if (count($tmpA)) {

                $masterData = $this->createMasterData($tmpA, $bankData);
            }
            else {
                cekAlert("Tidak Ada data <r>$bank_nama</r><br>Silahkan pilih option yang lainnya");
            }
            // arrPrintHijau(array_slice($masterData,1,1));
            // arrPrint(array_slice($masterData,10));
            //         arrPrint(($masterData));
        }
        else {
            cekAlert("Silahkan pilih option BANK yang ada");
        }

        $arrHeaders_0 = array(
            "pihak_nama"    => array(
                "label" => "diterima dari",
            ),
            "extern2_nama"  => array(
                "label" => "jenis penerimaan",
            ),
            "keterangan"    => array(
                "label"      => "keterangan",
                "data_order" => "due_date",
            ),
            //            "transaksi_id"        => array(
            //                "label" => "trid",
            //                "format" => "formatField_he_format",
            ////                "format_key" => "id",
            ////                "summary" => true,
            //            ),
            "nomer_counter" => array(
                "label"      => "nomer",
                "data_order" => ""
            ),
            "dtime"         => array(
                "label"  => "tanggal",
                "format" => "formatField_he_format",
            ),
            // "nomer_counter"       => array(
            //     "label" => "no grn",
            // ),

            // "transaksi_jam"     => array(
            //     "label" => "jam",
            //     // "format"     => "formatField_he_format",
            // ),
        );
        // $arrHeaders_1 = array(
        //     "produk_kode"   => array(
        //         "label" => "bank",
        //     ),
        //     "extern_nama"   => array(
        //         "label" => "rekening",
        //     ),
        // );

        $arrHeaders_2 = array(

            // "transaksi_id"          => array(
            //     "label" => "trid",
            // ),


            // "transaksi_no_2"    => array(
            //     "label" => "no. SO",
            //     "type"  => "string",
            // ),
            // "transaksi_no_3"    => array(
            //     "label" => "no. PPL",
            //     "type"  => "string",
            // ),
            // "transaksi_no_4"    => array(
            //     "label" => "no. PL",
            //     "type"  => "string",
            //     // "attr"       => "class='text-right bg-warning'",
            // ),
            // "transaksi_no_inv"  => array(
            //     "label" => "no. invoice",
            //     "type"  => "string",
            // ),
            // "pembayaran_nama"   => array(
            //     "label" => "pembayaran",
            // ),
            // // -------------------------------------------
            // "merek_nama"       => array(
            //     "label" => "nama",
            //     "type"  => "string",
            //     "attr"  => "class='bg-warning'",
            // ),
            // "produk_kode"       => array(
            //     "label" => "produk sku",
            //     "type"  => "string",
            //     "attr"  => "class='bg-warning'",
            // ),
            // "produk_nama"       => array(
            //     "label" => "produk",
            //     "type"  => "string",
            //     "attr"  => "class='bg-warning'",
            // ),
            // "outdoor_nama"          => array(
            //     "label" => "outdoor",
            //     "type"  => "string",
            // ),
            // "indoor_nama_1"         => array(
            //     "label" => "intdoor",
            //     "type"  => "string",
            // ),
            // "qty_kredit"        => array(
            //     "label" => "jumlah",
            //     "type"  => "integer",
            //     "attr"  => "class='text-right bg-warning'",
            // ),
            // "harga"             => array(
            //     "label"      => "harga per unit",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            // ),
            // "kredit"            => array(
            //     "label"      => "jumlah kena pajak",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            // ),
            // "ppn_nilai"     => array(
            //     "label"      => "pajak nilai",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            // ),

            /*----pajak ppn---*/
            // "c_ppn"     => array(
            //     "label"      => "pajak",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            // ),

            // "sub_ppn_nilai"  => array(
            //     "label"      => "pajak",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            // ),
            // // ---------------------
            // "c_sub_total"    => array(
            //     "label"      => "total penjualan",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            // ),

            // "harga_include_ppn"     => array(
            //     "label"      => "harga jual",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            // ),
            // "sub_harga_include_ppn" => array(
            //     "label"      => "sub harga jual",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            // ),
            // "tagihan_include_ppn" => array(
            //     "label"      => "nilai tagihan",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "summary"    => false,
            //     // "attr"       => "class='text-right bg-warning'",
            // ),
            // "kredit"    => array(
            //     "label"      => "um",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     // "attr"       => "class='text-right bg-warning'",
            // ),
            "debet"         => array(
                "label"      => "diterima",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                // "attr"       => "class='text-right bg-warning'",
            ),
            // "sisa"          => array(
            //     "label"      => "ssia",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     // "attr"       => "class='text-right bg-warning'",
            // ),
            //            "ppn_nilai"          => array(
            //                "label"      => "ppn",
            //                "format"     => "formatField_he_format",
            //                "format_key" => "harga",
            //                // "attr"       => "class='text-right bg-warning'",
            //            ),
            //            "pph23"          => array(
            //                "label"      => "pph-23",
            //                "format"     => "formatField_he_format",
            //                "format_key" => "harga",
            //                // "attr"       => "class='text-right bg-warning'",
            //            ),
            //            "pph22"          => array(
            //                "label"      => "pph-22",
            //                "format"     => "formatField_he_format",
            //                "format_key" => "harga",
            //                // "attr"       => "class='text-right bg-warning'",
            //            ),
        );

        $arrHeaders = $arrHeaders_0 + $arrHeaders_1 + $arrHeaders_2;
        /* ---------------------------------------------
         * summary per-peran atas
         * ---------------------------------------------*/
        $this->setNilaiKey("debet");
        $kolomNilais = array(
            "pihak_id"        => array(
                "label" => "diterima dari",
                "kolom" => "pihak_nama",
            ),
            "salesman_id"     => false,
            "sales_admin_id"  => false,
            "kategori_id"     => false,
            "pembayaran_nama" => false,
            "transaksi_id"    => false,
            "produk_id"       => false,
            "extern_nama"     => false,
            "extern_id"       => array(
                "label" => "rekening bank",
                "kolom" => array(
                    "produk_kode" => array(
                        "label" => "bank",
                    ),
                    "extern_nama" => array(
                        "label"   => "rekening",
                        "summary" => false,
                    ),

                    "debet" => array(
                        "label"   => "dibayar",
                        "summary" => true,
                    ),
                ),
            ),
            "extern2_nama"    => array(
                "label" => "Jenis penerimaan",
                "kolom" => "extern2_nama",
            )
        );
        $this->setKolomNilai($kolomNilais);
        $arrSummary = $this->viewSummary($masterData);
        // arrPrintPink($arrSummary);
        $summariNilais = $arrSummary['nilai'];
        $summariLabels = $arrSummary['label'];
        $summariSubjectLabels = $arrSummary['kolom_key'];

        $month_now = dtimeNow('Y-m');
        $month_req = formatTanggal($get_date1, 'Y-m');
        // cekHere("$month_req $month_now");
        if ($month_now == $month_req) {
            $judul_lap = "MTD " . dtimeNow('d F Y H:i:s');
        }
        elseif ($date1 == dtimeNow('Y-01-01') && $date2 == dtimeNow('Y-m-d')) {
            $judul_lap = "YTD " . dtimeNow('d F Y H:i');
        }
        else {
            $judul_lap = " " . formatTanggal($get_date1, 'd F Y') . " - " . formatTanggal($get_date2, 'd F Y');
        }
        // $judul_lap = "" . dtimeNow('d F Y H:i:s');

        $data = array(
            "mode"                 => "langsung_simple",
            "title"                => "Laporan Penerimaan kas pada bank $bank_nama " . $judul_lap,
            "subTitle"             => "Raw data",
            "modul_path"           => $this->modul_path,
            "color_bar"            => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"              => "582",
            "data_id"              => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "layout"               => isset($_GET['layout']) ? $_GET['layout'] : true,
            "arrHeaders"           => $arrHeaders,
            "master_data"          => $masterData,
            "summariNilais"        => $summariNilais,
            // "summariLabels"        => $summariLabels,
            "summariSubjectLabels" => $summariSubjectLabels,
            // navigasi
            // "url"         => base_url(uri_string()) . "$strGet",
            // "strGet"      => $strGet,
            "date1"                => $date1,
            "date2"                => $date2,
            "date_min"             => 1,
            "date_max"             => dtimeNow('Y-m-d'),
            "loader_div"           => "sum_satu",
            "preloader"            => $srcBk,
            "bankDataInduk"        => $bankDataInduk,
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingblnproduk" . "$strGet",
            // "sum_tiga"     => base_url() . "laporan/Crm/produkoutstandingblntransaksi" . "$strGet",
        );
        $this->load->view("kas", $data);
    }

    public function cekSumRowOt()
    {
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');

        $jenis = "5822spd";
        // $tbl_1 = "__raw_rek_pembantu__4010";
        // // $where_2 = array(
        // //     "link_id" => "0",
        // // );
        // // $this->db->select("produk_id,dtime,fulldate");
        // $wheres = "jenis='$jenis' AND DATE(dtime) >= '$date1' AND DATE(dtime) <= '$date2'";
        // $this->db->where($wheres);
        // $this->db->order_by("dtime", "asc");
        // $this->db->group_by("transaksi_id");
        // $tmpA = $this->db->get($tbl_1)->result_array();
        $tmpA = $this->getRawOt($date1, $date2);

        // showLast_query("biru");
        // arrPrint(array_slice($tmpA,1,2));
        // $tagihans = $this->callPaymentSource();
        // $tagihanDuedates = $this->callTransaksiDueDate();
        // $counterSpd = $this->callTransaksiCounterJenis($jenis);
        // arrPrintKuning(array_slice($tagihans,2));
        // arrPrintKuning(array_slice($tagihanDuedates,2));
        // $this->load->model("Mdls/MdlProduk");
        // $pr = new MdlProduk();
        // $spekProduks = $pr->callSpecs();

        $masterData = $this->createMasterData($tmpA);
        // arrPrintPink(array_slice($masterData, 1, 1));
        // arrPrint(array_slice($masterData,10));
        // arrPrint(($masterData));

        $arrHeaders = array(
            "label"         => array(
                "label" => "Jenis pengeluaran",
                "attr"  => "class='font-size-1-5 text-capitalize'",
            ),

            // "total_qty_kredit"      => array(
            //     "label"      => "total qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr" => "class='text-right'",
            //     "summary" => true,
            // ),
            "total_dibayar" => array(
                "label"      => "total nilai dibayar",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right font-size-1-5'",
                "summary"    => true,
            ),
            "rincian"       => array(
                "label" => "dikeluarakan untuk",
                "sub"   => array(
                    "pihak_nama" => array(/*----------------------------
                         * label akan mengunakan label pada key rincian
                         * --------------------------------------------*/
                    ),

                    // "note"         => array(
                    //     "label" => 'inv. supplier',
                    // ),
                    // "transaksi_id" => array(
                    //     "label" => 'trId',
                    // ),
                    // "label"        => array(
                    //     "label" => 'inv. supplier',
                    // ),

                    // "no_efaktur"       => array(
                    //     "label" => 'no. e-faktur',
                    // ),
                    // "total"      => array(
                    //     "label"      => 'nilai invoice<br>incl. ppn',
                    //     "format"     => "formatField_he_format",
                    //     "format_key" => "harga",
                    //     "summary"    => true,
                    // ),
                    "dibayar"    => array(
                        "label"      => 'dibayar',
                        "format"     => "formatField_he_format",
                        "format_key" => "harga",
                        "summary"    => true,
                    ),
                    // "sub_sisa_tagihan" => array(
                    //     "label"      => 'sisa tagihan<br>w/o ppn',
                    //     "format"     => "formatField_he_format",
                    //     "format_key" => "harga",
                    //     "summary"    => true,
                    // ),
                    // "sub_ppn" => array(
                    //     "label"      => 'ppn',
                    //     "format"     => "formatField_he_format",
                    //     "format_key" => "harga",
                    //     "summary" => true,
                    // ),


                )
            ),
            // "sub" => array(
            //     "label"  => "qty",
            //     "sub" => array(
            //
            //     )
            // ),
            // "pembayaran_nama"   => array(
            //     "label" => "pembayaran",
            // ),
            // "transaksi_id"      => array(
            //     "label" => "trid",
            // ),
            // "pihak_nama"        => array(
            //     "label" => "konsumen",
            // ),
            // "due_date"          => array(
            //     "label"      => "tanggal jatuh tempo",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "fulldate",
            // ),
            // "tagihan_status"    => array(
            //     "label" => "tagihan",
            //     // "format"     => "formatField_he_format",
            //     // "format_key" => "fulldate",
            // ),
            // "umur_status"       => array(
            //     "label" => "overdue",
            // ),
            // "transaksi_no_1"    => array(
            //     "label" => "no. spo",
            // ),
            // "transaksi_no_2"    => array(
            //     "label" => "no. SO",
            //     "type"  => "string",
            // ),
            // "transaksi_no_3"    => array(
            //     "label" => "no. PPL",
            //     "type"  => "string",
            // ),
            // "transaksi_no_4"    => array(
            //     "label" => "no. PL",
            //     "type"  => "string",
            // ),
            // "transaksi_no_inv"  => array(
            //     "label" => "no. invoice",
            //     "type"  => "string",
            // ),
            // // ----------
            // // "produk_kode"           => array(
            // //     "label" => "produk sku",
            // //     "type"  => "string",
            // // ),
            // // "produk_nama"           => array(
            // //     "label" => "produk",
            // //     "type"  => "string",
            // // ),
            // // "outdoor_nama"          => array(
            // //     "label" => "outdoor",
            // //     "type"  => "string",
            // // ),
            // // "indoor_nama_1"         => array(
            // //     "label" => "intdoor",
            // //     "type"  => "string",
            // // ),
            // // "qty_kredit"            => array(
            // //     "label" => "jumlah",
            // //     "type"  => "integer",
            // // ),
            // // "harga_include_ppn"     => array(
            // //     "label"      => "harga jual",
            // //     "format"     => "formatField_he_format",
            // //     "format_key" => "harga",
            // // ),
            // // "sub_harga_include_ppn" => array(
            // //     "label"      => "sub harga jual",
            // //     "format"     => "formatField_he_format",
            // //     "format_key" => "harga",
            // // ),
            // //-----------------
            // "dpp_ppn"           => array(
            //     "label"      => "jml kena pajak",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //
            // ),
            // "total_ppn"         => array(
            //     "label"      => "total pajak",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //
            // ),
            // "total_tagihan"     => array(
            //     "label"      => "total",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //
            // ),
            // "total_terbayar"    => array(
            //     "label"      => "pembayaran",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //
            // ),
            // "sisa_tagihan"      => array(
            //     "label"      => "sisa tagihan",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //
            // ),
        );

        $this->setPivotParent("extern2_nama");
        $this->setPivotChildFirst("pihak_nama");
        $pivotDatas = $this->creatPivot($masterData);

        // arrPrintHijau(array_slice($pivotDatas, 1, 1));

        // matiHere(__LINE__);
        /* ---------------------------------------------
        * summary per-peran
        * ---------------------------------------------*/
        $this->setNilaiKey("dibayar");
        $kolomNilais = array(
            "pihak_id"        => array(
                "label" => "dikeluarkan untuk",
                "kolom" => "pihak_nama",
            ),
            "salesman_id"     => false,
            "sales_admin_id"  => false,
            "kategori_id"     => false,
            "pembayaran_nama" => false,
            "transaksi_id"    => false,
            "produk_id"       => array(
                "label" => "rekening bank",
                "kolom" => array(
                    "produk_kode" => array(
                        "label" => "bank",
                    ),
                    "produk_nama" => array(
                        "label" => "rekening",
                    ),
                    "dibayar"     => array(
                        "label"   => "dibayar",
                        "summary" => true,
                    ),
                ),
            ),
            "extern2_nama"    => array(
                "label" => "Jenis pengeluaran",
                "kolom" => "extern2_nama",
            ),
        );
        $this->setKolomNilai($kolomNilais);
        $arrSummary = $this->viewSummary($masterData);
        // arrPrintPink($arrSummary);
        $summariNilais = $arrSummary['nilai'];
        $summariLabels = $arrSummary['label'];
        $summariSubjectLabels = $arrSummary['kolom_key'];

        $month_now = dtimeNow('Y-m');
        $month_req = formatTanggal($get_date1, 'Y-m');
        // cekHere("$month_req $month_now");
        if ($month_now == $month_req) {
            $judul_lap = "" . dtimeNow('d F Y H:i:s');
            // if ($date1 == $date2) {
            //     $judul_lap = formatTanggal($get_date1, 'd F Y');
            // }
        }
        elseif ($date1 == dtimeNow('Y-01-01') && $date2 == dtimeNow('Y-m-d')) {
            $judul_lap = "YTD " . dtimeNow('d F Y H:i');
        }
        else {
            $judul_lap = " " . formatTanggal($get_date1, 'd F Y') . " - " . formatTanggal($get_date2, 'd F Y');
        }
        // $judul_lap = "so";
        $data = array(
            // "mode"        => "langsung_indek",
            "mode"                 => "pivot",
            "title"                => "Laporan Summary Pengeluaran Kas " . $judul_lap,// isinya ada 749 (penerimaan ar), 4464 (penjualan tunai)
            "subTitle"             => "Raw data pembelian",
            "modul_path"           => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"              => "582",
            "data_id"              => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "layout"               => isset($_GET['layout']) ? $_GET['layout'] : true,
            "arrHeaders"           => $arrHeaders,
            "master_data"          => $masterData,
            "summariNilais"        => $summariNilais,
            "summariLabels"        => $summariLabels,
            "summariSubjectLabels" => $summariSubjectLabels,
            "pivotDatas"           => $pivotDatas,
            // navigasi
            // "url"         => base_url(uri_string()) . "$strGet",
            // "strGet"      => $strGet,
            "date1"                => $date1,
            "date2"                => $date2,
            "date_min"             => 1,
            "date_max"             => dtimeNow('Y-m-d'),
            "loader_div"           => "sum_satu",
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingblnproduk" . "$strGet",
            // "sum_tiga"     => base_url() . "laporan/Crm/produkoutstandingblntransaksi" . "$strGet",
        );
        // $this->load->view("laporan", $data);
        $this->load->view("penerimaan_periode", $data);
    }

    /* -------------------------------------------------------------
     * viewer
     * -------------------------------------------------------------*/
    public function viewhr()
    {
        $this->load->helper("he_mass_table");
        // $this->load->model("Coms/ComRekeningTransaksiPembantu");
        // $ps = new ComRekeningTransaksiPembantu();
        $ygditampilkan = url_segment(4);

        $day_now = dtimeNow('d');
        $date_now = dtimeNow('Y-m-d');
        if (isset($_GET['date1'])) {
            $date_now = $_GET['date1'];
        }
        $datemin = $kemarin = date('Y-m-d', strtotime("-30 day", strtotime($date_now)));
        $dateplus = $kemarin = date('Y-m-d', strtotime("1 day", strtotime($date_now)));

        // $date_start = dtimeNow('Y-m-01');
        // if ($day_now < 14) {
        //     $date_start = $datemin;
        // }
        // cekBiru($datemin . " $date_start");

        $_GET['date1'] = $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-d');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : $_GET['date2'] = dtimeNow('Y-m-d');

        /* -----------------------------------------------------------------------------------------------
       * navigasi bulan sebelumnya
       * -----------------------------------------------------------------------------------------------*/
        //<editor-fold desc="bulan sebelumnya">
        // $date_start_sebelumnya = dtimeNow('Y-m-01');
        $date_start_sebelumnya = previousDate($date2);
        // $date_stop_sebelumnya = date("Y-m-t", strtotime($date_start_sebelumnya));
        $date_stop_sebelumnya = date("Y-m-d", strtotime($date_start_sebelumnya));
        $date_sebelumnya = array(
            "date1" => $date_start_sebelumnya,
            "date2" => $date_stop_sebelumnya,
        );
        $nama_bulan_sebelum = date("d F Y", strtotime($date_start_sebelumnya));
        $str_geters = "";
        foreach (($date_sebelumnya + $_GET) as $item_key => $item_val) {
            $str_get = "$item_key=$item_val";

            if ($str_geters == "") {
                $str_geters .= "?$str_get";
            }
            else {
                $str_geters = "$str_geters" . "&$str_get";
            }
        }

        $ulr_bulan_sebelum = current_url() . $str_geters;
        $ulr_bulan_ini = current_url();
        //</editor-fold>

        /* -----------------------------------------------------------------------------------------------
         * navigasi bulan setelahnya
         * -----------------------------------------------------------------------------------------------*/
        //<editor-fold desc="bulan setelahnya">
        $date_start_setelahnya = afterDate($date2);
        $date_stop_setelahnya = date("Y-m-d", strtotime($date_start_setelahnya));
        $date_setelahnya = array(
            "date1" => $date_start_setelahnya,
            "date2" => $date_stop_setelahnya,
        );
        $nama_bulan_setelah = date("d F Y", strtotime($date_start_setelahnya));

        $str_geters = "";
        foreach (($date_setelahnya + $_GET) as $item_key => $item_val) {
            $str_get = "$item_key=$item_val";

            if ($str_geters == "") {
                $str_geters .= "?$str_get";
            }
            else {
                $str_geters = "$str_geters" . "&$str_get";
            }
        }

        $ulr_bulan_setelah = current_url() . $str_geters;
        $nama_bulan_ygtampil = formatTanggal($date2, "d F Y");
        $ulr_tahun_ini = current_url() . "?date1=" . dtimeNow('Y-01-01') . "&date2=" . dtimeNow('Y-m-d');
        //</editor-fold>

        /* -----------------------------------------------------------------------------------------------
         * logik untuk mematikan butun bulan belum sukses jadi masih dimatikan
         * -----------------------------------------------------------------------------------------------*/
        cekHijau("$date_now **$date2**  $date1 //// $date_start_setelahnya *** " . dtimeToSecond($date2) . " $date_stop_setelahnya");
        $btn_disabled = "";
        $btn_disabled_mtd = "";
        $btn_disabled_ytd = "";
        $btn_disabled_aft = "";
        $btn_active = "";
        if (($date1 == dtimeNow('Y-m-01')) && ($date2 == dtimeNow('Y-m-d'))) {
            // cekBiru(dtimeToSecond($date2) ."<br>" . dtimeToSecond($date_start_setelahnya));
            $btn_disabled_mtd = "disabled";
            $btn_disabled_aft = "disabled";
            $btn_active = "btn-primary";
        }
        elseif (($date1 == dtimeNow('Y-01-01')) && ($date2 == dtimeNow('Y-m-d'))) {
            $btn_disabled_ytd = "disabled";
            $btn_disabled_aft = "disabled";
        }
        elseif (($date_stop_setelahnya > dtimeNow('Y-m-d'))) {
            $btn_disabled_aft = "disabled";
        }

        /* -----------------------------------------------------------------------------------------------
         * button navigasi bulanan
         * -----------------------------------------------------------------------------------------------*/
        // cekMerah("$date_start_sebelumnya ****** $date_stop_sebelumnya ******* " . date('Y-m-t', strtotime('2022-02-01')));
        // cekHijau($ulr_bulan_sebelum . " **** $str_get **** $str_geters");
        $btn_td = "<button type='button' class='btn btn-danger' onclick=\"location.href='$ulr_bulan_sebelum'\"><i class='fa fa-chevron-circle-left'></i> $nama_bulan_sebelum </button>";
        $btn_td .= "&nbsp;<button type='button' class='btn btn-danger' disabled>$nama_bulan_ygtampil <i class='fa fa-chevron-circle-down'></i></button>";
        $btn_td .= " <button type='button' class='btn $btn_active btn-danger' $btn_disabled_mtd onclick=\"location . href = '$ulr_bulan_ini'\">Hari ini</button>";
        // $btn_td .= " <button type='button' class='btn $btn_active btn-danger' $btn_disabled_mtd onclick=\"location . href = '$ulr_bulan_ini'\">MTD</button>";
        // $btn_td .= " <button type='button' class='btn btn-danger' $btn_disabled_ytd onclick=\"location . href = '$ulr_tahun_ini'\">YTD</button>";
        $btn_td .= "&nbsp;<button type='button' class='btn btn-danger' $btn_disabled_aft  onclick=\"location.href='$ulr_bulan_setelah'\">$nama_bulan_setelah <i class='fa fa-chevron-circle-right '></i></button>";


        $add_td = "<td>$btn_td</td>";
        // -----------------------------------------------------------------------------------------------

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        $strDate = "";
        if (isset($_GET['date1']) && ($date1 != $date2)) {

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        elseif ($date1 == $date2) {

            $strDate .= formatTanggal($get_date1, 'd F Y');
        }
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        $masterData = array();
        $arrHeaders = array();
        $data = array(
            "mode"        => "indek",
            "title"       => callMenuLabel_he_menu(),
            "subTitle"    => "Raw Data Sales Order",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "permaster",
            "add_td"      => $add_td,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            "sum_null"    => base_url() . "laporan/" . get_class($this) . "/$ygditampilkan" . "$strGet",

        );
        $this->load->view("kas", $data);
    }

    public function viewbl()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        // $ps = new ComRekeningTransaksiPembantu();
        $ygditampilkan = url_segment(4);

        // cekHere($ygditampilkan);
        // matiHere(__LINE__);
        $day_now = dtimeNow('d');
        $date_now = dtimeNow('Y-m-d');
        $month_now = dtimeNow('Y-m');
        if (isset($_GET['date1'])) {
            $date_now = $_GET['date1'];
        }
        $datemin = $kemarin = date('Y-m-d', strtotime("-30 day", strtotime($date_now)));
        $dateplus = $kemarin = date('Y-m-d', strtotime("1 day", strtotime($date_now)));

        $date_start = dtimeNow('Y-m-01');
        /* -----------------------------------------
         * untuk logic-logikan
         * -----------------------------------------*/
        // if ($day_now < 14) {
        //     $date_start = $datemin;
        // }
        // cekBiru($datemin . " $date_start");

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : $_GET['date1'] = $date_start;
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : $_GET['date2'] = dtimeNow('Y-m-d');
        // cekBiru("$date1 $date2");

        /* -----------------------------------------------------------------------------------------------
         * navigasi bulan sebelumnya
         * -----------------------------------------------------------------------------------------------*/
        //<editor-fold desc="bulan sebelumnya">
        // $date_start_sebelumnya = dtimeNow('Y-m-01');
        $date_start_sebelumnya = previousMonth($date2) . "-01";
        $date_stop_sebelumnya = date("Y-m-t", strtotime($date_start_sebelumnya));
        $date_sebelumnya = array(
            "date1" => $date_start_sebelumnya,
            "date2" => $date_stop_sebelumnya,
        );
        $nama_bulan_sebelum = date("F Y", strtotime($date_start_sebelumnya));
        $str_geters = "";
        foreach (($date_sebelumnya + $_GET) as $item_key => $item_val) {
            $str_get = "$item_key=$item_val";

            if ($str_geters == "") {
                $str_geters .= "?$str_get";
            }
            else {
                $str_geters = "$str_geters" . "&$str_get";
            }
        }

        $ulr_bulan_sebelum = current_url() . $str_geters;
        $ulr_bulan_ini = current_url();
        //</editor-fold>

        /* -----------------------------------------------------------------------------------------------
         * navigasi bulan setelahnya
         * -----------------------------------------------------------------------------------------------*/
        //<editor-fold desc="bulan setelahnya">
        $date_start_setelahnya = aftersMonth($date2) . "-01";
        $date_stop_setelahnya = date("Y-m-t", strtotime($date_start_setelahnya));
        $date_setelahnya = array(
            "date1" => $date_start_setelahnya,
            "date2" => $date_stop_setelahnya,
        );
        $nama_bulan_setelah = date("F Y", strtotime($date_start_setelahnya));

        $str_geters = "";
        foreach (($date_setelahnya + $_GET) as $item_key => $item_val) {
            $str_get = "$item_key=$item_val";

            if ($str_geters == "") {
                $str_geters .= "?$str_get";
            }
            else {
                $str_geters = "$str_geters" . "&$str_get";
            }
        }

        $ulr_bulan_setelah = current_url() . $str_geters;
        $nama_bulan_ygtampil = formatTanggal($date2, "F Y");
        //</editor-fold>

        /* -----------------------------------------------------------------------------------------------
         * logik untuk mematikan butun bulan belum sukses jadi masih dimatikan
         * -----------------------------------------------------------------------------------------------*/
        // cekHijau("$date_now **$date2**  $date_start //// $date_start_setelahnya *** " . dtimeToSecond($date2));
        $btn_disabled = "";
        if (dtimeToSecond($date2) <= dtimeToSecond($date_start_setelahnya)) {
            // cekBiru(dtimeToSecond($date2) ."<br>" . dtimeToSecond($date_start_setelahnya));
            // $btn_disabled = "disabled";
        }

        /* -----------------------------------------------------------------------------------------------
         * button navigasi bulanan
         * -----------------------------------------------------------------------------------------------*/
        // cekMerah("$date_start_sebelumnya ****** $date_stop_sebelumnya ******* " . date('Y-m-t', strtotime('2022-02-01')));
        // cekHijau($ulr_bulan_sebelum . " **** $str_get **** $str_geters");
        $btn_td = "<button type='button' class='btn btn-danger' onclick=\"location.href='$ulr_bulan_sebelum'\"><i class='fa fa-chevron-circle-left'></i> $nama_bulan_sebelum </button>";
        $btn_td .= "&nbsp;<button type='button' class='btn btn-danger' disabled>$nama_bulan_ygtampil <i class='fa fa-chevron-circle-down'></i></button>";
        $btn_td .= "<button type='button' class='btn btn-danger' $btn_disabled onclick=\"location . href = '$ulr_bulan_ini'\">bulan ini</button>";
        $btn_td .= "&nbsp;<button type='button' class='btn btn-danger' $btn_disabled  onclick=\"location . href = '$ulr_bulan_setelah'\">$nama_bulan_setelah <i class='fa fa-chevron-circle-right '></i></button>";
        $add_td = "<td>$btn_td</td>";
        // -----------------------------------------------------------------------------------------------

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        $strDate = "";
        if (isset($_GET['date1'])) {

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        $masterData = array();
        $arrHeaders = array();
        $data = array(
            "mode"        => "indek",
            "title"       => callMenuLabel_he_menu(),
            "subTitle"    => "Raw Data Sales Order",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "permaster",
            "add_td"      => $add_td,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            // "sum_null"    => base_url() . "laporan/Penjualan/produksopersales" . "$strGet",
            "sum_null"    => base_url() . "laporan/" . get_class($this) . "/$ygditampilkan" . "$strGet",
            // "sum_satu"    => base_url() . "laporan/Penjualan/produksosales" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Penjualan/produksotransaksi" . "$strGet",
            // "sum_tiga"    => base_url() . "laporan/Penjualan/produksoproduk" . "$strGet",
            // "sum_empat"   => base_url() . "laporan/Penjualan/produksocustomer" . "$strGet",
            // "sum_lima"    => base_url() . "laporan/Crm/produkoutstandingcabang" . "$strGet",
        );
        $this->load->view("kas", $data);
    }

    public function viewBlKas()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        // $ps = new ComRekeningTransaksiPembantu();
        $ygditampilkan = url_segment(4);

        // cekHere($ygditampilkan);
        //         matiHere(__LINE__);
        $day_now = dtimeNow('d');
        $date_now = dtimeNow('Y-m-d');
        $month_now = dtimeNow('Y-m');
        if (isset($_GET['date1'])) {
            $date_now = $_GET['date1'];
        }
        $datemin = $kemarin = date('Y-m-d', strtotime("-30 day", strtotime($date_now)));
        $dateplus = $kemarin = date('Y-m-d', strtotime("1 day", strtotime($date_now)));

        $date_start = dtimeNow('Y-m-01');
        /* -----------------------------------------
         * untuk logic-logikan
         * -----------------------------------------*/
        // if ($day_now < 14) {
        //     $date_start = $datemin;
        // }
        // cekBiru($datemin . " $date_start");

        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : $_GET['date1'] = $date_start;
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : $_GET['date2'] = dtimeNow('Y-m-d');
        // cekBiru("$date1 $date2");

        /* -----------------------------------------------------------------------------------------------
         * navigasi bulan sebelumnya
         * -----------------------------------------------------------------------------------------------*/
        //<editor-fold desc="bulan sebelumnya">
        // $date_start_sebelumnya = dtimeNow('Y-m-01');
        $date_start_sebelumnya = previousMonth($date2) . "-01";
        $date_stop_sebelumnya = date("Y-m-t", strtotime($date_start_sebelumnya));
        $date_sebelumnya = array(
            "date1" => $date_start_sebelumnya,
            "date2" => $date_stop_sebelumnya,
        );
        $nama_bulan_sebelum = date("F Y", strtotime($date_start_sebelumnya));
        $str_geters = "";
        foreach (($date_sebelumnya + $_GET) as $item_key => $item_val) {
            $str_get = "$item_key=$item_val";

            if ($str_geters == "") {
                $str_geters .= "?$str_get";
            }
            else {
                $str_geters = "$str_geters" . "&$str_get";
            }
        }

        $ulr_bulan_sebelum = current_url() . $str_geters;
        $ulr_bulan_ini = current_url();
        //</editor-fold>

        /* -----------------------------------------------------------------------------------------------
         * navigasi bulan setelahnya
         * -----------------------------------------------------------------------------------------------*/
        //<editor-fold desc="bulan setelahnya">
        $date_start_setelahnya = aftersMonth($date2) . "-01";
        $date_stop_setelahnya = date("Y-m-t", strtotime($date_start_setelahnya));
        $date_setelahnya = array(
            "date1" => $date_start_setelahnya,
            "date2" => $date_stop_setelahnya,
        );
        $nama_bulan_setelah = date("F Y", strtotime($date_start_setelahnya));

        $str_geters = "";
        foreach (($date_setelahnya + $_GET) as $item_key => $item_val) {
            $str_get = "$item_key=$item_val";

            if ($str_geters == "") {
                $str_geters .= "?$str_get";
            }
            else {
                $str_geters = "$str_geters" . "&$str_get";
            }
        }

        $ulr_bulan_setelah = current_url() . $str_geters;
        $nama_bulan_ygtampil = formatTanggal($date2, "F Y");
        //</editor-fold>

        /* -----------------------------------------------------------------------------------------------
         * logik untuk mematikan butun bulan belum sukses jadi masih dimatikan
         * -----------------------------------------------------------------------------------------------*/
        // cekHijau("$date_now **$date2**  $date_start //// $date_start_setelahnya *** " . dtimeToSecond($date2));
        $btn_disabled = "";
        if (dtimeToSecond($date2) <= dtimeToSecond($date_start_setelahnya)) {
            // cekBiru(dtimeToSecond($date2) ."<br>" . dtimeToSecond($date_start_setelahnya));
            // $btn_disabled = "disabled";
        }

        /* -----------------------------------------------------------------------------------------------
         * button navigasi bulanan
         * -----------------------------------------------------------------------------------------------*/
        // cekMerah("$date_start_sebelumnya ****** $date_stop_sebelumnya ******* " . date('Y-m-t', strtotime('2022-02-01')));
        // cekHijau($ulr_bulan_sebelum . " **** $str_get **** $str_geters");
        $btn_td = "<button type='button' class='btn btn-danger' onclick=\"location.href='$ulr_bulan_sebelum'\"><i class='fa fa-chevron-circle-left'></i> $nama_bulan_sebelum </button>";
        $btn_td .= "&nbsp;<button type='button' class='btn btn-danger' disabled>$nama_bulan_ygtampil <i class='fa fa-chevron-circle-down'></i></button>";
        $btn_td .= "<button type='button' class='btn btn-danger' $btn_disabled onclick=\"location . href = '$ulr_bulan_ini'\">bulan ini</button>";
        $btn_td .= "&nbsp;<button type='button' class='btn btn-danger' $btn_disabled  onclick=\"location . href = '$ulr_bulan_setelah'\">$nama_bulan_setelah <i class='fa fa-chevron-circle-right '></i></button>";
        $add_td = "<td>$btn_td</td>";
        // -----------------------------------------------------------------------------------------------

        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        $strDate = "";
        if (isset($_GET['date1'])) {

            $strDate .= formatField_he_format("fulldate", $get_date1);
            $strDate .= " - " . formatField_he_format("fulldate", $get_date2);
        }
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        $masterData = array();
        $arrHeaders = array();
        $data = array(
            // "mode"        => "view_rek_kas",
            "mode"        => "indek",
            "title"       => callMenuLabel_he_menu(),
            "subTitle"    => "Raw Data Sales Order",
            "modul_path"  => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "data_id"     => "permaster",
            "add_td"      => $add_td,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            // "sum_null"    => base_url() . "laporan/Penjualan/produksopersales" . "$strGet",
            "sum_null"    => base_url() . "laporan/" . get_class($this) . "/$ygditampilkan" . "$strGet",
            // "sum_satu"    => base_url() . "laporan/Penjualan/produksosales" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Penjualan/produksotransaksi" . "$strGet",
            // "sum_tiga"    => base_url() . "laporan/Penjualan/produksoproduk" . "$strGet",
            // "sum_empat"   => base_url() . "laporan/Penjualan/produksocustomer" . "$strGet",
            // "sum_lima"    => base_url() . "laporan/Crm/produkoutstandingcabang" . "$strGet",
        );
        $this->load->view("kas", $data);
    }
}