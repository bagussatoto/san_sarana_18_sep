<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// START OF COMPLETE REPEATED LOGIC
class CronTray extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Lepas session lock seketika agar request non-blocking dan tidak saling tunggu di browser
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }

        // Pengaturan execution timeout untuk proses background / browser
        @set_time_limit(0);
        @ignore_user_abort(true);

        // Pengaman akses: izinkan dari CLI atau URL dengan parameter secure_key
        if (!is_cli() && $this->input->get('secure_key') !== 'cron_tray_cache_123') {
            // Uncomment baris berikut jika ingin proteksi penuh via key
            // show_error('Access Denied');
        }
    }

    public function generate_cache($clientParam = null)
    {
        // ---------------------------------------------------------
        // Identifikasi Client / Project & Format Header Log
        // ---------------------------------------------------------
        $clientName = 'SAN_BRANCH';
        $docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : '/var/www';
        $basePath = rtrim(str_replace('\\', '/', BASEPATH), '/');
        preg_match('#/([^/]+)/application#', $basePath, $matches);
        if (isset($matches[1])) {
            $clientName = strtoupper($matches[1]);
        }
        $timestamp = date('Y-m-d H:i:s');
        echo "[$timestamp] CronTray::generate_cache() -> Client: $clientName (Multi-Table Aggregator)\n";

        // ---------------------------------------------------------
        // Advisory Mutex Lock (Non-Blocking)
        // Mencegah 2 proses cron berjalan paralel di server yang sama.
        // ---------------------------------------------------------
        $lockResult = $this->db->query("SELECT GET_LOCK('cron_tray_cache_lock', 0) AS got_lock")->row();
        if (!$lockResult || $lockResult->got_lock != 1) {
            echo "[$timestamp] SKIP: Proses cron lain masih berjalan (mutex aktif).\n";
            return;
        }

        $tStart = microtime(true);

        // ---------------------------------------------------------
        // Fase 1: Siapkan Batch ID Baru (Atomik)
        // ---------------------------------------------------------
        $newBatch = time();

        // Gunakan isolasi READ UNCOMMITTED agar tidak terkena row-lock
        // dari transaksi kasir yang sedang berjalan.
        $this->db->query("SET SESSION TRANSACTION ISOLATION LEVEL READ UNCOMMITTED");

        // ---------------------------------------------------------
        // Fase 2: Deteksi Dinamis Semua Tabel Transaksi Per Modul
        // ---------------------------------------------------------
        $excludeTables = array(
            'stock_locker_transaksi',
            'sys_cache_tray_transaksi',
            'project_intern_transaksi',
            'e_project_intern_transaksi'
        );

        // Ambil semua tabel yang berakhiran '_transaksi'
        $resTables = $this->db->query("SHOW TABLES LIKE '%_transaksi'")->result_array();
        $modulTransTables = array();
        foreach ($resTables as $row) {
            $tblName = reset($row);
            if (in_array($tblName, $excludeTables)) {
                continue;
            }
            $modulTransTables[] = $tblName;
        }

        // Tambahkan tabel 'transaksi' utama jika ada (fallback untuk modul dasar)
        $hasMainTransaksi = $this->db->query("SHOW TABLES LIKE 'transaksi'")->num_rows();
        if ($hasMainTransaksi > 0 && !in_array('transaksi', $modulTransTables)) {
            $modulTransTables[] = 'transaksi';
        }

        $totalModules = count($modulTransTables);
        echo "  Ditemukan $totalModules tabel transaksi modul.\n";

        // ---------------------------------------------------------
        // Fase 3: Agregasi Transaksi dari Semua Tabel Modul
        // ---------------------------------------------------------
        $totalTrInserted = 0;
        foreach ($modulTransTables as $tblMain) {
            // Tentukan nama tabel detail
            $tblData = ($tblMain === 'transaksi') ? 'transaksi_data' : $tblMain . '_data';

            // Pastikan tabel detail ada
            $hasDataTable = $this->db->query("SHOW TABLES LIKE ?", array($tblData))->num_rows();
            if ($hasDataTable === 0) {
                continue;
            }

            // Deteksi apakah tabel data memiliki kolom valid_qty
            $hasValidQty = false;
            $colsResult = $this->db->query("DESCRIBE `$tblData`")->result();
            foreach ($colsResult as $col) {
                if ($col->Field === 'valid_qty') {
                    $hasValidQty = true;
                    break;
                }
            }

            // Kondisi qty: gunakan valid_qty jika ada, jika tidak cukup sub_step_number > 0
            $qtyCondition = $hasValidQty ? "AND valid_qty > 0" : "";

            $sqlInsert = "
            INSERT INTO sys_cache_tray_transaksi 
                (batch_id, cabang_id, cabang2_id, jenis_master, next_step_num, next_step_code, next_substep_num, jenis_label, oleh_id, qty)
            SELECT 
                $newBatch, t.cabang_id, t.cabang2_id, t.jenis_master, t.next_step_num, t.next_step_code, 
                d.next_substep_num, t.jenis_label, t.oleh_id, COUNT(1) as qty
            FROM (
                SELECT DISTINCT transaksi_id, next_substep_num 
                FROM `$tblData` 
                WHERE trash = '0' AND sub_step_number > 0 $qtyCondition
            ) d
            JOIN `$tblMain` t ON t.id = d.transaksi_id
            WHERE t.status = '1' AND t.trash = '0' AND t.link_id = '0'
            GROUP BY t.cabang_id, t.cabang2_id, t.jenis_master, t.next_step_num, t.next_step_code, 
                     d.next_substep_num, t.jenis_label, t.oleh_id
            ";

            $this->db->query($sqlInsert);
            $affected = $this->db->affected_rows();
            if ($affected > 0) {
                echo "    [TR] $tblMain -> $affected entri.\n";
                $totalTrInserted += $affected;
            }
        }

        // ---------------------------------------------------------
        // Fase 4: Agregasi Due Date (jika tabel ada)
        // ---------------------------------------------------------
        $totalDueDateInserted = 0;
        $hasDueDate = $this->db->query("SHOW TABLES LIKE 'transaksi_due_date'")->num_rows();
        if ($hasDueDate > 0) {
            $sqlDueDate = "
            INSERT INTO sys_cache_tray_duedate (batch_id, cabang_id, dtime, due_date, customers_id)
            SELECT $newBatch, cabang_id, dtime, due_date, customers_id
            FROM transaksi_due_date
            WHERE status = '1' AND trash = '0'
            ";
            $this->db->query($sqlDueDate);
            $totalDueDateInserted = $this->db->affected_rows();
        }

        // ---------------------------------------------------------
        // Fase 5: Agregasi Payment Source Bersih & Teragregasi (Pre-Aggregation)
        // Filter target_jenis dan extern_id cacat (0, 0000, minus) dan GROUP BY per vendor
        // ---------------------------------------------------------
        $totalPayInserted = 0;
        $resPayTables = $this->db->query("SHOW TABLES LIKE '%payment_source%'")->result_array();
        foreach ($resPayTables as $row) {
            $tblPay = reset($row);
            // Lewati tabel log, anti, cache, mutasi
            if (strpos($tblPay, '_log') !== false) { continue; }
            if (strpos($tblPay, 'anti') !== false) { continue; }
            if (strpos($tblPay, 'cache') !== false) { continue; }
            if (strpos($tblPay, 'mutasi') !== false) { continue; }

            // Pastikan tabel memiliki kolom sisa dan cabang_id
            $hasSisa = false;
            $colsPay = $this->db->query("DESCRIBE `$tblPay`")->result();
            foreach ($colsPay as $col) {
                if ($col->Field === 'sisa') {
                    $hasSisa = true;
                    break;
                }
            }
            if (!$hasSisa) { continue; }

            $sqlPayInsert = "
            INSERT INTO sys_cache_tray_payment (batch_id, cabang_id, target_jenis, extern_id, sisa)
            SELECT 
                $newBatch, cabang_id, target_jenis, extern_id, SUM(sisa) as sisa
            FROM `$tblPay`
            WHERE sisa > 1000
              AND target_jenis NOT IN ('0', '0000', '')
              AND CAST(target_jenis AS SIGNED) > 0
              AND extern_id NOT IN ('0', '0000', '')
              AND CAST(extern_id AS SIGNED) > 0
            GROUP BY cabang_id, target_jenis, extern_id
            ";
            $this->db->query($sqlPayInsert);
            $affPay = $this->db->affected_rows();
            if ($affPay > 0) {
                echo "    [PAY] $tblPay -> $affPay baris.\n";
                $totalPayInserted += $affPay;
            }
        }

        // ---------------------------------------------------------
        // Fase 6: Update Active Version Pointer (Atomik < 1 ms)
        // ---------------------------------------------------------
        $dtimeNow = date('Y-m-d H:i:s');
        $this->db->query("
            INSERT INTO sys_cache_tray_version (id, active_batch, updated_at)
            VALUES (1, ?, ?)
            ON DUPLICATE KEY UPDATE active_batch = ?, updated_at = ?
        ", array($newBatch, $dtimeNow, $newBatch, $dtimeNow));

        // ---------------------------------------------------------
        // Fase 7: Cleanup Batch Lama (Chunked Delete Ramah Replica Server)
        // ---------------------------------------------------------
        $cleanTables = array('sys_cache_tray_transaksi', 'sys_cache_tray_duedate', 'sys_cache_tray_payment');
        foreach ($cleanTables as $cTbl) {
            do {
                $this->db->query("DELETE FROM `$cTbl` WHERE batch_id != ? LIMIT 5000", array($newBatch));
                $aff = $this->db->affected_rows();
            } while ($aff >= 5000);
        }

        // ---------------------------------------------------------
        // Fase 8: Release Advisory Mutex Lock
        // ---------------------------------------------------------
        $this->db->query("SELECT RELEASE_LOCK('cron_tray_cache_lock')");

        $tEnd = microtime(true);
        $durasi = round($tEnd - $tStart, 4);

        echo "  SELESAI: Batch=$newBatch | Transaksi=$totalTrInserted | DueDate=$totalDueDateInserted | Payment=$totalPayInserted | Waktu={$durasi}s\n";
    }

    public function verify_cache()
    {
        echo "========== VERIFIKASI CACHE MULTI-TABLE ==========\n";

        // Ambil active batch
        $verRow = $this->db->query("SELECT * FROM sys_cache_tray_version WHERE id = 1")->row();
        $activeBatch = 0;
        if ($verRow && isset($verRow->active_batch)) {
            $activeBatch = (int)$verRow->active_batch;
        }

        $now = date('Y-m-d H:i:s');
        $updatedAt = ($verRow && isset($verRow->updated_at)) ? $verRow->updated_at : '-';
        $selisihDetik = ($verRow && isset($verRow->updated_at)) ? (time() - strtotime($verRow->updated_at)) : 0;

        echo "Waktu Server: $now | Terakhir Update: $updatedAt | Selisih: {$selisihDetik}s\n";
        echo "Active Batch: " . ($activeBatch > 0 ? $activeBatch : "None") . "\n\n";

        $batchCondition = ($activeBatch > 0) ? " AND batch_id = $activeBatch " : "";

        // Ringkasan transaksi per jenis_master
        echo "=== ISI CACHE TRANSAKSI ===\n";
        $qCache = $this->db->query("
            SELECT jenis_master, next_step_num, next_step_code, next_substep_num, jenis_label, SUM(qty) as total_qty
            FROM sys_cache_tray_transaksi 
            WHERE 1=1 $batchCondition
            GROUP BY jenis_master, next_step_num, next_step_code, next_substep_num, jenis_label
            ORDER BY jenis_master ASC, next_step_num ASC
        ")->result();

        $totalCache = 0;
        foreach ($qCache as $r) {
            echo "Jenis {$r->jenis_master} | Step {$r->next_step_num} | Substep {$r->next_substep_num} | Code: {$r->next_step_code} | Label: {$r->jenis_label} => {$r->total_qty}\n";
            $totalCache += $r->total_qty;
        }
        echo "TOTAL CACHE TRANSAKSI: $totalCache\n\n";

        // Ringkasan payment
        echo "=== ISI CACHE PAYMENT ===\n";
        $qPay = $this->db->query("
            SELECT target_jenis, count(distinct extern_id) as total_vendors, count(1) as total_invoices, sum(sisa) as total_sisa
            FROM sys_cache_tray_payment
            WHERE sisa > 1000 $batchCondition
            GROUP BY target_jenis
        ")->result();
        foreach ($qPay as $ap) {
            echo "Jenis {$ap->target_jenis} => Vendors: {$ap->total_vendors} | Total Sisa: " . number_format($ap->total_sisa) . "\n";
        }
        echo "==========================================\n";
    }

    public function setup_tables()
    {
        // Hanya dijalankan sekali saat inisialisasi awal database baru
        $this->db->query("
            CREATE TABLE IF NOT EXISTS sys_cache_tray_version (
                id INT PRIMARY KEY,
                active_batch INT,
                updated_at DATETIME
            ) ENGINE=InnoDB
        ");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS sys_cache_tray_transaksi (
                batch_id INT,
                cabang_id VARCHAR(50),
                cabang2_id VARCHAR(50),
                jenis_master VARCHAR(50),
                next_step_num INT,
                next_step_code VARCHAR(50),
                next_substep_num INT,
                jenis_label VARCHAR(100),
                oleh_id VARCHAR(50),
                qty INT,
                INDEX idx_batch (batch_id),
                INDEX idx_cabang (cabang_id),
                INDEX idx_cabang2 (cabang2_id),
                INDEX idx_jenis_master (jenis_master),
                INDEX idx_batch_cabang_jenis (batch_id, cabang_id, jenis_master)
            ) ENGINE=InnoDB
        ");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS sys_cache_tray_duedate (
                batch_id INT,
                cabang_id VARCHAR(50),
                dtime DATETIME,
                due_date DATETIME,
                customers_id VARCHAR(50),
                INDEX idx_batch (batch_id),
                INDEX idx_cabang (cabang_id),
                INDEX idx_customer (customers_id)
            ) ENGINE=InnoDB
        ");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS sys_cache_tray_payment (
                batch_id INT,
                cabang_id VARCHAR(50),
                target_jenis VARCHAR(50),
                extern_id VARCHAR(50),
                sisa DOUBLE,
                INDEX idx_batch (batch_id),
                INDEX idx_cabang (cabang_id),
                INDEX idx_target_jenis (target_jenis),
                INDEX idx_extern (extern_id)
            ) ENGINE=InnoDB
        ");

        // Adaptasi tabel warisan lama (jika tabel sudah ada tetapi belum memiliki kolom batch_id)
        $this->db->query("ALTER TABLE sys_cache_tray_transaksi ADD COLUMN IF NOT EXISTS batch_id INT FIRST, ADD INDEX IF NOT EXISTS idx_batch (batch_id), ADD INDEX IF NOT EXISTS idx_batch_cabang_jenis (batch_id, cabang_id, jenis_master)");
        $this->db->query("ALTER TABLE sys_cache_tray_duedate ADD COLUMN IF NOT EXISTS batch_id INT FIRST, ADD INDEX IF NOT EXISTS idx_batch (batch_id)");
        $this->db->query("ALTER TABLE sys_cache_tray_payment ADD COLUMN IF NOT EXISTS batch_id INT FIRST, ADD INDEX IF NOT EXISTS idx_batch (batch_id)");

        echo "Struktur tabel cache berhasil diinisialisasi.\n";
    }
}
// END OF COMPLETE REPEATED LOGIC