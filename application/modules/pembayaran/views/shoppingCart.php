<?php

if (isset($items)) {


    $elementTimeStart = microtime(true);

    if (isset($fixedNoteTop)) {
        echo "<div class='alert alert-danger' style='margin-top: 0px;font-size: 15px;'>";
        echo "<span>$fixedNoteTop</span>";
        echo "</div>";
    }

    $showItems = isset($showItems) && strlen($showItems) > 0 && $showItems == "false" ? false : "true";

    if (sizeof($items) > 0) {


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


        //-------------------------------------
        if (sizeof($arrRefData) > 0) {
            $listRef = "";
            foreach ($arrRefData as $tr_ref_po => $tr_ref_po_data) {
                $main_data = $tr_ref_po_data["main"];
                $items_data = $tr_ref_po_data["items"];
                $nomer_ref_po = $main_data["nomer"];
                $nomer_ref_po_f = formatField_he_format("nomer_nolink", $nomer_ref_po) . "-" . $main_data["global_number_reference"];

                $listRef .= "<div class='panel panel-default'>";
                $listRef .= "<div class='panel-body' style='padding: 5px;'>";
                $listRef .= "<div class='panel-headger text-bold font-size-1-5 text-uppercase border-cekk overflow-h'>Nomer PO: $nomer_ref_po_f</div>";
                $listRef .= "<table class='table table-bordered table-condensed no-margin' id='rincian_penerimaan_produk_00'>";

                $listRef .= "<thead>";
                $listRef .= "<tr class='bg-primary'>";
                $listRef .= "<th>No</th>";
                foreach ($receiptDetailFieldsReference as $pKey => $pid_label) {
                    if (is_array($pid_label)) {
                        $listRef .= "<th>" . $pid_label["label"] . "</th>";
                    }
                    else {
                        $listRef .= "<th>$pid_label</th>";
                    }
                }
                foreach ($receipCartNumFieldsReference as $pKey => $pid_label) {
                    if (is_array($pid_label)) {
                        $listRef .= "<th>" . $pid_label["label"] . "</th>";
                    }
                    else {
                        $listRef .= "<th>$pid_label</th>";
                    }
                }
                $listRef .= "<th>Subtotal</th>";
                $listRef .= "</tr>";
                $listRef .= "</thead>";

                $dt = 0;
                foreach ($items_data as $pids => $dataPID) {
                    $dt++;
                    $listRef .= "<tr>";
                    $listRef .= "<td>$dt</td>";
                    foreach ($receiptDetailFieldsReference as $pKey => $pid_label) {
                        if (is_array($pid_label)) {
                            $listRef .= "<td class='text-bold'>" . formatField($pKey, $dataPID[$pKey]) . "</td>";
                        }
                        else {
                            $listRef .= "<td class='text-bold'>" . formatField($pKey, $dataPID[$pKey]) . "</td>";
                        }
                    }
                    foreach ($receipCartNumFieldsReference as $pKey => $pid_label) {
                        if (is_array($pid_label)) {
                            $listRef .= "<td class='text-bold'>" . formatField($pKey, $dataPID[$pKey]) . "</td>";
                        }
                        else {
                            $listRef .= "<td class='text-bold'>" . formatField($pKey, $dataPID[$pKey]) . "</td>";
                        }
                    }
                    $listRef .= "<td  class='text-bold'>" . formatField("subtotal", $dataPID["subtotal"]) . "</td>";
                    $listRef .= "</tr>";
                }

                $colspan = count($receiptDetailFieldsReference) + count($receipCartNumFieldsReference) + 1;
                $listRef .= "<tfoot cclass='bg-gray'>";
                foreach ($receiptSumFieldsReference as $pkey => $p_label) {
                    $listRef .= "<tr>";
                    $listRef .= "<td colspan='$colspan' style='font-size: 12px;padding-right: 20px;' class='text-bold text-meta text-right'>$p_label</td>";
                    $listRef .= "<td colspan='' style='font-size: 12px;padding-right: 10px;' class='text-bold text-meta text-right'>" . formatField($pkey, $main_data[$pkey]) . "</td>";
                    $listRef .= "</tr>";
                }
                $listRef .= "</tfoot>";

                $listRef .= "</table class='table table-bordered no-margin' id='rincian_penerimaan_produk_00'>";
                $listRef .= "</div class='panel-body' style='padding: 5px;'>";
                $listRef .= "</div class='panel panel-default'>";
                $listRef .= "<br>";
            }
            echo $listRef;
        }
        //-------------------------------------
        if (sizeof($itemsBelumGrn) > 0) {
            $listRef = "";
            foreach ($mainBelumGrn as $trid => $mainBelumGrnSpec) {
                $nomer_ref_po = $mainBelumGrnSpec["transaksi_no"];
                $nomer_ref_po_f = formatField_he_format("nomer_nolink", $nomer_ref_po) . "-" . $arrRefData[$trid]["main"]["global_number_reference"];
                $bgcolor = $mainBelumGrnSpec["background_color"];

                $listRef .= "<div class='panel panel-default'>";
                $listRef .= "<div class='panel-body' style='padding: 5px;'>";
                $listRef .= "<div class='panel-headger text-bold font-size-1-5 text-uppercase border-cekk overflow-h'>Belum GRN Nomer PO: $nomer_ref_po_f</div>";
                $listRef .= "<table class='table table-bordered table-condensed no-margin' id='rincian_penerimaan_produk_01'>";

                $listRef .= "<thead>";
                $listRef .= "<tr class='bg-primary'>";
                $listRef .= "<th>No</th>";
                foreach ($receiptDetailFieldsReference as $pKey => $pid_label) {
                    if (is_array($pid_label)) {
                        $listRef .= "<th>" . $pid_label["label"] . "</th>";
                    }
                    else {
                        $listRef .= "<th>$pid_label</th>";
                    }
                }
                foreach ($receipCartNumFieldsReference as $pKey => $pid_label) {
                    if (is_array($pid_label)) {
                        $listRef .= "<th>" . $pid_label["label"] . "</th>";
                    }
                    else {
                        $listRef .= "<th>$pid_label</th>";
                    }
                }
                $listRef .= "<th>Subtotal</th>";
                $listRef .= "</tr>";
                $listRef .= "</thead>";

                $dt = 0;
                foreach ($itemsBelumGrn[$trid] as $pids => $dataPID) {
                    $dt++;
                    $listRef .= "<tr style='background-color:$bgcolor;'>";
                    $listRef .= "<td>$dt</td>";
                    foreach ($receiptDetailFieldsReference as $pKey => $pid_label) {
                        if (is_array($pid_label)) {
                            $listRef .= "<td class='text-bold'>" . formatField($pKey, $dataPID[$pKey]) . "</td>";
                        }
                        else {
                            $listRef .= "<td class='text-bold'>" . formatField($pKey, $dataPID[$pKey]) . "</td>";
                        }
                    }
                    foreach ($receipCartNumFieldsReference as $pKey => $pid_label) {
                        if (is_array($pid_label)) {
                            $listRef .= "<td  class='text-bold'>" . formatField($pKey, $dataPID[$pKey]) . "</td>";
                        }
                        else {
                            $listRef .= "<td class='text-bold'>" . formatField($pKey, $dataPID[$pKey]) . "</td>";
                        }
                    }
                    $listRef .= "<td class='text-bold'>" . formatField("subtotal", $dataPID["subtotal"]) . "</td>";
                    $listRef .= "</tr>";
                }

                $colspan = count($receiptDetailFieldsReference) + count($receipCartNumFieldsReference) + 1;
                $listRef .= "<tfoot cclass='bg-gray'>";
                foreach ($receiptSumFieldsReference as $pkey => $p_label) {
                    $listRef .= "<tr style='background-color:$bgcolor;'>";
                    $listRef .= "<td colspan='$colspan' style='font-size: 12px;padding-right: 20px;' class='text-bold text-meta text-right'>$p_label</td>";
                    $listRef .= "<td colspan='' style='font-size: 12px;padding-right: 10px;' class='text-bold text-meta text-right'>" . formatField($pkey, $mainBelumGrnSpec[$pkey]) . "</td>";
                    $listRef .= "</tr>";
                }
                $listRef .= "</tfoot>";

                $listRef .= "</table class='table table-bordered no-margin' id='rincian_penerimaan_produk_01'>";
                $listRef .= "</div class='panel-body' style='padding: 5px;'>";
                $listRef .= "</div class='panel panel-default'>";
                $listRef .= "<br>";
            }
            echo $listRef;
        }
        //-------------------------------------


        $listProduk = "";
        if (count($items4) > 0) {
            $colspan = count($items4Label);

            $input_diskon_global = "";
            if ($transaksi_jenis == "489") {
                $input_diskon_global .= "<div class='input-group pull-right' style='width: 20VW;padding: 6px;'>";
                $input_diskon_global .= "<span class='input-group-addon text-bold text-red'>ADDITIONAL DISKON</span>";
                $input_diskon_global .= "<span class='input-group-addon'><input placeholder='masukan nilai' style='min-width: 8vw;' type='text' id='global_disc' class='form-control text-right text-bold'></span>";
                $input_diskon_global .= "<span class='input-group-addon'><span id='diskon_terapkan' class='btn btn-md btn-warning btn-flat pull-right'>TERAPKAN</span></span>";
//            $input_diskon_global .= "<span class='input-group-addon'><input type='checkbox' id='global_disc_tic' onclick=\"add_global_disc('global_disc');\" class='pull-right'></span>";
                $input_diskon_global .= "</div>";
            }


            $listProduk = "<div class='panel panel-default'>";

            $listProduk = "";
            $listProduk .= "<style type='text/css'>
 .btn-radio {
            border: 1px solid #1a1a1a;
            display: inline-block;
            padding: 7px 0;
            position: relative;
            text-align: center;
            transition: background 600ms ease, color 600ms ease;
        }

        input[type=\"radio\"].toggle-radio {
            display: none;

            & + label {
                cursor: pointer;
                min-width: 30px;

                &:hover {
                    background: none;
                    color: #1a1a1a;
                }

                &:after {
                    background: #1a1a1a;
                    content: \"\";
                    height: 100%;
                    position: absolute;
                    top: 0;
                    transition: left 200ms cubic-bezier(0.77, 0, 0.175, 1);
                    width: 100%;
                    z-index: -1;
                }
            }

            &.toggle-left + label {
                border-right: 0;

                &:after {
                    left: 100%
                }
            }
            
            &.toggle-center + label {
                 margin-left: -4px;

                &:after {
                 left: -100%;
             }
            }
            
            &.toggle-right + label {
                margin-left: -4px;

                &:after {
                    left: -100%;
                }
            }

            &:checked + label {
                background-color: #9eff2d;
                cursor: default;
                color: #e30000;
                transition: color 200ms;

                &:after {
                    left: 0;
                }
            }

        }
</style>";
            $listProduk .= $optionPpn;

            $listProduk .= "<div class='panel panel-default'>";
            $listProduk .= "<div class='panel-body' style='padding: 5px;'>";
            $listProduk .= "<div id='overlay'><div id='text'>Loading Content...</div></div>";
            $listProduk .= "<div class='panel-headger text-bold font-size-1-5 text-uppercase border-cekk overflow-h'>$shopingCartPairProdukGateLabel $input_diskon_global</div>";
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
//                arrPrintCyan($dataPID);
                $dt++;
                $listProduk .= "<tr>";
                $listProduk .= "<td>$dt</td>";
                foreach ($items4Label as $pkey => $p_label) {
                    if ($pkey == "subtotal") {
                        if($allow_koreksi){
                        $origvalue = isset($dataPID['origvalue']) ? round($dataPID['origvalue'] * 1) : $dataPID[$pkey];
                        $checked = isset($dataPID['checked']) && $dataPID['checked'] == 1 ? "checked" : "";
                        $disabled = $checked == "checked" ? "" : "disabled";
                        $backgroundColor = $checked == "checked" ? "bg-olive" : "bg-red";
                        $listProduk .= "<td pkey='$pkey' class='text-auto text-bold text-right no-padding'>";
                        $listProduk .= "<div class='funkyradio-success'>";
                        $listProduk .= "
                                    <div class='input-group border-cekx'>
                                        <input it4pid='$pids' it4key='$pkey' sesVal='" . number_format($dataPID[$pkey]) . "' origvalue='" . $origvalue . "' id='in_$pids$pkey' title='!!! centang box untuk edit !!!' onclick='this.select();' size='1' $disabled value='" . number_format($dataPID[$pkey]) . "' type='text' class='in_koreksi no-border form-control $backgroundColor text-right no-padding'>
                                        <span class='input-group-addon'>
                                            <input name='arrItems4' it4pid='$pids' it4key='$pkey' id='det_$pids$pkey' class='checkDetails' $checked onclick=\" \" type='checkbox'>
                                        </span>
                                    </div>
                              ";
                        $listProduk .= "</div>";
                        $listProduk .= "</td>";
                        }
                        else{
                            $listProduk .= "<td pkey='$pkey' class='text-bold'>" . formatField($pkey, $dataPID[$pkey]) . "</td>";
                        }

                    }
                    else {
                        if ($pkey == "add_disc_persen") {
                            $origvalue = isset($dataPID['origvalue']) ? round($dataPID['origvalue'] * 1) : $dataPID[$pkey];
                            $checked = isset($dataPID['checked']) && $dataPID['checked'] == 1 ? "checked" : "";
                            $disabled = $checked == "checked" ? "" : "disabled";
                            $backgroundColor = $checked == "checked" ? "" : "bg-red";
                            $listProduk .= "<td pkey='$pkey' class='text-auto text-bold text-right no-padding'>";
                            $listProduk .= "<div class='funkyradio-success'>";
                            $listProduk .= "
                                    <div class='input-groupx border-cekx'>
                                        <input disabled it4_subtotal='" . $dataPID['subtotal'] . "' placeholder='$pkey' it4pid='$pids' it4key='$pkey' sesVal='" . number_format($dataPID[$pkey]) . "' origvalue='" . $origvalue . "' id='in_$pids$pkey' title='!!! centang box untuk edit !!!' onclick='this.select();' size='1' $disabled value='" . number_format($dataPID[$pkey]) . "' type='text' class='in_koreksi_$pkey no-border form-control $backgroundColor text-right no-padding'>
                                    </div>
                              ";
                            $listProduk .= "</div>";
                            $listProduk .= "</td>";
                        }
                        else {
                            if ($pkey == "add_disc_rupiah") {
                                $origvalue = isset($dataPID['origvalue']) ? round($dataPID['origvalue'] * 1) : $dataPID[$pkey];
                                $checked = isset($dataPID['checked']) && $dataPID['checked'] == 1 ? "checked" : "";
                                $disabled = $checked == "checked" ? "disabled" : "disabled";
                                $backgroundColor = $checked == "checked" ? "bg-grey" : "bg-red";
                                $listProduk .= "<td pkey='$pkey' class='text-auto text-bold text-right no-padding'>";
                                $listProduk .= "<div class='funkyradio-success'>";
                                $listProduk .= "
                                    <div class='input-groupx border-cekx'>
                                        <input it4_subtotal='" . $dataPID['subtotal'] . "' placeholder='$pkey' it4pid='$pids' it4key='$pkey' sesVal='" . number_format($dataPID[$pkey]) . "' origvalue='" . $origvalue . "' id='in_$pids$pkey' title='!!! centang box untuk edit !!!' onclick='this.select();' size='1' $disabled value='" . number_format($dataPID[$pkey]) . "' type='text' class='in_koreksi_$pkey no-border bg-olive form-control $backgroundColor text-right no-padding'>
                                    </div>
                              ";
                                $listProduk .= "</div>";
                                $listProduk .= "</td>";
                            }
                            else {
                                if ($pkey == "ppnFactor_item") {
                                    $iID = $dataPID['id'];
                                    $ppnFactor_item = $dataPID['ppnFactor_item'];
                                    $pilihan = array(
                                        0 => "0",
                                        11 => "11%",
                                        12 => "12%",
                                    );
                                    $keField = $iID . "_ppn";
                                    $listProduk .= "<td pkey='$pkey' class='text-bold'>";
                                    $listProduk .= "<div class='wwrapper-radio' style='margin-top: 0px;'>";
                                    // arrPrintHijau(($pilihan));
                                    // arrPrint(reset($pilihan));
                                    // arrPrint(array_keys($pilihan));

                                    $jmlPilihan = count($pilihan);
                                    $countP = 0;
                                    foreach ($pilihan as $ky => $data) {
                                        $countP++;
                                        $checked = $ppnFactor_item == $ky ? "checked" : "";
                                        if ($countP == 1) {
                                            $id_toggle = "toggle-$ky-$keField";
                                            $class_toggle = "toggle-left";
                                            $radius = "border-radius: 5px 0 0 5px;";
                                        }
                                        elseif ($countP == $jmlPilihan) {
                                            $id_toggle = "toggle-$ky-$keField";
                                            $class_toggle = "toggle-right";
                                            $radius = "border-radius: 0 5px 5px 0;";
                                        }
                                        else {
                                            $id_toggle = "toggle-$ky-$keField";
                                            $class_toggle = "toggle-center";
                                            $radius = "";
                                        }

                                        $listProduk .= "<input id='$id_toggle' $checked class='toggle-radio $class_toggle' data-pid='$iID' mid='$iID' vl='$ky' ct='$no' name='$keField' value='$ky' type='radio'>
                                            <label for='$id_toggle' class='btn-radio' style='$radius'>$data</label>";
                                    }
                                    $listProduk .= "</div>";
                                    $listProduk .= "</td>";
                                }
                                else{
                                    $listProduk .= "<td pkey='$pkey' class='text-bold'>" . formatField($pkey, $dataPID[$pkey]) . "</td>";
                                }
                            }
                        }
                    }

                }
                $subtotal += $dataPID["subtotal"];
                $ppn += $dataPID["ppn"];
            }
//            $ppn = $subtotal*0.11;
            $total = $ppn + $subtotal;

            $listProduk .= "</tr>";
            $listProduk .= "<tfoot class='bg-gray'>";
            $listProduk .= "<tr>";
            $listProduk .= "<th colspan='' style='font-size: 16px;padding-right: 40px;' class='text-bold text-meta text-right'>-</th>";
            foreach ($items4Label as $pkey => $p_label) {
                $listProduk .= "<th colspan='' style='font-size: 16px;padding-right: 40px;' class='text-bold text-meta text-right'></th>";
            }

            $listProduk .= "</tr>";
            $listProduk .= "</tfoot>";
//            $listProduk .= "<tr>";
//            $listProduk .= "<td colspan='$colspan' class='text-right'>total PPN</td>";
//            $listProduk .= "<td colspan=''>" . formatField("subtotal", $ppn) . "</td>";
//            $listProduk .= "</tr>";
//            $listProduk .="<tr>";
//            $listProduk .="<td colspan='$colspan' class='text-right'>Total</td>";
//            $listProduk .="<td colspan=''>".formatField("total",$total)."</td>";
//            $listProduk .="</tr>";
            $listProduk .= "</table>";
            $listProduk .= "<div id='wr_koreksi'></div>";
            $listProduk .= "</div>";

            $listProduk .= "</div>";
            $listProduk .= "<script>
                if( localStorage.last_global_diskon != 'undefined'){
                    $('#global_disc').val(localStorage.last_global_diskon)
                }

                $('.in_koreksi_add_disc_rupiah').off();
                $('.in_koreksi_add_disc_rupiah').on('keyup', delay_v2(function(event){
                    var formID = $(this).attr('id');
                    var pkey = $(this).attr('it4key');
                    $(this).attr('nilaiKeyup', removeCommas($(this).val()));
                    var nilaiDiskonGlobal = removeCommas($('#global_disc').val())*1
                    var tablesProduk = $('#rincian_penerimaan_produk tbody tr');
                    jQuery.each(tablesProduk, function(a, b){
                        var sourcepKey = $('td[pkey='+pkey+'] input', $(this)).attr('id');
                        if(sourcepKey==formID){
                            console.log('sourcepKey: '+ sourcepKey);
                            console.log('formID: '+ formID);
                        }
                        else{
                            console.error('sourcepKey: '+ sourcepKey);
                        }
                    });
                }, 250));

                $('#global_disc').on('keyup', delay_v2(function(a){
                    console.log( a.keyCode );
                    if( a.keyCode == 13){
                    //detect enter
                        $('#diskon_terapkan').click()
                    }
                }, 1000))

                var ses_nilaiTagihanNonPPN = 0;
                var ses_nilaiDiskonNonPPN = 0;
                $('#diskon_terapkan').on('click', delay_v2(function(){

                    var thisssss = $('#global_disc')
                    var additional_diskon = $(thisssss).val();
                    
                    var url = '$additionalDiskonRecorderTarget/diskon_global?val='+removeCommas(additional_diskon)
                    $.ajax(url).always(function(data) {
                    });
                    
                    

                    localStorage.last_global_diskon = $(thisssss).val();

//                    console.log('total_harga_produk_nppn: '+ total_harga_produk_nppn);
//                    console.log('total_diskon: '+ total_diskon);

                    localStorage.shoppingcartNoReload = 1

                    var tablesProduk = $('#rincian_penerimaan_produk tbody tr');
                    var totalOri = 0;
                    var totalAfterDiskon = 0;
                    var nilaiTagihan = 0;
                    var nilaiTagihanNonPPN = 0;

                    jQuery.each(tablesProduk, function(a, b){
                        var _hrgNow = removeCommas($('td[pkey=harga] span', $(b)).html());
                        var _qtyNow = removeCommas($('td[pkey=jml] span', $(b)).html());
                        var _totalNonPPn = (_hrgNow*1) * (_qtyNow*1);
                        nilaiTagihan += _totalNonPPn*1.11;
                        nilaiTagihanNonPPN += _totalNonPPn;
                        console.log('nilaiKeyup disc persen: ' + $('td[pkey=add_disc_persen] input', $(b)).attr('nilaiKeyup'))
                    });

                    ses_nilaiTagihanNonPPN = nilaiTagihanNonPPN;
                    console.error('SET ses_nilaiTagihanNonPPN: ' + ses_nilaiTagihanNonPPN);

//                    var total_harga_produk_nppn = $('#total_harga_produk_nppn').attr('value');
                    var total_harga_produk_nppn = nilaiTagihan;
                    var nilaiDiskonGlobal = removeCommas($(thisssss).val());
                    var tmp_total_diskon = (nilaiDiskonGlobal*1) / (total_harga_produk_nppn*1);
                    var total_diskon = tmp_total_diskon;

                    var whenMethod = '';
                    var totalProporsi = 0;
                    var totalDiskonItems = 0;

                    jQuery.each(tablesProduk, function(a, b){
                        var hrgNow = removeCommas($('td[pkey=harga] span', $(b)).html());
                        var qtyNow = removeCommas($('td[pkey=jml] span', $(b)).html());
                        var totalNonPPn = (hrgNow*1) * (qtyNow*1);
                        var afterDiskon = totalNonPPn - (totalNonPPn*total_diskon);
                        var nominDiskon = totalNonPPn-afterDiskon;
                        var proporsiItems = (totalNonPPn/nilaiTagihanNonPPN)*100;
                        var nilaiGlobalDiskon = removeCommas($(thisssss).val())*1;

                        totalProporsi += proporsiItems*1;
                        totalDiskonItems += nominDiskon*1;
                        ses_nilaiDiskonNonPPN += nominDiskon.toFixed(0)*1;

                        $('td[pkey=add_disc_persen] input', $(b)).val(proporsiItems.toFixed(2))
                        $('td[pkey=add_disc_persen] input', $(b)).attr('nilaiAuto', proporsiItems.toFixed(2))
                        $('td[pkey=add_disc_rupiah] input', $(b)).val( addCommas(nominDiskon.toFixed(0)) )
                        $('td[pkey=add_disc_rupiah] input', $(b)).attr('nilaiAuto', addCommas(nominDiskon.toFixed(0)) )

                        totalOri += totalNonPPn;
                        totalAfterDiskon += afterDiskon;
                        var rowPosisi = tablesProduk.length;
//                        console.log('a: ' + a +' || rowPosisi: '+(rowPosisi-1) );
                        if(a == rowPosisi - 1){
                            localStorage.shoppingcartNoReload = 0
                        }
//                        console.log('shoppingcartNoReload: ' + localStorage.shoppingcartNoReload);
                        $('td[pkey=subtotal] span input', $(b)).prop('checked', true)
                        $('td[pkey=subtotal] input', $(b)).val(addCommas( afterDiskon.toFixed(0) )).trigger('kirim_via_jne');


//                        console.log( 'totalNonPPn: ' + totalNonPPn + ' ||  afterDiskon: '+afterDiskon );


                    });

                    var footer = $('#rincian_penerimaan_produk tfoot tr th');

                    jQuery.each(footer, function(a, b){

//                        console.log('==========FOOTER=============');
//                        console.log(a);
//                        console.log(b);
                        if(a==6){
                            $(b).html(addCommas(totalProporsi.toFixed(0))).css('padding-right', 0)
                        }
                        if(a==7){
                            var b7 = ''
                            b7 += '<div>'+addCommas(totalDiskonItems.toFixed(0))+'</div>'
                            b7 += '<div><span class=pull-left>+PPN 11%</span><span class=pull-right>'+addCommas( (totalDiskonItems*1.11).toFixed(0))+'</span></div>'
                            $(b).html(b7).css('padding-right', 0)
                        }

                        if(a==9){
                            var b9 = ''
                            b9 += '<div>'+addCommas(totalAfterDiskon.toFixed(0))+'</div>'
                            b9 += '<div><span class=pull-left>+PPN 11%</span><span class=pull-right>'+addCommas( (totalAfterDiskon*1.11).toFixed(0))+'</span></div>'
                            $(b).html(b9)
                        }
                    })

//                    console.log( 'totalOri: ' + totalOri + ' ||  totalAfterDiskon: '+totalAfterDiskon );
                    console.log( 'totalProporsi: ' + totalProporsi );

                }, 200))

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
                    $.ajax(url)
                    .always(function(data) {
                        var shoppingcartNoReload = localStorage.shoppingcartNoReload;
                        if( shoppingcartNoReload!= 'undefined' && shoppingcartNoReload == 1 ){
                            console.log('tidak di reload ')
                        }
                        else{
                            top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID='+pid, function(){
                                //document.getElementById('overlay').style.display = 'none';
                            });
                        }
                    });
                }, 1200));

                $('.in_koreksi_add_disc_rupiah').keyup(delay_v2(function(){
                    var pid = $(this).attr('it4pid');
                    var key = $(this).attr('it4key');
                    var check = 1
                    var url = '$koreksiRecorderTarget/'+key+'/'+pid+'?val='+removeCommas(this.value)+'&check='+check
                    $.ajax(url)
                    .always(function(data) {
                        top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID='+pid, function(){
                            //document.getElementById('overlay').style.display = 'none';
                        });
                    });
                }, 1200));

                $('.in_koreksi').on('kirim_via_jne', function(){
                    var pid = $(this).attr('it4pid');
                    var key = $(this).attr('it4key');

                    var check = 0
                    if( $('#det_'+pid+''+key).prop('checked') ){
                        check = 1
                    }

                    var totalNonPPn = removeCommas(this.value)*1;
                    ses_nilaiTagihanNonPPN = ses_nilaiTagihanNonPPN-totalNonPPn;
                    // console.log('===================>>>>>>>>>>>>>>>> NILAI ses_nilaiTagihanNonPPN: ' + ses_nilaiTagihanNonPPN + ' - totalNonPPn: ' + totalNonPPn + '  === '+ ses_nilaiTagihanNonPPN)

                    var url = '$koreksiRecorderTarget/'+key+'/'+pid+'?val='+removeCommas(this.value)+'&check='+check

                    // console.error(ses_nilaiTagihanNonPPN + '-' + ses_nilaiDiskonNonPPN);
                    // console.error('===' + (ses_nilaiTagihanNonPPN-ses_nilaiDiskonNonPPN) );

                    if( ((ses_nilaiTagihanNonPPN*1) - (ses_nilaiDiskonNonPPN*1)).toFixed(0) == 0){
                        $.ajax(url)
                        .done(function(data) {
                             top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID='+pid, function(){
                                 //document.getElementById('overlay').style.display = 'none';
                             });

                        });
                    }
                    else{
                        $.ajax(url)
                        .done(function(data) {
                            //console.error('ses_nilaiTagihanNonPPN - ses_nilaiDiskonNonPPN || ' + ((ses_nilaiTagihanNonPPN*1) - (ses_nilaiDiskonNonPPN*1)) );
                        });
                    }

                });

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
                        
//                        swal({type: 'warning',title: 'Upss..',html: 'Berilah tic pada setiap nilai, sebagai konfirmasi nilainya sudah benar'});
//                         swal({type: 'warning',title: 'Upss..',html: 'Cek pada nilai barang, bila sudah sesuai silahkan cukup berikan tic, bila belum sesuai silahkan dikoreksi.'});

                    }
                    
                    // console.log('konfirmasi_cek 297:', konfirmasi_cek);
                </script>";

            $listProduk .= "<script>
                $('#global_disc').keyup(function(){
                    this.value = addCommas(this.value)
                });
                $('#global_disc_tic').change(function() {
                    if ($(this).is(':checked')) {
                        // Checkbox is checked
                        var value = removeCommas($('#global_disc').val());
                        console.log('Checkbox checked. Value:', value);
                        // Add your code here to handle the checked state
                        // add_global_disc(value);
                    }
                    else {
                        // Checkbox is unchecked
                        console.log('Checkbox unchecked.');
                        // Add your code here to handle the unchecked state
                        // remove_global_disc();
                    }
                });


            </script>";


            $link_selector = $link_selector_ppn;
            $listProduk .= "<script>
            let previousValues = {};
            $('input[type=\"radio\"].toggle-radio:checked').each(function () {
                const groupName = $(this).attr('name'); // Ambil nama grup
                previousValues[groupName] = $(this).val(); // Simpan nilai awal
            });
            
            $('input[type=\"radio\"].toggle-radio').on('change', function() {
                    console.log('kehed');
                    // Ambil ID dan nilai dari radio button yang dipilih
                    const selectedVal = Number($(this).attr('vl'));
                    const selectedMid = $(this).attr('mid');
                    const selectedName = $(this).attr('name');
                    const selectedId = $(this).attr('id');
                    const ct = $(this).attr('ct');
                    const selectedValue = $(this).val();
                    const newQty = $('#jml_' + ct).val();
                    const labelText = $(this).next('label').text();
                    let labelTextLc = labelText.toLowerCase();                              
                   console.log('selectedVal:', selectedVal);
                   
                   const previousValue = previousValues[selectedName]; // Nilai sebelumnya
                   console.log('previousValue:', previousValue);
                   
                   
                    swal({
                        title: 'Konfirmasi Pilihan',
                        text: `Anda memilih Tarif PPN ` + labelText + `. Apakah Anda yakin?`,
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, lanjutkan',
                        cancelButtonText: 'Batal'
                    }).then(
                        function(result) {
                            console.log('Aksi diterima:', result);
                            // if (selectedId === 'toggle-' + selectedVal + '-' + selectedName) {
                            console.log( labelText + ' dipilih pid= ' + selectedMid + 'ke $link_selector');
                            console.log('newQty:', newQty);
                            // top.$('#result').src('$link_selector' + selectedMid + '&ppn=' + selectedVal);
                            top.$('#result').load('$link_selector' + selectedMid + '&val=' + selectedVal + '&key=ppnFactor_item');
                            // }
                            // else if (selectedId === 'toggle-' + selectedVal + '-' + selectedName) {
                            //     console.log(labelText + ' dipilih');     
                            // }
                        },
                        function(dismiss) {
                            console.log('Aksi dibatalkan:', dismiss);
                            // console.log('Aksi dibatalkan:', selectedVal);                            
                            $('#toggle-' + previousValue + '-' + selectedName).prop('checked', true);
                            
                        }
                    );
                   
                });
        </script>";

        }
        else {
            $listProduk .= "<script>
                    var konfirmasi_cek = false;
                     </script>";
        }
        echo $listProduk;


        //-------------------------------------------------------------------------------
        if (sizeof($shopingCartPaymentItemsColor) > 0) {
            $legend = "";
            foreach ($shopingCartPaymentItemsColor['colorCode'] as $ix => $ixSpec) {
                $bgcolor = $ixSpec["color"];
                $legend .= "<span class='btn btn-sm' style='background-color:$bgcolor;'> </span> " . $ixSpec['label'] . "&nbsp;&nbsp;&nbsp;&nbsp;";
            }
            echo $legend;
        }
        //-------------------------------------------------------------------------------


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

                //------
                if (isset($iSpec['background_pembayaran'])) {
                    $bgColor = $iSpec['background_pembayaran'];
                }
                //------

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
                        if (isset($shoppingCartEditableFieldsType[$key])) {
                            $queryParams .= "&$key='+(document.getElementById('" . $colID[$key] . "').value)+'";
                        }
                        else {
                            $queryParams .= "&$key='+removeCommas(document.getElementById('" . $colID[$key] . "').value)+'";
                        }
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
                            if (isset($shoppingCartEditableFieldsType[$key])) {
//                                cekHere($queryParams);
                                $tipe_input = $shoppingCartEditableFieldsType[$key];
                                $max = "";
                                if ($tipe_input == "date") {
                                    $max = "max='" . dtimeNow("Y-m-d") . "'";
                                }
                                $niceValue = $fieldVal;
                                echo "<input type='$tipe_input' $max min='$moq' autocomplete='off' " . $readOnly[$key] . " keyid=$keyID noid=$noID id_jml=$iID id=$colID  class='form-control text-right' style='color:$color;' value='" . $niceValue . "' onclick='this.select()' " . $keyUpStr[$key] . " ";
                                $baseInputName = isset($unionSelectors['base']) ? "document.getElementById('" . $unionSelectors['base'] . "_" . $no . "')" : "this";
                                $pemicuGerbangAsli = "onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';} \" ";

                                $pemicuGerbang = "onblur=\"if($baseInputName.value!=$baseInputName.defaultValue){hiliteDiv($baseInputName);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';}\"  ";

                                $pemicuGerbangUnion = "onchange=\"if($baseInputName.value!=$baseInputName.defaultValue){hiliteDiv($baseInputName);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';} \" ";
                                if (isset($unionSelectors['base'])) {
                                    if ($unionSelectors['base'] == $key) {//==jadi acuan kiriman
                                        echo str_replace("this", $baseInputName, $pemicuGerbang);
                                    }
                                    else {
                                        if (in_array($key, $unionSelectors['members'])) {
                                            //==jadi member union, tidak memicu perubahan gerbang
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
                                    echo "onkeyup=\"document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';\"";
                                }
                                else {
                                    echo "onkeyup=\"delay( function(){ $('#shopping_cart').trigger('change') }, 200, this );\"";
                                }
                                echo ">";
                            }
                            else {
                                $tipe_input = "text";
                                $niceValue = niceDecimal($fieldVal);
                                echo "<input type='$tipe_input'  min='$moq' autocomplete='off' " . $readOnly[$key] . " keyid=$keyID noid=$noID id_jml=$iID id=$colID  class='form-control text-right' style='color:$color;' value='" . $niceValue . "' onclick='this.select()' " . $keyUpStr[$key] . " ";
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
                                        if (in_array($key, $unionSelectors['members'])) {
                                            //==jadi member union, tidak memicu perubahan gerbang
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
                                    echo "onkeyup=\"delay( function(){ $('#shopping_cart').trigger('change') }, 200, this );if(parseFloat(removeCommas(this.value))>0){ this.value=addCommas(this.value) }else{ this.value=0 }\"";
                                }
                                echo ">";
                            }

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
                    echo "<a class='text-black btn btn-warning btn-sm' title='remove this item' data-toggle='tooltip' data-placement='left' 
                    onclick=\"document.getElementById('result').src='" . $iSpec['removeTarget'] . "';\">
                    <span class='glyphicon glyphicon-remove'></span>
                    </a>";
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
                if (isset($shopingCartPairSubItemSrc) && (sizeof($shopingCartPairSubItemSrc) > 0)) {
                    $c_colspan = 3;
                    echo "<tr>";
                    echo "<td>&nbsp;</td>";
                    echo "<td colspan='" . $c_colspan . "'>";


                    echo "<div class='panel no-margin' sstyle='border:1px solid red;'>"; // anakan table
                    echo "<table class='table table-condensed table-striped no-padding no-border'>";

                    echo "<tr>";
                    echo "<td class='text-muted bg-grey-1 text-center'>";
                    echo "No";
                    echo "</td>";
                    foreach ($shopingCartPairSubItemSrc as $key => $label) {
                        echo "<td class='text-muted bg-grey-1 text-center text-capitalize'>";
                        echo $label;
                        echo "</td>";
                    }
                    echo "</tr>";

                    $totalBawah = array();
                    $no = 0;
                    foreach ($shopingCartPairSubItemSrcData[$iID] as $iSubSpec) {
                        $no++;
                        echo "<td>$no</td>";
                        foreach ($shopingCartPairSubItemSrc as $key => $label) {
                            echo "<td>";
                            echo formatField_he_format($key, $iSubSpec[$key]);
                            echo "</td>";
                            if (is_numeric($iSubSpec[$key])) {
                                if (!isset($totalBawah[$key])) {
                                    $totalBawah[$key] = 0;
                                }
                                $totalBawah[$key] += $iSubSpec[$key];
                            }
                        }
                    }

                    echo "<tr>";
                    echo "<td></td>";
                    foreach ($shopingCartPairSubItemSrc as $key => $label) {
                        echo "<td>";
                        echo formatField_he_format($key, $totalBawah[$key]);
                        echo "</td>";
                    }
                    echo "</tr>";

                    echo "</table class='table table-condensed table-striped no-padding no-border'>";
                    echo "</div class='panel no-margin' sstyle='border:1px solid red;'>"; // anakan table

                    echo "</td>";
                    echo "</tr>";
                }

            }


        }

        //region items2, kalau salah satunya untuk produksi dan konversi
        if (isset($items2) && sizeof($items2) > 0) {

            if ($shoppingCartAdvanceItems == true) {
                echo "<tr class='bg-info'>";
                echo "<td colspan='$jmlKolomHeader'>";

                echo "<div class='panel no-margin' sstyle='border:1px solid red;'>"; // anakan table
                echo "<table class='table table-condensed table-striped no-padding no-border'>";

                $no = 0;
                //region body table anakan
                $kurangStoks = array();
                foreach ($items2 as $iSpec) {
//                    arrPrintWebs($iSpec);
                    $trid = $main['refID'];
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
//
                                echo "<tr>";
                                echo "<td>&nbsp;</td>";
                                echo "<td colspan='" . $colspan . "'>";

                                //region tabel SUB_ITEMS
                                echo "<table class='table table-condensed no-padding no-border no-margin'>";
                                //--header sub items
                                echo "<tr class='bg-grey-1 text-uppercase'>";
                                foreach ($subItemLabels as $key => $label) {
                                    echo "<th style='width:1%;white-space: nowrap;' class='text-muted text-center'>";
                                    if (is_array($label)) {
                                        echo $label["label"];
                                    }
                                    else {
                                        echo $label;
                                    }
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
                                            $thisValue = "?trid=$trid&val='+removeCommas(this.value)";
                                            $thisChecked = "?trid=$trid&state='+this.checked";
                                            if ($subValue == NULL) {
                                                $subValue = 0;
                                            }
                                        }
                                        else {
                                            $input_type = "text";
                                            $text_align = "text-left";
                                            $thisValue = "?trid=$trid&val='+encodeURIComponent(this.value)";
                                            $thisChecked = "?trid=$trid&state='+this.checked";
                                        }

                                        $subcolID = $key . "_" . $iID . "_" . $subNo;
                                        $linkSubEditable = $shoppingCartAdvanceItemsSelector . "/$iID/$key/$subNo";
                                        echo "<td style='width:1%;white-space: nowrap;' class='text-muted'>";
                                        if (in_array($key, $editableField)) {
                                            echo "<div class=\"funkyradio-success\">";
                                            echo "<div class=\"input-group border-cekx\">";

                                            echo "<input type='$input_type' id='$subcolID' class='form-control bg-olive $text_align' $disabled value='$subValue'";
                                            echo " onclick='this.select()' ";
                                            echo " onblur=\"document.getElementById('result').src='" . $linkSubEditable . "$thisValue\"";
                                            echo " >";

                                            if (isset($shoppingCartAdvanceSubEditableAdditional[$key])) {
                                                $form_tipe = $shoppingCartAdvanceSubEditableAdditional[$key]["tipe"];
                                                $form_link = $shoppingCartAdvanceSubEditableAdditionalSelector . "/$iID/$key/$subNo";
                                                $checked = (isset($iiSpec[$key . '_checklist']) && ($iiSpec[$key . '_checklist'] == 1)) ? "checked" : "";
                                                echo "<span class=\"input-group-addon\">";
                                                echo "<input name='$key' $checked id='$key' class='bg-olive' onclick=\"document.getElementById('result').src='" . $form_link . "$thisChecked\" type='$form_tipe'>";
                                                echo "</span class=\"input-group-addon\">";
                                            }

                                            echo "</div class=\"input-group border-cekx\">";
                                            echo "</div class=\"funkyradio-success\">";
                                        }
                                        else {
                                            echo formatField_he_format($key, $subValue, "", "");
                                        }
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

                echo "</table>";
                echo "</div>"; // anakan table


                echo "</td>";
                echo "</tr>";
            }
            else {

                if (sizeof($itemLabels2) && (is_array($itemLabels2)) && $showItems) {
                    echo "<tr class='bg-info'>";
                    echo "<td colspan='$jmlKolomHeader'>";

                    echo "<div class='panel no-margin' sstyle='border:1px solid red;'>"; // anakan table
                    echo "<table class='table table-condensed table-striped no-padding no-border'>";

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


                    echo "</table>";
                    echo "</div>"; // anakan table


                    echo "</td>";
                    echo "</tr>";
                }
            }

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
//                cekHere("$label");
                /* --------------------------------------------------------------
                 * replacerLabel
                 * --------------------------------------------------------------*/
                $label_f = str_replace("{ppnFactor}", $ppnFactor, $label);
                // --------------------------------------------------------------

                $nr++;
                $bottom_borderless = $nr < sizeof($addRowLabels) ? "bottom-borderless" : "";
//                arrPrint($addRowHiddens[$k]);
                $rowHide = isset($addRowHiddens[$k]) ? $addRowHiddens[$k] : "tidak_hidden";
                echo "<tr class='$rowHide'>";
                echo "<td colspan='" . sizeof($itemLabels) . "' id='label_$k' class='text-right $bottom_borderless valign-m text-uppercase'>$label_f</td>";
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
            echo "<script>                                                          
                        function labelMencolok(key) {
                            var saldotext = $('#saldo_' + key).text();
                            var num_saldotext = Number(saldotext.replace(/\./g, ''));
                            if(num_saldotext > 0){
                                $('#label_' + key).addClass('text-red text-bold');
                            }
                        }
                        
                        var labelKeis = ['credit_note_diskon', 'credit_note_dipakai', 'uang_muka_nonrelasi_dipakai','uang_muka_dipakai', 'uang_muka_dipakai_ppn'];                        
                        labelKeis.forEach(function(item) {                        
                            labelMencolok(item);   
                        });
                        
                        labelKeis.forEach(function(item) {
                            $('#' + item).on('blur', function() {
                                let ketikan = $('#'+ item).val();
                                let saldotext = $('#saldo_' + item).text();
                                let num_saldotext = Number(saldotext.replace(/\./g, ''));
                                
                                console.log('cek', ketikan);
                                console.log('saldotext', num_saldotext);
                                if(ketikan > num_saldotext){
                                    swal({
                                            title: 'peringatan.. !!',
                                            html: 'maximal diskon ' + addCommas(max_diskon_nilai) + ', sekarang ' + addCommas(diskon_nilai) + ' dari ' + addCommas(dpp)
                                        });
                                }
                            });
                        });
                        
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
            if (isset($showFormulirFaktur) && ($showFormulirFaktur == true)) {
                
                $isFormulirPredefined = false;
                if (sizeof($formulirFaktur) > 0) {
                    foreach ($formulirFaktur as $checkSpec) {
                        if (isset($checkSpec['is_predefined_faktur']) && intval($checkSpec['is_predefined_faktur']) == 1) {
                            $isFormulirPredefined = true;
                            break;
                        }
                    }
                }

                $headerBadge2 = "";
                if ($isFormulirPredefined) {
                    $headerBadge2 = "<span class='pull-right label label-success' style='font-size: 12px; padding: 4px 10px; font-weight: bold;'><i class='fa fa-check-circle'></i> Faktur Sudah Diterima</span>";
                }

                $faktur .= "<div class='panel panel-primary' style='margin-top: 25px; margin-bottom: 25px; border: 2px solid #337ab7; box-shadow: 0 2px 5px rgba(0,0,0,0.08);'>";
                $faktur .= "<div class='panel-heading' style='font-weight: bold; font-size: 14px;'><i class='fa fa-file-text-o'></i> PEREKAMAN E-FAKTUR PPN MASUKAN SUPPLIER $headerBadge2</div>";
                $faktur .= "<table class='table' style='margin-bottom: 0;'>";
                $faktur .= "<tr class='bg-primary'>";
                foreach ($shopingCartFakturParam["fields"] as $ff => $ff_abels) {
                    $faktur .= "<th>$ff_abels</th>";
                }
                $faktur .= "</tr>";

                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    //-----------------------------
                    $faktur .= "<tr>";
                    $linkFaktur = MODUL_PATH . $shopingCartFakturTarget . "/";
                    foreach ($shopingCartFakturParam["fields"] as $fff => $f_labels) {
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
                    //-----------------------------
                }
                else {
                    if (sizeof($formulirFaktur) > 0) {
                        $countItems = sizeof($items);
                        if ($countItems == 1) {
                            unset($shopingCartFakturParam["editableFields"]["dpp_final"]);
                        }
                        foreach ($formulirFaktur as $ctt => $fSpec) {
                            $faktur .= "<tr>";
                            foreach ($shopingCartFakturParam["fields"] as $fff => $f_labels) {
                                $linkFaktur = MODUL_PATH . $shopingCartFakturTarget . "/";
                                $labels = "";
                                $btn_formulir = "";
                                $btn_formulir_delete = "";
                                if (isset($shopingCartFakturParam["editableFields"][$fff])) {
                                    $inputType = $shopingCartFakturParam["editableFields"][$fff];
                                    $defValues = isset($fSpec[$fff]) ? $fSpec[$fff] : "";
                                    $checkEfVal = isset($fSpec['eFaktur']) ? trim($fSpec['eFaktur']) : "";
                                    $checkTglVal = isset($fSpec['dateFaktur']) ? trim($fSpec['dateFaktur']) : "";
                                    $hasEFaktur = (strlen($checkEfVal) >= 10 && substr($checkEfVal, 0, 1) === "0");
                                    $hasDateFaktur = (strlen($checkTglVal) > 6 && $checkTglVal != "0000-00-00");
                                    $isPredefinedFaktur = (isset($fSpec['is_predefined_faktur']) && intval($fSpec['is_predefined_faktur']) == 1);

                                    if ($shopingCartFakturParam["editableFields"][$fff] == "checkbox") {
                                        $classinputType = "";
                                        $labels = ($ctt == 0) ? "tic disini jika faktur belum tersedia" : "";
                                        $vals = "checked";
                                        $checked = isset($fSpec[$fff]) && $fSpec[$fff] == "true" ? $vals : "";
                                        $disabledCheckbox = ($isPredefinedFaktur) ? "disabled title='Faktur sudah tersedia dari transaksi asal'" : "";
                                        $value = ($ctt == 0) ? "<input type='$inputType' id='$fff' class='$classinputType' name='$fff' onclick='this.select()' value='$defValues' $checked $disabledCheckbox onblur=\"eksekutor(this.$vals,this.name,$ctt)\">" : "";
                                        $btn_formulir = (($ctt == 0) && ($countItems > 1)) ? "<button class='btn btn-warning' onclick=\"$('#result').load('$cloneFormulirFaktur');\"><span class='glyphicon glyphicon-plus'></span> Tambah Formulir Faktur</button>" : "";
                                        $btn_formulir_delete = ($ctt > 0) ? "<button class='btn btn-danger' onclick=\"$('#result').load('$cloneFormulirFakturDelete/$ctt');\"><span class='glyphicon glyphicon-trash'></span></button>" : "";
                                    }
                                    else {
                                        $classinputType = "form-control ";
                                        $labels = "";
                                        $vals = "value";
                                        $checked = "";
                                        $readonlyAttr = ($isPredefinedFaktur && strlen(trim($defValues)) > 0) ? "readonly style='background-color:#eef2f7; cursor:not-allowed;' title='Terkunci: Terisi dari data transaksi asal'" : "";
                                        $value = "<input type='$inputType' id='$fff' class='$classinputType' name='$fff' onclick='this.select()' value='$defValues' $checked $readonlyAttr onblur=\"eksekutor(this.$vals,this.name,$ctt)\">";
                                        if ($fff == 'eFaktur') {
                                            if ($isPredefinedFaktur) {
                                                $btnFaktur = "<button type='button' class='btn btn-success' disabled style='font-weight:bold;' title='E-Faktur sudah tersedia dari transaksi asal'><i class='fa fa-check-circle'></i> E-FAKTUR TERSEDIA</button>";
                                            } else {
                                                $btnFaktur = "<button type='button' class='btn btn-primary' style='font-weight:bold;' onclick='simpanEfakturSaja()'><i class='fa fa-save'></i> SIMPAN E-FAKTUR SAJA</button>";
                                            }
                                            $value = "<div class='input-group'>" . $value . "<span class='input-group-btn'>" . $btnFaktur . "</span></div>";
                                        }
                                    }
                                }
                                else {
                                    $value = formatField($fff, $fSpec[$fff]);
                                }

                                $faktur .= "<td id='td_$fff'>$value <span class='text-danger text-bold text-blink'> $labels </span>";
                                $faktur .= $btn_formulir;
                                $faktur .= $btn_formulir_delete;
                                $faktur .= "<script>
                                        function eksekutor(nilai,nama,ctt) {
                                            $('#result').load('$linkFaktur'+ctt+'?nilai='+nilai+'&nama='+nama)
                                        }
                                        </script>";
                                $faktur .= "</td>";

                                if (is_numeric($fSpec[$fff])) {
                                    if (!isset($sub_total_bawah[$fff])) {
                                        $sub_total_bawah[$fff] = 0;
                                    }
                                    $sub_total_bawah[$fff] += $fSpec[$fff];
                                }
                            }
                            $faktur .= "</tr>";
                        }
                        if (sizeof($formulirFaktur) > 1) {
                            $bgcolor = isset($formulirFakturStyle["bgcolor"]) ? $formulirFakturStyle["bgcolor"] : "";
                            $faktur .= "<tr style='background-color:$bgcolor;'>";
                            foreach ($shopingCartFakturParam["fields"] as $fff => $f_labels) {
                                $value = isset($sub_total_bawah[$fff]) ? formatField($fff, $sub_total_bawah[$fff]) : "";
                                $faktur .= "<td id='td_$fff' class='text-bold' style='font-size:15px;'>$value";
                                $faktur .= "</td>";
                            }
                            $faktur .= "</tr>";
                        }
                    }
                }

                $faktur .= "</table>";
                
                if ($isFormulirPredefined) {
                    $faktur .= "<div style='padding: 10px 15px; background-color: #dff0d8; border-top: 1px solid #d6e9c6; color: #3c763d; font-size: 12px; font-weight: 500;'>";
                    $faktur .= "<i class='fa fa-check-circle text-success'></i> <b>Status Faktur:</b> Faktur PPN Masukan telah diterima.";
                    $faktur .= "</div>";
                } else {
                    $faktur .= "<div style='padding: 10px 15px; background-color: #f8fafc; border-top: 1px solid #e2e8f0; color: #31708f; font-size: 12px; font-weight: 500;'>";
                    $faktur .= "<i class='fa fa-info-circle text-primary'></i> <b>Informasi:</b> <i>'Simpan E-Faktur Saja'</i> memicu Jurnal PPN Masukan <code>(D) PPN Masukan</code> vs <code>(K) Hutang Pajak</code> tanpa mengeluarkan Kas/Bank (Rp 0).";
                    $faktur .= "</div>";
                }
                
                $faktur .= "<div id='wr_skip_efakture'></div>";
                $faktur .= "</div>";
                
                $faktur .= "<script>
                                window.simpanEfakturSaja = function() {
                                    var dateFaktur = $('#dateFaktur').length > 0 ? $('#dateFaktur').val() : ($('[name=\"dateFaktur\"]').length > 0 ? $('[name=\"dateFaktur\"]').val() : '');
                                    var eFaktur = $('#eFaktur').length > 0 ? $('#eFaktur').val() : ($('[name=\"eFaktur\"]').length > 0 ? $('[name=\"eFaktur\"]').val() : '');
                                    var skip_faktur = ($('#skip_faktur').length > 0 && $('#skip_faktur').prop('checked')) || ($('[name=\"skip_faktur\"]').length > 0 && $('[name=\"skip_faktur\"]').prop('checked')) ? true : false;
                                    if (skip_faktur == false && (dateFaktur == '' || eFaktur == '')) {
                                        if (typeof swal === 'function') {
                                            swal({
                                                type: 'warning',
                                                title: 'E-Faktur Belum Lengkap',
                                                text: 'Silahkan isikan Nomor E-Faktur dan Tanggal Terbitnya, atau centang kotak jika faktur belum tersedia.'
                                            });
                                        } else {
                                            alert('Silahkan isikan Nomor E-Faktur dan Tanggal Terbitnya, atau centang kotak jika faktur belum tersedia.');
                                        }

                                        if (typeof top !== 'undefined' && typeof top.close_holdon === 'function') {
                                            top.close_holdon();
                                        } else if (typeof close_holdon === 'function') {
                                            close_holdon();
                                        }

                                        return false;
                                    }
                                    var hasSaved = false;
                                    var doSave = function() {
                                        if (hasSaved) return;
                                        hasSaved = true;
                                        $('input[name=\"cash_account\"]').prop('checked', false);
                                        $('#konfirmasi_cek').prop('disabled', false).prop('checked', true);
                                        
                                        if (typeof top !== 'undefined' && typeof top.open_holdon === 'function') {
                                            top.open_holdon();
                                        } else if (typeof open_holdon === 'function') {
                                            open_holdon();
                                        }
                                        var targetSaveFakturUrl = '" . MODUL_PATH . "Create/saveFaktur/" . $this->jenisTr . "?dateFaktur=' + encodeURIComponent(dateFaktur || '') + '&eFaktur=' + encodeURIComponent(eFaktur || '') + '&skip_faktur=' + (skip_faktur ? 'true' : 'false');
                                        console.log('MELAKUKAN AJAX GET KE:', targetSaveFakturUrl);
                                        $.ajax({
                                            url: targetSaveFakturUrl,
                                            type: 'GET',
                                            dataType: 'html',
                                            success: function(htmlResponse) {
                                                console.log('AJAX SUCCESS RESPONSE RECEIVED:', htmlResponse);
                                                $('body').append(htmlResponse);
                                            },
                                            error: function(jqXHR, textStatus, errorThrown) {
                                                alert('AJAX Error: ' + textStatus + ' - ' + errorThrown);
                                            }
                                        });
                                    };

                                    if (typeof swal === 'function') {
                                        var res = swal({
                                            title: 'Konfirmasi Simpan E-Faktur',
                                            text: 'E-Faktur akan disimpan dan PPN Masukan akan dijurnal tanpa pembayaran Kas/Bank (Rp 0). Lanjutkan?',
                                            type: 'info',
                                            showCancelButton: true,
                                            confirmButtonColor: '#3085d6',
                                            confirmButtonText: 'Ya, Simpan E-Faktur',
                                            cancelButtonText: 'Batal'
                                        }, function(isConfirm) {
                                            if (isConfirm) {
                                                doSave();
                                            }
                                        });
                                        
                                        if (res && typeof res.then === 'function') {
                                            res.then(function(result) {
                                                if (result && (result.value || result.isConfirmed || result === true)) {
                                                    doSave();
                                                }
                                            });
                                        }
                                    } else {
                                        if (confirm('E-Faktur akan disimpan dan PPN Masukan akan dijurnal tanpa pembayaran Kas/Bank (Rp 0). Lanjutkan?')) {
                                            doSave();
                                        }
                                    }
                                };

                                var skip_faktur = $('#skip_faktur').prop('checked');
                                var dateFaktur = $('#dateFaktur').val();
                                var eFaktur = $('#eFaktur').val();
                                
                                console.log('skip_faktur:', skip_faktur);
                                console.log('dateFaktur:', dateFaktur);
                                console.log('eFaktur:', eFaktur);
                                console.log('konfirmasi_cek:', konfirmasi_cek);
                
                                if(skip_faktur == false && dateFaktur == '' && eFaktur == '' && konfirmasi_cek == true){
                                    $('#td_dateFaktur_0').length > 0 ? $('#td_dateFaktur_0').append('<r>Isikan tanggal e-faktur</r>') : $('#td_dateFaktur').append('<r>Isikan tanggal e-faktur</r>');
                                    $('#td_eFaktur_0').length > 0 ? $('#td_eFaktur_0').append('<r>Isikan e-faktur</r>') : $('#td_eFaktur').append('<r>Isikan e-faktur</r>');
                                    $('#dateFaktur').css('border-color', 'red');
                                    $('#eFaktur').css('border-color', 'red');
                                    $('#konfirmasi_cek').prop('disabled', true).prop('checked', false);
                                    konfirmasi_cek = false;
                                    $('#wr_skip_efakture').html('<r>Silahkan isikan e-faktur dan tanggal terbitnya, atau tik kotak bila belum tersedia</r>');
                                }
                                if(nilai_entry > 0 && isCa == 0 && konfirmasi_cek == true){
                                    $('#elTitle_cash_account').parent().append('<r>Pilih salah satu sumber dana</r>').css('border-color', 'red').focus();
                                    $('#konfirmasi_cek').prop('disabled', true).prop('checked', false);
                                    konfirmasi_cek = false;
                                }
                                else if(nilai_entry == 0 && isCa == 0) {
                                    $('#konfirmasi_cek').prop('disabled', false).prop('checked', false);
                                }
                                $('input[name=\"cash_account\"]').change(function(){
                                    $('#konfirmasi_cek').prop('disabled', false).prop('checked', false);
                                });
                            </script>";

            }

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
        if (isset($shoppingCartNoFakturLabelInfo) && ($shoppingCartNoFakturLabelInfo != NULL)) {
            echo "<div class='alert alert-info' style='margin-top: 10px;font-size: 15px;'>";
            echo "<span>$shoppingCartNoFakturLabelInfo</span>";
//            if (isset($fixedNoteLink)) {
//                foreach ($fixedNoteLink as $fixedNoteLink_spec) {
//                    $link = isset($fixedNoteLink_spec['link']) ? $fixedNoteLink_spec['link'] : NULL;
//                    $labels = isset($fixedNoteLink_spec['label']) ? $fixedNoteLink_spec['label'] : NULL;
//                    echo "<span><br>- $labels</span>";
//                    if ($link != NULL) {
//                        echo "atau <a href=\"$link\"
//                                target='_parent'>klik disini</a>.";
//                    }
//                }
//            }
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
//                        echo "&nbsp;&nbsp;&nbsp;<a href='" . $elementConfigMutasi[$eName] . "' target='_blank' title='klik untuk melihat mutasi'><span class='glyphicon glyphicon-time'></span></a>";
                        $modalDialog = modalDialogBtn('&nbsp;', $elementConfigMutasi[$eName], $auto_close = 0, 'saldo');
                        echo "&nbsp;&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\"$modalDialog\" ttarget='_blank' title='klik untuk melihat mutasi'><span class='glyphicon glyphicon-time'></span></a>";
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

                top.$('.select2').selectpicker({ dropdownParent: '.modal' });

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
                    
                    $('#tagihan_bayar').prop('disabled',false)
//                    $('#nilai_entry').prop('disabled',false)
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


        // tembak jenis dulu 483, maka mereplace formulir kewajiban bayar dengan sisa yang belum dibayar...
        if ($jenisTr == "483") {
            $nilai_entry = $main["tagihan_bayar_after_uang_muka_norelasi"];
            echo "<script>
                    var nilai_entri = $nilai_entry;
                    console.log('nilai_entri: ' + nilai_entri);
                    console.log('nilai_entri: ' + addCommas(Math.floor(nilai_entri)));
                $('#payment_out').val(addCommas(Math.floor(nilai_entri)));
            </script>";
        }

        //--------
        if (count($arrItemTidakDibayar) > 0) {
            echo "<script>
                top.document.getElementById('checkbox_payment').disabled=true;
                top.document.getElementById('btnSave').disabled=true;
            </script>";
        }
        else {
            echo "<script>
                top.document.getElementById('checkbox_payment').disabled=false;
            </script>";
        }
        //--------


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

        echo "<script>
                top.document.getElementById('checkbox_payment').disabled=true;
            </script>";

    }

//    heGetTimedQuery($elementTimeStart, __LINE__);

    $sessionCleares = array("errLines", "errFields", "errMsg");
    foreach ($sessionCleares as $s) {
        if (isset($_SESSION[$s])) {
            unset($_SESSION[$s]);
        }
    }

}
