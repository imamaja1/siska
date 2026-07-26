<?= $this->session->flashdata('info_reset_berhasil') ?>
<div class="box box-solid flat">
    <div class="box-body">
        <a id="kembali" href="<?= site_url('admin/jurusan/dosen'); ?>"
           class="btn btn-sm btn-success flat"><i
                class="fa fa-arrow-left"></i> Kembali</a>
        <a class="btn btn-info btn-sm flat" href="javascript:printDiv('krs');"><i class="fa fa-print"></i> Cetak Sandi
            Baru</a>
    </div>
</div>
<div class="box box-solid flat" id="krs">
    <div class="box-body">
        <table class="table demo-table">
            <thead>
                <tr>
                    <th id="th">No.</th>
                    <th id="th">NIK</th>
                    <th id="th">Nama Dosen</th>
                    <th id="th">Status Dosen</th>
                    <th id="th">Status Login</th>
                    <th id="th">Alamat Email</th>
                    <th id="th">Sandi Baru</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($data_dosen as $d) {
                    ?>
                    <tr>
                        <td align="center"><?= $i++; ?>.</td>
                        <td align="center" id="nik-<?= $d->kode_dosen ?>"><?= $d->nik ?></td>
                        <td id="nama-dosen-<?= $d->kode_dosen ?>"><?= $d->nama_dosen ?></td>
                        <td style="text-align: center;"><?php
                            $status_dosen = $d->status_dosen;
                            if ($status_dosen == "T") {
                                echo "Tetap";
                            } else {
                                echo "Tidak Tetap";
                            }
                            ?></td>
                        <td style="text-align: center;"><?php
                            $status_login = $d->status_login;
                            if ($status_login == "A") {
                                echo "Aktif";
                            } else {
                                echo "Tidak Aktif";
                            }
                            ?></td>
                        <td><?= $d->alamat_email ?></td>
                        <td align="center" style='font-size: 17px;'><b><?= $kirim_string; ?></b></td>
                    </tr>
                <?php } ?>

            </tbody>
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