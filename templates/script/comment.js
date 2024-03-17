$(document).ready(function () {
    let postId = window.location.pathname.match(/\/post\/(\d+)/)[1];

    let initialCountComment = $('#comment-textarea').val().length;
    $('#char-count-comment').text(initialCountComment + ' / 140');

    $('#comment-textarea').on('input', function () {
        let count = $(this).val().length;
        $('#char-count-comment').text(count + ' / 140');
    });

    let errorMessages = [];

    $("#alert-row").css('display', 'none');
    $("#error-message").css('display', 'none');
    $("#error-message").text("");

    $("#comment-btn").on("click", function () {
        $("#error-message").text("");
        checkComment();

        if (errorMessages.length === 0) {
            let formData = {
                id_post: postId,
                message: $("#comment-textarea").val(),
                arobases: getArobases(),
                hashtags: getHashtags()
            };

            $.ajax({
                type: "POST",
                url: '/comment/register',
                data: formData,
                success: function (response) {
                    // console.log(response);

                    $('#alert-row').css('display', 'none');
                    $("#error-message").css('display', 'none');

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
                    $("#error-message").css('display', 'block');
                }
            });
        } else {
            let errorMessage = errorMessages.join('<br>');
            $("#error-message").html(errorMessage);
            $("#error-message").css('display', 'block');
        }
    });

    function checkComment() {
        errorMessages = [];

        const message = $("#comment-textarea");

        if (!checkTextarea(message.val())) {
            errorMessages.push('Longueur du commentaire incorrect. (20-140)');
        }

        displayErrors(errorMessages.join('<br>'));

        if (errorMessages.length > 0) {
            $('#comment-btn').addClass('shake');

            setTimeout(() => {
                $('#comment-btn').removeClass('shake');
            }, 500);
        }
    }

    function checkTextarea(message) {
        return message.length >= 20 && message.length <= 140;
    }

    function getArobases() {
        let textarea = $('#comment-textarea').val();
        let usernameRegex = /\s@(\w+)/g;
        let usernames = textarea.match(usernameRegex) || [];

        let cleanedUsernames = usernames.map(username => username.replace(/\s@/, ''));

        return cleanedUsernames;
    }

    function getHashtags() {
        let textarea = $("#comment-textarea").val();
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