<html>
    <head>
        <link rel="stylesheet" href="<?= base_url('assets/print-preview/src/css/print-preview.css') ?>">
        <script src="<?= base_url('assets/plugins/jQuery/jQuery-2.2.0.min.js'); ?>"></script>
        <script src="<?= base_url('assets/print-preview/src/jquery.print-preview.js') ?>"></script>
        <style>
            @media all {
                body {font-family: sans-serif;
                    font-size: 8pt;
                }
                p.keterangan {
                    margin: 0pt;
                    font-family: sans-serif;
                    font-size: 8pt;
                    font-weight:bold;
                    text-decoration:underline;
                }
                td { vertical-align: top; }
                .items td {
                    border: 0.1mm solid #000000;
                }
                table thead td { background-color: #EEEEEE;
                    text-align: center;
                    border: 0.1mm solid #000000;
                }
                td.right {
                    text-align: right;
                }
                .items td.blanktotal {
                    background-color: #FFFFFF;
                    border: 0mm none #000000;
                    border-top: 0.1mm solid #000000;
                    border-right: 0.1mm solid #000000;
                }
                .items td.totals {
                    text-align: right;
                    border: 0.1mm solid #000000;
                }
                .footer {
                    font-family: sans-serif;
                    font-size: 8pt;
                }
                .garis_bawah {
                    text-decoration:underline;
                }
                h4 {
                    text-align:center;
                }

                @page {
                    size: auto;   /* auto is the initial value */

                    /* this affects the margin in the printer settings */
                    margin: 4mm 10mm 10mm 10mm;
                }
            }

        </style>
    </head>
    <body style="font-size: 8pt; padding-bottom: 10px">

        <table width="100%" style="font-size: 8pt; padding-bottom: 10px">
            <tr>
                <td colspan="2" align="right">BG/PSG/QSR/074-00/17</td>
            </tr>
            <tr>
                <td width="85%" style="border-bottom: 0.5px solid #000000;  ;text-align: center ;vertical-align: bottom; font-family:serif; font-size: 8pt;">
                    <!--<img style="height: 120px;" src="<?= base_url('assets/gambar/kop/'.$prodi->kode_fakultas.'.png'); ?>">-->
                    <img style="height: 120px;" src="<?= base_url('assets/gambar/kop/'.bodo_kop($perwalian->nim)['kop']) ?>">
                </td>
                <td width="15%">
                    <table style="border:1px solid #000000; width:80px; font-size: 8pt">
                        <tr>
<!--                            <td align="center" height="100px" valign="middle"><strong>FOTO<br />3 x 4</strong>-->
                            <td align="center" height="100px" valign="middle">
                                <img src="<?= base_url('assets/foto/'.$perwalian->foto) ?>" style="height: 120px;" alt="">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <!--Start Header KRS--> 
        <h4 style="text-align: center; margin-top: -1px; font-size: 8pt">KARTU BIMIBINGAN AKADEMIK</h4>
        <table width="100%" style="font-size: 8pt; border-collapse: collapse;" cellpadding="2">
            <tr>
                <td width="50%">
                    <table width="100%" style="font-size: 8pt; font-family: Arial">
                        <tr>
                            <td><b>Nama</b></td>
                            <td><b>:</b></td>
                            <td><?= $perwalian->nama_mahasiswa ?></td>
                        </tr>
                        <tr>
                            <td><b>NIM</b></td>
                            <td><b>:</b></td>
                            <td><?= $perwalian->nim ?></td>
                        </tr>
                        <tr>
                            <td><b>No. HP/Email</b></td>
                            <td><b>:</b></td>
                            <td><?= $perwalian->telepon ?> / <?= $perwalian->email ?></td>
                        </tr>	
                        <tr>
                            <td><b>Dosen Wali</b></td>
                            <td><b>:</b></td>
                            <td><?= $perwalian->nama_dosen ?></td>
                        </tr>	
                    </table>
                </td>
            </tr>
        </table>
        <!--End Header KRS--> 
        <br />
        <!--Start Content KRS--> 

        <table border="1" class="items" width="100%" style="font-size: 8pt; font-family: Arial; border-collapse: collapse;" cellpadding="2">
           <thead>
           <tr>
               <th>NO.</th>
               <th>Semester</th>
               <th>Tgl. Konsultasi</th>
               <th>Materi Konsultasi</th>
               <th>Solusi Konsultasi</th>
           </tr>
           </thead>
            <tbody>
            <?php $no = 1; foreach ($data as $row) : ?>
                <tr>
                    <td style="text-align: center"><?= $no++ ?>.</td>
                    <td style="text-align: center"><?= $row->semester ?></td>
                    <td><?= $row->date_created == null ? '(empty)' : tgl_indo($row->date_created) ?></td>
                    <td><?= $row->isi_konsultasi ?></td>
                    <td><?= $row->tanggapan ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>	
        <!--End Content KRS-->

    <script>
        $(document).ready(function (e) {
            $.printPreview.loadPrintPreview();
            $(".close").click(function () {
                window.top.close();
            });
        })

        $('body').click(function() {
            if (!$(this.target).is('#print-modal')) {
                window.top.close();
            }
        });

    </script>
    </body>
</html>