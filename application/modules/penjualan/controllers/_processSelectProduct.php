<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 9/18/2018
 * Time: 8:45 PM
 */
require_once "Modul_Controller.php";

class _processSelectProduct extends Modul_Controller
{
    public function __construct()
    {
        parent::__construct();

    }

    private function normalizeItemJenisAlias($itemJenis = "")
    {
        if (!is_string($itemJenis) || trim($itemJenis) == "") {
            return "produk";
        }
        $itemJenis = trim($itemJenis);

        return strtolower($itemJenis) === "item" ? "produk" : $itemJenis;
    }

    private function sanitizeSwalText($text = "")
    {
        $text = is_string($text) ? $text : "";
        $text = strip_tags($text);
        $text = str_replace(array("\r", "\n"), " ", $text);

        return htmlspecialchars($text, ENT_QUOTES);
    }

    private function normalizeProdukJenisLabel($rawJenis = "")
    {
        if (!is_string($rawJenis)) {
            return "";
        }
        $jenis = trim($rawJenis);
        if ($jenis == "") {
            return "";
        }
        $jenis = strtolower($jenis);
        $jenis = str_replace(array("/", "-", "  "), array("_", "_", " "), $jenis);
        $jenis = preg_replace("/\\s+/", "_", $jenis);
        $jenis = trim((string)$jenis, "_");

        if ($jenis === "item" || $jenis === "produk" || $jenis === "barang") {
            return "Produk";
        }
        if (strpos($jenis, "rakit") !== false || $jenis === "assembly") {
            return "Rakitan";
        }
        if (strpos($jenis, "komposit") !== false || strpos($jenis, "paket") !== false || strpos($jenis, "bundle") !== false) {
            return "Komposit/Paket";
        }

        return ucwords(str_replace(array("_", "-"), " ", $jenis));
    }

    private function normalizeMissingPriceProductSpec($rawProduct = array(), $fallbackId = 0, $fallbackNo = 0, $fallbackPriceKey = "jual")
    {
        $rawProduct = is_array($rawProduct) ? $rawProduct : array();
        $fallbackId = is_numeric($fallbackId) ? (int)$fallbackId : 0;
        $fallbackNo = is_numeric($fallbackNo) ? (int)$fallbackNo : 0;
        $fallbackPriceKey = is_string($fallbackPriceKey) && trim($fallbackPriceKey) != "" ? trim($fallbackPriceKey) : "jual";

        $idNum = isset($rawProduct["id"]) && is_numeric($rawProduct["id"]) ? (int)$rawProduct["id"] : $fallbackId;
        $nomor = 0;
        if (isset($rawProduct["nomor"]) && is_numeric($rawProduct["nomor"])) {
            $nomor = (int)$rawProduct["nomor"];
        }
        elseif (isset($rawProduct["no"]) && is_numeric($rawProduct["no"])) {
            $nomor = (int)$rawProduct["no"];
        }
        if ($nomor < 1) {
            $nomor = $fallbackNo;
        }

        $nama = isset($rawProduct["nama"]) ? trim((string)$rawProduct["nama"]) : "";
        $namaLengkap = isset($rawProduct["nama_lengkap"]) ? trim((string)$rawProduct["nama_lengkap"]) : "";
        if ($namaLengkap == "") {
            $namaLengkap = $nama;
        }
        if ($nama == "") {
            $nama = $namaLengkap;
        }

        $kode = isset($rawProduct["kode"]) ? trim((string)$rawProduct["kode"]) : "";
        $sku = isset($rawProduct["sku"]) ? trim((string)$rawProduct["sku"]) : "";
        if ($sku == "") {
            $sku = $kode;
        }
        if ($kode == "") {
            $kode = $sku;
        }

        $jenis = isset($rawProduct["jenis"]) ? $this->normalizeProdukJenisLabel((string)$rawProduct["jenis"]) : "";
        $priceKey = isset($rawProduct["price_key"]) && trim((string)$rawProduct["price_key"]) != ""
            ? trim((string)$rawProduct["price_key"])
            : $fallbackPriceKey;

        return array(
            "id" => $idNum,
            "nomor" => $nomor,
            "no" => $nomor,
            "nama" => $nama,
            "nama_lengkap" => $namaLengkap,
            "kode" => $kode,
            "sku" => $sku,
            "jenis" => $jenis,
            "price_key" => $priceKey,
        );
    }

    private function getProdukIdentityMapByIds($produkIds = array())
    {
        $safeIds = array();
        foreach ($produkIds as $rawId) {
            $idNum = is_numeric($rawId) ? (int)$rawId : 0;
            if ($idNum > 0) {
                $safeIds[$idNum] = $idNum;
            }
        }
        if (count($safeIds) < 1) {
            return array();
        }

        $this->db->select("id, kode, nama, jenis");
        $this->db->from("produk");
        $this->db->where_in("id", array_values($safeIds));
        $rows = $this->db->get()->result();

        $map = array();
        foreach ($rows as $row) {
            $idNum = isset($row->id) && is_numeric($row->id) ? (int)$row->id : 0;
            if ($idNum < 1) {
                continue;
            }
            $kode = isset($row->kode) ? trim((string)$row->kode) : "";
            $nama = isset($row->nama) ? trim((string)$row->nama) : "";
            $map[$idNum] = array(
                "id" => $idNum,
                "kode" => $kode,
                "sku" => $kode,
                "nama" => $nama,
                "nama_lengkap" => $nama,
                "jenis" => $this->normalizeProdukJenisLabel(isset($row->jenis) ? (string)$row->jenis : ""),
            );
        }

        return $map;
    }

    private function getProdukIdentityById($produkId = 0)
    {
        $produkId = is_numeric($produkId) ? (int)$produkId : 0;
        if ($produkId < 1) {
            return array();
        }
        $map = $this->getProdukIdentityMapByIds(array($produkId));

        return isset($map[$produkId]) ? $map[$produkId] : array();
    }
    private function requestHargaProdukHolding($produkId, $itemJenisAlias = "produk")
    {
        $produkId = is_numeric($produkId) ? (int)$produkId : 0;
        if ($produkId < 1) {
            return array();
        }
        $itemJenisAlias = $this->normalizeItemJenisAlias($itemJenisAlias);
        $api = ADM_DOMAIN . "eusvc/Products/reqProdukPrice?pid=$produkId&jn=" . rawurlencode($itemJenisAlias);
        $ret = call_curl($api);

        return is_array($ret) ? $ret : array();
    }

    private function syncHargaProdukLokal($produkId, $ret = array())
    {
        $produkId = is_numeric($produkId) ? (int)$produkId : 0;
        if ($produkId < 1) {
            return 0;
        }

        $dataHarga = isset($ret["data"]) && is_array($ret["data"]) ? $ret["data"] : array();
        if (count($dataHarga) < 1) {
            return 0;
        }

        $this->load->model("Mdls/MdlHargaProduk");
        $hr = new MdlHargaProduk();

        $syncCounter = 0;
        foreach ($dataHarga as $jenisValue => $hargaSpec) {
            $jenisValue = is_string($jenisValue) ? trim($jenisValue) : "";
            if ($jenisValue == "") {
                continue;
            }

            $nilai = 0;
            if (is_array($hargaSpec) && isset($hargaSpec["nilai"])) {
                $nilai = $hargaSpec["nilai"];
            }
            elseif (is_numeric($hargaSpec)) {
                $nilai = $hargaSpec;
            }

            $nilai = is_numeric($nilai) ? (float)$nilai : 0;
            if ($nilai <= 0) {
                continue;
            }

            $kondisi = array(
                "produk_id" => $produkId,
                "jenis" => "produk",
                "jenis_value" => $jenisValue,
                "cabang_id" => CB_ID_PUSAT,
                "trash" => 0,
            );

            $hr->setFilters(array());
            $existing = $hr->lookupByCondition($kondisi)->row();
            if ($existing) {
                $hr->setFilters(array());
                $hr->updateData(
                    array("id" => $existing->id),
                    array(
                        "nilai" => $nilai,
                        "status" => 1,
                    )
                );
            }
            else {
                $hr->setFilters(array());
                $hr->addData(
                    array(
                        "nilai" => $nilai,
                        "produk_id" => $produkId,
                        "trash" => 0,
                        "status" => 1,
                        "jenis" => "produk",
                        "jenis_value" => $jenisValue,
                        "cabang_id" => CB_ID_PUSAT,
                    )
                );
            }
            $syncCounter++;
        }

        return $syncCounter;
    }

    private function parseMembershipRoles($rawMembership)
    {
        if (is_array($rawMembership)) {
            return $rawMembership;
        }
        if (!is_string($rawMembership) || trim($rawMembership) == "") {
            return array();
        }

        $candidates = array();
        $decoded = base64_decode($rawMembership, true);
        if (is_string($decoded) && $decoded != "") {
            $candidates[] = $decoded;
        }
        $candidates[] = $rawMembership;

        foreach ($candidates as $candidate) {
            $roles = @unserialize($candidate);
            if (is_array($roles)) {
                return $roles;
            }
        }

        $json = json_decode($rawMembership, true);
        if (is_array($json)) {
            return $json;
        }

        return array();
    }

    private function membershipHasRole($roles, $role)
    {
        if (!is_array($roles) || !is_string($role) || trim($role) == "") {
            return false;
        }
        $roleLower = strtolower(trim($role));

        foreach ($roles as $k => $v) {
            $kLower = strtolower(trim((string)$k));
            $vLower = strtolower(trim((string)$v));

            if ($vLower === $roleLower) {
                return true;
            }

            if ($kLower === $roleLower) {
                if ($v === null || $v === "" || $v === true || $v === 1 || $v === "1" || $vLower === "true" || $vLower === "yes" || $vLower === "on" || $vLower === "aktif") {
                    return true;
                }
            }
        }

        return false;
    }

    private function getLocalPriceSettingMembers($limit = 200)
    {
        $dataBehaviour = $this->config->item("heDataBehaviour");
        $roles = isset($dataBehaviour["MdlProduk"]["updaters"]) ? $dataBehaviour["MdlProduk"]["updaters"] : array();
        if (!is_array($roles) || count($roles) < 1) {
            return array();
        }

        $this->db->select("nama, membership");
        $this->db->from("per_employee");
        $this->db->where("status", 1);
        $this->db->where("trash", 0);
        $this->db->where("ghost", 0);
        $this->db->order_by("nama", "asc");
        $this->db->limit((int)$limit);
        $rows = $this->db->get()->result();

        $members = array();
        foreach ($rows as $row) {
            $nama = isset($row->nama) ? trim((string)$row->nama) : "";
            if ($nama == "") {
                continue;
            }

            $rolesOfMember = $this->parseMembershipRoles(isset($row->membership) ? $row->membership : "");
            foreach ($roles as $role) {
                if ($this->membershipHasRole($rolesOfMember, $role)) {
                    $members[] = $this->sanitizeSwalText($nama);
                    break;
                }
            }
        }

        $members = array_values(array_unique($members));
        if (count($members) > 20) {
            $more = count($members) - 20;
            $members = array_slice($members, 0, 20);
            $members[] = $this->sanitizeSwalText("dan $more anggota lainnya");
        }
        return $members;
    }

    private function getExpectedMainPriceKey($defaultKey = "jual")
    {
        $expected = is_string($defaultKey) && trim($defaultKey) != "" ? trim($defaultKey) : "jual";
        $priceConfig = isset($this->configUi[$this->jenisTr]["selectedPrice"]) ? $this->configUi[$this->jenisTr]["selectedPrice"] : array();
        $priceMainConfig = isset($this->configUi[$this->jenisTr]["selectedMainPrice"]) ? $this->configUi[$this->jenisTr]["selectedMainPrice"] : array();
        $cCode = isset($this->cCode) ? $this->cCode : "";

        if ($cCode != "" && is_array($priceMainConfig) && isset($_SESSION[$cCode]["main"]["pihakMainName"])) {
            $mainName = $_SESSION[$cCode]["main"]["pihakMainName"];
            if (isset($priceMainConfig[$mainName]) && is_array($priceMainConfig[$mainName]) && isset($priceMainConfig[$mainName]["mainSrc"]) && trim((string)$priceMainConfig[$mainName]["mainSrc"]) != "") {
                $expected = trim((string)$priceMainConfig[$mainName]["mainSrc"]);
            }
        }
        elseif (is_array($priceConfig) && isset($priceConfig["mainSrc"]) && trim((string)$priceConfig["mainSrc"]) != "") {
            $expected = trim((string)$priceConfig["mainSrc"]);
        }

        return strtolower($expected);
    }

    private function getHargaProdukLokalByJenis($produkId, $jenisValue = "")
    {
        $produkId = is_numeric($produkId) ? (int)$produkId : 0;
        $jenisValue = is_string($jenisValue) ? trim($jenisValue) : "";
        if ($produkId < 1 || $jenisValue == "") {
            return 0;
        }

        $this->db->select("nilai");
        $this->db->from("price");
        $this->db->where("produk_id", $produkId);
        $this->db->where("jenis", "produk");
        $this->db->where("jenis_value", $jenisValue);
        $this->db->where("cabang_id", CB_ID_PUSAT);
        $this->db->where("trash", 0);
        $this->db->where("status", 1);
        $this->db->order_by("id", "desc");
        $row = $this->db->get()->row();

        return ($row && isset($row->nilai) && is_numeric($row->nilai)) ? (float)$row->nilai : 0;
    }

    private function getMissingPriceSyncPayloadFromSession($expectedPriceKey = "")
    {
        $expectedPriceKey = is_string($expectedPriceKey) && trim($expectedPriceKey) != "" ? trim($expectedPriceKey) : "jual";
        $payload = array(
            "products" => array(),
            "ids" => array(),
            "expectedPriceKey" => $expectedPriceKey,
            "syncBaseUrl" => base_url() . "statik/Data/syncro_data/Produk/Produk",
            "refreshUrl" => $this->getMissingPriceRefreshUrl(),
        );

        $cCode = isset($this->cCode) ? $this->cCode : "";
        if ($cCode == "" || !isset($_SESSION[$cCode]) || !is_array($_SESSION[$cCode])) {
            return $payload;
        }

        $sessionPayload = isset($_SESSION[$cCode]["missingPriceSyncPayload"]) && is_array($_SESSION[$cCode]["missingPriceSyncPayload"])
            ? $_SESSION[$cCode]["missingPriceSyncPayload"]
            : array();
        if (count($sessionPayload) < 1) {
            return $payload;
        }

        if (isset($sessionPayload["expectedPriceKey"]) && is_string($sessionPayload["expectedPriceKey"]) && trim($sessionPayload["expectedPriceKey"]) != "") {
            $payload["expectedPriceKey"] = trim($sessionPayload["expectedPriceKey"]);
        }
        if (isset($sessionPayload["syncBaseUrl"]) && is_string($sessionPayload["syncBaseUrl"]) && trim($sessionPayload["syncBaseUrl"]) != "") {
            $payload["syncBaseUrl"] = trim($sessionPayload["syncBaseUrl"]);
        }
        if (isset($sessionPayload["refreshUrl"]) && is_string($sessionPayload["refreshUrl"]) && trim($sessionPayload["refreshUrl"]) != "") {
            $payload["refreshUrl"] = trim($sessionPayload["refreshUrl"]);
        }

        $rawIds = isset($sessionPayload["ids"]) && is_array($sessionPayload["ids"]) ? $sessionPayload["ids"] : array();
        $safeIds = array();
        foreach ($rawIds as $rawId) {
            $idNum = is_numeric($rawId) ? (int)$rawId : 0;
            if ($idNum > 0) {
                $safeIds[$idNum] = $idNum;
            }
        }
        $payload["ids"] = array_values($safeIds);

        $rawProducts = isset($sessionPayload["products"]) && is_array($sessionPayload["products"]) ? $sessionPayload["products"] : array();
        $safeProducts = array();
        foreach ($rawProducts as $rawProduct) {
            if (!is_array($rawProduct)) {
                continue;
            }
            $idNum = isset($rawProduct["id"]) && is_numeric($rawProduct["id"]) ? (int)$rawProduct["id"] : 0;
            if ($idNum < 1 || !isset($safeIds[$idNum])) {
                continue;
            }
            if (isset($safeProducts[$idNum])) {
                continue;
            }
            $safeProducts[$idNum] = $this->normalizeMissingPriceProductSpec(
                $rawProduct,
                $idNum,
                count($safeProducts) + 1,
                $payload["expectedPriceKey"]
            );
        }
        $payload["products"] = array_values($safeProducts);

        return $payload;
    }

    private function getMissingPriceRefreshUrl()
    {
        return MODUL_PATH . "_processSelectProduct/refreshMissingPriceSyncPayload/" . $this->jenisTr;
    }

    private function normalizeMissingPriceNumber($value)
    {
        if (is_numeric($value)) {
            return $value + 0;
        }
        if (is_string($value)) {
            $value = trim($value);
            if ($value == "") {
                return 0;
            }
            $value = str_replace(array(".", ","), "", $value);
            if ($value == "" || $value == "-") {
                return 0;
            }
            if (is_numeric($value)) {
                return $value + 0;
            }
        }

        return 0;
    }

    private function getLatestProdukPriceMap($produkIds = array(), $expectedPriceKey = "")
    {
        $expectedPriceKey = is_string($expectedPriceKey) && trim($expectedPriceKey) != "" ? trim($expectedPriceKey) : "jual";
        $safeIds = array();
        foreach ($produkIds as $rawId) {
            $idNum = is_numeric($rawId) ? (int)$rawId : 0;
            if ($idNum > 0) {
                $safeIds[$idNum] = $idNum;
            }
        }
        if (count($safeIds) < 1) {
            return array();
        }

        $this->db->select("produk_id, nilai, id");
        $this->db->from("price");
        $this->db->where("jenis", "produk");
        $this->db->where("jenis_value", $expectedPriceKey);
        $this->db->where("cabang_id", CB_ID_PUSAT);
        $this->db->where("trash", 0);
        $this->db->where("status", 1);
        $this->db->where_in("produk_id", array_values($safeIds));
        $this->db->order_by("produk_id", "asc");
        $this->db->order_by("id", "desc");
        $rows = $this->db->get()->result();

        $map = array();
        foreach ($rows as $row) {
            $pid = isset($row->produk_id) && is_numeric($row->produk_id) ? (int)$row->produk_id : 0;
            if ($pid < 1 || isset($map[$pid])) {
                continue;
            }
            $map[$pid] = isset($row->nilai) && is_numeric($row->nilai) ? ($row->nilai + 0) : 0;
        }

        return $map;
    }

    private function setMissingPriceSyncPayloadFromCrmItems($listProduk = array(), $expectedPriceKey = "")
    {
        $expectedPriceKey = is_string($expectedPriceKey) && trim($expectedPriceKey) != "" ? trim($expectedPriceKey) : "jual";
        $syncBaseUrl = base_url() . "statik/Data/syncro_data/Produk/Produk";
        $refreshUrl = $this->getMissingPriceRefreshUrl();
        $cCode = isset($this->cCode) ? $this->cCode : "";
        if ($cCode == "") {
            return;
        }
        if (!isset($_SESSION[$cCode]) || !is_array($_SESSION[$cCode])) {
            $_SESSION[$cCode] = array();
        }

        if (!is_array($listProduk) || count($listProduk) < 1) {
            $_SESSION[$cCode]["missingPriceSyncPayload"] = array(
                "products" => array(),
                "ids" => array(),
                "expectedPriceKey" => $expectedPriceKey,
                "syncBaseUrl" => $syncBaseUrl,
                "refreshUrl" => $refreshUrl,
                "updated_at" => dtimeNow(),
            );
            return;
        }

        $productsMap = array();
        $qtyMap = array();
        $orderedProductIds = array();
        foreach ($listProduk as $kProdukId => $itemSpec) {
            if (!is_array($itemSpec)) {
                continue;
            }

            $produkId = is_numeric($kProdukId) ? (int)$kProdukId : 0;
            if ($produkId < 1 && isset($itemSpec["produk_id"]) && is_numeric($itemSpec["produk_id"])) {
                $produkId = (int)$itemSpec["produk_id"];
            }
            if ($produkId < 1) {
                continue;
            }

            $qty = isset($itemSpec["jml"]) ? $this->normalizeMissingPriceNumber($itemSpec["jml"]) : 0;
            if (!isset($qtyMap[$produkId])) {
                $qtyMap[$produkId] = 0;
                $orderedProductIds[] = $produkId;
            }
            if ($qty > 0) {
                $qtyMap[$produkId] += $qty;
            }

            if (!isset($productsMap[$produkId])) {
                $productsMap[$produkId] = $this->normalizeMissingPriceProductSpec(
                    array(
                        "id" => $produkId,
                        "nomor" => isset($itemSpec["nomor"]) ? $itemSpec["nomor"] : (isset($itemSpec["no"]) ? $itemSpec["no"] : count($orderedProductIds)),
                        "nama" => isset($itemSpec["nama"]) ? trim((string)$itemSpec["nama"]) : "",
                        "nama_lengkap" => isset($itemSpec["nama_lengkap"]) ? trim((string)$itemSpec["nama_lengkap"]) : "",
                        "kode" => isset($itemSpec["kode"]) ? trim((string)$itemSpec["kode"]) : "",
                        "sku" => isset($itemSpec["sku"]) ? trim((string)$itemSpec["sku"]) : "",
                        "jenis" => isset($itemSpec["jenis"]) ? trim((string)$itemSpec["jenis"]) : "",
                        "price_key" => $expectedPriceKey,
                    ),
                    $produkId,
                    count($orderedProductIds),
                    $expectedPriceKey
                );
            }
        }

        if (count($orderedProductIds) < 1) {
            $_SESSION[$cCode]["missingPriceSyncPayload"] = array(
                "products" => array(),
                "ids" => array(),
                "expectedPriceKey" => $expectedPriceKey,
                "syncBaseUrl" => $syncBaseUrl,
                "refreshUrl" => $refreshUrl,
                "updated_at" => dtimeNow(),
            );
            return;
        }

        // Sumber deteksi missing wajib harga lokal terbaru.
        $hargaLokalMap = $this->getLatestProdukPriceMap($orderedProductIds, $expectedPriceKey);
        $missingIdsMap = array();
        foreach ($orderedProductIds as $produkId) {
            $qty = isset($qtyMap[$produkId]) ? $this->normalizeMissingPriceNumber($qtyMap[$produkId]) : 0;
            $hargaLokal = isset($hargaLokalMap[$produkId]) ? $this->normalizeMissingPriceNumber($hargaLokalMap[$produkId]) : 0;
            if ($qty > 0 && $hargaLokal <= 0) {
                $missingIdsMap[$produkId] = $produkId;
            }
        }

        $ids = array_values($missingIdsMap);
        if (count($ids) < 1) {
            $_SESSION[$cCode]["missingPriceSyncPayload"] = array(
                "products" => array(),
                "ids" => array(),
                "expectedPriceKey" => $expectedPriceKey,
                "syncBaseUrl" => $syncBaseUrl,
                "refreshUrl" => $refreshUrl,
                "updated_at" => dtimeNow(),
            );
            return;
        }

        $existingPayload = $this->getMissingPriceSyncPayloadFromSession($expectedPriceKey);
        $existingProducts = isset($existingPayload["products"]) && is_array($existingPayload["products"]) ? $existingPayload["products"] : array();
        foreach ($existingProducts as $existingSpec) {
            if (!is_array($existingSpec)) {
                continue;
            }
            $idNum = isset($existingSpec["id"]) && is_numeric($existingSpec["id"]) ? (int)$existingSpec["id"] : 0;
            if ($idNum < 1 || !isset($missingIdsMap[$idNum])) {
                continue;
            }
            $fallbackNo = isset($productsMap[$idNum]["nomor"]) && is_numeric($productsMap[$idNum]["nomor"])
                ? (int)$productsMap[$idNum]["nomor"]
                : (array_search($idNum, $ids, true) !== false ? (array_search($idNum, $ids, true) + 1) : 0);
            if (!isset($productsMap[$idNum])) {
                $productsMap[$idNum] = $this->normalizeMissingPriceProductSpec(array("id" => $idNum), $idNum, $fallbackNo, $expectedPriceKey);
            }
            $normalizedExisting = $this->normalizeMissingPriceProductSpec($existingSpec, $idNum, $fallbackNo, $expectedPriceKey);
            if (trim((string)$productsMap[$idNum]["nama"]) == "" && trim((string)$normalizedExisting["nama"]) != "") {
                $productsMap[$idNum]["nama"] = $normalizedExisting["nama"];
            }
            if (trim((string)$productsMap[$idNum]["nama_lengkap"]) == "" && trim((string)$normalizedExisting["nama_lengkap"]) != "") {
                $productsMap[$idNum]["nama_lengkap"] = $normalizedExisting["nama_lengkap"];
            }
            if (trim((string)$productsMap[$idNum]["kode"]) == "" && trim((string)$normalizedExisting["kode"]) != "") {
                $productsMap[$idNum]["kode"] = $normalizedExisting["kode"];
            }
            if (trim((string)$productsMap[$idNum]["sku"]) == "" && trim((string)$normalizedExisting["sku"]) != "") {
                $productsMap[$idNum]["sku"] = $normalizedExisting["sku"];
            }
            if (trim((string)$productsMap[$idNum]["jenis"]) == "" && trim((string)$normalizedExisting["jenis"]) != "") {
                $productsMap[$idNum]["jenis"] = $normalizedExisting["jenis"];
            }
            if (isset($normalizedExisting["nomor"]) && is_numeric($normalizedExisting["nomor"]) && (int)$normalizedExisting["nomor"] > 0) {
                $existingNo = (int)$normalizedExisting["nomor"];
                $currentNo = (isset($productsMap[$idNum]["nomor"]) && is_numeric($productsMap[$idNum]["nomor"])) ? (int)$productsMap[$idNum]["nomor"] : 0;
                if ($currentNo < 1 || $existingNo < $currentNo) {
                    $productsMap[$idNum]["nomor"] = $existingNo;
                    $productsMap[$idNum]["no"] = $existingNo;
                }
            }
            if (isset($normalizedExisting["price_key"]) && trim((string)$normalizedExisting["price_key"]) != "") {
                $productsMap[$idNum]["price_key"] = trim((string)$normalizedExisting["price_key"]);
            }
        }

        foreach ($ids as $idNum) {
            if (!isset($productsMap[$idNum])) {
                $fallbackNo = array_search($idNum, $ids, true) !== false ? (array_search($idNum, $ids, true) + 1) : 0;
                $productsMap[$idNum] = $this->normalizeMissingPriceProductSpec(array("id" => $idNum), $idNum, $fallbackNo, $expectedPriceKey);
            }
        }

        $produkMasterMap = $this->getProdukIdentityMapByIds($ids);
        foreach ($ids as $idNum) {
            if (!isset($productsMap[$idNum])) {
                continue;
            }
            if (!isset($produkMasterMap[$idNum])) {
                continue;
            }
            $masterSpec = $produkMasterMap[$idNum];
            if (isset($masterSpec["nama_lengkap"]) && trim((string)$masterSpec["nama_lengkap"]) != "") {
                $productsMap[$idNum]["nama_lengkap"] = trim((string)$masterSpec["nama_lengkap"]);
            }
            if (isset($masterSpec["nama"]) && trim((string)$masterSpec["nama"]) != "") {
                $productsMap[$idNum]["nama"] = trim((string)$masterSpec["nama"]);
            }
            if (isset($masterSpec["kode"]) && trim((string)$masterSpec["kode"]) != "") {
                $productsMap[$idNum]["kode"] = trim((string)$masterSpec["kode"]);
                $productsMap[$idNum]["sku"] = trim((string)$masterSpec["kode"]);
            }
            if (isset($masterSpec["jenis"]) && trim((string)$masterSpec["jenis"]) != "") {
                $productsMap[$idNum]["jenis"] = trim((string)$masterSpec["jenis"]);
            }
        }

        $products = array();
        $seqNo = 0;
        foreach ($ids as $idNum) {
            $seqNo++;
            $spec = isset($productsMap[$idNum]) ? $productsMap[$idNum] : array(
                "id" => $idNum,
                "nomor" => $seqNo,
                "no" => $seqNo,
                "nama" => "",
                "nama_lengkap" => "",
                "kode" => "",
                "sku" => "",
                "jenis" => "",
                "price_key" => $expectedPriceKey,
            );
            $spec = $this->normalizeMissingPriceProductSpec($spec, $idNum, $seqNo, $expectedPriceKey);
            if ($spec["nomor"] < 1) {
                $spec["nomor"] = $seqNo;
                $spec["no"] = $seqNo;
            }
            $products[] = $spec;
        }

        $_SESSION[$cCode]["missingPriceSyncPayload"] = array(
            "products" => $products,
            "ids" => $ids,
            "expectedPriceKey" => $expectedPriceKey,
            "syncBaseUrl" => $syncBaseUrl,
            "refreshUrl" => $refreshUrl,
            "updated_at" => dtimeNow(),
        );
    }

    private function refreshMissingPriceSyncPayloadByLatestPrice($expectedPriceKey = "")
    {
        $payload = $this->getMissingPriceSyncPayloadFromSession($expectedPriceKey);
        $expectedPriceKey = isset($payload["expectedPriceKey"]) && is_string($payload["expectedPriceKey"]) && trim($payload["expectedPriceKey"]) != ""
            ? trim($payload["expectedPriceKey"])
            : (is_string($expectedPriceKey) && trim($expectedPriceKey) != "" ? trim($expectedPriceKey) : "jual");
        $syncBaseUrl = isset($payload["syncBaseUrl"]) && is_string($payload["syncBaseUrl"]) && trim($payload["syncBaseUrl"]) != ""
            ? trim($payload["syncBaseUrl"])
            : (base_url() . "statik/Data/syncro_data/Produk/Produk");
        $refreshUrl = $this->getMissingPriceRefreshUrl();
        $cCode = isset($this->cCode) ? $this->cCode : "";

        $rawIds = isset($payload["ids"]) && is_array($payload["ids"]) ? $payload["ids"] : array();
        $ids = array();
        foreach ($rawIds as $rawId) {
            $idNum = is_numeric($rawId) ? (int)$rawId : 0;
            if ($idNum > 0) {
                $ids[$idNum] = $idNum;
            }
        }
        $ids = array_values($ids);

        $emptyPayload = array(
            "products" => array(),
            "ids" => array(),
            "expectedPriceKey" => $expectedPriceKey,
            "syncBaseUrl" => $syncBaseUrl,
            "refreshUrl" => $refreshUrl,
            "updated_at" => dtimeNow(),
        );
        if ($cCode == "") {
            return $emptyPayload;
        }
        if (!isset($_SESSION[$cCode]) || !is_array($_SESSION[$cCode])) {
            $_SESSION[$cCode] = array();
        }
        if (count($ids) < 1) {
            $_SESSION[$cCode]["missingPriceSyncPayload"] = $emptyPayload;
            return $emptyPayload;
        }

        $hargaLokalMap = $this->getLatestProdukPriceMap($ids, $expectedPriceKey);
        $missingIdsMap = array();
        foreach ($ids as $idNum) {
            $hargaLokal = isset($hargaLokalMap[$idNum]) ? $this->normalizeMissingPriceNumber($hargaLokalMap[$idNum]) : 0;
            if ($hargaLokal <= 0) {
                $missingIdsMap[$idNum] = $idNum;
            }
        }

        $missingIds = array_values($missingIdsMap);
        if (count($missingIds) < 1) {
            $_SESSION[$cCode]["missingPriceSyncPayload"] = $emptyPayload;
            return $emptyPayload;
        }

        $productsMap = array();
        $rawProducts = isset($payload["products"]) && is_array($payload["products"]) ? $payload["products"] : array();
        foreach ($rawProducts as $rawProduct) {
            if (!is_array($rawProduct)) {
                continue;
            }
            $idNum = isset($rawProduct["id"]) && is_numeric($rawProduct["id"]) ? (int)$rawProduct["id"] : 0;
            if ($idNum < 1 || !isset($missingIdsMap[$idNum])) {
                continue;
            }
            if (isset($productsMap[$idNum])) {
                continue;
            }
            $fallbackNo = array_search($idNum, $ids, true) !== false ? (array_search($idNum, $ids, true) + 1) : 0;
            $productsMap[$idNum] = $this->normalizeMissingPriceProductSpec($rawProduct, $idNum, $fallbackNo, $expectedPriceKey);
        }

        foreach ($missingIds as $idNum) {
            if (!isset($productsMap[$idNum])) {
                $fallbackNo = array_search($idNum, $ids, true) !== false ? (array_search($idNum, $ids, true) + 1) : 0;
                $productsMap[$idNum] = $this->normalizeMissingPriceProductSpec(array("id" => $idNum), $idNum, $fallbackNo, $expectedPriceKey);
            }
        }

        $produkMasterMap = $this->getProdukIdentityMapByIds($missingIds);
        foreach ($missingIds as $idNum) {
            if (!isset($productsMap[$idNum]) || !isset($produkMasterMap[$idNum])) {
                continue;
            }
            $masterSpec = $produkMasterMap[$idNum];
            if (isset($masterSpec["nama_lengkap"]) && trim((string)$masterSpec["nama_lengkap"]) != "") {
                $productsMap[$idNum]["nama_lengkap"] = trim((string)$masterSpec["nama_lengkap"]);
                $productsMap[$idNum]["nama"] = trim((string)$masterSpec["nama_lengkap"]);
            }
            if (isset($masterSpec["kode"]) && trim((string)$masterSpec["kode"]) != "") {
                $productsMap[$idNum]["kode"] = trim((string)$masterSpec["kode"]);
                $productsMap[$idNum]["sku"] = trim((string)$masterSpec["kode"]);
            }
            if (isset($masterSpec["jenis"]) && trim((string)$masterSpec["jenis"]) != "") {
                $productsMap[$idNum]["jenis"] = trim((string)$masterSpec["jenis"]);
            }
        }

        $products = array();
        $seqNo = 0;
        foreach ($ids as $idNum) {
            if (!isset($missingIdsMap[$idNum])) {
                continue;
            }
            $seqNo++;
            $spec = isset($productsMap[$idNum]) ? $productsMap[$idNum] : array(
                "id" => $idNum,
                "nomor" => $seqNo,
                "no" => $seqNo,
                "nama" => "",
                "nama_lengkap" => "",
                "kode" => "",
                "sku" => "",
                "jenis" => "",
                "price_key" => $expectedPriceKey,
            );
            $spec = $this->normalizeMissingPriceProductSpec($spec, $idNum, $seqNo, $expectedPriceKey);
            if ($spec["nomor"] < 1) {
                $spec["nomor"] = $seqNo;
                $spec["no"] = $seqNo;
            }
            $products[] = $spec;
        }

        $updatedPayload = array(
            "products" => $products,
            "ids" => $missingIds,
            "expectedPriceKey" => $expectedPriceKey,
            "syncBaseUrl" => $syncBaseUrl,
            "refreshUrl" => $refreshUrl,
            "updated_at" => dtimeNow(),
        );
        $_SESSION[$cCode]["missingPriceSyncPayload"] = $updatedPayload;

        return $updatedPayload;
    }

    private function getMissingPriceBatchContext($produkId = 0, $expectedPriceKey = "")
    {
        $produkId = is_numeric($produkId) ? (int)$produkId : 0;
        $payload = $this->refreshMissingPriceSyncPayloadByLatestPrice($expectedPriceKey);
        $ids = isset($payload["ids"]) && is_array($payload["ids"]) ? $payload["ids"] : array();
        $products = isset($payload["products"]) && is_array($payload["products"]) ? $payload["products"] : array();

        $context = array(
            "enabled" => false,
            "ids" => $ids,
            "idsCsv" => implode(",", $ids),
            "products" => $products,
            "expectedPriceKey" => isset($payload["expectedPriceKey"]) ? $payload["expectedPriceKey"] : (is_string($expectedPriceKey) ? $expectedPriceKey : "jual"),
            "syncBaseUrl" => isset($payload["syncBaseUrl"]) ? $payload["syncBaseUrl"] : (base_url() . "statik/Data/syncro_data/Produk/Produk"),
            "refreshUrl" => isset($payload["refreshUrl"]) ? $payload["refreshUrl"] : $this->getMissingPriceRefreshUrl(),
        );

        if (count($ids) < 2) {
            return $context;
        }
        if ($produkId > 0 && !in_array($produkId, $ids, true)) {
            return $context;
        }

        if (count($products) < 1) {
            $productsBuilt = array();
            $seqNo = 0;
            foreach ($ids as $idNum) {
                $seqNo++;
                $productsBuilt[] = $this->normalizeMissingPriceProductSpec(array(
                    "id" => $idNum,
                    "nomor" => $seqNo,
                    "no" => $seqNo,
                    "nama" => "Produk tanpa nama",
                    "nama_lengkap" => "",
                    "kode" => "",
                    "sku" => "",
                    "jenis" => "",
                    "price_key" => $context["expectedPriceKey"],
                ), $idNum, $seqNo, $context["expectedPriceKey"]);
            }
            $context["products"] = $productsBuilt;
        }
        else {
            $productsMap = array();
            foreach ($products as $pSpec) {
                if (!is_array($pSpec)) {
                    continue;
                }
                $idNum = isset($pSpec["id"]) && is_numeric($pSpec["id"]) ? (int)$pSpec["id"] : 0;
                if ($idNum > 0 && !isset($productsMap[$idNum])) {
                    $productsMap[$idNum] = $pSpec;
                }
            }
            $productsOrdered = array();
            $seqNo = 0;
            foreach ($ids as $idNum) {
                $seqNo++;
                $rawSpec = isset($productsMap[$idNum]) ? $productsMap[$idNum] : array("id" => $idNum);
                $productsOrdered[] = $this->normalizeMissingPriceProductSpec($rawSpec, $idNum, $seqNo, $context["expectedPriceKey"]);
            }
            usort($productsOrdered, function ($a, $b) {
                $aNo = isset($a["nomor"]) && is_numeric($a["nomor"]) ? (int)$a["nomor"] : 0;
                $bNo = isset($b["nomor"]) && is_numeric($b["nomor"]) ? (int)$b["nomor"] : 0;
                if ($aNo > 0 && $bNo > 0 && $aNo != $bNo) {
                    return $aNo < $bNo ? -1 : 1;
                }
                $aId = isset($a["id"]) && is_numeric($a["id"]) ? (int)$a["id"] : 0;
                $bId = isset($b["id"]) && is_numeric($b["id"]) ? (int)$b["id"] : 0;
                if ($aId == $bId) {
                    return 0;
                }

                return $aId < $bId ? -1 : 1;
            });
            $context["products"] = $productsOrdered;
        }

        $context["enabled"] = true;
        return $context;
    }
    private function buildHargaBelumTersediaMessage($produkLabel = "", $produkId = 0, $itemJenisAlias = "produk", $ret = array(), $withSyncButton = true, $expectedPriceKey = "")
    {
        $produkId = is_numeric($produkId) ? (int)$produkId : 0;
        $itemJenisAlias = $this->normalizeItemJenisAlias($itemJenisAlias);
        $produkLabelSafe = $this->sanitizeSwalText($produkLabel);
        $expectedPriceKey = is_string($expectedPriceKey) ? trim($expectedPriceKey) : "";
        $expectedPriceKeySafe = $expectedPriceKey != "" ? $this->sanitizeSwalText($expectedPriceKey) : "harga jual";

        $batchContext = $this->getMissingPriceBatchContext($produkId, $expectedPriceKey);
        $approvalTexts = array();
        if (isset($ret["approval"]) && is_array($ret["approval"])) {
            foreach ($ret["approval"] as $approvalText) {
                if (is_string($approvalText) && trim($approvalText) != "") {
                    $approvalTexts[] = $this->sanitizeSwalText($approvalText);
                }
            }
        }
        $localMembers = $this->getLocalPriceSettingMembers();
        foreach ($localMembers as $memberName) {
            if (is_string($memberName) && trim($memberName) != "") {
                $approvalTexts[] = $memberName;
            }
        }
        $approvalTexts = array_values(array_unique($approvalTexts));

        $msg = "";
        if (isset($batchContext["enabled"]) && $batchContext["enabled"] === true) {
            $msg .= "<div style='font-size:16px;font-weight:600;margin-bottom:8px;'>Harga jual belum tersedia untuk beberapa produk</div>";
        }
        else {
        $msg .= "<div style='font-size:16px;font-weight:600;margin-bottom:8px;'>Harga jual belum tersedia untuk $produkLabelSafe</div>";
        }
        $msg .= "<div style='text-align:left;line-height:1.5;'>";
        if (isset($batchContext["enabled"]) && $batchContext["enabled"] === true) {
            $produkBatch = isset($batchContext["products"]) && is_array($batchContext["products"]) ? $batchContext["products"] : array();
            $msg .= "<div><b>Keterangan beberapa produk yang belum memiliki harga:</b></div>";
            $msg .= "<div style='margin:6px 0 8px 0;max-height:175px;overflow:auto;border:1px solid #e5e5e5;border-radius:4px;'>";
            $msg .= "<table style='width:100%;border-collapse:collapse;font-size:12px;table-layout:fixed;'>";
            $msg .= "<thead><tr style='background:#f7f7f7;'>";
            $msg .= "<th style='padding:4px 5px;border-bottom:1px solid #e5e5e5;text-align:center;width:38px;'>No</th>";
            $msg .= "<th style='padding:4px 5px;border-bottom:1px solid #e5e5e5;text-align:left;width:125px;'>SKU</th>";
            $msg .= "<th style='padding:4px 5px;border-bottom:1px solid #e5e5e5;text-align:left;'>Nama Produk</th>";
            $msg .= "<th style='padding:4px 5px;border-bottom:1px solid #e5e5e5;text-align:left;width:110px;'>Jenis</th>";
            $msg .= "</tr></thead><tbody>";
            $rowNo = 0;
            foreach ($produkBatch as $pSpec) {
                $rowNo++;
                $idNum = isset($pSpec["id"]) && is_numeric($pSpec["id"]) ? (int)$pSpec["id"] : 0;
                $pNormalized = $this->normalizeMissingPriceProductSpec($pSpec, $idNum, $rowNo, $expectedPriceKey);
                $pNo = isset($pNormalized["nomor"]) && is_numeric($pNormalized["nomor"]) && (int)$pNormalized["nomor"] > 0
                    ? (int)$pNormalized["nomor"]
                    : $rowNo;
                $pNama = trim((string)$pNormalized["nama_lengkap"]);
                if ($pNama == "") {
                    $pNama = trim((string)$pNormalized["nama"]);
                }
                if ($pNama == "") {
                    $pNama = "Produk tanpa nama";
                }
                $pSku = trim((string)$pNormalized["sku"]);
                if ($pSku == "") {
                    $pSku = trim((string)$pNormalized["kode"]);
                }
                if ($pSku == "") {
                    $pSku = "-";
                }
                $pJenis = trim((string)$pNormalized["jenis"]);
                if ($pJenis == "") {
                    $pJenis = "-";
                }
                $msg .= "<tr>";
                $msg .= "<td style='padding:3px 5px;border-bottom:1px solid #f1f1f1;text-align:center;vertical-align:top;'>" . $this->sanitizeSwalText((string)$pNo) . "</td>";
                $msg .= "<td style='padding:3px 5px;border-bottom:1px solid #f1f1f1;vertical-align:top;word-break:break-word;'>" . $this->sanitizeSwalText($pSku) . "</td>";
                $msg .= "<td style='padding:3px 5px;border-bottom:1px solid #f1f1f1;vertical-align:top;word-break:break-word;'>" . $this->sanitizeSwalText($pNama) . "</td>";
                $msg .= "<td style='padding:3px 5px;border-bottom:1px solid #f1f1f1;vertical-align:top;word-break:break-word;'>" . $this->sanitizeSwalText($pJenis) . "</td>";
                $msg .= "</tr>";
            }
            $msg .= "</tbody></table>";
            $msg .= "</div>";
            $msg .= "<div style='margin-bottom:8px;font-size:12px;color:#555;'>Total produk tanpa harga: <b>" . count($batchContext["ids"]) . "</b>. Sinkronisasi hanya akan memproses produk terkait ini (baris merah), bukan semua produk.</div>";
        }
        else {
            $produkInfo = $this->getProdukIdentityById($produkId);
            $singleNamaFallback = trim(strip_tags((string)$produkLabel));
            if ($singleNamaFallback == "") {
                $singleNamaFallback = "Produk";
            }
            $singleNama = isset($produkInfo["nama_lengkap"]) && trim((string)$produkInfo["nama_lengkap"]) != ""
                ? trim((string)$produkInfo["nama_lengkap"])
                : $singleNamaFallback;
            $singleSku = isset($produkInfo["sku"]) && trim((string)$produkInfo["sku"]) != ""
                ? trim((string)$produkInfo["sku"])
                : "-";
            $singleJenis = isset($produkInfo["jenis"]) && trim((string)$produkInfo["jenis"]) != ""
                ? trim((string)$produkInfo["jenis"])
                : $this->normalizeProdukJenisLabel($itemJenisAlias);
            if ($singleJenis == "") {
                $singleJenis = "-";
            }
            $msg .= "<div style='margin:6px 0 10px 0;'><b>Detail produk:</b></div>";
            $msg .= "<table style='width:100%;border-collapse:collapse;font-size:13px;margin-bottom:8px;'>";
            $msg .= "<tr><td style='padding:3px 6px;border-bottom:1px solid #f1f1f1;width:145px;'>Nama Lengkap</td><td style='padding:3px 6px;border-bottom:1px solid #f1f1f1;'>: " . $this->sanitizeSwalText($singleNama) . "</td></tr>";
            $msg .= "<tr><td style='padding:3px 6px;border-bottom:1px solid #f1f1f1;'>SKU</td><td style='padding:3px 6px;border-bottom:1px solid #f1f1f1;'>: " . $this->sanitizeSwalText($singleSku) . "</td></tr>";
            $msg .= "<tr><td style='padding:3px 6px;border-bottom:1px solid #f1f1f1;'>Jenis</td><td style='padding:3px 6px;border-bottom:1px solid #f1f1f1;'>: " . $this->sanitizeSwalText($singleJenis) . "</td></tr>";
            $msg .= "</table>";
        }
        $msg .= "<div><b>Kemungkinan penyebab:</b></div>";
        $msg .= "<ol style='margin:6px 0 10px 18px;padding:0;'>";
        $msg .= "<li>Produk belum tersinkron dengan data di Holding Company.</li>";
        $msg .= "<li>Nilai <b>$expectedPriceKeySafe</b> belum disetting di Holding Company.</li>";
        $msg .= "</ol>";
        $msg .= "<div><b>Langkah yang disarankan:</b></div>";
        $msg .= "<ol style='margin:6px 0 0 18px;padding:0;'>";
        if ($withSyncButton) {
            $msg .= "<li>Gunakan tombol Sinkronisasi Produk untuk mencoba sinkronisasi dari aplikasi ini.</li>";
        }
        else {
            $msg .= "<li>Lakukan sinkronisasi produk dari aplikasi ini.</li>";
        }
        $msg .= "<li>Jika masih gagal, lakukan setting harga di aplikasi Holding Company.</li>";
        $msg .= "</ol>";

        if (count($approvalTexts) > 0) {
            $msg .= "<div style='margin-top:10px;'><b>Setting harga hanya bisa dilakukan di Holding Company (san.mayagrahakencana.com).</b><br>Silahkan hubungi <b>" . implode(", ", $approvalTexts) . "</b> di Holding Company.<br>Jika sudah selesai setting produk, silahkan klik tombol sinkronisasi di bawah ini.</div>";
        }
        else {
            $msg .= "<div style='margin-top:10px;'><b>Setting harga hanya bisa dilakukan di Holding Company (san.mayagrahakencana.com).</b><br>Silahkan hubungi tim pricing Holding Company.<br>Jika sudah selesai setting produk, silahkan klik tombol sinkronisasi di bawah ini.</div>";
        }
        $msg .= "</div>";

        if ($withSyncButton && $produkId > 0) {
            $buttonLabel = "Sinkronisasi Produk";
            $buttonClass = "btn-sync-missing-price";
            $batchAttrs = "";
            $refreshUrlAttr = htmlspecialchars($this->getMissingPriceRefreshUrl(), ENT_QUOTES);
            if (isset($batchContext["enabled"]) && $batchContext["enabled"] === true) {
                $idsCsv = isset($batchContext["idsCsv"]) ? trim((string)$batchContext["idsCsv"]) : "";
                $syncLink = base_url() . $this->modul . "/_processSelectProduct/syncMissingProductPriceBatch/" . $this->jenisTr;
                if ($idsCsv != "") {
                    $syncLink .= "?ids=" . rawurlencode($idsCsv);
                    if ($expectedPriceKey != "") {
                        $syncLink .= "&price_key=" . rawurlencode($expectedPriceKey);
                    }
                }
                elseif ($expectedPriceKey != "") {
                    $syncLink .= "?price_key=" . rawurlencode($expectedPriceKey);
                }
                $buttonLabel = "Sinkronisasi semua produk yang tidak memiliki harga";
                $buttonClass = "btn-sync-missing-price btn-sync-missing-price-batch";
                $batchAttrs .= " data-sync-ids='" . htmlspecialchars($idsCsv, ENT_QUOTES) . "'";
                $batchAttrs .= " data-sync-base='" . htmlspecialchars(base_url() . "statik/Data/syncro_data/Produk/Produk", ENT_QUOTES) . "'";
                $batchAttrs .= " data-refresh-url='$refreshUrlAttr'";
            }
            else {
            $syncLink = base_url() . "statik/Data/syncro_data/Produk/Produk?dc_id=$produkId";
            if ($expectedPriceKey != "") {
                $syncLink .= "&price_key=" . rawurlencode($expectedPriceKey);
            }
            }
            $syncLinkAttr = htmlspecialchars($syncLink, ENT_QUOTES);
            $expectedPriceKeyAttr = htmlspecialchars($expectedPriceKey, ENT_QUOTES);
            $msg .= "<div style='margin-top:12px;'>";
            $msg .= "<a href='$syncLinkAttr' class='$buttonClass' data-sync-url='$syncLinkAttr' data-sync-product-id='$produkId' data-price-key='$expectedPriceKeyAttr' data-refresh-url='$refreshUrlAttr' $batchAttrs style='display:inline-block;position:relative;z-index:3001;cursor:pointer;padding:8px 14px;background:#1f6feb;color:#fff;text-decoration:none;border:0;border-radius:4px;'>$buttonLabel</a>";
            $msg .= "</div>";
            if (isset($batchContext["enabled"]) && $batchContext["enabled"] === true) {
                $msg .= "<div style='margin-top:8px;font-size:12px;color:#666;'>Klik sekali untuk sinkronisasi semua produk terkait yang belum memiliki harga.</div>";
            }
            else {
                $msg .= "<div style='margin-top:8px;font-size:12px;color:#666;'>Jika terdapat beberapa baris merah pada Shopping Cart, sekali klik tombol sinkronisasi akan memproses seluruh produk merah terkait.</div>";
            }
            $msg .= "<div style='margin-top:8px;font-size:12px;color:#666;'>Setelah sinkronisasi selesai, kembali ke halaman penjualan lalu pilih ulang produk.</div>";
        }

        return $msg;
    }

    private function stopWithHargaBelumTersedia($produkId, $produkLabel = "", $itemJenis = "")
    {
        $itemJenisAlias = $this->normalizeItemJenisAlias($itemJenis);
        $expectedPriceKey = $this->getExpectedMainPriceKey("jual");
        $ret = $this->requestHargaProdukHolding($produkId, $itemJenisAlias);
        $synced = $this->syncHargaProdukLokal($produkId, $ret);
        $nilaiMainPrice = $this->getHargaProdukLokalByJenis($produkId, $expectedPriceKey);
        $this->refreshMissingPriceSyncPayloadByLatestPrice($expectedPriceKey);

        if ($synced > 0 && $nilaiMainPrice > 0) {
            mati_disini("Harga produk berhasil disinkronkan dari Holding Company. Nilai $expectedPriceKey tersedia. Silahkan pilih ulang produk.");
        }

        $msg = $this->buildHargaBelumTersediaMessage($produkLabel, $produkId, $itemJenisAlias, $ret, true, $expectedPriceKey);
        mati_disini($msg);
    }

    public function syncMissingProductPrice()
    {
        $produkId = isset($_GET["pid"]) ? (int)$_GET["pid"] : 0;
        $itemJenisAlias = isset($_GET["jn"]) ? $_GET["jn"] : "produk";
        $itemJenisAlias = $this->normalizeItemJenisAlias($itemJenisAlias);
        $expectedPriceKey = $this->getExpectedMainPriceKey("jual");

        if ($produkId < 1) {
            mati_disini("Sinkronisasi gagal. ID produk tidak valid.");
        }

        $this->load->model("Mdls/MdlProduk");
        $p = new MdlProduk();
        $p->setFilters(array());
        $prd = $p->lookupByID($produkId)->row();

        $produkLabel = "Produk ID $produkId";
        if ($prd) {
            $produkLabel = "Produk " . $prd->kode . " " . $prd->nama;
        }

        $ret = $this->requestHargaProdukHolding($produkId, $itemJenisAlias);
        $synced = $this->syncHargaProdukLokal($produkId, $ret);
        $nilaiMainPrice = $this->getHargaProdukLokalByJenis($produkId, $expectedPriceKey);
        $this->refreshMissingPriceSyncPayloadByLatestPrice($expectedPriceKey);
        if ($synced > 0 && $nilaiMainPrice > 0) {
            $msg = "Sinkronisasi berhasil untuk " . $this->sanitizeSwalText($produkLabel) . ". ";
            $msg .= "$synced data harga berhasil diperbarui. Nilai $expectedPriceKey tersedia. Silahkan kembali ke halaman penjualan dan pilih ulang produk.";
            mati_disini($msg);
        }

        $msg = $this->buildHargaBelumTersediaMessage($produkLabel, $produkId, $itemJenisAlias, $ret, false, $expectedPriceKey);
        mati_disini($msg);
    }
    public function syncMissingProductPriceBatch()
    {
        $expectedPriceKey = $this->getExpectedMainPriceKey("jual");
        if (isset($_GET["price_key"]) && is_string($_GET["price_key"]) && trim($_GET["price_key"]) != "") {
            $expectedPriceKey = trim($_GET["price_key"]);
        }

        $ids = array();
        $rawIds = isset($_GET["ids"]) ? $_GET["ids"] : "";
        if (is_string($rawIds) && trim($rawIds) != "") {
            $parts = explode(",", $rawIds);
            foreach ($parts as $part) {
                $idNum = is_numeric(trim($part)) ? (int)trim($part) : 0;
                if ($idNum > 0) {
                    $ids[$idNum] = $idNum;
                }
            }
        }

        if (count($ids) < 1) {
            $sessionContext = $this->getMissingPriceBatchContext(0, $expectedPriceKey);
            $sessionIds = isset($sessionContext["ids"]) && is_array($sessionContext["ids"]) ? $sessionContext["ids"] : array();
            foreach ($sessionIds as $sid) {
                $sidNum = is_numeric($sid) ? (int)$sid : 0;
                if ($sidNum > 0) {
                    $ids[$sidNum] = $sidNum;
                }
            }
        }

        $ids = array_values($ids);
        if (count($ids) < 1) {
            mati_disini("Sinkronisasi batch gagal. Tidak ada produk yang valid untuk diproses.");
        }

        $this->load->model("Mdls/MdlProduk");
        $p = new MdlProduk();

        $ok = 0;
        $fail = 0;
        $failedIds = array();

        foreach ($ids as $produkId) {
            $syncResult = $p->syncApiData(my_cabang_id(), array(
                "dc_id" => $produkId,
                "price_key" => $expectedPriceKey,
            ));
            $status = isset($syncResult["status"]) ? (bool)$syncResult["status"] : false;
            if ($status) {
                $ok++;
            }
            else {
                $fail++;
                $failedIds[] = $produkId;
            }
        }

        $failedProductSpecMap = array();
        if (count($failedIds) > 0) {
            $failedMasterMap = $this->getProdukIdentityMapByIds($failedIds);
            foreach ($failedIds as $fid) {
                $baseSpec = array("id" => $fid);
                if (isset($failedMasterMap[$fid]) && is_array($failedMasterMap[$fid])) {
                    $baseSpec = array_merge($baseSpec, $failedMasterMap[$fid]);
                }
                $failedProductSpecMap[$fid] = $this->normalizeMissingPriceProductSpec($baseSpec, $fid, 0, $expectedPriceKey);
            }
        }

        $payloadAfterSync = $this->refreshMissingPriceSyncPayloadByLatestPrice($expectedPriceKey);
        $remainingMissingIds = isset($payloadAfterSync["ids"]) && is_array($payloadAfterSync["ids"]) ? $payloadAfterSync["ids"] : array();
        $remainingMissingMap = array();
        foreach ($remainingMissingIds as $rid) {
            $ridNum = is_numeric($rid) ? (int)$rid : 0;
            if ($ridNum > 0) {
                $remainingMissingMap[$ridNum] = $ridNum;
            }
        }
        $remainingProcessed = array();
        foreach ($ids as $pid) {
            if (isset($remainingMissingMap[$pid])) {
                $remainingProcessed[] = $pid;
            }
        }
        $remainingProductSpecMap = array();
        $remainingProductsRaw = isset($payloadAfterSync["products"]) && is_array($payloadAfterSync["products"]) ? $payloadAfterSync["products"] : array();
        foreach ($remainingProductsRaw as $rSpec) {
            if (!is_array($rSpec)) {
                continue;
            }
            $idNum = isset($rSpec["id"]) && is_numeric($rSpec["id"]) ? (int)$rSpec["id"] : 0;
            if ($idNum < 1 || isset($remainingProductSpecMap[$idNum])) {
                continue;
            }
            $remainingProductSpecMap[$idNum] = $this->normalizeMissingPriceProductSpec($rSpec, $idNum, 0, $expectedPriceKey);
        }
        $resolvedProcessed = count($ids) - count($remainingProcessed);

        $msg = "<div style='text-align:left;line-height:1.5;'>";
        $msg .= "<div><b>Sinkronisasi batch produk selesai.</b></div>";
        $msg .= "<div>Total diproses: <b>" . count($ids) . "</b></div>";
        $msg .= "<div>Berhasil: <b>$ok</b>, Gagal: <b>$fail</b></div>";
        $msg .= "<div>Hasil verifikasi harga lokal: <b>$resolvedProcessed</b> produk sudah memiliki harga, <b>" . count($remainingProcessed) . "</b> produk masih belum memiliki harga.</div>";
        if ($fail > 0) {
            $msg .= "<div style='margin-top:8px;'><b>Request sinkronisasi yang gagal:</b></div>";
            $msg .= "<div style='margin-top:4px;max-height:145px;overflow:auto;border:1px solid #e5e5e5;border-radius:4px;'>";
            $msg .= "<table style='width:100%;border-collapse:collapse;font-size:12px;table-layout:fixed;'>";
            $msg .= "<thead><tr style='background:#f7f7f7;'>";
            $msg .= "<th style='padding:4px 5px;border-bottom:1px solid #e5e5e5;text-align:center;width:38px;'>No</th>";
            $msg .= "<th style='padding:4px 5px;border-bottom:1px solid #e5e5e5;text-align:left;width:120px;'>SKU</th>";
            $msg .= "<th style='padding:4px 5px;border-bottom:1px solid #e5e5e5;text-align:left;'>Nama Produk</th>";
            $msg .= "<th style='padding:4px 5px;border-bottom:1px solid #e5e5e5;text-align:left;width:110px;'>Jenis</th>";
            $msg .= "</tr></thead><tbody>";
            $idxFail = 0;
            foreach ($failedIds as $fid) {
                $idxFail++;
                $fSpec = isset($failedProductSpecMap[$fid]) ? $failedProductSpecMap[$fid] : $this->normalizeMissingPriceProductSpec(array("id" => $fid), $fid, $idxFail, $expectedPriceKey);
                $fNama = trim((string)$fSpec["nama_lengkap"]);
                if ($fNama == "") {
                    $fNama = trim((string)$fSpec["nama"]);
                }
                if ($fNama == "") {
                    $fNama = "Produk tanpa nama";
                }
                $fSku = trim((string)$fSpec["sku"]);
                if ($fSku == "") {
                    $fSku = trim((string)$fSpec["kode"]);
                }
                if ($fSku == "") {
                    $fSku = "-";
                }
                $fJenis = trim((string)$fSpec["jenis"]);
                if ($fJenis == "") {
                    $fJenis = "-";
                }
                $msg .= "<tr>";
                $msg .= "<td style='padding:3px 5px;border-bottom:1px solid #f1f1f1;text-align:center;vertical-align:top;'>" . $idxFail . "</td>";
                $msg .= "<td style='padding:3px 5px;border-bottom:1px solid #f1f1f1;vertical-align:top;word-break:break-word;'>" . $this->sanitizeSwalText($fSku) . "</td>";
                $msg .= "<td style='padding:3px 5px;border-bottom:1px solid #f1f1f1;vertical-align:top;word-break:break-word;'>" . $this->sanitizeSwalText($fNama) . "</td>";
                $msg .= "<td style='padding:3px 5px;border-bottom:1px solid #f1f1f1;vertical-align:top;word-break:break-word;'>" . $this->sanitizeSwalText($fJenis) . "</td>";
                $msg .= "</tr>";
            }
            $msg .= "</tbody></table>";
            $msg .= "</div>";
        }
        if (count($remainingProcessed) > 0) {
            $msg .= "<div style='margin-top:8px;'><b>Produk yang masih belum memiliki harga:</b></div>";
            $msg .= "<div style='margin-top:4px;max-height:170px;overflow:auto;border:1px solid #e5e5e5;border-radius:4px;'>";
            $msg .= "<table style='width:100%;border-collapse:collapse;font-size:12px;table-layout:fixed;'>";
            $msg .= "<thead><tr style='background:#f7f7f7;'>";
            $msg .= "<th style='padding:4px 5px;border-bottom:1px solid #e5e5e5;text-align:center;width:38px;'>No</th>";
            $msg .= "<th style='padding:4px 5px;border-bottom:1px solid #e5e5e5;text-align:left;width:120px;'>SKU</th>";
            $msg .= "<th style='padding:4px 5px;border-bottom:1px solid #e5e5e5;text-align:left;'>Nama Produk</th>";
            $msg .= "<th style='padding:4px 5px;border-bottom:1px solid #e5e5e5;text-align:left;width:110px;'>Jenis</th>";
            $msg .= "</tr></thead><tbody>";
            $idxRemain = 0;
            foreach ($remainingProcessed as $pidRemain) {
                $idxRemain++;
                $spec = isset($remainingProductSpecMap[$pidRemain]) ? $remainingProductSpecMap[$pidRemain] : $this->normalizeMissingPriceProductSpec(array("id" => $pidRemain), $pidRemain, $idxRemain, $expectedPriceKey);
                $nama = trim((string)$spec["nama_lengkap"]);
                if ($nama == "") {
                    $nama = trim((string)$spec["nama"]);
                }
                if ($nama == "") {
                    $nama = "Produk tanpa nama";
                }
                $sku = trim((string)$spec["sku"]);
                if ($sku == "") {
                    $sku = trim((string)$spec["kode"]);
                }
                if ($sku == "") {
                    $sku = "-";
                }
                $jenis = trim((string)$spec["jenis"]);
                if ($jenis == "") {
                    $jenis = "-";
                }
                $msg .= "<tr>";
                $msg .= "<td style='padding:3px 5px;border-bottom:1px solid #f1f1f1;text-align:center;vertical-align:top;'>" . $idxRemain . "</td>";
                $msg .= "<td style='padding:3px 5px;border-bottom:1px solid #f1f1f1;vertical-align:top;word-break:break-word;'>" . $this->sanitizeSwalText($sku) . "</td>";
                $msg .= "<td style='padding:3px 5px;border-bottom:1px solid #f1f1f1;vertical-align:top;word-break:break-word;'>" . $this->sanitizeSwalText($nama) . "</td>";
                $msg .= "<td style='padding:3px 5px;border-bottom:1px solid #f1f1f1;vertical-align:top;word-break:break-word;'>" . $this->sanitizeSwalText($jenis) . "</td>";
                $msg .= "</tr>";
            }
            $msg .= "</tbody></table>";
            $msg .= "</div>";
        }
        $msg .= "<div style='margin-top:8px;'>Silahkan pilih ulang produk untuk memastikan harga terbaru sudah terambil.</div>";
        $msg .= "</div>";

        mati_disini($msg);
    }

    public function refreshMissingPriceSyncPayload()
    {
        header("Content-Type: application/json");
        $expectedPriceKey = $this->getExpectedMainPriceKey("jual");
        if (isset($_GET["price_key"]) && is_string($_GET["price_key"]) && trim($_GET["price_key"]) != "") {
            $expectedPriceKey = trim($_GET["price_key"]);
        }

        $payload = $this->refreshMissingPriceSyncPayloadByLatestPrice($expectedPriceKey);
        echo json_encode(array(
            "status" => "success",
            "payload" => $payload,
        ));
        exit;
    }
    public function select()
    {
        // arrPrint($_GET);

        //untuk obat sementara discount minus
        if ($_GET["disc_percent"] < 0 || $_GET["disc_percent"] > 100) {
            $minus = $_GET["disc_percent"] < 0 ? "persentase diskon salah, silahkan menggunakan nilai positif" : "pemberian diskon salah, diskon maksimal 100%";
            matiHEre($minus);
        }
        if ($_GET["disc"] < 0) {
            matiHEre("pemberian diskon salah, silahkan menggunakan nilai positif");
        }
        // if($_GET["nett1"] < 0){
        //     matiHEre("pemberian diskon salah, diskon maksimal 100%");
        // }

        //

        $this->load->helper("he_angka_helper");
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $id = $produk_id = $_GET['id'];
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;
        $stepNum = $this->uri->segment(5) > 0 ? $this->uri->segment(5) : 1;
        $cCode = $this->cCode;

        $selectorModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = isset($_SESSION[$cCode]['main']['pihakMdlNameSrc']) ? $_SESSION[$cCode]['main']['pihakMdlNameSrc'] : $this->configUi[$this->jenisTr]['selectorSrcModel'];
        $arrDataTambahan = isset($this->configUi[$this->jenisTr]['produkUnitPart']) ? $this->configUi[$this->jenisTr]['produkUnitPart'] : array();
        //-----------------------------------------------
        if (!isset($_SESSION[$cCode]['items2'][$id])) {
            $_SESSION[$cCode]['items2'][$id] = array();
        }
        //        $arrDataTambahan = array(
        //            "outdoor" => array(
        //                "outdoor_id" => "outdoor_nama",
        //            ),
        //            "indoor" => array(
        //                "indoor_id_1" => "indoor_nama_1",
        //                "indoor_id_2" => "indoor_nama_2",
        //                "indoor_id_3" => "indoor_nama_3",
        //                "indoor_id_4" => "indoor_nama_4",
        //            ),
        //            "heater" => array(
        //                "heater_id" => "heater_nama",
        //            ),
        //        );
        //-----------------------------------------------


        // detektor tanda kurawal {}
        if (substr($selectorModel, 0, 1) == "{") {
            $selectorModel = trim($selectorModel, "{");
            $selectorModel = trim($selectorModel, "}");
            $selectorModel = str_replace($selectorModel, $_SESSION[$cCode]['main'][$selectorModel], $selectorModel);
        }
        else {
            cekkuning("TIDAK mengandung kurawal @" . __LINE__ . __CLASS__);
        }
        if (substr($selectorSrcModel, 0, 1) == "{") {
            $selectorSrcModel = trim($selectorSrcModel, "{");
            $selectorSrcModel = trim($selectorSrcModel, "}");
            $selectorSrcModel = str_replace($selectorSrcModel, $_SESSION[$cCode]['main'][$selectorSrcModel], $selectorSrcModel);
        }
        else {
            cekkuning("TIDAK mengandung kurawal @" . __LINE__ . " " . __METHOD__);
        }

        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();

        $priceSrcConfig = $this->config->item('hePrices') != null ? $this->config->item('hePrices') : array();
        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();

        $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $priceMainConfig = isset($this->configUi[$this->jenisTr]['selectedMainPrice']) ? $this->configUi[$this->jenisTr]['selectedMainPrice'] : array();

        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $connectedDiscountConfig = isset($this->configUi[$this->jenisTr]['connectedDiscount']) ? $this->configUi[$this->jenisTr]['connectedDiscount'] : array();
        $priceFilter = isset($this->configUi[$this->jenisTr]['selectedPrice']['mdlFilter']) ? $this->configUi[$this->jenisTr]['selectedPrice']['mdlFilter'] : array();
        $resetFilter = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $validateMeasurement = isset($this->configUi[$this->jenisTr]['validateMeasurement'][1]) ? $this->configUi[$this->jenisTr]['validateMeasurement'][1] : array();
        $ppnFactor = isset($_SESSION[$cCode]["main"]["ppnFactor"]) ? $_SESSION[$cCode]["main"]["ppnFactor"] : my_ppn_factor();

        $tmpB = $b->lookupByID($id)->result();
        // showLast_query("lime");
        // matiHere(__LINE__ . " " .__METHOD__);

        // -----------------------------------------
        $tableIn_master = $_SESSION[$cCode]['tableIn_master'];
        // arrPrint($tableIn_master);
        $gudang_status_id = $tableIn_master['gudang_status_id'];

        $this->load->model("Mdls/MdlLockerStockBooking");
        $lsb = new MdlLockerStockBooking();
        $lsb_datas = $lsb->getStokBooking();
        // showLast_query("merah");
        // arrPrintHijau($lsb_datas);
        // arrPrintCyan();
        $ppnFactorInclude = $_SESSION[$cCode]['main']['ppnFactorInclude'];
        // matiHere(__LINE__ . " $ppnFactorInclude");

        $this->load->library("Diskon");
        $ld = new Diskon();
        $ld->setTokoId(my_toko_id());
        // unset($_SESSION[$cCode]['items']);
        $pro_jml = 0;
        if (sizeof($tmpB) > 0) {
            foreach ($tmpB as $row) {
                $rows = $row;
                $item_jenis = $row->jenis;//item,komposit/paket,rakitan
                $produk_jenis_id = $rows->kategori_id;
                $produk_jenis = $rows->kategori_nama;
                $produk_nama = $rows->nama;
                $produk_kode = $rows->kode;
                $produk_kode = htmlspecialchars($produk_kode);
                $produk_nama = htmlspecialchars($produk_nama);
                $produk_label_2 = "<span class='text-red'>Produk $produk_kode $produk_nama</span>";

                $valValidate_items = array();

                if (sizeof($validateMeasurement) > 0) {
                    $iValidate = 0;
                    foreach ($validateMeasurement as $keyVal => $validateKol) {
                        $valValidate = $row->$keyVal;
                        if ($valValidate == 0) {
                            $msg = "<br><red class='text-red'>" . htmlspecialchars($row->kode) . " " . htmlspecialchars($row->nama) . "</red><hr><br><red class='text-red'>$validateKol = $valValidate </red><br>silahkan hubungi bagian entry data untuk melengkapi data produk";
                            $alerts = array(
                                "type" => "warning",
                                "title" => strtoupper("Data ukuran produk $produk_label_2 belum lengkap "),
                                "html" => $msg,
                            );
                            echo swalAlert($alerts);
                            die($msg);
                        }
                    }

                }

                if (sizeof($valValidate_items) > 0) {
                    //                    arrPrint($valValidate_items);
                    $msg = "Data pendukung produk belum lengkap<br><red class='text-red'>" . htmlspecialchars($row->kode) . " " . htmlspecialchars($row->nama) . "</red><hr>$jml_now $satuan stock available";
                    $alerts = array(
                        "type" => "warning",
                        "title" => strtoupper($kode),
                        "html" => $msg,
                    );
                    echo swalAlert($alerts);
                    die($msg);
                }

                $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";

                $tmpJml = 1;
                if (isset($lockerConfig['enabled']) && $lockerConfig['enabled'] == true) {
                    cekMerah("masuk locker config");

                    $mdlName = $lockerConfig['mdlName'];
                    $this->load->model("Mdls/" . $mdlName);
                    $c = new $mdlName();
                    $c->addFilter("produk_id='$id'");
                    //                    $c->addFilter("id='$id'");//==id locker
                    $c->addFilter("state='active'");
                    $c->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                    $c->addFilter("gudang_id=" . $this->session->login['gudang_id']);


                    $tmpC = $c->lookupAll($id)->result();
                    cekHere($this->db->last_query());
                    // matiHere(__LINE__. "<hr>");
                    //                    $persediaan = sizeof($tmpC) > 0 ? $tmpC[0]->persediaan : "0";
                    if (sizeof($tmpC) > 0) {
                        // arrPrint($tmpC);
                        // arrPrint($row);
                        $kode = $row->kode;
                        foreach ($tmpC as $row) {
                            $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                            $nama = $row->nama;

                            $jml_now = $row->jumlah;
                            if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                                $jml_sudah_diambil = 0;
                                $jml_diperlukan = 1;
                                $jml_nambah = 1;
                            }
                            else {
                                if (isset($_GET['newQty'])) {
                                    $jml_sudah_diambil = $_SESSION[$cCode]['items'][$id]['jml'];
                                    $jml_diperlukan = $_GET['newQty'];
                                    $jml_nambah = $jml_diperlukan - $jml_sudah_diambil;
                                }
                                else {
                                    $jml_sudah_diambil = $_SESSION[$cCode]['items'][$id]['jml'];
                                    $jml_diperlukan = $jml_sudah_diambil + $jml;
                                    $jml_nambah = $jml;
                                }
                            }
                            //  region validasi stok
                            if ($jml_nambah > $jml_now) {
                                // echo "<script>top.alert('stok $nama tidak cukup. (perlu $jml_diperlukan, nambah $jml_nambah stok $jml_now)')";
                                // echo "</script>";
                                $msg = "Insufficient stock of:<br><red class='text-red'>$kode $nama</red><hr>$jml_now $satuan stock available";
                                $alerts = array(
                                    "type" => "warning",
                                    "title" => strtoupper($kode),
                                    "html" => $msg,
                                );
                                echo swalAlert($alerts);
                                die($msg);

                            }
                            //  endregion validasi stok


                            $this->db->trans_start();

                            //  region update locker active
                            $where = array(
                                "id" => $row->id,
                            );
                            $data_active = array(
                                "jumlah" => $jml_now - $jml_nambah,
                                "state" => "active",
                            );
                            $c->updateData($where, $data_active);
                            cekHere($this->db->last_query());
                            //  endregion update locker active


                            //  region locker hold
                            $array_hold_sebelumnya = $c->cekLoker($this->session->login['cabang_id'], $id, "hold", $this->session->login['id'], "0", $this->session->login['gudang_id']);
                            //                            arrPrint($array_hold_sebelumnya);
                            //                            mati_disini();
                            if (sizeof($array_hold_sebelumnya) > 0) {
                                $where = array(
                                    "id" => $array_hold_sebelumnya['id'],
                                );
                                $data_hold = array(
                                    "jumlah" => $array_hold_sebelumnya['jumlah'] + $jml_nambah,
                                );
                                $c->updateData($where, $data_hold);
                                cekHere($this->db->last_query());
                            }
                            else {
                                $data_hold = array(
                                    "jenis" => "produk",
                                    "cabang_id" => $this->session->login['cabang_id'],
                                    "produk_id" => $id,
                                    "nama" => $nama,
                                    "satuan" => $row->satuan,
                                    "state" => "hold",
                                    "jumlah" => $jml_nambah,
                                    "oleh_id" => $this->session->login['id'],
                                    "oleh_nama" => $this->session->login['nama'],
                                    "gudang_id" => $this->session->login['gudang_id'],
                                );
                                $c->addData($data_hold);
                                cekHere($this->db->last_query());
                            }
                            //  endregion locker hold

                            $this->db->trans_complete() or die("Gagal bro");

                            $tmpJml = $jml_diperlukan;

                        }
                    }
                    else {
                        mati_disini("tidak ditemukan item " . $row->nama . " di locker stock.");
                    }

                }

                if (sizeof($connectedDiscountConfig) > 0) {
                    if ($connectedDiscountConfig['enabled'] == 1) {
                        $mdlNameRelation = $connectedDiscountConfig['mdlNameRelation'];
                        $mdlNameSource = $connectedDiscountConfig['mdlNameSource'];

                        $this->load->model("Mdls/" . $mdlNameRelation);
                        $dr = new $mdlNameRelation();
                        $dr->addFilter("produk_id='$id'");
                        $dr->addFilter("status='1'");
                        $tmpDr = $dr->lookupAll($id)->result();
                        //                        cekMerah($this->db->last_query());
                        //                        arrPrint($tmpDr);
                        $produkQty = isset($_GET['jml']) ? $_GET['jml'] : $tmpJml;
                        foreach ($tmpDr as $drSpec) {
                            $this->load->model("Mdls/" . $mdlNameSource);
                            $sr = new $mdlNameSource();
                            $sr->addFilter("id='" . $drSpec->diskon_id . "'");
                            $sr->addFilter("status='1'");
                            $tmpSr = $sr->lookupAll($id)->result();
                            showLast_query("merah");
                            //                            arrPrint($tmpSr);
                            foreach ($tmpSr as $srSpec) {
                                arrPrint($srSpec);
                                if ($produkQty > $srSpec->max_qty) {
                                    $discountPersen = $srSpec->discount_persen;
                                    $discountQty = $srSpec->discount_qty;
                                }
                                elseif (($produkQty >= $srSpec->min_qty) && ($produkQty <= $srSpec->max_qty)) {
                                    $discountPersen = $srSpec->discount_persen;
                                    $discountQty = $srSpec->discount_qty;
                                }
                                else {
                                    $discountPersen = 0;
                                    $discountQty = 0;
                                }
                                $arrDiscount[$id] = array(
                                    "persen" => $discountPersen,
                                    "qty" => $discountQty,
                                );
                                cekMerah("pID: $id ::: persen: $discountPersen ::: qty: $discountQty");
                            }
                        }
                    }
                }

                $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");

                /** ----------------------------------------------------------------
                 * inisisasi cCode Items harga price
                 * ----------------------------------------------------------------*/
                if (!isset($_SESSION[$cCode]['items']) || !array_key_exists($id, $_SESSION[$cCode]['items'])) {
                    $tmp = array(
                        "handler" => $this->modul . "/" . $this->uri->segment(2),
                        "id" => $id,
                        "jml" => $tmpJml,
                        "harga" => 0,
                        "subtotal" => 0,
                        "satuan" => strlen($rows->satuan) > 0 ? $rows->satuan : "n/a",
                        "discount_persen" => isset($arrDiscount[$id]['persen']) ? $arrDiscount[$id]['persen'] : 0,
                        "discount_qty" => isset($arrDiscount[$id]['qty']) ? $arrDiscount[$id]['qty'] : 0,
                        "harga_jasa" => 0,
                    );

                    if (sizeof($priceMainConfig) > 0) {
                        if (isset($priceMainConfig[$_SESSION[$cCode]['main']['pihakMainName']])) {
                            $priceConfig = $priceMainConfig[$_SESSION[$cCode]['main']['pihakMainName']];
                            cekUngu("masuk disini...");
                        }
                    }

                    //                    cekBiru(__LINE__ . " sebelum price");
                    if (sizeof($priceConfig) > 0) {
                        //                        cekHijau("mmasuk price @" . __LINE__);
                        $mdlName = $priceConfig['model'];
                        $this->load->model("Mdls/" . $mdlName);
                        $h = new $mdlName();
                        if (isset($resetFilter['resetFilter']) && $resetFilter['resetFilter'] == true) {
                            $h->addFilter("produk_id='$id'");
                            //                            $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                            $h->addFilter("cabang_id=" . CB_ID_PUSAT);
                        }
                        else {
                            $h->addFilter("produk_id='$id'");
                            $h->addFilter("status='1'");
                            $h->addFilter("jenis_value in ('" . implode("','", $priceConfig['label']) . "')");
                            //                            $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                            $h->addFilter("cabang_id=" . CB_ID_PUSAT);
                        }

                        if (sizeof($priceFilter) > 0) {
                            foreach ($priceFilter as $f) {
                                $f_ex = explode("=", $f);
                                if (!isset($f_ex[1])) {
                                    $f_ey = explode(">", $f_ex[0]);
                                    if (substr($f_ey[1], 0, 1) == ".") {
                                        $h->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
                                    }
                                    else {
                                        if (isset($_SESSION[$cCode]['main'][$f_ey[1]])) {
                                            $h->addFilter($f_ey[0] . ">'" . $_SESSION[$cCode]['main'][$f_ey[1]] . "'");
                                        }
                                        else {
                                            $h->addFilter($f_ey[0] . ">0");
                                        }
                                    }
                                }
                                else {
                                    if (substr($f_ex[1], 0, 1) == ".") {
                                        $h->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
                                    }
                                    else {
                                        if (isset($_SESSION[$cCode]['main'][$f_ex[1]])) {
                                            $h->addFilter($f_ex[0] . "='" . $_SESSION[$cCode]['main'][$f_ex[1]] . "'");
                                        }
                                        else {
                                            $h->addFilter($f_ex[0] . "=''");
                                        }

                                    }
                                }
                            }
                        }
                        $tmpH = $h->lookupAll($id)->result();
//                                               showLast_query("kuning");
//                                               arrPrint($tmpH);
// matiHere(__LINE__);

                        $hasError = false;
                        if (sizeof($tmpH) > 0) {
                            $rawPrices = array();
                            foreach ($tmpH as $hSpec) {
                                foreach ($priceConfig['key_label'] as $key => $val) {
                                    //                                    cekHitam($key);
                                    if ($resetFilter['resetFilter']) {
                                        //                                        cekBiru("sino$key ||" . $hSpec->$key);
                                        //                                        if ($key == $hSpec->h) {
                                        //                                            cekLime($hSpec->$key);
                                        $rawPrices[$key] = isset($hSpec->$key) ? $hSpec->$key : 0;
                                        //                                        }
                                    }
                                    else {
                                        //                                        cekBiru("sini " . __LINE__);
                                        if ($key == $hSpec->jenis_value) {
                                            $rawPrices[$key] = isset($hSpec->nilai) ? $hSpec->nilai : 0;
                                        }
                                    }

                                }

                            }
                            //                            arrPrintKuning($rawPrices);
                            $prices = normalizePrices("produk", $rawPrices);
                            arrPrint($prices);
                            if (sizeof($prices) > 0) {
                                foreach ($prices as $k => $v) {
                                    $tmp[$k] = $v;
                                }
                                $tmp['harga'] = isset($tmp[$priceConfig['mainSrc']]) ? $tmp[$priceConfig['mainSrc']] : 0;
                                $tmp['harga_reguler'] = isset($tmp[$priceConfig['mainSrc']]) ? $tmp[$priceConfig['mainSrc']] : 0;
                                if ($tmp['harga'] == 0) {
                                    $hasError = true;
                                }
//                                arrprint($tmp);
                                // arrPrintKuning($rawPrices);
                                // arrPrintPink($tmp);
                                // matiDisini(__LINE__);
                            }
                            else {
                                $hasError = true;
                            }
                        }
                        else {
                            $hasError = true;
                        }

                        if ($hasError) {
                            $this->stopWithHargaBelumTersedia(
                                $id,
                                $produk_label_2,
                                isset($item_jenis) ? $item_jenis : ""
                            );
                        }
                    }
                    // arrPrintHijau($tmpH);
                    // arrPrintPink($tmp);
                    //                     matiHere(__LINE__);
                    //------------------------------------------------------
                    foreach ($fieldSrcs as $key => $src) {
                        if (is_array($src) && sizeof($src) > 0) {
                            foreach ($src as $srcSpec) {
                                if (isset($tmp[$srcSpec]) || isset($rows->$srcSpec)) {
                                    cekBiru("ambil gerbang key -> $srcSpec");
                                    $tmp[$key] = makeValue($srcSpec, $tmp, $tmp, isset($rows->$srcSpec) ? $rows->$srcSpec : 0);
                                }
                            }
                        }
                        else {
                            $tmp[$key] = makeValue($src, $tmp, $tmp, isset($rows->$src) ? $rows->$src : 0);
                            //                            cekHere("hasilnya $key -> " . $tmp[$key]);
                        }
                    }

                    if (sizeof($itemNumLabels) > 0) {

                        foreach ($itemNumLabels as $key => $label) {
                            if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                $newValue = $_GET[$key];
                                $tmp[$key] = $newValue;
                                //                    $_SESSION[$cCode]['items'][$id][$key] = $newValue;
                                $tmp[$key] = $newValue;

                            }
                        }
                    }
//                    arrPrint($tmp);
//                    matiHere(__LINE__);

                    if ($subAmountConfig != null) {
                        $tmp['subtotal'] = makeValue($subAmountConfig, $tmp, $tmp, 0);
                    }
                    else {
                        $tmp['subtotal'] = 0;
                    }

                    // arrPrintPink($tmp);
                    // matiHere(__LINE__);
                    $_SESSION[$cCode]['items'][$id] = $tmp;

                }
                else {
                    cekBiru("ada id $id  ada cCode items $cCode");

                    /* ---------------------------------------------
                     * penambahan pilihan harga manual
                     * ---------------------------------------------*/
                    $harga_pilihan = isset($_GET['harga']) ? $_GET['harga'] : false;
                    if ($harga_pilihan != false) {
                        if (isset($_SESSION[$cCode]['items'][$id]['jual'])) {
                            // cekOrange("harga diganti");
                            $_SESSION[$cCode]['items'][$id]['jual'] = $harga_pilihan;

                        }

                        if (isset($_SESSION[$cCode]['items'][$id]) && isset($_GET['rowid'])) {
                            if ($_SESSION[$cCode]['items'][$id]['id'] == $id) {
                                $_SESSION[$cCode]['items'][$id]['row_harga_id'] = $_GET['rowid'];
                            }
                        }

                        if (isset($_GET['rowid'])) {

                            $_SESSION[$cCode]['harga_dipilih'][$id]['rowid'] = $_GET['rowid'];
                            $_SESSION[$cCode]['harga_dipilih'][$id]['harga'] = $harga_pilihan;
                        }
                    }
                    //---end pilihan harga manual----------------------------------------------------

                    // cekBiru("harga_pilihan: $harga_pilihan");
                    // cekBiru("after price  $id");
                    // arrPrint($_SESSION[$cCode]['items']);
                    // arrPrint($_SESSION[$cCode]['harga_dipilih']);
                    // arrPrintBlue($_SESSION[$cCode]['items'], __LINE__);
                    if (isset($_GET['ppn'])) {
                        $_SESSION[$cCode]['items'][$id]['ppnFactor_item'] = $_GET['ppn'];
                    }

                    if (isset($_GET['newQty'])) {
                        $_SESSION[$cCode]['items'][$id]['jml'] = $_GET['newQty'];
                        $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * ($_SESSION[$cCode]['items'][$id]['harga'] + $_SESSION[$cCode]['items'][$id]['ppn']));
                    }
                    else {
                        $_SESSION[$cCode]['items'][$id]['jml'] += $jml;
                        $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * ($_SESSION[$cCode]['items'][$id]['harga'] + (isset($_SESSION[$cCode]['items'][$id]['ppn']) ? $_SESSION[$cCode]['items'][$id]['ppn'] : 0)));
                    }


                    if (isset($arrDiscount[$id]) && sizeof($arrDiscount[$id]) > 0) {
                        foreach ($arrDiscount[$id] as $dKey => $dVal) {
                            if (!isset($_SESSION[$cCode]['items'][$id]['discount_' . $dKey])) {
                                $_SESSION[$cCode]['items'][$id]['discount_' . $dKey] = 0;
                            }
                            $_SESSION[$cCode]['items'][$id]['discount_' . $dKey] = $dVal;
                        }
                        // matiHEre();
                    }

                    // arrPrint($itemNumLabels);
//                    matiHEre();
                    if (sizeof($itemNumLabels) > 0) {

                        foreach ($itemNumLabels as $key => $label) {
                            if (isset($_GET[$key]) && strlen($_GET[$key]) > 0) {
                                if ($key == "disc") {
                                    // matiHEre();
                                    $newValue = pembulatanDiskon($_GET[$key]);
                                }
                                else {
                                    $newValue = $_GET[$key];
                                }

                                $tmp[$key] = $newValue;
                                $_SESSION[$cCode]['items'][$id][$key] = $newValue;

                            }

                        }


                        if ($subAmountConfig != null) {
                            $tmp['subtotal'] = makeValue($subAmountConfig, $_SESSION[$cCode]['items'][$id], $_SESSION[$cCode]['items'][$id], 0);
                        }
                        else {
                            $tmp['subtotal'] = 0;
                        }
                        $_SESSION[$cCode]['items'][$id]['subtotal'] = $tmp['subtotal'];
                    }


                }

                /* ----------------------------------------------------------
                  * diskon-diskonan-grosir
                  * ----------------------------------------------------------*/
                $tmp = $_SESSION[$cCode]['items'][$id];
                $sesmain = $_SESSION[$cCode]["main"];
                $pihak_kategori = $sesmain['kategoriNama'];
                $pro_diskon = $rows->diskon_persen;
                $pro_premi = $rows->premi_jual;
                // cekHitam($pro_premi . " uhui");
                $pro_harga = $tmp['jual'] + (($pro_premi / 100) * $tmp['jual']);
                $pro_harga_reseller = $tmp['jual_reseller'] + (($pro_premi / 100) * $tmp['jual_reseller']);
                // cekBiru("pro_harga_reseller: $pro_harga_reseller");
                // cekBiru("masuk ke diskon2an:: $pro_premi || $pro_harga ori:".$tmp['jual']." @" . __LINE__);
                // cekKuning("$pihak_kategori");
                cekKuning("$pro_harga");
                $pro_jml = $tmp['jml'];

                if ($pihak_kategori == "distributor") {
                    $calc_hasils = $ld->selectorDiskon($id, $pro_harga_reseller, $pro_jml, $rows, $sesmain);
                    arrPrintHijau($calc_hasils);
                    $calc_hasil = $calc_hasils["grosir"];
                }
                else {
                    $calc_hasils = $ld->selectorDiskon($id, $pro_harga, $pro_jml, $rows);
                    $calc_hasil = $calc_hasils["simple"];
                }

                //                arrPrintKuning($calc_hasil);
                //                 cekHijau($gudang_status_id);
                $stok_booking = isset($lsb_datas[$id][$gudang_status_id]) ? $lsb_datas[$id][$gudang_status_id]['sum_valid_qty'] : "0";
                // arrPrintPink($stok_booking);
                // matiHere(__LINE__);
                $tmp['stok_booking'] = $stok_booking;
                // $tmp['stok_booking_center'] = 99;
                $tmp['discPersen'] = $calc_hasil['persen'];
                $tmp['lastNett'] = $calc_hasil['harga_af'];
                // $tmp['harga'] = $pihak_kategori == "reguler" ? $pro_harga : $calc_hasil['harga_af'];
                /* ---------------------------------------------------------------------------
                 * kategori ada 3: reguler distributor online
                 * yg mendapat diskon berjenang hanya distributor
                 * jika ada premi semua diskon off
                 * ---------------------------------*/
                $yg_dipakai = 2;
                if ($yg_dipakai == 1) {
                    if ($pro_premi > 0) {
                        $harga_yg_dipakai = $pro_harga;
                    }
                    elseif ($pro_premi == 0) {
                        $harga_yg_dipakai = $calc_hasil['harga_af'];
                    }
                    else {
                        if ($pihak_kategori == "distributor") {
                            $harga_yg_dipakai = $calc_hasil['harga_af'];
                        }
                        else {
                            $harga_yg_dipakai = $pro_harga;
                        }
                    }
                }
                elseif ($yg_dipakai == 2) {
                    if ($pihak_kategori == "distributor") {
                        if ($pro_premi > 0) {
                            $harga_yg_dipakai = $pro_harga;
                            $jual_dipakai = $pro_harga;
                        }
                        else {
                            $harga_yg_dipakai = $calc_hasil['harga_af'];
                            $jual_dipakai = $pro_harga_reseller;
                        }
                    }
                    else {
                        if ($pro_premi > 0) {
                            $harga_yg_dipakai = $pro_harga;
                            $jual_dipakai = $pro_harga;
                        }
                        else {
                            $harga_yg_dipakai = $calc_hasil['harga_af'];
                            $jual_dipakai = $pro_harga;
                        }
                    }
                }

//                matiHEre(__LINE__);
                $tmp['jual_dipakai'] = $jual_dipakai;
                $tmp['harga'] = $harga_yg_dipakai;
                // ------------------------------------------------------end--------------------
                $tmp['harga_jual'] = $calc_hasil['harga_be'] * $tmp['satuan_factor_qty'];
                $tmp['harga_disc'] = ($calc_hasil['harga_af'] * $tmp['satuan_factor_qty']) * $tmp['qty_unit'];

                $tmp['discNilai'] = $calc_hasil['nilai'] * $tmp['satuan_factor_qty'];
                $tmp['id'] = $id;

                $tmp['subtotal'] = $calc_hasil['harga_af'] * $pro_jml;
                // -------------------------------------------------------------------
                // $produk_jenis["jml"] = $pro_jml;
                // memasukkan kolom sku ke items2
                // handle serial 1 dan scan mode
                $jml_serial = $rows->jml_serial;
                $tmp['jml_serial'] = $jml_serial;
                $tmp['scan_mode'] = $jml_serial > 0 ? "serial" : "simple";
                if ($jml_serial * 1 == 1) {
                    $d_kode = $rows->kode;
                    $_SESSION[$cCode]['items2'][$produk_id][$d_kode] = array();
                }
                // matiHere("====|scan_mode:".$tmp['scan_mode']."|====$cCode====|serial:".$tmp['jml_serial']."|====");

                $arrCat = array();
                $arrCode = array();
                if ($produk_jenis == "unit") {
                    foreach ($arrDataTambahan as $cat => $catSpec) {
                        foreach ($catSpec as $dkey => $dval) {
                            if (isset($rows->$dval) && ($rows->$dval != NULL)) {
                                $_SESSION[$cCode]['items2'][$produk_id][$rows->$dval] = array();
                                //--------------
                                if (!isset($arrCat[$cat])) {
                                    $arrCat[$cat] = 0;
                                }
                                $arrCat[$cat] += 1;
                                //--------------
                                if (!isset($arrCode[$rows->$dval])) {
                                    $arrCode[$rows->$dval] = 0;
                                }
                                $arrCode[$rows->$dval] += 1;
                                //--------------
                            }
                        }
                    }
                }
                else {
                    $_SESSION[$cCode]['items2'][$produk_id][$rows->kode] = array();
                    $arrCat["barcode"] = 1;
                    $arrCode[$rows->kode] = 1;
                }
                $keterangan = "";
                $static_keterangan = "";
                if (sizeof($arrCat) > 0) {
                    foreach ($arrCat as $kcat => $vcat) {
                        $new_vcat = $vcat * $_SESSION[$cCode]['items'][$id]["jml"];
                        if ($keterangan == "") {
                            $keterangan = " $new_vcat $kcat";
                        }
                        else {
                            $keterangan .= "<br> $new_vcat $kcat";
                        }
                        if ($static_keterangan == "") {
                            $static_keterangan = " $vcat $kcat";
                        }
                        else {
                            $static_keterangan .= "<br> $vcat $kcat";
                        }
                        $new_keyy = "qty_" . $kcat;
                        $tmp[$new_keyy] = $vcat;
                    }
                }
                if (sizeof($arrCode) > 0) {
                    foreach ($arrCode as $kcat => $vcat) {
                        $new_vcat = $vcat * $_SESSION[$cCode]['items'][$id]["jml"];
                        $tmp[$kcat] = $new_vcat;
                    }
                }
                $tmp['keterangan'] = $keterangan;
                $tmp['static_keterangan'] = $static_keterangan;
                //----------------------------------------
                $_SESSION[$cCode]['items'][$produk_id] = $tmp;
                $pakai_ini = 1;
                if ($pakai_ini == 1) {
                    if ($item_jenis == "item_komposit") {
                        $_SESSION[$cCode]['items6'][$produk_id] = array();
                        $_SESSION[$cCode]['items7'][$produk_id] = array();
                        $this->load->model("Mdls/MdlProduk2");
                        $this->load->model("Mdls/MdlProdukKompositKomposisi");
                        $pp = new MdlProduk2();
                        $kk = new MdlProdukKompositKomposisi();
                        $kk->addFilter("produk_id=$id'");
                        $tmpKomposit = $kk->lookUpAll()->result();

                        if (count($tmpKomposit) > 0) {
                            $qty_faktor = isset($_GET['newQty']) ? $_GET['newQty'] : 1;
                            $idProduk_komposit = array();
                            $priceKomposit = array();
                            $items8 = array();
                            foreach ($tmpKomposit as $tmpKomposit_0) {
                                $idProduk_komposit[] = $tmpKomposit_0->produk_dasar_id;
                                $priceKomposit[$tmpKomposit_0->produk_dasar_id] = array(
                                    "harga" => $tmpKomposit_0->harga / $ppnFactorInclude,
                                    "jml" => $tmpKomposit_0->jml * $qty_faktor,
                                );
                                $items8[$tmpKomposit_0->produk_dasar_id] = array(
                                    "id" => $tmpKomposit_0->id,
                                    "produk_id" => $tmpKomposit_0->produk_id,
                                    "produk_nama" => $tmpKomposit_0->produk_nama,
                                    "produk_dasar_id" => $tmpKomposit_0->produk_dasar_id,
                                    "produk_dasar_nama" => $tmpKomposit_0->produk_dasar_nama,
                                    "jml" => $tmpKomposit_0->jml,
                                    "qty" => $tmpKomposit_0->jml,
                                    "harga" => $tmpKomposit_0->harga / $ppnFactorInclude,
                                    "harga_nppn" => $tmpKomposit_0->harga,
                                );
                                $_SESSION[$cCode]["items_komposisi"][$tmpKomposit_0->produk_id] = $items8;
                            }
                            $pp->addFilter("id in ('" . implode("','", $idProduk_komposit) . "')");
                            $tmpDataProdukKomposisi = $pp->lookUpAll()->result();
                            foreach ($tmpDataProdukKomposisi as $tmpProdukKomposisiPaket) {
                                $produk_jenis_paket = $tmpProdukKomposisiPaket->kategori_nama;
                                $tmpPaket = array(
                                    "id" => $tmpProdukKomposisiPaket->id,
                                    "jml" => $priceKomposit[$tmpProdukKomposisiPaket->id]["jml"],
                                    "qty" => $priceKomposit[$tmpProdukKomposisiPaket->id]["jml"],
                                    "harga" => $priceKomposit[$tmpProdukKomposisiPaket->id]["harga"],
                                    "subtotal" => $priceKomposit[$tmpProdukKomposisiPaket->id]["jml"] * $priceKomposit[$tmpProdukKomposisiPaket->id]["harga"],
                                    "satuan" => strlen($tmpProdukKomposisiPaket->satuan) > 0 ? $tmpProdukKomposisiPaket->satuan : "n/a",
                                    "harga_jasa" => 0,
                                );
                                foreach ($fieldSrcs as $key => $src) {
                                    if (is_array($src) && sizeof($src) > 0) {
                                        foreach ($src as $srcSpec) {
                                            if (isset($tmpPaket[$srcSpec]) || isset($tmpProdukKomposisiPaket->$srcSpec)) {
                                                cekBiru("ambil gerbang key -> $srcSpec");
                                                $tmpPaket[$key] = makeValue($srcSpec, $tmpPaket, $tmpPaket, isset($tmpProdukKomposisiPaket->$srcSpec) ? $tmpProdukKomposisiPaket->$srcSpec : 0);
                                            }
                                        }
                                    }
                                    else {
                                        $tmpPaket[$key] = makeValue($src, $tmpPaket, $tmpPaket, isset($tmpProdukKomposisiPaket->$src) ? $tmpProdukKomposisiPaket->$src : 0);
                                        //                            cekHere("hasilnya $key -> " . $tmp[$key]);
                                    }

                                }
                                $jml_serial_paket = $tmpProdukKomposisiPaket->jml_serial;
                                $tmpPaket['jml_serial'] = $jml_serial_paket;
                                $tmpPaket['scan_mode'] = $jml_serial_paket > 0 ? "serial" : "simple";
                                if ($jml_serial_paket * 1 == 1) {
                                    $d_kode = $tmpProdukKomposisiPaket->kode;
                                    $_SESSION[$cCode]['items7'][$produk_id][$tmpProdukKomposisiPaket->id][$d_kode] = array();
                                }
                                // matiHere("====|scan_mode:".$tmp['scan_mode']."|====$cCode====|serial:".$tmp['jml_serial']."|====");

                                $arrCat = array();
                                $arrCode = array();
                                if ($produk_jenis_paket == "unit") {
                                    foreach ($arrDataTambahan as $cat => $catSpec) {
                                        foreach ($catSpec as $dkey => $dval) {
                                            if (isset($tmpProdukKomposisiPaket->$dval) && ($tmpProdukKomposisiPaket->$dval != NULL)) {
                                                $_SESSION[$cCode]['items7'][$produk_id][$tmpProdukKomposisiPaket->id][$tmpProdukKomposisiPaket->$dval] = array();
                                                //--------------
                                                if (!isset($arrCat[$cat])) {
                                                    $arrCat[$cat] = 0;
                                                }
                                                $arrCat[$cat] += 1;
                                                //--------------
                                                if (!isset($arrCode[$tmpProdukKomposisiPaket->$dval])) {
                                                    $arrCode[$tmpProdukKomposisiPaket->$dval] = 0;
                                                }
                                                $arrCode[$tmpProdukKomposisiPaket->$dval] += 1;
                                                //--------------
                                            }
                                        }
                                    }
                                }
                                else {
                                    $_SESSION[$cCode]['items7'][$produk_id][$tmpProdukKomposisiPaket->id][$tmpProdukKomposisiPaket->kode] = array();
                                    $arrCat["barcode"] = 1;
                                    $arrCode[$tmpProdukKomposisiPaket->kode] = 1;
                                }
                                $keterangan = "";
                                $static_keterangan = "";
                                if (sizeof($arrCat) > 0) {
                                    foreach ($arrCat as $kcat => $vcat) {
                                        $new_vcat = $vcat * $priceKomposit[$tmpProdukKomposisiPaket->id]["jml"];
                                        if ($keterangan == "") {
                                            $keterangan = " $new_vcat $kcat";
                                        }
                                        else {
                                            $keterangan .= "<br> $new_vcat $kcat";
                                        }
                                        if ($static_keterangan == "") {
                                            $static_keterangan = " $vcat $kcat";
                                        }
                                        else {
                                            $static_keterangan .= "<br> $vcat $kcat";
                                        }
                                        $new_keyy = "qty_" . $kcat;
                                        $tmpPaket[$new_keyy] = $vcat;
                                    }
                                }
                                if (sizeof($arrCode) > 0) {
                                    foreach ($arrCode as $kcat => $vcat) {
                                        $new_vcat = $vcat * $priceKomposit[$tmpProdukKomposisiPaket->id]["jml"];
                                        $tmpPaket[$kcat] = $new_vcat;
                                    }
                                }
                                $tmpPaket['keterangan'] = $keterangan;
                                $tmpPaket['static_keterangan'] = $static_keterangan;
                                $tmpPaket['produk_paket_id'] = $produk_id;
                                $tmpPaket['produk_paket_nama'] = $row->nama;

                                $_SESSION[$cCode]["items6"][$produk_id][$tmpProdukKomposisiPaket->id] = $tmpPaket;


                            }
                        }
                        else {
//                            matiHere("produk paket belum memiliki komposisi !. Silahkan perbaiki data dari menu data produk penjualan paket");
                        }

                        //arrprint($tmpKomposit);
                    }
                }


            }

            /* -----------------------------------------------------------
            * diskon unit/non unit
            * -----------------------------------------------------------*/
            unset($_SESSION[$cCode]['items_kategori']);
            foreach ($_SESSION[$cCode]['items'] as $item) {
                $kategori_produk = $item["kategori_nama"];
                $_SESSION[$cCode]["main"]["jml_kategori_$kategori_produk"] = 0;
            }
            foreach ($_SESSION[$cCode]['items'] as $item) {
                $pro_jml = $item['jml'];
                $produk_jenis = str_replace(" ", "_", $item['kategori_nama']);

                if (!isset($_SESSION[$cCode]['items_kategori'][$produk_jenis]['jml'])) {
                    $_SESSION[$cCode]['items_kategori'][$produk_jenis]['jml'] = 0;
                }
                $_SESSION[$cCode]['items_kategori'][$produk_jenis]['jml'] += $pro_jml;
                $kategori_produk = $item["kategori_nama"];
                if (!isset($_SESSION[$cCode]["main"]["jml_kategori_$kategori_produk"])) {
                    $_SESSION[$cCode]["main"]["jml_kategori_$kategori_produk"] = 0;
                }
                $_SESSION[$cCode]["main"]["jml_kategori_$kategori_produk"] += $pro_jml;

            }
            // arrPrint($_SESSION[$cCode]["main"]);
            // ---------------------------------------------------------

            $potongan_nilai = $ld->selectorDiskonKategori($_SESSION[$cCode]);

            arrPrintPink($potongan_nilai);
            // arrPrintKuning($produk_jenis);
            cekHijau("membuat session main");
            if (ipadd() == "202.65.117.72") {
                //                mati_disini(__LINE__);
            }
            $rows2 = array();
            $pro_premi2 = 0;
            foreach ($potongan_nilai as $dcu_kategori => $dcu) {
                $nilai_dcu = $dcu['nilai'];
                $_SESSION[$cCode]["main"]["diskon_kategori_$dcu_kategori"] = $nilai_dcu;
                $_SESSION[$cCode]["main"]["jml_kategori_$dcu_kategori"] = $dcu['jml'];

                // if ($nilai_dcu > 0) {
                $sesmain2 = $_SESSION[$cCode]["main"];
                $pihak_kategori2 = $sesmain2["kategoriNama"];
                cekHere("update harga yg dipakai");
                foreach ($_SESSION[$cCode]['items'] as $pro_id => $item_speks) {
                    /* --------------------------------------------------
                     * pilih yg sebagai dasar mau harga list atau harga distributor/reseller
                     * ---------------------------------------------------*/
                    $pro_harga2 = $item_speks['jual'] + (($pro_premi2 / 100) * $item_speks['jual']);
                    $pro_harga_reseller2 = $item_speks['jual_reseller'] + (($pro_premi2 / 100) * $item_speks['jual_reseller']);
                    // ------------------------------------------------------------------------
                    $pro_jml2 = $item_speks['jml'];

                    if ($nilai_dcu > 0) {
                        if (!isset($item_speks['jual_reseller'])) {
                            $pro_harga_dipakai = $pro_harga2;
                        }
                        else {
                            $pro_harga_dipakai = $pro_harga_reseller2;
                        }
                        $calc_hasils = $ld->selectorDiskon($pro_id, $pro_harga_dipakai, $pro_jml2, $rows2, $sesmain2);
                        //                        $calc_hasils = $ld->selectorDiskon($pro_id, $pro_harga_reseller2, $pro_jml2, $rows2, $sesmain2);
                        $calc_hasil = $calc_hasils["grosir"];
                    }
                    else {
                        //     // if ($pihak_kategori2 == "distributor") {
                        cekOrange("harusnya tidak diskon " . __LINE__);
                        // $calc_hasils = $ld->selectorDiskon($pro_id, $pro_harga2, $pro_jml2, $rows2, $sesmain2);
                        // $calc_hasil = $calc_hasils["grosir"];
                        $calc_hasil = array(
                            "type" => "diskon",
                            "persen" => "0",
                            "nilai" => "0",
                            "harga_be" => $pro_harga2,
                            "harga_af" => $pro_harga2,
                        );
                        arrPrintHijau($calc_hasils);
                    }

                    cekHitam("$pro_id ---------");
                    arrPrintWebs($calc_hasils);

                    $tmp2['discPersen'] = $calc_hasil['persen'];
                    $tmp2['lastNett'] = $calc_hasil['harga_af'];
                    $tmp2['jual_dipakai'] = $pro_harga_reseller2;
                    $tmp2['harga'] = $calc_hasil['harga_af'];

                    arrPrintKuning($tmp2);

                    /* ----------------------------------------------------------------------
                     * ngupdate session items pada key2 tertentu saja spt yg didefine diatasnya
                     * ----------------------------------------------------------------------*/
                    foreach ($tmp2 as $sesKey => $newSesValue) {
                        $_SESSION[$cCode]['items'][$pro_id][$sesKey] = $newSesValue;
                    }
                }

                // }
            }
            // --------------------------en kategori diskon----------------------

            /* -----------------------------------------------------------------
             * ngupdate harga yg dipakai per item
             * -----------------------------------------------------------------*/
            // if(isset($potongan_nilai) && (count($potongan_nilai) > 0) && ($potongan_nilai['nilai'] > 0)){
            //     cekHere("update harga yg dipakai");
            //     foreach ($_SESSION[$cCode]['items'] as $item){
            //
            //     }
            // }


        }
        else {
            cekMerah("tidak ada itemnya! @" . __LINE__ . " " . __METHOD__);
            die();
        }

        $f_selector = "";
        if (isset($_GET['selector'])) {
            $f_selector = "selector&";
        }

        //-----------------------------------------------------
        $dtime_now = dtimeNow();
        $dtime_now_ex = explode(" ", $dtime_now);
        $date_now = str_replace("-", "", $dtime_now_ex[0]);
        $time_now = str_replace(":", "", $dtime_now_ex[1]);
        $bookingNumber = "$date_now" . "$time_now";
        if (!isset($_SESSION[$cCode]["main"]["bookingNumber"]) || ($_SESSION[$cCode]["main"]["bookingNumber"] == null)) {
            $_SESSION[$cCode]["main"]["bookingNumber"] = $bookingNumber;
        }
        //-----------------------------------------------------
        //        arrprintwebs($_SESSION[$cCode]["items6"]);
        //        matiHere();
        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setppnFactor($ppnFactor);


        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, $stepNum, $this->configUiJenis);

        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);
        //        arrprint($_SESSION[$cCode]["items6"]);
        //        matiHere();
        // matiHere(__METHOD__ . __LINE__);
        /* --------------------------------------------------
         * ngereload shoping cart dlm modul
         * --------------------------------------------------*/

        if (isset($_GET['minValue']) || isset($_GET['rowid']) || isset($_GET['ppn'])) {
        echo "<script>";
            echo "top.console.log('minValue isset shoppingcart direload');";
        echo "  if(top.document.getElementById('shopping_cart')){";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "  }";
        echo "</script>";
        }
        else {
            echo "<script>";
            echo "top.calcShoppingCartPettycash();";
            echo "</script>";
        }


    }

    public function multiSelect()
    {
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $items = $_GET['items'];

        $arrItems = isset($_GET['items']) ? unserialize(base64_decode($items)) : array();
        // id_produk => qty

        $arrTrID = isset($_GET['trs']) ? unserialize(base64_decode($_GET['trs'])) : array();

        $arrMain = isset($_GET['main']) ? unserialize(base64_decode($_GET['main'])) : array();

        $cCode = "_TR_" . $this->jenisTr;
        $toko_id = my_toko_id();

        $selectorModel = $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = $this->configUi[$this->jenisTr]['selectorSrcModel'];

        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;

        if (sizeof($arrItems) > 0) {
            foreach ($arrItems as $id => $jmlParam) {

                $tmpB = $b->lookupByID($id)->result();
//                cekHere($this->db->last_query());
//                arrPrint($tmpB);

                $jml = $jmlParam;
                if (sizeof($tmpB) > 0) {
                    foreach ($tmpB as $row) {
                        $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                        $tmpJml = $jmlParam;
                        if (isset($lockerConfig['enabled']) && $lockerConfig['enabled'] == true) {
                            cekMerah("masuk locker config");

                            $mdlName = $lockerConfig['mdlName'];
                            $this->load->model("Mdls/" . $mdlName);
                            $c = new $mdlName();
                            $c->addFilter("produk_id='$id'");
                            $c->addFilter("state='active'");
                            $c->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                            $c->addFilter("gudang_id=" . $this->session->login['gudang_id']);
                            $tmpC = $c->lookupAll($id)->result();
                            cekHere($this->db->last_query());


                            if (sizeof($tmpC) > 0) {
                                arrPrint($tmpC);
                                foreach ($tmpC as $row) {
                                    $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                                    $nama = $row->nama;

                                    $jml_now = $row->jumlah;
                                    if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                                        $jml_sudah_diambil = 0;
                                        $jml_diperlukan = 1;
                                        $jml_nambah = 1;
                                    }
                                    else {
                                        if (isset($_GET['newQty'])) {
                                            $jml_sudah_diambil = $_SESSION[$cCode]['items'][$id]['jml'];
                                            $jml_diperlukan = $_GET['newQty'];
                                            $jml_nambah = $jml_diperlukan - $jml_sudah_diambil;
                                        }
                                        else {
                                            $jml_sudah_diambil = $_SESSION[$cCode]['items'][$id]['jml'];
                                            $jml_diperlukan = $jml_sudah_diambil + $jml;
                                            $jml_nambah = $jml;
                                        }
                                    }
                                    //  region validasi stok
                                    if ($jml_nambah > $jml_now) {
                                        echo "<script>top.alert('stok $nama tidak cukup. (perlu $jml_diperlukan, nambah $jml_nambah stok $jml_now)')";
                                        echo "</script>";
                                        die();
                                    }
                                    //  endregion validasi stok


                                    $this->db->trans_start();

                                    //  region update locker active
                                    $where = array(
                                        "id" => $row->id,
                                    );
                                    $data_active = array(
                                        "jumlah" => $jml_now - $jml_nambah,
                                        "state" => "active",
                                    );
                                    $c->updateData($where, $data_active);
                                    cekHere($this->db->last_query());
                                    //  endregion update locker active


                                    //  region locker hold
                                    $array_hold_sebelumnya = $c->cekLoker($this->session->login['cabang_id'], $id, "hold", $this->session->login['id'], "0", $this->session->login['gudang_id']);
                                    if (sizeof($array_hold_sebelumnya) > 0) {
                                        $where = array(
                                            "id" => $array_hold_sebelumnya['id'],
                                        );
                                        $data_hold = array(
                                            "jumlah" => $array_hold_sebelumnya['jumlah'] + $jml_nambah,
                                        );
                                        $c->updateData($where, $data_hold);
                                        cekHere($this->db->last_query());
                                    }
                                    else {
                                        $data_hold = array(
                                            "jenis" => "produk",
                                            "cabang_id" => $this->session->login['cabang_id'],
                                            "produk_id" => $id,
                                            "nama" => $nama,
                                            "satuan" => $row->satuan,
                                            "state" => "hold",
                                            "jumlah" => $jml_nambah,
                                            "oleh_id" => $this->session->login['id'],
                                            "oleh_nama" => $this->session->login['nama'],
                                            "gudang_id" => $this->session->login['gudang_id'],
                                        );
                                        $c->addData($data_hold);
                                        cekHere($this->db->last_query());
                                    }
                                    //  endregion locker hold


                                    $this->db->trans_complete() or die("Gagal bro");

                                    $tmpJml = $jml_diperlukan;

                                }
                            }
                            else {
                                mati_disini("tidak ditemukan item " . $row->nama . " di locker stock.");
                            }

                        }

                        $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
                        if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                            $tmp = array(
                                "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
                                "id" => $id,
                                "jml" => $tmpJml,
                                "harga" => 0,
                                "subtotal" => 0,
                            );

                            if (sizeof($priceConfig) > 0) {
                                $mdlName = $priceConfig['model'];
                                $this->load->model("Mdls/" . $mdlName);
                                $h = new $mdlName();
                                $h->addFilter("produk_id='$id'");
                                $h->addFilter("status='1'");
                                //                                $h->addFilter("jenis_value='" . $priceConfig['label'] . "'");
                                $h->addFilter("jenis_value in ('" . implode("','", $priceConfig['label']) . "')");
                                $h->addFilter("toko_id=" . $toko_id);
                                $tmpH = $h->lookupAll($id)->result();
                                cekMerah($this->db->last_query());
                                if (sizeof($tmpH) > 0) {
                                    $rawPrices = array();
                                    foreach ($tmpH as $hSpec) {
                                        foreach ($priceConfig['key_label'] as $key => $val) {
                                            if ($key == $hSpec->jenis_value) {
                                                $rawPrices[$key] = isset($hSpec->nilai) ? $hSpec->nilai : 0;
                                            }
                                        }
                                    }
                                    $prices = normalizePrices("produk", $rawPrices);
                                    if (sizeof($prices) > 0) {
                                        foreach ($prices as $k => $v) {
                                            $tmp[$k] = $v;
                                        }
                                        $tmp['harga'] = isset($tmp[$priceConfig['mainSrc']]) ? $tmp[$priceConfig['mainSrc']] : 0;
                                    }
                                }

                            }

                            foreach ($fieldSrcs as $key => $src) {
                                $tmpEx = $cal->multiExplode($src);
//                                arrPrint($tmpEx);
                                if (sizeof($tmpEx) > 1) {//===berarti mengandung karakter simbol perhitungan
                                    cekBiru("$key perhitungan");
                                    $newSrc = $src;
                                    foreach ($tmpEx as $key2 => $val2) {
                                        echo "$key2 - $val2 <br>";
                                        if (!is_numeric($val2)) {
                                            if (isset($tmp[$val2]) && $tmp[$val2] > 0) {
                                                $newSrc = str_replace($val2, $tmp[$val2], $newSrc);
                                            }
                                            else {
                                                $newSrc = str_replace($val2, 0, $newSrc);
                                            }
                                        }

                                    }
                                    cekBiru("$$src -> $newSrc -> " . $cal->calculate($newSrc));
                                    $tmp[$key] = $cal->calculate($newSrc);
                                }
                                else {
                                    cekBiru("$key BUKAN perhitungan");
                                    $tmp[$key] = $row->$src;
                                }


                            }

                            //===perhitungan subtotal
                            $cal = new FieldCalculator();


                            if (sizeof($arrMain) > 0) {
                                foreach ($arrMain as $key => $val) {
                                    $_SESSION[$cCode][$key] = $val;
                                }
                            }

                            if ($subAmountConfig != null) {
                                $tmpEx = $cal->multiExplode($subAmountConfig);
                                if (sizeof($tmpEx) > 1) {
                                    $newSrc = $subAmountConfig;
                                    foreach ($tmpEx as $key2 => $val2) {
                                        if (isset($tmp[$val2])) {
                                            $newSrc = str_replace($val2, $tmp[$val2], $newSrc);
                                            cekKuning("$val2 direplace dengan " . $tmp[$val2]);
                                        }
                                        else {
                                            $newSrc = str_replace($val2, "0", $newSrc);
                                            cekKuning("$val2 direplace dengan NOL");
                                        }

                                    }
                                    $subtotal = $cal->calculate($newSrc);
                                    cekHijau("subtotal dari perhitungan $subAmountConfig $newSrc");

                                }
                                else {
                                    $subtotal = 0;
                                    cekHijau("subtotal dari perhitungan yang gak ada");
                                }
                            }
                            else {
                                $subtotal = 0;
                                cekHijau("subtotal NOL");
                            }
                            $tmp["subtotal"] = $subtotal;
                            $_SESSION[$cCode]['items'][$id] = $tmp;

                            //                    die();
                        }
                        else {
                            if (isset($_GET['newQty'])) {
                                $_SESSION[$cCode]['items'][$id]['jml'] = $_GET['newQty'];
                                $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
                            }
                            else {
                                $_SESSION[$cCode]['items'][$id]['jml'] += $jml;
                                $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
                            }

                            if (sizeof($itemNumLabels) > 0) {

                                foreach ($itemNumLabels as $key => $label) {
                                    if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                        $newValue = $_GET[$key];
                                        $tmp[$key] = $newValue;
                                        $_SESSION[$cCode]['items'][$id][$key] = $newValue;

                                    }

                                }

                                foreach ($itemNumLabels as $key => $label) {
                                    $_SESSION[$cCode]['items'][$id]["sub_" . $key] = ($_SESSION[$cCode]['items'][$id][$key] * $_SESSION[$cCode]['items'][$id]["jml"]);
                                }
                                $_SESSION[$cCode]['items'][$id]['sub_nett'] = ($_SESSION[$cCode]['items'][$id]['nett'] * $_SESSION[$cCode]['items'][$id]['jml']);

                                $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
                            }


                        }
                    }

                    if (sizeof($_SESSION[$cCode]['items']) > 0) {
                        $_SESSION[$cCode]['main']['harga'] = 0;
                        $_SESSION[$cCode]['out_master']['harga'] = 0;

                        /*
                         * akumulasi item ke main
                         * */
                        foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                            $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
                            $_SESSION[$cCode]['out_master']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
                        }
                    }

                }
                else {
                    cekMerah("tidak ada itemnya!");
                    die();
                }

            }
        }

        if (sizeof($arrTrID) > 0) {
            $_SESSION[$cCode]['main']['references'] = $arrTrID;
            $_SESSION[$cCode]['out_master']['references'] = $arrTrID;
        }
        if (isset($_GET['singleRefID']) && strlen($_GET['singleRefID']) > 0) {
            $_SESSION[$cCode]['main']['singleReference'] = $_GET['singleRefID'];
            $_SESSION[$cCode]['out_master']['singleReference'] = $_GET['singleRefID'];
        }


        //-----------------------------------------------------
        $dtime_now = dtimeNow();
        $dtime_now_ex = explode(" ", $dtime_now);
        $date_now = str_replace("-", "", $dtime_now_ex[0]);
        $time_now = str_replace(":", "", $dtime_now_ex[1]);
        $bookingNumber = "$date_now" . "$time_now";
        if (!isset($_SESSION[$cCode]["main"]["bookingNumber"]) || ($_SESSION[$cCode]["main"]["bookingNumber"] == null)) {
            $_SESSION[$cCode]["main"]["bookingNumber"] = $bookingNumber;
        }
        //-----------------------------------------------------

        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = array(
            "olehID" => my_id(),
            "olehName" => my_name(),
            "placeID" => my_cabang_id(),
            "placeName" => my_cabang_nama(),
            "divID" => my_div_id(),
            "divName" => my_div_nama(),
            "cabangID" => my_cabang_id(),
            "cabangName" => my_cabang_nama(),
            "gudangID" => my_gudang_id(),
            "gudangName" => my_gudang_nama(),
            "jenis_usaha" => my_jenis_usaha(),
            "tokoID" => my_toko_id(),
            "tokoNama" => my_toko_nama(),
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
            "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
            "stepNumber" => 1,
            "stepCode" => $this->configUiJenis['steps'][1]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        echo "<script>";
        echo "  if(top.document.getElementById('shopping_cart')){";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "  }";
        echo "</script>";
    }

    public function test()
    {
        matiHere(__FILE__);
    }

    public function remove()
    {
        $id = $_GET['id'];
        $cCode = "_TR_" . $this->jenisTr;
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $stepNum = $this->uri->segment(5) > 0 ? $this->uri->segment(5) : 1;
        $ppnFactor = isset($_SESSION[$cCode]["main"]["ppnFactor"]) ? $_SESSION[$cCode]["main"]["ppnFactor"] : my_ppn_factor();
        if (isset($lockerConfig['enabled']) && $lockerConfig['enabled'] == true) {
            cekBiru("melibatkan session");
            if (isset($_SESSION[$cCode]['items'][$id])) {
                cekBiru("ada barang, cek lokernya");
                $this->db->trans_start();

                $mdlName = $lockerConfig['mdlName'];
                $this->load->model("Mdls/" . $mdlName);

                $c = new $mdlName();
                $array_hold_sebelumnya = $c->cekLoker($this->session->login['cabang_id'], $id, "hold", $this->session->login['id'], "0", $this->session->login['gudang_id']);
                $where = array(
                    "id" => $array_hold_sebelumnya['id'],
                );
                $data_hold = array(
                    "jumlah" => 0,
                );
                $c->updateData($where, $data_hold);


                $c = new $mdlName();
                $array_active_sebelumnya = $c->cekLoker($this->session->login['cabang_id'], $id, "active", "0", "0", $this->session->login['gudang_id']);
                $where = array(
                    "id" => $array_active_sebelumnya['id'],
                );
                $data_active = array(
                    "jumlah" => $array_active_sebelumnya['jumlah'] + $array_hold_sebelumnya['jumlah'],
                );
                $c->updateData($where, $data_active);


                $this->db->trans_complete() or die("Gagal bro");
            }
            else {
                cekBiru("TIDAK ada barang, ga jadi cek loker");
            }
        }
        else {
            cekBiru("TIDAK melibatkan session @" . __CLASS__);
        }

        if (isset($_GET['id'])) {

            if (isset($_SESSION[$cCode]['items'][$id])) {
                $_SESSION[$cCode]['items'][$id] = null;
                unset($_SESSION[$cCode]['items'][$id]);
                $_SESSION[$cCode]['items'][$id] = null;
                unset($_SESSION[$cCode]['items'][$id]);
                //            $_SESSION[$cCode]['out_detail'][$id] = null;
                //            unset($_SESSION[$cCode]['out_detail'][$id]);
                //            $_SESSION[$cCode]['out_detail2'][$id] = null;
                //            unset($_SESSION[$cCode]['out_detail2'][$id]);
                /* -------------------------------------------------------
                 * kategori item untuk diskon unit/non-unit
                 * -------------------------------------------------------*/
                unset($_SESSION[$cCode]['items_kategori']);
                foreach ($_SESSION[$cCode]['items'] as $item) {
                    $kategori_produk = $item["kategori_nama"];
                    $_SESSION[$cCode]["main"]["jml_kategori_$kategori_produk"] = 0;
                }
                foreach ($_SESSION[$cCode]['items'] as $item) {
                    $pro_jml = $item['jml'];
                    $produk_jenis = $item['kategori_nama'];

                    if (!isset($_SESSION[$cCode]['items_kategori'][$produk_jenis]['jml'])) {
                        $_SESSION[$cCode]['items_kategori'][$produk_jenis]['jml'] = 0;
                    }
                    $_SESSION[$cCode]['items_kategori'][$produk_jenis]['jml'] += $pro_jml;

                    $kategori_produk = $item["kategori_nama"];
                    if (!isset($_SESSION[$cCode]["main"]["jml_kategori_$kategori_produk"])) {
                        $_SESSION[$cCode]["main"]["jml_kategori_$kategori_produk"] = 0;
                    }
                    $_SESSION[$cCode]["main"]["jml_kategori_$kategori_produk"] += $pro_jml;
                }
                // ----------------------------------------end---------------
            }
            if (isset($_SESSION[$cCode]['items2'][$id])) {
                $_SESSION[$cCode]['items2'][$id] = null;
                unset($_SESSION[$cCode]['items2'][$id]);
            }
            if (isset($_SESSION[$cCode]['tableIn_detail_values'][$id])) {
                $_SESSION[$cCode]['tableIn_detail_values'][$id] = null;
                unset($_SESSION[$cCode]['tableIn_detail_values'][$id]);
            }

            //region cleansing items6 produk paket yang berelasi degnan produk yang diremove
            if (isset($_SESSION[$cCode]["items6"][$id])) {
                unset($_SESSION[$cCode]["items6"][$id]);

            }
            if (isset($_SESSION[$cCode]["items7"][$id])) {
                unset($_SESSION[$cCode]["items7"][$id]);

            }
            if (isset($_SESSION[$cCode]["items_komposisi"][$id])) {
                unset($_SESSION[$cCode]["items_komposisi"][$id]);

            }
            else {
                cekBiru("gak adaaa");
            }
            //endregion
        }
        else {
            if (isset($_SESSION[$cCode]['items'])) {
                foreach ($_SESSION[$cCode]['items'] as $id => $item) {

                    $_SESSION[$cCode]['items'][$id] = null;
                    unset($_SESSION[$cCode]['items'][$id]);
                    $_SESSION[$cCode]['items'][$id] = null;
                    unset($_SESSION[$cCode]['items'][$id]);


                    //region cleansing items6 produk paket yang berelasi degnan produk yang diremove
                    if (isset($_SESSION[$cCode]["items6"][$id])) {
                        unset($_SESSION[$cCode]["items6"][$id]);

                    }
                    if (isset($_SESSION[$cCode]["items7"][$id])) {
                        unset($_SESSION[$cCode]["items7"][$id]);

                    }
                    if (isset($_SESSION[$cCode]["items_komposisi"][$id])) {
                        unset($_SESSION[$cCode]["items_komposisi"][$id]);

                    }
                    else {
                        cekBiru("gak adaaa");
                    }
                    //endregion
                }
                //            $_SESSION[$cCode]['out_detail'][$id] = null;
                //            unset($_SESSION[$cCode]['out_detail'][$id]);
                //            $_SESSION[$cCode]['out_detail2'][$id] = null;
                //            unset($_SESSION[$cCode]['out_detail2'][$id]);
                /* -------------------------------------------------------
                 * kategori item untuk diskon unit/non-unit
                 * -------------------------------------------------------*/
                unset($_SESSION[$cCode]['items_kategori']);
                foreach ($_SESSION[$cCode]['items'] as $item) {
                    $kategori_produk = $item["kategori_nama"];
                    $_SESSION[$cCode]["main"]["jml_kategori_$kategori_produk"] = 0;
                }
                foreach ($_SESSION[$cCode]['items'] as $item) {
                    $pro_jml = $item['jml'];
                    $produk_jenis = $item['kategori_nama'];

                    if (!isset($_SESSION[$cCode]['items_kategori'][$produk_jenis]['jml'])) {
                        $_SESSION[$cCode]['items_kategori'][$produk_jenis]['jml'] = 0;
                    }
                    $_SESSION[$cCode]['items_kategori'][$produk_jenis]['jml'] += $pro_jml;

                    $kategori_produk = $item["kategori_nama"];
                    if (!isset($_SESSION[$cCode]["main"]["jml_kategori_$kategori_produk"])) {
                        $_SESSION[$cCode]["main"]["jml_kategori_$kategori_produk"] = 0;
                    }
                    $_SESSION[$cCode]["main"]["jml_kategori_$kategori_produk"] += $pro_jml;
                }
                // ----------------------------------------end---------------
            }
        }
        //        matiHere();


        $f_selector = "";
        if (isset($_GET['selector'])) {
            $f_selector = "selector&";
        }


        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $configUiJenis = $this->configUi[$this->jenisTr];
        $configCoreJenis = $this->configCore[$this->jenisTr];

        $vg->setConfigUiJenis($configUiJenis);
        $vg->setConfigCoreJenis($configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setppnFactor($ppnFactor);
        if (isset($_GET['mb'])) {
            echo "<script>";
            echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?" . $f_selector . "selID=$id';";
            echo "top.load_shoppingcart();";
            echo "</script>";
        }
        else {
            $initMasterValues = array(
                "olehID" => my_id(),
                "olehName" => my_name(),
                "placeID" => my_cabang_id(),
                "placeName" => my_cabang_nama(),
                "divID" => my_div_id(),
                "divName" => my_div_nama(),
                "cabangID" => my_cabang_id(),
                "cabangName" => my_cabang_nama(),
                "gudangID" => my_gudang_id(),
                "gudangName" => my_gudang_nama(),
                "jenis_usaha" => my_jenis_usaha(),
                "tokoID" => my_toko_id(),
                "tokoNama" => my_toko_nama(),
                "jenisTr" => $this->jenisTr,
                "jenisTrMaster" => $this->jenisTr,
                "jenisTrTop" => $configUiJenis['steps'][1]['target'],
                "jenisTrName" => $configUiJenis['steps'][1]['label'],
                "stepNumber" => $stepNum,
                "stepCode" => $configUiJenis['steps'][$stepNum]['target'],
                "dtime" => dtimeNow(),
                "fulldate" => dtimeNow("Y-m-d"),
                // "jenis_pajak"=>$this->session->login['jenis_usaha'],
            );
            // $vg->buildValue($this->jenisTr, $id, $initMasterValues, $configUiJenis);
            $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

            echo "<script>";
            echo "  if(top.document.getElementById('shopping_cart')){";
            echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "  }";
            echo "</script>";

            // echo "<script>";
            // echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?".$f_selector."selID=$id';";
            // echo "</script>";
        }

        //        echo "<script>";
        //        echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=$id';";
        //        // echo "top.getData('".base_url()."_shoppingCart/viewCart/".$this->jenisTr."?ohYes=ohNo','shopping_cart')";
        //        echo "</script>";
    }

    public function updateValues()
    {
        echo "---------------------------your input params needed------------------------------";
        arrprint($_POST);
        $cCode = "_TR_" . $this->jenisTr;
        //        $rawParam = $_POST['param'];
        //        arrPrint($rawParam);
        //        arrPrint($cCode);
        die("updating.............................. (will be available sooner or later)");
        //        $rawParam = $_GET['param'];
        //        $param = unserialize(base64_decode($rawParam));
        //        if (is_array($param) && sizeof($param) > 0) {
        //
        //        }
    }

    public function selectNoQty()
    {
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $id = $_GET['id'];
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;
        $stepNum = $this->uri->segment(5) > 0 ? $this->uri->segment(5) : 1;

        $cCode = $this->cCode;

        $selectorModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorSrcModel'];

        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        $priceSrcConfig = $this->config->item('hePrices') != null ? $this->config->item('hePrices') : array();
        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $connectedDiscountConfig = isset($this->configUi[$this->jenisTr]['connectedDiscount']) ? $this->configUi[$this->jenisTr]['connectedDiscount'] : array();
        $priceFilter = isset($this->configUi[$this->jenisTr]['selectedPrice']['mdlFilter']) ? $this->configUi[$this->jenisTr]['selectedPrice']['mdlFilter'] : array();
        $resetFilter = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $validateMeasurement = isset($this->configUi[$this->jenisTr]['validateMeasurement'][1]) ? $this->configUi[$this->jenisTr]['validateMeasurement'][1] : array();
        $ppnFactor = isset($_SESSION[$cCode]["main"]["ppnFactor"]) ? $_SESSION[$cCode]["main"]["ppnFactor"] : matiHEre("undefine ppn factor, please logout and login again");

        $tmpB = $b->lookupByID($id)->result();

        if (sizeof($tmpB) > 0) {
            foreach ($tmpB as $row) {
                $rows = $row;
                $valValidate_items = array();
                if (sizeof($validateMeasurement) > 0) {
                    $iValidate = 0;
                    foreach ($validateMeasurement as $keyVal => $validateKol) {
                        $valValidate = $row->$keyVal;
                        if ($valValidate == 0) {
                            $msg = "<br><red class='text-red'>" . htmlspecialchars($row->kode) . " " . htmlspecialchars($row->nama) . "</red><hr><br><red class='text-red'>$validateKol = $valValidate </red><br>silahkan hubungi bagian entry data untuk melengkapi data produk";
                            $alerts = array(
                                "type" => "warning",
                                "title" => strtoupper("Data ukuran produk belum lengkap "),
                                "html" => $msg,
                            );
                            echo swalAlert($alerts);
                            die($msg);
                        }
                    }

                }

                if (sizeof($valValidate_items) > 0) {
                    $msg = "Data pendukung produk belum lengkap<br><red class='text-red'>" . htmlspecialchars($row->kode) . " " . htmlspecialchars($row->nama) . "</red><hr>$jml_now $satuan stock available";
                    $alerts = array(
                        "type" => "warning",
                        "title" => strtoupper($kode),
                        "html" => $msg,
                    );
                    echo swalAlert($alerts);
                    die($msg);
                }
                $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                $tmpJml = 1;
                if (isset($lockerConfig['enabled']) && $lockerConfig['enabled'] == true) {
                    $mdlName = $lockerConfig['mdlName'];

                    cekMerah("masuk locker config $mdlName");

                    $this->load->model("Mdls/" . $mdlName);
                    $c = new $mdlName();
                    $c->addFilter("produk_id='$id'");
                    //                    $c->addFilter("id='$id'");//==id locker
                    $c->addFilter("state='active'");
                    // $c->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                    // $c->addFilter("gudang_id=" . $this->session->login['gudang_id']);
                    $c->addFilter("toko_id=" . my_toko_id());


                    $tmpC = $c->lookupAll($id)->result();
                    cekHere($this->db->last_query());

                    //                    $persediaan = sizeof($tmpC) > 0 ? $tmpC[0]->persediaan : "0";
                    if (sizeof($tmpC) > 0) {
                        // arrPrint($tmpC);
                        // arrPrint($row);
                        $kode = $row->kode;
                        foreach ($tmpC as $row) {
                            $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                            $nama = $row->nama;

                            $jml_now = $row->jumlah;
                            if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                                $jml_sudah_diambil = 0;
                                $jml_diperlukan = 1;
                                $jml_nambah = 1;
                            }
                            else {
                                if (isset($_GET['newQty'])) {
                                    $jml_sudah_diambil = $_SESSION[$cCode]['items'][$id]['jml'];
                                    $jml_diperlukan = $_GET['newQty'];
                                    $jml_nambah = $jml_diperlukan - $jml_sudah_diambil;
                                }
                                else {
                                    $jml_sudah_diambil = $_SESSION[$cCode]['items'][$id]['jml'];
                                    $jml_diperlukan = $jml_sudah_diambil + $jml;
                                    $jml_nambah = $jml;
                                }
                            }
                            //  region validasi stok
                            if ($jml_nambah > $jml_now) {
                                // echo "<script>top.alert('stok $nama tidak cukup. (perlu $jml_diperlukan, nambah $jml_nambah stok $jml_now)')";
                                // echo "</script>";
                                $msg = "Insufficient stock of:<br><red class='text-red'>$kode $nama</red><hr>$jml_now $satuan stock available";
                                $alerts = array(
                                    "type" => "warning",
                                    "title" => strtoupper($kode),
                                    "html" => $msg,
                                );
                                echo swalAlert($alerts);
                                die($msg);

                            }
                            //  endregion validasi stok


                            $this->db->trans_start();

                            //  region update locker active
                            $where = array(
                                "id" => $row->id,
                            );
                            $data_active = array(
                                "jumlah" => $jml_now - $jml_nambah,
                                "state" => "active",
                            );
                            $c->updateData($where, $data_active);
                            cekHere($this->db->last_query());
                            //  endregion update locker active


                            //  region locker hold
                            $array_hold_sebelumnya = $c->cekLoker($this->session->login['cabang_id'], $id, "hold", $this->session->login['id'], "0", $this->session->login['gudang_id']);
                            //                            arrPrint($array_hold_sebelumnya);
                            //                            mati_disini();
                            if (sizeof($array_hold_sebelumnya) > 0) {
                                $where = array(
                                    "id" => $array_hold_sebelumnya['id'],
                                );
                                $data_hold = array(
                                    "jumlah" => $array_hold_sebelumnya['jumlah'] + $jml_nambah,
                                );
                                $c->updateData($where, $data_hold);
                                cekHere($this->db->last_query());
                            }
                            else {
                                $data_hold = array(
                                    "jenis" => "produk",
                                    "cabang_id" => $this->session->login['cabang_id'],
                                    "produk_id" => $id,
                                    "nama" => $nama,
                                    "satuan" => $row->satuan,
                                    "state" => "hold",
                                    "jumlah" => $jml_nambah,
                                    "oleh_id" => $this->session->login['id'],
                                    "oleh_nama" => $this->session->login['nama'],
                                    "gudang_id" => $this->session->login['gudang_id'],
                                );
                                $c->addData($data_hold);
                                cekHere($this->db->last_query());
                            }
                            //  endregion locker hold

                            $this->db->trans_complete() or die("Gagal bro");

                            $tmpJml = $jml_diperlukan;

                        }
                    }
                    else {
                        mati_disini("tidak ditemukan item " . $row->nama . " di locker stock.");
                    }

                }

                if (sizeof($connectedDiscountConfig) > 0) {
                    if ($connectedDiscountConfig['enabled'] == 1) {
                        $mdlNameRelation = $connectedDiscountConfig['mdlNameRelation'];
                        $mdlNameSource = $connectedDiscountConfig['mdlNameSource'];

                        $this->load->model("Mdls/" . $mdlNameRelation);
                        $dr = new $mdlNameRelation();
                        $dr->addFilter("produk_id='$id'");
                        $dr->addFilter("status='1'");
                        $dr->addFilter("toko_id=" . my_toko_id());
                        $tmpDr = $dr->lookupAll($id)->result();
                        //                        cekMerah($this->db->last_query());
                        //                        arrPrint($tmpDr);
                        $produkQty = isset($_GET['jml']) ? $_GET['jml'] : $tmpJml;
                        foreach ($tmpDr as $drSpec) {
                            $this->load->model("Mdls/" . $mdlNameSource);
                            $sr = new $mdlNameSource();
                            $sr->addFilter("id='" . $drSpec->diskon_id . "'");
                            $sr->addFilter("status='1'");
                            $tmpSr = $sr->lookupAll($id)->result();
                            //                            cekBiru($this->db->last_query());
                            //                            arrPrint($tmpSr);
                            foreach ($tmpSr as $srSpec) {
                                arrPrint($srSpec);
                                if ($produkQty > $srSpec->max_qty) {
                                    $discountPersen = $srSpec->discount_persen;
                                    $discountQty = $srSpec->discount_qty;
                                }
                                elseif (($produkQty >= $srSpec->min_qty) && ($produkQty <= $srSpec->max_qty)) {
                                    $discountPersen = $srSpec->discount_persen;
                                    $discountQty = $srSpec->discount_qty;
                                }
                                else {
                                    $discountPersen = 0;
                                    $discountQty = 0;
                                }
                                $arrDiscount[$id] = array(
                                    "persen" => $discountPersen,
                                    "qty" => $discountQty,
                                );
                                cekMerah("pID: $id ::: persen: $discountPersen ::: qty: $discountQty");
                            }
                        }
                    }
                }

                $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");

                if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                    $tmp = array(
                        "handler" => $this->modul . "/" . $this->uri->segment(2),
                        "id" => $id,
                        "jml" => $tmpJml,
                        "harga" => 0,
                        "subtotal" => 0,
                        "satuan" => strlen($rows->satuan) > 0 ? $rows->satuan : "n/a",
                        "discount_persen" => isset($arrDiscount[$id]['persen']) ? $arrDiscount[$id]['persen'] : 0,
                        "discount_qty" => isset($arrDiscount[$id]['qty']) ? $arrDiscount[$id]['qty'] : 0,
                    );


                    if (sizeof($priceConfig) > 0) {
                        $mdlName = $priceConfig['model'];
                        $this->load->model("Mdls/" . $mdlName);
                        $h = new $mdlName();

                        if (isset($resetFilter['resetFilter']) && $resetFilter['resetFilter'] == true) {
                            $h->addFilter("produk_id='$id'");
                            // $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                            $h->addFilter("toko_id=" . my_toko_id());
                        }
                        else {
                            $h->addFilter("produk_id='$id'");
                            $h->addFilter("status='1'");
                            $h->addFilter("jenis_value in ('" . implode("','", $priceConfig['label']) . "')");
                            // $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                            $h->addFilter("toko_id=" . my_toko_id());
                        }

                        cekKuning("masukkk pak eko");
                        if (sizeof($priceFilter) > 0) {
                            foreach ($priceFilter as $f) {
                                $f_ex = explode("=", $f);
                                if (!isset($f_ex[1])) {
                                    $f_ey = explode(">", $f_ex[0]);
                                    if (substr($f_ey[1], 0, 1) == ".") {
                                        $h->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
                                    }
                                    else {
                                        if (isset($_SESSION[$cCode]['main'][$f_ey[1]])) {
                                            $h->addFilter($f_ey[0] . ">'" . $_SESSION[$cCode]['main'][$f_ey[1]] . "'");
                                        }
                                        else {
                                            $h->addFilter($f_ey[0] . ">0");
                                        }
                                    }
                                }
                                else {
                                    if (substr($f_ex[1], 0, 1) == ".") {
                                        $h->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
                                    }
                                    else {
                                        if (isset($_SESSION[$cCode]['main'][$f_ex[1]])) {
                                            $h->addFilter($f_ex[0] . "='" . $_SESSION[$cCode]['main'][$f_ex[1]] . "'");
                                        }
                                        else {
                                            $h->addFilter($f_ex[0] . "=''");
                                        }

                                    }
                                }
                            }
                        }


                        $tmpH = $h->lookupAll($id)->result();
                        //                        cekmerah($this->db->last_query());
                        //                        matiHere();
                        if (sizeof($tmpH) > 0) {
                            $rawPrices = array();
                            foreach ($tmpH as $hSpec) {
                                foreach ($priceConfig['key_label'] as $key => $val) {

                                    cekHitam($key);
                                    if ($resetFilter['resetFilter']) {
                                        cekBiru("sino$key ||" . $hSpec->$key);
                                        //                                        if ($key == $hSpec->h) {
                                        //                                            cekLime($hSpec->$key);
                                        $rawPrices[$key] = isset($hSpec->$key) ? $hSpec->$key : 0;
                                        //                                        }
                                    }
                                    else {
                                        cekBiru("sini");
                                        if ($key == $hSpec->jenis_value) {
                                            $rawPrices[$key] = isset($hSpec->nilai) ? $hSpec->nilai : 0;
                                        }
                                    }

                                }

                            }
                            //                            arrPrint($rawPrices);
                            $prices = normalizePrices("produk", $rawPrices);
                            if (sizeof($prices) > 0) {
                                foreach ($prices as $k => $v) {
                                    $tmp[$k] = $v;
                                }
                                $tmp['harga'] = isset($tmp[$priceConfig['mainSrc']]) ? $tmp[$priceConfig['mainSrc']] : 0;
                            }
                        }

                    }


                    foreach ($fieldSrcs as $key => $src) {
                        cekUngu(":: $key => $src ::");
                        //                        $tmp[$key] = makeValue($src, $_SESSION[$cCode]['items'][$id], $tmp, $tmpB[0]->$src);
                        $tmp[$key] = makeValue($src, $tmp, $tmp, isset($rows->$src) ? $rows->$src : 0);
                    }

                    if (sizeof($itemNumLabels) > 0) {

                        foreach ($itemNumLabels as $key => $label) {
                            if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                $newValue = $_GET[$key];
                                $tmp[$key] = $newValue;
                                //                    $_SESSION[$cCode]['items'][$id][$key] = $newValue;
                                $tmp[$key] = $newValue;
                            }
                        }
                    }

                    //===perhitungan subtotal
                    //                    $this->load->library("FieldCalculator");
                    //                    $cal = new FieldCalculator();


                    if ($subAmountConfig != null) {
                        //                        $tmp['subtotal'] = makeValue($subAmountConfig, $tmp, $_SESSION[$cCode]['items'][$id], 0);
                        $tmp['subtotal'] = makeValue($subAmountConfig, $tmp, $tmp, 0);
                    }
                    else {
                        $tmp['subtotal'] = 0;
                    }
                    //                    arrprint($tmp);die();
                    $_SESSION[$cCode]['items'][$id] = $tmp;

                }
                else {

                    if (isset($_GET['newQty'])) {
                        //                        $_SESSION[$cCode]['items'][$id]['jml'] = $_GET['newQty'];
                        $_SESSION[$cCode]['items'][$id]['jml'] = $jml;
                        $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * ($_SESSION[$cCode]['items'][$id]['harga'] + $_SESSION[$cCode]['items'][$id]['ppn']));
                    }
                    else {
                        $_SESSION[$cCode]['items'][$id]['jml'] = $jml;
                        $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * ($_SESSION[$cCode]['items'][$id]['harga'] + $_SESSION[$cCode]['items'][$id]['ppn']));
                    }


                    if (isset($_GET['qty_opname'])) {
                        $_SESSION[$cCode]['items'][$id]['qty_opname'] = $_GET['qty_opname'];
                        $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * ($_SESSION[$cCode]['items'][$id]['harga'] + $_SESSION[$cCode]['items'][$id]['ppn']));

                        $selisih = $_GET['qty_opname'] - $_SESSION[$cCode]['items'][$id]['stok'];
                        if ($selisih > 0) {
                            $_SESSION[$cCode]['items'][$id]['qty_debet'] = $selisih;
                            $_SESSION[$cCode]['items'][$id]['qty_kredit'] = 0;
                            $_SESSION[$cCode]['items'][$id]['debet'] = $selisih * $_SESSION[$cCode]['items'][$id]['harga'];
                            $_SESSION[$cCode]['items'][$id]['kredit'] = 0;
                        }
                        elseif ($selisih < 0) {
                            $_SESSION[$cCode]['items'][$id]['qty_debet'] = 0;
                            $_SESSION[$cCode]['items'][$id]['qty_kredit'] = ($selisih * -1);
                            $_SESSION[$cCode]['items'][$id]['debet'] = 0;
                            $_SESSION[$cCode]['items'][$id]['kredit'] = ($selisih * -1) * $_SESSION[$cCode]['items'][$id]['harga'];
                        }
                        else {
                            $_SESSION[$cCode]['items'][$id]['qty_debet'] = 0;
                            $_SESSION[$cCode]['items'][$id]['qty_kredit'] = 0;
                            $_SESSION[$cCode]['items'][$id]['debet'] = 0;
                            $_SESSION[$cCode]['items'][$id]['kredit'] = 0;
                        }
                        $_SESSION[$cCode]['items'][$id]['qty_selisih'] = $selisih;
                    }


                    if (isset($arrDiscount[$id]) && sizeof($arrDiscount[$id]) > 0) {
                        foreach ($arrDiscount[$id] as $dKey => $dVal) {
                            if (!isset($_SESSION[$cCode]['items'][$id]['discount_' . $dKey])) {
                                $_SESSION[$cCode]['items'][$id]['discount_' . $dKey] = 0;
                            }
                            $_SESSION[$cCode]['items'][$id]['discount_' . $dKey] = $dVal;
                        }
                    }


                    if (sizeof($itemNumLabels) > 0) {

                        foreach ($itemNumLabels as $key => $label) {
                            if (isset($_GET[$key]) && strlen($_GET[$key]) > 0) {
                                $newValue = $_GET[$key];
                                $tmp[$key] = $newValue;
                                $_SESSION[$cCode]['items'][$id][$key] = $newValue;

                            }

                        }


                        if ($subAmountConfig != null) {
                            $tmp['subtotal'] = makeValue($subAmountConfig, $_SESSION[$cCode]['items'][$id], $_SESSION[$cCode]['items'][$id], 0);
                        }
                        else {
                            $tmp['subtotal'] = 0;
                        }
                        $_SESSION[$cCode]['items'][$id]['subtotal'] = $tmp['subtotal'];
                    }


                }
            }

        }
        else {
            cekMerah("tidak ada itemnya!");
            die();
        }

        //-----------------------------------------------------
        $dtime_now = dtimeNow();
        $dtime_now_ex = explode(" ", $dtime_now);
        $date_now = str_replace("-", "", $dtime_now_ex[0]);
        $time_now = str_replace(":", "", $dtime_now_ex[1]);
        $bookingNumber = "$date_now" . "$time_now";
        if (!isset($_SESSION[$cCode]["main"]["bookingNumber"]) || ($_SESSION[$cCode]["main"]["bookingNumber"] == null)) {
            $_SESSION[$cCode]["main"]["bookingNumber"] = $bookingNumber;
        }
        //-----------------------------------------------------

        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $configUiJenis = $this->configUi[$this->jenisTr];
        $configCoreJenis = $this->configCore[$this->jenisTr];

        $vg->setConfigUiJenis($configUiJenis);
        $vg->setConfigCoreJenis($configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setppnFactor($ppnFactor);
        $initMasterValues = array(
            "olehID" => my_id(),
            "olehName" => my_name(),
            "placeID" => my_cabang_id(),
            "placeName" => my_cabang_nama(),
            "divID" => my_div_id(),
            "divName" => my_div_nama(),
            "cabangID" => my_cabang_id(),
            "cabangName" => my_cabang_nama(),
            "gudangID" => my_gudang_id(),
            "gudangName" => my_gudang_nama(),
            "jenis_usaha" => my_jenis_usaha(),
            "tokoID" => my_toko_id(),
            "tokoNama" => my_toko_nama(),
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $configUiJenis['steps'][1]['target'],
            "jenisTrName" => $configUiJenis['steps'][1]['label'],
            "stepNumber" => $stepNum,
            "stepCode" => $configUiJenis['steps'][$stepNum]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        // $vg->buildValue($this->jenisTr, $id, $initMasterValues, $configUiJenis);
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        /* --------------------------------------------------
         * ngereload shoping cart dlm modul
         * --------------------------------------------------*/
        echo "<script>";
        echo "  if(top.document.getElementById('shopping_cart')){";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "  }";
        echo "</script>";
        // echo "<script>";
        // echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=$id';";
        // echo "</script>";
    }

    //tambahan selector satuan
    public function selectFactorSatuan()
    {
        $cCode = $this->cCode;
        arrPrint($_GET);
        // arrPrint($this->uri->segment_array());
        $this->load->model("Mdls/MdlProdukSatuanRelasi");
        $p = new MdlProdukSatuanRelasi();
        $id = isset($_GET['id']) ? $_GET['id'] : "0";
        $stepNum = $this->uri->segment(5) > 0 ? $this->uri->segment(5) : 1;
        $pID = $produk_id = $_GET['pid'];
        $kID = $_GET['key'];
        $kValue = $_GET['value'];
        $qparams = blobDecode($_GET["qparams"]);
        // arrPrint($qparams);
        // arrPrint($_GET);
        // matiHere();
        // arrprint($_SESSION[$cCode]["items3"]);

        $p->addFilter("toko_id='" . my_toko_id() . "'");
        $p->addFilter("produk_id='$pID'");
        $p->addFilter("$kID='$kValue'");
        $p->setTokoId(my_toko_id());
        $temp = $p->lookUpRelasiSatuan($pID);

        $toUpDate = array();
        foreach ($temp[$pID] as $temp_0) {
            if ($temp_0[$kID] == $kValue) {
                foreach ($qparams as $src => $srcTarget) {
                    $toUpDate[$srcTarget] = $temp_0[$src];
                }
            }
        }

        foreach ($toUpDate as $key => $values) {
            $_SESSION[$cCode]["items"][$pID][$key] = $values;
        }

        $selectorModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = isset($_SESSION[$cCode]['main']['pihakMdlNameSrc']) ? $_SESSION[$cCode]['main']['pihakMdlNameSrc'] : $this->configUi[$this->jenisTr]['selectorSrcModel'];

        // detektor tanda kurawal {}
        if (substr($selectorModel, 0, 1) == "{") {
            $selectorModel = trim($selectorModel, "{");
            $selectorModel = trim($selectorModel, "}");
            $selectorModel = str_replace($selectorModel, $_SESSION[$cCode]['main'][$selectorModel], $selectorModel);
        }
        else {
            cekkuning("TIDAK mengandung kurawal @" . __LINE__ . __CLASS__);
        }
        if (substr($selectorSrcModel, 0, 1) == "{") {
            $selectorSrcModel = trim($selectorSrcModel, "{");
            $selectorSrcModel = trim($selectorSrcModel, "}");
            $selectorSrcModel = str_replace($selectorSrcModel, $_SESSION[$cCode]['main'][$selectorSrcModel], $selectorSrcModel);
        }
        else {
            cekkuning("TIDAK mengandung kurawal @" . __LINE__ . " " . __METHOD__);
        }

        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();

        $tmpB = $b->lookupByID($pID)->result();
        showLast_query("lime");

        if (sizeof($tmpB) > 0) {
            foreach ($tmpB as $row) {

                /* ----------------------------------------------------------
                 * diskon-diskonan-grosir
                 * ----------------------------------------------------------*/
                $tmp = $_SESSION[$cCode]['items'][$pID];
                $pro_premi = $row->premi_jual;
                $pro_harga = $tmp['harga_list'] + (($pro_premi / 100) * $tmp['harga_list']);

                cekBiru("$pro_premi || $pro_harga");

                $pro_jml = $tmp['jml'];
                $calc_hasil = $this->selectorDiskon($pID, $pro_harga, $pro_jml);

                arrPrintKuning($calc_hasil);

                $tmp['discPersen'] = $calc_hasil['persen'];
                $tmp['harga_disc'] = $calc_hasil['harga_af'] * $tmp['satuan_factor_qty'];
                $tmp['lastNett'] = $calc_hasil['harga_af'];
                $tmp['harga'] = $calc_hasil['harga_af'];
                $tmp['harga_jual'] = $calc_hasil['harga_be'] * $tmp['satuan_factor_qty'];
                $tmp['discNilai'] = $calc_hasil['nilai'];
                $tmp['id'] = $pID;

                //                $tmp['bayar'] = 0; //reset bayar

                $tmp['subtotal'] = $calc_hasil['harga_af'] * $pro_jml;

                // arrPrintPink($_SESSION[$cCode]['items'], __LINE__ . " " . __METHOD__);

                //                cekMerah("hasil update + diskon sbb:");
                //                arrPrintWebs($tmp);


                $_SESSION[$cCode]['items'][$produk_id] = $tmp;
                // matiHere(__METHOD__ . __LINE__);

            }
        }

        //-----------------------------------------------------
        $dtime_now = dtimeNow();
        $dtime_now_ex = explode(" ", $dtime_now);
        $date_now = str_replace("-", "", $dtime_now_ex[0]);
        $time_now = str_replace(":", "", $dtime_now_ex[1]);
        $bookingNumber = "$date_now" . "$time_now";
        if (!isset($_SESSION[$cCode]["main"]["bookingNumber"]) || ($_SESSION[$cCode]["main"]["bookingNumber"] == null)) {
            $_SESSION[$cCode]["main"]["bookingNumber"] = $bookingNumber;
        }
        //-----------------------------------------------------

        $this->load->library("ValueGate");
        $vg = new ValueGate();
        // $configUiJenis = $this->configUi[$this->jenisTr];
        // $configCoreJenis = $this->configCore[$this->jenisTr];
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        if (isset($_GET['spc'])) {
            // matiHEre(__LINE__." file ".__FILE__);
            $initMasterValues = array(
                "olehID" => my_id(),
                "olehName" => my_name(),
                "placeID" => my_cabang_id(),
                "placeName" => my_cabang_nama(),
                "divID" => my_div_id(),
                "divName" => my_div_nama(),
                "cabangID" => my_cabang_id(),
                "cabangName" => my_cabang_nama(),
                "gudangID" => my_gudang_id(),
                "gudangName" => my_gudang_nama(),
                "jenis_usaha" => my_jenis_usaha(),
                "tokoID" => my_toko_id(),
                "tokoNama" => my_toko_nama(),
                "jenisTr" => $this->jenisTr,
                "jenisTrMaster" => $this->jenisTr,
                "jenisTrTop" => $this->configUiJenis['steps'][$stepNum]['target'],
                "jenisTrName" => $this->configUiJenis['steps'][$stepNum]['label'],
                "stepNumber" => $stepNum,
                "stepCode" => $this->configUiJenis['steps'][$stepNum]['target'],
                "dtime" => dtimeNow(),
                "fulldate" => dtimeNow("Y-m-d"),
                // "jenis_pajak"=>$this->session->login['jenis_usaha'],
            );
            // $vg->buildValue($this->jenisTr, $id, $initMasterValues, $configUiJenis);
            $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);


            // matiHere(__METHOD__ . __LINE__);
            /* --------------------------------------------------
             * ngereload shoping cart dlm modul
             * --------------------------------------------------*/
            echo "<script>";
            // echo "  if(top.document.getElementById('shopping_cart')){";
            // echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            // echo "  }";

            echo "if(top.document.getElementById('shopping_cart')){
                    localStorage.setItem('loadShoppingCart', 10);
                    top.$('#btn_kalkulasi').removeClass('hidden');
                  }";
            echo "</script>";

            // echo "<script>";
            // echo "top.document.getElementById('result').src='" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?" . $f_selector . "selID=$id';";
            // echo "top.load_shoppingcart();";
            // echo "</script>";
        }
        else {
            $initMasterValues = array(
                "olehID" => my_id(),
                "olehName" => my_name(),
                "placeID" => my_cabang_id(),
                "placeName" => my_cabang_nama(),
                "divID" => my_div_id(),
                "divName" => my_div_nama(),
                "cabangID" => my_cabang_id(),
                "cabangName" => my_cabang_nama(),
                "gudangID" => my_gudang_id(),
                "gudangName" => my_gudang_nama(),
                "jenis_usaha" => my_jenis_usaha(),
                "tokoID" => my_toko_id(),
                "tokoNama" => my_toko_nama(),
                "jenisTr" => $this->jenisTr,
                "jenisTrMaster" => $this->jenisTr,
                "jenisTrTop" => $this->configUiJenis['steps'][$stepNum]['target'],
                "jenisTrName" => $this->configUiJenis['steps'][$stepNum]['label'],
                "stepNumber" => $stepNum,
                "stepCode" => $this->configUiJenis['steps'][$stepNum]['target'],
                "dtime" => dtimeNow(),
                "fulldate" => dtimeNow("Y-m-d"),
                // "jenis_pajak"=>$this->session->login['jenis_usaha'],
            );
            // $vg->buildValue($this->jenisTr, $id, $initMasterValues, $configUiJenis);
            $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);
            // arrPrintKuning($_SESSION[$cCode]['items']);

            /* --------------------------------------------------
             * ngereload shoping cart dlm modul
             * --------------------------------------------------*/
            echo "<script>";
            echo "  if(top.document.getElementById('shopping_cart')){";
            echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "  }";
            echo "</script>";
        }
    }

    private function selectorDiskon($produk_id, $produk_harga, $produk_jml, $produk_speks = array(), $ses_mains = array())
    {
        // arrPrint($produk_speks);
        // cekHitam("produk_id $produk_id || tokid " . my_toko_id());
        $this->load->library("Diskon");
        $dp = new Diskon();
        $dp->setTokoId(my_toko_id());
        $src_diskon = $dp->CallProdukDiskon($produk_id);
        // arrPrintHijau($src_diskon);
        // diskon
        $pro_harga = $produk_harga * 1;
        $pro_jml = $produk_jml;
        // cekPink("$pro_harga || $pro_jml");
        /*---menentukan diskon pokok dari produk atau grosir----*/
        $pihak_kategori = $ses_mains['kategoriNama'];

        $d_pokok = isset($src_diskon['produk']) ? $src_diskon['produk'] : 0;

        if ($pihak_kategori == "distributor") {
            $d_pokok = 0;
        }

        $jml_spek_grosir = sizeof($src_diskon['grosir']);
        if ($jml_spek_grosir > 0) {
            $gro_count = 0;
            foreach ($src_diskon['grosir'] as $item) {
                $gro_count++;
                $gro_minim = $item['minim'];
                $gro_maxim = $gro_count == $jml_spek_grosir ? INF : $item['maxim'];
                $gro_persen = $item['persen'];
                // cekPink2("$pro_jml >= $gro_minim) && ($pro_jml <= $gro_maxim)");

                if (($pro_jml >= $gro_minim) && ($pro_jml <= $gro_maxim)) {
                    $d_pokok = $gro_persen;
                    // cekBiru("---- $d_pokok");
                    break;
                }
            }
        }
        else {
            // $d_pokok = isset($src_diskon['produk']) ? $src_diskon['produk'] : 0;
        }

        $diskon_pokok["produk"] = $d_pokok;
        $diskon_event = array();

        $calc_hasil_grosir = $dp->calcDiskon($pro_harga, $diskon_pokok, $diskon_event, "diskon");

        if (count($produk_speks) > 0) {
            $pro_diskon = $produk_speks->diskon_persen;
            // $pro_premi = $produk_speks->premi_jual;
            $diskon_pokok["produk"] = $pro_diskon;

            $calc_hasil_simple = $dp->calcDiskon($pro_harga, $diskon_pokok, $diskon_event, "diskon");
        }

        $calc_hasil = array();
        $calc_hasil["grosir"] = $calc_hasil_grosir;
        $calc_hasil["simple"] = $calc_hasil_simple;

        return $calc_hasil;
    }

    public function selectReturn()
    {
        // arrPrint($this->uri->segment_array());
        // arrPrint($_GET);
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $id = $produk_id = $_GET['id'];
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;
        $stepNum = $this->uri->segment(5) > 0 ? $this->uri->segment(5) : 1;
        $qty_unit = isset($_GET['qty_unit']) ? $_GET['qty_unit'] : 1;
        $cCode = $this->cCode;
        $pihakID = $_SESSION[$cCode]["main"]["pihakID"];
        $selectorModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = isset($_SESSION[$cCode]['main']['pihakMdlNameSrc']) ? $_SESSION[$cCode]['main']['pihakMdlNameSrc'] : $this->configUi[$this->jenisTr]['selectorSrcModel'];

        // detektor tanda kurawal {}
        if (substr($selectorModel, 0, 1) == "{") {
            $selectorModel = trim($selectorModel, "{");
            $selectorModel = trim($selectorModel, "}");
            $selectorModel = str_replace($selectorModel, $_SESSION[$cCode]['main'][$selectorModel], $selectorModel);
        }
        else {
            cekkuning("TIDAK mengandung kurawal @" . __LINE__ . __CLASS__);
        }
        if (substr($selectorSrcModel, 0, 1) == "{") {
            $selectorSrcModel = trim($selectorSrcModel, "{");
            $selectorSrcModel = trim($selectorSrcModel, "}");
            $selectorSrcModel = str_replace($selectorSrcModel, $_SESSION[$cCode]['main'][$selectorSrcModel], $selectorSrcModel);
        }
        else {
            cekkuning("TIDAK mengandung kurawal @" . __LINE__ . " " . __METHOD__);
        }


        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        $priceSrcConfig = $this->config->item('hePrices') != null ? $this->config->item('hePrices') : array();
        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();

        $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $priceMainConfig = isset($this->configUi[$this->jenisTr]['selectedMainPrice']) ? $this->configUi[$this->jenisTr]['selectedMainPrice'] : array();

        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $connectedDiscountConfig = isset($this->configUi[$this->jenisTr]['connectedDiscount']) ? $this->configUi[$this->jenisTr]['connectedDiscount'] : array();
        $priceFilter = isset($this->configUi[$this->jenisTr]['selectedPrice']['mdlFilter']) ? $this->configUi[$this->jenisTr]['selectedPrice']['mdlFilter'] : array();
        $resetFilter = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $validateMeasurement = isset($this->configUi[$this->jenisTr]['validateMeasurement'][1]) ? $this->configUi[$this->jenisTr]['validateMeasurement'][1] : array();
        $shopingCartItemOptionFields = isset($this->configUi[$this->jenisTr]['shopingCartItemOptionFields'][1]) ? $this->configUi[$this->jenisTr]['shopingCartItemOptionFields'][1] : array();
        $valueGateConfig = isset($this->configValues[$this->jenisTr]['detailInjectedValues']) ? $this->configValues[$this->jenisTr]['detailInjectedValues'] : array();
        $selectorProcessorParam = isset($this->configUi[$this->jenisTr]['selectorProcessorParam']) ? $this->configUi[$this->jenisTr]['selectorProcessorParam'] : array();
        $tmpB = $b->lookupByID($id)->result();
        // showLast_query("lime");
        // matiHere(__LINE__ . " " .__METHOD__);

        if (sizeof($tmpB) > 0) {
            foreach ($tmpB as $row) {
                $rows = $row;
                $valValidate_items = array();

                if (sizeof($validateMeasurement) > 0) {
                    $iValidate = 0;
                    foreach ($validateMeasurement as $keyVal => $validateKol) {
                        $valValidate = $row->$keyVal;
                        if ($valValidate == 0) {
                            $msg = "<br><red class='text-red'>" . htmlspecialchars($row->kode) . " " . htmlspecialchars($row->nama) . "</red><hr><br><red class='text-red'>$validateKol = $valValidate </red><br>silahkan hubungi bagian entry data untuk melengkapi data produk";
                            $alerts = array(
                                "type" => "warning",
                                "title" => strtoupper("Data ukuran produk belum lengkap "),
                                "html" => $msg,
                            );
                            echo swalAlert($alerts);
                            die($msg);
                        }
                    }

                }

                if (sizeof($valValidate_items) > 0) {
                    //                    arrPrint($valValidate_items);
                    $msg = "Data pendukung produk belum lengkap<br><red class='text-red'>" . htmlspecialchars($row->kode) . " " . htmlspecialchars($row->nama) . "</red><hr>$jml_now $satuan stock available";
                    $alerts = array(
                        "type" => "warning",
                        "title" => strtoupper($kode),
                        "html" => $msg,
                    );
                    echo swalAlert($alerts);
                    die($msg);
                }

                $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";

                $tmpJml = 1;


                $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");

                /* ----------------------------------------------------------------
                 * inisisasi cCode Items
                 * untuk harga ambil dari stock_locker_penjualan_cache degnan index custtomerid dan produk id
                 * ----------------------------------------------------------------*/
                if (!isset($_SESSION[$cCode]['items']) || !array_key_exists($id, $_SESSION[$cCode]['items'])) {
                    $tmp = array(
                        "handler" => $this->modul . "/" . $this->uri->segment(2),
                        "id" => $id,
                        "jml" => $tmpJml,
                        "qty_unit" => $qty_unit,
                        "harga" => 0,
                        "subtotal" => 0,
                        "satuan" => strlen($rows->satuan) > 0 ? $rows->satuan : "n/a",
                        "discount_persen" => isset($arrDiscount[$id]['persen']) ? $arrDiscount[$id]['persen'] : 0,
                        "discount_qty" => isset($arrDiscount[$id]['qty']) ? $arrDiscount[$id]['qty'] : 0,
                    );

                    if (sizeof($priceMainConfig) > 0) {
                        if (isset($priceMainConfig[$_SESSION[$cCode]['main']['pihakMainName']])) {
                            $priceConfig = $priceMainConfig[$_SESSION[$cCode]['main']['pihakMainName']];
                            cekUngu("masuk disini...");
                        }
                    }
                    cekBiru(__LINE__);
                    /*
                     * pengganti price config
                     */
                    $this->load->model("Mdls/MdlLockerStockPenjualanCache");
                    $pr = new MdlLockerStockPenjualanCache();
                    $pr->addFilter("extern_id='$id'");
                    $pr->addFilter("extern2_id='$pihakID'");
                    $tmpLocker = $pr->lookUpAll()->result();
                    if (sizeof($tmpLocker) > 0) {
                        foreach ($tmpLocker as $tmpLocker_0) {
                            foreach ($selectorProcessorParam as $key_tbl => $key_target) {
                                $tmp[$key_target] = $tmpLocker_0->$key_tbl;
                            }
                        }
                    }

                    //                    arrPrint($tmpLocker);
                    //                    arrPrint($selectorProcessorParam);


                    // if (sizeof($priceConfig) > 0) {
                    //     $mdlName = $priceConfig['model'];
                    //     $this->load->model("Mdls/" . $mdlName);
                    //     $h = new $mdlName();
                    //     if (isset($resetFilter['resetFilter']) && $resetFilter['resetFilter'] == true) {
                    //         $h->addFilter("produk_id='$id'");
                    //         // $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                    //         // $h->addFilter("toko_id=" . my_toko_id());
                    //     }
                    //     else {
                    //         $h->addFilter("produk_id='$id'");
                    //         $h->addFilter("status='1'");
                    //         $h->addFilter("jenis_value in ('" . implode("','", $priceConfig['label']) . "')");
                    //         // $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                    //         // $h->addFilter("toko_id=" . my_toko_id());
                    //     }
                    //
                    //     if (sizeof($priceFilter) > 0) {
                    //         foreach ($priceFilter as $f) {
                    //             $f_ex = explode("=", $f);
                    //             if (!isset($f_ex[1])) {
                    //                 $f_ey = explode(">", $f_ex[0]);
                    //                 if (substr($f_ey[1], 0, 1) == ".") {
                    //                     $h->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
                    //                 }
                    //                 else {
                    //                     if (isset($_SESSION[$cCode]['main'][$f_ey[1]])) {
                    //                         $h->addFilter($f_ey[0] . ">'" . $_SESSION[$cCode]['main'][$f_ey[1]] . "'");
                    //                     }
                    //                     else {
                    //                         $h->addFilter($f_ey[0] . ">0");
                    //                     }
                    //                 }
                    //             }
                    //             else {
                    //                 if (substr($f_ex[1], 0, 1) == ".") {
                    //                     $h->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
                    //                 }
                    //                 else {
                    //                     if (isset($_SESSION[$cCode]['main'][$f_ex[1]])) {
                    //                         $h->addFilter($f_ex[0] . "='" . $_SESSION[$cCode]['main'][$f_ex[1]] . "'");
                    //                     }
                    //                     else {
                    //                         $h->addFilter($f_ex[0] . "=''");
                    //                     }
                    //
                    //                 }
                    //             }
                    //         }
                    //     }
                    //     $tmpH = $h->lookupAll($id)->result();
                    //     // showLast_query("kuning");
                    //
                    //     if (sizeof($tmpH) > 0) {
                    //         $rawPrices = array();
                    //         foreach ($tmpH as $hSpec) {
                    //             foreach ($priceConfig['key_label'] as $key => $val) {
                    //                 //                                    cekHitam($key);
                    //                 if (isset($resetFilter['resetFilter'])) {
                    //                     cekBiru("sino$key ||" . $hSpec->$key);
                    //                     //                                        if ($key == $hSpec->h) {
                    //                     //                                            cekLime($hSpec->$key);
                    //                     $rawPrices[$key] = isset($hSpec->$key) ? $hSpec->$key : 0;
                    //                     //                                        }
                    //                 }
                    //                 else {
                    //                     cekBiru("sini " . __LINE__);
                    //                     if ($key == $hSpec->jenis_value) {
                    //                         $rawPrices[$key] = isset($hSpec->nilai) ? $hSpec->nilai : 0;
                    //                     }
                    //                 }
                    //
                    //             }
                    //
                    //         }
                    //         $prices = normalizePrices("produk", $rawPrices);
                    //         if (sizeof($prices) > 0) {
                    //             foreach ($prices as $k => $v) {
                    //                 $tmp[$k] = $v;
                    //             }
                    //             // $tmp['harga'] = isset($tmp[$priceConfig['mainSrc']]) ? $tmp[$priceConfig['mainSrc']] : 0;
                    //         }
                    //     }
                    //
                    // }
                    //------------------------------------------------------
                    // arrPrint( $fieldSrcs);
                    foreach ($fieldSrcs as $key => $src) {
                        if (is_array($src) && sizeof($src) > 0) {
                            foreach ($src as $srcSpec) {
                                if (isset($tmp[$srcSpec]) || isset($rows->$srcSpec)) {
                                    // cekBiru("ambil gerbang key -> $srcSpec");
                                    $tmp[$key] = makeValue($srcSpec, $tmp, $tmp, isset($rows->$srcSpec) ? $rows->$srcSpec : 0);
                                }
                            }
                        }
                        else {
                            $tmp[$key] = makeValue($src, $tmp, $tmp, isset($rows->$src) ? $rows->$src : 0);
                            //                            cekHere("hasilnya $key -> " . $tmp[$key]);
                        }
                    }

                    // matiHEre(__LINE__);

                    if (sizeof($itemNumLabels) > 0) {

                        foreach ($itemNumLabels as $key => $label) {
                            if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                $newValue = $_GET[$key];
                                $tmp[$key] = $newValue;
                                //                    $_SESSION[$cCode]['items'][$id][$key] = $newValue;
                                $tmp[$key] = $newValue;

                            }
                        }
                    }


                    if ($subAmountConfig != null) {
                        $tmp['subtotal'] = makeValue($subAmountConfig, $tmp, $tmp, 0);
                    }
                    else {
                        $tmp['subtotal'] = 0;
                    }

                    /*
                     * bagian option satuan
                     */
                    // arrPrint($shopingCartItemOptionFields);
                    //region sambungin ke relasi satuan
                    if (sizeof($shopingCartItemOptionFields) > 0) {
                        foreach ($shopingCartItemOptionFields as $keyIndex => $dataExtSatuan) {
                            $mdl = $dataExtSatuan["mdlName"];
                            // $field = $dataExtSatuan["selectField"];
                            $targetSes = $dataExtSatuan["targetSession"];
                            $methode = $dataExtSatuan["methode"];
                            $indexKey = $dataExtSatuan["keySrc"];
                            $this->load->model("Mdls/" . $mdl);
                            $mm = new $mdl();
                            $mm->setTokoId(my_toko_id());
                            $prevCon = $mm->$methode($id);
                            // arrprint($prevCon);
                            // matiHEre();
                            if (sizeof($prevCon) > 0) {
                                foreach ($prevCon[$id] as $prevCon_0) {
                                    foreach ($dataExtSatuan["usedFields"] as $src => $target) {
                                        if (!isset($_SESSION[$cCode][$targetSes][$id][$keyIndex][$prevCon_0[$indexKey]][$target])) {
                                            // cekMerah($prevCon_0[$indexKey]."  $target =>".$prevCon_0[$src]);
                                            $_SESSION[$cCode][$targetSes][$id][$keyIndex][$prevCon_0[$indexKey]][$target] = $prevCon_0[$src];
                                        }

                                    }
                                }
                            }
                        }
                    }
                    // matiHere();
                    $_SESSION[$cCode]['items'][$produk_id] = $tmp;
                    //endregion
                    // matiHEre(__LINE__);
                }
                else {
                    cekBiru("ada id $id tapi tidak ada cCode items @" . __LINE__);

                    //validasi stok
                    $stok_unit = $_SESSION[$cCode]['items'][$id]["stok"];
                    $newReq_qty = $_GET['newQty'] * $_SESSION[$cCode]['items'][$id]['satuan_factor_qty'];

                    if ($newReq_qty <= $stok_unit) {
                        //skip boleh lanjut
                    }
                    else {
                        $errMsg = "Ditolak karena qty retur " . htmlspecialchars($_SESSION[$cCode]["items"][$id]["nama"]) . " melebihi total pembelian  dari konsumen (" . htmlspecialchars($_SESSION[$cCode]["items"][$id]["customerName"]) . ") Silahkan periksa kembali pemilihan satuan, maupun qty yang di minta";

                        $msg = "<br><red class='text-red'>" . htmlspecialchars($row->kode) . " " . htmlspecialchars($row->nama) . "</red><hr><br><red class='text-red'>$validateKol = $valValidate </red><br>silahkan hubungi bagian entry data untuk melengkapi data produk";
                        $alerts = array(
                            "type" => "warning",
                            "title" => strtoupper("PERHATIAN"),
                            "html" => $errMsg,
                        );
                        echo swalAlert($alerts);
                        die($msg);
                    }

                    if (isset($_GET['newQty'])) {
                        // $_SESSION[$cCode]['items'][$id]['jml'] = $_GET['newQty'];
                        // $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * ($_SESSION[$cCode]['items'][$id]['harga'] + $_SESSION[$cCode]['items'][$id]['ppn']));
                        $_SESSION[$cCode]['items'][$id]['qty_unit'] = $_GET['newQty'];
                        $_SESSION[$cCode]['items'][$id]['jml'] = $_GET['newQty'] * $_SESSION[$cCode]['items'][$id]['satuan_factor_qty'];

                    }
                    else {
                        // $_SESSION[$cCode]['items'][$id]['jml'] += $jml;
                        // $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * ($_SESSION[$cCode]['items'][$id]['harga'] + (isset($_SESSION[$cCode]['items'][$id]['ppn']) ? $_SESSION[$cCode]['items'][$id]['ppn'] : 0)));
                        if (($_SESSION[$cCode]['items'][$id]['qty_unit'] + $qty_unit) > $stok_unit) {
                            $_SESSION[$cCode]['items'][$id]['qty_unit'] = $stok_unit;
                            $_SESSION[$cCode]['items'][$id]['jml'] = $stok_unit * $_SESSION[$cCode]['items'][$id]['satuan_factor_qty'];
                        }
                        else {
                            $_SESSION[$cCode]['items'][$id]['qty_unit'] += $qty_unit;
                            $_SESSION[$cCode]['items'][$id]['jml'] += $qty_unit * $_SESSION[$cCode]['items'][$id]['satuan_factor_qty'];
                        }


                    }


                    // if (isset($arrDiscount[$id]) && sizeof($arrDiscount[$id]) > 0) {
                    //     foreach ($arrDiscount[$id] as $dKey => $dVal) {
                    //         if (!isset($_SESSION[$cCode]['items'][$id]['discount_' . $dKey])) {
                    //             $_SESSION[$cCode]['items'][$id]['discount_' . $dKey] = 0;
                    //         }
                    //         $_SESSION[$cCode]['items'][$id]['discount_' . $dKey] = $dVal;
                    //     }
                    // }

                    // arrPrint($itemNumLabels);
                    if (sizeof($itemNumLabels) > 0) {

                        foreach ($itemNumLabels as $key => $label) {
                            if (isset($_GET[$key]) && strlen($_GET[$key]) > 0) {
                                $newValue = $_GET[$key];
                                $tmp[$key] = $newValue;
                                $_SESSION[$cCode]['items'][$id][$key] = $newValue;

                            }

                        }


                        if ($subAmountConfig != null) {
                            $tmp['subtotal'] = makeValue($subAmountConfig, $_SESSION[$cCode]['items'][$id], $_SESSION[$cCode]['items'][$id], 0);
                        }
                        else {
                            $tmp['subtotal'] = 0;
                        }
                        $_SESSION[$cCode]['items'][$id]['subtotal'] = $tmp['subtotal'];
                    }


                }

                // matiHEre(__LINE__);
                /* ----------------------------------------------------------
                 * diskon-diskonan-grosir
                 * ----------------------------------------------------------*/
                // $tmp = $_SESSION[$cCode]['items'][$id];
                // $pro_premi = $row->premi_jual;
                // $pro_harga = $tmp['harga_list'] + (($pro_premi / 100) * $tmp['harga_list']);
                // cekBiru("$pro_premi || $pro_harga");
                // $pro_jml = $tmp['jml'];
                // $calc_hasil = $this->selectorDiskon($id, $pro_harga, $pro_jml);
                // arrPrintKuning($calc_hasil);
                // $tmp['discPersen'] = $calc_hasil['persen'];
                // $tmp['harga_disc'] = $calc_hasil['harga_af'];
                // $tmp['lastNett'] = $calc_hasil['harga_af'];
                // $tmp['harga'] = $calc_hasil['harga_af'];
                // $tmp['harga_jual'] = $calc_hasil['harga_be'];
                // $tmp['discNilai'] = $calc_hasil['nilai'];
                // $tmp['id'] = $id;


                // $_SESSION[$cCode]['items'][$produk_id] = $tmp;// untuk return digeser ke builder session lihat !isset() <----

            }


        }
        else {
            cekMerah("tidak ada itemnya! @" . __LINE__ . " " . __METHOD__);
            die();
        }

        // arrPrint($_SESSION[$cCode]['items']);
        // mati_disini(__LINE__);
        $f_selector = "";
        if (isset($_GET['selector'])) {
            $f_selector = "selector&";
        }

        //-----------------------------------------------------
        $dtime_now = dtimeNow();
        $dtime_now_ex = explode(" ", $dtime_now);
        $date_now = str_replace("-", "", $dtime_now_ex[0]);
        $time_now = str_replace(":", "", $dtime_now_ex[1]);
        $bookingNumber = "$date_now" . "$time_now";
        if (!isset($_SESSION[$cCode]["main"]["bookingNumber"]) || ($_SESSION[$cCode]["main"]["bookingNumber"] == null)) {
            $_SESSION[$cCode]["main"]["bookingNumber"] = $bookingNumber;
        }
        //-----------------------------------------------------

        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());

        if (isset($_GET['mb'])) {
            mati_disini(__FILE__ . "<hr> @" . __LINE__);
            $initMaster = array(
                "olehID" => $this->session->login['id'],
                "olehName" => $this->session->login['nama'],
                "placeID" => $this->session->login['cabang_id'],
                "placeName" => $this->session->login['cabang_nama'],
                "divID" => isset($this->session->login['div_id']) ? $this->session->login['div_id'] : 0,
                "divName" => isset($this->session->login['div_nama']) ? $this->session->login['div_nama'] : 0,
                "cabangID" => $this->session->login['cabang_id'],
                "cabangName" => $this->session->login['cabang_nama'],
                "gudangID" => $this->session->login['gudang_id'],
                "gudangName" => $this->session->login['gudang_nama'],
                "jenis_usaha" => isset($this->session->login['jenis_usaha']) ? $this->session->login['jenis_usaha'] : '-',
                "jenisTr" => $this->jenisTr,
                "jenisTrMaster" => $this->jenisTr,
                "jenisTrTop" => $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][1]['target'],
                "jenisTrName" => $this->jenisTrName,
                "stepNumber" => $stepNum,
                "stepCode" => $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][$stepNum]['target'],
                "dtime" => date("Y-m-d H:i:s"),
                "fulldate" => date("Y-m-d"),
                // "jenis_pajak"=>$this->session->login['jenis_usaha'],
                "tokoID" => $this->session->login['toko_id'],
                "tokoNama" => $this->session->login['toko_nama'],
            );
            // hevalueGateInisisai($initMaster);
            echo "<script>";
            echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?" . $f_selector . "selID=$id';";
            echo "top.load_shoppingcart();";
            echo "</script>";
        }
        else {
            $initMasterValues = array(
                "olehID" => my_id(),
                "olehName" => my_name(),
                "placeID" => my_cabang_id(),
                "placeName" => my_cabang_nama(),
                "divID" => my_div_id(),
                "divName" => my_div_nama(),
                "cabangID" => my_cabang_id(),
                "cabangName" => my_cabang_nama(),
                "gudangID" => my_gudang_id(),
                "gudangName" => my_gudang_nama(),
                "jenis_usaha" => my_jenis_usaha(),
                "tokoID" => my_toko_id(),
                "tokoNama" => my_toko_nama(),
                "jenisTr" => $this->jenisTr,
                "jenisTrMaster" => $this->jenisTr,
                "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
                "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
                "stepNumber" => $stepNum,
                "stepCode" => $this->configUiJenis['steps'][1]['target'],
                "dtime" => dtimeNow(),
                "fulldate" => dtimeNow("Y-m-d"),
                // "jenis_pajak"=>$this->session->login['jenis_usaha'],
            );
            // $vg->buildValue($this->jenisTr, $id, $initMasterValues, $configUiJenis);
            $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);
            // mati_disini(__FILE__ . "<hr> @" . __LINE__);
            // echo "<script>";
            // echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?".$f_selector."selID=$id&modul=".$this->modul."';";
            // echo "</script>";

            // matiHere(__METHOD__ . __LINE__);
            /* --------------------------------------------------
             * ngereload shoping cart dlm modul
             * --------------------------------------------------*/
            echo "<script>";
            echo "  if(top.document.getElementById('shopping_cart')){";
            echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "  }";
            echo "</script>";
        }
    }

    private function selectorDiskonKategori($sessioncCode)
    {

        $this->load->model("Mdls/MdlDiskonCustomer");
        $dcu = new MdlDiskonCustomer();
        $dc_params = $dcu_srcs = $dcu->callDiskonAktive();
        // arrPrint($row);
        // arrPrint($dcu_srcs);
        // arrPrintPink($dc_params);
        /* ------------------------------------------
         * nyocokin dr item ada yg masuk setting diskon atau tidak
         * ------------------------------------------*/
        $sess_item_kategories = isset($sessioncCode['items_kategori']) ? $sessioncCode['items_kategori'] : array();
        $potongan_nilai = array();
        foreach ($dc_params as $dc_jenis_0 => $dc_param) {
            cekBiru("$dc_jenis_0:: " . $dc_jenis_0);
            /*-------kalau ada diskonnya dihitung----------*/
            if ((count($sess_item_kategories) > 0) && array_key_exists($dc_jenis_0, $sess_item_kategories)) {
                $jml_kategori = $sess_item_kategories[$dc_jenis_0]['jml'];

                $jml_dcu = count($dc_param);
                $dcu_count = 0;
                foreach ($dc_param as $x => $item) {
                    $dcu_count++;
                    // arrPrintKuning($item);
                    $minim = $item["minim"];
                    $maxim = $jml_dcu == $dcu_count ? INF : $item["maxim"];
                    $nilai = $item["nilai"];

                    cekOrange(" //// $minim <= $jml_kategori <= $maxim /////");
                    if ($jml_kategori >= $minim && $jml_kategori <= $maxim) {
                        cekHijau("nilai:: $nilai");
                        $potongan_nilai[$dc_jenis_0]['nilai'] = $nilai;
                        $potongan_nilai[$dc_jenis_0]['jml'] = $jml_kategori;

                        break;
                    }
                    else {
                        $potongan_nilai[$dc_jenis_0]['nilai'] = 0;
                        $potongan_nilai[$dc_jenis_0]['jml'] = $jml_kategori;
                    }
                }
            }
            else {
                cekKuning("tidak ada diskon " . __LINE__);
            }

        }

        return $potongan_nilai;
    }

    public function selectProdukService()
    {

        $this->load->helper("he_angka_helper");
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;
        $stepNum = $this->uri->segment(5) > 0 ? $this->uri->segment(5) : 1;

        $cCode = $this->cCode;
        $selectorModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorSrcModel'];

        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        $priceSrcConfig = $this->config->item('hePrices') != null ? $this->config->item('hePrices') : array();
        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $priceConfig2 = isset($this->configUi[$this->jenisTr]['selectedPrice2']) ? $this->configUi[$this->jenisTr]['selectedPrice2'] : array();
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $ppnFactor = isset($_SESSION[$cCode]["main"]["ppnFactor"]) ? $_SESSION[$cCode]["main"]["ppnFactor"] : matiHEre("undefine ppn factor, please logout and login again");
        $tmpB = $b->lookupByID($id)->result();


        if (sizeof($tmpB) > 0) {
            foreach ($tmpB as $row) {
                $satuan = isset($row->satuan) && strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                $tmpJml = 1;
                if (isset($lockerConfig['enabled']) && $lockerConfig['enabled'] == true) {
                    cekMerah("masuk locker config");

                    $mdlName = $lockerConfig['mdlName'];
                    $this->load->model("Mdls/" . $mdlName);
                    $c = new $mdlName();
                    $c->addFilter("produk_id='$id'");
                    //                    $c->addFilter("id='$id'");//==id locker
                    $c->addFilter("state='active'");
                    $c->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                    $c->addFilter("gudang_id=" . $this->session->login['gudang_id']);
                    $tmpC = $c->lookupAll($id)->result();
                    cekHere($this->db->last_query());

                    //                    $persediaan = sizeof($tmpC) > 0 ? $tmpC[0]->persediaan : "0";
                    if (sizeof($tmpC) > 0) {
                        arrPrint($tmpC);
                        foreach ($tmpC as $row) {
                            $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                            $nama = $row->nama;

                            $jml_now = $row->jumlah;
                            if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                                $jml_sudah_diambil = 0;
                                $jml_diperlukan = 1;
                                $jml_nambah = 1;
                            }
                            else {
                                //                                if (isset($_GET['newQty'])) {
                                //                                    $jml_sudah_diambil = $_SESSION[$cCode]['items'][$id]['jml'];
                                //                                    $jml_diperlukan = $_GET['newQty'];
                                //                                    $jml_nambah = $jml_diperlukan - $jml_sudah_diambil;
                                //                                }
                                //                                else {
                                //                                    $jml_sudah_diambil = $_SESSION[$cCode]['items'][$id]['jml'];
                                //                                    $jml_diperlukan = $jml_sudah_diambil + $jml;
                                //                                    $jml_nambah = $jml;
                                //                                }
                            }
                            //  region validasi stok
                            //                            if ($jml_nambah > $jml_now) {
                            //                                echo "<script>top.alert('stok $nama tidak cukup. (perlu $jml_diperlukan, nambah $jml_nambah stok $jml_now)')";
                            //                                echo "</script>";
                            //                                die();
                            //                            }
                            //  endregion validasi stok

                            //
                            //                            $this->db->trans_start();
                            //
                            //                            //  region update locker active
                            //                            $where = array(
                            //                                "id" => $row->id,
                            //                            );
                            //                            $data_active = array(
                            //                                "jumlah" => $jml_now - $jml_nambah,
                            //                                "state" => "active",
                            //                            );
                            //                            $c->updateData($where, $data_active);
                            //                            cekHere($this->db->last_query());
                            //                            //  endregion update locker active
                            //
                            //
                            //                            //  region locker hold
                            //                            $array_hold_sebelumnya = $c->cekLoker($this->session->login['cabang_id'], $id, "hold", $this->session->login['id'], "0", $this->session->login['gudang_id']);
                            ////                            arrPrint($array_hold_sebelumnya);
                            ////                            mati_disini();
                            //                            if (sizeof($array_hold_sebelumnya) > 0) {
                            //                                $where = array(
                            //                                    "id" => $array_hold_sebelumnya['id'],
                            //                                );
                            //                                $data_hold = array(
                            //                                    "jumlah" => $array_hold_sebelumnya['jumlah'] + $jml_nambah,
                            //                                );
                            //                                $c->updateData($where, $data_hold);
                            //                                cekHere($this->db->last_query());
                            //                            }
                            //                            else {
                            //                                $data_hold = array(
                            //                                    "jenis" => "produk",
                            //                                    "cabang_id" => $this->session->login['cabang_id'],
                            //                                    "produk_id" => $id,
                            //                                    "nama" => $nama,
                            //                                    "satuan" => $row->satuan,
                            //                                    "state" => "hold",
                            //                                    "jumlah" => $jml_nambah,
                            //                                    "oleh_id" => $this->session->login['id'],
                            //                                    "oleh_nama" => $this->session->login['nama'],
                            //                                    "gudang_id" => $this->session->login['gudang_id'],
                            //                                );
                            //                                $c->addData($data_hold);
                            //                                cekHere($this->db->last_query());
                            //                            }
                            //                            //  endregion locker hold
                            //
                            //                            $this->db->trans_complete() or die("Gagal bro");
                            //
                            //                            $tmpJml = $jml_diperlukan;

                        }
                    }
                    else {
                        mati_disini("tidak ditemukan item " . $row->nama . " di locker stock.");
                    }

                }

                $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
                if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                    $tmp = array(
                        "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
                        "id" => $id,
                        "jml" => $tmpJml,
                        "harga" => 0,
                        "nilai_untung" => 0,
                        "nilai_rugi" => 0,
                        "nilai_final_rugilaba" => 0,
                        //                        "txt_rugilaba" => "kerugian",
                        "subtotal" => 0,
                    );

                    if (sizeof($priceConfig) > 0) {
                        $mdlName = $priceConfig['model'];
                        $this->load->model("Mdls/" . $mdlName);
                        $h = new $mdlName();
                        $h->addFilter("produk_id='$id'");
                        $h->addFilter("status='1'");
                        $h->addFilter("jenis_value in ('" . implode("','", $priceConfig['label']) . "')");
                        //                        $h->addFilter("jenis_value in (" . implode(",", $priceConfig['label']) . ")");
                        $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                        $tmpH = $h->lookupAll($id)->result();
                        cekMerah($this->db->last_query());
                        if (sizeof($tmpH) > 0) {
                            $rawPrices = array();
                            foreach ($tmpH as $hSpec) {
                                foreach ($priceConfig['key_label'] as $key => $val) {
                                    if ($key == $hSpec->jenis_value) {
                                        $rawPrices[$key] = isset($hSpec->nilai) ? $hSpec->nilai : 0;
                                    }
                                }

                            }
                            $prices = normalizePrices("produk", $rawPrices);
                            if (sizeof($prices) > 0) {
                                foreach ($prices as $k => $v) {
                                    $tmp[$k] = $v;
                                }
                                $tmp['harga_perolehan'] = isset($tmp[$priceConfig['mainSrc']]) ? $tmp[$priceConfig['mainSrc']] : 0;
                                $tmp['harga'] = isset($tmp[$priceConfig['mainSrc']]) ? $tmp[$priceConfig['mainSrc']] : 0;
                            }
                        }

                    }
                    if (sizeof($priceConfig2) > 0) {
                        $mdlName = $priceConfig2['model'];
                        $this->load->model("Mdls/" . $mdlName);
                        $h = new $mdlName();
                        $h->addFilter("produk_id='$id'");
                        $h->addFilter("state='active'");
                        $h->addFilter("jenis in ('" . implode("','", $priceConfig2['label']) . "')");
                        $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                        $tmpH = $h->lookupAll($id)->result();

                        //                        cekHere("masuk sini bro #2, LINE => " . __LINE__);
                        //                        cekMerah($this->db->last_query());

                        if (sizeof($tmpH) > 0) {
                            $rawPrices = array();
                            foreach ($tmpH as $hSpec) {
                                foreach ($priceConfig2['key_label'] as $key => $val) {
                                    if ($key == $hSpec->jenis) {
                                        $rawPrices[$key] = isset($hSpec->nilai) ? $hSpec->nilai : 0;
                                    }
                                }

                            }
                            $prices = normalizePrices("produk", $rawPrices);
                            if (sizeof($prices) > 0) {
                                foreach ($prices as $k => $v) {
                                    $tmp[$k] = $v;
                                }
                                $tmp['harga_sisa_tmp'] = isset($tmp[$priceConfig2['mainSrc']]) ? $tmp[$priceConfig2['mainSrc']] : 0;
                                $tmp['harga_sisa'] = isset($tmp[$priceConfig2['mainSrc']]) ? $tmp[$priceConfig2['mainSrc']] : 0;
                                $tmp['harga'] = isset($tmp[$priceConfig2['mainSrc']]) ? $tmp[$priceConfig2['mainSrc']] : 0;
                            }
                        }

                    }

                    foreach ($fieldSrcs as $key => $src) {
                        //                        cekHere($row->$src . " " . $src);
                        $tmp[$key] = makeValue($src, $tmp, $tmp, $row->$src);
                    }
                    if ($subAmountConfig != null) {
                        $tmp['subtotal'] = makeValue($subAmountConfig, $tmp, $_SESSION[$cCode]['items'][$id], 0);
                    }
                    else {
                        $tmp['subtotal'] = 0;
                    }
                    $_SESSION[$cCode]['items'][$id] = $tmp;

                }
                else {

                    //                    matiHere(__LINE__);
                    if (sizeof($itemNumLabels) > 0) {

                        foreach ($itemNumLabels as $key => $label) {
                            if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                $newValue = $_GET[$key];
                                $tmp[$key] = $newValue;
                                $_SESSION[$cCode]['items'][$id][$key] = $newValue;

                            }
                        }
                        //                        if (sizeof($_SESSION[$cCode]['items'][$id][$key]) > 0) {
                        //                            if (!isset($_SESSION[$cCode]['items'][$id]['nilai_untung'])) {
                        //                                $_SESSION[$cCode]['items'][$id]['nilai_untung'] = 0;
                        //                            }
                        //                            if (!isset($_SESSION[$cCode]['items'][$id]['nilai_rugi'])) {
                        //                                $_SESSION[$cCode]['items'][$id]['nilai_rugi'] = 0;
                        //                            }
                        //                            $_SESSION[$cCode]['items'][$id]['nilai_untung'] = ($_SESSION[$cCode]['items'][$id]['harga'] - $_SESSION[$cCode]['items'][$id]['harga_sisa']) >= 0 ? ($_SESSION[$cCode]['items'][$id]['harga'] - $_SESSION[$cCode]['items'][$id]['harga_sisa']) : 0;
                        //                            $_SESSION[$cCode]['items'][$id]['nilai_rugi'] = ($_SESSION[$cCode]['items'][$id]['harga'] - $_SESSION[$cCode]['items'][$id]['harga_sisa']) >= 0 ? 0 : ($_SESSION[$cCode]['items'][$id]['harga_sisa'] - $_SESSION[$cCode]['items'][$id]['harga']);
                        //
                        //                        }
                        if ($subAmountConfig != null) {
                            $tmp['subtotal'] = makeValue($subAmountConfig, $_SESSION[$cCode]['items'][$id], $_SESSION[$cCode]['items'][$id], 0);
                        }
                        else {
                            $tmp['subtotal'] = 0;
                        }
                        $_SESSION[$cCode]['items'][$id]['subtotal'] = $tmp['subtotal'];
                    }


                }
            }

            //            if (sizeof($_SESSION[$cCode]['items']) > 0) {
            //                $_SESSION[$cCode]['main']['txt_rugilaba'] = "kerugian";
            //                $nilai_untung = 0;
            //                $nilai_rugi = 0;
            //                foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
            //                    $nilai_untung += $iSpec['nilai_untung'];
            //                    $nilai_rugi += $iSpec['nilai_rugi'];
            //                }
            //
            //                $_SESSION[$cCode]['main']['txt_rugilaba'] = ($nilai_untung - $nilai_rugi) >= 0 ? "keuntungan" : "kerugian";
            //                $_SESSION[$cCode]['items'][$id]['nilai_final_rugilaba'] = ($nilai_untung - $nilai_rugi) >= 0 ? ($nilai_untung - $nilai_rugi) : ($nilai_rugi - $nilai_untung);
            //            }
            //
            //            if (sizeof($_SESSION[$cCode]['items']) > 0) {
            //                $_SESSION[$cCode]['main']['harga'] = 0;
            //                foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
            //                    $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
            //                }
            //            }

        }
        else {
            cekMerah("tidak ada itemnya!");
            die();
        }

        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setppnFactor($ppnFactor);


        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, $stepNum, $this->configUiJenis);

        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        // matiHere(__METHOD__ . __LINE__);
        /* --------------------------------------------------
         * ngereload shoping cart dlm modul
         * --------------------------------------------------*/
        echo "<script>";
        echo "  if(top.document.getElementById('shopping_cart')){";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "  }";
        echo "</script>";
    }

    public function selectCrm(){

        $cek_source_index = $this->uri->segment(1) == "penjualan" ? "redirect":"default";
        $custID = $_GET["pihakID"];
        $estimate_id = $this->uri->segment(5);
        $lsitProduk = blobDecode($_GET["enc"]);
        $expectedMainPriceKey = $this->getExpectedMainPriceKey("jual");
        $this->setMissingPriceSyncPayloadFromCrmItems($lsitProduk, $expectedMainPriceKey);
        $this->load->model("Mdls/MdlCustomer");
        $this->load->model("Mdls/MdlProduk");
        $c = new MdlCustomer();
        $p = new MdlProduk();

        //region load data custoemr dahulu
        $pihakMainValueSrc = isset($this->configUi[$this->jenisTr]['pihakMainValueSrc']) ? $this->configUi[$this->jenisTr]['pihakMainValueSrc'] : array();
        $pihakValidate = isset($this->configUi[$this->jenisTr]['pihakValidate']) ? $this->configUi[$this->jenisTr]['pihakValidate'] : array();
        $pihakAddValidate = isset($this->configUi[$this->jenisTr]['pihakAddValidate']) ? $this->configUi[$this->jenisTr]['pihakAddValidate'] : array();
        $pihakPair = isset($this->configUi[$this->jenisTr]['pihakPair']) ? $this->configUi[$this->jenisTr]['pihakPair'] : array();

        $cCode = $this->cCode;
        $ppnFactor = isset($_SESSION[$cCode]["main"]["ppnFactor"]) ? $_SESSION[$cCode]["main"]["ppnFactor"] : matiHEre("undefine ppn factor");

        // matiHere(__LINE__);
        $id = isset($_GET['pihakID']) ? $_GET['pihakID'] : 0;
        $mdlName = "MdlCustomer";

//        $this->load->model("Mdls/" . $mdlName);
        $b = new $mdlName();
        $tmpB = $b->lookupByID($id)->result();
//        showLast_query("biru");
//        arrPrintWebs($tmpB);
        //---------------------------------------------------
        if (sizeof($pihakValidate) > 0) {

            foreach ($pihakValidate as $kolom => $spec) {
                if (isset($tmpB[0]->$kolom) && ($tmpB[0]->$kolom != NULL)) {
                    $result = $tmpB[0]->$kolom;
                    $tb_kolom = $spec['result'][$result]['kolom'];
                    $tb_label = $spec['result'][$result]['label'];
                    if (isset($tmpB[0]->$tb_kolom) && ($tmpB[0]->$tb_kolom != NULL)) {
                        cekHijau("LANJUT...");
                    }
                    else {
                        $label = $tmpB[0]->nama . ", " . $tb_label;
                        die(lgShowAlertBiru($label));
                    }
                }
                else {
                    $label = $tmpB[0]->nama . ", " . $spec['result']['none']['label'];
                    die(lgShowAlertBiru($label));
                }
            }
        }

        if (sizeof($pihakAddValidate) > 0) {
            $addMode = isset($pihakAddValidate['mode']) ? $pihakAddValidate['mode'] : NULL;
            $addFilter = isset($pihakAddValidate['filter']) ? $pihakAddValidate['filter'] : array();
            if (sizeof($addFilter) > 0) {
                foreach ($addFilter as $kf => $vf) {

                    cekHere(":: $kf => $vf :: $addMode ::");
                    switch ($addMode) {
                        case "!=":
                            if ($tmpB[0]->$kf != $vf) {
                                $label = $pihakAddValidate['label'][$kf];
                                die(lgShowAlertBiru($label));
                            }
                            break;
                        case "==":
                            if ($tmpB[0]->$kf == $vf) {
                                $label = $pihakAddValidate['label'][$kf];
                                die(lgShowAlertBiru($label));
                            }
                            break;
                        default:
                            cekHitam(":: masuk sini, default ::");
                            break;
                    }

                }
            }
        }

        if (sizeof($pihakPair) > 0) {
            $pihakPairData = array();
            if (isset($pihakPair["enabled"]) && ($pihakPair["enabled"] == true)) {
                $pairModel = $pihakPair["model"];
                $this->load->model("Coms/$pairModel");
                $pm = New $pairModel();
                $pm->addFilter("extern_id='$id'");
                if (sizeof($pihakPair["filter"]) > 0) {
                    makeFilter($pihakPair["filter"], $_SESSION[$cCode]["main"], $pm);
                }
                $pmTmp = $pm->$pihakPair["method"]($pihakPair["rekening"]);
                showLast_query("biru");
                if (sizeof($pmTmp) > 0) {
//                    arrPrintPink($pmTmp);
                    $pihakPairData[$id]["saldo"] = $pmTmp[0]->$pihakPair["key"];
                }
            }
        }
        //---------------------------------------------------
//arrPrintWebs($pihakPairData);
//mati_disini(__LINE__);

        if (isset($this->configUi[$this->jenisTr]["pihakMainNota"]) && $this->configUi[$this->jenisTr]["pihakMainNota"] == true) {
            $selectColumn = "nomer";
        }
        else {
            $selectColumn = "nama";
        }

        // region resetor session delivery dan billing detail
        $gateReset = array("main", "tableIn_master_values");
        $resetor = array(
            "vendorDetails",
            "billingDetails",
            "deliveryDetails",
        );
        foreach ($gateReset as $gate) {

            if (isset($_SESSION[$cCode][$gate])) {
                foreach ($_SESSION[$cCode][$gate] as $keys => $values) {
                    $keysTmp = explode("__", $keys);
                    // buang yang sama dulu
                    if (in_array($keys, $resetor)) {
                        unset($_SESSION[$cCode][$gate][$keys]);
                    }
                    // buang yang mengandung __
                    if (in_array($keysTmp[0], $resetor)) {
                        unset($_SESSION[$cCode][$gate][$keys]);
                    }
                }
            }
        }
        if (isset($_SESSION[$cCode]['main_elements'])) {
            foreach ($resetor as $resetValue) {
                if (array_key_exists($resetValue, $_SESSION[$cCode]['main_elements'])) {
                    unset($_SESSION[$cCode]['main_elements'][$resetValue]);
                }
            }
        }


        // endregion

        //-----------------------------------------------------
        $dtime_now = dtimeNow();
        $dtime_now_ex = explode(" ", $dtime_now);
        $date_now = str_replace("-", "", $dtime_now_ex[0]);
        $time_now = str_replace(":", "", $dtime_now_ex[1]);
        $bookingNumber = "$date_now" . "$time_now";
        if(!isset($_SESSION[$cCode]["main"]["bookingNumber"]) || ($_SESSION[$cCode]["main"]["bookingNumber"]==null)){
            $_SESSION[$cCode]["main"]["bookingNumber"] = $bookingNumber;
        }
        //-----------------------------------------------------

//        $this->load->library("ValueGate");
//        $vg = new ValueGate();
//        $vg->setConfigUiJenis($this->configUiJenis);
//        $vg->setConfigCoreJenis($this->configCoreJenis);
//        $vg->setConfigValuesJenis($this->configValuesJenis);
//        $vg->setPpnFactor($ppnFactor);
        if (sizeof($tmpB) > 0) {

            $_SESSION[$cCode]['main']['pihakID'] = $id;
            $_SESSION[$cCode]['main']['pihakLevel'] = $tmpB[0]->level_id;
            $_SESSION[$cCode]['main']['pihakName'] = isset($tmpB[0]->$selectColumn) ? $tmpB[0]->$selectColumn : "";
            $_SESSION[$cCode]['main']['pihakName2'] = isset($tmpB[0]->$selectColumn) ? formatNota($selectColumn, $tmpB[0]->$selectColumn) : "";
            $_SESSION[$cCode]['main']['pihakDisc'] = isset($tmpB[0]->diskon) ? $tmpB[0]->diskon : "";
            $_SESSION[$cCode]['main']['kategoriID'] = isset($tmpB[0]->kategori_id) ? $tmpB[0]->kategori_id : 0;
            $_SESSION[$cCode]['main']['kategoriNama'] = isset($tmpB[0]->kategori_nama) ? $tmpB[0]->kategori_nama : "";
            $_SESSION[$cCode]['main']['kategoriName'] = isset($tmpB[0]->kategori_nama) ? $tmpB[0]->kategori_nama : "";

            $tmpPihakName = isset($tmpB[0]->$selectColumn) ? formatNota($selectColumn, $tmpB[0]->$selectColumn) : "";
            if (isset($tmpB[0]->name)) {
                $tmpPihakName = $tmpB[0]->name;
            }

            if (sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
                }
            }

            //---------------------------------------------
            $kredit_limit = $tmpB[0]->kredit_limit;
            $duedays = $tmpB[0]->due_days;
            $default_payment_method = $kredit_limit > 0 ? "credit" : "cash";
            $default_term_of_payment = $duedays > 0 ? $duedays : 0;
            $_SESSION[$cCode]['main']['paymentMethod'] = 0;
            $_SESSION[$cCode]['main']['defaultPaymentMethod'] = 0;
            $_SESSION[$cCode]['main']['defaultTermOfPayment'] = 0;
            $_SESSION[$cCode]['main']['pihakKreditLimit'] = 0;

            $_SESSION[$cCode]['main']['paymentMethod'] = $default_payment_method;
            $_SESSION[$cCode]['main']['defaultPaymentMethod'] = $default_payment_method;
            $_SESSION[$cCode]['main']['defaultTermOfPayment'] = $default_term_of_payment;
            $_SESSION[$cCode]['main']['pihakKreditLimit'] = $kredit_limit;

            //---------------------------------------------
            if (isset($pihakPairData[$id])) {
                $_SESSION[$cCode]['main']['pihakPoint'] = $pihakPairData[$id]["saldo"];
            }
            //---------------------------------------------


//            $initMasterValues = array(
//                "olehID" => my_id(),
//                "olehName" => my_name(),
//                "sellerID" => my_id(),
//                "sellerName" => my_name(),
//                "placeID" => my_cabang_id(),
//                "placeName" => my_cabang_nama(),
//                "divID" => my_div_id(),
//                "divName" => my_div_nama(),
//                "cabangID" => my_cabang_id(),
//                "cabangName" => my_cabang_nama(),
//                "gudangID" => my_gudang_id(),
//                "gudangName" => my_gudang_nama(),
//                "jenis_usaha" => my_jenis_usaha(),
//                "tokoID" => my_toko_id(),
//                "tokoNama" => my_toko_nama(),
//                "jenisTr" => $this->jenisTr,
//                "jenisTrMaster" => $this->jenisTr,
//                "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
//                "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
//                "stepNumber" => 1,
//                "stepCode" => isset($this->configUiJenis['steps'][1]['target']) ? $this->configUiJenis['steps'][1]['target'] : 0,
//                "dtime" => dtimeNow(),
//                "fulldate" => dtimeNow("Y-m-d"),
//                // "jenis_pajak"=>$this->session->login['jenis_usaha'],
//            );
//            $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

//mati_disini("$kredit_limit ;;; $default_payment_method ;;; " . $_SESSION[$cCode]['main']['defaultPaymentMethod']);
            if($cek_source_index =="redirect"){

            }
            else{
            echo "<script>";
            echo "top.document.getElementById('pihakName').value='" . $tmpPihakName . "';";
            echo "top.document.getElementById('pilihan_outlet').innerHTML='';";
            echo "</script>";
        }

        }
        else {
            cekMerah($id);
            $warehouse = getDefaultWarehouseID($this->session->login['cabang_id']);
//            arrPrint($warehouse);
            $_SESSION[$cCode]['main']['pihakID'] = $id;
//            $_SESSION[$cCode]['main']['pihakName'] = "default warehouse";
            $_SESSION[$cCode]['main']['pihakName'] = $warehouse['gudang_nama'];
            $_SESSION[$cCode]['main']['pihakName2'] = "";

            if (sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
//                    $_SESSION[$cCode]['out_master'][$key] = $tmpB[0]->$src;
                }
            }
            // echo "<script>";
            // echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?ohYes=ohNo');";
            // echo "top.document.getElementById('pihakName').value='" . $_SESSION[$cCode]['main']['pihakName'] . "';";
            // echo "top.document.getElementById('pilihan_outlet').innerHTML='';";
            // echo "</script>";
        }
        //endregion

        $_SESSION[$cCode]["main"]["ref_order"]="crm";
        $_SESSION[$cCode]["main"]["ref_order_estimate_id"]=$estimate_id;
        //region load data produk
        foreach($lsitProduk as $pid =>$pidData){
            //untuk obat sementara discount minus
            if ($_GET["disc_percent"] < 0 || $_GET["disc_percent"] > 100) {
                $minus = $_GET["disc_percent"] < 0 ? "persentase diskon salah, silahkan menggunakan nilai positif" : "pemberian diskon salah, diskon maksimal 100%";
                matiHEre($minus);
            }
            if ($_GET["disc"] < 0) {
                matiHEre("pemberian diskon salah, silahkan menggunakan nilai positif");
            }
            // if($_GET["nett1"] < 0){
            //     matiHEre("pemberian diskon salah, diskon maksimal 100%");
            // }

            //

            $this->load->helper("he_angka_helper");
            $this->load->library("FieldCalculator");
            $cal = new FieldCalculator();

            $produk_id = $pid;
            $jml = isset($pidData['jml']) ? $pidData['jml'] : 1;
            $stepNum = $this->uri->segment(5) > 0 ? $this->uri->segment(5) : 1;
            $cCode = $this->cCode;

            $selectorModel = isset($_SESSION[$cCode]['main']['pihakMdlName']) ? $_SESSION[$cCode]['main']['pihakMdlName'] : $this->configUi[$this->jenisTr]['selectorModel'];
            $selectorSrcModel = isset($_SESSION[$cCode]['main']['pihakMdlNameSrc']) ? $_SESSION[$cCode]['main']['pihakMdlNameSrc'] : $this->configUi[$this->jenisTr]['selectorSrcModel'];
            $arrDataTambahan = isset($this->configUi[$this->jenisTr]['produkUnitPart']) ? $this->configUi[$this->jenisTr]['produkUnitPart'] : array();
            //-----------------------------------------------
            if (!isset($_SESSION[$cCode]['items2'][$pid])) {
                $_SESSION[$cCode]['items2'][$pid] = array();
            }

            // detektor tanda kurawal {}
            if (substr($selectorModel, 0, 1) == "{") {
                $selectorModel = trim($selectorModel, "{");
                $selectorModel = trim($selectorModel, "}");
                $selectorModel = str_replace($selectorModel, $_SESSION[$cCode]['main'][$selectorModel], $selectorModel);
            }
            else {
                cekkuning("TIDAK mengandung kurawal @" . __LINE__ . __CLASS__);
            }
            if (substr($selectorSrcModel, 0, 1) == "{") {
                $selectorSrcModel = trim($selectorSrcModel, "{");
                $selectorSrcModel = trim($selectorSrcModel, "}");
                $selectorSrcModel = str_replace($selectorSrcModel, $_SESSION[$cCode]['main'][$selectorSrcModel], $selectorSrcModel);
            }
            else {
                cekkuning("TIDAK mengandung kurawal @" . __LINE__ . " " . __METHOD__);
            }

            $this->load->model("Mdls/" . $selectorSrcModel);
            $b = new $selectorSrcModel();
//            matiHEre($selectorSrcModel);

            $priceSrcConfig = $this->config->item('hePrices') != null ? $this->config->item('hePrices') : array();
            $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();

            $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
            $priceMainConfig = isset($this->configUi[$this->jenisTr]['selectedMainPrice']) ? $this->configUi[$this->jenisTr]['selectedMainPrice'] : array();

            $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
            $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
            $connectedDiscountConfig = isset($this->configUi[$this->jenisTr]['connectedDiscount']) ? $this->configUi[$this->jenisTr]['connectedDiscount'] : array();
            $priceFilter = isset($this->configUi[$this->jenisTr]['selectedPrice']['mdlFilter']) ? $this->configUi[$this->jenisTr]['selectedPrice']['mdlFilter'] : array();
            $resetFilter = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
            $validateMeasurement = isset($this->configUi[$this->jenisTr]['validateMeasurement'][1]) ? $this->configUi[$this->jenisTr]['validateMeasurement'][1] : array();
            $ppnFactor = isset($_SESSION[$cCode]["main"]["ppnFactor"]) ? $_SESSION[$cCode]["main"]["ppnFactor"] : my_ppn_factor();

            $tmpB = $b->lookupByID($pid)->result();
             showLast_query("lime");
            // matiHere(__LINE__ . " " .__METHOD__);

            // -----------------------------------------
            $tableIn_master = $_SESSION[$cCode]['tableIn_master'];
            // arrPrint($tableIn_master);
            $gudang_status_id = $tableIn_master['gudang_status_id'];

            $this->load->model("Mdls/MdlLockerStockBooking");
            $lsb = new MdlLockerStockBooking();
            $lsb_datas = $lsb->getStokBooking();
            $ppnFactorInclude = $_SESSION[$cCode]['main']['ppnFactorInclude'];

            $this->load->library("Diskon");
            $ld = new Diskon();
            $ld->setTokoId(my_toko_id());
            $pro_jml = 0;
            if (sizeof($tmpB) > 0) {
                foreach ($tmpB as $row) {
                    $rows = $row;
                    $item_jenis = $row->jenis;//item,komposit/paket,rakitan
                    $produk_jenis_id = $rows->kategori_id;
                    $produk_jenis = $rows->kategori_nama;
                    $produk_nama = $rows->nama;
                    $produk_kode = $rows->kode;
                    $produk_kode = htmlspecialchars($produk_kode);
                    $produk_nama = htmlspecialchars($produk_nama);
                    $produk_label_2 = "<span class='text-red'>Produk $produk_kode $produk_nama</span>";

                    $valValidate_items = array();

                    if (sizeof($validateMeasurement) > 0) {
                        $iValidate = 0;
                        foreach ($validateMeasurement as $keyVal => $validateKol) {
                            $valValidate = $row->$keyVal;
                            if ($valValidate == 0) {
                                $msg = "<br><red class='text-red'>" . htmlspecialchars($row->kode) . " " . htmlspecialchars($row->nama) . "</red><hr><br><red class='text-red'>$validateKol = $valValidate </red><br>silahkan hubungi bagian entry data untuk melengkapi data produk";
                                $alerts = array(
                                    "type" => "warning",
                                    "title" => strtoupper("Data ukuran produk $produk_label_2 belum lengkap "),
                                    "html" => $msg,
                                );
                                echo swalAlert($alerts);
                                die($msg);
                            }
                        }

                    }

                    if (sizeof($valValidate_items) > 0) {
                        //                    arrPrint($valValidate_items);
                        $msg = "Data pendukung produk belum lengkap<br><red class='text-red'>" . htmlspecialchars($row->kode) . " " . htmlspecialchars($row->nama) . "</red><hr>$jml_now $satuan stock available";
                        $alerts = array(
                            "type" => "warning",
                            "title" => strtoupper($kode),
                            "html" => $msg,
                        );
                        echo swalAlert($alerts);
                        die($msg);
                    }

                    $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";

                    $tmpJml = $jml;
                    if (isset($lockerConfig['enabled']) && $lockerConfig['enabled'] == true) {
                        cekMerah("masuk locker config");

                        $mdlName = $lockerConfig['mdlName'];
                        $this->load->model("Mdls/" . $mdlName);
                        $c = new $mdlName();
                        $c->addFilter("produk_id='$pid'");
                        //                    $c->addFilter("id='$pid'");//==id locker
                        $c->addFilter("state='active'");
                        $c->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                        $c->addFilter("gudang_id=" . $this->session->login['gudang_id']);


                        $tmpC = $c->lookupAll($pid)->result();
                        cekHere($this->db->last_query());
                        // matiHere(__LINE__. "<hr>");
                        //                    $persediaan = sizeof($tmpC) > 0 ? $tmpC[0]->persediaan : "0";
                        if (sizeof($tmpC) > 0) {
                            // arrPrint($tmpC);
                            // arrPrint($row);
                            $kode = $row->kode;
                            foreach ($tmpC as $row) {
                                $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                                $nama = $row->nama;

                                $jml_now = $row->jumlah;
                                if (!array_key_exists($pid, $_SESSION[$cCode]['items'])) {
                                    $jml_sudah_diambil = 0;
                                    $jml_diperlukan = 1;
                                    $jml_nambah = 1;
                                }
                                else {
                                    if (isset($_GET['newQty'])) {
                                        $jml_sudah_diambil = $_SESSION[$cCode]['items'][$pid]['jml'];
                                        $jml_diperlukan = $_GET['newQty'];
                                        $jml_nambah = $jml_diperlukan - $jml_sudah_diambil;
                                    }
                                    else {
                                        $jml_sudah_diambil = $_SESSION[$cCode]['items'][$pid]['jml'];
                                        $jml_diperlukan = $jml_sudah_diambil + $jml;
                                        $jml_nambah = $jml;
                                    }
                                }
                                //  region validasi stok
                                if ($jml_nambah > $jml_now) {
                                    // echo "<script>top.alert('stok $nama tidak cukup. (perlu $jml_diperlukan, nambah $jml_nambah stok $jml_now)')";
                                    // echo "</script>";
                                    $msg = "Insufficient stock of:<br><red class='text-red'>$kode $nama</red><hr>$jml_now $satuan stock available";
                                    $alerts = array(
                                        "type" => "warning",
                                        "title" => strtoupper($kode),
                                        "html" => $msg,
                                    );
                                    echo swalAlert($alerts);
                                    die($msg);

                                }
                                //  endregion validasi stok


                                $this->db->trans_start();

                                //  region update locker active
                                $where = array(
                                    "id" => $row->id,
                                );
                                $data_active = array(
                                    "jumlah" => $jml_now - $jml_nambah,
                                    "state" => "active",
                                );
                                $c->updateData($where, $data_active);
                                cekHere($this->db->last_query());
                                //  endregion update locker active


                                //  region locker hold
                                $array_hold_sebelumnya = $c->cekLoker($this->session->login['cabang_id'], $pid, "hold", $this->session->login['id'], "0", $this->session->login['gudang_id']);
                                //                            arrPrint($array_hold_sebelumnya);
                                //                            mati_disini();
                                if (sizeof($array_hold_sebelumnya) > 0) {
                                    $where = array(
                                        "id" => $array_hold_sebelumnya['id'],
                                    );
                                    $data_hold = array(
                                        "jumlah" => $array_hold_sebelumnya['jumlah'] + $jml_nambah,
                                    );
                                    $c->updateData($where, $data_hold);
                                    cekHere($this->db->last_query());
                                }
                                else {
                                    $data_hold = array(
                                        "jenis" => "produk",
                                        "cabang_id" => $this->session->login['cabang_id'],
                                        "produk_id" => $pid,
                                        "nama" => $nama,
                                        "satuan" => $row->satuan,
                                        "state" => "hold",
                                        "jumlah" => $jml_nambah,
                                        "oleh_id" => $this->session->login['id'],
                                        "oleh_nama" => $this->session->login['nama'],
                                        "gudang_id" => $this->session->login['gudang_id'],
                                    );
                                    $c->addData($data_hold);
                                    cekHere($this->db->last_query());
                                }
                                //  endregion locker hold

                                $this->db->trans_complete() or die("Gagal bro");

                                $tmpJml = $jml_diperlukan;

                            }
                        }
                        else {
                            mati_disini("tidak ditemukan item " . $row->nama . " di locker stock.");
                        }

                    }

                    if (sizeof($connectedDiscountConfig) > 0) {
                        if ($connectedDiscountConfig['enabled'] == 1) {
                            $mdlNameRelation = $connectedDiscountConfig['mdlNameRelation'];
                            $mdlNameSource = $connectedDiscountConfig['mdlNameSource'];

                            $this->load->model("Mdls/" . $mdlNameRelation);
                            $dr = new $mdlNameRelation();
                            $dr->addFilter("produk_id='$pid'");
                            $dr->addFilter("status='1'");
                            $tmpDr = $dr->lookupAll($pid)->result();
                            //                        cekMerah($this->db->last_query());
                            //                        arrPrint($tmpDr);
                            $produkQty = isset($_GET['jml']) ? $_GET['jml'] : $tmpJml;
                            foreach ($tmpDr as $drSpec) {
                                $this->load->model("Mdls/" . $mdlNameSource);
                                $sr = new $mdlNameSource();
                                $sr->addFilter("id='" . $drSpec->diskon_id . "'");
                                $sr->addFilter("status='1'");
                                $tmpSr = $sr->lookupAll($pid)->result();
                                showLast_query("merah");
                                //                            arrPrint($tmpSr);
                                foreach ($tmpSr as $srSpec) {
                                    arrPrint($srSpec);
                                    if ($produkQty > $srSpec->max_qty) {
                                        $discountPersen = $srSpec->discount_persen;
                                        $discountQty = $srSpec->discount_qty;
                                    }
                                    elseif (($produkQty >= $srSpec->min_qty) && ($produkQty <= $srSpec->max_qty)) {
                                        $discountPersen = $srSpec->discount_persen;
                                        $discountQty = $srSpec->discount_qty;
                                    }
                                    else {
                                        $discountPersen = 0;
                                        $discountQty = 0;
                                    }
                                    $arrDiscount[$pid] = array(
                                        "persen" => $discountPersen,
                                        "qty" => $discountQty,
                                    );
                                    cekMerah("pID: $pid ::: persen: $discountPersen ::: qty: $discountQty");
                                }
                            }
                        }
                    }

                    $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");

                    /* ----------------------------------------------------------------
                     * inisisasi cCode Items harga price
                     * ----------------------------------------------------------------*/
                    if (!isset($_SESSION[$cCode]['items']) || !array_key_exists($pid, $_SESSION[$cCode]['items'])) {
                        $tmp = array(
                            "handler" => $this->modul . "/" . $this->uri->segment(2),
                            "id" => $pid,
                            "jml" => $tmpJml,
                            "harga" => 0,
                            "subtotal" => 0,
                            "satuan" => strlen($rows->satuan) > 0 ? $rows->satuan : "n/a",
                            "discount_persen" => isset($arrDiscount[$pid]['persen']) ? $arrDiscount[$pid]['persen'] : 0,
                            "discount_qty" => isset($arrDiscount[$pid]['qty']) ? $arrDiscount[$pid]['qty'] : 0,
                            "harga_jasa" => 0,
                        );

                        if (sizeof($priceMainConfig) > 0) {
                            if (isset($priceMainConfig[$_SESSION[$cCode]['main']['pihakMainName']])) {
                                $priceConfig = $priceMainConfig[$_SESSION[$cCode]['main']['pihakMainName']];
                                cekUngu("masuk disini...");
                            }
                        }

                        //                    cekBiru(__LINE__ . " sebelum price");
                        if (sizeof($priceConfig) > 0) {
                            //                        cekHijau("mmasuk price @" . __LINE__);
                            $mdlName = $priceConfig['model'];
                            $this->load->model("Mdls/" . $mdlName);
                            $h = new $mdlName();
                            if (isset($resetFilter['resetFilter']) && $resetFilter['resetFilter'] == true) {
                                $h->addFilter("produk_id='$pid'");
                                //                            $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                                $h->addFilter("cabang_id=" . CB_ID_PUSAT);
                            }
                            else {
                                $h->addFilter("produk_id='$pid'");
                                $h->addFilter("status='1'");
                                $h->addFilter("jenis_value in ('" . implode("','", $priceConfig['label']) . "')");
                                //                            $h->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                                $h->addFilter("cabang_id=" . CB_ID_PUSAT);
                            }

                            if (sizeof($priceFilter) > 0) {
                                foreach ($priceFilter as $f) {
                                    $f_ex = explode("=", $f);
                                    if (!isset($f_ex[1])) {
                                        $f_ey = explode(">", $f_ex[0]);
                                        if (substr($f_ey[1], 0, 1) == ".") {
                                            $h->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
                                        }
                                        else {
                                            if (isset($_SESSION[$cCode]['main'][$f_ey[1]])) {
                                                $h->addFilter($f_ey[0] . ">'" . $_SESSION[$cCode]['main'][$f_ey[1]] . "'");
                                            }
                                            else {
                                                $h->addFilter($f_ey[0] . ">0");
                                            }
                                        }
                                    }
                                    else {
                                        if (substr($f_ex[1], 0, 1) == ".") {
                                            $h->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
                                        }
                                        else {
                                            if (isset($_SESSION[$cCode]['main'][$f_ex[1]])) {
                                                $h->addFilter($f_ex[0] . "='" . $_SESSION[$cCode]['main'][$f_ex[1]] . "'");
                                            }
                                            else {
                                                $h->addFilter($f_ex[0] . "=''");
                                            }

                                        }
                                    }
                                }
                            }
                            $tmpH = $h->lookupAll($pid)->result();
                            showLast_query("kuning");
//                                               arrPrint($tmpH);
// matiHere(__LINE__);
                            if (sizeof($tmpH) > 0) {
                                $rawPrices = array();
                                foreach ($tmpH as $hSpec) {
                                    foreach ($priceConfig['key_label'] as $key => $val) {
                                        //                                    cekHitam($key);
                                        if ($resetFilter['resetFilter']) {
                                            //                                        cekBiru("sino$key ||" . $hSpec->$key);
                                            //                                        if ($key == $hSpec->h) {
                                            //                                            cekLime($hSpec->$key);
                                            $rawPrices[$key] = isset($hSpec->$key) ? $hSpec->$key : 0;
                                            //                                        }
                                        }
                                        else {
                                            //                                        cekBiru("sini " . __LINE__);
                                            if ($key == $hSpec->jenis_value) {
                                                $rawPrices[$key] = isset($hSpec->nilai) ? $hSpec->nilai : 0;
                                            }
                                        }

                                    }

                                }
                                //                            arrPrintKuning($rawPrices);
                                $prices = normalizePrices("produk", $rawPrices);
//                                arrPrint($prices);
                                if (sizeof($prices) > 0) {
                                    foreach ($prices as $k => $v) {
                                        $tmp[$k] = $v;
                                    }
                                    $tmp['harga'] = isset($tmp[$priceConfig['mainSrc']]) ? $tmp[$priceConfig['mainSrc']] : 0;
                                    $tmp['harga_reguler'] = isset($tmp[$priceConfig['mainSrc']]) ? $tmp[$priceConfig['mainSrc']] : 0;
                                    if ($tmp['harga'] == 0) {
                                        $this->stopWithHargaBelumTersedia(
                                            $pid,
                                            $produk_label_2,
                                            isset($item_jenis) ? $item_jenis : ""
                                        );
                                    }
//                                arrprint($tmp);
                                    // arrPrintKuning($rawPrices);
                                    // arrPrintPink($tmp);
                                    // matiDisini(__LINE__);
                                }
                                else {
                                    $this->stopWithHargaBelumTersedia(
                                        $pid,
                                        $produk_label_2,
                                        isset($item_jenis) ? $item_jenis : ""
                                    );
                                }
                            }
                            else {
                                $this->stopWithHargaBelumTersedia(
                                    $pid,
                                    $produk_label_2,
                                    isset($item_jenis) ? $item_jenis : ""
                                );
                            }
                        }
                        // arrPrintHijau($tmpH);
                        // arrPrintPink($tmp);
                        //                     matiHere(__LINE__);
                        //------------------------------------------------------
                        foreach ($fieldSrcs as $key => $src) {
                            if (is_array($src) && sizeof($src) > 0) {
                                foreach ($src as $srcSpec) {
                                    if (isset($tmp[$srcSpec]) || isset($rows->$srcSpec)) {
                                        cekBiru("ambil gerbang key -> $srcSpec");
                                        $tmp[$key] = makeValue($srcSpec, $tmp, $tmp, isset($rows->$srcSpec) ? $rows->$srcSpec : 0);
                                    }
                                }
                            }
                            else {
                                $tmp[$key] = makeValue($src, $tmp, $tmp, isset($rows->$src) ? $rows->$src : 0);
                                //                            cekHere("hasilnya $key -> " . $tmp[$key]);
                            }
                        }

                        if (sizeof($itemNumLabels) > 0) {

                            foreach ($itemNumLabels as $key => $label) {
                                if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                    $newValue = $_GET[$key];
                                    $tmp[$key] = $newValue;
                                    //                    $_SESSION[$cCode]['items'][$pid][$key] = $newValue;
                                    $tmp[$key] = $newValue;

                                }
                            }
                        }
                        $diskon_nilai = $tmp["harga"] - $pidData["harga"];
                        $tmp["_diskon_nilai"]=$diskon_nilai;
                        $tmp["disc_percent"]= ($tmp["_diskon_nilai"]/$tmp["harga"]) *100;
//                    arrPrint($tmp);
//                    matiHere(__LINE__);

                        if ($subAmountConfig != null) {
                            $tmp['subtotal'] = makeValue($subAmountConfig, $tmp, $tmp, 0);
                        }
                        else {
                            $tmp['subtotal'] = 0;
                        }

                        // arrPrintPink($tmp);
//                         matiHere(__LINE__);
                        $_SESSION[$cCode]['items'][$pid] = $tmp;

                    }
                    else {
                        cekBiru("ada id $pid  ada cCode items $cCode");

                        /* ---------------------------------------------
                         * penambahan pilihan harga manual
                         * ---------------------------------------------*/
                        $harga_pilihan = isset($_GET['harga']) ? $_GET['harga'] : false;
                        if ($harga_pilihan != false) {
                            if (isset($_SESSION[$cCode]['items'][$pid]['jual'])) {
                                // cekOrange("harga diganti");
                                $_SESSION[$cCode]['items'][$pid]['jual'] = $harga_pilihan;

                            }

                            if (isset($_SESSION[$cCode]['items'][$pid]) && isset($_GET['rowid'])) {
                                if ($_SESSION[$cCode]['items'][$pid]['id'] == $pid) {
                                    $_SESSION[$cCode]['items'][$pid]['row_harga_id'] = $_GET['rowid'];
                                }
                            }

                            if (isset($_GET['rowid'])) {

                                $_SESSION[$cCode]['harga_dipilih'][$pid]['rowid'] = $_GET['rowid'];
                                $_SESSION[$cCode]['harga_dipilih'][$pid]['harga'] = $harga_pilihan;
                            }
                        }
                        //---end pilihan harga manual----------------------------------------------------

                        // cekBiru("harga_pilihan: $harga_pilihan");
                        // cekBiru("after price  $id");
                        // arrPrint($_SESSION[$cCode]['items']);
                        // arrPrint($_SESSION[$cCode]['harga_dipilih']);
                        // arrPrintBlue($_SESSION[$cCode]['items'], __LINE__);
                        if (isset($_GET['ppn'])) {
                            $_SESSION[$cCode]['items'][$pid]['ppnFactor_item'] = $_GET['ppn'];
                        }

                        if (isset($_GET['newQty'])) {
                            $_SESSION[$cCode]['items'][$pid]['jml'] = $_GET['newQty'];
                            $_SESSION[$cCode]['items'][$pid]['subtotal'] = ($_SESSION[$cCode]['items'][$pid]['jml'] * ($_SESSION[$cCode]['items'][$pid]['harga'] + $_SESSION[$cCode]['items'][$pid]['ppn']));
                        }
                        else {
                            $_SESSION[$cCode]['items'][$pid]['jml'] += $jml;
                            $_SESSION[$cCode]['items'][$pid]['subtotal'] = ($_SESSION[$cCode]['items'][$pid]['jml'] * ($_SESSION[$cCode]['items'][$pid]['harga'] + (isset($_SESSION[$cCode]['items'][$pid]['ppn']) ? $_SESSION[$cCode]['items'][$pid]['ppn'] : 0)));
                        }


                        if (isset($arrDiscount[$pid]) && sizeof($arrDiscount[$pid]) > 0) {
                            foreach ($arrDiscount[$pid] as $dKey => $dVal) {
                                if (!isset($_SESSION[$cCode]['items'][$pid]['discount_' . $dKey])) {
                                    $_SESSION[$cCode]['items'][$pid]['discount_' . $dKey] = 0;
                                }
                                $_SESSION[$cCode]['items'][$pid]['discount_' . $dKey] = $dVal;
                            }
                            // matiHEre();
                        }

                        // arrPrint($itemNumLabels);
//                    matiHEre();
                        if (sizeof($itemNumLabels) > 0) {

                            foreach ($itemNumLabels as $key => $label) {
                                if (isset($_GET[$key]) && strlen($_GET[$key]) > 0) {
                                    if ($key == "disc") {
                                        // matiHEre();
                                        $newValue = pembulatanDiskon($_GET[$key]);
                                    }
                                    else {
                                        $newValue = $_GET[$key];
                                    }

                                    $tmp[$key] = $newValue;
                                    $_SESSION[$cCode]['items'][$pid][$key] = $newValue;

                                }

                            }


                            if ($subAmountConfig != null) {
                                $tmp['subtotal'] = makeValue($subAmountConfig, $_SESSION[$cCode]['items'][$pid], $_SESSION[$cCode]['items'][$pid], 0);
                            }
                            else {
                                $tmp['subtotal'] = 0;
                            }
                            $_SESSION[$cCode]['items'][$pid]['subtotal'] = $tmp['subtotal'];
                        }


                    }

                    /* ----------------------------------------------------------
                      * diskon-diskonan-grosir
                      * ----------------------------------------------------------*/
                    $tmp = $_SESSION[$cCode]['items'][$pid];
                    $sesmain = $_SESSION[$cCode]["main"];
                    $pihak_kategori = $sesmain['kategoriNama'];
                    $pro_diskon = $rows->diskon_persen;
                    $pro_premi = $rows->premi_jual;
                    // cekHitam($pro_premi . " uhui");
                    $pro_harga = $tmp['jual'] + (($pro_premi / 100) * $tmp['jual']);
                    $pro_harga_reseller = $tmp['jual_reseller'] + (($pro_premi / 100) * $tmp['jual_reseller']);
                    // cekBiru("pro_harga_reseller: $pro_harga_reseller");
                    // cekBiru("masuk ke diskon2an:: $pro_premi || $pro_harga ori:".$tmp['jual']." @" . __LINE__);
                    // cekKuning("$pihak_kategori");
                    cekKuning("$pro_harga");
                    $pro_jml = $tmp['jml'];

                    if ($pihak_kategori == "distributor") {
                        $calc_hasils = $ld->selectorDiskon($pid, $pro_harga_reseller, $pro_jml, $rows, $sesmain);
                        arrPrintHijau($calc_hasils);
                        $calc_hasil = $calc_hasils["grosir"];
                    }
                    else {
                        $calc_hasils = $ld->selectorDiskon($pid, $pro_harga, $pro_jml, $rows);
                        $calc_hasil = $calc_hasils["simple"];
                    }

                    //                arrPrintKuning($calc_hasil);
                    //                 cekHijau($gudang_status_id);
                    $stok_booking = isset($lsb_datas[$pid][$gudang_status_id]) ? $lsb_datas[$pid][$gudang_status_id]['sum_valid_qty'] : "0";
                    // arrPrintPink($stok_booking);
                    // matiHere(__LINE__);
                    $tmp['stok_booking'] = $stok_booking;
                    $tmp['ref_order'] = "crm";
                    $tmp['ref_order_estimate_id'] = $estimate_id;
                    // $tmp['stok_booking_center'] = 99;
                    $tmp['discPersen'] = $calc_hasil['persen'];
                    $tmp['lastNett'] = $calc_hasil['harga_af'];
                    // $tmp['harga'] = $pihak_kategori == "reguler" ? $pro_harga : $calc_hasil['harga_af'];
                    /* ---------------------------------------------------------------------------
                     * kategori ada 3: reguler distributor online
                     * yg mendapat diskon berjenang hanya distributor
                     * jika ada premi semua diskon off
                     * ---------------------------------*/
                    $yg_dipakai = 2;
                    if ($yg_dipakai == 1) {
                        if ($pro_premi > 0) {
                            $harga_yg_dipakai = $pro_harga;
                        }
                        elseif ($pro_premi == 0) {
                            $harga_yg_dipakai = $calc_hasil['harga_af'];
                        }
                        else {
                            if ($pihak_kategori == "distributor") {
                                $harga_yg_dipakai = $calc_hasil['harga_af'];
                            }
                            else {
                                $harga_yg_dipakai = $pro_harga;
                            }
                        }
                    }
                    elseif ($yg_dipakai == 2) {
                        if ($pihak_kategori == "distributor") {
                            if ($pro_premi > 0) {
                                $harga_yg_dipakai = $pro_harga;
                                $jual_dipakai = $pro_harga;
                            }
                            else {
                                $harga_yg_dipakai = $calc_hasil['harga_af'];
                                $jual_dipakai = $pro_harga_reseller;
                            }
                        }
                        else {
                            if ($pro_premi > 0) {
                                $harga_yg_dipakai = $pro_harga;
                                $jual_dipakai = $pro_harga;
                            }
                            else {
                                $harga_yg_dipakai = $calc_hasil['harga_af'];
                                $jual_dipakai = $pro_harga;
                            }
                        }
                    }

//                matiHEre(__LINE__);
                    $tmp['jual_dipakai'] = $jual_dipakai;
                    $tmp['harga'] = $harga_yg_dipakai;
                    // ------------------------------------------------------end--------------------
                    $tmp['harga_jual'] = $calc_hasil['harga_be'] * $tmp['satuan_factor_qty'];
                    $tmp['harga_disc'] = ($calc_hasil['harga_af'] * $tmp['satuan_factor_qty']) * $tmp['qty_unit'];

                    $tmp['discNilai'] = $calc_hasil['nilai'] * $tmp['satuan_factor_qty'];
                    $tmp['discNilai'] = $calc_hasil['nilai'] * $tmp['satuan_factor_qty'];
                    $tmp['id'] = $pid;

                    $tmp['subtotal'] = $calc_hasil['harga_af'] * $pro_jml;
                    // -------------------------------------------------------------------
                    // $produk_jenis["jml"] = $pro_jml;
                    // memasukkan kolom sku ke items2
                    // handle serial 1 dan scan mode
                    $jml_serial = $rows->jml_serial;
                    $tmp['jml_serial'] = $jml_serial;
                    $tmp['scan_mode'] = $jml_serial > 0 ? "serial" : "simple";
                    if ($jml_serial * 1 == 1) {
                        $d_kode = $rows->kode;
                        $_SESSION[$cCode]['items2'][$produk_id][$d_kode] = array();
                    }
                    // matiHere("====|scan_mode:".$tmp['scan_mode']."|====$cCode====|serial:".$tmp['jml_serial']."|====");

                    $arrCat = array();
                    $arrCode = array();
                    if ($produk_jenis == "unit") {
                        foreach ($arrDataTambahan as $cat => $catSpec) {
                            foreach ($catSpec as $dkey => $dval) {
                                if (isset($rows->$dval) && ($rows->$dval != NULL)) {
                                    $_SESSION[$cCode]['items2'][$produk_id][$rows->$dval] = array();
                                    //--------------
                                    if (!isset($arrCat[$cat])) {
                                        $arrCat[$cat] = 0;
                                    }
                                    $arrCat[$cat] += 1;
                                    //--------------
                                    if (!isset($arrCode[$rows->$dval])) {
                                        $arrCode[$rows->$dval] = 0;
                                    }
                                    $arrCode[$rows->$dval] += 1;
                                    //--------------
                                }
                            }
                        }
                    }
                    else {
                        $_SESSION[$cCode]['items2'][$produk_id][$rows->kode] = array();
                        $arrCat["barcode"] = 1;
                        $arrCode[$rows->kode] = 1;
                    }
                    $keterangan = "";
                    $static_keterangan = "";
                    if (sizeof($arrCat) > 0) {
                        foreach ($arrCat as $kcat => $vcat) {
                            $new_vcat = $vcat * $_SESSION[$cCode]['items'][$pid]["jml"];
                            if ($keterangan == "") {
                                $keterangan = " $new_vcat $kcat";
                            }
                            else {
                                $keterangan .= "<br> $new_vcat $kcat";
                            }
                            if ($static_keterangan == "") {
                                $static_keterangan = " $vcat $kcat";
                            }
                            else {
                                $static_keterangan .= "<br> $vcat $kcat";
                            }
                            $new_keyy = "qty_" . $kcat;
                            $tmp[$new_keyy] = $vcat;
                        }
                    }
                    if (sizeof($arrCode) > 0) {
                        foreach ($arrCode as $kcat => $vcat) {
                            $new_vcat = $vcat * $_SESSION[$cCode]['items'][$pid]["jml"];
                            $tmp[$kcat] = $new_vcat;
                        }
                    }
                    $tmp['keterangan'] = $keterangan;
                    $tmp['static_keterangan'] = $static_keterangan;
                    //----------------------------------------
                    $_SESSION[$cCode]['items'][$produk_id] = $tmp;
                    $pakai_ini = 1;
                    if ($pakai_ini == 1) {
//                        if ($item_jenis == "item_komposit") {
                        if ($item_jenis == "paket") {
                            $_SESSION[$cCode]['items6'][$produk_id] = array();
                            $_SESSION[$cCode]['items7'][$produk_id] = array();
                            $this->load->model("Mdls/MdlProduk2");
                            $this->load->model("Mdls/MdlProdukKompositKomposisi");
                            $pp = new MdlProduk2();
                            $kk = new MdlProdukKompositKomposisi();
                            $kk->addFilter("produk_id=$pid'");
                            $tmpKomposit = $kk->lookUpAll()->result();

                            if (count($tmpKomposit) > 0) {
                                $qty_faktor = isset($_GET['newQty']) ? $_GET['newQty'] : 1;
                                $idProduk_komposit = array();
                                $priceKomposit = array();
                                $items8 = array();
                                foreach ($tmpKomposit as $tmpKomposit_0) {
                                    $idProduk_komposit[] = $tmpKomposit_0->produk_dasar_id;
                                    $priceKomposit[$tmpKomposit_0->produk_dasar_id] = array(
                                        "harga" => $tmpKomposit_0->harga / $ppnFactorInclude,
                                        "jml" => $tmpKomposit_0->jml * $qty_faktor,
                                    );
                                    $items8[$tmpKomposit_0->produk_dasar_id] = array(
                                        "id" => $tmpKomposit_0->id,
                                        "produk_id" => $tmpKomposit_0->produk_id,
                                        "produk_nama" => $tmpKomposit_0->produk_nama,
                                        "produk_dasar_id" => $tmpKomposit_0->produk_dasar_id,
                                        "produk_dasar_nama" => $tmpKomposit_0->produk_dasar_nama,
                                        "jml" => $tmpKomposit_0->jml,
                                        "qty" => $tmpKomposit_0->jml,
                                        "harga" => $tmpKomposit_0->harga / $ppnFactorInclude,
                                        "harga_nppn" => $tmpKomposit_0->harga,
                                    );
                                    $_SESSION[$cCode]["items_komposisi"][$tmpKomposit_0->produk_id] = $items8;
                                }
                                $pp->addFilter("id in ('" . implode("','", $idProduk_komposit) . "')");
                                $tmpDataProdukKomposisi = $pp->lookUpAll()->result();
                                foreach ($tmpDataProdukKomposisi as $tmpProdukKomposisiPaket) {
                                    $produk_jenis_paket = $tmpProdukKomposisiPaket->kategori_nama;
                                    $tmpPaket = array(
                                        "id" => $tmpProdukKomposisiPaket->id,
                                        "jml" => $priceKomposit[$tmpProdukKomposisiPaket->id]["jml"],
                                        "qty" => $priceKomposit[$tmpProdukKomposisiPaket->id]["jml"],
                                        "harga" => $priceKomposit[$tmpProdukKomposisiPaket->id]["harga"],
                                        "subtotal" => $priceKomposit[$tmpProdukKomposisiPaket->id]["jml"] * $priceKomposit[$tmpProdukKomposisiPaket->id]["harga"],
                                        "satuan" => strlen($tmpProdukKomposisiPaket->satuan) > 0 ? $tmpProdukKomposisiPaket->satuan : "n/a",
                                        "harga_jasa" => 0,
                                    );
                                    foreach ($fieldSrcs as $key => $src) {
                                        if (is_array($src) && sizeof($src) > 0) {
                                            foreach ($src as $srcSpec) {
                                                if (isset($tmpPaket[$srcSpec]) || isset($tmpProdukKomposisiPaket->$srcSpec)) {
                                                    cekBiru("ambil gerbang key -> $srcSpec");
                                                    $tmpPaket[$key] = makeValue($srcSpec, $tmpPaket, $tmpPaket, isset($tmpProdukKomposisiPaket->$srcSpec) ? $tmpProdukKomposisiPaket->$srcSpec : 0);
                                                }
                                            }
                                        }
                                        else {
                                            $tmpPaket[$key] = makeValue($src, $tmpPaket, $tmpPaket, isset($tmpProdukKomposisiPaket->$src) ? $tmpProdukKomposisiPaket->$src : 0);
                                            //                            cekHere("hasilnya $key -> " . $tmp[$key]);
                                        }

                                    }
                                    $jml_serial_paket = $tmpProdukKomposisiPaket->jml_serial;
                                    $tmpPaket['jml_serial'] = $jml_serial_paket;
                                    $tmpPaket['scan_mode'] = $jml_serial_paket > 0 ? "serial" : "simple";
                                    if ($jml_serial_paket * 1 == 1) {
                                        $d_kode = $tmpProdukKomposisiPaket->kode;
                                        $_SESSION[$cCode]['items7'][$produk_id][$tmpProdukKomposisiPaket->id][$d_kode] = array();
                                    }
                                    // matiHere("====|scan_mode:".$tmp['scan_mode']."|====$cCode====|serial:".$tmp['jml_serial']."|====");

                                    $arrCat = array();
                                    $arrCode = array();
                                    if ($produk_jenis_paket == "unit") {
                                        foreach ($arrDataTambahan as $cat => $catSpec) {
                                            foreach ($catSpec as $dkey => $dval) {
                                                if (isset($tmpProdukKomposisiPaket->$dval) && ($tmpProdukKomposisiPaket->$dval != NULL)) {
                                                    $_SESSION[$cCode]['items7'][$produk_id][$tmpProdukKomposisiPaket->id][$tmpProdukKomposisiPaket->$dval] = array();
                                                    //--------------
                                                    if (!isset($arrCat[$cat])) {
                                                        $arrCat[$cat] = 0;
                                                    }
                                                    $arrCat[$cat] += 1;
                                                    //--------------
                                                    if (!isset($arrCode[$tmpProdukKomposisiPaket->$dval])) {
                                                        $arrCode[$tmpProdukKomposisiPaket->$dval] = 0;
                                                    }
                                                    $arrCode[$tmpProdukKomposisiPaket->$dval] += 1;
                                                    //--------------
                                                }
                                            }
                                        }
                                    }
                                    else {
                                        $_SESSION[$cCode]['items7'][$produk_id][$tmpProdukKomposisiPaket->id][$tmpProdukKomposisiPaket->kode] = array();
                                        $arrCat["barcode"] = 1;
                                        $arrCode[$tmpProdukKomposisiPaket->kode] = 1;
                                    }
                                    $keterangan = "";
                                    $static_keterangan = "";
                                    if (sizeof($arrCat) > 0) {
                                        foreach ($arrCat as $kcat => $vcat) {
                                            $new_vcat = $vcat * $priceKomposit[$tmpProdukKomposisiPaket->id]["jml"];
                                            if ($keterangan == "") {
                                                $keterangan = " $new_vcat $kcat";
                                            }
                                            else {
                                                $keterangan .= "<br> $new_vcat $kcat";
                                            }
                                            if ($static_keterangan == "") {
                                                $static_keterangan = " $vcat $kcat";
                                            }
                                            else {
                                                $static_keterangan .= "<br> $vcat $kcat";
                                            }
                                            $new_keyy = "qty_" . $kcat;
                                            $tmpPaket[$new_keyy] = $vcat;
                                        }
                                    }
                                    if (sizeof($arrCode) > 0) {
                                        foreach ($arrCode as $kcat => $vcat) {
                                            $new_vcat = $vcat * $priceKomposit[$tmpProdukKomposisiPaket->id]["jml"];
                                            $tmpPaket[$kcat] = $new_vcat;
                                        }
                                    }
                                    $tmpPaket['keterangan'] = $keterangan;
                                    $tmpPaket['static_keterangan'] = $static_keterangan;
                                    $tmpPaket['produk_paket_id'] = $produk_id;
                                    $tmpPaket['produk_paket_nama'] = $row->nama;

                                    $_SESSION[$cCode]["items6"][$produk_id][$tmpProdukKomposisiPaket->id] = $tmpPaket;


                                }
                            }
                            else {
//                                matiHere("produk paket belum memiliki komposisi !. Silahkan perbaiki data dari menu data produk penjualan paket");
                            }

                            //                    arrprint($tmpKomposit);
                        }
                    }


                }

                /* -----------------------------------------------------------
                * diskon unit/non unit
                * -----------------------------------------------------------*/
                unset($_SESSION[$cCode]['items_kategori']);
                foreach ($_SESSION[$cCode]['items'] as $item) {
                    $kategori_produk = $item["kategori_nama"];
                    $_SESSION[$cCode]["main"]["jml_kategori_$kategori_produk"] = 0;
                }
                foreach ($_SESSION[$cCode]['items'] as $item) {
                    $pro_jml = $item['jml'];
                    $produk_jenis = str_replace(" ", "_", $item['kategori_nama']);

                    if (!isset($_SESSION[$cCode]['items_kategori'][$produk_jenis]['jml'])) {
                        $_SESSION[$cCode]['items_kategori'][$produk_jenis]['jml'] = 0;
                    }
                    $_SESSION[$cCode]['items_kategori'][$produk_jenis]['jml'] += $pro_jml;
                    $kategori_produk = $item["kategori_nama"];
                    if (!isset($_SESSION[$cCode]["main"]["jml_kategori_$kategori_produk"])) {
                        $_SESSION[$cCode]["main"]["jml_kategori_$kategori_produk"] = 0;
                    }
                    $_SESSION[$cCode]["main"]["jml_kategori_$kategori_produk"] += $pro_jml;

                }
                // arrPrint($_SESSION[$cCode]["main"]);
                // ---------------------------------------------------------

                $potongan_nilai = $ld->selectorDiskonKategori($_SESSION[$cCode]);

                arrPrintPink($potongan_nilai);
                // arrPrintKuning($produk_jenis);
                cekHijau("membuat session main");
                if (ipadd() == "202.65.117.72") {
                    //                mati_disini(__LINE__);
                }
                $rows2 = array();
                $pro_premi2 = 0;
                foreach ($potongan_nilai as $dcu_kategori => $dcu) {
                    $nilai_dcu = $dcu['nilai'];
                    $_SESSION[$cCode]["main"]["diskon_kategori_$dcu_kategori"] = $nilai_dcu;
                    $_SESSION[$cCode]["main"]["jml_kategori_$dcu_kategori"] = $dcu['jml'];

                    // if ($nilai_dcu > 0) {
                    $sesmain2 = $_SESSION[$cCode]["main"];
                    $pihak_kategori2 = $sesmain2["kategoriNama"];
                    cekHere("update harga yg dipakai");
                    foreach ($_SESSION[$cCode]['items'] as $pro_id => $item_speks) {
                        /* --------------------------------------------------
                         * pilih yg sebagai dasar mau harga list atau harga distributor/reseller
                         * ---------------------------------------------------*/
                        $pro_harga2 = $item_speks['jual'] + (($pro_premi2 / 100) * $item_speks['jual']);
                        $pro_harga_reseller2 = $item_speks['jual_reseller'] + (($pro_premi2 / 100) * $item_speks['jual_reseller']);
                        // ------------------------------------------------------------------------
                        $pro_jml2 = $item_speks['jml'];

                        if ($nilai_dcu > 0) {
                            if (!isset($item_speks['jual_reseller'])) {
                                $pro_harga_dipakai = $pro_harga2;
                            }
                            else {
                                $pro_harga_dipakai = $pro_harga_reseller2;
                            }
                            $calc_hasils = $ld->selectorDiskon($pro_id, $pro_harga_dipakai, $pro_jml2, $rows2, $sesmain2);
                            //                        $calc_hasils = $ld->selectorDiskon($pro_id, $pro_harga_reseller2, $pro_jml2, $rows2, $sesmain2);
                            $calc_hasil = $calc_hasils["grosir"];
                        }
                        else {
                            //     // if ($pihak_kategori2 == "distributor") {
                            cekOrange("harusnya tidak diskon " . __LINE__);
                            // $calc_hasils = $ld->selectorDiskon($pro_id, $pro_harga2, $pro_jml2, $rows2, $sesmain2);
                            // $calc_hasil = $calc_hasils["grosir"];
                            $calc_hasil = array(
                                "type" => "diskon",
                                "persen" => "0",
                                "nilai" => "0",
                                "harga_be" => $pro_harga2,
                                "harga_af" => $pro_harga2,
                            );
                            arrPrintHijau($calc_hasils);
                        }

                        cekHitam("$pro_id ---------");
                        arrPrintWebs($calc_hasils);

                        $tmp2['discPersen'] = $calc_hasil['persen'];
                        $tmp2['lastNett'] = $calc_hasil['harga_af'];
                        $tmp2['jual_dipakai'] = $pro_harga_reseller2;
                        $tmp2['harga'] = $calc_hasil['harga_af'];

                        arrPrintKuning($tmp2);

                        /* ----------------------------------------------------------------------
                         * ngupdate session items pada key2 tertentu saja spt yg didefine diatasnya
                         * ----------------------------------------------------------------------*/
                        foreach ($tmp2 as $sesKey => $newSesValue) {
                            $_SESSION[$cCode]['items'][$pro_id][$sesKey] = $newSesValue;
                        }
                    }

                    // }
                }
                // --------------------------en kategori diskon----------------------

                /* -----------------------------------------------------------------
                 * ngupdate harga yg dipakai per item
                 * -----------------------------------------------------------------*/
                // if(isset($potongan_nilai) && (count($potongan_nilai) > 0) && ($potongan_nilai['nilai'] > 0)){
                //     cekHere("update harga yg dipakai");
                //     foreach ($_SESSION[$cCode]['items'] as $item){
                //
                //     }
                // }


            }
            else {
                //close holdon produk belum sinkron

                matiHere("Silahkan Sinkron Produk terlebih dahulu sebelum melanjutkan, untuk memperbaharui data.");
                cekMerah("tidak ada itemnya! @" . __LINE__ . " " . __METHOD__);
                die();
            }
            $f_selector = "";
            if (isset($_GET['selector'])) {
                $f_selector = "selector&";
            }
        }

        //endregion
        $dtime_now = dtimeNow();
        $dtime_now_ex = explode(" ", $dtime_now);
        $date_now = str_replace("-", "", $dtime_now_ex[0]);
        $time_now = str_replace(":", "", $dtime_now_ex[1]);
        $bookingNumber = "$date_now" . "$time_now";


        if (!isset($_SESSION[$cCode]["main"]["bookingNumber"]) || ($_SESSION[$cCode]["main"]["bookingNumber"] == null)) {
            $_SESSION[$cCode]["main"]["bookingNumber"] = $bookingNumber;
        }
        //-----------------------------------------------------
        //        arrprintwebs($_SESSION[$cCode]["items6"]);
        //        matiHere();
        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setppnFactor($ppnFactor);


        $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, $stepNum, $this->configUiJenis);

        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);
        //        arrprint($_SESSION[$cCode]["items6"]);
        //        matiHere();
//         matiHere(__METHOD__ . __LINE__);
        /* --------------------------------------------------
         * ngereload shoping cart dlm modul
         * --------------------------------------------------*/
if($cek_source_index =="redirect"){
    $link = MODUL_PATH . "Create/index/" . $this->jenisTr . "/?gr=cGVuanVhbGFu";
    echo "<script>";
//    echo " top.$('#shopping_cart').load('" . MODUL_PATH . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id', function() {";
    echo "     $('.bootstrap-dialog-close-button .close').click();";
    echo "     close_holdon();";
    echo "     window.top.location.href = '" . $link . "';";
//    echo " });";
    echo "</script>";
}
else{
        echo "<script>";
        echo " top.$('#shopping_cart').load('" . MODUL_PATH . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "$('.bootstrap-dialog-close-button .close').click();";
        echo "window.top.location.reload();";
        echo "close_holdon();";
        echo "</script>";
    }

}

}