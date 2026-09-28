<?php
/**
 * Created by PhpStorm.
 * User: widi
 * Date: 10/22/2018
 * Time: 4:34 PM
 */
//arrPrint ($style);
//cekHere($mode);
switch ($mode) {

    case "view":

        $p = New Layout("$title", "$subTitle", "application/template/default.html");
        $p->addTags(
            array(
                "menu_left"        => callMenuLeft(),
                //                                "trans_menu" => callTransMenu(),
                "float_menu_atas"  => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar"     => callMenuTaskbar(),
                "btn_back"         => callBackNav(),
                //                "menu_sub" => callSubMEnu(),
                "content"          => "",
                "profile_name"     => $this->session->login['nama'],
            )
        );

        //  endregion menu left
        if (isset($lebar_modal)) {
            $p->setLebarModal($lebar_modal);
        }

        $contents = isset($contens) ? $contens : "";
        $contents .= isset($scriptLoad) ? $scriptLoad : "";

        $p->setContent($contents);
        $p->render();
        break;
    case "index" :
        // arrPrint($content);
        // cekHere(MODUL_TEMPLATE_PATH);
        //        $p = New Layout("", "", "application/template/pages2.html");
        // $p = New Layout("", "", MODUL_TEMPLATE_PATH ."application/template/default.html");
        $p = New Layout("", "", MODUL_TEMPLATE_PATH . "template/opname.html");
        $template = array(
            'table_open' => '<table id="table" class="table table-bordered tabled-condensed">',
            'thead_open' => '<thead class="" style="text-align: center;">',
        );
        $this->table->set_template($template);
        $content = "";
        if (is_array($arrayHeader) && sizeof($arrayHeader) > 0) {
            $header_f = array();
            //            $header_f[] = array('data' => "No", 'class' => 'text-center text-muted');
            foreach ($arrayHeader as $kolom => $label) {
                $header_f[] = array('data' => $label, 'class' => 'text-center text-muted');
            }
            $this->table->set_heading($header_f);
            if (sizeof($items) > 0) {
                //arrPrint($items);
                foreach ($items as $key => $data) {
                    //                    arrPrint($data);
                    $isi = array();
                    foreach ($arrayHeader as $kolom => $label) {
                        $value = $data[$kolom];
                        $isi[] = array('data' => $value);
                    }
                    //                    arrPRint($isi);
                    $this->table->add_row($isi);

                }
                //                die();

            }
            else {
                $this->table->add_row(array(
                    'data'    => "no category found for ",
                    'colspan' => count($arrayHeader) + 2,
                    'class'   => 'text-center',
                ));
            }

            $content .= ($this->table->generate());
        }
        $content = "";
        //        $content .= "<br><a href='JavaScript:Void(0);' class='btn btn-success' onclick='$btnClick'>Opname</a>";
        $content .= "<br><a href='JavaScript:Void(0);' class='btn btn-success' onclick=\"$btnClick\">Opname by kategori</a>";
        // $content .= "<a href='JavaScript:Void(0);' class='btn btn-success' onclick=\"$btnClick_2\">Opname by rak</a>";

        $script_bottom = "";
        $script_bottom .= "<script>";
        // $link_option = MODUL_PATH . get_class($this) . "view/Produk/FolderProduk/persediaan_produk/kategori";
        $script_bottom .= "$('#content_body').load('$link_option');";
        $script_bottom .= "</script>";
        $script_bottom .= $scriptLoad;
        // cekHere("$link_option");
        $p->addTags(
            array(
                "title"            => $title,
                "sub_title"        => "Opname $sub_title",
                "menu_left"        => callMenuLeft(),
                //                                "trans_menu" => callTransMenu(),
                "float_menu_atas"  => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar"     => callMenuTaskbar(),
                "btn_back"         => callBackNav(),
                //                "menu_sub"     => callSubMenu(),
                "content"          => $content,
                "profile_name"     => $this->session->login['nama'],
                "script_bottom"    => $script_bottom,
                // "scriptLoad"    => $script_bottom,
            )
        );

        //  endregion menu left
        if (isset($lebar_modal)) {
            $p->setLebarModal($lebar_modal);
        }

        //        $p->setContent($contens);
        $p->render();

        break;

    case "doPrint":
        //        arrPrint($fixedElements);
        $title_x = urldecode($this->uri->segment(4));
        $p = New Layout("", "", "application/template/opname.html");
        $p->addTags(
            array(
                "content"        => $content,
                "title"          => "stok opname $title_x",
                "companyProfile" => $companyProfile['companyProfile']['contents'][0],
                //                "fixedElements" => $fixedElements,

            )
        );

        //  endregion menu left
        if (isset($lebar_modal)) {
            $p->setLebarModal($lebar_modal);
        }
        $p->render();
        break;
    case "cekTransaksiGantung":
        $p = New Layout("", "", MODUL_TEMPLATE_PATH . "template/viewdetails.html");

        // arrPrintPink($mLabel);
        // arrPrintPink($stepModul);
        $bodies = "";
        $bodies .= "<div class='row'>";
        foreach ($kelompokMaster as $master_jenis => $items) {
            $master_label = $masterLabel[$master_jenis];
            $bodies .= "<div class='col-md-6'>";
            $bodies .= "<b title='$master_jenis' class='text-uppercase'>$master_label</b>";
            foreach ($items as $jenis_tr => $jenies) {
                $jenis_label = $stepLabel[$jenis_tr];
                $jenis_jml = sizeof($jenies);
                $jenis_modul = base_url().$stepModul[$jenis_tr]."/";
                $bodies .= "<div class='row text-capitalize text-red' title='trj_$jenis_tr' style='margin-left: 10px;'>$jenis_label <span class='badge bg-red'>$jenis_jml</span></div>";
                foreach ($jenies as $jeny) {

                    // arrPrintPink($jenies);
                    $nomer = $jeny->nomer;
                    $dtime = $jeny->dtime;
                    $oleh_nm = $jeny->oleh_nama;
                    $nomer_f = formatField_he_format("nomer", $nomer,$jenis_tr,$jenis_modul);
                    $dtime_f = formatField_he_format("fulldate", $dtime,$jenis_tr,$jenis_modul);

                    $bodies .= "<div class='row' style='margin-left: 0;'>";
                    $bodies .= "<div class='col-md-4'>$dtime_f</div>";
                    $bodies .= "<div class='col-md-3'>$nomer_f</div>";
                    // $bodies .= "<div class='col-md-3'>$oleh_nm</div>";
                    $bodies .= "</div>";

                }

            }
            $bodies .= "</div>";
        }
        $bodies .= "</div>";

        echo $content = $bodies;
// return $bodies;
        $script_bottom = "";


        // echo $content;

        // $p->addTags(
        //     array(
        //         "title"         => $title,
        //         "sub_title"     => "$sub_title",
        //         // "menu_left"        => callMenuLeft(),
        //         //                                "trans_menu" => callTransMenu(),
        //         // "float_menu_atas"  => callFloatMenu('atas'),
        //         // "float_menu_bawah" => callFloatMenu(),
        //         // "menu_taskbar"     => callMenuTaskbar(),
        //         // "btn_back"         => callBackNav(),
        //         //                "menu_sub"     => callSubMenu(),
        //         "content"       => $content,
        //         "profile_name"  => $this->session->login['nama'],
        //         "script_bottom" => $script_bottom,
        //     )
        // );

        // if (isset($lebar_modal)) {
        //     $p->setLebarModal($lebar_modal);
        // }
        //
        // //        $p->setContent($contens);
        // $p->render();
        break;

}