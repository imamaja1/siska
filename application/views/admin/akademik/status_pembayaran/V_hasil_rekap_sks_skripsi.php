<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Pembayaran (SKRIPSI) Mahasiswa TA. <?= tahun_akademik()->tahun_akademik ?> <?= tahun_akademik()->semester == '0' ? 'GENAP' : 'GANJIL' ?></h3>
    </div>
    <div class="box-body">
        <?php if(count($data) > 0) : ?>
        <div class="table-responsive">
            <table class="table demo-table data-table">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>NIM</th>
                    <th>Nama Siswa</th>
                    <th>Program Studi</th>
                    <th>Semester</th>
                    <th>SKS SKRIPSI</th>
                    <th>SPP</th>
                    <th>SKS</th>
                    <th>LAB</th>
                    <th>KRS</th>
                </tr>
                </thead>
                <tbody>
                <?php $no = 1; foreach ($data as $row) : ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $row->nim ?></td>
                    <td><?= $row->nama_mahasiswa ?></td>
                    <td><?= get_kode_prodi($row->nim)->nama_program_studi ?></td>
                    <td style="text-align: center"><?= $row->semester ?></td>
                    <td style="text-align: center"><span class="badge bg-aqua-active"><?= $row->teori ?></span></td>
                    <td style="text-align: center;"><?= $row->pembayaran_spp ? "<span class='text-success'><i class='fa fa-check'></i></span>" : "<span class='text-danger'><i class='fa fa-times'></i></span>" ?></td>
                    <td style="text-align: center;"><?= $row->pembayaran_sks ? "<span class='text-success'><i class='fa fa-check'></i></span>" : "<span class='text-danger'><i class='fa fa-times'></i></span>" ?></td>
                    <td style="text-align: center;"><?= $row->pembayaran_lab ? "<span class='text-success'><i class='fa fa-check'></i></span>" : "<span class='text-danger'><i class='fa fa-times'></i></span>" ?></td>
                    <td style="text-align: center">
                        <div class="checkbox">
                            <label>
                                <input onclick="kumpul_krs('<?= $row->kode_status_perkuliahan ?>', this, event)" type="checkbox" <?= $row->pengumpulan_krs ? 'checked' : '' ?>>
                            </label>
                        </div>
                    </td>
                </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <p style="text-align: center; font-size: 20pt; font-weight: bold"><i> Tidak ada data di temukan</i></p>
        <?php endif; ?>
    </div>
</div>
<script>
    $(function () {
        $(".data-table").dataTable();
    })

    function kumpul_krs(id, contex, e) {
        // e.preventDefault();
        var url = "<?= site_url('admin/akademik/pembayaran_mahasiswa/kumpul_krs') ?>/"+id;
        $.ajax({
            url : url,
            success :  function (res) {
                console.log(res);
                var obj = JSON.parse(res);
                if (obj.status){
                    if (obj.val == '1'){
                        $(contex).attr('checked', true);
                    }else{
                        $(contex).attr('checked', false);
                    }
                    swal('Success','Berhasil merubah status','success');
                }else{
                    swal('Gagal mengirim data ke server','error');
                }
            }
        })
    }
</script>