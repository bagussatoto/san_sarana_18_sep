<?php
defined('BASEPATH') OR exit('No direct script access allowed');

error_reporting(0);
ini_set('display_errors', 0);

class Login extends CI_Controller
{
    /**
     * Login constructor.
     */

    protected $forceMobile;
    protected $forceDesktopView;

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        //==cek dulu apakah masiha da cookies utama
        if (isset($_COOKIE['uprop'])) {
            $unzippedSessions = unserialize(base64_decode($_COOKIE['uprop']));
            if (is_array($unzippedSessions) and sizeof($unzippedSessions) > 0) {
                $this->session->login = $unzippedSessions;
                redirect(base_url());
            }
        }

        if (isset($this->session->login['id'])) {
            topRedirect(base_url());
            // mati_disini("sudah ada session");
        }

        // matiHere(__LINE__ . __FILE__);
        if (isset($_GET['err'])) {
            $tempErr = blobDecode($_GET['err']);
            $tempIpadd = $tempErr['ipadd'];
            $tempDevices = $tempErr['devices'];
            $temp_err = "<div class='text-center bg-warning' style='margin-top: 5px;'>";
            $temp_err .= "<div>Session ended</div>";
            $temp_err .= "<div>your id was login on $tempIpadd</div>";
            $temp_err .= "<div>by  $tempDevices</div>";
            $temp_err .= "</div>";
        } else {
            $temp_err = "";
        }
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "";
        $param = isset($_GET['xxx']) ? $_GET['xxx'] : "";
        if (isset($_COOKIE['uid'])) {
            $c_uid = $_COOKIE['uid'];
            $c_pwd = base64_decode($_COOKIE['pwd']);
            $checked = "checked";
        } else {
            $checked = "";
            $c_uid = "";
            $c_pwd = "";
        }
        $formAttributes = array(
            'id' => 'fLogin',
            'name' => 'fLogin',
            'class' => 'form-signin',
            'data_toggle' => 'validator',
            'target' => 'result',
        );

        //region writelog
        //        $this->load->model("Mdls/" . "MdlActivityLog");
        //        $hTmp = new MdlActivityLog();
        //        $hTmp->setFilters(array());
        //        $tmpHData = array(
        //            "title"         => "Login",
        //            "sub_title"     => "Please Login",
        //            "uid"           => isset($this->session->login['id']) ? $this->session->login['id'] : 0,
        //            "uname"         => isset($this->session->login['nama']) ? $this->session->login['nama'] : "noname",
        //            "dtime"         => date("Y-m-d H:i:s"),
        //            "transaksi_id"  => 0,
        //            "deskripsi_old" => "",
        //            "deskripsi_new" => "",
        //            "jenis"         => "",
        //            "ipadd"         => $_SERVER['REMOTE_ADDR'],
        //            "devices"       => $_SERVER['HTTP_USER_AGENT'],
        //            "category"      => "browse",
        //            "controller"    => $this->uri->segment(1),
        //            "method"        => $this->uri->segment(2),
        //            "url"           => current_url(),
        //        );
        //        $logID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));

        $_SERVER['REMOTE_ADDR'] != "127.0.0.1" ? writeLog("Login", "Login Page", "auth") : "";
        //endregion

        $temp = array(
            "mode" => "forms",
            "formAttributes" => $formAttributes,
            "remember" => $checked,
            "defaultUserID" => $c_uid,
            "defaultPwd" => $c_pwd,
            "goTo" => $param,
            "ses_ended" => $temp_err,
        );
        $data = array(
            "mode" => "forms",
            "formAttributes" => $formAttributes,
            "remember" => $checked,
            "defaultUserID" => $c_uid,
            "defaultPwd" => $c_pwd,
            "goTo" => $param,
            "ses_ended" => $temp_err,
            "temp" => $temp,
            "errMsg" => $this->session->errMsg,
        );
        $this->load->view('login', $data);
    }

    public function authCheck()
    {
        matiHEre("untuk multi cabang siapin disini ".__LINE__.":: ".__FUNCTION__);
        $validCounter = 0;

        $nama_login = $this->input->post('nama');
        $post_password = $this->input->post('password');
        $goto_e = blobDecode($this->input->post('goto'));
        $bypass = $this->input->post('bypass');
        // arrPrint($nama_login);

        //trigger depresiasi
        $this_day = date("d");
        $tgl_mulai = "20";
        $tgl_akhir = "28";

        if ($this_day >= $tgl_mulai && $this_day <= $tgl_akhir) {
            //AREA ini akan di eksekusi
            $this->load->model("Mdls/" . "MdlActivityLog");
            $hTmp = new MdlActivityLog();
            $this->db->where("DATE(dtime)=CURDATE() AND uname!=''");
            $this->db->order_by("dtime ASC");
            $tmpLog = $hTmp->lookupAll()->result();

            if (sizeof($tmpLog) == 0) {
                echo "<script>
                          // var autodepresewa   = top.window.open('" . base_url() . "asetmanagement/AutoDepresiasiSewa?fromlogin=1','AutoDepresiasiSewa','toolbar=no,status=no,menubar=no,scrollbars=no,resizable=no,left=10000, top=10000, width=10, height=10, visible=none', '');
                          // var autodepre       = top.window.open('" . base_url() . "asetmanagement/AutoDepresiasi?fromlogin=1','AutoDepresiasi','toolbar=no,status=no,menubar=no,scrollbars=no,resizable=no,left=10000, top=10000, width=10, height=10, visible=none', '');
                      </script>";
            }
        }

        $authProps = array(
//            "MdlUser",
//            "MdlPos",
            "MdlEmployee",
//            "MdlEmployee__shadow",
//            "MdlEmployeeCabang",
//            "MdlEmployeeCabang__shadow",
//            "MdlEmployeeGudang",
//            "MdlEmployeeGudangFase",
//            "MdlEmployeeFreelanceCabang",
//            "MdlEmployeeKirim",
        );

        //==pairers
        //company profile grap jenis usaha
        $this->load->Model("Mdls/MdlCompany");
        $comPro = new MdlCompany();
        $tmpCompanyProfile = $comPro->lookupAll()->result();
        // cekLime($this->db->last_query());
        // arrPrint($tmpCompanyProfile);
        $companyProfil = array();
        $jn_usaha = $tmpCompanyProfile[0]->jenis_usaha;
        foreach ($tmpCompanyProfile as $rows) {
            $companyProfil["jenis_usaha"] = $rows->jenis_usaha;
        }
        // arrPrint($tmpCompanyProfile);
        $this->ci = $CI =& get_instance();
        //load dari config item pair pajak
        $masterPpnData = $CI->config->item("pairPajak");
        $masterPPN = $masterPpnData[$jn_usaha]["value"]["default"];
        // arrPrint($masterPPN);
        // matiHere();

        $cabangs = array();
        $this->load->model("Mdls/MdlCabang");
        $cab = new MdlCabang();
        $tmpc = $cab->lookupAll()->result();
        if (sizeof($tmpc) > 0) {
            foreach ($tmpc as $row) {
                $_id = $row->id;
                $cabangs[$_id] = $row->nama;
            }
        }
        $divs = array();
        $this->load->model("Mdls/MdlDiv");
        $cab = new MdlDiv();
        $tmpc = $cab->lookupAll()->result();
        if (sizeof($tmpc) > 0) {
            foreach ($tmpc as $row) {
                $divs[$row->id] = $row->nama;
            }
        }

        //loader data membership cabang
        $this->load->model("Mdls/MdlEmployeeMembershipCabang");
        $mc = new MdlEmployeeMembershipCabang();
        $gudangs = array();
//        $this->load->model("Mdls/MdlGudang");
//        $cab = new MdlGudang();
//        $tmpc = $cab->lookupAll()->result();
//        if (sizeof($tmpc) > 0) {
//            foreach ($tmpc as $row) {
//                $_id = $row->id;
//                $gudangs[$_id] = $row->nama;
//            }
//        }

        $loginProp = array();
        $nameField = "nama_login";
        $dasboarCabang = false;
        foreach ($authProps as $mdlName) {
            cekHitam(__LINE__.":: ".$mdlName);
            $this->load->model("Mdls/" . $mdlName);
            $u = new $mdlName();
            if ($bypass == 'on') {
                $tmpUser = $u->lookupByCondition(array(
                    $nameField => $nama_login,
                ))->result();
            } else {
                $tmpUser = $u->lookupByCondition(array(
                    $nameField => $nama_login,
                    "password" => md5($post_password),
                    "status" => "1",
                ))->result();
            }
                        cekmerah($this->db->last_query());
            if (sizeof($tmpUser) > 0) {
                $userProp = $tmpUser[0];
                $login_fail = $userProp->login_fail;
                $email = $userProp->email;
                if ($login_fail == 10) {
                    $arrAlert = array(
                        "type" => "warning",
                        "title" => "Passsword Reseted",
                        "html" => "please check your email and follow the link have been  sent to <b class='text-info'>$email</b> for confirm your account",
                    );
                    echo swalAlert($arrAlert);
                    die();
                }

                $arrAlert = array(
                    "html" => "<img src='" . base_url() . "public/images/sys/loader-100.gif'> <br>Authenticating.<br>Please wait...<br>",
                    "showConfirmButton" => false,
                    "allowOutsideClick" => false,

                );
                // echo swalAlert($arrAlert);
                //
                foreach ($userProp as $field => $item) {
                    $$field = $item;
                }


                $sessionSwappers = array(
                    "id",
                    "nama_login",
                    "nama",
                    "phpsess_dtime",
                    "phpsessid",
                    "phpsessid",
                    "status",
                    "jenis",
                    "debuger",
//                    "cabang_id",
//                    "gudang_id",
                    "div_id",
                    "ghost",
                    "employee_type",
                    "jenis_usaha",
                );
                $sessionUpdaters = array(
                    'phpsess_dtime' => dtimeNow(),
                    'phpsessid' => $_COOKIE['ci_session'],
                    'ipadd' => $_SERVER['REMOTE_ADDR'],
                    'devices' => $_SERVER['HTTP_USER_AGENT'],
                    'membership' => unserialize(base64_decode($userProp->membership)),
                    'longitude' => "",
                    'lattitude' => "",
                    'accuracy' => "",
                    'status_login' => "1",
                );
                $tableUpdaters = array(
                    "phpsessid" => "phpsesid",
                    "phpsess_dtime" => "phpses_dtime",
                    "php_session" => "php_session",
                    "ipadd" => "ipadd",
                    "devices" => "devices",
                    'status_login' => "status_login",
                );
                //region tambahan logic pengenal akses single login bisa akses semua cabang/pusat
                $cabangs = array();
                $gudangs = array();
                $mc->addFilter("employee_id='" . $tmpUser[0]->id . "'");
                $tempMemberCabang = $mc->lookUpAll()->result();
                switch (count($tempMemberCabang)) {

                    case "1":
                        //terelasi dengan satu cabang langsung beri akses

                        foreach ($tempMemberCabang as $tempMemberCabang_datas) {
                            $cabangs = array(
                                "cabang_id" => $tempMemberCabang_datas->cabang_id,
                                "cabang_nama" => $tempMemberCabang_datas->cabang_nama,
                            );

                            $gudSpec_cabang = getDefaultWarehouseID($tempMemberCabang_datas->cabang_id);
//                            arrPrint($gudSpec_cabang);
                        }

//                        arrPrint($tempMemberCabang);
//                        matiHEre(__LINE__);

                        break;
                    case "0":
                    default:
                        $cabangs = array();
                        //multi cabang
                        $dasboarCabang = true;
                        break;
                }

                //endregion


                $pakaiini = 0;
                if ($pakaiini == 1) {
                    $sessionUniqUpdaters = array(
                        "MdlUser" => array(
                            'gudang_id' => -999,
                            'gudang_nama' => 'no warehouse',
                            'cabang_id' => "-1",
//                        'cabang_nama' => "pusat",
                            'cabang_nama' => isset($cabangs[$userProp->cabang_id]) ? $cabangs[$userProp->cabang_id] : "",
                            'employee_type' => $userProp->employee_type,
                        ),
                        "MdlEmployee" => array(
                            'gudang_id' => $gudSpec_pusat['gudang_id'],
                            'gudang_nama' => $gudSpec_pusat['gudang_nama'],
                            'cabang_id' => "-1",
//                        'cabang_nama' => "pusat",
                            'cabang_nama' => isset($cabangs[$userProp->cabang_id]) ? $cabangs[$userProp->cabang_id] : "",
                            //                        'div_nama' => "div " . $userProp->div_id,
                            'div_nama' => $divs[$userProp->div_id],
                            'employee_type' => $userProp->employee_type,
                        ),
                        "MdlEmployee__shadow" => array(
                            'gudang_id' => $gudSpec_pusat['gudang_id'],
                            'gudang_nama' => $gudSpec_pusat['gudang_nama'],
                            'cabang_id' => "-1",
//                        'cabang_nama' => "pusat",
                            'cabang_nama' => isset($cabangs[$userProp->cabang_id]) ? $cabangs[$userProp->cabang_id] : "",
                            //                        'div_nama' => "div " . $userProp->div_id,
                            'div_nama' => $divs[$userProp->div_id],
                            'employee_type' => $userProp->employee_type,
                        ),
                        "MdlEmployeeCabang" => array(
                            'gudang_id' => $gudSpec_cabang['gudang_id'],
                            'gudang_nama' => $gudSpec_cabang['gudang_nama'],
                            //                        'cabang_nama' => "branch " . $userProp->cabang_id,
                            'cabang_nama' => isset($cabangs[$userProp->cabang_id]) ? $cabangs[$userProp->cabang_id] : "branch " . $userProp->cabang_id,
                            //                        'div_nama' => "div " . $userProp->div_id,
                            'div_nama' => $divs[$userProp->div_id],
                            'employee_type' => $userProp->employee_type,
                        ),
                        "MdlEmployeeCabang__shadow" => array(
                            'gudang_id' => $gudSpec_cabang['gudang_id'],
                            'gudang_nama' => $gudSpec_cabang['gudang_nama'],
                            //                        'cabang_nama' => "branch " . $userProp->cabang_id,
                            'cabang_nama' => isset($cabangs[$userProp->cabang_id]) ? $cabangs[$userProp->cabang_id] : "branch " . $userProp->cabang_id,
                            //                        'div_nama' => "div " . $userProp->div_id,
                            'div_nama' => $divs[$userProp->div_id],
                            'employee_type' => $userProp->employee_type,
                        ),
                        "MdlPos" => array(

                            'gudang_id' => $gudSpec_POS['gudang_id'],
                            'gudang_nama' => $gudSpec_POS['gudang_nama'],
                            //                        'cabang_nama' => "branch " . $userProp->cabang_id,
                            'cabang_nama' => isset($cabangs[$userProp->cabang_id]) ? $cabangs[$userProp->cabang_id] : "branch " . $userProp->cabang_id,
                            //                        'div_nama' => "div " . $userProp->div_id,
                            'div_nama' => $divs[$userProp->div_id],
                            'employee_type' => $userProp->employee_type,
                        ),
                        "MdlEmployeeGudang" => array(
                            'gudang_id' => $userProp->gudang_id,
                            'gudang_nama' => isset($gudangs[$userProp->gudang_id]) ? $gudangs[$userProp->gudang_id] : "warehouse " . $userProp->gudang_id,
                            //                        'cabang_nama' => "branch " . $userProp->cabang_id,
                            'cabang_nama' => isset($cabangs[$userProp->cabang_id]) ? $cabangs[$userProp->cabang_id] : "branch " . $userProp->cabang_id,
                            //                        'div_nama' => "div " . $userProp->div_id,
                            'div_nama' => $divs[$userProp->div_id],
                            'employee_type' => $userProp->employee_type,
                        ),
                        "MdlEmployeeGudangFase" => array(
                            'gudang_id' => $userProp->gudang_id,
                            'gudang_nama' => isset($gudangs[$userProp->gudang_id]) ? $gudangs[$userProp->gudang_id] : "warehouse " . $userProp->gudang_id,
                            //                        'cabang_nama' => "branch " . $userProp->cabang_id,
                            'cabang_nama' => isset($cabangs[$userProp->cabang_id]) ? $cabangs[$userProp->cabang_id] : "branch " . $userProp->cabang_id,
                            //                        'div_nama' => "div " . $userProp->div_id,
                            'div_nama' => $divs[$userProp->div_id],
                            'employee_type' => $userProp->employee_type,
                        ),
                        "MdlEmployeeFreelanceCabang" => array(
                            'gudang_id' => $gudSpec_cabang['gudang_id'],
                            'gudang_nama' => $gudSpec_cabang['gudang_nama'],
                            //                        'cabang_nama' => "branch " . $userProp->cabang_id,
                            'cabang_nama' => isset($cabangs[$userProp->cabang_id]) ? $cabangs[$userProp->cabang_id] : "branch " . $userProp->cabang_id,
                            //                        'div_nama' => "div " . $userProp->div_id,
                            'div_nama' => $divs[$userProp->div_id],
                            'employee_type' => $userProp->employee_type,
                        ),
                        "MdlEmployeeKirim" => array(
                            'gudang_id' => $gudSpec_cabang['gudang_id'],
                            'gudang_nama' => $gudSpec_cabang['gudang_nama'],
                            'cabang_nama' => isset($cabangs[$userProp->cabang_id]) ? $cabangs[$userProp->cabang_id] : "branch " . $userProp->cabang_id,
                            'div_nama' => $divs[$userProp->div_id],
                            'employee_type' => $userProp->employee_type,
                        ),
                    );
                } else {
                    $sessionUniqUpdaters = array(
                        "MdlEmployee" => array(
                            'gudang_id' => isset($gudSpec_cabang['gudang_id']) ? $gudSpec_cabang['gudang_id'] : "",
                            'gudang_nama' => isset($gudSpec_cabang['gudang_id']) ? $gudSpec_cabang['gudang_nama'] : "",
                            'cabang_id' => isset($cabangs["cabang_id"]) ? $cabangs["cabang_id"] : "",
//                        'cabang_nama' => "pusat",
                            'cabang_nama' => isset($cabangs["cabang_nama"]) ? $cabangs["cabang_nama"] : "",
                            //                        'div_nama' => "div " . $userProp->div_id,
                            'div_nama' => $divs[$userProp->div_id],
                            'employee_type' => $userProp->employee_type,
                        ),

                    );
                }
                foreach ($sessionSwappers as $key) {
                    $loginProp[$key] = isset($userProp->$key) ? $userProp->$key : "";
                }

//                arrPrint($loginProp);
//                matiHere();

                foreach ($sessionUpdaters as $key => $val) {
                    $loginProp[$key] = $val;
                }

                if (isset($sessionUniqUpdaters[$mdlName]) && sizeof($sessionUniqUpdaters[$mdlName]) > 0) {
                    foreach ($sessionUniqUpdaters[$mdlName] as $key => $val) {
                        $loginProp[$key] = $val;
                    }
                }
//                mati_disini($loginProp['gudang_id'] . " $mdlName");
                foreach ($companyProfil as $keys => $vals) {
                    $loginProp[$keys] = $vals;
                }
                foreach ($masterPPN as $keyPpn => $value_ppn) {
                    $loginProp[$keyPpn] = $value_ppn;
                }
                // arrPrint($loginProp);
                // matiHEre();

                //==force membership
                if (!is_array($loginProp['membership'])) {
                    $loginProp['membership'] = array();
                }

                $this->session->login = $loginProp;


                $zippedSessions = base64_encode(serialize($this->session->login));
                setcookie("uprop", $zippedSessions, time() + 31356000);

                if ($this->input->post('remember') == "on") {
                    setcookie("uid", $nama_login, time() + 31356000);
                    setcookie("pwd", base64_encode($post_password), time() + 31356000);
                    echo lgShowAlert("remembered result: " . print_r($_COOKIE, true));
                } else {
                    setcookie("uid", NULL, time());
                    setcookie("pwd", NULL, time());
                }
                $validCounter++;


                //region update data employee session idnya
                $temp = array();
                foreach ($tableUpdaters as $kolom => $alias) {
                    if (isset($loginProp[$kolom])) {
                        $temp[$kolom] = $loginProp[$kolom];
                    }
                }
                if (sizeof($temp) > 0) {
                    $condite = array("id" => $loginProp['id']);
                    $u->updateData($condite, $temp);
                }
                //endregion


            }
        }

// cekAlert();
//        arrPrint($this->session->login);
//        mati_disini("cek");
        //        die();
        if ($validCounter < 1) {
            $arrSwal = array(
                "title" => "Login failed",
                "html" => "Login Details Incorrect. Please try again.",
                "type" => "warning",
            );
            echo swalAlert($arrSwal);
            die();
        } else {
            $this->db->trans_start();
            //region normalisasi loker stok
            //===bersihkan & kembalikan locker2 yang dikunci orang ini
            // $this->load->model("Mdls/MdlLockerStock");
            // $this->load->model("Coms/ComLockerStock");
            // $this->load->model("Mdls/MdlLockerStockSupplies");
            // $this->load->model("Coms/ComLockerStockSupplies");
            // $this->load->model("Mdls/MdlLockerStockAktiva");
            // $this->load->model("Coms/ComLockerStockAktiva");
            // $this->load->model("Mdls/MdlLockerTransaksi");
            // $this->load->model("Coms/ComLockerTransaksi");

            //region locker finish goods
            // $c = new MdlLockerStock();
            // $c->addFilter("stock_locker.jenis='produk'");
            // $c->addFilter("state='hold'");
            // $c->addFilter("jumlah>'0'");
            // $c->addFilter("cabang_id=" . $this->session->login['cabang_id']);
            // $c->addFilter("gudang_id=" . $this->session->login['gudang_id']);
            // $c->addFilter("oleh_id=" . $this->session->login['id']);
            // $c->addFilter("transaksi_id='0'");
            // $tmpC = $c->lookupAll()->result();
            //
            // if (sizeof($tmpC) > 0) {
            //
            //     $sentParams = array();
            //     $sentParams2 = array();
            //     foreach ($tmpC as $row) {
            //         $pID = $row->produk_id;
            //         $jml = $row->jumlah;
            //
            //         $subParams = array(
            //             "static" => array(
            //                 "cabang_id" => $row->cabang_id,
            //                 "gudang_id" => $row->gudang_id,
            //                 "jenis" => $row->jenis,
            //                 "state" => "hold",
            //                 "jumlah" => -($jml),
            //                 "produk_id" => $pID,
            //                 "oleh_id" => $this->session->login['id'],
            //                 "transaksi_id" => 0,
            //
            //             ),
            //         );
            //         $sentParams[] = $subParams;
            //
            //         $subParams2 = array(
            //             "static" => array(
            //                 "cabang_id" => $row->cabang_id,
            //                 "gudang_id" => $row->gudang_id,
            //                 "jenis" => $row->jenis,
            //                 "state" => "active",
            //                 "jumlah" => $jml,
            //                 "produk_id" => $pID,
            //                 "oleh_id" => 0,
            //                 "transaksi_id" => 0,
            //
            //             ),
            //         );
            //         $sentParams2[] = $subParams2;
            //
            //     }
            //     $cs = new ComLockerStock();
            //     $cs->pair($sentParams) or die("Unable to pair locker for releasing");
            //     $cs->exec();
            //     //
            //     $cs = new ComLockerStock();
            //     $cs->pair($sentParams2) or die("Unable to pair locker for putting back");
            //     $cs->exec();
            //
            // }
            //endregion

            //region locker finish goods
            // $s = new MdlLockerStockSupplies();
            // //            $s->addFilter("jenis='supplies'");
            // $s->addFilter("stock_locker.jenis='supplies'");
            // $s->addFilter("state='hold'");
            // $s->addFilter("cabang_id=" . $this->session->login['cabang_id']);
            // $s->addFilter("gudang_id=" . $this->session->login['gudang_id']);
            // $s->addFilter("oleh_id=" . $this->session->login['id']);
            // $s->addFilter("transaksi_id='0'");
            // $s->addFilter("jumlah>'0'");
            // $tmpS = $s->lookupAll()->result();
            //
            // if (sizeof($tmpS) > 0) {
            //     $sentParams = array();
            //     $sentParams2 = array();
            //     foreach ($tmpS as $row) {
            //         $pID = $row->produk_id;
            //         $jml = $row->jumlah;
            //
            //         $subParams = array(
            //             "static" => array(
            //                 "cabang_id" => $row->cabang_id,
            //                 "gudang_id" => $row->gudang_id,
            //                 "jenis" => $row->jenis,
            //                 "state" => "hold",
            //                 "jumlah" => -($jml),
            //                 "produk_id" => $pID,
            //                 "oleh_id" => $this->session->login['id'],
            //                 "transaksi_id" => 0,
            //
            //             ),
            //         );
            //         $sentParams[] = $subParams;
            //
            //         $subParams2 = array(
            //             "static" => array(
            //                 "cabang_id" => $row->cabang_id,
            //                 "gudang_id" => $row->gudang_id,
            //                 "jenis" => $row->jenis,
            //                 "state" => "active",
            //                 "jumlah" => $jml,
            //                 "produk_id" => $pID,
            //                 "oleh_id" => 0,
            //                 "transaksi_id" => 0,
            //
            //             ),
            //         );
            //         $sentParams2[] = $subParams2;
            //
            //     }
            //     $ss = new ComLockerStockSupplies();
            //     $ss->pair($sentParams) or die("Unable to pair locker for releasing");
            //     $ss->exec();
            //     //
            //     $ss = new ComLockerStockSupplies();
            //     $ss->pair($sentParams2) or die("Unable to pair locker for putting back");
            //     $ss->exec();
            // }
            //endregion

            //region locker asset tetap
            // $s = new MdlLockerStockAktiva();
            // //        $s->addFilter("jenis='supplies'");
            // $s->addFilter("stock_locker.jenis='aktiva'");
            // $s->addFilter("state='hold'");
            // $s->addFilter("cabang_id=" . $this->session->login['cabang_id']);
            // $s->addFilter("gudang_id=" . $this->session->login['gudang_id']);
            // $s->addFilter("oleh_id=" . $this->session->login['id']);
            // $s->addFilter("transaksi_id='0'");
            // $tmpS = $s->lookupAll()->result();
            // if (sizeof($tmpS) > 0) {
            //
            //     $sentParams = array();
            //     $sentParams2 = array();
            //     foreach ($tmpS as $row) {
            //         $pID = $row->produk_id;
            //         $jml = $row->jumlah;
            //
            //         //==param untuk melepas stok HOLD
            //         $subParams = array(
            //             "static" => array(
            //                 "cabang_id" => $row->cabang_id,
            //                 "gudang_id" => $row->gudang_id,
            //                 "jenis" => $row->jenis,
            //                 "state" => "hold",
            //                 "jumlah" => -($jml),
            //                 "produk_id" => $pID,
            //                 "oleh_id" => $this->session->login['id'],
            //                 "transaksi_id" => 0,
            //             ),
            //         );
            //         $sentParams[] = $subParams;
            //
            //         //==param untuk mengembalikan stok aktiv
            //         $subParams2 = array(
            //             "static" => array(
            //                 "cabang_id" => $row->cabang_id,
            //                 "gudang_id" => $row->gudang_id,
            //                 "jenis" => $row->jenis,
            //                 "state" => "active",
            //                 "jumlah" => $jml,
            //                 "produk_id" => $pID,
            //                 "oleh_id" => 0,
            //                 "transaksi_id" => 0,
            //
            //             ),
            //         );
            //         $sentParams2[] = $subParams2;
            //
            //     }
            //     $ss = new ComLockerStockAktiva();
            //     $ss->pair($sentParams) or die("Unable to pair locker for releasing");
            //     $ss->exec();
            //     //
            //     $ss = new ComLockerStockAktiva();
            //     $ss->pair($sentParams2) or die("Unable to pair locker for putting back");
            //     $ss->exec();
            // }
            //endregion

            //region locker transaksi
            // $s = new MdlLockerTransaksi();
            // $s->addFilter("stock_locker_transaksi.jenis='transaksi'");
            // $s->addFilter("stock_locker_transaksi.jenis_locker='transaksi'");
            // $s->addFilter("state='hold'");
            // $s->addFilter("cabang_id=" . $this->session->login['cabang_id']);
            // $s->addFilter("oleh_id=" . $this->session->login['id']);
            // $s->addFilter("transaksi_id>'0'");
            // $s->addFilter("jumlah>'0'");
            // $tmpS = $s->lookupAll()->result();
            // if (sizeof($tmpS) > 0) {
            //
            //     $sentParams = array();
            //     $sentParams2 = array();
            //     foreach ($tmpS as $row) {
            //         $pID = $row->produk_id;
            //         $jml = $row->jumlah;
            //
            //         //==param untuk melepas stok HOLD
            //         $subParams = array(
            //             "static" => array(
            //                 "cabang_id" => $row->cabang_id,
            //                 "gudang_id" => 0,
            //                 "jenis" => $row->jenis,
            //                 "jenis_locker" => $row->jenis_locker,
            //                 "state" => "hold",
            //                 "jumlah" => -($jml),
            //                 "produk_id" => $pID,
            //                 "oleh_id" => $this->session->login['id'],
            //                 "transaksi_id" => $row->transaksi_id,
            //             ),
            //         );
            //         $sentParams[] = $subParams;
            //
            //         //==param untuk mengembalikan stok aktiv
            //         $subParams2 = array(
            //             "static" => array(
            //                 "cabang_id" => $row->cabang_id,
            //                 "gudang_id" => 0,
            //                 "jenis" => $row->jenis,
            //                 "jenis_locker" => $row->jenis_locker,
            //                 "state" => "active",
            //                 "jumlah" => $jml,
            //                 "produk_id" => $pID,
            //                 "oleh_id" => 0,
            //                 "transaksi_id" => $row->transaksi_id,
            //             ),
            //         );
            //         $sentParams2[] = $subParams2;
            //
            //     }
            //     $ss = new ComLockerTransaksi();
            //     $ss->pair($sentParams) or die("Unable to pair locker for releasing");
            //     $ss->exec();
            //     //
            //     $ss = new ComLockerTransaksi();
            //     $ss->pair($sentParams2) or die("Unable to pair locker for putting back");
            //     $ss->exec();
            // }
            //endregion

            $this->load->library("locker");
            $lls = new Locker();
            $lls->setLoginSessions($_SESSION['login']);
            $lls->normalisasiStok();
            //endregion

            // region locker kas
            $this->load->library("CheckerLocker");
            $cl = New CheckerLocker();
            $cl->setCabangId($this->session->login['cabang_id']);
            $cl->setExecute(true);
            $result = $cl->lockerKas();
            // endregion


            //region writelog

            //            $this->load->model("Mdls/" . "MdlActivityLog");
            //            $hTmp = new MdlActivityLog();
            //            $hTmp->setFilters(array());
            //            $tmpHData = array(
            //                "title"         => "Login",
            //                "sub_title"     => "Authenticating your credential..",
            //                "uid"           => $this->session->login['id'],
            //                "uname"         => $this->session->login['nama'],
            //                "dtime"         => date("Y-m-d H:i:s"),
            //                "transaksi_id"  => 0,
            //                "deskripsi_old" => "",
            //                "deskripsi_new" => base64_encode(serialize($this->session->login)),
            //                "jenis"         => "",
            //                "ipadd"         => $_SERVER['REMOTE_ADDR'],
            //                "devices"       => $_SERVER['HTTP_USER_AGENT'],
            //                "category"      => "auth",
            //                "controller"    => $this->uri->segment(1),
            //                "method"        => $this->uri->segment(2),
            //                "url"           => current_url(),
            //
            //            );
            //            $logID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            writeLog("Login", "Authenticating credentials..", "auth");
            //endregion
            $this->db->trans_complete() or die("Unable to commit transaction");
        }


        //region group landings
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        $defaultLandPages = array();
        if (sizeof($mems) > 0) {
            foreach ($mems as $gID) {
                if (isset($this->config->item("groupLandingPages")[$gID])) {
                    $defaultLandPages[] = base_url() . $this->config->item("groupLandingPages")[$gID];
                }
            }
        }
        //endregion


        //        if (sizeof($defaultLandPages) > 0) {
        //            echo "<script>top.location.href='" . $defaultLandPages[0] . "';</script>";
        //        } else {
        //            if (strlen($goto_e) > 10) {
        //                echo "<script>top.location.href='" . "$goto_e';</script>";
        //            } else {
        //                echo "<script>top.location.href='" . base_url() . "';</script>";
        //            }
        //        }

        //untuk redirect landing
        if($dasboarCabang){
            //landingkan untuk milih cabang
            matiHEre("ini untuk redirect ke ui pilih cabang");
        }
        else{
            if (strlen($goto_e) > 10) {

                $whiteList = array(
                    base_url() . "pembelian/Transaksi/index/461",
                );

                if (in_array($goto_e, $whiteList)) {
                    $_SESSION['login']['forceMobile'] = 1;
                    echo "<script>top.location.href='" . "$goto_e';</script>";
                } else {
                    echo "<script>top.location.href='" . base_url() . "';</script>";
                }
            } else {
                echo "<script>top.location.href='" . base_url() . "';</script>";
            }
        }



    }

}