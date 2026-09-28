<?php

//--include_once "MdlHistoriData.php";
class MdlCompany extends MdlMother
{

    protected $tableName = "company_profile";
    protected $indexFields = "id";

    /* -------------------------------
     * kolom yg akan dicari pada smartsearch
     * -------------------------------*/
    protected $listedFieldsSelectItem = array(
        "jenis","alias"
    );

    // protected $listedF = array_map(function ($value) use ($tableNames) {
    //         return $tableNames.".".$value;
    //     }, $this->listedFieldsSelectItem);

    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("jenis='profile'", "extern_type='company'", "status='1'", "trash='0'");
    // protected $sortBy = array(
    //     // "kolom" => "alias",
    //     // "mode"  => "ASC",
    // );

    protected $validationRules = array(

        "tlp_1"     => array("required", "numberOnly"),
        "no_ktp"    => array("required", "numberOnly"),
        "nama"      => array("required"),
        "npwp"      => array("required"),
        "status"    => array("required"),
        "tlp"       => array("required"),
        "alamat"    => array("required"),
        "kabupaten" => array("required"),
        "propinsi"  => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id"       => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "nama"     => array(
            "label"     => "name",
            "type"      => "varchar", "length" => "255",
            "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "email",
        ),
        // "alias"     => array(
        //     "label"     => "Alias",
        //     "type"      => "varchar", "length" => "255",
        //     "kolom" => "alias",
        //     "inputType" => "text",
        //     //--"inputName" => "email",
        // ),
        "npwp"     => array(
            "label"     => "npwp",
            "type"      => "varchar", "length" => "24", "kolom" => "npwp",
            "inputType" => "text",
            //--"inputName" => "email",
        ),


        "email"     => array(
            "label"     => "email",
            "type"      => "varchar", "length" => "255", "kolom" => "email",
            "inputType" => "text",
            //--"inputName" => "email",
        ),
        "telp"      => array(
            "label"     => "phone",
            "type"      => "int", "length" => "24", "kolom" => "tlp",
            "inputType" => "text",
            //--"inputName" => "telp",
        ),
        "telp2"     => array(
            "label"     => "phone#2",
            "type"      => "int", "length" => "24", "kolom" => "tlp_2",
            "inputType" => "text",
            //--"inputName" => "telp",
        ),
        "telp3"     => array(
            "label"     => "phone#3",
            "type"      => "int", "length" => "24", "kolom" => "tlp_3",
            "inputType" => "text",
            //--"inputName" => "telp",
        ),
        "alamat"    => array(
            "label"     => "address",
            "type"      => "varchar", "length" => "255", "kolom" => "alamat",
            "inputType" => "text",
            //--"inputName" => "alamat",
        ),
        // "kelurahan" => array(
        //     "label"     => "kelurahan",
        //     "type"      => "int", "length" => "24", "kolom" => "kelurahan",
        //     "inputType" => "text",
        //     //--"inputName" => "alamat",
        // ),
        // "kecamatan" => array(
        //     "label"     => "kecamatan",
        //     "type"      => "int", "length" => "24", "kolom" => "kecamatan",
        //     "inputType" => "text",
        //     //--"inputName" => "alamat",
        // ),
        // "kabupaten" => array(
        //     "label"     => "district",
        //     "type"      => "int", "length" => "24", "kolom" => "kabupaten",
        //     "inputType" => "text",
        //     //--"inputName" => "alamat",
        // ),
        // "propinsi"  => array(
        //     "label"     => "province",
        //     "type"      => "int", "length" => "24", "kolom" => "propinsi",
        //     "inputType" => "text",
        //     //--"inputName" => "alamat",
        // ),

        "kode_pos" => array(
            "label"     => "zip code",
            "type"      => "varchar",
            "length" => "6",
            "kolom" => "kode_pos",
            "inputType" => "text",
            //--"inputName" => "alamat",
        ),
        "ppn_faktor" => array(
            "label"     => "zip code",
            "type"      => "varchar",
            "length" => "6",
            "kolom" => "ppn_faktor",
            "inputType" => "text",
            //--"inputName" => "alamat",
        ),
        "ppn_constanta" => array(
            "label"     => "zip code",
            "type"      => "varchar",
            "length" => "6",
            "kolom" => "ppn_constanta",
            "inputType" => "text",
            //--"inputName" => "alamat",
        ),
        "jenis_usaha" => array(
            "label"     => "jenis usaha",
            "type"      => "varchar", "length" => "6", "kolom" => "jenis_usaha",
            "inputType"  => "combo",
            "dataSource" => array("pkp" => "pkp", "non_pkp" => "non pkp"), "defaultValue" => "pkp",
            //--"inputName" => "alamat",
        ),
        "status"  => array(
            "label"      => "status",
            "type"       => "int", "length" => "24", "kolom" => "status",
            "inputType"  => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "status",
        ),
    );
    protected $listedFields = array(
//        "extern_id" => "company",
        "nama"     => "nama",
        "npwp"    => "NPWP",
        "tlp"    => "telephon",
        "alamat"    => "address",
        "kecamatan" => "kecamatan",
        "kabupaten" => "kabupaten",
        "propinsi"  => "propinsi",
        "kode_pos"  => "kode pos",
    );

    /* ---------------------------
     * akan masuk ke session login
     * -------------------------*/
    protected $loginData = array(
        "jenis_bisnis" => "jenis_bisnis",
        "jenis_bisnis_label" => "jenisBisnis",
        "jenis_usaha_label" => "jenisUsaha",
        "jenis_usaha" => "jenis_usaha",
        "ppn_faktor" => "ppnFactor",
        "ppn_constanta" => "ppnConstanta",
        "ppn_constanta_str" => "ppnConstantaStr",
    );

    public function getLoginData()
    {
        return $this->loginData;
    }

    public function setLoginData($loginData)
    {
        $this->loginData = $loginData;
    }




    function __construct()
    {
        parent::__construct();
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

    public function callDatas()
    {
//        $cabang_id = isset($this->cabang_id) ? $this->cabang_id : matiDisini("cabang_id harap diset dulu" . __METHOD__);

        $condites = array(
            "main_used" => 0,
        );
        $src = $this->lookupByCondition($condites)->row();

        return $src;
    }

    protected $innerJoint = array(
        "tabel_1" => array(
            "tbl" => "postal_codes",
            "select" => array(
                "propinsi",
                "kabupaten",
                "kecamatan",
                "kelurahan"
            ),
            "on" => array(
                "propinsi" => "propinsi_id",
                "kabupaten" => "kabupaten_id",
                "kecamatan" => "kecamatan_id",
                "kelurahan" => "kelurahan_id",

            )
        ),
    );

    public function getInnerJoint()
    {
        return $this->innerJoint;
    }

    public function setInnerJoint($innerJoint)
    {
        $this->innerJoint = $innerJoint;
    }


    public function updateDataPreparation($kolom_data)
    {
//    arrPrint($_SESSION);
//        $toko_id = isset($this->toko_id) ? $this->toko_id : matiDisini("tokoo_id harap diset dulu" . __METHOD__);
        $where = array(
            "extern_type" => "company",
        );
        $data = array(
            $kolom_data . "_ok" => 1,
            $kolom_data . "_dtime" => dtimeNow(),
        );

        return parent::updateData($where, $data); // TODO: Change the autogenerated stub
    }

    public function lookupJoint()
    {
        $tbl_1 = "company_profile";
        $tbl_2 = "postal_codes";
        $this->db->select('
            cp.*,
            cp.propinsi,
            cp.kabupaten,
            pc.propinsi,
            pc.kabupaten,
            pc.kecamatan,
            pc.kelurahan,           
        ');
        $this->db->from("$tbl_1 cp");
        $this->db->join("$tbl_2 pc",
            'cp.propinsi = pc.propinsi_id AND cp.kabupaten = pc.kabupaten_id AND cp.kecamatan = pc.kecamatan_id AND cp.kelurahan = pc.kelurahan_id',
            'left');
        // $this->db->where('cp.id', $company_id);

        $query = $this->db->get();
        return $query->row(); // M
    }
}