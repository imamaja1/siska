<?= $this->session->flashdata('pesan') ?>

<div class="row">
    <div class="col-md-6">
        <div class="box box-primary flat">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-download"></i> Export Database</h3>
            </div>
            <div class="box-body">
                <p>Unduh backup penuh seluruh database SISKA (struktur + data) dalam format <code>.sql</code>.</p>
                <a href="<?= site_url('admin/pengaturan/backup_database/export'); ?>" class="btn btn-primary flat">
                    <i class="fa fa-download"></i> Download Backup Database
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="box box-warning flat">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-upload"></i> Import Database</h3>
            </div>
            <div class="box-body">
                <div class="alert alert-danger">
                    <i class="fa fa-warning"></i> <strong>Perhatian!</strong> Import akan menimpa data pada database saat ini. Sangat disarankan untuk melakukan <strong>Export</strong> terlebih dahulu.
                </div>
                <form action="<?= site_url('admin/pengaturan/backup_database/import'); ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <div class="form-group">
                        <label class="control-label">File Backup (.sql) :</label>
                        <input class="form-control" type="file" name="file_backup" accept=".sql" required>
                        <small class="text-muted">Maksimal ukuran file 50 MB.</small>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-warning flat" onclick="return confirm('Yakin ingin mengimport file ini? Seluruh data saat ini akan ditimpa.');">
                            <i class="fa fa-upload"></i> Import Database
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>