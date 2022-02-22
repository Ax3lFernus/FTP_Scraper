<?php
    require 'functions/checkToken.php';
    $style = "<link href=\"assets/css/signin.css\" rel=\"stylesheet\">";
    $page_title = "Login";
    require 'layouts/head.php';
?>

<body class="text-center">
<main class="form-signin">
    <form id="login">
        <img class="mb-3" src="<?php echo $link . '/assets/images/logo.png'; ?>" alt="" width="100" height="100">
        <h1 class="h3 mb-3 fw-normal">Accedi al server FTP</h1>
        <?php
        if(isset($_GET['ERROR'])) echo "<p class=\"text-danger font-weight-bold\">Si è verificato un errore.</p>";
        if(isset($_GET['PHONE_INVALID'])) echo "<p class=\"text-danger font-weight-bold\">Il numero di telefono è errato.</p>";
        //if(isset($_GET['PHONE_CODE_INVALID'])) echo "<p class=\"text-danger font-weight-bold\">Il codice di verifica è errato.</p>";
        ?>
        <div class="form-floating mb-3">
            <select class="form-select" id="protocol">
                <option selected value="1">FTP</option>
                <option value="2">FTPS</option>
            </select>
            <label for="protocol">Protocollo</label>
        </div>
        <div class="form-floating mb-3">
            <div class="input-group">
                <input type="text" class="form-control" id="username" placeholder="Username" aria-label="Username" required>
                <span class="input-group-text">@</span>
                <input type="text" class="form-control" id="server" placeholder="Server" aria-label="Server" required
                       pattern="^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])|(([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z0-9]|[A-Za-z0-9][A-Za-z0-9\-]*[A-Za-z0-9])$">
            </div>
        </div>

        <div class="form-floating mb-3">
            <input type="password" id="password" placeholder="Password" class="form-control" required>
            <label for="password">Password</label>
        </div>

        <div class="form-floating">
            <input type="number" min="0" max="65535" id="port" class="form-control" value="21" required>
            <label for="porta">Numero di porta</label>
        </div>

        <button id="form-btn" class="w-100 btn btn-lg btn-primary mt-3" type="submit">Accedi al server</button>
        <p class="mt-5 mb-3 text-muted">FTP Scraper &copy; <?php echo date('Y');?></p>
    </form>
</main>
<?php require('layouts/scripts.php'); ?>
<script src="./assets/js/index.js"></script>
</body>
</html>