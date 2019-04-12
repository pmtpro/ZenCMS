function is_free_input(inputObj) {
    if (inputObj.attr("readonly") != 'readonly' || !inputObj.val()) {
        return true;
    }
    return false;
}

$( document ).ready(function() {
    var icon_auto_save = 'icon-repeat icon-spin';
    var icon_lock = 'icon-lock';
    var icon_unlock = 'icon-unlock';
    var icon_spin = 'icon-spinner icon-spin';
    var obj_icon_action_url = $('a#action-edit-url i');
    var obj_icon_action_title = $('a#action-edit-title i');
    var obj_icon_auto_save = $('a#action-auto-save i');

    var obj_request_token = $('input#input-request-token');
    var obj_action_auto_save = $('a#action-auto-save');
    var obj_action_url = $('a#action-edit-url');
    var obj_action_title = $('a#action-edit-title');
    var obj_name = $('input#input-name');
    var obj_url = $('input#input-url');
    var obj_title = $('input#input-title');
    var obj_keyword = $('textarea#input-keyword');
    var obj_des = $('textarea#input-des');
    var obj_submit_save = $('#submit-save');
    var obj_submit_public = $('#submit-public');
    var arrResponse;

    var function_update_note = function(input) {
        switch (input) {
            case 'title':
                var title_len = obj_title.val().length;
                if (title_len < 20 || title_len > 65) {
                    $('#note-title').removeClass('status-success').addClass('status-error');
                } else {
                    $('#note-title').removeClass('status-error').addClass('status-success');
                }
                $('#note-title').html('Length is: ' + title_len);
                break;
            case 'keyword':
                var keyword_hash = obj_keyword.val().split(',');
                keyword_hash = keyword_hash.filter(function(v){return $.trim(v)!==''});
                if (keyword_hash.length > 10) {
                    $('#note-keyword').removeClass('status-success').addClass('status-error');
                } else {
                    $('#note-keyword').removeClass('status-error').addClass('status-success');
                }
                $('#note-keyword').html('Number keyword: ' + keyword_hash.length);
                break;
            case 'des':
                var des_len = obj_des.val().length;
                if (des_len < 140 || des_len > 160) {
                    $('#note-des').removeClass('status-success').addClass('status-error');
                } else {
                    $('#note-des').removeClass('status-error').addClass('status-success');
                }
                $('#note-des').html('Length is: ' + des_len);
                break;
        }
    }
    function_update_note('title');
    function_update_note('keyword');
    function_update_note('des');
    obj_title.on('input', function(e) {
        function_update_note('title');
    });
    obj_des.on('input', function(e) {
        function_update_note('des');
    });
    obj_keyword.on('input', function(e) {
        function_update_note('keyword');
    });

    /**
     * start alert "saving"
     * @type {number}
     */
    var timeStartSave = 9000;//4s
    /**
     * auto save
     * @type {number}
     */
    var timeSave = timeStartSave + 1000;//5s
    var old_icon_class = obj_icon_auto_save.attr('class');
    $.timer(timeStartSave, function(){
        obj_icon_auto_save.attr('class', icon_auto_save);
    });
    $.timer(timeSave, function() {
        ajaxAutoSave(function(result){
            obj_icon_auto_save.attr('class', old_icon_class);
        })
    });
    obj_submit_save.click(function(){
        obj_submit_save.val('Đang lưu...');
        obj_submit_save.attr('disable', 'disable');
    });
    obj_submit_public.click(function(){
        obj_submit_public.val('Publishing...');
        obj_submit_public.attr('disable', 'disable');
    });
    /**
     * gen title, url
     */
    obj_name.on('input',function(e){
        if (is_free_input(obj_title)) {
            obj_icon_action_title.attr('class', icon_unlock);
            obj_title.removeAttr("readonly");
            obj_title.val(obj_name.val());
            function_update_note('title');
        }
        if (is_free_input(obj_url)) {
            obj_icon_action_url.attr('class', icon_spin);
            $.ajax({
                type: "POST",
                data: {
                    'input-url': obj_name.val(),
                    'response[]': 'input-url',
                    'is-ajax-request': 1,
                    'submit-save': 1,
                    'input-request-token': obj_request_token.val()
                },
                dataType:"text",
                success:function(data){
                    arrResponse = $.parseJSON(data);
                    obj_url.removeAttr("readonly");
                    obj_url.val(arrResponse['success']['input-url']);
                    obj_icon_action_url.attr('class', icon_unlock);
                }
            });
        }
    });

    obj_action_url.click(function(){
        if ( obj_url.attr("readonly") == 'readonly') {
            obj_url.removeAttr("readonly");
            obj_icon_action_url.attr('class', icon_unlock);
        } else {
            obj_url.attr("readonly", "readonly");
            obj_icon_action_url.attr('class', icon_lock);
        }
    });
    obj_action_title.click(function(){
        if (obj_title.attr("readonly") == 'readonly') {
            obj_title.removeAttr("readonly");
            obj_icon_action_title.attr('class', icon_unlock);
        } else {
            obj_title.attr("readonly", "readonly");
            obj_icon_action_title.attr('class', icon_lock);
        }
    });

    $('#input-upload-icon').on('change', function () {
        upload_icon('input-upload-icon');
    });
    $('#action-upload-icon-url').click(function(){
        upload_icon('input-upload-icon-url');
    });
});

function ajaxAutoSave(funcSuccess, funcError) {
    for ( instance in CKEDITOR.instances )
        CKEDITOR.instances[instance].updateElement();
    var data = $('form#post-editor').serializeArray(); // convert form to array
    data.push({name: 'is-ajax-request', value: 1});
    data.push({name: 'submit-save', value: 1});
    $.ajax({
        type: "POST",
        data: $.param(data),
        dataType:"text",
        success: function(result) {
            funcSuccess(result);
        },
        error: function() {
            funcError();
        }
    });
}

function upload_icon(input_id) {
    if (!input_id) input_id = 'input-upload-icon';
    var obj_request_token = $('input#input-request-token');
    var obj_url = $('input#input-url');
    var bar = $('#upload-icon-bar');
    var percent = $('#upload-icon-percent');
    var progress = $('#upload-icon-progress');
    var result = $('#upload-icon-result');
    var fileInput = $('input#' + input_id);
    var ext = fileInput.val().split('.').pop().toLowerCase();
    if (input_id == 'input-upload-icon-url') {
        if (!fileInput.val().match(/^https?:\/\//i)) return false;
    }
    if($.inArray(ext, ['gif','png','jpg','jpeg', 'bmp']) != -1) {
        progress.css('display', 'block');
        $('form#post-editor').ajaxSubmit({
            type: 'post',
            data: {
                'submit-save' : 1,
                'is-ajax-request' : '1',
                'input-request-token': obj_request_token.val(),
                'input-icon-name': obj_url.val(),
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
                    var successRes = arrResponse['success'];
                    if (typeof arrResponse['error'] === 'undefined') {
                        result.html("<img src=\"/files/posts/images/" + successRes['input-icon'] + "\"/>\n<input type=\"hidden\" name=\"input-icon\" value=\"" + successRes['input-icon'] + "\"/>");
                    } else {
                        var displayError = '';
                        $.each(arrResponse['error'], function(index, val){
                            displayError += val + '<br/>';
                        });
                        result.html(displayError);
                    }
                }
                progress.css('display', 'none');
                if (input_id == 'input-upload-icon') {
                    fileInput.replaceWith( fileInput = fileInput.clone( true ) );
                } else fileInput.val('');
            }
        });
    }
}

