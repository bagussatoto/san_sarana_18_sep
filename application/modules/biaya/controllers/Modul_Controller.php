<?php
// defined('BASEPATH') OR exit('No direct script access allowed');

class Modul_Controller extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();

        /* ----------------------------------------------------------------------------------
         * validasi session bila tidak ada dipaksa ke halaman login
         * ----------------------------------------------------------------------------------*/

        if (!isset($_GET['ismob'])) {
            if (!isset($this->session->login['id'])) {
                // cekHijau(__LINE__);
                gotoLogin();
            }
            validateUserSession($this->session->login['id']);
        }

        /* ----------------------------------------------------------------------------------
         * loader dari masing-masing modul
         * ----------------------------------------------------------------------------------*/
//        cekBiru($this->uri->segment(4));
        $this->jenisTr = $tmpJenis = $this->uri->segment(4);
        $this->cCode = $cCode = "_TR_" . $this->jenisTr;
        $this->modul = $modul = $this->uri->segment(1);
        $this->cabangId = $this->placeId = my_cabang_id();
        $this->dates = dtimeNow('y-m-d');

        /* ----------------------------------------------------------------------------------
         * MODUL_### dibentuk di he_url_helper
         * ---------------------------------------------------------------------------------*/
        $this->modulPath = $modulPath = MODUL_PATH;
        // cekHitam($this->modulPath);
        /* ---------------------------------------------------------------------------------
         * untuk ngeload config pada modul
         * ---------------------------------------------------------------------------------*/
        $this->configPath = $configPath = MODUL_CONFIG_PATH;
        // cekHitam($this->configPath);
        $this->load->config($configPath . "coTransaksiUi");
        $this->configUi = $this->config->item("coTransaksiUi");


        $this->load->config($configPath . "coTransaksiCore");
        $this->configCore = $this->config->item("coTransaksiCore");

        $this->load->config($configPath . "coTransaksiLayout");
        $this->configLayout = $this->config->item("coTransaksiLayout");
        $this->load->config($configPath . "coTransaksiValues");
        $this->configValues = $this->config->item("coTransaksiValues");

        if (isset($this->jenisTr)) {
            // $this->configUiJenis = $this->configUi[$this->jenisTr];
            $this->configUiJenis = isset($this->configUi[$this->jenisTr]) ? $this->configUi[$this->jenisTr] : cekOrange($this->jenisTr . " belum ada di coTransaksiUi di modul " . $this->modul);
            $this->configCoreJenis = isset($this->configCore[$this->jenisTr]) ? $this->configCore[$this->jenisTr] : cekBiru($this->jenisTr . " belum ada di coTransaksiCore di modul " . $this->modul);
            $this->configLayoutJenis = isset($this->configLayout[$this->jenisTr]) ? $this->configLayout[$this->jenisTr] : cekMerah($this->jenisTr . " belum ada di coTransaksiLayout di modul " . $this->modul);
            $this->configValuesJenis = isset($this->configValues[$this->jenisTr]) ? $this->configValues[$this->jenisTr] : cekMerah($this->jenisTr . " belum ada di coTransaksiValues di modul " . $this->modul);
            $this->jenisTrName = isset($this->configUi[$this->jenisTr]['steps'][1]['label']) ? $this->configUi[$this->jenisTr]['steps'][1]['label'] : "unnamed";

            validatePlace($this->configUiJenis["place"], my_place());
        }

        $this->load->helper("he_access_right");
        $this->load->helper("he_session_replacer");
        $this->accessList = alowedAccess(my_id())["akses"];

//        arrPrint($this->accessList);
        $this->transaksiMaintenance = $this->config->item("maintenanceTransaksi") != null && $this->config->item("maintenanceTransaksi") == true ? true : false;
        $this->transaksiMaintenanceMsg = isset($this->config->item("maintenanceOptions")[1]) ? $this->config->item("maintenanceOptions")[1] : array();
        $this->mongoTableList = array(
            "main" => "transaksi",
            "mainValues" => "transaksi_values",
            "detail" => "transaksi_data",
            "detailValues" => "transaksi_data_values",
            "sign" => "transaksi_sign",
            "extras" => "transaksi_extstep",
            "registry" => "transaksi_registry",
        );
        $this->allSteps = isset($this->configUi[$this->jenisTr]['steps']) ? $this->configUi[$this->jenisTr]['steps'] : array();

        $this->ppnFactor = isset($_SESSION['login']['ppnFactor']) ? $this->session->login["ppnFactor"] : cekOrange("cek " . __FILE__);

    }

    protected function requestValue($key, $default = null)
    {
        $value = $this->input->post($key, false);
        if ($value === null) {
            $value = $this->input->get($key, false);
        }
        if ($value === null) {
            return $default;
        }

        return $value;
    }

    protected function requestInt($key, $default = 0)
    {
        return (int)$this->requestValue($key, $default);
    }

    protected function requestArrayPayload($key, $default = array())
    {
        $raw = $this->requestValue($key, null);
        if (!is_string($raw) || $raw === "") {
            return $default;
        }

        $decoded = blobDecodeRequest($raw, $default);
        if (!is_array($decoded)) {
            return $default;
        }

        return $decoded;
    }

    protected function enforceMutationAccess($step = 1)
    {
        $cCode = isset($this->cCode) ? $this->cCode : "";
        $sessionPlace = my_cabang_id();
        if ($cCode !== "" && isset($_SESSION[$cCode]['main']['placeID']) && $_SESSION[$cCode]['main']['placeID'] != "") {
            $sessionPlace = $_SESSION[$cCode]['main']['placeID'];
        }

        $valid = stepCodeByEmployeeID($this->jenisTr, (int)$step, my_id(), my_cabang_id(), $sessionPlace, false);
        if ($valid !== true) {
            return false;
        }

        validateAllowPlace($this->jenisTr, my_cabang_id(), false);

        return true;
    }

    protected function ensureBookingNumber($cCode = null)
    {
        $gate = $cCode !== null ? $cCode : $this->cCode;
        if (!isset($_SESSION[$gate])) {
            $_SESSION[$gate] = array();
        }
        if (!isset($_SESSION[$gate]['main'])) {
            $_SESSION[$gate]['main'] = array();
        }

        if (!isset($_SESSION[$gate]['main']['bookingNumber']) || $_SESSION[$gate]['main']['bookingNumber'] === null || $_SESSION[$gate]['main']['bookingNumber'] === "") {
            $seed = "";
            if (function_exists('random_bytes')) {
                try {
                    $seed = bin2hex(random_bytes(8));
                } catch (Exception $ex) {
                    $seed = "";
                }
            }
            if ($seed === "") {
                $seed = substr(sha1(uniqid(mt_rand(), true)), 0, 16);
            }
            $_SESSION[$gate]['main']['bookingNumber'] = date("YmdHis") . strtoupper($seed);
        }

        return $_SESSION[$gate]['main']['bookingNumber'];
    }

    protected function requireBookingNumber($cCode = null)
    {
        $gate = $cCode !== null ? $cCode : $this->cCode;
        if (!isset($_SESSION[$gate]) || !isset($_SESSION[$gate]['main']) || !isset($_SESSION[$gate]['main']['bookingNumber'])) {
            $msg = "Nomer Booking transaksi baru belum terdaftar. code: " . __LINE__;
            mati_disini($msg);
        }

        $bookingNumber = trim((string)$_SESSION[$gate]['main']['bookingNumber']);
        if ($bookingNumber === "") {
            $msg = "Nomer Booking transaksi baru belum terdaftar. code: " . __LINE__;
            mati_disini($msg);
        }

        return $bookingNumber;
    }

    protected function assertBookingNumberUnchanged($expectedBookingNumber, $cCode = null)
    {
        $currentBookingNumber = $this->requireBookingNumber($cCode);
        if ((string)$expectedBookingNumber !== $currentBookingNumber) {
            $msg = "Transaksi gagal disimpan karena terdeteksi ganda, silahkan refresh halaman ini. code: " . __LINE__;
            mati_disini($msg);
        }

        return true;
    }

    protected function clearBookingNumber($cCode = null)
    {
        $gate = $cCode !== null ? $cCode : $this->cCode;
        if (isset($_SESSION[$gate]['main']['bookingNumber'])) {
            $_SESSION[$gate]['main']['bookingNumber'] = null;
            unset($_SESSION[$gate]['main']['bookingNumber']);
        }
    }

    public function index()
    {
        cekOrange($this->modul);
        die(__FILE__ . " gondes");

    }

}
