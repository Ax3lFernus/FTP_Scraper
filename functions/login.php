<?php
session_start();
require __DIR__ . '/functions.php';
if (isset($_POST['server']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['port']) && isset($_POST['protocol'])) {
    $server =$_POST['server'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $port = $_POST['port'];
    $protocol = $_POST['protocol'] == 'true';

    $ftp = new \FtpClient\FtpClient();
    try {
        $ftp->connect($server, $protocol, $port);
        $ftp->login($username, $password);
        $token = generateRandomString(24);
        $_SESSION['ftp_vars'] = array('server' => $server, 'username' => $username, 'password' => $password, 'port' => $port, 'protocol' => ($protocol == 'true'));
        header('Location: /download.php');
        exit();
    } catch (\FtpClient\FtpException $e) {
        header('Location: /index.php?error=Credenziali errate.');
    }
} else {
    header('Location: /index.php?error=Non sono stati passati tutti i dati per effettuare l\'accesso.');
}
session_unset();
session_destroy();