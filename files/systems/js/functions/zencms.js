
function ajaxUploadFile(zenOptions) {
    if (!zenOptions['input_id']) {
        return false;
    }
    input_id = zenOptions['input_id'];
    var request_token = zenOptions['token'];
    var fileName = zenOptions['file_name'];

    var bar = $(zenOptions['process']['bar']);
    var percent = $(zenOptions['process']['percent']);
    var progress = $(zenOptions['process']['percentBar']);
    var result = $(zenOptions['result']);
    var fileInput = $('input#' + input_id);
    var ext = fileInput.val().split('.').pop().toLowerCase();
    if (input_id == 'input-upload-icon-url') {
        if (!fileInput.val().match(/^https?:\/\//i)) {
            return false;
        }
    }
    if($.inArray(ext, ['gif','png','jpg','jpeg', 'bmp']) != -1) {
        progress.css('display', 'block');
        $('form#post-editor').ajaxSubmit({
            type: 'post',
            data: {
                'submit-save' : 1,
                'is-ajax-request' : '1',
                'input-request-token': request_token,
                'input-icon-name': fileName,
                'input-upload-icon-keep-ratio': $('input#input-upload-icon-keep-ratio:checked').val(),
                'input-upload-icon-resize': $('select#input-upload-icon-resize').val(),
                'response[]': 'input-icon'
            },
            cache: false,
            crossDomain: true,
            contentType: false,
            processData: false,
            beforeSubmit: function (formData) {
                $.each(formData, function (i, obj) {
                    if (obj != null) {
                        if (obj.name != input_id) {
                            delete (formData[i].type);
                            delete (formData[i].name);
                            delete (formData[i].value);
                        }
                    }
                });
                delete (formData['undefined']);
            },
            beforeSend: function() {
                var percentVal = '0%';
                bar.css("width", percentVal).attr("title", percentVal).attr("data-percent", percentVal).attr("data-original-title", percentVal);
                percent.html(percentVal);
            },
            uploadProgress: function(event, position, total, percentComplete) {
                var percentVal = percentComplete + '%';
                bar.css("width", percentVal).attr("title", percentVal).attr("data-percent", percentVal).attr("data-original-title", percentVal);
                percent.html(percentVal);
            },
            success: function() {
                var percentVal = '100%';
                bar.css("width", percentVal).attr("title", percentVal).attr("data-percent", percentVal).attr("data-original-title", percentVal);
                percent.html(percentVal);
            },
            complete: function(xhr) {
                result.css('display', 'block');
                if (xhr.responseText) {
                    var arrResponse = $.parseJSON(xhr.responseText);
                    result.html("<img src=\"/files/posts/images/" + arrResponse['input-icon'] + "\"/>\n<input type=\"hidden\" name=\"input-icon\" value=\"" + arrResponse['input-icon'] + "\"/>");
                } else result.html('Lỗi');
                progress.css('display', 'none');
                if (input_id == 'input-upload-icon') {
                    fileInput.replaceWith( fileInput = fileInput.clone( true ) );
                } else fileInput.val('');
            }
        });
    }
}