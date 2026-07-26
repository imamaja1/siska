<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">TA. <?= tahun_akademik()->tahun_akademik ?> <?= tahun_akademik()->semester == 0 ? 'GENAP' : 'GANJIL'?> / Podi <?= $prodi->nama_program_studi ?></h3>
        <div class="box-tools">
            <a href="<?= site_url('admin/akademik/status_perkuliahan/excel_rekap/'.$kode_program_studi) ?>" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Excel</a>
        </div>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table demo-table">
                <thead>
                <tr>
                    <th style="text-align: center">NO.</th>
                    <th style="text-align: center">Tahun Angkatan</th>
                    <th style="text-align: center">Laki-Laki</th>
                    <th style="text-align: center">Perempuan</th>
                    <th style="text-align: center">Total</th>
                </tr>
                </thead>
               <tbody>
                <?php $total = 0 ; $total_x = 0; $total_y = 0; $no = 1;
                foreach ($data as $row) : ?>
                <tr>
                    <td style="text-align: center"><?= $no++ ?>.</td>
                    <td style="text-align: center"><a href="#" onclick="detail('<?= $row['angkatan'] ?>')"><?= $row['angkatan'] ?></a></td>
                    <td style="text-align: center"><?= $row['laki']  ?></td>
                    <td style="text-align: center"><?= $row['perempuan'] ?></td>
                    <td style="text-align: center"><?= $row['total'] ?></td>
                </tr>
                <?php
                $total =  $total + $row['total'];
                $total_x =  $total_x + $row['laki'];
                $total_y =  $total_y + $row['perempuan'];
                endforeach; ?>
               <tr>
                   <td colspan="2" style="text-align: center"><b>Total Keseluruhan Mahasiswa Aktif</b></td>
                   <td style="text-align: center; font-weight: bold"><?= $total_x ?></td>
                   <td style="text-align: center; font-weight: bold"><?= $total_y ?></td>
                   <td style="text-align: center; font-weight: bold"><?= $total ?></td>
               </tr>
               </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function detail(angkatan) {
        var url = "<?= site_url('admin/akademik/status_perkuliahan/list_rekap') ?>/"+angkatan+"/"+kode_program_studi;
        window.open(url, '_blank');
    }
</script>