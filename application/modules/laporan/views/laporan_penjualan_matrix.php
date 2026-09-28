<?php
defined('BASEPATH') or exit('No direct script access allowed');

$thn_now   = isset($year_now) ? $year_now : date('Y');
$thn_prev  = isset($year_prev) ? $year_prev : ($thn_now - 1);
$tbl_id    = "tbl_sales_compare_" . uniqid();
?>

<style>
    .table-matrix-report th, .table-matrix-report td {
        vertical-align: middle !important;
        white-space: nowrap;
        font-size: 12px;
    }
    .table-matrix-report thead tr th {
        text-align: center;
        background-color: #e9ecef;
        color: #333;
        border: 1px solid #ced4da;
    }
    .table-matrix-report tfoot tr th {
        background-color: #f8f9fa;
        font-weight: bold;
        border-top: 2px solid #6c757d;
    }
    .text-num {
        text-align: right;
    }
    .num-prev {
        color: #d9534f;
    }
    .num-now {
        color: #0275d8;
        font-weight: 500;
    }
    .num-zero {
        color: #bbb;
    }
    .cell-clickable {
        cursor: pointer !important;
        text-decoration: underline;
        text-decoration-style: dotted;
        transition: all 0.15s ease-in-out;
    }
    .cell-clickable:hover {
        background-color: #ffeeba !important;
        color: #004085 !important;
        font-weight: bold;
    }
    .col-summary-hdr {
        background-color: #e5dec9 !important;
        font-weight: bold;
        color: #222;
        text-align: center;
        vertical-align: middle !important;
        line-height: 1.3;
        border: 1px solid #ced4da;
    }
    .current-month-header {
        background-color: #d0ebff !important;
        color: #004085 !important;
        font-weight: bold;
    }
    .box-header-report {
        padding: 5px 0 15px 0;
    }
</style>

<!-- Header Title -->
<div class="box-header-report">
    <h4 style="margin: 0 0 5px 0; font-weight: bold;">
        <?php echo isset($title) ? htmlspecialchars($title) : ("Laporan penjualan per salesman Tahun " . $thn_now); ?>
        <?php if (!empty($sub_title)): ?>
            <small style="color: #0000FF; font-weight: normal; font-size: 13px;">
                <?php echo htmlspecialchars($sub_title); ?>
            </small>
        <?php endif; ?>
    </h4>
    <div style="font-size: 12px; color: #666;">
        Periode 01 Januari s/d 31 Desember <?php echo $thn_now; ?>
    </div>
</div>

<!-- Table Container -->
<div class="table-responsive">
    <table id="<?php echo $tbl_id; ?>" class="table table-bordered table-striped table-hover table-matrix-report" width="100%">
        <thead>
            <tr>
                <th rowspan="2" style="width: 40px;">NO</th>
                <th rowspan="2" style="min-width: 180px;">PIC</th>
                <?php foreach ($months as $mCode => $mName): 
                    $isCurrentMonth = ((int)$mCode === (int)$ytd_info['m_ytd']);
                    $thClass = $isCurrentMonth ? 'month-header current-month-header' : 'month-header';
                ?>
                    <th colspan="2" class="<?php echo $thClass; ?>"><?php echo strtoupper($mName); ?></th>
                <?php endforeach; ?>

                <!-- MODE KOMPARASI (Saat Tahun Lalu Ditampilkan) -->
                <th colspan="2" class="col-summary-compare col-summary-hdr" style="border-left: 2px solid #bbb;"><?php echo $ytd_info['label_compare_subtotal']; ?></th>
                <th colspan="2" class="col-summary-compare col-summary-hdr"><?php echo $ytd_info['label_compare_avg']; ?></th>
                <th colspan="2" class="col-summary-compare col-summary-hdr"><?php echo $ytd_info['label_compare_weighted']; ?></th>
                <th rowspan="2" class="col-summary-compare col-summary-hdr" style="min-width: 65px; vertical-align: middle !important;"><?php echo $ytd_info['label_compare_kpi']; ?></th>

                <!-- MODE SINGLE YEAR (Saat Tahun Lalu Disembunyikan) -->
                <th rowspan="2" class="col-summary-single col-summary-hdr" style="display: none; min-width: 130px; border-left: 2px solid #bbb;"><?php echo $ytd_info['label_subtotal_prev']; ?></th>
                <th rowspan="2" class="col-summary-single col-summary-hdr" style="display: none; min-width: 120px;"><?php echo $ytd_info['label_avg_prev']; ?></th>
                <th rowspan="2" class="col-summary-single col-summary-hdr" style="display: none; min-width: 130px;"><?php echo $ytd_info['label_subtotal_ytd']; ?></th>
                <th rowspan="2" class="col-summary-single col-summary-hdr" style="display: none; min-width: 120px;"><?php echo $ytd_info['label_avg_ytd']; ?></th>
            </tr>
            <tr>
                <?php foreach ($months as $mCode => $mName): 
                    $isCurrentMonth = ((int)$mCode === (int)$ytd_info['m_ytd']);
                    $curBg = $isCurrentMonth ? 'background-color: #d0ebff;' : '';
                ?>
                    <th class="col-prev" style="width: 90px; <?php echo $curBg; ?>"><?php echo $thn_prev; ?></th>
                    <th class="col-now" style="width: 90px; <?php echo $curBg; ?>"><?php echo $thn_now; ?></th>
                <?php endforeach; ?>

                <!-- Sub-header Mode Komparasi -->
                <th class="col-summary-compare col-summary-hdr col-prev" style="width: 90px; border-left: 2px solid #bbb;"><?php echo $ytd_info['sub_label_prev']; ?></th>
                <th class="col-summary-compare col-summary-hdr col-now" style="width: 90px;"><?php echo $ytd_info['sub_label_now']; ?></th>

                <th class="col-summary-compare col-summary-hdr col-prev" style="width: 90px;"><?php echo $ytd_info['sub_label_prev']; ?></th>
                <th class="col-summary-compare col-summary-hdr col-now" style="width: 90px;"><?php echo $ytd_info['sub_label_now']; ?></th>

                <th class="col-summary-compare col-summary-hdr col-prev" style="width: 90px;"><?php echo $ytd_info['sub_label_prev']; ?></th>
                <th class="col-summary-compare col-summary-hdr col-now" style="width: 90px;"><?php echo $ytd_info['sub_label_now']; ?></th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            foreach ($sellers as $sellerId => $sellerNama): 
                $isAllZero = true;
                foreach ($months as $mCode => $mName) {
                    $valPrev = isset($matrix[$sellerId][$thn_prev][$mCode]) ? (float)$matrix[$sellerId][$thn_prev][$mCode] : 0;
                    $valNow  = isset($matrix[$sellerId][$thn_now][$mCode]) ? (float)$matrix[$sellerId][$thn_now][$mCode] : 0;
                    if ($valPrev != 0 || $valNow != 0) {
                        $isAllZero = false;
                        break;
                    }
                }
                $rowClass = $isAllZero ? 'all-zero' : '';
            ?>
                <tr class="<?php echo $rowClass; ?>" data-seller-id="<?php echo $sellerId; ?>">
                    <td class="text-center"><?php echo $no++; ?></td>
                    <td><strong><?php echo htmlspecialchars($sellerNama); ?></strong></td>
                    <?php foreach ($months as $mCode => $mName): 
                        $valPrev = isset($matrix[$sellerId][$thn_prev][$mCode]) ? (float)$matrix[$sellerId][$thn_prev][$mCode] : 0;
                        $valNow  = isset($matrix[$sellerId][$thn_now][$mCode]) ? (float)$matrix[$sellerId][$thn_now][$mCode] : 0;

                        $fmtPrev = ($valPrev != 0) ? number_format($valPrev) : '0';
                        $fmtNow  = ($valNow != 0) ? number_format($valNow) : '0';

                        $clsPrev = ($valPrev > 0) ? 'num-prev' : (($valPrev < 0) ? 'text-danger' : 'num-zero');
                        $clsNow  = ($valNow > 0) ? 'num-now' : (($valNow < 0) ? 'text-danger' : 'num-zero');

                        $isCurrentMonth = ((int)$mCode === (int)$ytd_info['m_ytd']);
                        $curBg = $isCurrentMonth ? 'background-color: #e8f4fd;' : '';

                        // Clickable attributes
                        $clickPrev = ($valPrev != 0) ? 'cell-clickable' : '';
                        $attrPrev = ($valPrev != 0) 
                            ? 'data-seller-id="' . $sellerId . '" data-seller-nama="' . htmlspecialchars($sellerNama) . '" data-year="' . $thn_prev . '" data-month="' . (int)$mCode . '" data-label="' . $mName . ' ' . $thn_prev . '" title="Klik untuk rincian transaksi ' . $mName . ' ' . $thn_prev . '"' 
                            : '';

                        $clickNow = ($valNow != 0) ? 'cell-clickable' : '';
                        $attrNow = ($valNow != 0) 
                            ? 'data-seller-id="' . $sellerId . '" data-seller-nama="' . htmlspecialchars($sellerNama) . '" data-year="' . $thn_now . '" data-month="' . (int)$mCode . '" data-label="' . $mName . ' ' . $thn_now . '" title="Klik untuk rincian transaksi ' . $mName . ' ' . $thn_now . '"' 
                            : '';
                    ?>
                        <td class="text-num col-prev <?php echo $clsPrev; ?> <?php echo $clickPrev; ?>" style="<?php echo $curBg; ?>" <?php echo $attrPrev; ?>><?php echo $fmtPrev; ?></td>
                        <td class="text-num col-now <?php echo $clsNow; ?> <?php echo $clickNow; ?>" style="<?php echo $curBg; ?>" <?php echo $attrNow; ?>><?php echo $fmtNow; ?></td>
                    <?php endforeach; ?>

                    <!-- MODE KOMPARASI CELLS -->
                    <?php 
                        $cmp = isset($summary_compare[$sellerId]) ? $summary_compare[$sellerId] : array();
                        $subP = isset($cmp['subtotal_prev']) ? (float)$cmp['subtotal_prev'] : 0;
                        $subN = isset($cmp['subtotal_now']) ? (float)$cmp['subtotal_now'] : 0;
                        $avgP = isset($cmp['avg_prev']) ? (float)$cmp['avg_prev'] : 0;
                        $avgN = isset($cmp['avg_now']) ? (float)$cmp['avg_now'] : 0;
                        $wtP  = isset($cmp['avg_weighted_prev']) ? (float)$cmp['avg_weighted_prev'] : 0;
                        $wtN  = isset($cmp['avg_weighted_now']) ? (float)$cmp['avg_weighted_now'] : 0;
                        $kpi  = isset($cmp['kpi_status']) ? $cmp['kpi_status'] : 'Turun';

                        $clickSubP = ($subP != 0) ? 'cell-clickable' : '';
                        $attrSubP = ($subP != 0) 
                            ? 'data-seller-id="' . $sellerId . '" data-seller-nama="' . htmlspecialchars($sellerNama) . '" data-year="' . $thn_prev . '" data-month-start="1" data-month-end="12" data-label="Full Year ' . $thn_prev . '" title="Klik untuk rincian transaksi Full Year ' . $thn_prev . '"' 
                            : '';

                        $clickSubN = ($subN != 0) ? 'cell-clickable' : '';
                        $attrSubN = ($subN != 0) 
                            ? 'data-seller-id="' . $sellerId . '" data-seller-nama="' . htmlspecialchars($sellerNama) . '" data-year="' . $thn_now . '" data-month-start="1" data-month-end="' . $ytd_info['m_ytd'] . '" data-label="YTD s/d ' . $ytd_info['nama_bln_ytd'] . ' ' . $thn_now . '" title="Klik untuk rincian transaksi YTD ' . $thn_now . '"' 
                            : '';
                    ?>
                    <td class="text-num col-summary-compare col-prev <?php echo $clickSubP; ?>" style="background-color: #fef9e7; border-left: 2px solid #ddd;" <?php echo $attrSubP; ?>><?php echo ($subP != 0) ? number_format($subP) : '0'; ?></td>
                    <td class="text-num col-summary-compare col-now <?php echo $clickSubN; ?>" style="background-color: #fef9e7;" <?php echo $attrSubN; ?>><?php echo ($subN != 0) ? number_format($subN) : '0'; ?></td>

                    <td class="text-num col-summary-compare col-prev" style="background-color: #fef9e7;"><?php echo ($avgP != 0) ? number_format($avgP) : '0'; ?></td>
                    <td class="text-num col-summary-compare col-now" style="background-color: #fef9e7;"><?php echo ($avgN != 0) ? number_format($avgN) : '0'; ?></td>

                    <td class="text-num col-summary-compare col-prev" style="background-color: #fef9e7;"><?php echo ($wtP != 0) ? number_format($wtP) : '0'; ?></td>
                    <td class="text-num col-summary-compare col-now" style="background-color: #fef9e7;"><?php echo ($wtN != 0) ? number_format($wtN) : '0'; ?></td>

                    <td class="text-center col-summary-compare" style="background-color: #fef9e7; font-weight: bold;">
                        <?php if ($kpi === 'Naik'): ?>
                            <span style="color: #28a745;">Naik &uarr;</span>
                        <?php else: ?>
                            <span style="color: #dc3545;">Turun &darr;</span>
                        <?php endif; ?>
                    </td>

                    <!-- MODE SINGLE YEAR CELLS -->
                    <?php 
                        $sgl = isset($summary_single[$sellerId]) ? $summary_single[$sellerId] : array();
                        $sglSubPrev = isset($sgl['subtotal_prev_ytd']) ? (float)$sgl['subtotal_prev_ytd'] : 0;
                        $sglAvgPrev = isset($sgl['avg_prev_ytd']) ? (float)$sgl['avg_prev_ytd'] : 0;
                        $sglSubYtd  = isset($sgl['subtotal_ytd']) ? (float)$sgl['subtotal_ytd'] : 0;
                        $sglAvgYtd  = isset($sgl['avg_ytd']) ? (float)$sgl['avg_ytd'] : 0;

                        $clickSglP = ($sglSubPrev != 0) ? 'cell-clickable' : '';
                        $attrSglP = ($sglSubPrev != 0) 
                            ? 'data-seller-id="' . $sellerId . '" data-seller-nama="' . htmlspecialchars($sellerNama) . '" data-year="' . $thn_now . '" data-month-start="1" data-month-end="' . $ytd_info['m_prev_ytd'] . '" data-label="Up to ' . $ytd_info['nama_bln_prev_ytd'] . ' ' . $thn_now . '" title="Klik untuk rincian transaksi Up to ' . $ytd_info['nama_bln_prev_ytd'] . ' ' . $thn_now . '"' 
                            : '';

                        $clickSglY = ($sglSubYtd != 0) ? 'cell-clickable' : '';
                        $attrSglY = ($sglSubYtd != 0) 
                            ? 'data-seller-id="' . $sellerId . '" data-seller-nama="' . htmlspecialchars($sellerNama) . '" data-year="' . $thn_now . '" data-month-start="1" data-month-end="' . $ytd_info['m_ytd'] . '" data-label="YTD s/d ' . $ytd_info['nama_bln_ytd'] . ' ' . $thn_now . '" title="Klik untuk rincian transaksi YTD s/d ' . $ytd_info['nama_bln_ytd'] . ' ' . $thn_now . '"' 
                            : '';
                    ?>
                    <td class="text-num col-summary-single <?php echo $clickSglP; ?>" style="display: none; background-color: #fef9e7; border-left: 2px solid #ddd;" <?php echo $attrSglP; ?>><?php echo ($sglSubPrev != 0) ? number_format($sglSubPrev) : '0'; ?></td>
                    <td class="text-num col-summary-single" style="display: none; background-color: #fef9e7;"><?php echo ($sglAvgPrev != 0) ? number_format($sglAvgPrev) : '0'; ?></td>
                    <td class="text-num col-summary-single <?php echo $clickSglY; ?>" style="display: none; background-color: #fef9e7; font-weight: 500;" <?php echo $attrSglY; ?>><?php echo ($sglSubYtd != 0) ? number_format($sglSubYtd) : '0'; ?></td>
                    <td class="text-num col-summary-single" style="display: none; background-color: #fef9e7; font-weight: 500;"><?php echo ($sglAvgYtd != 0) ? number_format($sglAvgYtd) : '0'; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" style="text-align: right;">TOTAL</th>
                <?php foreach ($months as $mCode => $mName): 
                    $totPrev = isset($total_bawah[$thn_prev][$mCode]) ? (float)$total_bawah[$thn_prev][$mCode] : 0;
                    $totNow  = isset($total_bawah[$thn_now][$mCode]) ? (float)$total_bawah[$thn_now][$mCode] : 0;
                    $isCurrentMonth = ((int)$mCode === (int)$ytd_info['m_ytd']);
                    $curBg = $isCurrentMonth ? 'background-color: #d0ebff;' : '';
                ?>
                    <th class="text-num col-prev" style="<?php echo $curBg; ?>"><?php echo number_format($totPrev); ?></th>
                    <th class="text-num col-now" style="<?php echo $curBg; ?>"><?php echo number_format($totNow); ?></th>
                <?php endforeach; ?>

                <!-- MODE KOMPARASI FOOTER -->
                <th class="text-num col-summary-compare col-prev" style="background-color: #f7f1d8; border-left: 2px solid #bbb;"><?php echo number_format($total_compare['subtotal_prev']); ?></th>
                <th class="text-num col-summary-compare col-now" style="background-color: #f7f1d8;"><?php echo number_format($total_compare['subtotal_now']); ?></th>

                <th class="text-num col-summary-compare col-prev" style="background-color: #f7f1d8;"><?php echo number_format($total_compare['avg_prev']); ?></th>
                <th class="text-num col-summary-compare col-now" style="background-color: #f7f1d8;"><?php echo number_format($total_compare['avg_now']); ?></th>

                <th class="text-num col-summary-compare col-prev" style="background-color: #f7f1d8;"><?php echo number_format($total_compare['avg_weighted_prev']); ?></th>
                <th class="text-num col-summary-compare col-now" style="background-color: #f7f1d8;"><?php echo number_format($total_compare['avg_weighted_now']); ?></th>

                <th class="text-center col-summary-compare" style="background-color: #f7f1d8; font-weight: bold;">
                    <?php if ($total_compare['kpi_status'] === 'Naik'): ?>
                        <span style="color: #28a745;">Naik &uarr;</span>
                    <?php else: ?>
                        <span style="color: #dc3545;">Turun &darr;</span>
                    <?php endif; ?>
                </th>

                <!-- MODE SINGLE YEAR FOOTER -->
                <th class="text-num col-summary-single" style="display: none; background-color: #f7f1d8; border-left: 2px solid #bbb;"><?php echo number_format($total_single['subtotal_prev_ytd']); ?></th>
                <th class="text-num col-summary-single" style="display: none; background-color: #f7f1d8;"><?php echo number_format($total_single['avg_prev_ytd']); ?></th>
                <th class="text-num col-summary-single" style="display: none; background-color: #f7f1d8;"><?php echo number_format($total_single['subtotal_ytd']); ?></th>
                <th class="text-num col-summary-single" style="display: none; background-color: #f7f1d8;"><?php echo number_format($total_single['avg_ytd']); ?></th>
            </tr>
        </tfoot>
    </table>
</div>

<script>
$(document).ready(function() {
    var zeroRowsVisible = false;
    var prevYearVisible = true;

    // Sembunyikan baris yang nilainya semua 0 pada saat awal dimuat
    $('#<?php echo $tbl_id; ?> tbody tr.all-zero').hide();

    if ($.fn.DataTable.isDataTable('#<?php echo $tbl_id; ?>')) {
        $('#<?php echo $tbl_id; ?>').DataTable().destroy();
    }

    var table = $('#<?php echo $tbl_id; ?>').DataTable({
        dom: 'lBfrtip',
        fixedHeader: true,
        processing: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        pageLength: 50,
        buttons: [
            { extend: 'copy', className: 'btn btn-default btn-sm' },
            { extend: 'csv', className: 'btn btn-default btn-sm' },
            { extend: 'excel', className: 'btn btn-default btn-sm', title: 'Laporan_Order_Penjualan_<?php echo $thn_now; ?>' },
            { extend: 'print', className: 'btn btn-default btn-sm' },
            {
                text: 'Show Zero Rows',
                className: 'btn btn-default btn-sm',
                action: function (e, dt, node, config) {
                    zeroRowsVisible = !zeroRowsVisible;
                    if (zeroRowsVisible) {
                        $('#<?php echo $tbl_id; ?> tbody tr.all-zero').show();
                        $(node).text('Hide Zero Rows');
                    } else {
                        $('#<?php echo $tbl_id; ?> tbody tr.all-zero').hide();
                        $(node).text('Show Zero Rows');
                    }
                }
            },
            {
                text: 'Sembunyikan tahun sebelumnya',
                className: 'btn btn-default btn-sm',
                action: function (e, dt, node, config) {
                    prevYearVisible = !prevYearVisible;
                    if (prevYearVisible) {
                        // Tampilkan Tahun Sebelumnya (Mode Komparasi Aktif)
                        $('.col-prev').show();
                        $('.month-header').attr('colspan', 2);
                        $('.col-summary-compare').show();
                        $('.col-summary-single').hide();
                        $(node).text('Sembunyikan tahun sebelumnya');
                    } else {
                        // Sembunyikan Tahun Sebelumnya (Mode Single Year Aktif)
                        $('.col-prev').hide();
                        $('.month-header').attr('colspan', 1);
                        $('.col-summary-compare').hide();
                        $('.col-summary-single').show();
                        $(node).text('Tampilkan tahun sebelumnya');
                    }
                }
            }
        ],
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries"
        }
    });

    // Event Handler Drill-Down Modal Rincian Transaksi
    $(document).on('click', '.cell-clickable', function() {
        var sellerId   = $(this).data('seller-id');
        var sellerNama = $(this).data('seller-nama');
        var year       = $(this).data('year');
        var mStart     = $(this).data('month') || $(this).data('month-start') || 1;
        var mEnd       = $(this).data('month') || $(this).data('month-end') || mStart;
        var label      = $(this).data('label');

        var detailBaseUrl = '<?php echo isset($detail_url) ? $detail_url : (base_url() . "laporan/PenjualanCompare/detailSellerTransactions"); ?>';
        var gudangStatus  = '<?php echo isset($gudang_status) ? $gudang_status : "all"; ?>';
        var url = detailBaseUrl
                + '?seller_id=' + encodeURIComponent(sellerId)
                + '&seller_nama=' + encodeURIComponent(sellerNama)
                + '&year=' + encodeURIComponent(year)
                + '&month_start=' + encodeURIComponent(mStart)
                + '&month_end=' + encodeURIComponent(mEnd)
                + '&label=' + encodeURIComponent(label)
                + '&gudang_status=' + encodeURIComponent(gudangStatus);

        $('#myModal .modal-dialog').css('width', '90%').css('max-width', '1200px');
        $('#myModal .modal-content').html(
            '<div class="modal-body text-center" style="padding: 40px;">' +
            '<i class="fa fa-spinner fa-spin fa-3x fa-fw text-primary"></i>' +
            '<p style="margin-top: 15px; font-size: 14px;">Memuat rincian transaksi...</p>' +
            '</div>'
        );
        $('#myModal').modal('show');

        $('#myModal .modal-content').load(url, function(response, status, xhr) {
            if (status == "error") {
                $('#myModal .modal-content').html(
                    '<div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title text-danger"><i class="fa fa-exclamation-triangle"></i> Gagal Memuat Data</h4></div>' +
                    '<div class="modal-body"><p>Terjadi kesalahan saat memuat rincian transaksi: ' + xhr.status + ' ' + xhr.statusText + '</p></div>' +
                    '<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button></div>'
                );
            }
        });
    });
});
</script>
