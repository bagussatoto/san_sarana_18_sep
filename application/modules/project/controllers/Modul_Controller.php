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

        if(!isset($_GET['ismob'])){
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
        $this->jenisTr = $tmpJenis = $this->uri->segment(4) === 410302 ? $this->uri->segment(4) : "410302";
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

        $this->load->config($configPath . "coTransaksiUi");
        $this->configUi = $this->config->item("coTransaksiUi");
        $this->load->config($configPath . "coTransaksiCore");
        $this->configCore = $this->config->item("coTransaksiCore");
        $this->load->config($configPath . "coTransaksiLayout");
        $this->configLayout = $this->config->item("coTransaksiLayout");
        $this->load->config($configPath . "coTransaksiValues");
        $this->configValues = $this->config->item("coTransaksiValues");

        if (isset($this->jenisTr)) {
            $this->configUiJenis = isset($this->configUi[$this->jenisTr]) ? $this->configUi[$this->jenisTr] : cekOrange($this->jenisTr . " belum ada di coTransaksiUi di modul " . $this->modul);
            $this->configCoreJenis = isset($this->configCore[$this->jenisTr]) ? $this->configCore[$this->jenisTr] : cekBiru($this->jenisTr . " belum ada di coTransaksiCore di modul " . $this->modul);
            $this->configLayoutJenis = isset($this->configLayout[$this->jenisTr]) ? $this->configLayout[$this->jenisTr] : cekMerah($this->jenisTr . " belum ada di coTransaksiLayout di modul " . $this->modul);
            $this->configValuesJenis = isset($this->configValues[$this->jenisTr]) ? $this->configValues[$this->jenisTr] : cekMerah($this->jenisTr . " belum ada di coTransaksiLayout di modul " . $this->modul);
            $this->jenisTrName = isset($this->configUi[$this->jenisTr]['steps'][1]['label']) ? $this->configUi[$this->jenisTr]['steps'][1]['label'] : "unnamed";
            validatePlace($this->configUiJenis["place"], my_place());
        }

        $this->load->helper("he_access_right");
        $this->load->helper("he_session_replacer");
        $this->accessList = alowedAccess(my_id())["akses"];

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

    public function index()
    {
        cekOrange($this->modul);
        die(__FILE__ . " gondes");
    }

    public function projectBom($projectID, $handler = null)
    {
//        $ci = &get_instance();
        $this->load->model("Mdls/MdlProdukKomposisi");
        $b = new MdlProdukKomposisi();
        $b->setFilters(array());
        $b->addFilter("status='1'");
        $b->addFilter("trash='0'");
        $b->addFilter("produk_id='$projectID'");
        $temp = $b->lookUpAll()->result();
        cekHere($ci->db->last_query());
        $data = array();
        foreach ($temp as $tempData) {
            //        arrPrintWebs($tempData);
            $data[$tempData->jenis][$tempData->produk_dasar_id] = array(
                "handler" => $handler,
                "id" => $tempData->produk_dasar_id,
                "nama" => $tempData->produk_dasar_nama,
                "produk_nama" => $tempData->produk_dasar_nama,
                "name" => $tempData->produk_dasar_nama,
                //            "jml" => $tempData->jml,
                //            "qty" => $tempData->jml,
                //            "valid_qty" => $tempData->jml,
                //            "produk_ord_jml" => $tempData->jml,
                //            "harga" => $tempData->nilai,
                //            "subtotal" => $tempData->jml * $tempData->nilai,
                "jml" => 1,
                "qty" => 1,
                "valid_qty" => 1,
                "produk_ord_jml" => 1,
                "harga" => $tempData->nilai,
                "subtotal" => 1 * $tempData->nilai,
            );
        }
        //arrPrintHijau($data);

        return $data;


    }

    public function aksesProject()
    {
//        $ci = &get_instance();
        $this->load->model("Mdls/MdlProjectInternAccess");
        $m = new MdlProjectInternAccess();
        $fields = $m->getListedFields();
        $temp = $m->lookUpAll()->result();
        $data = array();
        if (count($temp) > 0) {
            foreach ($temp as $temp_0) {
                $data[$temp_0->id] = (array)$temp_0;
            }
        }
        // $data = array(
        //     "1"=>array(
        //         "id"=>"1",
        //         "nama"=>"admin",
        //         "keterangan"=>"full akses",
        //     ),
        //     "2"=>array(
        //         "id"=>"2",
        //         "nama"=>"supervisi",
        //         "keterangan"=>"melakukan monitoring dan pemeriksaan project",
        //     ),
        //     "3"=>array(
        //         "id"=>"3",
        //         "nama"=>"anggota",
        //         "keterangan"=>"melakukan pembaharuan progres",
        //     ),
        //     "4"=>array(
        //         "id"=>"4",
        //         "nama"=>"gudang site",
        //         "keterangan"=>"menerima dan mengeluarkan material dari gudang site"
        //     ),
        //     "5"=>array(
        //         "id"=>"5",
        //         "nama"=>"pihak lain",
        //         "keterangan"=>"pihak atau sub kontraktor"
        //     ),
        // );
        return $data;
    }

}
