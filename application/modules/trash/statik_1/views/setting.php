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
            "link" => $link_viewProdukHarga,
        );
        $isi_tab["other"] = array(
            "label" => "lain-lain",
            // "active" => true,
            "data"  => $other,
            "css"   => "bg-aqua",
            "link"   => $link_viewMember,
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

        // region customer level
        /* --------------------------------------------------------------------
        * THEAD
        * --------------------------------------------------------------------*/
        $groupHead = "";
        foreach ($arrHeadersGroup as $gkey => $gparams) {
            $glabel = isset($gparams['label']) ? $gparams['label'] : $gkey;
            $attr_header = isset($gparams['attr']) ? $gparams['attr'] : '';
            $heTransaksi_ui = isset($gparams['heTransaksi_ui']) ? $gparams['heTransaksi_ui'] : array();
            $jmlAnak = count($heTransaksi_ui);
            $colspan = $jmlAnak * 5;
            $rowspan = 1;

            $groupHead .= "<th colspan='$colspan' cls='$gkey' class='btn-toggle-group g-$gkey' $attr_header>$glabel&nbsp;<span class='badge badge-danger'>$jmlAnak</span></th>";
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
            $heTransaksi_ui = isset($gparams['heTransaksi_ui']) ? $gparams['heTransaksi_ui'] : array();
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
            $heTransaksi_ui = isset($gparams['heTransaksi_ui']) ? $gparams['heTransaksi_ui'] : array();
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
                    foreach ($heTransaksi_ui as $mdl) {
                        $mymenu = isset($mMenu[$mdl]) ? $mMenu[$mdl] : array();
                        foreach ($childs as $ky => $childrens) {
                            $checked_data = in_array($ky, $mymenu) ? "checked" : "";
                            if ($checked_data == "checked") {
                                // $ncheckBox = "<i class='fa fa-check-square text-red'></i>";
                                $ncheckBox = "<i class='fa fa-check text-red'></i>";
                                $check_class = "checked-green";
                            }
                            else {
                                $ncheckBox = "<i class='fa fa-circle-o'></i>";
                                $check_class = "checked-grey";
                            }
                            // $ncheckBox = "<input type='checkbox' $checked_data>";
                            $ncheckBox = $ncheckBox;
                            $ctoggle = $ky != 4 ? "c-$mdl ag-$gkey child" : "c";
                            $cchild = "ch-$mdl ch-$gkey";
                            // $strBody .= "<td class='$ctoggle $cchild checked $check_class' obid='$db_id' mdl='$mdl' crud='$ky'>$ncheckBox $gkey $mdl</td>";
                            $strBody .= "<td class='$ctoggle $cchild checked $check_class' obid='$db_id' mdl='$mdl' crud='$ky'>$ncheckBox</td>";
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
        $strTbl .= "<table class='table table-condensade table-striped' style='margin=0' id='$tbl_id'>";
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
            if($jmlAnak > 0){
                foreach ($heTransaksi_ui as $mdl) {

                    $stepDatums = isset($stepErp[$mdl]) ? $stepErp[$mdl] : array();
                    // cekKuning("$gkey");
                    // cekBiru($stepDatums);
                    $jmlCucu = count($stepDatums);
                    // cekHere("cc: $jmlCucu");
                    if(!isset($jmlCucuDrs[$gkey])){
                        $jmlCucuDrs[$gkey] = 0;
                    }
                    $jmlCucuDrs[$gkey] += $jmlCucu;
                }
                $jmlCucuDr = $jmlCucuDrs[$gkey];
// cekHijau();
//                 $colspan = $jmlAnak * 1;
                $colspan = $jmlCucuDr * 4;
                $rowspan = 1;

                $groupHead .= "<th colspan='$colspan' cls='$gkey' class='btn-toggle-group g-$gkey' $attr_header>$glabel $jmlAnak [$colspan] $jmlCucuDr *</th>";
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
                if(isset($childData[$mdl])){
                    $jmlColspan = isset($childDatum['steps']) ? count($childDatum['steps']) * $jmlChildColspan : 1;
                    $label = isset($childDatum["label"]) ? $childDatum["label"] : $mdl;

                    $strHead .= "<th class='btn-toggle g-$mdl' cls='$mdl' colspan='$jmlColspan' rowspan='1' title='$mdl' $attr_header>$label [$jmlColspan] $mdl</th>";
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
                    $strHead .= "<th class='sl-toggle' colspan='$jmlChildColspan'>$hLabel $jenis*</th>";
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
                        $strHead .= "<th class='$child' colspan='1'>$hLabel $mdl</th>";
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
                                $mmenu_0 = $mMenu[$mdl][$step];
                                $checked_data = in_array($ky, $mmenu_0) ? "checked" : "";
                                if ($checked_data == "checked") {
                                    $ncheckBox = "<i class='fa fa-check text-red'></i>";
                                    $check_class = "checked-green";
                                }
                                else {
                                    $ncheckBox = "<i class='fa fa-circle-o'></i>";
                                    $check_class = "checked-grey";
                                }
                                $child = $ky == 1 ? "c" : "child";
                                $strBody .= "<td class='checked $check_class $child ch-$ky' obid='$db_id' mdl='$mdl' crud='$ky' step='$step'>$ncheckBox $step :$ky</td>";
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
        $strTbl .= "<table border='1' class='table table-condensade table-striped' style='margin=0' id='$tbl_id'>";
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
            $('.checked').on('click', function() {
                // let data = $(this).data('subjek');
                let id = $(this).attr('obid');
                let mdl = $(this).attr('mdl');
                let crud = $(this).attr('crud');
                let step = $(this).attr('step');
                let url = '$base';
                if($(this).hasClass('checked-green')){
                    console.log('cek');
                    $.get(url,{id:id,mdl:mdl,crud:crud,step:step},function() {
                      console.log('cek send ' + url);
                    });
                    
                    $('td[obid=\"'+id+'\"][mdl=\"'+mdl+'\"][crud=\"'+crud+'\"][step=\"'+step+'\"]').removeClass('checked-green').addClass('checked-grey');
                    $('td[obid=\"'+id+'\"][mdl=\"'+mdl+'\"][crud=\"'+crud+'\"][step=\"'+step+'\"] i').removeClass('fa fa-check text-red').addClass('fa fa-circle-o');
                }
                else {
                    url = url + '?id='+ id + '&mdl=' + mdl + '&crud=' + crud + '&step=' + step;
                    console.log('un-cek', url);
                    $('#result_bottom').load(url);
                    
                    $('td[obid=\"'+id+'\"][mdl=\"'+mdl+'\"][crud=\"'+crud+'\"][step=\"'+step+'\"]').removeClass('checked-grey').addClass('checked-green');
                    $('td[obid=\"'+id+'\"][mdl=\"'+mdl+'\"][crud=\"'+crud+'\"][step=\"'+step+'\"] i').removeClass('fa fa-circle-o').addClass('fa fa-check text-red');
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
            $nom=0;
            foreach ($childrens as $kolom => $children) {
                $nom++;
                $duakeatas = $nom >= 2  ? "child ag-$ky" : "";
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
                        $strBody .= "<td class='$check_class checked $clsChild ch-$ky' obid='$db_id' mdl='$mdl' crud='$ky' colspan='1' $hattr_header>$ncheckBox</td>";
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
        $strTbl .= "<table border='1' class='table table-condensade table-striped' style='margin=0' id='$tbl_id'>";
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

}