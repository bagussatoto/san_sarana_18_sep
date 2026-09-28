<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Fallback jika columns_config tidak disuplai dari controller PenjualanCompare/getDetailColumnsConfig
if (!isset($columns_config) || !is_array($columns_config)) {
    $columns_config = array(
        array('field' => 'dtime',           'label' => 'TANGGAL & JAM',        'width' => '130px', 'align' => 'center', 'type' => 'datetime'),
        array('field' => 'nomer',           'label' => 'NO. TRANSAKSI',        'width' => '140px', 'align' => 'left',   'type' => 'bold'),
        // array('field' => 'jenis',           'label' => 'JENIS',                'width' => '80px',  'align' => 'center', 'type' => 'badge'),
        array('field' => 'customers_nama',  'label' => 'CUSTOMER / PELANGGAN', 'width' => '',      'align' => 'left',   'type' => 'text'),
        array('field' => 'oleh_nama',       'label' => 'OPERATOR INPUT',       'width' => '120px', 'align' => 'center', 'type' => 'text'),
        array('field' => 'transaksi_netto', 'label' => 'NETTO (RP)',           'width' => '130px', 'align' => 'right',  'type' => 'currency', 'sum' => true),
        array('field' => 'keterangan',      'label' => 'KETERANGAN',           'width' => '',      'align' => 'left',   'type' => 'muted')
    );
}

// Cari indeks kolom pertama yang memiliki flag sum untuk menentukan colspan label 'TOTAL:' di footer
$firstSumIdx = -1;
foreach ($columns_config as $idx => $col) {
    if (!empty($col['sum'])) {
        $firstSumIdx = $idx;
        break;
    }
}

$sums = array();
$total_netto = 0;
if (!empty($transactions)) {
    foreach ($transactions as $row) {
        $total_netto += isset($row['transaksi_netto']) ? (float)$row['transaksi_netto'] : 0;
    }
}
?>

<!-- Modal Header -->
<div class="modal-header" style="background-color: #f8f9fa; border-bottom: 2px solid #007bff;">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true" style="font-size: 24px;">&times;</span>
    </button>
    <h4 class="modal-title text-white" style="font-weight: bold; margin-bottom: 5px;">
        <i class="fa fa-list-alt text-primary"></i> Rincian Order Penjualan: 
        <span class="text-primary"><?php echo htmlspecialchars($seller_nama); ?></span>
    </h4>
    <div style="font-size: 13px; color: #ffffffff;">
        <span>Periode: <strong><?php echo htmlspecialchars($periode_label); ?></strong></span>
        <span style="margin: 0 10px;">|</span>
        <span>Jumlah: <strong><?php echo count($transactions); ?> Transaksi</strong></span>
        <span style="margin: 0 10px;">|</span>
        <span>Total: <strong>Rp <?php echo number_format($total_netto); ?></strong></span>
    </div>
</div>

<!-- Modal Body -->
<div class="modal-body" style="padding: 15px; max-height: 70vh; overflow-y: auto;">
    <?php if (empty($transactions)): ?>
        <div class="alert alert-info text-center" style="margin: 20px 0;">
            <i class="fa fa-info-circle fa-2x"></i>
            <p style="margin-top: 10px; font-size: 14px;">Tidak ditemukan data transaksi untuk periode ini.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover table-condensed" style="font-size: 12px; margin-bottom: 0;">
                <thead>
                    <tr style="background-color: #e9ecef; color: #333;">
                        <th style="width: 35px; text-align: center;">NO</th>
                        <?php foreach ($columns_config as $col): 
                            $w = !empty($col['width']) ? 'width: ' . $col['width'] . '; ' : '';
                            $a = !empty($col['align']) ? 'text-align: ' . $col['align'] . '; ' : 'text-align: left; ';
                        ?>
                            <th style="<?php echo $w . $a; ?>">
                                <?php echo htmlspecialchars($col['label']); ?>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach ($transactions as $row): 
                    ?>
                        <tr>
                            <td class="text-center"><?php echo $no++; ?></td>
                            <?php foreach ($columns_config as $col): 
                                $field = $col['field'];
                                $type  = isset($col['type']) ? $col['type'] : 'text';
                                $align = isset($col['align']) ? $col['align'] : 'left';
                                $val   = isset($row[$field]) ? $row[$field] : '';

                                if (!empty($col['sum'])) {
                                    $numVal = (float)$val;
                                    $sums[$field] = isset($sums[$field]) ? ($sums[$field] + $numVal) : $numVal;
                                }
                            ?>
                                <td class="text-<?php echo $align; ?>">
                                    <?php 
                                    switch ($type) {
                                        case 'datetime':
                                            echo !empty($val) ? date('d-m-Y H:i', strtotime($val)) : '-';
                                            break;
                                        case 'date':
                                            echo !empty($val) ? date('d-m-Y', strtotime($val)) : '-';
                                            break;
                                        case 'bold':
                                            echo '<strong>' . htmlspecialchars($val) . '</strong>';
                                            break;
                                        case 'badge':
                                            echo '<span class="label label-info" style="font-size: 11px;">' . htmlspecialchars($val) . '</span>';
                                            break;
                                        case 'currency':
                                            echo '<span style="font-weight: bold; color: #0275d8;">' . number_format((float)$val) . '</span>';
                                            break;
                                        case 'muted':
                                            echo '<small class="text-muted">' . htmlspecialchars(!empty($val) ? $val : '-') . '</small>';
                                            break;
                                        case 'text':
                                        default:
                                            echo htmlspecialchars(!empty($val) ? $val : '-');
                                            break;
                                    }
                                    ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr style="background-color: #f8f9fa; font-weight: bold; font-size: 13px; border-top: 2px solid #6c757d;">
                        <?php if ($firstSumIdx > 0): ?>
                            <th colspan="<?php echo $firstSumIdx + 1; ?>" style="text-align: right;">TOTAL:</th>
                            <?php for ($i = $firstSumIdx; $i < count($columns_config); $i++): 
                                $col = $columns_config[$i];
                                $field = $col['field'];
                                $align = isset($col['align']) ? $col['align'] : 'right';
                            ?>
                                <th class="text-<?php echo $align; ?>" style="color: #28a745; font-size: 14px;">
                                    <?php 
                                    if (!empty($col['sum']) && isset($sums[$field])) {
                                        echo 'Rp ' . number_format($sums[$field]);
                                    }
                                    ?>
                                </th>
                            <?php endfor; ?>
                        <?php else: ?>
                            <th colspan="<?php echo count($columns_config) + 1; ?>" style="text-align: right;">
                                TOTAL: <span style="color: #28a745; font-size: 14px;">Rp <?php echo number_format($total_netto); ?></span>
                            </th>
                        <?php endif; ?>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Modal Footer -->
<div class="modal-footer" style="background-color: #f8f9fa; border-top: 1px solid #dee2e6;">
    <button type="button" class="btn btn-default" data-dismiss="modal">
        <i class="fa fa-times"></i> Tutup
    </button>
</div>
