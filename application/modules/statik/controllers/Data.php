<?php

defined('BASEPATH') OR exit('No direct script access allowed');


/* --------------------------------------------------
 * error php dihidden untuk memunculkanya matikan saja
 * ----------------------------------------------*/

if (!isset($_GET['debug_index']) || $_GET['debug_index'] != 1) {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(-1);
}
else {
    // 1 untuk menampilkan error // .... &debug_index=1
    ini_set('display_errors', 1);
}
// ini_set('display_errors', 1);

// --------------------------------------------------

class Data extends CI_Controller
{
    protected $searchString;
    private $syncJsonResponseSent = false;
    private $syncLastStep = "";

    private $allowView = false;
    private $allowCreate = false;
    private $allowEdit = false;
    private $allowDelete = false;
    private $allowViewHistory = false;

    private $creatorUsingApproval = false;
    private $updaterUsingApproval = false;
    private $deleterUsingApproval = false;

    private $relations = array();
    private $relationPairs = array();
    private $listedFields = array();
    protected $sessionDatas;

    public function getSessionDatas()
    {
        return $this->sessionDatas;
    }

    public function setSessionDatas($sessionDatas)
    {
        $this->sessionDatas = $sessionDatas;
    }

    public function getSearchString()
    {
        return $this->searchString;
    }

    public function setSearchString($searchString)
    {
        $this->searchString = $searchString;
    }

    public function isAllowView()
    {
        return $this->allowView;
    }

    public function setAllowView($allowView)
    {
        $this->allowView = $allowView;
    }

    public function isAllowCreate()
    {
        return $this->allowCreate;
    }

    public function setAllowCreate($allowCreate)
    {
        $this->allowCreate = $allowCreate;
    }

    public function isAllowEdit()
    {
        return $this->allowEdit;
    }

    public function setAllowEdit($allowEdit)
    {
        $this->allowEdit = $allowEdit;
    }

    public function isAllowDelete()
    {
        return $this->allowDelete;
    }

    public function setAllowDelete($allowDelete)
    {
        $this->allowDelete = $allowDelete;
    }

    public function isCreatorUsingApproval()
    {
        return $this->creatorUsingApproval;
    }

    public function setCreatorUsingApproval($creatorUsingApproval)
    {
        $this->creatorUsingApproval = $creatorUsingApproval;
    }

    public function isUpdaterUsingApproval()
    {
        return $this->updaterUsingApproval;
    }

    public function setUpdaterUsingApproval($updaterUsingApproval)
    {
        $this->updaterUsingApproval = $updaterUsingApproval;
    }

    public function isDeleterUsingApproval()
    {
        return $this->deleterUsingApproval;
    }

    public function setDeleterUsingApproval($deleterUsingApproval)
    {
        $this->deleterUsingApproval = $deleterUsingApproval;
    }

    public function __construct()
    {
        parent::__construct();
        // arrPrintPink(url_segment());
        $this->segment_2 = ucwords($this->uri->segment(2));
        $this->segment_3 = ucwords($this->uri->segment(3));
        $this->segment_4 = ucwords($this->uri->segment(4));
        $this->segment_5 = ucwords($this->uri->segment(5));
        $this->className = $className = "Mdl" . $this->segment_4;
        // Endpoint sync job memakai segment_4 sebagai job_id (angka), bukan nama model.
        // Hindari inisialisasi model default constructor agar tidak mencoba load model seperti "Mdl5".
        $actionName = strtolower((string)$this->uri->segment(3));
        if (in_array($actionName, array("sync_job_start", "sync_job_process", "sync_job_status"), true)) {
            return;
        }
        $this->load->helper("he_access_right");
        $this->load->library("MobileDetect");

        //region relation translator
        $this->relations = array();
        $this->relationPairs = array();
        if (file_exists(APPPATH . "models/Mdls/$className.php")) {
            $this->load->model("Mdls/" . $className);
            $o = new $className();
            $fields = $o->getFields();
            foreach ($fields as $fName => $f2Spec) {
                if (isset($f2Spec['reference'])) {
                    $this->relations[$f2Spec['kolom']] = $f2Spec['reference'];
                    $this->load->model("Mdls/" . $f2Spec['reference']);
                    $o3 = new $f2Spec['reference']();
                    $tmp3 = $o3->lookupAll()->result();
                    // showLast_query("biru");
                    if (sizeof($tmp3) > 0) {
                        $mdlName = $f2Spec['kolom'];
                        $this->relationPairs[$mdlName] = array();
                        foreach ($tmp3 as $row3) {
                            $idxField = (null != $o3->getIndexFields()) ? $o3->getIndexFields() : "id";
                            $id = isset($row3->$idxField) ? $row3->$idxField : 0;
                            $name = isset($row3->nama) ? $row3->nama : "";
                            if (isset($row3->name)) {
                                $name = $row3->name;
                            }
                            $this->relationPairs[$mdlName][$id] = $name;
                        }
                    }
                    else {
                        //                        cekmerah("$fName TIDAK ketemu data relasinya");
                    }
                }
            }
        }
        //endregion

        // $dataAccess = isset($this->config->item('heDataBehaviour')[$className]) ? $this->config->item('heDataBehaviour')[$className] : array(
        //     "viewers"        => array(),
        //     "creators"       => array(),
        //     "creatorAdmins"  => array(),
        //     "updaters"       => array(),
        //     "updaterAdmins"  => array(),
        //     "deleters"       => array(),
        //     "deleterAdmins"  => array(),
        //     "historyViewers" => array(),
        // );

        //region custom hak akses data
        $dataAccess = array();
        $customDataAkses = availMenuData($this->session->login["id"]);
        //        arrPrint($customDataAkses);
        $dataMemberAllowed = isset($customDataAkses["fungsi"]) ? $customDataAkses["fungsi"] : array();
        //        arrPrint($dataMemberAllowed);
        if (isset($dataMemberAllowed[$className])) {
            foreach ($dataMemberAllowed[$className] as $fs => $fsMember) {
                $dataAccess[$fs] = $fsMember;
            }
        }
        //endregion
        //         arrPrintHijau($dataAccess);
        // matiHere(__LINE__ . __FUNCTION__);
        $ctrlName = $this->uri->segment(4);
        $menus = isset($this->config->item('menuConfig')['data']) ? $this->config->item('menuConfig')['data'] : array();
        // $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();

        if (isset($dataAccess['view'])) {
            if (sizeof($menus) > 0) {
                foreach ($menus as $m => $rowSpec) {
                    if (!in_array($dataAccess['view'], $mems)) {
                        $this->pageMenu .= "<li><a href='" . base_url() . "$m'><span class='glyphicon glyphicon-hdd'></span>$rowSpec</a> </li>";
                    }
                }
                $this->pageMenu .= "<li><a href='authLogout'><span class='glyphicon glyphicon-off'>Keluar</a></li>";
            }
        }

        // $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();


        $arrayAllowed = array(
            "1" => "allowCreate",
            "2" => "allowEdit",
            "3" => "allowDelete",
            "4" => "allowView",
            "5" => "allowViewHistory",
        );
        foreach ($arrayAllowed as $akses => $aksesFungsi) {
            if (array_key_exists($akses, $dataAccess)) {
                // cekHere($aksesFungsi);
                $this->$aksesFungsi = true;
            }
        }

        //         if(array_key_exists("creators",$dataAccess)){
        //             $this->allowCreate = true;
        //         }
        //         if (sizeof($dataAccess['creatorAdmins']) > 0) {
        //             $this->creatorUsingApproval = true;
        //         }
        //         else {
        //             $this->creatorUsingApproval = false;
        //         }
        //         if (sizeof($dataAccess['updaterAdmins']) > 0) {
        //             $this->updaterUsingApproval = true;
        //         }
        //         else {
        //             $this->updaterUsingApproval = false;
        //         }
        //         if (sizeof($dataAccess['deleterAdmins']) > 0) {
        //             $this->deleterUsingApproval = true;
        //         }
        //         else {
        //             $this->deleterUsingApproval = false;
        //         }

        //---init listed-fields
        if (file_exists(APPPATH . "models/Mdls/$className.php")) {
            $this->load->model("Mdls/" . $className);
            $this->mdl = $o = new $className;
            $this->listedFields = $o->getListedFields();
            $this->fields = $o->getFields();
            $this->fieldKoloms = array();
            foreach ($this->fields as $field => $params) {
                $this->fieldKoloms[] = $params["kolom"];
            }

            $mb = new MobileDetect();
            $isMob = $mb->isMobile();
            if ($isMob) {
                $this->listedFields = $o->getCompactListedFields();
            }
        }
    }

    public function add()
    {
        // arrPrintHijau($_GET);
        $get_main = isset($_GET['main']) ? "?main=" . $_GET['main'] : "";
        $get_kval = isset($_GET['kval']) ? "&kval=" . $_GET['kval'] : "";
        $content = "";
        //        include_once 'leftMenu.php';
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
            die();
        }
        //==menampilkan form penambahan data berdasarkan datamodel (kelas data) yang bersesuaian
        $segment_4 = $this->uri->segment(4);
        $className = "Mdl" . $segment_4;
        //                cekBiru($className);
        //                arrPrint($_SESSION);
        $ctrlName = $segment_4;
        /* ----------------------------------------
         * untuk wizard persiapan data
         * ditambahakan di action form
         * ----------------------------------------*/
        $strSegment4 = "";
        if (isset($segment_4)) {
            $strSegment4 = "/$segment_4";
        }
        // -----------------------------------------

        /* -------------------------------------
         * auto select saat sudah memilih pihak
         * --------------------------------*/
        $cCode = isset($_GET['cCode']) ? $_GET['cCode'] : "";
        //        $pihakId = isset($_GET['pihakId']) ? $_GET['pihakId'] : "";
        $pihakId = isset($_GET['reqVal']) ? $_GET['reqVal'] : "";
        $sID = isset($_GET['sID']) ? $_GET['sID'] : "";

        if ($pihakId > 0) {
            $pihakID = $pihakId;
        }
        else {
            $pihakID = isset($_SESSION[$cCode]['main']['pihakID']) ? $_SESSION[$cCode]['main']['pihakID'] : "";
        }
        //---------------------------------------------
        //        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();

        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();

        if (!$this->allowCreate) {
            $p = new Layout(get_class($this), "Wewenang ditolak", "application/template/blank.html");
            $content .= ("<div class='alert alert-danger'>");
            $content .= ("Anda tidak punya wewenang pada halaman ini<br>");
            $content .= ("<a href='" . base_url() . "'>Ke depan</a>");
            $content .= ("</div>");
            echo $content;
            die();
        }
        $this->load->model("Mdls/" . $className);
        $o = new $className;
        // cekHere($className);
        $maximumData = $o->getMaximumData();

        // cekBiru("$maximumData");
        $f = new MyForm($o, "add", array(
            "id"      => "f1add_" . $className,
            "method"  => "post",
            "enctype" => "multipart/form-data",
            "action"  => base_url() . get_class($this) . "/addProcess/$ctrlName",
            "target"  => "result",
            "class"   => "form-inline",
        ));
        /* -----------------------------------------------
         * selected value pada comco
         * ------ --------------------------------------*/
        cekKuning($className);
        $fields = $o->getFields();
        // arrPrint($fields);
        $xxx = array();
        foreach ($fields as $keyField => $field2) {
            $defaultValue = isset($field2['defaultValue']) ? $field2['defaultValue'] : "";
            if (isset($field2['defaultValue'])) {
                $refe = isset($field2['reference']) ? $field2['reference'] : "";
                if (strlen($refe) > 3) {

                    $xxx[$refe][0] = (object)array($field2['defaultValue'] => $pihakID);
                }
            }
            // else{
            //     $xxx = array();
            // }
        }
        // $xxx[$refe][0] = (object)array($field2['defaultValue'] => $pihakID);
        //         arrPrintHijau($xxx);
        // ----------------------------------------

        $pf = isset($_GET['pfid']) ? trim($_GET['pfid']) : "";

        $getPaket = "";
        if ($segment_4 == "Produk_Paket_Project") {
            $getPaket = $get_main == "" ? "&paket=1" : "";
        }

        // arrPrint($pf);
        $f->openForm(MODUL_PATH . get_class($this) . "/addProcess/$ctrlName?1=1" . $strSegment4 . $get_main . $get_kval . $getPaket);
        $f->fillForm($className, $xxx, $pf);
        // matiHere(__METHOD__);
        // matiHere();
        $f->closeForm();
        // matiHEre();
        $realObjName = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : get_class($this);
        $title = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : get_class($this);
        $p = new Layout($title, "Penambahan Data $title", "application/template/lte/index.html");

        //        $content .= ($f->getContent());

        $content .= "<div class='panel panel-success'>";
        $content .= "<div class='panel-heading'>";

        $content .= "<span class='text-blue text-uppercase'><span class='fa fa-folder-open'> main editor</span>";
        $content .= "</div>";

        $content .= "<div class='panel-body'>";

        $content .= ($f->getContent());

        if ($this->creatorUsingApproval) {
            $content .= ("<div class='panel-body'>");
            $content .= ("<div class='alert alert-warning-dot text-center'>");
            $content .= ("This action will require approval");
            $content .= ("</div>");
            $content .= ("</div class='panel-body'>");
        }

        $content .= "</div>";
        $content .= "</div>";
        $content .= "<div id='form_loader'></div>";
        $content .= "<script>
            function cek_master_data(x,i,t,n)
            {               
                let url = t + '?key='+x+'&kolom='+n;                
                $('#notif' + i).load(url);
            }
            
            function cek_kolom_data(x,i,t,n) {
              if(n == 'barcode'){
                  let nilai_master = $('input[name=kode]').val();
                  
                  $('input[name=barcode]').val(nilai_master);
              }
            }
            
            function copy_to(n,x) {
              $('input[name=' + n + ']').val(x);
            }
            
            function toggleInput(i,n) {
                console.log('id: ',i);
             
                var yesRadio = document.getElementById('lbl_kategori_id2');
                var inputField = document.getElementById('_'+n);
                
                if (yesRadio.checked) {
                    inputField.disabled = false; // Enable input text
                } else {
                    inputField.disabled = true; // Disable input text
                }
            }
            
            document.addEventListener(\"DOMContentLoaded\", function() {
                document.getElementById(\"lbl_kategori_id1\").addEventListener(\"click\", function() {
                    toggleInput(this.id, 'no_ktp');
                });
            });
        </script>";

        $data = array(
            "mode"     => $this->uri->segment(2),
            "title"    => "Data $ctrlName",
            "subTitle" => "Create new $ctrlName",
            "content"  => $content,
        );
        echo $content;

        if ($segment_4 == "ProdukRakitanBiayaPaket") {
            echo "
            <script>
                console.log('ProdukRakitanBiayaPaket');
                setTimeout(function(){
                    top.$('select#_produk_id').val($sID);
                    top.$('.selectpicker').selectpicker('refresh');
                }, 500);
            </script>
            ";
        }

        if ($segment_4 == "Produk_Paket_Project" && $_GET['auto'] == 1) {
            $segment_5 = $this->uri->segment(5);
            echo "
            <script>
                console.log('Produk_Paket_Project sID: $segment_5');
                setTimeout(function(){
                    top.$('select#_project_id').val($segment_5);
                    top.$('.selectpicker').selectpicker('refresh');
                }, 500);
            </script>
            ";
        }

        die();
        //        $this->load->view('data', $data);

    }

    //INI KATEGORI UTAMA
    public function addProduk()
    {

        $segment_4 = $this->uri->segment(4);
        $className = "Mdl" . $segment_4;
        // $kval = $_GET['kval'];
        // $this->load->model("Mdls/" . $className);
        // $o = new $className;
        $this->load->model("Mdls/MdlProduk");
        $o = new MdlProduk();

        $xclass = $this->segment_4;

        $kat = "";

        if ($className == "MdlProdukForProject") {
            $kat .= "<div class='container-fluid'>";
            $kat .= "<div class='row'>";
            $kat .= "<div class='col-md-3'>";
            $kat .= "<select id='asdasd' data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchangexx=\"\">";
            $kat .= "<option selected value='0'>NEW PRODUK PROJECT</option>";
            $kat .= "<option value='1'>TAMBAH DARI PRODUK REGULAR</option>";
            $kat .= "</select>";
            $kat .= "</div>";
            $kat .= "<div class='col-md-9'>&nbsp;";
            $kat .= "</div>";
            $kat .= "</div>";

            $kat .= "";

            $kat .= "
                <script>
                    top.$('.selectpicker').selectpicker();
                    top.$('#asdasd').on('change', function(){
                        if( $(this).val()*1 ){
                            $('#kategori_utama').addClass('hidden');
                            $('.kategori_project').removeClass('hidden');

                        }
                        else{
                            $('#kategori_utama').removeClass('hidden');
                            $('.kategori_project').addClass('hidden');
                        }
                    })
                </script>
            ";
        }

        $kat .= "<div class='row'>";
        $kat .= "<div class='well'>";
        $kat .= "<div class='col-sm-3 kategori_project hidden' id=''>";
        $kat .= "<select id='regular_produk' data-style=\"btn btn-sm btn-primary\" data-width='100%' class=\"selectpicker\" data-live-search=\"true\" onchangexx=\"\">";
        $kat .= "<option selected value='0'>===PILIH DARI PRODUK REGULER===</option>";
        $kat .= "</select>";
        $kat .= "</div>";

        $kat .= "<div class='col-sm-9 kategori_project hidden' id=''>&nbsp;";
        $kat .= "</div>";
        $kat .= "</div>";
        $kat .= "</div>";

        $kat .= "<div class='row'>";
        $kat .= "<div class='col-sm-12' id='kategori_utama'>";
        $kat .= "<r><b>PILIH KATEGORI UTAMA: &nbsp;&nbsp;&nbsp;&nbsp;</b></r>";

        $kategories = $o->getMasterFields();
        $no = 0;
        foreach ($kategories as $item => $item_val) {
            $no++;
            $item_jml_serial = isset($item_val['jml_serial']) ? $item_val['jml_serial'] : 0;
            $item_label = isset($item_val['label']) ? $item_val['label'] : $item;
            $item_mdl = isset($item_val['mdl']) ? $item_val['mdl'] : "";
            $item_kategori = isset($item_val['kategori']) ? $item_val['kategori'] : "none";

            if (empty($item_val['anakan'])) {
                //mode radio
                $kat .= "<label style='padding-right: 10px;' class='text-uppercase'><input class='flat-redx' type='radio' name='$item_kategori' id='$item' onclick=\"loadSubMain('$item','$item_mdl');\">&nbsp;$item_label</label>";
                //mode button
                //                $kat .= "<button type='button' id='$item' class='btn btn-info text-uppercase' onclick=\"loadSubMain('$item','$item_mdl');\">$item_label</button> ";
            }
            else {
                //$kat .= "<button type='button' id='$item' class='btn btn-primary text-uppercase' onclick=\"loadAnakan('$item','$item_jml_serial');\">$item_label</button> ";
            }
        }
        $kat .= "<div style='margin-top: 5px;' id='wadah_sub'></div>";
        $kat .= "<hr>";

        $var = "";
        $var .= $kat;
        $var .= "<div id='wadah_1'></div>";
        $var .= "</div>";
        $var .= "</div>";
        $var .= "</div>";

        $kval = isset($_GET['kval']) ? $_GET['kval'] : "";
        $kn = isset($kategories[$kval]['jml_serial']) ? $kategories[$kval]['jml_serial'] : "";
        $link_form = MODUL_PATH . "Data/addProdukForm/$xclass";
        $link_sub = MODUL_PATH . "Data/addProdukSub/$xclass";
        $var .= "<script>
            var kval = '$kval', kn = '$kn';
            if(kval != ''){
                $('#wadah_1').load('$link_form?kval='+ kval +'&n=' + kn);
            };
            function loadAnakan(x,n) {
              $('#wadah_sub').html('');
              $('#wadah_1').load('$link_form?kval='+ x +'&n=' + n);
            }
            function loadSubMain(x,n) {
              $('#wadah_sub').load('$link_sub?kval='+ x +'&n=' + n);
              $('#wadah_1').html('');
            }
        </script>";

        echo $var;
    }

    public function addProdukForm()
    {

        $ly = new Layout();
        $ly->setFormGroupLeftClass("col-sm-2 text-uppercase");
        $ly->setFormGroupRightClass("col-sm-10");

        $get_main = isset($_GET['main']) ? "?main=" . $_GET['main'] : "";
        $segment_4 = $this->uri->segment(4);
        $ctrlName = $segment_4;
        $className_main = $className = "Mdl" . $segment_4;
        $kval = $_GET['kval'];
        $kn = $_GET['n'];

        $this->load->model("Mdls/MdlProduk");
        $o = new $className;
        $fields = $o->getFields();
        $masterFields = $o->getMasterFields();

        $anakans = $masterFields[$kval]['anakan'];
        $field_2 = array_intersect_key($fields, array_flip($anakans));

        $fvar = "";
        $varForm = "";
        $strForm = "";
        foreach ($anakans as $anakan_ky) {
            $coSpeks = $fields[$anakan_ky];
            $inputType = $coSpeks['inputType'];
            $label = $coSpeks['label'];
            $defaultValue = isset($coSpeks['defaultValue']) ? $coSpeks['defaultValue'] : "-";
            $fValue = $defaultValue;
            $kolom = isset($coSpeks['kolom']) ? $coSpeks['kolom'] : "";
            $kolom_nama = isset($coSpeks['kolom_nama']) ? $coSpeks['kolom_nama'] : "";
            $fName = $kolom_nama != "" ? $kolom_nama : $kolom;

            if (isset($coSpeks['reference'])) {
                $reference = $coSpeks['reference'];
                $referenceClass = $reference ? substr($reference, 3) : "";
                $this->load->model("Mdls/" . $reference);
                $o2 = new $reference;
                $o2->setSortBy(array("kolom" => "id", "mode" => "desc"));
                $dataSrcs = $o2->lookupAll()->result();
                $dataSources = array();
                foreach ($dataSrcs as $key_src => $label_src) {
                    $relId = isset($label_src->id) ? $label_src->id : "";
                    $relLabel = isset($label_src->nama) ? $label_src->nama : "";
                    $dataSources[$relId] = $relLabel;
                }
            }
            else {
                $dataSources = isset($coSpeks['dataSource']) ? $coSpeks['dataSource'] : "";
            }

            $fvar .= "$anakan_ky";

            switch ($inputType) {
                case "combo":
                    $reference_label = strtoupper($label);
                    $link_add = base_url() . "statik/Data/add/$referenceClass?main=$className_main&kval=$kval";
                    $link_add_act = modalDialogBtn("New $reference_label", $link_add, 0);
                    $btn_add = isset($coSpeks['add_btn']) ? "<span class='input-group-btn'><button type='button' class='btn btn-warning' onclick=\"$link_add_act\"><i class='fa fa-plus'></i></button></span>" : "<span></span>";
                    $optionals = "<option value=''>----$label------</option>";
                    foreach ($dataSources as $key_src => $label_src) {
                        $fSelected = $fValue == $key_src ? "selected" : "";
                        $optionals .= "<option value='$key_src' $fSelected>$label_src</option>";
                    }
                    $varForm = "<div class='input-group input-group-sm'>";
                    $varForm .= "<select class='form-control' name='$kolom'>";
                    $varForm .= $optionals;
                    $varForm .= "</select>";
                    $varForm .= $btn_add;
                    $varForm .= "</div>";
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "radio":
                    $varForm = "";
                    foreach ($dataSources as $key_src => $label_src) {
                        $varForm .= "<label><input type='radio' id='$anakan_ky" . "_" . "$key_src' name='$kolom_nama' value='$key_src'> $label_src</label>";
                    }
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "text":
                    $varForm = "<input type='text' id='$anakan_ky' name='$kolom' class='form-control' value='$fValue'>";
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "number":
                    $varForm = "<input type='number' id='$anakan_ky' name='$kolom' class='form-control' value='$fValue'>";
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "hidden":
                    $fValue = $kn;
                    $varForm = "<input type='text' id='$anakan_ky' name='$kolom' value='$fValue'>";
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
            }
        }

        /* -------------------------------------------------------------------------------------
         * button sumit
         * -------------------------------------------------------------------------------------*/
        $strButton = "";
        $strButton .= "<div class='col-md-12' style='margin-top: 25px;border-top: #f9f7f7 solid 1px;padding-top: 10px;'>";
        $strButton .= "<button type='button' class='btn btn-default text-uppercase pull-left' data-dismiss='modal'>close</button>";
        $strButton .= "<button type='submit' class='btn btn-danger text-uppercase pull-right'>simpan</button>";
        $strButton .= "</div>";

        /* -------------------------------------------------------------------------------------
         * penampil form di UI
         * -------------------------------------------------------------------------------------*/
        $link_action = MODUL_PATH . "Data/addProcess/Produk/Produk";
        $var = "";
        $var .= "<style type='text/css'>
            .form-group{
                margin-bottom: 2px !important;
            }
        </style>";
        $var .= "<div class='row'>";
        $var .= "<form method='post' action='$link_action' target='result' style='margin-top: 0px;'>";
        $var .= $strForm;
        $var .= $strButton;
        $var .= "</form>";
        $var .= "</div>";

        echo $var;
    }

    // INI SUB KATEGORI
    public function addProdukSub()
    {
        $segment_4 = $this->uri->segment(4);
        $className = "Mdl" . $segment_4;
        $kval = isset($_GET['kval']) ? $_GET['kval'] : "";
        $this->load->model("Mdls/MdlProduk");
        $o = new MdlProduk();

        $xclass = $this->segment_4;
        $kat = "";
        $kat .= "<r><b>PILIH SUB KATEGORI: &nbsp;&nbsp;&nbsp;&nbsp;</b></r>";

        $masterKategory = $o->getMasterFields();
        $kategories = $o->getMasterSubs();

        foreach ($masterKategory as $item => $item_val) {
            $label = $item_val['label'];
            if ($kval == $item) {
                //                $kat .= "<span id='$item' style='font-size: 14px;' class='text-uppercase text-bold text-red'><u>$label&nbsp;&nbsp;&nbsp;</u></span> ";
            }
        }

        $no = 0;
        foreach ($kategories as $item => $item_val) {
            $no++;
            $item_jml_serial = isset($item_val['jml_serial']) ? $item_val['jml_serial'] : 0;
            $item_kategori = isset($item_val['kategori']) ? $item_val['kategori'] : 0;
            $item_label = isset($item_val['label']) ? $item_val['label'] : $item;
            $type = isset($item_val['type']) ? $item_val['type'] : "";
            $id = isset($item_val['id']) ? $item_val['id'] : "";
            $sub_kategori_id = isset($item_val['sub_kategori_id']) ? $item_val['sub_kategori_id'] : "";
            $title = isset($item_val['title']) ? $item_val['title'] : "";
            if ($type == $kval) {
                $kat .= "<label style='padding-right: 10px;' class='text-uppercase'><input style='margin-top: 6px;' type='radio' name='$type' id='$id' title='$title' class='flat-redx btn-sub' onclick=\"loadAnakanSub('$id','$sub_kategori_id', '$item_jml_serial','$item_kategori');\">&nbsp;<b>$item_label</b></label>";
                //mode button
                //                $kat .= "<button style='margin-top: 6px;' type='button' id='$item' title='$title' class='btn btn-info text-uppercase btn-sub' onclick=\"loadAnakanSub('$item','$item_jml_serial','$item_kategori');\"><b>$item_label</b> <div class='small text-white'><i>$title</i></div></button> ";
            }
        }

        //$kat .= "<span style='' type='button' id='0' title='add menu' class='btn btn-xs btn-flat btn-warning text-uppercase' onclick=\"addMenuForm('$kval','','');\"><i class='fa fa-plus'></i></span>";

        $kat .= "<div id='' class='sub_title'><div style='margin-top: 25px;' class='text-bold text-red box box-solid box-header box-danger text-center text-uppercase fa-2x'><span class=' blink'>Silahkan Pilih Sub Kategori</span></div></div>";

        $var = "";
        $var .= $kat;

        $kn = isset($kategories[$kval]['jml_serial']) ? $kategories[$kval]['jml_serial'] : "";
        $kk = isset($kategories[$kval]['kategori']) ? $kategories[$kval]['kategori'] : "";
        $link_form = MODUL_PATH . "Data/addProdukFormSub/$xclass";
        $link_addmenu = MODUL_PATH . "Data/addMenuFormSub/$xclass";
        $var .= "<script>
                    function loadAnakanSub(x,s,n,k) {
                      $('#wadah_1').load('$link_form?kval='+ x +'&n=' + n + '&k='+k+'&s='+s);
                      $('.btn-sub').removeClass('on');
                      $('#'+x).addClass('on');
                      $('.sub_title').html('');
                    }
                    function addMenuForm(x,n,k) {
                        top.BootstrapDialog.show(
                           {
                                title:'Add Menu',
                                message: $('<div></div>').load('$link_addmenu?kval='+ x +'&n=' + n + '&k='+k),
                                size: BootstrapDialog.SIZE_WIDE,
                                draggable:false,
                                closable:true,
                            }
                        );
                    }
                </script>";

        echo $var;
    }

    /* ------------------------------------
     * form tambah produk
     * ------------------------------------*/
    public function addProdukFormSub()
    {
        $ly = new Layout();
        $ly->setFormGroupLeftClass("col-sm-2 text-uppercase");
        $ly->setFormGroupRightClass("col-sm-10");
        $get_main = isset($_GET['main']) ? "?main=" . $_GET['main'] : "";
        $segment_4 = $this->uri->segment(4);
        $ctrlName = $segment_4;
        $className_main = $className = "Mdl" . $segment_4;
        $kval = $_GET['kval'];
        $sid = $_GET['s'];
        $kn = $_GET['n'];
        $kk = $_GET['k'];
        $this->load->model("Mdls/MdlProduk");
        $o = new $className;
        $fields = $o->getFields();
        $masterFields = $o->getMasterSubs();

        //        echo json_encode($masterFields);

        $anakans = $masterFields[$kval]['anakan'];
        $sub_kategori = isset($masterFields[$kval]['sub_kategori']) ? $masterFields[$kval]['sub_kategori'] : "";
        $validationRules = $o->getValidationRules();
        $requireds = array();
        foreach ($validationRules as $kolom => $validationRule) {
            if (in_array("required", $validationRule)) {
                $requireds[] = $kolom;
            }
        }

        $field_2 = array_intersect_key($fields, array_flip($anakans));
        $fvar = "";
        $varForm = "";
        $strForm = "";
        foreach ($anakans as $anakan_ky) {
            // arrPrintKuning($anakan_ky);
            $coSpeks = isset($fields[$anakan_ky]) ? $fields[$anakan_ky] : array();
            // ----------------------------------------------
            $mdlChilds = isset($coSpeks['mdlChild']) ? $coSpeks['mdlChild'] : "";
            // $mdlChild = is_array($mdlChilds) && in_array($anakan_ky, $mdlChilds) ? $anakan_ky : "";
            $mdlChild_json = is_array($mdlChilds) ? base64_encode(json_encode($mdlChilds)) : $mdlChilds;
            // arrPrintPink($mdlChilds);
            // arrPrintHijau($coSpeks);
            $inputType = isset($coSpeks['inputType']) ? $coSpeks['inputType'] : "";
            $editable = isset($coSpeks['editable']) && $coSpeks['editable'] == false ? "readonly" : "";
            // $show = $label = isset($coSpeks['show']) && $coSpeks['show'] == true ? "" : "style='display:none;'";
            $show = isset($coSpeks['show']) ? "1" : "0";
            $str_label = $label = isset($coSpeks['label']) ? $coSpeks['label'] : "";
            $required_field = "";
            $onChange = "";
            if (in_array($anakan_ky, $requireds)) {
                $label .= "&nbsp; <r>*</r>";
                $required_field = "required";
                // $onChange = "onchange=\"createSession(this, '$mdlChild_json');\"";
            }
            else {
                $required_field = "";
            }
            $onChange = "onchange=\"createSession(this, '$mdlChild_json');\"";
            // cekMerah("$anakan_ky ==== $required_field");

            $defaultValue = isset($coSpeks['defaultValue']) ? $coSpeks['defaultValue'] : "";
            $fValue = $defaultValue;
            // ----------------------------------------------
            $kolom = isset($coSpeks['kolom']) ? $coSpeks['kolom'] : "";
            $kolom_nama = isset($coSpeks['kolom_nama']) ? $coSpeks['kolom_nama'] : "";
            $fName = $kolom_nama != "" ? $kolom_nama : $kolom;

            $strField = isset($coSpeks['strField']) ? $coSpeks['strField'] : "nama";
            $indexFields = isset($coSpeks['indexFields']) ? $coSpeks['indexFields'] : "id";
            $referenceClass = "";
            if (isset($coSpeks['reference'])) {
                $reference = $coSpeks['reference'];
                $referenceClass = $reference ? substr($reference, 3) : "";
                $this->load->model("Mdls/" . $reference);
                $o2 = new $reference;
                $o2->setSortBy(array("kolom" => "id", "mode" => "desc"));
                if (isset($_SESSION['data']['supplier_id'])) {
                    $condites = array(
                        "supplier_id" => $_SESSION['data']['supplier_id'],
                    );
                }
                $dataSrcs = $o2->lookupAll()->result();
                $dataSources = array();
                foreach ($dataSrcs as $key_src => $label_src) {
                    $relId = isset($label_src->$indexFields) ? $label_src->$indexFields : "";
                    $relLabel = isset($label_src->$strField) ? $label_src->$strField : "";
                    $dataSources[$relId] = $relLabel;
                }
            }
            else {
                $dataSources = isset($coSpeks['dataSource']) ? $coSpeks['dataSource'] : "";
            }

            $btn_add = "<div></div>";
            if (isset($coSpeks['add_btn'])) {
                $reference_label = strtoupper($label);
                $link_editor_act = base_url() . "statik/Data/viewdt/$referenceClass";
                $link_add = base_url() . "statik/Data/add/$referenceClass?main=$className_main&kval=$kval";
                $link_add_act = modalDialogBtn("New $reference_label", $link_add, 0);
                $btn_add = "<div class='input-group-append'>";
                $btn_add .= "<button type='button' class='btn btn-sm btn-flat btn-warning' onclick=\"$link_add_act\"><i class='fa fa-plus'></i></button>";
                $btn_add .= "<button type='button' class='btn btn-sm btn-flat btn-info' onclick=\"btn_confirm_alert('Upss..!','Anda akan meninggalkan halaman ini? <br><r>Semua data yang belum disimpan akan hilang!</r>','$link_editor_act')\"><i class='fa fa-pencil'></i></button>";
                $btn_add .= "</div>";
            }

            $fvar .= "$anakan_ky";

            switch ($inputType) {
                case "combo-blank":
                    // $reference_label = strtoupper($label);
                    // $link_add = base_url() . "statik/Data/add/$referenceClass?main=$className_main&kval=$kval";
                    // $link_add_act = modalDialogBtn("New $reference_label", $link_add, 0);
                    // $btn_add = isset($coSpeks['add_btn']) ? "<div class='input-group-append'><button type='button' class='btn btn-sm btn-flat btn-warning' onclick=\"$link_add_act\"><i class='fa fa-plus'></i></button></div>" : "<div></div>";
                    $optionals = "<option value=''> silahkan pilih data sebelumnya </option>";
                    $varForm = "<div class='input-group input-group-sm'>";
                    $varForm .= "<select kval='$kval' data-style='btn btn-sm btn-danger' data-live-search='false' data-headers='' data-size='10' data-container='body' class='selectpicker form-controlx select2' $required_field ky='$anakan_ky' name='$kolom' $onChange>";
                    $varForm .= $optionals;
                    $varForm .= "</select>";
                    $varForm .= $btn_add;
                    $varForm .= "</div>";
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "combo-hidden":
                    // $reference_label = strtoupper($label);
                    // $link_add = base_url() . "statik/Data/add/$referenceClass?main=$className_main&kval=$kval";
                    // $link_add_act = modalDialogBtn("New $reference_label", $link_add, 0);
                    // $btn_add = isset($coSpeks['add_btn']) ? "<div class='input-group-append'><button type='button' class='btn btn-sm btn-flat btn-warning' onclick=\"$link_add_act\"><i class='fa fa-plus'></i></button></div>" : "<div></div>";
                    $optionals = "<option value=''> Pilih $str_label </option>";
                    foreach ($dataSources as $key_src => $label_src) {
                        $fSelected = $fValue == $key_src ? "selected" : "";
                        $optionals .= "<option class='text-uppercase' value='$key_src' $fSelected>$label_src</option>";
                    }
                    // $eventSession = $this->createSessionData();
                    $varForm = "<div class='input-group input-group-sm'>";

                    if (count($dataSources) == 0) {
                        $optionals = "<option value=''> SILAHKAN TAMBAHKAN DATA </option>";
                        $varForm .= "<select kval='$kval' data-style='btn btn-sm btn-danger' data-live-search='false' data-headers='' data-size='10' data-container='body' class='selectpicker form-controlx select2' $required_field ky='$anakan_ky' name='$kolom' $onChange>";
                    }
                    else {
                        $varForm .= "<select kval='$kval' data-style='btn btn-sm btn-primary' data-placeholder='cari data' data-live-search='true' data-headers='' data-size='10' data-container='body' class='selectpicker form-controlx select2 show-tick' $required_field ky='$anakan_ky' name='$kolom' $onChange>";
                    }

                    $varForm .= $optionals;
                    $varForm .= "</select>";
                    $varForm .= $btn_add;
                    $varForm .= "</div>";
                    $strForm .= $ly->form_group($label, $varForm, 1);
                    break;
                case "combo":
                    // $reference_label = strtoupper($label);
                    // $link_add = base_url() . "statik/Data/add/$referenceClass?main=$className_main&kval=$kval";
                    // $link_editor_act = base_url() . "statik/Data/viewdt/$referenceClass";
                    // $link_add_act = modalDialogBtn("New $reference_label", $link_add, 0);
                    // $btn_add = isset($coSpeks['add_btn']) ? "<div class='input-group-append'><button type='button' class='btn btn-sm btn-flat btn-warning' onclick=\"$link_add_act\"><i class='fa fa-plus'></i></button><button type='button' class='btn btn-sm btn-flat btn-info' onclick=\"location.href='$link_editor_act'\"><i class='fa fa-pencil'></i></button></div>" : "<div></div>";
                    $optionals = "<option value=''> Pilih $str_label </option>";
                    foreach ($dataSources as $key_src => $label_src) {
                        $fSelected = $fValue == $key_src ? "selected" : "";
                        $optionals .= "<option class='text-uppercase' value='$key_src' $fSelected>$label_src</option>";
                    }
                    // $eventSession = $this->createSessionData();
                    $varForm = "<div class='input-group input-group-sm'>";

                    if (count($dataSources) == 0) {
                        $optionals = "<option value=''> SILAHKAN TAMBAHKAN DATA </option>";
                        $varForm .= "<select kval='$kval' data-style='btn btn-sm btn-danger' data-live-search='false' data-headers='' data-size='10' data-container='body' class='selectpicker form-controlx select2' $required_field ky='$anakan_ky' name='$kolom' $onChange>";
                    }
                    else {
                        $varForm .= "<select kval='$kval' data-style='btn btn-sm btn-primary' data-placeholder='cari data' data-live-search='true' data-headers='' data-size='10' data-container='body' class='selectpicker form-controlx select2 show-tick' $required_field ky='$anakan_ky' name='$kolom' $onChange>";
                    }

                    $varForm .= $optionals;
                    $varForm .= "</select>";
                    $varForm .= $btn_add;
                    $varForm .= "</div>";
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "radio":
                    $varForm = "";
                    foreach ($dataSources as $key_src => $label_src) {
                        $varForm .= "<label><input class='flat-redx' type='radio' id='$anakan_ky" . "_" . "$key_src' name='$kolom_nama' value='$key_src'> $label_src</label>";
                    }
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "text":
                    $varForm = "<div class='form-group row'>";
                    $varForm .= "<div class='col-md-5'>";
                    $varForm .= "<input type='text' $required_field $editable id='$anakan_ky' autocomplete='off' placeholder='$str_label' name='$kolom' class='form-control' value='$fValue'>";
                    $varForm .= "</div>";
                    $varForm .= "</div>";

                    $strForm .= $ly->form_group($label, $varForm, $show);
                    break;
                case "number":
                    $varForm = "<input type='number' id='$anakan_ky' name='$kolom' class='form-control' value='$fValue'>";
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "hidden":
                    $fValue = $kn;
                    $varForm = "<input type='text' id='$anakan_ky' name='$kolom' value='$fValue'>";
                    $strForm .= $ly->form_group($label, $varForm, 1);
                    break;
            }
        }

        if ($kval != "") {
            $varForm = "<input type='text' id='kval' name='kval' value='$kval'>";
            $strForm .= $ly->form_group("", $varForm, 1);
        }

        /* -------------------------------------------------------------------------------------
         * button sumit
         * -------------------------------------------------------------------------------------*/
        $strButton = "";
        $strButton .= "<div class='col-md-12' style='margin-top: 25px;border-top: #f9f7f7 solid 1px;padding-top: 10px;'>";
        $strButton .= "<button type='button' class='btn btn-default text-uppercase pull-left' data-dismiss='modal'>close</button>";
        $strButton .= "<button type='submit' class='btn btn-danger text-uppercase pull-right'>simpan</button>";
        $strButton .= "</div>";

        /* -------------------------------------------------------------------------------------
         * penampil form di UI
         * -------------------------------------------------------------------------------------*/
        $link_action = MODUL_PATH . "Data/addProcess/$segment_4/$segment_4";
        $createSession_link = MODUL_PATH . "Data/createSessionData/Produk";
        $var = "";
        $var .= "<script>
                    function createSession(x,y) {
                        var name = x.name;
                        var val  = x.value;
                        var mode = $(x).attr('kval')
                        var ky = $(x).attr('ky')
                        $.ajax({
                            url: '$createSession_link/'+mode+'/'+name+'?val='+val+'&target='+y+'&ky='+ky,
                            success: function(a){
                                var arrData = JSON.parse(a);
                                var target_id = arrData.datas.target;
                                if(arrData.arrSelect){
                                    jQuery.each(arrData.arrSelect, function(a, b){
                                        $('select[name='+a+']').parent().html(b);
                                        setTimeout(function(){
                                            $('.select2').selectpicker('restart');
                                        },300);
                                        if(arrData.arrSelectJs){
                                            setTimeout( function(){ eval(arrData.arrSelectJs[a]) },200)
                                        }
                                    });
                                }
                            }
                        });
                    }
                </script>";
        $var .= "<style type='text/css'>.form-group{ margin-bottom: 2px !important; }</style>";
        $var .= "<div class='row'>";
        $var .= "<div id='loading'></div>";
        $var .= "<form method='post' action='$link_action' target='result' style='margin-top: 0px;'>";
        $var .= $strForm;
        $var .= $strButton;
        $var .= "</form>";
        $var .= "</div>";

        if ($sub_kategori != "") {
            $var .= "<script>
                    top.$('select[name=sub_kategori_id]').val($sub_kategori).change();
                 </script>";
        }

        //khusus untuk nembak value jml serial
        $var .= "<script>
                    top.$('#jml_serial').val($kn);
                    top.$('select[name=kategori_id]').val($kk).change();
                    top.$('select[name=sub_kategori_id]').val($sid).change();
                    setTimeout(function(){
                        top.$('.select2').selectpicker({dropdownParent:$('body')}).selectpicker();
                        $('input[type=checkbox].flat-red, input[type=radio].flat-red').iCheck({
                            checkboxClass: 'icheckbox_flat-green',
                            radioClass: 'iradio_flat-green'
                        });
                        $('#loading').hide();
                    }, 250);
                 </script>";
        echo $var;
    }

    public function createSessionData()
    {
        $kategori = url_segment(5);
        $kolom = url_segment(6);
        $anakan_ky = isset($_GET['ky']) ? $_GET['ky'] : "";
        $datas = $_GET;

        $this->load->model("Mdls/MdlProduk");
        $o = new MdlProduk();
        $fields = $o->getFields();
        // matiHere();
        $validationRules = $o->getValidationRules();
        // arrPrintPink($validationRules);

        $requireds = array();
        if (sizeof($validationRules) > 0) {
            foreach ($validationRules as $kolomRule => $validationRule) {
                if (in_array("required", $validationRule)) {
                    $requireds[] = $kolomRule;
                }
            }
        }

        $required_field = "";
        // $onChange = "";
        // if (in_array($anakan_ky, $requireds)) {
        //     $required_field = "required";
        //     // $onChange = "onchange=\"createSession(this, '$mdlChild_json');\"";
        // }
        // else {
        //     $required_field = "none";
        // }
        // cekHere($anakan_ky);
        // arrPrintHijau($requireds);
        // cekMerah("$required_field");

        $arrSelect = array();
        $arrSelectJs = array();
        $strSelect = "";

        switch ($kolom) {
            case "supplier_id":
                $arrFields = $fields['supplier'];
                $arrSelector = json_decode(base64_decode($datas['target']));
                if (!empty($arrSelector)) {
                    foreach ($arrSelector as $k) {
                        $strSelect = "";
                        $kol = $fields[$k]['kolom'];
                        //membuat selector MEREK
                        $strSelect .= "<select kval='$kategori' data-style='btn btn-sm btn-primary' data-live-search='true' data-headers='' data-size='120' data-container='body' class='selectpicker form-controlx select2 show-tick' required='' ky='$anakan_ky' name='merek_id' onchange=\"createSession(this, '$kol');\">";
                        $strSelect .= "<option value=''> --- pilih merek --- </option>";
                        $this->load->model("Mdls/MdlMerek");
                        $o2 = new MdlMerek();
                        $o2->addFilter("supplier_id='" . $datas['val'] . "'");
                        $dataSrcs = $o2->lookupAll()->result();
                        $lastQuery = $this->db->last_query();
                        foreach ($dataSrcs as $key_src => $label_src) {
                            $relId = isset($label_src->id) ? $label_src->id : "";
                            $relLabel = isset($label_src->nama) ? $label_src->nama : "";
                            $strSelect .= "<option supplier_id='" . $datas['val'] . "' value='$relId'>$relLabel</option>";
                        }
                        $strSelect .= "</select>";
                        $arrSelect[$kol] = $strSelect;

                        if (count($dataSrcs) == 1) {
                            $idSelect = $dataSrcs[0]->id;
                            $arrSelectJs['merek_id'] = "$('select[name=merek_id]').val($idSelect).trigger('change');";
                            // $arrSelectJs['outdoor_id'] = "$('select[name=outdoor_id]').val($idSelect).trigger('change');";
                        }
                    }
                }

                $_SESSION['data']['supplier_id'] = $datas['val'];
                // $_SESSION['data']['supplier_nama'] = $relLabel;
                // unset($_SESSION['data']);
                break;
            case "merek_id":
                $arrSelector = $fields['merek_nama']['mdlChild'];
                foreach ($arrSelector as $k) {
                    $strSelect = "";
                    $kol = $fields[$k]['kolom'];
                    $label = $fields[$k]['label'];
                    $reference = $fields[$k]['reference'];
                    //membuat selector MEREK
                    $strSelect .= "<select kval='$kategori' data-style='btn btn-sm btn-primary' data-live-search='true' data-headers='' data-size='100' $required_field data-container='body' class='selectpicker form-controlx select2 show-tick' ky='$anakan_ky' name='$kol'>";
                    $strSelect .= "<option value=''> --- pilih $label --- </option>";
                    $this->load->model("Mdls/$reference");
                    $o2 = new $reference;
                    $o2->addFilter("merek_id='" . $datas['val'] . "'");
                    $dataSrcs = $o2->lookupAll()->result();
                    foreach ($dataSrcs as $key_src => $label_src) {
                        $relId = isset($label_src->id) ? $label_src->id : "";
                        $relLabel = isset($label_src->nama) ? $label_src->nama : "";
                        $strSelect .= "<option merk_id='" . $datas['val'] . "' value='$relId'>$relLabel</option>";
                    }
                    $strSelect .= "</select>";
                    $arrSelect[$kol] = $strSelect;
                    // if (count($dataSrcs) == 1) {
                    //     $idSelect = $dataSrcs[0]->id;
                    //     $arrSelectJs[$kol] = "$('select[name=$kol]').val($idSelect).trigger('change');";
                    // }

                    $_SESSION['data'][$kolom] = $datas['val'];
                }
                break;
            case "size_id":
                $arrFields = $fields['skala'];
                $arrSelector = json_decode(base64_decode($datas['target']));
                if (!empty($arrSelector)) {
                    foreach ($arrSelector as $k) {
                        $strSelect = "";
                        $kol = $fields[$k]['kolom'];
                        //membuat selector MEREK
                        $strSelect .= "<select kval='$kategori' data-style='btn btn-sm btn-primary' data-live-search='true' data-headers='' data-size='130' data-container='body' class='selectpicker form-controlx select2 show-tick' name='tipe_id' onchange=\"createSession(this, '$kol');\">";
                        $strSelect .= "<option value=''> --- pilih size/skala --- </option>";
                        $this->load->model("Mdls/MdlTipe");
                        $o2 = new MdlTipe();
                        $o2->addFilter("size_id='" . $datas['val'] . "'");
                        $dataSrcs = $o2->lookupAll()->result();
                        $lastQuery = $this->db->last_query();
                        // showLast_query("merah");
                        foreach ($dataSrcs as $key_src => $label_src) {
                            $relId = isset($label_src->id) ? $label_src->id : "";
                            $relLabel = isset($label_src->nama) ? $label_src->nama : "";
                            $strSelect .= "<option size_id='" . $datas['val'] . "' value='$relId'>$relLabel</option>";
                        }
                        $strSelect .= "</select>";
                        $arrSelect[$kol] = $strSelect;

                        if (count($dataSrcs) == 1) {
                            $idSelect = $dataSrcs[0]->id;
                            $arrSelectJs['tipe_id'] = "$('select[name=tipe_id]').val($idSelect).trigger('change');";
                            // $arrSelectJs['outdoor_id'] = "$('select[name=outdoor_id]').val($idSelect).trigger('change');";
                        }
                    }
                }

                $_SESSION['data']['size_id'] = $datas['val'];
                // unset($_SESSION['data']);
                break;
            case "produk_part_kategori_id":
                $arrSelector = $fields['part_kategori_id']['mdlChild'];
                foreach ($arrSelector as $k) {
                    $strSelect = "";
                    $kol = $fields[$k]['kolom'];
                    $label = $fields[$k]['label'];
                    $reference = $fields[$k]['reference'];
                    //membuat selector .....
                    $strSelect .= "<select kval='$kategori' data-style='btn btn-sm btn-primary' data-live-search='true' data-headers='' data-size='130' data-container='body' class='selectpicker form-controlx select2 show-tick' name=$kol onchange=\"createSession(this, '$kol');\">";
                    // $strSelect .= "<select kval='$kategori' data-style='btn btn-sm btn-primary' data-live-search='true' data-headers='' data-size='100' $required_field data-container='body' class='selectpicker form-controlx select2 show-tick' ky='$anakan_ky' name='$kol'>";
                    $strSelect .= "<option value=''> --- pilih $label --- </option>";
                    $this->load->model("Mdls/$reference");
                    $o2 = new $reference;
                    $o2->addFilter("kategori_id='" . $datas['val'] . "'");
                    $dataSrcs = $o2->lookupAll()->result();
                    foreach ($dataSrcs as $key_src => $label_src) {
                        $relId = isset($label_src->id) ? $label_src->id : "";
                        $relLabel = isset($label_src->nama) ? $label_src->nama : "";
                        $strSelect .= "<option kategori_id='" . $datas['val'] . "' value='$relId'>$relLabel</option>";
                    }
                    $strSelect .= "</select>";
                    $arrSelect[$kol] = $strSelect;
                    if (count($dataSrcs) == 1) {
                        $idSelect = $dataSrcs[0]->id;
                        $arrSelectJs[$kol] = "$('select[name=$kol]').val($idSelect).trigger('change');";
                    }

                    $_SESSION['data'][$kolom] = $datas['val'];
                }
                break;
        }

        $result = array(
            "datas"       => $datas,
            "segment"     => $this->uri->segment_array(),
            "select"      => $strSelect,
            "arrSelect"   => $arrSelect,
            "arrSelectJs" => $arrSelectJs,
        );

        echo json_encode($result);
    }

    public function addMenuFormSub()
    {

        $ly = new Layout();
        $ly->setFormGroupLeftClass("col-sm-2 text-uppercase");
        $ly->setFormGroupRightClass("col-sm-10");

        $kval = $_GET['kval'];
        $kn = $_GET['n'];
        $kk = $_GET['k'];

        $segment_4 = $this->uri->segment(4);
        $ctrlName = $segment_4;
        $className_main = $className = "Mdl" . $segment_4;
        $this->load->model("Mdls/MdlMenuSubStatik");
        $o = new MdlMenuSubStatik();
        $fields = $o->getFields();

        $anakans = array(
            "label",
            "jml_serial",
            "id",
            "kategori",
            "sub_kategori",
        );

        $validationRules = $o->getValidationRules();
        $requireds = array();
        foreach ($validationRules as $kolom => $validationRule) {
            if (in_array("required", $validationRule)) {
                $requireds[] = $kolom;
            }
        }

        $field_2 = array_intersect_key($fields, array_flip($anakans));
        $fvar = "";
        $varForm = "";
        $strForm = "";

        foreach ($anakans as $anakan_ky) {
            $coSpeks = $fields[$anakan_ky];
            $inputType = $coSpeks['inputType'];
            $str_label = $label = $coSpeks['label'];
            $required_field = "";
            $onChange = "";
            if (in_array($anakan_ky, $requireds)) {
                $label .= "&nbsp; <r>*</r>";
                $required_field = "required";
            }
            $defaultValue = isset($coSpeks['defaultValue']) ? $coSpeks['defaultValue'] : "";
            $fValue = $defaultValue;
            // ----------------------------------------------
            $kolom = isset($coSpeks['kolom']) ? $coSpeks['kolom'] : "";
            $kolom_nama = isset($coSpeks['kolom_nama']) ? $coSpeks['kolom_nama'] : "";
            $fName = $kolom_nama != "" ? $kolom_nama : $kolom;

            $strField = isset($coSpeks['strField']) ? $coSpeks['strField'] : "nama";

            if (isset($coSpeks['reference'])) {
                $reference = $coSpeks['reference'];
                $referenceClass = $reference ? substr($reference, 3) : "";
                $this->load->model("Mdls/" . $reference);
                $o2 = new $reference;
                $o2->setSortBy(array("kolom" => "id", "mode" => "desc"));
                if (isset($_SESSION['data']['supplier_id'])) {
                    $condites = array(
                        "supplier_id" => $_SESSION['data']['supplier_id'],
                    );
                }
                $dataSrcs = $o2->lookupAll()->result();
                $dataSources = array();
                foreach ($dataSrcs as $key_src => $label_src) {
                    $relId = isset($label_src->id) ? $label_src->id : "";
                    $relLabel = isset($label_src->$strField) ? $label_src->$strField : "";
                    $dataSources[$relId] = $relLabel;
                }
            }
            else {
                $dataSources = isset($coSpeks['dataSource']) ? $coSpeks['dataSource'] : "";
            }

            $fvar .= "$anakan_ky";

            switch ($inputType) {
                case "combo":
                    $reference_label = strtoupper($label);
                    $link_add = base_url() . "statik/Data/add/$referenceClass?main=$className_main&kval=$kval";
                    $link_add_act = modalDialogBtn("New $reference_label", $link_add, 0);
                    $btn_add = isset($coSpeks['add_btn']) ? "<div class='input-group-append'><button type='button' class='btn btn-sm btn-flat btn-warning' onclick=\"$link_add_act\"><i class='fa fa-plus'></i></button></div>" : "<div></div>";
                    $optionals = "<option value=''> Pilih $str_label </option>";
                    foreach ($dataSources as $key_src => $label_src) {
                        $fSelected = $fValue == $key_src ? "selected" : "";
                        $optionals .= "<option class='text-uppercase' value='$key_src' $fSelected>$label_src</option>";
                    }
                    // $eventSession = $this->createSessionData();
                    $varForm = "<div class='input-group input-group-sm'>";
                    $varForm .= "<select kval='$kval' data-style='btn btn-sm btn-primary' data-live-search='true' data-headers='' data-size='10' data-container='body' class='selectpicker form-controlx select2 show-tick' $required_field name='$kolom' $onChange>";
                    $varForm .= $optionals;
                    $varForm .= "</select>";
                    $varForm .= $btn_add;
                    $varForm .= "</div>";
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "radio":
                    $varForm = "";
                    foreach ($dataSources as $key_src => $label_src) {
                        $varForm .= "<label><input class='flat-redx' type='radio' id='$anakan_ky" . "_" . "$key_src' name='$kolom_nama' value='$key_src'> $label_src</label>";
                    }
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "text":
                    $varForm = "<div class='form-group row'>";
                    $varForm .= "<div class='col-md-5'>";
                    $varForm .= "<input type='text' $required_field id='$anakan_ky' placeholder='$str_label' name='$kolom' class='form-control' value='$fValue'>";
                    $varForm .= "</div>";
                    $varForm .= "</div>";

                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "number":
                    $varForm = "<input type='number' id='$anakan_ky' name='$kolom' class='form-control' value='$fValue'>";
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "hidden":
                    $fValue = $kn;
                    $varForm = "<input type='text' id='$anakan_ky' name='$kolom' value='$fValue'>";
                    $strForm .= $ly->form_group($label, $varForm, 1);
                    break;
            }
        }

        if ($kval != "") {
            $varForm = "<input type='text' id='kval' name='kval' value='$kval'>";
            $strForm .= $ly->form_group("", $varForm, 1);
        }

        $strButton = "";
        $strButton .= "<div class='col-md-12' style='margin-top: 25px;border-top: #f9f7f7 solid 1px;padding-top: 10px;'>";
        $strButton .= "<button type='button' class='btn btn-default text-uppercase pull-left' data-dismiss='modal'>close</button>";
        $strButton .= "<button type='submit' class='btn btn-danger text-uppercase pull-right'>simpan</button>";
        $strButton .= "</div>";

        $link_action = MODUL_PATH . "Data/addProcess/MdlMenuSubStatik/Produk";

        $var = "";
        $var .= "<style type='text/css'>.form-group{ margin-bottom: 2px !important; }</style>";
        $var .= "<div class='row'>";
        $var .= "<div id='loading'></div>";
        $var .= "<form method='post' action='$link_action' target='result' style='margin-top: 0px;'>";
        $var .= $strForm;
        $var .= $strButton;
        $var .= "</form>";
        $var .= "</div>";

        //khusus untuk nembak value jml serial
        $var .= "<script>
                    top.$('#jml_serial').val(1);
                    top.$('select[name=kategori_id]').val(1).change();
                    setTimeout(function(){
                        top.$('.select2').selectpicker({dropdownParent:$('body')}).selectpicker();
                        $('input[type=checkbox].flat-red, input[type=radio].flat-red').iCheck({
                            checkboxClass: 'icheckbox_flat-green',
                            radioClass: 'iradio_flat-green'
                        });
                        $('#loading').hide();
                    }, 250);
                 </script>";
        echo $var;

    }

    /**/
    public function addPihakLain()
    {
        $ly = new Layout();
        $ly->setFormGroupLeftClass("col-sm-4 text-uppercase");
        $ly->setFormGroupRightClass("col-sm-8");

        $segment_4 = $this->uri->segment(4);
        $className_main = $className = "Mdl" . $segment_4;

        // $kval = $_GET['kval'];
        // $this->load->model("Mdls/" . $className);
        // $o = new $className;
        // cekHitam($className);
        $this->load->model("Mdls/MdlCustomer");
        $o = new MdlCustomer();

        $xclass = $this->segment_4;
        $fields = $o->getFields();
        $validationRules = $o->getValidationRules();
        $requireds = array();
        foreach ($validationRules as $kolom => $validationRule) {
            if (in_array("required", $validationRule)) {
                $requireds[] = $kolom;
            }
        }
        $kval = "";
        $kn = "";
        $strForm = "";
        foreach ($fields as $field_alias => $fieldParams) {
            $coSpeks = $fieldParams;
            $kolom_nama = $anakan_ky = $kolom = isset($fieldParams['kolom']) ? $fieldParams['kolom'] : $field_alias;
            $str_label = $label = isset($fieldParams['label']) ? $fieldParams['label'] : $field_alias;
            $inputType = isset($fieldParams['inputType']) ? $fieldParams['inputType'] : "text";
            $editable = isset($coSpeks['editable']) && $coSpeks['editable'] == false ? "readonly" : "";
            $show = isset($coSpeks['show']) ? "1" : "0";
            $urlTrigger = isset($coSpeks['urlTrigger']) ? "data-link='" . base_url() . $coSpeks['urlTrigger'] . "'" : "";
            $eventKeyup = isset($coSpeks['eventTrigger']) ? $coSpeks['eventTrigger'] : "";
            $required_field = "";
            $onChange = "";
            if (in_array($anakan_ky, $requireds)) {
                $label .= "&nbsp; <r>*</r>";
                $required_field = "required";
                // $onChange = "onchange=\"createSession(this, '$mdlChild_json');\"";
            }
            else {
                $required_field = "";
            }

            $strField = isset($coSpeks['strField']) ? $coSpeks['strField'] : "nama";
            $indexFields = isset($coSpeks['indexFields']) ? $coSpeks['indexFields'] : "id";
            $referenceClass = "";
            if (isset($coSpeks['reference'])) {
                $reference = $coSpeks['reference'];
                $referenceClass = $reference ? substr($reference, 3) : "";
                $this->load->model("Mdls/" . $reference);
                $o2 = new $reference();
                $o2->setSortBy(array("kolom" => "id", "mode" => "desc"));
                if (isset($_SESSION['data']['supplier_id'])) {
                    $condites = array(
                        "supplier_id" => $_SESSION['data']['supplier_id'],
                    );
                }
                $dataSrcs = $o2->lookupAll()->result();
                // showLast_query("hijau");
                // arrPrintKuning($dataSrcs);
                $dataSources = array();
                foreach ($dataSrcs as $key_src => $label_src) {
                    $relId = isset($label_src->$indexFields) ? $label_src->$indexFields : "";
                    $relLabel = isset($label_src->$strField) ? $label_src->$strField : "";
                    $dataSources[$relId] = $relLabel;
                }
            }
            else {
                $dataSources = isset($coSpeks['dataSource']) ? $coSpeks['dataSource'] : "";
            }
            // arrPrintHijau($dataSources);

            $btn_add = "<div></div>";
            if (isset($coSpeks['add_btn'])) {
                $reference_label = strtoupper($label);
                $link_editor_act = base_url() . "statik/Data/viewdt/$referenceClass";
                $link_add = base_url() . "statik/Data/add/$referenceClass?main=$className_main&kval=$kval";
                $link_add_act = modalDialogBtn("New $reference_label", $link_add, 0);
                $btn_add = "<div class='input-group-append'>";
                $btn_add .= "<button type='button' class='btn btn-sm btn-flat btn-warning' onclick=\"$link_add_act\"><i class='fa fa-plus'></i></button>";
                $btn_add .= "<button type='button' class='btn btn-sm btn-flat btn-info' onclick=\"btn_confirm_alert('Upss..!','Anda akan meninggalkan halaman ini? <br><r>Semua data yang belum disimpan akan hilang!</r>','$link_editor_act')\"><i class='fa fa-pencil'></i></button>";
                $btn_add .= "</div>";
            }

            switch ($inputType) {
                case "combo-blank":
                    // $reference_label = strtoupper($label);
                    // $link_add = base_url() . "statik/Data/add/$referenceClass?main=$className_main&kval=$kval";
                    // $link_add_act = modalDialogBtn("New $reference_label", $link_add, 0);
                    // $btn_add = isset($coSpeks['add_btn']) ? "<div class='input-group-append'><button type='button' class='btn btn-sm btn-flat btn-warning' onclick=\"$link_add_act\"><i class='fa fa-plus'></i></button></div>" : "<div></div>";
                    $optionals = "<option value=''> silahkan pilih data sebelumnya </option>";
                    $varForm = "<div class='input-group input-group-sm'>";
                    $varForm .= "<select kval='$kval' data-style='btn btn-sm btn-danger' data-live-search='false' data-headers='' data-size='10' data-container='body' class='selectpicker form-controlx select2' $required_field ky='$anakan_ky' name='$kolom' $onChange>";
                    $varForm .= $optionals;
                    $varForm .= "</select>";
                    $varForm .= $btn_add;
                    $varForm .= "</div>";
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "combo-hidden":
                    // $reference_label = strtoupper($label);
                    // $link_add = base_url() . "statik/Data/add/$referenceClass?main=$className_main&kval=$kval";
                    // $link_add_act = modalDialogBtn("New $reference_label", $link_add, 0);
                    // $btn_add = isset($coSpeks['add_btn']) ? "<div class='input-group-append'><button type='button' class='btn btn-sm btn-flat btn-warning' onclick=\"$link_add_act\"><i class='fa fa-plus'></i></button></div>" : "<div></div>";
                    $optionals = "<option value=''> Pilih $str_label </option>";
                    foreach ($dataSources as $key_src => $label_src) {
                        $fSelected = $fValue == $key_src ? "selected" : "";
                        $optionals .= "<option class='text-uppercase' value='$key_src' $fSelected>$label_src</option>";
                    }
                    // $eventSession = $this->createSessionData();
                    $varForm = "<div class='input-group input-group-sm'>";

                    if (count($dataSources) == 0) {
                        $optionals = "<option value=''> SILAHKAN TAMBAHKAN DATA </option>";
                        $varForm .= "<select kval='$kval' data-style='btn btn-sm btn-danger' data-live-search='false' data-headers='' data-size='10' data-container='body' class='selectpicker form-controlx select2' $required_field ky='$anakan_ky' name='$kolom' $onChange>";
                    }
                    else {
                        $varForm .= "<select kval='$kval' data-style='btn btn-sm btn-primary' data-placeholder='cari data' data-live-search='true' data-headers='' data-size='10' data-container='body' class='selectpicker form-controlx select2 show-tick' $required_field ky='$anakan_ky' name='$kolom' $onChange>";
                    }

                    $varForm .= $optionals;
                    $varForm .= "</select>";
                    $varForm .= $btn_add;
                    $varForm .= "</div>";
                    $strForm .= $ly->form_group($label, $varForm, 1);
                    break;
                case "combo":
                    // cekAlert(__LINE__);
                    $optionals = "<option value=''> Pilih $str_label </option>";
                    foreach ($dataSources as $key_src => $label_src) {
                        $fSelected = $fValue == $key_src ? "selected" : "";
                        $optionals .= "<option class='text-uppercase' value='$key_src' $fSelected>$label_src</option>";
                    }
                    // $eventSession = $this->createSessionData();
                    $varForm = "<div class='input-group input-group-sm'>";
                    // cekHere(count($dataSources));
                    if (count($dataSources) == 0) {
                        $optionals = "<option value=''> SILAHKAN TAMBAHKAN DATA </option>";
                        $varForm .= "<select kval='$kval' data-style='btn btn-sm btn-danger' data-live-search='false' data-headers='' data-size='10' data-container='body' class='selectpicker form-controlx select2' $required_field ky='$anakan_ky' name='$kolom' $onChange>";
                    }
                    else {
                        $varForm .= "<select kval='$kval' data-style='btn btn-sm btn-primary' data-placeholder='cari data' data-live-search='true' data-headers='' data-size='10' data-container='body' class='selectpicker form-controlx select2 show-tick' $required_field ky='$anakan_ky' name='$kolom' $onChange>";
                    }

                    $varForm .= $optionals;
                    $varForm .= "</select>";
                    $varForm .= $btn_add;
                    $varForm .= "</div>";
                    // echo $varForm;
                    $strForm .= $ly->form_group($label, $varForm, 0);
                    break;
                case "radio":
                    $varForm = "";
                    foreach ($dataSources as $key_src => $label_src) {
                        $varForm .= "<label><input class='flat-redx' type='radio' $required_field id='$anakan_ky" . "_" . "$key_src' name='$kolom_nama' value='$key_src' $eventKeyup> $label_src</label> &nbsp;";
                    }
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "text":
                    $varForm = "<div class='form-group row'>";
                    // $varForm .= "<div class='col-md-5'>";
                    $varForm .= "<input type='text' $required_field $editable id='$anakan_ky' autocomplete='off' placeholder='$str_label' name='$kolom' class='form-control' $urlTrigger value='$fValue' $eventKeyup>";
                    $varForm .= "<div id='notif_$anakan_ky'></div>";
                    $varForm .= "</div>";

                    $strForm .= $ly->form_group($label, $varForm, $show);
                    break;
                case "number":
                    $varForm = "<div class='form-group row'>";
                    $varForm .= "<input type='number' id='$anakan_ky' name='$kolom' autocomplete='off' placeholder='$str_label' class='form-control' value='$fValue'>";
                    $varForm .= "</div>";
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "hidden":
                    $fValue = $kn;
                    $varForm = "<input type='text' id='$anakan_ky' name='$kolom' value='$fValue'>";
                    $strForm .= $ly->form_group($label, $varForm, 1);
                    break;
            }

        }

        /* -------------------------------------------------------------------------------------
         * button sumit
         * -------------------------------------------------------------------------------------*/
        $strButton = "";
        $strButton .= "<div class='col-md-12' style='margin-top: 25px;border-top: #f9f7f7 solid 1px;padding-top: 10px;'>";
        $strButton .= "<button type='button' class='btn btn-default text-uppercase pull-left' data-dismiss='modal'>close</button>";
        $strButton .= "<button type='submit' class='btn btn-danger text-uppercase pull-right'>simpan</button>";
        $strButton .= "</div>";

        /* -------------------------------------------------------------------------------------
         * penampil form di UI
         * -------------------------------------------------------------------------------------*/
        $link_action = MODUL_PATH . "Data/addProcess/$segment_4/$segment_4";
        $createSession_link = MODUL_PATH . "Data/createSessionData/Produk";
        $var = "";
        $var .= "<style>
            .form-group {
                margin-bottom: 2px !important;
            }
            .input-group{
                margin-left: -13px;
            }

        </style>";
        $var .= "<div class='overflow-h'>";
        $var .= "<form method='post' action='$link_action' target='result' style='margin-top: 0px;'>";
        $var .= $strForm;
        $var .= $strButton;
        $var .= "</form>";
        $var .= "</div>";
        // $var .= "</div>";
        // $var .= "</div>";
        $var .= "<script>
            $('.selectpicker').selectpicker();
            $('#myModal').on('shown.bs.modal', function () {
                $('.selectpicker').selectpicker('refresh');
            });

        </script>";
        $var .= "<script>
            function cek_master_data(x,i,t,n)
            {               
                let url = t + '?key='+x+'&kolom='+n;                
                $('#notif_' + i).load(url);
            }
            
            function cek_kolom_data(x,i,t,n) {
              if(n == 'barcode'){
                  let nilai_master = $('input[name=kode]').val();
                  
                  $('input[name=barcode]').val(nilai_master);
              }
            }
            
            function copy_to(n,x) {
              $('input[name=' + n + ']').val(x);
            }
            
            function toggleInput(i,n) {
             
                var yesRadio = document.getElementById('kategori_id_2');
                var inputField = document.getElementById(n);
                var inputFieldNpwp = document.getElementById('npwp');
                var labelField = $('.nik');
                
                if (yesRadio.checked) {
                    inputField.disabled = false; // Enable input text
                    inputField.setAttribute('required', ''); // Disable input text
                    // $('#nik').attr('required', 'required');
                    $('.nik').append('<r>*</r>')
                    removeAppendedCharacter('npwp','*');
                } else {
                    inputField.disabled = true; // Disable input text
                     removeAppendedCharacter('nik','*');
                     $('.npwp').append('<r>*</r>');
                     // $('#npwp').attr('required', 'required');
                     inputFieldNpwp.setAttribute('required', '');
                     // document.getElementById('npwp').setAttribute('required', '');
                }
            }
            
            function removeAppendedCharacter(c,x) {
                $('.'+c).each(function() {
                    var content = $(this).text();
                    if (content.endsWith(x)) {
                        $(this).text(content.slice(0, -1));
                    }
                });
            }
            
        </script>";
        // $kval = isset($_GET['kval']) ? $_GET['kval'] : "";
        // $kn = isset($kategories[$kval]['jml_serial']) ? $kategories[$kval]['jml_serial'] : "";
        // $link_form = MODUL_PATH . "Data/addProdukForm/$xclass";
        // $link_sub = MODUL_PATH . "Data/addProdukSub/$xclass";
        // $var .= "<script>
        //     var kval = '$kval', kn = '$kn';
        //     if(kval != ''){
        //         $('#wadah_1').load('$link_form?kval='+ kval +'&n=' + kn);
        //     };
        //     function loadAnakan(x,n) {
        //       $('#wadah_sub').html('');
        //       $('#wadah_1').load('$link_form?kval='+ x +'&n=' + n);
        //     }
        //     function loadSubMain(x,n) {
        //       $('#wadah_sub').load('$link_sub?kval='+ x +'&n=' + n);
        //       $('#wadah_1').html('');
        //     }
        // </script>";

        echo $var;
    }

    /**/
    public function addEmployee_sbl_jembreng()
    {
        $ly = new Layout();
        $ly->setFormGroupLeftClass("col-sm-4 text-uppercase");
        $ly->setFormGroupRightClass("col-sm-8");

        $segment_4 = $this->uri->segment(4);
        $className_main = $className = "Mdl" . $segment_4;

        // $kval = $_GET['kval'];
        // $this->load->model("Mdls/" . $className);
        // $o = new $className;
        // cekHitam($className);
        $this->load->model("Mdls/$className_main");
        $o = new $className_main();

        $xclass = $this->segment_4;
        $fields = $o->getFields();
        $validationRules = $o->getValidationRules();
        $requireds = array();
        foreach ($validationRules as $kolom => $validationRule) {
            if (in_array("required", $validationRule)) {
                $requireds[] = $kolom;
            }
        }
        $kval = "";
        $kn = "";
        $strForm = "";
        foreach ($fields as $field_alias => $fieldParams) {
            $coSpeks = $fieldParams;
            $kolom_nama = $anakan_ky = $kolom = isset($fieldParams['kolom']) ? $fieldParams['kolom'] : $field_alias;
            $str_label = $label = isset($fieldParams['label']) ? $fieldParams['label'] : $field_alias;
            $inputType = isset($fieldParams['inputType']) ? $fieldParams['inputType'] : "text";
            $editable = isset($coSpeks['editable']) && $coSpeks['editable'] == false ? "readonly" : "";
            $show = isset($coSpeks['show']) ? "1" : "0";
            $urlTrigger = isset($coSpeks['urlTrigger']) ? "data-link='" . base_url() . $coSpeks['urlTrigger'] . "'" : "";
            $eventKeyup = isset($coSpeks['eventTrigger']) ? $coSpeks['eventTrigger'] : "";
            $defaultValue = isset($coSpeks['defaultValue']) ? $coSpeks['defaultValue'] : "";
            $required_field = "";
            $onChange = "";
            if (in_array($anakan_ky, $requireds)) {
                $label .= "&nbsp; <r>*</r>";
                $required_field = "required";
                // $onChange = "onchange=\"createSession(this, '$mdlChild_json');\"";
            }
            else {
                $required_field = "";
            }

            $strField = isset($coSpeks['strField']) ? $coSpeks['strField'] : "nama";
            $strFields = isset($coSpeks['strFields']) ? $coSpeks['strFields'] : array();
            $strFieldClass = isset($coSpeks['strFieldClass']) ? $coSpeks['strFieldClass'] : "";
            $indexFields = isset($coSpeks['indexFields']) ? $coSpeks['indexFields'] : "id";
            $referenceClass = "";
            if (isset($coSpeks['reference'])) {
                $reference = $coSpeks['reference'];
                $referenceClass = $reference ? substr($reference, 3) : "";
                $this->load->model("Mdls/" . $reference);
                $o2 = new $reference();
                $o2->setSortBy(array("kolom" => "id", "mode" => "desc"));
                if (isset($_SESSION['data']['supplier_id'])) {
                    $condites = array(
                        "supplier_id" => $_SESSION['data']['supplier_id'],
                    );
                }
                $dataSrcs = $o2->lookupAll()->result();
                // showLast_query("hijau");
                // arrPrintKuning($dataSrcs);
                $dataSources = array();
                $dataSource_all = array();
                foreach ($dataSrcs as $key_src => $label_src) {
                    $relId = isset($label_src->$indexFields) ? $label_src->$indexFields : "";
                    $relLabel = isset($label_src->$strField) ? $label_src->$strField : "";
                    $dataSources[$relId] = $relLabel;
                    $dataSource_all[$relId] = (array)$label_src;
                }
            }
            else {
                $dataSources = isset($coSpeks['dataSource']) ? $coSpeks['dataSource'] : "";
            }
            // arrPrintHijau($dataSources);
            // arrPrintPink($dataSource_all);

            $btn_add = "<div></div>";
            if (isset($coSpeks['add_btn'])) {
                $reference_label = strtoupper($label);
                $link_editor_act = base_url() . "statik/Data/viewdt/$referenceClass";
                $link_add = base_url() . "statik/Data/add/$referenceClass?main=$className_main&kval=$kval";
                $link_add_act = modalDialogBtn("New $reference_label", $link_add, 0);
                $btn_add = "<div class='input-group-append'>";
                $btn_add .= "<button type='button' class='btn btn-sm btn-flat btn-warning' onclick=\"$link_add_act\"><i class='fa fa-plus'></i></button>";
                $btn_add .= "<button type='button' class='btn btn-sm btn-flat btn-info' onclick=\"btn_confirm_alert('Upss..!','Anda akan meninggalkan halaman ini? <br><r>Semua data yang belum disimpan akan hilang!</r>','$link_editor_act')\"><i class='fa fa-pencil'></i></button>";
                $btn_add .= "</div>";
            }
            $fValue = "";
            switch ($inputType) {
                case "combo-blank":
                    // $reference_label = strtoupper($label);
                    // $link_add = base_url() . "statik/Data/add/$referenceClass?main=$className_main&kval=$kval";
                    // $link_add_act = modalDialogBtn("New $reference_label", $link_add, 0);
                    // $btn_add = isset($coSpeks['add_btn']) ? "<div class='input-group-append'><button type='button' class='btn btn-sm btn-flat btn-warning' onclick=\"$link_add_act\"><i class='fa fa-plus'></i></button></div>" : "<div></div>";
                    $optionals = "<option value=''> silahkan pilih data sebelumnya </option>";
                    $varForm = "<div class='input-group input-group-sm'>";
                    $varForm .= "<select kval='$kval' data-style='btn btn-sm btn-danger' data-live-search='false' data-headers='' data-size='10' data-container='body' class='selectpicker form-controlx select2' $required_field ky='$anakan_ky' name='$kolom' $onChange>";
                    $varForm .= $optionals;
                    $varForm .= "</select>";
                    $varForm .= $btn_add;
                    $varForm .= "</div>";
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "combo-hidden":
                    // $reference_label = strtoupper($label);
                    // $link_add = base_url() . "statik/Data/add/$referenceClass?main=$className_main&kval=$kval";
                    // $link_add_act = modalDialogBtn("New $reference_label", $link_add, 0);
                    // $btn_add = isset($coSpeks['add_btn']) ? "<div class='input-group-append'><button type='button' class='btn btn-sm btn-flat btn-warning' onclick=\"$link_add_act\"><i class='fa fa-plus'></i></button></div>" : "<div></div>";
                    $optionals = "<option value=''> Pilih $str_label </option>";
                    foreach ($dataSources as $key_src => $label_src) {
                        $fSelected = $fValue == $key_src ? "selected" : "";
                        $optionals .= "<option class='text-uppercase' value='$key_src' $fSelected>$label_src</option>";
                    }
                    // $eventSession = $this->createSessionData();
                    $varForm = "<div class='input-group input-group-sm'>";

                    if (count($dataSources) == 0) {
                        $optionals = "<option value=''> SILAHKAN TAMBAHKAN DATA </option>";
                        $varForm .= "<select kval='$kval' data-style='btn btn-sm btn-danger' data-live-search='false' data-headers='' data-size='10' data-container='body' class='selectpicker form-controlx select2' $required_field ky='$anakan_ky' name='$kolom' $onChange>";
                    }
                    else {
                        $varForm .= "<select kval='$kval' data-style='btn btn-sm btn-primary' data-placeholder='cari data' data-live-search='true' data-headers='' data-size='10' data-container='body' class='selectpicker form-controlx select2 show-tick' $required_field ky='$anakan_ky' name='$kolom' $onChange>";
                    }

                    $varForm .= $optionals;
                    $varForm .= "</select>";
                    $varForm .= $btn_add;
                    $varForm .= "</div>";
                    $strForm .= $ly->form_group($label, $varForm, 1);
                    break;
                case "combo":
                    // cekAlert(__LINE__);
                    $fValue = $defaultValue;
                    $optionals = "<option value=''> Pilih $str_label </option>";
                    foreach ($dataSources as $key_src => $label_src) {
                        $fSelected = $fValue == $key_src ? "selected" : "";
                        $optionals .= "<option class='text-uppercase' value='$key_src' $fSelected>$label_src</option>";
                    }
                    // $eventSession = $this->createSessionData();
                    $varForm = "<div class='input-group input-group-sm'>";
                    // cekHere(count($dataSources));
                    if (count($dataSources) == 0) {
                        $optionals = "<option value=''> SILAHKAN TAMBAHKAN DATA </option>";
                        $varForm .= "<select kval='$kval' data-style='btn btn-sm btn-danger' data-live-search='false' data-headers='' data-size='10' data-container='body' class='selectpicker form-controlx select2' $required_field ky='$anakan_ky' name='$kolom' $onChange>";
                    }
                    else {
                        $varForm .= "<select kval='$kval' data-style='btn btn-sm btn-primary' data-placeholder='cari data' data-live-search='true' data-headers='' data-size='10' data-container='body' class='selectpicker form-controlx select2 show-tick' $required_field ky='$anakan_ky' name='$kolom' $onChange>";
                    }

                    $varForm .= $optionals;
                    $varForm .= "</select>";
                    $varForm .= $btn_add;
                    $varForm .= "</div>";

                    // echo $varForm;
                    $strForm .= $ly->form_group($label, $varForm, 0);
                    break;
                case "checkbox":
                    $varForm = "";
                    // arrPrintHijau($dataSrcs);
                    foreach ($dataSources as $key_src => $label_src) {
                        $kolom_nama_key = $kolom_nama . "[]";
                        if (count($strFields)) {
                            $dataSourceAllSpeks = $dataSource_all[$key_src];
                            $strClass = $dataSourceAllSpeks[$strFieldClass];

                            /* -----------------------------------------------
                             * over write $label_src yg asli
                             * -----------------------------------------------*/
                            $str_label = "";
                            foreach ($strFields as $strField2) {
                                $lbl = $dataSourceAllSpeks[$strField2];
                                $var = $lbl;
                                if ($str_label == "") {
                                    $str_label .= "$var";
                                }
                                else {
                                    $str_label = "$str_label $var";
                                }

                            }
                            $label_src = $str_label;
                        }

                        $varForm .= "<label class='text-uppercase'><input class='flat-redx $strClass' data-kolom='$kolom' type='checkbox' $required_field id='$anakan_ky" . "_" . "$key_src' name=\"$kolom_nama_key\" value='$key_src' $eventKeyup> $label_src</label> &nbsp;";
                    }
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "radio":
                    $varForm = "";
                    // arrPrintWebs($strFields);
                    // arrPrintKuning("$strFieldClass");
                    // arrPrintHijau($dataSource_all[2]['parent_str']);
                    foreach ($dataSources as $key_src => $label_src) {

                        // if (count($strFieldClass)) {
                        $strClass = "";
                        if ($strFieldClass != "") {
                            $dataSourceAllSpeks = $dataSource_all[$key_src];
                            $strClass = $dataSourceAllSpeks[$strFieldClass];
                            $str_label = "";
                            // foreach ($strFields as $strField2) {
                            //     $lbl = $dataSourceAllSpeks[$strField2];
                            //     $var = $lbl;
                            //     if ($str_label == "") {
                            //         $str_label .= "$var";
                            //     }
                            //     else {
                            //         $str_label = "$str_label $var";
                            //     }
                            //
                            // }
                            // $label_src = $str_label;
                        }
                        $cssClass = trim("$kolom_nama flat-redx $strClass");
                        $varForm .= "<label class='text-uppercase'><input class='$cssClass' type='radio' $required_field id='$anakan_ky" . "_" . "$key_src' name='$kolom_nama' value='$key_src' $eventKeyup> $label_src</label> &nbsp;";
                    }
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "text":
                    $varForm = "<div class='form-group row'>";
                    // $varForm .= "<div class='col-md-5'>";
                    $varForm .= "<input type='text' $required_field $editable id='$anakan_ky' autocomplete='off' placeholder='$str_label' name='$kolom' class='form-control' $urlTrigger value='$fValue' $eventKeyup>";
                    $varForm .= "<div id='notif_$anakan_ky'></div>";
                    $varForm .= "</div>";

                    $strForm .= $ly->form_group($label, $varForm, $show);
                    break;
                case "password":
                    $fValue = defaultPassword();
                    $varForm = "<div class='form-group row'>";
                    // $varForm .= "<div class='col-md-5'>";
                    $varForm .= "<input type='password' $required_field $editable id='$anakan_ky' autocomplete='off' placeholder='$str_label' name='$kolom' class='form-control' $urlTrigger value='$fValue' $eventKeyup>";
                    $varForm .= "<div id='notif_$anakan_ky'></div>";
                    $varForm .= "</div>";

                    $strForm .= $ly->form_group($label, $varForm, $show);
                    break;
                case "number":
                    $varForm = "<div class='form-group row'>";
                    $varForm .= "<input type='number' id='$anakan_ky' name='$kolom' autocomplete='off' placeholder='$str_label' class='form-control' value='$fValue'>";
                    $varForm .= "</div>";
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "hidden":
                    $fValue = $kn;
                    $varForm = "<input type='text' id='$anakan_ky' name='$kolom' value='$fValue'>";
                    $strForm .= $ly->form_group($label, $varForm, 1);
                    break;
            }
        }

        /* -------------------------------------------------------------------------------------
         * button sumit
         * -------------------------------------------------------------------------------------*/
        $strButton = "";
        $strButton .= "<div class='col-md-12' style='margin-top: 25px;border-top: #f9f7f7 solid 1px;padding-top: 10px;'>";
        $strButton .= "<button type='button' class='btn btn-default text-uppercase pull-left' data-dismiss='modal'>close</button>";
        $strButton .= "<button type='submit' class='btn btn-danger text-uppercase pull-right'>simpan</button>";
        $strButton .= "</div>";

        /* -------------------------------------------------------------------------------------
         * penampil form di UI
         * -------------------------------------------------------------------------------------*/
        $link_action = MODUL_PATH . "Data/addProcess/$segment_4/$segment_4";
        $createSession_link = MODUL_PATH . "Data/createSessionData/Produk";
        $var = "";
        $var .= "<style>
            .form-group {
                margin-bottom: 2px !important;
            }
            .input-group{
                margin-left: -13px;
            }

        </style>";
        $var .= "<div class='overflow-h'>";
        $var .= "<form method='post' id='myForm' action='$link_action' target='result' style='margin-top: 0px;'>";
        $var .= $strForm;
        $var .= $strButton;
        $var .= "</form>";
        $var .= "</div>";

        // $var .= "</div>";
        // $var .= "</div>";
        $var .= "<script>
            $('.selectpicker').selectpicker();
            $('#myModal').on('shown.bs.modal', function () {
                $('.selectpicker').selectpicker('refresh');
            });

        </script>";

        $var .= "<script>
            function cek_master_data(x,i,t,n)
            {               
                let url = t + '?key='+x+'&kolom='+n;                
                $('#notif_' + i).load(url);
            }
            
            function cek_kolom_data(x,i,t,n) {
              if(n == 'barcode'){
                  let nilai_master = $('input[name=kode]').val();
                  
                  $('input[name=barcode]').val(nilai_master);
              }
            }
            
            function copy_to(n,x) {
              $('input[name=' + n + ']').val(x);
            }
            
            function copy_from(n,x) {
                let val_n = $('input[name=' + n + ']').val();
                
                $('input[name=' + x + ']').val(val_n);
            }
            
            function toggleInput(i,n) {
             
                var yesRadio = document.getElementById('kategori_id_2');
                var inputField = document.getElementById(n);
                var inputFieldNpwp = document.getElementById('npwp');
                var labelField = $('.nik');
                
                if (yesRadio.checked) {
                    inputField.disabled = false; // Enable input text
                    inputField.setAttribute('required', ''); // Disable input text
                    // $('#nik').attr('required', 'required');
                    $('.nik').append('<r>*</r>')
                    removeAppendedCharacter('npwp','*');
                } else {
                    inputField.disabled = true; // Disable input text
                     removeAppendedCharacter('nik','*');
                     $('.npwp').append('<r>*</r>');
                     // $('#npwp').attr('required', 'required');
                     inputFieldNpwp.setAttribute('required', '');
                     // document.getElementById('npwp').setAttribute('required', '');
                }
            }
            
            function removeAppendedCharacter(c,x) {
                $('.'+c).each(function() {
                    var content = $(this).text();
                    if (content.endsWith(x)) {
                        $(this).text(content.slice(0, -1));
                    }
                });
            }
         </script>";

        $var .= "<script>          
            function validatePenempatan(id) {
                var checkbox = $('#' + id);
                var tkolom = checkbox.data('kolom');
                 var checkboxClass = checkbox.attr('class');
                // console.log(checkboxClass);
    
                var otherCheckboxes = $('input[type=\"checkbox\"]').not('.pusat').filter(function() {
                    return $(this).data('kolom') == tkolom;
                });
    
                
                
                if (checkbox.hasClass('pusat')) {
                    console.log(checkboxClass);
                    if (checkbox.prop('checked')) {
                        otherCheckboxes.prop('disabled', true);
                    } else {
                        otherCheckboxes.prop('disabled', false);
                    }
                }
                else if (checkbox.hasClass('cabang')){
                    console.log(checkboxClass);
                }                     
            }  
            
            function validateFunction(id, c) {
                var checkbox = $('#' + id);
                var checkboxClass = $('input[type=\"radio\"].' + c);
                 
                //  console.log('id: ', id);
                //  console.log('c: ', c);
                // console.log(checkbox);
                // console.log(checkboxClass);
                    
                
                if (checkbox.hasClass('pusat')) {
                    // console.log('pusat: ', checkboxClass);

                    // var checkboxClass = checkbox.attr('class');
                    if (checkbox.prop('checked')) {
                        console.log('tic');
                        
                        // $('input[type=\"radio\"].cabang').not('.pusat').prop('disabled', true);
                        checkboxClass.not('.pusat').prop('disabled', true);
                                                                          
                        jQuery.each(checkboxClass,function(a,b) {
                            console.log(a);  
                            console.log(b);  
                            console.log($(b).hasClass('pusat'));
                    
                            if($(b).hasClass('pusat')){
                                $(b).addClass('pusat-on');
                            }
                        })
                        

                    } else {
                        console.log('un-tic');
                        
                        // $('input[type=\"radio\"].cabang').prop('disabled', false);
                        checkboxClass.not('.pusat').prop('disabled', false);
                        // checkboxClass.hasClass('pusat').removeClass('cabang-on');
                        // $('input[type=\"radio\"].cabang').removeClass('cabang-on');
                        if(checkboxClass.hasClass('pusat')){
                            checkboxClass.removeClass('pusat-on')
                        }
                    }
                }
                else if (checkbox.hasClass('cabang')){
                   
                    if (checkbox.prop('checked')) {
                        console.log('cabang tic');
                        
                        jQuery.each(checkboxClass,function(a,b) {
                            if($(b).hasClass('pusat-on')){
                                console.log(b);
                                // $(b).addClass('pusat-on');
                            }
                        }
                        // $('input[type=\"radio\"].pusat').not('.cabang').prop('disabled', true);
                        // if(checkboxClass.not('.pusat-on')){
                        //      console.log('pusat-on');
                        //     checkboxClass.prop('disabled', false);
                        //    
                        //     if(checkboxClass.hasClass('cabang')){
                        //         checkboxClass.addClass('cabang-on')
                        //     }
                        // }
                        //
                        // if (checkboxClass.not('.cabang')){
                        //     console.log('bukan cabang');
                        //     checkboxClass.not('.cabang').prop('disabled', true);
                        //     // checkboxClass.prop('disabled', true);
                        // }

                    } else {
                        console.log('un-tic');
                        
                        // $('input[type=\"radio\"].pusat').prop('disabled', false);
                        checkboxClass.not('.cabang').prop('disabled', false);
                        
                        if(checkboxClass.hasClass('cabang')){
                                checkboxClass.removeClass('cabang-on')
                        }
                        
                    }
                }                     
            }
            
            // function validateTic() {
                $('#myForm').on('submit', function(event) {
                    // Mendapatkan semua checkbox dengan kelas \"option\"
                    var checkboxes = $('.cabang');
                    // Memeriksa apakah setidaknya satu checkbox dipilih
                    var atLeastOneChecked = checkboxes.is(':checked');
                    
                    if (!atLeastOneChecked) {
                        // Menampilkan pesan kesalahan jika tidak ada checkbox yang dipilih
                        console.log('belum dipilih')
                        $('#error-message').show();
                        event.preventDefault(); // Mencegah pengiriman formulir
                    } else {
                        console.log('sudah dipilih')
                        $('#error-message').hide();
                    }
                });
            // }
            
        </script>";
        // $kval = isset($_GET['kval']) ? $_GET['kval'] : "";
        // $kn = isset($kategories[$kval]['jml_serial']) ? $kategories[$kval]['jml_serial'] : "";
        // $link_form = MODUL_PATH . "Data/addProdukForm/$xclass";
        // $link_sub = MODUL_PATH . "Data/addProdukSub/$xclass";
        // $var .= "<script>
        //     var kval = '$kval', kn = '$kn';
        //     if(kval != ''){
        //         $('#wadah_1').load('$link_form?kval='+ kval +'&n=' + kn);
        //     };
        //     function loadAnakan(x,n) {
        //       $('#wadah_sub').html('');
        //       $('#wadah_1').load('$link_form?kval='+ x +'&n=' + n);
        //     }
        //     function loadSubMain(x,n) {
        //       $('#wadah_sub').load('$link_sub?kval='+ x +'&n=' + n);
        //       $('#wadah_1').html('');
        //     }
        // </script>";

        echo $var;
    }

    public function formJembreng()
    {
        $var = "";
        $var .= "huahaha";
        echo $var;
    }

    public function addEmployee()
    {
        // arrPrint(url_segment());
        // arrPrint($_GET);

        $ly = new Layout();
        $ly->setFormGroupLeftClass("col-sm-4 text-uppercase");
        $ly->setFormGroupRightClass("col-sm-8");

        $segment_4 = $this->uri->segment(4);
        $dtId = $this->uri->segment(5);
        $className_main = $className = "Mdl" . $segment_4;

        // $kval = $_GET['kval'];
        // $this->load->model("Mdls/" . $className);
        // $o = new $className;
        // cekHitam($className);
        $this->load->model("Mdls/$className_main");
        $o = new $className_main();

        if (isset($dtId)) {
            $datas = $o->lookupByID($dtId)->row();
            // showLast_query("hijau");
            // arrPrint($datas);

        }

        $xclass = $this->segment_4;
        $fields = $o->getFields();
        $validationRules = $o->getValidationRules();
        $requireds = array();
        foreach ($validationRules as $kolom => $validationRule) {
            if (in_array("required", $validationRule)) {
                $requireds[] = $kolom;
            }
        }
        $kval = "";
        $kn = "";
        $strForm = "";
        foreach ($fields as $field_alias => $fieldParams) {
            $coSpeks = $fieldParams;
            $kolom_nama = $anakan_ky = $kolom = isset($fieldParams['kolom']) ? $fieldParams['kolom'] : $field_alias;
            $str_label = $label = isset($fieldParams['label']) ? $fieldParams['label'] : $field_alias;
            $inputType = isset($fieldParams['inputType']) ? $fieldParams['inputType'] : "text";
            $editable = isset($coSpeks['editable']) && $coSpeks['editable'] == false ? "readonly" : "";
            $show = isset($coSpeks['show']) && $coSpeks['show'] == 0 ? 1 : 0;
            $urlTrigger = isset($coSpeks['urlTrigger']) ? "data-link='" . base_url() . $coSpeks['urlTrigger'] . "'" : "";
            $customAtt = isset($coSpeks['att']) ? "data-link='" . base_url() . $coSpeks['att'] . "'" : "";
            $anakan = isset($coSpeks['anakan']) ? $coSpeks['anakan'] : false;
            $multi_rel = isset($coSpeks['multi_rel']) ? $coSpeks['multi_rel'] : false;
            if ($multi_rel === true) {
                $multi_mdl = isset($coSpeks['multi_mdl']) ? $coSpeks['multi_mdl'] : matiHere(__LINE__ . " multi_mdl belum diset");
                $multi_parent = isset($coSpeks['multi_parent']) ? $coSpeks['multi_parent'] : matiHere(__LINE__ . " multi_parent harap di-diset");
                $this->load->model("Mdls/$multi_mdl");
                $condites = array(
                    $multi_parent => $dtId,
                );
                $multi_srcs = $this->$multi_mdl->lookupByCondition($condites)->result();
                // showLast_query("kuning");
                // arrPrint($multi_srcs);
                $cabangIds = array();
                foreach ($multi_srcs as $multi_src) {
                    $dcabang_id = $multi_src->$kolom_nama;

                    $cabangIds[] = $dcabang_id;
                }
            }

            $eventKeyup = isset($coSpeks['eventTrigger']) ? $coSpeks['eventTrigger'] : "";
            $defaultValue = isset($coSpeks['defaultValue']) ? $coSpeks['defaultValue'] : "";
            $required_field = "";
            $onChange = "";
            if (in_array($anakan_ky, $requireds)) {
                $label .= "&nbsp; <r>*</r>";
                $required_field = "required";
                // $onChange = "onchange=\"createSession(this, '$mdlChild_json');\"";
            }
            else {
                $required_field = "";
            }

            $strField = isset($coSpeks['strField']) ? $coSpeks['strField'] : "nama";
            $strFields = isset($coSpeks['strFields']) ? $coSpeks['strFields'] : array();
            $strFieldClass = isset($coSpeks['strFieldClass']) ? $coSpeks['strFieldClass'] : "";
            $strFieldFixClass = isset($coSpeks['strFieldFixClass']) ? $coSpeks['strFieldFixClass'] : "";
            $indexFields = isset($coSpeks['indexFields']) ? $coSpeks['indexFields'] : "id";
            $referenceClass = "";
            if (isset($coSpeks['reference'])) {
                $reference = $coSpeks['reference'];
                $referenceClass = $reference ? substr($reference, 3) : "";
                $this->load->model("Mdls/" . $reference);
                $o2 = new $reference();
                $o2->setSortBy(array("kolom" => "id", "mode" => "desc"));
                if (isset($_SESSION['data']['supplier_id'])) {
                    $condites = array(
                        "supplier_id" => $_SESSION['data']['supplier_id'],
                    );
                }
                $dataSrcs = $o2->lookupAll()->result();
                // showLast_query("hijau");
                // arrPrintKuning($dataSrcs);
                $dataSources = array();
                $dataSource_all = array();
                foreach ($dataSrcs as $key_src => $label_src) {
                    $relId = isset($label_src->$indexFields) ? $label_src->$indexFields : "";
                    $relLabel = isset($label_src->$strField) ? $label_src->$strField : "";
                    $dataSources[$relId] = $relLabel;
                    $dataSource_all[$relId] = (array)$label_src;
                }
            }
            else {
                $dataSources = isset($coSpeks['dataSource']) ? $coSpeks['dataSource'] : "";
            }
            // arrPrintHijau($dataSources);
            // arrPrintPink($dataSource_all);

            $btn_add = "<div></div>";
            if (isset($coSpeks['add_btn'])) {
                $reference_label = strtoupper($label);
                $link_editor_act = base_url() . "statik/Data/viewdt/$referenceClass";
                $link_add = base_url() . "statik/Data/add/$referenceClass?main=$className_main&kval=$kval";
                $link_add_act = modalDialogBtn("New $reference_label", $link_add, 0);
                $btn_add = "<div class='input-group-append'>";
                $btn_add .= "<button type='button' class='btn btn-sm btn-flat btn-warning' onclick=\"$link_add_act\"><i class='fa fa-plus'></i></button>";
                $btn_add .= "<button type='button' class='btn btn-sm btn-flat btn-info' onclick=\"btn_confirm_alert('Upss..!','Anda akan meninggalkan halaman ini? <br><r>Semua data yang belum disimpan akan hilang!</r>','$link_editor_act')\"><i class='fa fa-pencil'></i></button>";
                $btn_add .= "</div>";
            }
            $fValue = isset($datas->$kolom_nama) ? $datas->$kolom_nama : "";
            switch ($inputType) {
                case "combo-blank":
                    // $reference_label = strtoupper($label);
                    // $link_add = base_url() . "statik/Data/add/$referenceClass?main=$className_main&kval=$kval";
                    // $link_add_act = modalDialogBtn("New $reference_label", $link_add, 0);
                    // $btn_add = isset($coSpeks['add_btn']) ? "<div class='input-group-append'><button type='button' class='btn btn-sm btn-flat btn-warning' onclick=\"$link_add_act\"><i class='fa fa-plus'></i></button></div>" : "<div></div>";
                    $optionals = "<option value=''> silahkan pilih data sebelumnya </option>";
                    $varForm = "<div class='input-group input-group-sm'>";
                    $varForm .= "<select kval='$kval' data-style='btn btn-sm btn-danger' data-live-search='false' data-headers='' data-size='10' data-container='body' class='selectpicker form-controlx select2' $required_field ky='$anakan_ky' name='$kolom' $onChange>";
                    $varForm .= $optionals;
                    $varForm .= "</select>";
                    $varForm .= $btn_add;
                    $varForm .= "</div>";
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "combo-hidden":
                    // $reference_label = strtoupper($label);
                    // $link_add = base_url() . "statik/Data/add/$referenceClass?main=$className_main&kval=$kval";
                    // $link_add_act = modalDialogBtn("New $reference_label", $link_add, 0);
                    // $btn_add = isset($coSpeks['add_btn']) ? "<div class='input-group-append'><button type='button' class='btn btn-sm btn-flat btn-warning' onclick=\"$link_add_act\"><i class='fa fa-plus'></i></button></div>" : "<div></div>";
                    $optionals = "<option value=''> Pilih $str_label </option>";
                    foreach ($dataSources as $key_src => $label_src) {
                        $fSelected = $fValue == $key_src ? "selected" : "";
                        $optionals .= "<option class='text-uppercase' value='$key_src' $fSelected>$label_src</option>";
                    }
                    // $eventSession = $this->createSessionData();
                    $varForm = "<div class='input-group input-group-sm'>";

                    if (count($dataSources) == 0) {
                        $optionals = "<option value=''> SILAHKAN TAMBAHKAN DATA </option>";
                        $varForm .= "<select kval='$kval' data-style='btn btn-sm btn-danger' data-live-search='false' data-headers='' data-size='10' data-container='body' class='selectpicker form-controlx select2' $required_field ky='$anakan_ky' name='$kolom' $onChange>";
                    }
                    else {
                        $varForm .= "<select kval='$kval' data-style='btn btn-sm btn-primary' data-placeholder='cari data' data-live-search='true' data-headers='' data-size='10' data-container='body' class='selectpicker form-controlx select2 show-tick' $required_field ky='$anakan_ky' name='$kolom' $onChange>";
                    }

                    $varForm .= $optionals;
                    $varForm .= "</select>";
                    $varForm .= $btn_add;
                    $varForm .= "</div>";
                    $strForm .= $ly->form_group($label, $varForm, 1);
                    break;
                case "combo":
                    // cekAlert(__LINE__);
                    $fValue = $defaultValue;
                    $optionals = "<option value=''> Pilih $str_label </option>";
                    foreach ($dataSources as $key_src => $label_src) {
                        $fSelected = $fValue == $key_src ? "selected" : "";
                        $optionals .= "<option class='text-uppercase' value='$key_src' $fSelected>$label_src</option>";
                    }
                    // $eventSession = $this->createSessionData();
                    $varForm = "<div class='input-group input-group-sm'>";
                    // cekHere(count($dataSources));
                    if (count($dataSources) == 0) {
                        $optionals = "<option value=''> SILAHKAN TAMBAHKAN DATA </option>";
                        $varForm .= "<select kval='$kval' data-style='btn btn-sm btn-danger' data-live-search='false' data-headers='' data-size='10' data-container='body' class='selectpicker form-controlx select2' $required_field ky='$anakan_ky' name='$kolom' $onChange>";
                    }
                    else {
                        $varForm .= "<select kval='$kval' data-style='btn btn-sm btn-primary' data-placeholder='cari data' data-live-search='true' data-headers='' data-size='10' data-container='body' class='selectpicker form-controlx select2 show-tick' $required_field ky='$anakan_ky' name='$kolom' $onChange>";
                    }

                    $varForm .= $optionals;
                    $varForm .= "</select>";
                    $varForm .= $btn_add;
                    $varForm .= "</div>";

                    // echo $varForm;
                    $strForm .= $ly->form_group($label, $varForm, 0);
                    break;
                case "checkbox":
                    $varForm = "";
                    // arrPrintHijau($dataSrcs);
                    foreach ($dataSources as $key_src => $label_src) {
                        $kolom_nama_key = $kolom_nama . "[]";

                        if (count($strFields)) {
                            $dataSourceAllSpeks = $dataSource_all[$key_src];
                            $strClass = $dataSourceAllSpeks[$strFieldClass];

                            /* -----------------------------------------------
                             * over write $label_src yg asli
                             * -----------------------------------------------*/
                            $str_label = "";
                            foreach ($strFields as $strField2) {
                                $lbl = $dataSourceAllSpeks[$strField2];
                                $var = $lbl;
                                if ($str_label == "") {
                                    $str_label .= "$var";
                                }
                                else {
                                    $str_label = "$str_label $var";
                                }

                            }
                            $label_src = $str_label;
                        }

                        $checked = in_array($key_src, $cabangIds) ? "checked" : "";
                        $varForm .= "<label class='text-uppercase'><input class='flat-redx $strClass $strFieldFixClass' data-kolom='$kolom' type='checkbox' $checked $required_field id='$anakan_ky" . "_" . "$key_src' name=\"$kolom_nama_key\" value='$key_src' $eventKeyup> $label_src</label> &nbsp;";
                    }
                    if ($anakan == true) {
                        $varForm .= "<span id='error-message' style='display: none;'><r>pilih salah satu option</r></span>";
                        $varForm .= "<div id='jembrengan'></div>";
                    }
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "radio":
                    $varForm = "";
                    // arrPrintWebs($strFields);
                    // arrPrintKuning("$strFieldClass");
                    // arrPrintHijau($dataSource_all[2]['parent_str']);
                    foreach ($dataSources as $key_src => $label_src) {

                        // if (count($strFieldClass)) {
                        $strClass = "";
                        if ($strFieldClass != "") {
                            $dataSourceAllSpeks = $dataSource_all[$key_src];
                            $strClass = $dataSourceAllSpeks[$strFieldClass];
                            $str_label = "";
                            // foreach ($strFields as $strField2) {
                            //     $lbl = $dataSourceAllSpeks[$strField2];
                            //     $var = $lbl;
                            //     if ($str_label == "") {
                            //         $str_label .= "$var";
                            //     }
                            //     else {
                            //         $str_label = "$str_label $var";
                            //     }
                            //
                            // }
                            // $label_src = $str_label;
                        }
                        $cssClass = trim("$kolom_nama flat-redx $strClass");
                        $varForm .= "<label class='text-uppercase'><input class='$cssClass' type='radio' $required_field id='$anakan_ky" . "_" . "$key_src' name='$kolom_nama' value='$key_src' $eventKeyup> $label_src</label> &nbsp;";
                    }
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "text":
                    $varForm = "<div class='form-group row'>";
                    // $varForm .= "<div class='col-md-5'>";
                    $varForm .= "<input type='text' $required_field $editable id='$anakan_ky' autocomplete='off' placeholder='$str_label' name='$kolom' class='form-control' $urlTrigger value='$fValue' $eventKeyup>";
                    $varForm .= "<div id='notif_$anakan_ky'></div>";
                    $varForm .= "</div>";

                    $strForm .= $ly->form_group($label, $varForm, $show);
                    break;
                case "password":
                    $fValue = defaultPassword();
                    $varForm = "<div class='form-group row'>";
                    // $varForm .= "<div class='col-md-5'>";
                    $varForm .= "<input type='password' $required_field $editable id='$anakan_ky' autocomplete='off' placeholder='$str_label' name='$kolom' class='form-control' $urlTrigger value='$fValue' $eventKeyup>";
                    $varForm .= "<div id='notif_$anakan_ky'></div>";
                    $varForm .= "</div>";

                    $strForm .= $ly->form_group($label, $varForm, $show);
                    break;
                case "number":
                    $varForm = "<div class='form-group row'>";
                    $varForm .= "<input type='number' id='$anakan_ky' name='$kolom' autocomplete='off' placeholder='$str_label' class='form-control' value='$fValue'>";
                    $varForm .= "</div>";
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "hidden_text":
                    $varForm = "<div class='form-group row'>";
                    // $varForm .= "<div class='col-md-5'>";
                    $varForm .= "<input type='text' $required_field $editable id='$anakan_ky' autocomplete='off' placeholder='$str_label' name='$kolom' class='form-control' $urlTrigger value='$fValue' $eventKeyup>";
                    $varForm .= "<div id='notif_$anakan_ky'></div>";
                    $varForm .= "</div>";

                    $strForm .= $ly->form_group($label, $varForm, 1);
                    break;
                case "hidden":
                    $fValue = isset($dtId) ? $dtId : $kn;
                    $varForm = "<input type='text' id='$anakan_ky' name='$kolom' value='$fValue'>";
                    $strForm .= $ly->form_group($label, $varForm, 1);
                    break;
            }
        }

        /* -------------------------------------------------------------------------------------
         * button sumit
         * -------------------------------------------------------------------------------------*/
        $strButton = "";
        $strButton .= "<div class='col-md-12' style='margin-top: 25px;border-top: #f9f7f7 solid 1px;padding-top: 10px;'>";
        $strButton .= "<button type='button' class='btn btn-default text-uppercase pull-left' data-dismiss='modal'>close</button>";
        $strButton .= " <button type='submit' id='submit_data' class='btn btn-danger text-uppercase pull-right'>simpan</button>";
        $strButton .= "<button type='reset' id='reset_data' class='btn btn-default text-uppercase pull-right'>reset form</button> ";
        $strButton .= "</div>";

        /* -------------------------------------------------------------------------------------
         * penampil form di UI
         * -------------------------------------------------------------------------------------*/
        if (isset($dtId)) {
            $link_action = MODUL_PATH . "Data/editProcess/$segment_4/$segment_4";
            $url_form = base_url() . "Addons/AccessRight/add/Produk/$dtId";
        }
        else {
            $link_action = MODUL_PATH . "Data/addProcess/$segment_4/$segment_4";
            $url_form = base_url() . 'Addons/AccessRight/add/Produk/0';
        }
        // cekPink($url_form);

        $createSession_link = MODUL_PATH . "Data/createSessionData/Produk";
        $var = "";
        $var .= "<style>
            .form-group {
                margin-bottom: 2px !important;
            }
            .input-group{
                margin-left: -13px;
            }

        </style>";
        $var .= "<style>
            .sugestion-box ul {
                list-style: none;
                padding: 0;
                margin: 0;
                bborder: 1px solid #ccc;
                max-height: 200px;
                overflow-y: auto;
            }
            .sugestion-box li {
                padding: 2px 4px;
                cursor: pointer;
            }
            .sugestion-box li:hover {
                background-color: #f0f0f0;
            }
            .sugestion-box {
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
                border: 1px solid #ff9797;
                padding: 8px;
                 background: #fff;
                 border-radius: 5px;
            }            
            .detail-preview {
            width: 300px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
                padding: 8px;
                border: 1px solid #ff9797;
                background: #f9f9f9;
                border-radius: 5px;
            }
        .disabled-item {
            color: #888;
            pointer-events: none;
            ccursor: not-allowed;
            font-style: italic;
            color: #f49804;
        }
        .label-exist {
            font-size: 0.8em;
            color: red;
            margin-left: 5px;
        }
            </style>";
        $var .= "<div class='overflow-h'>";
        $var .= "<form method='post' id='myForm' action='$link_action' target='result' style='margin-top: 0px;'>";
        $var .= $strForm;
        $var .= $strButton;
        $var .= "</form>";
        $var .= "</div>";

        // $var .= "</div>";
        // $var .= "</div>";
        $var .= "<script>
            $('.selectpicker').selectpicker();
            $('#myModal').on('shown.bs.modal', function () {
                $('.selectpicker').selectpicker('refresh');
            });

        </script>";

        $adm_domain = ADM_DOMAIN;
        $var .= "<script>
            // Fungsi utama
            function cek_master_data(x, i, t, n) {
                if (x.length > 3) {
                    let url = t + '?key=' + x + '&kolom=' + n + '&mid=' + i;
                    $('#notif_' + i).load(url, function(response, status, xhr) {
                        // Cek apakah ada hasil atau tidak
                        if ($('#notif_' + i).text().trim() === '') {
                            $('#hasil_' + i).html('<button id=\"btn_search_api_'+i+'\" class=\"btn-search-api\">Cari di sumber lain</button>');
                            console.log('kosong');
                            // Pasang event click ke tombol
                            $('#btn_search_api_'+i).off('click').on('click', function() {
                                cari_dari_api_lain(x, i);
                            });
                        } else {
                            console.log('isi');
                            // $('#hasil_' + i).empty(); // Kosongkan hasil tambahan kalau ada hasil lokal
                        }
                    });
                }
            }
        
            // Debounce
            var debounceTimer;
            function debounceSearch(x, i, t, n) {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    if (x.length > 3) {
                        cek_master_data(x, i, t, n);
                    } else {
                        $('#hasil_' + i).html('<r>Ketik minimal 4 karakter</r>');
                    }
                }, 400);
            }

            // Khusus Employee (API-only) agar tidak mengganggu flow legacy.
            function cek_master_data_employee_dc(x, i, t, n) {
                if (x.length > 3) {
                    let url = t + '?key=' + x + '&kolom=' + n + '&mid=' + i;
                    $('#notif_' + i).load(url);
                } else {
                    $('#notif_' + i).html('<r>Ketik minimal 4 karakter</r>');
                }
            }

            var debounceTimerEmployeeDc;
            function debounceSearchEmployeeDc(x, i, t, n) {
                clearTimeout(debounceTimerEmployeeDc);
                debounceTimerEmployeeDc = setTimeout(() => {
                    cek_master_data_employee_dc(x, i, t, n);
                }, 400);
            }
         
            // Fungsi cari ke API eksternal           
            function cari_dari_api_lain(keyword, i) {
                let apiUrl = '$adm_domain'+'eusvc/Customers/seeItemAll/search/' + encodeURIComponent(keyword);
            
                $.get(apiUrl, function(response) {
                    let data = response.data;
                    if (data && data.length > 0) {
                        $('#sugestion-box').removeClass('hidden');
                        let html = '<ul>';
                        data.forEach(function(item) {
                            let isExisting = data_lokal_ids.includes(item.id);
                            let disabledClass = isExisting ? 'disabled-item' : '';
                            let note = isExisting ? ' <span class=\"label-exist\" title=\"tersambung ke DC\"><i class=\'fa fa-link\'></i></span>' : '';
                            
                            html += '<li class=\"sugestion-item ' + disabledClass + '\" ' +
                                    'data-id=\"' + item.id + '\" ' +
                                    'data-nama=\"' + item.nama + '\" ' +
                                    'data-phone=\"' + item.phone + '\" ' +
                                    'data-npwp=\"' + item.npwp + '\" ' +
                                    'data-nik=\"' + item.nik + '\" ' +
                                    'data-attn=\"' + item.attn + '\" ' +
                                    'data-credit_limit=\"' + item.credit_limit + '\" ' +
                                    'data-email=\"' + item.email + '\" ' +
                                    'data-kelurahan=\"' + item.kelurahan + '\" ' +
                                    'data-kecamatan=\"' + item.kecamatan + '\" ' +
                                    'data-alamat=\"' + item.alamat + '\">' +
                                    item.nama + note +
                                    '</li>';
                        });
                        html += '</ul>';
                        $('#hasil_' + i).html(html);
            
                        // Hover: tampilkan detail
                        $('.sugestion-item').hover(function() {                            
                            $('#detail_preview').html(
                                '<strong>' + $(this).data('nama') + '</strong><br>' +
                                '<br>dc_id: ' + $(this).data('id') + 
                                '<br>Alamat: ' + $(this).data('alamat') + 
                                '<br>Email: ' + $(this).data('email') +
                                '<br>Telepon: ' + $(this).data('phone') +
                                '<br>NPWP: ' + $(this).data('npwp') +
                                '<br>NIK: ' + $(this).data('nik') +
                                '<br>Kredit limit: ' + $(this).data('credit_limit') * 1 +
                                '<br>ATTN: ' + $(this).data('attn') +
                                ''
                            );

                        }, function() {
                            $('#detail_preview').html('');
                        });
            
                        // Klik: isi form
                        $('.sugestion-item').on('click', function() {
                            $('#nama').val($(this).data('nama'));
                            $('#alamat_1').val($(this).data('alamat'));
                            $('#npwp').val($(this).data('npwp'));
                            $('#nik').val($(this).data('nik'));
                            $('#contact_person').val($(this).data('attn'));
                            $('#kredit_limit').val($(this).data('credit_limit'));
                            $('#dc_id').val($(this).data('id'));
                            $('#email').val($(this).data('email'));
                            $('#hasil_' + i).html('');
                            $('#detail_preview').html('');
                            
                            $('#sugestion-box').addClass('hidden');
                            $('#submit_data').prop('disabled', false);
                        });
            
                    } 
                    else {
                        $('#hasil_' + i).html('<i>Tidak ada hasil dari DC</i>');
                    }
                }).fail(function() {
                    $('#hasil_' + i).html('<i>Gagal menghubungi API eksternal</i>');
                });
            }        
        </script> ";

        $var .= "<script>                      
            function cek_kolom_data(x,i,t,n) {
              if(n == 'barcode'){
                  let nilai_master = $('input[name=kode]').val();
                  
                  $('input[name=barcode]').val(nilai_master);
              }
            }
            
            function copy_to(n,x) {
              $('input[name=' + n + ']').val(x);
            }
            
            function copy_from(n,x) {
                let val_n = $('input[name=' + n + ']').val();
                
                $('input[name=' + x + ']').val(val_n);
            }
            
            function toggleInput(i,n) {
             
                var yesRadio = document.getElementById('kategori_id_2');
                var inputField = document.getElementById(n);
                var inputFieldNpwp = document.getElementById('npwp');
                var labelField = $('.nik');
                
                if (yesRadio.checked) {
                    inputField.disabled = false; // Enable input text
                    inputField.setAttribute('required', ''); // Disable input text
                    // $('#nik').attr('required', 'required');
                    $('.nik').append('<r>*</r>')
                    removeAppendedCharacter('npwp','*');
                } else {
                    inputField.disabled = true; // Disable input text
                     removeAppendedCharacter('nik','*');
                     $('.npwp').append('<r>*</r>');
                     // $('#npwp').attr('required', 'required');
                     inputFieldNpwp.setAttribute('required', '');
                     // document.getElementById('npwp').setAttribute('required', '');
                }
            }
            
            function removeAppendedCharacter(c,x) {
                $('.'+c).each(function() {
                    var content = $(this).text();
                    if (content.endsWith(x)) {
                        $(this).text(content.slice(0, -1));
                    }
                });
            }
         </script>";

        // $url_form = base_url() . 'statik/Data/formJembreng/Produk';

        $var .= "<script>          
            // function validatePenempatan(id) {
            //     var checkbox = $('#' + id);
            //     var tkolom = checkbox.data('kolom');
            //      var checkboxClass = checkbox.attr('class');
            //     // console.log(checkboxClass);
            //
            //     var otherCheckboxes = $('input[type=\"checkbox\"]').not('.pusat').filter(function() {
            //         return $(this).data('kolom') == tkolom;
            //     });
            //
            //    
            //    
            //     if (checkbox.hasClass('pusat')) {
            //         console.log(checkboxClass);
            //         if (checkbox.prop('checked')) {
            //             otherCheckboxes.prop('disabled', true);
            //         } else {
            //             otherCheckboxes.prop('disabled', false);
            //         }
            //     }
            //     else if (checkbox.hasClass('cabang')){
            //         console.log(checkboxClass);
            //     }                     
            // }  
            //
            // function validateFunction(id, c) {
            //     var checkbox = $('#' + id);
            //     var checkboxClass = $('input[type=\"radio\"].' + c);
            //     
            //     //  console.log('id: ', id);
            //     //  console.log('c: ', c);
            //     // console.log(checkbox);
            //     // console.log(checkboxClass);
            //        
            //    
            //     if (checkbox.hasClass('pusat')) {
            //         // console.log('pusat: ', checkboxClass);
            //
            //         // var checkboxClass = checkbox.attr('class');
            //         if (checkbox.prop('checked')) {
            //             console.log('tic');
            //            
            //             // $('input[type=\"radio\"].cabang').not('.pusat').prop('disabled', true);
            //             checkboxClass.not('.pusat').prop('disabled', true);
            //                                                              
            //             jQuery.each(checkboxClass,function(a,b) {
            //                 console.log(a);  
            //                 console.log(b);  
            //                 console.log($(b).hasClass('pusat'));
            //        
            //                 if($(b).hasClass('pusat')){
            //                     $(b).addClass('pusat-on');
            //                 }
            //             })
            //            
            //
            //         } else {
            //             console.log('un-tic');
            //            
            //             // $('input[type=\"radio\"].cabang').prop('disabled', false);
            //             checkboxClass.not('.pusat').prop('disabled', false);
            //             // checkboxClass.hasClass('pusat').removeClass('cabang-on');
            //             // $('input[type=\"radio\"].cabang').removeClass('cabang-on');
            //             if(checkboxClass.hasClass('pusat')){
            //                 checkboxClass.removeClass('pusat-on')
            //             }
            //         }
            //     }
            //     else if (checkbox.hasClass('cabang')){
            //       
            //         if (checkbox.prop('checked')) {
            //             console.log('cabang tic');
            //            
            //             jQuery.each(checkboxClass,function(a,b) {
            //                 if($(b).hasClass('pusat-on')){
            //                     console.log(b);
            //                     // $(b).addClass('pusat-on');
            //                 }
            //             }
            //             // $('input[type=\"radio\"].pusat').not('.cabang').prop('disabled', true);
            //             // if(checkboxClass.not('.pusat-on')){
            //             //      console.log('pusat-on');
            //             //     checkboxClass.prop('disabled', false);
            //             //    
            //             //     if(checkboxClass.hasClass('cabang')){
            //             //         checkboxClass.addClass('cabang-on')
            //             //     }
            //             // }
            //             //
            //             // if (checkboxClass.not('.cabang')){
            //             //     console.log('bukan cabang');
            //             //     checkboxClass.not('.cabang').prop('disabled', true);
            //             //     // checkboxClass.prop('disabled', true);
            //             // }
            //
            //         } else {
            //             console.log('un-tic');
            //            
            //             // $('input[type=\"radio\"].pusat').prop('disabled', false);
            //             checkboxClass.not('.cabang').prop('disabled', false);
            //            
            //             if(checkboxClass.hasClass('cabang')){
            //                     checkboxClass.removeClass('cabang-on')
            //             }
            //            
            //         }
            //     }                     
            // }
            
           $('#myForm').on('submit', function(event) {
                // Mendapatkan semua checkbox dengan kelas \"group_cabang\"
                var checkboxes = $('.group_cabang');
            
                // Jika tidak ada elemen dengan class tersebut, skip validasi
                if (checkboxes.length === 0) {
                    console.log('tidak ada group_cabang, skip validasi');
                    return; // lanjutkan submit form
                }
            
                // Memeriksa apakah setidaknya satu checkbox dipilih
                var atLeastOneChecked = checkboxes.is(':checked');
            
                if (!atLeastOneChecked) {
                    console.log('belum dipilih');
                    $('#error-message').show();
                    event.preventDefault(); // Mencegah pengiriman formulir
                } else {
                    console.log('sudah dipilih');
                    $('#error-message').hide();
                }
            });

            
            // $('.cabang').change(function() {
            function tambahForm(i,target_id) {
              var url_form = '$url_form';
                // var checkboxes = $('.cabang');
                var checkboxes = $('#'+i);
                var value = checkboxes.val();
                // var checkboxes_id = checkboxes.id;
                // console.log('huahahah:', target_id);
                // console.log('id:', i);
                // console.log('value:', value);
                // console.log(checkboxes);
                var id = 'anak-' + i.replace(/\s+/g, '-').toLowerCase();

                if (checkboxes.is(':checked')) {
                    $('#'+target_id).append('<div id=\"' + id + '\" class=\"jemb_anak\"></div>');
                    
                    $('#'+id).load(url_form + '?id=' + value);
                     $('#error-message').hide();
                } else {
                    $('#' + id).remove();
                }
            };
            
            $('.modal').on('shown.bs.modal', function () {
                $(this).removeAttr('aria-hidden');
            });

            
               // --- [1] Saat pertama kali halaman diload ---
            $('.group_cabang').each(function(){
                if ($(this).is(':checked')) {
                    let id = $(this).attr('id');
                    console.log('Checkbox sudah dicentang dari awal:', id);
                    
                    // panggil fungsi seperti saat user klik manual
                    tambahForm(id, 'jembrengan');
                }
            });
        
            // --- [2] Saat user mengubah checkbox ---
            $('.group_cabang').on('change', function(){
                let id = $(this).attr('id');
                let checked = $(this).is(':checked');
        
                console.log('Checkbox berubah:', id, '=>', checked);
        
                if (checked) {
                    tambahForm(id, 'jembrengan');
                } else {
                    // contoh: hapus form yang terkait jika uncheck
                    $('#'+id+'_form').remove();
                }
        
                // --- [3] Validasi hanya boleh satu centang ---
                let total_checked = $('.group_cabang:checked').length;
                if (total_checked > 1) {
                    $('#error-message').show();
                } else {
                    $('#error-message').hide();
                }
            });


        </script>";
        // $kval = isset($_GET['kval']) ? $_GET['kval'] : "";
        // $kn = isset($kategories[$kval]['jml_serial']) ? $kategories[$kval]['jml_serial'] : "";
        // $link_form = MODUL_PATH . "Data/addProdukForm/$xclass";
        // $link_sub = MODUL_PATH . "Data/addProdukSub/$xclass";
        // $var .= "<script>
        //     var kval = '$kval', kn = '$kn';
        //     if(kval != ''){
        //         $('#wadah_1').load('$link_form?kval='+ kval +'&n=' + kn);
        //     };
        //     function loadAnakan(x,n) {
        //       $('#wadah_sub').html('');
        //       $('#wadah_1').load('$link_form?kval='+ x +'&n=' + n);
        //     }
        //     function loadSubMain(x,n) {
        //       $('#wadah_sub').load('$link_sub?kval='+ x +'&n=' + n);
        //       $('#wadah_1').html('');
        //     }
        // </script>";

        echo $var;
    }

    public function edit()
    {

        $jsBottom = "";
        $content = "";
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "auth/Login");
            die();
        }
        //==menampilkan form pengubahan data berdasarkan datamodel (kelas data) dan id-nya yang bersesuaian

        $segment_4 = $this->uri->segment(4);
        $className = "Mdl" . $segment_4;
        $ctrlName = $segment_4;
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        $content = "";

        if (!$this->allowEdit) {
            $p = new Layout(get_class($this), "Wewenang ditolak", "application/template/blank.html");
            $content .= ("<div class='alert alert-danger'>");
            $content .= ("Anda tidak punya wewenang pada halaman ini<br>");
            $content .= ("<a href='" . base_url() . "'>Ke depan</a>");
            $content .= ("</div>");
            $p->render();
            die();
        }

        /*----------------------------------------------------------------------------------------------------------*/
        $this->load->model("Mdls/" . $className);
        $o = new $className;
        $indexFieldName = "id";
        $selectedID = $this->uri->segment(5);

        $tmp = $o->lookupByCondition(array(
            "id" => $selectedID,
        ))->result();

        $f = new MyForm($o, "edit", array(
            "id"      => "f1ed_" . $className,
            "method"  => "post",
            "enctype" => "multipart/form-data",
            "action"  => MODUL_PATH . get_class($this) . "/editProcess/$ctrlName/" . $selectedID,
            "target"  => "result",
            "class"   => "form-horizontal",
        ));

        $pf = isset($_GET['pfid']) ? trim($_GET['pfid']) : "";

        $f->openForm(MODUL_PATH . get_class($this) . "/editProcess/$ctrlName/" . $selectedID);
        $f->fillForm($className, $tmp, $pf);
        $f->closeForm();
        $fields = $o->getFields();

        $title = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : $ctrlName;
        $p = new Layout($title, "Ubah Data $title", "application/template/lte/index.html");

        $dataRel = isset($this->config->item('dataRelation')[$className]) ? $this->config->item('dataRelation')[$className] : array();
        $dataExtRel = isset($this->config->item('dataExtRelation')[$className]) ? $this->config->item('dataExtRelation')[$className] : array();

        $content .= "<div class='panel panel-danger'>";
        $content .= "<div class='panel-heading'>";
        $content .= "<span class='text-blue no-padding text-uppercase'><span class='fa fa-folder-open'> main editor</span>";
        $content .= "</div>";

        $content .= "<div class='panel-body ini-body'>";
        if ($this->updaterUsingApproval) {
            $content .= "<div class='alert alert-warning-dot text-center'>";
            $content .= ("This modification requires approval and this entry will be deactivated until being approved<br>");
            $content .= ("</div class='panel-body'>");
        }
        $content .= ($f->getContent());


        //region khusus penampil barcode produk
        if (isset($tmp[0]->barcode)) {
            $barcode = $tmp[0]->barcode;
            $function = isset($fields['barcode']["transformValue"]) ? $fields['barcode']["transformValue"] : 0;

            $barcode_f = null;
            if ($function != 0) {
                $barcode_f = barcode($barcode, $function, 300, 100);
            }

            $content .= $barcode_f != null ? "<div class='text-center'>" . $barcode_f . " ***</div>" : "";

            // ------------------QRCODE------------------------------------------------------------------------
            //<editor-fold desc="qrcode qr">
            $xID = $tmp[0]->id;
            $xNama = $tmp[0]->nama;
            $qrbase = "$xID|$xNama";
            $link_print = base_url() . "addons/Qr/doPrint?id_produk=$xID&jml=1";
            $this->load->library("Ciqrcode");
            $qr = new Ciqrcode();
            $qrcode = $qr->get_qrcode_umum($qrbase);
            // $qrcode = $this->qrcode->get_qrcode_umum($qrbase);
            $qrfile = base_url() . $qrcode['file'];
            $qrcode_f = "<img src='$qrfile' title='$xID' class='img-thumbnail' onclick=\"popBig('$link_print');\">";
            $content .= "<div class='text-center'>";
            $content .= $qrcode_f;
            $content .= "</div>";
            //</editor-fold>
        }
        //endregion

        $content .= "</div>";
        $content .= "</div>";
        $content .= "<script>
            function cek_master_data(x,i,t,n){
                let url = t + '?key='+x+'&kolom='+n;                
                // $('#notif' + i).load(url);
            }
        </script>";

        if (sizeof($dataRel) > 0) {
            $content .= "<div class='panel panel-info'>";
            $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
            foreach ($dataRel as $mdlName => $mSpec) {
                $tmpDataAccess = isset($this->config->item('heDataBehaviour')[$mdlName]) ? $this->config->item('heDataBehaviour')[$mdlName] : array(
                    "viewers"       => array(),
                    "creators"      => array(),
                    "creatorAdmins" => array(),
                    "updaters"      => array(),
                    "updaterAdmins" => array(),
                    "deleters"      => array(),
                    "deleterAdmins" => array(),
                );
                $allowView = false;
                $allowCreate = false;
                $allowEdit = false;
                $allowDelete = false;
                foreach ($mems as $mID) {
                    if (in_array($mID, $tmpDataAccess['viewers'])) {
                        $allowView = true;
                    }
                    if (in_array($mID, $tmpDataAccess['creators'])) {
                        $allowCreate = true;
                    }
                    if (in_array($mID, $tmpDataAccess['updaters'])) {
                        $allowEdit = true;
                    }
                    if (in_array($mID, $tmpDataAccess['deleters'])) {
                        $allowDelete = true;
                    }
                }

                $relations = array();
                $relationPairs = array();
                if (file_exists(APPPATH . "models/Mdls/$mdlName.php")) {
                    $this->load->model("Mdls/" . $mdlName);
                    $o = new $mdlName();
                    $fields = $o->getFields();
                    foreach ($fields as $f2Spec) {
                        if (isset($f2Spec['reference'])) {
                            if (array_key_exists($f2Spec['kolom'], $o->getListedFields())) {
                                $relations[$f2Spec['kolom']] = $f2Spec['reference'];
                                $this->load->model("Mdls/" . $f2Spec['reference']);
                                $o3 = new $f2Spec['reference']();
                                $tmp3 = $o3->lookupAll()->result();

                                if (sizeof($tmp3) > 0) {
                                    $mdlName2 = $f2Spec['kolom'];
                                    $relationPairs[$mdlName2] = array();
                                    foreach ($tmp3 as $row3) {
                                        $id = isset($row3->id) ? $row3->id : 0;
                                        $name = isset($row3->nama) ? $row3->nama : "";
                                        $relationPairs[$mdlName2][$id] = $name;
                                    }
                                }
                            }
                        }
                    }
                }

                $mdlLink = MODUL_PATH . get_class($this) . "/view/" . str_replace("Mdl", "", $mdlName) . "?reqField=" . $mSpec['targetField'] . "&reqVal=" . $selectedID;
                $mdlLinkEdit = MODUL_PATH . get_class($this) . "/delete";
                $content .= "<div class='panel-heading'>";
                $content .= "<span class='text-blue text-uppercase'>";
                $content .= "<a href='$mdlLink'>";
                $content .= "<span class='fa fa-folder-open'></span> " . $mSpec['label'];
                $content .= "</a>";

                if ($allowCreate) {
                    //                    $relPihak = "&cCode=_TR_466&pId=yes";
                    $relPihak = "&pihakId=$selectedID&pId=yes";
                    $addLink = MODUL_PATH . get_class($this) . "/add/" . str_replace("Mdl", "", $mdlName);
                    $addLink .= "?reqField=" . $mSpec['targetField'] . "&reqVal=" . $selectedID . $relPihak;

                    $addClick = "
                                BootstrapDialog.show(
                                    {
                                        title:'New " . $mSpec['label'] . "',
                                        message: $('<div id=xxx></div>').load('" . $addLink . "&pfid=xxx'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        closable:true,
                                    }
                                );";
                    $content .= " <span class='pull-right'>";
                    $content .= "<a class=\" btn btn-default btn-xs\" onClick=\"$addClick\" data-toggle='tooltip' data-placement='top' title='Add new " . $mSpec['label'] . "' class='btn btn-circle btn-xs btn-primary bg-blue-gradient'><span class='glyphicon glyphicon-plus'></a>";
                    $content .= "</span> ";
                }
                $content .= "</span>";
                $content .= "</div>";

                $content .= "<div class='panel-body'>";
                $this->load->model("Mdls/" . $mdlName);

                $o2 = new $mdlName();
                $o2->addFilter($mSpec['targetField'] . "='$selectedID'");
                $tmpo2 = $o2->lookupAll()->result();

                cekLime($this->db->last_query());
                cekHitam($mdlName);
                /*---data produk*/
                $konversi_fname = array(
                    "produk_nama" => "nama",
                );
                switch ($mdlName) {
                    case "MdlProdukPerSupplier":
                        // arrPrint($tmpo2);
                        $this->load->model("Mdls/MdlProduk");
                        $pr = new MdlProduk();
                        $prspeks = $pr->callSpecs();

                        $pluss = "";
                        $link_pencarian = MODUL_PATH . "Data/pencarian/Supplier/produk";
                        $content .= "<div>";
                        $content .= "<input type='text' class='form-control' onkeyup=\"$('#pencarian').load('$link_pencarian?mid=$selectedID&key='+encodeURI(this.value));\">";
                        $content .= "</div>";
                        $content .= "<div id='pencarian'></div>";
                        $content .= "<div id='insert'></div>";

                        break;
                }
                // awalnya ada disini.... digeser ke atas 13 april 2023 (relasi produk dengan supplier)
                //                $pluss = "";
                //                $link_pencarian = base_url(). "Data/pencarian/Supplier/produk";
                //                $content .= "<div>";
                //                $content .= "<input type='text' class='form-control' onkeyup=\"$('#pencarian').load('$link_pencarian?mid=$selectedID&key='+encodeURI(this.value));\">";
                //                $content .= "</div>";
                //                $content .= "<div id='pencarian'></div>";
                //                $content .= "<div id='insert'></div>";
                // ----------------------------------------------------------------------------------------------
                $mdlRel = str_replace("Mdl", "", $mdlName);

                $content .= "<table class='table table-condensed'>";
                if (sizeof($tmpo2) > 0) {
                    $content .= "<tr bgcolor='#f0f0f0'>";
                    $content .= "<td>No</td>";
                    foreach ($o2->getListedFields() as $fName => $label) {
                        $content .= "<td>$label</td>";
                    }
                    if (isset($mSpec["allowEdit"]) && $mSpec["allowEdit"] == true) {
                        $content .= "<td>Action</td>";
                    }
                    $content .= "</tr>";
                    $no = 0;
                    foreach ($tmpo2 as $row) {
                        //                        arrPrint($row);
                        $rel_id = $row->rel_id;
                        $produk_id = $row->produk_id;
                        $produk_speks = isset($prspeks[$produk_id]) ? $prspeks[$produk_id] : array();
                        $no++;
                        $content .= "<tr>";
                        $content .= "<td>$no</td>";
                        $llabels = "";
                        foreach ($o2->getListedFields() as $fName => $label) {
                            $fvalue = isset($row->$fName) ? $row->$fName : "";

                            $content .= "<td title='$fName'>";
                            if (array_key_exists($fName, $relations)) {
                                $fieldLabel = isset($relationPairs[$fName][$fvalue]) ? $relationPairs[$fName][$fvalue] : "unknown rel";
                            }
                            else {
                                $fieldLabel_db_0 = isset($fvalue) ? $fvalue : "noname";
                                $fname_2 = array_key_exists($fName, $konversi_fname) ? $konversi_fname[$fName] : $fName;
                                $fieldLabel_db = strlen($fieldLabel_db_0) < 1 ? (isset($produk_speks->$fname_2) ? $produk_speks->$fname_2 : "") : $fieldLabel_db_0;
                                $fieldLabel = $fieldLabel_db;
                            }
                            $llabels .= $fieldLabel;
                            $content .= $fieldLabel;
                            $content .= "</td>";
                        }
                        if (isset($mSpec["allowEdit"]) && $mSpec["allowEdit"] == true) {
                            //                            $mdlLinkEdit
                            $link_swal = "
                         swal({
                            type:'warning',
                            title: 'Hapus relasi $llabels',
                            html :'Anda Yakin?',
                            confirmButtonText: 'Continue',
                            showCancelButton:true,

                        }).then(function (result) {
$('#result').load('$mdlLinkEdit/$mdlRel/$rel_id?reload=1')
                        });";
                            $remove_label = $mSpec["allowEdit_label"];
                            $remove_label_notif = $mSpec["allowEdit_notif"];
                            //                            $content .= "<td><button class=\"btn btn-danger btn-xs hidden-print\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"\" onclick=\"delete_confirm('Peringatan','Hapus  Relasi $llabels dari daftar?','$mdlLinkEdit/$mdlName/$cur_id');\" data-original-title=\"hapus relasi\"><span class=\"glyphicon glyphicon-remove\"></span></button></td>";
                            $content .= "<td><button id='hapus_relasi'class=\"btn btn-danger btn-xs hidden-print\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"\" onclick=\"$link_swal\" data-original-title=\"hapus relasi\"><span class=\"glyphicon glyphicon-remove\"></span></button></td>";
                        }
                        $content .= "</tr>";
                    }

                }


                $content .= "</table class='table table-condensed'>";
            }

            // $content .= "</div>";
            $content .= "</div>";
            $content .= "</div>";
        }
        // $content .= "</div>";

        /*-------------------------------------
         * editor dalam iframe
         * -----------------------------------*/
        if (sizeof($dataExtRel) > 0) {
            $num = 0;
            foreach ($dataExtRel as $mSpec) {
                $num++;
                $content .= "<div class='panel panel-default' style='background:#f0f0f0;resize: vertical;'>";
                // $content .= "<div class='col-lg-12 col-md-12 col-sm-12'>";
                $content .= "<div class='panel-heading'>";
                // $content .= "<h5 class='text-blue text-uppercase no-padding'><span class='fa fa-folder-open'></span> " . $mSpec['label'] . "</h5>";
                $content .= "<span class='text-blue text-uppercase no-padding'><span class='fa fa-folder-open'></span> " . $mSpec['label'] . "</span>";
                $content .= "</div>";

                $content .= "<div class='panel-body' style='resize: vertical;'>";
                $mSpec['target'];
                $backLink = blobEncode(current_url());
                $iframeLink = base_url() . $mSpec['target'] . "&attached=1&sID=" . $selectedID . "&backLink=$backLink";
                // cekHere("$iframeLink");

                $content .= "<iframe id='result2' frameborder='0' width='100%' heights='100%' style='max-height:500px;position:relative;top:0px;left:0px;right:0px;bottom:0px;overflow:hidden;resize: vertical;' src='" . base_url() . $mSpec['target'] . "&attached=1&sID=" . $selectedID . "&backLink=$backLink\' onloadsss='javascript:resizeIframe(this);'>";
                $content .= "</iframe>";
                if (show_debuger() == 1) {
                    $content .= "<a href='javaScript:void(0);' onclick=\"window.open('$iframeLink&dock=1','mywin','width=1000,height=600');\">open New Window</a>";
                }

                $content .= "</div>"; // body
                $content .= "</div>"; // panel
                $content .= "<script>
//                  function resizeIframe(obj) {
//                    obj.style.height = obj.contentWindow.document.documentElement.scrollHeight + 'px';
//                  }
//                  $('iframe#result2').on('load', function(){
//                    resizeIframe(this)
//                    console.log( $(this).height() );
//                  })

                        function resizeIFrameToFitContent( iFrame ) {
//                            iFrame.width  = iFrame.contentWindow.outerWeight;
                            iFrame.height = iFrame.contentWindow.outerHeight;

//                            $(iFrame).css('min-height', iFrame.height);
                                
//                            console.log('iFrame.id', $(iFrame).attr('id'));
//                            console.log('iFrame.width', iFrame.width);
//                            console.log('iFrame.height', iFrame.height);
                  }

                    $('iframe#result2').on('load', function(){

                        setTimeout(function(){
                            var iFrame = document.getElementById('result2');
                            resizeIFrameToFitContent( iFrame );
                            var iframes = document.querySelectorAll('iframe');
                            for( var i = 0; i < iframes.length; i++) {
                                resizeIFrameToFitContent( iframes[i] );
                            }
                        }
                        , 1000)

                    })
                            


                </script>";
            }
        }

        // $content .= "</div class='col-lg-12 col-md-12 col-sm-12'>";
        // $content .= "</div class='row'>";

        $arrSpecs = array(
            "mdlName"   => "$className",
            "mainLabel" => ucwords($ctrlName),
            "images"    => array(),
            "parent_id" => $selectedID,
        );


        $jsBottom .= "

        function createQr(container, value, w='80',h='80'){
            var qrcode = new QRCode(container, {
                text: value, width: w, height: h,
                colorDark : '#000000',
                colorLight : '#ffffff',
                correctLevel : QRCode.CorrectLevel.H
            });
        }

        function testuing(barangam){ top.console.log('" . json_encode($arrSpecs) . "'); top.console.log(barangam)}

        var fname;
        var label;

        function tutorialQrCode(fname,label){
            Sweetalert2({
                title: 'CARA MENGGUNAKAN',
                html: `<div><img height='200' class='thumbnail' id='bc_tutorial'></div>`,
                confirmButtonText: 'Saya Mengerti',
                onOpen: ()=>{
                    $('#bc_tutorial').attr('src', 'https://s27389.pcdn.co/wp-content/uploads/2019/10/retail-innovation-changing-tech-consumer-employee-demands-1024x440.jpeg');
                }
            }).then( (result) => {
                if(result){
                    uploadFromSmartphone(fname,label);
                }
            });


        }

        function uploadFromSmartphone(fname,label){

            var arr_label = JSON.parse('{ \"key\":\"'+fname+'\", \"label\":\"'+label+'\"}');
            var arr_specs = " . json_encode($arrSpecs) . ";
                arr_specs = Object.assign(arr_label, arr_specs);
            var dateGenerator = new Date();
            var validQrBarcode = btoa(dateGenerator)+'_sanQR';
            Sweetalert2({
                title: 'upload your '+label+' from smartphone',
                html: `<div class='image-container' id='qrcode_container'></div><div class='text-green text-center text-bold' id='connection'></div>`,
                onOpen: ()=>{
                    createQr('qrcode_container',validQrBarcode,200,200);
                    var callback = `doLoadImagesFromQR('`+validQrBarcode+`','`+fname+`','`+label+`')`;
                    registerNewQrCode(validQrBarcode,arr_specs, callback);
                }
            })
            .then( (result) => {
                if(result){
                    stopQRChecker(validQrBarcode);
                }
            });;
        }

        function removeSessionQR(code=''){
            $.ajax({
              url: \"" . base_url() . "Images/clearSessionCheckQR/\"+code,
              beforeSend: function( xhr ) {
                xhr.overrideMimeType( \"text/plain; charset=x-user-defined\" );
              }
            })
              .done(function( data ) {
                    var parse = JSON.parse(data);
                    console.log(parse.description);
              });
        }

        function stopQRChecker(code=''){
            clearInterval(loadImagesFromQR);
            if(code!==''){
                 removeSessionQR(code);
            }
        }

        function registerNewQrCode(code='', arr_specs, callback){
            var specs = arr_specs;
            $.ajax({
              url: \"" . base_url() . "Images/registerNewQrCode/$selectedID/\"+code,
              method: 'post',
              data: specs,
            })
            .done( function(keluaran) {
                eval(callback)
            });
        }

        var loadImagesFromQR;
        var reloadLimit=0;
        var loadMS = 2000;

        function doLoadImagesFromQR(code='',fname,label) {
            clearInterval(loadImagesFromQR);
            loadImagesFromQR = setInterval( function(){
                console.log(loadMS + 'ms ' + code)
                console.log('fname: ' + fname)
                console.log('label: ' + label)
                $.ajax({
                  url: \"" . base_url() . "Images/checkQR/\"+code,
                  beforeSend: function( xhr ) {
                    xhr.overrideMimeType( \"text/plain; charset=x-user-defined\" );
                  }
                })
                  .done(function( data ) {

                    if ( console && console.log ) {
                        var parseData = JSON.parse(data);
                        if(parseData.limit < 1){
                            stopQRChecker(code)

                            var append = '';
                                append += `<div class='after'>`;
                                append += `<span onclick='uploadFromSmartphone(\"`+fname+`\",\"`+label+`\")'><i class='fa fa-refresh'></i><div style='font-size: 12px'>expired<br>click here to reload</div></span>`;
                                append += '</div>';

                            $('#qrcode_container').append(append);
                        }
                        else{
                            console.log( data );
                            console.log( parseData.limit );
                            if( parseData.image_url == 0 ){

//                                                        if(parseData.connection==0){
//                                                            $('#connection').html('belum ada device terhubung');
//                                                        }
//                                                        else{
//                                                            $('#connection').html(parseData.connection);
//                                                        }

                            }
                            else{
                                Sweetalert2({
                                    title: 'image <b>'+label+'</b> siap diupload',
                                    html: `<img height='260' src='`+parseData.image_url+`'>`,
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Simpan Image'
                                }).then((result)=>{
                                    if(result){
                                        $.ajax({
                                          url: \"" . base_url() . "Images/saveMobile/\"+parseData.qrcode,
                                          beforeSend: function( xhr ) {
                                            xhr.overrideMimeType( \"text/plain; charset=x-user-defined\" );
                                          }
                                        })
                                          .done(function( data ) {
                                                var ret = JSON.parse(data);
                                                if(ret.status == 'success'){
                                                    Sweetalert2('sukses', 'Image berhasil disimpan', 'success');
                                                    setTimeout( function(){ eval(ret.redirect) }, 1000);
                                                }
                                                else{
                                                    Sweetalert2('error', 'Image gagal disimpan, silahkan ulangi', 'error');
                                                    setTimeout( function(){ eval(ret.redirect) }, 1000);
                                                }
                                          });
                                    }
                                });

                                console.log(parseData.image_url);
                                stopQRChecker(code)
                            }
                        }
                    }
                  });
            }, loadMS*1 );
        }
        ";

        $data = array(
            "mode"     => "barcodeView",
            "title"    => "Data $ctrlName",
            "subTitle" => "Create new $ctrlName",
            "content"  => $content,
            "jsBottom" => $jsBottom,
        );

        $this->load->view('data', $data);
    }

    public function cloning()
    {
        $jsBottom = "";
        $content = "";
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "auth/Login");
            die();
        }
        //==menampilkan form pengubahan data berdasarkan datamodel (kelas data) dan id-nya yang bersesuaian

        $segment_4 = $this->uri->segment(4);
        $className = "Mdl" . $segment_4;
        $ctrlName = $segment_4;
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        $content = "";

        if (!$this->allowEdit) {
            $p = new Layout(get_class($this), "Wewenang ditolak", "application/template/blank.html");
            $content .= ("<div class='alert alert-danger'>");
            $content .= ("Anda tidak punya wewenang pada halaman ini<br>");
            $content .= ("<a href='" . base_url() . "'>Ke depan</a>");
            $content .= ("</div>");
            $p->render();
            die();
        }

        /*----------------------------------------------------------------------------------------------------------*/
        $this->load->model("Mdls/" . $className);
        $o = new $className;
        $indexFieldName = "id";
        $selectedID = $this->uri->segment(5);

        $tmp = $o->lookupByCondition(array(
            "id" => $selectedID,
        ))->result();

        $f = new MyForm($o, "add", array(
            "id"      => "f1ed_" . $className,
            "method"  => "post",
            "enctype" => "multipart/form-data",
            "action"  => MODUL_PATH . get_class($this) . "/cloneProcess/$ctrlName/" . $ctrlName,
            "target"  => "result",
            "class"   => "form-horizontal",
        ));

        $pf = isset($_GET['pfid']) ? trim($_GET['pfid']) : "";

        $f->openForm(MODUL_PATH . get_class($this) . "/cloneProcess/$ctrlName/" . $ctrlName);
        $f->fillForm($className, $tmp, $pf);
        $f->closeForm();
        $fields = $o->getFields();

        $title = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : $ctrlName;
        $p = new Layout($title, "Ubah Data $title", "application/template/lte/index.html");

        $dataRel = isset($this->config->item('dataRelation')[$className]) ? $this->config->item('dataRelation')[$className] : array();
        $dataExtRel = isset($this->config->item('dataExtRelation')[$className]) ? $this->config->item('dataExtRelation')[$className] : array();

        $content .= "<div class='panel panel-danger'>";
        $content .= "<div class='panel-heading'>";
        $content .= "<span class='text-blue no-padding text-uppercase'><span class='fa fa-folder-open'> main editor</span>";
        $content .= "</div>";

        $content .= "<div class='panel-body ini-body'>";
        if ($this->updaterUsingApproval) {
            $content .= "<div class='alert alert-warning-dot text-center'>";
            $content .= ("This modification requires approval and this entry will be deactivated until being approved<br>");
            $content .= ("</div class='panel-body'>");
        }
        $content .= $f->getContent();
        $content .= "</div>";
        $content .= "</div>";

        $arrSpecs = array(
            "mdlName"   => $className,
            "mainLabel" => ucwords($ctrlName),
            "images"    => array(),
            "parent_id" => $selectedID,
        );

        if (isset($_GET['satuan_nilai']) && $_GET['satuan_nilai'] * 1 > 0) {
            $satuan_nilai = trim($_GET['satuan_nilai'] * 1);
            $jsBottom .= "
                var satuan_nilai_ori = top.$('#_satuan_nilai').val();
                var nama_ori = top.$('#_nama').val();
                var barcode_ori = top.$('#_kode').val();

                top.$('#_satuan_nilai').val('$satuan_nilai').addClass('text-bold fa-2x text-green');
                top.$('#_nama').addClass('text-bold text-red');
                top.$('#_kode').addClass('text-bold text-red');

                top.$('#_satuan_nilai,#_nama,#_kode').bind('keyup', function(){
                    var thisJenis = $(this).attr('id');
                    var currentValue = $(this).val();
                    console.log( $(this).val() )
                    console.log('thisJenis: ' + thisJenis)

                    switch(thisJenis){
                        case '_kode':
                            if(currentValue!=barcode_ori){
                                $(this).removeClass('text-red').addClass('text-green');
                            }
                            else{
                                $(this).addClass('text-red').removeClass('text-green');
                            }
                        break;
                        case '_nama':
                            if(currentValue!=nama_ori){
                                $(this).removeClass('text-red').addClass('text-green');
                            }
                            else{
                                $(this).addClass('text-red').removeClass('text-green');
                            }
                        break;
                        case '_satuan_nilai':
                            if(currentValue!=satuan_nilai_ori){
                                $(this).removeClass('text-red').addClass('text-green');
                            }
                            else{
                                $(this).addClass('text-red').removeClass('text-green');
                            }
                        break;
                    }
                })
            ";
        }


        $data = array(
            "mode"     => "barcodeView",
            "title"    => "Data $ctrlName",
            "subTitle" => "Create new $ctrlName",
            "content"  => $content,
            "jsBottom" => $jsBottom,
        );

        $this->load->view('data', $data);
    }

    public function editFrom()
    {

        $content = "";
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "auth/Login");
            die();
        }

        //==menampilkan form pengubahan data berdasarkan datamodel (kelas data) dan id-nya yang bersesuaian
        $className = "Mdl" . $this->uri->segment(3);
        $ctrlName = $this->uri->segment(3);

        $dataExtRel = isset($this->config->item('dataExtRelation')[$className]["images"]) ? $this->config->item('dataExtRelation')[$className]["images"] : array();
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        //arrPrint($dataExtRel);
        $this->load->model("Mdls/" . $className);
        $o = new $className;

        $selectedID = $this->uri->segment(4);
        $origID = $this->uri->segment(5);

        $this->load->model("Mdls/" . "MdlDataTmp");
        $oTmp = new MdlDataTmp();
        $oTmp->addFilter("mdl_name='$className'");
        $oTmp->addFilter("_id='$selectedID'");

        $tmp = $oTmp->lookupAll()->result();
        $tmpContent = (object)unserialize(base64_decode($tmp[0]->content));

        /* -----------------------------------------------------------------------------------------------
         * jml maksimal data yg diperbolehkan, setting ada dimaisng2 model protected $maximumData = ...;
         * model yg tidak ada limit berarti unlimited
         * -----------------------------------------------------------------------------------------------
         */
        $maximumData = $o->getMaximumData();
        $o->setTokoId(my_toko_id());
        $jmlDataNow = $o->lookupJmlActive();
        $sisa_quota_data = $maximumData - $jmlDataNow;
        // cekOrange("$jmlDataNow sisa $sisa_quota_data");

        //arrPrint($tmpContent);

        $realObjName = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : get_class($this);
        $title = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : get_class($this);
        $p = new Layout($title, "Ubah Data $title", "application/template/lte/index.html");
        $f = new MyForm($o, "edit", array(
            "id"      => "f1",
            "method"  => "post",
            "enctype" => "multipart/form-data",
            "action"  => MODUL_PATH . get_class($this) . "/editProcessFrom/$ctrlName/" . $selectedID . "/$origID",
            "target"  => "result",
            "class"   => "form-horizontal",
        ));
        $f->openForm(MODUL_PATH . get_class($this) . "/editProcessFrom/$ctrlName/" . $selectedID . "/$origID");

        $content .= ("<table class='table table-condensed'>");
        $content .= ("<tr><td colspan='2' class='text-muted text-uppercase'><h4>data yang diajukan</h4></td></tr>");
        $ii = 0;
        foreach ($o->getFields() as $fName => $fSpec) {

            $fType = $fSpec['type'];
            $fInputType = $fSpec['inputType'];
            $fDataSource = isset($fSpec['dataSource']) ? $fSpec['dataSource'] : "";
            $fColName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
            $fLabel = isset($fSpec['label']) ? $fSpec['label'] : $fName;
            $content .= ("<tr>");
            $content .= ("<td class='text-muted'>$fLabel");
            $content .= ("</td>");
            $fieldLabel = isset($tmpContent->$fColName) ? $tmpContent->$fColName : "";
            //region terjemahan isi berdasat type data
            switch ($fType) {
                case "image":
                    $hasil = "<div class='thumbnail'>";
                    $styleImage = $fieldLabel !== '' ? "style='width: 35em'" : "style='width: 10em'";
                    $fieldLabel = $fieldLabel !== '' ? $fieldLabel : base_url() . "assets/images/img_blank.gif";
                    $hasil .= "<img src='$fieldLabel' class='img-responsive ($fieldLabel)' $styleImage >";
                    $hasil .= "<div class='caption'>";
                    $hasil .= "</div>";
                    $hasil .= "</div>";
                    $fieldLabel = $hasil;
                    $conten_f = "$fieldLabel";
                    break;

                case "blob":
                case "longbloob":
                case "mediumblob":
                    $isiBlop = $fieldLabel != null ? blobEncode($fieldLabel) : "";
                    if (is_array($isiBlop)) {
                        $hasil = "";
                        if (array_key_exists("image", $isiBlop)) {
                            $images = base64_encode($isiBlop["image"]);
                            $hasil = "<div class='thumbnail'>";
                            $hasil .= "<img src='$images' class='img-responsive' width='150px'>";
                            $hasil .= "<div class='caption'>";
                            $hasil .= "</div>";
                            $hasil .= "</div>";
                        }
                        else {
                            foreach ($isiBlop as $kBlop) {
                                $var = $fDataSource[$kBlop];
                                if ($hasil == "") {
                                    $hasil .= "$var";
                                }
                                else {
                                    $hasil = "$hasil, " . "$var";
                                }
                            }
                        }
                        $fieldLabel = $hasil;
                    }
                    $conten_f = "$fieldLabel";
                    break;
                case "password":
                    $fieldLabel = "*********";
                    $conten_f = "<span class='form-control'>$fieldLabel</span>";
                    break;
                default:
                    $conten_f = "<span class='form-control'>$fieldLabel</span>";
                    break;
            }
            //endregion
            //===if related
            if (array_key_exists($fColName, $this->relations)) {
                $fieldLabel = isset($this->relationPairs[$fColName][$fieldLabel]) ? "<span class='fa fa-folder-o' style='color:#ff7700;'></span> " . $this->relationPairs[$fColName][$fieldLabel] : "unknown rel";
            }
            $fContent = $fieldLabel;
            $disabled = isset($tmpContent->$fColName) ? "readonly" : "disabled";
            $content .= ("<td>");
            $content .= ("$conten_f");
            $content .= ("</td>");
            $content .= ("</tr>");
        }
        // arrPrint(dataExtRelation);
        if (sizeof($dataExtRel) > 0) {

            if (isset($tmpContent->images)) {
                $content .= ("<tr>");
                $content .= ("<td class='text-muted'>Add Images");
                $content .= ("</td>");
                $fieldLabel = isset($tmpContent->images) ? $tmpContent->images : "";
                $hasil = "<div class='thumbnail'>";
                $styleImage = $fieldLabel !== '' ? "style='width: 35em'" : "style='width: 10em'";
                $fieldLabel = $fieldLabel !== '' ? $fieldLabel : base_url() . "assets/images/img_blank.gif";
                $hasil .= "<img src='$fieldLabel' class='img-responsive ($fieldLabel)' $styleImage >";
                $hasil .= "<div class='caption'>";
                $hasil .= "</div>";
                $hasil .= "</div>";
                $fieldLabel = $hasil;
                $conten_f = "$fieldLabel";

                $content .= ("<td>");
                $content .= ("$conten_f");
                $content .= ("</td>");
                $content .= ("</tr>");
            }

        }


        $addRows = array(
            "proposal type" => $tmp[0]->propose_type,
            "tgl. diajukan" => formatTanggal($tmp[0]->proposed_date),
            "oleh"          => $tmp[0]->proposed_by_name,
            "ID data asli"  => $tmp[0]->orig_id,
        );
        $content .= ("<tr><td colspan='2' class='text-muted'>&nbsp;</td></tr>");
        $content .= ("<tr><td colspan='2' class='text-muted text-uppercase'><h4>informasi pengajuan</h4></td></tr>");
        foreach ($addRows as $key => $val) {
            $fColName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
            $content .= ("<tr>");
            $content .= ("<td class='text-muted'>$key");
            $content .= ("</td>");

            $content .= ("<td>");
            $content .= ("<input type='text' class='form-control' $disabled value='$val'>");
            $content .= ("</td>");
            $content .= ("</tr>");
        }
        $content .= ("</table width=100%>");

        // $sisa_quota_data =0;
        $viewButton = false;
        switch ($tmp[0]->propose_type) {
            case "add":
            case "edit":

                // $yesAction = "document.getElementById('result').src='" . base_url() . get_class($this) . "/doApproveFrom/$ctrlName/$selectedID/$origID';";
                // $noAction = "document.getElementById('result').src='" . base_url() . get_class($this) . "/doRejectFrom/$ctrlName/$selectedID/$origID';";
                $yesAction = MODUL_PATH . get_class($this) . "/doApproveFrom/$ctrlName/$selectedID/$origID";
                $noAction = MODUL_PATH . get_class($this) . "/doRejectFrom/$ctrlName/$selectedID/$origID";
                $btn_disabled = "";
                if ($origID > 0) {
                    $rejectAlertMsg = "pengajuan data ditolak, dan akan mengembalikan data keversi sebelumnya";
                    $approveAlertMsg = "persetujuan pengajuan perubahan data, akan langsung mengatifkan data";
                    $yesLabel = "terima pengajuan perubahan data baru";
                    $noLabel = "reject/tolak pengajuan";
                }
                else {
                    $rejectAlertMsg = "Data yang diajukan akan dihapus secara permanen";
                    $approveAlertMsg = "Seluruh isi dari konten, akan langsung aktif";
                    $yesLabel = "terima pengajuan data baru";
                    $noLabel = "reject/tolak pengajuan";
                    $btn_disabled = (($maximumData > 0) && ($sisa_quota_data == 0)) ? "disabled" : "";
                }
                $viewButton = (($this->allowEditApproval == true) || ($this->allowCreateApproval == true)) ? true : false;
                break;
            case "delete":
                // $yesAction = "document.getElementById('result').src='" . base_url() . get_class($this) . "/doApproveDeleteFrom/$ctrlName/$selectedID/$origID';";
                // $noAction = "document.getElementById('result').src='" . base_url() . get_class($this) . "/doRejectDeleteFrom/$ctrlName/$selectedID/$origID';";
                $yesAction = MODUL_PATH . get_class($this) . "/doApproveDeleteFrom/$ctrlName/$selectedID/$origID";
                $noAction = MODUL_PATH . get_class($this) . "/doRejectDeleteFrom/$ctrlName/$selectedID/$origID";
                $rejectAlertMsg = "Data akan kembali aktif";
                $approveAlertMsg = "Data akan dihapus secara permanen";
                $yesLabel = "delete data";
                $noLabel = "pengajuan delete ditolak";
                $viewButton = $this->allowDeleteApproval == true ? true : false;
                break;
        }

        $content .= ("<div class='row'>");
        $content .= ("<div class='col-sm-6'>");
        // $content .= ("<button type='button' class='btn btn-danger btn-block text-uppercase' href='JavaScript:void(0)' onClick =\"if(confirm('$rejectAlertMsg \\nContinue?')==1){$noAction}\">$noLabel</button>");
        $content .= ("<button type='button' class='btn btn-danger btn-block text-uppercase' href='JavaScript:void(0)' onClick =\"btn_alert_result('PERHATIAN!','$rejectAlertMsg','$noAction');\">$noLabel</button>");
        $content .= ("</div class='col-sm-6'>");
        if ($viewButton == true) {

            $content .= ("<div class='col-sm-6'>");
            // $content .= ("<button type='button' class='btn btn-success btn-block text-uppercase' href='JavaScript:void(0)' onClick =\"if(confirm('$approveAlertMsg \\nContinue?')==1){$yesAction}\">$yesLabel</button>");
            $content .= ("<button type='button' $btn_disabled class='btn btn-success btn-block text-uppercase' href='JavaScript:void(0)' onClick =\"btn_alert_result('PERHATIAN!','$approveAlertMsg','$yesAction');\">$yesLabel</button>");
            $content .= ("</div class='col-sm-6'>");
            $content .= ("</div class='row'>");

            if (($maximumData > 0) && ($sisa_quota_data == 0)) {
                $content .= "<div class='alert alert-warning text-uppercase text-center font-size-1-5' style='padding: 5px;margin-top: 10px;'>";
                $content .= "<span class='blink'>info :: </span> quota sudah digunakan seluruhnya $maximumData, <r>penambahan tidak diizinkan !</r>";
                $content .= "</div>";
            }
        }

        $f->closeForm();

        //$content .=("<div class='panel panel-default'>");
        //$content .=("<div class='alert' style='background:#e5e5c5;border:1px #cccccc solid;'>");
        $content .= ($f->getContent());
        //$content .=("</div>");

        $data = array(
            "mode"     => $this->uri->segment(2),
            "title"    => "Data $ctrlName",
            "subTitle" => "Create new $ctrlName",
            "content"  => $content,
        );

        echo $content;
        die();
        //        $this->load->view('data', $data);
    }

    public function deleteFrom()
    {

        $pageMode = isset($_GET['mode']) ? $_GET['mode'] : "view";
        $pageTemplate = (isset($_GET['mode']) && $_GET['mode'] == 'print') ? "application/template/blank.html" : "application/template/lte/index.html";
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "auth/Login");
            die();
        }
        //==menampilkan form pengubahan data berdasarkan datamodel (kelas data) dan id-nya yang bersesuaian
        $className = "Mdl" . $this->uri->segment(3);
        $ctrlName = $this->uri->segment(3);
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        $content = "";
        if (!$this->allowDelete) {
            $p = new Layout(get_class($this), "Wewenang ditolak", "application/template/blank.html");
            $content .= ("<div class='alert alert-danger'>");
            $content .= ("Anda tidak punya wewenang pada halaman ini<br>");
            $content .= ("<a href='" . base_url() . "'>Ke depan</a>");
            $content .= ("</div>");
            $p->render();
            die();
        }
        $this->load->model("Mdls/" . $className);
        $o = new $className;

        $indexFieldName = "id";
        $selectedID = $this->uri->segment(4);
        $origID = $this->uri->segment(5);

        $this->load->model("Mdls/" . "MdlDataTmp");
        $oTmp = new MdlDataTmp();
        $oTmp->addFilter("mdl_name='$className'");
        $oTmp->addFilter("_id='$selectedID'");

        $tmp = $oTmp->lookupAll()->result();
        $tmpContent = (object)unserialize(base64_decode($tmp[0]->content));
        $title = isset($this->config->item('lgMenuLabel')[get_class($this)]) ? $this->config->item('lgMenuLabel')[get_class($this)] : get_class($this);
        $p = new Page($title, "Ubah Data $title", $pageTemplate);
        $f = new MyForm($o, "edit", array(
            "id"      => "f1",
            "method"  => "post",
            "enctype" => "multipart/form-data",
            "action"  => MODUL_PATH . get_class($this) . "/editProcessFrom/$ctrlName/" . $selectedID . "/$origID",
            "target"  => "result",
            "class"   => "form-horizontal",
        ));
        $f->openForm(MODUL_PATH . get_class($this) . "/editProcessFrom/$ctrlName/" . $selectedID . "/$origID");

        $content .= ("<table width=100%>");
        $content .= ("<tr><td colspan='2' class='text-muted'><h4>data yang diajukan</h4></td></tr>");
        foreach ($o->getFields() as $fName => $fSpec) {
            $fColName = isset($fSpec['fieldName']) ? $fSpec['fieldName'] : $fName;
            $fLabel = isset($fSpec['label']) ? $fSpec['label'] : $fName;
            $content .= ("<tr>");
            $content .= ("<td class='text-muted'>$fLabel");
            $content .= ("</td>");
            $fContent = isset($tmpContent->$fColName) ? $tmpContent->$fColName : "";
            $disabled = isset($tmpContent->$fColName) ? "readonly" : "disabled";
            $content .= ("<td>");
            $content .= ("<input type='text' class='form-control' $disabled value='$fContent'>");
            $content .= ("</td>");
            $content .= ("</tr>");
        }
        $addRows = array(
            "tgl. diajukan" => $tmp[0]->proposed_date,
            "oleh"          => $tmp[0]->proposed_by_name,
            "ID data asli"  => $tmp[0]->orig_id,
        );
        $content .= ("<tr><td colspan='2' class='text-muted'>&nbsp;</td></tr>");
        $content .= ("<tr><td colspan='2' class='text-muted'><h4>informasi pengajuan</h4></td></tr>");
        foreach ($addRows as $key => $val) {
            $fColName = isset($fSpec['fieldName']) ? $fSpec['fieldName'] : $fName;
            $content .= ("<tr>");
            $content .= ("<td class='text-muted'>$key");
            $content .= ("</td>");
            $content .= ("<td>");
            $content .= ("<input type='text' class='form-control' $disabled value='$val'>");
            $content .= ("</td>");
            $content .= ("</tr>");
        }
        $content .= ("</table width=100%>");

        $yesAction = "document.getElementById('result').src='" . MODUL_PATH . get_class($this) . "/doApproveDeleteFrom/$ctrlName/$selectedID/$origID';";
        $noAction = "document.getElementById('result').src='" . MODUL_PATH . get_class($this) . "/doRejectDeleteFrom/$ctrlName/$selectedID/$origID';";

        if ($origID > 0) {
            $rejectAlertMsg = "jika pengajuan ini anda tolak, data tidak akan jadi dihapus";
            $approveAlertMsg = "jika pengajuan ini anda setujui, data akan benar-benar TERHAPUS";
        }
        else {
            $rejectAlertMsg = "pengajuan ini akan dihapus permanen";
            $approveAlertMsg = "pengajuan ini akan diteruskan menjadi data aktif";
        }

        $content .= ("<div class='row'>");
        $content .= ("<div class='col-sm-6'>");
        $content .= ("<a class='btn btn-danger btn-block' href='JavaScript:void(0)' onClick =\"if(confirm('$rejectAlertMsg \\nContinue?')==1){$noAction}\">tolak penghapusan</a>");
        $content .= ("</div class='col-sm-6'>");

        $content .= ("<div class='col-sm-6'>");
        $content .= ("<a class='btn btn-success btn-block' href='JavaScript:void(0)' onClick =\"if(confirm('$approveAlertMsg \\nContinue?')==1){$yesAction}\">setujui penghapusan</a>");
        $content .= ("</div class='col-sm-6'>");
        $content .= ("</div class='row'>");

        $f->closeForm();

        $content .= ($f->getContent());

        echo $content;
        die();

    }

    public function addProcess()
    {

        // if (isset($_POST['id'])) {
        //     unset($_POST['id']);
        // }
        arrPrint($_POST);
        $_POST = trimArray($_POST);
        // arrPrintHijau($_POST);
        $arrAlert = array(
            "html"              => "<img src='" . base_url() . "public/images/sys/loader-100.gif'> <br>Please wait ... ... ,<br>saving data<br>",
            "showConfirmButton" => false,
            "allowOutsideClick" => false,
        );

        // echo swalAlert($arrAlert);

        $content = "";
        //==menyimpan inputan data baru ke dalam datamodel, lalu dari datamodel ke database (dilakukan oleh CI)

        $ctrlName = $this->uri->segment(4);
        $className = "Mdl" . $ctrlName;
        cekHere("$className");
        $dcomConf = isset($this->config->item("dataPostProcessors")[$className]) ? $this->config->item("dataPostProcessors")[$className][0] : array();//cek ada Dcomnya tidak
        $this->load->model("Mdls/" . $className);
        $cabang_id = my_cabang_id();

        /* -----------------------------------------
         * pengecualian masing2 model
         * ---------------------------------------*/
        switch ($className) {
            case "MdlProduk":
                arrPrintKuning($_POST);
                if (!isset($_POST["jml_serial"])) {
                    // matiDisini("serial tidak terdeteksi");
                }
                break;
        }

        $kval_ = isset($_POST["kval"]) ? $_POST["kval"] : "";
        $mainObj = $o = new $className;
        $f = new MyForm($o, "addProcess");

        // arrPrintPink($data);
        // matiHere(__LINE__);
        $inserted = array();
        if ($f->isInputValid()) { //==jika validasi lengkap
            if (sizeof($o->getUnionPairs()) > 0) {
                if ($f->isUnionValid()) {
                }
                else {
                    $errMsg = "";
                    foreach ($f->getValidationResults() as $err) {
                        $errMsg .= "Error ini <strong>$err[fieldLabel]</strong>:  $err[errMsg]<br>";
                    }
                    echo "<script>top.document.getElementById('btnSave').disabled=false;</script>";
                    die(lgShowAlert($errMsg));
                }
            }
            cekHijau(__LINE__ . "sampai");
            $this->db->trans_start();
            $data2 = array();
            $multi_param = array();

            foreach ($o->getFields() as $fieldName => $spec) {
                $fName = isset($spec['kolom']) ? $spec['kolom'] : $fieldName;
                $defaultValue = isset($spec['defaultValue']) ? $spec['defaultValue'] : "";
                if (isset($spec['inputType'])) {
                    $inputType = $spec['inputType'];
                    $multi_rel = isset($spec['multi_rel']) ? $spec['multi_rel'] : "";

                    if ($multi_rel == true) {
                        $multi_param[$fieldName] = $spec;
                    }

                    cekMerah($inputType);
                    switch ($inputType) {
                        case "checkbox":
                            // $data2[$fName] = base64_encode(serialize($this->input->post($fName)));
                            $data2[$fName] = ($this->input->post($fName));
                            break;
                        case "qtyFillBox":
                            $data[$fName] = base64_encode(serialize($this->input->post($fName)));
                            break;
                        case "texts":
                            if (isset($spec['dataParams'])) {
                                $tmp = array();
                                foreach ($spec['dataParams'] as $param) {
                                    $tmp[$param] = $this->input->post($fName . "_" . $param);
                                }
                                $data[$fName] = base64_encode(serialize($tmp));
                            }
                            break;
                        case "password":
                            $data[$fName] = md5($this->input->post($fName));
                            break;
                        case "file":
                            if ($_FILES[$fName]['size'] > 0) {
                                //                                $image["image"] = file_get_contents($_FILES[$fName]['tmp_name']);
                                //                                $data[$fName] = blobEncode($image);
                                //
                                //                                                                    arrPrint($data);
                                //                                    die();

                                $request = curl_init(cdn_upload_images());
                                $realpath = realpath($_FILES[$fName]['tmp_name']);
                                curl_setopt($request, CURLOPT_POST, true);
                                $fields = [
                                    //                                    'file' => new \CurlFile($realpath, $_FILES[$fName]['type'], $_FILES[$fName]['name']),
                                    'file'          => "@" . $realpath . ";filename=" . $_FILES[$fName]['name'] . ";type=" . $_FILES[$fName]['type'],
                                    'server_source' => $_SERVER['HTTP_HOST'],
                                ];
                                curl_setopt($request, CURLOPT_POSTFIELDS, $fields);
                                curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
                                $cUrl_result = json_decode(curl_exec($request));

                                curl_close($request);


                                if (isset($cUrl_result->status) && $cUrl_result->status == 'success') {
                                    //                                    $imagesBlob["files"] = $cUrl_result->full_url;
                                    //                                    $dataLast = array_replace($data, $imagesBlob);
                                    $data[$fName] = $cUrl_result->full_url;
                                    //
                                    //                                                                        arrPrint($data);
                                    //                                                                        die();
                                }
                                else {
                                    echo "<script>top.swal('error', 'image tidak valid, coba untuk ganti gambar yang akan di upload', 'error');</script>";
                                    die();
                                }

                            }
                            else {
                                cekHEre("$fName no image");
                                $data[$fName] = "";
                            }
                            break;
                        case "image":
                            if ($_FILES[$fName]['size'] > 0) {
                                // arrPrint($_FILES[$fName]);
                                $request = curl_init(cdn_upload_images());
                                $realpath = realpath($_FILES[$fName]['tmp_name']);
                                curl_setopt($request, CURLOPT_POST, true);
                                // cekMErah(cdn_upload_images());
                                $fields = [
                                    'file'          => new \CurlFile($realpath, $_FILES[$fName]['type'], $_FILES[$fName]['name']),
                                    'server_source' => $_SERVER['HTTP_HOST'],
                                ];
                                curl_setopt($request, CURLOPT_POSTFIELDS, $fields);
                                curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
                                $cUrl_result = json_decode(curl_exec($request));
                                // echo ($cUrl_result);
                                curl_close($request);


                                if (isset($cUrl_result->status) && $cUrl_result->status == 'success') {
                                    $data[$fName] = $cUrl_result->full_url;
                                }
                                else {
                                    echo "<script>top.Sweetalert2('error', 'image tidak valid, coba untuk ganti gambar yang akan di upload', 'error');</script>";
                                    die(__LINE__);
                                }

                            }
                            else {
                                cekHEre("$fName no image");
                                $data[$fName] = "";
                            }
                            break;
                        case "hidden":
                            // cekHitam($fName);
                            break;
                        case "hidden_text":
                        case "hidden_show":
                            // cekHitam($fName);
                            $data[$fName] = $defaultValue;
                            break;
                        case "textarea":
                            //                            $data[$fName] = nl2br($this->input->post($fName));
                            $data[$fName] = $this->input->post($fName);
                            //                            print_r($data);
                            //                            matiHere("hiksss");
                            break;
                        default:
                            $data[$fName] = heTrimAvoidedChars($this->input->post($fName));
                            break;
                    }
                }
                else {
                    switch ($spec['type']) {
                        case "varchar":
                            $data[$fName] = $this->input->post($fName);
                            break;
                        case "int":
                            $data[$fName] = $this->input->post($fName);
                            break;
                        case "date":
                            $data[$fName] = date("Y-m-d");
                            break;
                        case "datetime":
                            $data[$fName] = date("Y-m-d H:i:s");
                            break;
                        case "timestamp":
                            $data[$fName] = date("Y-m-d H:i:s");
                            break;
                        default:
                            $data[$fName] = $this->input->post($fName);
                            break;
                    }
                }
                // echo __LINE__;
                cekBiru(__LINE__ . " masuk $fieldName");

                /* --------------------------------------------------------
                 * langsung ngisi kolom di main table dari kolom erference (pendukung)
                 * --------------------------------------------------------*/
                if (isset($spec['strField']) || isset($spec["referenceDatas"])) {
                    if (isset($spec["reference"])) {
                        // cekKuning(__LINE__ . " masuk");

                        $this->load->model("Mdls/" . $spec["reference"]);
                        $idnya = $this->input->post($spec["kolom"]);
                        $tmpRe = new $spec["reference"]();

                        if (!is_array($idnya) && $idnya != 0) {
                            arrPrintWebs($idnya);
                            // matiHere(__LINE__);
                            $tmpFields = $tmpRe->lookupByID($idnya)->result();
                            // showLast_query("biru");
                            // cekHere(count($tmpFields));
                            // arrPrintPink($tmpFields);

                            if (isset($spec['strField'])) {
                                $strField = isset($tmpFields[0]->$spec["strField"]) ? $tmpFields[0]->$spec["strField"] : "";
                                $data[$spec["kolom_nama"]] = $strField;
                            }

                            // cekHijau("$fieldName $inputType");
                            if (isset($spec["referenceDatas"])) {
                                foreach ($spec["referenceDatas"] as $kolomSrc => $kolomTarget) {
                                    $data[$kolomTarget] = $tmpFields[0]->$kolomSrc;
                                }
                            }
                        }
                        else {
                            $this->db->where_in("id", $idnya);
                            $tmpFields = $tmpRe->lookupAll()->result();
                            // showLast_query("lime");
                            foreach ($tmpFields as $tmpField) {
                                $strField = isset($tmpField->$spec["strField"]) ? $tmpField->$spec["strField"] : "";
                                if (isset($spec["kolom_nama"])) {
                                    $data2[$spec["kolom_nama"]][$tmpField->id] = $strField;
                                }
                            }
                        }
                    }
                }

                /* --------------------------------------------
                 * pengabungan nilai 2 kolom untuk disimpan
                 * --------------------------------------------------*/
                if (isset($spec['concat'])) {
                    // cekHitam($fName);
                    $fConcats = isset($spec['concat']) ? $spec['concat'] : $fName;
                    // arrPrintKuning($fConcats);
                    $concatData = "";
                    foreach ($fConcats as $fConcat) {
                        $concatData .= $_POST[$fConcat];
                    }
                    $data[$fName] = $concatData;
                }
            }
            // arrPrintPink($concatData);
            // arrPrintHijau($data2);
            // arrPrintHijau($data);
            // matiHere(__LINE__);

            if (sizeof($o->getAutoFillFields()) > 0) {
                foreach ($o->getAutoFillFields() as $mainCol => $autoFieldsCal) {
                    $data[$mainCol] = makeValue($autoFieldsCal, $this->input->post(), $this->input->post(), 0);
                }
            }
            if (sizeof($o->getFilters()) > 0) {
                foreach ($o->getFilters() as $k => $v) {
                    $condPair = explode("=", $v);
                    if (sizeof($condPair) > 1) {
                        $data[$condPair[0]] = trim($condPair[1], "'");
                    }
                }
            }
            $this->load->model("Mdls/" . "MdlDataTmp");
            $dTmp = new MdlDataTmp();
            $tmpData = array(
                "mdl_name"         => $className,
                "mdl_label"        => $ctrlName,
                "proposed_by"      => $this->session->login['id'],
                "proposed_by_name" => $this->session->login['nama'],
                "proposed_date"    => dtimeNow(),
                "content"          => blobEncode($data),
            );

            $validateDataFields = sizeof($o->getValidateData()) > 0 ? $o->getValidateData() : array();
            $tmpOrig = array();
            cekBiru(__LINE__ . " masuk");
            if (sizeof($validateDataFields) > 0) {
                $where = array();
                foreach ($validateDataFields as $fieldsValidate) {
                    $where[$fieldsValidate] = $data[$fieldsValidate];
                }
                $tmpOrig = $o->lookupByCondition($where)->result();
                $bNama = $tmpOrig[0]->biaya_nama;
                $bProduk = $tmpOrig[0]->produk_nama;
                $bProdukId = $tmpOrig[0]->produk_id;
            }
            cekBiru(__LINE__ . " masuk");
            if (sizeof($tmpOrig) > 0) {
                cekHere(":: HAHAHA ");
                if ($bProdukId > 0) {
                    $where2 = array("produk_id" => $bProdukId);
                }
                else {
                    $where2 = array();
                }
                $tmpOrig2 = $o->lookupByCondition($where2)->result();
                showLast_query("biru");
                arrPrint($tmpOrig2);

                $hasil = "";
                $hasil .= "$bNama  already set up<br>";
                foreach ($tmpOrig2 as $itemOrigs) {
                    $bNama2 = $itemOrigs->biaya_nama;
                    $bNilai2 = formatField("harga", $itemOrigs->nilai);

                    foreach ($o->getListedFieldsView() as $val) {
                        $bNama2 = $itemOrigs->$val;
                        $bNilai2 = isset($itemOrigs->nilai) ? formatField("harga", $itemOrigs->nilai) : "";
                        $var = "$bNama2 <span>$bNilai2</span>";
                        if ($hasil == "") {
                            $hasil .= "$var";
                        }
                        else {
                            $hasil = "$hasil<br>$var";
                        }
                    }


                }

                $bJudul = "$bProduk";
                $alerts = array(
                    "type"  => "warning",
                    "title" => $bJudul,
                    "html"  => $hasil,
                );
                echo swalAlert($alerts);
                echo "<script>top.document.getElementById('btnSave').disabled=false;</script>";
                die();
                matiHere("data $bNama  already exist on $bProduk, no data change<hr>");
                //udah ada data ngapain ditambah lagi dengan id sama.....
            }
            cekBiru(__LINE__ . " masuk");

            if ($this->creatorUsingApproval) {
                cekHere("approval");
                $insertID = $dTmp->addData($tmpData, $dTmp->getTableName()) or die(lgShowError("Gagal menulis pengajuan data", __FILE__));
                $this->session->errMsg = "Data proposal has been saved and pending approval";
                $this->load->model("Mdls/" . "MdlDataHistory");
                $hTmp = new MdlDataHistory();
                $tmpHData = array(
                    "orig_id"            => 0,
                    "mdl_name"           => $className,
                    "mdl_label"          => get_class($this),
                    "old_content"        => "",
                    "new_content"        => base64_encode(serialize($data)),
                    "new_content_intext" => print_r($data, true),
                    "label"              => "proposed",
                    "oleh_id"            => $this->session->login['id'],
                    "oleh_name"          => $this->session->login['nama'],
                );
                $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
                cekHitam($this->db->last_query());
            }
            else {
                cekBiru(__LINE__ . " masuk tanpa approval");
                $validateDataFields = sizeof($o->getValidateData()) > 0 ? $o->getValidateData() : array();
                $tmpOrig = array();
                if (sizeof($validateDataFields) > 0) {
                    $where = array();
                    foreach ($validateDataFields as $fieldsValidate) {
                        $where[$fieldsValidate] = $data[$fieldsValidate];
                    }
                    $tmpOrig = $o->lookupByCondition($where)->result();
                }
                if (sizeof($tmpOrig) > 0) {
                    matiHere("data already exist, no data change");
                    //udah ada data ngapain ditambah lagi dengan id sama.....
                }

                /** --------------------------------------------------------------
                 * data utama insert tapi kalau yg insertkan ada musti ngupdate dowang
                 * --------------------------------------------------------------*/
                $mainDatas = $data;
                if (isset($_POST['id']) && $_POST['id'] > 0) {
                    arrPrintPink($validateDataFields);
                    $o->setFilters(array());
                    $where = array(
                        "id" => $_POST['id'],
                    );
                    $data = array_filter($data);
                    // $data = array();
                    // $data['is_'.strtolower($ctrlName)] = 1;
                    arrPrintKuning($data);
                    $mainInsertId = $insertID = $o->updateData($where, $data, $o->getTableName()) or die(lgShowError(__LINE__ . " Gagal menulis data", __FILE__));
                }
                else {
                    cekKuning("masuk insert " . __LINE__);
                    // arrPrintKuning($data);
                    $mainInsertId = $insertID = $o->addData($data, $o->getTableName()) or die(lgShowError(__LINE__ . " Gagal menulis data", __FILE__));
                }
                $supplier_id = isset($data['supplier_id']) ? $data['supplier_id'] : null;
                showLast_query("orange");

                /** -----------------------------------------------------------------------------
                 * tarikan ke table multy
                 * -----------------------------------------------------------------------------*/
                if (count($data2)) {
                    // arrPrintPink($multi_param);
                    foreach ($multi_param as $fieldKey => $multiSpeks) {
                        $mdl2 = $multiSpeks['multi_mdl'];
                        $mkolom2 = $multiSpeks['kolom'];
                        $mparent = $multiSpeks['multi_parent'];
                        cekHere(__LINE__ . " $mdl2");
                        $this->load->model("Mdls/$mdl2");
                        $md2 = new $mdl2();
                        $multiDatas = $data2[$fieldKey];

                        $mData = array();
                        foreach ($multiDatas as $multiData) {

                            $mData[$mparent] = $insertID;
                            $mData[$mkolom2] = $multiData;
                            $rr = $md2->addData($mData, $md2->getTableName());
                            showLast_query("hijau");

                            /* --------------------------------------
                             * menyimpan hak akses
                             * --------------------------------------*/
                            if ($mdl2 == "MdlEmployeeMembershipCabang") {
                                cekBiru("membuat hak akses " . __LINE__);
                                $cabang_akses = $_POST['cabang_akses'][$multiData];
                                arrPrint($cabang_akses);

                                $this->load->model("Mdls/MdlEmployeeHakAkses");
                                $ha = new MdlEmployeeHakAkses();

                                foreach ($cabang_akses as $ha_jenis => $cabang_aks) {
                                    foreach ($cabang_aks as $ha_step => $cabang_ak) {

                                        $haDatas = array(
                                            "employee_id" => $insertID,
                                            "cabang_id"   => $multiData,
                                            "jenis"       => $ha_jenis,
                                            "step"        => $ha_step,
                                            "akses"       => $cabang_ak,
                                        );
                                        $sss = $ha->addData($haDatas);
                                        showLast_query("kuning");


                                        $this->load->model("Mdls/MdlMenu");
                                        $emp = new MdlMenu();
                                        $haDatas = array(
                                            "employee_id"   => $insertID,
                                            // "cabang_id"   => $multiData,
                                            "menu_category" => $ha_jenis,
                                            "active"        => 1,
                                            "author"        => my_id(),
                                            "crud_id"       => $ha_step,
                                            "steps"         => $ha_step,
                                            // "akses"       => $cabang_ak,
                                        );
                                        $sss = $emp->addData($haDatas);
                                        showLast_query("hijau");

                                    }
                                }
                            }

                        }
                    }
                }
                //                arrPrintPink($mainDatas);

                //region takbahan Dcom
                if (sizeof($dcomConf) > 0) {
                    /** ---------------------------
                     * baca api customer
                     * */
                    // arrPrintHijau($data);
                    $dcom = true;
                    if (isset($data['dc_id'])) {
                        $dc_id = $data['dc_id'];
                        cekBiru("baca api dari $dc_id");

                        $adm_domain = ADM_DOMAIN . "eusvc/Customers/seeItemAll/id/$dc_id";
                        $api_respons = call_curl($adm_domain);
                        $api_datas = $api_respons["data"][0];

                        $data_address = $api_datas["address"][0];
                        $data_billing = $api_datas["billing"][0];
                        // arrPrintKuning($data_address);
                        // arrPrint($data_billing);

                        if (count($api_datas["address"]) > 0) {
                            $dcom = false;
                            cekLime("nulis addr");
                            $kolomKonversiAddr = [
                                "telp"  => "tlp",
                                "telp2" => "tlp_2",
                                "telp3" => "tlp_3",
                            ];
                            $this->load->Model("Mdls/MdlCustomerAddress");
                            $m = new MdlCustomerAddress();
                            unset($data_address["id"]);
                            $data_address["customer"] = $insertID;
                            $data_address["extern_id"] = $insertID;

                            $data_baru = [];
                            foreach ($data_address as $kolom_addr => $kolom_nilai) {
                                if (key_exists($kolom_addr, $kolomKonversiAddr)) {
                                    $data_baru[$kolomKonversiAddr[$kolom_addr]] = $kolom_nilai;
                                }
                                else {
                                    $data_baru[$kolom_addr] = $kolom_nilai;
                                }
                            }

                            // arrPrintPink($data_baru);
                            $m->addData($data_baru);
                            showLast_query("kuning");
                        }
                        else {
                            //default
                        }

                        if (count($api_datas["billing"]) > 0) {
                            cekLime("nulis bill");
                            $this->load->Model("Mdls/MdlCustomerBillAddress");
                            $m = new MdlCustomerBillAddress();

                            unset($data_billing["id"]);
                            $data_billing["customer"] = $insertID;
                            $data_billing["extern_id"] = $insertID;
                            $data_baru = [];
                            foreach ($data_billing as $kolom_addr => $kolom_nilai) {

                                if (key_exists($kolom_addr, $kolomKonversiAddr)) {
                                    $data_baru[$kolomKonversiAddr[$kolom_addr]] = $kolom_nilai;
                                }
                                else {
                                    $data_baru[$kolom_addr] = $kolom_nilai;
                                }

                            }

                            $m->addData($data_baru);
                            showLast_query("pink");

                            $dcom = false;
                        }
                        else {
                            //default
                        }
                    }

                    // matiHere(__LINE__);
                    if ($dcom == true) {
                        $inParam = array_merge($inserted, $data);
                        $className = "DCom" . $dcomConf;
                        $this->load->Model("DComs/" . $className);
                        $d = new $className();
                        $d->setWriteMode("insert");
                        $d->pair($inParam) or die("Tidak berhasil memasang  values pada dcom-processor: $className/" . __FUNCTION__ . "/" . __LINE__);
                        $gotParams = $d->exec();
                        showLast_query("merah");
                    }
                }
                //endregion
                // matiHere(__LINE__);
                /* --------------------------------------------------------------
                 * historycal
                 * --------------------------------------------------------------*/
                $this->load->model("Mdls/" . "MdlDataHistory");
                $hTmp = new MdlDataHistory();
                $tmpHData = array(
                    "orig_id"            => 0,
                    "mdl_name"           => $className,
                    "mdl_label"          => get_class($this),
                    "old_content"        => "",
                    "new_content"        => base64_encode(serialize($data)),
                    "new_content_intext" => print_r($data, true),
                    "label"              => "applied",
                    "oleh_id"            => $this->session->login['id'],
                    "oleh_name"          => $this->session->login['nama'],
                );
                $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));

                /* ---------------------------------------------------------------
                 * tested pada auto COA yg pakai aaproval masuk di doApproveFrom
                 * --------------------------------------------------------------*/
                if (method_exists($mainObj, "getConnectingData")) {
                    $merek_id = isset($mainDatas['merek_id']) ? $mainDatas['merek_id'] : null;
                    $merek_nama = isset($mainDatas['merek_nama']) ? $mainDatas['merek_nama'] : null;
                    $supplier_nama = isset($mainDatas['supplier_nama']) ? $mainDatas['supplier_nama'] : null;
                    $supplier_id = isset($mainDatas['supplier_id']) ? $mainDatas['supplier_id'] : null;
                    $nama = ucwords($mainDatas['nama']);
                    $negara = isset($data['country']) ? $data['country'] : "";
                    $extern_tipe = $negara == "ID" ? "lokal" : "non_lokal";
                    $my_name = my_name();
                    $connectings = $mainObj->getConnectingData();

                    /*-------------------menonaktifkan relasi supplier bila define produk jasa-------------------*/
                    if (isset($mainDatas['kategori_nama']) && $mainDatas['kategori_nama'] == 'jasa') {
                        unset($connectings['MdlProdukPerSupplier']);
                    }


                    foreach ($connectings as $model => $param_connecting) {
                        $fields = isset($param_connecting['fields']) ? $param_connecting['fields'] : $param_connecting;
                        $this->load->model($param_connecting['path'] . "/$model");
                        $connObj = new $model();
                        if (isset($param_connecting['staticOptions'])) {
                            $strHead_code = "";
                            if (is_array($param_connecting['staticOptions'])) {
                                $strHead_code_array = $param_connecting['staticOptions'];
                                foreach ($strHead_code_array as $field => $cfParams) {
                                    if (isset($cfParams['var_main'])) {
                                        $cNilai = $$cfParams['var_main'];
                                    }
                                    else {
                                        $cNilai = $cfParams['str'];
                                    }
                                    $strHead_code[$field] = $cNilai;
                                }
                            }
                            else {
                                $strHead_code = $param_connecting['staticOptions'];
                            }
                        }
                        else {
                            mati_disini("static optionnya tolong dikasih");
                        }
                        $datas = array();

                        foreach ($fields as $field => $cfParams) {
                            if (isset($cfParams['var_main'])) {
                                $cNilai = $$cfParams['var_main'];
                            }
                            else {
                                $cNilai = $cfParams['str'];
                            }
                            $datas[$field] = $cNilai;
                        }

                        /* -------------------------------------------------
                         * menulis ke table connecting
                         * -------------------------------------------------*/
                        $lastInset_code = $connObj->$param_connecting['fungsi']($strHead_code, $datas);
                        //                        showLast_query("merah");

                        /* -------------------------------------------------
                         * ngupdate ke data utama
                         * -------------------------------------------------*/
                        if (isset($param_connecting['updateMain'])) {
                            foreach ($param_connecting['updateMain']['condites'] as $key => $condite) {
                                $mainCondites[$key] = $$condite;
                            }
                            foreach ($param_connecting['updateMain']['datas'] as $key => $val) {
                                $mainUpdate[$key] = $$val;
                            }
                            $mainObj->updateData($mainCondites, $mainUpdate);
                            //                            showLast_query("orange");
                        }
                        //                        cekHitam($lastInset_code);
                    }
                }
                //    ---------------memasukan pendukung sebagau produk----------------------------------------------
                if ($kval_ != "") {
                    $masterSubs = $mainObj->getMasterSubs();
                    $subFields = array();
                    $getMasterSubs = isset($getMasterSubs) ? $getMasterSubs : array();
                    foreach ($getMasterSubs as $ky => $subRow) {
                        $subFields[$subRow['sub_kategori_id']] = $subRow;
                    }
                    cekBiru($subFields);
                    $fields_ = $mainObj->getFields();
                    if (isset($masterSubs[$kval_]['add_produk'])) {
                        $add_produk = $masterSubs[$kval_]['add_produk'];
                        foreach ($add_produk as $row) {
                            $ssc = explode("_", $row);
                            $sub_cat_id = 4;
                            $sub_cat_nama = isset($subFields[4]) ? $subFields[4]['sub_kategori_nama'] : "";

                            $ref = $fields_[$row]['reference'];
                            $kol = $fields_[$row]['kolom'];
                            $strF = isset($fields_[$row]['strField']) ? $fields_[$row]['strField'] : null;
                            $selectedID = $_POST[$kol];

                            if ($selectedID * 1 > 0) {
                                $this->load->model("Mdls/" . "$ref");
                                $hAdd = new $ref;
                                $hAdd->addFilter("id='" . $selectedID . "'");
                                $dataSrcs = $hAdd->lookupAll()->result();
                                showLast_query("pink");
                                $newData = array(
                                    'kategori_id'       => 3,
                                    'kategori_nama'     => "non unit",
                                    'sub_kategori_id'   => $sub_cat_id,
                                    'sub_kategori_nama' => $sub_cat_nama,
                                    'kode'              => $dataSrcs[0]->sku,
                                    'barcode'           => $dataSrcs[0]->barcode,
                                    'nama'              => $dataSrcs[0]->nama,
                                    'supplier_id'       => $data['supplier_id'],
                                    'kapasitas_id'      => $data['kapasitas_id'],
                                    'folders'           => $data['folders'],
                                    'size_id'           => $data['size_id'],
                                    'size_nama'         => $data['size_nama'],
                                    'tipe_id'           => $data['tipe_id'],
                                    'tipe_nama'         => isset($data['tipe_nama']) ? $data['tipe_nama'] : null,
                                    'merek_id'          => $data['merek_id'],
                                    'merek_nama'        => isset($data['merek_nama']) ? $data['merek_nama'] : null,
                                    'phase_id'          => $data['phase_id'],
                                    'phase_nama'        => isset($data['phase_nama']) ? $data['phase_nama'] : null,
                                    'series_id'         => isset($data['series_id']) ? $data['series_id'] : null,
                                    'series_nama'       => isset($data['series_nama']) ? $data['series_nama'] : null,
                                    'jml_serial'        => 1,
                                    'status'            => 1,
                                    'trash'             => 0,
                                    'jenis'             => "item",
                                );

                                $o->setFilters(array());
                                $tmpD = $o->lookupByCondition(array('kode' => $dataSrcs[0]->sku, 'status' => 1))->result();

                                if (!$tmpD) {
                                    $mainInsertId = $insertID = $o->addData($newData, $o->getTableName()) or die(lgShowError(__LINE__ . " Gagal menulis data", __FILE__));
                                    //                                    /* ---------------------------------------------------------------
                                    //                                     * tested pada auto COA yg pakai aaproval masuk di doApproveFrom
                                    //                                     * --------------------------------------------------------------*/
                                    if (method_exists($mainObj, "getConnectingData")) {
                                        $merek_id = isset($mainDatas['merek_id']) ? $mainDatas['merek_id'] : null;
                                        $merek_nama = isset($mainDatas['merek_nama']) ? $mainDatas['merek_nama'] : null;
                                        $supplier_nama = isset($mainDatas['supplier_nama']) ? $mainDatas['supplier_nama'] : null;
                                        $supplier_id = isset($mainDatas['supplier_id']) ? $mainDatas['supplier_id'] : null;
                                        $nama = ucwords($mainDatas['nama']);
                                        $negara = isset($data['country']) ? $data['country'] : "";
                                        $extern_tipe = $negara == "ID" ? "lokal" : "non_lokal";
                                        $my_name = my_name();

                                        $connectings = $mainObj->getConnectingData();
                                        foreach ($connectings as $model => $param_connecting) {
                                            $fields = isset($param_connecting['fields']) ? $param_connecting['fields'] : $param_connecting;
                                            $this->load->model($param_connecting['path'] . "/$model");
                                            $connObj = new $model();
                                            if (isset($param_connecting['staticOptions'])) {
                                                $strHead_code = "";
                                                if (is_array($param_connecting['staticOptions'])) {
                                                    $strHead_code_array = $param_connecting['staticOptions'];
                                                    foreach ($strHead_code_array as $field => $cfParams) {
                                                        if (isset($cfParams['var_main'])) {
                                                            $cNilai = $$cfParams['var_main'];
                                                        }
                                                        else {
                                                            $cNilai = $cfParams['str'];
                                                        }
                                                        $strHead_code[$field] = $cNilai;
                                                    }
                                                }
                                                else {
                                                    $strHead_code = $param_connecting['staticOptions'];
                                                }
                                            }
                                            else {
                                                mati_disini("static optionnya tolong dikasih");
                                            }
                                            $datas = array();

                                            foreach ($fields as $field => $cfParams) {
                                                if (isset($cfParams['var_main'])) {
                                                    $cNilai = $$cfParams['var_main'];
                                                }
                                                else {
                                                    $cNilai = $cfParams['str'];
                                                }
                                                $datas[$field] = $cNilai;
                                            }

                                            /* -------------------------------------------------
                                             * menulis ke table connecting
                                             * -------------------------------------------------*/
                                            $lastInset_code = $connObj->$param_connecting['fungsi']($strHead_code, $datas);
                                            /* -------------------------------------------------
                                             * ngupdate ke data utama
                                             * -------------------------------------------------*/
                                            if (isset($param_connecting['updateMain'])) {
                                                foreach ($param_connecting['updateMain']['condites'] as $key => $condite) {
                                                    $mainCondites[$key] = $$condite;
                                                }
                                                foreach ($param_connecting['updateMain']['datas'] as $key => $val) {
                                                    $mainUpdate[$key] = $$val;
                                                }
                                                $mainObj->updateData($mainCondites, $mainUpdate);
                                                showLast_query("orange");
                                            }
                                            //                                            cekHitam($lastInset_code);
                                        }
                                    }
                                    //                                    //    -------------------------------------------------------------
                                }
                            }

                            //                            if (method_exists($o, "paramSyncNamaNama")) {
                            //                                $syncNamaNamaMdls = method_exists($o, "paramSyncNamaNama") ? $o->paramSyncNamaNama() : mati_disini("paramSyncNamaNama belum terdifine");
                            //                                foreach ($syncNamaNamaMdls as $syncNamaNamaMdl => $syncNamaNamaParams) {
                            //                                    $id_ygdisync = isset($data[$syncNamaNamaParams['id']]) ? $data[$syncNamaNamaParams['id']] : "";
                            //                                    $o->setTokoId(my_toko_id());
                            //                                    if ($id_ygdisync > 0) {
                            //                                        $o->syncNamaNama($id_ygdisync);
                            //                                    }
                            //                                }
                            //                            }

                        }
                    }
                }
                else {
                    cekHitam(__LINE__ . " tidak membuat subProduk");
                }
            }

            /** -------------------------------------------------------------
             * create default hak akses sesuai group yg dipilih
             * -------------------------------------------------------------*/
            switch ($className) {
                case "MdlEmployee":
                    $this->load->model("Mdls/MdlMenu");
                    $sm = new MdlMenu();
                    // $sm->createDefault($mainInsertId, $data);
                    break;
            }


            // matiHere("data berhasil disimpan. belum comit under maintenance @" . __LINE__);
            //            $this->db->trans_complete();
            $this->db->trans_complete() or mati_disini("Data baru gagal disimpan. Silahkan segera hubungi admin. " . __LINE__);
            if (isset($_GET['main'])) {
                /*-------kembali ke main dialog------*/
                // cekHere(__LINE__);
                $reference = substr($_GET['main'], 3);
                if ($reference != "Produk") {
                    $link_add = base_url() . "statik/Data/add/$reference";
                }
                else {
                    $kval = $_GET['kval'];
                    // $link_add = base_url() . "statik/Data/addProduk/$reference?kval=$kval&pfid=l2";
                    $link_add = base_url() . "statik/Data/add/$reference?kval=$kval&pfid=l2";
                }
                //untuk auto pilih sat add
                $id_target = "none";
                switch ($className) {
                    case "MdlTipe":
                        $id_target = "tipe_id";
                        break;
                    case "MdlModelOutdoor":
                        $id_target = "outdoor_id";
                        break;
                    case "MdlModelIndoor_1":
                        $id_target = "indoor_id_1";
                        break;
                    case "MdlProdukPart_1":
                        $id_target = "produk_part_id_1";
                        break;
                    case "MdlProdukPart_2":
                        $id_target = "produk_part_id_2";
                        break;
                    default:

                        break;
                }

                if ($id_target == "none") {
                    $actionTarget = "
                        top.BootstrapDialog.closeAll();
                        top.BootstrapDialog.show({
                            title:'id_target: $id_target ($className) New $reference',
                            message: " . 'top.$' . "('<div id=l2></div>').load(\"$link_add\"),
                            size:top.BootstrapDialog.SIZE_WIDE,
                            draggable:true,
                            animate:false,
                            closable:true,
                            type:top.BootstrapDialog.TYPE_SUCCESS,
                        });";

                    echo "<script>$actionTarget</script>";
                    die();
                }
                else {
                    $this->load->model("Mdls/" . $className);
                    $d = new $className;
                    $label = $d->getFields();
                    $tmpD = $d->lookupAll()->result();
                    $arrOption = "";
                    $arrOption .= "<option value=''>Pilih " . $label[$id_target]['label'] . "</option>";
                    if (!empty($tmpD)) {
                        foreach ($tmpD as $row) {
                            $id = $row->id;
                            $nama = $row->nama;
                            $selected = $mainInsertId == $id ? "selected" : "";
                            $arrOption .= "<option $selected value='$id'>$nama</option>";
                        }
                    }
                    echo "
                        <script>
                            top.$('select[name=$id_target]').html(\"$arrOption\");
                            top.$('select[name=$id_target]').selectpicker('refresh');
                            var close = top.$('button.close').length;
                            top.$(top.$('button.close')[close-1]).trigger('click')
                        </script>
                    ";
                }
            }
            else {
                if (isset($_GET['paket'])) {
                    $actionTarget = "
                        top.BootstrapDialog.closeAll();
                        top.BootstrapDialog.show({
                            title:'New Add Produk Paket',
                            cssClass: 'edit-dialog',
                            message: top.$('<div id=l1></div>').load('" . base_url() . "statik/Data/edit/Produk_Paket_Project/$mainInsertId?1=1&pfid=l1'),
                            draggable:false,
                            closable:true,
                            onhidden: function(dialogRef){

                            }
                        });";
                    echo "<script>$actionTarget</script>";
                    die();
                }
                else {
                    if ($ctrlName == "ProdukRakitanBiayaPaket") {
                        $prodID = isset($_POST['produk_id']) ? $_POST['produk_id'] : "";
                        echo "
                <script>
                    top.swal({
                        title: 'BIAYA DISIMPAN',
                        type: 'success',
                        html: 'APAKAH ANDA INGIN MENAMBAHKAN BIAYA LAIN NYA...??',
                        showCancelButton: true,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: 'Tambah Lagi',
                        cancelButtonText: 'Tidak!'
                    })
                    .then(
                        function () {
                            top.BootstrapDialog.closeAll();
                            top.BootstrapDialog.show({
                                title:'New Produk Rakitan Biaya Paket',
                                message: top.$('<div></div>').load('" . base_url() . "statik/Data/add/ProdukRakitanBiayaPaket?sID=$prodID'),
                                size: top.BootstrapDialog.SIZE_WIDE,
                                draggable:false,
                                closable:true,
                            });
                            console.log('confirm') },

                        function () {
                            console.log('cancel')
                            top.location.reload();
                        }
                    );
                </script>
            ";
                    }
                    else {
                        if ($ctrlName == "DtaBiayaProject") {
                            $prodID = isset($_POST['produk_id']) ? $_POST['produk_id'] : "";
                            $frame = isset($_GET['frame']) ? $_GET['frame'] : "";
                            $backlink = isset($_GET['backlink']) ? $_GET['backlink'] : "";
                            echo "
                <script>
                    top.swal({
                        title: 'BIAYA DISIMPAN',
                        type: 'success',
                        html: 'APAKAH ANDA INGIN MENAMBAHKAN BIAYA LAIN NYA...??',
                        showCancelButton: true,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: 'Tambah Lagi',
                        cancelButtonText: 'Tidak!'
                    })
                    .then(
                        function () {
                            //top.BootstrapDialog.closeAll();
                            top.$('button.close')[1].click();
                            top.BootstrapDialog.show({
                                title:'Tambah Detail Biaya',
                                message: top.$('<div></div>').load('" . base_url() . "statik/Data/add/DtaBiayaProject?frame=$frame&backlink=$backlink&sID=$prodID'),
                                size: top.BootstrapDialog.SIZE_WIDE,
                                draggable:false,
                                closable:true,
                            });
                            console.log('confirm');
//                            var urlBack = top.$('iframe#result2').attr('src');
//                            top.$('iframe#result2').attr('src', urlBack);
                        },

                        function () {
                            console.log('cancel')
                            //top.location.reload();
                            top.$('button.close')[1].click();
                            var urlBack = top.$('iframe#result2').attr('src');
                            top.$('iframe#result2').attr('src', urlBack)
                        }
                    );
                </script>
            ";
                        }
                        else {
                            echo "<script>top.location.reload();</script>";
                        }
                    }
                }
            }
        }
        else {
            cekMerah("tidak valid @" . __LINE__);
            $errMsg = "";
            $errMsg .= "Ada kesalahan pengisian kolom<br> " . __LINE__;
            foreach ($f->getValidationResults() as $err) {
                $errMsg .= " <strong>$err[fieldLabel]</strong>:  $err[errMsg]<br>";
            }
            echo "<script>top.document.getElementById('btnSave').disabled=false;</script>";
            die(lgShowAlert($errMsg));
        }
    }

    public function editProcess()
    {
        $post = $this->input->post();
        // arrPrintHijau(url_segment());
        arrPrint($post);
        // matiHere(__LINE__);

        $content = "";
        //==menyimpan inputan perubahan data ke dalam datamodel, lalu dari datamodel ke database (dilakukan oleh CI)
        $className = "Mdl" . $this->uri->segment(4);
        $ctrlName = $this->uri->segment(4);
        // $pId = $this->uri->segment(5);
        $this->load->model("Mdls/" . $className);
        $o = new $className;
        $pairValidate = $o->getPairValidate();
        $this->db->trans_start();

        $postProcs = isset($this->config->item("dataPostProcessors")[$className]) ? $this->config->item("dataPostProcessors")[$className] : array();
        $indexFieldName = "id";
        $f = new MyForm($o, "editProcess");
        //        matiHere('$f->isInputValid(): ' . $f->isInputValid());
        //         arrPrint($this->input->post());
        //         arrPrint($pairValidate);
        if ($f->isInputValid()) { //==jika validasi lengkap
            if (sizeof($o->getUnionPairs()) > 0) {
                if ($f->isUnionValid()) {
                    //lolos
                }
                else {
                    $errMsg = "";
                    foreach ($f->getValidationResults() as $err) {
                        $errMsg .= "Error in <strong>$err[fieldLabel]</strong>:  $err[errMsg]<br>";
                    }
                    echo "<script>top.document.getElementById('btnSave').disabled=false;</script>";
                    die(lgShowAlert($errMsg));
                }
            }
            $data2 = array();
            $multi_param = array();

            foreach ($o->getFields() as $fieldName => $spec) {
                // arrPrintPink($spec);
                $fName = isset($spec['kolom']) ? $spec['kolom'] : $fieldName;
                $fmulti_rel = isset($spec['multi_rel']) ? $spec['multi_rel'] : false;
                if ($fmulti_rel === true) {
                    $fmulti_mdl = isset($spec['multi_mdl']) ? $spec['multi_mdl'] : "";
                    $fmulti_parent = isset($spec['multi_parent']) ? $spec['multi_parent'] : "";
                    $this->load->model("Mdls/$fmulti_mdl");
                    $fMulti_data = $post[$fName];
                    $condites = array(
                        $fmulti_parent => $post["id"],
                        // $fName => $post[$fName],
                    );
                    $multi_srcs = $this->$fmulti_mdl->lookupByCondition($condites)->result();
                    showLast_query("kuning");
                    arrPrint($multi_srcs);

                    if (count($multi_srcs) > 0) {
                        $data_tambahan = array(
                            "trash_id"   => my_id(),
                            "trash_nama" => my_name(),
                        );
                        $this->$fmulti_mdl->removeMember($post["id"], $data_tambahan);
                        showLast_query("biru");
                    }

                    foreach ($fMulti_data as $fMulti_datum) {

                        $multi_newdatas = array(
                            $fmulti_parent => $post["id"],
                            "cabang_id"    => $fMulti_datum,
                            "add_id"       => my_id(),
                            "add_nama"     => my_name(),
                            "dtime"        => dtimeNow(),
                        );

                        $this->$fmulti_mdl->addMember($multi_newdatas);
                        showLast_query("lime");
                    }

                    arrPrint($fMulti_data);

                    $multi_param[$fieldName] = $spec;
                }

                // cekHere($spec['inputType']);
                if (isset($spec['inputType'])) {

                    switch ($spec['inputType']) {
                        case "checkbox":
                            // $data[$fName] = base64_encode(serialize($this->input->post($fName)));
                            // if (isset($this->input->post()["$fName"])) {
                            // $data[$fName] = count($this->input->post($fName));
                            $data2[$fName] = ($this->input->post($fName));
                            // }
                            break;
                        case "qtyFillBox":
                            $data[$fName] = base64_encode(serialize($this->input->post($fName)));
                            break;
                        case "texts":
                            //$data[$fName] = date("Y-m-d H:i:s");

                            if (isset($spec['dataParams'])) {
                                $tmp = array();
                                foreach ($spec['dataParams'] as $param) {
                                    $tmp[$param] = $this->input->post($fName . "_" . $param);
                                }
                                $data[$fName] = base64_encode(serialize($tmp));
                            }
                            break;
                        case "address":
                            if (isset($spec['arrayVar'])) {
                                foreach ($spec['arrayVar'] as $fieldName_ => $spec_) {
                                    $fName_ = isset($spec_['kolom']) ? $spec_['kolom'] : $fieldName_;
                                    $data[$fName_] = heTrimAvoidedChars($this->input->post($fName_));
                                }
                            }
                            break;
                        case "password":
                            //                            $data[$fName] = md5($this->input->post($fName));
                            $data[$fName] = strlen($this->input->post($fName)) > 24 ? $this->input->post($fName) : md5($this->input->post($fName));
                            break;
                        case "file":

                            if ($_FILES[$fName]['size'] > 0) {
                                $request = curl_init(cdn_upload_images());
                                $realpath = realpath($_FILES[$fName]['tmp_name']);
                                curl_setopt($request, CURLOPT_POST, true);
                                $fields = [
                                    //                                    'file' => new \CurlFile($realpath, $_FILES[$fName]['type'], $_FILES[$fName]['name']),
                                    'file'          => "@" . $realpath . ";filename=" . $_FILES[$fName]['name'] . ";type=" . $_FILES[$fName]['type'],
                                    'server_source' => $_SERVER['HTTP_HOST'],
                                ];
                                curl_setopt($request, CURLOPT_POSTFIELDS, $fields);
                                curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
                                $cUrl_result = json_decode(curl_exec($request));
                                curl_close($request);
                                if (isset($cUrl_result->status) && $cUrl_result->status == 'success') {
                                    $data[$fName] = $cUrl_result->full_url;


                                    arrPrint($data);
                                    die();

                                }
                                else {
                                    echo "<script>top.swal('error', 'image tidak valid, coba untuk ganti gambar yang akan di upload', 'error');</script>";
                                    die();
                                }
                            }
                            else {
                                if ($this->input->post($fName)) {
                                    //                                    $image["image"] = base64_decode($this->input->post($fName));
                                    //                                    $newFile = blobEncode($image);
                                    $newFile = $this->input->post($fName);
                                }
                                else {
                                    $newFile = "";
                                }
                                $data[$fName] = $newFile;
                            }

                            break;
                        case "image":
                            if ($_FILES[$fName]['size'] > 0) {

                                $request = curl_init(cdn_upload_images());
                                $realpath = realpath($_FILES[$fName]['tmp_name']);
                                curl_setopt($request, CURLOPT_POST, true);
                                $fields = [
                                    'file'          => new \CurlFile($realpath, $_FILES[$fName]['type'], $_FILES[$fName]['name']),
                                    'server_source' => $_SERVER['HTTP_HOST'],
                                ];
                                curl_setopt($request, CURLOPT_POSTFIELDS, $fields);
                                curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
                                $cUrl_result = json_decode(curl_exec($request));

                                curl_close($request);

                                if (isset($cUrl_result->status) && $cUrl_result->status == 'success') {
                                    $data[$fName] = $cUrl_result->full_url;
                                }
                                else {
                                    echo "<script>top.Swal.fire('error', 'image tidak valid, coba untuk ganti gambar yang akan di upload', 'error');</script>";
                                    die();
                                }

                            }
                            else {
                                //                                cekHEre("$fName no image");
                                $data[$fName] = $this->input->post("tmp_" . $fName) != "" ? $this->input->post("tmp_" . $fName) : "";
                            }
                            break;
                        case "hidden":
                            //                            $data[$fName] = $this->input->post($fName);
                            if (isset($this->input->post()["$fName"])) {
                                $data[$fName] = $this->input->post($fName);
                            }

                            //                        }
                            // $data[$fName] = isset($_POST[$fName]) ? $this->input->post($fName) : $pId;
                            break;
                        default:
                            if (isset($this->input->post()["$fName"])) {
                                $data[$fName] = heTrimAvoidedChars($this->input->post($fName));
                            }
                            //                            $data[$fName] = heTrimAvoidedChars($this->input->post($fName));
                            break;
                    }
                }
                else {
                    switch ($spec['type']) {
                        case "varchar":
                            //                            $data[$fName] = $this->input->post($fName);
                            if (isset($this->input->post()["$fName"])) {
                                $data[$fName] = $this->input->post($fName);
                            }
                            break;
                        case "int":
                            //                            $data[$fName] = $this->input->post($fName);
                            if (isset($this->input->post()["$fName"])) {
                                $data[$fName] = $this->input->post($fName);
                            }
                            break;
                        case "date":
                            $data[$fName] = date("Y-m-d");

                            break;
                        case "datetime":
                            $data[$fName] = date("Y-m-d H:i:s");
                            break;
                        case "timestamp":
                            $data[$fName] = date("Y-m-d H:i:s");
                            break;
                        default:
                            //                            $data[$fName] = $this->input->post($fName);
                            if (isset($this->input->post()["$fName"])) {
                                $data[$fName] = $this->input->post($fName);
                            }
                            break;
                    }
                }
            }
            // arrPrintHijau($data);
            $where = array(
                "id" => $data['id'],
            );

            $this->load->model("Mdls/" . "MdlDataTmp");
            $dTmp = new MdlDataTmp();
            if ($this->updaterUsingApproval) {
                $data['trash'] = 0;
            }
            if (sizeof($o->getAutoFillFields()) > 0) {
                foreach ($o->getAutoFillFields() as $mainCol => $autoFieldsCal) {
                    $data[$mainCol] = makeValue($autoFieldsCal, $this->input->post(), $this->input->post(), 0);
                }
            }


            if (method_exists($o, "getListedUnsetFields")) {
                if (sizeof($o->getListedUnsetFields())) {
                    foreach ($o->getListedUnsetFields() as $val) {
                        if (array_key_exists($val, $this->input->post())) {
                            //                            cekHere("meng NULL kan -> $val");
                            $data[$val] = NULL;
                        }
                    }
                }
            }

            // arrprint($data);
            $tmpData = array(
                "orig_id"          => $data['id'],
                "mdl_name"         => $className,
                "mdl_label"        => $ctrlName,
                "proposed_by"      => $this->session->login['id'],
                "proposed_by_name" => $this->session->login['nama'],
                "proposed_date"    => date("Y-m-d H:i:s"),
                "content"          => blobEncode($data),
            );

            if ($this->updaterUsingApproval) {
                matiHere(__LINE__);
                $insertID = $dTmp->addData($tmpData, $dTmp->getTableName()) or die(lgShowError("Gagal menulis pengajuan data", __FILE__));
                $this->session->errMsg = "Data proposal has been saved and pending approval";
                $tmpOrig = $o->lookupByCondition(array(
                    "id" => $data['id'],
                ))->result();
                $o->setFilters(array());
                $o->updateData($where, array("status" => 0, "trash" => 1), $o->getTableName());
                $this->load->model("Mdls/" . "MdlDataHistory");
                //                arrPrint($data);
                //                die();
                $hTmp = new MdlDataHistory();
                $tmpHData = array(
                    "orig_id"            => $data['id'],
                    "mdl_name"           => $className,
                    "mdl_label"          => get_class($this),
                    "old_content"        => base64_encode(serialize((array)$tmpOrig)),
                    "old_content_intext" => print_r($tmpOrig, true),
                    "new_content"        => base64_encode(serialize($data)),
                    "new_content_intext" => print_r($data, true),
                    "label"              => "proposed",
                    "oleh_id"            => $this->session->login['id'],
                    "oleh_name"          => $this->session->login['nama'],
                );
                $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            }
            else {
                $tmpOrig = $o->lookupByCondition(array(
                    "id" => $data['id'],
                ))->result();
                $o->setFilters(array());
                $employeeId = $insertID = $data["id"];
                $o->updateData($where, $data, $o->getTableName());
                cekOrange($this->db->last_query() . " @" . __LINE__);
                // matiHere(__LINE__);
                $this->session->errMsg = "Data has been updated";
                //arrPrint($this->config->item("dataExtended"));
                // if (isset($this->config->item("dataExtended")[$className])) {
                // createAccessData($this->input->post('membership'), $data['id'], "false");
                // }

                if (method_exists($o, "paramSyncNamaNama")) {
                    $syncNamaNamaMdls = method_exists($o, "paramSyncNamaNama") ? $o->paramSyncNamaNama() : mati_disini("paramSyncNamaNama belum terdifine");
                    foreach ($syncNamaNamaMdls as $syncNamaNamaMdl => $syncNamaNamaParams) {
                        $id_ygdisync = isset($data[$syncNamaNamaParams['id']]) ? $data[$syncNamaNamaParams['id']] : "";
                        //$o->setTokoId(my_toko_id());
                        if ($id_ygdisync > 0) {
                            $o->syncNamaNama($id_ygdisync);
                        }
                    }
                    // matiHere(__LINE__);

                    // $o->syncNamaNama();
                }

                /** -----------------------------------------------------------------------------
                 * tarikan ke table multy
                 * -----------------------------------------------------------------------------*/
                arrPrint($data2);
                if (count($multi_param) > 0 && isset($post['id'])) {
                    $employeeId = $post['id'];

                    // =====================================================================
                    // ▼▼▼ LANGKAH 1: SAPU BERSIH (SOFT DELETE SEMUA HAK AKSES LAMA) ▼▼▼
                    // =====================================================================

                    // Nonaktifkan semua keanggotaan cabang
                    $this->load->model("Mdls/MdlEmployeeMembershipCabang");
                    $mem = new MdlEmployeeMembershipCabang();
                    $mem->deleteData(['employee_id' => $employeeId]);

                    // Nonaktifkan semua hak akses spesifik (cth: Penjualan, Logistik)
                    $this->load->model("Mdls/MdlEmployeeHakAkses");
                    $ha = new MdlEmployeeHakAkses();
                    $ha->deleteData(['employee_id' => $employeeId]);

                    // Nonaktifkan semua hak akses menu terkait
                    $this->load->model("Mdls/MdlMenu");
                    $sm = new MdlMenu();
                    $sm->deleteData(['employee_id' => $employeeId]);

                    // ===================================================================
                    // ▼▼▼ LANGKAH 2: BANGUN ULANG (INSERT KEMBALI SESUAI FORM) ▼▼▼
                    // ===================================================================

                    foreach ($multi_param as $fieldKey => $multiSpeks) {
                        // Hanya proses jika data dari form ada untuk field ini
                        if (isset($data2[$fieldKey]) && is_array($data2[$fieldKey])) {
                            $mdl2 = $multiSpeks['multi_mdl'];
                            $mkolom2 = $multiSpeks['kolom'];
                            $mparent = $multiSpeks['multi_parent'];

                            $this->load->model("Mdls/$mdl2");
                            $md2 = new $mdl2();
                            $multiDatas = $data2[$fieldKey];

                            foreach ($multiDatas as $multiData) {
                                // Sisipkan kembali keanggotaan cabang yang aktif
                                $mData = [
                                    $mparent => $employeeId,
                                    $mkolom2 => $multiData,
                                    'trash'  => 0 // Pastikan record baru aktif
                                ];
                                $md2->addData($mData, $md2->getTableName());

                                // Logika spesifik untuk memproses dan menyisipkan hak akses di dalam cabang
                                if ($mdl2 == "MdlEmployeeMembershipCabang") {
                                    $cabang_id = $multiData;
                                    $cabang_akses = isset($_POST['cabang_akses'][$cabang_id]) ? $_POST['cabang_akses'][$cabang_id] : [];

                                    if (is_array($cabang_akses)) {
                                        foreach ($cabang_akses as $ha_jenis => $cabang_aks) {
                                            foreach ($cabang_aks as $ha_step => $cabang_ak) {
                                                // Sisipkan kembali hak akses spesifik dan menu yang aktif
                                                $ha->saveEmployeeAccess($employeeId, $cabang_id, $ha_jenis, $ha_step, $cabang_ak);
                                                $sm->saveMenuAccess($employeeId, $ha_jenis, 1, $ha_step, my_id(), $cabang_id);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }


                //                arrPrint($postProcs);
                if (sizeof($postProcs) > 0) {
                    cekmerah("ada post-processors " . __FILE__ . " " . __LINE__);
                    foreach ($postProcs as $pp) {
                        $comName = "DCom" . $pp;
                        //                        cekmerah("post-proc name: $pp / $comName");
                        $this->load->model("DComs/" . $comName);

                        $o2 = new $comName();
                        $o2->pair($data) or die(lgShowError($comName, "failed to pair the params of DCom"));
                        $o2->exec() or die(lgShowError($comName, "failed to execute DCom"));
                    }
                }
                $this->load->model("Mdls/" . "MdlDataHistory");
                $hTmp = new MdlDataHistory();
                $tmpHData = array(
                    "orig_id"            => $data['id'],
                    "mdl_name"           => $className,
                    "mdl_label"          => get_class($this),
                    "old_content"        => base64_encode(serialize((array)$tmpOrig)),
                    "old_content_intext" => print_r($tmpOrig, true),
                    "new_content"        => base64_encode(serialize($data)),
                    "new_content_intext" => print_r($data, true),
                    "label"              => "applied",
                    "oleh_id"            => $this->session->login['id'],
                    "oleh_name"          => $this->session->login['nama'],
                );
                $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));


            }

            if (sizeof($pairValidate) > 0) {
                // arrPrint($pairValidate);
                $valPair = array();
                $o->setFilters(array());
                foreach ($pairValidate as $k) {
                    cekHitam($k);
                    // $valPair[$k] = $this->input->post($k);
                    $o->addFilter("$k='" . $this->input->post($k) . "'");
                    $o->addFilter("status='.1'");
                }
                $pairValidateData = $o->lookUpAll()->result();

                if (sizeof($pairValidateData) > 1) {
                    $alerts = array(
                        "type"  => "warning",
                        "title" => "Duplikasi data***",
                        "html"  => "<b class='text-uppercase'>" . $pairValidateData['0']->nama . "</b> sudah ada disistem. silahkan pilih nama lain",
                    );

                    echo swalAlert($alerts);
                    die();
                }

            }

            // if(($ipadd = ipadd()) == "202.65.117.72"){
            // }

            /* --------------------------------------------------------------------------------
             * api ke aplikasi POS untuk merubah data employee
             * --------------------------------------------------------------------------------*/
            //            $apistatus = "success";
            //            if ($className == "MdlEmployeeCabang") {
            //                $em_id = $data["id"];
            //                $this->load->config("heApi");
            //                $apiWebs = $this->config->item("heApi");
            //                $urlApi = $apiWebs["webs"]["edit_employee"] . "/$em_id";
            ////                cekHere("$urlApi");
            //
            //                $this->load->library('curl');
            //                $curl = New Curl();
            //
            ////                arrPrintPink($data);
            //                // $data["id"] = $mainInsertId;
            //
            ////                arrPrintKuning($data);
            //                // $data
            ////                $preOrderRequest = $curl->sendPost($urlApi, $data);
            //                $preOrderRequest = $this->curl->simple_post($urlApi, $data);
            ////                matiHere( json_encode($preOrderRequest) );
            ////                arrPrintHijau($preOrderRequest);
            //                $apirespons = json_decode($preOrderRequest);
            //                // arrPrintHijau($apirespons);
            //                $apistatus = $apirespons->status;
            //                // matiHere("belum complittt $ipadd $className $apistatus @" . __LINE__ );
            //            }

            // matiHere("mode monitoring belum complittt $className @" . __LINE__);
            //            if ($apistatus == "success") {
            //             matiHere("sudah complittt $className @" . __LINE__);
            $this->db->trans_complete();

            // matiHere("sudah complittt $className @" . __LINE__);
            //            $file = fopen(__DIR__ . '/eusvc/sync/' . $className . "_" . $this->session->login['toko_id'] . ".txt", "w");
            //            echo fwrite($file, json_encode(array("datetime" => date("Y-m-d H:i:s"))));
            //            fclose($file);

            $file = fopen(__DIR__ . '/eusvc/sync/' . $className . "_" . $this->session->login['toko_id'] . ".txt", "w");
            echo fwrite($file, json_encode(array("datetime" => date("Y-m-d H:i:s"))));
            fclose($file);

            //
            //            if ($className == "MdlEmployeeCabang") {
            //                $this->load->library("Curl");
            //                $curl = New Curl();
            //                $url = ADM_DOMAIN . "eusvc/Entries/syncEmployeeToApk";
            //                $toko_id = $this->session->login['toko_id'];
            //                $this->load->model("Mdls/MdlEmployeeCabang");
            //                $pr = new MdlEmployeeCabang();
            //                $pr->addFilter("toko_id=$toko_id");
            //                $prdTmp = $pr->lookupAll()->result();
            //                $arrEmployee = array();
            //                foreach ($prdTmp as $k => $dEmployee) {
            //                    $arrEmployee[$dEmployee->id] = $dEmployee;
            //                }
            //                $writer = $curl->syncEmployeeToApk($url, $arrEmployee);
            //            }

            echo "<script>top.location.reload();</script>";
            //            }
            //            else {
            //                echo lgShowWarning("Opss..." . __LINE__ , "gagal menyimpan data, cobalah beberapa saat lagi, atau hubungi webadmin");
            //            }
        }
        else {
            $errMsg = "";
            foreach ($f->getValidationResults() as $err) {
                $errMsg .= "Error in $err[fieldLabel]:  $err[errMsg]";
            }
            echo "<script>top.document.getElementById('btnSave').disabled=false;</script>";
            die(lgShowAlert($errMsg));
        }
    }

    public function editProcess_old()
    {
        $post = $this->input->post();
        // arrPrintHijau(url_segment());
        // arrPrint($post);
        // matiHere(__LINE__);
        $content = "";
        //==menyimpan inputan perubahan data ke dalam datamodel, lalu dari datamodel ke database (dilakukan oleh CI)
        $className = "Mdl" . $this->uri->segment(4);
        $ctrlName = $this->uri->segment(4);
        // $pId = $this->uri->segment(5);
        $this->load->model("Mdls/" . $className);
        $o = new $className;
        $pairValidate = $o->getPairValidate();
        $this->db->trans_start();

        $postProcs = isset($this->config->item("dataPostProcessors")[$className]) ? $this->config->item("dataPostProcessors")[$className] : array();
        $indexFieldName = "id";
        $f = new MyForm($o, "editProcess");
        //        matiHere('$f->isInputValid(): ' . $f->isInputValid());
        //         arrPrint($this->input->post());
        //         arrPrint($pairValidate);
        if ($f->isInputValid()) { //==jika validasi lengkap
            if (sizeof($o->getUnionPairs()) > 0) {
                if ($f->isUnionValid()) {
                    //lolos
                }
                else {
                    $errMsg = "";
                    foreach ($f->getValidationResults() as $err) {
                        $errMsg .= "Error in <strong>$err[fieldLabel]</strong>:  $err[errMsg]<br>";
                    }
                    echo "<script>top.document.getElementById('btnSave').disabled=false;</script>";
                    die(lgShowAlert($errMsg));
                }
            }
            $data2 = array();
            $multi_param = array();

            foreach ($o->getFields() as $fieldName => $spec) {
                // arrPrintPink($spec);
                $fName = isset($spec['kolom']) ? $spec['kolom'] : $fieldName;
                $fmulti_rel = isset($spec['multi_rel']) ? $spec['multi_rel'] : false;
                if ($fmulti_rel === true) {
                    $fmulti_mdl = isset($spec['multi_mdl']) ? $spec['multi_mdl'] : "";
                    $fmulti_parent = isset($spec['multi_parent']) ? $spec['multi_parent'] : "";
                    $this->load->model("Mdls/$fmulti_mdl");
                    $fMulti_data = $post[$fName];
                    $condites = array(
                        $fmulti_parent => $post["id"],
                        // $fName => $post[$fName],
                    );
                    $multi_srcs = $this->$fmulti_mdl->lookupByCondition($condites)->result();
                    showLast_query("kuning");
                    arrPrint($multi_srcs);

                    if (count($multi_srcs) > 0) {
                        $data_tambahan = array(
                            "trash_id"   => my_id(),
                            "trash_nama" => my_name(),
                        );
                        $this->$fmulti_mdl->removeMember($post["id"], $data_tambahan);
                        showLast_query("biru");
                    }

                    foreach ($fMulti_data as $fMulti_datum) {

                        $multi_newdatas = array(
                            $fmulti_parent => $post["id"],
                            "cabang_id"    => $fMulti_datum,
                            "add_id"       => my_id(),
                            "add_nama"     => my_name(),
                            "dtime"        => dtimeNow(),
                        );

                        $this->$fmulti_mdl->addMember($multi_newdatas);
                        showLast_query("lime");
                    }

                    arrPrint($fMulti_data);

                    $multi_param[$fieldName] = $spec;
                }


                if (isset($spec['inputType'])) {
                    switch ($spec['inputType']) {
                        case "checkbox":
                            // $data[$fName] = base64_encode(serialize($this->input->post($fName)));
                            // if (isset($this->input->post()["$fName"])) {
                            // $data[$fName] = count($this->input->post($fName));
                            $data2[$fName] = ($this->input->post($fName));
                            // }
                            break;
                        case "qtyFillBox":
                            $data[$fName] = base64_encode(serialize($this->input->post($fName)));
                            break;
                        case "texts":
                            //$data[$fName] = date("Y-m-d H:i:s");

                            if (isset($spec['dataParams'])) {
                                $tmp = array();
                                foreach ($spec['dataParams'] as $param) {
                                    $tmp[$param] = $this->input->post($fName . "_" . $param);
                                }
                                $data[$fName] = base64_encode(serialize($tmp));
                            }
                            break;
                        case "address":
                            if (isset($spec['arrayVar'])) {
                                foreach ($spec['arrayVar'] as $fieldName_ => $spec_) {
                                    $fName_ = isset($spec_['kolom']) ? $spec_['kolom'] : $fieldName_;
                                    $data[$fName_] = heTrimAvoidedChars($this->input->post($fName_));
                                }
                            }
                            break;
                        case "password":
                            //                            $data[$fName] = md5($this->input->post($fName));
                            $data[$fName] = strlen($this->input->post($fName)) > 24 ? $this->input->post($fName) : md5($this->input->post($fName));
                            break;
                        case "file":

                            if ($_FILES[$fName]['size'] > 0) {
                                $request = curl_init(cdn_upload_images());
                                $realpath = realpath($_FILES[$fName]['tmp_name']);
                                curl_setopt($request, CURLOPT_POST, true);
                                $fields = [
                                    //                                    'file' => new \CurlFile($realpath, $_FILES[$fName]['type'], $_FILES[$fName]['name']),
                                    'file'          => "@" . $realpath . ";filename=" . $_FILES[$fName]['name'] . ";type=" . $_FILES[$fName]['type'],
                                    'server_source' => $_SERVER['HTTP_HOST'],
                                ];
                                curl_setopt($request, CURLOPT_POSTFIELDS, $fields);
                                curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
                                $cUrl_result = json_decode(curl_exec($request));
                                curl_close($request);
                                if (isset($cUrl_result->status) && $cUrl_result->status == 'success') {
                                    $data[$fName] = $cUrl_result->full_url;


                                    arrPrint($data);
                                    die();

                                }
                                else {
                                    echo "<script>top.swal('error', 'image tidak valid, coba untuk ganti gambar yang akan di upload', 'error');</script>";
                                    die();
                                }
                            }
                            else {
                                if ($this->input->post($fName)) {
                                    //                                    $image["image"] = base64_decode($this->input->post($fName));
                                    //                                    $newFile = blobEncode($image);
                                    $newFile = $this->input->post($fName);
                                }
                                else {
                                    $newFile = "";
                                }
                                $data[$fName] = $newFile;
                            }

                            break;
                        case "image":
                            if ($_FILES[$fName]['size'] > 0) {

                                $request = curl_init(cdn_upload_images());
                                $realpath = realpath($_FILES[$fName]['tmp_name']);
                                curl_setopt($request, CURLOPT_POST, true);
                                $fields = [
                                    'file'          => new \CurlFile($realpath, $_FILES[$fName]['type'], $_FILES[$fName]['name']),
                                    'server_source' => $_SERVER['HTTP_HOST'],
                                ];
                                curl_setopt($request, CURLOPT_POSTFIELDS, $fields);
                                curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
                                $cUrl_result = json_decode(curl_exec($request));

                                curl_close($request);

                                if (isset($cUrl_result->status) && $cUrl_result->status == 'success') {
                                    $data[$fName] = $cUrl_result->full_url;
                                }
                                else {
                                    echo "<script>top.Swal.fire('error', 'image tidak valid, coba untuk ganti gambar yang akan di upload', 'error');</script>";
                                    die();
                                }

                            }
                            else {
                                //                                cekHEre("$fName no image");
                                $data[$fName] = $this->input->post("tmp_" . $fName) != "" ? $this->input->post("tmp_" . $fName) : "";
                            }
                            break;
                        case "hidden":
                            //                            $data[$fName] = $this->input->post($fName);
                            if (isset($this->input->post()["$fName"])) {
                                $data[$fName] = $this->input->post($fName);
                            }

                            //                        }
                            // $data[$fName] = isset($_POST[$fName]) ? $this->input->post($fName) : $pId;
                            break;
                        default:
                            if (isset($this->input->post()["$fName"])) {
                                $data[$fName] = heTrimAvoidedChars($this->input->post($fName));
                            }
                            //                            $data[$fName] = heTrimAvoidedChars($this->input->post($fName));
                            break;
                    }
                }
                else {
                    switch ($spec['type']) {
                        case "varchar":
                            //                            $data[$fName] = $this->input->post($fName);
                            if (isset($this->input->post()["$fName"])) {
                                $data[$fName] = $this->input->post($fName);
                            }
                            break;
                        case "int":
                            //                            $data[$fName] = $this->input->post($fName);
                            if (isset($this->input->post()["$fName"])) {
                                $data[$fName] = $this->input->post($fName);
                            }
                            break;
                        case "date":
                            $data[$fName] = date("Y-m-d");

                            break;
                        case "datetime":
                            $data[$fName] = date("Y-m-d H:i:s");
                            break;
                        case "timestamp":
                            $data[$fName] = date("Y-m-d H:i:s");
                            break;
                        default:
                            //                            $data[$fName] = $this->input->post($fName);
                            if (isset($this->input->post()["$fName"])) {
                                $data[$fName] = $this->input->post($fName);
                            }
                            break;
                    }
                }
            }
            // arrPrintHijau($data);
            $where = array(
                "id" => $data['id'],
            );

            $this->load->model("Mdls/" . "MdlDataTmp");
            $dTmp = new MdlDataTmp();
            if ($this->updaterUsingApproval) {
                $data['trash'] = 0;
            }
            if (sizeof($o->getAutoFillFields()) > 0) {
                foreach ($o->getAutoFillFields() as $mainCol => $autoFieldsCal) {
                    $data[$mainCol] = makeValue($autoFieldsCal, $this->input->post(), $this->input->post(), 0);
                }
            }


            if (method_exists($o, "getListedUnsetFields")) {
                if (sizeof($o->getListedUnsetFields())) {
                    foreach ($o->getListedUnsetFields() as $val) {
                        if (array_key_exists($val, $this->input->post())) {
                            //                            cekHere("meng NULL kan -> $val");
                            $data[$val] = NULL;
                        }
                    }
                }
            }

            //            arrprint($data);
            $tmpData = array(
                "orig_id"          => $data['id'],
                "mdl_name"         => $className,
                "mdl_label"        => $ctrlName,
                "proposed_by"      => $this->session->login['id'],
                "proposed_by_name" => $this->session->login['nama'],
                "proposed_date"    => date("Y-m-d H:i:s"),
                "content"          => blobEncode($data),
            );

            if ($this->updaterUsingApproval) {
                $insertID = $dTmp->addData($tmpData, $dTmp->getTableName()) or die(lgShowError("Gagal menulis pengajuan data", __FILE__));
                $this->session->errMsg = "Data proposal has been saved and pending approval";
                $tmpOrig = $o->lookupByCondition(array(
                    "id" => $data['id'],
                ))->result();
                $o->setFilters(array());
                $o->updateData($where, array("status" => 0, "trash" => 1), $o->getTableName());
                $this->load->model("Mdls/" . "MdlDataHistory");
                //                arrPrint($data);
                //                die();
                $hTmp = new MdlDataHistory();
                $tmpHData = array(
                    "orig_id"            => $data['id'],
                    "mdl_name"           => $className,
                    "mdl_label"          => get_class($this),
                    "old_content"        => base64_encode(serialize((array)$tmpOrig)),
                    "old_content_intext" => print_r($tmpOrig, true),
                    "new_content"        => base64_encode(serialize($data)),
                    "new_content_intext" => print_r($data, true),
                    "label"              => "proposed",
                    "oleh_id"            => $this->session->login['id'],
                    "oleh_name"          => $this->session->login['nama'],
                );
                $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            }
            else {
                $tmpOrig = $o->lookupByCondition(array(
                    "id" => $data['id'],
                ))->result();
                $o->setFilters(array());
                $employeeId = $insertID = $data["id"];
                $o->updateData($where, $data, $o->getTableName());
                cekOrange($this->db->last_query() . " @" . __LINE__);
                // matiHere(__LINE__);
                $this->session->errMsg = "Data has been updated";
                //arrPrint($this->config->item("dataExtended"));
                // if (isset($this->config->item("dataExtended")[$className])) {
                // createAccessData($this->input->post('membership'), $data['id'], "false");
                // }

                if (method_exists($o, "paramSyncNamaNama")) {
                    $syncNamaNamaMdls = method_exists($o, "paramSyncNamaNama") ? $o->paramSyncNamaNama() : mati_disini("paramSyncNamaNama belum terdifine");
                    foreach ($syncNamaNamaMdls as $syncNamaNamaMdl => $syncNamaNamaParams) {
                        $id_ygdisync = isset($data[$syncNamaNamaParams['id']]) ? $data[$syncNamaNamaParams['id']] : "";
                        //$o->setTokoId(my_toko_id());
                        if ($id_ygdisync > 0) {
                            $o->syncNamaNama($id_ygdisync);
                        }
                    }
                    // matiHere(__LINE__);

                    // $o->syncNamaNama();
                }

                /** -----------------------------------------------------------------------------
                 * tarikan ke table multy
                 * -----------------------------------------------------------------------------*/
                if (count($data2)) {
                    // cekPink(__LINE__);
                    // arrPrintPink($multi_param);
                    foreach ($multi_param as $fieldKey => $multiSpeks) {
                        $mdl2 = $multiSpeks['multi_mdl'];
                        $mkolom2 = $multiSpeks['kolom'];
                        $mparent = $multiSpeks['multi_parent'];
                        cekHere(__LINE__ . " $mdl2");
                        $this->load->model("Mdls/$mdl2");
                        $md2 = new $mdl2();
                        $multiDatas = $data2[$fieldKey];

                        $mData = array();
                        foreach ($multiDatas as $multiData) {
                            $cabang_id = $multiData;
                            $mData[$mparent] = $insertID;
                            $mData[$mkolom2] = $multiData;
                            $rr = $md2->addData($mData, $md2->getTableName());
                            showLast_query("hijau");

                            /* --------------------------------------
                             * menyimpan hak akses
                             * --------------------------------------*/
                            if ($mdl2 == "MdlEmployeeMembershipCabang") {
                                // cekBiru("membuat hak akses " . __LINE__);
                                $cabang_akses = $_POST['cabang_akses'][$multiData];
                                // arrPrint($cabang_akses);

                                $this->load->model("Mdls/MdlEmployeeHakAkses");
                                $ha = new MdlEmployeeHakAkses();

                                foreach ($cabang_akses as $ha_jenis => $cabang_aks) {
                                    foreach ($cabang_aks as $ha_step => $cabang_ak) {

                                        // $condites = array(
                                        //     "employee_id" => $insertID,
                                        //     "cabang_id"   => $multiData,
                                        //     "jenis"       => $ha_jenis,
                                        //     "step"        => $ha_step,
                                        // );
                                        // $tmps = $ha->lookupByCondition($condites)->result();
                                        // showLast_query("biru");
                                        // if(count($tmps) > 0){
                                        //     $datas = array(
                                        //         "trash" => 1
                                        //     );
                                        //     $ha->updateData($condites, $datas);
                                        //     showLast_query("merah");
                                        // }
                                        //
                                        // $haDatas = array(
                                        //     "employee_id" => $insertID,
                                        //     "cabang_id"   => $multiData,
                                        //     "jenis"       => $ha_jenis,
                                        //     "step"        => $ha_step,
                                        //     "akses"       => $cabang_ak,
                                        // );
                                        // $sss = $ha->addData($haDatas);
                                        // showLast_query("kuning");

                                        $ha->saveEmployeeAccess($employeeId, $cabang_id, $ha_jenis, $ha_step, $cabang_ak);
                                        // matiHere(__LINE__);
                                        /** ------------------------------------------
                                         * menuakses transakisonal
                                         * ------------------------------------------*/
                                        $this->load->model("Mdls/MdlMenu");
                                        $emp = new MdlMenu();
                                        //
                                        //                                         $condites = array(
                                        //                                             "employee_id"   => $insertID,
                                        //                                             "menu_category" => $ha_jenis,
                                        //                                             "crud_id"       => 1,
                                        //                                             "steps"         => $ha_step,
                                        //                                         );
                                        //
                                        //                                         /** ------------------ ------------------------------- -------------
                                        //                                          * cek keberadaan hak akses yg exis
                                        //                                          * ------------------ ------------------------------- -------------*/
                                        //                                         $tmps = $emp->lookupByCondition($condites)->result();
                                        //                                         showLast_query("hijau");
                                        //                                         // arrPrintHijau($tmps);
                                        // // matiHere(__LINE__);
                                        //                                         /** ------------------ ------------------------------- -------------
                                        //                                          * jika sudah ada akan dihapus, setelahnya baru insert
                                        //                                          * ------------------ ------------------------------- -------------*/
                                        //                                         if (count($tmps)) {
                                        //                                             $datas = array(
                                        //                                                 "trash" => 1
                                        //                                             );
                                        //                                             $emp->updateData($condites, $datas);
                                        //                                         }
                                        //                                         $haDatas = $condites + array(
                                        //                                             // "employee_id"   => $insertID,
                                        //                                             // "menu_category" => $ha_jenis,
                                        //                                             // "crud_id"       => $ha_step,
                                        //                                             // "steps"         => $ha_step,
                                        //                                             "active"        => 1,
                                        //                                             "author"        => my_id(),
                                        //                                         );
                                        //                                         $sss = $emp->addData($haDatas) or matiHere(__LINE__);
                                        //                                         showLast_query("hijau");
                                        //                                         // matiHere(__LINE__);
                                        //                                         matiHere(__LINE__);
                                        $emp->saveMenuAccess($employeeId, $ha_jenis, $crudId = 1, $ha_step, my_id());
                                        // matiHere(__LINE__);
                                    }
                                }
                            }
                        }
                    }
                }


                //                arrPrint($postProcs);
                if (sizeof($postProcs) > 0) {
                    cekmerah("ada post-processors " . __FILE__ . " " . __LINE__);
                    foreach ($postProcs as $pp) {
                        $comName = "DCom" . $pp;
                        //                        cekmerah("post-proc name: $pp / $comName");
                        $this->load->model("DComs/" . $comName);

                        $o2 = new $comName();
                        $o2->pair($data) or die(lgShowError($comName, "failed to pair the params of DCom"));
                        $o2->exec() or die(lgShowError($comName, "failed to execute DCom"));
                    }
                }
                $this->load->model("Mdls/" . "MdlDataHistory");
                $hTmp = new MdlDataHistory();
                $tmpHData = array(
                    "orig_id"            => $data['id'],
                    "mdl_name"           => $className,
                    "mdl_label"          => get_class($this),
                    "old_content"        => base64_encode(serialize((array)$tmpOrig)),
                    "old_content_intext" => print_r($tmpOrig, true),
                    "new_content"        => base64_encode(serialize($data)),
                    "new_content_intext" => print_r($data, true),
                    "label"              => "applied",
                    "oleh_id"            => $this->session->login['id'],
                    "oleh_name"          => $this->session->login['nama'],
                );
                $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));


            }

            if (sizeof($pairValidate) > 0) {
                // arrPrint($pairValidate);
                $valPair = array();
                $o->setFilters(array());
                foreach ($pairValidate as $k) {
                    cekHitam($k);
                    // $valPair[$k] = $this->input->post($k);
                    $o->addFilter("$k='" . $this->input->post($k) . "'");
                    $o->addFilter("status='.1'");
                }
                $pairValidateData = $o->lookUpAll()->result();

                if (sizeof($pairValidateData) > 1) {
                    $alerts = array(
                        "type"  => "warning",
                        "title" => "Duplikasi data***",
                        "html"  => "<b class='text-uppercase'>" . $pairValidateData['0']->nama . "</b> sudah ada disistem. silahkan pilih nama lain",
                    );

                    echo swalAlert($alerts);
                    die();
                }

            }

            // if(($ipadd = ipadd()) == "202.65.117.72"){
            // }

            /* --------------------------------------------------------------------------------
             * api ke aplikasi POS untuk merubah data employee
             * --------------------------------------------------------------------------------*/
            //            $apistatus = "success";
            //            if ($className == "MdlEmployeeCabang") {
            //                $em_id = $data["id"];
            //                $this->load->config("heApi");
            //                $apiWebs = $this->config->item("heApi");
            //                $urlApi = $apiWebs["webs"]["edit_employee"] . "/$em_id";
            ////                cekHere("$urlApi");
            //
            //                $this->load->library('curl');
            //                $curl = New Curl();
            //
            ////                arrPrintPink($data);
            //                // $data["id"] = $mainInsertId;
            //
            ////                arrPrintKuning($data);
            //                // $data
            ////                $preOrderRequest = $curl->sendPost($urlApi, $data);
            //                $preOrderRequest = $this->curl->simple_post($urlApi, $data);
            ////                matiHere( json_encode($preOrderRequest) );
            ////                arrPrintHijau($preOrderRequest);
            //                $apirespons = json_decode($preOrderRequest);
            //                // arrPrintHijau($apirespons);
            //                $apistatus = $apirespons->status;
            //                // matiHere("belum complittt $ipadd $className $apistatus @" . __LINE__ );
            //            }

            // matiHere("mode monitoring belum complittt $className @" . __LINE__);
            //            if ($apistatus == "success") {
            // matiHere("sudah complittt $className @" . __LINE__);
            $this->db->trans_complete();

            // matiHere("sudah complittt $className @" . __LINE__);
            //            $file = fopen(__DIR__ . '/eusvc/sync/' . $className . "_" . $this->session->login['toko_id'] . ".txt", "w");
            //            echo fwrite($file, json_encode(array("datetime" => date("Y-m-d H:i:s"))));
            //            fclose($file);

            $file = fopen(__DIR__ . '/eusvc/sync/' . $className . "_" . $this->session->login['toko_id'] . ".txt", "w");
            echo fwrite($file, json_encode(array("datetime" => date("Y-m-d H:i:s"))));
            fclose($file);

            //
            //            if ($className == "MdlEmployeeCabang") {
            //                $this->load->library("Curl");
            //                $curl = New Curl();
            //                $url = ADM_DOMAIN . "eusvc/Entries/syncEmployeeToApk";
            //                $toko_id = $this->session->login['toko_id'];
            //                $this->load->model("Mdls/MdlEmployeeCabang");
            //                $pr = new MdlEmployeeCabang();
            //                $pr->addFilter("toko_id=$toko_id");
            //                $prdTmp = $pr->lookupAll()->result();
            //                $arrEmployee = array();
            //                foreach ($prdTmp as $k => $dEmployee) {
            //                    $arrEmployee[$dEmployee->id] = $dEmployee;
            //                }
            //                $writer = $curl->syncEmployeeToApk($url, $arrEmployee);
            //            }

            echo "<script>top.location.reload();</script>";
            //            }
            //            else {
            //                echo lgShowWarning("Opss..." . __LINE__ , "gagal menyimpan data, cobalah beberapa saat lagi, atau hubungi webadmin");
            //            }
        }
        else {
            $errMsg = "";
            foreach ($f->getValidationResults() as $err) {
                $errMsg .= "Error in $err[fieldLabel]:  $err[errMsg]";
            }
            echo "<script>top.document.getElementById('btnSave').disabled=false;</script>";
            die(lgShowAlert($errMsg));
        }
    }

    public function cloneProcess()
    {

        if (isset($_POST['id'])) {
            unset($_POST['id']);
        }

        $arrAlert = array(
            "html"              => "<img src='" . base_url() . "public/images/sys/loader-100.gif'> <br>Please wait ... ... ,<br>saving data<br>",
            "showConfirmButton" => false,
            "allowOutsideClick" => false,
        );

        // echo swalAlert($arrAlert);

        $content = "";
        //==menyimpan inputan data baru ke dalam datamodel, lalu dari datamodel ke database (dilakukan oleh CI)

        $className = "Mdl" . $this->uri->segment(4);
        $dcomConf = isset($this->config->item("dataPostProcessors")[$className]) ? $this->config->item("dataPostProcessors")[$className][0] : array();//cek ada Dcomnya tidak
        $ctrlName = $this->uri->segment(4);
        $this->load->model("Mdls/" . $className);
        $cabang_id = my_cabang_id();
        switch ($className) {
            case "MdlProduk":
                arrPrintKuning($_POST);
                if (!isset($_POST["jml_serial"])) {
                    matiDisini("serial tidak terdeteksi");
                }
                break;
        }

        $kval_ = isset($_POST["kval"]) ? $_POST["kval"] : "";
        $mainObj = $o = new $className;
        $f = new MyForm($o, "addProcess");

        $inserted = array();
        if ($f->isInputValid()) { //==jika validasi lengkap
            if (sizeof($o->getUnionPairs()) > 0) {
                if ($f->isUnionValid()) {
                }
                else {
                    $errMsg = "";
                    foreach ($f->getValidationResults() as $err) {
                        $errMsg .= "Error in <strong>$err[fieldLabel]</strong>:  $err[errMsg]<br>";
                    }
                    echo "<script>top.document.getElementById('btnSave').disabled=false;</script>";
                    die(lgShowAlert($errMsg));
                }
            }
            $this->db->trans_start();
            foreach ($o->getFields() as $fieldName => $spec) {
                $fName = isset($spec['kolom']) ? $spec['kolom'] : $fieldName;
                if (isset($spec['inputType'])) {
                    // cekMerah($spec['inputType']);
                    switch ($spec['inputType']) {
                        case "checkbox":
                            $data[$fName] = base64_encode(serialize($this->input->post($fName)));
                            break;
                        case "qtyFillBox":
                            $data[$fName] = base64_encode(serialize($this->input->post($fName)));
                            break;
                        case "texts":
                            if (isset($spec['dataParams'])) {
                                $tmp = array();
                                foreach ($spec['dataParams'] as $param) {
                                    $tmp[$param] = $this->input->post($fName . "_" . $param);
                                }
                                $data[$fName] = base64_encode(serialize($tmp));
                            }
                            break;
                        case "password":
                            $data[$fName] = md5($this->input->post($fName));
                            break;
                        case "file":
                            if ($_FILES[$fName]['size'] > 0) {
                                //                                $image["image"] = file_get_contents($_FILES[$fName]['tmp_name']);
                                //                                $data[$fName] = blobEncode($image);
                                //
                                //                                                                    arrPrint($data);
                                //                                    die();

                                $request = curl_init(cdn_upload_images());
                                $realpath = realpath($_FILES[$fName]['tmp_name']);
                                curl_setopt($request, CURLOPT_POST, true);
                                $fields = [
                                    //                                    'file' => new \CurlFile($realpath, $_FILES[$fName]['type'], $_FILES[$fName]['name']),
                                    'file'          => "@" . $realpath . ";filename=" . $_FILES[$fName]['name'] . ";type=" . $_FILES[$fName]['type'],
                                    'server_source' => $_SERVER['HTTP_HOST'],
                                ];
                                curl_setopt($request, CURLOPT_POSTFIELDS, $fields);
                                curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
                                $cUrl_result = json_decode(curl_exec($request));

                                curl_close($request);


                                if (isset($cUrl_result->status) && $cUrl_result->status == 'success') {
                                    //                                    $imagesBlob["files"] = $cUrl_result->full_url;
                                    //                                    $dataLast = array_replace($data, $imagesBlob);
                                    $data[$fName] = $cUrl_result->full_url;
                                    //
                                    //                                                                        arrPrint($data);
                                    //                                                                        die();
                                }
                                else {
                                    echo "<script>top.swal('error', 'image tidak valid, coba untuk ganti gambar yang akan di upload', 'error');</script>";
                                    die();
                                }

                            }
                            else {
                                cekHEre("$fName no image");
                                $data[$fName] = "";
                            }
                            break;
                        case "image":
                            if ($_FILES[$fName]['size'] > 0) {
                                // arrPrint($_FILES[$fName]);
                                $request = curl_init(cdn_upload_images());
                                $realpath = realpath($_FILES[$fName]['tmp_name']);
                                curl_setopt($request, CURLOPT_POST, true);
                                // cekMErah(cdn_upload_images());
                                $fields = [
                                    'file'          => new \CurlFile($realpath, $_FILES[$fName]['type'], $_FILES[$fName]['name']),
                                    'server_source' => $_SERVER['HTTP_HOST'],
                                ];
                                curl_setopt($request, CURLOPT_POSTFIELDS, $fields);
                                curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
                                $cUrl_result = json_decode(curl_exec($request));
                                // echo ($cUrl_result);
                                curl_close($request);


                                if (isset($cUrl_result->status) && $cUrl_result->status == 'success') {
                                    $data[$fName] = $cUrl_result->full_url;
                                }
                                else {
                                    echo "<script>top.Sweetalert2('error', 'image tidak valid, coba untuk ganti gambar yang akan di upload', 'error');</script>";
                                    die(__LINE__);
                                }

                            }
                            else {
                                cekHEre("$fName no image");
                                $data[$fName] = "";
                            }
                            break;
                        case "hidden":

                            break;
                        case "textarea":
                            //                            $data[$fName] = nl2br($this->input->post($fName));
                            $data[$fName] = $this->input->post($fName);
                            //                            print_r($data);
                            //                            matiHere("hiksss");
                            break;
                        default:
                            $data[$fName] = heTrimAvoidedChars($this->input->post($fName));
                            break;
                    }
                }
                else {
                    switch ($spec['type']) {
                        case "varchar":
                            $data[$fName] = $this->input->post($fName);
                            break;
                        case "int":
                            $data[$fName] = $this->input->post($fName);
                            break;
                        case "date":
                            $data[$fName] = date("Y-m-d");
                            break;
                        case "datetime":
                            $data[$fName] = date("Y-m-d H:i:s");
                            break;
                        case "timestamp":
                            $data[$fName] = date("Y-m-d H:i:s");
                            break;
                        default:
                            $data[$fName] = $this->input->post($fName);
                            break;
                    }
                }
                // echo __LINE__;
                // arrPrintHijau($data);
                /* --------------------------------------------------------
                 * langsung ngisi kolom di main table dari kolom erference (pendukung)
                 * --------------------------------------------------------*/
                if (isset($spec['strField']) || isset($spec["referenceDatas"])) {
                    if (isset($spec["reference"])) {
                        $this->load->model("Mdls/" . $spec["reference"]);
                        $idnya = $this->input->post($spec["kolom"]);
                        if ($idnya > 0) {
                            $tmpRe = new $spec["reference"]();
                            $tmpFields = $tmpRe->lookupByID($idnya)->result();
                            // showLast_query("biru");
                            // cekHere(count($tmpFields));
                            if (isset($spec['strField'])) {
                                $strField = isset($tmpFields[0]->$spec["strField"]) ? $tmpFields[0]->$spec["strField"] : "";
                                $data[$spec["kolom_nama"]] = $strField;
                            }

                            if (isset($spec["referenceDatas"])) {
                                foreach ($spec["referenceDatas"] as $kolomSrc => $kolomTarget) {
                                    $data[$kolomTarget] = $tmpFields[0]->$kolomSrc;
                                }
                            }
                        }
                    }
                }
            }
            if (sizeof($o->getAutoFillFields()) > 0) {
                foreach ($o->getAutoFillFields() as $mainCol => $autoFieldsCal) {
                    $data[$mainCol] = makeValue($autoFieldsCal, $this->input->post(), $this->input->post(), 0);
                }
            }
            if (sizeof($o->getFilters()) > 0) {
                foreach ($o->getFilters() as $k => $v) {
                    $condPair = explode("=", $v);
                    if (sizeof($condPair) > 1) {
                        $data[$condPair[0]] = trim($condPair[1], "'");
                    }
                }
            }
            $this->load->model("Mdls/" . "MdlDataTmp");
            $dTmp = new MdlDataTmp();
            $tmpData = array(
                "mdl_name"         => $className,
                "mdl_label"        => $ctrlName,
                "proposed_by"      => $this->session->login['id'],
                "proposed_by_name" => $this->session->login['nama'],
                "proposed_date"    => dtimeNow(),
                "content"          => blobEncode($data),
            );

            $validateDataFields = sizeof($o->getValidateData()) > 0 ? $o->getValidateData() : array();
            $tmpOrig = array();
            if (sizeof($validateDataFields) > 0) {
                $where = array();
                foreach ($validateDataFields as $fieldsValidate) {
                    $where[$fieldsValidate] = $data[$fieldsValidate];
                }
                $tmpOrig = $o->lookupByCondition($where)->result();
                $bNama = $tmpOrig[0]->biaya_nama;
                $bProduk = $tmpOrig[0]->produk_nama;
                $bProdukId = $tmpOrig[0]->produk_id;
            }
            if (sizeof($tmpOrig) > 0) {
                cekHere(":: HAHAHA ");
                if ($bProdukId > 0) {
                    $where2 = array("produk_id" => $bProdukId);
                }
                else {
                    $where2 = array();
                }
                $tmpOrig2 = $o->lookupByCondition($where2)->result();
                showLast_query("biru");
                arrPrint($tmpOrig2);

                $hasil = "";
                $hasil .= "$bNama  already set up<br>";
                foreach ($tmpOrig2 as $itemOrigs) {
                    $bNama2 = $itemOrigs->biaya_nama;
                    $bNilai2 = formatField("harga", $itemOrigs->nilai);

                    foreach ($o->getListedFieldsView() as $val) {
                        $bNama2 = $itemOrigs->$val;
                        $bNilai2 = isset($itemOrigs->nilai) ? formatField("harga", $itemOrigs->nilai) : "";
                        $var = "$bNama2 <span>$bNilai2</span>";
                        if ($hasil == "") {
                            $hasil .= "$var";
                        }
                        else {
                            $hasil = "$hasil<br>$var";
                        }
                    }


                }

                $bJudul = "$bProduk";
                $alerts = array(
                    "type"  => "warning",
                    "title" => $bJudul,
                    "html"  => $hasil,
                );
                echo swalAlert($alerts);
                echo "<script>top.document.getElementById('btnSave').disabled=false;</script>";
                die();
                matiHere("data $bNama  already exist on $bProduk, no data change<hr>");
                //udah ada data ngapain ditambah lagi dengan id sama.....
            }
            if ($this->creatorUsingApproval) {
                cekHere("approval");
                $insertID = $dTmp->addData($tmpData, $dTmp->getTableName()) or die(lgShowError("Gagal menulis pengajuan data", __FILE__));
                $this->session->errMsg = "Data proposal has been saved and pending approval";
                $this->load->model("Mdls/" . "MdlDataHistory");
                $hTmp = new MdlDataHistory();
                $tmpHData = array(
                    "orig_id"            => 0,
                    "mdl_name"           => $className,
                    "mdl_label"          => get_class($this),
                    "old_content"        => "",
                    "new_content"        => base64_encode(serialize($data)),
                    "new_content_intext" => print_r($data, true),
                    "label"              => "proposed",
                    "oleh_id"            => $this->session->login['id'],
                    "oleh_name"          => $this->session->login['nama'],
                );
                $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
                cekHitam($this->db->last_query());
            }
            else {

                $validateDataFields = sizeof($o->getValidateData()) > 0 ? $o->getValidateData() : array();
                $tmpOrig = array();
                if (sizeof($validateDataFields) > 0) {
                    $where = array();
                    foreach ($validateDataFields as $fieldsValidate) {
                        $where[$fieldsValidate] = $data[$fieldsValidate];
                    }
                    $tmpOrig = $o->lookupByCondition($where)->result();
                }
                if (sizeof($tmpOrig) > 0) {
                    matiHere("data already exist, no data change");
                    //udah ada data ngapain ditambah lagi dengan id sama.....
                }

                $mainDatas = $data;
                $mainInsertId = $insertID = $o->addData($data, $o->getTableName()) or die(lgShowError(__LINE__ . " Gagal menulis data", __FILE__));
                $supplier_id = isset($data['supplier_id']) ? $data['supplier_id'] : null;
                //                showLast_query("orange");
                //                arrPrintPink($mainDatas);

                //region takbahan Dcom
                if (sizeof($dcomConf) > 0) {
                    $inParam = array_merge($inserted, $data);
                    $className = "DCom" . $dcomConf;
                    $this->load->Model("DComs/" . $className);
                    $d = new $className();
                    $d->setWriteMode("insert");
                    $d->pair($inParam) or die("Tidak berhasil memasang  values pada dcom-processor: $className/" . __FUNCTION__ . "/" . __LINE__);
                    $gotParams = $d->exec();
                }
                //endregion

                $this->load->model("Mdls/" . "MdlDataHistory");
                $hTmp = new MdlDataHistory();
                $tmpHData = array(
                    "orig_id"            => 0,
                    "mdl_name"           => $className,
                    "mdl_label"          => get_class($this),
                    "old_content"        => "",
                    "new_content"        => base64_encode(serialize($data)),
                    "new_content_intext" => print_r($data, true),
                    "label"              => "applied",
                    "oleh_id"            => $this->session->login['id'],
                    "oleh_name"          => $this->session->login['nama'],
                );
                $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));

                /* ---------------------------------------------------------------
                 * tested pada auto COA yg pakai aaproval masuk di doApproveFrom
                 * --------------------------------------------------------------*/
                if (method_exists($mainObj, "getConnectingData")) {
                    $merek_id = isset($mainDatas['merek_id']) ? $mainDatas['merek_id'] : null;
                    $merek_nama = isset($mainDatas['merek_nama']) ? $mainDatas['merek_nama'] : null;
                    $supplier_nama = isset($mainDatas['supplier_nama']) ? $mainDatas['supplier_nama'] : null;
                    $supplier_id = isset($mainDatas['supplier_id']) ? $mainDatas['supplier_id'] : null;
                    $nama = ucwords($mainDatas['nama']);
                    $negara = isset($data['country']) ? $data['country'] : "";
                    $extern_tipe = $negara == "ID" ? "lokal" : "non_lokal";
                    $my_name = my_name();
                    $connectings = $mainObj->getConnectingData();
                    foreach ($connectings as $model => $param_connecting) {
                        $fields = isset($param_connecting['fields']) ? $param_connecting['fields'] : $param_connecting;
                        $this->load->model($param_connecting['path'] . "/$model");
                        $connObj = new $model();
                        if (isset($param_connecting['staticOptions'])) {
                            $strHead_code = "";
                            if (is_array($param_connecting['staticOptions'])) {
                                $strHead_code_array = $param_connecting['staticOptions'];
                                foreach ($strHead_code_array as $field => $cfParams) {
                                    if (isset($cfParams['var_main'])) {
                                        $cNilai = $$cfParams['var_main'];
                                    }
                                    else {
                                        $cNilai = $cfParams['str'];
                                    }
                                    $strHead_code[$field] = $cNilai;
                                }
                            }
                            else {
                                $strHead_code = $param_connecting['staticOptions'];
                            }
                        }
                        else {
                            mati_disini("static optionnya tolong dikasih");
                        }
                        $datas = array();

                        foreach ($fields as $field => $cfParams) {
                            if (isset($cfParams['var_main'])) {
                                $cNilai = $$cfParams['var_main'];
                            }
                            else {
                                $cNilai = $cfParams['str'];
                            }
                            $datas[$field] = $cNilai;
                        }

                        /* -------------------------------------------------
                         * menulis ke table connecting
                         * -------------------------------------------------*/
                        $lastInset_code = $connObj->$param_connecting['fungsi']($strHead_code, $datas);
                        //                        showLast_query("merah");

                        /* -------------------------------------------------
                         * ngupdate ke data utama
                         * -------------------------------------------------*/
                        if (isset($param_connecting['updateMain'])) {
                            foreach ($param_connecting['updateMain']['condites'] as $key => $condite) {
                                $mainCondites[$key] = $$condite;
                            }
                            foreach ($param_connecting['updateMain']['datas'] as $key => $val) {
                                $mainUpdate[$key] = $$val;
                            }
                            $mainObj->updateData($mainCondites, $mainUpdate);
                            //                            showLast_query("orange");
                        }
                        //                        cekHitam($lastInset_code);
                    }
                }
                //    ---------------memasukan pendukung sebagau produk----------------------------------------------
                if ($kval_ != "") {
                    $masterSubs = $mainObj->getMasterSubs();
                    $subFields = array();
                    $getMasterSubs = isset($getMasterSubs) ? $getMasterSubs : array();
                    foreach ($getMasterSubs as $ky => $subRow) {
                        $subFields[$subRow['sub_kategori_id']] = $subRow;
                    }
                    cekBiru($subFields);
                    $fields_ = $mainObj->getFields();
                    if (isset($masterSubs[$kval_]['add_produk'])) {
                        $add_produk = $masterSubs[$kval_]['add_produk'];
                        foreach ($add_produk as $row) {
                            $ssc = explode("_", $row);
                            $sub_cat_id = 4;
                            $sub_cat_nama = isset($subFields[4]) ? $subFields[4]['sub_kategori_nama'] : "";

                            $ref = $fields_[$row]['reference'];
                            $kol = $fields_[$row]['kolom'];
                            $strF = isset($fields_[$row]['strField']) ? $fields_[$row]['strField'] : null;
                            $selectedID = $_POST[$kol];

                            if ($selectedID * 1 > 0) {
                                $this->load->model("Mdls/" . "$ref");
                                $hAdd = new $ref;
                                $hAdd->addFilter("id='" . $selectedID . "'");
                                $dataSrcs = $hAdd->lookupAll()->result();

                                $newData = array(
                                    'kategori_id'       => 3,
                                    'kategori_nama'     => "non unit",
                                    'sub_kategori_id'   => $sub_cat_id,
                                    'sub_kategori_nama' => $sub_cat_nama,
                                    'kode'              => $dataSrcs[0]->sku,
                                    'barcode'           => $dataSrcs[0]->barcode,
                                    'nama'              => $dataSrcs[0]->nama,
                                    'supplier_id'       => $data['supplier_id'],
                                    'kapasitas_id'      => $data['kapasitas_id'],
                                    'folders'           => $data['folders'],
                                    'size_id'           => $data['size_id'],
                                    'size_nama'         => $data['size_nama'],
                                    'tipe_id'           => $data['tipe_id'],
                                    'tipe_nama'         => isset($data['tipe_nama']) ? $data['tipe_nama'] : null,
                                    'merek_id'          => $data['merek_id'],
                                    'merek_nama'        => isset($data['merek_nama']) ? $data['merek_nama'] : null,
                                    'phase_id'          => $data['phase_id'],
                                    'phase_nama'        => isset($data['phase_nama']) ? $data['phase_nama'] : null,
                                    'series_id'         => isset($data['series_id']) ? $data['series_id'] : null,
                                    'series_nama'       => isset($data['series_nama']) ? $data['series_nama'] : null,
                                    'jml_serial'        => 1,
                                    'status'            => 1,
                                    'trash'             => 0,
                                    'jenis'             => "item",
                                );

                                $o->setFilters(array());
                                $tmpD = $o->lookupByCondition(array('barcode' => $dataSrcs[0]->sku, 'status' => 1))->result();

                                if (!$tmpD) {
                                    $mainInsertId = $insertID = $o->addData($newData, $o->getTableName()) or die(lgShowError(__LINE__ . " Gagal menulis data", __FILE__));
                                    //                                    /* ---------------------------------------------------------------
                                    //                                     * tested pada auto COA yg pakai aaproval masuk di doApproveFrom
                                    //                                     * --------------------------------------------------------------*/
                                    if (method_exists($mainObj, "getConnectingData")) {
                                        $merek_id = isset($mainDatas['merek_id']) ? $mainDatas['merek_id'] : null;
                                        $merek_nama = isset($mainDatas['merek_nama']) ? $mainDatas['merek_nama'] : null;
                                        $supplier_nama = isset($mainDatas['supplier_nama']) ? $mainDatas['supplier_nama'] : null;
                                        $supplier_id = isset($mainDatas['supplier_id']) ? $mainDatas['supplier_id'] : null;
                                        $nama = ucwords($mainDatas['nama']);
                                        $negara = isset($data['country']) ? $data['country'] : "";
                                        $extern_tipe = $negara == "ID" ? "lokal" : "non_lokal";
                                        $my_name = my_name();

                                        $connectings = $mainObj->getConnectingData();
                                        foreach ($connectings as $model => $param_connecting) {
                                            $fields = isset($param_connecting['fields']) ? $param_connecting['fields'] : $param_connecting;
                                            $this->load->model($param_connecting['path'] . "/$model");
                                            $connObj = new $model();
                                            if (isset($param_connecting['staticOptions'])) {
                                                $strHead_code = "";
                                                if (is_array($param_connecting['staticOptions'])) {
                                                    $strHead_code_array = $param_connecting['staticOptions'];
                                                    foreach ($strHead_code_array as $field => $cfParams) {
                                                        if (isset($cfParams['var_main'])) {
                                                            $cNilai = $$cfParams['var_main'];
                                                        }
                                                        else {
                                                            $cNilai = $cfParams['str'];
                                                        }
                                                        $strHead_code[$field] = $cNilai;
                                                    }
                                                }
                                                else {
                                                    $strHead_code = $param_connecting['staticOptions'];
                                                }
                                            }
                                            else {
                                                mati_disini("static optionnya tolong dikasih");
                                            }
                                            $datas = array();

                                            foreach ($fields as $field => $cfParams) {
                                                if (isset($cfParams['var_main'])) {
                                                    $cNilai = $$cfParams['var_main'];
                                                }
                                                else {
                                                    $cNilai = $cfParams['str'];
                                                }
                                                $datas[$field] = $cNilai;
                                            }

                                            /* -------------------------------------------------
                                             * menulis ke table connecting
                                             * -------------------------------------------------*/
                                            $lastInset_code = $connObj->$param_connecting['fungsi']($strHead_code, $datas);
                                            /* -------------------------------------------------
                                             * ngupdate ke data utama
                                             * -------------------------------------------------*/
                                            if (isset($param_connecting['updateMain'])) {
                                                foreach ($param_connecting['updateMain']['condites'] as $key => $condite) {
                                                    $mainCondites[$key] = $$condite;
                                                }
                                                foreach ($param_connecting['updateMain']['datas'] as $key => $val) {
                                                    $mainUpdate[$key] = $$val;
                                                }
                                                $mainObj->updateData($mainCondites, $mainUpdate);
                                                showLast_query("orange");
                                            }
                                            //                                            cekHitam($lastInset_code);
                                        }
                                    }
                                    //                                    //    -------------------------------------------------------------
                                }
                            }

                            //                            if (method_exists($o, "paramSyncNamaNama")) {
                            //                                $syncNamaNamaMdls = method_exists($o, "paramSyncNamaNama") ? $o->paramSyncNamaNama() : mati_disini("paramSyncNamaNama belum terdifine");
                            //                                foreach ($syncNamaNamaMdls as $syncNamaNamaMdl => $syncNamaNamaParams) {
                            //                                    $id_ygdisync = isset($data[$syncNamaNamaParams['id']]) ? $data[$syncNamaNamaParams['id']] : "";
                            //                                    $o->setTokoId(my_toko_id());
                            //                                    if ($id_ygdisync > 0) {
                            //                                        $o->syncNamaNama($id_ygdisync);
                            //                                    }
                            //                                }
                            //                            }

                        }
                    }
                }
            }

            //            matiHere("data berhasil disimpan. under maintenance @" . __LINE__);
            $this->db->trans_complete();

            $file = fopen(__DIR__ . '/eusvc/sync/' . $className . "_" . $this->session->login['toko_id'] . ".txt", "w");
            echo fwrite($file, json_encode(array("datetime" => date("Y-m-d H:i:s"))));
            fclose($file);

            echo "<script>top.document.getElementById('result').src='" . base_url() . "konversi/Transaksi/validate/1339?gr=a29udmVyc2k';</script>";

        }
        else {
            cekMerah("tidak valid @" . __LINE__);
            $errMsg = "";
            foreach ($f->getValidationResults() as $err) {
                $errMsg .= "Error in <strong>$err[fieldLabel]</strong>:  $err[errMsg]<br>";
            }
            echo "<script>top.document.getElementById('btnSave').disabled=false;</script>";
            die(lgShowAlert($errMsg));
        }
    }

    public function updateProcess()
    {
        arrPrintHijau(url_segment());
        $id = url_segment(4);
        $className = "Mdl" . url_segment(3);
        $kolom = $_GET['k'];
        $nilai = $_GET['n'];
        arrPrintHijau($_GET);
        $this->db->trans_begin();

        $this->load->model("Mdls/" . $className);
        $mainObj = $o = new $className();

        $o->setTokoId(my_toko_id());
        $condites = array(
            "id" => $id
        );
        $newdatas = array(
            $kolom => $nilai
        );
        $o->updateData($condites, $newdatas);
        showLast_query("pink");

        // matiDisini("belum commit");
        $this->db->trans_complete();
        $link_setting = base_url() . "setting/Cabang/ViewSetting";
        echo "<script>
            $('#show_anakan_1').load('$link_setting')
        </script>";
    }

    public function delete()
    {
        $content = "";
        //==menghapus (aslinya mendisable) data sesuai datamodel dan id-nya yang bersesuaian
        $ctrlName = $this->segment_4;
        $className = "Mdl" . $ctrlName;

        //        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        if (!$this->allowDelete) {
            $p = new Layout(get_class($this), "Wewenang ditolak", "application/template/blank.html");
            $content .= ("<div class='alert alert-danger'>");
            $content .= ("Anda tidak punya wewenang pada halaman ini<br>");
            $content .= ("<a href='" . base_url() . "'>Ke depan</a>");
            $content .= ("</div>");
            $p->render();
            die();
        }
        $this->load->model("Mdls/" . $className);
        $o = new $className;
        $indexFieldName = "id";
        $selectedID = $this->uri->segment(5);
        $where = array("id" => $selectedID);

        $oldDataTmp = $o->lookupByID($selectedID)->result();

        $this->db->trans_start();

        $this->load->model("Mdls/" . "MdlDataTmp");
        $dTmp = new MdlDataTmp();
        $tmpData = array(
            "orig_id"          => $selectedID,
            "mdl_name"         => $className,
            "mdl_label"        => $ctrlName,
            "proposed_by"      => $this->session->login['id'],
            "proposed_by_name" => $this->session->login['nama'],
            "proposed_date"    => date("Y-m-d H:i:s"),
            "propose_type"     => "delete",
            "content"          => base64_encode(serialize((array)$oldDataTmp[0])),
        );
        if ($this->deleterUsingApproval) {
            $insertID = $dTmp->addData($tmpData, $dTmp->getTableName()) or die(lgShowError("Gagal menulis pengajuan data", __FILE__));
            $this->session->errMsg = "Your deletion proposal has been saved and pending approval";

            $tmpOrig = $o->lookupByCondition(array("id" => $selectedID))->result();
            $o->setFilters(array());
            $o->updateData($where, array("status" => 0, "trash" => 1), $o->getTableName());
            $tmpNew = (array)$tmpOrig;
            $tmpNew["status"] = 0;
            $tmpNew["trash"] = 1;

            //<editor-fold desc="data history / propose">
            $this->load->model("Mdls/" . "MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id"            => $selectedID,
                "mdl_name"           => $className,
                "mdl_label"          => $ctrlName,
                "old_content"        => base64_encode(serialize((array)$tmpOrig)),
                "old_content_intext" => print_r($tmpOrig, true),
                "new_content"        => base64_encode(serialize($tmpNew)),
                "new_content_intext" => print_r($tmpNew, true),
                "label"              => "delete_proposed",
                "oleh_id"            => $this->session->login['id'],
                "oleh_name"          => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            //</editor-fold>

        }
        else {
            $tmpOrig = $o->lookupByCondition(array("id" => $selectedID))->result();

            //<editor-fold desc="really hapus">
            $o->lookupByCondition(array("id" => $selectedID));
            $data['trash'] = "1";
            //$o->deleteData($where, $o->getTableName());
            $o->setFilters(array());
            $o->updateData($where, $data, $o->getTableName());
            //            cekMerah($this->db->last_query());
            //</editor-fold>

            //<editor-fold desc="data history / approve">
            $this->load->model("Mdls/" . "MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id"            => $selectedID,
                "mdl_name"           => $className,
                "mdl_label"          => $ctrlName,
                "old_content"        => base64_encode(serialize((array)$tmpOrig)),
                "old_content_intext" => print_r($tmpOrig, true),
                "new_content"        => base64_encode(serialize($data)),
                "new_content_intext" => print_r($data, true),
                "label"              => "deleted",
                "oleh_id"            => $this->session->login['id'],
                "oleh_name"          => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            //</editor-fold>
        }

        //                    matiHere("complittt $className");
        $this->db->trans_complete();


        $key = isset($_GET['k']) ? $_GET['k'] : "";
        if (isset($_GET["reload"])) {
            echo "<script>top.location.reload();</script>";
        }
        else {
            redirect(MODUL_PATH . get_class($this) . "/viewdt/$ctrlName/?k=$key");
        }

    }

    public function status()
    {
        arrPrint(url_segment());

        // matiDisini(__LINE__);
        $content = "";
        //==menghapus (aslinya mendisable) data sesuai datamodel dan id-nya yang bersesuaian
        $ctrlName = $this->uri->segment(4);
        $className = "Mdl" . $this->uri->segment(4);
        $ctrlName = $this->uri->segment(4);
        //        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        if (!$this->allowDelete) {
            $p = new Layout(get_class($this), "Wewenang ditolak", "application/template/blank.html");
            $content .= ("<div class='alert alert-danger'>");
            $content .= ("Anda tidak punya wewenang pada halaman ini<br>");
            $content .= ("<a href='" . base_url() . "'>Ke depan</a>");
            $content .= ("</div>");
            $p->render();
            die();
        }
        $this->load->model("Mdls/" . $className);
        $o = new $className;
        $indexFieldName = "id";
        $selectedID = $this->uri->segment(5);
        $statusDb = $this->uri->segment(6);
        $statusNew = $statusDb == 1 ? 0 : 1;
        $where = array(
            "id" => $selectedID
        );

        $o->setFilters(array());
        // $o->addFilter("toko_id=" . $this->session->login['toko_id']);
        $oldDataTmp = $o->lookupByID($selectedID)->result();

        showLast_query("lime");
        // arrPrintPink($oldDataTmp);

        $this->db->trans_start();

        $this->load->model("Mdls/" . "MdlDataTmp");
        $dTmp = new MdlDataTmp();
        $tmpData = array(
            "orig_id"          => $selectedID,
            "mdl_name"         => $className,
            "mdl_label"        => $ctrlName,
            "proposed_by"      => $this->session->login['id'],
            "proposed_by_name" => $this->session->login['nama'],
            "proposed_date"    => date("Y-m-d H:i:s"),
            "propose_type"     => "status",
            "content"          => base64_encode(serialize((array)$oldDataTmp[0])),
        );
        // arrPrintPink($tmpData);
        // mati_disini(__LINE__);
        if ($this->deleterUsingApproval) {
            // $insertID = $dTmp->addData($tmpData, $dTmp->getTableName()) or die(lgShowError("Gagal menulis pengajuan data", __FILE__));
            showLast_query("biru");
            $this->session->errMsg = "Permintaan perubahan status sudah disimpan";

            $tmpOrig = $o->lookupByCondition(
                array("id" => $selectedID))->result();
            $o->setFilters(array());
            $o->updateData($where, array("status" => $statusNew), $o->getTableName());
            //            showLast_query("kuning");
            $tmpNew = (array)$tmpOrig;
            $tmpNew["status"] = $statusNew;
            // $tmpNew["trash"] = 1;

            //<editor-fold desc="data history / propose">
            $this->load->model("Mdls/" . "MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id"            => $selectedID,
                "mdl_name"           => $className,
                "mdl_label"          => $ctrlName,
                "old_content"        => base64_encode(serialize((array)$tmpOrig)),
                "old_content_intext" => print_r($tmpOrig, true),
                "new_content"        => base64_encode(serialize($tmpNew)),
                "new_content_intext" => print_r($tmpNew, true),
                "label"              => "status_proposed",
                "oleh_id"            => $this->session->login['id'],
                "oleh_name"          => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            showLast_query("hijau");
            //</editor-fold>

        }
        else {
            $tmpOrig = $o->lookupByCondition(array("id" => $selectedID))->result();

            //<editor-fold desc="really hapus">
            $o->lookupByCondition(array("id" => $selectedID));
            $data['status'] = "$statusNew";
            //$o->deleteData($where, $o->getTableName());
            $o->setFilters(array());
            $o->updateData($where, $data, $o->getTableName());
            cekMerah($this->db->last_query());
            //</editor-fold>

            //<editor-fold desc="data history / approve">
            $this->load->model("Mdls/" . "MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id"            => $selectedID,
                "mdl_name"           => $className,
                "mdl_label"          => $ctrlName,
                "old_content"        => base64_encode(serialize((array)$tmpOrig)),
                "old_content_intext" => print_r($tmpOrig, true),
                "new_content"        => base64_encode(serialize($data)),
                "new_content_intext" => print_r($data, true),
                "label"              => "status",
                "oleh_id"            => $this->session->login['id'],
                "oleh_name"          => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            showLast_query("hijau");
            //</editor-fold>
        }

        // matiHere("complittt $className");
        $this->db->trans_complete();

        echo lgShowSuccess("Berhasil", "untuk membarui tampilan Anda bisa melakukan refresh browser");


        $key = isset($_GET['k']) ? $_GET['k'] : "";
        redirecResult(base_url() . get_class($this) . "/viewdt/$ctrlName/?k=$key");
    }

    public function index()
    {

        $content = "";
        //==aksi default, yaitu dibawa ke mode "view"
        //==sebelumnya dicek dulu, user buka halaman pakai slash atau enggak

        // $splitStr = explode("/", __FILE__);
        // if (get_class($this) . ".php" != $splitStr[sizeof($splitStr) - 1]) {
        //     redirect(base_url() . get_class($this) . "/view");
        // } else {
        //     die("DiRECT access to this file is N.O.T. allowed!");
        // }

        $data = array(
            "title"               => $this->segment_4,
            'mode'                => $mode_switch,
            'segment_3'           => $this->segment_4,
            'mdl'                 => $this->className,
            // --
            "error"               => $this->session->errMsg,
            "strDataProposeTitle" => "<span class='glyphicon glyphicon-alert blink'></span>&nbsp; <span class='tebal'>approval needed</span>",
            "arrayProgressLabels" => $arrayProgressLabel,
            "arrayOnProgress"     => $arrItemTmp,
            // ---
            "strDataHistTitle"    => "<span class='glyphicon glyphicon-time'></span> recent data updates",
            "linkStr"             => "",
            "add_link"            => $strAddLink,
            "edit_link"           => $strEditLink,
            "bulk_edit_link"      => $strBulkEditLink,
            "folder_link"         => isset($folder_link) ? $folder_link : "",
            "filterId"            => $folderId,
            "filterId_str"        => $folderId > 0 ? "?fid=$folderId" : "",
            // -----
            "arrayRecapLabels"    => $arrayRecapLabel,
            "arrayRecap"          => $arrayRecap,
            "maximumData"         => $maximumData,
            "jmlDataNow"          => $jmlDataNow,

        );
        // arrPrint($data);
        $this->load->view("data", $data);
    }

    public function view()
    {
        arrPrint($this->uri->segment_array());
        $content = "";
        if (!isset($this->session->login['id'])) {
            gotoLogin();//remember last login
        }
        $ctrlName = $this->uri->segment(4);
        $className = "Mdl" . $ctrlName;

        $dataRel = isset($this->config->item('dataRelation')[$className]) ? $this->config->item('dataRelation')[$className] : array();
        //<editor-fold desc="data proposal data">
        $this->load->model("Mdls/" . "MdlDataTmp");
        $tData = new MdlDataTmp();
        $tData->addFilter("mdl_name='$className'");
        $tmpTmp = $tData->lookupAll()->result();
        // cekHitam($this->db->last_query());
        $dataProposals = array();
        if (sizeof($tmpTmp) > 0) {
            foreach ($tmpTmp as $row) {
                $mdlName = $row->mdl_name;
                $dataAccess = isset($this->config->item('heDataBehaviour')[$mdlName]) ? $this->config->item('heDataBehaviour')[$mdlName] : array(
                    "viewers"       => array(),
                    "creators"      => array(),
                    "creatorAdmins" => array(),
                    "updaters"      => array(),
                    "updaterAdmins" => array(),
                    "deleters"      => array(),
                    "deleterAdmins" => array(),
                );
                //                $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
                $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
                $allowView = false;
                $allowCreate = false;
                $allowEdit = false;
                $allowDelete = false;
                foreach ($mems as $mID) {
                    if (in_array($mID, $dataAccess['viewers'])) {
                        $allowView = true;
                    }
                    if (in_array($mID, $dataAccess['creators'])) {
                        $allowCreate = true;
                    }
                    if (in_array($mID, $dataAccess['updaters'])) {
                        $allowEdit = true;
                    }
                    if (in_array($mID, $dataAccess['deleters'])) {
                        $allowDelete = true;
                    }
                }

                if ($allowView || $allowCreate) {
                    if (!isset($dataProposals[$mdlName])) {
                        $dataProposals[$mdlName] = array();
                    }
                    $dataProposals[$mdlName][] = array(
                        "id"           => $row->_id,
                        "label"        => $row->mdl_label,
                        "origID"       => $row->orig_id,
                        "proposer"     => $row->proposed_by_name,
                        "date"         => $row->proposed_date,
                        "content"      => unserialize(base64_decode($row->content)),
                        "propose_type" => $row->propose_type,
                    );
                }
            }
        }

        //</editor-fold>

        $realObjName = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : $ctrlName;
        $title = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : $ctrlName;
        $dataExtRel = isset($this->config->item('dataExtRelation')[$className]["images"]) ? $this->config->item('dataExtRelation')[$className]["images"] : array();
        $arrExtImg = array();
        $badgeData = array();


        if (sizeof($dataExtRel) > 0) {
            $this->load->model("Mdls/MdlImages");
            $im = new MdlImages();
            $imgBlob = $im->lookupAll()->result();

            $countData = 0;
            foreach ($imgBlob as $rowImg) {
                $countData++;
                $arrExtImg[$rowImg->parent_id] = $rowImg->files;
                $badgeData[$rowImg->parent_id][] = $countData;
            }
        }

        $this->load->model("Mdls/" . $className);
        $o = new $className;
        $indexFieldName = "id";
        $fields = $o->getFields();
        $limitedEditor = $o->getLimiteEditor();

        $objState = "0";

        if (isset($_GET['trashed']) && $_GET['trashed'] > 0) {
            $objState = $_GET['trashed'];
            if ($objState == "1") {
                $title = "Deleted " . $title;
                $objStateStatus = "0";
            }
            $o->addFilter("trash='$objState'");
            $o->addFilter("status='$objStateStatus'");

        }
        else {
            //            $o->addFilter("trash='0'");
            //            $o->addFilter("status='1'");
        }

        switch ($objState) {
            case "0":
                $alternateLink = "<a href='" . base_url() . get_class($this) . "/view/$ctrlName?trashed=1'><span class='glyphicon glyphicon-ban-circle'></span> view deleted $realObjName</a>";
                break;
            case "1":
                $alternateLink = "<a href='" . base_url() . get_class($this) . "/view/$ctrlName'><span class='glyphicon glyphicon-ok-sign'></span> view active $realObjName</a>";
                break;
        }

        if (isset($_GET['fID']) && strlen($_GET['fID']) > 0) {
            $o->addFilter("folders='" . $_GET['fID'] . "'");
            //            $title.=" on ".$_GET['fName'];
        }

        if (isset($_GET['reqField']) && isset($_GET['reqVal'])) {
            $o->addFilter($_GET['reqField'] . "='" . $_GET['reqVal'] . "'");
        }

        if (isset($_GET['k']) && strlen($_GET['k']) > 1) {
            $key = $_GET['k'];
            $subtitle = "Contains '$key'";
        }
        else {
            $key = "";
            $subtitle = "List of $title";
        }

        $t = new Table();

        $arrItemTmp = array();
        if (sizeof($dataProposals) > 0) {


            foreach ($dataProposals as $mdlName => $pSpec) {
                $this->load->model("Mdls/" . $mdlName);
                $o2 = new $mdlName();
                $listedFields = $this->listedFields;
                $fields = $o2->getFields();

                foreach ($pSpec as $dSpec) {
                    $tmpItemTmp = array();
                    $dataStatus = $dSpec['origID'] > 0 ? "pembaruan" : "data baru";

                    foreach ($listedFields as $fName => $fLabel) {
                        $fRealName = $fName;
                        $fieldLabel = isset($dSpec['content'][$fRealName]) ? $dSpec['content'][$fRealName] : "";
                        //===if related
                        if (array_key_exists($fName, $this->relations)) {
                            $fieldLabel = isset($this->relationPairs[$fName][$fieldLabel]) ? $this->relationPairs[$fName][$fieldLabel] : "unknown rel";
                        }

                        if (isset($fields[$fName]['inputType'])) {
                            switch ($fields[$fName]['inputType']) {
                                case "image":
                                    $fieldLabel = sizeof($fieldLabel) > 0 ? "<img style='width: 40px;' src='$fieldLabel'>" : "<img src=''>";
                                    $tmpItemTmp[$fName] = $fieldLabel;
                                    break;
                                default:
                                    $tmpItemTmp[$fName] = $fieldLabel;
                                    break;
                            }
                        }
                    }

                    $approvalClick = "BootstrapDialog.closeAll();
                    BootstrapDialog.show(
                                   {
                                        title:'Data " . $dSpec['label'] . " &raquo; Setujui $dataStatus ',
                                        message: $('<div></div>').load('" . base_url() . "Data/editFrom/" . $dSpec['label'] . "/" . $dSpec['id'] . "/" . $dSpec['origID'] . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        closable:true,
                                        }
                                        );";

                    if (sizeof($dataExtRel) > 0) {
                        $fieldLabel = isset($dSpec['content']['images']) ? "<img style='width: 40px;' src='" . $dSpec['content']['images'] . "'>" : "";
                        $tmpItemTmp["images"] = $fieldLabel;
                    }

                    $tmpItemTmp["date"] = $dSpec['date'];
                    $tmpItemTmp["propose_type"] = $dSpec['propose_type'];
                    $tmpItemTmp["action"] = "<a class='btn btn-primary btn-block' href='JavaScript:void(0);' onclick =\"$approvalClick;\">review</a>";
                    $tmpItemTmp["history"] = "";
                    $arrItemTmp[] = $tmpItemTmp;
                }

            }

        }

        $addLink = base_url() . get_class($this) . "/add99/$ctrlName";
        $addManyLink = base_url() . get_class($this) . "/addMany/$ctrlName";

        if (isset($_GET['reqField']) && isset($_GET['reqVal'])) {
            $addLink .= "?reqField=" . $_GET['reqField'] . "&reqVal=" . $_GET['reqVal'];
        }


        $params = array();
        $limit_per_page = 10;
        $page = ($this->uri->segment(4)) ? ($this->uri->segment(4) - 1) : 0;

        $subitle = $subtitle . " hal. " . ($page + 1);
        $total_records = $o->lookupDataCount($key);
        showLast_query("kuning");

        if ($total_records > 0) {
            if (isset($_GET['sort']) && strlen($_GET['sort']) > 0) {
                $o->setSortby($_GET['sort']);
            }

            $params["results"] = $o->lookupLimitedData($limit_per_page, $page * $limit_per_page, $key);
            showLast_query("merah");
            $config = array(
                'base_url'           => base_url() . get_class($this) . '/' . __FUNCTION__ . "/$ctrlName/",
                'total_rows'         => $total_records,
                'per_page'           => $limit_per_page,
                "uri_segment"        => 4,
                'num_links'          => 6,
                'use_page_numbers'   => TRUE,
                'reuse_query_string' => TRUE,
                'full_tag_open'      => '<div class="text-center">',
                'full_tag_close'     => '</div>',
                'first_link'         => "<span class='fa fa-home'></span>",
                'first_tag_open'     => '<span style="padding:1px;">',
                'first_tag_close'    => '</span>',
                'last_link'          => "<span class='fa fa-gg'></span>",
                'last_tag_open'      => '<span style="padding:1px;">',
                'last_tag_close'     => '</span>',
                'next_link'          => "<span class='fa fa-angle-right'></span>",
                'next_tag_open'      => '<span style="padding:1px;">',
                'next_tag_close'     => '</span>',
                'prev_link'          => "<span class='fa fa-angle-left'></span>",
                'prev_tag_open'      => '<span style="padding:1px;">',
                'prev_tag_close'     => '</span>',
                'cur_tag_open'       => '<span class="btn btn-primary disabled">',
                'cur_tag_close'      => '</span>',
                'num_tag_open'       => '<span style="padding:1px;">',
                'num_tag_close'      => '</span>',
            );
            $this->pagination->initialize($config);
            $params["links"] = $this->pagination->create_links();
        }

        cekHere(__LINE__);
        $tmp = isset($params['results']) ? $params['results'] : array(); //===hasil data yang dibelokin ke hasil pagination

        $dataRow = array();
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();

        $defaultKey = $key != "" ? $key : "cari " . strtolower($title);
        $content .= ($t->addSpanRow(array(
            "<div class='input-group'>" . "<input type=text placeholder='$defaultKey' class='form-control text-center' onkeyup=\"if(detectEnter()==1){location.href='" . base_url() . get_class($this) . "/view/$ctrlName/?k='+this.value}\">" . "<span class='input-group-addon'>" . "<i class='glyphicon glyphicon-search'></i></span>" . "</div class='input-group'>",
        )));

        $selectID = 0;
        $arrayItem = array();
        if (sizeof($tmp) > 0) {//===ada data

            if ($this->uri->segment(3) > 0) {
                $rowCounter = ($limit_per_page * ($this->uri->segment(3) - 1));
            }
            else {
                $rowCounter = 0;
            }

            foreach ($tmp as $m => $rowSpec) {
                if ($this->allowEdit && $objState != "1") {
                    $updateLink = base_url() . get_class($this) . "/edit/$ctrlName/" . $rowSpec->id . "";
                    $editClick = "BootstrapDialog.show(
                                   {
                                        title:'Modify " . addslashes($title) . "',
                                        cssClass: 'edit-dialog',
                                        message: $('<div></div>').load('" . $updateLink . "'),
                                        draggable:false,
                                        closable:true,
                                        });";

                    $updateCommentStr = "Klik untuk mengubah entri";
                }
                else {
                    $updateCommentStr = "Anda tidak berhak mengubah entri";
                    $editClick = "return false;";
                }
                $deleteLink = base_url() . get_class($this) . "/delete/$ctrlName/" . $rowSpec->$indexFieldName . "";

                $colCounter = 0;
                $rowCounter++;

                $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();

                $idxName = "nama";
                $linkHist = base_url() . get_class($this) . "/viewHistories/$ctrlName/" . $rowSpec->id;
                $historyClick = "BootstrapDialog.closeAll();
                    BootstrapDialog.show(
                                   {
                                        title:'$ctrlName change histories ',
                                        message: $('<div></div>').load('" . $linkHist . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:true,
                                        closable:true,
                                        }
                                        );";

                $tmpItem = array();
                foreach ($o->getListedFields() as $ofName => $label) {
                    $fName = $ofName;
                    if (array_key_exists($ofName, $this->relations)) {
                        $fieldLabel = isset($this->relationPairs[$ofName][$rowSpec->$ofName]) ? $this->relationPairs[$ofName][$rowSpec->$ofName] : "-unknown rel-";
                    }
                    else {
                        if (sizeof($arrExtImg) > 0) {
                            $srcKey = $dataExtRel["srcKey"];
                            $selectID = $rowSpec->$srcKey;
                            if (isset($arrExtImg[$selectID])) {
                                $valData = $arrExtImg[$selectID];
                                $img_src = "src='$valData'";
                                $badge = sizeof($badgeData[$selectID]) > 1 ? sizeof($badgeData[$selectID]) : "";
                                $notifBadge = $badge > 1 ? "<span class='notify-badge' style=''>$badge</span>" : "";
                            }
                            else {
                                $valData = base_url() . "public/images/img_blank.gif";
                                $img_src = "src='$valData'";
                                $notifBadge = "";
                            }
                            $fieldsImages = "<div class=''>";
                            $fieldsImages .= "<div class='item'>$notifBadge";
                            $fieldsImages .= "<img $img_src class='img-responsive' width='65px'>";
                            $fieldsImages .= "</div>";
                            $fieldsImages .= "</div>";
                            $tmpItem['images'] = $fieldsImages;
                        }

                        if (isset($fields[$ofName]["transformValue"])) {
                            $function = $fields[$ofName]["transformValue"];
                            $dataValue = strlen($rowSpec->$ofName) > 0 ? $rowSpec->$ofName : "-unregistered-";
                            $listed = "<div class='text-center bottom-borderss' style='margin-bottom: 1px;'>";
                            $listed .= "<svg class='thumbnail' id='r_$selectID' style='width:120px;height:50px;padding: 0px;margin-bottom: 0px;border: none'></svg>";
                            $listed .= "</div>";
                            if (validate_EAN13Barcode($dataValue)) {
                                $listed .= "<script>$function('#r_$selectID', '$dataValue', {format: 'ean13'});</script>";
                            }
                            else {
                                if ($dataValue == "-unregistered-") {
                                    $listed .= "<script>$function('#r_$selectID', '$dataValue', {format: 'code39', lineColor: '#e02907'});</script>";
                                }
                                else {
                                    $listed .= "<script>$function('#r_$selectID', '$dataValue', {format: 'code39'});</script>";
                                }
                            }
                            $fieldLabel = $listed;
                        }
                        else {
                            if (($ofName == "image_ktp") || ($ofName == "image_npwp")) { // harus diganti supaya dinamis, tidak nembak kayak gini...
                                $img_src = "src='" . $rowSpec->$ofName . "'";
                                $arrImg = array(
                                    "title" => $rowSpec->nama,
                                    "body"  => array(
                                        $rowSpec->$ofName,
                                    ),
                                );
                                $modalImage = base_url() . "Katalog/modal/" . str_replace("=", "", blobEncode($arrImg)) . "";

                                $fieldsImages = "<div class=''>";
                                $fieldsImages .= "<div class='item'>";
                                $fieldsImages .= "<a href='" . $modalImage . "' data-toggle=\"modal\" data-target=\"#myModal\">";
                                $fieldsImages .= "<img $img_src class='img-responsive' width='65px'>";
                                $fieldsImages .= "</a>";
                                $fieldsImages .= "</div>";
                                $fieldsImages .= "</div>";

                                $fieldLabel = $fieldsImages;
                            }
                            else {
                                $fieldLabel = isset($rowSpec->$ofName) ? nl2br($rowSpec->$ofName) : "";
                            }
                        }
                    }

                    $tmpItem['action'] = "<span class='btn-block text-center'>";
                    $tmpItem[$ofName] = $fieldLabel;

                    if ($this->allowEdit && $objState != "1") {
                        $addNumber = $colCounter == 0 ? "<a href='JavaScript:void(0)' onclick =\"$historyClick\"><span class='badge' style='background:#c0c0c0;color:#656564;'>$rowCounter</span></a>" : "";
                        $tmpItem['action'] .= "<a class='btn btn-default ' href='JavaScript:void(0)' data-toggle='tooltip' data-placement='left' title='modify this entry' onclick=\"$editClick\"><span class='glyphicon glyphicon-pencil'></span></a>";
                    }
                    $colCounter++;
                }
                if ($this->allowDelete && $objState != "1") {
                    $tmpItem['action'] .= "<button class='btn btn-danger hidden-print' data-toggle='tooltip' data-placement='left' title='delete entry' onClick=\"if(confirm('Remove entry?')==1){location.href='$deleteLink'}\"><span class='glyphicon glyphicon-remove'></button>";
                }
                if ($this->allowViewHistory) {
                    $tmpItem['action'] .= "<a class='btn btn-default' href='JavaScript:void(0)' data-toggle='tooltip' data-placement='left' title='view histories of this entry' onclick=\"$historyClick\"><span class='glyphicon glyphicon-time'></span></a>";
                }

                $tmpItem['action'] .= "</span class='btn-block'>";
                $content .= ($t->addRow($dataRow));
                if (sizeof($dataRel) > 0) {
                    // $optClick = "BootstrapDialog.closeAll();
                    // BootstrapDialog.show(
                    //                {
                    //                     title:'$title options',
                    //                     message: $('<div></div>').load('" . base_url() . get_class($this) . "/showRelOptions/$className/" . $rowSpec->id . "'),
                    //                     size: BootstrapDialog.SIZE_WIDE,
                    //                     draggable:true,
                    //                     closable:true,
                    //                     }
                    //                     );";

                    // $tmpItem['option'] = "<a href='JavaScript:void(0)' onclick=\"$optClick\">" . "<span class='glyphicon glyphicon-option-vertical'></span>" . "</a>";
                }
                $arrayItem[] = $tmpItem;
            }
        }

        if ($this->allowCreate) {
            $addClick = "
                        BootstrapDialog.show(
                           {
                                title:'New $title',
                                message: $('<div></div>').load('" . $addLink . "'),
                                size: BootstrapDialog.SIZE_WIDE,
                                draggable:false,
                                closable:true,
                            }
                        );";
            $strAddLink = "";
            $strAddLink .= "<div class='btn-group'>";
            $strAddLink .= "<button href='JavaScript:void(0)' class=\" btn btn-primary\" onClick=\"$addClick\" data-toggle='tooltip' data-placement='top' title='Add new $title' class='btn btn-circle btn-xs btn-primary bg-blue-gradient'><span class='glyphicon glyphicon-plus'></button>";
            $strAddLink .= in_array("addMany", $limitedEditor) ? "" : "<button href='JavaScript:void(0)' class='btn btn-success' onclick=\"location.href = '$addManyLink';\"  data-toggle='tooltip' data-placement='top' title='Add many entries of $title'><span class='glyphicon glyphicon-plus-sign'></span></button>";
            $strAddLink .= "</div class='btn-group'>";

        }
        else {
            $strAddLink = "";
        }
        if ($this->allowEdit) {
            $strEditLink = in_array("addMany", $limitedEditor) ? "" : "<button href='JavaScript:void(0)' class=\" btn btn-default\" onClick=\"location.href='" . base_url() . get_class($this) . "/editMany/$ctrlName/" . $this->uri->segment(4) . "'\" data-toggle='tooltip' data-placement='top' title='Modify all $title in this page' class='btn btn-circle btn-xs btn-primary bg-blue-gradient'><span class='glyphicon glyphicon-pencil'></button>";
        }
        else {
            $strEditLink = "";
        }

        // arrPrint(get_class_methods($o));
        // arrPrint($this->excelWriters);
        if (method_exists($o, "getExcelWriters")) {
            $className_e = str_replace("=", "", blobEncode($className));
            $xlsLink = base_url() . "ExcelWriter/data/$className_e";
            // onclick=\"location.href='".base_url()."ExcelWriter/data/$className_e'\"
            $strEditLink .= "<button name='download' type='button' class='btn btn-warning'
            
         
         onclick=\"btn_result('$xlsLink');\"
         
        title='export data ke xlsx'><i class='fa fa-download'></i> EXCEL</button>";
        }
        else {
            cekOrange($className);
        }


        $arrayHeader = $this->listedFields;
        if (sizeof($dataExtRel) > 0) {
            $arrayHeader["images"] = "images";
        }
        $arrayHeader["action"] = "action";
        if (sizeof($dataRel) > 0) {
            $arrayHeader["option"] = "<span class='glyphicon glyphicon-th-list'></span>";
        }


        $this->load->model("Mdls/" . "MdlDataHistory");
        $h = new MdlDataHistory();
        $h->addFilter("mdl_name='$className'");
        $tmpH = $h->lookupRecentHistories()->result();

        $arrayRecap = array();
        if (sizeof($tmpH) > 0) {
            $tmpO = new $className();
            foreach ($tmpH as $row) {
                $tmpRecap = array();
                $content = unserialize(base64_decode($row->new_content));

                foreach ($this->listedFields as $fName => $label) {
                    $fieldLabel = isset($content[$fName]) ? $content[$fName] : "";
                    if (array_key_exists($fName, $this->relations)) {
                        $fieldLabel = isset($this->relationPairs[$fName][$fieldLabel]) ? "<span class='fa fa-folder-o'></span> " . $this->relationPairs[$fName][$fieldLabel] : "unknown rel";
                    }
                    else {
                        $fieldLabel = isset($row->$fName) ? $row->$fName : (isset($content[$fName]) ? $content[$fName] : "unknown rel#");
                    }
                    $type_data = isset($fields[$fName]['type']) ? $fields[$fName]['type'] : "varchar";

                    switch ($type_data) {
                        default:
                            $tmpRecap[$fName] = nl2br($fieldLabel);
                            break;
                        case "blob":
                            die(__LINE__);
                            $imageDecode = blobDecode($fieldLabel);
                            $imageAvail = base64_encode($imageDecode['image']);
                            $img_scr = "src='data:image/jpeg;base64,$imageAvail'";
                            $fblob_data = "<div><img $img_scr class='img-responsive' width='150px' ></div>";
                            $tmpRecap[$fName] = $fblob_data;
                            break;
                        case "image":
                            $img_scr = "src='$fieldLabel'";
                            $fblob_data = "<div><img $img_scr class='img-responsive' width='150px' ></div>";
                            $tmpRecap[$fName] = $fblob_data;
                            break;
                    }


                }
                $arrayRecap[] = $tmpRecap;
            }
        }

        $arrayProgressLabel['date'] = "date";
        $arrayProgressLabel['propose_type'] = "proposal type";
        $arrayProgressLabel = $arrayProgressLabel + $arrayHeader;
        $arrayRecapLabel = $arrayHeader;

        $arrayProgressLabel['action'] = "action";

        //        arrPrint($arrayProgressLabel);

        unset($arrayProgressLabel['history']);
        unset($arrayRecapLabel['action']);
        unset($arrayRecapLabel['history']);


        $titleSuffix = createObjectSuffix($realObjName);

        if (isset($this->relationPairs) && array_key_exists("folders", $this->relationPairs)) {
            $folders = array("" => "HOME") + $this->relationPairs['folders'];
            $fmdlName = $this->relations['folders'];
            $fdataAccess = isset($this->config->item('heDataBehaviour')[$fmdlName]) ? $this->config->item('heDataBehaviour')[$fmdlName] : array(
                "viewers"        => array(),
                "creators"       => array(),
                "creatorAdmins"  => array(),
                "updaters"       => array(),
                "updaterAdmins"  => array(),
                "deleters"       => array(),
                "deleterAdmins"  => array(),
                "historyViewers" => array(),
            );

            $allowCreateFolder = false;
            $allowEditFolder = false;
            $allowDeleteFolder = false;
            if (sizeof($mems) > 0 && sizeof($fdataAccess['creators']) > 0) {
                $allowCreateFolder = true;
            }
            if (sizeof($mems) > 0 && sizeof($fdataAccess['updaters']) > 0) {
                $allowEditFolder = true;
            }
            if (sizeof($mems) > 0 && sizeof($fdataAccess['deleters']) > 0) {
                $allowDeleteFolder = true;
            }

            if ($allowCreateFolder) {
                $faddLink = base_url() . get_class($this) . "/add/" . str_replace("Mdl", "", $fmdlName);
            }
            if ($allowEditFolder) {
                $fupdateLink = base_url() . get_class($this) . "/edit/" . str_replace("Mdl", "", $fmdlName) . "/";
            }
            if ($allowDeleteFolder) {
                $fdeleteLink = base_url() . get_class($this) . "/delete/" . str_replace("Mdl", "", $fmdlName) . "/";
            }

        }
        else {
            $folders = array();
        }

        $data = array(
            "mode"                => $this->uri->segment(2),
            "errMsg"              => $this->session->errMsg,
            "title"               => $realObjName . $titleSuffix,
            //            "subTitle" => "Registered $realObjName" . $titleSuffix,
            "subTitle"            => $subtitle,
            "strActiveDataTitle"  => "<span class='glyphicon glyphicon-th-list'></span> List of $title" . $titleSuffix,
            "linkStr"             => isset($params['links']) ? $params['links'] : "",
            "arrayHistoryLabels"  => $arrayHeader,
            "arrayHistory"        => $arrayItem,
            "strDataProposeTitle" => "<span class='glyphicon glyphicon-alert blink'></span>&nbsp; <span class='tebal'>approval needed</span>",
            "arrayProgressLabels" => $arrayProgressLabel,
            "arrayOnProgress"     => $arrItemTmp,
            //            "entities" => $entities,
            "strDataHistTitle"    => "<span class='glyphicon glyphicon-time'></span> recent data updates",
            "arrayRecapLabels"    => $arrayRecapLabel,
            "arrayRecap"          => $arrayRecap,
            "strEditLink"         => $strEditLink,
            "strAddLink"          => $strAddLink,
            "alternateLink"       => $alternateLink,
            "thisPage"            => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "?trashed=$objState",
            "folders"             => $folders,
            "faddLink"            => isset($faddLink) ? $faddLink : "",
            "feditLink"           => isset($fupdateLink) ? $fupdateLink : "",
            "fdeleteLink"         => isset($fdeleteLink) ? $fdeleteLink : "",
            "fmdlName"            => isset($fmdlName) ? $fmdlName : "",
            "fmdlTarget"          => isset($fmdlName) ? base_url() . get_class($this) . "/view/" . str_replace("Mdl", "", $fmdlName) : "",
        );
        $this->load->view('data', $data);
        $this->session->errMsg = "";
    }

    public function viewHistories()
    {
        $content = "";
        $className = "Mdl" . $this->segment_4;
        $ctrlName = $this->segment_4;
        $selectedID = $this->segment_5;
        $this->load->model("Mdls/" . $className);

        $o = new $className();
        $listedFields = $this->listedFields;
        $fields = $o->getFields();

        $p = new Layout("", "", "application/template/lte/index.html");
        $this->load->model("Mdls/" . "MdlDataHistory");
        $h = new MdlDataHistory();
        $h->addFilter("mdl_name='$className'");
        $h->addFilter("orig_id='$selectedID'");
        $this->db->order_by("id", "desc");
        $tmpH = $h->lookupAll()->result();
        // cekBiru($this->db->last_query());
        if (sizeof($tmpH) > 0) {
            // arrPrint($tmpH);
            $content .= ("<div class='table-responsive'>");
            $content .= ("<table class='table table-condensed table-bordered'>");

            //ASLI
            //            $content .= ("<tr bgcolor='#dedede'>");
            //            $content .= ("<td>date</td>");
            //            foreach ($listedFields as $fName => $label) {
            //                $content .= ("<td>");
            //                $content .= ($label);
            //                $content .= ("</td>");
            //            }
            //            $content .= ("<td>person</td>");
            //            $content .= ("</tr>");

            //MODIF to thead

            $content .= ("<thead class='text-uppercase'>");
            $content .= ("<tr bgcolor='#dedede'>");
            $content .= ("<td>no</td>");
            $content .= ("<td>date</td>");
            foreach ($listedFields as $fName => $label) {
                $content .= ("<td title='$fName'>");
                $content .= ($label);
                $content .= ("</td>");
            }
            $content .= ("<td>person</td>");
            $content .= ("</tr>");
            $content .= ("</thead>");
            // arrPrintKuning($listedFields);
            $no = 0;
            foreach ($tmpH as $row) {
                $no++;
                //                arrPrint($row);
                $oldContents_0 = blobDecode($row->old_content);
                $newContents_0 = blobDecode($row->new_content);
                //                arrPrintWebs($newContents);
                $oldContents = (array)$oldContents_0[0];
                $newContents = (array)$newContents_0[0];
                // arrPrintPink($newContents);
                // arrPrintPink($oldContents_0);
                // arrPrintHijau($oldContents);
                $content .= ("<tr>");
                $content .= ("<td>" . $no . "</td>");
                $content .= ("<td>" . $row->dtime . "</td>");
                foreach ($listedFields as $fName => $label) {
                    $type_conten = isset($fields[$fName]['type']) ? $fields[$fName]['type'] : "";
                    $fColName = $fName;
                    switch ($type_conten) {
                        default:
                            $strContent = isset($newContents[$fColName]) ? $newContents[$fColName] : "-";
                            break;
                        case "blob":
                            $existConten = isset($newContents[$fColName]) ? $newContents[$fColName] : "-";
                            $strContent = "<img src='$existConten' class='img-responsive' width='85px'>";
                            break;
                        case "image":
                            $existConten = isset($newContents[$fColName]) ? $newContents[$fColName] : "-";
                            $strContent = "<img src='$existConten' class='img-responsive' width='85px'>";
                            break;
                    }
                    $strOldContent = isset($oldContents[$fColName]) ? $oldContents[$fColName] : "-";
                    $content .= ("<td >");
                    $content .= ($strOldContent);
                    // $content .= ($strOldContent . " $fName");
                    $content .= ("</td>");
                }
                $content .= ("<td>");
                $content .= ($row->oleh_name);
                $content .= ("</td>");
                $content .= ("</tr>");
            }
            $content .= ("</table>");
            $content .= ("</div class='table-responsive'>");
        }
        else {
            $content .= ("<div class='alert alert-warning text-center'>");
            $content .= ("this item has no history entry");
            $content .= ("</div class='alert alert-warning'>");
        }

        $content .= "
        <script>
            var thisTable = $('.modal').find('.table');
            $(thisTable).dataTable();
        </script>";

        echo $content;
    }

    public function doApproveFrom()
    {
        $arrAlert = array(
            "html"              => "<img src='" . base_url() . "public/images/sys/loader-100.gif'> <br>Saving your data, please wait..<br>",
            "showConfirmButton" => false,
            "allowOutsideClick" => false,

        );
        echo swalAlert($arrAlert);
        $content = "";
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
            die();
        }

        $className = "Mdl" . $this->uri->segment(3);
        $dcomConf = isset($this->config->item("dataPostProcessors")[$className]) ? $this->config->item("dataPostProcessors")[$className][0] : array();//cek ada Dcomnya tidak
        $dataExtRel = isset($this->config->item('dataExtRelation')[$className]["images"]) ? $this->config->item('dataExtRelation')[$className]["images"] : array();

        $ctrlName = $this->uri->segment(3);
        $this->load->model("Mdls/" . $className);
        $mainObj = $o = new $className;

        $selectedID = $this->uri->segment(4);
        $origID = $this->uri->segment(5);

        $this->db->trans_start();

        $this->load->model("Mdls/" . "MdlDataTmp");
        $oTmp = new MdlDataTmp();
        $oTmp->addFilter("mdl_name='$className'");
        $oTmp->addFilter("_id='$selectedID'");

        $tmp = $oTmp->lookupAll()->result();

        $tmpContent = unserialize(base64_decode($tmp[0]->content));

        $oTmp->deleteData(array("_id" => $selectedID));

        if (sizeof($dataExtRel) > 0) {
            if (isset($tmpContent['images']) && $tmpContent['images'] != "") {

                $this->load->model("Mdls/MdlImages");
                $i = new MdlImages();
                $insertID = $i->addData(
                    array(
                        'parent_id' => $tmpContent['id'],
                        'jenis'     => ucwords($ctrlName),
                        'files'     => $tmpContent['images'],
                        'status'    => 1
                    )
                );
                unset($tmpContent['images']);
            }
            else {
                unset($tmpContent['images']);
            }
        }

        if ($origID != 0) {//===edit
            $where = array(
                "id" => $origID,
            );
            $tmpOrig = $o->lookupByCondition(array("id" => $origID))->result();
            $o->setFilters(array());
            $o->updateData($where, $tmpContent, $o->getTableName());
            cekMerah($this->db->last_query());
            if (sizeof($dcomConf) > 0) {
                cekmerah("ada post-processors " . __FILE__ . " " . __LINE__);
                $comName = "DCom" . $dcomConf;
                cekmerah("post-proc name:  $comName");
                $this->load->model("DComs/" . $comName);

                $o2 = new $comName();
                $o2->pair($tmpContent) or die(lgShowError($comName, "failed to pair the params of DCom"));
                $o2->exec() or die(lgShowError($comName, "failed to execute DCom"));
            }

            $this->session->errMsg = "Data has been updated";

            $this->load->model("Mdls/" . "MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id"            => $origID,
                "mdl_name"           => $className,
                "mdl_label"          => get_class($this),
                "old_content"        => base64_encode(serialize((array)$tmpOrig)),
                "old_content_intext" => print_r($tmpOrig, true),
                "new_content"        => base64_encode(serialize($tmpContent)),
                "new_content_intext" => print_r($tmpContent, true),
                "label"              => "approved",
                "oleh_id"            => $this->session->login['id'],
                "oleh_name"          => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            cekLime($this->db->last_query());
        }
        else {//===new data
            $tmpContent["status"] = 1;
            $tmpContent["trash"] = 0;
            unset($tmpContent["id"]);
            cekKuning("$className " . $o->getTableName());
            $mainInsertId = $insertID = $o->addData($tmpContent, $o->getTableName());
            cekMerah($this->db->last_query() . " == $insertID");

            if (sizeof($dcomConf) > 0) {
                $inParam = array_merge(array("id" => "$insertID"), $tmpContent);
                $className = "DCom" . $dcomConf;
                $this->load->Model("DComs/" . $className);
                $d = new $className();
                $d->setWriteMode("insert");
                $d->pair($inParam) or die("Tidak berhasil memasang  values pada dcom-processor: $className/" . __FUNCTION__ . "/" . __LINE__);
                $gotParams = $d->exec();
            }
            $this->session->errMsg = "Data has been saved";

            /* ------------------------------------------------
             * menulis data history perubahan
             * ------------------------------------------------*/
            $this->load->model("Mdls/" . "MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id"     => $origID,
                "mdl_name"    => $className,
                "mdl_label"   => get_class($this),
                "old_content" => "",
                "new_content" => base64_encode(serialize($tmpContent)),
                // "new_content_intext" => print_r($tmpContent, true),
                "label"       => "approved",
                "oleh_id"     => $this->session->login['id'],
                "oleh_name"   => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            showLast_query("kuning");

            /* -------------------------------------
             * tested pada auto COA yg pakai aaproval masuk di do sm dg yg di addProcess
             * -------------------------------------*/
            if (method_exists($mainObj, "getConnectingData")) {

                $data = $tmpContent;
                $nama = ucwords($data['nama']);
                $negara = $data['country'];
                $extern_tipe = $negara == "ID" ? "lokal" : "non_lokal";
                $my_name = my_name();

                cekBiru($negara . " $extern_tipe");
                $connectings = $mainObj->getConnectingData();
                foreach ($connectings as $model => $param_connecting) {

                    foreach ($param_connecting as $paramConnecting) {
                        $fields = isset($paramConnecting['fields']) ? $paramConnecting['fields'] : $paramConnecting;
                        $this->load->model($paramConnecting['path'] . "/$model");
                        $connObj = new $model();
                        if (isset($paramConnecting['staticOptions'])) {

                            $strHead_code = is_array($paramConnecting['staticOptions']) ? $paramConnecting['staticOptions'][$extern_tipe] : $paramConnecting['staticOptions'];
                        }
                        else {
                            matiHere("static optionnya tolong dikasih");
                        }

                        $datas = array();

                        foreach ($fields as $field => $cfParams) {

                            if (isset($cfParams['var_main'])) {
                                $cNilai = $$cfParams['var_main'];
                            }
                            else {
                                $cNilai = $cfParams['str'];
                            }

                            $datas[$field] = $cNilai;
                        }

                        arrPrint($datas);
                        // cekLime();
                        /* -------------------------------------------------
                         * menulis ke table connecting
                         * -------------------------------------------------*/
                        $lastInset_code = $connObj->$paramConnecting['fungsi']($strHead_code, $datas);
                        showLast_query("merah");

                        /* -------------------------------------------------
                         * ngupdate ke data utama
                         * -------------------------------------------------*/
                        if (isset($paramConnecting['updateMain'])) {

                            foreach ($paramConnecting['updateMain']['condites'] as $key => $condite) {
                                $mainCondites[$key] = $$condite;
                            }
                            foreach ($paramConnecting['updateMain']['datas'] as $key => $val) {
                                $mainUpdate[$key] = $$val;
                            }

                            $mainObj->updateData($mainCondites, $mainUpdate);
                            showLast_query("orange");
                        }
                    }


                    // cekHitam($lastInset_code);
                }


                // arrPrint($connecting);
            }
        }


        // matiHere("hoop ----DONE---- belom commit :: ". __METHOD__);
        $this->db->trans_complete();
        echo "<script>top.location.reload();</script>";
    }

    public function doRejectFrom()
    {
        $content = "";
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
            die();
        }

        $className = "Mdl" . $this->uri->segment(3);
        $ctrlName = $this->uri->segment(3);
        $this->load->model("Mdls/" . $className);
        $o = new $className;

        //$indexFieldName = $o->getIndexFieldName();$indexFieldName = "id";
        $selectedID = $this->uri->segment(4);
        $origID = $this->uri->segment(5);

        //        die($selectedID."-".$origID);
        $this->db->trans_start();

        $this->load->model("Mdls/" . "MdlDataTmp");
        $oTmp = new MdlDataTmp();
        $oTmp->addFilter("mdl_name='$className'");
        $oTmp->addFilter("_id='$selectedID'");

        $tmp = $oTmp->lookupAll()->result();
        //$tmpContent = unserialize(base64_decode($tmp[0]->content));
        $rejectedContent = unserialize(base64_decode($tmp[0]->content));
        $oTmp->deleteData(array("_id" => $selectedID));
        // print_r($tmpContent);
        // die();
        if ($origID > 0) {//===edit


            //===ambil data sebelumnya
            //            $tmpOrig = $o->lookupByCondition(array(/*$indexFieldName =>*/ "id" => $origID))->result();
            $tmpOrig = $o->lookupByCondition(array("id" => $origID))->result();

            $where = array(
                //                /*$indexFieldName =>*/ "id" => $origID,
                "id" => $origID,
            );
            $tmpContent["status"] = 1;
            $tmpContent["trash"] = 0;
            //            $tmpOrig = $o->lookupByCondition(array(/*$indexFieldName =>*/ "id" => $origID))->result();
            $tmpOrig = $o->lookupByCondition(array("id" => $origID))->result();
            $o->setFilters(array());
            $o->updateData($where, $tmpContent, $o->getTableName());
            $this->session->errMsg = "Data proposal has been rejected dan being reverted back";

            //<editor-fold desc="data history / reject">
            $this->load->model("Mdls/" . "MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id"            => $origID,
                "mdl_name"           => $className,
                "mdl_label"          => get_class($this),
                "old_content"        => base64_encode(serialize((array)$tmpOrig)),
                "old_content_intext" => print_r($tmpOrig, true),
                "new_content"        => base64_encode(serialize($rejectedContent)),
                "new_content_intext" => print_r($rejectedContent, true),
                "label"              => "rejected",
                "oleh_id"            => $this->session->login['id'],
                "oleh_name"          => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            //</editor-fold>

        }
        else {//===new data
            // $tmpContent["status"]=1;
            // $tmpContent["trash"]=0;
            // unset($tmpContent["id"]);
            // $insertID = $o->addData($tmpContent, $o->getTableName()) or die(lgShowError("Gagal menulis data", __FILE__));
            $this->session->errMsg = "Data proposal has been rejected";
            //<editor-fold desc="data history / reject">
            $this->load->model("Mdls/" . "MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id"            => $origID,
                "mdl_name"           => $className,
                "mdl_label"          => get_class($this),
                "old_content"        => "",
                "new_content"        => base64_encode(serialize($rejectedContent)),
                "new_content_intext" => print_r($rejectedContent, true),
                "label"              => "rejected",
                "oleh_id"            => $this->session->login['id'],
                "oleh_name"          => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            //</editor-fold>
        }

        $this->db->trans_complete();
        echo "<script>top.location.reload();</script>";
    }

    public function doApproveDeleteFrom()
    {
        $content = "";
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
            die();
        }

        $className = "Mdl" . $this->uri->segment(3);
        $ctrlName = $this->uri->segment(3);
        $this->load->model("Mdls/" . $className);
        $o = new $className;

        //$indexFieldName = $o->getIndexFieldName();$indexFieldName = "id";
        $selectedID = $this->uri->segment(4);
        $origID = $this->uri->segment(5);

        $this->db->trans_start();

        $this->load->model("Mdls/" . "MdlDataTmp");
        $oTmp = new MdlDataTmp();
        $oTmp->addFilter("mdl_name='$className'");
        $oTmp->addFilter("_id='$selectedID'");

        $tmp = $oTmp->lookupAll()->result();
        $tmpContent = unserialize(base64_decode($tmp[0]->content));
        $oTmp->deleteData(array("_id" => $selectedID));
        // print_r($tmpContent);
        // die();
        if ($origID > 0) {//===edit
            $where = array(
                //                /*$indexFieldName =>*/ "id" => $origID,
                "id" => $origID,
            );
            $tmpContent["status"] = 0;
            $tmpContent["trash"] = 1;
            //            $tmpOrig = $o->lookupByCondition(array(/*$indexFieldName =>*/ "id" => $origID))->result();
            $tmpOrig = $o->lookupByCondition(array("id" => $origID))->result();
            $o->setFilters(array());
            $o->updateData($where, $tmpContent, $o->getTableName());
            $this->session->errMsg = "Data has been deleted";

            //<editor-fold desc="data history / approve">
            $this->load->model("Mdls/" . "MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id"            => $origID,
                "mdl_name"           => $className,
                "mdl_label"          => get_class($this),
                "old_content"        => base64_encode(serialize((array)$tmpOrig)),
                "old_content_intext" => print_r($tmpOrig, true),
                "new_content"        => base64_encode(serialize($tmpContent)),
                "new_content_intext" => print_r($tmpContent, true),
                "label"              => "approved",
                "oleh_id"            => $this->session->login['id'],
                "oleh_name"          => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            //</editor-fold>
        }
        else {//===new data
            die("unable to determine which data to be deleted");
        }

        $this->db->trans_complete();

        $file = fopen(__DIR__ . '/eusvc/sync/' . $className . "_" . $this->session->login['toko_id'] . ".txt", "w");
        echo fwrite($file, json_encode(array("datetime" => date("Y-m-d H:i:s"))));
        fclose($file);

        echo "<script>top.location.reload();</script>";
    }

    public function doRejectDeleteFrom()
    {
        $content = "";
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
            die();
        }

        $className = "Mdl" . $this->uri->segment(3);
        $ctrlName = $this->uri->segment(3);
        $this->load->model("Mdls/" . $className);
        $o = new $className;

        //$indexFieldName = $o->getIndexFieldName();$indexFieldName = "id";
        $selectedID = $this->uri->segment(4);
        $origID = $this->uri->segment(5);

        //        die($selectedID."-".$origID);
        $this->db->trans_start();

        $this->load->model("Mdls/" . "MdlDataTmp");
        $oTmp = new MdlDataTmp();
        $oTmp->addFilter("mdl_name='$className'");
        $oTmp->addFilter("_id='$selectedID'");

        $tmp = $oTmp->lookupAll()->result();
        //$tmpContent = unserialize(base64_decode($tmp[0]->content));
        $rejectedContent = unserialize(base64_decode($tmp[0]->content));
        $oTmp->deleteData(array("_id" => $selectedID));
        // print_r($tmpContent);
        // die();
        if ($origID > 0) {//===edit


            //===ambil data sebelumnya
            //            $tmpOrig = $o->lookupByCondition(array(/*$indexFieldName =>*/ "id" => $origID))->result();
            $tmpOrig = $o->lookupByCondition(array("id" => $origID))->result();

            $where = array(
                //                /*$indexFieldName =>*/ "id" => $origID,
                "id" => $origID,
            );
            $tmpContent["status"] = 1;
            $tmpContent["trash"] = 0;
            //            $tmpOrig = $o->lookupByCondition(array(/*$indexFieldName =>*/ "id" => $origID))->result();
            $tmpOrig = $o->lookupByCondition(array("id" => $origID))->result();
            $o->setFilters(array());
            $o->updateData($where, $tmpContent, $o->getTableName());
            $this->session->errMsg = "Data proposal has been rejected dan being reverted back";

            //<editor-fold desc="data history / reject">
            $this->load->model("Mdls/" . "MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id"            => $origID,
                "mdl_name"           => $className,
                "mdl_label"          => get_class($this),
                "old_content"        => base64_encode(serialize((array)$tmpOrig)),
                "old_content_intext" => print_r($tmpOrig, true),
                "new_content"        => base64_encode(serialize($rejectedContent)),
                "new_content_intext" => print_r($rejectedContent, true),
                "label"              => "rejected",
                "oleh_id"            => $this->session->login['id'],
                "oleh_name"          => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            //</editor-fold>

        }
        else {//===new data
            // $tmpContent["status"]=1;
            // $tmpContent["trash"]=0;
            // unset($tmpContent["id"]);
            // $insertID = $o->addData($tmpContent, $o->getTableName()) or die(lgShowError("Gagal menulis data", __FILE__));
            $this->session->errMsg = "Data proposal has been rejected";
            //<editor-fold desc="data history / reject">
            $this->load->model("Mdls/" . "MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id"            => $origID,
                "mdl_name"           => $className,
                "mdl_label"          => get_class($this),
                "old_content"        => "",
                "new_content"        => base64_encode(serialize($rejectedContent)),
                "new_content_intext" => print_r($rejectedContent, true),
                "label"              => "rejected",
                "oleh_id"            => $this->session->login['id'],
                "oleh_name"          => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            //</editor-fold>
        }

        $this->db->trans_complete();
        echo "<script>top.location.reload();</script>";
    }

    public function showRelOptions()
    {
        //==required: model-name, model-ID
        $mdlName = $this->uri->segment(3);
        $id = $this->uri->segment(4);
        $dataRel = isset($this->config->item('dataRelation')[$mdlName]) ? $this->config->item('dataRelation')[$mdlName] : array();
        // arrPrint($dataRel);
        // mati_disini(__LINE__ . "Mdl".$mdlName);
        $content = "";
        if (sizeof($dataRel) > 0) {
            $content .= "<ul class='list-group'>";
            foreach ($dataRel as $tMdlName => $tSpec) {
                $content .= "<li class='list-group-item'>";
                $targetUrl = base_url() . "Data/view/" . str_replace("Mdl", "", $tMdlName) . "?reqField=" . $tSpec['targetField'] . "&reqVal=$id";
                $content .= "<a href='$targetUrl'>";
                $content .= $tSpec['label'];
                $content .= "</a>";
                $content .= "</li class='list-group-item'>";
            }
            $content .= "</ul class='list-group'>";
        }
        echo $content;
    }

    public function addMany()
    {
        //        arrPrint($this->uri->segment_array());
        $content = "";
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
            die();
        }
        $className = "Mdl" . $this->uri->segment(3);
        $ctrlName = $this->uri->segment(3);

        $this->load->model("Mdls/" . $className);
        $o = new $className;
        $indexFieldName = "id";
        $fields = $o->getFields();
        $validRules = $o->getValidationRules();
        //arrPrint($fields);
        $content .= "<table class='table table-condensed no-padding'>";
        $refModels = array();
        $refOptions = array();
        if (sizeof($fields) > 0) {
            $content .= "<tr>";
            foreach ($fields as $fieldName => $fieldSpec) {
                if ($fieldSpec['inputType'] != "hidden") {

                    if (array_key_exists($fieldSpec['kolom'], $validRules)) {
                        $suffix = "*";
                        $fStyle = "font-weight:bold;";
                    }
                    else {
                        $suffix = "";
                        $fStyle = "";
                    }
                    if ($fieldSpec['inputType'] == "hidden_ref") {

                    }
                    else {
                        $content .= "<th style='$fStyle' align='center'>";
                        $content .= str_replace(" ", "&nbsp;", $fieldSpec['label']) . $suffix;
                        $content .= "</th>";
                    }

                }

                if (isset($fieldSpec['reference'])) {
                    $tmpModelName = $fieldSpec['reference'];
                    //                    cekHijau(APPPATH."models/$tmpModelName.php");
                    if (file_exists(APPPATH . "models/Mdls/$tmpModelName.php")) {
                        $this->load->model("Mdls/" . $tmpModelName);
                        $o2 = new $tmpModelName();
                        $fields2 = $o2->getFields();
                        $tmp3 = $o2->lookupAll()->result();
                        //cekBiru($this->db->last_query());
                        if (!in_array($tmpModelName, $refModels)) {
                            $refModels[] = $tmpModelName;
                        }
                        //                        arrPrint($refModels);
                        if (sizeof($tmp3) > 0) {
                            $refOptions[$tmpModelName][''] = "- select -";
                            foreach ($tmp3 as $row3) {
                                $id = isset($row3->id) ? $row3->id : 0;
                                $name = isset($row3->nama) ? $row3->nama : "";
                                if (isset($row3->name)) {
                                    $name = $row3->name;
                                }
                                $refOptions[$tmpModelName][$id] = $name;
                            }
                        }
                    }

                }
            }
            $content .= "</tr>";
            $iCtr = 0;
            for ($i = 0; $i <= 10; $i++) {
                $iCtr++;
                $content .= "<tr>";
                $jCtr = 0;
                foreach ($fields as $fieldName => $fieldSpec) {
                    //arrPrint($fieldSpec);
                    if ($fieldSpec['inputType'] != "hidden") {
                        $jCtr++;
                        if ($jCtr == 1) {
                            $content .= "<input type='hidden' name='ctr[]' value='$iCtr'>";
                        }
                        if ($fieldSpec['inputType'] == "hidden_ref") {
                            if (isset($fieldSpec['reference'])) {
                                $className = $fieldSpec['reference'];
                                $this->load->model("Mdls/" . $className);
                                $o2 = new $className;
                                if (isset($fieldSpec['referenceFilter']) && sizeof($fieldSpec['referenceFilter']) > 0) {
                                    $aFilter = $fieldSpec['referenceFilter'];
                                    $o2 = makeFilter($aFilter, $this->session->login, $o2);
                                }
                                $dataSource = $o2->lookupAll()->result();
                                // cekHitam($this->db->last_query());
                                $defaultValue = $dataSource[0]->$fieldSpec['referenceSrc'];
                                // matiHEre($defaultValue);
                                $content .= "<input type='hidden' name='" . $fieldSpec['kolom'] . "[]' id='" . $fieldSpec['kolom'] . "_" . $iCtr . "' value='$defaultValue'>";
                            }

                        }
                        else {
                            $content .= "<td style='padding:0px;margin:0px;'>";
                            switch ($fieldSpec['inputType']) {
                                case "text":
                                    $content .= "<input type=text class='form-control' placeholder='" . $fieldSpec['label'] . "' name='" . $fieldSpec['kolom'] . "[]' id='" . $fieldSpec['kolom'] . "_" . $iCtr . "'>";
                                    break;
                                case "number":
                                    $content .= "<input type=number class='form-control' placeholder='" . $fieldSpec['label'] . "' name='" . $fieldSpec['kolom'] . "[]' id='" . $fieldSpec['kolom'] . "_" . $iCtr . "'>";
                                    break;
                                case "password":
                                    $content .= "<input type=password class='form-control' placeholder='" . $fieldSpec['label'] . "' name='" . $fieldSpec['kolom'] . "[]' id='" . $fieldSpec['kolom'] . "_" . $iCtr . "'>";
                                    break;
                                case "combo":
                                    if (isset($fieldSpec['dataSource']) || isset($fieldSpec['reference'])) {
                                        $content .= "<select class='form-control' name='" . $fieldSpec['kolom'] . "[]' id='" . $fieldSpec['kolom'] . "_" . $iCtr . "'>";
                                        if (isset($fieldSpec['dataSource'])) {
                                            foreach ($fieldSpec['dataSource'] as $key => $val) {
                                                $selected = isset($fieldSpec['defaultValue']) && $key == $fieldSpec['defaultValue'] ? "selected" : "";
                                                $content .= "<option value='$key' $selected>$val</option>";
                                            }
                                        }
                                        if (isset($fieldSpec['reference'])) {
                                            $tmpMdlName = $fieldSpec['reference'];
                                            //arrPrint($tmpMdlName);
                                            if (isset($refOptions[$tmpMdlName]) && sizeof($refOptions[$tmpMdlName]) > 0) {
                                                foreach ($refOptions[$tmpMdlName] as $key => $val) {

                                                    $content .= "<option value='$key'>$val</option>";
                                                    //                                                    $content .= "<input name='$key' type='hidden' value='$key[$val]'></input>";

                                                }
                                            }
                                        }
                                        $content .= "</select class='form-control'>";
                                    }
                                    else {
                                        $content .= "<input type=password class='form-control' disabled>";
                                    }
                                    break;
                                case "file":

                                    //                                    $imageAvail = base_url()."public/images/img_blank.gif";
                                    //                                    $img_scr = "src='$imageAvail'";

                                    $length = isset($fieldSpec['length']) ? $fieldSpec['length'] : "8";
                                    //                                $content .= "<div class='thumbnail'>";
                                    $content .= "<img  class='img-responsive' width='85px'>";
                                    $content .= "<div class='caption'>";
                                    $content .= "<input id='input-1a' type='" . "file" . "'  maxlength='" . $length . "' name='$fieldName' id='_$fieldName' placeholder='" . $fieldSpec['label'] . "'  class='form-control' autocomplete='off' data-show-preview='TRUE'  multiple data-show-upload='false'>";
                                    //                                $content .="</div>";
                                    $content .= "</div>";
                                    break;
                                case "image":

                                    $length = isset($fieldSpec['length']) ? $fieldSpec['length'] : "8";
                                    $content .= "<img class='img-responsive' width='85px'>";
                                    //                                $content .= "<div class='caption'>";
                                    //                                $content .= "<input id='input-1a' type='" . "file" . "'  maxlength='" . $length . "' name='$fieldName' id='_$fieldName' placeholder='" . $fieldSpec['label'] . "'  class='form-control' autocomplete='off' data-show-preview='TRUE'  multiple data-show-upload='false'>";
                                    //                                $content .= "</div>";
                                    break;
                                default:
                                    $content .= "-unknown-";
                                    break;
                            }
                        }

                        $content .= "</td>";
                    }

                }
                $content .= "</tr>";
            }
        }
        else {
            die("Fields required as a data-model primary property");
        }

        $content .= "</table class='table table-condensed'>";
        $title = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : $ctrlName;

        $data = array(
            //            "mode" => $this->uri->segment(2),
            "mode"         => "addMany",
            "errMsg"       => $this->session->errMsg,
            "title"        => "Add $title",
            "subTitle"     => "Add $title",
            "historyTitle" => "<span class='glyphicon glyphicon-th-list'></span> List of $title",
            "content"      => $content,
            "thisPage"     => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "?",
            "formTarget"   => base_url() . get_class($this) . "/doAddMany/" . $this->uri->segment(3),
        );
        $this->load->view('data', $data);
        $this->session->errMsg = "";

    }

    public function doAddMany_()
    {
        $className = "Mdl" . $this->uri->segment(3);
        $ctrlName = $this->uri->segment(3);

        $this->load->model("Mdls/" . $className);
        $o = new $className;
        $indexFieldName = "id";
        $fields = $o->getFields();
        $validRules = $o->getValidationRules();

        //        $inputTypeWhitelist = array("combo", "radio");
        $inputTypeWhitelist = array();
        $validRows = array();
        $inValidRows = array();
        if (isset($_POST['ctr']) && sizeof($_POST['ctr']) > 0) {
            $this->db->trans_start();
            foreach ($_POST['ctr'] as $ctr => $ctrx) {
                $filledCols = array();
                foreach ($fields as $fieldName => $fieldSpec) {
                    //                    cekHitam($fieldSpec['inputType']);
                    if (!in_array($fieldSpec['inputType'], $inputTypeWhitelist)) {
                        $inputName = $fieldSpec['kolom'];
                        if (isset($_POST[$inputName][$ctr]) && strlen($_POST[$inputName][$ctr]) > 0) {
                            cekKuning($inputName . " ada, yaitu " . $_POST[$inputName][$ctr]);
                            $filledCols[] = $inputName;
                        }
                    }

                }
                arrPrint($filledCols);

                if (sizeof($filledCols) > 0) {
                    $diisi = true;
                }
                else {
                    $diisi = false;
                }
                if ($diisi) {//==barulah divalidasi
                    cekHijau("$ctr diisi");
                    $valResult = $this->lineValidate($o, $ctr);
                    if (is_array($valResult)) {
                        arrPrint($valResult);
                        cekMerah("$ctr TIDAK VALID");
                        $inValidRows[] = $ctr;

                        echo "<script>";
                        foreach ($valResult as $f => $fff) {
                            echo "top.document.getElementById('$f" . "_" . "$ctrx').style.backgroundColor='#ffff00';";
                        }
                        echo "</script>";

                    }
                    else {
                        cekHijau("$ctr VALID");
                        $validRows[] = $ctr;
                        $data = array();
                        foreach ($fields as $fieldName => $fieldSpec) {
                            echo "<script>";
                            //                            foreach ($valResult as $f => $fff) {
                            //                                echo "top.document.getElementById('$f" . "_" . "$ctrx').style.backgroundColor='transparent';";
                            //                            }
                            echo "</script>";
                            $inputName = $fieldSpec['kolom'];
                            if (isset($_POST[$inputName][$ctr])) {
                                $data[$inputName] = $_POST[$inputName][$ctr];
                            }

                            //                            cekHijau("$f berisi ".$_POST[$inputName][$ctr]);
                        }

                        //                        arrPrint($data);die();
                        if ($this->creatorUsingApproval) {


                            $this->load->model("Mdls/" . "MdlDataTmp");
                            $dTmp = new MdlDataTmp();
                            $tmpData = array(
                                "mdl_name"         => $className,
                                "mdl_label"        => $ctrlName,
                                "proposed_by"      => $this->session->login['id'],
                                "proposed_by_name" => $this->session->login['nama'],
                                "proposed_date"    => date("Y-m-d H:i:s"),
                                "content"          => base64_encode(serialize($data)),
                            );

                            $insertID = $dTmp->addData($tmpData, $dTmp->getTableName()) or die(lgShowError("Gagal menulis pengajuan data", __FILE__));
                            $this->session->errMsg = "Data proposal has been saved and pending approval";

                            //<editor-fold desc="data history / propose">
                            $this->load->model("Mdls/" . "MdlDataHistory");
                            $hTmp = new MdlDataHistory();
                            $tmpHData = array(
                                "orig_id"            => 0,
                                "mdl_name"           => $className,
                                "mdl_label"          => get_class($this),
                                "old_content"        => "",
                                "new_content"        => base64_encode(serialize($data)),
                                "new_content_intext" => print_r($data, true),
                                "label"              => "proposed",
                                "oleh_id"            => $this->session->login['id'],
                                "oleh_name"          => $this->session->login['nama'],
                            );
                            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
                            //</editor-fold>

                        }
                        else {


                            $insertID = $o->addData($data, $o->getTableName()) or die(lgShowError("Gagal menulis data", __FILE__));
                            $this->session->errMsg = "Data contents have been saved";
                            cekHijau($this->db->last_query());

                            $updateLink = base_url() . get_class($this) . "/edit/$ctrlName/" . $insertID . "";
                            $editClick = "BootstrapDialog.show(
                                   {
                                        title:'Modify $ctrlName ',
//                                        size: BootstrapDialog.SIZE_WIDE,
                                        cssClass: 'edit-dialog',
                                        message: $('<div></div>').load('" . $updateLink . "'),
                                        draggable:false,
                                        closable:true,
                                        });";

                            $this->session->errMsg .= "<br><a href='JavaScript:void(0)' onclick=\"$editClick\">view entry</a>";


                            //<editor-fold desc="data history / commited">
                            $this->load->model("Mdls/" . "MdlDataHistory");
                            $hTmp = new MdlDataHistory();
                            $tmpHData = array(
                                "orig_id"            => 0,
                                "mdl_name"           => $className,
                                "mdl_label"          => get_class($this),
                                "old_content"        => "",
                                "new_content"        => base64_encode(serialize($data)),
                                "new_content_intext" => print_r($data, true),
                                "label"              => "applied",
                                "oleh_id"            => $this->session->login['id'],
                                "oleh_name"          => $this->session->login['nama'],
                            );
                            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
                            //</editor-fold>
                        }


                    }
                }
                else {
                    cekMerah("$ctr tidak diisi");

                }
            }
            //            arrPrint($inValidRows);
            //mati_disini();
            if (sizeof($inValidRows) > 0) {
                echo "<script>";
                echo "top.document.getElementById('btnSave').disabled=false;";
                echo "</script>";
            }
            else {
                $this->db->trans_complete();
                echo "<script>";
                echo "top.location.reload();";
                echo "</script>";
            }

        }
        else {
            die("items required");
        }
    }

    public function doAddMany()
    {

        $className = "Mdl" . $this->uri->segment(3);
        $ctrlName = $this->uri->segment(3);

        $this->load->model("Mdls/" . $className);
        $mainObj = $o = new $className;
        $indexFieldName = "id";
        $fields = $o->getFields();
        $validRules = $o->getValidationRules();

        //         arrPrint($_POST);
        //         matiHEre();
        //region balik id satuan
        if (isset($_POST["satuan"])) {
            $this->load->model("Mdls/MdlSatuan");
            $sa = new MdlSatuan();
            $spectData = $sa->lookupAll()->result();
            $arraySatuan = array();
            foreach ($spectData as $spectData_0) {
                $arraySatuan[$spectData_0->id] = $spectData_0->nama;
            }
        }
        //endregion

        //endregion
        //        $inputTypeWhitelist = array("combo", "radio");
        $inputTypeWhitelist = array("radio", "combo", "checkbox", "password");
        $validRows = array();
        $inValidRows = array();
        if (isset($_POST['ctr']) && sizeof($_POST['ctr']) > 0) {
            $this->db->trans_start();
            foreach ($_POST['ctr'] as $ctr => $ctrx) {
                $filledCols = array();
                foreach ($fields as $fieldName => $fieldSpec) {
                    cekHitam($fieldName);
                    //                    cekHitam($fieldSpec['inputType']);
                    cekHijau($fieldSpec);
                    if (!in_array($fieldSpec['inputType'], $inputTypeWhitelist)) {
                        $inputName = $fieldSpec['kolom'];
                        if (isset($_POST[$inputName][$ctr]) && strlen($_POST[$inputName][$ctr]) > 0) {
                            //                            cekKuning($inputName . " ada, yaitu " . $_POST[$inputName][$ctr]);
                            $filledCols[] = $inputName;
                        }
                    }
                }
                cekkuning("$ctr diisi: ");
                //                arrPrint($filledCols);

                if (sizeof($filledCols) > 0) {
                    $diisi = true;
                }
                else {
                    $diisi = false;
                }

                if ($diisi) {//==barulah divalidasi
                    //                    cekHijau("$ctr diisi");
                    $valResult = $this->lineValidate($o, $ctr);
                    if (is_array($valResult)) {
                        //                        arrPrint($valResult);
                        cekMerah("$ctr TIDAK VALID, jadi TIDAK MENULIS");
                        $inValidRows[] = $ctr;

                        echo "<script>";
                        foreach ($valResult as $f => $fff) {
                            echo "top.document.getElementById('$f" . "_" . "$ctrx').style.backgroundColor='#ffff00';";
                        }
                        echo "</script>";

                    }
                    else {
                        //                        cekHijau("$ctr VALID");
                        //                        arrPrint($_POST);
                        $validRows[] = $ctr;
                        $data = array();
                        foreach ($fields as $fieldName => $fieldSpec) {
                            echo "<script>";
                            //                            foreach ($valResult as $f => $fff) {
                            //                                echo "top.document.getElementById('$f" . "_" . "$ctrx').style.backgroundColor='transparent';";
                            //                            }
                            echo "</script>";

                            $inputName = $fieldSpec['kolom'];

                            if ($inputName == "satuan") {
                                $index = $_POST["satuan"][$ctr];
                                $_POST[$inputName][$ctr] = $arraySatuan[$index];
                                //                                $val = $arraySatuan[];
                                //                                $_POST["satuan"] = array("$ctr"=>$val);
                            }
                            //                            arrPrint($_POST);
                            //                            die();
                            if (isset($_POST[$inputName][$ctr])) {
                                $data[$inputName] = $_POST[$inputName][$ctr];
                            }

                            //                            cekHijau("$f berisi ".$_POST[$inputName][$ctr]);
                        }

                        //                                                arrPrint($data);die();
                        if ($this->creatorUsingApproval) {


                            $this->load->model("Mdls/" . "MdlDataTmp");
                            $dTmp = new MdlDataTmp();
                            $tmpData = array(
                                "mdl_name"         => $className,
                                "mdl_label"        => $ctrlName,
                                "proposed_by"      => $this->session->login['id'],
                                "proposed_by_name" => $this->session->login['nama'],
                                "proposed_date"    => date("Y-m-d H:i:s"),
                                "content"          => base64_encode(serialize($data)),
                            );

                            $insertID = $dTmp->addData($tmpData, $dTmp->getTableName()) or die(lgShowError("Gagal menulis pengajuan data", __FILE__));
                            $this->session->errMsg = "Data proposal has been saved and pending approval";

                            //<editor-fold desc="data history / propose">
                            $this->load->model("Mdls/" . "MdlDataHistory");
                            $hTmp = new MdlDataHistory();
                            $tmpHData = array(
                                "orig_id"            => 0,
                                "mdl_name"           => $className,
                                "mdl_label"          => get_class($this),
                                "old_content"        => "",
                                "new_content"        => base64_encode(serialize($data)),
                                "new_content_intext" => print_r($data, true),
                                "label"              => "proposed",
                                "oleh_id"            => $this->session->login['id'],
                                "oleh_name"          => $this->session->login['nama'],
                            );
                            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
                            //</editor-fold>

                        }
                        else {


                            $mainInsertId = $insertID = $o->addData($data, $o->getTableName()) or die(lgShowError("Gagal menulis data", __FILE__));
                            //                            cekHijau($this->db->last_query());
                            $this->session->errMsg = "Data contents have been saved";


                            /* -------------------------------------
                             * tested pada auto COA yg pakai aaproval masuk di do
                             * -------------------------------------*/
                            if (method_exists($mainObj, "getConnectingData")) {
                                $nama = ucwords($data['nama']);
                                $negara = isset($data['country']) ? $data['country'] : "";
                                $extern_tipe = $negara == "ID" ? "lokal" : "non_lokal";
                                $my_name = my_name();
                                // cekBiru($negara . " $extern_tipe");
                                // cekHEre($toko_id);
                                $connectings = $mainObj->getConnectingData();
                                //                                arrPrintWebs($connectings);
                                foreach ($connectings as $model => $param_connecting) {
                                    foreach ($param_connecting as $ii => $paramConnecting) {
                                        //                                        cekKuning($ii);
                                        //                                        arrPrint($paramConnecting);
                                        $fields = isset($paramConnecting['fields']) ? $paramConnecting['fields'] : $paramConnecting;
                                        //                                        $this->load->model($paramConnecting['path'] . "/$model");
                                        $this->load->model($paramConnecting['path'] . "/$model");
                                        $connObj = new $model();
                                        // $strHead_code = isset($param_connecting['staticOptions'][$extern_tipe]) ? $param_connecting['staticOptions'][$extern_tipe] : matiHere("parameter");


                                        if (isset($paramConnecting['staticOptions'])) {
                                            $strHead_code = is_array($paramConnecting['staticOptions']) ? $paramConnecting['staticOptions'][$extern_tipe] : $paramConnecting['staticOptions'];
                                        }
                                        else {
                                            matiHere("static optionnya tolong dikasih");
                                        }
                                        $datas = array();

                                        foreach ($fields as $field => $cfParams) {
                                            //                                            cekHere(": $field : ");
                                            //                                            cekHere($cfParams);

                                            if (isset($cfParams['var_main'])) {
                                                $cNilai = $$cfParams['var_main'];
                                                cekHitam($cNilai);

                                            }
                                            else {
                                                $cNilai = $cfParams['str'];
                                            }

                                            $datas[$field] = $cNilai;
                                        }


                                        $lastInset_code = $connObj->$paramConnecting['fungsi']($strHead_code, my_toko_id(), $datas);
                                        showLast_query("merah");

                                        /* -------------------------------------------------
                                         * ngupdate ke data utama
                                         * -------------------------------------------------*/
                                        if (isset($param_connecting['updateMain'])) {

                                            foreach ($param_connecting['updateMain']['condites'] as $key => $condite) {
                                                $mainCondites[$key] = $$condite;
                                            }
                                            foreach ($param_connecting['updateMain']['datas'] as $key => $val) {
                                                $mainUpdate[$key] = $$val;
                                            }

                                            $mainObj->updateData($mainCondites, $mainUpdate);
                                            showLast_query("orange");
                                        }
                                    }
                                    // matiHere();
                                    // cekHitam($lastInset_code);
                                }
                                // arrPrint($connecting);
                            }


                            $updateLink = base_url() . get_class($this) . "/edit/$ctrlName/" . $insertID . "";
                            $editClick = "BootstrapDialog.show(
                                   {
                                        title:'Modify $ctrlName ',
//                                        size: BootstrapDialog.SIZE_WIDE,
                                        cssClass: 'edit-dialog',
                                        message: $('<div></div>').load('" . $updateLink . "'),
                                        draggable:false,
                                        closable:true,
                                        });";

                            $this->session->errMsg .= "<br><a href='JavaScript:void(0)' onclick=\"$editClick\">view entry</a>";


                            //<editor-fold desc="data history / commited">
                            $this->load->model("Mdls/" . "MdlDataHistory");
                            $hTmp = new MdlDataHistory();
                            $tmpHData = array(
                                "orig_id"            => 0,
                                "mdl_name"           => $className,
                                "mdl_label"          => get_class($this),
                                "old_content"        => "",
                                "new_content"        => base64_encode(serialize($data)),
                                "new_content_intext" => print_r($data, true),
                                "label"              => "applied",
                                "oleh_id"            => $this->session->login['id'],
                                "oleh_name"          => $this->session->login['nama'],
                            );
                            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
                            //</editor-fold>
                            cekmerah("done saving");
                        }


                    }
                }
                else {
                    cekMerah("$ctr tidak diisi");

                }
            }
            //             arrPrint($inValidRows);
            //             arrPrint($validRows);
            //            mati_disini();

            if (sizeof($validRows) > 0 || sizeof($inValidRows) > 0) {
                $inValidRows = array();
                if (sizeof($inValidRows) > 0) {
                    // arrprint($inValidRows);
                    // cekmerah("ada yang invalid");
                    //                echo lgShowAlert("you must fill in at least one line of entry");
                    echo "<script>";
                    //                    echo ("alert('you must fill in at least one line of entry)");
                    echo "top.document.getElementById('btnSave').disabled=false;";
                    echo "</script>";
                }
                else {
                    cekmerah("LANCAAR");
                    //                     matiHere();
                    $this->db->trans_complete();
                    echo "<script>";
                    echo "top.location.reload();";
                    echo "</script>";
                }
            }
            else {

                echo "<script>";
                echo "top.document.getElementById('btnSave').disabled=false;";
                echo "</script>";
                echo lgShowAlert("you must fill in at least one line of entry");
                die();
            }


        }
        else {
            die("items required");
        }
    }

    private function lineValidate($o, $lineNumber, $mode = "add")
    {
        $invalidCounter = 0;
        $valResults = array();
        if (count($o->getValidationRules()) > 0) {
            //==do some validation
            foreach ($o->getFields() as $fieldName => $spec) {
                $fName = isset($spec['kolom']) ? $spec['kolom'] : $fieldName;

                if (!in_array($spec['inputType'], array("radio", "combo", "checkbox", "password"))) {
                    cekhitam("$fieldName to be validated");;
                    if (array_key_exists($fName, $o->getValidationRules())) {
                        //echo "$fName to be validated.<br>";
                        // <editor-fold defaultstate="collapsed" desc="validasi kolom wajib/required">
                        if (in_array("required", $o->getValidationRules()[$fName])) {
                            if (isset($spec['dataParams'])) {
                                foreach ($spec['dataParams'] as $param) {
                                    if (strlen($o->input->post($fName . "_" . $param)) < 1) {
                                        //echo "$fName can not be empty!<br>";
                                        $invalidCounter++;
                                        $valResults[$fName . "_" . $param] = array(
                                            "fieldName"  => $fName . "_" . $param,
                                            "fieldLabel" => $spec['label'] . " " . $param,
                                            "errMsg"     => $spec['label'] . " " . $param . " can not be empty!",
                                        );
                                    }
                                }
                            }
                            else {
                                if (strlen($o->input->post($fName)[$lineNumber]) < 1) {
                                    //echo "$fName can not be empty!<br>";
                                    $invalidCounter++;
                                    $valResults[$fName] = array(
                                        "fieldName"  => $fName,
                                        "fieldLabel" => $spec['label'],
                                        "errMsg"     => $spec['label'] . " can not be empty!",
                                    );
                                }
                            }
                        }
                        // </editor-fold>
                        // <editor-fold defaultstate="collapsed" desc="validasi numbers only">
                        if (in_array("numberOnly", $o->getValidationRules()[$fName])) {
                            if (isset($spec['dataParams'])) {
                                foreach ($spec['dataParams'] as $param) {
                                    if (!is_numeric($o->input->post($fName . "_" . $param))) {
                                        //echo "$fName can not be empty!<br>";
                                        $invalidCounter++;
                                        $valResults[$fName . "_" . $param] = array(
                                            "fieldName"  => $fName . "_" . $param,
                                            "fieldLabel" => $spec['label'] . " " . $param,
                                            "errMsg"     => $spec['label'] . " " . $param . " only accept numbers!",
                                        );
                                    }
                                }
                            }
                            else {
                                if (!is_numeric($o->input->post($fName)[$lineNumber])) {
                                    //echo "$fName can not be empty!<br>";
                                    $invalidCounter++;
                                    $valResults[$fName] = array(
                                        "fieldName"  => $fName,
                                        "fieldLabel" => $spec['label'],
                                        "errMsg"     => $spec['label'] . " only accept numbers!",
                                    );
                                }
                            }
                        }
                        // </editor-fold>
                        // <editor-fold defaultstate="collapsed" desc="validasi unique">
                        if (in_array("unique", $o->getValidationRules()[$fName])) {

                            //$tmpEvalQuery = $o->getByCondition(array($fName => $o->input->post($fName)[$lineNumber]))->result();
                            if ($mode == "edit") {
                                $o->addFilter("id<>'" . $o->input->post("id")[$lineNumber] . "'");
                            }
                            $o->addFilter($fName . "='" . $o->input->post($fName)[$lineNumber] . "'");
                            $tmpEvalQuery = $o->lookupAll()->result();
                            //==validasi unique hanya dikenakan pada penambahan data


                            if ($this->mode == "addProcess") {
                                //if ($tmpEvalQuery > 0) {
                                if (sizeof($tmpEvalQuery) > 0) {

                                    //echo "entri sudah ada <br>";
                                    $invalidCounter++;
                                    $valResults[$fName] = array(
                                        "fieldName"  => $fName,
                                        "fieldLabel" => $spec['label'],
                                        "errMsg"     => "  " . $o->input->post($fName)[$lineNumber] . " sudah terdaftar!",
                                    );
                                }
                            }
                        }// </editor-fold>


                        if (in_array("alphanumeric", $o->getValidationRules()[$fName])) {
                            if (isset($spec['dataParams'])) {
                                foreach ($spec['dataParams'] as $param) {
                                    if (!preg_match("/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/", $o->input->post($fName . "_" . $param))) {
                                        //echo "$fName can not be empty!<br>";
                                        $invalidCounter++;
                                        $valResults[$fName . "_" . $param] = array(
                                            "fieldName"  => $fName . "_" . $param,
                                            "fieldLabel" => $spec['label'] . " " . $param,
                                            "errMsg"     => $spec['label'] . " " . $param . " only alphanumeric accepted and must be started with letter!",
                                        );
                                    }
                                }
                            }
                            else {
                                if (!preg_match("/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/", $o->input->post($fName)[$lineNumber])) {
                                    //echo "$fName can not be empty!<br>";
                                    $invalidCounter++;
                                    $valResults[$fName] = array(
                                        "fieldName"  => $fName,
                                        "fieldLabel" => $spec['label'],
                                        "errMsg"     => $spec['label'] . " only alphanumeric accepted and must be started with letter!",
                                    );
                                }
                            }
                        }
                        //preg_match("/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/", $str);
                    }
                }
                else {
                    cekbiru("skipped validating $fieldName");;
                }


            }
            if ($invalidCounter > 0) {//==ada yang tidak valid===
                return $valResults;
            }
            else {
                return true;
            }
        }
        else {
            //die("Nothing to validate");
            return true;
        }
    }

    public function editMany_ori()
    {
        //        echo "9999";
        $content = "";
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
            die();
        }
        if (!$this->allowEdit) {
            die("Sorry, you are now allowed to modifiy any of these data entries");
        }
        $className = "Mdl" . $this->uri->segment(3);
        $ctrlName = $this->uri->segment(3);
        $dataRel = isset($this->config->item('dataRelation')[$className]) ? $this->config->item('dataRelation')[$className] : array();

        $realObjName = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : get_class($this);
        $title = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : get_class($this);

        //<editor-fold desc="data proposal data">
        $this->load->model("Mdls/" . "MdlDataTmp");
        $tData = new MdlDataTmp();
        $tData->addFilter("mdl_name='$className'");
        $tmpTmp = $tData->lookupAll()->result();
        $dataProposals = array();
        if (sizeof($tmpTmp) > 0) {
            foreach ($tmpTmp as $row) {
                $mdlName = $row->mdl_name;


                $dataAccess = isset($this->config->item('heDataBehaviour')[$mdlName]) ? $this->config->item('heDataBehaviour')[$mdlName] : array(
                    "viewers"       => array(),
                    "creators"      => array(),
                    "creatorAdmins" => array(),
                    "updaters"      => array(),
                    "updaterAdmins" => array(),
                    "deleters"      => array(),
                    "deleterAdmins" => array(),
                );
                //                $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
                $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
                $allowView = false;
                $allowCreate = false;
                $allowEdit = false;
                $allowDelete = false;
                foreach ($mems as $mID) {
                    if (in_array($mID, $dataAccess['viewers'])) {
                        $allowView = true;
                    }
                    if (in_array($mID, $dataAccess['creators'])) {
                        $allowCreate = true;
                    }
                    if (in_array($mID, $dataAccess['updaters'])) {
                        $allowEdit = true;
                    }
                    if (in_array($mID, $dataAccess['deleters'])) {
                        $allowDelete = true;
                    }
                }

                if ($allowView || $allowCreate) {
                    if (!isset($dataProposals[$mdlName])) {
                        $dataProposals[$mdlName] = array();
                    }
                    $dataProposals[$mdlName][] = array(
                        "id"           => $row->_id,
                        "label"        => $row->mdl_label,
                        "origID"       => $row->orig_id,
                        "proposer"     => $row->proposed_by_name,
                        "date"         => $row->proposed_date,
                        "content"      => unserialize(base64_decode($row->content)),
                        "propose_type" => $row->propose_type,
                    );
                }
            }
        }

        //</editor-fold>

        $title = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : $ctrlName;

        $this->load->model("Mdls/" . $className);
        $o = new $className;
        $indexFieldName = "id";

        if (isset($_GET['trashed']) && $_GET['trashed'] > 0) {
            $objState = $_GET['trashed'];
            if ($objState == "1") {
                $title = "Deleted " . $title;

            }
            else {
                $objState = "0";
            }

        }
        else {
            $objState = "0";
        }
        switch ($objState) {
            case "0":
                $alternateLink = "<a href='" . base_url() . get_class($this) . "/view/$ctrlName?trashed=1'><span class='glyphicon glyphicon-ban-circle'></span> view deleted $ctrlName</a>";
                break;
            case "1":
                $alternateLink = "<a href='" . base_url() . get_class($this) . "/view/$ctrlName'><span class='glyphicon glyphicon-ok-sign'></span> view active $ctrlName</a>";
                break;
        }
        $o->addFilter("trash='$objState'");

        if (isset($_GET['fID']) && strlen($_GET['fID']) > 0) {
            $o->addFilter("folders='" . $_GET['fID'] . "'");
            //            $title.=" on ".$_GET['fName'];
        }

        if (isset($_GET['reqField']) && isset($_GET['reqVal'])) {
            $o->addFilter($_GET['reqField'] . "='" . $_GET['reqVal'] . "'");
        }

        //        if (isset($_GET['reqField']) && isset($_GET['reqVal'])) {
        //            $o->addFilter($_GET['reqField'] . "='" . $_GET['reqVal'] . "'");
        //        }


        if (isset($_GET['k']) && strlen($_GET['k']) > 1) {
            $key = $_GET['k'];
            $subtitle = "Pencarian dengan nama '$key'";
        }
        else {
            $key = "";
            $subtitle = "Daftar $title";
        }

        $p = new Layout($title, $subtitle, "application/template/lte/index.html");
        $t = new Table();


        //<editor-fold desc="tampilan approval data">
        $arrItemTmp = array();
        if (sizeof($dataProposals) > 0) {


            foreach ($dataProposals as $mdlName => $pSpec) {
                $this->load->model("Mdls/" . $mdlName);
                $o = new $mdlName();
                $listedFields = $this->listedFields;
                foreach ($pSpec as $dSpec) {
                    //                    echo "mulai mengiterasi kolom .. <br>";
                    $tmpItemTmp = array();
                    $dataStatus = $dSpec['origID'] > 0 ? "pembaruan" : "data baru";

                    foreach ($listedFields as $fName => $fLabel) {
                        $fRealName = $fName;
                        //                        $tmpItemTmp[$fName] = $dSpec['content'][$fRealName];
                        $fieldLabel = isset($dSpec['content'][$fRealName]) ? $dSpec['content'][$fRealName] : "";
                        //===if related
                        if (array_key_exists($fName, $this->relations)) {
                            $fieldLabel = isset($this->relationPairs[$fName][$fieldLabel]) ? "<span class='fa fa-folder-o'></span> " . $this->relationPairs[$fName][$fieldLabel] : "unknown rel";
                        }
                        $tmpItemTmp[$fName] = $fieldLabel;
                    }


                    $approvalClick = "BootstrapDialog.closeAll();
                    BootstrapDialog.show(
                                   {
                                        title:'Data " . $dSpec['label'] . " &raquo; Setujui $dataStatus ',
                                        message: $('<div></div>').load('" . base_url() . "Data/editFrom/" . $dSpec['label'] . "/" . $dSpec['id'] . "/" . $dSpec['origID'] . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:true,
                                        closable:true,
                                        }
                                        );";

                    $tmpItemTmp["date"] = $dSpec['date'];
                    $tmpItemTmp["propose_type"] = $dSpec['propose_type'];
                    $tmpItemTmp["action"] = "<a class='btn btn-primary btn-block' href='JavaScript:void(0);' onclick =\"$approvalClick;\">review</a>";
                    $tmpItemTmp["history"] = "";
                    $arrItemTmp[] = $tmpItemTmp;
                }

            }

        }
        //</editor-fold>

        $addLink = base_url() . get_class($this) . "/add/$ctrlName";
        $addManyLink = base_url() . get_class($this) . "/addMany/$ctrlName";
        if (isset($_GET['reqField']) && isset($_GET['reqVal'])) {
            $addLink .= "?reqField=" . $_GET['reqField'] . "&reqVal=" . $_GET['reqVal'];
        }


        // <editor-fold defaultstate="collapsed" desc="pagination">

        $params = array();
        $limit_per_page = 9;
        $page = ($this->uri->segment(4)) ? ($this->uri->segment(4) - 1) : 0;

        $subitle = $subtitle . " hal. " . ($page + 1);
        $total_records = $o->lookupDataCount($key);
        if ($total_records > 0) {
            // get current page records
            if (isset($_GET['sort']) && strlen($_GET['sort']) > 0) {
                $o->setSortby($_GET['sort']);
            }
            $params["results"] = $o->lookupLimitedData($limit_per_page, $page * $limit_per_page, $key);

            $config = array(
                'base_url'           => base_url() . get_class($this) . '/' . __FUNCTION__ . "/$ctrlName/",
                'total_rows'         => $total_records,
                'per_page'           => $limit_per_page,
                "uri_segment"        => 4,
                // custom paging configuration
                'num_links'          => 6,
                'use_page_numbers'   => TRUE,
                'reuse_query_string' => TRUE,
                'full_tag_open'      => '<div class="text-center">',
                'full_tag_close'     => '</div>',
                'first_link'         => "<span class='fa fa-home'></span>",
                'first_tag_open'     => '<span style="padding:1px;">',
                'first_tag_close'    => '</span>',
                'last_link'          => "<span class='fa fa-gg'></span>",
                'last_tag_open'      => '<span style="padding:1px;">',
                'last_tag_close'     => '</span>',
                'next_link'          => "<span class='fa fa-angle-right'></span>",
                'next_tag_open'      => '<span style="padding:1px;">',
                'next_tag_close'     => '</span>',
                'prev_link'          => "<span class='fa fa-angle-left'></span>",
                'prev_tag_open'      => '<span style="padding:1px;">',
                'prev_tag_close'     => '</span>',
                'cur_tag_open'       => '<span class="btn btn-primary disabled">',
                'cur_tag_close'      => '</span>',
                'num_tag_open'       => '<span style="padding:1px;">',
                'num_tag_close'      => '</span>',
            );
            $this->pagination->initialize($config);

            // build paging links
            $params["links"] = $this->pagination->create_links();
        }
        // </editor-fold>

        $tmp = isset($params['results']) ? $params['results'] : array(); //===hasil data yang dibelokin ke hasil pagination
        $dataRow = array();
        //        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();


        $defaultKey = $key != "" ? $key : "cari " . strtolower($title);
        $content .= ($t->addSpanRow(array(
            "<div class='input-group'>" . "<input type=text placeholder='$defaultKey' class='form-control text-center' onkeyup=\"if(detectEnter()==1){location.href='" . base_url() . get_class($this) . "/view/$ctrlName/?k='+this.value}\">" . "<span class='input-group-addon'>" . "<i class='glyphicon glyphicon-search'></i></span>" . "</div class='input-group'>",
        )));


        $arrayHeader = array();
        $arrayItem = array();

        if (sizeof($tmp) > 0) {//===ada data


            //            arrPrint($this->relationPairs);die();

            // <editor-fold defaultstate="collapsed" desc="nomor baris di masing2 halaman">
            //$rowCounter = 0;
            if ($this->uri->segment(3) > 0) {
                $rowCounter = ($limit_per_page * ($this->uri->segment(3) - 1));
            }
            else {
                $rowCounter = 0;
            }// </editor-fold>


            // <editor-fold defaultstate="collapsed" desc="iterasi tampilan, jika bukan berupa selfCategory">
            $iCtr = 0;
            foreach ($tmp as $m => $rowSpec) {
                $iCtr++;

                $colCounter = 0;
                $rowCounter++;

                $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();

                $idxName = "nama";
                $linkHist = base_url() . get_class($this) . "/viewHistories/$ctrlName/" . $rowSpec->id;
                $historyClick = "BootstrapDialog.closeAll();
                    BootstrapDialog.show(
                                   {
                                        title:'$ctrlName histories ',
                                        message: $('<div></div>').load('" . $linkHist . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:true,
                                        closable:true,
                                        }
                                        );";
                //foreach ($rowSpec as $n => $nx) {
                $tmpItem = array();
                //                foreach ($o->getListedFields() as $ofName => $label) {
                $jCtr = 0;
                $validRules = $o->getValidationRules();
                foreach ($o->getFields() as $asasas => $fSpec) {
                    $fieldSpec = $fSpec;

                    $ofName = $fSpec['kolom'];
                    $ofLenght = isset($fSpec['width']) ? "width:" . $fSpec['width'] . ";'" : "";
                    $fName = $ofName;

                    //                    if(array_key_exists($ofName,$o->getListedFields())){
                    $jCtr++;
                    if (array_key_exists($fieldSpec['kolom'], $validRules)) {
                        $suffix = "*";
                        $fStyle = "font-weight:bold;";
                    }
                    else {
                        $suffix = "";
                        $fStyle = "";
                    }

                    $arrayHeader[$ofName] = "<div class='' style='$ofLenght'><span style='$fStyle'>" . $fSpec['label'] . $suffix . "</span></div>";
                    //===if related
                    if (array_key_exists($ofName, $this->relations)) {
                        $fieldLabel = isset($this->relationPairs[$ofName][$rowSpec->$ofName]) ? "<span class='fa fa-folder-o'></span> " . $this->relationPairs[$ofName][$rowSpec->$ofName] : "unknown rel";
                    }
                    else {
                        $fieldLabel = $rowSpec->$ofName;
                    }


                    switch ($fSpec['inputType']) {
                        case "hidden":
                            $fContent = "<input type=hidden name='" . $fieldSpec['kolom'] . "[]' id='" . $fieldSpec['kolom'] . "_" . $iCtr . "' value='$fieldLabel'>";
                            break;
                        case "text":
                            $fContent = "<input type=text class='form-control' placeholder='" . $fieldSpec['label'] . "' name='" . $fieldSpec['kolom'] . "[]' id='" . $fieldSpec['kolom'] . "_" . $iCtr . "' value='$fieldLabel'>";
                            break;
                        case "number":
                            $fContent = "<input type=number class='form-control' placeholder='" . $fieldSpec['label'] . "' name='" . $fieldSpec['kolom'] . "[]' id='" . $fieldSpec['kolom'] . "_" . $iCtr . "' value='$fieldLabel'>";
                            break;
                        case "password":
                            $fContent = "<input type=password class='form-control' placeholder='" . $fieldSpec['label'] . "' name='" . $fieldSpec['kolom'] . "[]' id='" . $fieldSpec['kolom'] . "_" . $iCtr . "' value='$fieldLabel'>";
                            break;
                        case "combo":

                            if (isset($fieldSpec['dataSource']) || isset($fieldSpec['reference'])) {
                                $fContent = "<select class='form-control' name='" . $fieldSpec['kolom'] . "[]' id='" . $fieldSpec['kolom'] . "_" . $iCtr . "'>";
                                if (isset($fieldSpec['dataSource'])) {
                                    foreach ($fieldSpec['dataSource'] as $key => $val) {
                                        $selected = isset($rowSpec->$fName) && $key == $rowSpec->$fName ? "selected" : "";
                                        $fContent .= "<option value='$key' $selected>$val</option>";
                                    }
                                }
                                if (isset($fieldSpec['reference'])) {
                                    //                                    cekHitam($fieldSpec['reference']);
                                    //                                    arrprint($this->relations);
                                    $tmpMdlName = $fieldSpec['reference'];
                                    if (in_array($tmpMdlName, $this->relations)) {

                                        $rmdl = array_search($tmpMdlName, $this->relations);
                                        if (isset($this->relationPairs[$rmdl]) && sizeof($this->relationPairs[$rmdl])) {
                                            foreach ($this->relationPairs[$rmdl] as $key => $val) {
                                                $selected = isset($rowSpec->$fName) && $key == $rowSpec->$fName ? "selected" : "";
                                                $fContent .= "<option value='$key' $selected>$val</option>";
                                            }
                                        }

                                    }
                                }
                                $fContent .= "</select class='form-control'>";
                            }
                            else {
                                $fContent = "<input type=password class='form-control' disabled>";
                            }
                            break;
                        default:
                            $fContent = "-unknown-";
                            break;
                    }
                    //                    $tmpItem[$ofName] = str_replace(" ", "&nbsp;", $fieldLabel) . "&nbsp;";
                    if ($jCtr == 1) {
                        $fContent .= "<input type='hidden' name='ctr[]' value='$iCtr'>";
                    }
                    $tmpItem[$ofName] = $fContent;
                    $colCounter++;
                    //                    }

                }


                //                $tmpItem['history'] = "<a class='btn btn-default' href='JavaScript:void(0)' onclick=\"$historyClick\"><span class='glyphicon glyphicon-time'></span> history</a>";
                $content .= ($t->addRow($dataRow));
                if (sizeof($dataRel) > 0) {

                    $optClick = "BootstrapDialog.closeAll();
                    BootstrapDialog.show(
                                   {
                                        title:'$title options',
                                        message: $('<div></div>').load('" . base_url() . get_class($this) . "/showRelOptions/$className/" . $rowSpec->id . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:true,
                                        closable:true,
                                        }
                                        );";
                    $tmpItem['option'] = "<a href='JavaScript:void(0)' onclick=\"$optClick\">" . "<span class='glyphicon glyphicon-option-vertical'></span>" . "</a>";
                }
                $arrayItem[] = $tmpItem;

            }//

            // </editor-fold>
            //endregion datacontent
        }


        if ($this->allowCreate) {
            $addClick = "
                    BootstrapDialog.show(
                                   {
                                        title:'New $title',
                                        message: $('<div></div>').load('" . $addLink . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:true,
                                        closable:true,
                                        }
                                        );";
            $strAddLink = "";
            $strAddLink .= "<div class='btn-group'>";
            $strAddLink .= "<button href='JavaScript:void(0)' class=\" btn btn-primary\" onClick=\"$addClick\" data-toggle='tooltip' data-placement='top' title='Add new $title' class='btn btn-circle btn-xs btn-primary bg-blue-gradient'><span class='glyphicon glyphicon-plus'></button>";

            $strAddLink .= "<button href='JavaScript:void(0)' class='btn btn-success' onclick=\"location.href = '$addManyLink';\"  data-toggle='tooltip' data-placement='top' title='Add many entries of $title'><span class='glyphicon glyphicon-plus-sign'></span></button>";

            $strAddLink .= "</div class='btn-group'>";


        }
        else {
            $strAddLink = "";
        }

        //        $arrayHeader = $o->getListedFields();

        if (sizeof($dataRel) > 0) {
            $arrayHeader["option"] = "<span class='glyphicon glyphicon-th-list'></span>";
        }


        $this->load->model("Mdls/" . "MdlDataHistory");
        $h = new MdlDataHistory();
        $h->addFilter("mdl_name='$className'");
        //        $h->addFilter("orig_id='$selectedID'");
        $tmpH = $h->lookupRecentHistories()->result();

        $arrayRecap = array();
        if (sizeof($tmpH) > 0) {
            $tmpO = new $className();
            //            cekHere(json_encode($tmpO->getListedFields()));
            foreach ($tmpH as $row) {
                $tmpRecap = array();
                $content = unserialize(base64_decode($row->new_content));
                //cekHere(json_encode($content));
                foreach ($this->listedFields as $fName => $label) {

                    //                    $tmpRecap[$fName] = isset($content[$fName]) ? $content[$fName] : "";
                    //                    echo $content[$fName];

                    $fieldLabel = isset($content[$fName]) ? $content[$fName] : "";
                    //===if related
                    if (array_key_exists($fName, $this->relations)) {
                        $fieldLabel = isset($this->relationPairs[$fName][$fieldLabel]) ? "<span class='fa fa-folder-o'></span> " . $this->relationPairs[$fName][$fieldLabel] : "unknown rel";
                    }
                    $tmpRecap[$fName] = $fieldLabel;

                }
                $arrayRecap[] = $tmpRecap;
            }
        }

        $arrayProgressLabel['date'] = "date";
        $arrayProgressLabel['propose_type'] = "proposal type";
        $arrayProgressLabel = $arrayProgressLabel + $arrayHeader;
        $arrayRecapLabel = $arrayHeader;

        $arrayProgressLabel['action'] = "action";

        unset($arrayProgressLabel['history']);
        unset($arrayRecapLabel['action']);
        unset($arrayRecapLabel['history']);


        //        die(substr($ctrlName,strlen($ctrlName)-1));
        $titleSuffix = createObjectSuffix($realObjName);

        $alternateLink = "<button class='btn btn-success' id='btnSave' name='btnSave' onclick=\"this.disabled=true;document.getElementById('fmany').submit();\"><span class='glyphicon glyphicon-ok'></span> save entries</button>";

        if (isset($this->relationPairs) && array_key_exists("folders", $this->relationPairs)) {
            $folders = array("" => "HOME") + $this->relationPairs['folders'];
            $fmdlName = $this->relations['folders'];
            $fdataAccess = isset($this->config->item('heDataBehaviour')[$fmdlName]) ? $this->config->item('heDataBehaviour')[$fmdlName] : array(
                "viewers"        => array(),
                "creators"       => array(),
                "creatorAdmins"  => array(),
                "updaters"       => array(),
                "updaterAdmins"  => array(),
                "deleters"       => array(),
                "deleterAdmins"  => array(),
                "historyViewers" => array(),
            );

            $allowCreateFolder = false;
            $allowEditFolder = false;
            $allowDeleteFolder = false;
            if (sizeof($mems) > 0 && sizeof($fdataAccess['creators']) > 0) {
                $allowCreateFolder = true;
            }
            if (sizeof($mems) > 0 && sizeof($fdataAccess['updaters']) > 0) {
                $allowEditFolder = true;
            }
            if (sizeof($mems) > 0 && sizeof($fdataAccess['deleters']) > 0) {
                $allowDeleteFolder = true;
            }

            if ($allowCreateFolder) {
                $faddLink = base_url() . get_class($this) . "/add/" . str_replace("Mdl", "", $fmdlName);
            }
            if ($allowEditFolder) {
                $fupdateLink = base_url() . get_class($this) . "/edit/" . str_replace("Mdl", "", $fmdlName) . "/";
            }
            if ($allowDeleteFolder) {
                $fdeleteLink = base_url() . get_class($this) . "/delete/" . str_replace("Mdl", "", $fmdlName) . "/";
            }

        }
        else {
            $folders = array();
        }

        $data = array(
            "mode"                => $this->uri->segment(2),
            "errMsg"              => $this->session->errMsg,
            "title"               => $realObjName . $titleSuffix,
            "subTitle"            => "Modify $realObjName" . addslashes($titleSuffix),
            "historyTitle"        => "<span class='glyphicon glyphicon-th-list'></span> Directly modify $title" . addslashes($titleSuffix) . " below",
            "linkStr"             => isset($params['links']) ? $params['links'] : "",
            "arrayHistoryLabels"  => $arrayHeader,
            "arrayHistory"        => $arrayItem,
            "onprogressTitle"     => "<span class='glyphicon glyphicon-alert'></span> approval needed",
            "arrayProgressLabels" => $arrayProgressLabel,
            "arrayOnProgress"     => $arrItemTmp,
            //            "entities" => $entities,
            "recapTitle"          => "<span class='glyphicon glyphicon-time'></span> recent data updates",
            "arrayRecapLabels"    => $arrayRecapLabel,
            "arrayRecap"          => $arrayRecap,
            "strAddLink"          => $strAddLink,
            "alternateLink"       => $alternateLink,
            "thisPage"            => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "?trashed=$objState",
            "formTarget"          => base_url() . get_class($this) . "/doEditMany/" . $this->uri->segment(3),
            "folders"             => $folders,
            "faddLink"            => isset($faddLink) ? $faddLink : "",
            "feditLink"           => isset($fupdateLink) ? $fupdateLink : "",
            "fdeleteLink"         => isset($fdeleteLink) ? $fdeleteLink : "",
            "fmdlName"            => isset($fmdlName) ? $fmdlName : "",
            "fmdlTarget"          => isset($fmdlName) ? base_url() . get_class($this) . "/view/" . str_replace("Mdl", "", $fmdlName) : "",
        );
        $this->load->view('data', $data);
        $this->session->errMsg = "";
    }

    public function editMany()
    {
        //        echo "9999";
        $content = "";
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
            die();
        }
        if (!$this->allowEdit) {
            die("Sorry, you are now allowed to modifiy any of these data entries");
        }
        $className = "Mdl" . $this->uri->segment(3);
        $ctrlName = $this->uri->segment(3);
        $dataRel = isset($this->config->item('dataRelation')[$className]) ? $this->config->item('dataRelation')[$className] : array();

        $realObjName = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : get_class($this);
        $title = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : get_class($this);

        //<editor-fold desc="data proposal data">
        $this->load->model("Mdls/" . "MdlDataTmp");
        $tData = new MdlDataTmp();
        $tData->addFilter("mdl_name='$className'");
        $tmpTmp = $tData->lookupAll()->result();
        $dataProposals = array();
        if (sizeof($tmpTmp) > 0) {
            foreach ($tmpTmp as $row) {
                $mdlName = $row->mdl_name;


                $dataAccess = isset($this->config->item('heDataBehaviour')[$mdlName]) ? $this->config->item('heDataBehaviour')[$mdlName] : array(
                    "viewers"       => array(),
                    "creators"      => array(),
                    "creatorAdmins" => array(),
                    "updaters"      => array(),
                    "updaterAdmins" => array(),
                    "deleters"      => array(),
                    "deleterAdmins" => array(),
                );
                //                $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
                $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
                $allowView = false;
                $allowCreate = false;
                $allowEdit = false;
                $allowDelete = false;
                foreach ($mems as $mID) {
                    if (in_array($mID, $dataAccess['viewers'])) {
                        $allowView = true;
                    }
                    if (in_array($mID, $dataAccess['creators'])) {
                        $allowCreate = true;
                    }
                    if (in_array($mID, $dataAccess['updaters'])) {
                        $allowEdit = true;
                    }
                    if (in_array($mID, $dataAccess['deleters'])) {
                        $allowDelete = true;
                    }
                }

                if ($allowView || $allowCreate) {
                    if (!isset($dataProposals[$mdlName])) {
                        $dataProposals[$mdlName] = array();
                    }
                    $dataProposals[$mdlName][] = array(
                        "id"           => $row->_id,
                        "label"        => $row->mdl_label,
                        "origID"       => $row->orig_id,
                        "proposer"     => $row->proposed_by_name,
                        "date"         => $row->proposed_date,
                        "content"      => unserialize(base64_decode($row->content)),
                        "propose_type" => $row->propose_type,
                    );
                }
            }
        }

        //</editor-fold>

        $title = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : $ctrlName;

        $this->load->model("Mdls/" . $className);
        $o = new $className;
        $indexFieldName = "id";

        if (isset($_GET['trashed']) && $_GET['trashed'] > 0) {
            $objState = $_GET['trashed'];
            if ($objState == "1") {
                $title = "Deleted " . $title;

            }
            else {
                $objState = "0";
            }

        }
        else {
            $objState = "0";
        }
        switch ($objState) {
            case "0":
                $alternateLink = "<a href='" . base_url() . get_class($this) . "/view/$ctrlName?trashed=1'><span class='glyphicon glyphicon-ban-circle'></span> view deleted $ctrlName</a>";
                break;
            case "1":
                $alternateLink = "<a href='" . base_url() . get_class($this) . "/view/$ctrlName'><span class='glyphicon glyphicon-ok-sign'></span> view active $ctrlName</a>";
                break;
        }
        $o->addFilter("trash='$objState'");

        if (isset($_GET['fID']) && strlen($_GET['fID']) > 0) {
            $o->addFilter("folders='" . $_GET['fID'] . "'");
            //            $title.=" on ".$_GET['fName'];
        }

        if (isset($_GET['reqField']) && isset($_GET['reqVal'])) {
            $o->addFilter($_GET['reqField'] . "='" . $_GET['reqVal'] . "'");
        }

        //        if (isset($_GET['reqField']) && isset($_GET['reqVal'])) {
        //            $o->addFilter($_GET['reqField'] . "='" . $_GET['reqVal'] . "'");
        //        }


        if (isset($_GET['k']) && strlen($_GET['k']) > 1) {
            $key = $_GET['k'];
            $subtitle = "Pencarian dengan nama '$key'";
        }
        else {
            $key = "";
            $subtitle = "Daftar $title";
        }

        $p = new Layout($title, $subtitle, "application/template/lte/index.html");
        $t = new Table();


        //<editor-fold desc="tampilan approval data">
        $arrItemTmp = array();
        if (sizeof($dataProposals) > 0) {


            foreach ($dataProposals as $mdlName => $pSpec) {
                $this->load->model("Mdls/" . $mdlName);
                $o = new $mdlName();
                $listedFields = $this->listedFields;
                foreach ($pSpec as $dSpec) {
                    //                    echo "mulai mengiterasi kolom .. <br>";
                    $tmpItemTmp = array();
                    $dataStatus = $dSpec['origID'] > 0 ? "pembaruan" : "data baru";

                    foreach ($listedFields as $fName => $fLabel) {
                        $fRealName = $fName;
                        //                        $tmpItemTmp[$fName] = $dSpec['content'][$fRealName];
                        $fieldLabel = isset($dSpec['content'][$fRealName]) ? $dSpec['content'][$fRealName] : "";
                        //===if related
                        if (array_key_exists($fName, $this->relations)) {
                            $fieldLabel = isset($this->relationPairs[$fName][$fieldLabel]) ? "<span class='fa fa-folder-o'></span> " . $this->relationPairs[$fName][$fieldLabel] : "unknown rel";
                        }
                        $tmpItemTmp[$fName] = $fieldLabel;
                    }


                    $approvalClick = "BootstrapDialog.closeAll();
                    BootstrapDialog.show(
                                   {
                                        title:'Data " . $dSpec['label'] . " &raquo; Setujui $dataStatus ',
                                        message: $('<div></div>').load('" . base_url() . "Data/editFrom/" . $dSpec['label'] . "/" . $dSpec['id'] . "/" . $dSpec['origID'] . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:true,
                                        closable:true,
                                        }
                                        );";

                    $tmpItemTmp["date"] = $dSpec['date'];
                    $tmpItemTmp["propose_type"] = $dSpec['propose_type'];
                    $tmpItemTmp["action"] = "<a class='btn btn-primary btn-block' href='JavaScript:void(0);' onclick =\"$approvalClick;\">review</a>";
                    $tmpItemTmp["history"] = "";
                    $arrItemTmp[] = $tmpItemTmp;
                }

            }

        }
        //</editor-fold>

        $addLink = base_url() . get_class($this) . "/add/$ctrlName";
        $addManyLink = base_url() . get_class($this) . "/addMany/$ctrlName";
        if (isset($_GET['reqField']) && isset($_GET['reqVal'])) {
            $addLink .= "?reqField=" . $_GET['reqField'] . "&reqVal=" . $_GET['reqVal'];
        }


        // <editor-fold defaultstate="collapsed" desc="pagination">

        $params = array();
        $limit_per_page = 9;
        $page = ($this->uri->segment(4)) ? ($this->uri->segment(4) - 1) : 0;

        $subitle = $subtitle . " hal. " . ($page + 1);
        $total_records = $o->lookupDataCount($key);
        if ($total_records > 0) {
            // get current page records
            if (isset($_GET['sort']) && strlen($_GET['sort']) > 0) {
                $o->setSortby($_GET['sort']);
            }
            $params["results"] = $o->lookupLimitedData($limit_per_page, $page * $limit_per_page, $key);

            $config = array(
                'base_url'           => base_url() . get_class($this) . '/' . __FUNCTION__ . "/$ctrlName/",
                'total_rows'         => $total_records,
                'per_page'           => $limit_per_page,
                "uri_segment"        => 4,
                // custom paging configuration
                'num_links'          => 6,
                'use_page_numbers'   => TRUE,
                'reuse_query_string' => TRUE,
                'full_tag_open'      => '<div class="text-center">',
                'full_tag_close'     => '</div>',
                'first_link'         => "<span class='fa fa-home'></span>",
                'first_tag_open'     => '<span style="padding:1px;">',
                'first_tag_close'    => '</span>',
                'last_link'          => "<span class='fa fa-gg'></span>",
                'last_tag_open'      => '<span style="padding:1px;">',
                'last_tag_close'     => '</span>',
                'next_link'          => "<span class='fa fa-angle-right'></span>",
                'next_tag_open'      => '<span style="padding:1px;">',
                'next_tag_close'     => '</span>',
                'prev_link'          => "<span class='fa fa-angle-left'></span>",
                'prev_tag_open'      => '<span style="padding:1px;">',
                'prev_tag_close'     => '</span>',
                'cur_tag_open'       => '<span class="btn btn-primary disabled">',
                'cur_tag_close'      => '</span>',
                'num_tag_open'       => '<span style="padding:1px;">',
                'num_tag_close'      => '</span>',
            );
            $this->pagination->initialize($config);

            // build paging links
            $params["links"] = $this->pagination->create_links();
        }
        // </editor-fold>

        $tmp = isset($params['results']) ? $params['results'] : array(); //===hasil data yang dibelokin ke hasil pagination
        $dataRow = array();
        //        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();


        $defaultKey = $key != "" ? $key : "cari " . strtolower($title);
        $content .= ($t->addSpanRow(array(
            "<div class='input-group'>" . "<input type=text placeholder='$defaultKey' class='form-control text-center' onkeyup=\"if(detectEnter()==1){location.href='" . base_url() . get_class($this) . "/view/$ctrlName/?k='+this.value}\">" . "<span class='input-group-addon'>" . "<i class='glyphicon glyphicon-search'></i></span>" . "</div class='input-group'>",
        )));


        $arrayHeader = array();
        $arrayItem = array();

        if (sizeof($tmp) > 0) {//===ada data


            //            arrPrint($this->relationPairs);die();

            // <editor-fold defaultstate="collapsed" desc="nomor baris di masing2 halaman">
            //$rowCounter = 0;
            if ($this->uri->segment(3) > 0) {
                $rowCounter = ($limit_per_page * ($this->uri->segment(3) - 1));
            }
            else {
                $rowCounter = 0;
            }// </editor-fold>


            // <editor-fold defaultstate="collapsed" desc="iterasi tampilan, jika bukan berupa selfCategory">
            $iCtr = 0;
            foreach ($tmp as $m => $rowSpec) {
                $iCtr++;

                $colCounter = 0;
                $rowCounter++;

                $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();

                $idxName = "nama";
                $linkHist = base_url() . get_class($this) . "/viewHistories/$ctrlName/" . $rowSpec->id;
                $historyClick = "BootstrapDialog.closeAll();
                    BootstrapDialog.show(
                                   {
                                        title:'$ctrlName histories ',
                                        message: $('<div></div>').load('" . $linkHist . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:true,
                                        closable:true,
                                        }
                                        );";
                //foreach ($rowSpec as $n => $nx) {
                $tmpItem = array();
                //                foreach ($o->getListedFields() as $ofName => $label) {
                $jCtr = 0;
                $validRules = $o->getValidationRules();
                foreach ($o->getFields() as $asasas => $fSpec) {
                    $fieldSpec = $fSpec;

                    $ofName = $fSpec['kolom'];
                    $ofLenght = isset($fSpec['width']) ? "width:" . $fSpec['width'] . ";'" : "";
                    $fName = $ofName;

                    //                    if(array_key_exists($ofName,$o->getListedFields())){
                    $jCtr++;
                    if (array_key_exists($fieldSpec['kolom'], $validRules)) {
                        $suffix = "*";
                        $fStyle = "font-weight:bold;";
                    }
                    else {
                        $suffix = "";
                        $fStyle = "";
                    }

                    $arrayHeader[$ofName] = "<div class='' style='$ofLenght'><span style='$fStyle'>" . $fSpec['label'] . $suffix . "</span></div>";
                    //===if related
                    if (array_key_exists($ofName, $this->relations)) {
                        $fieldLabel = isset($this->relationPairs[$ofName][$rowSpec->$ofName]) ? "<span class='fa fa-folder-o'></span> " . $this->relationPairs[$ofName][$rowSpec->$ofName] : "unknown rel";
                    }
                    else {
                        $fieldLabel = $rowSpec->$ofName;
                    }


                    switch ($fSpec['inputType']) {
                        case "hidden":
                            $fContent = "<input type=hidden name='" . $fieldSpec['kolom'] . "[]' id='" . $fieldSpec['kolom'] . "_" . $iCtr . "' value='$fieldLabel'>";
                            break;
                        case "text":
                            $fContent = "<input type=text class='form-control' placeholder='" . $fieldSpec['label'] . "' name='" . $fieldSpec['kolom'] . "[]' id='" . $fieldSpec['kolom'] . "_" . $iCtr . "' value='$fieldLabel'>";
                            break;
                        case "number":
                            $fContent = "<input type=number class='form-control' placeholder='" . $fieldSpec['label'] . "' name='" . $fieldSpec['kolom'] . "[]' id='" . $fieldSpec['kolom'] . "_" . $iCtr . "' value='$fieldLabel'>";
                            break;
                        case "password":
                            $fContent = "<input type=password class='form-control' placeholder='" . $fieldSpec['label'] . "' name='" . $fieldSpec['kolom'] . "[]' id='" . $fieldSpec['kolom'] . "_" . $iCtr . "' value='$fieldLabel'>";
                            break;
                        case "combo":

                            if (isset($fieldSpec['dataSource']) || isset($fieldSpec['reference'])) {
                                $fContent = "<select class='form-control' name='" . $fieldSpec['kolom'] . "[]' id='" . $fieldSpec['kolom'] . "_" . $iCtr . "'>";
                                if (isset($fieldSpec['dataSource'])) {
                                    foreach ($fieldSpec['dataSource'] as $key => $val) {
                                        $selected = isset($rowSpec->$fName) && $key == $rowSpec->$fName ? "selected" : "";
                                        $fContent .= "<option value='$key' $selected>$val</option>";
                                    }
                                }
                                if (isset($fieldSpec['reference'])) {
                                    //                                    cekHitam($fieldSpec['reference']);
                                    //                                    arrprint($this->relations);
                                    $tmpMdlName = $fieldSpec['reference'];
                                    if (in_array($tmpMdlName, $this->relations)) {

                                        $rmdl = array_search($tmpMdlName, $this->relations);
                                        if (isset($this->relationPairs[$rmdl]) && sizeof($this->relationPairs[$rmdl])) {
                                            foreach ($this->relationPairs[$rmdl] as $key => $val) {
                                                $selected = isset($rowSpec->$fName) && $key == $rowSpec->$fName ? "selected" : "";
                                                $fContent .= "<option value='$key' $selected>$val</option>";
                                            }
                                        }

                                    }
                                }
                                $fContent .= "</select class='form-control'>";
                            }
                            else {
                                $fContent = "<input type=password class='form-control' disabled>";
                            }
                            break;
                        default:
                            $fContent = "-unknown-";
                            break;
                    }
                    //                    $tmpItem[$ofName] = str_replace(" ", "&nbsp;", $fieldLabel) . "&nbsp;";
                    if ($jCtr == 1) {
                        $fContent .= "<input type='hidden' name='ctr[]' value='$iCtr'>";
                    }
                    $tmpItem[$ofName] = $fContent;
                    $colCounter++;
                    //                    }

                }


                //                $tmpItem['history'] = "<a class='btn btn-default' href='JavaScript:void(0)' onclick=\"$historyClick\"><span class='glyphicon glyphicon-time'></span> history</a>";
                $content .= ($t->addRow($dataRow));
                if (sizeof($dataRel) > 0) {

                    $optClick = "BootstrapDialog.closeAll();
                    BootstrapDialog.show(
                                   {
                                        title:'$title options',
                                        message: $('<div></div>').load('" . base_url() . get_class($this) . "/showRelOptions/$className/" . $rowSpec->id . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:true,
                                        closable:true,
                                        }
                                        );";
                    $tmpItem['option'] = "<a href='JavaScript:void(0)' onclick=\"$optClick\">" . "<span class='glyphicon glyphicon-option-vertical'></span>" . "</a>";
                }
                $arrayItem[] = $tmpItem;

            }//

            // </editor-fold>
            //endregion datacontent
        }


        if ($this->allowCreate) {
            $addClick = "
                    BootstrapDialog.show(
                                   {
                                        title:'New $title',
                                        message: $('<div></div>').load('" . $addLink . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:true,
                                        closable:true,
                                        }
                                        );";
            $strAddLink = "";
            $strAddLink .= "<div class='btn-group'>";
            $strAddLink .= "<button href='JavaScript:void(0)' class=\" btn btn-primary\" onClick=\"$addClick\" data-toggle='tooltip' data-placement='top' title='Add new $title' class='btn btn-circle btn-xs btn-primary bg-blue-gradient'><span class='glyphicon glyphicon-plus'></button>";

            $strAddLink .= "<button href='JavaScript:void(0)' class='btn btn-success' onclick=\"location.href = '$addManyLink';\"  data-toggle='tooltip' data-placement='top' title='Add many entries of $title'><span class='glyphicon glyphicon-plus-sign'></span></button>";

            $strAddLink .= "</div class='btn-group'>";


        }
        else {
            $strAddLink = "";
        }

        //        $arrayHeader = $o->getListedFields();

        if (sizeof($dataRel) > 0) {
            $arrayHeader["option"] = "<span class='glyphicon glyphicon-th-list'></span>";
        }


        $this->load->model("Mdls/" . "MdlDataHistory");
        $h = new MdlDataHistory();
        $h->addFilter("mdl_name='$className'");
        //        $h->addFilter("orig_id='$selectedID'");
        $tmpH = $h->lookupRecentHistories()->result();

        $arrayRecap = array();
        if (sizeof($tmpH) > 0) {
            $tmpO = new $className();
            //            cekHere(json_encode($tmpO->getListedFields()));
            foreach ($tmpH as $row) {
                $tmpRecap = array();
                $content = unserialize(base64_decode($row->new_content));
                //cekHere(json_encode($content));
                foreach ($this->listedFields as $fName => $label) {

                    //                    $tmpRecap[$fName] = isset($content[$fName]) ? $content[$fName] : "";
                    //                    echo $content[$fName];

                    $fieldLabel = isset($content[$fName]) ? $content[$fName] : "";
                    //===if related
                    if (array_key_exists($fName, $this->relations)) {
                        $fieldLabel = isset($this->relationPairs[$fName][$fieldLabel]) ? "<span class='fa fa-folder-o'></span> " . $this->relationPairs[$fName][$fieldLabel] : "unknown rel";
                    }
                    $tmpRecap[$fName] = $fieldLabel;

                }
                $arrayRecap[] = $tmpRecap;
            }
        }

        $arrayProgressLabel['date'] = "date";
        $arrayProgressLabel['propose_type'] = "proposal type";
        $arrayProgressLabel = $arrayProgressLabel + $arrayHeader;
        $arrayRecapLabel = $arrayHeader;

        $arrayProgressLabel['action'] = "action";

        unset($arrayProgressLabel['history']);
        unset($arrayRecapLabel['action']);
        unset($arrayRecapLabel['history']);


        //        die(substr($ctrlName,strlen($ctrlName)-1));
        $titleSuffix = createObjectSuffix($realObjName);

        $alternateLink = "<button class='btn btn-success' id='btnSave' name='btnSave' onclick=\"this.disabled=true;document.getElementById('fmany').submit();\"><span class='glyphicon glyphicon-ok'></span> save entries</button>";

        if (isset($this->relationPairs) && array_key_exists("folders", $this->relationPairs)) {
            $folders = array("" => "HOME") + $this->relationPairs['folders'];
            $fmdlName = $this->relations['folders'];
            $fdataAccess = isset($this->config->item('heDataBehaviour')[$fmdlName]) ? $this->config->item('heDataBehaviour')[$fmdlName] : array(
                "viewers"        => array(),
                "creators"       => array(),
                "creatorAdmins"  => array(),
                "updaters"       => array(),
                "updaterAdmins"  => array(),
                "deleters"       => array(),
                "deleterAdmins"  => array(),
                "historyViewers" => array(),
            );

            $allowCreateFolder = false;
            $allowEditFolder = false;
            $allowDeleteFolder = false;
            if (sizeof($mems) > 0 && sizeof($fdataAccess['creators']) > 0) {
                $allowCreateFolder = true;
            }
            if (sizeof($mems) > 0 && sizeof($fdataAccess['updaters']) > 0) {
                $allowEditFolder = true;
            }
            if (sizeof($mems) > 0 && sizeof($fdataAccess['deleters']) > 0) {
                $allowDeleteFolder = true;
            }

            if ($allowCreateFolder) {
                $faddLink = base_url() . get_class($this) . "/add/" . str_replace("Mdl", "", $fmdlName);
            }
            if ($allowEditFolder) {
                $fupdateLink = base_url() . get_class($this) . "/edit/" . str_replace("Mdl", "", $fmdlName) . "/";
            }
            if ($allowDeleteFolder) {
                $fdeleteLink = base_url() . get_class($this) . "/delete/" . str_replace("Mdl", "", $fmdlName) . "/";
            }

        }
        else {
            $folders = array();
        }

        $data = array(
            "mode"                => $this->uri->segment(2),
            "errMsg"              => $this->session->errMsg,
            "title"               => $realObjName . $titleSuffix,
            "subTitle"            => "Modify $realObjName" . addslashes($titleSuffix),
            "historyTitle"        => "<span class='glyphicon glyphicon-th-list'></span> Directly modify $title" . addslashes($titleSuffix) . " below",
            "linkStr"             => isset($params['links']) ? $params['links'] : "",
            "arrayHistoryLabels"  => $arrayHeader,
            "arrayHistory"        => $arrayItem,
            "onprogressTitle"     => "<span class='glyphicon glyphicon-alert'></span> approval needed",
            "arrayProgressLabels" => $arrayProgressLabel,
            "arrayOnProgress"     => $arrItemTmp,
            //            "entities" => $entities,
            "recapTitle"          => "<span class='glyphicon glyphicon-time'></span> recent data updates",
            "arrayRecapLabels"    => $arrayRecapLabel,
            "arrayRecap"          => $arrayRecap,
            "strAddLink"          => $strAddLink,
            "alternateLink"       => $alternateLink,
            "thisPage"            => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "?trashed=$objState",
            "formTarget"          => base_url() . get_class($this) . "/doEditMany/" . $this->uri->segment(3),
            "folders"             => $folders,
            "faddLink"            => isset($faddLink) ? $faddLink : "",
            "feditLink"           => isset($fupdateLink) ? $fupdateLink : "",
            "fdeleteLink"         => isset($fdeleteLink) ? $fdeleteLink : "",
            "fmdlName"            => isset($fmdlName) ? $fmdlName : "",
            "fmdlTarget"          => isset($fmdlName) ? base_url() . get_class($this) . "/view/" . str_replace("Mdl", "", $fmdlName) : "",
        );
        $this->load->view('data', $data);
        $this->session->errMsg = "";
    }

    public function doEditMany()
    {
        $className = "Mdl" . $this->uri->segment(3);
        $ctrlName = $this->uri->segment(3);

        $this->load->model("Mdls/" . $className);
        $o = new $className;
        $indexFieldName = "id";
        $fields = $o->getFields();
        $validRules = $o->getValidationRules();

        $inputTypeWhitelist = array("combo", "radio");
        $validRows = array();
        $inValidRows = array();
        //        arrPrint($_POST);
        if (isset($_POST['ctr']) && sizeof($_POST['ctr']) > 0) {
            $this->db->trans_start();
            foreach ($_POST['ctr'] as $ctr => $ctrx) {
                $filledCols = array();
                foreach ($fields as $fieldName => $fieldSpec) {
                    //                    cekHijau($fieldSpec['inputType']);
                    if (!in_array($fieldSpec['inputType'], $inputTypeWhitelist)) {
                        $inputName = $fieldSpec['kolom'];
                        if (isset($_POST[$inputName][$ctr]) && strlen($_POST[$inputName][$ctr]) > 0) {
                            cekKuning($inputName . " ada, yaitu " . $_POST[$inputName][$ctr]);
                            $filledCols[] = $inputName;
                        }
                    }

                }
                if (sizeof($filledCols) > 0) {
                    $diisi = true;
                }
                else {
                    $diisi = false;
                }
                if ($diisi) {//==barulah divalidasi
                    cekHijau("$ctr diisi");
                    $valResult = $this->lineValidate($o, $ctr, "edit");
                    if (is_array($valResult)) {
                        arrPrint($valResult);
                        cekMerah("$ctr TIDAK VALID");
                        $inValidRows[] = $ctr;

                        echo "<script>";
                        foreach ($valResult as $f => $fff) {
                            echo "top.document.getElementById('$f" . "_" . "$ctrx').style.backgroundColor='#ffff00';";
                        }
                        echo "</script>";

                    }
                    else {
                        cekHijau("$ctr VALID");
                        $validRows[] = $ctr;
                        $data = array();
                        foreach ($fields as $fieldName => $fieldSpec) {
                            echo "<script>";
                            //                            foreach ($valResult as $f => $fff) {
                            //                                echo "top.document.getElementById('$f" . "_" . "$ctrx').style.backgroundColor='transparent';";
                            //                            }
                            echo "</script>";
                            $inputName = $fieldSpec['kolom'];
                            if (isset($_POST[$inputName][$ctr])) {
                                $data[$inputName] = $_POST[$inputName][$ctr];
                            }

                            //                            cekHijau("$f berisi ".$_POST[$inputName][$ctr]);
                        }

                        //                        arrPrint($data);die();
                        //<editor-fold desc="data temporer, jika pakai approval">
                        $where = array(
                            /*$indexFieldName =>*/
                            "id" => $data['id'],
                            //                "id"=> $data['id'],
                        );
                        $this->load->model("Mdls/" . "MdlDataTmp");
                        $dTmp = new MdlDataTmp();
                        $tmpData = array(
                            "orig_id"          => $data['id'],
                            "mdl_name"         => $className,
                            "mdl_label"        => $ctrlName,
                            "proposed_by"      => $this->session->login['id'],
                            "proposed_by_name" => $this->session->login['nama'],
                            "proposed_date"    => date("Y-m-d H:i:s"),
                            "content"          => base64_encode(serialize($data)),
                        );


                        if ($this->updaterUsingApproval) {
                            $insertID = $dTmp->addData($tmpData, $dTmp->getTableName()) or die(lgShowError("Gagal menulis pengajuan data", __FILE__));
                            cekHijau($this->db->last_query());
                            $this->session->errMsg = "Data proposal has been saved and pending approval";

                            $tmpOrig = $o->lookupByCondition(array(/*$indexFieldName =>*/
                                "id" => $data['id'],
                            ))->result();
                            $o->setFilters(array());
                            $o->updateData($where, array("status" => 0, "trash" => 1), $o->getTableName());
                            cekMerah($this->db->last_query());

                            //<editor-fold desc="data history / propose">
                            $this->load->model("Mdls/" . "MdlDataHistory");
                            $hTmp = new MdlDataHistory();
                            $tmpHData = array(
                                "orig_id"            => $data['id'],
                                "mdl_name"           => $className,
                                "mdl_label"          => get_class($this),
                                "old_content"        => base64_encode(serialize((array)$tmpOrig)),
                                "old_content_intext" => print_r((array)$tmpOrig, true),
                                "new_content"        => base64_encode(serialize($data)),
                                "new_content_intext" => print_r($data, true),
                                "label"              => "proposed",
                                "oleh_id"            => $this->session->login['id'],
                                "oleh_name"          => $this->session->login['nama'],
                            );
                            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
                            //</editor-fold>

                        }
                        else {
                            $tmpOrig = $o->lookupByCondition(array(/*$indexFieldName =>*/
                                "id" => $data['id'],
                            ))->result();
                            $o->setFilters(array());
                            $o->updateData($where, $data, $o->getTableName());
                            $this->session->errMsg = "Data has been updated";

                            //<editor-fold desc="data history / approve">
                            $this->load->model("Mdls/" . "MdlDataHistory");
                            $hTmp = new MdlDataHistory();
                            $tmpHData = array(
                                "orig_id"            => $data['id'],
                                "mdl_name"           => $className,
                                "mdl_label"          => get_class($this),
                                "old_content"        => base64_encode(serialize((array)$tmpOrig)),
                                "old_content_intext" => print_r((array)$tmpOrig, true),
                                "new_content"        => base64_encode(serialize($data)),
                                "new_content_intext" => print_r($data, true),
                                "label"              => "applied",
                                "oleh_id"            => $this->session->login['id'],
                                "oleh_name"          => $this->session->login['nama'],
                            );
                            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
                            //</editor-fold>
                        }


                    }
                }
                else {
                    cekMerah("$ctr tidak diisi");

                }
            }

            if (sizeof($inValidRows) > 0) {
                echo "<script>";
                echo "top.document.getElementById('btnSave').disabled=false;";
                echo "</script>";
            }
            else {
                $this->db->trans_complete();
                echo "<script>";
                echo "top.location.reload();";
                echo "</script>";
            }

        }
        else {
            die("items required");
        }
    }


    //region inporter folders
    public function importFolder()
    {
        arrPrint($this->uri->segment_array());
        $content = "";
        //        if (!isset($this->session->login['id'])) {
        //            gotoLogin();//remember last login
        //        }
        $className = "MdlFolderProduk";

        //        $ctrlName = $this->uri->segment(1);
        $this->load->helper("he_misc");
        $this->load->model("Mdls/" . $className);

        $f = new MdlFolderProduk();
        $tmp = $f->lookupAll()->result();
        //        cekHijau($this->db->last_query());
        $selectedFields = $f->getFields();
        //        arrPrint($tmp);
        $arrFolder = array();
        foreach ($tmp as $tmp_0) {
            $arrTemp = array();
            foreach ($selectedFields as $kolom => $arrFields_0) {
                $val = isset($tmp_0->$kolom) ? $tmp_0->$kolom : "";
                $arrTemp[$kolom] = $val;
            }
            //            $newTemp = rename_array_key($arrTemp,"id","used_id");
            $arrFolder[] = $arrTemp;

        }
        arrPrint($arrFolder);
        $this->db->trans_start();
        $this->load->model("Mdls/MdlFolder");
        $fo = new MdlFolder();
        foreach ($arrFolder as $data) {
            $fo->addData($data, $fo->getTableName());
            //            cekMerah("nginsert");
            cekHijau($this->db->last_query());
        }

        //arrPrint($arrFolder);
        matiHEre("kill udah selesai");
        $this->db->trans_complete();


    }
    //endregion

    // -------------------------begin server side data table ---------------------
    /** ----------------------------------------
     * Pemanggil dat
     * ----------------------------------------*/
    function viewdt()
    {
        //        matiHEre
        // if (my_cabang_id() > 0) {
        //     $folderId = my_cabang_id();
        // }
        // else {
        $folderId = isset($_GET['id']) ? $_GET['id'] : "";
        // }
        switch ($this->className) {
            case "MdlCustomers":
            case "MdlCustomerExport":
                $this->allowCreate = false;
                break;
        }
        $ctrlName = $this->segment_4;
        $title = $ctrlName;
        $this->load->model("Mdls/" . $this->className);
        $o = new $this->className;
        $indexFieldName = "id";
        $maximumData = $o->getMaximumData();
        $buttonDatas = $o->getBtnActions();
        $buttonnActionAll = $o->getBtnActionAll();
        $fields = $o->getFields();
        /* -----------------------------------------------------------------------------------------------
         * jml maksimal data yg diperbolehkan, setting ada dimaisng2 model protected $maximumData = ...;
         * model yg tidak ada limit berarti unlimited
         * -----------------------------------------------------------------------------------------------
         */
        $o->setTokoId(my_toko_id());
        $jmlDataNow = $o->lookupJmlActive();
        // showLast_query("kuning");
        // $limitedEditor = $o->getLimiteEditor();

        if (isset($_GET['k']) && strlen($_GET['k']) > 1) {
            $key = $_GET['k'];
            $subtitle = "Contains '$key'";
        }
        else {
            $key = "";
            $subtitle = "List of $title";
        }
        $dataExtRel = isset($this->config->item('dataExtRelation')[$this->className]["images"]) ? $this->config->item('dataExtRelation')[$this->className]["images"] : array();
        $arrayItem = array();
        // if (sizeof($tmp) > 0) {//===ada data

        // if ($this->uri->segment(3) > 0) {
        //     $rowCounter = ($limit_per_page * ($this->uri->segment(3) - 1));
        // }
        // else {
        //     $rowCounter = 0;
        // }

        // -----begin----------
        $dataRel = isset($this->config->item('dataRelation')[$this->className]) ? $this->config->item('dataRelation')[$this->className] : array();
        //<editor-fold desc="data proposal data">
        $this->load->model("Mdls/" . "MdlDataTmp");
        $tData = new MdlDataTmp();
        $tData->addFilter("mdl_name='" . $this->className . "'");
        $tmpTmp = $tData->lookupAll()->result();
        cekHitam($this->db->last_query());
        $dataProposals = array();
        if (sizeof($tmpTmp) > 0) {
            foreach ($tmpTmp as $row) {
                $mdlName = $row->mdl_name;
                $dataAccess = isset($this->config->item('heDataBehaviour')[$mdlName]) ? $this->config->item('heDataBehaviour')[$mdlName] : array(
                    "viewers"       => array(),
                    "creators"      => array(),
                    "creatorAdmins" => array(),
                    "updaters"      => array(),
                    "updaterAdmins" => array(),
                    "deleters"      => array(),
                    "deleterAdmins" => array(),
                );
                //                $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
                $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
                $allowView = false;
                $allowCreate = false;
                $allowEdit = false;
                $allowDelete = false;
                foreach ($mems as $mID) {
                    if (in_array($mID, $dataAccess['viewers'])) {
                        $allowView = true;
                    }
                    if (in_array($mID, $dataAccess['creators'])) {
                        $allowCreate = true;
                    }
                    if (in_array($mID, $dataAccess['updaters'])) {
                        $allowEdit = true;
                    }
                    if (in_array($mID, $dataAccess['deleters'])) {
                        $allowDelete = true;
                    }
                }

                if ($allowView || $allowCreate) {
                    if (!isset($dataProposals[$mdlName])) {
                        $dataProposals[$mdlName] = array();
                    }
                    $dataProposals[$mdlName][] = array(
                        "id"           => $row->_id,
                        "label"        => $row->mdl_label,
                        "origID"       => $row->orig_id,
                        "proposer"     => $row->proposed_by_name,
                        "date"         => $row->proposed_date,
                        "content"      => unserialize(base64_decode($row->content)),
                        "propose_type" => $row->propose_type,
                    );
                }
            }
        }

        //</editor-fold>

        $arrayHeader = $this->listedFields;
        if (sizeof($dataExtRel) > 0) {
            $arrayHeader["images"] = "images";
        }
        $arrayHeader["action"] = "action";
        if (sizeof($dataRel) > 0) {
            $arrayHeader["option"] = "<span class='glyphicon glyphicon-th-list'></span>";
        }

        // ==== region
        $this->load->model("Mdls/" . "MdlDataHistory");
        $h = new MdlDataHistory();
        $arrayHeaderHist = $h->getListedFields();
        $h->addFilter("mdl_name='" . $this->className . "'");
        // $h->addFilter("toko_id='" . $this->session->login['toko_id'] . "'");
        $tmpH = $h->lookupRecentHistories()->result();
        //         arrPrint($this->session->login);
        // showLast_query("biru");

        $arrayRecap = array();
        if (sizeof($tmpH) > 0) {
            $tmpO = new $this->className();
            foreach ($tmpH as $row) {
                $tmpRecap = array();
                $content = unserialize(base64_decode($row->new_content));

                foreach ($this->listedFields as $fName => $label) {
                    $fieldLabel = isset($content[$fName]) ? $content[$fName] : "";
                    if (array_key_exists($fName, $this->relations)) {
                        $fieldLabel = isset($this->relationPairs[$fName][$fieldLabel]) ? "<span class='fa fa-folder-o'></span> " . $this->relationPairs[$fName][$fieldLabel] : "unknown rel";
                    }
                    else {
                        $fieldLabel = isset($row->$fName) ? $row->$fName : (isset($content[$fName]) ? $content[$fName] : "unknown rel#");
                    }
                    $type_data = isset($fields[$fName]['type']) ? $fields[$fName]['type'] : "varchar";

                    switch ($type_data) {
                        default:
                            $tmpRecap[$fName] = nl2br($fieldLabel);
                            break;
                        case "blob":
                            die(__LINE__);
                            $imageDecode = blobDecode($fieldLabel);
                            $imageAvail = base64_encode($imageDecode['image']);
                            $img_scr = "src='data:image/jpeg;base64,$imageAvail'";
                            //                            $img_scr = "src='$fieldLabel'";
                            $fblob_data = "<div><img $img_scr class='img-responsive' width='150px' ></div>";
                            $tmpRecap[$fName] = $fblob_data;
                            //                            $tmpRecap
                            break;
                        case "image":
                            $img_scr = "src='$fieldLabel'";
                            $fblob_data = "<div><img $img_scr class='img-responsive' width='150px' ></div>";
                            $tmpRecap[$fName] = $fblob_data;
                            break;
                    }


                }
                $arrayRecap[] = $tmpRecap;
            }
        }
        // ==== endregion

        // arrPrintKuning($arrayRecap);
        $arrayProgressLabel['date'] = "date";
        $arrayProgressLabel['propose_type'] = "proposal type";
        $arrayProgressLabel = $arrayProgressLabel + $arrayHeader;
        $arrayRecapLabel = $arrayHeader;
        // cekHere(ipadd());
        // if(ipadd() == "202.65.117.72"){
        //
        //     $arrayRecapLabel = $arrayHeaderHist;
        // }

        $arrayProgressLabel['action'] = "action";

        //<editor-fold desc="tampilan approval data">
        $arrItemTmp = array();
        if (sizeof($dataProposals) > 0) {


            foreach ($dataProposals as $mdlName => $pSpec) {
                $this->load->model("Mdls/" . $mdlName);
                $o2 = new $mdlName();
                $listedFields = $this->listedFields;
                foreach ($pSpec as $dSpec) {
                    //                    echo "mulai mengiterasi kolom .. <br>";
                    $tmpItemTmp = array();
                    $dataStatus = $dSpec['origID'] > 0 ? "pembaruan" : "data baru";

                    foreach ($listedFields as $fName => $fLabel) {
                        $fRealName = $fName;
                        //                        $tmpItemTmp[$fName] = $dSpec['content'][$fRealName];
                        $fieldLabel = isset($dSpec['content'][$fRealName]) ? $dSpec['content'][$fRealName] : "";
                        //===if related
                        if (array_key_exists($fName, $this->relations)) {
                            $fieldLabel = isset($this->relationPairs[$fName][$fieldLabel]) ? $this->relationPairs[$fName][$fieldLabel] : "unknown rel";
                        }
                        $tmpItemTmp[$fName] = $fieldLabel;
                    }


                    $approvalClick = "BootstrapDialog.closeAll();
                    BootstrapDialog.show(
                                   {
                                        title:'Data " . $dSpec['label'] . " &raquo; Setujui $dataStatus ',
                                        message: $('<div></div>').load('" . base_url() . "Data/editFrom/" . $dSpec['label'] . "/" . $dSpec['id'] . "/" . $dSpec['origID'] . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        closable:true,
                                        }
                                        );";

                    $tmpItemTmp["date"] = $dSpec['date'];
                    $tmpItemTmp["propose_type"] = $dSpec['propose_type'];
                    $tmpItemTmp["action"] = "<a class='btn btn-primary btn-block' href='JavaScript:void(0);' onclick =\"$approvalClick;\">review</a>";
                    $tmpItemTmp["history"] = "";
                    $arrItemTmp[] = $tmpItemTmp;
                }

            }

        }
        //</editor-fold>


        unset($arrayProgressLabel['history']);
        unset($arrayRecapLabel['action']);
        unset($arrayRecapLabel['history']);
        unset($arrayRecapLabel['id']);
        // -----end-----------

        $addLink_mdl = method_exists($o, "linkAddData") ? MODUL_PATH . $o->linkAddData() : "hdf";
        // cekHere($this->className);
        // cekHijau("$addLink_mdl");
        $addLink_local = MODUL_PATH . get_class($this) . "/add/$ctrlName";
        $addLink = method_exists($o, "linkAddData") ? $addLink_mdl : $addLink_local;
        $addManyLink = MODUL_PATH . get_class($this) . "/addMany/$ctrlName";
        $addManyLink = "";
        if ($this->allowCreate) {
            // $jmlDataNow = 3;
            $btn_disabled = ($maximumData > 0) && ($maximumData <= $jmlDataNow) ? "disabled" : "";
            $addClick = "
                        BootstrapDialog.show(
                           {
                                title:'New $title',
                                message: $('<div></div>').load('" . $addLink . "'),
                                size: BootstrapDialog.SIZE_WIDE,
                                draggable:false,
                                closable:true,
                            }
                        );";
            $strAddLink = "";
            $strAddLink .= "<div class='btn-group'>";
            $strAddLink .= "<button type='button' $btn_disabled href='JavaScript:void(0)' class=\" btn btn-primary\" onClick=\"$addClick\" data-toggle='tooltip' data-placement='top' title='Add new $title' class='btn btn-circle btn-xs btn-primary bg-blue-gradient'><span class='glyphicon glyphicon-plus'></button>";
            //            $strAddLink .= in_array("addMany", $limitedEditor) ? "" : "<button href='JavaScript:void(0)' class='btn btn-success' onclick=\"location.href = '$addManyLink';\"  data-toggle='tooltip' data-placement='top' title='Add many entries of $title'><span class='glyphicon glyphicon-plus-sign'></span></button>";
            if (isset($buttonnActionAll)) {
                $paramSett = $buttonnActionAll['setting'];
                $btn_label = $paramSett['label'];
                $btn_mdl = $paramSett['mdl'];
                $btn_events = str_replace("{base_url}", base_url(), $paramSett['events']);

                $strAddLink .= "<button type='button' $btn_disabled href='javascript:void(0)' class=\" btn btn-warning\" data-toggle='tooltip' data-placement='top' title='$btn_label' $btn_events><span class='fa fa-gear'></button>";
            }
            $strAddLink .= "</div class='btn-group'>";

        }
        else {
            $strAddLink = "";
        }
        if ($this->allowEdit) {
            // $strEditLink = in_array("addMany", $limitedEditor) ? "" : "<button href='JavaScript:void(0)' class=\" btn btn-default\" onClick=\"location.href='" . base_url() . get_class($this) . "/editMany/$ctrlName/" . $this->uri->segment(4) . "'\" data-toggle='tooltip' data-placement='top' title='Modify all $title in this page' class='btn btn-circle btn-xs btn-primary bg-blue-gradient'><span class='glyphicon glyphicon-pencil'></button>";
            $strEditLink = "";
            $strBulkEditLink = "";
        }
        else {
            $strEditLink = "";
            $strBulkEditLink = "";
        }


        // cekBiru($this->className);
        // switch ($this->className) {
        //     case "MdlRakCabang":
        //     case "MdlProduk":
        if (method_exists($o, "getNavFilters")) {
            $navFilter = $o->getNavFilters();
            $strCase = $navFilter['mdlFilter'];
            $strLabel = $navFilter['label'];
            $strKolom = $navFilter['kolomKey'];

            // $this->load->model("Mdls/MdlFolderProduk");
            $this->load->model("Mdls/$strCase");
            // $fo = new MdlFolderProduk();
            cekBiru($strCase);
            if (my_cabang_id() > 0) {
                // $disabled = "disabled";
                // $folderId = my_cabang_id();
                // $this->db->where("id",my_cabang_id());

                switch ($strCase) {
                    case "MdlRakCabang":
                        $this->db->where("cabang_id", my_cabang_id());
                        break;
                    case "MdlCustomerTipe":
                        break;
                    case "MdlCabang":
                        $this->db->where("id", my_cabang_id());
                        break;
                    default:
                        $this->db->where("cabang_id", my_cabang_id());
                        break;
                }
            }
            $fo = new $strCase();
            $this->db->where("toko_id", my_toko_id());
            $ftmp = $fo->lookupAll()->result();
            // showLast_query("kuning");
            // cekBiru($strCase ." ". my_cabang_id());
            //             arrPrint($ftmp);
            $link_self = MODUL_PATH . "" . $this->segment_2 . "/" . $this->segment_3 . "/" . $this->segment_4;
            $disabled = "";
            switch ($strCase) {
                case "MdlRakCabang":

                    break;
                default:
                    if (my_cabang_id() > 0) {
                        // $disabled = "disabled";
                        // $folderId = my_cabang_id();
                    }
                    break;
            }

            $folder_link = "&nbsp; Terapkan filter $strLabel <select class='btn btn-info' $disabled onchange=\"location.href='$link_self?id='+this.value\">";
            $folder_link .= "<option value=''>--pilih $strLabel--</option>";
            // $folder_link .= "<option>$link_self</option>";
            foreach ($ftmp as $fitem) {
                $fSelected = $fitem->id == $folderId ? "selected" : "";
                $namanya = isset($fitem->nama) ? $fitem->nama : $fitem->name;
                $folder_link .= "<option value='" . $fitem->id . "' $fSelected>" . $namanya . "</option>";
            }
            $folder_link .= "</select>&nbsp;";

            /** ------------------------------------
             * btn sync nama-nama harus ada methodnya dulu di tiap modelnya
             * -----------------------------------*/
            if (method_exists($o, "paramSyncNamaNama")) {
                $link_sync = MODUL_PATH . "Data/doSyncNamaNama/" . $this->segment_3 . "/" . $this->segment_4;
                $folder_link .= "<button type='button' class='btn btn-warning' onclick=\"btn_result('$link_sync');\" data-toggle='tooltip' title='sync. Label'><i class='fa fa-refresh'></i></button>&nbsp;";
            }
        }
        else {
            $folder_link = "";
        }

        if (method_exists($o, "getApiData")) {
            $syncQuery = array();
            if (isset($_GET["id"]) && $_GET["id"] != "") {
                $syncQuery["id"] = $_GET["id"];
            }
            if ($this->className != "MdlCustomer" && isset($_GET["sync_limit"]) && $_GET["sync_limit"] != "") {
                $syncQuery["limit"] = $_GET["sync_limit"];
            }
            if (isset($_GET["q"]) && $_GET["q"] != "") {
                $syncQuery["q"] = $_GET["q"];
            }
            if (isset($_GET["dc_id"]) && $_GET["dc_id"] != "") {
                $syncQuery["dc_id"] = $_GET["dc_id"];
            }
            if ($this->className == "MdlCustomer") {
                $syncStartUrl = MODUL_PATH . "Data/sync_job_start/" . $this->segment_4;
                $syncProcessUrl = MODUL_PATH . "Data/sync_job_process";
                $syncIdAttr = isset($_GET["id"]) ? htmlspecialchars($_GET["id"], ENT_QUOTES) : "";
                $syncQAttr = isset($_GET["q"]) ? htmlspecialchars($_GET["q"], ENT_QUOTES) : "";
                $syncDcAttr = isset($_GET["dc_id"]) ? htmlspecialchars($_GET["dc_id"], ENT_QUOTES) : "";
                $folder_link .= "<button type='button' id='btn_sync_customer_job' class='btn btn-danger' data-sync-start='$syncStartUrl' data-sync-process='$syncProcessUrl' data-sync-id='$syncIdAttr' data-sync-q='$syncQAttr' data-sync-dc='$syncDcAttr' data-toggle='tooltip' title='API syncron'><i class='fa fa-refresh'></i> Syncron Now</button>&nbsp;";
                $folder_link .= <<<'HTML'
<script>
(function () {
    if (window.__customerSyncJobBound) {
        return;
    }
    window.__customerSyncJobBound = true;

    $(document).off('click.customerSyncJob', '#btn_sync_customer_job').on('click.customerSyncJob', '#btn_sync_customer_job', function (e) {
        e.preventDefault();
        var $btn = $(this);
        if ($btn.data('running') == 1) {
            return;
        }
        $btn.data('running', 1).prop('disabled', true);

        if (typeof open_holdon === 'function') {
            open_holdon();
        }

        var startUrl = $btn.attr('data-sync-start');
        var processRoot = $btn.attr('data-sync-process');
        var params = {};
        var sid = $btn.attr('data-sync-id');
        var sq = $btn.attr('data-sync-q');
        var sdc = $btn.attr('data-sync-dc');
        var urlSearch = (window.location && window.location.search) ? window.location.search : '';
        if (sid !== '') {
            params.id = sid;
        }
        if (sq !== '') {
            params.q = sq;
        }
        if (sdc !== '') {
            params.dc_id = sdc;
        }
        var syncBatchFromUrl = null;
        try {
            var qs = new URLSearchParams(urlSearch);
            var b = qs.get('sync_batch');
            if (b !== null && b !== '') {
                syncBatchFromUrl = parseInt(b, 10);
                if (!isNaN(syncBatchFromUrl) && syncBatchFromUrl > 0) {
                    params.batch = syncBatchFromUrl;
                } else {
                    syncBatchFromUrl = null;
                }
            }
        } catch (e) {}

        var resetBtn = function () {
            $btn.data('running', 0).prop('disabled', false);
        };
        var buildRequestFailText = function (failCtx, baseText) {
            var failText = baseText;
            var xhr = (failCtx && failCtx.xhr) ? failCtx.xhr : failCtx;
            if (!xhr) {
                return failText;
            }

            var statusPart = xhr.status ? (' [HTTP ' + xhr.status + ']') : '';
            var bodyText = xhr.responseText ? String(xhr.responseText) : '';
            var parsedMessage = '';
            if (bodyText !== '') {
                try {
                    var bodyJson = JSON.parse(bodyText);
                    if (bodyJson && bodyJson.message) {
                        parsedMessage = String(bodyJson.message);
                        if (bodyJson.last_step) {
                            parsedMessage += ' (step: ' + bodyJson.last_step + ')';
                        }
                    }
                } catch (e) {}

                if (parsedMessage === '') {
                    bodyText = bodyText.replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();
                    if (bodyText.length > 220) {
                        bodyText = bodyText.substring(0, 220) + '...';
                    }
                    parsedMessage = bodyText;
                }
            }

            failText += statusPart;
            if (parsedMessage !== '') {
                failText += ' - ' + parsedMessage;
            }
            if (failCtx && failCtx.url) {
                failText += ' | URL: ' + failCtx.url;
            }
            if (failCtx && typeof failCtx.rawLength !== 'undefined') {
                failText += ' | raw_len:' + failCtx.rawLength;
            }
            if (failCtx && failCtx.rawSample) {
                failText += ' | sample:' + failCtx.rawSample;
            }
            if (failCtx && failCtx.textStatus) {
                failText += ' | textStatus:' + failCtx.textStatus;
            }
            if (failCtx && failCtx.errorThrown) {
                failText += ' | error:' + failCtx.errorThrown;
            }
            return failText;
        };
        var tryParseJsonResponse = function (xhr) {
            if (!xhr || !xhr.responseText) {
                return null;
            }
            var raw = String(xhr.responseText);
            raw = raw.replace(/^\uFEFF/, '');
            raw = raw.replace(/[\u0000-\u0008\u000B\u000C\u000E-\u001F]/g, '');
            raw = raw.trim();
            if (raw === '') {
                return null;
            }

            var tryParse = function (text) {
                if (!text || text === '') {
                    return null;
                }
                try {
                    return JSON.parse(text);
                } catch (e) {
                    return null;
                }
            };

            var parsed = tryParse(raw);
            if (parsed) {
                return parsed;
            }

            // Fallback: ekstrak kandidat JSON dari teks yang tercampur output lain.
            var startObj = raw.indexOf('{');
            var endObj = raw.lastIndexOf('}');
            if (startObj !== -1 && endObj > startObj) {
                parsed = tryParse(raw.substring(startObj, endObj + 1));
                if (parsed) {
                    return parsed;
                }
            }

            var startArr = raw.indexOf('[');
            var endArr = raw.lastIndexOf(']');
            if (startArr !== -1 && endArr > startArr) {
                parsed = tryParse(raw.substring(startArr, endArr + 1));
                if (parsed) {
                    return parsed;
                }
            }

            return null;
        };
        var doneWithMessage = function (title, text, type, onOk) {
            if (typeof close_holdon === 'function') {
                close_holdon();
            }
            resetBtn();
            if (typeof swal === 'function') {
                var handled = false;
                var done = function () {
                    if (handled) {
                        return;
                    }
                    handled = true;
                    if (typeof onOk === 'function') {
                        onOk();
                    }
                };
                try {
                    var ret = swal({
                        title: title,
                        text: text,
                        type: type,
                        confirmButtonText: 'OK'
                    });
                    if (ret && typeof ret.then === 'function') {
                        ret.then(function () {
                            done();
                        }, function () {
                            done();
                        });
                        return;
                    }
                } catch (e) {}
                try {
                    swal({
                        title: title,
                        text: text,
                        type: type
                    }, function () {
                        done();
                    });
                    return;
                } catch (e2) {
                    swal(title, text, type);
                    done();
                }
            }
            else {
                alert(text);
                if (typeof onOk === 'function') {
                    onOk();
                }
            }
        };
        var requestJsonText = function (url, params, onSuccess, onFail) {
            $.ajax({
                url: url,
                method: 'GET',
                data: params,
                dataType: 'text',
                cache: false
            }).done(function (rawText, textStatus, xhr) {
                var parsed = tryParseJsonResponse({responseText: rawText});
                if (parsed) {
                    onSuccess(parsed, xhr);
                    return;
                }
                if (typeof onFail === 'function') {
                    var rawStr = rawText === null || typeof rawText === 'undefined' ? '' : String(rawText);
                    var sample = rawStr.replace(/\s+/g, ' ').trim();
                    if (sample.length > 220) {
                        sample = sample.substring(0, 220) + '...';
                    }
                    if (xhr) {
                        xhr.responseText = rawText;
                    }
                    onFail({
                        xhr: xhr,
                        url: url,
                        rawText: rawStr,
                        rawLength: rawStr.length,
                        rawSample: sample,
                        textStatus: textStatus,
                        phase: 'parse'
                    });
                }
            }).fail(function () {
                var xhr = arguments.length > 0 ? arguments[0] : null;
                var textStatus = arguments.length > 1 ? arguments[1] : '';
                var errorThrown = arguments.length > 2 ? arguments[2] : '';
                if (typeof onFail === 'function') {
                    onFail({
                        xhr: xhr,
                        url: url,
                        textStatus: textStatus,
                        errorThrown: errorThrown,
                        phase: 'ajax'
                    });
                }
            });
        };
        var senderApiUrl = '';
        var setSenderApiUrl = function (payload) {
            if (!payload || typeof payload !== 'object') {
                return;
            }
            var candidate = '';
            if (payload.sender_api_url) {
                candidate = payload.sender_api_url;
            } else if (payload.remote_api_url) {
                candidate = payload.remote_api_url;
            } else if (payload.remote_api_url_probe) {
                candidate = payload.remote_api_url_probe;
            } else if (payload.last_remote_url) {
                candidate = payload.last_remote_url;
            } else if (payload.active_job_payload && payload.active_job_payload.last_remote_url) {
                candidate = payload.active_job_payload.last_remote_url;
            }
            if (candidate) {
                senderApiUrl = String(candidate);
            }
        };
        var basePollDelay = 400;
        var maxFailRetry = 4;
        var failRetryCount = 0;
        var startHandled = false;

        var handleProcessPayload = function (st, pollFn) {
            setSenderApiUrl(st);
            if (!st || !st.status) {
                var msg = (st && st.message) ? st.message : 'Gagal memproses sinkronisasi';
                if (st && st.sender_api_url) {
                    msg += ' | sender_api_url: ' + st.sender_api_url;
                } else if (st && st.remote_api_url) {
                    msg += ' | sender_api_url: ' + st.remote_api_url;
                } else if (senderApiUrl) {
                    msg += ' | sender_api_url: ' + senderApiUrl;
                }
                doneWithMessage('GAGAL', msg, 'error');
                return false;
            }
            if (st.is_finished) {
                doneWithMessage('BERHASIL', st.message ? st.message : 'Sinkronisasi selesai', 'success', function () {
                    window.location.reload();
                });
                return true;
            }
            failRetryCount = 0;
            setTimeout(pollFn, basePollDelay);
            return true;
        };

        var startPolling = function (jobId, batchSize) {
                var poll = function () {
                var processParams = {batch: batchSize};
                requestJsonText(processRoot + '/' + jobId, processParams, function (st) {
                    handleProcessPayload(st, poll);
                }, function (failCtx) {
                    var xhr = failCtx && failCtx.xhr ? failCtx.xhr : null;
                    failRetryCount++;
                    if (failRetryCount <= maxFailRetry) {
                        var nextDelay = basePollDelay * Math.pow(2, failRetryCount);
                        if (nextDelay > 4000) {
                            nextDelay = 4000;
                        }
                        setTimeout(poll, nextDelay);
                        return;
                    }

                    // Beberapa server mengirim JSON valid tapi jQuery masuk fail (parsererror).
                    // Jika HTTP 200 dan body JSON valid, tetap lanjutkan alur.
                    if (xhr && xhr.status === 200) {
                        var parsedProcess = tryParseJsonResponse({
                            responseText: (failCtx && typeof failCtx.rawText !== 'undefined') ? failCtx.rawText : xhr.responseText
                        });
                        if (parsedProcess) {
                            handleProcessPayload(parsedProcess, poll);
                            return;
                        }
                    }

                    var failText = buildRequestFailText(failCtx, 'Request batch sinkronisasi gagal');
                    if (senderApiUrl) {
                        failText += ' | sender_api_url:' + senderApiUrl;
                    }
                    doneWithMessage('GAGAL', failText, 'error');
                });
            };
            poll();
        };

        var handleStartPayload = function (res) {
            setSenderApiUrl(res);
            if (!res || !res.status) {
                var msg = (res && res.message) ? res.message : 'Gagal memulai sinkronisasi';
                if (res && res.sender_api_url) {
                    msg += ' | sender_api_url: ' + res.sender_api_url;
                } else if (res && res.remote_api_url_probe) {
                    msg += ' | sender_api_url: ' + res.remote_api_url_probe;
                } else if (senderApiUrl) {
                    msg += ' | sender_api_url: ' + senderApiUrl;
                }
                doneWithMessage('GAGAL', msg, 'error');
                return false;
            }

            var jobId = res.job_id;
            var batchSize = res.batch_size ? res.batch_size : 50;
            if (syncBatchFromUrl && syncBatchFromUrl > 0) {
                batchSize = syncBatchFromUrl;
            }
            failRetryCount = 0;
            startPolling(jobId, batchSize);
            return true;
        };

        var finalizeStart = function (res) {
            if (startHandled) {
                return;
            }
            startHandled = true;
            handleStartPayload(res);
        };

        requestJsonText(startUrl, params, function (res) {
            finalizeStart(res);
        }, function (failCtx) {
            var xhr = failCtx && failCtx.xhr ? failCtx.xhr : null;

            // Jika HTTP 200 namun parsererror, ambil body mentah dan parse manual.
            if (xhr && xhr.status === 200) {
                var parsedStart = tryParseJsonResponse({
                    responseText: (failCtx && typeof failCtx.rawText !== 'undefined') ? failCtx.rawText : xhr.responseText
                });
                if (parsedStart) {
                    finalizeStart(parsedStart);
                    return;
                }
            }

            var failText = buildRequestFailText(failCtx, 'Request mulai sinkronisasi gagal');
            if (senderApiUrl) {
                failText += ' | sender_api_url:' + senderApiUrl;
            }
            doneWithMessage('GAGAL', failText, 'error');
        });
    });
})();
</script>
HTML;
            }
            else {
                $queryString = count($syncQuery) > 0 ? "?" . http_build_query($syncQuery) : "";
                $link_sync = MODUL_PATH . "Data/syncro_data/" . $this->segment_4 . "/" . $this->segment_4 . $queryString;
                $folder_link .= "<button type='button' class='btn btn-danger' onclick=\"btn_result('$link_sync');open_holdon();\" data-toggle='tooltip' title='API syncron'><i class='fa fa-refresh'></i> Syncron Now</button>&nbsp;";
            }
        }

        if (method_exists($o, 'lookupNonAktif')) {
            $link_non_aktif = base_url() . "statik/Data/viewNonAktif/" . $this->segment_4;
            $folder_link .= "<a class='btn btn-grey bg-grey-1' href='$link_non_aktif' data-toggle='modal' data-target='#myModal' onclick=\"#\" data-toggle='tooltip' title='syncron'><span class='fa fa-eye'> $ctrlName Non Aktif</span></a>&nbsp;";
        }
        //        arrPrint($arrayRecapLabel);

        //redirect template

        // if ($ctrlName == "Meja") {
        //     $mode_switch = "pengaturan_meja";
        //     $view_switch = "pengaturan_meja";
        //
        //     $toko_id = $this->session->login['toko_id'];
        //
        //     $this->load->model("Mdls/MdlMeja");
        //     $mj = new MdlMeja();
        //
        //     $id_meja = isset($_GET['m']) ? $_GET['m'] : "";
        //     $baris_meja = isset($_GET['b']) ? $_GET['b'] : "";
        //     $kolom_meja = isset($_GET['k']) ? $_GET['k'] : "";
        //
        //     if (isset($_GET['m'])) {
        //
        //         $datas = array(
        //             "baris_meja" => $baris_meja,
        //             "kolom_meja" => $kolom_meja,
        //         );
        //         $up = $mj->updateData(array("id" => $id_meja), $datas);
        //
        //         $file = fopen(__DIR__ . "/eusvc/sync/MdlMeja_" . $this->session->login['toko_id'] . ".txt", "w");
        //         echo fwrite($file, json_encode(array("datetime" => date("Y-m-d H:i:s"))));
        //         fclose($file);
        //
        //     }
        //     else {
        //         $mj->addFilter("toko_id='$toko_id'");
        //         $mjTmp = $mj->lookupAll()->result();
        //         $arrMeja = array();
        //         foreach ($mjTmp as $k => $mj) {
        //             $arrMeja[$mj->id] = $mj;
        //         }
        //         $data = array(
        //             "title"     => $this->segment_3,
        //             "subTitle"  => "Management Table",
        //             'mode'      => "pengaturan_meja",
        //             'segment_3' => $this->segment_3,
        //             'mdl'       => $this->className,
        //             "error"     => $this->session->errMsg,
        //             "arrMeja"   => $arrMeja,
        //         );
        //         $this->load->view('pengaturan_meja', $data);
        //     }
        //
        // }
        // elseif ($ctrlName == "Struk") {
        //
        //     $mode_switch = "pengaturan_struk";
        //     $view_switch = "pengaturan_struk";
        //
        //     $toko_id = $this->session->login['toko_id'];
        //
        //     $this->load->model("Mdls/Mdl" . $ctrlName);
        //     $mj = new MdlStruk();
        //
        //     $mj->addFilter("toko_id='$toko_id'");
        //     $mjTmp = $mj->lookupAll()->result();
        //     $arrStruk = array();
        //     foreach ($mjTmp as $k => $mj) {
        //         $arrStruk[$mj->id] = $mj;
        //     }
        //     $data = array(
        //         "title"     => $this->segment_3,
        //         "subTitle"  => "PENGATURAN STRUK",
        //         'mode'      => $mode_switch,
        //         'segment_3' => $this->segment_3,
        //         'mdl'       => $this->className,
        //         "error"     => $this->session->errMsg,
        //         "arrStruk"  => $arrStruk,
        //     );
        //     $this->load->view($view_switch, $data);
        //
        // }
        // else {
        $mode_switch = "data_table";
        $view_switch = "data";

        //cekHere($this->className);
        $data = array(
            "title"               => $this->segment_4 == "DtaBiayaProduksi" ? "Jasa / biaya project" : $this->segment_4,
            'mode'                => $mode_switch,
            'segment_3'           => $this->segment_4,
            'mdl'                 => $this->className,
            // --
            "error"               => $this->session->errMsg,
            "strDataProposeTitle" => "<span class='glyphicon glyphicon-alert blink'></span>&nbsp; <span class='tebal'>approval needed</span>",
            "arrayProgressLabels" => $arrayProgressLabel,
            "arrayOnProgress"     => $arrItemTmp,
            // ---
            "strDataHistTitle"    => "<span class='glyphicon glyphicon-time'></span> recent data updates",
            "linkStr"             => "",
            "add_link"            => $strAddLink,
            "edit_link"           => $strEditLink,
            "bulk_edit_link"      => $strBulkEditLink,
            "folder_link"         => isset($folder_link) ? $folder_link : "",
            "filterId"            => $folderId,
            "filterId_str"        => $folderId > 0 ? "?fid=$folderId" : "",
            // -----
            "arrayRecapLabels"    => $arrayRecapLabel,
            "arrayRecap"          => $arrayRecap,
            "maximumData"         => $maximumData,
            "jmlDataNow"          => $jmlDataNow,

        );
        // arrPrint($data);

        $this->load->view($view_switch, $data);
        // }

    }

    /** -------------------------------
     * data dipangil oleh ajax
     * ------------------------------*/
    function fetch_data()
    {
        $jsonResponseSent = false;
        header('Content-Type: application/json; charset=utf-8');
        header('X-Statik-Fetch-Handler: Data.php');

        register_shutdown_function(function () use (&$jsonResponseSent) {
            if ($jsonResponseSent) {
                return;
            }

            $lastError = error_get_last();
            $fatalTypes = array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR);
            $isFatal = is_array($lastError) && in_array($lastError['type'], $fatalTypes, true);
            $stray = ob_get_contents();
            if ($stray !== false) {
                @ob_end_clean();
            }

            $fallback = array(
                "draw"            => isset($_POST['draw']) ? intval($_POST['draw']) : 0,
                "recordsTotal"    => 0,
                "recordsFiltered" => 0,
                "data"            => array(),
                "error"           => $isFatal
                    ? ("Fatal error: " . $lastError['message'] . " @ " . $lastError['file'] . ":" . $lastError['line'])
                    : "Invalid/empty response intercepted before JSON output"
            );
            echo json_encode($fallback);
        });

        // Buffer output agar debug/print tidak merusak response JSON DataTables.
        ob_start();
        //                 print_r($_POST);

        //        ini_set('display_errors', 0);
        //        if (version_compare(PHP_VERSION, '5.3', '>=')) {
        //            error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
        //        }
        //        else {
        //            error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
        //        }
        /*untuk ngelimit stok*/
        if (ipadd() == MGK_LIVE) {
            // $this->db->limit(5);
            // $_POST['length'] = 10;
        }
        $mdlName = isset($_POST['mdl']) ? $_POST['mdl'] : (isset($_GET['mdl']) ? $_GET['mdl'] : $this->className);
        if (!$mdlName) {
            echo json_encode(array("error" => "Model name not specified"));
            die();
        }
        $foldId = isset($_POST['fid']) ? $_POST['fid'] : (isset($_GET['fid']) ? $_GET['fid'] : "");
        $ctrlName = $this->segment_4;
        $tokoID = isset($this->session->login['toko_id']) ? $this->session->login['toko_id'] : null;

        /* ---------------------------------------------------------------
         * mendisable button delete direlasikan dengan data transaksi
         * pengaturan ditaruh di dataBehavior contoh penerapan ada pada key MdlProduk dan MdlCustomer
         * ---------------------------------------------------------------*/
        $dataBehavior = $this->config->item('heDataBehaviour');
        $paramMutasi = isset($dataBehavior[$mdlName]['rel_deleters']) ? $dataBehavior[$mdlName]['rel_deleters'] : array();
        $paramStoks = isset($dataBehavior[$mdlName]['rel_editors']) ? $dataBehavior[$mdlName]['rel_editors'] : array();
        $paramInfo = isset($dataBehavior[$mdlName]['rel_info']) ? $dataBehavior[$mdlName]['rel_info'] : array();
        $data_aktif = array();
        $data_aktif_locker = array();
        $data_aktif_locker_filter = array();
        if (sizeof($paramMutasi) > 0) {
            $relDir = $paramMutasi['dirModel'];
            $relMdl = $paramMutasi['baseModel'];
            $relCondites = $paramMutasi['condites'];
            $relGrouping = $paramMutasi['grouping'];
            $relSelected = $paramMutasi['selecteds'];
            $relStrukture = $paramMutasi['data_strukture'];

            $this->load->model($relDir . $relMdl);
            $pc = new $relMdl();

            // arrPrint($relCondites);
            $this->db->select($relSelected);
            $this->db->group_by($relGrouping);
            $data_cache = $pc->lookupByCondition($relCondites)->result();
            // showLast_query("lime");

            // $data_aktif = array();
            foreach ($data_cache as $item) {
                foreach ($relStrukture as $itemKey => $itemValues) {
                    if (!is_array($itemValues)) {
                        $data_aktif[$item->$itemKey] = $item->$itemValues;
                    }
                    else {
                        $data_aktif[$item->$itemKey][$itemValues] = $item->$itemValues;
                    }
                }
            }

            // arrPrintPink($data_aktif);
            // cekHijau();
        }
        // ---------------------------------------------------------------
        // mati_disini(__LINE__);
        // cekAlert($paramStoks);
        // print_r($paramStoks);

        if (!isset($this->$mdlName) && $mdlName) {
            if (file_exists(APPPATH . "models/Mdls/$mdlName.php")) {
                $this->load->model("Mdls/" . $mdlName);
            }
            if (class_exists($mdlName)) {
                $this->$mdlName = new $mdlName();
            }
        }
        if (!isset($this->$mdlName)) {
            $jsonNoModel = json_encode(array(
                "draw"            => isset($_POST['draw']) ? intval($_POST['draw']) : 0,
                "recordsTotal"    => 0,
                "recordsFiltered" => 0,
                "data"            => array(),
                "error"           => "Model tidak ditemukan: " . $mdlName
            ));
            while (ob_get_level() > 0) {
                @ob_end_clean();
            }
            $jsonResponseSent = true;
            exit($jsonNoModel);
        }
        $buttonDatas = isset($this->$mdlName) ? $this->$mdlName->getBtnActions() : array();
        if ($mdlName != "MdlMenuGroup") {
            // $this->db->where_in("toko_id", "$tokoID");
        }

        if ($foldId > 0) {
            if (method_exists($this->$mdlName, "getNavFilters")) {
                $navFilter = $this->$mdlName->getNavFilters();
                $strCase = $navFilter['mdlFilter'];
                $strLabel = $navFilter['label'];
                $strKolom = $navFilter['kolomKey'];

                $this->db->where($strKolom, "$foldId");
            }
        }

        /** ---------------------------------------
         * #custom aliasing controler untuk title pada modal editor
         * ---------------------------*/
        $namaAlias = array(
            "Supplier"    => "Vendor",
            "LokasiIndex" => "Lokasi Rak",
        );

        $tdPengenal = isset($namaAlias[$ctrlName]) ? $namaAlias[$ctrlName] : $ctrlName;

        $fields = $this->$mdlName->getFields();
        $listedFields = $this->$mdlName->getListedFields();

        // DataTables index kolom dimulai dari kolom nomor (0), sedangkan MdlMother::make_query()
        // meng-offset -1. Jika index tidak valid, unset order agar fallback aman.
        if (isset($_POST['order'][0]['column'])) {
            $orderColumnIndex = intval($_POST['order'][0]['column']);
            $maxSortableIndex = count($listedFields);
            if ($orderColumnIndex < 1 || $orderColumnIndex > $maxSortableIndex) {
                unset($_POST['order']);
            }
        }

        //handle order by chpy
        //order di MdlMother di matikan karena error
        $arrListed = array();
        $nn = 1;
        foreach ($listedFields as $key => $title) {
            $arrListed[$nn] = $key;
            $nn++;
        }
        $ord = "";
        $dir = "";
        if (isset($_REQUEST['order'][0])) {
            $ord_column = $_REQUEST['order'][0]['column'];
            $ord_dir = $_REQUEST['order'][0]['dir'];
            $ord = isset($arrListed[$ord_column]) ? $arrListed[$ord_column] : "id";
            $dir = isset($ord_dir) ? $ord_dir : "ASC";
            $this->db->order_by($ord, $dir);
        }
        else {
            // Fallback sorting harus pakai kolom yang benar-benar tersedia di model aktif.
            // Menghindari SQL error "Unknown column 'last_update'" pada model yang tidak punya kolom tsb
            // (contoh: MdlEmployee), yang menyebabkan Ajax DataTables gagal (tn/7).
            $defaultOrderField = "id";
            if (isset($fields["last_update"])) {
                $defaultOrderField = "last_update";
            }
            else if (isset($fields["nama"])) {
                $defaultOrderField = "nama";
            }
            else if (isset($fields["id"])) {
                $defaultOrderField = "id";
            }
            $this->db->order_by($defaultOrderField, "DESC");
        }
        //handle order by chpy

        $fetch_data_0 = $this->$mdlName->make_datatables();
        $dbErrFetch = $this->db->error();
        //        cekBiru($this->db->last_query());
        // arrPrintHijau($_POST);
        $indexFieldName = "id";
        // showLast_query("lime");;
        // arrPrint(sizeof($fetch_data_0));
        // arrPrint($fetch_data_0);
        // matiHere();
        $dataIds = array();
        foreach ($fetch_data_0 as $fetch_datum) {
            $dataIds[] = isset($fetch_datum->id) ? $fetch_datum->id : "";
        }
        // arrPrintPink($mdlName);
        $paireds = array();
        if (method_exists($this->$mdlName, "getPairedData")) {
            $paireds = $this->$mdlName->getPairedData();
            if (count($paireds) > 0) {
                // cekOrange(__LINE__ . " $mdlName");
                // arrPrintPink($paireds);
                $pairedKolom = array();
                foreach ($paireds as $mdl_nama => $pairAttr) {
                    $this->load->model("Mdls/$mdl_nama");
                    $im = new $mdl_nama();
                    // $images = $im->callSpecs();
                    $im->setTokoId(my_toko_id());
                    // cekBiru($pairAttr['methode']);
                    // $extDatas_0 = $images = $im->$pairAttr['methode']()->result_array();
                    $extDatas = $images = $im->$pairAttr['methode']();

                    // showLast_query("lime");
                    // arrPrintPink($extDatas_0);
                    // $extDatas = isset($extDatas_0[$pairAttr['methode_key']]) ? $extDatas_0[$pairAttr['methode_key']] : $extDatas_0;
                    //                    arrPrintHijau($extDatas);

                    if (is_array($pairAttr['kolom'])) {
                        foreach ($pairAttr['kolom'] as $ext_kolom => $ext_label) {
                            $pairedKolom[$ext_kolom]['label'] = $ext_label;
                            $pairedKolom[$ext_kolom]['default_nilai'] = $pairAttr['default_nilai'][$ext_kolom];
                        }
                    }
                    else {
                        $pairedKolom[$pairAttr['kolom']]['label'] = $pairAttr['label'];
                        // $pairedKolom[$pairAttr['kolom']]['default_nilai'] = $pairAttr['default_nilai'];
                        // $files = $pairAttr['kolom'];
                        // cekHijau("$files");
                        // $img = isset($pairAttr['kolom']) ? "<img src='$files'>" : "no";
                        // $pairedKolom[$pairAttr['kolom']]['default_nilai'] = $img;
                        // $pairedKolom[$pairAttr['kolom']]['images'][]['files'] = $img;
                        if (!empty($extDatas)) {
                            foreach ($extDatas as $im_produk_id => $extData) {
                                $pairedKolom[$pairAttr['kolom']]['images'][$im_produk_id]['files'] = $extData->files;
                            }
                        }
                    }
                }

                // arrPrintWebs($extDatas);
                // arrPrintPink($pairedKolom);
                /* ------------------------------------------------
                 * ijektor array uatama fetch_data
                 * ------------------------------------------------*/
                foreach ($fetch_data_0 as $fetch_datum) {
                    $fetch_datum_2 = isset($images[$fetch_datum->id]) ? $images[$fetch_datum->id] : array();
                    // $fetch_datum_3['images'] = $fetch_datum_2;
                    // $fetch_datum_3['qty_debet'] = $fetch_datum_2;
                    $fetch_datum_3['images'] = isset($extDatas[$fetch_datum->id]) ? $extDatas[$fetch_datum->id]->files : "";

                    // arrPrint($fetch_datum_2);
                    // arrPrintPink($fetch_datum_3);
                    // arrPrint($fetch_datum);

                    $fetch_data[] = (object)(((array)$fetch_datum) + (array)$fetch_datum_3);

                    // arrPrintWebs($fetch_data);
                    // matiHere();
                    // $fetch_data[] = 1;
                }
            }
            else {
                $pairedKolom = array();
                $fetch_data = $fetch_data_0;
            }
        }
        else {
            $pairedKolom = array();
            $fetch_data = $fetch_data_0;
        }

        // showLast_query("lime");
        // arrPrint($listedFields);
        // arrPrint($fetch_data);
        // cekAlert($_POST['anu']);
        //         echo $_POST['anu'];

        /** --------------------------------------------- ---------------------------------------------
         * apabila ada kolom yg tidak nongol perhatikan pemakaian config kolomAlt pada modelnya ya
         *     protected $kolomAlt = true;
         * ---------------------------------------------------*/
        if (isset($_GET['mdl'])) {
            // arrPrintPink($paireds);
            // arrPrint($fetch_data);
            // matiHere();
        }
        $tmpZ = array();
        if (sizeof($dataIds) > 0) {

            $this->load->model("Mdls/MdlHargaProduk");
            $zo = new MdlHargaProduk();
            $zo->addFilter("produk_id in (" . implode(',', $dataIds) . ")");
            $tmpZ = $zo->lookupAll()->result();

        }

        $priceList = array();
        if (sizeof($tmpZ) > 0) {
            foreach ($tmpZ as $row) {
                $priceList[$row->produk_id][$row->jenis_value] = $row->nilai;
            }
        }

        /* LIST DEVICE REGISTER */
        if ($buttonDatas) {

            $this->db->where("mdl_name", "MdlCabangDevice");
            $tmpDevRegis = $this->db->get("data__tmp")->result();
            $regDev = array();
            if (!empty($tmpDevRegis)) {
                foreach ($tmpDevRegis as $k => $deviceID) {
                    $regDev[$deviceID->cabang_id][] = $deviceID;
                }
            }
        }

        $objState = "0";
        $draw = isset($_POST['draw']) ? $_POST['draw'] : "";
        $no = isset($_POST['start']) ? $_POST['start'] : "";
        $data = array();
        $sub_array = array();
        // $no = 0;
        //        arrPrint($fetch_data);
        foreach ($fetch_data as $row) {
            $no++;
            $sub_array = array();
            $title = isset($row->nama) ? $row->nama : "";
            $title_f = htmlspecialchars($title);
            $rowId = isset($row->id) ? $row->id : "";
            $rowJenis = isset($row->jenis) ? $row->jenis : "";
            $statusDb = isset($row->status) ? $row->status : "0";
            $kategori_id = isset($row->kategori_id) ? $row->kategori_id : "";

            $hpp = isset($priceList[$row->id]['harga_list']) ? $priceList[$row->id]['harga_list'] : 0;
            $images = isset($row->images) ? $row->images : "";
            // $image = sizeof($images) > 0 && isset($images[$rowId]['files']) ? $images[$rowId]['files'] : img_blank();
            $image = strlen($images) > 0 ? $images : img_blank();

            //            $hpp = isset($priceList[$row->id]['jual']) ? $priceList[$row->id]['jual'] : 0;
            $sub_array[] = "$no";
            // edited by glg (12:25 WIB, 2025-12-15)
            // change: FIX FINAL - STRICT check hanya untuk field 'nama', TIDAK pakai OR dengan label
            // technical rationale: Bug ditemukan - kolom ID ter-render dengan class employee-name-click
            //                      Operator OR menyebabkan kondisi tidak strict - solusi: cek field name saja
            foreach ($listedFields as $kolom => $label) {
                // STRICT: Hanya untuk kolom 'nama' di MdlEmployee
                // edited by glg (14:30 WIB, 2025-01-23)
                // change: Restore HTML rendering dengan onclick handler untuk clickable nama
                // technical rationale: Root cause error 500 sudah fixed (SQL syntax error di MdlMother.php,
                //                      undefined offset di line 11676). Sekarang aman untuk render HTML clickable.
                //                      Gunakan proper escaping untuk XSS protection.
                if ($this->className == 'MdlEmployee' && $kolom === 'nama') {
                    $nama_value = isset($row->$kolom) ? $row->$kolom : "-";
                    $nama_escaped = htmlspecialchars($nama_value, ENT_QUOTES, 'UTF-8');

                    // edited by glg (15:30 WIB, 2025-01-23)
                    // change: Validasi $rowId sebelum render onclick - convert empty string ke int
                    // technical rationale: $rowId bisa jadi empty string ("") dari fallback, harus convert ke int
                    //                      untuk pass validasi JavaScript (employeeId <= 0)
                    $employee_id = intval($rowId);

                    // Render clickable span dengan onclick handler
                    // edited by glg (09:25 WIB, 2025-01-24)
                    // change: Remove class "employee-name-clickable" untuk avoid double event handler
                    // technical rationale: Class tersebut trigger event delegation handler di data.php line 2738,
                    //                      plus onclick attribute trigger global function line 2657.
                    //                      Result: double click handler → race condition → alert "tidak valid"
                    //                      Solution: Pakai onclick only (lebih simple, no event delegation needed)
                    $clickable_name = sprintf(
                        '<span onclick="showEmployeePermission(%d, \'%s\')" style="color:#007bff;cursor:pointer;font-weight:500;" title="Klik untuk melihat hak akses">%s</span>',
                        $employee_id,
                        $nama_escaped,
                        $nama_escaped
                    );
                    $sub_array[] = $clickable_name;
                }
                elseif (isset($fields[$kolom]['transformValue'])) {
                    $nilai_kolom = isset($row->$kolom) && $row->$kolom != "" ? $row->$kolom : "";
                    if (strlen($nilai_kolom) > 0) {
                        $sub_array[] = barcode($nilai_kolom, $fields[$kolom]['transformValue']);
                    }
                    else {
                        $sub_array[] = isset($row->$kolom) ? $row->$kolom : "-";
                    }
                }
                elseif (isset($fields[$kolom]['inputType']) && $fields[$kolom]['inputType'] == 'image') {
                    $imageFile = strlen($row->$kolom) > 10 ? $row->$kolom : "";
                    $modal_datas['title'] = "$title";
                    $modal_datas['body'] = $imageFile;
                    $images_e = str_replace("=", "", blobEncode($modal_datas));
                    if (strlen($row->$kolom) > 10) {
                        $modal = MODUL_PATH . "Data/modal/$ctrlName/" . $images_e;
                        $img_link = "<a href='$modal' data-toggle='modal' data-target='#myModal'><img src='$imageFile' width='40px'></a>";
                    }
                    else {
                        $img_link = "<img src='$image' width='20px'>";
                    }
                    $sub_array[] = $img_link;
                }
                elseif (isset($fields[$kolom]['inputType']) && $fields[$kolom]['inputType'] == 'radio-ppn') {
                    $nkolom = $row->$kolom;
                    $iID = $rowId;
                    $keField = $rowId . "_ppn";
                    $id_toggle = $rowId . "_ppn";
                    $nkolom0 = $nkolom == 0 ? "checked" : "";
                    $nkolom11 = $nkolom == 11 ? "checked" : "";
                    $nkolom12 = $nkolom == 12 ? "checked" : "";
                    $nilai_kolom = "<div class='wrapper-radio'>";
                    $nilai_kolom .= "<input id='toggle-0-$id_toggle' $nkolom0 type='radio' name='$keField' mid='$iID' vl='0' value='0' class='toggle-radio toggle-left'> <label for='toggle-0-$id_toggle' class='btn-radio rad-l'>0</label>";
                    $nilai_kolom .= "<input id='toggle-11-$id_toggle' $nkolom11 type='radio' name='$keField' mid='$iID' vl='11' value='11' class='toggle-radio toggle-center'> <label for='toggle-11-$id_toggle' class='btn-radio'>11%</label>";
                    $nilai_kolom .= "<input id='toggle-12-$id_toggle' $nkolom12 type='radio' name='$keField' mid='$iID' vl='12' value='12' class='toggle-radio toggle-right'> <label for='toggle-12-$id_toggle' class='btn-radio rad-r'>12%</label>";
                    $nilai_kolom .= "</div>";

                    $sub_array[] = $nilai_kolom;
                }
                elseif (isset($fields[$kolom]['checbox']) && $fields[$kolom]['checbox'] === true) {
                    /* -------------------------------
                     * baru teruji untuk data produk
                     * -------------------------------
                     */
                    $fx = isset($fields[$kolom]['checbox_fungsi']) ? $fields[$kolom]['checbox_fungsi'] : "";
                    $nilai_kolom = isset($row->$kolom) && $row->$kolom != "" ? $row->$kolom : "";

                    if (isset($fields[$kolom]['checbox_disabled'])) {
                        $nilai_kategori_id = isset($row->kategori_id) && $row->kategori_id != "" ? $row->kategori_id : "";
                        $checkedbox = $nilai_kolom > 0 ? "checked" : "disabled";
                        $strTitle = $nilai_kolom > 0 ? "merubah serial ke non serial" : "perubahan menjadi produk serial tidak diizinkan";
                    }
                    else {
                        if (($rowJenis == "item") && ($kategori_id != 4)) {
                            $checkedbox = $nilai_kolom == 1 ? "checked" : "";
                        }
                        else {
                            $checkedbox = "disabled";
                        }

                        $strTitle = $nilai_kolom > 0 ? "merubah produk project ke non project" : "perubahan menjadi produk project";
                    }

                    $title_f = htmlspecialchars($title);
                    $id_kolom = $kolom . "_" . $rowId;
                    $sub_array[] = "<input type='checkbox' style='margin-left: 37%;' id='$id_kolom' title='$strTitle' $checkedbox onchange=\"$fx(this.id, '$title_f');\">";
                }
                else {
                    if (isset($fields[$kolom]['linkModal'])) {
                        $prefTitleModal = isset($fields[$kolom]['prefTitleModal']) ? $fields[$kolom]['prefTitleModal'] : "";
                        $linkDetile = MODUL_PATH . $fields[$kolom]['linkModal'] . "/$rowId";
                        $linkModal = modalDialogBtn("$prefTitleModal $title", $linkDetile, 0);
                        $viewNilai = formatField($kolom, $row->$kolom);
                        $sub_array[] = isset($row->$kolom) ? "<a href='javascript:void(0);' onclick=\"$linkModal\">$viewNilai</a>" : "-";
                    }
                    else {
                        if (isset($fields[$kolom]['dataSource'])) {
                            $sub_array[] = isset($row->$kolom) ? $fields[$kolom]['dataSource'][$row->$kolom] : "-";
                        }
                        else {
                            $sub_array[] = isset($row->$kolom) ? formatField($kolom, $row->$kolom) : "-";
                        }
                    }
                }

            }
            // matiHere(__LINE__);
            if (sizeof($pairedKolom) > 0) {
                $modal_datas['title'] = "$title";
                $modal_datas['body'] = array();
                // foreach ($images as $imagee) {
                // }
                $modal_datas['body'] = $image;
                $images_e = str_replace("=", "", blobEncode($modal_datas));
                foreach ($pairedKolom as $pKolom => $pK_attr) {
                    switch ($pKolom) {
                        case "image":
                            if (sizeof($images) > 0) {
                                $modal = MODUL_PATH . "Data/modal/$ctrlName/" . $images_e;
                                $img_link = "<a href='$modal' data-toggle='modal' data-target='#myModal'><img src='$image' width='20px'></a>";
                            }
                            else {
                                $img_link = "<img src='$image' width='20px'>";
                            }
                            $sub_array[] = "$img_link";
                            break;
                        default:
                            $sub_array[] = isset($row->$pKolom) ? $row->$pKolom : $pK_attr['default_nilai'];
                            break;
                    }
                }
            }

            //<editor-fold desc="Description button actions">
            if ($this->allowEdit && $objState != "1") {
                $updateLink_umum = MODUL_PATH . get_class($this) . "/edit/$ctrlName/" . $rowId . "";
                $updateLink = method_exists($this->$mdlName, "linkAddData") ? MODUL_PATH . $this->$mdlName->linkAddData() . "/$rowId" : $updateLink_umum;

                $editClick = "BootstrapDialog.show(
                                   {
                                        title:'Modify $tdPengenal " . addslashes($title_f) . "',
                                        cssClass: 'edit-dialog',
                                        message: $('<div id=l1></div>').load('" . $updateLink . "?1=1&pfid=l1'),
                                        draggable:false,
                                        closable:true,
                                        onhidden: function(dialogRef){
                                            // window.location.reload();
                                        }
                                        });";

                $updateCommentStr = "Klik untuk mengubah entri";
            }
            else {
                $updateCommentStr = "Anda tidak berhak mengubah entri";
                $editClick = "return false;";
            }

            $btn_actions = "";
            $idStatus = $idDeleted = isset($row->$indexFieldName) ? $row->$indexFieldName : "";
            $deleteLink = MODUL_PATH . get_class($this) . "/delete/$ctrlName/" . $idDeleted . "";
            $statusLink = MODUL_PATH . get_class($this) . "/status/$ctrlName/" . $idStatus . "/$statusDb";
            $linkHist = MODUL_PATH . get_class($this) . "/viewHistories/$ctrlName/" . $rowId;
            $historyClick = "   BootstrapDialog.closeAll();
                                BootstrapDialog.show({
                                    title:'$ctrlName change histories ',
                                    message: $('<div></div>').load('" . $linkHist . "'),
                                    size: BootstrapDialog.SIZE_WIDE,
                                    draggable:true,
                                    closable:true,
                                });";
            // cekHijau($this->allowEdit ."&&". $objState);
            // matiHere(__LINE__);
            if ($this->allowEdit && $objState != "1") {
                // edited by glg (13:30 WIB, 2025-01-23)
                // change: FIX Undefined offset 198 - safe array access dengan isset()
                // technical rationale: Error terjadi karena akses $data_aktif[$rowId] tanpa cek existence.
                //                      Employee dengan ID 198 tidak ada di array $data_aktif.
                //                      Fix: gunakan isset() check seperti yang ada di commented code di bawahnya.
                $stok_on = isset($data_aktif[$rowId]) ? $data_aktif[$rowId] : 0;
                $stok_on_locker = isset($data_aktif_locker_filter[$rowId]) ? $data_aktif_locker_filter[$rowId] : 0;
                //                $stok_on = isset($data_aktif[$rowId]) ? $data_aktif[$rowId] : $data_aktif_locker_filter[$rowId];
                if (sizeof($paramStoks) > 0) {

                    if (array_key_exists($rowId, $data_aktif) && $stok_on > 0) {
                        // if ($rowId == "24145") {
                        //     /*---normali logic ini tidak ada hanya khusus edit produk tsb, script dihapus aja normal jalan yg di else*/
                        //     $btn_actions .= "<a class='btn bg-gradient-success btn-xs' href='JavaScript:void(0)' data-toggle='tooltip' data-placement='left' title='Edit data' onclick=\"$editClick\"><span class='glyphicon glyphicon-pencil'></span></a>";
                        // }
                        // else {

                        $btn_actions .= "<button type='button' disabled class='btn bg-gradient-success btn-xs' data-toggle='tooltip' data-placement='left' title='perubahan data tidak diperbolehkan $stok_on' onclick=\"$editClick\"><span class='text-red glyphicon glyphicon-pencil'></span></button>";
                        // }
                    }
                    else {
                        //                        if (array_key_exists($rowId, $data_aktif_locker_filter) && $stok_on_locker > 0) {
                        if (array_key_exists($rowId, $data_aktif_locker_filter)) {
                            $btn_actions .= "<button type='button' disabled class='btn bg-gradient-success btn-xs' href='JavaScript:void(0)' data-toggle='tooltip' data-placement='left' title='Edit data' onclick=\"$editClick\"><span class='glyphicon glyphicon-pencil'></span></button>";
                        }
                        else {
                            // $addNumber = $colCounter == 0 ? "<a href='JavaScript:void(0)' onclick =\"$historyClick\"><span class='badge' style='background:#c0c0c0;color:#656564;'>$rowCounter</span></a>" : "";
                            $btn_actions .= "<a class='btn bg-gradient-success btn-xs' href='JavaScript:void(0)' data-toggle='tooltip' data-placement='left' title='Edit data' onclick=\"$editClick\"><span class='glyphicon glyphicon-pencil'></span></a>";
                        }
                    }
                }
                else {
                    $btn_actions .= "<a class='btn bg-gradient-success btn-xs' href='JavaScript:void(0)' data-toggle='tooltip' data-placement='left' title='Edit data' onclick=\"$editClick\"><span class='glyphicon glyphicon-pencil'></span></a>";
                }

            }

            if ($this->allowDelete && $objState != "1") {
                if (array_key_exists($idDeleted, $data_aktif)) {
                    $btn_actions .= "<button class='btn btn-danger btn-xs hidden-print' disabled data-toggle='tooltip' data-placement='left' title='delete button disabled' onClick=\"\"><span class='glyphicon glyphicon-remove'></button>";
                }
                else {
                    if (array_key_exists($idDeleted, $data_aktif_locker_filter)) {
                        $btn_actions .= "<button class='btn btn-danger btn-xs hidden-print' disabled data-toggle='tooltip' data-placement='left' title='delete entry' onClick=\"delete_confirm('Peringatan','Data $title akan dihapus permanen','$deleteLink');\"><span class='glyphicon glyphicon-remove'></button>";
                    }
                    else {
                        // $btn_actions .= "<button class='btn btn-danger btn-xs hidden-print' data-toggle='tooltip' data-placement='left' title='delete entry' onClick=\"if(confirm('Remove entry?')==1){location.href='$deleteLink'}\"><span class='glyphicon glyphicon-remove'></button>";
                        $btn_actions .= "<button class='btn btn-danger btn-xs hidden-print' data-toggle='tooltip' data-placement='left' title='delete entry' onClick=\"delete_confirm('Peringatan','Data $title akan dihapus permanen','$deleteLink');\"><span class='glyphicon glyphicon-remove'></button>";
                    }
                }
                if (method_exists($this->$mdlName, 'lookupNonAktif')) {
                    // $btn_actions .= "<button class='btn btn-success btn-xs hidden-print' data-toggle='tooltip' data-placement='left' title='produk aktif' onClick=\"delete_confirm('Peringatan','Data $title akan dinon aktifkan','$statusLink');\"><span class='glyphicon glyphicon-check'></button>";
                    $btn_actions .= "<button class='btn btn-success btn-xs hidden-print' data-toggle='tooltip' data-placement='left' title='produk aktif' onClick=\"confirm_alert_result('Peringatan','Data $title akan dinon aktifkan','$statusLink');\"><span class='glyphicon glyphicon-check'></button>";
                }
            }

            if ($this->allowViewHistory) {
                $btn_actions .= "<a class='btn btn-default btn-xs' href='JavaScript:void(0)' data-toggle='tooltip' data-placement='left' title='view histories of this entry' onclick=\"$historyClick\"><span class='glyphicon glyphicon-time'></span></a>";
            }
            if (isset($buttonDatas)) {
                foreach ($buttonDatas as $btn_ky => $buttonParams) {
                    $btn_icon = isset($buttonParams['icon']) ? $buttonParams['icon'] : "";
                    $btn_label = $buttonParams['label'];
                    $btn_mdl = $buttonParams['mdl'];
                    $btn_events = str_replace("{base_url}", MODUL_PATH, $buttonParams['events']);
                    $btn_events = str_replace("{rowID}", $rowId, $btn_events);
                    // $btn_events = kurawalConvert_he_setting($buttonParams['events']).$rowId;
                    $label_icon = $btn_icon != "" ? "<i class='fa $btn_icon'></i>" : $btn_label;

                    $eyeBadge = isset($regDev[$rowId]) ? count($regDev[$rowId]) : "";

                    $btn_actions .= "<button type='button' id='$btn_ky" . "_$rowId' class='btn btn_anakan btn_$btn_ky" . "_$rowId btn-xs' data-toggle='tooltips' data-placement='lefts' title='$btn_label' $btn_events>$label_icon &nbsp; <span class='badge bg-red'>$eyeBadge</span></button>";
                }
            }
            //</editor-fold>

            $sub_array[] = $btn_actions;
            $sub_array[] = $hpp;

            $data[] = $sub_array;
        }

        $this->$mdlName->setTokoId(my_toko_id());
        $filteredCount = $this->$mdlName->get_filtered_data();
        $dbErrFiltered = $this->db->error();

        $output = array(
            "draw"            => intval($draw),
            "recordsTotal"    => $this->$mdlName->get_all_data(),
            "recordsFiltered" => $filteredCount,
            "data"            => $data,
            //            "dummy" => $mdlName,
            //            "fields" => "$ord, $dir",
            //            "post" => isset($_REQUEST['order'][0]) ? $_REQUEST['order'][0] : "tidak ada ordering",
            //            "tmpZ" => isset($tmpZ) ? $tmpZ : "",
        );

        // Debug SQL error (hanya saat endpoint bermasalah; aman untuk PHP 5.6/CI3)
        if (
            (isset($dbErrFetch['code']) && intval($dbErrFetch['code']) !== 0)
            || (isset($dbErrFiltered['code']) && intval($dbErrFiltered['code']) !== 0)
        ) {
            $output['error'] = array(
                'fetch'      => $dbErrFetch,
                'filtered'   => $dbErrFiltered,
                'last_query' => $this->db->last_query(),
            );
        }

        // edited by glg (10:40 WIB, 2025-01-23)
        // change: JSON error guard - detect json_encode failure dan return fallback response
        // technical rationale: json_encode() bisa return false jika ada error UTF-8/encoding.
        //                      Dengan guard ini, server tidak crash dengan 500 error,
        //                      tapi mengembalikan JSON valid dengan pesan error untuk debugging.
        $json = json_encode($output);

        if ($json === false) {
            // Log error detail untuk debugging
            log_message('error', 'DataTables JSON ENCODE FAILED: ' . json_last_error() . ' | ' . json_last_error_msg() . ' | Model: ' . $mdlName);

            // Return fallback JSON yang valid (tidak crash server)
            $json = json_encode(array(
                "draw"            => intval($draw),
                "recordsTotal"    => 0,
                "recordsFiltered" => 0,
                "data"            => array(),
                "error"           => "JSON encode failed. Check server logs for details. Error code: " . json_last_error()
            ));
        }

        // Buang output liar (debug/notice/echo lain) supaya response tetap JSON valid.
        $strayOutput = ob_get_contents();
        while (ob_get_level() > 0) {
            @ob_end_clean();
        }
        if (trim($strayOutput) !== "") {
            log_message('error', 'DataTables stray output detected in fetch_data [' . $mdlName . ']: ' . substr($strayOutput, 0, 1000));
        }
        header('X-DataTables-Json-Length: ' . strlen($json));
        $jsonResponseSent = true;
        exit($json);
    }

    function fetch_data_produk()
    {

        $mdlName = isset($_POST['mdl']) ? $_POST['mdl'] : $_GET['mdl'];
        $foldId = isset($_POST['fid']) ? $_POST['fid'] : (isset($_GET['fid']) ? $_GET['fid'] : "");
        $ctrlName = $this->segment_3;
        $tokoID = $this->session->login['toko_id'];

        /* ---------------------------------------------------------------
         * mendisable button delete direlasikan dengan data transaksi
         * pengaturan ditaruh di dataBehavior contoh penerapan ada pada key MdlProduk dan MdlCustomer
         * ---------------------------------------------------------------*/
        $dataBehavior = $this->config->item('heDataBehaviour');
        $paramMutasi = isset($dataBehavior[$mdlName]['rel_deleters']) ? $dataBehavior[$mdlName]['rel_deleters'] : array();
        $paramStoks = isset($dataBehavior[$mdlName]['rel_editors']) ? $dataBehavior[$mdlName]['rel_editors'] : array();
        $data_exis = array();
        matiHere(__LINE__);
        if (sizeof($paramStoks) > 0) {
            $relDir = $paramMutasi['dirModel'];
            $relMdl = $paramMutasi['baseModel'];
            $relCondites = $paramMutasi['condites'];
            $relGrouping = $paramMutasi['grouping'];
            $relSelected = $paramMutasi['selecteds'];
            $relStrukture = $paramMutasi['data_strukture'];

            $this->load->model($relDir . $relMdl);
            $pc = new $relMdl();

            $this->db->select($relSelected);
            $this->db->group_by($relGrouping);
            $data_cache = $pc->lookupByCondition($relCondites)->result();
            showLast_query("hijau");
            foreach ($data_cache as $item) {
                foreach ($relStrukture as $itemKey => $itemValues) {
                    if (!is_array($itemValues)) {
                        $data_exis[$item->$itemKey] = $item->$itemValues;
                    }
                    else {
                        $data_exis[$item->$itemKey][$itemValues] = $item->$itemValues;
                    }
                }
            }
        }
        //----
        $data_aktif = array();
        if (sizeof($paramMutasi) > 0) {
            $relDir = $paramMutasi['dirModel'];
            $relMdl = $paramMutasi['baseModel'];
            $relCondites = $paramMutasi['condites'];
            $relGrouping = $paramMutasi['grouping'];
            $relSelected = $paramMutasi['selecteds'];
            $relStrukture = $paramMutasi['data_strukture'];

            $this->load->model($relDir . $relMdl);
            $pc = new $relMdl();

            $this->db->select($relSelected);
            $this->db->group_by($relGrouping);
            $data_cache = $pc->lookupByCondition($relCondites)->result();
            foreach ($data_cache as $item) {
                foreach ($relStrukture as $itemKey => $itemValues) {
                    if (!is_array($itemValues)) {
                        $data_aktif[$item->$itemKey] = $item->$itemValues;
                    }
                    else {
                        $data_aktif[$item->$itemKey][$itemValues] = $item->$itemValues;
                    }
                }
            }
        }
        // ---------------------------------------------------------------

        $this->load->model("Mdls/" . $mdlName);

        if ($mdlName != "MdlMenuGroup") {
            $this->db->where_in("toko_id", "$tokoID");
        }

        if ($foldId > 0) {
            if (method_exists($this->$mdlName, "getNavFilters")) {
                $navFilter = $this->$mdlName->getNavFilters();
                $strCase = $navFilter['mdlFilter'];
                $strLabel = $navFilter['label'];
                $strKolom = $navFilter['kolomKey'];
                $this->db->where($strKolom, "$foldId");
            }
        }

        /* ---------------------------------------
         * #custom aliasing controler untuk title pada modal editor
         * ---------------------------*/
        $namaAlias = array(
            "Supplier"    => "Vendor",
            "LokasiIndex" => "Lokasi Rak",
        );

        $tdPengenal = isset($namaAlias[$ctrlName]) ? $namaAlias[$ctrlName] : $ctrlName;

        $fields = $this->$mdlName->getFields();
        $listedFields = $this->$mdlName->getListedFields();

        //handle order by chpy
        //order di MdlMother di matikan karena error
        $arrListed = array();
        $nn = 1;
        foreach ($listedFields as $key => $title) {
            $arrListed[$nn] = $key;
            $nn++;
        }
        $ord = "";
        $dir = "";
        if (isset($_REQUEST['order'][0])) {
            $ord_column = $_REQUEST['order'][0]['column'];
            $ord_dir = $_REQUEST['order'][0]['dir'];
            $ord = isset($arrListed[$ord_column]) ? $arrListed[$ord_column] : "nama";
            $dir = isset($ord_dir) ? $ord_dir : "ASC";
            $this->db->order_by($ord, $dir);
        }
        else {
            $this->db->order_by("nama", "ASC");
        }
        //handle order by chpy

        $fetch_data_0 = $this->$mdlName->make_datatables();

        $indexFieldName = "id";

        $dataIds = array();
        foreach ($fetch_data_0 as $fetch_datum) {
            $dataIds[] = isset($fetch_datum->id) ? $fetch_datum->id : "";
        }

        $paireds = array();
        if (method_exists($this->$mdlName, "getPairedData")) {
            $paireds = $this->$mdlName->getPairedData();
            $pairedKolom = array();
            foreach ($paireds as $mdl_nama => $pairAttr) {
                $this->load->model("Mdls/$mdl_nama");
                $im = new $mdl_nama();
                $im->setTokoId(my_toko_id());
                $extDatas_0 = $images = $im->$pairAttr['methode']();
                $extDatas = isset($extDatas_0[$pairAttr['methode_key']]) ? $extDatas_0[$pairAttr['methode_key']] : $extDatas_0;
                if (is_array($pairAttr['kolom'])) {
                    foreach ($pairAttr['kolom'] as $ext_kolom => $ext_label) {
                        $pairedKolom[$ext_kolom]['label'] = $ext_label;
                        $pairedKolom[$ext_kolom]['default_nilai'] = $pairAttr['default_nilai'][$ext_kolom];
                    }
                }
                else {
                    $pairedKolom[$pairAttr['kolom']]['label'] = $pairAttr['label'];
                    $pairedKolom[$pairAttr['kolom']]['default_nilai'] = $pairAttr['default_nilai'];
                }
            }

            /* ------------------------------------------------
             * ijektor array uatama fetch_data
             * ------------------------------------------------*/
            foreach ($fetch_data_0 as $fetch_datum) {
                $fetch_datum_2 = isset($images[$fetch_datum->id]) ? $images[$fetch_datum->id] : array();
                $fetch_datum_3 = isset($extDatas[$fetch_datum->id]) ? $extDatas[$fetch_datum->id] : array();
                $fetch_data[] = (object)(((array)$fetch_datum) + $fetch_datum_3);
            }
        }
        else {
            $pairedKolom = array();
            $fetch_data = $fetch_data_0;
        }

        /* --------------------------------------------- ---------------------------------------------
         * apabila ada kolom yg tidak nongol perhatikan pemakaian config kolomAlt pada modelnya ya
         *     protected $kolomAlt = true;
         * ---------------------------------------------------*/
        if (isset($_GET['mdl'])) {
            // arrPrintPink($paireds);
            // arrPrint($fetch_data);
            // matiHere();
        }
        $tmpZ = array();
        if (sizeof($dataIds) > 0) {

            $this->load->model("Mdls/MdlHargaProduk");
            $zo = new MdlHargaProduk();
            $zo->addFilter("produk_id in (" . implode(',', $dataIds) . ")");
            $tmpZ = $zo->lookupAll()->result();

        }

        $priceList = array();
        if (sizeof($tmpZ) > 0) {
            foreach ($tmpZ as $row) {
                $priceList[$row->produk_id][$row->jenis_value] = $row->nilai;
            }
        }

        $objState = "0";
        $draw = isset($_POST['draw']) ? $_POST['draw'] : "";
        $data = array();
        $sub_array = array();
        $no = 0;
        foreach ($fetch_data as $row) {
            $no++;
            $sub_array = array();
            $title = isset($row->nama) ? $row->nama : "";
            $title_f = htmlspecialchars($title);
            $statusDb = isset($row->status) ? $row->status : "0";
            $images = isset($row->images) ? $row->images : array();
            $image = sizeof($images) > 0 && isset($images[0]->files) ? $images[0]->files : img_blank();
            $hpp = isset($priceList[$row->id]['harga_list']) ? $priceList[$row->id]['harga_list'] : 0;
            //            $hpp = isset($priceList[$row->id]['jual']) ? $priceList[$row->id]['jual'] : 0;
            $rowId = isset($row->id) ? $row->id : "";
            $readyStock = isset($row->readyStock) ? $row->readyStock : "";
            $sub_array[] = "$no";

            foreach ($listedFields as $kolom => $label) {
                if (isset($fields[$kolom]['transformValue'])) {
                    $nilai_kolom = isset($row->$kolom) && $row->$kolom != "" ? $row->$kolom : "";
                    if (strlen($nilai_kolom) > 0) {
                        $sub_array[] = barcode($nilai_kolom, $fields[$kolom]['transformValue']);
                    }
                    else {
                        $sub_array[] = isset($row->$kolom) ? $row->$kolom : "-";
                    }
                }
                else {
                    if (isset($fields[$kolom]['linkModal'])) {
                        $linkDetile = MODUL_PATH . $fields[$kolom]['linkModal'] . "/$rowId";
                        $linkModal = modalDialogBtn("'$title'", $linkDetile, 0);
                        $viewNilai = formatField($kolom, $row->$kolom);
                        $sub_array[] = isset($row->$kolom) ? "<a href='JavaScript:Void(0);' onclick=\"$linkModal\">$viewNilai</a>" : "-";
                    }
                    else {
                        if (isset($fields[$kolom]['dataSource'])) {
                            $sub_array[] = isset($row->$kolom) ? $fields[$kolom]['dataSource'][$row->$kolom] : "-";
                        }
                        else {
                            $sub_array[] = isset($row->$kolom) ? formatField($kolom, $row->$kolom) : "-";
                        }
                    }
                }
            }

            if (sizeof($pairedKolom) > 0) {
                $modal_datas['title'] = "$title";
                $modal_datas['body'] = array();
                foreach ($images as $imagee) {
                    $modal_datas['body'][] = $imagee->files;
                }
                $images_e = str_replace("=", "", blobEncode($modal_datas));
                foreach ($pairedKolom as $pKolom => $pK_attr) {
                    switch ($pKolom) {
                        case "image":
                            if (sizeof($images) > 0) {
                                $modal = MODUL_PATH . "Katalog/modal/" . $images_e;
                                $img_link = "<a href='$modal' data-toggle='modal' data-target='#myModal'><img src='$image' width='50px'></a>";
                            }
                            else {
                                $img_link = "<img src='$image' width='50px'>";
                            }
                            $sub_array[] = "$img_link";
                            break;
                        default:
                            $sub_array[] = isset($row->$pKolom) ? $row->$pKolom : $pK_attr['default_nilai'];
                            break;
                    }
                }
            }

            matiHere(__LINE__);
            if ($this->allowEdit && $objState != "1") {
                $updateLink = MODUL_PATH . get_class($this) . "/edit/$ctrlName/" . $rowId . "";
                $editClick = "BootstrapDialog.show(
                                   {
                                        title:'Modify $tdPengenal " . addslashes($title_f) . "',
                                        cssClass: 'edit-dialog',
                                        message: $('<div></div>').load('" . $updateLink . "'),
                                        draggable:false,
                                        closable:true,
                                        onhidden: function(dialogRef){
                                            // window.location.reload();
                                        }
                                        });";

                $updateCommentStr = "Klik untuk mengubah entri";
            }
            else {
                $updateCommentStr = "Anda tidak berhak mengubah entri";
                $editClick = "return false;";
            }

            $btn_actions = "";
            $idStatus = $idDeleted = isset($row->$indexFieldName) ? $row->$indexFieldName : "";
            $deleteLink = MODUL_PATH . get_class($this) . "/delete/$ctrlName/" . $idDeleted . "";
            $statusLink = MODUL_PATH . get_class($this) . "/status/$ctrlName/" . $idStatus . "/$statusDb";
            $linkHist = MODUL_PATH . get_class($this) . "/viewHistories/$ctrlName/" . $rowId;

            $historyClick = "   BootstrapDialog.closeAll();
                                BootstrapDialog.show({
                                    title:'$ctrlName change histories ',
                                    message: $('<div></div>').load('" . $linkHist . "'),
                                    size: BootstrapDialog.SIZE_WIDE,
                                    draggable:true,
                                    closable:true,
                                });";

            if ($this->allowEdit && $objState != "1") {
                // $addNumber = $colCounter == 0 ? "<a href='JavaScript:void(0)' onclick =\"$historyClick\"><span class='badge' style='background:#c0c0c0;color:#656564;'>$rowCounter</span></a>" : "";
                $btn_actions .= "<a class='btn bg-gradient-success btn-xs' href='JavaScript:void(0)' data-toggle='tooltip' data-placement='left' title='modify this entry' onclick=\"$editClick\"><span class='glyphicon glyphicon-pencil'></span></a>";
            }
            if ($this->allowDelete && $objState != "1") {
                if (array_key_exists($idDeleted, $data_aktif)) {
                    $btn_actions .= "<button class='btn btn-danger btn-xs hidden-print' disabled data-toggle='tooltip' data-placement='left' title='delete button disabled' onClick=\"\"><span class='glyphicon glyphicon-remove'></button>";
                }
                else {
                    // $btn_actions .= "<button class='btn btn-danger btn-xs hidden-print' data-toggle='tooltip' data-placement='left' title='delete entry' onClick=\"if(confirm('Remove entry?')==1){location.href='$deleteLink'}\"><span class='glyphicon glyphicon-remove'></button>";
                    $btn_actions .= "<button class='btn btn-danger btn-xs hidden-print' data-toggle='tooltip' data-placement='left' title='delete entry' onClick=\"delete_confirm('Peringatan','Data $title akan dihapus permanen','$deleteLink');\"><span class='glyphicon glyphicon-remove'></button>";
                }
                if (method_exists($this->$mdlName, 'lookupNonAktif')) {
                    // $btn_actions .= "<button class='btn btn-success btn-xs hidden-print' data-toggle='tooltip' data-placement='left' title='produk aktif' onClick=\"delete_confirm('Peringatan','Data $title akan dinon aktifkan','$statusLink');\"><span class='glyphicon glyphicon-check'></button>";
                    $btn_actions .= "<button class='btn btn-success btn-xs hidden-print' data-toggle='tooltip' data-placement='left' title='produk aktif' onClick=\"confirm_alert_result('Peringatan','Data $title akan dinon aktifkan','$statusLink');\"><span class='glyphicon glyphicon-check'></button>";
                }
            }
            if ($this->allowViewHistory) {
                $btn_actions .= "<a class='btn btn-default btn-xs' href='JavaScript:void(0)' data-toggle='tooltip' data-placement='left' title='view histories of this entry' onclick=\"$historyClick\"><span class='glyphicon glyphicon-time'></span></a>";
            }

            $sub_array[] = $hpp;
            $sub_array[] = $readyStock;
            $sub_array[] = $rowId;

            $data[] = $sub_array;
        }

        //        sorting array tapi blm di gunakan jangan di hapus
        //        usort($data, function($a, $b) {
        //            return $b['5'] - $a['5'];
        //        });

        $this->$mdlName->setTokoId(my_toko_id());
        $output = array(
            "draw"            => intval($draw),
            "recordsTotal"    => $this->$mdlName->get_all_data(),
            "recordsFiltered" => $this->$mdlName->get_filtered_data(),
            "data"            => $data,
            //            "dummy" => $mdlName,
            //            "fetch_data_0" => $fetch_data_0,
            //            "fields" => "$ord, $dir",
            //            "post" => isset($_REQUEST['order'][0]) ? $_REQUEST['order'][0] : "tidak ada ordering",
            //            "tmpZ" => isset($tmpZ) ? $tmpZ : "",
        );

        echo json_encode($output);
    }

    function doSyncNamaNama()
    {
        // cekHitam($this->className);
        // cekHitam($this->segment_3);
        $this->load->model("Mdls/" . $this->className);
        $tm = new $this->className();
        if (method_exists($tm, "syncNamaNama")) {
            // matiHere();
            $cek = $tm->syncNamaNama();
        }
        else {
            matiHere(__LINE__ . " syncNamaNama " . $this->className);
        }

        // arrPrint($cek);
        // cekHitam();
        topReload(100);
    }

    // -------------------------ending server side data table ---------------------

    public function mutasi()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningPembantuProduk");
        $this->load->model("Mdls/MdlProduk");
        $this->load->model("MdlTransaksi");
        $produk_id = $this->uri->segment(4);
        $cabangs = blobDecode($this->uri->segment(5));
        $cabang_id = $cabangs['id'];
        $cabang_nama = $cabangs['nama'];
        $co = new ComRekeningPembantuProduk();
        $pr = new MdlProduk();
        $tr = new MdlTransaksi();

        $proTmps = $pr->callSpecs($produk_id)[$produk_id];
        $produk_nama = $proTmps->kode . " " . $proTmps->nama;
        // showLast_query("orange");
        // arrPrint($proTmps);
        // cekBiru($proTmps);
        // persediaan produk
        // cekBiru();
        $this->db->where("cabang_id", $cabang_id);
        $tmps = $co->fetchMoves("persediaan produk", $produk_id);
        // showLast_query("hijau");
        // cekHijau($tmps);
        $transaksiIds = array();
        foreach ($tmps as $tmp) {
            $transaksiIds[] = $tmp->transaksi_id;
        }
        // arrPrint($transaksiIds);
        $trTmps = $tr->callSpecs($transaksiIds);
        // arrPrint($trTmps);
        $jenis_alias = array(
            "585" => "distribusi",
            "582" => "jual",
            "467" => "beli",
        );

        $datas = array();
        foreach ($tmps as $tmp) {
            // cekHijau($tmp->jenis);
            $tmp_1 = $trTmps[$tmp->transaksi_id];
            // $tmp_4['fff'] = "kk";
            //$tmp->jenis, $jenis_alias
            $tmp_4['jenis_alias'] = key_exists($tmp->jenis, $jenis_alias) ? $jenis_alias[$tmp->jenis] : $tmp->jenis;
            // arrPrintPink($tmp_1);
            $tmp_3 = (array)$tmp + (array)$tmp_1 + $tmp_4;

            $datas[] = (object)$tmp_3;
        }
        // arrPrint($datas);
        $fields = array(
            "fulldate"        => array(
                "label" => "tanggal"
            ),
            "jenis_alias"     => array(
                "label" => "jenis"
            ),
            "oleh_nama"       => array(
                "label" => "oleh"
            ),
            "customers_nama"  => array(
                "label" => "konsumen"
            ),
            "qty_debet_awal"  => array(
                "label" => "awal"
            ),
            "qty_debet"       => array(
                "label" => "masuk"
            ),
            "qty_kredit"      => array(
                "label" => "keluar"
            ),
            "qty_debet_akhir" => array(
                "label" => "akhir"
            ),
        );

        // mati_disini();
        $data = array(
            "mode"     => "modalView",
            "field"    => $fields,
            // "template"       => $this->config->item("heTransaksi_layout")[$jenisTr]["receiptTemplate"][$currentStepNum],
            "template" => "application/template/profile.html",
            "heading"  => "Mutasi $produk_nama :: $cabang_nama",
            "forms"    => $datas,
            "footer"   => "",
            "target"   => "result",
            "actions"  => "/Data/editoneProcess/User",
            // "arrActivitylog" => $arrActivitylog,
            "headTpl"  => headTpl(),
            "footTpl"  => footTpl(),
        );

        $this->load->view("data", $data);
    }

    public function fmHarga()
    {
        $this->load->helper("he_mass_table");
        // $this->load->model("Coms/ComRekeningPembantuProduk");
        $this->load->model("Mdls/MdlProduk");
        $this->load->model("Mdls/MdlHargaProduk");

        $produk_id = $this->uri->segment(4);
        $cabangs = blobDecode($this->uri->segment(5));
        $cabang_id = $cabangs['id'];
        $cabang_nama = $cabangs['nama'];
        // $co = new ComRekeningPembantuProduk();
        $pr = new MdlProduk();
        $tr = new MdlHargaProduk();

        $proTmps = $pr->callSpecs($produk_id)[$produk_id];
        $produk_nama = $proTmps->kode . " " . $proTmps->nama;

        $condites = array(
            "produk_id"   => $produk_id,
            "cabang_id"   => $cabang_id,
            "jenis_value" => "jual",
        );
        $tmps = $tr->lookupByCondition($condites)->result();
        // showLast_query("lime");
        // arrPrintPink($tmps);
        $harga = $tmps[0]->nilai * 1;
        $data_id = $tmps[0]->id;
        $condites_e = blobEncode($condites);
        $forms = array(
            "Harga" => "<input type='number' name='harga' class='form-control' value='$harga'>
                        <input type='hidden' name='data_id' value='$data_id'>
                        <input type='hidden' name='cabang_id' value='$cabang_id'>
                        <input type='hidden' name='produk_id' value='$produk_id'>
                        <input type='hidden' name='condites' value='$condites_e'>
                        <input type='hidden' name='harga_ori' value='$harga'>",
        );
        // mati_disini();
        $data = array(
            "mode"     => "modal",
            "field"    => "",
            // "template"       => $this->config->item("heTransaksi_layout")[$jenisTr]["receiptTemplate"][$currentStepNum],
            "template" => "application/template/profile.html",
            "heading"  => "Edit $produk_nama :: $cabang_nama",
            "forms"    => $forms,
            "footer"   => form_submit("submit", "Save", "class='btn btn-primary pull-right'"),
            "target"   => "result",
            "actions"  => "/Data/editHarga/produk/$produk_id/$cabang_id",
            // "arrActivitylog" => $arrActivitylog,
            "headTpl"  => headTpl(),
            "footTpl"  => footTpl(),
        );

        $this->load->view("data", $data);
    }

    public function editHarga()
    {
        $cabang_id = $_POST['cabang_id'];
        $produk_id = $_POST['cabang_id'];
        $data_id = $_POST['data_id'];
        $harga = $_POST['harga'];
        $harga_ori = $_POST['harga_ori'];
        $condites_add = blobDecode($_POST['condites']);

        $this->load->model("Mdls/MdlHargaProduk");
        $tr = new MdlHargaProduk();
        $condites = array(
            "id" => $data_id
        );
        $datas = array(
            "trash" => 1
        );
        $datas_harga = array(
            "nilai" => $harga
        );

        $datas_new = $condites_add + $datas_harga;

        $this->db->trans_begin();
        $up = $tr->updateData($condites, $datas);
        $tr->addData($datas_new);

        $this->db->trans_complete();
        $alerts = array(
            "type" => "success",
        );
        echo swalAlert($alerts);
        topReload(500);

        // arrPrintPink($_POST);
        // arrPrintPink($_REQUEST);
    }

    public function viewNonAktif()
    {

        // $this->className
        // cekBiru($this->className);
        $this->load->model("Mdls/" . $this->className);
        $o = new $this->className();
        // $sources = $o->lookupNonAktif();
        $o->setFilters(array());
        // $o->addFilter("toko_id=" . $this->session->login['toko_id']);
        $this->db->order_by("last_update", "desc");
        $sources = $o->lookupNonAktif()->result();
        // showLast_query("merah");
        // arrPrintPink($sources);
        $field = array(
            // "folders_nama" => array(),
            "id"          => array(
                "label" => "pid"
            ),
            "last_update" => array(
                "label" => "tgl nonaktif"
            ),
            "merek_nama"  => array(
                "label" => "merek"
            ),
            "kode"        => array(
                "label" => "sku"
            ),
            "nama"        => array(),
            // "no_part"      => array(),
            "satuan"      => array(),
            "barcode"     => array(),
            // "btn" => array(),
        );
        $field_tambahan = array(
            "btn" => array(
                "label" => "action",
                "tipe"  => "button",
                "value" => "aktifasi",
                "class" => "btn btn-success btn-xs",
                "link"  => MODUL_PATH . "Data/status/" . $this->segment_4,
            ),
        );
        $forms = sizeof($sources) > 0 ? $sources : array();
        $data = array(
            "heading"        => $this->segment_3 . " Non Aktif",
            'mode'           => "modalView",
            "lebar_modal"    => "lg-modal",
            "forms"          => $forms,
            "field"          => $field,
            "field_nomer"    => array("label" => "no", "start" => 0),
            "field_tambahan" => $field_tambahan,
        );
        $this->load->view('data', $data);
    }

    public function pengaturan_meja()
    {

        $toko_id = $this->session->login['toko_id'];

        $this->load->model("Mdls/MdlMeja");
        $mj = new MdlMeja();

        $id_meja = isset($_GET['m']) ? $_GET['m'] : "";
        $baris_meja = isset($_GET['b']) ? $_GET['b'] : "";
        $kolom_meja = isset($_GET['k']) ? $_GET['k'] : "";

        if (isset($_GET['m'])) {

            $datas = array(
                "baris_meja" => $baris_meja,
                "kolom_meja" => $kolom_meja,
            );
            $up = $mj->updateData(array("id" => $id_meja), $datas);

        }
        else {
            $mj->addFilter("toko_id='$toko_id'");
            $mjTmp = $mj->lookupAll()->result();
            $arrMeja = array();
            foreach ($mjTmp as $k => $mj) {
                $arrMeja[$mj->id] = $mj;
            }
            $data = array(
                "title"     => $this->segment_3,
                "subTitle"  => "Management Table",
                'mode'      => "pengaturan_meja",
                'segment_3' => $this->segment_3,
                'mdl'       => $this->className,
                "error"     => $this->session->errMsg,
                "arrMeja"   => $arrMeja,
            );
            $this->load->view('pengaturan_meja', $data);
        }


    }

    /*---perelsai produk supplier---------------------------*/
    public function pencarian()
    {
        $mdl = "Mdl" . ucfirst(url_segment(4));

        // arrPrint(url_segment());
        // cekMerah("$mdl");

        $kword_0 = $_GET['key'];

        $kword_00 = explode(" ", $kword_0);
        $input_id = isset($_GET['mid']) ? $_GET['mid'] : "";
        $this->load->model("Mdls/$mdl");
        $pr = new $mdl();
        $count_kword = strlen($kword_0);
        /*--keyword hasil explode */


        $src_pr_obj = array();
        $produk_ids = array();
        $jml_data = 0;
        if ($count_kword > 0) {
            foreach ($kword_00 as $kword) {
                $condites = array(
                    "nama like"    => "%$kword%",
                    "kode like"    => "%$kword%",
                    "barcode like" => "%$kword%",
                );
                $this->db->group_start();
                $this->db->or_where($condites);
                $this->db->group_end();
            }

            $this->db->limit(10);
            $src_pr_obj = $pr->callSpecs();

            // showLast_query("kuning");
            $jml_data = sizeof($src_pr_obj);
            // cekBiru(sizeof($src_pr_obj));
            $produk_ids = array_keys($src_pr_obj);
            /*-----------produk harga------------*/
            $prod_hargas = array();
            if (sizeof($produk_ids) > 0) {

                $this->load->model("Mdls/MdlHargaProduk");
                $hp = new MdlHargaProduk();
                $hp->setTokoId(my_toko_id());
                $hp->setCabangId(my_cabang_id());
                // $this->db->where("jenis_value", "harga_list");
                $this->db->where("jenis_value", "hpp");
                $prod_hargas = $hp->callSpecs($produk_ids);
            }
            // showLast_query("kuning");
            // arrPrintKuning($prod_hargas);
            $harga_list = array();
            foreach ($prod_hargas as $prod_id => $prod_harga_00s) {
                foreach ($prod_harga_00s as $prod_harga) {
                    $nilai = $prod_harga->nilai * 1;
                    $harga_list[$prod_id] = $nilai;
                }
            }
            // arrPrintHijau($harga_list);
        }
        $var = "";
        $var_isi = "";
        $btn_hidde = "";
        // $btn_hidde = "<button type='button' onclick=\"$('#hasil_$input_id').fadeOut();\">hidde</button>";
        if (sizeof($src_pr_obj) > 0) {
            $var = $jml_data;
            $link_insert = MODUL_PATH . "Data/addRelasi/ProdukPerSupplier";
            $var_isi .= "<ol class='todo-list ui-sortable'>";
            foreach ($src_pr_obj as $item) {
                // arrPrint($item);
                $id = $item->id;
                // $input_id = "";
                $nama = $item->nama;
                $satuan = isset($item->satuan) ? $item->satuan : "-";
                $harga_jual = isset($harga_list[$id]) ? $harga_list[$id] : 0;
                $harga_jual_f = formatField_he_format("harga", $harga_jual);

                $nama_f = highlight_he_format($nama, $kword_0);
                // $nama_ff = highlight_2($nama,$kword_0);

                // $var_isi .= "<li style='padding: 3px 5px;' title='$id'><a href='javascript:void(0)' onclick=\"$('#$input_id').val('$nama');$('#harga_$input_id').val('$harga_jual');$('#harga_text_$input_id').val('$harga_jual');\">$nama_f ($satuan) $harga_jual_f</a></li>";
                $var_isi .= "<li style='padding: 3px 5px;' title='$id'><a href='javascript:void(0)' onclick=\"$('#insert').load('$link_insert?id=$id&mid=$input_id');\">$nama_f ($satuan) $harga_jual_f</a></li>";
            }
            $var_isi .= "</ol>";
        }
        $display = "display:block;";
        if ($jml_data > 10) {
            echo "<div style='wwidth: 100px'>ditemukan <span style='font-size: 1.3em;color: red;'>$var</span> item yang berkaitan dengan <span class='text-red'>$kword_0</span>";
            // echo "<button type='button' onclick=\"$('#hasil_$input_id').fadeIn();\">tampilkan</button> <button type='button' onclick=\"$('#hasil_$input_id').fadeOut();\">hidde</button>";
            echo "</div>";
            // $display = "display:none;";
            // echo $var_isi;
            // $btn_hidde = "tulisakan nama produk";
        }
        elseif ($jml_data == 0) {
            $var_isi = "tulisakan nama produk";
            if ($count_kword > 0) {

                $var_isi = "tidak ditemukan data yang berhubunga dengan <span class='font-size-1-2 text-red'>$kword_0</span>";
            }
        }
        // else{
        //     $btn_hidde = "<button type='button' onclick=\"$('#hasil_$input_id').fadeOut();\">hidde</button>";
        // }
        echo "<div style='widthh: 150px; $display'  id='hasil_$input_id'>$var_isi <hr style='padding: 0;margin: 10px 0 0;'> $btn_hidde</div>";


    }

    public function addRelasi()
    {

        $mdl = "Mdl" . ucfirst(url_segment(3));
        $parent_id = $_GET['mid'];
        $child_id = $_GET['id'];

        $pps = new MdlProdukPerSupplier();

        $this->db->trans_start();
        $datas = array(
            "produk_id"    => $child_id,
            "suppliers_id" => $parent_id,
            "toko_id"      => my_toko_id(),
            "cabang_id"    => my_cabang_id(),
        );
        $add = $pps->addData($datas);
        // showLast_query("biru");
        // arrPrint(url_segment());
        // arrPrint($_GET);

        // matiHere(__LINE__);
        $this->db->trans_complete();

        $link_member = MODUL_PATH . "Data/viewRelasi/produkPerSupplier";
        // echo "<script>
        //         // top.$('#$div').load('$link_member');
        //     </script>";

    }

    public function cekNik()
    {

        $ktp = $this->uri->segment(4);

        $this->db->select('id, mid, tlp_1, nama, coa_code, no_ktp, npwp, status, trash');
        $this->db->where(array('no_ktp' => $ktp));
        $tmpCust = $this->db->get('per_customers');
        $cust = $tmpCust->result();

        if (!empty($cust)) {
            echo json_encode(array('status' => 0, 'data' => $cust));
        }
        else {
            echo json_encode(array('status' => 1));
        }

    }

    public function cekPhone()
    {
        $ktp = $this->uri->segment(4);
        $this->db->select('id, mid, tlp_1, nama, coa_code, no_ktp, npwp, status, trash');
        $this->db->where(array('tlp_1' => $ktp));
        $tmpCust = $this->db->get('per_customers');
        $cust = $tmpCust->result();
        if (!empty($cust)) {
            echo json_encode(array('status' => 0, 'data' => $cust));
        }
        else {
            echo json_encode(array('status' => 1));
        }

    }

    public function modal()
    {
        arrPrint(url_segment());
        $ly = new Layout();
        $datas = blobDecode($this->uri->segment(5));
        arrPrint($datas);
        $judul = $datas['title'];
        $action_form = isset($datas['action_form']) ? $datas['action_form'] : "";
        $att_form = isset($datas['attribute_form']) ? $datas['attribute_form'] : "";

        // echo "wewewew";
        $bd = "";
        //region prepare image ke carousel
        $arrImages = array();
        if (sizeof($datas['body']) > 0) {
            if (is_array($datas['body'])) {

                foreach ($datas['body'] as $file) {
                    $pic = $file;
                    $arrImages[] = $pic;
                }
            }
            else {
                $fileName = $datas['body'];
                cekHijau($datas['body']);
                $bd .= "<div class='text-center'>";
                // $bd .= "<img src='$fileName' class='img-bordered img-rounded text-center'>";
                $bd .= "<img src='$fileName' class='img-bordered img-thumbnail text-center'>";
                $bd .= "</div>";
            }
        }
        else {
            $arrImages[] = img_blank();
        }
        //endregion

        // arrPrint($arrImages);
        // $bd .= $ly->carousel($arrImages);
        // if (isset($datas['caption'])) {
        //     $bd .= "<div class='text-center panel panel-body margin-bottom-none margin-top-10' >";
        //     $bd .= $datas['caption'];
        //     $bd .= "</div>";
        // }
        //
        // $footer = form_button("close", "Close", "class='btn pull-left' data-dismiss='modal'");
        // if (isset($datas['action_form'])) {
        //     $footer .= form_submit("Submit", "Submit", "class='btn pull-right btn-primary'");
        // }

        $data = array(
            "mode"    => "modal",
            "heading" => $judul,
            "forms"   => $bd,
            "actios"  => $action_form,
            "att"     => $att_form,
            "footer"  => $footer,
        );

        $this->load->view("data", $data);
    }

    public function viewSerial()
    {
        arrPrint(url_segment());
        $produk_id = url_segment(5);
        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        $crs_prs = $pr->callSpecs($produk_id);
        $data_produk = $crs_prs[$produk_id];
        arrPrintHijau($data_produk);


        $this->load->model("Mdls/MdlProdukPerSerialNumber");
        $psn = new MdlProdukPerSerialNumber();

        $this->db->where("produk_id", $produk_id);
        // $srcs = $psn->lookupJoinProduk()->result();
        $srcs = $psn->lookupAll()->result();
        // showLast_query("kuning");
        cekHere(count($srcs));
        // arrPrint($srcs);
        $data_serials = array();
        foreach ($srcs as $src) {
            $produk_sku_part_nama = $src->produk_sku_part_nama;

            $data_serials[$produk_sku_part_nama][] = (array)$src;
        }

        // arrPrint($data_serials);
        /* ----------------------------------------
         * mengatur header/data yg perlu tampil
         * ----------------------------------------*/
        if ($data_produk->jml_serial > 1) {
            $arrHeaders = array(
                "outdoor_nama"  => array(
                    "label"   => "out door",
                    "label_2" => "out door"
                ),
                "indoor_nama_1" => array(
                    "label" => "in door",
                ),
                // "indoor_nama_2" => array(),
                // "indoor_nama_3" => array(),
                // "indoor_nama_4" => array(),
            );
        }
        else {
            $arrHeaders = array(
                "produk_serial_number" => array(
                    "label"   => "serial number (SN)",
                    "label_2" => "out door"
                ),
            );
        }

        /* -------------------------------------
         * data produk
         * -------------------------------------*/
        $tprod = "";
        $tprod .= $data_produk->id;
        $tprod .= " - " . $data_produk->nama;
        $tprod .= " - " . $data_produk->barcode;
        $tprod .= " - " . $data_produk->kode;
        $tprod .= " - " . $data_produk->jml_serial;

        /* -------------------------------------
         * header
         * -------------------------------------*/
        $thead = "<tr class='text-uppercase' style='background-color: cadetblue;'>";
        $thead .= "<th>no</th>";
        foreach ($arrHeaders as $kolom => $kolom_params) {
            $label = isset($kolom_params['label']) ? $kolom_params['label'] : $kolom;
            // arrPrintKuning($crs_prs);
            $label_2 = $data_produk->$kolom;

            foreach ($data_serials as $inoutdoor => $items) {
                $jmlRow[$inoutdoor] = count($items);
            }

            $thead .= "<th title='$kolom' class='text-uppercase text-center'>$label<br>$label_2</th>";
        }
        $thead .= "</tr>";

        /* -------------------------------------
         * body
         * -------------------------------------*/
        $tbody = "";
        // cekBiru(max($jmlRow));
        $jmlBaris = max($jmlRow);
        $no = 0;
        for ($xx = 1; $xx <= $jmlBaris; $xx++) {
            $no++;
            $tbody .= "<tr>";
            $tbody .= "<td>$no</td>";
            foreach ($arrHeaders as $kolom => $kolom_params) {
                $label_2 = $data_produk->$kolom;

                $serial = $data_serials[$label_2][($xx - 1)]["produk_serial_number"];
                // $tbody .= "<td>$xx $kolom $label_2 $serial</td>";
                $tbody .= "<td class='text-center'>$serial</td>";
            }
            $tbody .= "</tr>";
        }

        /* -------------------------------------
         * rendering data // penampil data
         * -------------------------------------*/
        $strData = "";
        $strData .= "<h2 style='margin-top: 0;'>$tprod</h2>";
        $strData .= "<div class='bg-putih'>";
        $strData .= "<table class='table table-striped'>";
        $strData .= $thead;
        $strData .= $tbody;
        $strData .= "</table>";
        $strData .= "</div>";

        echo $strData;
    }

    public function doToggleSerial()
    {
        arrPrintKuning(url_segment());
        arrPrintKuning($_GET);
        $data_ky = $_GET['ky'];
        $data_id = $_GET['id'];
        $data_nama = $_GET['nama'];
        // $data_jml = $_GET['jml_serial'];
        $data_jml = $_GET[$data_ky];
        $mdl = "Mdl" . url_segment(4);
        $this->load->model = ("Mdls/$mdl");
        $md = new $mdl();

        $data_id = str_replace("allow_project_", "", $data_id);
        $data_id = str_replace("is_produk_", "", $data_id);
        $data_id = str_replace("is_rakitan_", "", $data_id);
        $data_id = str_replace("is_jual_", "", $data_id);
        $data_id = str_replace("is_beli_", "", $data_id);
        $data_id = str_replace("is_supplies_", "", $data_id);

        // matiHere(__LINE__);
        switch ($data_ky) {
            case "jml_serial":
                $jml_serial = $data_jml == 1 ? 0 : matiHere("saat ini belum bisa update dari non serial ke serial");
                break;
            case "allow_project":
                $jml_serial = $data_jml;
                break;
            case "is_produk":
                $jml_serial = $data_jml;
                break;
            case "is_rakitan":
                $jml_serial = $data_jml;
                break;
            case "is_jual":
                $jml_serial = $data_jml;
                break;
            case "is_beli":
                $jml_serial = $data_jml;
                break;
            case "is_supplies":
                $jml_serial = $data_jml;
                break;
            case "ppn":
                $jml_serial = $data_jml;
                break;
        }

        $this->db->trans_begin();
        $md->setFilters(array());
        $condites = array(
            "id" => $data_id
        );
        $datas = array(
            $data_ky => $jml_serial
        );
        $up = $md->updateData($condites, $datas);
        showLast_query("merah");

        // ---------------------------------------------------------------------------------------------
        $dataHisNew[] = $datas;
        $datas = array(
            "id"     => $data_id,
            $data_ky => $data_jml,
        );
        $dataHis[] = $datas;

        $this->load->model("Mdls/" . "MdlDataHistory");
        $h = new MdlDataHistory();
        $dataHist = array(
            "orig_id"     => $data_id,
            "mdl_name"    => $mdl,
            "mdl_label"   => url_segment(4),
            "oleh_id"     => my_id(),
            "oleh_name"   => my_name(),
            "old_content" => blobEncode($dataHis),
            "new_content" => blobEncode($dataHisNew),
        );
        $h->addData($dataHist);
        showLast_query("merah");

        // echo lgShowSuccess("$data_nama", "berhasil dirubah menjadi non serial");
        switch ($data_ky) {
            case "jml_serial":

                $suffix = $jml_serial == 1 ? "menjadi <r><b>MODE SERIAL</b></r>" : "menjadi <r><b>NON SERIAL</b></r>";
                echo lgShowSuccess(htmlspecialchars($data_nama), "berhasil dirubah $suffix");

                echo "<script>
                    $('#$data_id').prop('disabled', true);
                </script>";
                break;
            case "allow_project":
                if ($data_jml == 1) {
                    echo lgShowSuccess(htmlspecialchars($data_nama), "sudah bisa diakses sebagai material projek");
                }
                else {

                    echo lgShowSuccess(htmlspecialchars($data_nama), "sudah dikeluarkan dari material projek");
                }
                break;
            case "is_produk":
                if ($data_jml == 1) {
                    echo lgShowSuccess(htmlspecialchars($data_nama), "sudah ditandai sebagai produk");
                }
                else {
                    echo lgShowSuccess(htmlspecialchars($data_nama), "tanda produk sudah dihapus");
                }
                break;
            case "is_rakitan":
                if ($data_jml == 1) {
                    echo lgShowSuccess(htmlspecialchars($data_nama), "sudah ditandai sebagai rakitan");
                }
                else {
                    echo lgShowSuccess(htmlspecialchars($data_nama), "tanda rakitan sudah dihapus");
                }
                break;
            case "is_jual":
                if ($data_jml == 1) {
                    echo lgShowSuccess(htmlspecialchars($data_nama), "sudah ditandai sebagai item jual");
                }
                else {
                    echo lgShowSuccess(htmlspecialchars($data_nama), "tanda jual sudah dihapus");
                }
                break;
            case "is_beli":
                if ($data_jml == 1) {
                    echo lgShowSuccess(htmlspecialchars($data_nama), "sudah ditandai sebagai item beli");
                }
                else {
                    echo lgShowSuccess(htmlspecialchars($data_nama), "tanda beli sudah dihapus");
                }
                break;
            case "is_supplies":
                if ($data_jml == 1) {
                    echo lgShowSuccess(htmlspecialchars($data_nama), "sudah ditandai sebagai supplies");
                }
                else {
                    echo lgShowSuccess(htmlspecialchars($data_nama), "tanda supplies sudah dihapus");
                }
                break;
            case "ppn":
                $alert = "<script>
                    swal({
                        title: 'Berhasil',
                        text: `Perubahan Data disimpan`,
                        type: 'success',                                                
                        showConfirmButton: false,
                        timer: 1000                        
                    });
                </script>";
                echo $alert;
                break;
        }
        // matiHere("belum commit @" . __LINE__);

        $this->db->trans_complete();

    }

    // add billing
    public function addBillForm()
    {
        // arrPrintHijau($_GET);
        $srcGet = $_GET;
        $transaksi_id = $srcGet['tr'];
        $ly = new Layout();
        $ly->setFormGroupLeftClass("col-sm-2 text-uppercase");
        $ly->setFormGroupRightClass("col-sm-10");
        $reload = $_GET['reload'];
        $get_main = isset($_GET['main']) ? "?main=" . $_GET['main'] : "";
        $segment_4 = $this->uri->segment(4);
        $ctrlName = $segment_4;
        $className_main = $className = "Mdl" . $segment_4;
        $kval = $_GET['kval'];
        // $kn = $_GET['jn'];

        $this->load->model("Mdls/$className");
        $o = new $className;
        $fields = $o->getFields();
        // $masterFields = $o->getMasterFields();
        // showLast_query("biru");
        // arrPrint($fields);
        // $anakans = $masterFields[$kval]['anakan'];
        // $field_2 = array_intersect_key($fields, array_flip($anakans));

        /* ---------------------------------------
         * mengirimkan data bill dari editor
         * ---------------------------------------*/
        // cekHere("$indTransksiID ||| $masterID");
        $this->load->model("Mdls/MdlCustomerBillAddress");
        $bcu = new MdlCustomerBillAddress();
        $condites = array(
            "transaksi_id" => $transaksi_id,
        );
        $bcu->setFilters(array());
        // $srckBillDatas = array();
        $srckBillDatas = $bcu->lookupByCondition($condites)->row_array();
        // showLast_query("kuning");

        $fvar = "";
        $varForm = "";
        $strForm = "";
        foreach ($fields as $field_params) {
            $coSpeks = $field_params;

            $editable = isset($coSpeks['editable']) ? $coSpeks['editable'] : true;
            $strEdit = $editable == true ? "" : "disabled ";
            $inputType = $coSpeks['inputType'];
            $label = $coSpeks['label'];
            $kolom = isset($coSpeks['kolom']) ? $coSpeks['kolom'] : "";
            $anakan_ky = $kolom;
            // cekHere("$kolom $strEdit");
            // cekHere("$kolom $fValue");
            $kolom_nama = isset($coSpeks['kolom_nama']) ? $coSpeks['kolom_nama'] : "";
            $fName = $kolom_nama != "" ? $kolom_nama : $kolom;
            $defaultValue = isset($coSpeks['defaultValue']) ? $coSpeks['defaultValue'] : "";
            $defaultValueKey = isset($coSpeks['defaultValueKey']) ? $coSpeks['defaultValueKey'] : "";
            if (count($srckBillDatas)) {
                $fValue = $srckBillDatas[$kolom];
            }
            else {

                $fValue = $defaultValue;
                if (isset($srcGet) && $defaultValueKey != '') {
                    $fValue = $srcGet[$defaultValueKey];
                }
            }


            if (isset($coSpeks['reference'])) {
                $reference = $coSpeks['reference'];
                $referenceClass = $reference ? substr($reference, 3) : "";
                $this->load->model("Mdls/" . $reference);
                $o2 = new $reference;
                $o2->setSortBy(array("kolom" => "id", "mode" => "desc"));
                $dataSrcs = $o2->lookupAll()->result();
                $dataSources = array();
                foreach ($dataSrcs as $key_src => $label_src) {
                    $relId = isset($label_src->id) ? $label_src->id : "";
                    $relLabel = isset($label_src->nama) ? $label_src->nama : "";
                    $dataSources[$relId] = $relLabel;
                }
            }
            else {
                $dataSources = isset($coSpeks['dataSource']) ? $coSpeks['dataSource'] : "";
            }

            $fvar .= "$kolom";

            // cekHijau($inputType);
            switch ($inputType) {
                case "combo":
                    $reference_label = strtoupper($label);
                    $link_add = base_url() . "statik/Data/add/$referenceClass?main=$className_main&kval=$kval";
                    $link_add_act = modalDialogBtn("New $reference_label", $link_add, 0);
                    $btn_add = isset($coSpeks['add_btn']) ? "<span class='input-group-btn'><button type='button' class='btn btn-warning' onclick=\"$link_add_act\"><i class='fa fa-plus'></i></button></span>" : "<span></span>";
                    $optionals = "<option value=''>----$label------</option>";
                    foreach ($dataSources as $key_src => $label_src) {
                        $fSelected = $fValue == $key_src ? "selected" : "";
                        $optionals .= "<option value='$key_src' $fSelected>$label_src</option>";
                    }
                    $varForm = "<div class='input-group input-group-sm'>";
                    $varForm .= "<select class='form-control' name='$kolom' $strEdit>";
                    $varForm .= $optionals;
                    $varForm .= "</select>";
                    $varForm .= $btn_add;
                    $varForm .= "</div>";
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "radio":
                    $varForm = "";
                    foreach ($dataSources as $key_src => $label_src) {
                        $varForm .= "<label><input type='radio' id='$anakan_ky" . "_" . "$key_src' name='$kolom_nama' value='$key_src' $strEdit> $label_src</label>";
                    }
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "text":
                    $varForm = "<input type='text' id='$anakan_ky' name='$kolom' autocomplete='off' class='form-control' value='$fValue' $strEdit>";
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "number":
                    $varForm = "<input type='number' id='$anakan_ky' name='$kolom' class='form-control' value='$fValue' $strEdit>";
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "hidden":
                    // $fValue = $kn;
                    $varForm = "<input type='text' id='$anakan_ky' name='$kolom' value='$fValue' $strEdit>";
                    $strForm .= $ly->form_group($label, $varForm, 1);
                    break;
            }
        }

        /* -------------------------------------------------------------------------------------
         * button sumit
         * -------------------------------------------------------------------------------------*/
        $strButton = "";
        $strButton .= "<div class='col-md-12' style='margin-top: 25px;border-top: #f9f7f7 solid 1px;padding-top: 10px;'>";
        $strButton .= "<button type='button' class='btn btn-default text-uppercase pull-left' data-dismiss='modal'>close</button>";
        $strButton .= "<button type='submit' class='btn btn-danger text-uppercase pull-right'>simpan</button>";
        $strButton .= "</div>";

        /* -------------------------------------------------------------------------------------
         * penampil form di UI
         * -------------------------------------------------------------------------------------*/
        $link_action = MODUL_PATH . "Data/addBillProcess/$segment_4/$segment_4?reload=$reload";
        $var = "";
        $var .= "<style type='text/css'>
            .form-group{
                margin-bottom: 2px !important;
            }
        </style>";

        $var .= "<div class='panel panel-success'>";
        $var .= "<div class='panel-heading'>";

        $var .= "<span class='text-blue text-uppercase'><span class='fa fa-folder-open'> main editor</span>";
        $var .= "</div>";

        $var .= "<div class='panel-body'>";

        $var .= "<div class='row'>";
        $var .= "<form method='post' action='$link_action' target='result' style='margin-top: 0px;'>";
        $var .= $strForm;
        $var .= $strButton;
        $var .= "</form>";
        $var .= "</div>";
        $var .= "</div>"; // body

        echo $var;
    }

    public function addBillProcess()
    {
        arrPrint(url_segment());
        $reload = $_GET['reload'];
        // $link_relaod = $reload;
        $data_new = $_POST;
        arrPrint($data_new);
        $transaksi_id = $data_new['transaksi_id'];
        $this->load->model("Mdls/MdlCustomerBillAddress");
        $bcu = new MdlCustomerBillAddress();
        $condites = array(
            "transaksi_id" => $transaksi_id
        );
        $bcu->setFilters(array());
        $cekDatas = $bcu->lookupByCondition($condites)->row();
        showLast_query("kuning");
        arrPrintHijau($cekDatas);
        // matiHere();
        $data_new["last_update"] = dtimeNow();
        $data_new["oleh_id"] = my_id();
        $data_new["oleh_nama"] = my_name();
        $this->db->trans_start();

        if (count($cekDatas) == 0) {

            $bcu->addData($data_new);
            showLast_query("hijau");

            /*memtatikan bukaan akses*/

            echo lgShowSuccess("bill berhasil ditambahkan");
        }
        else {
            $currentData = (array)$cekDatas;
            unset($data_new['id']);
            // $currentData = $data_new;
            foreach ($data_new as $key => $value) {
                if (array_key_exists($key, $currentData) && $currentData[$key] != $value) {
                    $dataUpds[$key] = $value;
                }
            }

            if (count($dataUpds) > 1) {
                $condites = array(
                    "id" => $cekDatas->id,
                );
                $bcu->updateData($condites, $dataUpds);
                showLast_query("hijau");
                echo lgShowSuccess("Update Berhasil", 'data yang baru sudah tersimpan');
            }
            else {
                echo lgShowError("Upss", 'tidak terdeteksi perubahan data');
            }

            /* ------------------------------------------------------------------------
             * medisable print dan editor di lock lagi setelah edit
             * ------------------------------------------------------------------------*/
            $this->load->model("Mdls/MdlSetting");
            $st = new MdlSetting();
            $st->updateInvoicing();

            $this->load->model("Mdls/MdlPrintLog");
            $pl = new MdlPrintLog();
            $pl->closePrint($transaksi_id);

        }

        // mati_disini(__FUNCTION__ . "  TRANSAKSI BERHASIL (UNDER MAINTENANCE)  <br> silahkan tutup browser terlebih dahulu. <br>execute in " . $val . " ms");
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        topReload();
    }

    public function cekMasterData()
    {
        // arrPrintHijau(url_segment());
        $mdl = "Mdl" . ucfirst(url_segment(4));

        // arrPrint(url_segment());
        // arrPrintHijau($_GET);
        // cekMerah("$mdl");

        $kolom = $_GET['kolom'];
        $kword_0 = $_GET['key'];
        $id = isset($_GET['id']) ? $_GET['id'] : "";

        $kword_00 = explode(" ", $kword_0);
        $input_id = isset($_GET['mid']) ? $_GET['mid'] : "";
        $this->load->model("Mdls/$mdl");
        $pr = new $mdl();
        $count_kword = strlen($kword_0);
        /*--keyword hasil explode */
        $listedFieldsSelectItem = $pr->getListedFieldsSelectItem();
        // arrPrintKuning($listedFieldsSelectItem);

        $src_pr_obj = array();
        $produk_ids = array();
        $jml_data = 0;
        if ($count_kword > 0) {
            foreach ($kword_00 as $kword) {
                if ($id == "") {
                    foreach ($listedFieldsSelectItem as $itemKolom) {
                        $condites[$itemKolom] = $kword;
                    }
                    // arrPrintKuning($condites);

                    // $condites = array(
                    //     "label like"   => "%$kword%",
                    //     "nama like"    => "%$kword%",
                    //     "kode like"    => "%$kword%",
                    //     "barcode like" => "%$kword%",
                    // );
                }
                else {
                    $condites = array(
                        "id" => $id,
                    );
                }


                $this->db->group_start();
                // $this->db->or_where($condites);
                $this->db->or_like($condites);
                $this->db->group_end();
            }

            $this->db->limit(10);
            // $src_pr_obj_0 = $pr->lookUpAll();
            // $src_pr_obj = $src_pr_obj_0->result();
            $src_pr_obj = $pr->callSpecs();
            // showLast_query("kuning");

            $this->formLoader($src_pr_obj, $kolom);

            $jml_data = sizeof($src_pr_obj);
            // cekBiru(sizeof($src_pr_obj));
            $produk_ids = array_keys($src_pr_obj);
            /*-----------produk harga------------*/
            $prod_hargas = array();
            if (sizeof($produk_ids) > 0) {

                $this->load->model("Mdls/MdlHargaProduk");
                $hp = new MdlHargaProduk();
                $hp->setTokoId(my_toko_id());
                $hp->setCabangId(my_cabang_id());
                // $this->db->where("jenis_value", "harga_list");
                $this->db->where("jenis_value", "hpp");
                $prod_hargas = $hp->callSpecs($produk_ids);
            }
            // showLast_query("kuning");
            // arrPrintKuning($prod_hargas);
            $harga_list = array();
            foreach ($prod_hargas as $prod_id => $prod_harga_00s) {
                foreach ($prod_harga_00s as $prod_harga) {
                    $nilai = $prod_harga->nilai * 1;
                    $harga_list[$prod_id] = $nilai;
                }
            }
            // arrPrintHijau($harga_list);
        }
        $var = "";
        $var_isi = "";
        $btn_hidde = "";
        // matiHere(__LINE__);
        // $btn_hidde = "<button type='button' onclick=\"$('#hasil_$input_id').fadeOut();\">hidde</button>";
        if (sizeof($src_pr_obj) > 0) {

            $var = $jml_data;
            // $link_insert = MODUL_PATH . "Data/formLoader/ProdukData";
            $link_insert = MODUL_PATH . "Data/cekMasterData/ProdukData";
            $var_isi .= "<ol class='todo-list ui-sortable'>";
            $var_isi .= "<li style='padding: 3px 5px;'>data yang sudah exis</li>";
            foreach ($src_pr_obj as $item) {
                // arrPrint($item);
                $id = $item->id;
                // $input_id = "";
                $kode = $item->kode;
                $nama = $item->nama;
                $satuan = isset($item->satuan) ? $item->satuan : "-";
                $harga_jual = isset($harga_list[$id]) ? $harga_list[$id] : 0;
                $harga_jual_f = formatField_he_format("harga", $harga_jual);

                $nama_0 = "$nama $kode";
                $nama_f = highlight_he_format($nama_0, $kword_0);
                // $nama_ff = highlight_2($nama,$kword_0);
                $datas_embd = "";
                foreach ($item as $item_key => $item_val) {
                    $datas_embd .= "data-$item_key='" . htmlspecialchars($item_val, ENT_QUOTES) . "' ";
                }

                // $var_isi .= "<li style='padding: 3px 5px;' title='$id'><a href='javascript:void(0)' onclick=\"$('#$input_id').val('$nama');$('#harga_$input_id').val('$harga_jual');$('#harga_text_$input_id').val('$harga_jual');\">$nama_f ($satuan) $harga_jual_f</a></li>";
                // $var_isi .= "<li style='padding: 3px 5px;' title='$id'><a href='javascript:void(0)' onclick=\"$('#form_loader').load('$link_insert?id=$id&key='".urlencode($nama).");\">$nama_f $satuan</a></li>";
                $var_isi .= "<li class='sugestion-item' $datas_embd onmouseover=\"previewFromData(this);\" style='padding: 3px 5px;' title='$id'><a href='javascript:void(0)' onclick=\"$('#notif_kode').load('$link_insert?id=$id&key=" . urlencode($kode) . "');\">$nama_f $satuan</a></li>";
            }
            $var_isi .= "</ol>";
            $var_isi .= " <br><button type='button' class='btn btn-info btn-xs' onclick=\"cari_dari_api_lain('$kword_0', '$input_id');\">cari $kword_0 ke data center</button>";


            $data_lokal_ids = json_encode(array_map(function ($item) {
                return $item->dc_id;
            }, $src_pr_obj));


            // $data_lokal_ids = ($src_pr_obj);
            cekHere($data_lokal_ids);
            $var_isi .= "<script>
                function previewFromData(el) {
                    var data = $(el).data();
                    var html = '<strong>' + (data.nama || '-') + '</strong><br>' +
                               'Alamat: ' + (data.alamat_1 || '-') + '<br>' +
                               'Satuan: ' + (data.tlp_1 || '-') + '<br>' +
                               'Kredit limit: ' + (data.kredit_limit || '-') + '<br>' +
                               'NPWP: ' + (data.npwp || '-');
                               'KTP: ' + (data.ktp || '-');
                    $('#detail_preview').html(html);
                }
    
                 var data_lokal_ids = '$data_lokal_ids';
                 
                top.$('#submit_data').prop('disabled', true);
                // $('#detail_preview').html('data sudah exis, masukan data yang lain');
            </script>";
        }
        $display = "display:block;";
        if ($jml_data > 10) {
            // matiHere(__LINE__);
            echo "<div style='wwidth: 100px'>ditemukan <span style='font-size: 1.3em;color: red;'>$var</span> item yang berkaitan dengan <span class='text-red'>$kword_0</span>";
            // echo "<button type='button' onclick=\"$('#hasil_$input_id').fadeIn();\">tampilkan</button> <button type='button' onclick=\"$('#hasil_$input_id').fadeOut();\">hidde</button>";
            echo "</div>";
            // $display = "display:none;";
            // echo $var_isi;
            // $btn_hidde = "tulisakan nama produk";
        }
        elseif ($jml_data == 0) {
            // matiHere(__LINE__);
            $var_isi = "";
            // $var_isi = "tulisakan nama produk";
            if ($count_kword > 0) {

                $var_isi = "di local tidak ditemukan data yang berhubunga dengan <span class='font-size-1-2 text-red'>$kword_0</span>";
                $var_isi .= "<br> <button type='button' class='btn btn-info btn-xs' onclick=\"cari_dari_api_lain('$kword_0', '$input_id');\">cari ke data center</button>";
            }

            // edited by glg (18:30 WIB, 2025-12-16)
            // change: menambahkan definisi data_lokal_ids sebagai array kosong untuk kondisi $jml_data == 0
            // technical rationale: variabel data_lokal_ids diperlukan oleh fungsi cari_dari_api_lain() di line 2709 untuk mengecek item.id yang sudah exist. Tanpa definisi ini, akan terjadi ReferenceError saat tombol "cari ke data center" diklik.
            $var_isi .= "<script>
                var data_lokal_ids = [];
                top.$('#submit_data').prop('disabled', false);
            </script>";
        }
        // else{
        //     $btn_hidde = "<button type='button' onclick=\"$('#hasil_$input_id').fadeOut();\">hidde</button>";
        // }
        echo "<div style='
                position: fixed;
                top: 140px;
                left: 273px;
                display: flex;
                gap: 16px;
                z-index: 9999;' id='sugestion-box'>";
        echo " <button type='button' onclick=\"document . getElementById('sugestion-box') . style . display = 'none'\" 
                style='
                    position: absolute;
                    top: 5px;
                    right: 5px;
                    background: transparent;
                    border: none;
                    font-size: 16px;
                    cursor: pointer;
                '>×</button>";
        echo "<div style='width: 300px; $display' class='sugestion-box' id='hasil_$input_id'>$var_isi <hr style='padding: 0;margin: 10px 0 0;'> $btn_hidde</div>";
        echo "<div id=\"detail_preview\" class=\"detail-preview\"></div>";
        echo "</div>";

    }

    public function cekMasterDataEmployeeDc()
    {
        $startedAt = microtime(true);
        $keywordRaw = isset($_GET['key']) ? $_GET['key'] : "";
        $keyword = trim(urldecode($keywordRaw));
        $inputId = isset($_GET['mid']) ? $_GET['mid'] : "dc_id";
        $minLength = 4;

        if (strlen($keyword) < $minLength) {
            $elapsedMs = (int)round((microtime(true) - $startedAt) * 1000);
            log_message('info', "EmployeeDcLookup short_keyword|keyword_len=" . strlen($keyword) . "|input_id=" . $inputId . "|elapsed_ms=" . $elapsedMs);
            echo "<div class='text-warning'>Ketik minimal $minLength karakter untuk mencari data salesman ke data center.</div>";

            return;
        }

        $safeKeyword = htmlspecialchars($keyword, ENT_QUOTES);
        $encodedKeyword = rawurlencode($keyword);
        $candidates = array(
            //            ADM_DOMAIN . "eusvc/Employees/seeItemAll/search/" . $encodedKeyword,
            ADM_DOMAIN . "eusvc/Employee/seeItemAll/search/" . $encodedKeyword,
            //            ADM_DOMAIN . "eusvc/Users/seeItemAll/search/" . $encodedKeyword,
        );

        $apiData = array();
        $apiMsg = "";
        $usedUrl = "";
        $hadConnectionIssue = false;
        $statusInvalid = false;
        $attemptCount = 0;
        foreach ($candidates as $candidateUrl) {
            //            print_r($candidateUrl);
            //            print_r($candidateUrl);
            $attemptCount++;
            $usedUrl = $candidateUrl;
            $response = call_curl($candidateUrl);

            if (!is_array($response)) {
                $hadConnectionIssue = true;
                continue;
            }

            if (isset($response["status"]) && (strtolower((string)$response["status"]) === "error" || $response["status"] === false)) {
                $statusInvalid = true;
                $apiMsg = isset($response["message"]) ? $response["message"] : "";
                continue;
            }

            if (isset($response["data"]) && is_array($response["data"])) {
                $apiData = $response["data"];
                $apiMsg = isset($response["message"]) ? $response["message"] : "";
                break;
            }
        }

        if (!is_array($apiData) || count($apiData) === 0) {
            $elapsedMs = (int)round((microtime(true) - $startedAt) * 1000);
            log_message(
                'info',
                "EmployeeDcLookup empty_or_failed|keyword=" . $keyword
                . "|attempts=" . $attemptCount
                . "|conn_issue=" . ($hadConnectionIssue ? "1" : "0")
                . "|status_invalid=" . ($statusInvalid ? "1" : "0")
                . "|endpoint=" . $usedUrl
                . "|elapsed_ms=" . $elapsedMs
            );
            $msg = "Data salesman tidak ditemukan di data center untuk kata kunci <span class='text-red'>{$safeKeyword}</span>.";
            $alertClass = "alert-warning";
            if ($hadConnectionIssue) {
                $alertClass = "alert-danger";
                $msg = "Gagal terhubung ke data center. Silakan coba lagi beberapa saat.";
            }
            elseif ($statusInvalid) {
                $alertClass = "alert-danger";
                $msg = "Data center merespons error saat pencarian salesman.";
            }
            if (strlen($apiMsg) > 0) {
                $msg .= "<br><small class='text-muted'>Info API: " . htmlspecialchars($apiMsg, ENT_QUOTES) . "</small>";
            }
            if (strlen($usedUrl) > 0) {
                $msg .= "<br><small class='text-muted'>Endpoint: " . htmlspecialchars($usedUrl, ENT_QUOTES) . "</small>";
            }
            echo "<div class='alert {$alertClass}' style='margin-top:4px;padding:8px;'>$msg</div>";

            return;
        }

        $elapsedMs = (int)round((microtime(true) - $startedAt) * 1000);
        log_message(
            'info',
            "EmployeeDcLookup success|keyword=" . $keyword
            . "|attempts=" . $attemptCount
            . "|result_count=" . count($apiData)
            . "|endpoint=" . $usedUrl
            . "|elapsed_ms=" . $elapsedMs
        );

        $html = "";
        $html .= "<div id='sugestion-box' style='position: fixed; top: 140px; left: 273px; display: flex; gap: 16px; z-index: 9999;'>";
        $html .= "<button type='button' onclick=\"document.getElementById('sugestion-box').style.display='none'\" style='position:absolute;top:5px;right:5px;background:transparent;border:none;font-size:16px;cursor:pointer;'>×</button>";
        $html .= "<div style='width: 340px; display:block;' class='sugestion-box' id='hasil_" . htmlspecialchars($inputId, ENT_QUOTES) . "'>";
        $html .= "<ol class='todo-list ui-sortable'>";
        $html .= "<li style='padding: 3px 5px;'>hasil data center (" . count($apiData) . " data)</li>";

        foreach ($apiData as $item) {
            if (!is_array($item)) {
                continue;
            }

            $dcId = isset($item["id"]) ? $item["id"] : (isset($item["dc_id"]) ? $item["dc_id"] : "");
            $name = isset($item["nama"]) ? $item["nama"] : (isset($item["name"]) ? $item["name"] : "");
            $login = isset($item["nama_login"]) ? $item["nama_login"] : (isset($item["username"]) ? $item["username"] : "");
            $email = isset($item["email"]) ? $item["email"] : "";
            $phone = isset($item["tlp_1"]) ? $item["tlp_1"] : (isset($item["phone"]) ? $item["phone"] : "");
            $statusName = isset($item["status_nama"]) ? $item["status_nama"] : (isset($item["status"]) ? $item["status"] : "");
            $cabangName = isset($item["cabang_name"]) ? $item["cabang_name"] : (isset($item["cabang_nama"]) ? $item["cabang_nama"] : "");

            if ($dcId === "" && $name === "") {
                continue;
            }

            $dcIdEsc = htmlspecialchars((string)$dcId, ENT_QUOTES);
            $nameEsc = htmlspecialchars((string)$name, ENT_QUOTES);
            $loginEsc = htmlspecialchars((string)$login, ENT_QUOTES);
            $emailEsc = htmlspecialchars((string)$email, ENT_QUOTES);
            $phoneEsc = htmlspecialchars((string)$phone, ENT_QUOTES);
            $statusEsc = htmlspecialchars((string)$statusName, ENT_QUOTES);
            $cabangEsc = htmlspecialchars((string)$cabangName, ENT_QUOTES);

            $displayName = $nameEsc !== "" ? $nameEsc : "-";
            $displayCabang = $cabangEsc !== "" ? " <small class='text-muted'>[cabang: $cabangEsc]</small>" : "";

            $itemPayload = "data-id='$dcIdEsc' data-nama='$nameEsc' data-login='$loginEsc' data-email='$emailEsc' data-phone='$phoneEsc' data-status='$statusEsc' data-cabang='$cabangEsc'";
            $safeInputId = htmlspecialchars((string)$inputId, ENT_QUOTES);
            $onclick = "onclick=\"(function(){"
                . "var targetInputId='$safeInputId';"
                . "if(document.getElementById('dc_id')){document.getElementById('dc_id').value='$dcIdEsc';}"
                . "if(targetInputId && document.getElementById(targetInputId)){document.getElementById(targetInputId).value='$nameEsc';}"
                . "if(document.getElementsByName('dc_nama').length && document.getElementsByName('dc_nama')[0].value===''){document.getElementsByName('dc_nama')[0].value='$nameEsc';}"
                . "if(document.getElementById('nama') && document.getElementById('nama').value===''){document.getElementById('nama').value='$nameEsc';}"
                . "if(document.getElementById('nama_login') && document.getElementById('nama_login').value==='' && '$loginEsc'!==''){document.getElementById('nama_login').value='$loginEsc';}"
                . "document.getElementById('sugestion-box').style.display='none';"
                . "})()\"";

            $html .= "<li class='sugestion-item' $itemPayload style='padding: 3px 5px; cursor:pointer;' $onclick>"
                . "<a href='javascript:void(0)'>$displayName$displayCabang</a></li>";
        }

        $html .= "</ol>";
        //        $html .= "<small class='text-muted'>Sumber: " . htmlspecialchars($usedUrl, ENT_QUOTES) . "</small>";
        $html .= "</div>";
        $html .= "<div id='detail_preview' class='detail-preview'></div>";
        $html .= "</div>";
        $html .= "<script>
            $('#hasil_" . htmlspecialchars($inputId, ENT_QUOTES) . " .sugestion-item').hover(function() {
                var el = $(this);
                var detail = '<strong>' + (el.data('nama') || '-') + '</strong>' +
                    '<br>cabang: ' + (el.data('cabang') || '-') +
                    '<br>email: ' + (el.data('email') || '-') +
                    '<br>no telp: ' + (el.data('phone') || '-');
                $('#detail_preview').html(detail);
            }, function() {
                $('#detail_preview').html('');
            });
        </script>";

        echo $html;
    }

    public function cekUsernameAjax()
    {
        $this->load->database();

        $username = $this->input->get('username');
        $id = $this->input->get('id');

        $this->load->model("Mdls/MdlEmployee");
        $emp = new MdlEmployee();

        $escapedUsername = $this->db->escape_str($username);
        $emp->addFilter("nama_login='" . $escapedUsername . "'");

        if (!empty($id)) {
            $escapedId = $this->db->escape_str($id);
            $emp->addFilter("id<>'" . $escapedId . "'");
        }

        $exists = $emp->lookupAll()->num_rows();

        if ($exists > 0) {
            echo "<span class='text-danger' style='font-size: 85%; font-weight: bold;'><i class='fa fa-times-circle'></i> Login ID sudah terpakai!</span>
                  <script>$('#submit_data').prop('disabled', true);</script>";
        }
        else {
            echo "<span class='text-success' style='font-size: 85%; font-weight: bold;'><i class='fa fa-check-circle'></i> Login ID tersedia</span>
                  <script>$('#submit_data').prop('disabled', false);</script>";
        }
    }

    public function formLoader($kiriman_datas, $kolom, $reset = false)
    {
        $var = "";
        // arrPrintPink($kiriman_datas);
        if (count($kiriman_datas) == 1) {
            foreach ($kiriman_datas as $kiriman_data) {

                $data_json = json_encode($kiriman_data);
            }

            // $var = cekAlert("uhuii");
            $var .= "<script>
                let data = $data_json;
                
                // console.log(data);            
                
                Object.entries(data).forEach(([key, value]) => {
                    // $('#_'+key).val(data[key]);
                    $('input[name='+ key +']').val(data[key]);
                    
                    // console.log('Key2:', key);
                    // console.log('value:', value);
                });
         
            </script>";

            // echo $var;
        }
        else {
            $var .= "<script>
                
               function resetValues() {
                    let kolom = '$kolom';
                   
                    $('input').each(function() {
                           if ($(this).attr('name') != kolom) {
                           $(this).val('');
                        }
                    });
                }
                resetValues();
                
               // $('.form-control').val('');                
                        
            </script>";
            if ($reset == true) {
                echo $var;
            }
        }
    }

    public function addSupplierApi($datas)
    {
        /* ---------------------------------------------------
         * kumpulan link APi
         * ---------------------------------------------------*/

        $this->load->config("heApi");
        $apiUrls = $this->config->item("heApi");
        $cabangData_link = $apiUrls["cabang"];
        $explUrl = explode("/", $cabangData_link);
        cekHere("$cabangData_link");
        if (isset($datas) && is_array($datas)) {
            $tmpO = $datas;
        }
        else {
            $tmpO = call_curl($cabangData_link);
        }
        arrPrint($tmpO);

        $nama_login = $explUrl[2];
        $this->db->trans_start();

        foreach ($tmpO as $itemCabangs) {
            $cbid = $itemCabangs["id"];
            $cbnama = $itemCabangs["nama"];
            $condites = array(
                "member_id"  => $cbid,
                "nama_login" => $nama_login,
            );

            $this->db->where($condites);

            // arrPrintHijau($this->fieldKoloms);
            $this->db->select($this->fieldKoloms);
            $existing = $this->mdl->lookupAll()->result();
            showLast_query("biru");
            arrPrintKuning($existing);

            if (count($existing) == 0) {
                $databaru = array(
                    "member_id"   => $cbid,
                    "nama"        => $cbnama,
                    "is_supplier" => 1,
                    "nama_login"  => $nama_login,
                    "tlp_1"       => $itemCabangs["tlp_1"],
                    "alamat_1"    => $itemCabangs["alamat"],
                    "kabupaten"   => $itemCabangs["kabupaten"],
                    "propinsi"    => $itemCabangs["propinsi"],
                    // "status" => 1,
                );

                $this->mdl->addData($databaru);
                showLast_query("biru");
            }
            else {
                $exis[$cbid] = $itemCabangs;
            }
        }

        arrPrintHijau($exis);

        // matiHere("belum comit");
        $this->db->trans_complete();

        return "ok";
    }

    private function resolveSyncLockPath($safeLockKey)
    {
        $candidateDirs = array(
            APPPATH . "cache/sync_locks",
            (defined("FCPATH") ? FCPATH : "") . "uploads/sync_locks",
        );

        foreach ($candidateDirs as $candidateDir) {
            if ($candidateDir == "") {
                continue;
            }
            if (!is_dir($candidateDir)) {
                @mkdir($candidateDir, 0775, true);
            }
            if (is_dir($candidateDir) && is_writable($candidateDir)) {
                return rtrim($candidateDir, "/\\") . "/" . $safeLockKey . ".lock";
            }
        }

        $tmpDir = rtrim(sys_get_temp_dir(), "/\\");
        if ($tmpDir != "" && is_dir($tmpDir) && is_writable($tmpDir)) {
            return $tmpDir . DIRECTORY_SEPARATOR . "san_sarana_" . $safeLockKey . ".lock";
        }

        return "";
    }

    private function acquireSyncLock($lockKey, &$lockHandle = null)
    {
        $lockHandle = null;
        $safeLockKey = preg_replace("/[^a-zA-Z0-9_\\-]/", "_", $lockKey);

        // Prioritaskan advisory lock DB agar aman pada lingkungan multi-node
        // dan tidak tergantung permission filesystem.
        if (isset($this->db) && is_object($this->db)) {
            $lockScopeToko = function_exists("my_toko_id") ? (int)my_toko_id() : 0;
            $lockScopeCabang = function_exists("my_cabang_id") ? (int)my_cabang_id() : 0;
            $dbLockName = "sync_" . $safeLockKey . "_" . $lockScopeToko . "_" . $lockScopeCabang;
            if (strlen($dbLockName) > 64) {
                $dbLockName = substr($dbLockName, 0, 64);
            }

            $dbLockQuery = $this->db->query("SELECT GET_LOCK(?, 0) AS is_locked", array($dbLockName));
            if ($dbLockQuery) {
                $dbLockRow = $dbLockQuery->row_array();
                $isLocked = isset($dbLockRow["is_locked"]) ? (int)$dbLockRow["is_locked"] : 0;
                if ($isLocked === 1) {
                    $lockHandle = array(
                        "type" => "db",
                        "name" => $dbLockName,
                    );
                    return array(
                        "status"  => true,
                        "message" => "",
                    );
                }
                if ($isLocked === 0) {
                    return array(
                        "status"  => false,
                        "message" => "Sinkronisasi sedang berjalan. Tunggu proses sebelumnya selesai.",
                    );
                }
            }
        }

        $lockPath = $this->resolveSyncLockPath($safeLockKey);
        if ($lockPath == "") {
            if (function_exists("log_message")) {
                log_message("error", "sync lock path not writable for key: " . $safeLockKey);
            }
            return array(
                "status"  => false,
                "message" => "Gagal membuat lock sinkronisasi (path lock tidak writable)",
            );
        }

        $fp = @fopen($lockPath, "c+");
        if (!$fp) {
            if (function_exists("log_message")) {
                log_message("error", "sync lock fopen failed: " . $lockPath);
            }
            return array(
                "status"  => false,
                "message" => "Gagal membuat lock sinkronisasi",
            );
        }

        if (!@flock($fp, LOCK_EX | LOCK_NB)) {
            @fclose($fp);
            return array(
                "status"  => false,
                "message" => "Sinkronisasi sedang berjalan. Tunggu proses sebelumnya selesai.",
            );
        }

        @ftruncate($fp, 0);
        @fwrite($fp, date("Y-m-d H:i:s"));
        @fflush($fp);
        $lockHandle = $fp;

        return array(
            "status"  => true,
            "message" => "",
        );
    }

    private function releaseSyncLock($lockHandle = null)
    {
        if (is_array($lockHandle) && isset($lockHandle["type"]) && $lockHandle["type"] === "db") {
            $dbLockName = isset($lockHandle["name"]) ? $lockHandle["name"] : "";
            if ($dbLockName != "" && isset($this->db) && is_object($this->db)) {
                $this->db->query("SELECT RELEASE_LOCK(?)", array($dbLockName));
            }
            return;
        }

        if (is_resource($lockHandle)) {
            @flock($lockHandle, LOCK_UN);
            @fclose($lockHandle);
        }
    }

    private function outputSyncJson($payload, $httpCode = null)
    {
        $this->syncJsonResponseSent = true;
        $debugSync = $this->isSyncDebugMode();
        if ($debugSync && is_array($payload) && !isset($payload["_debug_io"])) {
            $payload["_debug_io"] = array(
                "step"                  => $this->syncLastStep,
                "time"                  => date("Y-m-d H:i:s"),
                "uri"                   => isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : "",
                "ob_level_before_clean" => ob_get_level(),
            );
        }
        while (ob_get_level() > 0) {
            @ob_end_clean();
        }
        if (!headers_sent()) {
            if ($httpCode !== null) {
                http_response_code((int)$httpCode);
            }
            header("Content-Type: application/json; charset=utf-8");
            if ($debugSync) {
                header("X-Sync-Debug: 1");
                header("X-Sync-Step: " . ($this->syncLastStep != "" ? $this->syncLastStep : "na"));
            }
        }
        $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR);
        if ($json === false) {
            $fallback = array(
                "status"  => false,
                "message" => "Gagal membentuk respons JSON: " . json_last_error_msg(),
            );
            if (is_array($payload) && isset($payload["message"])) {
                $fallback["source_message"] = (string)$payload["message"];
            }
            $json = json_encode($fallback, JSON_UNESCAPED_UNICODE);
            if ($json === false) {
                $json = '{"status":false,"message":"Gagal membentuk respons JSON"}';
            }
        }
        echo $json;
    }

    private function isSyncDebugMode()
    {
        if (isset($_GET["sync_debug"]) && (string)$_GET["sync_debug"] === "1") {
            return true;
        }
        if (function_exists("show_debuger")) {
            return show_debuger() == 1;
        }

        return false;
    }

    private function appendSyncTrace(&$trace, $enabled, $step, $context = array())
    {
        $this->syncLastStep = $step;
        if (!$enabled) {
            return;
        }
        $entry = array(
            "time" => date("H:i:s"),
            "step" => $step,
        );
        if (is_array($context) && count($context) > 0) {
            $entry["context"] = $context;
        }
        $trace[] = $entry;
    }

    private function appendSyncDebugFile($label, $context = array())
    {
        if (!$this->isSyncDebugMode()) {
            return;
        }
        $dir = APPPATH . "cache";
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }
        if (!is_dir($dir) || !is_writable($dir)) {
            return;
        }
        $path = rtrim($dir, "/\\") . DIRECTORY_SEPARATOR . "sync_debug_trace.log";
        $line = date("Y-m-d H:i:s") . " | " . $label;
        if (is_array($context) && count($context) > 0) {
            $json = json_encode($context, JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR);
            if ($json !== false) {
                $line .= " | " . $json;
            }
        }
        $line .= PHP_EOL;
        @file_put_contents($path, $line, FILE_APPEND | LOCK_EX);
    }

    private function markSyncJobFailed($jobId, $message)
    {
        $jobId = is_numeric($jobId) ? (int)$jobId : 0;
        if ($jobId < 1 || !isset($this->db) || !is_object($this->db)) {
            return;
        }
        if (!$this->db->table_exists("sync_jobs")) {
            return;
        }
        $safeMsg = is_string($message) ? $message : "Job gagal";
        $this->db->where("id", $jobId)->update("sync_jobs", array(
            "status"      => "failed",
            "message"     => $safeMsg,
            "updated_at"  => dtimeNow(),
            "finished_at" => dtimeNow(),
        ));
    }

    private function mergeUniqueCustomerNames($baseNames, $newNames, $maxItems = 12)
    {
        $base = is_array($baseNames) ? $baseNames : array();
        $incoming = is_array($newNames) ? $newNames : array();
        $merged = array();

        foreach ($base as $name) {
            $n = trim((string)$name);
            if ($n === "") {
                continue;
            }
            if (!in_array($n, $merged, true)) {
                $merged[] = $n;
            }
            if (count($merged) >= $maxItems) {
                return $merged;
            }
        }

        foreach ($incoming as $name) {
            $n = trim((string)$name);
            if ($n === "") {
                continue;
            }
            if (!in_array($n, $merged, true)) {
                $merged[] = $n;
            }
            if (count($merged) >= $maxItems) {
                break;
            }
        }

        return $merged;
    }

    private function buildMissingDcNotice($missingCount, $missingNames = array())
    {
        $count = is_numeric($missingCount) ? (int)$missingCount : 0;
        if ($count < 1) {
            return "";
        }

        $names = is_array($missingNames) ? $missingNames : array();
        $cleanNames = array();
        foreach ($names as $name) {
            $n = trim((string)$name);
            if ($n === "") {
                continue;
            }
            if (!in_array($n, $cleanNames, true)) {
                $cleanNames[] = $n;
            }
            if (count($cleanNames) >= 5) {
                break;
            }
        }

        if (count($cleanNames) > 0) {
            $label = implode(", ", $cleanNames);
            if ($count > count($cleanNames)) {
                $sisa = $count - count($cleanNames);
                $label .= " dan " . $sisa . " customer lainnya";
            }
            return "Customer " . $label . " belum terhubung ke data Holding Company, jadi belum bisa disinkronkan.";
        }

        return "Ada " . $count . " customer yang belum terhubung ke data Holding Company, jadi belum bisa disinkronkan.";
    }

    private function normalizeFailedSyncEntry($entry)
    {
        $arr = is_array($entry) ? $entry : array();
        $name = "";
        foreach (array("customer_name", "nama", "name", "customer") as $key) {
            if (isset($arr[$key])) {
                $tmp = trim((string)$arr[$key]);
                if ($tmp !== "") {
                    $name = $tmp;
                    break;
                }
            }
        }
        if ($name === "") {
            $name = "Customer tanpa nama";
        }

        $stage = isset($arr["stage"]) ? trim((string)$arr["stage"]) : "";
        $message = isset($arr["message"]) ? trim((string)$arr["message"]) : "";

        return array(
            "customer_name" => $name,
            "stage"         => $stage,
            "message"       => $message,
        );
    }

    private function mergeFailedSyncEntries($baseEntries, $newEntries, $maxItems = 20)
    {
        $maxItems = is_numeric($maxItems) ? (int)$maxItems : 20;
        if ($maxItems < 1) {
            $maxItems = 20;
        }

        $merged = array();
        $seen = array();

        foreach (array($baseEntries, $newEntries) as $source) {
            if (!is_array($source)) {
                continue;
            }
            foreach ($source as $entry) {
                $normalized = $this->normalizeFailedSyncEntry($entry);
                $key = strtolower(
                    trim((string)$normalized["customer_name"]) . "|" .
                    trim((string)$normalized["stage"]) . "|" .
                    trim((string)$normalized["message"])
                );
                if ($key === "" || isset($seen[$key])) {
                    continue;
                }
                $seen[$key] = 1;
                $merged[] = $normalized;
                if (count($merged) >= $maxItems) {
                    return $merged;
                }
            }
        }

        return $merged;
    }

    private function mapFailedSyncReasonToFriendly($stage, $message)
    {
        $stage = strtolower(trim((string)$stage));
        $rawMessage = strtolower(trim((string)$message));

        if ($stage === "fetch_remote") {
            if (strpos($rawMessage, "tidak ditemukan") !== false || strpos($rawMessage, "not found") !== false) {
                return "Data customer belum ditemukan di Holding Company.";
            }
            if (
                strpos($rawMessage, "timeout") !== false
                || strpos($rawMessage, "timed out") !== false
                || strpos($rawMessage, "failed to connect") !== false
                || strpos($rawMessage, "could not resolve host") !== false
                || strpos($rawMessage, "connection") !== false
                || strpos($rawMessage, "curl") !== false
            ) {
                return "Koneksi ke server Holding Company sedang bermasalah.";
            }
            if (
                strpos($rawMessage, "tidak valid") !== false
                || strpos($rawMessage, "invalid") !== false
                || strpos($rawMessage, "parse") !== false
            ) {
                return "Balasan data dari Holding Company belum bisa dibaca.";
            }
            return "Data customer dari Holding Company belum bisa diambil.";
        }

        if ($stage === "upsert_customer") {
            if (strpos($rawMessage, "duplicate") !== false) {
                return "Data customer bentrok dengan data yang sudah ada di aplikasi.";
            }
            if (
                strpos($rawMessage, "cannot be null") !== false
                || strpos($rawMessage, "null") !== false
                || strpos($rawMessage, "tidak valid") !== false
            ) {
                return "Data customer dari Holding Company belum lengkap.";
            }
            return "Data customer gagal disimpan ke aplikasi.";
        }

        if ($stage === "sync_address") {
            if (strpos($rawMessage, "tabel address tidak ditemukan") !== false) {
                return "Data alamat belum bisa disimpan karena konfigurasi alamat belum siap.";
            }
            if (strpos($rawMessage, "duplicate") !== false) {
                return "Data alamat bentrok dengan alamat yang sudah ada.";
            }
            return "Data alamat customer gagal diperbarui.";
        }

        if ($stage === "transaction") {
            return "Penyimpanan dibatalkan karena gangguan pada database.";
        }

        if ($rawMessage !== "") {
            return "Terjadi kendala saat memproses data customer.";
        }

        return "Terjadi kendala saat sinkronisasi customer.";
    }

    private function buildFailedSyncNotice($failedCount, $failedEntries = array())
    {
        $count = is_numeric($failedCount) ? (int)$failedCount : 0;
        if ($count < 1) {
            return "";
        }

        $rows = is_array($failedEntries) ? $failedEntries : array();
        $lines = array();
        $lines[] = $count . " customer belum berhasil disinkronkan:";

        $shown = 0;
        foreach ($rows as $entry) {
            $norm = $this->normalizeFailedSyncEntry($entry);
            $name = trim((string)$norm["customer_name"]);
            if ($name === "") {
                $name = "Customer tanpa nama";
            }
            $reason = $this->mapFailedSyncReasonToFriendly($norm["stage"], $norm["message"]);
            $shown++;
            $lines[] = $shown . ". " . $name . " - " . $reason;
            if ($shown >= 7) {
                break;
            }
        }
        if ($count > $shown) {
            $lines[] = "dan " . ($count - $shown) . " customer lainnya.";
        }
        if ($shown < 1) {
            $lines[] = "Silakan ulang sinkronisasi. Jika tetap gagal, hubungi admin.";
        }
        return implode("\n", $lines);
    }

    private function buildCustomerSyncFinalNotice($missingCount, $missingNames = array(), $failedCount = 0, $failedEntries = array())
    {
        $parts = array();

        $missingNotice = $this->buildMissingDcNotice($missingCount, $missingNames);
        if ($missingNotice !== "") {
            $parts[] = $missingNotice;
        }

        $failedNotice = $this->buildFailedSyncNotice($failedCount, $failedEntries);
        if ($failedNotice !== "") {
            $parts[] = $failedNotice;
        }

        if (count($parts) < 1) {
            return "Sinkronisasi selesai";
        }

        return "Sinkronisasi selesai dengan catatan:\n" . implode("\n\n", $parts);
    }

    private function resolveSyncStageDir()
    {
        $candidateDirs = array(
            APPPATH . "cache/sync_stage",
            (defined("FCPATH") ? FCPATH : "") . "uploads/sync_stage",
        );

        foreach ($candidateDirs as $candidateDir) {
            if ($candidateDir == "") {
                continue;
            }
            if (!is_dir($candidateDir)) {
                @mkdir($candidateDir, 0775, true);
            }
            if (is_dir($candidateDir) && is_writable($candidateDir)) {
                return rtrim($candidateDir, "/\\");
            }
        }

        $tmpDir = rtrim(sys_get_temp_dir(), "/\\");
        if ($tmpDir != "" && is_dir($tmpDir) && is_writable($tmpDir)) {
            $fallback = $tmpDir . DIRECTORY_SEPARATOR . "san_sarana_sync_stage";
            if (!is_dir($fallback)) {
                @mkdir($fallback, 0775, true);
            }
            if (is_dir($fallback) && is_writable($fallback)) {
                return rtrim($fallback, "/\\");
            }
        }

        return "";
    }

    private function writeSyncStageData($stageToken, $data)
    {
        $stageDir = $this->resolveSyncStageDir();
        if ($stageDir == "") {
            return false;
        }
        $path = $stageDir . DIRECTORY_SEPARATOR . $stageToken . ".json";
        $json = json_encode($data);
        if ($json === false) {
            return false;
        }
        return @file_put_contents($path, $json, LOCK_EX) !== false;
    }

    private function readSyncStageData($stageToken)
    {
        $stageDir = $this->resolveSyncStageDir();
        if ($stageDir == "") {
            return array();
        }
        $path = $stageDir . DIRECTORY_SEPARATOR . $stageToken . ".json";
        if (!file_exists($path)) {
            return array();
        }
        $raw = @file_get_contents($path);
        if ($raw === false || $raw == "") {
            return array();
        }
        $data = json_decode($raw, true);
        return is_array($data) ? $data : array();
    }

    private function deleteSyncStageData($stageToken)
    {
        $stageDir = $this->resolveSyncStageDir();
        if ($stageDir == "") {
            return;
        }
        $path = $stageDir . DIRECTORY_SEPARATOR . $stageToken . ".json";
        if (file_exists($path)) {
            @unlink($path);
        }
    }

    public function sync_job_start()
    {
        $this->syncLastStep = "sync_job_start_init";
        $mdl = $this->uri->segment(4);
        $mdl = $mdl != "" ? $mdl : (isset($_GET["mdl"]) ? $_GET["mdl"] : $this->segment_4);
        $className = "Mdl" . ucwords($mdl);
        if ($className != "MdlCustomer") {
            $this->outputSyncJson(array(
                "status"  => false,
                "message" => "Endpoint ini khusus sinkronisasi customer",
            ));
            return;
        }
        if (!file_exists(APPPATH . "models/Mdls/$className.php")) {
            $this->outputSyncJson(array(
                "status"  => false,
                "message" => "Model $className tidak ditemukan",
            ));
            return;
        }

        $this->load->model("Mdls/MdlSyncJob");
        $jobMdl = new MdlSyncJob();
        $jobMdl->ensureTable();
        $active = $jobMdl->getActiveJob($className, my_toko_id(), my_cabang_id());
        if ($active) {
            $activeIsStale = false;
            if (isset($active->updated_at) && $active->updated_at != "") {
                $activeTs = strtotime($active->updated_at);
                if ($activeTs !== false && $activeTs > 0) {
                    $activeIsStale = (time() - $activeTs) > 300;
                }
            }
            if ($activeIsStale) {
                $jobMdl->updateJob((int)$active->id, array(
                    "status"      => "failed",
                    "finished_at" => dtimeNow(),
                    "message"     => "Job aktif stale >5 menit, ditandai gagal otomatis",
                ));
                $active = null;
            }
        }
        if ($active) {
            $activePayload = array();
            if (isset($active->payload) && $active->payload != "") {
                $tmpActivePayload = json_decode($active->payload, true);
                if (is_array($tmpActivePayload)) {
                    $activePayload = $tmpActivePayload;
                }
            }
            $activeBatch = isset($_GET["batch"]) && is_numeric($_GET["batch"]) ? (int)$_GET["batch"] : 0;
            if ($activeBatch < 1) {
                $activeBatch = isset($activePayload["batch_size"]) && is_numeric($activePayload["batch_size"])
                    ? (int)$activePayload["batch_size"]
                    : 50;
            }
            if ($activeBatch < 1) {
                $activeBatch = 50;
            }
            if ($activeBatch > 200) {
                $activeBatch = 200;
            }
            $this->outputSyncJson(array(
                "status"                => true,
                "message"               => "Melanjutkan job sinkronisasi yang masih aktif",
                "job_id"                => (int)$active->id,
                "total_items"           => (int)$active->total_items,
                "processed_items"       => (int)$active->processed_items,
                "is_finished"           => false,
                "batch_size"            => $activeBatch,
                "active_job_status"     => isset($active->status) ? $active->status : "",
                "active_job_updated_at" => isset($active->updated_at) ? $active->updated_at : "",
                "sender_api_url"        => isset($activePayload["last_remote_url"]) ? $activePayload["last_remote_url"] : "",
                "active_job_payload"    => $this->isSyncDebugMode() ? $activePayload : array(),
            ));
            return;
        }

        $this->load->model("Mdls/" . $className);
        $o = new $className();
        if (!method_exists($o, "fetchApiCustomersBatch") || !method_exists($o, "processApiCustomerChunk")) {
            $this->outputSyncJson(array(
                "status"  => false,
                "message" => "Method batch sinkronisasi belum tersedia di $className",
            ));
            return;
        }

        $syncOptions = array(
            "id"    => isset($_GET["id"]) ? $_GET["id"] : "",
            "q"     => isset($_GET["q"]) ? $_GET["q"] : "",
            "dc_id" => isset($_GET["dc_id"]) ? $_GET["dc_id"] : "",
        );

        $batchSize = isset($_GET["batch"]) && is_numeric($_GET["batch"]) ? (int)$_GET["batch"] : 50;
        if ($batchSize < 1) {
            $batchSize = 50;
        }
        if ($batchSize > 200) {
            $batchSize = 200;
        }

        // pre-flight ringan: validasi endpoint batch customer tersedia
        $probe = $o->fetchApiCustomersBatch($syncOptions, 1, "", true);
        if (!isset($probe["status"]) || $probe["status"] !== true) {
            $this->outputSyncJson(array(
                "status"               => false,
                "message"              => isset($probe["message"]) ? $probe["message"] : "Gagal mengambil data customer batch dari API",
                "sender_api_url"       => isset($probe["debug_url"]) ? $probe["debug_url"] : "",
                "remote_api_url_probe" => isset($probe["debug_url"]) ? $probe["debug_url"] : "",
                "remote_api_domain"    => isset($probe["debug_domain"]) ? $probe["debug_domain"] : ADM_DOMAIN,
            ));
            return;
        }
        $probeRows = isset($probe["rows"]) && is_array($probe["rows"]) ? $probe["rows"] : array();
        if (count($probeRows) < 1) {
            $this->outputSyncJson(array(
                "status"  => false,
                "message" => "Data customer dari API kosong",
            ));
            return;
        }
        $totalItems = isset($probe["total_filtered"]) && is_numeric($probe["total_filtered"]) ? (int)$probe["total_filtered"] : 0;
        $jobPayload = array(
            "mode"            => "remote_batch_v2",
            "cursor"          => "",
            "batch_size"      => $batchSize,
            "source_label"    => isset($probe["sourceLabel"]) ? $probe["sourceLabel"] : "Customers/syncV2",
            "options"         => $syncOptions,
            "last_remote_url" => isset($probe["debug_url"]) ? $probe["debug_url"] : "",
        );

        $jobId = $jobMdl->createJob(array(
            "mdl_name"        => $className,
            "toko_id"         => my_toko_id(),
            "cabang_id"       => my_cabang_id(),
            "status"          => "pending",
            "payload"         => json_encode($jobPayload),
            "stage_token"     => "",
            "total_items"     => $totalItems,
            "processed_items" => 0,
            "inserted_items"  => 0,
            "updated_items"   => 0,
            "skipped_items"   => 0,
            "failed_items"    => 0,
            "message"         => "Job sinkronisasi batch dibuat",
            "created_by"      => isset($this->session->login["id"]) ? $this->session->login["id"] : 0,
        ));
        if ($jobId < 1) {
            $this->outputSyncJson(array(
                "status"  => false,
                "message" => "Gagal membuat job sinkronisasi",
            ));
            return;
        }

        $this->outputSyncJson(array(
            "status"               => true,
            "message"              => "Job sinkronisasi berhasil dibuat",
            "job_id"               => (int)$jobId,
            "total_items"          => $totalItems,
            "processed_items"      => 0,
            "is_finished"          => false,
            "batch_size"           => $batchSize,
            "sender_api_url"       => isset($probe["debug_url"]) ? $probe["debug_url"] : "",
            "remote_api_url_probe" => isset($probe["debug_url"]) ? $probe["debug_url"] : "",
            "remote_api_domain"    => isset($probe["debug_domain"]) ? $probe["debug_domain"] : ADM_DOMAIN,
        ));
    }

    public function sync_job_process($jobId = null)
    {
        $this->syncJsonResponseSent = false;
        $this->syncLastStep = "init";
        if (function_exists("ini_set")) {
            @ini_set("display_errors", "0");
        }
        $debugMode = $this->isSyncDebugMode();
        $syncTrace = array();
        $lockHandle = null;

        $jobId = $jobId != null ? $jobId : $this->uri->segment(4);
        $jobId = is_numeric($jobId) ? (int)$jobId : 0;
        $this->appendSyncDebugFile("sync_job_process_enter", array(
            "job_id" => $jobId,
            "get"    => $_GET,
            "uri"    => isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : "",
        ));
        $this->appendSyncTrace($syncTrace, $debugMode, "request_in", array(
            "job_id" => $jobId,
            "batch"  => isset($_GET["batch"]) ? $_GET["batch"] : "",
        ));

        if (isset($this->db) && is_object($this->db) && property_exists($this->db, "db_debug")) {
            $this->db->db_debug = false;
        }

        $self = $this;
        set_exception_handler(function ($e) use (&$lockHandle, &$syncTrace, $debugMode, $jobId, $self) {
            if ($self->syncJsonResponseSent === true) {
                return;
            }
            $self->releaseSyncLock($lockHandle);
            $lastStep = isset($self->syncLastStep) ? $self->syncLastStep : "";
            $msg = "Throwable batch sinkronisasi: " . (is_object($e) && method_exists($e, "getMessage") ? $e->getMessage() : "unknown");
            if ($lastStep != "") {
                $msg .= " [step:$lastStep]";
            }
            $file = is_object($e) && method_exists($e, "getFile") ? $e->getFile() : "";
            $line = is_object($e) && method_exists($e, "getLine") ? $e->getLine() : 0;
            if (function_exists("log_message")) {
                log_message("error", "sync_job_process throwable [job:$jobId] " . $msg . " @ " . $file . ":" . $line);
            }
            $self->appendSyncDebugFile("sync_job_process_throwable", array(
                "job_id"    => $jobId,
                "message"   => $msg,
                "file"      => $file,
                "line"      => $line,
                "last_step" => $lastStep,
            ));
            $self->markSyncJobFailed($jobId, $msg);
            $payload = array(
                "status"    => false,
                "message"   => $msg,
                "last_step" => $lastStep,
            );
            if ($debugMode) {
                $payload["file"] = $file;
                $payload["line"] = $line;
                $payload["trace"] = $syncTrace;
            }
            $self->outputSyncJson($payload, 500);
        });
        register_shutdown_function(function () use (&$lockHandle, &$syncTrace, $debugMode, $jobId, $self) {
            if ($self->syncJsonResponseSent === true) {
                return;
            }
            $fatal = error_get_last();
            if (!is_array($fatal)) {
                return;
            }

            $fatalTypes = array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR, E_RECOVERABLE_ERROR);
            if (!in_array($fatal["type"], $fatalTypes)) {
                return;
            }

            $self->releaseSyncLock($lockHandle);
            $msg = "Fatal error batch sinkronisasi";
            if (isset($fatal["message"]) && $fatal["message"] != "") {
                $msg .= ": " . $fatal["message"];
            }
            $lastStep = isset($self->syncLastStep) ? $self->syncLastStep : "";
            if ($lastStep != "") {
                $msg .= " [step:$lastStep]";
            }
            if (function_exists("log_message")) {
                log_message("error", "sync_job_process fatal [job:$jobId] " . $msg . " @ " . $fatal["file"] . ":" . $fatal["line"]);
            }
            $self->appendSyncDebugFile("sync_job_process_fatal", array(
                "job_id"    => $jobId,
                "message"   => $msg,
                "fatal"     => $fatal,
                "last_step" => $lastStep,
            ));
            $self->markSyncJobFailed($jobId, $msg);

            $payload = array(
                "status"    => false,
                "message"   => $msg,
                "last_step" => $lastStep,
            );
            if ($debugMode) {
                $payload["fatal"] = $fatal;
                $payload["trace"] = $syncTrace;
            }
            $self->outputSyncJson($payload, 500);
        });

        if ($jobId < 1) {
            $payload = array(
                "status"  => false,
                "message" => "Job ID tidak valid",
            );
            if ($debugMode) {
                $payload["trace"] = $syncTrace;
            }
            $this->outputSyncJson($payload);
            return;
        }

        try {
            $this->appendSyncTrace($syncTrace, $debugMode, "load_job_model");
            $this->load->model("Mdls/MdlSyncJob");
            $jobMdl = new MdlSyncJob();
            $jobMdl->ensureTable();

            $this->appendSyncTrace($syncTrace, $debugMode, "read_job");
            $job = $jobMdl->getById($jobId);
            if (!$job) {
                $payload = array(
                    "status"  => false,
                    "message" => "Job tidak ditemukan",
                );
                if ($debugMode) {
                    $payload["trace"] = $syncTrace;
                }
                $this->outputSyncJson($payload);
                return;
            }
            if ($job->status == "done") {
                $payload = array(
                    "status"      => true,
                    "message"     => $job->message,
                    "job_id"      => $jobId,
                    "processed"   => (int)$job->processed_items,
                    "total"       => (int)$job->total_items,
                    "remaining"   => 0,
                    "is_finished" => true,
                );
                if ($debugMode) {
                    $payload["trace"] = $syncTrace;
                }
                $this->outputSyncJson($payload);
                return;
            }
            if ($job->status == "failed") {
                $payload = array(
                    "status"  => false,
                    "message" => $job->message != "" ? $job->message : "Job gagal",
                );
                if ($debugMode) {
                    $payload["trace"] = $syncTrace;
                }
                $this->outputSyncJson($payload);
                return;
            }

            $this->appendSyncTrace($syncTrace, $debugMode, "acquire_lock");
            $lockState = $this->acquireSyncLock("sync_job_" . $jobId, $lockHandle);
            if (!isset($lockState["status"]) || $lockState["status"] !== true) {
                $payload = array(
                    "status"  => false,
                    "message" => isset($lockState["message"]) ? $lockState["message"] : "Job sedang diproses request lain",
                );
                if ($debugMode) {
                    $payload["trace"] = $syncTrace;
                }
                $this->outputSyncJson($payload);
                return;
            }

            $jobPayload = array();
            if (isset($job->payload) && $job->payload != "") {
                $tmpPayload = json_decode($job->payload, true);
                if (is_array($tmpPayload)) {
                    $jobPayload = $tmpPayload;
                }
            }
            $isRemoteBatch = isset($jobPayload["mode"]) && $jobPayload["mode"] === "remote_batch_v2";

            if ($isRemoteBatch) {
                $batch = isset($_GET["batch"]) && is_numeric($_GET["batch"]) ? (int)$_GET["batch"] : 0;
                if ($batch < 1) {
                    $batch = isset($jobPayload["batch_size"]) && is_numeric($jobPayload["batch_size"]) ? (int)$jobPayload["batch_size"] : 50;
                }
                if ($batch < 1) {
                    $batch = 50;
                }
                if ($batch > 200) {
                    $batch = 200;
                }

                $className = isset($job->mdl_name) ? $job->mdl_name : "MdlCustomer";
                if (!file_exists(APPPATH . "models/Mdls/$className.php")) {
                    $jobMdl->updateJob($jobId, array(
                        "status"  => "failed",
                        "message" => "Model $className tidak ditemukan",
                    ));
                    $this->releaseSyncLock($lockHandle);
                    $payload = array(
                        "status"  => false,
                        "message" => "Model $className tidak ditemukan",
                    );
                    if ($debugMode) {
                        $payload["trace"] = $syncTrace;
                    }
                    $this->outputSyncJson($payload);
                    return;
                }

                $this->appendSyncTrace($syncTrace, $debugMode, "load_remote_batch_model", array("model" => $className));
                $this->load->model("Mdls/" . $className);
                $o = new $className();
                if (!method_exists($o, "fetchApiCustomersBatch") || !method_exists($o, "processApiCustomerChunk")) {
                    $jobMdl->updateJob($jobId, array(
                        "status"  => "failed",
                        "message" => "Method fetchApiCustomersBatch/processApiCustomerChunk belum tersedia",
                    ));
                    $this->releaseSyncLock($lockHandle);
                    $payload = array(
                        "status"  => false,
                        "message" => "Method fetchApiCustomersBatch/processApiCustomerChunk belum tersedia",
                    );
                    if ($debugMode) {
                        $payload["trace"] = $syncTrace;
                    }
                    $this->outputSyncJson($payload);
                    return;
                }

                $syncOptions = isset($jobPayload["options"]) && is_array($jobPayload["options"]) ? $jobPayload["options"] : array();
                $cursor = isset($jobPayload["cursor"]) ? $jobPayload["cursor"] : "";
                $this->appendSyncTrace($syncTrace, $debugMode, "fetch_remote_batch", array(
                    "batch"  => $batch,
                    "cursor" => $cursor,
                ));
                $fetched = $o->fetchApiCustomersBatch($syncOptions, $batch, $cursor, true);
                if (!isset($fetched["status"]) || $fetched["status"] !== true) {
                    $jobMdl->updateJob($jobId, array(
                        "status"  => "failed",
                        "message" => isset($fetched["message"]) ? $fetched["message"] : "Gagal mengambil batch customer dari API",
                    ));
                    $this->releaseSyncLock($lockHandle);
                    $payload = array(
                        "status"            => false,
                        "message"           => isset($fetched["message"]) ? $fetched["message"] : "Gagal mengambil batch customer dari API",
                        "sender_api_url"    => isset($fetched["debug_url"]) ? $fetched["debug_url"] : "",
                        "remote_api_url"    => isset($fetched["debug_url"]) ? $fetched["debug_url"] : "",
                        "remote_api_domain" => isset($fetched["debug_domain"]) ? $fetched["debug_domain"] : ADM_DOMAIN,
                    );
                    if ($debugMode) {
                        $payload["trace"] = $syncTrace;
                    }
                    $this->outputSyncJson($payload);
                    return;
                }

                $chunk = isset($fetched["rows"]) && is_array($fetched["rows"]) ? $fetched["rows"] : array();
                $hasMore = isset($fetched["has_more"]) ? (bool)$fetched["has_more"] : false;
                $nextCursor = isset($fetched["next_cursor"]) ? $fetched["next_cursor"] : "";
                $sourceLabel = isset($fetched["sourceLabel"]) ? $fetched["sourceLabel"] : "Customers/syncV2";
                $remoteUrl = isset($fetched["debug_url"]) ? $fetched["debug_url"] : "";

                if (count($chunk) < 1) {
                    if ($hasMore) {
                        $jobMdl->updateJob($jobId, array(
                            "status"  => "failed",
                            "message" => "Batch kosong tetapi server menyatakan has_more=true",
                        ));
                        $this->releaseSyncLock($lockHandle);
                        $payload = array(
                            "status"  => false,
                            "message" => "Batch kosong tetapi server menyatakan has_more=true",
                        );
                        if ($debugMode) {
                            $payload["trace"] = $syncTrace;
                        }
                        $this->outputSyncJson($payload);
                        return;
                    }

                    $doneTotal = (int)$job->total_items;
                    $doneProcessed = (int)$job->processed_items;
                    if ($doneTotal < $doneProcessed) {
                        $doneTotal = $doneProcessed;
                    }
                    $jobPayload["cursor"] = $nextCursor;
                    $jobPayload["batch_size"] = $batch;
                    $jobPayload["source_label"] = $sourceLabel;
                    $doneMissingCount = isset($jobPayload["missing_dc_count"]) ? (int)$jobPayload["missing_dc_count"] : 0;
                    $doneMissingNames = isset($jobPayload["missing_dc_customers"]) ? $jobPayload["missing_dc_customers"] : array();
                    $doneFailedCount = (int)(isset($job->failed_items) ? $job->failed_items : 0);
                    $doneFailedEntries = isset($jobPayload["failed_entries"]) ? $jobPayload["failed_entries"] : array();
                    $doneMsg = $this->buildCustomerSyncFinalNotice(
                        $doneMissingCount,
                        $doneMissingNames,
                        $doneFailedCount,
                        $doneFailedEntries
                    );
                    $jobMdl->updateJob($jobId, array(
                        "status"          => "done",
                        "total_items"     => $doneTotal,
                        "processed_items" => $doneProcessed,
                        "finished_at"     => dtimeNow(),
                        "payload"         => json_encode($jobPayload),
                        "message"         => $doneMsg,
                    ));
                    $this->releaseSyncLock($lockHandle);
                    $payload = array(
                        "status"      => true,
                        "message"     => $doneMsg,
                        "job_id"      => $jobId,
                        "processed"   => $doneProcessed,
                        "total"       => $doneTotal,
                        "remaining"   => 0,
                        "is_finished" => true,
                    );
                    if ($debugMode) {
                        $payload["trace"] = $syncTrace;
                    }
                    $this->outputSyncJson($payload);
                    return;
                }

                $syncOptions["sourceLabel"] = $sourceLabel;
                $this->appendSyncTrace($syncTrace, $debugMode, "process_remote_chunk", array(
                    "chunk_size" => count($chunk),
                ));
                $processed = $o->processApiCustomerChunk($chunk, $syncOptions);
                if (!isset($processed["status"]) || $processed["status"] !== true) {
                    $jobMdl->updateJob($jobId, array(
                        "status"  => "failed",
                        "message" => isset($processed["message"]) ? $processed["message"] : "Gagal memproses batch sinkronisasi",
                    ));
                    $this->releaseSyncLock($lockHandle);
                    $payload = array(
                        "status"  => false,
                        "message" => isset($processed["message"]) ? $processed["message"] : "Gagal memproses batch sinkronisasi",
                    );
                    if ($debugMode) {
                        $payload["trace"] = $syncTrace;
                    }
                    $this->outputSyncJson($payload);
                    return;
                }

                $chunkProcessed = count($chunk);
                $newProcessed = (int)$job->processed_items + $chunkProcessed;
                $insertedItems = (int)$job->inserted_items + (int)$processed["inserted"];
                $updatedItems = (int)$job->updated_items + (int)$processed["updated"];
                $skippedItems = (int)$job->skipped_items + (int)$processed["skipped"];
                $failedItems = (int)$job->failed_items + (int)$processed["failed"];
                $missingDcCount = (int)(isset($jobPayload["missing_dc_count"]) ? $jobPayload["missing_dc_count"] : 0)
                    + (int)(isset($processed["missing_dc_count"]) ? $processed["missing_dc_count"] : 0);
                $missingDcNames = $this->mergeUniqueCustomerNames(
                    isset($jobPayload["missing_dc_customers"]) ? $jobPayload["missing_dc_customers"] : array(),
                    isset($processed["missing_dc_customers"]) ? $processed["missing_dc_customers"] : array()
                );
                $failedEntries = $this->mergeFailedSyncEntries(
                    isset($jobPayload["failed_entries"]) ? $jobPayload["failed_entries"] : array(),
                    isset($processed["errors"]) ? $processed["errors"] : array()
                );

                $totalItems = (int)$job->total_items;
                if (isset($fetched["total_filtered"]) && is_numeric($fetched["total_filtered"])) {
                    $tmpTotal = (int)$fetched["total_filtered"];
                    if ($tmpTotal > 0) {
                        $totalItems = $tmpTotal;
                    }
                }
                if ($totalItems < $newProcessed) {
                    $totalItems = $newProcessed;
                }

                $isFinished = !$hasMore;
                $newStatus = $isFinished ? "done" : "running";
                $msg = $totalItems > 0 ? "Progress $newProcessed/$totalItems" : "Progress $newProcessed";
                if ($isFinished) {
                    $msg = $this->buildCustomerSyncFinalNotice(
                        $missingDcCount,
                        $missingDcNames,
                        $failedItems,
                        $failedEntries
                    );
                }

                $jobPayload["cursor"] = $nextCursor;
                $jobPayload["batch_size"] = $batch;
                $jobPayload["source_label"] = $sourceLabel;
                $jobPayload["last_remote_url"] = $remoteUrl;
                $jobPayload["missing_dc_count"] = $missingDcCount;
                $jobPayload["missing_dc_customers"] = $missingDcNames;
                $jobPayload["failed_entries"] = $failedEntries;
                $jobMdl->updateJob($jobId, array(
                    "status"          => $newStatus,
                    "payload"         => json_encode($jobPayload),
                    "total_items"     => $totalItems,
                    "processed_items" => $newProcessed,
                    "inserted_items"  => $insertedItems,
                    "updated_items"   => $updatedItems,
                    "skipped_items"   => $skippedItems,
                    "failed_items"    => $failedItems,
                    "finished_at"     => $isFinished ? dtimeNow() : null,
                    "message"         => $msg,
                ));

                $this->releaseSyncLock($lockHandle);
                $remaining = $isFinished ? 0 : max(($totalItems - $newProcessed), 0);
                $remainingExact = true;
                if (!$isFinished && $hasMore && $remaining === 0) {
                    // total_items dari sender bisa tidak represent total final (mis. hanya total halaman saat ini)
                    // sehingga remaining numerik tidak akurat.
                    $remainingExact = false;
                }

                $payload = array(
                    "status"            => true,
                    "message"           => $msg,
                    "job_id"            => $jobId,
                    "processed"         => $newProcessed,
                    "total"             => $totalItems,
                    "remaining"         => $remaining,
                    "remaining_exact"   => $remainingExact,
                    "inserted"          => $insertedItems,
                    "updated"           => $updatedItems,
                    "skipped"           => $skippedItems,
                    "failed"            => $failedItems,
                    "is_finished"       => $isFinished,
                    "has_more"          => $hasMore,
                    "next_cursor"       => $nextCursor,
                    "sender_api_url"    => $remoteUrl,
                    "remote_api_url"    => $remoteUrl,
                    "remote_api_domain" => isset($fetched["debug_domain"]) ? $fetched["debug_domain"] : ADM_DOMAIN,
                );
                if (isset($processed["first_failed_reason"]) && $processed["first_failed_reason"] != "") {
                    $payload["first_failed_reason"] = $processed["first_failed_reason"];
                }
                if ($debugMode) {
                    $payload["trace"] = $syncTrace;
                }
                $this->outputSyncJson($payload);
                return;
            }

            $stageToken = isset($job->stage_token) ? $job->stage_token : "";
            $this->appendSyncTrace($syncTrace, $debugMode, "read_stage", array(
                "stage_token" => $stageToken,
            ));
            $stageData = $this->readSyncStageData($stageToken);
            $rows = isset($stageData["rows"]) && is_array($stageData["rows"]) ? $stageData["rows"] : array();
            $sourceLabel = isset($stageData["sourceLabel"]) ? $stageData["sourceLabel"] : "";
            $syncOptions = isset($stageData["options"]) && is_array($stageData["options"]) ? $stageData["options"] : array();
            if (count($rows) < 1) {
                $jobMdl->updateJob($jobId, array(
                    "status"  => "failed",
                    "message" => "Staging sinkronisasi tidak ditemukan atau kosong",
                ));
                $this->releaseSyncLock($lockHandle);
                $payload = array(
                    "status"  => false,
                    "message" => "Staging sinkronisasi tidak ditemukan atau kosong",
                );
                if ($debugMode) {
                    $payload["trace"] = $syncTrace;
                }
                $this->outputSyncJson($payload);
                return;
            }

            $totalItems = (int)$job->total_items;
            if ($totalItems < 1) {
                $totalItems = count($rows);
            }
            $offset = (int)$job->processed_items;
            $batch = isset($_GET["batch"]) && is_numeric($_GET["batch"]) ? (int)$_GET["batch"] : 50;
            if ($batch < 1) {
                $batch = 50;
            }
            if ($batch > 200) {
                $batch = 200;
            }

            $this->appendSyncTrace($syncTrace, $debugMode, "slice_chunk", array(
                "offset" => $offset,
                "batch"  => $batch,
                "total"  => $totalItems,
            ));
            $chunk = array_slice($rows, $offset, $batch);
            if (count($chunk) < 1) {
                $doneMissingCount = isset($jobPayload["missing_dc_count"]) ? (int)$jobPayload["missing_dc_count"] : 0;
                $doneMissingNames = isset($jobPayload["missing_dc_customers"]) ? $jobPayload["missing_dc_customers"] : array();
                $doneFailedCount = (int)(isset($job->failed_items) ? $job->failed_items : 0);
                $doneFailedEntries = isset($jobPayload["failed_entries"]) ? $jobPayload["failed_entries"] : array();
                $doneMsg = $this->buildCustomerSyncFinalNotice(
                    $doneMissingCount,
                    $doneMissingNames,
                    $doneFailedCount,
                    $doneFailedEntries
                );
                $jobMdl->updateJob($jobId, array(
                    "status"          => "done",
                    "total_items"     => $totalItems,
                    "processed_items" => $totalItems,
                    "finished_at"     => dtimeNow(),
                    "message"         => $doneMsg,
                ));
                $this->deleteSyncStageData($stageToken);
                $this->releaseSyncLock($lockHandle);
                $payload = array(
                    "status"      => true,
                    "message"     => $doneMsg,
                    "job_id"      => $jobId,
                    "processed"   => $totalItems,
                    "total"       => $totalItems,
                    "remaining"   => 0,
                    "is_finished" => true,
                );
                if ($debugMode) {
                    $payload["trace"] = $syncTrace;
                }
                $this->outputSyncJson($payload);
                return;
            }

            $className = isset($job->mdl_name) ? $job->mdl_name : "MdlCustomer";
            if (!file_exists(APPPATH . "models/Mdls/$className.php")) {
                $jobMdl->updateJob($jobId, array(
                    "status"  => "failed",
                    "message" => "Model $className tidak ditemukan",
                ));
                $this->releaseSyncLock($lockHandle);
                $payload = array(
                    "status"  => false,
                    "message" => "Model $className tidak ditemukan",
                );
                if ($debugMode) {
                    $payload["trace"] = $syncTrace;
                }
                $this->outputSyncJson($payload);
                return;
            }

            $this->appendSyncTrace($syncTrace, $debugMode, "load_sync_model", array("model" => $className));
            $this->load->model("Mdls/" . $className);
            $o = new $className();
            if (!method_exists($o, "processApiCustomerChunk")) {
                $jobMdl->updateJob($jobId, array(
                    "status"  => "failed",
                    "message" => "Method processApiCustomerChunk belum tersedia",
                ));
                $this->releaseSyncLock($lockHandle);
                $payload = array(
                    "status"  => false,
                    "message" => "Method processApiCustomerChunk belum tersedia",
                );
                if ($debugMode) {
                    $payload["trace"] = $syncTrace;
                }
                $this->outputSyncJson($payload);
                return;
            }

            $syncOptions["sourceLabel"] = $sourceLabel;
            $this->appendSyncTrace($syncTrace, $debugMode, "process_chunk", array(
                "chunk_size" => count($chunk),
            ));
            $processed = $o->processApiCustomerChunk($chunk, $syncOptions);
            if (!isset($processed["status"]) || $processed["status"] !== true) {
                $jobMdl->updateJob($jobId, array(
                    "status"  => "failed",
                    "message" => isset($processed["message"]) ? $processed["message"] : "Gagal memproses batch sinkronisasi",
                ));
                $this->releaseSyncLock($lockHandle);
                $payload = array(
                    "status"  => false,
                    "message" => isset($processed["message"]) ? $processed["message"] : "Gagal memproses batch sinkronisasi",
                );
                if ($debugMode) {
                    $payload["trace"] = $syncTrace;
                }
                $this->outputSyncJson($payload);
                return;
            }
            $chunkProcessed = count($chunk);
            $newProcessed = $offset + $chunkProcessed;
            $insertedItems = (int)$job->inserted_items + (int)$processed["inserted"];
            $updatedItems = (int)$job->updated_items + (int)$processed["updated"];
            $skippedItems = (int)$job->skipped_items + (int)$processed["skipped"];
            $failedItems = (int)$job->failed_items + (int)$processed["failed"];
            $missingDcCount = (int)(isset($jobPayload["missing_dc_count"]) ? $jobPayload["missing_dc_count"] : 0)
                + (int)(isset($processed["missing_dc_count"]) ? $processed["missing_dc_count"] : 0);
            $missingDcNames = $this->mergeUniqueCustomerNames(
                isset($jobPayload["missing_dc_customers"]) ? $jobPayload["missing_dc_customers"] : array(),
                isset($processed["missing_dc_customers"]) ? $processed["missing_dc_customers"] : array()
            );
            $failedEntries = $this->mergeFailedSyncEntries(
                isset($jobPayload["failed_entries"]) ? $jobPayload["failed_entries"] : array(),
                isset($processed["errors"]) ? $processed["errors"] : array()
            );
            $isFinished = $newProcessed >= $totalItems;
            $newStatus = $isFinished ? "done" : "running";
            $msg = "Progress $newProcessed/$totalItems";
            if ($isFinished) {
                $msg = $this->buildCustomerSyncFinalNotice(
                    $missingDcCount,
                    $missingDcNames,
                    $failedItems,
                    $failedEntries
                );
            }

            $this->appendSyncTrace($syncTrace, $debugMode, "update_job", array(
                "status"    => $newStatus,
                "processed" => $newProcessed,
                "total"     => $totalItems,
            ));
            $jobPayload["missing_dc_count"] = $missingDcCount;
            $jobPayload["missing_dc_customers"] = $missingDcNames;
            $jobPayload["failed_entries"] = $failedEntries;
            $jobMdl->updateJob($jobId, array(
                "status"          => $newStatus,
                "payload"         => json_encode($jobPayload),
                "total_items"     => $totalItems,
                "processed_items" => $newProcessed,
                "inserted_items"  => $insertedItems,
                "updated_items"   => $updatedItems,
                "skipped_items"   => $skippedItems,
                "failed_items"    => $failedItems,
                "finished_at"     => $isFinished ? dtimeNow() : null,
                "message"         => $msg,
            ));
            if ($isFinished) {
                $this->deleteSyncStageData($stageToken);
            }

            $this->releaseSyncLock($lockHandle);
            $payload = array(
                "status"      => true,
                "message"     => $msg,
                "job_id"      => $jobId,
                "processed"   => $newProcessed,
                "total"       => $totalItems,
                "remaining"   => $isFinished ? 0 : max(($totalItems - $newProcessed), 0),
                "inserted"    => $insertedItems,
                "updated"     => $updatedItems,
                "skipped"     => $skippedItems,
                "failed"      => $failedItems,
                "is_finished" => $isFinished,
            );
            if ($debugMode) {
                $payload["trace"] = $syncTrace;
            }
            $this->outputSyncJson($payload);
        } catch (Exception $e) {
            $this->releaseSyncLock($lockHandle);
            $lastStep = isset($this->syncLastStep) ? $this->syncLastStep : "";
            $msg = "Exception batch sinkronisasi: " . $e->getMessage();
            if ($lastStep != "") {
                $msg .= " [step:$lastStep]";
            }
            if (function_exists("log_message")) {
                log_message("error", "sync_job_process exception [job:$jobId] " . $msg . " @ " . $e->getFile() . ":" . $e->getLine());
            }
            $this->appendSyncDebugFile("sync_job_process_exception", array(
                "job_id"    => $jobId,
                "message"   => $msg,
                "file"      => $e->getFile(),
                "line"      => $e->getLine(),
                "last_step" => $lastStep,
            ));
            $this->markSyncJobFailed($jobId, $msg);
            $payload = array(
                "status"    => false,
                "message"   => $msg,
                "last_step" => $lastStep,
            );
            if ($debugMode) {
                $payload["line"] = $e->getLine();
                $payload["file"] = $e->getFile();
                $payload["trace"] = $syncTrace;
            }
            $this->outputSyncJson($payload, 500);
            return;
        }
    }

    public function sync_job_status($jobId = null)
    {
        $jobId = $jobId != null ? $jobId : $this->uri->segment(4);
        $jobId = is_numeric($jobId) ? (int)$jobId : 0;
        if ($jobId < 1) {
            $this->outputSyncJson(array(
                "status"  => false,
                "message" => "Job ID tidak valid",
            ));
            return;
        }

        $this->load->model("Mdls/MdlSyncJob");
        $jobMdl = new MdlSyncJob();
        $jobMdl->ensureTable();
        $job = $jobMdl->getById($jobId);
        if (!$job) {
            $this->outputSyncJson(array(
                "status"  => false,
                "message" => "Job tidak ditemukan",
            ));
            return;
        }

        $isFinished = $job->status == "done";
        $jobPayload = array();
        if (isset($job->payload) && $job->payload != "") {
            $tmpPayload = json_decode($job->payload, true);
            if (is_array($tmpPayload)) {
                $jobPayload = $tmpPayload;
            }
        }
        $this->outputSyncJson(array(
            "status"          => true,
            "job_id"          => (int)$job->id,
            "mdl_name"        => $job->mdl_name,
            "job_status"      => $job->status,
            "processed"       => (int)$job->processed_items,
            "total"           => (int)$job->total_items,
            "remaining"       => $isFinished ? 0 : max(((int)$job->total_items - (int)$job->processed_items), 0),
            "inserted"        => (int)$job->inserted_items,
            "updated"         => (int)$job->updated_items,
            "skipped"         => (int)$job->skipped_items,
            "failed"          => (int)$job->failed_items,
            "message"         => $job->message,
            "is_finished"     => $isFinished,
            "last_remote_url" => isset($jobPayload["last_remote_url"]) ? $jobPayload["last_remote_url"] : "",
        ));
    }

    public function syncro_data()
    {
        $mdl = $this->uri->segment(4);
        $mdl = $mdl != "" ? $mdl : $this->segment_4;
        $className = "Mdl" . ucwords($mdl);

        // Customer wajib lewat job sync agar tidak ada dualisme alur sinkronisasi.
        if ($className == "MdlCustomer") {
            cekAlert("Sinkronisasi customer menggunakan jalur job (Sync Now) pada halaman Customer.");
            return;
        }

        if (!file_exists(APPPATH . "models/Mdls/$className.php")) {
            cekAlert("Gagal sinkron: model $className tidak ditemukan");
            return;
        }

        $this->load->model("Mdls/" . $className);
        $o = new $className();

        if (!method_exists($o, "syncApiData")) {
            cekAlert("Gagal sinkron: method syncApiData belum tersedia di $className");
            return;
        }

        $syncOptions = array(
            "id"        => isset($_GET["id"]) ? $_GET["id"] : "",
            "limit"     => $className == "MdlCustomer" ? "" : (isset($_GET["limit"]) ? $_GET["limit"] : ""),
            "q"         => isset($_GET["q"]) ? $_GET["q"] : "",
            "dc_id"     => isset($_GET["dc_id"]) ? $_GET["dc_id"] : "",
            "price_key" => isset($_GET["price_key"]) ? $_GET["price_key"] : "",
        );

        $lockKey = strtolower($className . "_" . my_toko_id() . "_" . my_cabang_id());
        $lockHandle = null;
        $lockState = $this->acquireSyncLock($lockKey, $lockHandle);
        if (!isset($lockState["status"]) || $lockState["status"] !== true) {
            $msg = isset($lockState["message"]) ? $lockState["message"] : "Sinkronisasi sedang diproses";
            cekAlert($msg);
            return;
        }

        try {
            $syncResult = $o->syncApiData(my_cabang_id(), $syncOptions);
        } catch (Exception $e) {
            $this->releaseSyncLock($lockHandle);
            cekAlert("Gagal sinkron: " . $e->getMessage());
            return;
        }
        $this->releaseSyncLock($lockHandle);
        $status = isset($syncResult["status"]) ? $syncResult["status"] : false;
        $inserted = isset($syncResult["inserted"]) ? $syncResult["inserted"] : 0;
        $updated = isset($syncResult["updated"]) ? $syncResult["updated"] : 0;
        $skipped = isset($syncResult["skipped"]) ? $syncResult["skipped"] : 0;
        $failed = isset($syncResult["failed"]) ? $syncResult["failed"] : 0;
        $message = isset($syncResult["message"]) ? $syncResult["message"] : "";

        $summary = "<b>Sync $mdl</b><br>"
            . "inserted: $inserted<br>"
            . "updated: $updated<br>"
            . "skipped: $skipped<br>"
            . "failed: $failed";

        if ($message != "") {
            $summary .= "<br>$message";
        }

        if ($status) {
            cekAlert($summary);
        }
        else {
            cekAlert("Gagal sinkron.<br>$summary");
        }

    }

    // Endpoint: /statik/Data/access_rights/{employee_id}
    public function access_rights($employee_id = null)
    {
        if (!$employee_id) {
            echo json_encode(["error" => "No employee_id provided"]);
            return;
        }

        $this->load->model("Mdls/MdlMenu");
        $this->load->model("Mdls/MdlMenuBebas");
        $this->load->model("Mdls/MdlMenuData");

        // Ambil hak akses dari masing-masing model
        $menu = $this->MdlMenu->get_where(["employee_id" => $employee_id])->result_array();
        $menu_bebas = $this->MdlMenuBebas->get_where(["per_employee_id" => $employee_id])->result_array();
        $menu_data = $this->MdlMenuData->get_where(["employee_id" => $employee_id])->result_array();

        $result = [
            "menu"       => $menu,
            "menu_bebas" => $menu_bebas,
            "menu_data"  => $menu_data
        ];

        header('Content-Type: application/json');
        echo json_encode($result);
    }
}
