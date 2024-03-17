$(document).ready(function () {
    let pseudo = window.location.pathname.match(/\/profile\/(\w+)/)[1];
    let actualBiography = $("#biography").val();
    let actualUsername = $("#update-username").val();
    let actualPseudo = $("#update-pseudo").val();
    let actualEmail = $("#update-email").val();
    let actualCity = $("#update-city").val();

    $('#error-message').css('display', 'none');
    $('#error-message').text("");

    let initialCount = $('#biography').val().length;
    $('#char-count').text(initialCount + ' / 140');

    $('#biography').on('input', function () {
        let count = $(this).val().length;
        $('#char-count').text(count + ' / 140');
    });


    let errorMessages = [];

    $("#update-photo-btn").on("click", function () {
        let input = $("<input type='file' accept='image/*'>");

        $("body").append(input);
        input.hide();
        input.click();

        input.on("change", function () {
            let file = this.files[0];

            if (file) {
                let maxSize = 1024 * 1024;

                if (file.size <= maxSize) {
                    let formData = new FormData();
                    formData.append('photo', file);

                    if (pseudo) {
                        $.ajax({
                            url: '/profile/' + pseudo + '/photo/update',
                            method: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                // console.log('Response:', response);

                                const jsonResponse = JSON.parse(response);
                                // console.log('response.success:', jsonResponse.success);
                                // console.log('response.image:', jsonResponse.image);
                                // console.log('response.banner:', jsonResponse.banner);

                                if (jsonResponse.success && jsonResponse.image !== "null") {
                                    $('.btn-close').trigger('click');
                                    // console.log('Updating image source:', jsonResponse.image);
                                    $("#profile-img").attr("src", jsonResponse.image);
                                }
                            },
                            error: function (error) {
                                console.error(error);
                            }
                        });
                    }
                } else {
                    alert("La taille de l'image dépasse la limite autorisée.");
                }
            }

            input.remove();
        });
    });

    $("#update-banner-btn").on("click", function () {
        let input = $("<input type='file' accept='image/*'>");

        $("body").append(input);
        input.hide();
        input.click();

        input.on("change", function () {
            let file = this.files[0];

            if (file) {
                let maxSize = 1024 * 1024;

                if (file.size <= maxSize) {
                    let formData = new FormData();
                    formData.append('banner', file);

                    if (pseudo) {
                        $.ajax({
                            url: '/profile/' + pseudo + '/photo/update',
                            method: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                // console.log('Response:', response);

                                const jsonResponse = JSON.parse(response);
                                // console.log('response.success:', jsonResponse.success);
                                // console.log('response.image:', jsonResponse.image);
                                // console.log('response.banner:', jsonResponse.banner);

                                if (jsonResponse.success && jsonResponse.banner !== "null") {
                                    // console.log('Updating image source:', jsonResponse.banner);
                                    $('.cover').css('background-image', 'url(' + jsonResponse.banner + ')');
                                    $('.btn-close').trigger('click');
                                }
                            },
                            error: function (error) {
                                console.error(error);
                            }
                        });
                    }
                } else {
                    alert("La taille de l'image dépasse la limite autorisée.");
                }
            }

            input.remove();
        });
    });

    $("#update-btn").click(function () {
        $("#error-message").text("");
        checkUpdateProfile();

        if (errorMessages.length === 0) {
            let newBiography = $("#biography").val();
            let newUsername = $("#update-username").val();
            let newPseudo = $("#update-pseudo").val();
            let newEmail = $("#update-email").val();
            let newPassword = $("#update-password").val();
            let newConfirmPassword = $("#update-confirm-password").val();
            let newCity = $("#update-city").val();

            let formData = {};

            if (newBiography !== actualBiography) {
                formData.biography = newBiography;
            }

            if (newUsername !== actualUsername) {
                formData.username = newUsername;
            }

            if (newPseudo !== actualPseudo) {
                formData.pseudo = newPseudo;
            }

            if (newEmail !== actualEmail) {
                formData.email = newEmail;
            }

            if (newPassword !== "") {
                formData.password = newPassword;
            }

            if (newConfirmPassword !== "") {
                formData.confirmPassword = newConfirmPassword;
            }

            if (newCity !== actualCity) {
                formData.city = newCity;
            }

            $.ajax({
                type: "POST",
                url: '/profile/' + pseudo + '/update',
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
                            $('#update-btn').addClass('shake');

                            setTimeout(() => {
                                $('#update-btn').removeClass('shake');
                            }, 500);

                            $("#alert-row").css('display', 'block');
                            $("#error-message").css('display', 'block');
                            $('#error-message').text(jsonResponse.error);
                        }

                        actualBiography = newBiography;
                        actualUsername = newUsername;
                        actualPseudo = newPseudo;
                        actualEmail = newEmail;
                        actualPassword = newPassword;
                        actualConfirmPassword = newConfirmPassword;
                        actualCity = newCity;
                    } catch (e) {
                        console.error("Erreur de parsing JSON :", e);
                    }
                },
                error: function (xhr, status, error) {
                    // console.error(xhr.responseText);
                    let errorMessage = "Une erreur s'est produite lors de l'envoi des données. Veuillez réessayer.";

                    try {
                        let jsonResponse = JSON.parse(xhr.responseText);

                        if (jsonResponse.error) {
                            // console.log(jsonResponse.error);
                            $('#update-btn').addClass('shake');

                            setTimeout(() => {
                                $('#update-btn').removeClass('shake');
                            }, 500);

                            $("#alert-row").css('display', 'block');
                            $("#error-message").css('display', 'block');
                            $('#error-message').text(jsonResponse.error);
                            errorMessage = jsonResponse.error;
                        }
                    } catch (e) {
                        // console.error("Erreur de parsing JSON :", e);
                    }

                    $("#error-message").text(errorMessage);
                    // console.log(xhr.responseText);
                }
            });
        }
    });

    $("#profile-follow-btn").click(function () {
        $.ajax({
            type: "POST",
            url: '/follow/' + pseudo,
            data: { data: 'true' },
            success: function (response) {
                // console.log(response);

                try {
                    let jsonResponse = JSON.parse(response);

                    if (jsonResponse.success === "follow") {
                        $("#profile-follow-btn").html(`Abonné&emsp;<i class="fa-solid fa-check"></i>`);
                        $("#profile-follow-btn").removeClass("btn-success");
                        $("#profile-follow-btn").addClass("btn-primary");
                        $("#count-followers").text(jsonResponse.followers);
                    } else if (jsonResponse.success === "unfollow") {
                        $("#profile-follow-btn").text("Suivre");
                        $("#profile-follow-btn").removeClass("btn-primary");
                        $("#profile-follow-btn").addClass("btn-success");
                        $("#count-followers").text(jsonResponse.followers);
                    }
                } catch (e) {
                    console.error("Erreur de parsing JSON :", e);
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    function checkUpdateProfile() {
        errorMessages = [];

        const biography = $("#biography");
        const username = $("#update-username");
        const pseudo = $("#update-pseudo");
        const email = $("#update-email");
        const city = $("#update-city");
        const confirmPassword = $('#update-confirm-password');

        if (biography.val().length > 140) {
            errorMessages.push('Votre biographie est trop longue.');
        }

        if (!checkNames(username.val())) {
            errorMessages.push('Votre nom complet est trop court.');
        }

        if (!checkNames(pseudo.val())) {
            errorMessages.push('Votre pseudo est trop court.');
        }

        if (!checkEmail(email.val())) {
            errorMessages.push('Votre adresse e-mail est incorrecte.');
        }

        if (!checkCity(city.val())) {
            errorMessages.push('La ville est incorrecte.');
        }

        if (!checkPassword(confirmPassword.val())) {
            errorMessages.push('Votre nouveau mot de passe est incorrect.');
        }

        displayErrors(errorMessages.join('<br>'));

        if (errorMessages.length > 0) {
            $('#update-btn').addClass('shake');

            setTimeout(() => {
                $('#update-btn').removeClass('shake');
            }, 500);
        }
    }

    function displayErrors(messages) {
        $('#error-message').removeClass('d-none');
        $("#error-message").html(messages);
    }

    function checkEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }

    function checkNames(name) {
        return name.length > 3;
    }

    function checkCity(city) {
        return city.length > 3;
    }

    function checkPassword(password) {
        if (password === "") {
            return true;
        }

        return password.length > 8;
    }
});