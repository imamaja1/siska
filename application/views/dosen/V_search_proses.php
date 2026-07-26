<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('dosen/perwalian'); ?>" class="btn-sm btn-primary flat"><i class="fa fa-arrow-left"></i> Kembali</a>
    </div>
</div>
<div <?= $hidden ?>>
    <?php if (count($perwalian) > 0) : ?>
        <div class="box box-primary flat">
            <div class="box-body">
                <table class="table demo-table">
                    <tr>
                        <th>NIM</th>
                        <th>NAMA MAHASISWA</th>
                        <th>DATA AKADEMIK</th>
                        <th>STATUS PERKULIAHAN</th>
                    </tr>
                    <?php foreach ($perwalian as $row) { ?>
                        <td><?= $row->nim ?></td>
                        <td><?= $row->nama_mahasiswa ?></td>
                        <?php
                        switch ($row->status_perkuliahan) {
                            case 'A':
                                $status_perkuliahan = 'AKTIF';
                                break;
                            case 'C':
                                $status_perkuliahan = 'CUTI';
                                break;
                            case 'T':
                                $status_perkuliahan = 'TANPA KETERANGAN';
                                break;
                            case 'B':
                                $status_perkuliahan = 'BERHENTI';
                                break;
                            case 'P':
                                $status_perkuliahan = 'PINDAH/TRANSFER';
                                break;
                            default:
                                $status_perkuliahan = '-';
                        }
                        ?>
                        <td>Data Akademik</td>
                        <td><?= $status_perkuliahan ?></td>
                    <?php } ?>
                </table>
            </div>
        </div>
    <?php else: ?>
        <div class="callout callout-info flat">
            <p>Data yang anda cari tidak ada.</p>
        </div>
    <?php endif; ?>

</div>

<div class="box box-primary flat">
    <div class="box-header">
        <h4><b>Pencarian Data Perwalian</b></h4><hr>
    </div>
    <div class="box-body">
        <form class="form-horizontal" method="post" action="<?= site_url('dosen/perwalian/search_process'); ?>">
            <div class="form-group">
                <label class="control-label col-sm-3">Pencarian Berdasarkan :</label>
                <div class="col-sm-5">
                    <label><input type="radio" name="berdasarkan" value="nim" <?= set_radio('berdasarkan', 'nim') ?>> NIM (Nomor Induk Mahasiswa)</label>
                    &nbsp;&nbsp;
                    <label><input type="radio" name="berdasarkan" value="nama" <?= set_radio('berdasarkan', 'nama') ?>> Nama Mahasiswa </label>
                    <br>
                    <small class="text-danger"><?= form_error('berdasarkan') ?></small>
                </div>

            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Masukan Kata Kunci :</label>
                <div class="col-sm-4">
                    <input value="<?= set_value('kata_kunci') ?>" type="text" class="form-control" placeholder="Ketik Kata Kunci disini" name="kata_kunci" id="kata_kunci">
                    <small class="text-danger"><?= form_error('kata_kunci') ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3"></label>
                <div class="col-sm-3">
                    <button type="submit" class="btn btn-primary flat"><i class="fa fa-cog"></i>  Proses</button>
                </div>
            </div>
        </form>
    </div>
</div>