$(document).ready(function () {
    let container = $("#message-container");
    container.scrollTop(container[0].scrollHeight);

    let pseudoReceiver = window.location.pathname.match(/\/chat\/(\w+)/)[1];

    let initialCount = $('#chat-textarea').val().length;
    $('#char-count').text(initialCount + ' / 140');

    $('#chat-textarea').on('input', function () {
        let count = $(this).val().length;
        $('#char-count').text(count + ' / 140');
    });

    let errorMessages = [];

    $("#alert-row").css('display', 'none');
    $("#error-message").css('display', 'none');
    $("#error-message").text("");

    $("#chat-btn").on("click", function () {
        $("#error-message").text("");
        checkMessage();

        if (errorMessages.length === 0) {
            let formData = {
                receiver: pseudoReceiver,
                message: $("#chat-textarea").val(),
            };

            $.ajax({
                type: "POST",
                url: `/chat/${pseudoReceiver}/register`,
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
                            $('#chat-btn').addClass('shake');

                            setTimeout(() => {
                                $('#chat-btn').removeClass('shake');
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

    function checkMessage() {
        errorMessages = [];

        const message = $("#chat-textarea");

        if (!checkTextarea(message.val())) {
            errorMessages.push('Longueur du message incorrect. (1-140)');
        }

        displayErrors(errorMessages.join('<br>'));

        if (errorMessages.length > 0) {
            $('#chat-btn').addClass('shake');

            setTimeout(() => {
                $('#chat-btn').removeClass('shake');
            }, 500);
        }
    }

    function checkTextarea(message) {
        return message.length >= 1 && message.length <= 140;
    }

    function displayErrors(messages) {
        $('#alert-row').css('display', 'block');
        $("#error-message").css('display', 'block');
        $("#error-message").html(messages);
    }
});