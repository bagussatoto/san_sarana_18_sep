<!-- Header access modal: list users with access for a specific header/module -->
<div id="headerAccessModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Hak Akses Header</h4>
        <p style="margin:5px 0 0 0; font-size:13px; color:#555;">
          <i class="fa fa-info-circle" style="color:#31708f;"></i>
          Daftar user yang memiliki akses pada header yang dipilih.
        </p>
      </div>
      <div class="modal-body">
        <h5 id="modal-header-title" class="text-primary"><i class="fa fa-spinner fa-spin"></i> Loading...</h5>
        <hr>
        <div id="header-access-content">
          <p class="text-center text-muted"><i class="fa fa-spinner fa-spin"></i> Memuat data...</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
