var baseUrl = $('base').attr('href');
$.ajax(baseUrl + '/browseAddOns/ajax_check_update?is-ajax-request')
    .done(function(response) {
        if (response.status == 1) {
            var arr = $.makeArray(response.data);
            increaseTotalNote_number(arr.length);
            increaseNote_number(arr.length);
            $.each(arr, function(key, val) {
                addNote_item(
                    val.full_url_icon,
                    '<b>' + val.name + '</b> có bản cập nhật.',
                    {
                        full_url: baseUrl + 'admin/general/modulescp?appFollow=browseAddOns/checkUpdate/module/' + val.package,
                        name    : 'Cập nhật'
                    }
                );
            });
        }
    });