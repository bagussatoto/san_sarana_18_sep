<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!isset($this->session->login['id'])) {
            // redirect(base_url() . "Login");
            gotoLogin();
        }
        autoIdle_kick();
//                arrPrintWebs($this->session->login);
        //        matiHEre(__LINE__);
        $this->load->library("MobileDetect");
        $this->load->helper("he_access_right");
        $this->load->helper("he_session_replacer");
        $this->accessList = alowedAccess($this->session->login['id']);
        $this->configUiModul = loadConfigUiModul_he_misc();
        // matiHere(__FILE__);
        $this->masterConfigUi = $this->config->item("heTransaksi_ui");
        //        session_write_close();
    }

    public function index()
    {
//        arrPrint($this->session->login);
        $this->load->helper("he_menu");
        $this->load->library("MobileDetect");
        $mb = new MobileDetect();
        $isMob = $mb->isMobile();
        $wizard = "";
        $notif_opname = "";
        $view_opname = "";
        $scaner = "";
        $altDisplay = "";
        $is_avilMenu = notifAvilMenu($this->session->login['id']);

//        cekhitam($is_avilMenu);
        // $content = $this->MiniDesk();
        $data = array(
            "mode" => "index",
            // "mode"                => "minidesk",
            "isMobile" => $isMob,
            "title" => "Welcome $wizard",
            "subTitle" => "Welcome, " . $this->session->login['nama'],
            // "onprogressTitle"     => "<span class='glyphicon glyphicon-alert'></span> action needed #welcome",
            // "arrayProgressLabels" => $progressFields,
            // "arrayOnProgress"     => $arrayOnprogress,
            // "dataProposals"       => $arrItemTmp,
            // "historyTitle"        => "<span class='glyphicon glyphicon-time'></span> recent activities",
            //            "arrayHistoryLabels" => $historyFields,
            "sessionLogin" => $_SESSION['login'],
            "content" => "MiniDesk",
            // "videos"              => $videos,
            "notif_opname" => $notif_opname,
            "view_opname" => $view_opname,
            "scaner" => $scaner,
            "altDisplay" => $altDisplay,
            "notifNoakses" => $is_avilMenu,

        );

        $this->load->view("welcome", $data);
    }

    public function index_ori()
    {
        // cekMErah(__LINE__);
        // arrPrint($this->session->login);
        // arrprint($this->configUiModul);
        // matiHEre();
        if (!isset($this->session->login['id'])) {
            // redirect(base_url() . "Login");
            gotoLogin();
        }
        $this->load->library("MobileDetect");
        $mb = new MobileDetect();
        $isMob = $mb->isMobile();
        //cekMerah("HAHAHAA");
        $this->load->library("Transaksional");
        $trs = new Transaksional();
        $srcs = $trs->wizard_startup();
        $step_status = $srcs['step_status']['neraca_ok'];
        $wizard = "";
        //        $step_status = 1;
        if ($step_status == 0) {
            $link_now = $srcs['link_now'];
            // cekBiru($srcs);
            $alerts = array(
                "type" => "info",
                "html" => "Ada yang harus diselesaikan lebih dulu",
            );
            $link_to = $link_now;

            if (in_array("o_seller", my_memberships())) {
                $alerts = array(
                    "type" => "warning",
                    "html" => "belum semua data masuk, untuk saat ini transaksi belum bisa dilakukan",
                    "allowOutsideClick" => false,
                    "allowEscapeKey" => false,
                    "showConfirmButton" => false,
                );
                $wizard = swalAlert($alerts);
            } else {
                $wizard = swalAlertGoTo($alerts, $link_to);
            }
        }
        //        arrPrint($step_status);
        //        matiHere();
        validateUserSession($this->session->login['id']);//

        //        arrPrint($this->accessList);
        $replaceSession = replaceSession();
        $dataBehavior = $this->config->item('heDataBehaviour') != NULL ? $this->config->item('heDataBehaviour') : array();
        //arrPrintPink($dataBehavior);
        //<editor-fold desc="data proposal data">
        $this->load->model("Mdls/" . "MdlDataTmp");
        $tData = new MdlDataTmp();
        //        $tData->addFilter("mdl_name='$className'");
        $tmpTmp = $tData->lookupAll()->result();
        //        showLast_query("kuning");
        //        cekKuning(sizeof($tmpTmp));
        $dataProposals = array();
        if (sizeof($tmpTmp) > 0) {
            foreach ($tmpTmp as $row) {
                $mdlName = $row->mdl_name;
                $dataAccess = isset($this->config->item('heDataBehaviour')[$mdlName]) ? $this->config->item('heDataBehaviour')[$mdlName] : array(
                    "viewers" => array(),
                    "creators" => array(),
                    "creatorAdmins" => array(),
                    "updaters" => array(),
                    "updaterAdmins" => array(),
                    "deleters" => array(),
                    "deleterAdmins" => array(),
                );
                //                $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
                $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
                $allowView = false;
                $allowCreate = false;
                $allowEdit = false;
                $allowDelete = false;
                foreach ($mems as $mID) {
                    if (in_array($mID, $dataAccess['viewers'])) {
                        $allowView = true;
                    }
                    if (in_array($mID, $dataAccess['creators'])) {
                        $allowCreate = true;
                    }
                    if (in_array($mID, $dataAccess['updaters'])) {
                        $allowEdit = true;
                    }
                    if (in_array($mID, $dataAccess['deleters'])) {
                        $allowDelete = true;
                    }
                }

                if ($allowView || $allowCreate) {
                    if (!isset($dataProposals[$mdlName])) {
                        $dataProposals[$mdlName] = array();
                    }
                    $dataProposals[$mdlName][] = array(
                        "id" => $row->_id,
                        "label" => $row->mdl_label,
                        "origID" => $row->orig_id,
                        "proposer" => $row->proposed_by_name,
                        "date" => $row->proposed_date,
                        "content" => unserialize(base64_decode($row->content)),
                        "propose_type" => $row->propose_type,
                    );
                }
            }
        }

        //</editor-fold>
        //        arrPrintWebs($dataProposals);

        //<editor-fold desc="tampilan approval data">
        $no = 0;
        $arrItemTmp = array();
        if (sizeof($dataProposals) > 0) {
            foreach ($dataProposals as $mdlName => $pSpec) {
                $no++;
                //region relation translator
                $className = $mdlName;
                $this->relations = array();
                $this->relationPairs = array();
                if (file_exists(APPPATH . "models/Mdls/$className.php")) {
                    $this->load->model("Mdls/" . $className);
                    $o = new $className();
                    $fields = $o->getFields();
                    foreach ($fields as $fName => $f2Spec) {
                        //                echo $f2Spec["label"]."-".$f2Spec["reference"]."<br>";
                        if (isset($f2Spec['reference'])) {
                            //                    cekbiru("mendeteksi relasi milik $fName");
                            //if (array_key_exists($f2Spec['kolom'], $o->getListedFields())) {
                            $this->relations[$f2Spec['kolom']] = $f2Spec['reference'];
                            $this->load->model("Mdls/" . $f2Spec['reference']);
                            $o3 = new $f2Spec['reference']();
                            $tmp3 = $o3->lookupAll()->result();
                            //                    cekkuning($this->db->last_query());

                            if (sizeof($tmp3) > 0) {
                                //                        cekbiru("$fName ketemu data relasinya");
                                $mdlName2 = $f2Spec['kolom'];
                                $this->relationPairs[$mdlName2] = array();
                                foreach ($tmp3 as $row3) {
                                    $idxField = (null != $o3->getIndexFields()) ? $o3->getIndexFields() : "id";
                                    $id = isset($row3->$idxField) ? $row3->$idxField : 0;
                                    $name = isset($row3->nama) ? $row3->nama : "unknown";
                                    if (isset($row3->name)) {
                                        $name = $row3->name;
                                    }
                                    $this->relationPairs[$mdlName2][$id] = $name;
                                }
                            } else {
                                //                        cekmerah("$fName TIDAK ketemu data relasinya");
                            }
                            //}

                        }
                    }
                }
                //endregion


                $this->load->model("Mdls/" . $mdlName);

                $o2 = new $mdlName();
                $listedFields = $o2->getListedFields();
                foreach ($pSpec as $dSpec) {
                    //                    echo "mulai mengiterasi kolom .. <br>";
                    $tmpItemTmp = array();
                    $dataStatus = $dSpec['origID'] > 0 ? "pembaruan" : "data baru";

                    foreach ($listedFields as $fName => $fLabel) {
                        $fRealName = $fName;
                        //                        $tmpItemTmp[$fName] = $dSpec['content'][$fRealName];
                        $fieldLabel = isset($dSpec['content'][$fRealName]) ? $dSpec['content'][$fRealName] : "";
                        //===if related
                        if (array_key_exists($fName, $this->relations)) {
                            $fieldLabel = isset($this->relationPairs[$fName][$fieldLabel]) ? "<span class='fa fa-folder-o'></span> " . $this->relationPairs[$fName][$fieldLabel] : "unknown rel";
                        }
                        $tmpItemTmp[$fName] = $fieldLabel;
                    }


                    $approvalClick = "BootstrapDialog.closeAll();
                    BootstrapDialog.show(
                                   {
                                        title:'Data " . $dSpec['label'] . " &raquo; Setujui $dataStatus ',
                                        message: $('<div></div>').load('" . base_url() . "Data/editFrom/" . $dSpec['label'] . "/" . $dSpec['id'] . "/" . $dSpec['origID'] . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        closable:true,
                                        }
                                        );";

                    //                    $tmpItemTmp["date"] = $dSpec['date'];
                    //                    $tmpItemTmp["propose_type"] = $dSpec['propose_type'];
                    //                    $tmpItemTmp["proposed_by"] = $dSpec['proposer'];
                    $tmpItemTmp["action"] = "<a class='btn btn-primary btn-block' href='javascript:void(0);' onclick =\"$approvalClick;\">review</a>";
                    //                    $tmpItemTmp["history"] = "";
                    $tmpItemTmp["background-color"] = ($no % 2) == 0 ? "yellow" : "pink";
                    $tmpItemTmp["label"] = isset($dataBehavior[$mdlName]["label"]) ? $dataBehavior[$mdlName]["label"] : "no label";

                    if (!isset($arrItemTmp[$mdlName])) {
                        $arrItemTmp[$mdlName] = array();
                    }
                    $arrItemTmp[$mdlName][] = $tmpItemTmp;
                }

            }

        }
        //arrPrintWebs($arrItemTmp);
        //</editor-fold>


        //region allowedGroup
        $allowedGroup = array();
        $trans = $this->configUiModul;
        foreach ($trans as $jenisTr => $cSpec) {
            // arrPrint($cSpec['steps']);
            $tmp = array();
            if (is_array($cSpec['steps'])) {
                foreach ($cSpec['steps'] as $step => $sSpec) {
                    $group = $sSpec['userGroup'];
                    $tmp[$group] = $group;
                }
            }
            $allowedGroup[$jenisTr] = $tmp;

        }
        //        arrPrint($allowedGroup);
        //endregion


        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();

        //region firstSteps untuk common histories
        //===ngumpulin daftar kode step pertama, untuk keperluan ngindeks history
        $firstSteps = array();
        if (null != $this->configUiModul && sizeof($this->configUiModul) > 0) {
            foreach ($this->configUiModul as $jenis => $jSpec) {
                if (isset($jSpec['steps'][1])) {
                    $firstSteps[] = "'" . $jSpec['steps'][1]['target'] . "'";
                }
            }
        }
        //endregion

        $arrFilters = array();
        if (sizeof($this->accessList) > 0) {
            //            $arrFilters = array();
            $indsteps = "(";
            foreach ($this->accessList as $jenisMaster => $masterData) {
                foreach ($masterData as $stepNumber => $stepSpec) {
                    foreach ($stepSpec as $targetCode => $filters) {
                        $indsteps .= "'$targetCode',";
                        $stepCodes[] = $targetCode;
                        if ($filters['allowFollowUp'] == "true") {
                            $arrFilters["allowFollowUp"][] = $targetCode;
                        }
                    }
                }
            }
            $indsteps = rtrim($indsteps, ",");
            $indsteps .= ")";

        } else {

        }
        //dimatiakn dulu karena berat saat banyak data
        //$tmpHist = $tr->lookupUndoneEntries_joined($replaceSession)->result();
        $tmpHist = array();
        $mb = new MobileDetect();
        $isMob = $mb->isMobile();

        $progressFields = array();
        $arrayOnprogress = array();
        //endregion


        //region  lookup histories

        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("div_id='" . $this->session->login['div_id'] . "'");
        if (sizeof($replaceSession) > 0) {
            $tr->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        }

        // arrPrint($indsteps);
        if (sizeof($firstSteps) > 0) {
            $tr->addFilter("jenis in (" . implode(",", $firstSteps) . ")");
        }
        if (sizeof($arrFilters) > 0) {
            $tr->addFilter("next_step_code in $indsteps");//dimatiin dulu sementara karena coTransakiUi belum siap
            // $tr->addFilter("next_step_code in ('466','466r','467','111')");
        } else {
            $tr->addFilter("transaksi.oleh_id='" . $this->session->login['id'] . "'");
        }
        $tr->addFilter("link_id='0'");

        $tmpHist = $tr->lookupRecentHistories(10)->result();
        // arrPrint($tmpHist);
        //                cekMerah($this->db->last_query());
        // matiHEre();
        //arrPrint($tmpHist);
        $arrayHistory = array();
        $unionHistoryFields = array();
        if (sizeof($tmpHist) > 0) {
            foreach ($tmpHist as $row) {
                //                arrPrint($row);
                $tmp["jenis_label"] = $row->jenis_label;
                $jenisTr = $row->jenis_master;
                $modul = isset($this->masterConfigUi[$jenisTr]["modul"]) ? $this->masterConfigUi[$jenisTr]["modul"] : false;
                $modul_path = base_url() . $modul . "/";
                $historyFields = isset($this->configUiModul[$jenisTr]['shortHistoryFields']) ? $this->configUiModul[$jenisTr]['shortHistoryFields'] : array();
                $pairRegistries = isset($this->configUiModul[$jenisTr]['pairRegistries']) ? $this->configUiModul[$jenisTr]['pairRegistries'] : array("main", "items");
                $selectKolom = implode(",", $pairRegistries) . ",transaksi_id";
                // arrPrint($pairRegistries);
                //                 matiHEre($jenisTr);
                $mb = new MobileDetect();
                $isMob = $mb->isMobile();
                if ($isMob) {
                    $historyFields = isset($this->configUiModul[$jenisTr]['compactHistoryFields']) ? $this->configUiModul[$jenisTr]['compactHistoryFields'] : array();
                }
                $aallowedView = isset($allowedGroup[$jenisTr]) ? $allowedGroup[$jenisTr] : array();
                $tmp = array();

                if (sizeof($this->accessList) > 0) {
                    if (sizeof($pairRegistries) > 0) {
                        $trReg = new MdlTransaksi();
                        $trReg->setFilters(array());
                        $trReg->setJointSelectFields($selectKolom);
                        $trReg->addFilter("transaksi_id='" . $row->id . "'");
                        $tmpReg = $trReg->lookupDataRegistries()->result();
                        // cekHitam($this->db->last_query());
                        if (sizeof($tmpReg) > 0) {
                            foreach ($tmpReg as $regRow) {
                                foreach ($pairRegistries as $param) {
                                    $$param = blobDecode($regRow->$param);

                                }
                                // $param = $regRow->param;

                            }
                            foreach ($pairRegistries as $eReg) {
                                foreach ($$eReg as $k => $v) {
                                    if (($k != NULL) && !isset($row->$k)) {

                                        $row->$k = $v;
                                    }
                                }
                            }
                        }
                    }
                    foreach ($historyFields as $fName => $fLabel) {
                        $tmp[$fName] = isset($row->$fName) ? formatField_he_format($fName, $row->$fName, $jenisTr, $modul_path) : "";
                    }
                    $tmp['list'] = "<a class='btn btn-block btn-default' href='" . $modul_path . "History/viewHistory/$jenisTr'><span class='glyphicon glyphicon-th-list'></span></a>";
                    $unionHistoryFields = array_merge($unionHistoryFields, $historyFields);
                    $arrayHistory[] = $tmp;
                } else {
                    if (array_intersect($aallowedView, $mems)) {
                        if (sizeof($pairRegistries) > 0) {
                            $trReg = new MdlTransaksi();
                            $trReg->setFilters(array());
                            $trReg->setJointSelectFields($selectKolom);
                            $trReg->addFilter("transaksi_id='" . $row->id . "'");
                            $tmpReg = $trReg->lookupDataRegistries()->result();
                            if (sizeof($tmpReg) > 0) {
                                foreach ($tmpReg as $regRow) {
                                    foreach ($pairRegistries as $param) {
                                        $$param = blobDecode($regRow->$param);

                                    }
                                    // $param = $regRow->param;

                                }
                                foreach ($pairRegistries as $eReg) {
                                    foreach ($$eReg as $k => $v) {
                                        if (!isset($row->$k)) {

                                            $row->$k = $v;
                                        }
                                    }
                                }
                            }
                        }
                        foreach ($historyFields as $fName => $fLabel) {
                            $tmp[$fName] = isset($row->$fName) ? formatField_he_format($fName, $row->$fName, $jenisTr, $modul_path) : "";
                        }
                        $tmp['list'] = "<a class='btn btn-block btn-default' href='" . $modul_path . "History/viewHistory/$jenisTr'><span class='glyphicon glyphicon-th-list'></span></a>";
                        $unionHistoryFields = array_merge($unionHistoryFields, $historyFields);
                        $arrayHistory[] = $tmp;
                    }
                }
            }
        }

        //endregion
        // arrPrintHijau($_SESSION['login']);
        $scaner = "";
        if (my_type() == "employee_kirim") {
            $scaner .= "<i class='fa fa-qrcode' style='font-size: 10em;'></i>";
            $scaner .= "<div>arahkan pada QR saler order (SO)</div>";
        }

        $videos = $this->config->item('videos') != null ? $this->config->item('videos') : array();
        //        $videos = array();

        /* --------------------------------------------------------------------------
         * BEFORE OPNAME
         * --------------------------------------------------------------------------*/
        $tgl_now = dtimeNow("Y-m-d");
        $tgl_min = "2022-12-18";
        //        $tgl_max = "2022-12-30";
        $tgl_max = "2022-12-31";
        $notif_opname = "";
        //        cekHere("$tgl_now >= $tgl_min && $tgl_now <= $tgl_max");
        /* -----------------------------------------------------------------------
         * transaksi gantung
         * -----------------------------------------------------------------------*/
        $this->load->library("Transaksional");
        $ts = new Transaksional();
        //        if($tgl_now >= $tgl_min && $tgl_now <= $tgl_max){
        //            $this->load->library("Transaksional");
        //            $ts = new Transaksional();
        $src_ts = $ts->callTransaksiBeforeOpname();

        $jml_gantungan = $src_ts['jml'];
        $jml_gantungan = 0;
        $jenis_gantung = $src_ts['datas'];
        $link_gantungan = $src_ts['link'];

        if ($jml_gantungan > 0) {
            $link_gantung = base_url() . $link_gantungan;
            $notif_opname = modalDialogBtn("Transaksi yang harus diselesaikan sebelum stok opname", "$link_gantung");
        }
        //cekHere("masuk disini");
        //        }

        $view_opname = false;
        // $ts->setCabangId(false);
        $src_opname = $ts->cekOpnameAktive(false);
        if ($src_opname["jml"] > 0) {
            $view_opname = base_url() . "opname/Opname/viewOpnameAktive";
        }
        /* --------------------------------------------------------------------------*/

        if (!isset($sessionLogin['cabang_id']) || $sessionLogin['cabang_id'] == "") {

            // $link_miniDesk = base_url() . "Welcome/MiniDesk";
            // $script_bottom .= "$('#lobi').load('$link_miniDesk')";
            $progressFields = $this->MiniDesk;
        }

        //        arrprint($arrayOnprogress);

        //        print_r($unionHistoryFields);die();
        $unionHistoryFields['list'] = "more..";
        $data = array(
            "mode" => "welcome",
            // "mode"                => "minidesk",
            "isMobile" => $isMob,
            "title" => "Welcome $wizard",
            "sessionLogin" => $this->session->login,
            "subTitle" => "Welcome, " . $this->session->login['nama'],
            "onprogressTitle" => "<span class='glyphicon glyphicon-alert'></span> action needed #welcome",
            "arrayProgressLabels" => $progressFields,
            "arrayOnProgress" => $arrayOnprogress,
            "dataProposals" => $arrItemTmp,
            "historyTitle" => "<span class='glyphicon glyphicon-time'></span> recent activities",
            //            "arrayHistoryLabels" => $historyFields,
            "arrayHistoryLabels" => $unionHistoryFields,
            "arrayHistory" => $arrayHistory,
            "videos" => $videos,
            "notif_opname" => $notif_opname,
            "view_opname" => $view_opname,
            "scaner" => $scaner,

        );

        $this->load->view("welcome", $data);


        //endregion
    }

    public function MiniDesk()
    {
        $this->load->helper("he_access_right");
        $availmembership = availMembershipAndCabang(my_id());

        // arrPrintPink($availmembership);
        if (isset($availmembership["cabang"]) && count($availmembership["cabang"]) == 1) {
            $loginProp = array();

            foreach ($availmembership["cabang"] as $cid => $cidData) {
//                arrPrint($cidData);
                $defaultGudang = getDefaultWarehouseID($cid);
                $loginProp = $cidData + $defaultGudang;
                $data = blobEncode(
                    array(
                        "id" => $cid,
                        "name" => $cidData["nama"],
                        "place" => $cidData["status_nama"],
                    )
                );
                $link_exec = MODUL_PATH . "redirToAksesMenu" . "?ix=$data";
            }

//            mati_disini(__LINE__);
//            echo "<script>top.location.href='" . base_url() . "';</script>";


            if (!isset($this->session->login["cabang_id"]) || ($this->session->login["cabang_id"] == NULL)) {
                echo "<script>document.getElementById('result').src='$link_exec';</script>";
            }


        } else {
            if (isset($availmembership["cabang"]) && (count($availmembership["cabang"])) > 1) {
                //wajib milih dulu karena punya akses ke lebih 1 cabang

                $dataCabang = $availmembership["cabang"];


                $data = array(
                    "mode" => "minidesk",
                    "title" => "Anda memiliki Hak Akses Multi Cabang, Silahkan pilih cabang yang dikehendaki, sebelum melanjutkan transaksi",
                    "subTitle" => "Anda memiliki <b>hak akses <r>multi cabang</r></b><br>silahkan pilih salah satu lokasi yang akan diakses.",
                    "arrayOnProgress" => $dataCabang,
                    "link" => MODUL_PATH . "" . "redirToAksesMenu",
                );
                $this->load->view("welcome", $data);
            } else {
                $data = array(
                    "mode" => "minidesk",
                    "title" => "Anda TIDAK terdaftar di Cabang atau DC/Pusat. Silahkan hubungi admin untuk mendaftarkan ID dan Hak Akses Anda.",
                    "subTitle" => "Anda <b>TIDAK</b> terdaftar di Cabang atau DC/Pusat.<br>Silahkan hubungi admin untuk mendaftarkan <b>ID dan Hak Akses Anda</b>.",
                    "arrayOnProgress" => $dataCabang,
                    "link" => MODUL_PATH . "" . "redirToAksesMenu",
                );

                $this->load->view("welcome", $data);

            }
        }
    }

    public function redirToAksesMenu()
    {
        $lastSessionLogin = $this->session->login;
        $lastSessionLogin["cabang_id"] = $lastSessionLogin["last_cabang_id"];
        $lastSessionLogin["cabang_nama"] = $lastSessionLogin["last_cabang_nama"];
        unset($this->session->login["last_cabang_id"]);
        unset($this->session->login["last_cabang_nama"]);
//        arrPrint($lastSessionLogin);
//        arrPrint($_SESSION['login']);

        $loginProp = array();
        $data = blobDecode($_GET["ix"]);


        if (!isset($data["gudang_id"])) {
            $tempGudang = getDefaultWarehouseID($data["id"]);
            $data["gudang_id"] = $tempGudang["gudang_id"];
            $data["gudang_nama"] = $tempGudang["gudang_nama"];


        }
        $sessionSwappers = array(
            "cabang_id" => "id",
            "cabang_nama" => "name",
            "gudang_id" => "gudang_id",
            "gudang_nama" => "gudang_nama",
            "place_label" => "place",
        );

        $tmp = array();
        foreach ($sessionSwappers as $k => $src) {
            $tmp[$k] = $data[$src];
        }
        /*---------------------------------------------------------------
         * untuk menyetarkan istilah dengan config_ui pada pengenalan menu
         * --------------------------------------------------------------*/
        $tmp['place'] = ($data['place'] == "pusat") ? "center" : "branch";

        $loginprop = array_merge($this->session->login, $tmp);
        $this->session->login = $loginprop;

//        arrPrintPink($lastSessionLogin);

        //------
        $this->load->library("locker");
        $lls = new Locker();
        $lls->setLoginSessions($lastSessionLogin);
        $lls->normalisasiStok();
        //------
//        mati_disini(__LINE__);


        $zippedSessions = base64_encode(serialize($this->session->login));
        setcookie("uprop", $zippedSessions, time() + 31356000);
        echo "<script>top.location.href='" . base_url() . "';</script>";


    }


}
