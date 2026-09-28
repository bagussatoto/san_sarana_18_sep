<?php
/**
 * Created by PhpStorm.
 * User: widi
 * Date: 10/22/2018
 * Time: 4:34 PM
 */
//arrPrint ($style);
cekHere(__LINE__);
//matiHEre();
switch ($mode) {

    case "view":

        $p = New Layout("$title", "", MODUL_TEMPLATE_PATH."/template/default.html");
        $p->addTags(
            array(
                "menu_left"    => callMenuLeft(),
                "content"      => $contens,
                "profile_name" => $this->session->login['nama'],
            )
        );

        //  endregion menu left

        $p->render();
        break;

}