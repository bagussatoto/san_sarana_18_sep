<?php

//--include_once "MdlHistoriData.php";
class MdlProdukSubKategori extends MdlMother
{
    protected $tableName = "produk_sub_kategori";
    protected $indexFields = "id";

    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("jenis='sub_kategori'", "status='1'", "trash='0'");
//    protected $filters = array("jenis='folder'");

    protected $validationRules = array(
        "nama" => array("required", "singleOnly"),
        //        "status" => array("required"),
    );

    protected $listedFieldsView = array("kode", "nama");
    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int", "length" => "24",
            "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "kode" => array(
            "label" => "kode",
            "type" => "varchar", "length" => "6", "kolom" => "kode",
            "inputType" => "hidden",
            "attr" => "readonly",
        ),
        "kategori" => array(
            "label" => "kategori",
            "type" => "int", "length" => "255", "kolom" => "kategori_id",
            "inputType" => "combo",
            "reference" => "MdlProdukKategori",

            "strField" => "nama",
            "editable" => true,
            "kolom_nama" => "kategori_nama",
        ),
        "nama" => array(
            "label" => "nama",
            "type" => "varchar", "length" => "255", "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
        "status" => array(
            "label" => "status",
            "type" => "int", "length" => "24", "kolom" => "status",
            "inputType" => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "status",
        ),
    );
    protected $listedFields = array(
        "id" => "id",
        "kode" => "kode",
        "nama" => "nama",
    );

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

    public function addData($data)
    {
        $res = parent::addData($data);
        if ($res) {
            $id = $this->db->insert_id();
            if ($id && !empty($data['kategori_id'])) {
                $this->db->where('id', $data['kategori_id']);
                $katRow = $this->db->get('produk_kategori')->row();
                $dept_id = $katRow ? intval($katRow->department_id) : 0;
                
                $kat_id = intval($data['kategori_id']);
                $kode = sprintf('%02d', $dept_id) . sprintf('%02d', $kat_id) . sprintf('%02d', $id);
                $this->db->where('id', $id);
                $this->db->update($this->tableName, ['kode' => $kode]);
            }
        }
        return $res;
    }

    public function updateData($where, $data)
    {
        $res = parent::updateData($where, $data);
        if ($res && isset($where['id'])) {
            $id = $where['id'];
            $this->db->where('id', $id);
            $currentRow = $this->db->get($this->tableName)->row();
            
            if ($currentRow && !empty($currentRow->kategori_id)) {
                $kat_id = intval($currentRow->kategori_id);
                $this->db->where('id', $kat_id);
                $katRow = $this->db->get('produk_kategori')->row();
                $dept_id = $katRow ? intval($katRow->department_id) : 0;
                
                $kode = sprintf('%02d', $dept_id) . sprintf('%02d', $kat_id) . sprintf('%02d', $id);
                $this->db->where('id', $id);
                $this->db->update($this->tableName, ['kode' => $kode]);
            }
        }
        return $res;
    }

    public function paramSyncNamaNama()
    {
        $mdls = array(
            "MdlProdukKategori" => array(
                "id" => "kategori_id",
                // "str" => "merek_nama",
                "kolomDatas" => array(
                    "nama" => "kategori_nama",
                ),
            ),

        );

        return $mdls;

    }

}