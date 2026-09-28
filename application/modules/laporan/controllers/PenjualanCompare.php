<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Controller: PenjualanCompare
 * 
 * Modul Laporan Penjualan:
 * Menyajikan perbandingan order penjualan per salesman (Side-by-Side: Tahun Lalu vs Tahun Berjalan)
 * berbasis arsitektur Domain-Driven Design (DDD).
 * 
 * Mengadopsi pola template sistem:
 * - Shell Halaman: Memakai template laporan_penjualan_kompare.html via library Layout.
 * - Konten Tabel: Dimuat secara dinamis via AJAX ke #sum_satu dari cekSellerOrderBl2Side.
 */
class PenjualanCompare extends MX_Controller
{
    public $modul_path;

    public function __construct()
    {
        parent::__construct();

        // 1. Strict Guard Session sesuai pedoman global agent
        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
        if (function_exists('validateUserSession')) {
            validateUserSession($this->session->login['id']);
        }

        $this->modul_path = base_url() . "laporan/PenjualanCompare/";
        $this->load->helper(array('url', 'he_menu', 'he_mass_table', 'he_date_time', 'he_format'));
        $this->load->model('Mdls/MdlRawPenjualan', 'mdlrawpenjualan');
        $this->load->library('laporan/MonthlyMatrixFormatter', null, 'monthlymatrixformatter');
        $this->load->library('Layout');
    }

    /**
     * Endpoint utama laporan order penjualan per salesman side-by-side
     * Memakai template laporan_penjualan_kompare.html sesuai pola sistem
     * URL: /laporan/PenjualanCompare/vieworderpenjualanside
     */
    /**
     * Membuat HTML untuk filter Tahun dan Button Group Status Gudang
     *
     * @param string $actionUrl URL form action (current_url())
     * @param string|int $strTahun Tahun aktif
     * @param string $gudangStatus Status gudang aktif ('all', '0', 'not_0')
     * @return string HTML <td> elements
     */
    protected function renderFilterBar($actionUrl, $strTahun, $gudangStatus = 'all')
    {
        $arrTahun = array();
        $y = dtimeNow('Y');
        for ($i = 0; $i < 5; $i++) {
            $arrTahun[] = $y--;
        }

        $url_all      = $actionUrl . "?thn=" . $strTahun . "&gudang_status=all";
        $url_zero     = $actionUrl . "?thn=" . $strTahun . "&gudang_status=0";
        $url_not_zero = $actionUrl . "?thn=" . $strTahun . "&gudang_status=not_0";

        $cls_all      = ($gudangStatus === 'all' || empty($gudangStatus)) ? 'btn-primary' : 'btn-default';
        $cls_zero     = ($gudangStatus === '0') ? 'btn-primary' : 'btn-default';
        $cls_not_zero = ($gudangStatus === 'not_0') ? 'btn-primary' : 'btn-default';

        $html = "";
        $html .= "<td style='width: 140px; vertical-align: middle;'>";
        $html .= "<select id='tahun' class='btn btn-danger' style='width: 100%; font-weight: bold;' onchange=\"location.href='$actionUrl?thn='+this.value+'&gudang_status=$gudangStatus'\">";
        $html .= "<option value=''>--pilih tahun--</option>";
        foreach ($arrTahun as $th) {
            $selected = ((string)$th === (string)$strTahun) ? "selected" : "";
            $html .= "<option value='$th' $selected>$th</option>";
        }
        $html .= "</select>";
        $html .= "</td>";

        $html .= "<td style='padding-left: 15px; vertical-align: middle;'>";
        $html .= "<div class='btn-group'>";
        $html .= "<button type='button' class='btn $cls_all' onclick=\"location.href='$url_all'\">Semua</button>";
        $html .= "<button type='button' class='btn $cls_zero' onclick=\"location.href='$url_zero'\">Domestik</button>";
        $html .= "<button type='button' class='btn $cls_not_zero' onclick=\"location.href='$url_not_zero'\">Pusat</button>";
        $html .= "</div>";
        $html .= "</td>";
        $html .= "<td></td>";

        return $html;
    }

    /**
     * Endpoint utama laporan order penjualan per salesman side-by-side
     * Memakai template laporan_penjualan_kompare.html sesuai pola sistem
     * URL: /laporan/PenjualanCompare/vieworderpenjualanside
     */
    public function vieworderpenjualanside()
    {
        $year_now = dtimeNow('Y');
        $strTahun = isset($_GET['thn']) && !empty($_GET['thn']) ? $_GET['thn'] : $year_now;
        $gudang_status = isset($_GET['gudang_status']) ? $_GET['gudang_status'] : 'all';
        if (!in_array($gudang_status, array('all', '0', 'not_0'))) {
            $gudang_status = 'all';
        }

        $add_td = $this->renderFilterBar(current_url(), $strTahun, $gudang_status);

        // Query string untuk AJAX loader konten tabel
        $strGet = "?thn=" . $strTahun . "&gudang_status=" . $gudang_status;
        if (isset($_GET['refresh'])) $strGet .= "&refresh=" . $_GET['refresh'];

        $sum_satu = base_url() . "laporan/PenjualanCompare/cekSellerOrderBl2Side" . $strGet;
        $content = "<div id='sum_satu'></div>";
        $content .= "<script>$('#sum_satu').load('$sum_satu');</script>";

        // Path template HTML sesuai pola sistem
        $template_file = APPPATH . "modules/laporan/template/laporan_penjualan_kompare.html";
        $p = new Layout("Laporan Order Penjualan Per Salesman", "Tahun " . $strTahun, $template_file);

        $p->addTags(array(
            "menu_left"        => callMenuleft(),
            "trans_menu"       => callTransMenu(),
            "float_menu_atas"  => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar"     => callMenuTaskbar(),
            "btn_back"         => function_exists('callBackNav') ? callBackNav() : "",
            "add_td"           => $add_td,
            "content"          => $content,
            "url"              => current_url(),
            "url_hari_lalu"    => "",
            "hari_lalu"        => "",
            "url_hari_ini"     => "",
            "hari_ini"         => "",
            "url_hari_besuk"   => "",
            "hari_besuk"       => "",
            "btn_attr"         => "",
            "date1"            => "",
            "date2"            => "",
            "date_min"         => "2020-01-01",
            "date_max"         => dtimeNow('Y-m-d'),
            "bg_modal"         => "",
            "lebar_modal"      => "",
            "isi_modal"        => "",
            "footer"           => "",
        ));

        $p->render();
    }

    /**
     * Endpoint pemroses matriks data perbandingan yang dipanggil via AJAX oleh #sum_satu
     * URL: /laporan/PenjualanCompare/cekSellerOrderBl2Side
     */
    public function cekSellerOrderBl2Side()
    {
        $yearNow  = isset($_GET['thn']) && !empty($_GET['thn']) ? (int)$_GET['thn'] : (int)date('Y');
        $yearPrev = $yearNow - 1;
        $gudang_status = isset($_GET['gudang_status']) ? $_GET['gudang_status'] : 'all';
        if (!in_array($gudang_status, array('all', '0', 'not_0'))) {
            $gudang_status = 'all';
        }

        // Tentukan cut-off month dari date2 / date1 (jika tersedia)
        $cutOffMonth = null;
        if (isset($_GET['date2']) && !empty($_GET['date2'])) {
            $cutOffMonth = (int)date('n', strtotime($_GET['date2']));
        } elseif (isset($_GET['date1']) && !empty($_GET['date1'])) {
            $cutOffMonth = (int)date('n', strtotime($_GET['date1']));
        }

        // Jika tombol syncron / refresh ditekan, bersihkan cache tahun lampau
        if (isset($_GET['refresh']) && $_GET['refresh'] == '1') {
            $this->mdlrawpenjualan->clearYearCache($yearPrev, $gudang_status);
        }

        // 1. DOMAIN STEP: Ambil data proyeksi mentah dengan Hybrid Caching & Index Range Scan
        $rawRecords = $this->mdlrawpenjualan->callSummarySellerSoBulananHybrid($yearNow, $yearPrev, $gudang_status);

        // 2. SERVICE STEP: Format data mentah menjadi matriks 12 bulan side-by-side
        $matrixConfig = array(
            'subjek_key'      => 'subjek_id',
            'subjek_name_key' => 'subjek_nama',
            'year_now'        => $yearNow,
            'year_prev'       => $yearPrev,
            'cut_off_month'   => $cutOffMonth
        );
        $formattedData = $this->monthlymatrixformatter->formatSideBySideMatrix($rawRecords, $matrixConfig);

        // 3. PRESENTATION STEP: Kirim data siap saji ke View tabel murni
        $viewData = array_merge($formattedData, array(
            'modul_path'    => $this->modul_path,
            'title'         => 'Laporan order penjualan per salesman Tahun ' . $yearNow,
            'sub_title'     => 'meliputi data order penjualan local, export dan projek (NETTO)',
            'year_now'      => $yearNow,
            'year_prev'     => $yearPrev,
            'gudang_status' => $gudang_status,
            'detail_url'    => base_url() . "laporan/PenjualanCompare/detailSellerTransactions",
        ));

        $this->load->view('laporan_penjualan_matrix', $viewData);
    }

    /**
     * Endpoint utama laporan penjualan per salesman (Realisasi/Delivery 5822spd) side-by-side
     * Memakai template laporan_penjualan_kompare.html sesuai pola sistem
     * URL: /laporan/PenjualanCompare/viewpenjualanside
     */
    public function viewpenjualanside()
    {
        $year_now = dtimeNow('Y');
        $strTahun = isset($_GET['thn']) && !empty($_GET['thn']) ? $_GET['thn'] : $year_now;
        $gudang_status = isset($_GET['gudang_status']) ? $_GET['gudang_status'] : 'all';
        if (!in_array($gudang_status, array('all', '0', 'not_0'))) {
            $gudang_status = 'all';
        }

        $add_td = $this->renderFilterBar(current_url(), $strTahun, $gudang_status);

        // Query string untuk AJAX loader konten tabel
        $strGet = "?thn=" . $strTahun . "&gudang_status=" . $gudang_status;
        if (isset($_GET['refresh'])) $strGet .= "&refresh=" . $_GET['refresh'];

        $sum_satu = base_url() . "laporan/PenjualanCompare/cekSellerBl2Side" . $strGet;
        $content = "<div id='sum_satu'></div>";
        $content .= "<script>$('#sum_satu').load('$sum_satu');</script>";

        // Path template HTML sesuai pola sistem
        $template_file = APPPATH . "modules/laporan/template/laporan_penjualan_kompare.html";
        $p = new Layout("Laporan Penjualan Per Salesman", "Tahun " . $strTahun, $template_file);

        $p->addTags(array(
            "menu_left"        => callMenuleft(),
            "trans_menu"       => callTransMenu(),
            "float_menu_atas"  => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar"     => callMenuTaskbar(),
            "btn_back"         => function_exists('callBackNav') ? callBackNav() : "",
            "add_td"           => $add_td,
            "content"          => $content,
            "url"              => current_url(),
            "url_hari_lalu"    => "",
            "hari_lalu"        => "",
            "url_hari_ini"     => "",
            "hari_ini"         => "",
            "url_hari_besuk"   => "",
            "hari_besuk"       => "",
            "btn_attr"         => "",
            "date1"            => "",
            "date2"            => "",
            "date_min"         => "2020-01-01",
            "date_max"         => dtimeNow('Y-m-d'),
            "bg_modal"         => "",
            "lebar_modal"      => "",
            "isi_modal"        => "",
            "footer"           => "",
        ));

        $p->render();
    }

    /**
     * Endpoint pemroses matriks data perbandingan realisasi penjualan yang dipanggil via AJAX oleh #sum_satu
     * URL: /laporan/PenjualanCompare/cekSellerBl2Side
     */
    public function cekSellerBl2Side()
    {
        $yearNow  = isset($_GET['thn']) && !empty($_GET['thn']) ? (int)$_GET['thn'] : (int)date('Y');
        $yearPrev = $yearNow - 1;
        $gudang_status = isset($_GET['gudang_status']) ? $_GET['gudang_status'] : 'all';
        if (!in_array($gudang_status, array('all', '0', 'not_0'))) {
            $gudang_status = 'all';
        }

        // Tentukan cut-off month dari date2 / date1 (jika tersedia)
        $cutOffMonth = null;
        if (isset($_GET['date2']) && !empty($_GET['date2'])) {
            $cutOffMonth = (int)date('n', strtotime($_GET['date2']));
        } elseif (isset($_GET['date1']) && !empty($_GET['date1'])) {
            $cutOffMonth = (int)date('n', strtotime($_GET['date1']));
        }

        // Jika tombol syncron / refresh ditekan, bersihkan cache tahun lampau
        if (isset($_GET['refresh']) && $_GET['refresh'] == '1') {
            $this->mdlrawpenjualan->clearYearSalesCache($yearPrev, $gudang_status);
        }

        // 1. DOMAIN STEP: Ambil data realisasi penjualan (5822spd) dengan Hybrid Caching & Index Range Scan
        $rawRecords = $this->mdlrawpenjualan->callSummarySellerPenjualanBulananHybrid($yearNow, $yearPrev, $gudang_status);

        // 2. SERVICE STEP: Format data mentah menjadi matriks 12 bulan side-by-side
        $matrixConfig = array(
            'subjek_key'      => 'subjek_id',
            'subjek_name_key' => 'subjek_nama',
            'year_now'        => $yearNow,
            'year_prev'       => $yearPrev,
            'cut_off_month'   => $cutOffMonth
        );
        $formattedData = $this->monthlymatrixformatter->formatSideBySideMatrix($rawRecords, $matrixConfig);

        // 3. PRESENTATION STEP: Kirim data siap saji ke View tabel murni
        $viewData = array_merge($formattedData, array(
            'modul_path'    => $this->modul_path,
            'title'         => 'Laporan penjualan per salesman Tahun ' . $yearNow,
            'sub_title'     => 'meliputi data penjualan (packinglist / delivery 5822spd) (NETTO)',
            'year_now'      => $yearNow,
            'year_prev'     => $yearPrev,
            'gudang_status' => $gudang_status,
            'detail_url'    => base_url() . "laporan/PenjualanCompare/detailSellerPenjualanTransactions",
        ));

        $this->load->view('laporan_penjualan_matrix', $viewData);
    }

    /**
     * Endpoint AJAX untuk memuat rincian transaksi pembentuk angka di dalam modal (Order Penjualan)
     * URL: /laporan/PenjualanCompare/detailSellerTransactions
     */
    public function detailSellerTransactions()
    {
        $sellerId      = isset($_GET['seller_id']) ? (int)$_GET['seller_id'] : 0;
        $sellerNama    = isset($_GET['seller_nama']) ? trim($_GET['seller_nama']) : 'Salesman';
        $year          = isset($_GET['year']) && !empty($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');
        $monthStart    = isset($_GET['month_start']) ? (int)$_GET['month_start'] : 1;
        $monthEnd      = isset($_GET['month_end']) ? (int)$_GET['month_end'] : $monthStart;
        $gudang_status = isset($_GET['gudang_status']) ? $_GET['gudang_status'] : 'all';
        $label         = isset($_GET['label']) ? trim($_GET['label']) : ($monthStart == $monthEnd ? "Bulan {$monthStart} {$year}" : "Bulan {$monthStart} - {$monthEnd} {$year}");

        if ($gudang_status === '0') {
            $label .= " (Gudang Status = 0)";
        } elseif ($gudang_status === 'not_0') {
            $label .= " (Gudang Status != 0)";
        }

        $transactions = $this->mdlrawpenjualan->callDetailSellerSoTransactions($sellerId, $year, $monthStart, $monthEnd, $gudang_status);

        $viewData = array(
            'seller_id'      => $sellerId,
            'seller_nama'    => $sellerNama,
            'year'           => $year,
            'periode_label'  => $label,
            'transactions'   => $transactions,
            'columns_config' => $this->getDetailColumnsConfig()
        );

        $this->load->view('modal_detail_transaksi_seller', $viewData);
    }

    /**
     * Endpoint AJAX untuk memuat rincian transaksi pembentuk angka di dalam modal (Realisasi Penjualan 5822spd)
     * URL: /laporan/PenjualanCompare/detailSellerPenjualanTransactions
     */
    public function detailSellerPenjualanTransactions()
    {
        $sellerId      = isset($_GET['seller_id']) ? (int)$_GET['seller_id'] : 0;
        $sellerNama    = isset($_GET['seller_nama']) ? trim($_GET['seller_nama']) : 'Salesman';
        $year          = isset($_GET['year']) && !empty($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');
        $monthStart    = isset($_GET['month_start']) ? (int)$_GET['month_start'] : 1;
        $monthEnd      = isset($_GET['month_end']) ? (int)$_GET['month_end'] : $monthStart;
        $gudang_status = isset($_GET['gudang_status']) ? $_GET['gudang_status'] : 'all';
        $label         = isset($_GET['label']) ? trim($_GET['label']) : ($monthStart == $monthEnd ? "Bulan {$monthStart} {$year}" : "Bulan {$monthStart} - {$monthEnd} {$year}");

        if ($gudang_status === '0') {
            $label .= " (Gudang Status = 0)";
        } elseif ($gudang_status === 'not_0') {
            $label .= " (Gudang Status != 0)";
        }

        $transactions = $this->mdlrawpenjualan->callDetailSellerPenjualanTransactions($sellerId, $year, $monthStart, $monthEnd, $gudang_status);

        $viewData = array(
            'seller_id'      => $sellerId,
            'seller_nama'    => $sellerNama,
            'year'           => $year,
            'periode_label'  => $label,
            'transactions'   => $transactions,
            'columns_config' => $this->getDetailColumnsConfig()
        );

        $this->load->view('modal_detail_transaksi_seller', $viewData);
    }

    /**
     * Konfigurasi schema kolom untuk tabel rincian transaksi di dalam modal
     * Memudahkan penambahan/perubahan kolom tanpa menyentuh file View
     *
     * @return array
     */
    protected function getDetailColumnsConfig()
    {
        return array(
            array(
                'field' => 'dtime',
                'label' => 'TANGGAL & JAM',
                'width' => '130px',
                'align' => 'center',
                'type'  => 'datetime'
            ),
            array(
                'field' => 'nomer',
                'label' => 'NO. TRANSAKSI',
                'width' => '140px',
                'align' => 'left',
                'type'  => 'bold'
            ),
            array(
                'field' => 'customers_nama',
                'label' => 'CUSTOMER / PELANGGAN',
                'width' => '',
                'align' => 'left',
                'type'  => 'text'
            ),
            array(
                'field' => 'oleh_nama',
                'label' => 'OPERATOR INPUT',
                'width' => '120px',
                'align' => 'center',
                'type'  => 'text'
            ),
            array(
                'field' => 'gudang_status_nama',
                'label' => 'GUDANG',
                'width' => '100px',
                'align' => 'center',
                'type'  => 'badge'
            ),
            array(
                'field' => 'transaksi_netto',
                'label' => 'NETTO (RP)',
                'width' => '130px',
                'align' => 'right',
                'type'  => 'currency',
                'sum'   => true
            ),
            array(
                'field' => 'keterangan',
                'label' => 'KETERANGAN',
                'width' => '',
                'align' => 'left',
                'type'  => 'muted'
            )
        );
    }
}
