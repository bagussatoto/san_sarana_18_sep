<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CustomerApprovalApi Controller
 *
 * PURE API Controller - NO HTML/VIEW output
 * Handles customer approval workflow for CRM Follow Up
 *
 * @author ANTIGRAVITY Implementation
 * @date 2025-12-12
 */
class CustomerApprovalApi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Enable error display for debugging (temporary)
        ini_set('display_errors', 1);
        error_reporting(E_ALL);

        // Set JSON header globally for all methods
        @header('Content-Type: application/json');
    }

    /**
     * Check customer register approval status
     *
     * Endpoint: POST /penjualan/CustomerApprovalApi/checkStatus
     *
     * @param POST client_id - ID dari tabel per_crm_orders
     * @return JSON {status, trash, referensi_id, customer_id, nama, message}
     */
    public function checkStatus()
    {
        try {
            // Clean output buffer to ensure no HTML
            if (ob_get_level() > 0) {
                ob_clean();
            }

            // Get client_id from POST or GET
            $client_id = $this->input->post('client_id') ? $this->input->post('client_id') : $this->input->get('client_id');

            if (empty($client_id)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'client_id is required'
                ]);
                exit;
            }

            // Load model on-demand from application/models
            $this->load->model('MdlTransaksiCrm');
            $crmModel = $this->MdlTransaksiCrm;

            // Query per_customers_register berdasarkan referensi_id = client_id
            $regResult = $crmModel->getCustomerRegisterIdByClientId($client_id);

            if (!$regResult) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Data customer tidak ditemukan di per_customers_register',
                    'client_id' => $client_id
                ]);
                exit;
            }

            // Return data
            $customerName = isset($regResult->nama) && trim($regResult->nama) !== "" ? $regResult->nama : "";
            if ($customerName === "") {
                $customerName = !empty($regResult->email) ? $regResult->email : (!empty($regResult->tlp_1) ? $regResult->tlp_1 : "Lead CRM #" . $regResult->referensi_id);
            }
            echo json_encode([
                'status' => 'success',
                'trash' => (int)$regResult->trash,
                'referensi_id' => $regResult->referensi_id,
                'customer_id' => isset($regResult->id) ? $regResult->id : null,
                'nama' => $customerName,
                'message' => 'Data ditemukan'
            ]);
            exit;

        }
        catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Exception: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            exit;
        }
    }
    /**
     * Save customer data from DC to per_pihak_lain
     *
     * Endpoint: POST /penjualan/CustomerApprovalApi/saveCustomer
     *
     * @param POST customer_data - Data customer dari API response
     * @param POST client_id - client_id dari per_crm_orders
     * @return JSON {status, customer_id, message}
     */
    public function saveCustomer()
    {
        try {
            // Enable error display for debugging
            ini_set('display_errors', 1);
            error_reporting(E_ALL);

            // Clean output buffer to ensure no HTML
            if (ob_get_level() > 0) {
                ob_clean();
            }

            // Get data from POST
            $customerData = $this->input->post('customer_data');
            $client_id = $this->input->post('client_id');
            $bill = $this->input->post('bill');
            $shipment = $this->input->post('shipment');

            if (empty($customerData) || empty($client_id) || empty($bill) || empty($shipment)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'customer_data and client_id are required',
                    'debug' => [
                        'customerData' => $customerData,
                        'client_id' => $client_id,
                        'bill' => $bill,
                        'shipment' => $shipment
                    ]
                ]);
                exit;
            }

            // Decode jika berupa JSON string
            if (is_string($customerData)) {
                $customerData = json_decode($customerData, true);
            }

            // Decode jika berupa JSON string
            if (is_string($bill)) {
                $bill = json_decode($bill, true);
            }

            // Decode jika berupa JSON string
            if (is_string($shipment)) {
                $shipment = json_decode($shipment, true);
            }

            // Load model on-demand
            $this->load->model('Mdls/MdlCustomer');
            $customerModel = $this->MdlCustomer;

            // Start transaction
            $this->db->trans_start();

            // Prepare data untuk per_pihak_lain (per_customers)
            // Map field dari API response ke database schema per_pihak_lain
            $insertData = [
                'nama' => isset($customerData['nama']) ? $customerData['nama'] : '',
                'alamat_1' => isset($customerData['alamat']) ? $customerData['alamat'] : '',
                'tlp_1' => isset($customerData['phone']) ? $customerData['phone'] : (isset($customerData['tlp_1']) ? $customerData['tlp_1'] : ''),
                'email' => isset($customerData['email']) ? $customerData['email'] : '',
                'member_id' => isset($customerData['member_id']) ? $customerData['member_id'] : '',
                'dc_id' => isset($customerData['id']) ? $customerData['id'] : null,
                'propinsi' => isset($customerData['propinsi']) ? $customerData['propinsi'] : (isset($customerData['provinsi']) ? $customerData['provinsi'] : ''),
                'kabupaten' => isset($customerData['kabupaten']) ? $customerData['kabupaten'] : '',
                'npwp' => isset($customerData['npwp']) ? $customerData['npwp'] : '',
                'no_ktp' => isset($customerData['nik']) ? $customerData['nik'] : '', // nik maps to no_ktp
                'is_customer' => '1', // Flag bahwa ini customer (bukan supplier)
                'trash' => 0, // Active customer
                'dtime' => date('Y-m-d H:i:s'),
            ];

            // Insert ke per_pihak_lain
            $customer_id = $customerModel->addCustomer($insertData);

            if (!$customer_id || is_string($customer_id)) {
                $error = $this->db->error();
                throw new Exception($customer_id ? $customer_id : 'Failed to insert customer. DB Error: ' . json_encode($error));
            }

            // Update penjualan_transaksi_data_crm_bridge.customer_id
            $this->db->where('client_id', $client_id);
            $this->db->update('penjualan_transaksi_data_crm_bridge', ['customer_id' => $customer_id]);
            $error = $this->db->error();
            if ($error['code'] != 0) {
                throw new Exception('Failed to update penjualan_transaksi_data_crm_bridge: ' . $error['message']);
            }

            // Update per_customers_register.trash = 1 (approved)
            $this->db->where('referensi_id', $client_id);
            $this->db->update('per_customers_register', ['trash' => 1]);
            $error = $this->db->error();
            if ($error['code'] != 0) {
                throw new Exception('Failed to update per_customers_register: ' . $error['message']);
            }

            $ctrlName = "Customer";
            $className = "Mdl" . $ctrlName;
            $dcomConf = isset($this->config->item("dataPostProcessors")[$className]) ? $this->config->item("dataPostProcessors")[$className][0] : array();//cek ada Dcomnya tidak

            //region takbahan Dcom
            if (sizeof($dcomConf) > 0) {
                $data = $insertData;
                $dcom = true;
                if (isset($data['dc_id'])) {
                    $dc_id = $data['dc_id'];
                    $data_address = $shipment;
                    $data_billing = $bill;
                    if (count($data_address) > 0) {
                        $dcom = false;
                        $kolomKonversiAddr = [
                            "telp" => "tlp",
                            "telp2" => "tlp_2",
                            "telp3" => "tlp_3",
                        ];
                        $this->load->Model("Mdls/MdlCustomerAddress");
                        $m = new MdlCustomerAddress();
                        unset($data_address["id"]);
                        $data_address["customer"] = $customer_id;
                        $data_address["extern_id"] = $customer_id;
                        $data_baru = [];
                        foreach ($data_address as $kolom_addr => $kolom_nilai) {
                            if(key_exists($kolom_addr,$kolomKonversiAddr)){
                                $data_baru[$kolomKonversiAddr[$kolom_addr]] = $kolom_nilai;
                            }
                            else{
                                $data_baru[$kolom_addr] = $kolom_nilai;
                            }
                        }
                        $m->addData($data_baru);
                    }
                    else {
                        //default
                    }

                    if (count($data_billing) > 0) {
                        $this->load->Model("Mdls/MdlCustomerBillAddress");
                        $m = new MdlCustomerBillAddress();
                        unset($data_billing["id"]);
                        $data_billing["customer"] = $customer_id;
                        $data_billing["extern_id"] = $customer_id;
                        $data_baru = [];
                        foreach ($data_billing as $kolom_addr => $kolom_nilai) {
                            if(key_exists($kolom_addr,$kolomKonversiAddr)){
                                $data_baru[$kolomKonversiAddr[$kolom_addr]] = $kolom_nilai;
                            }
                            else{
                                $data_baru[$kolom_addr] = $kolom_nilai;
                            }
                        }
                        $m->addData($data_baru);
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

//            matiHere("mati dulu line: " . __LINE__);
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $error = $this->db->error();
                throw new Exception('Transaction failed. DB Error: ' . json_encode($error));
            }

            echo json_encode([
                'status' => 'success',
                'customer_id' => $customer_id,
                'message' => 'Data customer berhasil disimpan',
                'debug' => [
                    'insertData' => $insertData,
                    'client_id' => $client_id,
                    'bill' => $bill,
                    'shipment' => $shipment
                ]
            ]);
            exit;

        }
        catch (Exception $e) {
            if ($this->db->trans_status() !== FALSE) {
                $this->db->trans_rollback();
            }
            
            echo json_encode([
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => explode("\n", $e->getTraceAsString())
            ]);
            exit;
        }
    }

    /**
     * Auto-Approve semua customer CRM yang belum approved (customer_id = 0)
     *
     * Endpoint: GET /penjualan/CustomerApprovalApi/autoApproveAll
     *
     * Alur:
     * 1. Ambil semua client_id unik dari penjualan_transaksi_data_crm_bridge di mana customer_id = 0
     * 2. Untuk tiap client_id, cek per_customers_register (referensi_id)
     * 3. Panggil API DC: lookUpCustomerReferensi/{estimate_id}?ref_id={referensi_id}
     * 4. Jika ditemukan (status 200) → insert per_pihak_lain, update crm_bridge, tandai register
     * 5. Jika 404 → skip (belum diapprove di Holding)
     *
     * @return JSON {status, approved, skipped, failed, detail[]}
     */
    public function autoApproveAll()
    {
        try {
            if (ob_get_level() > 0) {
                ob_clean();
            }

            $this->load->model('MdlTransaksiCrm');
            $this->load->model('Mdls/MdlCustomer');

            $crmModel      = $this->MdlTransaksiCrm;
            $customerModel = $this->MdlCustomer;

            // 1. Ambil semua baris crm_bridge yang customer_id = 0
            $this->db->distinct();
            $this->db->select('client_id, estimate_id');
            $this->db->from('penjualan_transaksi_data_crm_bridge');
            $this->db->where('customer_id', 0);
            $this->db->where('client_id IS NOT NULL', null, false);
            $this->db->where("TRIM(client_id) <> ''", null, false);
            $query = $this->db->get();
            if ($query === false) {
                $db_err = $this->db->error();
                echo json_encode([
                    'status'   => 'error',
                    'message'  => 'DB Error: ' . $db_err['message'] . ' (Code: ' . $db_err['code'] . ')',
                ]);
                exit;
            }
            $pendingRows = $query->result();

            if (empty($pendingRows)) {
                echo json_encode([
                    'status'   => 'success',
                    'message'  => 'Tidak ada customer yang perlu diapprove',
                    'approved' => 0,
                    'skipped'  => 0,
                    'failed'   => 0,
                    'detail'   => [],
                ]);
                exit;
            }

            // Kelompokkan per client_id (ambil satu estimate_id sebagai referensi)
            $clientMap = [];
            foreach ($pendingRows as $row) {
                $cid = trim((string)$row->client_id);
                if ($cid !== '' && !isset($clientMap[$cid])) {
                    $clientMap[$cid] = trim((string)$row->estimate_id);
                }
            }

            $approved = 0;
            $skipped  = 0;
            $failed   = 0;
            $detail   = [];

            $admDomain = defined('ADM_DOMAIN') ? rtrim(ADM_DOMAIN, '/') . '/' : '';

            foreach ($clientMap as $clientId => $estimateId) {
                // 2. Cek per_customers_register
                $regResult = $crmModel->getCustomerRegisterIdByClientId($clientId);

                if (!$regResult) {
                    // Tidak ada di register, skip
                    $skipped++;
                    $detail[] = [
                        'client_id'   => $clientId,
                        'status'      => 'skipped',
                        'reason'      => 'Tidak ditemukan di per_customers_register',
                    ];
                    continue;
                }

                // Sudah pernah di-approve (trash=1)
                if ((int)$regResult->trash === 1) {
                    // Mungkin crm_bridge belum ter-update, coba sync customer_id dari per_pihak_lain via dc_id
                    $skipped++;
                    $detail[] = [
                        'client_id'   => $clientId,
                        'status'      => 'skipped',
                        'reason'      => 'Sudah diapprove sebelumnya (trash=1)',
                    ];
                    continue;
                }

                $referensiId = isset($regResult->referensi_id) ? (string)$regResult->referensi_id : (string)$clientId;
                $customerNama = isset($regResult->nama) ? $regResult->nama : '';

                // 3. Panggil API DC: lookUpCustomerReferensi
                $apiUrl = $admDomain . 'eusvc/Customers/lookUpCustomerReferensi/' . rawurlencode((string)$estimateId) . '?ref_id=' . rawurlencode($referensiId);

                $rawResponse = null;
                if (function_exists('call_curl')) {
                    $rawResponse = call_curl($apiUrl);
                } else {
                    $raw = @file_get_contents($apiUrl);
                    $rawResponse = json_decode($raw, true);
                }

                if (!is_array($rawResponse)) {
                    $failed++;
                    $detail[] = [
                        'client_id' => $clientId,
                        'nama'      => $customerNama,
                        'status'    => 'failed',
                        'reason'    => 'Gagal memanggil API DC atau response tidak valid',
                        'api_url'   => $apiUrl,
                    ];
                    continue;
                }

                $apiStatus = isset($rawResponse['status']) ? (string)$rawResponse['status'] : '';

                // 404 = belum diapprove di Holding
                if ($apiStatus === '404' || $apiStatus === 404) {
                    $skipped++;
                    $detail[] = [
                        'client_id' => $clientId,
                        'nama'      => $customerNama,
                        'status'    => 'skipped',
                        'reason'    => 'Belum diapprove di Holding (API 404)',
                        'api_url'   => $apiUrl,
                    ];
                    continue;
                }

                if ($apiStatus !== '200' && $apiStatus !== 200) {
                    $failed++;
                    $detail[] = [
                        'client_id'  => $clientId,
                        'nama'       => $customerNama,
                        'status'     => 'failed',
                        'reason'     => 'API DC mengembalikan status tidak dikenal: ' . $apiStatus,
                        'api_url'    => $apiUrl,
                    ];
                    continue;
                }

                // 4. Status 200 — parse data customer
                $dataArr = [];
                if (isset($rawResponse['data']) && is_array($rawResponse['data'])) {
                    $dataArr = isset($rawResponse['data'][0]) ? $rawResponse['data'][0] : $rawResponse['data'];
                }

                if (empty($dataArr)) {
                    $failed++;
                    $detail[] = [
                        'client_id' => $clientId,
                        'nama'      => $customerNama,
                        'status'    => 'failed',
                        'reason'    => 'Data customer kosong di response API',
                        'api_url'   => $apiUrl,
                    ];
                    continue;
                }

                $bill     = isset($rawResponse['bill'])     && is_array($rawResponse['bill'])     ? $rawResponse['bill']     : [];
                $shipment = isset($rawResponse['shipment']) && is_array($rawResponse['shipment']) ? $rawResponse['shipment'] : [];

                // 5. Lakukan approval: insert ke per_pihak_lain
                $this->db->trans_start();

                $insertData = [
                    'nama'       => isset($dataArr['nama'])      ? $dataArr['nama']      : (isset($dataArr['name']) ? $dataArr['name'] : ''),
                    'alamat_1'   => isset($dataArr['alamat'])     ? $dataArr['alamat']    : (isset($dataArr['alamat_1']) ? $dataArr['alamat_1'] : ''),
                    'tlp_1'      => isset($dataArr['phone'])      ? $dataArr['phone']     : (isset($dataArr['tlp_1']) ? $dataArr['tlp_1'] : ''),
                    'email'      => isset($dataArr['email'])      ? $dataArr['email']     : '',
                    'member_id'  => isset($dataArr['member_id'])  ? $dataArr['member_id'] : '',
                    'dc_id'      => isset($dataArr['id'])         ? $dataArr['id']        : (isset($dataArr['dc_id']) ? $dataArr['dc_id'] : null),
                    'propinsi'   => isset($dataArr['propinsi'])   ? $dataArr['propinsi']  : (isset($dataArr['provinsi']) ? $dataArr['provinsi'] : ''),
                    'kabupaten'  => isset($dataArr['kabupaten'])  ? $dataArr['kabupaten'] : '',
                    'npwp'       => isset($dataArr['npwp'])       ? $dataArr['npwp']      : '',
                    'no_ktp'     => isset($dataArr['nik'])        ? $dataArr['nik']       : '',
                    'is_customer' => '1',
                    'trash'      => 0,
                    'dtime'      => date('Y-m-d H:i:s'),
                ];

                $newCustomerId = $customerModel->addCustomer($insertData);

                if (!$newCustomerId || is_string($newCustomerId)) {
                    $this->db->trans_rollback();
                    $failed++;
                    $detail[] = [
                        'client_id' => $clientId,
                        'nama'      => $customerNama,
                        'status'    => 'failed',
                        'reason'    => 'Gagal insert ke per_pihak_lain: ' . (is_string($newCustomerId) ? $newCustomerId : 'unknown'),
                    ];
                    continue;
                }

                // Update crm_bridge: set customer_id
                $this->db->where('client_id', $clientId);
                $this->db->update('penjualan_transaksi_data_crm_bridge', ['customer_id' => $newCustomerId]);
                $err = $this->db->error();
                if ($err['code'] != 0) {
                    $this->db->trans_rollback();
                    $failed++;
                    $detail[] = [
                        'client_id' => $clientId,
                        'nama'      => $customerNama,
                        'status'    => 'failed',
                        'reason'    => 'Gagal update crm_bridge: ' . $err['message'],
                    ];
                    continue;
                }

                // Tandai per_customers_register sebagai selesai (trash = 1)
                $this->db->where('referensi_id', $clientId);
                $this->db->update('per_customers_register', ['trash' => 1]);
                $err = $this->db->error();
                if ($err['code'] != 0) {
                    $this->db->trans_rollback();
                    $failed++;
                    $detail[] = [
                        'client_id' => $clientId,
                        'nama'      => $customerNama,
                        'status'    => 'failed',
                        'reason'    => 'Gagal update per_customers_register: ' . $err['message'],
                    ];
                    continue;
                }

                // Simpan alamat pengiriman & tagihan jika ada
                if (!empty($shipment) && is_array($shipment)) {
                    $shipItem = isset($shipment[0]) ? $shipment[0] : $shipment;
                    if (is_array($shipItem) && !empty($shipItem)) {
                        $this->load->Model('Mdls/MdlCustomerAddress');
                        $mAddr = new MdlCustomerAddress();
                        $kolomKonversi = ['telp' => 'tlp', 'telp2' => 'tlp_2', 'telp3' => 'tlp_3'];
                        unset($shipItem['id']);
                        $shipItem['customer']  = $newCustomerId;
                        $shipItem['extern_id'] = $newCustomerId;
                        $dataBaru = [];
                        foreach ($shipItem as $k => $v) {
                            $dataBaru[isset($kolomKonversi[$k]) ? $kolomKonversi[$k] : $k] = $v;
                        }
                        $mAddr->addData($dataBaru);
                    }
                }

                if (!empty($bill) && is_array($bill)) {
                    $billItem = isset($bill[0]) ? $bill[0] : $bill;
                    if (is_array($billItem) && !empty($billItem)) {
                        $this->load->Model('Mdls/MdlCustomerBillAddress');
                        $mBill = new MdlCustomerBillAddress();
                        $kolomKonversi = ['telp' => 'tlp', 'telp2' => 'tlp_2', 'telp3' => 'tlp_3'];
                        unset($billItem['id']);
                        $billItem['customer']  = $newCustomerId;
                        $billItem['extern_id'] = $newCustomerId;
                        $dataBaru = [];
                        foreach ($billItem as $k => $v) {
                            $dataBaru[isset($kolomKonversi[$k]) ? $kolomKonversi[$k] : $k] = $v;
                        }
                        $mBill->addData($dataBaru);
                    }
                }

                $this->db->trans_complete();

                if ($this->db->trans_status() === false) {
                    $failed++;
                    $detail[] = [
                        'client_id' => $clientId,
                        'nama'      => $customerNama,
                        'status'    => 'failed',
                        'reason'    => 'Transaksi database gagal saat commit',
                    ];
                    continue;
                }

                $approved++;
                $detail[] = [
                    'client_id'   => $clientId,
                    'nama'        => isset($insertData['nama']) ? $insertData['nama'] : $customerNama,
                    'customer_id' => $newCustomerId,
                    'status'      => 'approved',
                ];
            }

            echo json_encode([
                'status'   => 'success',
                'message'  => "$approved customer berhasil diapprove, $skipped dilewati, $failed gagal",
                'approved' => $approved,
                'skipped'  => $skipped,
                'failed'   => $failed,
                'detail'   => $detail,
            ]);
            exit;

        } catch (\Throwable $e) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'Throwable: ' . $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);
            exit;
        }

    }
}
