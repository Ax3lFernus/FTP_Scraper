/*
* Telegram Scraper v1.1.0
* Content: message.php scripts
* Author: Alessandro Annese & Davide De Salvo
* Last update: 19/02/2021
*/
$("#login").submit(function (e) {
    e.preventDefault();
   // if ($("#inputCode").is(":hidden")) {
        $("#form-btn").prop("disabled", true).text("Accesso in corso...");
        $("#username").prop("disabled", true);
        $("#password").prop("disabled", true);
        $("#server").prop("disabled", true);
        $("#port").prop("disabled", true);
        $("#protocol").prop("disabled", true);
        $(".text-danger").hide();
        //Creo sessione & richiedo il codice di verifica
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: serverUrl + "functions/login.php",

            data: {server: $("#server").val(), username: $("#username").val(), password: $("#password").val(),
                port: $("#port").val(), protocol: $("#protocol").val() !== 1 ? true : false},
            timeout: 120000,
            success: (result) => {
                console.log(result);
                let json = JSON.parse(result);
                if (json.success) {
                    Cookies.set('token', json.token, { expires: 365 });
                    $("#form-btn").text("Fatto!");
                    //location.href = 'message.php';
                } else {
                   window.location = 'index.php?ERROR=E';
                }
            },
            error: (e) => {
                Cookies.remove('token');
                window.location = 'index.php?ERROR=E';
            }
        });
   /* } else {
        $("#form-btn").prop("disabled", true).text("Accesso in corso...");
        $("#inputCode").prop("disabled", true);
        $(".text-danger").hide();
        //Accedo con il codice
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: serverUrl + "functions/login.php",
            data: {token: Cookies.get('token'), code: $("#inputCode").val()},
            timeout: 120000,
            success: (result) => {
                console.log(result);
                let json = JSON.parse(result);
                if (json.success) {
                    $("#form-btn").text("Fatto!");
                    location.href = 'message.php'
                } else {
                    window.location = 'index.php?PHONE_CODE_INVALID=E';
                }
            },
            error: (e) => {
                Cookies.remove('token');
                window.location = 'index.php?ERROR=E';
            }
        });
    }*/
});