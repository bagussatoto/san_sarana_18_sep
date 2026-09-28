<?php


class MdlSalesRejectHolding extends MdlMother
{
    protected $tableName = "penjualan_transaksi_holding_reject";

    public function getTableName()
    {
        return $this->tableName;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }
    public function __construct()
    {
        parent::__construct();
    }

    public function _preValues($so_id,$po_id){
        $where = array(
            "so_id"=>$so_id,
            "po_id"=>$po_id,
        );
    }

    public function lookUpRejectedPoTrans(){
        $where = array(
            "exec_po"=>0,
        );
        $this->db->limit(1);
        $this->db->order_by("id","asc");
        $this->db->where($where);
        $result = $this->db->get($this->tableName)->result_array();
        return $result;
    }
    public function lookUpRejectedSOTrans(){
        $where = array(
            "exec_so"=>0,
        );
//        $this->db->limit(1);
        $this->db->order_by("id","asc");
        $this->db->where($where);
        $result = $this->db->get($this->tableName)->result_array();
        return $result;
    }

}