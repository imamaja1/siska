<div class="callout callout-danger">
    <p>Mohon Maaf, (1) tindakan memasukan nilai UTS dari tanggal 
        <b style="background-color: blue;"> <?= tgl_indo($time->tgl_awal_uts); ?> <?= date('h:i:s A', strtotime($time->tgl_awal_uts)) ?> - <?= tgl_indo($time->tgl_akhir_uts); ?> <?= date('h:i:s A', strtotime($time->tgl_akhir_uts)) ?> </b> 
        sudah berakhir, (2) namun Bapak/Ibu masih bisa memasukan/memperbaharui nilai UTS (3) tapi proses validasi dilakukan secara manual, sebelum divalidasi Bapak/Ibu harus memasukan/memperbaharui, mengunduh dan mencetak validasi yang statusnya <span class="label label-default"><i class="fa fa-remove"></i> telat</span> (4) kemudian Bapak/Ibu harus meminta tanda tangan Kaprodi dan Dekan, (5) setelah itu Bapak/Ibu menyerahkan nilai tersebut ke akademik untuk dibantu mengaktifkan validasinya, (6) bagi Bapak/Ibu yang sudah memasukan nilai UTS dan atau sedang revisi maka kami masih memberikan toleransi waktu 2 hari dari tanggal 
        <b style="background-color: blue;"> <?= tgl_indo($time->tgl_akhir_uts); ?> <?= date('h:i:s A', strtotime($time->tgl_akhir_uts)) ?> - <?= tgl_indo(date('Y-m-d', strtotime('+2 days', strtotime($time->tgl_akhir_uts)))); ?> <?= date('h:i:s A', strtotime($time->tgl_akhir_uts)) ?> </b> 
    apabila lewat dari tanggal tersebut maka Bapak/Ibu bisa menggunakan cara nomor (2) sampai (5).</p> 
</div>
<table class="table table-bordered demo-table">
    <thead>
        <tr>
            <th rowspan="2" style="white-space: nowrap;width: 1px; text-align: center">Kode</th>
            <th rowspan="2">PRODI</th>
            <th rowspan="2">Matakuliah</th>
            <th rowspan="2" style="text-align: center;">SMT</th>
            <th rowspan="2" style="white-space: nowrap;width: 1px;">Dosen</th>
            <th colspan="2"><center>Validasi</center></th>
            <th rowspan="2" style="text-align: center;">Tindakan</th>
            <th rowspan="2" style="text-align: center;">Pesan</th>
        </tr>
        <tr>
            <th style="white-space: nowrap;width: 1px;">Prodi</th>
            <th style="white-space: nowrap;width: 1px;">Dekan</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($data as $key => $row) :
            ?>
            <tr>

                <td align="center"><?= e($row->kelas_id) ?></td>
                <td><?= e($row->singkatan_program_studi) ?></td>
                <td><?= e($row->kode_matakuliah) ?> - <?= e($row->nama_matakuliah) ?> Kelas - <?= e($row->nama_kelas) ?></td>
                <td align="center"><?= e($row->semester) ?></td>
                <td>
                    <?php 
                    if($row->param_uts == "1") {
                        echo nilai_validasi_telat($row->status_nilai_uts);
                    }else{
                        echo nilai_validasi($row->status_nilai_uts);
                    }
                    ?>
                </td>
                <td>
                    <?php 
                    if($row->param_uts == "1") {
                        echo nilai_validasi_telat($row->validasi_nilai_uts);
                    }else{
                        echo nilai_validasi($row->validasi_nilai_uts);
                    }
                    ?>
                </td>
                <td>
                    <?php 
                    if($row->param_uts == "1") {
                        echo nilai_validasi_telat ($row->validasi_dekan_uts);
                    }else{
                        echo nilai_validasi($row->validasi_dekan_uts);
                    }
                    ?>
                </td>
                <td style="white-space: nowrap;width: 1px;">
                    <a class="btn btn-<?= e($row->status_nilai_uts != 'T' ? 'warning' : 'info') ?> btn-xs btn-flat" href="<?= site_url('dosen/penilaian/nilai_mahasiswa_uts/' . $row->kelas_id) ?>"> <i class="fa fa-arrow-circle-right"></i>
                        <?= ($row->param_uts == '1') && ($row->status_nilai_uts != 'T')  ? 'Update Nilai' : ($row->status_nilai_uts == 'T' ? 'Lihat Nilai' : ($row->status_revisi_uts=='1' ? 'Update Nilai':'Isi Nilai')); ?>
                    </a>

                    <?php if (($row->status_nilai_uts == "T") && ($row->validasi_nilai_uts == "T") && ($row->validasi_dekan_uts == "T")): ?>
                        <?php if ($row->param_uts == "1" && $row->valid_uts == '2' || $row->param_uts != "1"): ?>
                            <a href="<?= site_url('dosen/penilaian/cetak_validasi_uts/' . $row->kelas_id) ?>" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-print"></i> Cetak</a>
                        <?php else: ?>
                            <a href="<?= site_url('dosen/penilaian/cetak_validasi_not_valid_uts/' . $row->kelas_id) ?>" class="btn btn-info btn-xs btn-flat"><i class="fa fa-print"></i> Cetak</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
            <td style="white-space: nowrap;width: 1px;">
                <button id="pesan_prodi_<?= e($row->kelas_id) ?>" onclick="pesan_uts_prodi('<?= e($row->kelas_id) ?>')" class="btn btn-xs
                    btn-primary btn-flat badge-notif" 
                    <?php
                    if ($pesan_prodi[$key] != 0) {
                        echo 'data-badge="' . $pesan_prodi[$key] . '"';
                    }
                    ?> 
                    ><i class="fa fa-envelope"></i> Kaprodi</button>
                    <button id="pesan_dekan_<?= e($row->kelas_id) ?>" onclick="pesan_uts_dekan('<?= e($row->kelas_id) ?>')" class="btn btn-xs btn-flat btn-primary badge-notif" 
                        <?php
                        if ($pesan_dekan[$key] != 0) {
                            echo 'data-badge="' . $pesan_dekan[$key] . '"';
                        }
                        ?> 
                        ><i class="fa fa-envelope"></i> Dekan</button>


                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>