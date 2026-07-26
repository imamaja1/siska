<div class="box box-solid">
<!--    <div class="box-header">-->
<!--        <h4></h4>-->
<!--    </div>-->
    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th style="text-align: center">NO.</th>
                    <th style="text-align: center">NAMA MAHASISWA</th>
                    <th style="text-align: center">PEMBAYARAN</th>
                    <th style="text-align: center">TGL. BAYAR</th>
                    <th style="text-align: center">NOMINAL</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $key => $row) : ?>
                    <tr>
                        <td style="text-align: center"><?= $key+1 ?>.</td>
                        <td><?= e($row->nama_mahasiswa) ?></td>
                        <td><?= e($row->nama_pembayaran) ?></td>
                        <td><?= date('d M Y', strtotime($row->tgl_pembayaran)) ?></td>
                        <td style="text-align: right"><?= number_format($row->nominal_pembayaran,0) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
