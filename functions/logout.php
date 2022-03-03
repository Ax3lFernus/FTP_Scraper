<?php
session_start();
require __DIR__ . '/functions.php';

if(isset($_SESSION['ftp_vars'])){
    unset($_SESSION['ftp_vars']);
    session_unset();
    session_destroy();
    header('Location: /index.php?info=Logout effettuato.');
}else{
    header('Location: /index.php');
}