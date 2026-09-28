# Transkrip & Panduan UAT: Penjualan Sendiri / Domestik (Lokal)

## 1. Konsep & Prinsip Bisnis
Penjualan Domestik / Lokal adalah transaksi pengadaan dan penjualan mandiri di level cabang/unit tanpa intervensi dari rantai pasok Kantor Pusat (SAN Data Center).

### Perbandingan Alur: SAN vs Domestik (Lokal)
| Aspek | Alur Pengadaan SAN (Pusat) | Alur Domestik / Lokal (Mandiri) |
| :--- | :--- | :--- |
| **Sifat Alur** | Terhubung ke Data Center & terotomatisasi | Mandiri & dijalankan secara manual penuh |
| **Purchase Order (PO)** | Sistem memicu *Auto PO* ke SAN | Dibuat manual oleh cabang ke Vendor Lokal |
| **Penerimaan (GRN)** | Sistem memicu *Auto GRN* kiriman SAN | Dibuat manual oleh gudang cabang saat barang tiba |
| **Distribusi Stok** | Sistem memicu *Auto Distribusi* ke gudang | *Auto create request distribusi* internal cabang |
| **Prepacking** | Sistem memicu *Auto Prepacking* | Dijalankan dan dikonfirmasi di level cabang |
| **Hutang / Piutang** | Timbul mutasi/rekening antar-cabang (SAN) | Timbul hutang murni ke supplier pihak ketiga |

---

## 2. Analisis 5W + 1H

* **WHAT (Apa):**
  Pengujian User Acceptance Testing (UAT) untuk alur transaksi penjualan barang lokal secara mandiri. Memvalidasi bahwa seluruh siklus berjalan manual dan sistem **tidak** memicu background auto-process ke SAN.
* **WHY (Mengapa):**
  1. *Pemisahan Entitas*: Menghindari terbentuknya transaksi hutang/piutang antar-cabang (*intercompany*) ke SAN.
  2. *Integritas HPP*: Menjamin HPP barang lokal terbentuk murni dari faktur pembelian vendor lokal, bukan transfer price SAN.
  3. *Pencegahan Validasi Error*: Menghindari trigger `sellerDcGuard` / `sellerDcID` yang mensyaratkan relasi akun data center SAN.
* **WHO (Siapa):**
  - Purchasing Cabang: Input PO Lokal.
  - Gudang Cabang (Receiving): Input GRN Lokal.
  - Gudang Cabang (Inventory): Distribusi stok fisik di rak cabang.
  - Kasir / Sales Admin: Input transaksi penjualan di shopping cart (`Yanty`).
  - Gudang Cabang (Fulfillment): Eksekusi prepacking (`superadmin`).
  - Finance / Kasir: Penerimaan pembayaran / settlement A/R Receipt (`Yanty`).
* **WHERE (Di mana):**
  Berjalan 100% pada lingkungan Cabang JKT02 (`cabang_id: 31`) tanpa callout API ke SAN.
* **WHEN (Kapan):**
  Dieksekusi pada tanggal 18 September 2026.
* **HOW (Bagaimana Skenario Pengujian):**
  Eksekusi bertahap dari penerimaan stok lokal hingga pelunasan dan pembentukan jurnal penjualan lokal.

---

## 3. Hasil Rekonsiliasi & Status Skenario UAT Aktual

| Tahapan UAT | No. Bukti Transaksi | Waktu | Pelaksana | Keterangan & Status |
| :--- | :--- | :--- | :--- | :--- |
| **1 & 2. Pengadaan & GRN** | `RcS.31.10` | 12:30 | Logistik Cabang | Penerimaan stok lokal: 10 unit `NHT25` (HPP Rp 500k) & 10 unit `FP6SS-ABC40-MGK` (HPP Rp 1.320k). **(DONE)** |
| **3. Distribusi Stok** | `RcS.31.10` | 12:30 | Sistem Internal | *Auto create request distribusi* ke rak cabang JKT02 (`o=31`). **(DONE)** |
| **4. Sales Order (Cart)** | `5822SPO.31.115.1-00021` | 12:31 | Yanty (Sales Admin) | Customer: Wanda Dwiana Putri (115). Subtotal: Rp 3.633.182 + PPN: Rp 399.650 = Grand Total: Rp 4.032.832. **(DONE)** |
| **5. Prepacking** | `5822pkd.31.115.1` &rarr; `5822spd.31.115.1` | 12:41 - 12:42 | Superadmin | Pengiriman konsumen, stok masing-masing berkurang 1 unit (sisa 9 unit). Timbul Piutang Usaha Lokal Rp 4.032.832. **(DONE)** |
| **6. Settlement & Kas** | `RPC.31.115.1` (`749.31.115.1`) | 13:07 | Yanty (Kasir/Finance) | A/R Receipt pelunasan piutang Rp 4.032.832 (*auto settle*, Saldo Piutang = Rp 0). **(DONE)** |

---

## 4. Analisis Keuangan & Margin Transaksi UAT
* **Penjualan Bruto (DPP)**: Rp 3.633.182,00
* **PPN Keluaran (11%)**: Rp 399.650,00
* **Total Tagihan Konsumen**: Rp 4.032.832,00
* **Total HPP Produk Terjual**: Rp 1.820.000,00 *(Rp 500.000 + Rp 1.320.000)*
* **Laba Kotor (*Gross Profit*)**: **Rp 1.813.182,00** *(Margin Laba Kotor ~49.9%)*
* **Status Piutang Akhir**: **Rp 0,00 (LUNAS)**
