<?php
session_start();
require __DIR__ . '/functions/functions.php';
if (!isset($_SESSION['ftp_vars'])) {
    session_unset();
    session_destroy();
    header("Location: /index.php");
    exit();
}
$style = "<link href=\"assets/css/message.css\" rel=\"stylesheet\">";
$page_title = "Files";
require 'layouts/head.php';
$_SESSION['log'] .= "\n[" . date("Y-m-d H:i:s") . "] Riconnessione per recuperare il contenuto di './" . (isset($_GET['path']) == 1 ? $_GET['path'] : "") . "'";
$ftp = new \FtpClient\FtpClient();
try {
    $ftp->connect($_SESSION['ftp_vars']['server'], $_SESSION['ftp_vars']['protocol'], $_SESSION['ftp_vars']['port']);
    $ftp->login($_SESSION['ftp_vars']['username'], $_SESSION['ftp_vars']['password']);
    $ftp->pasv(true);
    $_SESSION['log'] .= "\n[" . date("Y-m-d H:i:s") . "] Connessione effettuata. Recupero il contenuto di './" . (isset($_GET['path']) == 1 ? $_GET['path'] : "") . "'";
    if (isset($_GET['path'])) {
        $items = $ftp->scanDir($_GET['path']);
    } else
        $items = $ftp->scanDir();
    $_SESSION['log'] .= "\n[" . date("Y-m-d H:i:s") . "] Contenuto di './" . (isset($_GET['path']) == 1 ? $_GET['path'] : "") . "' recuperato";
} catch (\FtpClient\FtpException $e) {
    $_SESSION['log'] .= "\n[" . date("Y-m-d H:i:s") . "] Errore durante la connessione al server.";
    echo $e;
}
//create_folder(__DIR__ . '/tmp/' . $_SESSION['id']); CREATE FOLDER
//file_put_contents( __DIR__ . '/tmp/' . $_SESSION['id'] . '/log.txt', $_SESSION['log']); SAVE LOGS
?>

<body>
<nav id="navbar_top" class="navbar navbar-dark bg-dark px-3">
    <a class="navbar-brand" href="#">
        <img src="<?php echo $link . '/assets/images/logo.png'; ?>" width="30" height="30"
             class="d-inline-block align-top" alt="">
        FTP Scraper
    </a>
    <button onclick="window.location.href = '/functions/logout.php'" class="btn btn-danger" type="button">Logout</button>
</nav>
<div id="page_body" class="container" style="display:none;">
    <div class="alert alert-danger fade" id="alertError" style="margin-top: 5px;" role="alert">
        <strong>Errore: </strong><span id="alertText">Seleziona almeno un file.</span>
    </div>
    <fieldset class="border mt-3 p-2">
        <legend class="text-center">Seleziona i file</legend>
        <div class="row mt-3">
            <div class="col-3">
                <select id="select_directory" class="form-select" name="path">
                    <?php
                    if (isset($_GET["path"]))
                        echo ' <option value="">/</option>';
                    else
                        echo ' <option value="" selected>/</option>';

                    if (isset($_GET["path"]))
                        echo ' <option value="' . $_GET["path"] . ' " selected>/' . $_GET["path"] . '</option>';

                    foreach ($items as $key => $item) {
                        if ($item["type"] === "directory")
                            if (isset($_GET["path"]))
                                echo ' <option value="' . $key . '">/' . $_GET["path"] . '/' . $item["name"] . '</option>';
                            else
                                echo ' <option value="' . $key . '">/' . $item["name"] . '</option>';
                    }

                    ?>
                </select>
                <label for="path"></label>
            </div>
            <div class="col-6" style="margin: auto 0">
                <div class="row mx-5" id="checkboxlist">
                    <div class="col">
                        <input rel="directory" type="checkbox" id="select_all_directory" name="select_all_directory">
                        <label class="form-check-label" for="select_all_directory">Directory</label>
                    </div>
                    <div class="col">
                        <input rel="channel" type="checkbox" id="select_all_chat_channel"
                               name="select_all_chat_channel">
                        <label class="form-check-label" for="select_all_chat_channel">Canali</label>
                    </div>
                    <div class="col">
                        <input rel="chat" type="checkbox" id="select_all_chat_groups" name="select_all_chat_groups">
                        <label class="form-check-label" for="select_all_chat_groups">Gruppi</label>
                    </div>
                </div>
            </div>
            <div class="col-3"><input class="form-control" id="search" type="text" placeholder="Cerca tra i file...">
            </div>
        </div>
        <div class="row mt-3" style="height: 300px;overflow: auto;">
            <div class="col tableFixHead">
                <table id="idTable" class="table table-striped">
                    <thead>
                    <tr>
                        <th class="col-1" scope="col">
                            <div class="form-check form-switch ps-5">
                                <input class="form-check-input" type="checkbox" id="check_all_chats">
                                <label class="form-check-label" for="check_all_chats"></label>
                            </div>
                        </th>
                        <th class="col-7" scope="col">File</th>
                        <th style="text-align: right;" scope="col">Dimensione</th>
                        <th style="text-align: right;" scope="col">Ultima modifica</th>
                    </tr>
                    </thead>
                    <tbody id="chat_list">
                    <?php
                    if (isset($_GET["path"])) {
                        $pos = strripos($_GET["path"], "/");
                        $path = substr($_GET["path"], 0, $pos);
                        echo ' <tr id="parent">
                               <td style="text-align: center;">-</td>';
                        if ($path!=="")
                            echo '<td><p><a href="download.php?path=' . $path . '" ><i class="fa-solid fa-arrow-turn-up" style="color: #0d6efd;"></i> Parent </a></p> </td>';
                        else
                        echo '<td><p><a href="download.php" ><i class="fa-solid fa-arrow-turn-up" style="color: #0d6efd;"></i> Parent </a></p> </td>';
                        echo ' <td style="text-align: right;"> <p>- </p></td>
                               <td style="text-align: right;"> <p>- </p></td>
                                </tr>';
                    }

                    foreach ($items as $key => $item) {
                        $pos = strpos($key, "#");
                        $path = substr($key, $pos + 1);
                        echo ' <tr>';

                        if ($item["type"] === "directory")
                            echo '<td style="text-align: center;"><input value="'.$path.'" type="checkbox" rel="directory"  name="user"></td><td><p><a href="download.php?path=' . $path . '" ><i class="fa-solid fa-folder" style="color: #f39200;"></i> ' . $item["name"] . ' </a></p> </td>';
                        else
                            echo '<td style="text-align: center;"><input value="'.$path.'" type="checkbox" rel="file"  name="user"></td><td>  <p><i  class="fa-solid fa-file" style="color: #0d6efd;"></i> ' . $item["name"] . ' </p></td>';
                        if ($item["type"] === "directory")
                        echo ' <td style="text-align: right;"> <p> - </p></td>';
                        else
                            echo ' <td style="text-align: right;"> <p>' . formatBytes($item["size"]) . ' </p></td>';

                        echo '<td style="text-align: right;" > <p >ciao </p></td>
                                </tr>';
                    }

                    ?>
                    </tbody>
                </table>
            </div>

    </fieldset>
    <div class="row mt-3">
        <div class="col-sm-4"></div>
        <div class="col-sm-2">
            <button id="csv" class="btn btn-success" type="button">Download csv</button>
        </div>
        <div class="col-sm-2">
            <button id="json" class="btn btn-success" type="button">Download json</button>
        </div>
        <div class="col-sm-4"></div>
    </div>
    <p class="mt-5 pb-2 text-muted text-center">FTP Scraper &copy; 2021-<?php echo date('Y'); ?></p>
</div>

<!-- Modal -->

<!-- Modal SHA/MD5 -->
<div class="modal fade" id="modalHash" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Download completato</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul style="list-style-type:none;">
                    <li><b>Hash del report MD5: </b>
                        <p id="md5_files" class="text-break">Errore</p></li>
                    <li><b>Hash del report SHA256: </b>
                        <p id="sha_files" class="text-break">Errore</p></li>
                </ul>
                <br/>
                <p>Link per scaricare il report: <a href="" id="report_url" download>Download</a></p>
                <p>Link per scaricare il file zip: <a href="" id="zip_url" download>Download</a></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>
<?php require('layouts/scripts.php'); ?>
<script src="./assets/js/download.js"></script>
<script>
    let selected = <?php if(isset($_SESSION['selected_files'])) echo $_SESSION['selected_files']; else echo "[]";?>;

    $("#csv").on('click', _ => {
            if (selected.length > 0) {
                $('#md5_files').text('Errore');
                $('#sha_files').text('Errore');
                $('#report_url').prop('href', '').text('');
                $('#zip_url').prop('href', '').text('');
                $('#modalLoading').modal({backdrop: 'true', keyboard: false, show: true, focus: true}).modal('show');
                $('#modalTitle').text('Creazione file csv in corso...');
                window.location = "functions/getFiles.php";
            } else {
                $('#alertText').text('Seleziona almeno una chat.');
                $('#alertError').addClass('show');
                setTimeout(_ => $('#alertError').removeClass('show'), 3000);
            }
    });

    $('#select_directory').on('change', function () {
        var url = $(this).val(); // get selected value
        b = url.indexOf('#');
        path = url.substring(b + 1);
        if (path) { // require a URL
            window.location = "download.php?path=" + path; // redirect
        } else {
            window.location = "download.php"; // redirect
        }
        return false;
    });

    $("input[type=checkbox][name='user']").on('click', function () {
        path = $(this).val();
        let index = null;
        for (let i = 0; i < selected.length; i++) {
            if (path.localeCompare(selected[i].path) === 0) {
                index = i;
                break;
            }
        }
        if ($(this).is(":checked") && index == null)
            selected.push({"path" : path});
        else
            selected.splice(index, 1);

        console.log(selected);
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: "/functions/selectedFiles.php",
            data: {selected_files: JSON.stringify(selected)},
            timeout: 120000,
            success: (result) => {
                console.log(result);
                let json = JSON.parse(result);
                if (json.result) {
                    console.log("OK");
                } else {
                    console.log("ERRORE");
                }
            },
            error: (e) => {
                console.log(e);
            }
        });
    });

    $(document).ready(function () {
        for (let i = 0; i < selected.length; i++) {
            $("input[type=checkbox][name='user']").each(function () {
                let val = $(this).val();
                if (val.localeCompare(selected[i].path) === 0) {
                    $(this).prop('checked', 'checked');
                }
            });
        }
    });
</script>
</body>
</html>