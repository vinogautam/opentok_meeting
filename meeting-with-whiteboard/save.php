<?php
$data = $_POST['data'];
$file = explode("?", $data)[1];
file_put_contents("zip_files/$file.zip", file_get_contents($data));

$zip = new ZipArchive;
$res = $zip->open("zip_files/$file.zip");
if ($res === TRUE) {
  $zip->extractTo("extract/$file/");
  $zip->close();
	$files = array();
	$i = 0;
    if (is_dir("extract/$file/")){
              if ($dh = opendir("extract/$file/")){
                while (($filee = readdir($dh)) !== false){
                  if($i > 1)
                  $files[] = $filee;
                  $i++;
                }
                closedir($dh);
              }
    }
	echo json_encode(array('folder' => $file, 'files' => $files));
} else {
  echo 'error';
}