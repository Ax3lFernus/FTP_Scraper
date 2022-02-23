<?php
session_start();
require __DIR__ . '/functions.php';

header('Content-Type: application/json');
if (isset($_POST['server']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['port']) && isset($_POST['protocol'])) {
    $server =$_POST['server'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $port = $_POST['port'];
    $protocol = $_POST['protocol'];

    $ftp = new \FtpClient\FtpClient();
    try {
        $ftp->connect($server, $protocol == 'true', $port);
        $ftp->login($username, $password);
        $token = generateRandomString(24);
        $_SESSION['ftp_vars'] = array('server' => $server, 'username' => $username, 'password' => $password, 'port' => $port, 'protocol' => ($protocol == 'true'));
        echo json_encode("{\"success\": true}");
        exit();
    } catch (\FtpClient\FtpException $e) {
        echo json_encode("{\"success\": false, \"error\": \"Credenziali errate.\"}");
    }
} else {
    echo json_encode("{\"success\": false, \"error\": \"Non sono stati passati tutti i dati per effettuare l'accesso.\"}");
}
session_destroy();