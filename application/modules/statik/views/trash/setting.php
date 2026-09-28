<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 8/16/2018
 * Time: 8:51 PM
 */


switch ($mode) {

    case "index":
        // cekHere();
        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/setting.html");
        $content = "";
        $content .= "<style type='text/css'>
                .nav-tabs-custom > .nav-tabs > li.active a {
                    background-color: coral !important;
                }
        </style>";
        $loader_indikator = "<div class='loader-5 center'><span></span></div>";

        $link_viewProdukKategori = base_url() . "statik/Setting/viewData";
        $content_00 = "<div id='enol'>Loading Produk Kategori Diskon Data... <div style='margin-left:50%;'>$loader_indikator</div></div>";
        $content_00 .= "<script>$('#enol').load('$link_viewProdukKategori');</script>";

        $link_viewProdukHarga = base_url() . "statik/Setting/viewTransaksi";
        $content_0 = "<div id='satu'>Loading Produk Harga dan Diskon Data... <span>$loader_indikator</span></div>";
        $content_0 .= "<script>
                var fid = localStorage.getItem('fid');
                var fkolom = localStorage.getItem('fkolom');
                
                if(!fid){
                    $('#satu').load('$link_viewProdukHarga');                 
                }
                else {                   
                    $('#satu').load('$link_viewProdukHarga?f='+fkolom+'&v='+fid);
                }
                
            </script>";
        // $content_0 .= "<script>var loadProdukHarga = function(){ $('#satu').html(\"<div class='text-center text-bold'><img width='15%' src='//cdn.mayagrahakencana.com/assets/images/d60eb1v-79212624-e842-4e55-8d58-4ac7514ca8e4.gif'><br/><h3>MOHON TUNGGU, SYSTEM SEDANG MEMUAT DATA PRODUK...</h3></div>\").load('$link_viewProdukHarga') }; loadProdukHarga()</script>";

        $link_viewMember = base_url() . "statik/Setting/viewOther";
        $other = "<div id='dua'>Loading akses menu ...</div>";
        $other .= "<script>$('#dua').load('$link_viewMember');</script>";

        //viewCashBackMember
        $link_viewCashBackMember = base_url() . "diskon/setting/viewCashBackMember";
        $cashBack = "<div id='tiga'>Loading Cashback Member Data...</div>";
        $cashBack .= "<script>$('#tiga').load('$link_viewCashBackMember');</script>";

        //viewPointMember
        $link_viewPointMember = base_url() . "diskon/setting/viewPointMember";
        $point = "<div id='enam'>Loading Point Member Data...<div style='margin-left:50%;'>$loader_indikator</div></div>";
        $point .= "<script>$('#enam').load('$link_viewPointMember');</script>";
        // tebusmurah
        $link_viewTebusMurah = base_url() . "diskon/setting/viewTebusMurah";
        $link_viewTebusMurah = base_url() . "diskon/setting/viewUnvalable";
        $tebusmurah = "<div id='lima'>Loading Tebus murah Data...  <div style='margin-left:50%;'>$loader_indikator</div></div>";
        $tebusmurah .= "<script>$('#lima').load('$link_viewTebusMurah');</script>";

        $link_viewDiskonFreeProduk = base_url() . "diskon/setting/viewDiskonFreeProduk";
        $freeproduk = "<div id='empat'>Loading Diskon Free Produk Data... $loader_indikator</div>";
        $freeproduk .= "<script>$('#empat').load('$link_viewDiskonFreeProduk');</script>";

        /*---------------TAB-TAB--------------‎‎*/
        $isi_tab = array();
        $isi_tab["data"] = array(
            "label"  => "data",
            "active" => true,
            "data"   => $content_00,
            "css"    => "bg-aqua",
            "class"  => "bg-aaaaa",
            "link"   => $link_viewProdukKategori,
        );
        $isi_tab["transaksi"] = array(
            "label" => "transaksional",
            // "active" => true,
            "data"  => $content_0,
            "css"   => "bg-aqua",
            "class" => "bg-aaaaa",
            "link"  => $link_viewProdukHarga,
        );
        $isi_tab["other"] = array(
            "label" => "lain-lain",
            // "active" => true,
            "data"  => $other,
            "css"   => "bg-aqua",
            "link"  => $link_viewMember,
        );
        // $isi_tab["cashback"] = array(
        //     "label" => "cash Back",
        //     // "active" => true,
        //     "data"  => $cashBack,
        //     "css"   => "bg-aqua",
        // );
        // $isi_tab["point"] = array(
        //     "label" => "point",
        //     // "active" => true,
        //     "data"  => $point,
        //     "css"   => "bg-aqua",
        // );
        // $isi_tab["getfreeproduk"] = array(
        //     "label" => "<i class='fa fa-gift'></i>&nbsp;&nbsp;diskon free produk",
        //     // "active" => true,
        //     "data"  => $freeproduk,
        //     "css"   => "bg-aqua",
        // );
        // $isi_tab["tebusmurah"] = array(
        //     "label" => "<i class='fa fa-gift'></i>&nbsp;&nbsp;tebus murah",
        //     // "active" => true,
        //     "data"  => $tebusmurah,
        //     "css"   => "bg-aqua",
        // );
        // $content .= $p->layout_tabs($isi_tab);
        $content .= $p->layout_tab_loaders($isi_tab);


        $p->addTags(
            array(
                "menu_left"        => callMenuLeft(),
                "trans_menu"       => callTransMenu(),
                "float_menu_atas"  => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar"     => callMenuTaskbar(),
                "btn_back"         => callBackNav(),
                "add_pihak"        => "",
                "pihak_label"      => "",
                "add_item"         => "",
                "selector_label"   => "",
                "tmp_request"      => "",
                "mobile_scan"      => "",
                "ext_tool"         => "",
                "submit_button"    => "",
                "content"          => $content,
            )
        );
        $p->render();
        break;

    // ------------------------------------------%&/

    case "viewData":
        // $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/diskon.html");
        $pluss = 0;
        // arrPrintHijau($childData);
        $mdlTersedia = array_keys($childData);
        // region customer level
        /* --------------------------------------------------------------------
        * THEAD
        * --------------------------------------------------------------------*/
        $groupHead = "";
        foreach ($arrHeadersGroup as $gkey => $gparams) {
            $glabel = isset($gparams['label']) ? $gparams['label'] : $gkey;
            $attr_header = isset($gparams['attr']) ? $gparams['attr'] : '';
            $heTransaksi_ui_0 = isset($gparams['heTransaksi_ui']) ? $gparams['heTransaksi_ui'] : array();
            $heTransaksi_ui = array_intersect($heTransaksi_ui_0, $mdlTersedia);
            $jmlAnak = count($heTransaksi_ui);
            $colspan = $jmlAnak * 5;
            $rowspan = 1;
            if ($jmlAnak > 0) {
                $groupHead .= "<th colspan='$colspan' cls='$gkey' class='btn-toggle-group g-$gkey' $attr_header>$glabel&nbsp;<span class='badge badge-danger'>$jmlAnak</span></th>";
            }
        }
        // -----------------------------------------------
        $strHead = "";
        $strHead .= "<tr class='bg-info'>";
        $strHead .= "<th rowspan='3' class='sticky-col' style='background-color: #d9edf7;'>no</th>";
        foreach ($arrHeaders as $kolom => $arrHeader) {
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $child = isset($arrHeader['child']) && $arrHeader['child'] == true ? 1 : 0;
            $parent_ky = isset($arrHeader['parent_ky']) ? $arrHeader['parent_ky'] : "";
            $attr_header = isset($arrHeader['attr_header']) ? $arrHeader['attr_header'] : "";

            $colspan = isset($childs[$kolom]) ? count($childs[$kolom]) : 1;
            $rowspan = isset($childs[$kolom]) ? 1 : 3;

            $strHead .= "<th colspan='$colspan' rowspan='$rowspan' $attr_header>$hLabel</th>";
        }
        $strHead .= $groupHead;
        $strHead .= "</tr>";

        // arrPrintPink($childData);
        $strHead .= "<tr>";
        $jmlColspan = count($childs);
        foreach ($arrHeadersGroup as $gkey => $gparams) {
            $heTransaksi_ui_0 = isset($gparams['heTransaksi_ui']) ? $gparams['heTransaksi_ui'] : array();

            $heTransaksi_ui = array_intersect($heTransaksi_ui_0, $mdlTersedia);
            foreach ($heTransaksi_ui as $mdl) {
                $childDatum = isset($childData[$mdl]) ? $childData[$mdl] : "";
                $label = isset($childDatum["label"]) ? $childDatum["label"] : $mdl;
                $strHead .= "<th class='btn-toggle p-$mdl pg-$gkey' gcls='g-$gkey' cls='$mdl' colspan='$jmlColspan' rowspan='1' >$label</th>";
            }
        }
        // foreach ($childData as $mdl => $childDatum) {
        //     $label = isset($childDatum["label"]) ? $childDatum["label"] : $mdl;
        //     $strHead .= "<th class='btn-toggle p-$mdl' cls='$mdl' colspan='$jmlColspan' rowspan='1' $attr_headerr>$label</th>";
        // }
        $strHead .= "</tr>";
        // arrPrintHijau($childs);
        /* -----------------------------------------------------------------------------------
         * THEAD
         * header anakan
         * -----------------------------------------------------------------------------------
         * */
        $strHead .= "<tr class='bg-info'>";
        foreach ($arrHeadersGroup as $gkey => $gparams) {
            $heTransaksi_ui_0 = isset($gparams['heTransaksi_ui']) ? $gparams['heTransaksi_ui'] : array();

            $heTransaksi_ui = array_intersect($heTransaksi_ui_0, $mdlTersedia);
            foreach ($heTransaksi_ui as $mdl) {
                foreach ($childs as $ky => $childrens) {
                    $hLabel = isset($childrens['label']) ? $childrens['label'] : $ky;
                    $ctoggle = $ky != 4 ? "c-$mdl ag-$gkey child" : "c";
                    $strHead .= "<th class='$ctoggle' colspan='1'>$hLabel</th>";
                }
            }
        }
        // foreach ($childData as $mdl => $childDatum) {
        //     foreach ($childs as $ky => $childrens) {
        //         $hLabel = isset($childrens['label']) ? $childrens['label'] : $ky;
        //         $ctoggle = $ky != 4 ? "c-$mdl child" : "c";
        //         $strHead .= "<th class='$ctoggle' colspan='1' $hattr_header>$hLabel</th>";
        //     }
        // }
        $strHead .= "</tr>";

        $row_id = "";
        if (count($masterData) > 0) {
            //     foreach ($masterData as $jenis_kdata => $level_data) {
            $pluss = $jenis_kdata = $mode;
            // arrPrintPink($level_data);

            /* --------------------------------------------------------------------
             * TBODY
             * --------------------------------------------------------------------*/
            $strBody = "";
            $no = 0;
            $modul_path = isset($modul_path) ? $modul_path : base_url() . "penjualan/";
            $jenistr = isset($jenisTr) ? $jenisTr : "582";
            // matiHere($jenistr);
            $count_id = 111;
            $row_id = "";
            // arrPrintKuning($level_data);
            // arrPrintKuning($level_header_00);
            foreach ($masterData as $master_datum) {
                $no++;
                $count_id++;
                $jenis = isset($master_datum['jenis']) ? $master_datum['jenis'] : 0;
                $minim = isset($master_datum['minim']) ? $master_datum['minim'] : 0;
                $db_id = isset($master_datum['id']) ? $master_datum['id'] : "";
                $mMenu = isset($myMenuData[$db_id]) ? $myMenuData[$db_id] : "";

                $row_id = "row_" . $jenis_kdata . "_$count_id";
                $strBody .= "<tr id='$row_id'>";
                $strBody .= "<td>$no</td>";
                foreach ($arrHeaders as $kolom => $attrs) {
                    $td_id = $kolom . "_" . $count_id;
                    // $nilai = $master_datum[$kolom];
                    // $nilai = isset($master_datum[$kolom]) ? $master_datum[$kolom] : (is_numeric($master_datum[$kolom]) ? 0 : "-");
                    $nilai = isset($master_datum[$kolom]) ? (is_numeric($master_datum[$kolom]) ? $master_datum[$kolom] * 1 : $master_datum[$kolom]) : "";

                    $attr = isset($attrs['attr']) ? $attrs['attr'] : "";
                    $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;
                    $nilai_f = isset($attrs['format']) ? ($nilai > 0 ? $attrs['format']($format_key, $nilai, $jenistr, $modul_path) : $nilai) : $nilai;

                    if (isset($attrs['links'])) {
                        // matiHere();
                        $modal_size = isset($attrs['links']['modal_size']) ? $attrs['links']['modal_size'] : "";
                        $title_head_key = isset($attrs['links']['title_head_key']) ? $attrs['links']['title_head_key'] : "";
                        $title_head = isset($attrs['links']['title_head_key']) ? (isset($master_datum[$title_head_key]) ? $master_datum[$title_head_key] : 'none') : $nilai;
                        $link_title = isset($attrs['links']['title']) ? $attrs['links']['title'] : "";
                        $strTitle_head = urlencode(trim("$link_title $title_head"));
                        // cekHere("$strTitle_head");
                        $reqKey = isset($attrs['links']['key']) ? $attrs['links']['key'] : "";
                        $reqValue = isset($master_datum[$reqKey]) ? $master_datum[$reqKey] : "none";
                        $linking = isset($attrs['links']['target']) ? $attrs['links']['target'] . "?$strGet" . "&$reqKey=$reqValue&modalSize=$modal_size" : "";
                        $linkDetile = base_url() . $linking . "";
                        $linkModal = modalDialogBtn("$strTitle_head", $linkDetile);
                        $nilai_link = isset($attrs['links']['target']) ? "<a href='javascript:void(0);' onclick=\"$linkModal\" title='$link_title'>$nilai_f</a>" : $nilai_f;
                    }
                    else {
                        // $linking = isset($attrs['link']) ? $attrs['link'] . "/" : "";
                        // $linkDetile = base_url() . $linking . "";
                        // $linkModal = modalDialogBtn("$nilai", $linkDetile);
                        $nilai_link = $nilai_f;
                    }

                    if (isset($attrs['tipe_input'])) {
                        $tipe_input = $attrs['tipe_input'];
                        $click_fx = isset($attrs['onclick_fx']) ? $attrs['onclick_fx'] : "";
                        // arrPrintKuning($master_datum);
                        // $nilai = isset($master_datum[$kolom]) ? (is_numeric($master_datum[$kolom]) ? $master_datum[$kolom] * 1 : $master_datum[$kolom]) : "";

                        switch ($tipe_input) {
                            case "checkbox":
                                // if ($kolom == "status") {
                                $link_hapus = "";
                                $checked = $nilai == 1 ? "checked" : "";
                                // $strBody .= "<td $attr id='$td_id'>";

                                // $strBody .= "<div class='funkyradio'>";
                                $nilai_link = "<div class='funkyradio-success'>";
                                $nilai_link .= "<input type='checkbox' $checked onclick=\"$click_fx('$db_id', '$row_id');\">";
                                $nilai_link .= "</div>";
                                // $strBody .= "</div>";

                                // $strBody .= "</td>";
                                // }
                                break;
                        }
                    }
                    // $linking = isset($attrs['link']) ? $attrs['link'] . "/$ksr_id" : "";
                    // $linkDetile = base_url() . $linking . "";
                    // $linkModal = modalDialogBtn("'$nama'", $linkDetile);
                    // $nilai_link = isset($attrs['link']) ? "<a href='javascript:void(0);' onclick=\"$linkModal\" title='lihat komposisi'>$nilai_f</a>" : $nilai_f;


                    if ($kolom == "action") {
                        $link_hapus = base_url() . "diskon/Setting/do_delete_member?jn=$jenis&minim=$minim&ctr=$my_controler&div=$my_div";
                        $strBody .= "<td $attr id='$td_id'>";
                        $strBody .= "<div class='btn-group'><button type='button' class='btn btn-link btn-sm' id='$td_id' onclick=\"btn_edit_$pluss('$row_id');\"><i class='fa fa-pencil'></i></button>";
                        $strBody .= "<button type='button' class='btn btn-sm btn-link' onclick=\"btn_alert_result('Oppss','akan meghapus setting diskon member?','$link_hapus');\"><i class='fa fa-trash'></i></button></div>";
                        $strBody .= "</td>";
                    }
                    else {
                        $strBody .= "<td $attr id='$td_id' title='$td_id'>$nilai_link</td>";
                    }

                    if (isset($attrs['summary'])) {
                        if (!isset($totals[$kolom])) {
                            $totals[$kolom] = 0;
                        }
                        $totals[$kolom] += $nilai;
                    }
                }

                foreach ($arrHeadersGroup as $gkey => $gparams) {
                    $heTransaksi_ui_0 = isset($gparams['heTransaksi_ui']) ? $gparams['heTransaksi_ui'] : array();
                    $heTransaksi_ui = array_intersect($heTransaksi_ui_0, $mdlTersedia);
                    foreach ($heTransaksi_ui as $mdl) {
                        $mymenu = isset($mMenu[$mdl]) ? $mMenu[$mdl] : array();
                        foreach ($childs as $ky => $childrens) {

                            /* ----------------------------------------------------------------------
                             * mematikan hak akses supaya tidak bisa diberikan, diatur dari behavior
                             * ----------------------------------------------------------------------*/
                            $disabledKy = $childData[$mdl]['disabled'];

                            $checked_data = in_array($ky, $mymenu) ? "checked" : "";
                            if ($checked_data == "checked") {
                                // $ncheckBox = "<i class='fa fa-check-square text-red'></i>";
                                $ncheckBox = "<i class='fa fa-check text-green'></i>";
                                $check_class = "checked-green";
                            }
                            else {
                                $ncheckBox = "<i class='fa fa-circle-o'></i>";
                                $check_class = "checked-grey";
                            }
                            // if(!is_array($creator) && $creator == false && $ky == 1){
                            if(in_array($ky, $disabledKy)){
                                $ncheckBox = "<i class='fa fa-circle text-grey'></i>";
                                $dis = "-no";
                            }
                            else{
                                $dis = "";
                            }

                            // $ncheckBox = "<input type='checkbox' $checked_data>";
                            $ncheckBox = $ncheckBox;
                            $ctoggle = $ky != 4 ? "c-$mdl ag-$gkey child" : "c";
                            $cchild = "ch-$mdl ch-$gkey";

                            $strBody .= "<td class='text-center $ctoggle $cchild checked$dis $check_class' obid='$db_id' mdl='$mdl' crud='$ky'>$ncheckBox</td>";
                        }
                    }
                }
                // foreach ($childData as $mdl => $childDatum) {
                //     // arrPrintHijau($mMenu[$mdl]);
                //     $mymenu = $mMenu[$mdl];
                //     foreach ($childs as $ky => $childrens) {
                //         $checked_data = in_array($ky, $mymenu) ? "checked" : "";
                //         if ($checked_data == "checked") {
                //             // $ncheckBox = "<i class='fa fa-check-square text-red'></i>";
                //             $ncheckBox = "<i class='fa fa-check text-red'></i>";
                //             $check_class = "checked-green";
                //         }
                //         else {
                //             $ncheckBox = "<i class='fa fa-circle-o'></i>";
                //             $check_class = "checked-grey";
                //         }
                //         // $ncheckBox = "<input type='checkbox' $checked_data>";
                //         $ncheckBox = $ncheckBox;
                //         $ctoggle = $ky != 4 ? "c-$mdl child" : "c";
                //         $strBody .= "<td class='$ctoggle checked $check_class' id='$db_id' mdl='$mdl' crud='$ky'>$ncheckBox</td>";
                //     }
                // }
                $strBody .= "</tr>";

            }

        }
        // }
        // else {
        //     $strBody = "";
        //     $nilai = "transaksi";
        // }
        /* --------------------------------------------------------------------
        * TFOOD
        * --------------------------------------------------------------------*/
        // arrPrint($level_header);
        // arrPrint($level_header_00);
        $link_save = base_url() . "diskon/Setting/do_save_member";
        $strFoot = "";
        $strFoot .= "<form method='post' id='my_form_$pluss' action='$link_save' target='result'>";
        $strFoot .= "<tr class='bg-danger' id='form_input_$pluss'>";
        $strFoot .= "<th></th>";
        foreach ($arrHeaders as $kolom => $arrHeader) {
            $attrs = $arrHeader;

            $kolom_id = $kolom . "_value_" . $pluss;
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $hTipe = isset($arrHeader['tipe_input']) ? $arrHeader['tipe_input'] : "text";
            $attr = isset($attrs['attr_footer']) ? $attrs['attr_footer'] : (isset($attrs['attr']) ? $attrs['attr'] : "");
            $data_srcs = isset($attrs['data_srcs']) ? $attrs['data_srcs'] : array();
            // $nilai = isset($attrs['default_data']) ? ($kolom == 'jenis' ? $jenis_kdata : $attrs['default_data']) : "";
            // $nilai = isset($attrs['default_data']) ? ($kolom == 'jenis' ? $attrs['default_data'] : $jenis_kdata) : "";
            $nilai = isset($attrs['default_data']) ? $attrs['default_data'] : "";

            switch ($hTipe) {
                default:
                case "text":
                    $str_input = "<input type='$hTipe' form='my_form_$pluss' onclick=\"this.select()\" name='$kolom' id='$kolom_id' $attr value='$nilai'>";
                    break;
                case "select":
                    $str_input = "<select name='$kolom' form='my_form_$pluss' id='$kolom_id' $attr>";
                    $str_input .= "<option value=''>------</option>";
                    foreach ($data_srcs as $data_src) {
                        $str_input .= "<option>$data_src</option>";
                    }
                    $str_input .= "</select>";
                    break;
            }

            $strFoot .= "<th>$str_input</th>";
        }
        $tipe = "";
        $my_div = "";
        $my_controler = "";
        $strFoot .= "<input type='hidden' form='my_form_$pluss' name='tipe' id='tipe_value_$pluss' value='$tipe'><input type='hidden' form='my_form_$pluss' name='my_controler' value='$my_controler'>
                <input type='hidden' form='my_form_$pluss' name='minim_be' id='maxim_value_$pluss' value=''>
                <input type='hidden' form='my_form_$pluss' name='my_div' value='$my_div'>";
        $strFoot .= "</tr>";
        $strFoot .= "</form>";

        $tbl_id = "member";
        $strTbl = "";
        $strTbl .= "<style type='text/css'>
                        .table>thead>tr>td, .table>thead>tr>th, .table>tbody>tr>td {
                            vertical-align : middle !important;
                            padding : 3px 10px !important;
                        }
                        .table>thead>tr>th {
                            font-size: 0.8em;;
                        }
                        .table>tfoot>tr>th>select.form-control, .table>tfoot>tr>th>input.form-control, .table>tfoot>tr>th>input.btn {
                            height: 30px;
                            padding: 0 6px !important;
                            font-size: 1em;;
                        }
                        .btn {
                            padding: 1px 6px !important;
                        }
                        </style>";
        $strTbl .= "<div class='table-responsivee wrapper-sticky tblid_$tbl_id' >";
        $strTbl .= "<lable class='text-uppercase'>$jenis_kdata </lable>";
        $strTbl .= "<lable class='text-uppercase'><button type='button' class='text-uppercase' id='showhidemaster'>show hide master</button></lable>";
        // $strTbl .= "<lable class='text-uppercase'><button type='button' class='text-uppercase' id='showhide'>show hide</button></lable>";
        $strTbl .= "<table class='table table-condensade table-striped table-hover-color-red' style='margin=0' id='$tbl_id'>";
        $strTbl .= "<thead class='text-uppercase'>";
        $strTbl .= $strHead;
        $strTbl .= "</thead>";
        $strTbl .= "<tbody>";
        $strTbl .= $strBody;
        $strTbl .= "</tbody>";

        // $strTbl .= "<form>";
        $strTbl .= "<tfoot>";
        // $strTbl .= $strFoot;
        $strTbl .= "</tfoot>";
        // $strTbl .= "</form>";

        $strTbl .= "</table>";
        $strTbl .= "</div>";

        $base = MODUL_PATH . "Setting/setData";
        $strTbl .= "<script>
            $('.checked').on('click', function() {
                // let data = $(this).data('subjek');
                let id = $(this).attr('obid');
                let mdl = $(this).attr('mdl');
                let crud = $(this).attr('crud');
                let url = '$base';
                if($(this).hasClass('checked-green')){
                    console.log('cek');
                    $.get(url,{id:id,mdl:mdl,crud:crud},function() {
                      console.log('cek send ' + url);
                    });
                    
                    $('td[obid=\"'+id+'\"][mdl=\"'+mdl+'\"][crud=\"'+crud+'\"]').removeClass('checked-green').addClass('checked-grey');
                    $('td[obid=\"'+id+'\"][mdl=\"'+mdl+'\"][crud=\"'+crud+'\"] i').removeClass('fa fa-check text-red').addClass('fa fa-circle-o');
                }
                else {
                    url = url + '?id='+ id + '&mdl=' + mdl + '&crud=' + crud;
                    console.log('un-cek', url);
                    $('#result_bottom').load(url);
                    
                    $('td[obid=\"'+id+'\"][mdl=\"'+mdl+'\"][crud=\"'+crud+'\"]').removeClass('checked-grey').addClass('checked-green');
                    $('td[obid=\"'+id+'\"][mdl=\"'+mdl+'\"][crud=\"'+crud+'\"] i').removeClass('fa fa-circle-o').addClass('fa fa-check text-red');
                }
                
                // location.reload();
                console.log('id:', id);
                // console.log('klik kolom');
                // console.log('data:', data);
                // alert('Kolom dengan class checked diklik!');
            });
        </script>";

        $strTbl .= "<script>
        
               function btn_edit_$pluss(r) {
                   // console.log(r);
                    var row_sumber = $('td',$('#'+r));
            
                    var objek = {};
                    jQuery.each(row_sumber, function(a,b) {
                          var nilai = $(b).html()
                
                          objek[a] = nilai;
                    })
            
                    
                    var row_target = $('th',$('#form_input_$pluss'));
                    var last_key = row_target.length - 1;
                    // console.log(last_key);
                    jQuery.each(row_target, function(c,d) {
                        var nilai = $('input',$(d));
                        var nilai_select = $('select',$(d));
                        
                        if(c != last_key){                
                            $(nilai).val(objek[c]);
                            $(nilai_select).val(objek[c]);
                        }
                          
                    })
        
                    if(typeof (r)){
                        // alert('ok');
                        $('tr').css('background-color','');
                        $('#'+r).css('background-color','#ff00007d');
                        $('#jenis_value_$pluss').prop('readonly', true);
                        $('#minim_value_$pluss').prop('readonly', true);
                    }

               }
               
               //   minim_values
            $('#minim_value_$pluss').blur(function() {
                // var row_sumber = $row_id;
                var row_sumber = $('td',$('#$row_id'));
                var objek = {};
                jQuery.each(row_sumber, function(a,b) {
                    var nilai = $(b).html()
            
                    objek[a] = nilai;
                })
                
                console.log('row_id :: $row_id');
                console.log(objek);
                var last_minim = objek['2'];
                var now_minim = $('#minim_value_$pluss').val();
                
                if(Number(now_minim) <= Number(last_minim)){
                    swal({
                        title: 'Opsss.. !!',
                        html: 'minimal transaksi harus lebih besar dari ' + last_minim + ' sekarang ' + now_minim
                    });
                    
                    $('#minim_value_$pluss').css('background-color','#fff700ad');
                }
                else {
                    $('#minim_value_$pluss').css('background-color','');
                    $('#maxim_value_$pluss').val(last_minim);
                }
            });

                $('#level_1_value_$pluss').blur(function() {
                    var row_id = Number($row_id);
                    var row_sumber = $('td',$('#'+(row_id-1)));
                    var objek = {};
                    jQuery.each(row_sumber, function(a,b) {
                    var nilai = $(b).html()

                        objek[a] = nilai;
                    })

                    console.log('row_id :: $row_id || ' + row_sumber + row_id);
                    console.log(objek);

            });
            </script>";

        $strTbl .= "<script>
            $('#showhidemaster').click(function () {
                        let buka = false;
                        $('.btn-toggle-group').each(function () {
                            let clsValue = $(this).attr('cls');
                            let parent_cls = 'g-' + clsValue;
            
                            let parent_column = $('.' + parent_cls);
                            let groupColspan = parseInt(parent_column.attr('colspan'), 10);  // Pastikan dalam bentuk angka
            
                            let defcolKey = 'defcol-' + parent_cls;
                            let defcolspan = localStorage.getItem(defcolKey);
            
                            if (!defcolspan) {
                                localStorage.setItem(defcolKey, groupColspan);
                                defcolspan = groupColspan;  // Set nilai default jika belum ada
                            } else {
                                defcolspan = parseInt(defcolspan, 10);  // Pastikan dalam bentuk angka
                            }
            
                            if (defcolspan === groupColspan) {
                                parent_column.attr('colspan', groupColspan / 5);
                                buka = false;
                                console.log('--clsValue /:', clsValue);
                            } else {
                                parent_column.attr('colspan', groupColspan * 5);
                                buka = true;
                                console.log('--clsValue *:', clsValue);
                            }
            
                            // console.log('cls:', clsValue);
                            console.log('-defcolspan:', defcolspan);
                            console.log('->groupColspan:', groupColspan);
                        });
            
            
                        if(buka === false){
                            console.log('tutup');
                            $('th.btn-toggle').attr('colspan', 1);
                            $('.child').fadeOut();
                            $('.checked').css({'color': 'green'});
                        }
                        else {
                            console.log('buka');
                            $('th.btn-toggle').attr('colspan', 5);
                            $('.child').fadeIn();
                        }
                    });
            
            // $('#showhidemaster').trigger('click');
            </script>";
        $member = "";
        $member .= $strTbl;
        // endregion

        if (!isset($_GET['tpl'])) {
            echo $member;
        }
        else {

            $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/setting.html");
            $p->addTags(
                array(
                    // "menu_left"        => callMenuLeft(),
                    // "trans_menu"       => callTransMenu(),
                    // "float_menu_atas"  => callFloatMenu('atas'),
                    // "float_menu_bawah" => callFloatMenu(),
                    // "menu_taskbar"     => callMenuTaskbar(),
                    // "btn_back"         => callBackNav(),
                    // "add_pihak"        => "",
                    // "pihak_label"      => "",
                    // "add_item"         => "",
                    // "selector_label"   => "",
                    // "tmp_request"      => "",
                    // "mobile_scan"      => "",
                    // "ext_tool"         => "",
                    // "submit_button"    => "",
                    "content" => $strTbl,
                )
            );
            $p->render();
        }


        break;

    case "viewTransaksi":
        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/diskon.html");
        $pluss = 0;
        // arrPrintHijau($level_data_0);
        $stepErp = array();
        foreach ($childData as $jenis => $arrHeader) {
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $steps = isset($arrHeader['steps']) ? $arrHeader['steps'] : array();
            $parent_ky = isset($arrHeader['parent_ky']) ? $arrHeader['parent_ky'] : "";

            $stepErp[$jenis] = $steps;
        }
        $stepErpJenis = array_keys($stepErp);
        // arrPrintHijau();
        // region customer level
        /* --------------------------------------------------------------------
        * THEAD
        * --------------------------------------------------------------------*/
        // arrPrint($arrHeadersGroup);
        $groupHead = "";
        foreach ($arrHeadersGroup as $gkey_0 => $gparams) {
            $gkey = str_replace(" ", "_", strtolower($gkey_0));
            $glabel = isset($gparams['label']) ? $gparams['label'] : $gkey;
            $attr_header = isset($gparams['attr']) ? $gparams['attr'] : '';
            $heTransaksi_ui_00 = isset($gparams['heTransaksi_ui']) ? $gparams['heTransaksi_ui'] : array();
            $heTransaksi_ui = array_filter(array_intersect($heTransaksi_ui_00, $stepErpJenis));
            // arrPrintPink($heTransaksi_ui);
            // arrPrintKuning();

            $jmlAnak = count($heTransaksi_ui);
            if ($jmlAnak > 0) {
                foreach ($heTransaksi_ui as $mdl) {

                    $stepDatums = isset($stepErp[$mdl]) ? $stepErp[$mdl] : array();
                    // cekKuning("$gkey");
                    // cekBiru($stepDatums);
                    $jmlCucu = count($stepDatums);
                    // cekHere("cc: $jmlCucu");
                    if (!isset($jmlCucuDrs[$gkey])) {
                        $jmlCucuDrs[$gkey] = 0;
                    }
                    $jmlCucuDrs[$gkey] += $jmlCucu;
                }
                $jmlCucuDr = $jmlCucuDrs[$gkey];
                // cekHijau();
                //                 $colspan = $jmlAnak * 1;
                $colspan = $jmlCucuDr * 4;
                $rowspan = 1;

                $groupHead .= "<th colspan='$colspan' cls='$gkey' class='btn-toggle-group g-$gkey' $attr_header>$glabel <span class='badge badge-danger'>$jmlAnak</span></th>";
            }
        }
        // -----------------------------------------------
        $strHead = "";
        $strHead .= "<tr class='bg-info'>";
        $strHead .= "<th rowspan='4'>no</th>";
        foreach ($arrHeaders as $kolom => $arrHeader) {
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $child = isset($arrHeader['child']) && $arrHeader['child'] == true ? 1 : 0;
            $parent_ky = isset($arrHeader['parent_ky']) ? $arrHeader['parent_ky'] : "";
            $attr_header = isset($arrHeader['attr_header']) ? $arrHeader['attr_header'] : "";

            $colspan = isset($childs[$kolom]) ? count($childs[$kolom]) : 1;
            $rowspan = isset($childs[$kolom]) ? 1 : 4;

            $strHead .= "<th colspan='$colspan' rowspan='$rowspan' $attr_header>$hLabel</th>";
        }
        $strHead .= $groupHead;
        $strHead .= "</tr>";

        // arrPrintPink($childData);
        $strHead .= "<tr>";
        $jmlChildColspan = count($childs);
        foreach ($arrHeadersGroup as $gkey_0 => $gparams) {
            $gkey = str_replace(" ", "_", strtolower($gkey_0));
            $heTransaksi_ui_00 = isset($gparams['heTransaksi_ui']) ? $gparams['heTransaksi_ui'] : array();
            $heTransaksi_ui = array_intersect($heTransaksi_ui_00, $stepErpJenis);
            // arrPrintHijau($heTransaksi_ui);
            // $heTransaksi_ui = array_filter($heTransaksi_ui);
            foreach ($heTransaksi_ui as $mdl) {

                $childDatum = isset($childData[$mdl]) ? $childData[$mdl] : array();
                $jmlChildDatum = count($childDatum['steps']);
                if (isset($childData[$mdl])) {
                    $jmlColspan = isset($childDatum['steps']) ? count($childDatum['steps']) * $jmlChildColspan : 1;
                    $label = isset($childDatum["label"]) ? $childDatum["label"] : $mdl;

                    $strHead .= "<th class='btn-toggle g-$mdl' cls='$mdl' colspan='$jmlColspan' rowspan='1' title='$mdl' $attr_header>$label <span class='badge badge-danger'>$jmlChildDatum </span></th>";
                }
            }
        }
        // foreach ($childData as $mdl => $childDatum) {
        //     $jmlColspan = isset($childDatum['steps']) ? count($childDatum['steps']) * $jmlChildColspan : 1;
        //     $label = isset($childDatum["label"]) ? $childDatum["label"] : $mdl;
        //     $strHead .= "<th colspan='$jmlColspan' rowspan='1' title='$mdl' $attr_header>$label $mdl</th>";
        // }
        $strHead .= "</tr>";
        // arrPrintHijau($childs);
        // arrPrintKuning($stepErp);

        /* -----------------------------------------------------------------------------------
         * THEAD
         * header anakan
         * -----------------------------------------------------------------------------------
         * */

        $strHead .= "<tr class='bg-info'>";
        foreach ($arrHeadersGroup as $gkey => $gparams) {
            $heTransaksi_ui = isset($gparams['heTransaksi_ui']) ? $gparams['heTransaksi_ui'] : array();
            // arrPrintHijau($heTransaksi_ui);
            foreach ($heTransaksi_ui as $mdl) {
                $stepDatums = $stepErp[$mdl];
                foreach ($stepDatums as $step => $stepDatum) {
                    $hLabel = isset($stepDatum['label']) ? $stepDatum['label'] : $step;
                    $strHead .= "<th class='sl-toggle' colspan='$jmlChildColspan'>$hLabel</th>";
                }
            }
        }
        // foreach ($stepErp as $jenis => $stepDatums) {
        //
        //     foreach ($stepDatums as $step => $stepDatum) {
        //
        //         $hLabel = isset($stepDatum['label']) ? $stepDatum['label'] : $step;
        //         $strHead .= "<th colspan='$jmlChildColspan'>$hLabel $jenis</th>";
        //     }
        // }
        $strHead .= "</tr>";
        $strHead .= "<tr class='bg-info'>";
        foreach ($arrHeadersGroup as $gkey => $gparams) {
            $heTransaksi_ui = isset($gparams['heTransaksi_ui']) ? $gparams['heTransaksi_ui'] : array();
            // arrPrintHijau($heTransaksi_ui);
            foreach ($heTransaksi_ui as $mdl) {
                $stepDatums = $stepErp[$mdl];

                foreach ($stepDatums as $step => $stepDatum) {

                    foreach ($childs as $ky => $childrens) {
                        $hLabel = isset($childrens['label']) ? $childrens['label'] : $ky;
                        // $sl_toggle = $ky == 1 ? "" : "sl-toggle";
                        $child = $ky == 1 ? "c" : "child";
                        $strHead .= "<th class='$child' colspan='1'>$hLabel</th>";
                    }
                }
            }
        }

        $strHead .= "</tr>";

        /* --------------------------------
         * body
         * --------------------------------*/
        $row_id = "";
        $jenis_kdata = $mode;
        if (count($masterData) > 0) {
            //     foreach ($masterData as $jenis_kdata => $level_data) {
            // $pluss = $jenis_kdata;
            // arrPrintPink($level_data);

            /* --------------------------------------------------------------------
             * TBODY
             * --------------------------------------------------------------------*/
            $strBody = "";
            $no = 0;
            $modul_path = isset($modul_path) ? $modul_path : base_url() . "penjualan/";
            $jenistr = isset($jenisTr) ? $jenisTr : "582";
            // matiHere($jenistr);
            $count_id = 222;
            $row_id = "";
            // arrPrintKuning($level_data);
            // arrPrintKuning($level_header_00);
            // arrPrintKuning($myMenuData);
            foreach ($masterData as $master_datum) {
                $no++;
                $count_id++;
                $jenis = isset($master_datum['jenis']) ? $master_datum['jenis'] : 0;
                $minim = isset($master_datum['minim']) ? $master_datum['minim'] : 0;
                $db_id = isset($master_datum['id']) ? $master_datum['id'] : "";
                $mMenu = $myMenuData[$db_id];
                // arrPrintPink($mMenu);
                // arrPrint($mMenu["758"]);

                $row_id = "row_" . $jenis_kdata . "_$count_id";
                $strBody .= "<tr id='$row_id'>";
                $strBody .= "<td>$no</td>";
                foreach ($arrHeaders as $kolom => $attrs) {
                    $td_id = $kolom . "_" . $count_id;
                    // $nilai = $master_datum[$kolom];
                    // $nilai = isset($master_datum[$kolom]) ? $master_datum[$kolom] : (is_numeric($master_datum[$kolom]) ? 0 : "-");
                    $nilai = isset($master_datum[$kolom]) ? (is_numeric($master_datum[$kolom]) ? $master_datum[$kolom] * 1 : $master_datum[$kolom]) : "";

                    $attr = isset($attrs['attr']) ? $attrs['attr'] : "";
                    $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;
                    $nilai_f = isset($attrs['format']) ? ($nilai > 0 ? $attrs['format']($format_key, $nilai, $jenistr, $modul_path) : $nilai) : $nilai;

                    if (isset($attrs['links'])) {
                        // matiHere();
                        $modal_size = isset($attrs['links']['modal_size']) ? $attrs['links']['modal_size'] : "";
                        $title_head_key = isset($attrs['links']['title_head_key']) ? $attrs['links']['title_head_key'] : "";
                        $title_head = isset($attrs['links']['title_head_key']) ? (isset($master_datum[$title_head_key]) ? $master_datum[$title_head_key] : 'none') : $nilai;
                        $link_title = isset($attrs['links']['title']) ? $attrs['links']['title'] : "";
                        $strTitle_head = urlencode(trim("$link_title $title_head"));
                        // cekHere("$strTitle_head");
                        $reqKey = isset($attrs['links']['key']) ? $attrs['links']['key'] : "";
                        $reqValue = isset($master_datum[$reqKey]) ? $master_datum[$reqKey] : "none";
                        $linking = isset($attrs['links']['target']) ? $attrs['links']['target'] . "?$strGet" . "&$reqKey=$reqValue&modalSize=$modal_size" : "";
                        $linkDetile = base_url() . $linking . "";
                        $linkModal = modalDialogBtn("$strTitle_head", $linkDetile);
                        $nilai_link = isset($attrs['links']['target']) ? "<a href='javascript:void(0);' onclick=\"$linkModal\" title='$link_title'>$nilai_f</a>" : $nilai_f;
                    }
                    else {
                        // $linking = isset($attrs['link']) ? $attrs['link'] . "/" : "";
                        // $linkDetile = base_url() . $linking . "";
                        // $linkModal = modalDialogBtn("$nilai", $linkDetile);
                        $nilai_link = $nilai_f;
                    }

                    if (isset($attrs['tipe_input'])) {
                        $tipe_input = $attrs['tipe_input'];
                        $click_fx = isset($attrs['onclick_fx']) ? $attrs['onclick_fx'] : "";
                        // arrPrintKuning($master_datum);
                        // $nilai = isset($master_datum[$kolom]) ? (is_numeric($master_datum[$kolom]) ? $master_datum[$kolom] * 1 : $master_datum[$kolom]) : "";

                        switch ($tipe_input) {
                            case "checkbox":
                                // if ($kolom == "status") {
                                $link_hapus = "";
                                $checked = $nilai == 1 ? "checked" : "";
                                // $strBody .= "<td $attr id='$td_id'>";

                                // $strBody .= "<div class='funkyradio'>";
                                $nilai_link = "<div class='funkyradio-success'>";
                                $nilai_link .= "<input type='checkbox' $checked onclick=\"$click_fx('$db_id', '$row_id');\">";
                                $nilai_link .= "</div>";
                                // $strBody .= "</div>";

                                // $strBody .= "</td>";
                                // }
                                break;
                        }
                    }
                    // $linking = isset($attrs['link']) ? $attrs['link'] . "/$ksr_id" : "";
                    // $linkDetile = base_url() . $linking . "";
                    // $linkModal = modalDialogBtn("'$nama'", $linkDetile);
                    // $nilai_link = isset($attrs['link']) ? "<a href='javascript:void(0);' onclick=\"$linkModal\" title='lihat komposisi'>$nilai_f</a>" : $nilai_f;


                    if ($kolom == "action") {
                        $link_hapus = base_url() . "diskon/Setting/do_delete_member?jn=$jenis&minim=$minim&ctr=$my_controler&div=$my_div";
                        $strBody .= "<td $attr id='$td_id'>";
                        $strBody .= "<div class='btn-group'><button type='button' class='btn btn-link btn-sm' id='$td_id' onclick=\"btn_edit_$pluss('$row_id');\"><i class='fa fa-pencil'></i></button>";
                        $strBody .= "<button type='button' class='btn btn-sm btn-link' onclick=\"btn_alert_result('Oppss','akan meghapus setting diskon member?','$link_hapus');\"><i class='fa fa-trash'></i></button></div>";
                        $strBody .= "</td>";
                    }
                    else {
                        $strBody .= "<td $attr id='$td_id'>$nilai_link</td>";
                    }

                    if (isset($attrs['summary'])) {
                        if (!isset($totals[$kolom])) {
                            $totals[$kolom] = 0;
                        }
                        $totals[$kolom] += $nilai;
                    }
                }

                foreach ($arrHeadersGroup as $gkey => $gparams) {
                    $heTransaksi_ui = isset($gparams['heTransaksi_ui']) ? $gparams['heTransaksi_ui'] : array();
                    // arrPrintHijau($heTransaksi_ui);
                    foreach ($heTransaksi_ui as $mdl) {
                        $stepDatums = $stepErp[$mdl];
                        foreach ($stepDatums as $step => $stepDatum) {
                            foreach ($childs as $ky => $childrens) {
                                $mmenu_00 = $mMenu[$mdl];
                                $mmenu_0 = $mMenu[$mdl][$step];
                                // arrPrintPink($mmenu_0);
                                $checked_data = in_array($ky, $mmenu_0) ? "checked" : "";
                                if ($checked_data == "checked") {
                                    $ncheckBox = "<i class='fa fa-check text-green'></i>";
                                    $check_class = "checked-green";
                                }
                                else {
                                    $ncheckBox = "<i class='fa fa-circle-o'></i>";
                                    $check_class = "checked-grey";
                                }

                                /* ------------------------------------------------------
                                 * logic supaya step 1 tidK BISA STEP 2
                                 * ------------------------------------------------------*/
                                $dis = "";
                                if($ruleAkses == 1){
                                    if(array_key_exists(1, $mmenu_00)){
                                        if($step == 2){
                                            $ncheckBox = "<i class='fa fa-times text-orange'></i>";
                                            $dis = "-no";
                                        }
                                    }
                                    elseif (array_key_exists(2, $mmenu_00)){
                                        if($step == 1){
                                            $ncheckBox = "<i class='fa fa-times text-orange'></i>";
                                            $dis = "-no";
                                        }
                                    }
                                    else{
                                        $dis = "";
                                    }
                                }

                                $child = $ky == 1 ? "c" : "child";
                                $title = "$mdl|$step|$ky";
                                $strBody .= "<td title='$title' class='text-center checked$dis $check_class $child ch-$ky' obid='$db_id' mdl='$mdl' crud='$ky' step='$step'>$ncheckBox</td>";
                            }
                        }
                    }
                }

                $strBody .= "</tr>";
            }
        } // count masterData
        // }
        // else {
        //     $strBody = "";
        //     $nilai = "transaksi";
        // }
        /* --------------------------------------------------------------------
        * TFOOD
        * --------------------------------------------------------------------*/
        // arrPrint($level_header);
        // arrPrint($level_header_00);
        $link_save = base_url() . "diskon/Setting/do_save_member";
        $strFoot = "";
        $strFoot .= "<form method='post' id='my_form_$pluss' action='$link_save' target='result'>";
        $strFoot .= "<tr class='bg-danger' id='form_input_$pluss'>";
        $strFoot .= "<th></th>";
        foreach ($arrHeaders as $kolom => $arrHeader) {
            $attrs = $arrHeader;

            $kolom_id = $kolom . "_value_" . $pluss;
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $hTipe = isset($arrHeader['tipe_input']) ? $arrHeader['tipe_input'] : "text";
            $attr = isset($attrs['attr_footer']) ? $attrs['attr_footer'] : (isset($attrs['attr']) ? $attrs['attr'] : "");
            $data_srcs = isset($attrs['data_srcs']) ? $attrs['data_srcs'] : array();
            // $nilai = isset($attrs['default_data']) ? ($kolom == 'jenis' ? $jenis_kdata : $attrs['default_data']) : "";
            // $nilai = isset($attrs['default_data']) ? ($kolom == 'jenis' ? $attrs['default_data'] : $jenis_kdata) : "";
            $nilai = isset($attrs['default_data']) ? $attrs['default_data'] : "";

            switch ($hTipe) {
                default:
                case "text":
                    $str_input = "<input type='$hTipe' form='my_form_$pluss' onclick=\"this.select()\" name='$kolom' id='$kolom_id' $attr value='$nilai'>";
                    break;
                case "select":
                    $str_input = "<select name='$kolom' form='my_form_$pluss' id='$kolom_id' $attr>";
                    $str_input .= "<option value=''>------</option>";
                    foreach ($data_srcs as $data_src) {
                        $str_input .= "<option>$data_src</option>";
                    }
                    $str_input .= "</select>";
                    break;
            }

            $strFoot .= "<th>$str_input</th>";
        }
        $strFoot .= "<input type='hidden' form='my_form_$pluss' name='tipe' id='tipe_value_$pluss' value='$tipe'><input type='hidden' form='my_form_$pluss' name='my_controler' value='$my_controler'>
                <input type='hidden' form='my_form_$pluss' name='minim_be' id='maxim_value_$pluss' value=''>
                <input type='hidden' form='my_form_$pluss' name='my_div' value='$my_div'>";
        $strFoot .= "</tr>";
        $strFoot .= "</form>";

        $tbl_id = "member";
        $strTbl = "";
        $strTbl .= "<style type='text/css'>
                        .table>thead>tr>td, .table>thead>tr>th, .table>tbody>tr>td {
                            vertical-align : middle !important;
                            padding : 3px 10px !important;
                        }
                        .table>thead>tr>th {
                            font-size: 0.8em;;
                        }
                        .table>tfoot>tr>th>select.form-control, .table>tfoot>tr>th>input.form-control, .table>tfoot>tr>th>input.btn {
                            height: 30px;
                            padding: 0 6px !important;
                            font-size: 1em;;
                        }
                        .btn {
                            padding: 1px 6px !important;
                        }
                        </style>";
        $strTbl .= "<div class='table-responsive tblid_$tbl_id' >";
        $strTbl .= "<lable class='text-uppercase'>$jenis_kdata</lable>";
        $strTbl .= "<lable class='text-uppercase'><button type='button' class='text-uppercase' id='showhidemastertransaksi'>*show hide master</button></lable>";
        $strTbl .= "<table border='1' class='table table-condensade table-striped table-hover-color-red' style='margin=0' id='$tbl_id'>";
        $strTbl .= "<thead class='text-uppercase'>";
        $strTbl .= $strHead;
        $strTbl .= "</thead>";
        $strTbl .= "<tbody>";
        $strTbl .= $strBody;
        $strTbl .= "</tbody>";

        // $strTbl .= "<form>";
        $strTbl .= "<tfoot>";
        // $strTbl .= $strFoot;
        $strTbl .= "</tfoot>";
        // $strTbl .= "</form>";

        $strTbl .= "</table>";
        $strTbl .= "</div>";

        $base = MODUL_PATH . "Setting/setTransaksi";
        $strTbl .= "<script>
            var ruleAkses = Number('$ruleAkses');
            function not_checked(){
                $('.checked').off();
                $('.checked-no').off();
            
                $('.checked').on('click', function() {
                    // let data = $(this).data('subjek');
                    let id = $(this).attr('obid');
                    let mdl = $(this).attr('mdl');
                    let crud = $(this).attr('crud');
                    let step = Number($(this).attr('step'));
                    let steptarget = step === 2 ? 1 : (step === 1) ? 2 : '';
                    let url = '$base';
            
                    if($(this).hasClass('checked-green')){
                        console.log('cek');
                        $.get(url,{id:id,mdl:mdl,crud:crud,step:step},function() {
                          // console.log('cek send ' + url);
                        });
                        
                        $('td[obid=\"'+id+'\"][mdl=\"'+mdl+'\"][crud=\"'+crud+'\"][step=\"'+step+'\"]').removeClass('checked-green').addClass('checked-grey');
                        $('td[obid=\"'+id+'\"][mdl=\"'+mdl+'\"][crud=\"'+crud+'\"][step=\"'+step+'\"] i').removeClass('fa fa-check text-green').addClass('fa fa-circle-o');
                        
                        if(ruleAkses === 1){                            
                            if(step === 2 || step === 1){                            
                                var xx = $('td[obid=\"'+id+'\"][mdl=\"'+mdl+'\"][step=\"'+step+'\"]');
                                var total = 0;
                                jQuery.each(xx, function(a,b){
                                    
                                    if($(b).hasClass('checked-green')){
                                        total++;
                                    }
                                });
                                console.log(total);
                                
                                if(total === 0 ){                            
                                    console.log('masuk', steptarget);
                                    $('td[obid=\"'+id+'\"][mdl=\"'+mdl+'\"][step=\"'+steptarget+'\"]').removeClass('checked-no').addClass('checked');
                                    $('td[obid=\"'+id+'\"][mdl=\"'+mdl+'\"][step=\"'+steptarget+'\"] i').removeClass('fa fa-times text-orange').addClass('fa fa-circle-o');
                                }
                            }
                            else {
                                // console.log('ora masuk');
                            }
                        }
                    }
                    else {
                        url = url + '?id='+ id + '&mdl=' + mdl + '&crud=' + crud + '&step=' + step;
                        // console.log('un-cek', url);
                        $('#result_bottom').load(url);
                        
                        $('td[obid=\"'+id+'\"][mdl=\"'+mdl+'\"][crud=\"'+crud+'\"][step=\"'+step+'\"]').removeClass('checked-grey').addClass('checked-green');
                        $('td[obid=\"'+id+'\"][mdl=\"'+mdl+'\"][crud=\"'+crud+'\"][step=\"'+step+'\"] i').removeClass('fa fa-circle-o').addClass('fa fa-check text-green');
                        
                        if(ruleAkses === 1){                            
                            if(step === 2 || step === 1){                                   
                                console.log('masuk', steptarget);
                                $('td[obid=\"'+id+'\"][mdl=\"'+mdl+'\"][step=\"'+steptarget+'\"]').removeClass('checked checked-green').addClass('checked-no');
                                $('td[obid=\"'+id+'\"][mdl=\"'+mdl+'\"][step=\"'+steptarget+'\"] i').removeClass('fa fa-circle-o').addClass('fa fa-times text-orange');
                            }
                            else {
                                // console.log('ora masuk');
                            }
                        }
                    }
                    
                    not_checked();
                    // location.reload();
                    // console.log('id:', id);
                    // console.log('mdl:', mdl);
                    // console.log('step:', step);
                    // console.log('klik kolom');
                    // console.log('data:', data);
                    // alert('Kolom dengan class checked diklik!');
                });
            }
            not_checked();

        </script>";

        // $strTbl .= "<script>
        //
        //        function btn_edit_$pluss(r) {
        //            // console.log(r);
        //             var row_sumber = $('td',$('#'+r));
        //
        //             var objek = {};
        //             jQuery.each(row_sumber, function(a,b) {
        //                   var nilai = $(b).html()
        //
        //                   objek[a] = nilai;
        //             })
        //
        //
        //             var row_target = $('th',$('#form_input_$pluss'));
        //             var last_key = row_target.length - 1;
        //             // console.log(last_key);
        //             jQuery.each(row_target, function(c,d) {
        //                 var nilai = $('input',$(d));
        //                 var nilai_select = $('select',$(d));
        //
        //                 if(c != last_key){
        //                     $(nilai).val(objek[c]);
        //                     $(nilai_select).val(objek[c]);
        //                 }
        //
        //             })
        //
        //             if(typeof (r)){
        //                 // alert('ok');
        //                 $('tr').css('background-color','');
        //                 $('#'+r).css('background-color','#ff00007d');
        //                 $('#jenis_value_$pluss').prop('readonly', true);
        //                 $('#minim_value_$pluss').prop('readonly', true);
        //             }
        //
        //        }
        //
        //        //   minim_values
        //     $('#minim_value_$pluss').blur(function() {
        //         // var row_sumber = $row_id;
        //         var row_sumber = $('td',$('#$row_id'));
        //         var objek = {};
        //         jQuery.each(row_sumber, function(a,b) {
        //             var nilai = $(b).html()
        //
        //             objek[a] = nilai;
        //         })
        //
        //         console.log('row_id :: $row_id');
        //         console.log(objek);
        //         var last_minim = objek['2'];
        //         var now_minim = $('#minim_value_$pluss').val();
        //
        //         if(Number(now_minim) <= Number(last_minim)){
        //             swal({
        //                 title: 'Opsss.. !!',
        //                 html: 'minimal transaksi harus lebih besar dari ' + last_minim + ' sekarang ' + now_minim
        //             });
        //
        //             $('#minim_value_$pluss').css('background-color','#fff700ad');
        //         }
        //         else {
        //             $('#minim_value_$pluss').css('background-color','');
        //             $('#maxim_value_$pluss').val(last_minim);
        //         }
        //     });
        //
        //         $('#level_1_value_$pluss').blur(function() {
        //             var row_id = Number($row_id);
        //             var row_sumber = $('td',$('#'+(row_id-1)));
        //             var objek = {};
        //             jQuery.each(row_sumber, function(a,b) {
        //             var nilai = $(b).html()
        //
        //                 objek[a] = nilai;
        //             })
        //
        //             console.log('row_id :: $row_id || ' + row_sumber + row_id);
        //             console.log(objek);
        //
        //     });
        //     </script>";

        $member = "";
        $member .= $strTbl;
        // endregion


        if (!isset($_GET['tpl'])) {
            echo $member;
        }
        else {

            $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/setting.html");
            $p->addTags(
                array(
                    "menu_left"       => callMenuLeft(),
                    "trans_menu"      => callTransMenu(),
                    "float_menu_atas" => callFloatMenu('atas'),
                    // "float_menu_bawah" => callFloatMenu(),
                    // "menu_taskbar"     => callMenuTaskbar(),
                    // "btn_back"         => callBackNav(),
                    // "add_pihak"        => "",
                    // "pihak_label"      => "",
                    // "add_item"         => "",
                    // "selector_label"   => "",
                    // "tmp_request"      => "",
                    // "mobile_scan"      => "",
                    // "ext_tool"         => "",
                    // "submit_button"    => "",
                    "content"         => $strTbl,
                )
            );
            $p->render();
        }

        break;

    case "viewOther":
        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/diskon.html");
        $pluss = 0;
        // arrPrintHijau($level_data_0);
        $childs = array();
        foreach ($level_header as $kolom => $arrHeader) {
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $child = isset($arrHeader['child']) && $arrHeader['child'] == true ? 1 : 0;
            $parent_ky = isset($arrHeader['parent_ky']) ? $arrHeader['parent_ky'] : "";

            /* ----------------------------------------------------------------------
             * yg menjadi parent
             * ----------------------------------------------------------------------
             * */
            if ($child != true) {

                $level_header_0[$kolom] = $arrHeader;
            }

            if (!isset($arrHeader['parent'])) {

                $level_header_00[$kolom] = $arrHeader;
            }

            /* ----------------------------------------------------------------------
             * yg mnejadi anak
             * ----------------------------------------------------------------------
             * */
            if (isset($arrHeader['parent_ky'])) {

                $childs[$parent_ky][$kolom] = $arrHeader;
            }
        }

        /* --------------------------------------------------------------------
        * THEAD
        * --------------------------------------------------------------------*/
        $strHead = "";
        $strHead .= "<tr class='bg-info'>";
        $strHead .= "<th rowspan='2'>no</th>";
        foreach ($arrHeaders as $kolom => $arrHeader) {
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $child = isset($arrHeader['child']) && $arrHeader['child'] == true ? 1 : 0;
            $parent_ky = isset($arrHeader['parent_ky']) ? $arrHeader['parent_ky'] : "";
            $attr_header = isset($arrHeader['attr_header']) ? $arrHeader['attr_header'] : "";

            $colspan = isset($childs[$kolom]) ? count($childs[$kolom]) : 1;
            $rowspan = isset($childs[$kolom]) ? 1 : 2;

            $strHead .= "<th colspan='$colspan' rowspan='$rowspan' $attr_header>$hLabel</th>";
        }
        foreach ($childData as $mdl => $childDatum) {

            $availMenu = isset($childDatum["availMenu"]) ? $childDatum["availMenu"] : array();
            $colspan_0 = count($availMenu);
            $colspan = $colspan_0 > 0 ? $colspan_0 : 1;
            /* ---------------------------------------
             * yg di confignya tidak ada menu aktif tidak perlu ditampilkan
             * ---------------------------------------*/
            if ($colspan_0 > 0) {

                $label = isset($childDatum["label"]) ? $childDatum["label"] : $mdl;
                $strHead .= "<th class='btn-toggle-group g-$mdl' cls='$mdl' md='$mode' title='$mdl' colspan='$colspan' rowspan='1' $attr_header>$label&nbsp;<span class='badge badge-danger'>$colspan_0</span></th>";

                $childs[$mdl] = $availMenu;
            }
        }
        $strHead .= "</tr>";
        // arrPrint($childs);
        /* -----------------------------------------------------------------------------------
         * THEAD
         * header anakan
         * -----------------------------------------------------------------------------------
         * */
        $strHead .= "<tr class='bg-info'>";
        foreach ($childs as $ky => $childrens) {
            // arrPrint($childrens);
            $jmlAnak = count($childrens);
            // cekHere($jmlAnak);
            $nom = 0;
            foreach ($childrens as $kolom => $children) {
                $nom++;
                $duakeatas = $nom >= 2 ? "child ag-$ky" : "";
                $hLabel = isset($children['label']) ? $children['label'] : $kolom;
                $hattr_header = isset($children['attr_header']) ? $children['attr_header'] : "";

                $strHead .= "<th colspan='1' class='$duakeatas'>$hLabel</th>";
            }
        }
        $strHead .= "</tr>";

        $row_id = "";
        if (count($masterData) > 0) {
            //     foreach ($masterData as $jenis_kdata => $level_data) {
            // $pluss = $jenis_kdata;
            $pluss = $jenis_kdata = $mode;
            // arrPrintPink($level_data);

            /* --------------------------------------------------------------------
             * TBODY
             * --------------------------------------------------------------------*/
            $strBody = "";
            $no = 0;
            $modul_path = isset($modul_path) ? $modul_path : base_url() . "penjualan/";
            $jenistr = isset($jenisTr) ? $jenisTr : "582";
            // matiHere($jenistr);
            $count_id = 333;
            $row_id = "";
            // arrPrintKuning($level_data);
            // arrPrintKuning($level_header_00);
            foreach ($masterData as $master_datum) {
                $no++;
                $count_id++;
                $jenis = isset($master_datum['jenis']) ? $master_datum['jenis'] : 0;
                $minim = isset($master_datum['minim']) ? $master_datum['minim'] : 0;
                $db_id = isset($master_datum['id']) ? $master_datum['id'] : "";
                $mMenu = $myMenu[$db_id];

                $row_id = "row_" . $jenis_kdata . "_$count_id";
                $strBody .= "<tr id='$row_id'>";
                $strBody .= "<td>$no</td>";
                foreach ($arrHeaders as $kolom => $attrs) {
                    $td_id = $kolom . "_" . $count_id;
                    // $nilai = $master_datum[$kolom];
                    // $nilai = isset($master_datum[$kolom]) ? $master_datum[$kolom] : (is_numeric($master_datum[$kolom]) ? 0 : "-");
                    $nilai = isset($master_datum[$kolom]) ? (is_numeric($master_datum[$kolom]) ? $master_datum[$kolom] * 1 : $master_datum[$kolom]) : "";

                    $attr = isset($attrs['attr']) ? $attrs['attr'] : "";
                    $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;
                    $nilai_f = isset($attrs['format']) ? ($nilai > 0 ? $attrs['format']($format_key, $nilai, $jenistr, $modul_path) : $nilai) : $nilai;

                    if (isset($attrs['links'])) {
                        // matiHere();
                        $modal_size = isset($attrs['links']['modal_size']) ? $attrs['links']['modal_size'] : "";
                        $title_head_key = isset($attrs['links']['title_head_key']) ? $attrs['links']['title_head_key'] : "";
                        $title_head = isset($attrs['links']['title_head_key']) ? (isset($master_datum[$title_head_key]) ? $master_datum[$title_head_key] : 'none') : $nilai;
                        $link_title = isset($attrs['links']['title']) ? $attrs['links']['title'] : "";
                        $strTitle_head = urlencode(trim("$link_title $title_head"));
                        // cekHere("$strTitle_head");
                        $reqKey = isset($attrs['links']['key']) ? $attrs['links']['key'] : "";
                        $reqValue = isset($master_datum[$reqKey]) ? $master_datum[$reqKey] : "none";
                        $linking = isset($attrs['links']['target']) ? $attrs['links']['target'] . "?$strGet" . "&$reqKey=$reqValue&modalSize=$modal_size" : "";
                        $linkDetile = base_url() . $linking . "";
                        $linkModal = modalDialogBtn("$strTitle_head", $linkDetile);
                        $nilai_link = isset($attrs['links']['target']) ? "<a href='javascript:void(0);' onclick=\"$linkModal\" title='$link_title'>$nilai_f</a>" : $nilai_f;
                    }
                    else {
                        // $linking = isset($attrs['link']) ? $attrs['link'] . "/" : "";
                        // $linkDetile = base_url() . $linking . "";
                        // $linkModal = modalDialogBtn("$nilai", $linkDetile);
                        $nilai_link = $nilai_f;
                    }

                    if (isset($attrs['tipe_input'])) {
                        $tipe_input = $attrs['tipe_input'];
                        $click_fx = isset($attrs['onclick_fx']) ? $attrs['onclick_fx'] : "";
                        // arrPrintKuning($master_datum);
                        // $nilai = isset($master_datum[$kolom]) ? (is_numeric($master_datum[$kolom]) ? $master_datum[$kolom] * 1 : $master_datum[$kolom]) : "";

                        switch ($tipe_input) {
                            case "checkbox":
                                // if ($kolom == "status") {
                                $link_hapus = "";
                                $checked = $nilai == 1 ? "checked" : "";
                                // $strBody .= "<td $attr id='$td_id'>";

                                // $strBody .= "<div class='funkyradio'>";
                                $nilai_link = "<div class='funkyradio-success'>";
                                $nilai_link .= "<input type='checkbox' $checked onclick=\"$click_fx('$db_id', '$row_id');\">";
                                $nilai_link .= "</div>";
                                // $strBody .= "</div>";

                                // $strBody .= "</td>";
                                // }
                                break;
                        }
                    }
                    // $linking = isset($attrs['link']) ? $attrs['link'] . "/$ksr_id" : "";
                    // $linkDetile = base_url() . $linking . "";
                    // $linkModal = modalDialogBtn("'$nama'", $linkDetile);
                    // $nilai_link = isset($attrs['link']) ? "<a href='javascript:void(0);' onclick=\"$linkModal\" title='lihat komposisi'>$nilai_f</a>" : $nilai_f;


                    if ($kolom == "action") {
                        $link_hapus = base_url() . "diskon/Setting/do_delete_member?jn=$jenis&minim=$minim&ctr=$my_controler&div=$my_div";
                        $strBody .= "<td $attr id='$td_id'>";
                        $strBody .= "<div class='btn-group'><button type='button' class='btn btn-link btn-sm' id='$td_id' onclick=\"btn_edit_$pluss('$row_id');\"><i class='fa fa-pencil'></i></button>";
                        $strBody .= "<button type='button' class='btn btn-sm btn-link' onclick=\"btn_alert_result('Oppss','akan meghapus setting diskon member?','$link_hapus');\"><i class='fa fa-trash'></i></button></div>";
                        $strBody .= "</td>";
                    }
                    else {
                        $strBody .= "<td $attr id='$td_id'>$nilai_link</td>";
                    }

                    if (isset($attrs['summary'])) {
                        if (!isset($totals[$kolom])) {
                            $totals[$kolom] = 0;
                        }
                        $totals[$kolom] += $nilai;
                    }
                }

                foreach ($childs as $ky => $childrens) {
                    $nom = 0;
                    foreach ($childrens as $mdl => $children) {
                        $nom++;
                        $checked_data = in_array($mdl, $mMenu) ? "checked" : "";
                        if ($checked_data == "checked") {
                            // $ncheckBox = "<i class='fa fa-check-square text-red'></i>";
                            $ncheckBox = "<i class='fa fa-check text-red'></i>";
                        }
                        else {
                            $ncheckBox = "<i class='fa fa-circle-o'></i>";
                        }

                        $hLabel = isset($children['label']) ? $children['label'] : $kolom;
                        $hattr_header = isset($children['attr_header']) ? $children['attr_header'] : "";
                        // $hCheckBox = "<input type='checkbox' $checked_data>";
                        if ($checked_data == "checked") {
                            $ncheckBox = "<i class='fa fa-check text-red'></i>";
                            $check_class = "checked-green";
                        }
                        else {
                            $ncheckBox = "<i class='fa fa-circle-o'></i>";
                            $check_class = "checked-grey";
                        }
                        $clsChild = $nom > 1 ? "child ag-$ky" : "";
                        // $hCheckBox = $ncheckBox;
                        $strBody .= "<td class='text-center $check_class checked $clsChild ch-$ky' obid='$db_id' mdl='$mdl' crud='$ky' colspan='1' $hattr_header>$ncheckBox</td>";
                    }
                }
                $strBody .= "</tr>";

            }

        }
        // }
        // else {
        //     $strBody = "";
        //     $nilai = "transaksi";
        // }
        /* --------------------------------------------------------------------
        * TFOOD
        * --------------------------------------------------------------------*/
        // arrPrint($level_header);
        // arrPrint($level_header_00);
        $link_save = base_url() . "diskon/Setting/do_save_member";
        $strFoot = "";
        $strFoot .= "<form method='post' id='my_form_$pluss' action='$link_save' target='result'>";
        $strFoot .= "<tr class='bg-danger' id='form_input_$pluss'>";
        $strFoot .= "<th></th>";
        foreach ($arrHeaders as $kolom => $arrHeader) {
            $attrs = $arrHeader;

            $kolom_id = $kolom . "_value_" . $pluss;
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $hTipe = isset($arrHeader['tipe_input']) ? $arrHeader['tipe_input'] : "text";
            $attr = isset($attrs['attr_footer']) ? $attrs['attr_footer'] : (isset($attrs['attr']) ? $attrs['attr'] : "");
            $data_srcs = isset($attrs['data_srcs']) ? $attrs['data_srcs'] : array();
            // $nilai = isset($attrs['default_data']) ? ($kolom == 'jenis' ? $jenis_kdata : $attrs['default_data']) : "";
            // $nilai = isset($attrs['default_data']) ? ($kolom == 'jenis' ? $attrs['default_data'] : $jenis_kdata) : "";
            $nilai = isset($attrs['default_data']) ? $attrs['default_data'] : "";

            switch ($hTipe) {
                default:
                case "text":
                    $str_input = "<input type='$hTipe' form='my_form_$pluss' onclick=\"this.select()\" name='$kolom' id='$kolom_id' $attr value='$nilai'>";
                    break;
                case "select":
                    $str_input = "<select name='$kolom' form='my_form_$pluss' id='$kolom_id' $attr>";
                    $str_input .= "<option value=''>------</option>";
                    foreach ($data_srcs as $data_src) {
                        $str_input .= "<option>$data_src</option>";
                    }
                    $str_input .= "</select>";
                    break;
            }

            $strFoot .= "<th>$str_input</th>";
        }
        $strFoot .= "<input type='hidden' form='my_form_$pluss' name='tipe' id='tipe_value_$pluss' value='$tipe'><input type='hidden' form='my_form_$pluss' name='my_controler' value='$my_controler'>
                <input type='hidden' form='my_form_$pluss' name='minim_be' id='maxim_value_$pluss' value=''>
                <input type='hidden' form='my_form_$pluss' name='my_div' value='$my_div'>";
        $strFoot .= "</tr>";
        $strFoot .= "</form>";

        $tbl_id = "member";
        $strTbl = "";
        $strTbl .= "<style type='text/css'>
                        .table>thead>tr>td, .table>thead>tr>th, .table>tbody>tr>td {
                            vertical-align : middle !important;
                            padding : 3px 10px !important;
                        }
                        .table>thead>tr>th {
                            font-size: 0.8em;;
                        }
                        .table>tfoot>tr>th>select.form-control, .table>tfoot>tr>th>input.form-control, .table>tfoot>tr>th>input.btn {
                            height: 30px;
                            padding: 0 6px !important;
                            font-size: 1em;;
                        }
                        .btn {
                            padding: 1px 6px !important;
                        }
                        </style>";
        $strTbl .= "<div class='table-responsive tblid_$tbl_id' >";
        $strTbl .= "<lable class='text-uppercase'>$jenis_kdata</lable>";
        $strTbl .= "<lable class='text-uppercase'><button type='button' class='text-uppercase' id='showhidemasterother'>show hide master</button></lable>";
        $strTbl .= "<table border='1' class='table table-condensade table-striped table-hover-color-red' style='margin=0' id='$tbl_id'>";
        $strTbl .= "<thead class='text-uppercase'>";
        $strTbl .= $strHead;
        $strTbl .= "</thead>";
        $strTbl .= "<tbody>";
        $strTbl .= $strBody;
        $strTbl .= "</tbody>";

        // $strTbl .= "<form>";
        $strTbl .= "<tfoot>";
        // $strTbl .= $strFoot;
        $strTbl .= "</tfoot>";
        // $strTbl .= "</form>";

        $strTbl .= "</table>";
        $strTbl .= "</div>";

        $base = MODUL_PATH . "Setting/setOther";
        $strTbl .= "<script>
            $('.checked').on('click', function() {
                // let data = $(this).data('subjek');
                let id = $(this).attr('obid');
                let mdl = $(this).attr('mdl');
                let crud = $(this).attr('crud');
                let url = '$base';
                if($(this).hasClass('checked-green')){
                    console.log('cek');
                    $.get(url,{id:id,mdl:mdl,crud:crud},function() {
                      console.log('cek send ' + url);
                    });
                    
                    $('td[obid=\"'+id+'\"][mdl=\"'+mdl+'\"]').removeClass('checked-green').addClass('checked-grey');
                    $('td[obid=\"'+id+'\"][mdl=\"'+mdl+'\"] i').removeClass('fa fa-check text-red').addClass('fa fa-circle-o');
                }
                else {
                    url = url + '?id='+ id + '&mdl=' + mdl + '&crud=' + crud;
                    console.log('un-cek', url);
                    $('#result_bottom').load(url);
                    
                    $('td[obid=\"'+id+'\"][mdl=\"'+mdl+'\"]').removeClass('checked-grey').addClass('checked-green');
                    $('td[obid=\"'+id+'\"][mdl=\"'+mdl+'\"] i').removeClass('fa fa-circle-o').addClass('fa fa-check text-red');
                }
                
                // location.reload();
                console.log('id:', id);
                // console.log('klik kolom');
                // console.log('data:', data);
                // alert('Kolom dengan class checked diklik!');
            });
        </script>";
        $strTbl .= "<script>
        
               function btn_edit_$pluss(r) {
                   // console.log(r);
                    var row_sumber = $('td',$('#'+r));
            
                    var objek = {};
                    jQuery.each(row_sumber, function(a,b) {
                          var nilai = $(b).html()
                
                          objek[a] = nilai;
                    })
            
                    
                    var row_target = $('th',$('#form_input_$pluss'));
                    var last_key = row_target.length - 1;
                    // console.log(last_key);
                    jQuery.each(row_target, function(c,d) {
                        var nilai = $('input',$(d));
                        var nilai_select = $('select',$(d));
                        
                        if(c != last_key){                
                            $(nilai).val(objek[c]);
                            $(nilai_select).val(objek[c]);
                        }
                          
                    })
        
                    if(typeof (r)){
                        // alert('ok');
                        $('tr').css('background-color','');
                        $('#'+r).css('background-color','#ff00007d');
                        $('#jenis_value_$pluss').prop('readonly', true);
                        $('#minim_value_$pluss').prop('readonly', true);
                    }

               }
               
               //   minim_values
            $('#minim_value_$pluss').blur(function() {
                // var row_sumber = $row_id;
                var row_sumber = $('td',$('#$row_id'));
                var objek = {};
                jQuery.each(row_sumber, function(a,b) {
                    var nilai = $(b).html()
            
                    objek[a] = nilai;
                })
                
                console.log('row_id :: $row_id');
                console.log(objek);
                var last_minim = objek['2'];
                var now_minim = $('#minim_value_$pluss').val();
                
                if(Number(now_minim) <= Number(last_minim)){
                    swal({
                        title: 'Opsss.. !!',
                        html: 'minimal transaksi harus lebih besar dari ' + last_minim + ' sekarang ' + now_minim
                    });
                    
                    $('#minim_value_$pluss').css('background-color','#fff700ad');
                }
                else {
                    $('#minim_value_$pluss').css('background-color','');
                    $('#maxim_value_$pluss').val(last_minim);
                }
            });

                $('#level_1_value_$pluss').blur(function() {
                    var row_id = Number($row_id);
                    var row_sumber = $('td',$('#'+(row_id-1)));
                    var objek = {};
                    jQuery.each(row_sumber, function(a,b) {
                    var nilai = $(b).html()

                        objek[a] = nilai;
                    })

                    console.log('row_id :: $row_id || ' + row_sumber + row_id);
                    console.log(objek);

            });
            </script>";
        $member = "";
        $member .= $strTbl;

        if (!isset($_GET['tpl'])) {
            echo $member;
        }
        else {

            $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/setting.html");
            $p->addTags(
                array(
                    // "menu_left"        => callMenuLeft(),
                    // "trans_menu"       => callTransMenu(),
                    // "float_menu_atas"  => callFloatMenu('atas'),
                    // "float_menu_bawah" => callFloatMenu(),
                    // "menu_taskbar"     => callMenuTaskbar(),
                    // "btn_back"         => callBackNav(),
                    // "add_pihak"        => "",
                    // "pihak_label"      => "",
                    // "add_item"         => "",
                    // "selector_label"   => "",
                    // "tmp_request"      => "",
                    // "mobile_scan"      => "",
                    // "ext_tool"         => "",
                    // "submit_button"    => "",
                    "content" => $strTbl,
                )
            );
            $p->render();
        }

        break;

    case "viewAplikasi":
        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/profile.html");

        $id = $masterData->id;
        //------------------------------------------------------------------------------------
        $arrData = array(
            "ppn_faktor"   => array(
                "label" => "PPN",
                "kolom" => "nama_login",
                // "link"  => "Data/editone/User",
            ),
            "jenis_usaha"  => array(
                "label" => "Kelompok Usaha",
                "kolom" => "password",
                // "link"  => "Setting/editone/User",
                "type"  => "radio",
                "datas" => array(
                    1 => "PKP",
                    2 => "NON PKP",
                ),
                // "replaceValue" => "******",
            ),
            "jenis_bisnis" => array(
                "label" => "Jenis Bisnis",
                "kolom" => "email",
                // "link"  => "Data/editone/User",
                "type"  => "radio",
                "datas" => array(
                    1 => "BTB",
                    2 => "BTC",
                ),
            ),
        );
        $strMember = "";
        $strMember .= "<ul class=\"list-group list-group-unbordered\">";
        foreach ($arrData as $keField => $arrDatum) {
            $field = $arrDatum['kolom'];
            $dbNilai = $masterData->$keField;
            $type = $arrDatum['type'];
            $datas = $arrDatum['datas'];
            if (array_key_exists("replaceValue", $arrDatum)) {
                if (is_array($arrDatum['replaceValue'])) {
                    $nilai = $arrDatum['replaceValue'][$dbNilai];
                }
                else {
                    $nilai = $arrDatum['replaceValue'];
                }
            }
            else {
                $nilai = $dbNilai;
            }

            if ($nilai == "") {

            }
            switch ($type) {
                case "radio":
                    $nilai = "<div class='wwrapper-radio' style='margin-top: -3px;'>";
                    foreach ($datas as $ky => $data) {
                        $checked = $dbNilai == $ky ? "checked" : "";
                        if ($ky == 1) {
                            $id_toggle = "toggle-on-$keField";
                            $class_toggle = "toggle-left";
                            $radius = "border-radius: 5px 0 0 5px;";
                        }
                        else {
                            $id_toggle = "toggle-off-$keField";
                            $class_toggle = "toggle-right";
                            $radius = "border-radius: 0 5px 5px 0;";
                        }

                        $nilai .= "<input id='$id_toggle' $checked class='toggle-radio $class_toggle' mid='$id' lb='$data' name='$keField' value='false' type='radio'>
                            <label for='$id_toggle' class='btn-radio' style='$radius'>$data</label>";
                    }
                    $nilai .= "</div>";

                    break;
                default:
                    $nilai = "<input type='number' mid='$id' id='$keField' class='form-control-mini text-right' value='$dbNilai'>";
                    break;
            }

            if (isset($arrDatum['format'])) {
                $nilai_f = $arrDatum['format']($field, $nilai);
            }
            else {
                $nilai_f = $nilai;
            }
            if (isset($arrDatum['link'])) {

                $href = strlen($arrDatum['link']) == 0 ? "" : "href='" . base_url() . $arrDatum['link'] . "/$field' data-toggle='modal' data-target='#myModal'";
                $nilai_f = strlen($nilai_f) > 0 ? $nilai_f : "<i class='fa fa-pencil-square-o'></i>";
                $nilai_l = "<a $href class='pull-right' title='edit' data-toggle='tooltip'>$nilai_f</a>";
            }
            else {
                $nilai_l = "<span class='pull-right'>$nilai_f</span>";
            }
            $strMember .= "<li class=\"list-group-item\">";
            $strMember .= "<b>" . $arrDatum['label'] . "</b> $nilai_l";
            $strMember .= "</li>";
        }
        $strMember .= "</ul>";

        $p->setLayoutBoxCss("box box-danger box-solid");
        $p->setLayoutBoxHeading("Applikasi Setting");
        $p->setLayoutBoxBody(true);
        $showProfile .= $p->layout_box("$strMember");

        // ----------------------------------------------------------------
        $strProfile = "";
        $link = "Setting/formCp";
        foreach ($arrHeaders as $pkey => $pdatums) {
            $label = $pdatums['label'];
            $attr = $pdatums['attr'];
            $format = isset($pdatums['format']) ? $pdatums['format'] : "";
            $icon = isset($pdatums['icon']) ? $pdatums['icon'] : "fa-genderless";
            $nilai = isset($masterData->$pkey) ? $masterData->$pkey : "-";
            $id = $masterData->id;
            $nilai_f = isset($pdatums['format']) ? $pdatums['format']($nilai) : $nilai;
            $nilai_g = urlencode($nilai);
            $strProfile .= "<strong class='text-capitalize'><i class='fa $icon margin-r-5'></i>";
            $strProfile .= "$label";
            $strProfile .= "</strong>";
            $strProfile .= "<p $attr>";
            $strProfile .= $nilai_f;
            $href = MODUL_PATH . $link . "/$pkey?v=$nilai_g&f=$format&i=$id' data-toggle='modal' data-target='#myModal'";
            $strProfile .= "<a href='$href'  class='pull-right'><i class='fa fa-edit'></i></a>";
            $strProfile .= "</p>";
            $strProfile .= "<hr>";
        }
        $p->setLayoutBoxCss("box box-success");
        $p->setLayoutBoxHeading("Profile Perusahaan");
        $p->setLayoutBoxBody(true);
        $showProfile .= $p->layout_box("$strProfile");

        //------------------------------------------------------------------------------------
        $leftProfile = $showProfile;

        /* ----------------------------------------------------
         * sebelah kanan
         * ----------------------------------------------------*/
        $content = "";
        $content .= "ini catatan semata";
        $p->setLayoutBoxCss("box box-info");
        $p->setLayoutBoxHeading("Catatan");
        $p->setLayoutBoxBody(true);
        //        $elementLabels['rightConten'] = $p->layout_box(print_r($arrActivitylog, true));
        $rightConten = $p->layout_box($content);

        $strTbl = "";
        $strTbl .= "<div>";
        $strTbl .= "anu";
        $strTbl .= "</div>";

        $baseUrl = MODUL_PATH;
        $scriptBottom = "";
        if(my_id() == 2){
            $scriptBottom .= "<script>
   
              $('input[type=\"radio\"].toggle-radio').on('change', function() {
                  console.log('kehed');
                    // Ambil ID dan nilai dari radio button yang dipilih
                    const selectedMid = $(this).attr('mid');
                    const selectedName = $(this).attr('name');
                    const selectedId = $(this).attr('id');
                    const selectedValue = $(this).val();
                    const labelText = $(this).next('label').text();
                    let labelTextLc = labelText.toLowerCase();
            
                    // Tampilkan Swal untuk konfirmasi
                    Swal.fire({
                        title: 'Konfirmasi Pilihan',
                        text: `Anda memilih ` +labelText + `. Apakah Anda yakin? Jika benar maka seluruh id yang aktif harus melakukan login ulang `,
                        icon: 'question',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, lanjutkan',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Jika konfirmasi OK, lakukan aksi
                            if (selectedId === 'toggle-on-' + selectedName) {
                                console.log( labelText + ' dipilih');
                                const url = '$baseUrl/Setting/doSaveCp/' + selectedName + '/' + selectedMid;
                                const data = {
                                            ['new_' + selectedName]: 1,    
                                            [selectedName + '_label']: labelTextLc, 
                                        };
                                sendAjaxRequest(url,data);     
                                // console.log(data);                           
                            } 
                            else if (selectedId === 'toggle-off-' + selectedName) {
                                console.log(labelText + ' dipilih');                     
                                const url = '$baseUrl/Setting/doSaveCp/' + selectedName + '/' + selectedMid;
                                const data = {
                                            ['new_' + selectedName]: 2,  
                                            [selectedName + '_label']: labelTextLc, 
                                        };
                                                                
                                sendAjaxRequest(url,data);
                                
                                // console.log(data);  
                            }
                                                                                    
                        } else {
                            // Jika dibatalkan, reset pilihan
                            console.log('batal', selectedId);

                            if(selectedId === 'toggle-on-jenis_usaha'){                                
                                $(`#toggle-off-jenis_usaha`).prop('checked', true);
                            }
                            else {
                                $(`#toggle-on-jenis_usaha`).prop('checked', true);
                            }
                        }
                    });
                });
              
                function sendAjaxRequest(url, data) {
                    $.ajax({
                        url: url, // URL yang diterima sebagai parameter
                        type: 'POST',
                        data: data, // Data yang diterima sebagai parameter
                        success: function (response) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: 'Data berhasil disimpan.',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            console.log('Data berhasil dikirim:', response);
                        },
                        error: function (xhr, status, error) {
                            Swal.fire({
                                title: 'Error',
                                text: 'Gagal menyimpan data. Silakan coba lagi.',
                                icon: 'error'
                            });
                            console.error('Kesalahan AJAX:', error);
                        }
                    });
                }
                
                let isEditing = false;
                let initialValue = null;
                $(document).on('click', 'input.form-control-mini', function (e) {
                    const input = $(this); // Ambil elemen input yang diklik
                    const currentValue = input.val(); // Ambil nilai saat ini dari input
                
                    Swal.fire({
                        title: 'Konfirmasi Edit',
                        text: `Nilai saat ini: ` + currentValue + `% Apakah Anda ingin mengedit? merubah nilainya akan memaksa seluruh yang login ke logout`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Edit',
                        cancelButtonText: 'Tidak',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            isEditing = true; // Set flag sebagai sedang mengedit
                            initialValue = currentValue;
                            input.prop('readonly', false); // Aktifkan input untuk diedit
                            input.focus().select(); // Fokuskan ke input untuk memudahkan pengeditan
                        } else {
                            input.prop('readonly', true); // Tetap nonaktifkan input
                        }
                    });
                
                    e.preventDefault(); // Mencegah aksi default jika ada
                });
                
                // Event listener untuk menyimpan perubahan (opsional)
                $(document).on('blur', 'input.form-control-mini', function () {
                     if (isEditing) {
                         
                        const input = $(this); // Ambil elemen input
                        const field = input.attr('id'); // Ambil nilai yang diperbarui
                        const updatedValue = input.val(); // Ambil nilai yang diperbarui
                        const selectedMid = input.attr('mid'); // Ambil nilai yang diperbarui
                         isEditing = false;
                        input.prop('readonly', true); // Nonaktifkan input setelah perubahan
                        if (updatedValue !== initialValue) {                            
                            console.log('Nilai baru:', updatedValue);
                            const url = '$baseUrl/Setting/doSaveCp/' + field + '/' + selectedMid;
                            const data = {
                                        ['new_' + field]: updatedValue   
                                    };
                            sendAjaxRequest(url,data);
                        }
                     }
                });
            </script>";
        }

        $p->addTags(
            array(
                "menu_left"        => callMenuLeft(),
                "trans_menu"       => callTransMenu(),
                "float_menu_atas"  => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar"     => callMenuTaskbar(),
                "btn_back"         => callBackNav(),
                "leftProfile"      => $leftProfile,
                "rightConten"      => $rightConten,
                // "add_item"         => "",
                // "selector_label"   => "",
                // "tmp_request"      => "",
                // "mobile_scan"      => "",
                // "ext_tool"         => "",
                // "submit_button"    => "",
                "content"          => $strTbl,
                "scriptBottom"     => $scriptBottom,
            )
        );
        $p->render();
        // }
        break;

    case "modal":
        $ly = new Layout();
        $footer = "";
        // arrPrint($forms);
        if (isset($forms)) {
            $ly->setFormGroupLeftClass("col-sm-3 text-right");
            $ly->setFormGroupRightClass("col-sm-8");

            $forms_viewe = "<div class='overflow-h'>";
            if (is_array($forms)) {
                foreach ($forms as $label => $nilai) {
                    $forms_viewe .= $ly->form_group($label, $nilai);
                }
            }
            else {
                $forms_viewe .= $forms;
            }
            $forms_viewe .= "</div>";
            if (sizeof($field) > 5) {
                $forms_viewe .= form_hidden("field", "$field");
            }
        }
        else {
            $forms_viewe = "kosong";
        }
        if (isset($notes) && (strlen($notes) > 2)) {
            $forms_viewe .= $notes;
            // $forms_viewe .= "<div class='alert bg-yellow-light no-margin'>****</div>";
        }
        $footer .= form_button('close', 'Close', "class='btn pull-left' data-dismiss='modal'");
        $footer .= form_submit('submit', 'Simpan', "class='btn btn-primary pull-right'");
        if (isset($heading)) {
            $ly->setLayoutModalHeader("$heading", true);
        }
        $ly->setLayoutModalBody("$forms_viewe");
        $ly->setLayoutModalFooter("$footer");

        $att = array(
            "class"  => "form-horizontal",
            "target" => $target,
        );
        $mdl = form_open($actions, $att);
        $mdl .= $ly->layout_modal();
        $mdl .= form_close();
        $mdl .= "<script>
                $('.modal').on('shown.bs.modal', function() {
                  $(this).find('[autofocus]').focus();
                });
            </script>";
        $mdl .= "<script>
                $(document).ready(function () {
                    // Load data provinsi saat halaman dimuat
                    $.ajax({
                        url: '$propinsiData', // URL untuk mengambil data provinsi
                        method: 'GET',
                        success: function (response) {
                            // console.log('Response:', response); // Lihat isi respons
                            const data = typeof response === 'string' ? JSON.parse(response) : response;
                             // console.log('Parsed Data:', data);
                           
                            if (Array.isArray(data)) {
                                $('#propinsi').append(
                                    data.map(prov => {
                                        // console.log(prov);
                                         return `<option value=\"` + prov['id'] + `\">` + prov['nama'] + `</option>`;
                                //          return `<option value=\"${prov.id}\">** ${prov.nama}</option>`;
                                    }).join('')
                                );
                                                                
                            } 
                            else {
                                console.error('Data bukan array:', data);
                                alert('Gagal memuat data. Respons server tidak valid.');
                            }
                            
                        }

                        // success: function (data) {
                        //     console.log(data);
                        //     $('#propinsi').append(data.map(prov => `<option value=\"${prov.id}\">${prov.nama}</option>`));
                        // },
                        // error: function () {
                        //     alert('Gagal memuat data provinsi.');
                        // }
                    });
                
                    // Event ketika provinsi dipilih
                    $('#propinsi').on('change', function () {
                        const propinsiId = $(this).val();
                        $('#kabupaten').empty().append('<option value=\"\">Pilih Kabupaten</option>').prop('disabled', false);
                        $('#kecamatan').empty().append('<option value=\"\">Pilih Kecamatan</option>').prop('disabled', false);
                        $('#kelurahan').empty().append('<option value=\"\">Pilih Kelurahan</option>').prop('disabled', false);
                        $('#postal').empty().append('<option value=\"\">Pilih kode pos</option>').prop('disabled', false);
                
                        // console.log(propinsiId)
                        
                        if (propinsiId) {
                            $.ajax({
                                url: '$kabupatenData' + '/' + propinsiId, // URL untuk mengambil data kabupaten berdasarkan provinsi
                                method: 'GET',
                                success: function (response) {
                                    // console.log('Response:', response); // Lihat isi respons
                                    const data = typeof response === 'string' ? JSON.parse(response) : response;
                                    // console.log('Parsed Data:', data);
                                   
                                    if (Array.isArray(data)) {
                                        $('#kabupaten').append(
                                            data.map(kab => {
                                                // console.log('kab', kab);
                                                return `<option value=\"` + kab['id'] + `\">` + kab['nama'] + `</option>`;
                                            }).join('')
                                        );
                                                                        
                                    } 
                                    else {
                                        console.error('Data bukan array:', data);
                                        alert('Gagal memuat data. Respons server tidak valid.');
                                    }                                    
                                }
                                
                                // success: function (data) {
                                //     $('#kabupaten').append(data.map(kab => `<option value=\"` + kab['id'] + `\">` + kab['nama'] + `</option>`)).prop('disabled', false);
                                //     // $('#kabupaten').append(data.map(kab => `<option value=\"${kab.id}\">${kab.nama}</option>`)).prop('disabled', false);
                                // },
                                // error: function () {
                                //     alert('Gagal memuat data kabupaten.');
                                // }
                            });
                        }
                    });
                
                    // Event ketika kabupaten dipilih
                    $('#kabupaten').on('change', function () {
                        const kabupatenId = $(this).val();
                        $('#kecamatan').empty().append('<option value=\"\">Pilih Kecamatan</option>').prop('disabled', false);
                        $('#kelurahan').empty().append('<option value=\"\">Pilih Kelurahan</option>').prop('disabled', false);
                        $('#postal').empty().append('<option value=\"\">Pilih kode pos</option>').prop('disabled', false);
                
                        if (kabupatenId) {
                            $.ajax({
                                url: '$kecamatanData' + '/' + kabupatenId, // URL untuk mengambil data kabupaten berdasarkan provinsi
                                method: 'GET',
                                success: function (response) {
                                    // console.log('Response:', response); // Lihat isi respons
                                    const data = typeof response === 'string' ? JSON.parse(response) : response;
                                    // console.log('Parsed Data:', data);
                                   
                                    if (Array.isArray(data)) {
                                        $('#kecamatan').append(
                                            data.map(kab => {
                                                // console.log('kab', kab);
                                                return `<option value=\"` + kab['id'] + `\">` + kab['nama'] + `</option>`;
                                            }).join('')
                                        );
                                                                        
                                    } 
                                    else {
                                        console.error('Data bukan array:', data);
                                        alert('Gagal memuat data. Respons server tidak valid.');
                                    }                                    
                                }
                                
                                
                                // success: function (data) {
                                //     $('#kecamatan').append(data.map(kec => `<option value=\"${kec.id}\">${kec.nama}</option>`)).prop('disabled', false);
                                // },
                                // error: function () {
                                //     alert('Gagal memuat data kecamatan.');
                                // }
                            });
                        }
                    });
                
                });

            </script>";
        $mdl .= "<script>
                    // Event ketika kecamatan dipilih
                    $('#kecamatan').on('change', function () {
                        const kecamatanId = $(this).val();
                        $('#kelurahan').empty().append('<option value=\"\">Pilih Kelurahan</option>').prop('disabled', false);
                        $('#postal').empty().append('<option value=\"\">Pilih kode pos</option>').prop('disabled', false);
                
                        if (kecamatanId) {
                            $.ajax({
                                url: '$kelurahanData' + '/' + kecamatanId, // URL untuk mengambil data kabupaten berdasarkan provinsi
                                method: 'GET',
                                
                                success: function (response) {
                                    // console.log('Response:', response); // Lihat isi respons
                                    const data = typeof response === 'string' ? JSON.parse(response) : response;
                                    // console.log('Parsed Data:', data);
                                   
                                    if (Array.isArray(data)) {
                                        $('#kelurahan').append(
                                            data.map(kab => {
                                                // console.log('kab', kab);
                                                return `<option value=\"` + kab['id'] + `\">` + kab['nama'] + `</option>`;
                                            }).join('')
                                        );
                                                                        
                                    } else {
                                        console.error('Data bukan array:', data);
                                        alert('Gagal memuat data. Respons server tidak valid.');
                                    }                                    
                                }
                            });
                            
                            $.ajax({
                                url: '$postalData' + '/' + kecamatanId, // URL untuk mengambil data kabupaten berdasarkan provinsi
                                method: 'GET',
                                
                                success: function (response) {
                                    // console.log('Response:', response); // Lihat isi respons
                                    const data = typeof response === 'string' ? JSON.parse(response) : response;
                                    // console.log('Parsed Data:', data);
                                   
                                    if (Array.isArray(data)) {
                                        $('#postal').append(
                                            data.map(kab => {
                                                // console.log('kab', kab);
                                                return `<option value=\"` + kab['id'] + `\">` + kab['nama'] + `</option>`;
                                            }).join('')
                                        );
                                                                        
                                    } else {
                                        console.error('Data bukan array:', data);
                                        alert('Gagal memuat data. Respons server tidak valid.');
                                    }                                    
                                }
                            });
                        }
                    });
                    
            </script>";


        echo $mdl;
        break;

}