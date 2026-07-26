<table width="100%">
    <tr>
        <td colspan="2" align="right">BG/BAA/QSR/004-01/010</td>
    </tr>
    <tr>
        <td width="85%" style="text-align: center ;vertical-align: bottom; font-family:serif; font-size: 8pt;">
            <!--<img style="height: 120px;" src="<?= base_url('assets/gambar/kop/'.$prodi->kode_fakultas.'.png'); ?>">-->
            <img style="height: 120px;" src="<?= base_url('assets/gambar/kop/'.bodo_kop($krs_mahasiswa->nim)['kop']); ?>">
        </td>
        <td width="15%">
            <table style=" width:80px;">
                <tr>
<!--                    <td align="center" height="100px" valign="middle"><strong>FOTO<br />3 x 4</strong>-->
<!--                    </td>-->
                    <td align="center" height="120px" valign="middle">
                        <?php if (empty($krs_mahasiswa->foto)) : ?>
                            <img src="<?= base_url('assets/foto/default.png') ?>" style="height: 120px" alt="">
                        <?php else: ?>
                            <img src="<?= base_url('assets/foto/'.$krs_mahasiswa->foto) ?>" style="height: 120px;" alt="">
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
