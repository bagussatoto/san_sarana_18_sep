<?php

//--include_once "MdlHistoriData.php";
class MdlProdukKategori extends MdlMother
{
    protected $tableName = "produk_kategori";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("jenis='kategori'", "status='1'", "trash='0'");
//    protected $filters = array("jenis='folder'");

    protected $validationRules = array(
        "nama" => array("required", "singleOnly"),
        //        "status" => array("required"),
    );

    protected $listedFieldsView = array("department", "kode", "nama");
    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int", "length" => "24",
            "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "department" => array(
            "label" => "department",
            "type" => "int", "length" => "255", "kolom" => "department_id",
            "inputType" => "combo",
            "reference" => "MdlProdukDepartment",
            "strField" => "nama",
            "editable" => true,
            "kolom_nama" => "department_nama",
        ),
        "kode" => array(
            "label" => "kode",
            "type" => "varchar", "length" => "4", "kolom" => "kode",
            "inputType" => "hidden",
            "attr" => "readonly",
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
//        "department" => "department",
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
            if ($id && !empty($data['department_id'])) {
                $dept_id = intval($data['department_id']);
                $kode = sprintf('%02d', $dept_id) . sprintf('%02d', $id);
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
            
            if ($currentRow) {
                $dept_id = intval($currentRow->department_id);
                $kode = sprintf('%02d', $dept_id) . sprintf('%02d', $id);
                $this->db->where('id', $id);
                $this->db->update($this->tableName, ['kode' => $kode]);
            }
        }
        return $res;
    }

    public function paramSyncNamaNama()
    {
        $mdls = array(
            "MdlProdukDepartment" => array(
                "id" => "department_id",
                "kolomDatas" => array(
                    "nama" => "department_nama",
                ),
            ),
        );
        return $mdls;
    }
}