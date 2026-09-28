<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header("Access-Control-Allow-Origin: *");

$forceDebug = 0;

if($forceDebug){
    error_reporting(-1);
    ini_set('display_errors', 1);
}
else{
    ini_set('display_errors', 0);
    if (version_compare(PHP_VERSION, '5.3', '>=')) {
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
    }
    else {
        error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
    }
}


require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class DataSync extends REST_Controller
{

    function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->database();
        session_write_close();
    }

    public function index()
    {


    }

    public function doSync_get()
    {

        $arrListKonsolidasi = array(
            "produk",
            "price",
            "satuan_produk_relasi",
            "satuan",
//            "acc_coa",
            "diskon",
            "diskon_customer",
            "produk_folders",
            // "per_cabang",
            "per_customer_level",
            "per_customers",
//            "per_employee",
            "bank",
            "company_profile",
            "setting_struk",
            "fifo_avg",
        );

        $dataResult = array();
        $dataQuery = array();
        $tableList = array();

        foreach ($arrListKonsolidasi as $table) {
            $tableList[] = $table;
            $query = $this->db->get($table);
            $result = $query->result();
            $num_rows = $query->num_rows();
            $dataResult[$table] = $result;
            $dataQuery[$table] = $num_rows;
        }

        $result = array(
            "query" => $dataQuery,
            "tableList" => $tableList,
            "data" => $dataResult,
        );

        $this->response($result, 200);
    }

    public function doSync_auto_get()
    {

        //ini yang di pakai
        $arrListKonsolidasi = array(
            "produk" => "-",
            "price" => "-",
            "price_last_purchase" => "-",
            "satuan_produk_relasi" => "-",
            "satuan" => "-",
//            "acc_coa" => "-",// dimatikan supaya tidak disinkron, pos tidak butuh 31-05-2023
            "diskon" => "-",
            "diskon_customer" => "-",
            "produk_folders" => "-",
            "per_cabang" => "-", //dimatikan dulu sementara, sampe update POS bawah
            "per_cabang_device" => "-",
            "per_customer_level" => "-",
            "per_customers" => "-",
//            "per_employee" => "MdlProduk",
            "bank" => "-",
            "company_profile" => "-",
            "setting_struk" => "-",
            "fifo_avg" => "-",
            "_rek_pembantu_customer_cache" => "-",
            "__rek_pembantu_customer__2010050" => "-",
        );

        $devID = $this->uri->segment(4);

        //CEK LAST SINKRON
        $dl = $this->db->get_where('last_sinkron', array('id' => (isset($devID) ? $devID : 1) ), 1, 0);
        $tmpLast = $dl->result();

        $arrLast = isset($tmpLast[0]->blob) && $tmpLast[0]->blob != "" ? blobDecode($tmpLast[0]->blob) : array();

//matiHere();

        $tableKolomList = array();
        $stat = array();
        foreach ($arrListKonsolidasi as $table => $mdls) {
            $lat = $this->db->query("CHECKSUM TABLE $table");
            $tmpListTable = $lat->result();
            $stat[$table]["Checksum"] = $tmpListTable[0]->Checksum;


            $query = $this->db->query("SELECT * FROM $table");
            $jmlKolom = $query->num_fields();
            $tableKolomList[$table] = $jmlKolom;
        }
        $perubahan = array();
        if (!empty($arrLast)) {
            foreach ($stat as $table => $size) {
                if ( !isset($arrLast[$table]) || isset($arrLast[$table]) && $arrLast[$table]['Checksum'] != $size['Checksum']) {
                    $perubahan[$table] = 1;
                }
            }
            $dataReplace = array(
                "id" => isset($devID) ? $devID : 1,
                "blob" => blobEncode($stat),
            );
            $this->db->replace('last_sinkron', $dataReplace);
        }
        else {
            foreach ($arrListKonsolidasi as $table => $size) {
                $perubahan[$table] = 1;
            }
            $dataReplace = array(
                "id" => isset($devID) ? $devID : 1,
                "blob" => blobEncode($stat),
            );
            $this->db->replace('last_sinkron', $dataReplace);
        }

        $dataResult = array();
        $dataQuery = array();
        $tableList = array();

        if(!empty($perubahan)){
            foreach ($perubahan as $table => $mdl) {
                $tableList[] = $table;

                if($table=="__rek_pembantu_customer__2010050"){
                    $this->db->where(array("cabang_id"=>"-1"));
                }
                elseif($table=="_rek_pembantu_customer_cache"){
                    $this->db->where(array("cabang_id"=>"-1", "periode"=>"forever"));
                }
                else{}

                $query = $this->db->get($table);
                $result = $query->result();
                $num_rows = $query->num_rows();
                $dataResult[$table] = $result;
                $dataQuery[$table] = $num_rows;


            }
        }


        $result = array(
            "query" => $dataQuery,
            "tableList" => $tableList,
            "data" => $dataResult,
            "arrLast" => $arrLast,
            "arrTabelKolom" => $tableKolomList,
//            "tmpLast" => $tmpLast,
//            "datas" => $this->uri->segment_array(),
//            "uri" => $this->uri->segment(4),
        );

        $this->response($result, 200);

    }

    //baru nih
    public function doSync_autoV2_post()
    {
        $arr = $_REQUEST;
        $arrList = $arr['date_last'];
        $machine_id = $arr['machine_id'];
        $cabang_id  = $arr['cabang_id'];
        $hasil = array();
        $query = array();

//        $arrList = array(

//              "produk"                => "1990-01-01 23:59:59",
//              "price"                 => "1990-01-01 23:59:59",
//              "price_last_purchase"   => "1990-01-01 23:59:59",
//              "price_per_area"        => "1990-01-01 23:59:59",
//              "satuan_produk_relasi"  => "1990-01-01 23:59:59",
//              "satuan"                => "1990-01-01 23:59:59",
//              "diskon"                => "1990-01-01 23:59:59",
//              "diskon_customer"       => "1990-01-01 23:59:59",
//              "produk_folders"        => "1990-01-01 23:59:59",
//              "per_cabang"            => "1990-01-01 23:59:59",
//              "per_cabang_device"     => "1990-01-01 23:59:59",
//              "per_customer_level"    => "1990-01-01 23:59:59",
//              "per_customers"         => "1990-01-01 23:59:59",
//              "bank"                  => "1990-01-01 23:59:59",
//              "company_profile"       => "1990-01-01 23:59:59",
//              "setting_struk"         => "1990-01-01 23:59:59",
//              "fifo_avg"              => "1990-01-01 23:59:59",

//             "_rek_pembantu_customer_cache"     => "1990-01-01 23:59:59",
//             "__rek_pembantu_customer__2010050" => "1990-01-01 23:59:59",

//             "__raw_rek_pembantu__1010040050"   => "COBA ADD TABLE",
//             "acc_coa"                          => "perubahan data coa",
//             "per_employee"                     => "perubahan akun karyawan",

//        );

        if($cabang_id=="none"){
            $arrCabangDev = array();
            $devTmp = $this->db->get("per_cabang_device");
            $cabDevTmp =  $devTmp ? $devTmp->result() : array();
            if(!empty($cabDevTmp)){
                foreach($cabDevTmp as $k => $cab){
                    $cabDevTmp[$cab->machine_id] = $cab->cabang_id;
                }
            }
            $cabang_id = $cabDevTmp[$machine_id];
        }
//
        foreach($arrList as $table => $last_update){
            $a = array();
            //kebutuhan khusus masing² table-table
            switch($table){
                case "per_cabang":
                case "per_cabang_device":
                    $last_update = date("Y-m-d H:i:s", strtotime("-2 minutes", strtotime($last_update)));
                    $this->db->where("last_update > ", $last_update);
                    $qtmp = $this->db->get($table);
                    $a = $qtmp ? $qtmp->result() : array();
                    break;
                default:
                    $last_update = date("Y-m-d H:i:s", strtotime("-2 minutes", strtotime($last_update)));
                    $this->db->where("last_update > ", $last_update);
                    $qtmp = $this->db->get($table);
                    $a = $qtmp ? $qtmp->result() : array();
                    break;
            }
            if(!empty($a)){
                $hasil[$table] = $a;
            }
        }
//
        $tableKolomList = array();
        $tableKolom = array();
        foreach($arrList as $table => $last_update){
            $query = $this->db->query("SELECT * FROM $table");
            $queryKolom = $this->db->query("SHOW columns FROM $table");
            $listKolom = $queryKolom->result();
            $jmlKolom = $query->num_fields();
            $tableKolomList[$table] = $jmlKolom;
            $availTableColumn = array();
            if(!empty($listKolom)){
                foreach($listKolom as $k => $col){
                    $availTableColumn[] = $col;
                }
            }
            $tableKolom[$table] = $availTableColumn;
        }

        $result = array(
            //      "row" => count($hasil),
            "data" => $hasil,
            "arrTabelKolom" => $tableKolomList,
            "tableKolom" => $tableKolom,
            //      "arr" => $arr,
            "connection" => 1,
        );

        $this->response($result, 200);

    }

    //baru nih
    public function laporanSync_post(){
        $arr = $_REQUEST;
        $status = $arr['status'];
        $machine_id = $arr['machine_id'];
        $request  = $arr['request'];
        $laporan  = $arr['laporan'];

        $mulai  = $arr['mulai'];
        $selesai  = $arr['selesai'];

        $this->db->set( array('mulai'=>$mulai, 'selesai'=>$selesai, 'machine_id'=>$machine_id, 'dtime'=> date("Y-m-d H:i:s"), 'status'=>$status, "blob_request"=>blobEncode($request), "blob_laporan"=>blobEncode($laporan)) );
        $this->db->insert('last_sinkron_lap');
    }

    //baru nih
    public function login_state_post(){

        $arr = $_REQUEST;

        $cabang_id     = $arr['cabang_id'];
        $state         = $arr['state'];
        $cabang_acc    = $arr['cabang_acc'];
        $machine_id    = $arr['machine_id'];
        $client_date   = $arr['date'];
        $login         = $arr['userProp'];
        $server_date   = date("Y-m-d H:i:s");
        $cpu_info      = $arr['cpu_info'];
        $com_info      = $arr['com_info'];
        $employee_type = $login['employee_type'];

        $this->db->where("cabang_id", $cabang_id);
        $this->db->where("devices", $machine_id);
        $this->db->where("uname", $login['nama_login']);
        $this->db->where("settlement_id", 0);

        $tmpState = $this->db->get("log_login_pos");
        $qTmpState = $this->db->last_query();
        $checkState = $tmpState ? $tmpState->result() : array();

        if($state=="login"&&!empty($checkState)){
            //posisi dia sdh login nih... mau gimana ?
            //cek dulu apakah tgl login nya sama dengan tgl saat ini?
            $tgl_login = date("Y-m-d", strtotime($checkState[0]->dtime_in_client));
            $tgl_skrg = date("Y-m-d");

            if($tgl_login!=$tgl_skrg){
                //jika tidak sama, kemungkinan ini login kemarin
                //cek apakah di tgl login sudah melakukan settlement
                $result = array(
                    "connection" => 1,
                    "status" => 1,
                    "state" => "#1",
                    "settlement_check" => $checkState[0]->settlement_check,
                    "settlement_dtime" => $checkState[0]->settlement_dtime,
                    "client_login_session" => $checkState[0]->client_login_session,
                );
                $this->response($result, 200);
            }
            else{
                $data = array(
                    'dtime_last_login' => date("Y-m-d H:i:s"),
                    'last_ipadd_login' => $_SERVER['REMOTE_ADDR'],
                );
                $this->db->where('id', $checkState[0]->id);
                $this->db->update('log_login_pos', $data);
                //tgl masih sama, kemungkinan ada accident yg hrus membuat dia logout..??
                $result = array(
                    "connection" => 1,
                    "status" => 1,
                    "state" => "#2",
                    "client_login_session" => $checkState[0]->client_login_session,
                    "membership" => blobDecode($login['membership']),
                );
                $this->response($result, 200);
            }
        }
        else{
            //belum ada state login yg aktif, catat log login nya si DIA
            $mem = blobDecode($login['membership']);
            $settlement_check = in_array("o_kasir", $mem);
            $session_login = array(
                "employee_type" => $login['employee_type'],
                "client_date" => $client_date,
                "uname" => $login['nama_login'],
                "uid" => $login['id'],
                "devices" => $machine_id,
            );
            $thisData = array(
                'fulldate' => date("Y-m-d", strtotime($client_date)),
                'dtime_in_client' => $client_date,
                'dtime_in_server' => $server_date,
                'uname' => $login['nama_login'],
                'uid' => $login['id'],
                'devices' => $machine_id,
                'cabang_id' => $cabang_id,
                'cabang_acc' => $cabang_acc,
                'login_state' => $state=="login" ? 1 : 0,
                'ipadd' => $_SERVER['REMOTE_ADDR'],
                'com_info' => $com_info,
                'cpu_info' => $cpu_info,
                'employee_type' => $login['employee_type'],
                'client_login_session' => md5(base64_encode(json_encode($session_login))),
                'settlement_check' => 1,
                'membership' => $login['membership'],
                'membership_intext' => json_encode(blobDecode($login['membership'])),
            );
            $this->db->insert('log_login_pos', $thisData);
            $insert = $this->db->affected_rows();
            if(!$insert){
                $result = array(
                    "connection" => 1,
                    "status" => $insert,
                    "state" => "#4",
                    "tmpQuery" => $qTmpState,
                );
                $this->response($result, 200);
            }
            else{
                $result = array(
                    "connection" => 1,
                    "status" => 1,
                    "state" => "#5",
                    "tmpQuery" => $qTmpState,
                );
                $this->response($result, 200);
            }
        }
    }

    public function sinkronDiskon_get()
    {
        $query = $this->db->get("produk_copy"); //produk baru fresh
        $results = $query->result();
    }

    //untuk sinkron data laporan log POS
    //allGetRawPos akan di matikan di CRON JOB di GANTI createBridge (mode rekening) by chepy 2024-04-23
    public function allGetRawPos_get()
    {
        $from = $this->uri->segment(4);

        $arrOutput=array();
        $arrOutput = json_decode($this->doSyncRawTransaksi());
        $ins=0;
        if(!empty($arrOutput)){
            if(isset($arrOutput->data) && count($arrOutput->data)*1> 0 ){
                $this->db->trans_start();
                $batch = array();
                foreach($arrOutput->data as $k => $arrData ){
                    $batch[$k]["dtime"]            = $arrData->dtime;
                    $batch[$k]["produk_id"]        = $arrData->produk_id;
                    $batch[$k]["produk_nama"]      = $arrData->produk_nama;
                    $batch[$k]["valid_qty"]        = $arrData->qty_kredit;
                    $batch[$k]["produk_sub_total"] = $arrData->kredit;
                    $batch[$k]["produk_sub_hpp"]   = $arrData->hpp;
                    $batch[$k]["produk_sub_laba"]  = $arrData->rugilaba;
                    $batch[$k]["produk_ord_hrg"]   = $arrData->harga;
                    $batch[$k]["produk_ord_hpp"]   = $arrData->hpp;
                    $batch[$k]["produk_ord_laba"]  = $arrData->rugilaba;
                    $batch[$k]["cabang_id"]        = $arrData->cabang_id;

                    if($arrData->qty_return*1>0){
                        $batch[$k]["produk_ord_jml_return"] = $arrData->qty_return*1;
                    }
                    else{
                        $batch[$k]["produk_ord_jml_return"] = 0;
                    }

                }
                $this->db->truncate("transaksi_data_sum_realtimepos_copy");
////                //batch insert
                $ins = $this->db->insert_batch("transaksi_data_sum_realtimepos_copy", $batch);
                $this->db->trans_complete();
            }
        }

        $result = array(
            "status"     => $ins,
            "arrOutput"     => $arrOutput,
        );

        $this->response($result, 200);
    }
    public function doSyncRawTransaksi()
    {

        $microStart = microtime(1);

        $dataResult      = array();
        $dataCountResult = array();
        $dataSizeResult  = array();
        $last_query      = array();

        $fulldate = date('Y-m-d'); //get today
//        $fulldate = date('2023-10-23'); //get today
//        $fulldate = date('Y-m-d', strtotime("-1 day", strtotime(date("Y-m-d")))); //get custom #CODEDS0002

        $this->db->select("produk_id, produk_nama, dtime, fulldate, sum(kredit)as kredit, sum(qty_kredit)as qty_kredit, sum(hpp*qty_kredit) as hpp, harga, sum(rugilaba*qty_kredit)as rugilaba, cabang_id"); //untuk ambil today
        $this->db->where("fulldate", $fulldate);
        $this->db->where("extern2_id", 4010010);
        $this->db->group_by("cabang_id,produk_id");
        $this->db->order_by("produk_id");
        $q = $this->db->get("__raw_rek_pembantu__4");
        $res = $q->result();
        $q = $this->db->last_query();

        $this->db->select("produk_id, produk_nama, dtime, fulldate, sum(debet)as debet, sum(qty_debet)as qty_debet, sum(hpp*qty_debet) as hpp, harga, sum(rugilaba*qty_debet)as rugilaba, cabang_id"); //untuk ambil today
        $this->db->where("fulldate", $fulldate);
        $this->db->where("extern2_id", 4010020);
        $this->db->group_by("cabang_id,produk_id");
        $this->db->order_by("produk_id");
        $q2 = $this->db->get("__raw_rek_pembantu__4");
        $res2 = $q2->result();

        if(!empty($res2)){
            $return = array();
            foreach($res2 as $kt => $rRet){
                $return[$rRet->produk_id] = $rRet;
            }
            $newData=array();
            foreach($res as $k => $rRes){
                $newData[$k] = $rRes;
                if(isset($return[$rRes->produk_id])){
                    $newData[$k]->qty_return = $return[$rRes->produk_id]->qty_debet*1;
                }
            }

            $res = $newData;
        }

        $result = array(
            "data" => $res,
            "query" => $q,
        );

//        echo json_encode($result);
        return json_encode($result);

    }

    //getDayPostBridge akan di matikan di CRON JOB di GANTI createBridge (mode rekening) by chepy 2024-04-23
    public function getDayPostBridge_get()
    {

        $fulldate = date("Y-m-d");
//        $fulldate = date("2023-10-23");
//        $fulldate = date("Y-m-d", strtotime("-1 day", strtotime(date("Y-m-d"))));
        $query = $this->db->get_where("transaksi_data_sum_realtimepos_copy", array('date(dtime)' => $fulldate));

        if( $query->num_rows()>0 ){
            $result = $query->result();
        }
        else{
            $result = array();
        }


        if(!empty($result)){
            foreach($result as $k => $row){
                $dl = $this->db->get_where('transaksi_data_sum_realtimepos_bridge', array("fulldate" => $fulldate, "cabang_id" => $row->cabang_id, "produk_id" => $row->produk_id));
                $tmpLast = $dl->result();
                if(!empty($tmpLast)){
                    if($tmpLast[0]->produk_ord_jml != $row->valid_qty){
                        //echo $row->produk_nama . " - UPDATE ADA PERUBAHAN (".$tmpLast[0]->produk_ord_jml."):(".$row->valid_qty.")<br>";
                        $rowSum = array(
                            "valid_qty" => $row->valid_qty,
                            "produk_ord_jml" => $row->valid_qty
                        );
                        $upd = $this->db->update("transaksi_data_sum_realtimepos_bridge", $rowSum, array("fulldate" => $fulldate, "cabang_id" => $row->cabang_id, "produk_id" => $row->produk_id));
                    }
                    else{
                        //echo $row->produk_nama . " - TIDAK ADA PERUBAHAN (".$tmpLast[0]->produk_ord_jml."):(".$row->valid_qty.")<br>";
                    }
                }
                else{
                    $row->fulldate = date("Y-m-d", strtotime($row->dtime));
                    $row->produk_ord_jml = $row->valid_qty;
                    unset($row->id);
//                    echo $row->produk_nama . " - INSERT (".$row->produk_ord_jml.") ### =>> ";
                    $ins = $this->db->insert("transaksi_data_sum_realtimepos_bridge", $row);
//                    echo "commit: " . $ins . "<br>";
//                    echo "query: " . $this->db->last_query() . "<br><br><br>";
                }
            }
        }
        else{
            echo "data kosong untuk tgl: " . $fulldate;
        }
//        echo json_encode($result);
    }
    public function allGetTransaksiPos_get(){
        $from = $this->uri->segment(4);
        $arrListKonsolidasi = array(
            "transaksi",
        //    "transaksi_data",
        );
        $suffix = "_realtimepos";
        $arrOutput=array();
//        if( $this->checkLogSync() ){
            $arrOutput = json_decode($this->doSyncTransaksiConsolidasi());
//        }
        $lap=array();
        $logID = 0;
        if(!empty($arrOutput)){
            if(isset($arrOutput->data) && count($arrOutput->data)*1> 0 ){
                $this->db->trans_start();
                foreach($arrOutput->data as $table => $arrData ){
                    if(isset($_GET['table'])&&$_GET['table']!=""){
                        if($table==$_GET['table']){
                            if( !empty($arrData) ){
                                $timeMulai = date("Y-m-d H:i:s");
                                $this->db->truncate("$table");
                                $rowInsert=0;
                                $batch=array();
                                foreach($arrData as $i => $row){
                                    $batch[] = $row;
                                    $rowInsert += 1;
                                }
                                $ins = $this->db->insert_batch($table,$batch);
                                $timeEnd = date("Y-m-d H:i:s");
                                $start_date = new DateTime($timeMulai);
                                $since_start = $start_date->diff(new DateTime($timeEnd));
                                $jam = $since_start->h*1>0 ? $since_start->h . " jam, " : "";
                                $menit = $since_start->i*1>0 ? $since_start->i . " menit, " : ($since_start->h*1>0?" 0 menit, ":"");
                                $detik = $since_start->s*1>0 ? $since_start->s . " detik" : ($since_start->i*1>0?" 0 detik":"");
                                $lap[$table] = array(
                                    "time_mulai" => $timeMulai,
                                    "time_end" => $timeEnd,
                                    "row_insert" => $rowInsert,
                                    "diff" => $jam.$menit.$detik,
                                    "timeStart" => strtotime($timeMulai),
                                    "timeEnd" => strtotime($timeEnd),
                                    "micro_diff" => strtotime($timeEnd)-strtotime($timeMulai),
                                );
                            }
                        }
                    }
                    else{
                        if( !empty($arrData) ){
                            $timeMulai = date("Y-m-d H:i:s");
                            $this->db->truncate("$table$suffix");
                            $rowInsert=0;
                            $batch=array();
                            foreach($arrData as $i => $row){
                                $batch[] = $row;
                                $rowInsert += 1;
                            }

                            //batch insert
                            $ins = $this->db->insert_batch("$table$suffix", $batch);

                            $timeEnd = date("Y-m-d H:i:s");
                            $start_date = new DateTime($timeMulai);
                            $since_start = $start_date->diff(new DateTime($timeEnd));
                            $jam = $since_start->h*1>0 ? $since_start->h . " jam, " : "";
                            $menit = $since_start->i*1>0 ? $since_start->i . " menit, " : ($since_start->h*1>0?" 0 menit, ":"");
                            $detik = $since_start->s*1>0 ? $since_start->s . " detik, " : ($since_start->i*1>0?" 0 detik, ":"");
                            $timeDiff = "";
                            $lap["$table$suffix"] = array(
                                "time_mulai" => $timeMulai,
                                "time_end" => $timeEnd,
                                "row_insert" => $rowInsert,
                                "diff" => $jam.$menit.$detik,
                                "timeStart" => strtotime($timeMulai),
                                "timeEnd" => strtotime($timeEnd),
                                "micro_diff" => strtotime($timeEnd)-strtotime($timeMulai),
                            );
                        }
                    }
                }
                $this->load->model("Mdls/" . "MdlActivityLog");
                $hTmp = new MdlActivityLog();
                $tmpHData = array(
                    "title"         => "SINKRON TRANSAKSI POS",
                    "sub_title"     => "-",
                    "uid"           => isset($this->session->login['id']) ? $this->session->login['id'] : "-1",
                    "uname"         => isset($this->session->login['nama']) ? $this->session->login['nama'] : "sys",
                    "dtime"         => date("Y-m-d H:i:s"),
                    "transaksi_id"  => "",
                    "deskripsi_old" => "",
                    "deskripsi_new" => base64_encode(serialize($lap)),
                    "jenis"         => "",
                    "ipadd"         => $_SERVER['REMOTE_ADDR'],
                    "devices"       => $_SERVER['HTTP_USER_AGENT'],
                    "category"      => "sinkron_data",
                    "controller"    => "eusvc-KonsolidasiData",
                    "method"        => "allGetTransaksiPos",
                    "url"           => current_url(),
                );
                $logID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
                $this->db->trans_complete();
            }
        }
        $result = array(
            "arrOutput" => count($arrOutput),
            "laporan"   => $lap,
            "logID"     => $logID,
            "isCLI"     => PHP_SAPI != "cli" ? 0 : 1,
        );
        $this->response($result, 200);
    }
    public function allGetTransaksiPosHour_get(){
        $from = $this->uri->segment(4);
        $arrListKonsolidasi = array(
            "transaksi",
        );
        $suffix = "_realtimepos_hour";
        $arrOutput = array();

        $arrOutput = json_decode($this->doSyncTransaksiConsolidasiHour());

        $lap=array();
        $logID = 0;
        if(!empty($arrOutput)){
            if(isset($arrOutput->data) && count($arrOutput->data)*1> 0 ){
                foreach($arrOutput->data as $table => $arrData ){
                    if( !empty($arrData) ){
                            $timeMulai = date("Y-m-d H:i:s");
                            if("$table$suffix"=="transaksi_data_sum_realtimepos_hour"){
                                $batch=array();
                                $rowInsert=0;
                                foreach($arrData as $i => $row){
                                    $batch2=array();
                                    foreach($row as $jam => $rowJam){
                                        $batch3=array();
                                        foreach($rowJam as $ks => $rJ){
                                            unset($rJ->id);
                                            $batch3[] = $rJ;
                                            $rowInsert += 1;
                                        }
                                        $batch2 = array_merge($batch2,$batch3);
                                    }
                                    $batch = array_merge($batch,$batch2);
                                }
                            }
                            else{
                                $this->db->trans_start();
                                $this->db->truncate("$table$suffix");
                                $rowInsert=0;
                                $batch=array();
                                foreach($arrData as $i => $row){
                                    $batch[] = $row;
                                    $rowInsert += 1;
                                }
                            }
                            //batch insert
//                            $ins = $this->db->insert_batch("$table$suffix",$batch);
                            if("$table$suffix"=="transaksi_data_sum_realtimepos_hour"){
                                $ins2=0;
                                $upd2=0;
                                $listQuery = array();
                                foreach($batch as $k => $rowSum){
                                    //$this->db->truncate("$table$suffix");
                                    //(CONCAT(thn,'',bln,'',tgl,'',jam,'',machine_id,'',cabang_id,'',produk_id))
                                    $keyword = $rowSum->thn."".$rowSum->bln."".$rowSum->tgl."".$rowSum->jam."".$rowSum->machine_id."".$rowSum->cabang_id."".$rowSum->produk_id;
                                    $this->db->where("keyword='$keyword'");
                                    $cd = $this->db->get("$table$suffix");

//                                    matiHere($this->db->last_query());
                                    $tmpDev = $cd->result();

                                    $upd=0;
                                    $ins=0;
                                    if($tmpDev){
                                        $upd = $this->db->update("$table$suffix", $rowSum, array("id"=>$tmpDev[0]->id)) or die( $this->db->last_query() );
                                    }
                                    else{
                                        $ins = $this->db->insert("$table$suffix", $rowSum) or die( $this->db->last_query() );
                                    }
                                    if($ins){
                                        $ins2++;
                                        $listQuery[] = $this->db->last_query();
                                    }
                                    if($upd){
                                        $upd2++;
                                        $listQuery[] = $this->db->last_query();
                                    }
                                }
                            }
                            else{
                                $ins = $this->db->insert_batch("$table$suffix",$batch);
                                $this->db->trans_complete();

                            }

                            $timeEnd = date("Y-m-d H:i:s");
                            $start_date = new DateTime($timeMulai);
                            $since_start = $start_date->diff(new DateTime($timeEnd));
                            $jam = $since_start->h*1>0 ? $since_start->h . " jam, " : "";
                            $menit = $since_start->i*1>0 ? $since_start->i . " menit, " : ($since_start->h*1>0?" 0 menit, ":"");
                            $detik = $since_start->s*1>0 ? $since_start->s . " detik, " : ($since_start->i*1>0?" 0 detik, ":"");
                            $timeDiff = "";
                            $lap["$table$suffix"] = array(
                                "ins" => $ins,
                                "ins2" => $ins2,
                                "time_mulai" => $timeMulai,
                                "time_end" => $timeEnd,
                                "row_insert" => $rowInsert,
                                "diff" => $jam.$menit.$detik,
                                "timeStart" => strtotime($timeMulai),
                                "timeEnd" => strtotime($timeEnd),
                                "micro_diff" => strtotime($timeEnd)-strtotime($timeMulai),
                            );
                        }


                }

//                echo json_encode($listQuery);

//                $this->load->model("Mdls/" . "MdlActivityLog");
//                $hTmp = new MdlActivityLog();
//                $tmpHData = array(
//                    "title"         => "SINKRON TRANSAKSI POS",
//                    "sub_title"     => "-",
//                    "uid"           => isset($this->session->login['id']) ? $this->session->login['id'] : "-1",
//                    "uname"         => isset($this->session->login['nama']) ? $this->session->login['nama'] : "sys",
//                    "dtime"         => date("Y-m-d H:i:s"),
//                    "transaksi_id"  => "",
//                    "deskripsi_old" => "",
//                    "deskripsi_new" => base64_encode(serialize($lap)),
//                    "jenis"         => "",
//                    "ipadd"         => $_SERVER['REMOTE_ADDR'],
//                    "devices"       => $_SERVER['HTTP_USER_AGENT'],
//                    "category"      => "sinkron_data",
//                    "controller"    => "eusvc-KonsolidasiData",
//                    "method"        => "allGetTransaksiPos",
//                    "url"           => current_url(),
//                );
//                $logID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
//                $this->db->trans_complete();
            }
        }
        $result = array(
            "arrOutput" => count($arrOutput),
            "laporan"   => $lap,
            "logID"     => $logID,
            "isCLI"     => PHP_SAPI != "cli" ? 0 : 1,
        );
        $this->response($result, 200);
    }

    public function allGetStockProduk_get(){

        $arrListKonsolidasi = array(
            "stock_locker",
        );

        $dataResult = array();
        $dataQuery = array();
        $tableList = array();

        foreach ($arrListKonsolidasi as $table) {
            $tableList[] = $table;
            $this->db->where("state='active' and jenis='produk'");
            $query = $this->db->get($table);
            $result = $query->result();
            $num_rows = $query->num_rows();
            $f_result = array();
            foreach($result as $k => $res ){
                $f_result[$res->produk_id] = $res->jumlah;
            }
            $dataResult[$table] = $f_result;
            $dataQuery[$table] = $num_rows;
        }

        $result = array(
            "query" => $dataQuery,
            "tableList" => $tableList,
            "data" => $dataResult,
        );

        $this->response($result, 200);
    }

    public function allDiskonPos_get(){

        $from = $this->uri->segment(4);

        $arrListKonsolidasi = array(
//            "diskon",
//            "diskon_customer",
        );

        $url = ADM_DOMAIN . "/eusvc/KonsolidasiData/doSync_auto/9999";

        $ch = New Curl();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close ($ch);

        $arrOutput = json_decode($server_output);

        $lap=array();
        $logID = 0;
        if(!empty($arrOutput)){
            if(isset($arrOutput->data) && count($arrOutput->data)*1> 0 ){
                $this->db->trans_start();
                foreach($arrOutput->data as $table => $arrData ){
                    if(isset($_GET['table'])&&$_GET['table']!=""){
                        if($table==$_GET['table']){
                            if( !empty($arrData) ){
                                $timeMulai = date("Y-m-d H:i:s");
                                $this->db->truncate("$table");
                                $rowInsert=0;
                                $batch=array();
                                foreach($arrData as $i => $row){
//                                    $ins = $this->db->insert("$table",$row);
                                    $batch[] = $row;
//                                    if($ins){
                                    $rowInsert += 1;
//                                    }
                                }

                                $ins = $this->db->insert_batch($table,$batch);

                                $timeEnd = date("Y-m-d H:i:s");
                                $start_date = new DateTime($timeMulai);
                                $since_start = $start_date->diff(new DateTime($timeEnd));
                                $jam = $since_start->h*1>0 ? $since_start->h . " jam, " : "";
                                $menit = $since_start->i*1>0 ? $since_start->i . " menit, " : ($since_start->h*1>0?" 0 menit, ":"");
                                $detik = $since_start->s*1>0 ? $since_start->s . " detik" : ($since_start->i*1>0?" 0 detik":"");
                                $lap[$table] = array(
                                    "time_mulai" => $timeMulai,
                                    "time_end" => $timeEnd,
                                    "row_insert" => $rowInsert,
                                    "diff" => $jam.$menit.$detik,
                                    "timeStart" => strtotime($timeMulai),
                                    "timeEnd" => strtotime($timeEnd),
                                    "micro_diff" => strtotime($timeEnd)-strtotime($timeMulai),
                                );
                            }
                        }
                    }
                    else{
                        if( !empty($arrData) ){
                            $timeMulai = date("Y-m-d H:i:s");
                            $this->db->truncate("$table");
                            $rowInsert=0;
                            $batch=array();
                            foreach($arrData as $i => $row){
//                                $ins = $this->db->insert("$table",$row);
                                $batch[] = $row;
//                                if($ins){
                                $rowInsert += 1;
//                                }
                            }


                            //batch insert
                            $ins = $this->db->insert_batch($table,$batch);
                            cekMerah($this->db->last_query());
//                            arrPrint($batch);

//matiHere(__LINE__);
                            $timeEnd = date("Y-m-d H:i:s");
                            $start_date = new DateTime($timeMulai);
                            $since_start = $start_date->diff(new DateTime($timeEnd));
                            $jam = $since_start->h*1>0 ? $since_start->h . " jam, " : "";
                            $menit = $since_start->i*1>0 ? $since_start->i . " menit, " : ($since_start->h*1>0?" 0 menit, ":"");
                            $detik = $since_start->s*1>0 ? $since_start->s . " detik, " : ($since_start->i*1>0?" 0 detik, ":"");
                            $timeDiff = "";
                            $lap[$table] = array(
                                "status" => $ins,
                                "time_mulai" => $timeMulai,
                                "time_end" => $timeEnd,
                                "row_insert" => $rowInsert,
                                "diff" => $jam.$menit.$detik,
                                "timeStart" => strtotime($timeMulai),
                                "timeEnd" => strtotime($timeEnd),
                                "micro_diff" => strtotime($timeEnd)-strtotime($timeMulai),
                            );
                        }
                    }
                }
                $this->load->model("Mdls/" . "MdlActivityLog");
                $hTmp = new MdlActivityLog();
                $tmpHData = array(
                    "title"         => "AUTO SINKRON DATA",
                    "sub_title"     => "-",
                    "uid"           => isset($this->session->login['id']) ? $this->session->login['id'] : "-1",
                    "uname"         => isset($this->session->login['nama']) ? $this->session->login['nama'] : "sys",
                    "dtime"         => date("Y-m-d H:i:s"),
                    "transaksi_id"  => "",
                    "deskripsi_old" => "",
                    "deskripsi_new" => base64_encode(json_encode($lap)),
                    "jenis"         => "",
                    "ipadd"         => $_SERVER['REMOTE_ADDR'],
                    "devices"       => $_SERVER['HTTP_USER_AGENT'],
                    "category"      => "sinkron_data",
                    "controller"    => "eusvc-KonsolidasiData",
                    "method"        => "allDiskonPos_get",
                    "url"           => current_url(),
                );
                $logID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
                $this->db->trans_complete();
            }
        }

        $result = array(
//            "tableList"     => $arrListKonsolidasi,
//            "table"     => isset($_GET['table']) && $_GET['table']!="" ? $_GET['table'] : "all table",
            "arrOutput"     => count($arrOutput->data),
            "laporan"     => $lap,
//            "segment"     => $this->uri->segment_array(),
            "logID"     => $logID,
            "isCLI"     => PHP_SAPI != "cli" ? 0 : 1,
        );

//        header("Refresh:10");
        if(isset($from)&&$from=="webadmin"){
            if(PHP_SAPI != "cli"){
                echo "<script>
                      if( confirm('Anda berhasil melakukan Sinkron Data ke POS. Silahkan klik OK untuk kembali.') ){
                          top.history.back();
                      }
                      //top.swal('sinkron berhasil');
                  </script>";
            }
        }
        else{
            if(PHP_SAPI != "cli"){
                echo "<script>
                      if( confirm('Sinkron Data Berhasil, silahkan klik OK untuk kembali.') ){
                          top.history.back();
                      }
                      //top.swal('sinkron berhasil');
                  </script>";
            }
//            $this->response($result, 200);
        }
    }

    public function allDataSales_Handler_post(){

        $arr = $_REQUEST;

        $table  = $arr['table'] . "_pos";
        $data   = $arr['data'];

        $ib = $this->db->insert_batch($table, $data);

        $result=array(
            "status" => 1,
            "ib" => $ib,
            "arrData" => $data,
//            "arr" => $arr,
            "size" => strlen(serialize($arr))+1,
//            "q" => $this->db->last_query(),
        );

        $this->response($result, 200);
    }

    public function doSyncEmployee_old_post()
    {

        $arrListKonsolidasi = array(
            "per_employee" => "--",
        );

        $devID = $this->uri->segment(4);
        $data = $_POST;

        //CEK Diff
        $tmpEmplye = $this->db->get("per_employee")->result();

        $blackListColumn = array(
            "dtime_in",
            "last_dtime",
            "dtime",
            "phpsessid",
            "phpsess_dtime",
            "php_session",
            "ipadd",
            "devices",
            "last_dtime_active",
            "status_login",
        );


        $OridEmployee = array();
        foreach($tmpEmplye as $k => $ddss ){
            $OridEmployee[] = $ddss;
        }
        $dOri = base64_encode(json_encode($OridEmployee));


        $dEmployee = array();
        foreach($tmpEmplye as $k => $dd ){
            foreach($blackListColumn as $bl){
                unset($dd->$bl);
            }
            $dEmployee[] = $dd;
        }

        $dataNow = base64_encode(json_encode($dEmployee));


//        $dataResult = array();
//        $dataQuery = array();
//        $tableList = array();
//
//        foreach ($perubahan as $table => $mdl) {
//            $tableList[] = $table;
//            $query = $this->db->get($table);
//            $result = $query->result();
//            $num_rows = $query->num_rows();
//            $dataResult[$table] = $result;
//            $dataQuery[$table] = $num_rows;
//        }
//
        $result = array(
//            "query" => $dataQuery,
//            "tableList" => $tableList,
//            "data" => $dataResult,
//            "data" => $data['data'],
            "dataNow" => $data['data'] != $dataNow ? $dOri : "nodata",
            "diff" => $data['data'] != $dataNow ? 1 : 0,
        );

        $this->response($result, 200);

    }

    //baru nih
    public function doSyncEmployee_post()
    {
        $arr = $_REQUEST;
        $arrList = $arr['date_last'];
        $machine_id = $arr['machine_id'];
        $cabang_id  = $arr['cabang_id'];
        $hasil = array();
        $query = array();
        if($cabang_id=="none"){
            $arrCabangDev = array();
            $cabDevTmp = $this->db->get("per_cabang_device")->result();
            if(!empty($cabDevTmp)){
                foreach($cabDevTmp as $k => $cab){
                    $cabDevTmp[$cab->machine_id] = $cab->cabang_id;
                }
            }
            $cabang_id = isset($cabDevTmp[$machine_id]) ? $cabDevTmp[$machine_id] : 0;
        }

        foreach($arrList as $table => $last_update){
            $a = array();
            //kebutuhan khusus masing² table-table
            switch($table){
                case "per_cabang":
                    $last_update_f = date("Y-m-d H:i:s", strtotime("-2 minutes", strtotime($last_update)));
//                    $last_update = date("Y-m-d H:i:s", strtotime($last_update));
                    $this->db->where("id", $cabang_id);
                    $this->db->where("last_update > ", $last_update_f);
                    $tmp_a = $this->db->get($table);
                    $a = $tmp_a->num_rows()>0 ? $tmp_a->result() : array();
                    break;
                case "per_cabang_device":
                    $last_update_f = date("Y-m-d H:i:s", strtotime("-2 minutes", strtotime($last_update)));
//                    $last_update = date("Y-m-d H:i:s", strtotime($last_update));
                    $this->db->where("cabang_id", $cabang_id);
                    $this->db->where("last_update > ", $last_update_f);
                    $tmp_a = $this->db->get($table);
                    $a = $tmp_a->num_rows()>0 ? $tmp_a->result() : array();
                    break;
                default:
                    $last_update_f = date("Y-m-d H:i:s", strtotime("-2 minutes", strtotime($last_update)));
//                    $last_update = date("Y-m-d H:i:s", strtotime($last_update));
                    if($last_update!="1990-01-01 23:59:59"){
                        $this->db->where("last_update > ", $last_update_f);
                    }

                    $tmp_a = $this->db->get($table);
                    $a = $tmp_a->num_rows()>0 ? $tmp_a->result() : array();
                    break;
            }
            if(!empty($a)){
                $hasil[$table] = $a;
            }
            $query[$table] = $this->db->last_query();
        }
        $tableKolomList = array();
        $tableKolom = array();
        foreach($arrList as $table => $last_update){
            $query = $this->db->query("SELECT * FROM $table");
            $queryKolom = $this->db->query("SHOW columns FROM $table");
            $listKolom = $queryKolom->result();
            $jmlKolom = $query->num_fields();
            $tableKolomList[$table] = $jmlKolom;

            $availTableColumn = array();
            if(!empty($listKolom)){
                foreach($listKolom as $k => $col){
                    $availTableColumn[] = $col;
                }
            }
            $tableKolom[$table] = $availTableColumn;
        }

        if($cabang_id==0){
            $hasil = array();
            $tableKolomList = array();
            $tableKolom = array();
        }

        if( !empty($hasil) ){
            $result = array(
//                "row" => count($hasil),
                "data" => $hasil,
                "arrTabelKolom" => $tableKolomList,
                "tableKolom" => $tableKolom,
//                "arr" => $arr,
                "connection" => 1,
            );
        }
        else{
            $result = array(
//                "row" => count($hasil),
                "data" => $hasil,
//                "arrList" => $arrList,
                "query" => $query,
                "cabang_id" => $cabang_id,
                "arrTabelKolom" => $tableKolomList,
                "tableKolom" => $tableKolom,
//                "arr" => $arr,
                "connection" => 1,
            );
        }

//        $result = array(
//            "row" => count($arr),
//            "data" => $hasil,
//            "arrTabelKolom" => $tableKolomList,
//            "tableKolom" => $tableKolom,
//            "arr" => $arr,
//        );
        $this->response($result, 200);
    }

    public function doSyncProduk_get()
    {

        $arrListKonsolidasi = array(
            "produk" => "aaaa",
            "price" => "aaaa",
            "satuan_produk_relasi" => "aaaa",
            "satuan" => "aaaa",
            "diskon" => "aaaa",
            "diskon_customer" => "aaaa",
            "produk_folders" => "aaaa",
        );

        $devID = $this->uri->segment(4);

        //CEK LAST SINKRON
        $dl = $this->db->get_where('last_sinkron', array('id' => (isset($devID) ? $devID : 1) ), 1, 0);
        $tmpLast = $dl->result();

        $arrLast = isset($tmpLast[0]->blob) && $tmpLast[0]->blob != "" ? blobDecode($tmpLast[0]->blob) : array();

        $stat = array();
        foreach ($arrListKonsolidasi as $table => $mdls) {
//            $query = $this->db->get($table);
//            $result = $query->result();

//            $lat = $this->db->query("SHOW TABLE STATUS WHERE Name='$table'");
            $lat = $this->db->query("CHECKSUM TABLE $table");
            $tmpListTable = $lat->result();

//            echo "<pre>";
//            print_r($tmpListTable);
//            echo "</pre>";

//            $stat[$table]["size"] = strlen(serialize($result));
//            $stat[$table]["time"] = strtotime($tmpListTable[0]->Update_time);
            $stat[$table]["Checksum"] = $tmpListTable[0]->Checksum;
        }

        $perubahan = array();

        if (!empty($arrLast)) {
//        echo "<br>ATAS !empty";
//        echo "<br>";
            foreach ($stat as $table => $size) {
                if ( isset($arrLast[$table]) && $arrLast[$table]['Checksum'] != $size['Checksum']) {
                    //$timeJadi = $stat[$table]['Checksum'];
                    $perubahan[$table] = 1;
                }
            }

            $dataReplace = array(
                "id" => isset($devID) ? $devID : 1,
                "blob" => blobEncode($stat),
            );
            $this->db->replace('last_sinkron', $dataReplace);
//            $this->db->set('blob', blobEncode($stat));
//            $this->db->where('id', 1);
//            $this->db->update('last_sinkron');
        }
        else {
//            $this->db->set('blob', blobEncode($stat));
//            $this->db->where('id', 1);
//            $this->db->update('last_sinkron');
//            echo "<br>BAWAH empty";
            foreach ($arrListKonsolidasi as $table => $size) {
                //$timeJadi = $stat[$table]['Checksum'];
                $perubahan[$table] = 1;
            }

            $dataReplace = array(
                "id" => isset($devID) ? $devID : 1,
                "blob" => blobEncode($stat),
            );
            $this->db->replace('last_sinkron', $dataReplace);
        }

        $dataResult = array();
        $dataQuery = array();
        $tableList = array();

        foreach ($perubahan as $table => $mdl) {
            $tableList[] = $table;
            $query = $this->db->get($table);
            $result = $query->result();
            $num_rows = $query->num_rows();
            $dataResult[$table] = $result;
            $dataQuery[$table] = $num_rows;
        }

        $result = array(
            "query" => $dataQuery,
            "tableList" => $tableList,
            "data" => $dataResult,
//            "datas" => $this->uri->segment_array(),
//            "uri" => $this->uri->segment(4),
        );

        $this->response($result, 200);

    }

    public function allGetKonsolidasiPos_get(){

        $from = $this->uri->segment(4);

        $arrListKonsolidasi = array(
            "transaksi",
            "transaksi_data",
        );

        $suffix = "_realtimepos";

//        $url = "https://demo.mayagrahakencana.com/boga_pindahbuku/pos/eusvc/KonsolidasiData/doSyncTransaksiPos";
        $url = ADM_DOMAIN . "/eusvc/KonsolidasiData/doSyncTransaksiPos";

        $ch = New Curl();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close ($ch);

        $arrOutput = json_decode($server_output);

        $lap=array();
        $logID = 0;
        if(!empty($arrOutput)){
            if(isset($arrOutput->data) && count($arrOutput->data)*1> 0 ){
                $this->db->trans_start();
                foreach($arrOutput->data as $table => $arrData ){
                    if(isset($_GET['table'])&&$_GET['table']!=""){
                        if($table==$_GET['table']){
                            if( !empty($arrData) ){
                                $timeMulai = date("Y-m-d H:i:s");
                                $this->db->truncate("$table");
                                $rowInsert=0;
                                $batch=array();
                                foreach($arrData as $i => $row){
                                    $batch[] = $row;
                                    $rowInsert += 1;
                                }

                                $ins = $this->db->insert_batch($table,$batch);

                                $timeEnd = date("Y-m-d H:i:s");
                                $start_date = new DateTime($timeMulai);
                                $since_start = $start_date->diff(new DateTime($timeEnd));
                                $jam = $since_start->h*1>0 ? $since_start->h . " jam, " : "";
                                $menit = $since_start->i*1>0 ? $since_start->i . " menit, " : ($since_start->h*1>0?" 0 menit, ":"");
                                $detik = $since_start->s*1>0 ? $since_start->s . " detik" : ($since_start->i*1>0?" 0 detik":"");
                                $lap[$table] = array(
                                    "time_mulai" => $timeMulai,
                                    "time_end" => $timeEnd,
                                    "row_insert" => $rowInsert,
                                    "diff" => $jam.$menit.$detik,
                                    "timeStart" => strtotime($timeMulai),
                                    "timeEnd" => strtotime($timeEnd),
                                    "micro_diff" => strtotime($timeEnd)-strtotime($timeMulai),
                                );
                            }
                        }
                    }
                    else{
                        if( !empty($arrData) ){
                            $timeMulai = date("Y-m-d H:i:s");
                            $this->db->truncate("$table$suffix");
                            $rowInsert=0;
                            $batch=array();
                            foreach($arrData as $i => $row){
                                $batch[] = $row;
                                $rowInsert += 1;
                            }

                            //batch insert
                            $ins = $this->db->insert_batch("$table$suffix",$batch);

                            $timeEnd = date("Y-m-d H:i:s");
                            $start_date = new DateTime($timeMulai);
                            $since_start = $start_date->diff(new DateTime($timeEnd));
                            $jam = $since_start->h*1>0 ? $since_start->h . " jam, " : "";
                            $menit = $since_start->i*1>0 ? $since_start->i . " menit, " : ($since_start->h*1>0?" 0 menit, ":"");
                            $detik = $since_start->s*1>0 ? $since_start->s . " detik, " : ($since_start->i*1>0?" 0 detik, ":"");
                            $timeDiff = "";
                            $lap["$table$suffix"] = array(
                                "time_mulai" => $timeMulai,
                                "time_end" => $timeEnd,
                                "row_insert" => $rowInsert,
                                "diff" => $jam.$menit.$detik,
                                "timeStart" => strtotime($timeMulai),
                                "timeEnd" => strtotime($timeEnd),
                                "micro_diff" => strtotime($timeEnd)-strtotime($timeMulai),
                            );
                        }
                    }
                }
                $this->load->model("Mdls/" . "MdlActivityLog");
                $hTmp = new MdlActivityLog();
                $tmpHData = array(
                    "title"         => "SINKRON TRANSAKSI POS",
                    "sub_title"     => "-",
                    "uid"           => isset($this->session->login['id']) ? $this->session->login['id'] : "-1",
                    "uname"         => isset($this->session->login['nama']) ? $this->session->login['nama'] : "sys",
                    "dtime"         => date("Y-m-d H:i:s"),
                    "transaksi_id"  => "",
                    "deskripsi_old" => "",
                    "deskripsi_new" => base64_encode(serialize($lap)),
                    "jenis"         => "",
                    "ipadd"         => $_SERVER['REMOTE_ADDR'],
                    "devices"       => $_SERVER['HTTP_USER_AGENT'],
                    "category"      => "sinkron_data",
                    "controller"    => "eusvc-KonsolidasiData",
                    "method"        => "allGetTransaksiPos",
                    "url"           => current_url(),
                );
                $logID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));

                $this->db->trans_complete();
            }
        }

        $result = array(
//            "tableList"     => $arrListKonsolidasi,
//            "table"     => isset($_GET['table']) && $_GET['table']!="" ? $_GET['table'] : "all table",
            "arrOutput"     => count($arrOutput),
            "laporan"     => $lap,
//            "segment"     => $this->uri->segment_array(),
            "logID"     => $logID,
            "isCLI"     => PHP_SAPI != "cli" ? 0 : 1,
        );

        $this->response($result, 200);
    }

    public function getConsolidasi_get(){

//        error_reporting(-1);
//        ini_set('display_errors', 1);

        $this->response($this->doSyncTransaksiConsolidasi(), 200);
    }

    public function doSyncTransaksiConsolidasi()
    {

        $microStart = microtime(1);

        $dataResult      = array();
        $dataCountResult = array();
        $dataSizeResult  = array();
        $last_query      = array();

        $fulldate = date('Y-m-d'); //get today
//        $fulldate = date('Y-m-d', strtotime("-1 day", strtotime(date("Y-m-d")))); //get custom #CODEDS0001
        $last_id = isset($_GET['transaksi']) ? $_GET['transaksi'] : 0;

        $this->db->where("link_id='0' and fulldate = '".$fulldate."' and jenis IN('582')"); //untuk ambil today
        $this->db->order_by("id", "desc");
//        $this->db->limit(1);
        $q = $this->db->get("transaksi_consolidasi");
        $res = $q->result();
        $last_query["transaksi"] = $this->db->last_query();
        $dataResult['transaksi'] = $res;
        $dataCountResult['transaksi'] = count($res);
        $dataSizeResult['transaksi'] = strlen(serialize($res))+1;

        $daTrId=array();
        if(!empty($res)){
            foreach($res as $k => $dat){
                $daTrId[] = $dat->x_id;
            }
        }


        if(!empty($daTrId)){
            $prd = $this->db->get("produk");
            $resDataProduk = $prd->result();
            $arrProduk_cabang = $prd->result();
            $arrProduk_barcode=array();
            foreach($resDataProduk as $k => $rowD ){
                $arrProduk_barcode[$rowD->id] = $rowD->barcode;
                $arrProduk_cabang[$rowD->id] = $rowD->cabang_id;
            }

            $this->db->where_in("transaksi_id", $daTrId);
            $this->db->like('dtime', "$fulldate");

//        $this->db->limit(0);

            $qDt = $this->db->get("transaksi_data_consolidasi");
            $resData = $qDt->result();
            $last_query["transaksi_data"] = $this->db->last_query();
            $dataResult['transaksi_data'] = $resData;
            $dataCountResult['transaksi_data'] = count($resData);
            $dataSizeResult['transaksi_data'] = strlen(serialize($resData))+1;
        }


//        arrPrint( $this->db->last_query() );
//        arrPrint( 'count($resData): ' . count($resData) );
//        arrPrint( $resData );
//        arrPrint($daTrId);
//        matiHere();

        $sum_by_produk = array();
        $tmpSum = array();
        if(!empty($resData)){
            foreach($resData as $k => $dRow){

                $newRow = (array)$dRow;

                if(!isset($tmpSum[$dRow->produk_jenis][$dRow->produk_id]['valid_qty'])){
                    $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['valid_qty'] = 0;
                }
                if(!isset($tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_ord_jml'])){
                    $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_ord_jml'] = 0;
                }
                if(!isset($tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_total'])){
                    $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_total'] = 0;
                }
                if(!isset($tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_hpp'])){
                    $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_hpp'] = 0;
                }
                if(!isset($tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_laba'])){
                    $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_laba'] = 0;
                }

                $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['valid_qty'] += $newRow['valid_qty'];
                $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_ord_jml'] += $newRow['produk_ord_jml'];
                $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_total'] += ($newRow['valid_qty']*$newRow['produk_ord_hrg']);
                $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_hpp'] += ($newRow['valid_qty']*$newRow['produk_ord_hpp']);
                $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_laba'] += ($newRow['produk_ord_laba']);
                $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['transaksi_id'][] = $newRow['transaksi_id'];

                if(!isset($sum_by_produk[$dRow->produk_jenis])){
                    $sum_by_produk[$dRow->produk_jenis] = array();
                }

                if(!isset($sum_by_produk[$dRow->produk_jenis][$dRow->produk_id])){
                    $sum_by_produk[$dRow->produk_jenis][$dRow->produk_id]= array();
                }

                $sum_by_produk[$dRow->produk_jenis][$dRow->produk_id] = $newRow;

                $sum_by_produk[$dRow->produk_jenis][$dRow->produk_id]['valid_qty'] = $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['valid_qty'];
                $sum_by_produk[$dRow->produk_jenis][$dRow->produk_id]['produk_ord_jml'] = $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_ord_jml'];
                $sum_by_produk[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_total'] = $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_total'];
                $sum_by_produk[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_hpp'] = $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_hpp'];
                $sum_by_produk[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_laba'] = $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_laba'];
                $sum_by_produk[$dRow->produk_jenis][$dRow->produk_id]['ext_intext'] = json_encode($tmpSum[$dRow->produk_jenis][$dRow->produk_id]['transaksi_id']);
                $sum_by_produk[$dRow->produk_jenis][$dRow->produk_id]['produk_kode'] = isset($arrProduk_barcode[$dRow->produk_id]) ? $arrProduk_barcode[$dRow->produk_id] : "";
                $sum_by_produk[$dRow->produk_jenis][$dRow->produk_id]['cabang_id'] = isset($arrProduk_barcode[$dRow->produk_id]) ? $arrProduk_barcode[$dRow->produk_id] : "";

            }
        }

        //============================

        $this->db->where("link_id='0' and fulldate = '".$fulldate."' and jenis IN('982')");
        $this->db->order_by("id", "desc");
//        $this->db->limit(1);
        $q = $this->db->get("transaksi_consolidasi");
        $res = $q->result();

        if(!empty($res)){
            $last_query["transaksi"] = $this->db->last_query();
            $dataResult['transaksi_cancel'] = $res;
            $dataCountResult['transaksi'] = count($res);
            $dataSizeResult['transaksi'] = strlen(serialize($res))+1;
        }

        $daTrId=array();
        if(!empty($res)){
            foreach($res as $k => $dat){
                $daTrId[] = $dat->x_id;
            }
        }

//        arrPrint( $this->db->last_query() );
//        arrPrint( 'count($resData): ' . count($resData) );
//        arrPrint( $resData );
//        arrPrint($daTrId);
//        matiHere();

        $prd = $this->db->get("produk");
        $resDataProduk = $prd->result();
        $arrProduk_barcode=array();
        foreach($resDataProduk as $k => $rowD ){
            $arrProduk_barcode[$rowD->id] = $rowD->barcode;
        }

        if(!empty($daTrId)){
            $this->db->where_in("transaksi_id", $daTrId);
            $this->db->like('dtime', "$fulldate");
            $this->db->limit(0);
            $qDt = $this->db->get("transaksi_data_consolidasi");
            $resData = $qDt->result();
            $last_query["transaksi_data"] = $this->db->last_query();
            $dataResult['transaksi_data_cancel'] = $resData;
            $dataCountResult['transaksi_data'] = count($resData);
            $dataSizeResult['transaksi_data'] = strlen(serialize($resData))+1;
        }
        else{
            $resData = array();
        }

        $sum_by_produk_cancel = array();
        $tmpSum = array();
        if(!empty($resData)){
            foreach($resData as $k => $dRow){

                $newRow = (array)$dRow;

                if(!isset($tmpSum[$dRow->produk_jenis][$dRow->produk_id]['valid_qty'])){
                    $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['valid_qty'] = 0;
                }
                if(!isset($tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_total'])){
                    $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_total'] = 0;
                }
                $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['valid_qty'] += $newRow['valid_qty'];
                $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_total'] += ($newRow['valid_qty']*$newRow['produk_ord_hrg']);
                $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['transaksi_id'][] = $newRow['transaksi_id'];

                if(!isset($sum_by_produk_cancel[$dRow->produk_jenis])){
                    $sum_by_produk_cancel[$dRow->produk_jenis] = array();
                }

                if(!isset($sum_by_produk_cancel[$dRow->produk_jenis][$dRow->produk_id])){
                    $sum_by_produk_cancel[$dRow->produk_jenis][$dRow->produk_id]= array();
                }

                $sum_by_produk_cancel[$dRow->produk_jenis][$dRow->produk_id] = $newRow;

                $sum_by_produk_cancel[$dRow->produk_jenis][$dRow->produk_id]['valid_qty_cancel'] = $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['valid_qty'];

//                $sum_by_produk_cancel[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_total'] = ($tmpSum[$dRow->produk_jenis][$dRow->produk_id]['valid_qty']*$newRow['produk_ord_hrg']);

                $sum_by_produk_cancel[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_total_cancel'] = $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_total'];
                $sum_by_produk_cancel[$dRow->produk_jenis][$dRow->produk_id]['ext_intext'] = json_encode($tmpSum[$dRow->produk_jenis][$dRow->produk_id]['transaksi_id']);
                $sum_by_produk_cancel[$dRow->produk_jenis][$dRow->produk_id]['produk_kode'] = isset($arrProduk_barcode[$dRow->produk_id]) ? $arrProduk_barcode[$dRow->produk_id] : "";
            }
        }
        foreach($sum_by_produk as $jn => $dts){
            foreach($dts as $ppid => $pRow){
                $dataResult['transaksi_data_sum'][] = $pRow;
            }
        }
        foreach($sum_by_produk_cancel as $jn => $dts){
            foreach($dts as $ppid => $pRow){
                $dataResult['transaksi_data_sum_cancel'][] = $pRow;
            }
        }
        $outputData = $dataResult;
        $microEnd = microtime(1);
        $result = array(
            "data" => $outputData,
            "fulldate" => $fulldate,
            "dataCount" => $dataCountResult,
            "dataSize" => $dataSizeResult,
            "microStart" => $microStart,
            "microEnd" => $microEnd,
        );
        return json_encode($result);

    }

    public function doSyncTransaksiConsolidasiHour()
    {
        $microStart = microtime(1);
        $dataResult      = array();
        $dataCountResult = array();
        $dataSizeResult  = array();
        $last_query      = array();

        $cd = $this->db->get("per_cabang_device");
        $tmpDev = $cd->result();
        $cabang=array();
        foreach($tmpDev as $k => $rCab){
            $cabang[$rCab->machine_id] = $rCab->cabang_id;
        }


        $fulldate = date('Y-m-d'); //get today
//        $fulldate = date('Y-m-d', strtotime("-1 day", strtotime(date("Y-m-d")))); //get custom #CODEDS0001
//        $fulldate = "2023-09-24";

        $last_id = isset($_GET['transaksi']) ? $_GET['transaksi'] : 0;
        $this->db->where("link_id='0' and fulldate = '".$fulldate."' and jenis IN('582')"); //untuk ambil today
        $this->db->order_by("id", "desc");
        $q = $this->db->get("transaksi_consolidasi");
        $res = $q->result();
        $last_query["transaksi"] = $this->db->last_query();
//        $dataResult['transaksi'] = $res;
        $dataCountResult['transaksi'] = count($res);
        $dataSizeResult['transaksi'] = strlen(serialize($res))+1;

        $daTrId=array();
        if(!empty($res)){
            foreach($res as $k => $dat){
                $daTrId[] = $dat->x_id;
            }
        }
        if(!empty($daTrId)){
            $prd = $this->db->get("produk");
            $resDataProduk = $prd->result();
            $arrProduk_barcode=array();
            foreach($resDataProduk as $k => $rowD ){
                $arrProduk_barcode[$rowD->id] = $rowD->barcode;
            }
            $this->db->where_in("transaksi_id", $daTrId);
            $this->db->like('dtime', "$fulldate");
            $qDt = $this->db->get("transaksi_data_consolidasi");
            $resData = $qDt->result();
            $last_query["transaksi_data"] = $this->db->last_query();
//            $dataResult['transaksi_data'] = $resData;
            $dataCountResult['transaksi_data'] = count($resData);
            $dataSizeResult['transaksi_data'] = strlen(serialize($resData))+1;
        }

        $sum_by_produk = array();
        $tmpSum = array();
        if(!empty($resData)){
            foreach($resData as $k => $dRow){
                $newRow = (array)$dRow;
                $hour_key = $dRow->jam;

                $cabang_id = isset($cabang[$dRow->machine_id]) ? $cabang[$dRow->machine_id] : "";

                if(!isset($tmpSum[$cabang_id][$dRow->produk_jenis][$dRow->produk_id][$hour_key]['valid_qty'])){
                    $tmpSum[$cabang_id][$dRow->produk_jenis][$dRow->produk_id][$hour_key]['valid_qty'] = 0;
                }
                if(!isset($tmpSum[$cabang_id][$dRow->produk_jenis][$dRow->produk_id][$hour_key]['produk_ord_jml'])){
                    $tmpSum[$cabang_id][$dRow->produk_jenis][$dRow->produk_id][$hour_key]['produk_ord_jml'] = 0;
                }
                if(!isset($tmpSum[$cabang_id][$dRow->produk_jenis][$dRow->produk_id][$hour_key]['produk_sub_total'])){
                    $tmpSum[$cabang_id][$dRow->produk_jenis][$dRow->produk_id][$hour_key]['produk_sub_total'] = 0;
                }
                if(!isset($tmpSum[$cabang_id][$dRow->produk_jenis][$dRow->produk_id][$hour_key]['produk_sub_hpp'])){
                    $tmpSum[$cabang_id][$dRow->produk_jenis][$dRow->produk_id][$hour_key]['produk_sub_hpp'] = 0;
                }
                if(!isset($tmpSum[$cabang_id][$dRow->produk_jenis][$dRow->produk_id][$hour_key]['produk_sub_laba'])){
                    $tmpSum[$cabang_id][$dRow->produk_jenis][$dRow->produk_id][$hour_key]['produk_sub_laba'] = 0;
                }

                $tmpSum[$cabang_id][$dRow->produk_jenis][$dRow->produk_id][$hour_key]['valid_qty']         += $newRow['valid_qty'];
                $tmpSum[$cabang_id][$dRow->produk_jenis][$dRow->produk_id][$hour_key]['produk_ord_jml']    += $newRow['produk_ord_jml'];
                $tmpSum[$cabang_id][$dRow->produk_jenis][$dRow->produk_id][$hour_key]['produk_sub_total']  += ($newRow['valid_qty']*$newRow['produk_ord_hrg']);
                $tmpSum[$cabang_id][$dRow->produk_jenis][$dRow->produk_id][$hour_key]['produk_sub_hpp']    += ($newRow['valid_qty']*$newRow['produk_ord_hpp']);
                $tmpSum[$cabang_id][$dRow->produk_jenis][$dRow->produk_id][$hour_key]['produk_sub_laba']   += ($newRow['produk_ord_laba']);
                $tmpSum[$cabang_id][$dRow->produk_jenis][$dRow->produk_id][$hour_key]['transaksi_id'][]     = $newRow['transaksi_id'];

                if(!isset($sum_by_produk[$cabang_id][$dRow->produk_jenis])){
                    $sum_by_produk[$cabang_id][$dRow->produk_jenis] = array();
                }
                if(!isset($sum_by_produk[$cabang_id][$dRow->produk_jenis][$dRow->produk_id])){
                    $sum_by_produk[$cabang_id][$dRow->produk_jenis][$dRow->produk_id]= array();
                }
                if(!isset($sum_by_produk[$cabang_id][$dRow->produk_jenis][$dRow->produk_id][$hour_key])){
                    $sum_by_produk[$cabang_id][$dRow->produk_jenis][$dRow->produk_id][$hour_key]= array();
                }

                $sum_by_produk[$cabang_id][$dRow->produk_jenis][$dRow->produk_id][$hour_key] = $newRow;
                $sum_by_produk[$cabang_id][$dRow->produk_jenis][$dRow->produk_id][$hour_key]['valid_qty']          = $tmpSum[$cabang_id][$dRow->produk_jenis][$dRow->produk_id][$hour_key]['valid_qty'];
                $sum_by_produk[$cabang_id][$dRow->produk_jenis][$dRow->produk_id][$hour_key]['produk_ord_jml']     = $tmpSum[$cabang_id][$dRow->produk_jenis][$dRow->produk_id][$hour_key]['produk_ord_jml'];
                $sum_by_produk[$cabang_id][$dRow->produk_jenis][$dRow->produk_id][$hour_key]['produk_sub_total']   = $tmpSum[$cabang_id][$dRow->produk_jenis][$dRow->produk_id][$hour_key]['produk_sub_total'];
                $sum_by_produk[$cabang_id][$dRow->produk_jenis][$dRow->produk_id][$hour_key]['produk_sub_hpp']     = $tmpSum[$cabang_id][$dRow->produk_jenis][$dRow->produk_id][$hour_key]['produk_sub_hpp'];
                $sum_by_produk[$cabang_id][$dRow->produk_jenis][$dRow->produk_id][$hour_key]['produk_sub_laba']    = $tmpSum[$cabang_id][$dRow->produk_jenis][$dRow->produk_id][$hour_key]['produk_sub_laba'];
                $sum_by_produk[$cabang_id][$dRow->produk_jenis][$dRow->produk_id][$hour_key]['ext_intext']         = json_encode($tmpSum[$cabang_id][$dRow->produk_jenis][$dRow->produk_id][$hour_key]['transaksi_id']);
                $sum_by_produk[$cabang_id][$dRow->produk_jenis][$dRow->produk_id][$hour_key]['produk_kode']        = isset($arrProduk_barcode[$dRow->produk_id]) ? $arrProduk_barcode[$dRow->produk_id] : "";
                $sum_by_produk[$cabang_id][$dRow->produk_jenis][$dRow->produk_id][$hour_key]['cabang_id']        = $cabang_id;

            }
        }

//        arrPrint( count($sum_by_produk) );
//        matiHere();

        //============================
        $this->db->where("link_id='0' and fulldate = '".$fulldate."' and jenis IN('982')");
        $this->db->order_by("id", "desc");
        $q = $this->db->get("transaksi_consolidasi");
        $res = $q->result();
        if(!empty($res)){
            $last_query["transaksi"] = $this->db->last_query();
//            $dataResult['transaksi_cancel'] = $res;
            $dataCountResult['transaksi'] = count($res);
            $dataSizeResult['transaksi'] = strlen(serialize($res))+1;
        }
        $daTrId=array();
        if(!empty($res)){
            foreach($res as $k => $dat){
                $daTrId[] = $dat->x_id;
            }
        }
        $prd = $this->db->get("produk");
        $resDataProduk = $prd->result();
        $arrProduk_barcode=array();
        foreach($resDataProduk as $k => $rowD ){
            $arrProduk_barcode[$rowD->id] = $rowD->barcode;
        }
        if(!empty($daTrId)){
            $this->db->where_in("transaksi_id", $daTrId);
            $this->db->like('dtime', "$fulldate");
            $this->db->limit(0);
            $qDt = $this->db->get("transaksi_data_consolidasi");
            $resData = $qDt->result();
            $last_query["transaksi_data"] = $this->db->last_query();
//            $dataResult['transaksi_data_cancel'] = $resData;
            $dataCountResult['transaksi_data'] = count($resData);
            $dataSizeResult['transaksi_data'] = strlen(serialize($resData))+1;
        }
        else{
            $resData = array();
        }
        $sum_by_produk_cancel = array();
        $tmpSum = array();
        if(!empty($resData)){
            foreach($resData as $k => $dRow){
                $newRow = (array)$dRow;
                $hour_key = $dRow->jam;

                if(!isset($tmpSum[$dRow->produk_jenis][$dRow->produk_id][$hour_key]['valid_qty'])){
                    $tmpSum[$dRow->produk_jenis][$dRow->produk_id][$hour_key]['valid_qty'] = 0;
                }
                if(!isset($tmpSum[$dRow->produk_jenis][$dRow->produk_id][$hour_key]['produk_sub_total'])){
                    $tmpSum[$dRow->produk_jenis][$dRow->produk_id][$hour_key]['produk_sub_total'] = 0;
                }

                $tmpSum[$dRow->produk_jenis][$dRow->produk_id][$hour_key]['valid_qty'] += $newRow['valid_qty'];
                $tmpSum[$dRow->produk_jenis][$dRow->produk_id][$hour_key]['produk_sub_total'] += ($newRow['valid_qty']*$newRow['produk_ord_hrg']);
                $tmpSum[$dRow->produk_jenis][$dRow->produk_id][$hour_key]['transaksi_id'][] = $newRow['transaksi_id'];

                if(!isset($sum_by_produk_cancel[$dRow->produk_jenis])){
                    $sum_by_produk_cancel[$dRow->produk_jenis] = array();
                }
                if(!isset($sum_by_produk_cancel[$dRow->produk_jenis][$dRow->produk_id])){
                    $sum_by_produk_cancel[$dRow->produk_jenis][$dRow->produk_id]= array();
                }
                if(!isset($sum_by_produk_cancel[$dRow->produk_jenis][$dRow->produk_id][$hour_key])){
                    $sum_by_produk_cancel[$dRow->produk_jenis][$dRow->produk_id][$hour_key]= array();
                }

                $sum_by_produk_cancel[$dRow->produk_jenis][$dRow->produk_id][$hour_key] = $newRow;
                $sum_by_produk_cancel[$dRow->produk_jenis][$dRow->produk_id][$hour_key]['valid_qty_cancel'] = $tmpSum[$dRow->produk_jenis][$dRow->produk_id][$hour_key]['valid_qty'];
                $sum_by_produk_cancel[$dRow->produk_jenis][$dRow->produk_id][$hour_key]['produk_sub_total_cancel'] = $tmpSum[$dRow->produk_jenis][$dRow->produk_id][$hour_key]['produk_sub_total'];
                $sum_by_produk_cancel[$dRow->produk_jenis][$dRow->produk_id][$hour_key]['ext_intext'] = json_encode($tmpSum[$dRow->produk_jenis][$dRow->produk_id][$hour_key]['transaksi_id']);
                $sum_by_produk_cancel[$dRow->produk_jenis][$dRow->produk_id][$hour_key]['produk_kode'] = isset($arrProduk_barcode[$dRow->produk_id]) ? $arrProduk_barcode[$dRow->produk_id] : "";
            }
        }
        foreach($sum_by_produk as $jn => $dts){
            foreach($dts as $ppid => $pRow){
                $dataResult['transaksi_data_sum'][] = $pRow;
            }
        }

        foreach($sum_by_produk_cancel as $jn => $dts){
            foreach($dts as $ppid => $pRow){
//                $dataResult['transaksi_data_sum_cancel'][] = $pRow;
            }
        }

        $outputData = $dataResult;

        $microEnd = microtime(1);

        $result = array(
            "data" => $outputData,
            "fulldate" => $fulldate,
            "dataCount" => $dataCountResult,
            "dataSize" => $dataSizeResult,
            "microStart" => $microStart,
            "microEnd" => $microEnd,
        );

        return json_encode($result);

    }

    //============== UNTUK CEK CUSTOM TGL==============
    public function allGetRawPosTGL_get(){
        $from = $this->uri->segment(4) != "" ? $this->uri->segment(4) : 1;
        $dateNowCheckStart = date("Y-m-d H:i:s");

        $arrOutput=array();
        //matiHere("MATI DULU.....");
        $arrOutput = json_decode($this->doSyncRawTransaksiTGL($from));
        $ins=0;
        if(!empty($arrOutput)){
            if(isset($arrOutput->data) && count($arrOutput->data)*1> 0 ){
                $this->db->trans_start();
                $batch = array();
                foreach($arrOutput->data as $k => $arrData ){
                    $batch[$k]["dtime"]            = $arrData->dtime;
                    $batch[$k]["produk_id"]        = $arrData->produk_id;
                    $batch[$k]["produk_nama"]      = $arrData->produk_nama;
                    $batch[$k]["valid_qty"]        = $arrData->qty_kredit;
                    $batch[$k]["produk_sub_total"] = $arrData->kredit;
                    $batch[$k]["produk_sub_hpp"]   = $arrData->hpp;
                    $batch[$k]["produk_sub_laba"]  = $arrData->rugilaba;
                    $batch[$k]["produk_ord_hrg"]   = $arrData->harga;
                    $batch[$k]["produk_ord_hpp"]   = $arrData->hpp;
                    $batch[$k]["produk_ord_laba"]  = $arrData->rugilaba;
                    $batch[$k]["cabang_id"]        = $arrData->cabang_id;

                    if($arrData->qty_return*1>0){
                        $batch[$k]["produk_ord_jml_return"] = $arrData->qty_return*1;
                    }
                    else{
                        $batch[$k]["produk_ord_jml_return"] = 0;
                    }

                }
                $this->db->truncate("transaksi_data_sum_realtimepos_copy_cek");
////                //batch insert
                $ins = $this->db->insert_batch("transaksi_data_sum_realtimepos_copy_cek", $batch);
                $this->db->trans_complete();

                sleep(1);
                $this->getDayPostBridgeTGL($from);
            }
        }

//        $result = array(
//            "status"     => $ins,
//            "arrOutput"     => $arrOutput,
//        );
//        $this->response($result, 200);

        if($from-1==0){
            //header("Refresh:3; url=".base_url()."eusvc/DataSync/allGetRawPosTGL/97");
        }
        else{
            $from_f = $from-1;
            //header("Refresh:3; url=".base_url()."eusvc/DataSync/allGetRawPosTGL/$from_f");
            echo "<script>setTimeout( function(){ location.href = '".base_url()."eusvc/DataSync/allGetRawPosTGL/$from_f'; }, 3000)</script>";
            echo "<BR><BR>START: ($dateNowCheckStart)<br>";
            echo "END: (".date("Y-m-d H:i:s").")";
        }

    }

    public function doSyncRawTransaksiTGL($from)
    {

        $microStart = microtime(1);

        $dataResult      = array();
        $dataCountResult = array();
        $dataSizeResult  = array();
        $last_query      = array();

//        $fulldate = date('Y-m-d'); //get today
//        $fulldate = date('2023-12-03'); //get today
        $fulldate = date('Y-m-d', strtotime("-$from day", strtotime(date("Y-m-d")))); //get custom #CODEDS0002

        $this->db->select("produk_id, produk_nama, dtime, fulldate, sum(kredit)as kredit, sum(qty_kredit)as qty_kredit, sum(hpp*qty_kredit) as hpp, harga, sum(rugilaba*qty_kredit)as rugilaba, cabang_id"); //untuk ambil today
        $this->db->where("fulldate", $fulldate);
        $this->db->where("extern2_id", 4010010);
        $this->db->group_by("cabang_id,produk_id");
        $this->db->order_by("produk_id");
        $q = $this->db->get("__raw_rek_pembantu__4");
        $res = $q->result();
        $q = $this->db->last_query();

        $this->db->select("produk_id, produk_nama, dtime, fulldate, sum(debet)as debet, sum(qty_debet)as qty_debet, sum(hpp*qty_debet) as hpp, harga, sum(rugilaba*qty_debet)as rugilaba, cabang_id"); //untuk ambil today
        $this->db->where("fulldate", $fulldate);
        $this->db->where("extern2_id", 4010020);
        $this->db->group_by("cabang_id,produk_id");
        $this->db->order_by("produk_id");
        $q2 = $this->db->get("__raw_rek_pembantu__4");
        $res2 = $q2->result();

        if(!empty($res2)){
            $return = array();
            foreach($res2 as $kt => $rRet){
                $return[$rRet->produk_id] = $rRet;
            }
            $newData=array();
            foreach($res as $k => $rRes){
                $newData[$k] = $rRes;
                if(isset($return[$rRes->produk_id])){
                    $newData[$k]->qty_return = $return[$rRes->produk_id]->qty_debet*1;
                }
            }

            $res = $newData;
        }

        $result = array(
            "data" => $res,
            "query" => $q,
        );

//        echo json_encode($result);
        return json_encode($result);

    }

    public function getDayPostBridgeTGL($from){

//        $fulldate = date("Y-m-d");
//        $fulldate = date("2023-12-03");
        $fulldate = date("Y-m-d", strtotime("-$from day", strtotime(date("Y-m-d"))));
        $query = $this->db->get_where("transaksi_data_sum_realtimepos_copy_cek", array('date(dtime)' => $fulldate));

        if( $query->num_rows()>0 ){
            $result = $query->result();
        }
        else{
            $result = array();
        }

        if(!empty($result)){
            foreach($result as $k => $row){
                $dl = $this->db->get_where('transaksi_data_sum_realtimepos_bridge', array("fulldate" => $fulldate, "cabang_id" => $row->cabang_id, "produk_id" => $row->produk_id));
                $tmpLast = $dl->result();
                if(!empty($tmpLast)){
                    if($tmpLast[0]->produk_ord_jml != $row->valid_qty){
                        //echo $row->produk_nama . " - UPDATE ADA PERUBAHAN (".$tmpLast[0]->produk_ord_jml."):(".$row->valid_qty.")<br>";
                        $rowSum = array(
                            "valid_qty" => $row->valid_qty,
                            "produk_ord_jml" => $row->valid_qty
                        );
                        $upd = $this->db->update("transaksi_data_sum_realtimepos_bridge", $rowSum, array("fulldate" => $fulldate, "cabang_id" => $row->cabang_id, "produk_id" => $row->produk_id));
                    }
                    else{
                        //echo $row->produk_nama . " - TIDAK ADA PERUBAHAN (".$tmpLast[0]->produk_ord_jml."):(".$row->valid_qty.")<br>";
                    }
                }
                else{
                    $row->fulldate = date("Y-m-d", strtotime($row->dtime));
                    $row->produk_ord_jml = $row->valid_qty;
                    unset($row->id);
//                    echo $row->produk_nama . " - INSERT (".$row->produk_ord_jml.") ### =>> ";
                    $ins = $this->db->insert("transaksi_data_sum_realtimepos_bridge", $row);
//                    echo "commit: " . $ins . "<br>";
//                    echo "query: " . $this->db->last_query() . "<br><br><br>";
                }
            }
        }
        else{
            echo "data kosong untuk tgl: " . $fulldate;
        }
//        echo json_encode($result);
    }

    //============== UNTUK CEK CUSTOM TGL==============

    public function checkLogSync(){
        $devID = "lap_realtime_pos";
        $dl = $this->db->get_where('last_sinkron', array('id' => (isset($devID) ? $devID : 1) ), 1, 0);
        $tmpLast = $dl->result();
        $arrLast = isset($tmpLast[0]->blob) && $tmpLast[0]->blob != "" ? blobDecode($tmpLast[0]->blob) : array();
        $lat = $this->db->query("CHECKSUM TABLE transaksi_consolidasi");
        $tmpListTable = $lat->result();
        $stat["transaksi_consolidasi"]["Checksum"] = $tmpListTable[0]->Checksum;
        if(!empty($arrLast)){
            foreach ($stat as $table => $size) {
                if ( !isset($arrLast[$table]) || isset($arrLast[$table]) && $arrLast[$table]['Checksum'] != $size['Checksum']) {
                    $perubahan[$table] = 1;
                }
            }
            $dataReplace = array(
                "id" => isset($devID) ? $devID : 1,
                "blob" => blobEncode($stat),
            );
            $this->db->replace('last_sinkron', $dataReplace);
        }
        else{
            $dataReplace = array(
                "id" => isset($devID) ? $devID : 1,
                "blob" => blobEncode($stat),
            );
            $this->db->replace('last_sinkron', $dataReplace);
        }
        $perubahan = array();
        if (!empty($arrLast)) {
            foreach ($stat as $table => $size) {
                if( !isset($arrLast[$table]) ) {
                    $perubahan[$table] = 1;
                }
                elseif( isset($arrLast[$table]) && $arrLast[$table]['Checksum'] != $size['Checksum'] ){
                    $perubahan[$table] = 1;
                }
                else {
                }
            }
        }
        else {
            $perubahan["transaksi_consolidasi"] = 1;
        }
        return count($perubahan);
    }

    public function allGetTransaksiPos_by_cabang_get(){
        $from = $this->uri->segment(4);
        $arrListKonsolidasi = array(
            "transaksi",
            //    "transaksi_data",
        );
        $suffix = "_realtimepos_bridge_by_cabang";
        $arrOutput=array();
        $arrOutput = json_decode($this->doSyncTransaksiConsolidasi_by_cabang());
        $lap=array();
        $logID = 0;
        if(!empty($arrOutput)){
            if(isset($arrOutput->data) && count($arrOutput->data)*1> 0 ){
                $this->db->trans_start();
                foreach($arrOutput->data as $table => $arrData ){
                    if(!empty($arrData)){
                        $timeMulai = date("Y-m-d H:i:s");
                        $this->db->truncate("$table$suffix");
                        $rowInsert=0;
                        $batch=array();
                        foreach($arrData as $i => $row){
                            $batch[] = $row;
                            $rowInsert += 1;
                        }
                        //batch insert
                        $ins = $this->db->insert_batch("$table$suffix",$batch);
                        $timeEnd = date("Y-m-d H:i:s");
                        $start_date = new DateTime($timeMulai);
                        $since_start = $start_date->diff(new DateTime($timeEnd));
                        $jam = $since_start->h*1>0 ? $since_start->h . " jam, " : "";
                        $menit = $since_start->i*1>0 ? $since_start->i . " menit, " : ($since_start->h*1>0?" 0 menit, ":"");
                        $detik = $since_start->s*1>0 ? $since_start->s . " detik, " : ($since_start->i*1>0?" 0 detik, ":"");
                        $timeDiff = "";
                        $lap["$table$suffix"] = array(
                            "time_mulai" => $timeMulai,
                            "time_end" => $timeEnd,
                            "row_insert" => $rowInsert,
                            "diff" => $jam.$menit.$detik,
                            "timeStart" => strtotime($timeMulai),
                            "timeEnd" => strtotime($timeEnd),
                            "micro_diff" => strtotime($timeEnd)-strtotime($timeMulai),
                        );
                    }
                }
                $this->load->model("Mdls/" . "MdlActivityLog");
                $hTmp = new MdlActivityLog();
                $tmpHData = array(
                    "title"         => "SINKRON TRANSAKSI POS",
                    "sub_title"     => "-",
                    "uid"           => isset($this->session->login['id']) ? $this->session->login['id'] : "-1",
                    "uname"         => isset($this->session->login['nama']) ? $this->session->login['nama'] : "sys",
                    "dtime"         => date("Y-m-d H:i:s"),
                    "transaksi_id"  => "",
                    "deskripsi_old" => "",
                    "deskripsi_new" => base64_encode(serialize($lap)),
                    "jenis"         => "",
                    "ipadd"         => $_SERVER['REMOTE_ADDR'],
                    "devices"       => $_SERVER['HTTP_USER_AGENT'],
                    "category"      => "sinkron_data",
                    "controller"    => "eusvc-KonsolidasiData",
                    "method"        => "allGetTransaksiPos",
                    "url"           => current_url(),
                );
                $logID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
                $this->db->trans_complete();
            }
        }
        $result = array(
            "arrOutput" => count($arrOutput),
            "laporan"   => $lap,
            "logID"     => $logID,
            "isCLI"     => PHP_SAPI != "cli" ? 0 : 1,
        );
        $this->response($result, 200);
    }

    public function doSyncTransaksiConsolidasi_by_cabang()
    {

        $microStart = microtime(1);
        $dataResult      = array();
        $dataCountResult = array();
        $dataSizeResult  = array();
        $last_query      = array();
        $fulldate = date('Y-m-d'); //get today
//        $fulldate = date('Y-m-d', strtotime("-2 day", strtotime(date("Y-m-d")))); //get custom #CODEDS0001
        $last_id = isset($_GET['transaksi']) ? $_GET['transaksi'] : 0;
        $this->db->where("link_id='0' and fulldate = '".$fulldate."' and jenis IN('582')"); //untuk ambil today
        $this->db->order_by("id", "desc");
        $q = $this->db->get("transaksi_consolidasi");
        $res = $q->result();
//        $last_query["transaksi"] = $this->db->last_query();
//        $dataCountResult['transaksi'] = count($res);
//        $dataSizeResult['transaksi'] = strlen(serialize($res))+1;
        $daTrId=array();
        $cabangID=array();
        if(!empty($res)){
            foreach($res as $k => $dat){
                $daTrId[] = $dat->x_id;
                $cabangID[$dat->x_id] = $dat->cabang_id;
                $tokoID[$dat->x_id] = $dat->toko_id;
            }
        }
        if(!empty($daTrId)){
            $this->db->where_in("transaksi_id", $daTrId);
            $this->db->like('dtime', "$fulldate");
            $qDt = $this->db->get("transaksi_data_consolidasi");
            $resData = $qDt->result();
//            $last_query["transaksi_data"] = $this->db->last_query();
//            $dataCountResult['transaksi_data'] = count($resData);
//            $dataSizeResult['transaksi_data'] = strlen(serialize($resData))+1;
        }

        $availColumn = array(
            "cabang_id",
            "toko_id",
            "fulldate",
            "produk_id",
            "produk_nama",
            "valid_qty",
            "produk_ord_jml",
//            "produk_ord_jml_return",
            "produk_ord_hrg",
            "produk_ord_hpp",
            "produk_ord_laba",
            "detail_tipe",
            "dtime",
            "produk_ord_batal",
            "produk_sub_total",
            "produk_sub_hpp",
            "produk_sub_laba",
        );

        $sum_by_produk = array();
        $tmpSum = array();
        if(!empty($resData)){
            foreach($resData as $k => $dRow){
                $newRow=array();
                foreach($availColumn as $keys){
                    $newRow[$keys] = $dRow->$keys;
                }

                //inject cabang
                $newRow['cabang_id'] = $cabangID[$dRow->transaksi_id];
                $newRow['toko_id']   = $tokoID[$dRow->transaksi_id];
                $newRow['fulldate']   = date("Y-m-d", strtotime($dRow->dtime));

                if(!isset($tmpSum[$dRow->produk_jenis][$dRow->produk_id]['valid_qty'])){
                    $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['valid_qty'] = 0;
                }
                if(!isset($tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_ord_jml'])){
                    $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_ord_jml'] = 0;
                }
                if(!isset($tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_total'])){
                    $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_total'] = 0;
                }
                if(!isset($tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_hpp'])){
                    $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_hpp'] = 0;
                }
                if(!isset($tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_laba'])){
                    $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_laba'] = 0;
                }
                $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['valid_qty']         += $newRow['valid_qty'];
                $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_ord_jml']    += $newRow['produk_ord_jml'];
                $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_total']  += ($newRow['valid_qty']*$newRow['produk_ord_hrg']);
                $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_hpp']    += ($newRow['valid_qty']*$newRow['produk_ord_hpp']);
                $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_laba']   += ($newRow['produk_ord_laba']);
                if(!isset($sum_by_produk[$dRow->produk_jenis])){
                    $sum_by_produk[$dRow->produk_jenis] = array();
                }
                if(!isset($sum_by_produk[$dRow->produk_jenis][$dRow->produk_id])){
                    $sum_by_produk[$dRow->produk_jenis][$dRow->produk_id]= array();
                }
                $sum_by_produk[$dRow->produk_jenis][$dRow->produk_id] = $newRow;
                $sum_by_produk[$dRow->produk_jenis][$dRow->produk_id]['valid_qty']          = $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['valid_qty'];
                $sum_by_produk[$dRow->produk_jenis][$dRow->produk_id]['produk_ord_jml']     = $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_ord_jml'];
                $sum_by_produk[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_total']   = $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_total'];
                $sum_by_produk[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_hpp']     = $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_hpp'];
                $sum_by_produk[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_laba']    = $tmpSum[$dRow->produk_jenis][$dRow->produk_id]['produk_sub_laba'];
            }
        }
        foreach($sum_by_produk as $produk_jenis => $dts){
            foreach($dts as $produk_id => $pRow){
                $dataResult['transaksi_data_sum'][] = $pRow;
            }
        }

        $outputData = $dataResult;
        $microEnd = microtime(1);
        $result = array(
            "data" => $outputData,
//            "fulldate" => $fulldate,
//            "dataCount" => $dataCountResult,
//            "dataSize" => $dataSizeResult,
//            "microStart" => $microStart,
//            "microEnd" => $microEnd,
        );
        return json_encode($result);

    }

    public function allGetTransaksiPos_by_kasir_get(){
        $from = $this->uri->segment(4);
        $suffix = "_realtimepos_bridge_by_kasir";
        $arrOutput=array();
        $arrOutput = json_decode($this->doSyncTransaksiConsolidasi_by_kasir());
        $lap=array();
        $logID = 0;
        if(!empty($arrOutput)){
            if(isset($arrOutput->data) && count($arrOutput->data)*1> 0 ){
                $this->db->trans_start();
                foreach($arrOutput->data as $table => $arrData ){
                    if(!empty($arrData)){
                        $timeMulai = date("Y-m-d H:i:s");
                        $this->db->truncate("$table$suffix");
                        $rowInsert=0;
                        $batch=array();
                        foreach($arrData as $i => $row){
                            $batch[] = $row;
                            $rowInsert += 1;
                        }
                        //batch insert
                        $ins = $this->db->insert_batch("$table$suffix",$batch);
                        $timeEnd = date("Y-m-d H:i:s");
                        $start_date = new DateTime($timeMulai);
                        $since_start = $start_date->diff(new DateTime($timeEnd));
                        $jam = $since_start->h*1>0 ? $since_start->h . " jam, " : "";
                        $menit = $since_start->i*1>0 ? $since_start->i . " menit, " : ($since_start->h*1>0?" 0 menit, ":"");
                        $detik = $since_start->s*1>0 ? $since_start->s . " detik, " : ($since_start->i*1>0?" 0 detik, ":"");
                        $timeDiff = "";
                        $lap["$table$suffix"] = array(
                            "time_mulai" => $timeMulai,
                            "time_end" => $timeEnd,
                            "row_insert" => $rowInsert,
                            "diff" => $jam.$menit.$detik,
                            "timeStart" => strtotime($timeMulai),
                            "timeEnd" => strtotime($timeEnd),
                            "micro_diff" => strtotime($timeEnd)-strtotime($timeMulai),
                        );
                    }
                }
                $this->load->model("Mdls/" . "MdlActivityLog");
                $hTmp = new MdlActivityLog();
                $tmpHData = array(
                    "title"         => "SINKRON TRANSAKSI POS",
                    "sub_title"     => "-",
                    "uid"           => isset($this->session->login['id']) ? $this->session->login['id'] : "-1",
                    "uname"         => isset($this->session->login['nama']) ? $this->session->login['nama'] : "sys",
                    "dtime"         => date("Y-m-d H:i:s"),
                    "transaksi_id"  => "",
                    "deskripsi_old" => "",
                    "deskripsi_new" => base64_encode(serialize($lap)),
                    "jenis"         => "",
                    "ipadd"         => $_SERVER['REMOTE_ADDR'],
                    "devices"       => $_SERVER['HTTP_USER_AGENT'],
                    "category"      => "sinkron_data",
                    "controller"    => "eusvc-KonsolidasiData",
                    "method"        => "allGetTransaksiPos",
                    "url"           => current_url(),
                );
                $logID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
                $this->db->trans_complete();
            }
        }
        $result = array(
            "arrOutput" => count($arrOutput),
            "laporan"   => $lap,
            "logID"     => $logID,
            "isCLI"     => PHP_SAPI != "cli" ? 0 : 1,
        );
        $this->response($result, 200);
    }

    public function doSyncTransaksiConsolidasi_by_kasir()
    {

        $microStart = microtime(1);
        $dataResult      = array();
        $dataCountResult = array();
        $dataSizeResult  = array();
        $last_query      = array();
        $fulldate = date('Y-m-d'); //get today
//        $fulldate = date('Y-m-d', strtotime("-2 day", strtotime(date("Y-m-d")))); //get custom #CODEDS0001
        $last_id = isset($_GET['transaksi']) ? $_GET['transaksi'] : 0;
        $this->db->where("link_id='0' and fulldate = '".$fulldate."' and jenis IN('582')"); //untuk ambil today
        $this->db->order_by("id", "desc");
        $q = $this->db->get("transaksi_consolidasi");
        $res = $q->result();
//        $last_query["transaksi"] = $this->db->last_query();
//        $dataCountResult['transaksi'] = count($res);
//        $dataSizeResult['transaksi'] = strlen(serialize($res))+1;
        $daTrId=array();
        $cabangID=array();
        $kasirID=array();
        $tokoID=array();
        if(!empty($res)){
            foreach($res as $k => $dat){
                $daTrId[] = $dat->x_id;
                $cabangID[$dat->x_id] = $dat->cabang_id;
                $tokoID[$dat->x_id] = $dat->toko_id;
                $kasirID[$dat->x_id] = array(
                    "id" => $dat->oleh_id,
                    "nama" => $dat->oleh_nama,
                );

            }
        }
        if(!empty($daTrId)){
            $this->db->where_in("transaksi_id", $daTrId);
            $this->db->like('dtime', "$fulldate");
            $qDt = $this->db->get("transaksi_data_consolidasi");
            $resData = $qDt->result();
//            $last_query["transaksi_data"] = $this->db->last_query();
//            $dataCountResult['transaksi_data'] = count($resData);
//            $dataSizeResult['transaksi_data'] = strlen(serialize($resData))+1;
        }

        $availColumn = array(
            "oleh_id",
            "oleh_nama",
            "cabang_id",
            "toko_id",
            "fulldate",
            "produk_id",
            "produk_nama",
            "valid_qty",
            "produk_ord_jml",
            "produk_ord_hrg",
            "produk_ord_hpp",
            "produk_ord_laba",
            "detail_tipe",
            "dtime",
            "produk_ord_batal",
            "produk_sub_total",
            "produk_sub_hpp",
            "produk_sub_laba",
        );
        $sum_by_produk = array();
        $tmpSum = array();
        if(!empty($resData)){
            foreach($resData as $k => $dRow){
                $newRow=array();
                foreach($availColumn as $keys){
                    $newRow[$keys] = $dRow->$keys;
                }

                //inject cabang
                $newRow['cabang_id']    = $cabangID[$dRow->transaksi_id];
                $newRow['oleh_id']     = $kasirID[$dRow->transaksi_id]['id'];
                $newRow['oleh_nama']   = $kasirID[$dRow->transaksi_id]['nama'];
                $newRow['toko_id']   = $tokoID[$dRow->transaksi_id];
                $newRow['fulldate']   = date("Y-m-d", strtotime($dRow->dtime));

                // [$newRow['oleh_id']]

                if(!isset($tmpSum[$dRow->produk_jenis][$newRow['oleh_id']][$dRow->produk_id]['valid_qty'])){
                    $tmpSum[$dRow->produk_jenis][$newRow['oleh_id']][$dRow->produk_id]['valid_qty'] = 0;
                }
                if(!isset($tmpSum[$dRow->produk_jenis][$newRow['oleh_id']][$dRow->produk_id]['produk_ord_jml'])){
                    $tmpSum[$dRow->produk_jenis][$newRow['oleh_id']][$dRow->produk_id]['produk_ord_jml'] = 0;
                }
                if(!isset($tmpSum[$dRow->produk_jenis][$newRow['oleh_id']][$dRow->produk_id]['produk_sub_total'])){
                    $tmpSum[$dRow->produk_jenis][$newRow['oleh_id']][$dRow->produk_id]['produk_sub_total'] = 0;
                }
                if(!isset($tmpSum[$dRow->produk_jenis][$newRow['oleh_id']][$dRow->produk_id]['produk_sub_hpp'])){
                    $tmpSum[$dRow->produk_jenis][$newRow['oleh_id']][$dRow->produk_id]['produk_sub_hpp'] = 0;
                }
                if(!isset($tmpSum[$dRow->produk_jenis][$newRow['oleh_id']][$dRow->produk_id]['produk_sub_laba'])){
                    $tmpSum[$dRow->produk_jenis][$newRow['oleh_id']][$dRow->produk_id]['produk_sub_laba'] = 0;
                }

                $tmpSum[$dRow->produk_jenis][$newRow['oleh_id']][$dRow->produk_id]['valid_qty']         += $newRow['valid_qty'];
                $tmpSum[$dRow->produk_jenis][$newRow['oleh_id']][$dRow->produk_id]['produk_ord_jml']    += $newRow['produk_ord_jml'];
                $tmpSum[$dRow->produk_jenis][$newRow['oleh_id']][$dRow->produk_id]['produk_sub_total']  += ($newRow['valid_qty']*$newRow['produk_ord_hrg']);
                $tmpSum[$dRow->produk_jenis][$newRow['oleh_id']][$dRow->produk_id]['produk_sub_hpp']    += ($newRow['valid_qty']*$newRow['produk_ord_hpp']);
                $tmpSum[$dRow->produk_jenis][$newRow['oleh_id']][$dRow->produk_id]['produk_sub_laba']   += ($newRow['produk_ord_laba']);

                if(!isset($sum_by_produk[$dRow->produk_jenis])){
                    $sum_by_produk[$dRow->produk_jenis] = array();
                }
                if(!isset($sum_by_produk[$dRow->produk_jenis][$newRow['oleh_id']])){
                    $sum_by_produk[$dRow->produk_jenis][$newRow['oleh_id']] = array();
                }
                if(!isset($sum_by_produk[$dRow->produk_jenis][$newRow['oleh_id']][$dRow->produk_id])){
                    $sum_by_produk[$dRow->produk_jenis][$newRow['oleh_id']][$dRow->produk_id]= array();
                }

                $sum_by_produk[$dRow->produk_jenis][$newRow['oleh_id']][$dRow->produk_id] = $newRow;
                $sum_by_produk[$dRow->produk_jenis][$newRow['oleh_id']][$dRow->produk_id]['valid_qty']          = $tmpSum[$dRow->produk_jenis][$newRow['oleh_id']][$dRow->produk_id]['valid_qty'];
                $sum_by_produk[$dRow->produk_jenis][$newRow['oleh_id']][$dRow->produk_id]['produk_ord_jml']     = $tmpSum[$dRow->produk_jenis][$newRow['oleh_id']][$dRow->produk_id]['produk_ord_jml'];
                $sum_by_produk[$dRow->produk_jenis][$newRow['oleh_id']][$dRow->produk_id]['produk_sub_total']   = $tmpSum[$dRow->produk_jenis][$newRow['oleh_id']][$dRow->produk_id]['produk_sub_total'];
                $sum_by_produk[$dRow->produk_jenis][$newRow['oleh_id']][$dRow->produk_id]['produk_sub_hpp']     = $tmpSum[$dRow->produk_jenis][$newRow['oleh_id']][$dRow->produk_id]['produk_sub_hpp'];
                $sum_by_produk[$dRow->produk_jenis][$newRow['oleh_id']][$dRow->produk_id]['produk_sub_laba']    = $tmpSum[$dRow->produk_jenis][$newRow['oleh_id']][$dRow->produk_id]['produk_sub_laba'];
            }
        }
        foreach($sum_by_produk as $produk_jenis => $dts){
            foreach($dts as $oleh_id => $oRow){
                foreach($oRow as $produk_id => $pRow){
                    $dataResult['transaksi_data_sum'][] = $pRow;
                }
            }
        }

        $outputData = $dataResult;
        $microEnd = microtime(1);
        $result = array(
            "data" => $outputData,
//            "fulldate" => $fulldate,
//            "dataCount" => $dataCountResult,
//            "dataSize" => $dataSizeResult,
//            "microStart" => $microStart,
//            "microEnd" => $microEnd,
        );
        return json_encode($result);

    }

    public function allGetTransaksiPos_by_machine_id_get(){
        $from = $this->uri->segment(4);
        $suffix = "_realtimepos_bridge_by_machine_id";
        $arrOutput=array();
        $arrOutput = json_decode($this->doSyncTransaksiConsolidasi_by_machine_id());
        $lap=array();
        $logID = 0;
        if(!empty($arrOutput)){
            if(isset($arrOutput->data) && count($arrOutput->data)*1> 0 ){
                $this->db->trans_start();
                foreach($arrOutput->data as $table => $arrData ){
                    if(!empty($arrData)){
                        $timeMulai = date("Y-m-d H:i:s");
                        $this->db->truncate("$table$suffix");
                        $rowInsert=0;
                        $batch=array();
                        foreach($arrData as $i => $row){
                            $batch[] = $row;
                            $rowInsert += 1;
                        }
                        //batch insert
                        $ins = $this->db->insert_batch("$table$suffix",$batch);
                        $timeEnd = date("Y-m-d H:i:s");
                        $start_date = new DateTime($timeMulai);
                        $since_start = $start_date->diff(new DateTime($timeEnd));
                        $jam = $since_start->h*1>0 ? $since_start->h . " jam, " : "";
                        $menit = $since_start->i*1>0 ? $since_start->i . " menit, " : ($since_start->h*1>0?" 0 menit, ":"");
                        $detik = $since_start->s*1>0 ? $since_start->s . " detik, " : ($since_start->i*1>0?" 0 detik, ":"");
                        $timeDiff = "";
                        $lap["$table$suffix"] = array(
                            "time_mulai" => $timeMulai,
                            "time_end" => $timeEnd,
                            "row_insert" => $rowInsert,
                            "diff" => $jam.$menit.$detik,
                            "timeStart" => strtotime($timeMulai),
                            "timeEnd" => strtotime($timeEnd),
                            "micro_diff" => strtotime($timeEnd)-strtotime($timeMulai),
                        );
                    }
                }
                $this->load->model("Mdls/" . "MdlActivityLog");
                $hTmp = new MdlActivityLog();
                $tmpHData = array(
                    "title"         => "SINKRON TRANSAKSI POS",
                    "sub_title"     => "-",
                    "uid"           => isset($this->session->login['id']) ? $this->session->login['id'] : "-1",
                    "uname"         => isset($this->session->login['nama']) ? $this->session->login['nama'] : "sys",
                    "dtime"         => date("Y-m-d H:i:s"),
                    "transaksi_id"  => "",
                    "deskripsi_old" => "",
                    "deskripsi_new" => base64_encode(serialize($lap)),
                    "jenis"         => "",
                    "ipadd"         => $_SERVER['REMOTE_ADDR'],
                    "devices"       => $_SERVER['HTTP_USER_AGENT'],
                    "category"      => "sinkron_data",
                    "controller"    => "eusvc-KonsolidasiData",
                    "method"        => "allGetTransaksiPos",
                    "url"           => current_url(),
                );
                $logID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
                $this->db->trans_complete();
            }
        }
        $result = array(
            "arrOutput" => count($arrOutput),
            "laporan"   => $lap,
            "logID"     => $logID,
            "isCLI"     => PHP_SAPI != "cli" ? 0 : 1,
        );
        $this->response($result, 200);
    }

    public function doSyncTransaksiConsolidasi_by_machine_id()
    {

        $microStart = microtime(1);
        $dataResult      = array();
        $dataCountResult = array();
        $dataSizeResult  = array();
        $last_query      = array();
        $fulldate = date('Y-m-d'); //get today
//        $fulldate = date('Y-m-d', strtotime("-2 day", strtotime(date("Y-m-d")))); //get custom #CODEDS0001
        $last_id = isset($_GET['transaksi']) ? $_GET['transaksi'] : 0;
        $this->db->where("link_id='0' and fulldate = '".$fulldate."' and jenis IN('582')"); //untuk ambil today
        $this->db->order_by("id", "desc");
        $q = $this->db->get("transaksi_consolidasi");
        $res = $q->result();
//        $last_query["transaksi"] = $this->db->last_query();
//        $dataCountResult['transaksi'] = count($res);
//        $dataSizeResult['transaksi'] = strlen(serialize($res))+1;
        $daTrId=array();
        $cabangID=array();
        $kasirID=array();
        $tokoID=array();
        $machineID=array();
        if(!empty($res)){
            foreach($res as $k => $dat){
                $daTrId[] = $dat->x_id;
                $cabangID[$dat->x_id] = $dat->cabang_id;
                $tokoID[$dat->x_id] = $dat->toko_id;
                $machineID[$dat->x_id] = $dat->machine_id;
                $kasirID[$dat->x_id] = array(
                    "id" => $dat->oleh_id,
                    "nama" => $dat->oleh_nama,
                );
            }
        }
        if(!empty($daTrId)){
            $this->db->where_in("transaksi_id", $daTrId);
            $this->db->like('dtime', "$fulldate");
            $qDt = $this->db->get("transaksi_data_consolidasi");
            $resData = $qDt->result();
//            $last_query["transaksi_data"] = $this->db->last_query();
//            $dataCountResult['transaksi_data'] = count($resData);
//            $dataSizeResult['transaksi_data'] = strlen(serialize($resData))+1;
        }

        $availColumn = array(
//            "oleh_id",
//            "oleh_nama",
            "machine_id",
            "cabang_id",
            "toko_id",
            "fulldate",
            "produk_id",
            "produk_nama",
            "valid_qty",
            "produk_ord_jml",
            "produk_ord_hrg",
            "produk_ord_hpp",
            "produk_ord_laba",
            "detail_tipe",
            "dtime",
            "produk_ord_batal",
            "produk_sub_total",
            "produk_sub_hpp",
            "produk_sub_laba",
        );
        $sum_by_produk = array();
        $tmpSum = array();
        if(!empty($resData)){
            foreach($resData as $k => $dRow){
                $newRow=array();
                foreach($availColumn as $keys){
                    $newRow[$keys] = $dRow->$keys;
                }

                //inject cabang
                $newRow['cabang_id']    = $cabangID[$dRow->transaksi_id];
                $newRow['machine_id']    = $machineID[$dRow->transaksi_id];
//                $newRow['oleh_id']     = $kasirID[$dRow->transaksi_id]['id'];
//                $newRow['oleh_nama']   = $kasirID[$dRow->transaksi_id]['nama'];
                $newRow['toko_id']   = $tokoID[$dRow->transaksi_id];
                $newRow['fulldate']   = date("Y-m-d", strtotime($dRow->dtime));

                // [$newRow['oleh_id']]

                if(!isset($tmpSum[$dRow->produk_jenis][$newRow['machine_id']][$dRow->produk_id]['valid_qty'])){
                    $tmpSum[$dRow->produk_jenis][$newRow['machine_id']][$dRow->produk_id]['valid_qty'] = 0;
                }
                if(!isset($tmpSum[$dRow->produk_jenis][$newRow['machine_id']][$dRow->produk_id]['produk_ord_jml'])){
                    $tmpSum[$dRow->produk_jenis][$newRow['machine_id']][$dRow->produk_id]['produk_ord_jml'] = 0;
                }
                if(!isset($tmpSum[$dRow->produk_jenis][$newRow['machine_id']][$dRow->produk_id]['produk_sub_total'])){
                    $tmpSum[$dRow->produk_jenis][$newRow['machine_id']][$dRow->produk_id]['produk_sub_total'] = 0;
                }
                if(!isset($tmpSum[$dRow->produk_jenis][$newRow['machine_id']][$dRow->produk_id]['produk_sub_hpp'])){
                    $tmpSum[$dRow->produk_jenis][$newRow['machine_id']][$dRow->produk_id]['produk_sub_hpp'] = 0;
                }
                if(!isset($tmpSum[$dRow->produk_jenis][$newRow['machine_id']][$dRow->produk_id]['produk_sub_laba'])){
                    $tmpSum[$dRow->produk_jenis][$newRow['machine_id']][$dRow->produk_id]['produk_sub_laba'] = 0;
                }

                $tmpSum[$dRow->produk_jenis][$newRow['machine_id']][$dRow->produk_id]['valid_qty']         += $newRow['valid_qty'];
                $tmpSum[$dRow->produk_jenis][$newRow['machine_id']][$dRow->produk_id]['produk_ord_jml']    += $newRow['produk_ord_jml'];
                $tmpSum[$dRow->produk_jenis][$newRow['machine_id']][$dRow->produk_id]['produk_sub_total']  += ($newRow['valid_qty']*$newRow['produk_ord_hrg']);
                $tmpSum[$dRow->produk_jenis][$newRow['machine_id']][$dRow->produk_id]['produk_sub_hpp']    += ($newRow['valid_qty']*$newRow['produk_ord_hpp']);
                $tmpSum[$dRow->produk_jenis][$newRow['machine_id']][$dRow->produk_id]['produk_sub_laba']   += ($newRow['produk_ord_laba']);

                if(!isset($sum_by_produk[$dRow->produk_jenis])){
                    $sum_by_produk[$dRow->produk_jenis] = array();
                }
                if(!isset($sum_by_produk[$dRow->produk_jenis][$newRow['machine_id']])){
                    $sum_by_produk[$dRow->produk_jenis][$newRow['machine_id']] = array();
                }
                if(!isset($sum_by_produk[$dRow->produk_jenis][$newRow['machine_id']][$dRow->produk_id])){
                    $sum_by_produk[$dRow->produk_jenis][$newRow['machine_id']][$dRow->produk_id]= array();
                }

                $sum_by_produk[$dRow->produk_jenis][$newRow['machine_id']][$dRow->produk_id] = $newRow;
                $sum_by_produk[$dRow->produk_jenis][$newRow['machine_id']][$dRow->produk_id]['valid_qty']          = $tmpSum[$dRow->produk_jenis][$newRow['machine_id']][$dRow->produk_id]['valid_qty'];
                $sum_by_produk[$dRow->produk_jenis][$newRow['machine_id']][$dRow->produk_id]['produk_ord_jml']     = $tmpSum[$dRow->produk_jenis][$newRow['machine_id']][$dRow->produk_id]['produk_ord_jml'];
                $sum_by_produk[$dRow->produk_jenis][$newRow['machine_id']][$dRow->produk_id]['produk_sub_total']   = $tmpSum[$dRow->produk_jenis][$newRow['machine_id']][$dRow->produk_id]['produk_sub_total'];
                $sum_by_produk[$dRow->produk_jenis][$newRow['machine_id']][$dRow->produk_id]['produk_sub_hpp']     = $tmpSum[$dRow->produk_jenis][$newRow['machine_id']][$dRow->produk_id]['produk_sub_hpp'];
                $sum_by_produk[$dRow->produk_jenis][$newRow['machine_id']][$dRow->produk_id]['produk_sub_laba']    = $tmpSum[$dRow->produk_jenis][$newRow['machine_id']][$dRow->produk_id]['produk_sub_laba'];
            }
        }
        foreach($sum_by_produk as $produk_jenis => $dts){
            foreach($dts as $oleh_id => $oRow){
                foreach($oRow as $produk_id => $pRow){
                    $dataResult['transaksi_data_sum'][] = $pRow;
                }
            }
        }

        $outputData = $dataResult;
        $microEnd = microtime(1);
        $result = array(
            "data" => $outputData,
//            "fulldate" => $fulldate,
//            "dataCount" => $dataCountResult,
//            "dataSize" => $dataSizeResult,
//            "microStart" => $microStart,
//            "microEnd" => $microEnd,
        );
        return json_encode($result);

    }

    //allGetRawPosReturn_get akan di matikan di CRON JOB di GANTI createBridge (mode rekening) by chepy 2024-04-23
    public function allGetRawPosReturn_get()
    {
        $from = $this->uri->segment(4);

        $arrOutput=array();
        $arrOutput = json_decode($this->doSyncRawTransaksiReturn());
        $ins=0;
        if(!empty($arrOutput)){
            if(isset($arrOutput->data) && count($arrOutput->data)*1> 0 ){
                $this->db->trans_start();
                $batch = array();
                foreach($arrOutput->data as $k => $arrData ){
                    $batch[$k]["dtime"]            = $arrData->dtime;
                    $batch[$k]["produk_id"]        = $arrData->produk_id;
                    $batch[$k]["produk_nama"]      = $arrData->produk_nama;
                    $batch[$k]["valid_qty"]        = $arrData->qty_debet;
                    $batch[$k]["produk_sub_total"] = $arrData->kredit;
                    $batch[$k]["produk_sub_hpp"]   = $arrData->hpp;
                    $batch[$k]["produk_sub_laba"]  = $arrData->rugilaba;
                    $batch[$k]["produk_ord_hrg"]   = $arrData->harga;
                    $batch[$k]["produk_ord_hpp"]   = $arrData->hpp;
                    $batch[$k]["produk_ord_laba"]  = $arrData->rugilaba;
                    $batch[$k]["cabang_id"]        = $arrData->cabang_id;
                }


                $this->db->truncate("transaksi_data_sum_realtimepos_return");
////                //batch insert
                $ins = $this->db->insert_batch("transaksi_data_sum_realtimepos_return", $batch);
                $this->db->trans_complete();
            }
        }

        $result = array(
            "status"     => $ins,
            "arrOutput"     => $arrOutput,
        );

        $this->response($result, 200);
    }
    public function doSyncRawTransaksiReturn()
    {

        $microStart = microtime(1);

        $dataResult      = array();
        $dataCountResult = array();
        $dataSizeResult  = array();
        $last_query      = array();

        $fulldate = date('Y-m-d'); //get today
//        $fulldate = date('2023-10-23'); //get today
//        $fulldate = date('Y-m-d', strtotime("-1 day", strtotime(date("Y-m-d")))); //get custom #CODEDS0002

        $this->db->select("id, produk_id, produk_nama, dtime, fulldate, sum(debet)as kredit, sum(qty_debet)as qty_debet, sum(hpp*qty_debet) as hpp, harga, sum(rugilaba*qty_debet)as rugilaba, cabang_id"); //untuk ambil today
//        $this->db->where("fulldate", $fulldate);
        $this->db->where("qty_debet > ", 0);
        $this->db->where("extern2_id", 4010020);
        $this->db->group_by("cabang_id,produk_id");
        $this->db->order_by("produk_id");
        $q = $this->db->get("__raw_rek_pembantu__4");
        $res = $q->result();
        $q = $this->db->last_query();

        $result = array(
            "data" => $res,
            "query" => $q,
        );

//        echo json_encode($result);
        return json_encode($result);

    }

    //getDayPostBridgeReturn_get akan di matikan di CRON JOB di GANTI createBridge (mode rekening) by chepy 2024-04-23
    public function getDayPostBridgeReturn_get()
    {

        $fulldate = date("Y-m-d");
//        $fulldate = date("2023-10-23");
//        $fulldate = date("Y-m-d", strtotime("-1 day", strtotime(date("Y-m-d"))));
        $query = $this->db->get_where("transaksi_data_sum_realtimepos_return", array('date(dtime)' => $fulldate));

        if( $query->num_rows()>0 ){
            $result = $query->result();
        }
        else{
            $result = array();
        }

        if(!empty($result)){
            foreach($result as $k => $row){
                $dl = $this->db->get_where('transaksi_data_sum_realtimepos_bridge_return', array("fulldate" => $fulldate, "cabang_id" => $row->cabang_id, "produk_id" => $row->produk_id));
                $tmpLast = $dl->result();
                if(!empty($tmpLast)){
                    if($tmpLast[0]->produk_ord_jml != $row->valid_qty){
                        //echo $row->produk_nama . " - UPDATE ADA PERUBAHAN (".$tmpLast[0]->produk_ord_jml."):(".$row->valid_qty.")<br>";
                        $rowSum = array(
                            "valid_qty" => $row->valid_qty,
                            "produk_ord_jml" => $row->valid_qty
                        );
                        $upd = $this->db->update("transaksi_data_sum_realtimepos_bridge_return", $rowSum, array("fulldate" => $fulldate, "cabang_id" => $row->cabang_id, "produk_id" => $row->produk_id));
                    }
                    else{
                        //echo $row->produk_nama . " - TIDAK ADA PERUBAHAN (".$tmpLast[0]->produk_ord_jml."):(".$row->valid_qty.")<br>";
                    }
                }
                else{
                    $row->fulldate = date("Y-m-d", strtotime($row->dtime));
                    $row->produk_ord_jml = $row->valid_qty;
                    unset($row->id);
                    $ins = $this->db->insert("transaksi_data_sum_realtimepos_bridge_return", $row);
                }
            }
        }
        else{
            echo "data kosong untuk tgl: " . $fulldate;
        }
//        echo json_encode($result);
    }

    //new bridge (mode rekening)
    public function rawCheckBridgePenjualan()
    {
//        $this->db->select("id, produk_id, produk_nama, dtime, fulldate, sum(kredit)as kredit, sum(qty_kredit)as qty_kredit, sum(hpp*qty_kredit) as hpp, harga, sum(rugilaba*qty_kredit)as rugilaba, cabang_id"); //untuk ambil today

        $this->db->select("id, produk_id, produk_nama, dtime, fulldate, kredit, qty_kredit, debet, qty_debet, hpp, rugilaba, harga, cabang_id"); //untuk ambil today
        $this->db->where("qty_debet", 0);
        $this->db->where("extern2_id", 4010010); //COA penjualan
//        $this->db->group_by("cabang_id, produk_id");
//        $this->db->order_by("produk_id");
        $this->db->limit(2700);
        $q = $this->db->get("__raw_rek_pembantu__4");
        $res = $q->result();
        $q = $this->db->last_query();

        $result=array();
        $preresult = array();
        $numP = 0;
        if(!empty($res)){
            foreach ($res as $row) {
                $numP++;
                if(!isset($result[$row->fulldate][$row->cabang_id][$row->produk_id])){
//                    $result[$row->fulldate][$row->cabang_id][$row->produk_id] = (array)$row;
//                    $result[$row->fulldate][$row->cabang_id][$row->produk_id]["kredit"] = 0;
//                    $result[$row->fulldate][$row->cabang_id][$row->produk_id]["qty_kredit"] = 0;
                    $preresult[$row->fulldate][$row->cabang_id][$row->produk_id]["cek_hpp"] = 0;


                    $result[$row->fulldate][$row->cabang_id][$row->produk_id]["valid_qty"] = 0;
                    $result[$row->fulldate][$row->cabang_id][$row->produk_id]["produk_sub_total"] = 0;
                    $result[$row->fulldate][$row->cabang_id][$row->produk_id]["produk_sub_hpp"] = 0;
                    $result[$row->fulldate][$row->cabang_id][$row->produk_id]["produk_sub_laba"] = 0;
                    $result[$row->fulldate][$row->cabang_id][$row->produk_id]["produk_ord_hrg"] = 0;
                    $result[$row->fulldate][$row->cabang_id][$row->produk_id]["produk_ord_hpp"] = 0;
                    $result[$row->fulldate][$row->cabang_id][$row->produk_id]["produk_ord_laba"] = 0;
                }


                $preresult[$row->fulldate][$row->cabang_id][$row->produk_id]["cek_row"][] = array();
                $total_row = count($preresult[$row->fulldate][$row->cabang_id][$row->produk_id]["cek_row"]);
                $preresult[$row->fulldate][$row->cabang_id][$row->produk_id]["cek_hpp"] += $row->hpp*1;

                //===============
                $result[$row->fulldate][$row->cabang_id][$row->produk_id]["dtime"] = $row->dtime;
                $result[$row->fulldate][$row->cabang_id][$row->produk_id]["produk_id"] = $row->produk_id;
                $result[$row->fulldate][$row->cabang_id][$row->produk_id]["cabang_id"] = $row->cabang_id;
                $result[$row->fulldate][$row->cabang_id][$row->produk_id]["produk_nama"] = $row->produk_nama;
                $result[$row->fulldate][$row->cabang_id][$row->produk_id]["valid_qty"] += $row->qty_kredit*1;
                $result[$row->fulldate][$row->cabang_id][$row->produk_id]["produk_ord_hrg"] = $row->harga;
                $result[$row->fulldate][$row->cabang_id][$row->produk_id]["produk_ord_hpp"] = $preresult[$row->fulldate][$row->cabang_id][$row->produk_id]["cek_hpp"] / $total_row;
                $result[$row->fulldate][$row->cabang_id][$row->produk_id]["produk_ord_laba"] += $row->rugilaba * 1;

                $rowSum = array(
                    "qty_debet" => $row->qty_kredit*1 > 0 ? $row->qty_kredit*1 : 1,
                );

                echo "$numP :::RAW PENJUALAN::: => ";
                $this->db->update("__raw_rek_pembantu__4", $rowSum, array("id" => $row->id ));
                echo $this->db->last_query() . "<br>";

            }
        }
        return $result;

    }
    public function rawCheckBridgeReturn()
    {
        $this->db->select("id, produk_id, produk_nama, dtime, fulldate, kredit, qty_kredit, debet, qty_debet, hpp, rugilaba, harga, cabang_id"); //untuk ambil today
        $this->db->where("qty_debet >", 0);
        $this->db->where("extern2_id", 4010020); //COA penjualan
//        $this->db->group_by("cabang_id, produk_id");
        $this->db->order_by("produk_id");
        $this->db->limit(2500);
        $q = $this->db->get("__raw_rek_pembantu__4");
        $res = $q->result();
        $q = $this->db->last_query();

        $preresult = array();
        $result=array();
        $numR = 0;
        if(!empty($res)){
            foreach($res as $row){

                $numR++;

                if(!isset($result[$row->fulldate][$row->cabang_id][$row->produk_id])){
//                    $result[$row->fulldate][$row->cabang_id][$row->produk_id] = (array)$row;
//                    $result[$row->fulldate][$row->cabang_id][$row->produk_id]["kredit"] = 0;
//                    $result[$row->fulldate][$row->cabang_id][$row->produk_id]["qty_kredit"] = 0;
                    $preresult[$row->fulldate][$row->cabang_id][$row->produk_id]["cek_hpp"] = 0;

                    $result[$row->fulldate][$row->cabang_id][$row->produk_id]["valid_qty"] = 0;
                    $result[$row->fulldate][$row->cabang_id][$row->produk_id]["produk_sub_total"] = 0;
                    $result[$row->fulldate][$row->cabang_id][$row->produk_id]["produk_sub_hpp"] = 0;
                    $result[$row->fulldate][$row->cabang_id][$row->produk_id]["produk_sub_laba"] = 0;
                    $result[$row->fulldate][$row->cabang_id][$row->produk_id]["produk_ord_hrg"] = 0;
                    $result[$row->fulldate][$row->cabang_id][$row->produk_id]["produk_ord_hpp"] = 0;
                    $result[$row->fulldate][$row->cabang_id][$row->produk_id]["produk_ord_laba"] = 0;
                }


                $preresult[$row->fulldate][$row->cabang_id][$row->produk_id]["cek_row"][] = array();
                $total_row = count($preresult[$row->fulldate][$row->cabang_id][$row->produk_id]["cek_row"]);
                $preresult[$row->fulldate][$row->cabang_id][$row->produk_id]["cek_hpp"] += $row->hpp*1;

                //===============
                $result[$row->fulldate][$row->cabang_id][$row->produk_id]["dtime"] = $row->dtime;
                $result[$row->fulldate][$row->cabang_id][$row->produk_id]["produk_id"] = $row->produk_id;
                $result[$row->fulldate][$row->cabang_id][$row->produk_id]["cabang_id"] = $row->cabang_id;
                $result[$row->fulldate][$row->cabang_id][$row->produk_id]["produk_nama"] = $row->produk_nama;
                $result[$row->fulldate][$row->cabang_id][$row->produk_id]["valid_qty"] += $row->qty_debet*1;
                $result[$row->fulldate][$row->cabang_id][$row->produk_id]["produk_ord_hrg"] = $row->harga;
                $result[$row->fulldate][$row->cabang_id][$row->produk_id]["produk_ord_hpp"] = $preresult[$row->fulldate][$row->cabang_id][$row->produk_id]["cek_hpp"] / $total_row;
                $result[$row->fulldate][$row->cabang_id][$row->produk_id]["produk_ord_laba"] += $row->rugilaba * 1;

                $rowSum = array(
                    "qty_kredit" => $row->qty_debet*1,
                    "qty_debet" => 0,
                );
                echo "$numR :::RAW RETURN::: => ";
                $this->db->update("__raw_rek_pembantu__4", $rowSum, array("id" => $row->id ));
                echo $this->db->last_query() . "<br>";
            }
        }
        return $result;
    }
    public function rawCheckBridge_get(){

        $this->db->trans_start();

        $result = array(
            "penjualan" => $this->rawCheckBridgePenjualan(),
//            "return" => $this->rawCheckBridgeReturn(),
        );

        echo json_encode( $result );
    }
    public function createBridge_get(){

        $dateStart = date("Y-m-d H:i:s");

        $this->db->trans_start();

        $numP = 0;
        //cek penjualan dulu
        $rowPenjualan = $this->rawCheckBridgePenjualan();
        if( count($rowPenjualan) ){
            foreach($rowPenjualan as $fulldate => $rData){
                foreach($rData as $cabang_id => $sData){
                    foreach($sData as $produk_id => $rowData){
                        $numP++;
                        echo "$numP :::CREATE PENJUALAN::: => ";
                        $dl = $this->db->get_where('transaksi_data_sum_realtimepos_bridge', array("fulldate" => $fulldate, "cabang_id" => $cabang_id, "produk_id" => $produk_id));
                        $tmpLast = $dl->result();
                        if (!empty($tmpLast)) {
                            $rowSum = array(
                                "valid_qty" => $tmpLast[0]->valid_qty + $rowData['valid_qty'],
                                "produk_ord_jml" => $tmpLast[0]->valid_qty + $rowData['valid_qty']
                            );
                            $upd = $this->db->update("transaksi_data_sum_realtimepos_bridge", $rowSum, array("fulldate" => $fulldate, "cabang_id" => $cabang_id, "produk_id" => $produk_id));
                            echo $this->db->last_query() . "<br>";
                        }
                        else {
                            $rowData['fulldate'] = date("Y-m-d", strtotime($rowData['dtime']));
                            $rowData['produk_ord_jml'] = $rowData['valid_qty'];
                            unset($rowData['id']);
                            $ins = $this->db->insert("transaksi_data_sum_realtimepos_bridge", $rowData);
                            echo $this->db->last_query() . "<br>";
                        }
                    }
                }
            }
        }

        echo "=======================================<br>";
        echo "============ LAP PENJUALAN ============<br>";
        echo "=======================================<br>";
        echo "== TOTAL ROW = ".count($rowPenjualan)." ==<br>";
        echo "== TOTAL PRODUK ROW = $numP ==<br>";
        echo "=======================================<br>";
        echo "=======================================<br><br>";

        $numR = 0;
        //cek return
        $rowReturnPenjualan = $this->rawCheckBridgeReturn();
        if( count($rowReturnPenjualan) ){
            foreach($rowReturnPenjualan as $fulldate => $rData){
                foreach($rData as $cabang_id => $sData){
                    foreach($sData as $produk_id => $rowData){
                        $numR++;
                        echo "$numR :::RETURN::: => ";
                        $dl = $this->db->get_where('transaksi_data_sum_realtimepos_bridge_return', array("fulldate" => $fulldate, "cabang_id" => $cabang_id, "produk_id" => $produk_id));
                        $tmpLast = $dl->result();
                        if(!empty($tmpLast)){
                            $rowSum = array(
                                "valid_qty" => $tmpLast[0]->valid_qty + $rowData['valid_qty'],
                                "produk_ord_jml" => $tmpLast[0]->valid_qty + $rowData['valid_qty']
                            );
                            $upd = $this->db->update("transaksi_data_sum_realtimepos_bridge_return", $rowSum, array("fulldate" => $fulldate, "cabang_id" => $cabang_id, "produk_id" => $produk_id));
                            echo $this->db->last_query() . "<br>";
                        }
                        else{
                            $rowData['fulldate'] = date("Y-m-d", strtotime($rowData['dtime']));
                            $rowData['produk_ord_jml'] = $rowData['valid_qty'];
                            unset($rowData['id']);
                            $ins = $this->db->insert("transaksi_data_sum_realtimepos_bridge_return", $rowData);
                            echo $this->db->last_query() . "<br>";
                        }
                    }
                }
            }
        }

        echo "=======================================<br>";
        echo "============   LAP RETUR   ============<br>";
        echo "=======================================<br>";
        echo "== TOTAL ROW = ".count($rowReturnPenjualan)." ==<br>";
        echo "== TOTAL PRODUK ROW = $numR ==<br>";
        echo "=======================================<br>";
        echo "=======================================<br><br>";

        $dateEnd = date("Y-m-d H:i:s");

//        $dateDiff = $dateEnd - $dateStart;
//        cekHere( count($this->rawCheckBridgePenjualan()) );

        $start_date = new DateTime($dateStart);
        $since_start = $start_date->diff(new DateTime($dateEnd));
        echo $since_start->days.' days total<br>';
        echo $since_start->y.' years<br>';
        echo $since_start->m.' months<br>';
        echo $since_start->d.' days<br>';
        echo $since_start->h.' hours<br>';
        echo $since_start->i.' minutes<br>';
        echo $since_start->s.' seconds<br>';

        //matiHere( "MATI DULU LINE: " . __LINE__ . "");

        $this->db->trans_complete();

//        header("Refresh:5");

//        echo "<script> setTimeout( function(){ top.window.location.reload() },5000) </script>";

        matiHere( "COMMITE DONE: " . __LINE__ . " || refresh tiap 5 detik");

    }

    //bersih2 pindahin ke archive

    protected $dayArchives = 30;
    public function moveBridgeToArchive()
    {
        $sevenDaysAgo = date('Y-m-d', strtotime("-".$this->dayArchives." days"));

        $this->db->select("id");
        $this->db->from("transaksi_data_sum_realtimepos_bridge");
        $this->db->where("fulldate < '$sevenDaysAgo' AND sisa=0");
        $this->db->limit(2500);
        $row = $this->db->get()->result();

        $result = array();
        $insert=array();
        $delete=array();
        if (!empty($row)) {
            foreach ($row as $k => $rows) {
//                echo $rows->id . "<br>";
                $ins = $this->db->query("INSERT INTO transaksi_data_sum_realtimepos_bridge_archives SELECT * FROM transaksi_data_sum_realtimepos_bridge WHERE id = " . $rows->id . ";");
                if ($ins) {
//                    echo "insertID: " . $ins . "<br>";
                    $insert[] = $ins;
                    $del = $this->db->query("DELETE FROM transaksi_data_sum_realtimepos_bridge WHERE id = " . $rows->id . ";");
                    if($del){
                        $delete[] = $del;
                    }
                }
            }

            if(count($row) == count($insert) && count($row) == count($delete)){
                $result["status"] = 1;
                $result["reason"] = "berhasil pindah (bridge): " . count($row);

                $this->db->select("id");
                $this->db->from("transaksi_data_sum_realtimepos_bridge");
                $this->db->where("fulldate < '$sevenDaysAgo' AND sisa=0");
                $num_rows = $this->db->get()->num_rows();
                $result["sisa"] = $num_rows;

                $this->db->select("id");
                $this->db->from("transaksi_data_sum_realtimepos_bridge_archives");
                $num_rows = $this->db->get()->num_rows();
                $result["arsip"] = $num_rows;

            }
            else{
                $result["status"] = 0;
                $result["reason"] = "GAGAL PINDAH (bridge)";
            }
            //header("Refresh:3");
        }
        else {
//            echo "habis";
            $result["status"] = 99;
            $result["reason"] = "HABIS";
            //header("Refresh:60");
        }

        return $result;
//        echo count($row);
    }

    public function moveTCToArchive()
    {
        $sevenDaysAgo = date('Y-m-d', strtotime("-".$this->dayArchives." days"));

        $this->db->select("id");
        $this->db->from("transaksi_consolidasi");
        $this->db->where("DATE(dtime) < '$sevenDaysAgo'");
        $this->db->limit(5000);
        $row = $this->db->get()->result();

        $result = array();
        $insert=array();
        $delete=array();

        if (!empty($row)) {
            foreach ($row as $k => $rows) {
//                echo $rows->id . "<br>";
                $ins = $this->db->query("INSERT INTO transaksi_consolidasi_archives SELECT * FROM transaksi_consolidasi WHERE id = " . $rows->id . ";");
                if ($ins) {
//                    echo "insertID: " . $ins . "<br>";
                    $insert[] = $ins;
                    $del = $this->db->query("DELETE FROM transaksi_consolidasi WHERE id = " . $rows->id . ";");
                    if($del){
                        $delete[] = $del;
                    }
                }
            }
            //header("Refresh:3");

            if(count($row) == count($insert) && count($row) == count($delete)){
                $result["status"] = 1;
                $result["reason"] = "berhasil pindah (transaksi): " . count($row);

                $this->db->select("id");
                $this->db->from("transaksi_consolidasi");
                $this->db->where("DATE(dtime) < '$sevenDaysAgo'");
                $num_rows = $this->db->get()->num_rows();
                $result["sisa"] = $num_rows;

                $this->db->select("id");
                $this->db->from("transaksi_consolidasi_archives");
                $num_rows = $this->db->get()->num_rows();
                $result["arsip"] = $num_rows;

            }
            else{
                $result["status"] = 0;
                $result["reason"] = "GAGAL PINDAH (transaksi)";
            }

        }
        else {

            $result["status"] = 99;
            $result["reason"] = "HABIS";

            $this->db->select("id");
            $this->db->from("transaksi_consolidasi");
            $num_rows = $this->db->get()->num_rows();

            $result["sisa"] = $num_rows;
            $this->db->select("id");
            $this->db->from("transaksi_consolidasi_archives");
            $num_rows = $this->db->get()->num_rows();

            $result["arsip"] = $num_rows;


            //header("Refresh:60");
        }

        return $result;
//        echo count($row);
    }
    public function moveTDCToArchive()
    {
        $sevenDaysAgo = date('Y-m-d', strtotime("-".$this->dayArchives." days"));

        $this->db->select("id");
        $this->db->from("transaksi_data_consolidasi");
        $this->db->where("DATE(dtime) < '$sevenDaysAgo'");
        $this->db->limit(5000);
        $row = $this->db->get()->result();
        $result = array();
        $insert=array();
        $delete=array();
        if (!empty($row)) {
            foreach ($row as $k => $rows) {
//                echo $rows->id . "<br>";
                $ins = $this->db->query("INSERT INTO transaksi_data_consolidasi_archives SELECT * FROM transaksi_data_consolidasi WHERE id = " . $rows->id . ";");
                if ($ins) {
//                    echo "insertID: " . $ins . "<br>";
                    $insert[] = $ins;
                    $del = $this->db->query("DELETE FROM transaksi_data_consolidasi WHERE id = " . $rows->id . ";");
                    if($del){
                        $delete[] = $del;
                    }
                }
            }

            if(count($row) == count($insert) && count($row) == count($delete)){
                $result["status"] = 1;
                $result["reason"] = "berhasil pindah (transaksi_data): " . count($row);

                $this->db->select("id");
                $this->db->from("transaksi_data_consolidasi");
                $this->db->where("DATE(dtime) < '$sevenDaysAgo'");
                $num_rows = $this->db->get()->num_rows();
                $result["sisa"] = $num_rows;

                $this->db->select("id");
                $this->db->from("transaksi_data_consolidasi_archives");
                $num_rows = $this->db->get()->num_rows();
                $result["arsip"] = $num_rows;
            }
            else{
                $result["status"] = 0;
                $result["reason"] = "GAGAL PINDAH (transaksi_data)";
            }

            //header("Refresh:3");
        }
        else {

            $result["status"] = 99;
            $result["jenis"] = "transaksi_data";
            $result["reason"] = "HABIS";

            $this->db->select("id");
            $this->db->from("transaksi_data_consolidasi");
            $num_rows = $this->db->get()->num_rows();
            $result["sisa"] = $num_rows;

            $this->db->select("id");
            $this->db->from("transaksi_data_consolidasi_archives");
            $num_rows = $this->db->get()->num_rows();
            $result["arsip"] = $num_rows;
            //header("Refresh:60");
        }

        return $result;
//        echo count($row);
    }
    public function moveTDRCToArchive()
    {
        $sevenDaysAgo = date('Y-m-d', strtotime("-".$this->dayArchives." days"));

        $this->db->select("id");
        $this->db->from("transaksi_data_registry_consolidasi");
        $this->db->where("DATE(datetime) < '$sevenDaysAgo'");
        $this->db->limit(5000);
        $row = $this->db->get()->result();

        $result = array();
        $insert=array();
        $delete=array();
        if (!empty($row)) {
            foreach ($row as $k => $rows) {
//                echo $rows->id . "<br>";
                $ins = $this->db->query("INSERT INTO transaksi_data_registry_consolidasi_archives SELECT * FROM transaksi_data_registry_consolidasi WHERE id = " . $rows->id . ";");
                if ($ins) {
//                    echo "insertID: " . $ins . "<br>";
                    $insert[] = $ins;
                    $del = $this->db->query("DELETE FROM transaksi_data_registry_consolidasi WHERE id = " . $rows->id . ";");
                    if($del){
                        $delete[] = $del;
                    }
                }
            }
            //header("Refresh:3");

            if(count($row) == count($insert) && count($row) == count($delete)){
                $result["status"] = 1;
                $result["reason"] = "berhasil pindah (transaksi_data_registry): " . count($row) . "";

                $this->db->select("id");
                $this->db->from("transaksi_data_registry_consolidasi");
                $this->db->where("DATE(datetime) < '$sevenDaysAgo'");
                $num_rows = $this->db->get()->num_rows();
                $result["sisa"] = $num_rows;

                $this->db->select("id");
                $this->db->from("transaksi_data_registry_consolidasi_archives");
                $num_rows = $this->db->get()->num_rows();
                $result["arsip"] = $num_rows;

            }
            else{
                $result["status"] = 0;
                $result["reason"] = "GAGAL PINDAH (transaksi_data_registry)";

            }
        }
        else {

            $result["status"] = 99;
            $result["jenis"] = "transaksi_data_registry";
            $result["reason"] = "HABIS";

            $this->db->select("id");
            $this->db->from("transaksi_data_registry_consolidasi");
            $num_rows = $this->db->get()->num_rows();
            $result["sisa"] = $num_rows;

            $this->db->select("id");
            $this->db->from("transaksi_data_registry_consolidasi_archives");
            $num_rows = $this->db->get()->num_rows();
            $result["arsip"] = $num_rows;
            //header("Refresh:60");
        }


        return $result;
//        echo count($row);
    }
    public function moveToArchives_get(){
        $result = array();
        $result["bridge"] = $this->moveBridgeToArchive();
        $result["transaksi"] = $this->moveTCToArchive();
        $result["transaksi_data"] = $this->moveTDCToArchive();
        $result["transaksi_data_registry"] = $this->moveTDRCToArchive();
        echo json_encode($result);

        header("Refresh:10");
    }

    public function myip_get(){

        echo json_encode(array("ip"=>$_SERVER['REMOTE_ADDR']));
    }

    function getCustomer_get(){
//        $options = array("id" => $clientId);
        $this->load->model("Mdls/MdlCustomer");
        $m = new MdlCustomer();
        $client_info =$m->lookUpAll()->result();
        $this->response($client_info, 200);
//        return $this->respond(json_encode($client_info));
//        echo json_encode($client_info);
    }
}