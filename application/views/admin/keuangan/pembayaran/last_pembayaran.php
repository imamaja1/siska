<div class="box box-danger">
    <div class="box-header">
        <h4><i class="fa fa-history"></i> Pembayaran Terakhir</h4>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table demo-table datatable">
                <thead>
                <tr>
                    <th style="text-align: center">No.</th>
                    <th style="text-align: center">NIM</th>
                    <th style="text-align: center">NAMA</th>
                    <th style="text-align: center">Pembayaran</th>
                    <th style="text-align: center">Nominal</th>
                    <th style="text-align: center">Rek. Pembayaran</th>
                    <th style="text-align: center">Jml. SKS</th>
                    <th style="text-align: center">Tgl. Bayar</th>
                    <th style="text-align: center">#</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($pembayaran as $key => $row) : ?>
                <tr>
                    <td style="text-align: center"><?= $key + 1 ?>.</td>
                    <td style="text-align: center"><?= $row->nim ?></td>
                    <td><?= $row->nama_mahasiswa ?></td>
                    <td><?= $row->nama_pembayaran ?></td>
                    <td style="text-align: right"><?= number_format($row->nominal_pembayaran,0,',','.')  ?></td>
                    <td><?= $row->nama_rek ?></td>
                    <td><?= $row->jml_sks ?></td>
                    <td><?= date('d M Y',strtotime($row->tgl_pembayaran)) ?></td>
                    <td style="text-align: center">
                        <a href="#" onclick="hapus('<?= $row->pembayaran_id ?>')" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(".datatable").dataTable({
        "ordering": false,
        "info": false,
        "pageLength": 20
    });
</script>