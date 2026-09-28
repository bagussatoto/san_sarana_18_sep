<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Job state storage untuk sinkronisasi asynchronous.
 * Dipakai oleh statik/Data::sync_job_start, sync_job_process, sync_job_status.
 */
class MdlSyncJob extends CI_Model
{
    private $tableName = "sync_jobs";

    public function __construct()
    {
        parent::__construct();
    }

    public function ensureTable()
    {
        if (!$this->db->table_exists($this->tableName)) {
            $sql = "CREATE TABLE IF NOT EXISTS `{$this->tableName}` (
                `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `mdl_name` VARCHAR(128) NOT NULL,
                `toko_id` BIGINT NOT NULL DEFAULT 0,
                `cabang_id` BIGINT NOT NULL DEFAULT 0,
                `status` VARCHAR(32) NOT NULL DEFAULT 'pending',
                `payload` LONGTEXT NULL,
                `stage_token` VARCHAR(128) NOT NULL DEFAULT '',
                `total_items` INT NOT NULL DEFAULT 0,
                `processed_items` INT NOT NULL DEFAULT 0,
                `inserted_items` INT NOT NULL DEFAULT 0,
                `updated_items` INT NOT NULL DEFAULT 0,
                `skipped_items` INT NOT NULL DEFAULT 0,
                `failed_items` INT NOT NULL DEFAULT 0,
                `message` TEXT NULL,
                `created_by` BIGINT NOT NULL DEFAULT 0,
                `created_at` DATETIME NOT NULL,
                `updated_at` DATETIME NOT NULL,
                `finished_at` DATETIME NULL,
                PRIMARY KEY (`id`),
                KEY `idx_sync_jobs_active` (`mdl_name`,`toko_id`,`cabang_id`,`status`),
                KEY `idx_sync_jobs_status_updated` (`status`,`updated_at`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            $this->runQuerySafe($sql);
            if (!$this->db->table_exists($this->tableName)) {
                return false;
            }
            return true;
        }

        // Hardening: tambahkan kolom jika tabel lama belum lengkap.
        $existing = $this->db->list_fields($this->tableName);
        $existingMap = array();
        if (is_array($existing)) {
            foreach ($existing as $col) {
                $existingMap[$col] = 1;
            }
        }

        $addColumns = array(
            "mdl_name" => "ALTER TABLE `{$this->tableName}` ADD COLUMN `mdl_name` VARCHAR(128) NOT NULL DEFAULT ''",
            "toko_id" => "ALTER TABLE `{$this->tableName}` ADD COLUMN `toko_id` BIGINT NOT NULL DEFAULT 0",
            "cabang_id" => "ALTER TABLE `{$this->tableName}` ADD COLUMN `cabang_id` BIGINT NOT NULL DEFAULT 0",
            "status" => "ALTER TABLE `{$this->tableName}` ADD COLUMN `status` VARCHAR(32) NOT NULL DEFAULT 'pending'",
            "payload" => "ALTER TABLE `{$this->tableName}` ADD COLUMN `payload` LONGTEXT NULL",
            "stage_token" => "ALTER TABLE `{$this->tableName}` ADD COLUMN `stage_token` VARCHAR(128) NOT NULL DEFAULT ''",
            "total_items" => "ALTER TABLE `{$this->tableName}` ADD COLUMN `total_items` INT NOT NULL DEFAULT 0",
            "processed_items" => "ALTER TABLE `{$this->tableName}` ADD COLUMN `processed_items` INT NOT NULL DEFAULT 0",
            "inserted_items" => "ALTER TABLE `{$this->tableName}` ADD COLUMN `inserted_items` INT NOT NULL DEFAULT 0",
            "updated_items" => "ALTER TABLE `{$this->tableName}` ADD COLUMN `updated_items` INT NOT NULL DEFAULT 0",
            "skipped_items" => "ALTER TABLE `{$this->tableName}` ADD COLUMN `skipped_items` INT NOT NULL DEFAULT 0",
            "failed_items" => "ALTER TABLE `{$this->tableName}` ADD COLUMN `failed_items` INT NOT NULL DEFAULT 0",
            "message" => "ALTER TABLE `{$this->tableName}` ADD COLUMN `message` TEXT NULL",
            "created_by" => "ALTER TABLE `{$this->tableName}` ADD COLUMN `created_by` BIGINT NOT NULL DEFAULT 0",
            "created_at" => "ALTER TABLE `{$this->tableName}` ADD COLUMN `created_at` DATETIME NULL",
            "updated_at" => "ALTER TABLE `{$this->tableName}` ADD COLUMN `updated_at` DATETIME NULL",
            "finished_at" => "ALTER TABLE `{$this->tableName}` ADD COLUMN `finished_at` DATETIME NULL",
        );

        foreach ($addColumns as $column => $sql) {
            if (!isset($existingMap[$column])) {
                $this->runQuerySafe($sql);
            }
        }
        return true;
    }

    public function getActiveJob($mdlName, $tokoId, $cabangId)
    {
        if (!$this->db->table_exists($this->tableName)) {
            return null;
        }
        $this->db->from($this->tableName);
        $this->db->where("mdl_name", (string)$mdlName);
        $this->db->where("toko_id", (int)$tokoId);
        $this->db->where("cabang_id", (int)$cabangId);
        $this->db->where_in("status", array("pending", "running"));
        $this->db->order_by("id", "DESC");
        $this->db->limit(1);
        return $this->db->get()->row();
    }

    public function createJob($data)
    {
        if (!$this->db->table_exists($this->tableName)) {
            return 0;
        }
        $payload = is_array($data) ? $data : array();
        $now = $this->now();

        if (!isset($payload["created_at"]) || $payload["created_at"] == "") {
            $payload["created_at"] = $now;
        }
        if (!isset($payload["updated_at"]) || $payload["updated_at"] == "") {
            $payload["updated_at"] = $now;
        }
        if (!array_key_exists("finished_at", $payload)) {
            $payload["finished_at"] = null;
        }

        $payload = $this->sanitizePayload($payload);
        $this->runInsertSafe($this->tableName, $payload);

        $err = $this->db->error();
        if (isset($err["code"]) && (int)$err["code"] !== 0) {
            return 0;
        }
        return (int)$this->db->insert_id();
    }

    public function getById($id)
    {
        $id = is_numeric($id) ? (int)$id : 0;
        if ($id < 1) {
            return null;
        }
        if (!$this->db->table_exists($this->tableName)) {
            return null;
        }

        $prevDebug = $this->disableDbDebug();
        $row = $this->db->where("id", $id)->get($this->tableName)->row();
        $this->restoreDbDebug($prevDebug);
        return $row;
    }

    public function updateJob($id, $data)
    {
        $id = is_numeric($id) ? (int)$id : 0;
        if ($id < 1 || !is_array($data) || count($data) < 1) {
            return false;
        }
        if (!$this->db->table_exists($this->tableName)) {
            return false;
        }

        $payload = $data;
        $payload["updated_at"] = $this->now();
        $payload = $this->sanitizePayload($payload);

        $this->runUpdateSafe($this->tableName, array("id" => $id), $payload);
        $err = $this->db->error();
        if (isset($err["code"]) && (int)$err["code"] !== 0) {
            return false;
        }
        return true;
    }

    private function sanitizePayload($data)
    {
        $data = is_array($data) ? $data : array();
        $allowed = array(
            "mdl_name", "toko_id", "cabang_id", "status", "payload", "stage_token",
            "total_items", "processed_items", "inserted_items", "updated_items",
            "skipped_items", "failed_items", "message", "created_by",
            "created_at", "updated_at", "finished_at",
        );

        $out = array();
        foreach ($allowed as $key) {
            if (array_key_exists($key, $data)) {
                $out[$key] = $data[$key];
            }
        }

        return $out;
    }

    private function now()
    {
        if (function_exists("dtimeNow")) {
            return dtimeNow();
        }
        return date("Y-m-d H:i:s");
    }

    private function disableDbDebug()
    {
        if (!isset($this->db) || !is_object($this->db) || !property_exists($this->db, "db_debug")) {
            return null;
        }
        $prev = $this->db->db_debug;
        $this->db->db_debug = false;
        return $prev;
    }

    private function restoreDbDebug($prev)
    {
        if ($prev === null) {
            return;
        }
        if (!isset($this->db) || !is_object($this->db) || !property_exists($this->db, "db_debug")) {
            return;
        }
        $this->db->db_debug = $prev;
    }

    private function runQuerySafe($sql)
    {
        $prevDebug = $this->disableDbDebug();
        $result = $this->db->query($sql);
        $this->restoreDbDebug($prevDebug);
        return $result;
    }

    private function runInsertSafe($table, $data)
    {
        $prevDebug = $this->disableDbDebug();
        $result = $this->db->insert($table, $data);
        $this->restoreDbDebug($prevDebug);
        return $result;
    }

    private function runUpdateSafe($table, $where, $data)
    {
        $prevDebug = $this->disableDbDebug();
        if (is_array($where) && count($where) > 0) {
            foreach ($where as $k => $v) {
                $this->db->where($k, $v);
            }
        }
        $result = $this->db->update($table, $data);
        $this->restoreDbDebug($prevDebug);
        return $result;
    }
}
