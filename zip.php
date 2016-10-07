<?php

$zip = new ZipArchive();
$zip->open('pos_20160714.zip',  ZipArchive::CREATE);
$srcDir = "pos";
$files= scandir($srcDir);
//var_dump($files);
unset($files[0],$files[1]);
foreach ($files as $file) {
  $zip->addFile("{$file}");    
}
$zip->close();