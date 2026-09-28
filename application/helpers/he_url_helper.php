<?php
/**
 * Created by thomas Maya Graha Kencana.
 * Date: 12/04/18
 * Time: 20:30
 */
define('MODUL_URL', base_url() . url_segment(1) . "/" . url_segment(2) . "/" . url_segment(3));
define('MODUL_PATH', base_url() . url_segment(1) . "/");
define('MODUL_CONFIG_PATH', "../../modules/" . url_segment(1) . "/config/");
define('MODUL_TEMPLATE_PATH', "application/modules/" . url_segment(1) . "/");
define('MODUL_TEMPLATE_ASSETS', "application/modules/" . url_segment(1) . "/assets");
define('MGK_LIVE', '202.65.117.72');
define('ADM_DOMAIN', 'https://demo.mayagrahakencana.com/san_11sep/');
//define('ADM_DOMAIN', 'https://sab.mayagrahakencana.com/');
define('ADM_LOCAL_DOMAIN', 'https://demo.mayagrahakencana.com/san_sarana_18_sep/');//domain yang dipakai
define('ADM_CRM_DOMAIN', 'https://charly.mayagrahakencana.com/');//domain crm
define('WEBHOOK_SECRET','mgk2025webhooks');

function url_segment($key = "")
{
    //    return "kekeke $key";
    $ci = &get_instance();
    if ($key == "") {
        return $ci->uri->segment_array();
    }
    else {

        return $ci->uri->segment($key);
    }
}

function modul()
{
    return url_segment(1);
}

function url_cleanup($url_e)
{
    $var = str_replace("=", "", $url_e);
    return $var;
}

function url_referer()
{
    if (isset($_SERVER['HTTP_REFERER'])) {
        return $_SERVER['HTTP_REFERER'];
    }
}

function my_host()
{

    return $_SERVER['HTTP_HOST'];
}

function ipadd()
{
    return $_SERVER['REMOTE_ADDR'];
}

function local_version()
{
    return "1.0101.2022.Corp";
}

function cdn_upload_images()
{
    return "https://cdn.mayagrahakencana.com/images/Upload/files";
}

function cdn_upload_document()
{
    return "https://cdn.mayagrahakencana.com/images/Upload/document";
}


function cdn_suport()
{
    return "https://cdn.mayagrahakencana.com/assets/suport/";
}

function local_suport()
{
    return base_url() . "assets/";
}

function img_produk()
{
    return base_url() . "public/images/produks/";
}

function img_profile()
{
    return base_url() . "public/images/profiles";
}

function img_sys()
{
    return base_url() . "public/images/sys";
}

function img_profile_default()
{
    return img_profile() . "/profile-default.png";
}

function img_blank()
{
    // return base_url(). "assets/images/img_blank.gif";
    return base_url() . "public/images/produks/img_blank.png";
}

function img_maintenace()
{
    // return base_url(). "assets/images/img_blank.gif";
    return base_url() . "public/images/sys/under-maintenance.png";
}

function img_bitzer()
{
    // return base_url(). "assets/images/img_blank.gif";
    return base_url() . "public/images/sys/bitzer.png";
}

function img_loading_muntir()
{

    return base_url() . "public/images/sys/load_muntir.gif";
}

function url_sanhistory()
{
    return "https://sanhistory.mayagrahakencana.com/";
    // return "http://demo.mayagrahakencana.com/san/";
}

function img_logo_header()
{

    return img_profile() . "/logo_header.png";
}

function img_logo_header_full()
{

    return img_profile() . "/logo_header_full.png";
}

function img_favicon()
{

    return img_profile() . "/favicon.ico";
}

//function url_sanhistory()
//{
//    return "https://sanhistory.mayagrahakencana.com/";
//    // return "http://demo.mayagrahakencana.com/san/";
//}

function upload_image($files)
{
    // $files = $_FILES['file'];

    $request = curl_init(cdn_upload_images());
    $realpath = realpath($files['tmp_name']);
    curl_setopt($request, CURLOPT_POST, true);
    $fields = array(
        //        'file'          => "@".$realpath.";filename=".$files['name'].";type=".$files['type'],
        'file'          => new \CurlFile($realpath, $files['type'], $files['name']),
        'server_source' => $_SERVER['HTTP_HOST'],
    );
    curl_setopt($request, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
    $cUrl_result = json_decode(curl_exec($request));
    curl_close($request);

    return $cUrl_result;


    /* =========================
    $url_img = $cUrl_result->full_url;
    // ========================= */
}

function upload_document($files)
{
    // $files = $_FILES['file'];

    $request = curl_init(cdn_upload_document());
    $realpath = realpath($files['tmp_name']);
    curl_setopt($request, CURLOPT_POST, true);
    $fields = array(
        //        'file'          => "@".$realpath.";filename=".$files['name'].";type=".$files['type'],
        'file'          => new \CurlFile($realpath, $files['type'], $files['name']),
        'server_source' => $_SERVER['HTTP_HOST'],
    );
    curl_setopt($request, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
    $cUrl_result = json_decode(curl_exec($request));
    curl_close($request);

    return $cUrl_result;


    /* =========================
    $url_img = $cUrl_result->full_url;
    // ========================= */
}

function download_tpl($tpl_name)
{
    $vars = array(
        "customer"  => "tpl_konsumen.xlsx",
        "produk"    => "tpl_produk.xlsx",
        "bahan"     => "tpl_bahan_baku.xlsx",
        "komposisi" => "tpl_produk_komposisi_resep.xlsx",
    );
    $tpl_file = $vars[$tpl_name];

    return base_url() . "download/$tpl_file";
}

function img_working()
{
    return base_url() . "public/images/sys/the-Lumber-jack.gif";
}

function call_curl($link)
{
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL            => $link,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => ["Accept: application/json"],
    ]);
    $response = curl_exec($curl);
    curl_close($curl);

    $data = json_decode($response, true);

    return $data;
}

function autoIdle_kick(){
    //region deteksi berapa lama idlenya
//    matiHEre(__LINE__);
    $ci = &get_instance();
    $webMaintenance = $ci->config->item('maintenance');
    $webLogin = $ci->config->item('logins');
    $idle_allowed = $webLogin['idleTime'];

    arrPrint($webLogin);
//            $logout = base_url() . "auth/Login/authLogout?xxx=";
//            redirect($logout);
//    matiHere($idle_allowed."**");
    $ci->load->model("Mdls/" . "MdlEmployee");
    $o = new MdlEmployee();
    $o->setFilters(array());
    // $tmpUser[0]->ghost
    $att = isset($ci->session->login['id']) ? $ci->session->login['id'] : null;
    $empKoloms = array(
        "id",
        "ghost",
        "nama",
        "last_dtime_active",
    );
    $ci->db->select($empKoloms);
    $tmpUser = $o->lookupByCondition(array(
        "id" => $att,
    ))->result();
    $last_dtime_active = $tmpUser[0]->last_dtime_active;
    $anggota_nama = $tmpUser[0]->nama;
    $ghost = $tmpUser[0]->ghost;

    // cekHitam($detik);

    $last_dtime_s = dtimeToSecond($last_dtime_active);
    $dtime_now = dtimeNow();
    $jam_now = dtimeNow('H:i');
    $dtimenow_s = dtimeToSecond($dtime_now);
    $idle_s = $dtimenow_s - $last_dtime_s;
    $idle_m = $idle_s / 60;
    $idle_m_f = round($idle_m);

    $pakai_ini = 1;
    $ghost=0;
    if($pakai_ini){
        if (isset($webLogin["idleTime"])) {
            if ($ghost == 0) { // Bypass idle logout untuk user ghost
                if ($idle_m > $idle_allowed) {
                    print_r($webLogin);
                    echo "aha";
                    $mesage = "$jam_now Terdeteksi idle selama $idle_m_f menit<br>silahkan login kembali untuk kembali beraktifitas";
                    $mesage_e = urlencode(blobEncode($mesage));

                    writeLog("expired", "User $anggota_nama (ID: $att) forced logout due to idle time ($idle_m_f mins)", "auth");

                    // Hancurkan session di sisi server secara instan demi kepatuhan ISO & Best Practice

                    $logout = base_url() . "auth/Login/authLogout";
                    $ci->session->sess_destroy();
                    redirect($logout);

                    exit;
                }
            }
        }
        else{
//            echo "ahii";
//            arrPrint($webLogin);
//            $logout = base_url() . "auth/Login/authLogout?xxx=";
//            redirect($logout);
//            matiHere($idle_allowed."**");
        }
    }
    //endregion
}

// function call_curl_cek($link)
// {
//     $curl = curl_init();
//     curl_setopt_array($curl, [
//         CURLOPT_URL            => $link,
//         CURLOPT_RETURNTRANSFER => true,
//         CURLOPT_HTTPHEADER     => [
//             "Accept: application/json",
//             "Accept-Encoding: gzip"
//         ],
//         CURLOPT_TIMEOUT        => 10,
//         CURLOPT_CONNECTTIMEOUT => 5,
//         CURLOPT_SSL_VERIFYPEER => false, // Nonaktifkan untuk pengujian
//     ]);
//
//     $response = curl_exec($curl);
//     curl_close($curl);
//
//     if (!$response) {
//         return ['error' => 'Request failed'];
//     }
//
//     $data = json_decode($response, true);
//     if (json_last_error() !== JSON_ERROR_NONE) {
//         return ['error' => 'Invalid JSON response'];
//     }
//
//     return $data;
// }
