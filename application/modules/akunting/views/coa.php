<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 8/16/2018
 * Time: 8:51 PM
 */


switch ($mode) {

    case "coa":
        // $this->load->model(array(
        //     'Mdls/MdlAccounts',
        //     // 'Web_settings'
        // ));
        //
        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH ."template/coa.html");
        $var = "<link rel='stylesheet' href='" . base_url() . "assets/custom/style.min.css' />";

        $var .= "<div class='box box-info'>";
        $var .= "<div class='box-body'>";
        $var .= "<div class='row'>";
        $var .= "<div class='col-md-5'>";
        $var .= "<div id='jstree1'>";
        $var .= "<ul>";

        $visit = array();
        for ($i = 0; $i < count($userList); $i++) {
            $visit[$i] = false;
        }

        // $var_data = $p->dfs('COA', '0', $userList, $visit, 0);
        $var_data = $p->dfs_code('COA', '0', $userList, $visit, 0);
        $var .= $var_data;

        $var .= "</ul>";

        $var .= "</div>"; // jstree
        $var .= "</div>"; // col-md-4

        $var .= "<div class='col-md-7' style='position: fixed; border: 1px solid red;left: 46%;width: 750px;z-index:1000;background-color: #ffffff;padding:10px 0;' id='newform'></div>";

        // $var .= "</div>";
        $var .= "</div>"; // panel-body
        $var .= "</div>"; // panel
        $var .= "</div>"; // row

        $p->addTags(
            array(
                "menu_left"        => callMenuLeft(),
                "float_menu_atas"  => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar"     => callMenuTaskbar(),
                "btn_back"         => callBackNav(),
                "btn_top"          => "",
                "stop_time"        => "",
                "content"          => $var,
                // "script_bottom"    => $script_bottom,
                "profile_name"     => $this->session->login['nama'],
            )
        );

        // $p->setContent($contens);
        $p->render();
        break;
}