<?php if ($this->session->flashdata('info')): ?>
    <?= $this->session->flashdata('info') ?>
<?php else: ?>
    <div class="callout callout-danger flat">
        <h4><i class="fa fa-ban"></i> Akses Ditolak</h4>
        <p>Anda tidak memiliki izin untuk mengakses halaman ini.</p>
    </div>
<?php endif; ?>