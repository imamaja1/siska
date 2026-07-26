<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$file_name.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<p><img src="<?= base_url('assets/gambar/header_krs.png') ?>" alt=""><p align="right"><strong>BG/BAA/QSR/007-00/09</strong></p>
<hr size="2"></p>
<p align="center">HASIL KUISIONER SEMESTER <?= $tahun_akademik->semester == 1 ? 'GANJIL' : 'GENAP' ?> TAHUN AKADEMIK <?= $tahun_akademik->ta ?></p>

<table style="font-family: 'Arial Narrow','Arial';">
    <tr>
        <td colspan="2">Nama Dosen </td>
        <td colspan="7"> : <?php foreach ($top['dosen'] as $row) :?>
                 <?= $row->nama_dosen ?> /
            <?php endforeach;?></td>
    </tr>
    <tr>
        <td colspan="2">Kelas</td>
        <td colspan="7"> : <?= $nama_kelas ?></td>
    </tr>
    <tr>
        <td colspan="2">Matakuliah</td>
        <td colspan="7"> : <?= $top['nama_matakuliah']->nama_matakuliah ?></td>
    </tr>
    <tr><td></td></tr>
<!--    CEK MATAKULIAH PRAKTIKUM DAN TEORI-->
    <?php if (isset($data['hasil']['P'])) : ?>
<!--        TABEL KUISIONER TEORI-->
        <thead>
        <tr>
            <th id="th" rowspan="2" style="border: 0.1pt solid black;">NO.</th>
            <?php $batas=0; foreach ($data['soal_kuisioner']['T'] as $key) : ?>
                <th id="th" colspan="<?= $key->colspan ?>" style="border: 0.1pt solid black;"><?= $key->kategori ?></th>
                <?php $batas = $batas + $key->colspan; ?>
            <?php endforeach;?>
            <th id="th" rowspan="2" style="border: 0.1pt solid black;">Skor</th>
        </tr>
        <tr>
            <?php foreach ($data['soal_kuisioner']['T'] as $row) : ?>
                <?php $count = $row->colspan+1;  for ($n=1; $n < $count; $n++) : ?>
                    <th style="width: 40px;" id="th" style="border: 0.1pt solid black;"><?=$n ?></th>
                <?php endfor; ?>
            <?php endforeach;?>
        </tr>
        </thead>
        <tbody>
        <?php $no=1; foreach ($data['hasil']['T'] as $item) :?>
            <tr>
                <td align="center" width="3%" style="border: 0.1pt solid black;"><?= $no++ ?>.</td>
                <?php $index = 0; foreach ($item as $val) :?>
                    <?php
                    if (($batas) == $index)
                    {
                        break;
                    }
                    ?>
                    <td align="center" style="border: 0.1pt solid black;"><?= $val->hasil ?></td>
                    <?php $pertahankan = $val->saran; $tingkatkan = $val->kritik; ?>
                    <?php
                    if (isset($total))
                    {
                        $jml[$index] = $total[$index] +  $val->hasil;
                    }else{
                        $jml[$index] = $val->hasil;
                    }
                    $index++;
                endforeach;
                $total = $jml;?>
                <td style="border: 0.1pt solid black;"> </td>
            </tr>
        <?php endforeach;?>
        <tr>
            <td align="center" style="background-color: #aed4e6;" style="border: 0.1pt solid black;" ><strong>Jumlah</strong> </td>
            <?php foreach ($total as $row => $value) : ?>
                <td align="center" style="background-color: #aed4e6;" style="border: 0.1pt solid black;"><strong><?= $value ?></strong></td>
            <?php endforeach; ?>
            <?php $total_rata = 0; foreach ($total as $key => $val){$total_rata = $total_rata + ($val / count($data['hasil']['T']));}  ?>
            <td rowspan="2" style="border: 0.1pt solid black;"><?= number_format($total_rata/ count($data['jumlah_soal']['T']),2) ?></td>
        </tr>
        <tr>
            <td align="center" style="background-color: #e6ddae;" style="border: 0.1pt solid black;"> <strong>Rata-rata</strong></td>
            <?php $jml_mah = count($data['hasil']['T']); foreach ($total as $key => $val) : ?>
                <td align="center" style="background-color: #e6ddae;" style="border: 0.1pt solid black;"><strong><?= number_format($val/$jml_mah, 1)?></strong></td>
            <?php endforeach; ?>
        </tr>
        </tbody>
        <tr><td></td></tr>
<!--        TABEL KUISIONER PRAKTIKUM-->
        <thead>
        <tr>
            <th id="th" rowspan="2" style="border: 0.1pt solid black;">NO.</th>
            <?php $batas2=0; foreach ($data['soal_kuisioner']['P'] as $key) : ?>
                <th id="th" colspan="<?= $key->colspan ?>" style="border: 0.1pt solid black;"><?= $key->kategori ?></th>
                <?php $batas2 = $batas2 + $key->colspan; ?>
            <?php endforeach;?>
            <th id="th" rowspan="2" style="border: 0.1pt solid black;">Skor</th>
        </tr>
        <tr>
            <?php foreach ($data['soal_kuisioner']['P'] as $row) : ?>
                <?php $count = $row->colspan+1;  for ($n=1; $n < $count; $n++) : ?>
                    <th style="width: 40px;" id="th" style="border: 0.1pt solid black;"><?=$n ?></th>
                <?php endfor; ?>
            <?php endforeach;?>
        </tr>
        </thead>
        <tbody>
        <?php $no=1; foreach ($data['hasil']['P'] as $item) :?>
            <tr>
                <td align="center" width="3%" style="border: 0.1pt solid black;"><?= $no++ ?>.</td>
                <?php $index = 0; foreach ($item as $val) :?>
                    <?php
                    if (($batas2) == $index)
                    {
                        break;
                    }
                    ?>
                    <td align="center" style="border: 0.1pt solid black;"><?= $val->hasil ?></td>
                    <?php $pertahankan = $val->saran; $tingkatkan = $val->kritik; ?>
                    <?php
                    if (isset($tot))
                    {
                        $jml[$index] = $tot[$index] +  $val->hasil;
                    }else{
                        $jml[$index] = $val->hasil;
                    }
                    $index++;
                endforeach;
                $tot = $jml;?>
                <td style="border: 0.1pt solid black;"></td>
            </tr>
        <?php endforeach;?>
        <tr>
            <td align="center" style="background-color: #aed4e6;" style="border: 0.1pt solid black;" ><strong>Jumlah</strong> </td>
            <?php foreach ($tot as $row => $value) : ?>
                <td align="center" style="background-color: #aed4e6;" style="border: 0.1pt solid black;"><strong><?= $value ?></strong></td>
            <?php endforeach; ?>
            <?php $total_rata = 0; foreach ($tot as $key => $val){$total_rata = $total_rata + ($val / count($data['hasil']['P']));}  ?>
            <td rowspan="2"  style="border: 0.1pt solid black;"><?= number_format($total_rata/ count($data['jumlah_soal']['P']),2) ?></td>
        </tr>
        <tr>
            <td align="center" style="background-color: #e6ddae;" style="border: 0.1pt solid black;"> <strong>Rata-rata</strong></td>
            <?php $jml_mah = count($data['hasil']['P']); foreach ($tot as $key => $val) : ?>
                <td align="center" style="background-color: #e6ddae;" style="border: 0.1pt solid black;"><strong><?= number_format($val/$jml_mah, 1)?></strong></td>
            <?php endforeach; ?>
        </tr>
        <tr><dt></dt></tr>
        <tr>
            <td colspan="3"><strong>Di tingkatkan</strong></td>
            <td colspan="10"> : <?= $tingkatkan ?></td>
        </tr>
        <tr>
            <td colspan="3"><strong>Di pethanakan</strong></td>
            <td colspan="10"> : <?= $pertahankan ?> </td>
        </tr>
        </tbody>
<!--        MATKULIAH TEORI SAJA-->
    <?php else: ?>
        <thead>
        <tr>
            <th id="th" rowspan="2" style="border: 0.1pt solid black;">NO.</th>
            <?php $batas3=0; foreach ($data['soal_kuisioner'] as $key) : ?>
                <th id="th" colspan="<?= $key->colspan ?>" style="border: 0.1pt solid black;"><?= $key->kategori ?></th>
                <?php $batas3 = $batas3 + $key->colspan; ?>
            <?php endforeach;?>
            <th id="th" rowspan="2" style="border: 0.1pt solid black;">Di tingkatkan</th>
            <th id="th" rowspan="2" style="border: 0.1pt solid black;">Di pertahankan</th>
        </tr>
        <tr>
            <?php foreach ($data['soal_kuisioner'] as $row) : ?>
                <?php $count = $row->colspan+1;  for ($n=1; $n < $count; $n++) : ?>
                    <th style="width: 40px;" id="th" style="border: 0.1pt solid black;"><?=$n ?></th>
                <?php endfor; ?>
            <?php endforeach;?>
        </tr>
        </thead>
        <tbody>
        <?php $no=1; foreach ($data['hasil'] as $item) :?>
            <tr>
                <td align="center" width="3%" style="border: 0.1pt solid black;"><?= $no++ ?>.</td>
                <?php $index = 0; foreach ($item as $val) :?>
                    <?php
                    if (($batas3) == $index)
                    {
                        break;
                    }
                    ?>
                    <td align="center" style="border: 0.1pt solid black;"><?= $val->hasil ?></td>
                    <?php $pertahankan = $val->saran; $tingkatkan = $val->kritik; ?>
                    <?php
                    if (isset($total))
                    {
                        $jml[$index] = $total[$index] +  $val->hasil;
                    }else{
                        $jml[$index] = $val->hasil;
                    }
                    $index++;
                endforeach;
                $total = $jml;?>
                <td style="border: 0.1pt solid black;"> <?= $pertahankan ?> </td>
                <td style="border: 0.1pt solid black;"> <?= $tingkatkan ?> </td>
            </tr>
        <?php endforeach;?>
        <tr>
            <td align="center" style="background-color: #aed4e6;" style="border: 0.1pt solid black;" ><strong>Jumlah</strong> </td>
            <?php foreach ($total as $row => $value) : ?>
                <td align="center" style="background-color: #aed4e6;" style="border: 0.1pt solid black;"><strong><?= $value ?></strong></td>
            <?php endforeach; ?>
            <?php $total_rata = 0; foreach ($total as $key => $val){$total_rata = $total_rata + ($val / count($data['hasil']));}  ?>
            <td rowspan="2" colspan="2" style="border: 0.1pt solid black;"><?= number_format($total_rata/ count($data['jumlah_soal']),2) ?></td>
        </tr>
        <tr>
            <td align="center" style="background-color: #e6ddae;" style="border: 0.1pt solid black;"> <strong>Rata-rata</strong></td>
            <?php $jml_mah = count($data['hasil']); foreach ($total as $key => $val) : ?>
                <td align="center" style="background-color: #e6ddae;" style="border: 0.1pt solid black;"><strong><?= number_format($val/$jml_mah, 1)?></strong></td>
            <?php endforeach; ?>
        </tr>
        </tbody>
    <?php endif; ?>
</table>
