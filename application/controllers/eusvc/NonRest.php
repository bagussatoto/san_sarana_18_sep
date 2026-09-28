<?php

class NonRest extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        session_write_close();
    }
    function index()
    {


    }
//     function SyncTransaksi__(){
//
//         $last_id = $this->db->get("transaksi_pos_lastID")->result();
//         $machine_id = MACHINE_ID;
//         if(!empty($last_id)){
//             $last_id = $last_id[0]->lastid*1;
//         }
//         else{
//             $last_id = 0;
//         }
//
//         $this->db->where("id > $last_id AND link_id='0' AND jenis IN('982','582')");
//         $this->db->limit(100);
//         $q = $this->db->get("transaksi");
//         $res = $q->result();
//
//         $newLastID = !empty($res) ? max($res)->id : $last_id;
//
//         $blackListKey=array(
//             "id",
// //            "ids_his",
// //            "counters",
// //            "indexing_detail_values",
// //            "indexing_main_values",
// //            "indexing_details",
//         );
//
//         $preInsData=array();
//
//         if(!empty($res)){
//             foreach($res as $ky => $obj){
//                 $preInsRow=array();
//                 foreach($obj as $col => $dt ){
//                     if(!in_array($col, $blackListKey)){
//                         $preInsRow[$col] = $dt;
//                     }
//                     $preInsRow['x_id'] = $obj->id;
//                     $preInsRow['machine_id'] = MACHINE_ID;
//                 }
//                 $preInsData[] = $preInsRow;
//             }
//         }
//
//         $sendTo = array(
//             "datas" => blobEncode($preInsData)
//         );
//
//         if(!empty($preInsData)){
//             $curl = New Curl();
//             $url_test = ADM_DOMAIN . "/eusvc/NonRest/insTrax";
//             $sendToMainServer = $curl->_simple_call("post", $url_test, $sendTo );
//             $arrCallBack = json_decode($sendToMainServer, true);
//             if($arrCallBack['status'] && $arrCallBack['ib'] > 0 ){
//                 $this->db->replace("transaksi_pos_lastID", array("id"=>1, "id_machine"=>$machine_id, "lastid"=>$newLastID));
//             }
//             echo json_encode($arrCallBack);
//         }
//
//     }
//     function SyncTransaksi(){
//
//         $microStart = microtime(1);
//
//         $dataResult      = array();
//         $dataCountResult = array();
//         $dataSizeResult  = array();
//         $last_query      = array();
//
//         $arrListPantau = array(
//             "transaksi",
//             "transaksi_data",
// //            "transaksi_data_registry",
//         );
//
//         $machine_id = MACHINE_ID;
//
//
//         $dataResult = array();
//         foreach($arrListPantau as $table){
//             $this->db->where("id_machine=$machine_id AND db_table='$table' ORDER BY id DESC");
//             $this->db->limit(1);
//             $tmpL = $this->db->get("transaksi_pos_lastid")->result();
//             $last_id = isset($tmpL[0]->lastid) ? $tmpL[0]->lastid : 0;
//             $this_id = isset($tmpL[0]->id) ? $tmpL[0]->id : "";
//
//             echo "$table last_id: $last_id <br>";
//
//             $fields = $this->db->list_fields($table);
//
// //            arrPrint($fields);
// //            if(in_array("id", $fields)){
// ////                echo "id di temukan <br>";
//             $this->db->where("id>$last_id");
// //            }
// //            else{
// ////               echo "tidak ada id <br>";
// //                $this->db->where("transaksi_id>$last_id");
// //            }
//
//             $this->db->limit(1);
//             $q = $this->db->get($table);
//             $res = $q->result();
//
//             if(in_array("id", $fields)){
// //                echo "id di temukan <br>";
//                 $newLastID = !empty($res) ? max($res)->id : $last_id;
//             }
//             else{
// //               echo "tidak ada id <br>";
//                 $newLastID = !empty($res) ? max($res)->transaksi_id : $last_id;
//             }
//             //echo "$table newLastID: $newLastID <br>";
//             $preInsData=array();
//             if(!empty($res)){
//                 foreach($res as $ky => $obj){
//                     $preInsRow=array();
//                     foreach($obj as $col => $dt ){
//                         $preInsRow[$col] = $dt;
//                         $preInsRow['x_id'] = $obj->id;
//                         $preInsRow['machine_id'] = MACHINE_ID;
//                     }
//                     unset($preInsRow['id']);
//                     unset($preInsRow['ids_his']);
//                     unset($preInsRow['counters']);
//                     unset($preInsRow['indexing_details']);
//                     unset($preInsRow['indexing_details_values']);
//                     unset($preInsRow['indexing_main_values']);
//                     $preInsData[] = $preInsRow;
//                 }
//                 $dataResult[$table] = array(
//                     "table" => $table,
//                     "last_id" => $last_id,
//                     "new_last_id" => $newLastID,
//                     "row" => count($preInsData),
//                     "datetime" => date("Y-m-d H:i:s"),
//                     "data" => $preInsData,
//                     "machine_id" => $machine_id,
//                 );
//             }
//         }
//
//         $callBackResult=array();
//         if(!empty($dataResult)){
//             foreach($dataResult as $tb => $datas){
//                 $curl = New Curl();
//                 $url_test = ADM_DOMAIN . "/eusvc/DataSync/allDataSales_Handler";
//                 $sendToMainServer = $curl->_simple_call("post", $url_test, $datas );
//                 $arrCallBack = json_decode($sendToMainServer, true);
//
//                 if( $arrCallBack['status']==1 && $arrCallBack['ib']*1>0 ){
//                     $this->db->replace("transaksi_pos_lastid", array("id"=>$machine_id, "id_machine"=>$machine_id, "db_table"=>$tb, "lastid"=>$newLastID));
//                 }
//
// //                echo $tb . "<br>";
// //                echo json_encode($arrCallBack) . "<br>";
//
//             }
//         }
//
//     }
//
//     function createTransaksiFile(){
//         $microStart = microtime(1);
//         $dataResult      = array();
//         $dataCountResult = array();
//         $dataSizeResult  = array();
//         $last_query      = array();
//         $arrListPantau = array(
//             "transaksi",
//             "transaksi_data",
//             "transaksi_data_registry",
//         );
//         $machine_id = detectMachineID();
//         $dataResult = array();
//         foreach($arrListPantau as $table){
//             $this->db->where("id_machine='$machine_id' AND db_table='$table' ORDER BY id DESC");
//             $this->db->limit(1);
//             $tmpL = $this->db->get("transaksi_pos_lastid")->result();
//             $last_id = isset($tmpL[0]->lastid) ? $tmpL[0]->lastid : 0;
//             $this_id = isset($tmpL[0]->id) ? $tmpL[0]->id : "";
//             if($table == "transaksi_data_registry"){
//                 $this->db->where("transaksi_id>$last_id");
//             }
//             else{
//                 $this->db->where("id>$last_id");
//             }
//             $q = $this->db->get($table);
//             $res = $q->result();
//             if($table == "transaksi_data_registry"){
//                 $newLastID = !empty($res) ? max($res)->transaksi_id : $last_id;
//             }
//             else{
//                 $newLastID = !empty($res) ? max($res)->id : $last_id;
//             }
//             if($table == "transaksi_data_registry"){
//
//             }
//             else{
//
//             }
//             $preInsData=array();
//             if(!empty($res)){
//                 foreach($res as $ky => $obj){
//                     $preInsRow=array();
//                     foreach($obj as $col => $dt ){
//                         $preInsRow[$col] = $dt;
//                         if($table == "transaksi_data_registry"){
//                             $preInsRow['x_id'] = $obj->transaksi_id;
//                             $preInsRow['machine_id'] = MACHINE_ID;
//                         }
//                         else{
//                             $preInsRow['x_id'] = $obj->id;
//                             $preInsRow['machine_id'] = MACHINE_ID;
//                         }
//                     }
//                     unset($preInsRow['id']);
//                     unset($preInsRow['ids_his']);
//                     unset($preInsRow['counters']);
//                     unset($preInsRow['indexing_details']);
//                     unset($preInsRow['indexing_details_values']);
//                     unset($preInsRow['indexing_main_values']);
//                     $preInsData[] = $preInsRow;
//                 }
//                 $dataResult[$table] = array(
//                     "table" => $table,
//                     "last_id" => $last_id,
//                     "new_last_id" => $newLastID,
//                     "row" => count($preInsData),
//                     "datetime" => date("Y-m-d H:i:s"),
//                     "data" => $preInsData,
//                     "machine_id" => $machine_id,
//                 );
//             }
//         }
//         $callBackResult=array();
//         if(!empty($dataResult)){
//             foreach($dataResult as $tb => $datas){
//                 $numbering = $this->numbering($tb);
//                 $namaFile = $numbering . "_" . date("YmdHis") . ".txt";
//                 $filename = __DIR__ . "/NonRest/" . $namaFile;
//                 $file = fopen($filename, "w");
//                 $writed = fwrite($file, json_encode($datas));
//                 fclose($file);
//                 if($writed){
//                     //nulis log ke transaksi_pos_create_file_log
//                     if(file_exists($filename)){
//                         $this->db->insert("transaksi_pos_create_file_log",
//                             array(
//                                 "id_machine" => $machine_id,
//                                 "db_table" => $tb,
//                                 "lastid" => $datas['new_last_id'],
//                                 "file_name" => $namaFile,
//                                 "time_create" => date("Y-m-d H:i:s"),
//                                 "file_path" => $filename,
//                                 "row" => $datas['row'],
//                                 "file_size" => (strlen(serialize($datas['data']))+1)
//                             ));
//                     }
//                     $this->db->select("*");
//                     $this->db->where(array("id_machine"=>$machine_id, "db_table"=>$tb));
//                     $qc = $this->db->get("transaksi_pos_lastid");
//                     if( $qc->result() ){
//                         $this->db->set('lastid', $datas['new_last_id']);
//                         $this->db->where(array("id_machine"=>$machine_id, "db_table"=>$tb));
//                         $this->db->update('transaksi_pos_lastid');
//                     }
//                     else{
//                         //nyimpan last id
//                         $this->db->replace("transaksi_pos_lastid",
//                             array(
//                                 "id" => $this_id,
//                                 "id_machine" => $machine_id,
//                                 "db_table" => $tb,
//                                 "lastid" => $datas['new_last_id'],
//                             )
//                         );
//                     }
//                 }
//             }
//         }
//     }
//     function sendFileToServer(){
//         $this->db->where("status=0");
//         $this->db->limit(1);
//         $tmpL = $this->db->get("transaksi_pos_create_file_log")->result();
//         if(!empty($tmpL)){
//             $id = $tmpL[0]->id;
//             $id_machine = $tmpL[0]->id_machine;
//             $db_table = $tmpL[0]->db_table;
//             $time_create = $tmpL[0]->time_create;
//             $lastid = $tmpL[0]->lastid;
//             $row = $tmpL[0]->row;
//             $file_size = $tmpL[0]->file_size;
//             $time_sent = $tmpL[0]->time_sent;
//             $file_name_with_full_path = $tmpL[0]->file_path;
//             if( !file_exists($file_name_with_full_path) ){
//                 matiHere("FILE $file_name_with_full_path TIDAK DITEMUKAN");
//             }
//             $target_url = ADM_DOMAIN . "/eusvc/NonRest/setUploadStream";
//             if(function_exists('curl_file_create')) {
//                 $cFile = curl_file_create($file_name_with_full_path);
//             }
//             else{
//                 $cFile = '@' . realpath($file_name_with_full_path);
//             }
//             $post = array(
//                 'time_sent'=> $time_sent,
//                 'file_size'=> $file_size,
//                 'row'=> $row,
//                 'lastid'=> $lastid,
//                 'time_create'=> $time_create,
//                 'id_machine'=> $id_machine,
//                 'db_table'=> $db_table,
//                 'file_contents'=> $cFile
//             );
//             $ch = curl_init();
//             curl_setopt($ch, CURLOPT_URL, $target_url);
//             curl_setopt($ch, CURLOPT_POST, 1);
//             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//             curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
//             $result = curl_exec($ch);
//             curl_close ($ch);
//             $outPut = json_decode($result, true);
//             if( $outPut['status']==1 ){
//                 //$this->sendFileToServer();
//                 $this->db->set(array('status'=>1, "time_sent"=>date("Y-m-d H:i:s")));
//                 $this->db->where('id', $id);
//                 $this->db->update('transaksi_pos_create_file_log');
//                 $result=array(
//                     "status" => 1,
//                     "reason" => "berhasil dikirim",
//                 );
//                 echo json_encode($result);
// 				$this->sendFileToServer();
//             }
//         }
//         else{
//             $result=array(
//                 "status" =>0,
//                 "reason" => "tidak ada file untuk dikirim",
//             );
//             echo json_encode($result);
//         }
//     }
//
//     //mode 1
//     //mode 2 ada di atas (web admin)
//     function checkSinkron(){
//
//         $from = $this->uri->segment(4);
//         $machine_id = MACHINE_ID;
//         $arrListKonsolidasi = array(
//             "produk",
//             "price",
//             "satuan_produk_relasi",
//             "satuan",
//             "acc_coa",
//             "diskon",
//             "diskon_customer",
//             "produk_folders",
//             "per_cabang",
//             "per_cabang_device",
//             "per_customer_level",
//             "per_customers",
//             "per_employee",
//             "bank",
//             "company_profile",
//             "setting_struk",
//             "fifo_avg",
//             "__rek_pembantu_customer__2010050",
//             "_rek_pembantu_customer_cache",
//         );
//
//         $url = ADM_DOMAIN . "/eusvc/NonRest/checkUpdate/".$machine_id;
//
//         $ch = New Curl();
//         $ch = curl_init();
//         curl_setopt($ch, CURLOPT_URL,$url);
//         curl_setopt($ch, CURLOPT_POST, 0);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//         $server_output = curl_exec($ch);
//         curl_close ($ch);
//
//         $arrOutput = json_decode($server_output);
//
//         echo json_encode($arrOutput);
//
//     }
//
//     //baru nih
//     function checkSinkronV2(){
//
//         $from = $this->uri->segment(4);
//         $machine_id = MACHINE_ID;
//
//         $arrList = list_update_table();
//
//         $url = ADM_DOMAIN . "/eusvc/NonRest/checkUpdateV2/".$machine_id;
//
//         $date_last=array();
//         foreach($arrList as $table => $lab){
//             if( $this->db->table_exists($table) ){
//                 if(isset($arrForced[$table])){
//                     $date_last[$table] = "1990-01-01 23:59:59";
//                 }
//                 else{
//                     if (!$this->db->field_exists("last_update", $table)){
//                         $date_last[$table] = "1990-01-01 23:59:59";
//                     }
//                     else{
//                         $this->db->order_by("last_update", "desc");
//                         $this->db->limit(1);
//                         $a = $this->db->get($table)->result();
//                         $date_last[$table] = !empty($a) && $a[0]->last_update!= '' ? $a[0]->last_update : "1990-01-01 23:59:59";
//                     }
//                 }
//             }
//             else{
//                 $date_last[$table] = "1990-01-01 23:59:59";
//             }
//         }
//
//         $arrCabangDev = array();
//         $cabDevTmp = $this->db->get("per_cabang_device")->result();
//         if(!empty($cabDevTmp)){
//             foreach($cabDevTmp as $k => $cab){
//                 $cabDevTmp[$cab->machine_id] = $cab->cabang_id;
//             }
//         }
//
//         $post = array(
//             "date_last"  => $date_last,
//             "machine_id" => $machine_id,
//             "cabang_id"  => isset($cabDevTmp[$machine_id]) ? $cabDevTmp[$machine_id] : "none",
//         );
//
//         $ch = New Curl();
//         $ch = curl_init();
//         curl_setopt($ch, CURLOPT_URL,$url);
//         curl_setopt($ch, CURLOPT_POST, 1);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post) );
//         $server_output = curl_exec($ch);
//         curl_close ($ch);
//
//         $arrOutput = json_decode($server_output);
//
//         if( $arrOutput->connection == 1 ){
//             echo json_encode($arrOutput);
//         }
//         else{
//             $result = array(
//                 "status" => 88,
//                 "reason" => "Pastikan Komputer terkoneksi dengan jaringan INTERNET.",
// //                "error" => $server_output,
//             );
//             echo json_encode($result);
//         }
//
//     }
//     //baru nih
//     function checkCabangSinkronV2(){
//
//         $from = $this->uri->segment(4);
//         $machine_id = MACHINE_ID;
//
//         $arrList = array(
//             "per_cabang" => "DATA CABANG",
//             "per_cabang_device" => "DEVICE CABANG",
//         );
//
//         $url = ADM_DOMAIN . "/eusvc/NonRest/checkUpdateV2/".$machine_id;
//
//         $date_last=array();
//         foreach($arrList as $table => $lab){
//             if( $this->db->table_exists($table) ){
//                 if (!$this->db->field_exists("last_update", $table)){
//                     $date_last[$table] = "1990-01-01 23:59:59";
//                 }
//                 else{
//                     $this->db->order_by("last_update", "desc");
//                     $this->db->limit(1);
//                     $a = $this->db->get($table)->result();
//                     $date_last[$table] = !empty($a) && $a[0]->last_update!= '' ? $a[0]->last_update : "1990-01-01 23:59:59";
//                 }
//             }
//             else{
//                 $date_last[$table] = "1990-01-01 23:59:59";
//             }
//         }
//
//         $arrCabangDev = array();
//         $cabDevTmp = $this->db->get("per_cabang_device")->result();
//         if(!empty($cabDevTmp)){
//             foreach($cabDevTmp as $k => $cab){
//                 $cabDevTmp[$cab->machine_id] = $cab->cabang_id;
//             }
//         }
//
//         $post = array(
//             "date_last"  => $date_last,
//             "machine_id" => $machine_id,
//             "cabang_id"  => isset($cabDevTmp[$machine_id]) ? $cabDevTmp[$machine_id] : "none",
//         );
//
//         $ch = New Curl();
//         $ch = curl_init();
//         curl_setopt($ch, CURLOPT_URL,$url);
//         curl_setopt($ch, CURLOPT_POST, 1);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post) );
//         $server_output = curl_exec($ch);
//         curl_close ($ch);
//
//         $arrOutput = json_decode($server_output);
//
//         if( $arrOutput->connection == 1 ){
//             echo json_encode($arrOutput);
//         }
//         else{
//             $result = array(
//                 "status" => 88,
//                 "reason" => "Pastikan Komputer terkoneksi dengan jaringan INTERNET.",
//             );
//             echo json_encode($result);
//         }
//
//     }
//     //baru nih
//     function checkProdukSinkronV2(){
//
//         $from = $this->uri->segment(4);
//         $machine_id = MACHINE_ID;
//
//         $arrList = array(
//             "produk" => "asdasd",
//             "price" => "asdasd",
//             "price_per_area" => "asdasd",
//             "satuan_produk_relasi" => "asdasd",
//             "satuan" => "asdasd",
//             "diskon" => "asdasd",
//             "diskon_customer" => "asdasd",
//             "produk_folders" => "asdasd",
//         );
//
//         $url = ADM_DOMAIN . "/eusvc/NonRest/checkUpdateV2/".$machine_id;
//
//         $hasil=array();
//         foreach($arrList as $table => $lab){
//             if( $this->db->table_exists($table) ){
//                 $this->db->order_by("last_update", "desc");
//                 $this->db->limit(1);
//                 $a = $this->db->get($table)->result();
//                 $hasil[$table] = !empty($a) && $a[0]->last_update!= '' ? $a[0]->last_update : "1990-01-01 23:59:59";
//             }
//             else{
//                 $hasil[$table] = "1990-01-01 23:59:59";
//             }
//         }
//
//         $arrCabangDev = array();
//         $cabDevTmp = $this->db->get("per_cabang_device")->result();
//         if(!empty($cabDevTmp)){
//             foreach($cabDevTmp as $k => $cab){
//                 $cabDevTmp[$cab->machine_id] = $cab->cabang_id;
//             }
//         }
//
//         $post = array(
//             "date_last"  => $hasil,
//             "machine_id" => $machine_id,
//             "cabang_id"  => isset($cabDevTmp[$machine_id]) ? $cabDevTmp[$machine_id] : "none",
//         );
//
//         $ch = New Curl();
//         $ch = curl_init();
//         curl_setopt($ch, CURLOPT_URL,$url);
//         curl_setopt($ch, CURLOPT_POST, 1);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post) );
//         $server_output = curl_exec($ch);
//         curl_close ($ch);
//
//         $arrOutput = json_decode($server_output);
//
//         if( $arrOutput->connection == 1 ){
//             echo json_encode($arrOutput);
//         }
//         else{
//             $result = array(
//                 "status" => 88,
//                 "reason" => "Pastikan Komputer terkoneksi dengan jaringan INTERNET.",
//             );
//             echo json_encode($result);
//         }
//
//     }
//
//     //untuk test
//     function regMainTable(){
//
//
//         $this->db->where_in('settlement_id', array(3733,3597,3173));
//         $transaksi = $this->db->get('transaksi')->result();
//         $trID=array();
//         foreach($transaksi as $k => $db){
//             $trID[] = $db->id;
//         }
//
//         $this->db->where_in('transaksi_id', $trID);
//         $this->db->order_by('transaksi_id',"ASC");
//
//         $reg = $this->db->get('transaksi_data_registry')->result();
//
//         $fieldList=array(
//             "nomer" => "INV",
//
// //            "settlement_id" => "settlement_id",
// //            "fulldate" => "TANGGAL",
//
//             "dtime" => "TANGGAL",
//             "customerName" => "MEMBER",
//             "olehName" => "KASIR",
//             "harga_jual" => "BRUTO",
//             "discNilai" => "DISKON PRODUK",
//             "add_disc" => "additional diskon",
//             "diskon_tambahan_nilai" => "diskon konsumen",
//             "tagihan" => "grand total",
//             "bayar" => "bayar",
//             "kembali" => "kembali",
//         );
//
//         $mainReg=array();
//         foreach($reg as $kk => $dtTR ){
//             $deBlob = blobDecode( $dtTR->main );
//             foreach($fieldList as $key => $labels){
//                 if(isset($deBlob[$key])){
//                     $mainReg[$kk][$key] = $deBlob[$key];
//                 }
//             }
//         }
//
//         echo "<table border=1 class='table'>";
//         echo "<thead>";
//         echo "<tr>";
//         foreach($fieldList as $ky => $lab){
//             echo "<th>".$lab."</th>";
//         }
//         echo "</tr>";
//         echo "</thead>";
//
//
//         echo "<tbody>";
//         foreach($mainReg as $row => $data){
//             echo "<tr>";
//
//             foreach($fieldList as $key => $labDa){
//                 $val = isset($data[$key]) ? $data[$key] : 0;
//                 echo "<td>".$val."</td>";
//             }
//
//             echo "</tr>";
//         }
//         echo "</tbody>";
//
//         echo "</table>";
// //        echo json_encode($mainReg);
//     }
//
//     function numbering($dt_table){
//         $arr = array();
//         //region penomoran receipt
//         $this->load->model("CustomCounter");
//         $cn = new CustomCounter("transaksi");
//         $cn->setType("file");
//         //$this->db->trans_start();
//         $trAlias = "f";
//         $numBatch = array(
//             "$trAlias" => array(
//                 "counters" => array(
//                     "stepCode", //global number
//                     "machine_id",
//                     "dt_table",
//                     "stepCode|machine_id",
//                     "stepCode|dt_table",
//                 ),
//                 "formatNota" => ".stepCode,.machine_id,stepCode|dt_table,stepCode|machine_id,.dt_table",
//             )
//         );
//         $arr['stepCode'] = $trAlias;
//         $arr['machine_id'] = MACHINE_ID;
//         $arr['dt_table'] = $dt_table;
//         $counterForNumber = array($numBatch[$trAlias]['formatNota']);
//         $cn = new CustomCounter("transaksi");
//         $cn->setType("file");
//         $configCustomParams = $numBatch[$trAlias]['counters'];
//         if (sizeof($configCustomParams) > 0) {
//             $cContent = array();
//             foreach ($configCustomParams as $i => $cRawParams) {
//                 $cParams = explode("|", $cRawParams);
//                 $cValues = array();
//                 foreach ($cParams as $param) {
//                     $cValues[$i][$param] = $arr[$param];
//                 }
//                 $cRawValues = implode("|", $cValues[$i]);
//                 $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);
//                 $cContent[$cRawParams][$cRawValues] = $paramSpec['value'];
//                 switch ($paramSpec['id']) {
//                     case 0: //===counter type is new
//                         $paramKeyRaw = print_r($cParams, true);
//                         $paramValuesRaw = print_r($cValues[$i], true);
//                         $cn->writeNewCount($cParams, $cValues[$i], $paramKeyRaw, $paramValuesRaw);
//                         break;
//                     default: //===counter to be updated
//                         $cn->updateCount($paramSpec['id'], $paramSpec['value']);
//                         break;
//                 }
//             }
//         }
//         $appliedCounters = base64_encode(serialize($cContent));
//         $appliedCounters_inText = print_r($cContent, true);
//         $this->load->model("CustomCounter");
//         $cn = new CustomCounter("transaksi");
//         $cn->setType("file");
//         $counterForNumber = array($numBatch[$trAlias]['formatNota']);
//         $tmpNomorNota="";
//         $arrNomorNota=array();
//         foreach ($counterForNumber as $i => $c0RawParams) {
//             $c0Params = explode(",", $c0RawParams);
//             $c0Values = array();
//             foreach($c0Params as $k=>$cRawParams){
//                 $arrRawParams = explode("|", $cRawParams);
//                 if(sizeof($arrRawParams)>1){
//                     $cRawParamsValues = array();
//                     foreach($arrRawParams as $key){
//                         $cRawParamsValues[$key] = $arr[$key];
//                     }
//                     $cRawParamsValuesK = implode("|", array_keys($cRawParamsValues));
//                     $cRawParamsValuesV = implode("|", $cRawParamsValues);
//                     $arrNomorNota[] = digit_4($cContent[$cRawParamsValuesK][$cRawParamsValuesV]);
//                 }
//                 else{
//                     if(isset($arr[$arrRawParams[0]])){
//                         $cRawParamsValuesV = $arr[$arrRawParams[0]];
//                         $cRawParamsValuesK = $arrRawParams[0];
//                         if($arrRawParams[0]=="startDate"){
//                             $arrNomorNota[] = $cRawParamsValuesV;
//                         }
//                         elseif($arrRawParams[0]=="toko_id"){
//                             $arrNomorNota[] = $cRawParamsValuesV;
//                         }
//                         elseif($arrRawParams[0]=="stepCode"){
//                             $arrNomorNota[] = $cRawParamsValuesV;
//                         }
//                         elseif($arrRawParams[0]=="machine_id"){
//                             $arrNomorNota[] = $cRawParamsValuesV;
//                         }
//                         else{
//                             if(isset($cContent[$cRawParamsValuesK])){
//                                 $arrNomorNota[] = $cContent[$cRawParamsValuesK][$cRawParamsValuesV];
//                             }
//                             else{
//                                 $arrNomorNota[] = $cRawParamsValuesV;
//                             }
//                         }
//                     }
//                     else{
//                         $cc = explode(".", $arrRawParams[0]);
//                         $arrNomorNota[] = $arr[$cc[1]];
//                     }
//                 }
//             }
//         }
//         $tmpNomorNota = implode("_", $arrNomorNota);
//
// //        $this->db->trans_complete();
//         return $tmpNomorNota;
//     }
//
//     function checkHpp(){
//
//         //daftar produk
//         $this->load->model("Mdls/MdlProduk");
//         $pr = new MdlProduk();
//         $tmpPr = $pr->lookupAll()->result();
//         $tmpProduk = array();
//         if(!empty($tmpPr)){
//             foreach($tmpPr as $k => $data){
//                 $tmpProduk[$data->id] = $data;
//             }
//         }
//
//         //daftar harga jual produk
//         $this->load->model("Mdls/MdlHargaProduk");
//         $prh = new MdlHargaProduk();
//         $this->db->where( array("jenis_value"=>"harga_list") );
//         $tmpPrh = $prh->lookupAll()->result();
//         $tmpProdukHarga = array();
//         if(!empty($tmpPrh)){
//             foreach($tmpPrh as $k => $data){
//                 $tmpProdukHarga[$data->produk_id] = $data;
//             }
//         }
//
//         //price_last_purchase
//         $this->load->model("Mdls/MdlHargaProdukLastPurchase");
//         $lp = new MdlHargaProdukLastPurchase();
//         $this->db->where( array("jenis_value"=> "hpp", 'cabang_id'=> -1 ) );
//         $tmpLp = $lp->lookupAll()->result();
//         $tmpLastPurchase = array();
//         if(!empty($tmpLp)){
//             foreach($tmpLp as $k => $data){
//                 $tmpLastPurchase[$data->produk_id] = $data;
//             }
//         }
//
//         //fifo_avg
//         $this->load->model("Mdls/MdlFifoAverage");
//         $hp = new MdlFifoAverage();
//         $this->db->where( array('cabang_id'=>100) );
//         $tmpHp = $hp->lookupAll()->result();
//         $tmpFifo = array();
//         if(!empty($tmpHp)){
//             foreach($tmpHp as $k => $data){
//                 $tmpFifo[$data->produk_id] = $data;
//             }
//         }
//
//         //DATA COMPILATION
//         $keyOut=array(
//             "produk" => array(
//                 "nama" => "nama produk",
//                 "barcode" => "barcode",
//             ),
//             "harga_jual" => array(
//                 "nilai" => "harga jual",
//             ),
//             "last_purchase" => array(
//                 "nilai" => "last purchase",
//             ),
//             "hpp" => array(
//                 "jml" => "stok",
//                 "hpp" => "avg beli",
//                 "jml_nilai" => "nilai stok",
//             ),
//         );
//
//         $arrProduk = array();
//         foreach($tmpProduk as $pid => $datas){
//             foreach($keyOut['produk'] as $k => $label){
//                 $arrProduk[$pid][$label] = $datas->$k;
//             }
//             foreach($keyOut['harga_jual'] as $k => $label){
//                 $arrProduk[$pid][$label] = isset($tmpProdukHarga[$pid]) ? number_format((float)$tmpProdukHarga[$pid]->$k*1, 2, '.', '')*1 : 0;
//             }
//             foreach($keyOut['last_purchase'] as $k => $label){
//                 $arrProduk[$pid][$label] = isset($tmpLastPurchase[$pid]) ? number_format((float)$tmpLastPurchase[$pid]->$k*1 , 2, '.', '')*1 : 0;
//             }
//             foreach($keyOut['hpp'] as $k => $label){
//                 $arrProduk[$pid][$label] = isset($tmpFifo[$pid]) ? number_format((float)$tmpFifo[$pid]->$k*1, 2, '.', '')*1 : 0;
//             }
//             $arrProduk[$pid]['laba'] = number_format((float)($tmpProdukHarga[$pid]->nilai*1)-($tmpFifo[$pid]->hpp*1), 2, '.', '')*1;
//         }
//
// //        $result = array(
// //            "arrProduk" => $arrProduk,
// ////            "tmpFifo" => $tmpFifo,
// //        );
// //        $this->response($result, 200);
//
//         echo "<script>console.table(".json_encode($arrProduk).");</script>";
//     }
//
//     function checkSaldoKas(){
//         $var = checkSaldoKas();
//         echo json_encode($var);
//     }
//
//     //update untuk update data LOG -7 Day atau -3 Day atur sajalah
//     function autoClearDataPos(){
//
//         $this->db->select("id,fulldate");
//         $this->db->where("jenis", "758");
//         $pysrc = $this->db->get("transaksi_payment_source");
//         $tmpPym = $pysrc->result();
//
//         if( !empty($tmpPym)  ){
//             $last_settlement = max($tmpPym)->fulldate;
//         }
//         else{
//             $last_settlement = date("Y-m-d");
//         }
//
//         $this->db->select('id');
//         $this->db->where("fulldate <= DATE_ADD('$last_settlement', INTERVAL -3 DAY)");
//         $qry = $this->db->get('transaksi');
//         $tmp = $qry->result();
//
//         $this->db->select('id');
//         $this->db->where("dtime <= DATE_ADD('$last_settlement', INTERVAL -3 DAY)");
//         $qry_log = $this->db->get('log');
//         $tmp_log = $qry_log->result();
//
//         if( !empty($tmp) ){
//             $id_max_delete = max($tmp)->id;
//
//             $this->db->select("id,fulldate");
//             $this->db->where("id <= $id_max_delete");
//             $mainTr = $this->db->get("transaksi");
//             $tmpMain = $mainTr->result();
//
//             $this->db->select("id,transaksi_id,produk_id,produk_nama,dtime");
//             $this->db->where("transaksi_id <= $id_max_delete");
//             $mainTrData = $this->db->get("transaksi_data");
//             $tmpMainData = $mainTrData->result();
//
//             $this->db->select("transaksi_id");
//             $this->db->where("transaksi_id <= $id_max_delete");
//             $mainTrDataReg = $this->db->get("transaksi_data_registry");
//             $tmpMainDataReg = $mainTrDataReg->result();
//
//             $this->db->trans_start();
//
//             echo "<pre>";
//             print_r( count($tmpMain) );
//             echo "</pre>";
//             $this->db->where("id <= $id_max_delete");
//             $delTR = $this->db->delete('transaksi');
//             echo "<pre>";
//             print_r( $this->db->last_query() );
//             echo "</pre>";
//             echo "<pre>";
//             print_r( $delTR );
//             echo "</pre>";
//
//             echo "<pre>";
//             print_r( count($tmpMainData) );
//             echo "</pre>";
//             $this->db->where("transaksi_id <= $id_max_delete");
//             $delTD = $this->db->delete('transaksi_data');
//             echo "<pre>";
//             print_r( $this->db->last_query() );
//             echo "</pre>";
//             echo "<pre>";
//             print_r( $delTD );
//             echo "</pre>";
//
//             echo "<pre>";
//             print_r( count($tmpMainDataReg) );
//             echo "</pre>";
//             $this->db->where("transaksi_id <= $id_max_delete");
//             $delTDR = $this->db->delete('transaksi_data_registry');
//             echo "<pre>";
//             print_r( $this->db->last_query() );
//             echo "</pre>";
//             echo "<pre>";
//             print_r( $delTDR );
//             echo "</pre>";
//
//             if( !empty($tmp_log) ){
//                 echo "<pre>";
//                 print_r( count($tmp_log) );
//                 echo "</pre>";
//                 $this->db->where("dtime <= DATE_ADD('$last_settlement', INTERVAL -3 DAY)");
//                 $delLOG = $this->db->delete('log');
//                 echo "<pre>";
//                 print_r( $this->db->last_query() );
//                 echo "</pre>";
//                 echo "<pre>";
//                 print_r( $delLOG );
//                 echo "</pre>";
//             }
//
// //            matiHere("belum commit");
//             $this->db->trans_complete();
//             matiHere("done commit");
//         }
//         else{
//
//             $this->db->select('id');
//             $this->db->where("fulldate >= '$last_settlement'");
//             $qry = $this->db->get('transaksi');
//             $tmp = $qry->result();
//
//             $id_max_delete = max($tmp)->id;
//
//             $this->db->select("id,fulldate");
//             $this->db->where("id >= $id_max_delete");
//             $mainTr = $this->db->get("transaksi");
//             $tmpMain = $mainTr->result();
//
//             $this->db->select("id,transaksi_id,produk_id,produk_nama,dtime");
//             $this->db->where("transaksi_id >= $id_max_delete");
//             $mainTrData = $this->db->get("transaksi_data");
//             $tmpMainData = $mainTrData->result();
//
//             $this->db->select("transaksi_id");
//             $this->db->where("transaksi_id >= $id_max_delete");
//             $mainTrDataReg = $this->db->get("transaksi_data_registry");
//             $tmpMainDataReg = $mainTrDataReg->result();
//
//             echo "dah abis cuy";
//             echo "<pre>";
//             print_r( count($tmpMain) );
//             echo "</pre>";
//
//             echo "<pre>";
//             print_r( count($tmpMainData) );
//             echo "</pre>";
//
//             echo "<pre>";
//             print_r( count($tmpMainDataReg) );
//             echo "</pre>";
//
//         }
//     }
//
//     //update untuk hapus file NonRest
//     function autoClearNonRestFile(){
//         $path = __DIR__ . "/NonRest/";
//         $from = $this->uri->segment(4);
//         $raw_file = array();
//         if($handle=opendir($path)){
//             while(false!==($file=readdir($handle))){
//                 $filelastmodified = filemtime($path.$file);
//                 // 24=1 48=2 72=3 96=4 120=5 144=6 168=7
//                 // 24 hours in a day * 3600 seconds per hour
//                 $day = $from!=""?$from:168;
//                 if((time()-$filelastmodified)>$day*3600){
//                     unlink($path.$file);
//                     $raw_file[] = $filelastmodified." | $path".$file;
//     }
//             }
//             closedir($handle);
//         }
//         echo "day: $day | jumlah file: ".count($raw_file);
//     }
//
//     function phantomJs(){
//         phantomJs();
//     }

    function norestip(){
//        echo base64_encode(json_encode($_SERVER));

        $url = "https://demo.eplusgo.com/preview/ip/";

        $ch = New Curl();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close ($ch);

        echo $server_output;
    }

    function testWaktu(){
        $gro_start = "2023-05-01 00:00:00";
        $gro_start_0 = "0000-00-00 00:00:00";
        $gro_stop = "2023-05-31 00:00:00";
        $gro_stop_0 = "0000-00-00 00:00:00";
        $gro_start_s = dtimeToSecond($gro_start);
        $gro_start_s_0 = dtimeToSecond($gro_start_0);
        $gro_stop_s = dtimeToSecond($gro_stop);
        $gro_stop_s_0 = dtimeToSecond($gro_stop_0);
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            echo 'This is a server using Windows!' . "<br>";
            echo "<table border=1 class='table'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Key</th>";
            echo "<th>Nilai Asli</th>";
            echo "<th>Nilai Format waktu</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            echo "<tr>";
            echo "<td>gro_start_s</td>      <td>$gro_start</td>     <td>$gro_start_s</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>gro_start_s_0</td>    <td>$gro_start_0</td>   <td>$gro_start_s_0</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>gro_stop_s</td>       <td>$gro_stop</td>      <td>$gro_stop_s</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>gro_stop_s_0</td>     <td>$gro_stop_0</td>    <td>$gro_stop_s_0</td>";
            echo "</tr>";
            echo "</tbody>";
            echo "</table>";
        }
        else {
            echo 'This is a server using Linux!'. "<br>";
            echo "<table border=1 class='table'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Key</th>";
            echo "<th>Nilai Asli</th>";
            echo "<th>Nilai Format waktu</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            echo "<tr>";
            echo "<td>gro_start_s</td>      <td>$gro_start</td>     <td>$gro_start_s</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>gro_start_s_0</td>    <td>$gro_start_0</td>   <td>$gro_start_s_0</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>gro_stop_s</td>       <td>$gro_stop</td>      <td>$gro_stop_s</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>gro_stop_s_0</td>     <td>$gro_stop_0</td>    <td>$gro_stop_s_0</td>";
            echo "</tr>";
            echo "</tbody>";
            echo "</table>";
        }
    }

    function cacheTab()
    {
        // $machine_id = detectMachineID();
        $machine_id = 0;
        $reg_tab_id = $_GET['tab'];
        $filename = __DIR__ . "/NonRest/" . "tab_" . $machine_id . ".json";
        $file = fopen($filename, "w+");
        $writed = fwrite($file, $reg_tab_id);
        fclose($file);
        $result = array(
            "active_tab" => $reg_tab_id,
            "status" => 1,
        );
        echo json_encode($result);
        }

    function cacheTabCheck()
    {
        $reg_tab_id = $_GET['tab'];
        // $machine_id = detectMachineID();
        $machine_id = 0;

//        $storage = fopen(__DIR__ . "/NonRest/" . "tab_" . $machine_id . ".txt", "r");
//        $txt = fread($storage, filesize(__DIR__ . "/NonRest/" . "tab_" . $machine_id . ".txt"));

        $txt = file_get_contents(__DIR__ . "/NonRest/" . "tab_" . $machine_id . ".json");

        $result = array(
            "this_tab" => $reg_tab_id,
            "active_tab" => $txt,
            "status" => 1,
        );
        echo json_encode($result);
    }

    public function cacheTabCheckLP()
    {
        $reg_tab_id = $_GET['tab'];
        // $machine_id = detectMachineID();
        $machine_id = 0;
        set_time_limit(0);
        $data_source_file = __DIR__ . "/NonRest/" . "tab_" . $machine_id . ".json";
        while (true) {
            $last_ajax_call = isset($_GET['timestamp']) ? (int)$_GET['timestamp'] : null;
            clearstatcache();
            $last_change_in_data_file = filemtime($data_source_file);
            if ($last_ajax_call == null || $last_change_in_data_file > $last_ajax_call) {
                $data = file_get_contents($data_source_file);
                $result = array(
                    "this_tab" => $reg_tab_id,
                    "active_tab" => json_decode($data),
                    "status" => 1,
                    "timestamp" => $last_change_in_data_file,
                );
                $json = json_encode($result);
                echo $json;
                break;
            }
            else{
                sleep(3);
                continue;
            }
        }
    }

    function checkDevRegis(){

        $req = $_REQUEST;

        $this->db->where('machine_id',$req['machine_id']);
        $tmpCab = $this->db->get('per_cabang_device');
        $cab = $tmpCab ? $tmpCab->result() : array();
//
        $this->db->where('machine_id',$req['machine_id']);
        $tmpDev = $this->db->get('data__tmp');
        $dev = $tmpDev ? $tmpDev->result() : array();

        $result = array(
            "status" => 1,
            "row" => count($dev),
            "dtime_reg" => isset($dev[0]->proposed_date) ? $dev[0]->proposed_date:0,
            "registered" => !empty($cab) ? count($cab[0]) : 0,
            "dataReg" => !empty($cab) ? $cab[0] : array(),
        );
        echo json_encode($result);
    }
}