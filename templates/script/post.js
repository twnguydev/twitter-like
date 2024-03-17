$(document).ready(function () {
    let initialCount = $('#post-textarea').val().length;
    $('#char-count').text(initialCount + ' / 140');

    $('#post-textarea').on('input', function () {
        let count = $(this).val().length;
        $('#char-count').text(count + ' / 140');
    });

    let errorMessages = [];

    $("#alert-row").css('display', 'none');
    $("#error-message").css('display', 'none');
    $("#error-message").text("");

    $("#post-btn").on("click", function () {
        $("#error-message").text("");
        checkPost();

        if (errorMessages.length === 0) {
            let formData = {
                message: $("#post-textarea").val(),
                arobases: getArobases(),
                hashtags: getHashtags()
            };

            $.ajax({
                type: "POST",
                url: '/post/register',
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

    function checkPost() {
        errorMessages = [];

        const message = $("#post-textarea");

        if (!checkTextarea(message.val())) {
            errorMessages.push('Longueur du post incorrect. (20-140)');
        }

        displayErrors(errorMessages.join('<br>'));

        if (errorMessages.length > 0) {
            $('#post-btn').addClass('shake');

            setTimeout(() => {
                $('#post-btn').removeClass('shake');
            }, 500);
        }
    }

    function checkTextarea(message) {
        return message.length >= 20 && message.length <= 140;
    }

    function getArobases() {
        let textarea = $('#post-textarea').val();
        let usernameRegex = /\s@(\w+)/g;
        let usernames = textarea.match(usernameRegex) || [];

        let cleanedUsernames = usernames.map(username => username.replace(/\s@/, ''));

        return cleanedUsernames;
    }

    function getHashtags() {
        let textarea = $("#post-textarea").val();
        let hashtagRegex = /\s#(\w+)/g;
        let hashtags = textarea.match(hashtagRegex) || [];

        let cleanedHashtags = hashtags.map(username => username.replace(/\s#/, ''));

        return cleanedHashtags;
    }

    function displayErrors(messages) {
        $('#alert-row').css('display', 'block');
        $("#error-message").css('display', 'block');
        $("#error-message").html(messages);
    }
});