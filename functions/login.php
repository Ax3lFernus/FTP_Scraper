<?php
require __DIR__ . '/functions.php';

header('Content-Type: application/json');
/*
if (isset($_POST['token']) && isset($_POST['code'])) {
    $token = $_POST['token'];
    $code = $_POST['code'];
    $output = curl($baseUrl . "api/users/" . $token . "/completePhoneLogin?code=" . $code);
    if ($output->success)
        echo json_encode("{\"success\": true, \"token\": \"" . $token . "\"}");
    else {
        deleteMadelineSession($token);
        echo json_encode("{\"success\": false, \"error\": \"Wrong verification code or token\"}");
    }
} else {*/
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
            echo json_encode("{\"success\": true, \"token\": \"" . $token . "\"}");
        } catch (\FtpClient\FtpException $e) {
            echo json_encode("{\"success\": false, \"error\": \"Unable to connect to server\"}");
        }
        //Crea la sessione sul TelegramApiServer
      /* $output = curl($baseUrl . "system/addSession?session=users/" . $token);
        if ($output->success) {
            //Collegamento Cellulare
            $output = curl($baseUrl . "api/users/" . $token . "/phoneLogin?phone=" . $tel);
            if ($output->success)
                echo json_encode("{\"success\": true, \"token\": \"" . $token . "\"}");
            else {
                deleteMadelineSession($token);
                echo json_encode("{\"success\": false, \"error\": \"Error sending verification code\"}");
            }
        } else
            echo json_encode("{\"success\": false, \"error\": \"Error while creating the session\"}");*/
    } else {
        echo json_encode("{\"success\": false, \"error\": \"No data passed\"}");
    }
//}