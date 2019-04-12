function increaseTotalNote_number(n) {
    var oldGlobalTotalNumber = $('#global_note_total_notice span').html();
    if (typeof oldGlobalTotalNumber === 'undefined') {
        if (n) $('<span class="badge badge-default">' + n + '</span>').appendTo('#global_note_total_notice');
    } else {
        var tNum = parseInt(oldGlobalTotalNumber) + n;
        if (tNum) $('<span class="badge badge-default">' + tNum + '</span>').appendTo('#global_note_total_notice');
    }
}

function increaseNote_number(n) {
    var oldGlobalNumber = $('#note_total_notice span').html();
    if (typeof oldGlobalNumber === 'undefined') {
        if (n) $('<span class="badge badge-success">' + n + '</span>').appendTo('#note_total_notice');
    } else {
        var num = parseInt(oldGlobalNumber) + n;
        if (num) $('<span class="badge badge-success">' + num + '</span>').appendTo('#note_total_notice');
    }
}

function addNote_item(icon, desc, btn) {
    var icon_ele = '<div class="label label-sm label-danger">' + icon + '</div>';
    var match = icon.match(/^https?:\/\/(?:[a-z\-]+\.)+[a-z]{2,6}(?:\/[^\/#?]+)+\.(?:jpe?g|gif|png)$/);
    if (match) {
        icon_ele = '<img src="' + icon + '"/>';
    }
    var btn_ele = '';
    if (btn) {
            btn_ele = '<a href="' + btn['full_url'] + '" class="btn btn-success btn-sm">' + btn['name'] + '</a>';
    }
    $('#note_nav_tabs_item').append('<li>'+
        '<div class="col1">'+
        '<div class="cont">'+
        '<div class="cont-col1">'+
        icon_ele+
        '</div>'+
        '<div class="cont-col2">'+
        '<div class="desc">'+
        desc+
        '</div>'+
        '</div>'+
        '</div>'+
        '</div>'+
        '<div class="col2">'+
        btn_ele+
        '</div>'+
        '</li>');
}