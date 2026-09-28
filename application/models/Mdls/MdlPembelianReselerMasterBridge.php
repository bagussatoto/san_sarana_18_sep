<?php

//--include_once "MdlHistoriData.php";

class MdlPembelianReselerMasterBridge extends MdlMother
{

    protected $tableName = "pembelian_reseler_master_bridge";
        protected $indexFields = "id";

    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("status='1'", "trash='0'");

    protected $validationRules = array(
//        "nama"   => array("required", "singleOnly"),
////        "tlp_1"  => array("required", "numberOnly"),
//        "status" => array("required"),
        "referensi_id" => array("required"),
        "po_id" => array("required"),
        "so_id" => array("required"),
//        "division" => array("required"),
    );

    protected $listedFieldsView = array("domain");
    protected $fields = array(
        "main"=>array(
            "id",
            "referensi_id",
            "referensi_cabang_id",
            "dtime",
            "fulldate",
            "po_id",
            "po_nomer",
            "so_id",
            "so_nomer",
            "domain",
            "cli",
            "status",
        ),
        "item"=>array(
            "cabang_id",
            "cabang_nama",
            "referensi_id",
            "transaksi_id",
            "transaksi_no",
            "supplier_id",
            "supplier_nama",
            "produk_id",
            "produk_nama",
            "produk_ord_jml",
            "produk_ord_hrg",
            "oleh_id",
            "oleh_nama",
            "dtime",
            "dtime_auto",
            "auto_po_id",
            "auto_po_nomer",
            "auto_po_dtime",
            "qty_saldo",
            "cli",
            "status",
            "trash",
            "principal_id_spd",
            "principal_nomer_spd",
            "principal_dtime_spd",
            "principal_spd_oleh_id",
            "principal_spd_oleh_nama",
            "principal_produk_ord_hrg",
            "principal_produk_ord_jml",
        ),

//        "division" => array(
//            "label" => "division",
//            "type" => "int", "length" => "24", "kolom" => "div_id",
//            "inputType" => "combo",
//            "reference" => "MdlDiv",
//        ),
        //        "email" => array(
        //            "label" => "email",
        //            "type" =>"int","length"=>"24","kolom" => "email",
        //            "inputType" => "text",
        //            //--"inputName" => "email",
        //        ),
//        "telp"      => array(
//            "label"     => "telp",
//            "type"      => "int", "length" => "24", "kolom" => "tlp_1",
//            "inputType" => "text",
            //--"inputName" => "telp",
//        ),
//        "alamat"    => array(
//            "label"     => "alamat",
//            "type"      => "int", "length" => "24", "kolom" => "alamat",
//            "inputType" => "text",
            //--"inputName" => "alamat",
//        ),
//        "kabupaten" => array(
//            "label"     => "kabupaten",
//            "type"      => "int", "length" => "24", "kolom" => "kabupaten",
//            "inputType" => "text",
            //--"inputName" => "alamat",
//        ),
//        "propinsi"  => array(
//            "label"     => "propinsi",
//            "type"      => "int", "length" => "24", "kolom" => "propinsi",
//            "inputType" => "text",
            //--"inputName" => "alamat",
//        ),
//
//        "status" => array(
//            "label"      => "status",
//            "type"       => "int", "length" => "24", "kolom" => "status",
//            "inputType"  => "combo",
//            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "status",
//        ),


    );
    protected $listedFields = array(
        "nama"   => "name",
//        "alamat" => "address",

    );



    public function getTableItem()
    {
        return $this->tableItem;
    }
    public function setTableItem($tableItem)
    {
        $this->tableItem = $tableItem;
    }
    public function getTableName()
    {
        return $this->tableName;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function getIndexFields()
    {
        return $this->indexFields;
    }

    public function setIndexFields($indexFields)
    {
        $this->indexFields = $indexFields;
    }

    public function getListedFieldsForm()
    {
        return $this->listedFieldsForm;
    }

    public function setListedFieldsForm($listedFieldsForm)
    {
        $this->listedFieldsForm = $listedFieldsForm;
    }

    public function getListedFieldsHidden()
    {
        return $this->listedFieldsHidden;
    }

    public function setListedFieldsHidden($listedFieldsHidden)
    {
        $this->listedFieldsHidden = $listedFieldsHidden;
    }

    public function getSearch()
    {
        return $this->search;
    }

    public function setSearch($search)
    {
        $this->search = $search;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    public function getValidationRules()
    {
        return $this->validationRules;
    }

    public function setValidationRules($validationRules)
    {
        $this->validationRules = $validationRules;
    }

    public function getListedFieldsView()
    {
        return $this->listedFieldsView;
    }

    public function setListedFieldsView($listedFieldsView)
    {
        $this->listedFieldsView = $listedFieldsView;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    public function getListedFields()
    {
        return $this->listedFields;
    }

    public function setListedFields($listedFields)
    {
        $this->listedFields = $listedFields;
    }

    public function addDataMasterBridge($params){
        if (is_array($params)) {
            if (sizeof($params) > 0) {

                $data = array();
                foreach ($params as $fName => $fValue) {
                    if (in_array($fName, $this->fields['main'])) {
                        $data[$fName] = $fValue;
                    }
                }
//cekHitam($this->tableName);
//arrprint($this->fields);
//arrprint($data);
                $this->db->insert($this->tableName, $data);
                $insertID = $this->db->insert_id();
                return $insertID;
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function getsubsidiaryOrder($so_id,$cli_id,$domain){
        $where = array(
            "so_id"=>$so_id,
            "cli_id"=>$cli_id,//so holding company
            "domain"=>$domain
        );
        $this->db->where($where);
        $temp =$this->db->get($this->tableName);
        return $temp;

    }

}