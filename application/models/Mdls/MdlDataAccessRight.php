<?php

include_once "MdlMenuData.php";

class MdlDataAccessRight extends MdlMenuData
{
    public function __construct()
    {
        parent::__construct();
    }

    public function lookupActive()
    {
        $condites = array(
            "trash" => 0,
        );

        $query = $this->lookupByCondition($condites);

        return $query;
    }
}