<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Debug extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();

        /* ----------------------------------------------------------------------------------
          * loader cunstruk yg wajib ada
          * variabel-variabel bisa langsung dipangil, apa saja yang ada bisa dilihat didalamnya
          * ----------------------------------------------------------------------------------*/
        // require_once "_construct_file.php";
        $_GET['debuger'] = 1;

    }


    public function index()
    {

        $cCode = "login";
        // cekKuning($cCode);
        if (isset($_SESSION[$cCode])) {
            // cekKuning("shopping-cart (creator)");
            arrprint($_SESSION[$cCode]);
        }
        else {
            arrPrint($_SESSION);
            die("<h1>the gate index you want to debug has not been formed yet!</h1>");

        }

        arrPrintKuning($_SESSION);
    }

    public function followupPreviewMobile()
    {
        $this->index();
    }

    public function MiniDesk()
    {
        $this->index();
    }
}
