$(document).ready(function () {
    let errorMessages = [];

    $("#alert-row").css('display', 'none');
    $("#error-message").css('display', 'none');
    $("#error-message").text("");

    $("#login-btn").click(function () {
        $("#error-message").text("");
        checkLogin();

        if (errorMessages.length === 0) {
            let formData = {
                email: $("#login-email").val(),
                password: $("#login-password").val(),
            };

            $.ajax({
                type: "POST",
                url: "/login/register",
                data: formData,
                success: function (response) {
                    // console.log(response);

                    $("#alert-row").css('display', 'none');
                    $("#error-message").css('display', 'none');
                    $("#error-message").text("");

                    try {
                        let jsonResponse = JSON.parse(response);

                        if (jsonResponse.redirect) {
                            window.location.href = jsonResponse.redirect;
                        } else if (jsonResponse.error) {
                            $('#login-btn').addClass('shake');

                            setTimeout(() => {
                                $('#login-btn').removeClass('shake');
                            }, 500);

                            $("#alert-row").css('display', 'block');
                            $("#error-message").css('display', 'block');
                            $('#error-message').text(jsonResponse.error);
                        }
                    } catch (e) {
                        console.error("Erreur de parsing JSON :", e);
                    }
                },
                error: function (xhr, status, error) {
                    let errorMessage = "Une erreur s'est produite lors de l'envoi des données. Veuillez réessayer.";

                    try {
                        let jsonResponse = JSON.parse(xhr.responseText);
                        if (jsonResponse.error) {
                            errorMessage = jsonResponse.error;
                        }
                    } catch (e) {
                        console.error("Erreur de parsing JSON :", e);
                    }

                    $("#error-message").text(errorMessage);
                    // console.log(xhr.responseText);
                }
            });
        }
    });

    function checkLogin() {
        errorMessages = [];

        const email = $('#login-email');
        const password = $('#login-password');

        if (!checkEmail(email.val())) {
            errorMessages.push('Votre adresse e-mail est incorrecte.');
        }

        displayErrors(errorMessages.join('<br>'));

        if (errorMessages.length > 0) {
            $('#login-btn').addClass('shake');

            setTimeout(() => {
                $('#login-btn').removeClass('shake');
            }, 500);
        }
    }

    function displayErrors(messages) {
        $("#alert-row").css('display', 'block');
        $("#error-message").css('display', 'block');
        $("#error-message").html(messages);
    }

    function checkEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }
});