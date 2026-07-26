<html>
    <head>
        <style>
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
        </style>
    </head>
    <body>


        <?php
        // Menentukan keterangan ganjil atau genap berdasarkan semester dari tabel KRS	 	 
        if ($krs_mahasiswa->semester % 2 == 1) {
            $semester = "GANJIL";
        } else {
            $semester = "GENAP";
        }

        if ($kode_jenjang == '3') {
            $jenjang = 'D3';
        } else {
            $jenjang = 'S1';
        }
        ?>
        <!--Start Header KRS--> 
        <h4>KARTU RENCANA STUDI PROGRAM STUDI <?php echo strtoupper($jurusan->nama_program_studi); ?><br />
            SEMESTER <?php echo $semester; ?> <?php echo $krs_mahasiswa->tahun_akademik; ?></h4>
        <table width="100%" style="font-size: 8pt; border-collapse: collapse;" cellpadding="2">
            <tr>
                <td>
                    <table width="100%">
                        <tr>
                            <td><b>Nama Mahasiswa</b></td>
                            <td><b>:</b></td>
                            <td><?php echo $krs_mahasiswa->nama_mahasiswa; ?></td>					
                        </tr>
                        <tr>
                            <td><b>NIM</b></td>
                            <td><b>:</b></td>
                            <td><?php echo $krs_mahasiswa->nim; ?></td>					
                        </tr>
                        <tr>
                            <td><b>Semester</b></td>
                            <td><b>:</b></td>
                            <td><?php echo $krs_mahasiswa->semester; ?></td>					
                        </tr>	
                        <tr>
                            <td><b>Dosen Wali</b></td>
                            <td><b>:</b></td>
                            <td><?php echo $krs_mahasiswa->nama_dosen; ?></td>					
                        </tr>	
                    </table>
                </td>
                <td width="10%">&nbsp;</td>
                <td>
                    <table>
                        <tr>
                            <td><b>Alamat Sekarang</b></td>
                            <td><b>:</b></td>
                            <td><?php echo $krs_mahasiswa->alamat . ', ' . $krs_mahasiswa->kota . '<br />' . strtoupper($krs_mahasiswa->propinsi); ?></td>		
                        </tr>
                        <tr>
                            <td><b>Telp/HP</b></td>
                            <td><b>:</b></td>
                            <td><?php echo $krs_mahasiswa->telepon; ?></td>		
                        </tr>
                        <tr>
                            <td><b>Alamat Ortu/Wali/Libur</b></td>
                            <td><b>:</b></td>
                            <td><?php echo $krs_mahasiswa->alamat_orangtua . ',' . $krs_mahasiswa->kota_orangtua . '<br />' . strtoupper($krs_mahasiswa->propinsi_orangtua); ?></td>
                        </tr>	
                        <tr>
                            <td><b>Telp</b></td>
                            <td><b>:</b></td>
                            <td><?php
                                if (empty($krs_mahasiswa->telepon_orangtua)) {
                                    echo '-';
                                } else {
                                    echo $krs_mahasiswa->telepon_orangtua;
                                }
                                ?></td>
                        </tr>	
                    </table>
                </td>
            </tr>
        </table>
        <!--End Header KRS--> 
        <br />
        <!--Start Content KRS--> 
        <div align="center">Tanda <strong>&radic;</strong> di kolom <strong>B</strong> atau <strong>U</strong> menandakan matakuliah yang dipilih.</div>
        <table border="1" class="items" width="100%" style="font-size: 8pt; border-collapse: collapse;" cellpadding="2">
            <tr>
                <th colspan="10" align="center">SEMESTER <?php echo $krs_mahasiswa->semester; ?></th>
            </tr>
            <tr>
                <th rowspan="2" width="5%">NO</th>
                <th rowspan="2" width="13%">KODE MK</th>
                <th rowspan="2" width="40%">MATAKULIAH</th>
                <th colspan="3" align="center" width="18%">SKS</th>
                <th rowspan="2" align="center" width="6%">B</th>
                <th rowspan="2" align="center" width="6%">U</th>
                <th rowspan="2" align="center" width="6%">UTS</th>
                <th rowspan="2" align="center" width="6%">UAS</th>
            </tr>
            <tr>
                <th align="center">T</th>
                <th align="center">PK</th>
                <th align="center">PT</th>
            </tr>  
            <?php
// Variable untuk menampung NO.
            $i = 1;
// Variable untuk menampung jumlah SKS teori, praktek, dan praktikum
            $jumlah_sks_teori = 0;
            $jumlah_sks_praktek = 0;
            $jumlah_sks_praktikum = 0;
            foreach ($krs_matakuliah as $row) {
                ?>  
                <tr>
                    <td align="center"><?php echo $i . '.'; ?></td>
                    <td align="center"><?php echo $row->kode_matakuliah; ?></td>
                    <td><?= $row->nama_matakuliah ?></td>
                    <td align="center"><?php
                        if ($row->sks_teori != 0) {
                            echo $row->sks_teori;
                        }
                        ?></td>
                    <td align="center"><?php
                        if ($row->sks_praktek != 0) {
                            echo $row->sks_praktek;
                        }
                        ?></td>
                    <td align="center"><?php
                        if ($row->sks_praktikum != 0) {
                            echo $row->sks_praktikum;
                        }
                        ?></td>
                    <td align="center"><?php
                        if ($row->status == 'B') {
                            echo '&radic;';
                        }
                        ?></td>
                    <td align="center"><?php
                        if ($row->status == 'U') {
                            echo '&radic;';
                        }
                        ?></td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                </tr>
                <?php
                $i++;
                $jumlah_sks_teori = $jumlah_sks_teori + $row->sks_teori;
                $jumlah_sks_praktek = $jumlah_sks_praktek + $row->sks_praktek;
                $jumlah_sks_praktikum = $jumlah_sks_praktikum + $row->sks_praktikum;
            }
            $jumlah_sks = $jumlah_sks_teori + $jumlah_sks_praktek + $jumlah_sks_praktikum;
            ?>  
            <tr class="dark">    
                <td colspan="3" align="center">JUMLAH</td>    
                <td align="center"><?php echo $jumlah_sks_teori; ?></td>
                <td align="center"><?php echo $jumlah_sks_praktek; ?></td>
                <td align="center"><?php echo $jumlah_sks_praktikum; ?></td>
                <td colspan="4">&nbsp;</td>    
            </tr>
        </table>	
        <!--End Content KRS--> 
        <!--Start Footer KRS--> 
        <table width="100%" style="font-size: 8pt; border-collapse: collapse;" cellpadding="2">
            <tr>
                <td valign="top">
                    <table>
                        <tr>
                            <td width="57%">
                                <?php
// Menentukan keterangan ganjil atau genap berdasarkan semester dari tabel KRS	 	 
                                if ($krs_mahasiswa->semester % 2 == 1) {
                                    echo "IP Semester Genap lalu";
                                } else {
                                    echo "IP Semester Ganjil lalu";
                                }
                                ?>
                            </td>
                            <td width="2%">:</td>
                            <td width="41%">
                                <?php
                                /* Menampilkan informasi IP semester lalu, jika semester ini adalah semester 1 dan mahasiswa tersebut 
                                 * adalah mahasiswa baru maka tampilkan tanda '-'. Tetapi jika semester ini adalah semester 1 dan mahasiswa tersebut
                                 * adalah mahasiswa transfer/lanjut, lakukan pengecekan IP di tabel KHS (prosedur ditanyakan ke jurusan).
                                 * Mahasiswa selain semester 1, lakukan pengecekan IP di tabel KHS.
                                 */
                                if ($krs_mahasiswa->semester == '1') {
                                    echo '-';
                                } else {
                                    echo number_format($beban_sks['ip_semester_lalu'],2);
                                }
                                ?>
                            </td>
                        </tr>
                        <?php if (substr($krs_mahasiswa->nim,4,1) !=3) : ?>
                        <tr>
                            <td>
                                <?php
// Menentukan keterangan ganjil atau genap berdasarkan semester dari tabel KRS	 	 
                                if ($krs_mahasiswa->semester % 2 == 1) {
                                    echo "Beban Maks. Semester Ganjil sekarang";
                                } else {
                                    echo "Beban Maks. Semester Genap sekarang";
                                }
                                ?>
                            </td>
                            <td>:</td>
                            <td>
                                <?php
                                /* Menampilkan informasi beban maksimum SKS. 
                                 * Jika semester ini adalah semester 1 dan mahasiswa tersebut adalah mahasiswa baru,
                                 * maka tampilkan jumlah sks keseluruhan matakuliah pada semester 1.
                                 * Berlaku di semua jurusan, karena semester 1 menggunakan sistem paket.
                                 * Sedangkan apabila semester ini adalah semester 1 tetapi mahasiswa tersebut adalah mahasiswa transfer/lanjut,
                                 * maka cek IP ditabel KHS. (Tanyakan Proses selanjutnya di jurusan).
                                 */
                                if ($krs_mahasiswa->semester == '1') {
                                    echo $jumlah_sks;
                                } else {
                                    echo $beban_sks['beban_sks'];
                                }
                                ?>
                            </td>
                        </tr>
                        <?php endif;?>
                    </table></td>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="61%">Jumlah kredit yang diambil</td>
                            <td width="3%">:</td>
                            <td width="20%" class="right">
                                <?php
                                /* Menampilkan jumlah kredit yang diambil. Khusus semester 1 karena menggunakan sistem paket maka langsung ditampilkan jumlah sks
                                 * dari matakuliah yang ada di semester 1.
                                 */
                                $jumlah_kredit_yg_diambil = $jumlah_sks;
                                echo $jumlah_kredit_yg_diambil;
                                ?>
                            </td>
                            <td width="16%" class="right">SKS</td>
                        </tr>
                        <tr>
                            <td>Jumlah kredit yang batal</td>
                            <td>:</td>
                            <td class="right">
                                <?php
                                /* Menampilkan jumlah kredit yang dibatalkan. 
                                 * Khusus semester 1 dan jika mahasiswa tersebut adalah mahasiswa baru,
                                 * maka tidak ada matakuliah yang akan dibatalkan karena menggunakan sistem paket. 
                                 * Tetapi jika semester 1 dan mahasiswa tersebut adalah mahasiswa tranfer/lanjut,
                                 * atau selain semester 1 maka tampilkan jumlah matakuliah yang dibatalkan.
                                 */
                                if ($krs_mahasiswa->semester != '1') {
                                    $jumlah_kredit_yg_batal = 0;
                                    if ($jumlah_kredit_yg_batal == 0) {
                                        echo '-';
                                    }
                                } else {
                                    $jumlah_kredit_yg_batal = 0;
                                    echo $jumlah_kredit_yg_batal;
                                }
                                ?>        
                            </td>
                            <td align="right">SKS</td>
                        </tr>
                        <tr>
                            <td>Jumlah penambahan kredit</td>
                            <td>:</td>
                            <td style="border-bottom: 1px solid #000000;" class="right">
                                <?php
                                /* Menampilkan jumlah penambahan kredit. 
                                 * Khusus semester 1 dan jika mahasiswa tersebut adalah mahasiswa baru,
                                 * maka tidak ada matakuliah yang akan ditambahkan karena menggunakan sistem paket. 
                                 * Tetapi jika semester 1 dan mahasiswa tersebut adalah mahasiswa tranfer/lanjut,
                                 * atau selain semester 1 maka tampilkan jumlah matakuliah yang ditambahkan.
                                 */
                                if ($krs_mahasiswa->semester != '1') {
                                    $jumlah_penambahan_kredit = 0;
                                    if ($jumlah_penambahan_kredit == 0) {
                                        echo '-';
                                    }
                                } else {
                                    $jumlah_penambahan_kredit = 0;
                                    echo $jumlah_penambahan_kredit;
                                }
                                ?>        
                            </td>
                            <td style="border-bottom: 1px solid #000000;" class="right">SKS</td>
                        </tr>
                        <tr>
                            <td>Jumlah kredit terakhir</td>
                            <td>:</td>
                            <td class="right">
                                <?php
                                $jumlah_kredit_terakhir = ($jumlah_kredit_yg_diambil - $jumlah_kredit_yg_batal) + $jumlah_penambahan_kredit;
                                echo $jumlah_kredit_terakhir;
                                ?>
                            </td>
                            <td class="right">SKS</td>
                        </tr>
                    </table></td>
            </tr>
        </table>
        <br />
        <table width="100%" style="font-size: 8pt; border-collapse: collapse;" cellpadding="2">
            <tr>
                <td width="33%">&nbsp;</td>
                <td width="33%">&nbsp;</td>
                <td width="34%">Mataram, <?= tgl_indo(date('d F Y')); ?></td>
            </tr>
            <tr>
                <td>Ka. Prodi <?php echo $jurusan->singkatan_program_studi; ?></td>
                <td>Dosen Wali</td>
                <td>Mahasiswa yang bersangkutan,</td>
            </tr>
            <tr>
                <td height="60px">
                    <!--<img style="height: 60px" src="<?= base_url('assets/signature_kaprodi/' . $kajur->tanda_tangan) ?>"/>-->
                    <img style="height: 60px" src="<?= base_url('assets/signature-dosen/' . $kajur->signature) ?>"/>
                </td>
                <td height="60px">
                    <?php if (!empty($krs_mahasiswa->signature)) : ?>
                        <img style="height: 60px" src="<?= base_url('assets/signature-dosen/' . $krs_mahasiswa->signature) ?>"/>
                    <?php endif; ?>
                </td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><div class="garis_bawah"><?php echo $kajur->nama_dosen; ?></div>NIP/NIK: <?php echo $kajur->nik; ?></td>
                <td><div class="garis_bawah"><?php echo $krs_mahasiswa->nama_dosen; ?></div>NIP/NIK: <?php echo $krs_mahasiswa->nik; ?></td>
                <td><div class="garis_bawah"><?php echo $krs_mahasiswa->nama_mahasiswa; ?></div>NIM: <?php echo $krs_mahasiswa->nim; ?></td>
            </tr>
        </table>
        <p class="keterangan"><br />Keterangan:</p>
        <ol class="footer">
            <li>Makna kode-kode:<br />
                <ol type="a">
                    <li>Status matakuliah yang diambil, yaitu: <i>Baru</i> <strong>(B)</strong>, <i>Ulang</i> <strong>(U)</strong>.</li>
                    <li>Jenis SKS matakuliah, yaitu: <i>Teori</i> <strong>(T)</strong>, <i>Praktek</i> <strong>(PK)</strong>, <i>Praktikum</i> <strong>(PT)</strong>, <i>Ujian Tengah Semester</i> <strong>(UTS)</strong>, <i>Ujian Akhir Semester</i> <strong>(UAS)</strong>.</li>
                </ol>
            </li>
            <li>Kartu ini dibuat rangkap 4, antara lain:<br />
                a. Lembar <strong>merah</strong> untuk Akademik.&nbsp;&nbsp;b. Lembar <strong>biru</strong> untuk Dosen Wali.&nbsp;&nbsp;c. Lembar <strong>putih</strong> untuk Mahasiswa.      
            </li>
            <li>Kartu ini harus dibawa saat mengikuti <strong>Ujian Tengah Semester (UTS)</strong> dan <strong>Ujian Akhir Semester (UAS)</strong>, serta saat mengurus keperluan lainnya di Universitas Bumigora Mataram.</li>
            <li>Universitas Bumigora Mataram tidak bertanggungjawab atas hilangnya kartu ini.</li>
          	<li>Kartu ini harus di stempel pada bagian Keuangan, BAA dan Perpustakaan.</li>
        </ol>
        <!--End Footer KRS--> 
        <table border="1" class="items" width="80%" style="font-size: 8pt; border-collapse: collapse;" cellpadding="2" align="center">
            <tr>
                <th width="10%">UJIAN</th>
                <th width="33%">KEUANGAN</th>
                <th width="34%">BAA</th>
                <th width="33%">PERPUSTAKAAN</th>
            </tr>
            <tr>
                <th>UTS</th>
                <td height="60px">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <th>UAS</th>
                <td height="60px">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </table>
    </body>
</html>