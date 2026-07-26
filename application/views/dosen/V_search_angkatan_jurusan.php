<div class="box box-solid flat">
    <div class="box-body">
        <a class="btn-sm btn-success flat" href="<?= site_url('dosen/perwalian'); ?>"><i class="fa fa-arrow-left"></i> Kembali</a>
    </div>
</div>
<?php if (count($perwalian) > 0) : ?>
    <div class="box box-primary flat">
        <div class="box-body">
            <div class="table-responsive">
                <table class="table demo-table">
                    <thead>
                        <tr>
                            <th style="text-align: center;">NO</th>
                            <th style="text-align: center;">NIM</th>
                            <th style="text-align: center;">NAMA MAHASISWA</th>
                            <th style="text-align: center;">DATA AKADEMIK</th>
                            <th style="text-align: center;">STATUS PERKULIAHAN</th>
                        </tr>
                    </thead>
                    <?php
                    $no = 1;
                    foreach ($perwalian as $row) {
                        ?>
                        <tr>
                            <td align="center"><?= e($no++) ?></td>
                            <td align="center"><?= e($row->nim) ?></td>
                            <td><?= e($row->nama_mahasiswa) ?></td>
                            <?php
                            switch ($row->status_perkuliahan) {
                                case 'A':
                                    $status_perkuliahan = 'AKTIF';
                                    break;
                                case 'C':
                                    $status_perkuliahan = 'CUTI';
                                    break;
                                case 'T':
                                    $status_perkuliahan = 'TANPA KETERANGAN';
                                    break;
                                case 'B':
                                    $status_perkuliahan = 'BERHENTI';
                                    break;
                                case 'P':
                                    $status_perkuliahan = 'PINDAH/TRANSFER';
                                    break;
                                default:
                                    $status_perkuliahan = '-';
                            }
                            ?>
                            <td align="center">
                                <?php
                                echo anchor_popup('dosen/Konsultasi_perwalian/biodata_mahasiswa/' . $row->nim, '<i class="fa fa-user"></i> Biodata', array('class' => 'btn-xs btn btn-default')) . ' ' . anchor_popup('dosen/konsultasi_perwalian/krs_mahasiswa/' . $row->nim, '<i class="fa fa-file"></i> KRS', array('class' => 'btn-xs btn btn-default')) . ' ' . anchor_popup('dosen/konsultasi_perwalian/khs_mahasiswa/' . $row->nim, '<i class="fa fa-file"></i> KHS Semester Lalu', array('class' => 'btn-xs btn btn-default')) . ' ' . anchor_popup('dosen/konsultasi_perwalian/petikan_nilai_mahasiswa/' . $row->nim, '<i class="fa fa-clipboard"></i> Petikan Nilai', array('class' => 'btn-xs btn btn-default'));
                                ?>

                            <td align="center"><?= e($status_perkuliahan) ?></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
<?php else : ?>
    <div class="callout callout-success flat">
        <p>Data Mahasiswa tidak ditemukan</p>
    </div>
<?php endif; ?>
