<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Penjualan extends REST_Controller
{
    private $model;

    private $validationRules=array(
        "nama",
        "nama_login",
        "email",

    );

    private $masterInits=array();

    function __construct($config = 'rest')
    {

        parent::__construct($config);

//        $this->model = "Mdl".ucfirst($this->uri->segment(4));
        $this->model = "MdlTransaksiPenjualan";

        $this->load->database();

        $this->load->model("Mdls/".$this->model);

    }
    function index(){

    }



}

?>