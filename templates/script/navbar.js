$(function() {
    let dropdownMenu = $("#searchInput").next('.dropdown-menu');

    $("#searchInput").on('input', function() {
        dropdownMenu.show();
        let searchQuery = $(this).val().trim();
        let urlParams = new URLSearchParams(window.location.search);
        let params = {};

        urlParams.forEach(function(value, key) {
            params[key] = value;
        });

        params['query'] = searchQuery;

        if (searchQuery === '') {
            dropdownMenu.hide();
            return;
        }

        $.ajax({
            url: '/search',
            type: 'POST',
            data: params,
            success: function(data) {
                // console.log(data);
                try {
                    data = JSON.parse(data);
                    if (Array.isArray(data) && data.length > 0) {
                        let htmlContent = '';
                        data.forEach(function(item) {
                            htmlContent += `
                                <a class="dropdown-item" href="/profile/${item.pseudo}">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex flex-row align-items-center">
                                            <img src="${item.profile_path}" alt="profile picture" class="rounded-circle" width="30" height="30">
                                            <p class="ms-2 text-primary mb-0">@${item.pseudo}</p>
                                        </div>
                                        <div class="ms-2 justify-self-end">
                                            <p class="m-0">${item.count_followers} ${item.count_followers > 1 ? 'suivent' : 'suit'}</p>
                                        </div>
                                    </div>
                                </a>
                            `;
                        });
                        dropdownMenu.show();
                        $("#search-content").html(htmlContent);
                    } else {
                        $("#search-content").html('<p class="ms-2 mb-0">Aucun résultat trouvé.</p>');
                    }
                } catch (error) {
                    console.error("Error parsing JSON:", error);
                }
            }
        });
    });

    $(document).click(function(event) {
        if (!$(event.target).closest('.dropdown').length) {
            dropdownMenu.hide();
        }
    });
});