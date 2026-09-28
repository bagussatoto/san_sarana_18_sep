<?php

switch ($mode) {

    default:
        break;

    case "followUpCrm":

        if(count($error)>0){
            $items="";

            //region tombol
            $btn_group = "<div class='btn-group ' role='group'>";
            $btn_group .= "<button type='button' class='btn btn-default' data-dismiss='modal' ><span class='glyphicon glyphicon-chevron-left'></span>Close</button>";
//            $btn_group .= "<button type='button' id='btn-to-shopping-cart' disabled class='btn btn-primary' data-dismis='modal' onclick=\"top.open_holdon();top.$('#result').load('$target');\">To Shopping Cart</button>";
//            $btn_group .= "<div id='notif_cabang' $allow_followup  class='btn btn-link hidden text-red pull-right blink'>Pilih salah satu cabang </div>";
            $btn_group .= "</div>";

            //endregion
            $items .= $error[0]."<br>";
            $items .= $btn_group;
            echo $items;
        }
        else{
        $pihakMainExec = isset($pihakMainExec) ? trim(strtolower((string)$pihakMainExec)) : "";
        if ($pihakMainExec != "san" && $pihakMainExec != "local") {
            $pihakMainExec = "local";
        }
        $pihakMainID = isset($pihakMainID) && is_numeric($pihakMainID) ? (int)$pihakMainID : 0;
        $pihakMainLabel = isset($pihakMainLabel) && trim((string)$pihakMainLabel) != "" ? $pihakMainLabel : "Pilih Cabang Pengiriman";
        $resetPengirimanOrder = isset($resetPengirimanOrder) ? trim((string)$resetPengirimanOrder) : "";
        $countP = 0;
        $data_cabang_pos_ = "";
        if (count($data_cabang_pos) > 0) {
            $pilihan_pengiriman = array(
                "san"   => array(
                    "label"     => "san",
                    "target_id" => "wrapper-radioo",
                ),
                "local" => array(
                    "label"     => "local",
                    "target_id" => "wrapper-radioo",
                ),
            );
            $jmlPilihan = count($pilihan_pengiriman);
            $keField = "sumber";
            $data_cabang_pos_ .= "<style>
                .crm-followup-shipping {
                    margin-bottom: 6px;
                }
                .crm-followup-shipping .shipping-source {
                    margin-bottom: 5px;
                }
                .crm-followup-shipping input.toggle-radioo {
                    position: absolute !important;
                    opacity: 0 !important;
                    width: 1px !important;
                    height: 1px !important;
                    margin: 0 !important;
                    pointer-events: none !important;
                }
                .crm-followup-shipping .btn-radioo {
                    border-radius: 6px;
                    border: 1px solid #1a1a1a;
                    display: inline-block;
                    cursor: pointer;
                    transition: background-color 0.2s ease, border-color 0.2s ease;
                    margin: 0 4px 4px 0;
                    padding: 1px 10px;
                    line-height: 1.2;
                    font-weight: 700;
                    font-size: 14px;
                    text-transform: uppercase;
                    background-image: linear-gradient(#2dff70, #03bd02);
                    color: #000000;
                    user-select: none;
                }
                .crm-followup-shipping .radio-active {
                    background-image: linear-gradient(#ffef30, #bd6e1f);
                    background-color: #ffef30;
                    border-color: #7a4b10;
                    color: #000000;
                }
                .crm-followup-shipping .radio-inactive {
                    background-image: linear-gradient(#2dff70, #03bd02);
                    background-color: #2dff70;
                    border-color: #1a1a1a;
                    color: #000000;
                }
            </style>";
            $data_cabang_pos_ .= "<div class='crm-followup-shipping'>";

            if($allow_followup=="disabled"){
                $data_cabang_pos_ .="<div class='panel'><h2 class='bg-danger text-center'style='color: brown;'>$msg_warning</h2></div>";
            }
            else{

                $data_cabang_pos_ .= "<div class='shipping-source'>";
                $data_cabang_pos_ .= "Pengiriman <div class='btn-group'>";
                foreach ($pilihan_pengiriman as $ky => $data) {
                    $target_id = $data["target_id"];
                    $countP++;
                    // arrPrintHijau($data);
                    // $ky = $data["id"];
                    $data_label = $data["label"];
                    $class_default = $pihakMainExec == $ky ? "btn-success" : "btn-danger";
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

                    $data_cabang_pos_ .= "<button type='button' id='$id_toggle' data-target-id='$target_id' data-source-mode='$ky' class='btn btn-xs $class_default btn-anu text-uppercase' style='padding: 2px 20px;'>$data_label</button>";
                }
                // $data_cabang_pos_ .= "<input id='$id_toggle' $checked class='toggle-radiooo $class_toggle' name='$keField' value='$ky' type='radio' onclick=\"pilihanCabang(this.id, '$target_id');\">                         <label for='$id_toggle' class='btn-radio' style='$radius' >$data_label</label>";
                $data_cabang_pos_ .= "</div>";
                $data_cabang_pos_ .= "</div>";


                $showPilihan = $pihakMainExec == "san" ? "" : "hidden";
                $data_cabang_pos_ .= "<div class='wrapper-radioo $showPilihan' >";
                $data_cabang_pos_ .= "<h4 style='text-transform: capitalize; margin: unset;'>$pihakMainLabel</h4>";

                $jmlPilihan = count($data_cabang_pos);
                $keField = "cabang";
                $countP = 0;
                // $rrrr = $customer["id"];
                $mainPihakId = $pihakMainID;
                foreach ($data_cabang_pos as $ky0 => $data) {
                    $data_cabang_pos_0 = $data;
                    $countP++;
                    // arrPrintHijau($data);
                    $ky = $data["id"];
                    $data_label = $data["label"];
                    $checked = $mainPihakId == $ky ? "checked" : "";
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

                    $radioStateClass = $checked != "" ? "radio-active" : "radio-inactive";
                    $data_cabang_pos_ .= "<input id='$id_toggle' $checked class='toggle-radioo $class_toggle' name='$keField' value='$ky' type='radio' onclick=\"document.getElementById('result').src='" . $data_cabang_pos_0["target"] . "?id=" . $ky . "&emb=" . $customer["id"] . "&crm_model=" . $relModelCrm . "';\">
                            <label for='$id_toggle' class='btn-radioo $radioStateClass'>$data_label</label>";
                }
                $data_cabang_pos_ .= "</div>";
                }
                $data_cabang_pos_ .= "</div>";

        }
        $listCustomer = "";
        if (count($customer) > 0) {
            $listCustomer .= "<div>";
            $listCustomer .= "<table class='table table-no-border'>";
            foreach ($customerField as $k => $k_alias) {
                $listCustomer .= "<tr>";
                $listCustomer .= "<td>" . $k_alias . "</td>";
                $listCustomer .= "<td>" . $customer[$k] . "</td>";
                $listCustomer .= "</tr>";
            }
            $listCustomer .= "</table>";
            $listCustomer .= "<div>";
        }
        $scriptBottom = "";
        if ((isset($pihakMainExec) && $pihakMainExec == "san") && (!isset($pihakMainID) || $pihakMainID <= 0)) {
            //            cekHere(__LINE__);
            $scriptBottom .= "<script>
                                
                setTimeout(function() {
                    $('#itemKeyword').prop('disabled', true);
                    $('#pihakName').prop('disabled', true);
                    $('#tic_lanjut').prop('disabled', true);
                    $('.wrapper-radioo').addClass('blink-border');
                 
                }, 3000);
                
                swal({
                    type: 'warning',
                    html: 'Harap $pihakMainLabel'
                });

              </script>";
        }
        //region tombol
        $btn_group = "<div class='btn-group ' role='group'>";
        $btn_group .= "<button type='button' class='btn btn-default' data-dismis='modal' onclick=\"top.$('#result').load('$reset_link')\"><span class='glyphicon glyphicon-chevron-left'></span>Close</button>";
        $btn_group .= "<button type='button' $allow_followup class='btn btn-danger' onclick=\"var _fn = (window.confirmReject || (top && top.confirmReject)); if(typeof _fn === 'function') { _fn('$rejectLink'); } else { console.error('confirmReject not found'); alert('Sistem belum siap, silakan coba lagi (Error: confirmReject undefined)'); }\"><span class='glyphicon glyphicon-remove'></span>Reject</button>";
        $btn_group .= "<button type='button' $allow_followup id='btn-to-shopping-cart' disabled class='btn btn-primary' data-dismis='modal' onclick=\"if(typeof top.handleToShoppingCartWithMissingPrice==='function'){top.handleToShoppingCartWithMissingPrice('$target');}else if(typeof handleToShoppingCartWithMissingPrice==='function'){handleToShoppingCartWithMissingPrice('$target');}else{top.open_holdon();top.$('#result').load('$target');}\">To Shopping Cart</button>";
        $btn_group .= "<div id='notif_cabang' $allow_followup  class='btn btn-link hidden text-red pull-right blink'>Pilih salah satu cabang </div>";
        $btn_group .= "</div>";
        //endregion
        $items .= $btn_group;

        $missingPriceProducts = isset($missingPriceProducts) && is_array($missingPriceProducts) ? $missingPriceProducts : array();
        $missingPriceProductIds = isset($missingPriceProductIds) && is_array($missingPriceProductIds) ? $missingPriceProductIds : array();
        $missingPriceExpectedKey = isset($missingPriceExpectedKey) && is_string($missingPriceExpectedKey) && trim($missingPriceExpectedKey) != "" ? trim($missingPriceExpectedKey) : "jual";
        $missingPriceSyncBaseUrl = isset($missingPriceSyncBaseUrl) && is_string($missingPriceSyncBaseUrl) ? $missingPriceSyncBaseUrl : (base_url() . "statik/Data/syncro_data/Produk/Produk");
        $missingPriceRefreshUrl = isset($missingPriceRefreshUrl) && is_string($missingPriceRefreshUrl) ? $missingPriceRefreshUrl : "";
        $missingPriceProductsJson = json_encode($missingPriceProducts, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
        $missingPriceIdsJson = json_encode($missingPriceProductIds, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
        $missingPriceExpectedKeyJson = json_encode($missingPriceExpectedKey, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
        $missingPriceSyncBaseUrlJson = json_encode($missingPriceSyncBaseUrl, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
        $missingPriceRefreshUrlJson = json_encode($missingPriceRefreshUrl, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
        $items .= "<script>
            (function(){
                var payload = {
                    products: $missingPriceProductsJson || [],
                    ids: $missingPriceIdsJson || [],
                    expectedPriceKey: $missingPriceExpectedKeyJson || 'jual',
                    syncBaseUrl: $missingPriceSyncBaseUrlJson || '',
                    refreshUrl: $missingPriceRefreshUrlJson || ''
                };
                window.__missingPriceSyncPayload = payload;
                if (typeof top !== 'undefined' && top) {
                    top.__missingPriceSyncPayload = payload;
                }
            })();
        </script>";
        // Tutup spinner & daftarkan fungsi sesegera mungkin agar terlihat di level mana pun
        $early_script = '<script>
            if(top && typeof top.close_holdon === "function") top.close_holdon();
            console.log("Definition of confirmReject starting...");

            function confirmReject(url) {
                console.log("confirmReject called with URL:", url);
                try {
                    var _disableFocus = function() { return false; };
                    if (window.$ && $.fn.modal && $.fn.modal.Constructor) $.fn.modal.Constructor.prototype.enforceFocus = _disableFocus;
                    if (top && top.$ && top.$.fn.modal && top.$.fn.modal.Constructor) {
                        top.$.fn.modal.Constructor.prototype.enforceFocus = _disableFocus;
                    }
                    if (typeof BootstrapDialog !== "undefined") BootstrapDialog.setEnforceFocus(false);
                    if (top && typeof top.BootstrapDialog !== "undefined") top.BootstrapDialog.setEnforceFocus(false);
                } catch(e) { console.warn("Focus fix error:", e); }
                
                try {
                    $(".modal").removeAttr("tabindex");
                    if (top && top.$) top.$(".modal").removeAttr("tabindex");
                } catch(e) {}

                var targetModal = "body";
                try {
                    var $visibleModals = (top && top.$) ? top.$(".modal:visible") : $(".modal:visible");
                    if ($visibleModals.length) {
                        targetModal = $visibleModals.last().find(".modal-content")[0] || "body";
                    }
                } catch(e) { console.warn("Targeting error:", e); }

                var _swal = (top && top.swal) ? top.swal : swal;

                if (typeof _swal !== "function") {
                    console.error("SweetAlert not found!");
                    alert("SweetAlert (swal) tidak ditemukan.");
                    return;
                }

                _swal({
                    title: "Alasan Reject",
                    input: "textarea",
                    inputPlaceholder: "Masukkan alasan reject...",
                    showCancelButton: true,
                    confirmButtonText: "Continue Reject",
                    confirmButtonColor: "#d33",
                    cancelButtonText: "close",
                    allowOutsideClick: false,
                    target: targetModal,
                    onOpen: function() {
                        var focusInterval = setInterval(function() {
                            var $ta = $(".swal2-textarea:visible, .swal-content__textarea:visible");
                            if (top && top.$ && top.$(".swal2-textarea:visible, .swal-content__textarea:visible").length) {
                                $ta = top.$(".swal2-textarea:visible, .swal-content__textarea:visible");
                            }
                            if ($ta.length) {
                                $ta.focus();
                                $ta.on("focusin focus keydown", function(e) { e.stopPropagation(); });
                                if ($ta.is(":focus")) {
                                    console.log("Focus achieved on textarea");
                                    clearInterval(focusInterval);
                                }
                            }
                        }, 300);
                        setTimeout(function() { clearInterval(focusInterval); }, 3000);
                    },
                    inputValidator: function(value) {
                        if (typeof Promise === "undefined") {
                             return (!value || value.trim().length === 0) ? "Alasan harus diisi!" : null;
                        }
                        return new Promise(function(resolve) {
                            if (value && value.trim().length > 0) {
                                resolve();
                            } else {
                                resolve("Alasan harus diisi!");
                            }
                        });
                    }
                }).then(function(result) {
                    console.log("Swal then result:", result);
                    var reasonVal = null;
                    if (result && typeof result === "object") {
                        if ("value" in result) {
                            reasonVal = result.value;
                        } else if (result.dismiss) {
                            return; 
                        }
                    } else if (result) {
                        reasonVal = result;
                    }

                    if (reasonVal && reasonVal !== true && reasonVal !== "undefined") {
                        var reason = encodeURIComponent(reasonVal);
                        var finalUrl = url + (url.indexOf("?") !== -1 ? "&" : "?") + "reason=" + reason;
                        console.log("Final URL built:", finalUrl);
                        
                        if (top && typeof top.open_holdon === "function") top.open_holdon();
                        
                        var $target = top.$("#result");
                        if (!$target.length) $target = $("#result");
                        
                        if ($target.length) {
                            $target.load(finalUrl, function() {
                                console.log("Content loaded from:", finalUrl);
                                try {
                                    var $vModals = (top && top.$) ? top.$(".modal:visible") : $(".modal:visible");
                                    if ($vModals.length) $vModals.last().modal("hide");
                                } catch(e) {}
                                if (top && typeof top.close_holdon === "function") top.close_holdon();

                                // Success message and Top-level Reload
                                if (typeof _swal === "function") {
                                    _swal({
                                        title: "Berhasil!",
                                        text: "Order CRM berhasil direject.",
                                        type: "success",
                                        icon: "success",
                                        confirmButtonText: "OK"
                                    }).then(function() {
                                        if (top && top.location) {
                                            top.location.reload();
                                        } else {
                                            window.location.reload();
                                        }
                                    });
                                } else {
                                    alert("Berhasil Reject!");
                                    if (top && top.location) {
                                        top.location.reload();
                                    } else {
                                        window.location.reload();
                                    }
                                }
                            });
                        } else {
                            console.error("Target #result not found, redirecting instead.");
                            window.location.href = finalUrl;
                        }
                    }
                })["catch"](function(err) {
                    console.error("Swal error:", err);
                });
            }

            window.confirmReject = confirmReject;
            if (typeof top !== "undefined" && top !== null) top.confirmReject = confirmReject;
            if (typeof parent !== "undefined" && parent !== null) parent.confirmReject = confirmReject;
            
            console.log("confirmReject successfully defined and exported");
        </script>';
        echo $early_script;

        $resetPengirimanOrderJson = json_encode($resetPengirimanOrder, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
        $pihakMainExecJson = json_encode($pihakMainExec, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
        $pihakMainIDNum = (int)$pihakMainID;
        $items .= "<script>
            (function(){
                var resetPengirimanOrderUrl = $resetPengirimanOrderJson || '';
                var currentMainExec = $pihakMainExecJson || 'local';
                var currentMainId = $pihakMainIDNum;

                function loadResultTarget(url) {
                    if (typeof url !== 'string' || url === '') {
                        return;
                    }
                    try {
                        if (typeof top !== 'undefined' && top && top.document) {
                            var topResultNode = top.document.getElementById('result');
                            if (topResultNode) {
                                if ((topResultNode.tagName || '').toLowerCase() === 'iframe') {
                                    topResultNode.src = url;
                                    return;
                                }
                                if (top.$ && top.$('#result').length > 0) {
                                    top.$('#result').load(url);
                                    return;
                                }
                            }
                        }
                    } catch (e) {}
                    var ifr = document.getElementById('result');
                    if (ifr) {
                        if ((ifr.tagName || '').toLowerCase() === 'iframe') {
                            ifr.src = url;
                        } else if (typeof $ === 'function') {
                            $('#result').load(url);
                        }
                    }
                }

                function markSourceButton(id) {
                    $('.btn-anu').addClass('btn-danger').removeClass('btn-success');
                    $('#' + id).removeClass('btn-danger').addClass('btn-success');
                }

                function syncMainExec(mode) {
                    if (resetPengirimanOrderUrl === '') {
                        return;
                    }
                    loadResultTarget(resetPengirimanOrderUrl + '?pihakMainExec=' + encodeURIComponent(mode));
                }

                function applySourceUi(mode) {
                    if (mode === 'san') {
                        $('.wrapper-radioo').removeClass('hidden');
                        markSourceButton('toggle-san-sumber');

                        var hasCabang = $('input[type=\"radio\"].toggle-radioo:checked').length > 0 || currentMainId > 0;
                        if (hasCabang) {
                            var checkedId = $('input[type=\"radio\"].toggle-radioo:checked').first().attr('id');
                            if (checkedId) {
                                $('input[type=\"radio\"].toggle-radioo').each(function() {
                                    $('label[for=\"' + this.id + '\"]').removeClass('radio-active').addClass('radio-inactive');
                                });
                                $('label[for=\"' + checkedId + '\"]').removeClass('radio-inactive').addClass('radio-active');
                            }
                        }
                        $('#btn-to-shopping-cart').prop('disabled', !hasCabang);
                        if (hasCabang) {
                            $('#notif_cabang').addClass('hidden');
                        } else {
                            $('#notif_cabang').removeClass('hidden');
                        }
                        return;
                    }

                    $('.wrapper-radioo').addClass('hidden');
                    markSourceButton('toggle-local-sumber');
                    $('input[type=\"radio\"].toggle-radioo').prop('checked', false);
                    $('label.btn-radioo').removeClass('radio-active').addClass('radio-inactive');
                    $('#btn-to-shopping-cart').prop('disabled', false);
                    $('#notif_cabang').addClass('hidden');
                }

                function onSourceModeChange(id) {
                    var mode = (id === 'toggle-san-sumber') ? 'san' : 'local';
                    currentMainExec = mode;
                    if (mode !== 'san') {
                        currentMainId = 0;
                    }
                    applySourceUi(mode);
                    syncMainExec(mode);
                }
                $('.btn-anu').off('click.crmShipping').on('click.crmShipping', function() {
                    var id = $(this).attr('id') || '';
                    if (id === '') {
                        return;
                    }
                    onSourceModeChange(id);
                });

                $('input[type=\"radio\"].toggle-radioo').off('change.crmShipping').on('change.crmShipping', function() {
                    var id = $(this).attr('id');
                    var name = $(this).attr('name');
                    $('input[name=\"' + name + '\"]').each(function() {
                        $('label[for=\"' + this.id + '\"]').removeClass('radio-active').addClass('radio-inactive');
                    });
                    $('label[for=\"' + id + '\"]').removeClass('radio-inactive').addClass('radio-active');
                    currentMainId = 1;
                    $('#btn-to-shopping-cart').prop('disabled', false);
                    $('#notif_cabang').addClass('hidden');
                });

                applySourceUi(currentMainExec);
            })();
        </script>";

        echo $listCustomer;
        echo $data_cabang_pos_;
        echo $items;
        }


        break;
}