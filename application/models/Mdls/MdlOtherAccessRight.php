<?php

include_once "MdlMenuBebas.php";

class MdlOtherAccessRight extends MdlMenuBebas
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