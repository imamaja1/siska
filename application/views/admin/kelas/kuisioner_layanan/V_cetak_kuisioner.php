<?php
//header("Content-type: application/octet-stream");
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=".$file_name.".xls");
//header("Pragma: no-cache");
//header("Expires: 0");
?>
<p><img src="<?= base_url('assets/gambar/header_krs.png') ?>" alt=""><p align="right"><strong>BG/BAA/QSR/007-00/09</strong></p>
<hr size="2"></p>
<p align="center">HASIL KUISIONER PELAYANAN SEMESTER <?= $tahun_akademik->semester == 1 ? 'GANJIL' : 'GENAP' ?> TAHUN AKADEMIK <?= $tahun_akademik->tahun_akademik ?></p>

<table style="font-family: 'Arial Narrow','Arial';">
    <tr>
        <td colspan="2">Program Studi</td>
        <td colspan="7"> : <?= $prodi->nama_program_studi ?></td>
    </tr>

    <tr><td></td></tr>
<!--    CEK MATAKULIAH PRAKTIKUM DAN TEORI-->
<!--        TABEL KUISIONER TEORI-->
        <thead>
        <tr>
            <th id="th" rowspan="2" style="border: 0.1pt solid black;">NO.</th>
            <?php $batas=0; foreach ($header as $key) : ?>
                <th id="th" colspan="<?= $key->colspan ?>" style="border: 0.1pt solid black;"><?= $key->nama_bagian ?></th>
                <?php $batas = $batas + $key->colspan; ?>
            <?php endforeach;?>
            <th id="th" rowspan="2" style="border: 0.1pt solid black;">Masukan</th>
        </tr>
        <tr>
            <?php foreach ($header as $row) : ?>
                <?php $count = $row->colspan+1;  for ($n=1; $n < $count; $n++) : ?>
                    <th style="width: 40px;" id="th" style="border: 0.1pt solid black;"><?=$n ?></th>
                <?php endfor; ?>
            <?php endforeach;?>
        </tr>
        </thead>
        <tbody>
        <?php $no=1; foreach ($data as $item) :?>
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
                    <?php $masukan = $val->masukan; ?>
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
                <td style="border: 0.1pt solid black;"> <?= $masukan ?> </td>
            </tr>
        <?php endforeach;?>
        </tbody>

</table>
