<?php
$contens = "";
$p = new Layout($title, $subTitle, "application/template/default.html");

$rows = isset($rows) && is_array($rows) ? $rows : array();
$summary = isset($summary) && is_array($summary) ? $summary : array();
$filters = isset($filters) && is_array($filters) ? $filters : array();
$flash = isset($flash) && is_array($flash) ? $flash : array();
$thisPage = isset($thisPage) && strlen(trim((string)$thisPage)) > 0 ? $thisPage : base_url();
$backUrl = isset($backUrl) && strlen(trim((string)$backUrl)) > 0 ? $backUrl : $thisPage;
$forceReleaseUrl = isset($forceReleaseUrl) && strlen(trim((string)$forceReleaseUrl)) > 0 ? $forceReleaseUrl : $thisPage;
$forceReleaseAllUrl = isset($forceReleaseAllUrl) && strlen(trim((string)$forceReleaseAllUrl)) > 0 ? $forceReleaseAllUrl : $thisPage;

$filterModul = isset($filters['modul']) ? $filters['modul'] : "";
$filterUser = isset($filters['user']) ? $filters['user'] : "";
$filterCabang = isset($filters['cabang_id']) ? $filters['cabang_id'] : "";
$filterGudang = isset($filters['gudang_id']) ? $filters['gudang_id'] : "";
$filterStatus = isset($filters['status']) ? $filters['status'] : "all";

$csrfName = "";
$csrfHash = "";
if (isset($this->security)) {
    $csrfName = $this->security->get_csrf_token_name();
    $csrfHash = $this->security->get_csrf_hash();
}

$contens .= "<style>
    .locker-summary-box { min-height: 110px; }
    .locker-summary-value { font-size: 30px; font-weight: bold; line-height: 1.2; }
    .locker-summary-label { font-size: 13px; text-transform: uppercase; letter-spacing: .5px; }
    .locker-table td, .locker-table th { vertical-align: middle !important; }
    .locker-table form { margin: 0; }
</style>";

if (isset($flash['message']) && trim((string)$flash['message']) != "") {
    $flashType = isset($flash['type']) ? $flash['type'] : "info";
    $contens .= "<div class='alert alert-" . htmlspecialchars($flashType, ENT_QUOTES, 'UTF-8') . " alert-dismissible' role='alert'>";
    $contens .= "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>";
    $contens .= htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8');
    $contens .= "</div>";
}

$contens .= "<div class='panel panel-default' style='padding:15px;margin-bottom:15px;'>";
$contens .= "<form method='get' action='" . htmlspecialchars($thisPage, ENT_QUOTES, 'UTF-8') . "'>";
$contens .= "<div class='row'>";
$contens .= "<div class='col-md-3'>";
$contens .= "<label>Modul</label>";
$contens .= "<input type='text' class='form-control' name='modul' value='" . htmlspecialchars($filterModul, ENT_QUOTES, 'UTF-8') . "' placeholder='contoh: penjualan'>";
$contens .= "</div>";
$contens .= "<div class='col-md-3'>";
$contens .= "<label>User</label>";
$contens .= "<input type='text' class='form-control' name='user' value='" . htmlspecialchars($filterUser, ENT_QUOTES, 'UTF-8') . "' placeholder='id / nama user'>";
$contens .= "</div>";
$contens .= "<div class='col-md-2'>";
$contens .= "<label>Cabang ID</label>";
$contens .= "<input type='text' class='form-control' name='cabang_id' value='" . htmlspecialchars($filterCabang, ENT_QUOTES, 'UTF-8') . "' placeholder='id cabang'>";
$contens .= "</div>";
$contens .= "<div class='col-md-2'>";
$contens .= "<label>Gudang ID</label>";
$contens .= "<input type='text' class='form-control' name='gudang_id' value='" . htmlspecialchars($filterGudang, ENT_QUOTES, 'UTF-8') . "' placeholder='id gudang'>";
$contens .= "</div>";
$contens .= "<div class='col-md-2'>";
$contens .= "<label>Status</label>";
$contens .= "<select class='form-control' name='status'>";
$contens .= "<option value='all'" . ($filterStatus == "all" ? " selected" : "") . ">All</option>";
$contens .= "<option value='busy'" . ($filterStatus == "busy" ? " selected" : "") . ">Busy</option>";
$contens .= "<option value='expired'" . ($filterStatus == "expired" ? " selected" : "") . ">Expired</option>";
$contens .= "</select>";
$contens .= "<small class='text-muted'>Filter status berlaku hanya untuk locker dengan state = hold.</small>";
$contens .= "</div>";
$contens .= "</div>";
$contens .= "<div class='row' style='margin-top:15px;'>";
$contens .= "<div class='col-md-12'>";
$contens .= "<button type='submit' class='btn btn-primary'><i class='fa fa-filter'></i> Filter</button> &nbsp;";
$contens .= "<a href='" . htmlspecialchars($thisPage, ENT_QUOTES, 'UTF-8') . "' class='btn btn-default'><i class='fa fa-refresh'></i> Reset</a>";
$contens .= "</div>";
$contens .= "</div>";

$contens .= "</form>";
$contens .= "</div>";

$forceAllUrl = $forceReleaseAllUrl . "?" . http_build_query($filters) . "&back_url=" . urlencode($backUrl);
$contens .= "<div class='panel panel-default' style='padding:15px;margin-bottom:15px;'>";
$contens .= "<a href='" . htmlspecialchars($forceAllUrl, ENT_QUOTES, 'UTF-8') . "' class='btn btn-danger' onclick=\"return confirm('Force release semua locker pada hasil filter ini?');\"><i class='fa fa-unlock'></i> Force Release All</a>";
$contens .= "</div>";

$cardTotal = isset($summary['total_rows']) ? $summary['total_rows'] : 0;
$cardBusy = isset($summary['busy_rows']) ? $summary['busy_rows'] : 0;
$cardExpired = isset($summary['expired_rows']) ? $summary['expired_rows'] : 0;
$cardJumlah = isset($summary['total_jumlah']) ? $summary['total_jumlah'] : 0;

$cards = array(
    array("label" => "Total Locker", "value" => $cardTotal, "class" => "info"),
    array("label" => "Busy", "value" => $cardBusy, "class" => "success"),
    array("label" => "Expired", "value" => $cardExpired, "class" => "danger"),
    array("label" => "Total Jumlah", "value" => number_format((float)$cardJumlah, 0, ".", ","), "class" => "warning"),
);

$contens .= "<div class='row'>";
foreach ($cards as $card) {
    $contens .= "<div class='col-md-3'>";
    $contens .= "<div class='panel panel-" . $card['class'] . " locker-summary-box'>";
    $contens .= "<div class='panel-heading locker-summary-label'>" . htmlspecialchars($card['label'], ENT_QUOTES, 'UTF-8') . "</div>";
    $contens .= "<div class='panel-body text-center locker-summary-value'>" . htmlspecialchars((string)$card['value'], ENT_QUOTES, 'UTF-8') . "</div>";
    $contens .= "</div>";
    $contens .= "</div>";
}
$contens .= "</div>";

$contens .= "<div class='panel panel-default'>";
$contens .= "<div class='panel-heading'><strong>Daftar Locker Transaksi</strong></div>";
$contens .= "<div class='panel-body'>";
$contens .= "<div class='table-responsive'>";
$contens .= "<table id='table_locker_transaksi' class='table table-bordered table-striped locker-table datatables'>";
$contens .= "<thead>";
$contens .= "<tr class='bg-info'>";
$contens .= "<th class='text-center'>No</th>";
$contens .= "<th class='text-center'>Status</th>";
$contens .= "<th class='text-center'>State</th>";
$contens .= "<th class='text-center'>Modul</th>";
$contens .= "<th class='text-center'>Transaksi ID</th>";
$contens .= "<th class='text-center'>Jenis</th>";
$contens .= "<th class='text-center'>User</th>";
$contens .= "<th class='text-center'>Cabang</th>";
$contens .= "<th class='text-center'>Gudang</th>";
$contens .= "<th class='text-center'>Jumlah</th>";
$contens .= "<th class='text-center'>Expires At</th>";
$contens .= "<th class='text-center'>Remaining</th>";
$contens .= "<th class='text-center'>Note</th>";
$contens .= "<th class='text-center'>Aksi</th>";
$contens .= "</tr>";
$contens .= "</thead>";
$contens .= "<tbody>";

$no = 0;
if (sizeof($rows) > 0) {
    foreach ($rows as $row) {
        $no++;
        $rowStyle = isset($row['row_style']) ? $row['row_style'] : "";
        $statusBadgeClass = isset($row['status_badge_class']) ? $row['status_badge_class'] : "label label-default";
        $statusLabel = isset($row['status_label']) ? $row['status_label'] : "-";
        $state = isset($row['state']) ? $row['state'] : "-";
        $modul = isset($row['modul']) ? $row['modul'] : "-";
        $lockID = isset($row['id']) ? $row['id'] : 0;
        $transaksiID = isset($row['transaksi_id']) ? $row['transaksi_id'] : "-";
        $jenis = isset($row['transaksi_jenis']) ? $row['transaksi_jenis'] : "-";
        $jenisLocker = isset($row['jenis_locker']) ? $row['jenis_locker'] : "transaksi";
        $cabangID = isset($row['cabang_id']) ? $row['cabang_id'] : "";
        $gudangID = isset($row['gudang_id']) ? $row['gudang_id'] : "";
        $userDisplay = isset($row['owner_display']) ? $row['owner_display'] : "-";
        $cabangDisplay = isset($row['cabang_display']) ? $row['cabang_display'] : "-";
        $gudangDisplay = isset($row['gudang_display']) ? $row['gudang_display'] : "-";
        $jumlah = isset($row['jumlah']) ? $row['jumlah'] : 0;
        $expiresAt = isset($row['expires_at']) ? $row['expires_at'] : "-";
        $remainingText = isset($row['remaining_text']) ? $row['remaining_text'] : "-";
        $note = isset($row['note']) ? $row['note'] : "-";

        $contens .= "<tr style='" . htmlspecialchars($rowStyle, ENT_QUOTES, 'UTF-8') . "'>";
        $contens .= "<td class='text-right'>" . $no . "</td>";
        $contens .= "<td class='text-center'><span class='" . htmlspecialchars($statusBadgeClass, ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($statusLabel, ENT_QUOTES, 'UTF-8') . "</span></td>";
        $contens .= "<td>" . htmlspecialchars((string)$state, ENT_QUOTES, 'UTF-8') . "</td>";
        $contens .= "<td>" . htmlspecialchars((string)$modul, ENT_QUOTES, 'UTF-8') . "</td>";
        $contens .= "<td class='text-right'>" . htmlspecialchars((string)$transaksiID, ENT_QUOTES, 'UTF-8') . "</td>";
        $contens .= "<td>" . htmlspecialchars((string)$jenis, ENT_QUOTES, 'UTF-8') . "</td>";
        $contens .= "<td>" . htmlspecialchars((string)$userDisplay, ENT_QUOTES, 'UTF-8') . "</td>";
        $contens .= "<td class='text-center'>" . htmlspecialchars((string)$cabangDisplay, ENT_QUOTES, 'UTF-8') . "</td>";
        $contens .= "<td class='text-center'>" . htmlspecialchars((string)$gudangDisplay, ENT_QUOTES, 'UTF-8') . "</td>";
        $contens .= "<td class='text-right'>" . number_format((float)$jumlah, 0, ".", ",") . "</td>";
        $contens .= "<td>" . htmlspecialchars((string)$expiresAt, ENT_QUOTES, 'UTF-8') . "</td>";
        $contens .= "<td>" . htmlspecialchars((string)$remainingText, ENT_QUOTES, 'UTF-8') . "</td>";
        $contens .= "<td>" . htmlspecialchars((string)$note, ENT_QUOTES, 'UTF-8') . "</td>";
        $forceUrl = $forceReleaseUrl
            . "?lock_id=" . urlencode((string)$lockID)
            . "&modul=" . urlencode((string)$modul)
            . "&transaksi_id=" . urlencode((string)$transaksiID)
            . "&jenis=" . urlencode((string)$jenis)
            . "&jenis_locker=" . urlencode((string)$jenisLocker)
            . "&cabang_id=" . urlencode((string)$cabangID)
            . "&gudang_id=" . urlencode((string)$gudangID)
            . "&back_url=" . urlencode($backUrl);
        $contens .= "<td class='text-center'>";
        $contens .= "<a href='" . htmlspecialchars($forceUrl, ENT_QUOTES, 'UTF-8') . "' class='btn btn-danger btn-xs' onclick=\"return confirm('Force release locker transaksi ini?');\"><i class='fa fa-unlock'></i> Force Release</a>";
        $contens .= "</td>";
        $contens .= "</tr>";
    }
}
else {
    $contens .= "<tr><td colspan='14' class='text-center'>Tidak ada locker transaksi yang cocok dengan filter.</td></tr>";
}

$contens .= "</tbody>";
$contens .= "</table>";
$contens .= "</div>";
$contens .= "</div>";
$contens .= "</div>";

$contens .= "<script>
$(document).ready(function(){
    var table = $('#table_locker_transaksi').DataTable({
        dom: 'lBfrtip',
        fixedHeader: true,
        stateSave: true,
        processing: true,
        searchDelay: 1000,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
        order: [],
        buttons: [
            { extend: 'print', footer: true }
        ]
    });

    $('.table-responsive').floatingScroll();
});
</script>";

$profileName = isset($this->session->login['nama']) ? $this->session->login['nama'] : "";

$p->addTags(
    array(
        "menu_left" => callMenuLeft(),
        "float_menu_atas" => callFloatMenu('atas'),
        "float_menu_bawah" => callFloatMenu(),
        "menu_taskbar" => callMenuTaskbar(),
        "btn_back" => callBackNav(),
        "content" => $contens,
        "profile_name" => $profileName,
        "url" => $thisPage,
    )
);

$p->setContent("");
$p->render();


