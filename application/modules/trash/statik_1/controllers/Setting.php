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
        // $menuDataTersedia = $this->config->item('heDataBehaviour');
        $menuBebasTersedia = $this->config->item('groupMenu');
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
                "label" => "nama"
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
        $myMenuData = $myMenu['data'];
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

            // $arrDataGroup["attr"] = "class='bg-danger'";

            $arrHeadersGroup[$group_key] = $myMenuDatum + $arrDataGroup;
        }

        // arrPrintKuning($arrHeadersGroup);

        $arrHeaders = array(
            "nama" => array(
                "label"       => "nama",
                "attr_header" => "class='sticky-col first-col'  style='background-color: #d9edf7;'",
                "attr"        => "class='sticky-col first-col'",
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
            "nama" => array(
                "label" => "nama"
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
            "mode"           => "viewTransaksi",
            // "isMobile" => $isMob,
            "errMsg"         => $this->session->errMsg,
            "globalTemplate" => isset($globalTemplate) ? $globalTemplate : "",
            // "template"       => MODUL_TEMPLATE_PATH . $this->configUi[$jenisTr]["template"],
            // "title" => callMenuLabel_he_menu(),
            "title"          => "setting menu",
            "subTitle"       => "-",
            "arrHeadersGroup" => $arrHeadersGroup,
            "arrHeaders"     => $arrHeaders,
            "masterData"     => isset($masterData) ? $masterData : array(),
            "childData"      => $childData,
            "childs"         => $childs,
            // "level_header" => $level_header,
            // "level_data" => $src_clevel_diskons,
            // "level_data"     => array(),
            "myMenuData"     => $myMenuData,

            // "submit_button_target" => $this->modul . "/Transaksi/validate/",
        );
        //arrPrint($data);
        $this->load->view("setting", $data);
    }

    protected function getEmployee()
    {
        $this->load->model("Mdls/MdlEmployee");
        $emp = new MdlEmployee();

        $employees = $emp->lookupAll()->result_array();

        return $employees;
    }

    protected function getEmployeeMenu()
    {
        $employee_id = my_id();
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
            $srcMd_employee[$item->employee_id][$item->mdl_name][] = $steps_code;
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
            $srcMb_employee[$item->employee_id][] = $steps_code;
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
                "oleh_id"       => my_id(),
            );
            $dt->addData($datas);
            showLast_query("kuning");
        }
        else{
            $id = $cekDatas[0]->id;
            $dataUpds = array(
                "trash" => 1,
                "trash_dtime" => 1,
                "trash_oleh_id" => my_id(),
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
            "steps_code"    => $mdl_name,
            // "steps"       => $steps,
            "trash"       => 0,
        );
        $cekDatas = $dt->lookupByCondition($condite)->result();
        showLast_query("kuning");
        arrPrintHijau($cekDatas);

        if (count($cekDatas) == 0) {
            $datas = array(
                "employee_id" => $employee_id,
                "steps_code"    => $mdl_name,
                // "steps"       => $steps,
                "author"       => my_id(),
                "dtime"       => dtimeNow(),
            );
            $dt->addData($datas);
            showLast_query("orange");
        }
        else{
            $id = $cekDatas[0]->id;
            $dataUpds = array(
                "trash" => 1,
                "trash_dtime" => 1,
                "trash_oleh_id" => my_id(),
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
            "employee_id" => $employee_id,
            "steps_code"    => $mdl_name,
            "menu_category"    => $mdl_name,
            "steps"       => $steps,
            "crud_id"       => $crud,
            "trash"       => 0,
        );
        $cekDatas = $dt->lookupByCondition($condite)->result();
        // showLast_query("kuning");
        // arrPrintHijau($cekDatas);

        if (count($cekDatas) == 0) {
            $datas = array(
                "employee_id" => $employee_id,
                "steps_code"    => $mdl_name,
                "menu_category"    => $mdl_name,
                "steps"       => $steps,
                "crud_id"       => $crud,
                "author"       => my_id(),
                "dtime"       => dtimeNow(),
            );
            $dt->addData($datas);
            showLast_query("orange");
        }
        else{
            $id = $cekDatas[0]->id;
            $dataUpds = array(
                "trash" => 1,
                "trash_dtime" => 1,
                "trash_oleh_id" => my_id(),
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

    public function test(){

        // $this->load->model('penjualan/Coms/ComRekeningPenjualan1', 'model1');
        $path = "../../penjualan";
        $path = APPPATH. "modules/pembelian/models/Coms/";
        cekHere(scandir($path));
        $path2 = "../../pembelian/models/Coms/";
        $this->load->model($path2."ComJurnalPembelian", 'model1');
    }
}
