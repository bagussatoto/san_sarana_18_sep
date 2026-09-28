<?php

require_once "Modul_Controller.php";

class _selectorProject extends Modul_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function select()
    {

        $jenisTr = $this->jenisTr;
        $cCode = $this->cCode;
        $mdlName = $this->uri->segment(5);

        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->addFilter("jenis='776wip'");
        $tr->addFilter("link_id=0");
        $tr->addFilter("transaksi_data.produk_jenis='produk'");
        $tr->addFilter("transaksi_data.valid_qty>0");

        if(isset($_GET['search']) && strlen($_GET['search'])>3){
            $tr->addFilter("produk_nama like `%".trim($_GET['search'])."%` OR nomer like `%".trim($_GET['search'])."%`");
        }

        $tmpTr = $tr->lookupJoined()->result();

        $items = array();
        $selectColumn = "produk_nama";
        $processor = base_url() . $this->modul . "/" . $this->configUi[$jenisTr]['projectProcessor'] . "/" . "$jenisTr";
        $pihakView = isset($this->configUi[$jenisTr]['pihakView']) ? $this->configUi[$jenisTr]['pihakView'] : "";

        if(!empty($tmpTr)){
            foreach($tmpTr as $ky => $row){
                $tmpName = isset($row->$selectColumn) ? $row->$selectColumn : "";
                if (isset($row->produk_nama)) {
                    $tmpName = $row->$selectColumn;
                }
                if (strlen($tmpName) > 1) {
                    if (in_array($selectColumn, arrAvailFields())) {
                        $newTmpName = formatNota($selectColumn, $tmpName);
                    }
                    else {
                        $newTmpName = $tmpName;
                    }
                    $items[] = array(
                        "id" => $row->id_master,
                        "nomer" => $row->nomer,
                        "label" => $newTmpName,
                        "lab_enc" => base64_encode($newTmpName),
                        "target" => $processor,
                        "label_view" => isset($row->nomer) ? $row->nomer : "",
                        "label_dtime" => isset($row->dtime) ? $row->dtime : "",
                    );
                }
            }
        }

        $data = array(
            "mode" => "viewProject",
            "items" => $items,
        );

        $this->load->view("_selectorPihakProject", $data);
    }

}