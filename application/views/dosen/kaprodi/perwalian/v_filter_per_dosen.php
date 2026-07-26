<?= $this->session->flashdata('info') ?>

<?php if (count($data) > 0) : ?>
    <div class="box box-solid flat">
        <div class="box-body">
            <a href="<?= site_url('dosen/kaprodi/Konsultasi_perwalian/rekap_dosen_wali/' . $kode_dosen) ?>"
               class="btn-xs btn  btn-success"><i class="fa fa-file-excel-o"></i> Export Perwalian
                : <?= $nama_dosen ?></a>
            <span class="badge bg-aqua pull-right">Nama Dosen : <b><?= $nama_dosen ?></b></span>
        </div>
    </div>
    <div class="box box-solid flat">
        <div class="box-body">
            <table class="table demo-table data-table" id="example1">
                <thead>
                <tr>
                    <th id="color" width="20" style="text-align: center;">No.</th>
                    <th id="color" style="text-align: center;">NIM</th>
                    <th id="color" style="text-align: center;">Nama Mahsaiswa</th>
                    <th id="color" style="text-align: center;">Dosen Perwakilan</th>
                    <th id="color" style="text-align: center;">Tgl Update</th>
                </tr>
                </thead>
                <tbody>
                <?php $i = 1;
                foreach ($data as $row) : ?>
                    <tr>
                        <td style="text-align: center;"><?= $i++ ?>.</td>
                        <td style="text-align: center;"><?= $row->nim ?></td>
                        <td><?= $row->nama_mahasiswa ?></td>
                        <td class="dosen-perwakilan">
                            <?php if ($row->kode_dosen_perwakilan) : ?>
                                <?= $this->m_dosen->get_nama($row->kode_dosen_perwakilan) ?>&nbsp;
                            <?php endif; ?>
                        </td>
                        <td style="text-align: center;"><?= date('d M Y H:i', strtotime($row->date_created)) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-info alert-dismissible flat">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-warning"></i> Info!</h4>
        Data Tidak ditemukan...
    </div>
<?php endif; ?>

<script>
    $(".data-table").dataTable({
        "ordering": true,
        "info": true,
        "pageLength": 25
    });
</script>
