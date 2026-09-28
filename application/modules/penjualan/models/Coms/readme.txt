ComJurnalPenjualan
* menulis jurnal penjualan mainnya saja sebagai rekening utama , sebebagai angkor nya adalah rekening penjualan/return
ComRekeningPenjualan
* menulis cache dan penjualan mainnya saja sebagai rekening utama , sebebagai angkor nya adalah rekening penjualan/return
ComRekeningPembantuPenjualan
* menulis cache dan mutasi penjualan per transaksi id untuk persapan jika ada erp nya, hanya main
ComRekeningPembantuPenjualanKas
* menulis cache dan mutasi kas per transaksi_id untuk persiapan jika ada erp nya, hanya main
ComRekeningPembantuPenjualanPpn
* menulis cache dan mutasi ppn belum ada faktur per transaksi id untuk persapan jika ada erp nya, hanya main
ComRekeningPembantuTransaksiDataPenjualan
* menulis cache dan mutasi detil penjualan per transaksi id,produk_id untuk persapan jika ada erp nya.

ComRekeningTransaksiDataPenjualanCache
* menulis cache dan mutasi detil penjualan per cabang ,produk_id summary/saldo per produk id dalam periode.
* tujuan supaya tidak perlu hitung hitung saat diambil total penjualan jarian dari produk a ->tinggal ambil saldonya.



Empat com ini wajib jalan bareng, tidak boleh ditinggal satu pun saat menulis transaksi
ComRekeningPembantuTransaksiPenjualan
ComRekeningTransaksiDataPenjualan
ComRekeningTransaksiDataPenjualanCache
ComTransaksiDataPenjualan


