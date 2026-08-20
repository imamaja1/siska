<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/akademik/kpat/krs') ?>" class="btn btn-success btn-sm flat"><i class="fa fa-arrow-left"></i> Kembali</a>
    <span class="badge bg-teal pull-right">Nama mahasiswa : <?= e($mahasiswa->nama_mahasiswa) ?></span>
    </div>
</div>
<?php if (count($data) > 0) : ?>
<form id="form-krs-kpat" method="POST" name="krs_form" action="<?= site_url('admin/akademik/kpat/krs/simpan_krs')  ?>">
<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
<div class="box box-primary flat">
	<div class="box-body flat">
        <p style="text-align: center;"><strong>MATAKULIAH KPAT</strong></p>
        <div class="table-responsive">
		<table class="table demo-table">
			<thead>
				<tr>
					<th id="th" rowspan="2" style="padding-top: -10px;">No.</th>
					<th id="th" rowspan="2">KODE</th>
					<th id="th" rowspan="2">Nama Matakuliah</center></th>
					<th id="th" colspan="3">SKS</th>
					<th id="th" rowspan="2">Nilai Sebelumnya</th>
					<th id="th" rowspan="2">Grade</th>
					<th id="th" rowspan="2">K</th>
				</tr>
				<tr>
					<th id="th">T</th>
					<th id="th">PK</th>
					<th id="th">PT</th>
				</tr>
			</thead>
			<tbody>
			<?php $i =1; $j=0; foreach ($data['data'] as $key) : ?>
				<tr>
					<td colspan="9" ><center><strong>SEMESTER <?= e($key['semester'])  ?></strong></center></td>
				</tr>
				<?php $i= 1; foreach ($key['data_nilai'] as $row) : ?>
					<tr>
						<td style="text-align: center;"><?= $i++."."  ?></td>
<td style="text-align: center;"><?= e($row['kode_matakuliah'])  ?></td>
                        <td><?= e($row['nama_matakuliah'])  ?> <?= e($row['ket'] == 0 ? "<span class='badge bg-red'>Belum ambil</span>" : "") ?></td>
                        <td style="text-align: center;"><?= e($row['sks_teori'] == (0) ? "" : $row['sks_teori'])  ?></td>
                        <td style="text-align: center;"><?= e($row['sks_praktek'] == (0) ? "" : $row['sks_praktek']) ?></td>
                        <td style="text-align: center;"><?= e($row['sks_praktikum'] == (0) ? "" : $row['sks_praktikum']) ?></center></td>
                        <td style="text-align: center;"><?= e(number_format($row['nilai_akhir'],2))  ?></td>
                        <td style="text-align: center;"><?= e($row['grade'])  ?></td>
						<td style="text-align: center;">
							<input name="kpat[]" class="check-kpat" type="checkbox" value="<?= e($row['id_matakuliah'])  ?>,<?= e($row['kode_matakuliah'])  ?>" onclick="calculate()" ></input>
                            <input name="nim" type="hidden" value="<?= e($mahasiswa->nim) ?>"></input>
                        </td>
					</tr>
				<?php endforeach; ?>
			<?php endforeach; ?>
			</tbody>
		</table>
        </div>
	</div>
</div>
<div class="box box-solid flat">
	<div class="box-body" id="loading">
		<button type="submit" name="submit" id="submit" class="btn btn-primary btn-sm flat"><i class="fa fa-check-square-o"></i> Simpan</button>
		&nbsp; <a class="btn btn-danger btn-sm flat" onclick="batal()"><i class="fa fa-times"></i> Batal </a>
    </div>
</div>
</form>
<?php else : ?>
	<p class="alert alert-danger"><strong>Data Tidak di Temukan...</strong></p>
<?php endif; ?>

<!-- kotak peas -->
<div class="row" id="kotak-pesan">
	Matakuliah KPAT
</div>


<!-- Script -->
<script type="text/javascript">
	function batal() {
		$('.check-kpat').attr('checked',false);
		$('#kotak-pesan').html('Matakuiah KPAT');
	}

	$(window).bind("load", function() {
    $('#kotak-pesan').animate({bottom:"50px"}, 1000);

    });

    function calculate()
    {
        var elems = document.forms['krs_form'].elements;
        var total = 0;
        var jumlah_maksimum_sks = 0;
        var sisa = 0;
        for(var i=0;i<elems.length;i++)
        {
            if (elems[i].checked)
            {
                //total += +(substr(elems[i].value,4,1));

                str = elems[i].value;
                arr = str.split(",");
                sks = arr[1].substr(4,1);
                total += +(sks);
            }
        }

        jumlah_maksimum_sks = 10;//document.getElementById('jumlah_maksimum_sks').value;
        sisa = jumlah_maksimum_sks - total;

        if (total > jumlah_maksimum_sks)
        {
            $('#kotak-pesan').html('<font color=red>Jumlah SKS matakuliah yang telah Anda pilih adalah <b>'+ total +' SKS</b>, melebihi jumlah maksimum <b>'+ jumlah_maksimum_sks +' SKS</b> yang dapat diambil.</font>');
            $('#submit').prop('disabled',true);
        }
        else if (total == jumlah_maksimum_sks)
        {
            $('#kotak-pesan').html('<font color=green>Jumlah SKS matakuliah yang Anda pilih telah sesuai dengan jumlah maksimum <b>'+ total +' SKS</b> yang dapat diambil.</font>');
            $('#submit').prop('disabled',false);
        }
        else
        {
            $('#kotak-pesan').html('Jumlah SKS matakuliah yang telah Anda pilih adalah <b>'+ total +' SKS</b>, masih tersisa <b>'+ sisa +' SKS</b> yang dapat diambil.');
            $('#submit').prop('disabled',false);
        }
    }
    
    $(document).ready(function () {
       $('#form-krs-kpat').submit(function () {
           var button = $('#loading');
           // Disable the submit button while evaluating if the form should be submitted
           button.html('<button class="btn btn-default btn-sm flat" disabled><i class="fa fa-refresh fa-spin"></i> Permintaan sedang diproses..</button>');
           var valid = true;

           // Do stuff (validations, etc) here and set
           // "valid" to false if the validation fails

           if (!valid) {
               // Prevent form from submitting if validation failed
               e.preventDefault();

               // Reactivate the button if the form was not submitted
               button.html('<button class="btn btn-default btn-sm flat" disabled><i class="fa fa-refresh fa-spin"></i> Permintaan sedang diproses..</button>');

           }
       }) 
    });
</script>