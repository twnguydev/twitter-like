$(document).ready(function () {
    function refreshTimeline() {
        $.ajax({
            url: '/timeline',
            method: 'GET',
            dataType: 'html',
            success: function (data) {
                $('#ajax-refresh').html(data);
                // console.log('timeline refreshed');
            }
        });
    }

    setInterval(refreshTimeline, 60000);
});