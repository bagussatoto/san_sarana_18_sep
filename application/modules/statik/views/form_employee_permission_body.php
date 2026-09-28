<?php
// PHP helper function to build permission table add.iwan.bpn
if (!function_exists('render_permission_table')) {
  function render_permission_table($data, $tabName)
  {
    if (empty($data)) {
      return '<div class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> Tidak ada hak akses untuk kategori ' . $tabName . '</div>';
    }

    $showHistorical = ($tabName === 'DATA');
    $colWidth = $showHistorical ? '12%' : '15%';

    $html = '<div class="permission-list" style="max-height: 450px; overflow-y: auto;">';
    $html .= '<table class="table table-condensed table-bordered table-hover" style="margin-bottom:0;">';
    $html .= '<thead style="position: sticky; top: 0; z-index: 10; background-color: #f5f5f5;"><tr>';
    $html .= '<th style="width:15%; background-color: #f5f5f5;" class="text-center">GRUP</th>';
    $html .= '<th style="width:25%; background-color: #f5f5f5;">NAMA</th>';
    $html .= '<th class="text-center" style="width:' . $colWidth . '; background-color: #f5f5f5;" title="Menambahkan data baru">CREATE</th>';
    $html .= '<th class="text-center" style="width:' . $colWidth . '; background-color: #f5f5f5;" title="Mengubah data yang sudah ada">EDIT</th>';
    $html .= '<th class="text-center" style="width:' . $colWidth . '; background-color: #f5f5f5;" title="Menghapus data (soft delete)">DELETE</th>';
    $html .= '<th class="text-center" style="width:' . $colWidth . '; background-color: #f5f5f5;" title="Melihat/membaca data">VIEW</th>';
    if ($showHistorical) {
      $html .= '<th class="text-center" style="width:12%; background-color: #f5f5f5;" title="Melihat riwayat perubahan data">HISTORICAL</th>';
    }
    $html .= '</tr></thead>';
    $html .= '<tbody>';

    foreach ($data as $groupName => $modules) {
      if (is_array($modules) && count($modules) > 0) {
        $moduleCount = count($modules);
        $firstRow = true;

        foreach ($modules as $moduleName => $permissions) {
          if (is_string($permissions)) {
            $permissions = [$permissions];
          }

          $hasCreate = is_array($permissions) && in_array('CREATE', $permissions);
          $hasEdit = is_array($permissions) && in_array('EDIT', $permissions);
          $hasDelete = is_array($permissions) && in_array('DELETE', $permissions);
          $hasView = is_array($permissions) && in_array('VIEW', $permissions);
          $hasHistorical = is_array($permissions) && in_array('HISTORICAL', $permissions);

          $html .= '<tr>';

          if ($firstRow) {
            $html .= '<td rowspan="' . $moduleCount . '" class="text-center" style="vertical-align:middle; background-color:#f5f5f5; font-weight:bold;">';
            $html .= '<i class="fa fa-folder-open"></i><br>' . strtoupper($groupName);
            $html .= '</td>';
            $firstRow = false;
          }

          $html .= '<td><strong>' . htmlspecialchars($moduleName) . '</strong></td>';
          $html .= '<td class="text-center">' . ($hasCreate ? '<i class="fa fa-check text-success"></i>' : '<span class="text-muted">-</span>') . '</td>';
          $html .= '<td class="text-center">' . ($hasEdit ? '<i class="fa fa-check text-success"></i>' : '<span class="text-muted">-</span>') . '</td>';
          $html .= '<td class="text-center">' . ($hasDelete ? '<i class="fa fa-check text-success"></i>' : '<span class="text-muted">-</span>') . '</td>';
          $html .= '<td class="text-center">' . ($hasView ? '<i class="fa fa-check text-success"></i>' : '<span class="text-muted">-</span>') . '</td>';
          if ($showHistorical) {
            $html .= '<td class="text-center">' . ($hasHistorical ? '<i class="fa fa-check text-success"></i>' : '<span class="text-muted">-</span>') . '</td>';
          }
          $html .= '</tr>';
        }
      }
    }

    $html .= '</tbody></table>';
    $html .= '</div>';

    return $html;
  }
}

if (!function_exists('render_permission_other_table')) {
  function render_permission_other_table($data, $tabName)
  {
    if (empty($data)) {
      return '<div class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> Tidak ada hak akses untuk kategori ' . $tabName . '</div>';
    }

    $html = '<div class="permission-list" style="max-height: 450px; overflow-y: auto;">';
    $html .= '<table class="table table-condensed table-bordered table-hover" style="margin-bottom:0;">';
    $html .= '<thead style="position: sticky; top: 0; z-index: 10; background-color: #f5f5f5;"><tr>';
    $html .= '<th style="width:70%; background-color: #f5f5f5;"><i class="fa fa-list"></i> LAIN-LAIN</th>';
    $html .= '<th class="text-center" style="width:30%; background-color: #f5f5f5;" title="Diizinkan untuk mengakses fitur ini">ACCESS</th>';
    $html .= '</tr></thead>';
    $html .= '<tbody>';

    foreach ($data as $permName => $status) {
      $hasAccess = false;
      if (is_array($status) && count($status) > 0) {
        $hasAccess = in_array('ACCESS', $status) || count($status) > 0;
      } elseif (is_string($status)) {
        $hasAccess = ($status === 'ACCESS' || strlen($status) > 0);
      }

      $html .= '<tr>';
      $html .= '<td><strong>' . htmlspecialchars($permName) . '</strong></td>';
      $html .= '<td class="text-center">' . ($hasAccess ? '<i class="fa fa-check text-success"></i>' : '<span class="text-muted">-</span>') . '</td>';
      $html .= '</tr>';
    }

    $html .= '</tbody></table>';
    $html .= '</div>';

    return $html;
  }
}

if (!function_exists('count_active_permissions')) {
  function count_active_permissions($data)
  {
    $count = 0;
    if (empty($data) || !is_array($data))
      return 0;
    foreach ($data as $group => $modules) {
      if (is_array($modules)) {
        foreach ($modules as $moduleName => $permissions) {
          if (is_array($permissions)) {
            $count += count($permissions);
          } elseif (is_string($permissions) && !empty($permissions)) {
            $count += 1;
          }
        }
      }
    }
    return $count;
  }
}

if (!function_exists('count_active_permissions_other')) {
  function count_active_permissions_other($data)
  {
    $count = 0;
    if (empty($data) || !is_array($data))
      return 0;
    foreach ($data as $permName => $status) {
      $hasAccess = false;
      if (is_array($status) && count($status) > 0) {
        $hasAccess = in_array('ACCESS', $status) || count($status) > 0;
      } elseif (is_string($status)) {
        $hasAccess = ($status === 'ACCESS' || strlen($status) > 0);
      }
      if ($hasAccess) {
        $count++;
      }
    }
    return $count;
  }
}

$count_data = count_active_permissions($data);
$count_transaksi = count_active_permissions($transaksi);
$count_other = count_active_permissions_other($other);
?>
<!-- Info Employee & Cabang -->
<div class="well well-sm"
  style="background-color: #f9f9f9; border-left: 3px solid #337ab7; margin-bottom: 15px; padding: 10px 15px;">
  <div style="font-size: 14px; font-weight: bold; color: #337ab7;">
    <span class="copy-clickable" data-copy-text="<?= htmlspecialchars($employee_login_name) ?>" style="cursor: pointer;"
      data-toggle="tooltip" title="Klik untuk menyalin ID Login (<?= htmlspecialchars($employee_login_name) ?>)">
      <i class="fa fa-user"></i>
      <?= htmlspecialchars($employee_name) ?> <span class="text-muted text-renggang-5">@<?= htmlspecialchars($employee_login_name) ?></span>
    </span>
  </div>
  <div style="font-size: 12px; margin-top: 5px; color: #666;">
    <i class="fa fa-map-marker" style="color: #d9534f; margin-right: 3px;"></i> <strong>Cabang Terelasi:</strong>
    <?php if (empty($branches)): ?>
      <span class="text-muted">Tidak ada cabang terelasi</span>
    <?php else: ?>
      <?php foreach ($branches as $branch): ?>
        <span class="label label-primary"
          style="font-size: 11px; margin-right: 5px; display: inline-block; padding: 3px 6px;"><?= htmlspecialchars($branch) ?></span>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<!-- Nav Tabs -->
<ul class="nav nav-tabs" role="tablist">
  <li class="active">
    <a href="#tab-data" data-toggle="tab">
      DATA
      <span class="badge"
        style="<?= $count_data > 0 ? 'background-color: #5cb85c; color: #fff;' : 'background-color: #777; color: #fff;' ?> margin-left: 5px;"><?= $count_data ?></span>
    </a>
  </li>
  <li>
    <a href="#tab-transaksi" data-toggle="tab">
      TRANSAKSIONAL
      <span class="badge"
        style="<?= $count_transaksi > 0 ? 'background-color: #5cb85c; color: #fff;' : 'background-color: #777; color: #fff;' ?> margin-left: 5px;"><?= $count_transaksi ?></span>
    </a>
  </li>
  <li>
    <a href="#tab-other" data-toggle="tab">
      LAIN-LAIN
      <span class="badge"
        style="<?= $count_other > 0 ? 'background-color: #5cb85c; color: #fff;' : 'background-color: #777; color: #fff;' ?> margin-left: 5px;"><?= $count_other ?></span>
    </a>
  </li>
</ul>

<!-- Tab Content -->
<div class="tab-content" style="padding-top:15px;">
  <div id="tab-data" class="tab-pane active">
    <div class="alert alert-info" style="padding:8px 12px; margin-bottom:12px; font-size:12px;">
      <i class="fa fa-database"></i> <strong>Tab DATA:</strong> Hak akses untuk modul master data (employee, produk,
      bank, dll).
    </div>
    <?= render_permission_table($data, 'DATA') ?>
  </div>
  <div id="tab-transaksi" class="tab-pane">
    <div class="alert alert-info" style="padding:8px 12px; margin-bottom:12px; font-size:12px;">
      <i class="fa fa-exchange"></i> <strong>Tab TRANSAKSIONAL:</strong> Hak akses untuk modul transaksi (purchasing,
      distribution, banking, dll).
    </div>
    <?= render_permission_table($transaksi, 'TRANSAKSIONAL') ?>
  </div>
  <div id="tab-other" class="tab-pane">
    <div class="alert alert-info" style="padding:8px 12px; margin-bottom:12px; font-size:12px;">
      <i class="fa fa-list"></i> <strong>Tab LAIN-LAIN:</strong> Hak akses khusus untuk laporan, export, dan fitur bebas
      lainnya.
    </div>
    <?= render_permission_other_table($other, 'LAIN-LAIN') ?>
  </div>
</div>

<script>
  setTimeout(function () {
    $('[data-toggle="tooltip"]').tooltip({
      container: 'body',
      html: true
    });
  }, 100);

  // Copy to clipboard function when clicking employee name
  $(document).off('click', '.copy-clickable').on('click', '.copy-clickable', function () {
    var textToCopy = $(this).attr('data-copy-text');
    var $this = $(this);

    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(textToCopy).then(showSuccess, showError);
    } else {
      // Fallback method
      var $temp = $('<input>');
      $('body').append($temp);
      $temp.val(textToCopy).select();
      document.execCommand('copy');
      $temp.remove();
      showSuccess();
    }

    function showSuccess() {
      var originalTitle = $this.attr('data-original-title') || $this.attr('title');
      $this.attr('title', 'ID Login Tersalin!').tooltip('fixTitle').tooltip('show');
      setTimeout(function () {
        $this.attr('title', originalTitle).tooltip('fixTitle');
      }, 1500);
    }

    function showError() {
      alert('Gagal menyalin teks ke clipboard');
    }
  });
</script>