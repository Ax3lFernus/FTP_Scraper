<?php
session_start();
require __DIR__ . '/functions.php';
$csv = "file_name, file_path, date_last_modification\n";
$num_file = 0;
$download_log = '';
$_SESSION['start_download'] = gmdate("d-m-Y H:i:s");
$regex = '';
if(isset($_SESSION['old_id']))
    if($_SESSION['old_id'] != '')
        delete_directory(dirname(__DIR__, 1) . '/tmp/' . $_SESSION['old_id']);

if (!isset($_SESSION['ftp_vars'])) {
    session_unset();
    session_destroy();
    header("Location: /index.php");
    exit();
}

function downloadRecursiveFile($ftp, $tmpDir, $path){
    global $csv, $num_file, $download_log, $regex;
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
        if(preg_match($regex, $filename) != false || !(isset($_GET['files']))) {
            $num_file++;
            $csv .= '"' . $filename . '","' . $save_path . '",' . gmdate("Y-m-d H:i:s", $ftp->mdtm($path)) . "\n";
            $ftp->get($save_path, $path);
            $download_log .= "\n[" . gmdate("d-m-Y H:i:s") . " GMT] Download di " . $path . " effettuato.";
        }
    }
}

if(isset($_SESSION['selected_files'])){
    if(isset($_GET['files']))
        $regex = '/^.*\.(' . $_GET['files'] . ')$/i';
    $selected = $_SESSION['selected_files'];
    $zipName = $_SESSION['id'];
    $request_date = gmdate("d-m-Y H:i:s");
    $request_date_underscore = gmdate("d-m-Y_H-i-s");
    $tmpDir = dirname(__DIR__, 1) . '/tmp/' . $zipName;
    $ftp = new \FtpClient\FtpClient();
    try {
        $ftp->connect($_SESSION['ftp_vars']['server'], $_SESSION['ftp_vars']['protocol'], $_SESSION['ftp_vars']['port']);
        $ftp->login($_SESSION['ftp_vars']['username'], $_SESSION['ftp_vars']['password']);
        if(isset($_SESSION['pasv'])){
            $ftp->pasv($_SESSION['pasv']);
        }
        $download_log .= "\n[" . gmdate("d-m-Y H:i:s") . " GMT] Riconnessione effettuata. Inizio download dei file e directory.";
        if (file_exists($tmpDir))
            delete_directory($tmpDir);
        create_folder($tmpDir);
        create_folder($tmpDir . '/content');
        create_folder($tmpDir . '/content/files');
        foreach (json_decode($_SESSION['selected_files']) as $sel){
            downloadRecursiveFile($ftp, $tmpDir . '/content/files', $sel);
        }
        $download_log .= "\n[" . gmdate("d-m-Y H:i:s") . " GMT] Download dei file e directory completato.";
        zipFolder($tmpDir . '/content/files', 'files');
        delete_directory($tmpDir . '/content/files');
        file_put_contents( $tmpDir . '/content/file_list.csv', $csv);
        zipFolder($tmpDir . '/content', 'download_' . $_SESSION['id']);
        delete_directory($tmpDir . '/content');
        $download_log .= "\n[" . gmdate("d-m-Y H:i:s") . " GMT] Cartella compressa creata.";
        $_SESSION['MD5'] = md5_file($tmpDir . '/download_' . $_SESSION['id'] . '.zip');
        $_SESSION['SHA'] = hash_file('sha256',$tmpDir . '/download_' . $_SESSION['id'] . '.zip');
        $download_log .= "\n[" . gmdate("d-m-Y H:i:s") . " GMT] MD5: " . md5_file($tmpDir . '/download_' . $_SESSION['id'] . '.zip');
        $download_log .= "\n[" . gmdate("d-m-Y H:i:s") . " GMT] SHA-256: " . hash_file('sha256', $tmpDir . '/download_' . $_SESSION['id'] . '.zip');
        require __DIR__ . '/getReport.php';
        $download_log .= "\n[" . gmdate("d-m-Y H:i:s") . " GMT] Report generato.";
        file_put_contents( $tmpDir . '/log_' . $_SESSION['id'] . '.txt', $_SESSION['log'] . $download_log);
        $download_log = "";
        $_SESSION['old_id'] = $_SESSION['id'];
        $_SESSION['id'] = generateRandomString(24);
        header("location:../download.php");
    } catch (\FtpClient\FtpException $e) {
        $_SESSION['log'] .= "\n[" . gmdate("d-m-Y H:i:s") . " GMT] Errore durante la connessione al server.";
        delete_directory($tmpDir);
        echo '{"result": false, "error": "Errore durante la connessione al server FTP!"}';
        exit();
    }
}else
    echo '{"result": false, "error": "No selected_file array passed!"}';
