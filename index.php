<?php
session_start();
if(isset($_SESSION['ftp_vars'])) {
    header("Location: /download.php");
    exit();
}
$style = "<link href=\"assets/css/signin.css\" rel=\"stylesheet\">";
$page_title = "Login";
require 'layouts/head.php';
?>

<body class="text-center">
<main class="form-signin">
    <form id="login" method="post" action="/functions/login.php">
        <img class="mb-3" src="/assets/images/logo.png" alt="" width="100" height="100">
        <h1 class="h3 mb-3 fw-normal">Accedi al server FTP</h1>
        <?php
        if(isset($_GET['error'])) echo "<p class=\"text-danger font-weight-bold\">" . $_GET['error'] . "</p>";
        if(isset($_GET['info'])) echo "<p class=\"text-primary font-weight-bold\">" . $_GET['info'] . "</p>";
        ?>
        <div class="form-floating mb-3">
            <select class="form-select" id="protocol" name="protocol">
                <option selected value="false">FTP</option>
                <option value="true">FTPS</option>
            </select>
            <label for="protocol">Protocollo</label>
        </div>
        <div class="form-floating mb-3">
            <div class="input-group">
                <input type="text" class="form-control" id="username" name="username" placeholder="Username" aria-label="Username" required>
                <span class="input-group-text">@</span>
                <input type="text" class="form-control" id="server" name="server" placeholder="Server" aria-label="Server" required
                       pattern="^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])|(([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z0-9]|[A-Za-z0-9][A-Za-z0-9\-]*[A-Za-z0-9])$">
            </div>
        </div>

        <div class="form-floating mb-3">
            <input type="password" id="password" name="password" placeholder="Password" class="form-control" required>
            <label for="password">Password</label>
        </div>

        <div class="form-floating">
            <input type="number" min="0" max="65535" id="port" name="port" class="form-control" value="21" required>
            <label for="porta">Numero di porta</label>
        </div>

        <button id="form-btn" class="w-100 btn btn-lg btn-primary mt-3" type="submit">Accedi al server</button>
    </form>
    <p class="mt-5 mb-3 text-muted">FTP Scraper &copy; <?php echo date('Y');?></p>
</main>
<?php require('layouts/scripts.php'); ?>
<script>
    $("#form-btn").click(_ => {
        $(".text-danger").hide();
        $("#login").submit();
        $("#form-btn").prop("disabled", true).text("Accesso in corso...");
        $("#username").prop("disabled", true);
        $("#password").prop("disabled", true);
        $("#server").prop("disabled", true);
        $("#port").prop("disabled", true);
        $("#protocol").prop("disabled", true);
    });
</script>
</body>
</html>