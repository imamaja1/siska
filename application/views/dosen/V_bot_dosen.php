<div class="row">
    <div class="col-md-12">
        <ul class="timeline">
            <li>
                <i class="fa fa-download bg-blue"></i>
                <div class="timeline-item">
                    <h3 class="timeline-header"><a href="#">Langkah 1</a></h3>
                    <div class="timeline-body">
                        <div class="row">
                            <div class="col-md-10">
                                Download dan install aplikasi telegram yang bisa anda dapatkan secara gratis di <b>PlayStore atau Appstore</b>
                            </div> 
                            <div class="col-md-2">
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#gambar0"><i class="fa fa-eye"></i> Lihat Gambar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </li>

            <div class="modal fade" id="gambar0" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document" style="width: 300px;">
                    <div class="modal-content" >
                        <div class="modal-body">
                            <img width="270" src="<?= base_url('assets/gambar/penjelasanbot/0.PNG') ?>">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <!--<button type="button" class="btn btn-primary">Baiklah</button>-->
                        </div>
                    </div>
                </div>
            </div>


            <li>
                <i class="fa fa-envelope bg-maroon"></i>
                <div class="timeline-item">
                    <h3 class="timeline-header"><a href="#">Langkah 2</a></h3>
                    <div class="timeline-body">
                        <div class="row">
                            <div class="col-md-10">
                                Buka aplikasi telegram setelah itu temukan dan pilih Bot dengan nama <b>universitasbumigorabot</b> pada telegram dengan memanfaatkan fitur pencarian aplikasi telegram
                            </div> 
                            <div class="col-md-2">
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#gambar1"><i class="fa fa-eye"></i> Lihat Gambar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </li>

            <div class="modal fade" id="gambar1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document" style="width: 300px;">
                    <div class="modal-content" >
                        <div class="modal-body">
                            <img width="270" src="<?= base_url('assets/gambar/penjelasanbot/1.PNG') ?>">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <!--<button type="button" class="btn btn-primary">Baiklah</button>-->
                        </div>
                    </div>
                </div>
            </div>

            <li>
                <i class="fa fa-arrow-right bg-green"></i>
                <div class="timeline-item">
                    <h3 class="timeline-header"><a href="#">Langkah 3</a></h3>
                    <div class="timeline-body">

                        <div class="row">
                            <div class="col-md-10">
                                Tekan <b>Start</b> atau <b>Mulai</b> untuk masuk ke Bot <b>universitasbumigorabot</b>
                            </div> 
                            <div class="col-md-2">
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#gambar2"><i class="fa fa-eye"></i> Lihat Gambar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </li>

            <div class="modal fade" id="gambar2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document" style="width: 300px;">
                    <div class="modal-content" >
                        <div class="modal-body">
                            <img width="270" src="<?= base_url('assets/gambar/penjelasanbot/2.PNG') ?>">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <!--<button type="button" class="btn btn-primary">Baiklah</button>-->
                        </div>
                    </div>
                </div>
            </div>

            <li>
                <i class="fa fa-envira bg-yellow"></i>
                <div class="timeline-item">
                    <h3 class="timeline-header"><a href="#">Langkah 4</a></h3>
                    <div class="timeline-body">

                        <div class="row">
                            <div class="col-md-10">
                                Tulis dan Kirim pesan yang isinya hanya alamat email ini: <b><?= e($this->session->userdata('alamat_email')) ?></b> ke <b>universitasbumigorabot</b>
                            </div> 
                            <div class="col-md-2">
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#gambar3"><i class="fa fa-eye"></i> Lihat Gambar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </li>

            <div class="modal fade" id="gambar3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document" style="width: 300px;">
                    <div class="modal-content" >
                        <div class="modal-body">
                            <img width="270" src="<?= base_url('assets/gambar/penjelasanbot/4.PNG') ?>">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <!--<button type="button" class="btn btn-primary">Baiklah</button>-->
                        </div>
                    </div>
                </div>
            </div>

            <li>
                <i class="fa fa-internet-explorer bg-red"></i>
                <div class="timeline-item">
                    <h3 class="timeline-header"><a href="#">Langkah 5</a></h3>
                    <div class="timeline-body">
                        Tekan tombol <b>Mulai</b> yang dibawah ini untuk menyambungkan siska dengan telegram anda, jika sudah tersambung maka tombol <b>Mulai</b> akan berubah menjadi tombol <b>Tersambung</b>
                        <br>
                        <p>
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
                        </p>
                    </div>
                </div>
            </li>

            <li>
                <i class="fa fa-send bg-blue"></i>
                <div class="timeline-item">
                    <h3 class="timeline-header"><a href="#">Langkah 6</a></h3>
                    <div class="timeline-body">

                        <div class="row">
                            <div class="col-md-10">
                                Tekan tombol <b>Kirim Pesan</b> untuk mencoba mengirim pesan dari siska ke telegram anda (Isi pesan yang dikirim yaitu: "<b>SISKA UNIVERSITAS BUMIGORA</b>"), tahap ini merupakan tahapan uji coba untuk meyakinkan apakah website siska tersambung dengan aplikasi telegram dan dapat menerima pesan notifikasi dengan baik.
                                <br>
                                <p>
                                    <a href="<?= site_url('dosen/botdosen/kirimpesan') ?>" class="btn btn-info">Kirim Pesan</a>&nbsp;&nbsp;<small class="text-danger"></small>
                                </p>
                            </div> 
                            <div class="col-md-2">
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#gambar5"><i class="fa fa-eye"></i> Lihat Gambar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            
             <div class="modal fade" id="gambar5" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document" style="width: 300px;">
                    <div class="modal-content" >
                        <div class="modal-body">
                            <img width="270" src="<?= base_url('assets/gambar/penjelasanbot/5.PNG') ?>">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <!--<button type="button" class="btn btn-primary">Baiklah</button>-->
                        </div>
                    </div>
                </div>
            </div>

            <li>
                <i class="fa fa-refresh bg-navy"></i>
                <div class="timeline-item">
                    <h3 class="timeline-header"><a href="#">Langkah 7</a></h3>
                    <div class="timeline-body">
                        Jika label tombol mulai belum berubah menjadi label tombol tersambung dan pesan belum diterima mohon untuk mengulang dari langkah ke-4 dengan menuliskan alamat email yang benar dan pastikan <b>koneksi internet</b> anda stabil.
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
</div>
