<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>CEK Double</title>
</head>
<body>
<div class="container">
    <h1>Cek Double</h1>
    <hr>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>No</th>
            <th>Kode KRS</th>
            <th>Nim</th>
            <th>Semester</th>
            <th>Jml</th>
            <th>#</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $no = 1;
        foreach ($data as $d) :
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= e($d->kode_krs) ?></td>
                <td><?= e($d->nim) ?></td>
                <td><?= $d->semester; ?></td>
                <td><?= $d->cnim; ?></td>
                <td>
                    <a href="<?= site_url('admin/double/detail/' . $d->nim . '/' . $d->semester) ?>"
                       class="btn btn-sm btn-success">Nilai</a>
                </td>
            </tr>
        <?php
        endforeach;
        ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>

</body>
</html>