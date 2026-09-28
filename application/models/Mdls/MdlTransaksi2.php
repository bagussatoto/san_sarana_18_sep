<?php

/*
 * biar bisa load MdlTransaksi oleh receiptElement
 */
require APPPATH.'/models/MdlTransaksi.php';//just add this line and keep rest

class MdlTransaksi2 extends MdlTransaksi
{
    public function __construct()
    {
        parent::__construct();
    }


}