<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

<?php if (!empty($data)) : ?>
    <div class="animated shake box box-primary box-solid">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="icon fa fa-info"></i> Result <i><strong>"<?= $nim ?>"</strong></i></h3>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th style="text-align: center">Jenis Pembayaran</th>
                        <th style="text-align: center">Status Pembayaran</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td style="text-align: center">SPP</td>
                        <?php if ($data->pembayaran_spp == '0') : ?>
                            <td style="text-align: center"><span class="badge bg-red">Belum</span></td>
                        <?php elseif ($data->pembayaran_spp == '1') : ?>
                            <td style="text-align: center"><span class="badge bg-green">Sudah</span>
                        <?php else : ?>
                            <td style="text-align: center"><span class="badge bg-orange">Dispen</span>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <td style="text-align: center">SKS</td>
                        <?php if ($data->pembayaran_sks == '0') : ?>
                            <td style="text-align: center"><span class="badge bg-red">Belum</span></td>
                        <?php elseif ($data->pembayaran_sks == '1') : ?>
                        <td style="text-align: center"><span class="badge bg-green">Sudah</span>
                            <?php else : ?>
                        <td style="text-align: center"><span class="badge bg-orange">Dispen</span>
                            <?php endif; ?>
                    </tr>
                    <tr>
                        <td style="text-align: center">LAB</td>
                        <?php if ($data->pembayaran_lab == '0') : ?>
                            <td style="text-align: center"><span class="badge bg-red">Belum</span></td>
                        <?php elseif ($data->pembayaran_lab == '1') : ?>
                        <td style="text-align: center"><span class="badge bg-green">Sudah</span>
                            <?php else : ?>
                        <td style="text-align: center"><span class="badge bg-orange">Dispen</span>
                            <?php endif; ?>
                    </tr>
                    </tbody>
                </table>
            </div>
                <i>NB : Abaikan status pembayaran LAB jika tidak mengambil matakuliah praktikum.</i>
        </div>
    </div>

<?php else: ?>
    <div class="container text-center" style="margin-top:100px" >
        <div class="row">
            <div class="col-md-12">
                <div class="error-template">
                    <h1>
                        Oops!</h1>
                    <h2>
                        404 Not Found</h2>
                    <div class="error-details">
                        Sorry, an error has occured, Requested page not found!
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>