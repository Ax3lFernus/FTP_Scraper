<?php
error_reporting(0);
require dirname(__DIR__, 1) . '/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;
$html2pdf = new Html2Pdf('P', 'A4', 'it');

function generateRandomString($length = 12)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function zipFolder($path, $zipName)
{
    $rootPath = realpath($path);

    $zip = new ZipArchive();
    $zip->open(dirname($path, 1) . '/' . $zipName . '.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $name => $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($rootPath) + 1);

            $zip->addFile($filePath, $relativePath);
        }
    }

    $zip->close();
    return $zipName;
}

function delete_directory($dirname)
{
    if (is_dir($dirname))
        $dir_handle = opendir($dirname);
    if (!$dir_handle)
        return false;
    while ($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
            if (!is_dir($dirname . "/" . $file))
                unlink($dirname . "/" . $file);
            else
                delete_directory($dirname . '/' . $file);
        }
    }
    closedir($dir_handle);
    rmdir($dirname);
    return true;
}

function create_folder($folderName){
    if (!file_exists($folderName))
        mkdir($folderName, 0777, true);
}

function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    $base = log($bytes, 1024);
    return round(pow(1024, $base - floor($base)), $precision) .' '. $units[floor($base)];
}