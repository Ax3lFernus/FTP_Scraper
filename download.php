<?php
session_start();
require __DIR__ . '/vendor/autoload.php';
if(!isset($_SESSION['ftp_vars'])) {
    session_unset();
    session_destroy();
    header("Location: /index.php");
    exit();
}
$style = "<link href=\"assets/css/message.css\" rel=\"stylesheet\">";
$page_title = "Files";
require 'layouts/head.php';

$ftp = new \FtpClient\FtpClient();
try {
    $ftp->connect($_SESSION['ftp_vars']['server'], $_SESSION['ftp_vars']['protocol'], $_SESSION['ftp_vars']['port']);
    $ftp->login($_SESSION['ftp_vars']['username'], $_SESSION['ftp_vars']['password']);
    $ftp->pasv(true);
    var_dump($ftp->scanDir('.', true));
} catch (\FtpClient\FtpException $e) {
    echo $e;
}
?>

<body >
<nav id="navbar_top" class="navbar navbar-dark bg-dark px-3">
    <a class="navbar-brand" href="#">
        <img src="<?php echo $link . '/assets/images/logo.png'; ?>" width="30" height="30"
             class="d-inline-block align-top" alt="">
        FTP Scraper
    </a>
    <button id="logout" class="btn btn-danger" type="button">Logout</button>
</nav>
<div id="page_body" class="container" style="display:none;">
    <div class="alert alert-danger fade" id="alertError" style="margin-top: 5px;" role="alert">
        <strong>Errore: </strong><span id="alertText">Seleziona almeno un file.</span>
    </div>
    <fieldset class="border mt-3 p-2">
        <legend class="text-center">Seleziona i file</legend>
        <div class="row mt-3">
            <div class="col-3">
                <select class="form-select" name="path">
                    <option value="" selected="">/</option>
                    <option value="/Cartella1">/Cartella 1</option>
                    <option value="/Cartella2">/Cartella 2</option>
                </select>
                <label for="path"></label>
            </div>
            <div class="col-6" style="margin: auto 0">
                <div class="row mx-5" id="checkboxlist">
                    <div class="col">
                        <input rel="user" type="checkbox" id="select_all_chat_user" name="select_all_chat_user">
                        <label class="form-check-label" for="select_all_chat_user">Utenti</label>
                    </div>
                    <div class="col">
                        <input rel="channel" type="checkbox" id="select_all_chat_channel" name="select_all_chat_channel">
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
                        <th class="col-1" scope="col"> <div class="form-check form-switch ps-5">
                                <input class="form-check-input" type="checkbox" id="check_all_chats">
                                <label class="form-check-label" for="check_all_chats"></label>
                            </div></th>
                        <th class="col-7" scope="col">File</th>
                        <th scope="col">Dimensione</th>
                        <th scope="col">Ultima modifica</th>
                    </tr>
                    </thead>
                    <tbody id="chat_list">
                   <?php
                    /*for ($i = 0; $i < count($chat_list); $i++) {
                        echo ' <tr>
                                <td><img name="img" src="./functions/profilePicture.php?peerType=' . $chat_list[$i]['peer']['_'] . '&peerIdType=' . array_keys($chat_list[$i]['peer'])[1] . '&peerId=' . $chat_list[$i]['peer'][array_keys($chat_list[$i]['peer'])[1]] . '" onerror="this.onerror=null;this.src=\'./assets/images/default_user.png\';" style="border-radius: 50%" width="30px" height="30px"></td>
                                <td> <p>' . $chat_list[$i]['name'] . ' </p></td>
                                <td><input type="checkbox" name="user"></td>
                                <input type="hidden" value="' . $chat_list[$i]['id'] . '" name="chatID"><input type="hidden" value="' . htmlspecialchars($chat_list[$i]['name']) . '" name="chatName"><input type="hidden" value="' . $chat_list[$i]['type'] . '" name="chatType">
                                <input type="hidden" value="' . $chat_list[$i]['peer']['_'] . '" name="peerType">
                                <input type="hidden" value="' . array_keys($chat_list[$i]['peer'])[1] . '" name="peerIdType">
                                <input type="hidden" value="' . $chat_list[$i]['peer'][array_keys($chat_list[$i]['peer'])[1]] . '" name="peerId">
                              </tr>';
                    }*/
                    ?>
                    </tbody>
                </table>
            </div>

    </fieldset>
    <fieldset class="border mt-3 p-2">
        <legend>Imposta i parametri</legend>
        <div class="row mt-4">
            <div class="col"><label for="dataInizio">Data inizio:</label> <input id="dataInizio" name="dataInizio"
                                                                                 type="date"
                                                                                 value='2013-08-14'
                                                                                 min='2013-08-14'
                                                                                 class="form-control"
                                                                                 max="<?php echo date('Y-m-d'); ?>">
                <div class="invalid-feedback">Inserisci una data inizio minore della data fine</div>
            </div>
            <div class="col"><label for="dataFine">Data fine:</label> <input id="dataFine" name="dataFine"
                                                                             type="date"
                                                                             value= <?php echo date('Y-m-d'); ?> min='2013-08-14'
                                                                             class="form-control"
                                                                             max="<?php echo date('Y-m-d'); ?>">
                <div class="invalid-feedback">Inserisci una data inizio minore della data fine</div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col"></div>
            <div class="col-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="user_list">
                    <label class="form-check-label" for="user_list">Scaricare la lista degli utenti nei gruppi</label>
                </div>
            </div>
            <div class="col"></div>
        </div>
        <div class="row mt-3">
            <div class="col"></div>
            <div class="col-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="profile_pic">
                    <label class="form-check-label" for="profile_pic">Scaricare le immagini del profilo</label>
                </div>
            </div>
            <div class="col"></div>
        </div>
        <div class="row mt-3">
            <div class="col"></div>
            <div class="col-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="media">
                    <label class="form-check-label" for="media">Includere i media</label>
                </div>
            </div>
            <div class="col"></div>
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
    <p class="mt-5 pb-2 text-muted">FTP Scraper &copy; 2021-<?php echo date('Y'); ?></p>
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
                    <li><b>Hash del report MD5: </b><p id="md5_files" class="text-break">Errore</p></li>
                    <li><b>Hash del report SHA256: </b><p id="sha_files" class="text-break">Errore</p></li>
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
<script src="./assets/js/message.js"></script>
</body>
</html>