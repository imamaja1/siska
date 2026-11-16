<?php if (!empty($kompetensi_mahasiswa)) : ?>
<div class="prasyarat-wrapper">

    <!-- 1. UNIFIED PAGE HEADER CARD -->
    <div class="prasyarat-header-card">
        <div>
            <h2 class="prasyarat-title">Kompetensi Mahasiswa</h2>
            <p class="prasyarat-sub">Bidang Keahlian / Peminatan Konsentrasi Program Studi Mahasiswa</p>
        </div>
    </div>

    <!-- 2. STANDARDIZED TABLE CARD -->
    <div class="prasyarat-table-card">
        <div class="prasyarat-table-head-bar">
            <h4 class="prasyarat-table-title">
                Data Pilihan Bidang Kompetensi
            </h4>
            <span class="prasyarat-table-badge">
                Terdaftar
            </span>
        </div>

        <div class="table-responsive" style="margin: 0; border: none;">
            <table class="prasyarat-table-grid">
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">NO</th>
                        <th style="width: 140px; text-align: center;">NIM</th>
                        <th>NAMA MAHASISWA</th>
                        <th style="width: 140px; text-align: center;">KODE</th>
                        <th>BIDANG KOMPETENSI / KONSENTRASI</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center; color: #64748B; font-weight: 600;">1.</td>
                        <td style="text-align: center;" class="prasyarat-td-code"><?= e($kompetensi_mahasiswa->nim) ?></td>
                        <td class="prasyarat-td-name"><?= e($this->session->userdata('nama_mahasiswa') ?? '-') ?></td>
                        <td style="text-align: center;" class="prasyarat-td-syarat-code"><?= e($kompetensi_mahasiswa->kode_kompetensi ?? '-') ?></td>
                        <td class="prasyarat-td-syarat-name">
                            <b><?= e($kompetensi_mahasiswa->nama_kompetensi) ?></b>
                            <?php if (!empty($kompetensi_mahasiswa->singkatan_kompetensi)): ?>
                                (<?= e($kompetensi_mahasiswa->singkatan_kompetensi) ?>)
                            <?php endif; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="prasyarat-table-footer-bar">
            <div>
                Status: <b>Telah Terdaftar &amp; Terverifikasi</b>
            </div>
            <div>
                Sistem Informasi Akademik &mdash; Universitas Bumigora
            </div>
        </div>
    </div>

</div>
<?php else: ?>
    <div class="modal fade" id="tambah-kompetensi">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Kompetensi Mahasiswa</h4>
                </div>
                <form action="<?= site_url('mahasiswa/kompetensi/simpan') ?>" method="post">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for=""> Kompetensi</label>
                            <select name="kode_kompetensi" required id="kode_kompetensi" class="form-control">
                                <option selected disabled>Pilih</option>
                                <?php foreach ($data_kompetensi as $row) : ?>
                                    <option value="<?= e($row->kode_kompetensi) ?>"><?= e($row->singkatan_kompetensi) ?>
                                        - <?= e($row->nama_kompetensi) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-info"><i class="fa fa-info-circle"></i> Pilih kompetensi anda. pilih salah satu kompetensi di atas untuk mengecek matakulah konsentrasi dan
                            untuk mengecek ketersediaan konsntrasi pada kurikulum anda.</small>
                        </div>
                        <div id="landing">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger flat" data-dismiss="modal"><i
                                    class="fa fa-times"></i> Batal
                        </button>
                        <button type="submit" name="submit" id="submit" disabled class="btn btn-primary flat"><i
                                    class="fa fa-check-square-o"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <script>
        $(document).ready(function () {
            $('#tambah-kompetensi').modal('show');

            $("#kode_kompetensi").change(function (e) {
                var kode_kompetensi = $(this).val();
                var url = "<?= site_url('mahasiswa/kompetensi/matakuliah_konsentrasi') ?>/"+kode_kompetensi;
                $.ajax({
                    url : url,
                    type : 'get',
                    success : function (res) {
                        obj = JSON.parse(res);
                        // console.log(obj);
                        $("#landing").html(obj.view)
                        if (obj.status){
                            $("#submit").prop('disabled',false)
                        }else{
                            $("#submit").prop('disabled',true)
                        }
                    },
                    error : function (err) {
                        console.log(err);
                    }
                })
            })
        })
    </script>
<?php endif; ?>
