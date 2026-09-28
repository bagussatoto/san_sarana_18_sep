<?php

//--include_once "MdlHistoriData.php";

class MdlEmployee extends MdlMother
{
    protected $tableName = "per_employee";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
        "status='1'",
        "trash='0'",
        // "employee_type='employee'",
        "ghost='0'",
        //        "php_session => '1'",
    );

    protected $validationRules = array(
        "nama"       => array("required", "singleOnly", "unique"),
        "nama_login" => array("required", "singleOnly", "unique"),
        "password"   => array("required"),
        "status"     => array("required"),
        // "struktur_id"   => array("required"),
        // "cabang_id"  => array("required"),
        // "fungsi_id"     => array("required"),
        // "division"   => array("required"),
        // "div_id"     => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id"         => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "name" => array(
            "label" => "nama",
            "type" => "int", "length" => "24", "kolom" => "nama",
            "inputType" => "text",
        ),
        "dc_id_name" => array(
            "label" => "Relasi akun utama",
            "type" => "varchar", "length" => "255", "kolom" => "dc_nama",
            "inputType" => "text",
            "urlTrigger" => "statik/Data/cekMasterDataEmployeeDc/Employee",
            "eventTrigger" => "onkeyup=\"debounceSearchEmployeeDc(encodeURI(this.value),this.id,this.getAttribute('data-link'),this.name);\" onclick=\"cek_master_data_employee_dc(encodeURI(this.value),this.id,this.getAttribute('data-link'),this.name);\"",
        ),
        "dc_id" => array(
            "label" => "dc id relasi",
            "type" => "int", "length" => "24", "kolom" => "dc_id",
            "inputType" => "hidden",
            //--"inputName" => "nama",
        ),
        "referensi_crm_account" => array(
            "label" => "referensi id crm",
            "type" => "int", "length" => "24", "kolom" => "referensi_crm_account",
            "inputType" => "text",

        ),
        "login_name" => array(
            "label"        => "login ID",
            "type"         => "int", "length" => "24", "kolom" => "nama_login",
            "inputType"    => "text",
            "urlTrigger"   => "statik/Data/cekUsernameAjax",
            "eventTrigger" => "onclick=\"copy_from('nama',this.name); $('input[name=\''+this.name+'\']').trigger('change');\" onchange=\"$('#notif_nama_login').load(this.getAttribute('data-link') + '?username=' + encodeURIComponent(this.value) + '&id=' + ($('#id').val() || ''));\"",
        ),
        "password"   => array(
            "label"     => "password",
            "type"      => "password", "length" => "24",
            "kolom"     => "password",
            "inputType" => "password",
            //--"inputName" => "nama_login",
        ),
        // "struktur"   => array(
        //     "type"       => "int",
        //     "label"      => "struktur",
        //     "kolom"      => "struktur_id",
        //     // "multi_rel" => true,
        //     // "multi_mdl" => "MdlEmployeeMembership",
        //     "inputType"  => "radio",
        //     "reference"  => "MdlStaticStructureEmployee",
        //     "strField"   => "name",
        //     "kolom_nama" => "struktur_nama",
        //     // -------------------------
        //     // "relExternal" => "MdlMenu",
        // ),
        "cabang_id"  => array(
            "label"         => "lokasi/penempatan",
            "type"          => "int",
            "length" => "24",
            "kolom"         => "cabang_id",
            "multi_rel"     => true,
            "multi_mdl"     => "MdlEmployeeMembershipCabang",
            "multi_parent"  => "employee_id",
            // ---------------------------start
            "inputType"     => "checkbox",
            "reference"     => "MdlCabang",
            "strFieldClass" => "status_nama", // kolom dr reference yg isinya dijadikan penanda class dlm form
            "strFieldFixClass" => "group_cabang", // kolom dr reference yg isinya dijadikan penanda class dlm form
            // "strField"   => "nama",
            "strFields"     => array(
                "status_nama",
                "nama",
            ),
            "kolom_nama"    => "cabang_nama",
            // "eventTrigger"  => "onclick=\"validateFunction(this.id,'fungsi_id');\"",
            "eventTrigger"  => "onclick=\"tambahForm(this.id,'jembrengan');\"",
            "custom_att"  => "",
            "anakan"  => true,
            // ---------------------------stop
        ),
        // "fungsi"     => array(
        //     "type"          => "int",
        //     "label"         => "fungsi",
        //     "kolom"         => "fungsi_id",
        //     // "multi_rel" => true,
        //     // "multi_mdl" => "MdlEmployeeMembership",
        //     "inputType"     => "radio",
        //     "reference"     => "MdlStaticFunctionEmployee",
        //     "strField"      => "name",
        //     "kolom_nama"    => "fungsi_nama",
        //     "strFieldClass" => "parent_str", // kolom dr reference yg isinya dijadikan penanda class dlm form
        //     // -------------------------
        //     // "relExternal" => "MdlMenu",
        // ),
        // "level"     => array(
        //     "type"          => "int",
        //     "label"         => "level",
        //     "kolom"         => "level_id",
        //     // "multi_rel" => true,
        //     // "multi_mdl" => "MdlEmployeeMembership",
        //     "inputType"     => "radio",
        //     "reference"     => "MdlStaticLevelEmployee",
        //     "strField"      => "name",
        //     "kolom_nama"    => "level_nama",
        //     // "strFieldClass" => "parent_str", // kolom dr reference yg isinya dijadikan penanda class dlm form
        //     // -------------------------
        //     // "relExternal" => "MdlMenu",
        // ),
        // "membership"    => array(
        //     "type"       => "mediumblob",
        //     "label"      => "access rights",
        //     "kolom"      => "membership_id",
        //     // "multi_rel" => true,
        //     // "multi_mdl" => "MdlEmployeeMembership",
        //     "inputType"  => "radio",
        //     "reference"  => "MdlStaticMemberShip",
        //     // "strField"   => "label",
        //     "strField"   => "name",
        //     "kolom_nama" => "membership_nama",
        //     //                "dataSource" => $this->config->item('userGroups'),
        //     // "dataSource" => array(//===see __construct
        //     // ),
        //     // -------------------------
        //     // "relExternal" => "MdlMenu", // belum dipakai boss
        // ),
//        "division"   => array(
//            "label"        => "division",
//            "type"         => "int", "length" => "24",
//            "kolom"        => "div_id",
//            "inputType"    => "combo",
//            "reference"    => "MdlDiv",
//            "defaultValue" => "18",
//        ),

        "allow_project" => array(
            "label"      => "view on project (pelaksana)",
            "type"       => "int", "length" => "24", "kolom" => "allow_project",
            "inputType"  => "combo",
            "dataSource" => array(0 => "no", 1 => "yes"), "defaultValue" => 0,
            //--"inputName" => "status",
        ),
        //        "last_name" => array(
        //            "label" => "last name",
        //            "type" => "int", "length" => "24", "kolom" => "nama_belakang",
        //            "inputType" => "text",
        //        ),

        "email"     => array(
            "label"     => "email",
            "type"      => "int", "length" => "24", "kolom" => "email",
            "inputType" => "text",
            //--"inputName" => "email",
        ),
        "telp"          => array(
            "label"     => "phone",
            "type"      => "int", "length" => "24", "kolom" => "tlp_1",
            "inputType" => "text",
            //--"inputName" => "telp",
        ),
        "hp"            => array(
            "label"     => "handphone",
            "type"      => "int", "length" => "24", "kolom" => "tlp_2",
            "inputType" => "text",
            //--"inputName" => "telp",
        ),
        "alamat"        => array(
            "label"     => "alamat",
            "type"      => "int", "length" => "24", "kolom" => "alamat_1",
            "inputType" => "text",
            //--"inputName" => "alamat",
        ),
        "kabupaten"     => array(
            "label"     => "district",
            "type"      => "int", "length" => "24", "kolom" => "kabupaten",
            "inputType" => "text",
            //--"inputName" => "alamat",
        ),
        "propinsi"      => array(
            "label"     => "province",
            "type"      => "int", "length" => "24", "kolom" => "propinsi",
            "inputType" => "text",
            //--"inputName" => "alamat",
        ),
        "country"       => array(
            "label"        => "country",
            "type"         => "varchar", "length" => "3", "kolom" => "country",
            "reference"    => "MdlCountry",
            "strField"     => "name",
            // "kolom_nama" => "cabang_nama",
            "defaultValue" => "ID",
            "inputType"    => "combo",
            //--"inputName" => "alamat",
        ),
        //        "cabang" => array(
        //            "label" => "cabang",
        //            "type" => "int", "length" => "24", "kolom" => "cabang_id",
        //            "inputType" => "combo",
        //            "reference" => "MdlCabang",
        //        ),
        /*"jenis"      => array(
            "label"      => "jenis (segera update ke access right)",
            "type"       => "varchar",
            "inputType"  => "combo",
            "dataSource" => array(
                "admin"      => "admin",
                "diskon_oto" => "diskon_oto",
                "finance"    => "finance",
                "kasir_in"   => "kasir_in",
                "kasir_penj" => "kasir_penj",
                "manager"    => "manager",
                "seller"     => "seller",
                "spv_gudang" => "spv_gudang",
                "spv_pemb"   => "spv_pemb",
                "spv_penj"   => "spv_penj",
                "spv_prod"   => "spv_prod",
                "superman"   => "superman",
                "supplier"   => "supplier",
//                "viewers" => "viewers",
            ),
            "kolom"      => "jenis",

        ),*/

        "status"        => array(
            "label"      => "status",
            "type"       => "int", "length" => "24", "kolom" => "status",
            "inputType"  => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"),
            "defaultValue" => 1,
            //--"inputName" => "status",
        ),
        "phpsessid"     => array(
            "label"     => "phpsessid",
            "type"      => "varchar", "length" => "255", "kolom" => "phpsessid",
            "inputType" => "hidden",// hidden
        ),
        "phpsess_dtime" => array(
            "label"     => "alamat",
            "type"      => "varchar", "length" => "", "kolom" => "phpsess_dtime",
            "inputType" => "hidden",
        ),
    );
    protected $listedFields = array(
        // "div_id" => "division",
        "id"            => "id",
        "nama"          => "name",
        "nama_login"    => "loginID",
        "allow_project" => "view on project",
        //        "alamat_1"   => "address",
        "email"         => "email",
        "tlp_1"         => "phone",
        "country"       => "country",

    );
    protected $updateFields = array(
        "phpsessid"     => "phpsesid",
        "phpsess_dtime" => "phpses_dtime",
        "php_session"   => "php_session",
        "ipadd"         => "ipadd",
        "devices"       => "devices",
    );
    // edited by glg (09:20 WIB, 2025-01-24)
    // change: Tambah kolom searchable untuk fitur search DataTables
    // technical rationale: listedFieldsSelectItem kosong menyebabkan $searchKoloms kosong,
    //                      createSmartSearch() di-skip, search tidak jalan sama sekali
    protected $listedFieldsSelectItem = array(
        "nama" => "nama",
    );
    protected $listedUnsetFields = array(
        "phpsessid",
    );
    // ---------------------------------------join
    // protected $innerJoint = array(
    //     "tabel_1" => array(
    //         "tbl" => "postal_codes",
    //         "select" => array(
    //             "propinsi",
    //             "kabupaten",
    //             "kecamatan",
    //             "kelurahan"
    //         ),
    //         "on" => array(
    //             "propinsi" => "propinsi_id",
    //             "kabupaten" => "kabupaten_id",
    //             "kecamatan" => "kecamatan_id",
    //             "kelurahan" => "kelurahan_id",
    //
    //         )
    //     ),
    // );

    public function getInnerJoint()
    {
        return $this->innerJoint;
    }

    public function setInnerJoint($innerJoint)
    {
        $this->innerJoint = $innerJoint;
    }
    // ---------------------------------------------

    public function __construct()
    {
        // $this->fields['membership']['dataSource'] = $this->config->item('userGroup');
        // $this->load->helper("he_access_right");
        // $this->fields['membership']['groupTransaksiLabel'] = groupAccessLabel_he_access_right();
        //        arrPrintWebs($this->fields['membership']['groupLabel']);
    }

    //region gs
    public function getTableName()
    {
        return $this->tableName;
    }

    public function getUpdateFields()
    {
        return $this->updateFields;
    }

    public function setUpdateFields($updateFields)
    {
        $this->updateFields = $updateFields;
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

    public function getListedUnsetFields()
    {
        return $this->listedUnsetFields;
    }

    public function setListedUnsetFields($listedUnsetFields)
    {
        $this->listedUnsetFields = $listedUnsetFields;
    }

    //endregion

    public function paramSyncNamaNama()
    {
        $mdls = array(
            "MdlCabang" => array(
                "id"         => "cabang_id",
                // "str" => "folders_nama",
                "kolomDatas" => array(
                    "nama" => "cabang_nama",
                ),
            ),
            "MdlDiv"    => array(
                "id"         => "div_id",
                "kolomDatas" => array(
                    "nama" => "div_name",
                ),
            ),
            // "MdlKendaraan"    => array(
            //     "id"  => "kendaraan_id",
            //     // "str" => "kendaraan_nama",
            //     "kolomDatas" => array(
            //         "nama" => "kendaraan_nama",
            //     ),
            // ),
            // "MdlLokasiIndex"  => array(
            //     "id"  => "lokasi",
            //     // "str" => "lokasi_nama",
            //     "kolomDatas" => array(
            //         "nama" => "lokasi_nama",
            //     ),
            // ),
        );

        return $mdls;

    }

    public function callLastActive()
    {
        $fields = array(
            "dtime",
            "last_dtime_active",
            "last_dtime",
            "cabang_id",
            "gudang_id",
        );
        foreach ($this->fields as $field) {
            $fields[] = $field['kolom'];
        }
        $this->db->select($fields);
        $this->filters = array();
        $filter_on = array(
            "trash"        => 0,
            "status"       => 1,
            "status_login" => 1,
        );
        $this->db->where($filter_on);
        $srcDatas = $this->lookupAll()->result();
        // showLast_query("lime");
        //
        // arrPrint($srcDatas);

        return $srcDatas;

    }

    public function forceLogout($user_id)
    {
        $condites = array(
            "id" => $user_id,
        );
        $dataUpds = array(
            "status_login" => 0,
            "phpsessid"    => 0,
        );
        $this->updateData($condites, $dataUpds);
    }

    public function callAllSeller()
    {
        $this->setFilters(array());
        $condites = array(
            "jenis"         => "seller",
            "status"        => "1",
            "ghost"         => "0",
            "employee_type" => "employee_cabang",
        );
        $this->db->where($condites);
        $srcs = $this->lookupAll()->result();
        // showLast_query("kuning");
        // cekHijau(sizeof($srcs));
        // arrPrintPink($srcs);

        $src_sellers = array();
        foreach ($srcs as $src) {
            $src_sellers[$src->id] = (array)$src;
        }

        $datas['data'] = $src_sellers;

        return $datas;
    }

    protected $pairValidate = array("nama");

    public function getPairValidate()
    {
        return $this->pairValidate;
    }

    public function setPairValidate($pairValidate)
    {
        $this->pairValidate = $pairValidate;
    }

    /* ------------------------------------------------------------
    * over write link add new data
    * ------------------------------------------------------------*/
    public function linkAddData()
    {
        return "Data/addEmployee/" . substr(get_class(), 3);
    }


}