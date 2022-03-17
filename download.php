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
$_SESSION['log'] .= "\n[" . gmdate("d-m-Y H:i:s") . " GMT] Riconnessione per recuperare il contenuto di './" . (isset($_GET['path']) == 1 ? $_GET['path'] : "") . "'";
$ftp = new \FtpClient\FtpClient();
try {
    $ftp->connect($_SESSION['ftp_vars']['server'], $_SESSION['ftp_vars']['protocol'], $_SESSION['ftp_vars']['port']);
    $ftp->login($_SESSION['ftp_vars']['username'], $_SESSION['ftp_vars']['password']);
    $ftp->pasv(true);
    $_SESSION['log'] .= "\n[" . gmdate("d-m-Y H:i:s") . " GMT] Connessione effettuata. Recupero il contenuto di './" . (isset($_GET['path']) == 1 ? $_GET['path'] : "") . "'";
    if (isset($_GET['path'])) {
        $items = $ftp->scanDir($_GET['path']);
    } else
        $items = $ftp->scanDir();
    $_SESSION['log'] .= "\n[" . gmdate("d-m-Y H:i:s") . " GMT] Contenuto di './" . (isset($_GET['path']) == 1 ? $_GET['path'] : "") . "' recuperato";
} catch (\FtpClient\FtpException $e) {
    $_SESSION['log'] .= "\n[" . gmdate("d-m-Y H:i:s") . " GMT] Errore durante la connessione al server.";
    echo $e;
}
$parent_selected = false;
if (isset($_GET["path"]))
    if (isset($_SESSION['selected_files']))
        foreach (json_decode($_SESSION['selected_files']) as $element){
            if(str_starts_with($_GET['path'], $element)){
                $parent_selected = true;
            }
        }
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
<div id="page_body" class="container">
    <div class="alert alert-danger" id="alertError" style="margin-top: 5px; opacity: 0;" role="alert">
        <strong>Errore: </strong><span id="alertText">Seleziona almeno un file.</span>
    </div>
    <fieldset class="border mt-3 p-2">
        <legend class="text-center">Seleziona i file</legend>
        <div class="row mt-3">
            <div class="col-3">
                <select id="select_directory" class="form-select" name="path">
                    <?php
                    if (isset($_GET["path"])) {
                        echo ' <option value="/">/</option>';
                        $expl_path = explode('/', $_GET["path"]);
                        foreach ($expl_path as $i => $el) {
                            echo ' <option value="' . $el . '" ' . ($i === array_key_last($expl_path) ? 'selected' : '') . ' >/' . $el . '</option>';
                        }
                    } else
                        echo ' <option value="/" selected>/</option>';
                    /*
                    foreach ($items as $key => $item) {
                        if ($item["type"] === "directory")
                            if (isset($_GET["path"])) {
                                echo ' <option value="' . $key . '">/' . $_GET["path"] . '/' . $item["name"] . '</option>';
                            } else
                                echo ' <option value="' . $key . '">/' . $item["name"] . '</option>';
                    }*/
                    ?>
                </select>
                <label for="path"></label>
            </div>
            <div class="col-6" style="margin: auto 0">
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
                                <input class="form-check-input" type="checkbox" id="check_all_chats" <?php if($parent_selected) echo "checked disabled"; ?>>
                                <label class="form-check-label" for="check_all_chats"></label>
                            </div>
                        </th>
                        <th class="col-7" scope="col">File</th>
                        <th style="text-align: right;" scope="col">Dimensione</th>
                        <th style="text-align: right;" scope="col">Ultima modifica</th>
                    </tr>
                    </thead>
                    <tbody id="element_list">
                    <?php
                    if (isset($_GET["path"])) {
                        $pos = strripos($_GET["path"], "/");
                        $path = substr($_GET["path"], 0, $pos);
                        echo ' <tr id="parent">
                               <td style="text-align: center;">-</td>';
                        if ($path !== "")
                            echo '<td><p><a href="download.php?path=' . $path . '" ><i class="fa-solid fa-arrow-turn-up" style="color: #0d6efd;"></i> Parent </a></p> </td>';
                        else
                        echo '<td><p><a href="download.php" ><i class="fa-solid fa-arrow-turn-up" style="color: #0d6efd;"></i> Parent </a></p> </td>';
                        echo ' <td style="text-align: right;"><p>-</p></td>
                               <td style="text-align: right;"><p>-</p></td>
                                </tr>';
                    }
                    foreach ($items as $key => $item) {
                        $pos = strpos($key, "#");
                        $path = substr($key, $pos + 1);
                        echo ' <tr>';

                        if ($item["type"] === "directory") {
                            echo '<td style="text-align: center;">';
                            echo '<input value="' . $path . '" type="checkbox" rel="directory" name="element" ' . ($parent_selected ? 'checked disabled' : '') . '></td>';
                            echo '<td><p><a href="#" onclick="goTo(\'' . $path . '\')"><i class="fa-solid fa-folder" style="color: #f39200;"></i> ' . $item["name"] . '</a></p></td>';
                        } else
                            echo '<td style="text-align: center;"><input value="'.$path.'" type="checkbox" rel="file" name="element" ' . ($parent_selected ? 'checked disabled' : '') . '></td><td><p><i class="fa-solid fa-file" style="color: #0d6efd;"></i> ' . $item["name"] . '</p></td>';

                        if ($item["type"] === "directory")
                            echo '<td style="text-align: right;"><p>-</p></td><td style="text-align: right;"><p>-</p></td>';
                        else
                            echo '<td style="text-align: right;"><p>' . formatBytes($item["size"]) . ' </p></td><td style="text-align: right;"><p>' . gmdate("Y-m-d H:i:s", $ftp->mdtm($path)) . '</p></td>';

                        echo '</tr>';
                    }

                    ?>
                    </tbody>
                </table>
            </div>

    </fieldset>
    <fieldset class="border mt-3 p-2">
        <legend class="text-center">Selezionare se vuoi determinati tipi di file</legend>
        <div class="row mt-2 px-5">
            <div class="col-3 mt-2">
        <input type="checkbox" id="pdf" name="pdf">
        <label class="form-check-label" for="pdf">.pdf</label>
            </div>
            <div class="col-3 mt-2">
                <input type="checkbox" id="html" name="html">
                <label class="form-check-label" for="html">.html</label>
            </div>
            <div class="col-3 mt-2">
                <input type="checkbox" id="doc" name="doc">
                <label class="form-check-label" for="doc">.doc</label>
            </div>
            <div class="col-3 mt-2">
                <input type="checkbox" id="txt" name="txt">
                <label class="form-check-label" for="txt">.txt</label>
            </div>
            <div class="col-3 mt-2">
                <input type="checkbox" id="php" name="php">
                <label class="form-check-label" for="php">.php</label>
            </div>
            <div class="col-3 mt-2">
                <input type="checkbox" id="img" name="img">
                <label class="form-check-label" for="img">File multimediali</label>
            </div>
            <div class="col-3 mt-2">
                <input type="checkbox" id="ppt" name="ppt">
                <label class="form-check-label" for="ppt">.ppt</label>
            </div>
            <div class="col-3 mt-2">
                <input type="checkbox" id="exc" name="exc">
                <label class="form-check-label" for="exc">.csv</label>
            </div>
        </div>
    </fieldset>
    <div class="row mt-2">
        <div class="col"></div>
        <div class="col d-grid">
            <button id="csv" class="btn btn-success" type="button">Download</button>
        </div>
        <div class="col"></div>
    </div>
    <p class="mt-5 pb-2 text-muted text-center">FTP Scraper &copy; 2021-<?php echo date('Y'); ?></p>
</div>

<!-- Modal -->
<div class="modal fade" id="modalLoading" tabindex="-1" role="dialog" aria-labelledby="modalTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Scaricamento dei file in corso...</h5>
            </div>
            <div class="modal-body">
                <div class="progress">
                    <div id="modalStripe" class="progress-bar progress-bar-striped progress-bar-animated"
                         role="progressbar"
                         aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
</div>
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
                <p>Link per scaricare il file zip: <a href="<?php echo  './tmp/' . $_SESSION['old_id']. '/download_'.$_SESSION['old_id'].'.zip'  ?>" id="zip_url" download>Download</a></p>
                <p>Link per scaricare il file log: <a href="<?php echo  './tmp/' . $_SESSION['old_id']. '/log_'.$_SESSION['old_id'].'.txt'  ?>" id="report_url" download>Download</a></p>
                <p>Link per scaricare il report: <a href="<?php echo  './tmp/' . $_SESSION['old_id']. '/report_'.$_SESSION['old_id'].'.pdf'  ?>" id="report_url" download>Download</a></p>
            </div>
            <div class="modal-footer">
                <button type="button" id="download_complete" class="btn btn-primary" data-bs-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>
<?php require('layouts/scripts.php'); ?>
<script>
    let selected = <?php if(isset($_SESSION['selected_files'])) echo $_SESSION['selected_files']; else echo "[]";?>;
    let parent = '<?php if(isset($_GET['path'])) echo $_GET['path']; else echo ""; ?>';
    let md5='<?php if(isset($_SESSION['MD5'])) { echo $_SESSION['MD5'];unset($_SESSION['MD5']);} else echo '';?>';
    let sha1='<?php if(isset($_SESSION['SHA'])) { echo $_SESSION['SHA'];unset($_SESSION['SHA']);} else echo '';?>';

    $("#csv").on('click', _ => {
            if (selected.length > 0) {
                $('#md5_files').text('Errore');
                $('#sha_files').text('Errore');
                $('#report_url').prop('href', '').text('');
                $('#zip_url').prop('href', '').text('');
                $('#modalLoading').modal({backdrop: 'true', keyboard: false, show: true, focus: true}).modal('show');
                window.location = "functions/getFiles.php";

            } else {
                $('#alertText').text('Nessun file selezionato.');
                $('#alertError').addClass('show');
                setTimeout(_ => $('#alertError').removeClass('show'), 3000);
            }
    });

    //SELETTORE SX
    $('#select_directory').on('change', function () {
        if ($(this).val() !== '/') { // require a URL
            window.location = "download.php?path=" + $(this).val(); // redirect
        } else {
            window.location = "download.php"; // redirect
        }
        return false;
    });

    //CLICK SU CHECKBOX
    $("input[type=checkbox][name='element']").on('click', function () {
        let path = $(this).val();
        let index = null;
        let all_checked = $("input[name='element']").length === $("input[name='element']:checked").length;
        if (all_checked && parent !== '') {
            $("#check_all_chats").prop('checked', true).attr('disabled', true);
            $("input[name='element']").each(function () {
                $(this).attr('disabled', true);
                selected = selected.filter(e => e !== $(this).val());
            });
            selected.push(parent);
        } else {
            if(all_checked)
                $("#check_all_chats").prop('checked', true);
            else
                $("#check_all_chats").prop('checked', false);

            for (let i = 0; i < selected.length; i++) {
                if (path.localeCompare(selected[i]) === 0) {
                    index = i;
                    break;
                }
            }
            if ($(this).is(":checked") && index == null)
                selected.push(path);
            else
                selected.splice(index, 1);
        }

        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: "/functions/selectedFiles.php",
            data: {selected_files: JSON.stringify(selected)},
            timeout: 120000,
            error: (e) => {
                console.log(e);
            }
        });
    });

    $("#check_all_chats").click(function () {
        if($(this).is(":checked")) {
            $('input[name="element"]:not(:checked)').each(function (){
               $(this).click();
            });
            if(parent !== '')
                $(this).attr('disabled', true);
        }else{
            $('input[name="element"]:checked').each(function (){
                $(this).click();
            });
        }
    });

    function goTo(path){
        $("input[name='element']").each(function () {
            let p = $(this).val();
            if (p.localeCompare(path) === 0) {
                window.location.href = "/download.php?path=" + path + ($(this).prop('checked') === true ? "&selected" : "");
            }
        });
    }

    $("#search").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $("#element_list tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    $(document).ready(function () {

        if(md5 !=='' && sha1 !==''){
            $('#modalLoading').modal('hide');
            $('#md5_files').text(md5);
            $('#sha_files').text(sha1);
            let href = window.location.href;
            let dir = href.substring(0, href.lastIndexOf('/'));
            /* $('#report_url').prop('href', result.report.url).text(dir + result.report.url.substring(1));
            $('#zip_url').prop('href', result.zip).text(dir + result.zip.substring(1));*/
            $('#modalHash').modal('show');

        }

        for (let i = 0; i < selected.length; i++) {
            $("input[type=checkbox][name='element']").each(function () {
                let val = $(this).val();
                if (val.localeCompare(selected[i]) === 0) {
                    $(this).prop('checked', 'checked');
                }
            });
        }
        if($("input[name='element']").length === $("input[name='element']:checked").length)
            $("#check_all_chats").prop('checked', true);
    });
</script>
</body>
</html>