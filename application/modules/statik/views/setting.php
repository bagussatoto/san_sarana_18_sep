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

        $modal_html = '';
        $modal_path = APPPATH . 'modules/statik/views/form_employee_permission_access.php';
        if (file_exists($modal_path)) {
            $modal_html = file_get_contents($modal_path);
        }

        $header_modal_html = '';
        $header_modal_path = APPPATH . 'modules/statik/views/form_header_access_modal.php';
        if (file_exists($header_modal_path)) {
            $header_modal_html = file_get_contents($header_modal_path);
        }

        $base_url_json = json_encode(base_url());
        $employee_script = "<script>\n";
        $employee_script .= "window.BASE_URL_JS = $base_url_json;\n";
        $employee_script .= "</script>\n";
        $employee_script .= <<<'HTML'
<script>
if (typeof window.showEmployeePermission !== 'function') {
    window.showEmployeePermission = function(employeeId, employeeName) {
        if (!employeeId || employeeId <= 0) {
            alert('Employee ID tidak valid');
            return false;
        }

        top.BootstrapDialog.show({
            title: 'Hak Akses: ' + employeeName,
            message: $('<div></div>').load(BASE_URL_JS + 'statik/Setting/employee_permission_view/' + employeeId),
            size: top.BootstrapDialog.SIZE_WIDE,
            draggable: true,
            closable: true,
            buttons: [{
                label: 'Close',
                action: function(dialogRef){
                    dialogRef.close();
                }
            }]
        });

        return false;
    };
}

if (typeof window.showHeaderAccess !== 'function') {
    window.showHeaderAccess = function(jenis, moduleKey, moduleLabel) {
        if (!jenis || !moduleKey) {
            alert('Header tidak valid');
            return false;
        }

        var modal = $('#headerAccessModal');
        if (modal.length === 0) {
            alert('Modal tidak ditemukan. Silakan refresh halaman.');
            return false;
        }

        var title = moduleLabel ? moduleLabel : moduleKey;
        modal.find('#modal-header-title').html('<i class="fa fa-spinner fa-spin"></i> Memuat data untuk ' + title + '...');
        modal.find('#header-access-content')
            .html('<p class="text-center text-muted"><i class="fa fa-spinner fa-spin fa-2x"></i><br>Memuat data...</p>');

        modal.modal('show');

        $.ajax({
            url: BASE_URL_JS + 'statik/Setting/getHeaderAccessModal',
            method: 'GET',
            data: { jenis: jenis, module: moduleKey },
            dataType: 'json',
            timeout: 10000,
            success: function(response) {
                if (response.error) {
                    alert('Error: ' + response.error);
                    modal.modal('hide');
                    return;
                }

                var headerTitle = response.module_label ? response.module_label : title;
                modal.find('#modal-header-title').html('<i class="fa fa-tag"></i> ' + headerTitle);
                modal.find('#header-access-content').html(buildHeaderAccessTable(response));
            },
            error: function(xhr, status, error) {
                var errorMsg = 'Gagal memuat data akses header.';
                if (status === 'timeout') {
                    errorMsg += ' Request timeout. Silakan coba lagi.';
                } else if (xhr.status === 404) {
                    errorMsg += ' Endpoint tidak ditemukan.';
                } else if (xhr.status === 500) {
                    errorMsg += ' Server error.';
                }
                alert(errorMsg);
                modal.modal('hide');
            }
        });

        return false;
    };

    window.buildHeaderAccessTable = function(response) {
        if (!response || !response.rows || response.rows.length === 0) {
            return '<div class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> Tidak ada user yang memiliki akses.</div>';
        }

        var jenis = response.jenis;
        var rows = response.rows;
        var html = '<div class="header-access-list">';

        if (jenis === 'transaksi') {
            var rowSpans = [];
            var idx = 0;
            while (idx < rows.length) {
                var currentId = rows[idx].employee_id;
                var span = 1;
                var nextIdx = idx + 1;
                while (nextIdx < rows.length && rows[nextIdx].employee_id === currentId) {
                    span++;
                    nextIdx++;
                }
                rowSpans[idx] = span;
                for (var skipIdx = idx + 1; skipIdx < nextIdx; skipIdx++) {
                    rowSpans[skipIdx] = 0;
                }
                idx = nextIdx;
            }

            html += '<table class="table table-condensed table-bordered table-hover">';
            html += '<thead><tr>';
            html += '<th style="width:28%;">NAMA</th>';
            html += '<th style="width:24%;">STEP</th>';
            html += '<th class="text-center" style="width:12%;">CREATE</th>';
            html += '<th class="text-center" style="width:12%;">EDIT</th>';
            html += '<th class="text-center" style="width:12%;">DELETE</th>';
            html += '<th class="text-center" style="width:12%;">VIEW</th>';
            html += '</tr></thead><tbody>';

            rows.forEach(function(item, rowIndex) {
                var perms = item.permissions || [];
                var hasCreate = perms.indexOf('CREATE') !== -1;
                var hasEdit = perms.indexOf('EDIT') !== -1;
                var hasDelete = perms.indexOf('DELETE') !== -1;
                var hasView = perms.indexOf('VIEW') !== -1;

                html += '<tr>';
                if (rowSpans[rowIndex] > 0) {
                    html += '<td rowspan="' + rowSpans[rowIndex] + '" style="vertical-align:middle;"><strong>' + item.employee_name + '</strong></td>';
                }
                html += '<td>' + (item.step_label || item.step) + '</td>';
                html += '<td class="text-center">' + (hasCreate ? '<i class="fa fa-check text-success"></i>' : '-') + '</td>';
                html += '<td class="text-center">' + (hasEdit ? '<i class="fa fa-check text-success"></i>' : '-') + '</td>';
                html += '<td class="text-center">' + (hasDelete ? '<i class="fa fa-check text-success"></i>' : '-') + '</td>';
                html += '<td class="text-center">' + (hasView ? '<i class="fa fa-check text-success"></i>' : '-') + '</td>';
                html += '</tr>';
            });

            html += '</tbody></table>';
            html += '</div>';
            return html;
        }

        if (jenis === 'other') {
            html += '<table class="table table-condensed table-bordered table-hover">';
            html += '<thead><tr>';
            html += '<th style="width:70%;">NAMA</th>';
            html += '<th class="text-center" style="width:30%;">ACCESS</th>';
            html += '</tr></thead><tbody>';
        }
        else {
            var showHistorical = (jenis === 'data');
            html += '<table class="table table-condensed table-bordered table-hover">';
            html += '<thead><tr>';
            html += '<th style="width:28%;">NAMA</th>';
            html += '<th class="text-center" style="width:12%;">CREATE</th>';
            html += '<th class="text-center" style="width:12%;">EDIT</th>';
            html += '<th class="text-center" style="width:12%;">DELETE</th>';
            html += '<th class="text-center" style="width:12%;">VIEW</th>';
            if (showHistorical) {
                html += '<th class="text-center" style="width:12%;">HISTORICAL</th>';
            }
            html += '</tr></thead><tbody>';
        }

        rows.forEach(function(item) {
            var perms = item.permissions || [];
            var hasCreate = perms.indexOf('CREATE') !== -1;
            var hasEdit = perms.indexOf('EDIT') !== -1;
            var hasDelete = perms.indexOf('DELETE') !== -1;
            var hasView = perms.indexOf('VIEW') !== -1;
            var hasHistorical = perms.indexOf('HISTORICAL') !== -1;
            var hasAccess = perms.indexOf('ACCESS') !== -1;

            html += '<tr>';
            html += '<td><strong>' + item.employee_name + '</strong></td>';
            if (jenis === 'other') {
                html += '<td class="text-center">' + (hasAccess ? '<i class="fa fa-check text-success"></i>' : '-') + '</td>';
            }
            else {
                html += '<td class="text-center">' + (hasCreate ? '<i class="fa fa-check text-success"></i>' : '-') + '</td>';
                html += '<td class="text-center">' + (hasEdit ? '<i class="fa fa-check text-success"></i>' : '-') + '</td>';
                html += '<td class="text-center">' + (hasDelete ? '<i class="fa fa-check text-success"></i>' : '-') + '</td>';
                html += '<td class="text-center">' + (hasView ? '<i class="fa fa-check text-success"></i>' : '-') + '</td>';
                if (showHistorical) {
                    html += '<td class="text-center">' + (hasHistorical ? '<i class="fa fa-check text-success"></i>' : '-') + '</td>';
                }
            }
            html += '</tr>';
        });

        html += '</tbody></table>';
        html += '</div>';
        return html;
    };

    $(document).on('click', '.header-access-click', function() {
        var jenis = $(this).data('jenis');
        var moduleKey = $(this).data('module');
        var label = $(this).data('label');
        showHeaderAccess(jenis, moduleKey, label);
    });
}
</script>
HTML;

        $content .= $modal_html . $header_modal_html . $employee_script;


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

        // fallback gv() jika belum didefinisikan secara global
        if (!function_exists('gv')) {
            function gv($arr, $key, $default = null)
            {
                if (is_array($arr) && isset($arr[$key])) {
                    return $arr[$key];
                }
                if (is_object($arr) && isset($arr->$key)) {
                    return $arr->$key;
                }
                return $default;
            }
        }

        $pluss = 0; // tetap sesuai original

        /**
         * buildTableHeader
         * header bar (mode, tombol, search)
         */
        $buildTableHeader = function ($mode, $tbl_id) use (&$pluss) {
            $s = "";
            $s .= "<div class='table-responsivee wrapper-stickys tblid_$tbl_id' >";
            $s .= "<div class='tbl-header-tools' style=\"display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; margin-bottom:10px;\">";
            $s .= "<div class='tbl-header-left' style='display:flex; align-items:center; gap:10px;'>";
            $s .= "<h3 class='text-uppercase mb-0'>$mode</h3>";
            $s .= "<button type='button' class='btn btn-xs btn-primary text-uppercase' id='showhidemastertransaksi'>show hide master</button>";
            $s .= "</div>";
            $s .= "<div class='tbl-header-right'>";
            $s .= "<input type='text' id='searchNama' class='form-control input-sm' placeholder='Cari nama...' style='width:200px; display:inline-block;'>";
            $s .= "</div>";
            $s .= "</div>";
            return $s;
        };

        /**
         * buildTableHead
         * membuat THEAD multi-row berdasarkan $arrHeadersGroup, $arrHeaders, $childs, $childData
         */
        $buildTableHead = function ($arrHeadersGroup, $arrHeaders, $childs, $childData, $mode) use (&$pluss) {
            $mdlTersedia = array_keys($childData);

            // group head baris 1
            $groupHead = "";
            foreach ($arrHeadersGroup as $gkey => $gparams) {
                $glabel = gv($gparams, 'label', $gkey);
                $attr_header = gv($gparams, 'attr', '');
                $heTransaksi_ui_0 = gv($gparams, 'heTransaksi_ui', array());
                $heTransaksi_ui = array_intersect($heTransaksi_ui_0, $mdlTersedia);
                $jmlAnak = count($heTransaksi_ui);
                if ($jmlAnak > 0) {
                    // di viewData original tiap anak 5 kolom
                    $colspan = $jmlAnak * 5;
                    $groupHead .= "<th colspan='$colspan' cls='$gkey' class='btn-toggle-group g-$gkey' $attr_header>$glabel&nbsp;<span class='badge badge-danger'>$jmlAnak</span></th>";
                }
            }

            // row pertama THEAD
            $strHead = "";
            $strHead .= "<tr class='bg-info'>";
            // kolom no dibuat sticky (freeze kolom pertama)
            $strHead .= "<th rowspan='3' class='sticky-col' style='background-color: #d9edf7;'>no</th>";

            foreach ($arrHeaders as $kolom => $arrHeader) {
                $hLabel = gv($arrHeader, 'label', $kolom);
                $child = (gv($arrHeader, 'child', false) == true) ? 1 : 0;
                $attr_header = gv($arrHeader, 'attr_header', '');
                $colspan = isset($childs[$kolom]) ? count($childs[$kolom]) : 1;
                $rowspan = isset($childs[$kolom]) ? 1 : 3;
                $strHead .= "<th colspan='$colspan' rowspan='$rowspan' $attr_header>$hLabel</th>";
            }
            $strHead .= $groupHead;
            $strHead .= "</tr>";

            // row kedua THEAD -> label per modul di group
            $strHead .= "<tr>";
            $jmlColspan = count($childs);
            foreach ($arrHeadersGroup as $gkey => $gparams) {
                $heTransaksi_ui_0 = gv($gparams, 'heTransaksi_ui', array());
                $heTransaksi_ui = array_intersect($heTransaksi_ui_0, $mdlTersedia);
                foreach ($heTransaksi_ui as $mdl) {
                    $childDatum = isset($childData[$mdl]) ? $childData[$mdl] : "";
                    $label = gv($childDatum, 'label', $mdl);
                    $label_safe = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
                    $mdl_safe = htmlspecialchars($mdl, ENT_QUOTES, 'UTF-8');
                    $strHead .= "<th class='btn-toggle p-$mdl pg-$gkey' gcls='g-$gkey' cls='$mdl' colspan='$jmlColspan' rowspan='1' >";
                    $strHead .= "<span class='header-access-click' data-jenis='data' data-module='$mdl_safe' data-label='$label_safe' style='cursor:pointer;text-decoration:underline;'>$label_safe</span>";
                    $strHead .= "</th>";
                }
            }
            $strHead .= "</tr>";

            // row ketiga THEAD -> header anak (childs)
            $strHead .= "<tr class='bg-info'>";
            foreach ($arrHeadersGroup as $gkey => $gparams) {
                $heTransaksi_ui_0 = gv($gparams, 'heTransaksi_ui', array());
                $heTransaksi_ui = array_intersect($heTransaksi_ui_0, $mdlTersedia);
                foreach ($heTransaksi_ui as $mdl) {
                    foreach ($childs as $ky => $childrens) {
                        $hLabel = gv($childrens, 'label', $ky);
                        // class ctoggle mengikuti original
                        $ctoggle = $ky != 4 ? "c-$mdl ag-$gkey child" : "c";
                        $strHead .= "<th class='$ctoggle' colspan='1'>$hLabel</th>";
                    }
                }
            }
            $strHead .= "</tr>";

            return $strHead;
        };

        /**
         * buildTableBody
         * membuat TBODY berdasarkan $masterData, $arrHeaders, $arrHeadersGroup, $childs, $childData, $myMenuData
         * Menjaga logic original termasuk disable behavior, checked class, dll.
         */
        $buildTableBody = function ($masterData, $arrHeaders, $arrHeadersGroup, $childs, $childData, $myMenuData, $mode, $ruleAkses) use (&$pluss) {
            $strBody = "";
            if (!empty($masterData) && count($masterData) > 0) {

                $no = 0;
                $modul_path = isset($modul_path) ? $modul_path : base_url() . "penjualan/";
                $jenistr = isset($jenisTr) ? $jenisTr : "582";
                $count_id = 111;

                foreach ($masterData as $master_datum) {
                    $no++;
                    $count_id++;
                    $jenis = gv($master_datum, 'jenis', 0);
                    $minim = gv($master_datum, 'minim', 0);
                    $db_id = gv($master_datum, 'id', "");
                    $mMenu = isset($myMenuData[$db_id]) ? $myMenuData[$db_id] : array();

                    $row_id = "row_" . $mode . "_$count_id";
                    $strBody .= "<tr id='$row_id'>";
                    $strBody .= "<td>$no</td>";

                    // kolom biasa (arrHeaders)
                    foreach ($arrHeaders as $kolom => $attrs) {
                        $td_id = $kolom . "_" . $count_id;
                        $nilai = isset($master_datum[$kolom]) ? (is_numeric($master_datum[$kolom]) ? $master_datum[$kolom] * 1 : $master_datum[$kolom]) : "";
                        $attr = gv($attrs, 'attr', '');
                        $format_key = gv($attrs, 'format_key', $kolom);
                        if (isset($attrs['format']) && $nilai > 0) {
                            $nilai_f = call_user_func($attrs['format'], $format_key, $nilai, $jenistr, $modul_path);
                        }
                        else {
                            $nilai_f = $nilai;
                        }

                        // links handling
                        $nilai_link = $nilai_f;
                        if (isset($attrs['links'])) {
                            $modal_size = gv($attrs['links'], 'modal_size', "");
                            $title_head_key = gv($attrs['links'], 'title_head_key', "");
                            $title_head = $title_head_key ? gv($master_datum, $title_head_key, 'none') : $nilai;
                            $link_title = gv($attrs['links'], 'title', "");
                            $strTitle_head = urlencode(trim("$link_title $title_head"));
                            $reqKey = gv($attrs['links'], 'key', "");
                            $reqValue = $reqKey ? gv($master_datum, $reqKey, "none") : "none";
                            $linking = gv($attrs['links'], 'target', '') ? (gv($attrs['links'], 'target') . "?$strGet" . "&$reqKey=$reqValue&modalSize=$modal_size") : "";
                            $linkDetile = base_url() . $linking;
                            $linkModal = modalDialogBtn("$strTitle_head", $linkDetile);
                            $nilai_link = $linking ? "<a href='javascript:void(0);' onclick=\"$linkModal\" title='$link_title'>$nilai_f</a>" : $nilai_f;
                        }

                        // tipe_input
                        if (isset($attrs['tipe_input'])) {
                            $tipe_input = $attrs['tipe_input'];
                            $click_fx = gv($attrs, 'onclick_fx', "");
                            switch ($tipe_input) {
                                case "checkbox":
                                    $checked = $nilai == 1 ? "checked" : "";
                                    $nilai_link = "<div class='funkyradio-success'>";
                                    $nilai_link .= "<input type='checkbox' $checked onclick=\"$click_fx('$db_id', '$row_id');\">";
                                    $nilai_link .= "</div>";
                                    break;
                            }
                        }

                        if ($kolom == "nama") {
                            $employee_id = (int)$db_id;
                            $nama_raw = is_scalar($nilai) ? (string)$nilai : "";
                            $nama_safe = htmlspecialchars($nama_raw, ENT_QUOTES, 'UTF-8');
                            $nama_js = htmlspecialchars(json_encode($nama_raw), ENT_QUOTES, 'UTF-8');
                            if ($employee_id > 0 && $nama_safe !== "") {
                                $nilai_link = "<span onclick=\"showEmployeePermission($employee_id, $nama_js)\" style=\"color:#007bff;cursor:pointer;font-weight:500;\" title=\"Klik untuk melihat hak akses\">$nama_safe</span>";
                            }
                        }

                        // action column
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

                        // summary
                        if (isset($attrs['summary'])) {
                            if (!isset($totals[$kolom])) {
                                $totals[$kolom] = 0;
                            }
                            $totals[$kolom] += (is_numeric($nilai) ? $nilai : 0);
                        }
                    }

                    // bagian hak akses (arrHeadersGroup -> childData -> childs)
                    foreach ($arrHeadersGroup as $gkey => $gparams) {
                        $heTransaksi_ui_0 = gv($gparams, 'heTransaksi_ui', array());
                        $heTransaksi_ui = array_intersect($heTransaksi_ui_0, array_keys($childData));
                        foreach ($heTransaksi_ui as $mdl) {
                            $mymenu = isset($mMenu[$mdl]) ? $mMenu[$mdl] : array();
                            foreach ($childs as $ky => $childrens) {

                                // behavior disabled list di childData per mdl
                                $disabledKy = gv($childData[$mdl], 'disabled', array());

                                // checked status
                                $checked_data = in_array($ky, $mymenu) ? "checked" : "";
                                if ($checked_data == "checked") {
                                    $ncheckBox = "<i class='fa fa-check text-green'></i>";
                                    $check_class = "checked-green";
                                }
                                else {
                                    $ncheckBox = "<i class='fa fa-circle-o'></i>";
                                    $check_class = "checked-grey";
                                }

                                if (in_array($ky, $disabledKy)) {
                                    $ncheckBox = "<i class='fa fa-circle text-grey'></i>";
                                    $dis = "-no";
                                }
                                else {
                                    $dis = "";
                                }

                                $ctoggle = $ky != 4 ? "c-$mdl ag-$gkey child" : "c";
                                $cchild = "ch-$mdl ch-$gkey";

                                $strBody .= "<td class='text-center $ctoggle $cchild checked$dis $check_class' obid='$db_id' mdl='$mdl' crud='$ky'>$ncheckBox</td>";
                            }
                        }
                    }

                    $strBody .= "</tr>";
                }
            }

            return $strBody;
        };

        /**
         * buildTableFoot
         * footer form input
         */
        $buildTableFoot = function ($arrHeaders, $pluss) {
            $link_save = base_url() . "diskon/Setting/do_save_member";
            $s = "";
            $s .= "<form method='post' id='my_form_$pluss' action='$link_save' target='result'>";
            $s .= "<tr class='bg-danger' id='form_input_$pluss'>";
            $s .= "<th></th>";
            foreach ($arrHeaders as $kolom => $arrHeader) {
                $attrs = $arrHeader;
                $kolom_id = $kolom . "_value_" . $pluss;
                $hLabel = gv($arrHeader, 'label', $kolom);
                $hTipe = gv($arrHeader, 'tipe_input', 'text');
                $attr = gv($attrs, 'attr_footer', gv($attrs, 'attr', ''));
                $data_srcs = gv($attrs, 'data_srcs', array());
                $nilai = gv($attrs, 'default_data', '');

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
                $s .= "<th>$str_input</th>";
            }

            // hidden fields (preserve original)
            $tipe = "";
            $my_div = "";
            $my_controler = "";
            $s .= "<input type='hidden' form='my_form_$pluss' name='tipe' id='tipe_value_$pluss' value='$tipe'>";
            $s .= "<input type='hidden' form='my_form_$pluss' name='my_controler' value='$my_controler'>";
            $s .= "<input type='hidden' form='my_form_$pluss' name='minim_be' id='maxim_value_$pluss' value=''>";
            $s .= "<input type='hidden' form='my_form_$pluss' name='my_div' value='$my_div'>";
            $s .= "</tr>";
            $s .= "</form>";
            return $s;
        };

        /**
         * buildTableScripts
         * JS & CSS: freeze header/column, show/hide group, search, checked handling, edit functions
         */
        $buildTableScripts = function ($tbl_id, $pluss, $ruleAkses, $base) {
            $s = "";

            // CSS: sticky header dan sticky first column
            $s .= "<style type='text/css'>
            .table>thead>tr>td, .table>thead>tr>th, .table>tbody>tr>td {
                vertical-align : middle !important;
                padding : 3px 10px !important;
                white-space: nowrap; /* biar tabel melebar horizontal */
            }
            .table>thead>tr>th { 
                font-size: 0.8em; 
                position: sticky; 
                top: 0; 
                background: #d9edf7;
                z-index: 10;
            }
            .table>tbody>tr>td {
                background-color: #fff;
            }
            .wrapper-stickys {
                overflow-x: auto; 
                overflow-y: auto;
                max-height: calc(100vh - 200px); /* biar scroll vertikal otomatis kalau tinggi berlebih */
                border: 1px solid #ccc;
            }
            
            /* sticky kolom pertama */
            .sticky-col {
                position: sticky;
                left: 0;
                background: #f7f7f7;
                z-index: 15;
                border-right: 1px solid #ccc;
            }
            
            /* biar tabel panjang tampil smooth */
            .table {
                min-width: 100%;
                border-collapse: collapse;
            }
            
            /* scrollbar bawah tampil otomatis */
            .wrapper-stickys::-webkit-scrollbar {
                height: 10px;
            }
            .wrapper-stickys::-webkit-scrollbar-thumb {
                background: #bbb;
                border-radius: 5px;
            }
            .wrapper-stickys::-webkit-scrollbar-thumb:hover {
                background: #888;
            }
            
            .btn { padding: 1px 6px !important; }

       
           
        </style>";

            /* wrapper scroll to allow sticky header */
            // .table-responsivee.wrapper-sticky { max-height: 600px; overflow-y: auto; border: 1px solid #ddd; }
            //
            // /* sticky THEAD */
            // .table thead th { position: sticky; top: 0; background: #f5f5f5; z-index: 10; }
            //
            // /* sticky first column (no) */
            // .table th.sticky-col, .table td.sticky-col { position: sticky; left: 0; z-index: 11; background: #fff; box-shadow: 2px 0 3px rgba(0,0,0,0.05); }
            //
            // /* small helper for child hide/show animations */
            // .child { transition: all 0.12s ease-in-out; }

            // JS: behavior
            $s .= "<script>
            // checked toggles
            $('.checked').on('click', function() {
                let id = $(this).attr('obid');
                let mdl = $(this).attr('mdl');
                let crud = $(this).attr('crud');
                let url = '$base';
                if($(this).hasClass('checked-green')){
                    $.get(url,{id:id,mdl:mdl,crud:crud},function(){});
                    $('td[obid=\"'+id+'\"][mdl=\"'+mdl+'\"][crud=\"'+crud+'\"]').removeClass('checked-green').addClass('checked-grey');
                    $('td[obid=\"'+id+'\"][mdl=\"'+mdl+'\"][crud=\"'+crud+'\"] i').removeClass('fa fa-check text-red').addClass('fa fa-circle-o');
                } else {
                    url = url + '?id='+ id + '&mdl=' + mdl + '&crud=' + crud;
                    $('#result_bottom').load(url);
                    $('td[obid=\"'+id+'\"][mdl=\"'+mdl+'\"][crud=\"'+crud+'\"]').removeClass('checked-grey').addClass('checked-green');
                    $('td[obid=\"'+id+'\"][mdl=\"'+mdl+'\"][crud=\"'+crud+'\"] i').removeClass('fa fa-circle-o').addClass('fa fa-check text-red');
                }
            });

            // show / hide master groups
            $('#showhidemastertransaksi').on('click', function () {
                let buka = false;
                $('.btn-toggle-group').each(function () {
                    let clsValue = $(this).attr('cls');
                    let parent_cls = 'g-' + clsValue;
                    let parent_column = $('.' + parent_cls);
                    let groupColspan = parseInt(parent_column.attr('colspan'), 10);
                    let defcolKey = 'defcol-' + parent_cls;
                    let defcolspan = localStorage.getItem(defcolKey);

                    if (!defcolspan) {
                        localStorage.setItem(defcolKey, groupColspan);
                        defcolspan = groupColspan;
                    } else {
                        defcolspan = parseInt(defcolspan, 10);
                    }

                    if (defcolspan === groupColspan) {
                        parent_column.attr('colspan', groupColspan / 5);
                        buka = false;
                    } else {
                        parent_column.attr('colspan', groupColspan * 5);
                        buka = true;
                    }
                });

                if(buka === false){
                    $('th.btn-toggle').attr('colspan', 1);
                    $('.child').fadeOut();
                    $('.checked').css({'color': 'green'});
                } else {
                    $('th.btn-toggle').attr('colspan', 5);
                    $('.child').fadeIn();
                }
            });

            // pencarian nama (client-side)
            $('#searchNama').on('keyup', function() {
                var keyword = $(this).val().toLowerCase();
                $('#$tbl_id tbody tr').filter(function() {
                    var rowText = $(this).text().toLowerCase();
                    $(this).toggle(rowText.indexOf(keyword) > -1);
                });
            });

            // fungsi edit row (memindahkan nilai ke form footer)
            function btn_edit_$pluss(r) {
                var row_sumber = $('td',$('#'+r));
                var objek = {};
                jQuery.each(row_sumber, function(a,b) {
                      var nilai = $(b).html();
                      objek[a] = nilai;
                });

                var row_target = $('th',$('#form_input_$pluss'));
                var last_key = row_target.length - 1;
                jQuery.each(row_target, function(c,d) {
                    var nilai = $('input',$(d));
                    var nilai_select = $('select',$(d));
                    if(c != last_key){
                        $(nilai).val(objek[c]);
                        $(nilai_select).val(objek[c]);
                    }
                });

                if(typeof (r)){
                    $('tr').css('background-color','');
                    $('#'+r).css('background-color','#ff00007d');
                    $('#jenis_value_$pluss').prop('readonly', true);
                    $('#minim_value_$pluss').prop('readonly', true);
                }
            }

            // minim value blur check (preserve logic)
            $('#minim_value_$pluss').blur(function() {
                var row_sumber = $('td',$('#$row_id'));
                var objek = {};
                jQuery.each(row_sumber, function(a,b) {
                    var nilai = $(b).html();
                    objek[a] = nilai;
                });
                var last_minim = objek['2'];
                var now_minim = $('#minim_value_$pluss').val();
                if(Number(now_minim) <= Number(last_minim)){
                    swal({ title: 'Opsss.. !!', html: 'minimal transaksi harus lebih besar dari ' + last_minim + ' sekarang ' + now_minim });
                    $('#minim_value_$pluss').css('background-color','#fff700ad');
                } else {
                    $('#minim_value_$pluss').css('background-color','');
                    $('#maxim_value_$pluss').val(last_minim);
                }
            });

        </script>";

            return $s;
        };

        // -------------------- build whole table --------------------
        $tbl_id = "member";
        $strTbl = "";

        // header/tools
        $strTbl .= $buildTableHeader($mode, $tbl_id);

        // table start
        $strTbl .= "<table class='table table-condensade table-striped table-hover-color-red' style='margin:0' id='$tbl_id'>";
        $strTbl .= "<thead class='text-uppercase'>";
        $strTbl .= $buildTableHead($arrHeadersGroup, $arrHeaders, $childs, $childData, $mode);
        $strTbl .= "</thead>";
        $strTbl .= "<tbody>";
        $strTbl .= $buildTableBody($masterData, $arrHeaders, $arrHeadersGroup, $childs, $childData, $myMenuData, $mode, $ruleAkses);
        $strTbl .= "</tbody>";
        $strTbl .= "<tfoot>";
        // untuk sekarang footer form tidak ditampilkan langsung (preserve original commented)
        // jika ingin tampilkan: $strTbl .= $buildTableFoot($arrHeaders, $pluss);
        $strTbl .= "</tfoot>";
        $strTbl .= "</table>";

        // tutup wrapper
        $strTbl .= "</div>";

        // scripts & styles
        $base = MODUL_PATH . "Setting/setData";
        $strTbl .= $buildTableScripts($tbl_id, $pluss, $ruleAkses, $base);

        $modal_html = '';
        $modal_path = APPPATH . 'modules/statik/views/form_employee_permission_access.php';
        if (file_exists($modal_path)) {
            $modal_html = file_get_contents($modal_path);
        }
        $header_modal_html = '';
        $header_modal_path = APPPATH . 'modules/statik/views/form_header_access_modal.php';
        if (file_exists($header_modal_path)) {
            $header_modal_html = file_get_contents($header_modal_path);
        }
        $strTbl .= $modal_html . $header_modal_html;

        // akhir: tampilkan
        $member = $strTbl;

        if (!isset($_GET['tpl'])) {
            echo $member;
        }
        else {
            $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/setting.html");
            $p->addTags(
                array(
                    "content" => $strTbl,
                )
            );
            $p->render();
        }

        break;

    case "viewTransaksi":
        // helper kecil untuk mengambil value dari array dengan default
        $gv = function ($arr, $key, $def = null) {
            return isset($arr[$key]) ? $arr[$key] : $def;
        };

        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/diskon.html");
        $pluss = 0;

        // build stepERP dari childData
        $stepErp = [];
        foreach ($childData as $jenis => $arrHeader) {
            $steps = $gv($arrHeader, 'steps', []);
            $stepErp[$jenis] = $steps;
        }
        $stepErpJenis = array_keys($stepErp);

        // ------------- BUILD GROUP HEAD (THEAD bagian group) --------------
        $groupHead = "";
        $jmlCucuDrs = [];

        foreach ($arrHeadersGroup as $gkey_0 => $gparams) {
            $gkey = str_replace(" ", "_", strtolower($gkey_0));
            $glabel = $gv($gparams, 'label', $gkey);
            $attr_header = $gv($gparams, 'attr', '');
            $heTransaksi_ui_00 = $gv($gparams, 'heTransaksi_ui', []);
            $heTransaksi_ui = array_filter(array_intersect($heTransaksi_ui_00, $stepErpJenis));

            $jmlAnak = count($heTransaksi_ui);
            if ($jmlAnak > 0) {
                // hitung total cucu (total steps untuk setiap modul di group ini)
                $totalCucu = 0;
                foreach ($heTransaksi_ui as $mdl) {
                    $stepDatums = $gv($stepErp, $mdl, []);
                    $totalCucu += count($stepDatums);
                }

                // setiap cucu punya 4 kolom (sesuai original)
                $colspan = $totalCucu * 4;
                $groupHead .= "<th colspan='$colspan' cls='$gkey' class='btn-toggle-group g-$gkey' $attr_header>$glabel <span class='badge badge-danger'>$jmlAnak</span></th>";
            }
        }

        // ------------- BUILD FIRST ROW THEAD (header utama) --------------
        $strHead = "";
        $strHead .= "<tr class='bg-info'>";
        $strHead .= "<th rowspan='4'>no</th>";

        foreach ($arrHeaders as $kolom => $arrHeader) {
            $hLabel = $gv($arrHeader, 'label', $kolom);
            $child = ($gv($arrHeader, 'child', false) == true) ? 1 : 0;
            $attr_header = $gv($arrHeader, 'attr_header', '');

            $colspan = isset($childs[$kolom]) ? count($childs[$kolom]) : 1;
            $rowspan = isset($childs[$kolom]) ? 1 : 4;

            $strHead .= "<th colspan='$colspan' rowspan='$rowspan' $attr_header>$hLabel</th>";
        }

        $strHead .= $groupHead;
        $strHead .= "</tr>";

        // ------------- BUILD SECOND ROW THEAD (group modul per kategori) --------------
        $strHead .= "<tr>";
        $jmlChildColspan = count($childs);

        foreach ($arrHeadersGroup as $gkey_0 => $gparams) {
            $attr_header = $gv($gparams, 'attr', '');
            $heTransaksi_ui_00 = $gv($gparams, 'heTransaksi_ui', []);
            $heTransaksi_ui = array_intersect($heTransaksi_ui_00, $stepErpJenis);

            foreach ($heTransaksi_ui as $mdl) {
                if (!isset($childData[$mdl])) {
                    continue;
                }
                $childDatum = $childData[$mdl];
                $jmlChildDatum = count($gv($childDatum, 'steps', []));
                $jmlColspan = $jmlChildDatum ? ($jmlChildDatum * $jmlChildColspan) : 1;
                $label = $gv($childDatum, 'label', $mdl);

                $label_safe = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
                $mdl_safe = htmlspecialchars($mdl, ENT_QUOTES, 'UTF-8');
                $display = $label . " ";
                $display_safe = htmlspecialchars($display, ENT_QUOTES, 'UTF-8');
                $strHead .= "<th class='btn-toggle g-$mdl' cls='$mdl' colspan='$jmlColspan' rowspan='1' title='$mdl' $attr_header>";
                $strHead .= "<span class='header-access-click' data-jenis='transaksi' data-module='$mdl_safe' data-label='$label_safe' style='cursor:pointer;text-decoration:underline;'>";
                $strHead .= $display_safe . "<span class='badge badge-danger'>$jmlChildDatum</span>";
                $strHead .= "</span></th>";
            }
        }
        $strHead .= "</tr>";

        // ------------- BUILD THIRD ROW THEAD (label steps) --------------
        $strHead .= "<tr class='bg-info'>";
        foreach ($arrHeadersGroup as $gkey => $gparams) {
            $heTransaksi_ui = $gv($gparams, 'heTransaksi_ui', []);
            foreach ($heTransaksi_ui as $mdl) {
                $stepDatums = $gv($stepErp, $mdl, []);
                foreach ($stepDatums as $step => $stepDatum) {
                    $hLabel = $gv($stepDatum, 'label', $step);
                    $strHead .= "<th class='sl-toggle' colspan='$jmlChildColspan'>$hLabel</th>";
                }
            }
        }
        $strHead .= "</tr>";

        // ------------- BUILD FOURTH ROW THEAD (label child columns per step) --------------
        $strHead .= "<tr class='bg-info'>";
        foreach ($arrHeadersGroup as $gkey => $gparams) {
            $heTransaksi_ui = $gv($gparams, 'heTransaksi_ui', []);
            foreach ($heTransaksi_ui as $mdl) {
                $stepDatums = $gv($stepErp, $mdl, []);
                foreach ($stepDatums as $step => $stepDatum) {
                    foreach ($childs as $ky => $childrens) {
                        $hLabel = $gv($childrens, 'label', $ky);
                        $childClass = ($ky == 1) ? "c" : "child";
                        $strHead .= "<th class='$childClass' colspan='1'>$hLabel</th>";
                    }
                }
            }
        }
        $strHead .= "</tr>";

        // ---------------- BODY ----------------
        $strBody = "";
        if (count($masterData) > 0) {
            $no = 0;
            $modul_path = isset($modul_path) ? $modul_path : base_url() . "penjualan/";
            $jenistr = isset($jenisTr) ? $jenisTr : "582";
            $count_id = 222;

            foreach ($masterData as $master_datum) {
                $no++;
                $count_id++;
                $jenis = $gv($master_datum, 'jenis', 0);
                $minim = $gv($master_datum, 'minim', 0);
                $db_id = $gv($master_datum, 'id', "");
                $mMenu = isset($myMenuData[$db_id]) ? $myMenuData[$db_id] : [];

                $row_id = "row_" . $mode . "_$count_id";
                $strBody .= "<tr id='$row_id'>";
                $strBody .= "<td>$no</td>";

                // normal columns
                foreach ($arrHeaders as $kolom => $attrs) {
                    $td_id = $kolom . "_" . $count_id;
                    $nilai = $gv($master_datum, $kolom, "");
                    if (is_numeric($nilai)) {
                        $nilai = $nilai * 1;
                    }

                    $attr = $gv($attrs, 'attr', '');
                    $format_key = $gv($attrs, 'format_key', $kolom);
                    if (isset($attrs['format']) && $nilai > 0) {
                        // fungsi format diharapkan menerima signature (key, value, jenistr, modul_path)
                        $nilai_f = call_user_func($attrs['format'], $format_key, $nilai, $jenistr, $modul_path);
                    }
                    else {
                        $nilai_f = $nilai;
                    }

                    // links
                    $nilai_link = $nilai_f;
                    if (isset($attrs['links'])) {
                        $modal_size = $gv($attrs['links'], 'modal_size', "");
                        $title_head_key = $gv($attrs['links'], 'title_head_key', "");
                        $title_head = $title_head_key ? $gv($master_datum, $title_head_key, 'none') : $nilai;
                        $link_title = $gv($attrs['links'], 'title', "");
                        $strTitle_head = urlencode(trim("$link_title $title_head"));
                        $reqKey = $gv($attrs['links'], 'key', "");
                        $reqValue = $reqKey ? $gv($master_datum, $reqKey, "none") : "none";
                        $linking = $gv($attrs['links'], 'target', '') ? ($gv($attrs['links'], 'target') . "?$strGet" . "&$reqKey=$reqValue&modalSize=$modal_size") : "";
                        $linkDetile = base_url() . $linking;
                        $linkModal = modalDialogBtn("$strTitle_head", $linkDetile);
                        $nilai_link = $linking ? "<a href='javascript:void(0);' onclick=\"$linkModal\" title='$link_title'>$nilai_f</a>" : $nilai_f;
                    }

                    // tipe_input (checkbox)
                    if (isset($attrs['tipe_input'])) {
                        $tipe_input = $attrs['tipe_input'];
                        $click_fx = $gv($attrs, 'onclick_fx', '');
                        switch ($tipe_input) {
                            case "checkbox":
                                $checked = ($nilai == 1) ? "checked" : "";
                                $nilai_link = "<div class='funkyradio-success'>";
                                $nilai_link .= "<input type='checkbox' $checked onclick=\"$click_fx('$db_id', '$row_id');\">";
                                $nilai_link .= "</div>";
                                break;
                        }
                    }

                    if ($kolom == "nama") {
                        $employee_id = (int)$db_id;
                        $nama_raw = is_scalar($nilai) ? (string)$nilai : "";
                        $nama_safe = htmlspecialchars($nama_raw, ENT_QUOTES, 'UTF-8');
                        $nama_js = htmlspecialchars(json_encode($nama_raw), ENT_QUOTES, 'UTF-8');
                        if ($employee_id > 0 && $nama_safe !== "") {
                            $nilai_link = "<span onclick=\"showEmployeePermission($employee_id, $nama_js)\" style=\"color:#007bff;cursor:pointer;font-weight:500;\" title=\"Klik untuk melihat hak akses\">$nama_safe</span>";
                        }
                    }

                    // action column special
                    if ($kolom == "action") {
                        $link_hapus = base_url() . "diskon/Setting/do_delete_member?jn=$jenis&minim=$minim&ctr=$my_controler&div=$my_div";
                        $strBody .= "<td $attr id='$td_id'>";
                        $strBody .= "<div class='btn-group'>";
                        $strBody .= "<button type='button' class='btn btn-link btn-sm' id='$td_id' onclick=\"btn_edit_$pluss('$row_id');\"><i class='fa fa-pencil'></i></button>";
                        $strBody .= "<button type='button' class='btn btn-sm btn-link' onclick=\"btn_alert_result('Oppss','akan meghapus setting diskon member?','$link_hapus');\"><i class='fa fa-trash'></i></button>";
                        $strBody .= "</div>";
                        $strBody .= "</td>";
                    }
                    else {
                        $strBody .= "<td $attr id='$td_id'>$nilai_link</td>";
                    }

                    // summary
                    if (isset($attrs['summary'])) {
                        if (!isset($totals[$kolom])) {
                            $totals[$kolom] = 0;
                        }
                        $totals[$kolom] += (is_numeric($nilai) ? $nilai : 0);
                    }
                } // end foreach arrHeaders

                // bagian hak akses per group / modul / step / child
                foreach ($arrHeadersGroup as $gkey => $gparams) {
                    $heTransaksi_ui = $gv($gparams, 'heTransaksi_ui', []);
                    foreach ($heTransaksi_ui as $mdl) {
                        $stepDatums = $gv($stepErp, $mdl, []);
                        foreach ($stepDatums as $step => $stepDatum) {
                            foreach ($childs as $ky => $childrens) {
                                $mmenu_00 = $gv($mMenu, $mdl, []);
                                $mmenu_0 = $gv($mmenu_00, $step, []);
                                $checked_data = in_array($ky, $mmenu_0) ? "checked" : "";

                                if ($checked_data == "checked") {
                                    $ncheckBox = "<i class='fa fa-check text-green'></i>";
                                    $check_class = "checked-green";
                                }
                                else {
                                    $ncheckBox = "<i class='fa fa-circle-o'></i>";
                                    $check_class = "checked-grey";
                                }

                                // logic supaya step 1 tidak bisa step 2 dan sebaliknya jika ruleAkses == 1
                                $dis = "";
                                if ($ruleAkses == 1) {
                                    if (array_key_exists(1, $mmenu_00) && $step == 2) {
                                        $ncheckBox = "<i class='fa fa-times text-orange'></i>";
                                        $dis = "-no";
                                    }
                                    elseif (array_key_exists(2, $mmenu_00) && $step == 1) {
                                        $ncheckBox = "<i class='fa fa-times text-orange'></i>";
                                        $dis = "-no";
                                    }
                                }

                                $childClass = ($ky == 1) ? "c" : "child";
                                $title = "$mdl|$step|$ky";
                                $strBody .= "<td title='$title' class='text-center checked$dis $check_class $childClass ch-$ky' obid='$db_id' mdl='$mdl' crud='$ky' step='$step'>$ncheckBox</td>";
                            }
                        }
                    }
                }

                $strBody .= "</tr>";
            } // end foreach masterData
        } // end if masterData

        // ---------------- FOOT (form input row) ----------------
        $link_save = base_url() . "diskon/Setting/do_save_member";
        $strFoot = "";
        $strFoot .= "<form method='post' id='my_form_$pluss' action='$link_save' target='result'>";
        $strFoot .= "<tr class='bg-danger' id='form_input_$pluss'>";
        $strFoot .= "<th></th>";

        foreach ($arrHeaders as $kolom => $arrHeader) {
            $attrs = $arrHeader;
            $kolom_id = $kolom . "_value_" . $pluss;
            $hLabel = $gv($arrHeader, 'label', $kolom);
            $hTipe = $gv($arrHeader, 'tipe_input', 'text');
            $attr = $gv($attrs, 'attr_footer', $gv($attrs, 'attr', ''));
            $data_srcs = $gv($attrs, 'data_srcs', []);
            $nilai = $gv($attrs, 'default_data', '');

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

        $strFoot .= "<input type='hidden' form='my_form_$pluss' name='tipe' id='tipe_value_$pluss' value='$tipe'>";
        $strFoot .= "<input type='hidden' form='my_form_$pluss' name='my_controler' value='$my_controler'>";
        $strFoot .= "<input type='hidden' form='my_form_$pluss' name='minim_be' id='maxim_value_$pluss' value=''>";
        $strFoot .= "<input type='hidden' form='my_form_$pluss' name='my_div' value='$my_div'>";
        $strFoot .= "</tr>";
        $strFoot .= "</form>";

        // ---------------- BUILD TABLE HTML ----------------
        $tbl_id = "member";
        $strTbl = "";
        $strTbl .= "<style type='text/css'>
                    .table>thead>tr>td, .table>thead>tr>th, .table>tbody>tr>td {
                        vertical-align : middle !important;
                        padding : 3px 10px !important;
                    }
                    .table>thead>tr>th {
                        font-size: 0.8em;
                    }
                    .table>tfoot>tr>th>select.form-control, .table>tfoot>tr>th>input.form-control, .table>tfoot>tr>th>input.btn {
                        height: 30px;
                        padding: 0 6px !important;
                        font-size: 1em;
                    }
          
                    
                    .table-responsive {
                        overflow-x: auto; 
                        overflow-y: auto;
                        max-height: calc(100vh - 200px); /* biar scroll vertikal otomatis kalau tinggi berlebih */
                        border: 1px solid #ccc;
                    }
                    
                    /* sticky kolom pertama */
                    .sticky-col {
                        position: sticky;
                        left: 0;
                        background: #f7f7f7;
                        z-index: 15;
                        border-right: 1px solid #ccc;
                    }
                    
                    /* biar tabel panjang tampil smooth */
                    .table {
                        min-width: 100%;
                        border-collapse: collapse;
                    }
                    
                    /* scrollbar bawah tampil otomatis */
                    .table-responsive::-webkit-scrollbar {
                        height: 10px;
                    }
                    .table-responsive::-webkit-scrollbar-thumb {
                        background: #bbb;
                        border-radius: 5px;
                    }
                    .table-responsive::-webkit-scrollbar-thumb:hover {
                        background: #888;
                    }
                    
                    .btn { padding: 1px 6px !important; }

                  </style>";

        $strTbl .= "<div class='table-responsive tblid_$tbl_id' >";

        $strTbl .= "<div class='tbl-header-tools' 
                         style=\"display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; margin-bottom:10px;\">
                        <div class='tbl-header-left' style='display:flex; align-items:center; gap:10px;'>
                            <h3 class='text-uppercase mb-0'>$mode</h3>
                            <button type='button' 
                                    class='btn btn-xs btn-primary text-uppercase' 
                                    id='showhidemastertransaksi'>show hide master</button>
                        </div>
                        <div class='tbl-header-right'>
                            <input type='text' 
                                   id='searchNama' 
                                   class='form-control input-sm' 
                                   placeholder='Cari nama...' 
                                   style='width:200px; display:inline-block;'>
                        </div>
                    </div>
                ";
        $strTbl .= "<table border='1' class='table table-condensade table-striped table-hover-color-red' style='margin=0' id='$tbl_id'>";
        $strTbl .= "<thead class='text-uppercase'>";
        $strTbl .= $strHead;
        $strTbl .= "</thead>";
        $strTbl .= "<tbody>";
        $strTbl .= $strBody;
        $strTbl .= "</tbody>";
        $strTbl .= "<tfoot>";
        // jika mau tampilkan form footer: $strTbl .= $strFoot;
        $strTbl .= "</tfoot>";
        $strTbl .= "</table>";
        $strTbl .= "</div>";

        // ---------------- JAVASCRIPT (fungsi handling checkbox) ----------------
        $base = MODUL_PATH . "Setting/setTransaksi";
        $js = "<script>
        var ruleAkses = Number('$ruleAkses');
        function not_checked(){
            $('.checked').off();
            $('.checked-no').off();

            $('.checked').on('click', function() {
                let id = $(this).attr('obid');
                let mdl = $(this).attr('mdl');
                let crud = $(this).attr('crud');
                let step = Number($(this).attr('step'));
                let steptarget = step === 2 ? 1 : (step === 1) ? 2 : '';
                let url = '$base';

                if($(this).hasClass('checked-green')){
                    $.get(url,{id:id,mdl:mdl,crud:crud,step:step},function() {});
                    
                    $('td[obid=\"'+id+'\"][mdl=\"'+mdl+'\"][crud=\"'+crud+'\"][step=\"'+step+'\"]')
                        .removeClass('checked-green').addClass('checked-grey')
                        .find('i').removeClass('fa fa-check text-green').addClass('fa fa-circle-o');
console.log('anu');
                    if(ruleAkses === 1){
                        if(step === 1 || step === 2){
                            var xx = $('td[obid=\"'+id+'\"][mdl=\"'+mdl+'\"][step=\"'+step+'\"]');
                            var total = 0;
                            jQuery.each(xx, function(a,b){
                                if($(b).hasClass('checked-green')){
                                    total++;
                                }
                            });
                            if(total === 0 ){
                                $('td[obid=\"'+id+'\"][mdl=\"'+mdl+'\"][step=\"'+steptarget+'\"]')
                                    .removeClass('checked-no').addClass('checked')
                                    .find('i').removeClass('fa fa-times text-orange').addClass('fa fa-circle-o');
                            }
                        }
                    }
                }
                else {
                    console.log('masuk sini');
                    url = url + '?id='+ id + '&mdl=' + mdl + '&crud=' + crud + '&step=' + step;
                    $('#result_bottom').load(url);

                    $('td[obid=\"'+id+'\"][mdl=\"'+mdl+'\"][crud=\"'+crud+'\"][step=\"'+step+'\"]')
                        .removeClass('checked-grey').addClass('checked-green')
                        .find('i').removeClass('fa fa-circle-o').addClass('fa fa-check text-green');

                    if(ruleAkses === 1){
                        if(step === 1 || step === 2){
                            $('td[obid=\"'+id+'\"][mdl=\"'+mdl+'\"][step=\"'+steptarget+'\"]')
                                .removeClass('checked checked-green').addClass('checked-no')
                                .find('i').removeClass('fa fa-circle-o').addClass('fa fa-times text-orange');
                        }
                    }
                }
                not_checked();
            });
        }
        not_checked();
        
        // --- fitur pencarian baris berdasarkan nama ---
        $('#searchNama').on('keyup', function() {
            var keyword = $(this).val().toLowerCase();
            $('#member tbody tr').filter(function() {
                var rowText = $(this).text().toLowerCase();
                $(this).toggle(rowText.indexOf(keyword) > -1);
            });
        });

    </script>";

        $strTbl .= $js;

        $modal_html = '';
        $modal_path = APPPATH . 'modules/statik/views/form_employee_permission_access.php';
        if (file_exists($modal_path)) {
            $modal_html = file_get_contents($modal_path);
        }
        $header_modal_html = '';
        $header_modal_path = APPPATH . 'modules/statik/views/form_header_access_modal.php';
        if (file_exists($header_modal_path)) {
            $header_modal_html = file_get_contents($header_modal_path);
        }
        $strTbl .= $modal_html . $header_modal_html;

        $member = $strTbl;

        // output sesuai request (tpl atau langsung echo)
        if (!isset($_GET['tpl'])) {
            echo $member;
        }
        else {
            $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/setting.html");
            $p->addTags(array(
                "menu_left"       => callMenuLeft(),
                "trans_menu"      => callTransMenu(),
                "float_menu_atas" => callFloatMenu('atas'),
                "content"         => $strTbl,
            ));
            $p->render();
        }

        break;

    case "viewOther":
        // Inisialisasi Layout
        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/diskon.html");
        $pluss = 0;

        // Inisialisasi array untuk header
        $childs = array();
        $level_header_0 = array();
        $level_header_00 = array();

        // ----------------------------------------------------------------------
        // Pemrosesan Header untuk menentukan parent/child
        // ----------------------------------------------------------------------
        foreach ($level_header as $kolom => $arrHeader) {
            // $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $child = isset($arrHeader['child']) && $arrHeader['child'] == true ? 1 : 0;
            $parent_ky = isset($arrHeader['parent_ky']) ? $arrHeader['parent_ky'] : "";

            // Header yang bukan anak (parent/top level)
            if ($child != true) {
                $level_header_0[$kolom] = $arrHeader;
            }

            // Header tanpa parent_ky (parent level)
            if (!isset($arrHeader['parent'])) {
                $level_header_00[$kolom] = $arrHeader;
            }

            // Header yang menjadi anak (child)
            if (isset($arrHeader['parent_ky'])) {
                $childs[$parent_ky][$kolom] = $arrHeader;
            }
        }

        // --------------------------------------------------------------------
        // PEMBUATAN THEAD (HEADER TABEL) - Baris 1
        // --------------------------------------------------------------------
        $strHead = "";
        $strHead .= "<tr class='bg-info'>";
        $strHead .= "<th rowspan='2'>No</th>";

        // Header utama dari $arrHeaders
        foreach ($arrHeaders as $kolom => $arrHeader) {
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            // $child = isset($arrHeader['child']) && $arrHeader['child'] == true ? 1 : 0;
            // $parent_ky = isset($arrHeader['parent_ky']) ? $arrHeader['parent_ky'] : "";
            $attr_header = isset($arrHeader['attr_header']) ? $arrHeader['attr_header'] : "";

            $colspan = isset($childs[$kolom]) ? count($childs[$kolom]) : 1;
            $rowspan = isset($childs[$kolom]) ? 1 : 2;

            $strHead .= "<th colspan='$colspan' rowspan='$rowspan' $attr_header>$hLabel</th>";
        }

        // Header dari $childData (untuk kolom hak akses/menu)
        foreach ($childData as $mdl => $childDatum) {
            $availMenu = isset($childDatum["availMenu"]) ? $childDatum["availMenu"] : array();
            $colspan_0 = count($availMenu);
            $colspan = $colspan_0 > 0 ? $colspan_0 : 1;

            // Hanya tampilkan jika ada menu aktif
            if ($colspan_0 > 0) {
                $label = isset($childDatum["label"]) ? $childDatum["label"] : $mdl;
                $strHead .= "<th class='btn-toggle-group g-$mdl' cls='$mdl' md='$mode' title='$mdl' colspan='$colspan' rowspan='1' $attr_header>$label&nbsp;<span class='badge badge-danger'>$colspan_0</span></th>";
                $childs[$mdl] = $availMenu; // Update $childs untuk baris kedua header
            }
        }
        $strHead .= "</tr>";

        // --------------------------------------------------------------------
        // PEMBUATAN THEAD (HEADER TABEL) - Baris 2 (Anak Header)
        // --------------------------------------------------------------------
        $strHead .= "<tr class='bg-info'>";
        foreach ($childs as $ky => $childrens) {
            $nom = 0;
            foreach ($childrens as $kolom => $children) {
                $nom++;
                $duakeatas = $nom >= 2 ? "child ag-$ky" : "";
                $hLabel = isset($children['label']) ? $children['label'] : $kolom;
                // $hattr_header = isset($children['attr_header']) ? $children['attr_header'] : ""; // Tidak digunakan di baris kedua

                $label_safe = htmlspecialchars($hLabel, ENT_QUOTES, 'UTF-8');
                $kolom_safe = htmlspecialchars($kolom, ENT_QUOTES, 'UTF-8');
                if (isset($children['target'])) {
                    $strHead .= "<th colspan='1' class='$duakeatas'>";
                    $strHead .= "<span class='header-access-click' data-jenis='other' data-module='$kolom_safe' data-label='$label_safe' style='cursor:pointer;text-decoration:underline;'>$label_safe</span>";
                    $strHead .= "</th>";
                }
                else {
                    $strHead .= "<th colspan='1' class='$duakeatas'>$label_safe</th>";
                }
            }
        }
        $strHead .= "</tr>";

        // --------------------------------------------------------------------
        // TBODY (ISI TABEL)
        // --------------------------------------------------------------------
        $strBody = "";
        $pluss = $jenis_kdata = $mode; // Menggunakan $mode untuk $pluss
        $no = 0;
        $modul_path = isset($modul_path) ? $modul_path : base_url() . "penjualan/";
        $jenistr = isset($jenisTr) ? $jenisTr : "582";
        $count_id = 333;
        $row_id = "";
        $totals = array(); // Untuk summary/total di footer

        if (count($masterData) > 0) {
            foreach ($masterData as $master_datum) {
                $no++;
                $count_id++;
                $jenis = isset($master_datum['jenis']) ? $master_datum['jenis'] : 0;
                $minim = isset($master_datum['minim']) ? $master_datum['minim'] : 0;
                $db_id = isset($master_datum['id']) ? $master_datum['id'] : "";
                $mMenu = $myMenu[$db_id];

                $row_id = "row_" . $jenis_kdata . "_$count_id";
                $strBody .= "<tr id='$row_id' class='data-row'>"; // Tambahkan class data-row untuk searching
                $strBody .= "<td>$no</td>";

                // Kolom dari $arrHeaders
                foreach ($arrHeaders as $kolom => $attrs) {
                    $td_id = $kolom . "_" . $count_id;
                    $nilai_raw = isset($master_datum[$kolom]) ? $master_datum[$kolom] : "";
                    // Konversi numerik ke float/int jika perlu, atau gunakan string
                    $nilai = is_numeric($nilai_raw) ? $nilai_raw * 1 : $nilai_raw;

                    $attr = isset($attrs['attr']) ? $attrs['attr'] : "";
                    $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;

                    // Format nilai jika ada
                    $nilai_f = isset($attrs['format']) ?
                        (($nilai_raw > 0 || is_string($nilai_raw)) ? $attrs['format']($format_key, $nilai_raw, $jenistr, $modul_path) : $nilai_raw) :
                        $nilai_raw;

                    $nilai_link = $nilai_f; // Default nilai

                    // Menambahkan link (modal dialog)
                    if (isset($attrs['links'])) {
                        $modal_size = isset($attrs['links']['modal_size']) ? $attrs['links']['modal_size'] : "";
                        $title_head_key = isset($attrs['links']['title_head_key']) ? $attrs['links']['title_head_key'] : "";
                        $title_head = isset($attrs['links']['title_head_key']) ? (isset($master_datum[$title_head_key]) ? $master_datum[$title_head_key] : 'none') : $nilai;
                        $link_title = isset($attrs['links']['title']) ? $attrs['links']['title'] : "";
                        $strTitle_head = urlencode(trim("$link_title $title_head"));
                        $reqKey = isset($attrs['links']['key']) ? $attrs['links']['key'] : "";
                        $reqValue = isset($master_datum[$reqKey]) ? $master_datum[$reqKey] : "none";
                        $linking = isset($attrs['links']['target']) ? $attrs['links']['target'] . "?$strGet" . "&$reqKey=$reqValue&modalSize=$modal_size" : "";
                        $linkDetile = base_url() . $linking;
                        $linkModal = modalDialogBtn("$strTitle_head", $linkDetile);
                        $nilai_link = isset($attrs['links']['target']) ? "<a href='javascript:void(0);' onclick=\"$linkModal\" title='$link_title'>$nilai_f</a>" : $nilai_f;
                    }

                    // Menangani tipe input khusus (checkbox)
                    if (isset($attrs['tipe_input'])) {
                        $tipe_input = $attrs['tipe_input'];
                        $click_fx = isset($attrs['onclick_fx']) ? $attrs['onclick_fx'] : "";

                        switch ($tipe_input) {
                            case "checkbox":
                                $checked = $nilai == 1 ? "checked" : "";
                                $nilai_link = "<div class='funkyradio-success'>";
                                $nilai_link .= "<input type='checkbox' $checked onclick=\"$click_fx('$db_id', '$row_id');\">";
                                $nilai_link .= "</div>";
                                break;
                        }
                    }

                    if ($kolom == "nama") {
                        $employee_id = (int)$db_id;
                        $nama_raw = is_scalar($nilai_raw) ? (string)$nilai_raw : "";
                        $nama_safe = htmlspecialchars($nama_raw, ENT_QUOTES, 'UTF-8');
                        $nama_js = htmlspecialchars(json_encode($nama_raw), ENT_QUOTES, 'UTF-8');
                        if ($employee_id > 0 && $nama_safe !== "") {
                            $nilai_link = "<span onclick=\"showEmployeePermission($employee_id, $nama_js)\" style=\"color:#007bff;cursor:pointer;font-weight:500;\" title=\"Klik untuk melihat hak akses\">$nama_safe</span>";
                        }
                    }

                    // Kolom 'action' (Edit dan Delete)
                    if ($kolom == "action") {
                        $link_hapus = base_url() . "diskon/Setting/do_delete_member?jn=$jenis&minim=$minim&ctr=$my_controler&div=$my_div";
                        $strBody .= "<td $attr id='$td_id'>";
                        $strBody .= "<div class='btn-group'>";
                        $strBody .= "<button type='button' class='btn btn-link btn-sm' id='$td_id' onclick=\"btn_edit_$pluss('$row_id');\"><i class='fa fa-pencil'></i></button>";
                        $strBody .= "<button type='button' class='btn btn-sm btn-link' onclick=\"btn_alert_result('Oppss','akan menghapus setting diskon member?','$link_hapus');\"><i class='fa fa-trash'></i></button>";
                        $strBody .= "</div>";
                        $strBody .= "</td>";
                    } else {
                        $strBody .= "<td $attr id='$td_id'>$nilai_link</td>";
                    }

                    // Perhitungan Summary
                    if (isset($attrs['summary'])) {
                        if (!isset($totals[$kolom])) {
                            $totals[$kolom] = 0;
                        }
                        $totals[$kolom] += $nilai;
                    }
                }

                // Kolom hak akses (checkbox/ikon)
                foreach ($childs as $ky => $childrens) {
                    $nom = 0;
                    foreach ($childrens as $mdl => $children) {
                        $nom++;
                        $checked_data = in_array($mdl, $mMenu) ? "checked" : "";

                        if ($checked_data == "checked") {
                            $ncheckBox = "<i class='fa fa-check text-red'></i>";
                            $check_class = "checked-green";
                        } else {
                            $ncheckBox = "<i class='fa fa-circle-o'></i>";
                            $check_class = "checked-grey";
                        }

                        $clsChild = $nom > 1 ? "child ag-$ky" : "";

                        $strBody .= "<td class='text-center $check_class checked $clsChild ch-$ky' obid='$db_id' mdl='$mdl' crud='$ky' colspan='1'>$ncheckBox</td>";
                    }
                }
                $strBody .= "</tr>";
            }
        }

        // --------------------------------------------------------------------
        // TFOOD (FOOTER TABEL - Form Input)
        // --------------------------------------------------------------------
        $link_save = base_url() . "diskon/Setting/do_save_member";
        $strFoot = "";
        $strFoot .= "<form method='post' id='my_form_$pluss' action='$link_save' target='result'>";
        $strFoot .= "<tr class='bg-danger' id='form_input_$pluss'>";
        $strFoot .= "<th></th>"; // Untuk kolom NO

        foreach ($arrHeaders as $kolom => $arrHeader) {
            $attrs = $arrHeader;
            $kolom_id = $kolom . "_value_" . $pluss;
            // $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $hTipe = isset($arrHeader['tipe_input']) ? $arrHeader['tipe_input'] : "text";
            $attr = isset($attrs['attr_footer']) ? $attrs['attr_footer'] : (isset($attrs['attr']) ? $attrs['attr'] : "");
            $data_srcs = isset($attrs['data_srcs']) ? $attrs['data_srcs'] : array();
            $nilai = isset($attrs['default_data']) ? $attrs['default_data'] : "";

            $str_input = "";
            switch ($hTipe) {
                default:
                case "text":
                    $str_input = "<input type='$hTipe' form='my_form_$pluss' onclick=\"this.select()\" name='$kolom' id='$kolom_id' class='form-control input-sm' $attr value='$nilai'>";
                    break;
                case "select":
                    $str_input = "<select name='$kolom' form='my_form_$pluss' id='$kolom_id' class='form-control input-sm' $attr>";
                    $str_input .= "<option value=''>------</option>";
                    foreach ($data_srcs as $data_src) {
                        $str_input .= "<option>$data_src</option>";
                    }
                    $str_input .= "</select>";
                    break;
            }

            $strFoot .= "<th>$str_input</th>";
        }
        // Hidden fields
        $strFoot .= "<input type='hidden' form='my_form_$pluss' name='tipe' id='tipe_value_$pluss' value='$tipe'>";
        $strFoot .= "<input type='hidden' form='my_form_$pluss' name='my_controler' value='$my_controler'>";
        $strFoot .= "<input type='hidden' form='my_form_$pluss' name='minim_be' id='maxim_value_$pluss' value=''>";
        $strFoot .= "<input type='hidden' form='my_form_$pluss' name='my_div' value='$my_div'>";
        $strFoot .= "</tr>";
        $strFoot .= "</form>";

        // --------------------------------------------------------------------
        // PEMBUATAN TABEL LENGKAP
        // --------------------------------------------------------------------
        $tbl_id = "member";
        $strTbl = "";

        // Style (dipindahkan ke sini agar mudah ditemukan)
        $strTbl .= "<style type='text/css'>
                    .table>thead>tr>td, .table>thead>tr>th, .table>tbody>tr>td {
                        vertical-align : middle !important;
                        padding : 3px 10px !important;
                    }
                    .table>thead>tr>th {
                        font-size: 0.8em;
                    }
                    /* Styling untuk input/select di tfoot */
                    .table>tfoot>tr>th>select.form-control, .table>tfoot>tr>th>input.form-control {
                        height: 30px !important;
                        padding: 0 6px !important;
                        font-size: 1em;
                    }
                    .btn {
                        padding: 1px 6px !important;
                    }
                    .hidden-row {
                        display: none; /* Class untuk menyembunyikan baris */
                    }
                    
                     .table-responsive {
                        overflow-x: auto; 
                        overflow-y: auto;
                        max-height: calc(100vh - 200px); /* biar scroll vertikal otomatis kalau tinggi berlebih */
                        border: 1px solid #ccc;
                    }
                    
                    /* sticky kolom pertama */
                    .sticky-col {
                        position: sticky;
                        left: 0;
                        background: #f7f7f7;
                        z-index: 15;
                        border-right: 1px solid #ccc;
                    }
                    
                    /* biar tabel panjang tampil smooth */
                    .table {
                        min-width: 100%;
                        border-collapse: collapse;
                    }
                    
                    /* scrollbar bawah tampil otomatis */
                    .table-responsive::-webkit-scrollbar {
                        height: 10px;
                    }
                    .table-responsive::-webkit-scrollbar-thumb {
                        background: #bbb;
                        border-radius: 5px;
                    }
                    .table-responsive::-webkit-scrollbar-thumb:hover {
                        background: #888;
                    }
                    
                    
                </style>";

        $strTbl .= "<div class='table-responsive tblid_$tbl_id' >";
            $strTbl .= "<div class='tbl-header-tools' 
                         style=\"display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; margin-bottom:10px;\">
                        <div class='tbl-header-left' style='display:flex; align-items:center; gap:10px;'>
                            <h3 class='text-uppercase mb-0'>$mode</h3>
                            <button type='button' 
                                    class='btn btn-xs btn-primary text-uppercase' 
                                    id='showhidemastertransaksi'>show hide master</button>
                        </div>
                        <div class='tbl-header-right'>
                            <input type='text' 
                                   id='searchNama' 
                                   class='form-control input-sm' 
                                   placeholder='Cari nama...' 
                                   style='width:200px; display:inline-block;'>
                        </div>
                    </div>
                ";
        $strTbl .= "<table border='1' class='table table-condensade table-striped table-hover-color-red' style='margin=0' id='$tbl_id'>";
        $strTbl .= "<thead class='text-uppercase'>";
        $strTbl .= $strHead;
        $strTbl .= "</thead>";
        $strTbl .= "<tbody>";
        $strTbl .= $strBody;
        $strTbl .= "</tbody>";

        // $strTbl .= "<tfoot>";
        // $strTbl .= $strFoot; // Memindahkan form input ke tfoot agar terlihat seperti baris input
        // $strTbl .= "</tfoot>";

        $strTbl .= "</table>";
        $strTbl .= "</div>";

        // --------------------------------------------------------------------
        // JAVASCRIPT
        // --------------------------------------------------------------------
        $base = MODUL_PATH . "Setting/setOther";
        $strTbl .= "<script>
        // 1. Logic Toggle Hak Akses
        $('.checked').on('click', function() {
            let id = $(this).attr('obid');
            let mdl = $(this).attr('mdl');
            let crud = $(this).attr('crud');
            let url = '$base';
            let tdElement = $('td[obid=\"'+id+'\"][mdl=\"'+mdl+'\"]');
            
            if(tdElement.hasClass('checked-green')){
                // UNCHECK
                $.get(url,{id:id,mdl:mdl,crud:crud},function() {
                  console.log('UNCHECK sent to ' + url);
                });
                
                tdElement.removeClass('checked-green').addClass('checked-grey');
                tdElement.find('i').removeClass('fa-check text-red').addClass('fa-circle-o');
            }
            else {
                // CHECK
                url = url + '?id='+ id + '&mdl=' + mdl + '&crud=' + crud;
                $('#result_bottom').load(url); // Menggunakan load untuk send dan update result
                
                tdElement.removeClass('checked-grey').addClass('checked-green');
                tdElement.find('i').removeClass('fa-circle-o').addClass('fa-check text-red');
            }
        });
    </script>";

        $strTbl .= "<script>
        // 2. Logic Edit Data (Memasukkan nilai baris ke form footer)
        function btn_edit_$pluss(r) {
            var row_sumber = $('td', $('#'+r));
            var objek = {};
            
            // Ambil semua nilai cell dari baris yang diklik
            jQuery.each(row_sumber, function(a,b) {
                  // Jika ada tag <a>, ambil teksnya, jika tidak, ambil html
                  var nilai = $(b).find('a').length ? $(b).find('a').text() : $(b).html();
                  if ($(b).find(':input').length) {
                       // Untuk checkbox/input, ambil nilai dari input/checkbox
                       nilai = $(b).find(':checkbox').prop('checked') ? 1 : 0;
                  }

                  objek[a] = $.trim(nilai);
            });
            
            // Masukkan nilai ke form di tfoot
            var row_target = $('th', $('#form_input_$pluss'));
            var last_key = row_target.length - 1;

            jQuery.each(row_target, function(c,d) {
                var input_element = $('input, select', $(d));
                
                if(c != last_key && objek[c] !== undefined){                
                    input_element.val(objek[c]);
                }
            });

            // Beri highlight pada baris yang sedang diedit
            if(r){
                $('tr').css('background-color','');
                $('#'+r).css('background-color','#ff00007d');
                // Set readonly pada key field
                $('#jenis_value_$pluss').prop('readonly', true);
                $('#minim_value_$pluss').prop('readonly', true);
            }
        }
           
        // 3. Logic Validasi Input 'minim'
        $('#minim_value_$pluss').blur(function() {
            var row_sumber = $('td',$('#$row_id'));
            var objek = {};
            // Ambil data 'minim' dari baris terakhir (kolom ke-2, diasumsikan kolom index 2 adalah 'minim')
            jQuery.each(row_sumber, function(a,b) {
                objek[a] = $(b).html();
            })
            
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

        // 4. Logic Pencarian BARU
         
         $('#searchNama').on('keyup', function() {
            var keyword = $(this).val().toLowerCase();
            $('#member tbody tr').filter(function() {
                var rowText = $(this).text().toLowerCase();
                $(this).toggle(rowText.indexOf(keyword) > -1);
            });
        });
        
    </script>";

        $modal_html = '';
        $modal_path = APPPATH . 'modules/statik/views/form_employee_permission_access.php';
        if (file_exists($modal_path)) {
            $modal_html = file_get_contents($modal_path);
        }
        $header_modal_html = '';
        $header_modal_path = APPPATH . 'modules/statik/views/form_header_access_modal.php';
        if (file_exists($header_modal_path)) {
            $header_modal_html = file_get_contents($header_modal_path);
        }
        $strTbl .= $modal_html . $header_modal_html;

        // 5. Menyusun output
        $member = $strTbl;

        if (!isset($_GET['tpl'])) {
            echo $member;
        }
        else {
            // Render dengan template penuh jika tpl=1
            $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/setting.html");
            $p->addTags(
                array(
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

        // edited by glg (22:30 WIB, 2025-12-23)
        // change: tambah box setting global e-signature pembelian & penjualan
        // technical rationale: aktif/nonaktif e-signature per modul pada halaman setting aplikasi
        $esignatureEnabled = isset($esignatureEnabled) ? (int)$esignatureEnabled : 1;
        $esignaturePenjualanEnabled = isset($esignaturePenjualanEnabled) ? (int)$esignaturePenjualanEnabled : 1;
        // edited by glg (11:32 WIB, 2025-12-23)
        // change: tambahkan flag e-signature distribusi
        // technical rationale: toggle distribusi ikut dirender pada setting aplikasi
        $esignatureDistribusiEnabled = isset($esignatureDistribusiEnabled) ? (int)$esignatureDistribusiEnabled : 1;
        // edited by glg (13:10 WIB, 2025-12-23)
        // change: tambahkan flag e-signature biaya
        // technical rationale: toggle biaya ikut dirender pada setting aplikasi
        $esignatureBiayaEnabled = isset($esignatureBiayaEnabled) ? (int)$esignatureBiayaEnabled : 1;

        $esignatureTogglePembelian = "<div class='wwrapper-radio' style='margin-top: -3px;'>";
        $esignatureOnChecked = $esignatureEnabled === 1 ? "checked" : "";
        $esignatureOffChecked = $esignatureEnabled === 0 ? "checked" : "";
        $esignatureTogglePembelian .= "<input id='toggle-on-esignature_pembelian' $esignatureOnChecked class='toggle-radio-esignature toggle-left' data-module='pembelian' name='esignature_pembelian' value='1' type='radio'>
            <label for='toggle-on-esignature_pembelian' class='btn-radio' style='border-radius: 5px 0 0 5px;'>AKTIF</label>";
        $esignatureTogglePembelian .= "<input id='toggle-off-esignature_pembelian' $esignatureOffChecked class='toggle-radio-esignature toggle-right' data-module='pembelian' name='esignature_pembelian' value='0' type='radio'>
            <label for='toggle-off-esignature_pembelian' class='btn-radio' style='border-radius: 0 5px 5px 0;'>NON AKTIF</label>";
        $esignatureTogglePembelian .= "</div>";

        $esignatureTogglePenjualan = "<div class='wwrapper-radio' style='margin-top: -3px;'>";
        $esignatureOnCheckedPenjualan = $esignaturePenjualanEnabled === 1 ? "checked" : "";
        $esignatureOffCheckedPenjualan = $esignaturePenjualanEnabled === 0 ? "checked" : "";
        $esignatureTogglePenjualan .= "<input id='toggle-on-esignature_penjualan' $esignatureOnCheckedPenjualan class='toggle-radio-esignature toggle-left' data-module='penjualan' name='esignature_penjualan' value='1' type='radio'>
            <label for='toggle-on-esignature_penjualan' class='btn-radio' style='border-radius: 5px 0 0 5px;'>AKTIF</label>";
        $esignatureTogglePenjualan .= "<input id='toggle-off-esignature_penjualan' $esignatureOffCheckedPenjualan class='toggle-radio-esignature toggle-right' data-module='penjualan' name='esignature_penjualan' value='0' type='radio'>
            <label for='toggle-off-esignature_penjualan' class='btn-radio' style='border-radius: 0 5px 5px 0;'>NON AKTIF</label>";
        $esignatureTogglePenjualan .= "</div>";

        // edited by glg (11:32 WIB, 2025-12-23)
        // change: tambah toggle e-signature distribusi
        // technical rationale: kontrol on/off e-signature distribusi dari setting aplikasi
        $esignatureToggleDistribusi = "<div class='wwrapper-radio' style='margin-top: -3px;'>";
        $esignatureOnCheckedDistribusi = $esignatureDistribusiEnabled === 1 ? "checked" : "";
        $esignatureOffCheckedDistribusi = $esignatureDistribusiEnabled === 0 ? "checked" : "";
        $esignatureToggleDistribusi .= "<input id='toggle-on-esignature_distribusi' $esignatureOnCheckedDistribusi class='toggle-radio-esignature toggle-left' data-module='distribusi' name='esignature_distribusi' value='1' type='radio'>
            <label for='toggle-on-esignature_distribusi' class='btn-radio' style='border-radius: 5px 0 0 5px;'>AKTIF</label>";
        $esignatureToggleDistribusi .= "<input id='toggle-off-esignature_distribusi' $esignatureOffCheckedDistribusi class='toggle-radio-esignature toggle-right' data-module='distribusi' name='esignature_distribusi' value='0' type='radio'>
            <label for='toggle-off-esignature_distribusi' class='btn-radio' style='border-radius: 0 5px 5px 0;'>NON AKTIF</label>";
        $esignatureToggleDistribusi .= "</div>";

        // edited by glg (13:10 WIB, 2025-12-23)
        // change: tambah toggle e-signature biaya
        // technical rationale: kontrol on/off e-signature biaya dari setting aplikasi
        $esignatureToggleBiaya = "<div class='wwrapper-radio' style='margin-top: -3px;'>";
        $esignatureOnCheckedBiaya = $esignatureBiayaEnabled === 1 ? "checked" : "";
        $esignatureOffCheckedBiaya = $esignatureBiayaEnabled === 0 ? "checked" : "";
        $esignatureToggleBiaya .= "<input id='toggle-on-esignature_biaya' $esignatureOnCheckedBiaya class='toggle-radio-esignature toggle-left' data-module='biaya' name='esignature_biaya' value='1' type='radio'>
            <label for='toggle-on-esignature_biaya' class='btn-radio' style='border-radius: 5px 0 0 5px;'>AKTIF</label>";
        $esignatureToggleBiaya .= "<input id='toggle-off-esignature_biaya' $esignatureOffCheckedBiaya class='toggle-radio-esignature toggle-right' data-module='biaya' name='esignature_biaya' value='0' type='radio'>
            <label for='toggle-off-esignature_biaya' class='btn-radio' style='border-radius: 0 5px 5px 0;'>NON AKTIF</label>";
        $esignatureToggleBiaya .= "</div>";

        $strEsignature = "";
        $strEsignature .= "<ul class=\"list-group list-group-unbordered\">";
        $strEsignature .= "<li class=\"list-group-item\">";
        $strEsignature .= "<b>E-Signature Pembelian</b> <span class='pull-right'>$esignatureTogglePembelian</span>";
        $strEsignature .= "</li>";
        $strEsignature .= "<li class=\"list-group-item\">";
        $strEsignature .= "<b>E-Signature Penjualan</b> <span class='pull-right'>$esignatureTogglePenjualan</span>";
        $strEsignature .= "</li>";
        $strEsignature .= "<li class=\"list-group-item\">";
        $strEsignature .= "<b>E-Signature Distribusi</b> <span class='pull-right'>$esignatureToggleDistribusi</span>";
        $strEsignature .= "</li>";
        $strEsignature .= "<li class=\"list-group-item\">";
        $strEsignature .= "<b>E-Signature Biaya</b> <span class='pull-right'>$esignatureToggleBiaya</span>";
        $strEsignature .= "</li>";
        $strEsignature .= "</ul>";

        $p->setLayoutBoxCss("box box-warning");
        $p->setLayoutBoxHeading("E-Signature Setting");
        $p->setLayoutBoxBody(true);
        $showProfile .= $p->layout_box("$strEsignature");
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
        if (my_id() == 2) {
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

        // edited by glg (22:30 WIB, 2025-12-23)
        // change: script toggle e-signature global di setting aplikasi
        // technical rationale: handle pembelian & penjualan tanpa dropdown tambahan
        $scriptBottom .= "<script>
                function sendAjaxRequestEsignature(url, data) {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: data,
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

                $('input[type=\"radio\"].toggle-radio-esignature').on('change', function() {
                    const selectedId = $(this).attr('id');
                    const labelText = $(this).next('label').text();
                    const module = $(this).data('module');
                    const onId = 'toggle-on-esignature_' + module;
                    const offId = 'toggle-off-esignature_' + module;
                    const enabled = (selectedId === onId) ? 1 : 0;

                    Swal.fire({
                        title: 'Konfirmasi Pilihan',
                        text: `Anda memilih ` + labelText + `. Apakah Anda yakin?`,
                        icon: 'question',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, lanjutkan',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const url = '$baseUrl/Setting/doSaveEsignature';
                            const data = {
                                esignature_enabled: enabled,
                                esignature_module: module
                            };
                            sendAjaxRequestEsignature(url, data);
                        }
                        else {
                            if (selectedId === onId) {
                                $(`#` + offId).prop('checked', true);
                            }
                            else {
                                $(`#` + onId).prop('checked', true);
                            }
                        }
                    });
                });
            </script>";
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
