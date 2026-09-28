<?php

class LockerTransaksi
{
    protected $CI;
    protected $tableName = "stock_locker_transaksi";
    protected $defaultTtl = 900;// ini dalam detik, saat ini setting 15 menit
    protected $defaultJenis = "transaksi";
    protected $defaultJenisLocker = "transaksi";

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model("Mdls/MdlLockerTransaksi");
    }

    public function acquire($params = array())
    {
        $context = $this->normalizeParams($params);
        $validation = $this->validateRequired($context, array("modul", "transaksi_id", "user_id"));
        if ($validation !== true) {
            return $validation;
        }

        $this->CI->db->trans_begin();
        $result = $this->acquireLocked($context);
        if ($result['success'] == true) {
            $this->CI->db->trans_commit();
            return $result;
        }

        $this->CI->db->trans_rollback();
        return $result;
    }

    public function release($params = array())
    {
        $context = $this->normalizeParams($params);
        $validation = $this->validateRequired($context, array("modul", "transaksi_id", "user_id"));
        if ($validation !== true) {
            return $validation;
        }

        return $this->releaseLocked($context, false);
    }

    public function refresh($params = array())
    {
        $context = $this->normalizeParams($params);
        $validation = $this->validateRequired($context, array("modul", "transaksi_id", "user_id", "lock_token"));
        if ($validation !== true) {
            return $validation;
        }

        $rows = $this->fetchLockRows($context, true);
        if (sizeof($rows) == 0) {
            return $this->makeResponse(false, "LOCK_NOT_FOUND", "Lock tidak ditemukan", array());
        }

        $matched = $this->findActiveOwnedRow($rows, $context, true);
        if ($matched == null) {
            return $this->makeResponse(false, "LOCK_NOT_OWNER", "Lock bukan milik requester", array());
        }

        if ($this->isExpiredRow($matched)) {
            return $this->makeResponse(false, "LOCK_EXPIRED", "Lock sudah expired", array());
        }

        $now = $this->now();
        $expiresAt = $this->addSeconds($now, $context['ttl']);
        $update = array(
            "locked_at" => isset($matched->locked_at) && strlen(trim($matched->locked_at)) > 0 ? $matched->locked_at : $now,
            "expires_at" => $expiresAt,
            "heartbeat_at" => $now,
            "last_access" => $now,
            "oleh_id" => $context['user_id'],
            "oleh_nama" => $context['user_name'],
            "cabang_id" => $context['cabang_id'],
            "gudang_id" => $context['gudang_id'],
            "jenis" => $context['jenis'],
            "jenis_locker" => $context['jenis_locker'],
            "state" => "hold",
            "jumlah" => 1,
            "note" => $context['reason'],
        );
        if ($this->columnExists("lock_token")) {
            $update["lock_token"] = $context['lock_token'];
        }
        if ($this->columnExists("owner_session_id")) {
            $update["owner_session_id"] = $context['owner_session_id'];
        }
        if ($this->columnExists("owner_ip")) {
            $update["owner_ip"] = $context['owner_ip'];
        }

        $this->CI->db->where("id", $matched->id);
        $updated = $this->CI->db->update($this->tableName, $update);
        if ($updated == false) {
            return $this->makeResponse(false, "LOCK_ERROR", "Gagal memperpanjang lock", array());
        }

        return $this->makeResponse(true, "LOCK_RENEWED", "Lock berhasil diperpanjang", $this->buildRowData($matched, $update));
    }

    public function inspect($params = array())
    {
        $context = $this->normalizeParams($params);
        $validation = $this->validateRequired($context, array("modul", "transaksi_id"));
        if ($validation !== true) {
            return $validation;
        }

        $rows = $this->fetchLockRows($context, false);
        $summary = $this->summarizeRows($rows, $context);
        return $this->makeResponse(true, "LOCK_INSPECTED", "Status lock berhasil dibaca", $summary);
    }

    public function cleanupExpired($params = array())
    {
        $context = $this->normalizeParams($params);

        $this->CI->db->from($this->tableName);
        $this->CI->db->where("expires_at IS NOT NULL", null, false);
        $this->CI->db->where("expires_at < " . $this->CI->db->escape($this->now()), null, false);
        if (isset($context['modul']) && strlen(trim($context['modul'])) > 0) {
            $this->CI->db->where("modul", $context['modul']);
        }
        if (isset($context['transaksi_id']) && strlen(trim((string)$context['transaksi_id'])) > 0) {
            $this->CI->db->where("transaksi_id", $context['transaksi_id']);
        }
        $query = $this->CI->db->get_compiled_select();
        $rows = $this->CI->db->query($query . " FOR UPDATE")->result();
        $affected = 0;
        foreach ($rows as $row) {
            $update = array(
                "jumlah" => 0,
                "released_at" => $this->now(),
                "released_by" => isset($context['user_id']) ? $context['user_id'] : 0,
                "last_access" => $this->now(),
            );
            if ($this->columnExists("lock_token")) {
                $update["lock_token"] = null;
            }
            if ($this->columnExists("expires_at")) {
                $update["expires_at"] = $this->now();
            }
            if ($this->columnExists("heartbeat_at")) {
                $update["heartbeat_at"] = $this->now();
            }
            $this->CI->db->where("id", $row->id);
            $updated = $this->CI->db->update($this->tableName, $update);
//            cekOrange($this->CI->db->last_query());
            if ($updated == false) {
                return $this->makeResponse(false, "LOCK_ERROR", "Gagal membersihkan lock expired", array());
            }
            $affected += $this->CI->db->affected_rows();
        }
        return $this->makeResponse(true, "LOCK_CLEANED", "Lock expired berhasil dibersihkan", array(
            "affected_rows" => $affected,
        ));
    }

    public function cleanupMyOwnExpired($params = array())
    {
        $context = $this->normalizeParams($params);
        arrPrintWebs($context);
        $this->CI->db->from($this->tableName);
        $this->CI->db->where("expires_at IS NOT NULL", null, false);
        $this->CI->db->where("expires_at < " . $this->CI->db->escape($this->now()), null, false);
        $this->CI->db->where("state", "hold");
        $this->CI->db->where("oleh_id", $context['user_id']);
        $this->CI->db->where("jumlah>", 0);
//        if (isset($context['modul']) && strlen(trim($context['modul'])) > 0) {
//            $this->CI->db->where("modul", $context['modul']);
//        }
//        if (isset($context['transaksi_id']) && strlen(trim((string)$context['transaksi_id'])) > 0) {
//            $this->CI->db->where("transaksi_id", $context['transaksi_id']);
//        }
        $query = $this->CI->db->get_compiled_select();
        $rows = $this->CI->db->query($query . " FOR UPDATE")->result();
        cekMerah($this->CI->db->last_query());
        arrPrintWebs($rows);
        $affected = 0;
        foreach ($rows as $row) {
            $update = array(
                "jumlah" => 0,
                "released_at" => $this->now(),
                "released_by" => isset($context['user_id']) ? $context['user_id'] : 0,
                "last_access" => $this->now(),
            );
            if ($this->columnExists("lock_token")) {
                $update["lock_token"] = null;
            }
            if ($this->columnExists("expires_at")) {
                $update["expires_at"] = $this->now();
            }
            if ($this->columnExists("heartbeat_at")) {
                $update["heartbeat_at"] = $this->now();
            }
            $this->CI->db->where("id", $row->id);
            $updated = $this->CI->db->update($this->tableName, $update);
            cekMerah($this->CI->db->last_query());
            if ($updated == false) {
                return $this->makeResponse(false, "LOCK_ERROR", "Gagal membersihkan lock expired", array());
            }
            $affected += $this->CI->db->affected_rows();
        }
        return $this->makeResponse(true, "LOCK_CLEANED", "Lock expired berhasil dibersihkan", array(
            "affected_rows" => $affected,
        ));
    }

    public function forceRelease($params = array())
    {
        $context = $this->normalizeParams($params);
        $lockID = isset($params['lock_id']) ? intval($params['lock_id']) : 0;
        if ($lockID > 0) {
            return $this->forceReleaseById($lockID, $context);
        }

        $validation = $this->validateRequired($context, array("modul", "transaksi_id"));
        if ($validation !== true) {
            return $validation;
        }

        $rows = $this->fetchLockRows($context, true);
        if (sizeof($rows) == 0) {
            return $this->makeResponse(false, "LOCK_NOT_FOUND", "Lock tidak ditemukan", array());
        }

        $now = $this->now();
        foreach ($rows as $row) {
            $update = array(
                "jumlah" => 0,
                "released_at" => $now,
                "released_by" => $context['user_id'],
                "last_access" => $now,
                "note" => $context['reason'],
            );
            if ($this->columnExists("lock_token")) {
                $update["lock_token"] = null;
            }
            if ($this->columnExists("expires_at")) {
                $update["expires_at"] = $now;
            }
            if ($this->columnExists("heartbeat_at")) {
                $update["heartbeat_at"] = $now;
            }
            $this->CI->db->where("id", $row->id);
            $updated = $this->CI->db->update($this->tableName, $update);
            if ($updated == false) {
                return $this->makeResponse(false, "LOCK_ERROR", "Gagal force release lock", array());
            }
        }

        return $this->makeResponse(true, "LOCK_RELEASED", "Lock berhasil dilepas", array(
            "released_rows" => sizeof($rows),
        ));
    }

    public function execLocker($mainGate, $nextStepNum, $refID, $newID, $modul = NULL)
    {
        $audit = $this->syncLockerTransaksi($mainGate, $nextStepNum, $refID, $newID, $modul, "execLocker");
        if (isset($audit['success']) && ($audit['success'] == true)) {
            return $this->makeResponse(true, "LOCK_SYNCED", "Locker transaksi tersinkron", $audit);
        }

        return $this->makeResponse(false, "LOCK_SYNC_FAILED", "Locker transaksi gagal disinkron", $audit);
    }

    public function syncLockerTransaksi($mainGate, $nextStepNum, $refID, $newID, $modul = NULL, $reason = "syncLockerTransaksi")
    {
        $contextModul = $modul;
        if ($contextModul == NULL && is_array($mainGate) && isset($mainGate['modul'])) {
            $contextModul = $mainGate['modul'];
        }
        if ($contextModul == NULL || strlen(trim($contextModul)) == 0) {
            $contextModul = isset($this->defaultModul) ? $this->defaultModul : "transaksi";
        }

        $sessionLogin = isset($this->CI->session->login) ? $this->CI->session->login : array();
        $userId = isset($sessionLogin['id']) ? $sessionLogin['id'] : 0;
        $userName = isset($sessionLogin['nama']) ? $sessionLogin['nama'] : "";
        $cabangId = isset($sessionLogin['cabang_id']) ? $sessionLogin['cabang_id'] : 0;
        $gudangId = isset($sessionLogin['gudang_id']) ? $sessionLogin['gudang_id'] : 0;
        $transaksiJenis = is_array($mainGate) && isset($mainGate['jenisTr']) ? $mainGate['jenisTr'] : $contextModul;
        $placeId = is_array($mainGate) && isset($mainGate['placeID']) ? $mainGate['placeID'] : $cabangId;
        $mainGudangId = is_array($mainGate) && isset($mainGate['gudangID']) ? $mainGate['gudangID'] : $gudangId;

        $audit = array(
            "success" => true,
            "modul" => $contextModul,
            "reason" => $reason,
            "ref_id" => $refID,
            "new_id" => $newID,
            "next_step" => $nextStepNum,
            "release" => array(),
            "acquire" => array(),
        );

        if ($refID != NULL && intval($refID) > 0) {
            log_message("debug", "[LockerSync] " . json_encode(array_merge($audit, array(
                    "stage" => "release_start",
                ))));

            $releaseResult = $this->release(array(
                "modul" => $contextModul,
                "transaksi_id" => $refID,
                "user_id" => $userId,
                "user_name" => $userName,
                "cabang_id" => $placeId,
                "gudang_id" => $mainGudangId,
                "jenis" => "transaksi",
                "jenis_locker" => "transaksi",
                "transaksi_jenis" => $transaksiJenis,
                "reason" => $reason . "_release",
            ));
            $audit['release'] = $releaseResult;
            log_message("debug", "[LockerSync] " . json_encode(array_merge($audit, array(
                    "stage" => "release_done",
                    "success" => isset($releaseResult['success']) ? $releaseResult['success'] : false,
                    "code" => isset($releaseResult['code']) ? $releaseResult['code'] : "",
                ))));

            if (!(isset($releaseResult['success']) && ($releaseResult['success'] == true))) {
                log_message("debug", "[LockerSync] " . json_encode(array_merge($audit, array(
                        "stage" => "release_force",
                    ))));
                $this->forceRelease(array(
                    "modul" => $contextModul,
                    "transaksi_id" => $refID,
                    "user_id" => $userId,
                    "user_name" => $userName,
                    "reason" => $reason . "_force_release",
                ));
            }

            $refLockerSessionKey = "_LOCKER_" . $contextModul . "_" . $refID;
            if (isset($_SESSION[$refLockerSessionKey])) {
                unset($_SESSION[$refLockerSessionKey]);
            }
        }

//        if ($newID != NULL && intval($newID) > 0 && intval($nextStepNum) != 0) {
//            log_message("debug", "[LockerSync] " . json_encode(array_merge($audit, array(
//                    "stage" => "acquire_start",
//                ))));
//
//            $acquireResult = $this->acquire(array(
//                "modul" => $contextModul,
//                "transaksi_id" => $newID,
//                "user_id" => $userId,
//                "user_name" => $userName,
//                "cabang_id" => $placeId,
//                "gudang_id" => $mainGudangId,
//                "jenis" => "transaksi",
//                "jenis_locker" => "transaksi",
//                "transaksi_jenis" => $transaksiJenis,
//                "reason" => $reason . "_acquire",
//            ));
//
//            $audit['acquire'] = $acquireResult;
//            if (isset($acquireResult['success']) && ($acquireResult['success'] == true)) {
//                $newLockerSessionKey = "_LOCKER_" . $contextModul . "_" . $newID;
//                $_SESSION[$newLockerSessionKey] = isset($acquireResult['data']) ? $acquireResult['data'] : array();
//                log_message("debug", "[LockerSync] " . json_encode(array_merge($audit, array(
//                        "stage" => "acquire_done",
//                        "success" => true,
//                        "code" => isset($acquireResult['code']) ? $acquireResult['code'] : "",
//                    ))));
//            }
//            else {
//                $audit['success'] = false;
//                log_message("debug", "[LockerSync] " . json_encode(array_merge($audit, array(
//                        "stage" => "acquire_failed",
//                        "success" => false,
//                        "code" => isset($acquireResult['code']) ? $acquireResult['code'] : "",
//                    ))));
//            }
//        }

        return $audit;
    }

    public function inspectTransaksi($transaksi_id, $modul, $transaksi_jenis = NULL, $jenis = "transaksi", $jenis_locker = "transaksi")
    {
        $sessionLogin = isset($this->CI->session->login) ? $this->CI->session->login : array();
        return $this->inspect(array(
            "modul" => $modul,
            "transaksi_id" => $transaksi_id,
            "transaksi_jenis" => $transaksi_jenis,
            "user_id" => isset($sessionLogin['id']) ? $sessionLogin['id'] : 0,
            "user_name" => isset($sessionLogin['nama']) ? $sessionLogin['nama'] : "",
            "cabang_id" => isset($sessionLogin['cabang_id']) ? $sessionLogin['cabang_id'] : 0,
            "gudang_id" => isset($sessionLogin['gudang_id']) ? $sessionLogin['gudang_id'] : 0,
            "jenis" => $jenis,
            "jenis_locker" => $jenis_locker,
        ));
    }

    public function inspectTransaksiBatch($arrTransID, $modul, $transaksi_jenis = NULL, $jenis = "transaksi", $jenis_locker = "transaksi")
    {
        if (!is_array($arrTransID)) {
            $arrTransID = array();
        }

        $result = array();
        foreach ($arrTransID as $transaksi_id) {
            $result[$transaksi_id] = $this->inspectTransaksi($transaksi_id, $modul, $transaksi_jenis, $jenis, $jenis_locker);
        }

        return $this->makeResponse(true, "LOCK_BATCH_INSPECTED", "Status lock batch berhasil dibaca", $result);
    }

    public function lockTransaksi($transaksi_id, $transaksi_jenis, $modul)
    {
        return $this->acquire(array(
            "modul" => $modul,
            "transaksi_id" => $transaksi_id,
            "transaksi_jenis" => $transaksi_jenis,
            "user_id" => isset($this->CI->session->login['id']) ? $this->CI->session->login['id'] : 0,
            "user_name" => isset($this->CI->session->login['nama']) ? $this->CI->session->login['nama'] : "",
            "cabang_id" => isset($this->CI->session->login['cabang_id']) ? $this->CI->session->login['cabang_id'] : 0,
            "gudang_id" => isset($this->CI->session->login['gudang_id']) ? $this->CI->session->login['gudang_id'] : 0,
            "jenis" => "transaksi",
            "jenis_locker" => "transaksi",
        ));
    }

    public function releaseTransaksi($transaksi_id, $transaksi_jenis, $modul)
    {
        return $this->release(array(
            "modul" => $modul,
            "transaksi_id" => $transaksi_id,
            "transaksi_jenis" => $transaksi_jenis,
            "user_id" => isset($this->CI->session->login['id']) ? $this->CI->session->login['id'] : 0,
            "user_name" => isset($this->CI->session->login['nama']) ? $this->CI->session->login['nama'] : "",
            "cabang_id" => isset($this->CI->session->login['cabang_id']) ? $this->CI->session->login['cabang_id'] : 0,
            "gudang_id" => isset($this->CI->session->login['gudang_id']) ? $this->CI->session->login['gudang_id'] : 0,
            "jenis" => "transaksi",
            "jenis_locker" => "transaksi",
        ));
    }

    public function refreshTransaksi($transaksi_id, $transaksi_jenis, $modul)
    {
        $lockerSessionKey = "_LOCKER_" . $modul . "_" . $transaksi_id;
        $lockerToken = isset($_SESSION[$lockerSessionKey]['lock_token']) ? $_SESSION[$lockerSessionKey]['lock_token'] : "";
        return $this->refresh(array(
            "modul" => $modul,
            "transaksi_id" => $transaksi_id,
            "transaksi_jenis" => $transaksi_jenis,
            "user_id" => isset($this->CI->session->login['id']) ? $this->CI->session->login['id'] : 0,
            "user_name" => isset($this->CI->session->login['nama']) ? $this->CI->session->login['nama'] : "",
            "cabang_id" => isset($this->CI->session->login['cabang_id']) ? $this->CI->session->login['cabang_id'] : 0,
            "gudang_id" => isset($this->CI->session->login['gudang_id']) ? $this->CI->session->login['gudang_id'] : 0,
            "jenis" => "transaksi",
            "jenis_locker" => "transaksi",
            "lock_token" => $lockerToken,
        ));
    }

    public function lockTransaksiCrm($transaksi_id, $transaksi_jenis, $modul)
    {
        return $this->acquire(array(
            "modul" => $modul,
            "transaksi_id" => $transaksi_id,
            "transaksi_jenis" => $transaksi_jenis,
            "user_id" => isset($this->CI->session->login['id']) ? $this->CI->session->login['id'] : 0,
            "user_name" => isset($this->CI->session->login['nama']) ? $this->CI->session->login['nama'] : "",
            "cabang_id" => isset($this->CI->session->login['cabang_id']) ? $this->CI->session->login['cabang_id'] : 0,
            "gudang_id" => isset($this->CI->session->login['gudang_id']) ? $this->CI->session->login['gudang_id'] : 0,
            "jenis" => "crm",
            "jenis_locker" => "crm",
        ));
    }

    public function releaseTransaksiCrm($transaksi_id, $transaksi_jenis, $modul)
    {
        return $this->release(array(
            "modul" => $modul,
            "transaksi_id" => $transaksi_id,
            "transaksi_jenis" => $transaksi_jenis,
            "user_id" => isset($this->CI->session->login['id']) ? $this->CI->session->login['id'] : 0,
            "user_name" => isset($this->CI->session->login['nama']) ? $this->CI->session->login['nama'] : "",
            "cabang_id" => isset($this->CI->session->login['cabang_id']) ? $this->CI->session->login['cabang_id'] : 0,
            "gudang_id" => isset($this->CI->session->login['gudang_id']) ? $this->CI->session->login['gudang_id'] : 0,
            "jenis" => "crm",
            "jenis_locker" => "crm",
        ));
    }

    protected function acquireLocked($context)
    {
        $rows = $this->fetchLockRows($context, true);
        $now = $this->now();
        $lockToken = isset($context['lock_token']) && strlen(trim($context['lock_token'])) > 0 ? trim($context['lock_token']) : $this->makeToken();
        $expiresAt = $this->addSeconds($now, $context['ttl']);

        $ownedRow = $this->findOwnedRow($rows, $context, true);
        if ($ownedRow != null) {
            $update = array(
                "jumlah" => 1,
                "state" => "hold",
                "locked_at" => isset($ownedRow->locked_at) && strlen(trim($ownedRow->locked_at)) > 0 ? $ownedRow->locked_at : $now,
                "expires_at" => $expiresAt,
                "heartbeat_at" => $now,
                "last_access" => $now,
                "oleh_id" => $context['user_id'],
                "oleh_nama" => $context['user_name'],
                "cabang_id" => $context['cabang_id'],
                "gudang_id" => $context['gudang_id'],
                "jenis" => $context['jenis'],
                "jenis_locker" => $context['jenis_locker'],
                "note" => $context['reason'],
            );
            if ($this->columnExists("lock_token")) {
                $update["lock_token"] = $lockToken;
            }
            if ($this->columnExists("owner_session_id")) {
                $update["owner_session_id"] = $context['owner_session_id'];
            }
            if ($this->columnExists("owner_ip")) {
                $update["owner_ip"] = $context['owner_ip'];
            }
            $this->CI->db->where("id", $ownedRow->id);
            $this->CI->db->update($this->tableName, $update);
            if ($this->CI->db->trans_status() === false) {
                return $this->makeResponse(false, "LOCK_ERROR", "Gagal mengambil lock", array());
            }

            return $this->makeResponse(true, "LOCK_ACQUIRED", "Lock berhasil diambil", $this->buildRowData($ownedRow, $update));
        }

        $busyRow = $this->findBusyRow($rows, $context);
        if ($busyRow != null && !$this->isExpiredRow($busyRow)) {
            return $this->makeResponse(false, "LOCK_BUSY", "Transaksi sedang dipakai user lain", $this->buildBusyData($busyRow));
        }

        $expiredRow = $this->findExpiredRow($rows);
        if ($expiredRow != null) {
            $update = array(
                "jenis" => $context['jenis'],
                "jenis_locker" => $context['jenis_locker'],
                "cabang_id" => $context['cabang_id'],
                "gudang_id" => $context['gudang_id'],
                "produk_id" => $context['transaksi_id'],
                "nama" => $context['nama'],
                "persediaan" => $context['persediaan'],
                "jumlah" => 1,
                "satuan" => $context['satuan'],
                "state" => "hold",
                "nilai" => $context['nilai'],
                "fulldate" => $now,
                "oleh_id" => $context['user_id'],
                "oleh_nama" => $context['user_name'],
                "transaksi_id" => $context['transaksi_id'],
                "nomer" => $context['nomer'],
                "note" => $context['reason'],
                "last_access" => $now,
                "transaksi_no" => $context['transaksi_no'],
                "transaksi_jenis" => $context['transaksi_jenis'],
                "modul" => $context['modul'],
            );
            if ($this->columnExists("lock_token")) {
                $update["lock_token"] = $lockToken;
            }
            if ($this->columnExists("locked_at")) {
                $update["locked_at"] = $now;
            }
            if ($this->columnExists("expires_at")) {
                $update["expires_at"] = $expiresAt;
            }
            if ($this->columnExists("heartbeat_at")) {
                $update["heartbeat_at"] = $now;
            }
            if ($this->columnExists("owner_session_id")) {
                $update["owner_session_id"] = $context['owner_session_id'];
            }
            if ($this->columnExists("owner_ip")) {
                $update["owner_ip"] = $context['owner_ip'];
            }
            $this->CI->db->where("id", $expiredRow->id);
            $this->CI->db->update($this->tableName, $update);
            if ($this->CI->db->trans_status() === false) {
                return $this->makeResponse(false, "LOCK_ERROR", "Gagal mengambil lock", array());
            }

            return $this->makeResponse(true, "LOCK_ACQUIRED", "Lock berhasil diambil", $this->buildRowData($expiredRow, $update));
        }

        $insert = array(
            "jenis" => $context['jenis'],
            "jenis_locker" => $context['jenis_locker'],
            "cabang_id" => $context['cabang_id'],
            "gudang_id" => $context['gudang_id'],
            "produk_id" => $context['transaksi_id'],
            "nama" => $context['nama'],
            "persediaan" => $context['persediaan'],
            "jumlah" => 1,
            "satuan" => $context['satuan'],
            "state" => "hold",
            "nilai" => $context['nilai'],
            "fulldate" => $now,
            "oleh_id" => $context['user_id'],
            "oleh_nama" => $context['user_name'],
            "transaksi_id" => $context['transaksi_id'],
            "nomer" => $context['nomer'],
            "note" => $context['reason'],
            "last_access" => $now,
            "transaksi_no" => $context['transaksi_no'],
            "transaksi_jenis" => $context['transaksi_jenis'],
            "modul" => $context['modul'],
        );
        if ($this->columnExists("lock_token")) {
            $insert["lock_token"] = $lockToken;
        }
        if ($this->columnExists("locked_at")) {
            $insert["locked_at"] = $now;
        }
        if ($this->columnExists("expires_at")) {
            $insert["expires_at"] = $expiresAt;
        }
        if ($this->columnExists("heartbeat_at")) {
            $insert["heartbeat_at"] = $now;
        }
        if ($this->columnExists("owner_session_id")) {
            $insert["owner_session_id"] = $context['owner_session_id'];
        }
        if ($this->columnExists("owner_ip")) {
            $insert["owner_ip"] = $context['owner_ip'];
        }

        $insertID = $this->CI->db->insert($this->tableName, $insert);
        if ($insertID == false || $this->CI->db->trans_status() === false) {
            return $this->makeResponse(false, "LOCK_ERROR", "Gagal menyimpan lock", array());
        }

        return $this->makeResponse(true, "LOCK_ACQUIRED", "Lock berhasil diambil", array(
            "lock_id" => $this->CI->db->insert_id(),
            "lock_token" => $lockToken,
            "locked_at" => $now,
            "expires_at" => $expiresAt,
        ));
    }

    protected function releaseLocked($context, $force = false)
    {
        $rows = $this->fetchLockRows($context, true);
        if (sizeof($rows) == 0) {
            return $this->makeResponse(false, "LOCK_NOT_FOUND", "Lock tidak ditemukan", array());
        }

        $matched = $force ? $this->findAnyActiveRow($rows) : $this->findActiveOwnedRow($rows, $context, true);
        if ($matched == null) {
            return $this->makeResponse(false, "LOCK_NOT_OWNER", "Lock bukan milik requester", array());
        }

        $now = $this->now();
        $update = array(
            "jumlah" => 0,
            "state" => "hold",
            "released_at" => $now,
            "released_by" => $context['user_id'],
            "last_access" => $now,
            "note" => $context['reason'],
        );
        if ($this->columnExists("lock_token")) {
            $update["lock_token"] = null;
        }
        if ($this->columnExists("expires_at")) {
            $update["expires_at"] = $now;
        }
        if ($this->columnExists("heartbeat_at")) {
            $update["heartbeat_at"] = $now;
        }

        $this->CI->db->where("id", $matched->id);
        $this->CI->db->update($this->tableName, $update);
        cekHere($this->CI->db->last_query());
        if ($this->CI->db->trans_status() === false) {
            return $this->makeResponse(false, "LOCK_ERROR", "Gagal melepas lock", array());
        }

        return $this->makeResponse(true, "LOCK_RELEASED", "Lock berhasil dilepas", $this->buildRowData($matched, $update));
    }

    protected function forceReleaseById($lockID, $context)
    {
        $lockID = intval($lockID);
        if ($lockID <= 0) {
            return $this->makeResponse(false, "LOCK_INVALID_PARAM", "Parameter lock tidak lengkap: lock_id", array());
        }

        $this->CI->db->from($this->tableName);
        $this->CI->db->where("id", $lockID);
        $row = $this->CI->db->get()->row();
        if ($row == null) {
            return $this->makeResponse(false, "LOCK_NOT_FOUND", "Lock tidak ditemukan", array());
        }

        $now = $this->now();
        $update = array(
            "jumlah" => 0,
            "state" => "hold",
            "released_at" => $now,
            "released_by" => isset($context['user_id']) ? $context['user_id'] : 0,
            "last_access" => $now,
            "note" => isset($context['reason']) ? $context['reason'] : "",
        );
        if ($this->columnExists("lock_token")) {
            $update["lock_token"] = null;
        }
        if ($this->columnExists("expires_at")) {
            $update["expires_at"] = $now;
        }
        if ($this->columnExists("heartbeat_at")) {
            $update["heartbeat_at"] = $now;
        }

        $this->CI->db->where("id", $row->id);
        $updated = $this->CI->db->update($this->tableName, $update);
        if ($updated == false) {
            return $this->makeResponse(false, "LOCK_ERROR", "Gagal force release lock", array());
        }

        return $this->makeResponse(true, "LOCK_RELEASED", "Lock berhasil dilepas", $this->buildRowData($row, $update));
    }

    protected function fetchLockRows($context, $forUpdate = false)
    {
        $this->CI->db->from($this->tableName);
        $this->CI->db->where("modul", $context['modul']);
        $this->CI->db->where("transaksi_id", $context['transaksi_id']);
        $this->CI->db->where("jenis", $context['jenis']);
        $this->CI->db->where("jenis_locker", $context['jenis_locker']);
        if (isset($context['cabang_id'])) {
            $this->CI->db->where("cabang_id", $context['cabang_id']);
        }
        if (isset($context['gudang_id'])) {
            $this->CI->db->where("gudang_id", $context['gudang_id']);
        }
        $this->CI->db->order_by("id", "ASC");
        $this->CI->db->limit(20);
        $query = $this->CI->db->get_compiled_select();
        if ($forUpdate == true) {
            $query .= " FOR UPDATE";
        }
        return $this->CI->db->query($query)->result();
    }

    protected function findOwnedRow($rows, $context, $allowLegacy = false)
    {
        if (sizeof($rows) == 0) {
            return null;
        }

        foreach ($rows as $row) {
            if (!$this->isActiveQuantityRow($row)) {
                continue;
            }
            $rowToken = isset($row->lock_token) ? trim((string)$row->lock_token) : "";
            $ctxToken = isset($context['lock_token']) ? trim((string)$context['lock_token']) : "";
            $sameOwner = isset($row->oleh_id) && ($row->oleh_id == $context['user_id']);
            $sameToken = ($rowToken !== "" && $ctxToken !== "" && $rowToken === $ctxToken);
            if ($sameToken) {
                return $row;
            }
            if ($ctxToken !== "" && $rowToken !== "" && $rowToken !== $ctxToken) {
                continue;
            }
            if ($sameOwner) {
                return $row;
            }
        }

        if ($allowLegacy == true) {
            foreach ($rows as $row) {
                if (!isset($row->lock_token) || strlen(trim((string)$row->lock_token)) == 0) {
                    if (isset($row->oleh_id) && $row->oleh_id == $context['user_id']) {
                        return $row;
                    }
                }
            }
        }

        return null;
    }

    protected function findActiveOwnedRow($rows, $context, $allowLegacy = false)
    {
        return $this->findOwnedRow($rows, $context, $allowLegacy);
    }

    protected function findAnyActiveRow($rows)
    {
        foreach ($rows as $row) {
            if ($this->isActiveQuantityRow($row) && !$this->isExpiredRow($row)) {
                return $row;
            }
        }

        return null;
    }

    protected function findExpiredRow($rows)
    {
        foreach ($rows as $row) {
            if ($this->isExpiredRow($row)) {
                return $row;
            }
        }

        return null;
    }

    protected function findBusyRow($rows, $context)
    {
        if (sizeof($rows) == 0) {
            return null;
        }

        foreach ($rows as $row) {
            if ($this->isExpiredRow($row)) {
                continue;
            }
            if (isset($row->jumlah) && $row->jumlah > 0) {
                if (isset($row->oleh_id) && $row->oleh_id != $context['user_id']) {
                    return $row;
                }
                if (isset($row->lock_token) && strlen(trim((string)$row->lock_token)) > 0 && $row->lock_token !== $context['lock_token']) {
                    return $row;
                }
            }
        }

        return null;
    }

    protected function summarizeRows($rows, $context)
    {
        $busyRow = $this->findBusyRow($rows, $context);
        $ownedRow = $this->findActiveOwnedRow($rows, $context, true);
        $ownerRow = ($ownedRow != null) ? $ownedRow : $busyRow;
        $data = array(
            "is_locked" => $this->hasActiveLock($rows),
            "is_busy" => $busyRow != null,
            "is_owned_by_me" => $ownedRow != null,
            "owner_user_id" => $ownerRow != null && isset($ownerRow->oleh_id) ? $ownerRow->oleh_id : null,
            "owner_user_name" => $ownerRow != null && isset($ownerRow->oleh_nama) ? $ownerRow->oleh_nama : null,
            "expires_at" => $ownerRow != null && isset($ownerRow->expires_at) ? $ownerRow->expires_at : null,
            "remaining_seconds" => $ownerRow != null && isset($ownerRow->expires_at) ? $this->remainingSeconds($ownerRow->expires_at) : null,
            "rows" => $this->mapRows($rows),
        );
        return $data;
    }

    protected function buildBusyData($row)
    {
        return array(
            "lock_id" => isset($row->id) ? $row->id : null,
            "owner_user_id" => isset($row->oleh_id) ? $row->oleh_id : null,
            "owner_user_name" => isset($row->oleh_nama) ? $row->oleh_nama : null,
            "expires_at" => isset($row->expires_at) ? $row->expires_at : null,
            "lock_token" => isset($row->lock_token) ? $row->lock_token : null,
        );
    }

    protected function buildRowData($row, $override = array())
    {
        $data = array(
            "lock_id" => isset($row->id) ? $row->id : null,
            "lock_token" => isset($override['lock_token']) ? $override['lock_token'] : (isset($row->lock_token) ? $row->lock_token : null),
            "modul" => isset($override['modul']) ? $override['modul'] : (isset($row->modul) ? $row->modul : null),
            "transaksi_id" => isset($override['transaksi_id']) ? $override['transaksi_id'] : (isset($row->transaksi_id) ? $row->transaksi_id : null),
            "user_id" => isset($override['oleh_id']) ? $override['oleh_id'] : (isset($row->oleh_id) ? $row->oleh_id : null),
            "user_name" => isset($override['oleh_nama']) ? $override['oleh_nama'] : (isset($row->oleh_nama) ? $row->oleh_nama : null),
            "cabang_id" => isset($override['cabang_id']) ? $override['cabang_id'] : (isset($row->cabang_id) ? $row->cabang_id : null),
            "gudang_id" => isset($override['gudang_id']) ? $override['gudang_id'] : (isset($row->gudang_id) ? $row->gudang_id : null),
            "jenis" => isset($override['jenis']) ? $override['jenis'] : (isset($row->jenis) ? $row->jenis : null),
            "jenis_locker" => isset($override['jenis_locker']) ? $override['jenis_locker'] : (isset($row->jenis_locker) ? $row->jenis_locker : null),
            "state" => isset($override['state']) ? $override['state'] : (isset($row->state) ? $row->state : null),
            "locked_at" => isset($override['locked_at']) ? $override['locked_at'] : (isset($row->locked_at) ? $row->locked_at : null),
            "expires_at" => isset($override['expires_at']) ? $override['expires_at'] : (isset($row->expires_at) ? $row->expires_at : null),
            "released_at" => isset($override['released_at']) ? $override['released_at'] : (isset($row->released_at) ? $row->released_at : null),
            "released_by" => isset($override['released_by']) ? $override['released_by'] : (isset($row->released_by) ? $row->released_by : null),
            "heartbeat_at" => isset($override['heartbeat_at']) ? $override['heartbeat_at'] : (isset($row->heartbeat_at) ? $row->heartbeat_at : null),
            "jumlah" => isset($override['jumlah']) ? $override['jumlah'] : (isset($row->jumlah) ? $row->jumlah : 0),
        );
        return $data;
    }

    protected function mapRows($rows)
    {
        $result = array();
        foreach ($rows as $row) {
            $result[] = $this->buildRowData($row);
        }
        return $result;
    }

    protected function isExpiredRow($row)
    {
        if (!isset($row->expires_at) || strlen(trim((string)$row->expires_at)) == 0) {
            return false;
        }
        return strtotime($row->expires_at) < strtotime($this->now());
    }

    protected function isActiveQuantityRow($row)
    {
        return isset($row->jumlah) && ($row->jumlah > 0);
    }

    protected function hasActiveLock($rows)
    {
        foreach ($rows as $row) {
            if ($this->isActiveQuantityRow($row) && !$this->isExpiredRow($row)) {
                return true;
            }
        }

        return false;
    }

    protected function remainingSeconds($expiresAt)
    {
        $diff = strtotime($expiresAt) - strtotime($this->now());
        return $diff > 0 ? $diff : 0;
    }

    protected function normalizeParams($params)
    {
        $context = array(
            "modul" => isset($params['modul']) ? $params['modul'] : "",
            "transaksi_id" => isset($params['transaksi_id']) ? $params['transaksi_id'] : "",
            "transaksi_jenis" => isset($params['transaksi_jenis']) ? $params['transaksi_jenis'] : "",
            "user_id" => isset($params['user_id']) ? $params['user_id'] : 0,
            "user_name" => isset($params['user_name']) ? $params['user_name'] : "",
            "cabang_id" => isset($params['cabang_id']) ? $params['cabang_id'] : 0,
            "gudang_id" => isset($params['gudang_id']) ? $params['gudang_id'] : 0,
            "jenis" => isset($params['jenis']) ? $params['jenis'] : $this->defaultJenis,
            "jenis_locker" => isset($params['jenis_locker']) ? $params['jenis_locker'] : $this->defaultJenisLocker,
            "ttl" => isset($params['ttl']) && intval($params['ttl']) > 0 ? intval($params['ttl']) : $this->defaultTtl,
            "lock_token" => isset($params['lock_token']) && strlen(trim($params['lock_token'])) > 0 ? trim($params['lock_token']) : "",
            "reason" => isset($params['reason']) ? $params['reason'] : "",
            "meta" => isset($params['meta']) && is_array($params['meta']) ? $params['meta'] : array(),
            "nama" => isset($params['nama']) ? $params['nama'] : "",
            "persediaan" => isset($params['persediaan']) ? $params['persediaan'] : 0,
            "satuan" => isset($params['satuan']) ? $params['satuan'] : "",
            "nilai" => isset($params['nilai']) ? $params['nilai'] : 0,
            "nomer" => isset($params['nomer']) ? $params['nomer'] : "",
            "transaksi_no" => isset($params['transaksi_no']) ? $params['transaksi_no'] : "",
            "owner_session_id" => isset($params['owner_session_id']) ? $params['owner_session_id'] : (function_exists("session_id") ? session_id() : ""),
            "owner_ip" => isset($params['owner_ip']) ? $params['owner_ip'] : $this->clientIp(),
        );

        if ($context['transaksi_jenis'] == "") {
            $context['transaksi_jenis'] = $context['modul'];
        }

        return $context;
    }

    protected function validateRequired($context, $requiredFields)
    {
        foreach ($requiredFields as $field) {
            if (!isset($context[$field])) {
                return $this->makeResponse(false, "LOCK_INVALID_PARAM", "Parameter lock tidak lengkap: " . $field, array());
            }
            if (is_string($context[$field]) && strlen(trim($context[$field])) == 0) {
                return $this->makeResponse(false, "LOCK_INVALID_PARAM", "Parameter lock tidak lengkap: " . $field, array());
            }
            if (!is_string($context[$field]) && !is_numeric($context[$field])) {
                return $this->makeResponse(false, "LOCK_INVALID_PARAM", "Parameter lock tidak lengkap: " . $field, array());
            }
            if ($field === "user_id" && intval($context[$field]) <= 0) {
                return $this->makeResponse(false, "LOCK_INVALID_PARAM", "Parameter lock tidak valid: " . $field, array());
            }
        }

        return true;
    }

    protected function makeResponse($success, $code, $message, $data = array())
    {
        return array(
            "success" => $success,
            "code" => $code,
            "message" => $message,
            "data" => $data,
        );
    }

    protected function now()
    {
        return date("Y-m-d H:i:s");
    }

    protected function addSeconds($datetime, $seconds)
    {
        return date("Y-m-d H:i:s", strtotime($datetime) + intval($seconds));
    }

    protected function makeToken()
    {
        return md5(uniqid("lt_", true) . mt_rand());
    }

    protected function clientIp()
    {
        if (isset($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }

        return "";
    }

    protected function columnExists($columnName)
    {
        static $cached = null;
        if (!is_array($cached)) {
            $cached = array();
            $fields = $this->CI->db->list_fields($this->tableName);
            if (is_array($fields)) {
                foreach ($fields as $field) {
                    $cached[$field] = true;
                }
            }
        }

        return isset($cached[$columnName]);
    }
}
