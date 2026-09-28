<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once "Modul_Controller.php";

class TransaksiCrm extends Modul_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper("he_stepping");
        $this->load->helper("he_access_right");
        $this->load->helper("he_misc_helper");
        $this->load->library("MobileDetect");
        $this->load->helper("he_session_replacer");
        $this->load->model("Mdls/MdlCurrency");
        $this->load->helper('he_angka');
        $tmpJenis = $this->jenisTr;
        $this->allSteps = isset($this->configUi[$tmpJenis]['steps']) ? $this->configUi[$tmpJenis]['steps'] : array();
        $this->mobile = $this->config->item("mobile");
        /* ----------------------------------------------------------------------------------
          * loader cunstruk yg wajib ada
          * variabel-variabel bisa langsung dipangil, apa saja yang ada bisa dilihat didalamnya
          * ----------------------------------------------------------------------------------*/
        // require_once "_construct_file.php";
        //        arrPrint($this->session->login);
        $this->reject = true;
        $this->reject_all = true;
    }

    public function viewOrderCrmOld()
    {
// matiHere(__LINE__);
        $jenisTr = $this->jenisTr;
        $alowedAccess = alowedAccess(my_id(), $this->jenisTr);
        $aksesOn = $alowedAccess["akses"][$this->jenisTr];

        $this->load->model("Mdls/MdlCrmDataBridge");
        $this->load->model("Mdls/MdlCustomer");

        $m = new MdlCrmDataBridge();
        $c = new MdlCustomer();
        $m->addFilter("qty_saldo >'0'");
//        $m->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        $tmp = $m->lookUpAll()->result();
//        cekLime($this->db->last_query());
//        arrprint($tmp);
        $historyData = array();
        $selectFields = array(
            "dtime" => "order date",
            "crm_domain" => "crm",
            "customer_id" => "customer",
            "transaksi_nilai" => "amount",
            "dppppn" => "tax basis",
            "ppn" => "tax",
            "total" => "total",
            "action" => "action",
        );
        $selectItemFields = array(
            "produk_id" => "crm",
            "produk_nama" => "customer",
            "produk_ord_jml" => "customer",
            "produk_ord_hrg" => "customer",
//            "grandtotal" => "customer",
        );

        $dataCustomers = $c->lookUpAll()->result();
//        arrprint($dataCustomers);
        $customer=array();
        foreach ($dataCustomers as $dataCustomers_0){
            $customer[$dataCustomers_0->id]=array(
                "nama"=>$dataCustomers_0->nama,
                "alamat"=>$dataCustomers_0->alamat_1,
            );
        }
//        arrprint($customer);
        $main = array();
        if (count($tmp) > 0) {
            $maindatas = array();
            $allTmpCrm = array();
            foreach ($tmp as $tmp_0) {

                $allTmpCrm[$tmp_0->estimate_id][] = (array)$tmp_0;
                $link = MODUL_PATH . "Create/previewCrm/" . $this->jenisTr . "/".$tmp_0->estimate_id;
//                $total_order += $tmp_0->produk_ord_jml * $tmp_0->produk_ord_hrg;
                if($tmp_0->customer_id > 0){
                    $customer_name = $customer[$tmp_0->customer_id]["nama"] ;
                    $btn_disable="";
                }
                else{
                    //cek dari register
                    $ref_id = $tmp_0->client_id;
                    $tmp_reg = $c->getRequestCustomerAll($ref_id)->result();
                    $customer_name = $tmp_reg[0]->nama ."(data belum diotorisasi)";
                    $btn_disable ="disabled";
                }
                $items[$tmp_0->estimate_id][] = (array)$tmp_0;
                $maindatas[$tmp_0->estimate_id]=array(
                    "dtime" => formatField("date",$tmp_0->dtime),
                    "estimate_id" => formatField("estimate_id",$tmp_0->estimate_id),
                    "crm_domain" => formatField("crm_domain",$tmp_0->crm_domain),
                    "customer_id" => formatField("customer_id",$customer_name),
                    "action"=>"<button type='button' $btn_disable class='btn btn-xs btn-primary' onclick=\"showModal('" . $link . "','view CRM Orders')\">followup</button>",
//                    "action"=>"<button type='button' class='btn btn-xs btn-primary' onclick=\"$show_modal\">followup</button>",
                );


                //JIKA ADA AKSES
                if (array_key_exists("11", $aksesOn)) {
                    $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
                    $isDisableMakeTrans = isset($this->configUi[$this->jenisTr]['isDisableMakeTrans']) ? $this->configUi[$this->jenisTr]['isDisableMakeTrans'] : false;

                    if (array_key_exists($this->jenisTr, $createIndexes)) {
                        $targetUrl = MODUL_PATH . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
                    }
                    else {
                        $targetUrl = MODUL_PATH . "Create/index/" . $this->jenisTr;
                    }

                    if ($isDisableMakeTrans) {

                    }
                    else {
                        //JIKA POSISI DI TRANSAKSI INDEX
                        $referer = $_SERVER['HTTP_REFERER'];
                        $cek = strpos($referer, 'Transaksi/index') !== false;

                        if($cek){
                            $maindatas[$tmp_0->estimate_id]['action'] = "<button $btn_disable type='button' class='btn btn-xs btn-warning' onclick=\"indexToCreate('$link')\">followup</button>";
                        }
                    }
                }
                else{
                    $maindatas[$tmp_0->estimate_id]['action'] = "<button type='button' $btn_disable class='btn btn-xs btn-default'>followup</button>";
                }

            }
//arrPrint($maindatas);
            foreach($allTmpCrm as $es_id =>$esData){
//                $link = MODUL_PATH . "Create/previewCrm/" . $this->jenisTr . "/".$es_id;
                $total_order = 0;
                foreach($esData as $esData_0){
                    $total_order += $esData_0["produk_ord_hrg"]*$esData_0["produk_ord_jml"];
                }
                $maindatas[$es_id]["transaksi_nilai"]=formatField("transaksi_nilai",$total_order);
                $maindatas[$es_id]["dppppn"]=formatField("transaksi_nilai",$total_order*(11/12));
                $maindatas[$es_id]["ppn"]=formatField("transaksi_nilai",$total_order*(11/100));
                $maindatas[$es_id]["total"]=formatField("netto",$total_order+($total_order*(11/100)));


            }
        }

        $data = array(
            "mode"=>"view_crm",
            "arrayOnProgress"=>$maindatas,
            "items"=>$items,
            "arrayProgressLabels"=>$selectFields,
            "itemFields"=>$selectItemFields,
        );
        $this->load->view("transaksi", $data);
//        cekHitam($this->db->last_query());
//        arrPrint($tmp);
    }

    public function viewOrderCrm()
    {
        $jenisTr = $this->jenisTr;
        $alowedAccess = alowedAccess(my_id(), $this->jenisTr);
        $aksesOn = isset($alowedAccess["akses"][$this->jenisTr][11]) ? $alowedAccess["akses"][$this->jenisTr][11] : false; // allowcreate

        // Load model CRM
        $this->load->model("MdlTransaksiCrm");
        $crmModel = $this->MdlTransaksiCrm;

        $orders = $crmModel->getCrmOrdersWithSaldo();
        $productList = $crmModel->buildProductListMapping($orders);

        $selectFields = array(
            "dtime" => "order date",
            "crm_domain" => "crm",
            "customer_id" => "customer",
            "bukti_order" => "bukti order",
            "transaksi_nilai" => "amount",
            "dppppn" => "tax basis",
            "ppn" => "tax",
            "total" => "total",
            "action" => "action",
        );
        $selectItemFields = array(
            "produk_id" => "crm",
            "produk_nama" => "customer",
            "produk_ord_jml" => "customer",
            "produk_ord_hrg" => "customer",
        );

        // Get approved and pending customers
        $dataCustomers = $crmModel->getApprovedCustomers();
        $dataPreCustomers = $crmModel->getPendingCustomers();

        $customer = $crmModel->buildCustomerMapping($dataCustomers);
        $preCustomer = $crmModel->buildPreCustomerMapping($dataPreCustomers);

        $maindatas = array();
        $items = array();
        $arrayOnprogressMarking = array();
        $groupedOrders = $crmModel->groupOrdersByEstimate($orders);

        $isDisableMakeTrans = isset($this->configUi[$this->jenisTr]['isDisableMakeTrans']) ? $this->configUi[$this->jenisTr]['isDisableMakeTrans'] : false;

        foreach ($groupedOrders as $estimateId => $groupData) {
            $firstOrder = $groupData['meta'];

            $items[$estimateId] = $groupData['orders'];

            // Resolve customer name with approved/pending mapping
            $customerName = $crmModel->resolveCustomerName($firstOrder, $customer, $preCustomer);

            // Cache fallback lookups
            if (!empty($firstOrder->customer_id) && !isset($customer[$firstOrder->customer_id]) && !empty($customerName)) {
                $customer[$firstOrder->customer_id] = array("nama" => $customerName, "alamat" => "");
            }

            $productPayload = isset($productList[$estimateId]) ? $productList[$estimateId] : array();

           // $target = MODUL_PATH . "_processSelectProduct/selectCrm/" . $this->jenisTr . "/" . $estimateId . "?pihakID=" . $firstOrder->customer_id . "&enc=" . blobEncode($productPayload);

            $target = MODUL_PATH . "Create/previewCrm/" . $this->jenisTr . "/".$estimateId;

            $needsApproval = (empty($firstOrder->customer_id) || $firstOrder->customer_id == 0);
            // Samakan flow followup dengan halaman Create, agar Transaksi hanya memuat list CRM.
            $actionContext = 'result';
            // Ikuti permintaan UI: tombol FOLLOWUP selalu kuning.
            $buttonVariant = 'warning';
            $actionAllowed = $aksesOn && !$isDisableMakeTrans;

            $maindatas[$estimateId] = array(
                "dtime" => formatField("date", $firstOrder->dtime),
                "estimate_id" => formatField("estimate_id", $firstOrder->estimate_id),
                "crm_domain" => formatField("crm_domain", $firstOrder->crm_domain),
                "customer_id" => formatField("customer_id", $customerName),
                "bukti_order" => !empty($firstOrder->bukti_order) ? '<button type="button" data-url="' . $firstOrder->bukti_order . '" class="btn btn-xs btn-info crm-lihat-bukti"><i class="fa fa-file"></i> Lihat Bukti</button>' : '-',
                "transaksi_nilai" => isset($groupData['totals']['transaksi_nilai']) ? formatField("transaksi_nilai", $groupData['totals']['transaksi_nilai']) : 0,
                "dppppn" => isset($groupData['totals']['dppppn']) ? formatField("transaksi_nilai", $groupData['totals']['dppppn']) : 0,
                "ppn" => isset($groupData['totals']['ppn']) ? formatField("transaksi_nilai", $groupData['totals']['ppn']) : 0,
                "total" => isset($groupData['totals']['total']) ? formatField("netto", $groupData['totals']['total']) : 0,
                "action" => array(
                    "label" => "followup",
                    "target" => $target,
                    "context" => $actionContext,
                    "needs_approval" => $needsApproval,
                    "estimate_id" => $estimateId,
                    "client_id" => $firstOrder->client_id,
                    "customer_id" => (int)$firstOrder->customer_id,
                    "variant" => $buttonVariant,
                    "allowed" => $actionAllowed,
                ),
            );

            if ($needsApproval) {
                $arrayOnprogressMarking[$estimateId]['style'] = "background-color: #fff3cd; border-left: 4px solid #ff9800;";
            }
        }

        $approvalConfig = array(
            "checkStatusUrl" => base_url() . "penjualan/CustomerApprovalApi/checkStatus",
            "lookupReferensiBase" => ADM_DOMAIN . "eusvc/Customers/lookUpCustomerReferensi/",
            "saveCustomerUrl" => base_url() . "penjualan/CustomerApprovalApi/saveCustomer",
            "autoApproveUrl"  => base_url() . "penjualan/CustomerApprovalApi/autoApproveAll",
        );

        $data = array(
            "mode" => "view_crm",
            "arrayOnProgress" => $maindatas,
            "items" => $items,
            "arrayProgressLabels" => $selectFields,
            "itemFields" => $selectItemFields,
            "arrayOnprogressMarking" => $arrayOnprogressMarking,
            "crmApprovalConfig" => $approvalConfig,
            "crmShowLegend" => true,
        );

        $this->load->view("transaksi", $data);
    }

    public function checkCustomerRegisterStatus()
    {
        header('Content-Type: application/json');
        // Get client_id from POST or GET
        $client_id = $this->input->post('client_id') ? $this->input->post('client_id') : $this->input->get('client_id');

        if (empty($client_id)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'client_id is required'
            ]);
            exit;
        }

        // Load model
        $this->load->model('Mdls/MdlCustomer');
        $this->load->model('Mdls/MdlTransaksiCrm');

        $crmModel = new MdlTransaksiCrm();

        // Query per_customers_register berdasarkan referensi_id = client_id
        $regResult = $crmModel->getCustomerRegisterIdByClientId($client_id);

        if (!$regResult) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Data customer tidak ditemukan di per_customers_register'
            ]);
            exit;
        }

        // Return data
        echo json_encode([
            'status' => 'success',
            'trash' => (int)$regResult->trash,
            'referensi_id' => $regResult->referensi_id,
            'customer_id' => isset($regResult->id) ? $regResult->id : null,
            'nama' => isset($regResult->nama) ? $regResult->nama : '',
            'message' => 'Data ditemukan'
        ]);
        exit;
    }

    /**
     * [ANTIGRAVITY] Save customer data from DC to per_pihak_lain
     * Endpoint untuk save data dari API lookUpCustomeReferensi ke per_pihak_lain
     *
     * @param array $customerData - Data customer dari API response
     * @return JSON {status: "success"|"error", customer_id: int, message: string}
     */
    public function saveCustomerToPihakLain()
    {
        header('Content-Type: application/json');

        // Get data from POST
        $customerData = $this->input->post('customer_data');
        $client_id = $this->input->post('client_id');

        if (empty($customerData) || empty($client_id)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'customer_data and client_id are required'
            ]);
            exit;
        }

        // Decode jika berupa JSON string
        if (is_string($customerData)) {
            $customerData = json_decode($customerData, true);
        }

        // Load model
        $this->load->model('Mdls/MdlCustomer');
        $customerModel = new MdlCustomer();

        // Start transaction
        $this->db->trans_start();

        try {
            // Prepare data untuk per_pihak_lain (per_customers)
            $insertData = [
                'nama' => isset($customerData['nama']) ? $customerData['nama'] : '',
                'alamat_1' => isset($customerData['alamat']) ? $customerData['alamat'] : '',
                'tlp_1' => isset($customerData['tlp_1']) ? $customerData['tlp_1'] : '',
                'email' => isset($customerData['email']) ? $customerData['email'] : '',
                'member_id' => isset($customerData['member_id']) ? $customerData['member_id'] : '',
                'dc_id' => isset($customerData['dc_id']) ? $customerData['dc_id'] : null,
                'trash' => 0, // Active customer
                'dtime' => date('Y-m-d H:i:s'),
            ];

            // Insert ke per_customers (per_pihak_lain)
            $customer_id = $customerModel->addCustomer($insertData);

            if (!$customer_id) {
                throw new Exception('Failed to insert customer to per_customers');
            }

            // Update per_crm_orders.customer_id
            $this->db->where('client_id', $client_id);
            $this->db->update('per_crm_orders', ['customer_id' => $customer_id]);

            // Update per_customers_register.trash = 1 (approved)
            $this->db->where('referensi_id', $client_id);
            $this->db->update('per_customers_register', ['trash' => 1]);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Transaction failed');
            }

            echo json_encode([
                'status' => 'success',
                'customer_id' => $customer_id,
                'message' => 'Data customer berhasil disimpan'
            ]);
            exit;

        } catch (Exception $e) {
            $this->db->trans_rollback();
            echo json_encode([
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ]);
            exit;
        }
    }

}






