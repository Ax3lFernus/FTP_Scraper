<?php
session_start();
require __DIR__ . '/functions.php';
$csv = "file_name, file_path, date_last_modification\n";
//header('Content-Type: application/json; charset=utf-8');
if (!isset($_SESSION['ftp_vars'])) {
    session_unset();
    session_destroy();
    header("Location: /index.php");
    exit();
}

function downloadRecursiveFile($ftp, $tmpDir, $path){
    global $csv;
    $save_path = $tmpDir . '/' . $path;
    if($ftp->isDir($path)){
        create_folder($save_path);
        foreach($ftp->scanDir($path) as $key => $item){
            $pos = strpos($key, "#");
            $path_dir = substr($key, $pos + 1);
            downloadRecursiveFile($ftp, $tmpDir, $path_dir);
        }
    } else {
        $expl_path = explode('/', $save_path);
        $filename = array_pop($expl_path);
        $expl_path = implode('/', $expl_path);
        create_folder($expl_path);
        $csv .= '"' . $filename . '","' . $save_path . '",' . gmdate("Y-m-d H:i:s", $ftp->mdtm($path)) . "\n";
        $ftp->get($save_path, $path);
        $_SESSION['log'] .= "\n[" . date("Y-m-d H:i:s") . "] Download di " . $path . " effettuato.";
    }
}

if(isset($_SESSION['selected_files'])){
    $selected = $_SESSION['selected_files'];
    $zipName = $_SESSION['id'];
    $request_date = gmdate("d-m-Y H:i:s");
    $request_date_underscore = gmdate("d-m-Y_H-i-s");
    $tmpDir = dirname(__DIR__, 1) . '/tmp/' . $zipName;
    print($selected);
    $ftp = new \FtpClient\FtpClient();
    try {
        $ftp->connect($_SESSION['ftp_vars']['server'], $_SESSION['ftp_vars']['protocol'], $_SESSION['ftp_vars']['port']);
        $ftp->login($_SESSION['ftp_vars']['username'], $_SESSION['ftp_vars']['password']);
        $ftp->pasv(true);
        $_SESSION['log'] .= "\n[" . date("Y-m-d H:i:s") . "] Connessione effettuata. Inizio download dei file e directory.";
        if (file_exists($tmpDir))
            delete_directory($tmpDir);
        create_folder($tmpDir);
        create_folder($tmpDir . '/content');
        create_folder($tmpDir . '/content/files');
        foreach (json_decode($_SESSION['selected_files']) as $sel){
            $_SESSION['log'] .= "\n[" . date("Y-m-d H:i:s") . "] Download di " . $sel;
            downloadRecursiveFile($ftp, $tmpDir . '/content/files', $sel);
        }
        $_SESSION['log'] .= "\n[" . date("Y-m-d H:i:s") . "] Download dei file e directory completato. Creazione della cartella di download.";
        zipFolder($tmpDir . '/content/files', 'files');
        delete_directory($tmpDir . '/content/files');
        file_put_contents( $tmpDir . '/content/file_list.csv', $csv);
        zipFolder($tmpDir . '/content', 'download');
        delete_directory($tmpDir . '/content');
        $_SESSION['log'] .= "\n[" . date("Y-m-d H:i:s") . "] Cartella compressa creata.";
        $_SESSION['log'] .= "\n[" . date("Y-m-d H:i:s") . "] MD5: " . md5_file($tmpDir . '/download.zip');
        $_SESSION['log'] .= "\n[" . date("Y-m-d H:i:s") . "] SHA-1: " . sha1_file($tmpDir . '/download.zip');
        file_put_contents( $tmpDir . '/log.txt', $_SESSION['log']);
        $_SESSION['log'] = "";
    } catch (\FtpClient\FtpException $e) {
        $_SESSION['log'] .= "\n[" . date("Y-m-d H:i:s") . "] Errore durante la connessione al server.";
        delete_directory($tmpDir);
        echo '{"result": false, "error": "Errore durante la connessione al server FTP!"}';
        exit();
    }
}else
    echo '{"result": false, "error": "No selected_file array passed!"}';
