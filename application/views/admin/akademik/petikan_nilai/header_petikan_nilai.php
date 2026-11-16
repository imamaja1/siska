<?php
$kop_nama = !empty($kop_file) ? $kop_file : (function_exists('bodo_kop') && !empty($mahasiswa->nim) ? (bodo_kop($mahasiswa->nim)['kop'] ?? '') : '');
?>
<table width="100%" style="border-collapse: collapse; margin-bottom: 0;">
    <tr>
        <td align="right" style="font-size: 8pt; padding-bottom: 2px;">BG/BAA/QSR/032-00/09</td>
    </tr>
    <tr>
        <td style="vertical-align: bottom; text-align: left; padding: 0;">
            <img style="width: 100%; height: auto; display: block;" src="<?= e(base_url('assets/gambar/kop/'.rawurlencode($kop_nama))) ?>">
        </td>
    </tr>
</table>
