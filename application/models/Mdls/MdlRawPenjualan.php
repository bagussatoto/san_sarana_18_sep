<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Domain Read-Model: MdlRawPenjualan
 * 
 * Bounded Context: Penjualan
 * Bertanggung jawab mengekstrak data proyeksi penjualan (Sales Order)
 * dari tabel operasional penjualan_transaksi dengan prinsip DDD,
 * efisiensi Index Range Scan, dan strategi Hybrid Caching untuk data historis.
 */
class MdlRawPenjualan extends CI_Model
{
    protected $tbl_transaksi = "penjualan_transaksi";
    protected $tbl_employee  = "per_employee";

    // Jenis transaksi yang sah sebagai sales order (lokal, pre-order, ekspor, projek)
    protected $order_types = array(
        '5822so',
        '5822spo',
        '382so',
        '588so'
    );

    // Jenis transaksi yang sah sebagai realisasi penjualan (delivery / faktur)
    protected $realized_types = array(
        '5822spd'
    );

    public function __construct()
    {
        parent::__construct();
        // Inisialisasi cache driver CI (file cache sebagai default)
        $this->load->driver('cache', array('adapter' => 'file', 'backup' => 'dummy'));
    }

    /**
     * Mengambil summary bulanan per salesman untuk tahun berjalan dan tahun pembanding.
     * Menggunakan strategi Hybrid Caching:
     * - Tahun lampau (sudah closing) diambil dari cache jika tersedia (TTL 30 hari).
     * - Tahun berjalan selalu dieksekusi real-time dari database.
     *
     * @param int $year_now Tahun berjalan (misal 2026)
     * @param int|null $year_prev Tahun pembanding (misal 2025). Jika null, otomatis $year_now - 1
     * @param string $gudang_status Filter gudang_status_id ('all', '0', 'not_0')
     * @return array Array data proyeksi standar
     */
    public function callSummarySellerSoBulananHybrid($year_now, $year_prev = null, $gudang_status = 'all')
    {
        if ($year_prev === null) {
            $year_prev = (int)$year_now - 1;
        }

        $records_prev = array();
        $records_now  = array();

        // 1. Data Tahun Lampau (Closed / Historical) -> Cek Cache terisolasi per gudang_status
        $cache_key = "so_seller_summary_" . $year_prev . "_" . $gudang_status;
        $cached_data = $this->cache->get($cache_key);

        if ($cached_data !== false && is_array($cached_data)) {
            $records_prev = $cached_data;
        } else {
            $records_prev = $this->callSummarySellerSoBulananSingleYear($year_prev, $gudang_status);
            // Simpan ke cache selama 30 hari (2592000 detik)
            $this->cache->save($cache_key, $records_prev, 2592000);
        }

        // 2. Data Tahun Berjalan -> Selalu Real-Time
        $records_now = $this->callSummarySellerSoBulananSingleYear($year_now, $gudang_status);

        // Gabungkan kedua tahun
        return array_merge($records_prev, $records_now);
    }

    /**
     * Query data proyeksi per salesman untuk satu tahun tertentu.
     * Menggunakan Index Range Scan pada kolom dtime (tanpa fungsi YEAR()) agar indeks terpakai optimal.
     *
     * @param int $year
     * @param string $gudang_status Filter gudang_status_id ('all', '0', 'not_0')
     * @return array
     */
    public function callSummarySellerSoBulananSingleYear($year, $gudang_status = 'all')
    {
        $date_start = $year . "-01-01 00:00:00";
        $date_end   = $year . "-12-31 23:59:59";

        $koloms = array(
            "COALESCE(NULLIF(t.seller_id, 0), e_dc.id, NULLIF(t.oleh_id, 0), 0) AS seller_id",
            "COALESCE(NULLIF(t.seller_nama, ''), e_dc.nama, NULLIF(t.oleh_nama, ''), 'Unknown') AS seller_nama",
            "YEAR(t.dtime) AS thn",
            "LPAD(MONTH(t.dtime), 2, '0') AS bln",
            "COALESCE(SUM(t.transaksi_netto), 0) AS sum_netto",
            "COALESCE(SUM(t.kredit), 0) AS sum_kredit",
            "COALESCE(SUM(t.debet + t.batal + t.return), 0) AS sum_debet",
            "0 AS sum_qty_kredit",
            "0 AS sum_qty_debet",
            "0 AS sum_hpp"
        );

        $this->db->select(implode(", ", $koloms), FALSE);
        $this->db->from($this->tbl_transaksi . " AS t");
        $this->db->join($this->tbl_employee . " AS e_dc", "e_dc.dc_id = t.seller_dc_id AND t.seller_dc_id > 0", "left");

        // Index Range Scan pada dtime
        $this->db->where("t.dtime >=", $date_start);
        $this->db->where("t.dtime <=", $date_end);

        // Filter validitas transaksi
        $this->db->where("t.status", "1");
        $this->db->where("t.trash", "0");
        $this->db->where_in("t.jenis", $this->order_types);

        // Filter gudang_status_id
        if ($gudang_status === '0') {
            $this->db->where("t.gudang_status_id", 0);
        } elseif ($gudang_status === 'not_0') {
            $this->db->where("t.gudang_status_id >", 0);
        }

        // Pengelompokan data per salesman dan per bulan
        $this->db->group_by(array(
            "COALESCE(NULLIF(t.seller_id, 0), e_dc.id, NULLIF(t.oleh_id, 0), 0)",
            "COALESCE(NULLIF(t.seller_nama, ''), e_dc.nama, NULLIF(t.oleh_nama, ''), 'Unknown')",
            "YEAR(t.dtime)",
            "MONTH(t.dtime)"
        ));

        $query = $this->db->get();
        $results = array();

        if ($query && $query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $results[] = array(
                    "subjek_id"      => (string)$row->seller_id,
                    "subjek_nama"    => (string)$row->seller_nama,
                    "thn"            => (string)$row->thn,
                    "bln"            => (string)$row->bln,
                    "sum_netto"      => (float)$row->sum_netto,
                    "sum_kredit"     => (float)$row->sum_kredit,
                    "sum_debet"      => (float)$row->sum_debet,
                    "sum_qty_kredit" => (float)$row->sum_qty_kredit,
                    "sum_qty_debet"  => (float)$row->sum_qty_debet,
                    "sum_hpp"        => (float)$row->sum_hpp,
                );
            }
        }

        return $results;
    }

    /**
     * Menghapus cache historis secara manual jika ada penyesuaian data lama.
     *
     * @param int $year
     * @param string|null $gudang_status
     * @return bool
     */
    public function clearYearCache($year, $gudang_status = null)
    {
        if ($gudang_status !== null) {
            return $this->cache->delete("so_seller_summary_" . $year . "_" . $gudang_status);
        }
        $this->cache->delete("so_seller_summary_" . $year . "_all");
        $this->cache->delete("so_seller_summary_" . $year . "_0");
        $this->cache->delete("so_seller_summary_" . $year . "_not_0");
        return $this->cache->delete("so_seller_summary_" . $year);
    }

    /**
     * Mengambil rincian transaksi Sales Order pembentuk angka untuk seller tertentu dalam rentang bulan tertentu.
     *
     * @param int|string $sellerId ID Salesman (per_employee.id)
     * @param int $year Tahun transaksi
     * @param int $monthStart Bulan mulai (1 s/d 12)
     * @param int|null $monthEnd Bulan akhir (jika null, sama dengan $monthStart)
     * @param string $gudang_status Filter gudang_status_id ('all', '0', 'not_0')
     * @return array Daftar baris transaksi
     */
    public function callDetailSellerSoTransactions($sellerId, $year, $monthStart = 1, $monthEnd = null, $gudang_status = 'all')
    {
        if ($monthEnd === null) {
            $monthEnd = $monthStart;
        }

        $date_start = sprintf("%04d-%02d-01 00:00:00", $year, (int)$monthStart);
        $date_end   = date("Y-m-t 23:59:59", strtotime(sprintf("%04d-%02d-01", $year, (int)$monthEnd)));

        $koloms = array(
            "t.id",
            "t.dtime",
            "t.nomer",
            "t.jenis",
            "t.customers_id",
            "t.customers_nama",
            "t.oleh_id",
            "t.oleh_nama",
            "t.seller_dc_id",
            "t.seller_dc_nama",
            "t.gudang_status_id",
            "t.gudang_status_nama",
            "t.transaksi_netto",
            "t.keterangan",
            "COALESCE(NULLIF(t.seller_id, 0), e_dc.id, NULLIF(t.oleh_id, 0), 0) AS seller_id",
            "COALESCE(NULLIF(t.seller_nama, ''), e_dc.nama, NULLIF(t.oleh_nama, ''), 'Unknown') AS seller_nama"
        );

        $this->db->select(implode(", ", $koloms), FALSE);
        $this->db->from($this->tbl_transaksi . " AS t");
        $this->db->join($this->tbl_employee . " AS e_dc", "e_dc.dc_id = t.seller_dc_id AND t.seller_dc_id > 0", "left");

        // Index Range Scan pada dtime
        $this->db->where("t.dtime >=", $date_start);
        $this->db->where("t.dtime <=", $date_end);

        $this->db->where("t.status", "1");
        $this->db->where("t.trash", "0");
        $this->db->where_in("t.jenis", $this->order_types);
        $this->db->where("COALESCE(NULLIF(t.seller_id, 0), e_dc.id, NULLIF(t.oleh_id, 0), 0) =", (int)$sellerId);

        // Filter gudang_status_id
        if ($gudang_status === '0') {
            $this->db->where("t.gudang_status_id", 0);
        } elseif ($gudang_status === 'not_0') {
            $this->db->where("t.gudang_status_id >", 0);
        }

        $this->db->order_by("t.dtime", "ASC");

        $query = $this->db->get();
        return ($query && $query->num_rows() > 0) ? $query->result_array() : array();
    }

    /**
     * Mengambil summary bulanan realisasi penjualan per salesman untuk tahun berjalan dan tahun pembanding.
     * Menggunakan strategi Hybrid Caching:
     * - Tahun lampau (sudah closing) diambil dari cache jika tersedia (TTL 30 hari).
     * - Tahun berjalan selalu dieksekusi real-time dari database.
     *
     * @param int $year_now Tahun berjalan (misal 2026)
     * @param int|null $year_prev Tahun pembanding (misal 2025). Jika null, otomatis $year_now - 1
     * @param string $gudang_status Filter gudang_status_id ('all', '0', 'not_0')
     * @return array Array data matriks penjualan standar
     */
    public function callSummarySellerPenjualanBulananHybrid($year_now, $year_prev = null, $gudang_status = 'all')
    {
        if ($year_prev === null) {
            $year_prev = (int)$year_now - 1;
        }

        $records_prev = array();
        $records_now  = array();

        // 1. Data Tahun Lampau (Closed / Historical) -> Cek Cache terisolasi per gudang_status
        $cache_key = "sales_seller_summary_" . $year_prev . "_" . $gudang_status;
        $cached_data = $this->cache->get($cache_key);

        if ($cached_data !== false && is_array($cached_data)) {
            $records_prev = $cached_data;
        } else {
            $records_prev = $this->callSummarySellerPenjualanBulananSingleYear($year_prev, $gudang_status);
            // Simpan ke cache selama 30 hari (2592000 detik)
            $this->cache->save($cache_key, $records_prev, 2592000);
        }

        // 2. Data Tahun Berjalan -> Selalu Real-Time
        $records_now = $this->callSummarySellerPenjualanBulananSingleYear($year_now, $gudang_status);

        // Gabungkan kedua tahun
        return array_merge($records_prev, $records_now);
    }

    /**
     * Query data realisasi penjualan per salesman untuk satu tahun tertentu.
     * Menggunakan Index Range Scan pada kolom dtime dan rumus Netto = transaksi_netto - batal - return.
     *
     * @param int $year
     * @param string $gudang_status Filter gudang_status_id ('all', '0', 'not_0')
     * @return array
     */
    public function callSummarySellerPenjualanBulananSingleYear($year, $gudang_status = 'all')
    {
        $date_start = $year . "-01-01 00:00:00";
        $date_end   = $year . "-12-31 23:59:59";

        $koloms = array(
            "COALESCE(NULLIF(t.seller_id, 0), e_dc.id, NULLIF(t.oleh_id, 0), 0) AS seller_id",
            "COALESCE(NULLIF(t.seller_nama, ''), e_dc.nama, NULLIF(t.oleh_nama, ''), 'Unknown') AS seller_nama",
            "YEAR(t.dtime) AS thn",
            "LPAD(MONTH(t.dtime), 2, '0') AS bln",
            "COALESCE(SUM(t.transaksi_netto - COALESCE(t.batal, 0) - COALESCE(t.return, 0)), 0) AS sum_netto",
            "COALESCE(SUM(t.kredit), 0) AS sum_kredit",
            "COALESCE(SUM(t.debet + t.batal + t.return), 0) AS sum_debet",
            "0 AS sum_qty_kredit",
            "0 AS sum_qty_debet",
            "0 AS sum_hpp"
        );

        $this->db->select(implode(", ", $koloms), FALSE);
        $this->db->from($this->tbl_transaksi . " AS t");
        $this->db->join($this->tbl_employee . " AS e_dc", "e_dc.dc_id = t.seller_dc_id AND t.seller_dc_id > 0", "left");

        // Index Range Scan pada dtime
        $this->db->where("t.dtime >=", $date_start);
        $this->db->where("t.dtime <=", $date_end);

        // Filter validitas transaksi
        $this->db->where("t.status", "1");
        $this->db->where("t.trash", "0");
        $this->db->where_in("t.jenis", $this->realized_types);

        // Filter gudang_status_id
        if ($gudang_status === '0') {
            $this->db->where("t.gudang_status_id", 0);
        } elseif ($gudang_status === 'not_0') {
            $this->db->where("t.gudang_status_id >", 0);
        }

        // Pengelompokan data per salesman dan per bulan
        $this->db->group_by(array(
            "COALESCE(NULLIF(t.seller_id, 0), e_dc.id, NULLIF(t.oleh_id, 0), 0)",
            "COALESCE(NULLIF(t.seller_nama, ''), e_dc.nama, NULLIF(t.oleh_nama, ''), 'Unknown')",
            "YEAR(t.dtime)",
            "MONTH(t.dtime)"
        ));

        $query = $this->db->get();
        $results = array();

        if ($query && $query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $results[] = array(
                    "subjek_id"      => (string)$row->seller_id,
                    "subjek_nama"    => (string)$row->seller_nama,
                    "thn"            => (string)$row->thn,
                    "bln"            => (string)$row->bln,
                    "sum_netto"      => (float)$row->sum_netto,
                    "sum_kredit"     => (float)$row->sum_kredit,
                    "sum_debet"      => (float)$row->sum_debet,
                    "sum_qty_kredit" => (float)$row->sum_qty_kredit,
                    "sum_qty_debet"  => (float)$row->sum_qty_debet,
                    "sum_hpp"        => (float)$row->sum_hpp,
                );
            }
        }

        return $results;
    }

    /**
     * Menghapus cache penjualan historis secara manual jika ada sinkronisasi ulang.
     *
     * @param int $year
     * @param string|null $gudang_status
     * @return bool
     */
    public function clearYearSalesCache($year, $gudang_status = null)
    {
        if ($gudang_status !== null) {
            return $this->cache->delete("sales_seller_summary_" . $year . "_" . $gudang_status);
        }
        $this->cache->delete("sales_seller_summary_" . $year . "_all");
        $this->cache->delete("sales_seller_summary_" . $year . "_0");
        $this->cache->delete("sales_seller_summary_" . $year . "_not_0");
        return $this->cache->delete("sales_seller_summary_" . $year);
    }

    /**
     * Mengambil rincian transaksi Realisasi Penjualan (5822spd) pembentuk angka untuk seller tertentu dalam rentang bulan tertentu.
     *
     * @param int|string $sellerId ID Salesman (per_employee.id)
     * @param int $year Tahun transaksi
     * @param int $monthStart Bulan mulai (1 s/d 12)
     * @param int|null $monthEnd Bulan akhir (jika null, sama dengan $monthStart)
     * @param string $gudang_status Filter gudang_status_id ('all', '0', 'not_0')
     * @return array Daftar baris transaksi
     */
    public function callDetailSellerPenjualanTransactions($sellerId, $year, $monthStart = 1, $monthEnd = null, $gudang_status = 'all')
    {
        if ($monthEnd === null) {
            $monthEnd = $monthStart;
        }

        $date_start = sprintf("%04d-%02d-01 00:00:00", $year, (int)$monthStart);
        $date_end   = date("Y-m-t 23:59:59", strtotime(sprintf("%04d-%02d-01", $year, (int)$monthEnd)));

        $koloms = array(
            "t.id",
            "t.dtime",
            "t.nomer",
            "t.jenis",
            "t.customers_id",
            "t.customers_nama",
            "t.oleh_id",
            "t.oleh_nama",
            "t.seller_dc_id",
            "t.seller_dc_nama",
            "t.gudang_status_id",
            "t.gudang_status_nama",
            "(t.transaksi_netto - COALESCE(t.batal, 0) - COALESCE(t.return, 0)) AS transaksi_netto",
            "t.keterangan",
            "COALESCE(NULLIF(t.seller_id, 0), e_dc.id, NULLIF(t.oleh_id, 0), 0) AS seller_id",
            "COALESCE(NULLIF(t.seller_nama, ''), e_dc.nama, NULLIF(t.oleh_nama, ''), 'Unknown') AS seller_nama"
        );

        $this->db->select(implode(", ", $koloms), FALSE);
        $this->db->from($this->tbl_transaksi . " AS t");
        $this->db->join($this->tbl_employee . " AS e_dc", "e_dc.dc_id = t.seller_dc_id AND t.seller_dc_id > 0", "left");

        // Index Range Scan pada dtime
        $this->db->where("t.dtime >=", $date_start);
        $this->db->where("t.dtime <=", $date_end);

        $this->db->where("t.status", "1");
        $this->db->where("t.trash", "0");
        $this->db->where_in("t.jenis", $this->realized_types);
        $this->db->where("COALESCE(NULLIF(t.seller_id, 0), e_dc.id, NULLIF(t.oleh_id, 0), 0) =", (int)$sellerId);

        // Filter gudang_status_id
        if ($gudang_status === '0') {
            $this->db->where("t.gudang_status_id", 0);
        } elseif ($gudang_status === 'not_0') {
            $this->db->where("t.gudang_status_id >", 0);
        }

        $this->db->order_by("t.dtime", "ASC");

        $query = $this->db->get();
        return ($query && $query->num_rows() > 0) ? $query->result_array() : array();
    }
}