<?php $cek = $this->db->select('*')->from('pembimbing_kkp')->where('kode_dosen', $this->session->userdata('kode_dosen'))->get()->result(); ?>
<div class="collapse navbar-collapse pull-left" id="navbar-collapse">
    <ul class="nav navbar-nav">
        <li class="<?= isset($a_dashboard) ? $a_dashboard : ''; ?>"><a href="<?= site_url('home/dosen') ?>">Dashboard<span class="sr-only">(current)</span></a></li>
        <li class="<?= isset($a_kurikulum) ? $a_kurikulum : ''; ?>"><a href="<?= site_url('dosen/kurikulum') ?>">Kurikulum</a></li>
        <li class="<?= isset($a_matakuliah_prasyarat) ? $a_matakuliah_prasyarat : ''; ?>"><a href="<?= site_url('dosen/matakuliah_prasyarat') ?>">Matakuliah Prasyarat</a></li>
        <!--        <li class="--><?//= isset($a_perwalian) ? $a_perwalian : ''; ?><!--"><a href="--><?//= site_url('dosen/perwalian') ?><!--">Perwakilan</a></li>-->
        <li class="<?= isset($a_konsultasi_perwalian) ? $a_konsultasi_perwalian : ''; ?>"><a href="<?= site_url('dosen/konsultasi_perwalian') ?>">Konsultasi Perwalian</a></li>
        <li class="<?= isset($a_penilaian) ? $a_penilaian : ''; ?>"><a href="<?= site_url('dosen/penilaian') ?>">Penilaian</a></li>
        <?php if (isKaprodi($this->session->userdata('kode_dosen'))) : ?>
            <!--            <li class="--><?//= isset($a_penilaian) ? $a_penilaian : ''; ?><!--"><a href="--><?//= site_url('dosen/kaprodi/konsultasi_perwalian') ?><!--">Kaprodi</a></li>-->
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Kaprodi <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="<?= site_url('dosen/kaprodi/konsultasi_perwalian') ?>">Perwalian Mahasiswa</a></li>
                    <li><a href="<?= site_url('dosen/kaprodi/ipk') ?>">IPK</a></li>
                    <li><a href="<?= site_url('dosen/kaprodi/validasinilai') ?>">Validasi Nilai</a></li>
                    <li><a href="<?= site_url('dosen/kaprodi/aktif_perkuliahan') ?>">Mahasiswa Aktif</a></li>
                </ul>
            </li>
        <?php endif; ?>
        <?php if (count($cek) > 0) : ?>
            <li class="<?= isset($a_kkp) ? $a_kkp : ''; ?>"><a href="<?= site_url('dosen/bimbingan_kkp') ?>">Bimbingan KKP</a></li>
        <?php endif; ?>
    </ul>
</div>