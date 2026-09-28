<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 8/16/2018
 * Time: 8:51 PM
 */

switch ($mode) {
    case "scaner":
        // cekHere();
        $add_style = "font-size:20px;";
        $contens = "";
        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/scaner.html");
        $cabang_id = my_cabang_id();
        $list_data = "";
        $tipe_input = $isMobile == true ? "hidden" : "text";

        // $linkAddRak = base_url() . "Data/add/RakCabang";
        // $addRak = modalDialogBtn("Penambahan Rak", $linkAddRak);
        // $list_data .= "<div class='input-group'>";
        // $list_data .= "<div class='input-group-btn'>";
        // $list_data .= "<button class='btn btn-info' id=\"scan_atas\" onclick=\"getScan();\"><i class=\"fa fa-qrcode\"></i></a>";
        // // $list_data .= "<button class='btn btn-info'><i class='fa fa-barcode'></i></button>";
        // $list_data .= "</div>";
        // $list_data .= "<input id='qr_scaner' type='hidden' readonly class='form-control' value='' placeholder='kode produk' onkeyup=\"getData('" . $do_scane . "?str='+encodeURI(this.value), 'hasil')\">";
        // $list_data .= "<div class='input-group-btn'>";
        // // $list_data .= "<button class='btn btn-info' id=\"scan_atas\" onclick=\"getScan();\"><i class=\"fa fa-qrcode\"></i></a>";
        // // $list_data .= "<button class='btn btn-info' onclick=\"$addRak\" title='tambah rak'><i class='fa fa-plus'></i></button>";
        // $list_data .= "</div>";
        // $list_data .= "</div>";

        $list_data .= "
            <div class='col-xs-12 text-center hidden-lg hidden-md hidden-sm' onclick=\"getScan();\">
                <i class='fa fa-qrcode thumnail-image' style='font-size: 60px;'></i>
                <div class='meta'> klik icon QR/Barcode untuk membuka scanner </div>
                <label class='table cell'> Scan QRcode pada produk yang akan dipersiapkan</label>
            </div>
        ";

        $list_data .= "
            <div
                style='position: fixed; z-index: 999; bottom: 60px; padding: 10px 14px; border-radius: 30px; margin: auto; left: 45%; display: none;'
                class='qr_btn_bawah btn btn-xs btn-info hidden-lg hidden-md hidden-sm'
                onclick=\"getScan();\">
                <i class='fa fa-qrcode thumnail-image' style='font-size: 35px;'></i>
            </div>
        ";

        $list_data .= "<div class='text-bold text-center text-primary margin fa-2x hidden-xs'>Pastikan Cursor mengarah ke form Input Barcode/QRCode sebelum melakukan scan</div>";

        $list_data .= "
            <div class='row hidden-xs'>
                <div class='container-fluid'>
                    <div id='input-group-qr' class='input-group' style='display:block!important;'>
                        <input id='qr_scaner' type='$tipe_input' onclick='select()' class='form-control text-center text-bold' value='' placeholder='kode produk'>
                        <span id='qr_scaner_go_group' class='input-group-btn hidden'>
                            <button id='qr_scaner_go' type='button' class='btn btn-info btn-flat'><i class='fa fa-send'></i> GO!</button>
                        </span>
                    </div>
                </div>
            </div>
        ";

//        $list_data .= "<textarea id='qr_scaner' autofocus='on' type='$tipe_input' class='form-control' value='' placeholder='kode produk' onkeyups=\"getData('" . $do_scane . "?str='+encodeURI(this.value), 'hasil')\"></textarea>";

        $list_data .= "
            <div class='row text-center margin hidden'>
                <div onclick='openBulkMode()' class='btn btn-xs btn-warning'>Open Scanner Bulk Mode</div>
            </div>
        ";

        $list_data .= "<div id='hasil'></div>";

        $list_data .= "
            <style>
                #qr-reader__dashboard{
                    display: none;
                }
                .swal-fullscreen {
                    min-height: 100vh!important;
                    width: 100vw!important;
                }
            </style>

            <script>

                $(window).scroll(function(){
                    var posisi = $(this).scrollTop();
                    if(posisi>100){
                        //console.log('qrcode bawah muncul');
                        $('.qr_btn_bawah').show();
                    }
                    else{
                        //console.log('qrcode bawah bersembunyi');
                        $('.qr_btn_bawah').hide();
                    }
                });

                var focusToQrForm = setInterval( function(){
//                    $('#qr_scaner').focus();
                }, 1000);

                var sesItems = ".json_encode($items).";

                function close_scanner(){
                    $('.tbl_stop').click();
                    setTimeout(function(){
                        swal.close();
                    }, 1000)
                }

                var s_error = new Audio('//cdn.mayagrahakencana.com/assets/suport/sound/wrong-buzzer-6268.mp3');
                var s_right = new Audio('//cdn.mayagrahakencana.com/assets/suport/sound/beep-sound-8333.mp3');

                function openBulkMode(){

                    swal({
                        title: \"BULK SCANNER <span onclick='close_scanner()' class='pull-right'><i class='fa fa-close'></i></span>\",
                        html: \"<div class='cam-container'>\" +
                              \"<div id='qr-reader' style='margin:auto;width:75%'></div>\" +
                              \"<div id='qr-reader-results'></div>\" +
                              \"<div class='tombol_tools margin'></div>\" +
                              \"<div class='count margin'></div>\" +
                              \"</div>\",
                        animation: false,
                        allowOutsideClick: false,
                        customClass: 'swal-fullscreen',
                        showConfirmButton: false,
                        onOpen: function(){
                            $('.swal2-container').css('padding', '0px');
                            $('.swal2-modal').css('border-radius', '0px');

                            var countResult = {};
                            jQuery.each(sesItems, function(a, b){
                                var barcode = b.barcode;
                                countResult[barcode] = 0;
                            })

                            tbl = \"<div class='cam_select margin'></div>\"
                            tbl += \"<div>\"
                            tbl += \"<span class='tbl_start btn btn-md btn-default text-gray-3 text-bold'><i class='fa fa-play'></i> Mulai Scanning</span>\"
                            tbl += \"<span class='tbl_stop btn btn-md btn-default text-gray-3 text-bold'><i class='fa fa-stop'></i> Stop Scanning</span>\"
                            tbl += \"<span class='tbl_permision btn btn-md btn-default text-gray-3'>minta permission kamera</span>\"
                            tbl += \"</div>\"

                            $('.tombol_tools').html(tbl)

                            $('.tbl_start').on('click', function(){
                                $('.tbl_start').hide();
                                $('.tbl_stop').show();
                                var cam_selected = $('#select_camera').val();
                                $('#html5-qrcode-select-camera').val(cam_selected);
                                $('#select_camera').prop('disabled', true);
                                $('#html5-qrcode-button-camera-start').click();
                                console.log('camera start');
                            });

                            $('.tbl_stop').on('click', function(){
                                $('.tbl_stop').hide();
                                $('.tbl_start').show();
                                $('#html5-qrcode-button-camera-stop').click();
                                $('#select_camera').prop('disabled', false);
                                //$('#qr-reader').find('div').first().remove();
                                console.log('camera stop');
                            });

                            $('.tbl_permision').on('click', function(){
                                $('.tbl_stop').show();
                                $('.tbl_start').show();
                                $('#html5-qrcode-button-camera-permission').click();
                                $('.tbl_permision').hide();
                                console.log('request permission');
                            });

                            function docReady(fn){
                                console.log('docReady #1');
                                if (document.readyState === 'complete' || document.readyState === 'interactive') {
                                    setTimeout(fn, 2500);
                                    console.log('docReady #2');
                                }
                                else{
                                    document.addEventListener('DOMContentLoaded', fn);
                                    console.log('docReady #3');
                                }
                            }

                            docReady(function(){
                                var lastResult, countAllResults = 0;
                                function onScanSuccess(decodedText, decodedResult) {
                                    hasil = decodedResult.decodedText
                                    if(countResult[hasil] != undefined){
                                        countResult[hasil] = countResult[hasil] + 1
                                        countAllResults++;
                                        s_right.play();
                                    }
                                    else{
//                                        countResult[hasil] = 1
//                                        countAllResults++;
                                        s_error.play();
                                    }
                                    lastResult = decodedText;
                                    var view = ''
                                    view += \"<div class='margin text-bold text-primary'><u>PROGRESS...</u></div>\"
                                    var num = 0
                                    jQuery.each(countResult, function(bc, count){
                                        num++;
                                        color = bc==hasil ? 'text-left bg-green text-bold' : 'text-left text-bold text-gray-c'
                                        view += \"<div class='\"+color+\"'>\"+ num + '. ' + bc + ': ' + count + '</div>'
                                    })
                                    if(countAllResults*1>0){
                                        view += \"<div class='bg-info text-left text-bold margin'>===>>> TOTAL\" + ': ' + countAllResults + '</div>'
                                    }
                                    $('.count').html(view)
                                    s_right.play();
                                }
                                var html5QrcodeScanner = new Html5QrcodeScanner('qr-reader', { fps: 1, qrbox: { width: 300, height: 300 }, aspectRatio: 1.0 });
                                html5QrcodeScanner.render(onScanSuccess);
                                if(html5QrcodeScanner.persistedDataManager.data.hasPermission){
                                    setTimeout(function(){
                                        var camera_list = $('select#html5-qrcode-select-camera option');
                                        if(camera_list.length>0){
                                            var select_camera = \"<select id='select_camera'>\"
                                            jQuery.each(camera_list, function(a, b){
                                                var cam_id = $(b).val();
                                                var cam_nama = $(b).text();
                                                select_camera += \"<option value='\"+cam_id+\"'>\"+cam_nama+\"</option>\"
                                            })
                                            select_camera += \"</select>\"
                                            $('.cam_select').html(select_camera);
                                            $('#select_camera').prop('disabled', true);
                                        }
                                        else{
                                            swal('DEVICE ANDA TIDAK MEMILIKI KAMERA...')
                                        }
                                        //$('#qr-reader').find('div').first().remove();
                                    }, 2000);
                                    $('.tbl_start').hide();
                                    setTimeout(function(){
                                        $('.tbl_start').click()
                                    }, 3500)
                                    $('.tbl_permision').hide();
                                }
                                else{
                                    $('.tbl_start').hide();
                                    $('.tbl_stop').hide();
                                    $('.tbl_permision').show();
                                }
                            });
                        }
                    })
                }

                $('#qr_scaner_go').off();
                $('#qr_scaner_go').bind('click', function(e){
                    getData('" . $do_scane . "?str='+encodeURI($('#qr_scaner').val()), 'hasil')
                    $('#qr_scaner').val('').focus();
                    $('#qr_scaner_go_group').removeClass('hidden').addClass('hidden');
                    $('#input-group-qr').css('display', 'block')
                });

                $('#qr_scaner').off();
                $('#qr_scaner').bind('keyup', delay_v2(function(e){
                    e.preventDefault();
                    if(e.key=='Enter'){

                        arrMultiForm = this.value.split(/\\r?\\n/g);

                        if(arrMultiForm.length>0){
                            var time = 500;
                            jQuery.each(arrMultiForm, function(a,b){
                                if(b!=''){
                                    setTimeout( function(){
                                        getData('" . $do_scane . "?str='+encodeURI(b), 'hasil')
                                    }, time);
                                    time += 500;
                                }
                            })
                        }
                        else{
                            swal('ISI SKU/BARCODE PADA FORM KEMUDIAN ENTER')
                        }


                        this.value = ''

//                        if(this.value!=''){
//                            getData('" . $do_scane . "?str='+encodeURI(this.value), 'hasil')
//                            $(this).val('').focus();
//                            $('#qr_scaner_go_group').removeClass('hidden').addClass('hidden');
//                            $('#input-group-qr').css('display', 'block')
//                        }
//                        else{
//                            swal('ISI SKU/BARCODE PADA FORM KEMUDIAN ENTER')
//                        }
                    }
                    if( $(this).val().length >= 4 ){
                        $('#qr_scaner_go_group').removeClass('hidden');
                        $('#input-group-qr').css('display', 'table')
                    }
                    else{
                        $('#qr_scaner_go_group').removeClass('hidden').addClass('hidden');
                        $('#input-group-qr').css('display', 'block')
                    }
                }, 250));

            </script>
        ";

        if (sizeof($items) > 0) {
            $list_data .= "<div id='shopingcart_mobile' class='margin-top-50 table-responsive'>";
            $list_data .= "</div>";
            $list_data .= "<script>
                                $('#shopingcart_mobile').load('$shopingcart_mobile');
                          </script>";
        }

        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
                "btn_top" => "",
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),
                "content" => $list_data,
                "profile_name" => $this->session->login['nama'],
            )
        );

        // $p->setContent($contens);
        $p->render();
        break;
}