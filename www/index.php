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
        <h1 class="h3 mb-3 fw-normal">Accedi</h1>
        <?php
        if(isset($_GET['ERROR'])) echo "<p class=\"text-danger font-weight-bold\">Si è verificato un errore.</p>";
        if(isset($_GET['PHONE_INVALID'])) echo "<p class=\"text-danger font-weight-bold\">Il numero di telefono è errato.</p>";
        if(isset($_GET['PHONE_CODE_INVALID'])) echo "<p class=\"text-danger font-weight-bold\">Il codice di verifica è errato.</p>";
        ?>
        <div class="form-floating">
            <input type="text" id="inputServer" class="form-control mb-2" placeholder="Nome Server" required autofocus>
            <label for="inputServer">Nome Server</label>
        </div>
        <div class="form-floating">
            <input type="text" id="inputUser" class="form-control mb-2" placeholder="Username" required autofocus>
            <label for="inputUser">Username</label>
        </div>
        <div class="form-floating">
            <input type="password" id="inputPassword" class="form-control mb-2" placeholder="Password" required autofocus>
            <label for="inputPassword">Password</label>
        </div>
        <div class="form-floating">
            <input type="number" id="inputPort" class="form-control" pattern="^[0-9]$|^[1-9][0-9]$|^[1-9][0-9][0-9]$|^[1-9][0-9][0-9][0-9]$|^[1-6][0-5][0-5][0-3][0-5]$"
                   placeholder="Porta" required autofocus>
            <label for="inputPort">Numero di porta</label>
        </div>



        <button id="form-btn" class="w-100 btn btn-lg btn-primary mt-3" type="submit">Accedi</button>
        <p class="mt-5 mb-3 text-muted">FTP Scraper &copy; 2021-<?php echo date('Y');?></p>
    </form>
</main>
<?php require('layouts/scripts.php'); ?>
<script src="./assets/js/index.js"></script>
</body>
</html>