<div class="box box-primary flat">
    <div class="box-header with-border">
        <h4 class="box-title"><i class="fa fa-calendar"></i><strong>
                TA. <?= e($tahun_akademik->tahun_akademik) ?> <?= e($tahun_akademik->semester == 0 ? "GENAP" : "GANJIL") ?></strong>
            (<?= e($prodi->singkatan_program_studi) ?> - <?= e(strtoupper($prodi->nama_program_studi)) ?>)</h4>
        <div class="box-tools pull-right">
            <!--            <a href="-->
            <? //= site_url('admin/laporan/rekap_ipk/cetak') ?><!--" class="btn btn-danger btn-sm flat"><i class="fa fa-file-excel-o"></i> Export</a>-->
            <a href="<?= site_url('dosen/kaprodi/ipk/cetak_new') ?>" class="btn btn-success btn-sm flat"><i
                        class="fa fa-file-excel-o"></i> Export</a>
        </div>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table demo-table data-table">
                <thead>
                <tr>
                    <th class="no-sort">NO</th>
                    <th>NIM</th>
                    <th>NAMA</th>
                    <th>SKS (SEMESTER)</th>
                    <th>IP (SEMESTER)</th>
                    <th>IPK</th>
                    <th>SKS TOTAL</th>
                    <th class="no-sort">#</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = 1;
                $total_ipk = 0;
                foreach ($data as $row) : ?>
                    <tr>
                        <td width="3%" align="center"><?= $i++ ?>.</td>
                        <td align="center"><?= e($row['nim']) ?></td>
                        <td><?= e($row['nama_mahasiswa']) ?></td>
                        <td align="center"><?= e($row['sks']) ?></td>
                        <td align="center"><?= e($row['ip']) ?></td>
                        <td align="center"><?= e($row['ipk']) ?></td>
                        <td align="center"><?= e($row['total_sks']) ?></td>
                        <td align="center">
                            <a href="#" onclick="grafik('<?= e($row['nim']) ?>')" class="btn btn-warning btn-xs flat"><i
                                        class="fa fa-line-chart"></i> Grafik Nilai</a>
                        </td>
                    </tr>
                <?php 
                  	$total_ipk += $row['ipk']; 
					endforeach; 
                  ?>
                </tbody>
            </table>
            </div>
      <?php if (count($data) > 0): ?>
            <h4 class="text-black" style="margin-bottom: 20px;">
                Rata - Rata IPK : <b><?= number_format($total_ipk / $jumlah_data, 2) ?></b>
            </h4>
        <?php endif; ?>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-grafik" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content" id="content-grafik" style="border-radius: 10px">
        </div>
    </div>
</div>
<script>
    function grafik(id) {
        var url = "<?= site_url('dosen/kaprodi/konsultasi_perwalian/grafik_nilai') ?>/" + id;
        $.ajax({
            url: url,
            success: function (res) {
                $('#content-grafik').html(res);
                $("#modal-grafik").modal('show');
            },
        })
    }
  $('.data-table').DataTable({
        "overflowX": true,
        columnDefs: [
            {targets: 'no-sort', orderable: false}
        ]
    });
</script>