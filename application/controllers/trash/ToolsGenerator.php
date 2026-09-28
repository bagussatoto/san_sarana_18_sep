<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ToolsGenerator extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->masterConfigUi = $this->config->item("heTransaksi_ui");
    }

    function satuan_nilai()
    {
        $this->db->select("
            id,
            nama,
            barcode,
            satuan_nilai,
            kategori_id,
            kategori_nama,
            sub_kategori_id,
            sub_kategori_nama,
            produk_part_kategori_id,
            produk_part_kategori_nama,
            produk_part_ukuran_id,
            produk_part_ukuran_nama
        ");
//        $this->db->where("kategori_id=3 and sub_kategori_id=5 and status=1 and trash=0 and satuan_nilai is null");
        $this->db->where("kategori_id=3 and sub_kategori_id=5 and status=1 and trash=0");
        $spare_part = $this->db->get("produk")->result();
        $wl = array("M", "m", "meter", "METER", "EMETER", "METETR");
        $this->db->trans_start();
        foreach ($spare_part as $k => $row) {
            $pid = $row->id;
            $sName = str_replace("  ", " ", $row->nama);
            $arr = explode(" ", $sName);
            $query = "UPDATE produk SET satuan_nilai={satuan_nilai} where id = $pid";
            switch (count($arr)) {
                case "3":
//                    arrPrint($arr);
                    $nilai_1 = 1;
                    $satuan_1 = 2;
                    $nilai_2 = 2;
                    $satuan_2 = 3;

                    if ($arr[$nilai_1] * 1 > 0) {
                        if (in_array($arr[$satuan_1], $wl)) {
//                            echo $pid . "-" . $sName . " <b>(".count($arr).") | (".$arr[1].")</b><br>";
                            $thisQuery = str_replace("{satuan_nilai}", $arr[$nilai_1], $query);
                            $thisQuery = str_replace(",", ".", $thisQuery);
                            echo $thisQuery . "<br>";
                            $this->db->query($thisQuery);
                        }
                        else {
                            echo "<div style='color: red'>";
                            arrPrint($arr);
                            echo $sName . " <b>(" . count($arr) . ")</b><br>";
                            echo "</div>";
                        }
                    }
                    else {
                        if ($arr[$nilai_2] * 1 > 0) {
                            if (isset($arr[$satuan_2])) {
                                if (in_array($arr[$satuan_2], $wl)) {
                                    echo $pid . "-" . $sName . " <b>(" . count($arr) . ") | (" . $arr[$nilai_2] . ")</b><br>";
                                    $thisQuery = str_replace("{satuan_nilai}", $arr[$nilai_2], $query);
                                    $thisQuery = str_replace(",", ".", $thisQuery);
                                    echo $thisQuery . "<br>";
                                }
                                else {
                                    echo "<div style='color: red'>";
                                    arrPrint($arr);
                                    echo $sName . " <b>(" . count($arr) . ")</b><br>";
                                    echo "</div>";
                                }
                            }
                        }
                        else {
                            echo "<div style='color: red'>";
                            arrPrint($arr);
                            echo $sName . " <b>(" . count($arr) . ")</b><br>";
                            echo "</div>";
                        }
                    }
                    break;
                case "4":
//                    arrPrint($arr);
                    $nilai_1 = 2;
                    $satuan_1 = 3;
                    $nilai_2 = 3;
                    $satuan_2 = 4;

                    if ($arr[$nilai_1] * 1 > 0) {
                        if (in_array($arr[$satuan_1], $wl)) {
//                            echo $pid . "-" . $sName . " <b>(".count($arr).") | (".$arr[1].")</b><br>";
                            $thisQuery = str_replace("{satuan_nilai}", $arr[$nilai_1], $query);
                            $thisQuery = str_replace(",", ".", $thisQuery);
                            echo $thisQuery . "<br>";
                            $this->db->query($thisQuery);
                        }
                        else {
                            echo "<div style='color: red'>";
                            arrPrint($arr);
                            echo $sName . " <b>(" . count($arr) . ")</b><br>";
                            echo "</div>";
                        }
                    }
                    else {
                        if ($arr[$nilai_2] * 1 > 0) {
                            if (isset($arr[$satuan_2])) {
                                if (in_array($arr[$satuan_2], $wl)) {
                                    echo $pid . "-" . $sName . " <b>(" . count($arr) . ") | (" . $arr[$nilai_2] . ")</b><br>";
                                    $thisQuery = str_replace("{satuan_nilai}", $arr[$nilai_2], $query);
                                    $thisQuery = str_replace(",", ".", $thisQuery);
                                    echo $thisQuery . "<br>";
                                    $this->db->query($thisQuery);
                                }
                                else {
                                    echo "<div style='color: red'>";
                                    arrPrint($arr);
                                    echo $sName . " <b>(" . count($arr) . ")</b><br>";
                                    echo "</div>";
                                }
                            }
                        }
                        else {
                            echo "<div style='color: red'>";
                            arrPrint($arr);
                            echo $sName . " <b>(" . count($arr) . ")</b><br>";
                            echo "</div>";
                        }
                    }
                    break;
                case "5":
//                    arrPrint($arr);
                    $nilai_1 = 3;
                    $satuan_1 = 4;
                    $nilai_2 = 4;
                    $satuan_2 = 5;

                    if ($arr[$nilai_1] * 1 > 0) {
                        if (in_array($arr[$satuan_1], $wl)) {
//                            echo $pid . "-" . $sName . " <b>(".count($arr).") | (".$arr[1].")</b><br>";
                            $thisQuery = str_replace("{satuan_nilai}", $arr[$nilai_1], $query);
                            $thisQuery = str_replace(",", ".", $thisQuery);
                            echo $thisQuery . "<br>";
                            $this->db->query($thisQuery);
                        }
                        else {
                            echo "<div style='color: red'>";
                            arrPrint($arr);
                            echo $sName . " <b>(" . count($arr) . ")</b><br>";
                            echo "</div>";
                        }
                    }
                    else {
                        if ($arr[$nilai_2] * 1 > 0) {
                            if (isset($arr[$satuan_2])) {
                                if (in_array($arr[$satuan_2], $wl)) {
                                    echo $pid . "-" . $sName . " <b>(" . count($arr) . ") | (" . $arr[$nilai_2] . ")</b><br>";
                                    $thisQuery = str_replace("{satuan_nilai}", $arr[$nilai_2], $query);
                                    $thisQuery = str_replace(",", ".", $thisQuery);
                                    echo $thisQuery . "<br>";
                                    $this->db->query($thisQuery);
                                }
                                else {
                                    echo "<div style='color: red'>";
                                    arrPrint($arr);
                                    echo $sName . " <b>(" . count($arr) . ")</b><br>";
                                    echo "</div>";
                                }
                            }
                        }
                        else {
                            echo "<div style='color: red'>";
                            arrPrint($arr);
                            echo $sName . " <b>(" . count($arr) . ")</b><br>";
                            echo "</div>";
                        }
                    }
                    break;
                case "6":
//                    arrPrint($arr);
                    $nilai_1 = 4;
                    $satuan_1 = 5;
                    $nilai_2 = 5;
                    $satuan_2 = 6;

                    if ($arr[$nilai_1] * 1 > 0) {
                        if (in_array($arr[$satuan_1], $wl)) {
//                            echo $pid . "-" . $sName . " <b>(".count($arr).") | (".$arr[1].")</b><br>";
                            $thisQuery = str_replace("{satuan_nilai}", $arr[$nilai_1], $query);
                            $thisQuery = str_replace(",", ".", $thisQuery);
                            echo $thisQuery . "<br>";
                            $this->db->query($thisQuery);
                        }
                        else {
                            echo "<div style='color: red'>";
                            arrPrint($arr);
                            echo $sName . " <b>(" . count($arr) . ")</b><br>";
                            echo "</div>";
                        }
                    }
                    else {
                        if ($arr[$nilai_2] * 1 > 0) {
                            if (isset($arr[$satuan_2])) {
                                if (in_array($arr[$satuan_2], $wl)) {
                                    echo $pid . "-" . $sName . " <b>(" . count($arr) . ") | (" . $arr[$nilai_2] . ")</b><br>";
                                    $thisQuery = str_replace("{satuan_nilai}", $arr[$nilai_2], $query);
                                    $thisQuery = str_replace(",", ".", $thisQuery);
                                    echo $thisQuery . "<br>";
                                    $this->db->query($thisQuery);
                                }
                                else {
                                    echo "<div style='color: red'>";
                                    arrPrint($arr);
                                    echo $sName . " <b>(" . count($arr) . ")</b><br>";
                                    echo "</div>";
                                }
                            }
                        }
                        else {
                            echo "<div style='color: red'>";
                            arrPrint($arr);
                            echo $sName . " <b>(" . count($arr) . ")</b><br>";
                            echo "</div>";
                        }
                    }
                    break;
                case "7":
//                    arrPrint($arr);
                    echo $sName . "<br>";
                    $nilai_1 = 5;
                    $satuan_1 = 6;
                    $nilai_2 = 6;
                    $satuan_2 = 7;

                    if ($arr[$nilai_1] * 1 > 0) {
                        if (in_array($arr[$satuan_1], $wl)) {
//                            echo $pid . "-" . $sName . " <b>(".count($arr).") | (".$arr[1].")</b><br>";
                            $thisQuery = str_replace("{satuan_nilai}", $arr[$nilai_1], $query);
                            $thisQuery = str_replace(",", ".", $thisQuery);
                            echo $thisQuery . "<br>";
                            $this->db->query($thisQuery);
                        }
                        else {
                            echo "<div style='color: red'>";
                            arrPrint($arr);
                            echo $sName . " <b>(" . count($arr) . ")</b><br>";
                            echo "</div>";
                        }
                    }
                    else {
                        if ($arr[$nilai_2] * 1 > 0) {
                            if (isset($arr[$satuan_2])) {
                                if (in_array($arr[$satuan_2], $wl)) {
                                    echo $pid . "-" . $sName . " <b>(" . count($arr) . ") | (" . $arr[$nilai_2] . ")</b><br>";
                                    $thisQuery = str_replace("{satuan_nilai}", $arr[$nilai_2], $query);
                                    $thisQuery = str_replace(",", ".", $thisQuery);
                                    echo $thisQuery . "<br>";
                                    $this->db->query($thisQuery);
                                }
                                else {
                                    echo "<div style='color: red'>";
                                    arrPrint($arr);
                                    echo $sName . " <b>(" . count($arr) . ")</b><br>";
                                    echo "</div>";
                                }
                            }
                        }
                        else {
                            echo "<div style='color: red'>";
                            arrPrint($arr);
                            echo $sName . " <b>(" . count($arr) . ")</b><br>";
                            echo "</div>";
                        }
                    }
                    break;
                case "8":
//                    arrPrint($arr);
                    $nilai_1 = 6;
                    $satuan_1 = 7;
                    $nilai_2 = 7;
                    $satuan_2 = 8;

                    if ($arr[$nilai_1] * 1 > 0) {
                        if (in_array($arr[$satuan_1], $wl)) {
//                            echo $pid . "-" . $sName . " <b>(".count($arr).") | (".$arr[1].")</b><br>";
                            $thisQuery = str_replace("{satuan_nilai}", $arr[$nilai_1], $query);
                            $thisQuery = str_replace(",", ".", $thisQuery);
                            echo $thisQuery . "<br>";
                            $this->db->query($thisQuery);
                        }
                        else {
                            echo "<div style='color: red'>";
                            arrPrint($arr);
                            echo $sName . " <b>(" . count($arr) . ")</b><br>";
                            echo "</div>";
                        }
                    }
                    else {
                        if ($arr[$nilai_2] * 1 > 0) {
                            if (isset($arr[$satuan_2])) {
                                if (in_array($arr[$satuan_2], $wl)) {
                                    echo $pid . "-" . $sName . " <b>(" . count($arr) . ") | (" . $arr[$nilai_2] . ")</b><br>";
                                    $thisQuery = str_replace("{satuan_nilai}", $arr[$nilai_2], $query);
                                    $thisQuery = str_replace(",", ".", $thisQuery);
                                    echo $thisQuery . "<br>";
                                    $this->db->query($thisQuery);
                                }
                                else {
                                    echo "<div style='color: red'>";
                                    arrPrint($arr);
                                    echo $sName . " <b>(" . count($arr) . ")</b><br>";
                                    echo "</div>";
                                }
                            }
                        }
                        else {
                            echo "<div style='color: red'>";
                            arrPrint($arr);
                            echo $sName . " <b>(" . count($arr) . ")</b><br>";
                            echo "</div>";
                        }
                    }
                    break;
                case "9":
//                    arrPrint($arr);
                    $nilai_1 = 7;
                    $satuan_1 = 8;
                    $nilai_2 = 8;
                    $satuan_2 = 9;

                    if ($arr[$nilai_1] * 1 > 0) {
                        if (in_array($arr[$satuan_1], $wl)) {
//                            echo $pid . "-" . $sName . " <b>(".count($arr).") | (".$arr[1].")</b><br>";
                            $thisQuery = str_replace("{satuan_nilai}", $arr[$nilai_1], $query);
                            $thisQuery = str_replace(",", ".", $thisQuery);
                            echo $thisQuery . "<br>";
                            $this->db->query($thisQuery);
                        }
                        else {
                            echo "<div style='color: red'>";
                            arrPrint($arr);
                            echo $sName . " <b>(" . count($arr) . ")</b><br>";
                            echo "</div>";
                        }
                    }
                    else {
                        if ($arr[$nilai_2] * 1 > 0) {
                            if (isset($arr[$satuan_2])) {
                                if (in_array($arr[$satuan_2], $wl)) {
                                    echo $pid . "-" . $sName . " <b>(" . count($arr) . ") | (" . $arr[$nilai_2] . ")</b><br>";
                                    $thisQuery = str_replace("{satuan_nilai}", $arr[$nilai_2], $query);
                                    $thisQuery = str_replace(",", ".", $thisQuery);
                                    echo $thisQuery . "<br>";
                                    $this->db->query($thisQuery);
                                }
                                else {
                                    echo "<div style='color: red'>";
                                    arrPrint($arr);
                                    echo $sName . " <b>(" . count($arr) . ")</b><br>";
                                    echo "</div>";
                                }
                            }
                        }
                        else {
                            echo "<div style='color: red'>";
                            arrPrint($arr);
                            echo $sName . " <b>(" . count($arr) . ")</b><br>";
                            echo "</div>";
                        }
                    }
                    break;
                default:
//                    echo "<div style='color: red'>";
//                    arrPrint($arr);
//                    echo $sName . " <b>(".count($arr).")</b><br>";
//                    echo "</div>";
                    break;
            }
        }

//        matiHere("DONE:: belum commit");
        $this->db->trans_commit();
    }

    function nama_produk()
    {

        $this->db->select("
            id,
            nama,
            barcode,
            satuan_nilai,
            kategori_id,
            kategori_nama,
            sub_kategori_id,
            sub_kategori_nama,
            produk_part_kategori_id,
            produk_part_kategori_nama,
            produk_part_ukuran_id,
            produk_part_ukuran_nama
        ");

        $this->db->where("status=1 and trash=0");
        $spare_part = $this->db->get("produk")->result();
        $this->db->trans_start();

        foreach ($spare_part as $k => $row) {
            $pid = $row->id;
            $sName = trim(str_replace("  ", " ", $row->nama));
            $query = "UPDATE produk SET nama='$sName' WHERE id=$pid";
            echo $query . "<br>";
            $this->db->query($query);
        }

//        matiHere("DONE:: belum commit");
        $this->db->trans_commit();
    }


    public function generatePiutangDagangVoid()
    {
        $this->load->helper("he_angka");
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlPaymentSource");
        $this->load->model("Coms/ComRekeningPembantuCustomer");
        $this->load->model("Coms/ComRekening");
        $this->load->model("Coms/ComJurnal");

        $arrDataSource = array(
//            1 => array(
//                "extern_id" => 278,
//                "extern_nama" => "SETIA JAYA ELECTRONIC",
//                "label" => "koreksi piutang dagang pemindahbukuan SETIA JAYA ELECTRONIC request void by Everet",
//            ),
//            2 => array(
//                "extern_id" => 221,
//                "extern_nama" => "PT. SUKSES MAKMUR SOLUSI",
//                "label" => "koreksi piutang dagang pemindahbukuan PT. SUKSES MAKMUR SOLUSI request void by Everet",
//            ),
//            3 => array(
//                "extern_id" => 203,
//                "extern_nama" => "PT. KURNIAMITRA DUTA SENTOSA, Tbk",
//                "label" => "koreksi piutang dagang pemindahbukuan PT. KURNIAMITRA DUTA SENTOSA, Tbk request void by Everet",
//            ),
//            4 => array(
//                "extern_id" => 184,
//                "extern_nama" => "PT. DAPUR COKELAT INDONESIA",
//                "label" => "koreksi piutang dagang pemindahbukuan PT. DAPUR COKELAT INDONESIA request void by Everet",
//            ),
//            5 => array(
//                "extern_id" => 115,
//                "extern_nama" => "HERU (PAMULANG)",
//                "label" => "koreksi piutang dagang pemindahbukuan HERU (PAMULANG) request void by Everet",
//            ),
            6 => array(
                "extern_id" => 152,
                "extern_nama" => "NEW GLODOK ELECRONIC",
                "label" => "koreksi piutang dagang pemindahbukuan NEW GLODOK ELECRONIC request void by Everet",
            ),
            7 => array(
                "extern_id" => 87,
                "extern_nama" => "VICA AC",
                "label" => "koreksi piutang dagang pemindahbukuan VICA AC request void by Everet",
            ),
        );
        $selectKey = 7;

        // region transaksi
        $cabangID = "1";
        $cabangNama = "cabang 1";
        $gudangID = "-10";
        $gudangNama = "default warehouse at branch #1";
        $cabang2ID = "-1";
        $cabang2Nama = "pusat dc";
        $gudang2ID = "-1";
        $gudang2Nama = "default warehouse at branch #-1";
        $olehID = "100";
        $olehNama = "system";
        $tokoID = "0";
        $tokoNama = "";
        $pihakID = $arrDataSource[$selectKey]["extern_id"];
        $pihakNama = $arrDataSource[$selectKey]["extern_nama"];
        $jenis = "99999";
        $this->jenisTr = $jenisTr = "99999";
        $jenisTrMaster = "99999";
        $dtime = date("Y-m-d H:i:s");
        $fulldate = date("Y-m-d");
        $ppnFactor = 11;
        $divID = 18;
        $jenis_target = "749";
        $pym_label = "piutang dagang";
        $pym_jenis = "7778";
        $referencetransaksiID = "15896";
        // endregion transaksi

        $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();

        $this->db->trans_start();

        $mainGate = array(
            "olehID" => $olehID,
            "olehName" => $olehNama,
            "sellerID" => "",
            "sellerName" => "",
            "pihakID" => $pihakID,
            "pihakName" => $pihakNama,
            "placeID" => $cabangID,
            "placeName" => $cabangNama,
            "cabangID" => $cabangID,
            "cabangName" => $cabangNama,
            "gudangID" => $gudangID,
            "gudangName" => $gudangNama,
            "place2ID" => $cabang2ID,
            "place2Name" => $cabang2Nama,
            "cabang2ID" => $cabang2ID,
            "cabang2Name" => $cabang2Nama,
            "gudang2ID" => $gudang2ID,
            "gudang2Name" => $gudang2Nama,
            "tokoEmail" => "",
            "jenisTr" => $jenis,
            "jenisTrMaster" => $jenisTrMaster,
            "jenisTrTop" => $jenis,
            "jenisTrName" => "",
            "stepNumber" => "",
            "stepCode" => $jenis,
            "dtime" => $dtime,
            "fulldate" => $fulldate,
            "ppnFactor" => $ppnFactor,
            "dummyElement" => "yes",
            "dummyElement__label" => "yes",
            "dummyElement__name" => "yes",
            "divID" => $divID,
            "jenis" => $jenis,
            "transaksi_jenis" => $jenis,
            "next_step_code" => $jenis,
            "next_group_code" => "o_holding",
            "step_number" => 1,
            "step_current" => 1,
            "longitude" => "",
            "lattitude" => "",
            "accuracy" => "",
            "description" => $arrDataSource[$selectKey]["extern_id"],
            "keterangan" => $arrDataSource[$selectKey]["extern_id"],
            "referenceTransaksiID" => $referencetransaksiID,
            "pymJenisTr" => $pym_jenis,
        );
        $detailGate = array();
//        arrPrintPink($mainGate);
        $components = array(
            "master" => array(
//                // JURNAL CABANG
                array(
                    "comName" => "Jurnal",
                    "loop" => array(
                        //-------------------
                        "2040010" => "-sisa", // hutang ke pusat
                        "1010020010" => "-sisa", // piutang dagang
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                array(
                    "comName" => "Rekening",
                    "loop" => array(
                        //-------------------
                        "2040010" => "-sisa", // hutang ke pusat
                        "1010020010" => "-sisa", // piutang dagang
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
//                //pembantu antarcabang (pusat)
                array(
                    "comName" => "RekeningPembantuAntarcabang",
                    "loop" => array(
                        "2040010" => "-sisa", // hutang ke pusat
                    ),
                    "static" => array(
                        "cabang_id" => "cabangID",
                        "cabang2_id" => "cabang2ID",
                        "cabang2_nama" => "cabang2Name",
                        "extern_id" => "cabang2ID",
                        "extern_nama" => "cabang2Name",
                        "jenis" => "jenisTr",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                array(
                    "comName" => "RekeningPembantuCustomer",
                    "loop" => array(
                        "1010020010" => "-sisa", // piutang dagang
                    ),
                    "static" => array(
                        "cabang_id" => "cabangID",
                        "extern_id" => "pihakID",
                        "extern_nama" => "pihakName",
                        "jenis" => "jenisTr",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),

                // JURNAL DC/PUSAT
                array(
                    "comName" => "Jurnal",
                    "loop" => array(
                        //-------------------
                        "1010060010" => "-sisa", // piutang cabang
                        "3010020" => "-sisa", // modal
                    ),
                    "static" => array(
                        "cabang_id" => "place2ID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                array(
                    "comName" => "Rekening",
                    "loop" => array(
                        //-------------------
                        "1010060010" => "-sisa", // piutang cabang
                        "3010020" => "-sisa", // modal
                    ),
                    "static" => array(
                        "cabang_id" => "place2ID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                //pembantu antarcabang (caabng)
                array(
                    "comName" => "RekeningPembantuAntarcabang",
                    "loop" => array(
                        "1010060010" => "-sisa", // piutang cabang
                    ),
                    "static" => array(
                        "cabang_id" => "place2ID",
                        "cabang2_id" => "place2ID",
                        "cabang2_nama" => "place2Name",
                        "extern_id" => "cabangID",
                        "extern_nama" => "cabangName",
                        "jenis" => "jenisTr",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),

            ),
            "detail" => array(),
        );
        $postProcessor = array(
            "master" => array(
                array(
                    "comName" => "PaymentSource",
                    "loop" => array(),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "pihakID",
                        "extern_nama" => "pihakName",
                        "label" => ".piutang dagang",
                        "jenis" => "pymJenisTr",
                        "transaksi_id" => "referenceTransaksiID",
                        "returned" => "sisa",
//                        "sisa" => ".0",
//                        "tabel_id" => "tabel_id",
                    ),
                    "reversable" => true,
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
            ),
            "detail" => array(),
        );


        // region payment source
        $pym = New MdlPaymentSource();
        $pym->addFilter("cabang_id=$cabangID");
        $pym->addFilter("target_jenis=$jenis_target");
        $pym->addFilter("label=$pym_label");
        $pym->addFilter("jenis=$pym_jenis");
        $pym->addFilter("extern_id=$pihakID");
        $pymTmp = $pym->lookupAll()->result();
        showLast_query("biru");
        if (sizeof($pymTmp) > 0) {
            foreach ($pymTmp as $spec) {
                $id = $spec->extern_id;
                $nama = $spec->extern_nama;
                $spec_new = (array)$spec;
                $spec_new["id"] = $id;
                $spec_new["nama"] = $nama;
                $spec_new["name"] = $nama;
                $detailGate[$spec->extern_id] = $spec_new;
                $mainGate["sisa"] = $spec_new["sisa"];
            }
        }
        // endregion payment source
//        arrPrintPink($detailGate);
//        arrPrintPink($mainGate);


        $tableIn = array(
            "master" => array(
                "jenis_master" => "jenisTrMaster",
                "jenis_top" => "jenisTrTop",
                "jenis" => "jenisTr",
                "jenis_label" => "jenisTrName",
                "div_id" => "divID",
                "div_nama" => "divName",
                "dtime" => "dtime",
                "fulldate" => "fulldate",
                "oleh_id" => "olehID",
                "oleh_nama" => "olehName",
                "customers_id" => "pihakID",
                "customers_nama" => "pihakName",
                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "new_net2",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",
                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "toko_id" => "tokoID",
                "toko_nama" => "tokoName",

            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "sisa",
                "satuan" => "satuan",
            ),
        );
        foreach ($tableIn["master"] as $key => $val) {
            $tableIn_master[$key] = isset($mainGate[$val]) ? $mainGate[$val] : "";
        }
        foreach ($detailGate as $idd => $iddSpec) {
            foreach ($tableIn["detail"] as $key => $val) {
                $tableIn_detail[$idd][$key] = isset($iddSpec[$val]) ? $iddSpec[$val] : "";
            }
            foreach ($mainGate as $ii => $vv) {
                if (!isset($detailGate[$idd][$ii])) {
                    $detailGate[$idd][$ii] = $vv;
                }
            }
        }

        $this->cCode = $cCode = "_TR_" . $jenis;
        $this->cCodeData[$cCode] = array(
            "main" => $mainGate,
            "items" => isset($detailGate) ? $detailGate : array(),
            "tableIn_master" => $tableIn_master,
            "tableIn_detail" => isset($tableIn_detail) ? $tableIn_detail : array(),
        );
//        cekBiru($cCode);
//        arrPrint($this->cCodeData[$cCode]);
//        mati_disini(__LINE__);

        // MEMBUAT TRANSAKSI
        $pakai_ini = 1;
        if ($pakai_ini == 1) {
            //region dynamic counters

            $counters = array(
                "stepCode|placeID",
                "stepCode|tokoID",
                "stepCode|tokoID|placeID",
                "stepCode|tokoID|olehID",
                "stepCode|tokoID|placeID|olehID",
            );
            $formatNota = "stepCode,placeID,stepCode|tokoID|placeID,stepCode|tokoID";

            //region penomoran receipt
            $this->load->model("CustomCounter");
            $cn = new CustomCounter("transaksi");
            $cn->setType("transaksi");
            $configCustomParams = $counters;
            if (sizeof($configCustomParams) > 0) {
                $cContent = array();
                foreach ($configCustomParams as $i => $cRawParams) {
                    $cParams = explode("|", $cRawParams);
                    $cValues = array();
                    foreach ($cParams as $param) {
                        $cValues[$i][$param] = $this->cCodeData[$cCode]["main"][$param];
                    }
                    $cRawValues = implode("|", $cValues[$i]);
                    $paramSpec = $cn->getNewCount($cParams, $cValues[$i], $tokoID);

                    $cContent[$cRawParams][$cRawValues] = $paramSpec["value"];
                    switch ($paramSpec["id"]) {
                        case 0: //===counter type is new
                            $addData = array(
//                                "toko_id" => $tokoID,
//                                "toko_nama" => $tokoNama,
                            );
                            $paramKeyRaw = print_r($cParams, true);
                            $paramValuesRaw = print_r($cValues[$i], true);
                            $cn->writeNewCount($cParams, $cValues[$i], $paramKeyRaw, $paramValuesRaw, $addData);
                            break;
                        default: //===counter to be updated
                            $cn->updateCount($paramSpec["id"], $paramSpec["value"]);
                            break;
                    }
                }
            }

            $appliedCounters = base64_encode(serialize($cContent));
            $appliedCounters_inText = print_r($cContent, true);

            $cn = new CustomCounter("transaksi");
            $cn->setType("transaksi");
            $counterForNumber = array($formatNota);
            foreach ($counterForNumber as $i => $c0RawParams) {
                $c0Params = explode(",", $c0RawParams);
                foreach ($c0Params as $k => $cRawParams) {
                    $dParams = explode("|", $cRawParams);
                    if (count($dParams) > 1) {
                        if (!in_array($cRawParams, $counters)) {
                            die(__LINE__ . "( $cRawParams ) Used number should be registered in counters config as well");
                        }
                    }
                }
            }

            $tmpNomorNota = "";
            $arrNomorNota = array();
            foreach ($counterForNumber as $i => $c0RawParams) {
                $c0Params = explode(",", $c0RawParams);
                $c0Values = array();
                foreach ($c0Params as $k => $cRawParams) {
                    $arrRawParams = explode("|", $cRawParams);
                    if (sizeof($arrRawParams) > 1) {
                        $cRawParamsValues = array();
                        foreach ($arrRawParams as $key) {
                            $cRawParamsValues[$key] = $this->cCodeData[$cCode]['main'][$key];
                        }
                        $cRawParamsValuesK = implode("|", array_keys($cRawParamsValues));
                        $cRawParamsValuesV = implode("|", $cRawParamsValues);
                        $arrNomorNota[] = digit_4($cContent[$cRawParamsValuesK][$cRawParamsValuesV]);
                    }
                    else {
                        $cRawParamsValuesK = $arrRawParams[0];
                        $cRawParamsValuesV = $this->cCodeData[$cCode]['main'][$arrRawParams[0]];
                        if ($arrRawParams[0] == "fulldate") {
                            $arrNomorNota[] = $arrRawParams[0] . "|" . date("mY", strtotime($cRawParamsValuesV));
                        }
                        elseif ($arrRawParams[0] == "stepCode") {
                            $arrNomorNota[] = $cRawParamsValuesV; //ini harus ori tidak boleh di masking/ diformat
//                            $arrNomorNota[] = digit_4($cContent[$cRawParamsValuesK][$cRawParamsValuesV]);
                        }
                        elseif ($arrRawParams[0] == "placeID") {
                            $arrNomorNota[] = digit_2($cRawParamsValuesV);
                        }
                        elseif ($arrRawParams[0] == "customerID") {
                            $arrNomorNota[] = digit_4($cRawParamsValuesV);
                        }
                        elseif ($arrRawParams[0] == "olehID") {
                            $arrNomorNota[] = digit_4($cRawParamsValuesV);
                        }
                        elseif ($arrRawParams[0] == "supplierID") {
                            $arrNomorNota[] = digit_4($cRawParamsValuesV);
                        }
                        else {
                            $arrNomorNota[] = $cRawParamsValuesV;
                        }
                    }
                }
            }

            $stepNumber = 1;
            $tmpNomorNota = implode("-", $arrNomorNota);
//            cekMerah(":: $tmpNomorNota ::");
            //endregion penomoran receipt

            //region addition on master
            $nextProp = array(
                "num" => 0,
                "code" => "",
                "label" => "",
                "groupID" => "",
            );
            $addValues = array(
                "counters" => $appliedCounters,
                'counters_intext' => $appliedCounters_inText,
                'nomer' => $tmpNomorNota,
                'dtime' => date("Y-m-d H:i:s"),
                'fulldate' => date("Y-m-d"),
                "step_avail" => 1,
                "step_number" => 1,
                "step_current" => 1,
                "next_step_num" => $nextProp["num"],
                "next_step_code" => $nextProp["code"],
                "next_step_label" => $nextProp["label"],
                "next_group_code" => $nextProp["groupID"],
                "tail_number" => 1,
                "tail_code" => "",
            );
            foreach ($addValues as $key => $val) {
                $this->cCodeData[$cCode]["tableIn_master"][$key] = $val;
            }
            //endregion

            //region addition on detail
            $addSubValues = array(
                "sub_step_number" => 1,
                "sub_step_current" => 1,
                "sub_step_avail" => 1,
                "next_substep_num" => $nextProp["num"],
                "next_substep_code" => $nextProp["code"],
                "next_substep_label" => $nextProp["label"],
                "next_subgroup_code" => $nextProp["groupID"],
                "sub_tail_number" => 1,
                "sub_tail_code" => "",
            );
            foreach ($this->cCodeData[$cCode]["tableIn_detail"] as $id => $dSpec) {
                foreach ($addSubValues as $key => $val) {
                    $this->cCodeData[$cCode]["tableIn_detail"][$id][$key] = $val;
                }
            }
            //endregion

            //endregion

            //region numbering tambahan
            $this->load->library("CounterNumber");
            $ccn = new CounterNumber();
            $ccn->setCCode($this->cCode);
            $ccn->setJenisTr($this->jenisTr);
            $ccn->setTransaksiGate($this->cCodeData[$cCode]["tableIn_master"]);
            $ccn->setMainGate($this->cCodeData[$cCode]["main"]);
            $ccn->setItemsGate($this->cCodeData[$cCode]["items"]);

            if (isset($this->cCodeData[$cCode]["items2_sum"])) {
                $ccn->setItems2SumGate($this->cCodeData[$cCode]["items2_sum"]);
            }

            $new_counter = $ccn->getCounterNumber();

            cekHitam("jenistr yang disett dari create " . $this->jenisTr);

            if (isset($new_counter["main"]) && sizeof($new_counter["main"]) > 0) {
                foreach ($new_counter["main"] as $ckey => $cval) {
                    $this->cCodeData[$cCode]["tableIn_master"][$ckey] = $cval;
                    $this->cCodeData[$cCode]["main"][$ckey] = $cval;
                }
            }
            if (isset($new_counter["items"]) && sizeof($new_counter["items"]) > 0) {
                foreach ($new_counter["items"] as $ikey => $iSpec) {
                    foreach ($iSpec as $iikey => $iival) {
                        $this->cCodeData[$cCode]["items"][$ikey][$iikey] = $iival;
                    }
                }
            }
            if (isset($new_counter["items2_sum"]) && sizeof($new_counter["items2_sum"]) > 0) {
                foreach ($new_counter["items2_sum"] as $ikey => $iSpec) {
                    foreach ($iSpec as $iikey => $iival) {
                        $this->cCodeData[$cCode]["items2_sum"][$ikey][$iikey] = $iival;
                    }
                }
            }
            //endregion

            //region MENULIS TRANSAKSIONAL
            if (isset($this->cCodeData[$cCode]["tableIn_master"]) && sizeof($this->cCodeData[$cCode]["tableIn_master"]) > 0) {

                $this->cCodeData[$cCode]["tableIn_master"]['status_4'] = 11;
                $this->cCodeData[$cCode]["tableIn_master"]['trash_4'] = 0;
                if ($runCliComponentDetail == false) {
                    $this->cCodeData[$cCode]["tableIn_master"]['cli'] = 1;
                }
                else {
                    $this->cCodeData[$cCode]["tableIn_master"]['cli'] = 0;
                }

                $tr = new MdlTransaksi();
                $tr->addFilter("transaksi.cabang_id='" . $this->cCodeData[$cCode]["tableIn_master"]['cabang_id'] . "'");
                $insertID = $tr->writeMainEntries($this->cCodeData[$cCode]["tableIn_master"]);
                cekHitam($this->db->last_query());
                $epID = $tr->writeMainEntries_entryPoint($insertID, $insertID, $this->cCodeData[$cCode]["tableIn_master"]);
                $insertNum = $this->cCodeData[$cCode]["tableIn_master"]['nomer'];
                $this->cCodeData[$cCode]["main"]['nomer'] = $insertNum;
                if ($insertID < 1) {
                    die("Gagal saat berusaha  write transaction entry pada " . __FILE__ . " baris " . __LINE__);
                }

                //==transaksi_id dan nomor nota diinject kan ke gate utama
                $injectors = array(
                    "transaksi_id" => $insertID,
                    "nomer" => $tmpNomorNota,
                    "nomer2" => isset($tmpNomorNotaAlias) ? $tmpNomorNotaAlias : "",
                );
                $arrInjectorsTarget = array(
                    "items",
                    "items2_sum",
                    "rsltItems",
                );
                foreach ($injectors as $key => $val) {
                    $this->cCodeData[$cCode]["main"][$key] = $val;
                    foreach ($arrInjectorsTarget as $target) {
                        if (isset($this->cCodeData[$cCode][$target])) {
                            foreach ($this->cCodeData[$cCode][$target] as $xid => $iSpec) {
                                $id = isset($iSpec["id"]) && $iSpec["id"] > 0 ? $iSpec["id"] : $xid;
                                if (isset($this->cCodeData[$cCode][$target][$id])) {
                                    $this->cCodeData[$cCode][$target][$id][$key] = $val;
                                }
                            }
                        }
                    }
                }

                //===signature
                $dwsign = $tr->writeSignature($insertID, array(
                    "nomer" => $this->cCodeData[$cCode]["main"]['nomer'],
                    "step_number" => 1,
                    "step_code" => $this->jenisTr,
//                    "step_name" => $this->configUiModul[$this->jenisTr]["steps"][1]["label"],
//                    "group_code" => $this->configUiModul[$this->jenisTr]["steps"][1]['userGroup'],
//                    "oleh_id" => $this->cCodeData[$cCode]["main"]['olehID'],
//                    "oleh_nama" => $this->cCodeData[$cCode]["main"]['olehName'],
                    "step_name" => "",
                    "group_code" => "",
                    "oleh_id" => "",
                    "oleh_nama" => "",
                    "keterangan" => "",
                    "transaksi_id" => $insertID,
                )) or die("Failed to write signature");

                $idHis = array(
                    $stepNumber => array(
                        "olehID" => $this->cCodeData[$cCode]["main"]['olehID'],
                        "olehName" => $this->cCodeData[$cCode]["main"]['olehName'],
                        "step" => $stepNumber,
                        "trID" => $insertID,
                        "nomer" => $tmpNomorNota,
                        "nomer2" => isset($tmpNomorNotaAlias) ? $tmpNomorNotaAlias : "",
                        "counters" => $appliedCounters,
                        // "counters_intext" => $appliedCounters_inText,
                    ),
                );
                $idHis_blob = blobEncode($idHis);
                $idHis_intext = print_r($idHis, true);
                $tr = new MdlTransaksi();
                $dupState = $tr->updateData(array("id" => $insertID), array(
                    "next_step_num" => $nextProp["num"],
                    "next_step_code" => $nextProp["code"],
                    "next_step_label" => $nextProp["label"],
                    "next_group_code" => $nextProp["groupID"],

                    //===references
                    "id_master" => $insertID,
                    "id_top" => $insertID,
                    "ids_prev" => "",
                    "nomer_top" => $this->cCodeData[$cCode]["main"]['nomer'],
                    "nomers_prev" => "",
                    "jenises_prev" => "",
                    "ids_his" => $idHis_blob,

                )) or die("Failed to update tr next-state!");
                cekHijau($this->db->last_query());
                $addValues = array(
                    //===references
                    "id_master" => $insertID,
                    "id_top" => $insertID,
                    "ids_prev" => "",
                    "nomer_top" => $this->cCodeData[$cCode]["main"]['nomer'],
                    "nomers_prev" => "",
                    "jenises_prev" => "",
                    "ids_his" => $idHis_blob,
                );
                foreach ($addValues as $key => $val) {
                    $this->cCodeData[$cCode]["tableIn_master"][$key] = $val;
                }

            }
            if (isset($this->cCodeData[$cCode]['tableIn_master_values']) && sizeof($this->cCodeData[$cCode]['tableIn_master_values']) > 0) {
                $inserMainValues = array();
                if (isset($this->configValuesModul[$this->jenisTr]["tableIn"]['mainValues'])) {
                    $inserMainValues = array();
                    foreach ($this->configValuesModul[$this->jenisTr]["tableIn"]['mainValues'] as $key => $src) {
                        if (isset($this->cCodeData[$cCode]['tableIn_master_values'][$key])) {
                            $dd = $tr->writeMainValues($insertID, array(
                                "key" => $key,
                                "value" => $this->cCodeData[$cCode]['tableIn_master_values'][$key],
                            ));
                            $inserMainValues[] = $dd;
                        }
                    }
                }
                if (sizeof($inserMainValues) > 0) {
                    $arrBlob = blobEncode($inserMainValues);
                    $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                }
            }
            if (isset($this->cCodeData[$cCode]['main_add_values']) && sizeof($this->cCodeData[$cCode]['main_add_values']) > 0) {
                $inserMainValues = array();
                foreach ($this->cCodeData[$cCode]['main_add_values'] as $key => $val) {
                    $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                    $inserMainValues[] = $dd;
                }
                if (sizeof($inserMainValues) > 0) {
                    $arrBlob = blobEncode($inserMainValues);
                    $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                }
            }
            if (isset($this->cCodeData[$cCode]['main_inputs']) && sizeof($this->cCodeData[$cCode]['main_inputs']) > 0) {
                foreach ($this->cCodeData[$cCode]['main_inputs'] as $key => $val) {
                    $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                }
            }
            if (isset($this->cCodeData[$cCode]['main_add_fields']) && sizeof($this->cCodeData[$cCode]['main_add_fields']) > 0) {
                foreach ($this->cCodeData[$cCode]['main_add_fields'] as $key => $val) {
                    $tr->writeMainFields($insertID, array("key" => $key, "value" => $val));
                }
            }
            if (isset($this->cCodeData[$cCode]['main_applets']) && sizeof($this->cCodeData[$cCode]['main_applets']) > 0) {
                foreach ($this->cCodeData[$cCode]['main_applets'] as $amdl => $aSpec) {
                    $tr->writeMainApplets($insertID, array(
                        "mdl_name" => $amdl,
                        "key" => $aSpec['key'],
                        "label" => $aSpec['labelValue'],
                        "description" => $aSpec['description'],
                    ));
                }
            }
            if (isset($this->cCodeData[$cCode]['main_elements']) && sizeof($this->cCodeData[$cCode]['main_elements']) > 0) {
                foreach ($this->cCodeData[$cCode]['main_elements'] as $elName => $aSpec) {
                    $tr->writeMainElements($insertID, array(
                        "mdl_name" => isset($aSpec['mdl_name']) ? $aSpec['mdl_name'] : "",
                        "key" => isset($aSpec['key']) ? $aSpec['key'] : 0,
                        "value" => isset($aSpec["value"]) ? $aSpec["value"] : "",
                        "name" => $aSpec['name'],
                        "label" => $aSpec["label"],
                        "contents" => isset($aSpec['contents']) ? $aSpec['contents'] : "",
                        "contents_intext" => isset($aSpec['contents_intext']) ? $aSpec['contents_intext'] : "",

                    ));
                    //==nebeng bikin inputLabels
                    $currentValue = "";
                    switch ($aSpec['elementType']) {
                        case "dataModel":
                            $currentValue = $aSpec['key'];
                            break;
                        case "dataField":
                            $currentValue = $aSpec["value"];
                            break;
                    }
                    if (array_key_exists($elName, $relOptionConfigs)) {
                        if (isset($relOptionConfigs[$elName][$currentValue])) {
                            if (sizeof($relOptionConfigs[$elName][$currentValue]) > 0) {
                                foreach ($relOptionConfigs[$elName][$currentValue] as $oValueName => $oValSpec) {
                                    $inputLabels[$oValueName] = $oValSpec["label"];
                                    if (isset($oValSpec['auth'])) {
                                        if (isset($oValSpec['auth']["groupID"])) {
                                            $inputAuthConfigs[$oValueName] = $oValSpec['auth']["groupID"];
                                        }
                                    }
                                }
                            }
                        }
                        else {
                            //						cekKuning("option $currentValue pada $eName TIDAK ada pilihannya");
                        }
                    }
                }
            }
            if (isset($this->cCodeData[$cCode]["tableIn_detail"]) && sizeof($this->cCodeData[$cCode]["tableIn_detail"]) > 0) {
                $insertIDs = array();
                $insertDeIDs = array();
                foreach ($this->cCodeData[$cCode]["tableIn_detail"] as $dSpec) {
                    $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                    if ($insertDetailID < 1) {
                        die("Gagal saat berusaha write transaction detail entry pada " . __FILE__ . " baris " . __LINE__);
                    }
                    else {
                        $insertIDs[] = $insertDetailID;
                        $insertDeIDs[$insertID][] = $insertDetailID;
                    }
                    if ($epID != 999) {
                        $insertEpID = $tr->writeDetailEntries($epID, $dSpec);
                        if ($insertEpID < 1) {
                            die("Gagal saat berusaha write transaction detail entry point pada " . __FILE__ . " baris " . __LINE__);
                        }
                        else {
                            $insertIDs[] = $insertEpID;
                            $insertDeIDs[$epID][] = $insertEpID;
                        }
                    }
                    cekUngu($this->db->last_query());
                }
                if (sizeof($insertIDs) == 0) {
                    die(lgShowAlert("Transaksi gagal disimpan karena rincian transaksi kosong."));
                }
                else {
                    $indexing_details = array();
                    foreach ($insertDeIDs as $key => $numb) {
                        $indexing_details[$key] = $numb;
                    }
                    foreach ($indexing_details as $k => $arrID) {
                        $arrBlob = blobEncode($arrID);
                        $this->db->query("UPDATE transaksi SET indexing_details = '$arrBlob' WHERE id=$k");
                        cekOrange($this->db->last_query());
                    }
                }
            }
//            else {
//                die(lgShowAlert("Transaksi gagal disimpan karena rincian transaksi kosong."));
//            }
//
            if (isset($this->cCodeData[$cCode]['tableIn_detail2']) && sizeof($this->cCodeData[$cCode]['tableIn_detail2']) > 0) {
                $insertIDs = array();
                foreach ($this->cCodeData[$cCode]['tableIn_detail2'] as $dSpec) {
                    $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                    if ($epID != 999) {
                        $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);
                    }
                    cekUngu($this->db->last_query());
                }
            }
            if (isset($this->cCodeData[$cCode]['tableIn_detail2_sum']) && sizeof($this->cCodeData[$cCode]['tableIn_detail2_sum']) > 0) {
                $insertIDs = array();
                foreach ($this->cCodeData[$cCode]['tableIn_detail2_sum'] as $dSpec) {
                    $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                    $insertIDs[] = $insertDetailID;
                    if ($epID != 999) {
                        $dd = $tr->writeDetailEntries($epID, $dSpec);
                        $insertIDs[] = $dd;
                        $mongoList['detail'][] = $dd;
                    }
                }
            }
            if (isset($this->cCodeData[$cCode]['tableIn_detail_rsltItems']) && sizeof($this->cCodeData[$cCode]['tableIn_detail_rsltItems']) > 0) {
                $insertIDs = array();
                foreach ($this->cCodeData[$cCode]['tableIn_detail_rsltItems'] as $dSpec) {
                    $dd = $tr->writeDetailEntries($insertID, $dSpec);
                    $insertIDs[] = $dd;
                    if ($epID != 999) {
                        $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);
                    }
                    cekUngu($this->db->last_query());
                }
            }
            if (isset($this->cCodeData[$cCode]['tableIn_detail_values']) && sizeof($this->cCodeData[$cCode]['tableIn_detail_values']) > 0) {
                $insertIDs = array();
                foreach ($this->cCodeData[$cCode]['tableIn_detail_values'] as $pID => $dSpec) {
                    if (isset($this->configValuesModul[$this->jenisTr]["tableIn"]['detailValues'])) {
                        foreach ($this->configValuesModul[$this->jenisTr]["tableIn"]['detailValues'] as $key => $src) {
                            if (isset($this->cCodeData[$cCode]["tableIn_detail"][$pID])) {
                                $dd = $tr->writeDetailValues($insertID, array(
                                    "produk_jenis" => $this->cCodeData[$cCode]["tableIn_detail"][$pID]['produk_jenis'],
                                    "produk_id" => $pID,
                                    "key" => $key,
                                    "value" => isset($dSpec[$src]) ? $dSpec[$src] : "0",
                                ));
                                $insertIDs[$pID][] = $dd;
                            }
                        }
                    }
                }
                if (sizeof($insertIDs) > 0) {
                    $arrBlob = blobEncode($insertIDs);
                    $this->db->query("UPDATE transaksi SET indexing_detail_values = '$arrBlob' WHERE id=$insertID");
                }
            }
            if (isset($this->cCodeData[$cCode]['tableIn_detail_values2_sum']) && sizeof($this->cCodeData[$cCode]['tableIn_detail_values2_sum']) > 0) {
                foreach ($this->cCodeData[$cCode]['tableIn_detail_values2_sum'] as $pID => $dSpec) {
                    if (isset($this->configValuesModul[$this->jenisTr]["tableIn"]['detailValues2_sum'])) {
                        $insertIDs = array();
                        foreach ($this->configValuesModul[$this->jenisTr]["tableIn"]['detailValues2_sum'] as $key => $src) {
                            $dd = $tr->writeDetailValues($insertID, array(
                                "produk_jenis" => $this->cCodeData[$cCode]['tableIn_detail2_sum'][$pID]['produk_jenis'],
                                "produk_id" => $pID,
                                "key" => $key,
                                "value" => $dSpec[$src],
                            ));
                            $insertIDs[] = $dd;
                        }
                    }
                }
            }
//        $steps = $this->configUiModul[$this->jenisTr]["steps"];

            //endregion
        }
        else {
            $insertID = "1111111111111";
        }

        //region MENULIS KE REGISTRY
        $pakai_ini = 1;
        if ($pakai_ini == 1) {
            $baseRegistries = array(
                "main" => isset($this->cCodeData[$cCode]["main"]) ? $this->cCodeData[$cCode]["main"] : array(),
                "items" => isset($this->cCodeData[$cCode]["items"]) ? $this->cCodeData[$cCode]["items"] : array(),
                "items2" => isset($this->cCodeData[$cCode]["items2"]) ? $this->cCodeData[$cCode]["items2"] : array(),
                "items2_sum" => isset($this->cCodeData[$cCode]["items2_sum"]) ? $this->cCodeData[$cCode]["items2_sum"] : array(),
                "itemSrc" => isset($this->cCodeData[$cCode]["itemSrc"]) ? $this->cCodeData[$cCode]["itemSrc"] : array(),
                "itemSrc_sum" => isset($this->cCodeData[$cCode]["itemSrc_sum"]) ? $this->cCodeData[$cCode]["itemSrc_sum"] : array(),
                "items3" => isset($this->cCodeData[$cCode]["items3"]) ? $this->cCodeData[$cCode]["items3"] : array(),
                "items3_sum" => isset($this->cCodeData[$cCode]["items3_sum"]) ? $this->cCodeData[$cCode]["items3_sum"] : array(),
                "items4" => isset($this->cCodeData[$cCode]["items4"]) ? $this->cCodeData[$cCode]["items4"] : array(),
                "items4_sum" => isset($this->cCodeData[$cCode]["items4_sum"]) ? $this->cCodeData[$cCode]["items4_sum"] : array(),
                "items5_sum" => isset($this->cCodeData[$cCode]["items5_sum"]) ? $this->cCodeData[$cCode]["items5_sum"] : array(),
                'items6_sum' => isset($this->cCodeData[$cCode]['items6_sum']) ? $this->cCodeData[$cCode]['items6_sum'] : array(),
                'items7_sum' => isset($this->cCodeData[$cCode]['items7_sum']) ? $this->cCodeData[$cCode]['items7_sum'] : array(),
                'items8_sum' => isset($this->cCodeData[$cCode]['items8_sum']) ? $this->cCodeData[$cCode]['items8_sum'] : array(),
                'items9_sum' => isset($this->cCodeData[$cCode]['items9_sum']) ? $this->cCodeData[$cCode]['items9_sum'] : array(),
                'items10_sum' => isset($this->cCodeData[$cCode]['items10_sum']) ? $this->cCodeData[$cCode]['items10_sum'] : array(),
                'rsltItems' => isset($this->cCodeData[$cCode]['rsltItems']) ? $this->cCodeData[$cCode]['rsltItems'] : array(),
                'rsltItems2' => isset($this->cCodeData[$cCode]['rsltItems2']) ? $this->cCodeData[$cCode]['rsltItems2'] : array(),
                'rsltItems3' => isset($this->cCodeData[$cCode]['rsltItems3']) ? $this->cCodeData[$cCode]['rsltItems3'] : array(),
                "tableIn_master" => isset($this->cCodeData[$cCode]["tableIn_master"]) ? $this->cCodeData[$cCode]["tableIn_master"] : array(),
                "tableIn_detail" => isset($this->cCodeData[$cCode]["tableIn_detail"]) ? $this->cCodeData[$cCode]["tableIn_detail"] : array(),
                'tableIn_detail2_sum' => isset($this->cCodeData[$cCode]['tableIn_detail2_sum']) ? $this->cCodeData[$cCode]['tableIn_detail2_sum'] : array(),
                'tableIn_detail_rsltItems' => isset($this->cCodeData[$cCode]['tableIn_detail_rsltItems']) ? $this->cCodeData[$cCode]['tableIn_detail_rsltItems'] : array(),
                'tableIn_detail_rsltItems2' => isset($this->cCodeData[$cCode]['tableIn_detail_rsltItems2']) ? $this->cCodeData[$cCode]['tableIn_detail_rsltItems2'] : array(),
                'tableIn_master_values' => isset($this->cCodeData[$cCode]['tableIn_master_values']) ? $this->cCodeData[$cCode]['tableIn_master_values'] : array(),
                'tableIn_detail_values' => isset($this->cCodeData[$cCode]['tableIn_detail_values']) ? $this->cCodeData[$cCode]['tableIn_detail_values'] : array(),
                'tableIn_detail_values_rsltItems' => isset($this->cCodeData[$cCode]['tableIn_detail_values_rsltItems']) ? $this->cCodeData[$cCode]['tableIn_detail_values_rsltItems'] : array(),
                'tableIn_detail_values_rsltItems2' => isset($this->cCodeData[$cCode]['tableIn_detail_values_rsltItems2']) ? $this->cCodeData[$cCode]['tableIn_detail_values_rsltItems2'] : array(),
                'tableIn_detail_values2_sum' => isset($this->cCodeData[$cCode]['tableIn_detail_values2_sum']) ? $this->cCodeData[$cCode]['tableIn_detail_values2_sum'] : array(),
                'main_add_values' => isset($this->cCodeData[$cCode]['main_add_values']) ? $this->cCodeData[$cCode]['main_add_values'] : array(),
                'main_add_fields' => isset($this->cCodeData[$cCode]['main_add_fields']) ? $this->cCodeData[$cCode]['main_add_fields'] : array(),
                'main_elements' => isset($this->cCodeData[$cCode]['main_elements']) ? $this->cCodeData[$cCode]['main_elements'] : array(),
//                'items_elements' => isset($this->cCodeData[$cCode]['items_elements']) ? $this->cCodeData[$cCode]['items_elements'] : array(),
                'main_inputs' => isset($this->cCodeData[$cCode]['main_inputs']) ? $this->cCodeData[$cCode]['main_inputs'] : array(),
                'main_inputs_orig' => isset($this->cCodeData[$cCode]['main_inputs']) ? $this->cCodeData[$cCode]['main_inputs'] : array(),
                "receiptDetailFields" => isset($this->configLayoutModul[$this->jenisTr]['receiptDetailFields'][1]) ? $this->configLayoutModul[$this->jenisTr]['receiptDetailFields'][1] : array(),
                "receiptSumFields" => isset($this->configLayoutModul[$this->jenisTr]['receiptSumFields'][1]) ? $this->configLayoutModul[$this->jenisTr]['receiptSumFields'][1] : array(),
                "receiptDetailFields2" => isset($this->configLayoutModul[$this->jenisTr]['receiptDetailFields2'][1]) ? $this->configLayoutModul[$this->jenisTr]['receiptDetailFields2'][1] : array(),
                "receiptDetailSrcFields" => isset($this->configLayoutModul[$this->jenisTr]['receiptDetailSrcFields'][1]) ? $this->configLayoutModul[$this->jenisTr]['receiptDetailSrcFields'][1] : array(),
                "receiptSumFields2" => isset($this->configLayoutModul[$this->jenisTr]['receiptSumFields2'][1]) ? $this->configLayoutModul[$this->jenisTr]['receiptSumFields2'][1] : array(),
                "jurnal_index" => $jurnalIndex,
                "postProcessor" => $jurnalPostProc,
                "preProcessor" => $jurnalPreProc,
                "revert" => isset($this->cCodeData[$cCode]['revert']) ? $this->cCodeData[$cCode]['revert'] : array(),
                "items_komposisi" => isset($this->cCodeData[$cCode]['items_komposisi']) ? $this->cCodeData[$cCode]['items_komposisi'] : array(),
                "items_noapprove" => isset($this->cCodeData[$cCode]['items_noapprove']) ? $this->cCodeData[$cCode]['items_noapprove'] : array(),
                "jurnalItems" => isset($this->cCodeData[$cCode]['jurnalItems']) ? $this->cCodeData[$cCode]['jurnalItems'] : array(),
                "componentsBuilder" => isset($this->cCodeData[$cCode]['componentsBuilder']) ? $this->cCodeData[$cCode]['componentsBuilder'] : array(),
//                "itemPrice" => isset($this->cCodeData[$cCode]['itemPrice']) ? $this->cCodeData[$cCode]['itemPrice'] : array(),
//                "itemPrice_sum" => isset($this->cCodeData[$cCode]['itemPrice_sum']) ? $this->cCodeData[$cCode]['itemPrice_sum'] : array(),
//                "requiredParam" => (isset($coreRequiredParam[$this->jenisTr]) && sizeof($coreRequiredParam[$this->jenisTr]) > 0) ? $coreRequiredParam[$this->jenisTr] : array(),
                //-----
//                "coreBuilder" => $coreBuilder,
//                'diskon_event' => isset($this->cCodeData[$cCode]['diskon_event']) ? $this->cCodeData[$cCode]['diskon_event'] : array(),
//                'cashback_event' => isset($this->cCodeData[$cCode]['cashback_event']) ? $this->cCodeData[$cCode]['cashback_event'] : array(),
                //-----
            );
            $doWriteReg = $tr->writeDataRegistries($insertID, $baseRegistries) or mati_disini(("Ada kesalahan, Gagal saat berusaha  write base params into registries"));
            showLast_query("biru");
        }
        //endregion

//        mati_disini(__LINE__);

        // COMPONENT
        $pakai_ini = 1;
        if ($pakai_ini == 1) {

            //region processing sub-components, if in single step geser ke CLI
            $componentGate['detail'] = array();
            $componentConfig['detail'] = array();
            $iterator = $components["detail"];
            if (sizeof($iterator) > 0) {
                foreach ($iterator as $cCtr => $tComSpec) {
                    $tmpOutParams[$cCtr] = array();
                    $gg = 0;
                    $srcGateName = $tComSpec['srcGateName'];
                    if ($componentsDetailLoop == true) {
                        foreach ($this->cCodeData[$cCode][$srcGateName] as $id => $dSpec) {
                            $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $this->cCodeData[$cCode][$srcGateName][$id][$comName], $comName);
                            }

                            $mdlName = "$comsPrefix" . ucfirst($comName);
                            if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                $filterNeeded = true;
                            }
                            else {
                                $filterNeeded = false;
                            }
                            cekHere("sub-component: [$comsLocation] $comName, initializing values <br>");

                            $subParams = array();

                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {
                                    if (substr($key, 0, 1) == "{") {
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        $key = str_replace($key, $this->cCodeData[$cCode][$srcGateName][$id][$key], $key);
                                    }

                                    $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$id], $this->cCodeData[$cCode][$srcGateName][$id], 0);
                                    $subParams['loop'][$key] = $realValue;

                                    if ($filterNeeded) {
                                        if ($subParams['loop'][$key] == 0) {
                                            unset($subParams['loop'][$key]);
                                        }
                                    }
                                }
                            }
                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {
                                    $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$id], $this->cCodeData[$cCode][$srcGateName][$id], 0);
                                    $subParams['static'][$key] = $realValue;
                                }
                                if (!isset($subParams['static']["transaksi_id"])) {
                                    $subParams['static']["transaksi_id"] = $insertID;
                                }
                                if (!isset($subParams['static']["transaksi_no"])) {
                                    $subParams['static']["transaksi_no"] = $insertNum;
                                }

                                $subParams['static']["fulldate"] = date("Y-m-d");
                                $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                $subParams['static']["keterangan"] = isset($this->cCodeData[$cCode][$srcGateName][$id]["keterangan"]) ? $this->cCodeData[$cCode][$srcGateName][$id]["keterangan"] : "";
                                if (isset($revertedTarget) && (strlen($revertedTarget) > 1)) {
                                    $subParams['static']['reverted_target'] = $revertedTarget;
                                }
                            }

                            if (sizeof($subParams) > 0) {
//                                cekhitam("subparam ada isinya");
                                if ($filterNeeded) {
                                    if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                    }
                                }
                                else {
                                    $tmpOutParams[$cCtr][] = $subParams;
                                }
                            }
                            else {
                                cekhitam("subparam TIDAK ada isinya");
                            }
                        }
                    }
                    else {
                        foreach ($this->cCodeData[$cCode][$srcGateName] as $id => $dSpec) {
                            if ($cCtr == $id) {
                                $srcRawGateName = $tComSpec['srcRawGateName'];
                                $comName = $tComSpec['comName'];
                                if (substr($comName, 0, 1) == "{") {
                                    $comName = trim($comName, "{");
                                    $comName = trim($comName, "}");

                                    $comName = str_replace($comName, $this->cCodeData[$cCode][$srcGateName][$id][$comName], $comName);
                                }

                                $mdlName = "$comsPrefix" . ucfirst($comName);
                                if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                    $filterNeeded = true;
                                }
                                else {
                                    $filterNeeded = false;
                                }
                                cekHere("sub-component: [$comsLocation] $comName, initializing values <br>");

                                $subParams = array();

                                if (isset($tComSpec['loop'])) {
                                    foreach ($tComSpec['loop'] as $key => $value) {

                                        if (substr($key, 0, 1) == "{") {
                                            $key = trim($key, "{");
                                            $key = trim($key, "}");

                                            $key = str_replace($key, $this->cCodeData[$cCode][$srcGateName][$id][$key], $key);
                                        }

                                        $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$id], $this->cCodeData[$cCode][$srcGateName][$id], 0);
                                        $subParams['loop'][$key] = $realValue;

                                        if ($filterNeeded) {
                                            if ($subParams['loop'][$key] == 0) {
                                                unset($subParams['loop'][$key]);
                                            }
                                        }
                                    }
                                }
                                if (isset($tComSpec['static'])) {
                                    foreach ($tComSpec['static'] as $key => $value) {
                                        $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$id], $this->cCodeData[$cCode][$srcGateName][$id], 0);
                                        $subParams['static'][$key] = $realValue;

                                    }
                                    if (!isset($subParams['static']["transaksi_id"])) {
                                        $subParams['static']["transaksi_id"] = $insertID;
                                    }
                                    if (!isset($subParams['static']["transaksi_no"])) {
                                        $subParams['static']["transaksi_no"] = $insertNum;
                                    }

                                    $subParams['static']["fulldate"] = date("Y-m-d");
                                    $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                    $subParams['static']["keterangan"] = "";
                                    if (isset($revertedTarget) && (strlen($revertedTarget) > 1)) {
                                        $subParams['static']['reverted_target'] = $revertedTarget;
                                    }
                                }

                                if (sizeof($subParams) > 0) {

                                    if ($filterNeeded) {
                                        if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                            $tmpOutParams[$cCtr][] = $subParams;
                                        }
                                    }
                                    else {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                    }
                                }
                                else {
                                    cekhitam("subparam TIDAK ada isinya");
                                }
                            }
                        }
                    }

                    $componentGate['detail'][$cCtr] = $subParams;
                }

                foreach ($iterator as $cCtr => $tComSpec) {
                    $srcGateName = $tComSpec['srcGateName'];
                    foreach ($this->cCodeData[$cCode][$srcGateName] as $id => $dSpec) {
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        $comName = $tComSpec['comName'];
                        if (substr($comName, 0, 1) == "{") {
                            $comName = trim($comName, "{");
                            $comName = trim($comName, "}");
                            $comName = str_replace($comName, $this->cCodeData[$cCode][$srcGateName][$id][$comName], $comName);
                        }
                    }
                    cekHere("sub component: [$comsLocation] $comName, sending values " . __LINE__ . "<br>");

                    $mdlName = "$comsPrefix" . ucfirst($comName);
                    $this->load->model("$comsLocation/" . $mdlName);
                    $m = new $mdlName();
                    //===filter value nol, jika harus difilter

                    if (sizeof($tmpOutParams[$cCtr]) > 0) {
                        $tobeExecuted = true;
                    }
                    else {
                        $tobeExecuted = false;
                    }

                    // matiHEre($tobeExecuted);
                    if ($tobeExecuted) {
                        //----- kiriman gerbang
                        if (method_exists($m, "setTableInMaster")) {
                            $m->setTableInMaster($this->cCodeData[$cCode]["tableIn_master"]);
                        }
                        if (method_exists($m, "setDetail")) {
                            $m->setDetail($this->cCodeData[$cCode][$srcGateName]);
                        }
                        if (method_exists($m, "setJenisTr")) {
                            $m->setJenisTr($this->jenisTr);
                        }
                        //----- kiriman gerbang
                        $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        cekBiru($this->db->last_query());
                    }
                    else {
                        cekMerah("$comName tidak eksekusi");
                    }

                }
            }
            else {
                cekKuning("subcomponents is not set");
            }
            //endregion

            //region processing main components, if in single step
            $componentGate['master'] = array();
            $componentConfig['master'] = array();
            $iterator = $components["master"];
            if (sizeof($iterator) > 0) {
                $componentConfig['master'] = $iterator;
                $cCtr = 0;
                foreach ($iterator as $cCtr => $tComSpec) {
                    $cCtr++;
                    $comName = $tComSpec['comName'];
                    if (substr($comName, 0, 1) == "{") {
                        $comName = trim($comName, "{");
                        $comName = trim($comName, "}");
                        $comName = str_replace($comName, $this->cCodeData[$cCode]["main"][$comName], $comName);
                    }
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    cekHere("component # $cCtr: $comName<br>");


                    // arrPrint($this->cCodeData[$cCode][$srcGateName]);
                    // matiHEre(__LINE__);
                    $dSpec = $this->cCodeData[$cCode][$srcGateName];
                    $tmpOutParams = array();
                    if (isset($tComSpec['loop'])) {
                        foreach ($tComSpec['loop'] as $key => $value) {
                            if (substr($key, 0, 1) == "{") {
                                $key = trim($key, "{");
                                $key = trim($key, "}");
                                $key = str_replace($key, $this->cCodeData[$cCode]["main"][$key], $key);
                            }
                            $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName], $this->cCodeData[$cCode][$srcGateName], 0);
                            $tmpOutParams['loop'][$key] = $realValue;
                        }
                    }
                    if (isset($tComSpec['static'])) {
                        foreach ($tComSpec['static'] as $key => $value) {
                            $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName], $this->cCodeData[$cCode][$srcGateName], 0);
                            $tmpOutParams['static'][$key] = $realValue;
                        }
                        if (!isset($tmpOutParams['static']["transaksi_id"])) {
                            $tmpOutParams['static']["transaksi_id"] = $insertID;
                        }
                        if (!isset($tmpOutParams['static']["transaksi_no"])) {
                            $tmpOutParams['static']["transaksi_no"] = $insertNum;
                        }
                        $tmpOutParams['static']["urut"] = $cCtr;
                        $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static']["keterangan"] = isset($this->cCodeData[$cCode][$srcGateName]["keterangan"]) ? $this->cCodeData[$cCode][$srcGateName]["keterangan"] : "";
                    }
                    if (isset($tComSpec['static2'])) {
                        foreach ($tComSpec['static2'] as $key => $value) {
                            $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$cCtr], $this->cCodeData[$cCode][$srcGateName][$cCtr], 0);
                            $tmpOutParams['static2'][$key] = $realValue;
                        }
                        if (!isset($tmpOutParams['static2']["transaksi_id"])) {
                            $tmpOutParams['static2']["transaksi_id"] = $insertID;
                        }
                        if (!isset($tmpOutParams['static2']["transaksi_no"])) {
                            $tmpOutParams['static2']["transaksi_no"] = $insertNum;
                        }
                        $tmpOutParams['static2']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static2']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static2']["keterangan"] = $this->configUiModul[$this->jenisTr]["steps"][$stepNum]["label"] . " nomor " . $tmpNomorNota . " oleh " . $this->cCodeData[$cCode]["tableIn_master"]['oleh_nama'];
                    }

                    $mdlName = "Com" . ucfirst($comName);
                    $this->load->model("Coms/" . $mdlName);
                    $m = new $mdlName();

                    //===filter value nol, jika harus difilter
                    $tobeExecuted = true;
                    if (in_array($mdlName, $compValidators)) {
                        $loopParams = isset($tmpOutParams['loop']) ? $tmpOutParams['loop'] : array();
                        if (sizeof($loopParams) > 0) {
                            foreach ($loopParams as $key => $val) {
                                cekmerah("$comName : $key = $val ");
                                if ($val == 0) {
                                    unset($tmpOutParams['loop'][$key]);
                                }
                            }
                        }
                        if (sizeof($tmpOutParams['loop']) < 1) {
                            $tobeExecuted = false;
                        }
                    }
                    if ($tobeExecuted) {
                        //----- kiriman gerbang untuk counter mutasi rekening
                        if (method_exists($m, "setTableInMaster")) {
                            $m->setTableInMaster($this->cCodeData[$cCode]["tableIn_master"]);
                        }
                        if (method_exists($m, "setMain")) {
                            $m->setMain($this->cCodeData[$cCode]["main"]);
                        }
                        if (method_exists($m, "setJenisTr")) {
                            $m->setJenisTr($this->jenisTr);
                        }
                        //----- kiriman gerbang untuk counter mutasi rekening
                        $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    }
                    $componentGate['master'][$cCtr] = $tmpOutParams;
                }
            }
            else {
                cekKuning("components is not set");
            }
            //endregion
        }

        // POST-PROCC
        $pakai_ini = 1;
        if ($pakai_ini == 1) {

            //region processing sub-post-processors, always
            $iterator = $postProcessor["detail"];
            if (sizeof($iterator) > 0) {
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    cekHere("[$cCtr] sub-postProcessor: $comName, gate: $srcGateName, initializing values <br>");
                    $tmpOutParams[$cCtr] = array();
                    if (isset($this->cCodeData[$cCode][$srcGateName]) && (sizeof($this->cCodeData[$cCode][$srcGateName]) > 0)) {
                        foreach ($this->cCodeData[$cCode][$srcGateName] as $xid => $dSpec) {
                            $id = $xid;
                            $subParams = array();
                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {
                                    $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$id], $this->cCodeData[$cCode][$srcGateName][$id], 0);
                                    $subParams['loop'][$key] = $realValue;
                                }
                            }
                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {
                                    $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$id], $this->cCodeData[$cCode][$srcGateName][$id], 0);
                                    $subParams['static'][$key] = $realValue;
                                }
                                if (!isset($subParams['static']["transaksi_id"])) {
                                    $subParams['static']["transaksi_id"] = $insertID;
                                }
                                if (!isset($subParams['static']["transaksi_no"])) {
                                    $subParams['static']["transaksi_no"] = $insertNum;
                                }
                                $subParams['static']["fulldate"] = date("Y-m-d");
                                $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                if (isset($this->cCodeData[$cCode]['revert']['postProc']['detail'])) {
                                    $subParams['static']["reverted_target"] = $this->cCodeData[$cCode]["main"]['pihakExternID'];
                                }
                                $subParams['static']["keterangan"] = "";
                            }
                            if (sizeof($subParams) > 0) {
                                $tmpOutParams[$cCtr][] = $subParams;
                            }
                        }
                    }
                }
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    if (isset($this->cCodeData[$cCode][$srcGateName])) {
                        cekHere("[$cCtr] sub-postProcessor: $comName, sending values " . __LINE__ . "<br>");
                        $mdlName = "Com" . ucfirst($comName);
                        $this->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();
                        $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        cekHitam($this->db->last_query());
                    }
                }
            }
            else {
                cekHitam("TIDAK ADA SETUP SUB-POSTPROC");
            }
            //endregion

            //region processing main-post-processors, always
            $iterator = $postProcessor["master"];
            if (sizeof($iterator) > 0) {
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    cekHere("post-processor: $comName<br>LINE: " . __LINE__);

                    $dSpec = $this->cCodeData[$cCode][$srcGateName];
                    $tmpOutParams = array();
                    if (isset($tComSpec['loop'])) {
                        foreach ($tComSpec['loop'] as $key => $value) {
                            $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName], $this->cCodeData[$cCode][$srcGateName], 0);
                            $tmpOutParams['loop'][$key] = $realValue;
                        }
                    }
                    if (isset($tComSpec['static'])) {
                        foreach ($tComSpec['static'] as $key => $value) {
                            $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName], $this->cCodeData[$cCode][$srcGateName], 0);
                            $tmpOutParams['static'][$key] = $realValue;
                        }
                        if (!isset($tmpOutParams['static']["transaksi_id"])) {
                            $tmpOutParams['static']["transaksi_id"] = $insertID;
                        }
                        if (!isset($tmpOutParams['static']["transaksi_no"])) {
                            $tmpOutParams['static']["transaksi_no"] = $insertNum;
                        }
                        $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static']["keterangan"] = "";
                    }
                    if (isset($tComSpec['static2'])) {
                        foreach ($tComSpec['static2'] as $key => $value) {
                            $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$cCtr], $this->cCodeData[$cCode][$srcGateName][$cCtr], 0);
                            $tmpOutParams['static2'][$key] = $realValue;
                        }
                        if (!isset($tmpOutParams['static2']["transaksi_id"])) {
                            $tmpOutParams['static2']["transaksi_id"] = $insertID;
                        }
                        if (!isset($tmpOutParams['static2']["transaksi_no"])) {
                            $tmpOutParams['static2']["transaksi_no"] = $insertNum;
                        }

                        $tmpOutParams['static2']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static2']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static2']["keterangan"] = "";
                    }

                    //lgShowError("Ada kesalahan",);
                    $mdlName = "Com" . ucfirst($comName);
                    $this->load->model("Coms/" . $mdlName);
                    $m = new $mdlName();

                    cekBiru("kiriman komponem $comName");
                    $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                }
            }
            else {
                cekHitam("TIDAK ADA SETUP MAIN-POSTPROC");
            }
            //endregion
        }

        validateAllBalances($cabangID);


//        mati_disini(__LINE__ . " BERHASIL SETOP...");

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3>SELESAI...</h3>");


    }

    public function generateSerialIntransit()
    {

        $this->load->model("MdlTransaksi");
        $this->load->model("Coms/ComRekeningPembantuProdukPerSerialIntransit");

        $trIDs = array(
            101475
        );
        $arrTrDatas = array();
        $arrRegDatas = array();

        $tr = New MdlTransaksi();
        $tr->addFilter("id in ('".implode("", $trIDs)."')");
        $trTmp = $tr->lookupAll()->result();
        if(sizeof($trTmp)>0){
            foreach ($trTmp as $trSpec){
                $arrTrDatas[$trSpec->id] = $trSpec;
            }

            $tr = New MdlTransaksi();
            $tr->setFilters(array());
            $tr->addFilter("transaksi_id in ('".implode("", $trIDs)."')");
            $trReg = $tr->lookupDataRegistries()->result();
            foreach ($trReg as $regSpec){
                foreach ($regSpec as $key => $val){
                    if($key != "transaksi_id"){
                        if($val == NULL){
                            $val = blobEncode(array());
                        }
                        $arrRegDatas[$regSpec->transaksi_id][$key] = blobDecode($val);
                    }
                }
            }
//            arrPrintKuning($arrRegDatas);
        }

        $postProcessor = array(
            "master" => array(),
            "detail" => array(
                array(
                    "comName" => "RekeningPembantuProdukPerSerial",
                    "loop" => array(
                        "1010030030" => ".-1",//persediaan produk, sub_diskon_nilai_total
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "extern_id" => ".0",
                        "extern_nama" => "produk_serial",
                        "extern2_id" => ".0",
                        "extern2_nama" => "produk_sku_part_nama",
                        "produk_id" => "id",
                        "produk_nama" => "name",
                        "produk_qty" => "-jml",
                        "produk_nilai" => ".1",
//                        "transaksi_id" => "masterID",
                    ),
                    "srcGateName" => "items3_sum",
                    "srcRawGateName" => "items3_sum",
                ),
                array(
                    "comName" => "RekeningPembantuProdukPerSerialIntransit",
                    "loop" => array(
                        "1010030030" => ".1",//persediaan produk, sub_diskon_nilai_total
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "extern_id" => ".0",
                        "extern_nama" => "produk_serial",
                        "extern2_id" => ".0",
                        "extern2_nama" => "produk_sku_part_nama",
                        "produk_id" => "id",
                        "produk_nama" => "name",
                        "produk_qty" => "jml",
                        "produk_nilai" => ".1",
                        "transaksi_id" => "masterID",
                    ),
                    "srcGateName" => "items3_sum",
                    "srcRawGateName" => "items3_sum",
                ),
            ),

        );

        $this->db->trans_start();

        if(sizeof($arrTrDatas)>0){
            foreach ($arrTrDatas as $trid => $trSpec){
                $insertID = $trSpec->id;
                $insertNum = $trSpec->nomer;
                $jenisTr_master = $trSpec->jenis_master;
                $fulldate = $trSpec->fulldate;
                $dtime = $trSpec->dtime;
                $cCode = "_TR_" . $jenisTr_master;
                $arrDatas = $arrRegDatas[$trid];
                $this->cCodeData[$cCode] = $arrDatas;

                //region processing sub-post-processors, always
                $iterator = $postProcessor["detail"];
                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        cekHere("[$cCtr] sub-postProcessor: $comName, gate: $srcGateName, initializing values <br>");
                        $tmpOutParams[$cCtr] = array();
                        if (isset($this->cCodeData[$cCode][$srcGateName]) && (sizeof($this->cCodeData[$cCode][$srcGateName]) > 0)) {
                            foreach ($this->cCodeData[$cCode][$srcGateName] as $xid => $dSpec) {
                                $id = $xid;
                                $subParams = array();
                                if (isset($tComSpec['loop'])) {
                                    foreach ($tComSpec['loop'] as $key => $value) {
                                        $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$id], $this->cCodeData[$cCode][$srcGateName][$id], 0);
                                        $subParams['loop'][$key] = $realValue;
                                    }
                                }
                                if (isset($tComSpec['static'])) {
                                    foreach ($tComSpec['static'] as $key => $value) {
                                        $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$id], $this->cCodeData[$cCode][$srcGateName][$id], 0);
                                        $subParams['static'][$key] = $realValue;
                                    }
                                    if (!isset($subParams['static']["transaksi_id"])) {
                                        $subParams['static']["transaksi_id"] = $insertID;
                                    }
                                    if (!isset($subParams['static']["transaksi_no"])) {
                                        $subParams['static']["transaksi_no"] = $insertNum;
                                    }
                                    $subParams['static']["fulldate"] = $fulldate;
                                    $subParams['static']["dtime"] = $dtime;
                                    if (isset($this->cCodeData[$cCode]['revert']['postProc']['detail'])) {
                                        $subParams['static']["reverted_target"] = $this->cCodeData[$cCode]["main"]['pihakExternID'];
                                    }
                                    $subParams['static']["keterangan"] = "";
                                }
                                if (sizeof($subParams) > 0) {
                                    $tmpOutParams[$cCtr][] = $subParams;
                                }
                            }
                        }
                    }
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        if (isset($this->cCodeData[$cCode][$srcGateName])) {
                            cekHere("[$cCtr] sub-postProcessor: $comName, sending values " . __LINE__ . "<br>");
                            $mdlName = "Com" . ucfirst($comName);
                            $this->load->model("Coms/" . $mdlName);
                            $m = new $mdlName();
                            $m->pair($tmpOutParams[$cCtr]) or mati_disini("Tidak berhasil memasang  values pada post-processor: ");
                            $m->exec() or mati_disini("Gagal saat berusaha  exec values pada post-processor: ");
                            cekHitam($this->db->last_query());
                        }
                    }
                }
                else {
                    cekHitam("TIDAK ADA SETUP SUB-POSTPROC");
                }
                //endregion


            }
        }



        mati_disini("---SETOP--- " . __LINE__);

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3>DONE...</h3>");
    }
}

?>