<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$file_name.".xls");
header("Pragma: no-cache");
header("Expires: 0");
echo ! empty($table) ? $table : '';
?>