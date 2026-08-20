<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/akademik/kpat/nilai') ?>" class="btn btn-success btn-sm flat"><i class="fa fa-arrow-left"></i> Kembali</a>
    </div>
</div>
<?php if (count($data) > 0) : ?>
<!--    <div class="callout callout-info flat alert alert-info alert-dismissible">-->
<!--        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>-->
<!--        <h4><i class="fa fa-info-circle"></i> Informasi!</h4>-->
<!---->
<!--        <p>Untuk penilaian <strong>KPAT</strong> silakan <strong>Klik 2 X </strong>pada kolom yang akan di siskan nilai. Contoh (Nilai harian, Nilai UTS, Nilai UAS). Kemudian tekan tombol <strong>ENTER</strong> untuk menyimpan peruabah</p>-->
<!--    </div>-->
    <div class="box box-primary flat">
        <div class="box-body">
            <div class="table-responsive">
                <table class="table demo-table" id="table-edit">
                    <thead>
                        <tr>
                            <th id="th" width="20">No.</th>
                            <th id="th">NIM</th>
                            <th id="th">Nama</th>
                            <th id="th">Nilai Harian</th>
                            <th id="th">Nilai UTS</th>
                            <th id="th">Nilai UAS</th>
                            <th id="th">Nilai Akhir</th>
                            <th id="th" width="35">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1;
                        foreach ($data as $row) :
                            ?>
                            <tr>
                                <td style="text-align: center;"><?= $i++ . "." ?></td>
                                <td style="display:none;"><?= e($row->kode_khs_detail) ?></td>
                                <td style="text-align: center;"><?= e($row->nim) ?></td>
                                <td><?= e($row->nama_mahasiswa) ?></td>
                                <td style="text-align:center;"><?= e($row->nilai_harian) ?></td>
                                <td style="text-align:center;"><?= e($row->nilai_uts) ?></td>
                                <td style="text-align:center;"><?= e($row->nilai_uas) ?></td>
                                <td style="text-align:center;"><?= e(number_format($row->nilai_akhir,2)) ?></td>
                                <td style="text-align: center;">-</td>
                            </tr>
    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="callout callout-warning">
        <h4><i class="fa fa-warning"></i> Peringatan!</h4>

        <p>Data tidak ditemukan.</p>
    </div>

<?php endif; ?>
<script type="text/javascript">
    $('#table-edit').Tabledit({
        url: "<?= site_url('admin/akademik/kpat/nilai/ubah_nilai') ?>",
        hideIdentifier: true,
//        eventType: 'dblclick',
        editButton: true,
        deleteButton: false,
        columns: {
            identifier: [1, 'kode_khs_detail'],
            editable: [[4, 'nilai_harian'], [5, 'nilai_uts'], [6, 'nilai_uas'], [7, 'nilai_akhir']],
        }
    });
</script>