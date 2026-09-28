<?php

function formatNPWP($npwp) {
    // Hapus semua karakter selain angka
    $cleanNPWP = preg_replace('/\D/', '', $npwp);

    // Pastikan panjang NPWP valid (15 digit)
    $digidNPWP = 15;
    $digid = strlen($cleanNPWP);
    if ($digid !== $digidNPWP) {
        return "Format NPWP tidak valid <r>$cleanNPWP</r> (<b>$digid</b>/$digidNPWP digid)";
    }

    // Format NPWP menjadi 99.999.999.9-999.999
    $formattedNPWP = sprintf(
        "%s.%s.%s.%s-%s.%s",
        substr($cleanNPWP, 0, 2),  // 2 digit pertama
        substr($cleanNPWP, 2, 3), // 3 digit berikutnya
        substr($cleanNPWP, 5, 3), // 3 digit berikutnya
        substr($cleanNPWP, 8, 1), // 1 digit berikutnya
        substr($cleanNPWP, 9, 3), // 3 digit berikutnya
        substr($cleanNPWP, 12, 3) // 3 digit terakhir
    );

    return $formattedNPWP;
}

function formatNomorTelepon0($nomor) {
    // Hapus semua karakter selain angka
    $cleanNomor = preg_replace('/\D/', '', $nomor);

    // Validasi panjang nomor telepon (minimal 9 digit)
    $digid = strlen($cleanNomor);

    if ($digid < 9) {
        $peringatan = "tambahkan kode area";
        return "Format nomor tidak valid <r>$nomor</r> $peringatan";
    }

    // Jika nomor dimulai dengan 62 (kode internasional Indonesia)
    if (substr($cleanNomor, 0, 2) === '62') {
        $cleanNomor = '0' . substr($cleanNomor, 2); // Ubah menjadi format lokal
    }

    // Jika nomor dimulai dengan 0 (lokal), format sesuai
    if (substr($cleanNomor, 0, 1) === '0') {
        if (substr($cleanNomor, 1, 1) === '2') {
            // Nomor telepon rumah (021, dll.)
            return sprintf(
                "(%s) %s-%s",
                substr($cleanNomor, 0, 3),  // Kode area (3 digit)
                substr($cleanNomor, 3, 4),  // 4 digit berikutnya
                substr($cleanNomor, 7)     // Sisa digit
            );
        } else {
            // Nomor ponsel
            return sprintf(
                "%s %s-%s-%s",
                substr($cleanNomor, 0, 4),  // 4 digit pertama (0812)
                substr($cleanNomor, 4, 4),  // 4 digit berikutnya
                substr($cleanNomor, 8, 4),  // 4 digit berikutnya
                substr($cleanNomor, 12)    // Sisa digit
            );
        }
    }

    // Jika format tidak dikenali
    return 'Format nomor tidak valid';
}

function formatNomorTelepon($nomor) {
    // Hapus semua karakter selain angka
    $cleanNomor = preg_replace('/\D/', '', $nomor);

    // Validasi panjang nomor telepon (minimal 9 digit)
    $digid = strlen($cleanNomor);

    if ($digid < 9) {
        $peringatan = "tambahkan kode area";
        return "Format nomor tidak valid <r>$nomor</r> $peringatan";
    }

    // Jika nomor dimulai dengan 62 (kode internasional Indonesia)
    if (substr($cleanNomor, 0, 2) === '62') {
        $cleanNomor = '0' . substr($cleanNomor, 2); // Ubah menjadi format lokal
    }

    // Jika nomor dimulai dengan 0 (lokal), format sesuai
    if (substr($cleanNomor, 0, 1) === '0') {
        if (preg_match('/^02\d{7,9}$/', $cleanNomor)) {
            // Nomor telepon rumah dengan kode area 3 digit (021, dll.)
            return sprintf(
                "(%s*) %s-%s",
                substr($cleanNomor, 0, 3),  // Kode area (3 digit)
                substr($cleanNomor, 3, 4),  // 4 digit berikutnya
                substr($cleanNomor, 7)     // Sisa digit
            );
        } elseif (preg_match('/^0[3-9]\d{7,10}$/', $cleanNomor)) {
            // Nomor telepon rumah dengan kode area 4 digit (061, dll.)
            return sprintf(
                "(%s) %s-%s",
                substr($cleanNomor, 0, 4),  // Kode area (4 digit)
                substr($cleanNomor, 4, 3),  // 3 digit berikutnya
                substr($cleanNomor, 7)     // Sisa digit
            );
        } else {
            // Nomor ponsel
            return sprintf(
                "%s %s-%s-%s",
                substr($cleanNomor, 0, 4),  // 4 digit pertama (0812)
                substr($cleanNomor, 4, 4),  // 4 digit berikutnya
                substr($cleanNomor, 8, 4),  // 4 digit berikutnya
                substr($cleanNomor, 12)    // Sisa digit
            );
        }
    }

    // Jika format tidak dikenali
    return 'Format nomor tidak valid';
}
