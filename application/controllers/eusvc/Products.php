<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Products extends REST_Controller
{
    private $model;

    function __construct($config = 'rest')
    {

        parent::__construct($config);

//		$this->model = "Mdl".ucfirst($this->uri->segment(4));
        $this->model = "MdlProduk";
        $this->load->database();
        $this->load->model("Mdls/" . $this->model);
//        die("constructor");
    }


    function askTotalNumbers_get()
    {

        $id = $this->get('id');

        $key = $this->uri->segment(4);
        $mdlName = $this->model;
        $o = new $mdlName();

        $result = $o->lookupDataCount($key);
        $this->response($result, 200);
    }

    function askLimited_get()
    {

        $limit_per_page = $this->uri->segment(4);
        $page = $this->uri->segment(5);
        $key = $this->uri->segment(6);

        $id = $this->get('id');
        $mdlName = $this->model;
        $o = new $mdlName();

//		$tmp = $o->lookupLimitedData($limit_per_page, $page * $limit_per_page, $key);
        $tmp = $o->lookupLimitedData($limit_per_page, ($page - 1) * $limit_per_page, $key);
//		die($this->db->last_query());

        $result = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $tmpData = array();
                foreach ($o->getFields() as $fName => $fSpec) {
                    $realFieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
                    $tmpData[$fName] = $row->$realFieldName;
                }
                $result[] = $tmpData;
            }
        }
        $this->response($result, 200);
    }

    function askTotalNumbersInFolder_get()
    {

        $id = $this->get('id');
        $folderID = $this->uri->segment(4);
        $key = $this->uri->segment(5);
        $mdlName = $this->model;
        $o = new $mdlName();

        $o->addFilter("folders='$folderID'");
        $result = $o->lookupDataCount($key);
        $this->response($result, 200);
    }

    function askLimitedInFolder_get()
    {

        $folderID = $this->uri->segment(4);
        $limit_per_page = $this->uri->segment(5);
        $page = $this->uri->segment(6);
        $key = $this->uri->segment(7);

        $id = $this->get('id');
        $mdlName = $this->model;
        $o = new $mdlName();

        $o->addFilter("folders='$folderID'");

//		$tmp = $o->lookupLimitedData($limit_per_page, $page * $limit_per_page, $key);
        $tmp = $o->lookupLimitedData($limit_per_page, ($page - 1) * $limit_per_page, $key);

        $result = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $tmpData = array();
                foreach ($o->getFields() as $fName => $fSpec) {
                    $realFieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
                    $tmpData[$fName] = $row->$realFieldName;
                }
                $result[] = $tmpData;
            }
        }
        $this->response($result, 200);
    }

    function seePrices_get()
    {
//        $productID = $this->uri->segment(4);
        $branchID = $this->uri->segment(4);
//        $segment = $this->uri->segment(5);

        $mdlName = "MdlHargaProduk";
        $this->load->model("Mdls/" . $mdlName);

        $o = new $mdlName();
//        $o->addFilter("produk_id='$productID'");
        $o->addFilter("cabang_id='$branchID'");
        $tmp = $o->lookupAll()->result();
        $results = array();
        if (sizeof($tmp) > 0) {

            foreach ($tmp as $row) {
                $results[$row->produk_id][$row->jenis_value] = $row->nilai;
            }
        }
//        cekbiru($this->db->last_query());
        $this->response($results, 200);
    }

    function askAvailNumber_get()
    {

        $productID = $this->uri->segment(4);
        $branchID = $this->uri->segment(5);
        $whID = $this->uri->segment(6);

        $mdlName = "MdlLockerStock";
        $this->load->model("Mdls/" . $mdlName);

        $o = new $mdlName();
        $result = $o->cekLoker($branchID, $productID, "active", 0, 0, $whID);

        $this->response($result, 200);
    }

    function whatIsPrice_get()
    {
        $productID = $this->uri->segment(4);
        $branchID = $this->uri->segment(5);
        $segment = $this->uri->segment(6);

        $mdlName = "MdlHargaProduk";
        $this->load->model("Mdls/" . $mdlName);

        $o = new $mdlName();
        $o->addFilter("produk_id='$productID'");
        $o->addFilter("cabang_id='$branchID'");
        $o->addFilter("jenis_value='$segment'");
        $tmp = $o->lookupAll()->result();
        if (sizeof($tmp) > 0) {
            $result = $tmp[0]->nilai;
        } else {
            $result = 0;
        }
        $this->response($result, 200);
    }

    function seeItemDetail_get()
    {

        $id = $this->uri->segment(4);
        $mdlName = $this->model;
        $o = new $mdlName();
        $tmp = $o->lookupByID($id)->result();
        $result = array();
//        print_r($tmp);die();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $tmpData = array();
                foreach ($o->getFields() as $fName => $fSpec) {
//                    echo "fName: $fName, kolom: ".$fSpec['kolom']."<br>";
                    $realFieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
                    $tmpData[$fName] = $row->$realFieldName;
                }
                $result[] = $tmpData;
            }
        }
        $this->response($result, 200);
    }

    function seeItemAll_get()
    {

        $key = $this->uri->segment(4);
        $val = $this->uri->segment(5);
        $mdlName = $this->model;
        $o = new $mdlName();

        switch ($key){
            case "id":
                $this->db->where($key,$val);
                break;
            case "limit":
                $this->db->limit($val);
                break;
            case "search":
                // $this->
                $tmpCols = array();

                $listedFieldsSelectItem =$o->getListedFieldsSelectItem();
                if (method_exists($o, "getListedFieldsSelectItem") && count($listedFieldsSelectItem) > 1) {
                    // arrPrint($listedFieldsSelectItem);

                    foreach ($listedFieldsSelectItem as $fName => $fSpec) {
                        $fieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
                        $tmpCols[$fieldName] = $fieldName;
                    }
                }
                else {
                    // arrPrint($o->getFields());
                    foreach ($o->getFields() as $fName => $fSpec) {
                        $fieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
                        $tmpCols[$fieldName] = $fieldName;
                    }
                }

                $o->createSmartSearch($val, $tmpCols);
                break;
        }
        $o->setTokoId(0);
        // $total_rows = $o->lookupJmlActive();
        $tmp = $o->lookupAll()->result();
        // showLast_query("biru");
        $result = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $tmpData = array();
                foreach ($o->getFields() as $fName => $fSpec) {
                    $realFieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
                    $tmpData[$fName] = $row->$realFieldName;
                }
                // tambahan data bisa dimasukan disini
                $tmpData["image"] = "";


                $result[] = $tmpData;
            }
        }

        $hasil = array();
        // $hasil["jml_total"] = $total_rows;
        $hasil["jml"] = count($result);
        $hasil["data"] = $result;

        $this->response($hasil, 200);
    }

    function seeFolders_get()
    {


        $limit_per_page = $this->uri->segment(4);
        $page = $this->uri->segment(5);
        $key = $this->uri->segment(6);

        $id = $this->get('id');
        $this->load->model("Mdls/MdlFolderProduk");
        $mdlName = "MdlFolderProduk";
        $o = new $mdlName();

        $tmp = $o->lookupAll()->result();

        $result = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {

                $result[$row->id] = $row->nama;
            }
        }
        $this->response($result, 200);
    }


    //====with active lockers only

    function askTotalStockNumbers_get()
    {

        $id = $this->get('id');
        $branchID = $this->uri->segment(4);
        $whID = $this->uri->segment(5);

        $key = $this->uri->segment(6);
        $mdlName = $this->model;

        $o = new $mdlName();

        $mdlName2 = "MdlLockerStock";
        $this->load->model("Mdls/$mdlName2");
        $c = new $mdlName2();

        $this->db->join($c->getTableName(), $c->getTableName() . ".produk_id = " . $o->getTableName() . ".id and state='active' and jumlah>0 ");
        $o->addFilter("cabang_id='$branchID'");
        $o->addFilter("gudang_id='$whID'");
        $result = $o->lookupDataCount($key);
//        cekkuning($this->db->last_query());
        $this->response($result, 200);
    }

    function askLimitedStocks_get()
    {


        $branchID = $this->uri->segment(4);
        $whID = $this->uri->segment(5);
        $limit_per_page = $this->uri->segment(6);
        $page = $this->uri->segment(7);
        $key = $this->uri->segment(8);

        $id = $this->get('id');
        $mdlName = $this->model;
        $o = new $mdlName();


        $className="MdlProduk";
        $dataExtRel = isset($this->config->item('dataExtRelation')[$className]["images"]) ? $this->config->item('dataExtRelation')[$className]["images"] : array();
        $arrExtImg = array();

        if (sizeof($dataExtRel) > 0) {
            $this->load->model("Mdls/MdlImages");
            $im = new MdlImages();
            $imgBlob = $im->lookupAll()->result();
            $countData = 0;
            foreach ($imgBlob as $rowImg) {
                $countData++;
                $arrExtImg[$rowImg->parent_id] = $rowImg->files;
                $badgeData[$rowImg->parent_id][] =$countData;
            }
        }

        $mdlName2 = "MdlLockerStock";
        $this->load->model("Mdls/$mdlName2");
        $c = new $mdlName2();

        $this->db->select("*,produk.id as id");
        $this->db->join($c->getTableName(), $c->getTableName() . ".produk_id = " . $o->getTableName() . ".id and state='active' and jumlah>0 ");
        $o->addFilter("cabang_id='$branchID'");
        $o->addFilter("gudang_id='$whID'");
        $tmp = $o->lookupLimitedData($limit_per_page, ($page - 1) * $limit_per_page, $key);
// showLast_query("lime");
// arrPrint($tmp);

        $result = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $tmpData = array();
                foreach ($o->getFields() as $fName => $fSpec) {
                    $realFieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;


                    if (sizeof($arrExtImg) > 0) {
                        $srcKey = $dataExtRel["srcKey"];
                        $selectID = $row->$srcKey;
                        if (isset($arrExtImg[$selectID])) {
                            $valData = $arrExtImg[$selectID];
                            $img_src = "src='$valData'";
//                            $img_src = "src='data:image/jpeg;base64,$valData'";
                            $badge = sizeof($badgeData[$selectID]) > 1 ? sizeof($badgeData[$selectID]) : "";
                            $notifBadge =$badge > 1 ? "<span class='notify-badge' style=''>$badge</span>" : "";
                        } else {
                            $valData = base_url() . "public/images/img_blank.gif";
                            $img_src = "src='$valData'";
                            $notifBadge = "";
                        }
                        $fieldsImages = "<div class=''>";
                        $fieldsImages .= "<div class='item'>$notifBadge";
                        $fieldsImages .= "<img $img_src class='img-responsive' width='65px'>";
                        $fieldsImages .= "</div>";
                        $fieldsImages .= "</div>";

//                        $tmpData['images'] = $fieldsImages;
                        $tmpData['images'] = $img_src;
                    }

                    $tmpData[$fName] = $row->$realFieldName;
                }
                $result[] = $tmpData;
            }
        }
        $this->response($result, 200);
    }

    function askTotalStockNumbersInFolder_get()
    {

        $id = $this->get('id');
        $folderID = $this->uri->segment(4);
        $branchID = $this->uri->segment(5);
        $whID = $this->uri->segment(6);

        $key = $this->uri->segment(7);
        $mdlName = $this->model;

        $o = new $mdlName();

        $mdlName2 = "MdlLockerStock";
        $this->load->model("Mdls/$mdlName2");
        $c = new $mdlName2();

        $this->db->join($c->getTableName(), $c->getTableName() . ".produk_id = " . $o->getTableName() . ".id and state='active' and jumlah>0 ");
        $o->addFilter("folders='$folderID'");
        $o->addFilter("cabang_id='$branchID'");
        $o->addFilter("gudang_id='$whID'");
        $result = $o->lookupDataCount($key);
//        cekkuning($this->db->last_query());
        $this->response($result, 200);
    }

    function askLimitedStocksInFolder_get()
    {

        $folderID = $this->uri->segment(4);
        $branchID = $this->uri->segment(5);
        $whID = $this->uri->segment(6);
        $limit_per_page = $this->uri->segment(7);
        $page = $this->uri->segment(8);
        $key = $this->uri->segment(9);

        $id = $this->get('id');
        $mdlName = $this->model;
        $o = new $mdlName();


        $mdlName2 = "MdlLockerStock";
        $this->load->model("Mdls/$mdlName2");
        $c = new $mdlName2();

        $this->db->select("*,produk.id as id");
        $this->db->join($c->getTableName(), $c->getTableName() . ".produk_id = " . $o->getTableName() . ".id and state='active' and jumlah>0 ");
        $o->addFilter("folders='$folderID'");
        $o->addFilter("cabang_id='$branchID'");
        $o->addFilter("gudang_id='$whID'");
        $tmp = $o->lookupLimitedData($limit_per_page, ($page - 1) * $limit_per_page, $key);


        $className="MdlProduk";
        $dataExtRel = isset($this->config->item('dataExtRelation')[$className]["images"]) ? $this->config->item('dataExtRelation')[$className]["images"] : array();
        $arrExtImg = array();

        if (sizeof($dataExtRel) > 0) {
            $this->load->model("Mdls/MdlImages");
            $im = new MdlImages();
            $imgBlob = $im->lookupAll()->result();
            $countData = 0;
            foreach ($imgBlob as $rowImg) {
                $countData++;
                $arrExtImg[$rowImg->parent_id] = $rowImg->files;
                $badgeData[$rowImg->parent_id][] =$countData;
            }
        }
        $result = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $tmpData = array();
                foreach ($o->getFields() as $fName => $fSpec) {
                    $realFieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
                    if (sizeof($arrExtImg) > 0) {
                        $srcKey = $dataExtRel["srcKey"];
                        $selectID = $row->$srcKey;
                        if (isset($arrExtImg[$selectID])) {
                            $valData = $arrExtImg[$selectID];
                            $img_src = "src='$valData'";
//                            $img_src = "src='data:image/jpeg;base64,$valData'";
                            $badge = sizeof($badgeData[$selectID]) > 1 ? sizeof($badgeData[$selectID]) : "";
                            $notifBadge =$badge > 1 ? "<span class='notify-badge' style=''>$badge</span>" : "";
                        } else {
                            $valData = base_url() . "public/images/img_blank.gif";
                            $img_src = "src='$valData'";
                            $notifBadge = "";
                        }
                        $fieldsImages = "<div class=''>";
                        $fieldsImages .= "<div class='item'>$notifBadge";
                        $fieldsImages .= "<img $img_src class='img-responsive' width='65px'>";
                        $fieldsImages .= "</div>";
                        $fieldsImages .= "</div>";

//                        $tmpData['images'] = $fieldsImages;
                        $tmpData['images'] = $img_src;
                    }
                    $tmpData[$fName] = $row->$realFieldName;
                }
                $result[] = $tmpData;
            }
        }
        $this->response($result, 200);
    }

    function askActiveStockAmount_get()
    {

        $prodID = $this->uri->segment(4);
        $branchID = $this->uri->segment(5);
        $whID = $this->uri->segment(6);



        $mdlName2 = "MdlLockerStock";
        $this->load->model("Mdls/$mdlName2");
        $c = new $mdlName2();

//        $this->db->select("*,produk.id as id");
//        $this->db->join($c->getTableName(), $c->getTableName() . ".produk_id = " . $o->getTableName() . ".id and state='active' and jumlah>0 ");
        $c->addFilter("cabang_id='$branchID'");
        $c->addFilter("gudang_id='$whID'");
        $c->addFilter("produk_id='$prodID'");
        $c->addFilter("state='active'");
        $tmp = $c->lookupAll()->result();
//        die($this->db->last_query());

        $result = 0;
        if (sizeof($tmp) > 0) {
            $result=$tmp[0]->jumlah;
        }
        $this->response($result, 200);
    }

    //==see how muach avail active stock in a product

}

?>