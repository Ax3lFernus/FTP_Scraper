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

if(isset($_POST['selected_files'])){
    $_SESSION['selected_files'] = $_POST['selected_files'];
    echo '{"result": true, "JSON" : ' . $_SESSION['selected_files'] . ']';
}else
    echo '{"result": false, "error": "No selected_file array passed!"}';
