<?php

/**
 * Created by JetBrains PhpStorm.
 * User: azes
 * Date: 5/9/12
 * Time: 11:56 AM
 * To change this template use File | Settings | File Templates.
 */

class TransaksiBridge
{


    public function __construct()
    {
        // parent::__construct();
        $this->CI =& get_instance();
        if (!file_exists($path = APPPATH.'modules/penjualan/models/ComRekeningPenjualan1.php')) {

            cekHitam('Model ComRekeningPenjualan1 tidak ditemukan di lokasi yang diharapkan.');
        }
        else{
            cekKuning("file ada : ".APPPATH.'modules/penjualan/models/ComRekeningPenjualan1.php');
            cekMerah("path sudah benar");
        }
        cekHere($path);

        $this->CI->load->model($path);

    }



}
?>