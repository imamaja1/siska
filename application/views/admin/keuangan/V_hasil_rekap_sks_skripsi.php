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
                    <th>Status Pembayaran</th>
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
                    <td style="text-align: center; width: 18%">
                        <div class="form-group">
                            <div class="checkbox pilihan">
                                <?php if ($row->pembayaran_spp == '0') : ?>
                                    <button onclick="bayar_spp('<?= $row->kode_status_perkuliahan ?>',this, event)"  class="btn btn-danger btn-xs"><i class="fa fa-times"></i> SPP</button>
                                <?php elseif ($row->pembayaran_spp == '1') : ?>
                                    <button onclick="bayar_spp('<?= $row->kode_status_perkuliahan ?>',this, event)"  class="btn btn-success btn-xs"><i class="fa fa-check"></i> SPP</button>
                                <?php else : ?>
                                    <button onclick="bayar_spp('<?= $row->kode_status_perkuliahan ?>',this, event)"  class="btn btn-warning btn-xs"><i class="fa fa-history"></i> SPP</button>
                                <?php endif; ?>
                            </div>&nbsp;&nbsp;

                            <div class="checkbox pilihan">
                                <?php if ($row->pembayaran_sks == '0') : ?>
                                    <button onclick="bayar_sks('<?= $row->kode_status_perkuliahan ?>',this, event)"  class="btn btn-danger btn-xs"><i class="fa fa-times"></i> SKS</button>
                                <?php elseif ($row->pembayaran_sks == '1') : ?>
                                    <button onclick="bayar_sks('<?= $row->kode_status_perkuliahan ?>',this, event)"  class="btn btn-success btn-xs"><i class="fa fa-check"></i> SKS</button>
                                <?php else : ?>
                                    <button onclick="bayar_sks('<?= $row->kode_status_perkuliahan ?>',this, event)"  class="btn btn-warning btn-xs"><i class="fa fa-history"></i> SKS</button>
                                <?php endif; ?>
                            </div>&nbsp;&nbsp;

                            <div class="checkbox pilihan">
                                <?php if ($row->pembayaran_lab == '0') : ?>
                                    <button onclick="bayar_lab('<?= $row->kode_status_perkuliahan ?>',this, event)"  class="btn btn-danger btn-xs"><i class="fa fa-times"></i> LAB</button>
                                <?php elseif ($row->pembayaran_lab == '1') : ?>
                                    <button onclick="bayar_lab('<?= $row->kode_status_perkuliahan ?>',this, event)"  class="btn btn-success btn-xs"><i class="fa fa-check"></i> LAB</button>
                                <?php else : ?>
                                    <button onclick="bayar_lab('<?= $row->kode_status_perkuliahan ?>',this, event)"  class="btn btn-warning btn-xs"><i class="fa fa-history"></i> LAB</button>
                                <?php endif; ?>
                            </div>
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
    var key = '';
    var kode_status_perkuliahan = '';
    function content() {
        return  '<form action="<?= site_url('admin/keuangan/status_perkuliahan/bayar') ?>" method="post">\n' +
            '    <div class="form-group">\n' +
            '        <div class="input-group">\n' +
            '<input type="hidden" id="key" name="key" value="'+ key +'">\n'+
            '<input type="hidden" id="kode_status_perkuliahan" name="kode_status_perkuliahan" value="'+kode_status_perkuliahan+'">\n'+
            '            <select name="value" required style="width:200px;" class="form-control">\n' +
            '                <option value="" selected disabled >Pilih</option>\n' +
            '                <option value="0" >Belum Lunas</option>\n' +
            '                <option value="1" >Lunas</option>\n' +
            '                <option value="2" >Dispensasi</option>\n' +
            '            </select>\n' +
            '            <span class="input-group-btn">\n' +
            '                      <button type="button" onclick="kirim(this)" class="btn btn-danger"><i class="fa fa-arrow-right"></i></button>\n' +
            '                </span>\n' +
            '        </div>\n' +
            '    </div>\n' +
            '</form>';
    }

    function status(status,status_perkuliahan_id,key) {
        if(key == 'pembayaran_spp'){
            if(status == '0'){
                return '<button onclick="bayar_spp('+status_perkuliahan_id+',this, event)"  class="btn btn-danger btn-xs"><i class="fa fa-times"></i> SPP</button>';
            }else if(status == '1'){
                return '<button onclick="bayar_spp('+status_perkuliahan_id+',this, event)"  class="btn btn-success btn-xs"><i class="fa fa-check"></i> SPP</button>';
            }else{
                return '<button onclick="bayar_spp('+status_perkuliahan_id+',this, event)"  class="btn btn-warning btn-xs"><i class="fa fa-history"></i> SPP</button>';
            }
        }else if(key == 'pembayaran_sks'){
            if(status == '0'){
                return '<button onclick="bayar_sks('+status_perkuliahan_id+',this, event)"  class="btn btn-danger btn-xs"><i class="fa fa-times"></i> SKS</button>';
            }else if(status == '1'){
                return '<button onclick="bayar_sks('+status_perkuliahan_id+',this, event)"  class="btn btn-success btn-xs"><i class="fa fa-check"></i> SKS</button>';
            }else{
                return '<button onclick="bayar_sks('+status_perkuliahan_id+',this, event)"  class="btn btn-warning btn-xs"><i class="fa fa-history"></i> SKS</button>';
            }
        }else{
            if(status == '0'){
                return '<button onclick="bayar_lab('+status_perkuliahan_id+',this, event)"  class="btn btn-danger btn-xs"><i class="fa fa-times"></i> LAB</button>';
            }else if(status == '1'){
                return '<button onclick="bayar_lab('+status_perkuliahan_id+',this, event)"  class="btn btn-success btn-xs"><i class="fa fa-check"></i> LAB</button>';
            }else{
                return '<button onclick="bayar_lab('+status_perkuliahan_id+',this, event)"  class="btn btn-warning btn-xs"><i class="fa fa-history"></i> LAB</button>';
            }
        }

    }

    function bayar_spp(id, contex, e) {
        kode_status_perkuliahan = id;
        key = 'pembayaran_spp';
        var content_spp = content();
        $(contex).popover({
            placement: 'left',
            title: 'Pembayaran SPP',
            html:true,
            content: content_spp,
        })
    }

    function bayar_sks(id, contex, e) {
        kode_status_perkuliahan = id;
        key = 'pembayaran_sks';
        var content_spp = content();
        $(contex).popover({
            placement: 'left',
            title: 'Pembayaran SKS',
            html:true,
            content: content_spp,
        })
    }

    function bayar_lab(id, contex, e) {
        kode_status_perkuliahan = id;
        key = 'pembayaran_lab';
        var content_spp = content();
        $(contex).popover({
            placement: 'left',
            title: 'Pembayaran LAB',
            html:true,
            content: content_spp,
        })
    }

    function kirim(contex) {
        var data = $(contex).closest('form').serialize();
        var url = $(contex).closest('form').prop('action');
        $.ajax({
            url : url,
            type : 'post',
            data : data,
            // beforeSend : function(){
            //     $(contex).closest('.pilihan').html('Loading...');
            // },
            success : function (res) {
                var obj = JSON.parse(res);
                if (obj.status == '1'){
                    var html = status(obj.status_pembayaran, obj.id,obj.pembayaran);
                    $(contex).closest('.pilihan').html(html);
                }
            }
        })
    }
</script>