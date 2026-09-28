<?php

/**
 * Created by PhpStorm.
 * User: none
 * Date: 5/6/2023
 * Time: 8:39 PM
 */
class Locker extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        // if (!isset($this->session->login['id'])) {
        //     gotoLogin();
        // }
        // $this->db2 = $this->load->database('testing', TRUE);
    }

    public function cleansingProduk()
    {
        $tbl_2 = "_rek_pembantu_produk_cache";

        // $this->db->where("trash", "0");
        $produkData = $this->db->get($tbl_2)->result_array();
        showLast_query("hijau", count($produkData));
        foreach ($produkData as $produkDatum) {
            $sxtern_id = $produkDatum['extern_id'];

            $ygsudahdipakai[$sxtern_id] = $sxtern_id;
        }
        cekHijau(count($ygsudahdipakai));
//         matiHere(__LINE__);

        $tbl_1 = "produk";

        $this->db->where("trash", "0");
        $produkData = $this->db->get($tbl_1)->result_array();
        showLast_query("hijau", count($produkData));

        foreach ($produkData as $item) {
            $id = $item['id'];
            $nama = $item['nama'];


            $produkDouble[$nama][] = array(
                'id' => $item['id'],
                'kode' => $item['kode'],
                'barcode' => $item['barcode'],
                'kategori' => $item['kategori_nama'],
            );
            $produkId[$id] = $item;
        }

        // cekHere(count($produkDouble));
        cekHere(count($produkId));

        $cocoks = (array_intersect_key($ygsudahdipakai, $produkId));

        arrPrintKuning(count($cocoks));
        // arrPrintKuning($produkDouble);
        matiHere();
        foreach ($produkDouble as $namaItem => $dataItem) {
            if (count($dataItem) > 1) {
                $ygDouble[$namaItem] = $dataItem;
            }
        }

        $this->db->trans_start();
        cekHijau(count($ygDouble));
        arrPrintHijau($ygDouble);
        foreach ($ygDouble as $namaitem2 => $dataItem2) {
            foreach ($dataItem2 as $ix => $item3) {
                if ($ix == 1) {
                    // if($item3['kode'] == ''){
                    //
                    //     $arrSet = array(
                    //         "trash" => 1,
                    //     );
                    //     $conditeUpd = array(
                    //         "id" => $item3['id'],
                    //         // "kode" => '',  ?
                    //     );
                    //     $this->db->set($arrSet);
                    //     $this->db->where($conditeUpd);
                    //     $var = $this->db->update($tbl_1);
                    //     showLast_query("orange");
                    // }

                    if (!array_key_exists($item3['id'], $ygsudahdpakai)) {

                        $arrSet = array(
                            "trash" => 1,
                        );
                        $conditeUpd = array(
                            "id" => $item3['id'],
                            // "kode" => '',  ?
                        );
                        $this->db->set($arrSet);
                        $this->db->where($conditeUpd);
                        $var = $this->db->update($tbl_1);
                        showLast_query("orange");

                    }
                }
            }
        }
        mati_disini(__LINE__);
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");


    }

    // locker produk
    public function cleansingLocker()
    {
        $tbl_1 = "stock_locker";

        $condites = array(
            "state" => "active",
            "jenis" => "produk",
        );
        $this->db->where($condites);
        $produkData = $this->db->get($tbl_1)->result_array();
        showLast_query("hijau", count($produkData));

        foreach ($produkData as $item) {
            $id = $item['id'];
            $nama = $item['nama'];
// arrPrintHijau($item);

            $produkDouble[$item['produk_id']][] = array(
                'id' => $item['id'],
                'jumlah' => $item['jumlah'],
                'produk_id' => $item['produk_id'],
                // 'barcode' => $item['barcode'],
                // 'kategori' => $item['kategori_nama'],
            );
            $produkId[$id] = $item;

            // break;
        }

        // arrPrintKuning($produkDouble);

        foreach ($produkDouble as $namaItem => $dataItem) {
            if (count($dataItem) > 1) {
                $ygDouble[$namaItem] = $dataItem;
            }
        }

        // arrPrintWebs($ygDouble);

        // $jml = array();
        foreach ($ygDouble as $produk_id => $item_1) {
            foreach ($item_1 as $item) {

                if (!isset($jml[$produk_id]['jumlah'])) {
                    $jml[$produk_id]['jumlah'] = 0;
                }
                $jml[$produk_id]['jumlah'] += $item['jumlah'];
                $jml[$produk_id]['id'] = $item['id'];
                $jml[$produk_id]['produk_id'] = $produk_id;
            }
        }

        // arrPrintPink($jml);

        $this->db->trans_start();
        foreach ($jml as $pro_id => $item_2) {

            arrPrintWebs($item_2);

            $jml_new = $item_2['jumlah'];
            $jml_id = $item_2['id'];

            $arrSet = array(
                "jumlah" => $jml_new,
            );
            $conditeUpd = array(
                "id" => $jml_id,
                // "kode" => '',  ?
            );
            $this->db->set($arrSet);
            $this->db->where($conditeUpd);
            $var = $this->db->update($tbl_1);
            showLast_query("orange");

            $arrSet2 = array(
                "jenis" => "produk_delete",
            );
            $conditeUpd2 = array(
                "produk_id" => $pro_id,
                "id !=" => $jml_id,
                "state" => "active",
            );
            $this->db->set($arrSet2);
            $this->db->where($conditeUpd2);
            $var = $this->db->update($tbl_1);
            showLast_query("kuning");

            // break;
        }

        mati_disini(__LINE__);
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
    }


    public function test()
    {

        // Fungsi untuk menghilangkan spasi pada kunci dan nilai
        // function trimArray($item)
        // {
        //     foreach ($item as $key => $value) {
        //         if (is_array($value)) {
        //             $key_1 = trim($key);
        //
        //             $array_2 = trimArray($value);
        //
        //             $temp[$key_1] = $array_2;
        //         }
        //         else {
        //             $temp[trim($key)] = trim($value);
        //         }
        //     }
        //
        //     return $temp;
        // }

        // Array yang ingin di-trim dari spasi di dalam kunci dan nilai
        $array['items'] = array(
            "5588 " => array(
                "anu" => " sultan",
                " anus" => array(
                    "bapak " => " ibu"
                ),
            ),
        );

        arrPrintHijau($array);
        arrPrintWebs(trimArray($array));


    }

    // locker transaksi
    public function releaseLockerTransaksi()
    {
        $this->load->library("LockerTransaksi");
        $locker = new LockerTransaksi();

        $this->db->trans_start();

        $arrKiriman = array(
//            "modul" => "penjualan",
//            "transaksi_id" => 0,
            "user_id" => 0,
            "user_name" => "",
            "cabang_id" => "",
            "gudang_id" => "",
            "jenis" => "transaksi",
            "jenis_locker" => "transaksi",
            "transaksi_jenis" => "",
            "lock_token" => "",
            "reason" => "clearContent",
        );
        arrPrint($arrKiriman);
        $result = $locker->cleanupExpired($arrKiriman);
        arrPrintWebs($result);
        mati_disini(__LINE__);


        $this->db->trans_complete();

        cekHijau("<h3>SELESAI...</h3>");
    }


    public function index()
    {
        $this->lockerTransaksi();
    }

    public function lockerTransaksi()
    {
        $filters = $this->getLockerTransaksiFilters();
        $payload = $this->getLockerTransaksiPayload($filters);
        $queryString = http_build_query($filters);
        $thisPage = base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . "/lockerTransaksi";
        $backUrl = $thisPage;
        if (strlen(trim($queryString)) > 0) {
            $backUrl .= "?" . $queryString;
        }

        $flash = $this->session->flashdata("locker_transaksi_flash");

        $data = array(
            "title" => "LOCKER TRANSAKSI",
            "subTitle" => "List locker transaksi dengan state = hold",
            "rows" => $payload['rows'],
            "summary" => $payload['summary'],
            "filters" => $filters,
            "thisPage" => $thisPage,
            "backUrl" => $backUrl,
            "forceReleaseUrl" => base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . "/forceReleaseLockerTransaksi",
            "forceReleaseAllUrl" => base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . "/forceReleaseLockerTransaksiAll",
            "flash" => $flash,
        );
        $this->load->view("locker_transaksi", $data);
    }

    public function forceReleaseLockerTransaksi()
    {
        $lockID = trim((string)$this->input->get("lock_id", true));
        $modul = trim((string)$this->input->get("modul", true));
        $transaksiID = trim((string)$this->input->get("transaksi_id", true));
        $jenis = trim((string)$this->input->get("jenis", true));
        $jenisLocker = trim((string)$this->input->get("jenis_locker", true));
        $cabangID = trim((string)$this->input->get("cabang_id", true));
        $gudangID = trim((string)$this->input->get("gudang_id", true));
        $backUrl = trim((string)$this->input->get("back_url", true));

        if ($backUrl == "") {
            $backUrl = base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . "/lockerTransaksi";
        }

        if ($lockID == "" && ($modul == "" || $transaksiID == "")) {
            $this->session->set_flashdata("locker_transaksi_flash", array(
                "type" => "danger",
                "message" => "Parameter force release tidak lengkap.",
            ));
            redirect($backUrl);
            return;
        }

        $this->load->library("LockerTransaksi");
        $locker = new LockerTransaksi();
        $result = $locker->forceRelease(array(
            "lock_id" => $lockID,
            "modul" => $modul,
            "transaksi_id" => $transaksiID,
            "jenis" => $jenis != "" ? $jenis : "transaksi",
            "jenis_locker" => $jenisLocker != "" ? $jenisLocker : "transaksi",
            "cabang_id" => $cabangID,
            "gudang_id" => $gudangID,
            "user_id" => isset($this->session->login['id']) ? $this->session->login['id'] : 0,
            "user_name" => isset($this->session->login['nama']) ? $this->session->login['nama'] : "",
            "reason" => "manual_force_release_from_locker_dashboard",
        ));

        $this->session->set_flashdata("locker_transaksi_flash", array(
            "type" => isset($result['success']) && $result['success'] == true ? "success" : "danger",
            "message" => isset($result['message']) ? $result['message'] : "Force release locker transaksi selesai.",
        ));
        redirect($backUrl);
    }

    public function forceReleaseLockerTransaksiAll()
    {
        $filters = $this->getLockerTransaksiFilters();
        $backUrl = trim((string)$this->input->get("back_url", true));
        if ($backUrl == "") {
            $queryString = http_build_query($filters);
            $backUrl = base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . "/lockerTransaksi";
            if (strlen(trim($queryString)) > 0) {
                $backUrl .= "?" . $queryString;
            }
        }

        $payload = $this->getLockerTransaksiPayload($filters);
        $rows = isset($payload['rows']) && is_array($payload['rows']) ? $payload['rows'] : array();
        if (sizeof($rows) == 0) {
            $this->session->set_flashdata("locker_transaksi_flash", array(
                "type" => "warning",
                "message" => "Tidak ada locker yang cocok untuk dipaksa release.",
            ));
            redirect($backUrl);
            return;
        }

        $this->load->library("LockerTransaksi");
        $locker = new LockerTransaksi();
        $sessionLogin = isset($this->session->login) ? $this->session->login : array();
        $userId = isset($sessionLogin['id']) ? $sessionLogin['id'] : 0;
        $userName = isset($sessionLogin['nama']) ? $sessionLogin['nama'] : "";

        $successCount = 0;
        $failCount = 0;
        $failedLockIds = array();

        foreach ($rows as $row) {
            $lockID = isset($row['id']) ? intval($row['id']) : 0;
            if ($lockID <= 0) {
                $failCount++;
                continue;
            }

            $result = $locker->forceRelease(array(
                "lock_id" => $lockID,
                "user_id" => $userId,
                "user_name" => $userName,
                "reason" => "manual_force_release_all_from_locker_dashboard",
            ));

            if (isset($result['success']) && $result['success'] == true) {
                $successCount++;
            }
            else {
                $failCount++;
                $failedLockIds[] = $lockID;
            }
        }

        if ($successCount > 0 && $failCount > 0) {
            $msgType = "warning";
        }
        elseif ($successCount > 0) {
            $msgType = "success";
        }
        else {
            $msgType = "danger";
        }

        $message = "Force release massal selesai. Berhasil: " . $successCount . ", gagal: " . $failCount . ".";
        if (sizeof($failedLockIds) > 0) {
            $message .= " Gagal lock_id: " . implode(", ", $failedLockIds) . ".";
        }

        $this->session->set_flashdata("locker_transaksi_flash", array(
            "type" => $msgType,
            "message" => $message,
        ));
        redirect($backUrl);
    }

    protected function getLockerTransaksiFilters()
    {
        $filters = array(
            "modul" => trim((string)$this->input->get("modul", true)),
            "user" => trim((string)$this->input->get("user", true)),
            "cabang_id" => trim((string)$this->input->get("cabang_id", true)),
            "gudang_id" => trim((string)$this->input->get("gudang_id", true)),
            "status" => trim((string)$this->input->get("status", true)),
        );

        if ($filters['status'] == "") {
            $filters['status'] = "all";
        }

        return $filters;
    }

    protected function getLockerTransaksiPayload($filters)
    {
        $now = date("Y-m-d H:i:s");
        $this->db->from("stock_locker_transaksi");
        $this->db->where("state", "hold");
        $this->db->where("jumlah >", 0);

        if ($filters['modul'] != "") {
            $this->db->like("modul", $filters['modul']);
        }

        if ($filters['user'] != "") {
            $this->db->group_start();
            if (ctype_digit($filters['user'])) {
                $this->db->where("oleh_id", $filters['user']);
                $this->db->or_like("oleh_nama", $filters['user']);
            }
            else {
                $this->db->like("oleh_nama", $filters['user']);
                $this->db->or_like("lock_token", $filters['user']);
            }
            $this->db->group_end();
        }

        if ($filters['cabang_id'] != "") {
            $this->db->where("cabang_id", $filters['cabang_id']);
        }

        if ($filters['gudang_id'] != "") {
            $this->db->where("gudang_id", $filters['gudang_id']);
        }

        $this->db->order_by("modul", "ASC");
        $this->db->order_by("transaksi_id", "ASC");
        $this->db->order_by("id", "ASC");
        $rows = $this->db->get()->result_array();

        $result = array();
        $summary = array(
            "total_rows" => 0,
            "busy_rows" => 0,
            "expired_rows" => 0,
            "total_jumlah" => 0,
        );

        foreach ($rows as $row) {
            $isExpired = $this->isLockerTransaksiExpired($row, $now);
            if ($filters['status'] == "busy" && $isExpired) {
                continue;
            }
            if ($filters['status'] == "expired" && !$isExpired) {
                continue;
            }

            $remainingSeconds = $this->remainingLockerTransaksiSeconds($row, $now);
            $row['status_code'] = $isExpired ? "expired" : "busy";
            $row['status_label'] = $isExpired ? "EXPIRED" : "BUSY";
            $row['status_badge_class'] = $isExpired ? "label label-danger" : "label label-success";
            $row['row_style'] = $isExpired ? "background-color:#ffe5e5;" : "background-color:#edf9ed;";
            $row['is_expired'] = $isExpired ? 1 : 0;
            $row['remaining_seconds'] = $remainingSeconds;
            $row['remaining_text'] = $isExpired ? "expired" : $this->formatLockerRemainingSeconds($remainingSeconds);
            $row['owner_display'] = $this->buildLockerOwnerDisplay($row);
            $row['cabang_display'] = $this->buildLockerPlaceDisplay($row, "cabang_id");
            $row['gudang_display'] = $this->buildLockerPlaceDisplay($row, "gudang_id");
            $result[] = $row;

            $summary['total_rows']++;
            $summary['total_jumlah'] += (int)$row['jumlah'];
            if ($isExpired) {
                $summary['expired_rows']++;
            }
            else {
                $summary['busy_rows']++;
            }
        }

        usort($result, function ($a, $b) {
            $aExpired = (int)$a['is_expired'];
            $bExpired = (int)$b['is_expired'];
            if ($aExpired < $bExpired) {
                return -1;
            }
            if ($aExpired > $bExpired) {
                return 1;
            }

            $aModul = (string)$a['modul'];
            $bModul = (string)$b['modul'];
            $cmp = strcmp($aModul, $bModul);
            if ($cmp !== 0) {
                return $cmp;
            }

            $aTransaksi = (int)$a['transaksi_id'];
            $bTransaksi = (int)$b['transaksi_id'];
            if ($aTransaksi < $bTransaksi) {
                return -1;
            }
            if ($aTransaksi > $bTransaksi) {
                return 1;
            }

            return 0;
        });

        return array(
            "rows" => $result,
            "summary" => $summary,
        );
    }

    protected function isLockerTransaksiExpired($row, $now = null)
    {
        if ($now == null) {
            $now = date("Y-m-d H:i:s");
        }

        if (!isset($row['expires_at']) || trim((string)$row['expires_at']) == "") {
            return false;
        }

        return strtotime($row['expires_at']) < strtotime($now);
    }

    protected function remainingLockerTransaksiSeconds($row, $now = null)
    {
        if ($now == null) {
            $now = date("Y-m-d H:i:s");
        }

        if (!isset($row['expires_at']) || trim((string)$row['expires_at']) == "") {
            return null;
        }

        $diff = strtotime($row['expires_at']) - strtotime($now);
        return $diff > 0 ? $diff : 0;
    }

    protected function formatLockerRemainingSeconds($seconds)
    {
        if ($seconds === null) {
            return "-";
        }

        $seconds = (int)$seconds;
        if ($seconds <= 0) {
            return "0s";
        }

        $days = floor($seconds / 86400);
        $seconds = $seconds % 86400;
        $hours = floor($seconds / 3600);
        $seconds = $seconds % 3600;
        $minutes = floor($seconds / 60);
        $seconds = $seconds % 60;

        $parts = array();
        if ($days > 0) {
            $parts[] = $days . "d";
        }
        if ($hours > 0) {
            $parts[] = $hours . "h";
        }
        if ($minutes > 0) {
            $parts[] = $minutes . "m";
        }
        if ($seconds > 0 || sizeof($parts) == 0) {
            $parts[] = $seconds . "s";
        }

        return implode(" ", $parts);
    }

    protected function buildLockerOwnerDisplay($row)
    {
        if (isset($row['oleh_nama']) && trim((string)$row['oleh_nama']) != "") {
            return $row['oleh_nama'] . " (#" . (isset($row['oleh_id']) ? $row['oleh_id'] : 0) . ")";
        }

        return "User #" . (isset($row['oleh_id']) ? $row['oleh_id'] : 0);
    }

    protected function buildLockerPlaceDisplay($row, $fieldName)
    {
        if (!isset($row[$fieldName]) || trim((string)$row[$fieldName]) == "") {
            return "-";
        }

        return "#" . $row[$fieldName];
    }
}
