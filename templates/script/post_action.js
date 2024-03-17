$(document).ready(function () {
    $(".like_element").on("click", function () {
        let formData = {
            id_post: $(this).data("post-id"),
            data: "true"
        };

        $.ajax({
            type: "POST",
            url: '/post/like/register',
            data: formData,
            success: function (response) {
                // console.log(response);

                try {
                    let jsonResponse = JSON.parse(response);

                    if (jsonResponse.success === "like") {
                        $(".like_element[data-post-id='" + jsonResponse.id_post + "']").html(`${jsonResponse.likes}&emsp;<i class="fa-solid fa-heart" style="color: red"></i>`);
                    } else if (jsonResponse.success === "dislike") {
                        $(".like_element[data-post-id='" + jsonResponse.id_post + "']").html(`${jsonResponse.likes}&emsp;<i class="fa-regular fa-heart"></i>`);
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

    $(".retweet_element").on("click", function () {
        let formData = {
            id_post: $(this).data("post-id"),
            data: "true"
        };

        $.ajax({
            type: "POST",
            url: '/post/retweet/register',
            data: formData,
            success: function (response) {
                // console.log(response);

                try {
                    let jsonResponse = JSON.parse(response);

                    if (jsonResponse.success === "retweet") {
                        $(".retweet_element[data-post-id='" + jsonResponse.id_post + "']").html(`${jsonResponse.retweets}&emsp;<i class="fa-solid fa-retweet" style="color: green"></i>`);
                    } else if (jsonResponse.success === "unretweet") {
                        $(".retweet_element[data-post-id='" + jsonResponse.id_post + "']").html(`${jsonResponse.retweets}&emsp;<i class="fa-solid fa-retweet"></i>`);
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
});