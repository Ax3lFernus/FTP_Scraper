<?php
session_start();
require __DIR__ . '/functions.php';
header('Content-Type: application/json; charset=utf-8');
if (!isset($_SESSION['ftp_vars'])) {
    session_unset();
    session_destroy();
    header("Location: /index.php");
    exit();
}

if(isset($_SESSION['selected_files'])){
    $selected = $_SESSION['selected_files'];
    $zipName = generateRandomString(15);
    $request_date = gmdate("d-m-Y H:i:s");
    $request_date_underscore = gmdate("d-m-Y_H-i-s");
    $tmpDir = dirname(__DIR__, 1) . '/tmp/' . $zipName;

    create_folder($tmpDir);

    $files_csv = fopen($tmpDir . '/files_' . $request_date_underscore. '.csv', 'w');
    $json = json_decode($selected, true);
    foreach ($json as $fields) {
        echo $fields;
        fputcsv($files_csv, $fields);
    }
    fclose($files_csv);
    header("location:../download.php");
    echo '{"result": true, "JSON" : ' . $_SESSION['selected_files'] . ']';
}else
    echo '{"result": false, "error": "No selected_file array passed!"}';
