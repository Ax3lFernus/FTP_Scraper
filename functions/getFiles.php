<?php
session_start();
require __DIR__ . '/functions.php';
//header('Content-Type: application/json; charset=utf-8');
if (!isset($_SESSION['ftp_vars'])) {
    session_unset();
    session_destroy();
    header("Location: /index.php");
    exit();
}
// I FILE SE SI TROVANO A INIZIO ARRAY "SELECTED_FILES" NON VENGONO SALVATI PERCHE' SENZA CARTELLA!
function downloadRecursiveFile($ftp, $tmpDir, $path){
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
        array_pop($expl_path);
        $expl_path = implode('/', $expl_path);
        create_folder($expl_path);
        $ftp->get($save_path, $path);
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
        $_SESSION['log'] .= "\n[" . date("Y-m-d H:i:s") . "] Connessione effettuata. Recupero il contenuto di './" . (isset($_GET['path']) == 1 ? $_GET['path'] : "") . "'";
        create_folder($tmpDir);
        create_folder($tmpDir . '/files');
        foreach (json_decode($_SESSION['selected_files']) as $sel){
            downloadRecursiveFile($ftp, $tmpDir . '/files', $sel);
        }
        $_SESSION['log'] .= "\n[" . date("Y-m-d H:i:s") . "] Contenuto di './" . (isset($_GET['path']) == 1 ? $_GET['path'] : "") . "' recuperato";
        file_put_contents( $tmpDir . '/log.txt', $_SESSION['log']);
    } catch (\FtpClient\FtpException $e) {
        $_SESSION['log'] .= "\n[" . date("Y-m-d H:i:s") . "] Errore durante la connessione al server.";
        echo '{"result": false, "error": "Errore durante la connessione al server FTP!"}';
        exit();
    }
}else
    echo '{"result": false, "error": "No selected_file array passed!"}';
