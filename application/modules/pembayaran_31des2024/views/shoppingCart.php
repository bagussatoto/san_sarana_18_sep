<?php

if (isset($items)) {

    if (isset($fixedNoteTop)) {
        echo "<div class='alert alert-danger' style='margin-top: 0px;font-size: 15px;'>";
        echo "<span>$fixedNoteTop</span>";
        echo "</div>";
    }

    $showItems = isset($showItems) && strlen($showItems) > 0 && $showItems == "false" ? false : "true";


    if (sizeof($items) > 0) {
// arrPrint($main);
// matiHEre($tipe_transaksi_sumber);
        /*===bagian logic tambahan taxes untuk payment src*/
        if (isset($shopingCartAddTax) && sizeof($shopingCartAddTax) > 0) {
            echo "<div class=''>";
            echo "<div class='text-center text-bold bg-red text-uppercase'> Tipe konsumen </div>";
            foreach ($shopingCartAddTax["fields"] as $sels => $label) {
                $checked = $checkTaxes == $sels ? "checked" : "";
                echo "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'>
                              <input type='radio' name='switch_pajak' $checked value='$sels'  onclick=\"$('#result').load('" . $shopingCartAddTaxAction . "/?val='+this.value+'&p=$sels');\">
                              <span>$label</span>
                          </label>";
            }
            echo "</div>";
        }

        /*============end tambahan*/
        $jmlKolomHeader = sizeof($itemLabels) + 2;
        // cekHijau($jmlKolomHeader);
        echo "<div class='table-responsive no-padding no-border'>";
        /*=============== BADGE PPN / NON PPN =================*/
        if (sizeof($arrHeaderElement) > 0) {
            foreach ($arrHeaderElement as $el => $eDetails) {
                $elLabel = $eDetails['label'];
                $elClass = $eDetails['class'];
                echo "<div class='$elClass'>";
                echo "<div class='text-center text-bold bg-yellow'> $elLabel </div>";
                foreach ($eDetails['subElements'] as $sels => $seDetails) {
                    $selsLabel = $seDetails['label'];
                    $selsValue = $seDetails['value'];
                    $selsMainTarget = $seDetails['srcMain'];
                    $selsItemsTarget = $seDetails['srcItem'];
                    $mainOverwrite = $seDetails['overWriteMain'];
                    $currentPPN = isset($main[$selsMainTarget]) ? $main[$selsMainTarget] : 0;
                    $ppnPersenItems = isset($items[0]['ppnVendor']) ? $items[0]['ppnVendor'] : 0;
                    $autoTerapkan = ($ppnPersenItems != $currentPPN) && ($selsValue == $currentPPN) ? true : false;
                    $checked = $selsValue == $currentPPN ? "checked" : "";

                    $jenisTr = isset($arrHeaderElementJenis) ? $arrHeaderElementJenis : "";
                    // cekhitam($checked."$currentPPN");
                    echo "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'>
                              <input type='radio' name='switch_ppn' value='$selsValue' $checked 
                              onclick=\"$('#result').load('" . MODUL_PATH . "_processSelectProductPpn/select/$jenisTr?ppn='+this.value+'&ppnTargetItems=$selsItemsTarget&ppnTargetMain=$selsMainTarget&overWriteMain=$mainOverwrite');\">
                              <span>$selsLabel</span>
                          </label>";

                    //                     if ($autoTerapkan) {
                    //                         echo "
                    //                         <script>
                    // //                            setTimeout( function(){ $('input[name=switch_ppn]:checked').click() }, 500);
                    //                             $('#result').load('" . base_url() . "Selectors/_processSelectProductPpn/select/466?ppn=$currentPPN&ppnTargetItems=$selsItemsTarget&ppnTargetMain=$selsMainTarget')
                    //                         </script>";
                    //                     }
                }
                echo "</div>";
            }
        }
        /*=============== BADGE PPN / NON PPN =================*/
        /*
         * untuk penmpil produk di a/P payment
         */
//        arrPrint($items4);
        $listProduk = "";
        if (count($items4) > 0) {
            $colspan = count($items4Label);
            $listProduk = "<div class='panel panel-default'>";

            $listProduk .= "<div class='panel-body' style='padding: 5px;'>";
            $listProduk .= "<div id='overlay'><div id='text'>Loading Content...</div></div>";
            $listProduk .= "<div class='panel-headger text-bold font-size-1-5 text-uppercase'>Produk yang telah diterima</div>";
            $listProduk .= "<table class='table table-bordered no-margin' id='rincian_penerimaan_produk'>";

            $listProduk .= "<thead>";
            $listProduk .= "<tr class='bg-primary'>";
            $listProduk .= "<th>No</th>";
            foreach ($items4Label as $pKey => $pid_label) {
                $listProduk .= "<th>$pid_label</th>";
            }
            $listProduk .= "</tr>";
            $listProduk .= "</thead>";

            $dt = 0;
            $subtotal = 0;
            $ppn = 0;
            foreach ($items4 as $pids => $dataPID) {
                $dt++;
                $listProduk .= "<tr>";
                $listProduk .= "<td>$dt</td>";
                foreach ($items4Label as $pkey => $p_label) {
                    if ($pkey == "subtotal") {
                        $origvalue = isset($dataPID['origvalue']) ? round($dataPID['origvalue'] * 1) : $dataPID[$pkey];
                        $checked = isset($dataPID['checked']) && $dataPID['checked'] == 1 ? "checked" : "";
                        $disabled = $checked == "checked" ? "" : "disabled";
                        $backgroundColor = $checked == "checked" ? "" : "bg-red";
                        $listProduk .= "<td class='text-auto text-bold text-right no-padding' pkey='$pkey'>";
                        $listProduk .= "<div class='funkyradio-success'>";
                        $listProduk .= "
                                    <div class='input-group border-cekx'>
                                        <input it4pid='$pids' it4key='$pkey' sesVal='" . number_format($dataPID[$pkey]) . "' origvalue='" . $origvalue . "' id='in_$pids$pkey' title='!!! centang box untuk edit !!!' onclick='this.select();' size='1' $disabled value='" . number_format($dataPID[$pkey]) . "' type='text' class='in_koreksi form-control $backgroundColor text-right no-padding'>
                                        <span class='input-group-addon'>
                                            <input it4pid='$pids' it4key='$pkey' id='det_$pids$pkey' class='checkDetails' $checked onclick=\" \" type='checkbox'>
                                        </span>
                                    </div>
                              ";
                        $listProduk .= "</div>";
                        $listProduk .= "</td>";
                    }
                    else {
                        $listProduk .= "<td pkey='$pkey'>" . formatField($pkey, $dataPID[$pkey]) . "</td>";
                    }
                }
                $subtotal += $dataPID["subtotal"];
                $ppn += $dataPID["ppn"];
            }
//            $ppn = $subtotal*0.11;
            $total = $ppn + $subtotal;
            $listProduk .= "</tr>";
            $listProduk .= "<tr>";
            $listProduk .= "<th colspan='$colspan' style='font-size: 16px;' class='text-right'>Total harga produk</th>";
            $listProduk .= "<th colspan='' style='font-size: 16px;padding-right: 40px;' class='text-bold text-meta text-right'>" . number_format($subtotal) . "</th>";
            $listProduk .= "</tr>";
//            $listProduk .= "<tr>";
//            $listProduk .= "<td colspan='$colspan' class='text-right'>total PPN</td>";
//            $listProduk .= "<td colspan=''>" . formatField("subtotal", $ppn) . "</td>";
//            $listProduk .= "</tr>";
//            $listProduk .="<tr>";
//            $listProduk .="<td colspan='$colspan' class='text-right'>Total</td>";
//            $listProduk .="<td colspan=''>".formatField("total",$total)."</td>";
//            $listProduk .="</tr>";
            $listProduk .= "</table>";
            $listProduk .= "</div>";

            $listProduk .= "</div>";
            $listProduk .= "<script>
                $('.in_koreksi').off();
                $('.in_koreksi').keyup(function(){
                    this.value = addCommas(this.value)
                });
                $('.in_koreksi').keyup(delay_v2(function(){
                    var pid = $(this).attr('it4pid');
                    var key = $(this).attr('it4key');

                    var check = 0
                    if( $('#det_'+pid+''+key).prop('checked') ){
                        check = 1
                    }

                    var url = '$koreksiRecorderTarget/'+key+'/'+pid+'?val='+removeCommas(this.value)+'&check='+check

//                    $('#result').load(url, function(){
//                        console.log('===================');
//                        console.log('load: '+ url);
//                        console.log('selesai....');
//                        console.log('target #result');
//                    });

                    $.ajax(url)
                    .always(function(data) {
                        top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID='+pid, function(){
                            //document.getElementById('overlay').style.display = 'none';
                        });
                    });

                }, 1000));

                var checkBox = $('.checkDetails');
                // $('.checkDetails').trigger('change');
                // console.log('checkBox:', checkBox);
                // console.log('-------------------------------------');
                jQuery.each(checkBox, function(a,b){
                    var pid = $(this).attr('it4pid');
                    var key = $(this).attr('it4key');
                    var formValue = removeCommas($('#in_'+pid+''+key).val())*1;
                    var orig = removeCommas($('#in_'+pid+''+key).attr('origvalue'))*1;
                    var sesval = removeCommas($('#in_'+pid+''+key).attr('sesVal'))*1;
                    
                    // console.log('formValue: ' + formValue);
                    // console.log('orig: ' + orig.toFixed(0));
                    // console.log('sesval: ' + sesval);
                });
                $('.checkDetails').off();
                $('.checkDetails').on('click', function() {
                    var pid = $(this).attr('it4pid');
                    var key = $(this).attr('it4key');
                    document.getElementById('overlay').style.display = 'block';
                    if( $(this).prop('checked') ){
                        $('#in_'+pid+''+key).prop('disabled', false)
                        .removeClass('bg-red')
                        .select()
                        .trigger('keyup');
                    }
                    else{
                        var origvalue = $('#in_'+pid+''+key).attr('origvalue')*1;
                        $('#in_'+pid+''+key).val( origvalue.toFixed(0) );
                        $('#in_'+pid+''+key).prop('disabled', true)
                        .addClass('bg-red')
                        .blur()
                        .trigger('keyup');
                    }
                });
            </script>";

            // arrPrintHijau($main);
            $uang_muka_dipakai_ppn = isset($main['uang_muka_dipakai_ppn']) ? $main['uang_muka_dipakai_ppn'] : "";
            $cash_account = isset($main['cash_account']) ? $main['cash_account'] : 0;
            $is_cash_account = isset($main['cash_account']) ? 1 : 0;
            $is_skip_faktur = isset($main['skip_faktur']) ? 1 : 0;

            $listProduk .= "<script>
                    var konfirmasi_cek = false;
                    var uang_muka_dipakai_ppn = removeCommas($uang_muka_dipakai_ppn);
                    var isSkipFaktur = '$is_skip_faktur';
                    var isCashAccount = '$is_cash_account';
                    
                    function semuaCheckboxDicentang() {
                        var semuaDicentang = true;
                    
                        // Menggunakan loop untuk memeriksa setiap checkbox dalam kolom
                        var tt = 0;
                        $('.checkDetails').each(function() {
                            tt++;
                            // var checkbox = $(this).find('td:eq(7) input[type=\'checkbox\']');
                            var checkbox = $(this);                        
                                                    
                            // console.log('tt::', tt);
                            // console.log('checkbox::', checkbox.prop('checked'));
                            // console.log(checkbox);
                            // console.log('checkbox::', $(checkbox).is(\":checked\"));
                            
                            // Periksa status cek checkbox
                            if (!checkbox.prop('checked')) {
                                semuaDicentang = false;
                                return false; // Menghentikan loop jika ada checkbox yang tidak dicentang
                            }
                        });
                    
                        // console.log('semuaDicentang', semuaDicentang);
                        return semuaDicentang;
                    }
                    
                    var skip_faktur = $('#skip_faktur').prop('checked');
                    var dateFaktur = $('#dateFaktur').val();
                    var eFaktur = $('#eFaktur').val();
                    // console.log('semuaDicentang:', semuaCheckboxDicentang);
                    
                    if (semuaCheckboxDicentang()) {
                        console.log('Semua checkbox dalam kolom telah dicentang.');
                        // if(konfirmasi_cek == false){                        
                        //     $('#konfirmasi_cek').prop('disabled', false).prop('checked', false);
                        // }
                        
                        konfirmasi_cek = true;
                      // if(isUm === 1 && uang_muka_dipakai_ppn == ''){                          
                      //       // $('#uang_muka_dipakai_ppn').css('background-color', '#95fd75').focus();
                      //       $('#uang_muka_dipakai_ppn').css('background-color', 'pink').focus();
                      // }
                      // else {
                      //     console.log('hahaha');
                      //     console.log('isCashAccount', isCashAccount);
                      //     console.log('isSkipFaktur', isSkipFaktur);
                      //    
                      //     if(isSkipFaktur == 0){                              
                      //       $('#td_dateFaktur').append('<r>Isikan tanggal e-faktur</r>');
                      //       $('#td_eFaktur').append('<r>Isikan e-faktur</r>');
                      //       // $('#dateFaktur').css('border-color', 'red');
                      //       $('#eFaktur').css('border-color', 'red').focus();
                      //       // $('#eFaktur').css('border-color', 'red');
                      //     }
                      //     else if(isCashAccount == 0 && isSkipFaktur == 1){                         
                      //      
                      //       $('#elTitle_cash_account').parent().append('<r>Pilih salah satu sumber dana</r>').css('border-color', 'red').focus();
                      //     }
                      // }
                        
                    } else {
                        console.log('belum dicentang semua');
                        $('#konfirmasi_cek').prop('disabled', true).prop('checked', false);
                        $('#rincian_penerimaan_produk').focus();
                        
                        swal({type: 'warning',title: 'Upss..',html: 'Berilah tic pada setiap nilai, sebagai konfirmasi nilainya sudah benar'});
                    }
                    
                    console.log('konfirmasi_cek 297:', konfirmasi_cek);
                </script>";
        }
        echo $listProduk;
//        arrprint($shopingCartFakturParam);
//        arrprint($shopingCartFakturItems);


//        matiHere(__LINE__);
        echo "<table class='table table-condensed no-padding table-bordered no-margin'>";
        /*===============header shoping cart======================*/
        if (isset($itemLabels)) {
            if (sizeof($itemLabels) && (is_array($itemLabels)) && $showItems) {
                echo "<tr class='bg-grey-2 text-uppercase'>";
                echo "<th style='width:1%;' class='text-muted text-center'>";
                echo "NO";
                echo "</th>";
                foreach ($itemLabels as $key => $label) {
                    echo "<th style='width:1%;white-space: nowrap;' class='text-muted text-center'>";
                    echo $label;
                    echo "</th>";
                }

                //----------
                if (isset($checkOpname) && ($checkOpname == true)) {
                    echo "<th style='width:1%;' class='text-muted text-center'>";
                    echo "V";
                    echo "</th>";
                }
                //----------
                if (!$avoidRemove) {
                    echo "<th style='width:1%;' class='text-muted text-center'>";
                    echo "x";
                    echo "</th>";
                }
                echo "</tr>";
            }
        }

        /*===============body shoping cart=======================================*/
//        cekHitam($showItems);
        $no = 0;
        foreach ($items as $iSpec) {

            if ($showItems) {

                $iID = $iSpec['id'];
                $no++;
                $bgColor = "transparent";
                if (isset($_SESSION['errLines'])) {
                    if (in_array($iSpec['id'], $_SESSION["errLines"])) {
                        $bgColor = "#ffff77";
                    }
                }

                echo "<tr id='tr_" . $iSpec['id'] . "' bgcolor=$bgColor>";
                echo "<td style='vertical-align:middle; width:1%' class='text-center'>";
                echo $no;
                echo "</td>";
                $colCtr = 0;
                $queryParams = "";
                $colID = array();
                $listMode = array();
                $readOnly = array();
                $qtyParam = "";
                if (isset($itemLabels['jml'])) {
                    $qtyParam = "+removeCommas(document.getElementById('jml_$no').value)";
                }
                foreach ($itemLabels as $key => $label) {
                    $listMode[$key] = "input";
                    $keyupEvent[$key] = "";
                    $keyUpStr[$key] = "";
                    if (array_key_exists($key, $keyUpEvents)) {
                        //                    cekbiru("$key has events");
                        if (sizeof($selectedPrices) > 0) {
                            $keyupEvent[$key] = $keyUpEvents[$key];
                            foreach ($selectedPrices as $k => $v) {
                                //                            $nameLabel = "value_" . $yID . "_" . $xID . "_" . $k . ""; //==untuk nama/ID input
                                $nameLabel = $k . "_" . $no;
                                $keyupEvent[$key] = str_replace("{" . $k . "}", $nameLabel, $keyupEvent[$key]);
                            }
                            foreach ($itemLabels as $k => $v) {
                                $nameLabel = $k . "_" . $no;
                                $keyupEvent[$key] = str_replace("{" . $k . "}", $nameLabel, $keyupEvent[$key]);
                            }
                        }
                        if (isset($keyupAction) && $keyupAction == true) {
                            $keyupEvent[$key] = $keyUpEvents[$key];
                            foreach ($selectedPrices as $k => $v) {
                                //                            $nameLabel = "value_" . $yID . "_" . $xID . "_" . $k . ""; //==untuk nama/ID input
                                $nameLabel = $k . "_" . $no;
                                $keyupEvent[$key] = str_replace("{" . $k . "}", $nameLabel, $keyupEvent[$key]);
                            }
                            foreach ($itemLabels as $k => $v) {
                                $nameLabel = $k . "_" . $no;
                                $keyupEvent[$key] = str_replace("{" . $k . "}", $nameLabel, $keyupEvent[$key]);
                            }
                        }
                    }
                    else {
                    }
                    if (strlen($keyupEvent[$key]) > 2) {
                        $keyUpStr[$key] = " onkeyup=\"" . $keyupEvent[$key] . "\" ";
                    }
                    if (in_array($key, $editableFields)) {
                        $readOnly[$key] = "";
                        if (isset($iSpec["jml"]) && $iSpec["jml"] < 1) {
                            $readOnly[$key] = "readonly_xz";
                        }
                        if (isset($paramsForceEditable[$key])) {
                            if ($paramsForceEditable[$key] == true) {

                            }
                            else {
                                $readOnly[$key] = "readonly_xxz";
                                $listMode[$key] = "text";
                            }
                        }
                    }
                    else {
                        $readOnly[$key] = "readonly_xxz";
                        $listMode[$key] = "text";
                    }
                    $colID[$key] = $key . "_" . $no;
                    if ($listMode[$key] == "input") {
                        $queryParams .= "&$key='+removeCommas(document.getElementById('" . $colID[$key] . "').value)+'";
                    }
                }
                foreach ($itemLabels as $key => $label) {
                    $colCtr++;
                    $color = "343434";
                    if (isset($_SESSION['errFields'][$iSpec['id']])) {
                        if (in_array($key, $_SESSION['errFields'][$iSpec['id']])) {
                            $color = "#dd3300";
                        }
                    }
                    echo "<td align='left'>";
                    $colID = $key . "_" . $no;
                    $keyID = $key;
                    $noID = $no;
                    $tabIndexNum = $colCtr . $no;
                    $fieldVal = "";
                    if (substr($key, 0, 1) == "*") {
                        $key_p = str_replace("*", "", $key);
                        $key_ex = explode("#", $key_p);
                        $pair_name = $key_ex[0];
                        $pair_key = $key_ex[1];
                        $pair_key_val = $iSpec[$pair_key];
                        if (sizeof($key_ex) > 1) {
                            $fieldVal = isset($pairedValue[$pair_name][$pair_key_val]) ? $pairedValue[$pair_name][$pair_key_val] : "0";
                        }
                        else {
                            $fieldVal = isset($pairedValue[$pair_name]) ? $pairedValue[$pair_name] : "0";
                        }
                    }
                    else {
                        if (isset($iSpec[$key])) {
                            if (is_numeric($iSpec[$key])) {
                                $fieldVal = isset($iSpec[$key]) ? $iSpec[$key] + 0 : "";
                            }
                            else {
                                $fieldVal = isset($iSpec[$key]) ? $iSpec[$key] : "";
                            }
                        }
                    }
                    if (sizeof($minValues) > 0) {
                        $moq = isset($minValues['moq'][$iID]) ? $minValues['moq'][$iID] : 0;
                        $validateKey_up = true;
                    }
                    else {
                        $moq = 0;
                        $validateKey_up = false;
                    }
                    $keyupData = (($key == "qty" || $key == "jml") && $validateKey_up == true) ? "onkeydown=\"if(parseInt(this.value)<$moq){setTimeout(function(){ this.value='" . $iSpec[$key] . "'}, 1000);} \"" : "";

                    switch ($listMode[$key]) {
                        case "input":
                            echo "<input type='text'  min='$moq' autocomplete='off' " . $readOnly[$key] . " keyid=$keyID noid=$noID id_jml=$iID id=$colID  class='form-control text-right' style='color:$color;' value='" . niceDecimal($fieldVal) . "' onclick='this.select()' " . $keyUpStr[$key] . " ";
                            $baseInputName = isset($unionSelectors['base']) ? "document.getElementById('" . $unionSelectors['base'] . "_" . $no . "')" : "this";
                            $pemicuGerbangAsli = "onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';} \" $keyupData";
                            $pemicuGerbangAsli .= "*onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';}\" ";
                            $pemicuGerbang = "onblur=\"if($baseInputName.value!=$baseInputName.defaultValue){hiliteDiv($baseInputName);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';}\" $keyupData ";
                            $pemicuGerbang .= "*onmouseout=\"if($baseInputName.value!=$baseInputName.defaultValue){hiliteDiv($baseInputName);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';}\" ";
                            $pemicuGerbangUnion = "onchange=\"if($baseInputName.value!=$baseInputName.defaultValue){hiliteDiv($baseInputName);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';} \" ";

                            if (isset($unionSelectors['base'])) {
                                if ($unionSelectors['base'] == $key) {//==jadi acuan kiriman
                                    echo str_replace("this", $baseInputName, $pemicuGerbang);
                                }
                                else {
                                    if (in_array($key, $unionSelectors['members'])) {//==jadi member union, tidak memicu perubahan gerbang
                                        echo $pemicuGerbangUnion;
                                    }
                                    else {//==biasa aja, memicu perubahan gerbang
                                        echo $pemicuGerbangAsli;
                                    }
                                }
                            }
                            else {
                                echo $pemicuGerbangAsli;
                            }

                            if (isset($keyupAction) && $keyupAction == true) {
                                echo "onkeyup=\"document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';if(parseFloat(removeCommas(this.value))>0){ this.value=addCommas(this.value) }else{ this.value=0 }\"";
                            }
                            else {
                                echo "onkeyup=\"delay( function(){ $('#shopping_cart').trigger('change') }, 400, this );if(parseFloat(removeCommas(this.value))>0){ this.value=addCommas(this.value) }else{ this.value=0 }\"";
                            }
                            echo ">";

                            break;
                        case "text":
                            if (is_numeric($fieldVal)) {
                                echo "<span keyid=$keyID noid=$noID id=$colID class='form-control text-right' style='color:$color;background:#f0f0f0;'>" . niceDecimal($fieldVal) . "</span>";
                            }
                            else {
                                if (strlen($fieldVal) > 10) {
                                    echo "<span keyid=$keyID noid=$noID id=$colID class='' style='color:$color;border:0px;'>" . formatField($key, $fieldVal) . "</span>";
                                }
                                else {
                                    echo "<span keyid=$keyID noid=$noID id=$colID class='form-control' style='color:$color;border:0px;'>" . formatField($key, $fieldVal) . "</span>";
                                }
                            }
                            break;
                    }
                    echo "</td>";
                }

                //-----------------
                if (isset($checkOpname) && ($checkOpname == true)) {
                    if (isset($iSpec['ceklist_opname']) && ($iSpec['ceklist_opname'] == 1)) {
                        $ceklist_checked = "checked";
                    }
                    else {
                        $ceklist_checked = "";
                    }
                    echo "<td width='1%'>";
                    echo "<input type='checkbox' $ceklist_checked 
                        onclick=\"document.getElementById('result').src='" . $checkOpnamePaired . "?id=$iID';\">";
                    echo "</td>";
                }
                //-----------------
                //region remover per row
                if (!$avoidRemove) {
                    echo "<td width='1%'>";
                    echo "<a class='text-red btn' title='remove this item' data-toggle='tooltip' data-placement='left' onclick=\"document.getElementById('result').src='" . $iSpec['removeTarget'] . "';\"><span class='glyphicon glyphicon-remove'></span></a>";
                    echo "</td>";
                }
                //endregion

                echo "</tr>";

                echo "
            <script>
                \n$('#check_" . trim($iSpec['id']) . "', $('#pilihan_item')).html(\"<i class='fa fa-check'></i>\");
                \n$('#check_" . trim($iSpec['id']) . "', $('#pilihan_item')).addClass(\"text-green text-bold pull-right\");
            </script>
            ";

                if ($noteEnabled == true) {
                    $colspan2 = $imageEnable == true ? 1 : -1;
                    $colspan = sizeof($itemLabels) - $colspan2;
                    echo "<tr>";
                    echo "<td>&nbsp;</td>";
                    echo "<td colspan='" . $colspan . "'>";
                    $noteVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                    if (isset($noteType)) {
                        switch ($noteType) {
                            case "textarea":
                                echo "<textarea class='form-control' placeholder='write notes here'
                                onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID';}\"
                                onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID';}\"
                                >$noteVal</textarea>";
                                break;
                            case "text":
                            default:
                                echo "<input type=text class='form-control' value='$noteVal' placeholder='write notes here'
                                onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID';}\"
                                onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID';}\"
                                >";
                                break;
                        }
                    }

                    echo "</td>";
                    if ($imageEnable == true) {
                        echo "<td colspan='2'>";
                        $imageVal = isset($iSpec['images']) ? $iSpec['images'] : "";
                        if (isset($imageType)) {
                            switch ($imageType) {
                                case "images":

                                    $file_e = "";
                                    $file = isset($iSpec['images']) ? $iSpec['images'] : "";
                                    $file_e = urlencode($file);
                                    echo "<div class='input-groups'>";
                                    if (strlen($imageVal) > 0) {
                                        $modals = array(
                                            "title" => "Attachment " . $iSpec['nama'],
                                            "body" => array($file),
                                        );
                                        $modal_e = urlencode(blobEncode($modals));
                                        $modal_l = base_url() . "Katalog/modal/$modal_e";

                                        echo "<a href='$modal_l' data-toggle='modal' data-target='#myModal'><img src='$file' class='img-rounder' height='50px' style='float: right;'></a>";
                                        echo "<input type='hidden' name='img_$iID' value='$file'>";
                                    }

                                    echo "<form class='input-group' id='myForm_$iID' method='post' enctype='multipart/form-data' action='$imageRecorder/$iID?valValue=$file_e' target='result'>";

                                    echo "<input type='file' id='file-upload' style='border: none;' name='file' class='file' onchange=\"document.getElementById('myForm_$iID').submit();swal({'text':'uploading image ... ... ',showConfirmButton: false,timer:5000,});\">";

                                    echo "</form>";
                                    echo "</div>";

                                    break;
                                case "text":
                                default:
                                    echo "<input type=text class='form-control' value='$noteVal'
                                onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID';}\"
                                onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID';}\"
                                >";
                                    break;
                            }
                        }
                        echo "</td>";
                    }
                    echo "</tr>";
                }
                if ($pairedItemEnabled == true) {
                    if (sizeof($pairedItemField) > 0) {
                        $listModePairedItem = array();
                        $readOnlyPairedItem = array();
                        foreach ($pairedItemField as $key => $label) {
                            $listModePairedItem[$key] = "input";
                            if (in_array($key, $editableFields)) {
                                $readOnlyPairedItem[$key] = "";
                                if (isset($iSpec["jml"]) && $iSpec["jml"] < 1) {
                                    $readOnlyPairedItem[$key] = "readonly_x";
                                }
                            }
                            else {
                                $readOnlyPairedItem[$key] = "readonly_xx";
                                $listModePairedItem[$key] = "text";
                            }
                        }
                    }
                    echo "<tr>";
                    echo "<td>&nbsp;</td>";
                    $c_itemLabels = sizeof($itemLabels);
                    $c_pairedItemField = sizeof($pairedItemField);
                    $c_colspan = ($c_itemLabels - $c_pairedItemField + 1);
                    echo "<td colspan='" . $c_colspan . "'>";
                    //==pairedItems, if any
                    if (isset($selItems) && sizeof($selItems) > 0) {
                        echo "<select
                                title='Choose one of the following...'
                                data-header='Ketik Nama/Kode/Folder/Barcode'
                                data-size='10'
                                data-container='body'
                                class='picker_$iID selectpicker form-control select2 show-tick'
                                data-style='btn-primary'
                                data-live-search='true'
                                classs='form-control'
                                onchange=\"document.getElementById('result').src='" . $pairedItemRecorder . "?val='+(this.value)+'&iid=$iID'\"
                                >";

                        asort($selItems);

                        foreach ($selItems as $piID => $piName) {
                            if ($piID != $iSpec['id']) {
                                $selectedState = (isset($pairedItems[$iID]) && ($piID == $pairedItems[$iID]['id'])) ? "selected" : "";
                                $selItemsKodes = isset($selItemsKode[$piID]) ? $selItemsKode[$piID] : "-";
                                $selItemsFolders = isset($selItemsFolder[$piID]) ? $selItemsFolder[$piID] : "-";
                                $selItemsKeterangans = isset($selItemsKeterangan[$piID]) ? $selItemsKeterangan[$piID] : "-";
                                $selItemsBarcodes = isset($selItemsBarcode[$piID]) ? $selItemsBarcode[$piID] : "-";
                                echo "<option data-subtext='$selItemsKodes' data-tokens='$piID $selItemsFolders $selItemsKeterangans $selItemsBarcodes' value='$piID' $selectedState>$piName </option>";
                            }
                        }

                        echo "</select>";

                    }

                    echo "</td>";

//                echo "<script>top.$('.select2').selectpicker();</script>";
//                echo "<script> setTimeout( function(){ top.$('.picker_$iID').selectpicker(); console.log('dari shopingcart picker_$iID') }, 100 ); </script>";

                    echo "<script> $('.picker_$iID').selectpicker(); </script>";

//                echo "<script> setTimeout( function(){ top.$('.select2').selectpicker(); console.log('dari shopingcart') }, 500 ); </script>";

                    if (sizeof($pairedItemField) > 0) {
                        foreach ($pairedItemField as $key => $label) {
                            $pairedItems2ID = isset($pairedItems[$iID]['id']) ? $pairedItems[$iID]['id'] : 0;
                            $pairedItems2Qty = isset($pairedItems[$iID]['jml']) ? $pairedItems[$iID]['jml'] : 0;
                            $fieldVal = isset($pairedItems[$iID][$key]) ? $pairedItems[$iID][$key] : "";
                            echo "<td>";
                            switch ($listMode[$key]) {
                                case "input":
                                    echo "<input type='text' class='form-control text-right' value='" . $pairedItems2Qty . "' min='0' autocomplete='off'
                                    onblur=\"document.getElementById('result').src='" . $pairedItemRecorder . "?newQty='+removeCommas(this.value)+'&iid=$iID&val=$pairedItems2ID';\"
                                    onmouseout=\"document.getElementById('result').src='" . $pairedItemRecorder . "?newQty='+removeCommas(this.value)+'&iid=$iID&val=$pairedItems2ID';\"
                                    >";
                                    break;
                                case "text":
                                    if (is_numeric($fieldVal)) {
                                        echo "<span class='form-control text-right' style='color:$color;background:#f0f0f0;'>" . niceDecimal($fieldVal) . "</span>";
                                    }
                                    else {
                                        echo "<span class='form-control text-left' style='color:$color;border:0px;'>" . str_replace(" ", "&nbsp;", $fieldVal) . "</span>";
                                    }
                                    break;
                            }
                            echo "</td>";
                        }
                    }
                    echo "</tr>";
                }
            }


        }

        //region items2, kalau salah satunya untuk produksi dan konversi
        if (isset($items2) && sizeof($items2) > 0) {
            echo "<tr class='bg-info'>";
            echo "<td colspan='$jmlKolomHeader'>";

            echo "<div class='panel no-margin' sstyle='border:1px solid red;'>"; // anakan table
            echo "<table class='table table-condensed table-striped no-padding no-border'>";

            if ($shoppingCartAdvanceItems == true) {

                $no = 0;
                //region body table anakan
                $kurangStoks = array();
                foreach ($items2 as $iSpec) {
//                    arrPrintWebs($iSpec);
                    $iID = $iSpec['id'];
                    $sub_key = $iSpec['pph'];
                    $itemLabels2 = isset($shoppingCartAdvanceItemsLabel[$iID]) ? $shoppingCartAdvanceItemsLabel[$iID] : array();
                    $subItemLabels = isset($shoppingCartAdvanceSubItemsLabel[$iID]) ? $shoppingCartAdvanceSubItemsLabel[$iID] : array();
                    $editableField = isset($shoppingCartAdvanceEditableField[$iID]) ? $shoppingCartAdvanceEditableField[$iID] : array();
                    $advanceNumType = isset($shoppingCartAdvanceNumType[$iID]) ? $shoppingCartAdvanceNumType[$iID] : array();
                    $subAdvanceItems2 = isset($subAdvanceItems[$iID]) ? $subAdvanceItems[$iID] : array();// ini items2
//arrPrintWebs($subAdvanceItems2);
                    if (sizeof($itemLabels2) && (is_array($itemLabels2))) {
                        //region header table anakan
                        echo "<tr>";
                        echo "<td class='text-muted bg-grey-1 text-center'>";
                        echo "No";
                        echo "</td>";
                        foreach ($itemLabels2 as $key => $label) {
                            echo "<td class='text-muted bg-grey-1 text-center text-capitalize'>";
                            echo $label;
                            echo "</td>";
                        }
                        echo "</tr>";
                        //endregion
                    }


                    $no++;
                    $bgColor = "transparent";
                    if (isset($items2_sum_kurang) && is_array($items2_sum_kurang)) {
                        if (isset($items2_sum_kurang[$iID])) {
                            $bgColor = "yellow";
                        }
                    }
                    if (isset($_SESSION['errLines'])) {
                        if (in_array($iSpec['id'], $_SESSION["errLines"])) {
                            $bgColor = "#ffff77";
                        }
                    }
                    echo "<tr id='tr_" . $iSpec['id'] . "' bgcolor=$bgColor>";
                    echo "<td width='5%'>";
                    echo $no;
                    echo ".</td>";
                    $colCtr = 0;
                    $queryParams = "";
                    foreach ($itemLabels2 as $key => $label) {
                        $colID = $key . "_" . $no;
                        $queryParams .= "&$key='+removeCommas(document.getElementById('$colID').value)+'";
                    }
                    foreach ($itemLabels2 as $key => $label) {
                        $colCtr++;
                        $color = "343434";
                        if (isset($_SESSION['errFields'][$iSpec['id']])) {
                            if (in_array($key, $_SESSION['errFields'][$iSpec['id']])) {
                                $color = "#dd3300";
                            }
                        }
                        $cAlign = is_numeric($iSpec[$key]) ? "text-right" : "text-left";
                        //region membuat array stok yang kurang
                        if ($key == "sisa") {
                            if ($iSpec[$key] < 0) {
                                $kurangStoks[$iSpec['nama']] = $iSpec['sisa'];
                                $cAlign .= " text-red text-bold";
                            }
                            else {
                                $cAlign .= "";
                            }
                        }
                        //endregion
                        echo "<td class='$cAlign'>";
                        $tabIndexNum = $colCtr . $no;

                        if (is_numeric($iSpec[$key])) {
                            // echo "<input type=text autocomplete='off' readOnly id=$colID class='form-control text-right' style='color:$color;' value='" . $iSpec[$key] . "' >";
                            echo formatField($key, $iSpec[$key]);
                            // echo $iSpec[$key];
                        }
                        else {
                            // echo "<input type=text autocomplete='off' readOnly id=$colID class='form-control' style='color:$color;' value='" . $iSpec[$key] . "' >";
                            echo $iSpec[$key];
                        }
                        echo "</td>";
                    }
                    echo "</tr>";

                    //region --SUB_ITEMS-----------
                    if (sizeof($subItemLabels) > 0) {
                        if (isset($subItemLabels)) {
                            if (sizeof($subItemLabels) && (is_array($subItemLabels))) {
                                $colspan2 = ((isset($imageEnable)) && ($imageEnable == true)) ? 1 : 0;
                                $colspan = sizeof($itemLabels2) - $colspan2 - 1;
//                                $colspan = sizeof($subItemLabels) - $colspan2;
//                                $linkSubAdd = $shoppingCartAdvanceItemsAdd . "/$iID/$sub_key";
//cekHere("colspan: $colspan");
                                echo "<tr>";
                                echo "<td>&nbsp;</td>";
                                echo "<td colspan='" . $colspan . "'>";

                                //region tabel SUB_ITEMS
                                echo "<table class='table table-condensed no-padding no-border no-margin'>";
                                //--header sub items
                                echo "<tr class='bg-grey-1 text-uppercase'>";
                                foreach ($subItemLabels as $key => $label) {
                                    echo "<th style='width:1%;white-space: nowrap;' class='text-muted text-center'>";
                                    echo $label;
                                    echo "</th>";
                                }
//                                if (!$avoidRemove) {
//                                    echo "<th style='width:1%;' class='text-muted text-center'>";
//                                    echo "x";
//                                    echo "</th>";
//                                }
                                echo "</tr>";
                                //--body sub items
                                foreach ($subAdvanceItems2 as $subNo => $iiSpec) {
                                    echo "<tr class='bbg-grey-2 text-uppercase'>";
                                    foreach ($subItemLabels as $key => $label) {
                                        $subValue = isset($iiSpec[$key]) ? $iiSpec[$key] : "";

                                        if (in_array($key, $editableField)) {
                                            $disabled = "";
                                        }
                                        else {
                                            $disabled = "disabled";
                                        }
                                        if (array_key_exists($key, $advanceNumType)) {
                                            $input_type = "number";
                                            $text_align = "text-right";
                                            $thisValue = "?val='+removeCommas(this.value)";
                                            if ($subValue == NULL) {
                                                $subValue = 0;
                                            }
                                        }
                                        else {
                                            $input_type = "text";
                                            $text_align = "text-left";
                                            $thisValue = "?val='+encodeURIComponent(this.value)";
                                        }

                                        $subcolID = $key . "_" . $iID . "_" . $subNo;
//                                        $linkSubEditable = $shoppingCartAdvanceItemsSelector . "/$iID/$key/$subNo";
                                        echo "<td style='width:1%;white-space: nowrap;' class='text-muted'>";
//                                        echo "<input type='$input_type' id='$subcolID' class='form-control $text_align' $disabled value='$subValue'";
//                                        echo " onclick='this.select()' ";
//                                        echo " onblur=\"document.getElementById('result').src='" . $linkSubEditable . "$thisValue\"";
//                                        echo " >";
                                        echo formatField_he_format($key, $subValue, "", "");
                                        echo "</td>";

                                    }
//                                    if (!$avoidRemove) {
//                                        $linkSubRemove = $shoppingCartAdvanceItemsRemove . "/$iID/$subNo";
//                                        echo "<td style='width:1%;' class='text-muted text-center'>";
//                                        echo "<a class='text-red btn' title='remove sub item' data-toggle='tooltip' data-placement='left'
//                                            onclick=\"document.getElementById('result').src='$linkSubRemove';\"
//                                        >";
//                                        echo "<span class='glyphicon glyphicon-remove'></span>";
//                                        echo "</a>";
//                                        echo "</td>";
//                                    }
                                    echo "</tr>";
                                }


                                echo "</table class='table table-condensed no-padding table-bordered no-margin'>";
                                //endregion

//                                echo "<td>&nbsp;</td>";
                                echo "</td>";
                                if (isset($imageEnable) && ($imageEnable == true)) {
                                    echo "<td colspan='2'>";
                                    $imageVal = isset($iSpec['images']) ? $iSpec['images'] : "";
                                    if (isset($imageType)) {
                                        switch ($imageType) {
                                            case "images":

                                                $file_e = "";
                                                $file = isset($iSpec['images']) ? $iSpec['images'] : "";
                                                $file_e = urlencode($file);
                                                echo "<div class='input-groups'>";
                                                if (strlen($imageVal) > 0) {
                                                    $modals = array(
                                                        "title" => "Attachment " . $iSpec['nama'],
                                                        "body" => array($file),
                                                    );
                                                    $modal_e = urlencode(blobEncode($modals));
                                                    $modal_l = base_url() . "Katalog/modal/$modal_e";

                                                    echo "<a href='$modal_l' data-toggle='modal' data-target='#myModal'><img src='$file' class='img-rounder' height='50px' style='float: right;'></a>";
                                                    echo "<input type='hidden' name='img_$iID' value='$file'>";
                                                }

                                                echo "<form class='input-group' id='myForm_$iID' method='post' enctype='multipart/form-data' action='$imageRecorder/$iID?valValue=$file_e' target='result'>";

                                                echo "<input type='file' id='file-upload' style='border: none;' name='file' class='file' onchange=\"document.getElementById('myForm_$iID').submit();swal({'text':'uploading image ... ... ',showConfirmButton: false,timer:5000,});\">";

                                                echo "</form>";
                                                echo "</div>";

                                                break;
                                            case "text":
                                            default:
                                                echo "<input type=text class='form-control' value='$noteVal'
                                onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID';}\"
                                onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID';}\"
                                >";
                                                break;
                                        }
                                    }
                                    echo "</td>";
                                }
                                echo "</tr>";

                            }
                        }
                    }
                    //endregion --SUB_ITEMS-----------


                }
                //endregion
            }
            else {
                if (sizeof($itemLabels2) && (is_array($itemLabels2)) && $showItems) {
                    //region header table anakan
                    echo "<tr>";
                    echo "<td class='text-muted bg-grey-1 text-center'>";
                    echo "No";
                    echo "</td>";
                    foreach ($itemLabels2 as $key => $label) {
                        echo "<td class='text-muted bg-grey-1 text-center text-capitalize'>";
                        echo $label;
                        echo "</td>";
                    }
                    echo "</tr>";
                    //endregion
                }

                $no = 0;
                //region body table anakan
                $kurangStoks = array();
                foreach ($items2 as $iSpec) {
                    $iID = $iSpec['id'];
                    $no++;
                    $bgColor = "transparent";
                    if (isset($items2_sum_kurang) && is_array($items2_sum_kurang)) {
                        if (isset($items2_sum_kurang[$iID])) {
                            $bgColor = "yellow";
                        }
                    }
                    if (isset($_SESSION['errLines'])) {
                        if (in_array($iSpec['id'], $_SESSION["errLines"])) {
                            $bgColor = "#ffff77";
                        }
                    }
                    echo "<tr id='tr_" . $iSpec['id'] . "' bgcolor=$bgColor>";
                    echo "<td width='5%'>";
                    echo $no;
                    echo ".</td>";
                    $colCtr = 0;
                    $queryParams = "";
                    foreach ($itemLabels2 as $key => $label) {
                        //                if(in_array($key,$editableFields)){
                        $colID = $key . "_" . $no;
                        $queryParams .= "&$key='+removeCommas(document.getElementById('$colID').value)+'";
                        //                }
                    }

                    foreach ($itemLabels2 as $key => $label) {
                        $colCtr++;
                        $color = "343434";
                        if (isset($_SESSION['errFields'][$iSpec['id']])) {
                            if (in_array($key, $_SESSION['errFields'][$iSpec['id']])) {
                                $color = "#dd3300";
                            }
                        }
                        $cAlign = is_numeric($iSpec[$key]) ? "text-right" : "text-left";
                        //region membuat array stok yang kurang
                        if ($key == "sisa") {
                            if ($iSpec[$key] < 0) {
                                $kurangStoks[$iSpec['nama']] = $iSpec['sisa'];
                                $cAlign .= " text-red text-bold";
                            }
                            else {
                                $cAlign .= "";
                            }
                        }
                        //endregion
                        echo "<td class='$cAlign'>";
                        $tabIndexNum = $colCtr . $no;

                        if (is_numeric($iSpec[$key])) {
                            // echo "<input type=text autocomplete='off' readOnly id=$colID class='form-control text-right' style='color:$color;' value='" . $iSpec[$key] . "' >";
                            echo formatField($key, $iSpec[$key]);
                            // echo $iSpec[$key];
                        }
                        else {
                            // echo "<input type=text autocomplete='off' readOnly id=$colID class='form-control' style='color:$color;' value='" . $iSpec[$key] . "' >";
                            echo $iSpec[$key];
                        }
                        echo "</td>";
                    }
                    echo "</tr>";
                }
                //endregion

            }
            echo "</table>";
            echo "</div>"; // anakan table


            echo "</td>";
            echo "</tr>";
        }
        //endregion

        //region items3
        if (isset($items3) && sizeof($items3) > 0) {
            echo "<tr class='bg-info'>";
            echo "<td colspan='$jmlKolomHeader'>";

            // echo "<div class='table-responsive no-padding no-border border-cek overflow-h'>";
            echo "<div class='panel no-margin'>"; // anakan table
            echo "<table class='table table-condensed table-striped no-padding no-border'>";

            if (sizeof($itemLabels3) && (is_array($itemLabels3)) && $showItems) {
                //region header table anakan
                echo "<tr>";
                echo "<td class='text-muted bg-grey-1 text-center'>";
                echo "No";
                echo "</td>";
                foreach ($itemLabels3 as $key => $label) {
                    echo "<td class='text-muted bg-grey-1 text-center text-capitalize'>";
                    echo $label;
                    echo "</td>";
                }
                echo "</tr>";
                //endregion
            }

            $no = 0;
            //region body table anakan
            $kurangStoks = array();
            foreach ($items3 as $iSpec) {
                $iID = $iSpec['id'];
                $no++;
                $bgColor = "transparent";
                if (isset($_SESSION['errLines'])) {
                    if (in_array($iSpec['id'], $_SESSION["errLines"])) {
                        $bgColor = "#ffff77";
                    }
                }
                echo "<tr id='tr_" . $iSpec['id'] . "' bgcolor=$bgColor>";
                echo "<td width='5%'>";
                echo $no;
                echo ".</td>";
                $colCtr = 0;
                $queryParams = "";
                foreach ($itemLabels3 as $key => $label) {
                    //                if(in_array($key,$editableFields)){
                    $colID = $key . "_" . $no;
                    $queryParams .= "&$key='+removeCommas(document.getElementById('$colID').value)+'";
                    //                }
                }

                foreach ($itemLabels3 as $key => $label) {
                    $colCtr++;
                    $color = "343434";
                    if (isset($_SESSION['errFields'][$iSpec['id']])) {
                        if (in_array($key, $_SESSION['errFields'][$iSpec['id']])) {
                            $color = "#dd3300";
                        }
                    }
                    $cAlign = is_numeric($iSpec[$key]) ? "text-right" : "text-left";
                    //region membuat array stok yang kurang
                    if ($key == "sisa") {
                        if ($iSpec[$key] < 0) {
                            $kurangStoks[$iSpec['nama']] = $iSpec['sisa'];
                            $cAlign .= " text-red text-bold";
                        }
                        else {
                            $cAlign .= "";
                        }
                    }
                    //endregion
                    echo "<td class='$cAlign'>";
                    $tabIndexNum = $colCtr . $no;

                    if (is_numeric($iSpec[$key])) {
                        // echo "<input type=text autocomplete='off' readOnly id=$colID class='form-control text-right' style='color:$color;' value='" . $iSpec[$key] . "' >";
                        echo $iSpec[$key];
                    }
                    else {
                        // echo "<input type=text autocomplete='off' readOnly id=$colID class='form-control' style='color:$color;' value='" . $iSpec[$key] . "' >";
                        echo $iSpec[$key];
                    }
                    echo "</td>";
                }
                echo "</tr>";
            }
            //endregion


            if (isset($sumRows3) && sizeof($sumRows3) > 0) {
                $nr = 0;
                foreach ($sumRows3 as $key => $label) {
                    $val = 0;
                    $nr++;
                    $bottom_borderless = $nr < sizeof($sumRows3) ? "bottom-borderless" : "";

                    if (isset($main[$key]) && $main[$key] > 0) {
                        $val = $main[$key];
                    }
                    else {
                        if (isset($addValues[$key]) && $addValues[$key] > 0) {
                            $val = $addValues[$key];
                        }
                    }

                    echo "<tr class='bg-grey-01 3'>";
                    echo "<td colspan='" . sizeof($itemLabels3) . "' class='text-right $bottom_borderless valign-m text-uppercase'>$label</td>";
                    echo "<td class='right-borderlesss'>";
                    echo formatField($key, $val);
                    echo "</td>";
                    echo "</tr>";
                }
            }

            echo "</table>";
            echo "</div>"; // anakan table

            echo "</td>";
            echo "</tr>";
        }
        //endregion
        /*=============================sumrows============================*/
        if (isset($sumRows) && sizeof($sumRows) > 0) {
            $nr = 0;
            foreach ($sumRows as $key => $label) {
                $val = 0;
                $nr++;
                $bottom_borderless = $nr < sizeof($sumRows) ? "bottom-borderless" : "";

                if (isset($main[$key]) && $main[$key] > 0) {
                    $val = $main[$key];
                }
                else {
                    if (isset($addValues[$key]) && $addValues[$key] > 0) {
                        $val = $addValues[$key];
                    }
                }

                if ($showItems) {
                    echo "<tr class='bg-grey-01 0'>";
                    echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right $bottom_borderless valign-m text-uppercase'>$label</td>";
                    echo "<td colspan='3' class='right-borderlesss'>";
                    echo "<input type='text' id='$key' class='form-control text-right' readonly value='" . niceDecimal($val) . "' >";
                    echo "</td>";
                    echo "</tr>";
                }

            }
        }
        if (isset($sumRows2) && sizeof($sumRows2) > 0) {

            echo "<!-- ===========sumRows2============= -->";
            echo "<tr bgcolor='#e0e0e0'>";
            echo "<td colspan='" . (sizeof($itemLabels2) + 1) . "' class='text-left text-muted'><span class='fa fa-cog'></span> additional fees</td>";
            echo "</td>";
            echo "</tr>";
            $nr = 0;
            foreach ($sumRows2 as $key => $label) {
                $nr++;
                $bottom_borderless = $nr < sizeof($sumRows2) ? "bottom-borderless" : "";

                echo "<tr bgcolor='#f0f0f5'>";
                echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right bottom-borderless valign-m text-uppercase'>$label</td>";
                echo "<td>";
                echo $sumSpec2[$key];
                echo "</td>";
                echo "</tr>";
            }
        }

        /*------------------------------------------------------------------------------------------*/
        if (sizeof($addRows) > 0) {
//arrPrint($addRowLabels);
            $nr = 0;
            foreach ($addRowLabels as $k => $label) {
                $nr++;
                $bottom_borderless = $nr < sizeof($addRowLabels) ? "bottom-borderless" : "";
//                arrPrint($addRowHiddens[$k]);
                $rowHide = isset($addRowHiddens[$k]) ? $addRowHiddens[$k] : "tidak_hidden";
                echo "<tr class='$rowHide'>";
                echo "<td colspan='" . sizeof($itemLabels) . "' id='label_$k' class='text-right $bottom_borderless valign-m text-uppercase'>$label</td>";
                echo "<td colspan='2' class='text-right'>";
                echo $addRows[$k];
                echo "</td>";
                echo "</tr>";
            }

            $harga_x = isset($main['harga_x']) ? $main['harga_x'] : 0;
            $is_cash_account = isset($main['cash_account']) ? 1 : 0;
            $cash_account = isset($main['cash_account']) ? $main['cash_account'] : 0;
            $is_uang_muka = isset($main['uangMukaPpn']) ? 1 : 0;
            $uang_muka_tersedia = isset($main['uangMukaPpn__debet']) ? $main['uangMukaPpn__debet'] : 0;
            echo "<script>
                        var harga_x = $harga_x;
                        var isUm = $is_uang_muka;
                        var isCa = $is_cash_account;
                        var cash_account = $cash_account;
                        var nilai_round = $('#nilai_round').val();
                        var tagihan_bayar = $('#tagihan_bayar').val();
                        var nilai_entry = removeCommas($('#nilai_entry').val());
                        var uang_muka = removeCommas($('#uang_muka_dipakai_ppn').val());
                        var uang_muka_tersedia = $uang_muka_tersedia;
                        // var konfirmasi_cek = false;
                        
                        if(harga_x == 0){
                            var nilai_max = removeCommas(nilai_round);
                        }
                        else {
                            var nilai_max = removeCommas(harga_x);
                        }
                                                                        
                        // if(isCa == 0 && nilai_entry > 0){
                        //     $('#konfirmasi_cek').prop('disabled', true).prop('checked', false);
                        //     // konfirmasi_cek = true;
                        // }
                        
                        // console.log('isUm:', isUm);
                        // console.log('isCa:', isCa);
                        // console.log('cash_account:', cash_account);
                        // console.log('----------------------------------------');
                        // console.log('nilai_entry:', nilai_entry);
                        // console.log('nilai_max:', nilai_max);
                        // console.log('uang_muka:', uang_muka);
                        // console.log('----------------------------------------');
                        // console.log('konfirmasi_cek 12701:', konfirmasi_cek);
                        
                        // if(isUm === 1 && konfirmasi_cek === true){
                        //     $('#label_uang_muka_dipakai_ppn').append(' <label><input type=checkbox id=pakai_semua_uang_muka> pakai semua</label>');
                        //    
                        //     // var pakai_semua = $('#pakai_semua_uang_muka').prop('checked');
                        //     $('#pakai_semua_uang_muka').change(function() {
                        //         // Periksa apakah checkbox telah dicentang atau tidak
                        //         var pakai_semua = $(this).prop('checked');
                        //    
                        //         // Jika checkbox dicentang, masukkan nilai 100
                        //         if (pakai_semua) {
                        //             var umDientri = 0;
                        //             if(uang_muka_tersedia > nilai_max){
                        //                 umDientri = nilai_max;     
                        //             }
                        //             else {
                        //                 umDientri = uang_muka_tersedia;
                        //             }
                        //            
                        //             console.log('nilai_max:', nilai_max);
                        //             console.log('uang_muka:', uang_muka);
                        //             console.log('umDientri', umDientri);
                        //             $('#uang_muka_dipakai_ppn').val(umDientri).trigger('blur');
                        //         }
                        //         else { // Jika checkbox tidak dicentang, hapus nilainya
                        //             $('#uang_muka_dipakai_ppn').val(0).css('background-color', '#95fd75').trigger('blur');
                        //             $('#label_uang_muka_dipakai_ppn').css('color', 'red');
                        //         }
                        //     });
                        //    
                        //     if(uang_muka == nilai_max){
                        //         $('#pakai_semua_uang_muka').prop('checked', true);
                        //     }
                        //    
                        //    
                        //     // var uang_muka = $('#uang_muka_dipakai_ppn').val();
                        //     if(uang_muka == 0 && uang_muka < nilai_max){
                        //         // $('#uang_muka_dipakai_ppn').css('background-color', '#95fd75').focus();
                        //         $('#uang_muka_dipakai_ppn').css('background-color', '#95fd75');
                        //         $('#label_uang_muka_dipakai_ppn').css('color', 'red');
                        //         // $('#uang_muka_dipakai_ppn').css('background-color', '#95fd75').focus().val(nilai_max);
                        //         // $('#label_uang_muka_dipakai_ppn').css('color', 'red').append(' <label><input type=checkbox id=pakai_semua_uang_muka> pakai semua</label>');
                        //        
                        //        
                        //         swal({type: 'warning',title: 'Upss..',html: 'Silahkan gunakan uang muka<br> maksimal Rp.' + addCommas(nilai_max)});                                 
                        //     }                            
                        //     else if(uang_muka > nilai_max){
                        //                 $('#uang_muka_dipakai_ppn').css('background-color', '#95fd753333').focus();
                        //                 $('#label_uang_muka_dipakai_ppn').css('color', 'red');
                        //                
                        //                 swal({type: 'warning',title: 'Upss..',html: 'pengunaan uang muka maksimal Rp.' + addCommas(nilai_max)});                                        
                        //     }
                        //    
                        //      $('#uang_muka_dipakai_ppn').blur(function(){
                        //             var uang_muka = $('#uang_muka_dipakai_ppn').val();
                        //            
                        //             // console.log('uang_muka:', uang_muka);
                        //             // console.log('tagihan_bayar:', tagihan_bayar);
                        //             // console.log('nilai_entry:', nilai_entry);
                        //             if(uang_muka == 0){
                        //                 $('#uang_muka_dipakai_ppn').css('background-color', '#95fd753333').focus();
                        //                 $('#label_uang_muka_dipakai_ppn').css('color', 'red');
                        //             }
                        //           
                        //      });
                        //    
                        //     konfirmasi_cek = false;
                        //    
                        //     if(uang_muka > 0){
                        //         konfirmasi_cek = true;    
                        //     }
                        // }
                        // else if(isUm === 0 && konfirmasi_cek === true){
                        //     konfirmasi_cek = true;
                        // }
                        //
                        // console.log('konfirmasi_cek 1336:', konfirmasi_cek);
            </script>";
        }

        $avoidRemoveAll_items = isset($avoidRemoveAll_items) ? $avoidRemoveAll_items : array();
        //region clear shoping cart
        if ((!$avoidRemove) || (!$avoidRemoveAll_items)) {
            $addColspan = (isset($checkOpname) && ($checkOpname == true)) ? 3 : 2;
            echo "<tr class='bg-grey-2'>";
            echo "<td colspan='" . (sizeof($itemLabels) + $addColspan) . "'>";

            echo "<span class='pull-left'>";
            echo "<a class='text-red' href='javascript:void(0)' title='remove ALL ITEMS' data-toggle='tooltip' data-placement='right' onclick=\"confirm_alert_result('Attention !!!','Remove all items on shopping cart?','$resetLink','YES CLEAR');\"><i class='fa fa-trash'> </i> Clear Shoping Cart</a>";
            echo "</span>";

            echo "</td>";
            echo "</tr>";
        }
        //endregion
        echo "</table class='table'>";
        echo "</div class='table-responsive'>";
        $faktur = "";
        if (count($shopingCartFakturItems) > 0) {
            $faktur .= "<div class='panel panel-default' style=' margin-top: 10px;'>";
            $faktur .= "<table class='table'>";
            $faktur .= "<tr class='bg-primary'>";
            foreach ($shopingCartFakturParam["fields"] as $ff => $ff_abels) {
                $faktur .= "<th>$ff_abels</th>";
            }
            $faktur .= "</tr>";
            $faktur .= "<tr>";
            $linkFaktur = MODUL_PATH . $shopingCartFakturTarget . "/";
//            arrPrintWebs($shopingCartFakturParam["fields"]);
            foreach ($shopingCartFakturParam["fields"] as $fff => $f_labels) {
//                cekMerah($fff);

                if (isset($shopingCartFakturParam["editableFields"][$fff])) {
                    $inputType = $shopingCartFakturParam["editableFields"][$fff];

                    $defValues = isset($shopingCartFakturItems[$fff]) ? $shopingCartFakturItems[$fff] : "";

                    if ($shopingCartFakturParam["editableFields"][$fff] == "checkbox") {
                        $classinputType = "";
                        $labels = "tic disini jika faktur belum tersedia";
                        $vals = "checked";
                        $checked = isset($shopingCartFakturItems[$fff]) && $shopingCartFakturItems[$fff] == "true" ? $vals : "";
                    }
                    else {
                        $classinputType = "form-control ";
                        $labels = "";
                        $vals = "value";
                        $checked = "";
                    }

                    $value = "<input type='$inputType' id='$fff' class='$classinputType' name='$fff' onclick='this.select()' value='$defValues' $checked onblur=\"eksekutor(this.$vals,this.name)\">";
                }
                else {
                    $value = formatField($fff, $shopingCartFakturItems[$fff]);
                }
                $faktur .= "<td id='td_$fff'>$value <span class='text-danger text-bold text-blink'>$labels </span></td>";
            }
            $faktur .= "</tr>";

            $faktur .= "</table>";
            $faktur .= "</div>";
            $faktur .= "<script>
function eksekutor(nilai,nama) {
//  console.log(nilai);
//  console.log(nama);
  $('#result').load('$linkFaktur?nilai='+nilai+'&nama='+nama)
}
                
                var skip_faktur = $('#skip_faktur').prop('checked');
                // if (skip_faktur) {
                    // console.log('Checkbox dengan id skip_faktur telah dicentang.');
                // } else {
                    // console.log('Checkbox dengan id skip_faktur belum dicentang.');
                // }
                
                var dateFaktur = $('#dateFaktur').val();
                var eFaktur = $('#eFaktur').val();
                
                console.log('skip_faktur:', skip_faktur);
                console.log('dateFaktur:', dateFaktur);
                console.log('eFaktur:', eFaktur);
                console.log('konfirmasi_cek 1421:', konfirmasi_cek);
                
                if(skip_faktur == false && dateFaktur == '' && eFaktur == '' && konfirmasi_cek == true){
                    $('#td_dateFaktur').append('<r>Isikan tanggal e-faktur</r>');
                    $('#td_eFaktur').append('<r>Isikan e-faktur</r>');
                    $('#dateFaktur').css('border-color', 'red');
                    // $('#eFaktur').css('border-color', 'red').focus();
                    $('#eFaktur').css('border-color', 'red');
                    
                    $('#konfirmasi_cek').prop('disabled', true).prop('checked', false);
                    
                    swal({type: 'warning',title: 'Upss..',html: 'Silahkan isikan e-faktur dan tanggal terbitnya, atau tik kotak bila belum tersedia'});
                    konfirmasi_cek = false;
                }
                // else if(eFaktur != '' && dateFaktur == '' && skip_faktur == false){
                //     $('#td_dateFaktur').append('<r>Isikan tanggal e-faktur</r>');
                //     $('#dateFaktur').css('border-color', 'red').focus();
                //    
                //     $('#skip_faktur').prop('checked', false);
                //      $('#konfirmasi_cek').prop('disabled', true).prop('checked', false);
                // }
                //  else if(eFaktur != '' && dateFaktur != ''){
                //     // $('#td_dateFaktur').append('<r>Isikan tanggal e-faktur</r>');
                //     // $('#dateFaktur').css('border-color', 'red').focus();
                //    
                //     $('#skip_faktur').prop('checked', false);
                //     // $('#konfirmasi_cek').prop('disabled', false).prop('checked', false);
                // }
                // else if(skip_faktur == true){
                //     $('#dateFaktur').val('');
                //     $('#eFaktur').val('');
                //    
                //     if(konfirmasi_cek == false){                        
                //         $('#konfirmasi_cek').prop('disabled', false).prop('checked', false);
                //     }
                // }
                
                console.log('konfirmasi_cek 1450:', konfirmasi_cek);
                if(nilai_entry > 0 && isCa == 0 && konfirmasi_cek == true){
                    $('#elTitle_cash_account').parent().append('<r>Pilih salah satu sumber dana</r>').css('border-color', 'red').focus();
                    $('#konfirmasi_cek').prop('disabled', true).prop('checked', false);
                    konfirmasi_cek = false;
                    
                    swal({type: 'warning',title: 'Upss..',html: 'Pilih salah satu Sumber Dana untuk pembayaran 1455'});
                }
                else if(nilai_entry == 0 && isCa == 0) {
                    $('#konfirmasi_cek').prop('disabled', false).prop('checked', false);
                }
                                         
                $('input[name=\"cash_account\"]').change(function(){
                    $('#konfirmasi_cek').prop('disabled', false).prop('checked', false);
                });
                                                
</script>";

        }

        echo $faktur;
        if (isset($fixedNote)) {
            echo "<div class='alert alert-danger' style='margin-top: 10px;font-size: 15px;'>";
            echo "<span>$fixedNote</span>";
            if (isset($fixedNoteLink)) {
//                arrPrintHijau($fixedNoteLink);
                foreach ($fixedNoteLink as $fixedNoteLink_spec) {
                    $link = isset($fixedNoteLink_spec['link']) ? $fixedNoteLink_spec['link'] : NULL;
                    $labels = isset($fixedNoteLink_spec['label']) ? $fixedNoteLink_spec['label'] : NULL;
                    echo "<span><br>- $labels</span>";
                    if ($link != NULL) {
                        echo "atau <a href=\"$link\" 
                                target='_parent'>klik disini</a>.";
                    }
                }
            }
            echo "</div>";
        }

        /*---------------------sum CBM CKD------------------------------------*/
        $volume_gross = "";
        $berat_gross = "";
        if (isset($detilSizeBar)) {
            if (sizeof($detilSizeBar) > 0) {

                $volume_gross = isset($detilSizeBar['volume_gross']) ? $detilSizeBar['volume_gross'] : 0;
                $berat_gross = isset($detilSizeBar['berat_gross']) ? $detilSizeBar['berat_gross'] : 0;

                $volume = isset($detilSizeBar['volume']) ? $detilSizeBar['volume'] : 0;
                $berat = isset($detilSizeBar['berat']) ? $detilSizeBar['berat'] : 0;


                echo "<div class='row bg-danger' style='background: #ffdecf;padding: 7px;'>";
                echo "<div class='col-md-3 col-lg-3'>
                        <div class='input-group'>
                        <span class='input-group-addon' style='color: #000000;'>CBU CBM</span>
                        <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$volume' disabled=''>
                        </div>
                     </div>";
                echo "<div class='col-md-3 col-lg-3'>
                        <div class='input-group'>
                        <span class='input-group-addon' style='color: #000000;'>CBU (KG)</span>
                        <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$berat' disabled=''>
                        </div>
                     </div>";
                echo "<div class='col-md-3 col-lg-3'>
                        <div class='input-group'>
                        <span class='input-group-addon' style='color: #000000;'>CKD CBM</span>
                        <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$volume_gross' disabled=''>
                        </div>
                     </div>";
                echo "<div class='col-md-3 col-lg-3'>
                        <div class='input-group'>
                        <span class='input-group-addon' style='color: #000000;'>CKD (KG)</span>
                        <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$berat_gross' disabled=''>
                        </div>
                     </div>";
                echo "</div>";
            }
        }

        //--------
        if (isset($checkOpnameEnabled) && ($checkOpnameEnabled == true)) {
            $noteEncode1 = blobEncode($checkOpnameNote1);
            $noteEncode2 = blobEncode($checkOpnameNote2);

            if (isset($checkOpnameCek1) && ($checkOpnameCek1 == 1)) {
                $ceklist_checked_1 = "checked";
            }
            else {
                $ceklist_checked_1 = "";
            }
            if (isset($checkOpnameCek2) && ($checkOpnameCek2 == 1)) {
                $ceklist_checked_2 = "checked";
            }
            else {
                $ceklist_checked_2 = "";
            }

            $strcekNote = "<br><div class='alert alert-danger' style='text-align: left;'>";

            $strcekNote .= "<input type='checkbox' value='' $ceklist_checked_1
                onclick=\"document.getElementById('result').src='" . $checkOpnameNotePaired . "?note1=$noteEncode1';\">";
            $strcekNote .= "<span style='font-size: 20px;'>&nbsp;&nbsp; $checkOpnameNote1</span>";

            $strcekNote .= "<br><input type='checkbox' value='' $ceklist_checked_2
                onclick=\"document.getElementById('result').src='" . $checkOpnameNotePaired . "?note2=$noteEncode2';\">";
            $strcekNote .= "<span style='font-size: 20px;'>&nbsp;&nbsp; $checkOpnameNote2</span>";

            $strcekNote .= "</div>";
            echo $strcekNote;
        }
        //--------

        /*------------element------------*/
        if (sizeof($elements) > 0) {
            echo "<div class='panel-body table-responsive'>";
            echo "<div class='row'>";
            echo "<div class='col-md-12'>";
            echo "<h4 class='text-blue text-left'>Please fill in details below</h4>";
            echo "</div class='col-md-12'>";
            echo "</div class='row'>";
            echo "<div class='col-lg-12 no-padding text-center' style='text-align:center;'>";
            $elCtr = 0;
            foreach ($elements as $eName => $pSpec) {
                $elCtr++;
                if (isset($pSpec['type']) && ($pSpec['type'] == "hidden")) {
                    // type hidden tidak perlu tampil di ui //
                }
                else {
                    //region penampil untuk elemen pada shopingcart
                    if ($elCtr % 2 == 0) {
                    }
                    else {
                        echo "<div class='col-lg-12 no-padding'>";
                        echo "<div class='row row-eq-height'>";
                    }
                    echo "<div class='col-md-6 col-lg-6' style='border:2px #e1ece6 solid;margin:0px;background:" . $pSpec['bgColor'] . "'>";

                    echo "<div id='elTitle_$eName' class='text-left text-muted text-bold text-capitalize'>";

                    echo $pSpec['label'] . " ";
                    if (isset($elementConfigs[$eName]['autoSelect']) && $elementConfigs[$eName]['autoSelect']) {

                    }
                    else {
                        echo "<a href='javascript:void(0)' onclick=\"hiliteDiv(this);document.getElementById('result').src='" . $elementResetTarget . "$eName';\"><span class='fa fa-eraser'></span></a>";
                    }
                    //----------------------------------------
                    if (isset($elementConfigMutasi[$eName])) {
                        echo "&nbsp;&nbsp;&nbsp;<a href='" . $elementConfigMutasi[$eName] . "' target='_blank' title='klik untuk melihat mutasi'><span class='glyphicon glyphicon-time'></span></a>";
                    }
                    //----------------------------------------
                    echo "<span class='pull-right'><sup>" . $pSpec['editStr'] . "&nbsp;" . $pSpec['addStr'] . "</sup></span>";

                    echo "</div class='box-title'>";

                    if (isset($elementConfigs[$eName]['warningLabel']) && $elementConfigs[$eName]['warningLabel']) {
                        echo "<div class='col-md-12'>" . $elementConfigs[$eName]['warningLabel'] . "</div>";
                    }


                    echo "<div class=''>&nbsp;</div>";
                    echo $pSpec['string'];

                    echo "</div>";
                    if ($elCtr % 2 == 0) {
                        echo "</div>";
                        echo "</div>";
                    }
                    //endregion
                }
            }

            echo "</div class='row'>";

            if (isset($showScheme) && sizeof($showScheme) > 0) {

                echo "<div class='clearfix'><hr></div>";
                echo "<div class='col-md-12 no-padding'>";
                echo "<div class='text-center text-danger text-bold'>-- SKEMA PINJAMAN ANDA --</div>";
                echo "<div class='text-center text-danger text-bold meta'>generator skema hanya berlaku untuk single kreditur</div>";
                echo "<div class='text-center text-danger text-bold'> ========================================== </div>";

                //header skema
                echo "<div class='col-md-12 no-padding'>";

                echo "<span class='col-md-2 text-left text-bold no-padding'>Nama Pemegang Saham </span>
                <span class='text-left col-md-9 no-padding text-capitalize'>: " . $headerScheme['nama'] . "</span>";

//                $headerScheme = array(
//                    "nama" => "$nmPemengangSaham",
//                    "jml_pinjaman" => "$nilai_pinjaman",
//                    "bunga_tahunan" => "$rate_bunga",
//                    "awal_meminjam" => "$awal_pinjaman",
//                    "pelunasan_pinjaman" => "$jatuh_tempo",
//                    "lama_pinjaman" => "$total_hari hari ($total_bulan bln)",
//                );

                echo "<span class='col-md-2 text-left text-bold no-padding'>Jumlah Pinjaman </span>      <span class='text-left col-md-9 no-padding'>: " . number_format($headerScheme['jml_pinjaman']) . "</span>";
                echo "<span class='col-md-2 text-left text-bold no-padding'>Bunga Tahunan </span>        <span class='text-left col-md-9 no-padding'>: " . $headerScheme['bunga_tahunan'] . "%</span>";
                echo "<span class='col-md-2 text-left text-bold no-padding'>Awal Meminjam </span>        <span class='text-left col-md-9 no-padding'>: " . $headerScheme['awal_meminjam'] . "</span>";
                echo "<span class='col-md-2 text-left text-bold no-padding'>Pelunasan Pinjaman </span>   <span class='text-left col-md-9 no-padding'>: " . $headerScheme['pelunasan_pinjaman'] . "</span>";
                echo "<span class='col-md-2 text-left text-bold no-padding'>Lama Pinjaman </span>        <span class='text-left col-md-9 no-padding'>: " . $headerScheme['lama_pinjaman'] . "</span>";

                echo "</div>";
                echo "<div class='clearfix'>&nbsp;</div>";
                echo "<div><table id='main_table' class='table datatable table-bordered table-hover table-striped'><thead>";
                echo "<tr>  <th width='1%'>No</th>
                            <th>Periode</th>
                            <th>jml hari / periode</th>
                            <th>Pokok Pinjaman</th>
                            <th>Rate Bunga</th>
                            <th>Nilai Bunga</th>
                            <th>PPh23</th>
                            <th>bunga setelah dipotong PPh</th>
                      </tr>";

                echo "</thead><tbody>";

                $total_bunga = 0;
                $total_pph23 = 0;
                $total_bunga_pph23 = 0;
                $total_hari = 0;
                $no = 1;

                foreach ($showScheme as $thnbln => $pinjaman) {

                    $setBackground = isset($pinjaman['silangan']) ? $pinjaman['silangan'] : "merah";
                    $bgColor = " ";

                    switch ($setBackground) {
                        default:
                        case "merah":
                            $bgColor = "bg-white";
                            break;
                        case "hijau":
                            $bgColor = "bg-success";
                            break;
                        case "berjalan":
                            $bgColor = "bg-warning";
                            break;
                    }

                    echo "  <tr>
                                <td class='$bgColor'>$no</td>
                                <td class='$bgColor'>" . date('F Y', strtotime($pinjaman['thnbln'] . '-01')) . "</td>
                                <td class='$bgColor'>" . $pinjaman['jml_hari_dbln'] . "</td>
                                <td class='$bgColor'>" . number_format($pinjaman['nilai_pinjaman'], 0) . "</td>
                                <td class='$bgColor'>" . $pinjaman['rate_bunga'] . "%</td>
                                <td class='$bgColor'>" . number_format($pinjaman['nilai_bunga'], 0) . "</td>
                                <td class='$bgColor'>" . number_format($pinjaman['nilai_pph23'], 0) . "</td>
                                <td class='$bgColor'>" . number_format($pinjaman['nett_bunga'], 0) . "</td>
                            </tr>";

                    $no++;

                    $total_bunga += $pinjaman['nilai_bunga'] * 1;
                    $total_pph23 += $pinjaman['nilai_pph23'] * 1;
                    $total_bunga_pph23 += $pinjaman['nett_bunga'] * 1;
                    $total_hari += $pinjaman['jml_hari_dbln'] * 1;
                }

                echo "<tfoot>
                        <tr>
                            <td>-</td>
                            <td>-</td>
                            <td>" . $total_hari . "</td>
                            <td>-</td>
                            <td>-</td>
                            <td>" . number_format($total_bunga, 0) . "</td>
                            <td>" . number_format($total_pph23, 0) . "</td>
                            <td>" . number_format($total_bunga_pph23, 0) . "</td>
                        </tr>
                    </tfoot>";

                echo "</tbody>
                        </table>
                        </div>";
                echo "<div class='clearfix'>&nbsp;</div>";
                echo "<div class='text-left'>Keterangan:</div>";
                echo "<div class='text-left'> - periode dengan background hijau akan otomatis dibuatkan <span class='text-capitalize text-bold'>request loan interest</span> sesaat setelah request pinjaman diapprove </div>";
                echo "</div>";
                echo "<br>";
            }

        }

        if (sizeof($inputs) > 0) {
            echo "<div class='col-lg-12 no-padding' style='margin-top:5px;'>";
            echo "<div class='alert alert-info-dot'>";
            echo "<h4 class='text-left'>additional values</h4>";
            echo "<table class='table table-condensed'>";
            echo "<tr>";
            foreach ($inputs as $eName => $eStr) {
                echo "<td class='text-muted'>";
                echo $inputLabels[$eName];
                echo "</td>";
            }
            echo "</tr>";
            echo "<tr>";
            foreach ($inputs as $eName => $eStr) {
                echo "<td>";
                echo $eStr;
                echo "</td>";
            }
            echo "</div>";
            echo "</div>";
            echo "</tr>";
            echo "</table class='table table-condensed'>";
            echo "</div class='panel-default'>";
            echo "</div class='panel'>";
        }


        if (isset($previewJurnal) && sizeof($previewJurnal) > 0) {
            $headersJurnal = $previewJurnal['header'];

//            echo "<div class='panel panel-info col-md-12'>";

            foreach ($previewJurnal['jurnal'] as $cabangID => $subItems) {
                if (sizeof($subItems) > 0) {
                    $cabangNama = isset($previewJurnal['cabang'][$cabangID]) ? $previewJurnal['cabang'][$cabangID] : "";


                    echo "<h4 class='text-blue' style='text-align: left;margin-top: 10px;'><span class='fa fa-book'></span> preview journal entries ($cabangNama)</h4>";

                    echo "<div class='tabel table-responsive'>";
                    echo "<table class='table table-condensed'>";

                    echo "<tr bgcolor='#f0f0f0'>";
                    foreach ($headersJurnal as $key => $label) {
                        echo "<td>";
                        echo "$label";
                        echo "</td>";
                    }
                    echo "</tr>";

                    foreach ($subItems as $iSpec) {
                        echo "<tr>";
                        foreach ($headersJurnal as $key => $label) {
                            echo "<td style='text-align: left;'>";
                            echo formatField($key, $iSpec[$key]);
                            echo "</td>";
                            if (is_numeric($iSpec[$key])) {
                                if (!isset($total[$cabangID][$key])) {
                                    $total[$cabangID][$key] = 0;
                                }
                                $total[$cabangID][$key] += $iSpec[$key];
                            }
                        }
                        echo "</tr>";
                    }

                    echo "<tr style='font-size: 15px;font-weight: bold;'>";
                    foreach ($headersJurnal as $key => $label) {
                        echo "<td>";
                        if (isset($total[$cabangID][$key])) {
                            echo formatField($key, $total[$cabangID][$key]);
                        }
                        echo "</td>";
                    }
                    echo "</tr>";

                    echo "</table>";
                    echo "</div>";

                }
                else {
                    echo "<div class='text-center text-warning'>";
                    echo "- no journal affected by this transaction -<br><br>";
                    echo "</div class='text-center text-warning'>";
                }
            }
//            echo "</div>";
        }
        //--------
        if (isset($showNotes) && ($showNotes == true)) {
            echo "<br>";
            echo "<div class='box-footer bg-gray' style='margin-top:10px;'>";
            echo "<div class='row'>";
            echo "<div class='col-md-12'>";
            echo "<textarea class='form-control' placeholder='description note'
                      style='font-style:italic;font-family:Monaco, Menlo, Consolas, monospace;'
                      onblur=\"document.getElementById('result').src='$column_recorder/description?val='+encodeURIComponent(this.value);\"
                      onmouseout=\"document.getElementById('result').src='$column_recorder/description?val='+encodeURIComponent(this.value);\"
                          >$default_description</textarea>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
        if (isset($viewDescriptionNote) && ($viewDescriptionNote == true)) {
            echo "<span>Catatan:</span>";
            echo "<div class=\"box-footer bg-gray\">";
            echo "<div class=\"row\">";
            echo "<div class=\"col-md-12\">";
            echo "<textarea class=\"form-control\" placeholder=\"description note\"
                  style=\"font-style:italic;font-family:Monaco, Menlo, Consolas, 'Courier New', monospace;\"
                  onblur=\"document.getElementById('result').src='$columnRecorderTarget/description?val='+encodeURIComponent(this.value);\"
                >$default_description</textarea>";
            echo "</div class=\"col-md-12\">";
            echo "</div class=\"row\">";
            echo "</div class=\"box-footer bg-gray\">";
        }
        //--------
//        cekHere("tipe_transaksi_sumber = $tipe_transaksi_sumber");
        echo "<script>
                if( $('span[keyid=qty_debet]').length > 0 ){
                    top.shoppingCardValidator()
                    //top.console.log('perlu validator shoppingcart');
                }
                else{
                    //top.console.error('tidak perlu validator shoppingcart');
                }
                var tipe_transaksi_sumber=$tipe_transaksi_sumber;
                var jenis_transaksi_sumber=$jenis_transaksi_sumber+'_';
//                var jenis_transaksi_sumber='jas';
                
                if(tipe_transaksi_sumber==1){
                    
                    $('#nilai_entry').prop('disabled',false)
                    var tmpharus_bayar=$('#nilai_entry').val()
                    var harus_bayar=removeCommas(tmpharus_bayar)*1
                    if($transaksi_injected_entry==1){
                        $('#nilai_entry').val(harus_bayar).trigger('onblur')    
                    }
                    
                    var paymentSrc=$('.paymentSrc')
                    console.log(paymentSrc)
jQuery.each(paymentSrc,function (a,b) {
    var checked=$(b).prop('checked')
    console.log(checked)
    if(!checked){
        $(b).prop('disabled',true)
    }

})
                }
                else{
//                    console.log(jenis_transaksi_sumber)
                    switch(jenis_transaksi_sumber){
                        case '489_':
//                            console.log('underscore ',jenis_transaksi_sumber)
                                $('#nilai_entry').prop('disabled',true)
                                var paymentSrc=$('.paymentSrc')
                                jQuery.each(paymentSrc,function (a,b) {
                                $(b).prop('disabled',false)                    
//                           console.log(jenis_transaksi_sumber)
            
            // console.log($(b).parent().parent())
                                })
                                jQuery.each(paymentSrc,function (a,b) {
                                var checked=$(b).parent().parent().parent()
                                var cci=$(checked).children()[1]
                                var cdi=$('span',$(cci)).text()
                                if(cdi=='pemindahbukuan'){
                                    $(b).prop('disabled',true)
                                }
                                
                                // console.log($(b).parent().parent())
                                })
                            break;
                            default:
//                                console.log('default....')
                                    $('#nilai_entry').prop('disabled',false)
                                    var tmpharus_bayar=$('#nilai_entry').val()
                                    var harus_bayar=removeCommas(tmpharus_bayar)*1
                                    if($transaksi_injected_entry==1){
                                        $('#nilai_entry').val(harus_bayar).trigger('onblur')    
                                    }
                                break;
                       
                    }
//                    
//                    else{
//                        
//                    }
                }
                close_holdon()
                </script>";
    }
    else {

        echo "<script>

var paymentSrc=$('.paymentSrc')
                    jQuery.each(paymentSrc,function (a,b) {
    $(b).prop('disabled',false)                    
    
    // console.log($(b).parent().parent())
                    })
close_holdon()
</script>";
        /*
         * ini milik setor PPN Bulanan*/
//        echo "<div class='panel-body'>";
//        echo "<div class='text-danger text-center'>";
//        echo "- <strong>KAMU BELUM MEMILIH PPN KELUARAN/PPN MASUKAN</strong> -<br>";
//        echo "<small>KAMU SETIDAK NYA HARUS MEMILIH SATU (1) PPN KELUARAN dan SATU (1) PPN MASUKAN</small><br>";
//        echo "</div>";
//        echo "</div>";
    }

    $sessionCleares = array("errLines", "errFields", "errMsg");
    foreach ($sessionCleares as $s) {
        if (isset($_SESSION[$s])) {
            unset($_SESSION[$s]);
        }
    }

}
