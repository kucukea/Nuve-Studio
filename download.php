<?php
$dil=trim($_GET["l"]);
$zipArchive = new ZipArchive();
$zipFilePath = "$dil".".zip";
$status = $zipArchive->open($zipFilePath,  ZipArchive::CREATE | ZipArchive::OVERWRITE);
$filesToAdd = array(
    $_SERVER['DOCUMENT_ROOT']."/storage/app/Resources/$dil/morphotactics.xml",
    $_SERVER['DOCUMENT_ROOT']."/storage/app/Resources/$dil/orthography.xml",
    $_SERVER['DOCUMENT_ROOT']."/storage/app/Resources/$dil/roots.csv",
    $_SERVER['DOCUMENT_ROOT']."/storage/app/Resources/$dil/suffixes.csv"
);

foreach ($filesToAdd as $f) {
    $filename_parts = explode('/', $f);
    @$zipArchive->addFile($f, end($filename_parts)) or die ("ERROR: Could not add file: ".end($filename_parts));   
}
 
$zipArchive->close();
 
$zipBaseName = basename($zipFilePath);
 
header("Content-Type: application/zip");
header("Content-Disposition: attachment; filename=$zipBaseName");
header("Content-Length: " . filesize($zipFilePath));
readfile($zipFilePath);
exit;
?>