var baseUrl = $('base').attr('href');
$.ajax(baseUrl + '/admin/ajax_check_update?is-ajax-request')
    .done(function(response) {
        if (response) {
            increaseTotalNote_number(1);
            increaseNote_number(1);
            addNote_item(
                '<i class="fa fa-level-up"></i>',
                '<b>ZenCMS ' + response.version + '</b> đã sẵn sàng!',
                {
                    full_url: response.link_down,
                    name    : 'Download'
                }
            );
        }
    });