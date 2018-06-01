<?php

$data = $_POST['data'];


header("Content-Description: File Transfer"); 
header("Content-Type: application/octet-stream"); 
header("Content-Disposition: attachment; filename='" . "canvas.png" . "'"); 

header('Content-Type: image/png');

list($type, $data) = explode(';', $data);
list(, $data)      = explode(',', $data);
$data = base64_decode($data);

echo $data;

?>