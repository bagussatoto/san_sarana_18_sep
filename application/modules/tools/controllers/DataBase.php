<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 5/6/2019
 * Time: 8:39 PM
 */
class DataBase extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        // if (!isset($this->session->login['id'])) {
        //     gotoLogin();
        // }
    }

    public function TambahKolom(){

        $tables_0 = $this->db->list_tables();
        $tables = $filtered_tables = preg_grep('/^_/', $tables_0);

        cekBiru($tables);

        $this->load->dbforge();

        $field_name = "dtime_2";
        $fields = array(
            $field_name => array(
                'type' => 'datetime',
                // 'constraint' => 'default',
                'null' => TRUE,
                'after' => 'dtime',
            )
        );

        $no = 0;
        foreach ($tables as $table) {
            if (!$this->db->field_exists($field_name, $table)) {
                $no++;

                $this->dbforge->add_column($table, $fields);
            }
            showLast_query("merah");
        }
        matiHere(__LINE__ . "  DONE $no table");
    }

    public function RenameProduk(){

    }

}