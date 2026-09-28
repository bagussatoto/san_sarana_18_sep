<?php

//--include_once "MdlHistoriData.php";

class MdlStaticLevelEmployee extends MdlMother
{
    protected $tableName = "static";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
        "jenis='payment'",
        "status='1'",
        "trash='0'",
    );

    protected $validationRules = array(
        "nama" => array("required", "singleOnly"),

    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id"   => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "name" => array(
            "label"     => "name",
            "type"      => "int", "length" => "24", "kolom" => "name",
            "inputType" => "text",
        ),
        // "parent_str" => array(
        //     "label"     => "label",
        //     "type"      => "int", "length" => "24", "kolom" => "parent_str",
        //     "inputType" => "text",
        // ),

    );
    protected $staticData = array(
        array(
            "id"    => "0",
            "name"  => "auto",
            // "label" => "owner",
            "parent_str" => "",
        ),
        array(
            "id"    => "1",
            "name"  => "creator",
            "parent_str" => "pusat cabang",
        ),
        array(
            "id"    => "2",
            "name"  => "otorisator",
            // "label" => "owner",
            "parent_str" => "pusat",
        ),
    );

    protected $listedFields = array(
        "name"     => "name",
        "due_days" => "due days",
        "status"   => "status",
    );

    public function __construct($data = "")
    {
        $this->data = $data;
        $this->class = get_class();
    }

    //region gs
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
    //endregion


    //@override with static data

    public function lookupAll()
    {
        if (isset($this->staticData) && is_array($this->staticData) && sizeof($this->staticData) > 0) {
            //            cekkuning("ada isinya");
            $iCtr = 0;
            $sql = "";
            //			arrprint($this->filters);
            foreach ($this->staticData as $iSpec) {
                $iCtr++;
                $sql .= 'SELECT ';
                $fCtr = 0;
                foreach ($this->fields as $fID => $fSpec) {
                    $fCtr++;
                    $sql .= "'" . $iSpec[$fID] . "' as $fID";
                    if ($fCtr < sizeof($this->fields)) {
                        $sql .= ",";
                    }
                }
                if ($iCtr < sizeof($this->staticData)) {
                    $sql .= " union ";
                }
            }
            //            cekkuning($sql);
            return $this->db->query($sql);
        }
        else {
            //            cekkuning("TIDAK ada isinya");
            return null;
        }

    }

    // --------------------------------
    public function where($key, $value)
    {
        $filtered = array_filter($this->staticData, function ($item) use ($key, $value) {
            // cekHijau("$key $value");
            // arrPrintWebs($item[$key]);


            return isset($item[$key]) && $item[$key] == $value;
        });

        return new $this->class($filtered);
    }

    public function result()
    {
        $resetArray = array_values($this->data); // Reset array keys to start from 0
        return array_map(function ($item) {
            return (object)$item;
        }, $resetArray);
    }

    public function lookupByID($id)
    {
        // cekKuning($id);


        $query = $this->where('id', $id);

        // arrPrintHijau($query->result());

        return $query;
    }

}