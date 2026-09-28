* menulis cache dan mutasi pembelian per transaksi id untuk persapan jika ada erp nya, hanya main
*extern_id->vendor/supplier/customer
*produk_id ->transaksi_id
*rekening ->stepcode
ComRekeningPembantuTransaksiPembelian//

* menulis cache dan mutasi detil pembelian
ComRekeningPembantuTransaksiDataPembelian//

* menulis cache dan mutasi detil pembelian per cabang ,stepcode,produk_id summary/saldo per produk id dalam periode(harian,bulanan,yahun,forever).
* tujuan supaya tidak perlu hitung hitung saat diambil total pembelian  dari produk a ->tinggal ambil saldonya.
ComRekeningTransaksiDataPembelianCache

* update qty_det/kredit pembelian_transaksi_data 
* hanya boleh update tidak boleh insert(insert dilakukan oleh model MdlPembelianTransaksi)
ComTranskasiDataPembelian 


4 com ini wajib jalan bareng, tidak boleh ditinggal satu pun saat menulis transaksi



