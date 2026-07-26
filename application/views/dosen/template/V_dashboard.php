

<div class="callout callout-info">
<h4>Selamat Datang di SISKA V.2</h4>

</div>

<!--<div class="row">
    <div class="col-md-12">
        <ul class="timeline">
            <li>
                <i class="fa fa-book bg-blue"></i>
                <div class="timeline-item">
                    <h3 class="timeline-header"><a href="#">Selamat Datang</a></h3>
                    <div class="timeline-body">
                        Aplikasi Sistem Informasi Akademik merupakan sarana bagi mahasiswa dan dosen untuk melakukan pengurusan bagian akademik
                    </div>
                </div>
            </li>
            <li>
                <i class="fa fa-bell bg-maroon"></i>
                <div class="timeline-item">
                    <h3 class="timeline-header"><a href="#">Notifikasi Ke Apikasi Telegram Anda</a></h3>
                    <div class="timeline-body">
                        <u> Dapatkan notifikasi semua aplikasi Universitas Bumigora Ke Telegram anda dengan cara <b>[<a href="#" data-toggle="modal" data-target="#gambar1">Lihat Gambar</a>]</b> atau ikuti langkah berikut ini:</u>
                        <ol type="1">
                          <li>Download dan install aplikasi telegram yang bisa anda dapatkan secara gratis di <b>PlayStore atau Appstore</b> </li>
                            <li>Temukan dan pilih Bot dengan nama <b>universitasbumigorabot</b> pada telegram dengan memanfaatkan fitur pencarian aplikasi telegram</li>
                            <li>Tekan <b>Start</b> atau <b>Mulai</b> untuk masuk ke Bot <b>universitasbumigorabot</b></li>
                            <li>Tulis dan Kirim pesan yang isinya hanya alamat email ini: <b><?= $this->session->userdata('alamat_email'); ?></b> ke <b>universitasbumigorabot</b></li>
                        </ol>
                        <table class="table">
                            <tr>
                              <td>5. Tekan tombol <b>Mulai</b> yang dibawah ini untuk menyambungkan siska dengan telegram anda, jika sudah tersambung maka tombol <b>Mulai</b> akan berubah menjadi tombol <b>Tersambung</b>.</td>
                                <td>6. Tekan tombol <b>Kirim Pesan</b> untuk mencoba mengirim pesan dari siska ke telegram anda (Isi pesan yang dikirim yaitu: "<b>SISKA UNIVERSITAS BUMIGORA</b>")</td>
                            </tr>
                            <tr>
                                <td>
                                    <?php
                                    if (!empty($chat_id['chatid'])):
                                        ?>
                                        <a href="<?= site_url('dosen/botdosen/getchatid') ?>" class="btn btn-warning">Tersambung</a>
                                        <?php
                                    else:
                                        ?>
                                        <a href="<?= site_url('dosen/botdosen/getchatid') ?>" class="btn btn-primary">Mulai</a>
                                    <?php
                                    endif;
                                    ?>
                                    </td>
                                <td><a href="<?= site_url('dosen/botdosen/kirimpesan') ?>" class="btn btn-info">Kirim Pesan</a>&nbsp;&nbsp;<small class="text-danger"></small></td>
                            </tr>   
                        </table>
                        7. Jika tombol mulai belum berubah dan pesan belum diterima mohon untuk mengulang dari langkah ke-4 dengan menuliskan alamat email yang benar.
                        <br>
                        <br>
                    </div>
                </div>
            </li>
            <?= $this->session->flashdata('infoxy') ?>
            <li>
                <i class="fa fa-commenting bg-green"></i>
                <div class="timeline-item">
                    <h3 class="timeline-header"><a href="#">Penjelasan Menu</a></h3>
                    <div class="timeline-body">
                        Berikut merupakan penjelasan dari menu yang ada pada SISKA :
                        <ul>
                            <li>Menu <b>Kurikulum</b> <br>
                                Menu ini digunakan untuk melihat kurikulum pada jurusan dan angkatan dengan dari mahasiswa yang melakukan bimbingan KRS.
                            </li>
                        </ul>
                        <br>1. Konsultasi Pengaktifan KRS
                        <br>2. Konsultasi Perwalian Khusus
                        <br>3. Konsultasi Perwalian Umum
                        <br>
                    </div>

                </div>

            </li>

            <li>
                <i class="fa fa-lock bg-yellow"></i>

                <div class="timeline-item">
                    <h3 class="timeline-header"><a href="#">LOGOUT</a></h3>
                    <div class="timeline-body">
                        Pilih nama anda di samping kanan atas kemudian pilih <b>Logout</b> untuk keluar dari aplikasi
                        Sistem Informasi Akademik ini atau dapat juga melalui tombol yang ada dibawah.
                    </div>
                    <div class="timeline-footer">
                        <a href="#" onclick="konfirmasiKeluar('<?= site_url('dosen/login_dosen/logout') ?>')" class="btn btn-danger btn-sm">Logout</a>
                    </div>
                </div>
            </li>

        </ul>
    </div>
</div>



<div class="modal fade" id="gambar1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
 
      <div class="modal-body">
          <img width="270" src="<?= base_url('assets/gambar/penjelasanbot/0.PNG') ?>">
          <img width="270" src="<?= base_url('assets/gambar/penjelasanbot/1.PNG') ?>">
          <img width="270" src="<?= base_url('assets/gambar/penjelasanbot/2.PNG') ?>">
          <img width="270" src="<?= base_url('assets/gambar/penjelasanbot/3.PNG') ?>">
          <img width="270" src="<?= base_url('assets/gambar/penjelasanbot/4.PNG') ?>">
          <img width="270" src="<?= base_url('assets/gambar/penjelasanbot/5.PNG') ?>">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary">Baiklah</button>
      </div>
    </div>
  </div>
</div>-->
