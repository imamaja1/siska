<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/akademik/nilai/get_update_nilai_per_mahasiswa'); ?>" class="btn btn btn-xs btn-success flat"><i class="fa fa-arrow-left"></i> Kembali</a>
        <?php if (!empty($jumlah_data) > 0): ?>

            <button class="btn btn-flat btn-default btn-xs ">Terdapat <b><?= e($jumlah_data) ?> Record</b></button>
            <div class="pull-right">
                <?= e($halaman) ?>
            </div>

        <?php else: ?>

        <?php endif; ?>
    </div>
</div>

<div class="box box-primary flat">
    <div class="box-body">
        <div class="table-responsive">
          	<table class="table demo-table" >
            <!-- <table class="table demo-table" id="table-edit"> -->
                <thead>
                    <tr>	
                        <th id="th">NO.</th>
                        <th id="th">NIM</th>
                        <th id="th">NAMA MAHASISWA</th>	
                        <th id="th">STATUS MATAKULIAH</th>	
                        <th id="th">NILAI HARIAN</th>
                        <th id="th">NILAI UTS</th>
                        <th id="th">NILAI UAS</th>	
                        <th id="th">NILAI AKHIR</th>
                        <th id="th">TIDAK BERHAK (TB)</th>
                    </tr>
                </thead>
                <?php
                $i = 0 + $this->uri->segment(5);
                foreach ($nilai_matakuliah as $row) {
                    ?>
                    <tr class="dark">
                        <td><div align="center"><?php echo ++$i . '.'; ?></div></td>
                        <td style="display:none;"><?= e($row->kode_khs_detail) ?></td>
                        <td><div align="center"><?php echo e($row->nim); ?></div></td>
                        <td><?php echo e($row->nama_mahasiswa); ?></td>	
                        <td align="center"><?= e($row->status) ?></td>	
                        <td style="text-align:center;"><?= e($row->nilai_harian) ?></td>
                        <td style="text-align:center;"><?= e($row->nilai_uts) ?></td>
                        <td style="text-align:center;"><?= e($row->nilai_uas) ?></td>
                        <td style="text-align:center;"><?= e($row->nilai_akhir) ?></td>
                        <td align="center">
                            <?php
                            $status = $row->tidak_berhak;
                            if ($status == "N") {
                                echo "Berhak";
                            } else if ($status == "A") {
                                echo "Tidak Berhak";
                            } else {
                                echo "";
                            }
                            ?>
                        </td> 	
                    </tr>
                    <?php
                }
                ?>

            </table>

        </div>
    </div>
</div>
<?= $this->session->flashdata('message') ?>
<?php
if (!empty($link)) {
    echo '<p id="bottom_link">';
    foreach ($link as $links) {
        echo $links . ' ';
    }
    echo '</p>';
}
?>

<script type="text/javascript">
    $('#table-edit').Tabledit({
        url: "<?= site_url('admin/akademik/nilai/get_all_mahasiswa_for_update_nilai_matakuliah_process') ?>",
        hideIdentifier: true,
        deleteButton: false,
        buttons: {
            save: {
                class: 'btn btn-sm btn-success',
                html: 'Save'
            }
        },
        columns: {
            identifier: [1, 'kode_khs_detail'],
            editable: [[5, 'nilai_harian'], [6, 'nilai_uts'], [7, 'nilai_uas'], [8, 'nilai_akhir'], [9, 'tidak_berhak', '{"1": "Berhak", "2": "Tidak Berhak"}']],
        }
    });

    function ref() {
        var url = window.location.href;
        window.location.href = url;
    }
</script>