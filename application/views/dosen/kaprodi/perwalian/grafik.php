<div class="box" style="border: 2px solid #3c8dbc; border-radius: 10px">
    <button class="btn btn-flat btn-danger" data-dismiss="modal" style="position: absolute; top: -10px; right: -10px"><i class="fa fa-times"></i></button>
    <div class="box-body" >
        <p style="margin-top: 20px; text-align: center"><b> GRAFIK NILAI MAHASISWA </b></p>
        <p style="margin-top: 20px; text-align: center"><b><i>"<?= $mahasiswa->nim ?> - <?= $mahasiswa->nama_mahasiswa ?>"</i></b></p>
        <hr>
        <canvas id="myChart" width="400" height="400"></canvas>
    </div>
</div>
<script>
    var ctx = document.getElementById('myChart');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($semester) ?>,
            datasets: [{
                label: '# Grafik IPK',
                data: <?= json_encode($ipk) ?>,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 3,
            }],
        },

        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        max : 4
                    }
                }]
            }
        }
    });
</script>