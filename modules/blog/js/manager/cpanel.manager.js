function htmlEncode(value){
    //create a in-memory div, set it's inner text(which jQuery automatically encodes)
    //then grab the encoded contents back out.  The div never exists on the page.
    return $('<div/>').text(value).html();
}
$( document ).ready(function() {
$('a.requestAct').click(function() {
    var request_url = $(this).data('url');
    var id = $(this).data('id');
    var iconFinded = $(this).find('i');
    var old_icon_class = iconFinded.attr('class');
    iconFinded.attr('class', 'icon-spinner icon-spin');
    $.ajax({
        type:"POST",
        cache:false,
        url:request_url,
        data:{ foo : 'bar', bar : 'foo' },    // multiple data sent using ajax
        success: function (html) {
            $('#' + id).modal('show');
            $( "#" + id + " .modal-body" ).html( "<p>" + htmlEncode(html) + "</p>" );
            iconFinded.attr('class', old_icon_class);
        }
    });
    return false;
});
});
