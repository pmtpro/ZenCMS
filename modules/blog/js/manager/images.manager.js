$( document ).ready(function() {
    var postID = $('input#post-id').val();

    $('.nav-tabs a[data-toggle="tab"]').click(function(e) {
        if ($(e.target).attr('href') == '#images') {
            get_list_images(postID);
        }
    });
    $('#reload-list-images').click(function() {
        $('#list-images').html('Loading...').delay( 80000);
        get_list_images(postID);
    });

    $('#action-upload-images').click(function(e){
        var btn = $(this);
        btn.button('loading');
        upload_images(postID, function() {
            btn.button('reset');
        }, function(xhr) {
            if (xhr.responseText) {
                //$('html').html(xhr.responseText);
                var arrResponse = $.parseJSON(xhr.responseText);
                var successRes = arrResponse['data'];
                if (arrResponse['status'] == 1) {
                    $('form#form-upload-image textarea').val("");
                    get_list_images(postID);
                }
            }
        }, function() {
            btn.button('reset');
        });
    });
});

function upload_images(postID, funcSuccessCallback, funcCompleteCallback, funcErrorCallback) {
    $('form#form-upload-image').ajaxSubmit({
        type: 'post',
        url: 'admin/general/modulescp?appFollow=blog/manager/images&id=' + postID,
        data: {
            'submit-upload' : 1,
            'is-ajax-request' : '1',
            'request-token-upload' : $("input#request-token-upload").val()
        },
        cache: false,
        crossDomain: true,
        contentType: false,
        processData: false,
        success: funcSuccessCallback,
        complete: funcCompleteCallback,
        error: funcErrorCallback
    });
}

function image_request_action(postID, url) {
    $('form#background-form').ajaxSubmit({
        type: 'post',
        url: url,
        data: {
            'is-ajax-request' : '1',
            'request-token-upload' : $("input#request-token-upload").val()
        },
        cache: false,
        crossDomain: true,
        contentType: false,
        processData: false,
        complete: function(xhr) {
            if (xhr.responseText) {
                var arrResponse = $.parseJSON(xhr.responseText);
                var successRes = arrResponse['data'];
                if (arrResponse['status'] == 1 && successRes == 1) {
                    get_list_images(postID);
                }
            }
        }
    });
}

function get_list_images(postID) {
    var btnReload = $('#reload-list-images span');
    btnReload.addClass("fa-spin").delay(500).queue(function(){
        $('form#background-form').ajaxSubmit({
            type: 'post',
            url: 'admin/general/modulescp?appFollow=blog/manager/images&id=' + postID,
            data: {
                'get-list-images' : 1,
                'is-ajax-request' : '1',
                'request-token-upload' : $("input#request-token-upload").val()
            },
            cache: false,
            crossDomain: true,
            contentType: false,
            processData: false,
            success: function() {},
            complete: function(xhr) {
                if (xhr.responseText) {
                    var arrResponse = $.parseJSON(xhr.responseText);
                    var successRes = arrResponse['data'];
                    if (arrResponse['status'] == 1) {
                        display_list_images(postID, successRes);
                    }
                }
            }
        });
        $(this).removeClass('fa-spin').dequeue();
    });
    //alert(JSON.stringify(out));
}

function display_list_images(postID, images) {
    var out = '';
    var i = 0;
    var numCol = 4;//col-lg-3
    var colName = 12/numCol;
    var numItem = images.length;
    $.each(images, function(k, img) {
        i++;
        if (i == 1 || i%numCol == 1) {
            out += '<div class="row">';
        }
        out += '<div class="col-lg-' + colName + '"><div class="thumbnail"><a href="' + img['full_url'] + '" target="_blank"><img src="' + img['full_url'] + '" style="max-height: 100px"/></a>';
        out += '<div class="caption text-center">';
        out += '<div class="btn-group">';
        out += '<a class="btn btn-success btn-sm copy-short-url" data-clipboard-text="' + img['short_url'] + '">Copy</a>';
        out += '<button class="btn btn-sm dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>';
        out += '<ul class="dropdown-menu image-actions">';

        $.each(img['actions'], function(k2, item) {
            if (k2 == 0) {
                out += '<li><a href="' + img['full_url'] + '" target="_blank"><span class="' + item['icon'] + '"></span> ' + item['name'] + '</a></li>';
            } else {
                if (item['divider']) {
                    out += '<li class="divider"></li>';
                }
                out += '<li><a onclick="image_request_action(' + postID + ', \'' + item["full_url"] + '\')" style="cursor: pointer;" data-href="' + item['full_url'] + '"><span class="' + item['icon'] + '"></span> ' + item['name'] + '</a></li>';
            }
        });

        out += '</ul>';
        out += '</div>';
        out += '</div>';
        out += '</div></div>';
        if (i == numItem || i%numCol == 0) {
            out += '</div>';
        }
    });
    if (!out) {
        out = 'No images';
    }
    $('#list-images').html(out).delay( 80000);
}