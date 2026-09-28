<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once "Modul_Controller.php";
class ProduksiStatus extends Modul_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Mdls/" . "MdlLockerStockSupplies");
        $this->load->model("Mdls/" . "MdlLockerStockJadiPh1");
        $this->load->model("Mdls/" . "MdlLockerStockJadiPh2");
        $this->load->model("Mdls/" . "MdlLockerStockJadiPh3");
        $this->load->model("Mdls/" . "MdlLockerStockJadiPh4");
        /* ----------------------------------------------------------------------------------
 * validasi session bila tidak ada dipaksa ke halaman login
 * ----------------------------------------------------------------------------------*/

    }
    public function index()
    {
        $tr_id = isset($_GET['id']) ? $_GET['id'] : "";
        $trJenis = $this->uri->segment(4);
        $cCode = "_TR_" . $trJenis;

        // $this->load->model("MdlTransaksi");
        // $tr = new MdlTransaksi();
        // $tr->addFilter("jenis='776wip'");
        // $tr->addFilter("link_id=0");
        // $tr->addFilter("transaksi_data.produk_jenis='produk'");
        // $tr->addFilter("transaksi_data.valid_qty>0");
        // $tmpTr = $tr->lookupJoined()->result();
        // $arrMainTr = array();
        // if(!empty($tmpTr)){
        //     foreach($tmpTr as $k => $dt ){
        //         $arrMainTr[$dt->id] = $dt;
        //     }
        // }
        // $trr = new MdlTransaksi();
        // $trr->setFilters(array());
        // $trr->addFilter("trash='0'");
        // $tmpReg = $tr->lookupDataRegistriesByMasterID()->row();
        // cekMerah( $this->db->last_query() );
        // arrPrint($tmpReg);
        // die("MATI DULU");

        $arrItems = array();
        if(isset($_SESSION[$cCode]['items'])){
            foreach( $_SESSION[$cCode]['items'] as $pid => $mainItemData ){
                $arrItems[$pid] = $mainItemData;
            }
        }

        $arrItems2 = array();
        if(isset($_SESSION[$cCode]['items2'])){
            foreach( $_SESSION[$cCode]['items2'] as $pid => $items2Data ){
                $arrItems2[$pid] = $items2Data;
            }
        }

        $arrGudangProduksi = array(
//            "-1" => "pusat",
            "7000" => "WH WIP",
            "7001" => "WIP Phase I <br><r>ADONAN</r><br><div class='wipph1'></div>",
            "7002" => "WIP Phase II <br><r>POTONG</r><br><div class='wipph2'></div>",
            "7003" => "WIP Phase III <br><r>BUNGKUS</r><br><div class='wipph3'></div>",
            "7004" => "WIP Phase IV <br><r>PACKING</r><br><div class='wipph4'></div>",
        );

        $selected_transaksi_id = isset($_SESSION[$cCode]['main']['transaksi_id']) ? $_SESSION[$cCode]['main']['transaksi_id'] : 0;

        $o = new MdlLockerStockSupplies();
        $o->addFilter("transaksi_id='$selected_transaksi_id'");
        $tmpLocker = $o->lookupAll()->result();

        $oph1 = new MdlLockerStockJadiPh1();
        $oph1->addFilter("transaksi_id='$selected_transaksi_id'");
        $tmpLockerJadiPh1 = $oph1->lookupAll()->result();

        $oph2 = new MdlLockerStockJadiPh2();
        $oph2->addFilter("transaksi_id='$selected_transaksi_id'");
        $tmpLockerJadiPh2 = $oph2->lookupAll()->result();

        $oph3 = new MdlLockerStockJadiPh3();
        $oph3->addFilter("transaksi_id='$selected_transaksi_id'");
        $tmpLockerJadiPh3 = $oph3->lookupAll()->result();

        $oph4 = new MdlLockerStockJadiPh4();
        $oph4->addFilter("transaksi_id='$selected_transaksi_id'");
        $tmpLockerJadiPh4 = $oph4->lookupAll()->result();

        $arrProgress=array();
        if(!empty($tmpLocker)){
            foreach($tmpLocker as $datas){
                $arrProgress[$datas->gudang_id][$datas->state][$datas->produk_id] = $datas;
            }
        }

        $arrProdukJadiPh1=array();
        if(!empty($tmpLockerJadiPh1)){
            foreach($tmpLockerJadiPh1 as $k => $datas){
                $arrProdukJadiPh1[$datas->gudang_id][$datas->state][] = $datas;
            }
        }

        $arrProdukJadiPh2=array();
        if(!empty($tmpLockerJadiPh2)){
            foreach($tmpLockerJadiPh2 as $k => $datas){
                $arrProdukJadiPh2[$datas->gudang_id][$datas->state][] = $datas;
            }
        }

        $arrProdukJadiPh3=array();
        if(!empty($tmpLockerJadiPh3)){
            foreach($tmpLockerJadiPh3 as $k => $datas){
                $arrProdukJadiPh3[$datas->gudang_id][$datas->state][] = $datas;
            }
        }

        $arrProdukJadiPh4=array();
        if(!empty($tmpLockerJadiPh4)){
            foreach($tmpLockerJadiPh4 as $k => $datas){
                $arrProdukJadiPh4[$datas->gudang_id][$datas->state][] = $datas;
            }
        }

        $addScript="";

        $theader ="";
        $theader .="<thead>";
        $theader .="<tr>";

        $theader .= "<th>";
        $theader .= "Work In Process";
        $theader .= "</th>";

        $theader .= "<th>";
        $theader .= "Bahan-bahan";
        $theader .= "</th>";

        foreach($arrGudangProduksi as $gudangID => $gudangLabel){
            $theader .= "<th class='text-center'>";
            $theader .= "$gudangLabel";
            $theader .= "</th>";
        }
        $theader .="</tr>";
        $theader .="</thead>";

        $tbody = "";
        $tbody .= "<tbody>";

        if(!empty($arrItems)){

            foreach($arrItems as $pid => $itemsData){
                $tbody .= "<tr>";
                $tbody .= "<td>";
                $tbody .= "Create " . $itemsData['jml'] . " " . $itemsData['satuan'] . "<br>";
                $tbody .= $itemsData['name'] . "<br>";
                $tbody .= "</td>";

                $tbody .= "<td>";

                $total_bahan_bahan=0;
                if(isset($arrItems2[$pid]['produk'])){
                    $arrBahan2 = isset($arrItems2[$pid]['produk']) ? $arrItems2[$pid]['produk'] : array();
                    $tbody .= "<table style='border: 0;width:100%;'>";
                    foreach($arrBahan2 as $k => $bahan2 ){
                        $tbody .= "<tr style='border: 0'>";
                        $tbody .= "<td style='border: 0;text-align: left;'>". $bahan2['nama'] ."</td>";
                        $tbody .= "<td style='border: 0;text-align: right;'>". number_format($bahan2['jml'], 0) ."</td>";
                        $tbody .= "<td style='border: 0;text-align: left;'>". $bahan2['satuan'] ."</td>";
                        $tbody .= "</tr>";
                        $total_bahan_bahan+= $bahan2['jml'];
                    }
                    $tbody .= "</table>";
                }
                $tbody .= "</td>";


                //region =======================   GUDANG WIP   =====================
                $tbody .= "<td>";
                if(isset($arrItems2[$pid]['produk'])){
                    $arrBahan2 = isset($arrItems2[$pid]['produk']) ? $arrItems2[$pid]['produk'] : array();
                    $tbody .= "<table style='border: 0;width:100%;'>";
                    if(isset($arrProgress['7000']['active'])){
                        $tbody .= "<tr style='border: 0;'>";
                        $tbody .= "<td colspan='3' class='bg-warning text-bold' style='border: 0;text-align: left;'><i class='fa text-red fa-warning blink'></i> Stock Gudang WIP </td>";
                        $tbody .= "</tr>";
                        foreach($arrBahan2 as $k => $bahan2 ){
                            $jml = isset($arrProgress['7000']['active'][$bahan2['id']]) ? $arrProgress['7000']['active'][$bahan2['id']]->jumlah : 0;
                            $satuan = isset($arrProgress['7000']['active'][$bahan2['id']]) ? $arrProgress['7000']['active'][$bahan2['id']]->satuan : "";
                            $tbody .= "<tr style='border:0;'>";
                            $tbody .= "<td style='border:0;text-align:left;'>". $bahan2['nama'] ."</td>";
                            $tbody .= "<td style='border:0;text-align:right;'>". number_format($jml, 0) ."</td>";
                            $tbody .= "<td style='border:0;text-align:left;'>". $satuan ."</td>";
                            $tbody .= "</tr>";
                        }
                    }
                    if(isset($arrProgress['7000']['hold'])){
                        $tbody .= "<tr style='border: 0;'>";
                        $tbody .= "<td colspan='3' class='bg-success text-bold' style='border: 0;text-align: left;'><i class='fa fa-send'></i> Prepared For Phase I</td>";
                        $tbody .= "</tr>";
                        foreach($arrBahan2 as $k => $bahan2 ){
                            $jml = isset($arrProgress['7000']['hold'][$bahan2['id']]) ? $arrProgress['7000']['hold'][$bahan2['id']]->jumlah : 0;
                            $satuan = isset($arrProgress['7000']['hold'][$bahan2['id']]) ? $arrProgress['7000']['hold'][$bahan2['id']]->satuan : "";
                            $tbody .= "<tr style='border: 0'>";
                            $tbody .= "<td style='border: 0;text-align: left;'>". $bahan2['nama'] ."</td>";
                            $tbody .= "<td style='border: 0;text-align: right;'>". number_format($jml, 0) ."</td>";
                            $tbody .= "<td style='border: 0;text-align: left;'>". $satuan ."</td>";
                            $tbody .= "</tr>";
                        }
                    }
                    $tbody .= "</table>";
                }
                $tbody .= "</td>";
                //endregion =======================   GUDANG WIP   =====================


                //region =======================   PHASE I   =====================
                $tbody .= "<td>";
                if(isset($arrItems2[$pid]['produk'])){
                    $arrBahan2 = isset($arrItems2[$pid]['produk']) ? $arrItems2[$pid]['produk'] : array();
                    $tbody .= "<table style='border: 0;width:100%;'>";
                    $progressPh1=0;
                    if(isset($arrProgress['7001']['active'])){
                        $tbody .= "<tr style='border: 0;'>";
                        $tbody .= "<td colspan='3' class='bg-warning text-bold' style='border: 0;text-align: left;'><i class='processing_ph1 fa fa-gear'></i> processing </td>";
                        $tbody .= "</tr>";
                        foreach($arrBahan2 as $k => $bahan2 ){
                            $jml = isset($arrProgress['7001']['active'][$bahan2['id']]) ? $arrProgress['7001']['active'][$bahan2['id']]->jumlah : 0;
                            $satuan = isset($arrProgress['7001']['active'][$bahan2['id']]) ? $arrProgress['7001']['active'][$bahan2['id']]->satuan : "";
                            $tbody .= "<tr style='border: 0'>";
                            $tbody .= "<td style='border: 0;text-align: left;'>". $bahan2['nama'] ."</td>";
                            $tbody .= "<td style='border: 0;text-align: right;'>". number_format($jml, 0) ."</td>";
                            $tbody .= "<td colspan='' style='border: 0;text-align: left;'>". $satuan ."</td>";
                            $tbody .= "</tr>";
                            $progressPh1 += $jml*1;
                        }
                    }
                    if($progressPh1>0){
                        $addScript .= "$('.wipph1').removeClass('bg-danger').addClass('bg-success').html(\"<span class='blink'>WORKING</span>\");\n";
                        $addScript .= "$('.processing_ph1').addClass('fa-spin');\n";
                    }
                    else{
                        $addScript .= "$('.wipph1').removeClass('bg-success').addClass('bg-danger').html(\"<span class=''>IDLE</span>\");\n";
                        $addScript .= "$('.processing_ph1').removeClass('fa-spin');\n";
                    }

                    $total_jadi_ph1 = 0;
                    if(isset($arrProdukJadiPh1['7001']['done'])){
//                        $tbody .= "<tr style='border: 0;'>";
//                        $tbody .= "<td colspan='3' class='bg-danger text-bold' style='border: 0;text-align: left;'><i class='fa fa-gift'></i> Produk WIP PHASE 1</td>";
//                        $tbody .= "</tr>";
                        foreach($arrProdukJadiPh1['7001']['done'] as $k => $bahan2 ){
//                            $tbody .= "<tr style='border: 0'>";
//                            $tbody .= "<td style='border: 0;text-align: right;'>". $bahan2->nama ."</td>";
//                            $tbody .= "<td style='border: 0;text-align: right;'>". number_format($bahan2->jumlah, 0) ."</td>";
//                            $tbody .= "<td style='border: 0;text-align: left;'>". $bahan2->satuan ."</td>";
//                            $tbody .= "</tr>";
                            $total_jadi_ph1 += $bahan2->jumlah;
                        }
                    }

                    if(isset($arrProgress['7001']['done'])){
                        $tbody .= "<tr style='border: 0;'>";
                        $tbody .= "<td colspan='3' class='bg-success text-bold' style='border: 0;text-align: left;'><i class='fa fa-check text-green'></i> selesai<span class='badge persentase_selesai_phase_1 pull-right text-bold'></span></td>";
                        $tbody .= "</tr>";
                        $total_selesai_phase_1 = 0;
                        foreach($arrBahan2 as $k => $bahan2 ){
                            $jml = isset($arrProgress['7001']['done'][$bahan2['id']]) ? $arrProgress['7001']['done'][$bahan2['id']]->jumlah : 0;
                            $satuan = isset($arrProgress['7001']['done'][$bahan2['id']]) ? $arrProgress['7001']['done'][$bahan2['id']]->satuan : "";
                            $tbody .= "<tr style='border: 0'>";
                            $tbody .= "<td style='border: 0;text-align: left;'>". $bahan2['nama'] ."</td>";
                            $tbody .= "<td style='border: 0;text-align: right;'>". number_format($jml, 0) ."</td>";
                            $tbody .= "<td colspan='' style='border: 0;text-align: left;'>". $satuan ."</td>";
                            $tbody .= "</tr>";
                            $total_selesai_phase_1 += $jml*1;
                        }
                        if($total_selesai_phase_1>0){
                            $persentase_selesai_phase_1 = round(($total_selesai_phase_1/$total_bahan_bahan)*100);
                            $badge_selesai_ph1 = $persentase_selesai_phase_1==0 ? "bg-red" : ($persentase_selesai_phase_1>50&&$persentase_selesai_phase_1<100?"bg-yellow" : ($persentase_selesai_phase_1==100?"bg-green":"bg-gray"));
                            $addScript .= "$('.persentase_selesai_phase_1').html('$persentase_selesai_phase_1%').addClass('$badge_selesai_ph1');\n";
                        }
                        else{
                            $addScript .= "$('.persentase_selesai_phase_1').html('0%');\n";
                        }
                    }

                    if(isset($arrProdukJadiPh1['7001']['active'])){
                        $tbody .= "<tr style='border: 0;'>";
                        $tbody .= "<td colspan='3' class='bg-danger text-bold' style='border: 0;text-align: left;'><i class='fa fa-gift'></i> Produk WIP PHASE 1</td>";
                        $tbody .= "</tr>";
                        foreach($arrProdukJadiPh1['7001']['active'] as $k => $bahan2 ){
                            $tbody .= "<tr style='border: 0'>";
                            $tbody .= "<td style='border: 0;text-align: right;'>". $bahan2->nama ."</td>";
                            $tbody .= "<td style='border: 0;text-align: right;'>". number_format($bahan2->jumlah, 0) ."</td>";
                            $tbody .= "<td style='border: 0;text-align: left;'>". $bahan2->satuan ."</td>";
                            $tbody .= "</tr>";
                        }
                    }

                    if(isset($arrProdukJadiPh1['7001']['hold'])){
                        $tbody .= "<tr style='border: 0;'>";
                        $tbody .= "<td colspan='3' class='bg-danger text-bold' style='border: 0;text-align: left;'><i class='fa fa-send'></i> Prepare For Phase 2 <div style='display: none;' class='waiting_ph2 text-red text-center blink'>waiting approval produksi<br>on phase 2</div></td>";
                        $tbody .= "</tr>";
                        $ph2_waiting_approve = 0;
                        foreach($arrProdukJadiPh1['7001']['hold'] as $k => $bahan2 ){
                            $tbody .= "<tr style='border: 0'>";
                            $tbody .= "<td style='border: 0;text-align: right;'>". $bahan2->nama ."</td>";
                            $tbody .= "<td style='border: 0;text-align: right;'>". number_format($bahan2->jumlah, 0) ."</td>";
                            $tbody .= "<td style='border: 0;text-align: left;'>". $bahan2->satuan ."</td>";
                            $tbody .= "</tr>";
                            $ph2_waiting_approve += $bahan2->jumlah;
                        }

                        if($ph2_waiting_approve*1>0){
                            $addScript .= "$('.waiting_ph2').show();\n";
                        }
                    }

                    $tbody .= "</table>";
                }
                $tbody .= "</td>";
                //endregion =======================   PHASE I   =====================

                //region =======================   PHASE II   =====================
                $tbody .= "<td>";
                if(isset($arrItems2[$pid]['produk'])){

                    $tbody .= "<table style='border: 0;width:100%;'>";
                    $progressPh2=0;

                    if(isset($arrProdukJadiPh1['7002']['active'])){
                        $tbody .= "<tr style='border: 0;'>";
                        $tbody .= "<td colspan='3' class='bg-warning text-bold' style='border: 0;text-align: left;'><i class='processing_ph2 fa fa-gear'></i> processing </td>";
                        $tbody .= "</tr>";
                        foreach($arrProdukJadiPh1['7002']['active'] as $k => $bahan2 ){
                            $tbody .= "<tr style='border: 0'>";
                            $tbody .= "<td style='border: 0;text-align: left;'>". $bahan2->nama ."</td>";
                            $tbody .= "<td style='border: 0;text-align: right;'>". number_format($bahan2->jumlah, 0) ."</td>";
                            $tbody .= "<td style='border: 0;text-align: left;'>". $bahan2->satuan ."</td>";
                            $tbody .= "</tr>";
                            $progressPh2 += $bahan2->jumlah*1;
                        }
                    }

                    if($progressPh2>0){
                        $addScript .= "$('.wipph2').removeClass('bg-danger').addClass('bg-success').html(\"<span class='blink'>WORKING</span>\");\n";
                        $addScript .= "$('.processing_ph2').addClass('fa-spin');\n";
                    }
                    else{
                        $addScript .= "$('.wipph2').removeClass('bg-success').addClass('bg-danger').html(\"<span class=''>IDLE</span>\");\n";
                        $addScript .= "$('.processing_ph2').removeClass('fa-spin');\n";
                    }

                    if(isset($arrProdukJadiPh1['7002']['done'])){
                        $tbody .= "<tr style='border: 0;'>";
                        $tbody .= "<td colspan='3' class='bg-success text-bold' style='border: 0;text-align: left;'><i class='fa fa-check text-green'></i> selesai</td>";
                        $tbody .= "</tr>";
                        foreach($arrProdukJadiPh1['7002']['done'] as $k => $bahan2 ){
                            $tbody .= "<td style='border: 0;text-align: left;'>". $bahan2->nama ."</td>";
                            $tbody .= "<td style='border: 0;text-align: right;'>". number_format($bahan2->jumlah, 0) ."</td>";
                            $tbody .= "<td style='border: 0;text-align: left;'>". $bahan2->satuan ."</td>";
                        }
                    }

                    if(isset($arrProdukJadiPh2['7002']['active'])){
                        $tbody .= "<tr style='border: 0;'>";
                        $tbody .= "<td colspan='3' class='bg-danger text-bold' style='border: 0;text-align: left;'><i class='fa fa-gift'></i> Produk WIP PHASE 2</td>";
                        $tbody .= "</tr>";
                        foreach($arrProdukJadiPh2['7002']['active'] as $k => $bahan2 ){
                            $tbody .= "<td style='border: 0;text-align: left;'>". $bahan2->nama ."</td>";
                            $tbody .= "<td style='border: 0;text-align: right;'>". number_format($bahan2->jumlah, 0) ."</td>";
                            $tbody .= "<td style='border: 0;text-align: left;'>". $bahan2->satuan ."</td>";
                        }
                    }

                    if(isset($arrProdukJadiPh2['7002']['hold'])){
                        $tbody .= "<tr style='border: 0;'>";
                        $tbody .= "<td colspan='3' class='bg-danger text-bold' style='border: 0;text-align: left;'><i class='fa fa-send'></i> Prepare For Phase 3 <div style='display: none;' class='waiting_ph3 text-red text-center blink'>waiting approval produksi<br>on phase 3</div></td>";
                        $tbody .= "</tr>";
                        $ph3_waiting_approve = 0;
                        foreach($arrProdukJadiPh2['7002']['hold'] as $k => $bahan2 ){
                            $tbody .= "<tr style='border: 0'>";
                            $tbody .= "<td style='border: 0;text-align: right;'>". $bahan2->nama ."</td>";
                            $tbody .= "<td style='border: 0;text-align: right;'>". number_format($bahan2->jumlah, 0) ."</td>";
                            $tbody .= "<td style='border: 0;text-align: left;'>". $bahan2->satuan ."</td>";
                            $tbody .= "</tr>";
                            $ph3_waiting_approve += $bahan2->jumlah;
                        }

                        if($ph3_waiting_approve*1>0){
                            $addScript .= "$('.waiting_ph3').show();\n";
                        }
                    }

                    $tbody .= "</table>";
                }
                $tbody .= "</td>";
                //endregion =======================   PHASE II   =====================

                //region =======================   PHASE III   =====================
                $tbody .= "<td>";
                if(isset($arrItems2[$pid]['produk'])){

                    $tbody .= "<table style='border: 0;width:100%;'>";
                    $progressPh3=0;

                    if(isset($arrProdukJadiPh2['7003']['active'])){
                        $tbody .= "<tr style='border: 0;'>";
                        $tbody .= "<td colspan='3' class='bg-warning text-bold' style='border: 0;text-align: left;'><i class='processing_ph3 fa fa-gear'></i> processing </td>";
                        $tbody .= "</tr>";
                        foreach($arrProdukJadiPh2['7003']['active'] as $k => $bahan2 ){
                            $tbody .= "<tr style='border: 0'>";
                            $tbody .= "<td style='border: 0;text-align: left;'>". $bahan2->nama ."</td>";
                            $tbody .= "<td style='border: 0;text-align: right;'>". number_format($bahan2->jumlah, 0) ."</td>";
                            $tbody .= "<td style='border: 0;text-align: left;'>". $bahan2->satuan ."</td>";
                            $tbody .= "</tr>";
                            $progressPh3 += $bahan2->jumlah*1;
                        }
                    }

                    if($progressPh3>0){
                        $addScript .= "$('.wipph3').removeClass('bg-danger').addClass('bg-success').html(\"<span class='blink'>WORKING</span>\");\n";
                        $addScript .= "$('.processing_ph3').addClass('fa-spin');\n";
                    }
                    else{
                        $addScript .= "$('.wipph3').removeClass('bg-success').addClass('bg-danger').html(\"<span class=''>IDLE</span>\");\n";
                        $addScript .= "$('.processing_ph3').removeClass('fa-spin');\n";
                    }

                    if(isset($arrProdukJadiPh2['7003']['done'])){
                        $tbody .= "<tr style='border: 0;'>";
                        $tbody .= "<td colspan='3' class='bg-success text-bold' style='border: 0;text-align: left;'><i class='fa fa-check text-green'></i> selesai</td>";
                        $tbody .= "</tr>";
                        foreach($arrProdukJadiPh2['7003']['done'] as $k => $bahan2 ){
                            $tbody .= "<td style='border: 0;text-align: left;'>". $bahan2->nama ."</td>";
                            $tbody .= "<td style='border: 0;text-align: right;'>". number_format($bahan2->jumlah, 0) ."</td>";
                            $tbody .= "<td style='border: 0;text-align: left;'>". $bahan2->satuan ."</td>";
                        }
                    }

                    if(isset($arrProdukJadiPh3['7003']['active'])){
                        $tbody .= "<tr style='border: 0;'>";
                        $tbody .= "<td colspan='3' class='bg-danger text-bold' style='border: 0;text-align: left;'><i class='fa fa-gift'></i> Produk WIP PHASE 3</td>";
                        $tbody .= "</tr>";
                        foreach($arrProdukJadiPh3['7003']['active'] as $k => $bahan2 ){
                            $tbody .= "<td style='border: 0;text-align: left;'>". $bahan2->nama ."</td>";
                            $tbody .= "<td style='border: 0;text-align: right;'>". number_format($bahan2->jumlah, 0) ."</td>";
                            $tbody .= "<td style='border: 0;text-align: left;'>". $bahan2->satuan ."</td>";
                        }
                    }

                    if(isset($arrProdukJadiPh3['7003']['hold'])){
                        $tbody .= "<tr style='border: 0;'>";
                        $tbody .= "<td colspan='3' class='bg-danger text-bold' style='border: 0;text-align: left;'><i class='fa fa-send'></i> Prepare For Phase 4 <div style='display: none;' class='waiting_ph4 text-red text-center blink'>waiting approval produksi<br>on phase 4</div></td>";
                        $tbody .= "</tr>";
                        $ph4_waiting_approve = 0;
                        foreach($arrProdukJadiPh3['7003']['hold'] as $k => $bahan2 ){
                            $tbody .= "<tr style='border: 0'>";
                            $tbody .= "<td style='border: 0;text-align: right;'>". $bahan2->nama ."</td>";
                            $tbody .= "<td style='border: 0;text-align: right;'>". number_format($bahan2->jumlah, 0) ."</td>";
                            $tbody .= "<td style='border: 0;text-align: left;'>". $bahan2->satuan ."</td>";
                            $tbody .= "</tr>";
                            $ph4_waiting_approve += $bahan2->jumlah;
                        }

                        if($ph4_waiting_approve*1>0){
                            $addScript .= "$('.waiting_ph4').show();\n";
                        }
                    }

                    $tbody .= "</table>";
                }
                $tbody .= "</td>";
                //endregion =======================   PHASE III   =====================

                //region =======================   PHASE IV   =====================
                $tbody .= "<td>";
                if(isset($arrItems2[$pid]['produk'])){

                    $tbody .= "<table style='border: 0;width:100%;'>";
                    $progressPh4=0;

                    if(isset($arrProdukJadiPh3['7004']['active'])){
                        $tbody .= "<tr style='border: 0;'>";
                        $tbody .= "<td colspan='3' class='bg-warning text-bold' style='border: 0;text-align: left;'><i class='processing_ph4 fa fa-gear'></i> processing </td>";
                        $tbody .= "</tr>";
                        foreach($arrProdukJadiPh3['7004']['active'] as $k => $bahan2 ){
                            $tbody .= "<tr style='border: 0'>";
                            $tbody .= "<td style='border: 0;text-align: left;'>". $bahan2->nama ."</td>";
                            $tbody .= "<td style='border: 0;text-align: right;'>". number_format($bahan2->jumlah, 0) ."</td>";
                            $tbody .= "<td style='border: 0;text-align: left;'>". $bahan2->satuan ."</td>";
                            $tbody .= "</tr>";
                            $progressPh4 += $bahan2->jumlah*1;
                        }
                    }

                    if($progressPh4>0){
                        $addScript .= "$('.wipph4').removeClass('bg-danger').addClass('bg-success').html(\"<span class='blink'>WORKING</span>\");\n";
                        $addScript .= "$('.processing_ph4').addClass('fa-spin');\n";
                    }
                    else{
                        $addScript .= "$('.wipph4').removeClass('bg-success').addClass('bg-danger').html(\"<span class=''>IDLE</span>\");\n";
                        $addScript .= "$('.processing_ph4').removeClass('fa-spin');\n";
                    }

                    if(isset($arrProdukJadiPh3['7004']['done'])){
                        $tbody .= "<tr style='border: 0;'>";
                        $tbody .= "<td colspan='3' class='bg-success text-bold' style='border: 0;text-align: left;'><i class='fa fa-check text-green'></i> selesai</td>";
                        $tbody .= "</tr>";
                        foreach($arrProdukJadiPh3['7004']['done'] as $k => $bahan2 ){
                            $tbody .= "<td style='border: 0;text-align: left;'>". $bahan2->nama ."</td>";
                            $tbody .= "<td style='border: 0;text-align: right;'>". number_format($bahan2->jumlah, 0) ."</td>";
                            $tbody .= "<td style='border: 0;text-align: left;'>". $bahan2->satuan ."</td>";
                        }
                    }

                    if(isset($arrProdukJadiPh4['7004']['active'])){
                        $tbody .= "<tr style='border: 0;'>";
                        $tbody .= "<td colspan='3' class='bg-danger text-bold' style='border: 0;text-align: left;'><i class='fa fa-gift'></i> Produk WIP PHASE 4</td>";
                        $tbody .= "</tr>";
                        foreach($arrProdukJadiPh4['7004']['active'] as $k => $bahan2 ){
                            $tbody .= "<td style='border: 0;text-align: left;'>". $bahan2->nama ."</td>";
                            $tbody .= "<td style='border: 0;text-align: right;'>". number_format($bahan2->jumlah, 0) ."</td>";
                            $tbody .= "<td style='border: 0;text-align: left;'>". $bahan2->satuan ."</td>";
                        }
                    }

                    if(isset($arrProdukJadiPh4['7004']['hold'])){
                        $tbody .= "<tr style='border: 0;'>";
                        $tbody .= "<td colspan='3' class='bg-danger text-bold' style='border: 0;text-align: left;'><i class='fa fa-send'></i> Prepare For Phase 5 <div style='display: none;' class='waiting_ph5 text-red text-center blink'>waiting approval produksi<br>on phase 5</div></td>";
                        $tbody .= "</tr>";
                        $ph4_waiting_approve = 0;
                        foreach($arrProdukJadiPh4['7004']['hold'] as $k => $bahan2 ){
                            $tbody .= "<tr style='border: 0'>";
                            $tbody .= "<td style='border: 0;text-align: right;'>". $bahan2->nama ."</td>";
                            $tbody .= "<td style='border: 0;text-align: right;'>". number_format($bahan2->jumlah, 0) ."</td>";
                            $tbody .= "<td style='border: 0;text-align: left;'>". $bahan2->satuan ."</td>";
                            $tbody .= "</tr>";
                            $ph4_waiting_approve += $bahan2->jumlah;
                        }

                        if($ph4_waiting_approve*1>0){
                            $addScript .= "$('.waiting_ph4').show();\n";
                        }
                    }

                    $tbody .= "</table>";
                }
                $tbody .= "</td>";
                //endregion =======================   PHASE IV   =====================

                $tbody .= "</tr>";
            }
        }

        $tbody .= "</tbody>";

        //==========================
        $table = "";
        $table .= "<table class='table table-bordered table-sm compact nowrap' style='width:100%;'>";
        $table .= "<caption class='text-red text-small text-bold'>terakhir di perbarui ".indonesian_date(date("Y-m-d H:i:s"))."</caption>";
        $table .= $theader;
        $table .= $tbody;
        $table .= "</table>";
        $table .= "<script>\n";
        $table .= $addScript;
        $table .= "\n</script>";

        if(isset($_SESSION[$cCode])){
            echo $table;
        }
        else{
            echo "SESSION TRANSAKSI EXPIRED, SILAHKAN REFRESH";
            echo "<script>top.$('.modal').modal('hide');</script>";
        }

    }
}
