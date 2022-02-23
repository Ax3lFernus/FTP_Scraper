/*
* Telegram Scraper v1.1.0
* Content: message.php scripts
* Author: Alessandro Annese & Davide De Salvo
* Last update: 19/02/2021
*/
$("#login").submit(function (e) {
    e.preventDefault();
    $("#form-btn").prop("disabled", true).text("Accesso in corso...");
    $("#username").prop("disabled", true);
    $("#password").prop("disabled", true);
    $("#server").prop("disabled", true);
    $("#port").prop("disabled", true);
    $("#protocol").prop("disabled", true);
    $(".text-danger").hide();
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: serverUrl + "functions/login.php",

        data: {server: $("#server").val(), username: $("#username").val(), password: $("#password").val(),
            port: $("#port").val(), protocol: $("#protocol").val() != 1},
        timeout: 120000,
        success: (result) => {
            console.log(result);
            let json = JSON.parse(result);
            if (json.success) {
                $("#form-btn").text("Connessione effettuata...");
                location.href = 'download.php';
            } else {
                window.location = 'index.php?error=' + json.error;
            }
        },
        error: (e) => {
            window.location = 'index.php?error=Si è verificato un errore durante la connessione.';
        }
    });
});