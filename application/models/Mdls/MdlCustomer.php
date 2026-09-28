<?php

//--include_once "MdlHistoriData.php";

class MdlCustomer extends MdlMother
{
    // protected $tableName = "per_customers";
    protected $tableName = "per_pihak_lain";
    protected $indexFields = "id";
    private $syncTableColumnsCache = array();
    private $addressSyncSchemaStatus = null;
    private $addressBackfillOnceResult = null;
    private $addressBackfillMigrationKey = "customer_addr_dc_id_address_backfill_v1";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
        "is_customer='1'",
        "trash='0'",
        //        "status='1'"
    );

    protected $validationRules = array(
        // "kategori_id" => array("required"),
        "nama"   => array("required", "singleOnly"),
        // "tlp_1" => array("required", "numberOnly"),
        // "tlp_1" => array("numberOnly"),
        // "no_ktp" => array("required", "numberOnly", "unique", "singleOnly"),
        // "npwp" => array("required", "unique", "singleOnly"),
        //        "nik" => array("required"),
        "status" => array("required"),
        // "image_ktp" => array("image"),
        // "image_npwp" => array("image"),
        // "country" => array("required"),

        //        "kategori_id" => array("required"),
    );

    protected $unionPairs = array(// "no_ktp", "npwp"
    );

    protected $listedFieldsSelectItem = array(//===kolom2 yang dibaca saat searching. silahkan di-override di model masing2 jika kolomnya kurang
        "id"             => "id",
        "nama"           => "nama",
        "nama_depan"     => "nama_depan",
        "nama_belakang"  => "nama_belakang",
        "member_id"      => "member_id",
        "tlp_1"          => "tlp_1",
        "no_ktp"         => "no_ktp",
        "npwp"           => "npwp",
        "contact_person" => "contact_person",
        "alamat_1"       => "alamat_1",
        "propinsi"       => "propinsi",
        "kabupaten"      => "kabupaten",
    );
    function __construct()
    {
        parent::__construct();
        $this->tbl_request = "per_customers_register";
    }
    public function getListedFieldsSelectItem()
    {
        return $this->listedFieldsSelectItem;
    }

    public function setListedFieldsSelectItem($listedFieldsSelectItem)
    {
        $this->listedFieldsSelectItem = $listedFieldsSelectItem;
    }

    public function getUnionPairs()
    {
        return $this->unionPairs;
    }

    public function setUnionPairs($unionPairs)
    {
        $this->unionPairs = $unionPairs;
    }

    protected $listedFieldsView = array("nama", "tlp_1", "npwp");

    protected $fields = array(
        "kategori_id"  => array(
            "label"     => "kategori konsumen",
            "type"      => "int",
            "length"    => "3",
            "kolom"     => "kategori_id",
            "reference" => "MdlCustomerTipe",
            //            "defaultValue" => "ID",
            "inputType" => "combo",
            //--"inputName" => "alamat",
        ),
        "id"           => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "dc_id"    => array(
            "label"     => "dc ID",
            "type"      => "varchar",
            "length"    => "50",
            "kolom"     => "dc_id",
            "inputType" => "text",
            "show" => false,
            // "inputType" => "hidden_show",
            //--"inputName" => "id",
        ),
        "member_id"    => array(
            "label"     => "member ID",
            "type"      => "varchar",
            "length"    => "50",
            "kolom"     => "member_id",
            "inputType" => "hidden",
            //--"inputName" => "id",
        ),
        "name"         => array(
            "label"     => "nama",
            "type"      => "int",
            "length"    => "255",
            "kolom"     => "nama",
            "inputType" => "text",
            "urlTrigger"   => "statik/Data/cekMasterData/Customer",
            // "eventTrigger" => "onblur=\"cek_master_data(this.value,this.id,this.getAttribute('data-link'),this.name);\" onclick=\"cek_master_data(this.value,this.id,this.getAttribute('data-link'),this.name);\"",
            "eventTrigger" => "onkeyup=\"debounceSearch(encodeURI(this.value),this.id,this.getAttribute('data-link'),this.name);\" onclick=\"cek_master_data(encodeURI(this.value),this.id,this.getAttribute('data-link'),this.name);\"",
            //--"inputName" => "nama",
        ),
        // "first_name"   => array(
        //     "label"     => "nama depan",
        //     "type"      => "int",
        //     "length"    => "255",
        //     "kolom"     => "nama_depan",
        //     "inputType" => "text",
        //     //--"inputName" => "nama_depan",
        // ),
        // "last_name"    => array(
        //     "label"     => "nama belakang",
        //     // "type"      => "int",
        //     "length"    => "255",
        //     "kolom"     => "nama_belakang",
        //     "inputType" => "text",
        //     //--"inputName" => "nama_belakang",
        // ),
        "login_name"   => array(
            "label"     => "login ID",
            "type"      => "int",
            "length"    => "24",
            "kolom"     => "nama_login",
            "inputType" => "hidden_text",
        ),
        "alamat_2"     => array(
            "label"     => "api",
            "type"      => "int",
            "length"    => "24",
            "kolom"     => "alamat_2",
            "inputType" => "hidden_text",
        ),
        "email"        => array(
            "label"     => "email",
            "type"      => "varchar", "length" => "45", "kolom" => "email",
            "inputType" => "text",
            //--"inputName" => "email",
        ),
        "phone"        => array(
            "label"     => "Nomor Telepon",
            "type"      => "varchar",
            "length"    => "45",
            "kolom"     => "tlp_1",
            "inputType" => "text",
            //--"inputName" => "telp",
        ),
        "alamat"       => array(
            "label"     => "Alamat",
            "type"      => "int",
            "length"    => "255",
            "kolom"     => "alamat_1",
            "inputType" => "text",
            //--"inputName" => "alamat",
        ),
        "alamat2"      => array(
            "label"     => "Jalan",
            "type"      => "int", "length" => "255",
            "kolom"     => "alamat_2",
            "inputType" => "text",
            //--"inputName" => "alamat",
        ),
        // "blok"         => array(
        //     "label"     => "Blok",
        //     "type"      => "int",
        //     "length"    => "255",
        //     "kolom"     => "blok",
        //     "inputType" => "text",
        //     //--"inputName" => "alamat",
        // ),
        // "nomer"        => array(
        //     "label"     => "nomer",
        //     "type"      => "int",
        //     "length"    => "255",
        //     "kolom"     => "nomer",
        //     "inputType" => "text",
        //     //--"inputName" => "alamat",
        // ),
        // "rt"           => array(
        //     "label"     => "RT",
        //     "type"      => "int",
        //     "length"    => "255",
        //     "kolom"     => "rt",
        //     "inputType" => "text",
        //     //--"inputName" => "alamat",
        // ),
        // "rw"           => array(
        //     "label"     => "RW",
        //     "type"      => "int",
        //     "length"    => "255",
        //     "kolom"     => "rw",
        //     "inputType" => "text",
        //     //--"inputName" => "alamat",
        // ),
        "kalurahan"    => array(
            "label"           => "kalurahan",
            "type"            => "int",
            "length"          => "255",
            "kolom"           => "kelurahan",
            "inputType"       => "text",
            // "reference"       => "MdlPostalCodes",
            // "referenceMetode" => "getKelurahan",
            // "referenceData"   => array(
            //     "nama" => "kelurahan"
            // )
        ),
        "kecamatan"    => array(
            "label"     => "kecamatan",
            "type"      => "int",
            "length"    => "255",
            "kolom"     => "kecamatan",
            "inputType" => "text",
        ),
        "kabupaten"    => array(
            "label"           => "kabupaten/Kota",
            "type"            => "int",
            "length"          => "255",
            "kolom"           => "kabupaten",
            "inputType"       => "text",
            // "reference"       => "MdlPostalCodes",
            // "referenceMetode" => "getKabupaten",
            // "referenceData"   => array(
            //     "nama" => "kabupaten"
            // )
        ),
        "propinsi"     => array(
            "label"            => "propinsi",
            "type"             => "int", "length" =>
                "255", "kolom" => "propinsi",
            "inputType"        => "text",
            // "reference"        => "MdlPostalCodes",
            // "referenceMetode"  => "getPropinsi",
            // "referenceData"    => array(
            //     "nama" => "propinsi",
            //     "id"   => "propinsi",
            // )
        ),
        "country"      => array(
            "label"        => "negara",
            "type"         => "varchar", "length" => "3", "kolom" => "country",
            // "reference"    => "MdlCountry",
            "defaultValue" => "ID",
            "inputType"    => "text",
            //--"inputName" => "alamat",
        ),
        "nik"          => array(
            "label"      => "nik",
            "type"       => "int", "length" => "255", "kolom" => "no_ktp",
            "inputType"  => "text",
            //--"inputName" => "nik",
            "keterangan" => "isikan salah satu antara NIK atau NPWP.",
        ),
        "image_ktp"    => array(
            "label"     => "ID card image",
            "type"      => "image", "length" => "", "kolom" => "image_ktp",
            "inputType" => "image",
        ),
        "npwp"         => array(
            "label"      => "npwp",
            "type"       => "int", "length" => "255", "kolom" => "npwp",
            "inputType"  => "text",
            //--"inputName" => "npwp",
            "keterangan" => "isikan salah satu antara NIK atau NPWP.",
        ),
        "image_npwp"   => array(
            "label"     => "npwp image",
            "type"      => "image", "length" => "", "kolom" => "image_npwp",
            "inputType" => "image",
        ),
        //        "due time" => array(
        //            "label" => "due (in seconds)",
        //            "type" => "int", "length" => "24", "kolom" => "jatuh_tempo",
        //            "inputType" => "text",
        //            //--"inputName" => "jatuh_tempo",
        //        ),
        "jatuh_tempo"  => array(
            "label"     => "lama kredit (hari)",
            "type"      => "int",
            "length"    => "24",
            "kolom"     => "due_days",
            "inputType" => "text",
            //--"inputName" => "jatuh_tempo",
        ),
        "credit_limit" => array(
            "label"     => "kredit limit",
            "type"      => "int",
            "length"    => "24",
            "kolom"     => "kredit_limit",
            "inputType" => "text",
            //--"inputName" => "kredit_limit",
        ),
        "diskon"       => array(
            "label"     => "discount (%)",
            "type"      => "int", "length" => "24", "kolom" => "diskon",
            "inputType" => "number",
            //--"inputName" => "diskon",
        ),
        "attn"         => array(
            "label"     => "CP",
            "type"      => "int", "length" => "255", "kolom" => "contact_person",
            "inputType" => "text",
            //--"inputName" => "person_nama",
        ),
        //        "trash" => array(
        //            "label" => "trash",
        //            "type" =>"int","length"=>"24","kolom" => "trash",
        //            "inputType" => "int",
        //            //--"inputName" => "trash",
        //        ),
        "status"       => array(
            "label"      => "status",
            "type"       => "int", "length" => "24", "kolom" => "status",
            "inputType"  => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "status",
        ),
        //        "ppn" => array(
        //            "label" => "VAT factor (%)",
        //            "type" =>"int","length"=>"24","kolom" => "ppn",
        //            "inputType" => "number",
        //            //--"inputName" => "ppn",
        //        ),
        //---------------
        //        "kategori_id" => array(
        //            "label" => "Customer Type",
        //            "type" => "varchar",
        //            "length" => "255",
        //            "kolom" => "kategori_id",
        //            "reference" => "MdlCustomerTipe",
        ////            "defaultValue" => "1",
        //            "inputType" => "combo",
        //            "strField" => "nama",
        //            "editable" => true,
        //            "kolom_nama" => "kategori_nama",
        //        ),
    );

    protected $listedFields = array(
        "kategori_nama" => "kategori konsumen",
        "nama"          => "name",
        //        "member_id" => "member_id",
        "email"         => "email",
        "tlp_1"         => "nomer telepon",
        "no_ktp"        => "nik",
        "npwp"          => "npwp",
        "diskon"        => "diskon(%)",
        "kredit_limit"  => "kredit_limit",
        "alamat_1"      => "alamat",
        "alamat_2"      => "jalan",
        "blok"          => "blok",
        "nomer"         => "nomer",
        "rt"            => "RT",
        "rw"            => "RW",
        "kelurahan"     => "desa",
        "kecamatan"     => "kecamatan",
        "kabupaten"     => "kabupaten",
        "propinsi"      => "propinsi",
        "country"       => "negara",
        "image_npwp"    => "image npwp",
        "image_ktp"     => "image ktp",
    );

    protected $pairValidate = array("nama");

    public function getPairValidate()
    {
        return $this->pairValidate;
    }

    public function setPairValidate($pairValidate)
    {
        $this->pairValidate = $pairValidate;
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

    // public function callSpecs($produkIds = "")
    // {
    //     $selecteds = array(
    //         "id",
    //         // "kode",
    //         "nama",
    //         // "employee_type",
    //         // "folders_nama",
    //         // "barcode",
    //         // "no_part",
    //         // // "merek_nama",
    //         // // "model_nama",
    //         // // "type_nama",
    //         // // "tahun",
    //         // // "lokasi_nama",
    //         // "satuan",
    //         // "diskon_persen",
    //         // "premi_beli",
    //         // "diskon_beli",
    //         // "biaya_beli",
    //         // "premi_jual",
    //         // "harga_jual",
    //         // "biaya_jual",
    //         // "limit",
    //         // "limit_time",
    //         // "lead_time",
    //         // "indeks",
    //         // "moq",
    //         // "moq_time",
    //     );
    //     $this->db->select($selecteds);
    //
    //     // if (isset($produkIds)) {
    //     if (is_array($produkIds)) {
    //         $this->db->where_in("id", $produkIds);
    //     }
    //     else {
    //         if ($produkIds > 0) {
    //             $this->db->where("id", $produkIds);
    //         }
    //     }
    //     // $this->db->where("toko_id",my_toko_id());
    //     $vars_0 = $this->lookupAll()->result();
    //     // showLast_query("orange");
    //     $vars = array();
    //     if (sizeof($vars_0) > 0) {
    //         foreach ($vars_0 as $item) {
    //             $vars[$item->id] = $item;
    //         }
    //     }
    //
    //
    //     return $vars;
    // }

    public function paramSyncNamaNama()
    {
        $mdls = array(
            "MdlCustomerTipe" => array(
                "id"         => "kategori_id",
                "kolomDatas" => array(
                    "nama" => "kategori_nama",
                ),
            ),

        );

        return $mdls;

    }

    protected $navFilters = array(
        "label"     => "kategori",
        "mdlFilter" => "MdlCustomerTipe",
        // "mdlFilter" => "MdlProdukKategori",
        "kolomKey"  => "kategori_id",
    );

    public function getNavFilters()
    {
        return $this->navFilters;
    }

    public function setNavFilters($navFilters)
    {
        $this->navFilters = $navFilters;
    }

    /*----------------------------------------------------------------
 * auto penambahan COA, bisa dugunakan keperluan lain
 * konnecting ke model yg lain
 * ----------------------------------------------------------*/
    protected $connectingData = array(
        "MdlAccounts" => array(
            "path"          => "Mdls",
            "fungsi"        => "addExtern_coa",
            /* ------------------- ------------------- -------------------
             * staticOptions bisa handling array atau singgle
             * ---------------------------------------------------------*/
            // "staticOptions" => array("1010020010"), // yg mana dipakai?
            "staticOptions" => "1010020010",
            "fields"        => array(
                "extern_jenis"         => array(
                    "str" => "customer",
                ),
                "extern_id"            => array(
                    "var_main" => "mainInsertId",
                ),
                "rekening"             => array(
                    "var_main" => "mainInsertId",
                ),
                "head_name"            => array(
                    "var_main" => "nama",
                ),
                "p_head_name"          => array(
                    "var_main" => "strHead_code",
                ),
                "create_by"            => array(
                    "var_main" => "my_name",
                ),
                /* -------------------------------------------------
                 * filter yg ingin langsung diaktifkan
                 * -------------------------------------------------*/
                "is_active"            => array(
                    "str" => "1",
                ),
                "is_transaction"       => array(
                    "str" => "1",
                ),
                "is_rekening_pembantu" => array(
                    "str" => "1",
                ),
                // "is_hutang" => array(
                //     "str" => "1",
                // ),
                // "is_gl" => array(
                //     "str" => "1",
                // ),
            ),
            "updateMain"    => array(
                "condites" => array(
                    "id" => "mainInsertId",
                ),
                "datas"    => array(
                    "coa_code" => "lastInset_code",
                )
            )
        )
    );

    //
    public function getConnectingData()
    {
        return $this->connectingData;
    }

    public function setConnectingData($connectingData)
    {
        $this->connectingData = $connectingData;
    }

    // protected $pairedData = array(
    //     "MdlImages" => array(
    //         // "kolom"       => array(
    //         //     "files"         => "images",
    //         //     ),
    //         "kolom" => "image",
    //         "default_nilai" => 0,
    //         "label" => "image",
    //         "link" => "image",
    //         "methode" => "callSpecs",
    //         "methode_key" => "files",
    //     ),
    // );
    //
    // public function getPairedData()
    // {
    //     return $this->pairedData;
    // }
    //
    // public function setPairedData($pairedData)
    // {
    //     $this->pairedData = $pairedData;
    // }

    protected $innerJoint = array(
        "tabel_1" => array(
            "tbl"    => "postal_codes",
            "select" => array(
                "propinsi",
                "kabupaten",
                "kecamatan",
                "kelurahan"
            ),
            "on"     => array(
                "propinsi"  => "propinsi_id",
                "kabupaten" => "kabupaten_id",
                "kecamatan" => "kecamatan_id",
                "kelurahan" => "kelurahan_id",
            )
        ),
    );

    public function getApiData(){
        return 1;
    }

    /* ------------------------------------------------------------
* over write link add new data
* ------------------------------------------------------------*/
    public function linkAddData()
    {
        return "Data/addEmployee/" . substr(get_class(), 3);
    }

    public function getRequestCustomerJoin($id = null)
    {
        $tbl1 = $this->tbl_request;
        $tbl2 = "postal_codes";

        $propinsiNormalized  = "NULLIF(NULLIF($tbl1.propinsi, ''), 0)";
        $kabupatenNormalized = "NULLIF(NULLIF($tbl1.kabupaten, ''), 0)";

        $this->db->select("
        $tbl1.*,
        $tbl1.propinsi AS propinsi_id_raw,
        $tbl1.kabupaten AS kabupaten_id_raw,
        $propinsiNormalized AS propinsi_valid,
        $kabupatenNormalized AS kabupaten_valid,
        p.propinsi AS propinsi_nama,
        k.kabupaten AS kabupaten_nama
    ");

        $this->db->from($tbl1);

        // JOIN Propinsi
        $this->db->join(
            "$tbl2 AS p",
            "$propinsiNormalized = p.propinsi_id",
            "left"
        );

        // JOIN Kabupaten
        $this->db->join(
            "$tbl2 AS k",
            "$kabupatenNormalized = k.kabupaten_id
         AND $propinsiNormalized = k.propinsi_id",
            "left"
        );

        $this->db->where("$tbl1.trash", 0);

        if ($id) {
            $this->db->where("$tbl1.id", $id);
        }

        // ✔️ GROUP BY tabel 1 (primary key)
        $this->db->group_by("$tbl1.id");

        return $this->db->get();
    }

    public function getRequestCustomer()
    {
        $tbl1 = $this->tbl_request;
        $this->db->where("$tbl1.trash", "0");
        $this->db->select("*");
        $this->db->from($tbl1);
        $query = $this->db->get();
        return $query;
    }

    public function getRequestCustomerAll($referensi_id)
    {
        //baca kolom referensi_id yang merupakan representasi dari id lead/prospek konsumen di CRM
        $tbl1 = $this->tbl_request;
        $this->db->where("$tbl1.referensi_id", $referensi_id);
        $this->db->select("*");
        $this->db->from($tbl1);
        $query = $this->db->get();

        return $query;
    }

    /**
     * Insert data customer request ke tabel per_customers_register
     * @param array $data Data yang akan diinsert
     * @return int Insert ID atau FALSE jika gagal
     */
    public function addRequest($data)
    {
        if (empty($data) || !is_array($data)) {
            return false;
        }

        $table = $this->tbl_request;
        $data = $this->applyRequestDefaults($table, $data);
        $data = $this->filterColumnsByTable($table, $data);

        // Insert ke tabel request
        $this->db->insert($table, $data);
        $error = $this->db->error();
        if (isset($error["code"]) && (int)$error["code"] !== 0) {
            log_message(
                "error",
                "[MdlCustomer::addRequest] gagal insert request customer. code="
                . $error["code"] . ", msg=" . $error["message"]
                . ", referensi_id=" . (isset($data["referensi_id"]) ? $data["referensi_id"] : "0")
            );
            return false;
        }

        // Return insert ID
        return $this->db->insert_id();
    }

    /**
     * Lengkapi default field wajib agar insert request customer tidak gagal
     * di variasi schema legacy antar environment.
     *
     * @param string $table
     * @param array $data
     * @return array
     */
    private function applyRequestDefaults($table, $data)
    {
        $data = is_array($data) ? $data : array();
        $columns = $this->getTableColumnsCached($table);
        if (count($columns) < 1) {
            return $data;
        }

        $defaults = array(
            "access_jml" => 0,
            "avatars" => "",
            "parent" => 0,
            "childs" => 0,
            "status" => 0,
            "trash" => 0,
            "login_fail" => 0,
            "npwp" => "0",
            "author_id" => 0,
            "author_nama" => "",
            "cabang_id" => 0,
            "cabang_nama" => "0",
            "diskon" => 0,
            "jatuh_tempo" => 0,
            "def_pembayaran" => 0,
            "dc_id" => 0,
            "referensi_id" => 0,
        );

        foreach ($defaults as $key => $val) {
            if (!in_array($key, $columns, true)) {
                continue;
            }
            if (!isset($data[$key]) || $data[$key] === null || $data[$key] === "") {
                $data[$key] = $val;
            }
        }

        if (in_array("dtime", $columns, true) && (!isset($data["dtime"]) || $data["dtime"] === "" || $data["dtime"] === null)) {
            $data["dtime"] = date("Y-m-d H:i:s");
        }

        return $data;
    }

        /**
         * Insert data customer ke tabel per_customers
         * @param array $data Data yang akan diinsert
         * @return int Insert ID atau FALSE jika gagal
         */
    public function addCustomer($data)
    {
        if (empty($data) || !is_array($data)) {
            return 'Data kosong atau bukan array';
        }

        // Insert to per_pihak_lain (master customer/supplier table)
        $this->db->insert($this->tableName, $data);
        $error = $this->db->error();
        if ($error['code'] != 0) {
            return 'DB Error: ' . $error['message'];
        }
        return $this->db->insert_id();
    }

    public function getCustomerDC($dc_id){
        $criteria = array("dc_id" => $dc_id);
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $criteria + $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
//        $this->db->where($criteria);
        return $this->db->get($this->tableName);
    }
    /**
     * Mengambil batch workload sinkronisasi customer dari data lokal.
     * Workload hanya customer aktif yang memiliki dc_id.
     */
    public function fetchApiCustomersBatch($options = array(), $limit = 50, $cursor = "", $probeOnly = false)
    {
        if (!$this->db->table_exists($this->tableName)) {
            return array(
                "status" => false,
                "message" => "Tabel customer tidak ditemukan: " . $this->tableName,
                "rows" => array(),
                "total_filtered" => 0,
                "has_more" => false,
                "next_cursor" => "",
                "sourceLabel" => "per_pihak_lain_dc_id",
                "debug_url" => "local://per_pihak_lain?missing_table=1",
                "debug_domain" => defined("ADM_DOMAIN") ? ADM_DOMAIN : "",
            );
        }

        $syncOptions = $this->normalizeCustomerSyncOptions($options);

        $limit = is_numeric($limit) ? (int)$limit : 50;
        if ($limit < 1) {
            $limit = 50;
        }
        if ($limit > 200) {
            $limit = 200;
        }

        $offset = $this->extractSyncOffsetCursor($cursor);

        $this->buildCustomerSyncWorkloadQuery($syncOptions, true);
        $totalFiltered = (int)$this->db->count_all_results();

        $rows = array();
        if ($totalFiltered > 0) {
            $this->buildCustomerSyncWorkloadQuery($syncOptions, false);
            $this->db->order_by("id", "ASC");
            $this->db->limit($limit, $offset);
            $rows = $this->db->get()->result_array();
        }

        $processedCount = is_array($rows) ? count($rows) : 0;
        $nextOffset = $offset + $processedCount;
        $hasMore = $nextOffset < $totalFiltered;

        return array(
            "status" => true,
            "message" => "Workload customer lokal berhasil dibaca",
            "rows" => $rows,
            "total_filtered" => $totalFiltered,
            "has_more" => $hasMore,
            "next_cursor" => $hasMore ? $this->buildSyncOffsetCursor($nextOffset) : "",
            "sourceLabel" => "per_pihak_lain_dc_id",
            "debug_url" => "local://per_pihak_lain?offset=$offset&limit=$limit",
            "debug_domain" => defined("ADM_DOMAIN") ? ADM_DOMAIN : "",
            "probe_only" => $probeOnly ? 1 : 0,
        );
    }

    /**
     * Memproses satu chunk workload customer.
     * Untuk setiap customer, ambil data detail dari API DC lalu upsert customer + address.
     */
    public function processApiCustomerChunk($chunk, $options = array())
    {
        if (!$this->db->table_exists($this->tableName)) {
            return array(
                "status" => false,
                "message" => "Tabel customer tidak ditemukan: " . $this->tableName,
                "inserted" => 0,
                "updated" => 0,
                "skipped" => 0,
                "failed" => 0,
            );
        }

        if (!is_array($chunk)) {
            return array(
                "status" => false,
                "message" => "Chunk sinkronisasi tidak valid",
                "inserted" => 0,
                "updated" => 0,
                "skipped" => 0,
                "failed" => 0,
            );
        }

        $summary = array(
            "status" => true,
            "message" => "Chunk sinkronisasi customer berhasil diproses",
            "inserted" => 0,
            "updated" => 0,
            "skipped" => 0,
            "failed" => 0,
            "customer_updated" => 0,
            "address_inserted" => 0,
            "address_updated" => 0,
            "address_deactivated" => 0,
            "missing_dc_count" => 0,
            "missing_dc_customers" => array(),
            "errors" => array(),
        );

        $backfillOnce = $this->maybeRunCustomerAddressBackfillOnce();
        if (!isset($backfillOnce["status"]) || $backfillOnce["status"] !== true) {
            return array(
                "status" => false,
                "message" => isset($backfillOnce["message"]) ? $backfillOnce["message"] : "Gagal migrasi one-time dc_id_address",
                "inserted" => 0,
                "updated" => 0,
                "skipped" => 0,
                "failed" => 0,
                "customer_updated" => 0,
                "address_inserted" => 0,
                "address_updated" => 0,
                "address_deactivated" => 0,
                "missing_dc_count" => 0,
                "missing_dc_customers" => array(),
                "errors" => array(),
            );
        }
        $summary["address_backfill_once_executed"] = !empty($backfillOnce["executed"]);
        if (isset($backfillOnce["detail"]) && is_array($backfillOnce["detail"])) {
            $summary["address_backfill_once_detail"] = $backfillOnce["detail"];
        }

        foreach ($chunk as $row) {
            $rowArr = is_array($row) ? $row : (array)$row;
            $localId = isset($rowArr["id"]) && is_numeric($rowArr["id"]) ? (int)$rowArr["id"] : 0;
            $dcId = isset($rowArr["dc_id"]) ? trim((string)$rowArr["dc_id"]) : "";
            $customerName = $this->resolveSyncCustomerName($rowArr, array(), $localId, $dcId);

            if ($dcId === "" || $dcId === "0") {
                $summary["skipped"]++;
                $summary["missing_dc_count"]++;
                $nama = $customerName;
                if (!in_array($nama, $summary["missing_dc_customers"], true)) {
                    $summary["missing_dc_customers"][] = $nama;
                }
                continue;
            }

            $remote = $this->fetchCustomerDetailByDcId($dcId);
            if (!isset($remote["status"]) || $remote["status"] !== true) {
                $summary["failed"]++;
                $summary["errors"][] = array(
                    "customer_name" => $customerName,
                    "dc_id" => $dcId,
                    "stage" => "fetch_remote",
                    "message" => isset($remote["message"]) ? $remote["message"] : "Gagal mengambil detail customer dari API",
                );
                continue;
            }

            $customerName = $this->resolveSyncCustomerName(
                $rowArr,
                isset($remote["customer"]) && is_array($remote["customer"]) ? $remote["customer"] : array(),
                $localId,
                $dcId
            );

            $this->db->trans_begin();

            $upsert = $this->upsertCustomerFromApiData(
                isset($remote["customer"]) && is_array($remote["customer"]) ? $remote["customer"] : array(),
                $dcId,
                $localId
            );

            if (!isset($upsert["status"]) || $upsert["status"] !== true) {
                $this->db->trans_rollback();
                $summary["failed"]++;
                $summary["errors"][] = array(
                    "customer_name" => $customerName,
                    "dc_id" => $dcId,
                    "stage" => "upsert_customer",
                    "message" => isset($upsert["message"]) ? $upsert["message"] : "Gagal menyimpan customer",
                );
                continue;
            }

            $customerId = isset($upsert["customer_id"]) ? (int)$upsert["customer_id"] : 0;
            if ($customerId < 1) {
                $this->db->trans_rollback();
                $summary["failed"]++;
                $summary["errors"][] = array(
                    "customer_name" => $customerName,
                    "dc_id" => $dcId,
                    "stage" => "upsert_customer",
                    "message" => "Customer ID tidak valid setelah upsert",
                );
                continue;
            }

            $addressSync = $this->syncCustomerAddressesFromRemote($customerId, $remote);
            if (!isset($addressSync["status"]) || $addressSync["status"] !== true) {
                $this->db->trans_rollback();
                $summary["failed"]++;
                $summary["errors"][] = array(
                    "customer_name" => $customerName,
                    "dc_id" => $dcId,
                    "stage" => "sync_address",
                    "message" => isset($addressSync["message"]) ? $addressSync["message"] : "Gagal sinkronisasi alamat customer",
                );
                continue;
            }

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                $summary["failed"]++;
                $summary["errors"][] = array(
                    "customer_name" => $customerName,
                    "dc_id" => $dcId,
                    "stage" => "transaction",
                    "message" => "Transaksi database gagal",
                );
                continue;
            }

            $this->db->trans_commit();
            if ($this->db->trans_status() === false) {
                $summary["failed"]++;
                $summary["errors"][] = array(
                    "customer_name" => $customerName,
                    "dc_id" => $dcId,
                    "stage" => "transaction_commit",
                    "message" => "Commit transaksi database gagal",
                );
                continue;
            }
            $summary["address_inserted"] += isset($addressSync["inserted"]) ? (int)$addressSync["inserted"] : 0;
            $summary["address_updated"] += isset($addressSync["updated"]) ? (int)$addressSync["updated"] : 0;
            $summary["address_deactivated"] += isset($addressSync["deactivated"]) ? (int)$addressSync["deactivated"] : 0;

            $customerChanged = isset($upsert["changed"]) ? (bool)$upsert["changed"] : false;
            $addressChanged = isset($addressSync["changed"]) ? (bool)$addressSync["changed"] : false;
            $customerAction = isset($upsert["action"]) ? $upsert["action"] : "skip";

            if ($customerAction === "insert") {
                $summary["inserted"]++;
            }
            elseif ($customerChanged || $addressChanged) {
                $summary["updated"]++;
                if ($customerChanged) {
                    $summary["customer_updated"]++;
                }
            }
            else {
                $summary["skipped"]++;
            }
        }

        if (count($summary["errors"]) > 0) {
            $summary["message"] = "Chunk selesai dengan sebagian error";
        }

        return $summary;
    }

    private function resolveSyncCustomerName($localRow, $remoteCustomer = array(), $localId = 0, $dcId = "")
    {
        $localRow = is_array($localRow) ? $localRow : array();
        $remoteCustomer = is_array($remoteCustomer) ? $remoteCustomer : array();
        $dcId = trim((string)$dcId);

        $candidate = $this->pickFirstNonEmptyValue($localRow, array("nama", "name", "customer_name"));
        if ($candidate === null || trim((string)$candidate) === "") {
            $candidate = $this->pickFirstNonEmptyValue($remoteCustomer, array("nama", "name", "customer_name"));
        }
        if ($candidate !== null && trim((string)$candidate) !== "") {
            return trim((string)$candidate);
        }

        return "Customer tanpa nama";
    }

    private function normalizeCustomerSyncOptions($options)
    {
        $syncOptions = is_array($options) ? $options : array();
        return array(
            "id" => isset($syncOptions["id"]) ? trim((string)$syncOptions["id"]) : "",
            "q" => isset($syncOptions["q"]) ? trim((string)$syncOptions["q"]) : "",
            "dc_id" => isset($syncOptions["dc_id"]) ? trim((string)$syncOptions["dc_id"]) : "",
        );
    }

    private function extractSyncOffsetCursor($cursor)
    {
        if (!is_string($cursor) || $cursor === "") {
            return 0;
        }
        if (is_numeric($cursor)) {
            $offset = (int)$cursor;
            return $offset > 0 ? $offset : 0;
        }
        if (strpos($cursor, "o:") === 0) {
            $num = substr($cursor, 2);
            if (is_numeric($num)) {
                $offset = (int)$num;
                return $offset > 0 ? $offset : 0;
            }
        }
        return 0;
    }

    private function buildSyncOffsetCursor($offset)
    {
        $offset = is_numeric($offset) ? (int)$offset : 0;
        if ($offset < 0) {
            $offset = 0;
        }
        return "o:" . $offset;
    }

    private function buildCustomerSyncWorkloadQuery($options, $countOnly = false)
    {
        $columns = $this->getTableColumnsCached($this->tableName);
        $this->db->from($this->tableName);
        if (!$countOnly) {
            $selectCols = array();
            foreach (array("id", "dc_id", "nama") as $col) {
                if (in_array($col, $columns, true)) {
                    $selectCols[] = $col;
                }
            }
            if (count($selectCols) < 1) {
                $selectCols[] = "*";
            }
            $this->db->select(implode(",", $selectCols));
        }

        if (!in_array("dc_id", $columns, true)) {
            $this->db->where("1=0", null, false);
            return;
        }

        if (in_array("is_customer", $columns, true)) {
            $this->db->where("is_customer", "1");
        }
        if (in_array("trash", $columns, true)) {
            $this->db->where("trash", "0");
        }
        $this->db->where("dc_id IS NOT NULL", null, false);
        $this->db->where("TRIM(dc_id) <> ''", null, false);

        if (in_array("id", $columns, true) && isset($options["id"]) && $options["id"] !== "" && is_numeric($options["id"])) {
            $this->db->where("id", (int)$options["id"]);
        }

        if (isset($options["dc_id"]) && $options["dc_id"] !== "") {
            $this->db->where("dc_id", $options["dc_id"]);
        }

        if (isset($options["q"]) && $options["q"] !== "") {
            $searchCols = array();
            foreach (array("nama", "dc_id", "member_id", "email", "tlp_1") as $col) {
                if (in_array($col, $columns, true)) {
                    $searchCols[] = $col;
                }
            }
            if (count($searchCols) < 1) {
                return;
            }
            $q = $this->db->escape_like_str($options["q"]);
            $parts = array();
            foreach ($searchCols as $col) {
                $parts[] = $col . " LIKE '%$q%'";
            }
            $this->db->where("(" . implode(" OR ", $parts) . ")", null, false);
        }
    }

    private function fetchCustomerDetailByDcId($dcId)
    {
        $dcId = trim((string)$dcId);
        if ($dcId === "") {
            return array(
                "status" => false,
                "message" => "dc_id kosong",
            );
        }

        $domain = defined("ADM_DOMAIN") ? rtrim(ADM_DOMAIN, "/") . "/" : "";
        $url = $domain . "eusvc/Customers/seeItemAll/id/" . rawurlencode($dcId);

        $response = null;
        if (function_exists("call_curl")) {
            $response = call_curl($url);
        }
        else {
            $raw = @file_get_contents($url);
            $decoded = json_decode($raw, true);
            $response = is_array($decoded) ? $decoded : array();
        }

        $parsed = $this->extractCustomerDetailPayload($response);
        if (!isset($parsed["status"]) || $parsed["status"] !== true) {
            $parsed["debug_url"] = $url;
            return $parsed;
}
        $parsed["debug_url"] = $url;
        return $parsed;
    }

    private function extractCustomerDetailPayload($response)
    {
        if (!is_array($response)) {
            return array(
                "status" => false,
                "message" => "Respons API customer tidak valid",
            );
        }

        $customer = array();
        if (isset($response["data"]) && is_array($response["data"])) {
            if ($this->isAssocArray($response["data"])) {
                $customer = $response["data"];
            }
            elseif (isset($response["data"][0]) && is_array($response["data"][0])) {
                $customer = $response["data"][0];
            }
        }
        if (empty($customer) && isset($response["customer"]) && is_array($response["customer"])) {
            $customer = $response["customer"];
        }
        if (empty($customer) && isset($response[0]) && is_array($response[0])) {
            $customer = $response[0];
        }

        if (empty($customer)) {
            return array(
                "status" => false,
                "message" => "Data customer tidak ditemukan pada respons API",
            );
        }

        $hasShipmentKey = false;
        $hasBillingKey = false;

        $shipmentRaw = array();
        if (array_key_exists("address", $customer)) {
            $shipmentRaw = $customer["address"];
            $hasShipmentKey = true;
        }
        elseif (array_key_exists("shipment", $customer)) {
            $shipmentRaw = $customer["shipment"];
            $hasShipmentKey = true;
        }
        elseif (array_key_exists("shipment", $response)) {
            $shipmentRaw = $response["shipment"];
            $hasShipmentKey = true;
        }
        elseif (array_key_exists("address", $response)) {
            $shipmentRaw = $response["address"];
            $hasShipmentKey = true;
        }

        $billingRaw = array();
        if (array_key_exists("billing", $customer)) {
            $billingRaw = $customer["billing"];
            $hasBillingKey = true;
        }
        elseif (array_key_exists("bill", $customer)) {
            $billingRaw = $customer["bill"];
            $hasBillingKey = true;
        }
        elseif (array_key_exists("bill", $response)) {
            $billingRaw = $response["bill"];
            $hasBillingKey = true;
        }
        elseif (array_key_exists("billing", $response)) {
            $billingRaw = $response["billing"];
            $hasBillingKey = true;
        }

        return array(
            "status" => true,
            "customer" => $customer,
            "shipment" => $this->normalizeAddressItems($shipmentRaw),
            "billing" => $this->normalizeAddressItems($billingRaw),
            "shipment_explicit" => $hasShipmentKey,
            "billing_explicit" => $hasBillingKey,
        );
    }

    private function normalizeAddressItems($raw)
    {
        if (!is_array($raw)) {
            return array();
        }
        if (count($raw) < 1) {
            return array();
        }
        if ($this->isAssocArray($raw)) {
            return array($raw);
        }

        $rows = array();
        foreach ($raw as $item) {
            if (is_array($item)) {
                $rows[] = $item;
            }
            elseif (is_object($item)) {
                $rows[] = (array)$item;
            }
        }
        return $rows;
    }

    private function upsertCustomerFromApiData($customerData, $dcId, $localId = 0)
    {
        $customerData = is_array($customerData) ? $customerData : array();
        $dcId = trim((string)$dcId);

        $current = null;
        if ($localId > 0) {
            $current = $this->db->where("id", $localId)->get($this->tableName)->row_array();
        }
        if (!$current && $dcId !== "") {
            $current = $this->db
                ->where("dc_id", $dcId)
                ->where("is_customer", "1")
                ->order_by("id", "ASC")
                ->limit(1)
                ->get($this->tableName)
                ->row_array();
        }

        $columns = $this->getTableColumnsCached($this->tableName);
        $payload = $this->buildCustomerPayloadFromApi($customerData, $dcId, $columns);

        if ($current) {
            $customerId = (int)$current["id"];
            $updateData = $this->filterChangedColumns($payload, $current);
            $hasCustomerChange = count($updateData) > 0;
            if ($hasCustomerChange && in_array("dtime_update", $columns, true)) {
                $updateData["dtime_update"] = date("Y-m-d H:i:s");
            }

            if (!$hasCustomerChange) {
                return array(
                    "status" => true,
                    "customer_id" => $customerId,
                    "action" => "update",
                    "changed" => false,
                );
            }

            $this->db->where("id", $customerId)->update($this->tableName, $updateData);
            $err = $this->db->error();
            if (isset($err["code"]) && (int)$err["code"] !== 0) {
                return array(
                    "status" => false,
                    "message" => "Gagal update customer: " . $err["message"],
                );
            }

            return array(
                "status" => true,
                "customer_id" => $customerId,
                "action" => "update",
                "changed" => true,
            );
        }

        if (in_array("dtime", $columns, true) && !isset($payload["dtime"])) {
            $payload["dtime"] = date("Y-m-d H:i:s");
        }
        if (in_array("status", $columns, true) && !isset($payload["status"])) {
            $payload["status"] = "1";
        }

        $this->db->insert($this->tableName, $payload);
        $err = $this->db->error();
        if (isset($err["code"]) && (int)$err["code"] !== 0) {
            return array(
                "status" => false,
                "message" => "Gagal insert customer: " . $err["message"],
            );
        }

        $customerId = (int)$this->db->insert_id();
        return array(
            "status" => true,
            "customer_id" => $customerId,
            "action" => "insert",
            "changed" => true,
        );
    }

    private function buildCustomerPayloadFromApi($customerData, $dcId, $columns)
    {
        $data = array();

        $nama = $this->pickFirstNonEmptyValue($customerData, array("nama", "name", "customer_name"));
        if ($nama !== null && in_array("nama", $columns, true)) {
            $data["nama"] = $nama;
        }

        $alamat = $this->pickFirstNonEmptyValue($customerData, array("alamat", "alamat_1", "address"));
        if ($alamat !== null && in_array("alamat_1", $columns, true)) {
            $data["alamat_1"] = $alamat;
        }

        $alamat2 = $this->pickFirstNonEmptyValue($customerData, array("alamat_2", "jalan"));
        if ($alamat2 !== null && in_array("alamat_2", $columns, true)) {
            $data["alamat_2"] = $alamat2;
        }

        $phone1 = $this->pickFirstNonEmptyValue($customerData, array("tlp_1", "phone", "telp", "tlp", "telp1"));
        if ($phone1 !== null && in_array("tlp_1", $columns, true)) {
            $data["tlp_1"] = $phone1;
        }

        $phone2 = $this->pickFirstNonEmptyValue($customerData, array("tlp_2", "phone2", "telp2", "tlp2"));
        if ($phone2 !== null && in_array("tlp_2", $columns, true)) {
            $data["tlp_2"] = $phone2;
        }

        $phone3 = $this->pickFirstNonEmptyValue($customerData, array("tlp_3", "phone3", "telp3", "tlp3"));
        if ($phone3 !== null && in_array("tlp_3", $columns, true)) {
            $data["tlp_3"] = $phone3;
        }

        $email = $this->pickFirstNonEmptyValue($customerData, array("email"));
        if ($email !== null && in_array("email", $columns, true)) {
            $data["email"] = $email;
        }

        $memberId = $this->pickFirstNonEmptyValue($customerData, array("member_id", "memberid"));
        if ($memberId !== null && in_array("member_id", $columns, true)) {
            $data["member_id"] = $memberId;
        }

        $propinsi = $this->pickFirstNonEmptyValue($customerData, array("propinsi", "provinsi", "provinsi_nama"));
        if ($propinsi !== null && in_array("propinsi", $columns, true)) {
            $data["propinsi"] = $propinsi;
        }

        $kabupaten = $this->pickFirstNonEmptyValue($customerData, array("kabupaten", "kota", "kabupaten_nama"));
        if ($kabupaten !== null && in_array("kabupaten", $columns, true)) {
            $data["kabupaten"] = $kabupaten;
        }

        $kecamatan = $this->pickFirstNonEmptyValue($customerData, array("kecamatan", "kecamatan_nama"));
        if ($kecamatan !== null && in_array("kecamatan", $columns, true)) {
            $data["kecamatan"] = $kecamatan;
        }

        $kelurahan = $this->pickFirstNonEmptyValue($customerData, array("kelurahan", "kelurahan_nama"));
        if ($kelurahan !== null && in_array("kelurahan", $columns, true)) {
            $data["kelurahan"] = $kelurahan;
        }

        $kodepos = $this->pickFirstNonEmptyValue($customerData, array("kode_pos", "kodepos", "postal_code"));
        if ($kodepos !== null) {
            if (in_array("kode_pos", $columns, true)) {
                $data["kode_pos"] = $kodepos;
            }
            elseif (in_array("kodepos", $columns, true)) {
                $data["kodepos"] = $kodepos;
            }
        }

        $npwp = $this->pickFirstNonEmptyValue($customerData, array("npwp"));
        if ($npwp !== null && in_array("npwp", $columns, true)) {
            $data["npwp"] = $npwp;
        }

        $nik = $this->pickFirstNonEmptyValue($customerData, array("nik", "no_ktp"));
        if ($nik !== null && in_array("no_ktp", $columns, true)) {
            $data["no_ktp"] = $nik;
        }

        if (in_array("dc_id", $columns, true) && $dcId !== "") {
            $data["dc_id"] = $dcId;
        }
        if (in_array("is_customer", $columns, true)) {
            $data["is_customer"] = "1";
        }
        if (in_array("trash", $columns, true)) {
            $data["trash"] = "0";
        }
        if (in_array("status", $columns, true)) {
            $data["status"] = "1";
        }

        return $data;
    }

    private function pickFirstNonEmptyValue($row, $keys)
    {
        if (!is_array($row)) {
            return null;
        }
        foreach ($keys as $key) {
            if (array_key_exists($key, $row)) {
                $val = is_string($row[$key]) ? trim($row[$key]) : $row[$key];
                if ($val !== "" && $val !== null) {
                    return $val;
                }
            }
        }
        return null;
    }

    private function syncCustomerAddressesFromRemote($customerId, $remotePayload)
    {
        if (!$this->db->table_exists("address")) {
            return array(
                "status" => false,
                "message" => "Tabel address tidak ditemukan",
            );
        }

        $schemaReady = $this->ensureAddressSyncSchema();
        if (!isset($schemaReady["status"]) || $schemaReady["status"] !== true) {
            return array(
                "status" => false,
                "message" => isset($schemaReady["message"]) ? $schemaReady["message"] : "Gagal menyiapkan skema sinkronisasi address",
            );
        }

        $columns = $this->getTableColumnsCached("address");

        $shipmentRows = isset($remotePayload["shipment"]) && is_array($remotePayload["shipment"]) ? $remotePayload["shipment"] : array();
        $billingRows = isset($remotePayload["billing"]) && is_array($remotePayload["billing"]) ? $remotePayload["billing"] : array();
        $shipmentExplicit = !empty($remotePayload["shipment_explicit"]);
        $billingExplicit = !empty($remotePayload["billing_explicit"]);

        $resultShipment = $this->syncAddressByJenis($customerId, "shipment", $shipmentRows, $shipmentExplicit, $columns);
        if (!isset($resultShipment["status"]) || $resultShipment["status"] !== true) {
            return $resultShipment;
        }

        $resultBilling = $this->syncAddressByJenis($customerId, "bill", $billingRows, $billingExplicit, $columns);
        if (!isset($resultBilling["status"]) || $resultBilling["status"] !== true) {
            return $resultBilling;
        }

        $inserted = (int)$resultShipment["inserted"] + (int)$resultBilling["inserted"];
        $updated = (int)$resultShipment["updated"] + (int)$resultBilling["updated"];
        $deactivated = (int)$resultShipment["deactivated"] + (int)$resultBilling["deactivated"];
        $changed = $inserted > 0 || $updated > 0 || $deactivated > 0;

        return array(
            "status" => true,
            "inserted" => $inserted,
            "updated" => $updated,
            "deactivated" => $deactivated,
            "changed" => $changed,
        );
    }

    private function syncAddressByJenis($customerId, $jenis, $rows, $explicit, $columns)
    {
        $rows = is_array($rows) ? $rows : array();

        $existingRows = $this->db
            ->where("extern_type", "customer")
            ->where("extern_id", $customerId)
            ->where("jenis", $jenis)
            ->get("address")
            ->result_array();

        $remoteKeyColumn = $this->resolveAddressRemoteKeyColumn($columns);
        $legacyRemoteKeyColumns = $this->resolveAddressLegacyRemoteKeyColumns($columns, $remoteKeyColumn);
        $existingByRemote = array();
        $existingByLegacyRemote = array();
        $existingByFingerprint = array();
        $existingById = array();

        foreach ($existingRows as $row) {
            if (!isset($row["id"])) {
                continue;
            }
            $id = (int)$row["id"];
            $existingById[$id] = $row;

            if ($remoteKeyColumn !== "" && isset($row[$remoteKeyColumn]) && trim((string)$row[$remoteKeyColumn]) !== "") {
                $existingByRemote[(string)$row[$remoteKeyColumn]] = $id;
            }
            foreach ($legacyRemoteKeyColumns as $legacyCol) {
                if (isset($row[$legacyCol]) && trim((string)$row[$legacyCol]) !== "") {
                    $existingByLegacyRemote[(string)$row[$legacyCol]] = $id;
                }
            }
            $finger = $this->buildAddressFingerprint($row);
            if ($finger !== "") {
                $existingByFingerprint[$finger] = $id;
            }
        }

        $matchedIds = array();
        $inserted = 0;
        $updated = 0;

        foreach ($rows as $raw) {
            $item = is_array($raw) ? $raw : (array)$raw;
            $mapped = $this->mapAddressPayloadFromApi($item, $customerId, $jenis, $columns, $remoteKeyColumn);

            $targetId = 0;
            $remoteKeyVal = isset($mapped["_remote_key"]) ? $mapped["_remote_key"] : "";
            if ($remoteKeyVal !== "" && isset($existingByRemote[$remoteKeyVal])) {
                $targetId = (int)$existingByRemote[$remoteKeyVal];
            }
            if ($targetId < 1 && $remoteKeyVal !== "" && isset($existingByLegacyRemote[$remoteKeyVal])) {
                $targetId = (int)$existingByLegacyRemote[$remoteKeyVal];
            }
            if ($targetId < 1) {
                $finger = isset($mapped["_fingerprint"]) ? $mapped["_fingerprint"] : "";
                if ($finger !== "" && isset($existingByFingerprint[$finger])) {
                    $targetId = (int)$existingByFingerprint[$finger];
                }
            }

            $data = isset($mapped["data"]) && is_array($mapped["data"]) ? $mapped["data"] : array();
            if (count($data) < 1) {
                continue;
            }

            if ($targetId > 0 && isset($existingById[$targetId])) {
                $before = $existingById[$targetId];
                $updateData = $this->filterChangedColumns($data, $before);
                if (count($updateData) > 0) {
                    $this->db->where("id", $targetId)->update("address", $updateData);
                    $err = $this->db->error();
                    if (isset($err["code"]) && (int)$err["code"] !== 0) {
                        return array(
                            "status" => false,
                            "message" => "Gagal update address: " . $err["message"],
                        );
                    }
                    $updated++;
                    $existingById[$targetId] = array_merge($before, $updateData);
                }
                $matchedIds[$targetId] = 1;
                continue;
            }

            $this->db->insert("address", $data);
            $err = $this->db->error();
            if (isset($err["code"]) && (int)$err["code"] !== 0) {
                return array(
                    "status" => false,
                    "message" => "Gagal insert address: " . $err["message"],
                );
            }
            $newId = (int)$this->db->insert_id();
            if ($newId > 0) {
                $matchedIds[$newId] = 1;
            }
            $inserted++;
        }

        $deactivated = 0;
        if ($explicit) {
            $hasDcIdAddressCol = in_array("dc_id_address", $columns, true);
            $legacyRemoteKeyColumns = $this->resolveAddressLegacyRemoteKeyColumns($columns, "dc_id_address");
            foreach ($existingById as $id => $row) {
                if (isset($matchedIds[$id])) {
                    continue;
                }
                if ($hasDcIdAddressCol) {
                    if (!$this->rowHasAnyRemoteAddressKey($row, array_merge(array("dc_id_address"), $legacyRemoteKeyColumns))) {
                        // Tanpa key remote apa pun, anggap data lokal/manual dan jangan dinonaktifkan otomatis.
                        continue;
                    }
                }

                $soft = array();
                if (in_array("status", $columns, true)) {
                    $soft["status"] = "0";
                }
                if (in_array("trash", $columns, true)) {
                    $soft["trash"] = "1";
                }
                if (count($soft) < 1) {
                    continue;
                }

                $this->db->where("id", $id)->update("address", $soft);
                $err = $this->db->error();
                if (isset($err["code"]) && (int)$err["code"] !== 0) {
                    return array(
                        "status" => false,
                        "message" => "Gagal nonaktifkan address lama: " . $err["message"],
                    );
                }
                $deactivated++;
            }
        }

        return array(
            "status" => true,
            "inserted" => $inserted,
            "updated" => $updated,
            "deactivated" => $deactivated,
            "changed" => ($inserted + $updated + $deactivated) > 0,
        );
    }

    private function mapAddressPayloadFromApi($item, $customerId, $jenis, $columns, $remoteKeyColumn = "")
    {
        $item = is_array($item) ? $item : array();

        $data = array(
            "extern_type" => "customer",
            "extern_id" => $customerId,
            "jenis" => $jenis,
        );

        if (in_array("status", $columns, true)) {
            $data["status"] = "1";
        }
        if (in_array("trash", $columns, true)) {
            $data["trash"] = "0";
        }

        $alias = $this->pickFirstNonEmptyValue($item, array("alias", "attn", "contact_person", "pic", "nama_pic", "nama"));
        if ($alias !== null && in_array("alias", $columns, true)) {
            $data["alias"] = $alias;
        }

        $nama = $this->pickFirstNonEmptyValue($item, array("nama", "name"));
        if ($nama !== null && in_array("nama", $columns, true)) {
            $data["nama"] = $nama;
        }

        $alamat = $this->pickFirstNonEmptyValue($item, array("alamat", "alamat_1", "address", "jalan"));
        if ($alamat !== null && in_array("alamat", $columns, true)) {
            $data["alamat"] = $alamat;
        }

        $kelurahan = $this->pickFirstNonEmptyValue($item, array("kelurahan", "desa", "village"));
        if ($kelurahan !== null && in_array("kelurahan", $columns, true)) {
            $data["kelurahan"] = $kelurahan;
        }

        $kecamatan = $this->pickFirstNonEmptyValue($item, array("kecamatan", "district"));
        if ($kecamatan !== null && in_array("kecamatan", $columns, true)) {
            $data["kecamatan"] = $kecamatan;
        }

        $kabupaten = $this->pickFirstNonEmptyValue($item, array("kabupaten", "kota", "city"));
        if ($kabupaten !== null && in_array("kabupaten", $columns, true)) {
            $data["kabupaten"] = $kabupaten;
        }

        $propinsi = $this->pickFirstNonEmptyValue($item, array("propinsi", "provinsi", "province"));
        if ($propinsi !== null && in_array("propinsi", $columns, true)) {
            $data["propinsi"] = $propinsi;
        }

        $kodepos = $this->pickFirstNonEmptyValue($item, array("kodepos", "kode_pos", "postal_code"));
        if ($kodepos !== null && in_array("kodepos", $columns, true)) {
            $data["kodepos"] = $kodepos;
        }

        $email = $this->pickFirstNonEmptyValue($item, array("email"));
        if ($email !== null && in_array("email", $columns, true)) {
            $data["email"] = $email;
        }

        $tlp = $this->pickFirstNonEmptyValue($item, array("tlp", "telp", "phone", "telp1", "tlp_1"));
        if ($tlp !== null && in_array("tlp", $columns, true)) {
            $data["tlp"] = $tlp;
        }

        $tlp2 = $this->pickFirstNonEmptyValue($item, array("tlp_2", "telp2", "phone2", "tlp2"));
        if ($tlp2 !== null && in_array("tlp_2", $columns, true)) {
            $data["tlp_2"] = $tlp2;
        }

        $tlp3 = $this->pickFirstNonEmptyValue($item, array("tlp_3", "telp3", "phone3", "tlp3"));
        if ($tlp3 !== null && in_array("tlp_3", $columns, true)) {
            $data["tlp_3"] = $tlp3;
        }

        $nik = $this->pickFirstNonEmptyValue($item, array("nik", "no_ktp"));
        if ($nik !== null && in_array("no_ktp", $columns, true)) {
            $data["no_ktp"] = $nik;
        }

        $npwp = $this->pickFirstNonEmptyValue($item, array("npwp"));
        if ($npwp !== null && in_array("npwp", $columns, true)) {
            $data["npwp"] = $npwp;
        }

        $remoteKeyValue = "";
        if ($remoteKeyColumn !== "") {
            $remoteKeyValue = $this->pickFirstNonEmptyValue($item, array("id", "address_id", "remote_id", "referensi_id", "dc_id"));
            if ($remoteKeyValue !== null && $remoteKeyValue !== "") {
                $data[$remoteKeyColumn] = $remoteKeyValue;
            }
            else {
                $remoteKeyValue = "";
            }
        }

        $data = $this->filterColumnsByTable("address", $data);

        return array(
            "data" => $data,
            "_remote_key" => (string)$remoteKeyValue,
            "_fingerprint" => $this->buildAddressFingerprint($data),
        );
    }

    private function resolveAddressRemoteKeyColumn($columns)
    {
        $candidates = array(
            "dc_id_address",
            "remote_id",
            "source_id",
            "referensi_id",
            "external_source_id",
            "dc_source_id",
            "source_dc_id",
        );
        foreach ($candidates as $col) {
            if (in_array($col, $columns, true)) {
                return $col;
            }
        }
        return "";
    }

    private function resolveAddressLegacyRemoteKeyColumns($columns, $activeColumn = "")
    {
        $activeColumn = trim((string)$activeColumn);
        $candidates = array(
            "remote_id",
            "source_id",
            "referensi_id",
            "external_source_id",
            "dc_source_id",
            "source_dc_id",
        );
        $result = array();
        foreach ($candidates as $col) {
            if ($col === $activeColumn) {
                continue;
            }
            if (in_array($col, $columns, true)) {
                $result[] = $col;
            }
        }
        return $result;
    }

    private function rowHasAnyRemoteAddressKey($row, $columns)
    {
        if (!is_array($row)) {
            return false;
        }
        $columns = is_array($columns) ? $columns : array();
        foreach ($columns as $col) {
            $col = trim((string)$col);
            if ($col === "") {
                continue;
            }
            if (isset($row[$col]) && trim((string)$row[$col]) !== "") {
                return true;
            }
        }
        return false;
    }

    private function ensureAddressSyncSchema()
    {
        if (is_array($this->addressSyncSchemaStatus)) {
            return $this->addressSyncSchemaStatus;
        }

        if (!$this->db->table_exists("address")) {
            $this->addressSyncSchemaStatus = array(
                "status" => false,
                "message" => "Tabel address tidak ditemukan",
            );
            return $this->addressSyncSchemaStatus;
        }

        $columns = $this->getTableColumnsCached("address");
        if (!in_array("dc_id_address", $columns, true)) {
            $added = $this->addAddressColumn("dc_id_address", "VARCHAR(64) NULL");
            if (!isset($added["status"]) || $added["status"] !== true) {
                $this->addressSyncSchemaStatus = $added;
                return $added;
            }
            if (isset($this->syncTableColumnsCache["address"])) {
                unset($this->syncTableColumnsCache["address"]);
            }
            $columns = $this->getTableColumnsCached("address");
        }

        $indexResult = $this->ensureAddressSyncIndex(
            "idx_address_customer_sync_key",
            array("extern_type", "extern_id", "jenis", "dc_id_address")
        );
        if (!isset($indexResult["status"]) || $indexResult["status"] !== true) {
            $this->addressSyncSchemaStatus = $indexResult;
            return $indexResult;
        }

        $this->addressSyncSchemaStatus = array("status" => true);
        return $this->addressSyncSchemaStatus;
    }

    private function addAddressColumn($columnName, $definitionSql)
    {
        $columnName = trim((string)$columnName);
        $definitionSql = trim((string)$definitionSql);
        if ($columnName === "" || $definitionSql === "") {
            return array(
                "status" => false,
                "message" => "Parameter addAddressColumn tidak valid",
            );
        }

        if ($this->db->field_exists($columnName, "address")) {
            return array("status" => true);
        }

        $tableName = $this->db->dbprefix("address");
        $sql = "ALTER TABLE `" . $tableName . "` ADD COLUMN `" . $columnName . "` " . $definitionSql;
        $this->db->query($sql);
        $err = $this->db->error();
        if (isset($err["code"]) && (int)$err["code"] !== 0) {
            $msg = isset($err["message"]) ? (string)$err["message"] : "";
            if (stripos($msg, "Duplicate column name") !== false || stripos($msg, "already exists") !== false) {
                return array("status" => true);
            }
            return array(
                "status" => false,
                "message" => "Gagal menambah kolom " . $columnName . " pada tabel address: " . $msg,
            );
        }

        return array("status" => true);
    }

    private function ensureAddressSyncIndex($indexName, $columns)
    {
        $indexName = trim((string)$indexName);
        $columns = is_array($columns) ? $columns : array();
        if ($indexName === "" || count($columns) < 1) {
            return array("status" => true);
        }

        $tableName = $this->db->dbprefix("address");
        $indexExists = false;
        $idxQuery = $this->db->query("SHOW INDEX FROM `" . $tableName . "`");
        if ($idxQuery && method_exists($idxQuery, "result_array")) {
            $idxRows = $idxQuery->result_array();
            foreach ($idxRows as $idxRow) {
                $keyName = isset($idxRow["Key_name"]) ? trim((string)$idxRow["Key_name"]) : "";
                if ($keyName === $indexName) {
                    $indexExists = true;
                    break;
                }
            }
        }
        if ($indexExists) {
            return array("status" => true);
        }

        $tableCols = $this->getTableColumnsCached("address");
        $safeCols = array();
        foreach ($columns as $col) {
            $col = trim((string)$col);
            if ($col !== "" && in_array($col, $tableCols, true)) {
                $safeCols[] = "`" . $col . "`";
            }
        }
        if (count($safeCols) < 1) {
            return array("status" => true);
        }

        $sql = "ALTER TABLE `" . $tableName . "` ADD INDEX `" . $indexName . "` (" . implode(",", $safeCols) . ")";
        $this->db->query($sql);
        $err = $this->db->error();
        if (isset($err["code"]) && (int)$err["code"] !== 0) {
            $msg = isset($err["message"]) ? (string)$err["message"] : "";
            if (stripos($msg, "Duplicate key name") !== false || stripos($msg, "already exists") !== false) {
                return array("status" => true);
            }
            return array(
                "status" => false,
                "message" => "Gagal menambah index sinkronisasi address: " . $msg,
            );
        }

        return array("status" => true);
    }

    private function maybeRunCustomerAddressBackfillOnce()
    {
        if (is_array($this->addressBackfillOnceResult)) {
            return $this->addressBackfillOnceResult;
        }

        if (!$this->isCustomerAddressBackfillEnabled()) {
            $this->addressBackfillOnceResult = array(
                "status" => true,
                "executed" => false,
                "message" => "Backfill one-time dc_id_address nonaktif",
            );
            return $this->addressBackfillOnceResult;
        }

        $schemaReady = $this->ensureAddressSyncSchema();
        if (!isset($schemaReady["status"]) || $schemaReady["status"] !== true) {
            $this->addressBackfillOnceResult = array(
                "status" => false,
                "executed" => false,
                "message" => isset($schemaReady["message"]) ? $schemaReady["message"] : "Skema address tidak siap untuk backfill",
            );
            return $this->addressBackfillOnceResult;
        }

        $markerTable = $this->ensureAddressBackfillMarkerTable();
        if (!isset($markerTable["status"]) || $markerTable["status"] !== true) {
            $this->addressBackfillOnceResult = array(
                "status" => false,
                "executed" => false,
                "message" => isset($markerTable["message"]) ? $markerTable["message"] : "Gagal menyiapkan tabel marker backfill",
            );
            return $this->addressBackfillOnceResult;
        }

        $marker = $this->getAddressBackfillMarker();
        if (is_array($marker)) {
            $markerStatus = isset($marker["status"]) ? strtolower(trim((string)$marker["status"])) : "";
            if ($markerStatus === "done") {
                $this->addressBackfillOnceResult = array(
                    "status" => true,
                    "executed" => false,
                    "message" => "Backfill one-time dc_id_address sudah pernah dijalankan",
                );
                return $this->addressBackfillOnceResult;
            }
        }

        $markRunning = $this->saveAddressBackfillMarker("running", "Backfill berjalan", "");
        if (!isset($markRunning["status"]) || $markRunning["status"] !== true) {
            $this->addressBackfillOnceResult = array(
                "status" => false,
                "executed" => false,
                "message" => isset($markRunning["message"]) ? $markRunning["message"] : "Gagal menandai marker running",
            );
            return $this->addressBackfillOnceResult;
        }

        $columns = $this->getTableColumnsCached("address");
        $backfill = $this->runCustomerAddressBackfillMigration($columns);
        if (!isset($backfill["status"]) || $backfill["status"] !== true) {
            $errMsg = isset($backfill["message"]) ? $backfill["message"] : "Backfill dc_id_address gagal";
            $this->saveAddressBackfillMarker("failed", "Backfill gagal", $errMsg);
            $this->addressBackfillOnceResult = array(
                "status" => false,
                "executed" => true,
                "message" => $errMsg,
                "detail" => $backfill,
            );
            return $this->addressBackfillOnceResult;
        }

        $summaryNote = "Backfill selesai";
        if (isset($backfill["updated_total"])) {
            $summaryNote .= " | updated_total:" . (int)$backfill["updated_total"];
        }
        if (isset($backfill["updated_from_legacy"])) {
            $summaryNote .= " | legacy:" . (int)$backfill["updated_from_legacy"];
        }
        if (isset($backfill["updated_from_remote"])) {
            $summaryNote .= " | remote:" . (int)$backfill["updated_from_remote"];
        }
        $this->saveAddressBackfillMarker("done", $summaryNote, "");

        $this->addressBackfillOnceResult = array(
            "status" => true,
            "executed" => true,
            "message" => $summaryNote,
            "detail" => $backfill,
        );
        return $this->addressBackfillOnceResult;
    }

    private function isCustomerAddressBackfillEnabled()
    {
        $flag = $this->config->item("customer_addr_backfill_once");
        if (is_bool($flag)) {
            return $flag;
        }
        if (is_numeric($flag)) {
            return ((int)$flag) === 1;
        }
        if (is_string($flag)) {
            $norm = strtolower(trim($flag));
            return in_array($norm, array("1", "true", "yes", "on"), true);
        }
        return false;
    }

    private function ensureAddressBackfillMarkerTable()
    {
        $tableName = $this->db->dbprefix("sync_migration_marker");
        $sql = "CREATE TABLE IF NOT EXISTS `" . $tableName . "` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `migration_key` VARCHAR(128) NOT NULL,
            `status` VARCHAR(16) NOT NULL DEFAULT 'pending',
            `note` TEXT NULL,
            `last_error` TEXT NULL,
            `created_at` DATETIME NULL,
            `updated_at` DATETIME NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uniq_migration_key` (`migration_key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        $this->db->query($sql);
        $err = $this->db->error();
        if (isset($err["code"]) && (int)$err["code"] !== 0) {
            return array(
                "status" => false,
                "message" => "Gagal menyiapkan tabel marker backfill: " . $err["message"],
            );
        }
        return array("status" => true);
    }

    private function getAddressBackfillMarker()
    {
        return $this->db
            ->where("migration_key", $this->addressBackfillMigrationKey)
            ->get("sync_migration_marker")
            ->row_array();
    }

    private function saveAddressBackfillMarker($status, $note = "", $lastError = "")
    {
        $status = trim((string)$status);
        $note = (string)$note;
        $lastError = (string)$lastError;
        $now = function_exists("dtimeNow") ? dtimeNow() : date("Y-m-d H:i:s");

        $row = $this->getAddressBackfillMarker();
        if (is_array($row) && isset($row["id"]) && is_numeric($row["id"])) {
            $this->db->where("id", (int)$row["id"])->update("sync_migration_marker", array(
                "status" => $status,
                "note" => $note,
                "last_error" => $lastError,
                "updated_at" => $now,
            ));
            $err = $this->db->error();
            if (isset($err["code"]) && (int)$err["code"] !== 0) {
                return array(
                    "status" => false,
                    "message" => "Gagal update marker backfill: " . $err["message"],
                );
            }
            return array("status" => true);
        }

        $this->db->insert("sync_migration_marker", array(
            "migration_key" => $this->addressBackfillMigrationKey,
            "status" => $status,
            "note" => $note,
            "last_error" => $lastError,
            "created_at" => $now,
            "updated_at" => $now,
        ));
        $err = $this->db->error();
        if (isset($err["code"]) && (int)$err["code"] !== 0) {
            $msg = isset($err["message"]) ? (string)$err["message"] : "";
            if (stripos($msg, "Duplicate entry") !== false) {
                return $this->saveAddressBackfillMarker($status, $note, $lastError);
            }
            return array(
                "status" => false,
                "message" => "Gagal insert marker backfill: " . $msg,
            );
        }

        return array("status" => true);
    }

    private function runCustomerAddressBackfillMigration($columns)
    {
        $columns = is_array($columns) ? $columns : array();
        if (!in_array("dc_id_address", $columns, true)) {
            return array(
                "status" => false,
                "message" => "Kolom dc_id_address tidak ditemukan pada tabel address",
            );
        }

        $result = array(
            "status" => true,
            "updated_from_legacy" => 0,
            "updated_from_remote" => 0,
            "updated_total" => 0,
            "customers_scanned" => 0,
            "customers_failed" => 0,
            "ambiguous_rows" => 0,
            "unmatched_rows" => 0,
        );

        $legacy = $this->runCustomerAddressLegacyColumnBackfill($columns);
        if (!isset($legacy["status"]) || $legacy["status"] !== true) {
            return $legacy;
        }
        $result["updated_from_legacy"] = isset($legacy["updated"]) ? (int)$legacy["updated"] : 0;

        $addressTable = $this->db->dbprefix("address");
        $customerTable = $this->db->dbprefix($this->tableName);
        $sql = "SELECT DISTINCT a.extern_id AS customer_id, c.dc_id AS dc_id
                FROM `" . $addressTable . "` a
                INNER JOIN `" . $customerTable . "` c ON c.id = a.extern_id
                WHERE a.extern_type='customer'
                  AND (a.dc_id_address IS NULL OR TRIM(CAST(a.dc_id_address AS CHAR))='')
                  AND c.dc_id IS NOT NULL
                  AND TRIM(CAST(c.dc_id AS CHAR))<>''
                  AND CAST(c.dc_id AS CHAR) <> '0'";
        $q = $this->db->query($sql);
        $err = $this->db->error();
        if (isset($err["code"]) && (int)$err["code"] !== 0) {
            return array(
                "status" => false,
                "message" => "Gagal membaca kandidat backfill address: " . $err["message"],
            );
        }
        $targets = $q && method_exists($q, "result_array") ? $q->result_array() : array();

        foreach ($targets as $target) {
            $customerId = isset($target["customer_id"]) && is_numeric($target["customer_id"]) ? (int)$target["customer_id"] : 0;
            $dcId = isset($target["dc_id"]) ? trim((string)$target["dc_id"]) : "";
            if ($customerId < 1 || $dcId === "" || $dcId === "0") {
                continue;
            }

            $result["customers_scanned"]++;
            $remote = $this->fetchCustomerDetailByDcId($dcId);
            if (!isset($remote["status"]) || $remote["status"] !== true) {
                $result["customers_failed"]++;
                continue;
            }

            $filled = $this->backfillCustomerAddressDcIdByFingerprint($customerId, $remote, $columns);
            if (!isset($filled["status"]) || $filled["status"] !== true) {
                $result["customers_failed"]++;
                continue;
            }

            $result["updated_from_remote"] += isset($filled["updated"]) ? (int)$filled["updated"] : 0;
            $result["ambiguous_rows"] += isset($filled["ambiguous"]) ? (int)$filled["ambiguous"] : 0;
            $result["unmatched_rows"] += isset($filled["unmatched"]) ? (int)$filled["unmatched"] : 0;
        }

        $result["updated_total"] = (int)$result["updated_from_legacy"] + (int)$result["updated_from_remote"];
        return $result;
    }

    private function runCustomerAddressLegacyColumnBackfill($columns)
    {
        $columns = is_array($columns) ? $columns : array();
        $legacyColumns = $this->resolveAddressLegacyRemoteKeyColumns($columns, "dc_id_address");
        if (count($legacyColumns) < 1) {
            return array("status" => true, "updated" => 0);
        }

        $addressTable = $this->db->dbprefix("address");
        $updated = 0;
        foreach ($legacyColumns as $legacyCol) {
            $legacyCol = trim((string)$legacyCol);
            if ($legacyCol === "" || !in_array($legacyCol, $columns, true)) {
                continue;
            }
            $sql = "UPDATE `" . $addressTable . "`
                    SET `dc_id_address` = `" . $legacyCol . "`
                    WHERE (`dc_id_address` IS NULL OR TRIM(CAST(`dc_id_address` AS CHAR))='')
                      AND `" . $legacyCol . "` IS NOT NULL
                      AND TRIM(CAST(`" . $legacyCol . "` AS CHAR))<>''";
            $this->db->query($sql);
            $err = $this->db->error();
            if (isset($err["code"]) && (int)$err["code"] !== 0) {
                return array(
                    "status" => false,
                    "message" => "Gagal backfill legacy " . $legacyCol . ": " . $err["message"],
                );
            }
            $updated += (int)$this->db->affected_rows();
        }

        return array(
            "status" => true,
            "updated" => $updated,
        );
    }

    private function backfillCustomerAddressDcIdByFingerprint($customerId, $remotePayload, $columns)
    {
        $customerId = is_numeric($customerId) ? (int)$customerId : 0;
        $columns = is_array($columns) ? $columns : array();
        $remotePayload = is_array($remotePayload) ? $remotePayload : array();
        if ($customerId < 1) {
            return array(
                "status" => false,
                "message" => "customerId tidak valid untuk backfill address",
            );
        }

        $result = array(
            "status" => true,
            "updated" => 0,
            "ambiguous" => 0,
            "unmatched" => 0,
        );

        $remoteByJenis = array(
            "shipment" => isset($remotePayload["shipment"]) && is_array($remotePayload["shipment"]) ? $remotePayload["shipment"] : array(),
            "bill" => isset($remotePayload["billing"]) && is_array($remotePayload["billing"]) ? $remotePayload["billing"] : array(),
        );

        foreach ($remoteByJenis as $jenis => $remoteRows) {
            $fingerMap = $this->buildRemoteAddressFingerprintMap($remoteRows, $customerId, $jenis, $columns);
            if (count($fingerMap) < 1) {
                continue;
            }

            $localRows = $this->db
                ->where("extern_type", "customer")
                ->where("extern_id", $customerId)
                ->where("jenis", $jenis)
                ->where("(dc_id_address IS NULL OR TRIM(CAST(dc_id_address AS CHAR))='')", null, false)
                ->get("address")
                ->result_array();

            foreach ($localRows as $localRow) {
                if (!isset($localRow["id"])) {
                    continue;
                }

                $finger = $this->buildAddressFingerprint($localRow);
                if ($finger === "" || !isset($fingerMap[$finger])) {
                    $result["unmatched"]++;
                    continue;
                }

                $candidateIds = array_keys($fingerMap[$finger]);
                if (count($candidateIds) !== 1) {
                    $result["ambiguous"]++;
                    continue;
                }

                $dcIdAddress = trim((string)$candidateIds[0]);
                if ($dcIdAddress === "") {
                    $result["unmatched"]++;
                    continue;
                }

                $this->db->where("id", (int)$localRow["id"])->update("address", array(
                    "dc_id_address" => $dcIdAddress,
                ));
                $err = $this->db->error();
                if (isset($err["code"]) && (int)$err["code"] !== 0) {
                    return array(
                        "status" => false,
                        "message" => "Gagal update dc_id_address address: " . $err["message"],
                    );
                }
                if ((int)$this->db->affected_rows() > 0) {
                    $result["updated"]++;
                }
            }
        }

        return $result;
    }

    private function buildRemoteAddressFingerprintMap($rows, $customerId, $jenis, $columns)
    {
        $rows = is_array($rows) ? $rows : array();
        $columns = is_array($columns) ? $columns : array();
        $map = array();

        foreach ($rows as $rawRow) {
            $item = is_array($rawRow) ? $rawRow : (array)$rawRow;
            $remoteId = $this->pickFirstNonEmptyValue($item, array("id", "address_id", "remote_id", "referensi_id", "dc_id"));
            if ($remoteId === null || trim((string)$remoteId) === "") {
                continue;
            }

            $mapped = $this->mapAddressPayloadFromApi($item, $customerId, $jenis, $columns, "");
            $finger = isset($mapped["_fingerprint"]) ? trim((string)$mapped["_fingerprint"]) : "";
            if ($finger === "") {
                continue;
            }

            if (!isset($map[$finger])) {
                $map[$finger] = array();
            }
            $map[$finger][trim((string)$remoteId)] = 1;
        }

        return $map;
    }

    private function buildAddressFingerprint($row)
    {
        $row = is_array($row) ? $row : array();
        $parts = array(
            isset($row["alias"]) ? strtolower(trim((string)$row["alias"])) : "",
            isset($row["nama"]) ? strtolower(trim((string)$row["nama"])) : "",
            isset($row["alamat"]) ? strtolower(trim((string)$row["alamat"])) : "",
            isset($row["kelurahan"]) ? strtolower(trim((string)$row["kelurahan"])) : "",
            isset($row["kecamatan"]) ? strtolower(trim((string)$row["kecamatan"])) : "",
            isset($row["kabupaten"]) ? strtolower(trim((string)$row["kabupaten"])) : "",
            isset($row["propinsi"]) ? strtolower(trim((string)$row["propinsi"])) : "",
            isset($row["kodepos"]) ? strtolower(trim((string)$row["kodepos"])) : "",
            isset($row["tlp"]) ? preg_replace('/\D+/', '', (string)$row["tlp"]) : "",
            isset($row["tlp_2"]) ? preg_replace('/\D+/', '', (string)$row["tlp_2"]) : "",
            isset($row["tlp_3"]) ? preg_replace('/\D+/', '', (string)$row["tlp_3"]) : "",
            isset($row["email"]) ? strtolower(trim((string)$row["email"])) : "",
        );
        return md5(implode("|", $parts));
    }

    private function filterChangedColumns($newData, $existingData)
    {
        $newData = is_array($newData) ? $newData : array();
        $existingData = is_array($existingData) ? $existingData : array();

        $changed = array();
        foreach ($newData as $key => $value) {
            $oldValue = array_key_exists($key, $existingData) ? $existingData[$key] : null;
            if ((string)$oldValue !== (string)$value) {
                $changed[$key] = $value;
            }
        }
        return $changed;
    }

    private function getTableColumnsCached($table)
    {
        if (isset($this->syncTableColumnsCache[$table])) {
            return $this->syncTableColumnsCache[$table];
        }
        if (!$this->db->table_exists($table)) {
            $this->syncTableColumnsCache[$table] = array();
            return $this->syncTableColumnsCache[$table];
        }
        $prevDebug = null;
        if (isset($this->db) && is_object($this->db) && property_exists($this->db, "db_debug")) {
            $prevDebug = $this->db->db_debug;
            $this->db->db_debug = false;
        }
        $cols = $this->db->list_fields($table);
        if ($prevDebug !== null) {
            $this->db->db_debug = $prevDebug;
        }
        $this->syncTableColumnsCache[$table] = is_array($cols) ? $cols : array();
        return $this->syncTableColumnsCache[$table];
    }

    private function filterColumnsByTable($table, $data)
    {
        $data = is_array($data) ? $data : array();
        $cols = $this->getTableColumnsCached($table);
        if (count($cols) < 1) {
            return $data;
        }

        $out = array();
        foreach ($data as $k => $v) {
            if (in_array($k, $cols, true)) {
                $out[$k] = $v;
            }
        }
        return $out;
    }

    private function isAssocArray($arr)
    {
        if (!is_array($arr)) {
            return false;
        }
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}