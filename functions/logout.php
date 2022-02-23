<?php
session_start();
require __DIR__ . '/functions.php';

header('Content-Type: application/json');
if(isset($_SESSION['ftp_vars'])){
    unset($_SESSION['ftp_vars']);
    session_unset();
    session_destroy();
    echo json_encode("{\"success\": true}");
}else{
    echo json_encode("{\"success\": false, \"error\": \"No token found in cookies.\"}");
}