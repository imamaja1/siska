<?php
foreach (scanDirectories('./application/controllers') as $row => $value) :
    $pecah = explode('/', $value);
    $end = end($pecah);
    ?>
    <div class="form-group">
        <label for="<?= str_replace('.php','',$end) ?>">
            <input type="checkbox" <?= (in_array(str_replace('.php','',$end), $controller)) ? 'checked' : '' ?>
                   id="<?= e(str_replace('.php','',$end)) ?>" name="controller[]"
                   value="<?= e(str_replace('.php','',$end)) ?>"> <?= e($value) ?>
        </label>
    </div>
<?php endforeach; ?>