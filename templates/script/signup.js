$(document).ready(function () {
    let errorMessages = [];

    $("#alert-row").css('display', 'none');
    $("#error-message").css('display', 'none');
    $("#error-message").text("");

    $("#signup-btn").click(function () {
        $("#error-message").text("");
        checkSignup();

        if (errorMessages.length === 0) {
            const checkbox = $('input[type="checkbox"]');
            const checkedCheckbox = checkbox.is(':checked');
            const checkboxValue = checkedCheckbox ? checkbox.attr('name') : '';

            let formData = {
                genre: $('#signup-form select').val(),
                fullname: $('#signup-fullname').val(),
                pseudo: $('#signup-pseudo').val(),
                email: $("#signup-email").val(),
                birthdate: $("#signup-birthdate").val(),
                password: $("#signup-password").val(),
                terms: checkboxValue,
                city: $('#signup-city').val()
            };

            $.ajax({
                type: "POST",
                url: "/signup/register",
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

    function checkSignup() {
        errorMessages = [];

        const select = $('#signup-form select');
        const fullname = $('#signup-fullname');
        const pseudo = $('#signup-pseudo');
        const email = $('#signup-email');
        const birthdate = $('#signup-birthdate');
        const city = $('#signup-city');
        const password = $('#signup-password');
        const confirmPassword = $('#signup-confirm-password');
        const checkbox = $('#signup-form input[type="checkbox"]');

        if (!checkSelect(select.val())) {
            errorMessages.push('Veuillez spécifier votre sexe.');
        }

        if (!checkNames(fullname.val())) {
            errorMessages.push('Votre nom ou prénom est incorrect.');
        }

        if (!checkPseudo(pseudo.val())) {
            errorMessages.push('Votre pseudonyme doit contenir au moins 6 caractères alphanumériques.');
        }

        if (!checkEmail(email.val())) {
            errorMessages.push('Votre adresse e-mail est incorrecte.');
        }

        if (!checkCity(city.val())) {
            errorMessages.push('La ville est incorrecte.');
        }

        if (!checkBirthdate(birthdate.val())) {
            errorMessages.push('Votre âge ne vous permet pas de vous inscrire sur la plateforme.');
        }

        if (!checkPasswords(password.val(), confirmPassword.val())) {
            errorMessages.push('Vos mots de passe sont incorrects.');
        }

        if (!checkCheckboxes(checkbox)) {
            errorMessages.push('Vous devez accepter les conditions d\'utilisation pour accéder à la plateforme.');
        }

        displayErrors(errorMessages.join('<br>'));

        if (errorMessages.length > 0) {
            $('#signup-btn').addClass('shake');

            setTimeout(() => {
                $('#signup-btn').removeClass('shake');
            }, 500);
        }
    }

    function displayErrors(messages) {
        $("#alert-row").css('display', 'block');
        $("#error-message").css('display', 'block');
        $("#error-message").html(messages);
    }

    function checkSelect(option) {
        return option > 0;
    }

    function checkEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }

    function checkNames(fullname) {
        const regex = /^[a-zA-Z]{2,}\s[a-zA-Z]{2,}$/;
        return regex.test(fullname);
    }
    
    function checkCity(city) {
        return city.length > 3;
    }

    function checkPseudo(pseudo) {
        return pseudo.length > 5 && /^[A-Za-z0-9-_]+$/.test(pseudo);
    }

    function checkBirthdate(birthdate) {
        const actualDate = new Date();
        const actualYear = actualDate.getFullYear();
        const birthdayValue = new Date(birthdate);
        const birthdayYear = birthdayValue.getFullYear();

        return actualYear - birthdayYear >= 18;
    }

    function checkPasswords(password, confirmPassword) {
        return password !== "" && confirmPassword !== "" &&
            password.length > 8 && confirmPassword.length > 8 && 
            password === confirmPassword;
    }

    function checkCheckboxes(checkboxes) {
        return checkboxes.filter(':checked').length > 0;
    }
});