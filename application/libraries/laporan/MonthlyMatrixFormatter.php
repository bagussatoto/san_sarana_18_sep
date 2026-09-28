<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Service Layer: MonthlyMatrixFormatter
 * 
 * Bertanggung jawab memetakan data mutasi/proyeksi mentah dari Domain Model
 * menjadi struktur matriks 12 bulan (Januari - Desember) berdampingan (Side-by-Side: Tahun Lalu vs Tahun Sekarang),
 * menghitung baris total bawah, serta menyiapkan data siap saji untuk View DataTables.
 * 
 * Bebas dari query SQL / dependensi database.
 * Kompatibel dengan PHP 5.6 s/d PHP 8+.
 */
class MonthlyMatrixFormatter
{
    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    /**
     * Memformat array mentah menjadi matriks 12 bulan side-by-side (2 tahun).
     *
     * @param array $rawRecords Data hasil query dari Domain Model
     * @param array $config Konfigurasi matriks
     * @return array
     */
    public function formatSideBySideMatrix($rawRecords, $config)
    {
        $subjekKey     = isset($config['subjek_key']) ? $config['subjek_key'] : 'subjek_id';
        $subjekNameKey = isset($config['subjek_name_key']) ? $config['subjek_name_key'] : 'subjek_nama';
        $yearNow       = isset($config['year_now']) ? (string)$config['year_now'] : date('Y');
        $yearPrev      = isset($config['year_prev']) ? (string)$config['year_prev'] : (string)($yearNow - 1);

        $sellers     = array();
        $matrix      = array();
        $totalBawah  = array();
        $totalTahun  = array(
            $yearPrev => 0,
            $yearNow  => 0
        );

        // Inisialisasi total 12 bulan untuk masing-masing tahun
        for ($m = 1; $m <= 12; $m++) {
            $blnStr = sprintf("%02d", $m);
            $totalBawah[$yearPrev][$blnStr] = 0;
            $totalBawah[$yearNow][$blnStr]  = 0;
        }

        // 1. Grouping data mentah ke matriks per subjek (salesman), per tahun, dan per bulan
        if (is_array($rawRecords) && count($rawRecords) > 0) {
            foreach ($rawRecords as $row) {
                $item = (array)$row;
                $sId  = isset($item[$subjekKey]) ? (string)$item[$subjekKey] : '0';
                $sNm  = isset($item[$subjekNameKey]) ? (string)$item[$subjekNameKey] : 'Unknown';
                $thn  = isset($item['thn']) ? (string)$item['thn'] : $yearNow;
                $bln  = isset($item['bln']) ? sprintf("%02d", (int)$item['bln']) : '01';

                $netto = isset($item['sum_netto']) ? (float)$item['sum_netto'] : 0;

                // Registrasi nama subjek
                if (!isset($sellers[$sId])) {
                    $sellers[$sId] = $sNm;
                }

                // Inisialisasi sel matriks
                if (!isset($matrix[$sId])) {
                    $matrix[$sId] = array(
                        $yearPrev => array(),
                        $yearNow  => array()
                    );
                }

                $matrix[$sId][$thn][$bln] = $netto;

                // Akumulasi Total Footer per bulan
                if (isset($totalBawah[$thn][$bln])) {
                    $totalBawah[$thn][$bln] += $netto;
                } else {
                    $totalBawah[$thn][$bln] = $netto;
                }

                // Akumulasi Total Tahunan
                if (isset($totalTahun[$thn])) {
                    $totalTahun[$thn] += $netto;
                } else {
                    $totalTahun[$thn] = $netto;
                }
            }
        }

        // Urutkan salesman berdasarkan nama secara alfabetis
        asort($sellers);

        // 2. Kalkulasi Ringkasan Dinamis (Compare Mode & Single-Year Mode)
        $monthsList = $this->getMonthsList();
        $cutOffMonth = isset($config['cut_off_month']) && (int)$config['cut_off_month'] > 0 
            ? (int)$config['cut_off_month'] 
            : ((int)$yearNow == (int)date('Y') ? (int)date('n') : 12);

        // Batasi rentang bulan 1 s/d 12
        $m_ytd = max(1, min(12, $cutOffMonth));
        $m_prev_ytd = $m_ytd - 1; // Jika Januari, bernilai 0

        $m_ytd_str = sprintf("%02d", $m_ytd);
        $m_prev_ytd_str = $m_prev_ytd > 0 ? sprintf("%02d", $m_prev_ytd) : "";

        $nama_bln_ytd = isset($monthsList[$m_ytd_str]) ? $monthsList[$m_ytd_str] : "";
        $nama_bln_prev_ytd = $m_prev_ytd > 0 && isset($monthsList[$m_prev_ytd_str]) ? $monthsList[$m_prev_ytd_str] : "";

        $ytdInfo = array(
            'm_ytd'               => $m_ytd,
            'm_prev_ytd'          => $m_prev_ytd,
            'nama_bln_ytd'        => $nama_bln_ytd,
            'nama_bln_prev_ytd'   => $nama_bln_prev_ytd,
            // Label Single Year
            'label_subtotal_prev' => $m_prev_ytd > 0 ? "SUB TOTAL {$m_prev_ytd} BL<br><small>(UP TO " . strtoupper($nama_bln_prev_ytd) . ")</small>" : "SUB TOTAL 0 BL",
            'label_avg_prev'      => $m_prev_ytd > 0 ? "AVG {$m_prev_ytd} BL<br><small>(UP TO " . strtoupper($nama_bln_prev_ytd) . ")</small>" : "AVG 0 BL",
            'label_subtotal_ytd'  => "SUB TOTAL YTD<br><small>(UP TO " . strtoupper($nama_bln_ytd) . ")</small>",
            'label_avg_ytd'       => "AVG YTD<br><small>(UP TO " . strtoupper($nama_bln_ytd) . ")</small>",
            // Label Compare Mode
            'label_compare_subtotal' => "SUB TOTAL",
            'label_compare_avg'      => "AVG",
            'label_compare_weighted' => "AVG TERTIMBANG",
            'label_compare_kpi'      => "KPI",
            'sub_label_now'          => "{$yearNow} (up to )",
            'sub_label_prev'         => "{$yearPrev}"
        );

        $summaryCompare = array();
        $summarySingle  = array();

        $totalCompare = array(
            'subtotal_prev'     => 0,
            'subtotal_now'      => 0,
            'avg_prev'          => 0,
            'avg_now'           => 0,
            'avg_weighted_prev' => 0,
            'avg_weighted_now'  => 0,
            'kpi_status'        => 'Turun'
        );

        $totalSingle = array(
            'subtotal_prev_ytd' => 0,
            'avg_prev_ytd'      => 0,
            'subtotal_ytd'      => 0,
            'avg_ytd'           => 0
        );

        foreach ($sellers as $sId => $sNm) {
            // A. Single Year Metrics (Tahun Berjalan)
            $subtotalPrevYtd = 0;
            $subtotalYtdNow  = 0;

            for ($m = 1; $m <= $m_ytd; $m++) {
                $bStr = sprintf("%02d", $m);
                $valNow = isset($matrix[$sId][$yearNow][$bStr]) ? (float)$matrix[$sId][$yearNow][$bStr] : 0;
                
                if ($m <= $m_prev_ytd) {
                    $subtotalPrevYtd += $valNow;
                }
                $subtotalYtdNow += $valNow;
            }

            $avgPrevYtd = ($m_prev_ytd > 0) ? ($subtotalPrevYtd / $m_prev_ytd) : 0;
            $avgYtdNow  = ($m_ytd > 0) ? ($subtotalYtdNow / $m_ytd) : 0;

            $summarySingle[$sId] = array(
                'subtotal_prev_ytd' => $subtotalPrevYtd,
                'avg_prev_ytd'      => $avgPrevYtd,
                'subtotal_ytd'      => $subtotalYtdNow,
                'avg_ytd'           => $avgYtdNow
            );

            $totalSingle['subtotal_prev_ytd'] += $subtotalPrevYtd;
            $totalSingle['subtotal_ytd']      += $subtotalYtdNow;

            // B. Compare Metrics (Tahun Lalu vs Tahun Berjalan)
            // Hitung total tahun lalu
            $totPrevYear = 0;
            for ($m = 1; $m <= 12; $m++) {
                $bStr = sprintf("%02d", $m);
                $totPrevYear += isset($matrix[$sId][$yearPrev][$bStr]) ? (float)$matrix[$sId][$yearPrev][$bStr] : 0;
            }

            $subtotalNowCompare = $subtotalYtdNow;
            $avgPrevCompare     = ($m_ytd > 0) ? ($totPrevYear / $m_ytd) : 0;
            $avgNowCompare      = ($m_ytd > 0) ? ($subtotalNowCompare / $m_ytd) : 0;
            $avgWeightedPrev    = $totPrevYear / 12;
            $avgWeightedNow     = $subtotalNowCompare / 12;
            $kpiStatus          = ($subtotalNowCompare >= $totPrevYear) ? 'Naik' : 'Turun';

            $summaryCompare[$sId] = array(
                'subtotal_prev'     => $totPrevYear,
                'subtotal_now'      => $subtotalNowCompare,
                'avg_prev'          => $avgPrevCompare,
                'avg_now'           => $avgNowCompare,
                'avg_weighted_prev' => $avgWeightedPrev,
                'avg_weighted_now'  => $avgWeightedNow,
                'kpi_status'        => $kpiStatus
            );

            $totalCompare['subtotal_prev'] += $totPrevYear;
            $totalCompare['subtotal_now']  += $subtotalNowCompare;
        }

        // Akumulasi Total Footer Single
        $totalSingle['avg_prev_ytd'] = ($m_prev_ytd > 0) ? ($totalSingle['subtotal_prev_ytd'] / $m_prev_ytd) : 0;
        $totalSingle['avg_ytd']      = ($m_ytd > 0) ? ($totalSingle['subtotal_ytd'] / $m_ytd) : 0;

        // Akumulasi Total Footer Compare
        $totalCompare['avg_prev']          = ($m_ytd > 0) ? ($totalCompare['subtotal_prev'] / $m_ytd) : 0;
        $totalCompare['avg_now']           = ($m_ytd > 0) ? ($totalCompare['subtotal_now'] / $m_ytd) : 0;
        $totalCompare['avg_weighted_prev'] = $totalCompare['subtotal_prev'] / 12;
        $totalCompare['avg_weighted_now']  = $totalCompare['subtotal_now'] / 12;
        $totalCompare['kpi_status']        = ($totalCompare['subtotal_now'] >= $totalCompare['subtotal_prev']) ? 'Naik' : 'Turun';

        return array(
            "sellers"               => $sellers,
            "matrix"                => $matrix,
            "total_bawah"           => $totalBawah,
            "total_tahun"           => $totalTahun,
            "year_now"              => $yearNow,
            "year_prev"             => $yearPrev,
            "months"                => $monthsList,
            "ytd_info"              => $ytdInfo,
            // Ringkasan 2 Mode
            "summary_compare"       => $summaryCompare,
            "total_compare"         => $totalCompare,
            "summary_single"        => $summarySingle,
            "total_single"          => $totalSingle,
            // Backward compatibility
            "summary_columns"       => $summarySingle,
            "total_summary_columns" => $totalSingle
        );
    }

    /**
     * Mengembalikan daftar nama bulan (01 s/d 12)
     *
     * @return array
     */
    public function getMonthsList()
    {
        return array(
            "01" => "Januari",
            "02" => "Februari",
            "03" => "Maret",
            "04" => "April",
            "05" => "Mei",
            "06" => "Juni",
            "07" => "Juli",
            "08" => "Agustus",
            "09" => "September",
            "10" => "Oktober",
            "11" => "November",
            "12" => "Desember"
        );
    }
}
