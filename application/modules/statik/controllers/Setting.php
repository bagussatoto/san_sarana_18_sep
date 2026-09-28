<?php

defined('BASEPATH') OR exit('No direct script access allowed');


/* --------------------------------------------------
 * error php dihidden untuk memunculkanya matikan saja
 * ----------------------------------------------*/

// if (!isset($_GET['debug_index']) || $_GET['debug_index'] != 1) {
//     ini_set('display_errors', 0);
//     ini_set('display_startup_errors', 0);
//     error_reporting(-1);
// }
// else {
//     ini_set('display_errors', 1);
// }


// --------------------------------------------------

class Setting extends MX_Controller
{
    // edited by glg (17:00 WIB, 2025-01-23)
    // change: menambahkan class constants untuk menghilangkan hardcoded values
    // technical rationale: memudahkan maintenance dan konsistensi di seluruh method

    // Step mapping untuk permission DATA (dengan HISTORICAL)
    const STEP_MAPPING_DATA = [
        '1' => 'CREATE',
        '2' => 'EDIT',
        '3' => 'DELETE',
        '4' => 'VIEW',
        '5' => 'HISTORICAL'
    ];

    // Step mapping untuk permission TRANSAKSI (tanpa HISTORICAL)
    const STEP_MAPPING_TRANSAKSI = [
        '1' => 'CREATE',
        '2' => 'EDIT',
        '3' => 'DELETE',
        '4' => 'VIEW'
    ];

    // Label untuk grup yang tidak terdefinisi
    const UNGROUPED_LABEL = 'Other';

    // Error messages untuk API response
    const ERROR_MSG_EMPLOYEE_ID_REQUIRED = 'Employee ID required';
    const ERROR_MSG_EMPLOYEE_NOT_FOUND = 'Employee not found';
    public function __construct()
    {
        parent::__construct();

        $this->headerProfile = array(
            "nama"       => array(
                "label" => "nama",
                "icon"  => "fa-bank",
                "attr"  => "style='font-size:2em;'",
            ),
            "email"      => array(
                "label" => "email"
            ),
            "tlp"        => array(
                "label"      => "telepon",
                "format"     => "formatNomorTelepon",
                "attr_input" => "pattern=\"\d{9,13}\" title='harus min 9 digit'"
            ),
            "alamat"     => array(
                "label" => "Alamat"
            ),
            "kelurahan"  => array(
                "label" => "Desa/Kelurahan"
            ),
            "kecamatan"  => array(
                "label" => "Kecamatan"
            ),
            "kabupaten"  => array(
                "label" => "Kabupaten/Kota"
            ),
            "propinsi"   => array(
                "label" => "Propinsi"
            ),
            "kode_pos"   => array(
                "label"      => "kode pos",
                "attr_input" => "maxlength='5' pattern=\"\d{5}\" title='harus 5 digit'"
            ),
            "npwp"       => array(
                "label"      => "NPWP",
                "format"     => "formatNPWP",
                "attr_input" => "maxlength='15' pattern=\"\d{15}\" title='harus 15 digit'"
            ),
            "ppn_faktor" => array(
                "label" => "PPN",
                // "format" => "formatDesimal",
                // "attr_input" => "maxlength='15' pattern=\"\d{15}\" title='harus 15 digit'"
            ),
        );
    }

    public function Index()
    {


        $arrHeaders = array(
            "nama" => array(
                "label" => "nama",
            )
        );

        $data = array(
            "mode"           => "index",
            // "isMobile" => $isMob,
            "errMsg"         => $this->session->errMsg,
            "globalTemplate" => isset($globalTemplate) ? $globalTemplate : "",
            // "template"       => MODUL_TEMPLATE_PATH . $this->configUi[$jenisTr]["template"],
            // "title" => callMenuLabel_he_menu(),
            "title"          => "setting menu",
            "subTitle"       => "-",
            "arrHeaders"     => $arrHeaders,
            "masterData"     => isset($masterData) ? $masterData : array(),
            // "grosir_header" => $grosir_header,
            // "grosir_data" => $src_dg,
            // "level_header" => $level_header,
            // "level_data" => $src_clevel_diskons,
            // "level_data"     => array(),
            // "jenisTransaksi" => $jenisTr,

            // "submit_button_target" => $this->modul . "/Transaksi/validate/",
        );
        //arrPrint($data);
        $this->load->view("setting", $data);
    }

    public function viewOther()
    {
        session_write_close();
        $menuBebasTersedia_0 = $this->config->item('groupMenu');
        // arrPrintPink($menuBebasTersedia_0);
        foreach ($menuBebasTersedia_0 as $key => $item) {
            $type = isset($item["type"]) ? $item["type"] : "";
            if ($type != "hidden") {
                $menuBebasTersedia[$key] = $item;
            }
        }
        // $epl = $this->getEmployeeMenu();
        // arrPrintPink($menuTersedia);
        // arrPrintHijau($menuDataTersedia);
        $myMenu = $this->getEmployeeMenu();
        // $myMenuOther = $myMenu['other'];
        $myMenuOther = $myMenu['bebas'];
        // arrPrintHijau();
        $employees = $this->getEmployee();
        $masterData = array();
        $masterData = $this->buildMasterData($employees, "id");
        $childData = $menuBebasTersedia;
        // arrPrintKuning($menuTersedia);
        // arrPrintHijau($masterData);
        // arrPrintHijau($childData);
        $arrHeaders = array(
            "id"   => array(
                "label" => "uid"
            ),
            "nama" => array(
                "label" => "nama",
                "attr_header" => "class='sticky-col first-col'  style='background-color: #d9edf7;'",
                "attr"        => "class='sticky-col first-col text-uppercase'",
            ),
        );

        $data = array(
            "mode"           => "viewOther",
            // "isMobile" => $isMob,
            "errMsg"         => $this->session->errMsg,
            "globalTemplate" => isset($globalTemplate) ? $globalTemplate : "",
            // "template"       => MODUL_TEMPLATE_PATH . $this->configUi[$jenisTr]["template"],
            // "title" => callMenuLabel_he_menu(),
            "title"          => "setting menu",
            "subTitle"       => "-",
            "arrHeaders"     => $arrHeaders,
            "masterData"     => isset($masterData) ? $masterData : array(),
            "childData"      => $childData,
            // "grosir_data" => $src_dg,
            // "level_header" => $level_header,
            // "level_data" => $src_clevel_diskons,
            // "level_data"     => array(),
            "myMenu"         => $myMenuOther,

            // "submit_button_target" => $this->modul . "/Transaksi/validate/",
        );
        //arrPrint($data);
        $this->load->view("setting", $data);
    }

    public function viewData()
    {
        session_write_close();
        $menuDataTersedia = $this->config->item('heDataBehaviour');
        $menuTersedia = $this->config->item('groupMenu');
        // $epl = $this->getEmployeeMenu();
        // arrPrintPink($menuTersedia);
        // arrPrintHijau($menuDataTersedia);
        $myMenu = $this->getEmployeeMenu();
        // $myMenuOther = $myMenu['other'];
        /* --------------------------------------------
         * dicleansing dg data behavior supaya yg sudah tidak aktif tidak mengganggu
         * --------------------------------------------*/
        $myMenuData = $myMenu['data'];
        // arrPrint($menuDataTersedia);
        // arrPrintHijau($myMenuData);

        $groupMenuData = $myMenu['data_group'];
        // arrPrintKuning($myMenuOther);

        $employees = $this->getEmployee();
        $masterData = array();
        $masterData = $this->buildMasterData($employees, "id");
        $childData = $menuDataTersedia;
        // arrPrintKuning($menuTersedia);
        // arrPrintHijau($masterData); // daftar nama employee
        // arrPrintHijau($childData);
        // arrPrintHijau($groupMenuData);

        $arrHeadersGroup = array();
        $arrDataGroup = array();
        foreach ($groupMenuData as $group_key => $myMenuDatum) {
            // arrPrintHijau($myMenuDatum);
            // $arrDataGroup["attr"] = "class='bg-danger'";

            $arrHeadersGroup[$group_key] = $myMenuDatum + $arrDataGroup;
        }

        // arrPrintKuning($arrHeadersGroup);

        $arrHeaders = array(
            "id"   => array(
                "label" => "id",
                // "attr_header" => "class='sticky-col first-col'  style='background-color: #d9edf7;'",
                // "attr"        => "class='sticky-col first-col'",

            ),
            "nama" => array(
                "label"       => "nama",
                "attr_header" => "class='sticky-col first-col'  style='background-color: #d9edf7;'",
                "attr"        => "class='sticky-col first-col text-uppercase'",
            ),
        );
        $childs = array(
            "1" => array(
                "key"   => "creators",
                "label" => "create",
            ),
            "2" => array(
                "key"   => "updaters",
                "label" => "edit",
            ),
            "3" => array(
                "key"   => "deleters",
                "label" => "delete",
            ),
            "4" => array(
                "key"   => "viewers",
                "label" => "view",
            ),
            "5" => array(
                "key"   => "history",
                "label" => "historical",
            ),
        );
        $data = array(
            "mode"            => "viewData",
            // "isMobile" => $isMob,
            "errMsg"          => $this->session->errMsg,
            "globalTemplate"  => isset($globalTemplate) ? $globalTemplate : "",
            // "template"       => MODUL_TEMPLATE_PATH . $this->configUi[$jenisTr]["template"],
            // "title" => callMenuLabel_he_menu(),
            "title"           => "setting menu",
            "subTitle"        => "-",
            "arrHeadersGroup" => $arrHeadersGroup,
            "arrHeaders"      => $arrHeaders,
            "masterData"      => isset($masterData) ? $masterData : array(),
            "childData"       => $childData,
            "childs"          => $childs,
            // "level_header" => $level_header,
            // "level_data" => $src_clevel_diskons,
            // "level_data"     => array(),
            "myMenuData"      => $myMenuData,

            // "submit_button_target" => $this->modul . "/Transaksi/validate/",
        );
        //arrPrint($data);
        $this->load->view("setting", $data);
    }

    public function viewTransaksi()
    {
        session_write_close();
        $menuTransaksiTersedia = $this->config->item('heTransaksi_ui');
        $menuDataTersedia = $this->config->item('heDataBehaviour');
        $menuTersedia = $this->config->item('groupMenu');
        // $epl = $this->getEmployeeMenu();
        // arrPrintPink($menuTersedia);
        // arrPrintHijau($menuDataTersedia);
        $myMenu = $this->getEmployeeMenu();
        // $myMenuOther = $myMenu['other'];
        $myMenuData = $myMenu['transaksi'];
        $groupMenuData = $myMenu['transaksi_group'];
        // arrPrintKuning($myMenuOther);

        $employees = $this->getEmployee();
        $masterData = array();
        $masterData = $this->buildMasterData($employees, "id");
        $childData = $menuTransaksiTersedia;
        // arrPrintKuning($menuTersedia);
        // arrPrintHijau($masterData);
        // arrPrintHijau($childData);

        $arrHeadersGroup = array();
        $arrDataGroup = array();
        foreach ($groupMenuData as $group_key => $myMenuDatum) {

            // $arrDataGroup["attr"] = "class='bg-danger'";

            $arrHeadersGroup[$group_key] = $myMenuDatum + $arrDataGroup;
        }
        // arrPrint($arrHeadersGroup);

        $arrHeaders = array(
            "id"   => array(
                "label" => "id",
                // "attr_header" => "class='sticky-col first-col'  style='background-color: #d9edf7;'",
                // "attr"        => "class='sticky-col first-col'",

            ),
            "nama" => array(
                "label"       => "nama",
                "attr_header" => "class='sticky-col first-col'  style='background-color: #d9edf7;'",
                "attr"        => "class='sticky-col first-col text-uppercase'",
            ),
        );
        $childs = array(
            "1" => array(
                "key"   => "creators",
                "label" => "create",
            ),
            "2" => array(
                "key"   => "updaters",
                "label" => "edit",
            ),
            "3" => array(
                "key"   => "deleters",
                "label" => "delete",
            ),
            "4" => array(
                "key"   => "viewers",
                "label" => "view",
            ),
        );
        $data = array(
            "mode"            => "viewTransaksi",
            // "isMobile" => $isMob,
            "errMsg"          => $this->session->errMsg,
            "globalTemplate"  => isset($globalTemplate) ? $globalTemplate : "",
            // "template"       => MODUL_TEMPLATE_PATH . $this->configUi[$jenisTr]["template"],
            // "title" => callMenuLabel_he_menu(),
            "title"           => "setting menu",
            "subTitle"        => "-",
            "arrHeadersGroup" => $arrHeadersGroup,
            "arrHeaders"      => $arrHeaders,
            "masterData"      => isset($masterData) ? $masterData : array(),
            "childData"       => $childData,
            "childs"          => $childs,
            // "level_header" => $level_header,
            // "level_data" => $src_clevel_diskons,
            // "level_data"     => array(),
            "myMenuData"      => $myMenuData,
            // "ruleAkses"       => 1, // step1 tidak bleh step2 dan sebaliknya
            "ruleAkses"       => $this->config->item("ruleAkses"), // step1 tidak bleh step2 dan sebaliknya

            // "submit_button_target" => $this->modul . "/Transaksi/validate/",
        );
        //arrPrint($data);
        $this->load->view("setting", $data);
    }

    protected function getEmployee()
    {
        $this->load->model("Mdls/MdlEmployee");
        $emp = new MdlEmployee();
        /*
         * allow ghos ini untuk munculin hak akses ghos, karena belum ada mekanismenya
         */
        $allow_ghos = array(
            "192.168.5.1",
            "192.168.5.2",
            "192.168.5.3",
            "192.168.5.4",
            "192.168.5.7",
            // "202.65.117.80",
// MGK_LIVE,
        );
        if(in_array(ipadd(),$allow_ghos)){
            $emp->setFilters(array());
        }
//        cekHitam(ipadd());

        $employees = $emp->lookupAll()->result_array();

        return $employees;
    }

    protected function getEmployeeMenu()
    {
        $employee_id = my_id();
        $menuDataTersedia = $this->config->item('heDataBehaviour');
        $mdlTersedia = array_keys($menuDataTersedia);
        //data
        $this->load->model("Mdls/MdlDataAccessRight");
        $md = new MdlDataAccessRight();
        // $md->addFilter("employee_id = $employee_id");
        $srcMd = $md->lookupAll()->result();
        // showLast_query("merah");
        // cekMerah(count($srcMd));
        // arrPrintKuning($srcMd);
        $srcMd_employee = array();
        foreach ($srcMd as $item) {
            $steps_code = $item->steps;
            $mdl = $item->mdl_name;

            if (in_array($mdl, $mdlTersedia)) {
                $srcMd_employee[$item->employee_id][$mdl][] = $steps_code;
            }
        }

        //transaksi
        $this->load->model("Mdls/MdlAccessRight");
        $mt = new MdlAccessRight();
        // $mt->addFilter("employee_id = $employee_id");
        $srcMt = $mt->lookupActive()->result();
        // showLast_query("kuning");
        // cekKuning(count($srcMt));
        // arrPrintKuning($srcMt);
        $srcMt_employee = array();
        foreach ($srcMt as $item) {
            $steps_code = $item->steps;
            $crud_id = $item->crud_id;
            $srcMt_employee[$item->employee_id][$item->menu_category][$steps_code][] = $crud_id;
        }
        // arrPrintHijau($srcMt_employee);

        //bebas/laporan/other
        $this->load->model("Mdls/MdlOtherAccessRight");
        $mb = new MdlOtherAccessRight();

        // $mb->addFilter("employee_id = $employee_id");
        $srcMb = $mb->lookupActive()->result();
        // showLast_query("merah");
        // cekMerah(count($srcMb));
        // arrPrintPink($srcMb);
        $srcMb_employee = array();
        foreach ($srcMb as $item) {
            $steps_code = $item->steps_code;
            // Prefer per_employee_id untuk konsistensi set_menu_bebas, fallback ke employee_id untuk data legacy.
            $employeeKey = 0;
            if (isset($item->per_employee_id) && (int)$item->per_employee_id > 0) {
                $employeeKey = (int)$item->per_employee_id;
            }
            elseif (isset($item->employee_id) && (int)$item->employee_id > 0) {
                $employeeKey = (int)$item->employee_id;
            }

            if ($employeeKey > 0) {
                $srcMb_employee[$employeeKey][] = $steps_code;
            }
        }

        $this->load->model("Mdls/MdlMenuGroupUi");
        $gu = new MdlMenuGroupUi();
        $heTransaksiGroup_uiDb = $gu->callGroupMenuTransaksiUi();
        // showLast_query("biru");
        // arrPrint($heTransaksiGroup_uiDb['data']);
        // arrPrintPink($heTransaksiGroup_uiDb['transaksi']);


        $vars = array();
        $vars["data_group"] = $heTransaksiGroup_uiDb['data'];
        $vars["transaksi_group"] = $heTransaksiGroup_uiDb['transaksi'];
        $vars["data"] = $srcMd_employee;
        $vars["transaksi"] = $srcMt_employee;
        $vars["bebas"] = $srcMb_employee;

        return $vars;
    }

    protected function buildMasterData($datas, $master_key)
    {

        foreach ($datas as $employee) {
            $mdIndek = $employee[$master_key];

            $masterData[$mdIndek] = $employee;
        }

        return $masterData;
    }

    public function setData()
    {
        arrPrintHijau($_GET);
        $employee_id = $_GET['id'];
        $mdl_name = $_GET['mdl'];
        $steps = $_GET['crud'];

        $this->load->model("Mdls/MdlMenuData");
        $dt = new MdlMenuData();

        $this->db->trans_begin();
        $condite = array(
            "employee_id" => $employee_id,
            "mdl_name"    => $mdl_name,
            "steps"       => $steps,
        );
        $cekDatas = $dt->lookupByCondition($condite)->result();
        showLast_query("kuning");
        arrPrintHijau($cekDatas);

        if (count($cekDatas) == 0) {
            $datas = array(
                "employee_id" => $employee_id,
                "mdl_name"    => $mdl_name,
                "steps"       => $steps,
                "oleh_id"     => my_id(),
            );
            $dt->addData($datas);
            showLast_query("kuning");
        }
        else {
            $id = $cekDatas[0]->id;
            $dataUpds = array(
                "trash"           => 1,
                "trash_dtime"     => 1,
                "trash_oleh_id"   => my_id(),
                "trash_oleh_nama" => my_name(),
            );
            $where = array(
                "id" => $id
            );
            $dt->updateData($where, $dataUpds);
            showLast_query("kuning");

            // $datas = array(
            //     "employee_id" => $employee_id,
            //     "mdl_name"    => $mdl_name,
            //     "steps"       => $steps,
            //     "oleh_id"       => my_id(),
            // );
            // $dt->addData($datas);
            // showLast_query("kuning");
        }


        $this->db->trans_complete();

    }

    public function setOther()
    {
        arrPrintHijau($_GET);
        $employee_id = $_GET['id'];
        $mdl_name = $_GET['mdl'];
        $steps = $_GET['crud'];

        $this->load->model("Mdls/MdlMenuBebas");
        $dt = new MdlMenuBebas();

        $this->db->trans_begin();
        $condite = array(
            "employee_id" => $employee_id,
            "steps_code"  => $mdl_name,
            // "steps"       => $steps,
            "trash"       => 0,
        );
        $cekDatas = $dt->lookupByCondition($condite)->result();
        showLast_query("kuning");
        arrPrintHijau($cekDatas);

        if (count($cekDatas) == 0) {
            $datas = array(
                "employee_id" => $employee_id,
                "steps_code"  => $mdl_name,
                // "steps"       => $steps,
                "author"      => my_id(),
                "dtime"       => dtimeNow(),
            );
            $dt->addData($datas);
            showLast_query("orange");
        }
        else {
            $id = $cekDatas[0]->id;
            $dataUpds = array(
                "trash"           => 1,
                "trash_dtime"     => 1,
                "trash_oleh_id"   => my_id(),
                "trash_oleh_nama" => my_name(),
            );
            $where = array(
                "id" => $id
            );
            $dt->updateData($where, $dataUpds);
            showLast_query("kuning");

            // $datas = array(
            //     "employee_id" => $employee_id,
            //     "mdl_name"    => $mdl_name,
            //     "steps"       => $steps,
            //     "oleh_id"       => my_id(),
            // );
            // $dt->addData($datas);
            // showLast_query("kuning");
        }


        $this->db->trans_complete();

    }

    public function setTransaksi()
    {
        arrPrintHijau($_GET);
        $employee_id = $_GET['id'];
        $mdl_name = $_GET['mdl'];
        $crud = $_GET['crud'];
        $steps = $_GET['step'];

        $this->load->model("Mdls/MdlMenu");
        $dt = new MdlMenu();

        $this->db->trans_begin();
        $condite = array(
            "employee_id"   => $employee_id,
            "steps_code"    => $mdl_name,
            "menu_category" => $mdl_name,
            "steps"         => $steps,
            "crud_id"       => $crud,
            "trash"         => 0,
        );
        $cekDatas = $dt->lookupByCondition($condite)->result();
        // showLast_query("kuning");
        // arrPrintHijau($cekDatas);

        if (count($cekDatas) == 0) {
            $datas = array(
                "employee_id"   => $employee_id,
                "steps_code"    => $mdl_name,
                "menu_category" => $mdl_name,
                "steps"         => $steps,
                "crud_id"       => $crud,
                "author"        => my_id(),
                "dtime"         => dtimeNow(),
            );
            $dt->addData($datas);
            showLast_query("orange");
        }
        else {
            $id = $cekDatas[0]->id;
            $dataUpds = array(
                "trash"           => 1,
                "trash_dtime"     => 1,
                "trash_oleh_id"   => my_id(),
                "trash_oleh_nama" => my_name(),
            );
            $where = array(
                "id" => $id
            );
            $dt->updateData($where, $dataUpds);
            showLast_query("kuning");

            // $datas = array(
            //     "employee_id" => $employee_id,
            //     "mdl_name"    => $mdl_name,
            //     "steps"       => $steps,
            //     "oleh_id"       => my_id(),
            // );
            // $dt->addData($datas);
            // showLast_query("kuning");
        }


        $this->db->trans_complete();

    }

    //---------------------------------------------------

    public function test()
    {

        // $this->load->model('penjualan/Coms/ComRekeningPenjualan1', 'model1');
        $path = "../../penjualan";
        $path = APPPATH . "modules/pembelian/models/Coms/";
        cekHere(scandir($path));
        $path2 = "../../pembelian/models/Coms/";
        $this->load->model($path2 . "ComJurnalPembelian", 'model1');
    }

    public function viewAplikasi()
    {

        /* ---------------------------------------
         * mendapatkan ppn tersetting
         * ------------------------------------------*/
        $this->load->model("Mdls/MdlCompany");
        $cp = new MdlCompany();
        // $masterData = $cp->lookupAll()->row();
        $masterData = $cp->lookupJoint();
        // showLast_query("kuning");
        // arrPrint($masterData);
        // edited by glg (22:05 WIB, 2025-12-23)
        // change: ambil setting global e-signature pembelian
        // technical rationale: kirim flag ke view untuk render toggle status saat ini
        $this->load->model("Mdls/MdlSetting");
        $st = new MdlSetting();
        $esignatureSetting = $st->getEsignatureSetting();
        $esignatureEnabled = ($esignatureSetting && isset($esignatureSetting->nilai)) ? (int)$esignatureSetting->nilai : 1;
        // edited by glg (22:30 WIB, 2025-12-23)
        // change: ambil setting global e-signature penjualan
        // technical rationale: kirim flag penjualan ke view untuk toggle terpisah
        $esignatureSettingPenjualan = $st->getEsignatureSettingPenjualan();
        $esignaturePenjualanEnabled = ($esignatureSettingPenjualan && isset($esignatureSettingPenjualan->nilai)) ? (int)$esignatureSettingPenjualan->nilai : 1;
        // edited by glg (11:32 WIB, 2025-12-23)
        // change: ambil setting global e-signature distribusi
        // technical rationale: kirim flag distribusi ke view untuk toggle terpisah
        $esignatureSettingDistribusi = $st->getEsignatureSettingDistribusi();
        $esignatureDistribusiEnabled = ($esignatureSettingDistribusi && isset($esignatureSettingDistribusi->nilai)) ? (int)$esignatureSettingDistribusi->nilai : 1;
        // edited by glg (13:10 WIB, 2025-12-23)
        // change: ambil setting global e-signature biaya
        // technical rationale: kirim flag biaya ke view untuk toggle terpisah
        $esignatureSettingBiaya = $st->getEsignatureSettingBiaya();
        $esignatureBiayaEnabled = ($esignatureSettingBiaya && isset($esignatureSettingBiaya->nilai)) ? (int)$esignatureSettingBiaya->nilai : 1;

        $data = array(
            "mode"           => "viewAplikasi",
            // "isMobile" => $isMob,
            "errMsg"         => $this->session->errMsg,
            "globalTemplate" => isset($globalTemplate) ? $globalTemplate : "",
            // "template"       => MODUL_TEMPLATE_PATH . $this->configUi[$jenisTr]["template"],
            // "title" => callMenuLabel_he_menu(),
            "title"          => "setting menu",
            "subTitle"       => "-",
            "arrHeaders"     => $this->headerProfile,
            "masterData"     => isset($masterData) ? $masterData : array(),
            "esignatureEnabled" => $esignatureEnabled,
            "esignaturePenjualanEnabled" => $esignaturePenjualanEnabled,
            "esignatureDistribusiEnabled" => $esignatureDistribusiEnabled,
            "esignatureBiayaEnabled" => $esignatureBiayaEnabled,
            // "grosir_header" => $grosir_header,
            // "grosir_data" => $src_dg,
            // "level_header" => $level_header,
            // "level_data" => $src_clevel_diskons,
            // "level_data"     => array(),
            // "jenisTransaksi" => $jenisTr,

            // "submit_button_target" => $this->modul . "/Transaksi/validate/",
        );
        //arrPrint($data);
        $this->load->view("setting", $data);
    }

    public function formCp()
    {
        arrPrint(url_segment());
        arrPrint($_GET);
        $fieldValue = $_GET['v'];
        $fieldId = $_GET['i'];
        $field = url_segment(4);
        $header = $this->headerProfile;

        switch ($field) {
            case "password":
                $forms = array(
                    "Old"    => form_password("old_$segment_3", "", "class='form-control' placeholder='old $segment_3' autocomplete='off' required"),
                    "New"    => form_password("new_$segment_3", "", "class='form-control' placeholder='new $segment_3' autocomplete='off' required"),
                    "Retype" => form_password("re_$segment_3", "", "class='form-control' placeholder='retype $segment_3' autocomplete='off' required"),
                );
                break;
            case "email":
                $forms = array(
                    "Old" => form_input("old_$field", "$fieldValue", "class='form-control' placeholder='old $field' required disabled"),
                    "New" => "<input type='email' name='new_$field' class='form-control' placeholder='new $field' autocomplete='off' required>",
                );
                break;
            case "nama":
            case "npwp":
            case "alamat":
            case "tlp":
            case "ppn_faktor":
            case "tlp_1":

                // $fieldValue_f = formatNomorTelepon($fieldValue);
                $fieldValue_f = ($fieldValue);
                $attr_input = isset($header[$field]['attr_input']) ? $header[$field]['attr_input'] : "";

                $forms = array(
                    "Old" => "<input type='text' disabled class='form-control' name='old_$field' placeholder='old $field' value='$fieldValue_f'>",
                    "New" => "<input required class='form-control' name='new_$field' $attr_input placeholder='new $field'>",
                );
                break;
            case "kelurahan":
            case "kecamatan":
            case "kabupaten":
            case "propinsi":
            case "kode_pos":
                $selector = "";
                $selector .= "<select id='propinsi' name='propinsi' class='form-control'>";
                $selector .= "<option value=''>pilih Propinsi</option>";
                $selector .= "</select>";

                $selector .= "<select id='kabupaten' name='kabupaten' class='form-control'>";
                $selector .= "<option value=''>pilih Kabupaten</option>";
                $selector .= "</select>";

                $selector .= "<select id='kecamatan' name='kecamatan' class='form-control'>";
                $selector .= "<option value=''>pilih Kecamatan</option>";
                $selector .= "</select>";

                $selector .= "<select id='kelurahan' name='kelurahan' class='form-control'>";
                $selector .= "<option value=''>pilih Kelurahan</option>";
                $selector .= "</select>";

                $selector .= "<select id='postal' name='kode_pos' class='form-control'>";
                $selector .= "<option value=''>pilih kode pos</option>";
                $selector .= "</select>";

                $forms = array(
                    "Old" => "<input type='text' disabled class='form-control' name='old_$field' placeholder='old $field' value='$fieldValue'>",
                    "New" => "$selector",
                );
                break;
            default:
                $forms = "";
                mati_disini(__LINE__ . " " . __FILE__);
                break;
        }

        $data = array(
            "mode"          => "modal",
            "field"         => $field,
            // "template"       => $this->config->item("heTransaksi_layout")[$jenisTr]["receiptTemplate"][$currentStepNum],
            "template"      => "application/template/profile.html",
            "heading"       => "Edit " . $field,
            "forms"         => $forms,
            // "footer"   => form_submit("submit", "Save", "class='btn btn-primary pull-right'"),
            "target"        => "result",
            "actions"       => MODUL_PATH . get_class($this) . "/doSaveCp/$field/$fieldId",
            "propinsiData"  => MODUL_PATH . get_class($this) . "/getdata/propinsi",
            "kabupatenData" => MODUL_PATH . get_class($this) . "/getdata/kabupaten",
            "kecamatanData" => MODUL_PATH . get_class($this) . "/getdata/kecamatan",
            "kelurahanData" => MODUL_PATH . get_class($this) . "/getdata/kelurahan",
            "postalData"    => MODUL_PATH . get_class($this) . "/getdata/kode_pos",
            // "arrActivitylog" => $arrActivitylog,
            "headTpl"       => headTpl(),
            "footTpl"       => footTpl(),
        );
        $this->load->view("setting", $data);
    }

    public function getdata()
    {
        arrPrint(url_segment());
        $yg_dicari = url_segment(4);
        $propinsi_id = url_segment(5);
        $this->load->model("Mdls/MdlPostalCodes");
        $pc = new MdlPostalCodes();

        $key_id = $yg_dicari . "_id";
        // $key_id = $yg_dicari;
        $key_nama = $yg_dicari;

        switch ($yg_dicari) {
            case "propinsi":
                // $key_id = $yg_dicari ."_id";
                // $key_nama = $yg_dicari ."_nama";
                $this->db->group_by('propinsi_id');
                break;
            case "kabupaten":
                // $key_id = "kabupaten_id";
                $where = array(
                    "propinsi_id" => $propinsi_id
                );
                $this->db->where($where);
                $this->db->group_by('kabupaten_id');
                break;
            case "kecamatan":
                $where = array(
                    "kabupaten_id" => $propinsi_id
                );
                $this->db->where($where);
                $this->db->group_by('kecamatan_id');
                break;
            case "kelurahan":
                $where = array(
                    "kecamatan_id" => $propinsi_id
                );
                $this->db->where($where);
                $this->db->group_by('kelurahan_id');
                break;
            case "kode_pos":
                $key_id = 'postal_code';
                $key_nama = 'postal_code';
                $where = array(
                    "kecamatan_id" => $propinsi_id
                );
                $this->db->where($where);
                $this->db->group_by('postal_code');
                break;
        }

        $srcs = $pc->lookupAll()->result();
        showLast_query("biru");
        // cekHere(count($srcs));
        // arrPrint($srcs);

        foreach ($srcs as $src) {
            $data['id'] = $src->$key_id;
            $data['nama'] = $src->$key_nama;
            $dataOptions[] = $data;
        }
        $json = json_encode($dataOptions);
        // arrPrint($json);

        // header('Content-Type: application/json');
        echo $json;
    }

    public function doSaveCp()
    {
        arrPrint(url_segment());
        arrPrint($_POST);
        $field = url_segment(4);
        $fieldId = url_segment(5);

        $header = $this->headerProfile;

        $this->load->model("Mdls/MdlCompany");
        $cp = new MdlCompany();
        $postField = "new_$field";

        $data = $_POST;
        unset($data['submit']);
        if (isset($_POST[$postField])) {
            unset($data[$postField]);
            $data[$field] = $_POST[$postField];
        }

        $where['id'] = $fieldId;

        $cp->updateData($where, $data);
        showLast_query("merah");

        // echo lgShowSuccess("ok", "data berhasil diupdate");

        echo "<script>
                top.Swal.fire({
                                title: 'Berhasil',
                                text: 'Data berhasil disimpan.',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
            </script>";

    }

    // edited by glg (22:05 WIB, 2025-12-23)
    // change: endpoint simpan setting global e-signature pembelian
    // technical rationale: update flag on/off melalui model tanpa ubah logika bisnis lain
    public function doSaveEsignature()
    {
        $enabled = $this->input->post('esignature_enabled');
        $enabled = ((int)$enabled === 1) ? 1 : 0;
        // edited by glg (22:30 WIB, 2025-12-23)
        // change: tambahkan parameter modul untuk toggle e-signature
        // technical rationale: bedakan setting pembelian vs penjualan dengan whitelist modul
        $module = $this->input->post('esignature_module');
        $module = is_string($module) ? strtolower(trim($module)) : "pembelian";
        // edited by glg (13:10 WIB, 2025-12-23)
        // change: tambahkan modul biaya ke whitelist toggle
        // technical rationale: izinkan on/off e-signature biaya via UI
        $allowedModules = array("pembelian", "penjualan", "distribusi", "biaya");
        if (!in_array($module, $allowedModules, true)) {
            echo "INVALID";
            return;
        }

        $this->load->model("Mdls/MdlSetting");
        $st = new MdlSetting();
        if ($module === "penjualan") {
            $st->updateEsignatureSettingPenjualan($enabled);
        }
        elseif ($module === "distribusi") {
            $st->updateEsignatureSettingDistribusi($enabled);
        }
        elseif ($module === "biaya") {
            $st->updateEsignatureSettingBiaya($enabled);
        }
        else {
            $st->updateEsignatureSetting($enabled);
        }

        echo "OK";
    }
    // ----------------------------------------------------

    public function myProfile()
    {
        // $className = "Mdl" . $this->uri->segment(3);
        // $ctrlName = $this->uri->segment(3);
        // cekHEre($className);
        $this->load->model("Mdls/MdlUser");
        $pro = new MdlUser();
        $log = new MdlActivityLog();
        $pro->setFilters(array());
        $arrProfile = $pro->lookupByID(my_id())->result()[0];
        // cekHere($this->db->last_query());
        // arrPrint($ss);
        // $arrProfile = array(
        //     "nama" => "Nina"
        // );
        // $a

        $updateFields = $pro->getListedUpdateFields();
        // arrPrint($updateFields);

        // matiHere();
        $condite = "uid='" . my_id() . "' order by id desc limit 10";
        $arrActivitylog = $log->lookupByCondition($condite)->result();
        $arrayListed = $log->getListedFields();
        //        arrPrint($arrayListed);
        $blackList = array("uname", "title", "sub_title", "method", "url");
        $arrHeader = array_diff_key($arrayListed, array_flip($blackList));

        $data = array(
            "mode"           => "myProfile",
            "template"       => "template/profile.html",
            "title"          => "Profile",
            "subTitle"       => "",
            "updateFields"   => $updateFields,
            "arrProfile"     => $arrProfile,
            "arrActivitylog" => $arrActivitylog,
            "arrayHeader"    => $arrHeader,
            "headTpl"        => "",
            "footTpl"        => footTpl(),
        );

        $this->load->view("data", $data);
    }

    public function editone()
    {
        $pro = new MdlUser();
        $pro->setFilters(array());
        $arrProfile = $pro->lookupByID(my_id())->result()[0];
        // cekHere($this->db->last_query());
        $arrField = $pro->getFields();
        $arrKolom = array();
        $arrKolom_alias = array();
        foreach ($arrField as $arrItem) {
            $arrKolom[] = $arrItem['kolom'];
            $arrKolom_alias[$arrItem['kolom']] = $arrItem['label'];
        }
        foreach ($arrKolom as $kolom) {
            $$kolom = $arrProfile->$kolom;
        }
        // arrPrint($arrField);
        $segment_3 = $this->uri->segment(4);
        switch ($segment_3) {
            case "password":
                $forms = array(
                    "Old"    => form_password("old_$segment_3", "", "class='form-control' placeholder='old $segment_3' autocomplete='off' required"),
                    "New"    => form_password("new_$segment_3", "", "class='form-control' placeholder='new $segment_3' autocomplete='off' required"),
                    "Retype" => form_password("re_$segment_3", "", "class='form-control' placeholder='retype $segment_3' autocomplete='off' required"),
                );
                break;
            case "email":
                $forms = array(
                    "Old" => form_input("old_$segment_3", "$email", "class='form-control' placeholder='old $segment_3' required disabled"),
                    "New" => "<input type='email' name='new_$segment_3' class='form-control' placeholder='new $segment_3' autocomplete='off' required>",
                );
                break;
            case "tlp_1":
                $forms = array(
                    "Old" => "<input type='text' disabled class='form-control' name='old_$segment_3' placeholder='old $segment_3' value='$tlp_1'>",
                    "New" => "<input required class='form-control' name='new_$segment_3' placeholder='new $segment_3'>",
                );
                break;
            default:
                $forms = "";
                mati_disini(__LINE__ . " " . __FILE__);
                break;
        }

        $data = array(
            "mode"     => "modal",
            "field"    => $segment_3,
            // "template"       => $this->config->item("heTransaksi_layout")[$jenisTr]["receiptTemplate"][$currentStepNum],
            "template" => "application/template/profile.html",
            "heading"  => "Edit " . $arrKolom_alias[$segment_3],
            "forms"    => $forms,
            "footer"   => form_submit("submit", "Save", "class='btn btn-primary pull-right'"),
            "target"   => "result",
            "actions"  => "/Data/editoneProcess/User",
            // "arrActivitylog" => $arrActivitylog,
            "headTpl"  => headTpl(),
            "footTpl"  => footTpl(),
        );
        $this->load->view("data", $data);
    }

    public function editoneProcess()
    {
        // arrPrint($_REQUEST);
        $id = my_id();
        arrPrint($this->input->post());
        $new_tlp_1 = $this->input->post('new_tlp_1');
        $old_password = $this->input->post('old_password');
        $new_password = $this->input->post('new_password');
        $new_email = $this->input->post('new_email');
        $re_password = $this->input->post('re_password');
        $field = $this->input->post('field');

        // cekHijau("$password != md5($old_password)");
        $pro = new MdlUser();
        $arrProfile = $pro->lookupByID(my_id())->result()[0];
        $arrField = $pro->getFields();
        foreach ($arrField as $kolom => $property) {

            $arrKolom[] = $kolom;
        }
        //        arrPrint($arrKolom);
        $arrKolom_alias = array();
        foreach ($arrField as $arrItem) {
            $arrKolom[] = $arrItem['kolom'];
            $arrKolom_alias[$arrItem['kolom']] = $arrItem['label'];
        }
        foreach ($arrKolom as $kolom) {
            $$kolom = $arrProfile->$kolom;
        }

        $this->db->trans_start();

        switch ($field) {
            case "password":
                if ($password != md5($old_password)) {
                    cekBiru($password . " " . md5($old_password));
                    $msg = "You not enter the right password<br>please re enter your <b>current password</b>";
                    echo lgShowAlert("", $msg);
                    matiHere($msg);

                }
                elseif ($new_password != $re_password) {
                    $msg = "your confirmation password is not match<br>please retype your new password";
                    echo lgShowError("", $msg);
                    matiHere($msg);
                }
                else {
                    echo lgShowSuccess("sip", "processing your request");

                    $this->db->trans_start();
                    $arrData = array(
                        "password" => md5($re_password),
                    );
                    $pro->updateData(array('id' => $id), $arrData);

                    $this->db->trans_complete();

                    // echo lgShowSuccess("success", "your password has been changed successfully done");
                    // echo lgShowSuccess("success", "your " . $arrKolom_alias[$field] . " has been changed successfully done");
                    // topReload(700);
                }
                break;
            case "email":
                $this->db->trans_start();
                $arrData = array(
                    "email" => $new_email,
                );
                $pro->updateData(array('id' => $id), $arrData);

                $this->db->trans_complete();

                // echo lgShowSuccess("success", "your " . $arrKolom_alias[$field] . " has been changed successfully done");
                // topReload(700);
                break;
            case "tlp_1":
                $this->db->trans_start();
                $arrData = array(
                    $field => $new_tlp_1,
                );
                $pro->updateData(array('id' => $id), $arrData);

                $this->db->trans_complete();

                // echo lgShowSuccess("success", "your " . $arrKolom_alias[$field] . " has been changed successfully done");
                // topReload(700);
                break;
            default:
                mati_disini(__LINE__ . " " . __FILE__ . " field::" . $field);
                break;
        }
        //        cekHere($this->db->last_query());

        // matiHere(__METHOD__ . " @" . __LINE__);
        $this->db->trans_complete();
        echo lgShowSuccess("success", "your " . $arrKolom_alias[$field] . " has been changed successfully done");
        topReload(500);


    }

    // edited by glg (20:02 WIB, 2025-12-22)
    // change: sinkronkan nama field upload signature dengan kolom DB
    // technical rationale: memastikan update ke per_employee.esignature_img
    public function uploadSignature()
    {
        $fileKey = "esignature_img";
        if (!isset($_FILES[$fileKey]) || $_FILES[$fileKey]['size'] <= 0) {
            echo "<script>top.Swal.fire('error', 'File signature wajib diupload.', 'error');</script>";
            return;
        }

        if (isset($_FILES[$fileKey]['type']) && strpos($_FILES[$fileKey]['type'], 'image/') !== 0) {
            echo "<script>top.Swal.fire('error', 'Format file harus image.', 'error');</script>";
            return;
        }

        $this->load->helper('he_url');
        $uploadResult = upload_image($_FILES[$fileKey]);

        if (!isset($uploadResult->status) || $uploadResult->status != 'success' || !isset($uploadResult->full_url)) {
            echo "<script>top.Swal.fire('error', 'image tidak valid, coba untuk ganti gambar yang akan diupload', 'error');</script>";
            return;
        }

        $this->load->model("Mdls/MdlUser");
        $pro = new MdlUser();

        $this->db->trans_start();
        // edited by glg (19:53 WIB, 2025-12-22)
        // change: gunakan method model untuk update signature
        // technical rationale: query update dipindahkan ke MdlUser
        $pro->updateEsignatureImg(my_id(), $uploadResult->full_url);
        $this->db->trans_complete();

        echo "<script>top.Swal.fire({title:'Berhasil', text:'Signature berhasil diupload.', icon:'success', timer:2000, showConfirmButton:false});</script>";
        echo "<script>topReload(500);</script>";
    }

    // edited by glg (12:50 WIB, 2025-12-15)
    // change: Endpoint baru untuk get employee permission via AJAX (modal view)
    // technical rationale: Untuk menampilkan hak akses employee saat klik nama di tabel
    public function getEmployeePermissionModal()
    {
        header('Content-Type: application/json');
        $employee_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if ($employee_id == 0) {
            echo json_encode(['error' => self::ERROR_MSG_EMPLOYEE_ID_REQUIRED]);
            return;
        }

        // Get employee info
        $this->load->model("Mdls/MdlEmployee");
        $emp = new MdlEmployee();
        $employee = $emp->lookupById($employee_id)->row();

        if (!$employee) {
            echo json_encode(['error' => self::ERROR_MSG_EMPLOYEE_NOT_FOUND]);
            return;
        }

        // Get all permissions using existing function
        $allPerms = $this->getEmployeeMenu();

        // Filter for this employee
        $dataPerms = isset($allPerms['data'][$employee_id]) ? $allPerms['data'][$employee_id] : [];
        $transaksiPerms = isset($allPerms['transaksi'][$employee_id]) ? $allPerms['transaksi'][$employee_id] : [];
        $otherPerms = isset($allPerms['bebas'][$employee_id]) ? $allPerms['bebas'][$employee_id] : [];

        // edited by glg (13:30 WIB, 2025-12-15)
        // change: Tambah grouping data seperti di Setting Menu (BIAYA, SUPPLIES, BANKING, dll)
        // technical rationale: User request tampilan seperti header di setting menu dengan grouping
        $dataGroups = isset($allPerms['data_group']) ? $allPerms['data_group'] : [];
        $transaksiGroups = isset($allPerms['transaksi_group']) ? $allPerms['transaksi_group'] : [];

        // Build readable permission lists with grouping
        $result = [
            'employee_id' => $employee_id,
            'employee_name' => isset($employee->nama) ? $employee->nama : '-',
            'data' => $this->buildPermissionDataGrouped($dataPerms, $dataGroups),
            'transaksi' => $this->buildPermissionTransaksiGrouped($transaksiPerms, $transaksiGroups),
            'other' => $this->buildPermissionOther($otherPerms)
        ];

        echo json_encode($result);
    }

    // edited by antigravity (2026-07-01)
    // change: Add employee_permission_view endpoint to support Option A (server-side rendering in BootstrapDialog)
    public function employee_permission_view($employee_id = 0)
    {
        $employee_id = intval($employee_id);
        if ($employee_id == 0) {
            echo "<div class='alert alert-danger'>Employee ID tidak valid</div>";
            return;
        }

        // Get employee info
        $this->load->model("Mdls/MdlEmployee");
        $emp = new MdlEmployee();
        $employee = $emp->lookupById($employee_id)->row();

        if (!$employee) {
            echo "<div class='alert alert-danger'>Employee tidak ditemukan</div>";
            return;
        }

        // Get all permissions
        $allPerms = $this->getEmployeeMenu();

        // Get employee branch memberships
        $this->load->model("Mdls/MdlEmployeeMembershipCabang");
        $memCabang = new MdlEmployeeMembershipCabang();
        $branchMemberships = $memCabang->getMember($employee_id);
        $branches = [];
        if (isset($branchMemberships[$employee_id]) && is_array($branchMemberships[$employee_id])) {
            foreach ($branchMemberships[$employee_id] as $membership) {
                if (isset($membership->cabang_nama) && !empty($membership->cabang_nama)) {
                    $branches[] = $membership->cabang_nama;
                }
            }
        }
        $branches = array_unique($branches);

        // Filter for this employee
        $dataPerms = isset($allPerms['data'][$employee_id]) ? $allPerms['data'][$employee_id] : [];
        $transaksiPerms = isset($allPerms['transaksi'][$employee_id]) ? $allPerms['transaksi'][$employee_id] : [];
        $otherPerms = isset($allPerms['bebas'][$employee_id]) ? $allPerms['bebas'][$employee_id] : [];

        $dataGroups = isset($allPerms['data_group']) ? $allPerms['data_group'] : [];
        $transaksiGroups = isset($allPerms['transaksi_group']) ? $allPerms['transaksi_group'] : [];

        $data = [
            'employee_id' => $employee_id,
            'employee_name' => isset($employee->nama) ? $employee->nama : '-',
            'employee_login_name' => isset($employee->nama_login) ? $employee->nama_login : '-',
            'branches' => $branches,
            'data' => $this->buildPermissionDataGrouped($dataPerms, $dataGroups),
            'transaksi' => $this->buildPermissionTransaksiGrouped($transaksiPerms, $transaksiGroups),
            'other' => $this->buildPermissionOther($otherPerms)
        ];

        $this->load->view('form_employee_permission_body', $data);
    }

    public function getHeaderAccessModal()
    {
        header('Content-Type: application/json');
        $jenis = isset($_GET['jenis']) ? strtolower(trim($_GET['jenis'])) : "";
        $module = isset($_GET['module']) ? trim($_GET['module']) : "";

        if ($jenis === "" || $module === "") {
            echo json_encode(['error' => 'Jenis dan module wajib diisi']);
            return;
        }

        $employees = $this->getEmployee();
        $masterData = $this->buildMasterData($employees, "id");
        $allPerms = $this->getEmployeeMenu();
        $resolvedNames = [];

        $rows = [];
        $moduleLabel = $module;

        if ($jenis === "data") {
            $menuDataTersedia = $this->config->item('heDataBehaviour');
            if (isset($menuDataTersedia[$module]['label'])) {
                $moduleLabel = $menuDataTersedia[$module]['label'];
            }

            $stepMapping = self::STEP_MAPPING_DATA;
            $dataPerms = isset($allPerms['data']) ? $allPerms['data'] : [];
            foreach ($dataPerms as $employeeId => $mdlPerms) {
                if (!isset($mdlPerms[$module])) {
                    continue;
                }
                $steps = $mdlPerms[$module];
                $permissions = [];
                foreach ($steps as $step) {
                    if (isset($stepMapping[$step])) {
                        $permissions[] = $stepMapping[$step];
                    }
                }
                $permissions = array_values(array_unique($permissions));
                if (empty($permissions)) {
                    continue;
                }

                if (!isset($resolvedNames[$employeeId])) {
                    $resolvedNames[$employeeId] = $this->resolveEmployeeName($employeeId, $masterData);
                }
                $employeeName = $resolvedNames[$employeeId];
                $rows[] = [
                    'employee_id' => $employeeId,
                    'employee_name' => $employeeName,
                    'permissions' => $permissions,
                ];
            }
        }
        elseif ($jenis === "transaksi") {
            $menuTransaksiTersedia = $this->config->item('heTransaksi_ui');
            if (isset($menuTransaksiTersedia[$module]['label'])) {
                $moduleLabel = $menuTransaksiTersedia[$module]['label'];
            }

            $stepMapping = self::STEP_MAPPING_TRANSAKSI;
            $transaksiPerms = isset($allPerms['transaksi']) ? $allPerms['transaksi'] : [];

            foreach ($transaksiPerms as $employeeId => $menuPerms) {
                if (!isset($menuPerms[$module])) {
                    continue;
                }
                foreach ($menuPerms[$module] as $step => $crudIds) {
                    $permissions = [];
                    foreach ($crudIds as $crudId) {
                        if (isset($stepMapping[$crudId])) {
                            $permissions[] = $stepMapping[$crudId];
                        }
                    }
                    $permissions = array_values(array_unique($permissions));
                    if (empty($permissions)) {
                        continue;
                    }

                    $stepLabel = $step;
                    if (isset($menuTransaksiTersedia[$module]['steps'][$step]['label'])) {
                        $stepLabel = $menuTransaksiTersedia[$module]['steps'][$step]['label'];
                    }

                    if (!isset($resolvedNames[$employeeId])) {
                        $resolvedNames[$employeeId] = $this->resolveEmployeeName($employeeId, $masterData);
                    }
                    $employeeName = $resolvedNames[$employeeId];
                    $rows[] = [
                        'employee_id' => $employeeId,
                        'employee_name' => $employeeName,
                        'step' => $step,
                        'step_label' => $stepLabel,
                        'permissions' => $permissions,
                    ];
                }
            }
        }
        elseif ($jenis === "other") {
            $menuBebasTersedia = $this->config->item('groupMenu');
            foreach ($menuBebasTersedia as $group) {
                if (!isset($group['availMenu']) || !is_array($group['availMenu'])) {
                    continue;
                }
                if (isset($group['availMenu'][$module]['label'])) {
                    $moduleLabel = $group['availMenu'][$module]['label'];
                    break;
                }
            }

            $otherPerms = isset($allPerms['bebas']) ? $allPerms['bebas'] : [];
            foreach ($otherPerms as $employeeId => $steps) {
                if (!in_array($module, $steps, true)) {
                    continue;
                }
                if (!isset($resolvedNames[$employeeId])) {
                    $resolvedNames[$employeeId] = $this->resolveEmployeeName($employeeId, $masterData);
                }
                $employeeName = $resolvedNames[$employeeId];
                $rows[] = [
                    'employee_id' => $employeeId,
                    'employee_name' => $employeeName,
                    'permissions' => ['ACCESS'],
                ];
            }
        }
        else {
            echo json_encode(['error' => 'Jenis tidak dikenal']);
            return;
        }

        echo json_encode([
            'jenis' => $jenis,
            'module' => $module,
            'module_label' => $moduleLabel,
            'rows' => $rows,
        ]);
    }

    private function resolveEmployeeName($employeeId, $masterData)
    {
        if (isset($masterData[$employeeId]['nama']) && $masterData[$employeeId]['nama'] !== "") {
            return $masterData[$employeeId]['nama'];
        }

        $row = $this->db->select('nama')
            ->from('per_employee')
            ->where('id', (int)$employeeId)
            ->limit(1)
            ->get()
            ->row();

        if ($row && isset($row->nama) && $row->nama !== "") {
            return $row->nama;
        }

        return "ID:$employeeId";
    }

    private function buildPermissionDataGrouped($perms, $groups)
    {
        if (empty($perms)) {
            return [];
        }

        $menuDataTersedia = $this->config->item('heDataBehaviour');
        $result = [];

        // edited by glg (17:02 WIB, 2025-01-23)
        // change: menggunakan class constant untuk step mapping
        $stepMapping = self::STEP_MAPPING_DATA;

        // Build map: mdlName => groupName
        // edited by glg (16:10 WIB, 2025-01-23)
        // change: memperbaiki key dari 'mdls' menjadi 'heTransaksi_ui' sesuai struktur data dari callGroupMenuTransaksiUi()
        $mdlToGroup = [];
        foreach ($groups as $groupKey => $groupData) {
            if (isset($groupData['heTransaksi_ui']) && is_array($groupData['heTransaksi_ui'])) {
                foreach ($groupData['heTransaksi_ui'] as $mdlName) {
                    $mdlToGroup[$mdlName] = [
                        'key' => $groupKey,
                        'label' => isset($groupData['label']) ? $groupData['label'] : $groupKey
                    ];
                }
            }
        }

        // Group permissions
        foreach ($perms as $mdlName => $steps) {
            $label = isset($menuDataTersedia[$mdlName]['label']) ? $menuDataTersedia[$mdlName]['label'] : $mdlName;
            $permissions = [];

            foreach ($steps as $step) {
                if (isset($stepMapping[$step])) {
                    $permissions[] = $stepMapping[$step];
                }
            }

            if (!empty($permissions)) {
                // Get group
                if (isset($mdlToGroup[$mdlName])) {
                    $groupLabel = $mdlToGroup[$mdlName]['label'];
                    if (!isset($result[$groupLabel])) {
                        $result[$groupLabel] = [];
                    }
                    $result[$groupLabel][$label] = $permissions;
                } else {
                    // Ungrouped
                    if (!isset($result[self::UNGROUPED_LABEL])) {
                        $result[self::UNGROUPED_LABEL] = [];
                    }
                    $result[self::UNGROUPED_LABEL][$label] = $permissions;
                }
            }
        }

        return $result;
    }

    private function buildPermissionTransaksiGrouped($perms, $groups)
    {
        if (empty($perms)) {
            return [];
        }

        $menuTransaksiTersedia = $this->config->item('heTransaksi_ui');
        $result = [];

        // edited by glg (17:02 WIB, 2025-01-23)
        // change: menggunakan class constant untuk step mapping transaksi
        $stepMapping = self::STEP_MAPPING_TRANSAKSI;

        // Build map: menu_category => groupName
        $categoryToGroup = [];
        foreach ($groups as $groupKey => $groupData) {
            if (isset($groupData['heTransaksi_ui']) && is_array($groupData['heTransaksi_ui'])) {
                foreach ($groupData['heTransaksi_ui'] as $menuCategory) {
                    $categoryToGroup[$menuCategory] = [
                        'key' => $groupKey,
                        'label' => isset($groupData['label']) ? $groupData['label'] : $groupKey
                    ];
                }
            }
        }

        // Iterate: $perms[$menu_category][$stepCode][] = $crudIds
        foreach ($perms as $menuCategory => $stepsData) {
            foreach ($stepsData as $stepCode => $crudIds) {
                $permissions = [];

                if (isset($stepMapping[$stepCode])) {
                    $permissions[] = $stepMapping[$stepCode];
                }

                if (!empty($permissions)) {
                    // Get label from config
                    $label = isset($menuTransaksiTersedia[$menuCategory]['label'])
                        ? $menuTransaksiTersedia[$menuCategory]['label']
                        : $menuCategory;

                    // Get group
                    if (isset($categoryToGroup[$menuCategory])) {
                        $groupLabel = $categoryToGroup[$menuCategory]['label'];
                        if (!isset($result[$groupLabel])) {
                            $result[$groupLabel] = [];
                        }
                        if (!isset($result[$groupLabel][$label])) {
                            $result[$groupLabel][$label] = [];
                        }
                        $result[$groupLabel][$label] = array_unique(array_merge($result[$groupLabel][$label], $permissions));
                    } else {
                        // Ungrouped
                        if (!isset($result[self::UNGROUPED_LABEL])) {
                            $result[self::UNGROUPED_LABEL] = [];
                        }
                        if (!isset($result[self::UNGROUPED_LABEL][$label])) {
                            $result[self::UNGROUPED_LABEL][$label] = [];
                        }
                        $result[self::UNGROUPED_LABEL][$label] = array_unique(array_merge($result[self::UNGROUPED_LABEL][$label], $permissions));
                    }
                }
            }
        }

        return $result;
    }

    private function buildPermissionOther($perms)
    {
        if (empty($perms)) {
            return [];
        }

        $result = [];

        foreach ($perms as $stepCode) {
            // Step code adalah nama permission langsung
            $label = str_replace('_', ' ', $stepCode);
            $label = ucwords($label);
            $result[$label] = ['ACCESS'];
        }

        return $result;
    }
}
