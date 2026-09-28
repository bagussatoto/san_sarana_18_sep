<?php

/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 07/08/2018
 * Time: 21.34
 */
class MdlSetting extends MdlMother
{
    protected $tableName = "settings";

    public function __construct()
    {
        parent::__construct();
    }

    public function settingInv(){

    }

    public function callInvoicing(){
        $condites = array(
            "jenis" => "onoff",
            "untuk" => "history_invoice",
        );
        $existingData  = $this->lookupByCondition($condites)->row();
        showLast_query("hijau");

        return $existingData;
    }

    public function updateInvoicing(){

        $existingData =  $this->callInvoicing();

        if($existingData){
            $nilai = $existingData->nilai;
            $nilai_new = $nilai == 0 ? 1 : 0;

            $updCondites = array(
                "id" => $existingData->id,
            );
            $updDatas = array(
                "nilai" => $nilai_new,
                "oleh_nama" => my_name()
            );


            $this->updateData($updCondites,$updDatas);
            showLast_query("merah");
        }
        else{
            $condites = array(
                "jenis" => "onoff",
                "untuk" => "history_invoice",
            );
            $datas = $condites;
            $datas['nilai'] = 1;
            $datas['status'] = 1;
            $this->addData($datas);
            showLast_query("hijau");
        }

        return $nilai_new;
    }
    // edited by glg (22:05 WIB, 2025-12-23)
    // change: tambah setting global untuk e-signature pembelian
    // technical rationale: menyimpan flag on/off di tabel settings agar bisa dikontrol dari UI
    public function getEsignatureSetting()
    {
        return $this->getSettingOnoff("pembelian_esignature");
    }

    // edited by glg (22:05 WIB, 2025-12-23)
    // change: update nilai setting e-signature pembelian
    // technical rationale: set flag secara eksplisit (1/0) berdasarkan input UI
    public function updateEsignatureSetting($enabled)
    {
        return $this->updateSettingOnoff("pembelian_esignature", $enabled);
    }

    // edited by glg (22:30 WIB, 2025-12-23)
    // change: tambah setter/getter umum untuk setting on/off
    // technical rationale: dipakai ulang untuk toggle e-signature modul lain
    public function getSettingOnoff($untuk)
    {
        $condites = array(
            "jenis" => "onoff",
            "untuk" => $untuk,
        );

        return $this->lookupByCondition($condites)->row();
    }

    // edited by glg (22:30 WIB, 2025-12-23)
    // change: simpan nilai on/off untuk key setting tertentu
    // technical rationale: konsistensi update toggle di tabel settings
    public function updateSettingOnoff($untuk, $enabled)
    {
        $existingData = $this->getSettingOnoff($untuk);
        $nilai_new = ((int)$enabled === 1) ? 1 : 0;

        if ($existingData) {
            $updCondites = array(
                "id" => $existingData->id,
            );
            $updDatas = array(
                "nilai" => $nilai_new,
                "oleh_nama" => my_name(),
            );
            $this->updateData($updCondites, $updDatas);
        }
        else {
            $datas = array(
                "jenis" => "onoff",
                "untuk" => $untuk,
                "nilai" => $nilai_new,
                "status" => 1,
                "oleh_nama" => my_name(),
            );
            $this->addData($datas);
        }

        return $nilai_new;
    }

    // edited by glg (22:30 WIB, 2025-12-23)
    // change: tambah setting global untuk e-signature penjualan
    // technical rationale: kontrol on/off e-signature khusus modul penjualan
    public function getEsignatureSettingPenjualan()
    {
        return $this->getSettingOnoff("penjualan_esignature");
    }

    // edited by glg (22:30 WIB, 2025-12-23)
    // change: update nilai setting e-signature penjualan
    // technical rationale: set flag on/off berdasarkan input UI penjualan
    public function updateEsignatureSettingPenjualan($enabled)
    {
        return $this->updateSettingOnoff("penjualan_esignature", $enabled);
    }

    // edited by glg (11:32 WIB, 2025-12-23)
    // change: tambah setting global untuk e-signature distribusi
    // technical rationale: kontrol on/off e-signature khusus modul distribusi
    public function getEsignatureSettingDistribusi()
    {
        return $this->getSettingOnoff("distribusi_esignature");
    }

    // edited by glg (11:32 WIB, 2025-12-23)
    // change: update nilai setting e-signature distribusi
    // technical rationale: set flag on/off berdasarkan input UI distribusi
    public function updateEsignatureSettingDistribusi($enabled)
    {
        return $this->updateSettingOnoff("distribusi_esignature", $enabled);
    }

    // edited by glg (13:10 WIB, 2025-12-23)
    // change: tambah setting global untuk e-signature biaya
    // technical rationale: kontrol on/off e-signature khusus modul biaya
    public function getEsignatureSettingBiaya()
    {
        return $this->getSettingOnoff("biaya_esignature");
}
    // edited by glg (13:10 WIB, 2025-12-23)
    // change: update nilai setting e-signature biaya
    // technical rationale: set flag on/off berdasarkan input UI biaya
    public function updateEsignatureSettingBiaya($enabled)
    {
        return $this->updateSettingOnoff("biaya_esignature", $enabled);
    }
}