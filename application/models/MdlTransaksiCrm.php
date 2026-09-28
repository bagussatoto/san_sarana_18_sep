<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model untuk menangani operasi CRM Orders
 * Created: 2025-11-24
 * Purpose: Memisahkan query database dari controller Transaksi::viewOrderCrm()
 * 
 * Pattern: Menggunakan existing models (MdlCrmDataBridge, MdlCustomer) dengan addFilter()
 * untuk konsistensi dengan codebase pattern MdlMother
 * 
 * @refactored_from Transaksi::viewOrderCrm() Line 5553-5960
 * @author AI Refactoring Agent
 */
class MdlTransaksiCrm extends CI_Model
{
    private $mdlCrmBridge;
    private $mdlCustomer;

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Mdls/MdlCrmDataBridge");
        $this->load->model("Mdls/MdlCustomer");
        
        // Cache model instances untuk reusability
        $this->mdlCrmBridge = new MdlCrmDataBridge();
        $this->mdlCustomer = new MdlCustomer();
    }

    /**
     * Mengambil CRM orders dengan qty_saldo > 0
     * 
     * Pattern: Menggunakan MdlCrmDataBridge->addFilter() sesuai codebase pattern
     * 
     * @refactored_from Line 5564-5569
     * @return array
     */
    public function getCrmOrdersWithSaldo()
    {
        $m = new MdlCrmDataBridge();
        $m->addFilter("qty_saldo >'0'");
        return $m->lookUpAll()->result();
    }

    /**
     * Mengambil semua approved customers (per_customers)
     * 
     * Pattern: Menggunakan MdlCustomer dengan addFilter() alih-alih raw query
     * 
     * @refactored_from Line 5603-5607
     * @return array
     */
    public function getApprovedCustomers()
    {
        // Gunakan MdlCustomer yang sudah punya filter trash='0' built-in
        $c = new MdlCustomer();
        // MdlCustomer sudah punya protected $filters = array("trash='0'")
        
        // Ambil semua data (sudah otomatis filter trash='0')
        return $c->lookupAll()->result();
    }

    /**
     * Mengambil pending customers (per_customers_register)
     * 
     * Pattern: Menggunakan MdlCustomer->tbl_request dengan addFilter()
     * 
     * @refactored_from Line 5610-5613
     * @return array
     */
    public function getPendingCustomers()
    {
        $c = new MdlCustomer();
        
        // Set table ke per_customers_register
        $c->setFilters(array());
        $c->setTableName($c->tbl_request);
        
        // Add filter trash='0' menggunakan addFilter pattern
        $c->addFilter("trash='0'");
        
        return $c->lookupAll()->result();
    }

    /**
     * Mengambil employees dengan role tertentu di cabang tertentu
     * 
     * Note: Tidak ada MdlEmployee, jadi tetap menggunakan direct query
     * Tapi menggunakan addFilter pattern untuk konsistensi
     * 
     * @refactored_from Line 5646-5656
     * @param int $cabangId Cabang ID (null untuk semua cabang)
     * @param int $limit Limit hasil
     * @return array
     */
    public function getEmployeesByRole($cabangId = null, $limit = 50)
    {

        // Direct query karena tidak ada MdlEmployee di codebase
        $this->db->select("nama, membership");
        $this->db->from("per_employee");
        $this->db->where("status", 1);
        $this->db->where("trash", 0);
        $this->db->where("ghost", 0);
        
        if ($cabangId && $cabangId != -1) {
            $this->db->where("cabang_id", $cabangId);
        }
        
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    /**
     * Mengambil customer register ID berdasarkan referensi_id (client_id)
     * 
     * Pattern: Menggunakan MdlCustomer dengan addFilter()
     * 
     * @refactored_from Line 5742-5749
     * @param string $clientId Client ID dari CRM
     * @return object|null
     */
    public function getCustomerRegisterIdByClientId($clientId)
    {
        if (empty($clientId)) {
            return null;
        }

        $c = new MdlCustomer();
        $c->setTableName($c->tbl_request); // per_customers_register
        $c->setFilters(array());
        $c->addFilter("referensi_id='$clientId'");
        $c->addFilter("trash='0'");
        
        $result = $c->lookupAll()->result();
        return !empty($result) ? $result[0] : null;
    }

    /**
     * Mengambil customer by ID (fallback query)
     * 
     * Pattern: Menggunakan MdlCustomer->lookupByID() method yang sudah ada
     * 
     * @refactored_from Line 5815-5825
     * @param int $customerId Customer ID
     * @return object|null
     */
    public function getCustomerById($customerId)
    {
        if (empty($customerId)) {
            return null;
        }

        // Gunakan method lookupByID yang sudah ada di MdlMother
        $c = new MdlCustomer();
        $result = $c->lookupByID($customerId)->result();
        
        return !empty($result) ? $result[0] : null;
    }

    /**
     * Helper: Build customer mapping array dari result customers
     * 
     * @refactored_from Line 5616-5621
     * @param array $dataCustomers Result dari getApprovedCustomers()
     * @return array
     */
    public function buildCustomerMapping($dataCustomers)
    {
        $customer = array();
        foreach ($dataCustomers as $dataCustomers_0) {
            $customer[$dataCustomers_0->id] = array(
                "nama" => $dataCustomers_0->nama,
                "alamat" => $dataCustomers_0->alamat_1,
            );
        }
        return $customer;
    }

    /**
     * Helper: Build pre-customer mapping array
     * 
     * @refactored_from Line 5623-5630
     * @param array $dataPreCustomers Result dari getPendingCustomers()
     * @return array
     */
    public function buildPreCustomerMapping($dataPreCustomers)
    {
        $preCustomer = array();
        foreach ($dataPreCustomers as $preCustomer_0) {
            $preCustomer[$preCustomer_0->referensi_id] = array(
                "nama" => $preCustomer_0->nama,
                "alamat" => $preCustomer_0->alamat_1,
            );
        }
        return $preCustomer;
    }

    /**
     * Helper: Build product list mapping dari CRM orders
     * 
     * @refactored_from Line 5570-5577
     * @param array $crmOrders Result dari getCrmOrdersWithSaldo()
     * @return array
     */
    public function buildProductListMapping($crmOrders)
    {
        $lsitProduk = array();
        foreach ($crmOrders as $tmpProduks) {
            $lsitProduk[$tmpProduks->estimate_id][$tmpProduks->produk_id] = array(
                "id" => $tmpProduks->produk_id,
                "jml" => $tmpProduks->produk_ord_jml,
                "harga" => $tmpProduks->produk_ord_hrg,
            );
        }
        return $lsitProduk;
    }

    /**
     * Grouping order CRM per estimate_id dan hitung total finansial
     *
     * @param array $crmOrders
     * @return array
     */
    public function groupOrdersByEstimate($crmOrders)
    {
        $grouped = array();

        foreach ($crmOrders as $order) {
            $estimateId = $order->estimate_id;

            if (!isset($grouped[$estimateId])) {
                $grouped[$estimateId] = array(
                    'meta' => $order,
                    'orders' => array(),
                    'total_order' => 0,
                );
            }

            $grouped[$estimateId]['orders'][] = (array)$order;
            $grouped[$estimateId]['total_order'] += $order->produk_ord_hrg * $order->produk_ord_jml;
        }

        foreach ($grouped as $estimateId => $data) {
            $total = $data['total_order'];
            $grouped[$estimateId]['totals'] = array(
                'transaksi_nilai' => $total,
                'dppppn' => $total * (11 / 12),
                'ppn' => $total * (11 / 100),
                'total' => $total + ($total * (11 / 100)),
            );
        }

        return $grouped;
    }

    /**
     * Helper: Extract employees dengan role tertentu
     * 
     * @refactored_from Line 5658-5687
     * @param array $candidates Result dari getEmployeesByRole()
     * @param string $targetRole Role yang dicari (default: o_seller_entry)
     * @return array Array nama employees
     */
    public function extractEmployeesWithRole($candidates, $targetRole = 'o_seller_entry')
    {
        $foundNames = array();
        
        foreach ($candidates as $cand) {
            // Coba decode base64 dulu, baru unserialize
            $raw = $cand->membership;
            $decoded = base64_decode($raw);

            // Jika decode gagal atau kosong, coba raw langsung (backward compatibility)
            $roles = @unserialize($decoded);
            if ($roles === false) {
                $roles = @unserialize($raw);
            }

            // Cek apakah punya role target
            if (is_array($roles)) {
                // Cek value array
                if (in_array($targetRole, $roles)) {
                    $foundNames[] = $cand->nama;
                }
                // Cek key array (kadang disimpan sebagai key)
                elseif (array_key_exists($targetRole, $roles)) {
                    $foundNames[] = $cand->nama;
                }
            }
        }
        
        return $foundNames;
    }

    /**
     * Helper: Build approver label badges
     * 
     * @refactored_from Line 5689-5706
     * @param array $foundNames Array nama employees
     * @return string HTML badges
     */
    public function buildApproverLabelBadges($foundNames)
    {
        if (count($foundNames) == 0) {
            return "Seller Data Entry"; // Default fallback
        }

        // Buat badge warna-warni untuk setiap nama
        $colors = array(
            '#e1f5fe', '#e8f5e9', '#fff3e0', '#f3e5f5',
            '#ffebee', '#fff8e1', '#e0f2f1', '#fce4ec'
        );

        $badges = array();
        $i = 0;
        foreach ($foundNames as $name) {
            $color = $colors[$i % count($colors)]; // Rotasi warna
            $badges[] = "<span style=\"background-color:$color; padding:2px 6px; border-radius:4px; border:1px solid #ccc; margin:2px; display:inline-block; font-weight:bold; color:#333;\">$name</span>";
            $i++;
        }

        return implode(" ", $badges);
    }

    /**
     * Helper: Resolve customer name dengan COALESCE logic
     * 
     * @refactored_from Line 5794-5844
     * @param object $orderData Data order dari CRM
     * @param array $customerMapping Mapping approved customers
     * @param array $preCustomerMapping Mapping pending customers
     * @return string Customer name
     */
    public function resolveCustomerName($orderData, $customerMapping, $preCustomerMapping)
    {
        $customerName = "";

        if (!empty($orderData->customer_id) && $orderData->customer_id != 0) {
            // Skenario 1: Customer sudah approved, customer_id terisi
            if (isset($customerMapping[$orderData->customer_id])) {
                // Ada di array cache
                $customerName = $customerMapping[$orderData->customer_id]["nama"];
            } else {
                // Tidak ada di array, query langsung ke database (fallback)
                $custResult = $this->getCustomerById($orderData->customer_id);

                if ($custResult) {
                    $customerName = $custResult->nama;
                    // Note: Cache akan di-update di controller
                } else {
                    $customerName = "N/A (customer_id: {$orderData->customer_id} not found in per_customers)";
                }
            }
        } elseif (!empty($orderData->client_id)) {
            // Skenario 2: customer_id = 0, cari di per_customers_register berdasarkan client_id
            if (isset($preCustomerMapping[$orderData->client_id])) {
                // Customer belum approved
                $customerName = $preCustomerMapping[$orderData->client_id]["nama"] . " (Belum Approved)";
            } else {
                // client_id tidak ada di per_customers_register
                $customerName = "N/A (client_id: {$orderData->client_id} not found in per_customers_register)";
            }
        } else {
            // Tidak ada customer_id maupun client_id
            $customerName = "N/A";
        }

        return $customerName;
    }

    public function getCrmOrderActive($estimate_id){
        $m = new MdlCrmDataBridge();
        $m->addFilter("referensi_id ='$estimate_id'");
        $m->addFilter("qty_saldo >'0'");
        return $m->lookUpAll()->result();
    }


}
