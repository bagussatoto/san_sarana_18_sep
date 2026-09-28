<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Customers extends REST_Controller
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
        $this->model = "MdlCustomer";

        $this->load->database();

        $this->load->model("Mdls/".$this->model);
        $this->masterInits = array(
            "npwp" => "------",
            "due_days"=>"0000-00-00",
        );
    }

    function lookupByID_get()
    {

        $id=$this->uri->segment(4);
        $mdlName = $this->model;
        $o = new $mdlName();
        $tmp = $o->lookupByID($id)->result();
        
        $result = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $tmpData = array();
                foreach ($o->getFields() as $fName => $fSpec) {
                    $realFieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
                    $tmpData[$fName] = $row->$realFieldName;
                }
                $result[] = $tmpData;
            }
        }
        $this->response($result, 200);
    }

    function lookupByName_get()
    {

        $id=$this->uri->segment(4);
        $mdlName = $this->model;
        $o = new $mdlName();
        $o->addFilter("nama_login='$id'");
        $tmp = $o->lookupAll($id)->result();

//        die($this->db->last_query());
        $result = 0;
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $result = $row->id;
            }
        }
        $this->response($result, 200);
    }

    function addItem_post()
    {
        $mdlName = $this->model;
        $o = new $mdlName();

        $tmpInput=$_POST;
        foreach ($this->validationRules as $key) {
            if (!isset($tmpInput[$key]) || strlen($tmpInput[$key]) < 1) {
                die("we did not receive an input post variable named <b>" . $key . "</b><br>please make sure you set it before re-making this request ");
            }
        }


        foreach($this->masterInits as $key=>$src){
            $tmpInput[$key]=$src;
        }


        foreach ($o->getFields() as $fName => $fSpec) {

            $realFieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
            $data[$realFieldName] = isset($tmpInput[$fName])?$tmpInput[$fName]:"";
//            echo "iterating $fName tobe $realFieldName\n";
        }
        foreach($_POST as $key=>$val){
            $data[$key]=$val;
        }

//        print_r($data);

        $this->db->trans_start();
        $insert = $o->addData($data);
        $this->db->trans_complete();
//        cekmerah($this->db->last_query());
        if ($insert>0) {
//            $this->response($data, 200);
            $this->response($insert, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }

    function editItem_put()
    {
        $id = $this->uri->segment(5);
        $mdlName = $this->model;
        $o = new $mdlName();
        foreach ($o->getFields() as $fName => $fSpec) {
            $realFieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
            $data[$realFieldName] = $this->put($fName);
            $dbFname[$fName] = $fName;
        }
        // $this->db->where('id', $id);

        // $update = $this->db->update('telepon', $data);
        $update = $o->updateData(array("id" => $id), $data);
        //print_r($this->db->last_query());
        if ($update) {
            return $this->response($data, 200);
        } else {
            return $this->response(array('status' => 'fail', 'code' => 502, 'debug $Data' => $data, '$dbFname' => $dbFname, 'Debug put' => $this->put()));
        }
    }

    function editItem_get(){
        $this->response(array('status' => 'fail', 502));
    }
    function addRequestItem_post()
    {
        // Konsisten dengan pola project: gunakan $this->model
        $mdlName = $this->model;
        $this->load->model("Mdls/" . $mdlName);
        $o = new $mdlName();

        // Ambil input dari JSON atau POST
        $input = json_decode(trim(file_get_contents('php://input')), true);
        $data_0 = [];
        if (!empty($_POST)) {
            if (function_exists('blobDecode')) {
                $data_0 = blobDecode($_POST);
            } else {
                $data_0 = $_POST;
            }
        } elseif (!empty($input)) {
            if (function_exists('blobDecode')) {
                $data_0 = blobDecode($input);
            } else {
                $data_0 = $input;
            }
        }

        // Default data kosong jika tidak ada input
        if (empty($data_0)) {
            $data_0 = [];
        }

        // Ambil data utama
        $data = isset($data_0['data']['items'][0]) ? $data_0['data']['items'][0] : $data_0;
        if (isset($input['testing'])) {
            $data = $input;
        }

        // Mapping kolom dari input ke field DB
        $koloms = [
            "nama"          => "company_name",
            "email"         => "email",
            "tlp_1"         => "phone",
            "alamat"        => "address",
            "kelurahan"     => "subdivision",
            "kecamatan"     => "subdistrict",
            "kabupaten"     => "city",
            "propinsi"      => "province",
            "country"       => "state",
            "kode_pos"      => "zip",
            "no_ktp"        => "nik",
            "npwp"          => "npwp",
            "dtime_request" => "create_date",
            "referensi_id"  => "id",
            "domain"        => "domain",
        ];

        $data_baru = [];
        foreach ($koloms as $kolom => $kolom_sumber) {
            if ($kolom === 'nama') {
                $nilai = !empty($data['company_name']) ? $data['company_name'] :
                        (!empty($data['name']) ? $data['name'] :
                        (!empty($data['customer_name']) ? $data['customer_name'] :
                        (!empty($data['lead_name']) ? $data['lead_name'] :
                        (!empty($data['client_name']) ? $data['client_name'] :
                        (!empty($data['nama']) ? $data['nama'] : null)))));
            } else {
            $nilai = isset($data[$kolom_sumber]) ? $data[$kolom_sumber] : (isset($data[$kolom]) ? $data[$kolom] : null);
            }
            $data_baru[$kolom] = $nilai;
        }

        // Tambahkan default value dari masterInits jika ada
        if (property_exists($this, 'masterInits') && is_array($this->masterInits)) {
            foreach ($this->masterInits as $key => $val) {
                if (!isset($data_baru[$key]) || $data_baru[$key] === null) {
                    $data_baru[$key] = $val;
                }
            }
        }

        $this->db->trans_start();
        $insert = $o->addRequest($data_baru);
        $this->db->trans_complete();
        $query = $this->db->last_query();

        if ($insert > 0) {
            $vars = [
                'status'       => 200,
                'last_id'      => $insert,
                'message'      => 'Insert berhasil',
                'data'         => $data,
                'datas_insert' => $data_baru,
            ];
            $this->response($vars, 200);
        } else {
            $this->response([
                'last_query' => $query,
                'status'     => 'fail',
                502
            ]);
        }
    }

    function getBranches_get()
    {
        $div_id = $this->uri->segment(4);
        if (!$div_id) {
            $div_id = $this->input->get('div_id');
        }

        $this->load->model("Mdls/MdlCabang");
        $c = new MdlCabang();

        // if ($div_id) {
        //     $this->db->group_start();
            $this->db->where("id <>", CB_ID_PUSAT);
        //     $this->db->or_where("parent", $div_id);
        //     $this->db->group_end();
        // }
        $tempCabang = $c->lookupAll()->result();

        $result = array();
        foreach ($tempCabang as $row) {
            $tmpData = array();
            foreach ($c->getFields() as $fName => $fSpec) {
                $realFieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
                $tmpData[$fName] = $row->$realFieldName;
            }
            $result[] = $tmpData;
        }

        return $this->response(array(
            "success" => true,
            "data" => $result
        ), 200);
    }

}

?>