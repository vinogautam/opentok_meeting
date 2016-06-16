<?php
 $data = $_POST['data'];
$path = 'test.zip';
//list($type, $data) = explode(';', $data);
//list(, $data) = explode(',', $data);
//$data = base64_decode($data);
file_put_contents($path, $data);