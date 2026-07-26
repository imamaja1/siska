<?= $this->session->flashdata('info') ? $this->session->flashdata('info') : '' ?>
<div class="row">
<div class="col-md-12">
    <div class="box box-solid flat">
        <div class="box-body">
            <div class="pull-right">
                <a href="<?= site_url('admin/akademik/hapus_mak') ?>" class="btn btn-sm btn-success flat"><i class="fa fa-arrow-left"></i> Kembali</a>
            </div>
        </div>
    </div>
</div>
</div>
<div class="row">

<div class="col-md-12">
    <div class="box box-solid flat">
        <div class="box-header">
            <span class="badge bg-aqua"><?= $mahasiswa->nama_mahasiswa ?> - <?= $mahasiswa->nim ?></span>
        </div>
        <div class="box-body">
            <table class="demo-table" width="100%">
                <thead>
                <tr>
                    <th style="text-align: center">NO.</th>
                    <th style="text-align: center">Kode Matakuiah</th>
                    <th style="text-align: center">Nama Matakuiah</th>
                    <th style="text-align: center">SKS</th>
                    <th style="text-align: center">Nilai Akhir</th>
                    <th style="text-align: center">Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php $sksn = 0; $no=1; foreach ($data as $row) : ?>
                    <tr>
                        <td style="text-align: center"><?= $no++ ?>.</td>
                        <td style="text-align: center"><?= $row->kode_matakuliah ?></td>
                        <td><?= $row->nama_matakuliah ?></td>
                        <td style="text-align: center"><?= $sks = substr($row->kode_matakuliah,4,1)?></td>
                        <td style="text-align: center"><?= $row->nilai_akhir ?></td>
                        <td style="text-align: center">
                            <a href="#" onclick="hapus('<?= site_url('admin/akademik/hapus_mak/hapus/'.$nim.'/'.$row->kode_matakuliah.'/'.$row->kode_krs_detail) ?>')" class="btn btn-xs btn-danger flat"><i class="fa fa-trash"></i> Hapus</a>
                        </td>
                    </tr>
                <?php
                $sksn = $sksn + $sks;
                endforeach; ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="text-align: center"><strong><?= $sksn ?></strong></td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
<script>
    function hapus(url)
    {
        swal({
            title: '',
            text: "Anda yaikin menghapus data ini?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Tidak',
            confirmButtonText: 'Ya'
        }).then(function () {
            window.location.href = url;
        });
    }
</script>
