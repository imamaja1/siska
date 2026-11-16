<?php
$kop_nama = !empty($kop_file) ? $kop_file : (function_exists('bodo_kop') && !empty($mahasiswa->nim) ? (bodo_kop($mahasiswa->nim)['kop'] ?? '') : '');
?>
<table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse; margin-bottom: 0;">
    <tr>
        <td align="right" style="font-size: 7.5pt; font-family: sans-serif; color: #333333; padding-bottom: 2px;">BG/BAA/QSR/032-00/09</td>
    </tr>
    <tr>
        <td style="vertical-align: middle; text-align: center; padding: 0;">
            <img style="max-width: 100%; max-height: 110px; height: auto; display: inline-block; margin: 0 auto;" src="<?= e(base_url('assets/gambar/kop/'.rawurlencode($kop_nama))) ?>" alt="Kop Surat">
        </td>
    </tr>
    <tr>
        <td style="padding-top: 4px; padding-bottom: 0;">
            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                <tr><td style="border-top: 0.75px solid #000000; height: 1.5px; font-size: 1px; line-height: 1px; padding: 0;"></td></tr>
                <tr><td style="border-top: 2.5px solid #000000; height: 0px; font-size: 1px; line-height: 1px; padding: 0;"></td></tr>
            </table>
        </td>
    </tr>
</table>

