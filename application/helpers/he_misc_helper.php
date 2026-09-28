<?php
/**
 * Created by thomas Maya Graha Kencana.
 * Date: 30/06/18
 * Time: 20:02
 */
function str_clean_chars($string)
{
    $str = htmlspecialchars($string, ENT_QUOTES);
    $str = trim($str);

    return $str;
}

// region untuk keperluan debuger
function show_debuger()
{
    //    $debuger = true;
    $debuger = false;
    if ($debuger == true) {
        $_GET['debuger'] = 1;
    }
    else {
        if (isset($_SESSION['debuger'])) {
            // echo ("ada session debuger");
        }
        else {
            // echo("tidak ada session debuger");
            $_GET['debuger'] = 0;
        }
    }
    // echo("$debuger");
    // arrPrint($_SESSION['debuger']);

    $sesDebug = !isset($_SESSION['debuger']) ? "" : $_SESSION['debuger'];
    $getDebug = !isset($_GET['debuger']) ? "" : $_GET['debuger'];

    // print_r($_SESSION['login']['debuger']);
    //    echo sesEmployeeJenis() . "$sesDebug === $getDebug ****";

    $var = 0;
    if (!isset($_GET['debuger'])) {
        if (($sesDebug == 1)) {
            $var = 1;
            //            echo "tampil";
        }
        //        cekHere("noget");
        if (isset($_SESSION['login']['debuger'])) {
            $_SESSION["debuger"] = $_SESSION['login']['debuger'];
        }
        // echo("ok");
    }
    elseif (isset($_GET['debuger'])) {
        if ($getDebug == 1) {
            if (!isset($_SESSION['login'])) {

                $_SESSION['debuger'] = 1;
            }
            else {
                $_SESSION['login']['debuger'] = 1;
                $_SESSION['debuger'] = $_SESSION['login']['debuger'];
            }
        }
        else {
            if (!isset($_SESSION['login'])) {

                $_SESSION['debuger'] = 0;
            }
            else {
                $_SESSION['login']['debuger'] = 0;
                $_SESSION['debuger'] = $_SESSION['login']['debuger'];
            }
        }

        if (($sesDebug == 1) and ($getDebug != 1)) {
            $var = 0;
        }
        elseif (($sesDebug == 1) or ($getDebug == 1)) {
            $var = 1;
        }
        //        cekHere("ada getnya");
        //        echo "ada getnya";
    }
    else {
        $var = 0;
        //        cekHere("lainnya");
    }

    /* ---------------------------------------------------------------
     * hidupkan ip untuk membuat debuger selalu off
     * ---------------------------------------------------------------*/
    $force_debugers_off = array(
        // "192.168.5.8",
        // "192.168.5.7",
        // "192.168.5.4",
         "192.168.5.3",
        // "192.168.5.1",
        // "192.168.1.39",
        // MGK_LIVE,
        // "202.65.117.72",
        // "202.65.117.80", /// beta
    );
    /* -------------------------------------------------------------
     * debuger tidak bisa hidup diluar maya
     * -------------------------------------------------------------*/
    if (ipadd() != MGK_LIVE) {
//         $var = 0;
    }
    /* ----------------------------------------------------------
     * hanya berlakuku untuk network maya
     * ----------------------------------------------------------*/
    if (ipadd() == MGK_LIVE) {
        // $var = 1;
    }

    if (in_array(ipadd(), $force_debugers_off)) {
         $var = 1;
//        $var = 0;
    }
    else {
        //   $var = 1;
    }
    // ------------------------------------------------------
//    $var = 1;
    return $var;
}

function matiDisini($param = "------ mati disini ------")
{
    $dtime_now = dtimeNow() . " " . my_host();
    $swalAlert = array(
        "html" => $param . "<br><small class='meta'>$dtime_now</small>",
    );
    echo swalAlert($swalAlert);

    $param = "<div style='border: 1px dashed #ff4b33;background:#FDF5CE;padding: 10px 5px;color: red;text-align: center;font-family: consolas;text-wrap: normal;'>$param</div>";

    return die($param);
}

function matiDisiniOriginal($param = "------ mati disini ------")
{
    $dtime_now = dtimeNow();
    $host = $_SERVER['HTTP_HOST'];
    $swalAlert = array(
        "html" => $param . "<br><small class='meta'>$dtime_now $host</small>",
    );
    echo swalAlertOriginal($swalAlert);

    $param = "<div style='border: 1px dashed #ff4b33;background:#FDF5CE;padding: 10px 5px;color: red;text-align: center;font-family: consolas;text-wrap: normal;'>$param</div>";

    return die($param);
}

function matiAlert($param = "------ mati disini ------", $line = "")
{
    $dtime_now = dtimeNow();
    $host = $_SERVER['HTTP_HOST'];
    $lines = $line != "" ? "@" . $line : "";
    $swalAlert = array(
        "html" => $param . "<br><small class='meta'>$dtime_now $host $lines</small>",
    );
    echo swalAlertOriginal($swalAlert);

    $param = "<div style='border: 1px dashed #ff4b33;background:#FDF5CE;padding: 10px 5px;color: red;text-align: center;font-family: consolas;text-wrap: normal;'>$param $lines</div>";

    if (show_debuger() == 1) {
        echo $param;
        flush();
        ob_flush();
    }
    return die();
}

function mati_disini($param = "------ mati disini ------")
{
    // $dtime_now = dtimeNow() ." ". my_host();
    // $swalAlert = array(
    //     "html" => $param . "<br><small>$dtime_now</small>",
    // );
    // echo swalAlert($swalAlert);
    //
    // $param = "<div style='border: 1px dashed #ff4b33;background:#FDF5CE;padding: 10px 5px;color: red;text-align: center;font-family: consolas;text-wrap: normal;'>$param</div>";
    //
    // return die($param);
    return matiDisini($param);
}

function mati_disini_linkmutasi($param = "------ mati disini ------", $link = "")
{
    $dtime_now = dtimeNow() . " " . my_host();
    $param_new = $param;
    $param_new .= " Klik untuk melihat mutasi <a href='$link' target='_blank'><span class='fa fa-clock-o'></span></a>";
    $swalAlert = array(
        "html" => $param_new . "<br><small class='meta'>$dtime_now</small>",
    );
    echo swalAlert($swalAlert);

    $param = "<div style='border: 1px dashed #ff4b33;background:#FDF5CE;padding: 10px 5px;color: red;text-align: center;font-family: consolas;text-wrap: normal;'>$param_new</div>";

    return die($param);
}

function mati_disini_original($param = "------ mati disini ------")
{
    // $dtime_now = dtimeNow();
    // $swalAlert = array(
    //     "html" => $param . "<br><small>$dtime_now</small>",
    // );
    // echo swalAlert($swalAlert);
    //
    // $param = "<div style='border: 1px dashed #ff4b33;background:#FDF5CE;padding: 10px 5px;color: red;text-align: center;font-family: consolas;text-wrap: normal;'>$param</div>";
    //
    // return die($param);
    return matiDisiniOriginal($param);
}

function matiHere($param = "------ mati disini ------")
{
    // $dtime_now = dtimeNow() ." ". my_host();
    //
    // $swalAlert = array(
    //     "html" => $param . "<br><small class='meta'>$dtime_now</small>",
    // );
    // echo swalAlert($swalAlert);

    // $param = "<div style='border: 1px dashed #ff4b33;background:#FDF5CE;padding: 10px 5px;color: red;text-align: center;font-family: consolas;text-wrap: normal;'>$param</div>";
    //
    // return die($param);
    return matiDisini($param);
}

function cekAlert($param = "------ alert ------")
{
    $swalAlert = array(
        "html" => $param,
    );
    echo swalAlert($swalAlert);

    $param = "<div style='border: 1px dashed #ff4b33;background:#FDF5CE;padding: 10px 5px;color: red;text-align: center;font-family: consolas;text-wrap: normal;'>$param</div>";

    // return die($param);
}

function cekHere($param = "halooo.")
{
    if (is_array($param)) {
        if (show_debuger() == 1) {
            echo "<div style='margin-left: 100px'>";
            echo "<pre style='background: #FFF0F0;'>";
            print_r($param);
            echo "</pre>";
            echo "</div>";
        }
    }
    else {

        $param = "<div style='border: 1px dashed #fff;background:#FFF0F0;padding: 10px 5px;color: red;margin: 10px auto;text-align: center;font-family: consolas;text-wrap: normal;'>$param</div>";
        if (show_debuger() == 1) {

            echo $param;
            flush();
            ob_flush();
        }
    }
}

function cekHijau($param = "halooo ijau(s)")
{
    $array = is_array($param) ? $param : "";

    if (show_debuger() == 1) {
        if ($array != "") {
            echo "<pre style='background: #EDFBF1;padding: 5px;color: #008700;'>";
            print_r($array);
            echo "</pre>";
        }
        else {
            $param = "<div style='border: 0.5px dashed #B6E5B8;background:#EDFBF1;padding: 5px;
        color: #008700;margin: 10px auto;text-align: center;
        font-family: consolas;text-wrap: normal;'>$param</div>";

            echo $param;
        }

        flush();
        ob_flush();
    }
}

function cekBiru($param = "halooo biru(s)")
{

    if (show_debuger() == 1) {
        if (is_array($param)) {
            echo "<pre style='background: #39b3d7;padding: 5px;color: #ffffcc;'>";
            print_r($param);
            echo "</pre>";
        }
        else {
            $param = "<div style='border: 0.5px dashed #ffffff;background:#39b3d7;padding: 5px;color: #ffffcc;margin: 10px auto;text-align: center;font-family: consolas;text-wrap: normal;'>$param</div>";
            echo $param;
        }

        flush();
        ob_flush();
    }
}

function cekMerah($param = "halooo merah")
{
    if (is_array($param)) {
        if (show_debuger() == 1) {
            echo "<div style='margin-left: 100px'>";
            echo "<pre style='background: #990000;'>";
            print_r($param);
            echo "</pre>";
            echo "</div>";
        }
    }
    else {
        $param = "<div style='border: 0.5px dashed #B6E5B8;background:#990000;padding: 10px 5px;color: #ffffff;;margin: 10px auto;text-align: center;font-family: consolas;text-wrap: normal;'>$param</div>";
        if (show_debuger() == 1) {

            echo $param;
            flush();
            ob_flush();
        }
    }
}

function cekOrange($param = "halooo orange")
{
    if (is_array($param)) {
        if (show_debuger() == 1) {
            echo "<div style='margin-left: 100px'>";
            echo "<pre style='background: #ff9900;'>";
            print_r($param);
            echo "</pre>";
            echo "</div>";
        }
    }
    else {
        $param = "<div style='border: 0.5px dashed #ff7701;background:#ff9900;padding: 10px 5px;color: #000000;;margin: 10px auto;text-align: center;font-family: consolas;text-wrap: normal;'>$param</div>";
        if (show_debuger() == 1) {

            echo $param;
            flush();
            ob_flush();
        }
    }
}

function cekKuning($param = "halooo kuning(s)")
{
    $array = is_array($param) ? $param : "";

    if (show_debuger() == 1) {
        if ($array != "") {
            echo "<pre style='background: yellow;padding: 5px;color: #000000;'>";
            print_r($array);
            echo "</pre>";
        }
        else {
            $param = "<div style='border: 0.5px dashed yellow;background:yellow;padding: 10px 5px;color: #000000;;margin: 10px auto;text-align: center;font-family: consolas;text-wrap: normal;'>$param</div>";

            echo $param;
        }
        flush();
        ob_flush();
    }
}

function cekLime($param = "halooo lime")
{
    if (is_array($param)) {
        if (show_debuger() == 1) {
            echo "<div style='margin-left: 100px'>";
            echo "<pre style='background: greenyellow;'>";
            print_r($param);
            echo "</pre>";
            echo "</div>";
        }
    }
    else {
        $param = "<div style='border: 0.5px dashed greenyellow;background:greenyellow;padding: 10px 5px;color: #000000;;margin: 10px auto;text-align: center;font-family: consolas;text-wrap: normal;'>$param</div>";
        if (show_debuger() == 1) {

            echo $param;
            flush();
            ob_flush();
        }
    }
}

function cekPink($param = "halooo pink(s)")
{
    $array = is_array($param) ? $param : "";
    if (show_debuger() == 1) {
        if ($array != "") {
            echo "<pre style='background: pink;padding: 5px;color: #008700;'>";
            print_r($array);
            echo "</pre>";
        }
        else {
            $param = "<div style='border: 0.5px dashed pink;background:pink;padding: 10px 5px;color: #000000;;margin: 10px auto;text-align: center;font-family: consolas;text-wrap: normal;'>$param</div>";

            echo $param;
        }
        flush();
        ob_flush();
    }
}

function cekPink2($param = "halooo pink")
{
    if (is_array($param)) {
        if (show_debuger() == 1) {
            echo "<div style='margin-left: 100px'>";
            echo "<pre style='background: #FF55FF;'>";
            print_r($param);
            echo "</pre>";
            echo "</div>";
        }
    }
    else {
        $param = "<div style='border: 0.5px dashed #FF55FF;background:#FF55FF;padding: 10px 5px;color: #000000;;margin: 10px auto;text-align: center;font-family: consolas;text-wrap: normal;'>$param</div>";
        if (show_debuger() == 1) {

            echo $param;
            flush();
            ob_flush();
        }
    }
}

function cekHitam($param = "halooo hitam")
{
    if (is_array($param)) {
        if (show_debuger() == 1) {
            echo "<div style='margin-left: 100px'>";
            echo "<pre style='background: #000000;'>";
            print_r($param);
            echo "</pre>";
            echo "</div>";
        }
    }
    else {

        $param = "<div style='border: 0.5px dashed black;background:black;padding: 10px 5px;color: #ffffff;;margin: 10px auto;text-align: center;font-family: consolas;text-wrap: normal;'>$param</div>";
        if (show_debuger() == 1) {

            echo $param;
            flush();
            ob_flush();
        }
    }
}

function cekUngu($param = "halooo unguuuu unyu unyu XD")
{


    if (is_array($param)) {
        if (show_debuger() == 1) {
            echo "<div style='margin-left: 100px'>";
            echo "<pre style='background: #8D38C9;'>";
            print_r($param);
            echo "</pre>";
            echo "</div>";
        }
    }
    else {
        $param = "<div style='border: 0.5px dashed #8D38C9;background:#8D38C9;padding: 10px 5px;
        color: #ffffff;margin: 10px auto;text-align: center;
        font-family: consolas;text-wrap: normal;'>$param</div>";
        if (show_debuger() == 1) {

            echo $param;
            flush();
            ob_flush();
        }
    }
}

function jejeran($array)
{
    echo "<div style='float: left;overflow: hidden;'>";
    arrPrint($array);
    echo "</div>";
}

function jejeranPink($array)
{
    echo "<div style='float: left;overflow: hidden;'>";
    arrPrintPink($array);
    echo "</div>";
}

function showLast_query($warna, $msg = "")
{
    $ci = &get_instance();
    $warna_f = ucwords($warna);
    $cek = "cek" . $warna_f;

    $msg_f = strlen($msg) > 1 ? "<hr style='margin: 5px;'>$msg" : "";
    $cek($ci->db->last_query() . $msg_f);
}

function arrPrint($array)
{
    if (show_debuger() == 1) {

        echo "<pre style='background: #ffffee;'>";
        print_r($array);
        echo "</pre>";
    }
}

function arrPrintPink($array)
{
    if (show_debuger() == 1) {

        echo "<pre style='background: pink;'>";
        print_r($array);
        echo "</pre>";
    }
}

function arrPrintWebs($array)
{
    if (show_debuger() == 1) {
        echo "<div style='margin-left: 100px'>";
        echo "<pre style='background: #c4e3f3;'>";
        print_r($array);
        echo "</pre>";
        echo "</div>";
    }
}

function arrPrintKuning($array)
{
    if (show_debuger() == 1) {
        echo "<div style='margin-left: 100px'>";
        echo "<pre style='background: #f1d40f;'>";
        print_r($array);
        echo "</pre>";
        echo "</div>";
    }
}

function arrPrintHijau($array)
{
    if (show_debuger() == 1) {
        echo "<div style='margin-left: 100px'>";
        echo "<pre style='background: #55FF2A;'>";
        print_r($array);
        echo "</pre>";
        echo "</div>";
    }
}

function arrPrintHitam($array)
{
    if (show_debuger() == 1) {
        echo "<div style='margin-left: 100px'>";
        echo "<pre style='background: #000000;color:#ffffff;'>";
        print_r($array);
        echo "</pre>";
        echo "</div>";
    }
}

function arrPrintCyan($array)
{
    if (show_debuger() == 1) {
        echo "<div style='margin-left: 100px'>";
        echo "<pre style='background: #32fae3;color:#000000;'>";
        print_r($array);
        echo "</pre>";
        echo "</div>";
    }
}

function arrPrintOrange($array)
{
    if (show_debuger() == 1) {
        echo "<div style='margin-left: 100px'>";
        echo "<pre style='background:orange;color:#000000;'>";
        print_r($array);
        echo "</pre>";
        echo "</div>";
    }
}


function info_debuger()
{
    //    global $template, $anu;
    // $sf = $this->config->item('default');
    // $host = gethostname();                // sub_domain demo.mayagraha.....
    // $ip_server_2 = gethostbyname($host);

    $ci = &get_instance();
    $ci->load->config("heWebs");
    $logins = $ci->config->item('logins');
    $loginMuties = $logins['allowedMultySession'];
    $jenis = $ci->uri->segment(3);
    $trCode = "_TR_" . $jenis;
    // cekHijau($_SESSION[$trCode]);
    if (isset($_SESSION[$trCode])) {
        $gateUrl = base_url() . "Transaksi/debug/$jenis";
    }
    else {
        $gateUrl = "";
    }
    $ci->load->database();
    $ar = $ci->config->item("mongo_db");
    $arrInfo = array(
        "Domain" => $_SERVER['SERVER_NAME'],
        // "serverName" => gethostname(),
        "serverRoot" => $_SERVER['DOCUMENT_ROOT'],
        "path" => $_SERVER['REQUEST_URI'],
        "serverIp" => $_SERVER['SERVER_ADDR'],
        "dbHost" => array($ci->db->hostname, "text-red"
        ),
        "dbName" => array($ci->db->database,
            "text-red"
        ),
        "dbUser" => $ci->db->username,
        "clentIp" => $_SERVER['REMOTE_ADDR'],
        "mongoHost" => isset($ar['default']['hostname']) ? $ar['default']['hostname'] : "none",
        "mongoDB" => isset($ar['default']['database']) ? $ar['default']['database'] : "none",
        "multiSess" => print_r($loginMuties, true),
        "myId" => my_id() . "/" . my_name(),
        "cabangId" => my_cabang_id() . "/" . my_cabang_nama(),

        //        "template" => $anu,
    );
    // $sf = $ci->db->hostname;
    // $sf = $ci->db->database;
    // arrPrint($_SERVER);
    // arrPrint($_SESSION);


    $var = "<div class='col-md-4 pull-right'>";
    $var .= "<div class='border-cekk overflow-h'>";
    $var .= "<button type='button' class='btn btn-link btn-sm pull-left' onclick=\"btn_result('" . base_url() . "auth/Login/removeDebuger');\" 
                title='Turn off debuger mode' data-toggle='tooltip'>
            <i class='fa fa-power-off text-red'></i></button>";
    $var .= "<h3 class='pull-left'>Info Debuger</h3>";
    $var .= "</div>";
    $var .= "<ul class='list-unstyled'>";
    foreach ($arrInfo as $item => $nilai_0) {
        if (is_array($nilai_0)) {
            $nilai = $nilai_0[0];
            $cls = $nilai_0[1];
        }
        else {
            $nilai = $nilai_0;
            $cls = "";
        }

        $var .= "<li>$item: <span class='text-bold font-size-1-2 $cls'>$nilai</span></li>";
    }
    $var .= "</ul>";
    $var .= "</div>";

    return $var;
}

function underMaintenance($ref = "", $reload = "10")
{
    /* -----------------------------------
     * img_maintenace ada di url_helper
     * -----------------------------*/
    //    $ref = referer();
    $undermaintenace = "<div align='center'>";
    // $undermaintenace .= "<img src='../../assets/images/under-maintenance.png'>";
    $undermaintenace .= "<img src='" . img_maintenace() . "'>";
    $undermaintenace .= "</div>";
    if (strlen($ref) > 10) {
        $undermaintenace .= "<div class='text-center' align='center'>";
        // $undermaintenace .= "<button class='btn btn-block btn-warning' type='button' onclick=\"location.href='$ref'\">Back</button>";
        $undermaintenace .= dtimeNow() . " $ref ";
        $undermaintenace .= "</div>";
        // $undermaintenace .= "<script><meta http-equiv='refresh' content='3' URL='".$_SERVER['PHP_SELF']."'></script>";
        $undermaintenace .= "<meta http-equiv='refresh' content='$reload' URL='" . $_SERVER['PHP_SELF'] . "'>";
        // $undermaintenace .= "meta http-equiv='refresh' content=30 URL='".$_SERVER['PHP_SELF']."'";
    }

    return $undermaintenace;
}

function underConstruction()
{
    /* -----------------------------------
         * img_maintenace ada di url_helper
         * -----------------------------*/
    $undermaintenace = "<div align='center'>";
    $undermaintenace .= "<img src='" . base_url() . "assets/images/construction.png'>";
    $undermaintenace .= "</div>";

    return $undermaintenace;
}

function anjingPenjaga()
{

    $undermaintenace = "<div align='center'>";
    $undermaintenace .= "<img src='../../assets/images/bitzer.png'>";
    $undermaintenace .= "</div>";

    return $undermaintenace;
}

// endregion untuk keperluan debuger

function loading_progres($str_peringatan = "")
{
    if ($str_peringatan == "") {
        $warning = "Harap menunggu, transaksi sedang diproses...";
    }
    else {
        $warning = $str_peringatan;
    }
    $var = "<link rel='stylesheet' href='../../assets/templates/pure/css/custom_web.css'>";
    $var .= "<style type='text/css'>
    .layer{
//    background: #ff1493;
    }
    body{

    margin: 0;
    }
    </style>";
    //    $var .= "<div style='display: none;' id='tutup'>";
    $var .= "<div style='display: block;' id='tutup'>";
    $var .= "<div class='layer'></div>";
    $var .= "<div class='layer-3'>";
    $var .= "<div style='border: 0px solid red;text-align: center;font-family: consolas;padding: 5px;'>$warning</div>";
    $var .= "</div>";
    $var .= "</div>";

    return $var;
}

function valIsset($field)
{
    $var = isset($field) ? $field : "";

    return $var;
}

function setDataClean($data)
{
    $var = trim(htmlspecialchars($data, ENT_QUOTES));
    return $var;
}

function swalAlertGoTo($arrAlert, $link_to, $btn_label = "", $btn_cancel = "")
{
    $label_btn = $btn_label == "" ? "DO IT NOW" : $btn_label;
    $cancel_btn = $btn_cancel == "" ? "CLOSE" : ($btn_cancel == false ? "" : $btn_cancel);
    $show_cancel_btn = $btn_cancel == "" ? false : true;
    $executor = $linkSettlement = "$link_to";
    $arrAlertFix = array(
        "showCloseButton" => true,
        "allowOutsideClick" => true,
        "allowEscapeKey" => false,
        "confirmButtonText" => "$label_btn",
        "cancelButtonText" => "$cancel_btn",
        "showCancelButton" => $show_cancel_btn,
    );
    $arrAlertFinal = $arrAlert + $arrAlertFix;

    $hasil = "";
    foreach ($arrAlertFinal as $ky => $val) {

        $var = "$ky: \"$val\"";
        if ($hasil == "") {
            $hasil = "$var";
        }
        else {
            $hasil = "$hasil, $var";
        }
    }

    return ("<script>top.swal({" . $hasil . "}).then(function() {
        window.location = '$executor'        
    });</script>");
}

function swalAlertGoTo2($arrAlert, $link_to, $btn_label = "", $btn_cancel = "")
{
    $label_btn = $btn_label == "" ? "DO IT NOW" : $btn_label;
    $cancel_btn = $btn_cancel == "" ? "CLOSE" : ($btn_cancel == false ? "" : $btn_cancel);
    $show_cancel_btn = $btn_cancel == "" ? false : true;
    $executor = $linkSettlement = "$link_to";
    $arrAlertFix = array(
        "showCloseButton" => false,
        "allowOutsideClick" => false,
        "allowEscapeKey" => false,
        "confirmButtonText" => "$label_btn",
        "cancelButtonText" => "$cancel_btn",
        "showCancelButton" => $show_cancel_btn,
    );
    $arrAlertFinal = $arrAlert + $arrAlertFix;

    $hasil = "";
    foreach ($arrAlertFinal as $ky => $val) {

        $var = "$ky: \"$val\"";
        if ($hasil == "") {
            $hasil = "$var";
        }
        else {
            $hasil = "$hasil, $var";
        }
    }
    return ("<script>top.swal({" . $hasil . "}).then(function() {
        document.getElementById('result').src='$executor'
    });</script>");
}

function swalAlertGoTo3($arrAlert, $link_to, $btn_label = "", $btn_cancel = "")
{
    $label_btn = $btn_label == "" ? "DO IT NOW" : $btn_label;
    $cancel_btn = $btn_cancel == "" ? "CLOSE" : ($btn_cancel == false ? "" : $btn_cancel);
    $show_cancel_btn = $btn_cancel == "" ? false : true;
    $executor = $linkSettlement = "$link_to";
    $arrAlertFix = array(
        "showCloseButton" => false,
        "allowOutsideClick" => false,
        "allowEscapeKey" => false,
        "confirmButtonText" => "$label_btn",
        "cancelButtonText" => "$cancel_btn",
        "showCancelButton" => $show_cancel_btn,
    );
    $arrAlertFinal = $arrAlert + $arrAlertFix;

    $hasil = "";
    foreach ($arrAlertFinal as $ky => $val) {

        $var = "$ky: \"$val\"";
        if ($hasil == "") {
            $hasil = "$var";
        }
        else {
            $hasil = "$hasil, $var";
        }
    }
    return ("<script>top.swal({" . $hasil . "}).then(function() {
        window.location = '$executor'        
    });</script>");
}

function swalAlertGoToOption($arrAlert, $link_to, $btn_label = "", $btn_cancel = "")
{
    $label_btn = $btn_label == "" ? "DO IT NOW" : $btn_label;
    $cancel_btn = $btn_cancel == "" ? "CLOSE" : ($btn_cancel == false ? "" : $btn_cancel);
    $show_cancel_btn = $btn_cancel == "" ? false : true;
    $executor = $linkSettlement = "$link_to";
    $arrAlertFix = array(
        "type" => "warning",
        "title" => "<span style=color: red;>Perhatian</span>",
        "showCloseButton" => false,
        "allowOutsideClick" => false,
        "allowEscapeKey" => false,
        "confirmButtonText" => "$label_btn",
        "cancelButtonText" => "$cancel_btn",
        "showCancelButton" => $show_cancel_btn,
//        "background" => "#34abeb",
    );
    $arrAlertFinal = $arrAlert + $arrAlertFix;

    $hasil = "";
    foreach ($arrAlertFinal as $ky => $val) {
        $var = "$ky: \"$val\"";
        if ($hasil == "") {
            $hasil = "$var";
        }
        else {
            $hasil = "$hasil, $var";
        }
    }

    return ("<script>top.swal({" . $hasil . "}).then(function() {
        document.getElementById('result').src='$executor'
    });</script>");
}

function swalAlertGoToReload($arrAlert, $link_to, $btn_label = "", $btn_cancel = "")
{
    $label_btn = $btn_label == "" ? "RELOAD" : $btn_label;
    $cancel_btn = $btn_cancel == "" ? "CLOSE" : $btn_cancel;
//    $show_cancel_btn = $btn_cancel == "" ? false : true;
    $show_cancel_btn = true;
    $executor = $linkSettlement = "$link_to";
    $arrAlertFix = array(
        "showCloseButton" => true,
        "allowOutsideClick" => true,
        "allowEscapeKey" => false,
        "confirmButtonText" => "$label_btn",
        "cancelButtonText" => "$cancel_btn",
        "showCancelButton" => $show_cancel_btn,
    );
    $arrAlertFinal = $arrAlert + $arrAlertFix;

    $hasil = "";
    foreach ($arrAlertFinal as $ky => $val) {
        $var = "$ky: \"$val\"";
        if ($hasil == "") {
            $hasil = "$var";
        }
        else {
            $hasil = "$hasil, $var";
        }
    }

    return ("<script>top.swal({" . $hasil . "}).then(function() {
         top.$('#result').load('$link_to');
    });
    </script>");
}

function swalAlert($arrAlert)
{
    $hasil = "";
    foreach ($arrAlert as $ky => $val) {

        $var = "$ky: \"$val\"";
        if ($hasil == "") {
            $hasil = "$var";
        }
        else {
            $hasil = "$hasil, $var";
        }
    }

    //    echo toAlert($hasil);
    return ("<script>top.close_holdon();top.swal({" . $hasil . "});top.$('button.btn.btn-success').prop('disabled', false);</script>");

    //    echo("<script>top.swal({, type: 'success'}).then(function(){top.location.reload(true);})</script>");
    //    echo("<script>top.swal({title: 'Good job', text: 'Data Sudah tersimpan, klik OK untuk mereload halaman!', type: 'success'}).then(function(){top.location.reload(true);})</script>");
    //    die("<script>window.setTimeout(function(){
    //            top.location.reload();
    //        } ,2000);</script>");
}

function swalAlertOriginal($arrAlert)
{
    $hasil = "";
    foreach ($arrAlert as $ky => $val) {

        $var = "$ky: \"$val\"";
        if ($hasil == "") {
            $hasil = "$var";
        }
        else {
            $hasil = "$hasil, $var";
        }
    }

    //    echo toAlert($hasil);
    return ("<script>top.close_holdon();top.swal({" . $hasil . "});</script>");

    //    echo("<script>top.swal({, type: 'success'}).then(function(){top.location.reload(true);})</script>");
    //    echo("<script>top.swal({title: 'Good job', text: 'Data Sudah tersimpan, klik OK untuk mereload halaman!', type: 'success'}).then(function(){top.location.reload(true);})</script>");
    //    die("<script>window.setTimeout(function(){
    //            top.location.reload();
    //        } ,2000);</script>");
}

function modalDialogFormSubmit($headingTitle, $form_id, $linkAction, $submit_label = false)
{
    if ($submit_label != false) {
        $btn_submit = "{
                    label: '$submit_label',
                     cssClass: 'btn-primary pull-right',
                    action: function(){
                        document.getElementById('$form_id').submit();
                    },
            
                }";
    };
    $actionTarget = "top.BootstrapDialog.closeAll();
                            top.BootstrapDialog.show(
                                   {
                                       title:'$headingTitle',
                                       message: " . '$' . "('<div></div>').load('$linkAction'),
                                        size:top.BootstrapDialog.SIZE_WIDE,                                        
                                        draggable:false,
                                        closable:true,
                                        buttons: [{                                            
                                            label: 'Close',
                                             cssClass: 'pull-left',
                                            action: function(dialogItself){
                                                    dialogItself.close();
                                                }
                                            }," .
        $btn_submit .

        "]
                                    }
                                );
                                    ";

    return $actionTarget;
}

function modalDialogBtn($headingTitle, $linkAction, $auto_close = 1, $pf = null)
{

    $actionTarget = "";

    if ($auto_close) {
        $actionTarget .= "top.BootstrapDialog.closeAll();";
    }

    $prefix = $pf != null ? "id=$pf" : "";

    $actionTarget .= "top.BootstrapDialog.show({
                          title:'" . str_replace('+', ' ', htmlspecialchars($headingTitle)) . "',
                          message: " . '$' . "('<div $prefix></div>').load('" . $linkAction . "'),
                          size:top.BootstrapDialog.SIZE_WIDE,
                          draggable:true,
                          closable:true,
                          buttons: [{
                              label: 'Close',
                              cssClass: 'pull-left',
                              action: function(dialogItself){
                                  dialogItself.close();
                              }
                          }]
                       });";

    return $actionTarget;
}

function modalDialogKhusus($headingTitle, $linkContent, $linkAction)
{

    $actionTarget = "top.BootstrapDialog.closeAll();
                            top.BootstrapDialog.show(
                                   {
                                       title:'$headingTitle',
                                       message: " . '$' . "('<div></div>').load('$linkContent'),
                                        size:top.BootstrapDialog.SIZE_WIDE,                                        
                                        draggable:false,
                                        closable:false,
                                        buttons: [{                                            
                                            label: 'Back To Home',
                                             cssClass: 'pull-left',
                                            action: function(){
                                                    location.href='$linkAction';
                                                }
                                        }]
                                    }
                                );
                                    ";

    return $actionTarget;
}

function lgShowWarning($caption, $errMsg)
{
    $dtime_now = dtimeNow() . " " . my_host();
    return swalAlert(array(
        //        "width"             => '600px',
        "type" => "warning",
        "title" => "$caption",
        // "html" => "<span style='font-size:1.4em;'>" . htmlspecialchars($errMsg) . "</span><br><small>$dtime_now</small>",
        "html" => "<span style='font-size:1.1em;'>" . ($errMsg) . "</span><br><small class='meta'>$dtime_now</small>",
        "showConfirmButton" => true,
        "allowOutsideClick" => true,
        "showCloseButton" => true,
        // "showLoaderOnConfirm" => true,
        // "imageUrl"            => "http://inn.com"
    ));
}

function lgShowSuccess($caption, $errMsg)
{
    return swalAlert(array(
        //        "width"             => '600px',
        "type" => "success",
        "title" => "$caption",
        "html" => "<span style='font-size:1.4em;'>$errMsg</span>",
        "showConfirmButton" => true,
        "allowOutsideClick" => true,
        "showCloseButton" => true,
        // "showLoaderOnConfirm" => true,
        // "imageUrl"            => "http://inn.com"
    ));
}

function lgShowAlert($errMsg)
{
    $dtime_now = dtimeNow() . " " . my_host();
    return swalAlert(array(
        "type" => "warning",
        "title" => $judul,
        "html" => "<span style='font-size:1.2em;'>$errMsg</span><br><small class='meta'>$dtime_now</small>",
        "showConfirmButton" => true,
        "allowOutsideClick" => true,
        "showCloseButton" => true,
    ));
}

function lgShowAlertBiru($newWarningLabel)
{
    $dtime_now = dtimeNow();
    $arrSwals = array(
        "type" => "warning",
        "title" => "<span style='color: red;'>Perhatian</span>",
        "html" => "<span style='color: yellow;'>$newWarningLabel</span><br><small>$dtime_now</small>",
        "allowOutsideClick" => false,
        "background" => "#34abeb",
        "confirmButtonText" => "Close",
        "confirmButtonColor" => "#ff0055",
    );

    return swalAlert($arrSwals);

}

function lgShowAlertMerah($newWarningLabel)
{
    $dtime_now = dtimeNow();
    $arrSwals = array(
        "type" => "warning",
        "title" => "<span style='color:#ffffff;font-weight: bold;'>Perhatian</span>",
        "html" => "<span style='color:#ffffff;font-weight: bold;'>$newWarningLabel</span><br><small style='color:#ffffff;'>$dtime_now</small>",
        "allowOutsideClick" => false,
        "background" => "#E4080A",
        "confirmButtonText" => "Close",
        "confirmButtonColor" => "#000000",
    );

    return swalAlert($arrSwals);

}

function downloadXlsx($link_excel, $dataUrl, $file_nama)
{
    // dataUrl = "data=$strItems&item2=$strItems2";
    $var = "<script>
    
                        var download_excel = function(){
    
                            var xhr = new XMLHttpRequest();
                            xhr.open('POST', '$link_excel', true);
                           xhr.responseType = 'blob';
    //                        xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
                            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                            xhr.onload = function(e) {
                                if (this.status == 200) {
                                    var blob = new Blob([this.response], {type: 'application/vnd.ms-excel'});
                                    var downloadUrl = URL.createObjectURL(blob);
                                    var a = document.createElement(\"a\");
                                    a.href = downloadUrl;
                                    a.download = \"$file_nama.xlsx\";
                                    document.body.appendChild(a);
                                    a.click();
                                } else {
                                    alert('Unable to download excel.')
                                }
                            };
                            xhr.send('$dataUrl');
    
                        }
                    </script>";

    return $var;
}

function lgShowError($caption, $errMsg)
{
    $dtime_now = dtimeNow();

    return swalAlert(array(
        "type" => "error",
        "title" => $caption,
        "html" => $errMsg . "<br><small>$dtime_now</small>",
        "showConfirmButton" => true,
        "allowOutsideClick" => true,
        "showCloseButton" => true,
        // "imageUrl"            => "http://inn.com"
    ));
}

function lgWait($errMsg = "Please wait ... ...")
{
    return swalAlert(array(
        // "type" => "error",
        // "title" => "Pleas wait ... ...",
        "html" => $errMsg,
        "showConfirmButton" => false,
        "allowOutsideClick" => true,
        "showCloseButton" => true,
        // "imageUrl"            => "http://inn.com"
    ));
}

function reloaded($time_ms = "2000")
{
    die("<script>window.setTimeout(function(){
            location.reload();
        } ,$time_ms);</script>");
}

function redirecResult($target_url)
{
    die("<script>
        // document.getElementById('result').src = $target_url
        location.href='$target_url';
        </script>");
}

function topReload($time_ms = "2000")
{
    die("<script>window.setTimeout(function(){
            top.location.reload();
        } ,$time_ms);</script>");
}

function topRedirect($target_url, $time_ms = 0)
{
    die("<script>window.setTimeout(function(){
            top.location.href='$target_url';
        } ,$time_ms);</script>");
}

function session()
{
    // if (strlen($key) > 0) {
    //     $obj = (object)($_SESSION[$key]);
    // }
    // else {
    $obj = (object)$_SESSION;
    // }

    // return $_SESSION;
    return $obj;
}

//region webs session
function sessionWebs()
{
    return isset($_SESSION['webs']) ? $_SESSION['webs'] : array();
}

function produk_spec_webs()
{
    $var = sessionWebs();

    return isset($var['produk_spec']) ? $var['produk_spec'] : array();
}

function produk_folder_webs()
{
    $var = sessionWebs();

    return isset($var['produk_folders']) ? $var['produk_folders'] : array();
}

function produk_harga_webs()
{
    $var = sessionWebs();

    return isset($var['produk_hargas']) ? $var['produk_hargas'] : array();
}

function cart_webs()
{
    $var = sessionWebs();

    return isset($var['cart']) ? $var['cart'] : array();
}

function login_webs()
{
    $var = sessionWebs();

    return isset($var['login']) ? $var['login'] : array();
}

//endregion

function ion_icon($label)
{
    $label = strtolower($label);
    $arrIon_icon = array(
        "data" => "fa-database",
        "pembelian" => "fa-money",
        "produksi" => "ion-ios-gear",
        "distribusi" => "fa-truck",
        "penjualan" => "ion-ribbon-b",
        "laporan" => "ion-clipboard",
        "setting" => "ion-settings",
        "tools" => "ion-briefcase",
        "login" => "fa-key",
        "logout" => "fa-lock",
        "kategori" => "fa-folder-open",
    );

    return $arrIon_icon[$label];
}

function fa_icon($label = null)
{
    $label = strtolower($label);
    $arrIon_icon = array(
        "menu utama" => "fa-cube",
        "sub menu" => "fa-cubes",
        "warehouses" => "fa-cube",
        "supplies & equipments" => "fa-cubes",
        "web" => "fa-globe",
        "merk" => "fa-tag",
        "home" => "fa-home",
        "po" => "fa-rocket",
        "konsumen" => "fa-users",
        "customers" => "fa-users",
        "wewenang" => "fa-fire",
        "access" => "fa-fire",
        "email" => "fa-envelope-o",
        "distribusi" => "fa-truck",
        "couriers" => "fa-motorcycle",
        "vendors" => "fa-truck",
        "banks" => "fa-bank",
        "company profiles" => "fa-industry",
        // "banks"               => "fa-building-o",
        "bank accounts" => "fa-male",
        "produk" => "fa-diamond",
        "products" => "fa-diamond",
        "product prices" => "fa-tag",
        "kategori" => "fa-folder-open",
        "product folders" => "fa-briefcase",
        "item" => "fa-tag",

        "composition productses" => "fa-file-text-o",
        "assembled product folders" => "fa-briefcase",
        "assembled products" => "fa-puzzle-piece",

        "karyawan" => "fa-user",
        "user" => "fa-user",
        "users" => "fa-user",
        "employees" => "fa-user-secret",
        "warehouse employees" => "fa-user-secret",

        "profile" => "fa-user",
        "penjualan" => "ion-ribbon-b",
        "cabang" => "fa-puzzle-piece",
        "branches" => "fa-leaf",
        "laporan" => "ion-clipboard",
        "laporans" => " fa-pie-chart",
        "data" => "fa-database",
        "pembelian" => "fa-money",
        "purchasing" => "fa-money",
        "shoping cart" => "fa-shopping-cart",
        "produksi" => "ion-ios-gear",
        "setting" => "ion-settings",
        "settings" => "fa-gears",
        "tools" => "ion-briefcase",
        "login" => "fa-key",
        "logout" => "fa-lock",
        "shipping addresses" => "fa-map-pin",
        "billing addresses" => "fa-map",
        "my shipping addresses" => "fa-map-marker",
        "transaksi" => "fa-exchange",
        "connected productses" => "fa-link",
        "activity logs" => "fa-suitcase",
        "term of services" => "fa-bookmark-o",
        "term of payments" => "fa-bookmark-o",
        "capacities" => "fa-archive",
        "capacityes" => "fa-archive",
        "advertises" => "fa-bullhorn",
    );

    if ($label == null) {
        return $arrIon_icon;
    }
    else {
        return $arrIon_icon[$label];
    }
}

function my_id()
{
    if (isset($_SESSION['login']['id'])) {

        return $_SESSION['login']['id'];
    }
    else {
        return 0;
    }
}

function my_name()
{
    if (isset($_SESSION['login']['nama'])) {

        return $_SESSION['login']['nama'];
    }
    else {
        return 0;
    }
}

function my_dc_id()
{
    if (isset($_SESSION['login']['dc_id'])) {

        return $_SESSION['login']['dc_id'];
    }
    else {
        return 0;
    }
}

function my_dc_name()
{
    if (isset($_SESSION['login']['dc_nama'])) {

        return $_SESSION['login']['dc_nama'];
    }
    else {
        return 0;
    }
}

function my_cabang_id()
{
    if (isset($_SESSION['login']['cabang_id'])) {
        return $_SESSION['login']['cabang_id'];
    }
    else {
        return 0;
    }
}

function my_cabang_nama()
{
    if (isset($_SESSION['login']['cabang_nama'])) {
        return $_SESSION['login']['cabang_nama'];
    }
    else {
        return 0;
    }
}

function my_ppn_factor()
{
    if (isset($_SESSION['login']['ppnFactor'])) {
        return $_SESSION['login']['ppnFactor'];
    }
    else {
        return 0;
    }
}

function my_toko_id()
{
    if (isset($_SESSION['login']['toko_id'])) {
        return $_SESSION['login']['toko_id'];
    }
    else {
        return 0;
    }
}

function my_toko_nama()
{
    return isset($_SESSION['login']['toko_nama']) ? $_SESSION['login']['toko_nama'] : "-";
}

function my_gudang_id()
{
    if (isset($_SESSION['login']['gudang_id'])) {
        return $_SESSION['login']['gudang_id'];
    }
    else {
        return 0;
    }
}

function my_gudang_nama()
{
    if (isset($_SESSION['login']['gudang_nama'])) {
        return $_SESSION['login']['gudang_nama'];
    }
    else {
        return 0;
    }
}

function my_div_id()
{
    return isset($_SESSION['login']['div_id']) ? $_SESSION['login']['div_id'] : 0;
}

function my_div_nama()
{
    return isset($_SESSION['login']['div_nama']) ? $_SESSION['login']['div_nama'] : "-";
}

function my_jenis_usaha()
{
    return isset($_SESSION['login']['jenis_usaha']) ? $_SESSION['login']['jenis_usaha'] : "-";
}

function my_menu($cabang_id)
{
    $ci = &get_instance();
    $ci->load->config('menu_link.php');


    if ($cabang_id > 0) {
        $menu = $ci->config->item('menu')['cabang'];
    }
    elseif ($cabang_id < 0) {
        $menu = $ci->config->item('menu')['pusat'];
    }
    else {
        $menu = array();
    }

    return $menu;
}

function my_memberships()
{
    // return $_SESSION['login']['membership'];
    return cekAlert(__FUNCTION__ . "sudah tidak ada");
}

function my_type()
{
    return isset($_SESSION['login']['employee_type']) ? $_SESSION['login']['employee_type'] : false;
}

function my_place()
{
    return isset($_SESSION['login']['place']) ? $_SESSION['login']['place'] : false;
}

function my_ppn_constanta()
{
    return isset($_SESSION['login']['ppnConstanta']) ? $_SESSION['login']['ppnConstanta'] : false;
}

function my_ppn_constanta_str()
{
    return isset($_SESSION['login']['ppnConstantaStr']) ? $_SESSION['login']['ppnConstantaStr'] : false;
}


define('CB_ID_PUSAT', '11');
define('CB_NAME_PUSAT', 'DC/PUSAT');


function createStateSign($current, $totalAvail, $jenisTr = NULL)
{
    $ci = &get_instance();
    $array_step_label = isset($ci->config->item("heTransaksi_ui")[$jenisTr]['steps']) ? $ci->config->item("heTransaksi_ui")[$jenisTr]['steps'] : array();
    $str = "";
    for ($i = 1; $i <= $totalAvail; $i++) {
        $step_label = isset($array_step_label[$i]['label']) ? $array_step_label[$i]['label'] : "-";
        if ($i <= $current) {
            $str .= "<span class='fa fa-check-circle text-black'> $step_label</span><br>";
        }
        else {
            $str .= "<span class='fa fa-circle text-grey'> $step_label</span><br>";
        }
    }
    // tambahan next activity
    if ($jenisTr != NULL) {
        $next_step_number = $current + 1;
        $ci = &get_instance();
        $next_step_label = isset($ci->config->item("heTransaksi_ui")[$jenisTr]['steps'][$next_step_number]['label']) ? $ci->config->item("heTransaksi_ui")[$jenisTr]['steps'][$next_step_number]['label'] : "-";

        $str .= "<br>";
        $str .= "<span class='text-black' style='font-weight:bold;font-size:12px;'>Next Step:</span><br>";
        $str .= "<span class='text-red' style='font-weight:bold;font-size:12px;'>$next_step_label</span>";
    }

    return $str;
}

function createStateSignNext($current, $totalAvail, $jenisTr = NULL)
{
    $next_step_number = $current + 1;
    $ci = &get_instance();
    $array_step_label = isset($ci->config->item("heTransaksi_ui")[$jenisTr]['steps']) ? $ci->config->item("heTransaksi_ui")[$jenisTr]['steps'] : array();
    $str = "";
    for ($i = 1; $i <= $totalAvail; $i++) {
        $step_label = isset($array_step_label[$i]['label']) ? $array_step_label[$i]['label'] : "-";
        if ($i < $current) {
            $str .= "<span class='fa fa-check-circle text-grey'> $step_label</span><br>";
        }
        elseif ($i == $next_step_number) {
            $str .= "<span class='fa fa-check-circle text-black text-bold'> $step_label</span><br>";
        }
        else {
            $str .= "<span class='fa fa-circle text-grey'> $step_label</span><br>";
        }
    }
    // tambahan next activity
//    if ($jenisTr != NULL) {
//        $next_step_number = $current + 1;
//        $ci = &get_instance();
//        $next_step_label = isset($ci->config->item("heTransaksi_ui")[$jenisTr]['steps'][$next_step_number]['label']) ? $ci->config->item("heTransaksi_ui")[$jenisTr]['steps'][$next_step_number]['label'] : "-";
//
//        $str .= "<br>";
//        $str .= "<span class='text-black' style='font-weight:bold;font-size:12px;'>Next Step:</span><br>";
//        $str .= "<span class='text-red' style='font-weight:bold;font-size:12px;'>$next_step_label</span>";
//    }

    return $str;
}

function createStateHorizontal($current, $totalAvail, $jenisTr = NULL, $showLabel = true)
{
    $ci = &get_instance();
    $array_step_label = isset($ci->config->item("heTransaksi_ui")[$jenisTr]['steps']) ? $ci->config->item("heTransaksi_ui")[$jenisTr]['steps'] : array();


    $str = "";
    // $str .= "<div class='horizontal timeline'>";
    $str .= "<ul class='timeline-kecil'>";
    for ($i = 1; $i <= $totalAvail; $i++) {
        $step_label = isset($array_step_label[$i]['label']) ? $array_step_label[$i]['label'] : "-";
        if ($i <= $current) {
            //     $str .= "<span class='fa fa-check-circle text-black'> $step_label</span>";
            $bg_warna = "done-tl";
        }
        elseif ($i == ($current + 1)) {
            $bg_warna = "now-tl";
        }
        else {
            //     $str .= "<span class='fa fa-circle text-grey'> $step_label</span>";
            $bg_warna = "none";
        }
        // if($showLabel == true){
        $labelDisplay = $showLabel == true ? "" : "style='display:none;'";
        // }

        $str .= "<li class='$bg_warna no-margin'>";
        // $str .= "<span style='display: none;'>$step_label</span>";
        $str .= "<span $labelDisplay>$step_label</span>";
        $str .= "</li>";
    }
    // $str .= "</div>";

    // $str .= "<div class='line'></div>";
    $str .= "</ul>";

    // tambahan next activity
    if ($jenisTr != NULL) {
        // $next_step_number = $current + 1;
        // $ci = &get_instance();
        // $next_step_label = isset($ci->config->item("heTransaksi_ui")[$jenisTr]['steps'][$next_step_number]['label']) ? $ci->config->item("heTransaksi_ui")[$jenisTr]['steps'][$next_step_number]['label'] : "-";

        // $str .= "<br>";
        // $str .= "<span class='text-black' style='font-weight:bold;font-size:12px;'>Next Step:</span><br>";
        // $str .= "<span class='text-red' style='font-weight:bold;font-size:12px;'>$next_step_label</span>";
    }


    return $str;
}

function createStateMap($current, $totalAvail, $steps, $jenisTr)
{
    $ci = &get_instance();
    $str = "";

    $str .= "<span style='font-family:georgia;'>";
    $trConfig = $ci->config->item("heTransaksi_ui")[$jenisTr];
    $stepIcon = $trConfig['icon'];
    //    $str.="<span class='$stepIcon' style='font-size:1.3em;color:#ff7700;'></span> ";
    $iCtr = 0;
    for ($i = 1; $i <= $totalAvail; $i++) {
        $iCtr++;

        if ($i == $current) {
            $numIcon = "<span class='badge text-white bg-blue'>$i</span>";
            $str .= "<span class='text-blue'>$numIcon " . $steps[$i] . "</span>";
        }
        else {
            if ($i < $current) {
                $numIcon = "<span class='badge text-white' style='background:#345678;'>$i</span>";
                $str .= "<span class='' style='color:#345678;'>$numIcon " . $steps[$i] . "</span>";
            }
            else {
                $numIcon = "<span class='badge text-white bg-gray'>$i</span>";
                $str .= "<span class='text-grey'>$numIcon " . $steps[$i] . "</span>";
            }
        }
        if ($i < $totalAvail) {
            $str .= " <span class='fa fa-angle-double-right'> ";
        }

    }
    $str .= "</span>";

    return $str;
}

function createStateHorizontalMap($current, $totalAvail, $steps, $jenisTr)
{
    $ci = &get_instance();
    $str = "";

    // $str .= "<span style='font-family:georgia;'>";
    $trConfig = $ci->config->item("heTransaksi_ui")[$jenisTr];
    $stepIcon = $trConfig['icon'];
    //    $str.="<span class='$stepIcon' style='font-size:1.3em;color:#ff7700;'></span> ";
    $iCtr = 0;
    $str .= "<ul class='timeline'>";
    for ($i = 1; $i <= $totalAvail; $i++) {
        $iCtr++;
        $step_label = $steps[$i];
        if ($i == $current) {
            $numIcon = "<span class='badge text-white bg-blue'>$i</span>";
            // $str .= "<span class='text-blue'>$numIcon " . $steps[$i] . "</span>";
            $bg_warna = "now-tl";
        }
        else {
            if ($i < $current) {
                $numIcon = "<span class='badge text-white' style='background:#345678;'>$i</span>";
                // $str .= "<span class='' style='color:#345678;'>$numIcon " . $steps[$i] . "</span>";
                $bg_warna = "done-tl";
            }
            else {
                $numIcon = "<span class='badge text-white bg-gray'>$i</span>";
                // $str .= "<span class='text-grey'>$numIcon " . $steps[$i] . "</span>";
                $bg_warna = "none";
            }
        }

        $str .= "<li class='$bg_warna no-margin'>";
        $str .= "<span>$step_label</span>";
        $str .= "</li>";

    }
    $str .= "</ul>";

    return $str;
}

function heReturnDeviceName($dev)
{
    $splitKurung = explode("(", $dev);
    if (sizeof($splitKurung) > 1) {
        $rawDeviceName = $splitKurung[1];
        //        $rawBrowserName = $splitKurung[2];


        // <editor-fold defaultstate="collapsed" desc="cek nama device">
        if (preg_match("/Android/i", $rawDeviceName)) {
            $deviceName = "Android";
        }
        else {
            if (preg_match("/iPad/i", $rawDeviceName)) {
                $deviceName = "iPad";
            }
            else {
                if (preg_match("/iPhone/i", $rawDeviceName)) {
                    $deviceName = "iPhone";
                }
                else {
                    if (preg_match("/Windows/i", $rawDeviceName)) {
                        $deviceName = "PC";
                    }
                    else {
                        if (preg_match("/Mac/i", $rawDeviceName)) {
                            $deviceName = "Mac";
                        }
                        else {
                            $deviceName = "Ghost";
                        }
                    }
                }
            }
        }// </editor-fold>
        // <editor-fold defaultstate="collapsed" desc="cek nama browser">
        if (preg_match("/Firefox/i", $dev)) {
            $browserName = "Firefox";
        }
        else {
            if (preg_match("/Opera/i", $dev)) {
                $browserName = "Opera";
            }
            else {
                if (preg_match("/Edg/i", $dev)) {
                    $browserName = "Edge";
                }
                else {
                    if (preg_match("/Explorer/i", $dev)) {
                        $browserName = "IE";
                    }
                    else {
                        if (preg_match("/Chrome/i", $dev)) {
                            $browserName = "Chrome";
                        }
                        else {
                            if (preg_match("/Edg/i", $dev)) {
                                $browserName = "Edge";
                            }
                            else {
                                $browserName = "Ghost";
                            }
                        }
                    }
                }
            }
        }// </editor-fold>
        //echo "device: $deviceName<br>";
        //echo "browser: $browserName<br>";
        return array(
            "device" => $deviceName,
            "browser" => $browserName,
        );
    }
    else {
        return array(
            "device" => "unknown",
            "browser" => "unknown",
        );
    }

}

function writeLog($title, $sub_title, $category, $jenis = "", $uid = 0, $uname = "", $deskripsi_new = "", $deskripsi_old = "", $transaksi_id = 0)
{
    //    $session_login = $_SESSION['login'];
    // arrPrint($session_login);
    $ci = &get_instance();
    $session_login = $ci->session->login;
    $ci->load->model("Mdls/" . "MdlActivityLog");
    $hTmp = new MdlActivityLog();
    $hTmp->setFilters(array());
    $tmpHData = array(
        "title" => $title,
        "sub_title" => $sub_title,
        "uid" => isset($session_login['id']) ? $session_login['id'] : $uid,
        "uname" => isset($session_login['nama']) ? $session_login['nama'] : $uname,
        "dtime" => date("Y-m-d H:i:s"),
        "transaksi_id" => $transaksi_id,
        "deskripsi_old" => base64_encode(serialize($deskripsi_old)),
        "deskripsi_new" => base64_encode(serialize($deskripsi_new)),
        "jenis" => $jenis,
        "ipadd" => $_SERVER['REMOTE_ADDR'],
        "devices" => $_SERVER['HTTP_USER_AGENT'],
        "category" => $category,
        "controller" => $ci->uri->segment(1),
        "method" => $ci->uri->segment(2),
        "url" => current_url(),
        "referer_url" => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "none",
        "ghost" => isset($session_login['ghost']) ? $session_login['ghost'] : "0",
    );
    $logID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
}

function writeLogToFile($directory, $file_name, $txt_message)
{

    $myfile = file_put_contents($directory . '/' . $file_name . '.txt', $txt_message . PHP_EOL, FILE_APPEND | LOCK_EX);
}

//function heReturnISPName($ip = '')
//{
//    if ($ip == '')
//        $ip = $_SERVER['REMOTE_ADDR'];
//    $longisp = @gethostbyaddr($ip);
//    $isp = explode('.', $longisp);
//    $isp = array_reverse($isp);
//    $tmp = $isp[1];
//    if (preg_match("/\<(org?|com?|net)\>/i", $tmp)) {
//        $myisp = $isp[2] . '.' . $isp[1] . '.' . $isp[0];
//    } else {
//        $myisp = $isp[1] . '.' . $isp[0];
//    }
//    if (preg_match("/[0-9]{1,3}\.[0-9]{1,3}/", $myisp)) {
//        return 'Unknown';
//    } else {
//        return $myisp;
//    }
//}
function tplNoData($str = "")
{
    $var = "<div class='text-center font-size-2 margin-top-20 text-grey-1'>";
    $var .= "Tidak ada yang bisa ditampilkan";
    $var .= "<div class='bottom-space-20 font-size-0-7'>";
    if ($str != "") {
        $var .= "$str";
    }
    $var .= "</div>";
    $var .= "</div>";

    return $var;
}

function tplTableNoData($jml_kolom = "1", $str = "tidak ada data")
{
    $var = "";
    $var .= "<tr>";
    $var .= "<td colspan='$jml_kolom' align='center' class='text-grey-b'>$str</td>";
    $var .= "</tr>";

    return $var;
}

function headTpl()
{
    $var = "<link rel=\"icon\" type=\"image/png\" href=\"" . base_url() . "public/images/sys/head_top.ico\">
        <link rel=\"stylesheet\" href=\"" . local_suport() . "bootstrap-3.3.7-dist/css/bootstrap.min.css\">
        <link rel=\"stylesheet\" href=\"" . local_suport() . "Font-Awesome-master/css/font-awesome.min.css\">
        <link rel=\"stylesheet\" href=\"" . local_suport() . "ionicons-master/css/ionicons.min.css\">
        <link rel=\"stylesheet\" href=\"" . local_suport() . "simple-line-icons-master/css/simple-line-icons.css\">
        <link rel=\"stylesheet\" href=\"" . local_suport() . "AdminLTE-2.3.11/dist/css/AdminLTE.css\">
        <link rel=\"stylesheet\" href=\"" . local_suport() . "bootstrap3-dialog-master/dist/css/bootstrap-dialog.min.css\">
        <link rel=\"stylesheet\" href=\"" . local_suport() . "AdminLTE-2.3.11/dist/css/skins/_all-skins.min.css\">
        <link rel=\"stylesheet\" href=\"" . base_url() . "assets/bootstrap-fileinput-master/css/fileinput.min.css\">
        <link rel=\"stylesheet\" href=\"" . base_url() . "/assets/bootstrap-toggle-master/css/bootstrap-toggle.min.css\">
        <link rel=\"stylesheet\" href=\"" . base_url() . "/assets/custom/dialog.css\">
        <link rel=\"stylesheet\" href=\"" . local_suport() . "sweetalert2-master/dist/sweetalert2.min.css\">
        <link rel=\"stylesheet\" href=\"" . local_suport() . "bootstrap-xlgrid-master/bootstrap-xlgrid.min.css\">
        <link rel=\"stylesheet\" href=\"" . base_url() . "assets/custom/custom.min.css\">
                
        <script src=\"" . local_suport() . "AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>
        <script src=\"" . local_suport() . "bootstrap-3.3.7-dist/js/bootstrap.min.js\"></script>
        <script src=\"" . local_suport() . "sweetalert2-master/dist/sweetalert2.min.js\"></script>
        <script src=\"" . local_suport() . "bootstrap3-dialog-master/dist/js/bootstrap-dialog.min.js\"></script>
        <script src=\"" . base_url() . "/assets/custom/custom.js\"></script>
        
        <link rel=\"stylesheet\" href=\"" . local_suport() . "jquery-ui-themes-1.11.0/themes/smoothness/jquery-ui.css\"/>
        <script src=\"" . local_suport() . "jquery-ui-1.11.0/jquery-ui.min.js\"></script>
        ";

    return $var;
}

function footTpl()
{
    $var = "
        <script src=\"" . local_suport() . "AdminLTE-2.3.11/plugins/slimScroll/jquery.slimscroll.min.js\"></script>
        <script src=\"" . local_suport() . "AdminLTE-2.3.11/dist/js/app.min.js\"></script>
        <script src=\"" . local_suport() . "multi-step-modal-master/multi-step-modal\"></script>
        <script src=\"" . base_url() . "assets/bootstrap-fileinput-master/js/fileinput.min.js\"></script>
        <script src=\"" . base_url() . "assets/bootstrap-toggle-master/js/bootstrap-toggle.min.js\"></script>
        <script src=\"" . base_url() . "assets/custom/hippo_ajax.js\"></script>
    ";

    return $var;
}

function blobDecode($blob)
{
    if (!is_string($blob) || $blob === "") {
        return array();
    }

    $decoded = base64_decode($blob, true);
    if ($decoded === false) {
        return array();
    }

    $data = @unserialize($decoded);
    if ($data === false && $decoded !== "b:0;") {
        return array();
    }

    return $data;
}

function heSafeUnserializeNoObject($serialized, $default = null)
{
    if (!is_string($serialized) || $serialized === "") {
        return $default;
    }

    // Blok object injection dari payload request.
    if (preg_match('/(^|;|\\{|\\})[OC]:\\d+:/i', $serialized)) {
        return $default;
    }

    $data = @unserialize($serialized);
    if ($data === false && $serialized !== "b:0;") {
        return $default;
    }

    return $data;
}

function blobDecodeRequestMixed($blob, $default = null)
{
    if (!is_string($blob) || $blob === "") {
        return $default;
    }

    $decoded = base64_decode($blob, true);
    if ($decoded === false) {
        return $default;
    }

    $jsonData = json_decode($decoded, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        return $jsonData;
    }

    return heSafeUnserializeNoObject($decoded, $default);
}

function blobDecodeRequest($blob, $default = array())
{
    $mixed = blobDecodeRequestMixed($blob, $default);
    if (!is_array($mixed)) {
        return $default;
    }

    return $mixed;
}

function blobEncode($array)
{

    return base64_encode(serialize($array));
}

function gotoLogin()
{
    $curent = $_SERVER['REQUEST_URI'];
    $curent_e = blobEncode($curent);

    redirect(base_url() . "auth/Login?xxx=$curent_e");
    //    redirect(base_url() . "Login/authLogout?xxx=$curent_e");
    mati_disini(__LINE__ . "");
}

function imageSizeAllow()
{
    $max_size = 10000000;

    return $max_size;
}

function rename_array_key($array, $old_key, $new_key)
{
    if (!array_key_exists($old_key, $array)) {
        return $array;
    }
    $new_array = array();
    foreach ($array as $key => $value) {
        $new_key = $old_key === $key ? $new_key : $key;
        $new_array[$new_key] = $value;
    }

    return $new_array;
}

function validateUserSession($att)
{

    $ci = &get_instance();
    $ci->load->model("Mdls/" . "MdlEmployee");
    $ci->load->config("heWebs");
    $webLogin = $ci->config->item('logins');
    $o = new MdlEmployee();

    /* =================================================================================================
     * menambahakn id yg diperbolehkan multy session diluar user ghost di config heWebs ['multySessionLogin']
     * =================================================================================================*/

    $multiSessions = $webLogin['allowedMultySession'];
    $maintenance = $ci->config->item('maintenance');

    // arrPrint($multiSessions);
    $o->setFilters(array());
    $empKoloms = array(
        "id",
        "ghost",
        "nama",
        "last_dtime_active",
        "phpsessid",
        "ipadd",
        "devices",
    );

    $ci->db->select($empKoloms);
    $tmpUser = $o->lookupByCondition(array(
        "id" => $att,

    ))->result();

    if ((show_debuger() != 1) && ($maintenance != 0) && ($tmpUser[0]->ghost != 1)) {
        $logout = base_url() . "auth/Login/authLogout";
        // echo "tanpa debuger $logout";
        echo "<meta http-equiv='refresh' content=\"3;URL='$logout'\">";
    }
    else {
        /* =================================================
         * special id ghost tidak akan dibuat logout
         * ================================================*/
    }

    // arrPrint($tmpUser->ghost);
    // arrPrint($tmpUser[0]);

    /* ==========================================================================================
     * membuat timeStamp setiap kali terjadi reload aplikasi, untuk diperhitungakan idel timenya
     * =========================================================================================*/
    // region update last active
    $o->setFilters(array());
    $o->updateData(array("id" => $att), array("last_dtime_active" => dtimeNow()));
    // endregion update last active

    if ((key_exists($att, $multiSessions)) or ($tmpUser[0]->ghost == 1)) {
        /* ===========================================================================================
         * multy session dikenakan untuk user ghost
         * regular user jika menghendaki multy session harus ditambahan di config -> heWebs -> multySessionLogin
         * ===========================================================================================*/
    }
    else {

        if (sizeof($tmpUser) > 0) {
            $sesDef = $tmpUser[0]->phpsessid;
            $ipadd = $tmpUser[0]->ipadd;
            $devices = $tmpUser[0]->devices;
            $sesNow = isset($ci->session->login['phpsessid']) 
                ? $ci->session->login['phpsessid'] 
                : (isset($_SESSION['login']['phpsessid']) ? $_SESSION['login']['phpsessid'] : null);
            if (!empty($sesDef) && !empty($sesNow) && ($sesDef != $sesNow)) {
                $arrSwal = array(
                    "title" => "Session ended",
                    "html" => "Your id was login on $ipadd by $devices.",
                    "type" => "warning",
                );

                $ci->session->sess_destroy();


                //redirect(base_url() . get_class($this) . "/authLogin");
                $unregList = array("PROED",
                    "BHED"
                );
                foreach ($unregList as $s) {
                    $_SESSION[$s] = NULL;
                    unset($_SESSION[$s]);
                }

                $warning = "";
                $warning .= "You have logged in at $ipadd using " . heReturnDeviceName($devices)['device'] . "/" . heReturnDeviceName($devices)['browser'];
                $_SESSION['errMsg'] = $warning;
                $curent = $_SERVER['REQUEST_URI'];
                $curent_e = blobEncode($curent);
                $errMsg = blobEncode($warning);
                redirect(base_url() . "auth/Login/authLogout?xxx=$curent_e&e=$errMsg");
            }
            else {
                //        die("else");
                return true;
            }
        }
        else {

            $warning = "";
            $warning .= "You have been kicked out because you session is no longer valid";
            $_SESSION['errMsg'] = $warning;
            $curent = $_SERVER['REQUEST_URI'];
            $curent_e = blobEncode($curent);
            $errMsg = blobEncode($warning);
            redirect(base_url() . "auth/Login/authLogout?xxx=$curent_e&e=$errMsg");
        }
    }
}

function validateUserPageSession($att)
{
    $timeLimit = 20;//menit;
    $ci = &get_instance();
    $ci->load->model("Mdls/" . "MdlEmployee");
    $o = new MdlEmployee();

    $o->setFilters(array());
    $tmpUser = $o->lookupByCondition(array(
        "id" => $att,

    ))->result();


    $ci->load->model("Mdls/" . "MdlActivityLog");
    $hTmp = new MdlActivityLog();
    $hTmp->setFilters(array());
    $hTmp->addFilter("uid='" . $att . "'");
    $ci->db->order_by("id", "desc");
    $ci->db->limit(1);
    $tmp = $hTmp->lookupAll()->result();

    $result = array(
        "page" => "",
        "time" => "",
        "url" => "",
    );
    if (sizeof($tmp) > 0) {
        $result = array(
            "page" => $tmp[0]->controller . "/" . $tmp[0]->method,
            "time" => $tmp[0]->dtime,
            "url" => $tmp[0]->url,
        );
    }


    $limitedPageConfig = $ci->config->item("validatedPages");
    if (in_array($result['page'], $limitedPageConfig)) {

        $awal = date_create($result['time']);
        $akhir = date_create(); // waktu sekarang
        $diff = date_diff($awal, $akhir);
        $selisih = $diff->i;
        //        echo "selisih: $selisih";
        //        echo lgShowAlert("page ".$result['page']." needs to be validated each $timeLimit (now $selisih)");
        if ($selisih >= $timeLimit) {

            $warning = "";
            $warning .= "[" . date("H:i") . "]. Your idle time in " . $result['page'] . " has reached the limit of " . ($timeLimit) . " minutes. Please re-login to use the system again.";
            $_SESSION['errMsg'] = $warning;
            $curent = $result['url'];
            $curent_e = blobEncode($curent);
            $errMsg = blobEncode($warning);
            redirect(base_url() . "auth/Login/authLogout?xxx=$curent_e&e=$errMsg");
        }
    }
    else {
        //        echo lgShowAlert("halaman TIDAK ini perlu divalidasi");
    }


    return $result;


}

function validatePlace($master, $variabel)
{
    if ($master != $variabel || my_cabang_id() == 0) {
        // cekHere($this->configUiJenis["place"]);
        cekHijau(my_cabang_id());
        switch (my_cabang_id()) {
            case "0":
                $html = "cabang id anda tidak valid <br>Silahkan pilih cabang terlebih dahulu";
                break;
            default:
                $html = 'Anda Terdeteksi berganti cabang <br><r class=text-uppercase>transaksi tidak bisa dilanjutkan</r>';
                break;
        }

        $out = base_url() . "auth/Login/authBranchLogout";

        echo "<script>
            top.swal({
                type: 'warning',
                html: '$html',
                allowOutsideClick: false,
            }).then(
                function(result) {
                   location.href='$out';                                         
                },                
            );
        </script>";
        // echo die();
        // redirect($out);
        // cabang_id
        // matiHere(__LINE__);
    }
}

function getDefaultWarehouseID($cabangID, $deviceID = 0)
{

    if ($cabangID == "-1") {
        $gudangID = "-1";
        $gudangName = "default center warehouse";
    }
    else {
        if ($deviceID > 0) {
            $gudangID = ($cabangID * -10) - ($deviceID);
            $gudangName = "sub-branch #$cabangID/$gudangID";
        }
        else {
            $gudangID = ($cabangID * -10);
            $gudangName = "default warehouse at branch #$cabangID";
        }

//        cekMerah($cabangID . "::" . $deviceID . "::" . $gudangID . "::" . $gudangName);
        //matiHere();
        //        $gudangID = ($cabangID * -10);
        //        $gudangName = "default warehouse at branch #$cabangID";

    }

    return array(
        "gudang_id" => $gudangID,
        "gudang_nama" => $gudangName,
    );

}

function getPOSWarehouseID($posID, $cabangID, $deviceID = 0)
{

    if ($cabangID == "-1") {
        $gudangID = "-1";
        $gudangName = "default center warehouse";
    }
    else {
        if ($deviceID > 0) {
            //            $gudangID = ($cabangID * -10)-($deviceID);
            //            $gudangName = "sub-branch #$cabangID/$gudangID";

            $gudangID = ($cabangID * -10) . ($posID);
            $gudangName = "sbr #$gudangID";
        }
        else {
            $gudangID = ($cabangID * -10);
            $gudangName = "default warehouse at branch #$cabangID";
        }


        //        $gudangID = ($cabangID * -10);
        //        $gudangName = "default warehouse at branch #$cabangID";

    }

    return array(
        "gudang_id" => $gudangID,
        "gudang_nama" => $gudangName,
    );

}

function randomNumber($length)
{
    $result = '';

    for ($i = 0; $i < $length; $i++) {
        $result .= mt_rand(0, 9);
    }

    return $result;
}

function validate_EAN13Barcode($barcode)
{
    // check to see if barcode is 13 digits long
    if (!preg_match("/^[0-9]{13}$/", $barcode)) {
        return false;
    }

    $digits = $barcode;

    // 1. Add the values of the digits in the
    // even-numbered positions: 2, 4, 6, etc.
    $even_sum = $digits[1] + $digits[3] + $digits[5] + $digits[7] + $digits[9] + $digits[11];

    // 2. Multiply this result by 3.
    $even_sum_three = $even_sum * 3;

    // 3. Add the values of the digits in the
    // odd-numbered positions: 1, 3, 5, etc.
    $odd_sum = $digits[0] + $digits[2] + $digits[4] + $digits[6] + $digits[8] + $digits[10];

    // 4. Sum the results of steps 2 and 3.
    $total_sum = $even_sum_three + $odd_sum;

    // 5. The check character is the smallest number which,
    // when added to the result in step 4, produces a multiple of 10.
    $next_ten = (ceil($total_sum / 10)) * 10;
    $check_digit = $next_ten - $total_sum;

    // if the check digit and the last digit of the
    // barcode are OK return true;
    if ($check_digit == $digits[12]) {
        return true;
    }

    return false;
}

function reversePpn($dp_nilai, $ppn_persen)
{
    $pokok_nilai = (($dp_nilai * 100) / (100 + $ppn_persen));
    $ppn_nilai = $pokok_nilai * ($ppn_persen / 100);

    $arrTemp = array(
        "ppn" => $ppn_nilai,
        "pokok" => $pokok_nilai,
    );

    return $arrTemp;


}

function dueDate($dtime, $top)
{
    $tanggal_detik = dtimeToSecond($dtime);
    $default_jatuh_tempo = dayToSecond($top);
    $hari_detik = $default_jatuh_tempo;

    $tanggal_hari_detik = $tanggal_detik + $hari_detik;
    $tgl_jatuh_tempo = date("Y-m-d", $tanggal_hari_detik);

    return $tgl_jatuh_tempo;
}

function print_r_reverse($in)
{
    $lines = explode("\n", trim($in));
    if (trim($lines[0]) != 'Array') {
        // bottomed out to something that isn't an array
        return trim($in);
    }
    else {
        // this is an array, lets parse it
        if (preg_match("/(\\s{5,})\\(/", $lines[1], $match)) {
            // this is a tested array/recursive call to this function
            // take a set of spaces off the beginning
            $spaces = $match[1];
            $spaces_length = strlen($spaces);
            $lines_total = count($lines);
            for ($i = 0; $i < $lines_total; $i++) {
                if (substr($lines[$i], 0, $spaces_length) == $spaces) {
                    $lines[$i] = substr($lines[$i], $spaces_length);
                }
            }
        }
        array_shift($lines);
        // Array
        array_shift($lines);
        // (
        array_pop($lines);
        // )
        $in = implode("\n", $lines);
        // make sure we only match stuff with 4 preceding spaces (stuff for this array and not a nested one)
        preg_match_all("/^\\s{4}\\[(.+?)\\] \\=\\> /m", $in, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
        $pos = array();
        $previous_key = '';
        $in_length = strlen($in);
        // store the following in $pos:
        // array with key = key of the parsed array's item
        // value = array(start position in $in, $end position in $in)
        foreach ($matches as $match) {
            $key = $match[1][0];
            $start = $match[0][1] + strlen($match[0][0]);
            $pos[$key] = array($start,
                $in_length
            );
            if ($previous_key != '') {
                $pos[$previous_key][1] = $match[0][1] - 1;
            }
            $previous_key = $key;
        }
        $ret = array();
        foreach ($pos as $key => $where) {
            // recursively see if the parsed out value is an array too
            $ret[$key] = print_r_reverse(substr($in, $where[0], $where[1] - $where[0]));
        }

        return $ret;
    }
}

function digit_2($num)
{
    $ncGlobal = sprintf("%02d", $num);
    return $ncGlobal;
}

function digit_3($num)
{
    $digits = array(
        1 => "00",
        2 => "0"
    );
    $dGlobal = sizeof(str_split($num * 1, 1));
    $ncGlobal = "";
    $ncGlobal = sprintf("%03d", $num);
    return $ncGlobal;
}

function digit_4($num)
{
    $digits = array(
        1 => "000",
        2 => "00",
        3 => "0",
    );
    $dGlobal = sizeof(str_split($num * 1, 1));
    $ncGlobal = "";
    $ncGlobal = sprintf("%04d", $num);
    return $ncGlobal;
}

function digit_5($num)
{
    $digits = array(
        1 => "0000",
        2 => "000",
        3 => "00",
        4 => "0",
        // 5 => "0",
    );
    // $explode = explode("-",$fieldValue);

    // $cGlobal = end($explode);
    $dGlobal = sizeof(str_split($num * 1, 1));
    // cekHere($dGlobal);
    $ncGlobal = "";
    // $ncGlobal = key_exists($dGlobal,$digits) ? $digits[$dGlobal].$num : $num;
    $ncGlobal = sprintf("%05d", $num);

    // $newFormat = str_replace($cGlobal,$ncGlobal,$fieldValue);
    return $ncGlobal;
}

function customCounters($tmps, $code)
{
    if (sizeof($tmps)) {
        if (isset($tmps[0]->counters)) {
            $counterTop = blobDecode($tmps[0]->counters);
            // arrPrint($counterTop);
            $vars = array();
            foreach ($counterTop as $c_key => $c_val) {
                foreach ($c_val as $cc_key => $cc_val) {
                    $vars[$cc_key] = $cc_val;
                }
            }
        }
    }
    arrPrint($code);
    if (is_array($code)) {
        $hasil = "";
        foreach ($code as $ky => $item) {

            $var = "$item";
            if ($hasil == "") {
                $hasil = "$var";
            }
            else {
                $hasil = $hasil . "|" . $var;
            }


            $key = $hasil;
        }
        arrPrintWebs($key);

        return $vars[$key];
    }
    else {
        $key = $code;
        arrPrint($key);

        return $vars[$key];
    }


}

function validateDueDate($id, $dtime)
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlCustomer");
    $ci->load->model("MdlTransaksi");
    $c = new MdlCustomer();
    //    $c->addFilter("id='$id'");
    $customersData = $c->lookupByID($id)->result();
    $maxLimit = $customersData[0]->kredit_limit;
    //paggil due date transaksi
    $tr = new MdlTransaksi();

    $tr->setFilters(array());
    $trDue = $tr->lookupDueDate($id);
    cekHere("$id iki id customers");
    if (isset($trDue["transaksi_id"]) && $trDue["transaksi_id"] > 0) {
        $tr->setFilters(array());
        $tr->addFilter("id='" . $trDue['transaksi_id'] . "'");
        $topTransaction = $tr->lookupMainTransaksi()->result();
        $nomer_top = formatField("nama", $topTransaction[0]->nomer_top);
    }

    //    arrPrint($topTransaction);

    $data = array();
    if ($trDue['due_date'] > 0) {
        $due = strtotime($trDue['due_date']);
        $now = strtotime($dtime);
        $nomer = formatField("nama", $trDue['nomer']);
        //        cekHere($trDue['due_date']."||$due||$now");
        if ($now < $due) {
            $data = array(
                "allow_create" => "true",
                "error" => "",
            );
        }
        else {
            $data = array(
                "allow_create" => "false",
                "error" => "transaksi tidak dapat dilanjutkan karena, <br> $nomer_top ($nomer) <br>Over due <br> silahkan hubungi bapak Hindra untuk minta otorisasi overdue",
            );
        }


    }
    else {
        $data = array(
            "allow_create" => "true",
            "error" => "",
        );
    }

    return $data;
    //    arrPrint($trDue);
}

function validateOverDue($id)
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlOverDuePass");
    $o = new MdlOverDuePass();
    $o->addFilter("customers_id='$id'");
    $o->addFilter("jenis='forever'");
    $temp = $o->lookupAll()->result();
    // cekLime($ci->db->last_query());
    // arrPrint($temp);
    $status = 0;
    if (sizeof($temp) > 0) {
        $status = $temp['0']->status;
    }

    $data = array();
    if ($status > 0) {
        $data = array(
            "status" => "allowed",

        );
    }
    else {
        $data = array(
            "status" => "suspended",

        );
    }
    return $data;

}

/* ==========================================================================================================
    bila parameter step dan format tidak diisi akan mengeluarkan seluruh step dan nomernya dalam format array
 * ==========================================================================================================*/
function showHistoriGlobalNumbers($ids_his, $step = "", $format = false, $jenis_master = "")
{
    $ci = &get_instance();
    $masterConfigUi = $ci->config->item("heTransaksi_ui");
    $modul = isset($masterConfigUi[$jenis_master]['modul']) ? $masterConfigUi[$jenis_master]['modul'] : "";
    $modul_path = base_url() . "$modul/";

    $idHist = blobDecode($ids_his);


    $allNumbers = array();
    if (is_array($idHist)) {

        foreach ($idHist as $stepnya => $items) {
            $nomer = $items['nomer'];
            // arrPrint($items);
            // cekMerah($step . ":: $nomer");
            $cgHist = showGlobalNumber($items['counters'], $nomer);


            if ($format == true) {
                // $nomer_global = formatField("nomer", $nomer) . "&#x2011;" . $cgHist;
                $nomer_global = formatField_he_format("nomer", $nomer, $jenis_master, $modul_path) . "&#x2011;" . $cgHist;
            }
            else {

                $nomer_global = $nomer . "&#x2011;" . $cgHist;
            }

            // cekHijau($nomer_global);

            $allNumbers[$stepnya] = $nomer_global;
        }
    }

    $var = "";
    if ($step == "") {
        // cekHijau(__METHOD__);
        $var = $allNumbers;
        // $var = (object)$allNumbers;
    }
    else {
        $var = isset($allNumbers[$step]) ? $allNumbers[$step] : "";
    }

    return $var;
}

/* ==========================================================================================================
 * $counters,$nomer ngambil apaadanya dari isi kolom dB
 * ==========================================================================================================*/
function showGlobalNumber($counters, $nomer)
{
    is_array($counters) ? matiHere("var counters terdeteksi array :: " . __FILE__ . " @" . __LINE__) : "";

    $counters = blobDecode($counters);

    $cNomerExpl = explode(".", $nomer);
    $cTrcode = $cNomerExpl[0];
    $cTrplace = $cNomerExpl[1];
    $cgJenis = "$cTrcode|$cTrplace";

    $var = digit_5($counters["stepCode|placeID"]["$cgJenis"]);

    return $var;
}

function sendToSession($hippo_link, $wadah)
{
    $var = "getData('$hippo_link?v='+this.value+'&n='+this.name,'$wadah')";
    return $var;
}

function definitionButton()
{
    $result = array(
        "safely delete" => "transaksi mundur 1 langkah dan dilakukan oleh pembuat transaksi.",
        "undo" => "transaksi mundur 1 langkah dan dilakukan oleh otorisator (yang melakukan otorisasi) transaksi.",
        "reject 1 step" => "transaksi mundur 1 langkah dan dilakukan oleh pihak selanjutnya atau yang lebih tinggi dari rangkaian suatu transaksi.",
        "reject all steps" => "transaksi akan dimatikan sampai dengan requestnya dan tidak bisa digunakan lagi.",
        "close/fullfillment" => "menutup outstanding item untuk transaksi ini.",
    );

    return $result;
}

function sendNotif($TRid, $jenis, $msg)
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlKirimNotif");
    $c = new MdlKirimNotif();
    $c->addFilter("tr_id='$TRid'");
    $c->addFilter("jenis='$jenis'");
    $tmp = $c->lookUpAll()->result();
    if (sizeof($tmp) > 0) {
        cekLime("allready notified");
    }
    else {
        cekBiru("yokk kirim notif");

    }

    matiHEre("hoppp send notif");
    return true;
    //    arrPrint($trDue);
}

// ================================================= =================================================
function unbalanceNotif($trID, $mode, $data = array())
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlKirimNotifUnbalance");
    $c = new MdlKirimNotifUnbalance();
    $c->addFilter("transaksi_id='$trID'");
    $c->addFilter("mode='$mode'");
    $tmp = $c->lookUpAll()->result();
    if (sizeof($tmp) > 0) {
        cekLime("allready notified");
    }
    else {
//        cekBiru("yokk kirim notif");
        if (sizeof($data) > 0) {
            $c = new MdlKirimNotifUnbalance();
            $c->addData($data);
//            cekMerah($ci->db->last_query());
        }
        else {
//            cekMerah("tidak nulis notif");
        }

    }

    return true;
}

function niceDecimal($fieldVal)
{
    return str_replace('.00', '', number_format($fieldVal, 2));
}

function nestedLowercase($value)
{
    if (is_array($value)) {
        return array_map('nestedLowercase', $value);
    }
    return strtolower($value);
}

//----------------------------
function notifTransaksi()
{
    $str = "";
    $str .= "<span class='badge bg-red'>#</span>";
    $str .= "<span>&nbsp; notif merah: transaksi yang membutuhkan tindak lanjut dari id yang sedang login.</span>";
    $str .= "<br>";
    $str .= "<span class='badge bg-yellow'>#</span>";
    $str .= "<span>&nbsp; notif kuning: transaksi yang membutuhkan tindak lanjut dari pihak lain.</span>";

    return $str;
}

function createTree($datas)
{
}

// versi MODUL-----------------
function cCodeBuilderMisc($jenisTr)
{
    $var = "_TR_" . $jenisTr;

    return $var;
}

function createStateSign_he_misc($current, $totalAvail, $jenisTr = NULL, $configUi)
{
    $ci = &get_instance();
    $array_step_label = isset($configUi[$jenisTr]['steps']) ? $configUi[$jenisTr]['steps'] : array();


    $str = "";
    for ($i = 1; $i <= $totalAvail; $i++) {
        $step_label = isset($array_step_label[$i]['label']) ? $array_step_label[$i]['label'] : "-";
        if ($i <= $current) {
            $str .= "<span class='fa fa-check-circle text-black'> $step_label</span><br>";
        }
        else {
            $str .= "<span class='fa fa-circle text-grey'> $step_label</span><br>";
        }
    }

    // tambahan next activity
    if ($jenisTr != NULL) {
        $next_step_number = $current + 1;
        $ci = &get_instance();
        $next_step_label = isset($configUi[$jenisTr]['steps'][$next_step_number]['label']) ? $configUi[$jenisTr]['steps'][$next_step_number]['label'] : "-";

        $str .= "<br>";
        $str .= "<span class='text-black' style='font-weight:bold;font-size:12px;'>Next Step:</span><br>";
        $str .= "<span class='text-red' style='font-weight:bold;font-size:12px;'>$next_step_label</span>";
    }


    return $str;
}

function createStateHorizontal_he_misc($current, $totalAvail, $jenisTr = NULL, $showLabel = true, $configUi)
{
    $ci = &get_instance();
    $array_step_label = isset($configUi[$jenisTr]['steps']) ? $configUi[$jenisTr]['steps'] : array();


    $str = "";
    // $str .= "<div class='horizontal timeline'>";
    $str .= "<ul class='timeline-kecil'>";
    for ($i = 1; $i <= $totalAvail; $i++) {
        $step_label = isset($array_step_label[$i]['label']) ? $array_step_label[$i]['label'] : "-";
        if ($i <= $current) {
            //     $str .= "<span class='fa fa-check-circle text-black'> $step_label</span>";
            $bg_warna = "done-tl";
        }
        elseif ($i == ($current + 1)) {
            $bg_warna = "now-tl";
        }
        else {
            //     $str .= "<span class='fa fa-circle text-grey'> $step_label</span>";
            $bg_warna = "none";
        }
        // if($showLabel == true){
        $labelDisplay = $showLabel == true ? "" : "style='display:none;'";
        // }

        $str .= "<li class='$bg_warna no-margin'>";
        // $str .= "<span style='display: none;'>$step_label</span>";
        $str .= "<span $labelDisplay>$step_label</span>";
        $str .= "</li>";
    }
    // $str .= "</div>";

    // $str .= "<div class='line'></div>";
    $str .= "</ul>";

    // tambahan next activity
    if ($jenisTr != NULL) {
        // $next_step_number = $current + 1;
        // $ci = &get_instance();
        // $next_step_label = isset($configUi[$jenisTr]['steps'][$next_step_number]['label']) ? $configUi[$jenisTr]['steps'][$next_step_number]['label'] : "-";

        // $str .= "<br>";
        // $str .= "<span class='text-black' style='font-weight:bold;font-size:12px;'>Next Step:</span><br>";
        // $str .= "<span class='text-red' style='font-weight:bold;font-size:12px;'>$next_step_label</span>";
    }


    return $str;
}

function createStateMap_he_misc($current, $totalAvail, $steps, $jenisTr, $configUi)
{
    $ci = &get_instance();
    $str = "";

    $str .= "<span style='font-family:georgia;'>";
    $trConfig = $configUi[$jenisTr];
    $stepIcon = $trConfig['icon'];
    //    $str.="<span class='$stepIcon' style='font-size:1.3em;color:#ff7700;'></span> ";
    $iCtr = 0;
    for ($i = 1; $i <= $totalAvail; $i++) {
        $iCtr++;

        if ($i == $current) {
            $numIcon = "<span class='badge text-white bg-blue'>$i</span>";
            $str .= "<span class='text-blue'>$numIcon " . $steps[$i] . "</span>";
        }
        else {
            if ($i < $current) {
                $numIcon = "<span class='badge text-white' style='background:#345678;'>$i</span>";
                $str .= "<span class='' style='color:#345678;'>$numIcon " . $steps[$i] . "</span>";
            }
            else {
                $numIcon = "<span class='badge text-white bg-gray'>$i</span>";
                $str .= "<span class='text-grey'>$numIcon " . $steps[$i] . "</span>";
            }
        }
        if ($i < $totalAvail) {
            $str .= " <span class='fa fa-angle-double-right'> ";
        }

    }
    $str .= "</span>";

    return $str;
}

function createStateHorizontalMap_he_misc($current, $totalAvail, $steps, $jenisTr, $configUi)
{
    $ci = &get_instance();
    $str = "";

    // $str .= "<span style='font-family:georgia;'>";
    $trConfig = $configUi[$jenisTr];
    $stepIcon = $trConfig['icon'];
    //    $str.="<span class='$stepIcon' style='font-size:1.3em;color:#ff7700;'></span> ";
    $iCtr = 0;
    $str .= "<ul class='timeline'>";
    for ($i = 1; $i <= $totalAvail; $i++) {
        $iCtr++;
        $step_label = $steps[$i];
        if ($i == $current) {
            $numIcon = "<span class='badge text-white bg-blue'>$i</span>";
            // $str .= "<span class='text-blue'>$numIcon " . $steps[$i] . "</span>";
            $bg_warna = "now-tl";
        }
        else {
            if ($i < $current) {
                $numIcon = "<span class='badge text-white' style='background:#345678;'>$i</span>";
                // $str .= "<span class='' style='color:#345678;'>$numIcon " . $steps[$i] . "</span>";
                $bg_warna = "done-tl";
            }
            else {
                $numIcon = "<span class='badge text-white bg-gray'>$i</span>";
                // $str .= "<span class='text-grey'>$numIcon " . $steps[$i] . "</span>";
                $bg_warna = "none";
            }
        }

        $str .= "<li class='$bg_warna no-margin'>";
        $str .= "<span>$step_label</span>";
        $str .= "</li>";

    }
    $str .= "</ul>";

    return $str;
}

function loadConfigModulJenis_he_misc($jenisTransaksi, $fileConfig)
{
    // $arrAlias = array(
    //     "coTransaksiUi"     => "heTransaksi_ui",
    //     "coTransaksiCore"   => "heTransaksi_core",
    //     "coTransaksiLayout" => "heTransaksi_layout",
    // );
    $CI = &get_instance();
    $heTransaksi_ui = $CI->config->item("heTransaksi_ui")[$jenisTransaksi];

    if (isset($heTransaksi_ui["modul"])) {
        $modul_path = $heTransaksi_ui["modul"];
        $conf = "../../modules/$modul_path/config/";
        $CI->load->config($conf . "$fileConfig");
        $configData = $CI->config->item("$fileConfig")[$jenisTransaksi];
    }
    else {
        // matiHEre("modul config coTransksiCore belum diseting/ jenismaster dan target berbeda");
        $configData = $heTransaksi_ui;
    }


    return $configData;
}

//panggil semua configui yang ada di modul
function loadConfigUiModul_he_misc()
{
    $CI = &get_instance();

    $modul_pathSrc = $CI->config->item("heTransaksi_ui");
    if (sizeof($modul_pathSrc) > 0) {
        $modul_path = array();
        foreach ($modul_pathSrc as $jn => $jnData) {
            //cekHitam($jn);
            if (isset($jnData["modul"])) {
                $conf = "../../modules/" . $jnData['modul'] . "/config/";
                // cekHere("$conf :: $jn");
                $CI->load->config($conf . "coTransaksiUi");
                $modul_path[$jn] = isset($CI->config->item("coTransaksiUi")[$jn]) ? $CI->config->item("coTransaksiUi")[$jn] : cekMerah("heTransaksi_ui sudah siap modul, sayangnya config modul " . $jnData['modul'] . " untuk jenisTr $jn tidak ditemukan, tolong dilengkapi yak");
            }

            // arrPrint($modul_path);

        }
        // arrPrint($modul_path);
        // matiHEre();
        return $modul_path;
    }
    else {
        return array();
    }
    // matiHere();
    // $conf = "../../modules/$modul_path/config/";
}

//panggil semua configui yang ada di modul
function loadConfigUiModul()
{
    $CI = &get_instance();
    $modul_pathSrc = $CI->config->item("heTransaksi_ui");
    if (sizeof($modul_pathSrc) > 0) {
        $modul_path = array();
        foreach ($modul_pathSrc as $jn => $jnData) {

            if (isset($jnData["modul"])) {
                $conf = "../../modules/" . $jnData['modul'] . "/config/";
                $CI->load->config($conf . "coTransaksiUi");
                $modul_path[$jn] = $CI->config->item("coTransaksiUi")[$jn];
            }

            // arrPrint($modul_path);

        }
        return $modul_path;
    }
    else {
        return array();
    }
    matiHere();
    $conf = "../../modules/$modul_path/config/";
}

function loadConfigPathModul()
{
    $CI = &get_instance();
    $modul_pathSrc = $CI->config->item("heTransaksi_ui");
    if (sizeof($modul_pathSrc) > 0) {
        $modul_path = array();
        foreach ($modul_pathSrc as $jn => $jnData) {

            if (isset($jnData["modul"])) {
                $conf = "../../modules/" . $jnData['modul'] . "/config/";
                // $CI->load->config($conf . "coTransaksiUi");
                $modul_path[$jn] = $conf;
            }
            // arrPrint($modul_path);
        }
        return $modul_path;
    }
    else {
        return array();
    }
    // matiHere();
    // $conf = "../../modules/$modul_path/config/";
}

function loadConfigUiMaster()
{
    $ci = &get_instance();
    $masterConfigUi = $ci->config->item("heTransaksi_ui");

    return $masterConfigUi;
}

function getFileList($dir, $recurse = FALSE)
{
    $retval = array();

    // add trailing slash if missing
    if (substr($dir, -1) != "/") {
        $dir .= "/";
    }

    // open pointer to directory and read list of files
    $d = @dir($dir) or die("getFileList: Failed opening directory {$dir} for reading");
    while (FALSE !== ($entry = $d->read())) {
        // skip hidden files
        if ($entry[0] == ".") {
            continue;
        }
        if (is_dir("{$dir}{$entry}")) {
            $retval[] = array(
                'path' => "{$dir}{$entry}/",
                'name' => "$entry",
                'type' => filetype("{$dir}{$entry}"),
                'size' => 0,
                'lastmod' => filemtime("{$dir}{$entry}")
            );
            if ($recurse && is_readable("{$dir}{$entry}/")) {
                $retval = array_merge($retval, getFileList("{$dir}{$entry}/", TRUE));
            }
        }
        elseif (is_readable("{$dir}{$entry}")) {
            $retval[] = array(
                'path' => "{$dir}{$entry}",
                'name' => "$entry",
                'type' => mime_content_type("{$dir}{$entry}"),
                'size' => filesize("{$dir}{$entry}"),
                'lastmod' => filemtime("{$dir}{$entry}")
            );
        }
    }
    $d->close();

    return $retval;
}

function loadAllActiveGudang($cabangID)
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlGudangDefault_center");
    $ci->load->model("Mdls/MdlGudangDefault");
    $ci->load->model("Mdls/MdlGudang");
    $selectFields = array("id", "nama");
    //gcd gudang default (* yg pakai minus)
    $gcd = new MdlGudangDefault_center();
    $gdb = new MdlGudangDefault();
    $g = new MdlGudang();
    $gcd->addFilter("cabang_id='-1'");
    $tempCenterDefaultGudang = $gcd->lookUpAll()->result();
    $allWareHouse = array();
    foreach ($tempCenterDefaultGudang as $gCenter) {
        $temp = array();
        foreach ($selectFields as $i => $iField) {
            $temp[$iField] = $gCenter->$iField;
        }
        $allWareHouse[] = $temp;

    }
    // arrprint($tempCenterDefaultGudang);
    $gdb->addFilter("cabang_id='$cabangID'");
    $tempBranchGudang = $gdb->lookUpAll()->result();
    foreach ($tempBranchGudang as $gdefBranch) {
        // $allWareHouse[$gdefBranch->id]=$gdefBranch->nama;
        $temp = array();
        foreach ($selectFields as $i => $iField) {
            $temp[$iField] = $gdefBranch->$iField;
        }
        $allWareHouse[] = $temp;
    }
    // arrprint($tempBranchGudang);
    $g->addFilter("cabang_id='$cabangID'");
    $g->addFilter("id > '0'");
    $tempGudang = $g->lookUpAll()->result();
    foreach ($tempGudang as $tempGudang_o) {
        $temp = array();
        foreach ($selectFields as $i => $iField) {
            $temp[$iField] = $tempGudang_o->$iField;
        }
        $allWareHouse[] = $temp;
        // $allWareHouse[$tempGudang_o->id]=$tempGudang_o->nama;
    }
    return $allWareHouse;
    // arrprint($allWareHouse);

}

function test_table($datas)
{
    $td = "";
    foreach ($datas as $item) {
        // arrPrintHijau($item);
        $hd = "";
        $td .= "<tr>";
        foreach ($item as $kolom => $nilai) {
            $hd .= "<td>$kolom</td>";

            $td .= "<td>$nilai</td>";
        }
        $td .= "</tr>";

        $rekening = $item->rekening;

    }

    // echo "<button onClick=\"SelfCopy(this.id)\"  id=\"1\">1</button>";
    echo "<div id='2'>";
    echo "<table border='1' rules='all'>";
    echo "<tr>$hd</tr>";
    echo "$td";
    echo "</table>";
    echo "<div>";
}

//loader prebiaya coa
function produkrakitanPrebiaya()
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlProdukRakitanPreBiaya");
    $b = new MdlProdukRakitanPreBiaya();
    $temp = $b->lookUpAll()->result();
    $data = array();
    foreach ($temp as $tempData) {
        $data[$tempData->id] = array(
            "nama" => $tempData->nama,
            "coa_code" => $tempData->coa_code,
            "coa_code_2" => $tempData->coa_code_2,
        );
    }
    return $data;
    // arrPrint($data);
    // matiHEre();

}

function isMobile_he_misc()
{
    /* ----------------------------------------------------------------------
     * deteksi mobile auto atau hanya orang tertentu,
     * diatur di heWeb ::mobile
     * $ci->load->library('user_agent'); masuk list autoload
     * ----------------------------------------------------------------------*/

    $ci = &get_instance();
    $conf_mobile = $ci->config->item("mobile");

    $mobileAutoDetect = $conf_mobile['autoDetect'];
    $forcedMobile = $conf_mobile['forcedMobile'];
    $disallowedMobile = $conf_mobile['disallowedMobile'];

    $mobile = "";
    if (key_exists(my_id(), $disallowedMobile)) {
        $mobile = false;
    }
    else {
        if ($mobileAutoDetect == true) {
            if ($ci->agent->is_mobile()) {
                $mobile = true;
            }
            else {
                $mobile = false;
            }
        }
        else {
            if (isset($ci->session->login['forceMobile']) && $ci->session->login['forceMobile'] == 1 && $ci->agent->is_mobile()) {
                $mobile = true;
            }
        }
        if (key_exists(my_id(), $forcedMobile)) {
            $mobile = true;
        }
    }

    // $ci->agent->is_browser();
    // $ff = $ci->agent->browser().' '.$ci->agent->version();
    // arrPrintWebs($conf_mobile);

    return $mobile;
}

function addCommas($a)
{
    if (preg_match("/^[0-9,]+$/", $a)) {
        $a = str_replace(",", "", $a);
    }

    return number_format($a * 1);
}

function removeCommas($a)
{

    if (preg_match("/^[0-9,]+$/", $a)) {
        $a = str_replace(",", "", $a);
    }

    return $a * 1;
}

//-------------------------------
function my_fase_id()
{
    if (isset($_SESSION['login']['fase_id'])) {
        return $_SESSION['login']['fase_id'];
    }
    else {
        return 0;
    }
}

function my_fase_name()
{
    if (isset($_SESSION['login']['fase_nama'])) {
        return $_SESSION['login']['fase_nama'];
    }
    else {
        return 0;
    }
}

//-------------------------------
function getDefaultWarehouseProject($projectID, $projectNama, $deviceID = 0)
{

    if ($deviceID > 0) {
        $gudangID = ($projectID * -10) - ($deviceID);
        $gudangName = "sub-branch #$projectID/$gudangID";
    }
    else {
        $gudangID = ($projectID * -10);
        $gudangName = "gudang project $projectNama";
    }

    return array(
        "gudang_id" => $gudangID,
        "gudang_nama" => $gudangName,
    );

}

function getDefaultWarehouseProjectWorkorder($projectID, $workorderID, $workorderNama)
{
    $gudangID = "$projectID" . "$workorderID";
    $gudangName = "gudang workorder $workorderNama";

    return array(
        "gudang_id" => $gudangID,
        "gudang_nama" => $gudangName,
    );

}

function getDefaultWarehouseProjectSubWorkorder($projectID, $workorderID, $subWorkorderID, $subWorkorderNama, $source_spk)
{

    $ambil_angka_depan_spk = explode("/", $source_spk)[0];

    $gudangID = "$projectID" . "$workorderID" . "$subWorkorderID" . "$ambil_angka_depan_spk";
    $gudangName = "gudang workorder $subWorkorderNama";

    return array(
        "gudang_id" => $gudangID,
        "gudang_nama" => $gudangName,
    );

}


function projectBom($projectID, $handler = null)
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlProdukKomposisi");
    $b = new MdlProdukKomposisi();
    $b->setFilters(array());
    $b->addFilter("status='1'");
    $b->addFilter("trash='0'");
    $b->addFilter("produk_id='$projectID'");
    $temp = $b->lookUpAll()->result();
    cekHere($ci->db->last_query());
    $data = array();
    foreach ($temp as $tempData) {
        //        arrPrintWebs($tempData);
        $data[$tempData->jenis][$tempData->produk_dasar_id] = array(
            "handler" => $handler,
            "id" => $tempData->produk_dasar_id,
            "nama" => $tempData->produk_dasar_nama,
            "produk_nama" => $tempData->produk_dasar_nama,
            "name" => $tempData->produk_dasar_nama,
            //            "jml" => $tempData->jml,
            //            "qty" => $tempData->jml,
            //            "valid_qty" => $tempData->jml,
            //            "produk_ord_jml" => $tempData->jml,
            //            "harga" => $tempData->nilai,
            //            "subtotal" => $tempData->jml * $tempData->nilai,
            "jml" => 1,
            "qty" => 1,
            "valid_qty" => 1,
            "produk_ord_jml" => 1,
            "harga" => $tempData->nilai,
            "subtotal" => 1 * $tempData->nilai,
        );
    }
    //arrPrintHijau($data);

    return $data;


}

function aksesProject()
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlProjectInternAccess");
    $m = new MdlProjectInternAccess();
    $fields = $m->getListedFields();
    $temp = $m->lookUpAll()->result();
    $data = array();
    if (count($temp) > 0) {
        foreach ($temp as $temp_0) {
            $data[$temp_0->id] = (array)$temp_0;
        }
    }
    // $data = array(
    //     "1"=>array(
    //         "id"=>"1",
    //         "nama"=>"admin",
    //         "keterangan"=>"full akses",
    //     ),
    //     "2"=>array(
    //         "id"=>"2",
    //         "nama"=>"supervisi",
    //         "keterangan"=>"melakukan monitoring dan pemeriksaan project",
    //     ),
    //     "3"=>array(
    //         "id"=>"3",
    //         "nama"=>"anggota",
    //         "keterangan"=>"melakukan pembaharuan progres",
    //     ),
    //     "4"=>array(
    //         "id"=>"4",
    //         "nama"=>"gudang site",
    //         "keterangan"=>"menerima dan mengeluarkan material dari gudang site"
    //     ),
    //     "5"=>array(
    //         "id"=>"5",
    //         "nama"=>"pihak lain",
    //         "keterangan"=>"pihak atau sub kontraktor"
    //     ),
    // );
    return $data;
}

function createTaskListForm($project, $timWork, $workOrder, $link)
{
    /*
     *data projek sebagai title
     *data teamwork untuk dipilih
     *data work order untuk dipilih
     */
    $title = "DAFTAR TUGAS ( " . $project[0]->nama . " )";

    $data = "<div class='box box-warning'>";
    $data .= "<div class='box-header with-border'><h3>$title</h3></div>";
    $data .= "<div class='box-body'>";
    $data .= "<form class='form-control' target='result' method='post' action='$link'>";
    $data .= "<div class='form-group'>";
    $data .= "<label>Rencana kerja</label>";
    $data .= "<input>";
    $data .= "</div>";

    $data .= "</form >";
    $data .= "</div >";
    $data .= "</div >";


    // matiHere();
    return $data;
}

function showRincian($trID, $modeShow, $print_metode)
{

    $ci =  &get_instance();
    $ci->load->model("Mdls/MdlProdukProject");
    $ci->load->model("Mdls/MdlProjectWorkOrder");
    $ci->load->model("Mdls/MdlProjectKomposisiWorkorder");
    $ci->load->model("Mdls/MdlProjectKomposisiWorkorderSub");
    $p = new MdlProdukProject();
    $w = new MdlProjectWorkOrder();
    $k = new MdlProjectKomposisiWorkorder();
    $kw = new MdlProjectKomposisiWorkorderSub();


    $p->addFilter("transaksi_id='$trID'");
    $tempProduk = $p->lookUpAll()->result();
    //    cekHitam($ci->db->last_query());
    $pid = $tempProduk[0]->id;

    //ambil data actuive fase /wo
    $w->addFilter("produk_id='$pid'");
    $tempWo = $w->lookUpAll()->result();
    $activeWo = $tempWo[0]->id;
    cekHitam($ci->db->last_query());
    $womasterData = array();
    foreach ($tempWo as $tempWo_0) {
        $womasterData[$tempWo_0->id] = $tempWo_0;
    }
    //    cekHitam($ci->db->last_query());
    $k->addFilter("produk_id='$pid'");
    $k->addFilter("bahan_utama='1'");
    $tempKomposisiWo = $k->lookUpKomposisiFase();
    arrPrint($tempKomposisiWo);
    cekHitam($ci->db->last_query());
    $k->setFilters(array());
    $k->addFilter("produk_id='$pid'");
    $k->addFilter("jenis='jual'");
    $tempKomposisiJualWo = $k->lookupAll()->result();
    //    cekHitam($ci->db->last_query());
    $hrgJualKomp = array();
    if (!empty($tempKomposisiJualWo)) {
        $selectField = array(
            "id",
            "produk_dasar_id",
            "produk_id",
            "bahan_utama",
            "produk_nama",
            "jml",
            "jenis",
            "produk_nama",
            "produk_dasar_nama",
            "nilai",
            "harga",
            "fase_id",
            "gudang_id",
            "gudang2_id",
            "cat_id",
            "cat_nama",
            "satuan",
            "satuan_id",
            "fase_id",
            "jenis",
            "status",
            "trash"
        );
        foreach ($tempKomposisiJualWo as $tmp_0) {
            $tmp = array();
            foreach ($selectField as $i => $field) {
                $tmp[$field] = $tmp_0->$field;
            }
            $hrgJualKomp[$tmp_0->fase_id] = $tmp;
        }
    }

    $komposisiHeader = array("jml" => "qty", "nama", "subtotal");
    $detil = array();
    foreach ($tempKomposisiWo as $woID => $woData) {
        if (isset($woData["produk"])) {
            foreach ($woData["produk"] as $produkDatas) {
                $detil[$produkDatas["fase_id"]]["produk"][$produkDatas["id"]] = $produkDatas["produk_dasar_nama"];
                $detil[$produkDatas["fase_id"]]["pjml"][$produkDatas["id"]] = $produkDatas["jml"];
                //                $detil[$produkDatas["fase_id"]][$produkDatas["id"]]["nama"]     = $produkDatas["produk_dasar_nama"];
                //                $detil[$produkDatas["fase_id"]][$produkDatas["id"]]["jml"]      = $produkDatas["jml"];
                //                $detil[$produkDatas["fase_id"]][$produkDatas["id"]]["satuan"]   = $produkDatas["satuan"];
                if (isset($hrgJualKomp[$produkDatas["fase_id"]])) {
                    $detil[$produkDatas["fase_id"]]["harga_jual"] = $hrgJualKomp[$produkDatas["fase_id"]]["harga"];
                    //                    $detil[$produkDatas["fase_id"]][$produkDatas["id"]]["harga"] = $hrgJualKomp[$produkDatas["fase_id"]]["harga"];
                }
            }
        }
    }

    $komposisiSubWorkOrder = array();
    $kw->setFilters(array());
    $kw->addFilter("produk_id='$pid'");
    $kw->addFilter("jenis_transaksi=5582");
    $kw->addFilter("fase_id=$activeWo");
    $kw->addFilter("trash='0'");
    $kw->addFilter("status='1'");
    $tmp = $kw->lookUpAll()->result();
//    cekMerah($ci->db->last_query());

    $selectShowFields = array(
        "produk_dasar_nama" => "item",
        "satuan" => "satuan",
        "jml" => "jml",
        "harga" => "harga satuan",
    );
    $itemKomposisi = array();
    if (count($tmp) > 0) {
        foreach ($tmp as $ii => $tmp_0) {
            foreach ($selectShowFields as $kol => $alias) {
                $itemKomposisi[$ii][$kol] = $tmp_0->$kol;
            }
        }
    }
    //    arrPrint($itemKomposisi);
    //    cekBiru($ci->db->last_query());

    //arrPrintKuning($womasterData);
    //arrPrintWebs($hrgJualKomp);
    switch ($print_metode) {
        case "simple":
            $tblData = "<table class='table table-responsive table-bordered'>";
            $tblData .= "<thead>";
            $tblData .= "<tr>";
            $tblData .= "<th>No</th>";
            $tblData .= "<th>Pekerjaan</th>";
            $tblData .= "<th>Lokasi</th>";
            //    $tblData .= "<th>room</th>";
            //    $tblData .= "<th>item</th>";
            //    $tblData .= "<th>jml</th>";
            //    $tblData .= "<th>satuan</th>";
            //    $tblData .= "<th>Harga</th>";
            $tblData .= "<th>Nilai Project</th>";
            $tblData .= "</tr>";
            $tblData .= "</thead>";
            $tblData .= "<tbody>";
            $i = 0;
            $grandTotal = 0;


            foreach ($womasterData as $idWo => $woLabel) {
                $i++;
                $nama = $woLabel->nama;
                $jumlah_room = $woLabel->qty;
                $lokasi = strlen($woLabel->lokasi) > 5 ? $woLabel->lokasi : "-";
                $rowspan = count($detil);
                $rowspan = 0;
                $tblData .= "<tr>";
                $tblData .= "<td srowspan='$rowspan'>$i</td>";
                $tblData .= "<td srowspan='$rowspan'>$nama</td>";
                //        $tblData .= "<td rowspan='$rowspan' class='text-right'>$jumlah_room</td>";
                //        $tblData .= "<td rowspan='$rowspan' class='text-right'></td>";
                //        $tblData .= "<td rowspan='$rowspan' class='text-right'></td>";
                //        $tblData .= "<td rowspan='$rowspan' class='text-right'></td>";
                //        $tblData .= "<td rowspan='$rowspan' class='text-right'></td>";
                //        $tblData .= "<td rowspan='$rowspan' class='text-right'></td>";
                //        $tblData .= "</tr>";

                $nama_produk = "";
                //        foreach ($detil[$idWo]['produk'] as $k => $nm) {
                //            $nama_produk .= $nm . "<br>";
                //        }

                $jml_produk = "";
                //        foreach ($detil[$idWo]['pjml'] as $k => $nm) {
                //            $jml_produk .= "<div class='text-bold text-center text-primary'>" . $nm * $jumlah_room . "</div>";
                //        }

                //        $rowspans = count($detil[$idWo]['produk']);
                $rowspans = 0;
                //        $tblData .= "<tr>";
                //        $tblData .= "<td srowspan='$rowspans'>" . $nama_produk . "</td>";
                $tblData .= "<td srowspan='$rowspans'>" . $lokasi . "</td>";
                //        $tblData .= "<td srowspan='$rowspans' class='text-right'>" . $jml_produk . "</td>";
                //        $tblData .= "<td srowspan='$rowspans'>unit</td>";
                //        $tblData .= "<td srowspan='$rowspans' class='text-right'>" . number_format($detil[$idWo]["harga_jual"]) . "</td>";
                $tblData .= "<td srowspan='$rowspans' class='text-right'>" . number_format($hrgJualKomp[$idWo]["harga"] * $jumlah_room) . "</td>";
                $tblData .= "</tr>";
                //
                //        $tblData .= "<tr></tr>";


                $grandTotal += $hrgJualKomp[$idWo]["harga"] * $jumlah_room;

            }

            //    arrPrint($tempKomposisiWo[1]['produk']);

            $tblData .= "<tr>";
            $tblData .= "<td colspan='3'>&nbsp;</td>";
            $tblData .= "<td colspan='3'>&nbsp;</td>";
            $tblData .= "</tr>";

            $unitNo = 0;
            foreach ($tempKomposisiWo[1]['produk'] as $k => $unitData) {
                if ($unitData['harga'] * 1 > 500000) {
                    $unitNo++;
                    $unitNama = $unitData['produk_dasar_nama'];
                    $unitTotalHrg = number_format($unitData['jml'] * $unitData['harga']);
                    $tblData .= "<tr>";
                    $tblData .= "<td colspan='3' style=''>$unitNo. $unitNama</td>";
                    $tblData .= "<td colspan='3' class='text-right'>$unitTotalHrg</td>";
                    $tblData .= "</tr>";
                }
            }

            $tblData .= "<tr>";
            $tblData .= "<td colspan='3'>&nbsp;</td>";
            $tblData .= "<td colspan='3'>&nbsp;</td>";
            $tblData .= "</tr>";

            $tblData .= "</tbody>";

            $tblData .= "<tfoot>";
            $tblData .= "<tr>";
            $tblData .= "<th colspan='3' class='text-right'>PPN (11%)</th>";
            $tblData .= "<th colspan='' class='text-right'>" . number_format($grandTotal * 0.11) . "</th>";
            $tblData .= "</tr>";

            $tblData .= "<tr>";
            $tblData .= "<th colspan='3' class='text-right'>Grand Total</th>";
            $tblData .= "<th colspan='' class='text-right'>" . number_format($grandTotal + ($grandTotal * 0.11)) . "</th>";
            $tblData .= "</tr>";
            $tblData .= "</tfoot>";
            //    $tblData .= "</tr>";
            $tblData .= "</table>";
            break;
        case "detail":
            $tblData = "<table class='table table-responsive table-bordered'>";
            $tblData .= "<thead>";
            $tblData .= "<tr >";
            $tblData .= "<th class='text-center'>No</th>";
            foreach ($selectShowFields as $key => $label) {
                $tblData .= "<th class='text-center'>$label</th>";
            }
            $tblData .= "<th>Total harga</th>";
            $tblData .= "</tr>";
            $tblData .= "</thead>";
            $tblData .= "<tbody>";
            $i = 0;
            $grandTotal = 0;
            foreach ($itemKomposisi as $ix => $woLabel) {
                $i++;
                //                $nama = $woLabel->nama;
                //                $jumlah_room = $woLabel->jml;
                //                $lokasi = strlen($woLabel->lokasi) > 5 ? $woLabel->lokasi:"-";
                //                $rowspan = count($detil);
                //                $rowspan = 0;
                //                $tblData .= "<tr>";
                //                $tblData .= "<td srowspan='$rowspan'>$i</td>";
                //                $tblData .= "<td srowspan='$rowspan'>$nama</td>";
                //                $nama_produk = "";
                //                $jml_produk = "";
                //                $rowspans = 0;
                //                $tblData .= "<td srowspan='$rowspans'>" . $lokasi . "</td>";
                //                $tblData .= "<td srowspan='$rowspans' class='text-right'>" . number_format($hrgJualKomp[$idWo]["harga"] * $jumlah_room) . "</td>";
                //                $tblData .= "</tr>";
                //                $tblData .= "<tr>";
                //                $tblData .= "<td srowspan='$rowspan'>$i</td>";
                //                $tblData .= "<td srowspan='$rowspan'>$nama</td>";
                $tblData .= "<td srowspan='$rowspan'>$i</td>";
                foreach ($selectShowFields as $key => $label) {
                    $tblData .= "<td srowspan='$rowspans' class='text-left'>" . formatField($key, $woLabel[$key]) . "</td>";
                }
                $tblData .= "<td srowspan='$rowspan'>" . formatField("subtotal", $woLabel["harga"] * $woLabel["jml"]) . "</td>";
                $tblData .= "</tr>";
                $grandTotal += $woLabel["harga"] * $woLabel["jml"];

            }

            //    arrPrint($tempKomposisiWo[1]['produk']);

            $tblData .= "<tr>";
            $tblData .= "<td colspan='5'>&nbsp;</td>";
            $tblData .= "<td colspan='5'>&nbsp;</td>";
            $tblData .= "</tr>";

            $unitNo = 0;
            foreach ($tempKomposisiWo[1]['produk'] as $k => $unitData) {
                if ($unitData['harga'] * 1 > 500000) {
                    $unitNo++;
                    $unitNama = $unitData['produk_dasar_nama'];
                    $unitTotalHrg = number_format($unitData['jml'] * $unitData['harga']);
                    $tblData .= "<tr>";
                    $tblData .= "<td colspan='3' style=''>$unitNo. $unitNama</td>";
                    $tblData .= "<td colspan='3' class='text-right'>$unitTotalHrg</td>";
                    $tblData .= "</tr>";
                }
            }

            //            $tblData .= "<tr>";
            //            $tblData .= "<td colspan='5'>&nbsp;</td>";
            //            $tblData .= "<td colspan='5'>&nbsp;</td>";
            //            $tblData .= "</tr>";

            $tblData .= "</tbody>";

            $tblData .= "<tfoot>";
            $tblData .= "<tr>";
            $tblData .= "<th colspan='5' class='text-right'>Total</th>";
            $tblData .= "<th colspan='' class='text-right'>" . number_format($grandTotal) . "</th>";
            $tblData .= "</tr>";
            $tblData .= "<tr>";
            $tblData .= "<th colspan='5' class='text-right'>PPN (11%)</th>";
            $tblData .= "<th colspan='' class='text-right'>" . number_format($grandTotal * 0.11) . "</th>";
            $tblData .= "</tr>";

            $tblData .= "<tr>";
            $tblData .= "<th colspan='5' class='text-right'>Grand Total</th>";
            $tblData .= "<th colspan='' class='text-right'>" . number_format($grandTotal + ($grandTotal * 0.11)) . "</th>";
            $tblData .= "</tr>";
            $tblData .= "</tfoot>";
            //    $tblData .= "</tr>";
            $tblData .= "</table>";
            break;
        default:
            matiHere("unknown metode print");
            break;
    }


    //    cekMErah($mode);
    if ($modeShow == "show") {
        echo $tblData;
    }
    else {
        return $tblData;
    }

}

function getRomawi($bln)
{
    switch ($bln) {
        case 1:
            return "I";
            break;
        case 2:
            return "II";
            break;
        case 3:
            return "III";
            break;
        case 4:
            return "IV";
            break;
        case 5:
            return "V";
            break;
        case 6:
            return "VI";
            break;
        case 7:
            return "VII";
            break;
        case 8:
            return "VIII";
            break;
        case 9:
            return "IX";
            break;
        case 10:
            return "X";
            break;
        case 11:
            return "XI";
            break;
        case 12:
            return "XII";
            break;
    }
}


//-------------------------------
function loadCreateTransaksi($jenisTr, $employeeID)
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlAccessRight");
    $m = new MdlAccessRight();
    $m->addFilter("menu_category='$jenisTr'");
    $m->addFilter("employee_id='$employeeID'");
    $mTmp = $m->lookupAll()->result();

    return $mTmp;
}


//-------------------------------
function viewDetailTransaksi($arrDataTransaksi, $arrItemShow, $mode = "1336", $tambahan = array())
{

    // DEBUG VISUAL: tampilkan struktur data yang masuk ke helper (khusus troubleshooting)
    if (isset($_GET['debug_detail']) || isset($_POST['debug_detail'])) {
        echo "<div style='background:#ffe;border:1px solid #fc0;padding:5px;margin:5px 0;'>";
        echo "<b>DEBUG arrDataTransaksi:</b>";
        arrPrintWebs($arrDataTransaksi);
        echo "<b>DEBUG arrItemShow:</b>";
        arrPrintWebs($arrItemShow);
        echo "</div>";
    }

    // fallback logic: cari array item yang valid
    $tblItems = "";
    $dataItems = [];
    if (isset($arrDataTransaksi['items']) && is_array($arrDataTransaksi['items']) && count($arrDataTransaksi['items']) > 0) {
        $dataItems = $arrDataTransaksi['items'];
    } elseif (isset($arrDataTransaksi['items2']) && is_array($arrDataTransaksi['items2']) && count($arrDataTransaksi['items2']) > 0) {
        $dataItems = $arrDataTransaksi['items2'];
    } elseif (isset($arrDataTransaksi['items4']) && is_array($arrDataTransaksi['items4']) && count($arrDataTransaksi['items4']) > 0) {
        $dataItems = $arrDataTransaksi['items4'];
    } elseif (is_array($arrDataTransaksi) && count($arrDataTransaksi) > 0) {
        $dataItems = $arrDataTransaksi;
    }

    // Tampilkan tabel item (default)
    if (!empty($dataItems)) {
        $tblItems .= "<table class='table table-bordered no-padding' style='margin-top:-5px;'>";
        $tblItems .= "<tr>";
        $tblItems .= "<th>No</th>";
        foreach ($arrItemShow as $k => $iLabel) {
            if (is_array($iLabel)) {
                $tblItems .= "<th>" . $iLabel['label'] . "</th>";
            } else {
                $tblItems .= "<th>$iLabel</th>";
            }
        }
        $tblItems .= "</tr>";
        $no = 1;
        foreach ($dataItems as $iSpec) {
            $tblItems .= "<tr>";
            $tblItems .= "<td>" . $no++ . "</td>";
            foreach ($arrItemShow as $k => $iLabel) {
                $val = isset($iSpec[$k]) ? $iSpec[$k] : (isset($iSpec[$iLabel['label']]) ? $iSpec[$iLabel['label']] : (isset($iSpec['nama']) && $k == 'nama' ? $iSpec['nama'] : ''));
                $tblItems .= "<td>" . $val . "</td>";
            }
            $tblItems .= "</tr>";
        }
        $tblItems .= "</table>";
    }

    $tblItems = "";
    //arrPrintKuning($arrDataTransaksi);
    //matiHere(__LINE__);
    //arrPrintPink($arrItemShow);
    switch ($mode) {
        case "1336_":
            $colspan = count($arrItemShow) - 2;
            $tblItems .= "<table class='table table-bordered no-padding' style='margin-top:-5px;'>";
            //                $tblItems .= "<tr>";
            //                $tblItems .= "<th ccolspan='$colspan' style='vertical-align:top;'>Dari</th>";
            ////                $tblItems .= "<th ccolspan='$colspan' style='vertical-align:top;'>Ke/hasil</th>";
            //                $tblItems .= "</tr>";

            $tblItems .= "<tr>";

            //// asal konversi
            $tblItems .= "<td style='vertical-align:top;'>";
            $tblItems .= "<table class='table table-bordered no-padding' style='margin:-5px;'>";
            $tblItems .= "<tr>";
            foreach ($arrItemShow as $k => $iLabel) {
                if (is_array($iLabel)) {
                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                }
                else {
                    $tblItems .= "<th >$iLabel</th>";
                }
            }
            $tblItems .= "<th>hasil</th>";
            $tblItems .= "</tr>";
            $tblItems .= "<tr>";
            foreach ($arrDataTransaksi["items"] as $iSpec) {
                foreach ($arrItemShow as $k => $iLabel) {
                    $class = "";
                    if (is_numeric($iSpec[$k])) {
                        $class = "text-center";
                    }
                    $tblItems .= "<td style='vertical-align:top;'>" . $iSpec[$k] . "</td>";
                }
            }
            //// hasil konversi
            $tblItems .= "<td style='vertical-align:top;'>";
            $tblItems .= "<table class='table table-bordered no-padding' style='margin:-5px;'>";
            $tblItems .= "<tr>";
            foreach ($arrItemShow as $k => $iLabel) {
                if (is_array($iLabel)) {
                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                }
                else {
                    $tblItems .= "<th >$iLabel</th>";
                }
            }
            $tblItems .= "</tr>";
            foreach ($arrDataTransaksi["items"] as $pid => $iSpec) {
                foreach ($arrDataTransaksi["items4"][$pid] as $iSpec) {
                    $tblItems .= "<tr>";
                    foreach ($arrItemShow as $k => $iLabel) {
                        $tblItems .= "<td>" . $iSpec[$k] . "</td>";
                    }
                    $tblItems .= "</tr>";
                }
            }
            $tblItems .= "</table>";
            $tblItems .= "</td>";


            $tblItems .= "</tr>";
            $tblItems .= "</table>";
            $tblItems .= "</td>";


            $tblItems .= "</tr>";
            $tblItems .= "</table >";
            break;
        case "1336":
            $back_asal = "#f4a7fa";
            $back_hasil = "#b1faa7";
            $colspan = count($arrItemShow);
            $tblItems .= "<table class='table table-bordered no-padding' style='margin-top:-5px;'>";
            $tblItems .= "<tr>";
            $tblItems .= "<th ccolspan='$colspan' style='vertical-align:top;'>Dari</th>";
            $tblItems .= "<th ccolspan='$colspan' style='vertical-align:top;'>Ke/hasil</th>";
            $tblItems .= "</tr>";

            $tblItems .= "<tr>";

            //// asal konversi
            $tblItems .= "<td style='vertical-align:top;'>";
            $tblItems .= "<table class='table table-bordered no-padding' style='margin:-5px;background-color:$back_asal;'>";
            $tblItems .= "<tr>";
            $tblItems .= "<th >No.</th>";
            foreach ($arrItemShow as $k => $iLabel) {
                if (is_array($iLabel)) {
                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                }
                else {
                    $tblItems .= "<th >$iLabel</th>";
                }
            }

            $tblItems .= "</tr>";
            $tblItems .= "<tr>";
            $noa = 0;
            foreach ($arrDataTransaksi as $iSpec) {
                $noa++;
                $tblItems .= "<td style='vertical-align:top;'>" . $noa . "</td>";
                foreach ($arrItemShow as $k => $iLabel) {
                    $class = "";
                    if (is_numeric($iSpec[$k])) {
                        $class = "text-center";
                    }
                    $tblItems .= "<td style='vertical-align:top;'>" . $iSpec[$k] . "</td>";
                }
            }

            $tblItems .= "</tr>";
            $tblItems .= "</table>";
            $tblItems .= "</td>";

            //// hasil konversi
            $tblItems .= "<td style='vertical-align:top;'>";
            $tblItems .= "<table class='table table-bordered no-padding' style='margin:-5px;background-color:$back_hasil;'>";
            $tblItems .= "<tr>";
            $tblItems .= "<th >No.</th>";
            foreach ($arrItemShow as $k => $iLabel) {
                if (is_array($iLabel)) {
                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                }
                else {
                    $tblItems .= "<th >$iLabel</th>";
                }
            }
            $tblItems .= "</tr>";
            $noh = 0;
            foreach ($arrDataTransaksi as $pid => $iSpec) {
                foreach ($arrDataTransaksi["items4"][$pid] as $iSpec) {
                    $noh++;
                    $tblItems .= "<tr>";
                    $tblItems .= "<td>" . $noh . "</td>";
                    foreach ($arrItemShow as $k => $iLabel) {
                        $tblItems .= "<td>" . $iSpec[$k] . "</td>";
                    }
                    $tblItems .= "</tr>";
                }
            }
            $tblItems .= "</table>";
            $tblItems .= "</td>";


            $tblItems .= "</tr>";
            $tblItems .= "</table >";
            break;
        case "4464":
        case "4822":
            if (sizeof($arrDataTransaksi["items2"]) > 0) {
                foreach ($arrDataTransaksi["items2"] as $nomer => $specc) {
                    $f_nomer = str_replace('.', '', $nomer);
                    $tblItems .= "<div class='pull-left' style='margin-bottom: 2px;min-width: 150px;'><span width='50px' class='text-left text-bold text-uppercase'>$nomer</span> <i id='tombolMata_$f_nomer' title='klik untuk lihat produk' onclick=\"showTableHistory('$f_nomer')\" class='fa fa-eye text-link text-bold pull-right'></i></div>";
                    $tblItems .= "<table name='tableHistoryShow' id='showTable_$f_nomer' class='table table-bordered dataTable compact hidden' style='margin-top:0px;'>";
                    $tblItems .= "<tr>";
                    $tblItems .= "<th>No</th>";
                    foreach ($arrItemShow as $k => $iLabel) {
                        if (is_array($iLabel)) {
                            $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                        }
                        else {
                            $tblItems .= "<th >$iLabel</th>";
                        }
                    }
                    $tblItems .= "</tr>";
                    $numb = 0;
//                    foreach ($arrDataTransaksi["items"] as $pid => $iSpec) {
                    foreach ($specc as $pid => $iSpec) {
                        $numb++;
                        $tblItems .= "<tr>";
                        $tblItems .= "<td class='text-right'>$numb</td>";
                        foreach ($arrItemShow as $k => $iLabel) {
                            $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
                            $tblItems .= "<td>" . $val . "</td>";
                            if (is_numeric($iSpec[$k])) {
                                if (!isset($summary[$k])) {
                                    $summary[$k] = 0;
                                }
                                $summary[$k] += $iSpec[$k];
                            }
                        }
                        $tblItems .= "</tr>";
                    }

                    $tblItems .= "<tr class='bg-gray'>";
                    $tblItems .= "<td class='text-right'></td>";
                    foreach ($arrItemShow as $k => $iLabel) {
                        $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
                        if ($k == "nama") {
                            $val = "total";
                        }
                        $tblItems .= "<td>" . $val . "</td>";
                    }
                    $tblItems .= "</tr>";
                    $tblItems .= "</table><br>";

                }
            }
            else {
                $f_nomer = str_replace('.', '', '');
                // $tblItems .= "<i id='tombolMata_$f_nomer' title='klik untuk lihat produk' onclick=\"showTableHistory('$f_nomer')\" class='fa fa-eye text-link text-bold pull-right'></i>";

                // id='showTable_$f_nomer'
                $tblItems .= "<table name='tableHistoryShow'  class='table table-bordered $show_css' style='margin-top:0px;'>";
                $tblItems .= "<tr>";
                $tblItems .= "<th>No</th>";
                foreach ($arrItemShow as $k => $iLabel) {
                    if (is_array($iLabel)) {
                        $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                    }
                    else {
                        $tblItems .= "<th >$iLabel</th>";
                    }
                }
                $tblItems .= "</tr>";
                $numb = 0;
                foreach ($arrDataTransaksi["items"] as $pid => $iSpec) {
                    $numb++;
                    $tblItems .= "<tr>";
                    $tblItems .= "<td class='text-right'>$numb</td>";
                    foreach ($arrItemShow as $k => $iLabel) {
                        $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
                        $tblItems .= "<td>" . $val . "</td>";
                        if (is_numeric($iSpec[$k])) {
                            if (!isset($summary[$k])) {
                                $summary[$k] = 0;
                            }
                            $summary[$k] += $iSpec[$k];
                        }
                    }
                    $tblItems .= "</tr>";
                }
                $tblItems .= "<tr>";
                $tblItems .= "<td class='text-right'></td>";
                foreach ($arrItemShow as $k => $iLabel) {
                    $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
                    if ($k == "nama") {
                        $val = "total";
                    }
                    $tblItems .= "<td>" . $val . "</td>";
                }
                $tblItems .= "</tr>";
                $tblItems .= "</table >";
            }
            break;
        /* --------------------------------------
         * ngabil dari item2
         * --------------------------------------*/
        case "5834":
//        case "9833":
        case "9834":

//            echo "##############################";
//            echo json_encode($arrDataTransaksi);
//            echo "==============================<br><br>";

            arrPrintWebs($arrDataTransaksi);

            $items2_sum = array();
            $aliasBiaya = array();
            if (isset($arrDataTransaksi["items2"])) {
                foreach ($arrDataTransaksi["items2"] as $biy => $rItems2) {
                    $aliasBiaya[$biy] = $rItems2['biaya_nama'];
                    foreach ($rItems2 as $bdi => $iRow) {
//                        arrPrintWebs($iRow);
                        $items2_sum[$bdi]['jml'] += $iRow['jml'];
                        $items2_sum[$bdi]['produk_kode'] = "-";
                        $items2_sum[$bdi]['barcode'] = "-";
                        $items2_sum[$bdi]['nama'] = $iRow['biaya_dasar_nama'];
                    }
                }
            }

            $tblItems .= "<table class='table table-bordered' style='margin-top:0px;'>";
            $tblItems .= "<tr>";
            $tblItems .= "<th>No</th>";
            foreach ($arrItemShow as $k => $iLabel) {
                if (is_array($iLabel)) {
                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                }
                else {
                    $tblItems .= "<th >$iLabel</th>";
                }
            }
            $tblItems .= "</tr>";

            $numb = 0;
            foreach ($items2_sum as $pid => $iSpec) {
                $numb++;
                $tblItems .= "<tr>";
                $tblItems .= "<td class='text-right'>$numb</td>";
                foreach ($arrItemShow as $k => $iLabel) {
                    $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
                    $tblItems .= "<td>" . $val . "</td>";
                    if (is_numeric($iSpec[$k])) {
                        if (!isset($summary[$k])) {
                            $summary[$k] = 0;
                        }
                        $summary[$k] += $iSpec[$k];
                    }
                }
                $tblItems .= "</tr>";
            }

            $tblItems .= "<tr>";
            $tblItems .= "<td class='text-right'></td>";
            foreach ($arrItemShow as $k => $iLabel) {
                $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
                if ($k == "nama") {
                    $val = "total";
                }
                $tblItems .= "<td>" . $val . "</td>";
            }
            $tblItems .= "</tr>";
            $tblItems .= "</table >";
            break;
        case "463":
            $tblItems .= "<table name='tableHistoryShow' class='table table-bordered $show_css' style='margin-top:0px;'>";
            $tblItems .= "<tr>";
            $tblItems .= "<th>No</th>";
            foreach ($arrItemShow as $k => $iLabel) {
                if (is_array($iLabel)) {
                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                }
                else {
                    $tblItems .= "<th >$iLabel</th>";
                }
            }
            $tblItems .= "</tr>";
            $numb = 0;
            foreach ($arrDataTransaksi["items"] as $pid => $iSpec) {
                $numb++;
                $tblItems .= "<tr>";
                $tblItems .= "<td class='text-right'>$numb</td>";
                foreach ($arrItemShow as $k => $iLabel) {
                    $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
                    $addKey = "";
//                    if (is_array($iLabel)) {
//                        $addKey = isset($iSpec[$iLabel["addKey"]]) ? "<br>".formatField_he_format($k, $iSpec[$iLabel["addKey"]]) : "";
//                    }
                    $tblItems .= "<td>" . $val . "$addKey</td>";
                    if (is_numeric($iSpec[$k])) {
                        if (!isset($summary[$k])) {
                            $summary[$k] = 0;
                        }
                        $summary[$k] += $iSpec[$k];
                    }
                }
                $tblItems .= "</tr>";
            }
            $tblItems .= "<tr>";
            $tblItems .= "<td class='text-right'></td>";
            foreach ($arrItemShow as $k => $iLabel) {
                $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
//                if ($k == "nama") {
//                    $val = "total";
//                }
                switch ($k) {
                    case "nama":
                        $val = "total";
                        break;
                    case "nett":
                    case "subtotal":

                        break;
                    default:
                        $val = "";
                        break;
                }
                $tblItems .= "<td>" . $val . "</td>";
            }
            $tblItems .= "</tr>";
            $tblItems .= "</table >";
            break;
        /* --------------------------------------
         * ngabil dari item
         * --------------------------------------*/
        case "5833":
        case "3333":
            $tblItems .= "<table name='tableHistoryShow' class='table table-bordered $show_css' style='margin-top:0px;'>";
            $tblItems .= "<tr>";
            $tblItems .= "<th>No</th>";
            foreach ($arrItemShow as $k => $iLabel) {
                if (is_array($iLabel)) {
                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                }
                else {
                    $tblItems .= "<th >$iLabel</th>";
                }
            }
            $tblItems .= "</tr>";
            $numb = 0;
            foreach ($arrDataTransaksi["items"] as $pid => $iSpec) {
//                arrPrintPink($iSpec);
                $numb++;
                $tblItems .= "<tr>";
                $tblItems .= "<td class='text-right'>$numb</td>";
                foreach ($arrItemShow as $k => $iLabel) {
                    $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
                    $addKey = "";
//                    if (is_array($iLabel)) {
//                        $addKey = isset($iSpec[$iLabel["addKey"]]) ? "<br>".formatField_he_format($k, $iSpec[$iLabel["addKey"]]) : "";
//                    }
//

                    if (($k == "diskon_nama") && ($iSpec["diskon_id"] == 7)) {// pree produk, tampilkan barang bonusnya
                        $val = isset($iSpec["extern_nama"]) ? formatField_he_format($k, $iSpec["extern_nama"]) : "-";
                    }

                    $tblItems .= "<td>" . $val . "$addKey</td>";
                    if (is_numeric($iSpec[$k])) {
                        if (!isset($summary[$k])) {
                            $summary[$k] = 0;
                        }
                        $summary[$k] += $iSpec[$k];
                    }
                }
                $tblItems .= "</tr>";
            }
            $tblItems .= "<tr>";
            $tblItems .= "<td class='text-right'></td>";
            foreach ($arrItemShow as $k => $iLabel) {
                $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
                if ($k == "nama") {
                    $val = "total";
                }
                $tblItems .= "<td>" . $val . "</td>";
            }
            $tblItems .= "</tr>";
            $tblItems .= "</table >";
            break;
        default:
            // Ambil data items, fallback jika kosong
            $dataItems = [];
            if (isset($arrDataTransaksi["items"]) && is_array($arrDataTransaksi["items"]) && count($arrDataTransaksi["items"]) > 0) {
                $dataItems = $arrDataTransaksi["items"];
            } elseif (isset($arrDataTransaksi["items2"]) && is_array($arrDataTransaksi["items2"]) && count($arrDataTransaksi["items2"]) > 0) {
                $dataItems = $arrDataTransaksi["items2"];
            } elseif (isset($arrDataTransaksi["items4"]) && is_array($arrDataTransaksi["items4"]) && count($arrDataTransaksi["items4"]) > 0) {
                $dataItems = $arrDataTransaksi["items4"];
            } elseif (is_array($arrDataTransaksi) && array_keys($arrDataTransaksi) === range(0, count($arrDataTransaksi) - 1)) {
                $dataItems = $arrDataTransaksi;
            }
            $tblItems .= "<table name='tableHistoryShow' class='table table-bordered' style='margin-top:0px;'>";
            $tblItems .= "<tr>";
            $tblItems .= "<th>No</th>";
            foreach ($arrItemShow as $k => $iLabel) {
                $label = is_array($iLabel) && isset($iLabel['label']) ? $iLabel['label'] : (is_array($iLabel) ? json_encode($iLabel) : $iLabel);
                $tblItems .= "<th>" . $label . "</th>";
            }
            $tblItems .= "</tr>";
            $numb = 0;
            $summary = array();
            foreach ($dataItems as $pid => $iSpec) {
                if (sizeof($tambahan) > 0) {
                    foreach ($tambahan as $tSpec) {
                        if ($tSpec->produk_id == $pid) {
                            foreach ($tSpec as $tkey => $tval) {
                                if (!array_key_exists($tkey, $iSpec)) {
                                    $iSpec[$tkey] = $tval;
                                }
                            }
                        }
                    }
                }
                $numb++;
                $tblItems .= "<tr>";
                $tblItems .= "<td class='text-right'>$numb</td>";
                foreach ($arrItemShow as $k => $iLabel) {
                    // Flexible value mapping mirip penjualan
                    $val = isset($iSpec[$k]) ? $iSpec[$k] : (is_array($iLabel) && isset($iLabel['label']) && isset($iSpec[$iLabel['label']]) ? $iSpec[$iLabel['label']] : (isset($iSpec['nama']) && $k == 'nama' ? $iSpec['nama'] : '-'));
                    $addKey = "";
                    if (is_array($iLabel) && isset($iLabel['addKey']) && isset($iSpec[$iLabel['addKey']])) {
                        $addKey = '<div class="text-muted" style="font-size:11px">' . formatField_he_format($iLabel['addKey'], $iSpec[$iLabel['addKey']]) . '</div>';
                    }
                    $tblItems .= "<td>" . formatField_he_format($k, $val) . $addKey . "</td>";
                    if (is_numeric($val)) {
                        if (!isset($summary[$k])) {
                            $summary[$k] = 0;
                        }
                        $summary[$k] += $val;
                    }
                }
                $tblItems .= "</tr>";
            }
            $tblItems .= "<tr>";
            $tblItems .= "<td class='text-right'></td>";
            foreach ($arrItemShow as $k => $iLabel) {
                $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
                if ($k == "nama") {
                    $val = "total";
                }
                $tblItems .= "<td>" . $val . "</td>";
            }
            $tblItems .= "</tr>";
            $tblItems .= "</table >";
            break;
    }

//        echo $tblItems;
    return $tblItems;

}

function selectKurir($selected_kurir = 0, $linkSave)
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlEmployeeKirim");

    $k = New MdlEmployeeKirim();
    $kTmp = $k->lookupAll()->result();

    $str = "<select id='idselect' class='btn btn-warning' onchange=\"$('#result').load('$linkSave?kid='+this.value)\">";
    $str .= "<option value='0'> pilih kurir/driver </option>";
    if (sizeof($kTmp) > 0) {
        foreach ($kTmp as $kSpec) {
            $kurir_id = $kSpec->id;
            $kurir_nama = $kSpec->nama;
            $selected = ($kurir_id == $selected_kurir) ? "selected" : "";
            $str .= "<option value='$kurir_id,$kurir_nama' data-nama='$kurir_nama' $selected> $kurir_nama </option>";
        }
    }
    $str .= "</select>";
    return $str;
}

function selectWorker($selected_id = 0, $linkSave)
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlEmployeeWorker");

    $k = New MdlEmployeeWorker();
    $kTmp = $k->lookupAll()->result();

    $str = "<select id='idselect' class='btn btn-info text-uppercase' onchange=\"$('#result').load('$linkSave?kid='+this.value)\">";
    $str .= "<option value='0'> pilih teknisi </option>";
    if (sizeof($kTmp) > 0) {
        foreach ($kTmp as $kSpec) {
            $kurir_id = $kSpec->id;
            $kurir_nama = $kSpec->nama;
            $selected = ($kurir_id == $selected_id) ? "selected" : "";
            $str .= "<option value='$kurir_id,$kurir_nama' data-nama='$kurir_nama' $selected> $kurir_nama </option>";
        }
    }
    $str .= "</select>";
    return $str;
}

function viewFreeProduk($arrDataTransaksi, $arrItemShow, $mode = null)
{
    $tblItems = "";
    switch ($mode) {
        default:
            if (isset($arrDataTransaksi["items5_sum"]) && sizeof($arrDataTransaksi["items5_sum"]) > 0) {

                $tblItems .= "<table class='table table-bordered' style='margin-top:0px;'>";
                $tblItems .= "<tr>";
                $tblItems .= "<th>No</th>";
                foreach ($arrItemShow as $k => $iLabel) {
                    if (is_array($iLabel)) {
                        $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                    }
                    else {
                        $tblItems .= "<th >$iLabel</th>";
                    }
                }
                $tblItems .= "</tr>";
                $numb = 0;
                foreach ($arrDataTransaksi["items5_sum"] as $pid => $iSpec) {
                    $numb++;
                    $tblItems .= "<tr>";
                    $tblItems .= "<td class='text-right'>$numb</td>";
                    foreach ($arrItemShow as $k => $iLabel) {
                        $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
                        $tblItems .= "<td>" . $val . "</td>";
                        if (is_numeric($iSpec[$k])) {
                            if (!isset($summary[$k])) {
                                $summary[$k] = 0;
                            }
                            $summary[$k] += $iSpec[$k];
                        }
                    }
                    $tblItems .= "</tr>";
                }
                $tblItems .= "<tr>";
                $tblItems .= "<td class='text-right'></td>";
                foreach ($arrItemShow as $k => $iLabel) {
                    $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
                    switch ($k) {
                        case "produk_rel_nama":
                            $val = "total";
                            break;
                        case "qty":
                        case "subtotal":
                            $val = $val;
                            break;
                        default:
                            $val = "-";
                            break;
                    }
                    $tblItems .= "<td>" . $val . "</td>";
                }
                $tblItems .= "</tr>";
                $tblItems .= "</table >";
            }
            else {
                $tblItems .= "-";
            }
            break;
    }

    //        echo $tblItems;
    return $tblItems;

}

//-------------------------------
function reloadDataProduk($arrDataTambahan, $arrDataTambahanValidate, $items, $items2)
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlProduk2");
    $m = new MdlProduk2();
    $arrTidakLengkap = array();

    $arrItemsKey = array_keys($items);
    $m->addFilter("id in ('" . implode("','", $arrItemsKey) . "')");
    $tmpB = $m->lookupAll()->result();
    showLast_query("ungu");
    cekHitam(sizeof($tmpB));
    if (sizeof($tmpB) > 0) {
        foreach ($tmpB as $row) {
            $rows = $row;
            $tmp = (array)$row;
            $produk_id = $idp = $row->id;
            if (!isset($items2[$idp])) {
                $items2[$idp] = array();
            }
            //handle serial 1
            $jml_serial = $rows->jml_serial;
            $items[$produk_id]['jml_serial'] = $jml_serial;
            if (($jml_serial * 1) == 1) {
                $d_kode = $rows->kode;
                $items2[$produk_id][$d_kode] = array();
                $items[$produk_id][$d_kode] = $items[$produk_id]['jml'];
                //-----
                foreach ($arrDataTambahanValidate["non_unit"] as $key_cek) {
                    if ($rows->$key_cek == NULL) {
                        $arrTidakLengkap[$rows_id] = $rows->nama;
                    }
                }
            }
            elseif (($jml_serial) > 1) {
                foreach ($arrDataTambahanValidate["unit"] as $key_cek) {
                    if ($rows->$key_cek == NULL) {
                        $arrTidakLengkap[$rows->id] = $rows->nama;
                    }
                }
            }

            //
            //arrPrintWebs($arrDataTambahan);
            //arrPrintKuning($arrDataTambahanValidate);
            //arrPrintHijau($arrTidakLengkap);

            $arrCat = array();
            $arrCode = array();
            foreach ($arrDataTambahan as $cat => $catSpec) {
                foreach ($catSpec as $dkey => $dval) {
                    if (isset($rows->$dval) && ($rows->$dval != NULL)) {
                        $items2[$produk_id][$rows->$dval] = array();
                        if (!isset($arrCat[$cat])) {
                            $arrCat[$cat] = 0;
                        }
                        $arrCat[$cat] += 1;
                        if (!isset($arrCode[$rows->$dval])) {
                            $arrCode[$rows->$dval] = 0;
                        }
                        $arrCode[$rows->$dval] += 1;
                    }
                    //------------

                }
            }
            $keterangan = "";
            $static_keterangan = "";
            if (!empty($arrCat)) {
                foreach ($arrCat as $kcat => $vcat) {
                    $new_vcat = $vcat * $items[$idp]["jml"];
                    if ($keterangan == "") {
                        $keterangan = " $new_vcat $kcat";
                    }
                    else {
                        $keterangan .= "<br> $new_vcat $kcat";
                    }
                    if ($static_keterangan == "") {
                        $static_keterangan = " $vcat $kcat";
                    }
                    else {
                        $static_keterangan .= "<br> $vcat $kcat";
                    }
                    $new_keyy = "qty_" . $kcat;
                    $items[$idp][$new_keyy] = $vcat;
                }
            }
            if (!empty($arrCode)) {
                foreach ($arrCode as $kcat => $vcat) {
                    $new_vcat = $vcat * $items[$idp]["jml"];
                    $items[$idp][$kcat] = $new_vcat;
                }
            }
            $items[$idp]['keterangan'] = $keterangan;
            $items[$idp]['static_keterangan'] = $static_keterangan;


        }

        $result = array(
            "status" => (sizeof($arrTidakLengkap) > 0) ? 0 : 1,// 0 (tidak lengkap), 1 (lengkap)
            "items" => $items,
            "items2" => $items2,
            //-----
            "produk" => $arrTidakLengkap,
        );

        return $result;
    }
}

function reloadDataAset($items, $arrDataTambahan)
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlAsetDetail");
    $m = new MdlAsetDetail();
    $arrItemsKey = array_keys($items);
    $m->addFilter("id in ('" . implode("','", $arrItemsKey) . "')");
    $tmpB = $m->lookupAll()->result();
    $items = array();
    if (count($tmpB) > 0) {
        foreach ($tmpB as $tmpB_0) {
            foreach ($arrDataTambahan as $k_target => $key_src) {
                $items[$tmpB_0->id][$k_target] = $tmpB_0->$key_src;
            }
        }
    }
    $result = array(
        "items" => $items,
    );
    return $result;

}

//-------------------------------
function pisahBarangJasa($sesiData)
{
//    arrPrintKuning($sesiData["items"]);
//    arrprint($sesiData);
//matiHere();
    $itemsRegulerBarang = array();
    $itemsRegulerJasa = array();
    $itemsPaketBarang = array();
    $itemsPaketJasa = array();

    if (sizeof($sesiData["items"]) > 0) {
        foreach ($sesiData["items"] as $pid => $iSpec) {
            $jenis = $iSpec["jenis"];
            $kategori_id = $iSpec["kategori_id"];
            switch ($jenis) {
                case "item_komposit":
                    break;
                case "item":
                    if ($kategori_id == 4) {// jasa
                        $itemsRegulerJasa["items4"][$pid] = $iSpec;
                    }
                    else {// barang
                        $itemsRegulerBarang["items9_sum"][$pid] = $iSpec;
                    }

                    break;
            }

        }
    }

    if (is_array($sesiData["items6"]) && (sizeof($sesiData["items6"]) > 0)) {
        foreach ($sesiData["items6"] as $paket_id => $iSpec) {
            foreach ($iSpec as $pid => $iiSpec) {
                $kategori_id = $iiSpec["kategori_id"];
                if ($kategori_id == 4) {// jasa
                    $itemsPaketJasa["items4_sum"][$paket_id][$pid] = $iiSpec;
                }
                else {// barang
                    $itemsPaketBarang["items10_sum"][$paket_id][$pid] = $iiSpec;
                }
            }
        }
    }


//    arrPrintHijau($itemsRegulerBarang);
//    arrPrintWebs($itemsRegulerJasa);
//
//    arrPrintKuning($itemsPaketBarang);
//    arrPrintPink($itemsPaketJasa);

    $result = array(
        "items4" => sizeof($itemsRegulerJasa["items4"]) > 0 ? $itemsRegulerJasa["items4"] : array(),// jasa reguler
        "items9_sum" => sizeof($itemsRegulerBarang["items9_sum"]) > 0 ? $itemsRegulerBarang["items9_sum"] : array(),// barang reguler
        "items4_sum" => sizeof($itemsPaketJasa["items4_sum"]) > 0 ? $itemsPaketJasa["items4_sum"] : array(),// jasa dari paket
        "items10_sum" => sizeof($itemsPaketBarang["items10_sum"]) > 0 ? $itemsPaketBarang["items10_sum"] : array(),// barang dari paket
    );
    return $result;
}

function pisahProdukSupplies($sesiData)
{

    $itemsSupplies = array();
    $itemsProduk = array();
    $arrKey = array(
        "supplies" => array(
            "harga" => "harga_supplies",
            "hpp" => "hpp_supplies",
            "hpp_nppn" => "hpp_nppn_supplies",
            "hpp_nppv" => "hpp_nppv_supplies",
            "hpp_nppn_nppv" => "hpp_nppn_nppv_supplies",
            "nett" => "nett_supplies",
            "ppn" => "ppn_supplies",
        ),
        "item" => array(
            "harga" => "harga_produk",
            "hpp" => "hpp_produk",
            "hpp_nppn" => "hpp_nppn_produk",
            "hpp_nppv" => "hpp_nppv_produk",
            "hpp_nppn_nppv" => "hpp_nppn_nppv_produk",
            "nett" => "nett_produk",
            "ppn" => "ppn_produk",
        ),
    );

    if (sizeof($sesiData["items"]) > 0) {
        foreach ($sesiData["items"] as $pid => $iSpec) {
            $jenis_barang = isset($iSpec["jenis_barang"]) ? $iSpec["jenis_barang"] : "none";
            switch ($jenis_barang) {
                case "supplies":
                    foreach ($arrKey[$jenis_barang] as $kk => $vv) {
                        $iSpec[$vv] = isset($iSpec[$kk]) ? $iSpec[$kk] : 0;
                        $sub_kk = "sub_" . $kk;
                        unset($iSpec[$kk]);
                        unset($iSpec[$sub_kk]);
                    }
                    $itemsSupplies["items9_sum"][$pid] = $iSpec;
                    break;

                default:
                case "item":
                    foreach ($arrKey[$jenis_barang] as $kk => $vv) {
                        $iSpec[$vv] = isset($iSpec[$kk]) ? $iSpec[$kk] : 0;
                        $sub_kk = "sub_" . $kk;
                        unset($iSpec[$kk]);
                        unset($iSpec[$sub_kk]);
                    }
                    $itemsProduk["items10_sum"][$pid] = $iSpec;
                    break;
//                default:
//                    $msg = "jenis barang tidak diketahui. silahkan cek data barang anda.";
//                    mati_disini($msg);
//                    break;
            }

        }
    }
//arrPrintKuning($itemsProduk["items10_sum"]);
//mati_disini(__LINE__);

    $result = array(
        "items9_sum" => sizeof($itemsSupplies["items9_sum"]) > 0 ? $itemsSupplies["items9_sum"] : array(),// supplies
        "items10_sum" => sizeof($itemsProduk["items10_sum"]) > 0 ? $itemsProduk["items10_sum"] : array(),// produk
    );
    return $result;
}

function isTransaksiIdExist_misc($transaksi_id)
{
    $ci = &get_instance();
    $ci->load->model("MdlTransaksi");
    $tr = new MdlTransaksi();

    $ci->db->select("id");
    $condites = array(
        "id" => $transaksi_id
    );
    $tr->setFilters(array());
    $srcs = $tr->lookupByCondition($condites)->num_rows();

    return $srcs;
}

function uangMukaLabel()
{
    $arrData = array(
        "uang_muka_produk" => "uang muka produk",
        "uang_muka_jasa" => "uang muka jasa",
        "include_ppn" => "termasuk ppn",
        "exclude_ppn" => "belum termasuk ppn",
    );


    return $arrData;
}

//-------------------------------
function bgTransaksiColor()
{
    $bgColor = array(
        "orange" => "Transaksi Close/Fullfillment.",
        "yellow" => "Transaksi pernah diedit/dirubah.",
        "red" => "Transaksi dibatalkan/reject.",
    );

    return $bgColor;

}

//generate voucher
function getVoucherNumber($setType = 'tasklist', $trAlias = 'VC', $dt, $step_code, $modul = "pembelian")
{
    $ci = &get_instance();
    $arr = array();
    //cekMerah($setType);
    //    matiHEre($modul);
    //region penomoran receipt
    $ci->load->model("CustomCounter");
    $cn = new CustomCounter("transaksi");
    $cn->setType($setType);
    $cn->setModul($modul);
    $cn->setStepCode($step_code);

    $numBatch = array(
        "$trAlias" => array(
            "counters" => array(
                "stepCode",
                //                "stepCode|oleh_id",
                //                "stepCode|produk_id",
                //                "stepCode|fase_id",
                //                "stepCode|employee_id",
                //                "stepCode|month",
                //                "stepCode|years",
                //                "stepCode|month",
                //                "stepCode|day",
            ),
            //          "formatNota" => "stepCode|employee_id,.stepCode,.employee_id,stepCode|employee_id,.month,.years",
            "formatNota" => "stepCode,.month,.years,.",
        )
    );

    if (isset($dt['dtime'])) {
        $month_select = date("m", strtotime($dt['dtime']));
        $arr['day'] = date("d", strtotime($dt['dtime']));
        $arr['month'] = getRomawi($month_select);
        $arr['years'] = date("Y", strtotime($dt['dtime']));
    }

    $arr['stepCode'] = $trAlias;
    //    $arr['oleh_id']     = $dt['oleh_id'];
    //    $arr['produk_id']   = $dt['produk_id'];
    //    $arr['fase_id']     = $dt['fase_id'];
    //    $arr['employee_id'] = $dt['employee_id'];

    $counterForNumber = array($numBatch[$trAlias]['formatNota']);
    $configCustomParams = $numBatch[$trAlias]['counters'];

    if (sizeof($configCustomParams) > 0) {
        $cContent = array();
        foreach ($configCustomParams as $i => $cRawParams) {
            $cParams = explode("|", $cRawParams);
            $cValues = array();
            foreach ($cParams as $param) {
                $cValues[$i][$param] = $arr[$param];
            }
            $cRawValues = implode("|", $cValues[$i]);
            $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);
            $cContent[$cRawParams][$cRawValues] = $paramSpec['value'];
            arrprintwebs($paramSpec);
            switch ($paramSpec['id']) {
                case 0: //===counter type is new
                    //                    $addData = array(
                    //                        "toko_id" => $dt['toko_id'],
                    //                        "toko_nama" => $dt['toko_nama'],
                    //                    );
                    $paramKeyRaw = print_r($cParams, true);
                    $paramValuesRaw = print_r($cValues[$i], true);
                    $cn->writeNewCount($cParams, $cValues[$i], $paramKeyRaw, $paramValuesRaw);
                    cekLime($ci->db->last_query());
                    break;
                default: //===counter to be updated
                    $cn->updateCount($paramSpec['id'], $paramSpec['value']);
                    break;
            }
        }
    }
    $appliedCounters = base64_encode(serialize($cContent));
    $appliedCounters_inText = print_r($cContent, true);

    //cekMErah($paramSpec["paramString"]);
    $paramNum = (explode(".", $paramSpec["paramString"]));
    //matiHEre();

    $tmpNomorNota = array(
        "paramEncodeVoucher" => md5($paramNum[0] . "" . date("Ymd") . "." . $paramSpec["paramString"] . "" . $paramNum[1]),
        "paramVoucher" => $paramNum[0] . "" . date("Ymd") . "" . "" . $paramNum[1],
    );

    //arrPrint($tmpNomorNota);
    //cekHitam($tmpNomorNota);
    //matiHere();
    return $tmpNomorNota;
}

function getSerialGenerate($transaksi_id = NULL)
{
    if ($transaksi_id == NULL) {
        $msg = "Validasi nomer serial membutuhkan id transaksi saat ini. Silahkan cek lagi. code: " . __LINE__;
        mati_disini($msg);
    }
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlProdukPerSerialNumber");
    $psn = New MdlProdukPerSerialNumber();
    $psn->addFilter("transaksi_id='$transaksi_id'");
    $psnTmp = $psn->lookupAll()->result();
    $hasil = array();
    if (sizeof($psnTmp) > 0) {
        foreach ($psnTmp as $psnSpec) {
            $pid = $psnSpec->produk_id;
            $pserial = $psnSpec->produk_serial_number_2;
            $pskupart = $psnSpec->produk_sku_part_nama;
            $hasil[$pid][$pskupart][$pserial] = $pserial;
        }
    }
    return $hasil;
}

function getTransaksiImageReference($transaksi_id)
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlTransaksiImageReference");
    $ti = new MdlTransaksiImageReference();
    if (is_array($transaksi_id)) {
        $ti->addFilter("id_master in ('" . implode("','", $transaksi_id) . "')");
    }
    else {
        $ti->addFilter("id_master='$transaksi_id'");
    }

    $tmpImages = $ti->lookUpAll()->result();
    $relImages = array();
    if (count($tmpImages) > 0) {
        foreach ($tmpImages as $tmpImages_0) {
            $relImages[$tmpImages_0->id_master] = array(
                "nama" => $tmpImages_0->nama,
                "link_img" => $tmpImages_0->cdn_link,
                "dtime" => $tmpImages_0->dtime,
            );
        }
    }
    return $relImages;

}


function validateRelasiUangMuka($transaksi_id)
{
    $ci = &get_instance();
    $ci->load->model("MdlTransaksi");

    // region cek titipan relasi po
    $tr = new MdlTransaksi();
    $tr->setFilters(array());
    $tr->addFilter("extern2_id='$transaksi_id'");
    $tr->addFilter("sisa>'0'");
    $trTmp = $tr->lookupAllUangMukaSrc()->result();
//    cekMerah($ci->db->last_query());
    if (sizeof($trTmp) > 0) {
        $result = (array)$trTmp[0];

        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("id=" . $result["transaksi_id"]);
        $trTmpUm = $tr->lookupAll()->result();
        $result["nomer_um"] = $trTmpUm[0]->nomer;
//        $result["nomer_po"] = $trTmpUm[0]->extern2_nama;
        $result["oleh_nama_um"] = $trTmpUm[0]->oleh_nama;
        $result["jenis"] = "titipan";


    }
    else {
        $result = array();
    }
    // endregion cek uang muka relasi po ada ppnnya

    // region cek uang muka relasi po
    $tr = new MdlTransaksi();
    $tr->setFilters(array());
    $tr->addFilter("extern2_id='$transaksi_id'");
    $tr->addFilter("sisa>'0'");
    $tr->addFilter("label='uang muka supplier'");
    $trTmp = $tr->lookUpAllPaymentSrc()->result();
//    cekHitam($ci->db->last_query());
    if (sizeof($trTmp) > 0) {
        $result = (array)$trTmp[0];

        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("id=" . $result["transaksi_id"]);
        $trTmpUm = $tr->lookupAll()->result();
        $result["nomer_um"] = $trTmpUm[0]->nomer;
        $result["oleh_nama_um"] = $trTmpUm[0]->oleh_nama;
        $result["jenis"] = "uangmuka";
    }
    // endregion cek uang muka relasi po

//arrPrint($result);
//    matiHere();
    return $result;

}

function validateRelasiPaymentSrc($masterID, $jenis, $traget_jenis, $label)
{
    $ci = &get_instance();
    $ci->load->model("MdlTransaksi");

    // region cek uang muka relasi po
    $tr = new MdlTransaksi();
    $tr->setFilters(array());
    $tr->addFilter("transaksi.link_id='0'");
    $tr->addFilter("transaksi.id_master='$masterID'");
    $tr->addFilter("transaksi.jenis='$jenis'");
    $tr->addFilter("transaksi_payment_source.sisa>'1000'");
    $tr->addFilter("transaksi_payment_source.target_jenis='$traget_jenis'");
    $tr->addFilter("transaksi_payment_source.label='$label'");

//    matiHEre(__LINE__);
    $trTmp = $tr->paymentSrcRelasiMaster()->result();
//    cekBiru($ci->db->last_query());
//    arrprint($trTmp);
//    matiHere(__LINE__);

    // endregion cek uang muka relasi po


    return $trTmp;

}

//-----------------------------
function cekOpnameAktif($jenisTr, $configUi, $cabang_id)
{
    $ci = &get_instance();
    $ci->load->library("Transaksional");
    $lt = New Transaksional();
    $ltTmp = $lt->cekOpnameAktive($cabang_id);
//    showLast_query("biru");
//    arrPrintCyan($ltTmp);
    if ($ltTmp["jml"] > 0) {
        $jenisTransaksiLabel = isset($configUi[$jenisTr]['label']) ? $configUi[$jenisTr]['label'] : false;
        $msg = "Transaksi $jenisTransaksiLabel belum bisa digunakan karena masih ada stok opname yang belum selesai.";
        $alerts = array(
            "type" => "warning",
            "html" => "$msg",
        );
        $link = base_url();
        $btn_label = "OKE";
        $btn_cancel = NULL;
        $arrWarningPlaceTrans = array(
            "alert" => array(
                "type" => "warning",
                "html" => "$msg",
            ),
            "link" => $link,
            "btn_label" => $btn_label,
            "btn_cancel" => $btn_cancel,
        );
    }

    if (isset($arrWarningPlaceTrans) && (sizeof($arrWarningPlaceTrans) > 0)) {
        return $arrWarningPlaceTrans;
    }
    else {
        return array();
    }
}

//-----------------------------
function reloadProdukData($sessionData, $configUiJenis, $renew = false)
{
    $ci = &get_instance();

    // region reload data produk sesuai config dari shoppingcart
    $arrItemsKey = array_keys($sessionData["items"]);
    $arrDataTambahan = isset($configUiJenis['produkUnitPart']) ? $configUiJenis['produkUnitPart'] : array();

    $selectorSrcModel = isset($sessionData['main']['pihakMdlNameSrc']) ? $sessionData['main']['pihakMdlNameSrc'] : $configUiJenis['selectorSrcModel'];
    $fieldSrcs = isset($configUiJenis['shoppingCartFieldSrc']) ? $configUiJenis['shoppingCartFieldSrc'] : array("nama" => "nama");

    $ci->load->model("Mdls/" . $selectorSrcModel);
    $b = new $selectorSrcModel();
    $b->addFilter("id in ('" . implode("','", $arrItemsKey) . "')");
    $tmpB = $b->lookupAll()->result();
    if (sizeof($tmpB) > 0) {
        foreach ($tmpB as $row) {
            $rowsArray = (array)$row;
            $rowsArrayTrim = trimArray($rowsArray);
            $row = $rows = (object)$rowsArrayTrim;
            $tmp = (array)$rows;
            $produk_id = $idp = $row->id;
            $produk_nama = $row->nama;
            $produk_jenis = $row->kategori_nama;
            $jenis_barang = $row->jenis;
            $jml_serial = $row->jml_serial;


            if ($renew == true) {
                if (!isset($sessionData['items2'][$idp])) {
                    $sessionData['items2'][$idp] = array();
                }
            }

            foreach ($fieldSrcs as $key => $src) {
                if (!isset($sessionData['items'][$idp][$key])) {
                    if (is_array($src) && sizeof($src) > 0) {
                        foreach ($src as $srcSpec) {
                            if (isset($tmp[$srcSpec]) || isset($rows->$srcSpec)) {
                                $sessionData['items'][$idp][$key] = makeValue($srcSpec, $tmp, $tmp, isset($rows->$srcSpec) ? $rows->$srcSpec : "-");
                            }
                        }
                    }
                    else {
                        $sessionData['items'][$idp][$key] = makeValue($src, $tmp, $tmp, isset($rows->$src) ? $rows->$src : 0);
                    }
                }
            }
            // memasukkan kolom sku ke items2
            $arrCat = array();
            $arrCode = array();
            if ($produk_jenis == "unit") {
                foreach ($arrDataTambahan as $cat => $catSpec) {
                    foreach ($catSpec as $dkey => $dval) {
                        if (isset($rows->$dval) && ($rows->$dval != NULL)) {

                            if ($renew == true) {
                                $sessionData['items2'][$produk_id][$rows->$dval] = array();
                            }


                            //--------------
                            if (!isset($arrCat[$cat])) {
                                $arrCat[$cat] = 0;
                            }
                            $arrCat[$cat] += 1;
                            //--------------
                            if (!isset($arrCode[$rows->$dval])) {
                                $arrCode[$rows->$dval] = 0;
                            }
                            $arrCode[$rows->$dval] += 1;
                            //--------------
                        }
                    }
                }


                if (sizeof($arrCode) < 2) {
                    $produkUnitNoPart[$produk_id] = array(
                        "id" => $produk_id,
                        "nama" => $produk_nama,
                        "jml_serial" => $jml_serial,
                    );
                }
                $produkUnitAda = true;
            }
            else {
                //handle serial 1
                $tmp['jml_serial'] = $jml_serial;
                $tmp['scan_mode'] = $jml_serial > 0 ? "serial" : "simple";
                if (($jml_serial * 1) == 1) {
                    $d_kode = $rows->kode;


                    if ($renew == true) {
                        $sessionData['items2'][$produk_id][$d_kode] = array();
                    }


                    $sessionData['items'][$idp][$d_kode] = $sessionData['items'][$idp]["jml"];

                    //-------
                    $produkNonUnitSerialAda = true;
                    if ($d_kode == NULL) {
                        $produkNonUnitNoPart[$produk_id] = array(
                            "id" => $produk_id,
                            "nama" => $produk_nama,
                            "jml_serial" => $jml_serial,
                        );
                    }
                    //-------

                }
                elseif ($jml_serial == 0) {
                    $d_kode = $rows->kode;
                    $sessionData['items'][$idp][$d_kode] = 0;
                    $sessionData['items'][$idp]["jml_serial"] = 0;
                    unset($sessionData['items2'][$idp]);//reset items2nya karena gak ada serialnya
                }
                else {

                }
            }
            $keterangan = "";
            $static_keterangan = "";
            if (!empty($arrCat)) {
                foreach ($arrCat as $kcat => $vcat) {
                    $new_vcat = $vcat * $sessionData['items'][$idp]["jml"];
                    if ($keterangan == "") {
                        $keterangan = " $new_vcat $kcat";
                    }
                    else {
                        $keterangan .= "<br> $new_vcat $kcat";
                    }
                    if ($static_keterangan == "") {
                        $static_keterangan = " $vcat $kcat";
                    }
                    else {
                        $static_keterangan .= "<br> $vcat $kcat";
                    }
                    $new_keyy = "qty_" . $kcat;
                    $sessionData['items'][$idp][$new_keyy] = $vcat;
                }
            }
            if (!empty($arrCode)) {
                foreach ($arrCode as $kcat => $vcat) {
                    $new_vcat = $vcat * $sessionData['items'][$idp]["jml"];
                    $sessionData['items'][$idp][$kcat] = $new_vcat;
                }
            }
            $sessionData['items'][$idp]['keterangan'] = $keterangan;
            $sessionData['items'][$idp]['static_keterangan'] = $static_keterangan;
            $sessionData['items'][$idp]['jenis_barang'] = $jenis_barang;
        }
    }
    // endregion reload data produk sesuai config dari shoppingcart


    $peringatan = NULL;
    $produk_tidak_lengkap = false;
    if (isset($produkUnitAda) && ($produkUnitAda == true)) {
        if (sizeof($produkUnitNoPart) > 0) {
            $hasil = "";
            foreach ($produkUnitNoPart as $pid => $pSpec) {
                $nama = $pSpec["nama"];
                $jml_serial = $pSpec["jml_serial"];
                if ($hasil == "") {
                    $hasil = "$nama ($jml_serial part)";
                }
                else {
                    $hasil .= ", $nama ($jml_serial part)";
                }
            }
            $peringatan .= "Data Produk $hasil dengan kategori unit tidak lengkap. Pastikan part sudah disetting. ";
            $peringatan .= "Silahkan perbarui data produk anda. ";
            $produk_tidak_lengkap = true;
        }
        else {
            $produk_tidak_lengkap = false;
        }
    }


    if (isset($produkNonUnitSerialAda) && ($produkNonUnitSerialAda == true)) {
        if (sizeof($produkNonUnitNoPart) > 0) {
            $hasil = "";
            foreach ($produkNonUnitNoPart as $pid => $pSpec) {
                $nama = $pSpec["nama"];
                $jml_serial = $pSpec["jml_serial"];
                if ($hasil == "") {
                    $hasil = "$nama";
                }
                else {
                    $hasil .= ", $nama";
                }
            }
            $peringatan .= "<br>Data Kode/SKU Produk $hasil dengan kategori non unit tidak lengkap. Pastikan sudah disetting. ";
            $peringatan .= "Silahkan perbarui data produk anda. ";
            $produk_nonunit_tidak_lengkap = true;
        }
        else {
            $produk_nonunit_tidak_lengkap = false;
        }
    }


    $result = array(
        "items" => $sessionData['items'],
        "produk_unit_ada" => $produkUnitAda,
        "produk_unit_no_part" => $produkUnitNoPart,
        "warning" => isset($peringatan) ? $peringatan : NULL,
        "produk_tidak_lengkap" => $produk_tidak_lengkap,
        "produk_nonunit_tidak_lengkap" => $produk_nonunit_tidak_lengkap,

    );
    return $result;

}


function checkerProdukData($row, $arrDataTambahan)
{
    $rowsArray = (array)$row;
    $rowsArrayTrim = trimArray($rowsArray);
    $row = $rows = (object)$rowsArrayTrim;
    $tmp = (array)$rows;
    $produk_id = $idp = $row->id;
    $produk_nama = $row->nama;
    $produk_kode = $row->kode;
    $produk_jenis = $row->kategori_nama;
    $jenis_barang = $row->jenis;
    $jml_serial = $row->jml_serial;


    $arrCat = array();
    $arrCode = array();
    if ($produk_jenis == "unit") {
        foreach ($arrDataTambahan as $cat => $catSpec) {
            foreach ($catSpec as $dkey => $dval) {
                if (isset($rows->$dval) && ($rows->$dval != NULL)) {
                    //--------------
                    if (!isset($arrCat[$cat])) {
                        $arrCat[$cat] = 0;
                    }
                    $arrCat[$cat] += 1;
                    //--------------
                    if (!isset($arrCode[$rows->$dval])) {
                        $arrCode[$rows->$dval] = 0;
                    }
                    $arrCode[$rows->$dval] += 1;
                    //--------------
                }
            }
        }

        if (sizeof($arrCode) < $jml_serial) {

            $peringatan = "Data Produk ($produk_kode) $produk_nama dengan kategori unit tidak lengkap. Pastikan part sudah disetting. ";
            $peringatan .= "Silahkan perbarui data produk anda. ";
            die(lgShowAlertMerah($peringatan));
        }

    }
    else {
        //handle serial 1
        if (($jml_serial * 1) == 1) {
            $d_kode = $rows->kode;
            if ($d_kode == NULL) {

                $peringatan = "Data Kode/SKU Produk $produk_nama dengan kategori non unit tidak lengkap. Pastikan sudah disetting. ";
                $peringatan .= "Silahkan perbarui data produk anda. ";
                die(lgShowAlertMerah($peringatan));
            }
        }
        elseif ($jml_serial == 0) {

        }
        else {

        }


    }

//    mati_disini(__LINE__);
}


function getFolderModul($jenis)
{
    $CI = &get_instance();
    $arrConf_UI = $CI->config->item("heTransaksi_ui");
    $arrConfModul = array();
    if (!empty($arrConf_UI)) {
        foreach ($arrConf_UI as $jn => $uiData) {
            if (isset($uiData['modul'])) {
                $arrConfModul[$jn] = $uiData['modul'];
            }
        }
    }
    return isset($arrConfModul[$jenis]) ? $arrConfModul[$jenis] : false;
}

function loadConfigUiByModul_he_misc()
{
    $CI = &get_instance();

    $modul_pathSrc = $CI->config->item("heTransaksi_ui");
    if (sizeof($modul_pathSrc) > 0) {
        $modul_path = array();
        foreach ($modul_pathSrc as $jn => $jnData) {
            if (isset($jnData["modul"])) {
                $conf = "../../modules/" . $jnData['modul'] . "/config/";
                $CI->load->config($conf . "coTransaksiUi");

                $modul_path[$jnData["modul"]][$jn] = isset($CI->config->item("coTransaksiUi")[$jn]) ? $CI->config->item("coTransaksiUi")[$jn] : cekMerah("heTransaksi_ui sudah siap modul, sayangnya config modul " . $jnData['modul'] . " untuk jenisTr $jn tidak ditemukan, tolong dilengkapi yak");


            }
        }
        return $modul_path;
    }
    else {
        return array();
    }
}

function loadConfigCoreByModul_he_misc()
{
    $CI = &get_instance();

    $modul_pathSrc = $CI->config->item("heTransaksi_ui");
    if (sizeof($modul_pathSrc) > 0) {
        $modul_path = array();
        foreach ($modul_pathSrc as $jn => $jnData) {
            if (isset($jnData["modul"])) {
                $conf = "../../modules/" . $jnData['modul'] . "/config/";
                $CI->load->config($conf . "coTransaksiCore");
                $modul_path[$jnData["modul"]][$jn] = isset($CI->config->item("coTransaksiCore")[$jn]) ? $CI->config->item("coTransaksiCore")[$jn] : cekMerah("heTransaksi_core sudah siap modul, sayangnya config modul " . $jnData['modul'] . " untuk jenisTr $jn tidak ditemukan, tolong dilengkapi yak");


            }
        }
        return $modul_path;
    }
    else {
        return array();
    }
}

function loadConfigValuesByModul_he_misc()
{
    $CI = &get_instance();

    $modul_pathSrc = $CI->config->item("heTransaksi_ui");
    if (sizeof($modul_pathSrc) > 0) {
        $modul_path = array();
        foreach ($modul_pathSrc as $jn => $jnData) {
            if (isset($jnData["modul"])) {
                $conf = "../../modules/" . $jnData['modul'] . "/config/";
                $CI->load->config($conf . "coTransaksiValues");
                $modul_path[$jnData["modul"]][$jn] = isset($CI->config->item("coTransaksiValues")[$jn]) ? $CI->config->item("coTransaksiValues")[$jn] : cekMerah("coTransaksiValues sudah siap modul, sayangnya config modul " . $jnData['modul'] . " untuk jenisTr $jn tidak ditemukan, tolong dilengkapi yak");


            }
        }
        return $modul_path;
    }
    else {
        return array();
    }
}

function loadConfigLayoutByModul_he_misc()
{
    $CI = &get_instance();

    $modul_pathSrc = $CI->config->item("heTransaksi_ui");
    if (sizeof($modul_pathSrc) > 0) {
        $modul_path = array();
        foreach ($modul_pathSrc as $jn => $jnData) {
            if (isset($jnData["modul"])) {
                $conf = "../../modules/" . $jnData['modul'] . "/config/";
                $CI->load->config($conf . "coTransaksiLayout");
                $modul_path[$jnData["modul"]][$jn] = isset($CI->config->item("coTransaksiLayout")[$jn]) ? $CI->config->item("coTransaksiLayout")[$jn] : cekMerah("coTransaksiLayout sudah siap modul, sayangnya config modul " . $jnData['modul'] . " untuk jenisTr $jn tidak ditemukan, tolong dilengkapi yak");


            }
        }
        return $modul_path;
    }
    else {
        return array();
    }
}

define('MAX_DISKON_PEMBULATAN', '10');//konstanta dibaca sebagai persen
define('MAX_PREMI_PEMBULATAN', '1000');//konstanta dibaca sebagai nominal

function get_full_url()
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
        || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $request_uri = $_SERVER['REQUEST_URI'];
    return $protocol . $host . $request_uri;
}

function pisahGerbangKoreksi($configUiJenis, $sessionData)
{
    //--------
    $additionalDetailByCabang = isset($configUiJenis["additionalDetailByCabang"]) ? $configUiJenis["additionalDetailByCabang"] : array();
//    arrPrint($additionalDetailByCabang);
    if (sizeof($additionalDetailByCabang) > 0) {
        if (isset($additionalDetailByCabang["enabled"]) && ($additionalDetailByCabang["enabled"] == true)) {
//            $gateTarget = $additionalDetailByCabang["gateTarget"];
//            $gateTarget_sum = $additionalDetailByCabang["gateTarget_sum"];
            $gateSource = $additionalDetailByCabang["gateSource"];
            $gateSummary = $additionalDetailByCabang["gateSummary"];
            $gateSummaryCabang = $additionalDetailByCabang["gateSummaryCabang"];
            $copyKey = $additionalDetailByCabang["copyKey"];
            $copyKeyCabang = $additionalDetailByCabang["copyKeyCabang"];
//            arrPrintWebs($gateSummaryCabang);
//            matiHere(__LINE__);
//            $sessionData[$gateTarget] = array();
            $sessionData["items7"] = array();
            $sessionData["items7_sum"] = array();
            $sessionData["items8"] = array();
            $sessionData["items8_sum"] = array();
            switch ($gateSource) {
                case "items":
                    foreach ($sessionData[$gateSource] as $ii => $iiSpec) {
                        foreach ($copyKey as $cKey => $cVal) {
                            $new_ckey = "nilai_" . $iiSpec[$cKey];
                            $sessionData[$gateSource][$ii][$new_ckey] = isset($iiSpec[$cVal]) ? $iiSpec[$cVal] : 0;
//                            $sessionData[$gateSource][$ii]["hutang_pph"] = isset($iiSpec[$cVal]) ? $iiSpec[$cVal] : 0;
                            $iiSpec[$new_ckey] = isset($iiSpec[$cVal]) ? $iiSpec[$cVal] : 0;
                            $iiSpec["hutang_pph"] = isset($iiSpec[$cVal]) ? $iiSpec[$cVal] : 0;

                        }
//                        arrPrintWebs($iiSpec);
                        // dikelompokkan per-cabang
                        $cabang2_id = $iiSpec["cabang2_id"];
                        if ($cabang2_id > 0) {
                            $gateTarget = "items8";
                            // gerbang cabang, items8
                            foreach ($copyKeyCabang[0] as $cKey => $cVal) {
                                $sessionData[$gateSource][$ii][$cKey] = makeValue($cVal, $iiSpec, $iiSpec, 0);
                                $iiSpec[$cKey] = makeValue($cVal, $iiSpec, $iiSpec, 0);
                            }
                        }
                        else {
                            $gateTarget = "items7";
                            // gerbang dc/pusat, items7
                            foreach ($copyKeyCabang[1] as $cKey => $cVal) {
                                $sessionData[$gateSource][$ii][$cKey] = makeValue($cVal, $iiSpec, $iiSpec, 0);
                                $iiSpec[$cKey] = makeValue($cVal, $iiSpec, $iiSpec, 0);
                            }
                        }


                        if (!isset($sessionData[$gateTarget][$cabang2_id])) {
                            $sessionData[$gateTarget][$cabang2_id] = $iiSpec;
                        }
                        else {
                            foreach ($gateSummary as $cKey => $cVal) {

                                $new_ckeys = "nilai_" . $iiSpec[$cKey];

                                if (!isset($sessionData[$gateTarget][$cabang2_id]["hutang_pph"])) {
                                    $sessionData[$gateTarget][$cabang2_id]["hutang_pph"] = 0;
                                }
                                cekHitam($iiSpec[$cVal] . "::" . $cabang2_id . "::" . $gateTarget);
                                cekHitam($iiSpec[$cVal] . "::" . $cabang2_id . "::" . $gateTarget . "::" . $new_ckeys);
                                $sessionData[$gateTarget][$cabang2_id][$new_ckeys] += $iiSpec[$cVal];
                                $sessionData[$gateTarget][$cabang2_id]["hutang_pph"] += $iiSpec[$cVal];
                                cekBiru($new_ckeys . ":$gateTarget:" . $sessionData[$gateTarget][$cabang2_id][$new_ckeys]);
                                cekMerah($sessionData[$gateTarget][$cabang2_id]["hutang_pph"]);
                            }
                            foreach ($gateSummaryCabang as $sVal) {
//                                cekHitam($sVal);
//                                matiHere(__LINE__);
                                $sessionData[$gateTarget][$cabang2_id][$sVal] += makeValue($sVal, $iiSpec, $iiSpec, 0);
                            }
                        }
                    }
//                    arrPrint($sessionData["items7"]);
//matiHere(__LINE__);
                    foreach ($sessionData["items7"] as $cbID => $cbSpec) {
                        //untuk handling pusat
                        $sessionData["items7_sum"][$cbID] = $cbSpec;
                        foreach ($copyKey as $cKey => $cVal) {
                            $new_ckey_ = "nilai_" . $iiSpec[$cKey];
                            $sessionData["items7_sum"][$cbID][$new_ckey_] = isset($cbSpec[$new_ckey_]) ? $cbSpec[$new_ckey_] : 0;
                            $sessionData["items7_sum"][$cbID]["hutang_pph"] = isset($cbSpec["hutang_pph"]) ? $cbSpec["hutang_pph"] : 0;
                        }
                    }

                    foreach ($sessionData["items8"] as $cbID => $cbSpec) {
                        //untuk handling biaya terjadi dicabang
                        $sessionData["items8_sum"][$cbID] = $cbSpec;
                        foreach ($copyKey as $cKey => $cVal) {
                            $new_ckey_1 = "nilai_" . $iiSpec[$cKey];
                            $sessionData["items8_sum"][$cbID][$new_ckey_1] = isset($cbSpec[$new_ckey_1]) ? $cbSpec[$new_ckey_1] : 0;
//                            $sessionData["items8_sum"][$cbID]["hutang_kepusat"] = isset($cbSpec[$new_ckey]) ? $cbSpec[$new_ckey] : 0;
//                            $sessionData["items8_sum"][$cbID]["piutang_cabang"] = isset($cbSpec[$new_ckey]) ? $cbSpec[$new_ckey] : 0;
                            $sessionData["items8_sum"][$cbID]["hutang_pph"] = isset($cbSpec["hutang_pph"]) ? $cbSpec["hutang_pph"] : 0;
                        }
                    }

                    break;
                case "main":

                    break;
            }

        }
    }
    //--------
//
//    arrPrintCyan($sessionData["items"]);
//    arrPrintPink($sessionData["items7"]);
//    arrPrintKuning($sessionData["items7_sum"]);
//matiHere(__LINE__);
    return $sessionData;

}

/** ------------------------------------------------------
 * untuk ngecek session modul
 * ------------------------------------------------------*/
function cekSessionTransaksi($cCode)
{
    $balikan = false;
    if (isset($_SESSION[$cCode]["items"]) && count($_SESSION[$cCode]["items"])) {
        $balikan = true;
    }
    else {
        $balikan = false;
    }

    return $balikan;
}

function _showNotifActiveSessionSimpel($cCode)
{
    $sessmain = $_SESSION[$cCode]['main'];
    $sessitems = $_SESSION[$cCode]['items'];
    $dtime = $sessmain['dtime'];
    $jenisTrName = $sessmain['jenisTrName'];
    $main = "";
    if (isset($sessmain['requestReferenceNomer'])) {
        $requestReferenceNomer = $sessmain['requestReferenceNomer'];
        $customerName = $sessmain['customerName'];


        $main .= "<div><strong>NO SO:</strong> $requestReferenceNomer</div>";
        $main .= "<div><strong>Konsumen:</strong> $customerName</div>";
    }
    else {


        $main .= "<div class='text-capitalize'><strong>Transaksi:</strong> $jenisTrName</div>";

    }

    $items = "";
    $items .= "<div>";
    // $items .= "<strong>Daftar Barang:</strong>";
    $items .= "<ul class='text-left'>";
    foreach ($sessitems as $prodid => $sessitem) {
        // arrPrint($sessitem);

        $items .= "<li>";
        $items .= $sessitem['jml'] . " ";
        $items .= $sessitem['kategori_nama'] . " - ";
        $items .= "<strong>" . $sessitem['nama'] . "</strong> ";
        $items .= $sessitem['produk_kode'];
        $items .= "</li>";
    }
    $items .= "</ul>";
    $items .= "</div>";

    $content = "<div><r>Anda masih ada transaksi " . strtoupper($jenisTrName) . " yang terbuka (belum diselesaikan), Harap diselesaikan terlebih dahulu</r></div>";

    $content .= "<hr>" . $main;
    $content .= "<hr>" . $items;
    $content .= "<span class=meta>Transaksi dibuka pada: " . indonesian_date($dtime) . "</span>";
    $content .= "<div class='alert alert-warning'>Untuk membersihkan sesi, Anda bisa melakukan login ulang terlebih dahulu</div>";

    return $content;
}

function getTargetTransaksi()
{
    $ci = &get_instance();
    $misc = $ci->config->item("payment_source");
    $result = array();
    if (sizeof($misc) > 0) {
        foreach ($misc as $jenisTr => $spec) {
            foreach ($spec as $subSpec) {
                foreach ($subSpec as $xxSpec) {
                    $jenisTarget = $xxSpec["jenisTarget"];
                    if (!isset($result[$jenisTarget])) {
                        $result[$jenisTarget] = array();
                    }
                    $result[$jenisTarget][] = $jenisTr;
                }
            }
        }
    }

    return $result;

}

function checkerSession($jenisTr)
{
    $ci = &get_instance();
    $cCode = "_TR_" . $jenisTr;

    switch ($jenisTr){
        case "5822":

            break;
    }

}

function cabangStatus_he_misc(){
    $tmp = array(
        "1"=>array(
            "nama"=>"pusat",
            "alias"=>"center",
        ),
        "3"=>array(
            "nama"=>"cabang",
            "alias"=>"branch",
        ),
    );

    return $tmp;
}
function kodePajak($kodePajak, $nomer_faktur, $nilai_pajak)
{

    $kode_faktur = substr($nomer_faktur, 0, 2);
    $result = array();
    if (array_key_exists($kode_faktur, $kodePajak)) {
        $result["nilai_ppn_" . $kode_faktur] = $nilai_pajak;
    }
    else {
        $result["nilai_ppn_gunggung"] = $nilai_pajak;
    }

    return $result;
}

function checkerKodePajak($nomer_faktur)
{
    $ci = &get_instance();
    $kodePajak = $ci->config->item("kodePajak");
    $kode_faktur = substr($nomer_faktur, 0, 2);
    $result = array();
    if (array_key_exists($kode_faktur, $kodePajak)) {
        $result = array(
            "nilai" => 1,
            "label" => "",
        );
    }
    else {
        $result = array(
            "nilai" => 0,
            "label" => "Nomer Faktur yang Anda isi salah/tidak sesuai dengan Kode Faktur Pajak dari Direktorat Jenderal Pajak (DJP). Silahkan dikoreksi dahulu.",
        );
    }

    return $result;
}

function checkerNomerNpwp($nomer_npwp)
{
    // hapus karakter spesial
    $nomer_npwp = str_replace(",", "", $nomer_npwp);
    $nomer_npwp = str_replace(".", "", $nomer_npwp);
    $nomer_npwp = str_replace("-", "", $nomer_npwp);

    // cek digit nomer npwp (15 digit dan 16 digit)
    if (strlen($nomer_npwp) < 15) {
        $msg = "Nomer NPWP yang anda isikan salah. Isikan Nomer NPWP 15 atau 16 digit. ";
        $result = array(
            "nilai" => 0,
            "label" => $msg,
        );
    }
    elseif (strlen($nomer_npwp) > 16) {
        $msg = "Nomer NPWP yang anda isikan salah. Isikan Nomer NPWP 15 atau 16 digit. ";
        $result = array(
            "nilai" => 0,
            "label" => $msg,
        );
    }
    else {
        $result = array(
            "nilai" => 1,
            "label" => NULL,
        );
    }

    return $result;
}
// edited by glg (22:30 WIB, 2025-12-23)
// change: mapping key setting e-signature per modul
// technical rationale: konsistensi nama key settings untuk toggle
if (!function_exists('get_esignature_setting_key')) {
    function get_esignature_setting_key($module)
    {
        $module = is_string($module) ? strtolower(trim($module)) : "";
        // edited by glg (11:32 WIB, 2025-12-23)
        // change: tambahkan mapping e-signature untuk modul distribusi
        // technical rationale: gunakan satu key global untuk semua variasi modul distribusi
        if (strpos($module, "distribusi") === 0) {
            return "distribusi_esignature";
        }
        // edited by glg (13:10 WIB, 2025-12-23)
        // change: tambahkan mapping e-signature untuk modul biaya
        // technical rationale: gunakan key khusus agar toggle e-signature biaya terpisah
        if ($module === "biaya") {
            return "biaya_esignature";
        }
        if ($module === "penjualan") {
            return "penjualan_esignature";
        }

        return "pembelian_esignature";
    }
}

// edited by glg (22:30 WIB, 2025-12-23)
// change: tambah helper is_esignature_enabled untuk cek setting global per modul
// technical rationale: menghindari query berulang dengan cache statik per request
if (!function_exists('is_esignature_enabled')) {
    function is_esignature_enabled($module = "pembelian")
    {
        static $cached = array();
        $settingKey = get_esignature_setting_key($module);
        if (isset($cached[$settingKey])) {
            return $cached[$settingKey];
        }
        $cached[$settingKey] = 1;
        $ci = function_exists('get_instance') ? get_instance() : null;
        if ($ci === null) {
            return $cached[$settingKey];
        }
        $ci->load->model("Mdls/MdlSetting");
        $st = new MdlSetting();
        $row = $st->getSettingOnoff($settingKey);
        if ($row && isset($row->nilai)) {
            $cached[$settingKey] = ((int)$row->nilai === 1) ? 1 : 0;
        }

        return $cached[$settingKey];
    }
}

// edited by glg (22:30 WIB, 2025-12-23)
// change: render_esignature_img menghormati toggle global e-signature per modul
// technical rationale: menonaktifkan tampilan e-signature tanpa ubah tiap view
if (!function_exists('render_esignature_img')) {
    function render_esignature_img($esignatureUrl, $fallbackHtml = "<br><br><br>", $module = "pembelian")
    {
        if (!is_esignature_enabled($module)) {
            return $fallbackHtml;
        }
        $esignatureUrl = is_string($esignatureUrl) ? trim($esignatureUrl) : "";
        if ($esignatureUrl === "") {
            return $fallbackHtml;
        }
        $safeUrl = htmlspecialchars($esignatureUrl, ENT_QUOTES, 'UTF-8');

        return "<div style='padding:6px 0;'><img src='" . $safeUrl . "' alt='esignature' style='max-height:80px;max-width:100%;'></div>";
    }
}
?>