<?= $this->session->flashdata('info_reset_berhasil') ?>
<div class="box box-solid flat">
    <div class="box-body">
        <a id="kembali" href="<?= site_url('admin/akademik/mahasiswa/reset_sandi'); ?>"
           class="btn btn-xs btn-success flat"><i
                    class="fa fa-arrow-left"></i> Kembali</a>
        <a class="btn btn-info btn-xs flat" href="javascript:printDiv('krs');"><i class="fa fa-print"></i> Cetak Sandi
            Baru</a>
    </div>
</div>
<div class="box box-solid flat" id="krs">
    <div class="box-body">
        <table class="table demo-table">
            <thead>
            <tr>
                <th id="color" style="text-align: center;">NIM</th>
                <th id="color" style="text-align: center;">NAMA MAHASISWA</th>
                <th id="color" style="text-align: center;">EMAIL</th>
                <th id="color" style="text-align: center;">SANDI BARU</th>
            </tr>
            </thead>
            <?php if (isset($data_mahasiswa) && $data_mahasiswa) : ?>
                <tr>
                    <td align="center"><?= e($data_mahasiswa->nim); ?></td>
                    <td align="center"><?= e($data_mahasiswa->nama_mahasiswa); ?></td>
                    <td align="center"><?= e($data_mahasiswa->email); ?></td>
                    <td align="center" style='font-size: 17px;'><b><?= e($kirim_string); ?></b></td>
                </tr>
            <?php endif; ?>
        </table>
    </div>

</div>

<script type="text/javascript">
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
